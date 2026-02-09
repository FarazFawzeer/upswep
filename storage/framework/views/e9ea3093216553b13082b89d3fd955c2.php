<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo e($sale->invoice_no); ?></title>
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
        <p class="small">Invoice: <strong><?php echo e($sale->invoice_no); ?></strong></p>
        <p class="small"><?php echo e(optional($sale->sale_date)->format('d M Y, h:i A')); ?></p>
    </div>

    <div class="line"></div>

    <table>
        <?php $__currentLoopData = $sale->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td>
                    <div><strong><?php echo e($it->product_name); ?></strong></div>
                    <div class="small"><?php echo e($it->barcode_snapshot ?? ''); ?></div>
                    <div class="small"><?php echo e($it->qty); ?> x Rs <?php echo e(number_format($it->unit_price, 2)); ?></div>
                </td>
                <td class="right">Rs <?php echo e(number_format($it->line_total, 2)); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </table>

    <div class="line"></div>

    <table>
        <tr><td>Subtotal</td><td class="right">Rs <?php echo e(number_format($sale->sub_total, 2)); ?></td></tr>
        <tr><td>Discount</td><td class="right">- Rs <?php echo e(number_format($sale->discount_total, 2)); ?></td></tr>
        <tr><td>Tax</td><td class="right">Rs <?php echo e(number_format($sale->tax_total, 2)); ?></td></tr>
        <tr><td><strong>Total</strong></td><td class="right"><strong>Rs <?php echo e(number_format($sale->grand_total, 2)); ?></strong></td></tr>
    </table>

    <div class="line"></div>

    <div class="center small">
        Cashier: <?php echo e($sale->createdBy?->name ?? '—'); ?> <br>
        Payment: <?php echo e(strtoupper($sale->payment_method ?? 'cash')); ?> <br><br>
        Thank you!
    </div>

</div>
</body>
</html>
<?php /**PATH F:\Personal Projects\Upswep\upswep\resources\views/pos/invoice/thermal.blade.php ENDPATH**/ ?>