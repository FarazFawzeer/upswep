<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PosController extends Controller
{
    public function index()
    {
        return view('pos.index');
    }

    // GET /pos/product-by-barcode?barcode=XXXX
    public function productByBarcode(Request $request)
    {
        $barcode = trim($request->barcode ?? '');

        if ($barcode === '') {
            return response()->json([
                'success' => false,
                'message' => 'Barcode is required.'
            ], 422);
        }

        $product = Product::with('category')
            ->where('status', 1)
            ->where('barcode', $barcode)
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found for this barcode.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'barcode' => $product->barcode,
                'category' => $product->category?->name,
                'selling_price' => (float) $product->selling_price,
                'stock_qty' => (int) $product->stock_qty,
            ]
        ]);
    }

    public function searchProducts(Request $request)
    {
        $q = trim($request->q ?? '');

        if ($q === '') {
            return response()->json(['success' => true, 'products' => []]);
        }

        $products = Product::with('category')
            ->where('status', 1)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('barcode', 'like', "%{$q}%")
                    ->orWhere('brand', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'products' => $products->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'barcode' => $p->barcode,
                    'category' => $p->category?->name,
                    'selling_price' => (float) $p->selling_price,
                    'stock_qty' => (int) $p->stock_qty,
                ];
            })->values()
        ]);
    }

    public function storeSale(Request $request)
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty'        => ['required', 'integer', 'min:1'],

            'discount_total' => ['nullable', 'numeric', 'min:0'],
            'tax_percent'    => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'customer_id'    => ['nullable', 'exists:customers,id'],
        ]);

        $discountTotal = (float)($validated['discount_total'] ?? 0);
        $taxPercent    = (float)($validated['tax_percent'] ?? 0);
        $paymentMethod = $validated['payment_method'] ?? 'cash';

        $result = DB::transaction(function () use ($validated, $discountTotal, $taxPercent, $paymentMethod) {

            // ✅ generate invoice no
            $datePart = now()->format('Ymd');
            $nextId = ((int) Sale::max('id')) + 1;
            $invoiceNo = 'INV-' . $datePart . '-' . str_pad((string)$nextId, 6, '0', STR_PAD_LEFT);

            // lock products
            $productIds = collect($validated['items'])->pluck('product_id')->unique()->values();
            $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

            $subTotal = 0;

            // compute subtotal with DB prices + validate stock
            foreach ($validated['items'] as $row) {
                $pid = (int)$row['product_id'];
                $qty = (int)$row['qty'];

                $product = $products->get($pid);

                if (!$product) {
                    abort(422, "Product not found (ID: {$pid})");
                }

                if ($product->stock_qty < $qty) {
                    abort(422, "Not enough stock for {$product->name}. Available: {$product->stock_qty}");
                }

                $subTotal += ((float)$product->selling_price) * $qty;
            }

            $afterDiscount = max($subTotal - $discountTotal, 0);
            $taxTotal = $afterDiscount * ($taxPercent / 100);
            $grandTotal = $afterDiscount + $taxTotal;

            // ✅ create sale (your column names)
            $sale = Sale::create([
                'invoice_no'     => $invoiceNo,
                'sale_date'      => now(),
                'customer_id'    => $validated['customer_id'] ?? null,

                'sub_total'      => $subTotal,
                'discount_total' => $discountTotal,
                'tax_percent'    => $taxPercent,
                'tax_total'      => $taxTotal,
                'grand_total'    => $grandTotal,

                'payment_method' => $paymentMethod,
                'status'         => 'completed',
                'created_by'     => auth()->id(),
            ]);

            // ✅ create items + reduce stock + stock movement
            foreach ($validated['items'] as $row) {
                $pid = (int)$row['product_id'];
                $qty = (int)$row['qty'];

                $product = $products->get($pid);

                $unitPrice = (float)$product->selling_price;
                $unitCost  = (float)$product->cost_price;

                // We are not doing item-level discount now, keep 0
                $discountAmount = 0;

                $lineTotal = ($unitPrice * $qty) - $discountAmount;

                SaleItem::create([
                    'sale_id'          => $sale->id,
                    'product_id'       => $pid,
                    'product_name'     => $product->name,
                    'barcode_snapshot' => $product->barcode,

                    'qty'              => $qty,
                    'unit_price'       => $unitPrice,
                    'unit_cost'        => $unitCost,
                    'discount_amount'  => $discountAmount,
                    'line_total'       => $lineTotal,
                ]);

                // reduce stock
                $product->stock_qty = $product->stock_qty - $qty;
                $product->save();

                // stock movement record
                StockMovement::create([
                    'product_id'     => $pid,
                    'movement_type'  => 'sale',
                    'qty_change'     => -$qty,
                    'reference_type' => 'sale',
                    'reference_id'   => $sale->id,
                    'note'           => 'POS Sale: ' . $invoiceNo,
                    'created_by'     => auth()->id(),
                ]);
            }

            return [
                'sale_id'     => $sale->id,
                'invoice_no'  => $invoiceNo,
                'grand_total' => $grandTotal,
            ];
        });

        return response()->json([
            'success'    => true,
            'message'    => 'Sale completed successfully.',
            'sale_id'    => $result['sale_id'],
            'invoice_no' => $result['invoice_no'],
            'total'      => number_format($result['grand_total'], 2),
        ]);
    }
}
