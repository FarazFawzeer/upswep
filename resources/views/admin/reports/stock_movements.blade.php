@extends('layouts.vertical', ['subtitle' => 'Stock Movements'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Reports', 'subtitle' => 'Stock Movements'])

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Stock Movement History</h5>
        <p class="card-subtitle mb-0">All stock changes (purchase, sale, adjustment).</p>
    </div>

    <div class="card-body">

        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.reports.stock-movements') }}" class="mb-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                        </span>
                        <input type="text" class="form-control" name="q" value="{{ request('q') }}"
                               placeholder="Search product name / barcode...">
                    </div>
                </div>

                <div class="col-md-2">
                    <select class="form-select" name="type">
                        <option value="">All Types</option>
                        @foreach($types as $t)
                            <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>
                                {{ ucfirst($t) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="date" class="form-control" name="start" value="{{ request('start') }}">
                </div>

                <div class="col-md-2">
                    <input type="date" class="form-control" name="end" value="{{ request('end') }}">
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-outline-secondary w-100" type="submit">Filter</button>
                    <a class="btn btn-light w-100" href="{{ route('admin.reports.stock-movements') }}">Reset</a>
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover table-centered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Type</th>
                        <th class="text-end">Qty Change</th>
                        <th>Reference</th>
                        <th>Note</th>
                        <th>By</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $m)
                        @php
                            $isMinus = (int)$m->qty_change < 0;
                        @endphp
                        <tr>
                            <td>{{ $m->created_at?->format('d M Y, h:i A') }}</td>
                            <td>
                                <div class="fw-semibold">{{ $m->product?->name ?? '—' }}</div>
                                <small class="text-muted">{{ $m->product?->barcode ?? '' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ ucfirst($m->movement_type) }}</span>
                            </td>
                            <td class="text-end">
                                <span class="fw-semibold {{ $isMinus ? 'text-danger' : 'text-success' }}">
                                    {{ $m->qty_change }}
                                </span>
                            </td>
                            <td>
                                @if($m->reference_type && $m->reference_id)
                                    <span class="badge bg-light text-dark">
                                        {{ $m->reference_type }} #{{ $m->reference_id }}
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $m->note ?? '—' }}</td>
                            <td>{{ $m->createdBy?->name ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No stock movements found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-3">
                {{ $movements->links() }}
            </div>
        </div>

    </div>
</div>
@endsection
