<?php
/**
 * Lógica del negocio de InmunizacionModelo
 *
 * Este archivo se complementa con el archivo InmunizacionControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses InmunizacionLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class InmunizacionLogicaNegocio implements IModelo{

	private $modeloInmunizacion = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloInmunizacion = new InmunizacionModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new InmunizacionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdInmunizacion() != null && $tablaModelo->getIdInmunizacion() > 0){
			return $this->modeloInmunizacion->actualizar($datosBd, $tablaModelo->getIdInmunizacion());
		}else{
			unset($datosBd["id_inmunizacion"]);
			return $this->modeloInmunizacion->guardar($datosBd);
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
		$this->modeloInmunizacion->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return InmunizacionModelo
	 */
	public function buscar($id){
		return $this->modeloInmunizacion->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloInmunizacion->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloInmunizacion->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarInmunizacion(){
		$consulta = "SELECT * FROM " . $this->modeloInmunizacion->getEsquema() . ". inmunizacion";
		return $this->modeloInmunizacion->ejecutarSqlNativo($consulta);
	}
}
