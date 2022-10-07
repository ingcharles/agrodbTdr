<?php
/**
 * Lógica del negocio de SubtipoProcedimientoMedicoModelo
 *
 * Este archivo se complementa con el archivo SubtipoProcedimientoMedicoControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses SubtipoProcedimientoMedicoLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class SubtipoProcedimientoMedicoLogicaNegocio implements IModelo{

	private $modeloSubtipoProcedimientoMedico = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloSubtipoProcedimientoMedico = new SubtipoProcedimientoMedicoModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new SubtipoProcedimientoMedicoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdSubtipoProcedMedico() != null && $tablaModelo->getIdSubtipoProcedMedico() > 0){
			return $this->modeloSubtipoProcedimientoMedico->actualizar($datosBd, $tablaModelo->getIdSubtipoProcedMedico());
		}else{
			unset($datosBd["id_subtipo_proced_medico"]);
			return $this->modeloSubtipoProcedimientoMedico->guardar($datosBd);
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
		$this->modeloSubtipoProcedimientoMedico->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return SubtipoProcedimientoMedicoModelo
	 */
	public function buscar($id){
		return $this->modeloSubtipoProcedimientoMedico->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloSubtipoProcedimientoMedico->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloSubtipoProcedimientoMedico->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarSubtipoProcedimientoMedico(){
		$consulta = "SELECT * FROM " . $this->modeloSubtipoProcedimientoMedico->getEsquema() . ". subtipo_procedimiento_medico";
		return $this->modeloSubtipoProcedimientoMedico->ejecutarSqlNativo($consulta);
	}
}
