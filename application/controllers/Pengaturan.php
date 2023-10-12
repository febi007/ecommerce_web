<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengaturan extends CI_Controller
{

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        //$this->session->sess_destroy();

        $site_data = $this->m_website->site_data();
        $this->site = str_replace(' ', '', strtolower($site_data->nama));
        $this->control = 'Pengaturan';

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

    /*Start pengaturan harga COD*/
	public function harga_setting($action=null){
		$data = $this->data;
		$function = 'harga_setting';
		$view = $this->control.'/';
		if($this->session->userdata($this->site . 'admin_menu')!=$function) {
			$this->session->unset_userdata('search');
			$this->cart->destroy();
			$this->session->set_userdata($this->site . 'admin_menu', $function);
		}
		$data['main'] = 'Pengaturan';
		$data['title'] = 'Harga Setting';
		$data['page'] = $function;
		$data['content'] = $view.$function;

		if($action=='load_data'){
			echo json_encode(array('harga_cod'=>$this->m_crud->get_data("setting","harga_cod")['harga_cod']));
		}
		elseif ($action=='simpan'){
			$this->m_crud->update_data(
				"setting",array("harga_cod"=>$_POST['harga_cod']),"id_setting='1111'"
			);
			echo json_encode(array('status'=>true));
		}
		else{
			$this->load->view('bo/index', $data);
		}


	}

    /*Start pengaturan situs*/
    public function situs($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'situs';
        $table = 'site';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Pengaturan';
        $data['title'] = 'Situs';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $data['groups'] = $this->m_crud->read_data("groups", "id_groups, nama");
        $where = null;

        if ($action == 'get_data') {
            $output = '';
            $read_data = $this->m_crud->read_data($table, "*");
            $output .= '
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th width="1%" class="text-center">#</th>
                    <th>Site</th>
                    <th>Nama</th>
                    <th>Logo</th>
                    <th>Icon</th>
                    <th>Versi</th>
                    <th>Website</th>
                </tr>
            ';
            $no = 1;
            if ($read_data != null) {
                foreach ($read_data as $row) {
                    $output .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Pilihan <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu dropdown-position">
                                <li><a href="#" onclick="edit(\'' . $row['id_site'] . '\')">Edit</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . ($row['id_site']=='1111'?'Back Office':'Store') . '</td>
                        <td>' . $row['nama'] . '</td>
                        <td><img src="' . base_url().$row['logo'] . '" style="max-height: 100px"></td>
                        <td><img src="' . base_url().$row['icon'] . '" style="max-height: 80px"></td>
                        <td>' . $row['versi'] . '</td>
                        <td>' . $row['web'] . '</td>
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
                'result_table' => $output
            );
            echo json_encode($result);
        } else if ($action == 'simpan') {
            $this->db->trans_begin();

            $path = 'assets/images/site';
            $config['upload_path']          = './'.$path;
            $config['allowed_types']        = 'bmp|gif|jpg|jpeg|png';
            $config['max_size']             = 5120;
            $this->load->library('upload', $config);
            $input_file = array('1'=>'logo', '2'=>'icon');
            $valid = true;
            foreach($input_file as $row){
                if( (! $this->upload->do_upload($row)) && $_FILES[$row]['name']!=null){
                    $file[$row]['file_name']=null;
                    $file[$row] = $this->upload->data();
                    $valid = false;
                    $data['error_'.$row] = $this->upload->display_errors();
                    break;
                } else{
                    $file[$row] = $this->upload->data();
                    $data[$row] = $file;
                    /*if($file[$row]['file_name']!=null){
                        $manipulasi['image_library'] = 'gd2';
                        $manipulasi['source_image'] = $file[$row]['full_path'];
                        $manipulasi['maintain_ratio'] = true;
                        $manipulasi['width']         = 500;
                        //$manipulasi['height']       = 250;
                        $manipulasi['new_image']       = $file[$row]['full_path'];
                        $manipulasi['create_thumb']       = true;
                        //$manipulasi['thumb_marker']       = '_thumb';
                        $this->load->library('image_lib', $manipulasi);
                        $this->image_lib->resize();
                    }*/
                }
            }

            if ($valid) {
                if ($_POST['param'] == 'add') {

                } else {
                    $id = $_POST['id'];

                    $data_site = array(
                        'nama' => $this->m_website->replace_kutip($_POST['nama']),
                        'versi' => $this->m_website->replace_kutip($_POST['versi']),
                        'web' => $this->m_website->replace_kutip($_POST['website'])
                    );

                    foreach($input_file as $row) {
                        if($_FILES[$row]['name']!=null) {
                            $data_site[$row] = ($_FILES[$row]['name']!=null)?($path.'/'.$file[$row]['file_name']):null;
                        }
                    }

                    $this->m_crud->update_data("site", $data_site, "id_site='" . $id . "'");
                }
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo $valid;
            }
        } else if ($action == 'edit') {
            $get_data = $this->m_crud->get_data($table, "*", "id_site='".$_POST['id']."'");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_data'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'edit_navbar') {
            $get_data = $this->m_crud->read_data("navbar", "*");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_data'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'simpan_navbar') {

            $this->m_crud->update_data("navbar", array("groups"=>$_POST['1']), "id_navbar='1'");
            $this->m_crud->update_data("navbar", array("groups"=>$_POST['2']), "id_navbar='2'");
            $this->m_crud->update_data("navbar", array("groups"=>$_POST['3']), "id_navbar='3'");
            $this->m_crud->update_data("navbar", array("groups"=>$_POST['4']), "id_navbar='4'");
            $this->m_crud->update_data("navbar", array("groups"=>$_POST['5']), "id_navbar='5'");
            $this->m_crud->update_data("navbar", array("groups"=>$_POST['6']), "id_navbar='6'");
            $this->m_crud->update_data("navbar", array("groups"=>$_POST['7']), "id_navbar='7'");

            echo true;
        } else if ($action == 'edit_cs') {
            $get_data = $this->m_crud->get_data("setting", "cs", "id_setting='1111'")['cs'];
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_data'] = json_decode($get_data, true);
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'simpan_cs') {

            $this->m_crud->update_data("setting", array("cs"=>json_encode(array('open'=>$_POST['open'], 'tlp'=>$_POST['tlp'], 'email'=>$_POST['email'], 'time_open'=>$_POST['time_open'], 'time_close'=>$_POST['time_close']))), "id_setting='1111'");

            echo true;
        } else {
            $this->load->view('bo/index', $data);
        }
    }
    /*End pengaturan situs*/

    /*Start home setting*/
    public function home_setting($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'home_setting';
        $table = null;
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Pengaturan';
        $data['title'] = 'Home Setting';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = null;

        if ($action == 'get_data') {
            $output_top = ''; $output_middle = ''; $output_bottom = ''; $output_slider = ''; $output_text = '<h3 align="center">Home Text</h3>';
            $read_dashboard = $this->m_crud->get_data("setting", "home_dashboard", "id_setting='1111'");
            $read_top = $this->m_crud->read_data("top_item", "*");
            $read_middle = $this->m_crud->read_data("middle_item", "*");
            $read_bottom = $this->m_crud->read_data("bottom_item", "*");
            $read_slider = $this->m_crud->read_data("slider", "*");
            $read_text = $this->m_crud->get_data("setting", "home_text", "id_setting='1111'")['home_text'];

            $output_dashboard = '
                <h3 align="center">Home Dashboard</h3>
                <h2>'.strtoupper($read_dashboard['home_dashboard']).'</h2>
            ';

            $output_top .= '
                <h3 align="center">Top menu</h3>
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th width="1%" class="text-center">#</th>
                    <th>Nama</th>
                    <th>Gambar</th>
                </tr>
            ';
            $no = 1;
            if ($read_top != null) {
                foreach ($read_top as $row) {
                    $output_top .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Pilihan <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu dropdown-position">
                                <li><a href="javascript:" onclick="edit(\'' . $row['id_item'] . '\', \'top_item\')">Edit</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['nama'] . '</td>
                        <td><img src="' . base_url().$row['gambar'] . '" style="max-height: 100px"></td>
                    </tr>
                ';
                }
            }
            $output_top .= '</table>';

            $output_middle .= '
                <h3 align="center">Middle menu</h3>
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th width="1%" class="text-center">#</th>
                    <th>Nama</th>
                    <th>Gambar</th>
                </tr>
            ';
            $no = 1;
            if ($read_middle != null) {
                foreach ($read_middle as $row) {
                    $output_middle .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Pilihan <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu dropdown-position">
                                <li><a href="javascript:" onclick="edit(\'' . $row['id_item'] . '\', \'middle_item\')">Edit</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['nama'] . '</td>
                        <td><img src="' . base_url().$row['gambar'] . '" style="max-height: 100px"></td>
                    </tr>
                ';
                }
            }
            $output_middle .= '</table>';

            $output_bottom .= '
                <h3 align="center">Bottom menu</h3>
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th width="1%" class="text-center">#</th>
                    <th>Nama</th>
                    <th>Gambar</th>
                </tr>
            ';
            $no = 1;
            if ($read_bottom != null) {
                foreach ($read_bottom as $row) {
                    $output_bottom .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Pilihan <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu dropdown-position">
                                <li><a href="javascript:" onclick="edit(\'' . $row['id_item'] . '\', \'bottom_item\')">Edit</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['nama'] . '</td>
                        <td><img src="' . base_url().$row['gambar'] . '" style="max-height: 100px"></td>
                    </tr>
                ';
                }
            }
            $output_bottom .= '</table>';

            $output_slider .= '
                <h3 align="center">Slider</h3>
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th width="1%" class="text-center">#</th>
                    <th>Gambar</th>
                </tr>
            ';
            $no = 1;
            if ($read_slider != null) {
                foreach ($read_slider as $row) {
                    $output_slider .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Pilihan <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu dropdown-position">
                                <li><a href="javascript:" onclick="edit(\'' . $row['id_slider'] . '\', \'slider\')">Edit</a></li>
                                <li><a href="javascript:" onclick="hapus(\'' . $row['id_slider'] . '\', \'slider\')">Delete</a></li>
                            </ul>
                        </div>
                        </td>
                        <td><img src="' . base_url().$row['gambar'] . '" style="max-height: 100px"></td>
                    </tr>
                ';
                }
            }
            $output_slider .= '</table>';

            if ($read_text != null) {
                $decode_text = json_decode($read_text, true);
                $output_text .= '<div class="col-md-12"><h3>'.$decode_text['title'].'</h3>'.$decode_text['desc'].'</div>';
            }

            $result = array(
                'result_dashboard' => $output_dashboard,
                'result_top' => $output_top,
                'result_middle' => $output_middle,
                'result_bottom' => $output_bottom,
                'result_slider' => $output_slider,
                'result_text' => $output_text
            );
            echo json_encode($result);
        }
        else if ($action == 'simpan') {
            $row = 'gambar';
            $config['upload_path']          = './assets/images/site';
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

            $id = $_POST['id'];
            if ($_POST['param'] == 'slider') {
                $data_slider = array();
                if ($_FILES['gambar']['name'] != null) {
                    $data_slider['gambar'] = 'assets/images/site/' . $file['gambar']['file_name'];
                }

                if ($id == 'add') {
                    $this->m_crud->create_data($_POST['param'], $data_slider);
                } else {
                    $this->m_crud->update_data($_POST['param'], $data_slider, "id_slider='" . $id . "'");
                }
            } else if ($_POST['param'] == 'text') {
                $this->m_crud->update_data("setting", array("home_text"=>json_encode(array('title'=>$_POST['title'], 'desc'=>$_POST['desc']))), "id_setting='1111'");
            } else if ($_POST['param'] == 'home_dashboard') {
                $this->m_crud->update_data("setting", array("home_dashboard"=>$_POST['home_dashboard']), "id_setting='1111'");
            } else {
                $data_item = array(
                    'nama' => $_POST['nama']
                );
                if ($_FILES['gambar']['name'] != null) {
                    $data_item['gambar'] = 'assets/images/site/' . $file['gambar']['file_name'];
                }
                $this->m_crud->update_data($_POST['param'], $data_item, "id_item='" . $id . "'");

                $this->m_crud->delete_data("det_" . $_POST['param'], $_POST['param'] . "='" . $id . "'");
                foreach ($_POST['id_produk'] as $row) {
                    $this->m_crud->create_data("det_" . $_POST['param'], array($_POST['param'] => $id, 'produk' => $row));
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
        else if ($action == 'edit') {
            if ($_POST['table'] == 'slider') {
                $get_data = $this->m_crud->get_data($_POST['table'], "*", "id_slider='" . $_POST['id'] . "'");
            } else if ($_POST['table'] == 'text') {
                $get_setting = $this->m_crud->get_data("setting", "home_text", "id_setting='1111'")['home_text'];
                $get_data = json_decode($get_setting, true);
            } else if ($_POST['table'] == 'home_dashboard') {
                $get_setting = $this->m_crud->get_data("setting", "home_dashboard", "id_setting='1111'")['home_dashboard'];
                $get_data = $get_setting;
            } else {
                $get_data = $this->m_crud->get_data($_POST['table'], "*", "id_item='" . $_POST['id'] . "'");
            }
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                if ($_POST['table'] != 'slider' && $_POST['table'] != 'text' && $_POST['table'] != 'home_dashboard') {
                    $result['det_item'] = $this->m_crud->join_data("det_" . $_POST['table'] . " di", "p.id_produk, p.nama, p.code, dpr.berat, dpr.hrg_jual", array("produk p", "det_produk dpr"), array("p.id_produk=di.produk", "dpr.produk=p.id_produk"), "di." . $_POST['table'] . "='" . $_POST['id'] . "'", null, "di.produk");
                }
                $result['res_data'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        }
        else if ($action == 'get_produk') {
            $get_produk = $this->m_crud->join_data("produk p", "CONCAT(p.code, ' | ', p.nama) value, p.id_produk, p.nama, p.code, dp.berat, dp.hrg_jual", "det_produk dp", "dp.produk=p.id_produk AND dp.code=p.code", "p.nama like '%".$_POST['query']."%' OR p.code like '%".$_POST['query']."%'");

            if ($get_produk != null) {
                $result = $get_produk;
            } else {
                $result = array(array('id_produk'=>'not_found', 'value'=>'Produk Tidak Tersedia!'));
            }

            echo json_encode(array("suggestions"=>$result));
        }
        else if ($action == 'hapus') {
            $delete_data = $this->m_crud->delete_data($_POST['param'], "id_".$_POST['param']." = '".$_POST['id']."'");

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
    /*End home setting*/

    /*Start tentang*/
    public function tentang_kami($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'tentang_kami';
        $table = 'setting';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Pengaturan';
        $data['title'] = 'Tentang Kami';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = "id_setting='1111'";

        if ($action == 'get_data') {

            $read_data = $this->m_crud->get_data($table, "tentang", $where);

            if ($read_data != null) {
                $output = '<div style="margin: 20px">'.$read_data['tentang'].'</div>';
            } else {
                $output = '
                    <h3 class="text-center">Tidak ada data</h3>
                ';
            }

            $result = array(
                'result_data' => $output
            );
            echo json_encode($result);
        } else if ($action == 'simpan') {
            $this->db->trans_begin();

            $data_setting = array(
                'tentang' => $this->m_website->replace_kutip($_POST['ckeditor'])
            );

            if ($_POST['param'] == 'add') {

            } else {
                $id = $_POST['id'];
                $this->m_crud->update_data($table, $data_setting, "id_setting='".$id."'");
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo true;
            }
        } else if ($action == 'edit') {
            $get_data = $this->m_crud->get_data($table, "tentang", "id_setting='".$_POST['id']."'");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_data'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else {
            $this->load->view('bo/index', $data);
        }
    }
    /*End pengaturan tentang*/

    /*Start cara belanja*/
    public function cara_belanja($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'cara_belanja';
        $table = 'setting';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Pengaturan';
        $data['title'] = 'Cara Belanja';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = "id_setting='1111'";

        if ($action == 'get_data') {

            $read_data = $this->m_crud->get_data($table, "cara_belanja", $where);

            if ($read_data != null) {
                $output = '<div style="margin: 20px">'.$read_data['cara_belanja'].'</div>';
            } else {
                $output = '
                    <h3 class="text-center">Tidak ada data</h3>
                ';
            }

            $result = array(
                'result_data' => $output
            );
            echo json_encode($result);
        } else if ($action == 'simpan') {
            $this->db->trans_begin();

            $data_setting = array(
                'cara_belanja' => $this->m_website->replace_kutip($_POST['cara_belanja'])
            );

            if ($_POST['param'] == 'add') {

            } else {
                $id = $_POST['id'];
                $this->m_crud->update_data($table, $data_setting, "id_setting='".$id."'");
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo true;
            }
        } else if ($action == 'edit') {
            $get_data = $this->m_crud->get_data($table, "cara_belanja", "id_setting='".$_POST['id']."'");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_data'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else {
            $this->load->view('bo/index', $data);
        }
    }
    /*End pengaturan cara belanja*/

    /*Start syarat & ketentuan*/
    public function syarat($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'syarat';
        $table = 'setting';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Pengaturan';
        $data['title'] = 'Syarat dan Ketentuan';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = "id_setting='1111'";

        if ($action == 'get_data') {

            $read_data = $this->m_crud->get_data($table, "syarat", $where);

            if ($read_data != null) {
                $output = '<div style="margin: 20px">'.$read_data['syarat'].'</div>';
            } else {
                $output = '
                    <h3 class="text-center">Tidak ada data</h3>
                ';
            }

            $result = array(
                'result_data' => $output
            );
            echo json_encode($result);
        } else if ($action == 'simpan') {
            $this->db->trans_begin();

            $data_setting = array(
                'syarat' => $this->m_website->replace_kutip($_POST['syarat'])
            );

            if ($_POST['param'] == 'add') {

            } else {
                $id = $_POST['id'];
                $this->m_crud->update_data($table, $data_setting, "id_setting='".$id."'");
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo true;
            }
        } else if ($action == 'edit') {
            $get_data = $this->m_crud->get_data($table, "syarat", "id_setting='".$_POST['id']."'");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_data'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else {
            $this->load->view('bo/index', $data);
        }
    }
    /*End pengaturan syarat & ketentuan*/

    /*Start kebijakan*/
    public function kebijakan($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'kebijakan';
        $table = 'setting';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Pengaturan';
        $data['title'] = 'Kebijakan Privasi';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = "id_setting='1111'";

        if ($action == 'get_data') {

            $read_data = $this->m_crud->get_data($table, "kebijakan", $where);

            if ($read_data != null) {
                $output = '<div style="margin: 20px">'.$read_data['kebijakan'].'</div>';
            } else {
                $output = '
                    <h3 class="text-center">Tidak ada data</h3>
                ';
            }

            $result = array(
                'result_data' => $output
            );
            echo json_encode($result);
        } else if ($action == 'simpan') {
            $this->db->trans_begin();

            $data_setting = array(
                'kebijakan' => $this->m_website->replace_kutip($_POST['kebijakan'])
            );

            if ($_POST['param'] == 'add') {

            } else {
                $id = $_POST['id'];
                $this->m_crud->update_data($table, $data_setting, "id_setting='".$id."'");
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo true;
            }
        } else if ($action == 'edit') {
            $get_data = $this->m_crud->get_data($table, "kebijakan", "id_setting='".$_POST['id']."'");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_data'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else {
            $this->load->view('bo/index', $data);
        }
    }
    /*End pengaturan kebijakan*/

    /*Start resolusi*/
    public function resolusi($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'resolusi';
        $table = 'setting';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Pengaturan';
        $data['title'] = 'Pusat Resolusi';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = "id_setting='1111'";

        if ($action == 'get_data') {

            $read_data = $this->m_crud->get_data($table, "pusat_resolusi", $where);

            if ($read_data != null) {
                $output = '<div style="margin: 20px">'.$read_data['pusat_resolusi'].'</div>';
            } else {
                $output = '
                    <h3 class="text-center">Tidak ada data</h3>
                ';
            }

            $result = array(
                'result_data' => $output
            );
            echo json_encode($result);
        } else if ($action == 'simpan') {
            $this->db->trans_begin();

            $data_setting = array(
                'pusat_resolusi' => $this->m_website->replace_kutip($_POST['resolusi'])
            );

            if ($_POST['param'] == 'add') {

            } else {
                $id = $_POST['id'];
                $this->m_crud->update_data($table, $data_setting, "id_setting='".$id."'");
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo true;
            }
        } else if ($action == 'edit') {
            $get_data = $this->m_crud->get_data($table, "pusat_resolusi", "id_setting='".$_POST['id']."'");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_data'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else {
            $this->load->view('bo/index', $data);
        }
    }
    /*End pengaturan resolusi*/

    /*Start sosial media*/
    public function sosial_media($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'sosial_media';
        $table = 'setting';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Pengaturan';
        $data['title'] = 'Sosial Media';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $data['sosmed'] = array(
            array('id' => 'facebook', 'nama' => 'Facebook'),
            array('id' => 'instagram', 'nama' => 'Instagram'),
            array('id' => 'twitter', 'nama' => 'Twitter'),
            array('id' => 'gplus', 'nama' => 'Google Plus'),
            array('id' => 'line', 'nama' => 'Line'),
            array('id' => 'whatsapp', 'nama' => 'Whatsapp')
        );
        $where = "id_setting='1111'";

        if ($action == 'get_data') {

            $read_data = $this->m_crud->get_data($table, "sosmed", $where);

            $output = '';
            $decode = json_decode($read_data['sosmed'], true);
            if ($read_data != null && is_array($decode)) {
                foreach ($decode as $row) {
                    $output .= '
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon"><img style="padding: 10px" src="'.base_url().'assets/images/sosial_media/'.$row['id'].'.png"></span>

                            <div class="info-box-content">
                                <span class="info-box-text">'.$row['nama'].'</span>
                                <span class="info-box-number">'.$row['value'].'</span>
                            </div>
                        </div>
                    </div>
                    ';
                }
            } else {
                $output = '
                    <h3 class="text-center">Tidak ada data</h3>
                ';
            }

            $result = array(
                'result_data' => $output
            );
            echo json_encode($result);
        } else if ($action == 'simpan') {
            $this->db->trans_begin();

            $sosmed = $_POST['sosmed'];
            $nama = $_POST['nama'];

            $data_sosmed = array();
            foreach ($sosmed as $row => $value) {
                $id = $sosmed[$row];

                $list = array(
                    'id' => $id,
                    'nama' => $nama[$row],
                    'value' => $_POST[$id],
                    'format_order' => $_POST['format_order']
                );

                array_push($data_sosmed, $list);
            }

            $data_setting = array(
                'sosmed' => json_encode($data_sosmed)
            );

            if ($_POST['param'] == 'add') {

            } else {
                $id = $_POST['id'];
                $this->m_crud->update_data($table, $data_setting, "id_setting='".$id."'");
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo true;
            }
        } else if ($action == 'edit') {
            $get_data = $this->m_crud->get_data($table, "sosmed", "id_setting='".$_POST['id']."'");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_data'] = $get_data['sosmed'];
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else {
            $this->load->view('bo/index', $data);
        }
    }
    /*End pengaturan sosial media*/

    /*Start video home*/
    public function video_home($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'video_home';
        $table = 'setting';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Pengaturan';
        $data['title'] = 'Video Home';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = "id_setting='1111'";

        if ($action == 'get_data') {

            $read_data = $this->m_crud->get_data($table, "video_home", $where);

            $decode = json_decode($read_data['video_home'], true);

            if ($read_data != null) {
                $output = '
                    <h3 class="text-center">'.$decode['note'].' ~ '.$decode['note2'].'</h3>
                    <video autoplay loop muted>
                        <source src="'.base_url().$decode['video'].'" type="video/mp4">
                    </video>
                ';
            } else {
                $output = '
                    <h3 class="text-center">Tidak ada data</h3>
                ';
            }

            $result = array(
                'result_data' => $output
            );
            echo json_encode($result);
        } else if ($action == 'simpan') {
            $row = 'video';
            $config['upload_path']          = './assets/videos';
            $config['allowed_types']        = 'mp4';
            $config['max_size']             = 102400;
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

            $list_video = array(
                'note' => $_POST['note'],
                'note2' => $_POST['note2']
            );

            if($_FILES[$row]['name']!=null){
                $list_video['video'] = 'assets/videos/'.$file[$row]['file_name'];
            } else {
                $list_video['video'] = $_POST['old_video'];
            }

            $data_setting = array(
                'video_home' => json_encode($list_video)
            );

            if ($_POST['param'] == 'add') {

            } else {
                $id = $_POST['id'];
                $this->m_crud->update_data($table, $data_setting, "id_setting='".$id."'");
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo true;
            }
        } else if ($action == 'edit') {
            $get_data = $this->m_crud->get_data($table, "video_home", "id_setting='".$_POST['id']."'");
            $result = array();

            $decode = json_decode($get_data['video_home'], true);

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_data'] = $decode;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else {
            $this->load->view('bo/index', $data);
        }
    }
    /*End pengaturan video home*/

    /*Start video share*/
    public function video_share($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'video_share';
        $table = 'setting';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Pengaturan';
        $data['title'] = 'Video Share';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = "id_setting='1111'";

        if ($action == 'get_data') {

            $read_data = $this->m_crud->get_data($table, "video_share", $where);

            if ($read_data != null) {
                $output = '<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/'.$read_data['video_share'].'" frameborder="0" allowfullscreen></iframe>';
            } else {
                $output = '
                    <h3 class="text-center">Tidak ada data</h3>
                ';
            }

            $result = array(
                'result_data' => $output
            );
            echo json_encode($result);
        } else if ($action == 'simpan') {
            $this->db->trans_begin();

            $data_setting = array(
                'video_share' => $this->m_website->replace_kutip($_POST['video_share'])
            );

            if ($_POST['param'] == 'add') {

            } else {
                $id = $_POST['id'];
                $this->m_crud->update_data($table, $data_setting, "id_setting='".$id."'");
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo true;
            }
        } else if ($action == 'edit') {
            $get_data = $this->m_crud->get_data($table, "video_share", "id_setting='".$_POST['id']."'");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_data'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else {
            $this->load->view('bo/index', $data);
        }
    }
    /*End pengaturan video share*/

    /*Start karir*/
    public function karir($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'karir';
        $table = 'setting';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Pengaturan';
        $data['title'] = 'Karir';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = "id_setting='1111'";

        if ($action == 'get_data') {

            $read_data = $this->m_crud->get_data($table, "karir", $where);

            if ($read_data != null) {
                $output = '<div style="margin: 20px">'.$read_data['karir'].'</div>';
            } else {
                $output = '
                    <h3 class="text-center">Tidak ada data</h3>
                ';
            }

            $result = array(
                'result_data' => $output
            );
            echo json_encode($result);
        } else if ($action == 'simpan') {
            $this->db->trans_begin();

            $data_setting = array(
                'karir' => $this->m_website->replace_kutip($_POST['karir'])
            );

            if ($_POST['param'] == 'add') {

            } else {
                $id = $_POST['id'];
                $this->m_crud->update_data($table, $data_setting, "id_setting='".$id."'");
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo true;
            }
        } else if ($action == 'edit') {
            $get_data = $this->m_crud->get_data($table, "karir", "id_setting='".$_POST['id']."'");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_data'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else {
            $this->load->view('bo/index', $data);
        }
    }
    /*End pengaturan karir*/

    /*Start master home_slide*/
    public function home_slide($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'home_slide';
        $table = 'home_slide';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Pengaturan';
        $data['title'] = 'Slide Home';
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
            $where .= "judul like '%".$search."%'";
        }

        if ($action == 'get_data') {
            $config = array();
            $config["base_url"] = "#";
            //$config["total_rows"] = $this->ajax_pagination_model->count_all();
            $config["total_rows"] = $this->m_crud->count_data($table, "id_home_slide", $where);
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
            $read_data = $this->m_crud->read_data($table, "*", $where, "id_home_slide", null, $config["per_page"], $start);
            $output .= '
                <table class="table table-hover">
                <tr>
                    <th  style="white-space:nowrap">No</th>
                    <th  style="white-space:nowrap" class="text-center">#</th>
                    <th style="white-space:nowrap">Judul</th>
                    <th style="white-space:nowrap">Link</th>
                    <th style="white-space:nowrap">Gambar</th>
                </tr>
            ';
            $no = $start+1;
            if ($read_data != null) {
                foreach ($read_data as $row) {
                    $output .= '
                    <tr>
                        <td style="white-space:nowrap">' . $no++ . '</td>
                        <td style="white-space:nowrap">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Pilihan <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu dropdown-position">
                                <li><a href="#" onclick="edit(\'' . $row['id_home_slide'] . '\')">Edit</a></li>
                                <li><a href="#" onclick="hapus(\'' . $row['id_home_slide'] . '\')">Hapus</a></li>
                            </ul>
                        </div>
                        </td>
                        <td style="white-space:nowrap">' . $row['judul'] . '</td>
                        <td style="white-space:nowrap">' . $row['link'] . '</td>
                        <td style="white-space:nowrap"><div style="width: 100px; height: 20px; background: '.$row['warna'].'"></div></td>
                        <td style="white-space:nowrap"><img style="max-height:100px;" src="' . base_url().$this->m_website->file_thumb($row['gambar']) . '" /></td>
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
        } else if ($action == 'simpan') {
            $path = 'assets/images/slide';
            $config['upload_path']          = './'.$path;
            $config['allowed_types']        = 'bmp|gif|jpg|jpeg|png';
            $config['max_size']             = 5120;
            $this->load->library('upload', $config);
            $input_file = array('1'=>'file_upload');
            $valid = true;
            foreach($input_file as $row){
                if( (! $this->upload->do_upload($row)) && $_FILES[$row]['name']!=null){
                    $file[$row]['file_name']=null;
                    $file[$row] = $this->upload->data();
                    $valid = false;
                    $data['error_'.$row] = $this->upload->display_errors();
                    break;
                } else{
                    $file[$row] = $this->upload->data();
                    $data[$row] = $file;
                    if($file[$row]['file_name']!=null){
                        $manipulasi['image_library'] = 'gd2';
                        $manipulasi['source_image'] = $file[$row]['full_path'];
                        $manipulasi['maintain_ratio'] = true;
                        $manipulasi['width']         = 500;
                        //$manipulasi['height']       = 250;
                        $manipulasi['new_image']       = $file[$row]['full_path'];
                        $manipulasi['create_thumb']       = true;
                        //$manipulasi['thumb_marker']       = '_thumb';
                        $this->load->library('image_lib', $manipulasi);
                        $this->image_lib->resize();
                    }
                }
            }

            $this->db->trans_begin();

            if ($valid) {
                $data_slide = array(
                    'judul' => $_POST['judul'],
                    'link' => $_POST['link'],
                    'warna' => $_POST['warna']
                );

                if($_FILES['file_upload']['name']!=null) {
                    $data_slide['gambar'] = $path.'/'.$file['file_upload']['file_name'];
                }

                if ($_POST['param'] == 'add') {
                    $this->m_crud->create_data($table, $data_slide);
                } else {
                    $id = $_POST['id'];
                    $this->m_crud->update_data($table, $data_slide, "id_home_slide='" . $id . "'");
                }
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo $valid;
            }
        } else if ($action == 'edit') {
            $get_data = $this->m_crud->get_data($table, "*", "id_home_slide = '".$_POST['id']."'");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_data'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'hapus') {
            $delete_data = $this->m_crud->delete_data($table, "id_home_slide = '".$_POST['id']."'");

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
    /*End master home_slide*/

    public function shipping_service($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'shipping_service';
        $table = 'shipping_service';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Pengaturan';
        $data['title'] = 'Shipping Service';
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
            $where .= "title like '%".$search."%'";
        }

        if ($action == 'get_data') {
            $config = array();
            $config["base_url"] = "#";
            //$config["total_rows"] = $this->ajax_pagination_model->count_all();
            $config["total_rows"] = $this->m_crud->count_data($table, "id_shipping_service", $where);
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
            $read_data = $this->m_crud->read_data($table, "*", $where, "id_shipping_service", null, $config["per_page"], $start);
            $output .= '
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th width="1%" class="text-center">#</th>
                    <th>Judul</th>
                    <th>Status</th>
                    <th>Gambar</th>
                </tr>
            ';
            $no = $start+1;
            if ($read_data != null) {
                foreach ($read_data as $row) {
                    $status="";
                    if($row['status']==0){
                        $status.='<button class="btn btn-primary bg-red  btn-sm">Tidak Aktif</button>';
                    }
                    else{
                        $status.='<button class="btn btn-primary bg-green btn-sm">Aktif</button>';
                    }
                    $output .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Pilihan <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu dropdown-position">
                                <li><a href="#" onclick="edit(\'' . $row['id_shipping_service'] . '\')">Edit</a></li>
                                <li><a href="#" onclick="hapus(\'' . $row['id_shipping_service'] . '\')">Hapus</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['title'] . '</td>
                        <td>'.$status.'</td>
                        <td><img style="max-height:100px;" src="' .$row['image'] . '" /></td>
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
        } else if ($action == 'simpan') {
            $path = 'assets/images/shipping_service';
            $config['upload_path']          = './'.$path;
            $config['allowed_types']        = 'bmp|gif|jpg|jpeg|png|svg';
            $config['max_size']             = 5120;
            $this->load->library('upload', $config);
            $input_file = array('1'=>'file_upload');
            $valid = true;
            foreach($input_file as $row){
                if( (! $this->upload->do_upload($row)) && $_FILES[$row]['name']!=null){
                    $file[$row]['file_name']=null;
                    $file[$row] = $this->upload->data();
                    $valid = false;
                    $data['error_'.$row] = $this->upload->display_errors();
                    break;
                } else{
                    $file[$row] = $this->upload->data();
                    $data[$row] = $file;
                    if($file[$row]['file_name']!=null){
                        $manipulasi['image_library'] = 'gd2';
                        $manipulasi['source_image'] = $file[$row]['full_path'];
                        $manipulasi['maintain_ratio'] = true;
                        $manipulasi['width']         = 500;
                        //$manipulasi['height']       = 250;
                        $manipulasi['new_image']       = $file[$row]['full_path'];
                        $manipulasi['create_thumb']       = true;
                        //$manipulasi['thumb_marker']       = '_thumb';
                        $this->load->library('image_lib', $manipulasi);
                        $this->image_lib->resize();
                    }
                }
            }

            $this->db->trans_begin();

            if ($valid) {
                $data_slide = array(
                    'title' => $_POST['title'],
                    'status' => $_POST['status'],
                );

                if($_FILES['file_upload']['name']!=null) {
                    $data_slide['image'] = base_url().$path.'/'.$file['file_upload']['file_name'];
                }

                if ($_POST['param'] == 'add') {
                    $this->m_crud->create_data($table, $data_slide);
                } else {
                    $id = $_POST['id'];
                    $this->m_crud->update_data($table, $data_slide, "id_shipping_service='" . $id . "'");
                }
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo $valid;
            }
        } else if ($action == 'edit') {
            $get_data = $this->m_crud->get_data($table, "*", "id_shipping_service = '".$_POST['id']."'");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_data'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'hapus') {
            $delete_data = $this->m_crud->delete_data($table, "id_shipping_service = '".$_POST['id']."'");

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


}
