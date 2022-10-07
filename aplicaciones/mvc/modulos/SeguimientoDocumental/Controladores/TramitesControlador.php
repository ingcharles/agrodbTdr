<?php
/**
 * Controlador Tramites
 *
 * Este archivo controla la lógica del negocio del modelo:  TramitesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2019-01-15
 * @uses    TramitesControlador
 * @package SeguimientoDocumental
 * @subpackage Controladores
 */
namespace Agrodb\SeguimientoDocumental\Controladores;

use Agrodb\SeguimientoDocumental\Modelos\TramitesLogicaNegocio;
use Agrodb\SeguimientoDocumental\Modelos\TramitesModelo;
use Agrodb\SeguimientoDocumental\Modelos\SeguimientosLogicaNegocio;
use Agrodb\SeguimientoDocumental\Modelos\SeguimientosModelo;
use Agrodb\SeguimientoDocumental\Modelos\UsuariosVentanillaLogicaNegocio;
use Agrodb\SeguimientoDocumental\Modelos\UsuariosVentanillaModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class TramitesControlador extends BaseControlador
{

    private $lNegocioTramites = null;
    private $modeloTramites = null;
    
    private $lNegocioSeguimientos = null;
    private $modeloSeguimientos = null;
    
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
        $this->lNegocioTramites = new TramitesLogicaNegocio();
        $this->modeloTramites = new TramitesModelo();
        
        $this->lNegocioSeguimientos = new SeguimientosLogicaNegocio();
        $this->modeloSeguimientos = new SeguimientosModelo();

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
        $this->cargarPanelTramites();

        require APP . 'SeguimientoDocumental/vistas/listaTramitesVista.php';
    }
    
    /**
     * Método de inicio del controlador para administración
     */
    public function listarAdministracionTramite()
    {
        $this->cargarPanelTramites();
        
        require APP . 'SeguimientoDocumental/vistas/listaTramitesAdministracionVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Tramites";
        $this->formulario = 'nuevo';

        $datosUsuario = $this->obtenerDatosUsuarioTecnico($_SESSION['usuario']);

        $this->modeloTramites->setIdVentanilla($datosUsuario['idVentanilla']);
        $this->modeloTramites->setNombreVentanilla($datosUsuario['ventanillaUsuario']);
        $this->modeloTramites->setidentificador($_SESSION['usuario']);
        $this->modeloTramites->setNombreEmpleado($datosUsuario['nombreUsuarioVentanilla']);

        $numero = $this->generarCodigoTramites($datosUsuario['idVentanilla'], $datosUsuario['codigoVentanilla']);
        $this->modeloTramites->setNumeroTramite($numero);
        require APP . 'SeguimientoDocumental/vistas/formularioTramitesVista.php';
    }
    
    /**
     * Método para desplegar el listado de trámites para impresión de bitácora
     */
    public function nuevaBitacora()
    {
        $this->accion = "Nueva Bitácora";
        $this->formulario = 'bitacora';

        require APP . 'SeguimientoDocumental/vistas/formularioTramitesBitacoraVista.php';
    }

    /**
     * Método para registrar en la base de datos -Tramites
     */
    public function guardar()
    {
        switch($_POST["estado_tramite"]){
            case '':{    
                $numero = $this->generarCodigoTramites($_POST["id_ventanilla"], $_POST["codigo_ventanilla"]);
                
                if ($_POST["id_tramite"] === '') {
                    $_POST['numero_tramite'] = $numero;
                }
                
                break;
            }

            default:{
                break;
            }
        }
        
        $this->lNegocioTramites->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }
    
    /**
     * Método para registrar en la base de datos -Tramites
     */
    public function guardarAdministrador()
    {
        $this->lNegocioTramites->guardarAdministrador($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Tramites
     */
    public function editar()
    {
        $this->accion = "Editar Tramites";
        $this->formulario = 'abrir';
        $this->modeloTramites = $this->lNegocioTramites->buscar($_POST["id"]);

        $datosUsuario = $this->obtenerDatosUsuarioRegistro($this->modeloTramites->getIdentificador(), $this->modeloTramites->getIdVentanilla());

        $this->modeloTramites->setNombreVentanilla($datosUsuario['ventanillaUsuario']);
        $this->modeloTramites->setNombreEmpleado($datosUsuario['nombreUsuarioVentanilla']);

        require APP . 'SeguimientoDocumental/vistas/formularioTramitesVista.php';
    }
    
    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Tramites
     */
    public function editarAdministrador()
    {
        $this->accion = "Editar Trámites Administrador";
        $this->formulario = 'abrir';
        $this->modeloTramites = $this->lNegocioTramites->buscar($_POST["id"]);
        
        $datosUsuario = $this->obtenerDatosUsuarioRegistro($this->modeloTramites->getIdentificador(), $this->modeloTramites->getIdVentanilla());
        
        $this->modeloTramites->setNombreVentanilla($datosUsuario['ventanillaUsuario']);
        $this->modeloTramites->setNombreEmpleado($datosUsuario['nombreUsuarioVentanilla']);
        
        require APP . 'SeguimientoDocumental/vistas/formularioTramitesAdministradorVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Tramites
     */
    public function borrar()
    {
        $this->lNegocioTramites->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Tramites
     */
    public function tablaHtmlTramites($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_tramite'] . '"
                    		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'SeguimientoDocumental/tramites"
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
     * Construye el código HTML para desplegar panel de busqueda para Trámites
     */
    public function cargarPanelTramites()
    {
        $datosUsuario = $this->obtenerDatosUsuarioTecnico($_SESSION['usuario']);

        $this->panelBusquedaTramites = '<form id="filtrar" action="aplicaciones/mvc/SeguimientoDocumental/Tramites/exportarListaExcel" target="_blank" method="post">
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
                                                        <select id="estadoTramite" name="estadoTramite" style="width: 100%;" required>' . $this->comboEstadosTramitesIngreso() . '</select>
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
        
        $this->panelBusquedaTramitesAdministracion = '<table class="filtro" style="width: 450px;">
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
                                                        <select id="estadoTramite" name="estadoTramite" style="width: 100%;" required>' . $this->comboEstadosTramitesAdministrador() . '</select>
                            						</td>
                            					</tr>
                                                            
                                                <tr></tr>
                            					<tr>
                            						<td colspan="3">
                            							<button id="btnFiltrar">Filtrar lista</button>
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
    public function comboEstadosTramitesIngreso($opcion = null)
    {
        $combo = "";
        if ($opcion == "Ingresado") {
            $combo .= '<option value="Ingresado" selected="selected">Ingresado</option>';
            $combo .= '<option value="Despachado">Despachado</option>';
        } else if ($opcion == "Despachado") {
            $combo .= '<option value="Ingresado" >Ingresado</option>';
            $combo .= '<option value="Despachado" selected="selected">Despachado</option>';
        } else {
            $combo .= '<option value="Ingresado" selected="selected">Ingresado</option>';
            $combo .= '<option value="Despachado">Despachado</option>';
        }

        return $combo;
    }
    
    /**
     * Combo de estados para trámites que maneja el administrador
     *
     * @param
     *            $respuesta
     * @return string
     */
    public function comboEstadosTramitesAdministrador($opcion = null)
    {
        $combo = "<option>Seleccione...</option>";
        if ($opcion == "Seguimiento") {
            $combo .= '<option value="Seguimiento" selected="selected">Seguimiento</option>';
            //$combo .= '<option value="Cerrado">Cerrado</option>';
        } else if ($opcion == "Cerrado") {
            $combo .= '<option value="Seguimiento">Seguimiento</option>';
            //$combo .= '<option value="Cerrado" selected="selected">Cerrado</option>';
        } else {
            //$combo .= '<option value="Seguimiento">Seguimiento</option>';
            $combo .= '<option value="Cerrado">Cerrado</option>';
        }
        
        return $combo;
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
            $combo .= '<option value="Ingresado" selected="selected">Ingresado</option>';
            $combo .= '<option value="Despachado">Despachado</option>';
            $combo .= '<option value="Seguimiento">Seguimiento</option>';
            $combo .= '<option value="Cerrado">Cerrado</option>';
        } else if ($opcion == "Despachado") {
            $combo .= '<option value="Ingresado" >Ingresado</option>';
            $combo .= '<option value="Despachado" selected="selected">Despachado</option>';
            $combo .= '<option value="Seguimiento">Seguimiento</option>';
            $combo .= '<option value="Cerrado">Cerrado</option>';
        } else if ($opcion == "Seguimiento") {
            $combo .= '<option value="Ingresado" >Ingresado</option>';
            $combo .= '<option value="Despachado">Despachado</option>';
            $combo .= '<option value="Seguimiento" selected="selected">Seguimiento</option>';
            $combo .= '<option value="Cerrado">Cerrado</option>';
        } else if ($opcion == "Cerrado") {
            $combo .= '<option value="Ingresado" >Ingresado</option>';
            $combo .= '<option value="Despachado">Despachado</option>';
            $combo .= '<option value="Seguimiento">Seguimiento</option>';
            $combo .= '<option value="Cerrado" selected="selected">Cerrado</option>';
        } else {
            $combo .= '<option value="Ingresado" selected="selected">Ingresado</option>';
            $combo .= '<option value="Despachado">Despachado</option>';
            $combo .= '<option value="Seguimiento">Seguimiento</option>';
            $combo .= '<option value="Cerrado">Cerrado</option>';
        }
        
        return $combo;
    }
    
    /**
     * Combo de estados para origen de trámites
     *
     * @param
     *            $respuesta
     * @return string
     */
    public function comboOrigenTramite($opcion = null)
    {
        $combo = "";
        if ($opcion == "Ciudadano") {
            $combo .= '<option value="Ciudadano" selected="selected">Ciudadano</option>';
            $combo .= '<option value="Ventanilla">Ventanilla</option>';
        } else if ($opcion == "Ventanilla") {
            $combo .= '<option value="Ciudadano" >Ciudadano</option>';
            $combo .= '<option value="Ventanilla" selected="selected">Ventanilla</option>';
        } else {
            $combo .= '<option value="Ciudadano">Ciudadano</option>';
            $combo .= '<option value="Ventanilla" selected="selected">Ventanilla</option>';
        }
        
        return $combo;
    }

    /**
     * Método para listar los trámites registrados
     */
    public function listarTramitesFiltrados()
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

        $this->tablaHtmlTramites($tramites);
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);

        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }

    /**
     * Método para generar la numeración de las valijas
     */
    public function generarCodigoTramites($idVentanilla, $idCodigoVentanilla)
    {
        
    	return $this->lNegocioTramites->buscarNumeroTramite($idCodigoVentanilla);
    }
    
    /**
     * Construye el combo para desplegar la lista de Trámites por estado (para trámites derivados)
     */
    public function comboTramites($estado)
    {
        $sentencia = "estado_tramite in (" . $estado . ");";
        
        $comboTramites = '<option value="">Seleccionar...</option>';
        $tramite = $this->lNegocioTramites->buscarLista($sentencia);
        
        $comboTramites = array();
        
        foreach ($tramite as $item){
            if(($item['quipux_agr']!==null) && ($item['quipux_agr']!=='') && ($item['quipux_agr']!=='No indica')){
            	$comboTramites[] = array ('value' => $item->quipux_agr, 'label' => $item->quipux_agr);
            }
        }
        
        echo json_encode(array('mensaje' => $comboTramites ));
    }
    
    /**
     * Buscar trámites derivados enlazados por Quipux (quipux_agr) para cierre en cascada (para trámites derivados)
     */
    public function listarTramitesDerivados($quipuxAgr)
    {
        $tramite = $this->lNegocioTramites->buscarTramitesDerivadosXQuipux($quipuxAgr);
        
        foreach ($tramite as $fila){
            $tramitesDerivados = array(
                'id_tramite' => $fila['id_tramite']
            );
        }

        return $tramitesDerivados;
    }
    
    /**
     * Método para listar los trámites registrados
     */
    public function listarTramitesAdministradorFiltrados()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        $numTramite = $_POST["numTramite"];
        $nombreRemitente = $_POST["nombreRemitente"];
        $nombreDestinatario = $_POST["nombreDestinatario"];
        $numQuipux = $_POST["numQuipux"];
        $factura = $_POST["numFactura"];
        $fechaInicio = $_POST["fechaInicio"];
        $fechaFin = $_POST["fechaFin"];
        $estadoTramite = $_POST["estadoTramite"];
        
        $arrayParametros = array(
            'numero_tramite' => $numTramite,
            'remitente' => $nombreRemitente,
            'destinatario' => $nombreDestinatario,
            'quipux_agr' => $numQuipux,
            'factura' => $factura,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estado_tramite' => $estadoTramite
        );
        
        $tramites = $this->lNegocioTramites->buscarTramitesNacionalXFiltro($arrayParametros);

        $this->tablaHtmlTramitesAdministrador($tramites);
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        
        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }
    
    /**
     * Construye el código HTML para desplegar la lista de - Tramites
     */
    public function tablaHtmlTramitesAdministrador($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_tramite'] . '"
                    		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'SeguimientoDocumental/tramites"
                    		  data-opcion="editarAdministrador" ondragstart="drag(event)" draggable="true"
                    		  data-destino="detalleItem">
                    		  <td>' . ++ $contador . '</td>
                        <td style="white - space:nowrap; "><b>' . $fila['numero_tramite'] . '</b></td>
                        <td>' . $fila['asunto'] . '</td>
                        <td>' . $fila['remitente'] . '</td>
                        <td>' . $fila['destinatario'] . '</td>
                    </tr>'
                );
            }
        }
    }
    
    /**
     * Método para listar los trámites por estado
     */
    public function construirTramitesCheck($estado)
    {
        $datosUsuario = $this->obtenerDatosUsuarioTecnico($_SESSION['usuario']);
        
        $arrayParametros = array(
            'id_ventanilla' => $datosUsuario['idVentanilla'],
            'estado_tramite' => $estado
        );
        
        $listaTramites = $this->lNegocioTramites->buscarTramitesBitacoraEstado($arrayParametros);
        
		$this->listaTramites = '';
        $this->listaTramites = '<table style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>No. Registro</th>
                                                <th>Fecha</th>
                                                <th>Unidad destino</th>
                                                <th>Añadir</th>
                                            </tr>
                                        </thead>';
        
        foreach ($listaTramites as $fila) {
            
            $this->listaTramites .=
                    '<tr>
                        <td>' . ($fila['numero_tramite'] != '' ? $fila['numero_tramite'] : '') . '
                            <input type="hidden" name="numeroTramitesSeguimiento[]" value="' . ($fila['numero_tramite'] != '' ? $fila['numero_tramite'] : '') . '" />
                        </td>
                        <td>' . date('Y-m-d',strtotime($fila['fecha_creacion'])). '
                            <input type="hidden" name="fechaTramitesSeguimiento[]" value="' . ($fila['fecha_creacion'] != '' ? date('Y-m-d',strtotime($fila['fecha_creacion'])) : '') . '" />
                        </td>
                        <td>' . ($fila['unidad_destino'] != '' ? $fila['unidad_destino'] : '') . '
                            <input type="hidden" name="estadoTramitesSeguimiento[]" value="' . ($fila['estado_tramite'] != '' ? $fila['estado_tramite'] : '') . '" />
                            <input type="hidden" name="ventanillaTramitesSeguimiento[]" value="' . ($fila['unidad_destino'] != '' ? $fila['unidad_destino'] : '') . '" />
                        </td>
                        <td>
                            <input type="checkbox" name="tramites'.$fila['estado_tramite'].'[]" class="registroActivar'.$fila['estado_tramite'].'" value="' . ($fila['id_tramite'] != '' ? $fila['id_tramite'] : '') . '" />
                        </td>
                    </tr>';
        }
        
        echo $this->listaTramites;
    }
    
	 /**
     * Método para listar los trámites por estado
     */
    public function generarBitacora()
    {
        $arrayTramites = array();
        $estado = 'Despachado';
        $i=0;
        
        if (isset($_POST['dtxtItem'])){
            $tramites = $_POST['dtxtItem'];

            $tramites = implode(',', $tramites);

            $resultado = $this->lNegocioTramites->buscarTramitesBitacora($tramites);

            $resultado = $resultado->toArray();

            foreach ($resultado as $tramite) {
                $arrayParametrosGuardar = array(
                    'id_tramite' => $tramite['id_tramite'],
                    'estado_tramite' => $estado
                );

                $this->lNegocioTramites->guardar($arrayParametrosGuardar);
            }

            $this->generarArchivoBitacora($tramites);
        }else{
            exit();
        }

    }
	
	/**
     * Función para visualizar la bitácora de trámites
     */
    public function generarArchivoBitacora($tramites)
    {
        $estado = 'exito';
        $mensaje = 'Bitácora generada con exito';
        $contenido = '';
        
        $fechaSolicitud = date('Y-m-d');
        $aleatorio = rand(1, 999);
        $nombreArchivo = 'bitacora_'.$_SESSION['usuario'] . '_' . $fechaSolicitud . '_' . $aleatorio;
        
        // ****************  ***************************
        if (strlen($tramites)>0) {
            //$this->lNegocioTramites->generarCertificado($arrayTramites, $nombreArchivo);
        	$this->lNegocioTramites->generarReporteBitacora($tramites, $nombreArchivo);
            
            $this->urlPdf = SEG_DOC_BIT_URL . "bitacora/" . $nombreArchivo . ".pdf";
            $contenido = $this->urlPdf;
        } else {
            $mensaje = 'No existen registros de los trámites';
            $estado = 'FALLO';
        }
        
        echo json_encode(array('estado' => $estado, 'mensaje' => $mensaje, 'contenido' => $contenido));
    }
	
	/**
     * Método para desplegar el certificado
     */
    public function mostrarReporte()
    {
        $this->urlPdf = $_POST['id'];
        require APP . 'SeguimientoDocumental/vistas/visorPDF.php';
    }
	
	/**
     * Método para desplegar la pantalla de carga masiva de tramites.
     */
    
    public function cargaMasiva(){
    	$this->accion = "Carga masiva / Ingreso documental";
    	require APP . 'SeguimientoDocumental/vistas/formularioCargaMasivaTramite.php';
    }
    
    /**
     * Método para obtener ruta de archivo excel
     * */
    public function cargarTramiteMasivo(){
    	$this->lNegocioTramites->leerArchivoExcelTramites($_POST);
    }
    
    /**
     * Método para desplegar la pantalla de carga masiva de tramites ciudadanos.
     */
    
    public function cargaMasivaCiudadano(){
        $this->accion = "Carga masiva de trámites ciudadanos / Ingreso documental";
        require APP . 'SeguimientoDocumental/vistas/formularioCargaMasivaTramiteCiudadano.php';
    }
    
    /**
     * Método para obtener ruta de archivo excel
     * */
    public function cargarTramiteMasivoCiudadano(){
        $this->lNegocioTramites->leerArchivoExcelTramitesCiudadanos($_POST);
    }
    
    /**
     * Método para verificación de existencia de número de quipux registrado previamente.
     * */
    public function buscarQuipux($numeroQuipux){
    	$mensaje = $this->lNegocioTramites->obtenerQuipuxTramite($numeroQuipux);
    	echo json_encode(array('mensaje' => $mensaje ));
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
	    $tramites = $this->lNegocioTramites->buscarTramitesXFiltro($arrayParametros);
	    
	    $this->lNegocioTramites->exportarArchivoExcel($tramites);
    }
	
	public function exportarListaExcelAdministrador() {
        $idVentanillaFiltro = $_POST["idVentanillaReporte"];
        $idUnidadDestino = $_POST["idUnidadDestinoReporte"];
        $fechaInicio = $_POST["fechaInicioReporte"];
        $fechaFin = $_POST["fechaFinReporte"];
        $estadoTramite = $_POST["estadoTramiteReporte"];
        
        $arrayParametros = array(
            'id_ventanilla' => $idVentanillaFiltro,
            'id_unidad_destino' => $idUnidadDestino,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estado_tramite' => $estadoTramite
        );
        $tramites = $this->lNegocioTramites->buscarTramitesNacionalXFiltro($arrayParametros);
        
        $this->lNegocioTramites->exportarArchivoExcelTramitesAdministrador($tramites);
    }
}