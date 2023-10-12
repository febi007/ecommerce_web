<?php
/**
 * Created by PhpStorm.
 * User: annashrul_yusuf
 * Date: 10/12/2020
 * Time: 7:26
 */
//$mobile=$this->agent->is_mobile();
//if(!$mobile):
?>
<!-- Hero Start -->
<section class="bg-profile d-table w-100 bg-primary" style="background: url('<?=base_url()?>assets/frontend/images/account/bg.png') center center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 text-center">
                <div class="page-next-level">
                    <h4 class="title" style="color:white"> Pembayaran </h4>
                    <div class="page-next">
                        <nav aria-label="breadcrumb" class="d-inline-block">
                            <ul class="breadcrumb bg-white rounded shadow mb-0">
                                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?= $this->data['site']->nama ?></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Pembayaran</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div> <!--end container-->
</section><!--end section-->
<div class="position-relative">
    <div class="shape overflow-hidden text-white">
        <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor"></path>
        </svg>
    </div>
</div>
<!-- Hero End -->

<!-- Start -->
<section class="section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="rounded shadow-lg p-4">
                    <form class="row"  id="form_checkout">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 style="color: #2f55d4 !important">Data Alamat</h4>
                                    <div class="row">
                                        <div class="col-md-3 form-group">
                                            <label for="">Pilih Alamat Anda</label>
                                            <select class="form-control"  name="ch_alamat_jual" onchange="ubah_alamat()" id="ch_alamat_jual" required>
                                                <option value="baru">Buat Alamat Baru</option>
                                                <?php
                                                if(count($alamat) > 0) {
                                                    foreach ($alamat as $row) {echo '<option value="'.$row['id_alamat_member'].'">'.$row['nama'].'</option>';}
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 form-group" id="new_address">
                                            <?php $field = 'nama_alamat'; ?>
                                            <label for="<?=$field?>">Jenis Alamat</label>
                                            <input type="text" class="form-control" id="<?=$field?>" name="<?=$field?>" required />
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <?php $field = 'nama_penerima'; ?>
                                            <label for="<?=$field?>">Nama Penerima</label>
                                            <input type="text" class="form-control" id="<?=$field?>" name="<?=$field?>" required />
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <?php $field = 'telepon'; ?>
                                            <label for="<?=$field?>">No Telepon</label>
                                            <input type="text" class="form-control" id="<?=$field?>" name="<?=$field?>" />
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <?php $field = 'kota'; ?>
                                            <label for="<?=$field?>">Kota</label>
                                            <input type="text" class="form-control" id="<?=$field?>" name="<?=$field?>" />
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <?php $field = 'kecamatan'; ?>
                                            <label for="<?=$field?>">Kecamatan</label>
                                            <input type="text" class="form-control" id="<?=$field?>" name="<?=$field?>" />
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <?php $field = 'provinsi'; ?>
                                            <label for="<?=$field?>">Provinsi</label>
                                            <input type="text" class="form-control" id="<?=$field?>" name="<?=$field?>" />
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-md-12 form-group">
                                                    <?php $field = 'alamat'; ?>
                                                    <label for="<?=$field?>">Detail Alamat</label>
                                                    <Textarea class="form-control" id="<?=$field?>" name="<?=$field?>" placeholder="Masukan alamat selengkap mungkin untuk mempercepat proses pengiriman"></Textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <h4 style="color:  #2f55d4 !important">Jasa Pengiriman</h4>
                                    <div class="row">
                                        <div class="col-md-3 form-group p_star">
                                            <label for="">Kurir</label>
                                            <select class="form-control" name="kurir" id="kurir" onchange="get_ongkir()">
                                                <option value="">Pilih Kurir</option>
                                                <?php
                                                if(count($kurir) > 0) {
                                                    foreach ($kurir as $row) {
                                                        echo '<option value="'.$row['id_kurir'].'">'.$row['kurir'].'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label for="">Layanan</label>
                                            <select class="form-control" name="layanan" id="layanan" onchange="hitung_total()">
                                                <option value="">Pilih Layanan</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <?php $field = 'ongkir'; ?>
                                            <label for="<?=$field?>">Ongkos Kirim</label>
                                            <input type="text" class="form-control" id="<?=$field?>" name="<?=$field?>" readonly />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <h4 style="color:  #2f55d4 !important">Rekening Anda</h4>
                                    <div class="row">
                                        <div class="col-md-3 form-group p_star">
                                            <label for="">Bank</label>
                                            <select class="form-control" onchange="get_rekening()" name="bank_pengirim" id="bank_pengirim">
                                                <option value="">Pilih Bank</option>
                                                <?php
                                                if(count($bank) > 0) {
                                                    foreach ($bank as $row) {
                                                        echo '<option value="'.$row['id_bank'].'">'.$row['nama'].'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <?php $field = 'nomor_rekening_pengirim'; ?>
                                            <label for="<?=$field?>">No.Rekening</label>
                                            <input type="text" class="form-control" id="<?=$field?>" name="<?=$field?>" />
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <?php $field = 'atas_nama_pengirim'; ?>
                                            <label for="<?=$field?>">Atas Nama</label>
                                            <input type="text" class="form-control" id="<?=$field?>" name="<?=$field?>" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <h4 style="color:  #2f55d4 !important">Pilih Rekening Tujuan</h4>
                                    <div class="row">
                                        <div class="col-md-3 form-group p_star">
                                            <label for="">Bank</label>
                                            <?php
                                            $list_bank = '';
                                            if(count($bank_tujuan) > 0) {
                                                foreach ($bank_tujuan as $row) {
                                                    echo '<input type="hidden" id="no_rek'.$row['id_bank'].'" value="'.$row['no_rek'].'"><input type="hidden" id="atas_nama'.$row['id_bank'].'" value="'.$row['atas_nama'].'"><input type="hidden" id="nama'.$row['id_bank'].'" value="'.$row['nama'].'">';
                                                    $list_bank .= '<option value="'.$row['id_bank'].'">'.$row['nama'].' ( '.$row["no_rek"].' ) </option>';
                                                }
                                            }
                                            ?>
                                            <select class="form-control" onchange="change_bank()" name="bank_tujuan" id="bank_tujuan">
                                                <option value="">Pilih Bank</option>
                                                <?=$list_bank?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 form-group" style="display: none;">
                                            <?php $field = 'nomor_rekening_tujuan'; ?>
                                            <label for="<?=$field?>">No. Rekening</label>
                                            <input type="text" class="form-control" id="<?=$field?>" name="<?=$field?>" readonly />
                                        </div>
                                        <div class="col-md-12 form-group" style="display: none;">
                                            <?php $field = 'atas_nama_tujuan'; ?>
                                            <label for="<?=$field?>">Atas Nama</label>
                                            <input type="text" class="form-control" id="<?=$field?>" name="<?=$field?>" readonly />
                                        </div>
                                    </div>
                                </div>



                            </div>
                        </div>
                        <div class="container-fluid">
                            <div class="cart_inner">
                                <div class="table-responsive bg-white shadow">
                                    <table class="table table-center table-padding mb-0">
                                        <thead>
                                        <tr>
                                            <th class="py-3" style="min-width:20px ">No</th>
                                            <th class="py-3" style="min-width: 300px;">Produk</th>
                                            <th class="text-center py-3" style="min-width: 160px;">Berat</th>
                                            <th class="text-center py-3" style="min-width: 160px;">Harga</th>
                                            <th class="text-center py-3" style="min-width: 160px;">Jumlah</th>
                                            <th class="text-center py-3" style="min-width: 160px;">Total</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <?php  $total=0;$berat=0;$no=0; foreach($cart as $row):
                                            $no++;
                                            $total = $total + ($row['harga'] * $row['qty']);
                                            $berat = $berat + ($row['berat']*$row['qty']);
                                            $gambar = $this->m_crud->get_data("gambar_produk", "CONCAT('" . base_url() . "', gambar) gambar", "produk='" . $row['id_produk'] . "'", "id_gambar DESC")['gambar'];
                                            ?>
                                            <input type="hidden" name="qty_produk_<?=$no?>" id="no_produk_<?=$no?>" value=" <?=$row['qty']?>">
                                            <input type="hidden" name="stok_produk_<?=$no?>" id="stok_produk_<?=$no?>" value="<?=$row['stok']?>">
                                            <input type="hidden" name="id_produk_<?=$no?>" id="id_produk_<?=$no?>" value="<?=$row['id_produk']?>">
                                            <tr>
                                                <td><?=$no?></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="<?=$gambar?>" class="img-fluid avatar avatar-small rounded shadow" style="height:auto;" alt="">
                                                        <h6 class="mb-0 ml-3">
                                                            <?=$row["nama_produk"]?><br>
                                                            size : <?=$row["ukuran"]?><br>
                                                            color : <?=$row["warna"]?>
                                                        </h6>
                                                    </div>

                                                </td>
                                                <td class="text-center">
                                                    <?=(($row['berat']*$row['qty'])/1000)?> Kg
                                                </td>
                                                <td class="text-center">
                                                    <?=number_format($row['harga'])?>
                                                </td>
                                                <td class="text-center">
                                                    <?=$row['qty']?>
                                                </td>
                                                <td class="text-right">
                                                    <?=number_format($row['harga']*$row['qty'])?>
                                                </td>

                                            </tr>
                                        <?php endforeach; ?>
                                        <input type="hidden" name="no_produk" id="no_produk" value="<?=$no?>">

                                        </tbody>
                                    </table>
                                </div>

                                <br>
                                <div class="row">
                                    <div class="col-6 col-xs-6">
                                        <a href="#">Ongkos Kirim</a>
                                    </div>
                                    <div class="col-6 col-xs-6">
                                        <h5 class="v_ongkir"  style="text-align:right">0</h5>
                                    </div>
                                    <div class="col-6 col-xs-6">
                                        <a href="#">Voucher</a>
                                    </div>
                                    <div class="col-6 col-xs-6">
                                        <h5 class="v_voucher"  style="text-align:right">0</h5>
                                    </div>
                                    <div class="col-6 col-xs-6">
                                        <a href="#">Total Pembayaran</a>
                                    </div>
                                    <div class="col-6 col-xs-6">
                                        <h5 class="v_total" style="text-align:right"><?=number_format($total)?></h5>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label for="<?=$field?>">Kode Voucher</label>
                                        <input placeholder='masukan kode voucher anda disini' class="form-control" name="voucher" id="voucher" onchange="cek_voucher()" type="text">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-6 col-xs-6">
                                        <a href="<?=base_url()?>" class="btn btn-primary">Belanja</a>
                                    </div>
                                    <div class="col-6 col-xs-6">
                                        <button type="submit" style="float: right;" class="btn btn-primary">Bayar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="jumlah_voucher" id="jumlah_voucher">
                        <input type="hidden" name="id_voucher" id="id_voucher" value="-">
                        <input type="hidden" name="data_layanan" id="data_layanan">
                        <input type="hidden" name="total" id="total" value="<?=$total?>">
                        <input type="hidden" name="berat" id="berat" value="<?=$berat?>">
                        <input type="hidden" name="kd_kec" id="kd_kec">
                        <input type="hidden" name="kd_kota" id="kd_kota">
                        <input type="hidden" name="kd_prov" id="kd_prov">
                        <input type="hidden" name="nama_bank_tujuan" id="nama_bank_tujuan">
                        <input type="hidden" name="nama_bank_pengirim" id="nama_bank_pengirim">
                    </form>
                </div>


            </div><!--end col-->


        </div><!--end row-->
    </div><!--end container-->
</section><!--end section-->


<script>
    $(document).ready(function(){
        if(parseInt('<?=$total?>') < 1){
            window.location.href="<?=base_url()?>";
        }
    });
    var array_ongkir = [];
    function cek_voucher() {
        var voucher = $("#voucher").val();
        var orders = $("#total").val();

        $.ajax({
            url: "<?=base_url().'ajax/cek_voucher'?>",
            type: "POST",
            data: {voucher: voucher, orders: orders},
            dataType: "JSON",
            success: function (res) {
                if (res.status) {
                    $(".v_voucher").text(res.v_voucher);
                    $("#jumlah_voucher").val(res.jumlah_voucher);
                    $("#id_voucher").val(res.id_voucher);
                    hitung_total();
                } else {
                    $("#voucher").val('');
                    $(".v_voucher").text('Rp 0');
                    $("#jumlah_voucher").val(0);
                    $("#id_voucher").val('-');
                    Swal.fire({
                        title: 'Alert!',
                        text: res.pesan,
                        icon: 'error'
                    })
                }

                hitung_total();
            }
        });
    }
    function ubah_alamat() {
        var alamat = $("#ch_alamat_jual").val();
        if (alamat === 'baru') {
            $("#new_address").show();
            $("#form_checkout").validate();
            $("#nama_penerima").rules("remove", "required");
            $("#nama_penerima").val('').attr('readonly', false);
            $("#telepon").val('').attr('readonly', false);
            $("#kota").val('').attr('readonly', false);
            $("#alamat").text('').attr('readonly', false);
            $("#provinsi").val('').attr('readonly', true);
            $("#kecamatan").val('').attr('readonly', true);
        }
        else if (alamat !== '') {
            $("#form_checkout").validate();
            $("#nama_penerima-error").hide();
            $("#telepon-error").hide();
            $("#kota-error").hide();
            $("#alamat-error").hide();
            $.ajax({
                url: "<?=base_url() . 'api/get_alamat/'?>" + alamat,
                type: "GET",
                dataType: "JSON",
                success: function (res) {
                    $("#nama_penerima").val(res.penerima).attr('readonly', true).rules("remove", "required");
                    $("#telepon").val(res.telepon).attr('readonly', true);
                    $("#alamat").text(res.alamat).attr('readonly', true);
                    $("#provinsi").val(res.nama_provinsi).attr('readonly', true);
                    $("#kota").val(res.nama_kota).attr('readonly', true);
                    $("#kecamatan").val(res.nama_kecamatan).attr('readonly', true);
                    $("#kd_kota").val(res.kota);
                    $("#kd_kec").val(res.kecamatan);
                    $("#kd_prov").val(res.provinsi);
                    $("#new_address").hide();
                    get_ongkir();
                }
            });
        }
    }


    $("#kota").autocomplete({
        minChars: 3,
        serviceUrl: '<?=base_url().'ajax/get_location'?>',
        type: 'post',
        dataType: 'json',
        response: function(event, ui) {
            if (ui.content.length === 0) {
                $("#empty-message").text("No results found");
            } else {
                $("#empty-message").empty();
            }
        },
        onSelect: function (suggestion) {
            if (suggestion.lokasi !== 'not_found') {
                add_lokasi(suggestion);
            } else {
                $("#kota").val('').focus();
            }
        }
    });
    function add_lokasi(res) {
        $("#kd_kota").val(res['kota_id']);
        $("#kd_prov").val(res['provinsi_id']);
        $("#kd_kec").val(res['kecamatan_id']);
        $("#kota").val(res['kot']);
        $("#provinsi").val(res['prov']).attr('readonly', true);
        $("#kecamatan").val(res['kec']).attr('readonly', true);
    }

    function get_ongkir() {
        var kecamatan_ = $("#kd_kec").val();
        var kurir_ = $("#kurir").val();
        var berat_ = $("#berat").val();

        if (kecamatan_ !== '' && kurir_ !== '') {
            array_ongkir = [];
            $.ajax({
                url: "<?=base_url().'api/get_ongkir';?>",
                type: "POST",
                data: {kecamatan: kecamatan_, kurir: kurir_, berat: berat_, jumlah: 1},
                dataType: "JSON",
                beforeSend: function() {$('body').append('<div class="first-loader"><img src="<?=base_url()?>assets/images/spin.svg"></div>');},
                complete: function() {$('.first-loader').remove();},
                success: function (res) {
                    var layanan = '';
                    var data;
                    if(res!==null){
                        if (res.costs.length > 0) {
                            $("#layanan-error").hide();
                            for (var x = 0; x < res.costs.length; x++) {
                                data = {service: res.costs[x].service, cost: res.costs[x].cost};
                                array_ongkir.push(data);
                                layanan += '<option value="' + x + '">' + res.costs[x].service + '</option>';
                            }
                        } else {
                            data = {service: '-', cost: 0};
                            array_ongkir.push(data);
                        }
                        $("#layanan").html(layanan);
                        setTimeout(function () {
                            hitung_total();
                        }, 500)
                    }
                    else{
                        Swal.fire({
                            title: 'Ooppps',
                            text: 'problem server',
                            icon: 'warning'
                        })
                    }

                }
            });
        }
    }


    function hitung_total() {
        /*if ($("#layanan").val() != '') {

        }*/

        var ongkir = 0;
        var etd = 0;
        var id = $("#layanan").val();
        var voucher = $("#jumlah_voucher").val();
        var data = array_ongkir[id];

        if (typeof data !== "undefined") {
            ongkir = data.cost[0]['value'];
            etd = data.cost[0]['etd'];
            $("#data_layanan").val(data.service);
        }

        total = ongkir + parseFloat($("#total").val()) - (isNaN(parseFloat(voucher))?0:parseFloat(voucher));
        $("#ongkir").val(to_rp(ongkir, '-'));
        $(".v_ongkir").text('Rp '+to_rp(ongkir, '-'));
        $(".v_total").text('Rp '+to_rp(total, '-'));
    }

    function change_bank() {
        var id = $("#bank_tujuan").val();
        $("#nama_bank_tujuan").val($("#nama"+id).val());
        $("#atas_nama_tujuan").val($("#atas_nama"+id).val());
        $("#nomor_rekening_tujuan").val($("#no_rek"+id).val());
        $("#bank_pengirim").val(id).change();
        $("#bank_pengirim-error").hide();

    }

    function get_rekening() {
        var id = $("#bank_pengirim").val();
        $.ajax({
            url: "<?=base_url().'api/get_rekening'?>",
            type: "POST",
            data: {bank: id},
            dataType: "JSON",
            success: function (res) {

                if (res.status) {
                    $("#nomor_rekening_pengirim-error").hide();
                    $("#atas_nama_pengirim-error").hide();
                    $("#nama_bank_pengirim").val(res.res_rekening['bank']);
                    $("#nomor_rekening_pengirim").val(res.res_rekening['no_rek']);
                    $("#atas_nama_pengirim").val(res.res_rekening['atas_nama']);
                } else {
                    $("#nama_bank_pengirim").val(res.res_rekening['nama']);
                    $("#nomor_rekening_pengirim").val('');
                    $("#atas_nama_pengirim").val('');
                }
            }
        });
    }

    $('#form_checkout').validate({
        rules: {
            nama_alamat:{
                required: function(element) {
                    if($("#ch_alamat_jual").val()==='baru'){
                        return true;
                    }else{
                        return false;
                    }
                }
            },
            nama_penerima:{required:true},
            telepon:{required:true},
            alamat:{required:true},
            kota:{required:true},
            kurir: {required: true},
            layanan: {required: true},
            bank_tujuan: {required: true},
            bank_pengirim: {required: true},
            nomor_rekening_pengirim: {required: true},
            atas_nama_pengirim: {required: true}
        },
        //For custom messages
        messages: {
            nama_alamat: {required: "name address cannot be empty!"},
            nama_penerima: {required: "name cannot be empty!"},
            telepon: {required: "phone number cannot be empty!"},
            alamat: {required: "address cannot be empty!"},
            kota: {required: "city cannot be empty!"},
            kurir: {required: "courier cannot be empty!"},
            layanan: {required: "shipping service cannot be empty!"},
            bank_tujuan: {required: "bank  cannot be empty"},
            bank_pengirim: {required: "Bank  cannot be empty!"},
            nomor_rekening_pengirim: {required: "account number cannot be empty!"},
            atas_nama_pengirim: {required: "account number cannot be empty!"}
        },

        submitHandler: function (form) {
            if($("#countCart").text()==='0'){
                Swal.fire({
                    title: 'Information !!!',
                    text: 'Item not available ',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Home',
                    confirmButtonText: 'Shop Now'
                }).then(function (result) {
                    if (result.value) {
                        window.location.href="<?=base_url().'store/get_all_product'?>";
                    }
                    else{
                        window.location.href="<?=base_url()?>";

                    }
                });
            }
            else{
                Swal.fire({
                    title: 'Confirm!',
                    html: 'Pastikan data sudah benar.<br><b style="font-weight: bold">Bank '+$("#nama_bank_pengirim").val()+'</b><br> <b style="font-weight: bold">Nomor Rekening : '+$("#nomor_rekening_pengirim").val()+'</b><br> <b style="font-weight: bold">Atas Nama : '+$("#atas_nama_pengirim").val()+'</b>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Tidak',
                    confirmButtonText: 'Ya'
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            url: "<?=base_url().'api/checkout_bayar'?>",
                            type:"POST",
                            data: $("#form_checkout").serialize(),
                            dataType: "JSON",
                            beforeSend: function() {$('body').append('<div class="first-loader"><img src="<?=base_url()?>assets/images/spin.svg"></div>');},
                            complete: function() {$('.first-loader').remove();},
                            success: function (res) {
                                console.log(res);
                                if (res.status) {
                                    Swal.fire({
                                        title: 'Checkout berhasil!',
                                        html: 'Segera transfer ke rekening :<br><b style="font-weight: bold">Bank '+res.bank+'</b><br> <b style="font-weight: bold">Nomor Rekening : '+res.norek+'</b><br> <b style="font-weight: bold">Atas Nama : '+res.atasnama+'</b><br> <b style="font-weight: bold">Sejumlah : '+to_rp(res.total)+'</b>',
                                        icon: 'success'
                                    }).then(function (result) {
                                        window.location = '<?=base_url().'store/profile'?>';
                                    })
                                } else {
                                    alert("Data gagal disimpan!");
                                }
                            }
                        });
                    }
                })
            }

        }


    })


    function to_rp(angka){
        if(angka != '' || angka != 0){
            var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
            var rev2    = '';
            for(var i = 0; i < rev.length; i++){
                rev2  += rev[i];
                if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
                    rev2 += ',';
                }
            }

            var dec		= parseFloat(angka, 10).toString().split('.');
            if(dec[1] > 0){ dec = dec[1]; } else { dec = '00'; }

            //return 'IDR : ' + rev2.split('').reverse().join('') + ',-';
            return rev2.split('').reverse().join('') + '.' + dec;
        } else {
            //return 'IDR : ';
            return '0';
        }
    }

</script>
