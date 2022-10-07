<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 22/02/18
 * Time: 22:46
 */

require_once '../controladores/ControladorCatalogosInc.php';
require_once '../controladores/ControladorLaboratorio.php';
require_once '../controladores/ControladorRequerimiento.php';
require_once '../controladores/ControladorMuestra.php';
require_once '../controladores/ControladorComite.php';
require_once '../controladores/ControladorCatalogosInc.php';
require_once '../controladores/ControladorMensajes.php';
require_once '../Modelo/Evaluacion.php';
require_once '../servicios/ServiceEvaluacionDAO.php';
require_once '../servicios/ServiceComiteDAO.php';
require_once '../../../clases/Conexion.php';

class ControladorEvaluacion
{
    private $servicios;
    private $conexion;
    private $serviciosComite;

    public function __construct()
    {
        $this->conexion = new Conexion();
        $this->servicios = new ServiceEvaluacionDAO();
        $this->serviciosComite = new ServiceComiteDAO();
    }

    public function getEvaluacion($ic_evaluacion_id){
        $resultado=null;
        try{
            $resultado=$this->servicios->getEvaluacionById($ic_evaluacion_id,$this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    function creaEvaluacionLaboratorio(Laboratorio $laboratorio){
        $resultado=null;
        try{
            $resultado=$this->servicios->creaEvaluacionLaboratorio($laboratorio,$this->conexion);
            $evaluacion = $this->servicios->getEvaluacionById($resultado,$this->conexion);
            $controladorRequerimiento = new ControladorRequerimiento();
            $requerimiento = $controladorRequerimiento->recuperarRequerimiento($evaluacion->getIcRequerimientoId());
            $auditoria = new ControladorAuditoria();
            $auditoria->incrementarNumeroNotificacion($requerimiento->getInspectorId());
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    /*Actualiza la Evaluación a Estado Activo*/
    function activarEvaluacion($ic_evaluacion_analisis_id){
        $this->servicios->activarEvaluacion($ic_evaluacion_analisis_id,$this->conexion);
    }

    /*
     * Según el resultado de la decisión, el sistema pone el caso en uno de los estados siguientes
     * */
    public function evaluarAccion($ic_evaluacion_analisis_id, $ic_resultado_decision_id){
        $mensaje = array();
        $mensaje['estado']="fallo";
        $mensaje['mensaje']="No se ha podido ejecutar la acción";
        $controladorMensajes = new ControladorMensajes();
        $evaluacion = $this->servicios->getEvaluacionById($ic_evaluacion_analisis_id,$this->conexion);
        $evaluacion->setIcResultadoDecisionId($ic_resultado_decision_id);
        $this->servicios->saveAndUpdateEvaluacion($evaluacion,$this->conexion);
        $controladorMuestra = new ControladorMuestra($this->conexion);
        $muestra = $controladorMuestra->getMuestra($evaluacion->getIcMuestraId());
        $ic_requerimiento_id = $muestra->getIcRequerimientoId();
        $controladorCatalogo = new ControladorCatalogosInc();
        $tipo = $controladorCatalogo->obtenerTipoSegunDecision($ic_resultado_decision_id);
        switch ($tipo){
            //Los Tipo ALE Finalizan el CASO para crear una ALERTA. Estos casos se visualizarán en el reporte
            case 'ALE':
                try{
                    $muestra->setIcResultadoDecisionId($ic_resultado_decision_id);
                    $controladorMuestra->saveAndUpdateMuestra($muestra,$this->conexion);
                    $this->reducirNotificacion($evaluacion);
                    //Desactivamos el registro de analisis
                    $this->servicios->desactivarEvaluacion($evaluacion->getIcEvaluacionAnalisisId(),$this->conexion);
                    $controladorMensajes->enviarMensaje($ic_requerimiento_id,"EVAL","ALE");
                    $mensaje['estado']="exito";
                    $mensaje['mensaje']="Se ha creado la Alerta";
                }catch (Exception $e){
                    $mensaje['estado']="fallo";
                    $mensaje['mensaje']=$e->getMessage();
                }
                break;
            //Los Tipo COM se envían a los usuarios con rol de COMITÉ, para que puedan evaluar los casos y devolverlos a análisis con sus observaciones.
            case 'COM':
                try{
                    $this->serviciosComite->creaComiteEvaluacion($evaluacion,$this->conexion);
                    $this->reducirNotificacion($evaluacion);
                    //Desactivamos el registro de analisis
                    $this->servicios->desactivarEvaluacion($evaluacion->getIcEvaluacionAnalisisId(),$this->conexion);
                    $controladorMensajes->enviarMensaje($ic_requerimiento_id,"EVAL","COM");
                    $mensaje['estado']="exito";
                    $mensaje['mensaje']="Se ha enviado el registro a Comité";
                }catch(Exception $e){
                    $mensaje['estado']="fallo";
                    $mensaje['mensaje']=$e->getMessage();
                }
                break;
            //Los tipo NOR se cierran sin generar Alerta. Es donde deberían calzar la mayoría de casos.
            case 'NOR':
                try{
                    $muestra->setIcResultadoDecisionId($ic_resultado_decision_id);
                    $this->reducirNotificacion($evaluacion);
                    $controladorMuestra->saveAndUpdateMuestra($muestra,$this->conexion);
                    //Desactivamos el registro de analisis
                    $this->servicios->desactivarEvaluacion($evaluacion->getIcEvaluacionAnalisisId(),$this->conexion);
                    $controladorMensajes->enviarMensaje($ic_requerimiento_id,"EVAL","NOR");
                    $mensaje['estado']="exito";
                    $mensaje['mensaje']="El registro se ha cerrado con éxito";
                }catch (Exception $e){
                    $mensaje['estado']="fallo";
                    $mensaje['mensaje']=$e->getMessage();
                }
                break;
            //Los tipo REA envían nuevamente a laboratorio, siempre y cuando existan muestras pendientes por analizar.
            case 'REA':
                try{
                    //Verificamos si hay muestras para pasar por el laboratorio
                    $controladorLaboratorio = new ControladorLaboratorio();
                    $laboratorio = $controladorLaboratorio->getLaboratorio($evaluacion->getIcAnalisisMuestraId());

                    if($muestra->getCantidadMuestrasLab()>$muestra->getCantidadContraMuestra()){
                        //Desactivamos el registro de analisis y de laboratorio anterior
                        $this->servicios->desactivarEvaluacion($evaluacion->getIcEvaluacionAnalisisId(),$this->conexion);
                        $controladorLaboratorio->desactivarLaboratorio($laboratorio->getIcAnalisisMuestraId());
                        $this->reducirNotificacion($evaluacion);
                        //Creamos el nuevo registro
                        $controladorMuestra->creaLaboratorio($muestra->getIcMuestraId(),($muestra->getCantidadContraMuestra()+1));
                        $controladorMuestra->actualizaContramuestra($muestra->getIcMuestraId(),($muestra->getCantidadContraMuestra()+1));
                        $controladorMensajes->enviarMensaje($ic_requerimiento_id,"EVAL","REA");
                        $mensaje['estado']="exito";
                        $mensaje['mensaje']="Se ha enviado el reanálisis a Laboratorio";
                    }else {
                        $mensaje['estado']="fallo";
                        $mensaje['mensaje']="No existen contramuestras para analizar: Muestra("
                            .$muestra->getCantidadMuestrasLab()." - ".$muestra->getCantidadContraMuestra();
                    }
                }catch (Exception $e){
                    $mensaje['estado']="fallo";
                    $mensaje['mensaje']=$e->getMessage();
                }
                break;
        }
        return $mensaje;
    }

    public function reducirNotificacion(Evaluacion $evaluacion)
    {
        $controladorRequerimiento = new ControladorRequerimiento();
        $requerimiento = $controladorRequerimiento->recuperarRequerimiento($evaluacion->getIcRequerimientoId());
        $auditoria = new ControladorAuditoria();
        $auditoria->reducirNumeroNotificacion($requerimiento->getInspectorId());
    }

    public function saveAndUpdateEvaluacion(Evaluacion $evaluacion){
        $resultado=null;
        try{
            $resultado=$this->servicios->saveAndUpdateEvaluacion($evaluacion,$this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    /*Muestra los registros pendientes de análisis según el usuario logeado*/
    public function listArticles($usuario){
        $resultado=null;
        try{
            $controladorCatalogo = new ControladorCatalogosInc();
            $evaluacions=$this->servicios->getAllEvaluacionInStep($usuario,$this->conexion);
            $resultado="";
            $ic_tipo_requerimiento_id=0;
            $nombre_producto="";
            $changed = false;
            /* @var $evaluacion Evaluacion */
            foreach ($evaluacions as $evaluacion){
                if($ic_tipo_requerimiento_id!=$evaluacion->getIcTipoRequerimientoId()){
                    if($ic_tipo_requerimiento_id!=0){
                        $resultado.="</div>";
                    }
                    $ic_tipo_requerimiento_id=$evaluacion->getIcTipoRequerimientoId();
                    $controladorLaboratorio = new ControladorLaboratorio();
                    $laboratorio = $controladorLaboratorio->getLaboratorio($evaluacion->getIcAnalisisMuestraId());
                    $nombre_tipo_requerimiento = $controladorCatalogo->obtenerNombreTipoRequerimiento($ic_tipo_requerimiento_id);
                    $productoId = $laboratorio->getIcProductoId();
                    $nombre_producto = $controladorCatalogo->obtenerNombreIcProducto($productoId);
                    $resultado.= "<div id='evaluacion-container'>";
                    $resultado.= "<h2>$nombre_tipo_requerimiento</h2>";
                }
                $ic_evaluacion_analisis_id = $evaluacion->getIcEvaluacionAnalisisId();
                $nombre = "Caso N° ".$evaluacion->getIcRequerimientoId()."<br><br><div style='width:100%;text-align: center'</div>";

                $descripcion = $nombre_producto;
                $color = $evaluacion->getObservacion()!=null ? "#7acff;" : "#D46A6A;";
                $resultado.= "<article 
                                id='$ic_evaluacion_analisis_id'
                                style='background-color:$color'
                                class='item'
                                data-rutaAplicacion='inocuidad'
                                data-opcion='./vistas/icAnalisisEditar' 
                                ondragstart='drag(event)' 
                                draggable='true' 
                                data-destino='detalleItem'>
                                    <span class='ordinal'>$ic_evaluacion_analisis_id</span>
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
    public function getEvaluacionRO($idEvaluacion){
        $evaluacion=$this->servicios->getEvaluacionRO($idEvaluacion,$this->conexion);
        $header="<fieldset class='fieldset-resumen'> <table class='table-resumen'>";
        $header .= "<tr class='resumen-subtitulo'><td colspan='2'><label class='resumen-subtitulo-label'>Evaluación / Analisis</label></td></tr>";
        $header.="<tr><td><label class='resumen-titulo'>Observaciones</label></td><td><label class='resumen-contenido'>".$evaluacion['observacion']."</label></td></tr>";
        $header.="</table> </fieldset>";
        return $header;
    }

    /*
     * Construye la tabla de resultado de análisis automático y la pinta según el límite exedido del compuesto
     * */
    public function getResultadoDatos($ic_analisis_muestra_id){
        $evaluaciones=$this->servicios->getResultadoDatos($ic_analisis_muestra_id,$this->conexion);
        $resultado = "";
        foreach ($evaluaciones as $evaluacion) {
            $res = "OK";
            $color="transparent";
            if($evaluacion['valor']<$evaluacion['limite_minimo']){
                $res="INFERIOR";
                $color="#e8daaf";
            }else if($evaluacion['valor']>$evaluacion['limite_maximo']){
                $res="SUPERIOR";
                $color="#e8afb6";
            }
            $resultado .= "<tr style='background-color: $color'><td>" . $evaluacion['insumo'] . "</td>";
            $resultado .= "<td>" . $evaluacion['lmr'] . "</td>";
            $resultado .= "<td>" . $evaluacion['unidad_medida'] . "</td>";
            $resultado .= "<td style='text-align: right'>" . $evaluacion['limite_minimo'] . "</td>";
            $resultado .= "<td style='text-align: right'>" . $evaluacion['valor'] . "</td>";
            $resultado .= "<td style='text-align: right'>" . $evaluacion['limite_maximo'] . "</td>";
            $resultado .= "<td>" . $evaluacion['observaciones'] . "</td>";
            $resultado .= "<td>$res<img/></td></tr>";
        }
        return $resultado;
    }
}