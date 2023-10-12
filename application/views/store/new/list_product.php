<?php
/**
 * Created by PhpStorm.
 * User: annashrul_yusuf
 * Date: 09/12/2020
 * Time: 5:21
 */
?>
<input type="hidden" name="param" id="param" value="<?=$param?>">
<?php
$table=$this->uri->segment(3);
if($table=='groups'){
    $status = "AND status='1'";
}
if($table=='top_item'){
    $id = "id_item";
}else{
    $id = "id_$table";
}
$menu_group = $this->m_crud->get_data($table, "*", "$id='" . $param . "' $status");
?>
<!-- Hero Start -->
<section class="bg-profile d-table w-100 bg-primary" style="background: url('<?=base_url()?>assets/frontend/images/account/bg.png') center center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 text-center">
                <div class="page-next-level">
                    <h4 class="title" style="color:white;"> <?=$menu_group['nama']?> </h4>
                    <div class="page-next">
                        <nav aria-label="breadcrumb" class="d-inline-block">
                            <ul class="breadcrumb bg-white rounded shadow mb-0">
                                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?= $this->data['site']->nama ?></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Produk</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div> <!--end container-->
</section><!--end section-->
<div class="position-relative">
    <div class="shape overflow-hidden text-white">
        <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor"></path>
        </svg>
    </div>
</div>
<!-- Hero End -->

<!-- Start Products -->
<section class="section">
    <div class="container" style="margin-top: -50px">
        <div class="row">
            <div class="col-lg-9 col-md-8 col-12 mt-sm-0 pt-sm-0">
                <div class="row align-items-center">
                    <div class="col-lg-8 col-md-7">
                        <div class="section-title">
                        </div>
                    </div><!--end col-->

                    <div class="col-lg-4 col-md-5 mt-4 mt-sm-0 pt-2 pt-sm-0">
                        <div class="form custom-form">
                            <div class="form-group mb-0">
                                <select class="filter form-control custom-select" name="select" id="filter_harga" onchange="filter('harga',$(this).val())">
                                    <option value="">Pilih Harga</option>
                                    <option value="0-100">Rp 0 - 100,000</option>
                                    <option value="100-500">Rp 100000 - 500,000</option>
                                    <option value="500-">Rp + 500,000</option>
                                </select>

                            </div>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->

                <div class="row" id="resultProduct">

                    <div class="col-lg-4 col-md-6 col-12 mt-4 pt-2">
                        <div class="card shop-list border-0 position-relative">
                            <ul class="label list-unstyled mb-0">
                                <li><a href="javascript:void(0)" class="badge badge-pill badge-success">Featured</a></li>
                            </ul>
                            <div class="shop-image position-relative overflow-hidden rounded shadow">
                                <a href="shop-product-detail.html"><img src="images/shop/product/s1.jpg" class="img-fluid" alt=""></a>
                                <a href="shop-product-detail.html" class="overlay-work">
                                    <img src="images/shop/product/s-1.jpg" class="img-fluid" alt="">
                                </a>
                                <ul class="list-unstyled shop-icons">
                                    <li><a href="javascript:void(0)" class="btn btn-icon btn-pills btn-soft-danger"><i data-feather="heart" class="icons"></i></a></li>
                                    <li class="mt-2"><a href="javascript:void(0)" data-toggle="modal" data-target="#productview" class="btn btn-icon btn-pills btn-soft-primary"><i data-feather="eye" class="icons"></i></a></li>
                                    <li class="mt-2"><a href="shop-cart.html" class="btn btn-icon btn-pills btn-soft-warning"><i data-feather="shopping-cart" class="icons"></i></a></li>
                                </ul>
                            </div>
                            <div class="card-body content pt-4 p-2">
                                <a href="shop-product-detail.html" class="text-dark product-name h6">Branded T-Shirt</a>
                                <div class="d-flex justify-content-between mt-1">
                                    <h6 class="text-muted small font-italic mb-0 mt-1">$16.00 <del class="text-danger ml-2">$21.00</del> </h6>
                                    <ul class="list-unstyled text-warning mb-0">
                                        <li class="list-inline-item"><i class="mdi mdi-star"></i></li>
                                        <li class="list-inline-item"><i class="mdi mdi-star"></i></li>
                                        <li class="list-inline-item"><i class="mdi mdi-star"></i></li>
                                        <li class="list-inline-item"><i class="mdi mdi-star"></i></li>
                                        <li class="list-inline-item"><i class="mdi mdi-star"></i></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div><!--end col-->

                    <!-- PAGINATION END -->
                </div><!--end row-->
                <hr>
                <div class="row  d-none d-lg-block">
                    <div class="col-md-12">
                        <button class="btn btn-primary btn-block" onclick="loadmoreProduct()">Lebih Banyak</button>
                    </div>
                </div>
            </div><!--end col-->
            <div class="col-lg-3 col-md-4 col-12 border-0 shadow rounded">
                <div class="card border-0 sidebar sticky-bar">
                    <div class="card-body p-0">
                        <!-- Categories -->
                        <div class="widget mt-4 pt-2">
                            <h5 class="widget-title">Kategori</h5>
                            <ul class="list-unstyled mt-4 mb-0 blog-categories" id="filter_category">
                            </ul>
                        </div>
                        <!-- Categories -->
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
</section><!--end section-->
<!-- End Products -->

