<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$data['slider'] = $this->m_crud->read_data("home_slide", "*");
		$data['bestSeller'] = $this->m_crud->join_data("bestsellers bs", "bs.*,pr.*,gp.*,dp.*", array("produk pr","gambar_produk gp","det_produk dp"), array("bs.produk=pr.id_produk","pr.id_produk=gp.produk","pr.id_produk=dp.produk"));
//		$data['kelompok'] = $this->m_crud->read_data("kelompok kl", "kl.id_kelompok,kl.nama");
		$data['content'] = 'fo/home/index';
		$this->load->view('fo/index',$data);
	}


	public function produk($action=null,$page){
		$where = null;
		$response = array();
		$table = 'produk pr';
		$field = "pr.*,gp.*,dp.*";
		$join = array("gambar_produk gp","det_produk dp");
		$on = array("pr.id_produk=gp.produk","pr.id_produk=dp.produk");
//		$pagin = $this->M_website->myPagination('join',"$table.id_$table","$join","$on",$where,6);
		if($action=='load_data' && $page=='home'){
			if($_POST['id_groups']!=null||$_POST['id_groups']!=''){
				($where!=null)?$where.=' AND ':null;
				$where.='pr.';
			}
			$read_data = $this->m_crud->join_data("$table","$field", array("gambar_produk gp","det_produk dp"),array("pr.id_produk=gp.produk","pr.id_produk=dp.produk"),$where,"pr.id_produk DESC",NULL,6);
			$result = '';
			if($read_data!=null){
				foreach($read_data as $row){
					$result.='
					<div class="col-xl-4 col-lg-4 col-md-6">
						<div class="single-product mb-60">
							<div class="product-img">
								<img src="'.base_url().$row["gambar"].'" alt="">
								<div class="new-product">
									<span>New</span>
								</div>
							</div>
							<div class="product-caption">
								<div class="product-ratting">
									<i class="far fa-star"></i>
									<i class="far fa-star"></i>
									<i class="far fa-star"></i>
									<i class="far fa-star low-star"></i>
									<i class="far fa-star low-star"></i>
								</div>
								<h4><a href="#">'.$row["nama"].'</a></h4>
								<div class="price">
									<ul>
										<li>'.$row["hrg_jual"].'</li>
										<li class="discount">'.$row["hrg_sebelum"].'</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					';
				}
			}else{
				$result.=$this->m_website->noData();
			}
			$response=array("result"=>$result,"msg"=>"berhasil","status"=>"success");
		}

		echo json_encode($response);


	}



}
