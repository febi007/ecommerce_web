<?php
/**
 * Created by PhpStorm.
 * User: annashrul_yusuf
 * Date: 15/12/2020
 * Time: 16:15
 */
?>
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
<!-- Hero Start -->
<section class="bg-profile d-table w-100 bg-primary" style="background: url('<?=base_url()?>assets/frontend/images/account/bg.png') center center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 text-center">
                <div class="page-next-level">
                    <h4 class="title" style="color: white;"> Gallery Product </h4>
                    <div class="page-next">
                        <nav aria-label="breadcrumb" class="d-inline-block">
                            <ul class="breadcrumb bg-white rounded shadow mb-0">
                                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?= $this->data['site']->nama ?></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Gallery Product</li>
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
