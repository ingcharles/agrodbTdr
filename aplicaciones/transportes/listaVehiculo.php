<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorVehiculos.php';
	
	$conexion = new Conexion();

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<header>
		<h1>Vehículos</h1>
		<nav>

		<?php 
			$ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
			//data-rutaAplicacion="' . $fila['ruta'] .'"
			while($fila = pg_fetch_assoc($res)){
				echo '<a href="#"
						id="' . $fila['estilo'] . '"
						data-destino="detalleItem"
						data-opcion="' . $fila['pagina'] . '"
						data-rutaAplicacion="' . $fila['ruta'] . '"
						>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
				
			}
		?>
		</nav>
	</header>
	<table id="LOCAL">
	<thead>
		<tr>
			<th>LOCAL</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Marca</th>
			<th>Modelo</th>
			<th>Placa</th>
			<th>Oficina</th>
			<th>Responsable</th>
			<th>Estado</th>
		</tr>
	</thead>
	
	</table>
  
  	<table id="COMISION">
	<thead>
		<tr>
			<th>COMISIÓN</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Marca</th>
			<th>Modelo</th>
			<th>Placa</th>
			<th>Oficina</th>
			<th>Responsable</th>
			<th>Estado</th>
		</tr>
	</thead>
	
	</table>


	<?php 
	
		$cv = new ControladorVehiculos();
		$res = $cv->datosVehiculos($conexion, $_SESSION['nombreLocalizacion']);
		$contador = 0;
		while($vehiculo = pg_fetch_assoc($res)){

			$categoria = $vehiculo['tipo'];

			$contenido = '<tr 
						id="'.$vehiculo['placa'].'"
						class="item"
						data-rutaAplicacion="transportes"
						data-opcion="abrirVehiculo" 
						ondragstart="drag(event)" 
						draggable="true" 
						data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td style="white-space:nowrap;"><b>'.$vehiculo['marca'].'</b></td>
					<td>'.$vehiculo['modelo'].'</td>
					<td>'.$vehiculo['placa'].'</td>
					<td>'.$vehiculo['nombre'].'</td>
					<td>'.$vehiculo['nombres_completos'].'</td>
					<td><span class="n'.($vehiculo['estado']==1?'Vehiculo':($vehiculo['estado']==2?'Mantenimiento':($vehiculo['estado']==3?'Movilizacion':($vehiculo['estado']==4?'Siniestro':'Rechazado')))).'"></span></td>			
							
				</tr>';
		?>
			<script type="text/javascript">
					var contenido = <?php echo json_encode($contenido);?>;
					var categoria = <?php echo json_encode($categoria);?>;
					$("#"+categoria+"").append(contenido);
			</script>
			<?php 		
			
		}
		
/*		$cc = new ControladorCatalogos();
		for ($i=1; $i<=200; $i++){
		
			
			$res = $cc->obtenerNombreLocalizacion($conexion, $i);
			$nombreLocalizacion = pg_fetch_assoc($res);
		
			$resultado = $cv->actualizarNombreLocalizacionCombustible($conexion, $i, $nombreLocalizacion['nombre']);
			$resultado = $cv->actualizarNombreLocalizacionMovilizaciones($conexion, $i, $nombreLocalizacion['nombre']);
			$resultado = $cv->actualizarNombreLocalizacionTalleres($conexion, $i, $nombreLocalizacion['nombre']);
			$resultado = $cv->actualizarNombreLocalizacionGasolineras($conexion, $i, $nombreLocalizacion['nombre']);
			$resultado = $cv->actualizarNombreLocalizacionRutas($conexion, $i, $nombreLocalizacion['nombre']);
			$resultado = $cv->actualizarNombreLocalizacionMantenimientos($conexion, $i, $nombreLocalizacion['nombre']);
			//$resultado = $cv->actualizarNombreLocalizacionSiniestro($conexion, $i, $nombreLocalizacion['nombre']);
			$resultado = $cv->actualizarNombreLocalizacionVehiculos($conexion, $i, $nombreLocalizacion['nombre']);
			$resultado = $cv->actualizarNombreLocalizacionContratos($conexion, $i, $nombreLocalizacion['nombre']);
		}*/
		
		
	?>
	
</body>
<script>
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#COMISION tbody tr").length == 0 ? $("#COMISION").remove():"";
	});

	$("#_eliminar").click(function(){
		if($("#cantidadItemsSeleccionados").text()>1){
				alert('Por favor seleccione un vehículo a la vez');
				return false;
			}
		
		});
	
	

	
</script>
</html>