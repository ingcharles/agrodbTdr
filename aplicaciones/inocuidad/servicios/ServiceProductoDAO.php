<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 06/02/18
 * Time: 8:58
 */

require_once '../Modelo/Producto.php';
require_once '../Modelo/ProductoInsumo.php';
require_once '../Modelo/ProductoMuestraRapida.php';
class ServiceProductoDAO
{
    /**
     * ServiceProductoDAO constructor.
     */
    public function __construct()
    {
    }

    public function getAllProductos($conexion){
        $queryAll=" SELECT icp.ic_producto_id, icp.producto_id, icp.programa_id, icp.nombre, sp.id_subtipo_producto, sp.id_tipo_producto, tp.id_area, icp.muestra_rapida";
        $queryAll.=" FROM g_inocuidad.ic_producto icp";
        $queryAll.=" JOIN g_catalogos.productos p ON p.id_producto = icp.producto_id";
        $queryAll.=" JOIN g_catalogos.subtipo_productos sp ON sp.id_subtipo_producto = p.id_subtipo_producto";
        $queryAll.=" JOIN g_catalogos.tipo_productos tp ON tp.id_tipo_producto = sp.id_tipo_producto";
        $queryAll.=" ORDER BY tp.id_area, icp.nombre";

        $filas = array();
        try{
            $result = $conexion->ejecutarConsulta($queryAll);
            while ($filasProducto = pg_fetch_assoc($result)) {
                $producto = new Producto($filasProducto['ic_producto_id'],$filasProducto['producto_id'],$filasProducto['programa_id'],$filasProducto['nombre'],$filasProducto['muestra_rapida']);
                $producto->setIdArea($filasProducto['id_area']);
                array_push($filas, $producto);
            }
        }catch(Exception $exc){
            return array();
        }
        return $filas;
    }

    public function getProductoById($productoId,$conexion){
        $queryAll=" SELECT icp.ic_producto_id, icp.producto_id, icp.programa_id, icp.nombre, sp.id_subtipo_producto, sp.id_tipo_producto, tp.id_area, icp.muestra_rapida";
        $queryAll.=" FROM g_inocuidad.ic_producto icp";
        $queryAll.=" JOIN g_catalogos.productos p ON p.id_producto = icp.producto_id";
        $queryAll.=" JOIN g_catalogos.subtipo_productos sp ON sp.id_subtipo_producto = p.id_subtipo_producto";
        $queryAll.=" JOIN g_catalogos.tipo_productos tp ON tp.id_tipo_producto = sp.id_tipo_producto";
        $queryAll.=" WHERE ic_producto_id=$productoId";
        try{
            $result = $conexion->ejecutarConsulta($queryAll);
            while ($filasProducto = pg_fetch_assoc($result)) {
                $producto = new Producto($filasProducto['ic_producto_id'],$filasProducto['producto_id'],$filasProducto['programa_id'],$filasProducto['nombre'],$filasProducto['muestra_rapida']);
                $producto->setIdTipoProducto($filasProducto['id_tipo_producto']);
                $producto->setIdSubtipoProducto($filasProducto['id_subtipo_producto']);
                $producto->setIdArea($filasProducto['id_area']);
            }
        }catch(Exception $exc){
            return new Producto();
        }
        return $producto;
    }

    public function getInsumosProductos($ic_producto_id,$conexion){
        $queryAll=" SELECT ic_producto_insumo_id,ic_producto_id, ic_insumo_id, ic_lmr_id, um, limite_minimo, limite_maximo";
        $queryAll.=" FROM g_inocuidad.ic_producto_insumo";
        $queryAll.=" WHERE ic_producto_id=$ic_producto_id";

        $filas = array();
        try{
            $result = $conexion->ejecutarConsulta($queryAll);
            while ($filasProducto = pg_fetch_assoc($result)) {
                $producto = new ProductoInsumo($filasProducto['ic_producto_insumo_id'],$filasProducto['ic_producto_id'],$filasProducto['ic_insumo_id'],$filasProducto['ic_lmr_id'],$filasProducto['um'],$filasProducto['limite_minimo'],$filasProducto['limite_maximo']);
                array_push($filas, $producto);
            }
        }catch(Exception $exc){
            return array();
        }
        return $filas;
    }

