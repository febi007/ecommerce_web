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

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_user" style="display: none">
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
                        <div class="form-group">
                            <?php $label = 'username'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Username</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Username">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'password'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Password</label>

                            <div class="col-sm-10">
                                <input type="password" name="<?=$label?>" class="form-control" id="<?=$label?>" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'cpassword'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Ulangi Password</label>

                            <div class="col-sm-10">
                                <input type="password" name="<?=$label?>" class="form-control" id="<?=$label?>" placeholder="Ulangi Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'user_level'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">User Level</label>

                            <div class="col-sm-10">
                                <select id="<?=$label?>" name="<?=$label?>" class="form-control">
                                    <option value="">Pilih Level</option>
                                </select>
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
        $("#modal_title").text("Tambah User");
        $("#param").val("add");
        $("#modal_user").modal("show");
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
            beforeSend: function() {
                $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
            },
            complete: function() {
                $('.first-loader').remove();
            },
            success: function (res) {
                if (res.status) {
                    $("#modal_title").text("Edit User");
                    $("#param").val("edit");
                    $("#id").val(id);
                    $("#nama").val(res.res_user['nama']);
                    $("#username").val(res.res_user['username']);
                    if (res.res_user['status']=='0') {
                        $("#status").prop("checked", false);
                    }
                    $("#user_level").val(res.res_user['user_level']).change();
                    $("#modal_user").modal("show");
                    setTimeout(function () {
                        $("#nama").focus();
                    }, 600);
                } else {
                    alert("Error getting data!")
                }
            }
        });
    }

    $('#form_user').validate({
        rules: {
            nama: {
                required: true
            },
            username: {
                required: true,
                minlength: 5,
                remote: {
                    url: "<?=base_url().$content.'/cek_username'?>",
                    type: "post",
                    data: {
                        param: function() {
                            return $("#param").val();
                        }
                    }
                }
            },
            password: {
                required: function() {
                    return $("#param").val() == 'add';
                },
                minlength: 6
            },
            cpassword: {
                required: function() {
                    return $("#param").val() == 'add';
                },
                equalTo: "#password"
            },
            user_level: {
                required: true
            }
        },
        //For custom messages
        messages: {
            nama:{
                required: "Nama tidak boleh kosong!"
            },
            username:{
                required: "Username tidak boleh kosong!",
                minlength: "Username harus lebih dari 5 karakter!",
                remote: "Username sudah tersedia!"
            },
            password:{
                required: "Password tidak boleh kosong!",
                minlength: "Password harus lebih dari 6 karakter!"
            },
            cpassword:{
                required: "Password tidak boleh kosong!",
                equalTo: "Password tidak sesuai!"
            },
            user_level: "User level harus dipilih!"
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
                beforeSend: function() {
                    $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
                },
                complete: function() {
                    $('.first-loader').remove();
                },
                success: function (res) {
                    if (res) {
                        $("#modal_user").modal('hide');
                        load_data(1);
                    } else {
                        alert("Data gagal disimpan!");
                    }
                }
            });
        }
    });

    $("#modal_user").on("hide.bs.modal", function () {
        document.getElementById("form_user").reset();
        $( "#form_user" ).validate().resetForm();
    });
</script>