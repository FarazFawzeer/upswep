@extends('layouts.vertical', ['subtitle' => 'Low Stock Report'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Reports', 'subtitle' => 'Low Stock'])

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="card-title mb-0">Low Stock Products</h5>
                <p class="card-subtitle mb-0">Products where stock_qty <= low_stock_alert_qty</p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                {{-- Export PDF --}}
                <a class="btn btn-outline-danger btn-sm"
                    href="{{ route('admin.reports.low-stock.export.pdf', request()->query()) }}" data-bs-toggle="tooltip"
                    title="Download PDF">
                    <iconify-icon icon="solar:file-text-outline"></iconify-icon>
                    PDF
                </a>

                {{-- Export Excel --}}
                <a class="btn btn-outline-success btn-sm"
                    href="{{ route('admin.reports.low-stock.export.excel', request()->query()) }}" data-bs-toggle="tooltip"
                    title="Download Excel">
                    <iconify-icon icon="solar:sheet-outline"></iconify-icon>
                    Excel
                </a>

                {{-- Open Products --}}
                <a href="{{ route('admin.products.index', ['low_stock' => 1]) }}" class="btn btn-light btn-sm"
                    data-bs-toggle="tooltip" title="Open low stock in Products list">
                    Open In Products
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-centered">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Barcode</th>
                            <th class="text-center">Stock</th>
                            <th class="text-center">Alert</th>
                            <th class="text-center" style="width: 90px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $p)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $p->image ? asset('storage/' . $p->image) : asset('/images/users/avatar-6.jpg') }}"
                                            class="avatar-sm rounded" alt="img">
                                        <div>
                                            <h6 class="mb-0">{{ $p->name }}</h6>
                                            <small class="text-muted">{{ $p->brand ?? '—' }} | {{ $p->size ?? '—' }}</small>
                                        </div>
                                    </div>
                                </td>

                                <td>{{ $p->category?->name ?? '—' }}</td>

                                <td>
                                    <span class="badge bg-light text-dark">{{ $p->barcode }}</span>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-danger">{{ $p->stock_qty }}</span>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $p->low_stock_alert_qty }}</span>
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('admin.products.edit', $p->id) }}" class="text-warning fs-5"
                                        data-bs-toggle="tooltip" title="Edit">
                                        <iconify-icon icon="solar:pen-outline"></iconify-icon>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No low stock items 🎉</td>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
        });
    </script>
@endsection
