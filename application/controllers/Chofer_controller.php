<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chofer_controller extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		/*
		if(!$this->session->userdata('id_chofer')){
			if(!$this->session->userdata('id_chofer')){
				Redirect('Login_controller/soy_chofer');
			}
		}
		*/
		$this->load->model('Control_model');
		$this->load->model('Api_model');
	}
	function kiosko_chofer(){
		$data['pedidos'] = $this->Control_model->get_pedidos_app();
		$data['no_menu']=TRUE;
		$this->load->view('cabecera',$data);
		$this->load->view('pedidos/kiosko');
		$this->load->view('pie');
	}
	function get_ticket_avattia_chofer(){
		$data = explode("S", $_POST['data']);
		if($data[0]=='1'){$sucursal='brasil';}
		if($data[0]=='2'){$sucursal='sanmarcos';}
		$total = preg_replace('/\s+/', ' ', trim($this->Api_model->get_ticket_avattia($sucursal,$data[2])));
		if($total==0||strlen($data[2])<4){
			echo json_encode(array(
				"codigo"=>"0",
				"mensaje"=>"Ticket no reconocido"
			)); 
			exit;
		}
		$p = $this->Api_model->get_pedido_chofer($data[0],$data[1]);
		echo json_encode(array(
			"id_pedido"=>$p->id_pedido,
			"codigo"=>"1",
			"sucursal"=>$data[0],
			"consecutivo"=>$data[1],
			"ticket"=>$data[2],
			"total"=>$total,
			"nombre"=>$p->nombre,
			"direccion"=>$p->dir_calle." #".$p->dir_numero1.", ".$p->dir_colonia,
			"lat"=>$p->lat,
			"lon"=>$p->lon,
		));
	}
	function relacionar_choferes(){
		
	}
	

}

/* End of file chofer_controller.php */
/* Location: ./application/controllers/chofer_controller.php */