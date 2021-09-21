var url_api = "https://sd.ferbis.com/index.php/api_controller/";
$(document).ready(function() {
	
	var mascara_telefono= [{ "mask": "(###) ###-##-##"}, { "mask": "(###) ###-##-##"}];
	//al colocar numero de telefono
	$("#pedido_telefono").keyup(function(event) {
		$.post("../Control_controller/get_cliente_telefono",{telefono:$(this).val()},function(r){
			var cli = jQuery.parseJSON(r);
			$("#id_cliente").val(cli.id_cliente)
			$("#pedido_nombre").val(cli.nombre)
			$("#pedido_frec").val(cli.numero)
			$("#pedido_calle").val(cli.dir_calle)
			$("#pedido_numero1").val(cli.dir_numero1)
			$("#pedido_numero2").val(cli.dir_numero2)
			$("#pedido_colonia").val(cli.dir_colonia)
			$("#pedido_referencias").val(cli.referencia)
			if($("#pedido_calle").val()!=""&&$("#pedido_numero1").val()!=""&&$("#pedido_colonia").val()!=""){
				geolacalizar_direccion();
			}
		})
	});
	//al cambiar la fecha de entrega
	$("#fecha_pedido").change(function(){
		calcular_envio($("#form_pedido_id_sucursal").val());
	})
	//MASCARA DE TELEFONO
	/*
    $('#pedido_telefono').inputmask({ 
        mask: mascara_telefono, 
        greedy: false, 
        definitions: { '#': { validator: "[0-9]", cardinality: 1}},
        unmaskAsNumber: true,
        autoUnmask: true 
    });
*/
    //al seleccionar texto de buscador
    $(".input_search").on("click", function () {
	   $(this).select();
	});
	$("#f_cantidad").on("click", function () {
	   $(this).select();
	});
	$(".input_orden").on("click", function () {
	   $(this).select();
	});


    //al presionar el servicio de asado
    $(".servicio_asado").click(function(){
    	$(this).next('div').find('input').click();
    })
    //actualizar cantidad de articulos en la lista
    function actualizar_cantidad_cttiro(){
    	var cantidad_articulos = 0;
    	$(".articulo_carrito").each(function() {cantidad_articulos++;});
    	$(".cantidad_articulos").html("("+cantidad_articulos+")");
    	$("#form_pedido_articulos").val(cantidad_articulos);
    	return cantidad_articulos;
    }



    //actualizar mapa con la direccion actual
    $(".pedido_dir").focusout(function(event) {
    	if($("#pedido_calle").val()!=""&&$("#pedido_numero1").val()!=""&&$("#pedido_colonia").val()!=""){
			geolacalizar_direccion();
		}
    });

    //cerrar modal 
    $(".cerrar_modal").click(function(){
    	$(".modal").modal("hide");
    })
    //actualizacion de input de asado
	$(document).on('change',".check_asado",function(){
		if($(this).is(":checked")){
			$(".check_asado_input").val(1);
			$(".select_termino").val('B/A');
			if($(this).attr("departamento")=='002'){
				$(".fyv_asado").show(500);
			}
		}
		else{
			$(".check_asado_input").val(0); 
			$(".fyv_asado").hide(500);
		}
	})

	// al pesionar el texto del servicio_asado
	$(document).on("click",".servicio_preparado",function(){
		$(this).parent('div').find('.div_check_preparado').find('input').click();
	})

	$(document).on('change',".check_preparado",function(){
		if($(this).is(":checked")){$(".check_preparado_input").val(1);}
		else{$(".check_preparado_input").val(0);}
	})
	// Al escribir en el filtro buscador
	$(document).on("keyup",".input_search",function(){
		if($(this).val()=="."){
			console.log($(this).val());
			$(".li_paso3 > a").click();
		}else{
			if($(this).val().length<3){return;}
			$("#contenedor_articulos").html(loader());
			$.post(url_api+'get_productos_filtro',{desc:$(this).val()}, function(resp_json){
				string_articulos(resp_json);
				$(".img_dep").attr('dep',0);
			});
		}
		
	})
	// Al seleccionar el articulo
	$(document).on("change",".select_producto",function(){
		$("#contenedor_articulos").html(loader());
		$.post(url_api+'get_producto',{producto:$(this).val()}, function(resp_json){
			string_articulos2(resp_json);
			$(".img_dep").attr('dep',0);
		});
	})


	//funcion alta de producto al carrito
	$("#form_alta_carrito").submit(function(event) {
		event.preventDefault();
		$("#agregarArticuloModal").modal("hide");
		$(".contenido_carrito").append("<div id='id"+actualizar_cantidad_cttiro()+" class='id"+actualizar_cantidad_cttiro()+"'>"+row_string_carrito($("#form_alta_carrito").serializeArray())+"</div>");
		actualizar_cantidad_cttiro();
		$("#input_search").val("");
		$("#input_search").click();
		notificacion("Producto agregado");
	});

	//al agregar un producto rapido
	$("#agregar_producto_form").submit(function(event) {
		event.preventDefault();
		console.log(objectifyForm($(this).serializeArray()).producto+" "+objectifyForm($(this).serializeArray()).detalles)
		if(objectifyForm($(this).serializeArray()).producto=='01010101' && objectifyForm($(this).serializeArray()).detalles==""){
			notificacion("No se permite agregar el procuto 'OTRO' sin detalles del pedido.");
			return;
		}

		$(".contenido_carrito").append("<div id='id"+actualizar_cantidad_cttiro()+"' class='id"+actualizar_cantidad_cttiro()+"'>"+row_string_carrito($(this).serializeArray())+"</div>");
		actualizar_cantidad_cttiro();
		notificacion("Producto agregado !");
		$("#f_unidad").val("PZA");
		$("#f_producto").val("01010101");
		$('#f_producto').trigger("chosen:updated");
		$("#f_preparad").val("0");
		$("#f_asado").val("0");
		$("#f_termino").val("N");
		$("#f_corte").val("N");
		$("#f_cantidad").val("1");
		$("#f_detalles").val("");
		$("#f_cantidad").focus();
		$("#f_cantidad").select();

		// actualizar el producto otro
		$.post(url_api+'get_producto',{producto:"01010101"}, function(resp_json){
			string_articulos2(resp_json);
			$(".img_dep").attr('dep',0);
		});
	});

// Al precional el boton de mas producto
	$(document).on("click",".ord_mas",function(){
		$(".input_orden").val(parseInt($(".input_orden").val())+1);
	})
// Al precional el boton de menos producto
	$(document).on("click",".ord_menos",function(){
		if($(".input_orden").val()>1)
			$(".input_orden").val(parseInt($(".input_orden").val())-1);
	})
	//ocultar domicilio y paso por el
	$(".radio_tipo").change(function(){
		$("#tipo_sevicio").val($(this).val());
		if($("input[name='servicio']:checked").val()=='1'){
			$(".pedido_div_direccion").show(500);
			$(".tipo_servicio_resumen").html("Servicio a domicilio");
		}else{
			$(".pedido_div_direccion").hide(500);
			$(".tipo_servicio_resumen").html("Pasa por el");
		}
	})
	//// navegacion de finalizar
	$(document).on("click",".li_paso",function(e){
		geolacalizar_direccion();
		$(".btn_paso").hide();
		$(".btn_paso_"+$(this).attr('paso')).show();
		var paso =$(this).attr('paso');
		setTimeout(function() {
			if((paso!='1'&&($("#pedido_nombre").val()==""||$("#pedido_telefono").val()==""))||(paso!='1'&&$("input[name='tipo']:checked").val()=='1'&&($("#pedido_calle").val()==""||$("#pedido_numero1").val()==""||$("#pedido_colonia").val()==""))){ 
				alert("verificala informacion! Faltan datos por llenar....");
				$(".li_paso1 > a").click();
			}else{
				if($("#id_cliente").val()==""&&paso!='1'){
					$.post(url_api+'alta_cliente_telefono',{telefono:$("#pedido_telefono").val()},function(r){
						$("#id_cliente").val(r);
						$('.chosen-select').chosen();
						$("#f_cantidad").focus();
						$("#f_cantidad").select();
						producto0();
					})
				}
				if($("#id_cliente").val()!=""&&paso!='1'){
					$('.chosen-select').chosen();
					if(paso=='3'){
						calcular_envio($("#form_pedido_id_sucursal").val());
					}
					$.post(url_api+'actualizar_cuenta',$("#form_cliente_pedido").serialize(),function(r){
						console.log(r);
						actualizar_resumen();
						calculo_total();
						$("#f_cantidad").focus();
						$("#f_cantidad").select();
						// actualizar el producto otro
						producto0();
					})
				}
			}
		}, 500);
		
		
	})

	function producto0(){
		$.post(url_api+'get_producto',{producto:"01010101"}, function(resp_json){
			string_articulos2(resp_json);
			$(".img_dep").attr('dep',0);
		});
	}
	$("#form_cliente_pedido").submit(function(event) {
		event.preventDefault();
		$(".btn_paso_1 > button").click();
		$("#input_search").focus();
	});
	$(document).on("click",".btn_paso",function(){
		$(".li_paso"+$(this).attr('paso')+" > a").click();
	})
	function actualizar_resumen(){
		$(".res_nombre").html($("#pedido_nombre").val());
		$(".res_numero").html($("#pedido_frec").val());
		$(".res_telefono").html($("#pedido_telefono").val());
		$(".res_dir_calle").html($("#pedido_calle").val());
		$(".res_dir_numero1").html($("#pedido_numero1").val());
		$(".res_dir_numero2").html($("#pedido_numero2").val());
		$(".res_dir_colonia").html($("#pedido_colonia").val());
	}
	function calculo_total(){
		var total = 0;
		$(".calculo_total").each(function(index,el){
			total=(parseFloat(total)+parseFloat($(this).val()));
		})
		$("#res_total_input").val(parseFloat(total).toFixed(2));
		$(".res_total").html("$"+parseFloat(total).toFixed(2));
	}
	function actualizar_paso3(){
		var pedido = objectifyForm($("#pedido_form").serializeArray());
		$(".p3_nombre").html(pedido.nombre);
		$(".p3_telefono").html(pedido.telefono);
		$(".p3_frecuente").html(pedido.numero);
		var servicio = "A domicilio (+$30)"; if(pedido.servicio!=1){ servicio = "Paso por el"; }
		$(".p3_servicio").html(servicio);
		$(".p3_direccion").html(pedido.dir_calle+" "+pedido.dir_numero1+" "+pedido.dir_numero1+","+pedido.dir_colonia);
		$(".p3_referencia").html(pedido.referencia);
	}

	//editar producto de lista
	$(".btn_modal_guardar_e").click(function(){
		$("#editarArticuloModal").modal("hide");
		$("."+$(this).attr('identificador')).html(row_string_carrito($("#form_editar_carrito").serializeArray()));
		notificacion("Articulo actualizado");
		calculo_total();
		calcular_envio($("#form_pedido_id_sucursal").val());
	})

	//quitar el articulo de la lista
	$(".btn_modal_borrar_e").click(function(){
		$("#editarArticuloModal").modal("hide");
		$("."+$(this).attr('identificador')).remove();
		notificacion("Articulo retirado");
		calculo_total();
		calcular_envio($("#form_pedido_id_sucursal").val());
	})
	//realizar pedido
	$(".btn_enviar_pedido").click(function(){
		//validar si cumple con el total
		//if($("#tipo_sevicio").val()=='1'&&$("#res_total_input").val()<200){ alert("Consumo minimo $200.00 para envio a domicilio");return; }
		//console.log($("#select_horas_disponibles").val());return;
		if($("#select_horas_disponibles").val() === null){alert("No se capturo la hora de entrega");return;}
		if($("#atendido_por").val() === null||$("#atendido_por").val()==''){alert("Debes capturar tu nombre en ATENDIDO POR");return;}
		//deshabiitar boton 
		$(".btn_enviar_pedido").attr('disabled',true);
		//dar de alta el carrito estatus 1
		$.post(url_api+"alta_carrito",{id_cliente:$("#id_cliente").val(),status:'1'},function(r1){
			//dar de alta los detalles del carrito
			$(".form_carrito_det_id_carrito").val(r1);
			$("#form_pedido_id_carrito").val(r1);
			$("#form_pedido_id_cliente").val($("#id_cliente").val());

			$("#form_pedido_dir_colonia").val($("#pedido_colonia").val());
			$("#form_pedido_dir_calle").val($("#pedido_calle").val());
			$("#form_pedido_dir_numero1").val($("#pedido_numero1").val());
			$("#form_pedido_dir_numero2").val($("#pedido_numero2").val());
			$("#form_pedido_referencia").val($("#tipo_sevicio").val());
			$("#form_pedido_nombre").val($("#pedido_nombre").val());
			$("#form_pedido_telefono").val($("#pedido_telefono").val());
			$("#form_pedido_numero").val($("#pedido_frec").val());

			$.post(url_api+"alta_det_carrito",$("#carrito_det_form").serialize().replace(/%5B%5D/g, '[]'),function(r2){
				console.log(r2);
				//alta de pedido
				$.post(url_api+"alta_pedido",$("#pedido_form").serialize(),function(r3){
					alert("Pedido programado!");
					window.location.href = "https://sd.ferbis.com/index.php/Control_controller/dash";
				})
			})
		}).fail(function(error) { alert("Error de conexión..."); console.log(error.responseJSON); $(".btn_enviar_pedido").attr('disabled',false);});
	})
// Al presionar un articulo
	$(document).on("click",".row_articulo",function(){
		$("#agregarArticuloModal").modal("show");
		if($(this).attr('producto')=="01010101"){
			$(".ocultar_contenido_producto").hide();
			$(".ocultar_contenido_producto_mensaje").show();
			setTimeout(function() {$(".img_modal_loader").hide();$(".img_prod_modal").show();$("#detalles_input").focus();},500);
		}else{
			$(".ocultar_contenido_producto").show();
			$(".ocultar_contenido_producto_mensaje").hide();
			setTimeout(function() {$(".img_modal_loader").hide();$(".img_prod_modal").show();$("#cantidad_input").click();},500);
		}
		$(".descripcion_modal").html($(this).attr('descripcion'));
		$(".unidad_modal").html($(this).attr('unidad'));

		//transicion de imagen
		$(".img_modal_loader").html(loader_mini());
		$(".img_modal_loader").show();
		$(".img_prod_modal").hide();
		$(".img_prod_modal").attr('src',$(this).attr('imagen'));


		
		
		$(".input_orden").val(1);
		$(".check_asado").prop('checked',false);
		$(".ord_detalles").val("");
		$(".check_asado_input").val(0);
		$(".check_asado").attr('departamento',$(this).attr('departamento'));
		$(".check_preparado_input").val(0);
		$(".check_preparado").prop('checked',false);

		//datos para formulario 
		$("#cliente_modal_form").val($("#id_cliente").val());
		$("#producto_modal_form").val($(this).attr('producto'));
		$("#departamento_modal_form").val($(this).attr('departamento'));
		$("#unidad_modal_form").val($(this).attr('unidad'));
		$("#imagen_modal_form").val($(this).attr('imagen'));
		$("#precio_modal_form").val($(this).attr('precio'));

		/*$("#cliente_modal_form").val(sesion_local.getItem("FerbisAPP_id"));*/
		$("#descripcion_modal_form").val($(this).attr('descripcion'));
		$(".fyv_asado").hide(500);
		//etiquetas
		if($(this).attr('departamento')=='005'||$(this).attr('departamento')=='002'){
			$(".row_asado").show();
			if($(this).attr('unidad')=='KG'){
				$(".unidad_modal").html("<div class='col-xs-4'></div><div class='col-xs-4'>"+
				"<select name='unidad' class='form-control cambio_unidad' style='text-align-last:center;' precio='"+$(this).attr('precio')+"'>"+
				"<option value='KG'>KG</option>"+
				"<option value='PZA'>PZA</option>"+
				"</select></div><div class='col-xs-4'></div>");
			}
		}
		else{
			$(".row_asado").hide();
		}
		if($(this).attr('departamento')=='005'){
		$(".row_preparado").hide();
		$(".row_preparado2").show();
		}else{
			$(".row_preparado").show();
			$(".row_preparado2").hide();
		}
		if($(this).attr('departamento')=='002'){$(".contenedor_corte").show();}
		else{ $(".contenedor_corte").hide(); }
	})

	//funcion para abrir modal de edicion deun pedido
$(document).on("click",".articulo_carrito",function(){
	$("#editarArticuloModal").modal("show");
	if($(this).attr('producto')=="01010101"){
			$(".ocultar_contenido_producto").hide();
			$(".ocultar_contenido_producto_mensaje").show();
	}else{
			$(".ocultar_contenido_producto").show();
			$(".ocultar_contenido_producto_mensaje").hide();
	}

	//transicion de imagen
	$(".img_modal_loader_e").html(loader_mini());
	$(".img_modal_loader_e").show();
	$(".img_prod_modal_e").hide();
	$(".img_prod_modal_e").attr('src',$(this).attr('imagen'));
	setTimeout(function() {$(".img_modal_loader_e").hide();$(".img_prod_modal_e").show();},1500)

	$(".descripcion_modal_e").html($(this).attr('descripcion'));
	$(".unidad_modal_e").html($(this).attr('unidad'));
	$(".input_orden").val(parseFloat($(this).attr('cantidad')).toFixed(2));


	$(".check_asado").prop('checked',false);
	$(".check_preparado").prop('checked',false);
	$(".check_asado").attr('departamento',$(this).attr('departamento'));
	if($(this).attr('asado')=='1'){$(".fyv_asado").show();$(".check_asado").prop('checked',true);}
	if($(this).attr('preparado')=='1'){$(".check_preparado").prop('checked',true);}
	$(".ord_detalles").val($(this).attr('detalles'));
	$(".check_asado_input").val($(this).attr('asado'));
	$(".check_preparado_input").val($(this).attr('preparado'));
	$(".select_termino").val($(this).attr('termino'));
	$(".select_corte").val($(this).attr('corte'));


	


	$(".ord_detalles").val($(this).attr('detalles'));
	$(".check_asado_input").val($(this).attr('asado'));


	//datos fara formulario 
	$(".btn_modal_guardar_e").attr('identificador',$(this).parent("div").attr('id'));
	$(".btn_modal_borrar_e").attr('identificador',$(this).parent("div").attr('id'));

	
	$("#imagen_modal_form_e").val($(this).attr('imagen'));
	$("#producto_carrito_modal_form_e").val($(this).attr('id_carrito_det'));
	$("#producto_modal_form_e").val($(this).attr('producto'));
	$("#departamento_modal_form_e").val($(this).attr('departamento'));
	$("#unidad_modal_form_e").val($(this).attr('unidad'));
	$("#precio_modal_form_e").val($(this).attr('precio'));
	$("#cliente_modal_form_e").val($("#id_cliente"));
	$("#descripcion_modal_form_e").val($(this).attr('descripcion'));
	$(".contenedor_menu_lateral_der").hide(300);
	

	if($(this).attr('departamento')=='005'||$(this).attr('departamento')=='002'){
		$(".row_asado").show();
		$(".unidad_modal_e").html("<div class='col-xs-4'></div><div class='col-xs-4'>"+
			"<select name='unidad' class='form-control cambio_unidad' style='text-align-last:center;' precio='"+$(this).attr('precio')+"'>"+
			"<option value='KG'>KG</option>"+
			"<option value='PZA'>PZA</option>"+
			"</select></div><div class='col-xs-4'></div>");
		console.log($(this).attr('unidad'));
		$(".cambio_unidad").val($(this).attr('unidad'));
			
	}else{
		$(".row_asado").hide();
	}
	if($(this).attr('departamento')=='005'){
		$(".row_preparado").hide();
		$(".row_preparado2").show();
	}else{
		$(".row_preparado").show();
		$(".row_preparado2").hide();
	}
	if($(this).attr('departamento')=='002'){$(".contenedor_corte").show();}
	else{ $(".contenedor_corte").hide(); }
})


	//general para todas las tablas
	$('.datatables').DataTable({
		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"language":{
		    "sProcessing":     "<i class='fa fa-spinner fa-spin fa-3x fa-fw'></i><span class='sr-only'>Loading..n.</span>",
		    "sLengthMenu":     "Mostrar _MENU_ registros",
		    "sZeroRecords":    "No se encontraron resultados",
		    "sEmptyTable":     "Ningún dato disponible en esta tabla",
		    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
		    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
		    "sInfoPostFix":    "",
		    "sSearch":         "Buscar:",
		    "sUrl":            "",
		    "sInfoThousands":  ",",
		    "sLoadingRecords": "Cargando...",
		    "oPaginate": {
		        "sFirst":    "Primero",
		        "sLast":     "Último",
		        "sNext":     "Siguiente",
		        "sPrevious": "Anterior"
		    },
		    "oAria": {
		        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
		        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
		    },
		    "buttons": {
		        "copy": "Copiar",
		        "colvis": "Visibilidad",
		        "excel": "Excel"
		    }
		},
		"initComplete": function( settings, json ) {
		    	$('.cargando_tabla').remove();
		    	$('.datatables').show();
		}
	});
	$(document).on("click",".panel_pedido1",function(){
		$("#contenido_pedido_modal").modal("show");
		$(".modal_contenido_carrito").html(loader());
		$("#id_pedido_form").val($(this).attr('id_pedido'));
		$(".pedidos_imp_pedido").attr('id_pedido',$(this).attr('id_pedido'));
		$(".pedidos_cambiar_sucursal").attr('id_pedido',$(this).attr('id_pedido'));
		$(".pedidos_cambiar_sucursal").attr('id_sucursal',$(this).attr('id_sucursal'));
		$(".btn_surtido").attr('id_pedido',$(this).attr('id_pedido'));
		$(".contenedor_btn_pedido").hide();
		resumen_pedido($(this).attr('id_pedido'));
		$.post("../Api_controller/get_carritos_departamento",{id:$(this).attr('id_carrito')},function(r){
			console.log(r);
			$(".modal_contenido_carrito").html(string_carrito_pedido(r));
			$("#contenido_pedido_modal").modal("show");
			$(".div_procesar").hide();
			$(".div_surtido").show();
			$(".contenedor_btn_pedido").show();
		})
	})
	//Actualizacion de paneles de pedidos
	if(window.location.href.split('/')[5]=='dash'){
		actualizar_dashboard();
		setInterval(function(){ actualizar_dashboard(); },10000);
	}
	

	function resumen_pedido(id_pedido){
		$.post("../Api_controller/get_pedido",{id:id_pedido},function(r_p){
			r_p = jQuery.parseJSON(r_p);
			
			$("#hidden_status").val(r_p.status);
			$("#hidden_servicio").val(r_p.servicio);
			$("#res_mod_consecutivo").html(r_p.consecutivo);
			$("#res_mod_nombre").html(r_p.nombre);
			$("#res_mod_sucursal").html(r_p.sucursal);
			if(r_p.servicio==1){$("#res_mod_servicio").html("A domicilio");}
			if(r_p.servicio==2){$("#res_mod_servicio").html("Paso por el");}
			$("#res_mod_dir").html(r_p.dir_calle+" "+r_p.dir_numero1+" "+r_p.dir_numero2+" "+r_p.dir_colonia);
			$("#res_mod_pedido").html(r_p.fecha+" "+r_p.hora);
			$("#res_mod_entrega").html(r_p.fecha_entrega+" "+r_p.hora_entrega);
			$("#res_mod_tomo").html(r_p.tomo);
			$("#res_mod_chofer").html(r_p.chofer);
			$("#res_mod_surtido").html(r_p.fecha_surtido+" "+r_p.hora_surtido);
			$("#res_mod_enviado").html(r_p.fecha_envio+" "+r_p.hora_envio);
		})
	}
	function actualizar_dashboard(){
		//status 1
		$.post("../Api_controller/get_all_carritos",{status:'1',servicio:'0'},function(p1){ 
			$("#pedidos_1").html(p1);
		})
		//status 2-1
		$.post("../Api_controller/get_all_carritos",{status:'2',servicio:'1'},function(p21){ 
			$("#pedidos_2_1").html(p21);
		})
		//status 2-2
		$.post("../Api_controller/get_all_carritos",{status:'2',servicio:'2'},function(p22){ 
			$("#pedidos_2_2").html(p22);
		})
		//statis 3
		$.post("../Api_controller/get_all_carritos",{status:'3',servicio:'0'},function(p3){
			$("#pedidos_3").html(p3);
		})
		//actualizar cantidades
		$.post("../Api_controller/get_cantidad_pedidos",function(cant){
			$(".cant_pedidos_span").html("0"); 
			$.each(jQuery.parseJSON(cant), function( i, sta ){
				$("#span_cant_pediddos_"+sta.status).html(sta.cantidad);
				if(sta.status==2){$("#span_cant_pediddos_2_"+sta.servicio).html(sta.cantidad);}
			})
		})
	}



	$(document).on("click",".panel_pedido2",function(){
		$("#contenido_pedido_modal").modal("show");
		$(".modal_contenido_carrito").html(loader());
		$("#id_pedido_form").val($(this).attr('id_pedido'));
		$(".pedidos_imp_pedido").attr('id_pedido',$(this).attr('id_pedido'));	
		resumen_pedido($(this).attr('id_pedido'));	
		$.post("../Api_controller/get_carritos_departamento",{id:$(this).attr('id_carrito')},function(r){
			console.log(r);
			$(".modal_contenido_carrito").html(string_carrito_pedido2(r));
			$("#contenido_pedido_modal").modal("show");
			$(".div_procesar").show();
			$(".div_surtido").hide();
		})
	})
	
	$(document).on("click",".panel_pedido3",function(){
		$("#contenido_pedido_modal").modal("show");
		$(".modal_contenido_carrito").html(loader());
		$("#id_pedido_form").val($(this).attr('id_pedido'));
		$(".pedidos_imp_pedido").attr('id_pedido',$(this).attr('id_pedido'));	
		resumen_pedido($(this).attr('id_pedido'));
		$.post("../Api_controller/get_carritos_departamento",{id:$(this).attr('id_carrito')},function(r){
			console.log(r);
			$(".modal_contenido_carrito").html(string_carrito_pedido2(r));
			$("#contenido_pedido_modal").modal("show");
			$(".div_procesar").hide();
			$(".div_surtido").hide();
		})
	})
	$(document).on("click",".btn_procesar",function(){
		$("#chofer_form").val("");
		if($("#hidden_status").val()=='2'&&$("#hidden_servicio").val()=='1'){
			$("#select_chofer").val("otro")
			$("#modal_chofer").modal('show');
		}else{
			procesar_pedido();
		}
	})

	$(document).on("click","#chofer_procesar",function(){
		$("#chofer_form").val($("#select_chofer").val());
		procesar_pedido();
	})

	function procesar_pedido(){
		if(confirm('Estas seguro de que deseas procesar este pedido?')){
			$("#modal_chofer").modal('hide');
			$.post("../Api_controller/editar_pedido",$("#procesar_pedido_form").serialize(),function(r){
				if(window.location.href.split('/')[5]=='dash'){
					actualizar_dashboard();
				}else{
					location.reload();
				}
			})
		}
	}

/*
	$(document).on("click",".btn_surtido",function(){
		if(confirm('Estas seguro de que deseas procesar este pedido?')){
			$(".check_green").each(function(index, el) {
				el.click();
			});
		}
	})
*/

	$(document).on("click",".btn_surtido",function(){
		if(confirm('Estas seguro de que deseas procesar este pedido?')){
			$.post("../Api_controller/pedido_surtido",{id_pedido:$(this).attr('id_pedido')},function(r){
				if(r=='1'){
					if(window.location.href.split('/')[5]=='dash'){
						actualizar_dashboard();
					}else{
						location.reload();
					}
				}
			});
		}
	})



	$(document).on("click",".carrito_det_check",function(){
		if($(this).attr('tipo')=='green'){
			$(this).parent('div').parent('div').addClass('carr_det_green');
			$(this).parent('div').parent('div').removeClass('carr_det_red');
		}else{
			$(this).parent('div').parent('div').addClass('carr_det_red');
			$(this).parent('div').parent('div').removeClass('carr_det_green');
		}
		$.post("../Api_controller/editar_carrito",{id_carrito_det:$(this).attr('id_carrito_det'),status:$(this).val()},function(r){
			console.log(r);
			if(r=='1'){
				location.reload();
			}
		})
	})
/*
	$(document).on("click", function(){
		//carrito_det_check
		$.post("../Api_controller/editar_carrito",{id_carrito_det:$(this).attr('id_carrito_det'),status:$(this).val()},function(r){
			console.log(r);
			if(r=='1'){
				location.reload();
			}
		})
	})

*/

	$(".pedidos_imp_pedido").click(function(){
		$.post('../Control_controller/imprimir_pedido', {id_pedido: $(this).attr('id_pedido')}, function(r) {
			alert('Pedido Impreso');
		});
	})
	//cambiar el pedido de sucursal
	$(".pedidos_cambiar_sucursal").click(function(){
		$("#pedidos_cambiar_sucursal_sucursal").val($(this).attr("id_sucursal"));
		$("#pedidos_cambiar_sucursal_pedido").val($(this).attr("id_pedido"));
		$("#modal_cambio_sucursal").modal("show");
		nombre_sucursal_select();
	})
	$("#pedidos_cambiar_sucursal_sucursal").change(function(){
		nombre_sucursal_select();
	})
	function nombre_sucursal_select(){
		$("#pedidos_cambiar_sucursal_nombre").val($("#pedidos_cambiar_sucursal_sucursal").find(':selected').attr('sucursal'));
	}
	$("#form_cambio_sucursal").submit(function(e) {
		e.preventDefault();
		$.post("../Control_controller/cambio_sucursal",$(this).serialize(),function(r){
			actualizar_dashboard();
			$("#contenido_pedido_modal").modal("hide");
			$("#modal_cambio_sucursal").modal("hide");
		})
	})

	/*     FUNCIONES DE IMAGENES     */
	$(document).on('change','.p_imagen',function(e){
		$("#inv_producto").val($(this).attr('id_producto'));
		$("#img_ver_mas").attr("src",URL.createObjectURL(e.target.files[0]));
		var canvas = $('#canvas_img')[0];
		canvas.getContext("2d").clearRect(0, 0, 300, 300);
		var img = new Image;
		img.onload = function(){
			canvas.getContext("2d").clearRect(0, 0, 300, 300);
			canvas.getContext("2d").drawImage(img,0,0,300,300);
			$("#imagen_base64").val(canvas.toDataURL());
			$.post("../Control_controller/subir_imagen_producto",$("#inv_form").serialize(),function(r){
				$(".td_inv_prod_"+$("#inv_producto").val()).html('<i class="fa fa-check-circle-o fa-2x" aria-hidden="true"></i>');
				$("#img_producto_"+$("#inv_producto").val()).attr('src',$("#base_url").val()+'assets/img/productos/'+$("#inv_producto").val()+'.png?'+Math.round(Math.random()*10))
			})
		};
		img.src = URL.createObjectURL(e.target.files[0]);

	})
	$(document).on("click",".file_camera",function(){
		$(this).parent('td').find('input').click();
	})
	/*     FUNCIONES DE IMAGENES     */



	/* FUNCIONES DE PAGO EN APP */
	$("#form_id_pedido").submit(function(e) {
		e.preventDefault();
		var encontrado = false;
		$(".a_ligar_pedido").each(function(index, el) {
			if(el.id=="pedido_"+$("#folio_pedido").val()){
				$(el).click();
				encontrado=true;
			}
		});
		if(!encontrado){
			mensaje_chofer("Folio de pedido NO localizado");
			$("#folio_pedido").val("");
			setTimeout(function() {$("#folio_pedido").select();}, 500);
		}else{
			$("#folio_pedido").val("");
		}
	});
	$(".a_ligar_pedido").click(function(){
		if($(this).attr('estatus')=='2'){
			$.post("../Control_controller/comprobante", {data: $(this).attr('valor')}, function(r) {
				$(".hide_print").hide(0, function() {
					$("#contenido_comprobante").html(r);
					window.print();
					$(".hide_print").show(0);
				});

				
			});
		}else{
			$("#captura_ticket_modal").modal("show");
			$("#folio_ticket").val("");
			$("#folio_pedido2").val($(this).attr('valor'));
			setTimeout(function() {$("#folio_ticket").select();}, 500);
		}
		
	})
	$("#form_buscar_ticket").submit(function(e){
		e.preventDefault();
		$.post("../Api_controller/get_ticket_avattia",{pedido:$("#folio_pedido2").val(),ticket:$("#folio_ticket").val()},function(r){
			if(r=='1'){location.reload();}
			else{
				alert(r);
			}
		});
	});
	$("#form_buscar_ticket_chofer").submit(function(e){
		e.preventDefault();
		$.post("../Chofer_controller/get_ticket_avattia_chofer",{data:$("#folio_pedido2").val()+"S"+$("#folio_ticket").val()},function(r){
			r = jQuery.parseJSON(r);
			if(r.codigo=='0'){mensaje_chofer(r.mensaje);}
			if(r.codigo=='1'){
				string_pedido_chofer(r);
				agregar_marcador_chofer(r.sucursal,r.consecutivo,r.lat,r.lon);
			}
			$("#captura_ticket_modal").modal("hide");
			setTimeout(function() {$("#folio_pedido").select();}, 2000);
		});
	});
	
});

