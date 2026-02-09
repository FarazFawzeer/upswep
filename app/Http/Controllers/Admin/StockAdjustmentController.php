<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{
    public function create()
    {
        $products = Product::where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name', 'barcode', 'stock_qty']);

        return view('admin.stock_adjustments.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'direction'  => ['required', 'in:in,out'], // in=+, out=-
            'qty'        => ['required', 'integer', 'min:1'],
            'reason'     => ['required', 'string', 'max:255'],
            'note'       => ['nullable', 'string', 'max:500'],
        ]);

        try {
            DB::transaction(function () use ($validated) {

                $product = Product::lockForUpdate()->findOrFail($validated['product_id']);

                $qty = (int) $validated['qty'];
                $qtyChange = $validated['direction'] === 'in' ? $qty : -$qty;

                // prevent negative stock
                if ($qtyChange < 0 && ($product->stock_qty + $qtyChange) < 0) {
                    abort(422, 'Stock cannot go below 0.');
                }

                // update stock
                $product->stock_qty = $product->stock_qty + $qtyChange;
                $product->save();

                // create stock movement record
                StockMovement::create([
                    'product_id'     => $product->id,
                    'movement_type'  => 'adjustment',
                    'qty_change'     => $qtyChange,
                    'reference_type' => 'manual',
                    'reference_id'   => null,
                    'note'           => trim($validated['reason'] . ($validated['note'] ? ' - ' . $validated['note'] : '')),
                    'created_by'     => auth()->id(),
                ]);
            });

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Stock adjusted successfully.']);
            }

            return redirect()->route('admin.stock-movements.index')->with('success', 'Stock adjusted successfully.');
        } catch (\Throwable $e) {

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

   
}
