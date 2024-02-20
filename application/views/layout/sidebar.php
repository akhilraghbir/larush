<?php $modulename = $this->router->class; ?>
<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>
                <li class="<?= ($modulename == 'Dashboard') ? 'mm-active' : ''; ?>">
                    <a href="<?= base_url(); ?>" class="waves-effect">
                        <i class="ri-dashboard-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <?php if($this->session->user_type == 'Admin'){ ?>
                    <li class="<?= ($modulename == 'Users') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/Users'); ?>" class="waves-effect">
                            <i class="ri-account-circle-line"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'Warehouses') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/Warehouses'); ?>" class="waves-effect">
                            <i class="ri-building-line"></i>
                            <span>Warehouses</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'Categories') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/Categories'); ?>" class="waves-effect">
                            <i class="ri-money-dollar-circle-fill"></i>
                            <span>Expense Categories</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'Expenses') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/Expenses'); ?>" class="waves-effect">
                            <i class="ri-money-dollar-circle-line"></i>
                            <span>Expenses</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'Receipts') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/Receipts'); ?>" class="waves-effect">
                            <i class="ri-file-list-3-line"></i>
                            <span>Receipts</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'Dispatch') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/Dispatch'); ?>" class="waves-effect">
                            <i class="ri-red-packet-line"></i>
                            <span>Dispatch</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'CatalyticDispatch') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/CatalyticDispatch'); ?>" class="waves-effect">
                            <i class="ri-red-packet-line"></i>
                            <span>Catalytic Dispatch</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'Invoices') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/Invoices'); ?>" class="waves-effect">
                            <i class="ri-red-packet-line"></i>
                            <span>Invoices</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'Suppliers') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/Suppliers'); ?>" class="waves-effect">
                            <i class="ri-user-heart-line"></i>
                            <span>Suppliers</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'Buyers') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/Buyers'); ?>" class="waves-effect">
                            <i class="ri-user-star-line"></i>
                            <span>Buyers</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'Leads') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/Leads'); ?>" class="waves-effect">
                            <i class="ri-money-dollar-circle-line"></i>
                            <span>Leads</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'Inventory') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/Inventory'); ?>" class="waves-effect">
                            <i class="ri-store-2-line"></i>
                            <span>Inventory</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'StockTransfer') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/StockTransfer'); ?>" class="waves-effect">
                            <i class="ri-store-2-line"></i>
                            <span>Stock Transfer</span>
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
                            <i class=" ri-settings-3-line"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'Tasks') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/Tasks'); ?>" class="waves-effect">
                            <i class="ri-task-line"></i>
                            <span>Tasks</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'Notifications') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/Notifications'); ?>" class="waves-effect">
                            <i class="ri-notification-line"></i>
                            <span>Notifications</span>
                        </a>
                    </li>
                    <li class="<?= (in_array($modulename,['StockReport','AttendanceReport'])) ? 'mm-active' : ''; ?>">
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-table-2"></i>
                            <span>Reports</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="<?= base_url('administrator/StockReport'); ?>">Stock Report</a></li>
                            <li><a href="<?= base_url('administrator/AttendanceReport'); ?>">Attendance Report</a></li>
                            <li><a href="<?= base_url('administrator/EmployeeExpenseReport'); ?>">Employee Wise Report</a></li>
                            <li><a href="<?= base_url('administrator/CompanyExpenseReport'); ?>">Company Wise Report</a></li>
                            <li><a href="<?= base_url('administrator/ProductProfitReport'); ?>">Product Profit Report</a></li>
                            <li><a href="<?= base_url('administrator/SalesReport'); ?>">Sales Report</a></li>
                            <li><a href="<?= base_url('administrator/PurchaseReport'); ?>">Purchase Report</a></li>
                            <li><a href="<?= base_url('administrator/DispatchReport'); ?>">Dispatch Report Fe / Non Fe</a></li>
                        </ul>
                    </li>
                    
                <?php } 
                else if($this->session->user_type == 'Employee'){ ?>
                    <li class="<?= ($modulename == 'Receipts') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/Receipts'); ?>" class="waves-effect">
                            <i class="ri-file-list-3-line"></i>
                            <span>Receipts</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'Expenses') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/Expenses'); ?>" class="waves-effect">
                            <i class="ri-money-dollar-circle-line"></i>
                            <span>Expenses</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'Attendance') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/Attendance'); ?>" class="waves-effect">
                            <i class="ri-money-dollar-circle-line"></i>
                            <span>Clock In & Clock Out</span>
                        </a>
                    </li>
                    <li class="<?= ($modulename == 'Leads') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url('administrator/Leads'); ?>" class="waves-effect">
                            <i class="ri-money-dollar-circle-line"></i>
                            <span>Leads</span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->