<!DOCTYPE html>
<html>
<head>
	<title>SD.ferbis</title>
	<meta name="referrer" content="no-referrer" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="<?= base_url('assets/js/jquery.js') ?>"></script>
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/bootstrap.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/general.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/datatables.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/chosen.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/font-awesome/css/font-awesome.min.css') ?>">
</head>
<body>

<?php if(!isset($no_menu)){ ?>

	<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">FERBIS</a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
      <ul class="nav navbar-nav">
        <li> <a href="<?= site_url('Control_controller/dash') ?>"><i class="fa fa-tasks" aria-hidden="true"></i> Pedidos</a></li>
        <li> <a href="<?= site_url('Control_controller/impresor') ?>"> <i class="fa fa-print" aria-hidden="true"></i> Impresor</a> </li>
        <li><a href="<?= site_url('Control_controller/busqueda') ?>"><i class="fa fa-search" aria-hidden="true"></i> Buscar</a></li>
        <li> <a href="<?= site_url('Control_controller/ligar') ?>"><i class="fa fa-link" aria-hidden="true"></i> Pago APP </a></li>
        <?php if($this->session->userdata('tipo')=='0'){?>
          <li> <a href="<?= site_url('Control_controller/inventario') ?>"> <i class="fa fa-cubes" aria-hidden="true"></i> Inventario</a></li>
        <?php } ?>
        <?php if($this->session->userdata('tipo')=='0'){?>
          <li><a href="<?= site_url('Control_controller/historico') ?>"> <i class="fa fa-clock-o" aria-hidden="true"></i> Historico</a></li>
          <li><a href="<?= site_url('Reportes_controller/mapa_pedidos') ?>"> <i class="fa fa-map" aria-hidden="true"></i> Mapa pedidos</a></li>
       <?php } ?>
        <li><a href="<?= site_url('Reportes_controller') ?>"><i class="fa fa-file-text-o" aria-hidden="true"></i> Reporte</a></li>
        <li><a href="<?= site_url('Encuesta_controller') ?>"><i class="fa fa-star-half-o" aria-hidden="true"></i> Encuesta</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="../Login_controller"><i class="fa fa-power-off" aria-hidden="true"></i> Salir</a></li>
      </ul>
    </div>
  </div>
</nav>
<?php } ?>


<input type="hidden" value="<?= base_url() ?>" id="base_url">
	<div class="contenedor_body">
