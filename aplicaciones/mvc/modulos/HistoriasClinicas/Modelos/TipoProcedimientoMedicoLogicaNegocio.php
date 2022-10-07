<?php
/**
 * Lógica del negocio de TipoProcedimientoMedicoModelo
 *
 * Este archivo se complementa con el archivo TipoProcedimientoMedicoControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses TipoProcedimientoMedicoLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class TipoProcedimientoMedicoLogicaNegocio implements IModelo{

	private $modeloTipoProcedimientoMedico = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloTipoProcedimientoMedico = new TipoProcedimientoMedicoModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new TipoProcedimientoMedicoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdTipoProcedimientoMedico() != null && $tablaModelo->getIdTipoProcedimientoMedico() > 0){
			return $this->modeloTipoProcedimientoMedico->actualizar($datosBd, $tablaModelo->getIdTipoProcedimientoMedico());
		}else{
			unset($datosBd["id_tipo_procedimiento_medico"]);
			return $this->modeloTipoProcedimientoMedico->guardar($datosBd);
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
		$this->modeloTipoProcedimientoMedico->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return TipoProcedimientoMedicoModelo
	 */
	public function buscar($id){
		return $this->modeloTipoProcedimientoMedico->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloTipoProcedimientoMedico->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloTipoProcedimientoMedico->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarTipoProcedimientoMedico(){
		$consulta = "SELECT * FROM " . $this->modeloTipoProcedimientoMedico->getEsquema() . ". tipo_procedimiento_medico";
		return $this->modeloTipoProcedimientoMedico->ejecutarSqlNativo($consulta);
	}
}
