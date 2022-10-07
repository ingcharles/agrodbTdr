<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorPAPP.php';
?>

<header>
		<h1>Seguimiento Trimestral</h1>
		<nav>
		<?php 
			
			$conexion = new Conexion();
			$ca = new ControladorAplicaciones();
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

	
	<div id="aprobado">
		<h2>Actividades aprobadas en planta central</h2>
		<div class="elementos"></div>
	</div>
		
	
	<?php 
		$cd = new ControladorPAPP();
		$res = $cd->listarRegistrosPOA($conexion, $_SESSION['usuario']);
		$contador = 0;
		$cantidadCaracteres = 50;
		while($fila = pg_fetch_assoc($res)){
			if($fila['estado']==4){
            	$categoria ="aprobado";
            }
            
            $cadenaCodigoComponente = strpos($fila['codigo_componente'],' ',$cantidadCaracteres);
            $cadenaActividad = strpos($fila['codigo_actividad'],' ',$cantidadCaracteres);
            $cadenaDetalleActividad = strpos($fila['detalle_actividad'],' ',$cantidadCaracteres);
            
            $codigoComponente = (strlen($fila['codigo_componente'])>$cantidadCaracteres?(substr($fila['codigo_componente'], 0, (($cadenaCodigoComponente)?$cadenaCodigoComponente:$cantidadCaracteres)).'...'):(strlen($fila['codigo_componente'])>0?$fila['codigo_componente']:'Sin asunto'));
            $codigoActividad = (strlen($fila['codigo_actividad'])>$cantidadCaracteres?(substr($fila['codigo_actividad'], 0, (($cadenaActividad)?$cadenaActividad:$cantidadCaracteres)).'...'):(strlen($fila['codigo_actividad'])>0?$fila['codigo_actividad']:'Sin asunto'));
            $detalleActividad = (strlen($fila['detalle_actividad'])>$cantidadCaracteres?(substr($fila['detalle_actividad'], 0, (($cadenaActividad)?$cadenaActividad:$cantidadCaracteres)).'...'):(strlen($fila['detalle_actividad'])>0?$fila['detalle_actividad']:'Sin asunto'));
             
            
            
           
			$contenido = '<article 
						id="'.$fila['id_item'].'"
						class="item"
						data-rutaAplicacion="poa"
						data-opcion="abrirSeguimientoTrimestral" 
						ondragstart="drag(event)" 
						draggable="true" 
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
						<span>'.$detalleActividad.'</span>
					<aside>
							<strong>Cod: </strong>'	.$fila['id_item'].'<br />'
							.date('j/n/Y',strtotime($fila['fecha_creacion'])).'
					
					</aside>
				</article>';
			
		
	?>
	<script type="text/javascript">
					var contenido = <?php echo json_encode($contenido);?>;
					var categoria = <?php echo json_encode($categoria);?>;
					$("#"+categoria+" div.elementos").append(contenido);
	</script>
<?php					
		}
	?>
<script>
	
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');

		$("#creado div> article").length == 0 ? $("#creado").remove():"";
		$("#enviado div> article").length == 0 ? $("#enviado").remove():"";
		$("#atendido div> article").length == 0 ? $("#atendido").remove():"";
		$("#reenviado div> article").length == 0 ? $("#reenviado").remove():"";
		$("#asignarResponsable div> article").length == 0 ? $("#asignarResponsable").remove():"";
		$("#revisionResponsable div> article").length == 0 ? $("#revisionResponsable").remove():"";
		$("#atentidoResponsable div> article").length == 0 ? $("#atentidoResponsable").remove():"";
		$("#aprobado div> article").length == 0 ? $("#aprobado").remove():"";
		$("#archivado div> article").length == 0 ? $("#archivado").remove():"";
	});

	
	</script>
