@extends('layouts.vertical', ['subtitle' => 'Stock Entry View'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Stock Entry', 'subtitle' => 'View'])

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-0">Stock Entry Details</h5>
            <p class="card-subtitle mb-0">
                Entry No:
                <span class="badge bg-light text-dark">
                    {{ $entry->entry_no ?? '—' }}
                </span>
            </p>
        </div>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-dark btn-sm" onclick="window.print()">
                <iconify-icon icon="solar:printer-outline"></iconify-icon>
                Print
            </button>

            <a href="{{ route('admin.stock-entries.index') }}" class="btn btn-light btn-sm">
                Back
            </a>
        </div>
    </div>

    <div class="card-body">

        {{-- Entry Meta --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <strong>Supplier</strong>
                <div class="text-muted">
                    {{ $entry->supplier?->name ?? '—' }}
                </div>
            </div>

            <div class="col-md-3">
                <strong>Entry Date</strong>
                <div class="text-muted">
                    {{ $entry->entry_date?->format('d M Y') }}
                </div>
            </div>

            <div class="col-md-3">
                <strong>Created By</strong>
                <div class="text-muted">
                    {{ $entry->createdBy?->name ?? '—' }}
                </div>
            </div>

            <div class="col-md-3">
                <strong>Notes</strong>
                <div class="text-muted">
                    {{ $entry->note ?? '—' }}
                </div>
            </div>
        </div>

        {{-- Items Table --}}
        <div class="table-responsive">
            <table class="table table-hover table-centered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Barcode</th>
                        <th class="text-end">Qty</th>
                        <th class="text-end">Unit Cost</th>
                        <th class="text-end">Line Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($entry->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                <strong>{{ $item->product?->name }}</strong>
                                <div class="text-muted small">
                                    {{ $item->product?->brand ?? '—' }}
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ $item->product?->barcode }}
                                </span>
                            </td>

                            <td class="text-end">
                                {{ $item->qty }}
                            </td>

                            <td class="text-end">
                                {{ number_format($item->unit_cost, 2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($item->line_total, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Totals --}}
        <div class="row mt-4">
            <div class="col-md-6"></div>

            <div class="col-md-6">
                <div class="border rounded p-3">
                    <div class="d-flex justify-content-between">
                        <span>Total Qty</span>
                        <strong>{{ $entry->total_qty }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <span>Total Cost</span>
                        <strong>{{ number_format($entry->total_cost, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
