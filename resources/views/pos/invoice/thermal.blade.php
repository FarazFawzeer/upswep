<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $sale->invoice_no }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 8px; }
        .paper { width: 80mm; }
        h2,h3,p { margin: 0; }
        .center { text-align: center; }
        .small { font-size: 12px; }
        .line { border-top: 1px dashed #000; margin: 8px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 4px 0; font-size: 12px; vertical-align: top; }
        .right { text-align: right; }
    </style>
</head>
<body onload="window.print()">
<div class="paper">

    <div class="center">
        <h3>Upswep Dress Shop</h3>
        <p class="small">Colombo, Sri Lanka</p>
        <p class="small">Invoice: <strong>{{ $sale->invoice_no }}</strong></p>
        <p class="small">{{ optional($sale->sale_date)->format('d M Y, h:i A') }}</p>
    </div>

    <div class="line"></div>

    <table>
        @foreach($sale->items as $it)
            <tr>
                <td>
                    <div><strong>{{ $it->product_name }}</strong></div>
                    <div class="small">{{ $it->barcode_snapshot ?? '' }}</div>
                    <div class="small">{{ $it->qty }} x Rs {{ number_format($it->unit_price, 2) }}</div>
                </td>
                <td class="right">Rs {{ number_format($it->line_total, 2) }}</td>
            </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <table>
        <tr><td>Subtotal</td><td class="right">Rs {{ number_format($sale->sub_total, 2) }}</td></tr>
        <tr><td>Discount</td><td class="right">- Rs {{ number_format($sale->discount_total, 2) }}</td></tr>
        <tr><td>Tax</td><td class="right">Rs {{ number_format($sale->tax_total, 2) }}</td></tr>
        <tr><td><strong>Total</strong></td><td class="right"><strong>Rs {{ number_format($sale->grand_total, 2) }}</strong></td></tr>
    </table>

    <div class="line"></div>

    <div class="center small">
        Cashier: {{ $sale->createdBy?->name ?? '—' }} <br>
        Payment: {{ strtoupper($sale->payment_method ?? 'cash') }} <br><br>
        Thank you!
    </div>

</div>
</body>
</html>
