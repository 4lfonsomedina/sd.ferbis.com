<?
$contador_pedidos=array(0,0,0,0); 
foreach($pedidos as &$p){ 
	$p->color = "primary";
	if($p->status==2){$p->color = "warning";}
	if($p->status==3){$p->color = "success";}
	if($p->origen==1){$p->color = "info";}

	if($p->status==1){$contador_pedidos[0]++;}
	if($p->status==2&&$p->servicio==1){ $contador_pedidos[1]++; }
	if($p->status==2&&$p->servicio==2){ $contador_pedidos[2]++; }
	if($p->status==3){$contador_pedidos[3]++;}
} ?>


<div class="col-xs-12" style="text-align: right;"><a href="#" class="btn btn-default procesar_historico"><i class="fa fa-refresh" aria-hidden="true"></i> Procesar todos los pedidos anteriores</a></div>
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
			<?php foreach($pedidos as $p)if($p->status=='1'){?>

		<div class="col-sm-3">
			<div class="panel panel-<?= $p->color; ?> panel_pedido<?=$p->status?>" id_carrito="<?= $p->id_carrito ?>" id_pedido="<?= $p->id_pedido ?>">
					<div class="panel-heading"><h5 class="titulo"><?= $p->nombre ?> </h5>
						<span class="badge badge-light pull-right" style="font-size: 18px"><?= round($p->distancia,2)."km ".$p->sucursal ?></span>
						<span class="badge badge-light pull-right folio_pedido"><?= str_pad($p->consecutivo, 5, "0", STR_PAD_LEFT) ?></span>
						<span class="badge badge-light pull-right servicio_icon">
							<?php if($p->servicio=='1'){echo '<i class="fa fa-motorcycle" aria-hidden="true"></i>';}?>
							<?php if($p->servicio=='2'){echo '<i class="fa fa-hand-o-down" aria-hidden="true"></i>';}?>
							</span>
					</div>
					<div class="panel-body" style="height: 80px;">
						<? if($p->dir_numero2==0){$p->dir_numero2="";}?>
						<div class="col-xs-12"><b>Col./Frac. <?= $p->dir_colonia." </b> , calle ".$p->dir_calle." , #".$p->dir_numero1,"  ".$p->dir_numero2 ?></div>
					</div>
					<div class="panel-footer">
						<div class="row">
							<div class="col-xs-4"><?= dia_bonito($p->fecha_entrega) ?></div>
							<div class="col-xs-4" style="text-align: center;"><b><?= $p->telefono ?></b></div>
							<div class="col-xs-4"> <span class="badge badge-light pull-right hora_entrega_pedido"><?= hora_bonito($p->hora_entrega) ?></span> </div>
						</div>
					</div>
				</div>
		</div>


			<?php }?>
		</div>
	</div>

<!-- X enviar -->
	<div id="enviar" class="tab-pane fade">
		<div class="col-xs-12" id="pedidos_2_1">
			<?php foreach($pedidos as $p)if($p->status=='2'&&$p->servicio=='1'){?>

		<div class="col-sm-3">
			<div class="panel panel-<?= $p->color; ?> panel_pedido<?=$p->status?>" id_carrito="<?= $p->id_carrito ?>" id_pedido="<?= $p->id_pedido ?>">
					<div class="panel-heading"><h5 class="titulo"><?= $p->nombre ?> </h5>
						<span class="badge badge-light pull-right" style="font-size: 18px"><?= round($p->distancia,2)."km ".$p->sucursal ?></span>
						<span class="badge badge-light pull-right folio_pedido"><?= str_pad($p->consecutivo, 5, "0", STR_PAD_LEFT) ?></span>
						<span class="badge badge-light pull-right servicio_icon">
							<?php if($p->servicio=='1'){echo '<i class="fa fa-motorcycle" aria-hidden="true"></i>';}?>
							<?php if($p->servicio=='2'){echo '<i class="fa fa-hand-o-down" aria-hidden="true"></i>';}?>
							</span>
					</div>
					<div class="panel-body" style="height: 80px;">
						<? if($p->dir_numero2==0){$p->dir_numero2="";}?>
						<div class="col-xs-12"><b>Col./Frac. <?= $p->dir_colonia." </b> , calle ".$p->dir_calle." , #".$p->dir_numero1,"  ".$p->dir_numero2 ?></div>
					</div>
					<div class="panel-footer">
						<div class="row">
							<div class="col-xs-4"><?= dia_bonito($p->fecha_entrega) ?></div>
							<div class="col-xs-4" style="text-align: center;"><b><?= $p->telefono ?></b></div>
							<div class="col-xs-4"> <span class="badge badge-light pull-right hora_entrega_pedido"><?= hora_bonito($p->hora_entrega) ?></span> </div>
						</div>
					</div>
				</div>
		</div>


			<?php }?>
		</div>
	</div>

