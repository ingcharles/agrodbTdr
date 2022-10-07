<?php
/**
 * Lógica del negocio de AdjuntosConsultaMedicaModelo
 *
 * Este archivo se complementa con el archivo AdjuntosConsultaMedicaControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses AdjuntosConsultaMedicaLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class AdjuntosConsultaMedicaLogicaNegocio implements IModelo{

	private $modeloAdjuntosConsultaMedica = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloAdjuntosConsultaMedica = new AdjuntosConsultaMedicaModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new AdjuntosConsultaMedicaModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdAdjuntosConsultaMedica() != null && $tablaModelo->getIdAdjuntosConsultaMedica() > 0){
			return $this->modeloAdjuntosConsultaMedica->actualizar($datosBd, $tablaModelo->getIdAdjuntosConsultaMedica());
		}else{
			unset($datosBd["id_adjuntos_consulta_medica"]);
			return $this->modeloAdjuntosConsultaMedica->guardar($datosBd);
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
		$this->modeloAdjuntosConsultaMedica->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return AdjuntosConsultaMedicaModelo
	 */
	public function buscar($id){
		return $this->modeloAdjuntosConsultaMedica->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloAdjuntosConsultaMedica->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloAdjuntosConsultaMedica->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarAdjuntosConsultaMedica(){
		$consulta = "SELECT * FROM " . $this->modeloAdjuntosConsultaMedica->getEsquema() . ". adjuntos_consulta_medica";
		return $this->modeloAdjuntosConsultaMedica->ejecutarSqlNativo($consulta);
	}
}
