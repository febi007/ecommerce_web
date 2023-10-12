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
                            <div class="box-tools pull-right">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body" id="result_table" 				style="padding:20px">
				    <form class="form" id="form_bestsellers" enctype="multipart/form-data">
							<div class="form-group">
								<?php $label = 'harga_cod'; ?>
								<label style="color:black">Harga COD</label>
								<input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Harga COD">
							</div>
					<div style="float:right !important">
					<button type="submit" class="btn btn-primary" id="simpan" name="simpan">Simpan</button>
				    <input type="hidden" name="param" id="param" value="add">
				    <input type="hidden" name="id" id="id">
					</div>
					</form>
				</div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>


<script>
	// $("#modal_bestsellers").modal("show");
	$(document).ready(function(){
		// $("#modal_title").text("Tambah Promo");
		$("#param").val("add");
		// $("#modal_bestsellers").modal("show");
		load_data();
	});

	function load_data(){
		$.ajax({
			url : "<?=base_url().$content.'/load_data'?>",
			type:"POST",
			dataType:"JSON",
			success:function(res){
				console.log(res);
				$("#harga_cod").val(res.harga_cod)
			}
		})
	}

	$('#form_bestsellers').validate({
		rules: {
			harga_cod: {
				required: true
			}
		},
		//For custom messages
		messages: {
			harga_cod:{
				required: "harga harus diisi!"
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
			var myForm = document.getElementById('form_bestsellers');
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
					$("#modal_bestsellers").modal('hide');
					load_data(1);
				}
			});
		}
	});

	$("#modal_bestsellers").on("hide.bs.modal", function () {
		document.getElementById("form_bestsellers").reset();
		$( "#form_bestsellers" ).validate().resetForm();
	});
</script>
