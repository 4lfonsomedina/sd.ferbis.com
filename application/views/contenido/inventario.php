<style type="text/css">
	.editar_producto{
		color:blue;
		cursor: pointer;
	}
	input{
		width: 100%;
		padding: 5px !important;
	}
	select{
		width: 100%;
		padding: 7px !important;
	}
</style>
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading" style="text-align: right;">
			<button type="button" class="btn btn-warning btn_temporada"><i class="fa fa-calendar" aria-hidden="true"></i> 	Temporada</button>
			<button type="button" class="btn btn-success alta_producto"><i class="fa fa-plus" aria-hidden="true"></i> Producto </button>
			<button type="button" class="btn btn-primary btn_act"><i class="fa fa-refresh" aria-hidden="true"></i> 	Precios</button>
		</div>
		<div class="panel-body">
			<center><canvas id= "canvas_img" height="300" width="300" hidden></canvas></center>
			<form id="inv_form">
				<input type="hidden" id="inv_producto" name="producto">
				<input type="hidden" id="imagen_base64" name="imagen_base64">
			</form>
			<div class="cargando_tabla">
				<h1><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i> Cargando Información...</h1>
			</div>
			<table class="datatables" hidden>
				<thead>
				<tr>
					<th>#</th>
					<th>Foto</th>
					<th>Estatus</th>
					<th>Descripción</th>
					<th>Departamento</th>
					<th>SubDep</th>
					<th>Unidad</th>
					<th style="text-align: right">Precio</th>
				</tr>
				</thead>
				<tbody>
				<? $contador=1; foreach($productos as $p){?>
				<tr>
					<td><?= $contador++ ?></td>
					<td><input type="file" class="p_imagen" accept="image/png, image/jpeg" id_producto="<?= $p->producto ?>">
						<img id="img_producto_<?= $p->producto ?>" class="file_camera" src="<? 
				
							//if(existe_img_producto($p->producto)){ echo base_url('assets/img/productos/').$p->producto.'.png?'.rand(0,1000); }
							if(existe_img_producto($p->producto)){ echo base_url('assets/img/').'check.png?'; }
							else{echo base_url('assets/img/productos/0.png'); }

					?>" height="50" width="50"></td>
					<td id="producto_activo_<?= $p->producto ?>"><?php if($p->activo=='1'){ echo 'ACT'; }else{ echo "INA";} ?></td>
					<td class="editar_producto" id="producto_desc_<?= $p->producto ?>" producto="<?= $p->producto ?>" ><?= $p->descripcion ?></td>
					<td id="producto_dep_<?= $p->producto ?>"><?= $p->nombre_departamento ?></td>
					<td id="producto_subdep_<?= $p->producto ?>"><?= $p->nombre_subdepartamento ?></td>
					<td ><?= $p->unidad ?></td>
					<td align="right"><?= number_format($p->precio,2) ?></td>
				</tr>
				<? } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Modal Alta  -->
