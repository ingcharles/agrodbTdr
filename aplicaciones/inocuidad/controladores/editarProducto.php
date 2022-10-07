<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 06/02/18
 * Time: 10:57
 */
require_once 'ControladorProducto.php';
require_once '../../../clases/Conexion.php';
require_once '../Modelo/Producto.php';
require_once '../Modelo/ProductoMuestraRapida.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

//Llama a metodo Guardar de Caso
try{
    $conexion = new Conexion();
    $controladorProducto=new ControladorProducto($conexion);
    $ic_producto_id = isset($_POST['ic_producto_id']) ? $_POST['ic_producto_id'] : null;
    $nombre = isset($_POST['nombre_producto']) ? $_POST['nombre_producto'] : null;
    $producto_id = isset($_POST['producto_id']) ? $_POST['producto_id'] : null;
    $programa_id = isset($_POST['programa_id']) ? $_POST['programa_id'] : 0;
    $arr_insumos = isset($_POST['arr_insumos']) ? $_POST['arr_insumos'] : null;
    $arr_muestra = isset($_POST['arr_muestra']) ? $_POST['arr_muestra'] : null;
    $muestra_rapida = isset($_POST['muestra_rapida']) ? 'S' : 'N';


    $producto = new Producto($ic_producto_id,$producto_id,$programa_id,$nombre,$muestra_rapida);

    $id_res = $controladorProducto->saveAndUpdateProducto($producto);
    $ic_producto_id = $id_res;

    if($arr_insumos!=null){
       $objArr = json_decode($arr_insumos);
       $insumos = array();
       if($objArr!=null) {
           //Recorremos el objeto de Insumos del producto, obtenidos por ajax en la ventana para almacenarlo en la base de datos.
           foreach ($objArr as $obj) {
               $ic_producto_insumo_id = $obj->{'ic_producto_insumo_id'};
               $ic_insumo_id = $obj->{'ic_insumo_id'};
               $ic_lmr_id = $obj->{'ic_lmr_id'};
               $um = $obj->{'um'};
               $limite_minimo = $obj->{'limite_minimo'};
               $limite_maximo = $obj->{'limite_maximo'};
               $insumo = new ProductoInsumo($ic_producto_insumo_id, $ic_producto_id, $ic_insumo_id, $ic_lmr_id, $um, $limite_minimo, $limite_maximo);
               array_push($insumos, $insumo);
           }
           $controladorProducto->saveAndUpdateProductoInsumo($ic_producto_id, $insumos);
       }
    }
    if($arr_muestra!=null){
        $objArr = json_decode($arr_muestra);
        $muestras = array();
        if($objArr!=null) {
            //Si tiene muestra rápida, almacenamos uno a uno los insumos asociados.
            foreach ($objArr as $obj) {
                $ic_producto_muestra_rapida_id = $obj->{'ic_producto_muestra_rapida_id'};
                $ic_insumo_id = $obj->{'ic_insumo_id'};
                $um = $obj->{'um'};
                $limite_minimo = $obj->{'limite_minimo'};
                $limite_maximo = $obj->{'limite_maximo'};
                $muestra_rapida = new ProductoMuestraRapida($ic_producto_muestra_rapida_id, $ic_producto_id, $ic_insumo_id, $um, $limite_minimo, $limite_maximo);
                array_push($muestras, $muestra_rapida);
            }

            $controladorProducto->saveAndUpdateProductoMuestraRapida($ic_producto_id, $muestras);
        }
    }

    $mensaje['estado'] = 'exito';
    $mensaje['mensaje'] = 'Los datos fueron actualizados';

    $conexion->desconectar();
    echo json_encode($mensaje);
} catch (Exception $ex){
    pg_close($conexion);
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = "Error al ejecutar sentencia";
    echo json_encode($mensaje);
}