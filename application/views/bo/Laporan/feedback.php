<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <div class="row">
                        <div class="col-md-2">
                            <h3 class="box-title"><?=$title?></h3>
                        </div>

                        <div class="col-md-3">
                            <div class="box-tools">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <div class="checkbox" data-toggle="tooltip" data-placement="top" title="Semua Periode">
                                          <label>
                                              <input type="checkbox" name="periode" id="periode" value="1" <?=$this->session->search['periode']=='1'?'checked':''?>>
                                          </label>
                                        </div>
                                    </span>
                                    <input type="text" id="table_date" class="form-control daterange2" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="box-tools">
                                <div class="input-group input-group-sm" style="width: 100%;">
                                    <input type="text" name="table_search" class="form-control pull-right" id="search" value="<?=isset($this->session->search['any'])?$this->session->search['any']:''?>" placeholder="Cari by Kode Member/Nama Member">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-primary bg-blue" onclick="cari()" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cari"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive" id="result_table"></div>
                <div align="center" id="pagination_link"></div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>
<?php
isset($this->session->search['date'])?$date = $this->session->search['date']:$date='null';
?>
<script>
    $(document).ready(function(){
        load_data(1);
        if ('<?=$date?>' != 'null') {
            set_date('<?=$date?>', 'daterange2');
        }
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
            success:function(data)
            {
                $('#result_table').html(data.result_table);
                $('#pagination_link').html(data.pagination_link);
                $("#user_level").html(data.user_level);
            }
        });
    }

    function cari() {
        var date_ = $("#table_date").val();
        var periode_ = null;
        var checkBox = document.getElementById("periode");
        if (checkBox.checked == true){
            periode_ = checkBox.value;
        }

        load_data(1, {search: true, any: $("#search").val(), date: date_, status: $("#status").val(), periode: periode_});
    }

    function detail(id_) {
        $.ajax({
            url: "<?=base_url().$content.'/detail'?>",
            type: "POST",
            data: {id: id_},
            dataType: "JSON",
            success: function (res) {
                $("#modal_title_detail").text("Detail Orders");
                $("#det_produk").html(res.res_produk);
                $("#modal_detail").modal("show");
            }
        });
    }
</script>