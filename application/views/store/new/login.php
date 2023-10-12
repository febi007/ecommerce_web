
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
    <script src="<?=base_url().'assets/plugins/'?>jquery-validation/jquery.validate.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="<?=base_url()?>assets/frontend/css/bootstrap-side-modals.css">

    <script type="text/javascript" src="<?=base_url().'assets/fo/'?>assets/js/jQuery-autocomplete/jquery.autocomplete.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="<?=base_url().'assets/plugins/'?>jquery-validation/jquery.validate.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<!--    <script type="text/javascript" src="https://technopark.smkn14bdg.sch.id/assets/plugins/jquery-validation/jquery.validate.min.js"></script>-->
<!--    <script type="text/javascript" src="https://technopark.smkn14bdg.sch.id/assets/plugins/jquery-validation/additional-methods.min.js"></script>-->

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

<section class="bg-home d-flex align-items-center">
    <div class="container ">
        <div class="row align-items-center">
            <div class="col-lg-7 col-md-6">
                <div class="mr-lg-5">
                    <img src="<?=base_url()?>assets/frontend/images/user/login.svg" class="img-fluid d-block mx-auto" alt="">
                </div>
            </div>
            <div class="col-lg-5 col-md-6">
                <div class="card login-page bg-white shadow rounded border-10 ">
                    <div class="card-body">
                        <h4 class="card-title text-center">Sign In</h4>
                        <form class="login-form mt-4" id="formLogin">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Your Email <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <i data-feather="user" class="fea icon-sm icons"></i>
                                            <input type="email" class="form-control pl-5" placeholder="Email" name="email" id="email" required>
                                        </div>
                                    </div>
                                </div><!--end col-->

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Password <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <i data-feather="key" class="fea icon-sm icons"></i>
                                            <input type="password" class="form-control pl-5" placeholder="Password" name="password" id="password" required>
                                        </div>
                                    </div>
                                </div><!--end col-->



                                <div class="col-lg-12 mb-0">
                                    <button class="btn btn-primary btn-block">Sign in</button>
                                </div><!--end col-->



                                <div class="col-12 text-center">
                                    <p class="mb-0 mt-3"><small class="text-dark mr-2">Don't have an account ?</small> <a href="<?=base_url().'store/auth?page=register'?>" class="text-dark font-weight-bold">Sign Up</a></p>
                                </div><!--end col-->
                            </div><!--end row-->
                        </form>
                    </div>
                </div><!---->
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
<script src="<?=base_url().'assets/frontend/'?>js/feather.min.js"></script>
<script src="<?=base_url().'assets/frontend/'?>unicons.iconscout.com/release/v3.0.3/script/monochrome/bundle.js"></script>
<!-- Switcher -->
<script src="<?=base_url().'assets/frontend/'?>js/switcher.js"></script>
<!-- Main Js -->
<script src="<?=base_url().'assets/frontend/'?>js/app.js"></script>
<script>

    $(document).ready(function(){
        $("#email").focus();
    })
    var notif = 'cannot be empty!';
    $('#formLogin').validate({

        rules: {
            email: {
                required: true,
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 15
            },

        },
        //For custom messages
        messages: {
            email:{
                required: "Email "+notif,
            },
            password:{
                required: "Password "+notif,
                minlength: "Password must be more than 6 characters!",
                maxlength: "Password cannot be longer than 15 characters"
            },
        },

        submitHandler: function (form) {
            $.ajax({
                url: "<?=base_url().'ajax/login'?>",
                type: "POST",
                dataType: "JSON",
                data: $("#formLogin").serialize(),
                beforeSend: function() {$('body').append('<div class="first-loader"><img src="<?=base_url()?>assets/images/spin.svg"></div>');},
                complete: function() {$('.first-loader').remove();},
                success: function (res) {
                    if (res.status) {
                        window.location.href="<?=base_url()?>";
                    }else{
                        Swal.fire({
                            title: 'Opppss ...',
                            text: "Incorrect email or password",
                            icon: 'warning',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Oke'
                        })
                    }
                }
            });
        }
    });




</script>
</body>
</html>