<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <div class="row">
                        <div class="col-md-4">
                            <h3 class="box-title"><?=$title?></h3>
                        </div>

                        <div class="col-md-8">
                            <div class="box-tools">
                                <div class="form-inline pull-right">
                                    <div class="input-group input-group-sm">
                                        <input type="text" id="table_date" class="form-control daterange2" style="width: 200px" readonly>
                                    </div>
                                    <div class="input-group input-group-sm">
                                        <input type="text" id="table_search" class="form-control" onkeyup="return cari(event)" value="<?=isset($this->session->search['any'])?$this->session->search['any']:''?>" placeholder="Cari">
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-primary bg-blue" onclick="cari(event)"><i class="fa fa-search"></i></button>
                                        </div>
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

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_detail" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title"></h4>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <?php $label = 'code'; ?>
                                <label for="<?=$label?>" class="col-sm-2 control-label">SKU</label>

                                <div class="col-sm-10">
                                    <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php $label = 'nama'; ?>
                                <label for="<?=$label?>" class="col-sm-2 control-label">Nama</label>

                                <div class="col-sm-10">
                                    <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <?php $label = 'periode'; ?>
                                <label for="<?=$label?>" class="col-sm-2 control-label">Periode</label>

                                <div class="col-sm-10">
                                    <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="box-header with-border"></div>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Kode Transaksi</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Stok Masuk</th>
                                <th>Stok Keluar</th>
                            </tr>
                            </thead>
                            <tbody id="list_det_stok"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<?php
isset($this->session->search['date'])?$date=$this->session->search['date']:$date='null';
?>
<script>
    $(document).ready(function(){
        if ('<?=$date?>' != 'null') {
            set_date('<?=$date?>', 'daterange2');
        }
        load_data(<?=$this->session->search['page']?>);
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
                $('#result_table').html(data.result_table);
                $('#pagination_link').html(data.pagination_link);
            }
        });
    }

    function cari(e) {
        if (e.keyCode == 13 || e.button == 0) {
            load_data(1, {search:true, any:$("#table_search").val(), date:$("#table_date").val()});
        }
    }

    function detail(id) {
        $.ajax({
            url: "<?=base_url().$content.'/detail_api'?>",
            type: "POST",
            data: {id: id, date:$("#table_date").val()},
            dataType: "JSON",
            beforeSend: function() {
                $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
            },
            complete: function() {
                $('.first-loader').remove();
            },
            success: function (res) {
                if (res.status) {
                    $("#modal_title").text("Detail Stok Transaksi");
                    $("#code").val(res.produk.code);
                    $("#nama").val(res.produk.nama);
                    $("#periode").val(res.date);
                    $("#list_det_stok").html(res.list);
                    $("#modal_detail").modal("show");
                } else {
                    alert("Error getting data!")
                }
            }
        });
    }
</script>
