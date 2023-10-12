<?php


class Ajax extends CI_Controller
{



	public function produkJson($action=null){
		$where = null;
		$response = array();
		$table = 'produk pr';
		$field = "pr.*,gp.*,dp.*";
		$join = array("gambar_produk gp","det_produk dp");
		$on = array("pr.id_produk=gp.produk","pr.id_produk=dp.produk");
		if($action=='home'){
			$read_data = $this->m_crud->join_data("$table","$field", array("gambar_produk gp","det_produk dp"),array("pr.id_produk=gp.produk","pr.id_produk=dp.produk"),$where,"pr.id_produk DESC",NULL,6);
			$result = '';
			if($read_data!=null){
				foreach($read_data as $row){
					$result.=$this->tempProduk($row['gambar'],$row['id_produk'],$row['nama'],$row['hrg_jual'],$row['hrg_sebelum']);
				}
			}else{
				$result.=$this->m_website->noData();
			}
			$response=array("result"=>$result,"msg"=>"berhasil","status"=>"success");
		}

		else if($action=='groups'){
			$result='';
			if(isset($_POST['id_kelompok']) && $_POST['id_kelompok']!=null){
				($where!=null)?$where.=' AND ':null;
				$where.="pr.kelompok='".$_POST['id_kelompok']."'";
			}
			if(isset($_POST['id_merk']) && $_POST['id_merk']!=null){
				($where!=null)?$where.=' AND ':null;
				$where.="pr.merk='".$_POST['id_merk']."'";
			}
			$pagin = $this->m_website->myPagination('join',"$table","id_produk",array("gambar_produk gp","det_produk dp"),array("pr.id_produk=gp.produk","pr.id_produk=dp.produk"),$where,15);
			$read_data = $this->m_crud->join_data("$table","$field", array("gambar_produk gp","det_produk dp"),array("pr.id_produk=gp.produk","pr.id_produk=dp.produk"),$where,"pr.id_produk DESC",NULL,$pagin["perPage"], $pagin['start']);
			if($read_data!=null){
				foreach($read_data as $row){
					$result.=$this->tempProduk($row['gambar'],$row['id_produk'],$row['nama'],$row['hrg_jual'],$row['hrg_sebelum']);
				}
			}else{
				$result.=$this->m_website->noData();
			}
			$response=array("pagination_link"=> $pagin['pagination_link'],"result"=>$result,"msg"=>"berhasil","status"=>"success");

		}

		echo json_encode($response);


	}

	public function tempProduk($gambar,$id,$nama,$hrg_jual,$hrg_sebelum){
		return $result = /** @lang text */ '
		<div class="col-xl-4 col-lg-4 col-md-6">
			<div class="single-product mb-60">
				<div class="product-img">
					<img src="'.base_url()."assets/fo/assets/img/product/product_list_1.png".'" alt="">
				</div>
				<div class="product-caption">
					<div class="product-ratting">
						<i class="far fa-star"></i>
						<i class="far fa-star"></i>
						<i class="far fa-star"></i>
						<i class="far fa-star low-star"></i>
						<i class="far fa-star low-star"></i>
					</div>
					<h4><a href="'.base_url().'store/product?product_id='.$id.'">'.$nama.'</a></h4>
					<div class="price">
						<ul>
							<li>'.$hrg_jual.'</li>
							<li class="discount">'.$hrg_sebelum.'</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		';
	}

