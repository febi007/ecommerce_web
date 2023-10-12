<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produk extends CI_Controller
{

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        //$this->session->sess_destroy();

        $site_data = $this->m_website->site_data();
        $this->site = str_replace(' ', '', strtolower($site_data->nama));
        $this->control = 'Produk';

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

    /*Start master data produk*/
    public function data_produk($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'data_produk';
        $table = 'produk';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Produk';
        $data['title'] = 'Data Produk';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = null;
        if(isset($_POST['search'])||isset($_POST['to_excel'])) {
            $this->session->set_userdata('search', array('any' => $_POST['any'], 'page' => $page));
        }
//        if ($page > 1) {
//            $this->session->set_userdata('search', array('any' => $this->session->search['any'], 'page' => $page));
//        }

        $search = $this->session->search['any'];
//        $page = $this->session->search['page'];
//        if(!isset($page)&&$page==null) {
//            $page = 1;
//        }

        if(isset($search)&&$search!=null) {
            ($where == null) ? null : $where .= " AND ";
            $where .= "(p.nama like '%".$search."%' OR p.code like '%".$search."%' OR k.nama like '%".$search."%' OR m.nama like '%".$search."%')";
        }


        if ($action == 'get_data') {
            $config = array();
            $config["base_url"] = "#";
            $config["total_rows"] = $this->m_crud->count_data_join($table." p", "p.id_produk", array(array("table"=>"det_produk dp", "type"=>"LEFT"), array("table"=>"kelompok k", "type"=>"LEFT"), array("table"=>"merk m", "type"=>"LEFT")), array("dp.produk=p.id_produk AND dp.code=p.code", "k.id_kelompok=p.kelompok", "m.id_merk=p.merk"), $where,"p.id_produk DESC");
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
            $read_data = $this->m_crud->join_data($table." p", "p.stok, p.id_produk, p.nama, p.deskripsi, p.code, p.pre_order, p.free_return, dp.berat, ifnull(dp.hrg_beli, 0) hrg_beli, ifnull(dp.hrg_jual, 0) hrg_jual, ifnull(k.nama, 'Tidak Memiliki Kelompok') nama_kelompok, ifnull(m.nama, 'Tidak Memiliki Merk') nama_merk", array(array("table"=>"det_produk dp", "type"=>"LEFT"), array("table"=>"kelompok k", "type"=>"LEFT"), array("table"=>"merk m", "type"=>"LEFT")), array("dp.produk=p.id_produk AND dp.code=p.code", "k.id_kelompok=p.kelompok", "m.id_merk=p.merk"), $where, "p.id_produk DESC", null, $config["per_page"], $start);
            $output .= /** @lang text */
                '
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th width="1%" class="text-center">#</th>
                    <th>SKU</th>
                    <th>Nama</th>
                    <th>Stok</th>
                    <th>Deskripsi</th>
                    <th>Kelompok</th>
                    <th>Merk</th>
                    <th>Berat</th>
                    <!--<th>Harga Beli</th>-->
                    <th>Harga Jual</th>
                    <th>Pre Order</th>
                    <th>Free Return</th>
                </tr>
            ';
            $no = $start+1;
            if ($read_data != null) {
                foreach ($read_data as $row) {
                    if ($row['free_return'] == '1') {
                        $return = '<span class="label label-success">Ya</span>';
                    } else {
                        $return = '<span class="label label-danger">Tidak</span>';
                    }

                    if ($row['pre_order']!='0') {
                        $order = $row['pre_order'].' hari';
                    } else {
                        $order = '-';
                    }
                    $output .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Pilihan <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu dropdown-position">
                                <li><a href="#" onclick="detail(\'' . $row['id_produk'] . '\')">Detail</a></li>
                                <li><a href="#" onclick="edit(\'' . $row['id_produk'] . '\')">Edit</a></li>
                                <li><a href="#" onclick="hapus(\'' . $row['id_produk'] . '\')">Hapus</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['code'] . '</td>
                        <td>' . (strlen($row['nama'])>30?substr($row['nama'], 0, 30).'...':$row['nama']) . '</td>
                           <td>' . $row['stok'] . '</td>

                        <td>' . (strlen($row['deskripsi'])>=30?mb_substr($row['deskripsi'], 0, 30).'...':$row['deskripsi']) . '</td>
                        <td>' . $row['nama_kelompok'] . '</td>
                        <td>' . $row['nama_merk'] . '</td>
                        <td>' . $row['berat'] . ' gr</td>
                        <!--<td class="text-right">' . number_format($row['hrg_beli']) . '</td>-->
                        <td class="text-right">' . number_format($row['hrg_jual']) . '</td>
                        <td>' . $order . '</td>
                        <td>' . $return . '</td>
                    </tr>
                ';
                }
            } else {
                $output .= '
                <tr>
                    <td colspan="12" class="text-center">Tidak ada data</td>
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
        else if ($action == 'cek_nama') {
            $cek_username = null;

            if ($_POST['nama']!='' && $_POST['group']!='') {
                $where = "nama='" . $_POST['nama'] . "' AND groups='" . $_POST['group'] . "'";

                $_POST['param'] == 'edit' ? $where .= " AND nama<>'" . $_POST['nama'] . "' AND groups='" . $_POST['group'] . "'" : null;

                $cek_username = $this->m_crud->get_data($table, "nama", $where);
            }

            if ($cek_username == null) {
                echo 'true';
            } else {
                echo 'false';
            }
        }
        else if ($action == 'simpan') {
            $row = 'gambar[]';
            $files = $_FILES['gambar'];
            $config['upload_path']          = './assets/images/produk';
            $config['allowed_types']        = 'gif|jpg|jpeg|png|svg';
            $config['max_size']             = 5120;
            $this->load->library('upload', $config);
            $valid = true;

            $this->db->trans_begin();
            $tgl = date('Y-m-d H:i:s');

            if ($_POST['param'] == 'add') {
                $code = $this->m_website->generate_kode('produk', $_POST['kelompok']);

                /*insert produk*/
                $data_produk = array(
                    'kelompok' => $_POST['kelompok'],
                    'merk' => $_POST['merk'],
                    'nama' => $_POST['nama'],
                    'deskripsi' => $_POST['deskripsi'],
                    'code' => $code,
                    'tgl_input' => $tgl,
                    'tgl_update' => $tgl,
                    'pre_order' => $_POST['pre_order']==''?'0':$_POST['pre_order'],
                    'free_return' => isset($_POST['free_return'])?$_POST['free_return']:'0'
                );

                $this->m_crud->create_data($table, $data_produk);
                $id_produk = $this->db->insert_id();

                /*insert det produk*/
                $det_produk = array(
                    'produk' => $id_produk,
                    'code' => $code,
                    'berat' => $_POST['berat'],
                    'ukuran' => $_POST['ukuran'],
                    'warna' => $_POST['warna'],
                    /*'hrg_beli' => str_replace(',','',$_POST['hrg_beli']),*/
                    'hrg_beli' => 0,
                    'hrg_jual' => str_replace(',','',$_POST['hrg_jual']),
                    'hrg_varian' => 0,
                    'hrg_sebelum' => str_replace(',','',$_POST['hrg_jual'])
                );
                $this->m_crud->create_data("det_produk", $det_produk);

                /*insert images*/
                foreach ($files['name'] as $key => $image) {
                    $_FILES[$row]['name']= $files['name'][$key];
                    $_FILES[$row]['type']= $files['type'][$key];
                    $_FILES[$row]['tmp_name']= $files['tmp_name'][$key];
                    $_FILES[$row]['error']= $files['error'][$key];
                    $_FILES[$row]['size']= $files['size'][$key];

                    if( (!$this->upload->do_upload($row)) && $files['name'][$key]!=null){
                        $valid = false;
                        $file[$row]['file_name']=null;
                        $file[$row] = $this->upload->data();
                        $data['error_'.$row] = $this->upload->display_errors();
                    } else{
                        $file[$row] = $this->upload->data();
                        $data[$row] = $file;

                        if ($file[$row]['file_name'] != '') {
                            $this->m_crud->create_data("gambar_produk", array('produk' => $id_produk, 'gambar' => 'assets/images/produk/' . $file[$row]['file_name']));
                        }
                    }
                }

                if (isset($_POST['varian'])) {
                    for ($i=0; $i<=$_POST['max_data_varian']; $i++) {
                        if ($_POST['u_'.$i]!='' && $_POST['w_'.$i]!='' && $_POST['hrg_varian_'.$i]!='') {
                            /*insert det produk*/
                            $det_produk = array(
                                'produk' => $id_produk,
                                'code' => $code.sprintf('%03d', $i+1),
                                'ukuran' => $_POST['u_'.$i],
                                'berat' => $_POST['berat'],
                                'warna' => $_POST['w_'.$i],
                                /*'hrg_beli' => str_replace(',','',$_POST['hrg_beli']),*/
                                'hrg_beli' => 0,
                                'hrg_jual' => str_replace(',','',$_POST['hrg_jual']),
                                'hrg_varian' => str_replace(',','',$_POST['hrg_varian_'.$i]),
                                'hrg_sebelum' => str_replace(',','',$_POST['hrg_varian_'.$i])
                            );
                            $this->m_crud->create_data("det_produk", $det_produk);
                        }
                    }
                }

                if (isset($_POST['grosir'])) {
                    for ($i=0; $i<=$_POST['max_data_grosir']; $i++) {
                        if ($_POST['q1_'.$i]!='' && $_POST['hrg_'.$i]!='') {
                            $grosir = array(
                                'produk' => $id_produk,
                                'qty1' => $_POST['q1_'.$i],
                                'qty2' => $_POST['q2_'.$i]==''?'0':$_POST['q2_'.$i],
                                'hrg_jual' => str_replace(',','',$_POST['hrg_'.$i])
                            );
                            $this->m_crud->create_data("grosir", $grosir);
                        }
                    }
                }
            } else {
                /*simpan edit*/
                $id = $_POST['id'];
                $tgl = date('Y-m-d H:i:s');
                $get_produk = $this->m_crud->get_join_data($table." p", "dp.hrg_jual, p.code", "det_produk dp", "dp.produk=p.id_produk AND dp.code=p.code", "p.id_produk='".$id."'");

                /*insert produk*/
                $data_produk = array(
                    'kelompok' => $_POST['kelompok'],
                    'merk' => $_POST['merk'],
                    'nama' => $_POST['nama'],
                    'code' => strtoupper($_POST['code']),
                    'deskripsi' => $_POST['deskripsi'],
                    'tgl_update' => $tgl,
                    'pre_order' => $_POST['pre_order']==''?'0':$_POST['pre_order'],
                    'free_return' => isset($_POST['free_return'])?$_POST['free_return']:'0'
                );
                $this->m_crud->update_data($table, $data_produk, "id_produk='".$id."'");

                /*insert det produk*/
                $det_produk = array(
                    'berat' => $_POST['berat'],
                    'ukuran' => $_POST['ukuran'],
                    'warna' => $_POST['warna'],
                    'code' => strtoupper($_POST['code']),
                    /*'hrg_beli' => str_replace(',','',$_POST['hrg_beli']),*/
                    'hrg_beli' => 0,
                    'hrg_jual' => str_replace(',','',$_POST['hrg_jual']),
                    'hrg_varian' => 0,
                    'hrg_sebelum' => $get_produk['hrg_jual']
                );
                $this->m_crud->update_data("det_produk", $det_produk, "produk='".$id."' AND code='".$get_produk['code']."'");

                /*insert images*/
                foreach ($files['name'] as $key => $image) {
                    $_FILES[$row]['name']= $files['name'][$key];
                    $_FILES[$row]['type']= $files['type'][$key];
                    $_FILES[$row]['tmp_name']= $files['tmp_name'][$key];
                    $_FILES[$row]['error']= $files['error'][$key];
                    $_FILES[$row]['size']= $files['size'][$key];

                    if( (!$this->upload->do_upload($row)) && $files['name'][$key]!=null){
                        $valid = false;
                        $file[$row]['file_name']=null;
                        $file[$row] = $this->upload->data();
                        $data['error_'.$row] = $this->upload->display_errors();
                    } else{
                        $file[$row] = $this->upload->data();
                        $data[$row] = $file;

                        if ($file[$row]['file_name'] != '') {
                            $this->m_crud->create_data("gambar_produk", array('produk' => $id, 'gambar' => 'assets/images/produk/' . $file[$row]['file_name']));
                        }
                    }
                }

                if (isset($_POST['varian'])) {
                    $max_data = $this->m_crud->get_data("det_produk", "MAX(SUBSTRING(code, 9, 3)) max_code", "produk='".$id."'");
                    for ($i=0; $i<=$_POST['max_data_varian']; $i++) {
                        if ($_POST['u_'.$i]!='' && $_POST['w_'.$i]!='' && $_POST['hrg_varian_'.$i]!='') {
                            if ($_POST['id_varian_'.$i] == 'new') {
                                /*insert det produk*/
                                $det_produk = array(
                                    'produk' => $id,
                                    'code' => $get_produk['code'] . sprintf('%03d', $max_data['max_code'] + ($i+1)),
                                    'ukuran' => $_POST['u_' . $i],
                                    'berat' => $_POST['berat'],
                                    'warna' => $_POST['w_' . $i],
                                    /*'hrg_beli' => str_replace(',','',$_POST['hrg_beli']),*/
                                    'hrg_beli' => 0,
                                    'hrg_jual' => str_replace(',','',$_POST['hrg_jual']),
                                    'hrg_varian' => str_replace(',','',$_POST['hrg_varian_'.$i]),
                                    'hrg_sebelum' => $get_produk['hrg_jual']
                                );
                                $this->m_crud->create_data("det_produk", $det_produk);
                            } else {
                                $get_varian = $this->m_crud->get_data("det_produk", "hrg_jual", "id_det_produk='".$_POST['id_varian_'.$i]."'");
                                $det_produk = array(
                                    'ukuran' => $_POST['u_' . $i],
                                    'berat' => $_POST['berat'],
                                    'warna' => $_POST['w_' . $i],
                                    /*'hrg_beli' => str_replace(',','',$_POST['hrg_beli']),*/
                                    'hrg_beli' => 0,
                                    'hrg_jual' => str_replace(',','',$_POST['hrg_jual']),
                                    'hrg_varian' => str_replace(',','',$_POST['hrg_varian_'.$i]),
                                    'hrg_sebelum' => $get_produk['hrg_jual']
                                );
                                $this->m_crud->update_data("det_produk", $det_produk, "id_det_produk='".$_POST['id_varian_'.$i]."'");
                            }
                        }
                    }
                }

                $this->m_crud->delete_data("grosir", "produk='".$id."'");
                if (isset($_POST['grosir'])) {
                    for ($i=0; $i<=$_POST['max_data_grosir']; $i++) {
                        if ($_POST['q1_'.$i]!='' && $_POST['hrg_'.$i]!='') {
                            $grosir = array(
                                'produk' => $id,
                                'qty1' => $_POST['q1_'.$i],
                                'qty2' => $_POST['q2_'.$i]==''?'0':$_POST['q2_'.$i],
                                'hrg_jual' => str_replace(',','',$_POST['hrg_'.$i])
                            );
                            $this->m_crud->create_data("grosir", $grosir);
                        }
                    }
                }
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo true;
            }
        }
        else if ($action == 'cek_varian') {
            $id = $_POST['id'];
            $check_data = $this->m_crud->count_data("kartu_stok", "det_produk", "det_produk='".$id."'");

            if ($check_data > 0) {
                echo false;
            } else {
                $this->m_crud->delete_data("det_produk", "id_det_produk='".$id."'");
                echo true;
            }
        }
        else if ($action == 'get_group') {
            $result = array();
            $list = '<option value="">Pilih Group</option>';
            $read_group = $this->m_crud->read_data("groups", "*", "status='1'");

            if ($read_group != null) {
                $result['status'] = true;
                foreach ($read_group as $row) {
                    $list .= '<option value="'.$row['id_groups'].'">'.$row['nama'].'</option>';
                }
                $result['group'] = $list;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        }
        else if ($action == 'get_kelompok') {
            $result = array();
            $list = '<option value="">Pilih Kelompok</option>';
            $read_kelompok = $this->m_crud->join_data("kelompok k", "k.id_kelompok, k.nama, g.nama nama_group", "groups g", "g.id_groups=k.groups", "k.status='1'", "k.nama");

            if ($read_kelompok != null) {
                $result['status'] = true;
                foreach ($read_kelompok as $row) {
                    $list .= '<option value="'.$row['id_kelompok'].'">'.$row['nama'].' | '.$row['nama_group'].'</option>';
                }
                $result['kelompok'] = $list;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        }
        else if ($action == 'get_merk') {
            $result = array();
            $list = '<option value="">Pilih Merk</option>';
            $read_merk = $this->m_crud->read_data("merk", "*", "status='1'");

            if ($read_merk != null) {
                $result['status'] = true;
                foreach ($read_merk as $row) {
                    $list .= '<option value="'.$row['id_merk'].'">'.$row['nama'].'</option>';
                }
                $result['merk'] = $list;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        }
        else if ($action == 'get_gambar') {
            $get_gambar = $this->m_crud->read_data("gambar_produk", "id_gambar, gambar", "produk='".$_POST['id']."'");
            if ($get_gambar != null) {
                $list_gambar = '';
                foreach ($get_gambar as $baris => $row_gambar) {
                    if((($baris) % 4) == 0) {
                        $list_gambar .= '<div class="row" style="margin-top: 10px">';
                    }

                    $list_gambar .= '<div class="col-lg-3"><span class="badge bg-red topcorner" onclick="hapus_gambar(\''.$row_gambar['id_gambar'].'\')">&times;</span><img src="'.base_url().$row_gambar['gambar'].'" class="img_preview"></div>';

                    if((($baris+1) % 4) == 0) {
                        $list_gambar .= '</div>';
                    }
                }
            } else {
                $list_gambar = '<img src="'.base_url().'assets/images/no_image.png'.'" class="img_preview">';
            }
            echo $list_gambar;
        }
        else if ($action == 'detail') {
            $result = array(); $list_varian = ''; $list_grosir = ''; $list_gambar = ''; $id = $_POST['id'];

            $get_deskripsi = $this->m_crud->get_data("produk", "deskripsi", "id_produk='".$id."'");
            $get_varian = $this->m_crud->join_data("det_produk dp", "dp.ukuran, dp.warna, dp.hrg_jual", "produk p", "p.id_produk=dp.produk AND p.code<>dp.code", "p.id_produk='".$id."'");
            $get_grosir = $this->m_crud->read_data("grosir", "qty1, qty2, hrg_jual", "produk='".$id."'");
            $get_gambar = $this->m_crud->read_data("gambar_produk", "gambar", "produk='".$id."'");

            if ($get_varian != null) {
                foreach ($get_varian as $row_varian) {
                    $list_varian .= '<tr><td>' .$row_varian['ukuran'].'</td><td>' .$row_varian['warna'].'</td><td class="text-right">' .number_format($row_varian['hrg_jual']).'</td></tr>';
                }
            } else {
                $list_varian = '<tr><td colspan="3" class="text-center">Tidak memiliki varian</td></tr>';
            }

            if ($get_grosir != null) {
                foreach ($get_grosir as $row_grosir) {
                    $list_grosir .= '<tr><td>' .$row_grosir['qty1'].'</td><td>' .$row_grosir['qty2'].'</td><td class="text-right">' .number_format($row_grosir['hrg_jual']).'</td></tr>';
                }
            } else {
                $list_grosir = '<tr><td colspan="3" class="text-center">Tidak memiliki harga grosir</td></tr>';
            }

            if ($get_gambar != null) {
                foreach ($get_gambar as $baris => $row_gambar) {
                    if((($baris) % 4) == 0) {
                        $list_gambar .= '<div class="row" style="margin-top: 10px">';
                    }

                    $list_gambar .= '<div class="col-lg-3"><img src="'.base_url().$row_gambar['gambar'].'" class="img_preview"></div>';

                    if((($baris+1) % 4) == 0) {
                        $list_gambar .= '</div>';
                    }
                }
            } else {
                $list_gambar = '<img src="'.base_url().'assets/images/no_image.png'.'" class="img_preview">';
            }

            $result['varian'] = $list_varian; $result['grosir'] = $list_grosir; $result['gambar'] = $list_gambar; $result['deskripsi'] = nl2br($get_deskripsi['deskripsi']);

            echo json_encode($result);
        } else if ($action == 'edit') {
            $id = $_POST['id'];
            $result = array();

            $get_varian = $this->m_crud->join_data("det_produk dp", "dp.id_det_produk, dp.ukuran, dp.warna, dp.hrg_jual", "produk p", "p.id_produk=dp.produk AND p.code<>dp.code", "p.id_produk='".$id."'");
            $get_grosir = $this->m_crud->read_data("grosir", "qty1, qty2, hrg_jual", "produk='".$id."'");
            $get_produk = $this->m_crud->join_data($table." p", "p.id_produk, p.code, p.kelompok, p.merk, p.nama, p.deskripsi, p.pre_order, p.free_return, dp.hrg_beli, dp.hrg_jual, dp.berat, dp.ukuran, dp.warna", "det_produk dp", "dp.produk=p.id_produk AND dp.code=p.code", "p.id_produk = '".$id."'");

            if ($get_varian != null) {
                $result['status_varian'] = true;
                $result['res_varian'] = $get_varian;
            } else {
                $result['status_varian'] = false;
            }

            if ($get_grosir != null) {
                $result['status_grosir'] = true;
                $result['res_grosir'] = $get_grosir;
            } else {
                $result['status_grosir'] = false;
            }

            if ($get_produk != null) {
                $result['status'] = true;
                $result['res_produk'] = $get_produk[0];
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'hapus') {
            $delete_data = $this->m_crud->delete_data($table, "id_produk = '".$_POST['id']."'");

            if ($delete_data) {
                $status = true;
            } else {
                $status = false;
            }

            echo $status;
        } else if ($action == 'hapus_gambar') {
            $delete_data = $this->m_crud->delete_data("gambar_produk", "id_gambar = '".$_POST['id']."'");

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
    /*End master data produk*/

    /*Start master group*/
    public function group($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'group';
        $table = 'groups';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Produk';
        $data['title'] = 'Group';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = null;

        if(isset($_POST['search'])||isset($_POST['to_excel'])) {
            $this->session->set_userdata('search', array('any' => $_POST['any']));
        }

        $search = $this->session->search['any'];
        if(isset($search)&&$search!=null) {
            ($where == null) ? null : $where .= " AND ";
            $where .= "nama like '%".$search."%'";
        }

        if ($action == 'get_data') {
            $config = array();
            $config["base_url"] = "#";
            //$config["total_rows"] = $this->ajax_pagination_model->count_all();
            $config["total_rows"] = $this->m_crud->count_data($table, "id_groups", $where);
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
            $read_data = $this->m_crud->read_data($table, "*", $where, "nama", null, $config["per_page"], $start);
            $output .= '
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th width="1%" class="text-center">#</th>
                    <th>Nama</th>
                    <th>Gambar</th>
                    <th>Status</th>
                </tr>
            ';
            $no = $start+1;
            if ($read_data != null) {
                foreach ($read_data as $row) {
                    if ($row['status'] == '1') {
                        $status = '<span class="label label-success">Aktif</span>';
                    } else {
                        $status = '<span class="label label-danger">Tidak Aktif</span>';
                    }
                    $output .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Pilihan <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu dropdown-position">
                                <li><a href="#" onclick="edit(\'' . $row['id_groups'] . '\')">Edit</a></li>
                                <li><a href="#" onclick="hapus(\'' . $row['id_groups'] . '\')">Hapus</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['nama'] . '</td>
                        <td><img class="img_profile" src="' . base_url().$row['gambar'] . '"></td>
                        <td>' . $status . '</td>
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
        } else if ($action == 'cek_nama') {
            $where = "nama='".$_POST['nama']."'";

            $_POST['param']=='edit'?$where.=" AND nama<>'".$_POST['nama']."'":null;

            $cek_username = $this->m_crud->get_data($table, "nama", $where);

            if ($cek_username == null) {
                echo 'true';
            } else {
                echo 'false';
            }
        } else if ($action == 'simpan') {
            $row = 'gambar';
            $config['upload_path']          = './assets/images/group';
            $config['allowed_types']        = 'gif|jpg|jpeg|png|svg';
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
                /*if($file[$row]['file_name']!=null){
                    $manipulasi['image_library'] = 'gd2';
                    $manipulasi['source_image'] = $file[$row]['full_path'];
                    $manipulasi['maintain_ratio'] = true;
                    $manipulasi['width']         = 500;
                    //$manipulasi['height']       = 300;
                    $manipulasi['new_image']       = $file[$row]['full_path'];
                    $this->load->library('image_lib', $manipulasi);
                    $this->image_lib->resize();
                }*/
            }
            $this->db->trans_begin();

            if ($_POST['param'] == 'add') {
                $data_groups = array(
                    'nama' => $_POST['nama'],
                    'status' => isset($_POST['status'])?$_POST['status']:'0'
                );
                if($_FILES[$row]['name']!=null){
                    $data_groups['gambar'] = 'assets/images/group/'.$file[$row]['file_name'];
                }

                $this->m_crud->create_data($table, $data_groups);
            } else {
                $id = $_POST['id'];
                $data_groups = array(
                    'nama' => $_POST['nama'],
                    'status' => isset($_POST['status'])?$_POST['status']:'0'
                );
                if($_FILES[$row]['name']!=null){
                    $data_groups['gambar'] = 'assets/images/group/'.$file[$row]['file_name'];
                }
                $this->m_crud->update_data($table, $data_groups, "id_groups='".$id."'");
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo true;
            }
        } else if ($action == 'edit') {
            $get_data = $this->m_crud->get_data($table, "*", "id_groups = '".$_POST['id']."'");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_group'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'hapus') {
            $delete_data = $this->m_crud->delete_data($table, "id_groups = '".$_POST['id']."'");

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
    /*End master group*/

    /*Start master kelompok*/
    public function kelompok($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'kelompok';
        $table = 'kelompok';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Produk';
        $data['title'] = 'Kelompok';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = null;

        if(isset($_POST['search'])||isset($_POST['to_excel'])) {
            $this->session->set_userdata('search', array('any' => $_POST['any']));
        }

        $search = $this->session->search['any'];
        if(isset($search)&&$search!=null) {
            ($where == null) ? null : $where .= " AND ";
            $where .= "k.nama like '%".$search."%'";
        }

        if ($action == 'get_data') {
            $config = array();
            $config["base_url"] = "#";
            $config["total_rows"] = $this->m_crud->count_join_data($table." k", "k.id_kelompok", "groups g", "g.id_groups=k.groups", $where);
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
            $read_data = $this->m_crud->join_data($table." k", "k.*, g.nama nama_group", "groups g", "g.id_groups=k.groups", $where, "nama", null, $config["per_page"], $start);
            $output .= '
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th width="1%" class="text-center">#</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Gambar</th>
                    <th>Group</th>
                    <th>Status</th>
                </tr>
            ';
            $no = $start+1;
            if ($read_data != null) {
                foreach ($read_data as $row) {
                    if ($row['status'] == '1') {
                        $status = '<span class="label label-success">Aktif</span>';
                    } else {
                        $status = '<span class="label label-danger">Tidak Aktif</span>';
                    }
                    $output .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Pilihan <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu dropdown-position">
                                <li><a href="#" onclick="edit(\'' . $row['id_kelompok'] . '\')">Edit</a></li>
                                <li><a href="#" onclick="hapus(\'' . $row['id_kelompok'] . '\')">Hapus</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['code'] . '</td>
                        <td>' . $row['nama'] . '</td>
                        <td><img class="img_profile" src="' . base_url().$row['gambar'] . '"></td>
                        <td>' . $row['nama_group'] . '</td>
                        <td>' . $status . '</td>
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
        } else if ($action == 'cek_nama') {
            $cek_username = null;

            if ($_POST['nama']!='' && $_POST['group']!='') {
                $where = "nama='" . $_POST['nama'] . "' AND groups='" . $_POST['group'] . "'";

                $_POST['param'] == 'edit' ? $where .= " AND nama<>'" . $_POST['nama'] . "' AND groups='" . $_POST['group'] . "'" : null;

                $cek_username = $this->m_crud->get_data($table, "nama", $where);
            }

            if ($cek_username == null) {
                echo 'true';
            } else {
                echo 'false';
            }
        } else if ($action == 'simpan') {
            $row = 'gambar';
            $config['upload_path']          = './assets/images/kelompok';
            $config['allowed_types']        = 'gif|jpg|jpeg|png';
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
                /*if($file[$row]['file_name']!=null){
                    $manipulasi['image_library'] = 'gd2';
                    $manipulasi['source_image'] = $file[$row]['full_path'];
                    $manipulasi['maintain_ratio'] = true;
                    $manipulasi['width']         = 500;
                    //$manipulasi['height']       = 300;
                    $manipulasi['new_image']       = $file[$row]['full_path'];
                    $this->load->library('image_lib', $manipulasi);
                    $this->image_lib->resize();
                }*/
            }
            $this->db->trans_begin();

            if ($_POST['param'] == 'add') {
                $code = $this->m_website->generate_kode('kelompok', strtoupper(substr(str_replace(' ', '', $_POST['nama']), 0, 2)));
                $data_kelompok = array(
                    'code' => $code,
                    'nama' => $_POST['nama'],
                    'groups' => $_POST['group'],
                    'status' => isset($_POST['status'])?$_POST['status']:'0'
                );
                if($_FILES[$row]['name']!=null){
                    $data_kelompok['gambar'] = 'assets/images/kelompok/'.$file[$row]['file_name'];
                }

                $this->m_crud->create_data($table, $data_kelompok);
            } else {
                $id = $_POST['id'];
                $data_kelompok = array(
                    'nama' => $_POST['nama'],
                    'groups' => $_POST['group'],
                    'status' => isset($_POST['status'])?$_POST['status']:'0'
                );
                if($_FILES[$row]['name']!=null){
                    $data_kelompok['gambar'] = 'assets/images/kelompok/'.$file[$row]['file_name'];
                }
                $this->m_crud->update_data($table, $data_kelompok, "id_kelompok='".$id."'");
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo true;
            }
        } else if ($action == 'get_group') {
            $result = array();
            $list = '<option value="">Pilih Group</option>';
            $read_group = $this->m_crud->read_data("groups", "*", "status='1'");

            if ($read_group != null) {
                $result['status'] = true;
                foreach ($read_group as $row) {
                    $list .= '<option value="'.$row['id_groups'].'">'.$row['nama'].'</option>';
                }
                $result['group'] = $list;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'edit') {
            $get_data = $this->m_crud->get_data($table, "*", "id_kelompok = '".$_POST['id']."'");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_kelompok'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'hapus') {
            $delete_data = $this->m_crud->delete_data($table, "id_kelompok = '".$_POST['id']."'");

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
    /*End master kelompok*/

    /*Start master merk*/
    public function merk($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'merk';
        $table = 'merk';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Produk';
        $data['title'] = 'Merk';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = null;

        if(isset($_POST['search'])||isset($_POST['to_excel'])) {
            $this->session->set_userdata('search', array('any' => $_POST['any']));
        }

        $search = $this->session->search['any'];
        if(isset($search)&&$search!=null) {
            ($where == null) ? null : $where .= " AND ";
            $where .= "nama like '%".$search."%'";
        }

        if ($action == 'get_data') {
            $config = array();
            $config["base_url"] = "#";
            //$config["total_rows"] = $this->ajax_pagination_model->count_all();
            $config["total_rows"] = $this->m_crud->count_data($table, "id_merk", $where);
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
            $read_data = $this->m_crud->read_data($table, "*", $where, "nama", null, $config["per_page"], $start);
            $output .= '
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th width="1%" class="text-center">#</th>
                    <th>Nama</th>
                    <th>Gambar</th>
                    <th>Status</th>
                </tr>
            ';
            $no = $start+1;
            if ($read_data != null) {
                foreach ($read_data as $row) {
                    if ($row['status'] == '1') {
                        $status = '<span class="label label-success">Aktif</span>';
                    } else {
                        $status = '<span class="label label-danger">Tidak Aktif</span>';
                    }
                    $output .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Pilihan <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu dropdown-position">
                                <li><a href="#" onclick="edit(\'' . $row['id_merk'] . '\')">Edit</a></li>
                                <li><a href="#" onclick="hapus(\'' . $row['id_merk'] . '\')">Hapus</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['nama'] . '</td>
                        <td><img class="img_profile" src="' . base_url().$row['gambar'] . '"></td>
                        <td>' . $status . '</td>
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
        } else if ($action == 'cek_nama') {
            $where = "nama='".$_POST['nama']."'";

            $_POST['param']=='edit'?$where.=" AND nama<>'".$_POST['nama']."'":null;

            $cek_username = $this->m_crud->get_data($table, "nama", $where);

            if ($cek_username == null) {
                echo 'true';
            } else {
                echo 'false';
            }
        } else if ($action == 'simpan') {
            $row = 'gambar';
            $config['upload_path']          = './assets/images/merk';
            $config['allowed_types']        = 'gif|jpg|jpeg|png';
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
                /*if($file[$row]['file_name']!=null){
                    $manipulasi['image_library'] = 'gd2';
                    $manipulasi['source_image'] = $file[$row]['full_path'];
                    $manipulasi['maintain_ratio'] = true;
                    $manipulasi['width']         = 500;
                    //$manipulasi['height']       = 300;
                    $manipulasi['new_image']       = $file[$row]['full_path'];
                    $this->load->library('image_lib', $manipulasi);
                    $this->image_lib->resize();
                }*/
            }
            $this->db->trans_begin();

            if ($_POST['param'] == 'add') {
                $data_groups = array(
                    'nama' => $_POST['nama'],
                    'status' => isset($_POST['status'])?$_POST['status']:'0'
                );
                if($_FILES[$row]['name']!=null){
                    $data_groups['gambar'] = 'assets/images/merk/'.$file[$row]['file_name'];
                }

                $this->m_crud->create_data($table, $data_groups);
            } else {
                $id = $_POST['id'];
                $data_groups = array(
                    'nama' => $_POST['nama'],
                    'status' => isset($_POST['status'])?$_POST['status']:'0'
                );
                if($_FILES[$row]['name']!=null){
                    $data_groups['gambar'] = 'assets/images/merk/'.$file[$row]['file_name'];
                }
                $this->m_crud->update_data($table, $data_groups, "id_merk='".$id."'");
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo true;
            }
        } else if ($action == 'edit') {
            $get_data = $this->m_crud->get_data($table, "*", "id_merk = '".$_POST['id']."'");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_merk'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'hapus') {
            $delete_data = $this->m_crud->delete_data($table, "id_merk = '".$_POST['id']."'");

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
    /*End master merk*/

    /*Start master promo*/
    public function promo($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'promo';
        $table = 'promo';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Produk';
        $data['title'] = 'Promo';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = "'".date('Y-m-d H:i:s')."' <= tgl_akhir";

        if(isset($_POST['search'])||isset($_POST['to_excel'])) {
            $this->session->set_userdata('search', array('any' => $_POST['any']));
        }

        $search = $this->session->search['any'];
        if(isset($search)&&$search!=null) {
            ($where == null) ? null : $where .= " AND ";
            $where .= "promo like '%".$search."%'";
        }

        if ($action == 'get_data') {
            $config = array();
            $config["base_url"] = "#";
            //$config["total_rows"] = $this->ajax_pagination_model->count_all();
            $config["total_rows"] = $this->m_crud->count_data($table, "id_promo", $where);
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
            $read_data = $this->m_crud->read_data($table, "*", $where, "promo", null, $config["per_page"], $start);

            $output .= '
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th width="1%" class="text-center">#</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Periode</th>
                    <th>Gambar</th>
                    <th>Diskon %</th>
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
                                <li><a href="#" onclick="detail(\'' . $row['id_promo'] . '\')">Detail</a></li>
                                <li><a href="#" onclick="edit(\'' . $row['id_promo'] . '\')">Edit</a></li>
                                <li><a href="#" onclick="hapus(\'' . $row['id_promo'] . '\')">Hapus</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['promo'] . '</td>
                        <td>' . substr($row['deskripsi'], 0, 100) . '</td>
                        <td>' . date('Y-m-d h:i A', strtotime($row['tgl_awal'])) . ' - ' . date('Y-m-d h:i A', strtotime($row['tgl_akhir'])) . '</td>
                        <td><img class="img_profile" src="' . base_url().$row['gambar'] . '"></td>
                        <td>' . implode(' + ', json_decode($row['diskon'], true)) . '</td>
                    </tr>
                ';
                }
            } else {
                $output .= '
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data</td>
                </tr>
                ';
            }
            $output .= '</table>';

            $result = array(
                'pagination_link' => $this->pagination->create_links(),
                'result_table' => $output
            );
            echo json_encode($result);
        } else if ($action == 'get_produk') {
            $get_produk = $this->m_crud->join_data("produk p", "CONCAT(p.code, ' | ', p.nama) value, p.id_produk, p.nama, p.code, dp.berat, dp.hrg_jual", "det_produk dp", "dp.produk=p.id_produk AND dp.code=p.code", "p.nama like '%".$_POST['query']."%' OR p.code like '%".$_POST['query']."%'");

            if ($get_produk != null) {
                $result = $get_produk;
            } else {
                $result = array(array('id_produk'=>'not_found', 'value'=>'Produk Tidak Tersedia!'));
            }

            echo json_encode(array("suggestions"=>$result));
        } else if ($action == 'detail') {
            $get_data = $this->m_crud->get_data($table, "*", "id_promo = '".$_POST['id']."'");
            $result = array();
            $list_promo = '';
            $read_data = $this->m_crud->join_data("det_promo dp", "p.id_produk, p.nama, p.code, dpr.berat, dpr.hrg_jual", array("produk p", "det_produk dpr"), array("p.id_produk=dp.produk", "dpr.produk=p.id_produk"), "dp.promo='".$_POST['id']."'", null, "dp.produk");
            foreach ($read_data as $row) {
                $list_promo .= '
                <tr>
                <td>'.$row['code'].'</td>
                <td>'.$row['nama'].'</td>
                <td>'.$row['berat'].'</td>
                <td>'.number_format($row['hrg_jual'], 2).'</td>
                </tr>
                ';
            }

            if ($get_data != null) {
                $result['status'] = true;
                $periode = date('Y-m-d h:i A', strtotime($get_data['tgl_awal'])).' - '.date('Y-m-d h:i A', strtotime($get_data['tgl_akhir']));
                $array_master = array('id_promo'=>$get_data['id_promo'], 'promo'=>$get_data['promo'], 'deskripsi'=>$get_data['deskripsi'], 'periode'=>$periode, 'gambar'=>$get_data['gambar'], 'diskon'=>implode('+', json_decode($get_data['diskon'], true)));
                $result['res_promo'] = $array_master;
                $result['det_promo'] = $list_promo;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'simpan') {
            $row = 'gambar';
            $config['upload_path']          = './assets/images/promo';
            $config['allowed_types']        = 'gif|jpg|jpeg|png';
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
                /*if($file[$row]['file_name']!=null){
                    $manipulasi['image_library'] = 'gd2';
                    $manipulasi['source_image'] = $file[$row]['full_path'];
                    $manipulasi['maintain_ratio'] = true;
                    $manipulasi['width']         = 500;
                    //$manipulasi['height']       = 300;
                    $manipulasi['new_image']       = $file[$row]['full_path'];
                    $this->load->library('image_lib', $manipulasi);
                    $this->image_lib->resize();
                }*/
            }

            $this->db->trans_begin();
            $explode = explode(' - ', $_POST['periode']);
            $tgl_awal = date('Y-m-d H:i:s', strtotime($explode[0]));
            $tgl_akhir = date('Y-m-d H:i:s', strtotime($explode[1]));
            $explode_diskon = explode('+', $_POST['diskon']);
            $diskon = array();
            foreach ($explode_diskon as $row) {
                if (is_numeric($row) && $row != 0) {
                    array_push($diskon, (float)$row);
                }
            }

            if ($_POST['param'] == 'add') {
                $data_promo = array(
                    'promo' => $_POST['promo'],
                    'deskripsi' => $_POST['deskripsi'],
                    'tgl_awal' => $tgl_awal,
                    'tgl_akhir' => $tgl_akhir,
                    'diskon' => json_encode($diskon),
                    'slug_promo'=>slug($_POST['promo'])
                );
                if($_FILES['gambar']['name']!=null){
                    $data_promo['gambar'] = 'assets/images/promo/'.$file['gambar']['file_name'];
                }
                $this->m_crud->create_data($table, $data_promo);
                $id = $this->db->insert_id();

                foreach ($_POST['id_produk'] as $row) {
                    $this->m_crud->create_data("det_promo", array('promo'=>$id,'produk'=>$row,'slug_promo'=>slug($_POST['promo'])));
                }
            } else {
                $id = $_POST['id'];
                $data_promo = array(
                    'promo' => $_POST['promo'],
                    'deskripsi' => $_POST['deskripsi'],
                    'tgl_awal' => $tgl_awal,
                    'tgl_akhir' => $tgl_akhir,
                    'diskon' => json_encode($diskon),
                    'slug_promo'=>slug($_POST['promo'])

                );
                if($_FILES['gambar']['name']!=null){
                    $data_promo['gambar'] = 'assets/images/promo/'.$file['gambar']['file_name'];
                }
                $this->m_crud->update_data($table, $data_promo, "id_promo='".$id."'");

                $this->m_crud->delete_data("det_promo", "promo='".$id."'");
                foreach ($_POST['id_produk'] as $row) {
//                    $this->m_crud->create_data("det_promo", array('promo'=>$id,'produk'=>$row));
                    $this->m_crud->create_data("det_promo", array('promo'=>$id,'produk'=>$row,'slug_promo'=>slug($_POST['promo'])));
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
            $get_data = $this->m_crud->get_data($table, "*", "id_promo = '".$_POST['id']."'");
            $result = array();
            $read_data = $this->m_crud->join_data("det_promo dp", "p.id_produk, p.nama, p.code, dpr.berat, dpr.hrg_jual", array("produk p", "det_produk dpr"), array("p.id_produk=dp.produk", "dpr.produk=p.id_produk"), "dp.promo='".$_POST['id']."'", null, "dp.produk");

            if ($get_data != null) {
                $result['status'] = true;
                $periode = $get_data['tgl_awal'].' - '.$get_data['tgl_akhir'];
                $array_master = array('id_promo'=>$get_data['id_promo'], 'promo'=>$get_data['promo'], 'periode'=>$periode, 'gambar'=>$get_data['gambar'], 'diskon'=>implode('+', json_decode($get_data['diskon'], true)));
                $result['res_promo'] = $array_master;
                $result['det_promo'] = $read_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'hapus') {
            $delete_data = $this->m_crud->delete_data($table, "id_promo = '".$_POST['id']."'");

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
    /*End master promo*/

    /*Start master bestsellers*/
    public function bestsellers($action=null, $page=1) {
        //$this->access_denied(40);
        $data = $this->data;
        $function = 'bestsellers';
        $table = 'bestsellers';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Produk';
        $data['title'] = 'Bestsellers';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = null;

        if(isset($_POST['search'])||isset($_POST['to_excel'])) {
            $this->session->set_userdata('search', array('any' => $_POST['any']));
        }

        $search = $this->session->search['any'];
        if(isset($search)&&$search!=null) {
            ($where == null) ? null : $where .= " AND ";
            $where .= "(p.nama like '%".$search."%' OR p.code like '%".$search."%')";
        }

        if ($action == 'get_data') {
            $config = array();
            $config["base_url"] = "#";
            //$config["total_rows"] = $this->ajax_pagination_model->count_all();
            $config["total_rows"] = $this->m_crud->count_join_data($table." b", "b.id_bestsellers", array("produk p", "det_produk dp"), array("p.id_produk=b.produk", "dp.produk=p.id_produk AND dp.code=p.code"), $where);
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
            $read_data = $this->m_crud->join_data($table." b", "b.id_bestsellers, p.id_produk, p.nama, p.deskripsi, p.code, dp.berat, ifnull(dp.hrg_beli, 0) hrg_beli, ifnull(dp.hrg_jual, 0) hrg_jual", array("produk p", "det_produk dp"), array("p.id_produk=b.produk", "dp.produk=p.id_produk AND dp.code=p.code"), $where, "b.id_bestsellers", null, $config["per_page"], $start);
            $output .= '
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th width="1%" class="text-center">#</th>
                    <th>SKU</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Berat</th>
                    <th>Harga Jual</th>
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
                                <li><a href="#" onclick="hapus(\'' . $row['id_bestsellers'] . '\')">Hapus</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['code'] . '</td>
                        <td>' . (strlen($row['nama'])>30?substr($row['nama'], 0, 30).'...':$row['nama']) . '</td>
                        <td>' . (strlen($row['deskripsi'])>=30?mb_substr($row['deskripsi'], 0, 30).'...':$row['deskripsi']) . '</td>
                        <td>' . $row['berat'] . ' gr</td>
                        <td class="text-right">' . number_format($row['hrg_jual']) . '</td>
                    </tr>
                ';
                }
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
        } else if ($action == 'get_produk') {
            $get_produk = $this->m_crud->join_data("produk p", "CONCAT(p.code, ' | ', p.nama) value, p.id_produk, p.nama, p.code, dp.berat, dp.hrg_jual", "det_produk dp", "dp.produk=p.id_produk AND dp.code=p.code", "(p.nama like '%".$_POST['query']."%' OR p.code like '%".$_POST['query']."%') AND p.id_produk NOT IN (SELECT produk FROM bestsellers)");

            if ($get_produk != null) {
                $result = $get_produk;
            } else {
                $result = array(array('id_produk'=>'not_found', 'value'=>'Produk Tidak Tersedia!'));
            }

            echo json_encode(array("suggestions"=>$result));
        } else if ($action == 'simpan') {
            $this->db->trans_begin();

            foreach ($_POST['id_produk'] as $row) {
                $this->m_crud->create_data("bestsellers", array('produk'=>$row));
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo true;
            }
        } else if ($action == 'hapus') {
            $delete_data = $this->m_crud->delete_data($table, "id_bestsellers = '".$_POST['id']."'");

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
    /*End master bestsellers*/

    /*Start master voucher*/
    public function voucher($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'voucher';
        $table = 'voucher';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Produk';
        $data['title'] = 'Voucher';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = "'".date('Y-m-d H:i:s')."' <= tgl_selesai";

        if(isset($_POST['search'])||isset($_POST['to_excel'])) {
            $this->session->set_userdata('search', array('any' => $_POST['any']));
        }

        $search = $this->session->search['any'];
        if(isset($search)&&$search!=null) {
            ($where == null) ? null : $where .= " AND ";
            $where .= "nama like '%".$search."%'";
        }

        if ($action == 'get_data') {
            $config = array();
            $config["base_url"] = "#";
            //$config["total_rows"] = $this->ajax_pagination_model->count_all();
            $config["total_rows"] = $this->m_crud->count_data($table, "id_voucher", $where);
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
            $read_data = $this->m_crud->read_data($table, "*", $where, "nama", null, $config["per_page"], $start);
            $output .= '
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th width="1%" class="text-center">#</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Periode</th>
                    <th>Gambar</th>
                    <th>Quota</th>
                    <th>Min Orders</th>
                    <th>Jenis</th>
                    <th>Potongan</th>
                    <th>Status</th>
                </tr>
            ';
            $no = $start+1;
            if ($read_data != null) {
                foreach ($read_data as $row) {
                    if ($row['status'] == '1') {
                        $status = '<span class="label label-success">Aktif</span>';
                    } else {
                        $status = '<span class="label label-danger">Tidak Aktif</span>';
                    }

                    $output .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Pilihan <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu dropdown-position">
                                <li><a href="#" onclick="edit(\'' . $row['id_voucher'] . '\')">Edit</a></li>
                                <li><a href="#" onclick="hapus(\'' . $row['id_voucher'] . '\')">Hapus</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['nama'] . '</td>
                        <td>' . substr($row['deskripsi'], 0, 100) . '</td>
                        <td>' . date('Y-m-d h:i A', strtotime($row['tgl_mulai'])) . ' - ' . date('Y-m-d h:i A', strtotime($row['tgl_selesai'])) . '</td>
                        <td><img class="img_profile" src="' . base_url().$row['gambar'] . '"></td>
                        <td>' . (int)$row['quota'] . '</td>
                        <td>' . number_format($row['min_orders']) . '</td>
                        <td>' . ucfirst($row['jenis']) . '</td>
                        <td>' . ($row['jenis']=='nominal'?number_format($row['value']):$row['value']) . '</td>
                        <td>' . $status . '</td>
                    </tr>
                ';
                }
            } else {
                $output .= '
                <tr>
                    <td colspan="11" class="text-center">Tidak ada data</td>
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
        else if ($action == 'simpan') {
            $row = 'gambar';
            $path = '/assets/images/voucher';
            $config['upload_path']          = '.'.$path;
            $config['allowed_types']        = 'gif|jpg|jpeg|png';
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
                /*if($file[$row]['file_name']!=null){
                    $manipulasi['image_library'] = 'gd2';
                    $manipulasi['source_image'] = $file[$row]['full_path'];
                    $manipulasi['maintain_ratio'] = true;
                    $manipulasi['width']         = 500;
                    //$manipulasi['height']       = 300;
                    $manipulasi['new_image']       = $file[$row]['full_path'];
                    $this->load->library('image_lib', $manipulasi);
                    $this->image_lib->resize();
                }*/
            }

            $this->db->trans_begin();
            $explode = explode(' - ', $_POST['periode']);
            $tgl_awal = date('Y-m-d H:i:s', strtotime($explode[0]));
            $tgl_akhir = date('Y-m-d H:i:s', strtotime($explode[1]));

            $jenis = $_POST['jenis'];
            if ($jenis == 'nominal') {
                $value = str_replace(',', '', $_POST['nominal']);
            } else {
                $value = $_POST['persen'];
            }

            $data_promo = array(
            	'kode'=>$this->m_website->kodeVoucher(),
                'nama' => $_POST['nama'],
                'deskripsi' => $_POST['deskripsi'],
                'jenis' => $jenis,
                'value' => $value,
                'tgl_mulai' => $tgl_awal,
                'tgl_selesai' => $tgl_akhir,
                'quota' => $_POST['quota'],
                'min_orders' => str_replace(',', '', $_POST['min_orders']),
                'status' => $_POST['status']
            );
            if($_FILES['gambar']['name']!=null){
                $data_promo['gambar'] = $path.'/'.$file['gambar']['file_name'];
            }

            if ($_POST['param'] == 'add') {
                $this->m_crud->create_data($table, $data_promo);
            } else {
                $id = $_POST['id'];
                $this->m_crud->update_data($table, $data_promo, "id_voucher='".$id."'");
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo true;
            }
        } else if ($action == 'edit') {
            $get_data = $this->m_crud->get_data($table, "*", "id_voucher = '".$_POST['id']."'");

            if ($get_data != null) {
                $result['status'] = true;
                $periode = $get_data['tgl_mulai'].' - '.$get_data['tgl_selesai'];
                $get_data['periode'] = $periode;
                $result['res_data'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'hapus') {
            $delete_data = $this->m_crud->delete_data($table, "id_voucher = '".$_POST['id']."'");

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
    /*End master voucher*/

    /*Start master model*/
    public function model($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'model';
        $table = 'model';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Produk';
        $data['title'] = 'Model';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = null;

        if(isset($_POST['search'])||isset($_POST['to_excel'])) {
            $this->session->set_userdata('search', array('any' => $_POST['any']));
        }

        $search = $this->session->search['any'];
        if(isset($search)&&$search!=null) {
            ($where == null) ? null : $where .= " AND ";
            $where .= "nama like '%".$search."%'";
        }

        if ($action == 'get_data') {
            $config = array();
            $config["base_url"] = "#";
            //$config["total_rows"] = $this->ajax_pagination_model->count_all();
            $config["total_rows"] = $this->m_crud->count_data($table, "id_model", $where);
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
            $read_data = $this->m_crud->read_data($table, "*", $where, "nama", null, $config["per_page"], $start);
            $output .= '
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th width="1%" class="text-center">#</th>
                    <th>Nama</th>
                    <th>Gambar</th>
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
                                <li><a href="#" onclick="detail(\'' . $row['id_model'] . '\')">Detail</a></li>
                                <li><a href="#" onclick="edit(\'' . $row['id_model'] . '\')">Edit</a></li>
                                <li><a href="#" onclick="hapus(\'' . $row['id_model'] . '\')">Hapus</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['nama'] . '</td>
                        <td><img class="img_profile" src="' . base_url().$row['gambar'] . '"></td>
                    </tr>
                ';
                }
            } else {
                $output .= '
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
                ';
            }
            $output .= '</table>';

            $result = array(
                'pagination_link' => $this->pagination->create_links(),
                'result_table' => $output
            );
            echo json_encode($result);
        } else if ($action == 'get_produk') {
            $get_produk = $this->m_crud->join_data("produk p", "CONCAT(p.code, ' | ', p.nama) value, p.id_produk, p.nama, p.code, dp.berat, dp.hrg_jual", "det_produk dp", "dp.produk=p.id_produk AND dp.code=p.code", "p.nama like '%".$_POST['query']."%' OR p.code like '%".$_POST['query']."%'");

            if ($get_produk != null) {
                $result = $get_produk;
            } else {
                $result = array(array('id_produk'=>'not_found', 'value'=>'Produk Tidak Tersedia!'));
            }

            echo json_encode(array("suggestions"=>$result));
        } else if ($action == 'detail') {
            $get_data = $this->m_crud->get_data($table, "*", "id_model = '".$_POST['id']."'");
            $result = array();
            $list_model = '';
            $read_data = $this->m_crud->join_data("det_model dp", "p.id_produk, p.nama, p.code, dpr.berat, dpr.hrg_jual", array("produk p", "det_produk dpr"), array("p.id_produk=dp.produk", "dpr.produk=p.id_produk"), "dp.model='".$_POST['id']."'", null, "dp.produk");
            foreach ($read_data as $row) {
                $list_model .= '
                <tr>
                <td>'.$row['code'].'</td>
                <td>'.$row['nama'].'</td>
                <td>'.$row['berat'].'</td>
                <td>'.number_format($row['hrg_jual'], 2).'</td>
                </tr>
                ';
            }

            if ($get_data != null) {
                $result['status'] = true;
                $array_master = array('id_model'=>$get_data['id_model'], 'nama'=>$get_data['nama'], 'gambar'=>$get_data['gambar']);
                $result['res_model'] = $array_master;
                $result['det_model'] = $list_model;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'simpan') {
            $row = 'gambar';
            $config['upload_path']          = './assets/images/produk';
            $config['allowed_types']        = 'gif|jpg|jpeg|png';
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
                /*if($file[$row]['file_name']!=null){
                    $manipulasi['image_library'] = 'gd2';
                    $manipulasi['source_image'] = $file[$row]['full_path'];
                    $manipulasi['maintain_ratio'] = true;
                    $manipulasi['width']         = 500;
                    //$manipulasi['height']       = 300;
                    $manipulasi['new_image']       = $file[$row]['full_path'];
                    $this->load->library('image_lib', $manipulasi);
                    $this->image_lib->resize();
                }*/
            }

            $this->db->trans_begin();

            if ($_POST['param'] == 'add') {
                $data_model = array(
                    'nama' => $_POST['nama']
                );
                if($_FILES['gambar']['name']!=null){
                    $data_model['gambar'] = "assets/images/produk/".$file['gambar']['file_name'];
                }
                $this->m_crud->create_data($table, $data_model);
                $id = $this->db->insert_id();

                foreach ($_POST['id_produk'] as $row) {
                    $this->m_crud->create_data("det_model", array('model'=>$id,'produk'=>$row));
                }
            } else {
                $id = $_POST['id'];
                $data_model = array(
                    'nama' => $_POST['nama']
                );
                if($_FILES['gambar']['name']!=null){
                    $data_model['gambar'] = "assets/images/produk/".$file['gambar']['file_name'];
                }
                $this->m_crud->update_data($table, $data_model, "id_model='".$id."'");

                $this->m_crud->delete_data("det_model", "model='".$id."'");
                foreach ($_POST['id_produk'] as $row) {
                    $this->m_crud->create_data("det_model", array('model'=>$id,'produk'=>$row));
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
            $get_data = $this->m_crud->get_data($table, "*", "id_model = '".$_POST['id']."'");
            $result = array();
            $read_data = $this->m_crud->join_data("det_model dp", "p.id_produk, p.nama, p.code, dpr.berat, dpr.hrg_jual", array("produk p", "det_produk dpr"), array("p.id_produk=dp.produk", "dpr.produk=p.id_produk"), "dp.model='".$_POST['id']."'", null, "dp.produk");

            if ($get_data != null) {
                $result['status'] = true;
                $array_master = array('id_model'=>$get_data['id_model'], 'nama'=>$get_data['nama'], 'gambar'=>$get_data['gambar']);
                $result['res_model'] = $array_master;
                $result['det_model'] = $read_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'hapus') {
            $delete_data = $this->m_crud->delete_data($table, "id_model = '".$_POST['id']."'");

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
    /*End master model*/

    /*Start diskusi*/
    public function diskusi($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'diskusi';
        $table = 'diskusi_produk';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Produk';
        $data['title'] = 'Diskusi Produk';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = "dp.response IS NULL";

        if(isset($_POST['search'])||isset($_POST['to_excel'])) {
            $this->session->set_userdata('search', array('any' => $_POST['any']));
        }

        $search = $this->session->search['any'];
        if(isset($search)&&$search!=null) {
            ($where == null) ? null : $where .= " AND ";
            $where .= "p.nama like '%".$search."%'";
        }

        if ($action == 'get_data') {
            $config = array();
            $config["base_url"] = "#";
            //$config["total_rows"] = $this->ajax_pagination_model->count_all();
            $config["total_rows"] = $this->m_crud->count_data_join($table." dp", "dp.produk", "produk p", "p.id_produk=dp.produk", $where, null, "produk");
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

            $read_data = $this->m_crud->join_data($table." dp", "dp.id_diskusi_produk, p.id_produk, p.nama", "produk p", "p.id_produk=dp.produk", $where, "dp.tgl_diskusi DESC", "p.id_produk", $config["per_page"], $start);

            $output = '';
            if ($read_data != null) {
                foreach ($read_data as $row) {
                    $get_gambar = base_url() . $this->m_crud->get_data("gambar_produk", "IFNULL(gambar, 'assets/images/no_image.png') gambar", "produk='" . $row['id_produk'] . "'")['gambar'];

                    $get_diskusi = $this->m_crud->join_data("diskusi_produk dp", "dp.id_diskusi_produk, dp.tgl_diskusi, dp.comment, dp.status, m.id_member, m.nama, m.foto", "member m", "m.id_member=dp.member", "dp.produk='".$row['id_produk']."' AND dp.response IS NULL", "dp.tgl_diskusi");
                    $diskusi = '';
                    $ts = '';
                    $comment = '';
                    foreach ($get_diskusi as $baris => $row_diskusi) {
                        if ($baris == 0) {
                            $id_comment = $row_diskusi['id_diskusi_produk'];
                            $diskusi .= '
                                <div class="direct-chat-msg ts">
                                    <div class="direct-chat-info clearfix" id="cont_status'.str_replace('/', '_', $row_diskusi['id_diskusi_produk']).'">
                                        <span style="color: '.($row_diskusi['status']=='0'?'red':'green').'" title="'.($row_diskusi['status']=='1'?'Sembunyikan':'Tampilkan').'" onclick="change_status(\''.$row_diskusi['id_diskusi_produk'].'\', \''.$row_diskusi['status'].'\')" class="pull-right"><i class="fa '.($row_diskusi['status']=='0'?'fa-times':'fa-check').'"></i></span>
                                    </div>
                                    <div class="direct-chat-info clearfix">
                                        <span class="direct-chat-name pull-left">'.$row_diskusi['nama'].'</span>
                                        <span class="direct-chat-timestamp pull-right">'.date('d M Y h:i A', strtotime($row_diskusi['tgl_diskusi'])).'</span>
                                    </div>
                                    <img class="direct-chat-img" src="'.base_url().$row_diskusi['foto'].'" alt="Message User Image">
                                    <div class="direct-chat-text">
                                        '.$row_diskusi['comment'].'
                                    </div>
                                </div>
                            ';
                            $get_comment = $this->m_crud->join_data("diskusi_produk dp", "dp.id_diskusi_produk, dp.tgl_diskusi, dp.comment, dp.status, m.id_member, IFNULL(m.nama, 'Admin Indokids') nama, IFNULL(m.nama, 'admin_idk') verify, IFNULL(m.foto, 'assets/images/member/admin.png') foto", array(array('table'=>'member m', 'type'=>'LEFT')), array("m.id_member=dp.member"), "dp.response = '".$row_diskusi['id_diskusi_produk']."'");
                            $comment = '';
                            foreach ($get_comment as $row_comment) {
                                if ($row_comment['verify'] == 'admin_idk') {
                                    $comment .= '
                                    <div class="direct-chat-msg right">
                                        <div class="direct-chat-info clearfix" id="cont_status'.str_replace('/', '_', $row_comment['id_diskusi_produk']).'">
                                            <span style="color: '.($row_comment['status']=='0'?'red':'green').'" title="'.($row_comment['status']=='1'?'Sembunyikan':'Tampilkan').'" onclick="change_status(\''.$row_comment['id_diskusi_produk'].'\', \''.$row_comment['status'].'\')" class="pull-right"><i class="fa '.($row_comment['status']=='0'?'fa-times':'fa-check').'"></i></span>
                                        </div>
                                        <div class="direct-chat-info clearfix">
                                            <span class="direct-chat-name pull-right">' . $row_comment['nama'] . '</span>
                                            <span class="direct-chat-timestamp pull-left">'.date('d M Y h:i A', strtotime($row_comment['tgl_diskusi'])).'</span>
                                        </div>
                                        <img class="direct-chat-img" src="' . base_url() . $row_comment['foto'] . '" alt="Message User Image">
                                        <div class="direct-chat-text">
                                            ' . $row_comment['comment'] . '
                                        </div>
                                    </div>
                                    ';
                                } else {
                                    $comment .= '
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-info clearfix" id="cont_status'.str_replace('/', '_', $row_comment['id_diskusi_produk']).'">
                                            <span style="color: '.($row_comment['status']=='0'?'red':'green').'" title="'.($row_comment['status']=='1'?'Sembunyikan':'Tampilkan').'" onclick="change_status(\''.$row_comment['id_diskusi_produk'].'\', \''.$row_comment['status'].'\')" class="pull-right"><i class="fa '.($row_comment['status']=='0'?'fa-times':'fa-check').'"></i></span>
                                        </div>
                                        <div class="direct-chat-info clearfix">
                                            <span class="direct-chat-name pull-left">' . $row_comment['nama'] . '</span>
                                            <span class="direct-chat-timestamp pull-right">'.date('d M Y h:i A', strtotime($row_comment['tgl_diskusi'])).'</span>
                                        </div>
                                        <img class="direct-chat-img" src="' . base_url() . $row_comment['foto'] . '" alt="Message User Image">
                                        <div class="direct-chat-text">
                                            ' . $row_comment['comment'] . '
                                        </div>
                                    </div>
                                ';
                                }
                            }
                        }
                        $ts .= '
                        <li>
                            <a href="javascript:" onclick="load_diskusi(\''.$row['id_produk'].'\', \''.$row_diskusi['id_diskusi_produk'].'\')" data-widget="chat-pane-toggle">
                                <img class="contacts-list-img" src="' . base_url() . $row_diskusi['foto'] . '" alt="User Image">
                                <div class="contacts-list-info">
                                    <span class="contacts-list-name">
                                      '.$row_diskusi['nama'].'
                                      <small class="contacts-list-date pull-right">'.date('d/m/Y', strtotime($row_diskusi['tgl_diskusi'])).'</small>
                                    </span>
                                    <span class="contacts-list-msg">'.$row_diskusi['comment'].'</span>
                                </div>
                            </a>
                        </li>
                        ';
                    }
                    $output .= '
                    <div class="row">
                        <div class="col-md-10">
                            <div class="box box-primary direct-chat direct-chat-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">'.$row['nama'].'</h3>
                
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="chat-pane-toggle">
                                            <i class="fa fa-comments"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="direct-chat-messages" id="cont_diskusi'.$row['id_produk'].'">
                                        '.$diskusi.'
                                        <div id="cont_comment'.$row['id_produk'].'">
                                        '.$comment.'
                                        </div>
                                    </div>
                                    <div class="direct-chat-contacts">
                                        <div class="overlay" id="load_" style="display: none">
                                            <i style="color: white" class="fa fa-refresh fa-spin"></i>
                                        </div>
                                        <ul class="contacts-list">
                                            '.$ts.'
                                        </ul>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <form id="form_comment'.$row['id_produk'].'">
                                        <input type="hidden" id="id_diskusi'.$row['id_produk'].'" name="id_comment" value="'.$id_comment.'">
                                        <input type="hidden" id="id_produk'.$row['id_produk'].'" name="produk" value="'.$row['id_produk'].'">
                                        <input type="text" name="komentar" onkeydown="if (event.keyCode == 13) comment(\''.$row['id_produk'].'\')" placeholder="Balas diskusi disini" class="form-control">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    ';
                }
            } else {
                $output = '
                    <div class="box"><h3 style="padding: 10px" class="text-center">Tidak ada data</h3></div>
                ';
            }

            $result = array(
                'result_data' => $output
            );
            echo json_encode($result);
        } else if ($action == 'get_diskusi') {
            $result = array();

            $id_diskusi = $_POST['id_diskusi'];
            $id_produk = $_POST['id_produk'];

            $get_diskusi = $this->m_crud->join_data("diskusi_produk dp", "dp.id_diskusi_produk, dp.tgl_diskusi, dp.comment, dp.status, m.id_member, m.nama, m.foto", "member m", "m.id_member=dp.member", "id_diskusi_produk = '".$id_diskusi."'");
            $diskusi = '';
            $comment = '';
            foreach ($get_diskusi as $baris => $row_diskusi) {
                if ($baris == 0) {
                    $diskusi .= '
                        <div class="direct-chat-msg ts">
                            <div class="direct-chat-info clearfix" id="cont_status'.str_replace('/', '_', $row_diskusi['id_diskusi_produk']).'">
                                <span style="color: '.($row_diskusi['status']=='0'?'red':'green').'" title="'.($row_diskusi['status']=='1'?'Sembunyikan':'Tampilkan').'" onclick="change_status(\''.$row_diskusi['id_diskusi_produk'].'\', \''.$row_diskusi['status'].'\')" class="pull-right"><i class="fa '.($row_diskusi['status']=='0'?'fa-times':'fa-check').'"></i></span>
                            </div>
                            <div class="direct-chat-info clearfix">
                                <span class="direct-chat-name pull-left">'.$row_diskusi['nama'].'</span>
                                <span class="direct-chat-timestamp pull-right">'.date('d M Y H:i A', strtotime($row_diskusi['tgl_diskusi'])).'</span>
                            </div>
                            <img class="direct-chat-img" src="'.base_url().$row_diskusi['foto'].'" alt="Message User Image">
                            <div class="direct-chat-text">
                                '.$row_diskusi['comment'].'
                            </div>
                        </div>
                        <div id="cont_comment'.$id_produk.'"></div>
                    ';
                    $get_comment = $this->m_crud->join_data("diskusi_produk dp", "dp.id_diskusi_produk, dp.tgl_diskusi, dp.comment, dp.status, m.id_member, IFNULL(m.nama, 'Admin Indokids') nama, IFNULL(m.nama, 'admin_idk') verify, IFNULL(m.foto, 'assets/images/member/admin.png') foto", array(array('table'=>'member m', 'type'=>'LEFT')), array("m.id_member=dp.member"), "dp.response = '".$row_diskusi['id_diskusi_produk']."'");
                    foreach ($get_comment as $row_comment) {
                        if ($row_comment['verify'] == 'admin_idk') {
                            $comment .= '
                            <div class="direct-chat-msg right">
                                <div class="direct-chat-info clearfix" id="cont_status'.str_replace('/', '_', $row_comment['id_diskusi_produk']).'">
                                    <span style="color: '.($row_comment['status']=='0'?'red':'green').'" title="'.($row_comment['status']=='1'?'Sembunyikan':'Tampilkan').'" onclick="change_status(\''.$row_comment['id_diskusi_produk'].'\', \''.$row_comment['status'].'\')" class="pull-right"><i class="fa '.($row_comment['status']=='0'?'fa-times':'fa-check').'"></i></span>
                                </div>
                                <div class="direct-chat-info clearfix">
                                    <span class="direct-chat-name pull-right">' . $row_comment['nama'] . '</span>
                                    <span class="direct-chat-timestamp pull-left">'.date('d M Y H:i A', strtotime($row_comment['tgl_diskusi'])).'</span>
                                </div>
                                <img class="direct-chat-img" src="' . base_url() . $row_comment['foto'] . '" alt="Message User Image">
                                <div class="direct-chat-text">
                                    ' . $row_comment['comment'] . '
                                </div>
                            </div>
                            ';
                        } else {
                            $comment .= '
                                <div class="direct-chat-msg">
                                    <div class="direct-chat-info clearfix" id="cont_status'.str_replace('/', '_', $row_comment['id_diskusi_produk']).'">
                                        <span style="color: '.($row_comment['status']=='0'?'red':'green').'" title="'.($row_comment['status']=='1'?'Sembunyikan':'Tampilkan').'" onclick="change_status(\''.$row_comment['id_diskusi_produk'].'\', \''.$row_comment['status'].'\')" class="pull-right"><i class="fa '.($row_comment['status']=='0'?'fa-times':'fa-check').'"></i></span>
                                    </div>
                                    <div class="direct-chat-info clearfix">
                                        <span class="direct-chat-name pull-left">' . $row_comment['nama'] . '</span>
                                        <span class="direct-chat-timestamp pull-right">'.date('d M Y H:i A', strtotime($row_comment['tgl_diskusi'])).'</span>
                                    </div>
                                    <img class="direct-chat-img" src="' . base_url() . $row_comment['foto'] . '" alt="Message User Image">
                                    <div class="direct-chat-text">
                                        ' . $row_comment['comment'] . '
                                    </div>
                                </div>
                            ';
                        }
                    }
                }
            }

            if ($diskusi != '') {
                $result['status'] = true;
                $result['res_diskusi'] = $diskusi;
                $result['res_comment'] = $comment;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'change_status') {
            $id_diskusi = $_POST['id_diskusi'];
            $status = $_POST['status'];
            if ($status == '0') {
                $this->m_crud->update_data("diskusi_produk", array('status'=>'1'), "id_diskusi_produk='".$id_diskusi."'");
                $status = '<span style="color: green" title="Sembunyikan" onclick="change_status(\''.$id_diskusi.'\', \'1\')" class="pull-right"><i class="fa fa-check"></i></span>';
            } else {
                $this->m_crud->update_data("diskusi_produk", array('status'=>'0'), "id_diskusi_produk='".$id_diskusi."'");
                $status = '<span style="color: red" title="Tampilkan" onclick="change_status(\''.$id_diskusi.'\', \'0\')" class="pull-right"><i class="fa fa-times"></i></span>';
            }

            echo json_encode(array('status'=>$status));
        } else {
            $this->load->view('bo/index', $data);
        }
    }
    /*End diskusi*/

    /*Start ulasan*/
    public function ulasan($action=null, $page=1){
        $data = $this->data;
        $table = 'ulasan';
        $function = 'ulasan';
        $view = $this->control.'/';
        $data['page'] = $function;
        $data['title'] = 'Ulasan Produk';
        $data['content'] = $view.$function;
        $where = null;
        $paging = 3;

        if(isset($_POST['search'])||isset($_POST['to_excel'])) {
            $this->session->set_userdata('search', array('any' => $_POST['any']));
        }

        $search = $this->session->search['any'];
        if(isset($search)&&$search!=null) {
            ($where == null) ? null : $where .= " AND ";
            $where .= "p.nama like '%".$search."%'";
        }

        if ($action == 'get_data') {
            $config = array();
            $config["base_url"] = "#";
            //$config["total_rows"] = $this->ajax_pagination_model->count_all();
            $config["total_rows"] = $this->m_crud->count_data_join($table." u", "u.produk", "produk p", "p.id_produk=u.produk", $where);
            $config["per_page"] = 8;
            $config["uri_segment"] = 4;
            $config["num_links"] = 5;
            $config["use_page_numbers"] = TRUE;
            $config["full_tag_open"] = '<ul class="pagination2">';
            $config["full_tag_close"] = '</ul>';
            $config['next_link'] = 'Next';
            $config["next_tag_open"] = '<li>';
            $config["next_tag_close"] = '</li>';
            $config["prev_link"] = "Previous";
            $config["prev_tag_open"] = '<li>';
            $config["prev_tag_close"] = '</li>';
            $config["cur_tag_open"] = '<li class="active"><a href="#">';
            $config["cur_tag_close"] = '</a></li>';
            $config["num_tag_open"] = '<li>';
            $config["num_tag_close"] = '</li>';
            $this->pagination->initialize($config);
            $start = ($page - 1) * $config["per_page"];

            $output = '';
            $read_data = $this->m_crud->join_data($table." u", "p.id_produk, p.nama", "produk p", "p.id_produk=u.produk", $where, "tgl_ulasan DESC", "u.produk", $config["per_page"], $start);

            $output .= '';
            if ($read_data != null) {
                foreach ($read_data as $row) {
                    $baris = $row['id_produk'];
                    $get_ulasan = $this->m_crud->join_data("ulasan u", "u.id_ulasan, u.rating_produk, u.status, u.rating_pelayanan, ulasan, tgl_ulasan, m.id_member, m.nama nama_member, CONCAT('".base_url()."', m.foto) foto", "member m", "m.id_member=u.member", "u.produk='".$row['id_produk']."'", "u.tgl_ulasan DESC", null, $paging);
                    $ulasan = '';
                    $load_more = '';
                    if ($this->m_crud->count_data_join("ulasan u", "u.rating_produk", "member m", "m.id_member=u.member", "u.produk='".$row['id_produk']."'") > $paging) {
                        $load_more = '
                        <div class="row" onclick="load_more(\''.$row['id_produk'].'\')" id="load_more'.$row['id_produk'].'">
                            <div class="col-md-12">
                                <button onclick="load_ulasan(\''.$row['id_produk'].'\')" class="btn btn-outline-secondary btn-block">SHOW MORE</button>
                            </div>
                        </div>
                        ';
                    }
                    foreach ($get_ulasan as $row_ulasan) {
                        $rating = $row_ulasan['id_ulasan'];
                        $ulasan .= '
                        <div class="post">
                            <div class="user-block">
                                <img class="img-circle img-bordered-sm" src="'.$row_ulasan['foto'].'" alt="user image">
                                    <span class="username">
                                        <a href="javascript:">'.$row_ulasan['nama_member'].'</a>
                                        <div id="cont_status'.$rating.'">
                                            <span style="color: '.($row_ulasan['status']=='0'?'red':'green').'" title="'.($row_ulasan['status']=='1'?'Sembunyikan':'Tampilkan').'" onclick="change_status(\''.$row_ulasan['id_ulasan'].'\', \''.$row_ulasan['status'].'\')" class="pull-right"><i class="fa '.($row_ulasan['status']=='0'?'fa-times':'fa-check').'"></i></span>
                                        </div>
                                    </span>
                                <span class="description">'.date('d M Y', strtotime($row_ulasan['tgl_ulasan'])).', '.date('H:i A', strtotime($row_ulasan['tgl_ulasan'])).'</span>
                            </div>
                            <p>'.$row_ulasan['ulasan'].'</p>
                            <div class="clearfix">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="pull-left mt-8">
                                            <span class="mr-2">Kualitas Produk</span>
                                        </div>
                                        <div class="pull-left">
                                            <div class="rating-grid">
                                                <fieldset class="rating">
                                                    <input type="radio" id="stars5'.$baris.$rating.'" disabled '.($row_ulasan['rating_produk']==5?'checked':'').' name="rating'.$baris.$rating.'" value="5" /><label class = "full" for="stars5'.$baris.$rating.'" title="Awesome"></label>
                                                    <input type="radio" id="stars4'.$baris.$rating.'" disabled '.($row_ulasan['rating_produk']==4?'checked':'').' name="rating'.$baris.$rating.'" value="4" /><label class = "full" for="stars4'.$baris.$rating.'" title="Pretty Good"></label>
                                                    <input type="radio" id="stars3'.$baris.$rating.'" disabled '.($row_ulasan['rating_produk']==3?'checked':'').' name="rating'.$baris.$rating.'" value="3" /><label class = "full" for="stars3'.$baris.$rating.'" title="Good"></label>
                                                    <input type="radio" id="stars2'.$baris.$rating.'" disabled '.($row_ulasan['rating_produk']==2?'checked':'').' name="rating'.$baris.$rating.'" value="2" /><label class = "full" for="stars2'.$baris.$rating.'" title="Bad"></label>
                                                    <input type="radio" id="stars1'.$baris.$rating.'" disabled '.($row_ulasan['rating_produk']==1?'checked':'').' name="rating'.$baris.$rating.'" value="1" /><label class = "full" for="stars1'.$baris.$rating.'" title="Very Bad"></label>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="pull-left mt-8">
                                            <span class="mr-3">Pelayanan</span>
                                        </div>
                                        <div class="pull-left">
                                            <div class="rating-grid mr-2">
                                                <fieldset class="rating">
                                                    <input type="radio" id="stars25'.$baris.$rating.'" disabled '.($row_ulasan['rating_pelayanan']==5?'checked':'').' name="rating2'.$baris.$rating.'" value="5" /><label class = "full" for="stars25'.$baris.$rating.'" title="Awesome"></label>
                                                    <input type="radio" id="stars24'.$baris.$rating.'" disabled '.($row_ulasan['rating_pelayanan']==4?'checked':'').' name="rating2'.$baris.$rating.'" value="4" /><label class = "full" for="stars24'.$baris.$rating.'" title="Pretty Good"></label>
                                                    <input type="radio" id="stars23'.$baris.$rating.'" disabled '.($row_ulasan['rating_pelayanan']==3?'checked':'').' name="rating2'.$baris.$rating.'" value="3" /><label class = "full" for="stars23'.$baris.$rating.'" title="Good"></label>
                                                    <input type="radio" id="stars22'.$baris.$rating.'" disabled '.($row_ulasan['rating_pelayanan']==2?'checked':'').' name="rating2'.$baris.$rating.'" value="2" /><label class = "full" for="stars22'.$baris.$rating.'" title="Bad"></label>
                                                    <input type="radio" id="stars21'.$baris.$rating.'" disabled '.($row_ulasan['rating_pelayanan']==1?'checked':'').' name="rating2'.$baris.$rating.'" value="1" /><label class = "full" for="stars21'.$baris.$rating.'" title="Very Bad"></label>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        ';
                    }
                    $output .= '
                    <div class="box">
                        <div class="box-header">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="box-title">Ulasan untuk produk - <a target="_blank" href="'.base_url().'store/produk_detail/'.$row['id_produk'].'">'.$row['nama'].'</a></h3>
                                </div>
                            </div>
                        </div>
                        <div class="box-body">
                            <div id="cont_ulasan'.$row['id_produk'].'">
                            '.$ulasan.'
                            </div>
                        '.$load_more.'
                        <input type="hidden" name="page'.$row['id_produk'].'" id="page'.$row['id_produk'].'" value="2">
                        </div>
                    </div>
                ';
                }
            } else {
                $output .= '
                    <div class="box"><h3 style="padding: 10px" class="text-center">Tidak ada data</h3></div>
                ';
            }

            $result = array(
                'pagination_link' => $this->pagination->create_links(),
                'result_data' => $output
            );
            echo json_encode($result);
        } else if ($action == 'load_more') {
            $result = array();
            $id_produk = $_POST['id_produk'];
            $start = ($page - 1) * $paging;
            $result['page'] = $page+1;

            $get_ulasan = $this->m_crud->join_data("ulasan u", "u.id_ulasan, u.rating_produk, u.status, u.rating_pelayanan, ulasan, tgl_ulasan, m.id_member, m.nama nama_member, CONCAT('".base_url()."', m.foto) foto", "member m", "m.id_member=u.member", "u.produk='".$id_produk."'", "u.tgl_ulasan DESC", null, $paging, $start);
            $ulasan = '';
            $baris = $id_produk;
            if ($get_ulasan != null) {
                $result['status'] = true;
                foreach ($get_ulasan as $row_ulasan) {
                    $rating = $row_ulasan['id_ulasan'];
                    $ulasan .= '
                    <div class="post">
                        <div class="user-block">
                            <img class="img-circle img-bordered-sm" src="' . $row_ulasan['foto'] . '" alt="user image">
                                <span class="username">
                                    <a href="javascript:">' . $row_ulasan['nama_member'] . '</a>
                                    <div id="cont_status'.$rating.'">
                                        <span style="color: '.($row_ulasan['status']=='0'?'red':'green').'" title="'.($row_ulasan['status']=='1'?'Sembunyikan':'Tampilkan').'" onclick="change_status(\''.$row_ulasan['id_ulasan'].'\', \''.$row_ulasan['status'].'\')" class="pull-right"><i class="fa '.($row_ulasan['status']=='0'?'fa-times':'fa-check').'"></i></span>
                                    </div>
                                </span>
                            <span class="description">' . date('d M Y', strtotime($row_ulasan['tgl_ulasan'])) . ', ' . date('H:i A', strtotime($row_ulasan['tgl_ulasan'])) . '</span>
                        </div>
                        <p>' . $row_ulasan['ulasan'] . '</p>
                        <div class="clearfix">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="pull-left mt-8">
                                        <span class="mr-2">Kualitas Produk</span>
                                    </div>
                                    <div class="pull-left">
                                        <div class="rating-grid">
                                            <fieldset class="rating">
                                                <input type="radio" id="stars5' . $baris . $rating . '" disabled ' . ($row_ulasan['rating_produk'] == 5 ? 'checked' : '') . ' name="rating' . $baris . $rating . '" value="5" /><label class = "full" for="stars5' . $baris . $rating . '" title="Awesome"></label>
                                                <input type="radio" id="stars4' . $baris . $rating . '" disabled ' . ($row_ulasan['rating_produk'] == 4 ? 'checked' : '') . ' name="rating' . $baris . $rating . '" value="4" /><label class = "full" for="stars4' . $baris . $rating . '" title="Pretty Good"></label>
                                                <input type="radio" id="stars3' . $baris . $rating . '" disabled ' . ($row_ulasan['rating_produk'] == 3 ? 'checked' : '') . ' name="rating' . $baris . $rating . '" value="3" /><label class = "full" for="stars3' . $baris . $rating . '" title="Good"></label>
                                                <input type="radio" id="stars2' . $baris . $rating . '" disabled ' . ($row_ulasan['rating_produk'] == 2 ? 'checked' : '') . ' name="rating' . $baris . $rating . '" value="2" /><label class = "full" for="stars2' . $baris . $rating . '" title="Bad"></label>
                                                <input type="radio" id="stars1' . $baris . $rating . '" disabled ' . ($row_ulasan['rating_produk'] == 1 ? 'checked' : '') . ' name="rating' . $baris . $rating . '" value="1" /><label class = "full" for="stars1' . $baris . $rating . '" title="Very Bad"></label>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="pull-left mt-8">
                                        <span class="mr-3">Pelayanan</span>
                                    </div>
                                    <div class="pull-left">
                                        <div class="rating-grid mr-2">
                                            <fieldset class="rating">
                                                <input type="radio" id="stars25' . $baris . $rating . '" disabled ' . ($row_ulasan['rating_pelayanan'] == 5 ? 'checked' : '') . ' name="rating2' . $baris . $rating . '" value="5" /><label class = "full" for="stars25' . $baris . $rating . '" title="Awesome"></label>
                                                <input type="radio" id="stars24' . $baris . $rating . '" disabled ' . ($row_ulasan['rating_pelayanan'] == 4 ? 'checked' : '') . ' name="rating2' . $baris . $rating . '" value="4" /><label class = "full" for="stars24' . $baris . $rating . '" title="Pretty Good"></label>
                                                <input type="radio" id="stars23' . $baris . $rating . '" disabled ' . ($row_ulasan['rating_pelayanan'] == 3 ? 'checked' : '') . ' name="rating2' . $baris . $rating . '" value="3" /><label class = "full" for="stars23' . $baris . $rating . '" title="Good"></label>
                                                <input type="radio" id="stars22' . $baris . $rating . '" disabled ' . ($row_ulasan['rating_pelayanan'] == 2 ? 'checked' : '') . ' name="rating2' . $baris . $rating . '" value="2" /><label class = "full" for="stars22' . $baris . $rating . '" title="Bad"></label>
                                                <input type="radio" id="stars21' . $baris . $rating . '" disabled ' . ($row_ulasan['rating_pelayanan'] == 1 ? 'checked' : '') . ' name="rating2' . $baris . $rating . '" value="1" /><label class = "full" for="stars21' . $baris . $rating . '" title="Very Bad"></label>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    ';
                }
                $result['result_data'] = $ulasan;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'change_status') {
            $id_ulasan = $_POST['id_ulasan'];
            $status = $_POST['status'];
            if ($status == '0') {
                $this->m_crud->update_data("ulasan", array('status'=>'1'), "id_ulasan='".$id_ulasan."'");
                $status = '<span style="color: green" title="Sembunyikan" onclick="change_status(\''.$id_ulasan.'\', \'1\')" class="pull-right"><i class="fa fa-check"></i></span>';
            } else {
                $this->m_crud->update_data("ulasan", array('status'=>'0'), "id_ulasan='".$id_ulasan."'");
                $status = '<span style="color: red" title="Tampilkan" onclick="change_status(\''.$id_ulasan.'\', \'0\')" class="pull-right"><i class="fa fa-times"></i></span>';
            }

            echo json_encode(array('status'=>$status));
        } else {
            $this->load->view('bo/index', $data);
        }
    }
    /*End Ulasan*/
}
