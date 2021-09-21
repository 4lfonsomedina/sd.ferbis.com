<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_controller extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Control_model');
		$this->load->model('Api_model');
		$this->Api_model->marcar_pagados_ayer();
	}
	function index(){
		$this->session->sess_destroy();
		$this->load->view('login');
	}
	function validar_session(){
		if($this->Control_model->validar_session($_POST)){
			$this->session->set_userdata($this->Control_model->get_usuario($_POST));
			Redirect('Control_controller/dash');
		}else{
			Redirect('Login_controller/index?error=1');
		}
	}
	function soy_chofer(){
		$this->session->sess_destroy();
		$this->load->view('login_chofer');
	}
	function validar_chofer(){
		if($this->Control_model->validar_session_chofer($_POST)){
			$this->session->set_userdata($this->Control_model->get_chofer($_POST));
			Redirect('Chofer_controller/kiosko_chofer');
		}else{
			Redirect('Login_controller/soy_chofer?error=1');
		}
	}
}

/* End of file login_controller.php */
/* Location: ./application/controllers/login_controller.php */