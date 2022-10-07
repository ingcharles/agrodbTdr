<?php
/**
 * Created by PhpStorm.
 * User: advance
 * Date: 2/1/18
 * Time: 11:46 PM
 */

require_once '../servicios/ServiceCasoDAO.php';
require_once '../servicios/ServiceMuestraDAO.php';
require_once '../Modelo/Caso.php';
require_once '../controladores/ControladorCatalogosInc.php';
require_once '../../../clases/Conexion.php';

class ControladorRequerimiento
{
    private $conexion;
    private $servicios;
    private $servicioMuestra;
    /**
     * ControladorRequerimiento constructor.
     * @param $conexion
     */
    public function __construct()
    {
        $this->conexion = new Conexion();
        $this->servicios = new ServiceCasoDAO();
        $this->servicioMuestra=new ServiceMuestraDAO();
    }
    public function saveAndUpdateCaso(Caso $caso,$numero_casos){
        $resultado=null;
        try{
            $resultado=$this->servicios->saveAndUpdateCaso($caso,$this->conexion,$numero_casos);

            if ($resultado === false) {
                $resultado = pg_last_error($this->conexion);
            } else {
                $resultado = null;
            }

        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    public function listarRequerimientos(){
        $resultado=null;
        try{
            $resultado=$this->servicios->getAllCasos($this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    public function listarRequerimientosInStep($usuario){
        $resultado=null;
        try{
            $resultado=$this->servicios->getAllCasosInStep($usuario,$this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    public function recuperarRequerimiento($id){
        $resultado=null;
        try{
            $resultado=$this->servicios->getCasoById($id,$this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    /*
     * Construye los articulos visibles para el paso Caso del usuario logeado.
     * */
    public function listArticles($usuario){
        $resultado=null;
        try{
            $requerimientos=$this->listarRequerimientosInStep($usuario);
            $catalogos=new ControladorCatalogosInc();
            $resultado="";
            $ic_tipo_requerimiento_id=0;
            $provincia_id=0;
            $changed = false;
            /* @var $caso Caso */
            foreach ($requerimientos as $caso){
                if($ic_tipo_requerimiento_id!=$caso->getTipoRequerimientoId()){
                    if($ic_tipo_requerimiento_id!=0){
                        $resultado.="</div>";
                    }
                    $ic_tipo_requerimiento_id=$caso->getTipoRequerimientoId();
                    $nombre_tipo_requerimiento = $caso->getTipoRequerimiento();
                    $resultado.= "<div id='requerimiento-container'>";
                    $resultado.= "<h2>$nombre_tipo_requerimiento</h2>";
                }
                if($provincia_id!=$caso->getProvinciaId()){
                    if($provincia_id=!0){
                        $resultado.="</div>";
                    }
                    $provincia_id=$caso->getProvinciaId();
                    $nombre_provincia = $catalogos->obtenerNombreProvincia($provincia_id);
                    $resultado.= "<div id='provincia-container'>";
                    $resultado.= "<h3>$nombre_provincia</h3>";
                }
                $ic_req_id = $caso->getId();
                $producto_id=$caso->getNombreProducto();
                $resultado.= "<article 
                                id='$ic_req_id'
                                class='item'
                                data-rutaAplicacion='inocuidad'
                                data-opcion='./vistas/icCasosEditar' 
                                ondragstart='drag(event)' 
                                draggable='true' 
                                data-destino='detalleItem'>
                                    <span class='ordinal'>$ic_req_id</span>
                                    <span>$producto_id</span>
                                    <aside><small>$nombre_tipo_requerimiento</small></aside>
                                </article>";
            }
            $resultado.="</div>";
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    /*
     * Construye el resumen del caso para la vitacora
     * */
    public function getCasoRO($idRequerimiento){
        $caso=$this->servicios->getCasoRO($idRequerimiento,$this->conexion);
        $header="<fieldset class='fieldset-header'> <legend>Caso</legend> <table class='table-header'>";
        if($caso!=null){
            $header.="<tr><td><label class='header-titulo'>Número de Caso</label></td><td><label class='header-contenido'>".$caso['ic_requerimiento_id']."</label></td></tr>";
            $header.="<tr><td><label class='header-titulo'>Tipo de Requerimiento</label></td><td><label class='header-contenido'>".$caso['tipo_requerimiento']."</label></td></tr>";
            $header.="<tr><td><label class='header-titulo'>Producto</label></td><td><label class='header-contenido'>".$caso['producto']."</label></td></tr>";
            $header.="<tr><td><label class='header-titulo'>Número de Muestras (Incluido Contramuestras)</label></td><td><label class='header-contenido'>".$caso['numero_muestras']."</label></td></tr>";
            $header.="<tr><td><label class='header-titulo'>Fecha Requerimiento</label></td><td><label class='header-contenido'>".$this->formatoFecha($caso['fecha_solicitud'])."</label></td></tr>";
            $header.="<tr><td><label class='header-titulo'>Inspector</label></td><td><label class='header-contenido'>".$caso['inspector']."</label></td></tr>";
            if($caso['cancelado']=='S'){
                $header .= "<tr class='resumen-subtitulo'><td colspan='2' style='text-align: center;'><label class='resumen-subtitulo-label' style='color: darkred'>*** CANCELADO ***</label></td></tr>";
                $header.="<tr><td><label class='header-titulo'>Detalle</label></td><td><label class='header-contenido' style='color: darkred'>".$caso['motivo_cancelacion']."</label></td></tr>";
            }
            if($caso['ic_tipo_requerimiento_id']==1){//Plan de vigilancia
                $header .= "<tr class='resumen-subtitulo'><td colspan='2'><label class='resumen-subtitulo-label'>Plan de Vigilancia</label></td></tr>";
                $header.="<tr><td><label class='resumen-titulo'>Programa</label></td><td><label class='resumen-contenido'>".$caso['programa']."</label></td></tr>";
                $header.="<tr><td><label class='resumen-titulo'>Fecha Estimada de Inspección</label></td><td><label class='resumen-contenido'>".$this->formatoFecha($caso['fecha_inspeccion'])."</label></td></tr>";
                $header.="<tr><td><label class='resumen-titulo'>Provincia</label></td><td><label class='resumen-contenido'>".$caso['provincia']."</label></td></tr>";
                $header.="<tr><td><label class='resumen-titulo'>Observación</label></td><td><label class='resumen-contenido'>".$caso['observacion']."</label></td></tr>";
            }else if($caso['ic_tipo_requerimiento_id']==2) {//Denuncia
                $header .= "<tr class='resumen-subtitulo'><td colspan='2'><label class='resumen-subtitulo-label'>Denuncia</label></td></tr>";
                $header.="<tr><td><label class='resumen-titulo'>Fecha Denuncia</label></td><td><label class='resumen-contenido'>".$this->formatoFecha($caso['fecha_denuncia'])."</label></td></tr>";
                $header.="<tr><td><label class='resumen-titulo'>Fuente</label></td><td><label class='resumen-contenido'>".$caso['fuente_denuncia']."</label></td></tr>";
                $header.="<tr><td><label class='resumen-titulo'>Nombre Denunciante</label></td><td><label class='resumen-contenido'>".$caso['nombre_denunciante']."</label></td></tr>";
                $header.="<tr><td><label class='resumen-titulo'>Datos Denunciante</label></td><td><label class='resumen-contenido'>".$caso['datos_denunciante']."</label></td></tr>";
                $header.="<tr><td><label class='resumen-titulo'>Provincia</label></td><td><label class='resumen-contenido'>".$caso['provincia']."</label></td></tr>";
                $header.="<tr><td><label class='resumen-titulo'>Observación</label></td><td><label class='resumen-contenido'>".$caso['observacion']."</label></td></tr>";
                $header.="<tr><td><label class='resumen-titulo'>Descripción Denuncia</label></td><td><label class='resumen-contenido'>".$caso['descripcion_denuncia']."</label></td></tr>";
            }else if($caso['ic_tipo_requerimiento_id']==3) {//Notificacion Exterior
                $header .= "<tr class='resumen-subtitulo'><td colspan='2'><label class='resumen-subtitulo-label'>Notificación Exterior</label></td></tr>";
                $header.="<tr><td><label class='resumen-titulo'>Fecha Notificacion</label></td><td><label class='resumen-contenido'>".$this->formatoFecha($caso['fecha_notificacion'])."</label></td></tr>";
                $header.="<tr><td><label class='resumen-titulo'>País Notificación</label></td><td><label class='resumen-contenido'>".$caso['pais_notificacion']."</label></td></tr>";
                $header.="<tr><td><label class='resumen-titulo'>Observación</label></td><td><label class='resumen-contenido'>".$caso['observacion']."</label></td></tr>";
            }
            $header.="</table> </fieldset>";
        }

        return $header;
    }

    private function formatoFecha($fecha){
        $date = new DateTime($fecha);
        return $date->format('d/m/Y');
    }

    /*
     * Crea la muestra a partir del requerimiento realizado
     * */
    public function creaMuestra($idRequerimiento){
        $resultado=null;
        try{
            $caso = $this->servicios->getCasoById($idRequerimiento,$this->conexion);
            $ic_producto_id = $caso->getProductoId();
            $resultado=$this->servicioMuestra->creaMuestraCaso($idRequerimiento,$this->conexion, $ic_producto_id, $caso->getProvinciaId());
            //Auditoria
            $auditoria = new ControladorAuditoria();
            $auditoria->incrementarNumeroNotificacion($caso->getInspectorId());

        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;

    }
}