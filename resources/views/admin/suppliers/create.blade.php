@extends('layouts.vertical', ['subtitle' => 'Supplier Create'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Supplier', 'subtitle' => 'Create'])

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">New Supplier</h5>
    </div>

    <div class="card-body">
        <div id="message"></div>

        <form id="createSupplierForm" action="{{ route('admin.suppliers.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Supplier Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Ex: ABC Traders" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone (Optional)</label>
                    <input type="text" name="phone" class="form-control" placeholder="Ex: 0771234567">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email (Optional)</label>
                    <input type="email" name="email" class="form-control" placeholder="Ex: supplier@gmail.com">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Address (Optional)</label>
                <textarea name="address" class="form-control" rows="3" placeholder="Supplier address..."></textarea>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Create Supplier</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('createSupplierForm');
    const messageBox = document.getElementById('message');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

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
            if (data.success) {
                messageBox.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                form.reset();
                setTimeout(() => messageBox.innerHTML = "", 2500);
            } else {
                const errors = Object.values(data.errors || {}).flat().join('<br>');
                messageBox.innerHTML = `<div class="alert alert-danger">${errors || 'Something went wrong!'}</div>`;
            }
        })
        .catch(() => {
            messageBox.innerHTML = `<div class="alert alert-danger">Something went wrong!</div>`;
        });
    });
});
</script>
@endsection
