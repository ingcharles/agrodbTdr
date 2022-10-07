<?php
/**
 * Lógica del negocio de ZooExportacionesModelo
 *
 * Este archivo se complementa con el archivo ZooExportacionesControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-09-16
 * @uses ZooExportacionesLogicaNegocio
 * @package ZoosanitarioExportacion
 * @subpackage Modelos
 */
namespace Agrodb\ZoosanitarioExportacion\Modelos;

use Agrodb\ZoosanitarioExportacion\Modelos\IModelo;

class ZooExportacionesLogicaNegocio implements IModelo{

	private $modeloZooExportaciones = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloZooExportaciones = new ZooExportacionesModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new ZooExportacionesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdZooExportacion() != null && $tablaModelo->getIdZooExportacion() > 0){
			return $this->modeloZooExportaciones->actualizar($datosBd, $tablaModelo->getIdZooExportacion());
		}else{
			unset($datosBd["id_zoo_exportacion"]);
			return $this->modeloZooExportaciones->guardar($datosBd);
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
		$this->modeloZooExportaciones->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return ZooExportacionesModelo
	 */
	public function buscar($id){
		return $this->modeloZooExportaciones->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloZooExportaciones->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloZooExportaciones->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarZooExportaciones(){
		$consulta = "SELECT * FROM " . $this->modeloZooExportaciones->getEsquema() . ". zoo_exportaciones";
		return $this->modeloZooExportaciones->ejecutarSqlNativo($consulta);
	}
}
