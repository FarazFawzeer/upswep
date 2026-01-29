@extends('layouts.vertical', ['subtitle' => 'Product Create'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Product', 'subtitle' => 'Create'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">New Product</h5>
        </div>

        <div class="card-body">
            <div id="message"></div>

            <form id="createProductForm" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Supplier (Optional)</label>
                        <select name="supplier_id" class="form-select">
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>
                                    {{ $sup->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                               placeholder="Ex: Formal Shirt" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Brand</label>
                        <input type="text" name="brand" class="form-control" value="{{ old('brand') }}" placeholder="Ex: Nike">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Size</label>
                        <input type="text" name="size" class="form-control" value="{{ old('size') }}" placeholder="Ex: M">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Color</label>
                        <input type="text" name="color" class="form-control" value="{{ old('color') }}" placeholder="Ex: Black">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Cost Price</label>
                        <input type="number" step="0.01" name="cost_price" class="form-control"
                               value="{{ old('cost_price', 0) }}" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Selling Price</label>
                        <input type="number" step="0.01" name="selling_price" class="form-control"
                               value="{{ old('selling_price', 0) }}" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Opening Stock</label>
                        <input type="number" name="stock_qty" class="form-control" value="{{ old('stock_qty', 0) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Low Stock Alert Qty</label>
                        <input type="number" name="low_stock_alert_qty" class="form-control" value="{{ old('low_stock_alert_qty', 5) }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Barcode (Optional)</label>
                        <input type="text" name="barcode" class="form-control" value="{{ old('barcode') }}"
                               placeholder="Leave empty to auto-generate">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Product Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*" id="imageInput">
                        <small class="text-muted">jpg, png, webp (max 2MB)</small>
                    </div>

                    <div class="col-md-6 mb-3 d-flex align-items-end">
                        <img id="preview" src="{{ asset('/images/users/avatar-6.jpg') }}"
                             class="rounded border" style="height: 80px; width: 80px; object-fit: cover;" alt="preview">
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-light me-2">Back</a>
                    <button type="submit" class="btn btn-primary">Create Product</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // image preview
        const imgInput = document.getElementById('imageInput');
        const preview = document.getElementById('preview');

        imgInput?.addEventListener('change', (e) => {
            const file = e.target.files?.[0];
            if (!file) return;
            preview.src = URL.createObjectURL(file);
        });

        // ajax submit
        document.getElementById('createProductForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let form = this;
            let formData = new FormData(form);

            fetch(form.action, {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                let box = document.getElementById('message');

                if (data.success) {
                    box.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                    form.reset();
                    preview.src = "{{ asset('/images/users/avatar-6.jpg') }}";
                    setTimeout(() => box.innerHTML = "", 2500);
                } else {
                    let errors = data.errors ? Object.values(data.errors).flat().join('<br>') : (data.message || 'Something went wrong');
                    box.innerHTML = `<div class="alert alert-danger">${errors}</div>`;
                }
            })
            .catch(err => {
                document.getElementById('message').innerHTML =
                    `<div class="alert alert-danger">Error: ${err}</div>`;
            });
        });
    </script>
@endsection
