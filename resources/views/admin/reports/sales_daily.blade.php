@extends('layouts.vertical', ['subtitle' => 'Daily Sales'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Reports', 'subtitle' => 'Daily Sales'])

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-0">Daily Sales</h5>
            <p class="card-subtitle mb-0">Filter sales by date.</p>
        </div>
        <form class="d-flex gap-2" method="GET" action="{{ route('admin.reports.sales.daily') }}">
            <input type="date" class="form-control form-control-sm" name="date" value="{{ $date }}">
            <button class="btn btn-outline-secondary btn-sm" type="submit">Filter</button>
        </form>
    </div>

    <div class="card-body">

        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="border rounded p-3">
                    <div class="text-muted">Bills</div>
                    <div class="fs-4 fw-semibold">{{ $summary->bills ?? 0 }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3">
                    <div class="text-muted">Subtotal</div>
                    <div class="fs-4 fw-semibold">{{ number_format($summary->sub_total ?? 0, 2) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3">
                    <div class="text-muted">Discount</div>
                    <div class="fs-4 fw-semibold">{{ number_format($summary->discount_total ?? 0, 2) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3">
                    <div class="text-muted">Grand Total</div>
                    <div class="fs-4 fw-semibold">{{ number_format($summary->grand_total ?? 0, 2) }}</div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-centered">
                <thead class="table-light">
                <tr>
                    <th>Invoice</th>
                    <th>Date/Time</th>
                    <th>Cashier</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Discount</th>
                    <th class="text-end">Tax</th>
                </tr>
                </thead>
                <tbody>
                @forelse($sales as $s)
                    <tr>
                        <td class="fw-semibold">{{ $s->invoice_no }}</td>
                        <td>{{ optional($s->sale_date)->format('d M Y, h:i A') }}</td>
                        <td>{{ $s->createdBy?->name ?? '—' }}</td>
                        <td class="text-end">{{ number_format($s->grand_total, 2) }}</td>
                        <td class="text-end">{{ number_format($s->discount_total, 2) }}</td>
                        <td class="text-end">{{ number_format($s->tax_total, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No sales found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-3">
                {{ $sales->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
