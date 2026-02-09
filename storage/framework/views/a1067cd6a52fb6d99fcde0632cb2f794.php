<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo e($sale->invoice_no); ?></title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f4f4f4; }
        .right { text-align: right; }
    </style>
</head>
<body>

<div class="header">
    <h3>Upswep Dress Shop</h3>
    <div>Invoice: <strong><?php echo e($sale->invoice_no); ?></strong></div>
    <div>Date: <?php echo e(optional($sale->sale_date)->format('d M Y, h:i A')); ?></div>
</div>

<table>
    <thead>
    <tr>
        <th>Item</th>
        <th style="width:70px;">Qty</th>
        <th style="width:120px;" class="right">Unit Price</th>
        <th style="width:120px;" class="right">Line Total</th>
    </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $sale->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($it->product_name); ?> <br><small><?php echo e($it->barcode_snapshot ?? ''); ?></small></td>
            <td><?php echo e($it->qty); ?></td>
            <td class="right">Rs <?php echo e(number_format($it->unit_price, 2)); ?></td>
            <td class="right">Rs <?php echo e(number_format($it->line_total, 2)); ?></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>

<br>

<table>
    <tr><td class="right"><strong>Subtotal:</strong></td><td class="right" style="width:160px;">Rs <?php echo e(number_format($sale->sub_total, 2)); ?></td></tr>
    <tr><td class="right"><strong>Discount:</strong></td><td class="right">- Rs <?php echo e(number_format($sale->discount_total, 2)); ?></td></tr>
    <tr><td class="right"><strong>Tax:</strong></td><td class="right">Rs <?php echo e(number_format($sale->tax_total, 2)); ?></td></tr>
    <tr><td class="right"><strong>Grand Total:</strong></td><td class="right"><strong>Rs <?php echo e(number_format($sale->grand_total, 2)); ?></strong></td></tr>
</table>

</body>
</html>
<?php /**PATH F:\Personal Projects\Upswep\upswep\resources\views/pos/invoice/a4_pdf.blade.php ENDPATH**/ ?>