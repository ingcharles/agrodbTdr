<?php
/**
 * Lógica del negocio de PlagasModelo
 *
 * Este archivo se complementa con el archivo PlagasControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-03-24
 * @uses PlagasLogicaNegocio
 * @package PlagasLaboratorio
 * @subpackage Modelos
 */
namespace Agrodb\PlagasLaboratorio\Modelos;

use Agrodb\PlagasLaboratorio\Modelos\IModelo;

class PlagasLogicaNegocio implements IModelo{

	private $modeloPlagas = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloPlagas = new PlagasModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new PlagasModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdPlaga() != null && $tablaModelo->getIdPlaga() > 0){
			return $this->modeloPlagas->actualizar($datosBd, $tablaModelo->getIdPlaga());
		}else{
			unset($datosBd["id_plaga"]);
			return $this->modeloPlagas->guardar($datosBd);
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
		$this->modeloPlagas->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return PlagasModelo
	 */
	public function buscar($id){
		return $this->modeloPlagas->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloPlagas->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloPlagas->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarPlagas(){
		$consulta = "SELECT * FROM " . $this->modeloPlagas->getEsquema() . ". plagas";
		return $this->modeloPlagas->ejecutarSqlNativo($consulta);
	}
}