function string_pedido_chofer(r){
	$("#lista_pedidos_chofer").append('<div class="col-sm-12">'+
				'<div class="panel panel-primary">'+
					'<div class="panel-body">'+	
						'<input type="hidden" value="'+r.id_pedido+'" name="id_pedido[]">'+
						'<input type="hidden" value="'+r.ticket+'" name="ticket[]">'+
						'<input type="hidden" value="'+r.total+'" name="total[]">'+
						'<b>'+r.nombre+'<span class="pull-right">'+r.total+'</span></b><br>'+
						r.direccion+'<BR>'+
					'</div>'+
				'</div>'+
			'</div>');

}

$("#btn_regresar_chofer").click(function(){
			window.location.href="https://sd.ferbis.com/index.php/Login_controller/soy_chofer";
})
$("#btn_finalizar_chofer").click(function(){
	console.log("hola");
	$.post(url_api+'chofer_pedidos_listos',$("#form_pedidos_chofer").serialize(),function(){
		mensaje_chofer("Pedidos registrados exitosamente!");
		window.location.href="https://sd.ferbis.com/index.php/Login_controller/soy_chofer";
	})
})
function mensaje_chofer($mensaje){
	$(".chofer_modal_mensaje_body").html($mensaje);
	$("#chofer_modal_mensaje").modal({backdrop: 'static', keyboard: false}); 
	setTimeout(function() {
		$("#chofer_modal_mensaje").modal("hide");
	}, 3000);
}
var contador_pedidos_chofer = 0;
function agregar_marcador_chofer(sucursal,consecutivo,rlat,rlon){
	if(contador_pedidos_chofer==0){
		contador_pedidos_chofer++;
		if(sucursal=='1'){
			var sucursal = new google.maps.Marker({
		      draggable: false,
		      animation: google.maps.Animation.DROP,
		      position: {lat: 32.66689700, lng: -115.43701200},
		      map: map_pedidos,
		      icon: '../../assets/img/map_icon3.png'
		    });
		    bounds.extend(sucursal.position);
		}
		if(sucursal=='2'){
			var sucursal = new google.maps.Marker({
		      draggable: false,
		      animation: google.maps.Animation.DROP,
		      position: {lat: 32.63357400, lng: -115.49321200},
		      map: map_pedidos,
		      icon: '../../assets/img/map_icon3.png'
		    });
		    bounds.extend(sucursal.position);
		}
	}
	rlat = parseFloat(rlat);
	rlon = parseFloat(rlon);
	var marker = new google.maps.Marker({
	      draggable: false,
	      animation: google.maps.Animation.DROP,
	      position: {lat: rlat, lng: rlon},
	      map: map_pedidos,
	      icon: '../../assets/img/map_icon.png'
	    });
	console.log(bounds);
	bounds.extend(marker.position);
	map_pedidos.fitBounds(bounds);
}