    public function getInsumosProductosGrouped($ic_producto_id,$conexion){
        $queryAll="SELECT ic_producto_id, ic_insumo_id,um
                    FROM g_inocuidad.ic_producto_insumo
                    WHERE ic_producto_id=$ic_producto_id
                    GROUP BY ic_producto_id,ic_insumo_id,um
                    ORDER BY ic_producto_id";

        $filas = array();
        try{
            $result = $conexion->ejecutarConsulta($queryAll);
            while ($filasProducto = pg_fetch_assoc($result)) {
                $producto = new ProductoInsumo(null,$filasProducto['ic_producto_id'],$filasProducto['ic_insumo_id'],null,$filasProducto['um'],null,null);
                array_push($filas, $producto);
            }
        }catch(Exception $exc){
            return array();
        }
        return $filas;
    }

    public function getInsumosMuestraRapida($ic_producto_id,$conexion){
        $queryAll="SELECT ic_producto_muestra_rapida_id,ic_producto_id, ic_insumo_id,um,limite_minimo,limite_maximo
                    FROM g_inocuidad.ic_producto_muestra_rapida
                    WHERE ic_producto_id=$ic_producto_id";

        $filas = array();
        try{
            $result = $conexion->ejecutarConsulta($queryAll);
            while ($filasProducto = pg_fetch_assoc($result)) {
                $producto = new ProductoMuestraRapida($filasProducto['ic_producto_muestra_rapida_id'],$filasProducto['ic_producto_id'],$filasProducto['ic_insumo_id'],$filasProducto['um'],$filasProducto['limite_minimo'],$filasProducto['limite_maximo']);
                array_push($filas, $producto);
            }
        }catch(Exception $exc){
            return array();
        }
        return $filas;
    }

    public function saveAndUpdateProducto(Producto $producto,$conexion){
        $result=null;
        $querySave="";
        $sequenceQuery ="SELECT nextval('g_inocuidad.ic_producto_ic_producto_id_seq')";
        if(isset($producto)) {

            $ic_producto_id = $producto->getIcProductoId();
            $producto_id = $producto->getProductoId();
            $programa_id = $producto->getProgramaId();
            $nombre = $producto->getNombre();
            $muestra_rapida = $producto->getMuestraRapida();

            if ($producto->getIcProductoId() != null) {
                $querySave = " UPDATE g_inocuidad.ic_producto";
                $querySave .= "   SET nombre='$nombre', producto_id=$producto_id, programa_id=$programa_id, muestra_rapida='$muestra_rapida'";
                $querySave .= " WHERE ic_producto_id=$ic_producto_id";
            } else {
                $ic_producto_id = $this->obtenerSecuencial($conexion,$sequenceQuery);
                $querySave = " INSERT INTO g_inocuidad.ic_producto(ic_producto_id,nombre, producto_id, programa_id, muestra_rapida)";
                $querySave .= " VALUES($ic_producto_id,'$nombre',$producto_id,$programa_id,'$muestra_rapida')";
            }
            try{
                $result=$conexion->ejecutarConsulta($querySave);
                $result=$ic_producto_id;
            }catch (Exception $exc){
                $result = $exc->getMessage();
            }

            return $result;
        }
    }

