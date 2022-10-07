<?php
	session_start();
	require_once '../../clases/Conexion.php';
	
	require_once '../../clases/ControladorAplicaciones.php';
	
	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorDossierFertilizante.php';

	require_once '../ensayoEficacia/clases/Perfil.php';
	require_once '../ensayoEficacia/clases/Flujo.php';

	$conexion = new Conexion();
	
	$ca = new ControladorAplicaciones();
	
	$identificador=$_SESSION['usuario'];

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
<header>
		<h1>Evaluación de puntos mínimos de etiqueta</h1>
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

	<div id="solicitud">
		<h2>Solicitudes sin enviar</h2>
		<div class="elementos"></div>
	</div>

	<div id="pago">
		<h2>Solicitudes en pago</h2>
		<div class="elementos"></div>
	</div>

	<div id="asignarTecnico">
		<h2>Conformación de grupos</h2>
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
		<h2>Aprobación de puntos mínimos de etiqueta</h2>
		<div class="elementos"></div>
	</div>
	<div id="subsanarCumplimiento">
		<h2>Por subsanar puntos mínimos de etiqueta</h2>
		<div class="elementos"></div>
	</div>

	

	<?php

	$ce = new ControladorEnsayoEficacia();
	$cf=new ControladorDossierFertilizante();
	//Miro si perfil es Director
	$perfiles= $ce->obtenerPerfiles($conexion,$identificador);

	$perfil=new Perfil($perfiles);
	$estadoSolicitud='aprobarCumplimiento';
	$aplicacion=$cf->obtenerFlujoEquivalente($conexion,'PRG_DOSSIER_PLA');
	$flujo=$ce->obtenerFlujo($conexion,$aplicacion,$estadoSolicitud);

	$yaDesplego=false;
	$tramites =array();
	$perfilRIA='PFL_DF_ARIA';
	if($perfil->tieneEstePerfil($perfilRIA)){
		$ts=$cf->obtenerSolicitudEtiquetaXevaluar($conexion,$identificador,$flujo['id_fase'],'T','aprobarEtiqueta');
		while($fila = pg_fetch_assoc($ts))
		{
			$tramites[]=$fila;
		}
	}



	if(sizeof($tramites)>0){
		$flujos=$ce->obtenerFlujosDelDocumento($conexion,$flujo['id_flujo']);

		$contador = 0;

		foreach($tramites as $key=>$fila)
		{
			$categoria = $fila['estado'];
			if($categoria==null)
				$categoria='solicitud';
			else
				$categoria = trim($categoria);
				$date=date_create($fila['fecha_inicio']);
				$flujoActual=new Flujo($flujos,$fila[id_flujo_documento]);
				$f=$flujoActual->Plazo();
				date_add($date,date_interval_create_from_date_string($f." days"));
				$fecha="F.limite: ".date_format($date,"Y-m-d");


				$contenido = '<article
							id="'.$fila['id_documento'].'"
							data-flujo="'.$flujo['id_flujo'].'"
							data-idOpcion="'.$flujo['id_fase'].'"
							data-nombre="'.$fila['id_tramite_flujo'].'"
							class="item"
							data-rutaAplicacion="dossierFertilizante"
							data-opcion="abrirEvaluarEtiqueta"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
							<span class="ordinal">'.++$contador.'</span>
							<span>'.(strlen($fila['razon_social'])>45?(substr($fila['razon_social'],0,45).'...'):(strlen($fila['razon_social'])>0?$fila['razon_social']:'Empresa')).'</span><br/>
							<span>'.(strlen($fila['nombre'])>45?(substr($fila['nombre'],0,45).'...'):(strlen($fila['nombre'])>0?$fila['nombre']:'X definir')).'</span><br/>
							<span>'.(strlen($fila['id_expediente'])>45?(substr($fila['id_expediente'],0,45).'...'):(strlen($fila['id_expediente'])>0?$fila['id_expediente']:'X definir')).'</span>
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