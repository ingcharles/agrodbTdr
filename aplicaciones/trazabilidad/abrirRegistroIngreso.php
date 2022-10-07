<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorTrazabilidad.php';


$conexion = new Conexion();
$ct = new ControladorTrazabilidad();

$identificadorProveedor = $_POST['id'];
$identificadorOperador = $_SESSION['usuario'];


$sitiosIngresoOperdaor = $ct->listarSitiosRegistroIngreso($conexion, $identificadorProveedor,$identificadorOperador) ;
$productoIngresoRegistro = $ct->listarProductoRegistroIngreso($conexion, $identificadorProveedor,$identificadorOperador);
$areasIngresoRegistro = $ct->listarAreasRegistroIngreso($conexion, $identificadorProveedor, $identificadorOperador);
$fechasIngresoRegistro = $ct->listarFechasRegistroIngreso($conexion, $identificadorProveedor, $identificadorOperador);



while($fila = pg_fetch_assoc($areasIngresoRegistro)){
	$areas[]= array(idArea=>$fila['id_area'], nombreArea=>$fila['nombre_area'], idSitio=>$fila['id_sitio']);
}

while($fila = pg_fetch_assoc($fechasIngresoRegistro)){
	$fechas[]= array(fechaIngreso=>$fila['fecha_ingreso'], idArea=>$fila['id_area_proveedor']);
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>

<body>
	<header>
		<h1>Datos del Registro</h1>
	</header>

	<div id="estado"></div>
	
	<form id="datosRegistro" data-rutaAplicacion="trazabilidad" data-opcion="listaDetalleIngreso" data-destino="tabla">
	
		<input id="identificadorOperador" name="identificadorOperador" type="hidden" value="<?php echo $identificadorOperador;?>"/>
		<input id="idProducto" name="idProducto" type="hidden" value="<?php  echo pg_fetch_result($productoIngresoRegistro, 0, 'id_producto');?>"/>
		
		<table class="filtro">
			<tr>
				<td>
					
					<fieldset>
						<legend>Datos generales de ingreso</legend>

						<div data-linea="1">

							<label>Código Proveedor</label> 
								<input type="text" id="codproveedor" name="codproveedor" value="<?php echo $identificadorProveedor;?>" readonly="readonly" />
						</div>

						<div data-linea="1">
							<label>Producto</label> 
								<input type="text" name="producto" value="<?php echo pg_fetch_result($productoIngresoRegistro, 0, 'nombre_comun');?>" disabled="disabled" />
						</div>

						<div data-linea="2">
							<label>Sitio</label> 
								<select id="sitio" name="sitio">
									<option value="" selected="selected">Sitio....</option>
									<?php 
										while ($fila = pg_fetch_assoc($sitiosIngresoOperdaor)){
											echo '<option value="' . $fila['id_sitio'] . '" >'. $fila['nombre_lugar'] .'</option>';

										}
									?>
								</select>

						</div>
						
						<div data-linea="2">
							<label>Area</label> 
								<select id="area" name="area" disabled="disabled"></select>
						</div>
						
						<div data-linea="3">
							<label>Fecha Ingreso</label> <select id="fecha" name="fecha"></select>
							<button type="submit">Buscar</button>
						</div>
					</fieldset>
				</td>
				
			</tr>
		</table>
	</form>
	<div id="tabla"></div>
</body>
<script type="text/javascript">

	var array_areas= <?php echo json_encode($areas); ?>;
	var array_fechas= <?php echo json_encode($fechas); ?>;

									
		$(document).ready(function(){
			distribuirLineas();
		});

		
		
		$("#sitio").change(function(){
	 		sarea ='0';
			sarea = '<option value="">Area...</option>';
			for(var i=0;i<array_areas.length;i++){
			    if ($("#sitio").val()==array_areas[i]['idSitio']){
			   	    	sarea += '<option value="'+array_areas [i]['idArea']+'">'+array_areas[i]['nombreArea']+'</option>';
				}
		   	}
		    $('#area').html(sarea);
		    $('#area').removeAttr("disabled");
		 });


		$("#area").change(function(){
	 		sfecha ='0';
			sfecha = '<option value="">Fecha...</option>';
			for(var i=0;i<array_fechas.length;i++){
			    if ($("#area").val()==array_fechas[i]['idArea']){
			   	    	sfecha += '<option value="'+array_fechas[i]['fechaIngreso']+'">'+array_fechas[i]['fechaIngreso']+'</option>';
				}
		   	}
		    $('#fecha').html(sfecha);
		    $('#fecha').removeAttr("disabled");
		 });
					

	$("#datosRegistro").submit(function(event){

		event.preventDefault();
		
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#sitio").val())){
			error = true;
			$("#sitio").addClass("alertaCombo");
		}

		if(!$.trim($("#area").val())){
			error = true;
			$("#area").addClass("alertaCombo");
		}

		if(!$.trim($("#fecha").val())){
			error = true;
			$("#fecha").addClass("alertaCombo");
		}

		if (!error){
			abrir($(this),event,false);
			$("#estado").html("").removeClass('alerta');
		}else{
			$("#estado").html("Por favor ingrese los campos obligatorios.").addClass('alerta');
		}
	});

</script>
</html>
