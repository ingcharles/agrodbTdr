<?php
/**
 * Lógica del negocio de EvaluacionPrimariaModelo
 *
 * Este archivo se complementa con el archivo EvaluacionPrimariaControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses EvaluacionPrimariaLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class EvaluacionPrimariaLogicaNegocio implements IModelo{

	private $modeloEvaluacionPrimaria = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloEvaluacionPrimaria = new EvaluacionPrimariaModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new EvaluacionPrimariaModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdEvaluacionPrimaria() != null && $tablaModelo->getIdEvaluacionPrimaria() > 0){
			return $this->modeloEvaluacionPrimaria->actualizar($datosBd, $tablaModelo->getIdEvaluacionPrimaria());
		}else{
			unset($datosBd["id_evaluacion_primaria"]);
			return $this->modeloEvaluacionPrimaria->guardar($datosBd);
		}
	}

	/**
	 * Borra el registro actual
	 *
	 * @param
	 *        	string Where|array $where
	 * @return int
	 */
	public function borrar($id){
		$this->modeloEvaluacionPrimaria->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return EvaluacionPrimariaModelo
	 */
	public function buscar($id){
		return $this->modeloEvaluacionPrimaria->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloEvaluacionPrimaria->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloEvaluacionPrimaria->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarEvaluacionPrimaria(){
		$consulta = "SELECT * FROM " . $this->modeloEvaluacionPrimaria->getEsquema() . ". evaluacion_primaria";
		return $this->modeloEvaluacionPrimaria->ejecutarSqlNativo($consulta);
	}

	/**
	 * Columnas para guardar junto con el formulario
	 *
	 * @return string[]
	 */
	public function columnas(){
		$columnas = array(
			'id_historia_clinica',
			'id_procedimiento_medico',
			'id_tipo_procedimiento_medico');
		return $columnas;
	}
}