<div id="modal_alta_producto" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="text-align: center">ALTA DE PRODUCTO</h4>
      </div>
      <div class="modal-body" style="overflow-y: auto; background-color: #eaeaea; padding: 30px">
      	<form id="form_alta_prod">
      		<div class="row"style="padding-bottom: 20px;">
      		<div class="col-md-6">
	      		<input type="text" class="form-control modal_id_prod alta_clave" name="producto" placeholder="Clave">
	      	</div>
	      	<div class="col-md-6">
	      		 <input type="text" class="form-control modal_descrip_prod alta_descrip" name="descripcion" placeholder="Descripción">
	      	</div>
	      	</div>
	      	<div class="row"style="padding-bottom: 20px;">
      		<div class="col-md-6">
	      		<input type="hidden" class="form-control modal_id_prod alta_unidad" name="unidad" placeholder="Unidad">
	      	</div>
	      	<div class="col-md-6">
	      		 <input type="hidden" class="form-control modal_descrip_prod alta_precio" name="precio" placeholder="Precio">
	      	</div>
	      	</div>
	      	<div class="row">
	      	<div class="col-md-6">
	      		Departamento
	      		<select class="form-control modal_dep_prod select_dep" name="departamento">
	      			<? foreach($departamentos as $d){ ?>
	      			<option value="<?= $d->id_departamento?>"><?= $d->nombre_departamento?></option>
	      			<? } ?>
	      		</select>
	        </div>
	        <div class="col-md-6">
	        	subdepartamento
	      		<select class="form-control modal_subdep_prod select_subdep" name="subdepartamento">
	      		</select>
	        </div>
	        <div class="col-md-6">
	        	2do subdepartamento
	      		<select class="form-control modal_subsubdep_prod select_subsubdep modal_subsubdep" name="subsubdepartamento">
	      		</select>
	        </div>
	        <div class="col-md-6">
	        	3er subdepartamento
	      		<select class="form-control modal_subsubdep_prod select_subsubdep modal_subsubsubdep" name="subsubsubdepartamento">
	      		</select>
	        </div>
	        <div class="col-md-12">
	      		 <input type="text" class="form-control modal_meta_prod" name="metadatos" placeholder="Metadatos">
	      	</div>
	      	<div class="col-md-6">
	        	Orden (0-999)
	      		<input type="number" class="form-control modal_orden_prod" name="prod_orden" placeholder="Orden ( 0 - 9999)">
	        </div>
	        <div class="col-md-6">
	        	Peso Promedio
	      		<input type="number" class="form-control modal_peso_prod" name="peso_promedio" value='0.3'>
	        </div>

	    	</div>
	    </form>
	    <br><br><br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-success btn_alta">Guardar</button>
      </div>
    </div>

  </div>
</div>


<!-- Modal edicion -->
<div id="modal_editar_producto" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="text-align: center">Editar Producto</h4>
      </div>
      <div class="modal-body" style="overflow-y: auto;background-color: white; padding: 30px">
      	<img width="70" height="70" src="" id="imagen_producto">
      	<form id="form_editar_prod">
	      	<input type="hidden" class="form-control modal_id_prod" name="producto">
	      	<div class="col-xs-8"></div><div class="col-xs-4"><input type="text" class="form-control modal_id_prod"></div>
	      	<div class="col-md-12">
	      		Descripción
	      		 <input type="text" class="form-control modal_descrip_prod" name="descripcion">
	      	</div>
	      	<div class="col-md-6">
	      		Departamento
	      		<select class="form-control modal_dep_prod select_dep" name="departamento">
	      			<? foreach($departamentos as $d){ ?>
	      			<option value="<?= $d->id_departamento?>"><?= $d->nombre_departamento?></option>
	      			<? } ?>
	      		</select>
	        </div>
	        <div class="col-md-6">
	        	subdepartamento
	      		<select class="form-control modal_subdep_prod select_subdep" name="subdepartamento">
	      		</select>
	        </div>
	        <div class="col-md-6">
	        	2do subdepartamento
	      		<select class="form-control modal_subsubdep_prod select_subsubdep modal_subsubdep" name="subsubdepartamento">
	      		</select>
	        </div>
	        <div class="col-md-6">
	        	3er subdepartamento
	      		<select class="form-control modal_subsubdep_prod select_subsubdep modal_subsubsubdep" name="subsubsubdepartamento">
	      		</select>
	        </div>
	        <div class="col-md-12">
	      		 <input type="text" class="form-control modal_meta_prod" name="metadatos" placeholder="Metadatos">
	      	</div>
	        <div class="col-md-6">
	        	Orden (0-999)
	      		<input type="number" class="form-control modal_orden_prod" name="prod_orden" placeholder="Orden ( 0 - 9999)">
	        </div>
	        <div class="col-md-6">
	        	Estatus
	        	<select class="form-control modal_activo_prod" name="activo">
	        		<option value="1">Activo</option>
	        		<option value="0">Inactivo</option>
	        	</select>
	        </div>
	        <div class="col-md-6">
	        	Peso Promedio
	      		<input type="number" class="form-control modal_peso_prod" name="peso_promedio">
	        </div>
	    </form>
	    <br><br><br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-danger eliminar_prod" producto="">Eliminar</button>
        <button type="button" class="btn btn-success btn_editar">Guardar</button>
      </div>
    </div>

  </div>