	public function login(){
		$result = array();
		$email = $_POST['email'];
		$password = $_POST['password'];
		$get_user = $this->m_crud->get_data("member", "*, ifnull(telepon, 'Phone Number is Empty') tlp", "email='".$email."' AND status='1' AND verify='1'");
		if ($get_user != null) {
			if (password_verify($password, $get_user['password'])) {
				$result['status'] = true;
				$result['res_login'] = array(
					'id_member'=>$get_user['id_member'],
					'nama'=>strtoupper($get_user['nama']),
					'telepon'=>$get_user['tlp'],
					'register'=>$get_user['register'],
					'foto'=>base_url().$get_user['foto'],
					'kode_member'=>$get_user['ol_code'],
					'tgl_register'=>$get_user['tgl_register'],
					'isLogin' => true,
				);
				$this->session->set_userdata($result['res_login']);
			} else {
				$result['status'] = false;
				$result['res_login'] = array('message'=>'Invalid username or password!');
			}
		} else {
			$result['status'] = false;
			$result['res_login'] = array('message'=>'User Not Found.');
		}

		echo json_encode($result);
	}

	public function register($action=null) {
		$response = array();
		$nama = $this->input->post("nama",true);
		$email = $this->input->post("email",true);
		$tlp = $this->input->post("telp",true);
		$id = md5($email);
		$new_password = $this->input->post("password",true);
		$options = array('cost' => 12);
		$password = password_hash($new_password, PASSWORD_BCRYPT, $options);
		$ol_code = $this->m_website->generate_kode("member", date('ym'));

		$data_member = array(
			'email'=>$email,
			'password'=>$password,
			'nama'=>$nama,
			'telepon'=>$tlp,
			'status'=>'1',
			'foto'=>'assets/images/member/default.png',
			'tgl_register'=>date('Y-m-d H:i:s'),
			'verify'=>'1',
			'register'=>'email',
			'id_register'=>$id,
			'hash'=>password_hash($new_password, PASSWORD_BCRYPT, $options),
			'ol_code' => $ol_code
		);
		if (isset($_POST['jk'])) {
			$data_member['jenis_kelamin'] =  $this->input->post("jk",true);
		}

		if (isset($_POST['tgl_lahir'])) {
			$data_member['tgl_lahir'] = date('Y-m-d', strtotime($_POST['tgl_lahir']));
		}

		if ($action == 'cek_email') {
			$where = "email='".$_POST['email']."'";
			$cek_email = $this->m_crud->get_data("member", "email", "$where");
			if ($cek_email == null) {
				echo 'true';
			} else {
				echo 'false';
			}
		}
		else if ($action == 'cek_telepon') {
			$where = "telepon='".$_POST['telp']."'";
			$cek_telepon = $this->m_crud->get_data("member", "telepon", $where);
			if ($cek_telepon == null) {
				echo 'true';
			} else {
				echo 'false';
			}
		}
		else if($action == 'simpan'){
			$this->m_crud->create_data("member", $data_member);
			$response['status'] = true;
			$response['ol_code'] = $ol_code;
			$get_user = $this->m_crud->get_data("member", "*, ifnull(telepon, 'Phone Number is Empty') tlp", "email='".$email."' AND status='1' AND verify='1'");

			$result['res_login'] = array(
				'id_member'=>$get_user['id_member'],
				'nama'=>strtoupper($get_user['nama']),
				'telepon'=>$get_user['tlp'],
				'register'=>$get_user['register'],
				'foto'=>base_url().$get_user['foto'],
				'kode_member'=>$get_user['ol_code'],
				'tgl_register'=>$get_user['tgl_register'],
				'isLogin' => true,
			);
			$this->session->set_userdata($result['res_login']);
			echo json_encode($response);
		}


	}
	function random_char($length = 6) {
		$str = "";
		$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
		$max = count($characters) - 1;
		for ($i = 0; $i < $length; $i++) {
			$rand = mt_rand(0, $max);
			$str .= $characters[$rand];
		}
		return $str;
	}

