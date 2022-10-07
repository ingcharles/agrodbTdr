<?php
 /**
 * Lógica del negocio de RespuestaNotificacionModelo
 *
 * Este archivo se complementa con el archivo RespuestaNotificacionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-09-09
 * @uses    RespuestaNotificacionLogicaNegocio
 * @package NotificacionesFitosanitarias
 * @subpackage Modelos
 */
  namespace Agrodb\NotificacionesFitosanitarias\Modelos;
  
  use Agrodb\NotificacionesFitosanitarias\Modelos\IModelo;
 
class RespuestaNotificacionLogicaNegocio implements IModelo 
{

	 private $modeloRespuestaNotificacion = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloRespuestaNotificacion = new RespuestaNotificacionModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new RespuestaNotificacionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdRespuestaNotificacion() != null && $tablaModelo->getIdRespuestaNotificacion() > 0) {
		return $this->modeloRespuestaNotificacion->actualizar($datosBd, $tablaModelo->getIdRespuestaNotificacion());
		} else {
		unset($datosBd["id_respuesta_notificacion"]);
		return $this->modeloRespuestaNotificacion->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloRespuestaNotificacion->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return RespuestaNotificacionModelo
	*/
	public function buscar($id)
	{
		return $this->modeloRespuestaNotificacion->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloRespuestaNotificacion->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloRespuestaNotificacion->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarRespuestaNotificacion()
	{
	$consulta = "SELECT * FROM ".$this->modeloRespuestaNotificacion->getEsquema().". respuesta_notificacion";
		 return $this->modeloRespuestaNotificacion->ejecutarSqlNativo($consulta);
	}
        /**
	 * VERIFICAR TIPO DE PERFIL DEL OPERADOR
	 */
	public function verificarPerfil($identificadorOperador){
		$sql = "SELECT
					p.nombre, p.codificacion_perfil
			  FROM
					g_usuario.usuarios_perfiles up
					INNER JOIN g_usuario.perfiles p ON up.id_perfil = p.id_perfil
					INNER JOIN g_programas.aplicaciones ap ON ap.id_aplicacion = p.id_aplicacion
			  WHERE
					identificador in ('" . $identificadorOperador . "') AND
					ap.codificacion_aplicacion='PGR_NOTI_OMC';";
		return $this->modeloRespuestaNotificacion->ejecutarSqlNativo($sql);
	}
        
        /**
	 * Obtener registros por operador
	 */
	public function buscarRegistrosXOperador($arrayParametros){  
            $busqueda = '';
            if (isset($arrayParametros['idNotificacion']) && ($arrayParametros['idNotificacion'] != '')) {
                $busqueda .= " id_notificacion = '" . $arrayParametros['idNotificacion'] . "'";
            }
            if (isset($arrayParametros['identificador']) && ($arrayParametros['identificador'] != '')) {
                $busqueda .= " and identificador = '". $arrayParametros['identificador']."' ";
            }
            if (isset($arrayParametros['idRespuestaNotificacion']) && ($arrayParametros['idRespuestaNotificacion'] != '')) {
                $busqueda .= " and id_respuesta_notificacion = '". $arrayParametros['idRespuestaNotificacion']."' ";
            }
            $consulta = "with recursive path(id_respuesta_notificacion,id_notificacion,identificador,id_padre,tipo,respuesta,archivo,fecha_revision,fecha_respuesta,estado_respuesta,finalizar_respuesta) as 
            (select id_respuesta_notificacion,id_notificacion,identificador,id_padre,tipo,respuesta,archivo,fecha_revision,fecha_respuesta,estado_respuesta,finalizar_respuesta
             from g_notificaciones_fitosanitarias.respuesta_notificacion 
             where " . $busqueda . "
             union
             select r.id_respuesta_notificacion,r.id_notificacion,r.identificador,r.id_padre,r.tipo,r.respuesta,r.archivo,r.fecha_revision,r.fecha_respuesta,r.estado_respuesta,r.finalizar_respuesta
             from g_notificaciones_fitosanitarias.respuesta_notificacion as r, path as rs
             where r.id_padre = rs.id_respuesta_notificacion) 
             SELECT * FROM path ORDER BY id_respuesta_notificacion ASC; ";
                
            return $this->modeloRespuestaNotificacion->ejecutarSqlNativo($consulta);
        }
        
         /**
	 * Obtener registros por operador que realizan preguntas a tecnicos
	 */
	public function buscarRegistrosXOperadorRespuesta($idProceso){  
            $busqueda = '';
            $busqueda .= " and n.id_notificacion = rn.id_notificacion
                            and rn.tipo = 'operador' 
                            and rn.estado_respuesta = 'false'
                            and rn.finalizar_respuesta = 'false'
                            and now() <= n.fecha_cierre ";
            $consulta = " select distinct n.* from g_notificaciones_fitosanitarias.notificaciones n, 
                            g_notificaciones_fitosanitarias.respuesta_notificacion rn
                            where n.id_lista_notificacion =  '" . $idProceso . "'" . $busqueda . "; ";
            
            return $this->modeloRespuestaNotificacion->ejecutarSqlNativo($consulta);
            
        }
        
        public function buscarRegistrosXCampo($arrayParametros) {
            $busqueda = '';
            if (isset($arrayParametros) && ($arrayParametros != '')) {
                //$busqueda .= " id_respuesta_notificacion = '" . $arrayParametros['idResNotificacion'] . "' ";
                $busqueda .= " id_respuesta_notificacion = '" . $arrayParametros . "' ";
            }
            $consulta = "with recursive path(id_respuesta_notificacion,id_notificacion,identificador,id_padre,tipo,respuesta,archivo,fecha_revision,fecha_respuesta,estado_respuesta) as
            (select id_respuesta_notificacion,id_notificacion,identificador,id_padre,tipo,respuesta,archivo,fecha_revision,fecha_respuesta,estado_respuesta
             from g_notificaciones_fitosanitarias.respuesta_notificacion
             where " . $busqueda . "
              union
             select r.id_respuesta_notificacion,r.id_notificacion,r.identificador,r.id_padre,r.tipo,r.respuesta,r.archivo,r.fecha_revision,r.fecha_respuesta,r.estado_respuesta
             from g_notificaciones_fitosanitarias.respuesta_notificacion as r, path as rs
             where r.id_padre = rs.id_respuesta_notificacion)
             SELECT * FROM path ORDER BY id_respuesta_notificacion ASC; ";
            
            return $this->modeloRespuestaNotificacion->ejecutarSqlNativo($consulta);
            
        }
        
        public function buscarInformacionTecnico($identificador){
            $sql = "SELECT 
                        fe.nombre ||' '|| fe.apellido as funcionario, nombre_puesto as puesto, c.estado
                        FROM g_uath.ficha_empleado fe INNER JOIN g_uath.datos_contrato c
                        on fe.identificador = c.identificador 
                        WHERE fe.identificador='".$identificador."' ORDER BY 3 ASC LIMIT 1 ;";
            return $this->modeloRespuestaNotificacion->ejecutarSqlNativo($sql);
        }
}
