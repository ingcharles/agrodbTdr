<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 06/02/18
 * Time: 10:53
 */

require_once '../servicios/ServiceProductoDAO.php';
require_once '../Modelo/Producto.php';
require_once '../Modelo/ProductoInsumo.php';
require_once '../controladores/ControladorCatalogosInc.php';
require_once '../controladores/ControladorInsumo.php';
require_once '../controladores/ControladorLmr.php';
require_once '../../../clases/Conexion.php';
class ControladorProducto
{
    private $conexion;
    private $servicios;

    /**
     * ControladorProducto constructor.
     */
    public function __construct()
    {
        $this->conexion = new Conexion();
        $this->servicios = new ServiceProductoDAO();
    }

    public function saveAndUpdateProducto(Producto $producto){
        $resultado=null;
        try{
            $resultado=$this->servicios->saveAndUpdateProducto($producto,$this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    public function consultarInsumosDeProducto($producto_id){
        $resultado=null;
        try{
            $resultado=$this->servicios->consultarInsumosDeProducto($producto_id,$this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    public function crearInsumosProducto($ic_producto_id,$producto_id){
        $resultado=null;
        try{
            $resultado=$this->servicios->crearInsumosProducto($ic_producto_id,$producto_id,$this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    public function saveAndUpdateProductoInsumo($ic_producto_id,array $insumos){
        $resultado=null;
        try{
            $resultado=$this->servicios->saveAndUpdateInsumos($ic_producto_id,$insumos,$this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    public function saveAndUpdateProductoMuestraRapida($ic_producto_id,array $muestras){
        $resultado=null;
        try{
            $resultado=$this->servicios->saveAndUpdateProductoMuestraRapida($ic_producto_id,$muestras,$this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    public function listarProductos(){
        $resultado=null;
        try{
            $resultado=$this->servicios->getAllProductos($this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    public function listarProductosInsumos($ic_producto_id){
        $resultado=null;
        try{
            $resultado=$this->servicios->getInsumosProductos($ic_producto_id,$this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    public function listarProductosInsumosGrouped($ic_producto_id){
        $resultado=null;
        try{
            $resultado=$this->servicios->getInsumosProductosGrouped($ic_producto_id,$this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    public function listarProductosInsumosMuestraRapida($ic_producto_id){
        $resultado=null;
        try{
            $resultado=$this->servicios->getInsumosMuestraRapida($ic_producto_id,$this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    /*
     * Este método se invoca mediante ajax para obtener la lista de productos
     * */
    public function listarProductosInsumosJSON($ic_producto_id){
        $json = array();
        try{
            $insumos=$this->servicios->getInsumosProductos($ic_producto_id,$this->conexion);
            /* @var $insumo ProductoInsumo */
            $controladorLmr = new ControladorLmr();
            $controladorCatalogos = new ControladorCatalogosInc();
            foreach ($insumos as $insumo){
                $obj = new stdClass();
                $obj->{'ic_producto_insumo_id'}=$insumo->getIcProductoInsumoId();
                $obj->{'stamped'}= time();
                //El insumo ahora hace referencia a Ingrediente Activo
                $obj->{'ic_insumo_id'}=$insumo->getIcInsumoId();
                $obj->{'insumo'}=$controladorCatalogos->obtenerNombreIngredienteActivo($insumo->getIcInsumoId());
                $obj->{'ic_lmr_id'}=$insumo->getIcLmrId();
                $obj->{'lmr'}=$controladorLmr->getLmr($insumo->getIcLmrId())->getNombre();
                $obj->{'um'}=$insumo->getUm();
                //Unidad de Medida ahora es Texto Obtenido de Ingrediente Activo
                $obj->{'limite_minimo'}=$insumo->getLimiteMinimo();
                $obj->{'limite_maximo'}=$insumo->getLimiteMaximo();
                array_push($json,$obj);
            }
        }catch (Exception $e){
            $json=$e->getMessage();
        }
        return $json;
    }
    /*
     * Este método se invoca mediante ajax para obtener la lista de productos para muestra rápida
     * */
    public function listarMuestraRapidaJSON($ic_producto_id){
        $json_muestras = array();
        try{
            $muestras=$this->servicios->getInsumosMuestraRapida($ic_producto_id,$this->conexion);
            /* @var $muestra ProductoMuestraRapida */
            $controladorInsumo = new ControladorInsumo();
            $controladorCatalogos = new ControladorCatalogosInc();
            foreach ($muestras as $muestra){
                $obj = new stdClass();
                $obj->{'ic_producto_muestra_rapida_id'}=$muestra->getIcProductoMuestraRapidaId();
                $obj->{'stamped'}= time();
                $obj->{'ic_insumo_id'}=$muestra->getIcInsumoId();
                $obj->{'insumo'}=$controladorInsumo->getInsumo($muestra->getIcInsumoId())->getNombre();
                $obj->{'um'}=$muestra->getUm();
                $obj->{'um_name'}=$controladorCatalogos->obtenerUnidadMedidadById($muestra->getUm());
                $obj->{'limite_minimo'}=$muestra->getLimiteMinimo();
                $obj->{'limite_maximo'}=$muestra->getLimiteMaximo();
                array_push($json_muestras,$obj);
            }
        }catch (Exception $e){
            $json_muestras=$e->getMessage();
        }
        return $json_muestras;
    }

    public function getProducto($ic_producto_id){
        $resultado=null;
        try{
            $resultado=$this->servicios->getProductoById($ic_producto_id,$this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }
    /*
     * Construye los productos visibles
     * */
    public function listArticles(){
        $resultado=null;
        try{
            $controladorCatalogo = new ControladorCatalogosInc();
            $productos=$this->servicios->getAllProductos($this->conexion);
            $resultado="";
            $id_area='KK';
            $nombre_area="";
            $changed = false;
            /* @var $producto Producto */
            foreach ($productos as $producto){
                if($id_area!=$producto->getIdArea()){
                    if($id_area!='KK'){
                        $resultado.="</div>";
                    }
                    $id_area=$producto->getIdArea();
                    $nombre_area = $controladorCatalogo->obtenerNombreAreaProducto($id_area);
                    $resultado.= "<div id='productos-container'>";
                    $resultado.= "<h2>$nombre_area</h2>";
                }
                $ic_producto_id = $producto->getIcProductoId();
                $nombre = strlen($producto->getNombre())>45?substr($producto->getNombre(),0,45):$producto->getNombre();
                $descripcion = strlen($nombre_area)>29?substr($nombre_area,0,29):$nombre_area;
                $resultado.= "<article 
                                id='$ic_producto_id'
                                class='item'
                                data-rutaAplicacion='inocuidad'
                                data-opcion='./vistas/adminProductosEditar' 
                                ondragstart='drag(event)' 
                                draggable='true' 
                                data-destino='detalleItem'>
                                    <span class='ordinal'>$ic_producto_id</span>
                                    <span>$nombre</span>
                                    <aside><small>$descripcion</small></aside>
                                </article>";
            }
            $resultado.="</div>";
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }
}