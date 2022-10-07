<?php

class ControladorAdministracionDeTrampas{
	
	public function guardarNuevoAdminintracionTrampas ($conexion, $codigoTrampa, $idAreaTrampa, $etapaTrampa, $fechaInstalacion, $idProvincia, $idCanton, $idParroquia, $coordenadaX, $coordenadaY, $coordenadaZ, $idLugarInstalacion, $numeroLugarInstalacion, $idPlagaMonitoreada, $idTipoTrampa, $idTipoAtrayente, $estadoTrampa, $observacion, $identficadorTecnico, $codigoProgramaEspecifico){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO
				g_administracion_trampas.administracion_trampas(codigo_trampa, id_area_trampa, etapa_trampa, fecha_instalacion_trampa, id_provincia, id_canton, 
																id_parroquia, coordenadax, coordenaday, coordenadaz, id_lugar_instalacion, numero_lugar_instalacion, 
																id_plaga, id_tipo_trampa, id_tipo_atrayente, estado_trampa, observacion, identificador_tecnico, fecha_modificacion, codigo_programa_especifico)
				VALUES('$codigoTrampa', $idAreaTrampa, '$etapaTrampa', '$fechaInstalacion', $idProvincia, $idCanton, $idParroquia, $coordenadaX, $coordenadaY, $coordenadaZ, $idLugarInstalacion, $numeroLugarInstalacion, $idPlagaMonitoreada, $idTipoTrampa, $idTipoAtrayente, '$estadoTrampa', '$observacion', '$identficadorTecnico','now()', '$codigoProgramaEspecifico') RETURNING id_administracion_trampa;");
				return $res;
	}
	
	public function guardarNuevoHistoriaAdminintracionTrampas ($conexion, $idAdministracionTrampa, $codigoTrampa, $idAreaTrampa, $etapaTrampa, $fechaInstalacion, $idProvincia, $idCanton, $idParroquia, $coordenadaX, $coordenadaY, $coordenadaZ, $idLugarInstalacion, $numeroLugarInstalacion, $idPlagaMonitoreada, $idTipoTrampa, $idTipoAtrayente, $estadoTrampa, $observacion, $identficadorTecnico, $codigoProgramaEspecifico){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
				g_administracion_trampas.historia_administracion_trampas(id_administracion_trampa, codigo_trampa, id_area_trampa, etapa_trampa, fecha_instalacion_trampa, id_provincia, id_canton,
				id_parroquia, coordenadax, coordenaday, coordenadaz, id_lugar_instalacion, numero_lugar_instalacion,
				id_plaga, id_tipo_trampa, id_tipo_atrayente, estado_trampa, observacion, identificador_tecnico, fecha_modificacion, codigo_programa_especifico)
				VALUES($idAdministracionTrampa, '$codigoTrampa', $idAreaTrampa, '$etapaTrampa', '$fechaInstalacion', $idProvincia, $idCanton, $idParroquia, $coordenadaX, $coordenadaY, $coordenadaZ, $idLugarInstalacion, $numeroLugarInstalacion, $idPlagaMonitoreada, $idTipoTrampa, $idTipoAtrayente, '$estadoTrampa', '$observacion', '$identficadorTecnico', 'now()', '$codigoProgramaEspecifico');");
		return $res;
	}
	
