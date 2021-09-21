<style type="text/css">
	@media print {

	  .hide_print{
	  	height: 10px;
	  	visibility: hidden;
	  }

	  #section-to-print {
	  	width: 100%;
	    position: absolute;
	    left: 0;
	    top: 0;
	  }

	  .titulo_print h1{
	  	text-align: center;
	  	margin-bottom: 0px;
	  }
	  .titulo_print p{
	  	font-size: 18px;
	  	text-align: center;
	  }
	  .contenido_comprobante{
	  	text-align: left;
	  	font-size: 18px;
	  }
	  .navbar{
	  	margin-bottom: 0px;
	  }
	  .contenedor_body{
	  	padding: 0px;
	  }
	}
</style>
<div class="hide_print">
<div class="row" style=" margin:0px; margin-bottom: 20px;">
	<form id="form_id_pedido">
		<div class="col-sm-4">
			<a href="imprimir_cierre_app?fecha1=<?= date('Y-m-d') ?>&fecha2=<?= date('Y-m-d') ?>" 
				target="_blank" 
				class="btn btn-default imprimir_cierre_pagos">
				<i class="fa fa-print" aria-hidden="true"></i> Imprimir cierre
			</a><br><br>
		</div>
		<div class="col-sm-4"><input type="text" class="form-control" style="background-color: white;" id="folio_pedido"></div>
		<button type="submit" hidden>-</button>
	</form>
</div>
<?php foreach($pedidos as $p) {?>
<div class="col-md-3 col-sm-4">
	<a href="#" 
					class="a_ligar_pedido"
					id="pedido_<?= $p->id_sucursal."S".str_pad($p->consecutivo, 5, "0", STR_PAD_LEFT) ?>"
					valor = "<?= $p->id_sucursal."S".str_pad($p->consecutivo, 5, "0", STR_PAD_LEFT) ?>"
					estatus=<?= $p->pago_status ?>
					>

	<div class="panel panel-default">
		<div class="panel-heading">
					<b style="font-size: 10px;"><?= $p->nombre ?></b><br>
					<?= $p->id_sucursal."S".str_pad($p->consecutivo, 5, "0", STR_PAD_LEFT) ?>
			<span class="pull-right"><?= $p->pago_ticket ?></span>
		</div>
		<div class="panel-body">
			<?= number_format($p->pago_total,2) ?>
			<?php if($p->pago_status=='0'){echo "<b style='color:gray' class='pull-right'>No enviado</b>";} ?>
			<?php if($p->pago_status=='1'){echo "<b style='color:orange' class='pull-right'>Enviado</b>";} ?>
			<?php if($p->pago_status=='2'){echo "<b style='color:green' class='pull-right'>Pagado</b>";} ?>
		</div>
	</div>
</div>
</a>
<?php } ?>

</div>



<!-- Modal -->
<div id="captura_ticket_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Folio de ticket</h4>
      </div>
      <div class="modal-body">
      	<form id="form_buscar_ticket">
      		<center>
	      		<input type="hidden" class="form-control" style="max-width:200px" id="folio_pedido2">
	      		<input type="text" class="form-control" style="max-width:200px" id="folio_ticket">
	      		<button type="submit" hidden>-</button>
      		</center>
      	</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>




<div id="section-to-print" >
	<p id="contenido_comprobante"></p><br>
</div>