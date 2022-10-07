<?php
/**
 * Lógica del negocio de TipoProductosModelo
 *
 * Este archivo se complementa con el archivo TipoProductosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-09-10
 * @uses    TipoProductosLogicaNegocio
 * @package RequisitoComercializacion
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class TipoProductosLogicaNegocio implements IModelo
{

    private $modeloTipoProductos = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloTipoProductos = new TipoProductosModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new TipoProductosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdTipoProducto() != null && $tablaModelo->getIdTipoProducto() > 0) {
            return $this->modeloTipoProductos->actualizar($datosBd, $tablaModelo->getIdTipoProducto());
        } else {
            unset($datosBd["id_tipo_producto"]);
            return $this->modeloTipoProductos->guardar($datosBd);
        }
    }

    /**
     * Borra el registro actual
     *
     * @param
     *            string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        $this->modeloTipoProductos->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return TipoProductosModelo
     */
    public function buscar($id)
    {
        return $this->modeloTipoProductos->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloTipoProductos->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloTipoProductos->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarTipoProductos()
    {
        $consulta = "SELECT * FROM " . $this->modeloTipoProductos->getEsquema() . ". tipo_productos";
        return $this->modeloTipoProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar información de Tipos de Productos por área temática.
     *
     * @return array|ResultSet
     */
    public function buscarTipoProductoXArea($area)
    {
        $consulta = "  SELECT
                        	tp.*
                        FROM
                        	g_catalogos.tipo_productos tp
                        WHERE
                            tp.id_area = '$area' and
                            tp.estado = 1;";

        $tipoProducto = $this->modeloTipoProductos->ejecutarSqlNativo($consulta);

        return $tipoProducto;
    }
	
	/**
     * Ejecuta una consulta(SQL) personalizada para obtener los tipos de productos que tiene un operador
     * de acuerdo a una operación por área temática.
     *
     * @return array|ResultSet
     */
    public function obtenerTipoProductoXOperacionAreaOperador($arrayParametros) {
        
        $consulta = "   SELECT
                        	distinct tp.id_tipo_producto, tp.nombre, tp.id_area
                        FROM
                        	g_operadores.operaciones op
                        	INNER JOIN g_catalogos.productos p ON op.id_producto = p.id_producto
                        	INNER JOIN g_catalogos.subtipo_productos sp ON p.id_subtipo_producto = sp.id_subtipo_producto
                        	INNER JOIN g_catalogos.tipo_productos tp ON sp.id_tipo_producto = tp.id_tipo_producto
                        	INNER JOIN g_catalogos.tipos_operacion ot ON op.id_tipo_operacion = ot.id_tipo_operacion
                        WHERE
                        	ot.codigo in (".$arrayParametros['codigo_operacion'].") and
                            ot.id_area in ('".$arrayParametros['id_area']."') and
                            op.estado = 'registrado' and
                            op.identificador_operador in (".$arrayParametros['identificador'].")
                        ORDER BY
                        	tp.nombre ASC;";
        
        return $this->modeloTipoProductos->ejecutarSqlNativo($consulta);
    }
	
	/**
     * Busca un tipo de producto por nombre
     *
     * @return ResultSet 
     */
    public function buscarTipoProductoPorNombre($nombreTipoProducto)
    {
        $where = "upper(unaccent(nombre)) = upper(unaccent('$nombreTipoProducto'))";
        return $this->modeloTipoProductos->buscarLista($where, 'nombre');
    }
    
    /**
     * Busca un tipo de producto por nombre
     *
     * @return ResultSet
     */
    public function buscarTipoProductoPorNombreArea($nombreTipoProducto, $area)
    {
        $where = "upper(unaccent(nombre)) = upper(unaccent('$nombreTipoProducto')) and id_area= '$area'";
        return $this->modeloTipoProductos->buscarLista($where, 'nombre');
    }
}