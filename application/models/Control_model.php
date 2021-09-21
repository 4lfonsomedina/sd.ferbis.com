<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Control_model extends CI_Model {
	function get_choferes(){
		return $this->db->get('choferes')->result();
	}
	function validar_session($user){
		$this->db->where('nombre_usuario',$user['usuario']);
		$this->db->where('clave',$user['clave']);
		if($this->db->get('usuarios')->num_rows()>0){return TRUE;}
		else{return FALSE;}
	}
	function validar_session_chofer($user){
		$this->db->where('id_chofer',$user['clave']);
		if($this->db->get('choferes')->num_rows()>0){return TRUE;}
		else{return FALSE;}
	}
	function get_usuario($user){
		$this->db->where('nombre_usuario',$user['usuario']);
		$this->db->where('clave',$user['clave']);
		return $this->db->get('usuarios')->row_array();
	}
	function cambiar_sucursal($id_pedido,$data){
		$this->db->where('id_pedido',$id_pedido);
		$this->db->update('pedidos',$data);
	}
	function get_chofer($user){
		$this->db->where('id_chofer',$user['clave']);
		return $this->db->get('choferes')->row_array();
	}
	function get_pedidos(){
		if($this->session->userdata('tipo')!=0){
			$this->db->where('id_sucursal',$this->session->userdata('id_sucursal'));
		}
		$this->db->where('fecha_entrega',date('Y-m-d'));
		$this->db->order_by('hora_entrega');
		return $this->db->get('pedidos')->result();
	}
	function get_pedidos_app(){
		if($this->session->userdata('tipo')!=0){
			$this->db->where('id_sucursal',$this->session->userdata('id_sucursal'));
		}
		$this->db->where('fecha_entrega',date('Y-m-d'));
		//$this->db->where('tomo','APP');
		$this->db->order_by('hora_entrega');
		return $this->db->get('pedidos')->result();
	}
	function get_pedidos_app_cajera(){
		if($this->session->userdata('tipo')!=0){
			$this->db->where('id_sucursal',$this->session->userdata('id_sucursal'));
		}
		$this->db->where('fecha_entrega',date('Y-m-d'));
		//$this->db->where('id_pedido','17876');
		
		$this->db->where('tomo','APP');
		$this->db->order_by('hora_entrega','DESC');
		return $this->db->get('pedidos')->result();
	}
	function get_pedidos2(){
		if($this->session->userdata('tipo')!=0){
			$this->db->where('id_sucursal',$this->session->userdata('id_sucursal'));
		}
		$this->db->order_by('id_pedido','DESC');
		$this->db->limit(1000);
		return $this->db->get('pedidos')->result();
	}
	function get_pedidos3(){
		if($this->session->userdata('tipo')!=0){
			$this->db->where('id_sucursal',$this->session->userdata('id_sucursal'));
		}
		$this->db->where('fecha_entrega !=',date('Y-m-d'));
		$this->db->order_by('fecha_entrega, hora_entrega', 'DESC');
		return $this->db->get('pedidos')->result();
	}
	function get_productos(){
		$this->db->join('departamentos','productos.departamento=id_departamento',"LEFT");
		$this->db->join('subdepartamentos','productos.subdepartamento=id_subdepartamento',"LEFT");
		$this->db->order_by('nombre_departamento');
		$this->db->order_by('nombre_subdepartamento');
		return $this->db->get('productos')->result();
	}
	function get_departamentos(){
		return $this->db->get('departamentos')->result();
	}
	function get_subdepartamentos(){
		return $this->db->get('subdepartamentos')->result();
	}
	function get_subdep($dep){
		$this->db->where('id_departamento',$dep);
		$this->db->order_by('orden');
		return $this->db->get('subdepartamentos')->result();
	}
	function get_subdep_all(){
		$this->db->join('departamentos','subdepartamentos.id_departamento=departamentos.id_departamento');
		$this->db->order_by('subdepartamentos.id_departamento, orden');
		return $this->db->get('subdepartamentos')->result();
	}
	function get_cliente_telefono($telefono){
		$this->db->where('telefono',$telefono);
		return $this->db->get('clientes')->row();
	}
	function imprimir_pedido($id_pedido){
		$this->db->where('id_pedido',$id_pedido);
		$this->db->update('pedidos',array('imprimir'=>1));
	}
	function pedido_impreso($id_carrito){
		$this->db->where('id_carrito',$id_carrito);
		$this->db->set('imprimir', 0);
		$this->db->set('impresiones', 'impresiones+1', FALSE);
		$this->db->update('pedidos');
	}
	function por_imprimir_pedido(){
		if($this->session->userdata('id_sucursal')!=0){
			$this->db->where('id_sucursal',$this->session->userdata('id_sucursal'));
		}
		$this->db->where('imprimir',1);
		return $this->db->get('pedidos')->row();
	}
	function procesar_historico(){
		//traer pedidos
		$this->db->where('fecha_entrega <',date('Y-m-d'));
		$pedidos = $this->db->get('pedidos')->result();

		//Actualizar pedidos a estatus 3
		$this->db->where('fecha_entrega <',date('Y-m-d'));
		$this->db->update('pedidos',array('status'=>'3'));

		//actualizar carritos
		$carritos = array();
		foreach($pedidos as $p){ $carritos[] = $p->id_carrito; }
		$this->db->where_in('id_carrito', $carritos);
		$this->db->update('carrito',array('status'=>'3'));
	}
	function get_encuesta(){
		$this->db->order_by("id_encuesta","DESC");
		return $this->db->get("encuesta")->result();
	}
	function get_encuesta_id($id){
		$this->db->where("id_encuesta",$id);
		return $this->db->get("encuesta")->row();
	}
	function alta_temporada($producto){
		$this->db->where("producto",$producto);
		$this->db->update('productos',array('temporada'=>'999'));
	}
	function get_temporada(){
		$this->db->where("temporada",'999');
		$this->db->order_by("producto");
		return $this->db->get('productos')->result();
	}
	function borrar_temporada($producto){
		$this->db->where("producto",$producto);
		$this->db->update('productos',array('temporada'=>'0'));
	}
	function get_pedidos5(){
		$this->db->select("servicio,consecutivo, sucursal, status, nombre, dir_colonia, dir_calle, dir_numero1, telefono, fecha_entrega, hora_entrega, origen, tomo, chofer");
		if($this->session->userdata('tipo')!=0){
			$this->db->where('id_sucursal',$this->session->userdata('id_sucursal'));
		}
		$this->db->order_by("fecha_entrega", "DESC");
		return $this->db->get('pedidos')->result();
	}

}

/* End of file control_model.php */
/* Location: ./application/models/control_model.php */