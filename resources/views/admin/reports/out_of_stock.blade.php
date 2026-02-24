@extends('layouts.vertical', ['subtitle' => 'Out of Stock'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Reports', 'subtitle' => 'Out of Stock'])

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-0">Out of Stock Report</h5>
            <p class="card-subtitle mb-0">Products where stock is zero.</p>
        </div>

        <form method="GET" action="{{ route('admin.reports.out-of-stock') }}" class="d-flex gap-2">
            <div class="input-group input-group-sm" style="width: 320px;">
                <span class="input-group-text">
                    <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                </span>
                <input type="text" class="form-control" name="q" value="{{ request('q') }}"
                    placeholder="Search name / barcode / brand...">
            </div>
            <button class="btn btn-outline-secondary btn-sm" type="submit">Search</button>
            <a class="btn btn-light btn-sm" href="{{ route('admin.reports.out-of-stock') }}">Reset</a>
        </form>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-centered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Barcode</th>
                        <th class="text-end">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                        <tr>
                            <td class="fw-semibold">{{ $p->name }}</td>
                            <td>{{ $p->category?->name ?? '—' }}</td>
                            <td><span class="badge bg-light text-dark">{{ $p->barcode }}</span></td>
                            <td class="text-end"><span class="badge bg-secondary">0</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No out-of-stock products found.</td>
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
