<?php
/**
 * Created by PhpStorm.
 * User: annashrul_yusuf
 * Date: 09/12/2020
 * Time: 5:21
 */
?>

<!-- Start Products -->
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12 mt-5 pt-2 mt-sm-0 pt-sm-0">


                <div class="row" id="resultProductAll">



                    <!-- PAGINATION END -->
                </div><!--end row-->
                <hr>
                <div class="row  d-none d-lg-block">
                    <div class="col-md-12">
                        <button class="btn btn-primary btn-block" onclick="loadmore()">Lebih Banyak</button>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
</section><!--end section-->
<!-- End Products -->

<!-- product list part end-->
<script>
    var page=8;
    $(document).ready(function() {
        load_data_produk(page);

    });

    function loadmore(){
        page=page+4;
        load_data_produk(page);
        $("html, body").animate({ scrollTop: $("#resultProductAll")[0].scrollHeight },1400, "easeOutQuint");
        return false;
    }

    function load_data_produk(page, data={}) {
        $.ajax({
            url:"<?=base_url().'store/get_all_product/load_data/'?>"+page,
            type:"POST",
            data:data,
            dataType:"JSON",
            beforeSend: function() {$('body').append('<div class="first-loader"><img src="<?=base_url()?>assets/images/spin.svg"></div>');},
            complete: function() {$('.first-loader').remove();},
            success:function(res)
            {
                $('#resultProductAll').html(res.res_produk);
            }
        });
    }


</script>
