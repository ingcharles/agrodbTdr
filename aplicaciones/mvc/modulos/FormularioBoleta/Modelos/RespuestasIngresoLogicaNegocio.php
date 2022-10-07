<?php
 /**
 * Lógica del negocio de RespuestasIngresoModelo
 *
 * Este archivo se complementa con el archivo RespuestasIngresoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-08-14
 * @uses    RespuestasIngresoLogicaNegocio
 * @package FormularioBoleta
 * @subpackage Modelos
 */
  namespace Agrodb\FormularioBoleta\Modelos;
  
 
class RespuestasIngresoLogicaNegocio implements IModelo 
{

	 private $modeloRespuestasIngreso = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloRespuestasIngreso = new RespuestasIngresoModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new RespuestasIngresoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdRespuestasIngreso() != null && $tablaModelo->getIdRespuestasIngreso() > 0) {
		return $this->modeloRespuestasIngreso->actualizar($datosBd, $tablaModelo->getIdRespuestasIngreso());
		} else {
		unset($datosBd["id_respuestas_ingreso"]);
		return $this->modeloRespuestasIngreso->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloRespuestasIngreso->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return RespuestasIngresoModelo
	*/
	public function buscar($id)
	{
		return $this->modeloRespuestasIngreso->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloRespuestasIngreso->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloRespuestasIngreso->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarRespuestasIngreso()
	{
	$consulta = "SELECT * FROM ".$this->modeloRespuestasIngreso->getEsquema().". respuestas_ingreso";
		 return $this->modeloRespuestasIngreso->ejecutarSqlNativo($consulta);
	}
	/**
	 * Columnas para guardar junto con el formulario
	 * @return string[]
	 */
	public function columnas()
	{
	    $columnas = array(
	        'id_preguntas_ingreso',
	        'id_datos_ingreso',
	        'respuesta',
	        'num_hombres',
	        'num_mujeres'
	    );
	    return $columnas;
	}
	
}
