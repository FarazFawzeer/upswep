<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;
use PDF; // dompdf facade

class PosInvoiceController extends Controller
{
    public function show(Sale $sale)
    {
        $sale->load(['items.product', 'customer', 'createdBy']); // createdBy exists in your model

        return view('pos.invoice.a4', compact('sale'));
    }

    public function thermal(Sale $sale)
    {
        $sale->load(['items.product', 'customer', 'createdBy']);

        return view('pos.invoice.thermal', compact('sale'));
    }

    public function pdf(Sale $sale)
    {
        $sale->load(['items.product', 'customer', 'createdBy']);

        $pdf = PDF::loadView('pos.invoice.a4_pdf', compact('sale'))
            ->setPaper('a4', 'portrait');

        return $pdf->download($sale->invoice_no . '.pdf');
    }
}
