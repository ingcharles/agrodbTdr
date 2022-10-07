<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorPAPP.php';	
	
	$fecha = getdate();
	
	function reemplazarCaracteres($cadena){
		$cadena = str_replace('á', 'a', $cadena);
		$cadena = str_replace('é', 'e', $cadena);
		$cadena = str_replace('í', 'i', $cadena);
		$cadena = str_replace('ó', 'o', $cadena);
		$cadena = str_replace('ú', 'u', $cadena);
		$cadena = str_replace('ñ', 'n', $cadena);
	
		$cadena = str_replace('Á', 'A', $cadena);
		$cadena = str_replace('É', 'E', $cadena);
		$cadena = str_replace('Í', 'I', $cadena);
		$cadena = str_replace('Ó', 'O', $cadena);
		$cadena = str_replace('Ú', 'U', $cadena);
		$cadena = str_replace('Ñ', 'N', $cadena);
	
		return $cadena;
	}
?>

<header>
		<h1>Registros Proforma</h1>
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

	
	<div id="creado">
		<h2>Actividades Proforma creadas</h2>
		<div class="elementos"></div>
	</div>
	
	
	<div id="enviado">
		<h2>Actividades enviadas a revisión Director/Coordinador</h2>
		<div class="elementos"></div>
	</div>

	<div id="reenviado">
		<h2>Actividades enviadas a corrección</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="atendido">
		<h2>Actividades aprobadas por Director/Coordinador</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="aprobado">
		<h2>Actividades aprobadas por Planificación</h2>
		<div class="elementos"></div>
	</div>
		
	
	<?php 
		$cd = new ControladorPAPP();
		$res = $cd->listarRegistrosPOA($conexion, $_SESSION['usuario'], $fecha['year']);
		$contador = 0;
		$cantidadCaracteres = 50;
		while($fila = pg_fetch_assoc($res)){
			if($fila['estado']==1 && $fila['observaciones']!=null){
	           $categoria ="reenviado";
            }  
            else if($fila['estado']==1){
               $categoria ="creado"; 
            }
            else if($fila['estado']==2){
            	$categoria ="enviado";
            }
            else if($fila['estado']==3){
            	$categoria ="atendido";
            }
            else if($fila['estado']==4){
            	$categoria ="aprobado";
            }
            
            $actividad = reemplazarCaracteres($fila['codigo_actividad']);
            $cadenaDetalleActividad = $actividad;
            $detalleActividad = (strlen($cadenaDetalleActividad)>$cantidadCaracteres?(substr($cadenaDetalleActividad,0,$cantidadCaracteres).'...'):$cadenaDetalleActividad);
             
            /*
            $cadenaCodigoComponente = strpos($fila['codigo_componente'],' ',$cantidadCaracteres);
            $cadenaActividad = strpos($fila['codigo_actividad'],' ',$cantidadCaracteres);
            $cadenaDetalleActividad = strpos($fila['detalle_actividad'],' ',$cantidadCaracteres);
            
            $codigoComponente = (strlen($fila['codigo_componente'])>$cantidadCaracteres?(substr($fila['codigo_componente'], 0, (($cadenaCodigoComponente)?$cadenaCodigoComponente:$cantidadCaracteres)).'...'):(strlen($fila['codigo_componente'])>0?$fila['codigo_componente']:'Sin asunto'));
            $codigoActividad = (strlen($fila['codigo_actividad'])>$cantidadCaracteres?(substr($fila['codigo_actividad'], 0, (($cadenaActividad)?$cadenaActividad:$cantidadCaracteres)).'...'):(strlen($fila['codigo_actividad'])>0?$fila['codigo_actividad']:'Sin asunto'));
            $detalleActividad = (strlen($fila['detalle_actividad'])>$cantidadCaracteres?(substr($fila['detalle_actividad'], 0, (($cadenaActividad)?$cadenaActividad:$cantidadCaracteres)).'...'):(strlen($fila['detalle_actividad'])>0?$fila['detalle_actividad']:'Sin asunto'));
            */
            
            //$detalleActividad =  reemplazarCaracteres($fila['detalle_actividad']);
           
			$contenido = '<article 
						id="'.$fila['id_item'].'"
						class="item"
						data-rutaAplicacion="poa"
						data-opcion="abrirRegistroPOA" 
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
		$("#detalleItem").html('<div class="mensajeInicial">Registros Proforma.</div>');
	});

	
	</script>
