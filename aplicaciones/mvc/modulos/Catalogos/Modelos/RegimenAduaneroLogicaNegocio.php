<?php
/**
 * Lógica del negocio de RegimenAduaneroModelo
 *
 * Este archivo se complementa con el archivo RegimenAduaneroControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-07-06
 * @uses RegimenAduaneroLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class RegimenAduaneroLogicaNegocio implements IModelo{

	private $modeloRegimenAduanero = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloRegimenAduanero = new RegimenAduaneroModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new RegimenAduaneroModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdRegimen() != null && $tablaModelo->getIdRegimen() > 0){
			return $this->modeloRegimenAduanero->actualizar($datosBd, $tablaModelo->getIdRegimen());
		}else{
			unset($datosBd["id_regimen"]);
			return $this->modeloRegimenAduanero->guardar($datosBd);
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
		$this->modeloRegimenAduanero->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return RegimenAduaneroModelo
	 */
	public function buscar($id){
		return $this->modeloRegimenAduanero->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloRegimenAduanero->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloRegimenAduanero->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarRegimenAduanero(){
		$consulta = "SELECT * FROM " . $this->modeloRegimenAduanero->getEsquema() . ". regimen_aduanero";
		return $this->modeloRegimenAduanero->ejecutarSqlNativo($consulta);
	}
}
