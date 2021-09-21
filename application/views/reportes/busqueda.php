<?
//consecutivo,nombre, dir_colonia, dir_calle, dir_numero, telefono, fecha_entrega, hora_entrega, origen, tomo, chofer


?>

<div class="panel panel-primary">
	<div class="panel-heading">Consulta de pedidos</div>
	<div class="panel-body">
		<div class="cargando_tabla">
			<h1><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i> Cargando Información...</h1>
		</div>
		<div class="col-xs-12 div_excel">
				<a class="btn btn-success btn_excel pull-left btn-sm"><i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
			</div>
			<div class="col-xs-12 div_cont_reporte">
				<table class="table table-condensed datatables" hidden>
					<thead>
						<tr>
							<th>Sucursal</th>
							<th>Folio</th>
							<th>Tipo</th>
							<th>Entega</th>
							<th>Nombre</th>
							<th>Colonia</th>
							<th>Calle</th>
							<th>Telefono</th>
							<th>Tomo</th>
							<th>Chofer</th>
						</tr>
					</thead>
					<tbody>
						<? foreach($pedidos as $p){ ?>
							<tr>
								<td><?= $p->sucursal ?></td>
								<td><?= $p->consecutivo ?></td>
								<td><? if($p->servicio=='1'){echo "ENVIO";}else{echo "VXEL";} ?></td>
								<td><?= $p->fecha_entrega." ".$p->hora_entrega ?></td>
								<td><?= $p->nombre ?></td>
								<td><?= $p->dir_colonia ?></td>
								<td><?= $p->dir_calle ?></td>
								<td><?= $p->telefono ?></td>
								<td><?= $p->tomo ?></td>
								<td><?= $p->chofer ?></td>
							</tr>
						<? } ?>
					</tbody>
				</table>
			</div>
	</div>
</div>