</div>



<!-- Modal temporada -->
<div id="modal_temporada" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="text-align: center">Editar Temporada</h4>
      </div>
      <div class="modal-body">
      	<div class="row">
	      	<div class="col-xs-8">
	      		<select class="form-control" id="select_temporada">
	      			<? foreach($productos as $p)if($p->activo=='1'){?>
	      				<option value="<?= $p->producto ?>"><?= $p->producto ?> - <?= $p->descripcion ?></option>
	      			<?}?>
	      		</select>
	      	</div>
	      	<div class="col-xs-4">
	      		<a class="btn btn-primary agregar_temporada">Agregar</a>
	      	</div>
      	
	      	<div class="col-xs-12" style="padding-top: 20px;">
		      	<table class="table tabla_temporada">
		      		
		      	</table>
		    </div>
	    </div>
      </div>
  </div>
</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		
		actualizar_temporada();

		$(".btn_temporada").click(function(){
			$("#modal_temporada").modal("show");
		})

		$(".agregar_temporada").click(function(){
			var producto = $("#select_temporada").val();
			$.post('../Control_controller/alta_temporada', {producto:producto},function(r){
				actualizar_temporada();
			})
		})
		$(document).on("click",".borrar_temporada",function(){
			var producto = $(this).attr("producto");
			$.post('../Control_controller/borrar_temporada', {producto:producto},function(r){
				actualizar_temporada();
			})
		})
		function actualizar_temporada(){
			$.post('../Control_controller/get_temporada', function(r){
				$(".tabla_temporada").html(r);
			})
		}
		

		$(".alta_producto").click(function(){
			$(".alta_clave").val("");
			$(".alta_descrip").val("");
			$.post('../Control_controller/get_subdep', {dep:$(".select_dep").val()},function(r){
				var options ="<option value='0'>No Aplica</option>";
				$.each(JSON.parse(r), function(i, subdep) {
					options+="<option value='"+subdep.id_subdepartamento+"'>"+subdep.nombre_subdepartamento+"</option>";
				});
				$(".select_subdep").html(options);
			});
			$.post('../Control_controller/get_subdep_all',function(r){
				var options ="<option value='0'>No Aplica</option>";
				$.each(JSON.parse(r), function(i, subdep) {
					options+="<option value='"+subdep.id_subdepartamento+"'>"+subdep.nombre_departamento+" - "+subdep.nombre_subdepartamento+"</option>";
				});
				$(".select_subsubdep").html(options);
			});
			$("#modal_alta_producto").modal("show");
			$(".alta_clave").focus();
		})
		$(".eliminar_prod").click(function(){
			if(confirm("estas seguro de que deseas eliminar este producto?")){
				$.post('../Api_controller/eliminar_producto', {producto:$(this).attr("producto")},function(r){
					location.reload();
				})
			}
		})
		$(".alta_clave").focusout(function() {
			var t_producto=$(this).val();
			if(t_producto==""){return;}

			$.post('../Api_controller/get_producto',{producto:t_producto}, function(r){
				var validacion = 0;
				var producto = JSON.parse(r);
				if(producto!==null){
					alert("Esta clave ya existe en el catalogo actual");
					$(".alta_clave").val("");
					$(".alta_clave").focus();
					return;
				}else{
					//$.post('http://192.168.1.10:85/ferbis-interno/index.php/API/api_Controller/get_producto',{producto:t_producto}, function(r2){
					$.post('../Api_controller/get_producto_avattia',{producto:t_producto}, function(r2){
						var producto2 = JSON.parse(r2);
						if(producto2.length==0){
							alert("Esta clave no existe en el catalogo de avattia");
							$(".alta_clave").val("");
							$(".alta_clave").focus();
							return;
						}else{
							$(".alta_clave").val(producto2[0].producto);
							$(".alta_descrip").val(producto2[0].desc1);
							$(".alta_unidad").val(producto2[0].um);
							$(".alta_precio").val(producto2[0].precio1);
						}
					})
				}
			});
		});
		$(".btn_alta").click(function(){
			if($(".alta_clave").val()==""||$(".alta_descrip").val()==""){
				alert("No se permiten campos vacios");
				return;
			}
			$.post('../Api_controller/alta_producto',$("#form_alta_prod").serialize(), function(r){
				location.reload();
			});
		})

		$(".editar_producto").click(function(){
			$.post('../Api_controller/get_producto', {producto: $(this).attr('producto')}, function(r1) {
				r1 = jQuery.parseJSON(r1);
				$("#imagen_producto").attr("src",r1.puntuacion);
				$(".modal_id_prod").val(r1.producto);
				$(".modal_descrip_prod").val(r1.descripcion);
				$(".modal_orden_prod").val(r1.prod_orden);
				$(".modal_activo_prod").val(r1.activo);
				$(".modal_dep_prod").val(r1.departamento);
				$(".eliminar_prod").attr('producto',r1.producto);
				$(".modal_meta_prod").val(r1.metadatos);
				$(".modal_peso_prod").val(r1.peso_promedio);
				$("#modal_editar_producto").modal("show");
				var subdepartamento = r1.subdepartamento;
				var subsubdepartamento = r1.subsubdepartamento;
				var subsubsubdepartamento = r1.subsubsubdepartamento;
				$.post('../Control_controller/get_subdep', {dep:r1.departamento},function(r){
					var options ="<option value='0'>No Aplica</option>";
					$.each(JSON.parse(r), function(i, subdep){
						options+="<option value='"+subdep.id_subdepartamento+"'>"+subdep.nombre_subdepartamento+"</option>";
					});
					$(".select_subdep").html(options);
					$(".modal_subdep_prod").val(subdepartamento);
				});
				$.post('../Control_controller/get_subdep_all',function(r){
					var options ="<option value='0'>No Aplica</option>";
					$.each(JSON.parse(r), function(i, subdep) {
						options+="<option value='"+subdep.id_subdepartamento+"'>"+subdep.nombre_departamento+" - "+subdep.nombre_subdepartamento+"</option>";
					});
					$(".select_subsubdep").html(options);
					$(".modal_subsubdep").val(subsubdepartamento);
					$(".modal_subsubsubdep").val(subsubsubdepartamento);
				});


				
			});
			
		})
		
		
		$(".btn_editar").click(function(){
			$.post('../Api_controller/editar_producto', $("#form_editar_prod").serialize(), function(r){
				//location.reload();
				$("#modal_editar_producto").modal("hide");
				$.each(JSON.parse(r), function(i, prod) {
					var activo='ACT';if(prod.activo!='1'){ activo='INA';}
					$("#producto_desc_"+prod.producto).html(prod.descripcion);
					$("#producto_activo_"+prod.producto).html(activo);
					$("#producto_dep_"+prod.producto).html(prod.nombre_departamento);
					$("#producto_subdep_"+prod.producto).html(prod.nombre_subdepartamento);
				})
			});
		})
		

		$(".btn_act").click(function(){
			$.post('../Control_controller/actualizar_precios', function(r){
				location.reload();
			});
		})
		$(".select_dep").change(function(event) {
			$.post('../Control_controller/get_subdep', {dep:$(this).val()},function(r){
				var options ="<option value='0'>No Aplica</option>";
				$.each(JSON.parse(r), function(i, subdep) {
					options+="<option value='"+subdep.id_subdepartamento+"'>"+subdep.nombre_subdepartamento+"</option>";
				});
				$(".select_subdep").html(options);
			});
			$.post('../Control_controller/get_subdep_all',function(r){
				var options ="<option value='0'>No Aplica</option>";
				$.each(JSON.parse(r), function(i, subdep) {
					options+="<option value='"+subdep.id_subdepartamento+"'>"+subdep.nombre_departamento+" - "+subdep.nombre_subdepartamento+"</option>";
				});
				$(".select_subsubdep").html(options);
			});
		});
	});
</script>