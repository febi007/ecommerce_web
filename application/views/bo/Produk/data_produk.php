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

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_detail_produk" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title_detail"></h4>
            </div>
            <form class="form-horizontal" id="form_merk" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Deskripsi Produk</h3>
                                </div>
                                <div id="det_deskripsi"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Varian</h3>
                                </div>
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Ukuran</th>
                                        <th>Warna</th>
                                        <th>Harga Jual Tambahan</th>
                                    </tr>
                                    </thead>
                                    <tbody id="det_varian">
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-6">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Grosir</h3>
                                </div>
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Qty 1</th>
                                        <th>Qty 2</th>
                                        <th>Harga Jual</th>
                                    </tr>
                                    </thead>
                                    <tbody id="det_grosir">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Gambar</h3>
                                </div>
                                <div id="det_gambar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal_data_produk" style="display: none">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title"></h4>
            </div>
            <form class="form-horizontal" id="form_data_produk" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="box-body">
                                <div class="form-group" id="cont_code">
                                    <?php $label = 'code'; ?>
                                    <label for="<?=$label?>" class="col-sm-2 control-label">SKU</label>

                                    <div class="col-sm-10">
                                        <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="SKU">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php $label = 'kelompok'; ?>
                                    <label for="<?=$label?>" class="col-sm-2 control-label">Kelompok</label>

                                    <div class="col-sm-10">
                                        <div class="input-group" style="width: 100%;">
                                            <select id="<?=$label?>" name="<?=$label?>" class="form-control">
                                                <option value="">Pilih Kelompok</option>
                                            </select>
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-primary btn-sm bg-blue" onclick="add_kelompok()"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php $label = 'merk'; ?>
                                    <label for="<?=$label?>" class="col-sm-2 control-label">Merk</label>

                                    <div class="col-sm-10">
                                        <div class="input-group" style="width: 100%;">
                                            <select id="<?=$label?>" name="<?=$label?>" class="form-control">
                                                <option value="">Pilih Merk</option>
                                            </select>
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-primary btn-sm bg-blue" onclick="add_merk()"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php $label = 'nama'; ?>
                                    <label for="<?=$label?>" class="col-sm-2 control-label">Nama</label>

                                    <div class="col-sm-10">
                                        <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Nama Poduk">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php $label = 'deskripsi'; ?>
                                    <label for="<?=$label?>" class="col-sm-2 control-label">Deskripsi</label>

                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="<?=$label?>" id="<?=$label?>" rows="4" placeholder="Deskripsi"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php $label = 'gambar'; ?>
                                    <label for="<?=$label?>" class="col-sm-2 control-label">Gambar</label>

                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <input type="text" readonly="" class="form-control" placeholder="Browse...">
                                            <input type="file" id="<?=$label?>" name="<?=$label?>[]" style="width: 85%" multiple accept="image/*">

                                            <div class="input-group-btn" id="btn_<?=$label?>">
                                                <button type="button" class="btn btn-primary btn-sm bg-blue" onclick="view_gambar()"><i class="fa fa-image"></i></button>
                                            </div>
                                        </div>

                                        <p class="help-block">Gambar akan ditampilkan sebagai menu pada website utama.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <?php $label = 'berat'; ?>
                                <label for="<?=$label?>" class="col-sm-2 control-label">Berat</label>

                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="text" name="<?=$label?>" class="form-control positive-integer" id="<?=$label?>" autocomplete="off" placeholder="Berat">
                                        <span class="input-group-addon">gram</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php $label = 'ukuran'; ?>
                                <label for="<?=$label?>" class="col-sm-2 control-label">Ukuran</label>

                                <div class="col-sm-10">
                                    <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Ukuran">
                                </div>
                            </div>
                            <div class="form-group">
                                <?php $label = 'warna'; ?>
                                <label for="<?=$label?>" class="col-sm-2 control-label">Warna</label>

                                <div class="col-sm-10">
                                    <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Warna">
                                </div>
                            </div>
                            <!--<div class="form-group">
                                <?php /*$label = 'hrg_beli'; */?>
                                <label for="<?/*=$label*/?>" class="col-sm-2 control-label">Harga Beli</label>

                                <div class="col-sm-10">
                                    <input type="text" name="<?/*=$label*/?>" class="form-control currency" id="<?/*=$label*/?>" autocomplete="off" placeholder="Harga Beli">
                                </div>
                            </div>-->
                            <div class="form-group">
                                <?php $label = 'hrg_jual'; ?>
                                <label for="<?=$label?>" class="col-sm-2 control-label">Harga Jual</label>

                                <div class="col-sm-10">
                                    <input type="text" name="<?=$label?>" class="form-control currency" id="<?=$label?>" autocomplete="off" placeholder="Harga Jual">
                                </div>
                            </div>
                            <div class="form-group">
                                <?php $label = 'pre_order'; ?>
                                <label for="<?=$label?>" class="col-sm-2 control-label">Pre Order</label>

                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="text" name="<?=$label?>" class="form-control positive-integer" id="<?=$label?>" autocomplete="off" placeholder="Pre Order">
                                        <span class="input-group-addon">hari</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?php $label = 'free_return'; ?>
                                        <label for="<?=$label?>" class="col-sm-7 control-label">Free Return</label>

                                        <div class="col-sm-5" style="padding-top: 16px">
                                            <div class="togglebutton">
                                                <label>
                                                    <input type="checkbox" value="1" id="<?=$label?>" name="<?=$label?>">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?php $label = 'varian'; ?>
                                        <label for="<?=$label?>" class="col-sm-7 control-label">Varian</label>

                                        <div class="col-sm-5" style="padding-top: 16px">
                                            <div class="togglebutton">
                                                <label>
                                                    <input type="checkbox" value="1" id="<?=$label?>" name="<?=$label?>">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?php $label = 'grosir'; ?>
                                        <label for="<?=$label?>" class="col-sm-7 control-label">Grosir</label>

                                        <div class="col-sm-5" style="padding-top: 16px">
                                            <div class="togglebutton">
                                                <label>
                                                    <input type="checkbox" value="1" id="<?=$label?>" name="<?=$label?>">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6" id="container_varian">
                            <div class="box-header with-border">
                                <h3 class="box-title">Varian</h3>
                            </div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Ukuran</th>
                                    <th>Warna</th>
                                    <th>Harga Jual Tambahan</th>
                                    <th width="1%" class="text-center">#</th>
                                </tr>
                                </thead>
                                <tbody id="list_varian">
                                </tbody>
                                <input type="hidden" id="max_data_varian" name="max_data_varian" value="0">
                            </table>
                        </div>
                        <div class="col-lg-6" id="container_grosir">
                            <div class="box-header with-border">
                                <h3 class="box-title">Grosir</h3>
                            </div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Qty 1</th>
                                    <th>Qty 2</th>
                                    <th>Harga Jual</th>
                                    <th width="1%" class="text-center">#</th>
                                </tr>
                                </thead>
                                <tbody id="list_grosir">
                                </tbody>
                                <input type="hidden" id="max_data_grosir" name="max_data_grosir" value="0">
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

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_kelompok" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title_kelompok"></h4>
            </div>
            <form class="form-horizontal" id="form_kelompok" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <?php $label = 'group'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Group</label>

                            <div class="col-sm-10">
                                <div class="input-group" style="width: 100%;">
                                    <select id="<?=$label?>" name="<?=$label?>" class="form-control">
                                        <option value="">Pilih Group</option>
                                    </select>
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-primary btn-sm bg-blue" onclick="add_group()"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'nama'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Nama Kelompok</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>" autocomplete="off" placeholder="Nama Kelompok">
                            </div>
                        </div>
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
                <input type="hidden" name="param" id="param_kelompok" value="add">
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_group" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title_group"></h4>
            </div>
            <form class="form-horizontal" id="form_group" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <?php $label = 'nama'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Nama Group</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>_group" autocomplete="off" placeholder="Nama Group">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'gambar'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Gambar</label>

                            <div class="col-sm-10">
                                <input type="text" readonly="" class="form-control" placeholder="Browse...">
                                <input type="file" id="<?=$label?>_group" name="<?=$label?>" accept="image/*">

                                <p class="help-block">Gambar akan ditampilkan sebagai menu pada website utama.</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'status'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Status</label>

                            <div class="col-sm-10" style="padding-top: 16px">
                                <div class="togglebutton">
                                    <label>
                                        <input type="checkbox" value="1" id="<?=$label?>_group" name="<?=$label?>" checked>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="simpan" name="simpan">Simpan</button>
                </div>
                <input type="hidden" name="param" id="param_group" value="add">
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_merk" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title_merk"></h4>
            </div>
            <form class="form-horizontal" id="form_merk" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <?php $label = 'nama'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Nama Merk</label>

                            <div class="col-sm-10">
                                <input type="text" name="<?=$label?>" class="form-control" id="<?=$label?>_merk" autocomplete="off" placeholder="Nama Merk">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'gambar'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Gambar</label>

                            <div class="col-sm-10">
                                <input type="text" readonly="" class="form-control" placeholder="Browse...">
                                <input type="file" id="<?=$label?>_merk" name="<?=$label?>" accept="image/*">

                                <p class="help-block">Gambar akan ditampilkan sebagai menu pada website utama.</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $label = 'status'; ?>
                            <label for="<?=$label?>" class="col-sm-2 control-label">Status</label>

                            <div class="col-sm-10" style="padding-top: 16px">
                                <div class="togglebutton">
                                    <label>
                                        <input type="checkbox" value="1" id="<?=$label?>_merk" name="<?=$label?>" checked>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="simpan" name="simpan">Simpan</button>
                </div>
                <input type="hidden" name="param" id="param_merk" value="add">
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modal_gambar" style="display: none">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_title_gambar"></h4>
            </div>
            <form class="form-horizontal" id="form_merk" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="view_gambar"></div>
                            </div>
                        </div>
                    </div>
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
        load_group();
        load_kelompok();
        load_merk();
        document.getElementById("container_varian").style.display = "none";
        document.getElementById("container_grosir").style.display = "none";
    }).on("click", ".pagination li a", function(event){
        event.preventDefault();
        var page = $(this).data("ci-pagination-page");
        load_data(page);
    });

    $("#varian").click(function () {
        if ($(this).is(":checked")) {
            document.getElementById("container_varian").style.display = "block";
        } else {
            document.getElementById("container_varian").style.display = "none";
        }
    });

    var array_varian = [];
    function add_list_varian(id) {
        var new_list = '';
        var max_data_varian = parseInt(document.getElementById("max_data_varian").value);

        if (id == 'edit') {
            for (var x = 0; x < array_varian.length; x++) {
                new_list += '<tr>' +
                    '<td><input type="text" value="' + array_varian[x].ukuran + '" class="form-control" id="u_' + x + '" name="u_' + x + '"></td>' +
                    '<td><input type="text" value="' + array_varian[x].warna + '" class="form-control" id="w_' + x + '" name="w_' + x + '"></td>' +
                    '<td><input type="text" value="' + array_varian[x].harga + '" class="form-control currency" id="hrg_varian_' + x + '" name="hrg_varian_' + x + '"></td>' +
                    '<td><button type="button" id="add_harga_varian_' + x + '" onclick="add_list_varian(' + x + ')" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-plus"></i></button><button type="button" id="remove_harga_varian_' + x + '" onclick="remove_list_varian(' + x + ')" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-close"></i></button></td>' +
                    '</tr>';
                new_list += '<input type="hidden" name="id_varian_' + x + '" id="id_varian_' + x + '" value="' + array_varian[x].id + '">';

                if ($("#param").val() == 'edit' && array_varian[x].id != 'new') {
                    new_list += '<input type="hidden" name="id_edit[]" value="' + array_varian[x].id + '">';
                }

                max_data_varian = x + 1;
            }
        } else if (id != 'new') {
            var u = $("#u_"+id).val();
            var w = $("#w_"+id).val();
            var hrg = hapuskoma($("#hrg_varian_"+id).val());

            if (u != '' && w != '' && hrg != '') {
                var data = {ukuran: u, warna: w, harga: hrg, id: 'new'};
                array_varian.push(data);

                for (var x = 0; x < array_varian.length; x++) {
                    new_list += '<tr>' +
                        '<td><input type="text" value="' + array_varian[x].ukuran + '" class="form-control" id="u_' + x + '" name="u_' + x + '"></td>' +
                        '<td><input type="text" value="' + array_varian[x].warna + '" class="form-control" id="w_' + x + '" name="w_' + x + '"></td>' +
                        '<td><input type="text" value="' + array_varian[x].harga + '" class="form-control currency" id="hrg_varian_' + x + '" name="hrg_varian_' + x + '"></td>' +
                        '<td><button type="button" id="add_harga_varian_' + x + '" onclick="add_list_varian(' + x + ')" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-plus"></i></button><button type="button" id="remove_harga_varian_' + x + '" onclick="remove_list_varian(' + x + ')" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-close"></i></button></td>' +
                        '</tr>';
                    new_list += '<input type="hidden" name="id_varian_' + x + '" id="id_varian_' + x + '" value="' + array_varian[x].id + '">';

                    if ($("#param").val() == 'edit' && array_varian[x].id != 'new') {
                        new_list += '<input type="hidden" name="id_edit[]" value="' + array_varian[x].id + '">';
                    }

                    max_data_varian = x + 1;
                }
            } else {
                return false;
            }
        }

        new_list += '<tr>' +
            '<td><input type="text"  class="form-control" id="u_'+(max_data_varian)+'" name="u_'+(max_data_varian)+'"></td>' +
            '<td><input type="text" class="form-control" id="w_'+(max_data_varian)+'" name="w_'+(max_data_varian)+'"></b></td>' +
            '<td><input type="text" class="form-control currency" id="hrg_varian_'+(max_data_varian)+'" name="hrg_varian_'+(max_data_varian)+'"></td>' +
            '<td><button type="button" id="add_harga_varian_'+(max_data_varian)+'" onclick="add_list_varian('+(max_data_varian)+')" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-plus"></i></button><button type="button" id="remove_harga_varian_'+(max_data_varian)+'" onclick="remove_list_varian()" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-close"></i></button></td>' +
            '</tr>';
        new_list += '<input type="hidden" name="id_varian_' + (max_data_varian) + '" id="id_varian_' + (max_data_varian) + '" value="new">';

        document.getElementById("max_data_varian").value = max_data_varian;
        document.getElementById("list_varian").innerHTML = new_list;

        disable_form_varian(max_data_varian);
    }

    function remove_list_varian(id) {
        var status;
        var id_varian = $("#id_varian_"+id).val();
        if ($("#param").val() == 'edit' && id_varian != 'new') {
            if (confirm("Akan menghapus varian?")) {
                $.ajax({
                    url: "<?=base_url() . $content . '/cek_varian'?>",
                    type: "POST",
                    data: {id: id_varian},
                    async: false,
                    beforeSend: function() {
                        $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
                    },
                    complete: function() {
                        $('.first-loader').remove();
                    },
                    success: function (res) {
                        if (!res) {
                            alert("Data sudah ditransaksikan!")
                        }
                        status = res;
                    }
                });
            }
        } else {
            status = true;
        }

        if (status) {
            var new_list = '';
            var max_data_varian = parseInt(document.getElementById("max_data_varian").value);

            array_varian.splice(id, 1);

            for (var x = 0; x < array_varian.length; x++) {
                new_list += '<tr>' +
                    '<td><input type="text" value="' + array_varian[x].ukuran + '" class="form-control" id="u_' + x + '" name="u_' + x + '"></td>' +
                    '<td><input type="text" value="' + array_varian[x].warna + '" class="form-control" id="w_' + x + '" name="w_' + x + '"></td>' +
                    '<td><input type="text" value="' + array_varian[x].harga + '" class="form-control currency" id="hrg_varian_' + x + '" name="hrg_varian_' + x + '"></td>' +
                    '<td><button type="button" id="add_harga_varian_' + x + '" onclick="add_list_varian(' + x + ')" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-plus"></i></button><button type="button" id="remove_harga_varian_' + x + '" onclick="remove_list_varian(' + x + ')" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-close"></i></button></td>' +
                    '</tr>';
                new_list += '<input type="hidden" name="id_varian_' + x + '" id="id_varian_' + x + '" value="' + array_varian[x].id + '">';

                if ($("#param").val() == 'edit' && array_varian[x].id != 'new') {
                    new_list += '<input type="hidden" name="id_edit[]" value="' + array_varian[x].id + '">';
                }

                max_data_varian = x + 1;
            }

            new_list += '<tr>' +
                '<td><input type="text"  class="form-control" id="u_' + (max_data_varian) + '" name="u_' + (max_data_varian) + '"></td>' +
                '<td><input type="text" class="form-control" id="w_' + (max_data_varian) + '" name="w_' + (max_data_varian) + '"></b></td>' +
                '<td><input type="text" class="form-control currency" id="hrg_varian_' + (max_data_varian) + '" name="hrg_varian_' + (max_data_varian) + '"></td>' +
                '<td><button type="button" id="add_harga_varian_' + (max_data_varian) + '" onclick="add_list_varian(' + (max_data_varian) + ')" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-plus"></i></button><button type="button" id="remove_harga_varian_' + (max_data_varian) + '" onclick="remove_list_varian()" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-close"></i></button></td>' +
                '</tr>';
            new_list += '<input type="hidden" name="id_varian_' + (max_data_varian) + '" id="id_varian_' + (max_data_varian) + '" value="new">';

            document.getElementById("max_data_varian").value = max_data_varian;
            document.getElementById("list_varian").innerHTML = new_list;

            disable_form_varian(max_data_varian);
        }
    }

    function disable_form_varian(id) {
        var x = 0;
        for (x; x<id; x++) {
            $("#remove_harga_varian_"+x).show();
            $("#add_harga_varian_"+x).hide();
            /*$("#u_"+x).prop('readonly', true);
            $("#w_"+x).prop('readonly', true);
            $("#hrg_varian_"+x).prop('readonly', true);*/
        }

        $("#remove_harga_varian_"+id).hide();
        $('.currency').autoNumeric('init');
    }


    $("#grosir").click(function () {
        if ($(this).is(":checked")) {
            document.getElementById("container_grosir").style.display = "block";
        } else {
            document.getElementById("container_grosir").style.display = "none";
        }
    });

    var array_harga = [];
    function add_list_grosir(id) {
        var new_list = '';
        var qty_before = 2;
        var max_data_grosir = parseInt(document.getElementById("max_data_grosir").value);

        if (id == 'edit') {
            for (var x = 0; x < array_harga.length; x++) {
                new_list += '<tr>' +
                    '<td><input type="text" value="' + array_harga[x].qty_1 + '" class="form-control" id="q1_' + x + '" name="q1_' + x + '"></td>' +
                    '<td><input type="text" value="' + array_harga[x].qty_2 + '" class="form-control" id="q2_' + x + '" name="q2_' + x + '"></td>' +
                    '<td><input type="text" value="' + array_harga[x].harga + '" class="form-control currency" id="hrg_' + x + '" name="hrg_' + x + '"></td>' +
                    '<td><button type="button" id="add_harga_' + x + '" onclick="add_list_grosir(' + x + ')" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-plus"></i></button><button type="button" id="remove_harga_' + x + '" onclick="remove_list_grosir(' + x + ')" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-close"></i></button></td>' +
                    '</tr>';

                max_data_grosir = x + 1;
            }

            qty_before = parseInt(array_harga[x - 1].qty_2) + 1;
        } else if (id != 'new') {
            var q1 = $("#q1_"+id).val();
            var q2 = $("#q2_"+id).val();
            var hrg = hapuskoma($("#hrg_"+id).val());

            if (q1 != '' && q2 != '' && hrg != '') {
                var data = {qty_1: q1, qty_2: q2, harga: hrg};
                array_harga.push(data);

                for (var x = 0; x < array_harga.length; x++) {
                    new_list += '<tr>' +
                        '<td><input type="text" value="' + array_harga[x].qty_1 + '" class="form-control" id="q1_' + x + '" name="q1_' + x + '"></td>' +
                        '<td><input type="text" value="' + array_harga[x].qty_2 + '" class="form-control" id="q2_' + x + '" name="q2_' + x + '"></td>' +
                        '<td><input type="text" value="' + array_harga[x].harga + '" class="form-control currency" id="hrg_' + x + '" name="hrg_' + x + '"></td>' +
                        '<td><button type="button" id="add_harga_' + x + '" onclick="add_list_grosir(' + x + ')" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-plus"></i></button><button type="button" id="remove_harga_' + x + '" onclick="remove_list_grosir(' + x + ')" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-close"></i></button></td>' +
                        '</tr>';

                    max_data_grosir = x + 1;
                }

                qty_before = parseInt(array_harga[x - 1].qty_2) + 1;
            } else {
                return false;
            }
        }

        new_list += '<tr>' +
            '<td><input type="text" value="'+qty_before+'" class="form-control positive-integer" onkeyup="valid_qty_grosir('+(max_data_grosir)+')" id="q1_'+(max_data_grosir)+'" name="q1_'+(max_data_grosir)+'"><b class="error" id="ntf_dari"></b></td>' +
            '<td><input type="text" class="form-control positive-integer" onkeyup="valid_qty_grosir('+(max_data_grosir)+')" id="q2_'+(max_data_grosir)+'" name="q2_'+(max_data_grosir)+'"><b class="error" id="ntf_sampai"></b></td>' +
            '<td><input type="text" class="form-control currency" id="hrg_'+(max_data_grosir)+'" name="hrg_'+(max_data_grosir)+'"></td>' +
            '<td><button type="button" id="add_harga_'+(max_data_grosir)+'" onclick="add_list_grosir('+(max_data_grosir)+')" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-plus"></i></button><button type="button" id="remove_harga_'+(max_data_grosir)+'" onclick="remove_list_grosir()" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-close"></i></button></td>' +
            '</tr>';

        document.getElementById("max_data_grosir").value = max_data_grosir;
        document.getElementById("list_grosir").innerHTML = new_list;

        disable_form_grosir(max_data_grosir);
    }

    function remove_list_grosir(id) {
        var new_list = '';
        var max_data_grosir = 0;

        array_harga.splice(id, 1);

        for (var x = 0; x < array_harga.length; x++) {
            new_list += '<tr>' +
                '<td><input type="text" value="' + array_harga[x].qty_1 + '" class="form-control" id="q1_' + x + '" name="q1_' + x + '"></td>' +
                '<td><input type="text" value="' + array_harga[x].qty_2 + '" class="form-control" id="q2_' + x + '" name="q2_' + x + '"></td>' +
                '<td><input type="text" value="' + array_harga[x].harga + '" class="form-control" id="hrg_' + x + '" name="hrg_' + x + '"></td>' +
                '<td><button type="button" id="add_harga_' + x + '" onclick="add_list_grosir(' + x + ')" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-plus"></i></button><button type="button" id="remove_harga_' + x + '" onclick="remove_list_grosir(' + x + ')" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-close"></i></button></td>' +
                '</tr>';

            max_data_grosir = x+1;
        }

        if (x > 0) {
            qty_before = parseInt(array_harga[x - 1].qty_2) + 1;
        } else {
            qty_before = 2;
        }

        new_list += '<tr>' +
            '<td><input type="text" value="'+qty_before+'" class="form-control positive-integer" onkeyup="valid_qty_grosir('+(max_data_grosir)+')" id="q1_'+(max_data_grosir)+'" name="q1_'+(max_data_grosir)+'"><b class="error" id="ntf_dari"></b></td>' +
            '<td><input type="text" class="form-control positive-integer" onkeyup="valid_qty_grosir('+(max_data_grosir)+')" id="q2_'+(max_data_grosir)+'" name="q2_'+(max_data_grosir)+'"><b class="error" id="ntf_sampai"></b></td>' +
            '<td><input type="text" class="form-control currency" id="hrg_'+(max_data_grosir)+'" name="hrg_'+(max_data_grosir)+'"></td>' +
            '<td><button type="button" id="add_harga_'+(max_data_grosir)+'" onclick="add_list_grosir('+(max_data_grosir)+')" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-plus"></i></button><button type="button" id="remove_harga_'+(max_data_grosir)+'" onclick="remove_list_grosir()" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-close"></i></button></td>' +
            '</tr>';

        document.getElementById("max_data_grosir").value = max_data_grosir;
        document.getElementById("list_grosir").innerHTML = new_list;

        disable_form_grosir(max_data_grosir);
    }

    function valid_qty_grosir(id) {
        var dari = parseInt($("#q1_"+id).val());
        var sampai = parseInt($("#q2_"+id).val());
        var status = 1;

        if (isNaN(sampai)) {
            $("#ntf_sampai").text("Qty 2 harus lebih dari 0!");
            $("#save").prop('disabled', true);
            $("#add_harga_"+id).prop('disabled', true);
            status = status * 0;
        } else if (sampai < dari) {
            $("#ntf_sampai").text("Qty 2 harus lebih besar dari Qty 1!");
            $("#save").prop('disabled', true);
            $("#add_harga_"+id).prop('disabled', true);
            status = status * 0;
        } else {
            hide_notif('sampai');
        }

        if (isNaN(dari)) {
            $("#ntf_dari").text("Qty 1 harus lebih dari 0!");
            $("#save").prop('disabled', true);
            $("#add_harga_"+id).prop('disabled', true);
            status = status * 0;
        } else if (dari < 2) {
            $("#ntf_dari").text("Qty 1 minimal 2 pcs!");
            $("#save").prop('disabled', true);
            $("#add_harga_"+id).prop('disabled', true);
            status = status * 0;
        } else {
            hide_notif('dari');
        }

        if (status == 1) {
            $("#save").prop('disabled', false);
            $("#add_harga_"+id).prop('disabled', false);
        }
    }

    function disable_form_grosir(id) {
        var x = 0;
        var y = 0;
        for (x; x<id; x++) {
            $("#remove_harga_"+x).hide();
            $("#add_harga_"+x).hide();
            $("#q1_"+x).prop('readonly', true);
            $("#q2_"+x).prop('readonly', true);
            /*$("#hrg_"+x).prop('readonly', true);*/
            y = x;
        }
        if (id != 0) {
            $("#q1_"+id).prop('readonly', true);
        } else {
            $("#q1_"+id).prop('readonly', false);
        }

        $("#remove_harga_"+y).show();
        $("#remove_harga_"+id).hide();
        $(".positive-integer").numeric({ decimal: false, negative: false }, function() { alert("Positive integers only"); this.value = ""; this.focus(); });
        $('.currency').autoNumeric('init');
    }

    function load_group() {
        $.ajax({
            url: "<?=base_url().$content.'/get_group'?>",
            type: "GET",
            dataType: "JSON",
            beforeSend: function() {
                $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
            },
            complete: function() {
                $('.first-loader').remove();
            },
            success: function (res) {
                if (res.status) {
                    $("#group").html(res.group);
                } else {
                    alert("Error getting data!")
                }
            }
        });
    }

    function load_kelompok() {
        $.ajax({
            url: "<?=base_url().$content.'/get_kelompok'?>",
            type: "GET",
            dataType: "JSON",
            beforeSend: function() {
                $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
            },
            complete: function() {
                $('.first-loader').remove();
            },
            success: function (res) {
                if (res.status) {
                    $("#kelompok").html(res.kelompok);
                } else {
                    alert("Error getting data!")
                }
            }
        });
    }

    function load_merk() {
        $.ajax({
            url: "<?=base_url().$content.'/get_merk'?>",
            type: "GET",
            dataType: "JSON",
            beforeSend: function() {
                $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
            },
            complete: function() {
                $('.first-loader').remove();
            },
            success: function (res) {
                if (res.status) {
                    $("#merk").html(res.merk);
                } else {
                    alert("Error getting data!")
                }
            }
        });
    }

    function load_gambar(id_) {
        $.ajax({
            url: "<?=base_url().$content.'/get_gambar'?>",
            type: "POST",
            data: {id: id_},
            beforeSend: function() {
                $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
            },
            complete: function() {
                $('.first-loader').remove();
            },
            success: function (res) {
                $("#view_gambar").html(res);
            }
        });
    }

    function load_data(page,data={}) {
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
        $("#modal_title").text("Tambah Produk");
        $("#param").val("add");
        $("#cont_code").hide();
        add_list_varian('new');
        add_list_grosir('new');
        document.getElementById("container_varian").style.display = "none";
        document.getElementById("container_grosir").style.display = "none";
        $("#modal_data_produk").modal("show");
        setTimeout(function () {
            $("#group").focus();
        }, 600);
    }

    function add_group() {
        $("#modal_title_group").text("Tambah Group");
        $("#modal_group").modal("show");
        setTimeout(function () {
            $("#nama_group").focus();
        }, 600);
    }

    function add_kelompok() {
        $("#modal_title_kelompok").text("Tambah Kelompok");
        $("#modal_kelompok").modal("show");
        setTimeout(function () {
            $("#group").focus();
        }, 600);
    }

    function add_merk() {
        $("#modal_title_merk").text("Tambah Merk");
        $("#modal_merk").modal("show");
        setTimeout(function () {
            $("#nama_merk").focus();
        }, 600);
    }

    function view_gambar() {
        var param = $("#param").val();
        if (param == 'edit') {
            $("#modal_title_gambar").text("Gambar Produk Saat Ini");
            $("#modal_gambar").modal('show');
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
                $("#modal_title_detail").text("Detail Produk");
                $("#det_varian").html(res.varian);
                $("#det_grosir").html(res.grosir);
                $("#det_gambar").html(res.gambar);
                $("#det_deskripsi").html(res.deskripsi);
                $("#modal_detail_produk").modal("show");
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
                    array_varian = [];
                    array_harga = [];
                    var x;
                    $("#modal_title").text("Edit Produk");
                    $("#param").val("edit");
                    $("#id").val(id);
                    $("#kelompok").val(res.res_produk['kelompok']).change();
                    $("#merk").val(res.res_produk['merk']).change();
                    $("#nama").val(res.res_produk['nama']);
                    $("#cont_code").show();
                    $("#code").val(res.res_produk['code']);
                    $("#deskripsi").val(res.res_produk['deskripsi']);
                    $("#berat").val(res.res_produk['berat']);
                    $("#ukuran").val(res.res_produk['ukuran']);
                    $("#warna").val(res.res_produk['warna']);
                    $("#hrg_jual").autoNumeric('set', res.res_produk['hrg_jual']);
                    $("#pre_order").val(res.res_produk['pre_order']);
                    if (res.res_produk['free_return']=='1') {
                        $("#free_return").prop("checked", true);
                    }
                    if (res.status_varian) {
                        document.getElementById("container_varian").style.display = "block";
                        $("#varian").prop("checked", true);
                        for(x=0; x<res.res_varian.length; x++) {
                            var data_varian = {ukuran: res.res_varian[x]['ukuran'], warna: res.res_varian[x]['warna'], harga: res.res_varian[x]['hrg_jual'], id: res.res_varian[x]['id_det_produk']};
                            array_varian.push(data_varian);
                        }
                        add_list_varian('edit');
                    } else {
                        add_list_varian('new');
                        document.getElementById("container_varian").style.display = "none";
                    }
                    if (res.status_grosir) {
                        document.getElementById("container_grosir").style.display = "block";
                        $("#grosir").prop("checked", true);
                        for(x=0; x<res.res_grosir.length; x++) {
                            var data_grosir = {qty_1: res.res_grosir[x]['qty1'], qty_2: res.res_grosir[x]['qty2'], harga: res.res_grosir[x]['hrg_jual']};
                            array_harga.push(data_grosir);
                        }
                        add_list_grosir('edit');
                    } else {
                        add_list_grosir('new');
                        document.getElementById("container_grosir").style.display = "none";
                    }

                    load_gambar(id);

                    $("#modal_data_produk").modal("show");
                    setTimeout(function () {
                        $("#kelompok").focus();
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
                        load_data(<?=$this->session->search['page']?>);
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

    function hapus_gambar(id_) {
        if (confirm("Akan menghapus gambar?")) {
            $.ajax({
                url: "<?=base_url().$content.'/hapus_gambar'?>",
                type: "POST",
                data: {id: id_},
                beforeSend: function() {
                    $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
                },
                complete: function() {
                    $('.first-loader').remove();
                },
                success: function () {
                    load_gambar($("#id").val());
                }
            });
        }
    }

    $('#form_data_produk').validate({
        rules: {
            kelompok: {
                required: true
            },
            merk: {
                required: true
            },
            nama: {
                required: true
            },
            deskripsi: {
                required: true
            },
            berat: {
                required: true
            },
            ukuran: {
                required: true
            },
            warna: {
                required: true
            },
            /*hrg_beli: {
                required: true
            },*/
            hrg_jual: {
                required: true
            }
        },
        //For custom messages
        messages: {
            kelompok: {
                required: "Kelompok harus dipilih!"
            },
            merk: {
                required: "Merk harus dipilih!"
            },
            nama: {
                required: "Nama tidak boleh kosong!"
            },
            deskripsi: {
                required: "Deskripsi tidak boleh kosong!"
            },
            berat: {
                required: "Berat tidak boleh kosong!"
            },
            ukuran: {
                required: "Ukuran tidak boleh kosong!"
            },
            warna: {
                required: "Warna tidak boleh kosong!"
            },
            /*hrg_beli: {
                required: "Harga beli tidak boleh kosong!"
            },*/
            hrg_jual: {
                required: "Harga jual tidak boleh kosong!"
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
            var myForm = document.getElementById('form_data_produk');
            $.ajax({
                url: "<?=base_url().$content.'/simpan'?>",
                type: "POST",
                data: new FormData(myForm),
                mimeType: "multipart/form-data",
                contentType: false,
                processData: false,
                success: function (res) {
                    if (res) {
                        $("#modal_data_produk").modal('hide');
                        load_data(<?=$this->session->search['page']?>);
                    } else {
                        alert("Data gagal disimpan!");
                    }
                }
            });
        }
    });

    $('#form_kelompok').validate({
        rules: {
            nama: {
                required: true,
                remote: {
                    url: "<?=base_url().'Produk/kelompok/cek_nama'?>",
                    type: "post",
                    data: {
                        param: function() {
                            return $("#param_kelompok").val();
                        },
                        group: function() {
                            return $("#group").val();
                        }
                    }
                }
            },
            group: {
                required: true,
                remote: {
                    url: "<?=base_url().'Produk/kelompok/cek_nama'?>",
                    type: "post",
                    data: {
                        param: function() {
                            return $("#param_kelompok").val();
                        },
                        nama: function() {
                            return $("#nama").val();
                        }
                    }
                }
            }
        },
        //For custom messages
        messages: {
            nama:{
                required: "Nama kelompok tidak boleh kosong!",
                remote: "Nama kelompok sudah tersedia!"
            },
            group:{
                required: "Group harus dipilih!",
                remote: "Nama kelompok sudah tersedia!"
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
            var myForm = document.getElementById('form_kelompok');
            $.ajax({
                url: "<?=base_url().'Produk/kelompok/simpan'?>",
                type: "POST",
                data: new FormData(myForm),
                mimeType: "multipart/form-data",
                contentType: false,
                processData: false,
                success: function (res) {
                    if (res) {
                        $("#modal_kelompok").modal('hide');
                        load_kelompok();
                    } else {
                        alert("Data gagal disimpan!");
                    }
                }
            });
        }
    });

    $('#form_group').validate({
        rules: {
            nama: {
                required: true,
                remote: {
                    url: "<?=base_url().'Produk/group/cek_nama'?>",
                    type: "post",
                    data: {
                        param: function() {
                            return $("#param_group").val();
                        }
                    }
                }
            }
        },
        //For custom messages
        messages: {
            nama:{
                required: "Nama group tidak boleh kosong!",
                remote: "Nama group sudah tersedia!"
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
            var myForm = document.getElementById('form_group');
            $.ajax({
                url: "<?=base_url().'Produk/group/simpan'?>",
                type: "POST",
                data: new FormData(myForm),
                mimeType: "multipart/form-data",
                contentType: false,
                processData: false,
                success: function (res) {
                    if (res) {
                        $("#modal_group").modal('hide');
                        load_group();
                    } else {
                        alert("Data gagal disimpan!");
                    }
                }
            });
        }
    });

    $('#form_merk').validate({
        rules: {
            nama: {
                required: true,
                remote: {
                    url: "<?=base_url().'Produk/merk/cek_nama'?>",
                    type: "post",
                    data: {
                        param: function() {
                            return $("#param_merk").val();
                        }
                    }
                }
            }
        },
        //For custom messages
        messages: {
            nama:{
                required: "Nama merk tidak boleh kosong!",
                remote: "Nama merk sudah tersedia!"
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
            var myForm = document.getElementById('form_merk');
            $.ajax({
                url: "<?=base_url().'Produk/merk/simpan'?>",
                type: "POST",
                data: new FormData(myForm),
                mimeType: "multipart/form-data",
                contentType: false,
                processData: false,
                success: function (res) {
                    if (res) {
                        $("#modal_merk").modal('hide');
                        load_merk();
                    } else {
                        alert("Data gagal disimpan!");
                    }
                }
            });
        }
    });

    $("#modal_data_produk").on("hide.bs.modal", function () {
        document.getElementById("form_data_produk").reset();
        $( "#form_data_produk" ).validate().resetForm();
    });

    $("#modal_kelompok").on("hide.bs.modal", function () {
        document.getElementById("form_kelompok").reset();
        $( "#form_kelompok" ).validate().resetForm();
    });

    $("#modal_group").on("hide.bs.modal", function () {
        document.getElementById("form_group").reset();
        $( "#form_group" ).validate().resetForm();
    });

    $("#modal_merk").on("hide.bs.modal", function () {
        document.getElementById("form_merk").reset();
        $( "#form_merk" ).validate().resetForm();
    });
</script>
