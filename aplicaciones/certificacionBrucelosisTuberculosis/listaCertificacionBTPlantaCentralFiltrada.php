<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorBrucelosisTuberculosis.php';
	
	$conexion = new Conexion();
	$cbt = new ControladorBrucelosisTuberculosis();
	
	$numSolicitud = htmlspecialchars ($_POST['bNumSolicitud'],ENT_NOQUOTES,'UTF-8');
	$fecha = htmlspecialchars ($_POST['bFechaCreacion'],ENT_NOQUOTES,'UTF-8');
	$nombrePredio = htmlspecialchars ($_POST['bNombrePredio'],ENT_NOQUOTES,'UTF-8');
	$nombrePropietario = htmlspecialchars ($_POST['bNombrePropietario'],ENT_NOQUOTES,'UTF-8');
	$idProvincia = htmlspecialchars ($_POST['bIdProvincia'],ENT_NOQUOTES,'UTF-8');
	$idCanton = htmlspecialchars ($_POST['bIdCanton'],ENT_NOQUOTES,'UTF-8');
	$idParroquia = htmlspecialchars ($_POST['bIdParroquia'],ENT_NOQUOTES,'UTF-8');
	$certificacion = htmlspecialchars ($_POST['bCertificacion'],ENT_NOQUOTES,'UTF-8');
	$tipo = htmlspecialchars ($_POST['bTipo'],ENT_NOQUOTES,'UTF-8');
	$estado = htmlspecialchars ($_POST['bEstado'],ENT_NOQUOTES,'UTF-8');
	
	if($tipo == 'certificacion'){
		//Función buscarCertificacionBT
		$certificacionesBT = $cbt->buscarCertificacionBT($conexion, $numSolicitud, $fecha, $nombrePredio,
				$nombrePropietario, $idProvincia, $idCanton,
				$idParroquia, $certificacion, $estado, $idLaboratorio);
	
		while($fila = pg_fetch_assoc($certificacionesBT)){
				
			echo '<article
						id="'.$fila['id_certificacion_bt'].'"
						class="item"
						data-rutaAplicacion="certificacionBrucelosisTuberculosis"';
			if($fila['certificacion_bt']=='Brucelosis'){
				echo 'data-opcion="abrirCertificacionBTPCBrucelosis"';
			}else{
				echo 'data-opcion="abrirCertificacionBTPCTuberculosis"';
			}
			
			echo '			ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span><b>'.$fila['num_solicitud'].'</b></span><br/>
					<small><span><b> Predio: </b>'.$fila['nombre_predio'].'</span><br /></small>
					<small><span><b> Certificación: </b>'.$fila['certificacion_bt'].'</span><br /></small>
					<aside><small><b> Creación:</b>'.date('j/n/Y',strtotime($fila['fecha'])).'<br />
					</small></aside>
				</article>';
		}
	}else{
		//Función buscarRecertificacionBT
		$certificacionesBT = $cbt->buscarRecertificacionBT($conexion, $numSolicitud, $fecha, $nombrePredio,
				$nombrePropietario, $idProvincia, $idCanton,
				$idParroquia, $certificacion, $estado);
	
		while($fila = pg_fetch_assoc($certificacionesBT)){
	
			echo '<article
						id="'.$fila['id_recertificacion_bt'].'"
						class="item"
						data-rutaAplicacion="certificacionBrucelosisTuberculosis"';			
			
		if($fila['certificacion_bt']=='Brucelosis'){
			echo 'data-opcion="abrirRecertificacionBTPCBrucelosis"';
		}else{
			echo 'data-opcion="abrirRecertificacionBTPCTuberculosis"';
		}
			echo 'ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span><b>'.$fila['num_solicitud'].'</b></span><br/>
					<small><span><b> Predio: </b>'.$fila['nombre_predio'].'</span><br /></small>
					<small><span><b> Recertificación: </b>'.$fila['certificacion_bt'].'</span><br /></small>
					<aside><small><b> Creación:</b>'.date('j/n/Y',strtotime($fila['fecha'])).'<br />
					</small></aside>
				</article>';
		}
	}
?>