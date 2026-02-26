<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stock Movements</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        .title { font-size: 16px; font-weight: bold; margin-bottom: 6px; }
        .muted { color: #666; font-size: 10px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 5px; }
        th { background: #f5f5f5; text-align: left; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <div class="title">Stock Movement History</div>
    <div class="muted">
        Generated: {{ $generatedAt->format('d M Y, h:i A') }}
        @if($type) | Type: {{ $type }} @endif
        @if($start) | From: {{ $start }} @endif
        @if($end) | To: {{ $end }} @endif
        @if($q) | Search: "{{ $q }}" @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Product</th>
                <th>Barcode</th>
                <th>Type</th>
                <th class="right">Qty</th>
                <th>Reference</th>
                <th>Note</th>
                <th>By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movements as $m)
                <tr>
                    <td>{{ optional($m->created_at)->format('Y-m-d H:i') }}</td>
                    <td>{{ $m->product?->name ?? '—' }}</td>
                    <td>{{ $m->product?->barcode ?? '—' }}</td>
                    <td>{{ $m->movement_type }}</td>
                    <td class="right">{{ $m->qty_change }}</td>
                    <td>
                        @if($m->reference_type && $m->reference_id)
                            {{ $m->reference_type }} #{{ $m->reference_id }}
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ $m->note ?? '—' }}</td>
                    <td>{{ $m->createdBy?->name ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>