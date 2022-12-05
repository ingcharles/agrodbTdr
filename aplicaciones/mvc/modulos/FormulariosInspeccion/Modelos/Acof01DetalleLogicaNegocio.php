<?php
 /**
 * Lógica del negocio de Acof01DetalleModelo
 *
 * Este archivo se complementa con el archivo Acof01DetalleControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-04-05
 * @uses    Acof01DetalleLogicaNegocio
 * @package FormulariosInspeccion
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\FormulariosInspeccion\Modelos\IModelo;
 
class Acof01DetalleLogicaNegocio implements IModelo 
{

	 private $modeloAcof01Detalle = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloAcof01Detalle = new Acof01DetalleModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new Acof01DetalleModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getId() != null && $tablaModelo->getId() > 0) {
		return $this->modeloAcof01Detalle->actualizar($datosBd, $tablaModelo->getId());
		} else {
		unset($datosBd["id"]);
		return $this->modeloAcof01Detalle->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloAcof01Detalle->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return Acof01DetalleModelo
	*/
	public function buscar($id)
	{
		return $this->modeloAcof01Detalle->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloAcof01Detalle->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloAcof01Detalle->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarAcof01Detalle()
	{
	$consulta = "SELECT * FROM ".$this->modeloAcof01Detalle->getEsquema().". acof01_detalle";
		 return $this->modeloAcof01Detalle->ejecutarSqlNativo($consulta);
	}

}
