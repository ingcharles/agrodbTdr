<?php
/**
 * Lógica del negocio de ValoracionConsultaMedicaModelo
 *
 * Este archivo se complementa con el archivo ValoracionConsultaMedicaControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses ValoracionConsultaMedicaLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class ValoracionConsultaMedicaLogicaNegocio implements IModelo{

	private $modeloValoracionConsultaMedica = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloValoracionConsultaMedica = new ValoracionConsultaMedicaModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new ValoracionConsultaMedicaModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdValoracionConsultaMedica() != null && $tablaModelo->getIdValoracionConsultaMedica() > 0){
			return $this->modeloValoracionConsultaMedica->actualizar($datosBd, $tablaModelo->getIdValoracionConsultaMedica());
		}else{
			unset($datosBd["id_valoracion_consulta_medica"]);
			return $this->modeloValoracionConsultaMedica->guardar($datosBd);
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
		$this->modeloValoracionConsultaMedica->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return ValoracionConsultaMedicaModelo
	 */
	public function buscar($id){
		return $this->modeloValoracionConsultaMedica->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloValoracionConsultaMedica->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloValoracionConsultaMedica->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarValoracionConsultaMedica(){
		$consulta = "SELECT * FROM " . $this->modeloValoracionConsultaMedica->getEsquema() . ". valoracion_consulta_medica";
		return $this->modeloValoracionConsultaMedica->ejecutarSqlNativo($consulta);
	}
}
