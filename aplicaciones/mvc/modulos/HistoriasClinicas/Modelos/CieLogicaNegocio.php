<?php
/**
 * Lógica del negocio de Cie10Modelo
 *
 * Este archivo se complementa con el archivo Cie10Controlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses Cie10LogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class CieLogicaNegocio implements IModelo{

	private $modeloCie = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloCie = new CieModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new CieModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdCie() != null && $tablaModelo->getIdCie() > 0){
			return $this->modeloCie->actualizar($datosBd, $tablaModelo->getIdCie());
		}else{
			unset($datosBd["id_cie"]);
			return $this->modeloCie->guardar($datosBd);
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
		$this->modeloCie10->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return Cie10Modelo
	 */
	public function buscar($id){
		return $this->modeloCie->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloCie->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloCie->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarCie(){
		$consulta = "SELECT * FROM " . $this->modeloCie->getEsquema() . ". cie";
		return $this->modeloCie->ejecutarSqlNativo($consulta);
	}
}