function string_carrito_pedido(string_json){
var string_ret="";
var id_departamento = "0";
$.each(jQuery.parseJSON(string_json), function( i, prod ) {
	//dividir por departamento
	if(id_departamento!=prod.id_departamento){
		id_departamento=prod.id_departamento;
		string_ret+="<h4 class='titulo2'>"+prod.nombre_departamento+"</h4>";
	}
	//si ya esta marcado agregar la clase
	var clase="";
	var green="";
	var red="";
	if(prod.status==1){clase="carr_det_green";green="checked";}
	if(prod.status==2){clase="carr_det_red";red="checked";}
	if(prod.detalles!=""){prod.detalles="<br>"+prod.detalles;}
	var asado=""; if(prod.asado=='1'){ asado='(ASA) ';}

	var preparado=""; if(prod.preparado=='1'&&prod.id_departamento=='002'){ preparado='(PRE) ';}
	var corte=""; if(prod.corte!='N'&&prod.corte!=''&&prod.id_departamento=='002'){ corte='(COR '+prod.corte+') '; }
	var termino=""; if(prod.termino!=''&&prod.asado=='1'&&prod.id_departamento=='002'){ termino='(TER '+prod.termino+') ';}

	string_ret+="<div class='articulo_carrito_pedido carrito_det"+prod.id_carrito_det+" "+clase+"' >"+
	  				"<div class='col-xs-2 car_cantidad'>"+parseFloat(prod.cantidad).toFixed(2)+"<br><b>"+prod.unidad+"</b></div>"+
	  				"<div class='col-xs-8 car_desc'>"+prod.descripcion+"<br>"+asado+preparado+corte+termino+prod.detalles+"</div>"+
	  				"<div class='col-xs-1'><input type='radio' class='form-contorl carrito_det_check check_green' tipo='green' id_carrito_det='"+prod.id_carrito_det+"' name='check"+prod.id_carrito_det+"' value='1' "+green+"></div>"+
	  				"<div class='col-xs-1'><input type='radio' class='form-contorl carrito_det_check' tipo='red' id_carrito_det='"+prod.id_carrito_det+"' name='check"+prod.id_carrito_det+"' value='2' "+red+"></div>"+
	  				"</div>";
});
return string_ret;
}

