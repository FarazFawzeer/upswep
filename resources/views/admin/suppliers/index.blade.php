@extends('layouts.vertical', ['subtitle' => 'Supplier View'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Supplier', 'subtitle' => 'View'])

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-0">Supplier List</h5>
            <p class="card-subtitle mb-0">All suppliers in your system.</p>
        </div>
        <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary btn-sm">
            + Add Supplier
        </a>
    </div>

    <div class="card-body">
        {{-- Search --}}
        <div class="row mb-3 align-items-center">
            <div class="col-md-6 ms-auto">
                <form method="GET" action="{{ route('admin.suppliers.index') }}">
                    <div class="input-group">
                        <span class="input-group-text">
                            <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                        </span>
                        <input type="text"
                               class="form-control"
                               name="q"
                               value="{{ request('q') }}"
                               placeholder="Search name / phone / email...">
                        <button class="btn btn-outline-secondary" type="submit">Search</button>
                        @if(request()->filled('q'))
                            <a class="btn btn-light" href="{{ route('admin.suppliers.index') }}">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover table-centered">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Updated</th>
                        <th style="width: 160px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $s)
                        <tr id="supplier-{{ $s->id }}">
                            <td><h6 class="mb-0">{{ $s->name }}</h6></td>
                            <td>{{ $s->phone ?? '—' }}</td>
                            <td>{{ $s->email ?? '—' }}</td>
                            <td>
                                @if($s->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>{{ optional($s->updated_at)->format('d M Y, h:i A') }}</td>
                            <td>
                                <div class="d-flex gap-3 justify-content-center align-items-center">
                                    <a href="{{ route('admin.suppliers.edit', $s->id) }}"
                                       class="text-warning fs-5"
                                       data-bs-toggle="tooltip"
                                       title="Edit">
                                        <iconify-icon icon="solar:pen-outline"></iconify-icon>
                                    </a>

                                    <button type="button"
                                            class="btn p-0 text-danger fs-5 delete-supplier"
                                            data-id="{{ $s->id }}"
                                            data-bs-toggle="tooltip"
                                            title="Delete">
                                        <iconify-icon icon="solar:trash-bin-trash-outline"></iconify-icon>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">No suppliers found.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-3">
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    document.querySelectorAll('.delete-supplier').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;

            Swal.fire({
                title: 'Are you sure?',
                text: "This supplier will be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (!result.isConfirmed) return;

                fetch("{{ url('admin/suppliers') }}/" + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('supplier-' + id)?.remove();
                        Swal.fire('Deleted!', data.message, 'success');
                    } else {
                        Swal.fire('Error!', data.message || 'Something went wrong!', 'error');
                    }
                })
                .catch(() => Swal.fire('Error!', 'Something went wrong!', 'error'));
            });
        });
    });
});
</script>
@endsection
