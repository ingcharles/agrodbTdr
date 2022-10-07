<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';

	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorDossierFertilizante.php';


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
		<h1>Solicitudes de Dossier Fertilizante</h1>
		<nav>
			<?php
		$testOperacion=$ce->testAccesoPermitido($conexion,$_SESSION['usuario'],'IAF','DF_OPERA');
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
		<h2>Asignar técnico</h2>
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

		$cf = new ControladorDossierFertilizante();
		$res = $cf->listarSolicitudesOperador($conexion, $_SESSION['usuario']);

		$estadoSolicitud='solicitud';

		$aplicacion=$cf->obtenerFlujoEquivalente($conexion,'PRG_DOSSIER_PLA');
		$flujo=$ce->obtenerFlujo($conexion,$aplicacion,$estadoSolicitud);

		$flujosDocumento=$ce->obtenerFlujosDelDocumento($conexion,$flujo['id_flujo']);
		$flujoActual=new Flujo($flujosDocumento,'DG','',$flujo['id_flujo']);
		$flujoActual->InicializarFlujo('');
		$contador = 0;
		$paginaSiguiente='abrirSolicitudDossier';


		$contador = 0;
		while($fila = pg_fetch_assoc($res))
		{
			$categoria = $fila['estado'];
			if($categoria==null)
				$categoria='solicitud';
			else
				$categoria=trim($categoria);
			$fechaTiempo=new DateTime($fila['fecha_fin']);
			
			switch($categoria){
				case 'solicitud':
					$paginaSiguiente='abrirSolicitudDossier';
					$intervalo=15;
					$fechaTiempo=new DateTime($fila['fecha_solicitud']);
					$fechaTiempo->add(new DateInterval('P'.$intervalo.'D'));		//AÑADE plazo DIAS
					break;
				case 'pago':
				case 'verificacion':
				case 'asignarTecnico':
				case 'analizarDossier':
				case 'aprobarDirector':
				case 'aprobarCoordinador':
					$paginaSiguiente='abrirSolicitudDossierBloqueado';
					break;
				case 'subsanarDossier':
					$paginaSiguiente='abrirSubsanarDossier';
					break;
				case 'aprobarCumplimiento':
					$paginaSiguiente='abrirSolicitudEtiqueta';
					break;
				case 'subsanarCumplimiento':
					$paginaSiguiente='abrirSubsanarEtiqueta';
					break;
				case 'aprobado':
					$paginaSiguiente='abrirDossierAprobado';
					$fechaTiempo=null;
					break;

			}
			
			//$date=date_create($fila['fecha_creacion']);
			//$plazo=$flujoActual->Plazo();
			//date_add($date,date_interval_create_from_date_string($plazo." days"));
			//$fecha="F.límite: ".date_format($date,"Y-m-d");

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
							data-rutaAplicacion="dossierFertilizante"
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