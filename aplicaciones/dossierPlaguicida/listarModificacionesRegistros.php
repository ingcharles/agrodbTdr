<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	
	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorDossierPlaguicida.php';

	require_once '../ensayoEficacia/clases/Perfil.php';
	require_once '../ensayoEficacia/clases/Flujo.php';



	$conexion = new Conexion();
	



?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
<header>
		<h1>Solicitud de modificación de registro</h1>
		<nav>
			<?php
		$testOperacion=$ce->testAccesoPermitido($conexion,$_SESSION['usuario'],'IAP','EE_OPERA');

		if($testOperacion){
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
		}
			?>
		</nav>
</header>

	<div id="solicitud">
		<h2>Solicitudes sin enviar</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="verificacion">
		<h2>En revisión de solicitud</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="subsanar">
		<h2>Para subsanar la modificación</h2>
		<div class="elementos"></div>
	</div>
	<div id="aprobarDirector">
		<h2>Por aprobar Director</h2>
		<div class="elementos"></div>
	</div>
	<div id="aprobarCoordinador">
		<h2>Por aprobar Coordinador</h2>
		<div class="elementos"></div>
	</div>
	<div id="aprobado">
		<h2>Protocolos aprobados</h2>
		<div class="elementos"></div>
	</div>

	<?php
	if($testOperacion){
		$ce = new ControladorEnsayoEficacia();
		$cg = new ControladorDossierPlaguicida();
		$res = $cg->listarModificacionesOperador($conexion, $_SESSION['usuario']);

		$estadoSolicitud='solicitud';
		$flujo=$ce->obtenerFlujo($conexion,$_SESSION['idAplicacion'],$estadoSolicitud);

		$flujosDocumento=$ce->obtenerFlujosDelDocumento($conexion,$flujo['id_flujo']);
		$flujoActual=new Flujo($flujosDocumento,'DG','',$flujo['id_flujo']);
		$flujoActual->InicializarFlujo('');
		$contador = 0;
		while($fila = pg_fetch_assoc($res))
		{
			$categoria = $fila['estado'];
			if($categoria==null)
				$categoria='solicitud';
			else
				$categoria=trim($categoria);
			if($categoria!="solicitud")
				continue;
			$date=date_create($fila['fecha_creacion']);
			$plazo=$flujoActual->Plazo();
			date_add($date,date_interval_create_from_date_string($plazo." days"));
			$fecha="F.limite: ".date_format($date,"Y-m-d");

			$contenido = '<article
							id="'.$fila['id_modificacion'].'"
							data-flujo="'.$flujo['id_flujo'].'"
							data-idOpcion="'.$flujo['id_fase'].'"
							class="item"
							data-rutaAplicacion="dossierPlaguicida"
							data-opcion="abrirSolicitudModificacion"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
							<span class="ordinal">'.++$contador.'</span>
							<span>'.(strlen($fila['producto_nombre'])>45?(substr($fila['producto_nombre'],0,45).'...'):(strlen($fila['producto_nombre'])>0?$fila['producto_nombre']:'Por definir nombre')).'</span>
							<span>'.'Solicitud No:'.$fila['id_modificacion'].'</span>
							<aside><small>'.$fecha.'</small></aside>
						</article>';
    ?>
				<script type="text/javascript">
					var contenido = <?php echo json_encode($contenido);?>;
					var categoria = <?php echo json_encode($categoria);?>;
					$("#"+categoria+" div.elementos").append(contenido);
				</script>

	<?php 				
		}
	}
    ?>	
</body>
<script>
$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#solicitud div> article").length == 0 ? $("#solicitud").remove():"";
		$("#verificacion div> article").length == 0 ? $("#verificacion").remove():"";
		$("#subsanar div> article").length == 0 ? $("#subsanar").remove():"";
		$("#aprobarDirector div> article").length == 0 ? $("#aprobarDirector").remove():"";
		$("#aprobarCoordinador div> article").length == 0 ? $("#aprobarCoordinador").remove():"";		
		$("#aprobado div> article").length == 0 ? $("#aprobado").remove():"";

	});

</script>
</html>