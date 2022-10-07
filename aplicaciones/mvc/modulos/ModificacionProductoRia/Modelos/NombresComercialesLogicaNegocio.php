<?php
 /**
 * Lógica del negocio de NombresComercialesModelo
 *
 * Este archivo se complementa con el archivo NombresComercialesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-21
 * @uses    NombresComercialesLogicaNegocio
 * @package ModificacionProductoRia
 * @subpackage Modelos
 */
  namespace Agrodb\ModificacionProductoRia\Modelos;
  
  use Agrodb\ModificacionProductoRia\Modelos\IModelo;
 
class NombresComercialesLogicaNegocio implements IModelo 
{

	 private $modeloNombresComerciales = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloNombresComerciales = new NombresComercialesModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new NombresComercialesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdNombreComercial() != null && $tablaModelo->getIdNombreComercial() > 0) {
		return $this->modeloNombresComerciales->actualizar($datosBd, $tablaModelo->getIdNombreComercial());
		} else {
		unset($datosBd["id_nombre_comercial"]);
		return $this->modeloNombresComerciales->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloNombresComerciales->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return NombresComercialesModelo
	*/
	public function buscar($id)
	{
		return $this->modeloNombresComerciales->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloNombresComerciales->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloNombresComerciales->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarNombresComerciales()
	{
	$consulta = "SELECT * FROM ".$this->modeloNombresComerciales->getEsquema().". nombres_comerciales";
		 return $this->modeloNombresComerciales->ejecutarSqlNativo($consulta);
	}

}
