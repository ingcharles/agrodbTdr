<?php
/**
 * Lógica del negocio de AdjuntosHistoriaClinicaModelo
 *
 * Este archivo se complementa con el archivo AdjuntosHistoriaClinicaControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses AdjuntosHistoriaClinicaLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class AdjuntosHistoriaClinicaLogicaNegocio implements IModelo{

	private $modeloAdjuntosHistoriaClinica = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloAdjuntosHistoriaClinica = new AdjuntosHistoriaClinicaModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new AdjuntosHistoriaClinicaModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdAdjuntosHistoriaClinica() != null && $tablaModelo->getIdAdjuntosHistoriaClinica() > 0){
			return $this->modeloAdjuntosHistoriaClinica->actualizar($datosBd, $tablaModelo->getIdAdjuntosHistoriaClinica());
		}else{
			unset($datosBd["id_adjuntos_historia_clinica"]);
			return $this->modeloAdjuntosHistoriaClinica->guardar($datosBd);
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
		$this->modeloAdjuntosHistoriaClinica->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return AdjuntosHistoriaClinicaModelo
	 */
	public function buscar($id){
		return $this->modeloAdjuntosHistoriaClinica->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloAdjuntosHistoriaClinica->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloAdjuntosHistoriaClinica->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarAdjuntosHistoriaClinica(){
		$consulta = "SELECT * FROM " . $this->modeloAdjuntosHistoriaClinica->getEsquema() . ". adjuntos_historia_clinica";
		return $this->modeloAdjuntosHistoriaClinica->ejecutarSqlNativo($consulta);
	}
}
