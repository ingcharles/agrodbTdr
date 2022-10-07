<?php
	session_start();
	require_once '../../clases/Conexion.php';

	require_once '../../clases/ControladorAplicaciones.php';

	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorDossierPecuario.php';


	$conexion = new Conexion();
	$ce=new ControladorEnsayoEficacia();

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
<header>
		<h1>Solicitudes de dossier pecuario</h1>
		<nav>
			<?php
			$testOperacion=$ce->testAccesoPermitido($conexion,$_SESSION['usuario'],'IAV','DP_OPERA');
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
	<div id="asignarDirector">
		<h2>Para asignar trámites al director</h2>
		<div class="elementos"></div>
	</div>

	<div id="asignarTecnicoIngreso">
		<h2>Para asignar trámites a los técnicos de Sanidad Animal</h2>
		<div class="elementos"></div>
	</div>
	<div id="analizarIngreso">
		<h2>Solicitudes por analizar ingreso al país</h2>
		<div class="elementos"></div>
	</div>
	<div id="aprobarIngresoDirector">
		<h2>Solicitudes por aprobar ingreso al país</h2>
		<div class="elementos"></div>
	</div>
	<div id="aprobarIngresoCoordinador">
		<h2>Solicitudes por aprobar ingreso al país</h2>
		<div class="elementos"></div>
	</div>
	

	<div id="asignarTecnicoMetodo">
		<h2>Para asignar el análisis del método analítico al técnico de laboratorio</h2>
		<div class="elementos"></div>
	</div>
	<div id="analizarMetodo">
		<h2>Análisis de métodos analíticos</h2> 
		<div class="elementos"></div>
	</div>
	<div id="aprobarMetodo">
		<h2>Por aprobar análisis de laboratorio</h2>
		<div class="elementos"></div>
	</div>
	<div id="subsanarMetodo">
		<h2>Por subsanar el método analítico</h2>
		<div class="elementos"></div>
	</div>
	<div id="asignarTecnico">
		<h2>Dossier por asignar al técnico</h2>
		<div class="elementos"></div>
	</div>
	<div id="analizarDossier">
		<h2>Dossier por evaluar</h2>
		<div class="elementos"></div>
	</div>
	<div id="aprobarDirector">
		<h2>Dossiser por aprobar el director</h2>
		<div class="elementos"></div>
	</div>
	<div id="aprobarCoordinador">
		<h2>Dossier por aporbar el Coordinador</h2>
		<div class="elementos"></div>
	</div>
	<div id="subsanarDossier">
		<h2>Dossier por subsanar</h2>
		<div class="elementos"></div>
	</div>
	<div id="aprobado">
		<h2>Dossier aprobados</h2>
		<div class="elementos"></div>
	</div>
	<div id="rechazado">
		<h2>Dossier rechazados</h2>
		<div class="elementos"></div>
	</div>

	<?php
	if($testOperacion){
		$cp = new ControladorDossierPecuario();

		$res = $cp->listarSolicitudesOperador($conexion, $_SESSION['usuario']);

		$estadoSolicitud='solicitud';
		$flujo=$ce->obtenerFlujo($conexion,$_SESSION['idAplicacion'],$estadoSolicitud);

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
					$fechaTiempo=new DateTime($fila['fecha_fin']);
					break;*/
					
				case 'verificacion':
				case 'asignarDirector':
				case 'asignarTecnicoIngreso':
				case 'analizarIngreso':
				case 'aprobarIngresoDirector':
				case 'aprobarIngresoCoordinador':
				case 'asignarTecnicoMetodo':
				case 'analizarMetodo':
				case 'aprobarMetodo':
					$paginaSiguiente='abrirSolicitudDossierBloqueado';
					$fechaTiempo=new DateTime($fila['fecha_fin']);
					break;
				case 'subsanarMetodo':
					$paginaSiguiente='abrirSubsanarMetodo';
					$fechaTiempo=new DateTime($fila['fecha_fin']);
					break;
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
				case 'aprobado':
				case 'rechazado':
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
							data-rutaAplicacion="dossierPecuario"
							data-opcion="'.$paginaSiguiente.'"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
							<span class="ordinal">'.++$contador.'</span>
							<span>'.(strlen($fila['nombre'])>45?(mb_substr($fila['nombre'],0,45).'...'):(strlen($fila['nombre'])>0?$fila['nombre']:'Por definir nombre')).'</span><br/>
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

		$("#asignarDirector div> article").length == 0 ? $("#asignarDirector").remove():"";
		$("#asignarTecnicoIngreso div> article").length == 0 ? $("#asignarTecnicoIngreso").remove():"";
		$("#analizarIngreso div> article").length == 0 ? $("#analizarIngreso").remove():"";
		$("#aprobarIngresoDirector div> article").length == 0 ? $("#aprobarIngresoDirector").remove():"";
		$("#aprobarIngresoCoordinador div> article").length == 0 ? $("#aprobarIngresoCoordinador").remove():"";
		$("#asignarTecnicoMetodo div> article").length == 0 ? $("#asignarTecnicoMetodo").remove():"";
		$("#analizarMetodo div> article").length == 0 ? $("#analizarMetodo").remove():"";
		$("#aprobarMetodo div> article").length == 0 ? $("#aprobarMetodo").remove():"";
		$("#subsanarMetodo div> article").length == 0 ? $("#subsanarMetodo").remove():"";
		$("#asignarTecnico div> article").length == 0 ? $("#asignarTecnico").remove():"";
		$("#analizarDossier div> article").length == 0 ? $("#analizarDossier").remove():"";
		$("#aprobarDirector div> article").length == 0 ? $("#aprobarDirector").remove():"";
		$("#aprobarCoordinador div> article").length == 0 ? $("#aprobarCoordinador").remove():"";
		$("#subsanarDossier div> article").length == 0 ? $("#subsanarDossier").remove():"";

		$("#aprobado div> article").length == 0 ? $("#aprobado").remove():"";
		$("#rechazado div> article").length == 0 ? $("#rechazado").remove():"";

	});

</script>
</html>