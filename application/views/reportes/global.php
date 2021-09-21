
<style type="text/css">
	tr th,
	tr td{
		text-align: center;
	}
	.resumen_pedidos_table{
		margin-top: 20px;
	}
	.div_excel{
		margin-top: 30px;
	}
	.div_cont_reporte{
		height: 500px;
		overflow-y: auto;
		text-align: center; 
	}
</style>
<div class="col-md-4">
	<div class="panel panel-default">
		<div class="panel-heading" style="text-align: center">
			Resumen de pedidos
		</div>
		<div class="panel-body">
			<div class="col-xs-5">
				Desde
				<input type="date" class="form-control rp_fecha1" value="<?= date('Y-m-d') ?>">
			</div>
			<div class="col-xs-5">
				Hasta
				<input type="date" class="form-control rp_fecha2" value="<?= date('Y-m-d') ?>">
			</div>
			<div class="col-xs-2"><br>
				<a class="btn btn-warning btn_consultar"><i class="fa fa-search" aria-hidden="true"></i></a>
			</div>
			<div class="col-xs-12 div_excel">
				<a class="btn btn-success btn_excel pull-left btn-sm"><i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
			</div>
			<div class="col-xs-12 div_cont_reporte">
				<div class="contenedor_resumen_pedidos"></div>
			</div>
		</div>
	</div>
</div>

<div class="col-md-4">
	<div class="panel panel-default" style="text-align: center">
		<div class="panel-heading">
			Top 10 productos pedidos
		</div>
		<div class="panel-body">
			<div class="col-xs-5">
				Desde
				<input type="date" class="form-control pt_fecha1" value="<?= date('Y-m-d') ?>">
			</div>
			<div class="col-xs-5">
				Hasta
				<input type="date" class="form-control pt_fecha2" value="<?= date('Y-m-d') ?>">
			</div>
			<div class="col-xs-2"><br>
				<a class="btn btn-warning btn_consultar_ptop"><i class="fa fa-search" aria-hidden="true"></i></a>
			</div>
			<div class="col-xs-12 div_excel">
				<a class="btn btn-success btn_excel pull-left btn-sm"><i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
			</div>
			<div class="col-xs-12 div_cont_reporte">
				<div class="tabla_productos_top"></div>
			</div>
		</div>
	</div>
</div>


<div class="col-md-4">
	<div class="panel panel-default" style="text-align: center">
		<div class="panel-heading">
			Productos marcados como agotados
		</div>
		<div class="panel-body">
			<div class="col-xs-5">
				Desde
				<input type="date" class="form-control pa_fecha1" value="<?= date('Y-m-d') ?>">
			</div>
			<div class="col-xs-5">
				Hasta
				<input type="date" class="form-control pa_fecha2" value="<?= date('Y-m-d') ?>">
			</div>
			<div class="col-xs-2"><br>
				<a class="btn btn-warning btn_consultar_pagotados"><i class="fa fa-search" aria-hidden="true"></i></a>
			</div>
			<div class="col-xs-12 div_excel">
				<a class="btn btn-success btn_excel pull-left btn-sm"><i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
			</div>
			<div class="col-xs-12 div_cont_reporte">
					<div class="tabla_productos_agotados"></div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div id="modal_reportes" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4>Detalles de producto</h4>
      </div>
      <div class="col-xs-12 div_excel">
				<a class="btn btn-success btn_excel pull-left btn-sm"><i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
			</div>
      <div class="modal-body modal_reportes_body div_cont_reporte">
        
      </div>
    </div>
  </div>
</div>