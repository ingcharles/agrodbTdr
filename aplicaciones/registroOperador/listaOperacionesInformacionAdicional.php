<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorAplicaciones.php';

	
$conexion = new Conexion();	
$cro = new ControladorRegistroOperador();

$identificadorOperador= $_SESSION['usuario'];

?>			   

<div id="contendorArticulos">
	<header>
		<h1>Declarar información adicional</h1>
	</header>

	
<div id="cargarIA">
	<h2>Operaciones por cargar información adicional</h2>
	<div class="elementos"></div>
</div>

<div id="declararICentroAcopio">
	<h2>Operaciones por declarar información de centro de acopio</h2>
	<div class="elementos"></div>
</div>

<div id="declararDVehiculo">
	<h2>Operaciones por declarar información de vehículo</h2>
	<div class="elementos"></div>
</div>

<div id="declararIMercanciaPecuaria">
	<h2>Operaciones por declarar información de mercancías pecuarias</h2>
	<div class="elementos"></div>
</div>

<div id="declararIColmenar">
	<h2>Operaciones por declarar información de colmenares</h2>
	<div class="elementos"></div>
</div>

<?php 

	$res = $cro->listarOperacionesOperador($conexion, $identificadorOperador, " in ('declararICentroAcopio', 'declararDVehiculo','declararIMercanciaPecuaria', 'declararIColmenar')", 400, 0);
	$contador = 0;

	while($fila = pg_fetch_assoc($res)){
		
		switch ($fila['estado']){
			case 'declararICentroAcopio':
				$opcionPagina = 'declararInformacionCentroAcopio';
				$idEnvio = $fila['id_sitio'].'@'.$fila['id_operacion'];
				$categoria = 'declararICentroAcopio';
				$estado = 'Inf. Adicional';
			break;
			case 'declararDVehiculo':
    			if($fila['codigo']=='MDC'){
    			        $opcionPagina = 'declararDatosVehiculoCarnicos';
    			        $idEnvio = $fila['id_sitio'].'@'.$fila['id_operacion'];
    			}else if($fila['codigo']=='MDT'){
    			        $opcionPagina = 'declararDatosVehiculo';
    			        $idEnvio = $fila['id_sitio'].'@'.$fila['id_operacion'];
    			}else if($fila['codigo']=='TAV'){
    			    $opcionPagina = 'declararDatosVehiculoTransporteAnimalesVivos';
    			    $idEnvio = $fila['id_sitio'].'-'.$fila['id_operacion'];
    			}				
				$categoria = 'declararDVehiculo';
				$estado = 'Inf. Adicional';
			break;
			case 'declararIMercanciaPecuaria':
				$opcionPagina = 'declararInformacionMercanciaPecuaria';
				$idEnvio = $fila['id_operacion'];
				$categoria = 'declararIMercanciaPecuaria';
				$estado = 'Inf. Adicional';
			break;
			case 'declararIColmenar':
			    $opcionPagina = 'declararInformacionColmenar';
			    $idEnvio = $fila['id_operacion'];
			    $categoria = 'declararIColmenar';
			    $estado = 'Inf. Adicional';
			break;
		}
		
		$nombreArea = $cro->buscarNombreAreaPorSitioPorTipoOperacion($conexion, $fila['id_tipo_operacion'], $identificadorOperador, $fila['id_sitio'], $fila['id_operacion']);
		
		$codigoSitio = $fila['id_sitio'].'-'.$categoria;
		$nombreSitio = $fila['nombre_lugar'];
		
		$contenido = '<article
						id="'.$idEnvio.'"
						class="item"
						data-rutaAplicacion="registroOperador"
						data-opcion="'.$opcionPagina.'"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem">
						<span><small> # '.$fila['id_tipo_operacion'].'-'.$fila['id_sitio'].' </small></span>
						<span><small>'.(strlen($fila['provincia'])>14?(substr($cro->reemplazarCaracteres($fila['provincia']),0,14).'...'):(strlen($fila['provincia'])>0?$fila['provincia']:'')).'</small></span><br />
						<span><small>'.(strlen($fila['nombre_tipo_operacion'])>30?(substr($cro->reemplazarCaracteres($fila['nombre_tipo_operacion']),0,30).'...'):(strlen($fila['nombre_tipo_operacion'])>0?$fila['nombre_tipo_operacion']:'')).'<b> en </b> '.
						(strlen($nombreArea)>42?(substr($cro->reemplazarCaracteres($nombreArea),0,42).'...'):(strlen($nombreArea)>0?$nombreArea:'')).'</small></span>
						<aside class= "estadoOperador"><small> Estado: '.$estado.'</small></aside>
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
		
		$res=$cro->listarOperacionesOperadorPorProducto($conexion, $identificadorOperador," in ('cargarIA')",1000,0);
		
		while($fila = pg_fetch_assoc($res)){

		$codigoSitio = $fila['id_sitio'].'-'.$categoria;
		$nombreSitio = $fila['nombre_lugar'];
		$estado = 'Inf. Adicional';

		$nombreArea = $cro->buscarNombreAreaPorSitioPorTipoOperacion($conexion, $fila['id_tipo_operacion'], $identificadorOperador, $fila['id_sitio'], $fila['id_operacion']);

			$categoria = 'cargarIA';
			$contenido = '<article
							id="'.$fila['id_operacion'].'"
							class="item"
								data-rutaAplicacion="registroOperador"
								data-opcion="abrirOperacionesVariedades"
								ondragstart="drag(event)"
								draggable="true"
								data-destino="detalleItem"
								data-idOpcion ="'.$fila['nombretipooperacion'].'">
								<span><small></div> # '.$fila['id_operacion'].'</small></span><br/>
								<span><small>'.(strlen($fila['provincia'])>14?(substr($cro->reemplazarCaracteres($fila['provincia']),0,14).'...'):(strlen($fila['provincia'])>0?$fila['provincia']:'')).'</small></span><br />
								<span><small>'.(strlen($fila['nombre_tipo_operacion'])>30?(substr($cro->reemplazarCaracteres($fila['nombre_tipo_operacion']),0,30).'...'):(strlen($fila['nombre_tipo_operacion'])>0?$fila['nombre_tipo_operacion']:'')).'<b> en </b> '.
								(strlen($nombreArea)>42?(substr($cro->reemplazarCaracteres($nombreArea),0,42).'...'):(strlen($nombreArea)>0?$nombreArea:'')).'</small></span>
								<aside class= "estadoOperador"><small> Estado: '.$estado.'</small></aside>
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
$(document).ready(function(){	
	$("#listadoItems").addClass("comunes");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
	$("#declararICentroAcopio div> article").length == 0 ? $("#declararICentroAcopio").remove():"";
	$("#declararDVehiculo div> article").length == 0 ? $("#declararDVehiculo").remove():"";
	$("#declararIMercanciaPecuaria div> article").length == 0 ? $("#declararIMercanciaPecuaria").remove():"";
	$("#declararIColmenar div> article").length == 0 ? $("#declararIColmenar").remove():"";
	$("#cargarIA div> article").length == 0 ? $("#cargarIA").remove():"";
});
</script>