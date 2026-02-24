@extends('layouts.vertical', ['subtitle' => 'Best Selling Products'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Reports', 'subtitle' => 'Best Selling Products'])

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-0">Best Selling Products</h5>
            <p class="card-subtitle mb-0">Top products by quantity sold.</p>
        </div>

        <form class="d-flex gap-2" method="GET" action="{{ route('admin.reports.sales.bestProducts') }}">
            <input type="date" class="form-control form-control-sm" name="start" value="{{ $start }}">
            <input type="date" class="form-control form-control-sm" name="end" value="{{ $end }}">
            <button class="btn btn-outline-secondary btn-sm" type="submit">Filter</button>
            <a class="btn btn-light btn-sm" href="{{ route('admin.reports.sales.bestProducts') }}">Reset</a>
        </form>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-centered">
                <thead class="table-light">
                <tr>
                    <th>Product</th>
                    <th>Barcode</th>
                    <th class="text-end">Qty Sold</th>
                    <th class="text-end">Total Sales</th>
                </tr>
                </thead>
                <tbody>
                @forelse($rows as $r)
                    <tr>
                        <td class="fw-semibold">{{ $r->product_name }}</td>
                        <td><span class="badge bg-light text-dark">{{ $r->barcode_snapshot ?: '—' }}</span></td>
                        <td class="text-end">{{ $r->total_qty }}</td>
                        <td class="text-end fw-semibold">{{ number_format($r->total_sales, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">No data found.</td></tr>
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
