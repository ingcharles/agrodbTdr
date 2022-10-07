<?php
/**
 * Lógica del negocio de DetalleExamenesClinicosModelo
 *
 * Este archivo se complementa con el archivo DetalleExamenesClinicosControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses DetalleExamenesClinicosLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class DetalleExamenesClinicosLogicaNegocio implements IModelo{

	private $modeloDetalleExamenesClinicos = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloDetalleExamenesClinicos = new DetalleExamenesClinicosModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new DetalleExamenesClinicosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetalleExamenesClinicos() != null && $tablaModelo->getIdDetalleExamenesClinicos() > 0){
			return $this->modeloDetalleExamenesClinicos->actualizar($datosBd, $tablaModelo->getIdDetalleExamenesClinicos());
		}else{
			unset($datosBd["id_detalle_examenes_clinicos"]);
			return $this->modeloDetalleExamenesClinicos->guardar($datosBd);
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
		$this->modeloDetalleExamenesClinicos->borrar($id);
	}

	public function borrarPorParametro($param, $value){
		$this->modeloDetalleExamenesClinicos->borrarPorParametro($param, $value);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return DetalleExamenesClinicosModelo
	 */
	public function buscar($id){
		return $this->modeloDetalleExamenesClinicos->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloDetalleExamenesClinicos->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloDetalleExamenesClinicos->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarDetalleExamenesClinicos(){
		$consulta = "SELECT * FROM " . $this->modeloDetalleExamenesClinicos->getEsquema() . ". detalle_examenes_clinicos";
		return $this->modeloDetalleExamenesClinicos->ejecutarSqlNativo($consulta);
	}

	public function columnas(){
		$columnas = array(
			'id_examenes_clinicos',
			'id_subtipo_proced_medico',
			'estado_clinico',
			'observaciones');
		return $columnas;
	}
}
