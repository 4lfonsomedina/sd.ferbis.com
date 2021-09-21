
<script type="text/javascript" src="<?= base_url() ?>/assets/js/code39.js"></script>
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
	  .contenido_carrito_impresor{
	  	font-size: 18px;
	  }
	}
</style>

<div class="panel panel-primary hide_print" style="height: 600px;overflow-y: auto;"><div class="panel-body">
	<table class="table">
		<tr>
			<th>Folio</th>
			<th>Cliente</th>
			<th>Telefono</th>
			<th>Estatus</th>
			<th>Fecha</th>
			<th>Hora</th>
			<th>Articulos</th>
			<th>Imprimir</th>
		</tr>
		<? foreach($pedidos as $p){ $folio = str_pad($p->consecutivo, 5, "0", STR_PAD_LEFT); ?>
			<tr>
				<td><?= $folio ?></td>
				<td><?= $p->nombre ?></td>
				<td><?= $p->telefono ?></td>
				<td><?= $p->status ?></td>
				<td><?= $p->fecha_entrega ?></td>
				<td><?= $p->hora_entrega ?></td>
				<td><?= $p->articulos ?></td>
				<td>
					<a href="#" 
					id="print_pedido_<?= $p->id_pedido ?>"
					id_pedido="<?= $p->id_pedido ?>"
					folio="<?= $folio ?>"
					id_carrito="<?= $p->id_carrito ?>" 
					cliente="<?= $p->numero.' - '.$p->nombre.', '.$p->telefono ?>" 
					direccion="<?= $p->dir_calle.','.$p->dir_numero1.','.$p->dir_numero2.','.$p->dir_colonia.', '.$p->referencia ?>"
					entrega="<?= dia_bonito($p->fecha_entrega).' '.hora_bonito($p->hora_entrega) ?>" 
					entrega2="<?= dia_bonito($p->fecha_entrega).' '.hora_bonito(menos_hora($p->hora_entrega)) ?>"
					servicio="<?= $p->servicio ?>"
					cbarras="<?= $p->id_sucursal.'S'.$folio ?>"
					origen="<?= $p->tomo ?>"
					class="btn btn-primary print_pedido"
					><i class="fa fa-print" aria-hidden="true"></i></a>
				</td>
			</tr>
		<?}?>
	</table>
