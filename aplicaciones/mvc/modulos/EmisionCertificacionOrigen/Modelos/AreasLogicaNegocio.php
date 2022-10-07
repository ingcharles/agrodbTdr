<?php
 /**
 * Lógica del negocio de AreasModelo
 *
 * Este archivo se complementa con el archivo AreasControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    AreasLogicaNegocio
 * @package EmisionCertificacionOrigen
 * @subpackage Modelos
 */
  namespace Agrodb\EmisionCertificacionOrigen\Modelos;
  
  use Agrodb\EmisionCertificacionOrigen\Modelos\IModelo;
 
class AreasLogicaNegocio implements IModelo 
{

	 private $modeloAreas = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloAreas = new AreasModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new AreasModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdArea() != null && $tablaModelo->getIdArea() > 0) {
		return $this->modeloAreas->actualizar($datosBd, $tablaModelo->getIdArea());
		} else {
		unset($datosBd["id_area"]);
		return $this->modeloAreas->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloAreas->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return AreasModelo
	*/
	public function buscar($id)
	{
		return $this->modeloAreas->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloAreas->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloAreas->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarAreas()
	{
	$consulta = "SELECT * FROM ".$this->modeloAreas->getEsquema().". areas";
		 return $this->modeloAreas->ejecutarSqlNativo($consulta);
	}

}
