<?php
/**
 * Lógica del negocio de AccidentesLaboralesModelo
 *
 * Este archivo se complementa con el archivo AccidentesLaboralesControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses AccidentesLaboralesLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class AccidentesLaboralesLogicaNegocio implements IModelo{

	private $modeloAccidentesLaborales = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloAccidentesLaborales = new AccidentesLaboralesModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new AccidentesLaboralesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdAccidentesLaborales() != null && $tablaModelo->getIdAccidentesLaborales() > 0){
			return $this->modeloAccidentesLaborales->actualizar($datosBd, $tablaModelo->getIdAccidentesLaborales());
		}else{
			unset($datosBd["id_accidentes_laborales"]);
			return $this->modeloAccidentesLaborales->guardar($datosBd);
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
		$this->modeloAccidentesLaborales->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return AccidentesLaboralesModelo
	 */
	public function buscar($id){
		return $this->modeloAccidentesLaborales->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloAccidentesLaborales->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloAccidentesLaborales->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarAccidentesLaborales(){
		$consulta = "SELECT * FROM " . $this->modeloAccidentesLaborales->getEsquema() . ". accidentes_laborales";
		return $this->modeloAccidentesLaborales->ejecutarSqlNativo($consulta);
	}
}
