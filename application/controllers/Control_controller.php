<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Control_controller extends CI_Controller {
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('id_usuario')){
			Redirect('Login_controller');
		}
		$this->load->model('Control_model');
		$this->load->model('Api_model');
	}
	function dash(){
		$data['pedidos'] = $this->Control_model->get_pedidos();
		$data['choferes'] = $this->Control_model->get_choferes();
		$this->load->view('cabecera');
		$this->load->view('pedidos/pedidos',$data);
		$this->load->view('pie');
	}
	function ligar(){
		$data['pedidos'] = $this->Control_model->get_pedidos_app_cajera();
		$this->load->view('cabecera');
		$this->load->view('pedidos/ligar',$data);
		$this->load->view('pie');
	}
	function imprimir_cierre_app(){
		$data['fecha1']=$_GET['fecha1'];
		$data['fecha2']=$_GET['fecha2'];
		$data['comprobantes']=$this->Api_model->get_comprobantes_pagos($this->session->userdata('id_sucursal'),$data['fecha1'],$data['fecha2']);
		$this->load->view('pedidos/imprimir_cierre',$data);
	}
	function comprobante(){
		$data = explode("S", $_POST['data']);
		$trans = json_decode($this->Api_model->get_comprobante($this->Api_model->get_pedido_chofer($data[0],$data[1])->id_pedido));
		if(isset($trans->BNRG_TIPO_CUENTA)){ $trans->BNRG_TIPO_CUENTA = str_replace("-", "", $trans->BNRG_TIPO_CUENTA);}
		else{ $trans->BNRG_TIPO_CUENTA = "PRIVADO";}
		echo "<table>
			<tr><td style='text-align:center;font-weight: bold;' colspan='2'> Pago en APP </td></tr>
			<tr><td>CODIGO_AUT</td><td style='text-align:right'>".$trans->BNRG_CODIGO_AUT."</td></tr>
			<tr><td>TEXTO</td><td style='text-align:right'>".$trans->BNRG_TEXTO."</td></tr>
			<tr><td>TIPO_CUENTA</td><td style='text-align:right'>".$trans->BNRG_TIPO_CUENTA."</td></tr>
			<tr><td>MARCA_TARJETA</td><td style='text-align:right'>".$trans->BNRG_MARCA_TARJETA."</td></tr>
			<tr><td>REFERENCIA</td><td style='text-align:right'>".$trans->BNRG_REFERENCIA."</td></tr>
			<tr><td>ID_MEDIO</td><td style='text-align:right'>".$trans->BNRG_ID_MEDIO."</td></tr>
			<tr><td>CODIGO_PROC</td><td style='text-align:right'>".$trans->BNRG_CODIGO_PROC."</td></tr>
			<tr><td>HORA_LOCAL</td><td style='text-align:right'>".$trans->BNRG_HORA_LOCAL."</td></tr>
			<tr><td>FECHA_LOCAL</td><td style='text-align:right'>".$trans->BNRG_FECHA_LOCAL."</td></tr>
			<tr><td>BANCO_EMISOR</td><td style='text-align:right'>".$trans->BNRG_BANCO_EMISOR."</td></tr>
			<tr><td>CODIGO_EMISOR</td><td style='text-align:right'>".$trans->BNRG_CODIGO_EMISOR."</td></tr>
			<tr><td>MONTO_TRANS</td><td style='text-align:right'>".$trans->BNRG_MONTO_TRANS."</td></tr>
			<tr><td>FOLIO</td><td style='text-align:right'>".$trans->BNRG_FOLIO."</td></tr>
			<tr><td>REF_CLIENTE1</td><td style='text-align:right'>".$trans->BNRG_REF_CLIENTE1."</td></tr>
			<tr><td>ID_PEDIDO</td><td style='text-align:right'>".$trans->ID_PEDIDO."</td></tr>
			<tr><td>ID_SUCURSAL</td><td style='text-align:right'>".$trans->ID_SUCURSAL."</td></tr>
			<tr><td>ID_CLIENTE</td><td style='text-align:right'>".$trans->ID_CLIENTE."</td></tr>
			<tr><td>BNRG_ID_AFILIACION</td><td style='text-align:right'>".$trans->BNRG_ID_AFILIACION."</td></tr>";
	}
	function inventario(){
		$data['productos'] = $this->Control_model->get_productos();
		$data['departamentos'] = $this->Control_model->get_departamentos();
		$data['subdepartamentos'] = $this->Control_model->get_subdepartamentos();
		$this->load->view('cabecera');
		$this->load->view('contenido/inventario',$data);
		$this->load->view('pie');
	}
	function historico(){
		$data['pedidos'] = $this->Control_model->get_pedidos3();
		$this->load->view('cabecera');
		$this->load->view('pedidos/pedidos_historico',$data);
		$this->load->view('pie');
	}
	function procesar_historicos(){
		$this->Control_model->procesar_historico();
		echo "1";
	}
	function cambio_sucursal(){
		$this->Control_model->cambiar_sucursal(
			$_POST['id_pedido'],
			Array(
				"id_sucursal"=>$_POST['id_sucursal'],
				"sucursal"=>$_POST['sucursal']
			)
		);
	}
	function subir_imagen_producto(){
		//echo getcwd();
		$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST['imagen_base64']));
		if(strlen($data)>1){
			file_put_contents(getcwd().'/assets/img/productos/'.$_POST['producto'].'.png', $data); 
			$source_img = getcwd().'/assets/img/productos/'.$_POST['producto'].'.png';
			$destination_img = getcwd().'/assets/img/productos/'.$_POST['producto'].'.png';
			$this->compress_img($source_img, $destination_img, 65);
			echo '1';
		}
		else{ echo '0';}
	}
	function compress_img($source, $destination, $quality) {
	    $info = getimagesize($source);
	    if ($info['mime'] == 'image/jpeg') 
	        $image = imagecreatefromjpeg($source);
	    elseif ($info['mime'] == 'image/gif') 
	        $image = imagecreatefromgif($source);
	    elseif ($info['mime'] == 'image/png') 
	        $image = imagecreatefrompng($source);
	    imagejpeg($image, $destination, $quality);
	    return $destination;
	}
	function actualizar_precios(){
		$productos = $this->Control_model->get_productos();
		$string="0";
		foreach ($productos as $p) {$string.=",".$p->producto;}
		//echo $string."<br>";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,'http://201.143.78.123:8051/ferbis-interno/index.php/API/api_Controller/get_precio');
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "productos=".$string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$respuesta = curl_exec ($ch);
		curl_close($ch);

		foreach(json_decode($respuesta,TRUE) as $r){
			$this->Api_model->editar_producto($r);
		}
		echo "Precios actualizados";
	}
	function get_subdep(){
		echo json_encode($this->Control_model->get_subdep($_POST['dep']));
	}
	function get_subdep_all(){
		echo json_encode($this->Control_model->get_subdep_all());
	}
	function nuevo_pedido_form(){
		$this->load->view('cabecera');
		$this->load->view('pedidos/nuevo_pedido2');
		$this->load->view('pie');
	}
	function nuevo_pedido_form2(){
		$data['productos'] = $this->Control_model->get_productos();
		$this->load->view('cabecera');
		$this->load->view('pedidos/nuevo_pedido',$data);
		$this->load->view('pie');
	}
	function get_cliente_telefono(){
		echo json_encode($this->Control_model->get_cliente_telefono($_POST['telefono']));
	}
	function impresor(){
		$data['pedidos'] = $this->Control_model->get_pedidos2();
		$this->load->view('cabecera');
		$this->load->view('pedidos/impresor',$data);
		$this->load->view('pie');
	}
	function imprimir_pedido(){
		$this->Control_model->imprimir_pedido($_POST['id_pedido']);
	}
	function pedido_impreso(){
		$this->Control_model->pedido_impreso($_POST['id_carrito']);
	}
	function por_imprimir_pedido(){
		echo json_encode($this->Control_model->por_imprimir_pedido());
	}
	function reduccion_imagenes(){
		//traer lista de productos para reducir imagen
		$productos = $this->Control_model->get_productos();
		foreach($productos as $p){
			$nombreIMG = $p->producto.'.png';
			$ruta = getcwd().'/assets/img/productos/'.$nombreIMG;
			$ruta2 = getcwd().'/assets/img/productos/'.$nombreIMG;


			//unlink($ruta2);
			//ver si existe su imagen
			if(file_exists($ruta)){
				unlink($ruta);
				
				$this->compress_img($ruta, $ruta2, 65);
				/*
				//reducir imagen
				$nvaImagen = $this->redimensionar_imagen($nombreIMG, $ruta, 250, 250);
				//borrar imagen anterior
				unlink($ruta);

				//guardar nueva imagen 
				imagepng($nvaImagen, $ruta);
				*/
				echo $nombreIMG." --->  exito!<br>";
			}
		}
	}
	function redimensionar_imagen($nombreimg, $rutaimg, $xmax, $ymax){  
        $ext = explode(".", $nombreimg);  
        $ext = $ext[count($ext)-1];  
      
        if($ext == "jpg" || $ext == "jpeg")  
            $imagen = imagecreatefromjpeg($rutaimg);  
        elseif($ext == "png")  
            $imagen = imagecreatefrompng($rutaimg);  
        elseif($ext == "gif")  
            $imagen = imagecreatefromgif($rutaimg);  
          
        $x = imagesx($imagen);  
        $y = imagesy($imagen);  
          
        if($x <= $xmax && $y <= $ymax){
            echo "<center>Esta imagen ya esta optimizada para los maximos que deseas.<center>";
            return $imagen;  
        }
      
        if($x >= $y) {  
            $nuevax = $xmax;  
            $nuevay = $nuevax * $y / $x;  
        }  
        else {  
            $nuevay = $ymax;  
            $nuevax = $x / $y * $nuevay;  
        }  
          
        $img2 = imagecreatetruecolor($nuevax, $nuevay);  
        imagecopyresized($img2, $imagen, 0, 0, 0, 0, floor($nuevax), floor($nuevay), $x, $y);  
        echo "<center>La imagen se ha optimizado correctamente.</center>";
        return $img2;   
    }
    function alta_temporada(){
    	$this->Control_model->alta_temporada($_POST['producto']);
    }
    function get_temporada(){
    	$temporada = $this->Control_model->get_temporada();
    	$r = "";
    	foreach ($temporada as $p) {
    		$r .= '<tr>
		      			<td>'.$p->producto.'</td>
		      			<td>'.$p->descripcion.'</td>
		      			<td><a href="#" style="color:red;" producto="'.$p->producto.'" class="borrar_temporada"><i class="fa fa-times" aria-hidden="true"></i></a></td>
		      		</tr>';
    	}
    	echo $r;
    }
    function borrar_temporada(){
    	$this->Control_model->borrar_temporada($_POST['producto']);
    }
    function busqueda(){
    	$data['pedidos'] = $this->Control_model->get_pedidos5();
    	$this->load->view('cabecera');
		$this->load->view('reportes/busqueda', $data);
		$this->load->view('pie');
    }
}

/* End of file control_controller.php */
/* Location: ./application/controllers/control_controller.php */