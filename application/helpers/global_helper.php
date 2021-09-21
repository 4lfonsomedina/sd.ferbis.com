<?php
function mensaje_banco($mensaje){
	if(strpos($mensaje, "Declinad")!==false){
		return array("codigo"=>0,"mensaje"=>"Operación rechazada");
	}elseif(strpos($mensaje, "Aprobad")!==false){
		return array("codigo"=>1,"mensaje"=>"Operación aprobada");
	}elseif(strpos($mensaje, "Dato de entrada")!==false){
		return array("codigo"=>0,"mensaje"=>"Datos de tarjeta Invalidos");
	}else{
		return array("codigo"=>0,"mensaje"=>$mensaje);
	}

}
function existe_img_producto($producto){
	if (file_exists(getcwd().'/assets/img/productos/'.$producto.".png")) {return TRUE;} 
	else {return FALSE;}
}
function digitos_t($string){
	return substr(json_decode(base64_decode($string))->nt, 12,16);
}
function adjuntar_imagen($vector){
	foreach ($vector as &$v){
		$v->puntuacion = base_url()."assets/img/productos/0.png";
		if(existe_img_producto($v->producto))
			$v->puntuacion = base_url()."assets/img/productos/".$v->producto.".png?".rand(0,1000);
	}
	return $vector;
}
function adjuntar_imagen2($producto){
	$producto->puntuacion = base_url()."assets/img/productos/0.png";
	if(existe_img_producto($producto->producto))
		$producto->puntuacion = base_url()."assets/img/productos/".$producto->producto.".png?".rand(0,1000);
	return $producto;
}
function show_array($array,$exit=true){
	echo "<pre>";print_r($array);echo "</pre>";
	if($exit){exit;}
}
function suma_dias($fecha,$dias){ //d/m/Y

	$fecha = explode("/", $fecha);
	$fecha = $fecha[0]."-".$fecha[1]."-".$fecha[2];
	$fecha = date("d-m-Y",strtotime($fecha."+ ".$dias." days"));
	$fecha = explode("-", $fecha);

	return $fecha[0]."/".$fecha[1]."/".$fecha[2];
}
function suma_dias2($fecha,$dias){ //Y-m-d
	$fecha = date("Y-m-d",strtotime($fecha."+ ".$dias." days"));
	return $fecha;
}
function sql_fecha($fecha){
	$r=explode("/", $fecha);
	if(count($r)==3)
		return $r[2]."-".$r[1]."-".$r[0];
	else
		return $fecha;
}
function menos_hora($hora,$resta=1){
	$r=explode(":", $hora);
	return ($r[0]-$resta).":".$r[1].":".$r[2];
}
function formato_fecha($fecha){//YYYY-mm-dd
	if($fecha==''){return "";}
	$r=explode(" ", $fecha);
	$r=explode("-", $r[0]);
	return $r[2]."/".$r[1]."/".$r[0];
}
function dia_texto($fecha){//YYYY-mm-dd
	$fecha=explode("/", formato_fecha($fecha));
	$dia_f = date("l", mktime(0, 0, 0, $fecha[1], $fecha[0], $fecha[2]));
	$r="";
	if($dia_f=="Sunday"){$r="Domingo";}
	if($dia_f=="Monday"){$r="Lunes";}
	if($dia_f=="Tuesday"){$r="Martes";}
	if($dia_f=="Wednesday"){$r="Miercoles";}
	if($dia_f=="Thursday"){$r="Jueves";}
	if($dia_f=="Friday"){$r="Viernes";}
	if($dia_f=="Saturday"){$r="Sabado";}
	return $r;
}
function dia_bonito($fecha){//YYYY-mm-dd
	return dia_texto2($fecha).explode("-",$fecha)[2];
}
function dia_texto2($fecha){//YYYY-mm-dd
	$fecha=explode("/", formato_fecha($fecha));
	$dia_f = date("l", mktime(0, 0, 0, $fecha[1], $fecha[0], $fecha[2]));
	$r="";
	if($dia_f=="Sunday"){$r="DOM";}
	if($dia_f=="Monday"){$r="LUN";}
	if($dia_f=="Tuesday"){$r="MAR";}
	if($dia_f=="Wednesday"){$r="MIE";}
	if($dia_f=="Thursday"){$r="JUE";}
	if($dia_f=="Friday"){$r="VIE";}
	if($dia_f=="Saturday"){$r="SAB";}
	return $r;
}
function hora_bonito($hora){
	$horat = explode(":",$hora);
	$hora = $horat[0];
	$min = $horat[1]; 
	if($hora==12){$hora=$hora.":".$min."pm";}
	if($hora<12){$hora=$hora.":".$min."am";}
	if($hora>12){$hora=($hora-12).":".$min."pm";}
	return $hora;
}

?>