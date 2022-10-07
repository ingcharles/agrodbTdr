<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorVehiculos.php';
?>

<header>
		<h1>Siniestros</h1>
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
	$res = $cv->listarSiniestro($conexion,$_SESSION['nombreLocalizacion'],'ABIERTOS','CHOQUE');
	$contador = 0;
	while($fila = pg_fetch_assoc($res)){
		echo '<article
						id="'.$fila['id_siniestro'].'"
						class="item"
						data-rutaAplicacion="transportes"
						data-opcion="abrirSiniestro"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem" 
						style="'.($fila['estado']== 1?'background-color: #7D97EB;':($fila['estado']== 2?'background-color: #BD588D;':($fila['estado']== 3?'background-color: #ABCDA6;':'background-color: #A1A3A7;'))).'">
					<span class="ordinal">'.++$contador.'</span>
					<span><b>'.$fila['id_siniestro'].'</b><br/></span>
					<span>'.$fila['marca'].' - '.$fila['modelo'].' ('.$fila['placa'].')<br/></span>
					<span>'.(strlen($fila['tipo_siniestro'])>16?(substr($fila['tipo_siniestro'],0,16).'...'):(strlen($fila['tipo_siniestro'])>0?$fila['tipo_siniestro']:'Sin motivo')).'</span>
					<aside><small>'.date('j/n/Y (G:i)',strtotime($fila['fecha_solicitud'])).'<br/>'.
					($fila['estado']== 1?'Documentos':'Por finalizar').'</small></aside>
				</article>';
	
	}
	?>
	

<script>
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un siniestro para revisarlo.</div>');
	});

	$("#_habilitarVehiculo").click(function(){
		if($("#cantidadItemsSeleccionados").text()>1){
			alert('Por favor seleccione un vehículo a la vez');
			return false;
		}
	});
</script>