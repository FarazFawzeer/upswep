<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Low Stock Report
     * Shows products where stock_qty <= low_stock_alert_qty (per-product alert only)
     */
    public function lowStock(Request $request)
    {
        $products = Product::with('category')
            ->where('status', 1)
            ->whereColumn('stock_qty', '<=', 'low_stock_alert_qty')
            ->orderBy('stock_qty', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.reports.low_stock', compact('products'));
    }
}
