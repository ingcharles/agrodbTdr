<?php
/**
 * Lógica del negocio de EstiloVidaModelo
 *
 * Este archivo se complementa con el archivo EstiloVidaControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses EstiloVidaLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class EstiloVidaLogicaNegocio implements IModelo{

	private $modeloEstiloVida = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloEstiloVida = new EstiloVidaModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new EstiloVidaModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdEstiloVida() != null && $tablaModelo->getIdEstiloVida() > 0){
			return $this->modeloEstiloVida->actualizar($datosBd, $tablaModelo->getIdEstiloVida());
		}else{
			unset($datosBd["id_estilo_vida"]);
			return $this->modeloEstiloVida->guardar($datosBd);
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
		$this->modeloEstiloVida->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return EstiloVidaModelo
	 */
	public function buscar($id){
		return $this->modeloEstiloVida->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloEstiloVida->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloEstiloVida->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarEstiloVida(){
		$consulta = "SELECT * FROM " . $this->modeloEstiloVida->getEsquema() . ". estilo_vida";
		return $this->modeloEstiloVida->ejecutarSqlNativo($consulta);
	}
}
