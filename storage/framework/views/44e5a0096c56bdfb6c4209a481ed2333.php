<?php
    use Picqer\Barcode\BarcodeGeneratorPNG;
    $generator = new BarcodeGeneratorPNG();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Print Barcodes</title>

    <style>
        @page { margin: 10mm; }
        body { font-family: Arial, sans-serif; }

        /* A4 label grid */
        .sheet { display: flex; flex-wrap: wrap; gap: 8mm; }

        /* label size (change if you use different sticker sheet) */
        .label {
            width: 48mm;
            height: 25mm;
            padding: 3mm;
            box-sizing: border-box;
            border: 1px dashed #ddd;

            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .name { font-size: 11px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .price { font-size: 11px; margin-top: 2px; }
        .code  { font-size: 10px; letter-spacing: 1px; margin-top: 2px; }

        img { max-width: 100%; height: 30px; }

        .topbar { margin-bottom: 10px; display:flex; justify-content: space-between; align-items:center; }
        @media print {
            .topbar { display:none; }
            .label { border: none; }
        }
    </style>
</head>
<body>

<div class="topbar">
    <div><strong>Total:</strong> <?php echo e($products->count()); ?> labels</div>
    <button onclick="window.print()">Print</button>
</div>

<div class="sheet">
    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $barcodePng = base64_encode(
                $generator->getBarcode($p->barcode, $generator::TYPE_CODE_128, 2, 40)
            );
        ?>

        <div class="label">
            <div class="name"><?php echo e($p->name); ?></div>
            <div class="price">Rs <?php echo e(number_format($p->selling_price, 2)); ?></div>
            <img src="data:image/png;base64,<?php echo e($barcodePng); ?>" alt="barcode">
            <div class="code"><?php echo e($p->barcode); ?></div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

</body>
</html>
<?php /**PATH F:\Personal Projects\Upswep\upswep\resources\views/admin/products/print-barcodes.blade.php ENDPATH**/ ?>