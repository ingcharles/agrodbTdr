<?php
/**
 * Lógica del negocio de ProductosAreasOperacionModelo
 *
 * Este archivo se complementa con el archivo ProductosAreasOperacionControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-09-18
 * @uses ProductosAreasOperacionLogicaNegocio
 * @package AdministrarOperacionesGuia
 * @subpackage Modelos
 */
namespace Agrodb\AdministrarOperacionesGuia\Modelos;

use Agrodb\AdministrarOperacionesGuia\Modelos\IModelo;

class ProductosAreasOperacionLogicaNegocio implements IModelo{

	private $modeloProductosAreasOperacion = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloProductosAreasOperacion = new ProductosAreasOperacionModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new ProductosAreasOperacionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdProductoAreaOperacion() != null && $tablaModelo->getIdProductoAreaOperacion() > 0){
			return $this->modeloProductosAreasOperacion->actualizar($datosBd, $tablaModelo->getIdProductoAreaOperacion());
		}else{
			unset($datosBd["id_producto_area_operacion"]);
			return $this->modeloProductosAreasOperacion->guardar($datosBd);
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
		$this->modeloProductosAreasOperacion->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return ProductosAreasOperacionModelo
	 */
	public function buscar($id){
		return $this->modeloProductosAreasOperacion->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloProductosAreasOperacion->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloProductosAreasOperacion->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarProductosAreasOperacion(){
		$consulta = "SELECT * FROM " . $this->modeloProductosAreasOperacion->getEsquema() . ". productos_areas_operacion";
		return $this->modeloProductosAreasOperacion->ejecutarSqlNativo($consulta);
	}
}