</div>
</div>
<div id="section-to-print" >
	<div class="titulo_print">
		<h1 style="font-size: 100px" id="folio_print" >00000</h1>
		<br>
		
		<h1 style="font-size: 35px" id="entrega_print">16-Jun 10pm</h1>
		<br>
		<h1 style="font-size: 35px" id="asado_print" >ASADO</h1>
		<br>
		<h1 style="font-size: 35px" id="servicio_print">A domicilio</h1>
		<br>
		<p id="cliente_print">Alfonso Medina Duran<br></p><br>
		<p id="cliente_print_dir">Artemisa #4245, Victoria Residencial</p><br>
		<br>
		<div class="contenido_carrito_impresor" style="width: 100%;"></div>
		<svg id="folio_print_barcode" ></svg>
		<p id="tomo_print">Tomo: APP</p><br>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		// Cada 5 segundos revisar si hay algo por imprimir
		setInterval(function(){ imprimir_pedidos(); }, 5000);

		$(document).on("click",".print_pedido",function(){
			$(".modal_contenido_carrito").html("");
			$("#id_pedido_form").val($(this).attr('id_pedido'));
			var servicio = "A domicilio";
			if($(this).attr('servicio')=='2'){servicio = "Viene por el";}
			$("#folio_print").html($(this).attr('folio'));
			JsBarcode("#folio_print_barcode", $(this).attr('cbarras'), {format: "CODE128", height: 50});
			//$("#folio_print_barcode").html($(this).attr('folio'));
			$("#entrega_print").html($(this).attr('entrega'));
			$("#servicio_print").html(servicio);
			$("#cliente_print").html($(this).attr('cliente'));
			$("#cliente_print_dir").html($(this).attr('direccion'));
			$("#tomo_print").html($(this).attr('origen'));
			
			var entrega2 = $(this).attr('entrega2');
			$.post("../Api_controller/get_carritos_departamento",{id:$(this).attr('id_carrito')},function(r){
				$("#servicio_print").show();
				$("#cliente_print").show();
				$("#asado_print").hide();if(tiene_asado(r)){$("#asado_print").show();}
				$(".contenido_carrito_impresor").html("");

				//ocultar direccion si es a domicilio
				console.log($("#servicio_print").html());
				if($("#servicio_print").html() == "Viene por el"){$("#cliente_print_dir").hide();}
				else{$("#cliente_print_dir").show();}

				//$(".contenido_carrito_impresor").hide();
				var campo_total="<tr><td>Total</td><td style='border:solid 2px'>$</td></tr>";
				$(".contenido_carrito_impresor").html("<table style='width:95%'>"+campo_total+string_carrito_pedido4(r)+"</table>");
				window.print();
				$(".contenido_carrito_impresor").html("<table style='width:95%'>"+campo_total+"<tr><td colspan='2' style='text-align:center;font-weight:bold;'>*** copia ***</td></tr>"+string_carrito_pedido4(r)+"</table>");
				window.print();
				$("#entrega_print").html(entrega2);
				$("#servicio_print").hide();
				$("#cliente_print").hide();
				$("#cliente_print_dir").hide();

				$(".contenido_carrito_impresor").html("");
				$(".contenido_carrito_impresor").html("<table style='width:95%'>"+string_carrito_pedido3(r)+"</table>");
				$.post('../Control_controller/pedido_impreso',{id_carrito:jQuery.parseJSON(r)[0].id_carrito}, function(r) {
						/*optional stuff to do after success */
					});
				if(string_carrito_pedido3(r)!=''){
					window.print();
				}
				location.reload();
			})
		})
	});
	function tiene_asado(string_json){
		var string_ret=false;
		$.each(jQuery.parseJSON(string_json), function( i, prod ) {
			if(prod.asado=='1'){string_ret=true;}
		})
		return string_ret;
	}
	function string_carrito_pedido4(string_json){
		var string_ret="";
		var id_departamento = "0";
		$.each(jQuery.parseJSON(string_json), function( i, prod ) {

			//dividir por departamento
			if(id_departamento!=prod.id_departamento){
				id_departamento=prod.id_departamento;
				string_ret+="<tr><td colspan='2' style='font-size:50;font-weight: bold; text-align:center;'>=-=- "+prod.nombre_departamento+"-=-=</td></tr>";
			}
			var icon="";
			var detalles=""; if(prod.detalles!=''){ detalles=prod.detalles;}

			var asado=""; if(prod.asado=='1'){ asado='(ASA) ';}

			var preparado=""; if(prod.preparado=='1'&&prod.id_departamento=='002'){ preparado='(PRE) ';}
			var corte=""; if(prod.corte!='N'&&prod.corte!=''&&prod.id_departamento=='002'){ corte='(COR '+prod.corte+') '; }
			var termino=""; if(prod.termino!=''&&prod.asado=='1'&&prod.id_departamento=='002'){ termino='(TER '+prod.termino+') ';}


			string_ret+="<tr>"+
			  				"<td style='font-size:30; border-top: solid 2px;'>"+parseFloat(prod.cantidad).toFixed(2)+"<br><b>"+prod.unidad+"</b></td>"+
			  				"<td style='font-size:30; border-top: solid 2px;'>"+prod.descripcion+"<br>"+asado+preparado+corte+termino+prod.detalles+"</td>"+
			  			"</tr>";
		});
		return string_ret;
	}

	function string_carrito_pedido3(string_json){
		var string_ret="";
		var id_departamento = "0";
		$.each(jQuery.parseJSON(string_json), function( i, prod ) { if(prod.id_departamento=='002'){

			//dividir por departamento
			if(id_departamento!=prod.id_departamento){
				id_departamento=prod.id_departamento;
				string_ret+="<tr><td colspan='2' style='font-size:50;font-weight: bold; text-align:center;'>=-=- "+prod.nombre_departamento+"-=-=</td></tr>";
			}
			var icon="";
			var detalles=""; if(prod.detalles!=''){ detalles=prod.detalles;}

			var asado=""; if(prod.asado=='1'){ asado='(ASA) ';}

			var preparado=""; if(prod.preparado=='1'&&prod.id_departamento=='002'){ preparado='(PRE) ';}
			var corte=""; if(prod.corte!='N'&&prod.corte!=''&&prod.id_departamento=='002'){ corte='(COR '+prod.corte+') '; }
			var termino=""; if(prod.termino!=''&&prod.asado=='1'&&prod.id_departamento=='002'){ termino='(TER '+prod.termino+') ';}

			string_ret+="<tr>"+
			  				"<td style='font-size:30; border-top: solid 2px;'>"+parseFloat(prod.cantidad).toFixed(2)+"<br><b>"+prod.unidad+"</b></td>"+
			  				"<td style='font-size:30; border-top: solid 2px;'>"+prod.descripcion+"<br>"+asado+preparado+corte+termino+prod.detalles+"</td>"+
			  			"</tr>";
		}});
		return string_ret;
	}

	function imprimir_pedidos(){
		$.post('../Control_controller/por_imprimir_pedido', function(r) {
			if(r!='null'){
				var pedido = jQuery.parseJSON(r);
				if (($("#print_pedido_"+pedido.id_pedido).length == 0)){ 
				location.reload();
			}
				console.log(pedido.id_pedido);
				$("#print_pedido_"+pedido.id_pedido).click();
			}
		});
	}
</script>