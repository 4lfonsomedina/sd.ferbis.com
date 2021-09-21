<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes_model extends CI_Model {

	function get_pedidos($fecha1,$fecha2){
		$this->db->select('origen,id_sucursal,servicio,count(*) as cantidad');
		$this->db->where("fecha_entrega >=",$fecha1);
		$this->db->where("fecha_entrega <=",$fecha2);
		$this->db->group_by("origen,sucursal,servicio");
		$pedidos = $this->db->get('pedidos')->result();

		/*
		1 APP
		0 plataforma 

		1 brasil
		2 san marcos

		1 a domicilio
		2 viene por el
		*/

		$dtable = array();

		//Plataforma
		$dtable[0][1][1]=0;
		$dtable[0][1][2]=0;
		$dtable[0][2][1]=0;
		$dtable[0][2][2]=0;
		//APP
		$dtable[1][1][1]=0;
		$dtable[1][1][2]=0;
		$dtable[1][2][1]=0;
		$dtable[1][2][2]=0;

		foreach ($pedidos as $p) {
			$dtable[$p->origen][$p->id_sucursal][$p->servicio]=$p->cantidad;
		}

		return $dtable;
	}
	function get_latlon_pedidos(){
		$this->db->select('lat,lon,count(*) as cant');
		$this->db->group_by('lat');
		return $this->db->get('pedidos')->result();
	}
	function get_top($fecha1,$fecha2){
		//traer los id_carritos
		$this->db->select('id_carrito');
		$this->db->where("fecha_entrega >=",$fecha1);
		$this->db->where("fecha_entrega <=",$fecha2);
		$carritos_q = $this->db->get('pedidos')->result();
		$carritos_array=array();
		foreach ($carritos_q as $c) {$carritos_array[]=$c->id_carrito;}

		$this->db->select('count(*) as veces, producto, descripcion');
		$this->db->where_in("id_carrito",$carritos_array);
		$this->db->group_by("producto");
		$this->db->limit(10);
		$this->db->order_by('veces','DESC');
		
		return $this->db->get('carrito_det')->result();
	}
	function productos_agotados($fecha1,$fecha2){
		$this->db->select('id_carrito');
		$this->db->where("fecha_entrega >=",$fecha1);
		$this->db->where("fecha_entrega <=",$fecha2);
		$carritos_q = $this->db->get('pedidos')->result();
		$carritos_array=array();
		foreach ($carritos_q as $c) {$carritos_array[]=$c->id_carrito;}

		$this->db->select('producto, descripcion');
		$this->db->where_in("id_carrito",$carritos_array);
		$this->db->where("status","2");
		$this->db->group_by("producto");
		return $this->db->get('carrito_det')->result();
	}
	function get_prod_d($producto,$fecha1,$fecha2){
		//traer los id_carritos
		$this->db->select('id_carrito');
		$this->db->where("fecha_entrega >=",$fecha1);
		$this->db->where("fecha_entrega <=",$fecha2);
		$carritos_q = $this->db->get('pedidos')->result();
		$carritos_array=array();
		foreach ($carritos_q as $c) {$carritos_array[]=$c->id_carrito;}

		$this->db->select('producto, descripcion, cantidad, detalles, unidad, precio');
		$this->db->where_in("id_carrito",$carritos_array);
		$this->db->where("producto",$producto);
		
		return $this->db->get('carrito_det')->result();
	}
	function get_prod_bascula(){
		$this->db->select('producto,plu');
		$this->db->where('departamento','005');		
		return $this->db->get('productos')->result();
	}
}

/* End of file Reportes_model.php */
/* Location: ./application/models/Reportes_model.php */