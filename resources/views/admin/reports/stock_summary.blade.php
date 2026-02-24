@extends('layouts.vertical', ['subtitle' => 'Stock Summary'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Reports', 'subtitle' => 'Stock Summary'])

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-0">Stock Summary</h5>
            <p class="card-subtitle mb-0">All products with current stock and alerts.</p>
        </div>

        <form method="GET" action="{{ route('admin.reports.stock-summary') }}" class="d-flex gap-2">
            <div class="input-group input-group-sm" style="width: 320px;">
                <span class="input-group-text">
                    <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                </span>
                <input type="text" class="form-control" name="q" value="{{ request('q') }}"
                    placeholder="Search name / barcode / brand...">
            </div>
            <button class="btn btn-outline-secondary btn-sm" type="submit">Search</button>
            <a class="btn btn-light btn-sm" href="{{ route('admin.reports.stock-summary') }}">Reset</a>
        </form>
    </div>

    <div class="card-body">

        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="border rounded p-3">
                    <div class="text-muted">Total Products</div>
                    <div class="fs-4 fw-semibold">{{ $totals['total_products'] }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3">
                    <div class="text-muted">Total Stock Qty</div>
                    <div class="fs-4 fw-semibold">{{ number_format($totals['total_qty']) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3">
                    <div class="text-muted">Low Stock Items</div>
                    <div class="fs-4 fw-semibold">{{ $totals['low_stock'] }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3">
                    <div class="text-muted">Out of Stock</div>
                    <div class="fs-4 fw-semibold">{{ $totals['out_of_stock'] }}</div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-centered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Barcode</th>
                        <th class="text-end">Stock</th>
                        <th class="text-end">Low Stock Alert</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                        @php
                            $isLow = $p->stock_qty <= $p->low_stock_alert_qty && $p->stock_qty > 0;
                            $isOut = $p->stock_qty <= 0;
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $p->name }}</td>
                            <td>{{ $p->category?->name ?? '—' }}</td>
                            <td><span class="badge bg-light text-dark">{{ $p->barcode }}</span></td>
                            <td class="text-end">
                                <span class="badge {{ $isOut ? 'bg-secondary' : ($isLow ? 'bg-danger' : 'bg-success') }}">
                                    {{ $p->stock_qty }}
                                </span>
                            </td>
                            <td class="text-end">{{ $p->low_stock_alert_qty }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-3">
                {{ $products->links() }}
            </div>
        </div>

    </div>
</div>
@endsection
