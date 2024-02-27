<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?= $breadcrumbs['title']; ?> | <?= SITENAME; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?= SITENAME; ?>" name="description" />
    <meta content="Webartise" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= base_url('assets/backend/');?>images/larush_logo.jpg">
    <!-- jquery.vectormap css -->
    <link href="<?= base_url('assets/backend/');?>libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <!-- DataTables -->
    <link href="<?= base_url('assets/backend/');?>libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="<?= base_url('assets/backend/');?>libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- Bootstrap Css -->
    <link href="<?= base_url('assets/backend/');?>css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?= base_url('assets/backend/');?>css/icons.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/backend/');?>libs/toastr/build/toastr.min.css">
    <!-- App Css-->
    <link href="<?= base_url('assets/backend/');?>css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <script src="<?= base_url('assets/backend/');?>libs/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets/backend/');?>libs/toastr/build/toastr.min.js"></script>
    <!-- Required datatable js -->
    <script src="<?= base_url('assets/backend/');?>libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url('assets/backend/');?>libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script> 
    <link href="<?= base_url('assets/backend/');?>libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css" integrity="sha512-0V10q+b1Iumz67sVDL8LPFZEEavo6H/nBSyghr7mm9JEQkOAm91HNoZQRvQdjennBb/oEuW+8oZHVpIKq+d25g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="<?= base_url('assets/backend/'); ?>libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('assets/backend/'); ?>libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url('assets/backend/'); ?>libs/pdfmake/build/pdfmake.min.js"></script>
<script src="<?= base_url('assets/backend/'); ?>libs/pdfmake/build/vfs_fonts.js"></script>
<script src="<?= base_url('assets/backend/'); ?>libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?= base_url('assets/backend/'); ?>libs/datatables.net-buttons/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script>
    var base_url = "<?= base_url();?>";
    var role = "<?= $this->session->user_type;?>";
</script>
<?=	$this->messages->getMessage();?>
</head>
<body data-sidebar="dark">
    <!-- <body data-layout="horizontal" data-topbar="dark"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box">
                        <a href="<?= base_url(); ?>" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="<?= base_url('assets/backend/');?>images/larush_logo.jpg" alt="logo-sm-dark" height="80">
                            </span>
                            <span class="logo-lg">
                                <img src="<?= base_url('assets/backend/');?>images/larush_logo.jpg" alt="logo-dark" height="80">
                            </span>
                        </a>

                        <a href="<?= base_url(); ?>" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="<?= base_url('assets/backend/');?>images/larush_logo.jpg" alt="logo-sm-light" height="80">
                            </span>
                            <span class="logo-lg">
                                <img src="<?= base_url('assets/backend/');?>images/larush_logo.jpg" alt="logo-light" height="80">
                            </span>
                        </a>
                    </div>

                    <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                        <i class="ri-menu-2-line align-middle"></i>
                    </button>
                </div>

                <div class="d-flex">
                    <div class="dropdown d-none d-lg-inline-block ms-1">
                        <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                            <i class="ri-fullscreen-line"></i>
                        </button>
                    </div>

                      <div class="dropdown d-inline-block notifications-dropdown">
                            <button type="button" onclick="getNotifications()" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                                  data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-notification-3-line"></i>
                                <span class="noti-dot"></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                                aria-labelledby="page-header-notifications-dropdown">
                                <div class="p-3">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0"> Notifications </h6>
                                        </div>
                                    </div>
                                </div>
                                <div data-simplebar style="max-height: 230px;">
                                    
                                </div>
                                <div class="p-2 border-top">
                                    <div class="d-grid">
                                        <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                                            <i class="mdi mdi-arrow-right-circle me-1"></i> View More..
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <div class="dropdown d-inline-block user-dropdown">
                        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle header-profile-user" src="<?= base_url('assets/backend/');?>images/users/avatar-2.jpg" alt="Header Avatar">
                            <span class="d-none d-xl-inline-block ms-1"><?= $this->session->name;?></span>
                            <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <a class="dropdown-item" href="<?= base_url('profile'); ?>"><i class="ri-user-line align-middle me-1"></i> Profile</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="<?= base_url('login/dologout'); ?>"><i class="ri-shut-down-line align-middle me-1 text-danger"></i> Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>