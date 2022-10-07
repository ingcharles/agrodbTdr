<?php
/**
 * Lógica del negocio de ElementoProteccionModelo
 *
 * Este archivo se complementa con el archivo ElementoProteccionControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses ElementoProteccionLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class ElementoProteccionLogicaNegocio implements IModelo{

	private $modeloElementoProteccion = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloElementoProteccion = new ElementoProteccionModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new ElementoProteccionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdElementoProteccion() != null && $tablaModelo->getIdElementoProteccion() > 0){
			return $this->modeloElementoProteccion->actualizar($datosBd, $tablaModelo->getIdElementoProteccion());
		}else{
			unset($datosBd["id_elemento_proteccion"]);
			return $this->modeloElementoProteccion->guardar($datosBd);
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
		$this->modeloElementoProteccion->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return ElementoProteccionModelo
	 */
	public function buscar($id){
		return $this->modeloElementoProteccion->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloElementoProteccion->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloElementoProteccion->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarElementoProteccion(){
		$consulta = "SELECT * FROM " . $this->modeloElementoProteccion->getEsquema() . ". elemento_proteccion";
		return $this->modeloElementoProteccion->ejecutarSqlNativo($consulta);
	}

	/**
	 * Columnas para guardar junto con el formulario
	 *
	 * @return string[]
	 */
	public function columnas(){
		$columnas = array(
			'id_historia_clinica',
			'id_procedimiento_medico',
			'id_tipo_procedimiento_medico');
		return $columnas;
	}
}