    public function crearInsumosProducto($ic_producto_id,$producto_id, $conexion){
        $result=null;
        $querySave = "INSERT INTO g_inocuidad.ic_producto_insumo(
                            ic_producto_id, ic_insumo_id, ic_lmr_id,
                            um, limite_minimo, limite_maximo)
                (SELECT distinct $ic_producto_id,p.id_producto, lmr.ic_lmr_id, p.unidad_medida, 0, 0
                FROM g_catalogos.producto_inocuidad_uso uso
                JOIN g_catalogos.productos as p on uso.id_producto = p.id_producto
                JOIN g_inocuidad.ic_lmr lmr ON 1=1
                WHERE id_aplicacion_producto = $producto_id)";
        try{
            $conexion->ejecutarConsulta($querySave);
        }catch (Exception $exc){
            $result = $exc->getMessage();
        }
        return $result;
    }

    public function consultarInsumosDeProducto($producto_id, $conexion){
        $result=null;
        $totalInsumos=0;
        $queryAll = "SELECT count(distinct id_producto) as total
                    FROM g_catalogos.producto_inocuidad_uso uso
                    WHERE id_aplicacion_producto = $producto_id";
        try{
            $result = $conexion->ejecutarConsulta($queryAll);
            while ($fila = pg_fetch_assoc($result)) {
                $totalInsumos=$fila['total'];
            }
        }catch(Exception $exc){
            return $exc;
        }
        return $totalInsumos;
    }

    public function saveAndUpdateInsumos($ic_producto_id,array $insumos, $conexion){
        $result=null;
        $querySave="";
        $sequenceQuery ="SELECT nextval('g_inocuidad.ic_producto_insumo_ic_producto_insumo_id_seq')";
        if(isset($insumos)) {
            /* @var $insumo ProductoInsumo */
            foreach ($insumos as $insumo){
                $ic_producto_insumo_id = $insumo->getIcProductoInsumoId();
                $ic_insumo_id = $insumo->getIcInsumoId();
                $ic_lmr_id = $insumo->getIcLmrId();
                $um = $insumo->getUm();
                $limite_minimo=$insumo->getLimiteMinimo();
                $limite_maximo=$insumo->getLimiteMaximo();

                if($insumo->getIcProductoInsumoId() != null){
                    $querySave = " UPDATE g_inocuidad.ic_producto_insumo";
                    $querySave .= "   SET ic_insumo_id=$ic_insumo_id, ic_lmr_id=$ic_lmr_id, um='$um', limite_minimo=$limite_minimo, limite_maximo=$limite_maximo";
                    $querySave .= " WHERE ic_producto_insumo_id=$ic_producto_insumo_id";
                }else{
                    $ic_producto_insumo_id = $this->obtenerSecuencial($conexion,$sequenceQuery);
                    $querySave = "INSERT INTO g_inocuidad.ic_producto_insumo(";
                    $querySave .= " ic_producto_insumo_id, ic_producto_id, ic_insumo_id, ic_lmr_id,";
                    $querySave .= " um, limite_minimo, limite_maximo)";
                    $querySave .= " VALUES ($ic_producto_insumo_id, $ic_producto_id, $ic_insumo_id, $ic_lmr_id,";
                    $querySave .= "  '$um', $limite_minimo, $limite_maximo)";
                }
                try{
                    $result=$conexion->ejecutarConsulta($querySave);
                    $result=$ic_producto_insumo_id;
                }catch (Exception $exc){
                    $result = $exc->getMessage();
                }
            }
        }
        return $result;
    }

    public function saveAndUpdateProductoMuestraRapida($ic_producto_id,array $muestras, $conexion){
        $result=null;
        $querySave="";
        $sequenceQuery ="SELECT nextval('g_inocuidad.ic_producto_muestra_rapida_ic_producto_muestra_rapida_id_seq')";
        if(isset($muestras)) {
            /* @var $muestra ProductoMuestraRapida */
            foreach ($muestras as $muestra){
                $ic_producto_muestra_rapida_id = $muestra->getIcProductoMuestraRapidaId();
                $ic_insumo_id = $muestra->getIcInsumoId();
                $um = $muestra->getUm();
                $limite_minimo=$muestra->getLimiteMinimo();
                $limite_maximo=$muestra->getLimiteMaximo();

                if($muestra->getIcProductoMuestraRapidaId() != null){
                    $querySave = " UPDATE g_inocuidad.ic_producto_muestra_rapida";
                    $querySave .= "   SET ic_insumo_id=$ic_insumo_id, um='$um', limite_minimo=$limite_minimo, limite_maximo=$limite_maximo";
                    $querySave .= " WHERE ic_producto_muestra_rapida_id=$ic_producto_muestra_rapida_id";
                }else{
                    $ic_producto_muestra_rapida_id = $this->obtenerSecuencial($conexion,$sequenceQuery);
                    $querySave = "INSERT INTO g_inocuidad.ic_producto_muestra_rapida(";
                    $querySave .= " ic_producto_muestra_rapida_id, ic_producto_id, ic_insumo_id,";
                    $querySave .= " um, limite_minimo, limite_maximo)";
                    $querySave .= " VALUES ($ic_producto_muestra_rapida_id, $ic_producto_id, $ic_insumo_id,";
                    $querySave .= "  '$um', $limite_minimo, $limite_maximo)";
                }
                try{
                    $result=$conexion->ejecutarConsulta($querySave);
                    $result=$ic_producto_muestra_rapida_id;
                }catch (Exception $exc){
                    $result = $exc->getMessage();
                }
            }
        }
        return $result;
    }

    public function deleteProducto($productoId,$conexion){
        $queryDelete="DELETE FROM g_inocuidad.ic_producto WHERE ic_producto_id=$productoId";
        $result = $conexion->ejecutarConsulta($queryDelete);
        return $result;
    }
    private function obtenerSecuencial($conexion,$querySequence){
        $res=$conexion->ejecutarConsulta($querySequence);
        $sec=pg_fetch_assoc($res);
        return $sec['nextval'];
    }


}