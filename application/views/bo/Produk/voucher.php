<style>
    .btn, .input-group-btn .btn {
        margin: 0px !important;
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
                                    <input type="text" name="table_search" class="form-control pull-right" onkeyup="return cari(event, $(this).val())" value="<?=isset($this->session->search['any'])?$this->session->search['any']:''?>" placeholder="Cari">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-primary bg-blue" onclick="add()"><i class="fa fa-plus"></i></button>
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

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_voucher" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title"></h4>
            </div>
            <form class="form-horizontal" id="form_data" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <?php $label = 'nama'; ?>
                                    <label for="<?=$label?>" class="col-sm-2 control-label">Voucher</label>

                                    <div class="col-sm-10">
                                        <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Nama Voucher">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php $label = 'deskripsi'; ?>
                                    <label for="<?=$label?>" class="col-sm-2 control-label">Deskripsi</label>

                                    <div class="col-sm-10">
                                        <textarea type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" rows="4" autocomplete="off" placeholder="Deskripsi"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php $label = 'gambar'; ?>
                                    <label for="<?=$label?>" class="col-sm-2 control-label">Gambar</label>

                                    <div class="col-sm-10">
                                        <input type="text" readonly="" class="form-control" placeholder="Browse...">
                                        <input type="file" id="<?=$label?>" name="<?=$label?>" accept="image/*">
                                    </div>
                                </div>
                                <div class="form-group" id="img_preview">
                                    <label class="col-sm-2 control-label">Gambar Sekarang</label>

                                    <div class="col-sm-10">
                                        <img class="img_preview" id="preview">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php $label = 'status'; ?>
                                    <label for="<?=$label?>" class="col-sm-2 control-label">Status</label>

                                    <div class="col-sm-10" style="padding-top: 16px">
                                        <div class="togglebutton">
                                            <label>
                                                <input type="checkbox" value="1" id="<?=$label?>" name="<?=$label?>" checked>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <?php $label = 'periode'; ?>
                                    <label for="<?=$label?>" class="col-sm-2 control-label">Periode</label>

                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <input type="text" class="form-control pull-right datetimerange" name="<?=$label?>" id="<?=$label?>" autocomplete="off" placeholder="Periode" readonly>
                                            <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php $label = 'quota'; ?>
                                    <label for="<?=$label?>" class="col-sm-2 control-label">Quota</label>

                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <input type="text" name="<?=$label?>" class="form-control positive-integer" id="<?=$label?>" autocomplete="off" placeholder="Quota Penggunaan">
                                            <span class="input-group-addon">x</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php $label = 'min_orders'; ?>
                                    <label for="<?=$label?>" class="col-sm-2 control-label">Min Orders</label>

                                    <div class="col-sm-10">
                                        <input type="text" name="<?=$label?>" class="form-control currency" id="<?=$label?>" autocomplete="off" placeholder="Min Orders">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php $label = 'jenis'; ?>
                                    <label for="<?=$label?>" class="col-sm-2 control-label">Jenis</label>

                                    <div class="col-sm-10">
                                        <select class="form-control" onchange="change_class()" id="<?=$label?>" name="<?=$label?>">
                                            <option value="nominal">Nominal (Rp)</option>
                                            <option value="persen">Persen (%)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php $label = 'value'; ?>
                                    <label for="<?=$label?>" class="col-sm-2 control-label">Potongan</label>

                                    <div class="col-sm-10">
                                        <input type="text" name="nominal" class="form-control currency" id="nominal" autocomplete="off" placeholder="Potongan">
                                        <input type="text" name="persen" class="form-control positive-integer" style="display: none" id="persen" autocomplete="off" placeholder="Potongan">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="simpan" name="simpan">Simpan</button>
                </div>
                <input type="hidden" name="param" id="param" value="add">
                <input type="hidden" name="id" id="id">
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    $(document).ready(function(){
        load_data(1);
    }).on("click", ".pagination li a", function(event){
        event.preventDefault();
        var page = $(this).data("ci-pagination-page");
        load_data(page);
    });

    function change_class() {
        if ($("#jenis").val() == 'nominal') {
            $("#nominal").show();
            $("#persen").hide();
            $("#nominal").rules("add", {
                required: true,
                messages: {
                    required: "Potongan tidak boleh kosong!"
                }
            });
            $("#persen").rules("remove", "required");
        } else {
            $("#nominal").hide();
            $("#persen").show();
            $("#persen").rules("add", {
                required: true,
                messages: {
                    required: "Potongan tidak boleh kosong!"
                }
            });
            $("#nominal").rules("remove", "required");
        }
    }

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

    function cari(e, val) {
        if (e.keyCode == 13) {
            load_data(1, {search:true, any:val});
        }
    }

    function add() {
        $("#modal_title").text("Tambah Voucher");
        $("#param").val("add");
        $("#modal_voucher").modal("show");
        document.getElementById("img_preview").style.display = 'none';
        setTimeout(function () {
            $("#promo").focus();
        }, 600);
    }

    function edit(id) {
        array_produk = [];
        $.ajax({
            url: "<?=base_url().$content.'/edit'?>",
            type: "POST",
            data: {id: id},
            dataType: "JSON",
            beforeSend: function() {
                $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
            },
            complete: function() {
                $('.first-loader').remove();
            },
            success: function (res) {
                if (res.status) {
                    $("#modal_title").text("Edit Voucher");
                    $("#param").val("edit");
                    $("#id").val(id);
                    $("#nama").val(res.res_data['nama']);
                    $("#deskripsi").val(res.res_data['deskripsi']);
                    set_date(res.res_data['periode'], 'datetimerange');
                    $("#quota").val(res.res_data['quota']);
                    $("#preview").attr("src", "<?=base_url()?>"+res.res_data['gambar']);
                    $("#min_orders").autoNumeric('set', res.res_data['min_orders']);
                    if (res.res_data['status']=='') {
                        $("#status").prop("checked", false);
                    }
                    document.getElementById("img_preview").style.display = 'block';
                    $("#modal_voucher").modal("show");
                    setTimeout(function () {
                        $("#nama").focus();
                        $("#jenis").val(res.res_data['jenis']).change();
                        if (res.res_data['jenis']=='nominal') {
                            $("#nominal").autoNumeric('set', res.res_data['value']);
                        } else {
                            $("#persen").val(res.res_data['value']);
                        }
                    }, 600);
                } else {
                    alert("Error getting data!")
                }
            }
        });
    }

    function hapus(id) {
        if (confirm("Akan menghapus data?")) {
            $.ajax({
                url: "<?=base_url() . $content . '/hapus'?>",
                type: "POST",
                data: {id: id},
                beforeSend: function() {
                    $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
                },
                complete: function() {
                    $('.first-loader').remove();
                },
                success: function (res) {
                    if (res) {
                        load_data(1);
                    } else {
                        alert("Error deleting data!");
                    }
                },
                error: function(xhr, status, error) {
                    alert("Data tidak bisa dihapus!");
                    console.log(xhr.responseText);
                }
            });
        }
    }

    function detail(id) {
        $.ajax({
            url: "<?=base_url().$content.'/detail'?>",
            type: "POST",
            data: {id: id},
            dataType: "JSON",
            beforeSend: function() {
                $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
            },
            complete: function() {
                $('.first-loader').remove();
            },
            success: function (res) {
                $("#modal_title_detail").text("Detail Voucher");
                $("#d_promo").val(res.res_data['promo']);
                $("#d_deskripsi").val(res.res_data['deskripsi']);
                $("#d_periode").val(res.res_data['periode']);
                $("#d_diskon").val(res.res_data['diskon']);
                $("#d_gambar").attr("src", "<?=base_url()?>"+res.res_data['gambar']);
                $("#list_det_promo").html(res.det_data);
                $("#modal_detail_promo").modal("show");
            }
        });
    }

    $('#form_data').validate({
        ignore: [],
        rules: {
            nama: {
                required: true
            },
            periode: {
                required: true
            },
            quota: {
                required: true
            },
            min_orders: {
                required: true
            },
            nominal: {
                required: true
            }
        },
        //For custom messages
        messages: {
            nama:{
                required: "Nama voucher tidak boleh kosong!"
            },
            periode:{
                required: "Periode wajib dipilih!"
            },
            quota:{
                required: "Quota tidak boleh kosong!"
            },
            min_orders:{
                required: "Min orders tidak boleh kosong!"
            },
            nominal:{
                required: "Nominal tidak boleh kosong!"
            }
        },
        errorElement : 'div',
        errorPlacement: function(error, element) {
            var placement = $(element).data('error');
            if (placement) {
                $(placement).append(error)
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            var myForm = document.getElementById('form_data');
            $.ajax({
                url: "<?=base_url().$content.'/simpan'?>",
                type: "POST",
                data: new FormData(myForm),
                mimeType: "multipart/form-data",
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
                },
                complete: function() {
                    $('.first-loader').remove();
                },
                success: function (res) {
                    if (res) {
                        $("#modal_voucher").modal('hide');
                        load_data(1);
                    } else {
                        alert("Data gagal disimpan!");
                    }
                }
            });
        }
    });

    $("#modal_voucher").on("hide.bs.modal", function () {
        document.getElementById("form_data").reset();
        $( "#form_data" ).validate().resetForm();
    });
</script>
