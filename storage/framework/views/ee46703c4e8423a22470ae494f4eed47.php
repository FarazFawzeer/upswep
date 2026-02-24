

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.partials.page-title', ['title' => 'Reports', 'subtitle' => 'Profit Report'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-0">Profit Report</h5>
            <p class="card-subtitle mb-0">Profit = (Selling - Cost) × Qty (based on sale item snapshots).</p>
        </div>

        <form class="d-flex gap-2" method="GET" action="<?php echo e(route('admin.reports.profit')); ?>">
            <input type="date" class="form-control form-control-sm" name="start" value="<?php echo e($start); ?>">
            <input type="date" class="form-control form-control-sm" name="end" value="<?php echo e($end); ?>">
            <button class="btn btn-outline-secondary btn-sm" type="submit">Filter</button>
            <a class="btn btn-light btn-sm" href="<?php echo e(route('admin.reports.profit')); ?>">Reset</a>
        </form>
    </div>

    <div class="card-body">

        
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="border rounded p-3">
                    <div class="text-muted">Revenue</div>
                    <div class="fs-4 fw-semibold"><?php echo e(number_format($totals->revenue ?? 0, 2)); ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-3">
                    <div class="text-muted">Cost</div>
                    <div class="fs-4 fw-semibold"><?php echo e(number_format($totals->cost ?? 0, 2)); ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-3">
                    <div class="text-muted">Profit</div>
                    <div class="fs-4 fw-semibold"><?php echo e(number_format($totals->profit ?? 0, 2)); ?></div>
                </div>
            </div>
        </div>

        
        <div class="table-responsive">
            <table class="table table-hover table-centered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Barcode</th>
                        <th class="text-end">Qty</th>
                        <th class="text-end">Revenue</th>
                        <th class="text-end">Cost</th>
                        <th class="text-end">Profit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $profit = (float) $r->profit;
                        ?>
                        <tr>
                            <td class="fw-semibold"><?php echo e($r->product_name); ?></td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    <?php echo e($r->barcode_snapshot ?: '—'); ?>

                                </span>
                            </td>
                            <td class="text-end"><?php echo e($r->total_qty); ?></td>
                            <td class="text-end"><?php echo e(number_format($r->revenue, 2)); ?></td>
                            <td class="text-end"><?php echo e(number_format($r->cost, 2)); ?></td>
                            <td class="text-end fw-semibold <?php echo e($profit < 0 ? 'text-danger' : 'text-success'); ?>">
                                <?php echo e(number_format($profit, 2)); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No data found.</td>
                        </tr>
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

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Profit Report'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Upswep\upswep\resources\views/admin/reports/profit_report.blade.php ENDPATH**/ ?>