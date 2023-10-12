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
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-primary bg-blue" onclick="edit_navbar()"><i class="fa fa-edit"></i> Navbar</button>
                                <button type="button" class="btn btn-primary bg-blue" onclick="edit_cs()"><i class="fa fa-edit"></i> CS</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive" id="result_table"></div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_situs" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title"></h4>
            </div>
            <form class="form-horizontal" id="form_situs">
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
                            <?php $label = 'logo'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Logo</label>

                            <div class="col-sm-10">
                                <input type="text" readonly="" class="form-control" placeholder="Browse...">
                                <input type="file" id="<?=$label?>" name="<?=$label?>" accept="image/*">
                            </div>
                        </div>
                        <div class="form-group" id="img_preview_logo">
                            <label class="col-sm-2 control-label">Gambar Sekarang</label>

                            <div class="col-sm-10">
                                <img class="img_preview" id="preview_logo">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'icon'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Icon</label>

                            <div class="col-sm-10">
                                <input type="text" readonly="" class="form-control" placeholder="Browse...">
                                <input type="file" id="<?=$label?>" name="<?=$label?>" accept="image/*">
                            </div>
                        </div>
                        <div class="form-group" id="img_preview_icon">
                            <label class="col-sm-2 control-label">Gambar Sekarang</label>

                            <div class="col-sm-10">
                                <img class="img_preview" id="preview_icon">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'versi'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Versi</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Versi">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'website'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Website</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Website">
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

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_navbar" style="display: none">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title"></h4>
            </div>
            <form class="form-horizontal" id="form_navbar">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <?php $label = '1'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Navbar 1</label>

                            <div class="col-sm-10">
                                <select id="<?=$label?>" name="<?=$label?>" class="form-control">
                                    <option value="">Pilih Group</option>
                                    <?php
                                    foreach ($groups as $row) {
                                        echo '<option value="'.$row['id_groups'].'">'.$row['nama'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = '2'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Navbar 2</label>

                            <div class="col-sm-10">
                                <select id="<?=$label?>" name="<?=$label?>" class="form-control">
                                    <option value="">Pilih Group</option>
                                    <?php
                                    foreach ($groups as $row) {
                                        echo '<option value="'.$row['id_groups'].'">'.$row['nama'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = '3'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Navbar 3</label>

                            <div class="col-sm-10">
                                <select id="<?=$label?>" name="<?=$label?>" class="form-control">
                                    <option value="">Pilih Group</option>
                                    <?php
                                    foreach ($groups as $row) {
                                        echo '<option value="'.$row['id_groups'].'">'.$row['nama'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = '4'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Navbar 4</label>

                            <div class="col-sm-10">
                                <select id="<?=$label?>" name="<?=$label?>" class="form-control">
                                    <option value="">Pilih Group</option>
                                    <?php
                                    foreach ($groups as $row) {
                                        echo '<option value="'.$row['id_groups'].'">'.$row['nama'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = '5'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Navbar 5</label>

                            <div class="col-sm-10">
                                <select id="<?=$label?>" name="<?=$label?>" class="form-control">
                                    <option value="">Pilih Group</option>
                                    <?php
                                    foreach ($groups as $row) {
                                        echo '<option value="'.$row['id_groups'].'">'.$row['nama'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = '6'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Navbar 6</label>

                            <div class="col-sm-10">
                                <select id="<?=$label?>" name="<?=$label?>" class="form-control">
                                    <option value="">Pilih Group</option>
                                    <?php
                                    foreach ($groups as $row) {
                                        echo '<option value="'.$row['id_groups'].'">'.$row['nama'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = '7'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Navbar 7</label>

                            <div class="col-sm-10">
                                <select id="<?=$label?>" name="<?=$label?>" class="form-control">
                                    <option value="">Pilih Group</option>
                                    <?php
                                    foreach ($groups as $row) {
                                        echo '<option value="'.$row['id_groups'].'">'.$row['nama'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="simpan" name="simpan">Simpan</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_cs" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title"></h4>
            </div>
            <form class="form-horizontal" id="form_cs">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <?php $label = 'open'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Hari kerja</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Hari kerja">
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
                            <?php $label = 'tlp'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Telepon</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control positive-integer" id="<?=$label?>" autocomplete="off" placeholder="Telepon">
                            </div>
                        </div>
                        <div class="form-group form-inline">
                            <label for="<?=$label?>" class="col-sm-2 control-label">Jam Operasional</label>
                            <div class="form-group col-sm-5" style="margin: 0">
                                <?php $label = 'time_open'; ?>
                                <label for="<?=$label?>" class="col-sm-4 control-label">Jam Buka</label>

                                <div class="col-sm-6">
                                    <input type="text" name="<?=$label?>" data-autoclose="true" class="form-control clockpicker" id="<?=$label?>" autocomplete="off" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-5" style="margin: 0">
                                <?php $label = 'time_close'; ?>
                                <label for="<?=$label?>" class="col-sm-4 control-label">Jam Tutup</label>

                                <div class="col-sm-6">
                                    <input type="text" name="<?=$label?>" data-autoclose="true" class="form-control clockpicker" id="<?=$label?>" autocomplete="off" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="simpan" name="simpan">Simpan</button>
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
            success:function(data)
            {
                $('#result_table').html(data.result_table);
            }
        });
    }

    function edit(id) {
        $.ajax({
            url: "<?=base_url().$content.'/edit'?>",
            type: "POST",
            data: {id: id},
            dataType: "JSON",
            success: function (res) {
                if (res.status) {
                    $("#modal_title").text("Edit Situs");
                    $("#param").val("edit");
                    $("#id").val(id);
                    $("#nama").val(res.res_data['nama']);
                    $("#versi").val(res.res_data['versi']);
                    $("#website").val(res.res_data['web']);
                    $("#preview_logo").attr("src", "<?=base_url()?>"+res.res_data['logo']);
                    document.getElementById("img_preview_logo").style.display = 'block';
                    $("#preview_icon").attr("src", "<?=base_url()?>"+res.res_data['icon']);
                    document.getElementById("img_preview_icon").style.display = 'block';
                    $("#modal_situs").modal("show");
                    setTimeout(function () {
                        $("#nama").focus();
                    }, 600);
                } else {
                    alert("Error getting data!")
                }
            }
        });
    }

    function edit_navbar() {
        $.ajax({
            url: "<?=base_url() . $content . '/edit_navbar'?>",
            type: "GET",
            dataType: "JSON",
            success: function (res) {
                var navbar = res.res_data;
                for (var i=0; i<navbar.length; i++) {
                    $("#"+navbar[i]['id_navbar']).val(navbar[i]['groups']).change();
                    $("#modal_navbar").modal('show');
                }
            }
        });
    }

    function edit_cs() {
        $.ajax({
            url: "<?=base_url() . $content . '/edit_cs'?>",
            type: "GET",
            dataType: "JSON",
            success: function (res) {
                $("#open").val(res.res_data['open']);
                $("#tlp").val(res.res_data['tlp']);
                $("#email").val(res.res_data['email']);
                $("#time_open").val(res.res_data['time_open']);
                $("#time_close").val(res.res_data['time_close']);
                $("#modal_cs").modal('show');
            }
        });
    }

    $('#form_situs').validate({
        rules: {
            nama: {
                required: true
            },
            versi: {
                required: true
            },
            website: {
                required: true
            }
        },
        //For custom messages
        messages: {
            nama:{
                required: "Nama tidak boleh kosong!"
            },
            versi:{
                required: "Versi tidak boleh kosong!"
            },
            website:{
                required: "Website tidak boleh kosong!"
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
            var myForm = document.getElementById('form_situs');
            $.ajax({
                url: "<?=base_url().$content.'/simpan'?>",
                type: "POST",
                data: new FormData(myForm),
                mimeType: "multipart/form-data",
                contentType: false,
                processData: false,
                success: function (res) {
                    if (res) {
                        $("#modal_situs").modal('hide');
                        location.reload();
                    } else {
                        alert("Data gagal disimpan!");
                    }
                }
            });
        }
    });

    $('#form_navbar').validate({
        rules: {
            1: {
                required: true
            },
            2: {
                required: true
            },
            3: {
                required: true
            },
            4: {
                required: true
            },
            5: {
                required: true
            },
            6: {
                required: true
            },
            7: {
                required: true
            }
        },
        //For custom messages
        messages: {
            1: {
                required: "Groups harus dipilih"
            },
            2: {
                required: "Groups harus dipilih"
            },
            3: {
                required: "Groups harus dipilih"
            },
            4: {
                required: "Groups harus dipilih"
            },
            5: {
                required: "Groups harus dipilih"
            },
            6: {
                required: "Groups harus dipilih"
            },
            7: {
                required: "Groups harus dipilih"
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
                url: "<?=base_url().$content.'/simpan_navbar'?>",
                type: "POST",
                data: $("#form_navbar").serialize(),
                success: function (res) {
                    if (res) {
                        $("#modal_navbar").modal('hide');
                    } else {
                        alert("Data gagal disimpan!");
                    }
                }
            });
        }
    });

    $('#form_cs').validate({
        rules: {
            open: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            tlp: {
                required: true
            }
        },
        //For custom messages
        messages: {
            open: {
                required: "Jam kerja tidak boleh kosong"
            },
            email: {
                required: "Email tidak boleh kosong"
            },
            tlp: {
                required: "Telepon tidak boleh kosong"
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
                url: "<?=base_url().$content.'/simpan_cs'?>",
                type: "POST",
                data: $("#form_cs").serialize(),
                success: function (res) {
                    if (res) {
                        $("#modal_cs").modal('hide');
                    } else {
                        alert("Data gagal disimpan!");
                    }
                }
            });
        }
    });

    $("#modal_situs").on("hide.bs.modal", function () {
        document.getElementById("form_situs").reset();
        $( "#form_situs" ).validate().resetForm();
    });

    $("#modal_cs").on("hide.bs.modal", function () {
        document.getElementById("form_cs").reset();
        $( "#form_cs" ).validate().resetForm();
    });
</script>