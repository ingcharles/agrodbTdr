<?php
 /**
 * Lógica del negocio de NotificacionPorPaisAfectadoModelo
 *
 * Este archivo se complementa con el archivo NotificacionPorPaisAfectadoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-09-09
 * @uses    NotificacionPorPaisAfectadoLogicaNegocio
 * @package NotificacionesFitosanitarias
 * @subpackage Modelos
 */
  namespace Agrodb\NotificacionesFitosanitarias\Modelos;
  
  use Agrodb\NotificacionesFitosanitarias\Modelos\IModelo;
 
class NotificacionPorPaisAfectadoLogicaNegocio implements IModelo 
{

	 private $modeloNotificacionPorPaisAfectado = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloNotificacionPorPaisAfectado = new NotificacionPorPaisAfectadoModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new NotificacionPorPaisAfectadoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdNotificacionPorProducto() != null && $tablaModelo->getIdNotificacionPorProducto() > 0) {
		return $this->modeloNotificacionPorPaisAfectado->actualizar($datosBd, $tablaModelo->getIdNotificacionPorProducto());
		} else {
		unset($datosBd["id_notificacion_por_producto"]);
		return $this->modeloNotificacionPorPaisAfectado->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloNotificacionPorPaisAfectado->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return NotificacionPorPaisAfectadoModelo
	*/
	public function buscar($id)
	{
		return $this->modeloNotificacionPorPaisAfectado->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloNotificacionPorPaisAfectado->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloNotificacionPorPaisAfectado->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarNotificacionPorPaisAfectado()
	{
	$consulta = "SELECT * FROM ".$this->modeloNotificacionPorPaisAfectado->getEsquema().". notificacion_por_pais_afectado";
		 return $this->modeloNotificacionPorPaisAfectado->ejecutarSqlNativo($consulta);
	}
        
        public function columnas(){
		$columnas = array(
			'id_notificacion',
			'id_localizacion',
                        'nombre_pais'
		);
		return $columnas;
	}
}
