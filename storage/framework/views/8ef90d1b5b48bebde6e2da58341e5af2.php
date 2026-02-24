

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.partials.page-title', ['title' => 'Reports', 'subtitle' => 'Weekly / Monthly Sales'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-0">Sales Summary</h5>
            <p class="card-subtitle mb-0">View totals by day within a range.</p>
        </div>

        <form class="d-flex gap-2 align-items-center" method="GET" action="<?php echo e(route('admin.reports.sales.summary')); ?>">
            <select class="form-select form-select-sm" name="mode" style="width: 130px;">
                <option value="weekly" <?php echo e($mode === 'weekly' ? 'selected' : ''); ?>>Weekly</option>
                <option value="monthly" <?php echo e($mode === 'monthly' ? 'selected' : ''); ?>>Monthly</option>
            </select>

            <input type="date" class="form-control form-control-sm" name="start" value="<?php echo e($start); ?>">
            <input type="date" class="form-control form-control-sm" name="end" value="<?php echo e($end); ?>">

            <button class="btn btn-outline-secondary btn-sm" type="submit">Filter</button>
            <a class="btn btn-light btn-sm" href="<?php echo e(route('admin.reports.sales.summary', ['mode' => $mode])); ?>">Reset</a>
        </form>
    </div>

    <div class="card-body">

        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="border rounded p-3">
                    <div class="text-muted">Bills</div>
                    <div class="fs-4 fw-semibold"><?php echo e($totals->bills ?? 0); ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3">
                    <div class="text-muted">Subtotal</div>
                    <div class="fs-4 fw-semibold"><?php echo e(number_format($totals->sub_total ?? 0, 2)); ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3">
                    <div class="text-muted">Discount</div>
                    <div class="fs-4 fw-semibold"><?php echo e(number_format($totals->discount_total ?? 0, 2)); ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3">
                    <div class="text-muted">Grand Total</div>
                    <div class="fs-4 fw-semibold"><?php echo e(number_format($totals->grand_total ?? 0, 2)); ?></div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-centered">
                <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th class="text-end">Bills</th>
                    <th class="text-end">Discount</th>
                    <th class="text-end">Tax</th>
                    <th class="text-end">Total</th>
                </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-semibold"><?php echo e(\Carbon\Carbon::parse($r->day)->format('d M Y')); ?></td>
                        <td class="text-end"><?php echo e($r->bills); ?></td>
                        <td class="text-end"><?php echo e(number_format($r->discount, 2)); ?></td>
                        <td class="text-end"><?php echo e(number_format($r->tax, 2)); ?></td>
                        <td class="text-end fw-semibold"><?php echo e(number_format($r->total, 2)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-center text-muted">No data found.</td></tr>
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

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Sales Summary'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Upswep\upswep\resources\views/admin/reports/sales_summary.blade.php ENDPATH**/ ?>