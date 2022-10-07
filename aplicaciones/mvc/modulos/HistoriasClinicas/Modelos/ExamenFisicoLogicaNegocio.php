<?php
/**
 * Lógica del negocio de ExamenFisicoModelo
 *
 * Este archivo se complementa con el archivo ExamenFisicoControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses ExamenFisicoLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class ExamenFisicoLogicaNegocio implements IModelo{

	private $modeloExamenFisico = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloExamenFisico = new ExamenFisicoModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new ExamenFisicoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdExamenFisico() != null && $tablaModelo->getIdExamenFisico() > 0){
			return $this->modeloExamenFisico->actualizar($datosBd, $tablaModelo->getIdExamenFisico());
		}else{
			unset($datosBd["id_examen_fisico"]);
			return $this->modeloExamenFisico->guardar($datosBd);
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
		$this->modeloExamenFisico->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return ExamenFisicoModelo
	 */
	public function buscar($id){
		return $this->modeloExamenFisico->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloExamenFisico->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloExamenFisico->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarExamenFisico(){
		$consulta = "SELECT * FROM " . $this->modeloExamenFisico->getEsquema() . ". examen_fisico";
		return $this->modeloExamenFisico->ejecutarSqlNativo($consulta);
	}

	/**
	 * Columnas para guardar junto con el formulario
	 *
	 * @return string[]
	 */
	public function columnas(){
		$columnas = array(
			'id_historia_clinica',
			'tension_arterial',
			'saturacion_oxigeno',
			'frecuencia_cardiaca',
			'frecuencia_respiratoria',
			'talla_mts',
			'temperatura_c',
			'peso_kg',
			'imc',
			'interpretacion_imc');
		return $columnas;
	}

	/**
	 * Columnas para guardar junto con el formulario
	 *
	 * @return string[]
	 */
	public function columnas1(){
		$columnas = array(
			'id_consulta_medica',
			'tension_arterial',
			'saturacion_oxigeno',
			'frecuencia_cardiaca',
			'frecuencia_respiratoria',
			'talla_mts',
			'temperatura_c',
			'peso_kg',
			'imc',
			'interpretacion_imc');
		return $columnas;
	}
}
