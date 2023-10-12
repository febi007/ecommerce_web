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

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_kelompok" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title"></h4>
            </div>
            <form class="form-horizontal" id="form_kelompok" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <?php $label = 'group'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Group</label>

                            <div class="col-sm-10">
                                <div class="input-group" style="width: 100%;">
                                    <select id="<?=$label?>" name="<?=$label?>" class="form-control">
                                        <option value="">Pilih Group</option>
                                    </select>
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-primary btn-sm bg-blue" onclick="add_group()"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'nama'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Nama Kelompok</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Nama Kelompok">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'gambar'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Gambar</label>

                            <div class="col-sm-10">
                                <input type="text" readonly="" class="form-control" placeholder="Browse...">
                                <input type="file" id="<?=$label?>" name="<?=$label?>" accept="image/*">

                                <p class="help-block">Gambar akan ditampilkan sebagai menu pada website utama.</p>
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

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_group" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title_group"></h4>
            </div>
            <form class="form-horizontal" id="form_group" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <?php $label = 'nama'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Nama Group</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>_group" autocomplete="off" placeholder="Nama Group">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'gambar'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Gambar</label>

                            <div class="col-sm-10">
                                <input type="text" readonly="" class="form-control" placeholder="Browse...">
                                <input type="file" id="<?=$label?>_group" name="<?=$label?>" accept="image/*">

                                <p class="help-block">Gambar akan ditampilkan sebagai menu pada website utama.</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'status'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Status</label>

                            <div class="col-sm-10" style="padding-top: 16px">
                                <div class="togglebutton">
                                    <label>
                                        <input type="checkbox" value="1" id="<?=$label?>_group" name="<?=$label?>" checked>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="simpan" name="simpan">Simpan</button>
                </div>
                <input type="hidden" name="param" id="param_group" value="add">
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    $(document).ready(function(){
        load_data(1);
        load_group();
    }).on("click", ".pagination li a", function(event){
        event.preventDefault();
        var page = $(this).data("ci-pagination-page");
        load_data(page);
    });

    function load_group() {
        $.ajax({
            url: "<?=base_url().$content.'/get_group'?>",
            type: "GET",
            dataType: "JSON",
            success: function (res) {
                if (res.status) {
                    $("#group").html(res.group);
                } else {
                    alert("Error getting data!")
                }
            }
        });
    }

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
            }
        });
    }

    function cari(e, val) {
        if (e.keyCode == 13) {
            load_data(1, {search:true, any:val});
        }
    }

    function add() {
        $("#modal_title").text("Tambah Kelompok");
        $("#param").val("add");
        $("#modal_kelompok").modal("show");
        document.getElementById("img_preview").style.display = 'none';
        setTimeout(function () {
            $("#group").focus();
        }, 600);
    }

    function add_group() {
        $("#modal_title_group").text("Tambah Group");
        $("#modal_group").modal("show");
        setTimeout(function () {
            $("#nama_group").focus();
        }, 600);
    }

    function edit(id) {
        $.ajax({
            url: "<?=base_url().$content.'/edit'?>",
            type: "POST",
            data: {id: id},
            dataType: "JSON",
            success: function (res) {
                if (res.status) {
                    $("#modal_title").text("Edit Kelompok");
                    $("#param").val("edit");
                    $("#id").val(id);
                    $("#nama").val(res.res_kelompok['nama']);
                    $("#group").val(res.res_kelompok['groups']).change();
                        $("#preview").attr("src", "<?=base_url()?>"+res.res_kelompok['gambar']);
                        document.getElementById("img_preview").style.display = 'block';
                    if (res.res_kelompok['status']=='0') {
                        $("#status").prop("checked", false);
                    }
                    $("#modal_kelompok").modal("show");
                    setTimeout(function () {
                        $("#group").focus();
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

    $('#form_kelompok').validate({
        rules: {
            nama: {
                required: true,
                remote: {
                    url: "<?=base_url().$content.'/cek_nama'?>",
                    type: "post",
                    data: {
                        param: function() {
                            return $("#param").val();
                        },
                        group: function() {
                            return $("#group").val();
                        }
                    }
                }
            },
            group: {
                required: true,
                remote: {
                    url: "<?=base_url().$content.'/cek_nama'?>",
                    type: "post",
                    data: {
                        param: function() {
                            return $("#param").val();
                        },
                        nama: function() {
                            return $("#nama").val();
                        }
                    }
                }
            }
        },
        //For custom messages
        messages: {
            nama:{
                required: "Nama kelompok tidak boleh kosong!",
                remote: "Nama kelompok sudah tersedia!"
            },
            group:{
                required: "Group harus dipilih!",
                remote: "Nama kelompok sudah tersedia!"
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
            var myForm = document.getElementById('form_kelompok');
            $.ajax({
                url: "<?=base_url().$content.'/simpan'?>",
                type: "POST",
                data: new FormData(myForm),
                mimeType: "multipart/form-data",
                contentType: false,
                processData: false,
                success: function (res) {
                    if (res) {
                        $("#modal_kelompok").modal('hide');
                        load_data(1);
                    } else {
                        alert("Data gagal disimpan!");
                    }
                }
            });
        }
    });

    $('#form_group').validate({
        rules: {
            nama: {
                required: true,
                remote: {
                    url: "<?=base_url().'Produk/group/cek_nama'?>",
                    type: "post",
                    data: {
                        param: function() {
                            return $("#param_group").val();
                        }
                    }
                }
            }
        },
        //For custom messages
        messages: {
            nama:{
                required: "Nama group tidak boleh kosong!",
                remote: "Nama group sudah tersedia!"
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
            var myForm = document.getElementById('form_group');
            $.ajax({
                url: "<?=base_url().'Produk/group/simpan'?>",
                type: "POST",
                data: new FormData(myForm),
                mimeType: "multipart/form-data",
                contentType: false,
                processData: false,
                success: function (res) {
                    if (res) {
                        $("#modal_group").modal('hide');
                        load_group();
                    } else {
                        alert("Data gagal disimpan!");
                    }
                }
            });
        }
    });

    $("#modal_kelompok").on("hide.bs.modal", function () {
        document.getElementById("form_kelompok").reset();
        $( "#form_kelompok" ).validate().resetForm();
    });

    $("#modal_group").on("hide.bs.modal", function () {
        document.getElementById("form_group").reset();
        $( "#form_group" ).validate().resetForm();
    });
</script>