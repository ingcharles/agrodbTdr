<?php
/**
 * Lógica del negocio de AntecedentesSaludFamiliarModelo
 *
 * Este archivo se complementa con el archivo AntecedentesSaludFamiliarControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses AntecedentesSaludFamiliarLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class AntecedentesSaludFamiliarLogicaNegocio implements IModelo{

	private $modeloAntecedentesSaludFamiliar = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloAntecedentesSaludFamiliar = new AntecedentesSaludFamiliarModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new AntecedentesSaludFamiliarModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdAntecedSaludFamiliar() != null && $tablaModelo->getIdAntecedSaludFamiliar() > 0){
			return $this->modeloAntecedentesSaludFamiliar->actualizar($datosBd, $tablaModelo->getIdAntecedSaludFamiliar());
		}else{
			unset($datosBd["id_anteced_salud_familiar"]);
			return $this->modeloAntecedentesSaludFamiliar->guardar($datosBd);
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
		$this->modeloAntecedentesSaludFamiliar->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return AntecedentesSaludFamiliarModelo
	 */
	public function buscar($id){
		return $this->modeloAntecedentesSaludFamiliar->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloAntecedentesSaludFamiliar->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloAntecedentesSaludFamiliar->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarAntecedentesSaludFamiliar(){
		$consulta = "SELECT * FROM " . $this->modeloAntecedentesSaludFamiliar->getEsquema() . ". antecedentes_salud_familiar";
		return $this->modeloAntecedentesSaludFamiliar->ejecutarSqlNativo($consulta);
	}
}
