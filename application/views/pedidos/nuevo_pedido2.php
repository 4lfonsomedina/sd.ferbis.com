<ul class="nav nav-tabs pedido_nav">
  <li class="active li_paso li_paso1" paso="1"><a data-toggle="tab" href="#cliente"><i class="fa fa-user-o fa-2x" aria-hidden="true"></i></a></li>
  <li class="li_paso li_paso2" paso="2"><a data-toggle="tab" href="#pedido"><i class="fa fa-list-ul fa-2x" aria-hidden="true"></i></a></li>
  <li class="li_paso li_paso3" paso="3"><a data-toggle="tab" href="#finalizar"><i class="fa fa-check-square-o fa-2x" aria-hidden="true"></i><span class="cantidad_articulos"></span></a></li>
</ul>

<div class="tab-content">
<div id="cliente" class="tab-pane fade in active">
<div class="col-sm-8 cabecera_pedido">
	<div class="panel panel-default">
		<div class="panel-body formulario_pedido">
            <form id="form_cliente_pedido">
                <input type="hidden" name="id_cliente" id="id_cliente">
			<div class="col-xs-8"><input id="pedido_telefono" type="number" class="form-control" name="telefono" placeholder="Telefono" autofocus></div>
            <div class="col-xs-4"><input id="pedido_frec" type="text" class="form-control" placeholder="#ClieFrec" name="numero"></div>
			<div class="col-xs-12"><input id="pedido_nombre" type="text" class="form-control" placeholder="Nombre" name="nombre"></div>
			<div class="row">
			<div class="col-xs-3" style="text-align: right;">A domiciolio</div>
			<div class="col-xs-3"><input type="radio" name="servicio" class="form-control radio_tipo" name="tipo" value="1" checked></div>
			<div class="col-xs-3"  style="text-align: right;">Paso por el</div>
			<div class="col-xs-3"><input type="radio" name="servicio" class="form-control radio_tipo" name="tipo" value="2"></div>
			</div>
            <div class="pedido_div_direccion">
			 <div class="col-xs-8"><input id="pedido_calle" type="text" class="form-control pedido_dir" placeholder="Calle" name="dir_calle"></div>
    		  <div class="col-xs-2"><input id="pedido_numero1" type="number" class="form-control pedido_dir" placeholder="#Ext" name="dir_numero1"></div>
                <div class="col-xs-2"><input id="pedido_numero2" type="number" class="form-control pedido_dir" placeholder="#Int" name="dir_numero2"></div>
    		  <div class="col-xs-6"><input id="pedido_colonia" type="text" class="form-control pedido_dir" placeholder="Colonia" name="dir_colonia"></div>
              <div class="col-xs-6 pedido_sucursal_cercana" style="padding-top: 20px;text-align: right; font-weight: bold;"></div>
              
            </div>
            <button type="submit" class="btn btn-success"> Siguiente > </button>
            </form>
		</div>
	</div>
</div>
<div class="col-sm-4" id="map_pedido" style="height: 330px;"></div>
</div>

<div id="pedido" class="tab-pane fade">
	<div class="col-sm-12 buscador_pedido"> 
        <div class="panel-heading cabecera_buscador">
            <input type="text" class="form-control input_search" placeholder="Buscar" id="input_search">
        </div>
		<div class="panel panel-default">
			<div class="panel-body panel_contenedor_articulos">
                <form id="form_pedido_articulos">
			     <div id="contenedor_articulos"></div>
                </form>
            </div>
		</div>
	</div>
</div>

<!-- FORMULARIO PARA ALTA DE CARRITO, DET_CARRITO Y PEDIDO-->

