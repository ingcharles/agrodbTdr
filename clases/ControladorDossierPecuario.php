<?php

class ControladorDossierPecuario
{
    //ok
    public function abrirSolicitud ($conexion, $idSolicitud){
        
        $res = $conexion->ejecutarConsulta("SELECT
										      *
											FROM
												g_dossier_pecuario_mvc.solicitud s
                                                INNER JOIN g_operadores.operadores o ON s.identificador = o.identificador
                                                INNER JOIN g_catalogos.subtipo_productos sp ON sp.id_subtipo_producto = s.id_subtipo_producto
                                                INNER JOIN g_catalogos.clasificacion c ON c.id_clasificacion = s.id_clasificacion
											WHERE
												s.id_solicitud =  $idSolicitud;");
        return $res;
    }

    //ok
    public function actualizarEstadoSolicitud($conexion, $estado, $idSolicitud, $identificador, $idProvincia, $mensaje){

        $res = $conexion->ejecutarConsulta("UPDATE
                                                g_dossier_pecuario_mvc.solicitud
                                            SET
                                                estado_solicitud = '$estado',
                                                fecha_revision = now(),
                                                fase_revision = 'Pago',
                                                identificador_revisor = '$identificador',
                                                id_provincia_revision = $idProvincia,
                                                observacion_revision = '$mensaje'
                                            WHERE
                                                id_solicitud = $idSolicitud;");
        
        return $res; 
    }
    
    //ok
    public function ingresarHistoricoEstados($conexion, $estado, $idSolicitud, $identificador, $mensaje){
        
        $nombre = $_SESSION['datosUsuario'];
        $idProv = $_SESSION['idProvincia'];
        $provincia= $_SESSION['nombreProvincia'];
        
        $res = $conexion->ejecutarConsulta("INSERT INTO 
                                                    g_dossier_pecuario_mvc.secuencia_revision(
                                                        id_solicitud, fecha_creacion, identificador_ejecutor, 
                                                        nombre_ejecutor, perfil, id_provincia, provincia, estado_revision, 
                                                        accion)
                                            VALUES ($idSolicitud, now(), '$identificador', '$nombre', 
                                                    'Financiero', $idProv, '$provincia', '$estado', 
                                                    '$mensaje');");
        return $res;
        
    }
    
    //ok
    public function obtenerSolicitudPorEstadoProvincia ($conexion, $estado, $provincia){
        
        $consulta = "SELECT
						dp.id_solicitud as id_solicitud,
						dp.fecha_creacion as fecha_registro,
						dp.id_expediente as numero_solicitud,
						dp.identificador as identificador_operador
					 FROM
						g_dossier_pecuario_mvc.solicitud dp
                        INNER JOIN g_operadores.operadores o ON o.identificador = dp.identificador 
					 WHERE
						dp.estado_solicitud = '$estado'
						and upper(o.provincia) = upper('$provincia');";
        
        $res = $conexion->ejecutarConsulta($consulta);
        
        return $res;
    }
}