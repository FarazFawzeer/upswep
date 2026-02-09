<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $movements = StockMovement::with(['product', 'createdBy'])
            ->when($request->filled('product_id'), fn($q) => $q->where('product_id', $request->product_id))
            ->when($request->filled('movement_type'), fn($q) => $q->where('movement_type', $request->movement_type))
            ->when($request->filled('from'), fn($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->filled('to'), fn($q) => $q->whereDate('created_at', '<=', $request->to))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $products = Product::where('status', 1)
            ->orderBy('name')
            ->get(['id','name','barcode']);

        return view('admin.stock_movements.index', compact('movements', 'products'));
    }
}
