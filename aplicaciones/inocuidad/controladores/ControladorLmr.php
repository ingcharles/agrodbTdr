<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 05/02/18
 * Time: 10:13
 */
require_once '../servicios/ServiceLmrDAO.php';
require_once '../Modelo/Lmr.php';
require_once '../../../clases/Conexion.php';
class ControladorLmr
{
    private $conexion;
    private $servicios;

    /**
     * ControladorLmr constructor.
     */
    public function __construct()
    {
        $this->conexion = new Conexion();
        $this->servicios = new ServiceLmrDAO();
    }

    public function saveAndUpdateLmr(Lmr $lmr){
        $resultado=null;
        try{
            $resultado=$this->servicios->saveAndUpdateLmr($lmr,$this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    public function listarLmrs(){
        $resultado=null;
        try{
            $resultado=$this->servicios->getAllLmrs($this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    public function getLmr($ic_lmr_id){
        $resultado=null;
        try{
            $resultado=$this->servicios->getLmrById($ic_lmr_id,$this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    /*
     * Construye los lmr visibles
     * */
    public function listArticles(){
        $resultado=null;
        try{
            $lmrs=$this->servicios->getAllLmrs($this->conexion);
            $resultado="";
            foreach ($lmrs as $lmr){
                $ic_lmr_id = $lmr->getIcLmrId();
                $nombre = strlen($lmr->getNombre())>45?substr($lmr->getNombre(),0,45):$lmr->getNombre();
                $descripcion = strlen($lmr->getDescripcion())>29?substr($lmr->getDescripcion(),0,29):$lmr->getDescripcion();
                $resultado.= "<article 
                                id='$ic_lmr_id'
                                class='item'
                                data-rutaAplicacion='inocuidad'
                                data-opcion='./vistas/adminLmrsEditar' 
                                ondragstart='drag(event)' 
                                draggable='true' 
                                data-destino='detalleItem'>
                                    <span class='ordinal'>$ic_lmr_id</span>
                                    <span>$nombre</span>
                                    <aside><small>$descripcion</small></aside>
                                </article>";
            }
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }
}