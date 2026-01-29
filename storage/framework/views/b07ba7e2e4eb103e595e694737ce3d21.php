

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.partials.page-title', ['title' => 'Supplier', 'subtitle' => 'Edit'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Edit Supplier</h5>
    </div>

    <div class="card-body">
        <div id="message"></div>

        <form id="editSupplierForm" action="<?php echo e(route('admin.suppliers.update', $supplier->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Supplier Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo e($supplier->name); ?>" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone (Optional)</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo e($supplier->phone); ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email (Optional)</label>
                    <input type="email" name="email" class="form-control" value="<?php echo e($supplier->email); ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="1" <?php echo e($supplier->status ? 'selected' : ''); ?>>Active</option>
                        <option value="0" <?php echo e(!$supplier->status ? 'selected' : ''); ?>>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Address (Optional)</label>
                <textarea name="address" class="form-control" rows="3"><?php echo e($supplier->address); ?></textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo e(route('admin.suppliers.index')); ?>" class="btn btn-light">Back</a>
                <button type="submit" class="btn btn-primary">Update Supplier</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('editSupplierForm');
    const messageBox = document.getElementById('message');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch(form.action, {
            method: "POST", // method spoofing with _method=PUT
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                "Accept": "application/json"
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                messageBox.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                setTimeout(() => messageBox.innerHTML = "", 2500);
            } else {
                const errors = Object.values(data.errors || {}).flat().join('<br>');
                messageBox.innerHTML = `<div class="alert alert-danger">${errors || 'Something went wrong!'}</div>`;
            }
        })
        .catch(() => {
            messageBox.innerHTML = `<div class="alert alert-danger">Something went wrong!</div>`;
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Supplier Edit'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Upswep\upswep\resources\views/admin/suppliers/edit.blade.php ENDPATH**/ ?>