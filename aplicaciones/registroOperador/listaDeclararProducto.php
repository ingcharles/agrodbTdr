<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorAplicaciones.php';

$identificadorOperador = $_SESSION['usuario'];

?>


<div id="contendorArticulos">

	<header>
		<h1>Solicitudes</h1>
		<nav>
			<?php 

			$conexion = new Conexion();
			$ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $identificadorOperador);
			while($fila = pg_fetch_assoc($res)){
				echo '<a href="#"
						id="' . $fila['estilo'] . '"
						data-destino="detalleItem"
						data-opcion="' . $fila['pagina'] . '"
						data-rutaAplicacion="' . $fila['ruta'] . '">'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
			}
		?>
		</nav>
	</header>
	
	<?php 
	$conexion = new Conexion();
	$cr = new ControladorRegistroOperador();
	
	$res = $cr->listarOperacionesOperador($conexion, $identificadorOperador, " in ('cargarProducto','registrado','subsanacionProducto')", 2000, 0, 'cargarProducto');
	
	while($fila = pg_fetch_assoc($res)){
		
		switch ($fila['estado']){
		
			case 'registrado':
			    $condicion = $cr->obtenerCondicionTipoOperacion($conexion, $fila['id_tipo_operacion'], 'cargarProducto');
				$estado = 'aprobada';
			break;		
			case 'subsanacionProducto':
				$estado = 'Subsanación';
			break;
			case 'cargarProducto':
				$estado = 'Cargar productos';
			break;
		
			default:
				$estado = 'ninguna';
		}
		
		if($fila['estado'] == 'registrado'){
		    if(pg_num_rows($condicion)==0){
		        continue;
		    }
		}
		
		$nombreArea = $cr->buscarNombreAreaPorSitioPorTipoOperacion($conexion, $fila['id_tipo_operacion'], $identificadorOperador, $fila['id_sitio'], $fila['id_operacion']);
		
		$codigoSitio = $fila['id_sitio'];
		$nombreSitio = $fila['nombre_lugar'];
		$contenido = '<article
			id="'.$fila['id_operacion'].'"
			class="item"
			data-rutaAplicacion="registroOperador"
			data-opcion="nuevoDeclararProducto"
			ondragstart="drag(event)"
			draggable="true"
			data-destino="detalleItem">
			<span><small> # '.$fila['id_tipo_operacion'].'-'.$fila['id_sitio'].' </small></span>
						<span><small>'.(strlen($fila['provincia'])>14?(substr($cr->reemplazarCaracteres($fila['provincia']),0,14).'...'):(strlen($fila['provincia'])>0?$fila['provincia']:'')).'</small></span><br />
						<span><small>'.(strlen($fila['nombre_tipo_operacion'])>30?(substr($cr->reemplazarCaracteres($fila['nombre_tipo_operacion']),0,30).'...'):(strlen($fila['nombre_tipo_operacion'])>0?$fila['nombre_tipo_operacion']:'')).'<b> en </b> '.
							(strlen($nombreArea)>42?(substr($cr->reemplazarCaracteres($nombreArea),0,42).'...'):(strlen($nombreArea)>0?$nombreArea:'')).'</small></span>
					<aside class= "estadoOperador"><small> Estado: '.$estado.'<span</span></small></aside>
						</article>';
		?>
		<script type="text/javascript">
						var contenido = <?php echo json_encode($contenido);?>;
						var subcategoria = <?php echo json_encode($codigoSitio);?>;
						var nombreSitio = <?php echo json_encode($nombreSitio);?>;
						if($("#"+subcategoria).length == 0){
							$("#contendorArticulos").append("<div id= "+subcategoria+"><h2>"+nombreSitio+"</h2><div class='subElementos'></div></div>");
						}
						$("#"+subcategoria+" div.subElementos").append(contenido);
						
					</script>
	
		<?php
		}
	
	?>


</div>

<script type="text/javascript">	

	$(document).ready(function(event){
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
	});

</script>	