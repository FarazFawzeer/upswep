

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Stock Entry', 'subtitle' => 'View'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="card-title mb-0">Stock Entry List</h5>
                <p class="card-subtitle mb-0">All stock purchase entries in your system.</p>
            </div>

            <a href="<?php echo e(route('admin.stock-entries.create')); ?>" class="btn btn-primary btn-sm">
                + New Stock Entry
            </a>
        </div>

        <div class="card-body">

            
            <div class="row mb-3 align-items-center">
                <div class="col-md-6 ms-auto">
                    <form method="GET" action="<?php echo e(route('admin.stock-entries.index')); ?>">
                        <div class="input-group">
                            <span class="input-group-text">
                                <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                            </span>

                            <input type="text" class="form-control" name="q" value="<?php echo e(request('q')); ?>"
                                placeholder="Search entry no / supplier / date (YYYY-MM-DD)">

                            <button class="btn btn-outline-secondary" type="submit">Search</button>

                            <?php if(request()->filled('q')): ?>
                                <a class="btn btn-light" href="<?php echo e(route('admin.stock-entries.index')); ?>">Reset</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            
            <div class="table-responsive">
                <table class="table table-hover table-centered">
                    <thead class="table-light">
                        <tr>
                            <th>Entry No</th>
                            <th>Supplier</th>
                            <th>Date</th>
                            <th>Total Qty</th>
                            <th>Total Cost</th>
                            <th>Created By</th>
                            <th>Updated</th>
                            <th style="width: 120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $totalQty = $entry->items->sum('qty');
                                $totalCost = $entry->items->sum('line_total');
                            ?>
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark"><?php echo e($entry->entry_no); ?></span>
                                </td>
                                <td><?php echo e($entry->supplier?->name ?? '—'); ?></td>
                                <td><?php echo e(optional($entry->entry_date)->format('d M Y')); ?></td>

                                <td>
                                    <span class="badge bg-success"><?php echo e($totalQty); ?></span>
                                </td>

                                <td>
                                    <strong><?php echo e(number_format($totalCost, 2)); ?></strong>
                                </td>

                                <td><?php echo e($entry->createdBy?->name ?? '—'); ?></td>
                                <td><?php echo e(optional($entry->updated_at)->format('d M Y, h:i A')); ?></td>

                                <td>
                                    <div class="d-flex gap-3 justify-content-center align-items-center">
                                        <a href="<?php echo e(route('admin.stock-entries.show', $entry->id)); ?>"
                                            class="text-primary fs-5" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="View">
                                            <iconify-icon icon="solar:eye-outline"></iconify-icon>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted">No stock entries found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                
                <div class="d-flex justify-content-end mt-3">
                    <?php echo e($entries->links()); ?>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Stock Entry View'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Upswep\upswep\resources\views/admin/stock_entries/index.blade.php ENDPATH**/ ?>