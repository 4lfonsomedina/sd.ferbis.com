<b>Nombre:</b> <?= $encuesta->nombre ?><br>
<b>Telefono:</b> <?= $encuesta->telefono ?><br>
<b>Pedidos Realizados:</b> <?= $encuesta->pedidos ?><br>
<b>Comentario:</b> <?= $encuesta->comentario ?><br><br>
<center>
	<b>¿Cual fue tu experiencia con los tiempos de entrega?</b><br>
	<?= $encuesta->tiempo ?> <i class="fa fa-star" aria-hidden="true"></i><br><br>

	<b>¿Que le pareció el catálogo de productos de la aplicación?</b><br>
	<?= $encuesta->productos ?> <i class="fa fa-star" aria-hidden="true"></i><br><br>

	<b>¿Cual fue su experiencia con la calidad del producto surtido por el personal de Ferbis?</b><br>
	<?= $encuesta->calidad ?> <i class="fa fa-star" aria-hidden="true"></i><br><br>

	<b>¿Como evaluaría la facilidad de la aplicación al realizar un pedido?</b><br>
	<?= $encuesta->aplicacion ?> <i class="fa fa-star" aria-hidden="true"></i><br><br>

	<b>¿En general cual fue su experiencia al ordenar por la aplicación Ferbis?</b><br>
	<?= $encuesta->general ?> <i class="fa fa-star" aria-hidden="true"></i><br><br>
</center>





