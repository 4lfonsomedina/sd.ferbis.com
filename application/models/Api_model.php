<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_model extends CI_Model {
	function realizar_encuesta($id){
		return '0';
	}
	function alta_t($data){
		$insert_data = Array(
			"id_cliente"=>$data["id_cliente"],
			"valor"=>base64_encode(json_encode($data))
		);
		$this->db->insert("usuarios_opc3", $insert_data);
	}
	function baja_t($id_t){
		$this->db->where("id_opc3",$id_t);
		$this->db->delete("usuarios_opc3");
	}
	function get_t($id_cliente){
		$this->db->select("id_opc3,valor");
		$this->db->where("id_cliente",$id_cliente);
		$data = $this->db->get("usuarios_opc3")->result();
		foreach ($data as &$d) {
			$d->valor=digitos_t($d->valor);
		}
		return $data;
	}
	function get_t2($id_opc3){
		$this->db->select("id_opc3,valor");
		$this->db->where("id_opc3",$id_opc3);
		return json_decode(base64_decode($this->db->get("usuarios_opc3")->row()->valor));
	}
	function realizar_encuesta2($id){
		//return '1'; exit;
		if($this->cantidad_pedidos($id)>0&&$this->cantidad_encuestas($id)==0)
			return '1';
		else
			return '0';
	}
	function batch_ligar_pedidos($updata){
		$this->db->update_batch('pedidos', $updata, 'id_pedido');
	}
	function batch_ligar_pago($updata_pago){
		$this->db->where('pago_status',0);
		$this->db->update_batch('pedidos', $updata_pago, 'id_pedido');
	}
	function batch_ligar_carritos($updata_carrito){
		$this->db->update_batch('carrito', $updata_carrito, 'id_carrito');
	}
	function marcar_pagados_ayer(){
		$this->db->query("update pedidos set pago_status = '2' 
			where pago_status = '1' 
			and fecha_entrega < '".date('Y-m-d')."'"
		);
	}
	function get_pedido_por_pagar($id_cliente){
		$this->db->select("Count(*) as cantidad");
		$this->db->where('id_cliente',$id_cliente);
		$this->db->where('status <', 3);
		$this->db->where('pago_status', 1);
		$this->db->where('fecha_entrega', date('Y-m-d'));
		return $this->db->get('pedidos')->row()->cantidad;
	}
	function productos_dep($dep){
		$this->db->where('departamento',$dep);
		$this->db->where('activo',1);
		$this->db->order_by('departamento, prod_orden');
		return adjuntar_imagen($this->db->get('productos')->result());
	}
	function productos_subdep($subdep){
		$this->db->group_start();
			$this->db->where('subdepartamento',$subdep);
			$this->db->or_where('subsubdepartamento',$subdep);
			$this->db->or_where('subsubsubdepartamento',$subdep);
			$this->db->or_where('temporada',$subdep);
		$this->db->group_end();
		$this->db->where('activo',1);
		$this->db->order_by('departamento, prod_orden');
		return adjuntar_imagen($this->db->get('productos')->result());
	}
	function guardar_encuesta($data){
		//cliente
		$cliente = $this->get_cuenta($data['id_cliente']);
		$data['nombre']=$cliente->nombre;
		$data['telefono']=$cliente->telefono;
		$data['pedidos'] = $this->cantidad_pedidos($data['id_cliente']);
		$this->db->insert('encuesta',$data);
	}
	function cantidad_pedidos($id_cliente){
		return $this->db->query("select count(*) as cantidad from pedidos where status='3' and id_cliente='".$id_cliente."'")->row()->cantidad;
	}
	function cantidad_encuestas($id_cliente){
		return $this->db->query("select count(*) as cantidad from encuesta where id_cliente='".$id_cliente."'")->row()->cantidad;
	}
	function contar_carritos(){	
		//show_array($this->session->userdata());
		$this->db->select('count(*) as cantidad,status,servicio');
		if($this->session->userdata('tipo')!=0){
			$this->db->where('id_sucursal',$this->session->userdata('id_sucursal'));
		}
		$this->db->where('fecha_entrega',date('Y-m-d'));
		$this->db->order_by('id_pedido','DESC');
		$this->db->group_by('status,servicio');
		$r = $this->db->get('pedidos')->result();
		//echo $this->db->last_query();
		return $r;
	}

	function get_all_carritos_status($status,$servicio){	
		if($this->session->userdata('tipo')!=0){
			$this->db->where('id_sucursal',$this->session->userdata('id_sucursal'));
		}
		$this->db->where('status',$status);
		if($servicio!=0){
			$this->db->where('servicio',$servicio);
		}		
		$this->db->where('fecha_entrega',date('Y-m-d'));
		$this->db->order_by('hora_entrega');
		return $this->db->get('pedidos')->result();
	}

	function productos_desc($desc,$dep){
		$this->db->group_start();
		$this->db->group_start();
			$this->db->like('descripcion',$desc);
			$this->db->or_like('metadatos',$desc);
		$this->db->group_end();

		//separar la descripcion por palabras
		$descArray = explode(' ',$desc);
		//var_dump($descArray);
		if(count($descArray)>1){
			$this->db->or_group_start();
			for($i=0;$i<count($descArray);$i++)if($descArray[$i]!==''){
				$this->db->like('descripcion',$descArray[$i]);
				$this->db->or_like('metadatos',$descArray[$i]);
			}
			$this->db->group_end();
		}
		$this->db->group_end();
		

		$this->db->where('activo',1);

		if($dep!=0){
			$this->db->where('departamento',$dep);
		}
		$this->db->order_by('departamento, prod_orden');
		$r = adjuntar_imagen($this->db->get('productos')->result());
		//echo $this->db->last_query();

		return $r;
	}
	function alta_cliente($nombre){
		$this->db->insert('clientes', array('nombre'=>$nombre));
		$id_cliente = $this->db->insert_id();
		
		//Nueva notificacion
		$mensaje="Hace más de 20 años en FERBIS soñamos con la idea de ofrecer productos sanos y de excelente sabor, que vinieran de los campos hasta nuestras casas y cambiaran tu concepto de calidad.<br><br>

Para lógralo, todos los días cuidamos con integridad el proceso de selección de todos nuestros productos. Así una simple idea se ha convertido en una idea cumplida.<br><br>

Para nosotros lo único más importante que la calidad es el compromiso de ofrecerla, mejorando día a día nuestro servicio al cliente, es por eso que nos complace compartir, tu aplicación móvil con la que podrás realizar pedidos de una manera fácil y rápida desde la comodidad de tu hogar, a domicilio o recoger en tu tienda más cercana.";

		$this->alta_notificacion($id_cliente,"¡Bienvenido(a)!",$mensaje);

		$this->db->where('id_cliente', $id_cliente);
		return $this->db->get('clientes')->result();
	}
	function alta_cliente_telefono($telefono){
		$this->db->insert('clientes', array('telefono'=>$telefono));
		return $this->db->insert_id();
	}
	function get_notificaciones($id_cliente){
		$this->db->select('id_notificacion,id_cliente,asunto,leido,fecha');
		$this->db->where('id_cliente', $id_cliente);
		$this->db->order_by('id_notificacion', "DESC");
		return $this->db->get('notificaciones')->result();
	}
	function get_notificacion($id_notificacion){
		$this->db->where('id_notificacion', $id_notificacion);
		$this->db->update('notificaciones',array('leido'=> 1));
		$this->db->where('id_notificacion', $id_notificacion);
		return $this->db->get('notificaciones')->row();
	}
	function get_num_notificaciones($id_cliente){
		$this->db->select('count(*)as notificaciones');
		$this->db->where('id_cliente', $id_cliente);
		$this->db->where('leido', 0);
		return $this->db->get('notificaciones')->row()->notificaciones;
	}
	function actualizar_session($id){
		$this->db->select(" * , '0' as link_banner");              //////////////////////////////////////////////// ACTIVAR BANNER
		$this->db->where('id_cliente', $id);
		return $this->db->get('clientes')->result();
	}
	function agregar_carrito($data){
		//ver si tiene carrito abierto estatus 0
		$this->db->select("id_carrito");
		$this->db->where('id_cliente',$data['id_cliente']);
		$this->db->where('status',0);
		$carrito = $this->db->get("carrito")->result();
		if(count($carrito)>0){
			$id_carrito = $carrito[0]->id_carrito;
		}else{
			$this->db->insert('carrito', array('id_cliente'=>$data['id_cliente']));
			$id_carrito = $this->db->insert_id();
		}
		$data = array_merge($data,array('id_carrito' => $id_carrito));
		$this->db->insert('carrito_det', $data);
		return $this->db->query("select count(*)as cantidad from carrito_det where id_carrito='".$data['id_carrito']."' group by id_carrito")->row()->cantidad;
	}
	function get_carrito_activo($id){
		$this->db->select("id_carrito");
		$this->db->where('id_cliente',$id);
		$this->db->where('status',0);
		$carrito = $this->db->get("carrito");
		if($carrito->num_rows()>0){
			$this->db->select('carrito_det.*,productos.peso_promedio');
			$this->db->where('id_carrito',$carrito->row()->id_carrito);
			$this->db->where('id_cliente',$id);
			$this->db->join('productos','carrito_det.producto=productos.producto');
			$this->db->order_by("id_carrito_det");
			return adjuntar_imagen($this->db->get("carrito_det")->result());
		}else{
			return 0;
		}
	}
	function rem_carrito($id){
		$this->db->where('id_carrito_det',$id);
		$this->db->delete("carrito_det");
	}
	function editar_carrito($data){
		$this->db->where('id_carrito_det',$data['id_carrito_det']);
		$this->db->update('carrito_det',$data);
	}
	function pedido_surtido($id_pedido){
		//traer id del carrito
		$this->db->select('id_carrito');
		$this->db->where('id_pedido',$id_pedido);
		$id_carrito = $this->db->get('pedidos')->row()->id_carrito;

		//actualizar carrito
		$this->db->where('id_carrito',$id_carrito);
		$this->db->where('status',0);
		$this->db->update('carrito_det',array('status'=>1));

	}
	function verificar_status_carrito($id_carrito_det){
		$this->db->where('id_carrito_det',$id_carrito_det);
		$carrito_det = $this->db->get('carrito_det')->row();
		$id_carrito=$carrito_det->id_carrito;
		$id_cliente=$carrito_det->id_cliente;

		$this->db->where('id_carrito',$id_carrito);
		$this->db->where('status',0);
		$resultado = $this->db->get('carrito_det');
		if($resultado->num_rows()>0){//aun hay productos sin procesar
			return 0;
		}else{//ya todos los productos fueron procesados hay que cambiar el estatus del carrito a 2 - surtido y enviar notificacion

			$this->db->where('id_carrito',$id_carrito);
			$this->db->update('carrito',array('status'=>2));
			$this->db->where('id_carrito',$id_carrito);
			$this->db->update('pedidos',array(
				'status'=>2,
				'fecha_surtido'=>date('Y-m-d'),
				'hora_surtido'=>date('H:i:s')
			));
			$mensaje="Ha cambiado el estatus de su pedido a surtido, en un momento recibirá una llamada para informarle el total exacto de este, para ver sus artículos surtidos a detalle puede entrar a menú->Mis Pedidos";
			$this->alta_notificacion($id_cliente,"Pedido Surtido",$mensaje);
			return 1;
		}
	}
	function verificar_status_carrito2($id_pedido){
		$this->db->where('id_pedido',$id_pedido);
		$pedido = $this->db->get('pedidos')->row();
		$id_carrito=$pedido->id_carrito;
		$id_cliente=$pedido->id_cliente;

		//echo $id_carrito." ".$id_cliente;

		$this->db->where('id_carrito',$id_carrito);
		$this->db->where('status',0);
		$resultado = $this->db->get('carrito_det');

		//echo $this->db->last_query();
		
		if($resultado->num_rows()>0){//aun hay productos sin procesar
			return 0;
		}else{//ya todos los productos fueron procesados hay que cambiar el estatus del carrito a 2 - surtido y enviar notificacion\
			$this->db->where('id_carrito',$id_carrito);
			$this->db->update('carrito',array('status'=>2));
			$this->db->where('id_carrito',$id_carrito);
			$this->db->update('pedidos',array(
				'status'=>2,
				'fecha_surtido'=>date('Y-m-d'),
				'hora_surtido'=>date('H:i:s')
			));
			$mensaje="Ha cambiado el estatus de su pedido a surtido, en un momento recibirá una llamada para informarle el total exacto de este, para ver sus artículos surtidos a detalle puede entrar a menú->Mis Pedidos";

			$this->alta_notificacion($id_cliente,"Pedido Surtido",$mensaje);
			
			return 1;
		}
	}
	function alta_notificacion($id_cliente,$asunto,$mensaje){
		$this->db->insert('notificaciones', array('id_cliente'=>$id_cliente, 'asunto'=>$asunto,'mensaje' => $mensaje,'fecha'=>date('Y-m-d'))); 
	}
	function get_carritos($id){
		$this->db->select('pedidos.sucursal, pedidos.consecutivo, pedidos.id_pedido, pedidos.pago_status, pedidos.pago_ticket, pedidos.pago_total, carrito.fecha, carrito.status, carrito.id_carrito, carrito.id_cliente, sum(carrito_det.cantidad*carrito_det.precio) as total, count(*) as cantidad, pedidos.fecha_entrega, pedidos.hora_entrega');
		$this->db->join('carrito_det','carrito.id_carrito=carrito_det.id_carrito');
		$this->db->join('pedidos','carrito.id_carrito=pedidos.id_carrito');
		$this->db->group_by('carrito.id_carrito,carrito.id_cliente');
		$this->db->where('carrito.id_cliente',$id);
		$this->db->order_by('carrito.id_carrito', 'DESC');
		return $this->db->get("carrito")->result();
	}
	function get_pedido($id){
		$this->db->where('id_pedido',$id);
		return $this->db->get("pedidos")->row();
	}
	function get_pedido_chofer($sucursal,$consecutivo){
		$this->db->where('id_sucursal',$sucursal);
		$this->db->where('consecutivo',$consecutivo);
		$this->db->order_by("id_pedido","DESC");
		$this->db->limit(1);
		return $this->get_pedido($this->db->get("pedidos")->row()->id_pedido);
	}
	function get_carritos_id($id){
		$this->db->select('carrito_det.*,productos.unidad as unidad_2,productos.precio as precio_2, productos.peso_promedio');
		$this->db->join('productos','carrito_det.producto=productos.producto');
		$this->db->where('id_carrito',$id);
		return $this->db->get("carrito_det")->result();
	}
	function guardar_token($id,$token){
		$this->db->where('id_cliente',$id);
		$this->db->update('clientes',array('token'=>$token));
	}
	function re_ordenar($data){
		//ver si tiene carrito abierto estatus 0
		$this->db->select("id_carrito");
		$this->db->where('id_cliente',$data[0]->id_cliente);
		$this->db->where('status',0);
		$carrito = $this->db->get("carrito")->result();
		if(count($carrito)>0){
			$id_carrito = $carrito[0]->id_carrito;
		}else{
			$this->db->insert('carrito', array('id_cliente'=>$data[0]->id_cliente));
			$id_carrito = $this->db->insert_id();
		}
		// re-escribir el id_carrito y actualizar precios
		foreach($data as &$d){
			$d->id_carrito=$id_carrito;
			unset($d->id_carrito_det);
			unset($d->status);
			if($d->unidad!=$d->unidad_2){
				$d->precio=$d->precio_2*$d->peso_promedio;
			}else{
				$d->precio=$d->precio_2;
			}
			unset($d->precio_2);
			unset($d->unidad_2);
			unset($d->peso_promedio);
			
		}
		$this->db->insert_batch('carrito_det', $data);
		return $this->db->query("select count(*)as cantidad from carrito_det where id_carrito='".$data['id_carrito']."' group by id_carrito")->row()->cantidad;
	}
	function get_carritos_departamento($id){
		$this->db->where('id_carrito',$id);
		$this->db->join('departamentos',"departamento = id_departamento","LEFT");
		$this->db->order_by('nombre_departamento,id_carrito_det');
		return $this->db->get("carrito_det")->result();
	}
	function get_cuenta($id_cliente){
		$this->db->where('id_cliente',$id_cliente);
		return $this->db->get("clientes")->row();
	}
	function actualizar_cuenta($data){
		$this->db->where('id_cliente',$data['id_cliente']);
		$this->db->update('clientes',$data);
	}
	function alta_pedido($data){
		if(!isset($data['pago'])){
			$data['pago']='0';
		}
		//actualizar carrito
		$this->db->where("id_carrito",$data['id_carrito']);
		$this->db->update('carrito', array('status'=>'1'));

		//actualizar los datos del cliente
		$this->db->where("id_cliente",$data['id_cliente']);
		$data2=array(
			"numero"=>$data['numero'],
			"nombre"=>$data['nombre'],
			"telefono"=>$data['telefono'],
			"dir_colonia"=>$data['dir_colonia'],
			"dir_calle"=>$data['dir_calle'],
			"dir_numero1"=>$data['dir_numero1'],
			"dir_numero2"=>$data['dir_numero2'],
			"referencia"=>$data['referencia'],
			"lat"=>$data['lat'],
			"lon"=>$data['lon'],
			"ultima_compra"=>date('Y-m-d'),
			"servicio"=>$data['servicio'],
			"pago"=>$data['pago']
		);
		$this->db->update("clientes",$data2);

		//si el pedido es desde la aplicacion se manda a imprimir automaticamente
		if(isset($data['origen'])){$data['imprimir']=1;}

		//fecha y hora de levantamiento de pedido
		$data['fecha']=date('Y-m-d');
		$data['hora']=date('H:i:s');

		//Consulto el consecutivo
		$this->db->select("consecutivo");
		$this->db->where("id_sucursal",$data['id_sucursal']);
		$data['consecutivo']=$this->db->get("sucursales")->row()->consecutivo;
		//insertar pedido
		$this->db->insert('pedidos', $data);

		//aumento en uno el consecutivo
		$this->db->where("id_sucursal",$data['id_sucursal']);
		$this->db->update("sucursales",array("consecutivo"=>($data['consecutivo']+1)));
		
		return "1";
	}
	function sucursal_cercana($coordenadas){
		//taer las coordenadas de las dos sucursales
		$sucursales = $this->db->get("sucursales")->result();
		$brasil = $sucursales[0];
		$sanmarcos = $sucursales[1];

		//calculo de distancias
		$distancia_brasil = $this->calcular_distancia($coordenadas['lat'], $coordenadas['lon'], $brasil->lat, $brasil->lon);
		$distancia_sanmarcos = $this->calcular_distancia($coordenadas['lat'], $coordenadas['lon'], $sanmarcos->lat, $sanmarcos->lon);

		//identificar la menor
		$id_sucursal=$brasil->id_sucursal;
		$sucursal=$brasil->sucursal;
		$distancia=$distancia_brasil;
		if($distancia_sanmarcos<$distancia_brasil){
			$id_sucursal=$sanmarcos->id_sucursal;
			$sucursal=$sanmarcos->sucursal;
			$distancia=$distancia_sanmarcos;
		}

		return array('id_sucursal'=>$id_sucursal,'sucursal'=>$sucursal,'distancia'=>$distancia);
	}
	function calcular_distancia($point1_lat, $point1_long, $point2_lat, $point2_long, $decimals = 2) {
		$degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));
		$distance = $degrees * 111.13384;
		return round($distance, $decimals);
	}
	function editar_pedido($data){
		
		//estatus anterior
		$this->db->select('status');
		$this->db->where('id_pedido',$data['id_pedido']);
		$status_anterior = $this->db->get('pedidos',$data)->row()->status;


		//calculo de cambio de estatus
		if($status_anterior!=$data['status']&&$data['status']=='2'){
			$data['fecha_surtido']=date('Y-m-d');
			$data['hora_surtido']=date('H:i:s');
		}
		if($status_anterior!=$data['status']&&$data['status']=='3'){
			$data['fecha_envio']=date('Y-m-d');
			$data['hora_envio']=date('H:i:s');
		}

		//actualizar pedido
		$this->db->where('id_pedido',$data['id_pedido']);
		$this->db->update('pedidos',$data);

		//actualizar carrito
		$this->db->select('id_carrito,id_cliente,status');
		$this->db->where('id_pedido',$data['id_pedido']);
		$pedido = $this->db->get('pedidos',$data)->row();
		$id_carrito = $pedido->id_carrito;
		$id_cliente = $pedido->id_cliente;

		$this->db->where('id_carrito',$id_carrito);
		$this->db->update('carrito',array('status'=>$data['status']));

		//mensaje
		if($data['status']=='3'){ 
			$mensaje="Tu pedido se encuentra en camino para mas detalles puede entrar a menú->Mis Pedidos";
			$this->alta_notificacion($id_cliente,"Pedido en camino",$mensaje);
		}
	}
	function editar_producto($data){
		$this->db->where('producto',$data['producto']);
		$this->db->update('productos',$data);

		$this->db->select('producto,descripcion,nombre_departamento,nombre_subdepartamento,activo');
		$this->db->where('producto',$data['producto']);
		$this->db->join('departamentos','productos.departamento=id_departamento',"LEFT");
		$this->db->join('subdepartamentos','productos.subdepartamento=id_subdepartamento',"LEFT");
		return $this->db->get('productos')->result();
	}
	function alta_producto($data){
		$this->db->insert('productos',$data);
	}
	function existe_producto($producto){
		$this->db->where('producto',$producto);
		if($this->db->get('productos')->num_rows()>0){return TRUE;}
		else{return FALSE;}
	}
	function get_subdepartamentos($dep){
		$this->db->where('id_departamento',$dep);
		$this->db->order_by('orden');
		return $this->db->get('subdepartamentos')->result();
	}
	function get_producto($producto){
		$this->db->where('producto',$producto);
		$resultado = $this->db->get('productos');
		if($resultado->num_rows()>0){
			return adjuntar_imagen2($resultado->row());
		}else{
			return $resultado->row();
		}
	}
	function get_producto_avattia($producto){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,'http://201.143.78.123:8051/ferbis-interno/index.php/API/api_Controller/get_producto');
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "producto=".$producto);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$respuesta = curl_exec ($ch);
		if(!$respuesta){
			echo curl_error($ch);
		}
		//echo curl_error($ch);
		curl_close ($ch);
		//echo $respuesta;
		return $respuesta;
	}
	function get_ticket_avattia($sucursal,$ticket){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,'http://201.143.78.123:8051/ferbis-interno/index.php/API/api_Controller/get_ticket');
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "sucursal=".$sucursal."&ticket=".$ticket);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$respuesta = curl_exec ($ch);
		if(!$respuesta){
			echo curl_error($ch);
		}
		//echo curl_error($ch);
		curl_close ($ch);
		//echo $respuesta;
		return $respuesta;
	}
	function envio_sms($mensaje,$telefono){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,'http://201.143.78.123:8051/ferbis-interno/index.php/API/api_Controller/envio_sms');
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "mensaje=".$mensaje."&telefono=".$telefono);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$respuesta = curl_exec ($ch);
		if(!$respuesta){
			echo curl_error($ch);
		}
		//echo curl_error($ch);
		curl_close ($ch);
		//echo $respuesta;
		return $respuesta;
	}
	function get_comprobante($id_pedido){
		$this->db->where("id_pedido",$id_pedido);
		$this->db->order_by("id_transfer","DESC");
		$this->db->limit(1);
		return $this->db->get("transferencias")->row()->resultado;
	}
	function get_comprobantes_pagos($id_sucursal,$fecha1,$fecha2){
		if($id_sucursal!=0){$this->db->where('id_sucursal',$id_sucursal);}
		$this->db->where("Date(fecha) >=",$fecha1);
		$this->db->where("Date(fecha) <=",$fecha2);
		return $this->db->get("transferencias")->result();
	}
	function transferencia($data){
		$this->db->insert('transferencias',$data);
	}

	function procesar_pago_tarjeta($data){
		$produccion=1;
		$afiliacion=array('8090005','8541836'); //pruebas / correspondientes
		$medio=array('RzZr2zfH','F105YBN3'); //pruebas / correspondientes
		$modo=array('RND','PRD');
		$url_hub=array('https://testhub.banregio.com/adq/','https://colecto.banregio.com/adq/');

		$data_post="BNRG_CMD_TRANS=VENTA".
			"&BNRG_ID_AFILIACION=".$afiliacion[$produccion].
			"&BNRG_ID_MEDIO=".$medio[$produccion].
			"&BNRG_FOLIO=".date('dmy').$data['hora'].
			"&BNRG_HORA_LOCAL=".$data['hora'].
			"&BNRG_FECHA_LOCAL=".$data['fecha'].
			"&BNRG_MODO_ENTRADA=MANUAL".
			"&BNRG_MODO_TRANS=".$modo[$produccion].
			"&BNRG_MONTO_TRANS=".$data['importe'].
			"&BNRG_NUMERO_TARJETA=".$data['tarjeta'].
			"&BNRG_FECHA_EXP=".$data['expiracion'].
			"&BNRG_CODIGO_SEGURIDAD=".$data['cvv'].
			"&BNRG_REF_CLIENTE1=".$data['cliente'].
			"&BNRG_IDIOMA_SALIDA=ES".
			"&BNRG_URL_RESPUESTA=https://sd.ferbis.com/index.php/api_controller/comprobante_pago".
			"&BNRG_REF_TRANS_PREVIA=PEDIDOFERBIS".$data['id_pedido'];
		//echo $data_post;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url_hub[$produccion]);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_post);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_exec($ch);
		$respuesta_url = curl_getinfo($ch)['redirect_url']."&ID_PEDIDO=".$data['id_pedido']."&ID_SUCURSAL=".$data['id_sucursal']."&ID_CLIENTE=".$data['id_cliente'];
		$respuesta_url = str_replace(" ", "-", $respuesta_url);
		curl_close($ch);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $respuesta_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$respuesta = curl_exec($ch);
		curl_close($ch);
		return $respuesta;
	}
	function actualizar_pago_pedido($id_pedido,$referencia){
		$this->db->where("id_pedido",$id_pedido);
		$this->db->update("pedidos", array("pago_referencia"=>$referencia,"pago_status"=>'2'));
	}
	function ligar_pedido_ticket($sucursal,$consecutivo,$ticket,$total){
		$data = Array(
			"pago_ticket"=>$ticket,
			"pago_total"=>$total,
			"pago_status"=>1
		);
		$consecutivo=$consecutivo+0;
		$this->db->where('id_sucursal',$sucursal);
		$this->db->where('consecutivo',$consecutivo);
		$this->db->update('pedidos',$data);
	}
	function eliminar_producto($producto){
		$this->db->where('producto',$producto);
		$this->db->delete('productos');
	}

	function alta_carrito($data){
		$this->db->insert('carrito',$data);
		return $this->db->insert_id();
	}
	function alta_det_carrito($data){
		$this->db->insert_batch('carrito_det', $data);
	}
	function calcular_hora_entrega($id_sucursal){
		$this->db->select("count(*) as cantidad_pedidos, fecha_entrega as fecha, hour(hora_entrega) as hora");
		$this->db->group_by('fecha_entrega, hour(hora_entrega)');
		$this->db->order_by('fecha_entrega, hora_entrega');
		$this->db->where('id_sucursal',$id_sucursal);
		$this->db->where('status <',3);
		return $this->db->get('pedidos')->result();
	}
	function calcular_hora_entrega2($id_sucursal,$dia){
		$this->db->select("count(*) as cantidad_pedidos, fecha_entrega as fecha, hora_entrega");
		$this->db->group_by('fecha_entrega, hora_entrega');
		$this->db->order_by('fecha_entrega, hora_entrega');
		$this->db->where('id_sucursal',$id_sucursal);
		$this->db->where('fecha_entrega',$dia);
		$this->db->where('status <',3);
		$r = $this->db->get('pedidos')->result();
		//echo $this->db->last_query();
		return $r;
	}
	
}

/* End of file Api_model.php */
/* Location: ./application/models/Api_model.php */