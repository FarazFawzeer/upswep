

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Product', 'subtitle' => 'View'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="card-title mb-0">Product List</h5>
                <p class="card-subtitle mb-0">All products in your system.</p>
            </div>
            <a href="<?php echo e(route('admin.products.create')); ?>" class="btn btn-primary btn-sm">
                + Add Product
            </a>
        </div>

        <div class="card-body">
            
            
            <div class="row mb-3 align-items-center">
                <div class="col-md-12">
                    <form method="GET" action="<?php echo e(route('admin.products.index')); ?>">
                        <div class="row g-2 align-items-center">

                            
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                                    </span>
                                    <input type="text" class="form-control" name="q" value="<?php echo e(request('q')); ?>"
                                        placeholder="Search name / barcode / brand...">
                                </div>
                            </div>

                            
                            <div class="col-md-2">
                                <select class="form-select" name="category_id">
                                    <option value="">All Categories</option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($cat->id); ?>"
                                            <?php echo e(request('category_id') == $cat->id ? 'selected' : ''); ?>>
                                            <?php echo e($cat->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="brand" value="<?php echo e(request('brand')); ?>"
                                    placeholder="Brand">
                            </div>

                            
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="size" value="<?php echo e(request('size')); ?>"
                                    placeholder="Size">
                            </div>

                            
                            <div class="col-md-2 d-flex gap-2">
                                <button class="btn btn-outline-secondary w-100" type="submit">
                                    Filter
                                </button>

                                <?php if(request()->query()): ?>
                                    <a class="btn btn-light w-100" href="<?php echo e(route('admin.products.index')); ?>">
                                        Reset
                                    </a>
                                <?php endif; ?>
                            </div>

                            
                            <div class="col-md-12 mt-2 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="low_stock" value="1"
                                        id="low_stock" <?php echo e(request('low_stock') ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="low_stock">
                                        Low stock only
                                    </label>
                                </div>

                                <div class="d-flex gap-2">
                                    <a href="<?php echo e(route('admin.products.barcodes.print')); ?>"
                                        class="btn btn-outline-dark btn-sm" target="_blank" data-bs-toggle="tooltip"
                                        title="Print all barcodes (based on current filter)">
                                        <iconify-icon icon="solar:printer-outline"></iconify-icon>
                                        Print All
                                    </a>

                                    <button type="button" id="printSelectedBtn" class="btn btn-outline-primary btn-sm"
                                        data-bs-toggle="tooltip" title="Print barcodes for selected products">
                                        <iconify-icon icon="solar:checklist-outline"></iconify-icon>
                                        Print Selected
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>


            
            
            <div class="table-responsive">
                <table class="table table-hover table-centered">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Barcode</th>
                            <th>Prices</th>
                            <th>Stock</th>
                            <th>Updated</th>
                            <th style="width: 160px;">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr id="product-<?php echo e($product->id); ?>">
                                <td>
                                    <input type="checkbox" class="product-check" value="<?php echo e($product->id); ?>">
                                </td>

                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="<?php echo e($product->image ? asset('storage/' . $product->image) : asset('/images/users/avatar-6.jpg')); ?>"
                                            class="avatar-sm rounded" alt="img">
                                        <div>
                                            <h6 class="mb-0"><?php echo e($product->name); ?></h6>
                                            <small class="text-muted">
                                                <?php echo e($product->brand ?? '—'); ?> | <?php echo e($product->size ?? '—'); ?> |
                                                <?php echo e($product->color ?? '—'); ?>

                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <td><?php echo e($product->category?->name ?? '—'); ?></td>

                                <td>
                                    <span class="badge bg-light text-dark"><?php echo e($product->barcode); ?></span>
                                </td>

                                <td>
                                    <div>Cost: <strong><?php echo e(number_format($product->cost_price, 2)); ?></strong></div>
                                    <div>Sell: <strong><?php echo e(number_format($product->selling_price, 2)); ?></strong></div>
                                </td>

                                <td>
                                    <?php
                                        $isLow = $product->stock_qty <= $product->low_stock_alert_qty;
                                    ?>

                                    <span class="badge <?php echo e($isLow ? 'bg-danger' : 'bg-success'); ?>">
                                        <?php echo e($product->stock_qty); ?>

                                    </span>

                                    <small class="text-muted d-block">
                                        Alert: <?php echo e($product->low_stock_alert_qty); ?>

                                    </small>
                                </td>

                                <td><?php echo e(optional($product->updated_at)->format('d M Y, h:i A')); ?></td>

                                <td>
                                    <div class="d-flex gap-3 justify-content-center align-items-center">

                                        
                                        <a href="<?php echo e(route('admin.products.barcodes.print', ['ids' => $product->id])); ?>"
                                            class="text-dark fs-5" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Print Barcode" target="_blank">
                                            <iconify-icon icon="solar:printer-outline"></iconify-icon>
                                        </a>

                                        
                                        <a href="<?php echo e(route('admin.products.show', $product->id)); ?>"
                                            class="text-primary fs-5" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="View">
                                            <iconify-icon icon="solar:eye-outline"></iconify-icon>
                                        </a>

                                        
                                        <a href="<?php echo e(route('admin.products.edit', $product->id)); ?>"
                                            class="text-warning fs-5" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Edit">
                                            <iconify-icon icon="solar:pen-outline"></iconify-icon>
                                        </a>

                                        
                                        <button type="button" class="btn p-0 text-danger fs-5 delete-product"
                                            data-id="<?php echo e($product->id); ?>" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Delete">
                                            <iconify-icon icon="solar:trash-bin-trash-outline"></iconify-icon>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted">No products found.</td>
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
document.addEventListener('DOMContentLoaded', function () {

    // Tooltips (once)
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    // Delete
    document.querySelectorAll('.delete-product').forEach(button => {
        button.addEventListener('click', function() {
            let id = this.dataset.id;

            Swal.fire({
                title: 'Are you sure?',
                text: "This product will be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("<?php echo e(url('admin/products')); ?>/" + id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('product-' + id)?.remove();
                            Swal.fire('Deleted!', data.message, 'success');
                        } else {
                            Swal.fire('Error!', data.message || 'Something went wrong!', 'error');
                        }
                    })
                    .catch(() => Swal.fire('Error!', 'Something went wrong!', 'error'));
                }
            });
        });
    });

    // Select all checkbox
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            document.querySelectorAll('.product-check').forEach(ch => ch.checked = selectAll.checked);
        });
    }

    // Print selected
    const printBtn = document.getElementById('printSelectedBtn');
    if (printBtn) {
        printBtn.addEventListener('click', function() {
            const ids = Array.from(document.querySelectorAll('.product-check:checked')).map(el => el.value);

            if (ids.length === 0) {
                Swal.fire('Select products', 'Please select at least one product.', 'info');
                return;
            }

            const url = "<?php echo e(route('admin.products.barcodes.print')); ?>" + "?ids=" + ids.join(',');
            window.open(url, "_blank");
        });
    } else {
        console.log('printSelectedBtn not found');
    }

});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Product View'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Upswep\upswep\resources\views/admin/products/index.blade.php ENDPATH**/ ?>