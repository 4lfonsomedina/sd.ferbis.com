<style type="text/css">
	table tr td,table tr th{
		font-size: 14px;
	}
</style>
<table width="100%">
	<tr>
		<th colspan="3" style="text-align: center;font-weight: bold;font-size: 20;">
			DELI MARKET POR FERBIS S.A. DE C.V.<br>
		</th>
	</tr>
	<tr>
		<th colspan="3" style="text-align: center;font-size: 16;">
			Pagos en APP del periodo <?= $fecha1 ?> al <?= $fecha2 ?><br><br>
		</th>
	</tr>
	<tr>
		<th colspan="3" style="text-align: center">
		</th>
	</tr>
	<tr>
		<th style="text-align: left;">Referencia</th>
		<th style="text-align: left;">Tipo</th>
		<th style="text-align: right;">Importe</th>
	</tr>
	<?php 
	$total=0;
	foreach($comprobantes as $c){ $c = json_decode($c->resultado);if($c->BNRG_CODIGO_PROC=='A'){ ?>
		<tr>
			<td style="text-align: left; font-size: 12;"><?= $c->BNRG_FECHA_LOCAL." ".$c->BNRG_HORA_LOCAL."<br>".$c->BNRG_REFERENCIA ?></td>
			<td style="text-align: left;"><?php if(isset($c->BNRG_TIPO_CUENTA)){ echo str_replace("-", "", $c->BNRG_TIPO_CUENTA);}else{ echo "PRIVADO";} ?></td>
			<td style="text-align: right;"><?= $c->BNRG_MONTO_TRANS ?> <?php $total+=$c->BNRG_MONTO_TRANS; ?></td>
		</tr>
	<?php }} ?>
	<tr>
		<th colspan="3" style="text-align: right;font-size: 16; border-top: solid 2px;">
			<?= number_format($total,2) ?><br><br><br>.
		</th>
	</tr>
</table>


<script type="text/javascript">
	window.print();
	setTimeout(function() { window.close(); }, 2000);
	
</script>