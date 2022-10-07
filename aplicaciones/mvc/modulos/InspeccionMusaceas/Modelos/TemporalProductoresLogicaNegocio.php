<?php
 /**
 * Lógica del negocio de TemporalProductoresModelo
 *
 * Este archivo se complementa con el archivo TemporalProductoresControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    TemporalProductoresLogicaNegocio
 * @package InspeccionMusaceas
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionMusaceas\Modelos;
  
  use Agrodb\InspeccionMusaceas\Modelos\IModelo;
 
class TemporalProductoresLogicaNegocio implements IModelo 
{

	 private $modeloTemporalProductores = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloTemporalProductores = new TemporalProductoresModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new TemporalProductoresModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdTemporalProductores() != null && $tablaModelo->getIdTemporalProductores() > 0) {
		return $this->modeloTemporalProductores->actualizar($datosBd, $tablaModelo->getIdTemporalProductores());
		} else {
		unset($datosBd["id_temporal_productores"]);
		return $this->modeloTemporalProductores->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloTemporalProductores->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return TemporalProductoresModelo
	*/
	public function buscar($id)
	{
		return $this->modeloTemporalProductores->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloTemporalProductores->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloTemporalProductores->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarTemporalProductores()
	{
	$consulta = "SELECT * FROM ".$this->modeloTemporalProductores->getEsquema().". temporal_productores";
		 return $this->modeloTemporalProductores->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function borrarTemporalProductores($identificador)
	{
	    $consulta = "DELETE FROM g_inspeccion_musaceas.temporal_productores WHERE identificador='".$identificador."';";
	    return $this->modeloTemporalProductores->ejecutarSqlNativo($consulta);
	}

}
