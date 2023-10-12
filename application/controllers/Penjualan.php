<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penjualan extends CI_Controller
{

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        //$this->session->sess_destroy();

        $site_data = $this->m_website->site_data();
        $this->site = str_replace(' ', '', strtolower($site_data->nama));
        $this->control = 'Penjualan';

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

    /*Start orders*/
    public function orders($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'orders';
        $table = 'orders';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Orders';
        $data['title'] = 'Data Orders';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = "o.status NOT IN ('0', '5')";

        if(isset($_POST['search'])||isset($_POST['to_excel'])) {
            $filter = $_POST['filter'];

            if ($filter == 'pagging') {
                $filter = $this->session->search['filter'];
            }

            $this->session->set_userdata('search', array('any' => $_POST['any'], 'filter' => $filter, 'date' => $_POST['date'], 'bank' => $_POST['bank'], 'periode' => $_POST['periode']));
        }

        $search = $this->session->search['any']; $filter = $this->session->search['filter']; $date = $this->session->search['date']; $bank = $this->session->search['bank']; $periode = $this->session->search['periode'];
        if(isset($search)&&$search!=null) {
            ($where == null) ? null : $where .= " AND ";
            $where .= "(o.id_orders like '%".$search."%' OR m.nama like '%".$search."%' OR p.penerima like '%".$search."%')";
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

        if(isset($bank)&&$bank!=null) {
            ($where == null) ? null : $where .= " AND ";
            $where .= "pb.bank2 = '".$bank."'";
        }

        $where_filter = "";
        if (isset($filter)&&$filter!=null) {
            ($where == null) ? null : $where_filter .= " AND ";
            if ($filter == 'belum_bayar') {
                $where_filter .= "pb.status = '1'";
            } else if ($filter == 'belum_proses') {
                $where_filter .= "pb.status = '2'";
            } else if ($filter == 'belum_resi') {
                $where_filter .= "pb.status = '3' AND (p.no_resi = '' OR p.no_resi IS NULL)";
            } else if ($filter == 'belum_lacak') {
                $where_filter .= "o.status = '2' AND p.no_resi <> '' AND p.no_resi IS NOT NULL";
            } else if ($filter == 'dalam_proses') {
                $where_filter .= "o.status = '3'";
            } else if ($filter == 'berhasil') {
                $where_filter .= "o.status = '4'";
            }
        }

        if ($action == 'get_data') {
            $config = array();
            $config["base_url"] = "#";
            //$config["total_rows"] = $this->ajax_pagination_model->count_all();
            $config["total_rows"] = count($this->m_crud->join_data($table." o", "o.id_orders", array("det_orders do", "pengiriman p", "member m", "det_pembayaran dp", "pembayaran pb", "bank b"), array("do.orders=o.id_orders", "p.orders=o.id_orders", "m.id_member=o.member", "dp.orders=o.id_orders", "pb.id_pembayaran=dp.pembayaran", "b.id_bank=pb.bank2"), $where.$where_filter, null, "o.id_orders"));
            $config["per_page"] = 10;
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
            $read_data = $this->m_crud->join_data($table." o", "o.id_orders, o.tgl_orders, o.status status_order, m.nama nama_pemesan, p.id_pengiriman, p.penerima, p.alamat, p.provinsi, p.kota, p.kecamatan, p.kode_pos, p.telepon, p.biaya, p.kurir, p.service, p.no_resi, pb.id_pembayaran, pb.bank_tujuan, pb.tgl_konfirmasi, pb.status status_pembayaran, pb.kode_unik, b.gambar gambar_bank, pb.jumlah_voucher, pb.voucher", array("det_orders do", "pengiriman p", "member m", "det_pembayaran dp", "pembayaran pb", "bank b"), array("do.orders=o.id_orders", "p.orders=o.id_orders", "m.id_member=o.member", "dp.orders=o.id_orders", "pb.id_pembayaran=dp.pembayaran", "b.id_bank=pb.bank2"), $where.$where_filter, "o.tgl_orders DESC", "o.id_orders", $config["per_page"], $start);
            if ($read_data != null) {
                foreach ($read_data as $row) {
                    $status_order = $row['status_order'];
                    $status_pembayaran = $row['status_pembayaran'];
                    $list_produk = '';
                    $tagihan = 0;
                    $read_produk = $this->m_crud->join_data("det_orders do", "do.qty, do.hrg_jual, do.hrg_varian, do.diskon, dp.ukuran, dp.warna, p.code, p.nama nama_produk", array("det_produk dp", "produk p"), array("dp.id_det_produk=do.det_produk", "p.id_produk=dp.produk"), "do.orders='".$row['id_orders']."'");
                    foreach ($read_produk as $row_produk) {
                        $harga = $row_produk['hrg_jual']+$row_produk['hrg_varian'];
                        $diskon = $row_produk['diskon'];
                        $list_produk .= '<p>'.$row_produk['code'].' '.$row_produk['nama_produk'].'<br>('.$row_produk['ukuran'].' / '.$row_produk['warna'].')<br>'.$row_produk['qty'].' x '.number_format($harga).'<br>'.($diskon>0?'Diskon '.number_format($diskon).'<br>':'').'</p><p class="order-items"></p>';
                        $tagihan = $tagihan + ($row_produk['qty'] * ($harga-$diskon));
                    }
                    $tagihan = $tagihan + $row['biaya'] + $row['kode_unik'] - $row['jumlah_voucher'];
                    $id = str_replace('/', '_', $row['id_orders']);
                    if ($status_order=='1' && $status_pembayaran<'3') {
                        $input_resi = '';
                    } else {
                        if ($status_order=='4') {
                            $input_resi = '';
                        } else {
                            $input_resi = '
                            <div class="input-group input-group-sm" style="margin-top: 0px;">
                                <input type="text" class="form-control" id="data_resi' . $id . '" placeholder="Input Resi...">
                                <span class="input-group-btn"><button class="btn btn-primary" onclick="input_resi(\'' . $row['id_orders'] . '\')" type="button">' . ($row['no_resi'] == null ? 'Simpan' : 'Ubah') . '</button></span>
                            </div>
                            ';
                        }
                    }

                    if ($status_pembayaran=='1') {
                        $aksi = '<button id="batalkan" onclick="batalkan(\'' . $row['id_orders'] . '\')" class="btn btn-block btn-sm btn-danger">Batalkan Pesanan</button>';
                    } else if ($status_pembayaran=='2') {
                        $aksi = '<button id="batalkan" onclick="bukti_tf(\''.$row['id_pembayaran'].'\')" class="btn btn-block btn-sm btn-primary">Bukti Transfer</button><button id="verifikasi" onclick="verifikasi(\'' . $row['id_pembayaran'] . '\')" class="btn btn-block btn-sm btn-info">Verifikasi Pembayaran</button><button id="batalkan" onclick="batalkan(\'' . $row['id_orders'] . '\')" class="btn btn-block btn-sm btn-danger">Batalkan Pesanan</button>';
                    } else {
                        $aksi = '<button id="batalkan" onclick="bukti_tf(\''.$row['id_pembayaran'].'\')" class="btn btn-block btn-sm btn-primary">Bukti Transfer</button>';
                    }
                    $output .= '
                    <div class="box">
                        <div class="box-body" id="result_table">
                            <div id="head_order'.$id.'" class="col-md-3">
                                <p class="h5">
                                    <a href="#"><strong>'.$row['id_orders'].'</strong></a>
                                </p>
                                <small>Pemesan</small>
                                <p><span>'.$row['nama_pemesan'].'</span></p>
                                <small>Dikirim kepada</small>
                                <p><span>'.$row['penerima'].'</span></p>
                                <p><small>Tgl Pemesanan</small><br>'.date('d M Y', strtotime($row['tgl_orders'])).'</p>
                                <small>Alamat</small>
                                <p><span>'.$row['alamat'].', '.$row['kota'].', '.$row['provinsi'].'</span></p>
                                <small>Telepon</small>
                                <p><span>'.$row['telepon'].'</span></p>
                                <hr>
                                <input class="ck_print" id="print'.$id.'" type="checkbox" value="'.$row['id_orders'].'"><button class="btn btn-sm bg-aqua" onclick="print_label(\'single\', \''.$row['id_orders'].'\')" style="margin-left: 10px"><span class="fa fa-print"></span></button>
                            </div>
                            <div id="product'.$id.'" class="col-md-3">
                                <div class="scrollbar" style="height: 300px">
                                    <p><small>Produk</small><br>'.$list_produk.'</p>
                                    <p class="order-items"><small>Kode Unik</small><br>'.number_format($row['kode_unik']).'</p>
                                    <p class="order-items"><small>Voucher</small><br>'.number_format($row['jumlah_voucher']).'</p>
                                    <p class="order-items"><small>Ongkir</small><br>'.number_format($row['biaya']).'</p>
                                </div>
                                <hr>
                            </div>
                            <div id="address'.$id.'" class="col-md-3">
                                <div class="alert alert-'.($status_pembayaran=='1'?'danger':'success').'">
                                    <p><small>Tagihan</small><br></p>
                                    <p class="med mbtm-10" data-toggle="tooltip" data-placement="top" title="'.($status_pembayaran=='1'?'Belum Dibayar':'Sudah Dibayar').'"><span class="lnr"><img src="'.base_url().'assets/images/icon/clipboards.svg" alt="" class="svg icon2">Rp '.number_format($tagihan).'</span></p>
                                    <div class="row payment-stts">
                                        <div class="col-sm-6">'.($row['tgl_konfirmasi']!=null?date('d M Y', strtotime($row['tgl_konfirmasi'])):'').'</div>
                                        <div class="col-sm-6"><img src="'.base_url().$row['gambar_bank'].'" style="max-width: 90px; max-width: 70px"></div>
                                    </div>
                                </div>
                                <div id="action'.$id.'">
                                    '.$aksi.'
                                </div>
                                <hr>
                            </div>
                            <div id="ekspedisi'.$id.'" class="col-md-3">
                                <p class="mbtm-10"><small>Status Transaksi</small></p>
                                <div class="tr-status" id="status_transaksi'.$id.'">
                                    <ul>
                                        <li class="'.($status_pembayaran>='2'?'done':'undone').'" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.($status_pembayaran>='2'?'Sudah Dibayar':'Belum Dibayar').'">
                                            <img id="dollar" class="svg icon" src="'.base_url().'assets/images/icon/dollar.svg"/>
                                        </li>
    
                                        <li class="'.($status_pembayaran>='3'?'done':'undone').'" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.($status_pembayaran>='3'?'Sedang Diproses':'Belum Diproses').'">
                                            <img id="pack_delivery" class="svg icon" src="'.base_url().'assets/images/icon/pack_delivery.svg"/>
                                        </li>
    
                                        <li class="'.($row['no_resi']!=null?'done':'undone').'" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.($row['no_resi']!=null?'Sudah Dikirim':'Belum Dikirim').'">
                                            <img id="truck" class="svg icon" src="'.base_url().'assets/images/icon/truck.svg"/>
                                        </li>
    
                                        <li class="'.($status_order>='3'?'done':'undone').'" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.($status_order>='3'?'Dalam Pengiriman Kurir':'').'">
                                            <img id="truck_clock" class="svg icon" src="'.base_url().'assets/images/icon/truck_clock.svg"/>
                                        </li>
    
                                        <li class="'.($status_order>='4'?'done':'undone').'" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.($status_order>='4'?'Paket Telah Diterima':'').'">
                                            <img id="pack_delivered" class="svg icon" src="'.base_url().'assets/images/icon/pack_delivered.svg"/>
                                        </li>
                                    </ul>
                                </div>
                                <p class="mbtm-10"><small>Expedisi</small></p>
                                <img src="'.base_url().'assets/images/icon/'.strtolower($row['kurir']).'.png" style="max-width: 100px; max-height: 40px; margin-right:10px;">
                                <span class="label label-gray-blank">'.$row['kurir'].'-'.$row['service'].'</span>
                                <p class="mtop-20"><small>No. Resi</small><br><div id="no_resi'.$id.'">'.($row['no_resi']==null?'':'<a href="javascript:" data-toggle="tooltip" data-placement="right" title="" data-original-title="Lacak Pengiriman" onclick="lacak(\''.$row['id_pengiriman'].'\')">'.$row['no_resi'].'</a>').'</div></p>
                                <div id="input_resi'.$id.'">
                                '.$input_resi.'
                                </div>
                            </div>
                        </div>
                    </div>
                    ';
                }
            } else {
                $output .= '
                <div class="box">
                    <div class="box-body"><h4 class="text-center">Tidak ada data</h4></div>
                </div>
                ';
            }

            $result = array(
                'pagination_link' => $this->pagination->create_links(),
                'result_order' => $output
            );
            echo json_encode($result);
        }
        else if ($action == 'load_status') {
            $result = array();
            $orders = json_decode($_POST['orders'], true);

            $res_order = array();
            foreach ($orders as $row) {
                $get_data = $this->m_crud->get_join_data($table." o", "o.status status_order, p.id_pengiriman, p.no_resi, pb.status status_pembayaran", array("det_orders do", "pengiriman p", "det_pembayaran dp", "pembayaran pb"), array("do.orders=o.id_orders", "p.orders=o.id_orders", "dp.orders=o.id_orders", "pb.id_pembayaran=dp.pembayaran"), "o.id_orders='".$row['orders']."'", null, "o.id_orders");
                $status_order = $get_data['status_order'];
                $status_pembayaran = $get_data['status_pembayaran'];

                $list_status_order = '
                <ul>
                    <li class="'.($status_pembayaran>='2'?'done':'undone').'" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.($status_pembayaran>='2'?'Sudah Dibayar':'Belum Dibayar').'">
                        <img id="dollar" class="svg icon" src="'.base_url().'assets/images/icon/dollar.svg"/>
                    </li>

                    <li class="'.($status_pembayaran>='3'?'done':'undone').'" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.($status_pembayaran>='3'?'Sedang Diproses':'Belum Diproses').'">
                        <img id="pack_delivery" class="svg icon" src="'.base_url().'assets/images/icon/pack_delivery.svg"/>
                    </li>

                    <li class="'.($get_data['no_resi']!=null?'done':'undone').'" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.($get_data['no_resi']!=null?'Sudah Dikirim':'Belum Dikirim').'">
                        <img id="truck" class="svg icon" src="'.base_url().'assets/images/icon/truck.svg"/>
                    </li>

                    <li class="'.($status_order>='3'?'done':'undone').'" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.($status_order>='3'?'Dalam Pengiriman Kurir':'').'">
                        <img id="truck_clock" class="svg icon" src="'.base_url().'assets/images/icon/truck_clock.svg"/>
                    </li>

                    <li class="'.($status_order>='4'?'done':'undone').'" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.($status_order>='4'?'Paket Telah Diterima':'').'">
                        <img id="pack_delivered" class="svg icon" src="'.base_url().'assets/images/icon/pack_delivered.svg"/>
                    </li>
                </ul>
                ';

                if ($status_order=='1' && $status_pembayaran<'3') {
                    $input_resi = '';
                } else {
                    if ($status_order=='4') {
                        $input_resi = '';
                    } else {
                        $input_resi = '
                            <div class="input-group input-group-sm" style="margin-top: 0px;">
                                <input type="text" class="form-control" id="data_resi'.str_replace('/', '_', $row['orders']).'" placeholder="Input Resi...">
                                <span class="input-group-btn"><button class="btn btn-primary" onclick="input_resi(\''.$row['orders'].'\')" type="button">'.($get_data['no_resi']==null?'Simpan':'Ubah').'</button></span>
                            </div>
                        ';
                    }
                }

                if ($status_pembayaran=='1') {
                    $aksi = '<button id="batalkan" onclick="batalkan(\'' . $row['id_orders'] . '\')" class="btn btn-block btn-sm btn-danger">Batalkan Pesanan</button>';
                } else if ($status_pembayaran=='2') {
                    $aksi = '<button id="batalkan" onclick="bukti_tf(\''.$row['id_pembayaran'].'\')" class="btn btn-block btn-sm btn-primary">Bukti Transfer</button><button id="verifikasi" onclick="verifikasi(\'' . $row['id_pembayaran'] . '\')" class="btn btn-block btn-sm btn-info">Verifikasi Pembayaran</button><button id="batalkan" onclick="batalkan(\'' . $row['id_orders'] . '\')" class="btn btn-block btn-sm btn-danger">Batalkan Pesanan</button>';
                } else {
                    $aksi = '<button id="batalkan" onclick="bukti_tf(\''.$row['id_pembayaran'].'\')" class="btn btn-block btn-sm btn-primary">Bukti Transfer</button>';
                }

                $data_order = array(
                    'id' => str_replace('/', '_', $row['orders']),
                    'status' => $list_status_order,
                    'input_resi' => $input_resi,
                    'aksi' => $aksi,
                    'no_resi' => ($get_data['no_resi']==null?'':'<a href="javascript:" data-toggle="tooltip" data-placement="right" title="" data-original-title="Lacak Pengiriman" onclick="lacak(\''.$get_data['id_pengiriman'].'\')">'.$get_data['no_resi'].'</a>')
                );

                array_push($res_order, $data_order);
            }

            $result['status'] = true;
            $result['res_order'] = $res_order;

            echo json_encode($result);
        }
        else if ($action == 'verifikasi') {
            $result = array();
            $id_pembayaran = base64_decode($_POST['id_pembayaran']);

            $update_pembayaran = $this->m_crud->update_data("pembayaran", array('status'=>'3', 'tgl_verify'=>date('Y-m-d H:i:s')), "id_pembayaran='".$id_pembayaran."'");

            if ($update_pembayaran) {
                $result['status'] = true;
                $get_transaksi = $this->m_crud->read_data("det_pembayaran", "orders", "pembayaran='".$id_pembayaran."'");
                $result['res_orders'] = $get_transaksi;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'batalkan') {
            $result = array();
            $id_order = base64_decode($_POST['id_order']);

            $update_order = $this->m_crud->update_data("orders", array('status'=>'5'), "id_orders='".$id_order."'");

            if ($update_order) {
                $result['status'] = true;
                $result['res_orders'] = array(array('orders'=>$id_order));
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'input_resi') {
            $result = array();
            $id_order = base64_decode($_POST['id_order']);

            if ($_POST['no_resi']!='') {
                $this->m_crud->update_data("orders", array('status' => '2'), "id_orders='" . $id_order . "'");
            } else {
                $this->m_crud->update_data("orders", array('status' => '1'), "id_orders='" . $id_order . "'");
            }
            $update_resi = $this->m_crud->update_data("pengiriman", array('no_resi'=>$_POST['no_resi']), "orders='".$id_order."'");

            if ($update_resi) {
                $result['status'] = true;
                $result['res_orders'] = array(array('orders'=>$id_order));
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'lacak_resi') {
            $result = array();
            $id_pengiriman = $_POST['id_pengiriman'];
            $get_data = $this->m_crud->get_join_data("pengiriman p", "p.orders, p.kurir, p.no_resi, o.member", "orders o", "o.id_orders=p.orders", "p.id_pengiriman='".$id_pengiriman."'");

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
                    $id_orders = $get_data['orders'];
                    $member = $get_data['member'];
                    $get_total = $this->m_crud->get_data("det_orders", "SUM(qty*(hrg_jual+hrg_varian-diskon)) total", "orders='".$id_orders."'")['total'];
                    $check_data = $this->m_crud->get_data("poin", "kode_transaksi", "kode_transaksi='".$id_orders."'");
                    if ($check_data == null) {
                        $this->m_crud->create_data("poin", array('kode_transaksi'=>$id_orders, 'member'=>$member, 'poin'=>floor($get_total/100000), 'keterangan'=>'Pembelian'));
                    }
                    $this->m_crud->update_data("orders", array('status'=>'4'), "id_orders='".$get_data['orders']."'");
                    $result['message'] = "Paket telah tiba di tujuan";
                } else {
                    $this->m_crud->update_data("orders", array('status'=>'3'), "id_orders='".$get_data['orders']."'");
                    $result['message'] = "Paket dalam proses pengiriman";
                }
                $result['res_orders'] = array(array('orders'=>$get_data['orders']));
                $result['status'] = true;
            } else {
                $result['status'] = false;
                $result['message'] = "Nomor resi salah atau belum terdaftar";
            }

            echo json_encode($result);
        } else if ($action == 'load_header') {
            $read_data = $this->m_crud->join_data($table." o", "o.status status_order, p.no_resi, pb.status status_pembayaran", array("det_orders do", "pengiriman p", "member m", "det_pembayaran dp", "pembayaran pb"), array("do.orders=o.id_orders", "p.orders=o.id_orders", "m.id_member=o.member", "dp.orders=o.id_orders", "pb.id_pembayaran=dp.pembayaran"), $where, "o.tgl_orders DESC", "o.id_orders");
            $belum_bayar = 0; $belum_proses = 0; $belum_resi = 0; $belum_lacak = 0; $dalam_proses = 0; $berhasil = 0;
            foreach ($read_data as $row) {
                $status_order = $row['status_order'];
                $status_pembayaran = $row['status_pembayaran'];

                $status_pembayaran=='1'?$belum_bayar = $belum_bayar + 1:null;
                $status_pembayaran=='2'?$belum_proses = $belum_proses + 1:null;
                $status_pembayaran=='3' && $row['no_resi']==null?$belum_resi = $belum_resi + 1:null;
                $status_order=='2' && $row['no_resi']!=null?$belum_lacak = $belum_lacak + 1:null;
                $status_order=='3'?$dalam_proses = $dalam_proses + 1:null;
                $status_order=='4'?$berhasil = $berhasil + 1:null;
            }

            $result = array(
                'belum_bayar' => $belum_bayar,
                'belum_proses' => $belum_proses,
                'belum_resi' => $belum_resi,
                'belum_lacak' => $belum_lacak,
                'dalam_proses' => $dalam_proses,
                'berhasil' => $berhasil
            );

            echo json_encode($result);
        } else {
            $this->load->view('bo/index', $data);
        }
    }

    public function print_label($id=null) {
        $data_order = json_decode(base64_decode($id), true);
        if (is_array($data_order)) {
            $situs = $this->m_crud->get_data("site", "nama, CONCAT('".base_url()."', logo) logo, web", "id_site='2222'");
            $get_sosmed = $this->m_crud->get_data("setting", "sosmed", "id_setting='1111'")['sosmed'];
            $decode = json_decode($get_sosmed, true);
            $found = 0;
            foreach($decode as $key => $value) {
                if ($value['id'] == 'whatsapp') {
                    $found = $key;
                    break;
                }
            }
            $situs['tlp'] = $decode[$found]['value'];
            $data = implode(',', $data_order);
            $get_data = $this->m_crud->join_data("orders o", "o.id_orders, o.tgl_orders, m.nama nama_member, png.penerima, png.alamat, png.provinsi, png.kota, png.kecamatan, png.kode_pos, png.telepon, png.kurir, png.service, png.biaya, pb.kode_unik, pb.jumlah", array("member m", "pengiriman png", "det_pembayaran dpb", "pembayaran pb"), array("m.id_member=o.member", "png.orders=o.id_orders", "dpb.orders=o.id_orders", "pb.id_pembayaran=dpb.pembayaran"), "o.id_orders IN (".$data.")");
            $this->load->view("bo/Penjualan/print_label", array('situs'=>$situs, 'data'=>$get_data));
        } else {
            redirect(base_url());
        }
    }
    /*End orders*/
}
