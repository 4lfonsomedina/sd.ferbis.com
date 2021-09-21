$(document).ready(function() {

	//al presionar boton de consulta de pedidos
	$(".btn_consultar").click(function(){ 
		reporte_resumen1();
	})
	setTimeout(function(){reporte_resumen1();}, 1000);
	function reporte_resumen1(){
		$.post('Reportes_controller/get_pedidos', {fecha1:$(".rp_fecha1").val(),fecha2:$(".rp_fecha2").val()}, function(r) {
			$(".contenedor_resumen_pedidos").html(r);
		});
	}

	//get_top
	$(".btn_consultar_ptop").click(function(){ 
		reporte_resumen2();
	})
	setTimeout(function(){reporte_resumen2();}, 1000);
	function reporte_resumen2(){
		$.post('Reportes_controller/get_top', {fecha1:$(".pt_fecha1").val(),fecha2:$(".pt_fecha2").val()}, function(r) {
			$(".tabla_productos_top").html(r);
		});
	}

	//get_top
	$(".btn_consultar_pagotados").click(function(){ 
		reporte_resumen3();
	})
	setTimeout(function(){reporte_resumen3();}, 1000);
	function reporte_resumen3(){
		$.post('Reportes_controller/get_agotados', {fecha1:$(".pa_fecha1").val(),fecha2:$(".pa_fecha2").val()}, function(r) {
			$(".tabla_productos_agotados").html(r);
		});
	}
	$(".btn_excel").click(function(e){
		//Conseguir nombre del documento
		var tabla = $(this).parent('div').next('div').find('table');
		tabla.table2excel({
			exclude: ".noExl",
			name: "Excel Document Name",
			filename: tabla.attr('nombre') + ".xls",
			fileext: ".xls",
			exclude_img: true,
			exclude_links: true,
			exclude_inputs: true
		});
	});
	//al presionar los detalles del producto
	$(document).on("click",".detalles_top",function(){
		$.post('Reportes_controller/detalle_producto_top', {producto:$(this).attr("prod"),fecha1:$(this).attr("f1"),fecha2:$(this).attr("f2")}, function(r) {
			$(".modal_reportes_body").html(r);
			$("#modal_reportes").modal("show");
		});
	})
});