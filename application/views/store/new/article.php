<div class="position-relative">
    <div class="shape overflow-hidden text-white">
        <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor"></path>
        </svg>
    </div>
</div>
<!-- Hero End -->

<!--Blog Lists Start-->
<section class="section">
    <div class="container">
        <div class="row" id="resultArticle">
        </div><!--end row-->
        <hr>
        <div class="row  d-none d-lg-block">
            <div class="col-md-12">
                <button class="btn btn-primary btn-block" onclick="loadmoreNews()">Lebih Banyak</button>
            </div>
        </div>
    </div><!--end container-->
</section><!--end section -->
<!--Blog Lists End-->

<script>
    var pages=8;
    var lengthArticle=0;

    $(document).ready(function() {
        load_data(pages);
    });
    function loadmoreNews(){
        if(lengthArticle < pages){
            Swal.fire({
                title: "Opppss ...",
                text: "data tidak tersedia",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
        }else{
            pages=pages+8;
            load_data(pages)
            $("html, body").animate({ scrollTop: $("#resultArticle")[0].scrollHeight },1000, "easeOutQuint");
            return false;
        }

    }
    function load_data(page, data={}) {

        $.ajax({
            url:"<?=base_url().'store/article/'.$this->uri->segment(3).'/load_data/';?>"+page,
            type:"POST",
            data:data,
            dataType:"JSON",
            beforeSend: function() {$('body').append('<div class="first-loader"><img src="<?=base_url()?>assets/images/spin.svg"></div>');},
            complete: function() {$('.first-loader').remove();},
            success:function(res)
            {
                lengthArticle = res["count_berita"];
                $('#resultArticle').html(res.result);

//                $('#pagination_link').html(res.pagination_link);



            }
        });
    }
</script>