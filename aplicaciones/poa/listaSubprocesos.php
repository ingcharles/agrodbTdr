<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorPAPP.php';
	
	$fecha = getdate();
?>

<header>
		<h1>Administración de subprocesos</h1>
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
		$cd = new ControladorPAPP();
		$res = $cd->listarSubprocesos($conexion, $fecha['year']);
		$contador = 0;
		$cantidadCaracteres = 50;
		while($fila = pg_fetch_assoc($res)){

			$cadena = strpos($fila['descripcion'],' ',$cantidadCaracteres);
			$descripcion = (strlen($fila['descripcion'])>$cantidadCaracteres?(substr($fila['descripcion'], 0, (($cadena)?$cadena:$cantidadCaracteres)).'...'):(strlen($fila['descripcion'])>0?$fila['descripcion']:'Sin asunto'));
			
			echo '<article 
						id="'.$fila['id_subproceso'].'"
						class="item"
						data-rutaAplicacion="poa"
						data-opcion="abrirSubproceso" 
						ondragstart="drag(event)" 
						draggable="true" 
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span>'.$descripcion.'</span>
					<aside>'.date('j/n/Y',strtotime($fila['fecha_creacion'])).'</aside>
				</article>';
		
		}
	?>

<script>
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		//$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');
	});
	</script>
