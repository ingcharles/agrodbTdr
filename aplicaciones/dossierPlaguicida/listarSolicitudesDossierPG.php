<?php
	session_start();
	require_once '../../clases/Conexion.php';

	require_once '../../clases/ControladorAplicaciones.php';

	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorDossierPlaguicida.php';

	require_once '../ensayoEficacia/clases/Perfil.php';
	require_once '../ensayoEficacia/clases/Flujo.php';



	$conexion = new Conexion();
	$ce = new ControladorEnsayoEficacia();



?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
<header>
		<h1>Solicitud de Dossier de Plaguicidas</h1>
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
	
	<div id="pago">
		<h2>Solicitudes en pago</h2>
		<div class="elementos"></div>
	</div>

	<div id="verificacion">
		<h2>Solicitudes en verificación</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="asignarTecnico">
		<h2>Asignar técnico / Conformación de grupos</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="analizarDossier">
		<h2>Dossier en análisis</h2>
		<div class="elementos"></div>
	</div>
	<div id="aprobarDirector">
		<h2>Por aprobar Director de RIA</h2>
		<div class="elementos"></div>
	</div>
	<div id="aprobarCoordinador">
		<h2>Por aprobar Coordinador de RIA</h2>
		<div class="elementos"></div>
	</div>
	<div id="subsanarDossier">
		<h2>Por subsanar dossier</h2>
		<div class="elementos"></div>
	</div>
	<div id="aprobarCumplimiento">
		<h2>Puntos mínimos de etiqueta</h2>
		<div class="elementos"></div>
	</div>
	<div id="subsanarCumplimiento">
		<h2>Por subsanar puntos mínimos de etiqueta</h2>
		<div class="elementos"></div>
	</div>
	

	<?php
	if($testOperacion){
		
		$cg = new ControladorDossierPlaguicida();
		$res = $cg->listarSolicitudesOperador($conexion, $_SESSION['usuario']);
		$estadoSolicitud='solicitud';
		$flujo=$ce->obtenerFlujo($conexion,$_SESSION['idAplicacion'],$estadoSolicitud);

		$flujosDocumento=$ce->obtenerFlujosDelDocumento($conexion,$flujo['id_flujo']);
		$flujoActual=new Flujo($flujosDocumento,'DG','',$flujo['id_flujo']);
		$flujoActual->InicializarFlujo('');
		$contador = 0;
		$paginaSiguiente='abrirSolicitudDossier';
		while($fila = pg_fetch_assoc($res))
		{
			$categoria = $fila['estado'];
			if($categoria==null)
				$categoria='solicitud';
			else
				$categoria=trim($categoria);
			$fechaTiempo=null;

			switch($categoria){
				case 'solicitud':
					$paginaSiguiente='abrirSolicitudDossier';
					$intervalo=15;
					$fechaTiempo=new DateTime($fila['fecha_solicitud']);
					$fechaTiempo->add(new DateInterval('P'.$intervalo.'D'));		//AÑADE plazo DIAS
					break;
				case 'pago':
					/*$paginaSiguiente='abrirPagoDossier';
					break;*/
				case 'verificacion':
				case 'asignarTecnico':
				case 'analizarDossier':
				case 'aprobarDirector':
				case 'aprobarCoordinador':
					$paginaSiguiente='abrirSolicitudDossierBloqueado';
					$fechaTiempo=new DateTime($fila['fecha_fin']);
					break;
				case 'subsanarDossier':
					$paginaSiguiente='abrirSubsanarDossier';
					$fechaTiempo=new DateTime($fila['fecha_fin']);
					break;
				case 'aprobarCumplimiento':
					$paginaSiguiente='abrirSolicitudEtiqueta';
					$fechaTiempo=new DateTime($fila['fecha_fin']);
					break;
				case 'subsanarCumplimiento':
					$paginaSiguiente='abrirSubsanarEtiqueta';
					$fechaTiempo=new DateTime($fila['fecha_fin']);
					break;
				case 'aprobado':
					$paginaSiguiente='abrirDossierAprobado';
					break;

			}


			$fecha='';
			if($fechaTiempo!=null){
				$fechaActual=new DateTime();
				if($fechaActual<$fechaTiempo)
					$fecha ='F.límite:'.$fechaTiempo->format('Y-m-d');
				else
					$fecha ='<font color="red">'.'F.límite:'.$fechaTiempo->format('Y-m-d').'</font>';
			}

			$contenido = '<article
							id="'.$fila['id_solicitud'].'"
							data-flujo="'.$flujo['id_flujo'].'"
							data-idOpcion="'.$flujo['id_fase'].'"
							class="item"
							data-rutaAplicacion="dossierPlaguicida"
							data-opcion="'.$paginaSiguiente.'"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
							<span class="ordinal">'.++$contador.'</span>
							<span>'.(strlen($fila['producto_nombre'])>45?(substr($fila['producto_nombre'],0,45).'...'):(strlen($fila['producto_nombre'])>0?$fila['producto_nombre']:'Por definir nombre')).'</span><br/>
							<span>'.'Solicitud No:'.$fila['id_solicitud'].'</span>
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
		$("#pago div> article").length == 0 ? $("#pago").remove():"";

		$("#verificacion div> article").length == 0 ? $("#verificacion").remove() : "";

		$("#asignarTecnico div> article").length == 0 ? $("#asignarTecnico").remove():"";
		$("#aprobarDirector div> article").length == 0 ? $("#aprobarDirector").remove():"";
		$("#analizarDossier div> article").length == 0 ? $("#analizarDossier").remove():"";
		$("#aprobarCoordinador div> article").length == 0 ? $("#aprobarCoordinador").remove():"";
		$("#subsanarDossier div> article").length == 0 ? $("#subsanarDossier").remove():"";
		$("#aprobarCumplimiento div> article").length == 0 ? $("#aprobarCumplimiento").remove():"";
		$("#subsanarCumplimiento div> article").length == 0 ? $("#subsanarCumplimiento").remove():"";
		

	});

</script>
</html>