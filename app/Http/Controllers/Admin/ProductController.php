<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('status', 1)->orderBy('name')->get();
        $suppliers  = Supplier::where('status', 1)->orderBy('name')->get();

        $query = Product::query()->with(['category', 'supplier']);

        // Filters
        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")
                    ->orWhere('barcode', 'like', "%{$q}%")
                    ->orWhere('brand', 'like', "%{$q}%")
                    ->orWhere('size', 'like', "%{$q}%")
                    ->orWhere('color', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('brand')) {
            $query->where('brand', 'like', "%{$request->brand}%");
        }

        if ($request->filled('size')) {
            $query->where('size', 'like', "%{$request->size}%");
        }

        if ($request->boolean('low_stock')) {
            // low_stock = stock_qty <= low_stock_alert_qty
            $query->whereColumn('stock_qty', '<=', 'low_stock_alert_qty');
        }

        $products = $query->orderByDesc('id')->paginate(10)->withQueryString();

        return view('admin.products.index', compact('products', 'categories', 'suppliers'));
    }

    public function create()
    {
        $categories = Category::where('status', 1)->orderBy('name')->get();
        $suppliers  = Supplier::where('status', 1)->orderBy('name')->get();

        return view('admin.products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],

            'name'        => ['required', 'string', 'max:255'],
            'brand'       => ['nullable', 'string', 'max:255'],
            'size'        => ['nullable', 'string', 'max:255'],
            'color'       => ['nullable', 'string', 'max:255'],

            'cost_price'  => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],

            'stock_qty'   => ['nullable', 'integer', 'min:0'],
            'low_stock_alert_qty' => ['nullable', 'integer', 'min:0'],

            'barcode'     => ['nullable', 'string', 'max:255', 'unique:products,barcode'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status'      => ['nullable', 'in:0,1'],
        ]);

        $barcode = $validated['barcode'] ?? $this->generateUniqueBarcode();

        $imagePath = null;
        if ($request->hasFile('image')) {
            // stored in: storage/app/public/products/...
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'supplier_id' => $validated['supplier_id'] ?? null,

            'name'        => $validated['name'],
            'brand'       => $validated['brand'] ?? null,
            'size'        => $validated['size'] ?? null,
            'color'       => $validated['color'] ?? null,

            'cost_price'  => $validated['cost_price'],
            'selling_price' => $validated['selling_price'],

            'stock_qty'   => $validated['stock_qty'] ?? 0,
            'low_stock_alert_qty' => $validated['low_stock_alert_qty'] ?? 5,

            'barcode'     => $barcode,
            'image'       => $imagePath,
            'status'      => (bool)($validated['status'] ?? 1),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully.',
                'data'    => $product,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('status', 1)->orderBy('name')->get();
        $suppliers  = Supplier::where('status', 1)->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],

            'name'        => ['required', 'string', 'max:255'],
            'brand'       => ['nullable', 'string', 'max:255'],
            'size'        => ['nullable', 'string', 'max:255'],
            'color'       => ['nullable', 'string', 'max:255'],

            'cost_price'  => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],

            'stock_qty'   => ['nullable', 'integer', 'min:0'],
            'low_stock_alert_qty' => ['nullable', 'integer', 'min:0'],

            'barcode'     => ['required', 'string', 'max:255', Rule::unique('products', 'barcode')->ignore($product->id)],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status'      => ['nullable', 'in:0,1'],
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        $product->fill([
            'category_id' => $validated['category_id'],
            'supplier_id' => $validated['supplier_id'] ?? null,

            'name'        => $validated['name'],
            'brand'       => $validated['brand'] ?? null,
            'size'        => $validated['size'] ?? null,
            'color'       => $validated['color'] ?? null,

            'cost_price'  => $validated['cost_price'],
            'selling_price' => $validated['selling_price'],

            'stock_qty'   => $validated['stock_qty'] ?? $product->stock_qty,
            'low_stock_alert_qty' => $validated['low_stock_alert_qty'] ?? $product->low_stock_alert_qty,

            'barcode'     => $validated['barcode'],
            'status'      => (bool)($validated['status'] ?? $product->status),
        ])->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully.',
                'data'    => $product,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully.',
        ]);
    }

    private function generateUniqueBarcode(): string
    {
        // Code128 can be alphanumeric, but scanners usually like numeric.
        // We'll generate 12-digit numeric and ensure unique.
        do {
            $barcode = (string) random_int(100000000000, 999999999999); // 12 digits
        } while (Product::where('barcode', $barcode)->exists());

        return $barcode;
    }


    public function show(Product $product)
    {
        $product->load(['category', 'supplier']);

        return view('admin.products.show', compact('product'));
    }

    public function printBarcodes(Request $request)
    {
        $query = Product::query()->where('status', 1);

        // print selected products (ids=1,2,3)
        if ($request->filled('ids')) {
            $ids = array_filter(explode(',', $request->ids));
            $query->whereIn('id', $ids);
        } else {
            // optional: support printing based on filters (same as index)
            if ($request->filled('category_id')) $query->where('category_id', $request->category_id);
            if ($request->filled('brand')) $query->where('brand', 'like', "%{$request->brand}%");
            if ($request->filled('size')) $query->where('size', 'like', "%{$request->size}%");
            if ($request->boolean('low_stock')) $query->whereColumn('stock_qty', '<=', 'low_stock_alert_qty');
            if ($request->filled('q')) {
                $q = trim($request->q);
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                        ->orWhere('barcode', 'like', "%{$q}%");
                });
            }
        }

        $products = $query->orderBy('name')->get();

        return view('admin.products.print-barcodes', compact('products'));
    }
}