<!-- X RECOGER -->
	<div id="recoger" class="tab-pane fade">
		<div class="col-xs-12" id="pedidos_2_2">
			<?php foreach($pedidos as $p)if($p->status=='2'&&$p->servicio=='2'){?>

		<div class="col-sm-3">
			<div class="panel panel-<?= $p->color; ?> panel_pedido<?=$p->status?>" id_carrito="<?= $p->id_carrito ?>" id_pedido="<?= $p->id_pedido ?>">
					<div class="panel-heading"><h5 class="titulo"><?= $p->nombre ?> </h5>
						<span class="badge badge-light pull-right" style="font-size: 18px"><?= round($p->distancia,2)."km ".$p->sucursal ?></span>
						<span class="badge badge-light pull-right folio_pedido"><?= str_pad($p->consecutivo, 5, "0", STR_PAD_LEFT) ?></span>
						<span class="badge badge-light pull-right servicio_icon">
							<?php if($p->servicio=='1'){echo '<i class="fa fa-motorcycle" aria-hidden="true"></i>';}?>
							<?php if($p->servicio=='2'){echo '<i class="fa fa-hand-o-down" aria-hidden="true"></i>';}?>
							</span>
					</div>
					<div class="panel-body" style="height: 80px;">
						<? if($p->dir_numero2==0){$p->dir_numero2="";}?>
						<div class="col-xs-12"><b>Col./Frac. <?= $p->dir_colonia." </b> , calle ".$p->dir_calle." , #".$p->dir_numero1,"  ".$p->dir_numero2 ?></div>
					</div>
					<div class="panel-footer">
						<div class="row">
							<div class="col-xs-4"><?= dia_bonito($p->fecha_entrega) ?></div>
							<div class="col-xs-4" style="text-align: center;"><b><?= $p->telefono ?></b></div>
							<div class="col-xs-4"> <span class="badge badge-light pull-right hora_entrega_pedido"><?= hora_bonito($p->hora_entrega) ?></span> </div>
						</div>
					</div>
				</div>
		</div>


			<?php }?>
		</div>
	</div>


<!-- FINALIZADOS -->
	<div id="finalizados" class="tab-pane fade">
		<div class="col-xs-12" id="pedidos_3">
			<?php foreach($pedidos as $p)if($p->status=='3'){?>

		<div class="col-sm-3">
			<div class="panel panel-<?= $p->color; ?> panel_pedido<?=$p->status?>" id_carrito="<?= $p->id_carrito ?>" id_pedido="<?= $p->id_pedido ?>">
					<div class="panel-heading"><h5 class="titulo"><?= $p->nombre ?> </h5>
						<span class="badge badge-light pull-right" style="font-size: 18px"><?= round($p->distancia,2)."km ".$p->sucursal ?></span>
						<span class="badge badge-light pull-right folio_pedido"><?= str_pad($p->consecutivo, 5, "0", STR_PAD_LEFT) ?></span>
						<span class="badge badge-light pull-right servicio_icon">
							<?php if($p->servicio=='1'){echo '<i class="fa fa-motorcycle" aria-hidden="true"></i>';}?>
							<?php if($p->servicio=='2'){echo '<i class="fa fa-hand-o-down" aria-hidden="true"></i>';}?>
							</span>
					</div>
					<div class="panel-body" style="height: 80px;">
						<? if($p->dir_numero2==0){$p->dir_numero2="";}?>
						<div class="col-xs-12"><b>Col./Frac. <?= $p->dir_colonia." </b> , calle ".$p->dir_calle." , #".$p->dir_numero1,"  ".$p->dir_numero2 ?></div>
					</div>
					<div class="panel-footer">
						<div class="row">
							<div class="col-xs-4"><?= dia_bonito($p->fecha_entrega) ?></div>
							<div class="col-xs-4" style="text-align: center;"><b><?= $p->telefono ?></b></div>
							<div class="col-xs-4"> <span class="badge badge-light pull-right hora_entrega_pedido"><?= hora_bonito($p->hora_entrega) ?></span> </div>
						</div>
					</div>
				</div>
		</div>


			<?php }?>
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
        <h5 class="modal-title">Contenido Pedido <button type="button" class="btn btn-info pedidos_imp_pedido" id_pedido=""><i class="fa fa-print" aria-hidden="true"></i></button></h5>
        
      </div>
      <div class="modal-body">
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
      		</table>
      	</div>
        <div class="modal_contenido_carrito" style="width: 100%; height: 500px; max-height: 500px; overflow-y: auto;">
      </div>
      <div class="modal-footer">
      	<div class="col-xs-6">
        	<button type="button" class="btn btn-primary" data-dismiss="modal" style="width: 100%">
        		<i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Regresar</button>
        </div>
        <div class="col-xs-6 div_surtido">
        	<button type="button" class="btn btn-success btn_surtido" data-dismiss="modal" style="width: 100%">
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



<form id="procesar_pedido_form" >
	<input type="hidden" id="id_pedido_form" name="id_pedido">
	<input type="hidden" name="status" value="3">
</form>