function string_carrito_pedido2(string_json){
var string_ret="";
var id_departamento = "0";
$.each(jQuery.parseJSON(string_json), function( i, prod ) {
	//dividir por departamento
	if(id_departamento!=prod.id_departamento){
		id_departamento=prod.id_departamento;
		string_ret+="<h4 class='titulo2'>"+prod.nombre_departamento+"</h4>";
	}
	var icon="";
	if(prod.status==0){icon="<div class='col-xs-3' style='text-align:center'><i class='fa fa-shopping-basket' aria-hidden='true'></i></div>";}
	if(prod.status==1){icon="<div class='col-xs-3' style='color:#1E8449;text-align:center'><i class='fa fa-check-square-o' aria-hidden='true'></i></div>";}
	if(prod.status==2){icon="<div class='col-xs-3' style='color:#A93226;text-align:center'>Agotado</div>";}
	if(prod.detalles!=""){prod.detalles="<br>"+prod.detalles;}
	var asado=""; if(prod.asado=='1'){ asado='(ASA) ';}

	var preparado=""; if(prod.preparado=='1'&&prod.id_departamento=='002'){ preparado='(PRE) ';}
	var corte=""; if(prod.corte!='N'&&prod.corte!=''&&prod.id_departamento=='002'){ corte='(COR '+prod.corte+') '; }
	var termino=""; if(prod.termino!=''&&prod.asado=='1'&&prod.id_departamento=='002'){ termino='(TER '+prod.termino+') ';}

	string_ret+="<div class='articulo_carrito_pedido' >"+
	  				"<div class='col-xs-2 car_cantidad'>"+parseFloat(prod.cantidad).toFixed(2)+"<br><b>"+prod.unidad+"</b></div>"+
	  				"<div class='col-xs-7 car_desc'>"+prod.descripcion+"<br>"+asado+preparado+corte+termino+prod.detalles+"</div>"+icon+
	  				"</div>";
});
return string_ret;
}

