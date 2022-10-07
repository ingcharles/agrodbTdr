<?php
/**
 * Lógica del negocio de FitoExportacionesModelo
 *
 * Este archivo se complementa con el archivo FitoExportacionesControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-09-16
 * @uses FitoExportacionesLogicaNegocio
 * @package FitosanitarioExportacion
 * @subpackage Modelos
 */
namespace Agrodb\FitosanitarioExportacion\Modelos;

use Agrodb\FitosanitarioExportacion\Modelos\IModelo;

class FitoExportacionesLogicaNegocio implements IModelo{

	private $modeloFitoExportaciones = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloFitoExportaciones = new FitoExportacionesModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new FitoExportacionesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdFitoExportacion() != null && $tablaModelo->getIdFitoExportacion() > 0){
			return $this->modeloFitoExportaciones->actualizar($datosBd, $tablaModelo->getIdFitoExportacion());
		}else{
			unset($datosBd["id_fito_exportacion"]);
			return $this->modeloFitoExportaciones->guardar($datosBd);
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
		$this->modeloFitoExportaciones->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return FitoExportacionesModelo
	 */
	public function buscar($id){
		return $this->modeloFitoExportaciones->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloFitoExportaciones->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloFitoExportaciones->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarFitoExportaciones(){
		$consulta = "SELECT * FROM " . $this->modeloFitoExportaciones->getEsquema() . ". fito_exportaciones";
		return $this->modeloFitoExportaciones->ejecutarSqlNativo($consulta);
	}
}
