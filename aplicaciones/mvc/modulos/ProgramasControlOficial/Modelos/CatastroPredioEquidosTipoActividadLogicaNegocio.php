<?php
 /**
 * Lógica del negocio de CatastroPredioEquidosTipoActividadModelo
 *
 * Este archivo se complementa con el archivo CatastroPredioEquidosTipoActividadControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-02-16
 * @uses    CatastroPredioEquidosTipoActividadLogicaNegocio
 * @package ProgramasControlOficial
 * @subpackage Modelos
 */
  namespace Agrodb\ProgramasControlOficial\Modelos;
  
  use Agrodb\ProgramasControlOficial\Modelos\IModelo;
 
class CatastroPredioEquidosTipoActividadLogicaNegocio implements IModelo 
{

	 private $modeloCatastroPredioEquidosTipoActividad = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloCatastroPredioEquidosTipoActividad = new CatastroPredioEquidosTipoActividadModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new CatastroPredioEquidosTipoActividadModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdCatastroPredioEquidosTipoActividad() != null && $tablaModelo->getIdCatastroPredioEquidosTipoActividad() > 0) {
		return $this->modeloCatastroPredioEquidosTipoActividad->actualizar($datosBd, $tablaModelo->getIdCatastroPredioEquidosTipoActividad());
		} else {
		unset($datosBd["id_catastro_predio_equidos_tipo_actividad"]);
		return $this->modeloCatastroPredioEquidosTipoActividad->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloCatastroPredioEquidosTipoActividad->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return CatastroPredioEquidosTipoActividadModelo
	*/
	public function buscar($id)
	{
		return $this->modeloCatastroPredioEquidosTipoActividad->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloCatastroPredioEquidosTipoActividad->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloCatastroPredioEquidosTipoActividad->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarCatastroPredioEquidosTipoActividad()
	{
	$consulta = "SELECT * FROM ".$this->modeloCatastroPredioEquidosTipoActividad->getEsquema().". catastro_predio_equidos_tipo_actividad";
		 return $this->modeloCatastroPredioEquidosTipoActividad->ejecutarSqlNativo($consulta);
	}

}
