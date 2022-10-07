<?php
 /**
 * Lógica del negocio de ExamenPostHallazgosModelo
 *
 * Este archivo se complementa con el archivo ExamenPostHallazgosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    ExamenPostHallazgosLogicaNegocio
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
 
class ExamenPostHallazgosLogicaNegocio implements IModelo 
{

	 private $modeloExamenPostHallazgos = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloExamenPostHallazgos = new ExamenPostHallazgosModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new ExamenPostHallazgosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdExamenPostHallazgos() != null && $tablaModelo->getIdExamenPostHallazgos() > 0) {
		return $this->modeloExamenPostHallazgos->actualizar($datosBd, $tablaModelo->getIdExamenPostHallazgos());
		} else {
		unset($datosBd["id_examen_post_hallazgos"]);
		return $this->modeloExamenPostHallazgos->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloExamenPostHallazgos->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return ExamenPostHallazgosModelo
	*/
	public function buscar($id)
	{
		return $this->modeloExamenPostHallazgos->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloExamenPostHallazgos->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloExamenPostHallazgos->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarExamenPostHallazgos()
	{
	$consulta = "SELECT * FROM ".$this->modeloExamenPostHallazgos->getEsquema().". examen_post_hallazgos";
		 return $this->modeloExamenPostHallazgos->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Columnas para guardar junto con el formulario
	 * @return string[]
	 */
	public function columnas()
	{
		$columnas = array(
			'id_detalle_post_animales',
			'enfermedad',
			'localizacion',
			'num_animales_afectados'
		);
		return $columnas;
	}

}
