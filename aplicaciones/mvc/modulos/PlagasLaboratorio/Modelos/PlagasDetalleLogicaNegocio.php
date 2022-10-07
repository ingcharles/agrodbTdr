<?php
/**
 * Lógica del negocio de PlagasDetalleModelo
 *
 * Este archivo se complementa con el archivo PlagasDetalleControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-03-24
 * @uses PlagasDetalleLogicaNegocio
 * @package PlagasLaboratorio
 * @subpackage Modelos
 */
namespace Agrodb\PlagasLaboratorio\Modelos;

use Agrodb\PlagasLaboratorio\Modelos\IModelo;

class PlagasDetalleLogicaNegocio implements IModelo{

	private $modeloPlagasDetalle = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloPlagasDetalle = new PlagasDetalleModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new PlagasDetalleModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdPlagaDetalle() != null && $tablaModelo->getIdPlagaDetalle() > 0){
			return $this->modeloPlagasDetalle->actualizar($datosBd, $tablaModelo->getIdPlagaDetalle());
		}else{
			unset($datosBd["id_plaga_detalle"]);
			return $this->modeloPlagasDetalle->guardar($datosBd);
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
		$this->modeloPlagasDetalle->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return PlagasDetalleModelo
	 */
	public function buscar($id){
		return $this->modeloPlagasDetalle->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloPlagasDetalle->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloPlagasDetalle->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarPlagasDetalle(){
		$consulta = "SELECT * FROM " . $this->modeloPlagasDetalle->getEsquema() . ". plagas_detalle";
		return $this->modeloPlagasDetalle->ejecutarSqlNativo($consulta);
	}
}
