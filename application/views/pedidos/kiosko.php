<div class="row" style=" margin:0px; margin-bottom: 20px; height: 10vh">
	<form id="form_id_pedido">
		<div class="col-xs-4"><button type="button" class="btn btn-warning" style="width: 100%; height: 10vh; font-size: 30px; font-weight: bold;" id="btn_regresar_chofer"> < CANCELAR </button></div>
		<div class="col-xs-4"><input type="text" class="form-control" style="text-align: center; background-color: white; height: 10vh" id="folio_pedido"></div>
		<div class="col-xs-4"><button type="button" class="btn btn-success" style="width: 100%; height: 10vh; font-size: 30px; font-weight: bold;" id="btn_finalizar_chofer"> FINALIZAR > </button></div>
		<button type="submit" hidden>-</button>
	</form>
</div>

<div class="row" style="margin:0px;">
	<form id="form_pedidos_chofer">
		<input type="hidden" name="chofer" value="<?= $this->session->userdata('nombre') ?>">
		<div class="col-sm-7" style="height: 80vh; overflow-y: auto;" id="lista_pedidos_chofer"></div>
	</form>
	<div class="col-sm-5" style="height: 80vh;" id='map_pedidos'></div>
</div>



<div class="row" style="margin:0px;" hidden>
<?php foreach($pedidos as $p) {?>
<div class="col-md-3 col-sm-4">
	<a href="#" 
					class="a_ligar_pedido"
					id="pedido_<?= $p->id_sucursal."S".str_pad($p->consecutivo, 5, "0", STR_PAD_LEFT) ?>"
					valor = "<?= $p->id_sucursal."S".str_pad($p->consecutivo, 5, "0", STR_PAD_LEFT) ?>">

	<div class="panel panel-default">
		<div class="panel-heading">
			
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
        <h3 class="modal-title">Folio de ticket</h3>
      </div>
      <div class="modal-body">
      	<form id="form_buscar_ticket_chofer">
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

<!-- Modal -->
<div id="chofer_modal_mensaje" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" >
      <div class="modal-body chofer_modal_mensaje_body" style="font-size: 40px; font-weight: bold;text-align: center;">
      </div>
    </div>

  </div>
</div>


<script type="text/javascript">
	var map_pedidos;
	var bounds;
	function constrir_mapa_pedidos(){
		map_pedidos = new google.maps.Map(document.getElementById('map_pedidos'), {
	      zoom: 11,
	      center: {lat:32.62507103135048,lng:-115.45348998340425}
	    });
	    bounds = new google.maps.LatLngBounds();
	}
	$(document).ready(function() {
		setTimeout(function() { constrir_mapa_pedidos(); }, 1000);
		$("#folio_pedido").select();
		
	});
</script>