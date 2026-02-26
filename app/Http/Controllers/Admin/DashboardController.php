<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockEntry;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // ✅ Cards
        $todaySalesAmount = (float) Sale::whereDate('sale_date', $today)->sum('grand_total');
        $todaySalesCount  = (int)   Sale::whereDate('sale_date', $today)->count();

        $totalProducts = (int) Product::count();
        $lowStockCount = (int) Product::where('status', 1)
            ->whereColumn('stock_qty', '<=', 'low_stock_alert_qty')
            ->count();

        $outOfStockCount = (int) Product::where('status', 1)->where('stock_qty', '<=', 0)->count();

        $totalCustomers = class_exists(Customer::class) ? (int) Customer::count() : 0;
        $totalSuppliers = (int) Supplier::count();

        $thisMonthSalesAmount = (float) Sale::whereBetween('sale_date', [$startOfMonth, $endOfMonth])->sum('grand_total');

        // ✅ % from last month (example for sales amount)
        $lastMonthStart = Carbon::now()->subMonthNoOverflow()->startOfMonth();
        $lastMonthEnd   = Carbon::now()->subMonthNoOverflow()->endOfMonth();
        $lastMonthSalesAmount = (float) Sale::whereBetween('sale_date', [$lastMonthStart, $lastMonthEnd])->sum('grand_total');

        $salesChangePercent = 0;
        if ($lastMonthSalesAmount > 0) {
            $salesChangePercent = (($thisMonthSalesAmount - $lastMonthSalesAmount) / $lastMonthSalesAmount) * 100;
        }

        // ✅ Chart: sales by day (last 14 days)
        $from = Carbon::now()->subDays(13)->startOfDay();
        $to   = Carbon::now()->endOfDay();

        $rawSalesByDay = Sale::selectRaw('DATE(sale_date) as d, SUM(grand_total) as total')
            ->whereBetween('sale_date', [$from, $to])
            ->groupBy('d')
            ->orderBy('d')
            ->pluck('total', 'd')
            ->toArray();

        $chartLabels = [];
        $chartSeries = [];

        for ($i = 0; $i < 14; $i++) {
            $date = Carbon::now()->subDays(13 - $i)->toDateString();
            $chartLabels[] = Carbon::parse($date)->format('d M');
            $chartSeries[] = (float) ($rawSalesByDay[$date] ?? 0);
        }

        // ✅ Tables
        $latestUsers = User::latest()->take(5)->get();

        $recentSales = Sale::with(['customer', 'cashier'])
            ->latest('sale_date')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'todaySalesAmount',
            'todaySalesCount',
            'totalProducts',
            'lowStockCount',
            'outOfStockCount',
            'totalCustomers',
            'totalSuppliers',
            'salesChangePercent',
            'chartLabels',
            'chartSeries',
            'latestUsers',
            'recentSales'
        ));
    }
}