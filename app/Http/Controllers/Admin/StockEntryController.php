<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockEntry;
use App\Models\StockEntryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockEntryController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;

        $entries = StockEntry::with(['supplier', 'createdBy', 'items'])
            ->when($q, function ($query) use ($q) {
                $query->where('entry_no', 'like', "%{$q}%")
                    ->orWhereDate('entry_date', $q)
                    ->orWhereHas('supplier', fn($s) => $s->where('name', 'like', "%{$q}%"));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();



        return view('admin.stock_entries.index', compact('entries', 'q'));
    }

    public function create()
    {
        $suppliers = Supplier::where('status', 1)->orderBy('name')->get();
        $products = Product::where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name', 'barcode', 'cost_price'])
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'barcode' => $p->barcode,
                    'cost_price' => (float) $p->cost_price,
                ];
            })
            ->values();

        return view('admin.stock_entries.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'entry_date'  => ['required', 'date'],
            'notes'       => ['nullable', 'string', 'max:500'],

            'items'                 => ['required', 'array', 'min:1'],
            'items.*.product_id'    => ['required', 'exists:products,id'],
            'items.*.qty'           => ['required', 'integer', 'min:1'],
            'items.*.unit_cost'     => ['nullable', 'numeric', 'min:0'],
            'items.*.line_total'    => ['nullable', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($validated) {

            // ✅ entry number (simple)
            $entryNo = 'SE-' . now()->format('Ymd-His');

            $entry = StockEntry::create([
                'supplier_id' => $validated['supplier_id'] ?? null,
                'entry_no'    => $entryNo,
                'entry_date'  => $validated['entry_date'],
                'notes'       => $validated['notes'] ?? null,
                'created_by'  => auth()->id(),
            ]);

            foreach ($validated['items'] as $row) {
                $product = Product::lockForUpdate()->findOrFail($row['product_id']);

                $qty = (int) $row['qty'];

                // ✅ if unit_cost not provided, use product cost_price
                $unitCost = (isset($row['unit_cost']) && $row['unit_cost'] !== null && $row['unit_cost'] !== '')
                    ? (float) $row['unit_cost']
                    : (float) $product->cost_price;

                $lineTotal = $qty * $unitCost;

                StockEntryItem::create([
                    'stock_entry_id' => $entry->id,
                    'product_id'     => $product->id,
                    'qty'            => $qty,
                    'unit_cost'      => $unitCost,
                    'line_total'     => $lineTotal,
                ]);

                // ✅ Increase stock
                $product->increment('stock_qty', $qty);

                // ✅ Stock movement log
                \App\Models\StockMovement::create([
                    'product_id'      => $product->id,
                    'movement_type'   => 'purchase',
                    'qty_change'      => $qty,
                    'reference_type'  => 'stock_entry',
                    'reference_id'    => $entry->id,
                    'note'            => $entry->entry_no,
                    'created_by'      => auth()->id(),
                ]);
            }
        });

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Stock entry saved successfully.']);
        }

        return redirect()->route('admin.stock-entries.index')->with('success', 'Stock entry saved successfully.');
    }


    public function show(StockEntry $stock_entry)
    {
        $stock_entry->load(['supplier', 'items.product']);

        return view('admin.stock_entries.show', ['entry' => $stock_entry]);
    }
}
