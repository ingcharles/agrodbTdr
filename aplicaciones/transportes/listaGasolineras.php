<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorVehiculos.php';
?>

<header>
		<h1>Administración gasolineras</h1>
		<nav>
		<?php 

			$conexion = new Conexion();
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
	$res = $cv->listarGasolineras($conexion,$_SESSION['nombreLocalizacion'],'ABIERTOS');
	$contador = 0;
	while($fila = pg_fetch_assoc($res)){
		echo '<article
						id="'.$fila['id_gasolinera'].'"
						class="item"
						data-rutaAplicacion="transportes"
						data-opcion="abrirGasolinera"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span><small>'.$fila['nombre'].'<br/></span>
					<span>'.(strlen($fila['direccion'])>35?(substr($fila['direccion'],0,35).'...'):(strlen($fila['direccion'])>0?$fila['direccion']:'Sin dirección')).'</span>
					<aside>'.$fila['telefono'].'<br/>'.$fila['contacto'].'</small></aside>
				</article>';
		}
	?>
	

<script>
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una gasolinera para revisarlo.</div>');
	});

	$("#_eliminar").click(function(){
		if($("#cantidadItemsSeleccionados").text()>1){
				alert('Por favor seleccione una gasolinera a la vez');
				return false;
			}
		});
</script>