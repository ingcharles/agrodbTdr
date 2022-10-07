<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorVehiculos.php';
?>

<header>
		<h1>Combustible</h1>
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
	$res = $cv->listarCombustible($conexion,$_SESSION['nombreLocalizacion'],'ABIERTOS');
	$contador = 0;
	while($fila = pg_fetch_assoc($res)){
		echo '<article
						id="'.$fila['id_combustible'].'"
						class="item"
						data-rutaAplicacion="transportes"
						data-opcion="abrirCombustible"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem"
						style="'.($fila['estado_combustible']== 1?'background-color: #7D97EB;':'background-color: #BD588D;').'">
					<span class="ordinal">'.++$contador.'</span>
					<span><small><b>'.$fila['id_combustible'].'</b><br/></span>
					<!--span>'.$fila['marca'].' - '.$fila['modelo'].' ('.$fila['placa'].')<br/></span-->
					<span>'.$fila['placa'].'<br/></span>
					<span>'.$fila['nombre'].'<br/></span>
					<span>'.date('j/n/Y',strtotime($fila['fecha_despacho'])).'<br/></span>
					<aside>Km: '.$fila['kilometraje'].'<br/>'.($fila['estado_combustible']== 1?'Por imprimir':'Por liquidar').'</small></aside>
				</article>';
	
	}
	?>
	

<script>
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una orden de combustible para revisarlo.</div>');
	});
</script>
