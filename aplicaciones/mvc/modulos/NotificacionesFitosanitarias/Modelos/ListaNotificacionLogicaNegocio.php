<?php
 /**
 * Lógica del negocio de ListaNotificacionModelo
 *
 * Este archivo se complementa con el archivo ListaNotificacionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-09-09
 * @uses    ListaNotificacionLogicaNegocio
 * @package NotificacionesFitosanitarias
 * @subpackage Modelos
 */
namespace Agrodb\NotificacionesFitosanitarias\Modelos;
  
use Agrodb\NotificacionesFitosanitarias\Modelos\IModelo;
 
 
class ListaNotificacionLogicaNegocio implements IModelo 
{

	 private $modeloListaNotificacion = null;
       


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloListaNotificacion = new ListaNotificacionModelo();
       
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new ListaNotificacionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdListaNotificacion() != null && $tablaModelo->getIdListaNotificacion() > 0) {
		return $this->modeloListaNotificacion->actualizar($datosBd, $tablaModelo->getIdListaNotificacion());
		} else {
		unset($datosBd["id_lista_notificacion"]);
		return $this->modeloListaNotificacion->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloListaNotificacion->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return ListaNotificacionModelo
	*/
	public function buscar($id)
	{
		return $this->modeloListaNotificacion->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloListaNotificacion->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloListaNotificacion->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarListaNotificacion()
	{
	$consulta = "SELECT * FROM ".$this->modeloListaNotificacion->getEsquema().". lista_notificacion";
		 return $this->modeloListaNotificacion->ejecutarSqlNativo($consulta);
	}
        
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
		
                return $this->modeloListaNotificacion->ejecutarSqlNativo($sql);
	}
        
        public function obtenerAccionesPermitidas($idOpcion, $idUsuario) {
        $consulta = "select a.id_accion,
                    a.pagina,a.estilo,a.descripcion,apl.ruta from g_programas.acciones a,
                    g_programas.acciones_perfiles ap,g_usuario.usuarios_perfiles up,g_programas.aplicaciones apl
                    where up.identificador = '" . $idUsuario . "' and up.id_perfil = ap.id_perfil and
                    ap.id_accion = a.id_accion and  a.id_aplicacion = apl.id_aplicacion and
                    a.id_opcion = " . $idOpcion . " order by a.orden;";
                return $this->modelo->ejecutarConsulta($consulta);
        }
        
        public function buscarPaisxCodigo($idLocalizacion){
		$sql = "SELECT
					id_localizacion, nombre
			  FROM
					g_catalogos.localizacion					
					
			  WHERE
					id_localizacion = $idLocalizacion";
		
                return $this->modeloListaNotificacion->ejecutarSqlNativo($sql);
	}
        
     
    
     
    
        /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar notificaciones usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarListaNotificacionesFiltro($id)
    {
        
        $consulta = " SELECT DISTINCT lno.* 
                        FROM 
                        g_notificaciones_fitosanitarias.lista_notificacion lno
                        WHERE lno.id_lista_notificacion = ". "$id";
         
        return $this->modeloListaNotificacion->ejecutarSqlNativo($consulta);
        
    }
    
    public function buscarAnioNotificaciones($comentadas=null)
	{
    	if ($comentadas != ''){
    		$consulta = " SELECT distinct anio FROM g_notificaciones_fitosanitarias.notificaciones n inner join g_notificaciones_fitosanitarias.lista_notificacion ln
							on n.id_lista_notificacion = ln.id_lista_notificacion inner join g_notificaciones_fitosanitarias.respuesta_notificacion rn on 
							rn.id_notificacion = n.id_notificacion  group by anio order by anio desc; "; 
    	}else{
                    $consulta = " SELECT DISTINCT anio 
                        FROM 
                        g_notificaciones_fitosanitarias.lista_notificacion
                        group by anio order by anio desc; "; 
    	}
         
        return $this->modeloListaNotificacion->ejecutarSqlNativo($consulta);
	}
	
	public function buscarAnioNotificacionesBusqueda($query)
	{
	    $consulta = " SELECT DISTINCT anio
                        FROM
                        g_notificaciones_fitosanitarias.lista_notificacion
                        WHERE ".$query."
                        group by anio; ";
	    
	    return $this->modeloListaNotificacion->ejecutarSqlNativo($consulta);
	}
}