	public function obtenerListaAdministracionTrampas($conexion, $nombreArea, $codigoTrampa, $estadoTrampa, $provincia, $fechaInicio, $fechaFin){
	
		$nombreArea = $nombreArea != "" ? "'" . $nombreArea ."'" : "NULL";
		$codigoTrampa = $codigoTrampa != "" ? "'%" . $codigoTrampa . "%'" : "NULL";
		$estadoTrampa = $estadoTrampa != "" ? "'" . $estadoTrampa . "'" : "NULL";
		$provincia = $provincia != "" ? "'" . $provincia . "'" : "NULL";
		$fechaInicio = $fechaInicio != "" ? "'" . $fechaInicio . " 00:00:00'" : "NULL";
		$fechaFin = $fechaFin != "" ? "'" . $fechaFin . " 00:00:24'" : "NULL";	
		
		$res = $conexion->ejecutarConsulta("SELECT 
												adt.id_administracion_trampa, art.nombre_area_trampa, adt.codigo_trampa, adt.estado_trampa, lo.nombre, to_char(adt.fecha_instalacion_trampa,'YYYY/MM/DD') fecha_registro 
											FROM 
												g_administracion_trampas.administracion_trampas adt, 
												g_catalogos.areas_trampas art, 
												g_catalogos.localizacion lo	
											WHERE 
												adt.id_area_trampa = art.id_area_trampa 
												and adt.id_provincia = lo.id_localizacion 
												and ($nombreArea is NULL or adt.id_area_trampa = $nombreArea)
												and ($codigoTrampa is NULL or adt.codigo_trampa like $codigoTrampa)
												and ($estadoTrampa is NULL or adt.estado_trampa = $estadoTrampa)
												and ($provincia is NULL or adt.id_provincia = $provincia)
												and ($fechaInicio is NULL or adt.fecha_instalacion_trampa >= $fechaInicio)
												and ($fechaFin is NULL or adt.fecha_instalacion_trampa <= $fechaFin);");
	
		return $res;
	}
	
	public function obtenerAdministracionTrampaPorIdAdministracion($conexion, $idAdministracionTrampa){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_administracion_trampas.administracion_trampas adt, 
												g_catalogos.areas_trampas art, 
												g_catalogos.lugar_instalacion li,
												g_catalogos.plaga pl,
												g_catalogos.tipo_trampa tt,
												g_catalogos.tipo_atrayente ta
											WHERE
												adt.id_area_trampa = art.id_area_trampa
												and adt.id_lugar_instalacion = li.id_lugar_instalacion
												and adt.id_plaga = pl.id_plaga
												and adt.id_tipo_trampa = tt.id_tipo_trampa
												and adt.id_tipo_atrayente = ta.id_tipo_atrayente
												and adt.id_administracion_trampa = $idAdministracionTrampa;");
		return $res;
	}	
	
	public function obtenerListaHistoriaAdministracionTrampas($conexion, $nombreArea, $codigoTrampa, $provincia, $fechaTrampa){
	
		$nombreArea = $nombreArea != "" ? $nombreArea : "NULL";
		$codigoTrampa = $codigoTrampa != "" ? "'%" . $codigoTrampa . "%'" : "NULL";
		$provincia = $provincia != "" ? "'" . $provincia . "'" : "NULL";
		$fechaTrampa1 = $fechaTrampa != "" ? "'" . $fechaTrampa . " 00:00:00'" : "NULL" ;
		$fechaTrampa2 = $fechaTrampa != "" ? "'" . $fechaTrampa . " 00:00:24'" : "NULL";

		$res = $conexion->ejecutarConsulta("SELECT
												distinct adt.id_administracion_trampa, art.nombre_area_trampa, adt.codigo_trampa, lo.nombre 
											FROM
												g_administracion_trampas.historia_administracion_trampas adt,
												g_catalogos.areas_trampas art,
												g_catalogos.localizacion lo
											WHERE
												adt.id_area_trampa = art.id_area_trampa
												and adt.id_provincia = lo.id_localizacion												
												and ($nombreArea is NULL or adt.id_area_trampa = $nombreArea)
												and ($codigoTrampa is NULL or adt.codigo_trampa like $codigoTrampa)
												and ($provincia is NULL or adt.id_provincia = $provincia)
												and ($fechaTrampa1 is NULL or fecha_instalacion_trampa >= $fechaTrampa1)
       											and ($fechaTrampa2 is NULL or fecha_instalacion_trampa <= $fechaTrampa2);");
	
		return $res;
	}
	
	public function obtenerHistoriaAdministracionTrampaPorIdAdministracion($conexion, $idAdministracionTrampa){
	
		$res = $conexion->ejecutarConsulta("SELECT
												id_administracion_trampa, codigo_trampa, estado_trampa, identificador_tecnico,  TO_CHAR(fecha_modificacion,'YYYY-MM-DD HH24:MI:SS') as fecha_modificacion, observacion
											FROM
												g_administracion_trampas.historia_administracion_trampas
											WHERE
												id_administracion_trampa = $idAdministracionTrampa;");
		return $res;
	}
	
	public function modificarNuevoAdminintracionTrampas ($conexion, $idAdministracionTrampa, $estadoTrampa, $observacion, $coordenadax, $coordenaday, $coordenadaz, $idTipoAtrayente){
		
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_administracion_trampas.administracion_trampas 
											SET 
												estado_trampa = '$estadoTrampa', observacion = '$observacion',
												coordenadax = '$coordenadax', coordenaday = '$coordenaday',
												coordenadaz = '$coordenadaz', id_tipo_atrayente = $idTipoAtrayente
											WHERE 
												id_administracion_trampa = $idAdministracionTrampa;");
		return $res;
	}
	
	public function buscarEstadoObservacionTrampas ($conexion, $idAdministracionTrampa, $estadoTrampa, $observacion){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_administracion_trampas.administracion_trampas
											WHERE
												id_administracion_trampa = $idAdministracionTrampa
												and estado_trampa = '$estadoTrampa'
												and observacion = '$observacion';");
		return $res;
	}	
	
	public function generarCodigoTrampa($conexion,$codigo){
			
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(codigo_trampa) as codigo_trampa
											FROM
												g_administracion_trampas.administracion_trampas
											WHERE
												codigo_trampa LIKE '$codigo';");
		return $res;
	}
	
	public function obtenerTrampaPorCodigoTrampa ($conexion, $codigoTrampa){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_administracion_trampas.administracion_trampas
											WHERE
												codigo_trampa = '$codigoTrampa';");
		return $res;
	}
	//***********************OBTENER CATALOGOS DE TIPO ATRAYENTE*********************
	public function listarTipoAtrayente($conexion,$estado){
		$cid = $this->obtenerTipoAtrayente($conexion,$estado);
		while ($fila = pg_fetch_assoc($cid)){
			$res[] = array(id_tipo_atrayente=>$fila['id_tipo_atrayente'],nombre_tipo_atrayente=>$fila['nombre_tipo_atrayente']);
		}
		return $res;
	}
	
	public function obtenerTipoAtrayente ($conexion, $estado){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.tipo_atrayente
											WHERE
												estado_tipo_atrayente = '".$estado."';");
		return $res;
	}
	//**************************************************************************************
}


?>