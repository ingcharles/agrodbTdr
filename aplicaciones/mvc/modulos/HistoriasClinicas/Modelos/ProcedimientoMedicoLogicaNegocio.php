<?php
/**
 * Lógica del negocio de ProcedimientoMedicoModelo
 *
 * Este archivo se complementa con el archivo ProcedimientoMedicoControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses ProcedimientoMedicoLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class ProcedimientoMedicoLogicaNegocio implements IModelo{

	private $modeloProcedimientoMedico = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloProcedimientoMedico = new ProcedimientoMedicoModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new ProcedimientoMedicoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdProcedimientoMedico() != null && $tablaModelo->getIdProcedimientoMedico() > 0){
			return $this->modeloProcedimientoMedico->actualizar($datosBd, $tablaModelo->getIdProcedimientoMedico());
		}else{
			unset($datosBd["id_procedimiento_medico"]);
			return $this->modeloProcedimientoMedico->guardar($datosBd);
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
		$this->modeloProcedimientoMedico->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return ProcedimientoMedicoModelo
	 */
	public function buscar($id){
		return $this->modeloProcedimientoMedico->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloProcedimientoMedico->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloProcedimientoMedico->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarProcedimientoMedico(){
		$consulta = "SELECT * FROM " . $this->modeloProcedimientoMedico->getEsquema() . ". procedimiento_medico";
		return $this->modeloProcedimientoMedico->ejecutarSqlNativo($consulta);
	}
}
