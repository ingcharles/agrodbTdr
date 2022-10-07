<?php 
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorExpedienteDigital.php';
	$conexion = new Conexion();
	$ce = new ControladorExpedienteDigital();
	
	set_time_limit(10800);
	
	$idGrupoOld=0;	
	$infoInspeccion = $ce->migrarDatos($conexion,1,'','','','','','','');
		
	while ($datos = pg_fetch_assoc($infoInspeccion)){
		$tmp = explode(" ", $datos['observacion']);
		$idSolicitud=$tmp[0];
		if(!is_numeric($idSolicitud)) {
			$idSolicitud=0;
			$observacion=$datos['observacion'];
		}else {
			$tmp = explode($idSolicitud, $datos['observacion']);
		    $observacion=trim($tmp[1]);
		}								
		$idGrupo=$datos['id_grupo'];		
		if($idGrupo==$idGrupoOld);
		else $idInspeccion=$datos['id_inspeccion'];
			
		$idItemInspeccion=$datos['id_item_inspeccion'] == '' ? 0 : $datos['id_item_inspeccion'];
		$fechaInspeccion=$datos['fecha_inspeccion'];
		$tipoElemento=$datos['tipo_elemento']; 
		
		$idGrupoOld=$idGrupo;
	    $guardarObservacion = $ce->migrarDatos($conexion,2,'',$idInspeccion,$idItemInspeccion,$fechaInspeccion,$observacion,$tipoElemento,$idSolicitud);
		$idSolicitud='';
	}
	
//------------eliminar datos duplicados----------------------------------------
	
	   $eliminar=$ce->eliminarDuplicados($conexion);
	
?>