    public function getCart(){
	    $result='';

        $get_keranjang = $this->m_crud->join_data(
            "orders o",
            "o.id_orders, p.nama nama_produk, p.id_produk, p.merk, dp.id_det_produk, dp.code, dp.ukuran, dp.warna, do.berat, do.qty, (do.hrg_jual+do.hrg_varian-do.diskon) harga",
            array("det_orders do", "det_produk dp", "produk p"),
            array("do.orders=o.id_orders", "dp.id_det_produk=do.det_produk", "p.id_produk=dp.produk"), "o.status='0' AND o.member='".$this->session->id_member."'"
        );
        if(count($get_keranjang)>0){
            $total = 0;$no=1;$qty=0;
            foreach($get_keranjang as $row){
                $total = $total + ($row['harga']*$row['qty']);
                $qty = $qty+$row['qty'];
                $gambar = $this->m_crud->get_data("gambar_produk", "CONCAT('".base_url()."', gambar) gambar", "produk='".$row['id_produk']."'", "id_gambar DESC")['gambar'];
                $nama=strlen($row["nama_produk"])>10?substr($row["nama_produk"],0,10).'...':$row["nama_produk"];
                $result.='
                <div class="col-lg-3 col-md-6 col-11 mt-4 pt-2">
                    <div class="media customer-testi m-2" style="cursor: pointer">
                        <img src="'.$gambar.'" class="avatar avatar-small mr-3 rounded shadow" alt="">
                        <div onclick="hapus_item(\''.$row['id_orders'].'\', \''.$row['id_det_produk'].'\')" class="ribbon ribbon-right ribbon-warning overflow-hidden">
                            <span class="text-center d-block shadow small h6">
                                <i class="uil uil-trash align-middle icons"></i>
                            </span>
                        </div>
        
                        <div class="media-body content p-2 shadow rounded bg-white position-relative">
                            <ul class="list-unstyled mb-0">
                                <li class="list-inline-item">'.$row["nama_produk"].'</li>
                            </ul>
                            <h5 class="text-muted">'.number_format($row['harga']).' X '.$row['qty'].'</h5>
                            <hr>
                            <div class="d-flex align-items-center shop-list align-items-center">
                                <input onclick="min_qty(\''.$row['id_orders'].'\', \''.$row['id_det_produk'].'\', \''.$row['qty'].'\')" type="button" value="-" class=" minus btn btn-icon btn-soft-primary font-weight-bold mr-2">
                                <input onchange="update_qty(\''.$row['id_orders'].'\', \''.$row['id_det_produk'].'\')" type="text" step="1" min="1" value="'.$row['qty'].'" title="Qty" class="qty-'.$row['id_det_produk'].' mr-2 btn btn-icon btn-soft-primary font-weight-bold">
                                <input onclick="add_qty(\''.$row['id_orders'].'\', \''.$row['id_det_produk'].'\', \''.$row['qty'].'\')" type="button" value="+" class="plus btn btn-icon btn-soft-primary font-weight-bold">
                            </div>
                        </div>
                    </div>
                </div>
                ';
            }
            echo json_encode(
                array('count'=>count($get_keranjang), 'result' => $result,'total'=>number_format($total),'qty'=>$qty, 'res_mobile'=>$resMobile)
            );
        }
        else{
            echo json_encode(array('count'=>0,'result' => $this->m_website->noData()));
        }
    }
	public function cart(){
        $get_keranjang = $this->m_crud->join_data(
            "orders o",
            "o.id_orders, p.nama nama_produk, p.id_produk, p.merk, dp.id_det_produk, dp.code, dp.ukuran, dp.warna, do.berat, do.qty, (do.hrg_jual+do.hrg_varian-do.diskon) harga",
            array("det_orders do", "det_produk dp", "produk p"),
            array("do.orders=o.id_orders", "dp.id_det_produk=do.det_produk", "p.id_produk=dp.produk"), "o.status='0' AND o.member='".$this->session->id_member."'"
        );
        if(count($get_keranjang)>0){
            $result='';$resMobile="";$total = 0;$no=1;$qty=0;
            foreach($get_keranjang as $row){
                $total = $total + ($row['harga']*$row['qty']);
                $qty = $qty+$row['qty'];
                $gambar = $this->m_crud->get_data("gambar_produk", "CONCAT('".base_url()."', gambar) gambar", "produk='".$row['id_produk']."'", "id_gambar DESC")['gambar'];
                $nama=strlen($row["nama_produk"])>10?substr($row["nama_produk"],0,10).'...':$row["nama_produk"];
                $result.='
				<a href="javascript:void(0)" class="media align-items-center" style="border-bottom: 1px solid #EEEEEE;">
                    <img src="'.$gambar.'" class="shadow rounded" style="max-height: 64px;width: 64px;" alt="">
                    <div class="media-body text-left ml-3">
                        <h6 class="text-dark mb-0">'.$nama.'</h6>
                        <p class="text-muted mb-0">'.number_format($row['harga']).' X '.$row['qty'].'</p>
                    </div>
                    <h6 class="text-dark mb-0">'.number_format($row['harga']*$row['qty']).'</h6>
                </a>
				
				';
            }
            echo json_encode(
                array('count'=>count($get_keranjang), 'result' => $result,'total'=>number_format($total),'qty'=>$qty, 'res_mobile'=>$resMobile)
            );
        }
        else{
            echo json_encode(array('count'=>0,'result' => $this->m_website->noData()));
        }
	}
	public function get_location() {
		$get_lokasi = $this->m_crud->join_data(
			"kota_rajaongkir k",
			"CONCAT('Kecamatan ', kcr.kecamatan, ' ', k.tipe, ' ', k.kota, ', ', p.provinsi) value,kcr.kecamatan kec,k.kota kot,p.provinsi prov, k.kota_id, p.provinsi_id, kcr.kecamatan_id",
			array("provinsi_rajaongkir p", "kecamatan_rajaongkir kcr"),
			array("p.provinsi_id=k.provinsi", "kcr.kota=k.kota_id"),
			"p.provinsi like '%".$_POST['query']."%' OR k.kota like '%".$_POST['query']."%' OR kcr.kecamatan like '%".$_POST['query']."%' OR k.tipe like '%".$_POST['query']."%'"
		);
		if ($get_lokasi != null) {
			$result = $get_lokasi;
		} else {
			$result = array(array('lokasi'=>'not_found', 'value'=>'Lokasi Tidak Tersedia!'));
		}

		echo json_encode(array("suggestions"=>$result));
	}

