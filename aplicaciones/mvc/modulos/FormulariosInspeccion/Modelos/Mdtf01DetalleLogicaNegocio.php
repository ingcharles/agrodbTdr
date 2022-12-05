<?php
 /**
 * Lógica del negocio de Mdtf01DetalleModelo
 *
 * Este archivo se complementa con el archivo Mdtf01DetalleControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-09-23
 * @uses    Mdtf01DetalleLogicaNegocio
 * @package AplicacionMovilBPA
 * @subpackage Modelos
 */
namespace Agrodb\FormulariosInspeccion\Modelos;
  
use Agrodb\FormulariosInspeccion\Modelos\IModelo;
 
class Mdtf01DetalleLogicaNegocio implements IModelo 
{

	 private $modeloMdtf01Detalle = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloMdtf01Detalle = new Mdtf01DetalleModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new Mdtf01DetalleModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getId() != null && $tablaModelo->getId() > 0) {
		return $this->modeloMdtf01Detalle->actualizar($datosBd, $tablaModelo->getId());
		} else {
		unset($datosBd["id"]);
		return $this->modeloMdtf01Detalle->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloMdtf01Detalle->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return Mdtf01DetalleModelo
	*/
	public function buscar($id)
	{
		return $this->modeloMdtf01Detalle->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloMdtf01Detalle->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloMdtf01Detalle->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarMdtf01Detalle()
	{
	$consulta = "SELECT * FROM ".$this->modeloMdtf01Detalle->getEsquema().". mdtf01_detalle";
		 return $this->modeloMdtf01Detalle->ejecutarSqlNativo($consulta);
	}

}
