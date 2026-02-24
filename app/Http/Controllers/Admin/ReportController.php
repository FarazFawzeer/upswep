<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

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

    /**
     * Step 14A: Daily Sales (date filter)
     */
    public function dailySales(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $dateObj = Carbon::parse($date);

        $sales = Sale::with(['createdBy', 'customer'])
            ->whereDate('sale_date', $dateObj)
            ->latest('sale_date')
            ->paginate(15)
            ->withQueryString();

        $summary = Sale::whereDate('sale_date', $dateObj)
            ->selectRaw('
                COUNT(*) as bills,
                COALESCE(SUM(sub_total),0) as sub_total,
                COALESCE(SUM(discount_total),0) as discount_total,
                COALESCE(SUM(tax_total),0) as tax_total,
                COALESCE(SUM(grand_total),0) as grand_total
            ')
            ->first();

        return view('admin.reports.sales_daily', compact('sales', 'date', 'summary'));
    }

    /**
     * Step 14B: Weekly/Monthly Sales (range + quick presets)
     */
    public function salesSummary(Request $request)
    {
        $mode = $request->input('mode', 'weekly'); // weekly | monthly

        // date range defaults
        $start = $request->input('start');
        $end   = $request->input('end');

        if (!$start || !$end) {
            if ($mode === 'monthly') {
                $start = now()->startOfMonth()->toDateString();
                $end   = now()->endOfMonth()->toDateString();
            } else {
                $start = now()->startOfWeek()->toDateString();
                $end   = now()->endOfWeek()->toDateString();
            }
        }

        $startObj = Carbon::parse($start)->startOfDay();
        $endObj   = Carbon::parse($end)->endOfDay();

        $rows = Sale::whereBetween('sale_date', [$startObj, $endObj])
            ->selectRaw('DATE(sale_date) as day,
                        COUNT(*) as bills,
                        COALESCE(SUM(grand_total),0) as total,
                        COALESCE(SUM(discount_total),0) as discount,
                        COALESCE(SUM(tax_total),0) as tax')
            ->groupBy('day')
            ->orderBy('day', 'desc')
            ->paginate(20)
            ->withQueryString();

        $totals = Sale::whereBetween('sale_date', [$startObj, $endObj])
            ->selectRaw('
                COUNT(*) as bills,
                COALESCE(SUM(sub_total),0) as sub_total,
                COALESCE(SUM(discount_total),0) as discount_total,
                COALESCE(SUM(tax_total),0) as tax_total,
                COALESCE(SUM(grand_total),0) as grand_total
            ')
            ->first();

        return view('admin.reports.sales_summary', compact('rows', 'mode', 'start', 'end', 'totals'));
    }

    /**
     * Step 14C: Sales by cashier (range filter)
     */
    public function salesByCashier(Request $request)
    {
        $start = $request->input('start', now()->startOfMonth()->toDateString());
        $end   = $request->input('end', now()->endOfMonth()->toDateString());

        $startObj = Carbon::parse($start)->startOfDay();
        $endObj   = Carbon::parse($end)->endOfDay();

        // If you store cashier in created_by, use created_by.
        // If you truly store cashier_id in sales table, replace created_by with cashier_id.
        $rows = Sale::whereBetween('sale_date', [$startObj, $endObj])
            ->selectRaw('created_by as cashier_id,
                        COUNT(*) as bills,
                        COALESCE(SUM(grand_total),0) as total,
                        COALESCE(SUM(discount_total),0) as discount,
                        COALESCE(SUM(tax_total),0) as tax')
            ->groupBy('cashier_id')
            ->orderByDesc('total')
            ->paginate(20)
            ->withQueryString();

        // Load cashier names
        $cashierIds = $rows->getCollection()->pluck('cashier_id')->filter()->unique()->values();
        $cashiers = User::whereIn('id', $cashierIds)->pluck('name', 'id');

        return view('admin.reports.sales_by_cashier', compact('rows', 'start', 'end', 'cashiers'));
    }

    /**
     * Step 14D: Best selling products (range filter)
     */
    public function bestSellingProducts(Request $request)
    {
        $start = $request->input('start', now()->startOfMonth()->toDateString());
        $end   = $request->input('end', now()->endOfMonth()->toDateString());

        $startObj = Carbon::parse($start)->startOfDay();
        $endObj   = Carbon::parse($end)->endOfDay();

        $rows = SaleItem::query()
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->whereBetween('sales.sale_date', [$startObj, $endObj])
            ->selectRaw('
                sale_items.product_id,
                sale_items.product_name,
                COALESCE(sale_items.barcode_snapshot, "") as barcode_snapshot,
                SUM(sale_items.qty) as total_qty,
                COALESCE(SUM(sale_items.line_total),0) as total_sales
            ')
            ->groupBy('sale_items.product_id', 'sale_items.product_name', 'sale_items.barcode_snapshot')
            ->orderByDesc('total_qty')
            ->paginate(20)
            ->withQueryString();

        return view('admin.reports.best_selling_products', compact('rows', 'start', 'end'));
    }

    public function profitReport(Request $request)
    {
        $start = $request->input('start', now()->startOfMonth()->toDateString());
        $end   = $request->input('end', now()->endOfMonth()->toDateString());

        $startObj = Carbon::parse($start)->startOfDay();
        $endObj   = Carbon::parse($end)->endOfDay();

        // ✅ Product-wise profit summary (based on sale_items snapshots)
        $rows = SaleItem::query()
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->whereBetween('sales.sale_date', [$startObj, $endObj])
            ->selectRaw('
            sale_items.product_id,
            sale_items.product_name,
            COALESCE(sale_items.barcode_snapshot, "") as barcode_snapshot,
            SUM(sale_items.qty) as total_qty,
            COALESCE(SUM(sale_items.unit_price * sale_items.qty),0) as revenue,
            COALESCE(SUM(sale_items.unit_cost * sale_items.qty),0) as cost,
            COALESCE(SUM((sale_items.unit_price - sale_items.unit_cost) * sale_items.qty),0) as profit
        ')
            ->groupBy('sale_items.product_id', 'sale_items.product_name', 'sale_items.barcode_snapshot')
            ->orderByDesc('profit')
            ->paginate(20)
            ->withQueryString();

        // ✅ Overall totals (for top cards)
        $totals = SaleItem::query()
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->whereBetween('sales.sale_date', [$startObj, $endObj])
            ->selectRaw('
            COALESCE(SUM(sale_items.unit_price * sale_items.qty),0) as revenue,
            COALESCE(SUM(sale_items.unit_cost * sale_items.qty),0) as cost,
            COALESCE(SUM((sale_items.unit_price - sale_items.unit_cost) * sale_items.qty),0) as profit
        ')
            ->first();

        return view('admin.reports.profit_report', compact('rows', 'totals', 'start', 'end'));
    }


    public function stockSummary(Request $request)
    {
        $q = $request->q;

        $products = Product::with('category')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                        ->orWhere('barcode', 'like', "%{$q}%")
                        ->orWhere('brand', 'like', "%{$q}%");
                });
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $totals = [
            'total_products' => Product::count(),
            'total_qty'      => (int) Product::sum('stock_qty'),
            'low_stock'      => (int) Product::whereColumn('stock_qty', '<=', 'low_stock_alert_qty')->count(),
            'out_of_stock'   => (int) Product::where('stock_qty', '<=', 0)->count(),
        ];

        return view('admin.reports.stock_summary', compact('products', 'q', 'totals'));
    }


    public function outOfStock(Request $request)
    {
        $q = $request->q;

        $products = Product::with('category')
            ->where('status', 1)
            ->where('stock_qty', '<=', 0)
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                        ->orWhere('barcode', 'like', "%{$q}%")
                        ->orWhere('brand', 'like', "%{$q}%");
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.reports.out_of_stock', compact('products', 'q'));
    }

    public function stockMovements(Request $request)
    {
        $q     = $request->q;
        $type  = $request->type;     // purchase / sale / adjustment / return
        $start = $request->start;
        $end   = $request->end;

        $movements = StockMovement::with(['product', 'createdBy'])
            ->when($q, function ($query) use ($q) {
                $query->whereHas('product', function ($p) use ($q) {
                    $p->where('name', 'like', "%{$q}%")
                        ->orWhere('barcode', 'like', "%{$q}%");
                });
            })
            ->when($type, fn($query) => $query->where('movement_type', $type))
            ->when($start, fn($query) => $query->whereDate('created_at', '>=', $start))
            ->when($end, fn($query) => $query->whereDate('created_at', '<=', $end))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $types = ['purchase', 'sale', 'adjustment', 'return'];

        return view('admin.reports.stock_movements', compact('movements', 'q', 'type', 'start', 'end', 'types'));
    }
}
