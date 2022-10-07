<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 20/02/18
 * Time: 23:31
 */

require_once "../Modelo/Laboratorio.php";
require_once "../Modelo/RegistroValor.php";
require_once "../servicios/ServiceLaboratorioDAO.php";

class ControladorLaboratorio
{
    private $conexion;
    private $servicios;
    private $servicioAnalisis;

    public function __construct()
    {
        $this->conexion =  new Conexion();
        $this->servicios = new ServiceLaboratorioDAO();
    }

    public function getLaboratorio($ic_analisis_laboratorio_id){
        $resultado=null;
        try{
            $resultado=$this->servicios->getLaboratorioById($ic_analisis_laboratorio_id,$this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    /*Establece el registro de laboratorio con estado desactivado(Cuando el registro pasa análsis)*/
    public function desactivarLaboratorio($icAnalisisMuestraId){
        $resultado=null;
        try{
            $resultado=$this->servicios->desactivarLaboratorio($icAnalisisMuestraId,$this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    public function saveAndUpdateLaboratorio(Laboratorio $laboratorio){
        $resultado=null;
        try{
            $resultado=$this->servicios->saveAndUpdateLaboratorio($laboratorio,$this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    public function saveAndUpdateRegistroValor(RegistroValor $registroValor){
        $resultado=null;
        try{
            $resultado=$this->servicios->saveAndUpdateRegistroValor($registroValor,$this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    public function getRegistroValores($ic_analisis_muestra_id){
        $resultado=null;
        try{
            $resultado=$this->servicios->getAllRegistroValorByLaboratorio($ic_analisis_muestra_id,$this->conexion);
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    /*
     * Construye la tabla de registro valores mostrada en laboratorio
     * */
    public function listRegistroValores($ic_analisis_muestra_id){
        $resultado=null;
        try{
            $controladorCatalogo = new ControladorCatalogosInc();
            $registros=$this->servicios->getAllRegistroValorByLaboratorio($ic_analisis_muestra_id,$this->conexion);
            $resultado="";
            /* @var $registro RegistroValor */
            foreach ($registros as $registro){
                $ic_registro_valor_id   = $registro->getIcRegistroValorId();
                $valor                  = $registro->getValor();
                $observaciones          = $registro->getObservaciones();
                $uidadm                 = $registro ->getUm();

                $insumo = $controladorCatalogo->obtenerIngredienteActivoById($registro->getIcInsumoId());

                $resultado.="<tr id='$ic_registro_valor_id'>";
                $resultado.="   <td>$insumo</td>";
                $resultado.="   <td>$uidadm</td>";
                $resultado.="   <td><input style='width: 95%' value=\"$valor\" type=\"text\" id=\"valor_$ic_registro_valor_id\" name=\"valor_$ic_registro_valor_id\" class=\"decimal\" data-required/></td>";
                $resultado.="   <td><textarea id=\"obs_$ic_registro_valor_id\" name=\"obs_$ic_registro_valor_id\" cols=\"5\" rows=\"3\" data-required>$observaciones</textarea></td>";
                $resultado.="</tr>";
            }
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    /*
     * Construye los articulos visibles para el paso Laboratorio del usuario logeado.
     * */
    public function listArticles(){
        $resultado=null;
        try{
            $controladorCatalogo = new ControladorCatalogosInc();
            $laboratorios=$this->servicios->getAllLaboratorioInStep($this->conexion);
            $resultado="";
            $ic_tipo_requerimiento_id=0;
            $nombre_producto="";
            $changed = false;
            /* @var $laboratorio Laboratorio */
            foreach ($laboratorios as $laboratorio){
                if($ic_tipo_requerimiento_id!=$laboratorio->getIcTipoRequerimientoId()){
                    if($ic_tipo_requerimiento_id!=0){
                        $resultado.="</div>";
                    }
                    $ic_tipo_requerimiento_id=$laboratorio->getIcTipoRequerimientoId();
                    $nombre_tipo_requerimiento = $controladorCatalogo->obtenerNombreTipoRequerimiento($ic_tipo_requerimiento_id);
                    $nombre_producto = $controladorCatalogo->obtenerNombreIcProducto($laboratorio->getIcProductoId());
                    $resultado.= "<div id='laboratorio-container'>";
                    $resultado.= "<h2>$nombre_tipo_requerimiento</h2>";
                }
                $ic_analisis_muestra_id = $laboratorio->getIcAnalisisMuestraId();
                $nombre = "Caso N° ".$laboratorio->getIcRequerimientoId();
                $descripcion = $nombre_producto;
                $color = $laboratorio->getObservaciones()!=null ? "#7acff;" : "#D46A6A;";
                $resultado.= "<article 
                                id='$ic_analisis_muestra_id'
                                style='background-color:$color'
                                class='item'
                                data-rutaAplicacion='inocuidad'
                                data-opcion='./vistas/icLaboratorioEditar' 
                                ondragstart='drag(event)' 
                                draggable='true' 
                                data-destino='detalleItem'>
                                    <span class='ordinal'>$ic_analisis_muestra_id</span>
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
     * Construye el resumen de laboratorio para la vitacora
     * */
    public function getLaboratorioRO($idLaboratorio){
        $registros=$this->servicios->getLaboratorioRO($idLaboratorio,$this->conexion);
        $header="<fieldset class='fieldset-resumen'> <table class='table-resumen'>";
        $header .= "<tr class='resumen-subtitulo'><td colspan='2'><label class='resumen-subtitulo-label'>Laboratorio</label></td></tr>";
        $hasHeader = false;
        foreach ($registros as $laboratorio){
            if(!$hasHeader){
                $header .= "<tr class='resumen-subtitulo'><td colspan='2'><label class='resumen-subtitulo-label'>Registro de Valores</label></td></tr>";
                $hasHeader=true;
            }
            $header .= "<tr><td colspan='2' style='background-color: #eeeeee' class='header-titulo'>". $laboratorio['insumo'] ." - ". $laboratorio['unidad_medida'] ."</td></tr>";
            $header .= "<tr><td><label class='resumen-titulo'>Valor</label></td><td><label class='resumen-contenido'>" . $laboratorio['valor'] . "</label></td></tr>";
            $header .= "<tr><td><label class='resumen-titulo'>Observación</label></td><td><label class='resumen-contenido'>" . $laboratorio['obs'] . "</label></td></tr>";
        }
        $header.="</table> </fieldset>";

        return $header;
    }
}