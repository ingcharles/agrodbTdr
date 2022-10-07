<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorNotificacionEventoSanitario.php';
	
	$conexion = new Conexion();
	$cpco = new ControladorNotificacionEventoSanitario();
	
	$numSolicitud = htmlspecialchars ($_POST['bNumSolicitud'],ENT_NOQUOTES,'UTF-8');
	$fecha = htmlspecialchars ($_POST['bFechaCreacion'],ENT_NOQUOTES,'UTF-8');
	$idProvincia = htmlspecialchars ($_POST['bIdProvincia'],ENT_NOQUOTES,'UTF-8');
	$idCanton = htmlspecialchars ($_POST['bIdCanton'],ENT_NOQUOTES,'UTF-8');
	$idParroquia = htmlspecialchars ($_POST['bIdParroquia'],ENT_NOQUOTES,'UTF-8');
	$sitio = htmlspecialchars ($_POST['bSitio'],ENT_NOQUOTES,'UTF-8');
	$finca = htmlspecialchars ($_POST['bFinca'],ENT_NOQUOTES,'UTF-8');
	$estado = htmlspecialchars ($_POST['bEstado'],ENT_NOQUOTES,'UTF-8');
	
	//Función buscar 
	$NotificacionEventoSanitario = $cpco->buscarNotificacionEventoSanitarioFiltrado($conexion, $numSolicitud, $fecha, $idProvincia, 
													$idCanton, $idParroquia, $sitio, $finca,$estado);
	
	while($fila = pg_fetch_assoc($NotificacionEventoSanitario)){
		
		echo '<article 
					id="'.$fila['id_notificacion_evento_sanitario'].'"
					class="item"
					data-rutaAplicacion="seguimientoEventosSanitarios"
					data-opcion="abrirNotificacionEventoSanitario" 
					ondragstart="drag(event)" 
					draggable="true" 
					data-destino="detalleItem">
				<span class="ordinal">'.++$contador.'</span>
				<span><b>'.$fila['numero'].'</b></span><br/>
				<small><span><b> Sitio: </b>'.$fila['sitio_predio'].'</span><br /></small>
				<aside><small><b> Creación:</b>'.date('j/n/Y',strtotime($fila['fecha'])).'<br />';
 		
		if ($fila['fecha_nueva_inspeccion'] != ''){
			echo '<b>Inspección:</b>'.date('j/n/Y',strtotime($fila['fecha_nueva_inspeccion']));
		}
		
 		echo '</small></aside>
			</article>';
	}
?>