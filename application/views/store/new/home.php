

<!-- Hero Start -->
<section class="main-slider">
    <ul class="slides">
        <?php if($slider!=null){ foreach($slider as $row):?>
        <li class="bg-slider slider-rtl-2 d-flex align-items-center bg-slider" style="width:100%!important;background:url('<?=base_url().$row['gambar']?>') center center;">
            <div class="bg-overlay" style="opacity: 0.65;"></div>
            <div class="container">
                <div class="row align-items-center mt-5">
                    <div class="col-lg-7 col-md-7">
                        <div class="title-heading mt-4">
                            <h5 class="display-5 title-white font-weight-bold mb-3" style="color:white!important;"><?=$row['judul']?></h5>
<!--                            <p class="para-desc text-muted para-dark" style="color:white!important;">Launch your campaign and benefit from our expertise on designing and managing conversion centered bootstrap4 html page.</p>-->
                            <div class="mt-4">
                                <a href="<?=$row['link']?>" class="btn btn-primary m-1">Detail</a>
                            </div>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end container-->
        </li>
        <?php endforeach; } ?>

    </ul>
</section><!--end section-->


<section class="section">
    <!--Shape End-->
    <!-- Start Most Viewed Products -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h5 class="mb-0">Produk Terlaris</h5>
            </div><!--end col-->
        </div><!--end row-->

        <div class="row">
            <?php
            $i=0;  $res_produk = json_decode($bestSeller, true);
                foreach ($res_produk['res_produk'] as $row) { $no++; ?>
                    <div class="col-lg-3 col-md-6 col-6 mt-4 pt-2">
                        <div onclick="return window.location='<?=base_url().'store/product?product_id='.$row['id_produk']?>'" data-aos="<?=$i%2==0?'zoom-in':'zoom-out'?>" data-aos-duration="1000" class="card shop-list border-0 position-relative" style="cursor:pointer;background:#f5f6fa!important;box-shadow: 3px 3px 0px 0 #2f55d4!important;">
                            <ul class="label list-unstyled mb-0">
                                <li><a href="javascript:void(0)" class="badge badge-pill badge-success">Best Seller</a></li>
                            </ul>
                            <div class="shop-image position-relative overflow-hidden rounded shadow">
                                <a href="<?=base_url().'store/product?product_id='.$row['id_produk']?>"><img src="<?=$row['gambar_produk'][0].'?height=400&amp;width=476&amp;trim.threshold=80'?>" class="img-fluid" alt=""></a>
                                <a href="<?=base_url().'store/product?product_id='.$row['id_produk']?>" class="overlay-work">
                                    <img src="<?=$row['gambar_produk'][0].'?height=400&amp;width=476&amp;trim.threshold=80'?>" class="img-fluid" alt="">
                                </a>

                            </div>
                            <div class="card-body content pt-4 p-2">
                                <a href="<?=base_url().'store/product?product_id='.$row['id_produk']?>" class="text-dark product-name h6"><?=$row['nama_produk']?></a>
                                <div class="d-flex justify-content-between mt-1">
                                    <h6 class="text-muted small font-italic mb-0 mt-1"><?=number_format($row['hrg_jual'])?></h6>

                                </div>
                            </div>
                        </div>
                    </div><!--end col-->
                <?php }
            ?>


        </div><!--end row-->
    </div><!--end container-->
    <!-- End Most Viewed Product -->

    <!-- Shape Start -->
    <div class="position-relative" style="margin-top: 20px">
        <div class="shape overflow-hidden text-light">
            <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor"></path>
            </svg>
        </div>
    </div>
</section>
<style>
    .grid-container {
        display: grid;
        grid-template-columns: auto auto auto auto;
        grid-gap: 10px;
        /*background-color: #2196F3;*/
    }

    .grid-container > div {
        text-align: center;
        /*font-size: 30px;*/
    }

    .item1 {
        grid-area: 2 / 1 / span 2 / span 2;
    }

</style>

<section class="section bg-light">
    <!-- Start Categories -->
    <div class="container-fluid ">

        <div class="grid-container">
            <?php $no=1; foreach ($model as $row):?>
                <div class="item<?=$no++?>"  style="cursor: pointer!important;" onclick="return window.location.href='<?=base_url()."store/list_produk/model/".$row["id_model"]?>'">
                    <div class="popular-tour rounded-md position-relative overflow-hidden">
                        <img src="<?=base_url().$row['gambar']?>" style="width: 100%;height: 100%!important;">
                        <div class="overlay-work bg-dark"></div>
                        <div class="content" style="<?=$this->agent->is_mobile()?'display:none':'display:block'?>">
                            <a href="javascript:void(0)" class="title text-white h4 title-dark" style="text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black ;"><?=$row['nama']?></a>
                        </div>
                    </div><!--end tour post-->
                </div>
            <?php endforeach;?>
        </div>
    </div><!--end container-->
    <!-- Start Categories -->
</section>

