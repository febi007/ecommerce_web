<style>
    .togglebutton > label:hover {
        color: #0073b7;
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

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_level" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title"></h4>
            </div>
            <form class="form-horizontal" id="form_user">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <?php $label = 'nama'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Nama</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Nama">
                            </div>
                        </div>
                        <?php
                        $access_menu = array(
                            'Pengaturan'=>array( //0-10 / 111-120
                                0=>'Situs',
                                1=>'Tentang Kami',
                                2=>'Cara Belanja',
                                3=>'Syarat & Ketentuan',
                                4=>'Kebijakan Privasi',
                                5=>'Pusat Resolusi',
                                6=>'Sosial Media',
                                7=>'Video Home',
                                111=>'Slide Home',
                                //8=>'Video Share',
                                9=>'Karir',
                                10=>'Home Setting'
                            ), 'Master Data'=>array( //11-30
                                11=>'Data User',
                                12=>'User Level',
                                13=>'Member',
                                14=>'Bank',
                                15=>'Rekening Bank',
                                16=>'Lokasi',
                                17=>'Berita',
                                //18=>'Galeri',
                                //19=>'Testimonial',
                                18=>'Kategori Berita',
                                20=>'Kurir'
                            ), 'Produk'=>array( //31-50
                                31=>'Data Produk',
                                32=>'Group',
                                33=>'Kelompok',
                                34=>'Merk',
                                35=>'Promo',
                                //36=>'Diskusi',
                                //37=>'Ulasan',
                                38=>'Model',
                                39=>'Voucher',
                                40=>'Bestsellers'
                            ), 'Inventory'=>array( //51-70
                                51=>'Data Stok',
                                52=>'Adjustment'
                            ), 'Penjualan'=>array( //71-90
                                71=>'Data Order'
                            ), 'Laporan'=>array( //91-110
                                91=>'Penjualan',
                                92=>'Kritik & Saran'
                            )
                        ); ?>

                        <input type="hidden" id="jumlah" name="jumlah" value="110" />

                        <div class="row">
                            <?php $label = 'akses'; ?>
                            <label class="col-sm-2 control-label">Akses</label>

                            <div class="col-sm-10" style="padding-top: 16px">
                                <?php foreach($access_menu as $row => $value){ ?>
                                    <div class="togglebutton">
                                        <label>
                                            <input type="checkbox" value="1" id="<?=str_replace(' ', '_', $row)?>" name="<?=$row?>"> <?=$row?>
                                        </label>
                                    </div>
                                    <div class="col-lg-12 form-inline" style="margin-bottom: 30px">
                                    <?php foreach($value as $rows => $values){ ?>
                                        <div class="togglebutton col-lg-4">
                                            <label>
                                                <input class="<?=str_replace(' ', '_', $row)?>" type="checkbox" value="1" id="<?=$rows?>" name="<?=$rows?>"> <?=$values?>
                                            </label>
                                        </div>
                                    <?php } ?>
                                    </div>
                                    <script>
                                        $("#<?=str_replace(' ', '_', $row)?>").click(function () {
                                            if ($("#<?=str_replace(' ', '_', $row)?>").is(":checked")) {
                                                $(".<?=str_replace(' ', '_', $row)?>").prop('checked', true);
                                            } else {
                                                $(".<?=str_replace(' ', '_', $row)?>").prop('checked', false);
                                            }
                                        });
                                    </script>
                                <?php } ?>
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
        $("#modal_title").text("Tambah Level");
        $("#param").val("add");
        $("#modal_level").modal("show");
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
                    $("#modal_title").text("Edit Level");
                    $("#param").val("edit");
                    $("#id").val(id);
                    $("#nama").val(res.res_level['nama']);

                    for (var i=0; i<res.res_level['jumlah']; i++) {
                        if (res.res_level['level'].substring(i, i+1) == '1') {
                            $("#" + i).prop("checked", true);
                        }
                    }
                    $("#modal_level").modal("show");
                    setTimeout(function () {
                        $("#nama").focus();
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
                },
                error: function(xhr, status, error) {
                    alert("Data tidak bisa dihapus!");
                    console.log(xhr.responseText);
                }
            });
        }
    }

    $('#form_user').validate({
        rules: {
            nama: {
                required: true,
                remote: {
                    url: "<?=base_url().$content.'/cek_level'?>",
                    type: "post",
                    data: {
                        param: function() {
                            return $("#param").val();
                        }
                    }
                }
            }
        },
        //For custom messages
        messages: {
            nama:{
                required: "Nama tidak boleh kosong!",
                remote: "Nama sudah tersedia!"
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
                url: "<?=base_url().$content.'/simpan'?>",
                type: "POST",
                data: $("#form_user").serialize(),
                beforeSend: function() {
                    $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
                },
                complete: function() {
                    $('.first-loader').remove();
                },
                success: function (res) {
                    if (res) {
                        $("#modal_level").modal('hide');
                        load_data(1);
                    } else {
                        alert("Data gagal disimpan!");
                    }
                }
            });
        }
    });

    $("#modal_level").on("hide.bs.modal", function () {
        document.getElementById("form_user").reset();
        $( "#form_user" ).validate().resetForm();
    });
</script>