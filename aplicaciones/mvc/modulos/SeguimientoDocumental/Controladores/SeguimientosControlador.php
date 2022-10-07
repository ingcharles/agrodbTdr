<?php
/**
 * Controlador Seguimientos
 *
 * Este archivo controla la lógica del negocio del modelo:  SeguimientosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2019-01-15
 * @uses    SeguimientosControlador
 * @package SeguimientoDocumental
 * @subpackage Controladores
 */
namespace Agrodb\SeguimientoDocumental\Controladores;

use Agrodb\SeguimientoDocumental\Modelos\SeguimientosLogicaNegocio;
use Agrodb\SeguimientoDocumental\Modelos\SeguimientosModelo;
use Agrodb\SeguimientoDocumental\Modelos\TramitesLogicaNegocio;
use Agrodb\SeguimientoDocumental\Modelos\TramitesModelo;
use Agrodb\SeguimientoDocumental\Modelos\UsuariosVentanillaLogicaNegocio;
use Agrodb\SeguimientoDocumental\Modelos\UsuariosVentanillaModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class SeguimientosControlador extends BaseControlador
{

    private $lNegocioSeguimientos = null;
    private $modeloSeguimientos = null;
    
    private $lNegocioTramites = null;
    private $modeloTramites = null;
    
    private $lNegocioUsuariosVentanilla = null;    
    private $modeloUsuariosVentanilla = null;
    
    private $accion = null;    
    private $formulario = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioSeguimientos = new SeguimientosLogicaNegocio();
        $this->modeloSeguimientos = new SeguimientosModelo();
        
        $this->lNegocioTramites = new TramitesLogicaNegocio();
        $this->modeloTramites = new TramitesModelo();
        
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
        $this->cargarPanelTramitesSeguimiento();

        require APP . 'SeguimientoDocumental/vistas/listaSeguimientosVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Seguimientos";
        require APP . 'SeguimientoDocumental/vistas/formularioSeguimientosVista.php';
    }

    /**
     * Método para registrar en la base de datos -Seguimientos
     */
    public function guardar()
    {
        $this->lNegocioSeguimientos->guardar($_POST);
        
        //Guardar cambio de estado en el registro
        $arrayParametros = array(
            'id_tramite' => $_POST['id_tramite'],
            'estado_tramite' => 'Seguimiento',
            'id_unidad_destino_actual' => $_POST['id_unidad_destino'],
            'unidad_destino_actual' => $_POST['unidad_destino_actual']
        );
        $this->lNegocioTramites->guardar($arrayParametros);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Seguimientos
     */
    public function editar()
    {
        $this->accion = "Editar Seguimientos";
        $this->formulario = 'abrir';
        
        $this->modeloTramites = $this->lNegocioTramites->buscar($_POST["id"]);
        
        $datosUsuario = $this->obtenerDatosUsuarioRegistro($this->modeloTramites->getIdentificador(), $this->modeloTramites->getIdVentanilla());
        
        $this->modeloTramites->setNombreVentanilla($datosUsuario['ventanillaUsuario']);
        $this->modeloTramites->setNombreEmpleado($datosUsuario['nombreUsuarioVentanilla']);
        
        require APP . 'SeguimientoDocumental/vistas/formularioSeguimientosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Seguimientos
     */
    public function borrar()
    {
        $this->lNegocioSeguimientos->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Seguimientos
     */
    public function tablaHtmlTramitesSeguimientos($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_tramite'] . '"
                    		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'SeguimientoDocumental\seguimientos"
                    		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                    		  data-destino="detalleItem">
                    		  <td>' . ++ $contador . '</td>
                    		  <td style="white - space:nowrap; "><b>' . $fila['numero_tramite'] . '</b></td>
                        <td>' . $fila['asunto'] . '</td>
                        <td>' . ($fila['fecha_creacion']!=null?date('Y-m-d',strtotime($fila['fecha_creacion'])):'') . '</td>
                        <td>' . $fila['remitente'] . '</td>
                        <td>' . $fila['destinatario'] . '</td>
                    </tr>'
                );
            }
        }
    }
    
    /**
     * Método para listar las valijas registradas
     */
    public function obtenerDatosUsuarioTecnico($identificador)
    {
    	return $this->lNegocioUsuariosVentanilla->buscarDatosUsuarioTecnico($identificador);
    }
    
    /**
     * Método para listar las valijas registradas
     */
    public function obtenerDatosUsuarioRegistro($identificador, $idVentanilla)
    {
    	return $this->lNegocioUsuariosVentanilla->buscarDatosUsuarioRegistro($identificador, $idVentanilla);
    }
    
    /**
     * Construye el código HTML para desplegar panel de busqueda para Trámites con seguimiento
     */
    public function cargarPanelTramitesSeguimiento()
    {
        $datosUsuario = $this->obtenerDatosUsuarioTecnico($_SESSION['usuario']);
        
        $this->panelBusquedaTramitesSeguimiento = '<form id="filtrar" action="aplicaciones/mvc/SeguimientoDocumental/Seguimientos/exportarListaExcel" target="_blank" method="post">
										<table class="filtro" style="width: 450px;">
                                            <input type="hidden" id="idVentanillaFiltro" name="idVentanillaFiltro" value="' . $datosUsuario['idVentanilla'] . '" readonly="readonly" >
                            				<tbody>
                                                <tr>
                                                    <th colspan="2">Buscar:</th>
                                                </tr>
                            					<tr  style="width: 100%;">
                            						<td >Número de Trámite: </td>
                            						<td>
                            							<input id="numTramite" type="text" name="numTramite" style="width: 100%" >
                            						</td>
                            					</tr>
                                                <tr  style="width: 100%;">
                            						<td >Remitente: </td>
                            						<td>
                            							<input id="nombreRemitente" type="text" name="nombreRemitente" style="width: 100%" >
                            						</td>
                            					</tr>
                                                <tr  style="width: 100%;">
                            						<td >Destinatario: </td>
                            						<td>
                            							<input id="nombreDestinatario" type="text" name="nombreDestinatario" style="width: 100%" >
                            						</td>
                            					</tr>
                                                <tr  style="width: 100%;">
                            						<td >No. Quipux: </td>
                            						<td>
                            							<input id="numQuipux" type="text" name="numQuipux" style="width: 100%" >
                            						</td>
                            					</tr>
                                                <tr  style="width: 100%;">
                            						<td >Factura: </td>
                            						<td>
                            							<input id="numFactura" type="text" name="numFactura" style="width: 100%" >
                            						</td>
                            					</tr>
												<tr  style="width: 100%;">
													<td style="width: 30%;">Unidad de Destino: </td>
													<td>
														<select id="idUnidadDestinoFiltro" name="idUnidadDestinoFiltro" style="width: 100%;">
															<option value="">Todas</option>
															'.$this->comboAreasCategoriaNacional('') .'
														</select>
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
                            						<td>
                                                        <select id="estadoTramite" name="estadoTramite" style="width: 100%;" required>' . $this->comboEstadosTramitesSeguimiento() . '</select>
                            						</td>
                            					</tr>
                            					<tr>
													<td colspan="1">
                            							<button id="btnExcel">Exportar xls</button>
                            						</td>
                            						<td colspan="2" style="text-align: end;">
                            							<button id="btnFiltrar">Filtrar lista</button>
                            						</td>
                            					</tr>
                            				</tbody>
                            			</table></form>';
    }
    
    /**
     * Combo de estados para trámites en etapa de seguimiento
     *
     * @param
     *            $respuesta
     * @return string
     */
    public function comboEstadosTramitesSeguimiento($opcion = null)
    {
        $combo = "";
        if ($opcion == "Despachado") {
            $combo .= '<option value="Despachado" selected="selected">Despachado</option>';
            $combo .= '<option value="Seguimiento">Seguimiento</option>';
            $combo .= '<option value="Cerrado">Cerrado</option>';
        } else if ($opcion == "Seguimiento") {
            $combo .= '<option value="Despachado">Despachado</option>';
            $combo .= '<option value="Seguimiento" selected="selected">Seguimiento</option>';
            $combo .= '<option value="Cerrado">Cerrado</option>';
        } else if ($opcion == "Cerrado") {
           $combo .= '<option value="Despachado">Despachado</option>';
            $combo .= '<option value="Seguimiento">Seguimiento</option>';
            $combo .= '<option value="Cerrado" selected="selected">Cerrado</option>';
        }else {
            $combo .= '<option value="Despachado">Despachado</option>';
            $combo .= '<option value="Seguimiento">Seguimiento</option>';
            $combo .= '<option value="Cerrado">Cerrado</option>';
        }
        return $combo;
    }
    
    /**
     * Método para listar los trámites registrados
     */
    public function listarTramitesSeguimientoFiltrados()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        $idVentanillaFiltro = $_POST["idVentanillaFiltro"];
        $numTramite = $_POST["numTramite"];
        $nombreRemitente = $_POST["nombreRemitente"];
        $nombreDestinatario = $_POST["nombreDestinatario"];
        $numQuipux = $_POST["numQuipux"];
        $factura = $_POST["numFactura"];
        $idUnidadDestinoFiltro = $_POST["idUnidadDestinoFiltro"];
        $fechaInicio = $_POST["fechaInicio"];
        $fechaFin = $_POST["fechaFin"];
        $estadoTramite = $_POST["estadoTramite"];
        
        $arrayParametros = array(
            'id_ventanilla' => $idVentanillaFiltro,
            'numero_tramite' => $numTramite,
            'remitente' => $nombreRemitente,
            'destinatario' => $nombreDestinatario,
            'quipux_agr' => $numQuipux,
            'factura' => $factura,
        	'id_unidad_destino' => $idUnidadDestinoFiltro,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estado_tramite' => $estadoTramite
        );
        $tramites = $this->lNegocioTramites->buscarTramitesXFiltro($arrayParametros);
        
        $this->tablaHtmlTramitesSeguimientos($tramites);
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        
        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }
    
    /**
     * Método para listar los seguimientos registrados
     */
    public function construirSeguimientos($idTramite)
    {
        $listaSeguimientos = $this->lNegocioSeguimientos->buscarSeguimientosXTramite($idTramite);

        $this->listaSeguimientos = '<table style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Recibido/Entregado por</th>
                                                <th>Dirección de Destino</th>
                                                <th>Observaciones</th>
                                            </tr>
                                        </thead>';
        
        foreach ($listaSeguimientos as $fila) {
            
            $this->listaSeguimientos .=
                    '<tr>
                        <td style="width: 15%;">' . date('Y-m-d',strtotime($fila['fecha'])). '</td>
                        <td style="width: 25%;">' . ($fila['persona_recibe'] != '' ? $fila['persona_recibe'] : '') . '</td>
                        <td style="width: 30%;">' . ($fila['unidad_destino'] != '' ? $fila['unidad_destino'] : '') . '</td>
                        <td style="width: 30%;">' . ($fila['observaciones_seguimiento'] != '' ? $fila['observaciones_seguimiento'] : '') . '</td>
                    </tr>';
        }
        
        $this->listaSeguimientos .= '</table>';
        
        echo $this->listaSeguimientos;
    }
    
    public function exportarListaExcel() {
    	
	    $idVentanillaFiltro = $_POST["idVentanillaFiltro"];
	    $numTramite = $_POST["numTramite"];
	    $nombreRemitente = $_POST["nombreRemitente"];
	    $nombreDestinatario = $_POST["nombreDestinatario"];
	    $numQuipux = $_POST["numQuipux"];
	    $factura = $_POST["numFactura"];
	    $idUnidadDestinoFiltro = $_POST["idUnidadDestinoFiltro"];
	    $fechaInicio = $_POST["fechaInicio"];
	    $fechaFin = $_POST["fechaFin"];
	    $estadoTramite = $_POST["estadoTramite"];
	    
	    $arrayParametros = array(
	    	'id_ventanilla' => $idVentanillaFiltro,
	    	'numero_tramite' => $numTramite,
	    	'remitente' => $nombreRemitente,
	    	'destinatario' => $nombreDestinatario,
	    	'quipux_agr' => $numQuipux,
	    	'factura' => $factura,
	    	'id_unidad_destino' => $idUnidadDestinoFiltro,
	    	'fecha_inicio' => $fechaInicio,
	    	'fecha_fin' => $fechaFin,
	    	'estado_tramite' => $estadoTramite
	    );
	    
	    //$tramites = $this->lNegocioTramites->buscarTramitesXFiltro($arrayParametros);
	    $tramites = $this->lNegocioTramites->buscarTramitesSeguimientoXFiltro($arrayParametros);
	    $this->lNegocioTramites->exportarArchivoExcelTramiteSeguimiento($tramites);
    }
    
    public function exportarSeguimientoExcel(){
    	
    	$idTramite = $_POST['id_tramite_seguimiento'];
    	
    	$listaSeguimientos = $this->lNegocioSeguimientos->buscarSeguimientosXTramite($idTramite);
    	
    	$this->lNegocioSeguimientos->exportarArchivoExcel($listaSeguimientos);
    }
}