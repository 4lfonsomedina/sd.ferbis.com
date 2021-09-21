
<? foreach($pedidos as $p){ 
	$color = "primary";
	//if($p->status=='2'){$color = "warning";}
	if($p->status=='3'){$color = "success";}
	if($p->origen=='1'){$color = "info";}
	
	
	$hora1 = strtotime( $p->hora_entrega );
	$hora2 = strtotime( date('H:i:s') );
	$urgente = "";
	if( $p->status!='3' && $hora1 < $hora2 ) {$urgente = "style='background-color: #F85858 !important;'";} 

	if($p->status!='3'&&$p->id_cliente=='7243'){$color = "warning";}
	if($p->id_cliente=='7243'){
		$p->hora_entrega=$p->hora;
		$urgente = "style='background-color: #F85858 !important;'";}

	?>
		<div class="col-sm-4">
			<div class="panel panel-<?= $color; ?> panel_pedido<?=$p->status?>" id_carrito="<?= $p->id_carrito ?>" id_pedido="<?= $p->id_pedido ?>" id_sucursal="<?= $p->id_sucursal ?>">
					<div class="panel-heading"><h5 class="titulo"><?= $p->nombre ?> </h5>
						<span class="badge badge-light pull-right" style="font-size: 18px"><?= round($p->distancia,2)."km ".$p->sucursal ?></span>
						<span class="badge badge-light pull-right" style="font-size: 18px"><i class="fa fa-print" aria-hidden="true"></i> <?= $p->impresiones ?></span>
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
							<div class="col-xs-4"> <span class="badge badge-light pull-right hora_entrega_pedido"
								<?= $urgente ?>
								><?= hora_bonito($p->hora_entrega) ?></span> </div>
						</div>
					</div>
				</div>
		</div>
				<? } ?>