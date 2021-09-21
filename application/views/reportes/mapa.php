<div class="col-md-12">
	<div class="panel panel-info">
		<div class="panel-heading">Mapa de distribucion de pedidos</div>
		<div class="panel-body">
			<div class="col-sm-4" id="map_pedidos" style="height: 600px;width: 100%"></div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function constrir_mapa_pedidos(){
		var map_pedidos = new google.maps.Map(document.getElementById('map_pedidos'), {
      zoom: 10,
      center: {lat:32.62507103135048,lng:-115.45348998340425}
    });

	<?php $markerid=0; foreach($pedidos as $p){?>
	    var marker<?= $markerid++; ?> = new google.maps.Marker({
	      draggable: false,
	      animation: google.maps.Animation.DROP,
	      position: {lat: <?= $p->lat ?>, lng: <?= $p->lon ?>},
	      map: map_pedidos,
	      title: '<?= $p->cant ?> pedidos',
	      icon: '../../assets/img/map_icon2.png'
	    });
	<?php } ?>
	}

	$(document).ready(function() {
		setTimeout(function() { constrir_mapa_pedidos(); }, 1000);
	});
	
</script>