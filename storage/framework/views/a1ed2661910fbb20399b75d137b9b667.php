

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.partials.page-title', ['title' => 'Reports', 'subtitle' => 'Best Selling Products'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-0">Best Selling Products</h5>
            <p class="card-subtitle mb-0">Top products by quantity sold.</p>
        </div>

        <form class="d-flex gap-2" method="GET" action="<?php echo e(route('admin.reports.sales.bestProducts')); ?>">
            <input type="date" class="form-control form-control-sm" name="start" value="<?php echo e($start); ?>">
            <input type="date" class="form-control form-control-sm" name="end" value="<?php echo e($end); ?>">
            <button class="btn btn-outline-secondary btn-sm" type="submit">Filter</button>
            <a class="btn btn-light btn-sm" href="<?php echo e(route('admin.reports.sales.bestProducts')); ?>">Reset</a>
        </form>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-centered">
                <thead class="table-light">
                <tr>
                    <th>Product</th>
                    <th>Barcode</th>
                    <th class="text-end">Qty Sold</th>
                    <th class="text-end">Total Sales</th>
                </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-semibold"><?php echo e($r->product_name); ?></td>
                        <td><span class="badge bg-light text-dark"><?php echo e($r->barcode_snapshot ?: '—'); ?></span></td>
                        <td class="text-end"><?php echo e($r->total_qty); ?></td>
                        <td class="text-end fw-semibold"><?php echo e(number_format($r->total_sales, 2)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="4" class="text-center text-muted">No data found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-3">
                <?php echo e($rows->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Best Selling Products'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Upswep\upswep\resources\views/admin/reports/best_selling_products.blade.php ENDPATH**/ ?>