<?php


class Store extends CI_Controller
{
	public function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		//$this->session->sess_destroy();
		$site_data = $this->m_website->site_data('2222');
//		var_dump($site_data);
		$this->site = str_replace(' ', '', strtolower($site_data->nama));
		$this->user = $this->session->userdata('id_member');
		$this->login = $this->session->userdata('isLogin');
		$get_setting = $this->m_crud->get_data("setting", "sosmed, cs", "id_setting='1111'");
		$decode_sosmed = json_decode($get_setting['sosmed'], true);
		$decode_cs = json_decode($get_setting['cs'], true);
		$sosmed = array();
		foreach ($decode_sosmed as $row) {
			$sosmed[$row['id']] = $row['value'];
		}
		$this->data = array(
			'nav_menu' => $this->m_website->navbar_menu(),
			'site' => $site_data,
			'user' => $this->user,
			'account' => $this->m_website->member_data($this->user),
			'access' => $this->m_website->user_access_data($this->user),
			'status_login' => $this->session->userdata('isLogin'),
			'sosmed' => $sosmed,
			'cs' => $decode_cs
		);
		$this->output->set_header("Cache-Control: no-store, no-cache, max-age=0, post-check=0, pre-check=0");

	}

	public function other(){
        $data = $this->data;
        if($_GET['page']=='about'){
            $data['about']= $this->m_crud->get_data("setting", "tentang", "id_setting='1111'")['tentang'];
            $data['content'] 	= 'store/new/about';
        }
        if($_GET['page']=='tutorial'){
            $data['tutorial']= $this->m_crud->get_data("setting", "cara_belanja", "id_setting='1111'")['cara_belanja'];
            $data['content'] 	= 'store/new/tutorial';

        }
        if($_GET['page']=='gallery'){
            $data['model'] = $this->m_crud->read_data("model", "id_model, nama, gambar");
            $data['content'] 	= 'store/new/gallery';

        }
        if($_GET['page']=='contact'){
            $data['res_lokasi'] = $this->m_crud->read_data("lokasi", "*");
            $data['content'] 	= 'store/new/contact';

        }
        if($_GET['page']=='resolution'){
            $data['res_resolusi'] = $this->m_crud->get_data("setting", "pusat_resolusi", "id_setting='1111'")['pusat_resolusi'];
            $data['content'] 	= 'store/new/resolution';

        }
        if($_GET['page']=='privacy_policy'){
            $data['res_kebijakan'] = $this->m_crud->get_data("setting", "kebijakan", "id_setting='1111'")['kebijakan'];
            $data['content'] 	= 'store/new/privacy_policy';

        }
        if($_GET['page']=='career'){
            $data['res_karir'] = $this->m_crud->get_data("setting", "karir", "id_setting='1111'")['karir'];
            $data['content'] 	= 'store/new/career';

        }

        $this->load->view('store/new/index',$data);

    }

    public function index(){
		$data = $this->data;
		$data['title']='home';
        $in_produk = array();
        $get_bestsellers = $this->m_crud->read_data("bestsellers", "produk", null, "id_bestsellers");
        if ($get_bestsellers != null) {
            foreach ($get_bestsellers as $item) {
                array_push($in_produk, '\''.$item['produk'].'\'');
            }
        }
        $req_api = $this->m_website->request_api_local('get_produk', 'member=' . ($this->login ? $this->user : 'non_member') . '&filter=' . json_encode(array('in_produk' => json_encode($in_produk))));
        $data['slider'] = $this->m_crud->read_data("home_slide", "*");
        $data['bestSeller']=$req_api;
		$data['latestProduct'] = $this->m_crud->join_data(
			"produk pr","pr.*,gp.*,dp.*",
			array("gambar_produk gp","det_produk dp"),
			array("pr.id_produk=gp.produk","pr.id_produk=dp.produk"),
			null,"pr.id_produk DESC","pr.id_produk",16
		);
		$data['model'] = $this->m_crud->read_data("model", "id_model, nama, gambar",null,"rand()",null,9);
		$data['news'] = $this->m_crud->join_data("berita b","b.*,kb.nama",array("kategori_berita kb"),array("kb.id_kategori_berita=b.kategori_berita"),null,"b.id_berita DESC",null,6);
		$data['promo'] = $this->m_crud->read_data("promo", "deskripsi,id_promo, promo, gambar, diskon", "'".date('Y-m-d H:i:s')."' BETWEEN tgl_awal AND tgl_akhir");
		$data['topitem'] = $this->m_crud->read_data("top_item", "*");
        if(isset($_GET['page'])){
			if($_GET['page']=='about'){$data['about']= $this->m_crud->get_data("setting", "tentang", "id_setting='1111'")['tentang'];}
			if($_GET['page']=='tutorial'){$data['tutorial']= $this->m_crud->get_data("setting", "cara_belanja", "id_setting='1111'")['cara_belanja'];}
			if($_GET['page']=='gallery'){$data['model'] = $this->m_crud->read_data("model", "id_model, nama, gambar");}
			if($_GET['page']=='contact'){$data['res_lokasi'] = $this->m_crud->read_data("lokasi", "*");}
			if($_GET['page']=='resolution'){$data['res_resolusi'] = $this->m_crud->get_data("setting", "pusat_resolusi", "id_setting='1111'")['pusat_resolusi'];}
			if($_GET['page']=='privacy_policy'){ $data['res_kebijakan'] = $this->m_crud->get_data("setting", "kebijakan", "id_setting='1111'")['kebijakan'];}
			if($_GET['page']=='career'){$data['res_karir'] = $this->m_crud->get_data("setting", "karir", "id_setting='1111'")['karir'];}
			$data['content'] 	= 'store/new/'.$_GET['page'];
		}else{
			$data['content'] = 'store/new/home';
		}

        $this->load->view('store/new/index',$data);
	}
	public function promo(){
		$data = $this->data;
		$data['content'] = 'store/new/promo';
		$data['promo'] = $this->m_crud->read_data("promo", "id_promo,deskripsi, promo, gambar, diskon,slug_promo", "'".date('Y-m-d H:i:s')."' <= tgl_akhir");
//		var_dump($data['promo']);
		$this->load->view("store/new/index",$data);
	}
	public function history(){

		$data = $this->data;
		$data['content'] = 'store/history';
		$data['orders'] =$this->m_crud->join_data(
			"orders o",
			"o.id_orders, o.tgl_orders, o.status, SUM(do.qty * (do.hrg_jual+do.hrg_varian-do.diskon)) total, dp.pembayaran, pb.status status_bayar, pb.kode_unik, pb.jumlah_voucher, pb.voucher, pn.id_pengiriman",
			array("det_orders do", "det_pembayaran dp", "pembayaran pb", "pengiriman pn"),
			array("do.orders=o.id_orders", "dp.orders=o.id_orders", "pb.id_pembayaran=dp.pembayaran", "pn.orders=o.id_orders"),
			"o.member='".$this->user."' AND o.status <> '0'", "o.tgl_orders DESC", "o.id_orders"
		);
//		echo '<pre/>';
//		var_dump($data['orders']);
		$this->load->view("store/wrapper",$data);
	}



	public function article($param=null,$action=null,$page=4){
		$data = $this->data;
		$data['title']='article';
		$where="";
        $data['content'] = 'store/new/article';

		$table_join = array("kategori_berita kb");
		$join_on = array("kb.id_kategori_berita=b.kategori_berita");
		if($_GET['detail']){
			$data['content']='store/new/article_detail';
			$data['detail'] = $this->m_crud->get_join_data(
				"berita b", "b.*,kb.nama nama_kategori",
				$table_join,$join_on,"b.slug_berita='".$_GET['detail']."'","b.tgl_berita DESC"
			);
//			echo '<pre/>';
//			var_dump($data['detail']);die();
            $data['nextNews'] = $this->m_crud->get_data("berita","*","id_berita = (select min(id_berita) from berita where id_berita > '".$data['detail']['id_berita']."')");
            $data['prevNews'] = $this->m_crud->get_data("berita","*","id_berita = (select max(id_berita) from berita where id_berita < '".$data['detail']['id_berita']."')");

//            var_dump($data);die();
        }
//		if(isset($_POST['any']) && $_POST['any']!=''){
//			($where!=null)?$where.=' AND ':null;
//			$where.="b.judul like '%".$_POST['any']."%'";
//		}
//		if(isset($_POST['category']) && $_POST['category']!='' && $_POST['category']!='all'){
//			($where!=null)?$where.=' AND ':null;
//			$where.="kb.slug_kategori_berita='".$_POST['category']."'";
//		}else{
//			if($param!='all'){
//				if($_POST['category']=='all'){
//					$where=null;
//				}else{
//					($where!=null)?$where.=' AND ':null;
//					$where.="b.slug_kategori_berita='".$param."'";
//				}
//
//			}
//		}
		if($this->uri->segment(3)!='all'){
            ($where!=null)?$where.=' AND ':null;
            $where.="kb.slug_kategori_berita='".$this->uri->segment(3)."'";
        }


		if($action=='load_data'){
			$read_data = $this->m_crud->join_data(
				"berita b",
				"b.*,kb.id_kategori_berita,kb.nama,kb.slug_kategori_berita",
				$table_join,$join_on,$where,"b.tgl_berita DESC",null,$page,0
			);
			$result='';
			if($read_data!=null){
				foreach($read_data as $row){
				    $ringkasan='';
				    if(strlen($row['ringkasan'])>100){
				        $ringkasan.=substr($row['ringkasan'],0,100).' ...';
                    }
                    else{
				        $ringkasan.=$row['ringkasan'];
                    }
					$result.='
					<div class="col-lg-6 col-12 mb-4 pb-2">
                        <div class="card blog rounded border-0 shadow overflow-hidden">
                            <div class="row align-items-center no-gutters">
                                <div class="col-md-6">
                                    <img src="'.base_url().$row["gambar"].'" class="img-fluid" alt="">
                                </div><!--end col-->
        
                                <div class="col-md-6">
                                    <div class="card-body content">
                                        <h5><a href="'.base_url().'store/article?detail='.$row['slug_berita'].'" class="card-title title text-dark">'.$row["judul"].'</a></h5>
                                        <small class="text-muted mb-0">'.$ringkasan.'</small>
                                        <div class="post-meta d-flex justify-content-between mt-3">
                                            <ul class="list-unstyled mb-0">
                                                <li class="list-inline-item mr-2 mb-0"><a href="'.base_url().'store/article?detail='.$row['slug_berita'].'" class="text-muted like"><i class="mdi mdi-account"></i>'.$row["nama"].'</a></li>
                                                <li class="list-inline-item"><a href="'.base_url().'store/article?detail='.$row['slug_berita'].'" class="text-muted comments"><i class="mdi mdi-clock"></i>'.time_elapsed_string($row["tgl_berita"]).'</a></li>
                                            </ul>
                                        </div>
                                        <a style="color: #2f55d4;font-weight: bold" href="'.base_url().'store/article?detail='.$row['slug_berita'].'" class="readmore">Selengkapnya <i class="mdi mdi-chevron-right"></i></a>

                                        
                                    </div>
                                </div><!--end col-->
                            </div> <!--end row-->
                        </div><!--end blog post-->
                    </div><!--end col-->
					';
				}
			}else{
				$result.=$this->m_website->noData();
			}

			echo json_encode(array("count_berita"=>count($read_data),"category"=>$_GET['category'],"pagination_link"=>$pagin['pagination_link'],'result'=>$result));
		}else{
            $data['category'] = $this->m_crud->join_data("berita br","count(br.kategori_berita) kategori, kb.slug_kategori_berita,kb.id_kategori_berita, kb.nama","kategori_berita kb","kb.id_kategori_berita=br.kategori_berita",null,null,"br.kategori_berita");
            $data['promo'] = $this->m_crud->read_data("promo", "id_promo, promo, gambar, diskon,slug_promo", "'".date('Y-m-d H:i:s')."' <= tgl_akhir");
            $data['model'] = $this->m_crud->read_data("model", "id_model, nama,gambar",null,null,null,12);
			$this->load->view("store/new/index",$data);
		}

	}

	public function product($param=null){
		$data = $this->data;
		$where=null;$page=null;
		if(isset($_GET['product_id'])){
			($where!=null)?$where.=' AND ':null;
			$where.="pr.id_produk='".$_GET['product_id']."'";
			$data['product'] = $this->m_crud->join_data(
				"produk pr","pr.*,gp.*,dp.*",
				array("gambar_produk gp","det_produk dp"),
				array("pr.id_produk=gp.produk","pr.id_produk=dp.produk"),$where,"pr.id_produk DESC",'pr.id_produk',6
			)[0];
            $req_api2 = $this->m_website->request_api_local('get_produk', 'member='.($this->login?$this->user:'non_member').'&limit=12&page=1&filter='.json_encode(array('kelompok'=>$decode['res_produk'][0]['kelompok'])));
            $decode2 = json_decode($req_api2, true);
            $data['releatedProduct']=$decode2['res_produk'];
//            echo '<pre/>';
//            var_dump($decode2);die();
//            $data['releated   Product'] = $this->m_crud->join_data(
//				"produk pr","pr.*,gp.*,dp.*",
//				array("gambar_produk gp","det_produk dp"),
//				array("pr.id_produk=gp.produk","pr.id_produk=dp.produk"),
//				"pr.id_produk!='".$_GET['product_id']."' and pr.kelompok='".$data['product']['kelompok']."'",
//				"pr.id_produk DESC",'pr.id_produk'
//			);
            $data['nextProduct'] = $this->m_crud->get_data("produk","*","id_produk = (select min(id_produk) from produk where kelompok='".$data['product']['kelompok']."' and id_produk > '".$_GET['product_id']."')");
            $data['prevProduct'] = $this->m_crud->get_data("produk","*","id_produk = (select max(id_produk) from produk where kelompok='".$data['product']['kelompok']."' and id_produk < '".$_GET['product_id']."')");
//            var_dump($data['prevProduct']);
//			var_dump($data['releatedProduct']);
			$data['content'] = 'store/new/detail_product';
			$this->load->view("store/new/index",$data);
		}


	}
	public function cart(){
		$data = $this->data;
		$data['content'] = 'store/new/cart';
		$this->load->view("store/new/index",$data);
	}

	public function dataAddress(){
        return $this->m_crud->join_data(
            "alamat_member am", "am.id_alamat_member,am.nama nama_alamat,am.alamat, am.penerima, am.telepon,pr.provinsi_id,pr.provinsi,ktr.kota_id,ktr.kota,kcr.kecamatan_id,kcr.kecamatan",
            array("provinsi_rajaongkir pr","kota_rajaongkir ktr", "kecamatan_rajaongkir kcr"),
            array("pr.provinsi_id=am.provinsi", "ktr.kota_id=am.kota","kcr.kecamatan_id=am.kecamatan"),
            "am.status='1' AND am.member='".$this->user."'"
        );
    }

    public function profile(){
        if ($this->session->id_member == '') {
            redirect();
        }
        $data = $this->data;
        $data['content'] = 'store/history';
        $data['alamat'] = $this->dataAddress();
//        var_dump()
        $data['orders'] =$this->m_crud->join_data(
            "orders o",
            "o.id_orders, o.tgl_orders, o.status, SUM(do.qty * (do.hrg_jual+do.hrg_varian-do.diskon)) total, dp.pembayaran, pb.status status_bayar, pb.kode_unik, pb.jumlah_voucher, pb.voucher, pn.id_pengiriman",
            array("det_orders do", "det_pembayaran dp", "pembayaran pb", "pengiriman pn"),
            array("do.orders=o.id_orders", "dp.orders=o.id_orders", "pb.id_pembayaran=dp.pembayaran", "pn.orders=o.id_orders"),
            "o.member='".$this->user."' AND o.status <> '0'", "o.tgl_orders DESC", "o.id_orders"
        );
        $data['content'] = 'store/new/profile';
        $this->load->view("store/new/index",$data);
    }

    public function checkout(){
        if ($this->session->id_member == '') {
            redirect();
        }
        $data = $this->data;
        $data['cart'] = $this->m_crud->join_data("orders o", "o.id_orders,p.stok, p.nama nama_produk, p.id_produk, p.merk, dp.id_det_produk, dp.code, dp.ukuran, dp.warna, do.berat, do.qty, (do.hrg_jual+do.hrg_varian-do.diskon) harga", array("det_orders do", "det_produk dp", "produk p"), array("do.orders=o.id_orders", "dp.id_det_produk=do.det_produk", "p.id_produk=dp.produk"), "o.status='0' AND o.member='" . $this->user . "'");
        $data['alamat'] = $this->m_crud->read_data("alamat_member", "id_alamat_member, nama", "status='1' AND member='".$this->user."'");
        $data['kurir'] = $this->m_crud->read_data("kurir", "id_kurir, kurir", "status='1'");
        $data['bank_tujuan'] = $this->m_crud->join_data("bank b", "b.id_bank, b.nama, b.gambar, r.atas_nama, r.no_rek", "rekening r", "r.bank=b.id_bank", "r.utama='1'", null, "b.id_bank");
        $data['bank'] = $this->m_crud->read_data("bank", "id_bank, nama", null, "id_bank");
        $data['content'] = 'store/new/checkout';
        $this->load->view("store/new/index",$data);
    }
	public function auth(){
        if($_GET['page']=='login'||$_GET['page']=='register'){
            $data = $this->data;
            $data['content'] = 'store/new/'.$_GET['page'];
            $this->load->view('store/new/'.$_GET['page'],$data);
        }

	}
	public function logout(){
		$this->session->sess_destroy();
		redirect();
	}

	public function list_produk($param=null, $id=null, $page=4) {
		$data = $this->data;
		$response=array();
		$data['content'] ='store/new/list_product';
		$data['param'] = $id;
		$where = null;
		$uri=$this->uri->segment(3);

		if(isset($_POST['search']) ){
			if ($_POST['search']=='kategori') {
				$kelompok = $_POST['param'];
				($where!=null)?$where.=' AND ':null;
				$where.='pr.kelompok=\''.$kelompok.'\'';
			}
			if ($_POST['search']=='merk') {
				$merk = $_POST['param'];
				($where!=null)?$where.=' AND ':null;
				$where.='pr.merk=\''.$merk.'\'';
			}
			if($_POST['search']=='harga'){
				$hrg = explode('-', $_POST['param']);
				if (count($hrg) > 1) {
					$exHrg1 = (int)$hrg[0].'000';
					$exHrg2 = (int)$hrg[1].'000';
					($where!=null)?$where.=' AND ':null;
					$where.=' dp.hrg_jual BETWEEN CAST('.$exHrg1.' AS DECIMAL) AND CAST('.$exHrg2.' AS DECIMAL)';
				}
			}
			$table_join = array("det_produk dp", "merk mr", "kelompok kl");
			$join_on = array("dp.produk=pr.id_produk AND dp.code=pr.code", "mr.id_merk=pr.merk AND mr.status='1'", "kl.id_kelompok=pr.kelompok AND kl.status='1'");

			if($uri=='groups'){
				($where!=null)?$where.=' AND ':null;
				$where.='kl.groups=\''.$id.'\'';
			}
			if($uri=='groups'){
				($where!=null)?$where.=' AND ':null;
				$where.='kl.groups=\''.$id.'\'';
			}
			if($uri=='model' || $uri=='top_item' || $uri=='promo'){
			    $tbl="";
			    $par="";
			    if($uri=='promo'){
                    $tbl="det_promo";
                    $uri="slug_promo";
                }
                else{
			        $tbl="det_$uri";
                }

				array_push($table_join, "$tbl dti");
				array_push($join_on, "dti.produk=pr.id_produk AND dti.".$uri."='".$id."'");
			}

			$response['merk'] = $this->m_crud->join_data("produk pr", "mr.id_merk, mr.nama", $table_join, $join_on, $where, null, "mr.id_merk");
			$response['kelompok'] = $this->m_crud->join_data("produk pr", "kl.id_kelompok, kl.nama", $table_join, $join_on, $where, null, "kl.id_kelompok");
//			$pagin=$this->m_website->myPagination('join',5,"produk pr","pr.id_produk",$table_join,$join_on,$where,15,$page);
			$read_produk = $this->m_crud->join_data("produk pr", "pr.id_produk, pr.nama nama_produk, pr.code, pr.deskripsi, pr.free_return, pr.pre_order, pr.kelompok, dp.hrg_beli, dp.berat, dp.hrg_jual, mr.nama nama_merk, kl.nama nm_kelompok, mr.gambar gambar_merk", $table_join, $join_on, $where, "pr.id_produk DESC", "pr.id_produk",  $page, 0);
			$result='';
			if($read_produk!=null){
				foreach($read_produk as $row){
					/*Get promo*/
					$get_promo = $this->m_crud->get_join_data("promo pr", "pr.diskon", "det_promo dp", "dp.slug_promo=pr.slug_promo", "dp.produk='".$row['id_produk']."' AND '".date('Y-m-d H:i:s')."' BETWEEN pr.tgl_awal AND pr.tgl_akhir");
					if ($get_promo!=null) {
						$promo = 1;
						$hrgcoret = $row['hrg_jual'];
						$diskon = json_decode($get_promo['diskon'], true);
						$harga_promo = $this->m_website->double_diskon($row['hrg_jual'], $diskon);
						$data_diskon = '';
						for ($i=0;$i<count($diskon);$i++) {
							$data_diskon .= ($i>0)?' + ':'Diskon ';
							$data_diskon .= $diskon[$i].'%';
						}
						$diskon_persen = $diskon;
						$diskon = $data_diskon;
						$hrg_jual = $harga_promo;
					}
					else {
						$diskon_persen = array();
						$promo = 0;
						$hrg_jual = $row['hrg_jual'];
					}
					/*Get gambar produk*/
					$read_gambar = $this->m_crud->read_data("gambar_produk", "gambar", "produk='".$row['id_produk']."'");
					$gambar_produk ='';
					if ($read_gambar!=null) {
						foreach ($read_gambar as $row_gambar) {
							$gambar_produk=$row_gambar['gambar'];
//							array_push($gambar_produk, base_url().$row_gambar['gambar']);
						}
					}
					else {
						$gambar_produk=base_url().'assets/images/no_image.png';
//						array_push($gambar_produk, base_url().'assets/images/no_image.png');
					}
                    $core='';

					if($hrgcoret!=''){
					    $core=number_format($hrgcoret);
                    }
                    else{
					    $core='';
                    }

					$result.='
					 <div class="col-lg-3 col-md-6 col-6 mt-4 pt-2">
                        <div class="card shop-list border-0 position-relative" style="background:#f5f6fa!important;box-shadow: 3px 3px 0px 0 #2f55d4!important;">
                            <ul class="label list-unstyled mb-0">
                                <li><a href="javascript:void(0)" class="badge badge-pill badge-success">'.$diskon.'</a></li>
                            </ul>
                            <div class="shop-image position-relative overflow-hidden rounded shadow">
                                <a href="'.base_url().'store/product?product_id='.$row['id_produk'].'"><img src="'.base_url().$gambar_produk.'" class="img-fluid" alt=""></a>
                                <a href="'.base_url().'store/product?product_id='.$row['id_produk'].'" class="overlay-work">
                                    <img src="'.base_url().$gambar_produk.'" class="img-fluid" alt="">
                                </a>
                                
                            </div>
                            <div class="card-body content pt-4 p-2">
                                <a href="'.base_url().'store/product?product_id='.$row['id_produk'].'" class="text-dark product-name h6">'.$row["nama_produk"].'</a>
                                <div class="d-flex justify-content-between mt-1">
                                    <h6 class="text-muted small font-italic mb-0 mt-1">'.number_format($hrg_jual).' <del class="text-danger ml-2">'.$core.'</del> </h6>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
					';
				}
			}else{
				$result.=$this->m_website->noData();
			}
			$response['pagination_link'] = $pagin['pagination_link'];
			$response['res_produk'] = $result;
			$response['where'] = $get_promo;
            $response['count_produk'] = count($read_produk);
            echo json_encode($response);
		}
		else{
			$this->load->view("store/new/index",$data);
		}

	}


	public function get_all_product($action=null,$page=16){
        $response=array();
        $data = $this->data;
        $data['content'] ='store/new/list_all_product';
        if($action=='load_data'){
            $table_join = array("det_produk dp", "merk mr", "kelompok kl");
            $join_on = array("dp.produk=pr.id_produk AND dp.code=pr.code", "mr.id_merk=pr.merk AND mr.status='1'", "kl.id_kelompok=pr.kelompok AND kl.status='1'");
            $read_produk = $this->m_crud->join_data("produk pr", "pr.id_produk, pr.nama nama_produk, pr.code, pr.deskripsi, pr.free_return, pr.pre_order, pr.kelompok, dp.hrg_beli, dp.berat, dp.hrg_jual, mr.nama nama_merk, kl.nama nm_kelompok, mr.gambar gambar_merk", $table_join, $join_on, null, "pr.id_produk DESC", "pr.id_produk",  $page);

//            $read_produk = $this->m_crud->join_data(
//                "produk pr","pr.*,gp.*,dp.*",
//                array("gambar_produk gp","det_produk dp"),
//                array("pr.id_produk=gp.produk","pr.id_produk=dp.produk"),
//                null,"pr.id_produk DESC","pr.id_produk",$page, 1
//            );

            $result='';
            if($read_produk!=null){
                foreach($read_produk as $row){
                    /*Get promo*/
                    $get_promo = $this->m_crud->get_join_data("promo pr", "pr.diskon", "det_promo dp", "dp.slug_promo=pr.slug_promo", "dp.produk='".$row['id_produk']."' AND '".date('Y-m-d H:i:s')."' BETWEEN pr.tgl_awal AND pr.tgl_akhir");
                    if ($get_promo!=null) {
                        $promo = 1;
                        $hrgcoret = $row['hrg_jual'];
                        $diskon = json_decode($get_promo['diskon'], true);
                        $harga_promo = $this->m_website->double_diskon($row['hrg_jual'], $diskon);
                        $data_diskon = '';
                        for ($i=0;$i<count($diskon);$i++) {
                            $data_diskon .= ($i>0)?' + ':'Diskon ';
                            $data_diskon .= $diskon[$i].'%';
                        }
                        $diskon_persen = $diskon;
                        $diskon = $data_diskon;
                        $hrg_jual = $harga_promo;
                    }
                    else {
                        $diskon_persen = array();
                        $promo = 0;
                        $hrg_jual = $row['hrg_jual'];
                    }
                    $core='';

                    if($hrgcoret!=''){
                        $core=number_format($hrgcoret);
                    }
                    else{
                        $core='';
                    }
                    /*Get gambar produk*/
                    $read_gambar = $this->m_crud->read_data("gambar_produk", "gambar", "produk='".$row['id_produk']."'");
                    $gambar_produk ='';
                    if ($read_gambar!=null) {
                        foreach ($read_gambar as $row_gambar) {
                            $gambar_produk=$row_gambar['gambar'];
//							array_push($gambar_produk, base_url().$row_gambar['gambar']);
                        }
                    }
                    else {
                        $gambar_produk=base_url().'assets/images/no_image.png';
//						array_push($gambar_produk, base_url().'assets/images/no_image.png');
                    }
                    $result.='
					 <div class="col-lg-3 col-md-6 col-6 mt-4 pt-2">
                        <div class="card shop-list border-0 position-relative" style="background:#f5f6fa!important;box-shadow: 3px 3px 0px 0 #2f55d4!important;">
                            <ul class="label list-unstyled mb-0">
                                <li><a href="javascript:void(0)" class="badge badge-pill badge-success">'.$diskon.'</a></li>
                            </ul>
                            <div class="shop-image position-relative overflow-hidden rounded shadow">
                                <a href="'.base_url().'store/product?product_id='.$row['id_produk'].'"><img src="'.base_url().$gambar_produk.'" class="img-fluid" alt=""></a>
                                <a href="'.base_url().'store/product?product_id='.$row['id_produk'].'" class="overlay-work">
                                    <img src="'.base_url().$gambar_produk.'" class="img-fluid" alt="">
                                </a>
                            </div>
                            <div class="card-body content pt-4 p-2">
                                <a href="'.base_url().'store/product?product_id='.$row['id_produk'].'" class="text-dark product-name h6">'.$row["nama_produk"].'</a>
                                <div class="d-flex justify-content-between mt-1">
                                    <h6 class="text-muted small font-italic mb-0 mt-1">'.number_format($hrg_jual).' <del class="text-danger ml-2">'.$core.'</del> </h6>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
					';
                }
            }else{
                $result.=$this->m_website->noData();
            }
            $response['res_produk'] = $result;
            echo json_encode($response);
        }
        else{
            $this->load->view("store/new/index",$data);
        }
    }

}
