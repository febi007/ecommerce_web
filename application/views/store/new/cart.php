<?php
/**
 * Created by PhpStorm.
 * User: annashrul_yusuf
 * Date: 10/12/2020
 * Time: 5:19
 */
?>

<!-- Hero Start -->
<section class="bg-half bg-light d-table w-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 text-center">
                <div class="page-next-level">
                    <h4 class="title"> Keranjang Belanja </h4>
                    <div class="page-next">
                        <nav aria-label="breadcrumb" class="d-inline-block">
                            <ul class="breadcrumb bg-white rounded shadow mb-0">
                                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?= $this->data['site']->nama ?></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Keranjang Kamu</li>
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

<!-- Start -->
<section class="section">
    <div class="container">
        <div class="row" id="resCart">
        </div><!--end row-->
        <?php $mobile=$this->agent->is_mobile(); if(!$mobile): ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12 mt-4 pt-2">
                <a href="javascript:void(0)" onclick="bayar()" class="btn btn-soft-primary btn-block text-left">Bayar <small class="totCart" style="float: right;font-weight: bold;"></small></a>
            </div>

        </div><!--end row-->
        <?php endif; ?>

    </div><!--end container-->
</section><!--end section-->
<!-- End -->
<script>
    load_cart();

    function load_cart(){
        $.ajax({
            url : "<?=base_url().'ajax/getCart'?>",
            type : "POST",
            dataType : "JSON",
            success:function(res){
                $("#resCart").html(res.result);
//                $("#res_mobile").html(res.res_mobile);
                $("#tot_qty").val(res.qty);
                $(".totCart").html(res.total);
                if(parseInt(res.count)>0) $("#btn_checkout").css('display','block')
                else  $("#btn_checkout").css('display','none')
                countCart();
            }
        })
    }
    function update_qty(order, produk) {
        if(parseInt($(".qty-"+produk).val()) < 1){
            $(".qty-"+produk).val('1')
        }
        $.ajax({
            url: "<?=base_url().'api/edit_cart'?>",
            type:"POST",
            data: {jumlah:parseInt($(".qty-"+produk).val()), kd_trans:order, det_produk:produk},
            dataType: "JSON",
            success: function (res) {
                if (res.status) {
                    load_cart();
                }
            }
        })

    }
    var qty=0;
    var qty1=1;
    function add_qty(order, produk, qtys) {
        qty++;
        $.ajax({
            url: "<?=base_url().'api/edit_cart'?>",
            type:"POST",
            data: {jumlah:parseInt(qtys)+qty, kd_trans:order, det_produk:produk},
            dataType: "JSON",
            success: function (res) {
                if (res.status) {
                    load_cart();
                    qty = 0;
                }
            }
        })
    }

    function min_qty(order, produk, qtys) {
        var qu=$(".qty-"+produk);
        var b = parseInt(qu.val());
        if(b>1){
            b--;
            $.ajax({
                url: "<?=base_url().'api/edit_cart'?>",
                type:"POST",
                data: {jumlah:b, kd_trans:order, det_produk:produk},
                dataType: "JSON",
                success: function (res) {
                    if (res.status) {
                        load_cart();
                        $(".qty-"+produk).val(b);
                    }
                }
            })
        }
    }
    function hapus_item(trans, id) {
        Swal.fire({
            title: "Information !",
            text: "Are you sure ???",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Oke'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "<?=base_url().'api/delete_item_cart'?>",
                    type: "POST",
                    data: {orders: trans, produk: id},
                    dataType: "JSON",
                    success: function (res) {
                        if (res.status) {
                            countCart();
                            load_cart();
                            location.reload();
                        }
                    }
                });
            }
        })
    }
    function update_qty_m(order, produk) {
        $.ajax({
            url: "<?=base_url().'api/edit_cart'?>",
            type:"POST",
            data: {jumlah:$(".qty_m-"+produk).val(), kd_trans:order, det_produk:produk},
            dataType: "JSON",
            success: function (res) {
                if (res.status) {
                    load_cart();
                }
            }
        })
    }

</script>