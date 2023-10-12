<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory extends CI_Controller
{

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        //$this->session->sess_destroy();

        $site_data = $this->m_website->site_data();
        $this->site = str_replace(' ', '', strtolower($site_data->nama));
        $this->control = 'Inventory';

        $this->user = $this->session->userdata($this->site . 'user');

        $this->data = array(
            'site' => $site_data,
            'account' => $this->m_website->user_data($this->user),
            'access' => $this->m_website->user_access_data($this->user)
        );

        $this->output->set_header("Cache-Control: no-store, no-cache, max-age=0, post-check=0, pre-check=0");
    }

    public function index(){
        redirect(base_url());
    }

    function access_denied($str){
        if(substr($this->m_website->user_access_data($this->user)->level, $str,1) == 0) {
            echo "<script>alert('Access Denied'); window.location='" . base_url() . "site';</script>";
        }
    }

    /*Start data stok*/
    public function data_stok($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'data_stok';
        $table = 'kartu_stok';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Inventory';
        $data['title'] = 'Data Stok';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = null;

        if (!isset($this->session->search['page'])) {
            $this->session->set_userdata('search', array('page' => 1));
        }

        if(isset($_POST['search'])||isset($_POST['to_excel'])) {
            $this->session->set_userdata('search', array('any' => $_POST['any'], 'date' => $_POST['date'], 'page' => $page));
        }

        if ($page > 1) {
            $this->session->set_userdata('search', array('any' => $this->session->search['any'], 'date' => $_POST['date'], 'page' => $page));
        }

        $search = $this->session->search['any']; $date = $this->session->search['date']; $page = $this->session->search['page'];
        if(!isset($page)&&$page==null) {
            $page = 1;
        }

        if(isset($search)&&$search!=null) {
            ($where == null) ? null : $where .= " AND ";
            $where .= "(nama like '%".$search."%' OR code like '%".$search."%')";
        } else {
            $search = '';
        }

        if(isset($date)&&$date!=null) {
            $explode_date = explode(' - ', $date);
            $tgl_awal = $explode_date[0]; $tgl_akhir = $explode_date[1];
        }
        else {
            $tgl_awal = date('Y-m-d'); $tgl_akhir = date('Y-m-d');
        }

        if ($action == 'get_data') {
            $config = array();
            $config["base_url"] = "#";
            //$config["total_rows"] = $this->ajax_pagination_model->count_all();
            $config["total_rows"] = $this->m_crud->count_data("produk", "id_produk", $where, null);
            $config["per_page"] = 6;
            $config["uri_segment"] = 4;
            $config["num_links"] = 5;
            $config["use_page_numbers"] = TRUE;
            $config["full_tag_open"] = '<ul class="pagination pagination-sm">';
            $config["full_tag_close"] = '</ul>';
            $config['first_link'] = '&laquo;';
            $config["first_tag_open"] = '<li>';
            $config["first_tag_close"] = '</li>';
            $config['last_link'] = '&raquo;';
            $config["last_tag_open"] = '<li>';
            $config["last_tag_close"] = '</li>';
            $config['next_link'] = '&gt;';
            $config["next_tag_open"] = '<li>';
            $config["next_tag_close"] = '</li>';
            $config["prev_link"] = "&lt;";
            $config["prev_tag_open"] = "<li>";
            $config["prev_tag_close"] = "</li>";
            $config["cur_tag_open"] = "<li class='active'><a href='#'>";
            $config["cur_tag_close"] = "</a></li>";
            $config["num_tag_open"] = "<li>";
            $config["num_tag_close"] = "</li>";
            $this->pagination->initialize($config);
            $start = ($page - 1) * $config["per_page"];

            $output = '';
            $stok_awal = " ,(SELECT ifnull(SUM(stok_in-stok_out), 0) FROM kartu_stok WHERE produk=id_produk AND LEFT(tgl_trx, 10)<'".$tgl_awal."') stok_awal";
            $stok_masuk = " ,(SELECT ifnull(SUM(stok_in), 0) FROM kartu_stok WHERE produk=id_produk AND LEFT(tgl_trx, 10) BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."') stok_masuk";
            $stok_keluar = " ,(SELECT ifnull(SUM(stok_out), 0) FROM kartu_stok WHERE produk=id_produk AND LEFT(tgl_trx, 10) BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."') stok_keluar";
            $read_data = $this->m_crud->read_data("produk", "id_produk, code, nama".$stok_awal.$stok_masuk.$stok_keluar, $where, "nama", null, $config["per_page"], $start);
            $output .= '
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th width="1%" class="text-center">#</th>
                    <th>SKU</th>
                    <th>Nama</th>
                    <th>Stok Awal</th>
                    <th>Stok Masuk</th>
                    <th>Stok Keluar</th>
                    <th>Stok Akhir</th>
                </tr>
            ';
            $no = $start+1;
            if ($read_data != null) {
                $stok_awal_page = 0; $stok_masuk_page = 0; $stok_keluar_page = 0;
                $get_stok_total = $this->m_crud->get_join_data("kartu_stok ks", "ifnull(SUM(stok_in), 0) stok_masuk, ifnull(SUM(stok_out), 0) stok_keluar", "produk", "produk.id_produk=ks.produk", ($where==null?null:$where.' AND ')."tgl_trx BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."'");
                $stok_awal_total = $this->m_crud->get_join_data("kartu_stok ks", "ifnull(SUM(stok_in-stok_out), 0) stok_awal", "produk", "produk.id_produk=ks.produk", ($where==null?null:$where.' AND ')."tgl_trx<'".$tgl_awal."'")['stok_awal'];
                $stok_masuk_total = $get_stok_total['stok_masuk'];
                $stok_keluar_total = $get_stok_total['stok_keluar'];
                foreach ($read_data as $row) {
                    $output .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Pilihan <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu dropdown-position">
                                <li><a href="#" onclick="detail(\'' . $row['id_produk'] . '\')">Detail</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['code'] . '</td>
                        <td>' . $row['nama'] . '</td>
                        <td>' . $row['stok_awal'] . '</td>
                        <td>' . $row['stok_masuk'] . '</td>
                        <td>' . $row['stok_keluar'] . '</td>
                        <td>' . ($row['stok_awal']+$row['stok_masuk']-$row['stok_keluar']) . '</td>
                    </tr>
                    ';
                    $stok_awal_page = $stok_awal_page + $row['stok_awal']; $stok_masuk_page = $stok_masuk_page + $row['stok_masuk']; $stok_keluar_page = $stok_keluar_page + $row['stok_keluar'];
                }
                $output .= '
                <tr>
                    <th colspan="4">Jumlah</th>
                    <th>'.$stok_awal_page.'</th>
                    <th>'.$stok_masuk_page.'</th>
                    <th>'.$stok_keluar_page.'</th>
                    <th>'.($stok_awal_page+$stok_masuk_page-$stok_keluar_page).'</th>
                </tr>
                <tr>
                    <th colspan="4">Total</th>
                    <th>'.$stok_awal_total.'</th>
                    <th>'.$stok_masuk_total.'</th>
                    <th>'.$stok_keluar_total.'</th>
                    <th>'.($stok_awal_total+$stok_masuk_total-$stok_keluar_total).'</th>
                </tr>
                ';
            } else {
                $output .= '
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data</td>
                </tr>
                ';
            }
            $output .= '</table>';

            $result = array(
                'pagination_link' => $this->pagination->create_links(),
                'result_table' => $output
            );
            echo json_encode($result);
        }
        else if ($action == 'get_data_api') {
            $get_produk = $this->m_crud->read_data("produk", "code, nama", $where);
            $in_produk = array();
            foreach ($get_produk as $item) {
                array_push($in_produk, "'".$item['code']."'");
            }

            $data_post = array(
                'in_brg' => json_encode($in_produk),
                'tgl_awal' => $tgl_awal,
                'tgl_akhir' => $tgl_akhir,
                'per_page' => 6,
                'page' => $page
            );

            $req_api = json_decode($this->m_website->request_api_interlocal("kartu_stock", $data_post), true);

            $total_rows = $req_api['total_rows'];
            $res_api = $req_api['result'];
            $report = $res_api['report'];

			//echo json_encode($report);
//			var_dump($req_api);die();
			if(!is_null($report)){
				foreach ($report as $key => $item) {
					foreach ($get_produk as $item2) {
						if ($item['kd_brg'] == $item2['code']) {
							$report[$key]['nama_produk'] = $item2['nama'];
							break;
						}
					}
				}
			}

            $config = array();
            $config["base_url"] = "#";
            $config["total_rows"] = $total_rows;
            $config["per_page"] = 6;
            $config["uri_segment"] = 4;
            $config["num_links"] = 5;
            $config["use_page_numbers"] = TRUE;
            $config["full_tag_open"] = '<ul class="pagination pagination-sm">';
            $config["full_tag_close"] = '</ul>';
            $config['first_link'] = '&laquo;';
            $config["first_tag_open"] = '<li>';
            $config["first_tag_close"] = '</li>';
            $config['last_link'] = '&raquo;';
            $config["last_tag_open"] = '<li>';
            $config["last_tag_close"] = '</li>';
            $config['next_link'] = '&gt;';
            $config["next_tag_open"] = '<li>';
            $config["next_tag_close"] = '</li>';
            $config["prev_link"] = "&lt;";
            $config["prev_tag_open"] = "<li>";
            $config["prev_tag_close"] = "</li>";
            $config["cur_tag_open"] = "<li class='active'><a href='#'>";
            $config["cur_tag_close"] = "</a></li>";
            $config["num_tag_open"] = "<li>";
            $config["num_tag_close"] = "</li>";
            $this->pagination->initialize($config);
            $start = ($page - 1) * $config["per_page"];

            $output = '';
            $output .= '
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th width="1%" class="text-center">#</th>
                    <th>SKU</th>
                    <th>Nama</th>
                    <th>Stok Awal</th>
                    <th>Stok Masuk</th>
                    <th>Stok Keluar</th>
                    <th>Stok Akhir</th>
                </tr>
            ';
            $no = $start+1;
            if (count(is_null($report)?0:$report) > 0) {
                $stok_awal_page = 0; $stok_masuk_page = 0; $stok_keluar_page = 0;
                $stok_awal_total = (int)$res_api['tstaw'];
                $stok_masuk_total = (int)$res_api['tstma'];
                $stok_keluar_total = (int)$res_api['tstke'];
                foreach ($report as $row) {
                    $output .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Pilihan <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu dropdown-position">
                                <li><a href="#" onclick="detail(\'' . $row['kd_brg'] . '\')">Detail</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['kd_brg'] . '</td>
                        <td>' . $row['nama_produk'] . '</td>
                        <td>' . (int)$row['stock_awal'] . '</td>
                        <td>' . (int)$row['stock_masuk'] . '</td>
                        <td>' . (int)$row['stock_keluar'] . '</td>
                        <td>' . ((int)$row['stock_awal']+(int)$row['stock_masuk']-(int)$row['stock_keluar']) . '</td>
                    </tr>
                    ';
                    $stok_awal_page = $stok_awal_page + (int)$row['stock_awal']; $stok_masuk_page = $stok_masuk_page + (int)$row['stock_masuk']; $stok_keluar_page = $stok_keluar_page + (int)$row['stock_keluar'];
                }
                $output .= '
                <tr>
                    <th colspan="4">Jumlah</th>
                    <th>'.$stok_awal_page.'</th>
                    <th>'.$stok_masuk_page.'</th>
                    <th>'.$stok_keluar_page.'</th>
                    <th>'.($stok_awal_page+$stok_masuk_page-$stok_keluar_page).'</th>
                </tr>
                <tr>
                    <th colspan="4">Total</th>
                    <th>'.$stok_awal_total.'</th>
                    <th>'.$stok_masuk_total.'</th>
                    <th>'.$stok_keluar_total.'</th>
                    <th>'.($stok_awal_total+$stok_masuk_total-$stok_keluar_total).'</th>
                </tr>
                ';
            } else {
                $output .= '
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data</td>
                </tr>
                ';
            }
            $output .= '</table>';

            $result = array(
                'pagination_link' => $this->pagination->create_links(),
                'result_table' => $output
            );
            echo json_encode($result);
        }
        else if ($action == 'detail') {
            $result = array();
            $id = $_POST['id'];
            $explode_date = explode(' - ', $_POST['date']);
            $list_produk = '';
            $get_data = $this->m_crud->get_data("produk", "code, nama", "id_produk='".$id."'");
            $read_data = $this->m_crud->read_data("kartu_stok", "kd_trx, tgl_trx, trx jenis, stok_in stok_masuk, stok_out stok_keluar", "produk='".$id."' AND LEFT(tgl_trx, 10) BETWEEN '".$explode_date[0]."' AND '".$explode_date[1]."'", "tgl_trx DESC");
            if ($read_data != null) {
                foreach ($read_data as $row) {
                    $list_produk .= '
                    <tr>
                        <td>' . $row['kd_trx'] . '</td>
                        <td>' . $row['tgl_trx'] . '</td>
                        <td>' . $row['jenis'] . '</td>
                        <td>' . $row['stok_masuk'] . '</td>
                        <td>' . $row['stok_keluar'] . '</td>
                    </tr>
                    ';
                }
            } else {
                $list_produk = '<tr><td colspan="5" class="text-center">Tidak ada transaksi!</td></tr>';
            }

            if ($get_data != null) {
                $result['status'] = true;
                $result['produk'] = $get_data;
                $result['date'] = $_POST['date'];
                $result['list'] = $list_produk;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        }
        else if ($action == 'detail_api') {
            $result = array();
            $id = $_POST['id'];
            $explode_date = explode(' - ', $_POST['date']);
            $list_produk = '';
            $get_data = $this->m_crud->get_data("produk", "code, nama", "code='".$id."'");

            $data_post = array(
                'kd_brg' => $id,
                'tgl_awal' => $tgl_awal,
                'tgl_akhir' => $tgl_akhir
            );

            $req_api = json_decode($this->m_website->request_api_interlocal("detail_by_transaksi", $data_post), true);
            $read_data = $req_api['list'];
            if (count($read_data) > 0) {
                foreach ($read_data as $row) {
                    $list_produk .= '
                    <tr>
                        <td>' . $row['kd_trx'] . '</td>
                        <td>' . date('Y-m-d H:i:s', strtotime($row['tgl'])) . '</td>
                        <td>' . $row['keterangan'] . '</td>
                        <td>' . (int)$row['stock_in'] . '</td>
                        <td>' . (int)$row['stock_out'] . '</td>
                    </tr>
                    ';
                }
            } else {
                $list_produk = '<tr><td colspan="5" class="text-center">Tidak ada transaksi!</td></tr>';
            }

            if ($get_data != null) {
                $result['status'] = true;
                $result['produk'] = $get_data;
                $result['date'] = $_POST['date'];
                $result['list'] = $list_produk;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else {
            $this->load->view('bo/index', $data);
        }
    }
    /*End data stok*/

    /*Start adjustment*/
    public function adjustment($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'adjustment';
        $table = 'adjustment';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Inventory';
        $data['title'] = 'Adjustment';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = null;

        if(isset($_POST['search'])||isset($_POST['to_excel'])) {
            $this->session->set_userdata('search', array('any' => $_POST['any'], 'date' => $_POST['date']));
        }

        $search = $this->session->search['any']; $date = $this->session->search['date'];
        if(isset($search)&&$search!=null) {
            ($where == null) ? null : $where .= " AND ";
            $where .= "ta.id_adjustment like '%".$search."%'";
        }

        if(isset($date)&&$date!=null) {
            $explode_date = explode(' - ', $date);
            $tgl_awal = $explode_date[0]; $tgl_akhir = $explode_date[1];
        } else {
            $tgl_awal = date('Y-m-d'); $tgl_akhir = date('Y-m-d');
        }
        ($where == null) ? null : $where .= " AND ";
        $where .= "LEFT(ta.tgl_adjustment, 10) BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."'";

        if ($action == 'get_data') {
            $config = array();
            $config["base_url"] = "#";
            //$config["total_rows"] = $this->ajax_pagination_model->count_all();
            $config["total_rows"] = $this->m_crud->count_data_join($table." ta", "ta.id_adjustment", "user_detail ud", "ud.id_user=ta.user_detail", $where);
            $config["per_page"] = 6;
            $config["uri_segment"] = 4;
            $config["num_links"] = 5;
            $config["use_page_numbers"] = TRUE;
            $config["full_tag_open"] = '<ul class="pagination pagination-sm">';
            $config["full_tag_close"] = '</ul>';
            $config['first_link'] = '&laquo;';
            $config["first_tag_open"] = '<li>';
            $config["first_tag_close"] = '</li>';
            $config['last_link'] = '&raquo;';
            $config["last_tag_open"] = '<li>';
            $config["last_tag_close"] = '</li>';
            $config['next_link'] = '&gt;';
            $config["next_tag_open"] = '<li>';
            $config["next_tag_close"] = '</li>';
            $config["prev_link"] = "&lt;";
            $config["prev_tag_open"] = "<li>";
            $config["prev_tag_close"] = "</li>";
            $config["cur_tag_open"] = "<li class='active'><a href='#'>";
            $config["cur_tag_close"] = "</a></li>";
            $config["num_tag_open"] = "<li>";
            $config["num_tag_close"] = "</li>";
            $this->pagination->initialize($config);
            $start = ($page - 1) * $config["per_page"];

            $output = '';
            $read_data = $this->m_crud->join_data($table." ta", "ta.id_adjustment, ta.tgl_adjustment, ta.keterangan, ud.nama operator", "user_detail ud", "ud.id_user=ta.user_detail", $where, "ta.id_adjustment DESC", null, $config["per_page"], $start);
            $output .= '
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th width="1%" class="text-center">#</th>
                    <th>Kode Transaksi</th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Operator</th>
                </tr>
            ';
            $no = $start+1;
            if ($read_data != null) {
                foreach ($read_data as $row) {
                    $output .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Pilihan <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu dropdown-position">
                                <li><a href="#" onclick="detail(\'' . $row['id_adjustment'] . '\')">Detail</a></li>
                                <li><a href="#" onclick="edit(\'' . $row['id_adjustment'] . '\')">Edit</a></li>
                                <li><a href="#" onclick="hapus(\'' . $row['id_adjustment'] . '\')">Hapus</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['id_adjustment'] . '</td>
                        <td>' . $row['tgl_adjustment'] . '</td>
                        <td>' . $row['keterangan'] . '</td>
                        <td>' . $row['operator'] . '</td>
                    </tr>
                    ';
                }
            } else {
                $output .= '
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data</td>
                </tr>
                ';
            }
            $output .= '</table>';

            $result = array(
                'pagination_link' => $this->pagination->create_links(),
                'result_table' => $output
            );
            echo json_encode($result);
        }
        else if ($action == 'get_produk') {
            $get_produk = $this->m_crud->join_data("produk p", "CONCAT(p.code, ' | ', p.nama, ' | ', ifnull(dp.ukuran, '-'), ' | ', ifnull(dp.warna, '-')) value,p.stok as stok_produk,p.id_produk, dp.id_det_produk, ifnull(dp.ukuran, '-') ukuran, ifnull(dp.warna, '-') warna, p.nama, p.code, ifnull(SUM(ks.stok_in-ks.stok_out), 0) stok, 0 qty, 'tambah' jenis", array("det_produk dp", array("table"=>"kartu_stok ks","type"=>"LEFT")), array("dp.produk=p.id_produk", "ks.det_produk=dp.id_det_produk"), "p.nama like '%".$_POST['query']."%' OR p.code like '%".$_POST['query']."%'", null, "dp.id_det_produk");

            if ($get_produk != null) {
                $result = $get_produk;
            } else {
                $result = array(array('id_produk'=>'not_found', 'value'=>'Produk Tidak Tersedia!'));
            }

            echo json_encode(array("suggestions"=>$result));
        }
        else if ($action == 'detail') {
            $get_data = $this->m_crud->get_data($table, "*", "id_adjustment = '".$_POST['id']."'");
            $result = array();
            $list_adjustment = '';
            $read_data = $this->m_crud->join_data("det_adjustment da", "p.id_produk, p.nama, p.code, ifnull(dpr.ukuran, '-') ukuran, ifnull(dpr.warna, '-') warna, da.jenis, da.qty", array("det_produk dpr", "produk p"), array("dpr.id_det_produk=da.det_produk", "p.id_produk=dpr.produk"), "da.adjustment='".$_POST['id']."'", null, "da.det_produk");
            foreach ($read_data as $row) {
                $list_adjustment .= '
                <tr>
                <td>'.$row['code'].'</td>
                <td>'.$row['nama'].'</td>
                <td>'.$row['ukuran'].'</td>
                <td>'.$row['warna'].'</td>
                <td>'.ucfirst($row['jenis']).'</td>
                <td>'.$row['qty'].'</td>
                </tr>
                ';
            }

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_adjustment'] = $get_data;
                $result['det_adjustment'] = $list_adjustment;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        }
        else if ($action == 'simpan') {
            $this->db->trans_begin();
            if ($_POST['param'] == 'add') {
                $code = $this->m_website->generate_kode('adjustment', $_POST['tanggal']);
                $data_adjustment = array(
                    'id_adjustment' => $code,
                    'tgl_adjustment' => $_POST['tanggal'].' '.date('H:i:s'),
                    'keterangan' => $_POST['keterangan'],
                    'user_detail' => $this->user
                );

                $this->m_crud->create_data($table, $data_adjustment);
                foreach ($_POST['id_det_produk'] as $row => $value) {
                    if($_POST['jenis_'.$row] == "tambah"){
                        $stokProduk = (int)$_POST['stok_produk_'.$row]+$_POST['qty_'.$row];
                    }else{
                        $stokProduk = (int)$_POST['stok_produk_'.$row]-$_POST['qty_'.$row];

                    }
                    $det_adjustment = array(
                        'adjustment' => $code,
                        'det_produk' => $value,
                        'jenis' => $_POST['jenis_'.$row],
                        'qty' => $_POST['qty_'.$row]
                    );


                    $this->m_crud->update_data("produk", array("stok"=>$stokProduk), "id_produk='".$_POST['id_produk_'.$row]."'");
                    $this->m_crud->create_data("det_adjustment", $det_adjustment);
                }

            }
            else {
                $id = $_POST['id'];
                $data_adjustment = array(
                    'tgl_adjustment' => $_POST['tanggal'].' '.date('H:i:s'),
                    'keterangan' => $_POST['keterangan'],
                    'user_detail' => $this->user
                );

                $check_data = $this->m_crud->get_data("adjustment", "id_adjustment", "id_adjustment='".$id."' AND LEFT(tgl_adjustment, 10)='".$_POST['tanggal']."'");
                if ($check_data != null) {
                    $this->m_crud->delete_data("det_adjustment", "adjustment = '" . $id . "'");
                    $this->m_crud->update_data($table, $data_adjustment, "id_adjustment='".$id."'");
                } else {
                    $this->m_crud->delete_data($table, "id_adjustment = '" . $id . "'");
                    $code = $this->m_website->generate_kode('adjustment', $_POST['tanggal']);
                    $data_adjustment['id_adjustment'] = $code;
                    $this->m_crud->create_data($table, $data_adjustment);
                    $id = $code;
                }

                foreach ($_POST['id_det_produk'] as $row => $value) {
                    if($_POST['jenis_'.$row] == "tambah"){
                        $stokProduk = (int)$_POST['stok_produk_'.$row]+$_POST['qty_'.$row];
                    }else{
                        $stokProduk = (int)$_POST['stok_produk_'.$row]-$_POST['qty_'.$row];

                    }
                    echo json_encode(array("stok"=>$stokProduk));
                    $det_adjustment = array(
                        'adjustment' => $id,
                        'det_produk' => $value,
                        'jenis' => $_POST['jenis_'.$row],
                        'qty' => $_POST['qty_'.$row]
                    );
                    $this->m_crud->update_data("produk", array("stok"=>$stokProduk), "id_produk='".$_POST['id_produk_'.$row]."'");
                    $this->m_crud->create_data("det_adjustment", $det_adjustment);
                }
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo true;
            }
        } else if ($action == 'edit') {
            $result = array();
            $get_data = $this->m_crud->get_data($table, "*", "id_adjustment = '".$_POST['id']."'");
            $read_data = $this->m_crud->read_data("det_adjustment", "*", "adjustment='".$_POST['id']."'");
            $det_produk = array();
            foreach ($read_data as $row) {
                $get_produk = $this->m_crud->get_join_data("produk p", "p.id_produk,p.stok as stok_produk,dp.id_det_produk, ifnull(dp.ukuran, '-') ukuran, ifnull(dp.warna, '-') warna, p.nama, p.code, ifnull(SUM(ks.stok_in-ks.stok_out), 0) stok", array("det_produk dp", array("table"=>"kartu_stok ks","type"=>"LEFT")), array("dp.produk=p.id_produk", "ks.det_produk=dp.id_det_produk"), "dp.id_det_produk='".$row['det_produk']."'", null, "dp.id_det_produk");
                if ($row['jenis']=='tambah') {
                    $stok = (int)$get_produk['stok']-(int)$row['qty'];
                } else {
                    $stok = (int)$get_produk['stok']+(int)$row['qty'];
                }
                $list = array(
                    'id_det_produk' => $get_produk['id_det_produk'],
                    'code' => $get_produk['code'],
                    'nama' => $get_produk['nama'],
                    'ukuran' => $get_produk['ukuran'],
                    'warna' => $get_produk['warna'],
                    'id_produk' => $get_produk['id_produk'],
                    'stok_produk' => $get_produk['stok_produk'],
                    'stok' => $stok,
                    'qty' => $row['qty'],
                    'jenis' => $row['jenis']
                );
                array_push($det_produk, $list);
            }

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_adjustment'] = $get_data;
                $result['det_adjustment'] = $det_produk;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'hapus') {
            $delete_data = $this->m_crud->delete_data($table, "id_adjustment = '".$_POST['id']."'");

            if ($delete_data) {
                $status = true;
            } else {
                $status = false;
            }

            echo $status;
        } else {
            $this->load->view('bo/index', $data);
        }
    }
    /*End adjustment*/
}
