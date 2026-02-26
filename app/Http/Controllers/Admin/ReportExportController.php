<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use App\Exports\StockSummaryExport;
use App\Exports\LowStockExport;
use App\Exports\OutOfStockExport;
use App\Exports\StockMovementsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportExportController extends Controller
{
    // -------------------------
    // Stock Summary
    // -------------------------
    public function stockSummaryPdf(Request $request)
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
            ->get();

        $pdf = Pdf::loadView('admin.reports.pdf.stock_summary', [
            'products' => $products,
            'q' => $q,
            'generatedAt' => now(),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('stock-summary-' . now()->format('Y-m-d_H-i') . '.pdf');
    }

    public function stockSummaryExcel(Request $request)
    {
        return Excel::download(new StockSummaryExport($request), 'stock-summary-' . now()->format('Y-m-d_H-i') . '.xlsx');
    }

    // -------------------------
    // Low Stock
    // -------------------------
    public function lowStockPdf(Request $request)
    {
        $q = $request->q;

        $products = Product::with('category')
            ->where('status', 1)
            ->whereColumn('stock_qty', '<=', 'low_stock_alert_qty')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                        ->orWhere('barcode', 'like', "%{$q}%")
                        ->orWhere('brand', 'like', "%{$q}%");
                });
            })
            ->orderBy('stock_qty', 'asc')
            ->get();

        $pdf = Pdf::loadView('admin.reports.pdf.low_stock', [
            'products' => $products,
            'q' => $q,
            'generatedAt' => now(),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('low-stock-' . now()->format('Y-m-d_H-i') . '.pdf');
    }

    public function lowStockExcel(Request $request)
    {
        return Excel::download(new LowStockExport($request), 'low-stock-' . now()->format('Y-m-d_H-i') . '.xlsx');
    }

    // -------------------------
    // Out of Stock
    // -------------------------
    public function outOfStockPdf(Request $request)
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
            ->get();

        $pdf = Pdf::loadView('admin.reports.pdf.out_of_stock', [
            'products' => $products,
            'q' => $q,
            'generatedAt' => now(),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('out-of-stock-' . now()->format('Y-m-d_H-i') . '.pdf');
    }

    public function outOfStockExcel(Request $request)
    {
        return Excel::download(new OutOfStockExport($request), 'out-of-stock-' . now()->format('Y-m-d_H-i') . '.xlsx');
    }

    // -------------------------
    // Stock Movements
    // -------------------------
    public function stockMovementsPdf(Request $request)
    {
        $q     = $request->q;
        $type  = $request->type;
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
            ->get();

        $pdf = Pdf::loadView('admin.reports.pdf.stock_movements', [
            'movements' => $movements,
            'q' => $q,
            'type' => $type,
            'start' => $start,
            'end' => $end,
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('stock-movements-' . now()->format('Y-m-d_H-i') . '.pdf');
    }

    public function stockMovementsExcel(Request $request)
    {
        return Excel::download(new StockMovementsExport($request), 'stock-movements-' . now()->format('Y-m-d_H-i') . '.xlsx');
    }
}