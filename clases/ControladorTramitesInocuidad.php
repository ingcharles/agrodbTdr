<?php

class ControladorTramitesInocuidad {

   public function listarTramitesDisponibles($conexion, $estado) {
   	
        $res = $conexion->ejecutarConsulta("SELECT
                                                id_tramite as id_solicitud, 
        										identificador_operador, 
        										fecha_solicitud, 
        										id_producto, 
       											nombre_producto, 
        										id_tipo_tramite, 
        										nombre_tipo_tramite, 
        										estado, 
       											observacion
											FROM 
												g_tramites_inocuidad.tramites
        									WHERE
        										estado IN ($estado);");
        return $res;
        
    }
    
    public function buscarSecuencialNumeroTramite($conexion) {
    	
    	$res = $conexion->ejecutarConsulta("SELECT 
												(count(id_tramite)+1) as numero
											FROM 
												g_tramites_inocuidad.tramites
											WHERE 
												date_part('year',fecha_solicitud) = date_part('year',now());");
    	return $res;
    	
    }  
    
    public function guardarTramite($conexion,$numeroTramite, $identificadorOperador, $idProducto, $nombreProducto, $idTipoTramite, $nombreTipoTramite, $estado, $observacion) {
    	 
    	$res = $conexion->ejecutarConsulta("INSERT INTO 
    												g_tramites_inocuidad.tramites (id_tramite, identificador_operador, fecha_solicitud, id_producto, nombre_producto, id_tipo_tramite, nombre_tipo_tramite, estado, observacion)
    										VALUES ($numeroTramite,'$identificadorOperador', now(), $idProducto, '$nombreProducto', $idTipoTramite, '$nombreTipoTramite', '$estado', '$observacion') returning id_tramite;");
    	return $res;
    	 
    }
    
    public function guardarSeguimientoTramite($conexion,$numeroTramite, $identificador, $fechaDespacho, $observacion = null) {
    
    	$res = $conexion->ejecutarConsulta("INSERT INTO
    												g_tramites_inocuidad.seguimiento_tramites(id_tramite, identificador, fecha_despacho, observacion) 
    										VALUES ($numeroTramite,'$identificador', '$fechaDespacho', '$observacion') returning id_seguimiento_tramite;");
    			return $res;
    
    }
    
    public function obtenerTramiteInocuidad($conexion, $idTramite) {
    
    	$res = $conexion->ejecutarConsulta("SELECT
								    			*
								    		FROM
								    			g_tramites_inocuidad.tramites
								    		WHERE
								    			id_tramite = $idTramite;");
    	return $res;
    
    }
    
    public function actualizarDocumentosFalsosTramite($conexion, $idTramite, $documentoFalso) {
    
    	$res = $conexion->ejecutarConsulta("UPDATE
								    			g_tramites_inocuidad.tramites
								    		SET
								    			documento_falso = '$documentoFalso'
								    			WHERE
								    			id_tramite = $idTramite;");
    	return $res;
    
    }
    
    public function actualizarEstadoTramite($conexion, $idTramite, $estado) {
    
    	$res = $conexion->ejecutarConsulta("UPDATE
								    			g_tramites_inocuidad.tramites
								    		SET
								    			estado = '$estado'
								    		WHERE
								    			id_tramite = $idTramite;");
    	return $res;
    
    }
    
    public function listarTramitesAsignadasInspectorRS ($conexion, $estado, $identificadorInspector, $tipoSolicitud, $tipoInspector){
    
    	$res = $conexion->ejecutarConsulta("SELECT
								    			distinct t.id_tramite as id_solicitud,
												t.identificador_operador,
												t.estado
								    		FROM
								    			g_tramites_inocuidad.tramites t,
								    			g_revision_solicitudes.asignacion_coordinador ac
								    		WHERE
								    			t.id_tramite = ac.id_solicitud and
								    			ac.tipo_solicitud = '$tipoSolicitud' and
								    			ac.tipo_inspector = '$tipoInspector' and
								    			ac.identificador_inspector = '$identificadorInspector' and
								    			t.estado in ('$estado')
								    			order by 1 asc;");
    			return $res;
    }
    
    public function actualizarNumeroOficioTramite($conexion, $idTramite, $numeroOficio) {
    
    	$res = $conexion->ejecutarConsulta("UPDATE
							    				g_tramites_inocuidad.tramites
							    			SET
							    				numero_oficio = '$numeroOficio'
							    			WHERE
							    				id_tramite = $idTramite;");
    	return $res;
    
    }
}
