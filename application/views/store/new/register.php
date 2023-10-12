


<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-MJC5FWG');</script>
    <!-- End Google Tag Manager -->
    <meta name="google-site-verification" content="MGaJ-eY9-qmOrrUlu3DHE_-qVFzq6NSYLYvY2hbmyis" />

    <meta charset="utf-8" />
    <title><?= $this->data['site']->nama ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="<?=base_url().$this->data['site']->icon?>">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Premium Bootstrap 4 Landing Page Template" />
    <meta name="keywords" content="Saas, Software, multi-uses, HTML, Clean, Modern" />
    <meta name="author" content="Shreethemes" />
    <meta name="email" content="shreethemes@gmail.com" />
    <meta name="website" content="http://www.shreethemes.in/" />
    <meta name="Version" content="v2.6" />
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css?family=Stylish" rel="stylesheet">
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <!-- favicon -->
    <!-- Bootstrap -->
    <link href="<?=base_url().'assets/frontend/'?>css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons -->
    <link href="<?=base_url().'assets/frontend/'?>css/materialdesignicons.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?=base_url().'assets/frontend/'?>unicons.iconscout.com/release/v3.0.3/css/line.css">
    <!-- Slider -->
    <!-- Main Css -->
    <link href="<?=base_url().'assets/frontend/'?>css/style.min.css" rel="stylesheet" type="text/css" id="theme-opt" />
    <link href="<?=base_url().'assets/frontend/'?>css/colors/default.css" rel="stylesheet" id="color-opt">
    <script src="<?=base_url().'assets/frontend/'?>js/jquery-3.5.1.min.js"></script>
    <script src="<?=base_url().'assets/frontend/'?>js/owl.carousel.min.js"></script>
    <script src="<?=base_url().'assets/frontend/'?>js/owl.init.js"></script>
    <!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>-->
    <script type="text/javascript" src="<?=base_url().'assets/plugins/'?>jquery-validation/jquery.validate.min.js"></script>
    <link rel="stylesheet" href="<?=base_url()?>assets/frontend/css/bootstrap-side-modals.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <style>
        *{font-family: 'Stylish', sans-serif!important;}
        html{font-family: 'Stylish', sans-serif!important;}
        body{font-family: 'Stylish', sans-serif!important;}
        .error{
            color:red!important;
        }
        .first-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1050;
            background: rgba(168, 168, 168, .5)
        }
        .first-loader img {
            position: absolute;
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            width: 60px;
            height: 60px
        }


    </style>
</head>


<body>

<div class="back-to-home rounded">
    <a href="<?=base_url()?>" class="btn btn-icon btn-soft-primary"><i data-feather="home" class="icons"></i></a>
</div>

<!-- Hero Start -->
<section class="bg-auth-home d-table w-100">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 col-md-6">
                <div class="mr-lg-5">
                    <img src="<?=base_url()?>assets/frontend/images/user/signup.svg" class="img-fluid d-block mx-auto" alt="">
                </div>
            </div>
            <div class="col-lg-5 col-md-6">
                <div class="card shadow rounded border-0">
                    <div class="card-body">
                        <h4 class="card-title text-center">Signup</h4>
                        <form class="login-form mt-4" id="formRegister">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php $field='nama';?>
                                        <label>Name <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <i data-feather="user" class="fea icon-sm icons"></i>
                                            <input type="text" class="form-control pl-5" placeholder="First Name" name="<?=$field?>" id="<?=$field?>">
                                        </div>
                                    </div>
                                </div><!--end col-->

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Gender <span class="text-danger">*</span></label>
                                        <?php $field = 'jk'; ?>
                                        <select name="<?=$field?>" id="<?=$field?>" class="form-control">
                                            <option value="">Gender</option>
                                            <option value="L">Male</option>
                                            <option value="P">Female</option>
                                        </select>

                                    </div>
                                </div><!--end col-->

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?php $field='email'; ?>
                                        <label>Email <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <i data-feather="mail" class="fea icon-sm icons"></i>
                                            <input type="email" class="form-control pl-5" placeholder="Email" name="<?=$field?>" id="<?=$field?>">
                                        </div>
                                    </div>
                                </div><!--end col-->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?php $field = 'telp'; ?>
                                        <label>Phone <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <i data-feather="phone" class="fea icon-sm icons"></i>
                                            <input type="number" class="form-control pl-5" placeholder="Phone" name="<?=$field?>" id="<?=$field?>">
                                        </div>
                                    </div>
                                </div><!--end col-->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?php $field = 'tgl_lahir'; ?>
                                        <label>Birth Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="<?=$field?>" id="<?=$field?>" data-inputmask="'alias': 'dd-mm-yyyy'" data-mask>
                                    </div>
                                </div><!--end col-->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?=$field='password';?>
                                        <label>Password <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <i data-feather="key" class="fea icon-sm icons"></i>
                                            <input type="password" class="form-control pl-5" placeholder="Password" name="<?=$field?>" id="<?=$field?>">
                                        </div>
                                    </div>
                                </div><!--end col-->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?php $field='password_conf';?>
                                        <label>Confirmation Password <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <i data-feather="key" class="fea icon-sm icons"></i>
                                            <input type="password" class="form-control pl-5" placeholder="Password" name="<?=$field?>" id="<?=$field?>">
                                        </div>
                                    </div>
                                </div><!--end col-->

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1">
                                            <label class="custom-control-label" for="customCheck1">I Accept <a href="#" class="text-primary">Terms And Condition</a></label>
                                        </div>
                                    </div>
                                </div><!--end col-->

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                                </div><!--end col-->

                            </div><!--end row-->
                        </form>
                        <div class="row">
                            <div class="mx-auto">
                                <p class="mb-0 mt-3"><small class="text-dark mr-2">Already have an account ?</small> <a href="<?=base_url().'store/auth?page=login'?>" class="text-dark font-weight-bold">Sign in</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!--end col-->
        </div><!--end row-->
    </div> <!--end container-->
