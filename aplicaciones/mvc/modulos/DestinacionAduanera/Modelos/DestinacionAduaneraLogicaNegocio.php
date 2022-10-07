<?php
/**
 * Lógica del negocio de DestinacionAduaneraModelo
 *
 * Este archivo se complementa con el archivo DestinacionAduaneraControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-09-16
 * @uses DestinacionAduaneraLogicaNegocio
 * @package DestinacionAduanera
 * @subpackage Modelos
 */
namespace Agrodb\DestinacionAduanera\Modelos;

use Agrodb\DestinacionAduanera\Modelos\IModelo;

class DestinacionAduaneraLogicaNegocio implements IModelo{

	private $modeloDestinacionAduanera = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloDestinacionAduanera = new DestinacionAduaneraModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new DestinacionAduaneraModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDestinacionAduanera() != null && $tablaModelo->getIdDestinacionAduanera() > 0){
			return $this->modeloDestinacionAduanera->actualizar($datosBd, $tablaModelo->getIdDestinacionAduanera());
		}else{
			unset($datosBd["id_destinacion_aduanera"]);
			return $this->modeloDestinacionAduanera->guardar($datosBd);
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
		$this->modeloDestinacionAduanera->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return DestinacionAduaneraModelo
	 */
	public function buscar($id){
		return $this->modeloDestinacionAduanera->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloDestinacionAduanera->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloDestinacionAduanera->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarDestinacionAduanera(){
		$consulta = "SELECT * FROM " . $this->modeloDestinacionAduanera->getEsquema() . ". destinacion_aduanera";
		return $this->modeloDestinacionAduanera->ejecutarSqlNativo($consulta);
	}
}
