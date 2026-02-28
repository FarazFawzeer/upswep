@extends('layouts.vertical', ['subtitle' => 'Customer History'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Customer', 'subtitle' => 'Purchase History'])

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-0">{{ $customer->name }}</h5>
            <p class="card-subtitle mb-0">
                Phone: {{ $customer->phone ?? '—' }} | Total Spent: <strong>Rs {{ number_format($totalSpent, 2) }}</strong>
            </p>
        </div>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-light btn-sm">Back</a>
    </div>

    <div class="card-body">

        <form class="row g-2 mb-3" method="GET">
            <div class="col-md-3">
                <input type="date" name="from" value="{{ $from }}" class="form-control">
            </div>
            <div class="col-md-3">
                <input type="date" name="to" value="{{ $to }}" class="form-control">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-outline-secondary w-100" type="submit">Filter</button>
                <a class="btn btn-light w-100" href="{{ route('admin.customers.history', $customer->id) }}">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-centered">
                <thead class="table-light">
                    <tr>
                        <th>Invoice</th>
                        <th>Date</th>
                        <th class="text-center">Items</th>
                        <th class="text-end">Total</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($sales as $s)
                    <tr>
                        <td><span class="badge bg-light text-dark">{{ $s->invoice_no }}</span></td>
                        <td>{{ optional($s->sale_date)->format('d M Y, h:i A') }}</td>
                        <td class="text-center">{{ $s->items->sum('qty') }}</td>
                        <td class="text-end"><strong>Rs {{ number_format($s->grand_total, 2) }}</strong></td>
                        <td class="text-center">
                            <a href="{{ url('/pos/invoice/'.$s->id) }}" target="_blank"
                               class="text-primary fs-5" data-bs-toggle="tooltip" title="Open Invoice">
                                <iconify-icon icon="solar:bill-list-outline"></iconify-icon>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">No purchases found.</td></tr>
                @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-3">
                {{ $sales->links() }}
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