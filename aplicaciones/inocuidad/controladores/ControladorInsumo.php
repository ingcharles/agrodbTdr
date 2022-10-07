<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 05/02/18
 * Time: 10:13
 */
require_once '../servicios/ServiceInsumoDAO.php';
require_once '../Modelo/Insumo.php';
require_once '../../../clases/Conexion.php';
require_once '../controladores/ControladorCatalogosInc.php';
class ControladorInsumo
{
    private $conexion;
    private $servicios;

    /**
     * ControladorInsumo constructor.
     */
    public function __construct()
    {
        $this->conexion = new Conexion();
        $this->servicios = new ServiceInsumoDAO();
    }

    public function saveAndUpdateInsumo(Insumo $insumo){
        $resultado=null;
        try{
            $resultado=$this->servicios->saveAndUpdateInsumo($insumo,$this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    public function listarInsumos(){
        $resultado=null;
        try{
            $resultado=$this->servicios->getAllInsumos($this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    public function getInsumo($ic_insumo_id){
        $resultado=null;
        try{
            $resultado=$this->servicios->getInsumoById($ic_insumo_id,$this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    /*
     * Construye los insumos visibles
     * */
    public function listArticles(){
        $resultado=null;
        try{
            $controladorCatalogo = new ControladorCatalogosInc();
            $insumos=$this->servicios->getAllInsumos($this->conexion);
            $resultado="";
            $programa_id=0;
            $changed = false;
            /* @var $insumo Insumo */
            foreach ($insumos as $insumo){
                if($programa_id!=$insumo->getProgramaId()){
                    if($programa_id!=0){
                        $resultado.="</div>";
                    }
                    $programa_id=$insumo->getProgramaId();
                    $nombre_programa = $controladorCatalogo->obtenerNombrePrograma($programa_id);
                    $resultado.= "<div id='insumos-container'>";
                    $resultado.= "<h2>$nombre_programa</h2>";
                }
                $ic_insumo_id = $insumo->getIcInsumoId();
                $nombre = strlen($insumo->getNombre())>45?substr($insumo->getNombre(),0,45):$insumo->getNombre();
                $descripcion = strlen($insumo->getDescripcion())>29?substr($insumo->getDescripcion(),0,29):$insumo->getDescripcion();
                $resultado.= "<article 
                                id='$ic_insumo_id'
                                class='item'
                                data-rutaAplicacion='inocuidad'
                                data-opcion='./vistas/adminInsumosEditar' 
                                ondragstart='drag(event)' 
                                draggable='true' 
                                data-destino='detalleItem'>
                                    <span class='ordinal'>$ic_insumo_id</span>
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