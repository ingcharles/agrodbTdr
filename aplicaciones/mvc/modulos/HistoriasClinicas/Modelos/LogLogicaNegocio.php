<?php
/**
 * Lógica del negocio de LogModelo
 *
 * Este archivo se complementa con el archivo LogControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses LogLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class LogLogicaNegocio implements IModelo{

	private $modeloLog = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloLog = new LogModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new LogModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdLog() != null && $tablaModelo->getIdLog() > 0){
			return $this->modeloLog->actualizar($datosBd, $tablaModelo->getIdLog());
		}else{
			unset($datosBd["id_log"]);
			return $this->modeloLog->guardar($datosBd);
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
		$this->modeloLog->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return LogModelo
	 */
	public function buscar($id){
		return $this->modeloLog->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloLog->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloLog->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarLog(){
		$consulta = "SELECT * FROM " . $this->modeloLog->getEsquema() . ". log";
		return $this->modeloLog->ejecutarSqlNativo($consulta);
	}

	/**
	 * Columnas para guardar junto con el formulario
	 *
	 * @return string[]
	 */
	public function columnas(){
		$columnas = array(
			'identificador',
			'id_historia_clinica',
			'accion',
			'transaccion');
		return $columnas;
	}
}
