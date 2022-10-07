<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorProgramasControlOficial.php';
	
	$conexion = new Conexion();
	$cpco = new ControladorProgramasControlOficial();
	
	$numSolicitud = htmlspecialchars ($_POST['bNumSolicitud'],ENT_NOQUOTES,'UTF-8');
	$fecha = htmlspecialchars ($_POST['bFechaCreacion'],ENT_NOQUOTES,'UTF-8');
	$nombrePredio = htmlspecialchars ($_POST['bNombrePredio'],ENT_NOQUOTES,'UTF-8');
	$nombrePropietario = htmlspecialchars ($_POST['bNombrePropietario'],ENT_NOQUOTES,'UTF-8');
	$idProvincia = htmlspecialchars ($_POST['bIdProvincia'],ENT_NOQUOTES,'UTF-8');
	$idCanton = htmlspecialchars ($_POST['bIdCanton'],ENT_NOQUOTES,'UTF-8');
	$idParroquia = htmlspecialchars ($_POST['bIdParroquia'],ENT_NOQUOTES,'UTF-8');
	$sitio = htmlspecialchars ($_POST['bSitio'],ENT_NOQUOTES,'UTF-8');
	$estado = htmlspecialchars ($_POST['bEstado'],ENT_NOQUOTES,'UTF-8');
	
	//Función buscarMurcielagosHematofagos
	$controlVectores = $cpco->buscarControlVectores($conexion, $numSolicitud, $fecha, $nombrePredio, 
											$nombrePropietario, $idProvincia, $idCanton, 
											$idParroquia, $sitio, $estado);
	
	while($fila = pg_fetch_assoc($controlVectores)){
		
		echo '<article 
					id="'.$fila['id_control_vectores'].'"
					class="item"
					data-rutaAplicacion="programasControlOficial"
					data-opcion="abrirControlVectores" 
					ondragstart="drag(event)" 
					draggable="true" 
					data-destino="detalleItem">
				<span class="ordinal">'.++$contador.'</span>
				<span><b>'.$fila['num_solicitud'].'</b></span><br/>
				<small><span><b> Predio: </b>'.$fila['nombre_predio'].'</span><br />
				<span><b> Fase lunar: </b>'.$fila['fase_lunar'].'</span></small>
				<aside><small><b> Creación:</b>'.date('j/n/Y',strtotime($fila['fecha'])).'<br /></small></aside>
			</article>';
	}
?>