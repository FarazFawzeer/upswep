<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stock Summary</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 6px; }
        .muted { color: #666; font-size: 11px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f5f5f5; text-align: left; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <div class="title">Stock Summary</div>
    <div class="muted">
        Generated: {{ $generatedAt->format('d M Y, h:i A') }}
        @if($q) | Search: "{{ $q }}" @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Category</th>
                <th>Barcode</th>
                <th>Brand</th>
                <th class="right">Stock</th>
                <th class="right">Alert</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
                <tr>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->category?->name ?? '—' }}</td>
                    <td>{{ $p->barcode }}</td>
                    <td>{{ $p->brand ?? '—' }}</td>
                    <td class="right">{{ $p->stock_qty }}</td>
                    <td class="right">{{ $p->low_stock_alert_qty }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>