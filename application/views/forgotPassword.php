<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
<title><?= SITENAME?> | <?= $title;?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <link rel="icon" href="<?= base_url();?>assets/backend/images/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:500,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/backend/bower_components/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url();?>assets/backend/pages/waves/css/waves.min.css" type="text/css" media="all">
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/backend/icon/feather/css/feather.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/backend/icon/themify-icons/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/backend/icon/icofont/css/icofont.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/backend/icon/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/backend/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/backend/css/pages.css">
</head>
<body themebg-pattern="theme1"  class="login-bg" style="background-image:url('<?= base_url(); ?>assets/backend/images/login-bg-2.jpg')">
    <div class="theme-loader">
        <div class="loader-track">
            <div class="preloader-wrapper">
                <div class="spinner-layer spinner-blue">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
                <div class="spinner-layer spinner-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
                <div class="spinner-layer spinner-yellow">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
                <div class="spinner-layer spinner-green">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="login-block">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <form action="<?= base_url('forgot-password');?>" method="post" class="md-float-material form-material">
                        <div class="auth-box card">
                            <div class="card-block">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="logo-circle">
                                            <img src="<?= base_url(); ?>assets/backend/images/logo.png" alt="logo.png">
                                        </div>
                                    </div>
                                </div>
                                <div class="row m-b-20">
                                    <div class="col-md-12">
                                        <h3 class="text-left">Recover your password</h3>
                                        <?php echo $this->messages->getMessageFront(); ?>
                                    </div>
                                </div>
                                <div class="form-group form-primary">
                                    <input type="text" name="username" class="form-control">
                                    <span class="form-bar"></span>
                                    <label class="float-label">Your Email Address</label>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">Reset Password</button>
                                    </div>
                                </div>
                                <p class="f-w-600 text-right"><i class="ti-arrow-left"></i> Back to <a href="<?= base_url();?>" style="color:#4099ff">Login.</a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript" src="<?= base_url();?>assets/backend/bower_components/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?= base_url();?>assets/backend/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?= base_url();?>assets/backend/bower_components/popper_js/js/popper.min.js"></script>
    <script type="text/javascript" src="<?= base_url();?>assets/backend/bower_components/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= base_url();?>assets/backend/pages/waves/js/waves.min.js"></script>
    <script type="text/javascript" src="<?= base_url();?>assets/backend/bower_components/jquery-slimscroll/js/jquery.slimscroll.js"></script>
    <script type="text/javascript" src="<?= base_url();?>assets/backend/bower_components/modernizr/js/modernizr.js"></script>
    <script type="text/javascript" src="<?= base_url();?>assets/backend/bower_components/modernizr/js/css-scrollbars.js"></script>
    <script type="text/javascript" src="<?= base_url();?>assets/backend/js/common-pages.js"></script>
</body>
</html>