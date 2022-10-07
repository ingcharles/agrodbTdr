<?php
/**
 * Lógica del negocio de PeriodoSubsanacionModelo
 *
 * Este archivo se complementa con el archivo PeriodoSubsanacionControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-07-13
 * @uses PeriodoSubsanacionLogicaNegocio
 * @package ProveedoresExterior
 * @subpackage Modelos
 */
namespace Agrodb\ProveedoresExterior\Modelos;

use Agrodb\ProveedoresExterior\Modelos\IModelo;

class PeriodoSubsanacionLogicaNegocio implements IModelo{

	private $modeloPeriodoSubsanacion = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloPeriodoSubsanacion = new PeriodoSubsanacionModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new PeriodoSubsanacionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdPeriodoSubsanacion() != null && $tablaModelo->getIdPeriodoSubsanacion() > 0){
			return $this->modeloPeriodoSubsanacion->actualizar($datosBd, $tablaModelo->getIdPeriodoSubsanacion());
		}else{
			unset($datosBd["id_periodo_subsanacion"]);
			return $this->modeloPeriodoSubsanacion->guardar($datosBd);
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
		$this->modeloPeriodoSubsanacion->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return PeriodoSubsanacionModelo
	 */
	public function buscar($id){
		return $this->modeloPeriodoSubsanacion->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloPeriodoSubsanacion->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloPeriodoSubsanacion->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarPeriodoSubsanacion(){
		$consulta = "SELECT * FROM " . $this->modeloPeriodoSubsanacion->getEsquema() . ". periodo_subsanacion WHERE estado_periodo_subsanacion = 'activo'";
		return $this->modeloPeriodoSubsanacion->ejecutarSqlNativo($consulta);
	}
}
