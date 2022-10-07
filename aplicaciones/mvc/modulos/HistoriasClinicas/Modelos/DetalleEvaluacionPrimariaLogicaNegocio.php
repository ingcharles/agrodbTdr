<?php
/**
 * Lógica del negocio de DetalleEvaluacionPrimariaModelo
 *
 * Este archivo se complementa con el archivo DetalleEvaluacionPrimariaControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses DetalleEvaluacionPrimariaLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class DetalleEvaluacionPrimariaLogicaNegocio implements IModelo{

	private $modeloDetalleEvaluacionPrimaria = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloDetalleEvaluacionPrimaria = new DetalleEvaluacionPrimariaModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new DetalleEvaluacionPrimariaModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetalleEvalPrimaria() != null && $tablaModelo->getIdDetalleEvalPrimaria() > 0){
			return $this->modeloDetalleEvaluacionPrimaria->actualizar($datosBd, $tablaModelo->getIdDetalleEvalPrimaria());
		}else{
			unset($datosBd["id_detalle_eval_primaria"]);
			return $this->modeloDetalleEvaluacionPrimaria->guardar($datosBd);
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
		$this->modeloDetalleEvaluacionPrimaria->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return DetalleEvaluacionPrimariaModelo
	 */
	public function buscar($id){
		return $this->modeloDetalleEvaluacionPrimaria->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloDetalleEvaluacionPrimaria->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloDetalleEvaluacionPrimaria->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarDetalleEvaluacionPrimaria(){
		$consulta = "SELECT * FROM " . $this->modeloDetalleEvaluacionPrimaria->getEsquema() . ". detalle_evaluacion_primaria";
		return $this->modeloDetalleEvaluacionPrimaria->ejecutarSqlNativo($consulta);
	}

	/**
	 * Columnas para guardar junto con el formulario
	 *
	 * @return string[]
	 */
	public function columnas(){
		$columnas = array(
			'id_evaluacion_primaria',
			'id_subtipo_proced_medico',
			'normal',
			'observaciones');
		return $columnas;
	}
}
