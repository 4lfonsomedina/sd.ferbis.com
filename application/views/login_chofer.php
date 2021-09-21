<!DOCTYPE html>
<html>
<head>
	<title>SD.FERBIS</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:700,600' rel='stylesheet' type='text/css'>
	<link rel="manifest" href="<?= base_url() ?>manifest.json">
</head>
<body>
	<style type="text/css">
		body{
		  font-family: 'Open Sans', sans-serif;
		  background:black;
		  margin: 0 auto 0 auto;  
		  width:100%; 
		  text-align:center;
		  margin: 20px 0px 20px 0px;   
		}

		p{
		  font-size:12px;
		  text-decoration: none;
		  color:#ffffff;
		}

		h1{
		  font-size:1.5em;
		  color:#525252;
		}

		.box{
		  background:white;
		  width:300px;
		  border-radius:6px;
		  margin: 0 auto 0 auto;
		  padding:0px 0px 70px 0px;
		  /*border: #2980b9 4px solid; */
		}

		.email{
		  background:#ecf0f1;
		  border: #ccc 1px solid;
		  border-bottom: #ccc 2px solid;
		  padding: 8px;
		  width:250px;
		  color:#AAAAAA;
		  margin-top:10px;
		  font-size:1em;
		  border-radius:4px;
		}

		.password{
		  border-radius:4px;
		  background:#ecf0f1;
		  border: #ccc 1px solid;
		  padding: 8px;
		  width:250px;
		  font-size:1em;
		}

		.btn{
		  width:265px;
		  background:#2ecc71;
		  padding-top:5px;
		  padding-bottom:5px;
		  color:white;
		  border-radius:4px;
		  border: #27ae60 1px solid;
		  
		  margin-top:20px;
		  margin-bottom:20px;
		  float:left;
		  font-weight:800;
		  font-size:0.8em;
		  margin-left:16px;
		}

		.btn:hover{
		  background:#2CC06B; 
		}

		#btn2{
		  float:left;
		  background:#3498db;
		  width:125px;  padding-top:5px;
		  padding-bottom:5px;
		  color:white;
		  border-radius:4px;
		  border: #2980b9 1px solid;
		  
		  margin-top:20px;
		  margin-bottom:20px;
		  margin-left:10px;
		  font-weight:800;
		  font-size:0.8em;
		}

		#btn2:hover{ 
		background:#3594D2; 
		}
		#mensaje_error{
			position:absolute;
			bottom:20px;
			left:20px;
			border-radius: 10px;
			padding: 20px;
			background-color: white;
		}
	</style>
	<?php if(isset($_GET['error'])){?>
	<div id="mensaje_error">Usuario y/o clave incorrectos</div>
	<?php } ?>
	<form method="post" action="<?= site_url('Login_controller/validar_chofer') ?>">
	<div class="box">
		<img src="<?= base_url('assets/img/logo.png') ?>">

	<input type="password" name="clave" class="email" placeholder="Clave" id="campo_chofer"/>
	  
	<button class="btn">Entrar</div></button> <!-- End Btn -->  
	</div> <!-- End Box -->
	  
	</form>
</body>
<footer>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js" type="text/javascript"></script>
	<script src="<?= site_url('../sw.js') ?>" type="text/javascript"></script>
	<script type="text/javascript">

		// This is the "Offline page" service worker
		if ('serviceWorker' in navigator) {
		   window.addEventListener('load', function() {
		     navigator.serviceWorker.register('/sw.js').then(function(registration) {
		       // Si es exitoso
		       console.log('SW registrado correctamente');
		     }, function(err) {
		       // Si falla
		       console.log('SW fallo', err);
		     });
		   });
		 }

 
		//Fade in dashboard box

		$(document).ready(function(){
			setTimeout(function() { 
                $('#campo_chofer').focus(); 
            }, 1000);
		    $('.box').hide().fadeIn(1000);
		    setTimeout(function() { 
                $('#mensaje_error').fadeOut(500); 
            }, 5000);

		    });
	</script>
</footer>
</html>



  
