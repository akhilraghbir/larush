<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Login | <?= SITENAME;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?= SITENAME; ?>" name="description" />
    <meta content="Themesdesign" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= base_url('assets/backend/');?>images/larush_logo.jpg">
    <!-- Bootstrap Css -->
    <link href="<?= base_url('assets/backend/');?>css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?= base_url('assets/backend/');?>css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?= base_url('assets/backend/');?>css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
</head>
<body class="auth-body-bg">
    <div>
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="col-lg-4">
                    <div class="authentication-page-content p-4 d-flex align-items-center min-vh-100">
                        <div class="w-100">
                            <div class="row justify-content-center">
                                <div class="col-lg-9">
                                    <div>
                                        <div class="text-center">
                                            <div>
                                                <a href="<?= base_url(); ?>" class="">
                                                    <img src="<?= base_url('assets/backend/');?>images/larush_logo.jpg" alt="" height="120" class="auth-logo logo-dark mx-auto">
                                                </a>
                                            </div>
                                            <h4 class="font-size-18 mt-4">Welcome Back !</h4>
                                            <p class="text-muted">Sign in to continue to <?= SITENAME;?>.</p>
                                            <?php echo $this->messages->getMessageFront(); ?>
                                        </div>

                                        <div class="p-2 mt-5">
                                            <form class="" method="post" action="<?= base_url('login/userlogin'); ?>">
                                                <div class="mb-3 auth-form-group-custom mb-4">
                                                    <i class="ri-user-2-line auti-custom-input-icon"></i>
                                                    <label for="username" class="fw-semibold">Username</label>
                                                    <input type="text" class="form-control" name="username" id="username" placeholder="Enter username">
                                                </div>

                                                <div class="mb-3 auth-form-group-custom mb-4">
                                                    <i class="ri-lock-2-line auti-custom-input-icon"></i>
                                                    <label for="userpassword">Password</label>
                                                    <input type="password" class="form-control" name="password" id="userpassword" placeholder="Enter password">
                                                </div>

                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="customControlInline">
                                                    <label class="form-check-label" for="customControlInline">Remember me</label>
                                                </div>

                                                <div class="mt-4 text-center">
                                                    <button class="btn btn-primary w-md waves-effect waves-light" name="submit" type="submit">Log In</button>
                                                </div>

                                                <div class="mt-4 text-center">
                                                    <a href="<?= base_url('forgot-password'); ?>" class="text-muted"><i class="mdi mdi-lock me-1"></i> Forgot your password?</a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="authentication-bg">
                        <div class="bg-overlay"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- JAVASCRIPT -->
    <script src="<?= base_url('assets/backend/');?>libs/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets/backend/');?>libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/backend/');?>libs/metismenu/metisMenu.min.js"></script>
    <script src="<?= base_url('assets/backend/');?>libs/simplebar/simplebar.min.js"></script>
    <script src="<?= base_url('assets/backend/');?>libs/node-waves/waves.min.js"></script>
    <script src="<?= base_url('assets/backend/');?>js/app.js"></script>
</body>
</html>