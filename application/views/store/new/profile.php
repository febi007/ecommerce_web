<style>
    @media screen and (max-width: 900px) {
        table {
            border: 0;
        }

        table caption {
            font-size: 1.3em;
        }

        table thead {
            border: none;
            clip: rect(0 0 0 0);
            height: 1px;
            margin: -1px;
            overflow: hidden;
            padding: 0;
            position: absolute;
            width: 1px;
        }

        table tr {
            border-bottom: 3px solid #ddd;
            display: block;
            margin-bottom: .625em;
        }

        table td {
            border-bottom: 1px solid #ddd;
            display: block;
            font-size: .8em;
            text-align: right;
        }

        table td::before {
            /*
            * aria-label has no advantage, it won't be read inside a table
            content: attr(aria-label);
            */
            content: attr(data-label);
            float: left;
            font-weight: bold;
            text-transform: uppercase;
        }

        table td:last-child {
            border-bottom: 0;
        }
    }

</style>


<!-- Hero Start -->
<section class="bg-profile d-table w-100 bg-primary" style="background: url('<?=base_url()?>assets/frontend/images/account/bg.png') center center;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card public-profile border-0 rounded shadow" style="z-index: 1;">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-lg-2 col-md-3 text-md-left text-center">
                                <img src="<?=base_url()?>assets/frontend/images/client/05.jpg" id="imgUser" class="avatar avatar-large rounded-circle shadow d-block mx-auto" alt="">
                            </div><!--end col-->

                            <div class="col-lg-10 col-md-9">
                                <div class="row align-items-end">
                                    <div class="col-md-7 text-md-left text-center mt-4 mt-sm-0">
                                        <h3 class="title mb-0" id="name_member">Krista Joseph</h3>
                                        <small class="text-muted h6 mr-2"><?= $this->data['account']['ol_code'] ?> <span class="badge badge-pill badge-success"><?= $this->data['account']['poin'] ?></span></small>
                                        <ul class="list-inline mb-0 mt-3">
                                            <li class="list-inline-item mr-2"><a href="javascript:void(0)" class="text-muted" title="Instagram" id="email_member">krista_joseph</a></li>
                                            <li class="list-inline-item"><a href="javascript:void(0)" class="text-muted" title="Linkedin" id="telepon_member">Krista Joseph</a></li>
                                        </ul>
                                    </div><!--end col-->

                                </div><!--end row-->
                            </div><!--end col-->
                        </div><!--end row-->
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--ed container-->
</section><!--end section-->
<!-- Hero End -->

