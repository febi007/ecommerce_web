<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		
		//$this->session->sess_destroy();

        $site_data = $this->m_website->site_data();
        $this->site = str_replace(' ', '', strtolower($site_data->nama));
        $this->control = 'Site';

        $this->user = $this->session->userdata($this->site . 'user');

        $this->data = array(
            'site' => $site_data,
            'account' => $this->m_website->user_data($this->user),
            'access' => $this->m_website->user_access_data($this->user)
        );
		
		$this->output->set_header("Cache-Control: no-store, no-cache, max-age=0, post-check=0, pre-check=0");
	}

	public function unset_session($session) {
        $this->session->unset_userdata($session);

        echo true;
    }
	
	public function nojs(){
		$data = $this->data;
		$data['title'] = 'Javascript not active';
		$data['redirect'] = base_url();
		$this->load->view('site/nojs');		
	}

	public function delete_ajax_trx() {
	    $table = $_POST['table'];
        $condition = $_POST['condition'];

        for ($i=0; $i<count($table); $i++) {
            $this->m_crud->delete_data($table[$i], $condition[$i]);
        }

        echo true;
    }

    public function get_dropdown($table, $column, $id, $default) {
        $read_data = $this->m_crud->read_data($table, "*", $column." = '".$id."'");
        $list = '<option value="">'.base64_decode($default).'</option>';

        foreach ($read_data as $row) {
            $list .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
        }

        echo $list;
    }

    public function get_list_dropdown($table, $column, $condition, $id, $default) {
        $read_data = $this->m_crud->read_data(base64_decode($table), base64_decode($column), base64_decode($condition)." = '".base64_decode($id)."'");
        $list = '<option value="">'.base64_decode($default).'</option>';

        foreach ($read_data as $row) {
            $list .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
        }

        echo $list;
    }

    public function get_list_join_dropdown($table, $column, $join, $on, $condition, $id, $default) {
        $read_data = $this->m_crud->join_data(base64_decode($table), base64_decode($column), base64_decode($join), base64_decode($on), base64_decode($condition)." = '".base64_decode($id)."'");
        $list = '<option value="">'.base64_decode($default).'</option>';

        foreach ($read_data as $row) {
            $list .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
        }

        echo $list;
    }
	
	public function delete_ajax($table, $column, $id){
		//$id = $_POST['delete_id'];
		$this->m_crud->delete_data($table, $column." = '".$id."'");
		echo json_encode(array('status'=>true));
	}
	
	public function delete_ajax2($table, $where, $cek_query=null){
		//$id = $_POST['delete_id'];
		if($cek_query==null){
			$this->m_crud->delete_data($table, base64_decode($where));
			//echo json_encode(array('status'=>true));
			echo true;
		} else {
			$cek = $this->m_crud->my_query(base64_decode($cek_query));
			if($cek == null){
				$this->m_crud->delete_data($table, base64_decode($where));
				//echo json_encode(array('status'=>true));
				echo true;
			} else {
				//echo json_encode(array('status'=>false));
				echo false;
			}
		}
	}

    public function cek_data($table, $column, $id){
        if($this->m_crud->get_data($table, $column, "ltrim(rtrim(".$column.")) = '".ltrim(rtrim($id))."'")[$column] != null){
            echo true;
        } else {
            echo false;
        }
    }
	
	public function cek_data_2($table, $column, $id){
        $table = base64_decode($table);
        $column = base64_decode($column);
        $id = base64_decode($id);

		if($this->m_crud->get_data($table, $column, "ltrim(rtrim(".$column.")) = '".ltrim(rtrim($id))."'")[$column] != null){
			echo true;
		} else {
			echo false;
		}
	}

	public function count_data($table, $condition) {
	    if($this->m_crud->count_read_data(base64_decode($table), "*", base64_decode($condition)) == 0) {
	        echo 0;
        } else {
            echo 1;
        }

    }
	
	public function get_data($table, $column, $where, $id){
		$data = $this->m_crud->get_data($table, $column, $where." = '".$id."'");
		if($data[$column] != null){
			echo $data[$column];
		} else {
			echo false;
		}
	}
	
	public function search_autocomplete($table, $select, $where){
		$keyword = $this->uri->segment(6); // tangkap variabel keyword dari URL
		$select = str_replace('-', ',', $select);
		$where = str_replace('-', ',', $where);
		$where = explode(',', $where);
		$where = ((isset($where[0])?$where[0]." like '%".$keyword."%'":null).(isset($where[1])?' or '.$where[1]." like '%".$keyword."%'":null).(isset($where[2])?' or '.$where[2]." like '%".$keyword."%'":null));
		$data = $this->m_crud->read_data($table, $select, $where, null, null, 30); // cari di database
		$select = explode(',', $select);
		foreach($data as $row){ // format keluaran di dalam array
			$arr['query'] = $keyword;
			$arr['suggestions'][] = array(
				'value'	=> ((isset($select[0])?$row[$select[0]]:null).(isset($select[1])?'|'.$row[$select[1]]:null).(isset($select[2])?'|'.$row[$select[2]]:null)),
			);
		}
		echo json_encode($arr);
	}
	
	public function max_kode($tmp_jenis,$tmp_tanggal,$tmp_status) {
	    $jenis = base64_decode($tmp_jenis);
	    $replace_tanggal = str_replace('-','',base64_decode($tmp_tanggal));
        $tanggal = substr($replace_tanggal,2);
	    $status = base64_decode($tmp_status);

        $kode_baru = $this->m_website->generate_kode($jenis, $status, $tanggal);

        echo $kode_baru;
    }

    public function max_kode_barang($kode) {
        $kode = base64_decode($kode);
        $length = strlen(ltrim(rtrim($kode)));

        $kode_baru = $this->m_website->generate_kode_barang($kode, $length);

        echo $kode_baru;
    }

    public function max_kode_kelompok($kode) {
	    $kode = base64_decode($kode);
	    $length = strlen(ltrim(rtrim($kode)));

	    $kode_baru = $this->m_website->generate_kode_kelompok($kode, $length);

	    echo $kode_baru;
    }
	
	public function trx_number(){
		$table		= $_GET['table']; 
		$column		= $_GET['column']; 
		$trx		= $_GET['trx']; 
		$tanggal	= $_GET['tanggal']; 
		$digit_seri	= $_GET['digit_seri'];
		$seri = (int) $this->m_crud->get_data($table, "max(substring(".$column.", ".(strlen($trx.$tanggal)+1).", ".$digit_seri.")) as id", $column." like '%".$trx.$tanggal."%'")['id'];
		$seri++; $seri = str_pad($seri, $digit_seri, '0', STR_PAD_LEFT);
		echo $trx.$tanggal.$seri;
	}
	
	public function trx_number_2(){
		$table		= $_GET['table']; 
		$column		= $_GET['column']; 
		$trx		= $_GET['trx']; 
		$coa		= $_GET['coa']; 
		$tanggal	= $_GET['tanggal']; 
		$digit_seri	= $_GET['digit_seri'];
		$seri = (int) $this->m_crud->get_data($table, "max(substring(".$column.", ".(strlen($trx.$coa.$tanggal)+1).", ".$digit_seri.")) as id", $column." like '%".$trx.$coa.$tanggal."%'")['id'];
		$seri++; $seri = str_pad($seri, $digit_seri, '0', STR_PAD_LEFT);
		echo $trx.$coa.$tanggal.$seri;
	}
	
	public function check_data() {
	    $type = $_POST['type_'];
	    $data = $_POST['data_'];

	    if ($type == 'email') {
	        $get_data = $this->m_crud->get_data("user_akun", "COUNT(user_id) rows", "email='".$data."'");
        }

        if ($get_data['rows'] > 0) {
	        echo false;
        } else {
	        echo true;
        }
    }
	
	public function approval_retur_cabang(){
		$trx = $_POST['trx_'];
		$this->db->trans_begin();
		$this->m_crud->create_data('kartu_stock', array(
			'kd_trx' => $trx,
			'tgl' => date('Y-m-d H:i:s'),
			'kd_brg' => $_POST['kd_brg_'],
			'saldo_awal' => 0,
			'stock_in' => $_POST['sisa_approval_'],
			'stock_out' => 0,
			'lokasi' => 'HO',
			'keterangan' => 'Retur Approval '.$_POST['kd_trx_'],
			'hrg_beli' => $_POST['hrg_beli_']
		));
		$this->m_crud->create_data('kartu_stock', array(
			'kd_trx' => $trx,
			'tgl' => date('Y-m-d H:i:s'),
			'kd_brg' => $_POST['kd_brg_'],
			'saldo_awal' => 0,
			'stock_in' => 0,
			'stock_out' => $_POST['sisa_approval_'],
			'lokasi' => 'Retur',
			'keterangan' => 'Retur Approval '.$_POST['kd_trx_'],
			'hrg_beli' => $_POST['hrg_beli_']
		));
		if ($this->db->trans_status() === FALSE){ $this->db->trans_rollback(); 
		} else {
			$this->db->trans_commit(); $this->cart->destroy();
			//echo '<script>alert("Cash Mutation has been saved");window.location = "'.$function.'";</script>';
		}
		//redirect(base_url().$this->control .'/'. $function . '/'. base64_encode($kd_trx));
	}


    public function index($status=1){
        $data = $this->data;
        $function = 'login';
        $view = null;
        $data['title'] = 'Sign In';
        $data['content'] = $view.$function;
        $data['status'] = $status;
        if($this->form_validation->run() == false) {
            $this->load->view('site/login', $data);
        } else {
            $this->load->view('site/login', $data);
        }
    }
	
	public function dashboard($action=null){
		//$this->access_denied(0);
		$data = $this->data;
		$function = 'dashboard';
		$view = null;
		$table = null;
        $data['main'] = 'Dashboard';
        $data['title'] = 'Dashboard';
		$data['page'] = $function;
		$data['content'] = $view.$function;
		$data['table'] = $table;

        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }

		if($action == 'get_data') {
            $label=array();
            $data=array();
            $month = date('m');
            $year = date('Y');

            $last_month = date('Y-m', strtotime('-1 month', strtotime(date('Y-m'))));
            $this_month = date('Y-m');
            $get_orders = $this->m_crud->get_join_data("orders o", "COUNT(DISTINCT o.id_orders) penjualan, IFNULL(SUM(dto.qty * (dto.hrg_jual+dto.hrg_varian-dto.diskon)), 0) total", array("det_orders dto", "det_pembayaran dtp", "pembayaran p"), array("dto.orders=o.id_orders", "o.id_orders=dtp.orders", "dtp.pembayaran=p.id_pembayaran"), "SUBSTRING(o.tgl_orders, 1, 7) = '".$this_month."' AND o.status IN ('1', '2', '3', '4') and p.tgl_verify is not null");
            $last_orders = $this->m_crud->get_join_data("orders o", "COUNT(DISTINCT o.id_orders) penjualan, IFNULL(SUM(dto.qty * (dto.hrg_jual+dto.hrg_varian-dto.diskon)), 0) total", array("det_orders dto", "det_pembayaran dtp", "pembayaran p"), array("dto.orders=o.id_orders", "o.id_orders=dtp.orders", "dtp.pembayaran=p.id_pembayaran"), "SUBSTRING(o.tgl_orders, 1, 7) = '".$last_month."' AND o.status IN ('1', '2', '3', '4') and p.tgl_verify is not null");
            $get_members = $this->m_crud->get_data("member", "COUNT(id_member) member", "SUBSTRING(tgl_register, 1, 7) = '".$this_month."'");
            $last_members = $this->m_crud->get_data("member", "COUNT(id_member) member", "SUBSTRING(tgl_register, 1, 7) = '".$last_month."'");
            $get_omset = $this->m_crud->join_data("orders o", "SUBSTRING(o.tgl_orders, 9, 2) tanggal, IFNULL(SUM(dto.qty * (dto.hrg_jual+dto.hrg_varian-dto.diskon)), 0) total", array("det_orders dto", "det_pembayaran dtp", "pembayaran p"), array("dto.orders=o.id_orders", "o.id_orders=dtp.orders", "dtp.pembayaran=p.id_pembayaran"), "SUBSTRING(o.tgl_orders, 1, 7) = '".$this_month."' AND o.status IN ('1', '2', '3', '4') and p.tgl_verify is not null", "SUBSTRING(o.tgl_orders, 9, 2)", "SUBSTRING(o.tgl_orders, 9, 2)");
			
			for($d=1; $d<=31; $d++) {
                $time=mktime(12, 0, 0, $month, $d, $year);
                if (date('m', $time)==$month)
                    $label[]=date('d', $time);
                    $data[]=0;
            }
            foreach ($get_omset as $row) {
                $data[(int)$row['tanggal']-1] = $row['total'];
            }

            if ($last_orders['total'] == 0) {
                if ($get_orders['total'] == 0) {
                    $p_omt = '<span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>';
                } else {
                    $p_omt = '<span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 100%</span>';
                }
            } else {
                $persentase = (($get_orders['total'] - $last_orders['total']) / $last_orders['total']) * 100;
                if ($persentase == 0) {
                    $p_omt = '<span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>';
                } else if ($persentase > 0) {
                    $p_omt = '<span class="description-percentage text-green"><i class="fa fa-caret-up"></i> '.number_format($persentase, 2, '.', '').'%</span>';
                } else {
                    $p_omt = '<span class="description-percentage text-red"><i class="fa fa-caret-down"></i> '.number_format(abs($persentase), 2, '.', '').'%</span>';
                }
            }

            if ($last_orders['penjualan'] == 0) {
                if ($get_orders['penjualan'] == 0) {
                    $p_ord = '<span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>';
                } else {
                    $p_ord = '<span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 100%</span>';
                }
            } else {
                $persentase = (($get_orders['penjualan'] - $last_orders['penjualan']) / $last_orders['penjualan']) * 100;
                if ($persentase == 0) {
                    $p_ord = '<span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>';
                } else if ($persentase > 0) {
                    $p_ord = '<span class="description-percentage text-green"><i class="fa fa-caret-up"></i> '.number_format($persentase, 2, '.', '').'%</span>';
                } else {
                    $p_ord = '<span class="description-percentage text-red"><i class="fa fa-caret-down"></i> '.number_format(abs($persentase), 2, '.', '').'%</span>';
                }
            }

            if ($get_orders['penjualan']==0) {
                $get_avg = 0;
            } else {
                $get_avg = $get_orders['total'] / $get_orders['penjualan'];
            }

            if ($last_orders['penjualan']==0) {
                $last_avg = 0;
            } else {
                $last_avg = $last_orders['total'] / $last_orders['penjualan'];
            }

            if ($last_avg == 0) {
                if ($get_avg == 0) {
                    $p_avg = '<span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>';
                } else {
                    $p_avg = '<span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 100%</span>';
                }
            } else {
                $persentase = (($get_avg - $last_avg) / $last_avg) * 100;
                if ($persentase == 0) {
                    $p_avg = '<span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>';
                } else if ($persentase > 0) {
                    $p_avg = '<span class="description-percentage text-green"><i class="fa fa-caret-up"></i> '.number_format($persentase, 2, '.', '').'%</span>';
                } else {
                    $p_avg = '<span class="description-percentage text-red"><i class="fa fa-caret-down"></i> '.number_format(abs($persentase), 2, '.', '').'%</span>';
                }
            }

            if ($last_members['member'] == 0) {
                if ($get_members['member'] == 0) {
                    $p_mbr = '<span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>';
                } else {
                    $p_mbr = '<span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 100%</span>';
                }
            } else {
                $persentase = (($get_members['member'] - $last_members['member']) / $last_members['member']) * 100;
                if ($persentase == 0) {
                    $p_mbr = '<span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>';
                } else if ($persentase > 0) {
                    $p_mbr = '<span class="description-percentage text-green"><i class="fa fa-caret-up"></i> '.number_format($persentase, 2, '.', '').'%</span>';
                } else {
                    $p_mbr = '<span class="description-percentage text-red"><i class="fa fa-caret-down"></i> '.number_format(abs($persentase), 2, '.', '').'%</span>';
                }
            }

            echo json_encode(array('label'=>$label, 'data'=>$data, 'head'=>array('omset'=>number_format($get_orders['total']),'orders'=>$get_orders['penjualan'],'avg'=>number_format($get_orders['total']?($get_orders['total']/$get_orders['penjualan']):0),'member'=>$get_members['member']), 'persentase'=>array('omset'=>$p_omt,'orders'=>$p_ord,'avg'=>$p_avg,'member'=>$p_mbr)));
        } else {
            $this->load->view('bo/index', $data);
        }
	}
	
	public function email_verification($hash){
		$hash = base64_decode($hash);
		$this->m_crud->update_data('user_akun', array('verify'=>'1'), "hash = '".$hash."'");
		redirect(base_url());
	}

	public function log_in(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $remember = $this->input->post('remember');

        $cek = $this->m_website->login($username);
        if($cek <> 0){
            if (password_verify($password, $cek->password)) {
                $this->session->set_userdata($this->site . 'isLogin', TRUE);
                $this->session->set_userdata($this->site . 'user', $cek->id_user);
                $this->session->set_userdata($this->site . 'name', $cek->nama);
                $this->session->set_userdata($this->site . 'start', time());
                $this->session->set_userdata($this->site . 'expired', $this->session->userdata($this->site . 'start') + (30 * 60));

                if ($remember == 1) {
                    $data = array('status' => true, 'username' => $username);
                    $cookie = array(
                        'name' => 'idk_store',
                        'value' => json_encode($data, true),
                        'expire' => time() + (86400 * 7),
                    );
                    set_cookie($cookie);
                }

                redirect('site/dashboard');
            } else {
                if ($username != '' && $password != '') {
                    $this->index(0);
                } else {
                    $this->index();
                }
            }
        } else {
            if ($username != '' && $password != '') {
                $this->index(0);
            } else {
                $this->index();
            }
        }
	}
	
	public function logout() {
        delete_cookie('idk_store');
        $this->session->unset_userdata($this->site . 'isLogin');
        $this->session->unset_userdata($this->site . 'user');
        $this->session->unset_userdata($this->site . 'name');
        $this->session->unset_userdata($this->site . 'start');
        $this->session->unset_userdata($this->site . 'expired');
        redirect(base_url());
	}

    public function send_invoice($email = 'lovianamayoreta@gmail.com', $id = 'B00001/XVIIIIXIX/01') {

        $to = strip_tags($email);
        $subject = "Invoice N-POS";

        $billing = $this->m_crud->get_data("bo_npos.dbo.toko tk, bo_npos.dbo.billing b, bo_npos.dbo.det_billing db, bo_npos.dbo.user_akun ua", "tk.nama_toko, b.kd_billing, b.tgl_billing, b.pembayaran, b.status_pembayaran, ua.email, db.tgl_aktif, db.tgl_sebelum", "tk.id_toko=b.toko AND tk.id_toko=ua.toko AND db.billing=b.kd_billing AND ua.user_lvl='1' AND b.kd_billing='".$id."'", null, "tk.nama_toko, b.kd_billing, b.tgl_billing, b.pembayaran, b.status_pembayaran, ua.email, db.tgl_aktif, db.tgl_sebelum");
        $det_billing = $this->m_crud->read_data("bo_npos.dbo.det_billing db, bo_npos.dbo.billing b, bo_npos.dbo.lokasi lk", "db.nominal, db.disc, lk.nama_lokasi, b.pembayaran", "db.billing=b.kd_billing AND db.lokasi=lk.kode AND db.billing='".$id."'");

        $sub_total = 0;
        $disc = 0;
        $list = '';
        foreach ($det_billing as $row) {
            $list .= '
                <tr>
                    <td class="left">'.$row['nama_lokasi'].'</td>
                    <td class="right">Rp '.number_format($row['nominal']*$row['pembayaran']).'</td>
                </tr>
            ';

            $sub_total = $sub_total + ($row['nominal']*$row['pembayaran']);
            $disc = $disc + $row['disc'];
        }

        if ($billing['status_pembayaran']=='0') {
            $status = '<p style="color: orange; font-weight: bold; font-size: 12pt; font-style: italic">Menunggu pembayaran</p>';
        } else if ($billing['status_pembayaran']=='0') {
            $status = '<p style="color: green; font-weight: bold; font-size: 12pt; font-style: italic">Selesai</p>';
        } else {
            $status = '<p style="color: red; font-weight: bold; font-size: 12pt; font-style: italic">Gagal</p>';
        }

        $message = '
        <html>
            <head>
                <style>
                    .width-table {
                        border-left: solid; border-width: thin;
                    }
            
                    .container {
                        text-align: center;
                        padding: 5px;
                        font-family: Arial;
                    }
            
                    .center {
                        text-align: center;
                    }
            
                    .right {
                        text-align: right;
                    }
            
                    .left {
                        text-align: left;
                    }
            
                    .border_bottom {
                        border-bottom: solid;
                    }
            
                    .position_bottom {
                        position: absolute;
                        width: 100%;
                        bottom: 0;
                    }
                </style>
            </head>
            <body>
                <div>
                    <div style="background: #00BCD4; height: 2cm; width: 100%">
                        <div class="container">
                            <table align="center" width="50%">
                                <tbody>
                                <tr>
                                    <td style="width: 1%"><img src="http://npos.co.id/logo_npos_21.png" style="max-height: 1.5cm"></td>
                                    <td class="center"><p style="color: white; font-weight: bold; font-size: 14pt">Invoice</p>'.$status.'</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="container">
                        <table align="center" width="50%">
                            <tbody>
                            <tr>
                                <th class="left">Tagihan untuk</th>
                                <th class="right">Kode Pembayaran</th>
                            </tr>
                            <tr>
                                <td class="left">'.$billing['nama_toko'].'</td>
                                <td class="right">'.$billing['kd_billing'].'</td>
                            </tr>
                            <tr>
                                <td class="left">'.$billing['email'].'</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th></th>
                                <th class="right">Tanggal Pembayaran</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="right">'.substr($billing['tgl_billing'], 0, 10).'</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="border_bottom left"><h2>Detail</h2></td>
                            </tr>
                            <tr>
                                <td class="left" colspan="2">Pembayaran '.$billing['pembayaran'].' bulan</td>
                            </tr>
                            <tr>
                                <td class="left" colspan="2"><i>Periode '.date('Y/m/d', strtotime($billing['tgl_sebelum'])).' - '.date('Y/m/d', strtotime($billing['tgl_aktif'])).'</i></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            '.$list.'            
                            <tr>
                                <td colspan="2" class="border_bottom"></td>
                            </tr>
                            <tr>
                                <td class="left">Subtotal</td>
                                <td class="right">Rp '.number_format($sub_total).'</td>
                            </tr>
                            <tr>
                                <td class="left">Discount</td>
                                <td class="right">Rp '.number_format($disc).'</td>
                            </tr>
                            <tr>
                                <td class="left"><h3>Total</h3></td>
                                <td class="right"><h3>Rp '.number_format($sub_total-$disc).'</h3></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="border_bottom"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
            
                    <div class="position_bottom">
                        <div class="container">
                            <table align="center" width="50%">
                                <tr>
                                    <td class="center">
            
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </body>
            </html>
        ';

        $headers = "From: " . strip_tags('no-reply@npos.com') . "\r\n";
        //$headers .= "CC: agrowisata_n8@yahoo.com \r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        mail($to,$subject,$message,$headers);
    }

    /*B99999/'.$this->numberToRomanRepresentation(17).$this->numberToRomanRepresentation(01).$this->numberToRomanRepresentation(31).'/001*/
    function numberToRomanRepresentation($number) {
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
	
	public function sendVerificatinEmail($email, $verificationText) {
		/*$to = "h45byasidik@gmail.com";
		$subject = "test email";

		$message = "blablabla";

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= 'From: adehasbiasidik@gmail.com' . "\r\n";
		//$headers .= 'Cc: email@example.com' . "\r\n";

		mail($to,$subject,$message,$headers);
		*/
			
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => 465, //587 atau 465
            'smtp_user' => 'adehasbiasidik@gmail.com', // change it to yours
            'smtp_pass' => '1315061993004001', // change it to yours
            'smtp_crypto' => 'ssl',
			'mailtype' => 'text',
            'charset' => 'iso-8859-1',
            'wordwrap' => TRUE
        );

        $this->email->initialize($config);
        $this->email->from('adehasbiasidik@gmail.com', 'Hasbi');
        $this->email->to($email);
        $this->email->subject('Email Test');
        $this->email->message($verificationText);
        $this->email->send();

        echo $this->email->print_debugger();
		
    }
	
	public function set_session($session_name_, $value_) {
        $value = base64_decode($value_);
        $session_name = base64_decode($session_name_);
        $this->session->set_userdata($session_name, $value);
    }

    public function get_session($session_name_) {
        $session_name = base64_decode($session_name_);

        $session = $this->session->$session_name;

        echo $session;
    }

    public function set_session_date($session_name_, $value_) {
        $value = base64_decode($value_);
        $session_name = base64_decode($session_name_);
        $this->session->set_userdata('search', array($session_name=>$value));
    }

    public function get_session_date($type) {
        $field = 'field-date';
        $date = $this->session->search[$field];

        $explode_date = explode(' - ', $date);
        $get_date_1 = explode('/', $explode_date[0]);
        $get_date_2 = explode('/', $explode_date[1]);

        $date1 = $get_date_1[1].'/'.$get_date_1[2].'/'.$get_date_1[0];
        $date2 = $get_date_2[1].'/'.$get_date_2[2].'/'.$get_date_2[0];

        if (isset($date) && $date!=null) {
            if ($type == 'startDate') {
                echo $date1;
            } else {
                echo $date2;
            }
        } else {
            echo date('m/d/Y');
        }
    }

	public function get_dashboard($date_, $lokasi_) {
        $date = base64_decode($date_);
        $lokasi = base64_decode($lokasi_);

        $qlokasi = null;
        ($lokasi != '-')?$qlokasi=" AND mt.Lokasi='".$lokasi."'":$qlokasi=null;

        $explode_date = explode(' - ', $date);

        $date1 = str_replace('/','-',$explode_date[0]);
        $date2 = str_replace('/','-',$explode_date[1]);

        $label = ['0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23'];
        $data = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
        $penjualan = 0;
        $dis_item = 0;

        $count_data_penjualan = $this->m_crud->count_read_data("Master_Trx mt, Det_Trx dt", "mt.kd_trx", "mt.HR='S' AND dt.qty>0 AND mt.kd_trx=dt.kd_trx AND LEFT(CONVERT(VARCHAR, mt.tgl, 120), 10) BETWEEN '".$date1."' AND '".$date2."'".$qlokasi, null, "mt.kd_trx");
        $read_data_grafik = $this->m_crud->read_data("Master_Trx mt, Det_Trx dt", "SUBSTRING(CONVERT(VARCHAR, jam, 120), 12, 2) jam, SUM(dt.qty*dt.hrg_jual) gross_sales, SUM(dt.dis_persen) ddis, SUM(mt.dis_rp) mdis, SUM(mt.kas_lain) kln", "mt.HR='S' AND dt.qty>0 AND mt.kd_trx=dt.kd_trx AND LEFT(CONVERT(VARCHAR, mt.tgl, 120), 10) BETWEEN '".$date1."' AND '".$date2."'".$qlokasi, "SUBSTRING(CONVERT(VARCHAR, mt.jam, 120), 12, 2)", "SUBSTRING(CONVERT(VARCHAR, mt.jam, 120), 12, 2)");
        $get_diskon = $this->m_crud->get_data("Master_Trx mt", "SUM(mt.dis_rp) mdis, SUM(mt.kas_lain) kln", "mt.HR='S' AND LEFT(CONVERT(VARCHAR, mt.tgl, 120), 10) BETWEEN '".$date1."' AND '".$date2."'".$qlokasi);
        foreach ($read_data_grafik as $row) {
            $penjualan = $penjualan + ($row['gross_sales']+0);
            $data[(int)$row['jam']] = ($row['gross_sales']+0);
            $dis_item = $dis_item + $row['ddis'];
        }
        $diskon = $get_diskon['mdis'] + $row['kln'] + $dis_item;
        $transaksi = $count_data_penjualan;

        $label2 = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
        $data2 = [0,0,0,0,0,0,0];
        $read_data_grafik2 = $this->m_crud->read_data("Master_Trx mt, Det_Trx dt", "Datename(weekday, tgl) AS hari, case Datename(weekday, tgl) when 'monday' then 1 when 'tuesday' then 2 when 'wednesday' then 3 when 'thursday' then 4 when 'friday' then 5 when 'saturday' then 6 when 'sunday' then 7 end as hari_ke, SUM(dt.qty*dt.hrg_jual) gross_sales", "mt.HR='S' AND dt.qty>0 AND mt.kd_trx=dt.kd_trx AND LEFT(CONVERT(VARCHAR, mt.tgl, 120), 10) BETWEEN '".$date1."' AND '".$date2."'".$qlokasi, "hari_ke", "Datename(weekday, tgl)");
        foreach ($read_data_grafik2 as $row2) {
            $data2[(int)$row2['hari_ke']-1] = ($row2['gross_sales']+0);
        }

        $label3 = [];
        $data3 = [];
        $read_data_top_item = $this->m_crud->read_data("Master_Trx mt, Det_Trx dt, barang br","ltrim(rtrim(br.nm_brg)) nm_brg, SUM(dt.qty) qty","mt.kd_trx=dt.kd_trx AND dt.qty>0 AND dt.kd_brg=br.kd_brg AND LEFT(CONVERT(VARCHAR, mt.tgl, 120), 10) BETWEEN '".$date1."' AND '".$date2."'".$qlokasi,"SUM(dt.qty) DESC","br.nm_brg",10);
        foreach ($read_data_top_item as $row3) {
            array_push($label3, $row3['nm_brg']);
            array_push($data3, $row3['qty']);
        }

        $label3_2 = [];
        $data3_2 = [];
        $read_data_top_item = $this->m_crud->read_data("Master_Trx mt, Det_Trx dt, barang br","ltrim(rtrim(br.nm_brg)) nm_brg, SUM(dt.qty*dt.hrg_jual) gross_sales","mt.kd_trx=dt.kd_trx AND dt.qty>0 AND dt.kd_brg=br.kd_brg AND LEFT(CONVERT(VARCHAR, mt.tgl, 120), 10) BETWEEN '".$date1."' AND '".$date2."'".$qlokasi,"SUM(dt.qty*dt.hrg_jual) DESC","br.nm_brg",10);
        foreach ($read_data_top_item as $row3_2) {
            array_push($label3_2, $row3_2['nm_brg']);
            array_push($data3_2, ($row3_2['gross_sales']+0));
        }

        $label4 = [];
        $data4 = [];
        $read_data_top_cat = $this->m_crud->read_data("Master_Trx mt, Det_Trx dt, barang br, kel_brg kb","ltrim(rtrim(kb.nm_kel_brg)) nm_kel_brg, SUM(dt.qty) qty","mt.kd_trx=dt.kd_trx AND dt.qty>0 AND dt.kd_brg=br.kd_brg AND br.kel_brg=kb.kel_brg AND LEFT(CONVERT(VARCHAR, mt.tgl, 120), 10) BETWEEN '".$date1."' AND '".$date2."'".$qlokasi,"SUM(dt.qty) DESC","kb.nm_kel_brg",10);
        foreach ($read_data_top_cat as $row4) {
            array_push($label4, $row4['nm_kel_brg']);
            array_push($data4, $row4['qty']);
        }

        $label4_2 = [];
        $data4_2 = [];
        $read_data_top_cat2 = $this->m_crud->read_data("Master_Trx mt, Det_Trx dt, barang br, kel_brg kb","ltrim(rtrim(kb.nm_kel_brg)) nm_kel_brg, SUM(dt.qty*dt.hrg_jual) gross_sales","mt.kd_trx=dt.kd_trx AND dt.qty>0 AND dt.kd_brg=br.kd_brg AND br.kel_brg=kb.kel_brg AND LEFT(CONVERT(VARCHAR, mt.tgl, 120), 10) BETWEEN '".$date1."' AND '".$date2."'".$qlokasi,"SUM(dt.qty*dt.hrg_jual) DESC","kb.nm_kel_brg",10);
        foreach ($read_data_top_cat2 as $row4_2) {
            array_push($label4_2, $row4_2['nm_kel_brg']);
            array_push($data4_2, ($row4_2['gross_sales']+0));
        }

        $label5 = [];
        $data5 = [];
        $read_data_top_supp = $this->m_crud->read_data("Master_Trx mt, Det_Trx dt, barang br, Group1 gr1","ltrim(rtrim(gr1.Nama)) nm_supplier, SUM(dt.qty) qty","mt.kd_trx=dt.kd_trx AND dt.qty>0 AND dt.kd_brg=br.kd_brg AND br.Group1=gr1.Kode AND LEFT(CONVERT(VARCHAR, mt.tgl, 120), 10) BETWEEN '".$date1."' AND '".$date2."'".$qlokasi,"SUM(dt.qty) DESC","gr1.Nama",10);
        foreach ($read_data_top_supp as $row5) {
            array_push($label5, $row5['nm_supplier']);
            array_push($data5, $row5['qty']);
        }

        $label5_2 = [];
        $data5_2 = [];
        $read_data_top_supp2 = $this->m_crud->read_data("Master_Trx mt, Det_Trx dt, barang br, Group1 gr1","ltrim(rtrim(gr1.Nama)) nm_supplier, SUM(dt.qty*dt.hrg_jual) gross_sales","mt.kd_trx=dt.kd_trx AND dt.qty>0 AND dt.kd_brg=br.kd_brg AND br.Group1=gr1.Kode AND LEFT(CONVERT(VARCHAR, mt.tgl, 120), 10) BETWEEN '".$date1."' AND '".$date2."'".$qlokasi,"SUM(dt.qty*dt.hrg_jual) DESC","gr1.Nama",10);
        foreach ($read_data_top_supp2 as $row5_2) {
            array_push($label5_2, $row5_2['nm_supplier']);
            array_push($data5_2, ($row5_2['gross_sales']+0));
        }


        echo json_encode(array("gross_sales" => array("label" => $label, "data" => $data), "gross_sales2" => array("label" => $label2, "data" => $data2), "head" => array("penjualan" => number_format($penjualan), "transaksi" => $transaksi, "net" => number_format($penjualan-$diskon), "avg" => number_format($penjualan/$transaksi)), "top_item" => array("label" => $label3, "data" => $data3, "label2" => $label3_2, "data2" => $data3_2), "top_cat" => array("label" => $label4, "label2" => $label4_2, "data" => $data4, "data2" => $data4_2), "top_supp" => array("label" => $label5, "label2" => $label5_2, "data" => $data5, "data2" => $data5_2)));
    }

    public function valid_otorisasi($password) {
	    $password = base64_decode($password);

	    $valid = $this->m_crud->count_read_data("user_akun", "user_id", "password_otorisasi = '".$password."'");

        echo $valid;
    }
	
}

