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

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_rekening" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title"></h4>
            </div>
            <form class="form-horizontal" id="form_rekening">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <?php $label = 'bank'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Bank</label>

                            <div class="col-sm-10">
                                <div class="input-group" style="width: 100%;">
                                    <select id="<?=$label?>" name="<?=$label?>" class="form-control">
                                        <option value="">Pilih Bank</option>
                                    </select>
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-primary btn-sm bg-blue" onclick="add_bank()"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'atas_nama'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Atas Nama</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Atas Nama">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'no_rek'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Nomor Rekening</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control positive-integer" id="<?=$label?>" autocomplete="off" placeholder="Nomor Rekening">
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

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_bank" style="display: none">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title_bank"></h4>
            </div>
            <form class="form-horizontal" id="form_bank">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <?php $label = 'nama'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Nama Bank</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Nama Bank">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="simpan" name="simpan">Simpan</button>
                </div>
                <input type="hidden" name="param" id="param_bank" value="add">
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    $(document).ready(function(){
        load_data(1);
        load_bank();
    }).on("click", ".pagination li a", function(event){
        event.preventDefault();
        var page = $(this).data("ci-pagination-page");
        load_data(page);
    });

    function load_data(page,data={}) {
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
                $("#user_level").html(data.user_level);
            }
        });
    }

    function cari(e, val) {
        if (e.keyCode == 13) {
            load_data(1, {search:true, any:val});
        }
    }

    function add() {
        $("#modal_title").text("Tambah Rekening");
        $("#param").val("add");
        $("#modal_rekening").modal("show");
        setTimeout(function () {
            $("#bank").focus();
        }, 600);
    }

    function add_bank() {
        $("#modal_title_bank").text("Tambah Bank");
        $("#modal_bank").modal("show");
        setTimeout(function () {
            $("#nama").focus();
        }, 600);
    }

    function edit(id) {
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
                    $("#modal_title").text("Edit Rekening");
                    $("#param").val("edit");
                    $("#id").val(id);
                    $("#atas_nama").val(res.res_rekening['atas_nama']);
                    $("#no_rek").val(res.res_rekening['no_rek']);
                    $("#bank").val(res.res_rekening['bank']).change();
                    $("#modal_rekening").modal("show");
                    setTimeout(function () {
                        $("#bank").focus();
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
                        alert("Error deleting data!")
                    }
                }
            });
        }
    }

    function load_bank() {
        $.ajax({
            url: "<?=base_url().$content.'/get_bank'?>",
            type: "GET",
            dataType: "JSON",
            beforeSend: function() {
                $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
            },
            complete: function() {
                $('.first-loader').remove();
            },
            success: function (res) {
                if (res.status) {
                    $("#bank").html(res.bank);
                } else {
                    alert("Error getting data!")
                }
            }
        });
    }

    $('#form_bank').validate({
        rules: {
            nama: {
                required: true,
                remote: {
                    url: "<?=base_url().'Masterdata/bank/cek_nama'?>",
                    type: "post",
                    data: {
                        param: function() {
                            return $("#param_bank").val();
                        }
                    }
                }
            }
        },
        //For custom messages
        messages: {
            nama:{
                required: "Nama bank tidak boleh kosong!",
                remote: "Nama bank sudah tersedia!"
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
            $.ajax({
                url: "<?=base_url().'Masterdata/bank/simpan'?>",
                type: "POST",
                data: $("#form_bank").serialize(),
                beforeSend: function() {
                    $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
                },
                complete: function() {
                    $('.first-loader').remove();
                },
                success: function (res) {
                    if (res) {
                        $("#modal_bank").modal('hide');
                        load_bank();
                    } else {
                        alert("Data gagal disimpan!");
                    }
                }
            });
        }
    });

    $('#form_rekening').validate({
        rules: {
            bank: {
                required: true
            },
            no_rek: {
                required: true,
                remote: {
                    url: "<?=base_url().$content.'/cek_rekening'?>",
                    type: "post",
                    data: {
                        param: function() {
                            return $("#param").val();
                        }
                    }
                }
            },
            atas_nama: {
                required: true
            }
        },
        //For custom messages
        messages: {
            bank:{
                required: "Bank harus dipilih!"
            },
            no_rek:{
                required: "Nomor rekening tidak boleh kosong!",
                remote: "Nomor rekening sudah tersedia!"
            },
            atas_nama: "Atas nama tidak boleh kosong!"
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
            $.ajax({
                url: "<?=base_url().$content.'/simpan'?>",
                type: "POST",
                data: $("#form_rekening").serialize(),
                beforeSend: function() {
                    $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
                },
                complete: function() {
                    $('.first-loader').remove();
                },
                success: function (res) {
                    if (res) {
                        $("#modal_rekening").modal('hide');
                        load_data(1);
                    } else {
                        alert("Data gagal disimpan!");
                    }
                }
            });
        }
    });

    $("#modal_rekening").on("hide.bs.modal", function () {
        document.getElementById("form_rekening").reset();
        $( "#form_rekening" ).validate().resetForm();
    });

    $("#modal_bank").on("hide.bs.modal", function () {
        document.getElementById("form_bank").reset();
        $( "#form_bank" ).validate().resetForm();
    });
</script>