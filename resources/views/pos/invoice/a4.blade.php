@extends('layouts.vertical', ['subtitle' => 'Invoice'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Invoice', 'subtitle' => $sale->invoice_no])

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="card-title mb-0">Invoice</h5>
            <p class="card-subtitle mb-0">Invoice No: <strong>{{ $sale->invoice_no }}</strong></p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('pos.invoice.pdf', $sale->id) }}" class="btn btn-outline-secondary btn-sm">
                <iconify-icon icon="solar:file-download-outline"></iconify-icon> PDF
            </a>

            <a href="{{ route('pos.invoice.thermal', $sale->id) }}" class="btn btn-outline-dark btn-sm" target="_blank">
                <iconify-icon icon="solar:printer-outline"></iconify-icon> Thermal
            </a>

            <button class="btn btn-primary btn-sm" onclick="window.print()">
                <iconify-icon icon="solar:printer-outline"></iconify-icon> Print
            </button>
        </div>
    </div>

    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <h6 class="mb-1">Upswep Dress Shop</h6>
                <div class="text-muted small">Colombo, Sri Lanka</div>
            </div>

            <div class="col-md-6 text-md-end">
                <div><strong>Date:</strong> {{ optional($sale->sale_date)->format('d M Y, h:i A') }}</div>
                <div><strong>Cashier:</strong> {{ $sale->createdBy?->name ?? '—' }}</div>
                <div><strong>Payment:</strong> {{ strtoupper($sale->payment_method ?? 'cash') }}</div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Item</th>
                        <th style="width:90px;" class="text-center">Qty</th>
                        <th style="width:140px;" class="text-end">Unit Price</th>
                        <th style="width:140px;" class="text-end">Line Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $it)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $it->product_name }}</div>
                                <div class="text-muted small">{{ $it->barcode_snapshot ?? '—' }}</div>
                            </td>
                            <td class="text-center">{{ $it->qty }}</td>
                            <td class="text-end">Rs {{ number_format($it->unit_price, 2) }}</td>
                            <td class="text-end">Rs {{ number_format($it->line_total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row justify-content-end mt-3">
            <div class="col-md-5">
                <div class="border rounded p-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Subtotal</span>
                        <strong>Rs {{ number_format($sale->sub_total, 2) }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <span class="text-muted">Discount</span>
                        <strong>- Rs {{ number_format($sale->discount_total, 2) }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <span class="text-muted">Tax</span>
                        <strong>Rs {{ number_format($sale->tax_total, 2) }}</strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Grand Total</span>
                        <strong class="fs-5">Rs {{ number_format($sale->grand_total, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center text-muted mt-4 small">
            Thank you for your purchase!
        </div>
    </div>
</div>

<style>
@media print {
    .app-sidebar, .topbar, .page-title-box, .btn, .card-header { display: none !important; }
    .card { border: none !important; box-shadow: none !important; }
}
</style>
@endsection