<section class="section">

    <!-- Start Popular -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h5 class="mb-0">Produk Terbaru</h5>
            </div><!--end col-->
        </div><!--end row-->

        <div class="row">
            <?php $i=0; foreach ($latestProduct as $row):
                $i++;
                $get_promo = $this->m_crud->get_join_data("promo pr", "pr.diskon", "det_promo dp", "dp.slug_promo=pr.slug_promo", "dp.produk='".$row['id_produk']."' AND '".date('Y-m-d H:i:s')."' BETWEEN pr.tgl_awal AND pr.tgl_akhir");
                if ($get_promo!=null) {
                    $promo = 1;
                    $hrgcoret = $row['hrg_jual'];
                    $diskon = json_decode($get_promo['diskon'], true);
                    $harga_promo = $this->m_website->double_diskon($row['hrg_jual'], $diskon);
                    $data_diskon = '';
                    for ($i=0;$i<count($diskon);$i++) {
                        $data_diskon .= ($i>0)?' + ':'Diskon ';
                        $data_diskon .= $diskon[$i].'%';
                    }
                    $diskon_persen = $diskon;
                    $diskon = $data_diskon;
                    $hrg_jual = $harga_promo;
                } else {
                    $diskon_persen = array();
                    $promo = 0;
                    $hrg_jual = $row['hrg_jual'];
                }
                $core='';

                if($hrgcoret!=''){
                    $core=number_format($hrgcoret);
                }
                else{
                    $core='';
                }
            ?>
                <div class="col-lg-3 col-md-6 col-6 mt-4 pt-2">
                    <div data-aos="<?=$i%2==0?'zoom-in':'zoom-out'?>" data-aos-duration="1000" class="card shop-list border-0 position-relative" style="background:#f5f6fa!important;box-shadow: 3px 3px 0px 0 #2f55d4!important;">
                        <ul class="label list-unstyled mb-0">
                            <li><a href="javascript:void(0)" class="badge badge-pill badge-primary"><?=$diskon?></a></li>
                        </ul>
                        <div class="shop-image position-relative overflow-hidden rounded shadow">
                            <a href="<?=base_url().'store/product?product_id='.$row['id_produk']?>"><img src="<?=base_url().$row['gambar']?>" class="img-fluid" alt=""></a>
                            <a href="<?=base_url().'store/product?product_id='.$row['id_produk']?>" class="overlay-work">
                                <img src="<?=base_url().$row['gambar']?>" class="img-fluid" alt="">
                            </a>

                        </div>
                        <div class="card-body content pt-4 p-2">
                            <a href="<?=base_url().'store/product?product_id='.$row['id_produk']?>" class="text-dark product-name h6"><?=$row['nama']?></a>
                            <div class="d-flex justify-content-between mt-1">
                                <h6 class="text-muted small font-italic mb-0 mt-1"><?=number_format($hrg_jual)?> <del class="text-danger ml-2"><?=$core?></del> </h6>

                            </div>
                        </div>
                    </div>
                </div><!--end col-->
            <?php endforeach;?>
        </div><!--end row-->
    </div><!--end container-->
    <!-- End Popular -->


</section><!--end section-->
<!-- End -->

<!-- Shape Start -->
<div class="position-relative">
    <div class="shape overflow-hidden text-light">
        <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor"></path>
        </svg>
    </div>
</div>
<!--Shape End-->

<!-- News Start -->
<section class="section bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="section-title mb-4 pb-2">
                    <h4 class="title mb-4">Berita</h4>
                </div>
            </div><!--end col-->
        </div><!--end row-->

        <div class="row">

            <?php $i=0; foreach($news as $row):$i++;?>
                <div class="col-lg-4 col-md-6 mb-4 pb-2 mt-4 pt-2">
                    <div style="cursor: pointer;" onclick="return window.location.href='<?=base_url()."store/article?detail=".$row['slug_berita']?>'" class="card blog rounded border-0 shadow overflow-hidden"  data-aos="flip-left"
                         data-aos-easing="ease-out-cubic"
                         data-aos-duration="2000">
                        <div class="position-relative">
                            <img src="<?=$row['gambar']?>" class="card-img-top" alt="...">
                            <div class="overlay rounded-top bg-dark"></div>
                        </div>
                        <div class="card-body content">
                            <h5><a href="<?=base_url().'store/article?detail='.$row['slug_berita']?>" class="card-title title text-dark"><?=$row["judul"]?></a></h5>
                            <small class="text-dark user d-block"><?=strlen($row['ringkasan'])>100?substr(strip_tags($row['ringkasan']),0,100).'...':$row['ringkasan']?></small>
                            <div class="post-meta d-flex justify-content-between mt-3">
                                <ul class="list-unstyled mb-0">
                                    <li class="list-inline-item mr-2 mb-0"><a href="javascript:void(0)" class="text-muted like"><i class="mdi mdi-account"></i><?=$row["nama"]?></a></li>
                                    <li class="list-inline-item"><a href="javascript:void(0)" class="text-muted comments"><i class="mdi mdi-clock"></i><?=time_elapsed_string($row["tgl_berita"])?></a></li>
                                </ul>
                            </div>
                            <a style="color: #2f55d4;font-weight: bold" href="<?=base_url().'store/article?detail='.$row['slug_berita']?>" class="readmore">Selengkapnya <i class="mdi mdi-chevron-right"></i></a>

                        </div>
                        <div class="author">
                            <small class="text-light user d-block"><?=$row['ringkasan']?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
        </div><!--end row-->
    </div><!--end container-->

</section><!--end section-->
<!-- End News -->

<!-- Shape Start -->
<div class="position-relative">
    <div class="shape overflow-hidden text-footer">
        <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor"></path>
        </svg>
    </div>
</div>
<!--Shape End-->

