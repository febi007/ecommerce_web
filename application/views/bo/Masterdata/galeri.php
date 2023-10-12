<style>
    .result_upload {
        width: 100%;
    }

    .result_upload > div {
        position: relative;
        height: 260px;
        padding-right: 15px;
        padding-left: 15px;
        float: left;
        width: 25%;
    }

    .result_upload > div > img {
        width: 100%;
        max-height: 180px;
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
                <div class="box-body table-responsive" id="result_table">

                </div>
                <div align="center" id="pagination_link"></div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_detail_album" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title_detail"></h4>
            </div>
            <form class="form-horizontal" id="form_merk" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Gambar</h3>
                                </div>
                                <div id="det_gambar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal_galeri" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title"></h4>
            </div>
            <form class="form-horizontal" id="form_galeri" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <?php $label = 'album'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Album</label>

                            <div class="col-sm-10">
                                <div class="input-group" style="width: 100%;">
                                    <select id="<?=$label?>" name="<?=$label?>" class="form-control">
                                        <option value="">Pilih Album</option>
                                    </select>
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-primary btn-sm bg-blue" onclick="add_album()"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <?php $label = 'gambar'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Gambar</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" readonly="" class="form-control" placeholder="Browse...">
                                    <input type="file" id="<?=$label?>" name="<?=$label?>[]" style="width: 85%" multiple accept="image/*">

                                    <div class="input-group-btn" id="btn_<?=$label?>">
                                        <button type="button" class="btn btn-primary btn-sm bg-blue" onclick="view_gambar()"><i class="fa fa-image"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <output class="result_upload" id="result">
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

<div class="modal fade" tabindex="-1" role="dialog" id="modal_album" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title_album"></h4>
            </div>
            <form class="form-horizontal" id="form_album" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <?php $label = 'nama'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Nama Album</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>_album" autocomplete="off" placeholder="Nama Album">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'gambar'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Gambar</label>

                            <div class="col-sm-10">
                                <input type="text" readonly="" class="form-control" placeholder="Browse...">
                                <input type="file" id="<?=$label?>_album" name="<?=$label?>" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="simpan" name="simpan">Simpan</button>
                </div>
                <input type="hidden" name="param" id="param_album" value="add">
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_gambar" style="display: none">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title_gambar"></h4>
            </div>
            <form class="form-horizontal" id="form_merk" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="view_gambar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    $(document).ready(function(){
        load_data(1);
        load_album();
    }).on("click", ".pagination li a", function(event){
        event.preventDefault();
        var page = $(this).data("ci-pagination-page");
        load_data(page);
    });

    function handleFileSelect() {
        //Check File API support
        if (window.File && window.FileList && window.FileReader) {

            var files = event.target.files; //FileList object
            var output = document.getElementById("result");

            for (var i = 0; i < files.length; i++) {

                var file = files[i];
                //Only pics
                if (!file.type.match('image')) continue;

                var picReader = new FileReader();
                picReader.addEventListener("load", function (event) {
                    var picFile = event.target;
                    var div = document.createElement("div");
                    div.innerHTML = "<img class='image-responsive' src='" + picFile.result + "'" + " title='" + picFile.name + "'/><input type='text' name='label_gambar[]' placeholder='Label Gambar' class='form-control'>";
                    output.insertBefore(div, null);
                });
                //Read the image
                picReader.readAsDataURL(file);
            }
        } else {
            console.log("Your browser does not support File API");
        }
    }

    document.getElementById('gambar').addEventListener('change', handleFileSelect, false);

    function load_album() {
        $.ajax({
            url: "<?=base_url().$content.'/get_album'?>",
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
                    $("#album").html(res.album);
                } else {
                    alert("Error getting data!")
                }
            }
        });
    }

    function load_gambar(id_) {
        $.ajax({
            url: "<?=base_url().$content.'/get_gambar'?>",
            type: "POST",
            data: {id: id_},
            beforeSend: function() {
                $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
            },
            complete: function() {
                $('.first-loader').remove();
            },
            success: function (res) {
                $("#view_gambar").html(res);
            }
        });
    }

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
            }
        });
    }

    function cari(e, val) {
        if (e.keyCode == 13) {
            load_data(1, {search:true, any:val});
        }
    }

    function add() {
        $("#modal_title").text("Tambah Galeri");
        $("#param").val("add");
        $("#modal_galeri").modal("show");
        setTimeout(function () {
            $("#album").focus();
        }, 600);
    }

    function add_album() {
        $("#modal_title_album").text("Tambah Album");
        $("#modal_album").modal("show");
        $("#modal_galeri").modal("hide");
        setTimeout(function () {
            $("#nama_album").focus();
        }, 600);
    }

    function view_gambar() {
        var param = $("#param").val();
        if (param == 'edit') {
            $("#modal_title_gambar").text("Foto Album Saat Ini");
            $("#modal_gambar").modal('show');
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
                $("#modal_title_detail").text(res.album['nama']);
                $("#det_gambar").html(res.gambar);
                $("#modal_detail_album").modal("show");
            }
        });
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
                    array_varian = [];
                    array_harga = [];
                    $("#modal_title").text("Edit Galeri");
                    $("#param").val("edit");
                    $("#id").val(id);
                    $("#album").val(res.res_data['id_album']).change();

                    load_gambar(id);

                    $("#modal_galeri").modal("show");
                    setTimeout(function () {
                        $("#album").focus();
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

    function hapus_gambar(id_) {
        if (confirm("Akan menghapus gambar?")) {
            $.ajax({
                url: "<?=base_url().$content.'/hapus_gambar'?>",
                type: "POST",
                data: {id: id_},
                beforeSend: function() {
                    $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
                },
                complete: function() {
                    $('.first-loader').remove();
                },
                success: function () {
                    load_gambar($("#id").val());
                }
            });
        }
    }

    $('#form_galeri').validate({
        rules: {
            album: {
                required: true
            }
        },
        //For custom messages
        messages: {
            album: {
                required: "Album harus dipilih!"
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
            var myForm = document.getElementById('form_galeri');
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
                        $("#modal_galeri").modal('hide');
                        load_data(1);
                    } else {
                        alert("Data gagal disimpan!");
                    }
                }
            });
        }
    });

    $('#form_album').validate({
        rules: {
            nama: {
                required: true
            }
        },
        //For custom messages
        messages: {
            nama:{
                required: "Nama album tidak boleh kosong!"
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
            var myForm = document.getElementById('form_album');
            $.ajax({
                url: "<?=base_url().$content.'/simpan_album'?>",
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
                        $("#modal_album").modal('hide');
                        load_album();
                    } else {
                        alert("Data gagal disimpan!");
                    }
                }
            });
        }
    });

    $("#modal_galeri").on("hide.bs.modal", function () {
        document.getElementById("form_galeri").reset();
        $( "#form_galeri" ).validate().resetForm();
        $("#result").html('');
    });

    $("#modal_album").on("hide.bs.modal", function () {
        document.getElementById("form_album").reset();
        $("#modal_galeri").modal('show');
        $( "#form_album" ).validate().resetForm();
    });
</script>