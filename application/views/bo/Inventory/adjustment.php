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
                        <div class="col-md-4">
                            <h3 class="box-title"><?=$title?></h3>
                        </div>

                        <div class="col-md-8">
                            <div class="box-tools">
                                <div class="form-inline pull-right">
                                    <div class="input-group input-group-sm">
                                        <input type="text" id="table_date" class="form-control daterange2" readonly>
                                    </div>
                                    <div class="input-group input-group-sm">
                                        <input type="text" id="table_search" class="form-control" onkeyup="return cari(event)" value="<?=isset($this->session->search['any'])?$this->session->search['any']:''?>" placeholder="Cari">
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-primary bg-blue" onclick="cari(event)"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-primary bg-blue" onclick="add()"><i class="fa fa-plus"></i></button>
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

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_detail_adjustment" style="display: none">
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
                        <div class="col-lg-6">
                            <div class="form-group">
                                <?php $label = 'd_id'; ?>
                                <label for="<?=$label?>" class="col-sm-2 control-label">Kode Transaksi</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control pull-right" name="<?=$label?>" id="<?=$label?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php $label = 'd_tanggal'; ?>
                                <label for="<?=$label?>" class="col-sm-2 control-label">Tanggal</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control pull-right" name="<?=$label?>" id="<?=$label?>" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <?php $label = 'd_keterangan'; ?>
                                <label for="<?=$label?>" class="col-sm-2 control-label">Keterangan</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="<?=$label?>" id="<?=$label?>" readonly>
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
                                <th>Ukuran</th>
                                <th>Warna</th>
                                <th>Jenis</th>
                                <th>Qty</th>
                            </tr>
                            </thead>
                            <tbody id="list_det_adjustment"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_adjustment" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title"></h4>
            </div>
            <form class="form-horizontal" id="form_adjustment" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <?php $label = 'tanggal'; ?>
                                    <label for="<?=$label?>" class="col-sm-2 control-label">Periode</label>

                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <input type="text" class="form-control pull-right daterangesingle" name="<?=$label?>" id="<?=$label?>" placeholder="Periode" readonly>
                                            <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <?php $label = 'keterangan'; ?>
                                    <label for="<?=$label?>" class="col-sm-2 control-label">Keterangan</label>

                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="<?=$label?>" id="<?=$label?>" placeholder="Keterangan" autocomplete="off">
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
                                    <th>Ukuran</th>
                                    <th>Warna</th>
                                    <th>Jenis</th>
                                    <th>Stok</th>
                                    <th>Qty</th>
                                    <th>Saldo</th>
                                </tr>
                                </thead>
                                <tbody id="list_adjustment">
                                <tr><td colspan="9" class="text-center"><input type="hidden" name="list" value=""></td></tr>
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

