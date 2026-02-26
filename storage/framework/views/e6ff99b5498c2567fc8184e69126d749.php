

<?php $__env->startSection('content'); ?>

<?php echo $__env->make('layouts.partials.page-title', ['title' => 'Taplox', 'subtitle' => 'Dashboard'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="row">
    
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                            <iconify-icon icon="solar:money-bag-outline"
                                class="fs-32 text-primary avatar-title"></iconify-icon>
                        </div>
                    </div>
                    <div class="col-6 text-end">
                        <p class="text-muted mb-0 text-truncate">Today Sales</p>
                        <h3 class="text-dark mt-2 mb-0">Rs <?php echo e(number_format($todaySalesAmount, 2)); ?></h3>
                    </div>
                </div>
            </div>
            <div class="card-footer border-0 py-2 bg-light bg-opacity-50 mx-2 mb-2">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <?php $isUp = $salesChangePercent >= 0; ?>
                        <span class="<?php echo e($isUp ? 'text-success' : 'text-danger'); ?>">
                            <i class="bx <?php echo e($isUp ? 'bxs-up-arrow' : 'bxs-down-arrow'); ?> fs-12"></i>
                            <?php echo e(number_format($salesChangePercent, 2)); ?>%
                        </span>
                        <span class="text-muted ms-1 fs-12">From last month</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                            <iconify-icon icon="solar:bag-check-outline"
                                class="fs-32 text-primary avatar-title"></iconify-icon>
                        </div>
                    </div>
                    <div class="col-6 text-end">
                        <p class="text-muted mb-0 text-truncate">Today Orders</p>
                        <h3 class="text-dark mt-2 mb-0"><?php echo e(number_format($todaySalesCount)); ?></h3>
                    </div>
                </div>
            </div>
            <div class="card-footer border-0 py-2 bg-light bg-opacity-50 mx-2 mb-2">
                <span class="text-muted fs-12">Sales count today</span>
            </div>
        </div>
    </div>

    
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                            <iconify-icon icon="solar:box-outline"
                                class="fs-32 text-primary avatar-title"></iconify-icon>
                        </div>
                    </div>
                    <div class="col-6 text-end">
                        <p class="text-muted mb-0 text-truncate">Products</p>
                        <h3 class="text-dark mt-2 mb-0"><?php echo e(number_format($totalProducts)); ?></h3>
                    </div>
                </div>
            </div>
            <div class="card-footer border-0 py-2 bg-light bg-opacity-50 mx-2 mb-2">
                <span class="text-muted fs-12">Total products in system</span>
            </div>
        </div>
    </div>

    
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-md bg-danger bg-opacity-10 rounded-circle">
                            <iconify-icon icon="solar:shield-warning-outline"
                                class="fs-32 text-danger avatar-title"></iconify-icon>
                        </div>
                    </div>
                    <div class="col-6 text-end">
                        <p class="text-muted mb-0 text-truncate">Low Stock</p>
                        <h3 class="text-dark mt-2 mb-0"><?php echo e(number_format($lowStockCount)); ?></h3>
                    </div>
                </div>
            </div>
            <div class="card-footer border-0 py-2 bg-light bg-opacity-50 mx-2 mb-2">
                <div class="d-flex justify-content-between">
                    <span class="text-muted fs-12">Out of stock: <?php echo e(number_format($outOfStockCount)); ?></span>
                    <a class="fs-12" href="<?php echo e(route('admin.reports.low-stock')); ?>">View</a>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-6">
        <div class="card card-height-100">
            <div class="card-header d-flex align-items-center justify-content-between gap-2">
                <h4 class="card-title flex-grow-1">Sales (Last 14 Days)</h4>
            </div>

            <div class="card-body pt-0">
                <div dir="ltr">
                    <div id="dash-performance-chart" class="apex-charts"></div>
                </div>
            </div>

        </div>
    </div>

    <div class="col-lg-6">
        <div class="card card-height-100">
            <div class="card-header d-flex align-items-center justify-content-between gap-2">
                <h4 class="card-title flex-grow-1">Quick Stats</h4>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <div class="text-muted">Customers</div>
                            <div class="fs-4 fw-semibold"><?php echo e(number_format($totalCustomers)); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <div class="text-muted">Suppliers</div>
                            <div class="fs-4 fw-semibold"><?php echo e(number_format($totalSuppliers)); ?></div>
                        </div>
                    </div>
                </div>
                <div class="text-muted mt-3">
                    (We can add more widgets later: profit, stock value, etc.)
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="card-title">New Users</h4>

                    <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-sm btn-primary">
                        <i class="bx bx-user me-1"></i>View Users
                    </a>
                </div>
            </div>

            <div class="table-responsive table-centered">
                <table class="table mb-0">
                    <thead class="bg-light bg-opacity-50">
                        <tr>
                            <th class="border-0 py-2">Date</th>
                            <th class="border-0 py-2">User</th>
                            <th class="border-0 py-2">Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $latestUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e(optional($u->created_at)->format('d M, Y')); ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="<?php echo e($u->image_path ? asset($u->image_path) : asset('/images/users/avatar-6.jpg')); ?>"
                                             class="img-fluid avatar-xs rounded-circle" alt="img">
                                        <span class="align-middle"><?php echo e($u->name); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark"><?php echo e($u->type ?? 'User'); ?></span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="card-title">Recent Sales</h4>
                    <a href="#" class="btn btn-sm btn-primary">
                        <i class="bx bx-receipt me-1"></i>Reports
                    </a>
                </div>
            </div>

            <div class="table-responsive table-centered">
                <table class="table mb-0">
                    <thead class="bg-light bg-opacity-50">
                        <tr>
                            <th class="border-0 py-2">Invoice</th>
                            <th class="border-0 py-2">Date</th>
                            <th class="border-0 py-2">Customer</th>
                            <th class="border-0 py-2">Cashier</th>
                            <th class="border-0 py-2">Payment</th>
                            <th class="border-0 py-2 text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentSales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><a href="javascript:void(0);"><?php echo e($s->invoice_no); ?></a></td>
                                <td><?php echo e(optional($s->sale_date)->format('d M Y, h:i A')); ?></td>
                                <td><?php echo e($s->customer?->name ?? 'Walk-in'); ?></td>
                                <td><?php echo e($s->cashier?->name ?? '—'); ?></td>
                                <td><?php echo e(strtoupper($s->payment_method ?? 'cash')); ?></td>
                                <td class="text-end fw-semibold">Rs <?php echo e(number_format($s->grand_total, 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">No sales yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    window.__DASH_SALES__ = {
        labels: <?php echo json_encode($chartLabels, 15, 512) ?>,
        series: <?php echo json_encode($chartSeries, 15, 512) ?>
    };
</script>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/pages/dashboard.js']); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Dashboard'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Upswep\upswep\resources\views/admin/dashboard/index.blade.php ENDPATH**/ ?>