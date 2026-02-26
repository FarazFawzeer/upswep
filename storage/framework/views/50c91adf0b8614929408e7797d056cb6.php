<div class="app-sidebar">
    <!-- Sidebar Logo -->
    <div class="logo-box">
  <a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none">
    <h1 class="text-center text-black mt-3 mb-0">UPSWEP</h1>
</a>
        
    </div>

    <div class="scrollbar" data-simplebar>

        <ul class="navbar-nav" id="navbar-nav">

            <li class="menu-title">Menu...</li>

            
            <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('pos.index')); ?>">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:cash-out-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> POS </span>
                </a>
            </li>


            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarAdmin" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarAdmin">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:user-circle-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Admin</span>
                </a>
                <div class="collapse" id="sidebarAdmin">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('second', ['admin', 'create'])); ?>">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.users.index')); ?>">View </a>
                        </li>


                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarCustomer" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarCustomer">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:user-circle-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Customer</span>
                </a>
                <div class="collapse" id="sidebarCustomer">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('second', ['customer', 'create'])); ?>">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.customers.index')); ?>">View </a>
                        </li>


                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarCategory" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarCategory">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:tag-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Categories</span>
                </a>
                <div class="collapse" id="sidebarCategory">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.categories.create')); ?>">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.categories.index')); ?>">View</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarSupplier" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarSupplier">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:buildings-3-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Supplier</span>
                </a>
                <div class="collapse" id="sidebarSupplier">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.suppliers.create')); ?>">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.suppliers.index')); ?>">View</a>
                        </li>
                    </ul>
                </div>
            </li>


            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarProducts" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarProducts">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:box-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Products</span>
                </a>
                <div class="collapse" id="sidebarProducts">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.products.create')); ?>">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.products.index')); ?>">View</a>
                        </li>
                    </ul>
                </div>
            </li>

            

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarStock" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarStock">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:box-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Stock</span>
                </a>
                <div class="collapse" id="sidebarStock">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.stock-entries.create')); ?>">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.stock-entries.index')); ?>">Stock Entries</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link"
                                href="<?php echo e(route('admin.stock-adjustments.create')); ?>">Adjustments</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.stock-movements.index')); ?>">Stock
                                History</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarReports" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarReports">

                    <span class="nav-icon">
                        <iconify-icon icon="solar:chart-square-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Reports </span>
                </a>

                <div class="collapse" id="sidebarReports">
                    <ul class="nav sub-navbar-nav">

                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.reports.low-stock')); ?>">
                                Low Stock
                            </a>
                        </li>

                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.reports.sales.daily')); ?>">Daily Sales</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.reports.sales.summary')); ?>">Weekly /
                                Monthly</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.reports.sales.byCashier')); ?>">Sales by
                                Cashier</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.reports.sales.bestProducts')); ?>">Best
                                Selling Products</a>
                        </li>

                        
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.reports.low-stock')); ?>">Low Stock</a>
                        </li>

                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.reports.profit')); ?>">Profit Report</a>
                        </li>

                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.reports.stock-summary')); ?>">Stock
                                Summary</a>
                        </li>

                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.reports.out-of-stock')); ?>">Out of Stock</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.reports.stock-movements')); ?>">Stock
                                Movements</a>
                        </li>
                    </ul>
                </div>
            </li>


            <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('admin.profile.edit')); ?>">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:widget-2-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Profile </span>

                </a>
            </li>


            
        </ul>
    </div>
</div>
<?php /**PATH F:\Personal Projects\Upswep\upswep\resources\views/layouts/partials/sidebar.blade.php ENDPATH**/ ?>