function loader(){
		return '<div style="text-align:center;padding-top:100px;"><i class="fa fa-spinner fa-spin fa-5x fa-fw"></i><span class="sr-only"></span></div>';
}


function string_articulos(string_json){
		var string_ret="";
		$.each(jQuery.parseJSON(string_json), function( i, prod ) {
			// se utiliza puntuacion para la imagen
			string_ret+="<div class='row_articulo' "+
							"producto='"+prod.producto+"' "+
							"departamento='"+prod.departamento+"' "+
							"descripcion='"+capitalize(prod.descripcion)+"' "+
							"unidad='"+prod.unidad+"' "+
							"imagen='"+prod.puntuacion+"' "+
							"precio='"+prod.precio+"' "+
							">"+
			  				"<div class='col-xs-12 col-md-4 articulo'><div class='col-xs-2 cont_imagen_articulo'>"+
			  				"<div class='art_img' style='height:80px;'></div>"+
			  				"</div><div class='col-xs-10 articulo_desc'>"+
			  				"<div class='col-xs-12'><div class='art_desc'><b>"+capitalize(prod.descripcion)+"</b></div></div>"+
			  				"<div class='col-xs-12'><div class='art_um'>$"+parseFloat(prod.precio).toFixed(2)+" "+prod.unidad+"</div></div>"+
			  				"</div></div></div>";
		});
		//agregamos al contenedor
		$("#contenedor_articulos").hide();
		$("#contenedor_articulos").html(string_ret);
		$("#contenedor_articulos").slideDown(500);

		$(".art_img").each(function(index, el) {
			setTimeout(function() {
				$(el).fadeOut(100,function(){
					$(el).html("<img src='"+$(el).parent("div").parent("div").parent("div").attr('imagen')+"' class='img_art'>");
					$(el).fadeIn(100);
				});
			},100*index);

		});
	}
	function string_articulos2(string_json){
		var string_ret="";
		var prod = jQuery.parseJSON(string_json);
		$("#f_precio").val(prod.precio);
		$("#f_departamento").val(prod.departamento);
		$("#f_descripcion").val(capitalize(prod.descripcion));
		$("#f_imagen").val(prod.puntuacion);

		// se utiliza puntuacion para la imagen
		string_ret+="<div "+
						"producto='"+prod.producto+"' "+
						"departamento='"+prod.departamento+"' "+
						"descripcion='"+capitalize(prod.descripcion)+"' "+
						"unidad='"+prod.unidad+"' "+
						"imagen='"+prod.puntuacion+"' "+
						"precio='"+prod.precio+"' "+
						">"+
		  				"<div class='col-xs-12 articulo2'><div class='col-xs-2 cont_imagen_articulo'>"+
		  				"<div class='art_img' style='height:100px;'></div>"+
		  				"</div><div class='col-xs-10 articulo_desc'>"+
		  				"<div class='col-xs-12'><div class='art_desc'><b>"+capitalize(prod.descripcion)+"</b></div></div>"+
		  				"<div class='col-xs-12'><div class='art_um'>$"+parseFloat(prod.precio).toFixed(2)+" "+prod.unidad+"</div></div>"+
		  				"</div></div></div>";
		//agregamos al contenedor
		$("#contenedor_articulo").hide();
		$("#contenedor_articulo").html(string_ret);
		$("#contenedor_articulo").slideDown(500);

		$(".art_img").each(function(index, el) {
			setTimeout(function() {
				$(el).fadeOut(100,function(){
					$(el).html("<img src='"+$(el).parent("div").parent("div").parent("div").attr('imagen')+"' class='img_art'>");
					$(el).fadeIn(100);
				});
			},100*index);

		});
	}
	/*
	function actualizar_carrito(){
		$(".contenido_carrito").html(loader());
		$.post(url_api+'get_carrito_activo',{id_cliente:sesion_local.getItem("FerbisAPP_id")},function(r){
			$(".cant_carrito").html(jQuery.parseJSON(r).length);
			if(r==0){
				$(".contenido_carrito").html("<div class='carrito_vacio'>Carrito vacío</div>");
				$(".div_procesar_pedido").hide();
			}else{	
				$(".div_procesar_pedido").show();
				$(".contenido_carrito").html(string_carrito(r));
			}
			var total_aprox=0;
			$(".car_importe").each(function() {total_aprox+=parseFloat($(this).html());});
			$(".total_pedido").html(parseFloat(total_aprox).toFixed(2));
		})
	}
	*/
	function row_string_carrito(form){
		var prod = objectifyForm(form);
		var string_ret="";
		var asado=""; if(prod.asado=='1'){ asado='<i class="fa fa-fire ico_asado" aria-hidden="true"></i>';}
		var det = ""; if(prod.detalles!=''){ det='<br>*'+prod.detalles;}
		string_ret+="<input type='hidden' value='"+parseFloat(prod.cantidad*prod.precio).toFixed(2)+"' class='calculo_total'>";

		string_ret+="<input type='hidden' value='' name='id_carrito[]' class='form_carrito_det_id_carrito'>";
		string_ret+="<input type='hidden' value='"+$("#id_cliente").val()+"' name='id_cliente[]'>";
		string_ret+="<input type='hidden' value='"+prod.producto+"' name='producto[]'>";
		string_ret+="<input type='hidden' value='"+prod.unidad+"' name='unidad[]'>";
		string_ret+="<input type='hidden' value='"+prod.departamento+"' name='departamento[]'>";
		string_ret+="<input type='hidden' value='"+prod.cantidad+"' name='cantidad[]'>";
		string_ret+="<input type='hidden' value='"+prod.precio+"' name='precio[]'>";
		string_ret+="<input type='hidden' value='"+capitalize(prod.descripcion)+"' name='descripcion[]'>";
		string_ret+="<input type='hidden' value='"+prod.asado+"' name='asado[]'>";
		string_ret+="<input type='hidden' value='"+prod.detalles+"' name='detalles[]'>";
		string_ret+="<input type='hidden' value='"+prod.termino+"' name='termino[]'>";
		string_ret+="<input type='hidden' value='"+prod.corte+"' name='corte[]'>";
		string_ret+="<input type='hidden' value='"+prod.preparado+"' name='preparado[]'>";

		string_ret+="<a href='#' class='articulo_carrito'"+
		"producto='"+prod.producto+"' "+
		"departamento='"+prod.departamento+"' "+
		"cantidad='"+prod.cantidad+"' "+
		"asado='"+prod.asado+"' "+
		"descripcion='"+capitalize(prod.descripcion)+"' "+
		"unidad='"+prod.unidad+"' "+
		"detalles='"+prod.detalles+"' "+
		"imagen='"+prod.imagen+"' "+
		"termino='"+prod.termino+"' "+
		"corte='"+prod.corte+"' "+
		"preparado='"+prod.preparado+"' "+
		"precio='"+prod.precio+"' >"+
			"<div class='col-xs-2 car_cantidad'>"+parseFloat(prod.cantidad).toFixed(2)+"<br><b>"+prod.unidad+"</b></div>"+
			"<div class='col-xs-8 car_desc'>"+asado+" "+capitalize(prod.descripcion)+det+"</div>"+
			"<div class='col-xs-2 car_importe'>"+parseFloat(prod.cantidad*prod.precio).toFixed(2)+"</div>"+
			"</a>";
		return string_ret;
	}

