

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.partials.page-title', ['title' => 'Reports', 'subtitle' => 'Daily Sales'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-0">Daily Sales</h5>
            <p class="card-subtitle mb-0">Filter sales by date.</p>
        </div>
        <form class="d-flex gap-2" method="GET" action="<?php echo e(route('admin.reports.sales.daily')); ?>">
            <input type="date" class="form-control form-control-sm" name="date" value="<?php echo e($date); ?>">
            <button class="btn btn-outline-secondary btn-sm" type="submit">Filter</button>
        </form>
    </div>

    <div class="card-body">

        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="border rounded p-3">
                    <div class="text-muted">Bills</div>
                    <div class="fs-4 fw-semibold"><?php echo e($summary->bills ?? 0); ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3">
                    <div class="text-muted">Subtotal</div>
                    <div class="fs-4 fw-semibold"><?php echo e(number_format($summary->sub_total ?? 0, 2)); ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3">
                    <div class="text-muted">Discount</div>
                    <div class="fs-4 fw-semibold"><?php echo e(number_format($summary->discount_total ?? 0, 2)); ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3">
                    <div class="text-muted">Grand Total</div>
                    <div class="fs-4 fw-semibold"><?php echo e(number_format($summary->grand_total ?? 0, 2)); ?></div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-centered">
                <thead class="table-light">
                <tr>
                    <th>Invoice</th>
                    <th>Date/Time</th>
                    <th>Cashier</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Discount</th>
                    <th class="text-end">Tax</th>
                </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-semibold"><?php echo e($s->invoice_no); ?></td>
                        <td><?php echo e(optional($s->sale_date)->format('d M Y, h:i A')); ?></td>
                        <td><?php echo e($s->createdBy?->name ?? '—'); ?></td>
                        <td class="text-end"><?php echo e(number_format($s->grand_total, 2)); ?></td>
                        <td class="text-end"><?php echo e(number_format($s->discount_total, 2)); ?></td>
                        <td class="text-end"><?php echo e(number_format($s->tax_total, 2)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No sales found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-3">
                <?php echo e($sales->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Daily Sales'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Upswep\upswep\resources\views/admin/reports/sales_daily.blade.php ENDPATH**/ ?>