<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Encuesta_controller extends CI_Controller {

	public function __construct(){
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
		$this->load->model('Control_model');
	}
	
	public function index(){
		$data['encuesta'] = $this->Control_model->get_encuesta();

		$this->load->view('cabecera');
		$this->load->view('reportes/encuesta',$data);
		$this->load->view('pie');
	}
	function encuesta($id){
		$data['encuesta'] = $this->Control_model->get_encuesta_id($id);
		echo $this->load->view('reportes/encuesta_view',$data,TRUE);
	}

}

/* End of file Encuesta_controller.php */
/* Location: ./application/controllers/Encuesta_controller.php */