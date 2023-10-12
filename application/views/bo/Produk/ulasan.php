<style>
    /****** Style Star Rating Widget *****/

    .rating {
        border: none;
        float: left;
    }

    .rating > input { display: none; }
    .rating > label:before {
        margin: 5px;
        font-size: 1.25em;
        font-family: FontAwesome;
        display: inline-block;
        content: "\f005";
    }

    .rating > .half:before {
        content: "\f089";
        position: absolute;
    }

    .rating > label {
        color: #ddd;
        float: right;
    }

    /***** CSS Magic to Highlight Stars on Hover *****/

    .rating > input:checked ~ label, /* show gold star when clicked */
    .rating:not(:checked) > label:hover:enabled, /* hover current star */
    .rating:not(:checked) > label:hover:enabled ~ label { color: #FFD700;  } /* hover previous stars in list */

    .rating > input:checked + label:hover:enabled, /* hover current star when changing rating */
    .rating > input:checked ~ label:hover:enabled,
    .rating > label:hover:enabled ~ input:checked ~ label, /* lighten current selection */
    .rating > input:checked ~ label:hover:enabled ~ label { color: #FFED85;  }

    .mt-8 {
        margin-top: 8px;
    }
    .mr-2 {
        margin-right: 2px;
    }
</style>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="box-title"><?=$title?></h3>
                        </div>
                        <div class="col-md-4">
                            <div class="box-tools">
                                <div class="input-group input-group-sm" style="width: 100%;">
                                    <input type="text" name="table_search" class="form-control pull-right" onkeyup="return cari(event, $(this).val())" value="<?=isset($this->session->search['any'])?$this->session->search['any']:''?>" placeholder="Cari Produk">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
            </div>
            <!-- /.box -->
            <div id="result_table"></div>
            <div class="box">
                <div align="center" id="pagination_link"></div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function(){
        load_data(1);
    }).on("click", ".pagination li a", function(event){
        event.preventDefault();
        var page = $(this).data("ci-pagination-page");
        load_data(page);
    });

    function load_data(page,data={})
    {
        $.ajax({
            url:"<?=base_url().$content.'/get_data/';?>"+page,
            type:"POST",
            data:data,
            dataType:"JSON",
            beforeSend: function() {
                $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
            },
            complete: function() {
                $('.first-loader').remove();
            },
            success:function(data)
            {
                $('#result_table').html(data.result_data);
                $('#pagination_link').html(data.pagination_link);
            }
        });
    }

    function cari(e, val) {
        if (e.keyCode == 13) {
            load_data(1, {search:true, any:val});
        }
    }

    function load_more(id) {
        var page = $("#page"+id).val();

        $.ajax({
            url:"<?=base_url().$content.'/load_more/';?>"+page,
            type:"POST",
            data: {id_produk: id},
            dataType:"JSON",
            beforeSend: function() {
                $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
            },
            complete: function() {
                $('.first-loader').remove();
            },
            success:function(data)
            {
                $("#page"+id).val(data.page);
                if (data.status) {
                    var ulasan = document.getElementById("cont_ulasan" + id);
                    ulasan.innerHTML = ulasan.innerHTML + data.result_data;
                } else {
                    $("#load_more"+id).hide();
                }
            }
        });
    }

    function change_status(id, st) {
        $.ajax({
            url: "<?=base_url().$content.'/change_status'?>",
            type: "POST",
            data: {id_ulasan: id, status: st},
            dataType: "JSON",
            beforeSend: function() {
                $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
            },
            complete: function() {
                $('.first-loader').remove();
            },
            success: function (res) {
                $("#cont_status"+id).html(res.status);
            }
        });
    }
</script>