<?php
isset($this->session->search['date'])?$date=$this->session->search['date']:$date='null';
?>
<script>
    $(document).ready(function(){
        if ('<?=$date?>' != 'null') {
            set_date('<?=$date?>', 'daterange2');
        }
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
            console.log(suggestion);
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
            list_produk += '<tr><td colspan="9" class="text-center"><input type="hidden" name="list" value=""></td></tr>';
        } else {
            var qty = res['qty']; var jenis = res['jenis']; var saldo = 0;
            if (jenis == 'tambah') {
                saldo = parseInt( res['stok_produk']) + parseInt(qty);
            } else {
                saldo = parseInt( res['stok_produk']) - parseInt(qty);
            }
            var status = true;
            var data_produk = {
                id_produk: res['id_produk'],
                id_det_produk: res['id_det_produk'],
                sku: res['code'],
                nama: res['nama'],
                ukuran: res['ukuran'],
                warna: res['warna'],
                stok: res['stok_produk'],
                stok_produk: res['stok_produk'],
                saldo: saldo,
                jenis: jenis,
                qty: qty
            };
            if (array_produk.length > 0) {
                for (var i = 0; i < array_produk.length; i++) {
                    if (array_produk[i].id_det_produk === res['id_det_produk']) {
                        status = false;
                    }
                }

            }
            if (status && res!='edit') {
                array_produk.push(data_produk);
            }

            for (var x = 0; x < array_produk.length; x++) {
                console.log(array_produk[x]);

                list_produk += '<tr>' +
                    '<td><button type="button" onclick="remove_produk(' + x + ')" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-close"></i></button></td>' +
                    '<td>' + array_produk[x]['sku'] + '</td>' +
                    '<td>' + array_produk[x]['nama'] + '</td>' +
                    '<td>' + array_produk[x]['ukuran'] + '</td>' +
                    '<td>' + array_produk[x]['warna'] + '</td>' +
                    '<td><select class="form-control" id="jenis_'+x+'" name="jenis_'+x+'" onchange="hitung(\''+x+'\')"><option value="tambah" '+(array_produk[x]['jenis']=='tambah'?'selected':'')+'>Tambah</option><option value="kurang" '+(array_produk[x]['jenis']=='kurang'?'selected':'')+'>Kurang</option></select></td>' +
                    '<td><input style="width: 50px" type="text" class="form-control" value="' + array_produk[x]['stok'] + '" id="stok_'+x+'" readonly></td>' +
                    '<td><input style="width: 50px" type="text" class="form-control positive-integer" id="qty_'+x+'" name="qty_'+x+'" value="' + array_produk[x]['qty'] + '" onkeyup="hitung(\''+x+'\')" autocomplete="off"></td>' +
                    '<td><input style="width: 50px" type="text" class="form-control" value="' + array_produk[x]['saldo'] + '" id="saldo_'+x+'" readonly></td>' +
                    '<td><input type="hidden" style="width: 50px" type="text" class="form-control" value="' + array_produk[x]['id_produk'] + '" id="id_produk_'+x+'" name="id_produk_'+x+'"></td>' +
                    '<td><input type="hidden" style="width: 50px" type="text" class="form-control" value="' + array_produk[x]['stok_produk'] + '" id="stok_produk_'+x+'" name="stok_produk_'+x+'"></td>' +
                    '</tr>';
                list_produk += '<input type="hidden" name="id_det_produk[]" value="' + array_produk[x]['id_det_produk'] + '">';
            }


            $("#cari").val('').focus();
        }
        $("#list_adjustment").html(list_produk);
        $(".positive-integer").numeric({ decimal: false, negative: false }, function() { alert("Positive integers only"); this.value = ""; this.focus(); });
        if (array_produk.length > 0) {
            for (x = 0; x < array_produk.length; x++) {
                $("#qty_"+x).rules("add", {
                    required: true,
                    min: 1,
                    messages: {
                        required: "Qty tidak boleh kosong!",
                        min: "Qty harus lebih dari 0!"
                    }
                });
            }
        }
    }

    function remove_produk(id) {
        var list_produk = '';
        array_produk.splice(id, 1);
        for (var x = 0; x < array_produk.length; x++) {
            list_produk += '<tr>' +
                '<td><button type="button" onclick="remove_produk(' + x + ')" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-close"></i></button></td>' +
                '<td>' + array_produk[x]['sku'] + '</td>' +
                '<td>' + array_produk[x]['nama'] + '</td>' +
                '<td>' + array_produk[x]['ukuran'] + '</td>' +
                '<td>' + array_produk[x]['warna'] + '</td>' +
                '<td><select class="form-control" id="jenis_'+x+'" name="jenis_'+x+'" onchange="hitung(\''+x+'\')"><option value="tambah" '+(array_produk[x]['jenis']=='tambah'?'selected':'')+'>Tambah</option><option value="kurang" '+(array_produk[x]['jenis']=='kurang'?'selected':'')+'>Kurang</option></select></td>' +
                '<td><input style="width: 50px" type="text" class="form-control" value="' + array_produk[x]['stok'] + '" id="stok_'+x+'" readonly></td>' +
                '<td><input style="width: 50px" type="text" class="form-control positive-integer" id="qty_'+x+'" name="qty_'+x+'" value="' + array_produk[x]['qty'] + '" onkeyup="hitung(\''+x+'\')" autocomplete="off"></td>' +
                '<td><input style="width: 50px" type="text" class="form-control" value="' + array_produk[x]['saldo'] + '" id="saldo_'+x+'" readonly></td>' +
                '</tr>';
            list_produk += '<input type="hidden" name="id_det_produk[]" value="'+array_produk[x]['id_det_produk']+'">';
        }

        if (array_produk.length == 0) {
            list_produk += '<tr><td colspan="9" class="text-center"><input type="hidden" name="list" value=""></td></tr>';
        }

        $("#list_adjustment").html(list_produk);
        $("#cari").val('').focus();
        $(".positive-integer").numeric({ decimal: false, negative: false }, function() { alert("Positive integers only"); this.value = ""; this.focus(); });
    }

    function hitung(id) {
        var hitung; var stok = $("#stok_"+id).val(); var saldo = $("#saldo_"+id); var qty = $("#qty_"+id).val(); var jenis = $("#jenis_"+id).val();

        if (qty == '') {
            qty = 0;
        }

        if (jenis == 'tambah') {
            hitung = parseInt(stok)+parseInt(qty);
        } else {
            hitung = parseInt(stok)-parseInt(qty);
        }

        array_produk[id]['jenis'] = jenis;
        array_produk[id]['qty'] = qty;
        array_produk[id]['saldo'] = hitung;
        saldo.val(hitung);
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
        if (e.keyCode == 13 || e.button == 0) {
            load_data(1, {search:true, any:$("#table_search").val(), date:$("#table_date").val()});
        }
    }

    function add() {
        $("#modal_title").text("Tambah Adjustment");
        $("#param").val("add");
        array_produk = [];
        add_produk('new');
        $("#modal_adjustment").modal("show");
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
            success: function (res) {
                if (res.status) {
                    $("#modal_title").text("Edit Adjustment");
                    $("#param").val("edit");
                    $("#id").val(id);
                    $("#keterangan").val(res.res_adjustment['keterangan']);
                    set_date(res.res_adjustment['tgl_adjustment'], 'daterangesingle');
                    $("#diskon").val(res.res_adjustment['diskon']);
                    for(x=0; x<res.det_adjustment.length; x++) {
                        add_produk(res.det_adjustment[x]);
                    }
                    add_produk('edit');
                    $("#modal_adjustment").modal("show");
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

    function detail(id) {
        $.ajax({
            url: "<?=base_url().$content.'/detail'?>",
            type: "POST",
            data: {id: id},
            dataType: "JSON",
            success: function (res) {
                $("#modal_title_detail").text("Detail Adjustment");
                $("#d_id").val(res.res_adjustment['id_adjustment']);
                $("#d_tanggal").val(res.res_adjustment['tgl_adjustment']);
                $("#d_keterangan").val(res.res_adjustment['keterangan']);
                $("#list_det_adjustment").html(res.det_adjustment);
                $("#modal_detail_adjustment").modal("show");
            }
        });
    }

    $('#form_adjustment').validate({
        ignore: [],
        rules: {
            tanggal: {
                required: true
            },
            keterangan: {
                required: true
            },
            list: {
                required: true
            }
        },
        //For custom messages
        messages: {
            tanggal:{
                required: "Tanggal harus dipilih!"
            },
            keterangan:{
                required: "Keterangan tidak boleh kosong!"
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
            var myForm = document.getElementById('form_adjustment');
            $.ajax({
                url: "<?=base_url().$content.'/simpan'?>",
                type: "POST",
                data: new FormData(myForm),
                mimeType: "multipart/form-data",
                contentType: false,
                processData: false,
                success: function (res) {
                    console.log(res);
                    if (res) {
                        $("#modal_adjustment").modal('hide');
                        load_data(1);
                    } else {
                        alert("Data gagal disimpan!");
                    }
                }
            });
        }
    });

    $("#modal_adjustment").on("hide.bs.modal", function () {
        document.getElementById("form_adjustment").reset();
        $( "#form_adjustment" ).validate().resetForm();
    });
</script>
