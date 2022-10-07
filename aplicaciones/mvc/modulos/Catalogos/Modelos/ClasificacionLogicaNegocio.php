<?php
 /**
 * Lógica del negocio de ClasificacionModelo
 *
 * Este archivo se complementa con el archivo ClasificacionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-06-23
 * @uses    ClasificacionLogicaNegocio
 * @package AdministracionCatalogosRIA
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Catalogos\Modelos\IModelo;
 
class ClasificacionLogicaNegocio implements IModelo 
{

	 private $modeloClasificacion = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloClasificacion = new ClasificacionModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new ClasificacionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdClasificacion() != null && $tablaModelo->getIdClasificacion() > 0) {
		return $this->modeloClasificacion->actualizar($datosBd, $tablaModelo->getIdClasificacion());
		} else {
		unset($datosBd["id_clasificacion"]);
		return $this->modeloClasificacion->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloClasificacion->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return ClasificacionModelo
	*/
	public function buscar($id)
	{
		return $this->modeloClasificacion->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloClasificacion->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloClasificacion->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarClasificacion()
	{
	$consulta = "SELECT * FROM ".$this->modeloClasificacion->getEsquema().". clasificacion";
		 return $this->modeloClasificacion->ejecutarSqlNativo($consulta);
	}

}