</section><!--end section-->
<!-- Hero End -->


<!-- javascript -->
<script src="<?=base_url().'assets/frontend/'?>js/bootstrap.bundle.min.js"></script>
<script src="<?=base_url().'assets/frontend/'?>js/jquery.easing.min.js"></script>
<script src="<?=base_url().'assets/frontend/'?>js/scrollspy.min.js"></script>
<!--FLEX SLIDER-->
<script src="<?=base_url().'assets/frontend/'?>js/jquery.flexslider-min.js"></script>
<script src="<?=base_url().'assets/frontend/'?>js/flexslider.init.js"></script>
<!-- Slider -->
<script src="<?=base_url().'assets/frontend/'?>js/slick.min.js"></script>
<script src="<?=base_url().'assets/frontend/'?>js/slick.init.js"></script>
<!-- Icons -->
<script src="<?=base_url().'assets/frontend/'?>js/feather.min.js"></script>
<script src="<?=base_url().'assets/frontend/'?>unicons.iconscout.com/release/v3.0.3/script/monochrome/bundle.js"></script>
<!-- Switcher -->
<script src="<?=base_url().'assets/frontend/'?>js/switcher.js"></script>
<!-- Main Js -->
<script src="<?=base_url().'assets/frontend/'?>js/app.js"></script>

<script>
    $(document).ready(function(){
        $("#nama").focus();
    })
    var notif = 'cannot be empty!';
    $('#formRegister').validate({
        rules: {
            nama: {
                required: true
            },
            jk: {
                required: true
            },
            tgl_lahir: {
                required: true
            },
            email: {
                required: true,
                email: true,
                remote: {
                    url: "<?=base_url().'ajax/register/cek_email'?>",
                    type: "post"
                }
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 15
            },
            password_conf: {
                required: true,
                equalTo: "#password"
            },
            telp: {
                required: true,
                remote: {
                    url: "<?=base_url().'ajax/register/cek_telepon'?>",
                    type: "post"
                }
            }
        },
        //For custom messages
        messages: {
            nama: {
                required: "Name "+notif
            },
            jk: {
                required: "Gender "+notif
            },
            tgl_lahir: {
                required: "Date of birth "+notif
            },
            telp: {
                required: "Phone Number "+notif,
                remote: "Phone number already exist!"
            },
            email:{
                required: "Email "+notif,
                email: "Email Is Incorrect",
                remote: "Email already exist!"
            },
            password:{
                required: "Password "+notif,
                minlength: "Password must be more than 6 characters!",
                maxlength: "Password cannot be longer than 15 characters"
            },
            password_conf:{
                required: "Confirmation Password "+notif,
                equalTo: "Password does not match!"
            },
        },

        submitHandler: function (form) {
            $.ajax({
                url: "<?=base_url().'ajax/register/simpan'?>",
                type: "POST",
                dataType: "JSON",
                data: $("#formRegister").serialize(),
                beforeSend: function() {$('body').append('<div class="first-loader"><img src="<?=base_url()?>assets/images/spin.svg"></div>');},
                complete: function() {$('.first-loader').remove();},
                success: function (res) {
                    console.log(res);
                    if (res.status) {
                        window.location.href="<?=base_url()?>";
                    }else{
                        alert(res.status);
                    }
                }
            });
        }
    });
</script>

</body>

<!-- Mirrored from shreethemes.in/landrick/layouts/auth-signup.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 01 Dec 2020 14:46:50 GMT -->
</html>