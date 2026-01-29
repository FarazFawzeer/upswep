

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Product', 'subtitle' => 'Create'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">New Product</h5>
        </div>

        <div class="card-body">
            <div id="message"></div>

            <form id="createProductForm" action="<?php echo e(route('admin.products.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cat->id); ?>" <?php echo e(old('category_id') == $cat->id ? 'selected' : ''); ?>>
                                    <?php echo e($cat->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Supplier (Optional)</label>
                        <select name="supplier_id" class="form-select">
                            <option value="">Select Supplier</option>
                            <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($sup->id); ?>" <?php echo e(old('supplier_id') == $sup->id ? 'selected' : ''); ?>>
                                    <?php echo e($sup->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo e(old('name')); ?>"
                               placeholder="Ex: Formal Shirt" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Brand</label>
                        <input type="text" name="brand" class="form-control" value="<?php echo e(old('brand')); ?>" placeholder="Ex: Nike">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Size</label>
                        <input type="text" name="size" class="form-control" value="<?php echo e(old('size')); ?>" placeholder="Ex: M">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Color</label>
                        <input type="text" name="color" class="form-control" value="<?php echo e(old('color')); ?>" placeholder="Ex: Black">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Cost Price</label>
                        <input type="number" step="0.01" name="cost_price" class="form-control"
                               value="<?php echo e(old('cost_price', 0)); ?>" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Selling Price</label>
                        <input type="number" step="0.01" name="selling_price" class="form-control"
                               value="<?php echo e(old('selling_price', 0)); ?>" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Opening Stock</label>
                        <input type="number" name="stock_qty" class="form-control" value="<?php echo e(old('stock_qty', 0)); ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Low Stock Alert Qty</label>
                        <input type="number" name="low_stock_alert_qty" class="form-control" value="<?php echo e(old('low_stock_alert_qty', 5)); ?>">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Barcode (Optional)</label>
                        <input type="text" name="barcode" class="form-control" value="<?php echo e(old('barcode')); ?>"
                               placeholder="Leave empty to auto-generate">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="1" <?php echo e(old('status', '1') == '1' ? 'selected' : ''); ?>>Active</option>
                            <option value="0" <?php echo e(old('status') == '0' ? 'selected' : ''); ?>>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Product Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*" id="imageInput">
                        <small class="text-muted">jpg, png, webp (max 2MB)</small>
                    </div>

                    <div class="col-md-6 mb-3 d-flex align-items-end">
                        <img id="preview" src="<?php echo e(asset('/images/users/avatar-6.jpg')); ?>"
                             class="rounded border" style="height: 80px; width: 80px; object-fit: cover;" alt="preview">
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-light me-2">Back</a>
                    <button type="submit" class="btn btn-primary">Create Product</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // image preview
        const imgInput = document.getElementById('imageInput');
        const preview = document.getElementById('preview');

        imgInput?.addEventListener('change', (e) => {
            const file = e.target.files?.[0];
            if (!file) return;
            preview.src = URL.createObjectURL(file);
        });

        // ajax submit
        document.getElementById('createProductForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let form = this;
            let formData = new FormData(form);

            fetch(form.action, {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                let box = document.getElementById('message');

                if (data.success) {
                    box.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                    form.reset();
                    preview.src = "<?php echo e(asset('/images/users/avatar-6.jpg')); ?>";
                    setTimeout(() => box.innerHTML = "", 2500);
                } else {
                    let errors = data.errors ? Object.values(data.errors).flat().join('<br>') : (data.message || 'Something went wrong');
                    box.innerHTML = `<div class="alert alert-danger">${errors}</div>`;
                }
            })
            .catch(err => {
                document.getElementById('message').innerHTML =
                    `<div class="alert alert-danger">Error: ${err}</div>`;
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Product Create'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Upswep\upswep\resources\views/admin/products/create.blade.php ENDPATH**/ ?>