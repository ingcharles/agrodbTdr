<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorVehiculos.php';
?>

<header>
		<h1>Mantenimiento</h1>
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
	$res = $cv->listarMantenimiento($conexion,$_SESSION['nombreLocalizacion'],'ABIERTOS','LAVADAS');
	$contador = 0;
	while($fila = pg_fetch_assoc($res)){
		echo '<article
						id="'.$fila['id_mantenimiento'].'"
						class="item"
						data-rutaAplicacion="transportes"
						data-opcion="abrirLavado"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem" 
						style="'.($fila['estado_mantenimiento']== 1?'background-color: #7D97EB;':'background-color: #BD588D;').'">
					<span class="ordinal">'.++$contador.'</span>
					<span><small><b>'.$fila['id_mantenimiento'].'</b><br/></span>
					<span>'.$fila['marca'].' - '.$fila['modelo'].' ('.$fila['placa'].')<br/></span>
					<span>'.(strlen($fila['motivo'])>16?(substr($fila['motivo'],0,16).'...'):(strlen($fila['motivo'])>0?$fila['motivo']:'Sin motivo')).'</span>
					<aside>'.date('j/n/Y (G:i)',strtotime($fila['fecha_solicitud'])).'<br/>'.($fila['estado_mantenimiento']== 1?'Por imprimir':'Por liquidar').'</small></aside>
				</article>';
	
	}
	?>
	

<script>
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un mantenimiento para revisarlo.</div>');
	});

	$("#_eliminar").click(function(){
		if($("#cantidadItemsSeleccionados").text()>1){
				alert('Por favor seleccione una orden de lavado a la vez');
				return false;
			}
		});
</script>