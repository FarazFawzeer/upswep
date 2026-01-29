

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.partials.page-title', ['title' => 'Stock Entry', 'subtitle' => 'View'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-0">Stock Entry Details</h5>
            <p class="card-subtitle mb-0">
                Entry No:
                <span class="badge bg-light text-dark">
                    <?php echo e($entry->entry_no ?? '—'); ?>

                </span>
            </p>
        </div>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-dark btn-sm" onclick="window.print()">
                <iconify-icon icon="solar:printer-outline"></iconify-icon>
                Print
            </button>

            <a href="<?php echo e(route('admin.stock-entries.index')); ?>" class="btn btn-light btn-sm">
                Back
            </a>
        </div>
    </div>

    <div class="card-body">

        
        <div class="row mb-4">
            <div class="col-md-3">
                <strong>Supplier</strong>
                <div class="text-muted">
                    <?php echo e($entry->supplier?->name ?? '—'); ?>

                </div>
            </div>

            <div class="col-md-3">
                <strong>Entry Date</strong>
                <div class="text-muted">
                    <?php echo e($entry->entry_date?->format('d M Y')); ?>

                </div>
            </div>

            <div class="col-md-3">
                <strong>Created By</strong>
                <div class="text-muted">
                    <?php echo e($entry->createdBy?->name ?? '—'); ?>

                </div>
            </div>

            <div class="col-md-3">
                <strong>Notes</strong>
                <div class="text-muted">
                    <?php echo e($entry->note ?? '—'); ?>

                </div>
            </div>
        </div>

        
        <div class="table-responsive">
            <table class="table table-hover table-centered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Barcode</th>
                        <th class="text-end">Qty</th>
                        <th class="text-end">Unit Cost</th>
                        <th class="text-end">Line Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $entry->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>

                            <td>
                                <strong><?php echo e($item->product?->name); ?></strong>
                                <div class="text-muted small">
                                    <?php echo e($item->product?->brand ?? '—'); ?>

                                </div>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark">
                                    <?php echo e($item->product?->barcode); ?>

                                </span>
                            </td>

                            <td class="text-end">
                                <?php echo e($item->qty); ?>

                            </td>

                            <td class="text-end">
                                <?php echo e(number_format($item->unit_cost, 2)); ?>

                            </td>

                            <td class="text-end">
                                <?php echo e(number_format($item->line_total, 2)); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        
        <div class="row mt-4">
            <div class="col-md-6"></div>

            <div class="col-md-6">
                <div class="border rounded p-3">
                    <div class="d-flex justify-content-between">
                        <span>Total Qty</span>
                        <strong><?php echo e($entry->total_qty); ?></strong>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <span>Total Cost</span>
                        <strong><?php echo e(number_format($entry->total_cost, 2)); ?></strong>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Stock Entry View'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Upswep\upswep\resources\views/admin/stock_entries/show.blade.php ENDPATH**/ ?>