@extends('layouts.vertical', ['subtitle' => 'Category View'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Category', 'subtitle' => 'View'])

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="card-title mb-0">Category List</h5>
                <p class="card-subtitle mb-0">All categories in your system.</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                + Add Category
            </a>
        </div>

        <div class="card-body">
            <div class="row mb-3 align-items-center">
                <div class="col-md-6 ms-auto">
                    <form method="GET" action="{{ route('admin.categories.index') }}">
                        <div class="input-group">
                            <span class="input-group-text">
                                <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                            </span>

                            <input type="text" class="form-control" name="q" value="{{ request('q') }}"
                                placeholder="Search category name...">

                            <button class="btn btn-outline-secondary" type="submit">
                                Search
                            </button>

                            @if (request()->filled('q'))
                                <a class="btn btn-light" href="{{ route('admin.categories.index') }}">
                                    Reset
                                </a>
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
                            <th>Status</th>
                            <th>Updated At</th>
                            <th style="width: 220px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr id="category-{{ $category->id }}">
                                <td>
                                    <h6 class="mb-0">{{ $category->name }}</h6>
                                </td>
                                <td>
                                    @if ($category->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ optional($category->updated_at)->format('d M Y, h:i A') }}</td>
                                <td>
                                    <div class="d-flex gap-3 justify-content-center align-items-center">

                                        <a href="{{ route('admin.categories.edit', $category->id) }}"
                                            class="text-warning fs-5" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Edit">
                                            <iconify-icon icon="solar:pen-outline"></iconify-icon>
                                        </a>

                                        <button type="button" class="btn p-0 text-danger fs-5 delete-category"
                                            data-id="{{ $category->id }}" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Delete">
                                            <iconify-icon icon="solar:trash-bin-trash-outline"></iconify-icon>
                                        </button>

                                    </div>
                                </td>


                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-end mt-3">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.delete-category').forEach(button => {
            button.addEventListener('click', function() {
                let id = this.dataset.id;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This category will be deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("{{ url('admin/categories') }}/" + id, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById('category-' + id).remove();
                                    Swal.fire('Deleted!', data.message, 'success');
                                } else {
                                    Swal.fire('Error!', data.message || 'Something went wrong!',
                                        'error');
                                }
                            })
                            .catch(() => Swal.fire('Error!', 'Something went wrong!', 'error'));
                    }
                });
            });
        });
    </script>
@endsection
