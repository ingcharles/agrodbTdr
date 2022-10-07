<?php 
	//session_start();
	require_once '../../clases/Conexion.php';
	/*require_once '../../clases/controladorAplicaciones.php';*/
	require_once '../../clases/ControladorSolicitudes.php';
	
	$conexion = new Conexion();
	$cs = new ControladorSolicitudes();
	
	$conexion->verificarSesion();
	
?>

	<header>
		<h1>Solicitudes por atender</h1>	
	</header>
	
	
	
	<div id="enviado">
		<h2>Nuevas solicitudes</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="reenviado">
		<h2>Solicitudes por corregir</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="revisionResponsable">
		<h2>Solicitudes por revisar</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="atentidoResponsable">
		<h2>Solicitudes por revisar</h2>
		<div class="elementos"></div>
	</div>
	
	<?php 
		
		$res = $cs->listarSolicitudesPendientes($conexion, $_SESSION['usuario']);
		$contador = 0;
		while($fila = pg_fetch_assoc($res)){
			
			$categoria = $fila['condicion'];
			
			$contenido = '<article 
						id="'.$fila['id_solicitud'].'"
						class="item"
						data-rutaAplicacion="solicitudes"
						data-opcion="abrirSolicitud" 
						ondragstart="drag(event)" 
						draggable="true" 
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span>Solicitud de '.$fila['tipo'].'<br/>'.$fila['estado'].'</span>
					<aside>'.date('j/n/Y',strtotime($fila['fecha_creacion'])).'</aside>
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

		$("#enviado div> article").length == 0 ? $("#enviado").remove():"";
		$("#reenviado div> article").length == 0 ? $("#reenviado").remove():"";
		$("#revisionResponsable div> article").length == 0 ? $("#revisionResponsable").remove():"";
		$("#atentidoResponsable div> article").length == 0 ? $("#atentidoResponsable").remove():"";

		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un documento para revisarlo.</div>');

	});
</script>