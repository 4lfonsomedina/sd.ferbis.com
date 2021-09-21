<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_controller extends CI_Controller {
	public function __construct(){
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
		$this->load->model('Api_model');
	}
	function guardar_token(){
		echo $this->Api_model->guardar_token($_POST['id'],$_POST['token']);
	}
	function alta_tarjeta(){
		echo $this->Api_model->alta_t($_POST);
	}
	function baja_tarjeta(){
		echo $this->Api_model->baja_t($_POST['id_t']);
	}
	function get_t(){
		echo json_encode($this->Api_model->get_t($_POST['id']));
	}
	/*
	function consulta_transferencia(){

	}*/
	function chofer_pedidos_listos(){
		$updata=Array();
		$updata_carrito=Array();
		$updata_pago=Array();
		for($i=0;$i<count($_POST['id_pedido']);$i++){
			$updata[]=Array(
				"id_pedido"=>$_POST['id_pedido'][$i],
				"status"=>"3",
				"fecha_envio"=>date('Y-m-d'),
				"hora_envio"=>date('H:i:s'),
				"chofer"=>$_POST['chofer']
			);
			//actualizar pago desde APP
			$updata_pago[]=Array(
				"id_pedido"=>$_POST['id_pedido'][$i],
				"pago_ticket"=>$_POST['ticket'][$i],
				"pago_total"=>$_POST['total'][$i],
				"pago_status"=>"1"
			);
			//actualizacion_carrito
			$pedido = $this->Api_model->get_pedido($_POST['id_pedido'][$i]);
			$updata_carrito[]=Array(
				"id_carrito"=>$pedido->id_carrito,
				"status"=>"3"
			);
		}
		//actualizar estatus de pago solo si el estatus es cero
		$this->Api_model->batch_ligar_pedidos($updata);
		$this->Api_model->batch_ligar_pago($updata_pago);
		$this->Api_model->batch_ligar_carritos($updata_carrito);
		
		//show_array($updata);
		/*
		foreach($updata as $p){
			$pedido = $this->Api_model->get_pedido($p['id_pedido']);
			$telefono = $pedido->telefono;
			$mensaje="Su Pedido Ferbis se encuentra en camino";
			
			if($pedido->origen=='1'||$pedido->origen){
				$mensaje.=", puedes realizar el pago desde la aplicacion en Menu > Mis Pedidos";
			}
			$this->Api_model->envio_sms($mensaje,$telefono);
			sleep(1);
		}
		*/
		
	}
	function comprobante_pago(){
		$this->Api_model->transferencia(array(
			"resultado"		=>json_encode($_GET),
			"id_cliente"	=>$_GET['ID_CLIENTE'],
			"id_sucursal"	=>$_GET['ID_SUCURSAL'],
			"id_pedido"		=>$_GET['ID_PEDIDO']
		));
		echo json_encode($_GET);
	}
	function pago_tarjeta(){
		//datos de tarjeta get_t2
		$tarjeta = $this->Api_model->get_t2($_POST['id_tarjeta']);
		$pedido = $this->Api_model->get_pedido($_POST['id_pedido']);
		$data=array(
			"id_pedido" => $_POST['id_pedido'],
			"importe" => $_POST['importe'],
			"tarjeta" => $tarjeta->nt,
			"expiracion" => $tarjeta->em.$tarjeta->ea,
			"cvv" => $_POST['cvv'],
			"fecha" => date('dmY'),
			"hora" => date('His'),
			"id_cliente" => $pedido->id_cliente,
			"id_sucursal" => $pedido->id_sucursal,
			"cliente" => substr($pedido->nombre, 0, 29)
		);
		//var_dump($data);exit;
		$respuesta = json_decode($this->Api_model->procesar_pago_tarjeta($data));
		$traduccion = mensaje_banco($respuesta->BNRG_TEXTO);
		//marcar pedido como pagado en caso de afirmativo
		if($traduccion['codigo']==1){
			$this->Api_model->actualizar_pago_pedido($_POST['id_pedido'],$respuesta->BNRG_REFERENCIA);
		}
		echo json_encode($traduccion);
	}
	function get_pedidos_por_pagar(){
		echo $this->Api_model->get_pedido_por_pagar($_POST['id_cliente']);
	}
	function realizar_encuesta(){
		echo $this->Api_model->realizar_encuesta($_POST['id']);
	}
	function realizar_encuesta2(){
		echo $this->Api_model->realizar_encuesta2($_POST['id']);
	}
	function guardar_encuesta(){
		$this->Api_model->guardar_encuesta($_POST);
		echo "1";
	}
	function get_productos_dep(){
		echo json_encode($this->Api_model->productos_dep($_POST['dep']));
	}
	function get_productos_subdep(){
		echo json_encode($this->Api_model->productos_subdep($_POST['subdep']));
	}
	function editar_producto(){
		echo json_encode($this->Api_model->editar_producto($_POST));
	}
	function alta_producto(){
		$this->Api_model->alta_producto($_POST);
	}
	function eliminar_producto(){
		$this->Api_model->eliminar_producto($_POST['producto']);
	}
	function get_producto(){
		echo json_encode($this->Api_model->get_producto($_POST['producto']));
	}
	function get_producto_avattia(){
		echo $this->Api_model->get_producto_avattia($_POST['producto']);
	}
	function get_ticket_avattia(){
		$data = explode("S", $_POST['pedido']);
		if($data[0]=='1'){$sucursal='brasil';}
		if($data[0]=='2'){$sucursal='sanmarcos';}
		$total = $this->Api_model->get_ticket_avattia($sucursal,$_POST['ticket']);
		if($total==0){echo "No encontrado"; exit;}
		$this->Api_model->ligar_pedido_ticket($data[0],$data[1],$_POST['ticket'],$total);
		$telefono = $this->Api_model->get_pedido_chofer($data[0],$data[1])->telefono;
		$mensaje="Tu Pedido Ferbis esta listo. ya puedes realizar el pago desde la aplicacion. Menu > Mis Pedidos";
		//$this->Api_model->envio_sms($mensaje,$telefono);
		echo "1";
	}
	
	function envio_sms2(){
		$telefono = $this->Api_model->get_pedido_chofer('1','1S13158')->telefono;
		$mensaje="Tu Pedido Ferbis se encuentra listo. ya puedes realizar el pago desde la aplicacion. Menu > Mis Pedidos";
		$this->Api_model->envio_sms($mensaje,$telefono);
	}
	function get_productos_filtro(){
		$dep=0; if(isset($_POST['dep'])){ $dep=$_POST['dep']; }
		echo json_encode($this->Api_model->productos_desc($_POST['desc'],$dep));
	}
	function alta_cliente(){
		echo json_encode($this->Api_model->alta_cliente($_POST['nombre']));
	}
	function alta_cliente_telefono(){
		echo $this->Api_model->alta_cliente_telefono($_POST['telefono']);
	}
	function actualizar_session(){
		echo json_encode($this->Api_model->actualizar_session($_POST['id']));
	}
	function agregar_producto_pedido_activo(){
		echo $this->Api_model->agregar_carrito($_POST);
	}
	function get_carrito_activo(){
		echo json_encode($this->Api_model->get_carrito_activo($_POST['id_cliente']));
	}
	function remover_carrito(){
		$this->Api_model->rem_carrito($_POST['id_carrito_det']);
	}
	function editar_carrito(){
		$this->Api_model->editar_carrito($_POST);
		//verificar si ya cambio el estatus de todos los productos del carrito
		echo $this->Api_model->verificar_status_carrito($_POST['id_carrito_det']);
	}
	function pedido_surtido(){
		$this->Api_model->pedido_surtido($_POST['id_pedido']);
		echo $this->Api_model->verificar_status_carrito2($_POST['id_pedido']);
	}
	function get_pedido(){
		echo json_encode($this->Api_model->get_pedido($_POST['id']));
	}
	function get_carritos(){
		echo json_encode($this->Api_model->get_carritos($_POST['id']));
	}
	function get_carritos_id(){
		echo json_encode($this->Api_model->get_carritos_id($_POST['id']));
	}
	function get_carritos_departamento(){
		echo json_encode($this->Api_model->get_carritos_departamento($_POST['id']));
	}
	function datos_cuenta(){
		echo json_encode($this->Api_model->get_cuenta($_POST['id_cliente']));
	}
	function actualizar_cuenta(){
		$this->Api_model->actualizar_cuenta($_POST);
	}
	function get_num_notificaciones(){
		echo $this->Api_model->get_num_notificaciones($_POST['id_cliente']);
	}
	function get_notificaciones(){
		echo json_encode($this->Api_model->get_notificaciones($_POST['id_cliente']));
	}
	function get_notificacion(){
		echo json_encode($this->Api_model->get_notificacion($_POST['id_notificacion']));
	}
	function alta_pedido(){
		echo $this->Api_model->alta_pedido($_POST);
	}
	function verificar_horario(){
		//$fecha_perdido=$_POST['fecha'];
		$datetime1 = new DateTime($_POST['fecha']);
		$datetime2 = new DateTime(date('Y-m-d H:i:s'));
		$interval = $datetime1->diff($datetime2);
		$limite_minutos=60;
		$minutos_total=($interval->y*525600)+($interval->m*438000)+($interval->d*1440)+($interval->h*60)+$interval->i;
		if($minutos_total<$limite_minutos){
			echo "Error en la fecha y horario de tu pedido.";
		}else{
			echo "1";
		}
	}
	function editar_pedido(){
		$this->Api_model->editar_pedido($_POST);
	}
	function sucursal_cercana(){
		echo json_encode($this->Api_model->sucursal_cercana($_POST));
	}
	function get_subdepartamentos(){
		echo json_encode($this->Api_model->get_subdepartamentos($_POST['dep']));
	}
	function alta_carrito(){
		echo $this->Api_model->alta_carrito($_POST);
	}
	function re_ordenar(){
		//traer contenido de carrito
		$this->Api_model->re_ordenar($this->Api_model->get_carritos_id($_POST['id_carrito']));

		//$this->Api_model->agregar_carrito($_POST);
	}
	function get_cantidad_pedidos(){
		echo json_encode($this->Api_model->contar_carritos());
	}

	function get_all_carritos(){
		$data['pedidos']=$this->Api_model->get_all_carritos_status($_POST['status'],$_POST['servicio']);
		$this->load->view('pedidos/pedidos_status',$data);
	}



	function alta_det_carrito(){
		$data=Array();
		foreach ($_POST['id_carrito'] as $i => $carrito) {
			$data[] = array(
			                'id_carrito' => $_POST['id_carrito'][$i],
			                'id_cliente' => $_POST['id_cliente'][$i],
			                'producto' => $_POST['producto'][$i],
			                'unidad' => $_POST['unidad'][$i],
			                'departamento' => $_POST['departamento'][$i],
			                'cantidad' => $_POST['cantidad'][$i],
			                'precio' => $_POST['precio'][$i],
			                'descripcion' => $_POST['descripcion'][$i],
			                'asado' => $_POST['asado'][$i],
			                'detalles' => $_POST['detalles'][$i],
			                'preparado' => $_POST['preparado'][$i],
			                'termino' => $_POST['termino'][$i],
			                'corte' => $_POST['corte'][$i],
			        );
		}
		$this->Api_model->alta_det_carrito($data);
	}
	function view_test_curl(){
	

		echo '<script src="https://sd.ferbis.com/assets/js/jquery.js"></script>

		<a href="http://201.143.78.123:8051/ferbis-interno/index.php/API/api_Controller/test_curl">http://201.143.78.123:8051/ferbis-interno/index.php/API/api_Controller/test_curl</a>
		<br><br><br>

		<button id="test_button"> TEST - - ></button><br>
		<br>Respuesta:<br>
		<textarea id="respuesta" rows="5"></textarea>
		<br><br><br><p>Codigo PHP de test_curl:<br><br>
		$ch = curl_init();<br><br>
		curl_setopt($ch, CURLOPT_URL,"http://201.143.78.123:8051/ferbis-interno/index.php/API/api_Controller/test_curl");<br><br>
		$respuesta = curl_exec ($ch);<br><br>
		if(!$respuesta){<br><br>
			echo "Error: ".curl_error($ch);<br><br>
		}else{<br><br>
			echo $respuesta;<br><br>
		}<br><br>
		curl_close ($ch);<br><br>

		</p>
		<script>
			$("#test_button").click(function(){
				$.post("../Api_controller/test_curl",function(r){
					$("#respuesta").val(r);
				})
			})
			
		</script>
		';

	}
	function test_curl(){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,'http://201.143.78.123:8051/ferbis-interno/index.php/API/api_Controller/test_curl');
		$respuesta = curl_exec ($ch);
		if(!$respuesta){
			echo "Error: ".curl_error($ch);
		}else{
			echo $respuesta;
		}
		curl_close ($ch);
		//echo $respuesta;
	}


