<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 23/02/18
 * Time: 8:40
 */

require_once '../controladores/ControladorEvaluacion.php';
require_once '../servicios/ServiceComiteDAO.php';
require_once '../../../clases/Conexion.php';

class ControladorComite
{
    private $servicios;
    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
        $this->servicios = new ServiceComiteDAO();
    }

    public function getComite($ic_comite_id){
        $resultado=null;
        try{
            $resultado=$this->servicios->getComiteById($ic_comite_id,$this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    function creaComiteEvaluacion(Evaluacion $evaluacion){
        $resultado=null;
        try{
            $resultado=$this->servicios->creaComiteEvaluacion($evaluacion,$this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    public function saveAndUpdateComite(Comite $comite){
        $resultado=null;
        try{
            $comiteObtenido = $this->servicios->getComiteById($comite->getIcEvaluacionComiteId(),$this->conexion);
            $resultado=$this->servicios->saveAndUpdateComite($comite,$this->conexion);

            $controladorRequerimiento = new ControladorRequerimiento();
            $requerimiento = $controladorRequerimiento->recuperarRequerimiento($comiteObtenido->getIcRequerimientoId());
            $auditoria = new ControladorAuditoria();
            $auditoria->incrementarNumeroNotificacion($requerimiento->getInspectorId());
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    /*
     * Construye los articulos visibles para el paso Comité del usuario logeado.
     * */
    public function listArticles(){
        $resultado=null;
        try{
            $controladorCatalogo = new ControladorCatalogosInc();
            $comites=$this->servicios->getAllComiteInStep($this->conexion);
            $resultado="";
            $ic_tipo_requerimiento_id=0;
            $nombre_producto="";
            $changed = false;
            /* @var $comite Comite */
            foreach ($comites as $comite){
                if($ic_tipo_requerimiento_id!=$comite->getIcTipoRequerimientoId()){
                    if($ic_tipo_requerimiento_id!=0){
                        $resultado.="</div>";
                    }
                    $ic_tipo_requerimiento_id=$comite->getIcTipoRequerimientoId();
                    $controladorLaboratorio = new ControladorLaboratorio();
                    $laboratorio = $controladorLaboratorio->getLaboratorio($comite->getIcAnalisisMuestraId());
                    $nombre_tipo_requerimiento = $controladorCatalogo->obtenerNombreTipoRequerimiento($ic_tipo_requerimiento_id);
                    $productoId = $laboratorio->getIcProductoId();
                    $nombre_producto = $controladorCatalogo->obtenerNombreIcProducto($productoId);
                    $resultado.= "<div id='comite-container'>";
                    $resultado.= "<h2>$nombre_tipo_requerimiento</h2>";
                }
                $ic_comite_analisis_id = $comite->getIcEvaluacionComiteId();
                $nombre = "Caso N° ".$comite->getIcRequerimientoId()."<br><br><div style='width:100%;text-align: center'</div>";

                $descripcion = $nombre_producto;
                $color = $comite->getObservacion()!=null ? "#7acff;" : "#D46A6A;";
                $resultado.= "<article 
                                id='$ic_comite_analisis_id'
                                style='background-color:$color'
                                class='item'
                                data-rutaAplicacion='inocuidad'
                                data-opcion='./vistas/icComiteEditar' 
                                ondragstart='drag(event)' 
                                draggable='true' 
                                data-destino='detalleItem'>
                                    <span class='ordinal'>$ic_comite_analisis_id</span>
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

    /*
     * Construye el resumen visible en toda la vitacora del caso
     * */
    public function getComiteRO($idComite){
        $comite=$this->servicios->getComiteById($idComite,$this->conexion);
        $obs = $comite->getObservacion();
        $header="<fieldset class='fieldset-resumen'> <table class='table-resumen'>";
        $header .= "<tr class='resumen-subtitulo'><td colspan='2'><label class='resumen-subtitulo-label'>Comité</label></td></tr>";
        $header.="<tr><td><label class='resumen-titulo'>Observaciones</label></td><td><label class='resumen-contenido'>".$obs."</label></td></tr>";
        $header.="</table> </fieldset>";
        return $header;
    }

}