	public function get_produk() {
		$get_product = $this->m_crud->read_data(
			"produk pr","pr.id_produk,pr.nama value",
			"pr.nama like '%".$_POST['query']."%'"
		);

		if ($get_product != null) {
			$result = $get_product;
		} else {
			$result = array(array('lokasi'=>'not_found', 'value'=>'product not found!'));
		}

		echo json_encode(array("suggestions"=>$result));
	}
	public function cek_voucher() {
		$result = array();
		$voucher = $_POST['voucher'];
		$orders = $_POST['orders'];
		$date = date('Y-m-d H:i:s');

		$get_voucher = $this->m_crud->get_data("voucher", "*", "kode = '".$voucher."' AND status = '1' AND '".$date."' BETWEEN tgl_mulai AND tgl_selesai");
		if ($get_voucher != null) {
			$cek_penggunaan = $this->m_crud->count_data("pembayaran", "id_pembayaran", "voucher='".$get_voucher['id_voucher']."' AND member='".$this->user."'");

			if ($cek_penggunaan >= $get_voucher['quota']) {
				$result['status'] = false;
				$result['pesan'] = "Voucher melebihi batas penggunaan";
			} else if ($orders < $get_voucher['min_orders']) {
				$result['status'] = false;
				$result['pesan'] = "Jumlah belanja anda minimal Rp. ".number_format($get_voucher['min_orders']);
			} else {
				$result['id_voucher'] = $get_voucher['id_voucher'];
				$result['jumlah_voucher'] = $get_voucher['value'];
				$result['v_voucher'] = 'Rp '.number_format($get_voucher['value']);
				$result['status'] = true;
			}
		} else {
			$result['status'] = false;
			$result['pesan'] = "Voucher tidak tersedia";
		}

		echo json_encode($result);
	}


