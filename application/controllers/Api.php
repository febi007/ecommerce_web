<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library('curl');
		date_default_timezone_set('Asia/Jakarta');
		$site_data = $this->m_website->site_data('2222');
		$this->site = str_replace(' ', '', strtolower($site_data->nama));

		$this->user = $this->session->userdata('id_member');
		$this->api = base_url().'api/';

	}

	public function monitoring() {
		$last_month = date('Y-m', strtotime('-50 month', strtotime(date('Y-m'))));
		$this_month = date('Y-m');
		$last_month2 = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-d'))));
		$now = date('Y-m-d');
		$last_month3 = date('Y-m-d', strtotime('-100 month', strtotime(date('Y-m-d'))));

		$get_orders = $this->m_crud->get_join_data("orders o", "COUNT(DISTINCT o.id_orders) penjualan, IFNULL(SUM(dto.qty * (dto.hrg_jual+dto.hrg_varian-dto.diskon)), 0) total", array("det_orders dto", "det_pembayaran dtp", "pembayaran p"), array("dto.orders=o.id_orders", "o.id_orders=dtp.orders", "dtp.pembayaran=p.id_pembayaran"), "o.status IN ('1', '2', '3', '4') and p.tgl_verify is not null");

		$get_members = $this->m_crud->get_data("member", "COUNT(id_member) member", "SUBSTRING(tgl_register, 1, 7) = '".$this_month."'");

		$chart_bulan = $this->m_crud->read_data("orders", "DATE_FORMAT(tgl_orders, '%Y-%m-%d') AS tgl , COUNT(tgl_orders)/10 AS count", "tgl_orders BETWEEN DATE_FORMAT('".$last_month2."', '%Y-%m-%d') AND DATE_FORMAT('".$now."', '%Y-%m-%d')",null,'tgl');
		$chart_all = $this->m_crud->read_data("orders", "DATE_FORMAT(tgl_orders, '%Y-%m-%d') AS tgl , COUNT(tgl_orders)/10 AS count", "tgl_orders BETWEEN DATE_FORMAT('".$last_month3."', '%Y-%m-%d') AND DATE_FORMAT('".$now."', '%Y-%m-%d')",null,'tgl');
		$charts = '[[';
		foreach($chart_bulan as $k=>$item){
			$charts .= '"'.$item['count'].'"'.(count($chart_bulan)!=$k+1?',':'');
		}
		$charts.='],[';
		foreach($chart_all as $k=>$item){
			$charts .= '"'.$item['count'].'"'.(count($chart_all)!=$k+1?',':']]');
		}
		$result =array(
			'omset'=>"Rp ".number_format($get_orders['total']),
			'orders'=>$get_orders['penjualan'],
			'avg'=>"Rp ".number_format($get_orders['total']/$get_orders['penjualan']),
			'member'=>$get_members['member'],
			'charts'=>$charts
		);
		echo json_encode($result);

	}

	public function login_monitoring(){
		 $username = $this->input->post('username');
        $password = $this->input->post('password');

        $cek = $this->m_website->login($username);
        if($cek <> 0){
            if (password_verify($password, $cek->password)) {
				echo json_encode(array("status"=>1,"msg"=>"Berhasil."));
            } else {
                echo json_encode(array("status"=>0,"msg"=>"Password salah."));
            }
        } else {
			echo json_encode(array("status"=>0,"msg"=>"User tidak ditemukan."));
        }
	}
	
	public function laporan_monitoring(){
		$limit=isset($_GET['limit'])?$_GET['limit']:5;
		// number_format($row['sub_total']-$row['diskon']+$row['biaya']-$row['jumlah_voucher']) 
		$read_data = $this->m_crud->join_data("orders o", "o.id_orders, o.tgl_orders, 
		IF(o.status=1,IF(pb.status>=2,'Sudah dibayar.','Menunggu Pembayaran.'), IF(o.status=2,IF(p.no_resi='' OR p.no_resi=null,'Belum dikirim.','Sudah dikirim.'),IF(o.status=3,'Dalam Pengiriman Kurir.',IF(o.status=4,'Selesai.','Batal.')))) as status,
		(select count(do.orders) from det_orders do where do.orders=o.id_orders) item,
		CONCAT('Rp ',FORMAT( (SUM(dto.qty*(dto.hrg_jual+dto.hrg_varian))-SUM(dto.qty*dto.diskon)+p.biaya-IFNULL(pb.jumlah_voucher,0)),0)) total,
		m.nama, CONCAT(p.kurir,', ', p.service) kurir, CONCAT('Rp ',FORMAT(p.biaya,0)) ongkir, p.no_resi, CONCAT('Rp ',FORMAT(SUM(dto.qty*(dto.hrg_jual+dto.hrg_varian)),0)) sub_total, SUM(dto.qty*dto.diskon) diskon, pb.jumlah_voucher, pb.voucher", array("det_orders dto", "pengiriman p", "member m", "det_pembayaran dp", "pembayaran pb"), array("dto.orders=o.id_orders", "p.orders=o.id_orders", "m.id_member=o.member", "dp.orders=o.id_orders", "pb.id_pembayaran=dp.pembayaran"), null, "o.tgl_orders DESC", "o.id_orders", $limit);
		echo json_encode($read_data,JSON_PRETTY_PRINT);

	}

	public function cek_poin(){
//		var_dump($this->site);
		$read_data = $this->m_crud->read_data("poin","*");
		file_put_contents('member_poin.txt',json_encode($read_data));
		echo json_encode($read_data);
	}

	public function get_member_where(){
		$read_data = $this->m_crud->read_data("member","*",null,"ol_code DESC");
		file_put_contents('json_member.txt',json_encode($read_data));
		echo json_encode($read_data);
	}

	public function req() {
		$this->m_crud->create_data("log", array('jenis'=>'A','code'=>'B','tgl'=>date('Y-m-d H:i:s'), 'keterangan'=>'C','user'=>'D'));

		echo json_encode(array('status'=>true));
	}

	public function login() {
		$result = array();

		$email = $_POST['email'];
		$password = $_POST['password'];

		$get_user = $this->m_crud->get_data("member", "*, ifnull(telepon, 'Phone Number is Empty') tlp", "email='".$email."' AND status='1' AND verify='1'");

		if ($get_user != null) {/**/
			if (password_verify($password, $get_user['password'])) {
				$result['status'] = true;
				$result['res_login'] = array('id_member'=>$get_user['id_member'],'nama'=>strtoupper($get_user['nama']),'telepon'=>$get_user['tlp'],'register'=>$get_user['register'],'foto'=>base_url().$get_user['foto'],'kode_member'=>$get_user['ol_code']);
			} else {
				$result['status'] = false;
				$result['res_login'] = array('message'=>'Invalid email or password!');
			}
		} else {
			$result['status'] = false;
			$result['res_login'] = array('message'=>'Invalid email or password!');
		}

		echo json_encode($result);
	}

	public function register() {
		$result = array();

		$register = $_POST['register'];
		$email = $_POST['email'];
		$nama = $_POST['nama'];
		$id = md5($email);
		$id_member = '';

		$options = array('cost' => 12);
		$password = '-';

		$this->db->trans_begin();

		$check_tlp = null;
		$check_email = $this->m_crud->get_data("member", "id_member, verify, register", "email='" . $email . "'");

		if ($check_email == null) {
			if ($register == 'email') {
				$tlp = $_POST['tlp'];
				$check_tlp = $this->m_crud->get_data("member", "id_member", "telepon='".$tlp."'");
				if ($check_tlp != null) {
					$check_email = array('verify'=>1, 'register'=>'email');
				}
				$password = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);
			} else if ($register != '') {
				$id = $_POST['id'];
				$check_email = $this->m_crud->get_data("member", "id_member", "register='" . $register. "' AND id_register='".$id."'");
			} else {
				$check_email = null;
			}
		}

		$url = '-';

		if ($check_email == null) {
			$result['status'] = true;

			$ol_code = $this->m_website->generate_kode("member", date('ym'));

			$data_member = array(
				'email'=>$email,
				'password'=>$password,
				'nama'=>$nama,
				'telepon'=>$tlp,
				'status'=>'1',
				'foto'=>'assets/images/member/default.png',
				'tgl_register'=>date('Y-m-d H:i:s'),
				'verify'=>'0',
				'jenis_kelamin'=>$_POST['jk'],
				'tgl_lahir'=>date('Y-m-d', strtotime($_POST['tgl_lahir'])),
				'register'=>$register,
				'id_register'=>$id,
				'hash'=>password_hash($_POST['password'], PASSWORD_BCRYPT, $options),
				'ol_code' => $ol_code
			);
			$this->m_crud->create_data("member", $data_member);
		// 			$cek = $this->curl->simple_post('http://192.168.100.151:8080/workspace/netindo/boidknrm/api/insert_sample', array('ol_code'=>'894','nama'=>'teko','email'=>'teko@gmail.com','tlp1'=>'089568895'));
			// var_dump($cek);die();
			$this->m_website->request_api_interlocal('insert_sample','&ol_code='.$ol_code.'&nama='.$nama.'&email='.$email.'&telepon='.$tlp.'');

			$id_member = $this->db->insert_id();

			$data_customer = array(
				'param' => 'add',
				'kode' => $ol_code,
				'nama' => strtoupper($nama),
				'tlp' => $tlp,
				'tgl_lahir' => date('Y-m-d', strtotime($_POST['tgl_lahir'])),
				'alamat' => '-'
			);
			// $insert = $this->curl->simple_post('http://192.168.100.151:8080/workspace/netindo/boidknrm/api/insert_sample', $data_member);
			// var_dump($insert);die();
			// file_put_contents('insert_member.txt',json_encode(array("data"=>$data_member)));
			// file_get_contents("http://192.168.100.151:8080/workspace/netindo/boidknrm/api/insert_sample");

			$this->m_website->request_api('data_customer', $data_customer);
			$url = base_url().'api/email_confirmation/'.base64_encode($id_member).'/'.base64_encode($email);
		} else {
			if ($register == 'email') {
				$result['status'] = false;
				if ($check_email['verify'] == '0' && $check_email['register'] == 'email') {
					$url = base_url() . 'email_confirmation/' . base64_encode($check_email['id_member']).'/'.base64_encode($email);
					$result['res_register'] = array('res' => 1, 'email' => $email, 'url' => $url, 'message' => 'Please verify your email address!');
				} else if ($register != '') {
					if ($check_tlp != null) {
						$result['res_register'] = array('res' => 0, 'email' => $email, 'url' => $url, 'message' => 'Phone number already exist!');
					} else if ($check_email != null) {
						$result['res_register'] = array('res' => 0, 'email' => $email, 'url' => $url, 'message' => 'Email address already exist!');
					}
				} else {
					$result['res_register'] = array('res' => 0, 'email' => $email, 'url' => $url, 'message' => 'Registration failed!');
				}
			} else {
				$id_member = $check_email['id_member'];
				$result['status'] = true;
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();

			$result['status'] = false;
			$result['res_register'] = array('res'=>2, 'email'=>$email, 'url'=>$url, 'message'=>'Registration failed!');
		} else {
			$this->db->trans_commit();

			if ($result['status'] == true) {
				if ($register != 'email') {
					$get_member = $this->m_crud->get_data("member", "nama, foto, ol_code, ifnull(telepon, 'Phone Number is Empty') telepon", "id_member='".$id_member."'");
					$result['res_register'] = array('id_member' => $id_member, 'kode_member'=>$get_member['ol_code'], 'nama' => strtoupper($get_member['nama']), 'telepon' => $get_member['telepon'], 'foto' => base_url().$get_member['foto'], 'message' => 'Successful!');
				} else {
					if ($this->email_verification(base64_encode($email), base64_encode($url), 'register')) {
						$result['res_register'] = array('send_email' => 'success', 'email' => $email, 'url' => $url, 'message' => 'Registration successful, please check your email address!');
					} else {
						$result['res_register'] = array('send_email' => 'failed', 'email' => $email, 'url' => $url, 'message' => 'Registration successful, send email failed!');
					}
				}
			}
		}

		echo json_encode($result);
	}

	public function email_verification($email, $url, $param='resend') {
		$email = base64_decode($email);
		$url = base64_decode($url);

		$to = strip_tags($email);
		$subject = "Email Verification";

		$message = '
            <!doctype html>
            <html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
            
              <head>
                <title></title>
                <!--[if !mso]>
                  <!-- -->
                  <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <!--<![endif]-->
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <style type="text/css">
                  #outlook a { padding: 0; }
                  .ReadMsgBody { width: 100%; }
                  .ExternalClass { width: 100%; }
                  .ExternalClass * { line-height:100%; }
                  body { margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
                  table, td { border-collapse:collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
                  img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
                  p { display: block; margin: 13px 0; }
                </style>
                <!--[if !mso]>
                  <!-->
                  <style type="text/css">
                    @media only screen and (max-width:480px) {
                      @-ms-viewport { width:320px; }
                      @viewport { width:320px; }
                    }
                  </style>
                <!--<![endif]-->
                <!--[if mso]>
                  <xml>
                    <o:OfficeDocumentSettings>
                      <o:AllowPNG/>
                      <o:PixelsPerInch>96</o:PixelsPerInch>
                    </o:OfficeDocumentSettings>
                  </xml>
                <![endif]-->
                <!--[if lte mso 11]>
                  <style type="text/css">
                    .outlook-group-fix {
                      width:100% !important;
                    }
                  </style>
                <![endif]-->
                <!--[if !mso]>
                  <!-->
                  <link href="https://d2yjfm58htokf8.cloudfront.net/static/fonts/averta-v2.css" rel="stylesheet" type="text/css">
                  <style type="text/css">
                    @import url(https://d2yjfm58htokf8.cloudfront.net/static/fonts/averta-v2.css);
                  </style>
                <!--<![endif]-->
                <style type="text/css">
                  p {
                    margin: 0 0 24px 0;
                  }
            
                  a {
                    color: #00b9ff;
                  }
            
                  hr {
                    margin: 32px 0;
                    border-top: 1px #e2e6e8;
                  }
            
                  dt {
                    font-size: 13px;
                    margin-left: 0;
                  }
            
                  dd {
                    color: #37517e;
                    margin-bottom: 24px;
                    margin-left: 0;
                  }
            
                  h5 {
                    font-family: TW-Averta-SemiBold, Averta, Helvetica, Arial;
                    font-size: 16px;
                    line-height: 24px;
                    color: #2e4369;
                  }
            
                  pre {
                    display: block;
                    padding: 16px;
                    padding: 12px 24px;
                    margin: 0 0 48px;
                    font-size: 14px;
                    line-height: 24px;
                    color: #4a5860;
                    word-break: break-all;
                    word-wrap: break-word;
                    background-color: #f2f5f7;
                    border-radius: 3px;
                  }
            
                  .body-wrapper {
                background: #f2f5f7 url("https://d2yjfm58htokf8.cloudfront.net/static/images/background-v1.png") no-repeat center top;
                    padding: 0px;
                    margin: auto;
                  }
            
                  .content-wrapper {
                    max-width: 536px;
                    padding: 32px;
                    padding-bottom: 48px;
                  }
            
                  .footer-wrapper div {
                    color: #37517e !important;
                  }
            
                  .footer-wrapper div a {
                    color: #00b9ff !important;
                  }
            
                  .hero {
                    font-family: TW-Averta-Bold, Averta, Helvetica, Arial;
                    color: #37517e;
                    font-size: 22px;
                    line-height: 30px;
                  }
            
                  .page-header {
                    border-bottom: 1px solid #eaebed;
                    padding-bottom: 16px;
                  }
            
                  .mb-0 {
                    margin-bottom: 0 !important;
                  }
            
                  .mt-0 {
                    margin-top: 0 !important;
                  }
            
                  .btn {
                    box-sizing: border-box;
                    display: inline-block;
                    min-height: 36px;
                    padding: 12px 24px;
                    margin: 0 0 24px;
                    font-size: 16px;
                    font-weight: 600;
                    line-height: 24px;
                    text-align: center;
                    white-space: nowrap;
                    vertical-align: middle;
                    cursor: pointer;
                    border: 0;
                    border-radius: 3px;
                    color: #fff;
                    background-color: #00b9ff;
                    text-decoration: none;
            
                    -webkit-transition: all .15s ease-in-out;
                    -o-transition: all .15s ease-in-out;
                    transition: all .15s ease-in-out;
                  }
            
                  .btn:hover {
                    background-color: #00a4df;
                  }
            
                  .btn:active {
                    background-color: #008ec0;
                  }
            
                  @media screen and (min-width: 576px) and (max-width: 768px) {
                    .body-wrapper {
                      padding: 24px !important;
                    }
            
                    .content-wrapper {
                      max-width: 504px !important;
                      padding: 48px !important;
                    }
                  }
            
                  @media screen and (min-width: 768px) {
                    .body-wrapper {
                      padding: 48px !important;
                    }
            
                    .content-wrapper {
                      max-width: 456px !important;
                      padding: 72px !important;
                      padding-top: 48px !important;
                    }
                  }
                </style>
                <style type="text/css">
                  @media only screen and (min-width:480px) {
                    .mj-column-per-100 { width:100%!important; }
                  }
                </style>
              </head>
            
              <body>
                <div class="mj-container body-wrapper">
                  <!--[if mso | IE]>
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600"
                    align="center" style="width:600px;">
                      <tr>
                        <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
                        <![endif]-->
                        <div style="margin:0px auto;max-width:600px;background:#fff;" class="content-wrapper"
                        data-class="content-wrapper">
                          <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:#fff;"
                          align="center" border="0">
                            <tbody>
                              <tr>
                                <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:0px;">
                                  <!--[if mso | IE]>
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                      <tr>
                                        <td style="width:600px;">
                                        <![endif]-->
                                        <div style="margin:0px auto;max-width:600px;">
                                          <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;"
                                          align="center" border="0">
                                            <tbody>
                                              <tr>
                                                <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:0px;">
                                                  <!--[if mso | IE]>
                                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                      <tr>
                                                        <td style="vertical-align:middle;width:600px;">
                                                        <![endif]-->
                                                        <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:middle;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                                                          <table role="presentation" cellpadding="0" cellspacing="0" style="vertical-align:middle;"
                                                          width="100%" border="0">
                                                            <tbody>
                                                              <tr>
                                                                <td style="word-wrap:break-word;font-size:0px;padding:0px;" align="center">
                                                                  <table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;"
                                                                  align="center" border="0">
                                                                    <tbody>
                                                                      <tr>
                                                                        <td style="width:150px;">
                                                                          <img alt="Logo" title="" height="auto" src="http://www.indokids.co.id/image/catalog/logo.png"
                                                                          style="border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;"
                                                                          width="150">
                                                                        </td>
                                                                      </tr>
                                                                    </tbody>
                                                                  </table>
                                                                </td>
                                                              </tr>
                                                            </tbody>
                                                          </table>
                                                        </div>
                                                        <!--[if mso | IE]>
                                                        </td>
                                                      </tr>
                                                    </table>
                                                  <![endif]-->
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </div>
                                        <!--[if mso | IE]>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="width:600px;">
                                        <![endif]-->
                                        <div style="margin:0px auto;max-width:600px;">
                                          <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;"
                                          align="center" border="0">
                                            <tbody>
                                              <tr>
                                                <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:0px;">
                                                  <!--[if mso | IE]>
                                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                      <tr>
                                                        <td style="vertical-align:top;width:600px;">
                                                        <![endif]-->
                                                        <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                                                          <table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                                                            <tbody>
                                                              <tr>
                                                                <td style="word-wrap:break-word;font-size:0px;padding:0px;">
                                                                  <div style="font-size:1px;line-height:48px;white-space:nowrap;"> </div>
                                                                </td>
                                                              </tr>
                                                            </tbody>
                                                          </table>
                                                        </div>
                                                        <!--[if mso | IE]>
                                                        </td>
                                                      </tr>
                                                    </table>
                                                  <![endif]-->
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </div>
                                        <!--[if mso | IE]>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="width:600px;">
                                        <![endif]-->
                                        <div style="margin:0px auto;max-width:600px;">
                                          <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;"
                                          align="center" border="0">
                                            <tbody>
                                              <tr>
                                                <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:0px;">
                                                  <!--[if mso | IE]>
                                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                      <tr>
                                                        <td style="vertical-align:top;width:600px;">
                                                        <![endif]-->
                                                        <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                                                          <table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                                                            <tbody>
                                                              <tr>
                                                                <td style="word-wrap:break-word;font-size:0px;padding:0px;" align="left">
                                                                  <div style="cursor:auto;color:#5d7079;font-family:TW-Averta-Regular, Averta, Helvetica, Arial;font-size:16px;line-height:24px;letter-spacing:0.4px;text-align:left;">
                                                                    <p>Halo,</p>
                                                                    <p class="hero">Waktunya untuk mengkonfirmasi alamat email anda.</p>
                                                                    <p>Untuk dapat melakukan transaksi online di Indokids anda harus melakukan konfirmasi email terlebih dahulu, silahkan klik tombol dibawah ini untuk konfirmasi alamat email anda.</p>
                                                                    <p>
                                                                      <a href="'.$url.'" class="btn" mc:disable-tracking="">Konfirmasi Alamat Email</a>
                                                                    </p>
                                                                    <hr style="margin-top: 56px">
                                                                    <p class="mb-0">Terimakasih,</p>
                                                                    <p class="mb-0">Indokids</p>
                                                                  </div>
                                                                </td>
                                                              </tr>
                                                            </tbody>
                                                          </table>
                                                        </div>
                                                        <!--[if mso | IE]>
                                                        </td>
                                                      </tr>
                                                    </table>
                                                  <![endif]-->
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </div>
                                        <!--[if mso | IE]>
                                        </td>
                                      </tr>
                                    </table>
                                  <![endif]-->
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                        <!--[if mso | IE]>
                        </td>
                      </tr>
                    </table>
                  <![endif]-->
                </div>
              </body>
            
            </html>
        ';

		$headers = "From: " . strip_tags('no-reply@indokids.co.id') . "\r\n";
		//$headers .= "CC: agrowisata_n8@yahoo.com \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		if (mail($to,$subject,$message,$headers) == true) {
			if ($param == 'resend') {
				echo json_encode(array('status'=>true, 'message'=>"Send email successful!"));
			} else {
				return true;
			}
		} else {
			if ($param == 'resend') {
				echo json_encode(array('status'=>false, 'message'=>"Send email failed!"));
			} else {
				return false;
			}
		}
	}

	public function email_confirmation($id_member,$email) {
		$new_password = $this->random_char();
		$this->debug(base64_decode($email),$new_password);
		// $new_password = $this->random_char();
		// $this->m_website->email_new_account(array('email' => $email, 'password' => $new_password));
		// $result = array();
		// $id_member = base64_decode($id_member);
		// var_dump($id_member);
		// $check_email = $this->m_crud->get_data("member", "id_member", "verify='0' AND id_member='".$id_member."'");
		// if ($check_email != null) {
		//     $this->db->trans_begin();

		$this->m_crud->update_data("member", array('verify'=>'1'), "id_member='".$id_member."'");

		//     if ($this->db->trans_status() === FALSE) {
		//         $this->db->trans_rollback();

		//         $result['status'] = false;
		//         $this->load->view('site/email_failed');
		//     } else {
		//         $this->db->trans_commit();

		//         $result['status'] = true;
		//         $this->load->view('site/email_success');
		//     }
		// } else {
		//     $result['status'] = false;
		//     $this->load->view('site/email_failed');
		// }
	}

	public function get_album() {
		$result = array();

		$get_album = $this->m_crud->read_data("album", "id_album, nama, CONCAT('".base_url()."', gambar) gambar");

		if ($get_album != null) {
			$result['res_album'] = $get_album;
			$result['status'] = true;
		} else {
			$result['status'] = false;
		}

		echo json_encode($result);
	}

	public function get_galeri() {
		$result = array();
		$id_album = $_POST['id_album'];

		$get_galeri = $this->m_crud->read_data("galeri", "id_galeri, nama, CONCAT('".base_url()."', gambar) gambar", "album = '".$id_album."'");

		if ($get_galeri != null) {
			$result['res_galeri'] = $get_galeri;
			$result['status'] = true;
		} else {
			$result['status'] = false;
		}

		echo json_encode($result);
	}

	public function get_group() {
		$result = array();

		$read_group = $this->m_crud->read_data("groups", "id_groups, nama, gambar", "status='1'");
		if ($read_group!=null) {
			$result['status'] = true;
			$res_group = array();
			foreach ($read_group as $row) {
				array_push($res_group, array('id_group'=>$row['id_groups'], 'nama'=>$this->m_website->replace_kutip($row['nama'],'restore'), 'gambar'=>base_url().$row['gambar']));
			}
			$result['res_group'] = $res_group;
		} else {
			$result['status'] = false;
		}

		echo json_encode($result);
	}

	public function get_kelompok() {
		$group = $_POST['group'];
		$result = array();

		$read_kelompok = $this->m_crud->read_data("kelompok", "id_kelompok, nama, gambar", "status='1' AND groups='".$group."'");
		if ($read_kelompok!=null) {
			$result['status'] = true;
			$res_kelompok = array();
			foreach ($read_kelompok as $row) {
				array_push($res_kelompok, array('id_kelompok'=>$row['id_kelompok'], 'nama'=>$this->m_website->replace_kutip($row['nama'],'restore'), 'gambar'=>base_url().$row['gambar']));
			}
			$result['res_kelompok'] = $res_kelompok;
		} else {
			$result['status'] = false;
		}

		echo json_encode($result);
	}

	public function get_merk() {
		$result = array();

		$read_merk = $this->m_crud->read_data("merk", "id_merk, nama, gambar", "status='1'");
		if ($read_merk!=null) {
			$result['status'] = true;
			$res_merk = array();
			foreach ($read_merk as $row) {
				array_push($res_merk, array('id_merk'=>$row['id_merk'], 'nama'=>$this->m_website->replace_kutip($row['nama'], 'restore'), 'gambar'=>base_url().$row['gambar']));
			}
			$result['res_merk'] = $res_merk;
		} else {
			$result['status'] = false;
		}

		echo json_encode($result);
	}

	public function get_image_slider() {
		$result = array();
		$gambar = array();
		for ($i=0; $i<3; $i++) {
			array_push($gambar, base_url().'assets/images/no_image.png');
		}

		$result['status'] = true;
		$result['gambar'] = $gambar;

		echo json_encode($result);
	}

	/*{"kelompok":"kl","nama":"nm","merk":"mr","free_return":"fr","pre_order":"po","harga1":"h1","harga2":"h2"}*/
	public function get_produk() {
		$result = array();
		$member = $_POST['member'];
		$filter = json_decode($_POST['filter'], true);
		$limit = isset($_POST['limit'])?$_POST['limit']:0;
		$page = isset($_POST['page'])?$_POST['page']:1;
		$where = null;
		$order = null;

		if (isset($filter['kelompok']) && $filter['kelompok']!='') {
			$kelompok = $filter['kelompok'];
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
		} else if (isset($filter['harga1']) && $filter['harga1']!='') {
			($where!=null)?$where.=' AND ':null;
			$where.=' dp.hrg_jual >= CAST('.$harga1.' AS DECIMAL)';
		} else if (isset($filter['harga2']) && $filter['harga2']!='') {
			($where!=null)?$where.=' AND ':null;
			$where.=' dp.hrg_jual <= CAST('.$harga2.' AS DECIMAL)';
		}

		if (isset($filter['produk_baru']) && $filter['produk_baru']!='') {
			$order = "pr.tgl_input DESC";
		} else {
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

		$read_produk = $this->m_crud->join_data("produk pr", "pr.id_produk, pr.nama nama_produk, pr.code, pr.deskripsi, pr.free_return, pr.pre_order, pr.kelompok, dp.hrg_beli, dp.berat, dp.hrg_jual, mr.nama nama_merk, kl.nama nm_kelompok, mr.gambar gambar_merk, COUNT(ul.orders) ulasan, COUNT(dk.comment) diskusi, IFNULL(AVG(ul.rating_produk), 0) rating, ifnull(SUM(ks.stok_in-ks.stok_out), 0) stok", $table_join, $join_on, $where.$where_merk, $order, "pr.id_produk", $limit, $limit*($page-1));
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
		} else {
			$result['status'] = false;
		}

		echo json_encode($result);
	}

	public function favorit() {
		$result = array();
		$produk = $_POST['id_produk'];
		if (isset($_POST['member'])) {
			$member = $_POST['member'];
		} else {
			$member = $this->user;
		}

		$get_data = $this->m_crud->get_data("favorit", "id_favorit", "member='".$member."' AND produk='".$produk."'");

		if ($get_data == null) {
			$data_favorit = array(
				'member' => $member,
				'produk' => $produk,
				'tgl_favorit' => date('Y-m-d H:i:s')
			);
			$this->m_crud->create_data("favorit", $data_favorit);
		} else {
			$this->m_crud->delete_data("favorit", "id_favorit='".$get_data['id_favorit']."'");
		}

		$result['status'] = true;
		echo json_encode($result);
	}

	public function get_ukuran() {
		$result = array();
		$produk = $_POST['produk'];

		$get_ukuran = $this->m_crud->read_data("det_produk", "ukuran", "produk='".$produk."'", "id_det_produk ASC", "ukuran");

		if ($get_ukuran == null) {
			$result['status'] = false;
		} else {
			$result['status'] = true;
			$result['res_ukuran'] = $get_ukuran;
		}

		echo json_encode($result);
	}

	public function feedback() {
		$response = array();

		$this->db->trans_begin();

		$this->m_crud->create_data(
		    "feedback", array("tanggal"=>date('Y-m-d H:i:s'), "member"=>$this->input->post("member",true), "pesan"=>$this->input->post("pesan",true)));

		if ($this->db->trans_status() === true) {
			$this->db->trans_commit();
			$response['pesan'] = 'Terimakasih';
			$response['status'] = true;
		} else {
			$this->db->trans_rollback();
			$response['pesan'] = 'Gagal menyimpan, ulangi lagi.';
			$response['status'] = false;
		}

		echo json_encode($response);
	}

	public function get_warna() {
		$result = array();
		$produk = $_POST['produk'];
		$ukuran = $_POST['ukuran'];

		$get_warna = $this->m_crud->read_data("det_produk", "id_det_produk, warna", "produk='".$produk."' AND ukuran='".$ukuran."'", "id_det_produk ASC", "warna");

		if ($get_warna == null) {
			$result['status'] = false;
		} else {
			$result['status'] = true;
			$result['res_warna'] = $get_warna;
		}

		echo json_encode($result);
	}

	public function get_harga_varian() {
		$result = array();
		$det_produk = $_POST['det_produk'];

		$get_harga = $this->m_crud->get_data("det_produk", "hrg_varian", "id_det_produk='".$det_produk."'")['hrg_varian'];
		if ($get_harga != null) {
			$result['status'] = true;
			$result['res_harga'] = $get_harga;
		} else {
			$result['status'] = false;
		}

		echo json_encode($result);
	}

	public function cek_resi() {
		$resi = $this->m_website->rajaongkir_resi(json_encode(array('resi'=>$_POST['resi'], 'kurir'=>$_POST['kurir'])));

		$decode = json_decode($resi, true);
		$status_resi = $decode['rajaongkir']['status']['code'];

		$result = $decode['rajaongkir']['result'];
		$delivered = $result['delivered'];
		$summary = $result['summary'];
		$details = $result['details'];
		$manifest = $result['manifest'];

		/*foreach ($manifest as $row) {
			echo $row['manifest_date'].' '.$row['manifest_time'].' ~ '.$row['manifest_description'].'<br>';
		}*/

		//echo json_encode($details);
		echo $resi;
	}

	public function get_ongkir() {

		$data = array(
			'tujuan' => $_POST['kecamatan'],
			'berat' => (int)$_POST['berat']*(int)$_POST['jumlah'],
			'kurir' => $_POST['kurir']
		);
		$req_api = $this->m_website->rajaongkir_cost(json_encode($data));
		$req_api_local = $this->m_website->local_ongkir($this->m_crud->get_data("setting","harga_cod")['harga_cod']);
		$decode_local = json_decode($req_api_local,true);
		$decode = json_decode($req_api, true);
		$res_rajaongkir = $decode['rajaongkir']['results'][0];
		$res_localongkir = $decode_local['rajaongkir']['results'][0];
		if($_POST['kurir'] != 'COD'){
			echo json_encode($res_rajaongkir);
		}else{
			echo json_encode($res_localongkir);
		}
	}

	public function alamat_member() {
		$member = $_POST['member'];
		$read_data = $this->m_crud->join_data("alamat_member am", "am.id_alamat_member, am.nama nama_alamat, am.alamat, am.provinsi, am.kota, am.penerima, am.telepon, am.kecamatan, kcr.kecamatan nama_kecamatan, pr.provinsi nama_provinsi, kr.tipe, kr.kota nama_kota", array("provinsi_rajaongkir pr", "kota_rajaongkir kr", "kecamatan_rajaongkir kcr"), array("am.provinsi=pr.provinsi_id", "am.kota=kr.kota_id", "am.kecamatan=kcr.kecamatan_id"), "am.member='".$member."'");

		$status = true;
		if ($read_data == null) {
			$status = false;
		}

		$res = array(
			'status' => $status,
			'data' => $read_data
		);

		echo json_encode($res);
	}

	public function get_alamat($id) {
		$read_data = $this->m_crud->join_data("alamat_member am", "am.id_alamat_member, am.nama nama_alamat, am.alamat, am.provinsi, am.kota, ifnull(am.kecamatan, kcr.kecamatan_id) kecamatan, am.penerima, am.telepon, pr.provinsi nama_provinsi, kr.tipe, kr.kota nama_kota, kcr.kecamatan nama_kecamatan", array("provinsi_rajaongkir pr", "kota_rajaongkir kr", "kecamatan_rajaongkir kcr"), array("am.provinsi=pr.provinsi_id", "am.kota=kr.kota_id", "kcr.kota=kr.kota_id"), "am.id_alamat_member='".$id."'");

		echo json_encode($read_data[0]);
	}

	public function read_alamat() {
		$get_alamat = $this->m_crud->read_data("alamat_member", "id_alamat_member, nama", "status='1' AND member='".$_POST['member']."'");

		$status = true;
		if ($get_alamat == null) {
			$status = false;
		}

		echo json_encode(array('status'=>$status, 'data'=>$get_alamat));
	}
    public function delete_alamat() {
        $data = $this->m_crud->delete_data("alamat_member", "id_alamat_member='".$this->input->post('id')."'");

        $status = false;
        if ($data) {
            $status = true;
//            $this->session->set_userdata("isActiveAddress");
            $this->session->set_userdata(array("isActiveAddress"=>true));

        }else{
            $status = false;
        }

        echo json_encode(array('status'=>$status));
    }

	public function to_cart() {
		$result = array();
		$this->db->trans_begin();

		$this->reset_pembayaran();
		$member = $_POST['member'];
		$nama_penerima = $_POST['nama_penerima'];
		$tlp_penerima = $_POST['tlp_penerima'];
		$kd_kota = $_POST['kd_kota'];
		$kota = $_POST['kota'];
		$kd_prov = $_POST['kd_prov'];
		$prov = $_POST['prov'];
		$kd_alamat = $_POST['kd_alamat'];
		$alamat = $_POST['alamat'];
		$kurir = $_POST['kurir'];
		$layanan_kurir = $_POST['layanan_kurir'];
		$ongkir = $_POST['ongkir'];
		$det_produk = $_POST['det_produk'];
		$berat = (int)$_POST['berat'];
		$jumlah = (int)$_POST['jumlah'];
		$hrg_beli = (float)$_POST['hrg_beli'];
		$hrg_jual = (float)$_POST['hrg_jual'];
		$hrg_coret = (float)$_POST['hrg_coret'];
		$hrg_varian = (float)$_POST['hrg_varian'];
		$tgl = date('Y-m-d H:i:s');
		$code = 'CART/'.$member;

		if ($hrg_coret==0) {
			$diskon = 0;
		} else {
			$diskon = ($hrg_coret+$hrg_varian)-$hrg_jual;
		}

		$get_cart = $this->m_crud->get_data("orders", "id_orders", "id_orders='".$code."' AND status='0'");
		if ($get_cart == null) {
			$data_order = array(
				'id_orders' => $code,
				'tgl_orders' => $tgl,
				'tipe' => '1',
				'member' => $member,
				'status' => '0'
			);
			$this->m_crud->create_data("orders", $data_order);

			$det_order = array(
				'orders' => $code,
				'det_produk' => $det_produk,
				'qty' => $jumlah,
				'berat' => $berat,
				'hrg_beli' => $hrg_beli,
				'hrg_jual' => $hrg_jual+$diskon-$hrg_varian,
				'hrg_varian' => $hrg_varian,
				'diskon' => $diskon,
				'charge' => '0'
			);
			$this->m_crud->create_data("det_orders", $det_order);
		} else {
			$get_produk = $this->m_crud->get_data("det_orders", "qty", "orders='".$code."' AND det_produk='".$det_produk."'");
			if ($get_produk == null) {
				$det_order = array(
					'orders' => $code,
					'det_produk' => $det_produk,
					'qty' => $jumlah,
					'berat' => $berat,
					'hrg_beli' => $hrg_beli,
					'hrg_jual' => $hrg_jual+$diskon-$hrg_varian,
					'hrg_varian' => $hrg_varian,
					'diskon' => $diskon,
					'charge' => '0'
				);
				$this->m_crud->create_data("det_orders", $det_order);
			} else {
				$this->m_crud->update_data("det_orders", array('qty'=>(int)$get_produk['qty']+$jumlah), "orders='".$code."' AND det_produk='".$det_produk."'");
			}
		}

		/*$get_pengiriman = $this->m_crud->get_data("pengiriman", "id_pengiriman", "orders='".$code."'");
		if ($get_pengiriman == null) {
			$data_pengiriman = array(
				'id_pengiriman' => 'DO/'.$code,
				'orders' => $code,
				'penerima' => $nama_penerima,
				'alamat' => $alamat,
				'id_provinsi' => $kd_prov,
				'provinsi' => $prov,
				'id_kota' => $kd_kota,
				'kota' => $kota,
				'telepon' => $tlp_penerima,
				'kurir' => $kurir,
				'service' => $layanan_kurir,
				'biaya' => $ongkir
			);
			$this->m_crud->create_data("pengiriman", $data_pengiriman);
		} else {
			$this->update_ongkir(base64_encode($code));
		}*/

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$result['status'] = false;
		} else {
			$this->db->trans_commit();
			$result['status'] = true;
			$result['count'] = $this->m_crud->count_data_join("orders o", "o.id_orders", "det_orders do", "do.orders=o.id_orders", "o.status='0' AND o.member='".$this->user."'");
		}

		echo json_encode($result);
	}

	public function edit_cart() {
		$result = array();
		if ($_POST['jumlah']==0) {
			$jumlah = 1;
		} else {
			$jumlah = $_POST['jumlah'];
		}
		$data_order = array('qty'=>$jumlah);
		$this->db->trans_begin();
		$get_grosir = $this->m_crud->join_data("grosir g", "g.*, dp.hrg_jual hrg_normal", array("produk p", "det_produk dp"), array("p.id_produk=g.produk", "dp.produk=p.id_produk"), "dp.id_det_produk='".$_POST['det_produk']."'");
		if ($get_grosir != null) {
			$harga = 0;
			for ($x = 0; $x < count($get_grosir); $x++) {
				if ($jumlah >= $get_grosir[$x]['qty1'] && $jumlah <= $get_grosir[$x]['qty2']) {
					$harga = $get_grosir[$x]['hrg_jual'];
					break;
				} else {
					if ($jumlah <= 1) {
						$harga = $get_grosir[$x]['hrg_normal'];
					} else if ($jumlah > $get_grosir[$x]['qty2']) {
						$harga = $get_grosir[$x]['hrg_jual'];
					}
				}
			}

			$data_order['hrg_jual'] = $harga;
		}
//		$this->reset_pembayaran();
		$this->m_crud->update_data("det_orders", $data_order, "orders='".$_POST['kd_trans']."' AND det_produk='".$_POST['det_produk']."'");

		$this->update_ongkir(base64_encode($_POST['kd_trans']));

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$result['status'] = false;
			$result['data']=$_POST['jumlah'];
		} else {
			$this->db->trans_commit();
			$result['status'] = true;
            $result['data']=$_POST['jumlah'];
		}

		echo json_encode($result);
	}

	public function edit_pengiriman() {
		$result = array();
		$this->db->trans_begin();

		$this->reset_pembayaran();
		$nama_penerima = $_POST['nama_penerima'];
		$tlp_penerima = $_POST['tlp_penerima'];
		$kd_kota = $_POST['kd_kota'];
		$kota = $_POST['kota'];
		$kd_prov = $_POST['kd_prov'];
		$prov = $_POST['prov'];
		$kd_alamat = $_POST['kd_alamat'];
		$alamat = $_POST['alamat'];
		$kurir = $_POST['kurir'];
		$layanan_kurir = $_POST['layanan_kurir'];
		$ongkir = $_POST['ongkir'];
		$kd_pengiriman = $_POST['kd_pengiriman'];
		$kd_orders = $_POST['kd_orders'];
		$code = 'CART/'.$kd_alamat.'/'.$kurir.'/'.str_replace(' ', '', $layanan_kurir);

		$data_pengiriman = array(
			'id_pengiriman' => 'DO/'.$code,
			'penerima' => $nama_penerima,
			'alamat' => $alamat,
			'id_provinsi' => $kd_prov,
			'provinsi' => $prov,
			'id_kota' => $kd_kota,
			'kota' => $kota,
			'telepon' => $tlp_penerima,
			'kurir' => $kurir,
			'service' => $layanan_kurir,
			'biaya' => $ongkir
		);
		$this->m_crud->update_data("pengiriman", $data_pengiriman, "id_pengiriman='".$kd_pengiriman."'");

		$this->m_crud->update_data("orders", array('id_orders'=>$code), "id_orders='".$kd_orders."'");

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$result['status'] = false;
		} else {
			$this->db->trans_commit();
			$result['status'] = true;
		}

		echo json_encode($result);
	}

	public function reset_pembayaran() {
		$member = $this->user;
		$cek_data = $this->m_crud->get_data("pembayaran", "id_pembayaran", "member='".$member."' AND status = '0'");

		if ($cek_data != null) {
			$this->m_crud->delete_data("pembayaran", "id_pembayaran='".$cek_data['id_pembayaran']."'");
		}
	}

	public function update_ongkir($orders) {
		$orders = base64_decode($orders);
		$get_pengiriman = $this->m_crud->get_data("pengiriman", "id_pengiriman, id_kota, kurir, service", "orders='".$orders."'");
		if ($get_pengiriman != null) {
			$berat = (int)$this->m_crud->get_data("det_orders", "SUM(berat*qty) berat", "orders='" . $orders . "'")['berat'];
			$cek_ongkir = array(
				'tujuan' => $get_pengiriman['id_kota'],
				'berat' => $berat,
				'kurir' => strtolower($get_pengiriman['kurir'])
			);
			$req_api = $this->m_website->rajaongkir_cost(json_encode($cek_ongkir));
			$decode = json_decode($req_api, true);
			$biaya = $decode['rajaongkir']['results'][0]['costs'];
			foreach ($biaya as $row => $value) {
				if (strtoupper($get_pengiriman['service']) == strtoupper($value['service'])) {
					$ongkir = $value['cost'][0]['value'];
					break;
				}
			}
			$this->m_crud->update_data("pengiriman", array('biaya' => $ongkir), "id_pengiriman='" . $get_pengiriman['id_pengiriman'] . "'");
		}
	}

	public function get_cart() {
		$result = array();
		$member = $_POST['member'];

		$get_cart = $this->m_crud->read_data("orders", "id_orders, tgl_orders", "member='".$member."' AND status='0'");

		if ($get_cart == null) {
			$result['status'] = false;
		} else {
			$result['status'] = true;
			$result['res_cart'] = $get_cart;
		}

		echo json_encode($result);
	}

	public function get_item_cart($param=null) {
		$result = array();
		if ($param == null) {
			$orders = json_decode($_POST['orders'], true);
		} else {
			$orders = json_decode($param, true);
		}

		$cart = array();
		foreach ($orders as $row) {
			$id = str_replace("_", "/", $row);

			$get_produk = $this->m_crud->join_data("det_orders do", "do.det_produk, do.qty, do.berat, do.catatan, do.hrg_jual, do.hrg_varian, do.diskon, p.id_produk, p.nama nama_produk, p.code sku, dp.ukuran, dp.warna, (SELECT ifnull(SUM(stok_in-stok_out), 0) FROM kartu_stok WHERE produk=p.id_produk) stok", array("det_produk dp", "produk p"), array("dp.id_det_produk=do.det_produk", "p.id_produk=dp.produk"), "do.orders='".$id."'");
			$get_pengiriman = $this->m_crud->get_data("pengiriman", "*", "orders='".$id."'");

			$cart_list = array();
			$data_produk = array();
			$data_pengiriman = array();
			if ($get_produk!=null) {
				foreach ($get_produk as $row_produk) {
					$list_produk = array(
						'det_produk' => $row_produk['det_produk'],
						'qty' => $row_produk['qty'],
						'berat' => $row_produk['berat'],
						'hrg_jual' => $row_produk['hrg_jual'],
						'hrg_varian' => $row_produk['hrg_varian'],
						'diskon' => $row_produk['diskon'],
						'id_produk' => $row_produk['id_produk'],
						'catatan' => $row_produk['catatan'],
						'nama_produk' => $row_produk['nama_produk'].' ('.$row_produk['ukuran'].' ~ '.$row_produk['warna'].')',
						'sku' => $row_produk['sku'],
						/*'stok' => $row_produk['stok']*/
						'stok' => 99
					);
					/*Get gambar produk*/
					$read_gambar = $this->m_crud->read_data("gambar_produk", "gambar", "produk='" . $row_produk['id_produk'] . "'");
					$gambar_produk = array();
					if ($read_gambar != null) {
						foreach ($read_gambar as $row_gambar) {
							array_push($gambar_produk, base_url() . $row_gambar['gambar']);
						}
					} else {
						array_push($gambar_produk, base_url() . 'assets/images/no_image.png');
					}
					$list_produk['gambar_produk'] = $gambar_produk;

					array_push($data_produk, $list_produk);
				}
			}

			if ($get_pengiriman != null) {
				$data_pengiriman = $get_pengiriman;
			}

			$cart_list['orders'] = $id;
			$cart_list['id'] = $row;
			$cart_list['list_produk'] = $data_produk;
			$cart_list['list_pengiriman'] = $data_pengiriman;
			array_push($cart, $cart_list);
		}

		$result['res_cart'] = $cart;
		$result['status'] = true;

		if ($param == null) {
			echo json_encode($result);
		} else {
			return json_encode($result);
		}
	}

	public function delete_cart() {
		$result = array();
		$orders = $_POST['orders'];

		$this->db->trans_begin();

		$this->m_crud->delete_data("orders", "id_orders='".$orders."'");

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$result['status'] = false;
		} else {
			$this->db->trans_commit();
			$result['status'] = true;
			$result['count'] = $this->m_crud->count_data_join("orders o", "o.id_orders", "det_orders do", "do.orders=o.id_orders", "o.status='0' AND o.member='".$this->user."'");
		}

		echo json_encode($result);
	}

	public function delete_item_cart() {
		$result = array();
		$orders = $_POST['orders'];
		$produk = $_POST['produk'];

		$this->db->trans_begin();

		$this->m_crud->delete_data("det_orders", "orders='".$orders."' AND det_produk='".$produk."'");

		$check_data = $this->m_crud->count_data("det_orders", "orders", "orders='".$orders."'");
		if ($check_data == 0) {
			$this->m_crud->delete_data("orders", "id_orders='".$orders."'");
		} else {
			$this->update_ongkir(base64_encode($orders));
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$result['status'] = false;
		} else {
			$this->db->trans_commit();
			$result['status'] = true;
			$result['count'] = $this->m_crud->count_data_join("orders o", "o.id_orders", "det_orders do", "do.orders=o.id_orders", "o.status='0' AND o.member='".$this->user."'");
		}

		echo json_encode($result);
	}

	public function checkout() {
		$result = array();

		$member = $this->user;

		$this->db->trans_begin();

		$cek_pembayaran = $this->m_crud->get_data("pembayaran", "id_pembayaran", "member='".$member."' AND status='0'");

		if ($cek_pembayaran != null) {
			$this->m_crud->delete_data("pembayaran", "id_pembayaran='" . $cek_pembayaran['id_pembayaran'] . "'");
		}

		$code_pembayaran = 'TF/' . $this->m_website->date_romawi() . '/' . $member;
		$data_pembayaran = array(
			'id_pembayaran' => $code_pembayaran,
			'member' => $member,
			'tgl_bayar' => date('Y-m-d H:i:s'),
			'bank' => '-',
			'no_rek' => '-',
			'atas_nama' => '-',
			'jumlah' => 0,
			'kode_unik' => 0,
			'bank_tujuan' => '-',
			'no_rek_tujuan' => '-',
			'atas_nama_tujuan' => '-',
			'status' => '0'
		);
		$this->m_crud->create_data("pembayaran", $data_pembayaran);

		$get_orders = $this->m_crud->read_data("orders o", "o.id_orders", "o.member='" . $member . "' AND o.status = '0'");
		foreach ($get_orders as $row) {
			$this->m_crud->create_data("det_pembayaran", array('pembayaran' => $code_pembayaran, 'orders' => $row['id_orders']));
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$result['status'] = false;
		} else {
			$this->db->trans_commit();
			$result['status'] = true;
			$result['code'] = $code_pembayaran;
		}

		echo json_encode($result);
	}

	public function bayar() {
		$result = array();

		$member = $this->user;
		$jumlah = (float)$_POST['total_tagihan'];
		$bank2 = $_POST['bank2'];
		$bank = $_POST['bank_tujuan'];
		$rekening = $_POST['rekening_tujuan'];
		$pemilik = $_POST['pemilik_tujuan'];
		$bank1 = $_POST['bank1'];
		$bank_pengirim = $_POST['data_bank'];
		$rekening_pengirim = $_POST['nomor_rekening_pengirim'];
		$pemilik_pengirim = $_POST['nama_pemilik_pengirim'];

		$kode_unik = 10;
		$param = true;
		while ($param) {
			$kode_unik = mt_rand( 10, 999 );
			$cek_kode_unik = $this->m_crud->get_data("pembayaran", "id_pembayaran", "jumlah=".$jumlah." AND kode_unik=".$kode_unik." AND status IN ('0', '1')");
			if ($cek_kode_unik == null) {
				$param = false;
			} else {
				$param = true;
			}
		}

		$this->db->trans_begin();

		$this->reset_pembayaran();
		$code_pembayaran = 'TF/'.$this->m_website->date_romawi().'/'.$member;
		$data_pembayaran = array(
			'id_pembayaran' => $code_pembayaran,
			'member' => $member,
			'tgl_bayar' => date('Y-m-d H:i:s'),
			'bank2' => $bank2,
			'bank_tujuan' => $bank,
			'no_rek_tujuan' => $rekening,
			'atas_nama_tujuan' => $pemilik,
			'jumlah' => $jumlah,
			'kode_unik' => $kode_unik,
			'bank1' => $bank1,
			'bank' => $bank_pengirim,
			'no_rek' => $rekening_pengirim,
			'atas_nama' => $pemilik_pengirim,
			'status' => '1'
		);
		$this->m_crud->create_data("pembayaran", $data_pembayaran);

		$get_orders = $this->m_crud->join_data("orders o", "o.id_orders, p.id_kota, p.id_pengiriman", "pengiriman p", "p.orders=o.id_orders", "o.member='".$member."' AND o.status = '0'");
		foreach ($get_orders as $row) {
			$romawi = $this->m_website->date_romawi('time');
			$tanggal = date('Y-m-d H:i:s');
			$max_order = $this->m_crud->get_data("orders", "MAX(RIGHT(id_orders, 3)) max_data", "RIGHT(id_orders, 3) REGEXP '^[0-9]' AND tgl_orders='".$tanggal."'")['max_data'];
			$code = '/'.$romawi.'/'.sprintf('%03d', (int)$max_order+1);
			$this->m_crud->update_data("orders", array('id_orders'=>'TR'.$code, 'tgl_orders'=>$tanggal, 'status'=>'1'), "id_orders='".$row['id_orders']."'");
			$this->m_crud->update_data("pengiriman", array('id_pengiriman'=>'DO'.$code), "id_pengiriman='".$row['id_pengiriman']."'");
			$this->m_crud->create_data("det_pembayaran", array('pembayaran'=> $code_pembayaran, 'orders'=>'TR'.$code));
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$result['status'] = false;
		} else {
			$this->db->trans_commit();
			$result['status'] = true;
			$result['code'] = $code_pembayaran;
		}

		echo json_encode($result);
	}

    public function checkout_bayar() {
        if($this->session->id_member!=''){
            $result = array();
            $member = $this->user;
            $tipe_alamat = $this->input->post('ch_alamat_jual',true);
            $kota =$this->input->post('kota',true);
            $nama_penerima = $this->input->post('nama_penerima',true);
            $alamat = $this->input->post('alamat',true);
            $kd_prov = $this->input->post('kd_prov',true);
            $kd_kec = $this->input->post('kd_kec',true);
            $prov = $this->input->post('provinsi',true);
            $kd_kota = $this->input->post('kd_kota',true);
            $tlp_penerima = $this->input->post('telepon',true);
            $kurir = strtoupper($this->input->post('kurir',true));
            $layanan_kurir = strtoupper($this->input->post('data_layanan',true));
            $ongkir = str_replace(',', '', $this->input->post('ongkir',true));
            $jumlah = (float)$this->input->post('total',true)+(float)$ongkir;
            $bank2 = $this->input->post('bank_tujuan',true);
            $bank = $this->input->post('nama_bank_tujuan',true);
            $rekening = $this->input->post('nomor_rekening_tujuan',true);
            $pemilik =$this->input->post('atas_nama_tujuan',true);
            $bank1 = $this->input->post('bank_pengirim',true);
            $bank_pengirim =$this->input->post('nama_bank_pengirim',true);
            $rekening_pengirim = $this->input->post('nomor_rekening_pengirim',true);
            $pemilik_pengirim =$this->input->post('atas_nama_pengirim',true);
            $tanggal = date('Y-m-d H:i:s');
            $voucher = $this->input->post('id_voucher',true);
            $jumlah_voucher = $this->input->post('jumlah_voucher',true);

            $kode_unik = 10;
            $param = true;
            while ($param) {
                $kode_unik = mt_rand( 10, 999 );
                $cek_kode_unik = $this->m_crud->get_data("pembayaran", "id_pembayaran", "jumlah=".$jumlah." AND kode_unik=".$kode_unik." AND status IN ('0', '1')");
                if ($cek_kode_unik == null) {
                    $param = false;
                } else {
                    $param = true;
                }
            }

            $this->db->trans_begin();

            if ($tipe_alamat == 'baru') {
                $kota = $this->input->post('kota',true);
                $nama_alamat = $this->input->post('nama_alamat',true);

                $data_lokasi = array(
                    'nama' => $nama_alamat,
                    'alamat' => $alamat,
                    'member' => $member,
                    'penerima' => $nama_penerima,
                    'telepon' => $tlp_penerima,
                    'kota' => $kd_kota,
                    'kecamatan' => $kd_kec,
                    'provinsi' => $kd_prov,
                    'status' => '1'
                );

                $this->m_crud->create_data("alamat_member", $data_lokasi);
            }

            $this->reset_pembayaran();
            $code_pembayaran = 'TF/'.$this->m_website->date_romawi().'/'.$member;
            $data_pembayaran = array(
                'id_pembayaran' => $code_pembayaran,
                'member' => $member,
                'tgl_bayar' => $tanggal,
                'bank2' => $bank2,
                'bank_tujuan' => $bank,
                'no_rek_tujuan' => $rekening,
                'atas_nama_tujuan' => $pemilik,
                'jumlah' => $jumlah,
                'kode_unik' => $kode_unik,
                'bank1' => $bank1,
                'bank' => $bank_pengirim,
                'no_rek' => $rekening_pengirim,
                'atas_nama' => $pemilik_pengirim,
                'status' => '1'
            );

            if ($voucher != '-') {
                $data_pembayaran['voucher'] = $voucher;
                $data_pembayaran['jumlah_voucher'] = $jumlah_voucher;
            }

            $this->m_crud->create_data("pembayaran", $data_pembayaran);

            $list = '';
            $sub_total = 0;
            $diskon = 0;

            $get_orders = $this->m_crud->read_data("orders", "id_orders", "member='".$member."' AND status = '0'");
            foreach ($get_orders as $row) {
                $romawi = $this->m_website->date_romawi('time');
                $tanggal = date('Y-m-d H:i:s');
                $max_order = $this->m_crud->get_data("orders", "MAX(RIGHT(id_orders, 3)) max_data", "RIGHT(id_orders, 3) REGEXP '^[0-9]' AND tgl_orders='".$tanggal."'")['max_data'];
                $code = '/'.$romawi.'/'.sprintf('%03d', (int)$max_order+1);
                $this->m_crud->update_data("orders", array('id_orders'=>'TR'.$code, 'tgl_orders'=>$tanggal, 'status'=>'1'), "id_orders='".$row['id_orders']."'");
                $this->m_crud->update_data("det_orders", array('orders'=>'TR'.$code), "orders='".$row['id_orders']."'");
                $this->m_crud->create_data("det_pembayaran", array('pembayaran'=> $code_pembayaran, 'orders'=>'TR'.$code));

                $to_cart = $this->m_crud->read_data("det_orders", "det_produk, qty, hrg_jual, hrg_varian, diskon", "orders='".$row['id_orders']."'");
                foreach ($to_cart as $item) {
                    $hitung_jumlah = ((float)$item['hrg_jual']+(float)$item['hrg_varian'])*(int)$item['qty'];
                    $diskon = $diskon + ((float)$item['diskon']*(int)$item['qty']);
                    $sub_total = $sub_total + $hitung_jumlah;
                    $get_produk = $this->m_crud->get_join_data("produk p", "p.nama", "det_produk dp", "dp.produk=p.id_produk", "dp.id_det_produk='".$item['det_produk']."'");
                    $list .= '
                <tr class="item">
                    <td>'.$get_produk['nama'].' x'.(int)$item['qty'].'</td>
                    <td>'.number_format($hitung_jumlah).'</td>
                </tr>
	        ';
                }
            }

            $data_pengiriman = array(
                'id_pengiriman' => 'DO'.$code,
                'orders' => 'TR'.$code,
                'penerima' => $nama_penerima,
                'alamat' => $alamat,
                'id_provinsi' => $kd_prov,
                'provinsi' => $prov,
                'id_kota' => $kd_kota,
                'kota' => $kota,
                'telepon' => $tlp_penerima,
                'kurir' => $kurir,
                'service' => $layanan_kurir,
                'biaya' => $ongkir
            );
            $this->m_crud->create_data("pengiriman", $data_pengiriman);
            $resultStok = 0;
            for($i=0;$i<(int)$_POST["no_produk"];$i++){
                $i=$i+1;
                $stokProduk=(int)$_POST["stok_produk_".$i]-(int)$_POST["qty_produk_".$i];
                $resultStok = (int)$_POST["stok_produk_".$i]-(int)$_POST["qty_produk_".$i];
                $idProduk=$_POST["id_produk_".$i];
                $this->m_crud->update_data("produk", array('stok'=>$stokProduk), "id_produk='".$idProduk."'");
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $result['status'] = false;
            } else {
                $this->db->trans_commit();
                $data = array(
                    'id_orders' => 'TR'.$code,
                    'tanggal' => $tanggal,
                    'penerima' => $nama_penerima,
                    'tlp' => $tlp_penerima,
                    'total' => (float)$jumlah+(float)$kode_unik-(float)$jumlah_voucher,
                    'disc' => (float)$diskon,
                    'bank' => $bank,
                    'rek' => $rekening,
                    'an' => $pemilik,
                    'kurir' => $kurir.' ~ '.$layanan_kurir,
                    'ongkir' => $ongkir,
                    'jumlah_voucher' => $jumlah_voucher,
                    'kode_unik' => $kode_unik,
                    'list' => $list
                );

                $result['status'] = true;
                $result['code'] = $code_pembayaran;
                $result['bank'] = $bank;
                $result['norek'] = $rekening;
                $result['atasnama'] = $pemilik;
                $result['total'] = (float)$jumlah+(float)$kode_unik-(float)$jumlah_voucher;
            }


            echo json_encode($result);
        }
        else{
            echo 'licik anying';
        }

    }

	public function jsons(){
		echo '{"to_cart":[{"hrg_varian":"0","catatan":"-","member":"25","det_produk":"226","berat":"500.0","hrg_beli":"0.0","jumlah":"4.0","hrg_jual":"84500.0"},{"hrg_varian":"0","catatan":"-","member":"25","det_produk":"222","berat":"900.0","hrg_beli":"0.0","jumlah":"1.0","hrg_jual":"98500.0"}],"checkout":{"kd_kota":"23","nama_bank_pengirim":"BCA","kurir":"jne","nomor_rekening_pengirim":"23234","bank_pengirim":"1","kd_kec":"340","atas_nama_tujuan":"PT. Indo Suhar Jaya","alamat":"asdasdasdad","atas_nama_pengirim":"asdadsad","data_layanan":"OKE (2-3 Hari)","nama_penerima":"asdadas","telepon":"08999999","member":"25","total":"183000","ongkir":"30000","kd_alamat":"40","kd_prov":"9","nomor_rekening_tujuan":"2339010808","nama_bank_tujuan":"BCA","kota":"Kota Bandung","provinsi":" ","bank_tujuan":"1"}}';
	}

	public function to_cart_mobile($param=null) {
		$result = array();
		$this->db->trans_begin();

		//$this->reset_pembayaran();
		$member = $param['member'];
		$det_produk = $param['det_produk'];
		$catatan= $param['catatan'];
		$berat = (float)$param['berat'];
		$jumlah = (int)$param['jumlah'];
		$hrg_jual = (float)$param['hrg_jual'];
		$hrg_beli = (float)$param['hrg_beli'];
		$hrg_coret = (float)$param['hrg_coret'];
		$hrg_varian = (float)$param['hrg_varian'];
		$tgl = date('Y-m-d H:i:s');
		$code = 'CART/'.$member;

		if ($hrg_coret==0) {
			$diskon = 0;
		} else {
			$diskon = ($hrg_coret+$hrg_varian)-$hrg_jual;
			$hrg_jual = $hrg_jual+$diskon;
		}

		$get_cart = $this->m_crud->get_data("orders", "id_orders", "id_orders='".$code."' AND status='0'");
		if ($get_cart == null) {
			$data_order = array(
				'id_orders' => $code,
				'tgl_orders' => $tgl,
				'tipe' => '1',
				'member' => $member,
				'status' => '0'
			);
			$this->m_crud->create_data("orders", $data_order);

			$det_order = array(
				'orders' => $code,
				'det_produk' => $det_produk,
				'qty' => $jumlah,
				'berat' => $berat,
				'hrg_beli' => $hrg_beli,
				'hrg_jual' => $hrg_jual,
				'hrg_varian' => 0,
				'diskon' => $diskon,
				'charge' => '0',
				'catatan' => $catatan
			);
			$this->m_crud->create_data("det_orders", $det_order);
		} else {
			$get_produk = $this->m_crud->get_data("det_orders", "qty", "orders='".$code."' AND det_produk='".$det_produk."'");
			if ($get_produk == null) {
				$det_order = array(
					'orders' => $code,
					'det_produk' => $det_produk,
					'qty' => $jumlah,
					'berat' => $berat,
					'hrg_beli' => $hrg_beli,
					'hrg_jual' => $hrg_jual+$diskon-$hrg_varian,
					'hrg_varian' => $hrg_varian,
					'diskon' => $diskon,
					'charge' => '0',
					'catatan' => $catatan
				);
				$this->m_crud->create_data("det_orders", $det_order);
			} else {
				$this->m_crud->update_data("det_orders", array('qty'=>$jumlah), "orders='".$code."' AND det_produk='".$det_produk."'");
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$status = false;
		} else {
			$this->db->trans_commit();
			$status = true;
			$result['count'] = $this->m_crud->count_data_join("orders o", "o.id_orders", "det_orders do", "do.orders=o.id_orders", "o.status='0' AND o.member='".$this->user."'");
		}

		return $status;
	}

	public function checkout_mobile() {
		$result = array();

		$data_post = json_decode($_POST['checkout'], true);
		$to_cart = $data_post['to_cart'];

		$list = '';
		$sub_total = 0;
		$diskon = 0;
		foreach ($to_cart as $item) {
			$hitung_jumlah = (float)$item['hrg_jual']*(int)$item['jumlah'];
			$sub_total = $sub_total + $hitung_jumlah;
			$this->to_cart_mobile($item);
			$get_produk = $this->m_crud->get_join_data("produk p", "p.nama", "det_produk dp", "dp.produk=p.id_produk", "dp.id_det_produk='".$item['det_produk']."'");
			$list .= '
                <tr class="item">
                    <td>'.$get_produk['nama'].' x'.(int)$item['qty'].'</td>
                    <td>'.number_format($hitung_jumlah).'</td>
                </tr>
	        ';
		}

		$checkout = $data_post['checkout'];

		$member = $checkout['member'];
		$tipe_alamat = $checkout['kd_alamat_jual'];
		$nama_penerima = $checkout['nama_penerima'];
		$alamat = $checkout['alamat'];
		$kd_prov = $checkout['kd_prov'];
		$prov = $this->m_crud->get_data("provinsi_rajaongkir", "provinsi", "provinsi_id='".$kd_prov."'")['provinsi'];
		$kd_kota = $checkout['kd_kota'];
		$kota = $this->m_crud->get_data("kota_rajaongkir", "kota", "kota_id='".$kd_kota."'")['kota'];
		$kd_kec = $checkout['kd_kec'];
		$kecamatan = $this->m_crud->get_data("kecamatan_rajaongkir", "kecamatan", "kecamatan_id='".$kd_kec."'")['kecamatan'];
		$tlp_penerima = $checkout['telepon'];
		$kurir = strtoupper($checkout['kurir']);
		$layanan_kurir = strtoupper($checkout['data_layanan']);
		$ongkir = str_replace(',', '', $checkout['ongkir']);
		$jumlah = (float)$sub_total+(float)$ongkir;
		$bank2 = $checkout['bank_tujuan'];
		$bank = $checkout['nama_bank_tujuan'];
		$rekening = $checkout['nomor_rekening_tujuan'];
		$pemilik = $checkout['atas_nama_tujuan'];
		$bank1 = $checkout['bank_pengirim'];
		$bank_pengirim = $checkout['nama_bank_pengirim'];
		$rekening_pengirim = $checkout['nomor_rekening_pengirim'];
		$pemilik_pengirim = $checkout['atas_nama_pengirim'];
		$tanggal = date('Y-m-d H:i:s');
		$jumlah_voucher = 0;

		$kode_unik = 11;
		$param = true;
		while ($param) {
			$cek_kode_unik = $this->m_crud->get_data("pembayaran", "id_pembayaran", "jumlah=".$jumlah." AND kode_unik=".$kode_unik." AND status IN ('0', '1')");
			if ($cek_kode_unik == null) {
				$param = false;
			} else {
				$param = true;
				$kode_unik++;
			}
		}

		$this->db->trans_begin();

		if ($tipe_alamat == 'baru') {
			$kota = $_POST['kota'];
			$nama_alamat = $_POST['nama_alamat'];

			$data_lokasi = array(
				'nama' => $nama_alamat,
				'alamat' => $alamat,
				'member' => $member,
				'penerima' => $nama_penerima,
				'telepon' => $tlp_penerima,
				'kota' => $kd_kota,
				'provinsi' => $kd_prov,
				'kecamatan' => $kd_kec,
				'status' => '1'
			);

			$this->m_crud->create_data("alamat_member", $data_lokasi);
		}

		$this->reset_pembayaran($member);
		$code_pembayaran = 'TF/'.$this->m_website->date_romawi().'/'.$member;
		$data_pembayaran = array(
			'id_pembayaran' => $code_pembayaran,
			'member' => $member,
			'tgl_bayar' => $tanggal,
			'bank2' => $bank2,
			'bank_tujuan' => $bank,
			'no_rek_tujuan' => $rekening,
			'atas_nama_tujuan' => $pemilik,
			'jumlah' => $jumlah,
			'kode_unik' => $kode_unik,
			'bank1' => $bank1,
			'bank' => $bank_pengirim,
			'no_rek' => $rekening_pengirim,
			'atas_nama' => $pemilik_pengirim,
			'status' => '1'
		);
		$this->m_crud->create_data("pembayaran", $data_pembayaran);

		$get_orders = $this->m_crud->read_data("orders", "id_orders", "member='".$member."' AND status = '0'");
		foreach ($get_orders as $row) {
			$romawi = $this->m_website->date_romawi('time');
			$tanggal = $tanggal;
			$max_order = $this->m_crud->get_data("orders", "MAX(RIGHT(id_orders, 3)) max_data", "RIGHT(id_orders, 3) REGEXP '^-?[0-9]+$' AND tgl_orders='".$tanggal."'")['max_data'];
			$code = '/'.$romawi.'/'.sprintf('%03d', (int)$max_order+1);
			$this->m_crud->update_data("orders", array('id_orders'=>'TR'.$code, 'tgl_orders'=>$tanggal, 'status'=>'1'), "id_orders='".$row['id_orders']."'");
			$this->m_crud->update_data("det_orders", array('orders'=>'TR'.$code), "orders='".$row['id_orders']."'");
			$this->m_crud->create_data("det_pembayaran", array('pembayaran'=> $code_pembayaran, 'orders'=>'TR'.$code));
		}

		$data_pengiriman = array(
			'id_pengiriman' => 'DO'.$code,
			'orders' => 'TR'.$code,
			'penerima' => $nama_penerima,
			'alamat' => $alamat,
			'id_provinsi' => $kd_prov,
			'provinsi' => $prov,
			'id_kota' => $kd_kota,
			'kota' => $kota,
			'id_kecamatan' => $kd_kec,
			'kecamatan' => $kecamatan,
			'telepon' => $tlp_penerima,
			'kurir' => $kurir,
			'service' => $layanan_kurir,
			'biaya' => $ongkir
		);
		$this->m_crud->create_data("pengiriman", $data_pengiriman);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$result['status'] = false;
			$result['judul'] = "Checkout Gagal.";
			$result['deskripsi'] = "Silahkan ulangi transaksi anda.";
		} else {
			$this->db->trans_commit();

			$result['status'] = true;
			$result['judul'] = "Checkout Berhasil.";
			$result['deskripsi'] = "Silahkan lakukan transfer ke rekening yang tertera di atas, dan segera lakukan konfirmasi jika telah melakukan transfer.";
			$result['code'] = $code_pembayaran;
			$result['bank'] = $bank;
			$result['norek'] = $rekening;
			$result['atasnama'] = $pemilik;
			$result['total'] = (float)$jumlah+(float)$kode_unik;

			$data = array(
				'id_orders' => 'TR'.$code,
				'tanggal' => $tanggal,
				'penerima' => $nama_penerima,
				'tlp' => $tlp_penerima,
				'total' => (float)$jumlah+(float)$kode_unik-(float)$jumlah_voucher,
				'disc' => (float)$diskon,
				'bank' => $bank,
				'rek' => $rekening,
				'an' => $pemilik,
				'kurir' => $kurir.' ~ '.$layanan_kurir,
				'ongkir' => $ongkir,
				'jumlah_voucher' => $jumlah_voucher,
				'kode_unik' => $kode_unik,
				'list' => $list
			);

			$this->m_website->create_notif(array('head'=>"Pesanan baru masuk",'content'=>'Invoice '.$code_pembayaran));

			$get_email = $this->m_crud->get_data("member", "email", "id_member='".$member."'");
			$this->m_website->email_invoice($get_email['email'], json_encode($data));
			$this->m_website->email_invoice($this->config->item('email'), json_encode($data));

		}

		echo json_encode($result);
	}

	public function get_rekening() {
		$result = array();
		if (isset($_POST['member'])) {
			$member = $_POST['member'];
		} else {
			$member = $this->user;
		}
		$bank = $_POST['bank'];

		$get_rekening = $this->m_crud->get_data("pembayaran", "bank, no_rek, atas_nama", "member='".$member."' AND bank1='".$bank."'");

		if ($get_rekening == null) {
			$result['status'] = false;
			$result['res_rekening'] = $this->m_crud->get_data("bank", "nama", "id_bank='".$bank."'");
		} else {
			$result['status'] = true;
			$result['res_rekening'] = $get_rekening;
		}

		echo json_encode($result);
	}

	/*Transaksi konfirmasi*/
	public function get_confirm() {
		$result = array();
		$member = $this->user;

		$list_confirm = '
        <table class="table datatable table-bordered table-hovered m-0 bg-white" border="0" cellspacing="0" style="font-size:12px">
            <thead>
                <tr>
                    <th class="p-2 bg-light">Nomor Transaksi</th>
                    <th class="p-2">Tanggal</th>
                    <th class="p-2">Waktu</th>
                    <th class="p-2">Tipe Transaksi</th>
                    <th class="p-2">Total</th>
                    <th class="p-2"></th>
                </tr>
            </thead>
            <tbody class="fprime">
        ';
		$get_data = $this->m_crud->read_data("pembayaran", "id_pembayaran, tgl_bayar, (jumlah+kode_unik) total", "member='".$member."' AND status='1'");

		if ($get_data != null) {
			foreach ($get_data as $row) {
				$list_confirm .= '
                <tr>
                    <td class="bg-light">'.$row['id_pembayaran'].'</td>
                    <td>'.date('d M Y', strtotime($row['tgl_bayar'])).'</td>
                    <td>'.date('H:i:s', strtotime($row['tgl_bayar'])).'</td>
                    <td>Transfer</td>
                    <td>Rp '.number_format($row['total']).'</td>
                    <td>
                        <a href="javascript:" onclick="konfirmasi(\''.$row['id_pembayaran'].'\')" class="btn btn-outline-success btn-sm" title="Konfirmasi"><span class="fa fa-check"></span></a>
                        <a href="javascript:" onclick="detail(\''.$row['id_pembayaran'].'\')" class="btn btn-outline-info btn-sm" title="Detail"><span class="fa fa-info"></span></a>
                        <a href="javascript:" onclick="batal(\''.$row['id_pembayaran'].'\')" class="btn btn-outline-danger btn-sm" title="Batal"><span class="fa fa-close"></span></a>
                    </td>
                </tr>
                ';
			}
		} else {
			$list_confirm .= '<tr><td class="text-center" colspan="6">Data tidak tersedia</td></tr>';
		}

		$list_confirm .= '
            </tbody>
        </table>
        ';
		$result['status'] = true;
		$result['res_confirm'] = $list_confirm;

		echo json_encode($result);
	}

	public function prev_confirm() {
		$result = array();
		$id_pembayaran = $_POST['id_pembayaran'];

		$get_data = $this->m_crud->get_data("pembayaran", "(jumlah+kode_unik) total, bank, no_rek, atas_nama", "id_pembayaran='".$id_pembayaran."'");
		if ($get_data != null) {
			$result['status'] = true;
			$result['res_confirm'] = $get_data;
		} else {
			$result['status'] = false;
		}

		echo json_encode($result);
	}

	public function get_history_pembelian($action=5, $id=1) {
		$member = $_POST['id_member'];

		$page=$id;
		$config['per_page'] = $action;

		$start = $config["per_page"];
		$end = $config["per_page"]*($page-1);

		$get_data = $this->m_crud->join_data("orders o", "o.id_orders, o.tgl_orders, o.status, SUM(do.qty * (do.hrg_jual+do.hrg_varian-do.diskon)) total, dp.pembayaran, pb.status status_bayar, pb.kode_unik, pn.id_pengiriman", array("det_orders do", "det_pembayaran dp", "pembayaran pb", "pengiriman pn"), array("do.orders=o.id_orders", "dp.orders=o.id_orders", "pb.id_pembayaran=dp.pembayaran", "pn.orders=o.id_orders"), "o.member='".$member."' AND o.status <> '0'", "o.tgl_orders DESC", "o.id_orders, o.tgl_orders, o.status, dp.pembayaran, pb.status, pb.kode_unik, pn.id_pengiriman", $start, $end);

		if ($get_data != null) {
			$status = true;
			foreach ($get_data as $key => $row) {
				$action = array(array('api'=>'get_detail_pembelian', 'text'=>'Detail Pembelian'));
				if ($row['status'] == '1') {
					if ($row['status_bayar'] == '1') {
						$status_trx = array('hex'=>'#80FF9800', 'text'=>'Waiting Payment');
						array_push($action, array('api'=>'konfirmasi_pembayaran', 'text'=>'Konfirmasi Pembayaran'));
						array_push($action, array('api'=>'cancel_order', 'text'=>'Batalkan Pembelian'));
					} else if ($row['status_bayar'] == '3') {
						$status_trx = array('hex'=>'#8003A9F4', 'text'=>'On Process');
					} else {
						$status_trx = array('hex'=>'#80673AB7', 'text'=>'Waiting Payment Verified');
					}
				} else if ($row['status'] == '2' || $row['status'] == '3') {
					if ($row['status'] == '3') {
						array_push($action, array('api'=>'finish_order', 'text'=>'Terima Pesanan'));
					}
					array_push($action, array('api'=>'lacak_resi', 'text'=>'Lacak Pengiriman'));

					$status_trx = array('hex'=>'#8003A9F4', 'text'=>'On Process');
				} else if ($row['status'] == '4') {
					$status_trx = array('hex'=>'#804CAF50', 'text'=>'Success');
				} else {
					$status_trx = array('hex'=>'#80f44336', 'text'=>'Cancel');
				}
				$get_ongkir = $this->m_crud->get_data("pengiriman", "biaya", "orders='" . $row['id_orders'] . "'");

				$get_data[$key]['status_trx'] = $status_trx;
				$get_data[$key]['action'] = $action;
				$get_data[$key]['ongkir'] = $get_ongkir['biaya'];
			}
		} else {
			$status = false;
		}

		echo json_encode(array('status'=>$status, 'data'=>$get_data));
	}

	public function get_detail_pembelian() {
		$result = array();

		$id_pembayaran = $_POST['id_pembayaran'];
		$get_pembayaran = $this->m_crud->get_data("pembayaran", "bank, no_rek, atas_nama, jumlah, kode_unik", "id_pembayaran='" . $id_pembayaran . "'");

		if ($get_pembayaran == null) {
			$result['status'] = false;
		} else {
			$result['status'] = true;
			$data_produk = array();

			$kode_unik = $get_pembayaran['kode_unik'];
			$get_penjualan = $this->m_crud->read_data("det_pembayaran", "orders", "pembayaran='" . $id_pembayaran . "'");
			$kode_orders = array();
			foreach ($get_penjualan as $row) {
				array_push($kode_orders, str_replace("/", "_", $row['orders']));
				$get_status = $this->m_crud->get_data("orders", "status", "id_orders='".$row['orders']."'");
				if ($get_status['status'] == '3') {
					$result['finish_button'] = true;
				} else {
					$result['finish_button'] = false;
				}
			}

			$get_data_cart = json_decode($this->get_item_cart(json_encode($kode_orders)), true);

			$res_cart = $get_data_cart['res_cart'];

			$ongkir = 0;
			foreach ($res_cart as $row) {
				foreach ($row['list_produk'] as $row_produk) {
					$produk = array(
						'nama_produk' => $row_produk['nama_produk'],
						'qty' => $row_produk['qty'],
						'catatan' => $row_produk['catatan'],
						'hrg_jual' => $row_produk['hrg_jual']+$row_produk['hrg_varian'],
						'diskon' => $row_produk['diskon'],
						'gambar' => $row_produk['gambar_produk'][0]
					);

					array_push($data_produk, $produk);
				}

				$ongkir = $ongkir + $row['list_pengiriman']['biaya'];
			}

			$tagihan = 0;
			$diskon = 0;
			foreach ($data_produk as $row) {
				$tagihan = $tagihan + ($row['qty']*$row['hrg_jual']);
				$diskon = $diskon + ($row['qty']*$row['diskon']);
			}

			$result['res_detail'] = $res_cart;
		}

		echo json_encode($result);
	}

	public function konfirmasi_pembayaran() {
		$result = array();
		$member = $this->user;

		$id_pembayaran = $this->input->post('id_pembayaran',true);

		$row = 'bukti_transfer';
		$config['upload_path']          = './assets/images/bukti_transfer';
		$config['allowed_types']        = 'jpg|jpeg|png';
		$config['max_size']             = 5120;
		$this->load->library('upload', $config);
		$valid = true;

		if( (!$this->upload->do_upload($row)) && $_FILES[$row]['name']!=null){
			$valid = false;
			$file[$row]['file_name']=null;
			$file[$row] = $this->upload->data();
			$data['error_'.$row] = $this->upload->display_errors();
		} else{
			$file[$row] = $this->upload->data();
			$data[$row] = $file;
		}

		$this->db->trans_begin();

		$data_pembayaran = array("tgl_konfirmasi"=>date('Y-m-d H:i:s'), "status"=>'2');
		$data_order = array("status"=>'2');
		if($_FILES[$row]['name']!=null){
			$data_pembayaran['bukti_transfer'] = 'assets/images/bukti_transfer/'.$file[$row]['file_name'];
		}
		$this->m_crud->update_data("pembayaran", $data_pembayaran, "id_pembayaran='".$id_pembayaran."'");
		$this->m_crud->update_data("orders", $data_order, "id_orders='".$this->input->post('id_orders',true)."'");

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$result['status'] = false;
		} else {
			$this->db->trans_commit();
			$result['status'] = true;
			$result['count'] = $this->m_crud->count_data("pembayaran", "id_pembayaran", "member='".$member."' AND status = '1'");

			$this->m_website->create_notif(array('head'=>"Pembayaran berhasil",'content'=>'Penmabayaran dengan invoice '.$code_pembayaran.' telah berhasil.'));

		}

		echo json_encode($result);
	}

	public function get_detail_orders($param=null) {
		$result = array();

		if ($param == null) {
			$id_pembayaran = $_POST['id_pembayaran'];
			$get_pembayaran = $this->m_crud->get_data("pembayaran", "bank_tujuan bank, no_rek_tujuan no_rek, atas_nama_tujuan atas_nama, jumlah, kode_unik, jumlah_voucher", "id_pembayaran='" . $id_pembayaran . "' AND status='1'");
		} else {
			$get_pembayaran = $param;
		}

		if ($get_pembayaran == null) {
			$result['status'] = false;
		} else {
			$result['status'] = true;
			$data_produk = array();

			if ($param == null) {
			    $bank=$get_pembayaran['bank'];
			    $no_rek=$get_pembayaran['no_rek'];
			    $atas_nama=$get_pembayaran['atas_nama'];
				$kode_unik = $get_pembayaran['kode_unik'];
				$jumlah_voucher = $get_pembayaran['jumlah_voucher'];
				$get_penjualan = $this->m_crud->read_data("det_pembayaran", "orders", "pembayaran='" . $id_pembayaran . "'");
				$kode_orders = array();
				foreach ($get_penjualan as $row) {
					array_push($kode_orders, str_replace("/", "_", $row['orders']));
				}
			} else {
				$data_pembayaran = $this->m_crud->get_join_data("pembayaran pb", "bank_tujuan bank, no_rek_tujuan no_rek, atas_nama_tujuan atas_nama,kode_unik, jumlah_voucher", "det_pembayaran dp", "dp.pembayaran=pb.id_pembayaran", "dp.orders='" . base64_decode($param) . "'");
                $bank=$data_pembayaran['bank'];
                $no_rek=$data_pembayaran['no_rek'];
                $atas_nama=$data_pembayaran['atas_nama'];
				$kode_unik = $data_pembayaran['kode_unik'];
				$jumlah_voucher = $data_pembayaran['jumlah_voucher'];
				$kode_orders = array(base64_decode($param));
			}

			$get_data_cart = json_decode($this->get_item_cart(json_encode($kode_orders)), true);

			$res_cart = $get_data_cart['res_cart'];

			$ongkir = 0;
			foreach ($res_cart as $row) {
				foreach ($row['list_produk'] as $row_produk) {
					$produk = array(
						'nama_produk' => $row_produk['nama_produk'],
						'qty' => $row_produk['qty'],
						'hrg_jual' => $row_produk['hrg_jual']+$row_produk['hrg_varian'],
						'diskon' => $row_produk['diskon'],
						'gambar' => $row_produk['gambar_produk'][0]
					);

					array_push($data_produk, $produk);
				}

				$ongkir = $ongkir + $row['list_pengiriman']['biaya'];
			}

			$tagihan = 0;
			$diskon = 0;
			$list_produk = '';
			foreach ($data_produk as $row) {
				$tagihan = $tagihan + ($row['qty']*$row['hrg_jual']);
				$diskon = $diskon + ($row['qty']*$row['diskon']);
				$list_produk .= '
                    <li class="list-group-item border-left-0 border-right-0">
                        <div class="media">
                            <img src="'.$row['gambar'].'" alt="" width="50" style="margin-right: 10px">
                            <div class="media-body fprime">
                                <h6 class="fsecond mb-1">'.$row['nama_produk'].'</h6>
                                '.$row['qty'].' x Rp '.number_format($row['hrg_jual']).'
                            </div>
                        </div>
                    </li>
                ';
			}

			$res_detail = '
            <div class="card">
                <div class="card-header">
                    Produk yang dibeli
                </div>
                    <ul class="list-group rounded-0 border-top-0 border-bottom-0">
                        '.$list_produk.'
                    </ul>
            </div>
            <br>
            <div class="row dl-tagihan">
            <div class="col-6 text-secondary mb-3 text-left">Akun </div>
                <div class="col-6 mb-3 text-right">( '.$bank.','.$atas_nama.','.$no_rek.' )</div>
                <div class="col-6 text-secondary mb-3 text-left">Tagihan produk</div>
                <div class="col-6 mb-3 text-right">Rp '.number_format($tagihan).'</div>
                <div class="col-6 text-secondary mb-3 text-left">Diskon</div>
                <div class="col-6 mb-3 text-right">Rp '.number_format($diskon).'</div>
                <div class="col-12"><hr class="sm border-grey"></div>
                <div class="col-6 text-secondary mb-3 text-left">Ongkos kirim</div>
                <div class="col-6 mb-3 text-right">Rp '.number_format($ongkir).'</div>
                <div class="col-6 text-secondary mb-3 text-left">Voucher</div>
                <div class="col-6 mb-3 text-right">Rp '.number_format($jumlah_voucher).'</div>
                <div class="col-6 text-secondary mb-3 text-left">Kode Unik</div>
                <div class="col-6 mb-3 text-right">Rp '.$kode_unik.'</div>
                <div class="col-12"><hr class="sm border-grey"></div>
                <div class="col-6 text-secondary mb-3 text-left">Total Tagihan</div>
                <div class="col-6 mb-3 text-right">Rp '.number_format($tagihan+$ongkir-$diskon-$jumlah_voucher+$kode_unik).'</div>
            </div>            
            ';

			$result['res_detail'] = $res_detail;
			$result['pembayaran'] = $get_pembayaran;
		}

		echo json_encode($result);
	}

	public function cancel_order() {
		$result = array();
		$id_pembayaran = $_POST['id_pembayaran'];
		$member = $this->user;
		$this->db->trans_begin();

		$this->m_crud->update_data("pembayaran", array('status'=>'4'), "id_pembayaran='".$id_pembayaran."'");
		$this->m_crud->update_data("orders", array('status'=>'5'), "id_orders IN (SELECT orders FROM det_pembayaran WHERE pembayaran='".$id_pembayaran."')");

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$result['status'] = false;
			$result['pesan'] = "Terjadi kesalahan, silahkan ulangi lagi.";
		} else {
			$this->db->trans_commit();
			$result['status'] = true;
			$result['pesan'] = "Transaksi berhasil dibatalkan.";
			$result['count'] = $this->m_crud->count_data("pembayaran", "id_pembayaran", "member='".$member."' AND status = '1'");
		}

		echo json_encode($result);
	}
	/*End*/

	/*Modul Histori Transaksi*/
	public function get_history() {
		$result = array();
		$member = $this->user;

		$get_data = $this->m_crud->join_data("orders o", "o.id_orders, o.tgl_orders, o.status, SUM(do.qty * (do.hrg_jual+do.hrg_varian-do.diskon)) total", "det_orders do", "do.orders=o.id_orders", "o.member='".$member."' AND o.status <> '0'", "o.tgl_orders DESC", "o.id_orders");

		$list_transaksi = '
        <table class="table datatable table-bordered table-hovered m-0 bg-white" border="0" cellspacing="0" style="font-size:12px">
            <thead>
                <tr>
                    <th class="p-2 bg-light">Nomor Transaksi</th>
                    <th class="p-2">Tanggal</th>
                    <th class="p-2">Waktu</th>
                    <th class="p-2">Tipe Transaksi</th>
                    <th class="p-2">Total Harga</th>
                    <th class="p-2">Status</th>
                    <th class="p-2"></th>
                </tr>
            </thead>
            <tbody class="fprime">
        ';

		if ($get_data != null) {
			foreach ($get_data as $row) {
				if ($row['status'] == '1') {
					$status = '<span class="badge badge-pill badge-warning text-white py-2 px-3">Pending</span>';
				} else if ($row['status'] == '2' || $row['status'] == '3') {
					$status = '<span class="badge badge-pill badge-primary text-white py-2 px-3">On Process</span>';
				} else if ($row['status'] == '4') {
					$status = '<span class="badge badge-pill badge-success text-white py-2 px-3">Success</span>';
				} else {
					$status = '<span class="badge badge-pill badge-danger text-white py-2 px-3">Cancel</span>';
				}

				$get_ongkir = $this->m_crud->get_data("pengiriman", "biaya", "orders='" . $row['id_orders'] . "'")['biaya'];

				$list_transaksi .= '
                <tr>
                    <td class="bg-light">'.$row['id_orders'].'</td>
                    <td>'.date('d M Y', strtotime($row['tgl_orders'])).'</td>
                    <td>'.date('H:i:s', strtotime($row['tgl_orders'])).'</td>
                    <td>Transfer</td>
                    <td>Rp '.number_format($row['total']+$get_ongkir).'</td>
                    <td>'.$status.'</td>
                    <td><a href="javascript:" onclick="detail(\''.$row['id_orders'].'\')" class="btn btn-outline-info btn-sm" title="Detail"><span class="fa fa-info"></span></a></td>
                </tr>
                ';
			}
		} else {
			$list_transaksi .= '<tr><td colspan="7" class="text-center">Data tidak tersedia</td></tr>';
		}

		$list_transaksi .= '
            </tbody>
        </table>
        ';

		$result['status'] = true;
		$result['res_history'] = $list_transaksi;

		echo json_encode($result);
	}
	/*End*/

	/*Modul Status Pengiriman*/
	public function get_pengiriman() {
		$result = array();
		$member = $this->user;

		$get_data = $this->m_crud->join_data("orders o", "o.id_orders, o.status, p.id_pengiriman, p.kurir, p.service, p.biaya, p.no_resi", "pengiriman p", "p.orders=o.id_orders", "o.member='".$member."' AND o.status IN ('2', '3')", "o.tgl_orders DESC", "o.id_orders");

		$list_transaksi = '
        <table class="table datatable table-bordered table-hovered m-0 bg-white" border="0" cellspacing="0" style="font-size:12px">
            <thead>
                <tr>
                    <th class="p-2 bg-light">Nomor Transaksi</th>
                    <th class="p-2">Ekspedisi</th>
                    <th class="p-2">Nomor Resi Pengiriman</th>
                    <th class="p-2">Biaya Kirim</th>
                    <th class="p-2"></th>
                </tr>
            </thead>
            <tbody class="fprime">
        ';

		if ($get_data != null) {
			foreach ($get_data as $row) {
				if ($row['no_resi'] != '') {
					$lacak = '<a href="javascript:" onclick="detail(\''.$row['id_pengiriman'].'\')" class="btn btn-outline-info btn-sm" title="Lacak"><span class="fa fa-search"></span></a><a href="javascript:" onclick="finish(\''.$row['id_orders'].'\')" class="btn btn-outline-success btn-sm" title="Selesai"><span class="fa fa-check"></span></a>';
				} else {
					$lacak = '';
				}

				$list_transaksi .= '
                <tr>
                    <td class="bg-light">'.$row['id_orders'].'</td>
                    <td>'.$row['kurir'].' - '.$row['service'].'</td>
                    <td>'.$row['no_resi'].'</td>
                    <td>Rp '.number_format($row['biaya']).'</td>
                    <td>'.$lacak.'</td>
                </tr>
                ';
			}
		} else {
			$list_transaksi .= '<tr><td colspan="5" class="text-center">Data tidak tersedia</td></tr>';
		}

		$list_transaksi .= '
            </tbody>
        </table>
        ';

		$result['status'] = true;
		$result['res_pengiriman'] = $list_transaksi;

		echo json_encode($result);
	}

	public function finish_order() {
		$result = array();
		$id_orders = $_POST['id_orders'];
		$this->db->trans_begin();
		$min_order = 100000;

		$this->m_crud->update_data("orders", array('status'=>'4'), "id_orders='".$id_orders."'");

		/*$get_total = $this->m_crud->get_data("det_orders", "SUM(qty*(hrg_jual+hrg_varian-diskon)) total", "orders='".$id_orders."'")['total'];
		$check_data = $this->m_crud->get_data("poin", "kode_transaksi", "kode_transaksi='".$id_orders."'");
		if ($check_data == null && $get_total>=$min_order) {
			$this->m_crud->create_data("poin", array('kode_transaksi'=>$id_orders, 'member'=>$this->user, 'poin'=>floor($get_total/$min_order), 'keterangan'=>'Pembelian'));
		}*/

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$result['status'] = false;
		} else {
			$this->db->trans_commit();
			$result['status'] = true;
			$result['count'] = $this->m_crud->count_data_join("orders o", "o.id_orders", "pengiriman p", "p.orders=o.id_orders", "o.member='".$this->user."' AND o.status IN ('2', '3') AND p.no_resi IS NOT NULL");
			$result['count_ulasan'] = $this->m_crud->count_data_join("orders o", "do.det_produk", array("det_orders do", "det_produk dp", "produk p", array("table"=>"ulasan u", "type"=>"LEFT")), array("do.orders=o.id_orders", "dp.id_det_produk=do.det_produk", "p.id_produk=dp.produk", "u.orders=o.id_orders AND u.produk=p.id_produk"), "o.member='" . $this->user . "' AND o.status='4' AND u.orders IS NULL");
		}

		echo json_encode($result);
	}
	/*End*/

	/*Ubah Profile*/
	public function get_profile() {
		$result = array();
		$member = $_POST['id_member'];

		$get_data = $this->m_crud->get_data("member", "email,nama, jenis_kelamin, tgl_lahir, telepon, foto", "id_member='".$member."'");
		if ($get_data != null) {
			$result['status'] = true;
			$result['res_profile'] = array(
				'email' => $get_data['email'],
				'nama' => $get_data['nama'],
				'jenis_kelamin' => $get_data['jenis_kelamin'],
				'tgl_lahir' => $get_data['tgl_lahir'],
				'telepon' => $get_data['telepon'],
				'foto' => base_url().$get_data['foto']
			);
		} else {
			$result['status'] = false;
		}

		echo json_encode($result);
	}

	public function update_profile() {
		$result = array();

		$member = $this->input->post('id_member',true);
		$nama = $this->input->post('nama',true);
		$jenis_kelamin = $this->input->post('jenis_kelamin',true);
		$tgl_lahir = date('Y-m-d', strtotime($this->input->post('tgl_lahir',true)));
		$telepon =$this->input->post('telepon',true);

		$row = 'foto';
		$config['upload_path']          = './assets/images/member';
		$config['allowed_types']        = 'gif|jpg|jpeg|png';
		$config['max_size']             = 5120;
		$this->load->library('upload', $config);
		$valid = true;

		$cek_tlp = $this->m_crud->get_data("member", "id_member", "telepon='".$telepon."' and id_member <> '".$member."'");

		if ($cek_tlp == null) {
			if ((!$this->upload->do_upload($row)) && $_FILES[$row]['name'] != null) {
				$valid = false;
				$file[$row]['file_name'] = null;
				$file[$row] = $this->upload->data();
				$data['error_' . $row] = $this->upload->display_errors();
			} else {
				$file[$row] = $this->upload->data();
				$data[$row] = $file;
			}

			$this->db->trans_begin();

			$data_member = array(
				'nama' => $nama,
				'jenis_kelamin' => $jenis_kelamin,
				'tgl_lahir' => $tgl_lahir,
				'telepon' => $telepon
			);

			if ($_FILES[$row]['name'] != null) {
				$data_member['foto'] = 'assets/images/member/' . $file[$row]['file_name'];
				$this->session->set_userdata($this->site . 'foto', base_url() . 'assets/images/member/' . $file[$row]['file_name']);
			}

			$this->m_crud->update_data("member", $data_member, "id_member='" . $member . "'");

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$result['status'] = false;
				$result['pesan'] = "Data gagal disimpan";
			} else {
				$this->db->trans_commit();
				$this->session->set_userdata($this->site . 'nama', $nama);
				$result['status'] = true;

				$data_customer = array(
					'param' => 'edit',
					'kode' => $this->m_crud->get_data('member', 'ol_code', "id_member='".$member."'")['ol_code'],
					'nama' => strtoupper($nama),
					'tlp' => $telepon,
					'tgl_lahir' => date('Y-m-d', strtotime($tgl_lahir))
				);
//				$this->m_website->request_api('data_customer', $data_customer);
			}
		} else {
			$result['status'] = false;
			$result['pesan'] = "Nomor telepon sudah tersedia";
		}

		echo json_encode($result);
	}
	/*End*/

	/*Ubah Password*/
	public function ubah_password() {
		$result = array();

		$member = $_POST['id_member'];
		$options = ['cost' => 12];
		$password = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);

		$this->db->trans_begin();

		$data_member = array(
			'password' => $password
		);

		$this->m_crud->update_data("member", $data_member, "id_member='".$member."'");

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$result['status'] = false;
		} else {
			$this->db->trans_commit();
			$result['status'] = true;
		}

		echo json_encode($result);
	}
	/*End*/

	public function ganti_password() {
		$result = array();

		$member = $_POST['id_member'];
		$p_lama = $_POST['password_lama'];

		$get_data = $this->m_crud->get_data("member", "password", "id_member = '".$member."'");

		if ($get_data != null) {
			if (password_verify($p_lama, $get_data['password'])) {
				$options = ['cost' => 12];
				$password = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);

				$this->db->trans_begin();

				$data_member = array(
					'password' => $password
				);

				$this->m_crud->update_data("member", $data_member, "id_member='".$member."'");

				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$result['status'] = false;
					$result['message'] = "Password gagal diubah";
				} else {
					$this->db->trans_commit();
					$result['status'] = true;
					$result['message'] = "Password berhasil diubah";
				}
			} else {
				$result['status'] = false;
				$result['message'] = "Password lama salah";
			}
		}

		echo json_encode($result);
	}

	public function cek_password() {
		$result = 'false';

		$member = $_POST['member'];
		$p_lama = $_POST['p_lama'];

		$get_data = $this->m_crud->get_data("member", "password", "id_member = '".$member."'");

		if ($get_data != null) {
			if (password_verify($p_lama, $get_data['password'])) {
				$result = 'true';
			}
		}

		echo $result;
	}

	public function lacak_resi() {
		$result = array();
		$id_pengiriman = $_POST['id_pengiriman'];
		$get_data = $this->m_crud->get_data("pengiriman", "orders, kurir, no_resi", "id_pengiriman='".$id_pengiriman."'");

		$resi = $this->m_website->rajaongkir_resi(json_encode(array('resi'=>$get_data['no_resi'], 'kurir'=>strtolower($get_data['kurir']))));
		$decode = json_decode($resi, true);
		$status_resi = $decode['rajaongkir']['status']['code'];

		if ($status_resi == '200') {
			$result = $decode['rajaongkir']['result'];
			$delivered = $result['delivered'];
			$summary = $result['summary'];
			$details = $result['details'];
			$manifest = $result['manifest'];

			$result['delivered'] = $delivered;
			$result['summary'] = $summary;
			$result['details'] = $details;
			$result['manifest'] = $manifest;
			$result['status'] = true;
		} else {
			$result['status'] = false;
		}

		echo json_encode($result);
	}

	public function lacak_resi_mobile() {
		$result = array();
		$id_pengiriman = $_POST['id_pengiriman'];
		$get_data = $this->m_crud->get_data("pengiriman", "orders, kurir, no_resi", "id_pengiriman='".$id_pengiriman."'");

		$resi = $this->m_website->rajaongkir_resi(json_encode(array('resi'=>$get_data['no_resi'], 'kurir'=>strtolower($get_data['kurir']))));
		$decode = json_decode($resi, true);
		$status_resi = $decode['rajaongkir']['status']['code'];

		if ($status_resi == '200') {
			$result = $decode['rajaongkir']['result'];
			$delivered = $result['delivered'];
			$summary = $result['summary'];
			$details = $result['details'];
			$manifest = $result['manifest'];

			if ($delivered) {
				$this->m_crud->update_data("orders", array('status'=>'4'), "id_orders='".$get_data['orders']."'");
				$result['message'] = "Paket telah tiba di tujuan";
			} else {
				$this->m_crud->update_data("orders", array('status'=>'3'), "id_orders='".$get_data['orders']."'");
				$result['message'] = "Paket dalam proses pengiriman";
			}

			$result['delivered'] = $delivered;
			$result['summary'] = $summary;
			$result['details'] = $details;
			$result['manifest'] = $manifest;
			$result['status'] = true;
		} else {
			$result['status'] = false;
			$result['message'] = "Nomor resi salah atau belum tercatat di sistem kurir";
		}

		echo json_encode($result);
	}

	public function get_lokasi($page=1) {
		$result = array();

		$res_lokasi = array();
		$perpage = 100;
		$start = ($page - 1) * $perpage;
		$get_lokasi = $this->m_crud->read_data("lokasi", "*, CONCAT(jam_buka, ' - ', jam_tutup) operasional", null, null, null, $perpage, $start);
		foreach ($get_lokasi as $row) {
			array_push($res_lokasi, array('nama'=>$row['nama'], 'gambar'=>base_url().$row['gambar'], 'tlp'=>$row['tlp1'], 'operation'=>$row['operasional'], 'lat'=>$row['lat'], 'long'=>$row['lng']));
		}

		if ($get_lokasi) {
			$result['res_lokasi'] = $res_lokasi;
			$result['status'] = true;
		} else {
			$result['status'] = false;
		}

		echo json_encode($result);
	}

	public function get_promo($page=1) {
		$result = array();

		$res_promo = array();
		$start = ($page - 1) * 10;
		$promo = $this->m_crud->read_data("promo", "*", "'".date('Y-m-d H:i:s')."' BETWEEN tgl_awal AND tgl_akhir", null, null, 10, $start);
		foreach ($promo as $row) {
			$get_produk = $this->m_crud->read_data("det_promo", "concat(\"'\", produk, \"'\") produk", "promo='".$row['id_promo']."'");
			$produk = array();
			foreach ($get_produk as $item) {
				array_push($produk, $item['produk']);
			}

			array_push($res_promo, array('in_produk'=>base64_encode(json_encode($produk)), 'nama'=>$row['promo'], 'gambar'=>base_url().$row['gambar'], 'deskripsi'=>$row['deskripsi'], 'diskon'=>implode(' + ', json_decode($row['diskon'], true)), 'start'=>date('d M Y H:i', strtotime($row['tgl_awal'])), 'end'=>date('d M Y H:i', strtotime($row['tgl_akhir']))));
		}

		if ($promo) {
			$result['res_promo'] = $res_promo;
			$result['status'] = true;
		} else {
			$result['status'] = false;
		}

		echo json_encode($result);
	}

	public function get_about() {
		$result = array();

		$result['status'] = true;
		$result['res_about'] = $this->m_crud->get_data("setting", "tentang", "id_setting='1111'")['tentang'];

		echo json_encode($result);
	}

	public function get_help() {
		$result = array();

		$result['status'] = true;
		$result['res_help'] = $this->m_crud->get_data("setting", "cara_belanja", "id_setting='1111'")['cara_belanja'];

		echo json_encode($result);
	}

	public function get_syarat() {
		$result = array();

		$result['status'] = true;
		$result['res_syarat'] = $this->m_crud->get_data("setting", "syarat", "id_setting='1111'")['syarat'];

		echo json_encode($result);
	}

	public function get_kebijakan() {
		$result = array();

		$result['status'] = true;
		$result['res_kebijakan'] = $this->m_crud->get_data("setting", "kebijakan", "id_setting='1111'")['kebijakan'];

		echo json_encode($result);
	}

	public function get_karir() {
		$result = array();

		$result['status'] = true;
		$result['res_karir'] = $this->m_crud->get_data("setting", "karir", "id_setting='1111'")['karir'];

		echo json_encode($result);
	}

	public function get_resolusi() {
		$result = array();

		$result['status'] = true;
		$result['res_resolusi'] = $this->m_crud->get_data("setting", "pusat_resolusi", "id_setting='1111'")['pusat_resolusi'];

		echo json_encode($result);
	}

	public function get_bank() {
		$response = array();
		$get_data = $this->m_crud->join_data("bank", "bank.id_bank, bank.nama, bank.gambar, rekening.atas_nama, rekening.no_rek", "rekening", "bank.id_bank=rekening.bank");

		if ($get_data == null) {
			$response['status'] = false;
		} else {
			$response['status'] = true;
			$get_data = $this->tambah_data('bank', $get_data);
		}

		$response['data'] = $get_data;

		echo json_encode($response);
	}

	public function get_bank_pengirim() {
		$response = array();
		$get_data = $this->m_crud->read_data("bank", "id_bank, nama, gambar");

		if ($get_data == null) {
			$response['status'] = false;
		} else {
			$response['status'] = true;
			$get_data = $this->tambah_data('bank', $get_data);
		}

		$response['data'] = $get_data;

		echo json_encode($response);
	}

	public function get_poin() {
		$result = array();

		$member = $_POST['member'];

		$result['status'] = true;
		$result['res_poin'] = $this->m_crud->get_data("poin", "IFNULL(SUM(poin), 0) poin", "member='".$member."' and '".date('y')."' in (substr(kode_transaksi, 4, 2), substr(kode_transaksi, 3, 2))")['poin'];
		$get_sosmed = $this->m_crud->get_data("setting", "sosmed", "id_setting='1111'")['sosmed'];
		$decode = json_decode($get_sosmed, true);
		$found = 0;
		foreach($decode as $key => $value) {
			if ($value['id'] == 'whatsapp') {
				$found = $key;
				break;
			}
		}
		$result['res_number'] = $decode[$found]['value'];
		$result['res_message'] = $decode[$found]['format_order'];
		$result['exp'] = date('d-m-Y', strtotime('12/31'));

		echo json_encode($result);
	}

	public function save_lokasi() {
		$result = array();

		$data_lokasi = array(
			'nama' => $_POST['nama_lokasi'],
			'alamat' => $_POST['alamat'],
			'member' => $_POST['member'],
			'penerima' => $_POST['penerima'],
			'telepon' => $_POST['telepon'],
			'kota' => $_POST['id_kota'],
			'provinsi' => $_POST['id_provinsi'],
			'kecamatan' => $_POST['id_kecamatan'],
			'status' => '1'
		);

		$this->m_crud->create_data("alamat_member", $data_lokasi);
		$id = $this->db->insert_id();
		$result['status'] = true;
		$result['id_lokasi'] = $id;

		echo json_encode($result);
	}

	/*Load data ulasan*/
	public function add_ulasan() {
		$result = array();

		$data_ulasan = array(
			'orders' => $_POST['orders'],
			'produk' => $_POST['produk'],
			'member' => $this->user,
			'rating_produk' => $_POST['kualitas'],
			'rating_pelayanan' => $_POST['pelayanan'],
			'ulasan' => $_POST['ulasan'],
			'tgl_ulasan' => date('Y-m-d H:i:s')
		);
		$this->m_crud->create_data("ulasan", $data_ulasan);

		$result['status'] = true;
		$result['res_orders'] = $_POST['orders'];
		$result['res_produk'] = $_POST['produk'];

		echo json_encode($result);
	}

	public function get_ulasan($id_produk, $page=1, $limit=4) {
		$result = array();
		$id_produk = base64_decode($id_produk);
		$page = base64_decode($page);
		$limit = base64_decode($limit);

		$get_ulasan = $this->m_crud->join_data("ulasan u", "u.id_ulasan, u.rating_produk, u.rating_pelayanan, ulasan, tgl_ulasan, m.id_member, m.nama nama_member, CONCAT('".base_url()."', m.foto) foto", "member m", "m.id_member=u.member", "u.status='1' AND u.produk='".$id_produk."'");

		if ($get_ulasan != null) {
			$result['status'] = true;
			$result['res_ulasan'] = $get_ulasan;
		} else {
			$result['status'] = false;
		}

		echo json_encode($result);
	}
	/*End*/

	/*Load data diskusi*/
	public function get_diskusi($id_produk, $member=null, $page=1) {
		$result = array();
		$id_produk = base64_decode($id_produk);
		$where = "dp.produk='".$id_produk."' AND dp.status='1' AND dp.response IS NULL";
		if ($member != null) {
			$where = " AND dp.member='".base64_decode($member)."'";
		}

		$get_diskusi = $this->m_crud->join_data("diskusi_produk dp", "dp.id_diskusi_produk, dp.tgl_diskusi, dp.produk, dp.comment diskusi, m.id_member, m.nama nama_user, m.foto foto_diskusi", "member m", "m.id_member=dp.member", $where, "dp.tgl_diskusi");

		if ($get_diskusi != null) {
			$list_diskusi = array();
			foreach ($get_diskusi as $row) {
				$get_comment = $this->m_crud->join_data("diskusi_produk dp", "dp.tgl_diskusi tgl_comment, dp.comment, m.id_member, IFNULL(m.nama, 'Admin Indokids') nama_comment, IFNULL(m.nama, 'admin_idk') verify, IFNULL(m.foto, 'assets/images/member/admin.png') foto_comment", array(array('table' => 'member m', 'type' => 'LEFT')), array("m.id_member=dp.member"), "dp.status='1' AND dp.response = '" . $row['id_diskusi_produk'] . "'");
				$comment = array();
				foreach ($get_comment as $row_comment) {
					array_push($comment, array('tgl_comment'=>$row_comment['tgl_comment'], 'comment'=>$row_comment['comment'], 'nama_comment'=>$row_comment['nama_comment'], 'foto' => base_url().$row_comment['foto_comment']));
				}
				$diskusi = array(
					'id_diskusi' => $row['id_diskusi_produk'],
					'tgl_diskusi' => $row['tgl_diskusi'],
					'produk' => $row['produk'],
					'diskusi' => $row['diskusi'],
					'nama_user' => $row['nama_user'],
					'foto' => base_url().$row['foto_diskusi'],
					'comment' => $comment
				);
				array_push($list_diskusi, $diskusi);
			}

			$result['res_diskusi'] = $list_diskusi;
			$result['status'] = true;
		} else {
			$result['status'] = false;
		}

		echo json_encode($result);
	}

	public function diskusi() {
		$result = array();
		$member = $this->user;
		$diskusi = $_POST['diskusi'];
		$produk = $_POST['produk'];

		$tgl = date('Y-m-d H:i:s');
		$paramDate = date('ymd');
		$max_code = $this->m_crud->get_data("diskusi_produk", "RIGHT(MAX(id_diskusi_produk), 6) max_code", "SUBSTR(id_diskusi_produk, 2, 6)='".$paramDate."' AND produk='".$produk."'")['max_code'];

		$code = 'D'.$paramDate.'/'.$produk.'/'.sprintf('%06d', $max_code+1);

		$data_comment = array(
			'id_diskusi_produk' => $code,
			'tgl_diskusi' => $tgl,
			'comment' => $diskusi,
			'produk' => $produk,
			'member' => $member
		);
		$this->m_crud->create_data("diskusi_produk", $data_comment);
		$result['status'] = true;
		$result['id_produk'] = $produk;

		echo json_encode($result);
	}

	public function comment($param='member') {
		$result = array();
		$member = $this->user;
		$id_comment = $_POST['id_comment'];
		$comment = $_POST['komentar'];
		$produk = $_POST['produk'];

		$tgl = date('Y-m-d H:i:s');
		$paramDate = date('ymd');
		$max_code = $this->m_crud->get_data("diskusi_produk", "RIGHT(MAX(id_diskusi_produk), 6) max_code", "SUBSTR(id_diskusi_produk, 2, 6)='".$paramDate."' AND produk='".$produk."'")['max_code'];

		$code = 'D'.$paramDate.'/'.$produk.'/'.sprintf('%06d', $max_code+1);

		$data_comment = array(
			'id_diskusi_produk' => $code,
			'tgl_diskusi' => $tgl,
			'comment' => $comment,
			'produk' => $produk,
			'response' => $id_comment
		);
		if ($param == 'member') {
			$data_comment['member'] = $member;
		}
		$this->m_crud->create_data("diskusi_produk", $data_comment);
		$result['status'] = true;
		$result['id_comment'] = $id_comment;

		echo json_encode($result);
	}

	public function get_comment($param='store') {
		$result = array();
		$comment = array();
		$id_diskusi = $_POST['id_diskusi'];

		$where = null;
		if ($param == 'store') {
			$where = " AND dp.status='1'";
		}

		$get_comment = $this->m_crud->join_data("diskusi_produk dp", "dp.id_diskusi_produk, dp.status, dp.tgl_diskusi tgl_comment, dp.comment, IFNULL(m.nama, 'Admin Indokids') nama_user2, IFNULL(m.nama, 'admin_idk') verify, IFNULL(m.foto, 'assets/images/member/admin.png') foto_comment", array(array("table"=>"member m", "type"=>"LEFT")), array("m.id_member=dp.member"), "dp.response='".$id_diskusi."'".$where, "dp.id_diskusi_produk, dp.tgl_diskusi ASC");

		if ($get_comment != null) {
			$result['status'] = true;
			foreach ($get_comment as $row) {
				array_push($comment, array('id_diskusi_produk'=>$row['id_diskusi_produk'], 'status'=>$row['status'], 'tgl_comment'=>$row['tgl_comment'], 'comment'=>$row['comment'], 'verify'=>$row['verify'], 'nama_comment'=>$row['nama_user2'], 'foto' => base_url().$row['foto_comment']));
			}
		} else {
			$result['status'] = false;
		}


		$result['res_comment'] = $comment;

		echo json_encode($result);
	}
	/*End*/

	public function get_transfer() {
		$result = array();
		$id_pembayaran = $_POST['id_pembayaran'];

		$get_bukti = $this->m_crud->get_data("pembayaran", "atas_nama, bank, no_rek, (jumlah + kode_unik) total, CONCAT('".base_url()."', bukti_transfer) gambar", "id_pembayaran='".$id_pembayaran."'");

		if ($get_bukti != null) {
			$result['status'] = true;
			$result['res_transfer'] = $get_bukti;
		} else {
			$result['status'] = false;
		}

		echo json_encode($result);
	}

	public function json() {
		echo json_encode(array(
			'kode_barang' => 'K01122',
			'nama' => 'NAMA BARANG',
			'deskripsi' => 'DESKRIPSI',
			'hrg_jual' => 100000
		));
	}

	public function simpan_produk() {
		$data = json_decode($_POST['produk'], true);
		$tgl = date('Y-m-d H:i:s');
		$get_produk = $this->m_crud->get_data("det_produk", "hrg_jual", "code='".$data['kode_barang']."'");

		if ($get_produk == null) {
			/*insert produk*/
			$data_produk = array(
				'kelompok' => '999999999',
				'merk' => '999999999',
				'nama' => $data['nama'],
				'deskripsi' => $data['deskripsi'],
				'code' => $data['kode_barang'],
				'tgl_input' => $tgl,
				'tgl_update' => $tgl,
				'pre_order' => '0',
				'free_return' => '0'
			);

			$this->m_crud->create_data("produk", $data_produk);
			$id_produk = $this->db->insert_id();

			/*insert det produk*/
			$det_produk = array(
				'produk' => $id_produk,
				'code' => $data['kode_barang'],
				'berat' => 0,
				'ukuran' => '-',
				'warna' => '-',
				/*'hrg_beli' => str_replace(',','',$_POST['hrg_beli']),*/
				'hrg_beli' => 0,
				'hrg_jual' => str_replace(',', '', $data['hrg_jual']),
				'hrg_varian' => 0,
				'hrg_sebelum' => str_replace(',', '', $data['hrg_jual'])
			);
			$this->m_crud->create_data("det_produk", $det_produk);
		} else {
			$data_produk = array(
				'nama' => $data['nama'],
				'deskripsi' => $data['deskripsi'],
				'tgl_update' => $tgl
			);

			$this->m_crud->update_data("produk", $data_produk, "code='".$data['kode_barang']."'");

			$det_produk = array(
				'hrg_jual' => str_replace(',', '', $data['hrg_jual']),
				'hrg_sebelum' => $get_produk['hrg_jual']
			);
			$this->m_crud->update_data("det_produk", $det_produk, "code='".$data['kode_barang']."'");
		}

		echo true;
	}

	public function mutasi() {
		$data = json_decode($_POST['mutasi'], true);
		$code = $this->m_website->generate_kode('adjustment', $data['tanggal']);
		$data_adjustment = array(
			'id_adjustment' => $code,
			'tgl_adjustment' => $data['tanggal'].' '.date('H:i:s'),
			'keterangan' => 'Mutasi dari BO',
			'user_detail' => '999999999'
		);

		$this->m_crud->create_data("adjustment", $data_adjustment);

		foreach ($data['detail'] as $item) {
			$get_produk = $this->m_crud->get_data("det_produk", "id_det_produk", "code='".$item['kode_produk']."'");
			if ($get_produk != null) {
				$det_adjustment = array(
					'adjustment' => $code,
					'det_produk' => $get_produk['id_det_produk'],
					'jenis' => $item['jenis'],
					'qty' => $item['qty']
				);
				$this->m_crud->create_data("det_adjustment", $det_adjustment);
			}
		}

		echo true;
	}

	public function get_stok() {
		$data_post = array(
			'in_brg' => json_encode(array('\'019327\'')),
			'tgl_awal' => '2018-06-08',
			'tgl_akhir' => '2018-06-08',
			'per_page' => 6,
			'page' => 1
		);

		$req_api = $this->m_website->request_api("kartu_stock", $data_post);

		echo $req_api;
	}

	public function send_invoice() {
		$list = '';
		for ($i=0; $i<5; $i++) {
			$list .= '
                <tr class="item">
                    <td>Produk</td>
                    <td style="text-align: right">' . number_format(10000) . '</td>
                </tr>
	        ';
		}
		$data = array(
			'id_orders' => 'TR10203040s',
			'tanggal' => date('Y-m-d H:i:s'),
			'penerima' => 'Loviana',
			'tlp' => '09121212',
			'total' => 100000,
			'bank' => 'BNI',
			'rek' => '0812321212',
			'an' => 'Indokids',
			'kurir' => 'JNE ~ YES',
			'ongkir' => '8000',
			'kode_unik' => '20',
			'list' => $list
		);

		//$get_email = $this->m_crud->get_data("member", "email", "id_member='".$member."'");
		$this->m_website->email_invoice('anashrulyusuf@gmail.com', json_encode($data));
	}

	public function get_voucher() {
		$result = array();

		$get_voucher = $this->m_crud->read_data("voucher", "*", "status='1' and (tgl_mulai > '".date('Y-m-d H:i:s')."' or '".date('Y-m-d H:i:s')."' between tgl_mulai and tgl_selesai)");

		if ($get_voucher != null) {
			$result['status'] = true;
			foreach ($get_voucher as $key => $item) {
				$get_voucher[$key]['gambar'] = base_url().$item['gambar'];
				if ($item['tgl_mulai'] > date('Y-m-d H:i:s')) {
					$get_voucher[$key]['status'] = 'Comming Soon';
				} else {
					$get_voucher[$key]['status'] = 'Available';
				}
			}
			$result['data'] = $get_voucher;
		} else {
			$result['status'] = false;
		}

		echo json_encode($result);
	}

	public function get_data($table, $action=5, $id=1){
		if(substr($table,0,6)=='return'){
			$table = str_replace('return_','',$table);
			$action = 'return';
		}

		$where=null;

		if(isset($_POST['where']) && $_POST['where']!=null){ $post_where=$_POST['where']; }

		if(isset($post_where) && $post_where!=null){ ($where==null)?null:$where.=" and "; $where.="(".$post_where.")"; }

		$page=$id;
		$config['per_page'] = $action;

		$select="*";
		$order = array(
			'berita'=>'tgl_berita desc',
			'kategori_berita'=>'nama asc',
			'home_slide' => 'id_home_slide asc'
		);
		$get_data = $this->m_crud->read_data($table, $select, $where, (isset($order[$table])?$order[$table]:null), null, $config['per_page'], ($page-1)*$config['per_page']);
		if($action=='return'){
			if(count($get_data) >= 1){
				$get_data = $this->tambah_data($table, $get_data);
				return json_encode(array('status'=>1, 'data'=>$get_data));
			} else {
				return json_encode(array('status'=>0));
			}
		} else {
			if(count($get_data) >= 1){
				$get_data = $this->tambah_data($table, $get_data);
				$data_return = array('status'=>1, 'data'=>$get_data);
				echo json_encode($data_return);
			} else {
				echo json_encode(array('status'=>0));
			}
		}
	}

	public function tambah_data($table, $get_data){
		if($table=='berita' || $table=='kategori_berita' || $table=='bank' || $table=='home_slide'){
			for($i=0; $i<count($get_data); $i++){
				if($get_data[$i]['gambar']!=null || $get_data[$i]['gambar']!=''){
					$get_data[$i]['gambar'] = base_url().$get_data[$i]['gambar'];
				} else {
					$get_data[$i]['gambar'] = $this->m_website->default_img_src();
				}
			}
		}

		return $get_data;
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

	public function forgot_password() {
       $this->m_website->email_to("anashrulyusuf@gmail.com",'1','2');
	}

	public function resset_password($response=null) {
		$result = array(
			'redirect' => base_url()
		);

		if ($response != null) {
			$response = json_decode(base64_decode($response), true);
			$get_data = $this->m_crud->get_data("member", "id_member", "id_member='".$response['id_member']."' AND token_resset_password='" . $response['token'] . "'");

			if ($get_data != null) {
				$new_password = $this->random_char();
				$options = array('cost' => 12);
				$password = password_hash($new_password, PASSWORD_BCRYPT, $options);

				$this->m_crud->update_data("member", array('password' => $password, 'token_resset_password'=>''), "id_member='" . $get_data['id_member'] . "'");
				$data_email = array(
					'email' => $response['email'],
					'password' => $new_password
				);
				$this->m_website->email_resset_password($data_email);

				$result['status'] = 'success';
				$result['message'] = 'Silahkan cek email anda untuk mendapatkan password baru';
			} else {
				$result['status'] = 'failed';
			}
		} else {
			$result['status'] = 'failed';
		}

		$this->load->view('site/resset_password', $result);
	}

	public function insert_poin() {
		$min_order = 100000;
		$id_orders = $_POST['kd_trx'];
		$get_total = (float)$_POST['total'];
		$kode_online = $_POST['ol_code'];

		$member = $this->m_crud->get_data("member", "id_member", "ol_code='".$kode_online."'");

		$check_data = $this->m_crud->get_data("poin", "kode_transaksi", "kode_transaksi='".$id_orders."'");

		if ($check_data==null && $get_total>=$min_order && $member!=null) {
			$this->m_crud->create_data("poin", array('kode_transaksi'=>$id_orders, 'member'=>$member['id_member'], 'poin'=>floor($get_total/$min_order), 'keterangan'=>'Pembelian NPOS'));
		}
	}

	public function minus_poin() {
		$id_orders = $_POST['kd_trx'];

		$check_data = $this->m_crud->get_data("poin", "*", "kode_transaksi='".$id_orders."'");

		if ($check_data!=null) {
			$this->m_crud->create_data("poin", array('kode_transaksi'=>$id_orders.'C', 'member'=>$check_data['member'], 'poin'=>$check_data['poin']*-1, 'keterangan'=>'Cancel Pembelian NPOS'));
			echo true;
		} else {
			echo true;
		}
	}

	public function register_member() {
		$response = array();
		$nama = $_POST['nama'];
		$email = $_POST['email'];
		$tlp = $_POST['tlp'];
		$id = md5($email);
		$new_password = $this->random_char();
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
			$data_member['jenis_kelamin'] = $_POST['jk'];
		}

		if (isset($_POST['tgl_lahir'])) {
			$data_member['tgl_lahir'] = date('Y-m-d', strtotime($_POST['tgl_lahir']));
		}

		if (isset($_POST['user_detail'])) {
			$data_member['user_detail'] = $_POST['user_detail'];
		}

		$check_email = $this->m_crud->get_data("member", "id_member", "email='".$email."'");
		if ($check_email == null) {
			$check_tlp = $this->m_crud->get_data("member", "id_member", "telepon='".$tlp."'");
			if ($check_tlp == null) {
				$response['status'] = true;
				$response['ol_code'] = $ol_code;
				$this->m_crud->create_data("member", $data_member);
				$this->m_website->email_new_account(array('email' => $email, 'password' => $new_password));
			} else {
				$response['status'] = false;
				$response['pesan'] = 'No telepon sudah terdaftar';
			}
		} else {
			$response['status'] = false;
			$response['pesan'] = 'Email sudah terdaftar';
		}

		echo json_encode($response);
	}

	public function get_member_poin($param=null) {
		$response = array();
		$kode = $_POST['ol_code'];
		$get_poin = $this->m_crud->get_join_data("member m", "m.ol_code, m.nama, IFNULL(SUM(p.poin), 0) poin, ifnull(m.telepon, 'Phone Number is Empty') telepon, m.email", array(array("table"=>"poin p", "type"=>"LEFT")), array("p.member=m.id_member and '".date('y')."' in (substr(p.kode_transaksi, 4, 2), substr(p.kode_transaksi, 3, 2))"), "(m.ol_code='".$kode."' or m.telepon like '%".substr($kode,3)."')");

		if ($get_poin != null) {
			$response['status'] = true;
			$response['data'] = $get_poin;
		} else {
			$response['status'] = false;
			$response['pesan'] = "Member tidak tersedia";
		}

		if ($param == 'return') {
			return json_encode($response);
		} else {
			echo json_encode($response);
		}
	}

	public function read_member_poin($param=null) {
		$response = array();
		$where = (isset($_POST['where'])?$_POST['where']:null);
		$get_poin = $this->m_crud->join_data("member m", "m.ol_code, m.email, IFNULL((select SUM(p_.poin) from poin p_ where p_.member=m.id_member and '".date('y')."' in (substr(p_.kode_transaksi, 4, 2), substr(p_.kode_transaksi, 3, 2))), 0) poin", array(array("table"=>"poin p", "type"=>"LEFT")), array("p.member=m.id_member"), $where);

		if (is_array($get_poin)) {
			$response['status'] = true;
			$response['data'] = $get_poin;
		} else {
			$response['status'] = false;
			$response['pesan'] = "Member tidak tersedia";
		}

		if ($param == 'return') {
			return json_encode($response);
		} else {
			echo json_encode($response);
		}
	}

	public function tukar_poin() {
		$response = array();
		$ol_code = $_POST['ol_code'];

		$member = $this->m_crud->get_data("member", "id_member", "ol_code='".$ol_code."'");
		$kd_trx = $this->m_website->generate_kode("poin", date('ymd'));
		if ($member != null) {
			$get_poin = json_decode($this->get_member_poin('return'), true)['data'];
			$poin = $get_poin['poin'];
			if ($_POST['poin'] >= $poin) {
				$response['status'] = false;
				$response['pesan'] = "Poin anda tidak cukup untuk ditukarkan";
			} else {
				$this->m_crud->create_data("poin", array('kode_transaksi' => $kd_trx, 'member' => $member['id_member'], 'poin' => abs($_POST['poin']) * -1, 'keterangan' => 'Penukaran Poin : ' . $_POST['ket']));
				$response['status'] = true;
				$response['pesan'] = "Poin berhasil ditukarkan";
			}
		} else {
			$response['status'] = false;
			$response['pesan'] = "Poin gagal ditukarkan";
		}

		echo json_encode($response);
	}

	public function batal_tukar_poin() {
		$id_orders = $_POST['kd_trx'];
		$ol_code = $_POST['ol_code'];

		$member = $this->m_crud->get_data("member", "id_member", "ol_code='".$ol_code."'");

		$this->m_crud->create_data("poin", array('kode_transaksi'=>$id_orders, 'member'=>$member['id_member'], 'poin'=>$_POST['poin'], 'keterangan'=>'Cancel Penukaran Poin'));
		echo true;
	}

	public function cek_telepon() {
		$where = "telepon='".$_POST['telepon']."' and id_member<>'".$_POST['id_member']."'";

		$cek_telepon = $this->m_crud->get_data("member", "telepon", $where);

		if ($cek_telepon == null) {
			echo 'true';
		} else {
			echo 'false';
		}
	}

	public function poin_tersedia() {
		$response = array();

		$get_poin = $this->m_crud->join_data("member m", "m.ol_code", array(array("table"=>"poin p", "type"=>"LEFT")), array("p.member=m.id_member"), "'".date('y')."' in (substr(p.kode_transaksi, 4, 2), substr(p.kode_transaksi, 3, 2))", null, "m.ol_code", 0, 0, "sum(p.poin) > 0");

		if (is_array($get_poin) && count($get_poin) > 0) {
			$response['status'] = true;
			$list = array();
			foreach ($get_poin as $item) {
				array_push($list, '\''.$item['ol_code'].'\'');
			}
			$response['data'] = $list;
		} else {
			$response['status'] = false;
			$response['pesan'] = "Data tidak tersedia";
		}

		echo json_encode($response);
	}
}