<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan extends CI_Controller
{

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        //$this->session->sess_destroy();

        $site_data = $this->m_website->site_data();
        $this->site = str_replace(' ', '', strtolower($site_data->nama));
        $this->control = 'Laporan';

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

    public function penjualan($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'penjualan';
        $table = 'orders';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Laporan';
        $data['title'] = 'Penjualan';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = "o.status<>'0'";

        if(isset($_POST['search'])||isset($_POST['to_excel'])) {
            $this->session->set_userdata('search', array('any' => $_POST['any'], 'date' => $_POST['date'], 'status' => $_POST['status'], 'periode' => $_POST['periode']));
        }

        $search = $this->session->search['any']; $date = $this->session->search['date']; $status = $this->session->search['status']; $periode = $this->session->search['periode'];
        if(isset($search)&&$search!=null) {
            ($where == null) ? null : $where .= " AND ";
            $where .= "(o.id_orders like '%".$search."%' OR m.nama like '%".$search."%')";
        }

        if(isset($date)&&$date!=null) {
            $explode_date = explode(' - ', $date);
            $tgl_awal = $explode_date[0]; $tgl_akhir = $explode_date[1];
        } else {
            $tgl_awal = date('Y-m-d'); $tgl_akhir = date('Y-m-d');
        }

        if($periode==null) {
            ($where == null) ? null : $where .= " AND ";
            $where .= "LEFT(o.tgl_orders, 10) BETWEEN '" . $tgl_awal . "' AND '" . $tgl_akhir . "'";
        }

        if(isset($status)&&$status!=null) {
            ($where == null) ? null : $where .= " AND ";
            $where .= "o.status = ".$status;
        }

        if ($action == 'get_data') {
            $config = array();
            $config["base_url"] = "#";
            //$config["total_rows"] = $this->ajax_pagination_model->count_all();
            $config["total_rows"] = $this->m_crud->count_data_join($table." o", "o.id_orders", array("det_orders dto", "pengiriman p", "member m", "det_pembayaran dp", "pembayaran pb"), array("dto.orders=o.id_orders", "p.orders=o.id_orders", "m.id_member=o.member", "dp.orders=o.id_orders", "pb.id_pembayaran=dp.pembayaran"), $where, null, null, 0, 0, null, 'DISTINCT');
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
            $read_data = $this->m_crud->join_data($table." o", "o.id_orders, o.tgl_orders, o.status, pb.status status_pembayaran, m.nama, p.kurir, p.service, p.biaya, p.no_resi, SUM(dto.qty*(dto.hrg_jual+dto.hrg_varian)) sub_total, SUM(dto.qty*dto.diskon) diskon, pb.jumlah_voucher, pb.voucher", array("det_orders dto", "pengiriman p", "member m", "det_pembayaran dp", "pembayaran pb"), array("dto.orders=o.id_orders", "p.orders=o.id_orders", "m.id_member=o.member", "dp.orders=o.id_orders", "pb.id_pembayaran=dp.pembayaran"), $where, "o.tgl_orders DESC", "o.id_orders", $config["per_page"], $start);
            $output .= '
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th width="1%" class="text-center">#</th>
                    <th>Kode Orders</th>
                    <th>Tanggal</th>
                    <th>Member</th>
                    <th>Kurir</th>
                    <th>Service</th>
                    <th>Sub Total</th>
                    <th>Diskon</th>
                    <th>Voucher</th>
                    <th>Omset</th>
                    <th>Ongkir</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            ';
            $no = $start+1;
            if ($read_data != null) {
                $sub_total = 0; $diskon = 0; $ongkir = 0; $voucher = 0;
                foreach ($read_data as $row) {
                    if ($row['status'] == '1') {
                        if($row['status_pembayaran']>=2){
                            $status = '<span class="label label-warning">Sudah Dibayar</span>';
                        } else {
                            $status = '<span class="label bg-maroon">Menunggu Pembayaran</span>';
                        }
                    } else if ($row['status'] == '2') {
                        if($row['no_resi']!=null && $row['no_resi']!=''){
                            $status = '<span class="label label-info">Sudah Dikirim</span>';
                        } else {
                            $status = '<span class="label bg-purple">Belum Dikirim</span>';
                        }
                    } else if ($row['status'] == '3') {
                        $status = '<span class="label label-primary">Dalam Pengiriman Kurir</span>';
                    } else if ($row['status'] == '4') {
                        $status = '<span class="label label-success">Success</span>';
                    } else {
                        $status = '<span class="label label-danger">Batal</span>';
                    }
                    $output .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Pilihan <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu dropdown-position">
                                <li><a href="#" onclick="detail(\'' . $row['id_orders'] . '\')">Detail</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['id_orders'] . '</td>
                        <td>' . $row['tgl_orders'] . '</td>
                        <td>' . $row['nama'] . '</td>
                        <td>' . $row['kurir'] . '</td>
                        <td>' . $row['service'] . '</td>
                        <td>' . number_format($row['sub_total']) . '</td>
                        <td>' . number_format($row['diskon']) . '</td>
                        <td>' . number_format($row['jumlah_voucher']) . '</td>
                        <td>' . number_format($row['sub_total']-$row['diskon']-$row['jumlah_voucher']) . '</td>
                        <td>' . number_format($row['biaya']) . '</td>
                        <td>' . number_format($row['sub_total']-$row['diskon']+$row['biaya']-$row['jumlah_voucher']) . '</td>
                        <td>' . $status . '</td>
                    </tr>
                ';
                    $sub_total = $sub_total + $row['sub_total'];
                    $diskon = $diskon + $row['diskon'];
                    $ongkir = $ongkir + $row['biaya'];
                    $voucher = $voucher + $row['jumlah_voucher'];
                }
                $total = $this->m_crud->get_join_data($table." o", "SUM(dto.qty*(dto.hrg_jual+dto.hrg_varian)) sub_total, SUM(dto.qty*dto.diskon) diskon", array("det_orders dto", "pengiriman p", "member m", "det_pembayaran dp", "pembayaran pb"), array("dto.orders=o.id_orders", "p.orders=o.id_orders", "m.id_member=o.member", "dp.orders=o.id_orders", "pb.id_pembayaran=dp.pembayaran"), $where);
                $total_master = $this->m_crud->get_join_data($table." o", "SUM(p.biaya) biaya, SUM(pb.jumlah_voucher) voucher", array("pengiriman p", "member m", "det_pembayaran dp", "pembayaran pb"), array("p.orders=o.id_orders", "m.id_member=o.member", "dp.orders=o.id_orders", "pb.id_pembayaran=dp.pembayaran"), $where);
                $output .= '
                    <tr>
                        <th colspan="7">Total per halaman</th>
                        <th>' . number_format($sub_total) . '</th>
                        <th>' . number_format($diskon) . '</th>
                        <th>' . number_format($voucher) . '</th>
                        <th>' . number_format($sub_total-$diskon-$voucher) . '</th>
                        <th>' . number_format($ongkir) . '</th>
                        <th>' . number_format($sub_total-$diskon-$voucher+$ongkir) . '</th>
                        <th></th>
                    </tr>
                    <tr>
                        <th colspan="7">Total</th>
                        <th>' . number_format($total['sub_total']) . '</th>
                        <th>' . number_format($total['diskon']) . '</th>
                        <th>' . number_format($total_master['voucher']) . '</th>
                        <th>' . number_format($total['sub_total']-$total['diskon']-$total_master['voucher']) . '</th>
                        <th>' . number_format($total_master['biaya']) . '</th>
                        <th>' . number_format($total['sub_total']-$total['diskon']-$total_master['voucher']+$total_master['biaya']) . '</th>
                        <th></th>
                    </tr>
                ';
            } else {
                $output .= '
                <tr>
                    <td colspan="14" class="text-center">Tidak ada data</td>
                </tr>
                ';
            }
            $output .= '</table>';

            $result = array(
                'pagination_link' => $this->pagination->create_links(),
                'result_table' => $output
            );
            echo json_encode($result);
        } else if ($action == 'detail') {
            $get_data = $this->m_crud->join_data("det_orders do", "p.nama, p.code, dp.ukuran, dp.warna, (do.hrg_jual+do.hrg_varian) hrg_jual, do.qty, do.diskon", array("det_produk dp", "produk p"), array("dp.id_det_produk=do.det_produk", "p.id_produk=dp.produk"), "do.orders='".$_POST['id']."'");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;

                $list_produk = '';
                foreach ($get_data as $row) {
                    $list_produk .= '
                    <tr>
                        <td>'.$row['code'].'</td>
                        <td>'.$row['nama'].'</td>
                        <td>'.$row['ukuran'].'</td>
                        <td>'.$row['warna'].'</td>
                        <td>'.$row['qty'].'</td>
                        <td>'.number_format($row['hrg_jual']).'</td>
                        <td>'.number_format($row['diskon']).'</td>
                    </tr>
                    ';
                }
                $result['res_produk'] = $list_produk;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else {
            $this->load->view('bo/index', $data);
        }
    }
    /*End laporan penjualan*/

    public function feedback($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'feedback';
        $table = 'feedback';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Laporan';
        $data['title'] = 'Kritik & Saran';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = null;

        if(isset($_POST['search'])||isset($_POST['to_excel'])) {
            $this->session->set_userdata('search', array('any' => $_POST['any'], 'date' => $_POST['date'], 'status' => $_POST['status'], 'periode' => $_POST['periode']));
        }

        $search = $this->session->search['any']; $date = $this->session->search['date']; $status = $this->session->search['status']; $periode = $this->session->search['periode'];
        if(isset($search)&&$search!=null) {
            ($where == null) ? null : $where .= " AND ";
            $where .= "(m.ol_code like '%".$search."%' OR m.nama like '%".$search."%')";
        }

        if(isset($date)&&$date!=null) {
            $explode_date = explode(' - ', $date);
            $tgl_awal = $explode_date[0]; $tgl_akhir = $explode_date[1];
        } else {
            $tgl_awal = date('Y-m-d'); $tgl_akhir = date('Y-m-d');
        }

        if($periode==null) {
            ($where == null) ? null : $where .= " AND ";
            $where .= "LEFT(f.tanggal, 10) BETWEEN '" . $tgl_awal . "' AND '" . $tgl_akhir . "'";
        }

        if ($action == 'get_data') {
            $config = array();
            $config["base_url"] = "#";
            //$config["total_rows"] = $this->ajax_pagination_model->count_all();
            $config["total_rows"] = $this->m_crud->count_data_join($table." f", "f.id_feedback", "member m", "m.id_member=f.member", $where);
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
            $read_data = $this->m_crud->join_data($table." f", "m.ol_code, m.nama, f.tanggal, f.pesan", "member m", "m.id_member=f.member", $where, "f.tanggal DESC", null, $config["per_page"], $start);
            $output .= '
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th>Kode Member</th>
                    <th>Nama Member</th>
                    <th>Tanggal</th>
                    <th>Pesan</th>
                </tr>
            ';
            $no = $start+1;
            if ($read_data != null) {
                foreach ($read_data as $row) {
                    $output .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>' . $row['ol_code'] . '</td>
                        <td>' . $row['nama'] . '</td>
                        <td>' . $row['tanggal'] . '</td>
                        <td>' . $row['pesan'] . '</td>
                    </tr>
                ';
                }
            } else {
                $output .= '
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data</td>
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
        elseif($action=='simpan'){

        }
        else {
            $this->load->view('bo/index', $data);
        }

    }
}