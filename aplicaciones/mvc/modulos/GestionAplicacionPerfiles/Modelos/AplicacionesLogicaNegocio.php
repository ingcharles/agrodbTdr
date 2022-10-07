<?php
 /**
 * Lógica del negocio de AplicacionesModelo
 *
 * Este archivo se complementa con el archivo AplicacionesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-03-20
 * @uses    AplicacionesLogicaNegocio
 * @package GestionAplicacionPerfiles
 * @subpackage Modelos
 */
  namespace Agrodb\GestionAplicacionPerfiles\Modelos;
  
  use Agrodb\GestionAplicacionPerfiles\Modelos\IModelo;
 
class AplicacionesLogicaNegocio implements IModelo 
{

	 private $modeloAplicaciones = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloAplicaciones = new AplicacionesModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new AplicacionesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdAplicacion() != null && $tablaModelo->getIdAplicacion() > 0) {
		return $this->modeloAplicaciones->actualizar($datosBd, $tablaModelo->getIdAplicacion());
		} else {
		unset($datosBd["id_aplicacion"]);
		return $this->modeloAplicaciones->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloAplicaciones->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return AplicacionesModelo
	*/
	public function buscar($id)
	{
		return $this->modeloAplicaciones->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloAplicaciones->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloAplicaciones->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarAplicaciones()
	{
	$consulta = "SELECT * FROM ".$this->modeloAplicaciones->getEsquema().". aplicaciones";
		 return $this->modeloAplicaciones->ejecutarSqlNativo($consulta);
	}

}