function capitalize(texto) {
	texto = texto.toLowerCase();
  	return texto[0].toUpperCase() + texto.slice(1);
}
function loader_mini(){
		return '<div style="height: 80px;display: flex;align-items: center;justify-content: center; color:gray;"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only"></span></div>';
}

function iniciar_mapa(){
	construir_mapa({lat: 32.666968, lng: -115.437199});
}
function geolacalizar_direccion(){
	var direccion=	$("#pedido_calle").val()+", "+
						$("#pedido_numero1").val()+", "+
						$("#pedido_colonia").val()+
						", Mexicali, BC";
	geocoder = new google.maps.Geocoder();
	geocoder.geocode({ 'address': direccion}, function(results, status) {
		construir_mapa(results[0].geometry.location);
	});
}

function construir_mapa(myLatLng){
				//sucursal_cercana();
				var map = new google.maps.Map(document.getElementById('map_pedido'), {
		          zoom: 17,
		          center: myLatLng
		        });
		        var marker = new google.maps.Marker({
		          draggable: true,
		          animation: google.maps.Animation.DROP,
		          position: myLatLng,
		          map: map,
		          icon: '../../assets/img/map_icon.png',
		          title: 'Ferbis Brasil'
		        });
		        sucursal_cercana(myLatLng.lat,myLatLng.lng);
		        marker.addListener("dragend", function(e) { 
		        	$(".cuenta_lat").val(e.latLng.lat);
					$(".cuenta_lon").val(e.latLng.lng);
					sucursal_cercana(e.latLng.lat,e.latLng.lng);
		        }); 
		        map.addListener('click', function(e) {
					marker.setPosition(e.latLng);
					$(".cuenta_lat").val(e.latLng.lat);
					$(".cuenta_lon").val(e.latLng.lng);
					sucursal_cercana(e.latLng.lat,e.latLng.lng);
				});
			}
