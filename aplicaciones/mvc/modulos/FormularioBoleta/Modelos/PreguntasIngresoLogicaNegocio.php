<?php
 /**
 * Lógica del negocio de PreguntasIngresoModelo
 *
 * Este archivo se complementa con el archivo PreguntasIngresoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-08-14
 * @uses    PreguntasIngresoLogicaNegocio
 * @package FormularioBoleta
 * @subpackage Modelos
 */
  namespace Agrodb\FormularioBoleta\Modelos;
  
 
class PreguntasIngresoLogicaNegocio implements IModelo 
{

	 private $modeloPreguntasIngreso = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloPreguntasIngreso = new PreguntasIngresoModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new PreguntasIngresoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdPreguntasIngreso() != null && $tablaModelo->getIdPreguntasIngreso() > 0) {
		return $this->modeloPreguntasIngreso->actualizar($datosBd, $tablaModelo->getIdPreguntasIngreso());
		} else {
		unset($datosBd["id_preguntas_ingreso"]);
		return $this->modeloPreguntasIngreso->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloPreguntasIngreso->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return PreguntasIngresoModelo
	*/
	public function buscar($id)
	{
		return $this->modeloPreguntasIngreso->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloPreguntasIngreso->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloPreguntasIngreso->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarPreguntasIngreso()
	{
	$consulta = "SELECT * FROM ".$this->modeloPreguntasIngreso->getEsquema().". preguntas_ingreso";
		 return $this->modeloPreguntasIngreso->ejecutarSqlNativo($consulta);
	}

}
