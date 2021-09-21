<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<style type="text/css">
	.font_red{
		font-weight: bold;
		color:red;
	}
	.font_green{
		font-weight: bold;
		color:green;
	}
	.font_orange{
		font-weight: bold;
		color:orange;
	}
	.tabla_encuesta_view tr td{
		padding-bottom: 25px;
	}
	.bloqueo{
		position: absolute;
		background-color: black;
		width: 100%;
		height: 100%;
		margin: -16px 0px 0px 0px;
		z-index: 150;
		padding-top: 60px;
	}
</style>
<!-- Pantalla de bloqueo 

<div class="bloqueo">
	<form id="form_acceso">
		<center>
			<input type="password" id="psw_acceso" placeholder="Contraseña" autofocus><br><br>
			<button type="submit" class="btn btn-outline-primary" style="background-color: white" > Entrar > </button>
		</center>
	</form>
</div>
-->
<!-- RESUMENES PARA GRAFICA -->
<?php 

$TIEM=0;
$PROD=0;
$CALI=0;
$APLI=0;
$GENE=0;

foreach($encuesta as $e){ 
	$TIEM+=$e->tiempo;
	$PROD+=$e->productos;
	$CALI+=$e->calidad;
	$APLI+=$e->aplicacion;
	$GENE+=$e->general;
}

?>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {

    var data = google.visualization.arrayToDataTable([
      ['Area', 			'Calificacion'],
      ['TIEMPO',		<?= $TIEM ?>],
      ['PRODUCTOS',	<?= $PROD ?>],
      ['CALIDAD',			<?= $CALI ?>],
      ['APLICACION',		<?= $APLI ?>],
      ['GENERAL',		<?= $GENE ?>]
    ]);

     var options = {
    chartArea: {
      height: '100%',
      width: '100%',
      top: 15
    },
    height: '100%',
    width: '100%',
    isStacked: true,
    legend: {
      position: 'right',
      textStyle: {
        color: '#999'
      }
    },
    title: ''
  };

    var chart = new google.visualization.PieChart(document.getElementById('piechart'));
    chart.draw(data, options);
  }

  
</script>

<?php 

	$P_TIEM=($TIEM/(count($encuesta)*5))*100;
	$P_PROD=($PROD/(count($encuesta)*5))*100;
	$P_CALI=($CALI/(count($encuesta)*5))*100;
	$P_APLI=($APLI/(count($encuesta)*5))*100;
	$P_GENE=($GENE/(count($encuesta)*5))*100;

?>

<script type="text/javascript">
	google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
	        ['Area', 			'PORCENTAJE', { role: "style" } ],
	      	['TIEM',			<?= $P_TIEM ?>, "#b87333"],
	      	['PROD',		<?= $P_PROD ?>, "silver"],
	      	['CALI',			<?= $P_CALI ?>, "#b87333"],
	      	['APLI',			<?= $P_APLI ?>, "silver"],
	      	['GENE',			<?= $P_GENE ?>, "#b87333"]
        ]);

        var options = {
        title: "Porcentaje de aprobacion",
        width: '100%',
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
</script>


<center>
<div id="piechart" style="width: 80%; height: 250px;"></div>
<div id="columnchart_material" style="width: 80%; height: 250px;"></div>
</center>
<br><br><br><br><br><br><br><br>
<body>
<div class="container mt-3" style="background-color: white">
	
	<h4 align="center">Resultado de Encuesta</h4>
<div style="max-height: 500px; overflow-y: auto;">
	<table class="table table-striped table-hover" style="font-size: 12px;">
		<thead>
			<tr>
				<th>Fecha</th>
				<th>Nombre</th>
				<th>Evaluacion</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($encuesta as $e){ 
				 $suma = $e->tiempo+$e->productos+$e->calidad+$e->aplicacion+$e->general;
				?>
			<tr class="tr_encuesta" id_encuesta='<?= $e->id_encuesta ?>'>
				<td><?= formato_fecha($e->fecha) ?></td>
				<td><?= $e->nombre ?></td>
				<td class="<?php if($suma<20){echo 'font_red';} if($suma>23){echo 'font_green';} ?>"><?= $suma ?>/25</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>

</body>


<!-- Modal -->
<div class="modal fade" id="modal_nueva_encuesta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<form action="<?= site_url('Mexquite/encuesta_mex') ?>" method="POST">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Nueva Encuesta</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <input type="text" name="mesero" placeholder="Mesero" class="form-control">
	        <select class="form-control mt-3" name="mesa">
	        	<option value="barra"> BARRA </option>
	        	<?php for($i=1;$i<26;$i++){ ?>
	        		<option value="Mesa<?= $i ?>">Mesa<?= $i ?></option>
	        	<?php } ?>
	        </select>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
	        <button type="submit" class="btn btn-primary">Iniciar Encuesta</button>
	      </div>
	    </div>
	  </div>
	</form>
</div>

<!-- Modal -->
<div class="modal fade" id="modal_ver_encuesta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Encuesta</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body" id="cuerpo_encuesta">
	        
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-warning" data-dismiss="modal"> < Regresar</button>
	      </div>
	    </div>
	  </div>
</div>

  
<script type="text/javascript">

    $(document).ready(function() {
      
      //clave de acceso
      $("#form_acceso").submit(function(e){
      	e.preventDefault();
      	if($("#psw_acceso").val()=="ferbis.3623"){$(".bloqueo").hide(500);}
      	else{alert("Clave Incorrecta");}
      })

      $("#btn_encuesta").click(function(){
      	$("#modal_nueva_encuesta").modal("show");
      })

      $(".tr_encuesta").click(function(){
      	$.post("<?= site_url('Encuesta_controller/encuesta/') ?>"+$(this).attr("id_encuesta") , function(result){
      		$("#cuerpo_encuesta").html(result);
      	})
      	$("#modal_ver_encuesta").modal("show");
      })

      $(".font_red").click(function(){
      	//alert("presionaste un elemento con la clase font_red");
      })

    });

  </script>