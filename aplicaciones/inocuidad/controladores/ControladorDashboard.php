<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 19/02/18
 * Time: 11:18
 */

require_once '../servicios/ServiceDashboardDAO.php';
require_once '../Modelo/Dashboard.php';

class ControladorDashboard
{

    private $conexion;
    private $servicios;

    /**
     * ControladorInsumo constructor.
     */
    public function __construct()
    {
        $this->conexion = new Conexion();
        $this->servicios = new ServiceDashboardDAO();
    }

    public function searchDashboard($searchItem){

    }

    /*
     * Obtiene la información visible en los tacómetros del dashboard
     * */
    public function recibidosVsDespachados(){
        $resultado=null;
        try{
            $resultado="";
            $registros=$this->servicios->recibidosVsDespachados($this->conexion);
            foreach ($registros as $indice){
                //Se devuelve como objeto JS, para que el componente construya los tacomentros.
                $resultado="var objIndice={\"recibido\":".$indice['recibido'].",\"atendido\":".$indice['atendido'].",\"despachado\":".$indice['despachado']."};";
            }
        }catch (Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    /*
     * Consulta la información que se muestra en la tabla del Dashboard según el usuario logeado.
     * */
    public function listDashboard($usuario){
        $resultado=null;
        try{
            $resultado="";
            $items=$this->servicios->getAllDashboard($usuario,$this->conexion);
            /* @var $item Dashboard */
            foreach ($items as $item) {
                $nombre_programa = $item->getNombrePrograma();
                $nombre_tipo_requerimiento = $item->getNombreTipoRequerimiento();
                $fecha_solicitud = (new DateTime($item->getFechaSolicitud()))->format('d/m/Y');
                $estado = $item->getEstado();
                $usuario = $item->getUsuario();

                $ic_requerimiento_id = $item->getIcRequerimientoId();
                $provincia=$item->getProvincia();
                $ic_muestra_id = $item->getIcMuestraId();
                $ic_analisis_muestra_id = $item->getIcAnalisisMuestraId();
                $ic_evaluacion_analisis_id = $item->getIcEvaluacionAnalisisId();
                $ic_evaluacion_comite_id = $item->getIcEvaluacionComiteId();
                $ic_cancelado = $item->getCancelado();

                $objId = "{
                            \"ic_requerimiento_id\":\"$ic_requerimiento_id\",
                            \"ic_muestra_id\":\"$ic_muestra_id\",
                            \"ic_analisis_muestra_id\":\"$ic_analisis_muestra_id\",
                            \"ic_evaluacion_analisis_id\":\"$ic_evaluacion_analisis_id\",
                            \"ic_evaluacion_comite_id\":\"$ic_evaluacion_comite_id\"
                            }";

                $resultado .= "<tr>
                            <td>$nombre_programa</td>
                            <td>$provincia</td>
                            <td>$nombre_tipo_requerimiento</td>
                            <td>$fecha_solicitud</td>
                            <td>$estado</td>
                            <td>$usuario</td>
                            <td>$ic_requerimiento_id</td>";
                if ($ic_cancelado == 'N') {
                    $resultado .= "<td class='action-container'>
                                <ul>
                                    <li>
                                        <nav class='accion'>
                                            <a href='#'
                                                id='$objId'
                                                data-rutaAplicacion='inocuidad'
                                                data-opcion='./vistas/icHomeDetalle' 
                                                ondragstart='drag(event)' 
                                                draggable='true' 
                                                data-destino='detalleItem'><i class='material-icons'>slideshow</i></a>
                                        </nav>
                                    </li>
                                    <li>
                                        <nav class='accion'>
                                            <a href='#'
                                                id='$ic_requerimiento_id'
                                                data-rutaAplicacion='inocuidad'
                                                data-opcion='./vistas/icReporte360' 
                                                ondragstart='drag(event)' 
                                                draggable='true' 
                                                data-destino='detalleItem'><i class='material-icons'>insert_drive_file</i></a>
                                        </nav>
                                    </li>
                                    <li>
                                        <a href='#' onclick='cancelarRegistro($ic_requerimiento_id)'>
                                            <i class='material-icons'>delete</i>
                                        </a>
                                    </li>
                                </ul>
                            </td>";
                }else {
                   $resultado .= "<td class='action-container'>
                            <ul>
                                <li>
                                    <nav class='accion'>
                                        <a href='#'
                                            id='$objId'
                                            data-rutaAplicacion='inocuidad'
                                            data-opcion='./vistas/icHomeDetalle' 
                                            data-destino='detalleItem'><i class='material-icons'>slideshow</i></a>
                                    </nav>
                                </li>
                                <li>
                                    <i class='material-icons' style='color: darkred'>cancel</i>
                                </li>
                            </ul>
                        </td>
                    </tr>";
                }
                $resultado .= "</tr>";
            }
            $resultado.="</div>";
        }catch(Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }
}