<?php
/**
 * Created by PhpStorm.
 * User: annashrul_yusuf
 * Date: 09/12/2020
 * Time: 6:38
 */
?>

<style>
    /*img {*/
        /*max-width: 100%;*/
        /*height: auto !important;*/
    /*}*/
</style>
<!-- Hero Start -->

<section class="bg-profile d-table w-100 bg-primary" style="background: url('<?=base_url()?>assets/frontend/images/account/bg.png') center center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 text-center">
                <div class="page-next-level">
                    <h2 style="color: white"><?=$detail['judul']?></h2>
                    <ul class="list-unstyled mt-4">
                        <li class="list-inline-item h6 date text-muted"><i class="mdi mdi-calendar-check"></i> <?=date('Y-m-d',strtotime($detail['tgl_berita']))?></li>
                    </ul>
                    <div class="page-next">
                        <nav aria-label="breadcrumb" class="d-inline-block">
                            <ul class="breadcrumb bg-white rounded shadow mb-0">
                                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?= $this->data['site']->nama ?></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Detail Berita</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div><!--end col-->
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

<!-- Blog STart -->
<section class="section">
    <div class="container">
        <div class="row">
            <!-- BLog Start -->
            <div class="col-lg-8 col-md-6">
                <div class="card blog blog-detail border-0 shadow rounded">
                    <img src="<?=base_url().$detail['gambar']?>" class="img-fluid rounded-top" alt="">
                    <div class="card-body content">
                        <h6><i class="mdi mdi-tag text-primary mr-1"></i><a href="javscript:void(0)" class="text-primary"><?=$detail['nama_kategori']?></a></h6>
                        <blockquote class="blockquote mt-3 p-3">
                            <p class="text-muted mb-0 font-italic"><?=$detail['ringkasan']?></p>
                        </blockquote>
                        <p class="text-muted mt-3 con"><?=$detail['isi']?></p>

                    </div>
                </div>
            </div>
            <!-- BLog End -->

            <!-- START SIDEBAR -->
            <div class="col-lg-4 col-md-6 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <div class="card border-0 sidebar sticky-bar rounded shadow">
                    <div class="card-body">
                        <!-- Categories -->
                        <div class="widget mb-4 pb-2">
                            <h5 class="widget-title">Kategori</h5>
                            <ul class="list-unstyled mt-4 mb-0 blog-categories">
                                <?php foreach ($category as $row):?>
                                <li><a href="<?=base_url().'store/article/'.$row['slug_kategori_berita']?>"><?=$row['nama']?></a> <span class="float-right"><?=$row['kategori']?></span></li>
                                <?php endforeach;?>
                            </ul>
                        </div>
                        <!-- Categories -->

                    </div>
                </div>
            </div><!--end col-->
            <!-- END SIDEBAR -->
        </div><!--end row-->
    </div><!--end container-->
</section><!--end section-->
<!-- Blog End -->