function sucursal_cercana(lat,lon){
	/*
	$("#form_pedido_lat").val(lat);
	$("#form_pedido_lon").val(lon);
	$.post(url_api+'sucursal_cercana',{lat:lat,lon:lon},function(r){
		var sucursal = jQuery.parseJSON(r);
		//$(".pedido_sucursal_cercana").html(sucursal.sucursal+" "+sucursal.distancia+"km");
		$("#form_pedido_id_sucursal").val(sucursal.id_sucursal);
		$("#form_pedido_sucursal").val(sucursal.sucursal);
		$("#form_pedido_distancia").val(sucursal.distancia);
		calcular_envio(sucursal.id_sucursal);
	})
	*/
}
function calcular_envio(id_sucursal){
	//contamos los asados
	var asados = 0;
	$(".articulo_carrito").each(function(index, el) {if($(this).attr('asado')==1){asados++;}});
	//verificar el horario disponible
	$.post("../Api_controller/calcular_hora_entrega2",{servicio:$("#tipo_sevicio").val(),dia:$("#fecha_pedido").val(),id_sucursal:id_sucursal,asado:asados,fuente:"llamada"},function(r){
		//verificamos si es un json
		$("#select_horas_disponibles").html("");
		try{var horarios = jQuery.parseJSON(r);}
       	catch(err){alert(r); return;}
		//llenamos el select
		
		$.each(horarios, function( i, pedido ){
			if(pedido.disponible=='s'){
				$("#select_horas_disponibles").append("<option value='"+pedido.hora+":00'>"+pedido.hora_nice+"</option>");
			}
		})
		$.each(horarios, function( i, pedido ){
			if(pedido.disponible=='s'){
				$("#fecha_pedido").val(pedido.fecha);
				$("#select_horas_disponibles").val(pedido.hora+":00");
				return false;
			}
		})
	})
}
//procesar pedidos anteriores
$(".procesar_historico").click(function(){
	$.post("../Control_controller/procesar_historicos",function(r){
		console.log(r);
		location.reload();
	});
})

function notificacion(mensaje){
	$(".notificacion").html(mensaje);
	$(".notificacion").show(100);
	setTimeout(function() {$(".notificacion").hide(100);}, 3000);
}
function objectifyForm(formArray) {//serialize data function
	var returnArray = {};for (var i = 0; i < formArray.length; i++){returnArray[formArray[i]['name']] = formArray[i]['value'];}
	return returnArray;
}
function formato_12hrs(hora){
	var ampm='am';
	if(hora>11){
		ampm='pm';
		hora=hora-12;
	}
	if(hora==0){hora=12;}
	return hora+ampm;
}

