<?php
 /**
 * Lógica del negocio de AreaTematicaNotificacionModelo
 *
 * Este archivo se complementa con el archivo AreaTematicaNotificacionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-03-17
 * @uses    AreaTematicaNotificacionLogicaNegocio
 * @package NotificacionesFitosanitarias
 * @subpackage Modelos
 */
  namespace Agrodb\NotificacionesFitosanitarias\Modelos;
  
  use Agrodb\NotificacionesFitosanitarias\Modelos\IModelo;
 
class AreaTematicaNotificacionLogicaNegocio implements IModelo 
{

	 private $modeloAreaTematicaNotificacion = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloAreaTematicaNotificacion = new AreaTematicaNotificacionModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new AreaTematicaNotificacionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdAreaTematicaNotificacion() != null && $tablaModelo->getIdAreaTematicaNotificacion() > 0) {
		return $this->modeloAreaTematicaNotificacion->actualizar($datosBd, $tablaModelo->getIdAreaTematicaNotificacion());
		} else {
		unset($datosBd["id_area_tematica_notificacion"]);
		return $this->modeloAreaTematicaNotificacion->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloAreaTematicaNotificacion->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return AreaTematicaNotificacionModelo
	*/
	public function buscar($id)
	{
		return $this->modeloAreaTematicaNotificacion->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloAreaTematicaNotificacion->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloAreaTematicaNotificacion->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarAreaTematicaNotificacion()
	{
	$consulta = "SELECT * FROM ".$this->modeloAreaTematicaNotificacion->getEsquema().". area_tematica_notificacion";
		 return $this->modeloAreaTematicaNotificacion->ejecutarSqlNativo($consulta);
	}
    public function devolverAreaTematicaNotificacion($idNotificacion)
	{
		$consulta = "SELECT 
						string_agg(distinct area_tematica,', ') as area_tematica 
					FROM 
						".$this->modeloAreaTematicaNotificacion->getEsquema().". area_tematica_notificacion
					WHERE 
					    id_notificacion=$idNotificacion";
		return $this->modeloAreaTematicaNotificacion->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Columnas para guardar junto con el formulario
	 *
	 * @return string[]
	 */
	public function columnas(){
	    $columnas = array(
	        'id_notificacion',
	        'area_tematica');
	    return $columnas;
	}

}
