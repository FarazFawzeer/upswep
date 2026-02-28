@extends('layouts.vertical', ['subtitle' => 'Customer View'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Customer', 'subtitle' => 'View'])

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-0">Customer List</h5>
            <p class="card-subtitle mb-0">All customers in your system.</p>
        </div>
        <a href="{{ route('admin.customers.create') }}" class="btn btn-primary btn-sm">+ Add Customer</a>
    </div>

    <div class="card-body">
        <div class="row mb-3 align-items-center">
            <div class="col-md-6 ms-auto">
                <form method="GET" action="{{ route('admin.customers.index') }}">
                    <div class="input-group">
                        <span class="input-group-text">
                            <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                        </span>
                        <input type="text" class="form-control" name="q" value="{{ request('q') }}"
                            placeholder="Search name / phone / email / code...">
                        <button class="btn btn-outline-secondary" type="submit">Search</button>
                        @if (request()->filled('q'))
                            <a class="btn btn-light" href="{{ route('admin.customers.index') }}">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-centered">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Code</th>
                        <th>Status</th>
                        <th>Updated</th>
                        <th class="text-center" style="width: 150px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($customers as $c)
                    <tr id="customer-{{ $c->id }}">
                        <td>
                            <h6 class="mb-0">{{ $c->name }}</h6>
                            <small class="text-muted">{{ $c->email ?? '—' }}</small>
                        </td>
                        <td>{{ $c->phone ?? '—' }}</td>
                        <td><span class="badge bg-light text-dark">{{ $c->customer_code ?? '—' }}</span></td>
                        <td>
                            @if($c->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>{{ optional($c->updated_at)->format('d M Y, h:i A') }}</td>
                        <td class="text-center">
                            <div class="d-flex gap-3 justify-content-center align-items-center">
                                <a href="{{ route('admin.customers.history', $c->id) }}"
                                   class="text-primary fs-5"
                                   data-bs-toggle="tooltip" title="Purchase History">
                                    <iconify-icon icon="solar:receipt-outline"></iconify-icon>
                                </a>

                                <a href="{{ route('admin.customers.edit', $c->id) }}"
                                   class="text-warning fs-5"
                                   data-bs-toggle="tooltip" title="Edit">
                                    <iconify-icon icon="solar:pen-outline"></iconify-icon>
                                </a>

                                <button type="button"
                                        class="btn p-0 text-danger fs-5 delete-customer"
                                        data-id="{{ $c->id }}"
                                        data-bs-toggle="tooltip" title="Delete">
                                    <iconify-icon icon="solar:trash-bin-trash-outline"></iconify-icon>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">No customers found.</td></tr>
                @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-3">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    document.querySelectorAll('.delete-customer').forEach(btn => {
        btn.addEventListener('click', function(){
            const id = this.dataset.id;

            Swal.fire({
                title: 'Are you sure?',
                text: "This customer will be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(async (result) => {
                if(!result.isConfirmed) return;

                const res = await fetch(`{{ url('admin/customers') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json();

                if(data.success){
                    document.getElementById('customer-'+id)?.remove();
                    Swal.fire('Deleted!', data.message, 'success');
                } else {
                    Swal.fire('Error!', data.message || 'Something went wrong!', 'error');
                }
            });
        });
    });
});
</script>
@endsection