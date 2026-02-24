@extends('layouts.vertical', ['subtitle' => 'Sales by Cashier'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Reports', 'subtitle' => 'Sales by Cashier'])

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-0">Sales by Cashier</h5>
            <p class="card-subtitle mb-0">Compare cashier performance by range.</p>
        </div>

        <form class="d-flex gap-2" method="GET" action="{{ route('admin.reports.sales.byCashier') }}">
            <input type="date" class="form-control form-control-sm" name="start" value="{{ $start }}">
            <input type="date" class="form-control form-control-sm" name="end" value="{{ $end }}">
            <button class="btn btn-outline-secondary btn-sm" type="submit">Filter</button>
            <a class="btn btn-light btn-sm" href="{{ route('admin.reports.sales.byCashier') }}">Reset</a>
        </form>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-centered">
                <thead class="table-light">
                <tr>
                    <th>Cashier</th>
                    <th class="text-end">Bills</th>
                    <th class="text-end">Discount</th>
                    <th class="text-end">Tax</th>
                    <th class="text-end">Total</th>
                </tr>
                </thead>
                <tbody>
                @forelse($rows as $r)
                    <tr>
                        <td class="fw-semibold">{{ $cashiers[$r->cashier_id] ?? '—' }}</td>
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