<div id="finalizar" class="tab-pane fade">

    <form id="pedido_form">
    <input type="hidden" name="id_carrito"  id="form_pedido_id_carrito"><!---->
    <input type="hidden" name="id_cliente"  id="form_pedido_id_cliente"><!---->

    <input type="hidden" name="id_sucursal" value="<?= $this->session->userdata('id_sucursal') ?>" id="form_pedido_id_sucursal">
    <?php 
        if($this->session->userdata('id_sucursal')=='2'){$sucursal="San Marcos";}
        if($this->session->userdata('id_sucursal')=='1'){$sucursal="Brasil";} 
    ?>
    <input type="hidden" name="sucursal" value="<?= $sucursal ?>">

    <input type="hidden" name="distancia"   id="form_pedido_distancia">
    <input type="hidden" name="dir_colonia" id="form_pedido_dir_colonia">
    <input type="hidden" name="dir_calle"   id="form_pedido_dir_calle">
    <input type="hidden" name="dir_numero1" id="form_pedido_dir_numero1">
    <input type="hidden" name="dir_numero2" id="form_pedido_dir_numero2">
    <input type="hidden" name="referencia"  id="form_pedido_referencia">
    <input type="hidden" name="lat"         id="form_pedido_lat">
    <input type="hidden" name="lon"         id="form_pedido_lon">
    <input type="hidden" name="nombre"      id="form_pedido_nombre">
    <input type="hidden" name="telefono"    id="form_pedido_telefono">
    <input type="hidden" name="numero"      id="form_pedido_numero">
    <input type="hidden" name="pago"        id="form_pedido_pago" value='1'>
    <input type="hidden" name="articulos"   id="form_pedido_articulos">
    <input type="hidden" name="servicio"    id="tipo_sevicio" value="1">
    <input type="hidden" name="total"       id="res_total_input">
    <input type="hidden" name="origen"      id="2">


    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-xs-12" style="text-align: center"><b>Resumen de pedido</b></div>
                <div class="col-xs-12">
                    <table class="table">
                        <tr><td class="res_nombre">Alfonso Medina Duran</td><td class="res_numero">12345678</td></tr>
                        <tr><td class="res_telefono">6861222068</td><td class="res_total">200.00</td></tr>
                    </table> 
                    <div class="col-xs-12 tipo_servicio_resumen" style="text-align: center; font-weight: bold;">
                        Servicio a domicilio
                    </div>
                    <div class="pedido_div_direccion">
                    <table class="table">
                        <tr class="pedido_div_direccion">
                            <td class="res_dir_calle">Artemisa</td>
                            <td class="res_dir_numero1">4245</td>
                            <td class="res_dir_numero2">0000</td>
                        </tr>
                        <tr class="pedido_div_direccion">
                            <td colspan="2" class="res_dir_colonia">Victoria Resindancial</td>
                            <td class="pedido_sucursal_cercana"></td>
                        </tr>
                    </table>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 fecha_entrega"><input type="date" id="fecha_pedido" class="form-control" value="<?= date('Y-m-d') ?>" name="fecha_entrega"></div>
                        <div class="col-xs-6 hora_entrega"><select id="select_horas_disponibles" class="form-control" name="hora_entrega"></select></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<form id="carrito_det_form">
	<div class="col-xs-6">
		<div class="panel panel-default">
			<div class="contenido_carrito"></div>
		</div>
	</div>
</form>

</div>
<!-- FORMULARIO PARA ALTA DE CARRITO, DET_CARRITO Y PEDIDO-->


</div>
<div class="contenedor_pedido_btn">
	<div class="btn_paso btn_paso_1" paso='2'>
		<button class="btn btn-success btn-lg">Siguiente <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> </button>
	</div>
	<div class="btn_paso btn_paso_2" paso='3' hidden>
		<button class="btn btn-success btn-lg">Siguiente <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> </button>
	</div>
	<div class="btn_paso btn_paso_3" hidden>
		<button class="btn btn-primary btn-lg btn_enviar_pedido">Enviar Pedido <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> </button>
	</div>
</div>



