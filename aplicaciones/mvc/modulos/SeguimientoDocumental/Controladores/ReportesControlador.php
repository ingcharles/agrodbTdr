<?php
/**
 * Controlador Ventanillas
 *
 * Este archivo controla la lógica del negocio del modelo:  VentanillasModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2019-02-13
 * @uses    VentanillasControlador
 * @package SeguimientoDocumental
 * @subpackage Controladores
 */
namespace Agrodb\SeguimientoDocumental\Controladores;

use Agrodb\SeguimientoDocumental\Modelos\TramitesLogicaNegocio;
use Agrodb\SeguimientoDocumental\Modelos\TramitesModelo;
use Agrodb\SeguimientoDocumental\Modelos\ValijasLogicaNegocio;
use Agrodb\SeguimientoDocumental\Modelos\ValijasModelo;
use Agrodb\SeguimientoDocumental\Modelos\UsuariosVentanillaLogicaNegocio;
use Agrodb\SeguimientoDocumental\Modelos\UsuariosVentanillaModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class ReportesControlador extends BaseControlador
{

    private $lNegocioTramites = null;
    private $modeloTramites = null;
    
    private $lNegocioValijas = null;
    private $modeloValijas = null;
    
    private $lNegocioUsuariosVentanilla = null;
    private $modeloUsuariosVentanilla = null;

    private $accion = null;
    private $listaBotones = null;
    
    private $formulario = null;
    private $ruta = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioTramites = new TramitesLogicaNegocio();
        $this->modeloTramites = new TramitesModelo();
        
        $this->lNegocioValijas = new ValijasLogicaNegocio();
        $this->modeloValijas = new ValijasModelo();
        
        $this->lNegocioUsuariosVentanilla = new UsuariosVentanillaLogicaNegocio();
        $this->modeloUsuariosVentanilla = new UsuariosVentanillaModelo();
        
        set_exception_handler(array(
            $this,
            'manejadorExcepciones'
        ));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $this->cargarPanelReportes();
        
        require APP . 'SeguimientoDocumental/vistas/listaOpcionesReportes.php';
    }
    
    /**
     * Método de inicio del controlador
     */
    public function reportesVentanilla()
    {
        $datosUsuario = $this->lNegocioUsuariosVentanilla->buscarDatosUsuarioTecnico($_SESSION['usuario']);
        
        require APP . 'SeguimientoDocumental/vistas/listaOpcionesReportesVentanilla.php';
    }
    
    /**
     * Método de inicio del controlador para reportes
     */
    public function listarReporteValijasAdm()
    {
        $this->cargarPanelReportes();
        
        require APP . 'SeguimientoDocumental/vistas/listaValijasReporteAdministracionVista.php';
    }
    
    /**
     * Método de inicio del controlador para reportes del analista
     */
    public function listarReporteValijasAna()
    {
        $this->cargarPanelReportes();
        
        require APP . 'SeguimientoDocumental/vistas/listaValijasReporteAnalistaVista.php';
    }
    
    /**
     * Método de inicio del controlador para reportes mensuales de valijas del analista
     */
    public function listarReporteMensualValijasAna()
    {
        $this->cargarPanelReportes();
        
        require APP . 'SeguimientoDocumental/vistas/listaValijasReporteMensualAnalistaVista.php';
    }
    
    /**
     * Método de inicio del controlador para reportes
     */
    public function listarReporteTramitesAdm()
    {
        $this->cargarPanelReportes();
        
        require APP . 'SeguimientoDocumental/vistas/listaTramitesReporteAdministracionVista.php';
    }
    
    /**
     * Método de inicio del controlador para reportes del analista
     */
    public function listarReporteTramitesAna()
    {
        $this->cargarPanelReportes();
        
        require APP . 'SeguimientoDocumental/vistas/listaTramitesReporteAnalistaVista.php';
    }
    
    /**
     * Método de inicio del controlador para reportes
     */
    public function listarReportePorcentajeTramitesAdm()
    {
        $this->cargarPanelReportes();
        
        require APP . 'SeguimientoDocumental/vistas/listaPorcentajeTramitesReporteAdministracionVista.php';
    }
    
    /**
     * Método de inicio del controlador para reportes
     */
    public function listarReportePorcentajeValijasAdm()
    {
        $this->cargarPanelReportes();
        
        require APP . 'SeguimientoDocumental/vistas/listaPorcentajeValijasReporteAdministracionVista.php';
    }

    /**
     * Construye el código HTML para desplegar panel de busqueda para los reportes
     */
    public function cargarPanelReportes()
    {
        $datosUsuario = $this->lNegocioUsuariosVentanilla->buscarDatosUsuarioTecnico($_SESSION['usuario']);
        
        $this->panelBusquedaTramitesReporteAdmin = '<table class="filtro" style="width: 450px;">
                                                        <tbody>
                                                            <tr>
                                                                <th colspan="2">Buscar:</th>
                                                            </tr>
                                        					<tr  style="width: 100%;">
                                        						<td >Ventanilla: </td>
                                        						<td style="width: 100%;">
                                                                    <select id="idVentanillaFiltro" name="idVentanillaFiltro" style="width: 100%;" required>
                                                                        <option value="">Todas</option>' .
                                                                        $this->comboVentanillasSeguimientoDocumental() .
                                                                        '</select>
                                        						</td>
                                        					</tr>
                                                            <tr  style="width: 100%;">
                                        						<td >Unidad de Destino: </td>
                                        						<td style="width: 100%;">
                                                                    <select id="idUnidadDestinoFiltro" name="idUnidadDestinoFiltro" style="width: 100%;" required>
                                                                        <option value="">Todas</option>' .
                                                                        $this->comboAreasCategoriaNacional('') .
                                                                        '</select>
                                        						</td>
                                        					</tr>
                                                            <tr  style="width: 100%;">
                                        						<td >Fecha Inicio: </td>
                                        						<td>
                                        							<input id="fechaInicio" type="text" name="fechaInicio" style="width: 100%" readonly="readonly">
                                        						</td>
                                        					</tr>
                                                            <tr  style="width: 100%;">
                                        						<td >Fecha Fin: </td>
                                        						<td>
                                        							<input id="fechaFin" type="text" name="fechaFin" style="width: 100%" readonly="readonly">
                                        						</td>
                                        					</tr>
                                                            <tr  style="width: 100%;">
                                        						<td >Estado del trámite: </td>
                                        						<td style="width: 100%;">
                                                                    <select id="estadoTramite" name="estadoTramite" style="width: 100%;" required>' . $this->comboEstadosTramites() . '</select>
                                        						</td>
                                        					</tr>
                                                                        
                                                            <tr></tr>
                                        					<tr>
                                        						<td colspan="3">
                                        							<button type="button" id="btnFiltrar" data-toggle="modal">Generar Reporte</button>
                                        						</td>
                                                                        
                                        					</tr>
                                        				</tbody>
                                        			</table>';
                                                                        
        $this->panelBusquedaValijasReporteAdmin = '<table class="filtro" style="width: 450px;">
                                                        <tbody>
                                                            <tr>
                                                                <th colspan="2">Buscar:</th>
                                                            </tr>
                                        					<tr  style="width: 100%;">
                                        						<td >Ventanilla: </td>
                                        						<td style="width: 100%;">
                                                                    <select id="idVentanillaFiltro" name="idVentanillaFiltro" style="width: 100%;" >
                                                                        <option value="">Todas</option>' .
                                                                        $this->comboVentanillasSeguimientoDocumental() .
                                                                        '</select>
                                        						</td>
                                        					</tr>
                                                            <tr  style="width: 100%;">
                                        						<td >Código de Guía: </td>
                                        						<td>
                                        							<input id="numGuia" type="text" name="numGuia" style="width: 100%" >
                                        						</td>
                                        					</tr>
                                                            <tr  style="width: 100%;">
                                        						<td >Fecha Inicio: </td>
                                        						<td>
                                        							<input id="fechaInicio" type="text" name="fechaInicio" style="width: 100%" readonly="readonly">
                                        						</td>
                                        					</tr>
                                                            <tr  style="width: 100%;">
                                        						<td >Fecha Fin: </td>
                                        						<td>
                                        							<input id="fechaFin" type="text" name="fechaFin" style="width: 100%" readonly="readonly">
                                        						</td>
                                        					</tr>
                                                            <tr  style="width: 100%;">
                                        						<td >Estado del trámite: </td>
                                        						<td style="width: 100%;">
                                                                    <select id="estadoEntrega" name="estadoEntrega" style="width: 100%;" required>' . $this->comboEnviadoCerrado() . '</select>
                                        						</td>
                                        					</tr>
                                                                        
                                                            <tr></tr>
                                        					<tr>
                                        						<td colspan="3">
                                        							<button type="button" id="btnFiltrar" data-toggle="modal">Generar Reporte</button>
                                        						</td>
                                                                        
                                        					</tr>
                                        				</tbody>
                                        			</table>';
        
                                                                        
            $this->panelPorcentajeValijasReporteAdmin = '<form id="formulario" data-rutaAplicacion="' . URL_MVC_FOLDER . 'SeguimientoDocumental" data-opcion="Reportes/mostrarReportePorcentajeValijas" data-destino="detalleItem" method="post">
                                                              <table class="filtro" style="width: 450px;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <th colspan="2">Buscar:</th>
                                                                        </tr>
                                                    					<tr  style="width: 100%;">
                                                    						<td >Ventanilla: </td>
                                                    						<td style="width: 100%;">
                                                                                <select id="idVentanillaFiltro" name="idVentanillaFiltro" style="width: 100%;" >
                                                                                    <option value="">Todas</option>' .
                                                                                    $this->comboVentanillasSeguimientoDocumental() .
                                                                                    '</select>
                                                    						</td>
                                                    					</tr>
                                                                        <tr  style="width: 100%;">
                                                    						<td >Fecha Inicio: </td>
                                                    						<td>
                                                    							<input id="fechaInicio" type="text" name="fechaInicio" style="width: 100%" readonly="readonly">
                                                    						</td>
                                                    					</tr>
                                                                        <tr  style="width: 100%;">
                                                    						<td >Fecha Fin: </td>
                                                    						<td>
                                                    							<input id="fechaFin" type="text" name="fechaFin" style="width: 100%" readonly="readonly">
                                                    						</td>
                                                    					</tr>
                                                                                    
                                                                        <tr></tr>
                                                    					<tr>
                                                    						<td colspan="3">
                                                    							<button type="submit" class="generarReporte">Generar Reporte</button>
                                                    						</td>      
                                                    					</tr>
                                                    				</tbody>
                                                    			</table>
                                                            </form>';
           
             $this->panelPorcentajeTramitesReporteAdmin = '<form id="formulario" data-rutaAplicacion="' . URL_MVC_FOLDER . 'SeguimientoDocumental" data-opcion="Reportes/mostrarReportePorcentaje" data-destino="detalleItem" method="post">
                                                              <table class="filtro" style="width: 450px;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <th colspan="2">Buscar:</th>
                                                                        </tr>
                                                    					<tr  style="width: 100%;">
                                                    						<td >Ventanilla: </td>
                                                    						<td style="width: 100%;">
                                                                                <select id="idVentanillaFiltro" name="idVentanillaFiltro" style="width: 100%;" >
                                                                                    <option value="">Todas</option>' .
                                                                                    $this->comboVentanillasSeguimientoDocumental() .
                                                                                    '</select>
                                                    						</td>
                                                    					</tr>
                                                                        <tr  style="width: 100%;">
                                                    						<td >Fecha Inicio: </td>
                                                    						<td>
                                                    							<input id="fechaInicio" type="text" name="fechaInicio" style="width: 100%" readonly="readonly">
                                                    						</td>
                                                    					</tr>
                                                                        <tr  style="width: 100%;">
                                                    						<td >Fecha Fin: </td>
                                                    						<td>
                                                    							<input id="fechaFin" type="text" name="fechaFin" style="width: 100%" readonly="readonly">
                                                    						</td>
                                                    					</tr>
                                                                                        
                                                                        <tr></tr>
                                                    					<tr>
                                                    						<td colspan="3">
                                                    							<button type="submit" class="generarReporte">Generar Reporte</button>
                                                    						</td>
                                                    					</tr>
                                                    				</tbody>
                                                    			</table>
                                                            </form>';
                                                                                    
           $this->panelBusquedaTramitesReporteAnalista = '<table class="filtro" style="width: 450px;">
                                                        <tbody>
                                                            <tr>
                                                                <th colspan="2">Buscar:</th>
                                                            </tr>
                                        					<tr  style="width: 100%;">
                                        						<td >Ventanilla: </td>
                                        						<td style="width: 100%;">'. $datosUsuario['ventanillaUsuario'] .'
                                                                    <input type="hidden" id="idVentanillaFiltro" name="idVentanillaFiltro" value="'.$datosUsuario['idVentanilla'].'" style="width: 100%" readonly="readonly">
                                        						</td>
                                        					</tr>
                                                            <tr  style="width: 100%;">
                                        						<td >Unidad de Destino: </td>
                                        						<td style="width: 100%;">
                                                                    <select id="idUnidadDestinoFiltro" name="idUnidadDestinoFiltro" style="width: 100%;" required>
                                                                        <option value="">Todas</option>' .
                                                                        $this->comboAreasCategoriaNacional('') .
                                                                        '</select>
                                        						</td>
                                        					</tr>
                                                            <tr  style="width: 100%;">
                                        						<td >Fecha Inicio: </td>
                                        						<td>
                                        							<input id="fechaInicio" type="text" name="fechaInicio" style="width: 100%" readonly="readonly">
                                        						</td>
                                        					</tr>
                                                            <tr  style="width: 100%;">
                                        						<td >Fecha Fin: </td>
                                        						<td>
                                        							<input id="fechaFin" type="text" name="fechaFin" style="width: 100%" readonly="readonly">
                                        						</td>
                                        					</tr>
                                                            <tr  style="width: 100%;">
                                        						<td >Estado del trámite: </td>
                                        						<td style="width: 100%;">
                                                                    <select id="estadoTramite" name="estadoTramite" style="width: 100%;" required>' . $this->comboEstadosTramites() . '</select>
                                        						</td>
                                        					</tr>
                                                                        
                                                            <tr></tr>
                                        					<tr>
                                        						<td colspan="3">
                                        							<button type="button" id="btnFiltrar" data-toggle="modal">Generar Reporte</button>
                                        						</td>
                                                                        
                                        					</tr>
                                        				</tbody>
                                        			</table>';
                                                                        
              $this->panelBusquedaValijasReporteAnalista = '<table class="filtro" style="width: 450px;">
                                                            <tbody>
                                                                <tr>
                                                                    <th colspan="2">Buscar:</th>
                                                                </tr>
                                            					<tr  style="width: 100%;">
                                            						<td >Ventanilla: </td>
                                            					<td style="width: 100%;">'. $datosUsuario['ventanillaUsuario'] .'
                                                                    <input type="hidden" id="idVentanillaFiltro" name="idVentanillaFiltro" value="'.$datosUsuario['idVentanilla'].'" style="width: 100%" readonly="readonly">
                                        						</td>
                                            					</tr>
                                                                <tr  style="width: 100%;">
                                            						<td >Código de Guía: </td>
                                            						<td>
                                            							<input id="numGuia" type="text" name="numGuia" style="width: 100%" >
                                            						</td>
                                            					</tr>
                                                                <tr  style="width: 100%;">
                                            						<td >Fecha Inicio: </td>
                                            						<td>
                                            							<input id="fechaInicio" type="text" name="fechaInicio" style="width: 100%" readonly="readonly">
                                            						</td>
                                            					</tr>
                                                                <tr  style="width: 100%;">
                                            						<td >Fecha Fin: </td>
                                            						<td>
                                            							<input id="fechaFin" type="text" name="fechaFin" style="width: 100%" readonly="readonly">
                                            						</td>
                                            					</tr>
                                                                <tr  style="width: 100%;">
                                            						<td >Estado del trámite: </td>
                                            						<td style="width: 100%;">
                                                                        <select id="estadoEntrega" name="estadoEntrega" style="width: 100%;" required>' . $this->comboEnviadoCerrado() . '</select>
                                            						</td>
                                            					</tr>
                                                                            
                                                                <tr></tr>
                                            					<tr>
                                            						<td colspan="3">
                                            							<button type="button" id="btnFiltrar" data-toggle="modal">Generar Reporte</button>
                                            						</td>
                                                                            
                                            					</tr>
                                            				</tbody>
                                            			</table>';
    }
    
    /**
     * Combo de estados para trámites
     *
     * @param
     *            $respuesta
     * @return string
     */
    public function comboEstadosTramites($opcion = null)
    {
        $combo = "";
        
        if ($opcion == "Ingresado") {
        	$combo .= '<option value="Todos">Todos</option>';
            $combo .= '<option value="Ingresado" selected="selected">Ingresado</option>';
            $combo .= '<option value="Despachado">Despachado</option>';
            $combo .= '<option value="Seguimiento">Seguimiento</option>';
            $combo .= '<option value="Cerrado">Cerrado</option>';
        } else if ($opcion == "Despachado") {
        	$combo .= '<option value="Todos">Todos</option>';
            $combo .= '<option value="Ingresado" >Ingresado</option>';
            $combo .= '<option value="Despachado" selected="selected">Despachado</option>';
            $combo .= '<option value="Seguimiento">Seguimiento</option>';
            $combo .= '<option value="Cerrado">Cerrado</option>';
        } else if ($opcion == "Seguimiento") {
        	$combo .= '<option value="Todos">Todos</option>';
            $combo .= '<option value="Ingresado" >Ingresado</option>';
            $combo .= '<option value="Despachado">Despachado</option>';
            $combo .= '<option value="Seguimiento" selected="selected">Seguimiento</option>';
            $combo .= '<option value="Cerrado">Cerrado</option>';
        } else if ($opcion == "Cerrado") {
        	$combo .= '<option value="Todos">Todos</option>';
            $combo .= '<option value="Ingresado" >Ingresado</option>';
            $combo .= '<option value="Despachado">Despachado</option>';
            $combo .= '<option value="Seguimiento">Seguimiento</option>';
            $combo .= '<option value="Cerrado" selected="selected">Cerrado</option>';
        } else {
        	$combo .= '<option value="Todos" selected="selected">Todos</option>';
            $combo .= '<option value="Ingresado">Ingresado</option>';
            $combo .= '<option value="Despachado">Despachado</option>';
            $combo .= '<option value="Seguimiento">Seguimiento</option>';
            $combo .= '<option value="Cerrado">Cerrado</option>';
        }
        
        return $combo;
    }
    
    /**
     * Combo de dos estados ENVIADO/RECIBIDO
     *
     * @param $respuesta
     * @return string
     */
    public function comboEnviadoCerrado($opcion=null)
    {
        $combo = "";
        if ($opcion == "Enviado") {
        	$combo .= '<option value="Todos">Todos</option>';
            $combo .= '<option value="Enviado" selected="selected">Enviado</option>';
            $combo .= '<option value="Entregado">Entregado</option>';
        } else if ($opcion == "Entregado") {
        	$combo .= '<option value="Todos">Todos</option>';
            $combo .= '<option value="Enviado" >Enviado</option>';
            $combo .= '<option value="Entregado" selected="selected">Entregado</option>';
        } else {
        	$combo .= '<option value="Todos" selected="selected">Todos</option>';
            $combo .= '<option value="Enviado">Enviado</option>';
            $combo .= '<option value="Entregado">Entregado</option>';
        }
        return $combo;
    }
    
    /**
     * Construye el combo para desplegar la lista de Ventanilla
     */
    public function comboVentanillasSeguimientoDocumental($idVentanilla=null)
    {
        $comboVentanilla = "";
        $ventanilla = $this->lNegocioUsuariosVentanilla->buscarVentanillas();
        
        foreach ($ventanilla as $item)
        {
            if ($idVentanilla == $item['id_ventanilla'])
            {
                $comboVentanilla .= '<option value="' . $item->id_ventanilla . '" data-unidad="'. $item->unidad_destino .'" selected >' . $item->nombre . '</option>';
            } else
            {
                $comboVentanilla .= '<option value="' . $item->id_ventanilla . '" data-unidad="'. $item->unidad_destino .'">' . $item->nombre . '</option>';
            }
        }
        return $comboVentanilla;
    }
    
    /**
     * Método para listar los trámites registrados
     */
    public function listarReporteTramitesAdministradorFiltrados()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        $idVentanillaFiltro = $_POST["idVentanillaFiltro"];
        $idUnidadDestinoFiltro = $_POST["idUnidadDestinoFiltro"];
        $fechaInicio = $_POST["fechaInicio"];
        $fechaFin = $_POST["fechaFin"];
        $estadoTramite = $_POST["estadoTramite"];
        
        $arrayParametros = array(
            'id_ventanilla' => $idVentanillaFiltro,
            'id_unidad_destino' => $idUnidadDestinoFiltro,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estado_tramite' => $estadoTramite
        );
        
        $tramites = $this->lNegocioTramites->buscarTramitesNacionalXFiltro($arrayParametros);
        
        $this->tablaHtmlTramitesReporteAdministrador($tramites);
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        
        echo json_encode(array('estado' => $estado, 'mensaje' => $mensaje, 'contenido' => $contenido));
    }
    
    /** TEMPORAL
     * Construye el código HTML para desplegar la lista de Trámites
     */
    public function tablaHtmlTramitesReporteAdministrador($tabla)
    {
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr>
                	<td>' . $fila['id_tramite'] . '</td>
                    <td>' . $fila['numero_tramite'] . '</td>
                    <td>' . $fila['fecha_creacion'] . '</td>
                    <td>' . $fila['ventanilla'] . '</td>
                    <td>' . $fila['identificador'] . ' - ' . $fila['nombre'] . ' ' . $fila['apellido'] . '</td>
                    <td>' . $fila['remitente'] . '</td>
                    <td>' . $fila['oficio_memo'] . '</td>
                    <td>' . $fila['factura'] . '</td>
                    <td>' . $fila['guia_quipux'] . '</td>
                    <td>' . $fila['asunto'] . '</td>
                    <td>' . $fila['anexos'] . '</td>
                    <td>' . $fila['destinatario'] . '</td>
                    <td>' . $fila['unidad_destino'] . '</td>
                    <td>' . $fila['quipux_agr'] . '</td>
                    <td>' . $fila['derivado'] . '</td>
                    <td>' . $fila['estado_tramite'] . '</td>
                    <td>' . $fila['documentos_entregados'] . '</td>
                    <td>' . ($fila['fecha_entrega']!=null?date('Y-m-d',strtotime($fila['fecha_entrega'])):'') . '</td>
                    <td>' . $fila['observaciones'] . '</td>
                    <td>' . $fila['origen_tramite'] . '</td>
                </tr>'
            );
        }
    }
    
    /**
     * Método para listar las valijas registradas
     */
    public function listarReporteValijasAdministradorFiltradas()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        $idVentanillaFiltro = $_POST["idVentanillaFiltro"];
        $numGuia = $_POST["numGuia"];
        $fechaInicio = $_POST["fechaInicio"];
        $fechaFin = $_POST["fechaFin"];
        $estadoEntrega = $_POST["estadoEntrega"];
        
        $arrayParametros = array(
            'id_ventanilla' => $idVentanillaFiltro, 
            'guia_correo' => $numGuia,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estado_entrega' => $estadoEntrega);
        
        $valijas = $this->lNegocioValijas->buscarValijasNacionalXFiltro($arrayParametros);
        
        $this->tablaHtmlValijasReporteAdministrador($valijas);
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        
        echo json_encode(array('estado' => $estado, 'mensaje' => $mensaje, 'contenido' => $contenido));
    }
    
    /**
     * Construye el código HTML para desplegar la lista de Valijas
     */
    public function tablaHtmlValijasReporteAdministrador($tabla)
    {
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr>
                	<td>' . $fila['id_valija'] . '</td>
                    <td>' . $fila['numero_valija'] . '</td>
                    <td>' . $fila['fecha_creacion'] . '</td>
                    <td>' . $fila['ventanilla'] . '</td>
                    <td>' . $fila['identificador'] . ' - ' . $fila['nombre'] . ' ' . $fila['apellido'] . '</td>
                    <td>' . $fila['guia_correo'] . '</td>
                    <td>' . $fila['unidad_origen'] . '</td>
                    <td>' . $fila['remitente'] . '</td>
                    <td>' . $fila['destinatario'] . '</td>
                    <td>' . $fila['direccion'] . '</td>
                    <td>' . $fila['telefono'] . '</td>
                    <td>' . $fila['pais'] . '</td>
                    <td>' . $fila['provincia'] . '</td>
                    <td>' . $fila['canton'] . '</td>
                    <td>' . $fila['referencia'] . '</td>
                    <td>' . $fila['email'] . '</td>
                    <td>' . $fila['descripcion'] . '</td>
                    <td>' . $fila['estado_entrega'] . '</td>
                    <td>' . $fila['nombre_entrega'] . '</td>
                    <td>' . ($fila['fecha_entrega']!=''?date('Y-m-d', strtotime($fila['fecha_entrega'])):'') . '</td>
                    <td>' . $fila['observaciones'] . '</td>
                </tr>'
            );
        }
    }
    
    /**
     * Método para listar las valijas registradas
     */
    public function listarReporteMensualValijasAdministradorFiltradas()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        $idVentanillaFiltro = $_POST["idVentanillaFiltro"];
        $numGuia = $_POST["numGuia"];
        $fechaInicio = $_POST["fechaInicio"];
        $fechaFin = $_POST["fechaFin"];
        $estadoEntrega = $_POST["estadoEntrega"];
        
        $arrayParametros = array(
            'id_ventanilla' => $idVentanillaFiltro,
            'guia_correo' => $numGuia,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estado_entrega' => $estadoEntrega);
        
        $valijas = $this->lNegocioValijas->buscarValijasNacionalXFiltro($arrayParametros);
        
        $this->tablaHtmlValijasReporteMensualAdministrador($valijas);
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        
        echo json_encode(array('estado' => $estado, 'mensaje' => $mensaje, 'contenido' => $contenido));
    }
    
    /**
     * Construye el código HTML para desplegar la lista de Valijas
     */
    public function tablaHtmlValijasReporteMensualAdministrador($tabla)
    {
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr>
                	<td>' . $fila['id_valija'] . '</td>
                    <td>' . $fila['guia_correo'] . '</td>
                    <td>' . date('Y-m-d', strtotime($fila['fecha_creacion'])) . '</td>
                    <td>' . $fila['destinatario'] . '</td>
                    <td>' . $fila['descripcion'] . '</td>
                    <td>' . $fila['canton'] . '</td>
                    <td>' . $fila['unidad_origen'] . ' - ' . $fila['remitente'] . '</td>
                    <td>' . $fila['destinatario'] . '</td>
                    <td>' . ($fila['fecha_entrega']!=''?date('Y-m-d', strtotime($fila['fecha_entrega'])):'') . '</td>
                </tr>'
            );
        }
    }
    
    /**
     * Método para listar los trámites registrados
     */
    public function listarReportePorcentajeTramitesAdministradorFiltrados()
    {
        $idVentanillaFiltro = $_POST["idVentanillaFiltro"];
        $fechaInicio = $_POST["fechaInicio"];
        $fechaFin = $_POST["fechaFin"];
        
        $arrayParametros = array(
            'id_ventanilla' => $idVentanillaFiltro,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        );
        
        return $this->lNegocioTramites->porcentajeTramitesAtendidosIngresados($arrayParametros);
    }
    
    /**
     * Método para listar las valijas registradas
     */
    public function listarReportePorcentajeValijasAdministradorFiltrados()
    {
        $idVentanillaFiltro = $_POST["idVentanillaFiltro"];
        $fechaInicio = $_POST["fechaInicio"];
        $fechaFin = $_POST["fechaFin"];
        
        $arrayParametros = array(
            'id_ventanilla' => $idVentanillaFiltro,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        );
        
        return $this->lNegocioValijas->porcentajeValijasAtendidasIngresadas($arrayParametros);
    }
    
    /**
     * Método para desplegar el reporte de Trámites
     */
    public function mostrarReporteTramites()
    {
        $this->formulario = $this->listarReporteTramitesAdministradorFiltrados();
    }
    
    /**
     * Método para desplegar el reporte de Valijas
     */
    public function mostrarReporteValijas()
    {
        $this->formulario = $this->listarReporteValijasAdministradorFiltradas();
    }
    
    /**
     * Método para desplegar el reporte mensual de Valijas
     */
    public function mostrarReporteMensualValijas()
    {
        $this->formulario = $this->listarReporteMensualValijasAdministradorFiltradas();
    }
    
    /**
     * Método para desplegar el reporte de porcentajes
     */
    public function mostrarReportePorcentaje()
    {
        $this->formulario = $this->listarReportePorcentajeTramitesAdministradorFiltrados();
        
        require APP . 'SeguimientoDocumental/vistas/reportePorcentaje.php';
    }
    
    /**
     * Método para desplegar el reporte de porcentajes de valijas
     */
    public function mostrarReportePorcentajeValijas()
    {
        $this->formulario = $this->listarReportePorcentajeValijasAdministradorFiltrados();
        
        require APP . 'SeguimientoDocumental/vistas/reportePorcentajeValijas.php';
    }
    
    /**
     * Método para desplegar el certificado
     */
    public function mostrarExcel()
    {        
        $this->formulario = $this->listarReportePorcentajeTramitesAdministradorFiltrados();
        
        require APP . 'SeguimientoDocumental/vistas/reporteExcelTramites.php';
    }
}