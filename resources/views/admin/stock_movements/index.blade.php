@extends('layouts.vertical', ['subtitle' => 'Stock History'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Stock Movements', 'subtitle' => 'History'])

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-0">Stock Movements</h5>
            <p class="card-subtitle mb-0">Purchases, sales and adjustments history.</p>
        </div>

        <a href="{{ route('admin.stock-adjustments.create') }}" class="btn btn-primary btn-sm">
            + New Adjustment
        </a>
    </div>

    <div class="card-body">

        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.stock-movements.index') }}">
            <div class="row g-2 align-items-center mb-3">
                <div class="col-md-4">
                    <select class="form-select" name="product_id">
                        <option value="">All Products</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->name }} ({{ $p->barcode }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select class="form-select" name="movement_type">
                        <option value="">All Types</option>
                        <option value="purchase" {{ request('movement_type')=='purchase'?'selected':'' }}>Purchase</option>
                        <option value="sale" {{ request('movement_type')=='sale'?'selected':'' }}>Sale</option>
                        <option value="adjustment" {{ request('movement_type')=='adjustment'?'selected':'' }}>Adjustment</option>
                        <option value="return" {{ request('movement_type')=='return'?'selected':'' }}>Return</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="date" class="form-control" name="from" value="{{ request('from') }}">
                </div>

                <div class="col-md-2">
                    <input type="date" class="form-control" name="to" value="{{ request('to') }}">
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-outline-secondary w-100">Filter</button>
                    @if(request()->query())
                        <a href="{{ route('admin.stock-movements.index') }}" class="btn btn-light w-100">Reset</a>
                    @endif
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover table-centered">
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
                            $badge = match($m->movement_type){
                                'purchase' => 'bg-success',
                                'sale' => 'bg-danger',
                                'adjustment' => 'bg-warning',
                                'return' => 'bg-info',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <tr>
                            <td>{{ optional($m->created_at)->format('d M Y, h:i A') }}</td>
                            <td>
                                <strong>{{ $m->product?->name ?? '—' }}</strong>
                                <div class="text-muted small">{{ $m->product?->barcode ?? '' }}</div>
                            </td>
                            <td><span class="badge {{ $badge }}">{{ ucfirst($m->movement_type) }}</span></td>
                            <td class="text-end">
                                <span class="badge {{ $m->qty_change >= 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $m->qty_change }}
                                </span>
                            </td>
                            <td>
                                {{ $m->reference_type ?? '—' }}
                                @if(!is_null($m->reference_id))
                                    #{{ $m->reference_id }}
                                @endif
                            </td>
                            <td>{{ $m->note ?? '—' }}</td>
                            <td>{{ $m->createdBy?->name ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No movements found.</td>
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
