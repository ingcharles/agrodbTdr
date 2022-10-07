<?php
/**
 * Lógica del negocio de ProductosProveedorModelo
 *
 * Este archivo se complementa con el archivo ProductosProveedorControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-07-13
 * @uses ProductosProveedorLogicaNegocio
 * @package ProveedoresExterior
 * @subpackage Modelos
 */
namespace Agrodb\ProveedoresExterior\Modelos;

use Agrodb\ProveedoresExterior\Modelos\IModelo;
use Agrodb\Core\Excepciones\GuardarExcepcion;

class ProductosProveedorLogicaNegocio implements IModelo{

	private $modeloProductosProveedor = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloProductosProveedor = new ProductosProveedorModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		try{

			$tablaModelo = new ProductosProveedorModelo($datos);

			$procesoIngreso = $this->modeloProductosProveedor->getAdapter()
				->getDriver()
				->getConnection();
			$procesoIngreso->beginTransaction();

			$this->modeloProductosProveedor->guardarSql('productos_proveedor', $this->modeloProductosProveedor->getEsquema());

			$datosBd = $tablaModelo->getPrepararDatos();

			if ($tablaModelo->getIdProductoProveedor() != null && $tablaModelo->getIdProductoProveedor() > 0){
				$this->modeloProductosProveedor->actualizar($datosBd, $tablaModelo->getIdProductoProveedor());
				$idProductoProveedor = $tablaModelo->getIdProductoProveedor();
			}else{
				unset($datosBd["id_producto_proveedor"]);
				$idProductoProveedor = $this->modeloProductosProveedor->guardar($datosBd);
			}

			$procesoIngreso->commit();
			return $idProductoProveedor;
		}catch (GuardarExcepcion $ex){
			$procesoIngreso->rollback();
			throw new \Exception($ex->getMessage());
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
		$this->modeloProductosProveedor->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return ProductosProveedorModelo
	 */
	public function buscar($id){
		return $this->modeloProductosProveedor->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloProductosProveedor->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloProductosProveedor->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarProductosProveedor(){
		$consulta = "SELECT * FROM " . $this->modeloProductosProveedor->getEsquema() . ". productos_proveedor";
		return $this->modeloProductosProveedor->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * para verificar los tipos de productos registrados por el proveedor.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerProductosProveedor($arrayParametros){
		$idProveedorExterior = $arrayParametros['id_proveedor_exterior'];
		$idSubtipoProducto = $arrayParametros['id_subtipo_producto'];

		$consulta = "SELECT
                        id_producto_proveedor
                        , id_proveedor_exterior
                        , id_subtipo_producto
                        , nombre_subtipo_producto
                    FROM
                        g_proveedores_exterior.productos_proveedor
                    WHERE
                        id_proveedor_exterior = '" . $idProveedorExterior . "'
                        and id_subtipo_producto = '" . $idSubtipoProducto . "';";

		return $this->modeloProductosProveedor->ejecutarSqlNativo($consulta);
	}

	/**
	 * Devuelve las columnas a ser insertadas.
	 *
	 * @return String
	 */
	public function columnas(){
		$columnas = array(
			'id_proveedor_exterior',
			'id_subtipo_producto',
			'nombre_subtipo_producto');

		return $columnas;
	}
}
