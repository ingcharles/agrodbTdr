<?php
/**
 * Lógica del negocio de IngresoAplicacionModelo
 *
 * Este archivo se complementa con el archivo IngresoAplicacionControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-07-01
 * @uses IngresoAplicacionLogicaNegocio
 * @package Auditoria
 * @subpackage Modelos
 */
namespace Agrodb\Auditoria\Modelos;

use Agrodb\Auditoria\Modelos\IModelo;

class IngresoAplicacionLogicaNegocio implements IModelo{

	private $modeloIngresoAplicacion = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloIngresoAplicacion = new IngresoAplicacionModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new IngresoAplicacionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdIngresoAplicacion() != null && $tablaModelo->getIdIngresoAplicacion() > 0){
			return $this->modeloIngresoAplicacion->actualizar($datosBd, $tablaModelo->getIdIngresoAplicacion());
		}else{
			unset($datosBd["id_ingreso_aplicacion"]);
			return $this->modeloIngresoAplicacion->guardar($datosBd);
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
		$this->modeloIngresoAplicacion->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return IngresoAplicacionModelo
	 */
	public function buscar($id){
		return $this->modeloIngresoAplicacion->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloIngresoAplicacion->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloIngresoAplicacion->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarIngresoAplicacion(){
		$consulta = "SELECT * FROM " . $this->modeloIngresoAplicacion->getEsquema() . ". ingreso_aplicacion";
		return $this->modeloIngresoAplicacion->ejecutarSqlNativo($consulta);
	}
}
