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

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_home_slide" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title"></h4>
            </div>
            <form class="form-horizontal" id="form_home_slide">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <?php $label = 'judul'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Judul</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Judul">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'warna'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Warna Judul</label>
                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control colorpicker" id="<?=$label?>" autocomplete="off" placeholder="Input Warna" />
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'link'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Link</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Link">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'file_upload'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Thumbnail</label>

                            <input type="hidden" id="<?=$label?>ed" name="<?=$label?>ed" />
                            <div class="col-sm-5">
                                <input type="text" readonly="" class="form-control" placeholder="Browse...">
                                <input type="file" id="<?=$label?>" name="<?=$label?>" onchange="return ValidateFileUpload()" accept="image/*">
                                <p class="error" id="alr_<?=$label?>"></p>
                            </div>
                            <div class="col-sm-5">
                                <img style="max-width:250px; max-height:250px;" src="<?=base_url().'assets/images/no_image.png'?>" id="result_image">
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
            }
        });
    }

    function cari(e, val) {
        if (e.keyCode == 13) {
            load_data(1, {search:true, any:val});
        }
    }

    function add() {
        $("#modal_title").text("Tambah Slide Home");
        $("#param").val("add");
        $("#modal_home_slide").modal("show");
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
                    $("#modal_title").text("Edit Slide Home");
                    $("#param").val("edit");
                    $("#id").val(id);
                    $("#judul").val(res.res_data['judul']);
                    $("#link").val(res.res_data['link']);
                    $("#warna").val(res.res_data['warna']);
                    $('#result_image').attr('src', '<?= base_url() ?>' + res.res_data['gambar']);
                    $("#modal_home_slide").modal("show");
                    setTimeout(function () {
                        $("#judul").focus();
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

    $('#form_home_slide').validate({
        /*rules: {
            judul: {
                required: true,
            }
        },
        //For custom messages
        messages: {
            judul:{
                required: "Judul tidak boleh kosong!"
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
        },*/
        submitHandler: function (form) {
            var myForm = document.getElementById('form_home_slide');
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
                        $("#modal_home_slide").modal('hide');
                        load_data(1);
                    } else {
                        alert("Data gagal disimpan!");
                    }
                }
            });
        }
    });

    $("#modal_home_slide").on("hide.bs.modal", function () {
        document.getElementById("form_home_slide").reset();
        $( "#form_home_slide" ).validate().resetForm();
        $('#result_image').attr('src', '<?=base_url().'assets/images/no_image.png'?>');
    });

    function ValidateFileUpload() {
        var fuData = document.getElementById('file_upload');
        var FileUploadPath = fuData.value;
        var valid = 1;
        $("#alr_file_upload").text("");
        //$("#upload").prop("disabled", true);

        if (FileUploadPath == '') {
            //$("#alr_file_upload").text("Please upload an image");
            //alert("Please upload an image");
            //valid = 0;
        } else {
            var Extension = FileUploadPath.substring(FileUploadPath.lastIndexOf('.') + 1).toLowerCase();

            if (Extension == "gif" || Extension == "png" || Extension == "bmp" || Extension == "jpeg" || Extension == "jpg") {
                if (fuData.files && fuData.files[0]) {
                    //var size = fuData.files[0].size;
                    //if(size > (2048 * 1000)){
                    //$("#alr_file_upload").text("Maximum file size exceeds");
                    //valid = 0;
                    //} else {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#result_image').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(fuData.files[0]);
                    //$("#upload").prop("disabled", false);
                    //}
                }
            } else {
                //$("#alr_file_upload").text("Image only allows file types of GIF, PNG, JPG, JPEG and BMP.");
                //valid = 0;
            }
        }
        return valid;
    }
</script>