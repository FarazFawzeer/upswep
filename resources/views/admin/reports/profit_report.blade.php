@extends('layouts.vertical', ['subtitle' => 'Profit Report'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Reports', 'subtitle' => 'Profit Report'])

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-0">Profit Report</h5>
            <p class="card-subtitle mb-0">Profit = (Selling - Cost) × Qty (based on sale item snapshots).</p>
        </div>

        <form class="d-flex gap-2" method="GET" action="{{ route('admin.reports.profit') }}">
            <input type="date" class="form-control form-control-sm" name="start" value="{{ $start }}">
            <input type="date" class="form-control form-control-sm" name="end" value="{{ $end }}">
            <button class="btn btn-outline-secondary btn-sm" type="submit">Filter</button>
            <a class="btn btn-light btn-sm" href="{{ route('admin.reports.profit') }}">Reset</a>
        </form>
    </div>

    <div class="card-body">

        {{-- Summary cards --}}
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="border rounded p-3">
                    <div class="text-muted">Revenue</div>
                    <div class="fs-4 fw-semibold">{{ number_format($totals->revenue ?? 0, 2) }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-3">
                    <div class="text-muted">Cost</div>
                    <div class="fs-4 fw-semibold">{{ number_format($totals->cost ?? 0, 2) }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-3">
                    <div class="text-muted">Profit</div>
                    <div class="fs-4 fw-semibold">{{ number_format($totals->profit ?? 0, 2) }}</div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover table-centered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Barcode</th>
                        <th class="text-end">Qty</th>
                        <th class="text-end">Revenue</th>
                        <th class="text-end">Cost</th>
                        <th class="text-end">Profit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $r)
                        @php
                            $profit = (float) $r->profit;
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $r->product_name }}</td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ $r->barcode_snapshot ?: '—' }}
                                </span>
                            </td>
                            <td class="text-end">{{ $r->total_qty }}</td>
                            <td class="text-end">{{ number_format($r->revenue, 2) }}</td>
                            <td class="text-end">{{ number_format($r->cost, 2) }}</td>
                            <td class="text-end fw-semibold {{ $profit < 0 ? 'text-danger' : 'text-success' }}">
                                {{ number_format($profit, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No data found.</td>
                        </tr>
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
