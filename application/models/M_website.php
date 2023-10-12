<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_website extends CI_Model {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		/*if(date('H:i:s')>'13:00:00' && date('H:i:s')<'15:00:00'){
			$this->load->dbutil();
			$prefs = array(
				//'tables'        => array('table1', 'table2'),   // Array of tables to backup.
				'ignore'        => array(),                     // List of tables to omit from the backup
				'format'        => 'txt',                       // gzip, zip, txt
				'filename'      => 'mybackup_'.date('md').'.sql',              // File name - NEEDED ONLY WITH ZIP FILES
				'add_drop'      => TRUE,                        // Whether to add DROP TABLE statements to backup file
				'add_insert'    => TRUE,                        // Whether to add INSERT data to backup file
				'newline'       => "\n"                         // Newline character used in backup file
			);
			if(date('d') > 10){ delete_files('assets/database/'.(date('m')-1), TRUE); }
			if(read_file('assets/database/'.date('m').'/'.$prefs['filename']) == null){
				$backup = $this->dbutil->backup($prefs);
				//$backup = $this->dbutil->backup();
				//$this->load->helper('file');
				write_file('assets/database/'.date('m').'/'.$prefs['filename'], $backup);
			}
			//$this->load->helper('download');
			//force_download($prefs['filename'], $backup);
		}*/
		$this->api = base_url().'api/';
		$this->api_interlocal = 'https://technopark.smkn14bdg.sch.id/api/';

	}

    public function struktur_email($to, $subject, $message){
        $config = array(
            'protocol' 	=> 'smtp', 											/********* default *********/
            'smtp_host' => 'ssl://smtp.googlemail.com', /***** incoming server *****/
            'smtp_port' => 465, 												/***** outgoing server *****/
            'smtp_user' => 'ansrlysf@gmail.com', 				/********* username ********/
            'smtp_pass' => 'acuy1234', 									/********* password ********/
            'mailtype' 	=> 'html',
            'charset' 	=> 'iso-8859-1'
        );
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from('ansrlysf@gmail.com', 'KYLAFOOD');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        return true;

    }


    public function create_notif($data_notif){
        $fields = array(
            'app_id' => "9a74b710-c5f3-441c-b3d5-de924945e5f9",
            'data' => array("type"=>"order"),
            'headings' => array("en" => $data_notif['head']),
            'contents' => array("en" => $data_notif['content']),
			'included_segments' => array('All'),
			'url'=>base_url().'site/'
        );
        // if(isset($data_notif['include_player_ids']) && $data_notif['include_player_ids']!=null){
        //     $fields['include_player_ids'] = $data_notif['include_player_ids'];
        // }
        // if(isset($data_notif['big_picture']) && $data_notif['big_picture']!=null){
        //     $fields['big_picture'] = $data_notif['big_picture'];
        // }
        // if(isset($data_notif['included_segments']) && $data_notif['included_segments']!=null){
        //     //'included_segments' => array('Active Users'),
        //     $fields['included_segments'] = $data_notif['included_segments'];
        // }

		$fields = json_encode($fields,JSON_PRETTY_PRINT);
		// echo $fields;die();
        //print("\nJSON sent:\n");
        //print($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Authorization: Basic YzY0NzQ3ODgtZDkzYy00NTBjLWFjZTctNzlhYjNhNzA5Y2I5'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }



	public function request_api_interlocal($param="check_server", $data="", $method="POST") {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		if ($method == "POST") {
			curl_setopt($ch, CURLOPT_URL,$this->api_interlocal.$param);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		} else {
			curl_setopt($ch, CURLOPT_URL,$this->api_interlocal.$param.$data);
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec ($ch);

		curl_close ($ch);

		return $result;
	}

	public function get_depart($id = null){
		return isset($_GET['depart'])?$_GET['depart']:(isset($_POST['depart'])?$_POST['depart']:$this->session->depart);
	}

	public function get_lokasi($id = null){
		return isset($_GET['lokasi'])?$_GET['lokasi']:(isset($_POST['lokasi'])?$_POST['lokasi']:$this->session->lokasi);
	}

	public function lokasi($id = null, $field = '*'){
		if($id==null){ $id = $id = $this->m_website->get_lokasi(); }
		$data = $this->m_crud->get_data('lokasi', $field, "kode = '".$id."'");
		if(substr($field,0,1)=='*'){ return $data; }
		else{ return $data[$field]; }
	}

	public function meta_data($meta_data=null){
		if($meta_data!=null){
			//meta website
			if(isset($meta_data['website'])){
				//<meta name="google-site-verification" content="bExkQFnEooJVIoZIm70CO8H8Yjx_FfyyCC6hNE_SeoA" />
				//<meta name="msvalidate.01" content="3123382E32539EBE8C53C2CA69F7510D" />
				$meta['website']['Content-Type']	= "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
				$meta['website']['keywords']		= "<meta name='keywords' content='".$this->m_website->domain().', '.$meta_data['website']['keywords']."' />";
				$meta['website']['title']			= "<meta name='title' content='".$meta_data['website']['title']."' />";
				$meta['website']['image_src']		= "<link rel='image_src' href='".$meta_data['website']['image_src']."' />";
				$meta['website']['description']		= "<meta name='description' content='".strip_tags($meta_data['website']['description'])."' />";
				$meta['website']['author']			= "<meta name='author' content='".$this->m_website->domain()."' />";
			}
			if(isset($meta_data['facebook'])){
				//meta facebook https://developers.facebook.com/tools/debug/ - https://developers.facebook.com/tools/debug/og/object/
				//<meta property="fb:app_id" content="191402794307447" />
				//<meta property="og:site_name" content="NACTS" />
				//$meta['facebook']['admins']			= "<meta property='fb:admins' content='".$meta_data['facebook']['admins']."' />";
				$meta['facebook']['url']			= "<meta property='og:url' content='".$meta_data['facebook']['url']."' />";
				$meta['facebook']['type']			= "<meta property='og:type' content='".$meta_data['facebook']['type']."' />";
				$meta['facebook']['title']			= "<meta property='og:title' content='".$meta_data['facebook']['title']."' />";
				$meta['facebook']['image']			= "<meta property='og:image' content='".$meta_data['facebook']['image']."' />";
				$meta['facebook']['description']	= "<meta property='og:description' content='".strip_tags($meta_data['facebook']['description'])."' />";
			}
			//meta twitter belum dicoba
			/*<meta name="twitter:card" content="summary" />
			<meta name="twitter:url" content="<?=base_url()?>artikel/detail/<?=$artikel['id_artikel']?>" />
			<meta name="twitter:title" content="<?=$artikel['nama']?>" />
			<meta name="twitter:description" content="<?=strip_tags($artikel['keterangan'])?>" />
			<meta name="twitter:image" content="<?=base_url()?>uploads/images/artikel/<?=$artikel['foto']?>" />*/
			return $meta;
		} else{ return $meta = null; }
	}

	public function logo($width="200px", $height="50px"){
		return '<img width="'.$width.'" height="'.$height.'" src="'.$this->config->item('url').$this->m_website->site_data()->logo.'"/>';
	}

	public function usia($tanggal_lahir){
		return (int) ($this->m_website->selisih_bulan(date('Y-m-d'), $tanggal_lahir) / 12);
	}

	public function selisih_bulan($date1, $date2){
		$date1 = new DateTime($date1);
		$date2 = new DateTime($date2);
		$diff =  $date1->diff($date2);

		//$months = $diff->y * 12 + $diff->m + $diff->d / 30;
		//return (int) round($months);

		$months = (($diff->format('%y') * 12) + $diff->format('%m'));
		return $months;
	}

	public function selisih_hari($date1, $date2){
		$hari = strtotime($date1)-strtotime($date2);
		$jumlah	= ($hari/3600)/24;

		$jumlah = explode('.',$jumlah);
		$jumlah = $jumlah[0];

		return $jumlah;
	}

	public function multi_periode($date1, $date2){
		$y_awal = (int) substr($date1, 0, 4);
		$y_akhir = (int) substr($date2, 0, 4);
		$m_awal = (int) substr($date1, 5, 2);
		$m_akhir = (int) substr($date2, 5, 2);
		$year = ($y_akhir - $y_awal) * 12;

		$awal = $m_awal;
		$akhir = $m_akhir + $year;
		$year = $y_awal;
		$month = $m_awal;

		$data = null;
		$array = 0;
		for($i=$awal; $i<=$akhir; $i++){
			if($i % 12 == 1 && $i != 1){ $year = $year + 1; $month = $month - 12; }
			$data[$array] = $year.'-'.$month.'-01';
			if($y_awal == $year && $m_awal == $month){
				$end = date('Y-m-d', strtotime('+1 month', strtotime($data[$array])));
				$data[$array] = array($date1, date('Y-m-d', strtotime('-1 day', strtotime($end))));
			} else if($y_akhir == $year && $m_akhir == $month){
				$data[$array] = array($data[$array], $date2);
			} else{
				$end = date('Y-m-d', strtotime('+1 month', strtotime($data[$array])));
				$data[$array] = array($data[$array], date('Y-m-d', strtotime('-1 day', strtotime($end))));
			}
			$array++;
			$month++;
		}
		return $data;
	}

	public function login($username){
		$query = "select ud.id_user, ud.nama, ua.password, ud.alamat
					from user_akun ua, user_detail ud
					where ud.id_user=ua.user_detail AND ua.status='1' AND ua.username = '$username'";
		$data = $this->db->query($query);
		if($data->num_rows()==1){
			return $data->row();
		}else{
			return false;
		}
	}

	public function user($user){
		$query = "select *
					from user_akun
					where user_id = '$user';";
		$data = $this->db->query($query);
		if($data){
			return $data->row();
		}else{
			return false;
		}
	}

	public function user_data($user){
		$data = $this->m_crud->join_data('user_detail ud', 'ud.id_user, ud.nama, ud.tgl_lahir, ud.foto, ud.alamat, ud.email, ud.telepon, ul.nama level, ul.level access',
			array('user_akun ua', 'user_level ul'),
			array('ua.user_detail=ud.id_user', 'ua.user_level=ul.id_level'),
			"ud.id_user = '".$user."'");

		if($data){
			return $data[0];
		}else{
			return false;
		}
	}

	public function member_data($user){
		$data = $this->m_crud->join_data('member m', 'm.id_member, m.email, m.nama, m.jenis_kelamin, m.tgl_lahir, m.telepon, m.foto, m.ol_code, ifnull(sum(p.poin), 0) poin',
			array(array('table'=>'poin p', 'type'=>'LEFT')), array("m.id_member=p.member and '".date('y')."' in (substr(p.kode_transaksi, 4, 2), substr(p.kode_transaksi, 3, 2))"), "m.id_member = '".$user."'");

		if($data){
			return $data[0];
		}else{
			return false;
		}
	}

	public function user_access_data($user){

		$query = "select *
					from user_akun, user_level
					where user_akun.user_level = user_level.id_level
					and user_akun.user_detail = '$user';";
		$data = $this->db->query($query);
		if($data){
			return $data->row();
		}else{
			return false;
		}
	}

	public function site_data($param = '1111'){
		$query = "select * from site where id_site = '$param'";
		$data = $this->db->query($query);
		if($data){
			return $data->row();
		}else{
			return false;
		}
	}

	public function edit_access_user($id, $new){
		$data = array (
			'level'  => $new,
		);
		$this->db->where('id_level', $id);
		if($this->db->update('user_level', $data)){
			return true;
		}else{
			return false;
		}
	}

	public function harga_rata($barang=null, $tanggal=null){
		$where = null;
		if($barang != null){ ($where==null)?null:$where.=" and "; $where.="barang = '".$barang."'"; }
		if($tanggal != null){ ($where==null)?null:$where.=" and "; $where.="tanggal <= '".substr($tanggal,0,10)." 23:59:59'"; }
		$last_rata = $this->m_crud->read_data('barang_rata', 'harga', $where, 'tanggal desc', null, 1);
		if(isset($last_rata[0]['harga'])){
			$harga_rata = $last_rata[0]['harga'];
		} else {
			$where = null;
			if($barang != null) {
				($where == null) ? null : $where .= " and ";
				$where .= "fasilitas = '" . $barang . "'";
			}
			$last_rata = $this->m_crud->get_data('saldo_awal', 'harga', $where);
			if($last_rata == null) {
				$last_rata = $this->m_crud->get_join_data('det_hpp dh', 'dh.harga', 'hpp h', 'h.id_hpp=dh.hpp', $where, 'h.id_hpp DESC');
			}
			$harga_rata = $last_rata['harga'];
		}

		return $harga_rata;
	}

	public function set_harga_rata($barang=null, $tanggal=null) {
		//set_time_limit(3600); // this way
		//ini_set('max_execution_time', 3600); // or this way
		$where = "pr.pr = 1 and prd.fasilitas = pod.fasilitas";
		if($barang != null){ ($where==null)?null:$where.=" and "; $where.="prd.fasilitas = '".$barang."'"; }
		if($tanggal != null){ ($where==null)?null:$where.=" and "; $where.="pr.tanggal < '".$tanggal."'"; }

		$harga_rata_1 = $this->m_crud->join_data('purchase_receiving as pr', 'pr.tanggal, pr.id_purchase_receiving, prd.fasilitas, (prd.qty - (ifnull((select ifnull(prrd.qty,0) from purc_receiv_return_detail as prrd join purc_receiv_return as prr on prrd.id_purc_receiv_return = prr.id_purc_receiv_return where purchase_receiving = pr.id_purchase_receiving and prrd.fasilitas = prd.fasilitas group by pr.id_purchase_receiving),0)) - (ifnull((select ifnull(prtd.qty,0) from purchase_return_detail as prtd join purchase_return as prt on prtd.id_purchase_return = prt.id_purchase_return join purchase_invoice as pi on prt.id_purchase_invoice = pi.id_purchase_invoice join purchase_invoice_detail as pid on pi.id_purchase_invoice = pid.id_purchase_invoice where prtd.fasilitas = prd.fasilitas and pid.fasilitas = prd.fasilitas and pid.id_receiving = prd.id_purchase_receiving group by prd.id_purchase_receiving),0))) as qty, pod.harga',
			array('purchase_receiving_detail as prd', 'purchase_order as po', 'purchase_order_detail as pod'),
			array('pr.id_purchase_receiving = prd.id_purchase_receiving', 'pr.id_purchase_order=po.id_purchase_order', 'po.id_purchase_order=pod.id_purchase_order'),
			$where, 'pr.tanggal desc', null, (($tanggal!=null)?(1):(null))
		);

		$harga_rata = $harga_rata_1;
		if($tanggal!=null){
			$where = "pr.pr = 1 and prd.fasilitas = pod.fasilitas";
			if($barang != null){ ($where==null)?null:$where.=" and "; $where.="prd.fasilitas = '".$barang."'"; }
			if($tanggal != null){ ($where==null)?null:$where.=" and "; $where.="pr.tanggal >= '".$tanggal."'"; }
			$harga_rata_2 = $this->m_crud->join_data('purchase_receiving as pr', 'pr.tanggal, pr.id_purchase_receiving, prd.fasilitas, (prd.qty - (ifnull((select ifnull(prrd.qty,0) from purc_receiv_return_detail as prrd join purc_receiv_return as prr on prrd.id_purc_receiv_return = prr.id_purc_receiv_return where purchase_receiving = pr.id_purchase_receiving and prrd.fasilitas = prd.fasilitas group by pr.id_purchase_receiving),0)) - (ifnull((select ifnull(prtd.qty,0) from purchase_return_detail as prtd join purchase_return as prt on prtd.id_purchase_return = prt.id_purchase_return join purchase_invoice as pi on prt.id_purchase_invoice = pi.id_purchase_invoice join purchase_invoice_detail as pid on pi.id_purchase_invoice = pid.id_purchase_invoice where prtd.fasilitas = prd.fasilitas and pid.fasilitas = prd.fasilitas and pid.id_receiving = prd.id_purchase_receiving group by prd.id_purchase_receiving),0))) as qty, pod.harga',
				array('purchase_receiving_detail as prd', 'purchase_order as po', 'purchase_order_detail as pod'),
				array('pr.id_purchase_receiving = prd.id_purchase_receiving', 'pr.id_purchase_order=po.id_purchase_order', 'po.id_purchase_order=pod.id_purchase_order'),
				$where, 'pr.tanggal desc'
			);
			if($harga_rata_2 != null){ $harga_rata = array_merge($harga_rata_1, $harga_rata_2); }
		}
		asort($harga_rata);
		$this->db->trans_begin();
		foreach($harga_rata as $row){
			//$stock = $this->m_website->stockonhand_barang($row['fasilitas'], $row['tanggal']);
			$stock = $this->m_website->stockonhand_barang_2($row['fasilitas'], $row['tanggal']);
			$last_rata = $this->m_crud->get_data('barang_rata', 'harga', "barang = '".$row['fasilitas']."' and trx = '".$row['id_purchase_receiving']."'");
			if($last_rata==null){
				$last_rata = $this->m_crud->read_data('barang_rata', 'harga', "barang = '".$row['fasilitas']."' and tanggal < '".$row['tanggal']."'", 'tanggal desc', null, 1);
				if(isset($last_rata[0]['harga'])){
					$this->m_crud->create_data('barang_rata', array(
						'barang'=>$row['fasilitas'],
						'trx'=>$row['id_purchase_receiving'],
						'tanggal'=>$row['tanggal'],
						'harga'=>(($stock*$last_rata[0]['harga'])+($row['qty']*$row['harga']))/(($stock+$row['qty'])!=0?($stock+$row['qty']):1)
					));
				} else {
					$last_rata = $this->m_crud->get_data('saldo_awal', 'harga', "fasilitas = '".$row['fasilitas']."'");
					if($last_rata == null){ $last_rata = $this->m_crud->get_data('barang', 'harga', "fasilitas = '".$row['fasilitas']."'"); }
					$this->m_crud->create_data('barang_rata', array(
						'barang'=>$row['fasilitas'],
						'trx'=>$row['id_purchase_receiving'],
						'tanggal'=>$row['tanggal'],
						'harga'=>(($stock*$last_rata['harga'])+($row['qty']*$row['harga']))/(($stock+$row['qty'])!=0?($stock+$row['qty']):1)
					));
				}
			} else {
				$last_rata = $this->m_crud->read_data('barang_rata', 'harga', "barang = '".$row['fasilitas']."' and tanggal < '".$row['tanggal']."'", 'tanggal desc', null, 1);
				if(isset($last_rata[0]['harga'])){
					$this->m_crud->update_data('barang_rata', array(
						'harga'=>(($stock*$last_rata[0]['harga'])+($row['qty']*$row['harga']))/(($stock+$row['qty'])!=0?($stock+$row['qty']):1)
						//'harga'=>$row['qty']
					), "barang = '".$row['fasilitas']."' and trx = '".$row['id_purchase_receiving']."' and tanggal = '".$row['tanggal']."'");
				} else {
					$last_rata = $this->m_crud->get_data('saldo_awal', 'harga', "fasilitas = '".$row['fasilitas']."'");
					if($last_rata == null){ $last_rata = $this->m_crud->get_data('barang', 'harga', "fasilitas = '".$row['fasilitas']."'"); }
					$this->m_crud->update_data('barang_rata', array(
						'harga'=>(($stock*$last_rata['harga'])+($row['qty']*$row['harga']))/(($stock+$row['qty'])!=0?($stock+$row['qty']):1)
						//'harga'=>$row['qty']
					), "barang = '".$row['fasilitas']."' and trx = '".$row['id_purchase_receiving']."' and tanggal = '".$row['tanggal']."'");
				}
			}
			$this->m_website->edit_harga_rata_tr($row['fasilitas'], $row['tanggal']);
		}
		if ($this->db->trans_status() === FALSE){ $this->db->trans_rollback(); }
		else { $this->db->trans_commit(); }
	}

	public function edit_harga_rata_tr($barang=null, $tanggal=null){
		$harga_rata = $this->m_website->harga_rata($barang, $tanggal);

		$where = null;
		if($tanggal != null){ ($where==null)?null:$where.=" and "; $where.="ajs.tanggal >= '".$tanggal."'"; }
		($where==null)?null:$where = $where;
		$ajs = $this->m_crud->read_data('adjustment_stock ajs', 'id_adjustment_stock', $where);
		foreach($ajs as $row){
			$where = "ajs.id_adjustment_stock = '".$row['id_adjustment_stock']."'";
			if($barang != null){ ($where==null)?null:$where.=" and "; $where.="ajsd.fasilitas = '".$barang."'"; }
			if($tanggal != null){ ($where==null)?null:$where.=" and "; $where.="ajs.tanggal >= '".$tanggal."'"; }
			($where==null)?null:$where = " where ".$where;
			$this->db->query("update adjustment_stock_detail ajsd join adjustment_stock ajs on ajsd.id_adjustment_stock = ajs.id_adjustment_stock join acc_general_journal gj on gj.id_trx = ajs.id_adjustment_stock set ajsd.harga = ".$harga_rata.", gj.debit = if(gj.debit <> 0, (ajsd.qty * ajsd.harga), 0), gj.credit = if(gj.credit <> 0, (ajsd.qty * ajsd.harga), 0)".$where);
		}

		$where = null;
		if($tanggal != null){ ($where==null)?null:$where.=" and "; $where.="aw.tanggal >= '".$tanggal."'"; }
		($where==null)?null:$where = $where;
		$aw = $this->m_crud->read_data('adjustment_waste aw', 'id_adjustment_waste', $where);
		foreach($aw as $row){
			$where = "aw.id_adjustment_waste = '".$row['id_adjustment_waste']."'";
			if($barang != null){ ($where==null)?null:$where.=" and "; $where.="awd.fasilitas = '".$barang."'"; }
			if($tanggal != null){ ($where==null)?null:$where.=" and "; $where.="aw.tanggal >= '".$tanggal."'"; }
			($where==null)?null:$where = " where ".$where;
			$this->db->query("update adjustment_waste_detail awd join adjustment_waste aw on awd.id_adjustment_waste = aw.id_adjustment_waste join acc_general_journal gj on gj.id_trx = aw.id_adjustment_waste set awd.harga = ".$harga_rata.", gj.debit = if(gj.debit <> 0, (awd.qty * awd.harga), 0), gj.credit = if(gj.credit <> 0, (awd.qty * awd.harga), 0)".$where);
		}

		$where = null;
		if($tanggal != null){ ($where==null)?null:$where.=" and "; $where.="csr.tanggal >= '".$tanggal."'"; }
		($where==null)?null:$where = $where;
		$csr = $this->m_crud->read_data('comp_send_req csr', 'id_comp_send_req', $where);
		foreach($csr as $row){
			$where = "csr.id_comp_send_req = '".$row['id_comp_send_req']."'";
			if($barang != null){ ($where==null)?null:$where.=" and "; $where.="csrd.barang = '".$barang."'"; }
			if($tanggal != null){ ($where==null)?null:$where.=" and "; $where.="csr.tanggal >= '".$tanggal."'"; }
			($where==null)?null:$where = " where ".$where;

			$this->db->query("update comp_send_req_det csrd join comp_send_req csr on csrd.comp_send_req = csr.id_comp_send_req join receiveboard_trx_barang rtc on csr.board_order = rtc.trx_id and csr.tanggal = rtc.tgl_done and csrd.barang = rtc.barang set csrd.harga = ".$harga_rata.", rtc.harga = ".$harga_rata." ".$where);

			$select_jumlah = "select sum(csrd_.qty * csrd_.harga) from comp_send_req_det csrd_ join comp_send_req csr_ on csrd_.comp_send_req = csr_.id_comp_send_req";
			$jumlah = $select_jumlah." where csr_.board_order = rtc.trx_id and csr_.tanggal = rtc.tgl_done";
			$this->db->query("update comp_send_req_det csrd join comp_send_req csr on csrd.comp_send_req = csr.id_comp_send_req join receiveboard_trx_barang rtc on csr.board_order = rtc.trx_id and csr.tanggal = rtc.tgl_done join acc_general_journal gj on gj.id_trx = csr.board_order and csr.tanggal <= gj.tanggal set gj.debit = if(gj.debit <> 0, (".$jumlah."), 0), gj.credit = if(gj.credit <> 0, (".$jumlah."), 0)".$where);

			$select_jumlah = "select sum(rtc_.qty_return * csrd_.harga) from comp_send_req_det csrd_ join comp_send_req csr_ on csrd_.comp_send_req = csr_.id_comp_send_req join receiveboard_trx_barang rtc_ on csr_.board_order = rtc_.trx_id and csr_.tanggal = rtc_.tgl_done and csrd_.barang = rtc_.barang";
			$jumlah = $select_jumlah." where csr_.board_order = rtc.trx_id and csr_.tanggal = rtc.tgl_done and rtc_.qty_return > 0";
			$this->db->query("update comp_send_req_det csrd join comp_send_req csr on csrd.comp_send_req = csr.id_comp_send_req join receiveboard_trx_barang rtc on csr.board_order = rtc.trx_id and csr.tanggal = rtc.tgl_done join acc_general_journal gj on gj.id_trx = csr.board_order and rtc.tgl_retur <= gj.tanggal set gj.debit = if(gj.debit <> 0, (".$jumlah."), 0), gj.credit = if(gj.credit <> 0, (".$jumlah."), 0)".$where." and rtc.qty_return > 0 and rtc.tgl_retur <= gj.tanggal");

			$select_jumlah = "select sum((csrd_.qty-rtc_.qty_return) * csrd_.harga) from comp_send_req_det csrd_ join comp_send_req csr_ on csrd_.comp_send_req = csr_.id_comp_send_req join receiveboard_trx_barang rtc_ on csr_.board_order = rtc_.trx_id and csr_.tanggal = rtc_.tgl_done and csrd_.barang = rtc_.barang";
			$jumlah = $select_jumlah." where csr_.board_order = dotd.board_order and csr_.tanggal <= dot.tanggal";
			$this->db->query("update comp_send_req_det csrd join comp_send_req csr on csrd.comp_send_req = csr.id_comp_send_req join do_tempo_detail dotd on csr.board_order = dotd.board_order join do_tempo dot on dotd.id_do_tempo = dot.id_do_tempo and csr.tanggal <= dot.tanggal join acc_general_journal gj on gj.id_trx = dot.id_do_tempo and dot.tanggal <= gj.tanggal set gj.debit = if(gj.debit <> 0, (".$jumlah."), 0), gj.credit = if(gj.credit <> 0, (".$jumlah."), 0)".$where);

			$jumlah = $select_jumlah." where csr_.board_order = dotrd.board_order and csr_.tanggal <= dotr.tanggal";
			$this->db->query("update comp_send_req_det csrd join comp_send_req csr on csrd.comp_send_req = csr.id_comp_send_req join dot_return_detail dotrd on csr.board_order = dotrd.board_order join dot_return dotr on dotrd.id_dot_return = dotr.id_dot_return and csr.tanggal <= dotr.tanggal join acc_general_journal gj on gj.id_trx = dotr.id_dot_return and dotr.tanggal <= gj.tanggal set gj.debit = if(gj.debit <> 0, (".$jumlah."), 0), gj.credit = if(gj.credit <> 0, (".$jumlah."), 0)".$where);

			$jumlah = $select_jumlah." where csr_.board_order = mrd.board_order and csr_.tanggal <= mr.tanggal";
			$this->db->query("update comp_send_req_det csrd join comp_send_req csr on csrd.comp_send_req = csr.id_comp_send_req join marks_received_detail mrd on csr.board_order = mrd.board_order join marks_received mr on mrd.id_marks_received = mr.id_marks_received and csr.tanggal <= mr.tanggal join acc_general_journal gj on gj.id_trx = mr.id_marks_received and mr.tanggal <= gj.tanggal set gj.debit = if(gj.debit <> 0, (".$jumlah."), 0), gj.credit = if(gj.credit <> 0, (".$jumlah."), 0)".$where);

			$jumlah = $select_jumlah." where csr_.board_order = mrd.board_order and csr_.tanggal <= mr.tanggal";
			$this->db->query("update comp_send_req_det csrd join comp_send_req csr on csrd.comp_send_req = csr.id_comp_send_req join mark_return_detail mrd on csr.board_order = mrd.board_order join mark_return mr on mrd.id_mark_return = mr.id_mark_return and csr.tanggal <= mr.tanggal join acc_general_journal gj on gj.id_trx = mr.id_mark_return and mr.tanggal <= gj.tanggal set gj.debit = if(gj.debit <> 0, (".$jumlah."), 0), gj.credit = if(gj.credit <> 0, (".$jumlah."), 0)".$where);

			$jumlah = $select_jumlah." where csr_.board_order = dod.board_order and csr_.tanggal <= do.tanggal";
			$this->db->query("update comp_send_req_det csrd join comp_send_req csr on csrd.comp_send_req = csr.id_comp_send_req join delivery_order_detail dod on csr.board_order = dod.board_order join delivery_order do on dod.id_delivery_order = do.id_delivery_order and csr.tanggal <= do.tanggal join acc_general_journal gj on gj.id_trx = do.id_delivery_order and do.tanggal <= gj.tanggal set gj.debit = if(gj.debit <> 0, (".$jumlah."), 0), gj.credit = if(gj.credit <> 0, (".$jumlah."), 0)".$where);

			$jumlah = $select_jumlah." where csr_.board_order = drd.board_order and csr_.tanggal <= dr.tanggal";
			$this->db->query("update comp_send_req_det csrd join comp_send_req csr on csrd.comp_send_req = csr.id_comp_send_req join delivery_return_detail drd on csr.board_order = drd.board_order join delivery_return dr on drd.id_delivery_return = dr.id_delivery_return and csr.tanggal <= dr.tanggal join acc_general_journal gj on gj.id_trx = dr.id_delivery_return and dr.tanggal <= gj.tanggal set gj.debit = if(gj.debit <> 0, (".$jumlah."), 0), gj.credit = if(gj.credit <> 0, (".$jumlah."), 0)".$where);

			$coa_proses = $this->m_accounting->acc_trx(3, 1, 'Proses'); // coa cogs git invoice
			$jumlah = $select_jumlah." where csr_.board_order = ivd.board_order and csr_.tanggal <= iv.tanggal";
			$this->db->query("update comp_send_req_det csrd join comp_send_req csr on csrd.comp_send_req = csr.id_comp_send_req join invoice_detail ivd on csr.board_order = ivd.board_order join invoice iv on ivd.id_invoice = iv.id_invoice and csr.tanggal <= iv.tanggal join acc_general_journal gj on gj.id_trx = iv.id_invoice and iv.tanggal <= gj.tanggal and coa in (".$coa_proses['debit'].",".$coa_proses['credit'].") set gj.debit = if(gj.debit <> 0, (".$jumlah."), 0), gj.credit = if(gj.credit <> 0, (".$jumlah."), 0)".$where);
		}

		//$board = $this->m_crud->join_data('comp_send_req csr');

		/* transaksi yang belum update otomatis harga rata
		$where_tanggal = "(tanggal >= '".$tgl_awal." 00:00:00' and tanggal <= '".$tgl_akhir." 23:59:59')";
		$receiving = $this->m_crud->join_data('purchase_receiving as pr', "tanggal, pr.id_purchase_receiving as trx, qty as masuk, 0 as keluar, 'PURCHASE RECEIVING' as descrip", 'purchase_receiving_detail as prd', 'pr.id_purchase_receiving = prd.id_purchase_receiving', 'fasilitas = '.$_GET['trx'].' and pr = 1 and '.$where_tanggal);
		$receiving_return = $this->m_crud->join_data('purc_receiv_return as prr', "tanggal, prr.id_purc_receiv_return as trx, 0 as masuk, qty as keluar, 'RECEIVING RETURN' as descrip", 'purc_receiv_return_detail as prrd', 'prr.id_purc_receiv_return = prrd.id_purc_receiv_return', 'fasilitas = '.$_GET['trx'].' and pr = 1 and '.$where_tanggal);
		$purchase_return = $this->m_crud->join_data('purchase_return as pr', "tanggal, pr.id_purchase_return as trx, 0 as masuk, qty as keluar, 'PURCHASE RETURN' as descrip", 'purchase_return_detail as prd', 'pr.id_purchase_return = prd.id_purchase_return', 'fasilitas = '.$_GET['trx'].' and pr = 1 and '.$where_tanggal);
		$stock_opname = $this->m_crud->join_data('stock_opname as so', "tanggal, so.id_stock_opname as trx, (if(tipe='+',diff,0)) as masuk, (if(tipe='-',diff,0)) as keluar, 'STOCK OPNAME' as descrip", 'stock_opname_detail as sod', 'so.id_stock_opname = sod.id_stock_opname', 'fasilitas = '.$_GET['trx'].' and '.$where_tanggal);
		*/
	}

	public function stockonhand_barang($id, $tanggal = null){
		if($tanggal == null){
			$masuk = "ifnull((select ifnull(sum(qty_return),0) from receiveboard_trx_barang as rtc where barang = comp.fasilitas and qty_return > 0),0) + ifnull((select ifnull(sum(qty),0) from purchase_receiving_detail as prd join purchase_receiving as pr on prd.id_purchase_receiving = pr.id_purchase_receiving where prd.fasilitas = comp.fasilitas and pr = 1),0) + ifnull((select ifnull(sum(qty),0) from adjustment_stock_detail as ajsd join adjustment_stock as ajs on ajsd.id_adjustment_stock = ajs.id_adjustment_stock where ajsd.fasilitas = comp.fasilitas and tipe = '+'),0) + ifnull((select ifnull(sum(diff),0) from stock_opname_detail as sod join stock_opname as so where sod.fasilitas = comp.fasilitas and tipe = '+'),0)";
			$keluar = "ifnull((select ifnull(sum(qty),0) from comp_send_req as csr join comp_send_req_det as csrd on id_comp_send_req = comp_send_req where barang = comp.fasilitas),0) + ifnull((select ifnull(sum(qty),0) from purc_receiv_return_detail as prrd join purc_receiv_return as prr on prrd.id_purc_receiv_return = prr.id_purc_receiv_return where prrd.fasilitas = comp.fasilitas and pr = 1),0) + ifnull((select ifnull(sum(qty),0) from adjustment_stock_detail as ajsd join adjustment_stock as ajs on ajsd.id_adjustment_stock = ajs.id_adjustment_stock where ajsd.fasilitas = comp.fasilitas and tipe = '-'),0) + ifnull((select ifnull(sum(diff),0) from stock_opname_detail as sod join stock_opname as so where sod.fasilitas = comp.fasilitas and tipe = '-'),0) + ifnull((select ifnull(sum(qty),0) from adjustment_waste_detail as awd join adjustment_waste as aw on awd.id_adjustment_waste = aw.id_adjustment_waste where awd.fasilitas = comp.fasilitas),0) + ifnull((select ifnull(sum(qty),0) from purchase_return_detail as prd join purchase_return as pr on prd.id_purchase_return = pr.id_purchase_return where prd.fasilitas = comp.fasilitas and pr = 1),0)";
		} else {
			// retur barang sesuai tanggal retur
			$tanggal = $tanggal;
			$where_tanggal = "(tanggal <= '".$tanggal."')";
			$masuk = "ifnull((select ifnull(sum(qty_return),0) from receiveboard_trx_barang as rtc where barang = comp.fasilitas and qty_return > 0 and (tgl_retur < '".$tanggal."')),0) + ifnull((select ifnull(sum(qty),0) from purchase_receiving_detail as prd join purchase_receiving as pr on prd.id_purchase_receiving = pr.id_purchase_receiving where prd.fasilitas = comp.fasilitas and pr = 1 and ".$where_tanggal."),0) + ifnull((select ifnull(sum(qty),0) from adjustment_stock_detail as ajsd join adjustment_stock as ajs on ajsd.id_adjustment_stock = ajs.id_adjustment_stock where ajsd.fasilitas = comp.fasilitas and tipe = '+' and ".$where_tanggal."),0) + ifnull((select ifnull(sum(diff),0) from stock_opname_detail as sod join stock_opname as so where sod.fasilitas = comp.fasilitas and tipe = '+' and ".$where_tanggal."),0)";
			$keluar = "ifnull((select ifnull(sum(qty),0) from comp_send_req as csr join comp_send_req_det as csrd on id_comp_send_req = comp_send_req where barang = comp.fasilitas and ".$where_tanggal."),0) + ifnull((select ifnull(sum(qty),0) from purc_receiv_return_detail as prrd join purc_receiv_return as prr on prrd.id_purc_receiv_return = prr.id_purc_receiv_return where prrd.fasilitas = comp.fasilitas and pr = 1 and ".$where_tanggal."),0) + ifnull((select ifnull(sum(qty),0) from adjustment_stock_detail as ajsd join adjustment_stock as ajs on ajsd.id_adjustment_stock = ajs.id_adjustment_stock where ajsd.fasilitas = comp.fasilitas and tipe = '-' and ".$where_tanggal."),0) + ifnull((select ifnull(sum(diff),0) from stock_opname_detail as sod join stock_opname as so where sod.fasilitas = comp.fasilitas and tipe = '-' and ".$where_tanggal."),0) + ifnull((select ifnull(sum(qty),0) from adjustment_waste_detail as awd join adjustment_waste as aw on awd.id_adjustment_waste = aw.id_adjustment_waste where awd.fasilitas = comp.fasilitas and ".$where_tanggal."),0) + ifnull((select ifnull(sum(qty),0) from purchase_return_detail as prd join purchase_return as pr on prd.id_purchase_return = pr.id_purchase_return where prd.fasilitas = comp.fasilitas and pr = 1 and ".$where_tanggal."),0)";
		}
		$stock = "ifnull((select ifnull(qty,0) from saldo_awal where saldo_awal.fasilitas = comp.fasilitas),0) + (".$masuk.") - (".$keluar.")";
		$select = "ifnull(".$stock.",0) as stock";
		$data = $this->m_crud->get_data('barang as comp', $select, "fasilitas = ".$id);
		return $data['stock'];
	}

	public function stockonhand_barang_2($id, $tanggal = null){
		if($tanggal == null){
			$masuk = "ifnull((select ifnull(sum(qty_return),0) from receiveboard_trx_barang as rtc where barang = comp.fasilitas and qty_return > 0),0) + ifnull((select ifnull(sum(qty),0) from purchase_receiving_detail as prd join purchase_receiving as pr on prd.id_purchase_receiving = pr.id_purchase_receiving where prd.fasilitas = comp.fasilitas and pr = 1),0) + ifnull((select ifnull(sum(qty),0) from adjustment_stock_detail as ajsd join adjustment_stock as ajs on ajsd.id_adjustment_stock = ajs.id_adjustment_stock where ajsd.fasilitas = comp.fasilitas and tipe = '+'),0) + ifnull((select ifnull(sum(diff),0) from stock_opname_detail as sod join stock_opname as so where sod.fasilitas = comp.fasilitas and tipe = '+'),0)";
			$keluar = "ifnull((select ifnull(sum(qty),0) from comp_send_req as csr join comp_send_req_det as csrd on id_comp_send_req = comp_send_req where barang = comp.fasilitas),0) + ifnull((select ifnull(sum(qty),0) from purc_receiv_return_detail as prrd join purc_receiv_return as prr on prrd.id_purc_receiv_return = prr.id_purc_receiv_return where prrd.fasilitas = comp.fasilitas and pr = 1),0) + ifnull((select ifnull(sum(qty),0) from adjustment_stock_detail as ajsd join adjustment_stock as ajs on ajsd.id_adjustment_stock = ajs.id_adjustment_stock where ajsd.fasilitas = comp.fasilitas and tipe = '-'),0) + ifnull((select ifnull(sum(diff),0) from stock_opname_detail as sod join stock_opname as so where sod.fasilitas = comp.fasilitas and tipe = '-'),0) + ifnull((select ifnull(sum(qty),0) from adjustment_waste_detail as awd join adjustment_waste as aw on awd.id_adjustment_waste = aw.id_adjustment_waste where awd.fasilitas = comp.fasilitas),0) + ifnull((select ifnull(sum(qty),0) from purchase_return_detail as prd join purchase_return as pr on prd.id_purchase_return = pr.id_purchase_return where prd.fasilitas = comp.fasilitas and pr = 1),0)";
		} else {
			// retur barang sesuai tanggal beli/jual
			$tanggal = $tanggal;
			$where_tanggal = "(tanggal < '".$tanggal."')";
			$masuk = "ifnull((select (ifnull(sum(qty),0) - ifnull((select ifnull(sum(qty),0) from purc_receiv_return_detail as prrd join purc_receiv_return as prr on prrd.id_purc_receiv_return = prr.id_purc_receiv_return where prrd.fasilitas = comp.fasilitas and pr = 1 and prr.purchase_receiving = pr.id_purchase_receiving),0) - ifnull((select ifnull(prtd.qty,0) from purchase_return_detail as prtd join purchase_return as prt on prtd.id_purchase_return = prt.id_purchase_return join purchase_invoice as pi on prt.id_purchase_invoice = pi.id_purchase_invoice join purchase_invoice_detail as pid on pi.id_purchase_invoice = pid.id_purchase_invoice where prtd.fasilitas = prd.fasilitas and pid.fasilitas = prd.fasilitas and pid.id_receiving = prd.id_purchase_receiving),0)) from purchase_receiving_detail as prd join purchase_receiving as pr on prd.id_purchase_receiving = pr.id_purchase_receiving where prd.fasilitas = comp.fasilitas and pr = 1 and ".$where_tanggal."),0) + ifnull((select ifnull(sum(qty),0) from adjustment_stock_detail as ajsd join adjustment_stock as ajs on ajsd.id_adjustment_stock = ajs.id_adjustment_stock where ajsd.fasilitas = comp.fasilitas and tipe = '+' and ".$where_tanggal."),0) + ifnull((select ifnull(sum(diff),0) from stock_opname_detail as sod join stock_opname as so where sod.fasilitas = comp.fasilitas and tipe = '+' and ".$where_tanggal."),0)";
			$keluar = "ifnull((select (ifnull(sum(qty),0) - ifnull((select ifnull(sum(qty_return),0) from receiveboard_trx_barang as rtc where rtc.barang = comp.fasilitas and qty_return > 0 and rtc.trx_id = csr.board_order and rtc.barang = csrd.barang and (rtc.tgl_done = csr.tanggal)),0)) from comp_send_req as csr join comp_send_req_det as csrd on id_comp_send_req = comp_send_req where barang = comp.fasilitas and ".$where_tanggal."),0) + ifnull((select ifnull(sum(qty),0) from adjustment_stock_detail as ajsd join adjustment_stock as ajs on ajsd.id_adjustment_stock = ajs.id_adjustment_stock where ajsd.fasilitas = comp.fasilitas and tipe = '-' and ".$where_tanggal."),0) + ifnull((select ifnull(sum(diff),0) from stock_opname_detail as sod join stock_opname as so where sod.fasilitas = comp.fasilitas and tipe = '-' and ".$where_tanggal."),0) + ifnull((select ifnull(sum(qty),0) from adjustment_waste_detail as awd join adjustment_waste as aw on awd.id_adjustment_waste = aw.id_adjustment_waste where awd.fasilitas = comp.fasilitas and ".$where_tanggal."),0)";
		}
		$stock = "ifnull((select ifnull(qty,0) from saldo_awal where saldo_awal.fasilitas = comp.fasilitas),0) + (".$masuk.") - (".$keluar.")";
		$select = "ifnull(".$stock.",0) as stock";
		$data = $this->m_crud->get_data('barang as comp', $select, "fasilitas = ".$id);
		return $data['stock'];
	}

	public function double_diskon($total, $diskon) {
		for ($i=0; $i<count($diskon); $i++) {
			$total = $total - ($total * ($diskon[$i] / 100));
		}

		return $total;
	}

	public function replace_kutip($string, $tipe='replace') {
		if ($tipe == 'replace') {
			$string = str_replace("'", "`", $string);
		} else if ($tipe == 'restore') {
			$string = str_replace("`", "'", $string);
		}

		return $string;
	}

	public function generate_kode($param, $id=null) {
		if ($param == 'kelompok') {
			$max_code = $this->m_crud->get_data("kelompok", "RIGHT(MAX(code), 3) max_code", "LEFT(code, 2)='".$id."'")['max_code'];

			return $id.sprintf('%03d', $max_code+1);
		} else if ($param == 'produk') {
			$kode_kelompok = $this->m_crud->get_data("kelompok", "code", "id_kelompok='".$id."'")['code'];
			$max_code = $this->m_crud->get_data("produk", "RIGHT(MAX(code), 3) max_code", "LEFT(code, 5)='".$kode_kelompok."'")['max_code'];

			return $kode_kelompok.sprintf('%03d', $max_code+1);
		} else if ($param == 'adjustment') {
			$max_code = $this->m_crud->get_data("adjustment", "RIGHT(MAX(id_adjustment), 4) max_code", "LEFT(tgl_adjustment, 10)='".$id."'")['max_code'];

			return 'AJ'.date('ymd', strtotime($id)).sprintf('%04d', $max_code+1);
		} else if ($param == 'order') {
			$max_code = $this->m_crud->get_data("orders", "RIGHT(MAX(id_orders), 4) max_code", "SUBSTR(id_orders, 3, 6)='".$id."'")['max_code'];

			return 'TR'.$id.sprintf('%04d', $max_code+1);
		} else if ($param == 'member') {
			$max_code = $this->m_crud->get_data("member", "RIGHT(MAX(ol_code), 5) max_code", "SUBSTR(ol_code, 2, 4)='".$id."'")['max_code'];

			return 'M'.$id.sprintf('%05d', $max_code+1);
		} else if ($param == 'poin') {
			$max_code = $this->m_crud->get_data("poin", "RIGHT(MAX(kode_transaksi), 5) max_code", "SUBSTR(kode_transaksi, 3, 6)='".$id."' AND keterangan like 'Penukaran Poin%'")['max_code'];

			return 'PN'.$id.sprintf('%05d', $max_code+1);
		} else {
			return false;
		}
	}
	function kodeVoucher(){
		$q = $this->db->query("SELECT MAX(RIGHT(kode,4)) AS kd_max FROM voucher WHERE DATE(tgl_mulai)=CURDATE()");
		$kd = "";
		if($q->num_rows()>0){
			foreach($q->result() as $k){
				$tmp = ((int)$k->kd_max)+1;
				$kd = sprintf("%04s", $tmp);
			}
		}else{
			$kd = "0001";
		}
		date_default_timezone_set('Asia/Jakarta');
		return $kd;
	}


	public function navbar_menu() {
		$result_menu = array();
		$read_menu = $this->m_crud->join_data("navbar n", "g.id_groups, g.nama, g.gambar", "groups g", "g.id_groups=n.groups", null, "n.id_navbar");
		foreach ($read_menu as $row) {
			$read_kelompok = $this->m_crud->read_data("kelompok", "id_kelompok, code, nama, gambar", "groups='".$row['id_groups']."' AND status='1'");
			$kelompok = array();
			foreach ($read_kelompok as $row_kelompok) {
				array_push($kelompok, array(
					'id_kelompok' => $row_kelompok['id_kelompok'],
					'code' => $row_kelompok['code'],
					'nama' => $row_kelompok['nama'],
					'gambar' => base_url().$row_kelompok['gambar']
				));
			}
			$array_menu = array(
				'id_groups' => $row['id_groups'],
				'nama' => $row['nama'],
				'gambar' => base_url().$row['gambar'],
				'kelompok' => $kelompok
			);
			array_push($result_menu, $array_menu);
		}

		return $result_menu;
	}

	public function rajaongkir_provinsi() {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://pro.rajaongkir.com/api/province",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"key: d9270415ac1ccd0f5a61cda8d7e1e82d"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return "cURL Error #:" . $err;
		} else {
			return $response;
		}
	}

	public function rajaongkir_kota() {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://pro.rajaongkir.com/api/city",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"key: d9270415ac1ccd0f5a61cda8d7e1e82d"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return "cURL Error #:" . $err;
		} else {
			return $response;
		}
	}

	public function rajaongkir_kecamatan($id) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://pro.rajaongkir.com/api/subdistrict?city=".$id,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"key: d9270415ac1ccd0f5a61cda8d7e1e82d"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return "cURL Error #:" . $err;
		} else {
			return $response;
		}
	}

	public function local_ongkir($harga){
		$result=array();
		$result['rajaongkir']=array(
			"query"=>array(
				"origin"=>"",
				"originType"=>"",
				"destination"=>"",
				"destinationType"=>"",
				"weight"=>0,
				"courier"=>"COD"
			),
			"status"=>array(
				"code"=> 200,
				"description"=> "OK"
			),
			"origin_details"=>array(
				"subdistrict_id"=>"2111",
				"province_id"=>"6",
				"province"=> "DKI Jakarta",
				"city_id"=> "153",
				"city"=>"Jakarta Selatan",
				"type"=>"Kota",
				"subdistrict_name"=>"Setia Budi"
			),
			"destination_details"=>array(
				"subdistrict_id"=>"1469",
				"province_id"=> "9",
				"province"=> "Jawa Barat",
				"city_id"=>"107",
				"city"=>"Cimahi",
				"type"=>"Kota",
				"subdistrict_name"=>"Cimahi Selatan"
			),
			"results"=>array(
				array(
					'code'=>'COD',
					'name'=>'',
					'costs'=>array(
						array(
							"service"=>'COD',
							"description"=>'',
							"cost"=>array(
								array(
									"value"=>$harga,
									"etd"=>"Cash On Delivery",
									"note"=>""
								)
							)
						)
					)
				)
			)
		);
		return json_encode($result);
	}

	public function rajaongkir_cost($data) {
		$post = json_decode($data, true);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://pro.rajaongkir.com/api/cost",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "origin=2111&originType=subdistrict&destination=".$post['tujuan']."&destinationType=subdistrict&weight=".$post['berat']."&courier=".$post['kurir']."",
			CURLOPT_HTTPHEADER => array(
				"content-type: application/x-www-form-urlencoded",
				"key: d9270415ac1ccd0f5a61cda8d7e1e82d"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return "cURL Error #:" . $err;
		} else {
			return $response;
		}
	}

	public function rajaongkir_resi($data) {
		$post = json_decode($data, true);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://pro.rajaongkir.com/api/waybill",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "waybill=".$post['resi']."&courier=".$post['kurir']."",
			CURLOPT_HTTPHEADER => array(
				"content-type: application/x-www-form-urlencoded",
				"key: d9270415ac1ccd0f5a61cda8d7e1e82d"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return "cURL Error #:" . $err;
		} else {
			return $response;
		}
	}

	public function lacak_resi($resi=null) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://www.lacakresi.id/api/lacak/",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 400,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "resi=".$resi."",
			CURLOPT_HTTPHEADER => array(
				"content-type: application/x-www-form-urlencoded"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return "cURL Error #:" . $err;
		} else {
			return $response;
		}
	}

	public function numberToRomanRepresentation($number) {
		$map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
		$returnValue = '';
		while ($number > 0) {
			foreach ($map as $roman => $int) {
				if($number >= $int) {
					$number -= $int;
					$returnValue .= $roman;
					break;
				}
			}
		}
		return $returnValue;
	}

	public function date_romawi ($param=null) {
		if ($param == 'time') {
			return date('ymd') . '/' . $this->m_website->numberToRomanRepresentation(date('H')) . $this->m_website->numberToRomanRepresentation(date('i')) . $this->m_website->numberToRomanRepresentation(date('s'));
		} else {
			return $this->m_website->numberToRomanRepresentation(date('y')) . $this->m_website->numberToRomanRepresentation(date('m')) . $this->m_website->numberToRomanRepresentation(date('d')) . '/' . $this->m_website->numberToRomanRepresentation(date('H')) . $this->m_website->numberToRomanRepresentation(date('i')) . $this->m_website->numberToRomanRepresentation(date('s'));
		}
	}

	public function file_thumb($file){
		$file_ori = explode('.', $file);
		$jml = count($file_ori);
		$file_thumb = null;
		for($i=0; $i<$jml; $i++){
			if($i == ($jml-1)){
				$file_thumb .= '_thumb.'.$file_ori[$i];
			} else {
				$file_thumb .= '.'.$file_ori[$i];
			}
		}
		return substr($file_thumb, 1);
	}

	public function request_api($param="check_server", $data="", $header=null) {
		//$url = "202.138.249.247:1233/bo_npos/api/";
		$url = "https://technopark.smkn14bdg.sch.id/api/";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_URL,$url.$param);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		if ($header != null) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec ($ch);

		curl_close ($ch);

		return $result;
	}

	public function request_api_local($param="check_server", $data="", $method="POST") {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		if ($method == "POST") {
			curl_setopt($ch, CURLOPT_URL,$this->api.$param);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		} else {
			curl_setopt($ch, CURLOPT_URL,$this->api.$param.$data);
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec ($ch);

		curl_close ($ch);

		return $result;
	}

	public function email_invoice($email, $pesan) {
		$decode = json_decode($pesan, true);
		$situs = $this->site_data();

		$to = strip_tags($email);
		$subject = 'Invoice '.$situs->nama.':'.$decode['id_orders'];
		$logo = base_url().$situs->logo;
		$message = '
	    <!doctype html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Invoice</title>
            
            <style>
            .invoice-box {
                max-width: 800px;
                margin: auto;
                padding: 30px;
                border: 1px solid #eee;
                box-shadow: 0 0 10px rgba(0, 0, 0, .15);
                font-size: 16px;
                line-height: 24px;
                font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;
                color: #555;
            }
            
            .invoice-box table {
                width: 100%;
                line-height: inherit;
                text-align: left;
            }
            
            .invoice-box table td {
                padding: 5px;
                vertical-align: top;
            }
            
            .invoice-box table tr td:nth-child(2) {
                text-align: right;
            }
            
            .invoice-box table tr.top table td {
                padding-bottom: 20px;
            }
            
            .invoice-box table tr.top table td.title {
                font-size: 45px;
                line-height: 45px;
                color: #333;
            }
            
            .invoice-box table tr.information table td {
                padding-bottom: 40px;
            }
            
            .invoice-box table tr.heading td {
                background: #eee;
                border-bottom: 1px solid #ddd;
                font-weight: bold;
            }
            
            .invoice-box table tr.details td {
                padding-bottom: 20px;
            }
            
            .invoice-box table tr.item td{
                border-bottom: 1px solid #eee;
            }
            
            .invoice-box table tr.item.last td {
                border-bottom: none;
            }
            
            .invoice-box table tr.total td:nth-child(2) {
                border-top: 2px solid #eee;
                font-weight: bold;
            }
            
            @media only screen and (max-width: 600px) {
                .invoice-box table tr.top table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }
                
                .invoice-box table tr.information table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }
            }
            
            /** RTL **/
            .rtl {
                direction: rtl;
                font-family: Tahoma, \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;
            }
            
            .rtl table {
                text-align: right;
            }
            
            .rtl table tr td:nth-child(2) {
                text-align: left;
            }
            </style>
        </head>
        
        <body>
            <div class="invoice-box">
                <table cellpadding="0" cellspacing="0">
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="title">
                                        <img src="'.$logo.'" style="width:100%; max-width:200px;">
                                    </td>                                    
                                    <td>
                                        Invoice : '.$decode['id_orders'].'<br>
                                        Dipesan: '.date('d F, Y', strtotime($decode['tanggal'])).'<br>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <tr class="information">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td>
                                        '.$situs->nama.'
                                    </td>                                    
                                    <td>
                                        '.$decode['penerima'].'<br>
                                        '.$decode['tlp'].'
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>                    
                    <tr class="heading">
                        <td>Payment Method</td>                        
                        <td></td>
                    </tr>
                    <tr class="details">
                        <td>Transfer '.$decode['bank'].'</td>
                        <td>'.number_format($decode['total']).'</td>
                    </tr>          
                    <tr class="details">
                        <td colspan="2">No Rek '.$decode['rek'].' a/n '.$decode['an'].'</td>
                    </tr>                    
                    <tr class="heading">
                        <td>Item</td>
                        <td>Price</td>
                    </tr>
                    '.$decode['list'].'                    
                    <tr class="item">
                        <th>'.$decode['kurir'].'</th>
                        <td style="text-align: right">'.number_format($decode['ongkir']).'</td>
                    </tr>
                    <tr class="item last">
                        <th>Kode Unik</th>
                        <td style="text-align: right">'.number_format($decode['kode_unik']).'</td>
                    </tr>    
                    <tr class="item last">
                        <th>Voucher</th>
                        <td style="text-align: right">'.number_format(($decode['jumlah_voucher']==''?0:$decode['jumlah_voucher'])).'</td>
                    </tr>     
                    <tr class="item last">
                        <th>Diskon</th>
                        <td style="text-align: right">'.number_format($decode['disc']).'</td>
                    </tr>                    
                    <tr class="total">
                        <th style="text-align: right">Total: </th>                        
                        <th style="text-align: right">'.number_format($decode['total']).'</th>
                    </tr>
                </table>
            </div>
        </body>
        </html>
	    ';

		$headers = "From: ".$situs->nama." <" . strip_tags('no-reply@indokids.co.id') . "> \r\n";
		//$headers .= "CC: agrowisata_n8@yahoo.com \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";


		if (mail($to,$subject,$message,$headers) == true) {
			return true;
		} else {
			return false;
		}
	}

	public function default_img_src(){
		return base_url().'assets/images/no_image.png';
	}

	public function email_forgot_password($data_email) {
		$email = $data_email['email'];
		$id = $data_email['id_member'];
		$token = $data_email['token'];
		$url = base_url().'api/resset_password/'.base64_encode(json_encode(array('id_member'=>$id, 'email'=>$email, 'token'=>$token)));
		$subject = "Confirm Reset Password ".$this->site_data()->nama;

		$message = '
			<p class="hero">Ini adalah email konfirmasi untuk reset password anda</p>
			<p>Silahkan klik tombol dibawah ini untuk reset password anda</p>
			<p>
			  <a href="'.$url.'" class="btn" mc:disable-tracking="">Reset Password Sekarang</a>
			</p>
			<p>Anda akan menerima email untuk password baru anda setelah menekan tombol Reset Password Sekarang</p>
			
			<hr style="margin-top: 56px">
			<p class="mb-0">Terima kasih,</p>
			<p class="mb-0">'.$this->site_data()->nama.'</p>
        ';

		if ($this->email_to($email,$subject,$message) == true) {
			return true;
		} else {
			return false;
		}
	}

	public function email_resset_password($data_email) {
		$email = $data_email['email'];
		$password = $data_email['password'];
		$subject = "Reset Password ".$this->site_data()->nama;

		$message = '
			<p>Password lama anda telah di reset. Lakukan login menggunakan password baru dibawah ini.</p>
			<p>Password : <b>'.$password.'</b></p>
			<p>Jangan beritahukan password ini kepada orang lain.</p>
            <p>Setelah login segera lakukan ubah password melalui profile setting, untuk mempermudah penulisan password.</p>
			
			<hr style="margin-top: 56px">
			<p class="mb-0">Terima kasih,</p>
			<p class="mb-0">'.$this->site_data()->nama.'</p>
        ';

		if ($this->email_to($email,$subject,$message) == true) {
			return true;
		} else {
			return false;
		}
	}

	public function email_new_account($data_email) {
		$email = $data_email['email'];
		$password = $data_email['password'];
		$subject = "Informasi akun member ".$this->site_data()->nama;

		$message = '
			<p>Terimakasih telah telah melakukan registrasi member di '.$this->site_data()->nama.'.</p>
			<p>Anda dapat melakukan login di web atau aplikasi '.$this->site_data()->nama.' menggunakan akun di bawah ini:</p>
			<p>Email : <b>'.$email.'</b></p>
			<p>Password : <b>'.$password.'</b></p>
			<p>Jangan beritahukan password ini kepada orang lain.</p>
			<p>Setelah login segera lakukan ubah password melalui profile setting, untuk mempermudah penulisan password.</p>
			
			
			<hr style="margin-top: 56px">
			<p class="mb-0">Terima kasih,</p>
			<p class="mb-0">'.$this->site_data()->nama.'</p>
        ';

		if ($this->email_to($email,$subject,$message) == true) {
			return true;
		} else {
			return false;
		}
	}

	public function email_to($email, $judul, $pesan) {

		$to = strip_tags($email);
		$subject = $judul;

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
                    background: #f2f5f7 url(https://d2yjfm58htokf8.cloudfront.net/static/images/background-v1.png) no-repeat center top;
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
                                                                          <img alt="Logo" title="" height="auto" src="'.base_url().$this->site_data()->logo.'"
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
                                                                  <div style="font-size:1px;line-height:48px;white-space:nowrap;"> </div>
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
                                                                    '.$pesan.'
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

		$headers = "From: " . strip_tags('no-reply@'.str_replace('www.', '', $this->site_data('2222')->web)) . "\r\n";
		//$headers .= "CC: agrowisata_n8@yahoo.com \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
//        $penerima = ‘mahrizal_nu@yahoo.co.id’;

//$subject = ‘Test’;

//$body  = ‘Just Say Hello’;

        $our_server = "mail.mucglobal.com";

        ini_set("SMTP", $our_server );

//        mail($penerima ,$subject, $body);
		if (mail($to,$subject,$message,$headers) == true) {
			return true;
		} else {
			return false;
		}
	}

	public function myPagination($param,$uri,$table,$field,$join=null,$on=null,$where,$perPage,$page){
		if($param == 'join'){
			$count =$this->m_crud->count_data_join($table, $field, $join, $on, $where);
		}else{
			$count = $this->M_crud->count_data($table, $field, $where);
		}
		$config = array();
		$config["base_url"] 		= "#";
		$config["total_rows"] 		= $count;
		$config["per_page"] 		= $perPage;
		$config["uri_segment"] 		= $uri;
		$config["num_links"] 		= 2;
		$config["use_page_numbers"] = TRUE;
		$config["full_tag_open"] 	= /** @lang text */'<ul class="pagination pagination-sm">';
		$config["full_tag_close"] 	= /** @lang text */'</ul>';
		$config['first_link'] 		= '&laquo;';
		$config["first_tag_open"] 	= /** @lang text */'<li>';
		$config["first_tag_close"] 	= /** @lang text */'</li>';
		$config['last_link'] 		= '&raquo;';
		$config["last_tag_open"] 	= /** @lang text */'<li>';
		$config["last_tag_close"] 	= /** @lang text */'</li>';
		$config['next_link'] 		= '&gt;';
		$config["next_tag_open"] 	= /** @lang text */'<li>';
		$config["next_tag_close"] 	= /** @lang text */'</li>';
		$config["prev_link"] 		= "&lt;";
		$config["prev_tag_open"] 	= /** @lang text */"<li>";
		$config["prev_tag_close"] 	= /** @lang text */"</li>";
		$config["cur_tag_open"] 	= /** @lang text */"<li class='active'><a href='#'>";
		$config["cur_tag_close"] 	= /** @lang text */"</a></li>";
		$config["num_tag_open"] 	= /** @lang text */"<li>";
		$config["num_tag_close"] 	= /** @lang text */"</li>";
		$this->pagination->initialize($config);
		$hal  	 = $uri;
		return $data = array(
			'start' => ($page - 1) * $config["per_page"],
			'perPage' => $config['per_page'],
			'pagination_link' => $this->pagination->create_links()
		);
	}
	public function noData(){
		return /** @lang text */'<div class="container">
			<div class="row">
				<div class="col-xl-12">
					<div class="hero-cap text-center">
						<h2>DATA NOT AVAILABLE</h2>
					</div>
				</div>
			</div>
		</div>';
	}
	public function tempProduk($gambar,$id,$nama,$hrg_jual,$hrg_sebelum,$diskon=null){
	    if(strlen($nama)>20){
	        $nama = substr($nama,0,20).'...';
        }
        else{
	        $nama = $nama;
        }
		$tempDiskon = '';
		if($diskon!=null || $diskon != ''){
			$tempDiskon.='<div class="new-product">
						<span>'.$diskon.'</span>
					</div>';
		}
		return $result = /** @lang text */ '
		<a href="'.base_url().'store/product?product_id='.$id.'">
		<div class="single-product mb-60" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border:1px solid #EEEEEE;border-bottom-left-radius:10px;border-bottom-right-radius:10px">
				<div class="product-img" style="margin-bottom:0px !important">
					<img src="'.base_url().$gambar.'" alt="" style="width:100%;-o-object-fit:contain;object-fit:contain;">
					'.$tempDiskon.'
				</div>
				<div class="product-caption" style="padding:10px">
					<!--<div class="product-ratting">
						<i class="far fa-star"></i>
						<i class="far fa-star"></i>
						<i class="far fa-star"></i>
						<i class="far fa-star low-star"></i>
						<i class="far fa-star low-star"></i>
					</div>-->
					<h4><a href="'.base_url().'store/product?product_id='.$id.'">'.$nama.'</a></h4>
					<div class="price">
						<ul>
							<li style="color:#2577fd!important;">Rp '.number_format($hrg_jual).'</li>
							<li class="discount">'.$hrg_sebelum.'</li>
						</ul>
					</div>
				</div>
			</div>
        </a>
		';
	}

	public function tempNews($gambar,$tgl,$slug,$judul,$ringkasan,$nama){
		return
            /** @lang text */ '<a href="'.base_url().'store/article?detail='.$slug.'">
			<article class="blog_item" style="margin-bottom: 60px;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border:1px solid #EEEEEE;border-bottom-left-radius:10px;border-bottom-right-radius:10px;height:650px;background-color:white!important;">
				<div class="blog_item_img">
					<img class="card-img rounded-0" src="'.base_url().$gambar.'" alt="">
					<a href="'.base_url().'store/article?detail='.$slug.'" class="blog_item_date">
						<h3>'.date('Y',strtotime($tgl)).'</h3>
						<p>'.date('d',strtotime($tgl))." ".date('F',strtotime($tgl)).'</p>
					</a>
				</div>
				<div class="blog_details" style="padding-right:10px;">
					<a href="'.base_url().'store/article?detail='.$slug.'" class="d-inline-block" href="single-blog.html">
						<h2>'.$judul.'</h2>
					</a>
					<p>'.substr($ringkasan,0,100).'</p>
					<ul class="blog-info-link">
						<li><a href="#"><i class="fa fa-tag"></i> '.$nama.'</a></li>
						<li><a href="#"><i class="fa fa-clock"></i> '.date('Y-m-d',strtotime($tgl)).'</a></li>
					</ul>
				</div>
			</article>
		</a>';
	}

}
