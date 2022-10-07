<?php
 /**
 * Lógica del negocio de CatastroPredioEquidosHistorialPatologiasModelo
 *
 * Este archivo se complementa con el archivo CatastroPredioEquidosHistorialPatologiasControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-02-16
 * @uses    CatastroPredioEquidosHistorialPatologiasLogicaNegocio
 * @package ProgramasControlOficial
 * @subpackage Modelos
 */
  namespace Agrodb\ProgramasControlOficial\Modelos;
  
  use Agrodb\ProgramasControlOficial\Modelos\IModelo;
 
class CatastroPredioEquidosHistorialPatologiasLogicaNegocio implements IModelo 
{

	 private $modeloCatastroPredioEquidosHistorialPatologias = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloCatastroPredioEquidosHistorialPatologias = new CatastroPredioEquidosHistorialPatologiasModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new CatastroPredioEquidosHistorialPatologiasModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdCatastroPredioEquidosHistorialPatologias() != null && $tablaModelo->getIdCatastroPredioEquidosHistorialPatologias() > 0) {
		return $this->modeloCatastroPredioEquidosHistorialPatologias->actualizar($datosBd, $tablaModelo->getIdCatastroPredioEquidosHistorialPatologias());
		} else {
		unset($datosBd["id_catastro_predio_equidos_historial_patologias"]);
		return $this->modeloCatastroPredioEquidosHistorialPatologias->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloCatastroPredioEquidosHistorialPatologias->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return CatastroPredioEquidosHistorialPatologiasModelo
	*/
	public function buscar($id)
	{
		return $this->modeloCatastroPredioEquidosHistorialPatologias->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloCatastroPredioEquidosHistorialPatologias->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloCatastroPredioEquidosHistorialPatologias->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarCatastroPredioEquidosHistorialPatologias()
	{
	$consulta = "SELECT * FROM ".$this->modeloCatastroPredioEquidosHistorialPatologias->getEsquema().". catastro_predio_equidos_historial_patologias";
		 return $this->modeloCatastroPredioEquidosHistorialPatologias->ejecutarSqlNativo($consulta);
	}

}
