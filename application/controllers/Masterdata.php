<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Masterdata extends CI_Controller
{

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        //$this->session->sess_destroy();

        $site_data = $this->m_website->site_data();
        $this->site = str_replace(' ', '', strtolower($site_data->nama));
        $this->control = 'Masterdata';

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

    /*Start master data user*/
    public function data_user($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'data_user';
        $table = 'user_detail';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Master Data';
        $data['title'] = 'Data User';
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
            $where .= "ud.nama like '%".$search."%'";
        }

        if ($action == 'get_data') {
            $config = array();
            $config["base_url"] = "#";
            //$config["total_rows"] = $this->ajax_pagination_model->count_all();
            $config["total_rows"] = $this->m_crud->count_data_join($table." ud", "ud.id_user", array("user_akun ua", "user_level ul"), array("ua.user_detail=ud.id_user", "ul.id_level=ua.user_level AND ul.id_level<>'1'"), $where);
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
            $read_data = $this->m_crud->join_data($table." ud", "ua.username, ua.status, ud.id_user, ud.nama, ul.nama level", array("user_akun ua", "user_level ul"), array("ua.user_detail=ud.id_user", "ul.id_level=ua.user_level AND ul.id_level<>'1'"), $where, null, null, $config["per_page"], $start);
            $output .= '
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th width="1%" class="text-center">#</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>User Level</th>
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
                                <!--li><a href="#" onclick="detail(\'' . $row['id_user'] . '\')">Detail</a></li-->
                                <li><a href="#" onclick="edit(\'' . $row['id_user'] . '\')">Edit</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['nama'] . '</td>
                        <td>' . $row['username'] . '</td>
                        <td>' . $row['level'] . '</td>
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

            $read_level = $this->m_crud->read_data("user_level", "nama, id_level", "id_level!='1'");
            $list = '<option value="">Pilih Level</option>';

            if ($read_level != null) {
                foreach ($read_level as $row) {
                    $list .= '<option value="'.$row['id_level'].'">'.$row['nama'].'</option>';
                }
            }

            $result = array(
                'pagination_link' => $this->pagination->create_links(),
                'result_table' => $output,
                'user_level' => $list
            );
            echo json_encode($result);
        } else if ($action == 'cek_username') {
            $where = "username='".$_POST['username']."'";

            $_POST['param']=='edit'?$where.=" AND username<>'".$_POST['username']."'":null;

            $cek_username = $this->m_crud->get_data("user_akun", "username", $where);

            if ($cek_username == null) {
                echo 'true';
            } else {
                echo 'false';
            }
        } else if ($action == 'simpan') {
            $this->db->trans_begin();

            if ($_POST['param'] == 'add') {
                $this->m_crud->create_data($table, array('nama' => $_POST['nama']));
                $id = $this->db->insert_id();

                $options = ['cost' => 12];
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);
                $user_akun = array(
                    'username' => $_POST['username'],
                    'password' => $password,
                    'user_detail' => $id,
                    'user_level' => $_POST['user_level'],
                    'status' => isset($_POST['status']) ? $_POST['status'] : '0'
                );
                $this->m_crud->create_data("user_akun", $user_akun);
            } else {
                $id = $_POST['id'];
                $this->m_crud->update_data($table, array('nama'=>$_POST['nama']), "id_user='".$id."'");

                $options = ['cost' => 12];
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);
                $user_akun = array(
                    'username' => $_POST['username'],
                    'user_detail' => $id,
                    'user_level' => $_POST['user_level'],
                    'status' => isset($_POST['status']) ? $_POST['status'] : '0'
                );

                if ($_POST['password'] != '') {
                    $user_akun['password'] = $password;
                }

                $this->m_crud->update_data("user_akun", $user_akun, "user_detail='".$id."'");
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo true;
            }
        } else if ($action == 'edit') {
            $get_data = $this->m_crud->get_join_data($table." ud", "ud.nama, ua.username, ua.user_level, ua.status", "user_akun ua", "ua.user_detail=ud.id_user", "ud.id_user='".$_POST['id']."'");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_user'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else {
            $this->load->view('bo/index', $data);
        }
    }
    /*End master data user*/

    /*Start master user level*/
    public function user_level($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'user_level';
        $table = 'user_level';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Master Data';
        $data['title'] = 'User Level';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = "id_level<>'1'";

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
            $config["total_rows"] = $this->m_crud->count_data($table, "id_level", $where);
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
            $read_data = $this->m_crud->read_data($table, "id_level, nama", $where, null, null, $config["per_page"], $start);
            $output .= '
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th width="1%" class="text-center">#</th>
                    <th>Nama</th>
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
                                <li><a href="#" onclick="edit(\'' . $row['id_level'] . '\')">Edit</a></li>
                                <li><a href="#" onclick="hapus(\'' . $row['id_level'] . '\')">Hapus</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['nama'] . '</td>
                    </tr>
                ';
                }
            } else {
                $output .= '
                <tr>
                    <td colspan="3" class="text-center">Tidak ada data</td>
                </tr>
                ';
            }
            $output .= '</table>';

            $result = array(
                'pagination_link' => $this->pagination->create_links(),
                'result_table' => $output
            );
            echo json_encode($result);
        } else if ($action == 'cek_level') {
            $where = "nama='".$_POST['nama']."'";

            $_POST['param']=='edit'?$where.=" AND nama<>'".$_POST['nama']."'":null;

            $cek_level = $this->m_crud->get_data("user_level", "nama", $where);

            if ($cek_level == null) {
                echo 'true';
            } else {
                echo 'false';
            }
        } else if ($action == 'simpan') {
            $this->db->trans_begin();
            $super = null; $access = null;
            for($i=0;$i<=$_POST['jumlah'];$i++){
                $post = $_POST[$i];
                if(empty($post)) {
                    $access .= '0';
                } else {
                    $access .= $post;
                }
                $super .= '1';
            }
            $this->m_crud->update_data($table, array('level' => $super), "id_level = '1'");

            if ($_POST['param'] == 'add') {
                $this->m_crud->create_data($table, array(
                    'nama' => $_POST['nama'],
                    'level' => $access
                ));
            } else {
                $id = $_POST['id'];

                $this->m_crud->update_data($table, array(
                    'nama' => $_POST['nama'],
                    'level' => $access
                ), "id_level = '".$id."'");
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo true;
            }
        } else if ($action == 'edit') {
            $get_data = $this->m_crud->get_data($table, "*, char_length(level) jumlah", "id_level = '".$_POST['id']."'");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_level'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'hapus') {
            $delete_data = $this->m_crud->delete_data($table, "id_level = '".$_POST['id']."'");

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
    /*End master user level*/

    /*Start master member*/
    public function member($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'member';
        $table = 'member';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Master Data';
        $data['title'] = 'Member';
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
            $where .= "nama like '%".$search."%' or id_member like '%".$search."%' or email like '%".$search."%' or telepon like '%".$search."%'";
        }

        if ($action == 'get_data') {
            $config = array();
            $config["base_url"] = "#";
            //$config["total_rows"] = $this->ajax_pagination_model->count_all();
            $config["total_rows"] = $this->m_crud->count_data($table, "id_member", $where);
            $config["per_page"] = 20;
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
            $read_data = $this->m_crud->join_data($table." m", "m.id_member, m.email, m.password, m.nama, m.jenis_kelamin, m.tgl_lahir, m.telepon, m.ol_code, m.status, m.foto, m.register,m.id_register, ifnull(sum(p.poin), 0) poin", array(array('table'=>'poin p', 'type'=>'LEFT')), array("p.member=m.id_member"), $where, null, "m.id_member", $config["per_page"], $start);
            $output .= '
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th width="1%" class="text-center">#</th>
                    
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No Registrasi</th>
                    <th>Jenis Kelamin</th>
                    <th>Tanggal Lahir</th>
                    <th>Telepon</th>
                    <th>Status</th>
                    <th>Register</th>
                    <th>Poin</th>
                    <th>ID Member</th>
                    
                </tr>
            ';
            $no = $start+1;
            if ($read_data != null) {
                foreach ($read_data as $row) {
                    if ($row['status'] == '1') {
                        $status = '<span class="label label-success">Aktif</span>';
                        $aksi = '<li><a href="#" onclick="update(\'0\', \'' . $row['id_member'] . '\')">Non Aktif</a></li>';
                    } else {
                        $status = '<span class="label label-danger">Tidak Aktif</span>';
                        $aksi = '<li><a href="#" onclick="update(\'1\', \'' . $row['id_member'] . '\')">Aktif</a></li>';
                    }

                    $aksi .= '<li><a href="#" onclick="hapus(\'' . $row['id_member'] . '\')">Hapus</a></li>';

                    $output .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Pilihan <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu dropdown-position">
                                '.$aksi.'
                            </ul>
                        </div>
                        </td>
                      
                        <td><img class="img_profile" src="' . base_url().$row['foto'] . '"></td>
                        <td>' . $row['nama'] . '</td>
                        <td>' . $row['email'] . '</td>
                        <td>' . $row['id_member'] . '</td>
                        <td>' . ($row['jenis_kelamin']=='L'?'Laki-Laki':'Perempuan') . '</td>
                        <td>' . $row['tgl_lahir'] . '</td>
                        <td>' . $row['telepon'] . '</td>
                        <td>' . $status . '</td>
                        <td>' . ucfirst($row['register']) . '</td>
                        <td>' . $row['poin'] . '</td>
                        <td>' . $row['ol_code'] . '</td>
                    </tr>
                ';
                }
            } else {
                $output .= '
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data</td>
                </tr>
                ';
            }
            $output .= '</table>';

            $read_level = $this->m_crud->read_data("user_level", "nama, id_level", "id_level!='1'");
            $list = '<option value="">Pilih Level</option>';

            if ($read_level != null) {
                foreach ($read_level as $row) {
                    $list .= '<option value="'.$row['id_level'].'">'.$row['nama'].'</option>';
                }
            }

            $result = array(
                'pagination_link' => $this->pagination->create_links(),
                'result_table' => $output,
                'user_level' => $list,
                'page' => $page
            );
            echo json_encode($result);
        } else if ($action == 'update') {
            $id = $_POST['id'];
            $data = $_POST['data'];

            $this->m_crud->update_data("member", array('status'=>$data), "id_member='".$id."'");

            echo json_encode(array('status'=>true));
        } else if ($action == 'delete') {
            $id = $_POST['id'];

            $this->db->trans_begin();

            $this->m_crud->delete_data("member", "id_member='".$id."'");

            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                $res = array('status'=>true);
            } else {
                $this->db->trans_rollback();
                $res = array('status'=>false);
            }

            echo json_encode($res);
        } else if ($action == 'cek_email') {
            $where = "email='".$_POST['email']."'";

            $cek_email = $this->m_crud->get_data("member", "email", $where);

            if ($cek_email == null) {
                echo 'true';
            } else {
                echo 'false';
            }
        } else if ($action == 'cek_telepon') {
            $where = "telepon='".$_POST['telepon']."'";

            $cek_telepon = $this->m_crud->get_data("member", "telepon", $where);

            if ($cek_telepon == null) {
                echo 'true';
            } else {
                echo 'false';
            }
        } else if ($action == 'simpan') {
            $req_api = $this->m_website->request_api_local('register_member', 'nama='.$_POST['nama'].'&email='.$_POST['email'].'&password='.$_POST['password'].'&tlp='.$_POST['telepon'].'&jk='.$_POST['jk'].'&tgl_lahir='.$_POST['tgl_lahir'].'&alamat='.$_POST['alamat'].'&user_detail='.$this->session->userdata($this->site . 'user').'');

            $decode = json_decode($req_api, true);

            if ($decode['status']) {
                $ol_code = $decode['ol_code'];

                $data_customer = array(
                    'param' => 'add',
                    'kode' => $ol_code,
                    'nama' => strtoupper($_POST['nama']),
                    'tlp' => $_POST['telepon'],
                    'tgl_lahir' => date('Y-m-d', strtotime($_POST['tgl_lahir'])),
                    'alamat' => $_POST['alamat'],
                    'email' => $_POST['email']
                );
                $this->m_website->request_api('data_customer', $data_customer);
				$this->m_website->request_api_interlocal('insert_sample','&ol_code='.$ol_code.'&nama='.strtoupper($_POST['nama']).'&email='.$_POST['email'].'&telepon='.$_POST['telepon'].'');

            }

            echo $req_api;
        } else {
            $this->load->view('bo/index', $data);
        }
    }
    /*End master member*/

    /*Start master lokasi*/
    public function lokasi($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'lokasi';
        $table = 'lokasi';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Master Data';
        $data['title'] = 'Lokasi';
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
            $config["total_rows"] = $this->m_crud->count_data($table, "id_lokasi", $where);
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
                    <th>Telepon</th>
                    <th>Jam Operasional</th>
                    <th>Alamat</th>
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
                                <li><a href="#" onclick="edit(\'' . $row['id_lokasi'] . '\')">Edit</a></li>
                                <li><a href="#" onclick="hapus(\'' . $row['id_lokasi'] . '\')">Hapus</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['nama'] . '</td>
                        <td><img class="img_profile" src="' . base_url().$row['gambar'] . '"></td>
                        <td>' . $row['tlp1'] . '</td>
                        <td>' . date('H:i', strtotime($row['jam_buka'])) . ' - ' . date('H:i', strtotime($row['jam_tutup'])) . '</td>
                        <td>' . $row['alamat'] . '</td>
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
            $row = 'gambar';
            $config['upload_path']          = './assets/images/lokasi';
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
            }
            $this->db->trans_begin();

            if ($_POST['param'] == 'add') {
                $data_lokasi = array(
                    'nama' => $_POST['nama'],
                    'tlp1' => $_POST['tlp1'],
                    'alamat' => $_POST['alamat'],
                    'jam_buka' => $_POST['jam_buka'],
                    'jam_tutup' => $_POST['jam_tutup'],
                    'lat' => $_POST['lat'],
                    'lng' => $_POST['lng']
                );
                if($_FILES[$row]['name']!=null){
                    $data_lokasi['gambar'] = 'assets/images/lokasi/'.$file[$row]['file_name'];
                }

                $this->m_crud->create_data($table, $data_lokasi);
            } else {
                $id = $_POST['id'];
                $data_lokasi = array(
                    'nama' => $_POST['nama'],
                    'tlp1' => $_POST['tlp1'],
                    'alamat' => $_POST['alamat'],
                    'jam_buka' => $_POST['jam_buka'],
                    'jam_tutup' => $_POST['jam_tutup'],
                    'lat' => $_POST['lat'],
                    'lng' => $_POST['lng']
                );
                if($_FILES[$row]['name']!=null){
                    $data_lokasi['gambar'] = 'assets/images/lokasi/'.$file[$row]['file_name'];
                }
                $this->m_crud->update_data($table, $data_lokasi, "id_lokasi='".$id."'");
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo true;
            }
        } else if ($action == 'edit') {
            $get_data = $this->m_crud->get_data($table, "*", "id_lokasi = '".$_POST['id']."'");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_lokasi'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'hapus') {
            $delete_data = $this->m_crud->delete_data($table, "id_lokasi = '".$_POST['id']."'");

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
    /*End master lokasi*/

    /*Start master bank*/
    public function bank($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'bank';
        $table = 'bank';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Master Data';
        $data['title'] = 'Data Bank';
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
            $config["total_rows"] = $this->m_crud->count_data($table, "id_bank", $where);
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
                    <th>Bank</th>
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
                                <li><a href="#" onclick="edit(\'' . $row['id_bank'] . '\')">Edit</a></li>
                                <li><a href="#" onclick="hapus(\'' . $row['id_bank'] . '\')">Hapus</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['nama'] . '</td>
                    </tr>
                ';
                }
            } else {
                $output .= '
                <tr>
                    <td colspan="3" class="text-center">Tidak ada data</td>
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
            $this->db->trans_begin();

            if ($_POST['param'] == 'add') {
                $this->m_crud->create_data($table, array('nama' => $_POST['nama']));
            } else {
                $id = $_POST['id'];
                $this->m_crud->update_data($table, array('nama'=>$_POST['nama']), "id_bank='".$id."'");
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo true;
            }
        } else if ($action == 'edit') {
            $get_data = $this->m_crud->get_data($table, "*", "id_bank = '".$_POST['id']."'");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_bank'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'hapus') {
            $delete_data = $this->m_crud->delete_data($table, "id_bank = '".$_POST['id']."'");

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
    /*End master bank*/

    /*Start master rekening bank*/
    public function rekening($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'rekening';
        $table = 'rekening';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Master Data';
        $data['title'] = 'Rekening Bank';
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
            $where .= "r.atas_nama like '%".$search."%'";
        }

        if ($action == 'get_data') {
            $config = array();
            $config["base_url"] = "#";
            //$config["total_rows"] = $this->ajax_pagination_model->count_all();
            $config["total_rows"] = $this->m_crud->count_join_data($table. " r", "r.id_rekening", "bank b", "b.id_bank=r.bank", $where);
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
            $read_data = $this->m_crud->join_data($table. " r", "r.id_rekening, r.atas_nama, r.no_rek, b.id_bank, b.nama, (SELECT COUNT(id_rekening) FROM rekening WHERE bank=b.id_bank) rows2", "bank b", "b.id_bank=r.bank", $where, "b.nama", null, $config["per_page"], $start);
            $output .= '
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th width="1%" class="text-center">#</th>
                    <th>Bank</th>
                    <th>Atas Nama</th>
                    <th>Nomor Rekening</th>
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
                                <li><a href="#" onclick="edit(\'' . $row['id_rekening'] . '\')">Edit</a></li>
                                <li><a href="#" onclick="hapus(\'' . $row['id_rekening'] . '\')">Hapus</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['nama'] . '</td>
                        <td>' . $row['atas_nama'] . '</td>
                        <td>' . $row['no_rek'] . '</td>
                    </tr>
                ';
                }
            } else {
                $output .= '
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data</td>
                </tr>
                ';
            }
            $output .= '</table>';

            $result = array(
                'pagination_link' => $this->pagination->create_links(),
                'result_table' => $output
            );
            echo json_encode($result);
        } else if ($action == 'cek_rekening') {
            $where = "no_rek='".$_POST['no_rek']."'";

            $_POST['param']=='edit'?$where.=" AND no_rek<>'".$_POST['no_rek']."'":null;

            $cek_rekening = $this->m_crud->get_data($table, "no_rek", $where);

            if ($cek_rekening == null) {
                echo 'true';
            } else {
                echo 'false';
            }
        } else if ($action == 'get_bank') {
            $result = array();
            $list = '<option value="">Pilih Bank</option>';
            $read_bank = $this->m_crud->read_data("bank", "*");

            if ($read_bank != null) {
                $result['status'] = true;
                foreach ($read_bank as $row) {
                    $list .= '<option value="'.$row['id_bank'].'">'.$row['nama'].'</option>';
                }
                $result['bank'] = $list;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'simpan') {
            $this->db->trans_begin();

            if ($_POST['param'] == 'add') {
                $this->m_crud->create_data($table, array('utama'=>'1','atas_nama' => $_POST['atas_nama'], 'no_rek' => $_POST['no_rek'], 'bank' => $_POST['bank']));
            } else {
                $id = $_POST['id'];
                $this->m_crud->update_data($table, array('utama'=>'1','atas_nama' => $_POST['atas_nama'], 'no_rek' => $_POST['no_rek'], 'bank' => $_POST['bank']), "id_rekening='".$id."'");
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo true;
            }
        } else if ($action == 'edit') {
            $get_data = $this->m_crud->get_data($table, "*", "id_rekening = '".$_POST['id']."'");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_rekening'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'hapus') {
            $delete_data = $this->m_crud->delete_data($table, "id_rekening = '".$_POST['id']."'");

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
    /*End master rekening bank*/

    /*Start master kategori_berita*/
    public function kategori_berita($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'kategori_berita';
        $table = 'kategori_berita';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Master Data';
        $data['title'] = 'Kategori Berita';
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
            $config["total_rows"] = $this->m_crud->count_data($table, "id_kategori_berita", $where);
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
            $output .= /** @lang text */'
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
                    if($row['gambar']!=null && $row['gambar']!=''){
                        $gambar = '<img style="max-height:100px;" src="' . base_url().$this->m_website->file_thumb($row['gambar']) . '" />';
                    } else {
                        $gambar = '<img style="max-height:100px;" src="' . base_url().'assets/images/no_image.png' . '" />';
                    }
                    $output .= /** @lang text */'
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Pilihan <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu dropdown-position">
                                <li><a href="#" onclick="edit(\'' . $row['id_kategori_berita'] . '\')">Edit</a></li>
                                <li><a href="#" onclick="hapus(\'' . $row['id_kategori_berita'] . '\')">Hapus</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['nama'] . '</td>
                        <td>' . $gambar . '</td>
                    </tr>
                ';
                }
            } else {
                $output .= /** @lang text */'
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
            $this->db->trans_begin();

            $path = 'assets/images/berita';
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

            $data_katagori = array('nama' => $_POST['nama'],'slug_kategori_berita'=>$this->slug($_POST['nama']));

            if($_FILES['file_upload']['name']!=null) {

                $data_katagori['gambar'] = ($_FILES['file_upload']['name']!=null)?($path.'/'.$file['file_upload']['file_name']):null;

                if($_POST['file_uploaded']!=null||$_POST['file_uploaded']!=''){
                    unlink(/*$file['file_upload']['file_path'].*/$_POST['file_uploaded']);
                    unlink($this->m_website->file_thumb(/*$file['file_upload']['file_path'].*/$_POST['file_uploaded']));
                }
            }

            if ($_POST['param'] == 'add') {
                $this->m_crud->create_data($table, $data_katagori);
            } else {
                $id = $_POST['id'];
                $this->m_crud->update_data($table, $data_katagori, "id_kategori_berita='".$id."'");
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo true;
            }
        } else if ($action == 'edit') {
            $get_data = $this->m_crud->get_data($table, "*", "id_kategori_berita = '".$_POST['id']."'");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_bank'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'hapus') {
            $delete_data = $this->m_crud->delete_data($table, "id_kategori_berita = '".$_POST['id']."'");

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
    /*End master kategori berita*/

    /*Start master berita*/
    public function berita($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'berita';
        $table = 'berita';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Master Data';
        $data['title'] = 'Data Berita';
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
            $config["total_rows"] = $this->m_crud->count_data($table, "id_berita", $where);
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
            $read_data = $this->m_crud->join_data($table.' b', "b.*, kb.nama", "kategori_berita kb", "b.kategori_berita=kb.id_kategori_berita", $where, 'b.tgl_berita desc', null, $config["per_page"], $start);
            $output .= '
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th width="1%" class="text-center">#</th>
                    <th>Judul</th>
                    <th>Tanggal</th>
                    <th>Gambar</th>
                    <th>Ringkasan</th>
                    <th>Kategori</th>
                </tr>
            ';
            $no = $start+1;
            if ($read_data != null) {
                foreach ($read_data as $row) {
                    if($row['gambar']!=null && $row['gambar']!=''){
                        $gambar = '<img style="max-height:100px;" src="' . base_url().$row['gambar']. '" />';
                    } else {
                        $gambar = '<img style="max-height:100px;" src="' . base_url().'assets/images/no_image.png' . '" />';
                    }

                    $output .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Pilihan <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu dropdown-position">
                                <!--li><a href="#" onclick="detail(\'' . $row['id_berita'] . '\')">Detail</a></li-->
                                <li><a href="#" onclick="edit(\'' . $row['id_berita'] . '\'); validasi(\'edit\');">Edit</a></li>
                                <li><a href="#" onclick="hapus(\'' . $row['id_berita'] . '\')">Hapus</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['judul'] . '</td>
                        <td>' . $row['tgl_berita'] . '</td>
                        <td>' . $gambar . '</td>
                        <td>' . substr(strip_tags($row['ringkasan']), 0, 250) . '</td>
                        <td>' . $row['nama'] . '</td>
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
        } else if ($action == 'simpan') {
            $this->db->trans_begin();

            $path = 'assets/images/berita';
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
            if($valid==true) {
                $data_berita = array(
                    'judul' => $this->m_website->replace_kutip($_POST['judul']),
                    'slug_berita' => $this->slug($this->m_website->replace_kutip($_POST['judul'])),
                    'kategori_berita' => $this->m_website->replace_kutip($_POST['kategori_berita']),
                    'tgl_berita' => isset($_POST['tanggal'])?date('Y-m-d', strtotime($_POST['tanggal'])).' '.date('H:i:s'):null,
                    'ringkasan' => $this->m_website->replace_kutip($_POST['ringkasan']),
                    'isi' => $this->m_website->replace_kutip($_POST['deskripsi'])
                );

                //$input_file array 1 file_upload
                if($_FILES['file_upload']['name']!=null) {

                    $data_berita['gambar'] = ($_FILES['file_upload']['name']!=null)?($path.'/'.$file['file_upload']['file_name']):null;

                    if($_POST['file_uploaded']!=null||$_POST['file_uploaded']!=''){
                        unlink(/*$file['file_upload']['file_path'].*/$_POST['file_uploaded']);
                        unlink($this->m_website->file_thumb(/*$file['file_upload']['file_path'].*/$_POST['file_uploaded']));
                    }
                }

                if ($_POST['param'] == 'add') {
                    $this->m_crud->create_data($table, $data_berita);
                } else {
                    $id = $_POST['id'];
                    $this->m_crud->update_data($table, $data_berita, "id_berita='".$id."'");
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
            $get_data = $this->m_crud->get_data($table, "*", "id_berita='".$_POST['id']."'");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_data'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'hapus') {
            /*$file = $this->m_crud->get_data($table, 'gambar', "id_berita = '".$_POST['id']."'");*/

            $delete_data = $this->m_crud->delete_data($table, "id_berita = '".$_POST['id']."'");

            if ($delete_data) {
                /*if($file!=null){
                    unlink($file['gambar']);
                    unlink($this->m_website->file_thumb($file['gambar']));
                }*/
                $status = true;
            } else {
                $status = false;
            }

            echo $status;
        } else if ($action == 'get_kategori') {
            $result = array();
            $list = '<option value="">Pilih Kategori</option>';
            $read_bank = $this->m_crud->read_data("kategori_berita", "*");

            if ($read_bank != null) {
                $result['status'] = true;
                foreach ($read_bank as $row) {
                    $list .= '<option value="'.$row['id_kategori_berita'].'">'.$row['nama'].'</option>';
                }
                $result['data'] = $list;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else {
            $this->load->view('bo/index', $data);
        }
    }
    /*End master berita*/

    /*Start master galeri*/
    public function galeri($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'galeri';
        $table = 'album';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Master Data';
        $data['title'] = 'Galeri';
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
            $config["total_rows"] = $this->m_crud->count_data($table, "id_album", $where);
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
            $read_data = $this->m_crud->read_data($table, "*", $where, 'tgl_album desc', null, $config["per_page"], $start);

            if ($read_data != null) {
                foreach ($read_data as $row) {
                    $get_galeri = $this->m_crud->read_data("galeri", "gambar", "album='".$row['id_album']."'", "tgl_galeri desc", null, 5);

                    $utama = '';
                    $list1 = '';
                    $list2 = '';
                    foreach ($get_galeri as $baris => $row_galeri) {
                        if ($baris == 0) {
                            $utama = '
                                <div class="col-sm-6">
                                    <img class="img-responsive" src="'.base_url().$row_galeri['gambar'].'" alt="Photo">
                                </div>
                            ';
                        } else {
                            if ($baris < 3) {
                                $list1 .= '
                                    <img class="img-responsive" src="'.base_url().$row_galeri['gambar'].'" alt="Photo">
                                ';
                                if ($baris == 1) {
                                    $list1 .= '<br>';
                                }
                            } else {
                                $list2 .= '
                                    <img class="img-responsive" src="'.base_url().$row_galeri['gambar'].'" alt="Photo">
                                ';
                                if ($baris == 3) {
                                    $list2 .= '<br>';
                                }
                            }
                        }
                    }

                    $output .= '
                    <div class="col-md-12" style="margin-bottom: 10px">
                        <div class="post">
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="user-block">
                                        <img class="img-circle img-bordered-sm" src="'.base_url().$row['gambar'].'" alt="Image">
                                        <span class="username">
                                            '.$row['nama'].'
                                        </span>
                                        <span class="description">Posted '.$this->m_crud->count_data("galeri", "id_galeri", "album='".$row['id_album']."'").' photos - '.$row['tgl_album'].'</span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Pilihan <span class="fa fa-caret-down"></span></button>
                                        <ul class="dropdown-menu dropdown-position">
                                            <li><a href="#" onclick="detail(\'' . $row['id_album'] . '\')">Detail</a></li>
                                            <li><a href="#" onclick="edit(\'' . $row['id_album'] . '\')">Edit</a></li>
                                            <li><a href="#" onclick="hapus(\'' . $row['id_album'] . '\')">Hapus</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- /.user-block -->
                            <div class="row margin-bottom">
                                '.$utama.'
                                <!-- /.col -->
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            '.$list1.'
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-sm-6">
                                            '.$list2.'
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                    <!-- /.row -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                    </div>
                ';
                }
            } else {
                $output .= '
                    <h3 class="text-center">Tidak ada data</h3>
                ';
            }
            $output .= '</table>';

            $result = array(
                'pagination_link' => $this->pagination->create_links(),
                'result_table' => $output
            );
            echo json_encode($result);
        } else if ($action == 'simpan') {
            $this->db->trans_begin();

            $row = 'gambar[]';
            $files = $_FILES['gambar'];
            $config['upload_path']          = './assets/images/galeri';
            $config['allowed_types']        = 'gif|jpg|jpeg|png|svg';
            $config['max_size']             = 5120;
            $this->load->library('upload', $config);
            $valid = true;

            if($valid==true) {
                if ($_POST['param'] == 'add') {

                } else {
                    $this->m_crud->update_data($table, array('album'=>$_POST['album']), "album='".$_POST['param']."'");
                }

                $tanggal = date('Y-m-d H:i:s');
                /*insert images*/
                $nama = $_POST['label_gambar'];
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
                            $this->m_crud->create_data("galeri", array('album' => $_POST['album'], 'tgl_galeri'=>$tanggal, 'nama'=>$nama[$key], 'gambar' => 'assets/images/galeri/' . $file[$row]['file_name']));
                        }
                    }
                }
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo $valid;
            }
        } else if ($action == 'simpan_album') {
            $this->db->trans_begin();

            $path = 'assets/images/galeri';
            $config['upload_path']          = './'.$path;
            $config['allowed_types']        = 'bmp|gif|jpg|jpeg|png';
            $config['max_size']             = 5120;
            $this->load->library('upload', $config);
            $input_file = array('1'=>'gambar');
            $valid = true;
            foreach($input_file as $row){
                if( (! $this->upload->do_upload($row)) && $_FILES[$row]['name']!=null){
                    $file[$row]['file_name']=null;
                    $file[$row] = $this->upload->data();
                    $valid = false;
                    $data['error_'.$row] = $this->upload->display_errors();
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
            if($valid==true) {
                $data_album = array(
                    'nama' => $this->m_website->replace_kutip($_POST['nama']),
                    'tgl_album' => date('Y-m-d H:i:s')
                );

                //$input_file array 1 file_upload
                if($_FILES['gambar']['name']!=null) {

                    $data_album['gambar'] = ($_FILES['gambar']['name']!=null)?($path.'/'.$file['gambar']['file_name']):null;

                    if($_POST['gambar']!=null||$_POST['gambar']!=''){
                        unlink(/*$file['file_upload']['file_path'].*/$_POST['gambar']);
                        unlink($this->m_website->file_thumb(/*$file['file_upload']['file_path'].*/$_POST['gambar']));
                    }
                }

                if ($_POST['param'] == 'add') {
                    $this->m_crud->create_data($table, $data_album);
                } else {
                    $id = $_POST['id'];
                    $this->m_crud->update_data($table, $data_album, "id_album='".$id."'");
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
            $get_data = $this->m_crud->get_data($table, "*", "id_album='".$_POST['id']."'");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_data'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'hapus') {
            /*$file = $this->m_crud->get_data($table, 'gambar', "id_berita = '".$_POST['id']."'");*/

            $delete_data = $this->m_crud->delete_data($table, "id_album = '".$_POST['id']."'");

            if ($delete_data) {
                /*if($file!=null){
                    unlink($file['gambar']);
                    unlink($this->m_website->file_thumb($file['gambar']));
                }*/
                $status = true;
            } else {
                $status = false;
            }

            echo $status;
        } else if ($action == 'get_album') {
            $result = array();
            $list = '<option value="">Pilih Album</option>';
            $read_merk = $this->m_crud->read_data("album", "*");

            if ($read_merk != null) {
                $result['status'] = true;
                foreach ($read_merk as $row) {
                    $list .= '<option value="'.$row['id_album'].'">'.$row['nama'].'</option>';
                }
                $result['album'] = $list;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'get_gambar') {
            $get_gambar = $this->m_crud->read_data("galeri", "id_galeri, nama, gambar", "album='".$_POST['id']."'");
            if ($get_gambar != null) {
                $list_gambar = '';
                foreach ($get_gambar as $baris => $row_gambar) {
                    if((($baris) % 4) == 0) {
                        $list_gambar .= '<div class="row" style="margin-top: 10px">';
                    }

                    $list_gambar .= '<div class="col-lg-3"><span class="badge bg-red topcorner" onclick="hapus_gambar(\''.$row_gambar['id_galeri'].'\')">&times;</span><img src="'.base_url().$row_gambar['gambar'].'" onclick="sweetImage(\''.base_url().$row_gambar['gambar'].'\', \''.$row_gambar['nama'].'\')" class="img_preview"></div>';

                    if((($baris+1) % 4) == 0) {
                        $list_gambar .= '</div>';
                    }
                }
            } else {
                $list_gambar = '<img src="'.base_url().'assets/images/no_image.png'.'" class="img_preview">';
            }

            echo $list_gambar;
        } else if ($action == 'hapus_gambar') {
            $delete_data = $this->m_crud->delete_data("galeri", "id_galeri = '".$_POST['id']."'");

            if ($delete_data) {
                $status = true;
            } else {
                $status = false;
            }

            echo $status;
        } else if ($action == 'detail') {
            $result = array(); $list_gambar = ''; $id = $_POST['id'];

            $get_album = $this->m_crud->get_data($table, "nama, gambar", "id_album='".$id."'");
            $get_gambar = $this->m_crud->read_data("galeri", "gambar, nama", "album='".$id."'");

            if ($get_gambar != null) {
                foreach ($get_gambar as $baris => $row_gambar) {
                    if((($baris) % 4) == 0) {
                        $list_gambar .= '<div class="row" style="margin-top: 10px">';
                    }

                    $list_gambar .= '<div class="col-lg-3"><img onclick="sweetImage(\''.base_url().$row_gambar['gambar'].'\', \''.$row_gambar['nama'].'\')" src="'.base_url().$row_gambar['gambar'].'" class="img_preview2"><h3>'.$row_gambar['nama'].'</h3></div>';

                    if((($baris+1) % 4) == 0) {
                        $list_gambar .= '</div>';
                    }
                }
            } else {
                $list_gambar = '<img src="'.base_url().'assets/images/no_image.png'.'" class="img_preview">';
            }

            $result['album'] = $get_album; $result['gambar'] = $list_gambar;

            echo json_encode($result);
        } else {
            $this->load->view('bo/index', $data);
        }
    }
    /*End master galeri*/

    /*Start master testimoni*/
    public function testimoni($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'testimoni';
        $table = 'testimoni';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Master Data';
        $data['title'] = 'Testimonial';
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
            $config["total_rows"] = $this->m_crud->count_data($table, "id_testimoni", $where);
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
                    <th>Foto</th>
                    <th>Kota</th>
                    <th>Tanggal</th>
                    <th>Testimoni</th>
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
                                <li><a href="#" onclick="edit(\'' . $row['id_testimoni'] . '\')">Edit</a></li>
                                <li><a href="#" onclick="hapus(\'' . $row['id_testimoni'] . '\')">Hapus</a></li>
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['nama'] . '</td>
                        <td><img src="' . base_url().$row['foto'] . '" style="max-height: 100px"></td>
                        <td>' . $row['kota'] . '</td>
                        <td>' . $row['tanggal'] . '</td>
                        <td>' . substr($row['testimoni'], 0, 200) . '</td>
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
        } else if ($action == 'simpan') {
            $row = 'foto';
            $config['upload_path']          = './assets/images/member';
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

            $data_testimoni = array(
                'nama' => $_POST['nama'],
                'kota' => $_POST['kota'],
                'testimoni' => $_POST['testimoni'],
                'tanggal' => date('Y-m-d')
            );

            if($_FILES[$row]['name']!=null){
                $data_testimoni['foto'] = 'assets/images/member/'.$file[$row]['file_name'];
            }

            if ($_POST['param'] == 'add') {
                $this->m_crud->create_data($table, $data_testimoni);
            } else {
                $id = $_POST['id'];
                $this->m_crud->update_data($table, $data_testimoni, "id_testimoni='".$id."'");
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo true;
            }
        } else if ($action == 'edit') {
            $get_data = $this->m_crud->get_data($table, "*", "id_testimoni = '".$_POST['id']."'");
            $result = array();

            if ($get_data != null) {
                $result['status'] = true;
                $result['res_testi'] = $get_data;
            } else {
                $result['status'] = false;
            }

            echo json_encode($result);
        } else if ($action == 'hapus') {
            $delete_data = $this->m_crud->delete_data($table, "id_testimoni = '".$_POST['id']."'");

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
    /*End master testimoni*/

    /*Start master kurir*/
    public function kurir($action=null, $page=1) {
        //$this->access_denied(11);
        $data = $this->data;
        $function = 'kurir';
        $table = 'kurir';
        $view = $this->control.'/';
        if($this->session->userdata($this->site . 'admin_menu')!=$function) {
            $this->session->unset_userdata('search');
            $this->cart->destroy();
            $this->session->set_userdata($this->site . 'admin_menu', $function);
        }
        $data['main'] = 'Master Data';
        $data['title'] = 'Data Kurir';
        $data['page'] = $function;
        $data['content'] = $view.$function;
        $data['table'] = $table;
        $where = "id_kurir <> 'jet'";

        if(isset($_POST['search'])||isset($_POST['to_excel'])) {
            $this->session->set_userdata('search', array('any' => $_POST['any']));
        }

        $search = $this->session->search['any'];
        if(isset($search)&&$search!=null) {
            ($where == null) ? null : $where .= " AND ";
            $where .= "kurir like '%".$search."%'";
        }

        if ($action == 'get_data') {
            $config = array();
            $config["base_url"] = "#";
            //$config["total_rows"] = $this->ajax_pagination_model->count_all();
            $config["total_rows"] = $this->m_crud->count_data($table, "id_kurir", $where);
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
            $read_data = $this->m_crud->read_data($table, "*", $where, "kurir", null, $config["per_page"], $start);
            $output .= '
                <table class="table table-hover">
                <tr>
                    <th width="1%">No</th>
                    <th width="1%" class="text-center">#</th>
                    <th>Kurir</th>
                    <th>Gambar</th>
                    <th>Status</th>
                </tr>
            ';
            $no = $start+1;
            if ($read_data != null) {
                foreach ($read_data as $row) {
                    if ($row['status'] == '0') {
                        $status = '<span class="label bg-red">Tidak Aktif</span>';
                        $aksi = '<li><a href="javascript:" onclick="edit(\'' . $row['id_kurir'] . '\', \'1\')"><i class="fa fa-circle-o text-green"></i> Set Aktif</a></li>';
                    } else {
                        $status = '<span class="label bg-green">Aktif</span>';
                        $aksi = '<li><a href="javascript:" onclick="edit(\'' . $row['id_kurir'] . '\', \'0\')"><i class="fa fa-circle-o text-red"></i> Set Tidak Aktif</a></li>';
                    }
                    $output .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Pilihan <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu dropdown-position">
                                '.$aksi.'
                            </ul>
                        </div>
                        </td>
                        <td>' . $row['kurir'] . '</td>
                        <td><img src="' . base_url().$row['gambar'] . '" style="max-height: 20px"></td>
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
        } else if ($action == 'simpan') {
            $this->db->trans_begin();

            $this->m_crud->update_data($table, array('status'=>$_POST['val']), "id_kurir='".$_POST['id']."'");

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo false;
            } else {
                $this->db->trans_commit();
                echo true;
            }
        } else {
            $this->load->view('bo/index', $data);
        }
    }
    /*End master kurir*/
    public function slug($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        // trim
        $text = trim($text, '-');
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // lowercase
        $text = strtolower($text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        if (empty($text))
        {
            return 'n-a';
        }
        return $text;
    }
}