<!-- Start -->
<section class="section mt-60">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-12">
                <div class="sidebar sticky-bar">
                    <ul class="nav nav-pills nav-justified flex-column bg-white rounded shadow p-3 mb-0" id="pills-tab" role="tablist">
                    <li class="nav-item mt-2">
                        <a class="nav-link rounded <?=$this->session->isActiveAddress==false?'active':''?>" id="order-history" data-toggle="pill" href="#orders" role="tab" aria-controls="orders" aria-selected="false">
                            <div class="text-left py-1 px-3">
                                <h6 class="mb-0"><i class="uil uil-list-ul h5 align-middle mr-2 mb-0"></i> Riwayat Pembelian</h6>
                            </div>
                        </a><!--end nav link-->
                    </li><!--end nav item-->
                    <li class="nav-item mt-2">
                        <a class="nav-link <?=$this->session->isActiveAddress==true?'active':''?> rounded" id="addresses" data-toggle="pill" href="#address" role="tab" aria-controls="address" aria-selected="false">
                            <div class="text-left py-1 px-3">
                                <h6 class="mb-0"><i class="uil uil-map-marker h5 align-middle mr-2 mb-0"></i> Alamat</h6>
                            </div>
                        </a><!--end nav link-->
                    </li><!--end nav item-->

                    <li class="nav-item mt-2">
                        <a class="nav-link rounded" id="account-details" data-toggle="pill" href="#account" role="tab" aria-controls="account" aria-selected="false">
                            <div class="text-left py-1 px-3">
                                <h6 class="mb-0"><i class="uil uil-user h5 align-middle mr-2 mb-0"></i> Data Diri</h6>
                            </div>
                        </a><!--end nav link-->
                    </li><!--end nav item-->

                    <li class="nav-item mt-2">
                        <a class="nav-link rounded" href="<?=base_url().'store/logout'?>" aria-selected="false">
                            <div class="text-left py-1 px-3">
                                <h6 class="mb-0"><i class="uil uil-sign-out-alt h5 align-middle mr-2 mb-0"></i> Keluar</h6>
                            </div>
                        </a><!--end nav link-->
                    </li><!--end nav item-->
                </ul><!--end nav pills-->
                </div>
            </div><!--end col-->

            <div class="col-lg-9 col-12">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade bg-white <?=$this->session->isActiveAddress==false?'show active':''?> shadow rounded p-4" id="orders" role="tabpanel" aria-labelledby="order-history">
                        <div class="table-responsive bg-white shadow rounded">
                            <table class="table mb-0 table-center table-nowrap">
                                <thead>
                                <tr>
                                    <th scope="col">No.Pembelian</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">#</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php
                                if($orders != null){
                                    foreach($orders as $key=>$row):
                                        $status='';
                                        $dropdown = '<a class="dropdown-item" href="javascript:" onclick="detail(\'' . $row['id_orders'] . '\')">Detail</a>';
                                        if ($row['status'] == '1'){
                                            if ($row['status_bayar'] == '1') {
                                                $status = '<span class="badge badge-pill badge-warning text-white py-2 px-3">Menunggu Pembayaran</span>';
                                                $dropdown .= '<a class="dropdown-item" href="javascript:" onclick="konfirmasi(\'' . $row['pembayaran'] . '\',\'' . $row['id_orders'] . '\')">Confirm</a>';
                                            }
                                        }
                                        else if ($row['status'] == '2' || $row['status'] == '3'){
                                            $dropdown .= '<a class="dropdown-item" href="javascript:" onclick="lacak(\'' . $row['id_pengiriman'] . '\')">Lacak</a> ';
                                            if ($row['status'] == '3') {
                                                $dropdown .= '<a class="dropdown-item" href="javascript:" onclick="finish(\'' . $row['id_orders'] . '\')">Terima</a>';
                                            }
                                            $status = '<span class="badge badge-pill badge-primary text-white py-2 px-3">Sedang Diproses</span>';
                                        }
                                        else if ($row['status'] == '4'){
                                            $status = '<span class="badge badge-pill badge-success text-white py-2 px-3">Sudah Diterima</span>';
                                        }
                                        else{
                                            $status = '<span class="badge badge-pill badge-danger text-white py-2 px-3">Dibatalkan</span>';
                                        }
                                        $get_ongkir = $this->m_crud->get_data("pengiriman", "biaya", "orders='" . $row['id_orders'] . "'")['biaya'];
                                        ?>
                                        <tr>
                                            <th data-label="Order No" scope="row"><?=$row['id_orders']?></th>
                                            <td data-label="Due Date"><?=time_elapsed_string($row['tgl_orders'])?></td>
                                            <td data-label="Status" class="text-success"><?=$status?></td>
                                            <td data-label="Amount">Rp <?=number_format((float)$row['total']+(float)$row['kode_unik']+(float)$get_ongkir-$row['jumlah_voucher'])?></td>
                                            <td data-label="Action">
                                                <!--                                        <a href="javascript:void(0)" class="text-primary">View <i class="uil uil-arrow-right"></i></a>-->
                                                <div class="dropdown dropdown-primary">
                                                    <a  class="dropdown-toggle text-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">View</a>
                                                    <div class="dropdown-menu dd-menu dropdown-menu-right bg-white shadow rounded border-0 mt-3 py-3" style="width: 200px;">
                                                        <?=$dropdown?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                    <?php endforeach;} ?>

                                </tbody>

                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div><!--end teb pane-->
                    <div class="tab-pane fade bg-white <?=$this->session->isActiveAddress==true?'show active':''?> shadow rounded p-4" id="address" role="tabpanel" aria-labelledby="addresses">
                        <div class="row">
                            <?php foreach($alamat as $row):?>
                            <div class="col-lg-6 mt-4 pt-2">
                                <div class="media align-items-center mb-4 justify-content-between">
                                    <h5 class="mb-0">Jenis Alamat : <?=$row['nama_alamat']?></h5>
                                    <a onclick="deleteAddress('<?=$row["id_alamat_member"]?>')" href="javascript:void(0)" class="text-primary h5 mb-0" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="uil uil-trash align-middle"></i></a>
                                </div>
                                <div class="pt-4 border-top">
                                    <p class="h6"><?=$row['penerima']?></p>
                                    <p class="h6 text-muted"><?=$row['alamat']?>,<?=$row['kecamatan']?>,<?=$row['kota']?>,<?=$row['provinsi']?></p>
                                    <p class="h6 text-muted mb-0"><?=$row['telepon']?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>

                        </div>
                    </div><!--end teb pane-->
                    <div class="tab-pane fade bg-white shadow rounded p-4" id="account" role="tabpanel" aria-labelledby="account-details">
                        <form id="form_member">
                            <input type="hidden" name="id_member" id="id_member" value="<?=$this->session->id_member?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php $field='nama';?>
                                        <label>Nama Lengkap</label>
                                        <div class="position-relative">
                                            <i data-feather="user" class="fea icon-sm icons"></i>
                                            <input name="<?=$field?>" id="<?=$field?>" type="text" class="form-control pl-5">
                                        </div>
                                    </div>
                                </div><!--end col-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php $field='telepon';?>
                                        <label>No.Telepon</label>
                                        <div class="position-relative">
                                            <i data-feather="phone" class="fea icon-sm icons"></i>
                                            <input name="<?=$field?>" id="<?=$field?>" type="number" class="form-control pl-5">
                                        </div>
                                    </div>
                                </div><!--end col-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jenis Kelamin</label>
                                        <?php $field = 'jenis_kelamin'; ?>
                                        <select name="<?=$field?>" id="<?=$field?>" class="form-control">
                                            <option value="">Gender</option>
                                            <option value="L">Male</option>
                                            <option value="P">Female</option>
                                        </select>
                                    </div>
                                </div><!--end col-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php $field = 'tgl_lahir'; ?>
                                        <label>Tanggal Lahir</label>
                                        <input type="date" class="form-control" name="<?=$field?>" id="<?=$field?>" data-inputmask="'alias': 'dd-mm-yyyy'" data-mask>
                                    </div>
                                </div><!--end col-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php $field='foto';?>
                                        <label>Photo</label>
                                        <input name="<?=$field?>" id="<?=$field?>" type="file" class="form-control" accept="image/*">
                                    </div>
                                </div><!--end col-->
                                <div class="col-lg-12 mt-2 mb-0">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div><!--end col-->
                            </div><!--end row-->
                        </form>

                        <h5 class="mt-4">Ubah Kata Sandi :</h5>
                        <form id="form_password">

                            <div class="row mt-3">
                                <div class="col-lg-12">
                                    <?php $field='password_lama';?>
                                    <div class="form-group">
                                        <label>Kata Sandi Lama :</label>
                                        <div class="position-relative">
                                            <i data-feather="key" class="fea icon-sm icons"></i>
                                            <input type="password" class="form-control pl-5" placeholder="" id="<?=$field?>" name="<?=$field?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <?php $field='password';?>
                                        <label>Kata Sandi Baru :</label>
                                        <div class="position-relative">
                                            <i data-feather="key" class="fea icon-sm icons"></i>
                                            <input type="password" class="form-control pl-5" placeholder=""  id="<?=$field?>" name="<?=$field?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <?php $field='password_conf';?>
                                    <div class="form-group">
                                        <label>Ulangi Kata Sandi :</label>
                                        <div class="position-relative">
                                            <i data-feather="key" class="fea icon-sm icons"></i>
                                            <input type="password" class="form-control pl-5" placeholder=""  id="<?=$field?>" name="<?=$field?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 mt-2 mb-0">
                                    <button class="btn btn-primary">Simpan</button>
                                </div><!--end col-->
                            </div><!--end row-->
                        </form>
                    </div><!--end teb pane-->
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
</section><!--end section-->
<!-- End -->

