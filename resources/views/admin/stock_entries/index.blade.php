@extends('layouts.vertical', ['subtitle' => 'Stock Entry View'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Stock Entry', 'subtitle' => 'View'])

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="card-title mb-0">Stock Entry List</h5>
                <p class="card-subtitle mb-0">All stock purchase entries in your system.</p>
            </div>

            <a href="{{ route('admin.stock-entries.create') }}" class="btn btn-primary btn-sm">
                + New Stock Entry
            </a>
        </div>

        <div class="card-body">

            {{-- Search --}}
            <div class="row mb-3 align-items-center">
                <div class="col-md-6 ms-auto">
                    <form method="GET" action="{{ route('admin.stock-entries.index') }}">
                        <div class="input-group">
                            <span class="input-group-text">
                                <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                            </span>

                            <input type="text" class="form-control" name="q" value="{{ request('q') }}"
                                placeholder="Search entry no / supplier / date (YYYY-MM-DD)">

                            <button class="btn btn-outline-secondary" type="submit">Search</button>

                            @if (request()->filled('q'))
                                <a class="btn btn-light" href="{{ route('admin.stock-entries.index') }}">Reset</a>
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
                            <th>Entry No</th>
                            <th>Supplier</th>
                            <th>Date</th>
                            <th>Total Qty</th>
                            <th>Total Cost</th>
                            <th>Created By</th>
                            <th>Updated</th>
                            <th style="width: 120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entries as $entry)
                            @php
                                $totalQty = $entry->items->sum('qty');
                                $totalCost = $entry->items->sum('line_total');
                            @endphp
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $entry->entry_no }}</span>
                                </td>
                                <td>{{ $entry->supplier?->name ?? '—' }}</td>
                                <td>{{ optional($entry->entry_date)->format('d M Y') }}</td>

                                <td>
                                    <span class="badge bg-success">{{ $totalQty }}</span>
                                </td>

                                <td>
                                    <strong>{{ number_format($totalCost, 2) }}</strong>
                                </td>

                                <td>{{ $entry->createdBy?->name ?? '—' }}</td>
                                <td>{{ optional($entry->updated_at)->format('d M Y, h:i A') }}</td>

                                <td>
                                    <div class="d-flex gap-3 justify-content-center align-items-center">
                                        <a href="{{ route('admin.stock-entries.show', $entry->id) }}"
                                            class="text-primary fs-5" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="View">
                                            <iconify-icon icon="solar:eye-outline"></iconify-icon>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No stock entries found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="d-flex justify-content-end mt-3">
                    {{ $entries->links() }}
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
