@extends('layouts.vertical', ['subtitle' => 'Stock Adjustment'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Stock Adjustment', 'subtitle' => 'Create'])

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-0">Stock Adjustment</h5>
            <p class="card-subtitle mb-0">Add or reduce stock manually (Admin only).</p>
        </div>
        <a href="{{ route('admin.stock-movements.index') }}" class="btn btn-light btn-sm">Back</a>
    </div>

    <div class="card-body">
        <div id="message"></div>

        <form id="adjustForm" action="{{ route('admin.stock-adjustments.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Product</label>
                    <select class="form-select" name="product_id" required>
                        <option value="">Select product</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->name }} ({{ $p->barcode }}) - Stock: {{ $p->stock_qty }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Direction</label>
                    <select class="form-select" name="direction" required>
                        <option value="in">Add (+)</option>
                        <option value="out">Reduce (-)</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Qty</label>
                    <input type="number" class="form-control" name="qty" min="1" value="1" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Reason</label>
                    <input type="text" class="form-control" name="reason" placeholder="Ex: Damaged / Correction" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Note (Optional)</label>
                    <input type="text" class="form-control" name="note" placeholder="Extra details...">
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button class="btn btn-primary" type="submit">Save Adjustment</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('adjustForm').addEventListener('submit', function(e){
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);

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
        const messageBox = document.getElementById('message');
        if (data.success) {
            messageBox.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
            form.reset();
            setTimeout(() => messageBox.innerHTML = "", 2500);
        } else {
            messageBox.innerHTML = `<div class="alert alert-danger">${data.message || 'Something went wrong!'}</div>`;
        }
    })
    .catch(() => {
        document.getElementById('message').innerHTML =
            `<div class="alert alert-danger">Something went wrong!</div>`;
    });
});
</script>
@endsection
