<?php

    class ControladorTablets{

        public function obtenerRegistrosTabletsPorEstado($conexion, $estadoTablet, $estadoGuia){
        	
            $res = $conexion->ejecutarConsulta("SELECT
            										*
                                                FROM 
            										t_inspeccion.inspeccion
            									WHERE
            										estado = '$estadoTablet' 
            										and estado_guia = '$estadoGuia'
            									ORDER BY 1,2,3;");
            return $res;
        }
        
        public function obtenerRegistrosTabletsGrupo($conexion, $idInspeccion, $identificadorTablet, $versionBD, $serial){
        	
        	$cid = $conexion->ejecutarConsulta("SELECT
								        			*
								        		FROM
								        			t_inspeccion.grupo
								        		WHERE
								        			id_inspeccion = '$idInspeccion'
								        			and identificador_tablet = '$identificadorTablet'
        											and version_bd = '$versionBD'
        											and serial = $serial;");
        	
        	$res = array();
        	
        	while ($fila = pg_fetch_assoc($cid)){
        		$res[] = array(idGrupo=>$fila['id_grupo'],idInspeccion=>$fila['id_inspeccion'],idOperacion=>$fila['id_operacion'],identificadorTablet=>$fila['identificador_tablet'],versionBD=>$fila['version_bd'],fechaIngreso=>$fila['fecha_ingreso'],token=>$fila['token'], serial=>$fila['serial']);
        	}
        	
        	
        	
        	return $res;
        }
        
        public function obtenerRegistrosTabletsObservaciones($conexion, $idInspeccion, $identificadorTablet, $versionBD, $serial){
        	       	 
        	$res = $conexion->ejecutarConsulta("SELECT
								        			*
								        		FROM
								        			t_inspeccion.observacion
								        		WHERE
								        			id_inspeccion = '$idInspeccion'
								        			and identificador_tablet = '$identificadorTablet'
								        			and version_bd = '$versionBD'
        											and serial = $serial;");
        	return $res;
        }
        
        public function actualizarEstadoInspeccionTablet($conexion, $idInspeccion, $identificadorTablet, $versionBD, $serial, $estado){
        	 
        	$res = $conexion->ejecutarConsulta("UPDATE
        											t_inspeccion.inspeccion
        										SET
        											estado_guia = '$estado'
        										WHERE
        											id_inspeccion = '$idInspeccion'
								        			and identificador_tablet = '$identificadorTablet'
								        			and version_bd = '$versionBD'
        											and serial = $serial;");
        	return $res;
        }
        
        //----------------------------------------------------------ADMINISTRACIÓN DE TRAMPAS----------------------------------------------------------------
        
        public function obtenerRegistrosTrampasMosca($conexion, $estadoTablet, $estadoGuia){
        	
        	$consulta = "SELECT
            					codigo_trampa, id, estado_trampa, fecha_inspeccion, usuario_id
                         FROM 
            					f_inspeccion.moscaf01_detalle_trampas
            			WHERE
            					estado_registro = '$estadoTablet' 
            					and estado_guia = '$estadoGuia';";
        	
        	$res = $conexion->ejecutarConsulta($consulta);
        	
        	return $res;
        }
        
        public function actualizarEstadoTrampasMosca($conexion, $idRegistroTrampa, $estado){
        
        	$consulta = "UPDATE
							f_inspeccion.moscaf01_detalle_trampas
						SET
							estado_guia = '$estado'
						WHERE
							id = '$idRegistroTrampa';";
        	
        	$res = $conexion->ejecutarConsulta($consulta);
        	
        	return $res;
        }
        
        //----------------------------------------------------------INSPECCION PRODUCTOS IMPORTACION DDA ----------------------------------------------------------------
        
        public function obtenerRegistrosInspeccionImportacion($conexion, $estadoGuia){
        	 
        	$consulta = "SELECT
        					id, dda,pfi,dictamen_final, observaciones, usuario_id, fecha_inspeccion, 
        					seguimiento_cuarentenario, provincia, peso_ingreso, tablet_id, tablet_version_base
			        	FROM
			        		f_inspeccion.controlf01
			        	WHERE
			        		estado_guia = '$estadoGuia';";
        	 
        	$res = $conexion->ejecutarConsulta($consulta);
        	 
        	return $res;
        }
        
        public function obtenerRegistrosInspeccionProductosImportacion($conexion, $idRegistro){
        
        	$consulta = "SELECT
			        		nombre, cantidad_declarada, cantidad_ingresada, unidad
			        	FROM
			        		f_inspeccion.controlf01_detalle_productos_ingresados
			        	WHERE
			        		id_padre = '$idRegistro';";
        
        	$res = $conexion->ejecutarConsulta($consulta);
        
        	return $res;
        }
        
        public function actualizarEstadoInspeccionImportacion($conexion, $idRegistro, $estado){
        
        	$consulta = "UPDATE
        					f_inspeccion.controlf01
        				SET
        					estado_guia = '$estado'
        				WHERE
        					id = '$idRegistro';";
        
        	$res = $conexion->ejecutarConsulta($consulta);
        
        	return $res;
        }
        
        
        //----------------------------------------------------------SEGUIMINETO CUARNTENARIO----------------------------------------------------------------
        
        public function actualizarEstadoSeguiminetoCuarentenario($conexion, $idRegistro, $estado){
        	
        	$consulta = "UPDATE
        					f_inspeccion.controlf04
        				SET
        					estado_guia = '$estado'
        				WHERE
        					id = '$idRegistro';";
        	
        	$res = $conexion->ejecutarConsulta($consulta);
        	
        	return $res;
        	
        }
        
        public function obtenerRegistrosSeguiminetoCuarentenario($conexion, $estadoGuia){
        	
        	$consulta = "SELECT 
        					id, id_seguimiento_cuarentenario, fecha_inspeccion, resultado_inspeccion, observaciones
        				FROM
				        	f_inspeccion.controlf04
				        WHERE
				        	estado_guia = '$estadoGuia';";
        	 
        	$res = $conexion->ejecutarConsulta($consulta);
        	 
        	return $res;
        	
        }
        
        public function ingresarSolicitudesXatenderGUIA($conexion, $numeroFormulario,$codigoProcesamiento,$codigoVerificacion,$idVUE, $estado, $observacion = null){
                
        	$res = $conexion->ejecutarConsulta("INSERT INTO g_vue.solicitudes_atender(formulario, codigo_procesamiento, codigo_verificacion, solicitud, estado, observacion)
        			VALUES ('$numeroFormulario', '$codigoProcesamiento', '$codigoVerificacion', '$idVUE','$estado', '$observacion');");
        	
        	return $res;
        
        }
        
    }