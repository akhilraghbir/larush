<?php $modulename = $this->router->class; ?>
<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>
                <li>
                    <a href="<?= base_url(); ?>" class="waves-effect">
                        <i class="ri-dashboard-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('administrator/users'); ?>" class="waves-effect">
                        <i class="ri-account-circle-line"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('administrator/suppliers'); ?>" class="waves-effect">
                        <i class="ri-account-circle-line"></i>
                        <span>Suppliers</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('administrator/buyers'); ?>" class="waves-effect">
                        <i class="ri-account-circle-line"></i>
                        <span>Buyers</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('administrator/inventory'); ?>" class="waves-effect">
                        <i class="ri-account-circle-line"></i>
                        <span>Inventory</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('administrator/EmailTemplates'); ?>" class="waves-effect">
                        <i class="ri-mail-send-line"></i>
                        <span>Email Templates</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->