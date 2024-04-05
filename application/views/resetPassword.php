<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Forgot Password | <?= SITENAME;?></title>
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
                                            <h4 class="font-size-18 mt-4">Create New password!</h4>
                                            <?php echo $this->messages->getMessageFront(); ?>
                                        </div>

                                        <div class="p-2">
                                            <form class="" method="post" action="<?= base_url('reset-password'); ?>">
                                                <div class="mb-3 auth-form-group-custom mb-4">
                                                    <i class="ri-user-2-line auti-custom-input-icon"></i>
                                                    <label for="username" class="fw-semibold">Username</label>
                                                    <input type="text" class="form-control" name="username" id="username" placeholder="Enter username">
                                                </div>
                                                <div class="mb-3 auth-form-group-custom mb-4">
                                                    <i class="ri-key-line  auti-custom-input-icon"></i>
                                                    <label for="security_token" class="fw-semibold">Security Token</label>
                                                    <input type="text" class="form-control" name="security_token" id="security_token" placeholder="Enter Security Token">
                                                </div>
                                                <div class="mb-3 auth-form-group-custom mb-4">
                                                    <i class="ri-lock-2-line auti-custom-input-icon"></i>
                                                    <label for="password" class="fw-semibold">Password</label>
                                                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter New Password">
                                                </div>
                                                <div class="mb-3 auth-form-group-custom mb-4">
                                                    <i class="ri-lock-2-line auti-custom-input-icon"></i>
                                                    <label for="confirm_password" class="fw-semibold">Confirm Password</label>
                                                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm Password">
                                                </div>

                                                <div class="mt-4 text-center">
                                                    <button class="btn btn-primary w-md waves-effect waves-light" name="submit" type="submit">Submit</button>
                                                </div>

                                                <div class="mt-4 text-center">
                                                    <a href="<?= base_url(); ?>" class="text-muted"><i class="mdi mdi-lock me-1"></i> Back to Login?</a>
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