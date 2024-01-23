<?php $modulename = $this->router->class; ?>
<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>
                <li class="<?= ($modulename == 'dashboard') ? 'mm-active' : ''; ?>">
                    <a href="<?= base_url(); ?>" class="waves-effect">
                        <i class="ri-dashboard-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <?php if($this->session->user_type == 'Admin'){ ?>
                    <li class="<?= ($modulename == 'users') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/users'); ?>" class="waves-effect">
                            <i class="ri-account-circle-line"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'warehouses') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/warehouses'); ?>" class="waves-effect">
                            <i class="ri-account-circle-line"></i>
                            <span>Warehouses</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'categories') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/categories'); ?>" class="waves-effect">
                            <i class="ri-account-circle-line"></i>
                            <span>Expense Categories</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'expenses') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/expenses'); ?>" class="waves-effect">
                            <i class="ri-account-circle-line"></i>
                            <span>Expenses</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'receipts') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/receipts'); ?>" class="waves-effect">
                            <i class="ri-account-circle-line"></i>
                            <span>Receipts</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'dispatch') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/dispatch'); ?>" class="waves-effect">
                            <i class="ri-account-circle-line"></i>
                            <span>Dispatch</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'suppliers') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/suppliers'); ?>" class="waves-effect">
                            <i class="ri-account-circle-line"></i>
                            <span>Suppliers</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'buyers') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/buyers'); ?>" class="waves-effect">
                            <i class="ri-account-circle-line"></i>
                            <span>Buyers</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'inventory') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/inventory'); ?>" class="waves-effect">
                            <i class="ri-account-circle-line"></i>
                            <span>Inventory</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'EmailTemplates') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/EmailTemplates'); ?>" class="waves-effect">
                            <i class="ri-mail-send-line"></i>
                            <span>Email Templates</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'Settings') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/Settings'); ?>" class="waves-effect">
                            <i class="ri-mail-send-line"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'Tasks') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/Tasks'); ?>" class="waves-effect">
                            <i class="ri-mail-send-line"></i>
                            <span>Tasks</span>
                        </a>
                    </li>
                <?php } 
                else if($this->session->user_type == 'Employee'){ ?>
                    <li class="<?= ($modulename == 'receipts') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/receipts'); ?>" class="waves-effect">
                            <i class="ri-account-circle-line"></i>
                            <span>Receipts</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'expenses') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/expenses'); ?>" class="waves-effect">
                            <i class="ri-account-circle-line"></i>
                            <span>Expenses</span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->