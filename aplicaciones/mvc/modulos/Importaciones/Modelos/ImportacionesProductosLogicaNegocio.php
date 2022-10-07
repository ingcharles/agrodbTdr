<?php
/**
 * Lógica del negocio de ImportacionesProductosModelo
 *
 * Este archivo se complementa con el archivo ImportacionesProductosControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-07-06
 * @uses ImportacionesProductosLogicaNegocio
 * @package Importaciones
 * @subpackage Modelos
 */
namespace Agrodb\Importaciones\Modelos;

use Agrodb\Importaciones\Modelos\IModelo;

class ImportacionesProductosLogicaNegocio implements IModelo{

	private $modeloImportacionesProductos = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloImportacionesProductos = new ImportacionesProductosModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new ImportacionesProductosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdImportacionProducto() != null && $tablaModelo->getIdImportacionProducto() > 0){
			return $this->modeloImportacionesProductos->actualizar($datosBd, $tablaModelo->getIdImportacionProducto());
		}else{
			unset($datosBd["id_importacion_producto"]);
			return $this->modeloImportacionesProductos->guardar($datosBd);
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
		$this->modeloImportacionesProductos->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return ImportacionesProductosModelo
	 */
	public function buscar($id){
		return $this->modeloImportacionesProductos->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloImportacionesProductos->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloImportacionesProductos->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarImportacionesProductos(){
		$consulta = "SELECT * FROM " . $this->modeloImportacionesProductos->getEsquema() . ". importaciones_productos";
		return $this->modeloImportacionesProductos->ejecutarSqlNativo($consulta);
	}
}