<input type="hidden" id="patokan">
<!-- product list part start-->



<!-- product list part end-->
<script>

    var page=8;
    var lengthProduct=0;

    function filter(param,id) {
        $("#patokan").val(param);
        load_data_produk(page, {search:param,param: id});

    }

    $(document).ready(function() {
        if ('<?=$this->uri->segment(3)?>' === 'groups') {
            load_data_produk(page, {search: '', group: $("#param").val()});
        } else {
            load_data_produk(page, {search: ''});
        }
        load_kelompok();
        var pixelFromTop = 500;
        $('html, body').animate({ scrollTop: pixelFromTop  }, 1);
    });
    function loadmoreProduct(){
        console.log(lengthProduct);
        if(lengthProduct < page){
            Swal.fire({
                title: "Opppss ...",
                text: "barang tidak tersedia",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
        }else{
            page = page+8;
            var patokan=$("#patokan").val();
            if(patokan==='kategori'){
                load_data_produk(page, {search: patokan,param:$("#filter_kategori").val()});
            }else if(patokan==='merk'){
                load_data_produk(page, {search: patokan,param:$("#filter_merk").val()});
            }else if(patokan==='harga'){
                load_data_produk(page, {search: patokan,param:$("#filter_harga").val()});
            }else{
                load_data_produk(page, {search: ''});
            }
        }
    }

    function load_data_produk(page, data={}) {
        $.ajax({
            url:"<?=base_url().'store/list_produk/'.$this->uri->segment(3).'/';?>"+$("#param").val()+'/'+page,
            type:"POST",
            data:data,
            dataType:"JSON",
            beforeSend: function() {$('body').append('<div class="first-loader"><img src="<?=base_url()?>assets/images/spin.svg"></div>');},
            complete: function() {$('.first-loader').remove();},
            success:function(res)
            {
                lengthProduct = res['count_produk'];
                $('#resultProduct').html(res.res_produk);
            }
        });
    }

    function load_kelompok(){
        $.ajax({
            url:"<?=base_url().'store/list_produk/'.$this->uri->segment(3).'/';?>"+$("#param").val(),
            type:"POST",
            data:{search:''},
            dataType:"JSON",
            beforeSend: function() {$('body').append('<div class="first-loader"><img src="<?=base_url()?>assets/images/spin.svg"></div>');},
            complete: function() {$('.first-loader').remove();},
            success:function(res) {
                var list='';var merk='';
                for(var i=0;i<res.kelompok.length;i++){
                    list+=`<li onclick="filter(\'kategori\','${res.kelompok[i].id_kelompok}')"><a href="#!">${res.kelompok[i].nama}</a></li>`;
                }
                $("#filter_category").html(list);
            }
        });
    }
</script>
