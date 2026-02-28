@extends('layouts.vertical', ['subtitle' => 'Customer Create'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Customer', 'subtitle' => 'Create'])

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">New Customer</h5>
    </div>

    <div class="card-body">
        <div id="message"></div>

        <form id="createCustomerForm" action="{{ route('admin.customers.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Customer Code (optional)</label>
                    <input type="text" name="customer_code" class="form-control" placeholder="EX: CUS-0001">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-control" placeholder="Ex: John" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" placeholder="07X XXX XXXX">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="ex: test@gmail.com">
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
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="3" placeholder="Address..."></textarea>
            </div>

            <div class="d-flex justify-content-end">
                <button class="btn btn-primary" type="submit">Create Customer</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('createCustomerForm').addEventListener('submit', function(e){
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);

    fetch(form.action, {
        method: "POST",
        body: formData,
        headers: { "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value }
    })
    .then(r => r.json())
    .then(data => {
        const box = document.getElementById('message');
        if(data.success){
            box.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
            form.reset();
            setTimeout(()=> box.innerHTML='', 2500);
        } else {
            const errors = Object.values(data.errors || {}).flat().join('<br>');
            box.innerHTML = `<div class="alert alert-danger">${errors || 'Something went wrong!'}</div>`;
        }
    })
    .catch(err => {
        document.getElementById('message').innerHTML = `<div class="alert alert-danger">Error: ${err}</div>`;
    });
});
</script>
@endsection