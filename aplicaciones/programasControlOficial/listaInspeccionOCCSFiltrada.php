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
	$nombreAsociacion = htmlspecialchars ($_POST['bNombreAsociacion'],ENT_NOQUOTES,'UTF-8');
	$idProvincia = htmlspecialchars ($_POST['bIdProvincia'],ENT_NOQUOTES,'UTF-8');
	$idCanton = htmlspecialchars ($_POST['bIdCanton'],ENT_NOQUOTES,'UTF-8');
	$idParroquia = htmlspecialchars ($_POST['bIdParroquia'],ENT_NOQUOTES,'UTF-8');
	$sector = htmlspecialchars ($_POST['bSector'],ENT_NOQUOTES,'UTF-8');
	$estado = htmlspecialchars ($_POST['bEstado'],ENT_NOQUOTES,'UTF-8');
	
	//Función buscarInspeccionOCCS
	$inspeccionesOCCS = $cpco->buscarInspeccionOCCS($conexion, $numSolicitud, $fecha, $nombrePredio,  
													$nombrePropietario, $nombreAsociacion, $idProvincia, 
													$idCanton, $idParroquia, $sector, $estado);
	
	while($fila = pg_fetch_assoc($inspeccionesOCCS)){
		
		echo '<article 
					id="'.$fila['id_inspeccion_occs'].'"
					class="item"
					data-rutaAplicacion="programasControlOficial"
					data-opcion="abrirInspeccionOCCS" 
					ondragstart="drag(event)" 
					draggable="true" 
					data-destino="detalleItem">
				<span class="ordinal">'.++$contador.'</span>
				<span><b>'.$fila['num_solicitud'].'</b></span><br/>
				<small><span><b> Predio: </b>'.$fila['nombre_predio'].'</span><br /></small>
				<aside><small><b> Creación:</b>'.date('j/n/Y',strtotime($fila['fecha'])).'<br />';
 		
		if ($fila['fecha_nueva_inspeccion'] != ''){
			echo '<b>Inspección:</b>'.date('j/n/Y',strtotime($fila['fecha_nueva_inspeccion']));
		}
		
 		echo '</small></aside>
			</article>';
	}
?>