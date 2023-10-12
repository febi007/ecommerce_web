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
                                <button type="button" class="btn btn-primary bg-blue" onclick="edit('1111')"><i class="fa fa-edit"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <div class="embed-responsive embed-responsive-16by9" id="result_table">

                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_form" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title"></h4>
            </div>
            <form class="" id="form_input">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <?php $label = 'note'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Note Video</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" id="<?=$label?>" class="form-control" placeholder="Note Video">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'note2'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Sub Note Video</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" id="<?=$label?>" class="form-control" placeholder="Sub Note Video">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'video'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Video</label>

                            <div class="col-sm-10">
                                <input type="text" readonly="" class="form-control" placeholder="Browse...">
                                <input type="file" id="<?=$label?>" name="<?=$label?>" accept="video/mp4">

                                <p class="help-block">Video akan ditampilkan pada website utama.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="simpan" name="simpan">Simpan</button>
                </div>
                <input type="hidden" name="old_video" id="old_video" value="add">
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
                $('#result_table').html(data.result_data);
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
                    $("#modal_title").text("Edit <?=$title?>");
                    $("#param").val("edit");
                    $("#id").val(id);
                    $("#note").val(res.res_data['note']);
                    $("#note2").val(res.res_data['note2']);
                    $("#old_video").val(res.res_data['video']);
					$("#modal_form").modal("show");
                } else {
                    alert("Error getting data!")
                }
            }
        });
    }
	
	$('#form_input').validate({
		submitHandler: function (form) {
			var myForm = document.getElementById('form_input');
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
						$("#modal_form").modal('hide');
						load_data(1);
					} else {
						alert("Data gagal disimpan!");
					}
				}
			});
		}
	});

    $("#modal_form").on("hide.bs.modal", function () {
        document.getElementById("form_input").reset();
        $( "#form_input" ).validate().resetForm();
    });
</script>