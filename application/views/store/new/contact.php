
<div id="content-wrapper">
    <!--	<link rel="stylesheet" type="text/css" href="https://indokids.co.id/assets/store/dist/css/common_white.css">-->
    <script src="https://maps.google.com/maps/api/js?key=AIzaSyDqD1Z03FoLnIGJTbpAgRvjcchrR-NiICk&sensor=false" type="text/javascript"></script>

    <style>
        #map-canvas {
            border: 1px solid #DDD;
            width:100%;
            height: 500px;
            float:left;
            margin-bottom: 50px;

            -webkit-box-shadow: #AAA 0px 0px 15px;
        }
        #map-canvas img {
            max-width: none;
        }
        #map-canvas label {
            width: auto; display:inline;
        }
        .active_lokasi {
            moz-box-shadow: inset 0 0 0 5px rgba(0, 0, 0, 0.2);
            -webkit-box-shadow: inset 0 0 0 5px rgba(0, 0, 0, 0.2);
            -o-box-shadow: inset 0 0 0 5px rgba(0, 0, 0, 0.2);
            -ms-box-shadow: inset 0 0 0 5px rgba(0, 0, 0, 0.2);
            box-shadow: inset 0 0 0 5px rgba(0, 0, 0, 0.2);
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
        .swiper-container {
            width: 100%;
            height: 100%;
        }
        .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: #fff;
            height: 100%;
            /* Center slide text vertically */
            display: -webkit-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            -webkit-justify-content: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            -webkit-align-items: center;
            align-items: center;
            background-position: center;
            background-size: cover;
        }
        @media (max-width: 33.9em) {
            .swiper-wrapper{margin-top:0px;}
            .product_img_slide{height: 600px!important;}
            /*// write CSS properties here*/
        }
        /*// Small devices (phones, tablets less than 48em)*/
        @media (max-width: 47.9em) {
            .swiper-slide{margin-top:0px;}
            .product_img_slide{height: 600px!important;}
            /*// write CSS properties here*/
        }

        /*// Medium devices (tablets, small laptops less than 62em)*/
        @media (max-width: 61.9em) {
            .swiper-slide{margin-top:0px;}
            .product_img_slide{height: 600px!important;}
            /*// write CSS properties here*/
        }
        @media (min-width: 74.9em) {
            .swiper-slide{margin-top:00px;}
            /*// write CSS properties here*/
        }
    </style>


</div>


<script>
    $(document).ready(function () {
        change_lokasi('<?=$res_lokasi[0]['id_lokasi']?>', '<?=$res_lokasi[0]['nama']?>', '<?=$res_lokasi[0]['alamat']?>', '<?=$res_lokasi[0]['tlp1']?>', parseFloat('<?=$res_lokasi[0]['lng']?>'), parseFloat('<?=$res_lokasi[0]['lat']?>'));
        var mySwiper = new Swiper ('.swiper-container', {
            direction: 'horizontal',
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            effect: 'coverflow',
            grabCursor: true,
            centeredSlides: true,
            coverflowEffect: {
                rotate: 50,
                stretch: 0,
                depth: 100,
                modifier: 1,
                slideShadows : true,
            },
            lazyLoadingInPrevNext: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 10,
                    centeredSlides: true,
                    coverflowEffect: {
                        rotate: 50,
                        stretch: 0,
                        depth: 100,
                        modifier: 1,
                        slideShadows : true,
                    },
                },
                // when window width is <= 480px
                480: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                    centeredSlides: true,
                    coverflowEffect: {
                        rotate: 50,
                        stretch: 0,
                        depth: 100,
                        modifier: 1,
                        slideShadows : true,
                    },
                },

                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                    centeredSlides: true,
                    coverflowEffect: {
                        rotate: 50,
                        stretch: 0,
                        depth: 100,
                        modifier: 1,
                        slideShadows : true,
                    },
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
            }
        });



    });

    function change_lokasi(id, nama, alamat, tlp, lng, lat) {
        $(".store").removeClass('active_lokasi');
        $("#"+id).addClass('active_lokasi');
        $("#nama_lokasi").text(nama);
        initMap(18, parseFloat(lat), parseFloat(lng), 'map-canvas', 'set');
    }

    function initMap(zoom_=14, lat_=-6.9228583, lng_=107.6058134, id_='map-canvas', param_='edit') {
        var uluru = {lat: lat_, lng: lng_};
        var map = new google.maps.Map(document.getElementById(id_), {
            zoom: zoom_,
            center: uluru
        });

        var geocoder = new google.maps.Geocoder;

        var marker = new google.maps.Marker({
            map: map
        });

        if (param_ == 'set' || $("#param").val()=='edit') {
            marker.setPosition(uluru);
        }

        google.maps.event.addListener(map, 'click', function(e) {
            if (param_ == 'edit') {
                var latLng = e.latLng;
                marker.setPosition(latLng);
                $("#lat").val(latLng.lat());
                $("#lng").val(latLng.lng());
                geocoder.geocode({
                    'latLng': latLng
                }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            $("#alamat").val(results[0].formatted_address);
                        }
                    }
                });
            }
        });
    }
