<?php 
	//session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorDocumentos.php';
	
	$conexion = new Conexion();
	$ca = new ControladorAplicaciones();
	
	$conexion->verificarSesion();
	
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
		$cadena = str_replace('“', '', $cadena);
		$cadena = str_replace('”', '', $cadena);
		
	
		return $cadena;
	}
?>

<header>
		<h1>Mis documentos</h1>
		<nav>
		<?php 
			
			
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
	
	<div id="creado">
		<h2>Documentos creados</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="enviado">
		<h2>Enviados a revisión</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="atendido">
		<h2>Atendidos por revisores</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="reenviado">
		<h2>Enviados a corrección</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="asignarResponsable">
		<h2>Asignación a responsable del área</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="revisionResponsable">
		<h2>Enviados a revisión del responsable del área</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="atentidoResponsable">
		<h2>Atendido por responsable del área</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="aprobado">
		<h2>Asignación de número </h2>
		<div class="elementos"></div>
	</div>
	
	<div id="archivado">
		<h2>Subir respaldo en PDF </h2>
		<div class="elementos"></div>
	</div>
	
	
	<?php 
		$cd = new ControladorDocumentos();
		$res = $cd->listarDocumentos($conexion, $_SESSION['usuario'],'ABIERTOS');
		$contador = 0;
		while($fila = pg_fetch_assoc($res)){
			$tmp = explode("-", $fila['id_documento']);
			$asunto = reemplazarCaracteres($fila['asunto']);
			
			$categoria = $fila['condicion'];

			$contenido = '<article 
						id="'.$fila['id_documento'].'"
						class="item"
						data-rutaAplicacion="documentos"
						data-opcion="abrirDocumento" 
						ondragstart="drag(event)" 
						draggable="true" 
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span>'.(strlen($asunto)>20?(substr($asunto,0,20).'...'):(strlen($asunto)>0?$asunto:'Sin asunto')).'</span>
					<aside>'.date('j/n/Y',strtotime($fila['fecha_creacion'])).'<br/>'.$tmp[0].' '.$tmp[1].'</aside>
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