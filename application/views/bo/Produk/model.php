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

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_detail_model" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title_detail"></h4>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="form-group">
                                <?php $label = 'd_nama'; ?>
                                <label for="<?=$label?>" class="col-sm-2 control-label">Nama</label>

                                <div class="col-sm-10">
                                    <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                <?php $label = 'd_gambar'; ?>
                                <label for="<?=$label?>" class="col-sm-2 control-label">Gambar</label>

                                <div class="col-sm-10">
                                    <img class="img_preview" id="<?=$label?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="box-header with-border"></div>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Nama</th>
                                <th>Berat (gram)</th>
                                <th>Harga</th>
                            </tr>
                            </thead>
                            <tbody id="list_det_model"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_model" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title"></h4>
            </div>
            <form class="form-horizontal" id="form_model" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="form-group">
                                    <?php $label = 'nama'; ?>
                                    <label for="<?=$label?>" class="col-sm-2 control-label">Nama Model</label>

                                    <div class="col-sm-10">
                                        <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Nama Model">
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
                                <tbody id="list_model">
                                <tr><td colspan="5" class="text-center"><input type="hidden" name="list" value=""></td></tr>
                                </tbody>
                            </table>
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
    function load_produk() {
        var list_produk = '';
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
    }

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
        $("#list_model").html(list_produk);
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

        $("#list_model").html(list_produk);
        $("#cari").val('').focus();
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
        $("#modal_title").text("Tambah Model");
        $("#param").val("add");
        array_produk = [];
        add_produk('new');
        $("#modal_model").modal("show");
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
                    $("#modal_title").text("Edit Model");
                    $("#param").val("edit");
                    $("#id").val(id);
                    $("#nama").val(res.res_model['nama']);
                    $("#preview").attr("src", "<?=base_url()?>"+res.res_model['gambar']);
                    document.getElementById("img_preview").style.display = 'block';
                    for(x=0; x<res.det_model.length; x++) {
                        var data_produk = {
                            id_produk: res.det_model[x]['id_produk'],
                            sku: res.det_model[x]['code'],
                            nama: res.det_model[x]['nama'],
                            harga: res.det_model[x]['hrg_jual'],
                            berat: res.det_model[x]['berat']
                        };
                        array_produk.push(data_produk);
                    }
                    add_produk('edit');
                    $("#modal_model").modal("show");
                    setTimeout(function () {
                        $("#promo").focus();
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
                $("#modal_title_detail").text("Detail Promo");
                $("#d_nama").val(res.res_model['nama']);
                $("#d_gambar").attr("src", "<?=base_url()?>"+res.res_model['gambar']);
                $("#list_det_model").html(res.det_model);
                $("#modal_detail_model").modal("show");
            }
        });
    }

    $('#form_model').validate({
        ignore: [],
        rules: {
            nama: {
                required: true
            }
        },
        //For custom messages
        messages: {
            nama:{
                required: "Nama model tidak boleh kosong!"
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
            var myForm = document.getElementById('form_model');
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
                        $("#modal_model").modal('hide');
                        load_data(1);
                    } else {
                        alert("Data gagal disimpan!");
                    }
                }
            });
        }
    });

    $("#modal_model").on("hide.bs.modal", function () {
        document.getElementById("form_model").reset();
        $( "#form_model" ).validate().resetForm();
    });
</script>