</script>




<!-- Hero Start -->
<section class="bg-profile d-table w-100 bg-primary" style="background: url('<?=base_url()?>assets/frontend/images/account/bg.png') center center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 text-center">
                <div class="page-next-level">
                    <h4 class="title" style="color: white;">Contact Us</h4>
                    <div class="page-next">
                        <nav aria-label="breadcrumb" class="d-inline-block">
                            <ul class="breadcrumb bg-white rounded shadow mb-0">
                                <li class="breadcrumb-item"><a href=""><?= $this->data['site']->nama ?></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Kontak Kami</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>  <!--end col-->
        </div><!--end row-->
    </div> <!--end container-->
</section><!--end section-->
<!-- Hero End -->

<!-- Shape Start -->
<div class="position-relative">
    <div class="shape overflow-hidden text-white">
        <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor"></path>
        </svg>
    </div>
</div>
<!--Shape End-->

<!-- Start Contact -->
<section class="section pb-0">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card border-0 text-center features feature-clean">
                    <div class="icons text-primary text-center mx-auto">
                        <i class="uil uil-phone d-block rounded h3 mb-0"></i>
                    </div>
                    <div class="content mt-3">
                        <h5 class="font-weight-bold">No.Telepon</h5>
                        <a href="tel:<?=$cs['tlp']?>" class="text-primary"><?=$cs['tlp']?></a>
                    </div>
                </div>
            </div><!--end col-->

            <div class="col-md-4 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <div class="card border-0 text-center features feature-clean">
                    <div class="icons text-primary text-center mx-auto">
                        <i class="uil uil-envelope d-block rounded h3 mb-0"></i>
                    </div>
                    <div class="content mt-3">
                        <h5 class="font-weight-bold">Email</h5>
                        <a href="mailto:<?=$cs['email']?>" class="text-primary"><?=$cs['email']?></a>
                    </div>
                </div>
            </div><!--end col-->

            <div class="col-md-4 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <div class="card border-0 text-center features feature-clean">
                    <div class="icons text-primary text-center mx-auto">
                        <i class="uil uil-map-marker d-block rounded h3 mb-0"></i>
                    </div>
                    <div class="content mt-3">
                        <h5 class="font-weight-bold">Lokasi</h5>
                        <p class="text-muted"><?=$res_lokasi[0]['alamat']?></p>
<!--                        <a href="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d39206.002432144705!2d-95.4973981212445!3d29.709510002925988!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8640c16de81f3ca5%3A0xf43e0b60ae539ac9!2sGerald+D.+Hines+Waterwall+Park!5e0!3m2!1sen!2sin!4v1566305861440!5m2!1sen!2sin" class="video-play-icon h6 text-primary">View on Google map</a>-->
                    </div>
                </div>
            </div>
        </div><!--end col-->
    </div><!--end row-->
    </div><!--end container-->


    <div class="container-fluid mt-100 mt-60">
        <div class="row">
            <div class="col-12 p-0">
                <div class="card map border-0">
                    <div class="card-body p-0">
                        <div id="content" class="stores map">
                            <div id="map">
                                <div id="map-canvas"></div>
                                <div class="loading"></div>
                            </div>
                        </div>
<!--                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d39206.002432144705!2d-95.4973981212445!3d29.709510002925988!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8640c16de81f3ca5%3A0xf43e0b60ae539ac9!2sGerald+D.+Hines+Waterwall+Park!5e0!3m2!1sen!2sin!4v1566305861440!5m2!1sen!2sin" style="border:0" allowfullscreen></iframe>-->
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
</section><!--end section-->
<!-- End contact -->