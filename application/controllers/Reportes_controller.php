<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes_controller extends CI_Controller {
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('id_usuario')){ Redirect('Login_controller');}
		$this->load->model('Control_model');
		$this->load->model('Reportes_model');
	}
	function index(){
		$data=1;
		$this->load->view('cabecera');
		$this->load->view('reportes/global', $data);
		$this->load->view('pie');
	}
	function mapa_pedidos(){
		$this->load->view('cabecera');
		$data['pedidos']=$this->Reportes_model->get_latlon_pedidos();
		$this->load->view('reportes/mapa', $data);
		$this->load->view('pie');
	}

	function get_pedidos(){
		$tdata = $this->Reportes_model->get_pedidos($_POST['fecha1'],$_POST['fecha2']);
		echo  '<table class="table table-bordered" nombre="Resumen_pedido_'.date('d/m/Y').'">
					<tr>
						<th></th>
						<th colspan="2">Brasil</th>
						<th colspan="2">San Marcos</th>
						<th></th>
					</tr>
					<tr>
						<th></th>
						<th>DOM</th>
						<th>VXEL</th>
						<th>DOM</th>
						<th>VXEL</th>
						<th></th>
					</tr>
					<tr>
						<th> APP </th>
						<td>'.$tdata[1][1][1].'</td>
						<td>'.$tdata[1][1][2].'</td>
						<td>'.$tdata[1][2][1].'</td>
						<td>'.$tdata[1][2][2].'</td>
						<th>'.($tdata[1][1][1]+$tdata[1][1][2]+$tdata[1][2][1]+$tdata[1][2][2]).'</th>
					</tr>
					<tr>
						<th> WEB </th>
						<td>'.$tdata[0][1][1].'</td>
						<td>'.$tdata[0][1][2].'</td>
						<td>'.$tdata[0][2][1].'</td>
						<td>'.$tdata[0][2][2].'</td>
						<th>'.($tdata[0][1][1]+$tdata[0][1][2]+$tdata[0][2][1]+$tdata[0][2][2]).'</th>
					</tr>
					<tr>
						<th></th>
						<th>'.($tdata[1][1][1]+$tdata[0][1][1]).'</th>
						<th>'.($tdata[1][1][2]+$tdata[0][1][2]).'</th>
						<th>'.($tdata[1][2][1]+$tdata[0][2][1]).'</th>
						<th>'.($tdata[1][2][2]+$tdata[0][2][2]).'</th>
						<th>'.($tdata[1][1][1]+$tdata[1][1][2]+$tdata[1][2][1]+$tdata[1][2][2]+$tdata[0][1][1]+$tdata[0][1][2]+$tdata[0][2][1]+$tdata[0][2][2]).'</th>
					</tr>
					<tr>
						<td></td>
						<th colspan="2">'.($tdata[1][1][1]+$tdata[0][1][1]+$tdata[1][1][2]+$tdata[0][1][2]).'</th>
						<th colspan="2">'.($tdata[1][2][1]+$tdata[0][2][1]+$tdata[1][2][2]+$tdata[0][2][2]).'</th>
						<td></td>
					</tr>
				</table>';
	}
	function get_top(){
		$top = $this->Reportes_model->get_top($_POST['fecha1'],$_POST['fecha2']);
		
		$tabla = "";
		$tabla.='<table class="table" nombre="Top_productos_'.date('d/m/Y').'">
						<thead>
							<tr>
								<th style="text-align: center">Peticiones</th>
								<th style="text-align: center">Producto</th>
							</tr>
						</thead>
						<tbody>';
						foreach($top as $t){
							$tabla.='<tr>
								<td>'.$t->veces.'</td>
								<td><a href="#" class="detalles_top" 
								prod="'.$t->producto.'" 
								f1="'.$_POST['fecha1'].'" 
								f2="'.$_POST['fecha2'].'" 
								>'.$t->descripcion.'</a></td>
							</tr>';
						}
						$tabla.='</tbody></table>';
		echo $tabla;
		
	}
	function get_agotados(){
		$agotados = $this->Reportes_model->productos_agotados($_POST['fecha1'],$_POST['fecha2']);
		
		$tabla = "";
		$tabla.='<table class="table" nombre="Productos_agotados_'.date('d/m/Y').'">
						<thead>
							<tr>
								<th style="text-align: center">Producto</th>
								<th style="text-align: center">Descripcion</th>
							</tr>
						</thead>
						<tbody>';
						foreach($agotados as $a){
							$tabla.='<tr>
								<td>'.$a->producto.'</td>
								<td>'.$a->descripcion.'</td>
							</tr>';
						}
						$tabla.='</tbody></table>';
		echo $tabla;
	}
	function detalle_producto_top(){
		$detalles = $this->Reportes_model->get_prod_d($_POST['producto'],$_POST['fecha1'],$_POST['fecha2']);
		
		$tabla = "";
		$tabla.='<table class="table" nombre="detalle_'.$detalles[0]->descripcion.'_'.date('d/m/Y').'">
						<thead>
						<tr>
							<th colspan="3">'.$detalles[0]->producto.'-'.$detalles[0]->descripcion.'</th>
						</tr>
							<tr>
								<th style="text-align: center">Detalle</th>
								<th style="text-align: center">Unidad</th>
								<th style="text-align: center">Cantidad</th>
							</tr>
						</thead>
						<tbody>';
						foreach($detalles as $d){if($d->detalles==""){$d->detalles="-";}
							$tabla.='<tr>
								<td>'.$d->detalles.'</td>
								<td>'.$d->unidad.'</td>
								<td>'.$d->cantidad.'</td>
							</tr>';
						}
						$tabla.='</tbody></table>';
		echo $tabla;
	}

	function imagenes_bascula(){
		$detalles = $this->Reportes_model->get_prod_bascula();
		$tabla = "";
		$tabla.='<table>
						<thead>
							<tr>
								<th>Producto</th>
								<th>PLU</th>
								<th>Imagen</th>
							</tr>
						</thead>
						<tbody>';
						foreach($detalles as $d){
							$tabla.='<tr>
								<td>'.$d->producto.'</td>
								<td>'.$d->plu.'</td>
								<td><img src="'.base_url("assets/img/productos/").$d->producto.'.png"></td>
							</tr>';
						}
						$tabla.='</tbody></table>';
		echo $tabla;
	}
	function crear_zip(){
		$detalles = $this->Reportes_model->get_prod_bascula();
		$imagenes = array();
		foreach($detalles as $d)
			if(existe_img_producto($d->producto)){
				$files[] = getcwd().'/assets/img/productos/'.$d->producto.".png";
				$nombres[] = $d->plu.".jpg";
			}

       # create new zip opbject
       $zip = new ZipArchive();

       # create a temp file & open it
       $tmp_file = tempnam('.','');
       $zip->open($tmp_file, ZipArchive::CREATE);

       # loop through each file

       for($i=0;$i<count($files);$i++){
           # download file
           $download_file = file_get_contents($files[$i]);
           #add it to the zip
           $zip->addFromString(basename($nombres[$i]),$download_file);

       }

       # close zip
       $zip->close();

       # send the file to the browser as a download
       header('Content-disposition: attachment; filename=Resumes.zip');
       header('Content-type: application/zip');
       readfile($tmp_file);
	}
}

/* End of file Reportes_controller.php */
/* Location: ./application/controllers/Reportes_controller.php */