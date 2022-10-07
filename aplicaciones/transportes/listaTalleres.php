<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
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
		<h1>Administración talleres</h1>
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

	<?php 
	$cv = new ControladorVehiculos();
	//$res = $cv->listarTalleres($conexion,$_SESSION['nombreLocalizacion'],'ABIERTOS');
	$res = $cv->abrirDatosTalleres($conexion, $_SESSION['nombreLocalizacion']);
	$contador = 0;
	while($fila = pg_fetch_assoc($res)){
		echo '<article
						id="'.$fila['id_taller'].'"
						class="item"
						data-rutaAplicacion="transportes"
						data-opcion="abrirTaller"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span><small>'.$fila['nombretaller'].'<br/></span>
					<span>'.(strlen($fila['direccion'])>35?(substr($fila['direccion'],0,35).'...'):(strlen($fila['direccion'])>0?$fila['direccion']:'Sin dirección')).'</span>
					<aside>'.$fila['telefono'].'<br/>'.$fila['contacto'].'</small></aside>
				</article>';
	
	}
	?>
	
</body>
<script>
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un taller para revisarlo.</div>');
	});

	$("#_eliminar").click(function(){
		if($("#cantidadItemsSeleccionados").text()>1){
				alert('Por favor seleccione un taller a la vez');
				return false;
			}
		});
	
</script>
</html>