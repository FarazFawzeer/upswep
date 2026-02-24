@extends('layouts.vertical', ['subtitle' => 'Sales Summary'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Reports', 'subtitle' => 'Weekly / Monthly Sales'])

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-0">Sales Summary</h5>
            <p class="card-subtitle mb-0">View totals by day within a range.</p>
        </div>

        <form class="d-flex gap-2 align-items-center" method="GET" action="{{ route('admin.reports.sales.summary') }}">
            <select class="form-select form-select-sm" name="mode" style="width: 130px;">
                <option value="weekly" {{ $mode === 'weekly' ? 'selected' : '' }}>Weekly</option>
                <option value="monthly" {{ $mode === 'monthly' ? 'selected' : '' }}>Monthly</option>
            </select>

            <input type="date" class="form-control form-control-sm" name="start" value="{{ $start }}">
            <input type="date" class="form-control form-control-sm" name="end" value="{{ $end }}">

            <button class="btn btn-outline-secondary btn-sm" type="submit">Filter</button>
            <a class="btn btn-light btn-sm" href="{{ route('admin.reports.sales.summary', ['mode' => $mode]) }}">Reset</a>
        </form>
    </div>

    <div class="card-body">

        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="border rounded p-3">
                    <div class="text-muted">Bills</div>
                    <div class="fs-4 fw-semibold">{{ $totals->bills ?? 0 }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3">
                    <div class="text-muted">Subtotal</div>
                    <div class="fs-4 fw-semibold">{{ number_format($totals->sub_total ?? 0, 2) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3">
                    <div class="text-muted">Discount</div>
                    <div class="fs-4 fw-semibold">{{ number_format($totals->discount_total ?? 0, 2) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3">
                    <div class="text-muted">Grand Total</div>
                    <div class="fs-4 fw-semibold">{{ number_format($totals->grand_total ?? 0, 2) }}</div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-centered">
                <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th class="text-end">Bills</th>
                    <th class="text-end">Discount</th>
                    <th class="text-end">Tax</th>
                    <th class="text-end">Total</th>
                </tr>
                </thead>
                <tbody>
                @forelse($rows as $r)
                    <tr>
                        <td class="fw-semibold">{{ \Carbon\Carbon::parse($r->day)->format('d M Y') }}</td>
                        <td class="text-end">{{ $r->bills }}</td>
                        <td class="text-end">{{ number_format($r->discount, 2) }}</td>
                        <td class="text-end">{{ number_format($r->tax, 2) }}</td>
                        <td class="text-end fw-semibold">{{ number_format($r->total, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">No data found.</td></tr>
                @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-3">
                {{ $rows->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