	public function ajax_produk(){
		$result = array();
		$member = $_POST['member'];

		$filter = $_POST['filter'];
		$limit = isset($_POST['limit'])?$_POST['limit']:0;
		$page = isset($_POST['page'])?$_POST['page']:1;
		$where = null;
		$order = null;

		if (isset($_POST['kelompok']) && $_POST['kelompok']!='') {
			$kelompok = $_POST['kelompok'];
			($where!=null)?$where.=' AND ':null;
			$where.='pr.kelompok=\''.$kelompok.'\'';
		}

		if (isset($filter['group']) && $filter['group']!='') {
			$group = $filter['group'];
			($where!=null)?$where.=' AND ':null;
			$where.='kl.groups=\''.$group.'\'';
		}

		if (isset($filter['nama']) && $filter['nama']!='') {
			$nama = $this->m_website->replace_kutip($filter['nama'],'replace');
			($where!=null)?$where.=' AND ':null;
			$where.='pr.nama LIKE \'%'.$nama.'%\'';
		}

		if (isset($filter['favorit']) && $filter['favorit']!='') {
			$fav = $filter['pre_order'];
			($where!=null)?$where.=' AND ':null;
			$where.='pr.id_produk in (select produk from favorit where member=\''.$fav.'\')';
		}

		if (isset($filter['merk']) && $filter['merk']!='') {
			$merk = $filter['merk'];
			($where!=null)?$where_merk=' AND ':null;
			$where_merk.='pr.merk IN ('.$merk.')';
		}

		if (isset($filter['in_produk']) && $filter['in_produk']!='') {
			if (is_array(json_decode($filter['in_produk'], true))){
				$in_produk = implode(', ', json_decode($filter['in_produk'], true));
			} else {
				$in_produk = implode(', ', json_decode(base64_decode($filter['in_produk']), true));
			}

			($where!=null)?$where=' AND ':null;
			$where.='pr.id_produk IN ('.$in_produk.')';
			$order = sprintf('FIELD(pr.id_produk, %s)', $in_produk);
		}

		if (isset($filter['id_produk']) && $filter['id_produk']!='') {
			$id_produk = $filter['id_produk'];
			($where!=null)?$where=' AND ':null;
			$where.='pr.id_produk = '.$id_produk.'';
		}

		if (isset($filter['free_return']) && $filter['free_return']!='') {
			$free_return = $filter['free_return'];
			($where!=null)?$where.=' AND ':null;
			$where.='pr.free_return=\''.$free_return.'\'';
		}

		if (isset($filter['pre_order']) && $filter['pre_order']!='') {
			$pre_order = $filter['pre_order'];
			($where!=null)?$where.=' AND ':null;
			$where.='pr.pre_order=\''.$pre_order.'\'';
		}

		$harga1 = $filter['harga1'];
		$harga2 = $filter['harga2'];
		if (isset($filter['harga1']) && $filter['harga1']!='' && isset($filter['harga2']) && $filter['harga2']!='') {
			($where!=null)?$where.=' AND ':null;
			$where.=' dp.hrg_jual BETWEEN CAST('.$harga1.' AS DECIMAL) AND CAST('.$harga2.' AS DECIMAL)';
		}
		else if (isset($filter['harga1']) && $filter['harga1']!='') {
			($where!=null)?$where.=' AND ':null;
			$where.=' dp.hrg_jual >= CAST('.$harga1.' AS DECIMAL)';
		}
		else if (isset($filter['harga2']) && $filter['harga2']!='') {
			($where!=null)?$where.=' AND ':null;
			$where.=' dp.hrg_jual <= CAST('.$harga2.' AS DECIMAL)';
		}

		if (isset($filter['produk_baru']) && $filter['produk_baru']!='') {
			$order = "pr.tgl_input DESC";
		}
		else {
			if (isset($filter['sorting']) && $filter['sorting']!='') {
				$sort = $filter['sorting'];
				if ($sort == 'ulasan') {
					$order = "COUNT(ul.orders) DESC";
				} else if ($sort == 'penjualan') {
					$order = "IFNULL(AVG(ul.rating_produk), 0) DESC";
				} else if ($sort == 'termurah') {
					$order = "dp.hrg_jual ASC";
				} else if ($sort == 'termahal') {
					$order = "dp.hrg_jual DESC";
				} else if ($sort == 'terbaru') {
					$order = "pr.tgl_input DESC";
				} else {
					$order = null;
				}
			}
		}

		if (isset($filter['promo']) && $filter['promo']!='') {
			$where .= " AND pr.id_produk IN ()";
		}

		$table_join = array("det_produk dp", "merk mr", "kelompok kl", array("table"=>"ulasan ul","type"=>"LEFT"), array("table"=>"diskusi_produk dk","type"=>"LEFT"), array("table"=>"kartu_stok ks","type"=>"LEFT"));
		$join_on = array("dp.produk=pr.id_produk AND dp.code=pr.code", "mr.id_merk=pr.merk AND mr.status='1'", "kl.id_kelompok=pr.kelompok AND kl.status='1'", "ul.produk=pr.id_produk", "dk.produk=pr.id_produk", "ks.produk=pr.id_produk");
		$table_join2 = array("det_produk dp", "merk mr", "kelompok kl");
		$join_on2 = array("dp.produk=pr.id_produk AND dp.code=pr.code", "mr.id_merk=pr.merk", "kl.id_kelompok=pr.kelompok");

		if (isset($filter['items']) && $filter['items']!='') {
			$items = json_decode($filter['items'], true);
			array_push($table_join, "det_".$items['table']." dti");
			array_push($join_on, "dti.produk=pr.id_produk AND dti.".$items['table']."='".$items['id']."'");
			array_push($table_join2, "det_".$items['table']." dti");
			array_push($join_on2, "dti.produk=pr.id_produk AND dti.".$items['table']."='".$items['id']."'");
		}

		$read_produk = $this->m_crud->join_data("produk pr", "pr.id_produk, pr.nama nama_produk, pr.code, pr.deskripsi, pr.free_return, pr.pre_order, pr.kelompok, dp.hrg_beli, dp.berat, dp.hrg_jual, mr.nama nama_merk, kl.nama nm_kelompok, mr.gambar gambar_merk, COUNT(ul.orders) ulasan, COUNT(dk.comment) diskusi, IFNULL(AVG(ul.rating_produk), 0) rating, ifnull(SUM(ks.stok_in-ks.stok_out), 0) stok", $table_join, $join_on, $where, $order, "pr.id_produk", $limit, $limit*($page-1));
		$result['rows'] = $this->m_crud->count_data_join("produk pr", "pr.id_produk", $table_join2, $join_on2, $where.$where_merk);
		$result['merk'] = $this->m_crud->join_data("produk pr", "mr.id_merk, mr.nama", $table_join2, $join_on2, $where, null, "mr.id_merk");
		$result['kelompok'] = $this->m_crud->join_data("produk pr", "kl.id_kelompok, kl.nama", $table_join2, $join_on2, $where, null, "kl.id_kelompok");
		$result['harga'] = array(array('val'=>'0-100', 'text'=>'Rp 0 - 100.000'), array('val'=>'100-500', 'text'=>'Rp 100.000 - 500.000'), array('val'=>'500-', 'text'=>'Rp > 500.000'));
		if ($read_produk!=null) {
			$result['status'] = true;
			$res_produk = array();
			foreach ($read_produk as $row) {
				$data_produk = array(
					'id_produk'=>$row['id_produk'],
					'kelompok'=>$row['kelompok'],
					'nm_kelompok'=>$row['nm_kelompok'],
					'code'=>$row['code'],
					'nama_produk'=>$row['nama_produk'],
					'deskripsi'=>$row['deskripsi'],
					'free_return'=>$row['free_return'],
					'pre_order'=>$row['pre_order'],
					'berat'=>$row['berat'],
					'hrg_beli'=>$row['hrg_beli'],
					'nama_merk'=>$row['nama_merk'],
					'gambar_merk'=>base_url().$row['gambar_merk'],
					'ulasan'=>$row['ulasan'],
					'diskusi'=>$row['diskusi'],
					'rating'=>$row['rating'],
					/*'stok'=>$row['stok']*/
					'stok'=>99
				);

				/*Get gambar produk*/
				$read_gambar = $this->m_crud->read_data("gambar_produk", "gambar", "produk='".$row['id_produk']."'");
				$gambar_produk = array();
				if ($read_gambar!=null) {
					foreach ($read_gambar as $row_gambar) {
						array_push($gambar_produk, base_url().$row_gambar['gambar']);
					}
				} else {
					array_push($gambar_produk, base_url().'assets/images/no_image.png');
				}
				$data_produk['gambar_produk'] = $gambar_produk;

				/*Get status favorit*/
				if ($member!='non_member') {
					$check_fav = $this->m_crud->get_data("favorit", "id_favorit", "produk='".$row['id_produk']."' AND member='".$member."'");
					if ($check_fav!=null) {
						$data_produk['favorit'] = 1;
					} else {
						$data_produk['favorit'] = 0;
					}
				} else {
					$data_produk['favorit'] = 0;
				}

				/*Get promo*/
				$get_promo = $this->m_crud->get_join_data("promo pr", "pr.diskon", "det_promo dp", "dp.promo=pr.id_promo", "dp.produk='".$row['id_produk']."' AND '".date('Y-m-d H:i:s')."' BETWEEN pr.tgl_awal AND pr.tgl_akhir");
				if ($get_promo!=null) {
					$data_produk['promo'] = 1;
					$data_produk['hrg_coret'] = $row['hrg_jual'];
					$diskon = json_decode($get_promo['diskon'], true);
					$harga_promo = $this->m_website->double_diskon($row['hrg_jual'], $diskon);
					$data_diskon = '';
					for ($i=0;$i<count($diskon);$i++) {
						$data_diskon .= ($i>0)?' + ':'Diskon ';
						$data_diskon .= $diskon[$i].'%';
					}
					$data_produk['diskon_persen'] = $diskon;
					$data_produk['diskon'] = $data_diskon;
					$data_produk['hrg_jual'] = $harga_promo;
				} else {
					$data_produk['diskon_persen'] = array();
					$data_produk['promo'] = 0;
					$data_produk['hrg_jual'] = $row['hrg_jual'];
				}

				/*Get grosir*/
				$get_grosir = $this->m_crud->read_data("grosir", "*", "produk='".$row['id_produk']."'", "qty1 ASC");
				if ($get_grosir != null) {
					$data_produk['grosir'] = $get_grosir;
				}

				array_push($res_produk, $data_produk);
			}
			$result['res_produk'] = $res_produk;
		}
		else {
			$result['status'] = false;
		}

		echo json_encode(array("seacrh"=>$where,"produk"=>$read_produk));
	}

	public function ajax_detail_produk(){
        $read_data = $this->m_crud->get_join_data(
            "produk pr","pr.*,gp.*,dp.*",
            array("gambar_produk gp","det_produk dp"),
            array("pr.id_produk=gp.produk","pr.id_produk=dp.produk"),
            "pr.id_produk='".$this->input->post('id',true)."'","pr.id_produk DESC","pr.id_produk",8
        );
        $readGambar = $this->m_crud->read_data("gambar_produk","*","produk='".$read_data['id_produk']."'");

        echo json_encode(array("result"=>$read_data,'res_image'=>$readGambar));
    }



}
