<?php

class ControladorAuditoria{

	function guardarLog($conexion,$tipo_aplicacion){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_auditoria.log(
            											id_aplicacion)
										    VALUES ('$tipo_aplicacion') returning id_log;");
		return $res;
	}
	
	
	function guardarTransaccion($conexion,$idSolicitud,$idLog){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_auditoria.transaccion(
														transaccion_log,id_log)
											VALUES ('$idSolicitud','$idLog') returning id_transaccion;");
		return $res;
	}	
	
	
	function guardarInsert($conexion,$idTransaccion,$identificador,$accion){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_auditoria.insert(
														id_transaccion,identificador,fecha,accion)
											VALUES ('$idTransaccion','".$identificador."',now(),'$accion') ;");
		return $res;
	}
	
	
	function buscarTransaccion($conexion,$id_solicitud,$idAplicacion){
		
		switch($idAplicacion){
			case 'Documentos' :
				$aplicacion = 'l.id_aplicacion in (1,4) and ';
				break;
				
			default:
				$aplicacion = 'l.id_aplicacion = ' . $idAplicacion . ' and ';
		}
		
		$res = $conexion->ejecutarConsulta("SELECT 
													t.*	
											FROM 
													g_auditoria.transaccion t,
													g_auditoria.log l
											WHERE
													$aplicacion 
													l.id_log = t.id_log and
													t.transaccion_log = $id_solicitud;");
		return $res;
	}
	
	
	public function condicionSolicitud ($conexion,$idSolicitud){
	
		$res = $conexion->ejecutarConsulta("SELECT
													*
											FROM
													g_solicitudes.solicitudes
											WHERE
													id_solicitud = ".$idSolicitud.";");
	
		return $res;
	}
	
	
	
	
	function guardarUpdate($conexion,$idTransaccion,$identificador,$accion){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_auditoria.update(
														id_transaccion,identificador,fecha,accion)
												VALUES ('$idTransaccion','$identificador',now(),'$accion');");
		return $res;
	}
	
	function guardarEliminar($conexion,$idTransaccion,$identificador,$accion){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_auditoria.delete(
				id_transaccion,identificador,fecha,accion)
				VALUES ('$idTransaccion','$identificador',now(),'$accion');");
		return $res;
	}
	
	
	function listaHistorial($conexion,$idTransaccion, $idAplicacion, $dOrden=null, $dLimite=null){
		$orden = 'asc';
		$limite = '';
		
		switch($idAplicacion){
			case 'Documentos' :
				$aplicacion = 'l.id_aplicacion in (1,4) and ';
				break;
				
			default:
				$aplicacion = 'l.id_aplicacion = ' . $idAplicacion . ' and ';
		}
		
		switch($dOrden){
			case 'DESC' :
				$orden = 'desc';
				break;
				
			default:
				$orden = 'asc';
		}
		
		if ($dLimite != null){
			$limite = ' LIMIT '. $dLimite;
		}
		
		$res = $conexion->ejecutarConsulta("select 
													t.id_transaccion, 
													t.transaccion_log,
													fecha, 
													accion
												from
													g_auditoria.log l,
													g_auditoria.transaccion t,
													g_auditoria.insert i
												where
													$aplicacion
													l.id_log = t.id_log and
													t.id_transaccion = i.id_transaccion and
													t.transaccion_log = $idTransaccion
													
												union
												
												select 
													t.id_transaccion, 
													t.transaccion_log,
													fecha, 
													accion
												from
													g_auditoria.log l,
													g_auditoria.transaccion t,
													g_auditoria.update u
												where
													$aplicacion
													l.id_log = t.id_log and
													t.id_transaccion = u.id_transaccion and
													t.transaccion_log = $idTransaccion
												
												union
												
												select 
													t.id_transaccion, 
													t.transaccion_log,
													fecha, 
													accion
												from
													g_auditoria.log l,
													g_auditoria.transaccion t,
													g_auditoria.delete d
												where
													$aplicacion
													l.id_log = t.id_log and
													t.id_transaccion = d.id_transaccion and
													t.transaccion_log = $idTransaccion
												
												order by fecha $orden 
												$limite;");
		
		return $res;
	}
	
	function buscarUltimoAcceso($conexion, $identificador, $tipo = "EXITO"){
	    
		
		$res = $conexion->ejecutarConsulta("SELECT 
                                            	max(fecha_inicio) as ultimo_acceso
                                            FROM 
                                            	g_auditoria.ingreso 
                                            WHERE 
                                            	identificador = '$identificador' 
                                            	and tipo = '$tipo'
                                            	and id_ingreso not in (SELECT 
                                                                            max(id_ingreso) 
                                                                        FROM 
                                                                            g_auditoria.ingreso 
                                                                        WHERE 
                                                                            identificador = '$identificador' 
                                                                             and tipo = '$tipo')");
		if(pg_fetch_result($res, 0, 'ultimo_acceso') == NULL){
		    
		    $res = $conexion->ejecutarConsulta("SELECT
                                            	max(fecha_inicio) as ultimo_acceso
                                            FROM
                                            	g_auditoria.ingreso
                                            WHERE
                                            	identificador = '$identificador'
                                            	and tipo = '$tipo'");
		    
		}
		
		
				return $res;
	}
	
	function guardarIngresoUsuario($conexion,$idLog, $identificador, $accion, $tipo, $ipAcceso){
	    
		$conexion->ejecutarConsulta("INSERT INTO g_auditoria.ingreso(
													id_log,identificador,fecha_inicio,accion, tipo, ip_acceso)
											VALUES ($idLog, '$identificador', now(), '$accion', '$tipo', '$ipAcceso');");
		
		$res = $conexion->ejecutarConsulta("SELECT max(id_ingreso) as id_ingreso FROM g_auditoria.ingreso WHERE identificador = '$identificador'");
		
		return $res;
	}
	
	function buscarAuditoriaXMiembrAsociacion($conexion, $identificadorMiembro, $nombreMiembro, $fechaInicio, $fechaFin){
	
		$identificadorMiembro = $identificadorMiembro != "" ? "'" . $identificadorMiembro . "'" : "NULL";
		$nombreMiembro = $nombreMiembro != "" ? "'%" . $nombreMiembro . "%'" : "NULL";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . " 00:00:00'"  : "NULL";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . " 24:00:00'"  : "NULL";
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_operadores.auditoria_asociacion
											WHERE
												($identificadorMiembro is NULL or identificador_miembro_asociacion = $identificadorMiembro)
												and ($nombreMiembro is NULL or nombre_miembro_asociacion ||' '|| apellido_miembro_asociacion ilike $nombreMiembro)
												and ($fechaInicio is NULL or fecha_registro >= $fechaInicio)
												and ($fechaFin is NULL or fecha_registro <= $fechaFin)
											ORDER BY
												fecha_registro desc");
				return $res;
	
	}
	
	function actualizarAuditoriaXIdMiembroASociacion($conexion,$idMiembroAsociacion){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_operadores.auditoria_asociacion
											SET
												estado_auditoria = 'inactivo'
											WHERE
												id_miembro_asociacion = $idMiembroAsociacion;");
	
			return $res;
				
	}
	
	function guardarAuditoriaAsociacion($conexion,$idMiembroAsociacion, $codigoMiembro, $identificadorMiembro, $usuario, $nombre, $apellido, $codigoMagap, $idOperacion, $idArea, $idSitio, $rendimiento, $descripcionAuditoria, $estadoAuditoria){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_operadores.auditoria_asociacion(
													id_miembro_asociacion, codigo_miembro_asociacion, identificador_miembro_asociacion, identificador_asociacion, nombre_miembro_asociacion, apellido_miembro_asociacion, codigo_magap, id_operacion, id_area, id_sitio, rendimiento, detalle_auditoria, estado_auditoria)
												VALUES ($idMiembroAsociacion, '$codigoMiembro', '$identificadorMiembro', '$usuario', '$nombre', '$apellido', '$codigoMagap', $idOperacion, $idArea, $idSitio, $rendimiento, '$descripcionAuditoria', '$estadoAuditoria');");
		return $res;
	}
	
	public function verificarEstadoUsuario($conexion, $identificador, $tipoExito = 'EXITO', $tipoError = 'ERROR', $tipo = 'EXITO'){
	    
	    if($tipo == 'EXITO'){
	        $consulta = "select
                    	count(id_ingreso) cantidad
                    from
                    	g_auditoria.ingreso
                    where
                    	tipo = '$tipoError'
                    	and identificador = '$identificador'
                    	and id_ingreso > (select
                    				max(id_ingreso)
                    			from
                    				g_auditoria.ingreso
                    			where
                    				tipo = '$tipoExito'
                    				and identificador = '$identificador')";
	    }else{
	        $consulta = "select
                    	   count(id_ingreso) cantidad
                    from
                    	g_auditoria.ingreso
                    where
                    	tipo = '$tipoError'
                    	and identificador = '$identificador'";
	    }
	       
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return pg_fetch_result($res, 0, 'cantidad');
	}
	
	
	public function actualizarIntentoAccesoUsuario($conexion, $intento, $idIngreso, $identificador){
	    
	    $consulta = "UPDATE g_auditoria.ingreso SET intento = $intento WHERE identificador = '$identificador' and id_ingreso = $idIngreso;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	    
	    
	}
	
	public function buscarLogIngresoUsuario($conexion, $identificador, $idAplicacion = 0){
	    
	    $consulta = "SELECT 
                    	distinct l.id_log
                    FROM 
                    	g_auditoria.log l INNER JOIN g_auditoria.ingreso i ON l.id_log = i.id_log
                    WHERE
                    	i.identificador = '$identificador' and
                    	l.id_aplicacion = $idAplicacion";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	    
	}
	
}
