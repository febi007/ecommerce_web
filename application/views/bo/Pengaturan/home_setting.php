<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="box-title"><?=$title?></h3>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-header">
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-primary bg-blue" onclick="edit('1111', 'home_dashboard')"><i class="fa fa-edit"></i></button>
                    </div>
                </div>
                <div class="box-body no-padding text-center" id="result_dashboard"></div>
                <!-- /.box-body -->
            </div>
            <div class="box">
                <div class="box-body table-responsive no-padding" id="result_top"></div>
            </div>
            <div class="box">
                <div class="box-body table-responsive no-padding" id="result_middle"></div>
            </div>
            <div class="box">
                <div class="box-body table-responsive no-padding" id="result_bottom"></div>
            </div>
            <div class="box">
                <div class="box-header">
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-primary bg-blue" onclick="add_slide()"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="box-body table-responsive no-padding" id="result_slider"></div>
            </div>
            <div class="box">
                <div class="box-header">
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-primary bg-blue" onclick="edit('1111', 'text')"><i class="fa fa-edit"></i></button>
                    </div>
                </div>
                <div class="box-body table-responsive no-padding" id="result_text"></div>
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_item" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title"></h4>
            </div>
            <form class="form-horizontal" id="form_item" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="form-group">
                                    <?php $label = 'nama'; ?>
                                    <label for="<?=$label?>" class="col-sm-2 control-label">Nama</label>

                                    <div class="col-sm-10">
                                        <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Nama">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
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
                            </div>
                        </div>
                        <div class="row">
                            <div class="box-header with-border"></div>
                            <div class="form-group">
                                <?php $label = 'cari'; ?>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Cari Produk">
                                        <span class="input-group-addon"><span class="fa fa-search"></span></span>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th width="1%">#</th>
                                    <th>SKU</th>
                                    <th>Nama</th>
                                    <th>Berat (gram)</th>
                                    <th>Harga</th>
                                </tr>
                                </thead>
                                <tbody id="list_item">
                                <tr><td colspan="5" class="text-center"><input type="hidden" name="list" value=""></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="simpan" name="simpan">Simpan</button>
                </div>
                <input type="hidden" name="param" id="param" value="">
                <input type="hidden" name="id" id="id">
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_slider" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title_slider"></h4>
            </div>
            <form class="form-horizontal" id="form_slider" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="form-group">
                                    <?php $label = 'gambar'; ?>
                                    <label for="<?=$label?>" class="col-sm-2 control-label">Gambar</label>

                                    <div class="col-sm-10">
                                        <input type="text" readonly="" class="form-control" placeholder="Browse...">
                                        <input type="file" id="<?=$label?>" name="<?=$label?>" accept="image/*">

                                        <p class="help-block">Gambar akan ditampilkan sebagai menu pada website utama.</p>
                                    </div>
                                </div>
                                <div class="form-group" id="img_preview_slider">
                                    <label class="col-sm-2 control-label">Gambar Sekarang</label>

                                    <div class="col-sm-10">
                                        <img class="img_preview" id="preview_slider">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="simpan" name="simpan">Simpan</button>
                </div>
                <input type="hidden" name="param" id="param_slider" value="">
                <input type="hidden" name="id" id="id_slider">
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_text" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Data</h4>
            </div>
            <form class="form-horizontal" id="form_deskripsi">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <?php $label = 'title'; ?>
                                    <label for="<?=$label?>" class="col-sm-2 control-label">Title</label>

                                    <div class="col-sm-10">
                                        <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Title">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php $label = 'desc'; ?>
                                    <label for="<?=$label?>" class="col-sm-2 control-label">Deskripsi</label>

                                    <div class="col-sm-10">
                                        <textarea type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Tentang Kami"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="simpan" name="simpan">Simpan</button>
                </div>
                <input type="hidden" name="param" id="param_text" value="">
                <input type="hidden" name="id" id="id_text">
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_dashboard" style="display: none">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Data</h4>
            </div>
            <form class="form-horizontal" id="form_dashboard">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <?php $label = 'home_dashboard'; ?>
                                    <label for="<?=$label?>" class="col-sm-2 control-label">Content</label>

                                    <div class="col-sm-10">
                                        <div class="form-group">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="<?=$label?>" id="<?=$label?>_video" value="video" checked>
                                                    Video
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="<?=$label?>" id="<?=$label?>_slide" value="slide">
                                                    Slide
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="simpan" name="simpan">Simpan</button>
                </div>
                <input type="hidden" name="param" id="param_dashboard" value="">
                <input type="hidden" name="id" id="id_dashboard">
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    $(document).ready(function(){
        load_data(1);
        set_ckeditor('desc');
    });

    $("#cari").autocomplete({
        minChars: 3,
        serviceUrl: '<?=base_url().$content.'/get_produk'?>',
        type: 'post',
        dataType: 'json',
        response: function(event, ui) {
            // ui.content is the array that's about to be sent to the response callback.
            if (ui.content.length === 0) {
                $("#empty-message").text("No results found");
            } else {
                $("#empty-message").empty();
            }
        },
        onSelect: function (suggestion) {
            if (suggestion.id_produk != 'not_found') {
                add_produk(suggestion);
            } else {
                $("#cari").val('').focus();
            }
        }
    });

    var array_produk = [];
    function add_produk(res) {
        var list_produk = '';
        if (res == 'new') {
            list_produk += '<tr><td colspan="5" class="text-center"><input type="hidden" name="list" value=""></td></tr>';
        } else {
            var status = true;
            var data_produk = {
                id_produk: res['id_produk'],
                sku: res['code'],
                nama: res['nama'],
                harga: res['hrg_jual'],
                berat: res['berat']
            };
            if (array_produk.length > 0) {
                for (var i = 0; i < array_produk.length; i++) {
                    if (array_produk[i].id_produk === res['id_produk']) {
                        status = false;
                    }
                }

            }
            if (status && res!='edit') {
                array_produk.push(data_produk);
            }
            for (var x = 0; x < array_produk.length; x++) {
                list_produk += '<tr>' +
                    '<td><button type="button" onclick="remove_produk(' + x + ')" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-close"></i></button></td>' +
                    '<td>' + array_produk[x]['sku'] + '</td>' +
                    '<td>' + array_produk[x]['nama'] + '</td>' +
                    '<td>' + array_produk[x]['berat'] + '</td>' +
                    '<td class="text-center">' + to_rp(array_produk[x]['harga']) + '</td>' +
                    '</tr>';
                list_produk += '<input type="hidden" name="id_produk[]" value="' + array_produk[x]['id_produk'] + '">';
            }

            $("#cari").val('').focus();
        }
        $("#list_item").html(list_produk);
    }

    function remove_produk(id) {
        var list_produk = '';
        array_produk.splice(id, 1);
        for (var x = 0; x < array_produk.length; x++) {
            list_produk += '<tr>' +
                '<td><button type="button" onclick="remove_produk(' + x + ')" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-close"></i></button></td>' +
                '<td>'+array_produk[x]['sku']+'</td>' +
                '<td>'+array_produk[x]['nama']+'</td>' +
                '<td>'+array_produk[x]['berat']+'</td>' +
                '<td class="text-center">'+to_rp(array_produk[x]['harga'])+'</td>' +
                '</tr>';
            list_produk += '<input type="hidden" name="id_produk[]" value="'+array_produk[x]['id_produk']+'">';
        }

        if (array_produk.length == 0) {
            list_produk += '<tr><td colspan="5" class="text-center"><input type="hidden" name="list" value=""></td></tr>';
        }

        $("#list_item").html(list_produk);
        $("#cari").val('').focus();
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
                $('#result_dashboard').html(data.result_dashboard);
                $('#result_top').html(data.result_top);
                $('#result_middle').html(data.result_middle);
                $('#result_bottom').html(data.result_bottom);
                $('#result_slider').html(data.result_slider);
                $('#result_text').html(data.result_text);
            }
        });
    }

    function add_slide() {
        $("#modal_title_slider").text("Add Data");
        $("#param_slider").val('slider');
        $("#id_slider").val('add');
        document.getElementById("img_preview_slider").style.display = 'none';
        $("#modal_slider").modal("show");
    }

    function hapus(id, param) {
        if (confirm('Akan menghapus data?')) {
            $.ajax({
                url: "<?=base_url() . $content . '/hapus'?>",
                type: "POST",
                data: {id: id, param: param},
                success: function (res) {
                    if (res) {
                        load_data(1);
                    } else {
                        alert("Data gagal dihapus!");
                    }
                }
            });
        }
    }

    function edit(id, param) {
        $.ajax({
            url: "<?=base_url().$content.'/edit'?>",
            type: "POST",
            data: {id: id, table: param},
            dataType: "JSON",
            success: function (res) {
                if (res.status) {
                    if (param === 'slider') {
                        $("#modal_title_slider").text("Edit Data");
                        $("#param_slider").val(param);
                        $("#id_slider").val(id);
                        $("#preview_slider").attr("src", "<?=base_url()?>" + res.res_data['gambar']);
                        document.getElementById("img_preview_slider").style.display = 'block';
                        $("#modal_slider").modal("show");
                    } else if (param === 'text') {
                        $("#param_text").val(param);
                        $("#id_text").val(id);
                        $("#title").val(res.res_data['title']);
                        CKEDITOR.instances.desc.setData(res.res_data['desc']);
                        $("#modal_text").modal("show");
                    } else if (param === 'home_dashboard') {
                        $("#param_dashboard").val(param);
                        if (res.res_data == 'slide') {
                            $("#home_dashboard_slide").prop('checked', true);
                        } else {
                            $("#home_dashboard_video").prop('checked', true);
                        }
                        $("#id_dashboard").val(id);
                        $("#modal_dashboard").modal("show");
                    } else {
                        $("#modal_title").text("Edit Data");
                        $("#param").val(param);
                        $("#id").val(id);
                        $("#nama").val(res.res_data['nama']);
                        $("#preview").attr("src", "<?=base_url()?>" + res.res_data['gambar']);
                        document.getElementById("img_preview").style.display = 'block';
                        $("#modal_item").modal("show");
                        for (x = 0; x < res.det_item.length; x++) {
                            var data_produk = {
                                id_produk: res.det_item[x]['id_produk'],
                                sku: res.det_item[x]['code'],
                                nama: res.det_item[x]['nama'],
                                harga: res.det_item[x]['hrg_jual'],
                                berat: res.det_item[x]['berat']
                            };
                            array_produk.push(data_produk);
                        }
                        add_produk('edit');
                        setTimeout(function () {
                            $("#nama").focus();
                        }, 600);
                    }
                } else {
                    alert("Error getting data!")
                }
            }
        });
    }

    $('#form_slider').validate({
        submitHandler: function (form) {
            var myForm = document.getElementById('form_slider');
            $.ajax({
                url: "<?=base_url().$content.'/simpan'?>",
                type: "POST",
                data: new FormData(myForm),
                mimeType: "multipart/form-data",
                contentType: false,
                processData: false,
                success: function (res) {
                    if (res) {
                        $("#modal_slider").modal('hide');
                        load_data(1);
                    } else {
                        alert("Data gagal disimpan!");
                    }
                }
            });
        }
    });

    $('#form_dashboard').validate({
        submitHandler: function (form) {
            var myForm = document.getElementById('form_dashboard');
            $.ajax({
                url: "<?=base_url().$content.'/simpan'?>",
                type: "POST",
                data: new FormData(myForm),
                mimeType: "multipart/form-data",
                contentType: false,
                processData: false,
                success: function (res) {
                    if (res) {
                        $("#modal_dashboard").modal('hide');
                        load_data(1);
                    } else {
                        alert("Data gagal disimpan!");
                    }
                }
            });
        }
    });

    $('#form_item').validate({
        ignore: [],
        rules: {
            nama: {
                required: true
            },
            list: {
                required: true
            }
        },
        //For custom messages
        messages: {
            nama:{
                required: "Nama tidak boleh kosong!"
            },
            list:{
                required: "Produk belum dimasukkan!"
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
            var myForm = document.getElementById('form_item');
            $.ajax({
                url: "<?=base_url().$content.'/simpan'?>",
                type: "POST",
                data: new FormData(myForm),
                mimeType: "multipart/form-data",
                contentType: false,
                processData: false,
                success: function (res) {
                    if (res) {
                        $("#modal_item").modal('hide');
                        load_data(1);
                    } else {
                        alert("Data gagal disimpan!");
                    }
                }
            });
        }
    });

    $('#form_deskripsi').validate({
        submitHandler: function (form) {
            var myForm = document.getElementById('form_deskripsi');
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            $.ajax({
                url: "<?=base_url().$content.'/simpan'?>",
                type: "POST",
                data: new FormData(myForm),
                mimeType: "multipart/form-data",
                contentType: false,
                processData: false,
                success: function (res) {
                    if (res) {
                        $("#modal_text").modal('hide');
                        load_data(1);
                    } else {
                        alert("Data gagal disimpan!");
                    }
                }
            });
        }
    });

    $("#modal_text").on("hide.bs.modal", function () {
        document.getElementById("form_deskripsi").reset();
        $( "#form_deskripsi" ).validate().resetForm();
        CKEDITOR.instances.desc.setData();
    });

    $("#modal_item").on("hide.bs.modal", function () {
        document.getElementById("form_item").reset();
        array_produk = [];
        $("#list_item").html('<tr><td colspan="5" class="text-center"><input type="hidden" name="list" value=""></td></tr>');
    });

    $("#modal_slider").on("hide.bs.modal", function () {
        document.getElementById("form_slider").reset();
    });
</script>