<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRegistroOperador.php';
	require_once '../../clases/ControladorAplicaciones.php';
	
	$conexion = new Conexion();
	$ca = new ControladorAplicaciones();
	$cro = new ControladorRegistroOperador();	
	
	$qSitiosOperador = $cro->listarOperacionesOperadorPorArea($conexion, $_SESSION['usuario'], " in ('registrado')", "in ('ACO-AI','MDT-AI')");
?>
	
	<div id="contendorArticulos">
	
<header>
		<h1>Aumento de capacidad instalada</h1>
		

		<nav>
		<?php 
		
		$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
		
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

	<div id="SA">
		<h2>Sanidad Animal</h2>
		<div class="elementos"></div>
	</div>	
	
	<div id="SV">
		<h2>Sanidad Vegetal</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="LT">
		<h2>Laboratorios</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="AI">
		<h2>Inocuidad de los alimentos</h2>
		<div class="elementos"></div>
	</div>
		
<?php 
		while($fila = pg_fetch_assoc($qSitiosOperador)){				
			
			$qNombreArea = $cro->buscarNombreAreaTematicaPorSitioPorTipoOperacion($conexion, $fila['id_tipo_operacion'], $fila['id_sitio'], $fila['id_operacion'], "in ('AI')");
			$nombreArea = pg_fetch_assoc($qNombreArea);
			
			switch ($nombreArea['tipo_operacion_area']){
			
				case 'ACOAI':
					$opcionPagina = 'abrirAumentoCapacidadCentroAcopio';
					$idEnvio = $fila['id_sitio'].'@'.$fila['id_operacion'];
					$categoria = 'AI';
					$estado = 'registrado';
				break;
				
				case 'MDTAI':
					$opcionPagina = 'abrirAumentoCapacidadVehiculo';
					$idEnvio = $fila['id_sitio'].'@'.$fila['id_operacion'];
					$categoria = 'AI';
					$estado = 'registrado';
				break;
			
			}
			
			$codigoSitio = $fila['id_sitio'].'-'.$categoria;
			$nombreSitio = $fila['nombre_lugar'];
			
			$contenido = '<article
						id="'.$idEnvio.'"
						class="item"
						data-rutaAplicacion="aumentoCapacidad"
						data-opcion="'.$opcionPagina.'"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem">
						<span><small> # '.$fila['id_tipo_operacion'].'-'.$fila['id_sitio'].' </small></span>
						<span><small>'.(strlen($fila['provincia'])>14?(substr($cro->reemplazarCaracteres($fila['provincia']),0,14).'...'):(strlen($fila['provincia'])>0?$fila['provincia']:'')).'</small></span><br />
						<span><small>'.(strlen($fila['nombre_tipo_operacion'])>30?(substr($cro->reemplazarCaracteres($fila['nombre_tipo_operacion']),0,30).'...'):(strlen($fila['nombre_tipo_operacion'])>0?$fila['nombre_tipo_operacion']:'')).'<b> en </b> '.
						(strlen($nombreArea['nombre_area'])>42?(substr($cro->reemplazarCaracteres($nombreArea['nombre_area']),0,42).'...'):(strlen($nombreArea['nombre_area'])>0?$nombreArea['nombre_area']:'')).'</small></span>
						<aside class= "estadoOperador"><small> Estado: '.$estado.'<span><div class= "'.$clase.'"></div></span></small></aside>
						</article>';
?>

			<script type="text/javascript">
				var contenido = <?php echo json_encode($contenido);?>;
				var subcategoria = <?php echo json_encode($codigoSitio);?>;	
				var nombreSitio = <?php echo json_encode($nombreSitio);?>;	
				var categoria = <?php echo json_encode($categoria);?>;	
									
				if($("#"+subcategoria).length == 0){
					$("#"+categoria+" div.elementos").append("<div id= "+subcategoria+"><h3>"+nombreSitio+"</h3><div class='subElementos'></div></div>");
				}
				$("#"+subcategoria+" div.subElementos").append(contenido);								
			</script>
<?php 
		}
?>

</div>
<script>
    $(document).ready(function () {
        $("#listadoItems").addClass("comunes");
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
        
    	$("#SA div> article").length == 0 ? $("#SA").remove():"";
    	$("#SV div> article").length == 0 ? $("#SV").remove():"";
    	$("#LT div> article").length == 0 ? $("#LT").remove():"";
    	$("#AI div> article").length == 0 ? $("#AI").remove():"";
    });

</script>