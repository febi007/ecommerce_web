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
<input type="hidden" name="page" id="page">

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_member" style="display: none">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title"></h4>
            </div>
            <form class="form-horizontal" id="form_member">
                <div class="modal-body">
                    <div class="box-body">
                         <div class="form-group">
                            <?php $label = 'id_member'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">ID Member</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="id Member">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'nama'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Nama Member</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Nama Member">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'jk'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Jenis Kelamin</label>

                            <div class="col-sm-10">
                                <select id="<?=$label?>" name="<?=$label?>" class="form-control">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L">Laki-Laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'tgl_lahir'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Tanggal Lahir</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control daterangesingle" id="<?=$label?>" autocomplete="off" placeholder="Tanggal Lahir">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'telepon'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">No HP</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control positive-integer" id="<?=$label?>" autocomplete="off" placeholder="No HP">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'email'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Email</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'password'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Password</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'alamat'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Alamat</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Alamat">
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

    function add() {
        $("#modal_title").text("Tambah Member");
        $("#param").val("add");
        $("#modal_member").modal("show");
        setTimeout(function () {
            $("#nama").focus();
        }, 600);
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
                $("#user_level").html(data.user_level);
                $("#page").val(data.page);
            }
        });
    }

    function cari(e, val) {
        if (e.keyCode == 13) {
            load_data(1, {search:true, any:val});
        }
    }

    function update(data, id) {
        $.ajax({
            url:"<?=base_url().$content.'/update';?>",
            type:"POST",
            data: {data: data, id: id},
            dataType:"JSON",
            beforeSend: function() {
                $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
            },
            complete: function() {
                $('.first-loader').remove();
            },
            success:function(res)
            {
                if (res.status) {
                    load_data($("#page").val());
                }
            }
        });
    }

    function hapus(id) {
        swal({
            title: 'Akan menghapus data?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "<?=base_url().$content.'/delete';?>",
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
                            load_data($("#page").val());
                        } else {
                            swal({
                                title: "Data gagal dihapus",
                                type: "error"
                            });
                        }
                    }
                })
            }
        })
    }

    $('#form_member').validate({
        rules: {
            nama: {
                required: true
            },
            jk: {
                required: true
            },
            tgl_lahir: {
                required: true
            },
            alamat: {
                required: true
            },
            email: {
                required: true,
                email: true,
                remote: {
                    url: "<?=base_url().$content.'/cek_email'?>",
                    type: "post"
                }
            },
            telepon: {
                required: true,
                remote: {
                    url: "<?=base_url().$content.'/cek_telepon'?>",
                    type: "post"
                }
            }
        },
        //For custom messages
        messages: {
            nama:{
                required: "Nama tidak boleh kosong!"
            },
            jk:{
                required: "Jenis kelamin tidak boleh kosong!"
            },
            tgl_lahir:{
                required: "Tanggal lahir tidak boleh kosong!"
            },
            alamat:{
                required: "Alamat tidak boleh kosong!"
            },
            email:{
                required: "Email tidak boleh kosong!",
                email: "Email tidak valid!",
                remote: "Email sudah terdaftar!"
            },
            telepon:{
                required: "No telepon tidak boleh kosong!",
                remote: "No telepon sudah terdaftar!"
            }
        },
        errorElement : 'div',
        errorPlacement: function(error, element) {
            var placement = $(element).data('error');
            var type = $(element).attr("type");
            if (placement) {
                $(placement).append(error)
            } else {
                if (type === "radio") {
                    error.insertAfter(".errorRadio");
                } else {
                    error.insertAfter(element);
                }
            }
        },
        submitHandler: function (form) {
            $.ajax({
                url: "<?=base_url().$content.'/simpan'?>",
                type: "POST",
                dataType: "JSON",
                data: $("#form_member").serialize(),
                beforeSend: function() {
                    $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
                },
                complete: function() {
                    $('.first-loader').remove();
                },
                success: function (res) {
                    var result = res.res_register;
                    if (res.status) {
                        $("#modal_member").modal('hide');
                        document.getElementById("form_member").reset();
                        $( "#form_member" ).validate().resetForm();
                        swal({
                            type: 'success',
                            title: 'Data berhasil disimpan'
                        })
                    } else {
                        swal({
                            type: 'error',
                            title: 'Data gagal disimpan',
                            text: result['message']
                        })
                    }
                }
            });
        }
    });
</script>