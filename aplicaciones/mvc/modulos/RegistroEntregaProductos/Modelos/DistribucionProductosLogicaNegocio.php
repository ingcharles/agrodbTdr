<?php
/**
 * Lógica del negocio de DistribucionProductosModelo
 *
 * Este archivo se complementa con el archivo DistribucionProductosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-01-03
 * @uses    DistribucionProductosLogicaNegocio
 * @package RegistroEntregaProductos
 * @subpackage Modelos
 */
namespace Agrodb\RegistroEntregaProductos\Modelos;

use Agrodb\RegistroEntregaProductos\Modelos\IModelo;

class DistribucionProductosLogicaNegocio implements IModelo
{

    private $modeloDistribucionProductos = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloDistribucionProductos = new DistribucionProductosModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        if((!isset($datos['id_distribucion'])) || ($datos['id_distribucion']=='')){
            $datos['identificador'] = $_SESSION['usuario'];
        }else{
            $datos['identificador_modificacion'] = $_SESSION['usuario'];
            $datos['fecha_modificacion'] = 'now()';
        }
        
        $tablaModelo = new DistribucionProductosModelo($datos);
        
        $datosBd = $tablaModelo->getPrepararDatos();
        
        if ($tablaModelo->getIdDistribucion() != null && $tablaModelo->getIdDistribucion() > 0) {                
            return $this->modeloDistribucionProductos->actualizar($datosBd, $tablaModelo->getIdDistribucion());
        } else {
            unset($datosBd["id_distribucion"]);
            return $this->modeloDistribucionProductos->guardar($datosBd);
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
        $this->modeloDistribucionProductos->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return DistribucionProductosModelo
     */
    public function buscar($id)
    {
        return $this->modeloDistribucionProductos->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloDistribucionProductos->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloDistribucionProductos->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarDistribucionProductos()
    {
        $consulta = "SELECT * FROM " . $this->modeloDistribucionProductos->getEsquema() . ". distribucion_productos";
        return $this->modeloDistribucionProductos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarDistribucionDisponibleXProvinciaEntidad($provincia, $entidad)
    {
        $consulta = "SELECT
                    	distinct(id_producto), producto, sum(cantidad_disponible) cantidad
                    FROM
                    	g_registro_entrega_producto.distribucion_productos dp
                    WHERE
                    	dp.estado in ('Activo') and
                        upper(dp.provincia) ilike upper('%$provincia%') and
                        upper(dp.entidad) ilike upper('%$entidad%')
                    GROUP BY
                    	dp.id_producto, producto
                    ORDER BY
			            dp.producto ASC;";
        
        return $this->modeloDistribucionProductos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarCantidadDisponibleXProductoProvinciaEntidad($idProducto, $provincia, $entidad)
    {
        $consulta = "SELECT
                    	distinct(id_producto), producto, sum(cantidad_disponible) cantidad
                    FROM
                    	g_registro_entrega_producto.distribucion_productos dp
                    WHERE
                    	dp.estado in ('Activo') and
                        upper(dp.provincia) ilike upper('%$provincia%') and
                        upper(dp.entidad) ilike upper('%$entidad%') and
                        dp.id_producto = $idProducto
                    GROUP BY
                    	dp.id_producto, producto
                    ORDER BY
			            dp.producto ASC;";
        
        return $this->modeloDistribucionProductos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Método para actualizar (disminuir) cantidad de productos de la distribución
     */
    public function disminuirCantidadProductosDistribucion($arrayParametros)
    {
        $cantidadPendiente = $arrayParametros['cantidad'];
        $cantidadDescontar = 0;
        
        $query = "  id_producto = ".$arrayParametros['id_producto']." and 
                    upper(provincia) ilike upper('%".$arrayParametros['provincia']."%') and
                    upper(entidad) ilike upper('%".$arrayParametros['entidad']."%') and
                    estado='Activo' 
                    ORDER BY fecha_creacion ASC";
        
        //Obtiene los registros disponibles de la distribución del producto solicitado por provincia y entidad que estén activos
        $registrosDistribucion = $this->buscarLista($query);
        
        //Se recorre cada registro para disminuir el valor del producto hasta cumplir con la cantidad requerida
        foreach ($registrosDistribucion as $fila) {
            
            if($cantidadPendiente > 0){
                
                if($fila['cantidad_disponible'] <= $cantidadPendiente){
                    $cantidadPendiente = $cantidadPendiente - $fila['cantidad_disponible'];
                    $cantidadDescontar = $fila['cantidad_disponible'];
                    $estado = 'Inactivo';
                }else{
                    $cantidadDescontar = $cantidadPendiente;
                    $cantidadPendiente -= $cantidadPendiente;
                    $estado = 'Activo';
                }
                
                $arrayParam = array(
                    'id_distribucion' => $fila['id_distribucion'],
                    'cantidad_disponible' => $fila['cantidad_disponible'] - $cantidadDescontar,
                    'estado' => $estado
                );
                
                $this->guardar($arrayParam);
            }
        }
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada para buscar los registros de distribución con la información de la unidad.
     *
     * @return array|ResultSet
     */
    public function listarDistribucionesConDatos()
    {
        $consulta = "SELECT
                        	d.*, p.unidad
                        FROM
                        	g_registro_entrega_producto.distribucion_productos d
                            INNER JOIN g_catalogos.productos_distribucion p ON d.id_producto = p.id_producto_distribucion
                        WHERE
                            d.estado = 'Activo'
                        ORDER BY
                        	d.producto, provincia, id_distribucion ASC;;";
        
        $codigo = $this->modeloDistribucionProductos->ejecutarSqlNativo($consulta);
        
        return $codigo;
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar distribución usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarDistribucionXFiltro($arrayParametros)
    {
        $busqueda = '';
        
        if (isset($arrayParametros['id_provincia']) && ($arrayParametros['id_provincia'] != '') && ($arrayParametros['id_provincia'] != 'Seleccione....')) {
            $busqueda .= " and d.id_provincia = '" . $arrayParametros['id_provincia'] . "' ";
        }
        if (isset($arrayParametros['entidad']) && ($arrayParametros['entidad'] != '') && ($arrayParametros['entidad'] != 'Seleccione....')) {
            $busqueda .= " and d.entidad = '" . $arrayParametros['entidad'] . "' ";
        }
        
        $consulta = "  SELECT
                        	d.*, p.unidad
                        FROM
                        	g_registro_entrega_producto.distribucion_productos d
                            INNER JOIN g_catalogos.productos_distribucion p ON d.id_producto = p.id_producto_distribucion
                        WHERE
                            d.estado = '".$arrayParametros['estado']."'
                            ".($arrayParametros['id_producto'] != '' ? " and d.id_producto = ".$arrayParametros['id_producto'] : "")."
                            ".($arrayParametros['tipo_registro'] != '' ? " and d.tipo_registro = '".$arrayParametros['tipo_registro'] ."'" : "").
                            $busqueda. "
                        ORDER BY
                        	d.producto, provincia, id_distribucion ASC;";
        
        return $this->modeloDistribucionProductos->ejecutarSqlNativo($consulta);
    }
}