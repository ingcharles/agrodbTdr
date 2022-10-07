<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEventoSanitario.php';
	
	$conexion = new Conexion();
	$cpco = new ControladorEventoSanitario();
	
	$numSolicitud = htmlspecialchars ($_POST['bNumSolicitud'],ENT_NOQUOTES,'UTF-8');
	$fecha = htmlspecialchars ($_POST['bFechaCreacion'],ENT_NOQUOTES,'UTF-8');
	$idProvincia = htmlspecialchars ($_POST['bIdProvincia'],ENT_NOQUOTES,'UTF-8');
	$idCanton = htmlspecialchars ($_POST['bIdCanton'],ENT_NOQUOTES,'UTF-8');
	$idParroquia = htmlspecialchars ($_POST['bIdParroquia'],ENT_NOQUOTES,'UTF-8');
	$sitio = htmlspecialchars ($_POST['bSitio'],ENT_NOQUOTES,'UTF-8');
	$nombrePredio = htmlspecialchars ($_POST['bNombrePredio'],ENT_NOQUOTES,'UTF-8');
	$estado = htmlspecialchars ($_POST['bEstado'],ENT_NOQUOTES,'UTF-8');
	$sindrome = htmlspecialchars ($_POST['bSindrome'],ENT_NOQUOTES,'UTF-8');
	
	//Función buscar 
	$EventoSanitario = $cpco->buscarEventoSanitarioFiltrado($conexion, $numSolicitud, $fecha, $idProvincia, 
													$idCanton, $idParroquia, $sitio, $nombrePredio,$estado, $sindrome);
	
	while($fila = pg_fetch_assoc($EventoSanitario)){
		
		echo '<article 
					id="'.$fila['id_evento_sanitario'].'"
					class="item"
					data-rutaAplicacion="seguimientoEventosSanitarios"';
		
			if($fila['estado']=='Creado'){
				echo 'data-opcion="abrirEventoSanitarioCierre"';
			}else if($fila['estado']=='primeraVisita'){
				echo 'data-opcion="abrirEventoSanitarioCierre"';
			}else if($fila['estado']=='visita'){
				echo 'data-opcion="abrirEventoSanitarioCierre"';
			}else if($fila['estado']=='visitaCierre'){
				echo 'data-opcion="abrirEventoSanitarioCierre"';
			}else if($fila['estado']=='cerrado'){
				echo 'data-opcion="abrirEventoSanitarioCierre"';
			}else if($fila['estado']=='plantaCentral'){
				echo 'data-opcion="abrirEventoSanitarioPC"';
			}
		
		echo '      ondragstart="drag(event)" 
					draggable="true" 
					data-destino="detalleItem">
				<span class="ordinal">'.++$contador.'</span>
				<span><b>'.$fila['numero_formulario'].'</b></span><br/>
				<small><span><b> Patología: </b>'.$fila['sindrome_presuntivo'].'</span><br /></small>
				<small><span><b> Provincia: </b>'.$fila['provincia'].'</span><br /></small>
				<small><span><b> Estado: </b>'.$fila['estado'].'</span><br /></small>
				<aside><small><b> Creación:</b>'.date('j/n/Y',strtotime($fila['fecha'])).'<br />';
 		
		if ($fila['fecha_nueva_visita'] != ''){
			echo '<b>Visita:</b>'.date('j/n/Y',strtotime($fila['fecha_nueva_visita']));
		}
		
 		echo '</small></aside>
			</article>';
	}
?>