// Brasil 		10 pedidos por hora de 9am a 19pm (10 lugares de 10 pedidos total 100)
// SanMarcos 	7 pedidos por hora de 9am a 19pm (10 lugares de 7 pedidos total 100)
// En ambos casos, si los pedidos tienen asado estos solo se pueden programar de 12 en adelante, siguiendo el mismo criterio.
// Traer todos los pedidos agrupados por hora, y definir que horas se tiene cupo... ordenado por fecha y hora.
// la primera posicion corresponde a la hora mas cercana para poder entregar
// todas las horas se calculan a partir de la hora en que se consulta
// ejemplo si calculo a las 8 am se recibira como si fueran las 9 para entregar a las 10 si hay cupo si no a las 11 y asi sucesivamente
// si se desea programar la hora esta solo se podra seleccionar en el horario con disponibilidad y si tiene asado a partir de las 12
// si pasa por el el pedido debe estar disponible en 1 hora (redondeado) aplica igual si tiene asado	
// si el pedido es despues de las 5pm este solo se puede programar al dia siguiente ya que no hay personal que lo surta

	function calcular_hora_entrega(){
		// corroborar la fuente
		$fuente="app";
		if(isset($_POST['fuente'])&&$_POST['fuente']=="llamada"){
			$fuente="llamada";
		}
		// recibimos las variables para calcular la fecha de entrega
		$dia = date('Y-m-d');if($_POST['dia']!=""){$dia = $_POST['dia'];}
		if($dia<date('Y-m-d')&&$fuente!="llamada"){
			echo "No hay servicio en el dia seleccionado";exit;
		}
		$sucursal 	= $_POST['id_sucursal'];
		$asado 		= $_POST['asado'];

		// Ajustamos la hora: si se pide antes de las 9 y despues de las 17
		$hora = date("H");
		// si el pedido lo hacen en la madrugada lo tomamos como si entrara a las 8am o se esta consultando un dia diferente al actual
		if($hora<9||$dia!=date('Y-m-d')){$hora=8;}
		// si el pedido se hace despues de las 5pm este se programa al dia siguiente
		if($hora>=17&&$dia==date('Y-m-d')&&$fuente!="llamada"){
			echo "No hay servicio en el dia seleccionado";exit;
		}
		// si el pedido contiene algun asado este se programa de las 12 en adelante (si hay cupo)
		if($asado>0){$hora=11;}
		
		//validamos si ese dia hay asados
		if($asado>0&&dia_texto($dia)!='Jueves'&&dia_texto($dia)!='Viernes'&&dia_texto($dia)!='Sabado'&&dia_texto($dia)!='Domingo'){
			echo "El dia ".$dia." no hay asados, solo de Jueves a Domingo.";exit;
		}
		
		//si tiene mas de 30 minutos se le suman 3 hrs
		$suma = 2; if($hora>8&&date("i")>29){$suma=3;}
		
		
		//consultamos los pedidos del dia
		$carrilla = $this->Api_model->calcular_hora_entrega2($sucursal,$dia);

		$max=10;if($sucursal=='2'){$max=6;}
		//$max=15;if($sucursal=='2'){$max=15;}
		$horario_retorno = array();
		
		$horario = array("");
		$hora_maxima = 18;
		for($i=$hora+$suma;$i<=$hora_maxima;$i++){
			$disponible = 's';
			$pedidos = 0;
			$nice = "am";if($i>11){$nice="pm";}
			$inice = $i;if($i>12){$inice=$i-12;}

			//validacion para hora
			foreach($carrilla as $c){
				if($c->hora_entrega==$i.":00:00"){
					
					$pedidos = $c->cantidad_pedidos;
					if($c->cantidad_pedidos>=$max){
						$disponible='n';
					}
				}
			}
			
			$horario_retorno[] = array(
				'fecha' => $dia,
				'hora' => $i.":00",
				'hora_nice' => $inice.":00 ".$nice,
				'pedidos' => $pedidos,
				'disponible' => $disponible
			);
			
			$disponible='s';

			//validacion para cada media
			foreach($carrilla as $c)if($c->hora_entrega==$i.":30:00"){
				$pedidos = $c->cantidad_pedidos;
				if($c->cantidad_pedidos>=$max){
					$disponible='n';
				}
			}
			if($i!=$hora_maxima)$horario_retorno[] = array(
				'fecha' => $dia,
				'hora' => $i.":30",
				'hora_nice' => $inice.":30 ".$nice,
				'pedidos' => $pedidos,
				'disponible' => $disponible
			);
		}
		//show_array();
		echo json_encode($horario_retorno);
	}

	function calcular_hora_entrega2(){
		// corroborar la fuente
		$fuente="app";
		if(isset($_POST['fuente'])&&$_POST['fuente']=="llamada"){
			$fuente="llamada";
		}
		// recibimos las variables para calcular la fecha de entrega
		$dia = date('Y-m-d');if($_POST['dia']!=""){$dia = $_POST['dia'];}
		if($dia<date('Y-m-d')&&$fuente!="llamada"){
			echo "No hay servicio en el dia seleccionado";exit;
		}
		$sucursal 	= $_POST['id_sucursal'];
		$asado 		= $_POST['asado'];

		// Ajustamos la hora: si se pide antes de las 9 y despues de las 17
		$hora = date("H");
		// si el pedido lo hacen en la madrugada lo tomamos como si entrara a las 8am o se esta consultando un dia diferente al actual
		if($hora<9||$dia!=date('Y-m-d')){$hora=8;}
		// si el pedido se hace despues de las 5pm este se programa al dia siguiente
		if($hora>=17&&$dia==date('Y-m-d')&&$fuente!="llamada"){
			echo "No hay servicio en el dia seleccionado";exit;
		}
		// si el pedido contiene algun asado este se programa de las 12 en adelante (si hay cupo)
		if($asado>0){$hora=11;}
		//validamos si ese dia hay asados
		if($asado>0&&dia_texto($dia)!='Jueves'&&dia_texto($dia)!='Viernes'&&dia_texto($dia)!='Sabado'&&dia_texto($dia)!='Domingo'){
			echo "El dia ".$dia." no hay asados, solo de Jueves a Domingo.";exit;
		}
		//si tiene mas de 30 minutos se le suman 3 hrs
		$suma = 2; if($hora>8&&date("i")>29){$suma=3;}
		
		
		//consultamos los pedidos del dia
		$carrilla = $this->Api_model->calcular_hora_entrega2($sucursal,$dia);

		//$max=10;if($sucursal=='2'){$max=6;}
		$max=15;if($sucursal=='2'){$max=15;}
		$horario_retorno = array();
		
		$horario = array("");
		$hora_maxima = 18;
		for($i=$hora+$suma;$i<=$hora_maxima;$i++){
			$disponible = 's';
			$pedidos = 0;
			$nice = "am";if($i>11){$nice="pm";}
			$inice = $i;if($i>12){$inice=$i-12;}

			//validacion para hora
			foreach($carrilla as $c){
				if($c->hora_entrega==$i.":00:00"){
					
					$pedidos = $c->cantidad_pedidos;
					if($c->cantidad_pedidos>=$max){
						$disponible='n';
					}
				}
			}
			
			$horario_retorno[] = array(
				'fecha' => $dia,
				'hora' => $i.":00",
				'hora_nice' => $inice.":00 ".$nice,
				'pedidos' => $pedidos,
				'disponible' => $disponible
			);
			
			$disponible='s';

			//validacion para cada media
			foreach($carrilla as $c)if($c->hora_entrega==$i.":30:00"){
				$pedidos = $c->cantidad_pedidos;
				if($c->cantidad_pedidos>=$max){
					$disponible='n';
				}
			}
			if($i!=$hora_maxima)$horario_retorno[] = array(
				'fecha' => $dia,
				'hora' => $i.":30",
				'hora_nice' => $inice.":30 ".$nice,
				'pedidos' => $pedidos,
				'disponible' => $disponible
			);
		}
		//show_array();
		echo json_encode($horario_retorno);
	}

	function marketing(){
/*
		echo "<!DOCTYPE html>
				<html>
				<head>
				    <title></title>
				</head>
				<body>

				<h4 style='text-align:center;'>
				<img src='https://ferbis.com/sorteo/assets/imagenes/logo.png' style='width:20%'><br><br>
				Redireccionando a plataforma del sorteo </h4>
				</body>
				<footer>
				    <script type='text/javascript'>
				    //$('.contenedor_marketing').load('https://ferbis.com/sorteo/');
				        setTimeout(function() {
				        	
				           window.location.href='https://ferbis.com/sorteo/';
				        }, 1000);
				    </script>
				</footer>
				</html>";
*/
		echo "<center style='padding-top:20px;'>".
		"<img style='width:90%;' src='".base_url('assets/img/APP/tarjeta1.jpg')."'>".
		"<img style='width:90%;' src='".base_url('assets/img/APP/tarjeta2.jpg')."'>".
		"<img style='width:90%;' src='".base_url('assets/img/APP/tarjeta3.jpg')."'>".
		"<img style='width:90%;' src='".base_url('assets/img/APP/tarjeta4.jpg')."'>".
		"<img style='width:90%;' src='".base_url('assets/img/APP/tarjeta5.jpg')."'>".
		"<img style='width:90%;' src='".base_url('assets/img/APP/tarjeta6.jpg')."'>".
			"</center>";
		

/*
		echo "

<br><br><br>
<div style='width:100%; text-align:center; font-size:20px; padding:15px;'>
<a id='android' class='device' href='https://m.facebook.com/story.php?story_fbid=4020786107953473&id=423427031022750&sfnsn=scwspwa'><img src='https://sd.ferbis.com/assets/img/FB.png' width='100px'><br>
Abrir publicación de sorteo en Facebook y participa</a>
	<br>
</div>
<script>

if( /iPhone|iPad/i.test(navigator.userAgent) ) {
   console.log('IOS');
   $('#android').attr('href','https://m.facebook.com/story.php?story_fbid=4020786107953473&id=423427031022750&sfnsn=scwspwa');
}

if(/Android/i.test(navigator.userAgent)){
	console.log('ANDROID');
}

</script>";

		/*
		echo "<center style='padding-top:20px;'> <img style='width:90%; border-radius: 20px;' src='".base_url('assets/img/APP/navidad2020-2.jpg')."'>".
		"<br><h6>Reserva tu cena en tu sucursal más cercana.<br> o llama para reservar:<br>".
		'<div class="col-xs-6 num_tienda"><a href="tel:6865653623"><i class="fa fa-phone-square fa-2x" aria-hidden="true"></i> (686)5653623</a></div>
			<div class="col-xs-6 num_tienda"><a href="tel:6865643061"><i class="fa fa-phone-square fa-2x" aria-hidden="true"></i> (686)5643061</a></div>
			<div class="col-xs-6 num_tienda"><a href="tel:6865649201"><i class="fa fa-phone-square fa-2x" aria-hidden="true"></i> (686)5649201</a></div>
			<div class="col-xs-6 num_tienda"><a href="tel:6865633212"><i class="fa fa-phone-square fa-2x" aria-hidden="true"></i> (686)5633212</a></div>'.
		" </h6>".
		"</center>";
*/
		
	}

}

/* End of file Api_controller.php */
/* Location: ./application/controllers/Api_controller.php */