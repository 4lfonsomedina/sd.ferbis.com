<!--
ESTATUS
1 - por despachar
2 - por enviar / por recoger
3 - finalizado

-->

<?
$contador_pedidos=array(0,0,0,0);
foreach($pedidos as $p){
	if($p->status==1){$contador_pedidos[0]++;}
	if($p->status==2&&$p->servicio==1){ $contador_pedidos[1]++; }
	if($p->status==2&&$p->servicio==2){ $contador_pedidos[2]++; }
	if($p->status==3){$contador_pedidos[3]++;}
}
?>

<div class="col-xs-12">
	<a class="btn btn-primary pull-right btn_nuevo_pedido" href="nuevo_pedido_form2" id="btn_nuevo_pedido2" style="margin-left: 10px;"> <i class="fa fa-plus" aria-hidden="true"></i> Nuevo Pedido</a>
<!--
  <a class="btn btn-primary pull-right btn_nuevo_pedido2" href="nuevo_pedido_form" id="btn_nuevo_pedido"> <i class="fa fa-plus" aria-hidden="true"></i> Nuevo Pedido (OLD)</a>
-->
</div>
<ul class="nav nav-tabs pedidos_nav">
  <li class="active"><a data-toggle="tab" href="#depachar"><i class="fa fa-shopping-basket fa-2x" aria-hidden="true"></i> (<span id="span_cant_pediddos_1" class="cant_pedidos_span"><?= $contador_pedidos[0] ?></span>)</a></li>
  <li><a data-toggle="tab" href="#enviar"><i class="fa fa-clock-o fa-2x" aria-hidden="true"></i> (<span id="span_cant_pediddos_2_1" class="cant_pedidos_span"><?= $contador_pedidos[1] ?></span>)</a></li>
  <li><a data-toggle="tab" href="#recoger"><i class="fa fa-hand-o-down fa-2x" aria-hidden="true"></i> (<span id="span_cant_pediddos_2_2" class="cant_pedidos_span"><?= $contador_pedidos[2] ?></span>)</a></li>
  <li><a data-toggle="tab" href="#finalizados"><i class="fa fa-motorcycle fa-2x" aria-hidden="true"></i> (<span id="span_cant_pediddos_3" class="cant_pedidos_span"><?= $contador_pedidos[3] ?></span>)</a></li>

</ul>


<div class="tab-content">

<!-- X DESPACHAR -->
	<div id="depachar" class="tab-pane fade in active">
		<div class="col-xs-12" id="pedidos_1">
			
		</div>
	</div>

<!-- X enviar -->
	<div id="enviar" class="tab-pane fade">
		<div class="col-xs-12" id="pedidos_2_1">
			
		</div>
	</div>

<!-- X RECOGER -->
	<div id="recoger" class="tab-pane fade">
		<div class="col-xs-12" id="pedidos_2_2">
			
		</div>
	</div>


<!-- FINALIZADOS -->
	<div id="finalizados" class="tab-pane fade">
		<div class="col-xs-12" id="pedidos_3">
			
		</div>
	</div>


</div>



<div class="modal fade" id="contenido_pedido_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title">
          Contenido Pedido 
          <button type="button" class="btn btn-info pedidos_imp_pedido" id_pedido="">
            <i class="fa fa-print" aria-hidden="true"></i>
          </button>
          <button type="button" class="btn btn-warning pedidos_cambiar_sucursal" id_pedido="" id_sucursal="">
            <i class="fa fa-paper-plane" aria-hidden="true"></i>
          </button>
        </h5>
        
      </div>
      <div class="modal-body">
        <input type="hidden" id="hidden_status" val="0">
        <input type="hidden" id="hidden_servicio" val="0">
      	<div class="col-xs-12 resumen_p">
      		<table style="width: 100%">
      			<tr>
      				<th>folio</th><td id="res_mod_consecutivo"></td>
      				<th>Nombre</th><td id="res_mod_nombre"></td>
      			</tr>
      			<tr>
      				<th>Sucursal</th><td id="res_mod_sucursal"></td>
      				<th>Servicio</th><td id="res_mod_servicio">
      					
      				</td>
      			</tr>
      			<tr>
      				<th>Direccion</th><td id="res_mod_dir" colspan="3"></td>
      			</tr>
      			<tr>
      				<th>Captura</th><td id="res_mod_pedido"></td>
      				<th>Surtido</th><td id="res_mod_surtido"></td>
      			</tr>
            <tr>
              <th>Finalizado</th><td id="res_mod_enviado"></td>
              <th>Promesa</th><td id="res_mod_entrega"></td>
            </tr>
            <tr>
              <th>Captura</th><td id="res_mod_tomo"></td>
              <th>Chofer</th><td id="res_mod_chofer"></td>
            </tr>
      		</table>
      	</div>
        <div class="modal_contenido_carrito" style="width: 100%; height: 400px; max-height: 500px; overflow-y: auto;">
      </div>
      <div class="modal-footer contenedor_btn_pedido">
      	<div class="col-xs-6">
        	<button type="button" class="btn btn-primary" data-dismiss="modal" style="width: 100%">
        		<i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Regresar</button>
        </div>
        <div class="col-xs-6 div_surtido">
        	<button type="button" class="btn btn-success btn_surtido" data-dismiss="modal" style="width: 100%" id_pedido="">
        		Pedido Listo <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
        </div>
        <div class="col-xs-6 div_procesar">
        	<button type="button" class="btn btn-success btn_procesar" data-dismiss="modal" style="width: 100%">
        		Entregar <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<div class="modal fade" id="modal_chofer" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title">Chofer que entrega el pedido ?</h5>
      </div>
      <div class="modal-body">
        <select class="form-control" id="select_chofer">
            <option value="otro">Otro</option>
          <? foreach($choferes as $ch){ ?>
            <option value="<?= $ch->nombre ?>"><?= $ch->nombre ?></option>
          <? } ?>
        </select>
      </div>
      <div class="modal-footer">
        <div class="col-xs-6">
          <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close" style="width: 100%">
            Regresar
          </button>
        </div>
        <div class="col-xs-6">
          <button type="button" class="btn btn-success" id="chofer_procesar" style="width: 100%">
            Procesar
          </button>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modal_cambio_sucursal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title">Sucursal Destino ?</h5>
      </div>
      <form id="form_cambio_sucursal" action="<?= site_url('') ?>" method="POST">
        <div class="modal-body">
          <input type="hidden" id="pedidos_cambiar_sucursal_pedido" name="id_pedido">
          <input type="hidden" id="pedidos_cambiar_sucursal_nombre" name="sucursal">
          <select class="form-control" id="pedidos_cambiar_sucursal_sucursal" name="id_sucursal">
            <option value="1" sucursal="Brasil">Brasil</option>
            <option value="2" sucursal="San Marcos">San Marcos</option>
          </select>
        </div>
        <div class="modal-footer">
          <div class="col-xs-6">
            <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close" style="width: 100%">
              Regresar
            </button>
          </div>
          <div class="col-xs-6">
            <button type="submit" class="btn btn-success" style="width: 100%">
              Realizar Cambio
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>



<form id="procesar_pedido_form" >
	<input type="hidden" id="id_pedido_form" name="id_pedido">
  <input type="hidden" id="chofer_form" name="chofer">
	<input type="hidden" name="status" value="3">
</form>

<script type="text/javascript">
	$(document).keypress(function(e){
		console.log("se presiono la "+e.charCode);
		if(e.charCode==110||e.charCode==78)
  			location.href="nuevo_pedido_form";
	});
</script>