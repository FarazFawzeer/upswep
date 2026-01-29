@extends('layouts.vertical', ['subtitle' => 'Product View'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Product', 'subtitle' => 'View'])

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="card-title mb-0">Product List</h5>
                <p class="card-subtitle mb-0">All products in your system.</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                + Add Product
            </a>
        </div>

        <div class="card-body">
            {{-- Filters --}}
            {{-- Filters --}}
            <div class="row mb-3 align-items-center">
                <div class="col-md-12">
                    <form method="GET" action="{{ route('admin.products.index') }}">
                        <div class="row g-2 align-items-center">

                            {{-- Search --}}
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                                    </span>
                                    <input type="text" class="form-control" name="q" value="{{ request('q') }}"
                                        placeholder="Search name / barcode / brand...">
                                </div>
                            </div>

                            {{-- Category --}}
                            <div class="col-md-2">
                                <select class="form-select" name="category_id">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Brand --}}
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="brand" value="{{ request('brand') }}"
                                    placeholder="Brand">
                            </div>

                            {{-- Size --}}
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="size" value="{{ request('size') }}"
                                    placeholder="Size">
                            </div>

                            {{-- Buttons --}}
                            <div class="col-md-2 d-flex gap-2">
                                <button class="btn btn-outline-secondary w-100" type="submit">
                                    Filter
                                </button>

                                @if (request()->query())
                                    <a class="btn btn-light w-100" href="{{ route('admin.products.index') }}">
                                        Reset
                                    </a>
                                @endif
                            </div>

                            {{-- Low stock + Print selected --}}
                            <div class="col-md-12 mt-2 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="low_stock" value="1"
                                        id="low_stock" {{ request('low_stock') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="low_stock">
                                        Low stock only
                                    </label>
                                </div>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.products.barcodes.print') }}"
                                        class="btn btn-outline-dark btn-sm" target="_blank" data-bs-toggle="tooltip"
                                        title="Print all barcodes (based on current filter)">
                                        <iconify-icon icon="solar:printer-outline"></iconify-icon>
                                        Print All
                                    </a>

                                    <button type="button" id="printSelectedBtn" class="btn btn-outline-primary btn-sm"
                                        data-bs-toggle="tooltip" title="Print barcodes for selected products">
                                        <iconify-icon icon="solar:checklist-outline"></iconify-icon>
                                        Print Selected
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>


            {{-- Table --}}
            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover table-centered">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Barcode</th>
                            <th>Prices</th>
                            <th>Stock</th>
                            <th>Updated</th>
                            <th style="width: 160px;">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($products as $product)
                            <tr id="product-{{ $product->id }}">
                                <td>
                                    <input type="checkbox" class="product-check" value="{{ $product->id }}">
                                </td>

                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('/images/users/avatar-6.jpg') }}"
                                            class="avatar-sm rounded" alt="img">
                                        <div>
                                            <h6 class="mb-0">{{ $product->name }}</h6>
                                            <small class="text-muted">
                                                {{ $product->brand ?? '—' }} | {{ $product->size ?? '—' }} |
                                                {{ $product->color ?? '—' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <td>{{ $product->category?->name ?? '—' }}</td>

                                <td>
                                    <span class="badge bg-light text-dark">{{ $product->barcode }}</span>
                                </td>

                                <td>
                                    <div>Cost: <strong>{{ number_format($product->cost_price, 2) }}</strong></div>
                                    <div>Sell: <strong>{{ number_format($product->selling_price, 2) }}</strong></div>
                                </td>

                                <td>
                                    @php
                                        $isLow = $product->stock_qty <= $product->low_stock_alert_qty;
                                    @endphp

                                    <span class="badge {{ $isLow ? 'bg-danger' : 'bg-success' }}">
                                        {{ $product->stock_qty }}
                                    </span>

                                    <small class="text-muted d-block">
                                        Alert: {{ $product->low_stock_alert_qty }}
                                    </small>
                                </td>

                                <td>{{ optional($product->updated_at)->format('d M Y, h:i A') }}</td>

                                <td>
                                    <div class="d-flex gap-3 justify-content-center align-items-center">

                                        {{-- Print single product barcode --}}
                                        <a href="{{ route('admin.products.barcodes.print', ['ids' => $product->id]) }}"
                                            class="text-dark fs-5" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Print Barcode" target="_blank">
                                            <iconify-icon icon="solar:printer-outline"></iconify-icon>
                                        </a>

                                        {{-- View --}}
                                        <a href="{{ route('admin.products.show', $product->id) }}"
                                            class="text-primary fs-5" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="View">
                                            <iconify-icon icon="solar:eye-outline"></iconify-icon>
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('admin.products.edit', $product->id) }}"
                                            class="text-warning fs-5" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Edit">
                                            <iconify-icon icon="solar:pen-outline"></iconify-icon>
                                        </a>

                                        {{-- Delete --}}
                                        <button type="button" class="btn p-0 text-danger fs-5 delete-product"
                                            data-id="{{ $product->id }}" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Delete">
                                            <iconify-icon icon="solar:trash-bin-trash-outline"></iconify-icon>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No products found.</td>
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
document.addEventListener('DOMContentLoaded', function () {

    // Tooltips (once)
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    // Delete
    document.querySelectorAll('.delete-product').forEach(button => {
        button.addEventListener('click', function() {
            let id = this.dataset.id;

            Swal.fire({
                title: 'Are you sure?',
                text: "This product will be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ url('admin/products') }}/" + id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('product-' + id)?.remove();
                            Swal.fire('Deleted!', data.message, 'success');
                        } else {
                            Swal.fire('Error!', data.message || 'Something went wrong!', 'error');
                        }
                    })
                    .catch(() => Swal.fire('Error!', 'Something went wrong!', 'error'));
                }
            });
        });
    });

    // Select all checkbox
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            document.querySelectorAll('.product-check').forEach(ch => ch.checked = selectAll.checked);
        });
    }

    // Print selected
    const printBtn = document.getElementById('printSelectedBtn');
    if (printBtn) {
        printBtn.addEventListener('click', function() {
            const ids = Array.from(document.querySelectorAll('.product-check:checked')).map(el => el.value);

            if (ids.length === 0) {
                Swal.fire('Select products', 'Please select at least one product.', 'info');
                return;
            }

            const url = "{{ route('admin.products.barcodes.print') }}" + "?ids=" + ids.join(',');
            window.open(url, "_blank");
        });
    } else {
        console.log('printSelectedBtn not found');
    }

});
</script>

@endsection