<div class="modal fade" id="modal_detail" tabindex="-1" role="dialog" aria-labelledby="productview-title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-header">
                <h5 class="modal-title">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="container-fluid px-0">
                    <div class="row">
                        <div class="col-md-12" id="content_modal">

                        </div>
                    </div>
                </div><!--end container-->
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_lacak" tabindex="-1" role="dialog" aria-labelledby="productview-title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-header">
                <h5 class="modal-title">Hasil Lacak</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h3 id="expedisi"></h3>
                <div class="table-responsive">
                    <h4>Informasi Pengiriman</h4>
                    <div class="container">
                        <div class="row">
                            <div class="col-4 col-xs-4 col-md-4"><p>No Resi</p></div>
                            <div class="col-8 col-xs-8 col-md-8"><p id="res_resi"></p></div>
                            <div class="col-4 col-xs-4 col-md-4"><p>Status</p></div>
                            <div class="col-8 col-xs-8 col-md-8"><p id="res_status"></p></div>
                            <div class="col-4 col-xs-4 col-md-4"><p>Jenis Layanan</p></div>
                            <div class="col-8 col-xs-8 col-md-8"><p id="res_jenis"></p></div>
                            <div class="col-4 col-xs-4 col-md-4"><p>Tanggal</p></div>
                            <div class="col-8 col-xs-8 col-md-8"><p id="res_tanggal"></p></div>
                            <div class="col-4 col-xs-4 col-md-4"><p>Pengirim</p></div>
                            <div class="col-8 col-xs-8 col-md-8"><p id="res_pengirim"></p></div>
                            <div class="col-4 col-xs-4 col-md-4"><p>Penerima</p></div>
                            <div class="col-8 col-xs-8 col-md-8"><p id="res_penerima"></p></div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <h4>Status Pengiriman</h4>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Keterangan</th>
                        </tr>
                        </thead>
                        <tbody id="status"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="productview-title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-header">
                <h5 class="modal-title">Upload Bukti Transfer</h5>
                <button type="button" class="close"  data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_confirm">
                    <input type="hidden" name="id_orders" id="id_orders">
                    <input type="hidden" name="id_pembayaran" id="id_pembayaran">
                    <div class="form-group-group">
                        <span>Nama Bank</span>
                        <input class="form-control" type="text" name="" id="nama_bank" readonly>
                    </div>
                    <div class="form-group">
                        <span>Nomor Rekening</span>
                        <input class="form-control" type="text" name="" id="nomor_rekening" readonly>
                    </div>
                    <div class="form-group">
                        <span>Atas Nama</span>
                        <input class="form-control" type="text" name="" id="atas_nama" readonly>
                    </div>
                    <div class="form-group">
                        <span>Jumlah Transfer</span>
                        <input class="form-control" type="text" name="" id="jumlah_transfer" readonly>
                    </div>
                    <div class="form-group">
                        <span>Bukti Transfer</span>
                        <input class="form-control" type="file" accept="image/*" name="bukti_transfer" id="customFile">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<script>

    $(document).ready(function(){
        <?php $this->session->unset_userdata('isActiveAddress');?>
        get_member();
    });

    function get_member(){
        $.ajax({
            url: "<?=base_url().'api/get_profile'?>",
            type: "POST",
            data: {id_member: "<?=$this->session->id_member?>"},
            dataType: "JSON",
            beforeSend: function() {$('body').append('<div class="first-loader"><img src="<?=base_url()?>assets/images/spin.svg"></div>');},
            complete: function() {$('.first-loader').remove();},
            success: function (res) {
                console.log(res.res_profile);
                $('#imgUser').attr('src',(res.res_profile.foto!=''?res.res_profile.foto:'<?=base_url()?>assets/images/no_image.png'));
                $("#nama").val(res.res_profile.nama);
                $("#jenis_kelamin").val(res.res_profile.jenis_kelamin);
                $("#tgl_lahir").val(res.res_profile.tgl_lahir);
                $("#telepon").val(res.res_profile.telepon);
                $("#name_member").text(res.res_profile.nama);
                $("#email_member").text(res.res_profile.email);
                $("#telepon_member").text(res.res_profile.telepon);
            }
        });
    }

    function deleteAddress(id) {
        Swal.fire({
            title: "Information !",
            text: "Are you sure ???",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Oke'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "<?=base_url().'api/delete_alamat'?>",
                    type: "POST",
                    data: {id: id},
                    dataType: "JSON",
                    beforeSend: function() {$('body').append('<div class="first-loader"><img src="<?=base_url()?>assets/images/spin.svg"></div>');},
                    complete: function() {$('.first-loader').remove();},
                    success: function (res) {
                        if (res.status) {

                            Swal.fire({
                                title: "Success",
                                text: "Data Has Ben Saved",
                                icon: "success",
                            });
                            setTimeout(function () {
                                location.reload();
                            }, 600);
                        }
                    }
                });
            }
        })
    }


    function finish(id) {
        Swal.fire({
            title: 'Konfirmasi penerimaan',
            text: 'Akan menyelesaikan transaksi?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya'
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: "<?=base_url().'api/finish_order'?>",
                    type: "POST",
                    data: {id_orders: id},
                    dataType: "JSON",
                    success: function (res) {
                        if (res.status) {
                            location.reload();
                        }
                    }
                });
            }
        })
    }

    function lacak(id) {
        $.ajax({
            url: "<?=base_url().'api/lacak_resi'?>",
            type: "POST",
            data: {id_pengiriman: id},
            dataType: "JSON",
            beforeSend: function() {$('body').append('<div class="first-loader"><img src="<?=base_url()?>assets/images/spin.svg"></div>');},
            complete: function() {$('.first-loader').remove();},
            success: function (res) {
                if (res.status) {
                    $("#expedisi").text('Expedisi '+res.summary['courier_name']);
                    $("#res_resi").text(": "+res.details['waybill_number']);
                    $("#res_status").text(": "+res.summary['status']);
                    $("#res_jenis").text(": "+res.summary['service_code']);
                    $("#res_tanggal").text(": "+res.details['waybill_number']);
                    $("#res_pengirim").text(": "+res.details['shippper_name']+' '+res.details['shipper_city']+', '+res.details['shipper_address1']+', '+res.details['shipper_address2']+', '+res.details['shipper_address3']);
                    $("#res_penerima").text(": "+res.details['receiver_name']+' '+res.details['receiver_city']+', '+res.details['receiver_address1']+', '+res.details['receiver_address2']+', '+res.details['receiver_address3']);
                    var informasi = '' +
                        '<tr><td width="130">Nomor Resi</td><td>:</td><td>'+res.details['waybill_number']+'</td></tr>'+
                        '<tr><td width="130">Status</td><td>:</td><td>'+res.summary['status']+'</td></tr>'+
                        '<tr><td width="130">Jenis Layanan</td><td>:</td><td>'+res.summary['service_code']+'</td></tr>'+
                        '<tr><td width="130">Tanggal</td><td>:</td><td>'+res.details['waybill_date']+' '+res.details['waybill_time']+'</td></tr>'+
                        '<tr><td width="130">Pengirim</td><td>:</td><td>'+res.details['shippper_name']+' '+res.details['shipper_city']+', '+res.details['shipper_address1']+', '+res.details['shipper_address2']+', '+res.details['shipper_address3']+'</td></tr>'+
                        '<tr><td width="130">Penerima</td><td>:</td><td>'+res.details['receiver_name']+' '+res.details['receiver_city']+', '+res.details['receiver_address1']+', '+res.details['receiver_address2']+', '+res.details['receiver_address3']+'</td></tr>';
                    $("#informasi").html(informasi);
                    var manifest = res.manifest;
                    var status = '';
                    for (var x=0; x<manifest.length; x++) {
                        status += '' +
                            '<tr><td>'+manifest[x]['manifest_date']+'</td><td>'+manifest[x]['manifest_time']+'</td><td>'+manifest[x]['manifest_description']+'</td></tr>';
                    }
                    $("#status").html(status);
                    $("#modal_lacak").modal('show')
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Oops...',
                        text: 'Nomor resi salah atau belum terdata oleh sistem expedisi'
                    })
                }
            }
        });
    }

    function detail(id) {
        $.ajax({
            url: "<?=base_url().'api/get_detail_orders/'?>" + btoa(id),
            type: "GET",
            dataType: "JSON",
            success: function (res) {
                if (res.status) {
                    $("#content_modal").html(res.res_detail);
                    $("#modal_detail").modal("show");
                }
            }
        });
    }

    function konfirmasi(id,order) {
        $.ajax({
            url: "<?=base_url().'api/prev_confirm'?>",
            data: {id_pembayaran: id},
            type: "POST",
            dataType: "JSON",
            beforeSend: function() {$('body').append('<div class="first-loader"><img src="<?=base_url()?>assets/images/spin.svg"></div>');},
            complete: function() {$('.first-loader').remove();},
            success: function (res) {
                $("#id_orders").val(order);
                if (res.status) {
                    $("#kode_pembayaran").text(id);
                    $("#id_pembayaran").val(id);
                    $("#nama_bank").val(res.res_confirm['bank']);
                    $("#nomor_rekening").val(res.res_confirm['no_rek']);
                    $("#atas_nama").val(res.res_confirm['atas_nama']);
                    $("#jumlah_transfer").val(to_rp(res.res_confirm['total'], '-'));
                    //$("#myModal").show();
                    $("#myModal").modal('show');
                    // document.getElementById('myModal').style.display = "block";
                }
            }
        });
    }

    $('#form_confirm').validate({
        rules: {
            bukti_transfer: {
                required: true
            }
        },
        //For custom messages
        messages: {
            bukti_transfer: {
                required: "Bukti transfer harus di upload!"
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
            var myForm = document.getElementById('form_confirm');
            $.ajax({
                url: "<?=base_url().'api/konfirmasi_pembayaran'?>",
                type: "POST",
                data: new FormData(myForm),
                mimeType: "multipart/form-data",
                contentType: false,
                processData: false,
                dataType: "JSON",
                beforeSend: function() {$('body').append('<div class="first-loader"><img src="<?=base_url()?>assets/images/spin.svg"></div>');},
                complete: function() {$('.first-loader').remove();},
                success: function (res) {
                    if (res.status) {
                        Swal.fire({
                            title: 'Success!',
                            type: 'success'
                        }).then((result) => {
                            if (result.value) {
                                location.reload();
                            }
                        });
                    }
                }
            });
        }
    });

    function hide_modal(param){
        if(param==='lacak'){
            $("#modal_lacak").modal('hide');
        }
        if(param==='bukti'){
            $('#myModal').on('shown.bs.modal', function(e) {
                $("#myModal").modal("hide");
            });
        }
    }


    $('#form_member').validate({
        rules: {
            nama: {
                required: true
            },
            telepon: {
                required: true
            },
            jenis_kelamin: {
                required: true
            },
        },
        //For custom messages
        messages: {
            nama: {
                required: "name cannot be empty"
            },
            telepon: {
                required: "phone cannot be empty"
            },
            jenis_kelamin: {
                required: "gender cannot be empty"
            },
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
            var myForm = document.getElementById('form_member');
            $.ajax({
                url: "<?=base_url().'api/update_profile'?>",
                type: "POST",
                data: new FormData(myForm),
                mimeType: "multipart/form-data",
                contentType: false,
                processData: false,
                dataType: "JSON",
                beforeSend: function() {$('body').append('<div class="first-loader"><img src="<?=base_url()?>assets/images/spin.svg"></div>');},
                complete: function() {$('.first-loader').remove();},
                success: function (res) {
                    if (res.status) {
                        Swal.fire({
                            title: 'Success!',
                            type: 'success',
                            text: "Data Has Been Saved",

                        }).then((result) => {
                            if (result.value) {
                                location.reload();
                            }
                        });
                    }
                    else{
                        alert(res.pesan)
                    }
                }
            });
        }
    });

    $('#form_password').validate({
        rules: {
            password_lama: {
                required: true
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 15
            },
            password_conf: {
                required: true,
                equalTo: "#password"
            },
        },
        //For custom messages
        messages: {
            password_lama: {
                required: "old password cannot be empty"
            },
            password:{
                required: "Password  cannot be empty",
                minlength: "Password must be more than 6 characters!",
                maxlength: "Password cannot be longer than 15 characters"
            },
            password_conf:{
                required: "Confirmation Password  cannot be empty",
                equalTo: "Password does not match!"
            },
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
                url: "<?=base_url().'api/ganti_password'?>",
                type: "POST",
                data: {id_member:$("#id_member").val(),password:$("#password").val(),password_lama:$("#password_lama").val()},
                dataType: "JSON",
                beforeSend: function() {$('body').append('<div class="first-loader"><img src="<?=base_url()?>assets/images/spin.svg"></div>');},
                complete: function() {$('.first-loader').remove();},
                success: function (res) {
                    console.log(res);
                    if (res.status) {
                        Swal.fire({
                            title: 'Success!',
                            type: 'success',
                            text: "Data Has Been Saved",
                        }).then((result) => {
                            if (result.value) {
                                location.reload();
                                window.location.href="<?=base_url().'store/logout'?>";
                            }
                        });
                    }
                    else{
                        alert(res.message)
                    }
                }
            });
        }
    });

</script>