<!-- MODAL PARA ALTA -->
<div id="agregarArticuloModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><b>Agregar al Carrito</b></h4>
      </div>
      <form id="form_alta_carrito">
      <div class="modal-body">

        <div class="ocultar_contenido_producto">

        <div class="row imagen_modal" style="text-align: center">
            <div class="img_modal_loader" style="height: 150px;"></div>
            <img src='img/no_image.png' class='img_art2 img_prod_modal'>
        </div>
        <div class="row descripcion_modal" style="text-align: center">
                DESCRIPCION
        </div>
        <BR>
        <div class="row" style="text-align: center">
            <div class="col col-xs-4" style="padding: 0px">
                <a class="btn btn-danger btn-sm ord_menos" style="color: white">
                    <i class="fa fa-minus-circle fa-2x" aria-hidden="true"></i>
                </a>
            </div>
            <div class="col col-xs-4" style="padding: 0px">
                <input type="hidden" name='preparado' class="check_preparado_input">
                <input type="hidden" name='asado' class="check_asado_input">
                <input type="hidden" name="unidad" id="unidad_modal_form">
                <input type="hidden" name="id_cliente" id="cliente_modal_form">
                <input type="hidden" name="producto" id="producto_modal_form">
                <input type="hidden" name="imagen" id="imagen_modal_form">
                <input type="hidden" name="departamento" id="departamento_modal_form">
                <input type="hidden" name="precio" id="precio_modal_form">
                <input type="hidden" name="descripcion" id="descripcion_modal_form">
                <input type="number" class="form-control input_orden" value="1" name="cantidad" step="0.01" id="cantidad_input">
                <i class="fa fa-pencil" aria-hidden="true"></i>
            </div>
            <div class="col col-xs-4" style="padding: 0px">
                <a class="btn btn-success btn-sm ord_mas" style="color: white">
                    <i class="fa fa-plus-circle fa-2x" aria-hidden="true"></i>
                </a>
            </div>
        </div>
        <div class="row unidad_modal" style="text-align: center;font-weight: bold;"></div>
        <div class="row fyv_pieza" style="text-align: center" hidden>
             Para especificar en PIEZAS utilice el campo de detalles<br><br>
        </div>
        <div class="row row_asado" style="padding-top:15px">
            <div class="row_preparado col-xs-6">
                <div class="col-xs-8 servicio_preparado" style="text-align: right; padding-top: 5px">Preparado</div>
                <div class="col-xs-4 div_check_preparado"><input type="checkbox" class="form-control check_preparado"></div>
            </div><div class="col-xs-6 row_preparado2" hidden></div>
            <div class="contenedor_asado col-xs-6">
                <div class="col-xs-8 servicio_asado" style="text-align: right; padding-top: 5px">Asado <i class="fa fa-fire" aria-hidden="true"></i></div>
                <div class="col-xs-4 div_check_asado"><input type="checkbox" class="form-control check_asado"></div>
            </div>
            <div class="contenedor_corte col-xs-12">
                    <div class="col-xs-4" style="text-align: right; padding-top: 10px">Corte</div>
                    <div class="col-xs-6" style="">
                        <select class="form-control select_corte" name="corte">
                            <option value="N">No Aplica</option>
                            <option value="1/4">Delgado 1/4"</option>
                            <option value="1/2">Medio 1/2"</option>
                            <option value="1">Grueso 1"</option>
                            <option value="2">Extremo 2"</option>
                        </select>
                    </div>
                </div>
        </div>
        <div class="row fyv_asado" style="text-align: center" hidden>
            <div class="row row_asado" >
            <div class="col-xs-4" style="text-align: right; padding-top: 10px">Término</div>
            <div class="col-xs-6" style="">
                <select class="form-control select_termino" name="termino">
                    <option value="B/A">Bien Asado</option>
                    <option value="3/4">Tres Cuartos</option>
                    <option value="1/2">Término Medio</option>
                </select>
            </div>
            </div>
        </div>

        </div>
        <div class="ocultar_contenido_producto_mensaje" hidden>Describa a detalle el producto que desea agregar a su pedido</div>
        <div class="row">
            <div class="col-xs-12" style="padding-top: 10px;">
                <input type="text" name="detalles" class="form-control ord_detalles" placeholder="Detalles del pedido" id="detalles_input">
            </div>
        </div>
    </div>
      
      <div class="modal-footer">
        <div class="row">
            <div class="col-xs-6">
                <button type="button" class="btn btn-warning" data-dismiss="modal" style="width: 100%">
                    <i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Regresar
                </button>
            </div>
            <div class="col-xs-6">
                <button type="submit" class="btn btn-success agregar_al_carrito_btn" style="width: 100%">
                    <i class="fa fa-cart-plus" aria-hidden="true"></i> Agregar
                </button>
            </div>
        </div>
      </div>
    </form>

    </div>
  </div>
</div> <!--FIN MODAL-->




