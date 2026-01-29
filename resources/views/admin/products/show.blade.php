@extends('layouts.vertical', ['subtitle' => 'Product Details'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Product', 'subtitle' => 'Details'])

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="card-title mb-0">{{ $product->name }}</h5>
                <p class="card-subtitle mb-0">Full product information</p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.products.index') }}" class="btn btn-light btn-sm">Back</a>
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning btn-sm">
                    Edit
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                {{-- Image --}}
                <div class="col-md-4 mb-3">
                    <div class="border rounded p-3 text-center">
                        <img
                            src="{{ $product->image ? asset('storage/'.$product->image) : asset('/images/users/avatar-6.jpg') }}"
                            alt="Product Image"
                            class="img-fluid rounded"
                            style="max-height: 240px; object-fit: cover;">
                    </div>
                </div>

                {{-- Details --}}
                <div class="col-md-8 mb-3">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted d-block">Category</small>
                                <h6 class="mb-0">{{ $product->category?->name ?? '—' }}</h6>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted d-block">Supplier</small>
                                <h6 class="mb-0">{{ $product->supplier?->name ?? '—' }}</h6>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted d-block">Brand</small>
                                <h6 class="mb-0">{{ $product->brand ?? '—' }}</h6>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted d-block">Size</small>
                                <h6 class="mb-0">{{ $product->size ?? '—' }}</h6>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted d-block">Color</small>
                                <h6 class="mb-0">{{ $product->color ?? '—' }}</h6>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted d-block">Cost Price</small>
                                <h6 class="mb-0">{{ number_format($product->cost_price, 2) }}</h6>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted d-block">Selling Price</small>
                                <h6 class="mb-0">{{ number_format($product->selling_price, 2) }}</h6>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted d-block">Stock Quantity</small>
                                @php
                                    $isLow = $product->stock_qty <= $product->low_stock_alert_qty;
                                @endphp
                                <span class="badge {{ $isLow ? 'bg-danger' : 'bg-success' }}">
                                    {{ $product->stock_qty }}
                                </span>
                                <small class="text-muted ms-2">Alert: {{ $product->low_stock_alert_qty }}</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted d-block">Status</small>
                                @if($product->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted d-block">Barcode</small>
                                <h6 class="mb-0">{{ $product->barcode }}</h6>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted d-block">Created At</small>
                                <h6 class="mb-0">{{ optional($product->created_at)->format('d M Y, h:i A') }}</h6>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted d-block">Updated At</small>
                                <h6 class="mb-0">{{ optional($product->updated_at)->format('d M Y, h:i A') }}</h6>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Extra actions row --}}
            <div class="d-flex justify-content-end mt-3 gap-2">
                <button type="button"
                        class="btn btn-danger btn-sm delete-product"
                        data-id="{{ $product->id }}">
                    Delete Product
                </button>
            </div>
        </div>
    </div>

    <script>
        // Delete from show page
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
                                Swal.fire('Deleted!', data.message, 'success')
                                    .then(() => window.location.href = "{{ route('admin.products.index') }}");
                            } else {
                                Swal.fire('Error!', data.message || 'Something went wrong!', 'error');
                            }
                        })
                        .catch(() => Swal.fire('Error!', 'Something went wrong!', 'error'));
                    }
                });
            });
        });
    </script>
@endsection
