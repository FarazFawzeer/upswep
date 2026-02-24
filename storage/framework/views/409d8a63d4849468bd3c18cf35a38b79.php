

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.partials.page-title', ['title' => 'Reports', 'subtitle' => 'Low Stock'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-0">Low Stock Products</h5>
            <p class="card-subtitle mb-0">Products where stock_qty <= low_stock_alert_qty</p>
        </div>
        <a href="<?php echo e(route('admin.products.index', ['low_stock' => 1])); ?>" class="btn btn-light btn-sm">
            Open In Products
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-centered">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Barcode</th>
                        <th class="text-center">Stock</th>
                        <th class="text-center">Alert</th>
                        <th class="text-center" style="width: 90px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="<?php echo e($p->image ? asset('storage/'.$p->image) : asset('/images/users/avatar-6.jpg')); ?>"
                                         class="avatar-sm rounded" alt="img">
                                    <div>
                                        <h6 class="mb-0"><?php echo e($p->name); ?></h6>
                                        <small class="text-muted"><?php echo e($p->brand ?? '—'); ?> | <?php echo e($p->size ?? '—'); ?></small>
                                    </div>
                                </div>
                            </td>

                            <td><?php echo e($p->category?->name ?? '—'); ?></td>

                            <td>
                                <span class="badge bg-light text-dark"><?php echo e($p->barcode); ?></span>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-danger"><?php echo e($p->stock_qty); ?></span>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-secondary"><?php echo e($p->low_stock_alert_qty); ?></span>
                            </td>

                            <td class="text-center">
                                <a href="<?php echo e(route('admin.products.edit', $p->id)); ?>"
                                   class="text-warning fs-5"
                                   data-bs-toggle="tooltip"
                                   title="Edit">
                                    <iconify-icon icon="solar:pen-outline"></iconify-icon>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No low stock items 🎉</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-3">
                <?php echo e($products->links()); ?>

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

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Low Stock Report'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Upswep\upswep\resources\views/admin/reports/low_stock.blade.php ENDPATH**/ ?>