<!-- MODAL PARA EDITAR -->
<div id="editarArticuloModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><b>Editar pedido</b></h4>
      </div>
      <form id="form_editar_carrito">
      <div class="modal-body">
        <div class="ocultar_contenido_producto">
        <div class="row imagen_modal" style="text-align: center">
            <div class="img_modal_loader_e" style="height: 150px;"></div>
            <img src='img/no_image.png' class='img_art2 img_prod_modal_e'>
        </div>
        <div class="row descripcion_modal_e" style="text-align: center">
                DESCRIPCION
        </div>
        <BR>
        <div class="row" style="text-align: center">
            <div class="col col-xs-4" style="padding: 0px">
                <a class="btn btn-danger btn-sm ord_menos" style="color: white">
                    <i class="fa fa-minus-circle fa-2x" aria-hidden="true"></i>
                </a>
            </div>
            <div class="col col-xs-4" style="padding: 0px">
                <input type="hidden" name="id_carrito_det" id="producto_carrito_modal_form_e">
                <input type="hidden" name='asado' class="check_asado_input">
                <input type="hidden" name='preparado' class="check_preparado_input">
                <input type="hidden" name="unidad" id="unidad_modal_form_e">
                <input type="hidden" name="id_cliente" id="cliente_modal_form_e">
                <input type="hidden" name="producto" id="producto_modal_form_e">
                <input type="hidden" name="departamento" id="departamento_modal_form_e">
                <input type="hidden" name="precio" id="precio_modal_form_e">
                <input type="hidden" name="descripcion" id="descripcion_modal_form_e">
                <input type="hidden" name="imagen" id="imagen_modal_form_e">
                <input type="number" class="form-control input_orden" value="1" name="cantidad" step="0.01">
                <i class="fa fa-pencil" aria-hidden="true"></i>
            </div>
            <div class="col col-xs-4" style="padding: 0px">
                <a class="btn btn-success btn-sm ord_mas" style="color: white">
                    <i class="fa fa-plus-circle fa-2x" aria-hidden="true"></i>
                </a>
            </div>
        </div>
        <div class="row fyv_pieza" style="text-align: center" hidden>
            Para especificar en PIEZAS utilice el campo de detalles<br><br>
        </div>
        <div class="row unidad_modal_e" style="text-align: center;font-weight: bold;"></div>
        <div class="row row_asado" style="padding-top:15px">
            <div class="row_preparado col-xs-6">
                <div class="col-xs-8 servicio_preparado" style="text-align: right; padding-top: 5px">Preparado</div>
                <div class="col-xs-4 div_check_preparado"><input type="checkbox" class="form-control check_preparado"></div>
            </div>
            <div class="col-xs-6 row_preparado2" hidden></div>
            <div class="contenedor_asado col-xs-6">
                <div class="col-xs-8 servicio_asado" style="text-align: right; padding-top: 5px">Asado <i class="fa fa-fire" aria-hidden="true"></i></div>
                <div class="col-xs-4 div_check_asado"><input type="checkbox" class="form-control check_asado"></div>
            </div>
            <div class="contenedor_corte col-xs-12">
                    <div class="col-xs-4" style="text-align: right; padding-top: 10px">Corte</div>
                    <div class="col-xs-6" style="">
                        <select class="form-control select_corte" name="corte">
                            <option value="N">No Aplica</option>
                            <option value="1/8">Muy Delgado 1/8"</option>
                            <option value="1/4">Delgado 1/4"</option>
                            <option value="1/2">Medio 1/2"</option>
                            <option value="1">Grueso 1"</option>
                            <option value="2">Extremo 2"</option>
                        </select>
                    </div>
                </div>
        </div>
        <div class="row fyv_asado" style="text-align: center" hidden>
            <div class="row row_asado" >
            <div class="col-xs-4" style="text-align: right; padding-top: 10px">Término</div>
            <div class="col-xs-6" style="">
                <select class="form-control select_termino" name="termino">
                    <option value="B/A">Bien Asado</option>
                    <option value="1/2">Término Medio</option>
                    <option value="3/4">Tres Cuartos</option>
                </select>
            </div>
            </div>
        </div>
        </div>
        <div class="ocultar_contenido_producto_mensaje" hidden>Describa a detalle los productos que desea agregar a su pedido</div>
        <div class="row">
            <div class="col-xs-12" style="padding-top: 10px">
                <input type="text" name="detalles" class="form-control ord_detalles" placeholder="Detalles del pedido">
            </div>
        </div>
    </div>
      
      <div class="modal-footer">
        <div class="row">
            <div class="col-xs-4">
                <button type="button" class="btn btn-warning" data-dismiss="modal" style="width: 100%">
                    <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
                </button>
            </div>
            <div class="col-xs-4">
                <button type="button" class="btn btn-danger btn_modal_borrar_e" style="width: 100%">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
            </div>
            <div class="col-xs-4">
                <button type="button" class="btn btn-success btn_modal_guardar_e" style="width: 100%">
                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
                </button>
            </div>
        </div>
      </div>
    </form>

    </div>
  </div> <!--FIN MODAL-->