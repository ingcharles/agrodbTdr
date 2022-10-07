<?php
/**
 * Lógica del negocio de InventarioProductosModelo
 *
 * Este archivo se complementa con el archivo InventarioProductosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-01-03
 * @uses    InventarioProductosLogicaNegocio
 * @package RegistroEntregaProductos
 * @subpackage Modelos
 */
namespace Agrodb\RegistroEntregaProductos\Modelos;

use Agrodb\RegistroEntregaProductos\Modelos\IModelo;

class InventarioProductosLogicaNegocio implements IModelo
{

    private $modeloInventarioProductos = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloInventarioProductos = new InventarioProductosModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        if((!isset($datos['id_inventario'])) || ($datos['id_inventario']=='')){
            $datos['identificador'] = $_SESSION['usuario'];
            $datos['cantidad_asignada'] = $datos['cantidad'];
        }
        
        $tablaModelo = new InventarioProductosModelo($datos);
        
        $datosBd = $tablaModelo->getPrepararDatos();
        
        if ($tablaModelo->getIdInventario() != null && $tablaModelo->getIdInventario() > 0) {
            return $this->modeloInventarioProductos->actualizar($datosBd, $tablaModelo->getIdInventario());
        } else {
            unset($datosBd["id_inventario"]);
            return $this->modeloInventarioProductos->guardar($datosBd);
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
        $this->modeloInventarioProductos->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return InventarioProductosModelo
     */
    public function buscar($id)
    {
        return $this->modeloInventarioProductos->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloInventarioProductos->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloInventarioProductos->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarInventarioProductos()
    {
        $consulta = "SELECT * FROM " . $this->modeloInventarioProductos->getEsquema() . ". inventario_productos";
        return $this->modeloInventarioProductos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarInventarioDisponible()
    {
        $consulta = "SELECT 
                    	distinct(id_producto_distribucion), nombre_producto_distribucion, sum(cantidad) cantidad
                    FROM 
                    	g_registro_entrega_producto.inventario_productos ip
                    WHERE
                    	ip.estado in ('Activo') 
                    GROUP BY
                    	ip.id_producto_distribucion, nombre_producto_distribucion
                    ORDER BY
			            ip.nombre_producto_distribucion ASC;";
        
        return $this->modeloInventarioProductos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarInventarioDisponibleXProducto($idProducto)
    {
        $consulta = "SELECT
                    	distinct(id_producto_distribucion), sum(cantidad) cantidad
                    FROM
                    	g_registro_entrega_producto.inventario_productos ip
                    WHERE
                    	ip.estado in ('Activo') and
                        ip.id_producto_distribucion = $idProducto
                    GROUP BY
                    	ip.id_producto_distribucion;";
        
        return $this->modeloInventarioProductos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Método para actualizar (disminuir) cantidad de productos del inventario -DistribucionProductos
     */
    public function disminuirCantidadProductos($arrayParametros)
    {
        $cantidadPendiente = $arrayParametros['cantidad'];
        $cantidadDescontar = 0;
        
        $query = "id_producto_distribucion = ".$arrayParametros['id_producto_distribucion']." and estado='Activo' ORDER BY fecha_creacion ASC";
        
        //Obtiene los registros disponibles de inventario del producto solicitado que estén activos
        $registrosInventario = $this->buscarLista($query);
        
        //Se recorre cada registro para disminuir el valor del producto hasta cumplir con la cantidad requerida
        foreach ($registrosInventario as $fila) {
            
            if($cantidadPendiente > 0){
                
                if($fila['cantidad'] <= $cantidadPendiente){
                    $cantidadPendiente = $cantidadPendiente - $fila['cantidad'];
                    $cantidadDescontar = $fila['cantidad'];
                    $estado = 'Inactivo';
                }else{
                    $cantidadDescontar = $cantidadPendiente;
                    $cantidadPendiente -= $cantidadPendiente;
                    $estado = 'Activo';
                }
                
                $arrayParam = array(
                    'id_inventario' => $fila['id_inventario'],
                    'cantidad' => $fila['cantidad'] - $cantidadDescontar,
                    'estado' => $estado
                );
                
                $this->guardar($arrayParam);
            }            
        }        
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar inventario usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarInventarioXFiltro($arrayParametros)
    {
        $consulta = "  SELECT
                        	i.*
                        FROM
                        	g_registro_entrega_producto.inventario_productos i
                        WHERE
                            i.estado = '".$arrayParametros['estado']."'
                            ".($arrayParametros['id_producto_distribucion'] != '' ? " and i.id_producto_distribucion = ".$arrayParametros['id_producto_distribucion'] : "")."
                            ".($arrayParametros['tipo_registro'] != '' ? " and i.tipo_registro = '".$arrayParametros['tipo_registro'] ."'" : "")."
                        ORDER BY
                        	i.nombre_producto_distribucion, id_inventario ASC;";
        
        return $this->modeloInventarioProductos->ejecutarSqlNativo($consulta);
    }
}
