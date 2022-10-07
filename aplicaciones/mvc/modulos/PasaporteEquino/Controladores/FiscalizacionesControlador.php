<?php
/**
 * Controlador Fiscalizaciones
 *
 * Este archivo controla la lógica del negocio del modelo:  FiscalizacionesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-03-22
 * @uses    FiscalizacionesControlador
 * @package PasaporteEquino
 * @subpackage Controladores
 */
namespace Agrodb\PasaporteEquino\Controladores;

use Agrodb\PasaporteEquino\Modelos\FiscalizacionesLogicaNegocio;
use Agrodb\PasaporteEquino\Modelos\FiscalizacionesModelo;

use Agrodb\PasaporteEquino\Modelos\MovilizacionesLogicaNegocio;
use Agrodb\PasaporteEquino\Modelos\MovilizacionesModelo;

use Agrodb\RegistroOperador\Modelos\OperadoresLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\OperadoresModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class FiscalizacionesControlador extends BaseControlador
{

    private $lNegocioFiscalizaciones = null;
    private $modeloFiscalizaciones = null;
    
    private $lNegocioMovilizaciones = null;
    private $modeloMovilizaciones = null;
    
    private $lNegocioOperadores = null;
    private $modeloOperadores = null;

    private $accion = null;
    
    private $tipoUsuario = null;
    private $formulario = null;
    
    private $razonSocialCC = null;
    private $provinciaCC = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        
        $this->lNegocioFiscalizaciones = new FiscalizacionesLogicaNegocio();
        $this->modeloFiscalizaciones = new FiscalizacionesModelo();
        
        $this->lNegocioMovilizaciones = new MovilizacionesLogicaNegocio();
        $this->modeloMovilizaciones = new MovilizacionesModelo();
        
        $this->lNegocioOperadores = new OperadoresLogicaNegocio();
        $this->modeloOperadores = new OperadoresModelo();
        
        set_exception_handler(array(
            $this,
            'manejadorExcepciones'
        ));
        
        if (!isset($_SESSION['idArea'])) {// && ($this->modeloOrganizacionEcuestre->current()->id_organizacion_ecuestre == null)
            $this->tipoUsuario = 'CentroConcentracion';
            
            $operadorCC = $this->lNegocioOperadores->buscar($_SESSION['usuario']);
            
            $this->razonSocialCC = $operadorCC->getRazonSocial();
            $this->provinciaCC = $operadorCC->getProvincia();
            
        } else {

            $this->tipoUsuario = 'Tecnico';
        }
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        //print_r($_SESSION);
        $this->cargarPanelFiscalizaciones();
        //echo $this->tipoUsuario;
        
        //$modeloFiscalizaciones = $this->lNegocioFiscalizaciones->buscarFiscalizaciones();
        //$this->tablaHtmlFiscalizaciones($modeloFiscalizaciones);
        require APP . 'PasaporteEquino/vistas/listaFiscalizacionesVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Fiscalizaciones";
        require APP . 'PasaporteEquino/vistas/formularioFiscalizacionesVista.php';
    }

    /**
     * Método para registrar en la base de datos -Fiscalizaciones
     */
    public function guardar()
    {
        $this->lNegocioFiscalizaciones->guardar($_POST);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Fiscalizaciones
     */
    public function editar()
    {
        $this->modeloMovilizaciones = $this->lNegocioMovilizaciones->buscar($_POST["id"]);
        
        $this->accion = "Fiscalización de Movilización ".$this->modeloMovilizaciones->getNumeroMovilizacion();
        
        $this->modeloFiscalizaciones = $this->lNegocioFiscalizaciones->buscar($_POST["id"]);
        require APP . 'PasaporteEquino/vistas/formularioFiscalizacionesVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Fiscalizaciones
     */
    public function borrar()
    {
        $this->lNegocioFiscalizaciones->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Fiscalizaciones
     */
    public function tablaHtmlFiscalizaciones($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_movilizacion'] . '"
                		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'PasaporteEquino\fiscalizaciones"
                		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                		  data-destino="detalleItem">
                		<td>' . ++ $contador . '</td>
                		<td style="white - space:nowrap; "><b>' . $fila['numero_movilizacion'] . '</b></td>
                        <td>' . $fila['nombre_ubicacion_origen'] . '</td>
                        <td>' . $fila['nombre_ubicacion_destino'] . '</td>
                        <td>' . $fila['estado_movilizacion'] . '</td>
                    </tr>'
            );
        }
    }
    
    /**
     * Construye el código HTML para desplegar panel de busqueda para Movilizaciones a fiscalizar
     */
    public function cargarPanelFiscalizaciones()
    {
        $this->panelBusquedaFiscalizaciones = '<table class="filtro" style="width: 100%;">
                                                <input type="hidden" id="identificadorUsuario" name="identificadorUsuario" value="' . $_SESSION['usuario'] . '" readonly="readonly" >
                                                <input type="hidden" id="tipoUsuarioFiltro" name="tipoUsuarioFiltro" value="' . $this->tipoUsuario . '" readonly="readonly" >
                                                    
                                                <tbody>
                                                    <tr>
                                                        <th colspan="2">Consultar de Certificados de Movilización a fiscalizar:</th>
                                                    </tr>
                                                    
                                                    <tr  style="width: 100%;">
                                						<td >Identificación solicitante: </td>
                                						<td>
                                							<input id="identificadorSolicitanteFiltro" type="text" name="identificadorSolicitanteFiltro" style="width: 90%" maxlength="13">
                                						</td>
                                					</tr>
                                                    
                                                    <tr  style="width: 100%;">
                                						<td >Nombre solicitante: </td>
                                						<td>
                                							<input id="nombreSolicitanteFiltro" type="text" name="nombreSolicitanteFiltro" style="width: 90%" maxlength="128">
                                						</td>
                                					</tr>
                                                    
                                                    <tr  style="width: 100%;">
                                						<td >Nombre Sitio origen: </td>
                                						<td>
                                							<input id="nombreSitioOrigenFiltro" type="text" name="nombreSitioOrigenFiltro" style="width: 90%" maxlength="128">
                                						</td>
                                					</tr>
                                                    
                                                    <tr  style="width: 100%;">
                                						<td >Nº Movilización: </td>
                                						<td>
                                							<input id="numMovilizacionFiltro" type="text" name="numMovilizacionFiltro" style="width: 90%" maxlength="32">
                                						</td>
                                					</tr>
                                                    
                                                    <tr  style="width: 100%;">
                                						<td >Nº Pasaporte equino: </td>
                                						<td>
                                							<input id="numPasaporteFiltro" type="text" name="numPasaporteFiltro" style="width: 90%" maxlength="16">
                                						</td>
                                					</tr>
                                                    
    												<tr  style="width: 100%;">
                                						<td >*Fecha Inicio: </td>
                                						<td>
                                							<input id="fechaInicioFiltro" type="text" name="fechaInicioFiltro" style="width: 90%" readonly="readonly">
                                						</td>
                                                    </tr>
                                                    
                                                    <tr  style="width: 100%;">
                                						<td >*Fecha Fin: </td>
                                						<td>
                                							<input id="fechaFinFiltro" type="text" name="fechaFinFiltro" style="width: 90%" readonly="readonly">
                                						</td>
                                					</tr>
                                                    
                                					<tr>
                                						<td colspan="2" style="text-align: end;">
                                							<button id="btnFiltrar">Consultar</button>
                                						</td>
                                					</tr>
                                				</tbody>
                                			</table>';
    }
    
    /**
     * Método para listar las movilizaciones registradas
     */
    public function listarMovilizacionesFiltradas()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        if ($this->tipoUsuario == 'Asociacion') {
            $idAsociacion = $this->idAsociacion;
        } else {
            $idAsociacion = '';
        }
        
        $tipoProceso = $_POST['tipoProceso'];
        $tipoUsuario = $this->tipoUsuario;
        $identificadorSolicitanteFiltro = (isset($_POST['identificadorSolicitanteFiltro']) ? $_POST['identificadorSolicitanteFiltro'] : '');
        $nombreSolicitanteFiltro = (isset($_POST['nombreSolicitanteFiltro']) ? $_POST['nombreSolicitanteFiltro'] : '');
        $nombreSitioOrigenFiltro = (isset($_POST['nombreSitioOrigenFiltro']) ? $_POST['nombreSitioOrigenFiltro'] : '');
        $numMovilizacionFiltro = (isset($_POST['numMovilizacionFiltro']) ? $_POST['numMovilizacionFiltro'] : '');
        $numPasaporteFiltro = (isset($_POST['numPasaporteFiltro']) ? $_POST['numPasaporteFiltro'] : '');
        $fechaInicioFiltro = $_POST['fechaInicioFiltro'];
        $fechaFinFiltro = $_POST['fechaFinFiltro'];
        
        $arrayParametros = array(
            'tipoProceso' => $tipoProceso,
            'tipoUsuario' => $tipoUsuario,
            'id_asociacion' => $idAsociacion,
            'identificador' => $_SESSION['usuario'],
            'identificador_solicitante' => $identificadorSolicitanteFiltro,
            'nombre_solicitante' => $nombreSolicitanteFiltro,
            'nombre_ubicacion_origen' => $nombreSitioOrigenFiltro,
            'numero_movilizacion' => $numMovilizacionFiltro,
            'pasaporte_equino' => $numPasaporteFiltro,
            'fecha_inicio_movilizacion' => $fechaInicioFiltro,
            'fecha_fin_movilizacion' => $fechaFinFiltro
        );
        
        $equinos = $this->lNegocioMovilizaciones->buscarMovilizacionesFiltradas($arrayParametros);
        
        $this->tablaHtmlFiscalizaciones($equinos);
        
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        
        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }
    
    /**
     * Combo de resultados de fiscalización
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboLugarFiscalizacion()
    {
        $combo = "";
        
        $combo .= '<option value="Centro de concentración animal">Centro de concentración animal</option>';
        $combo .= '<option value="Camper">Camper</option>';
        $combo .= '<option value="Operativo">Operativo</option>';
        $combo .= '<option value="Oficina">Oficina</option>';
        
        return $combo;
    }
    
    /**
     * Combo de resultados de fiscalización
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboResultadoFiscalizacion($opcion = null)
    {
        $combo = "";
        if ($opcion == "Positivo") {
            $combo .= '<option value="Positivo" selected="selected">Positivo</option>';
            $combo .= '<option value="Negativo">Negativo</option>';
        } else if ($opcion == "Negativo") {
            $combo .= '<option value="Positivo" >Positivo</option>';
            $combo .= '<option value="Negativo" selected="selected">Negativo</option>';
        } else {
            $combo .= '<option value="Positivo">Positivo</option>';
            $combo .= '<option value="Negativo">Negativo</option>';
        }
        
        return $combo;
    }
    
    /**
     * Combo de resultados de fiscalización
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboAccionCorrectivaFiscalizacion($resultado, $opcion = null)
    {
        $combo = "";
        
        switch($resultado){
            case 'Positivo':{
                if ($opcion == "Fiscalización correcta") {
                    $combo .= '<option value="">Seleccione....</option>';
                    $combo .= '<option value="Fiscalización correcta" selected="selected">Fiscalización correcta</option>';
                    $combo .= '<option value="Modificar registro de movilizacion">Modificar registro de movilizacion</option>';
                    $combo .= '<option value="Activar registro de movilizacion">Modificar registro de movilizacion</option>';
                } else if ($opcion == "Modificar permiso") {
                    $combo .= '<option value="">Seleccione....</option>';
                    $combo .= '<option value="Fiscalización correcta" >Fiscalización correcta</option>';
                    $combo .= '<option value="Modificar permiso" selected="selected">Modificar permiso</option>';
                    $combo .= '<option value="Activar registro de movilizacion">Modificar registro de movilizacion</option>';
                } else if ($opcion == "Activar registro de movilizacion") {
                    $combo .= '<option value="">Seleccione....</option>';
                    $combo .= '<option value="Fiscalización correcta" >Fiscalización correcta</option>';
                    $combo .= '<option value="Modificar permiso">Modificar permiso</option>';
                    $combo .= '<option value="Activar registro de movilizacion" selected="selected">Modificar registro de movilizacion</option>';
                } else {
                    $combo .= '<option value="">Seleccione....</option>';
                    $combo .= '<option value="Fiscalización correcta">Fiscalización correcta</option>';
                    $combo .= '<option value="Modificar permiso">Modificar permiso</option>';
                    $combo .= '<option value="Activar registro de movilizacion">Modificar registro de movilizacion</option>';
                }
                
                break;
            }
            case 'Negativo':{
                if ($opcion == "Inactivar registro de movilización") {
                    $combo .= '<option value="">Seleccione....</option>';
                    $combo .= '<option value="Inactivar registro de movilización" selected="selected">Inactivar registro de movilización</option>';
                    $combo .= '<option value="Anular registro de movilización">Anular registro de movilización</option>';
                }else if ($opcion == "Anular registro de movilización") {
                    $combo .= '<option value="">Seleccione....</option>';
                    $combo .= '<option value="Inactivar registro de movilización" selected="selected">Inactivar registro de movilización</option>';
                    $combo .= '<option value="Anular registro de movilización" selected="selected">Anular registro de movilización</option>';
                }else {
                    $combo .= '<option value="">Seleccione....</option>';
                    $combo .= '<option value="Inactivar registro de movilización">Inactivar registro de movilización</option>';
                    $combo .= '<option value="Anular registro de movilización">Anular registro de movilización</option>';
                }
                break;
            }
            
            default:{
                break;
            }
        }
        
        echo $combo;
        exit();
    }
    
    /**
     * Combo de resultados de fiscalización
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboMotivoFiscalizacion()
    {
        $combo = "";
        
        $combo .= '<option value="Destino erróneo">Destino erróneo</option>';
        $combo .= '<option value="Fecha de movilización errónea">Fecha de movilización errónea</option>';
        $combo .= '<option value="Identificadores erróneos">Identificadores erróneos</option>';
        $combo .= '<option value="No utilización del registro de movilización" >No utilización del registro de movilización</option>';
        $combo .= '<option value="Origen erróneo">Origen erróneo</option>';
        $combo .= '<option value="Producto erróneo">Producto erróneo</option>';
        $combo .= '<option value="Transporte erróneo">Transporte erróneo</option>';
        $combo .= '<option value="Inconsistencias durante traslado">Inconsistencias durante traslado</option>';
        
        return $combo;
    }
    
    /**
     * Combo de resultados de fiscalización
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboEstadoFiscalizacion($opcion = null)
    {
        $combo = "";
        if ($opcion == "Vigente") {
            $combo .= '<option value="Vigente" selected="selected">Vigente</option>';
            $combo .= '<option value="Finalizado">Finalizado</option>';
        } else if ($opcion == "Finalizado") {
            $combo .= '<option value="Vigente" >Vigente</option>';
            $combo .= '<option value="Finalizado" selected="selected">Finalizado</option>';
        } else {
            $combo .= '<option value="Vigente">Vigente</option>';
            $combo .= '<option value="Finalizado">Finalizado</option>';
        }
        
        return $combo;
    }
    
    /**
     * Método para listar los seguimientos registrados
     */
    public function construirDetalleFiscalizacion($idMovilizacion)
    {
        $query = "id_movilizacion = $idMovilizacion";
        
        $listaDestalles = $this->lNegocioFiscalizaciones->buscarLista($query);
        
        $this->listaDetalles = '<table style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Nº</th>
                                                <th>Fecha</th>
                                                <th>Tipo Fiscalizador</th>
                                                <th>Fiscalizador</th>
                                                <th>Resultado</th>
                                                <th>Acción correctiva</th>
                                                <th>Motivo</th>
                                                <th>Observación</th>
                                            </tr>
                                        </thead>';
        
        $i=1;
        
        foreach ($listaDestalles as $fila) {
            
            $this->listaDetalles .=
            '<tr>
                        <td>' . $i++ . '</td>
                        <td>' . ($fila['fecha_creacion'] != '' ? date('Y-m-d',strtotime($fila['fecha_creacion'])) : 'NA'). '</td>
                        <td>' . ($fila['tipo_fiscalizador'] != '' ? $fila['tipo_fiscalizador'] : 'NA') . '</td>
                        <td>' . ($fila['nombre_fiscalizador'] != '' ? $fila['nombre_fiscalizador'] : 'NA') . '</td>
                        <td>' . ($fila['resultado_fiscalizacion'] != '' ? $fila['resultado_fiscalizacion'] : 'NA'). '</td>
                        <td>' . ($fila['accion_correctiva'] != '' ? $fila['accion_correctiva'] : 'NA') . '</td>
                        <td>' . ($fila['motivo'] != '' ? $fila['motivo'] : 'NA') . '</td>
                        <td>' . ($fila['observacion_fiscalizacion'] != '' ? $fila['observacion_fiscalizacion'] : 'NA') . '</td>
                    </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
    
    /**
     * Función para generar el certificado
     */
    public function guardarFiscalizacion()
    {
        $resultado = $this->lNegocioFiscalizaciones->guardarFiscalizacion($_POST);
        
        if ($resultado['bandera']) {
            echo json_encode(array(
                'estado' => $resultado['estado'],
                'mensaje' => $resultado['mensaje'],
                'contenido' => $resultado['contenido']
            ));
        } else {
            Mensajes::fallo($resultado['mensaje']);
        }
    }
    
    /**
     * Método para generar el reporte de fiscalizaciones equinas en excel
     */
    public function exportarFiscalizacionesExcel() {
        $idProvinciaFiltro = (isset($_POST["idProvinciaFiltro"])?$_POST["idProvinciaFiltro"]:'');
        $idCantonFiltro = (isset($_POST["idCantonFiltro"])?$_POST["idCantonFiltro"]:'');
        $estadoFiltro = (isset($_POST["estadoFiltro"])?$_POST["estadoFiltro"]:'');
        $fechaInicio = (isset($_POST["fechaInicio"])?$_POST["fechaInicio"]:'');
        $fechaFin =(isset($_POST["fechaFin"])?$_POST["fechaFin"]:'');
        
        $arrayParametros = array(
            'id_provincia' => $idProvinciaFiltro,
            'id_canton' => $idCantonFiltro,
            'estado_fiscalizacion' => $estadoFiltro,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        );
        
        $fiscalizaciones = $this->lNegocioFiscalizaciones->buscarFiscalizacionesReporteFiltradas($arrayParametros);
        
        //if(!empty($fiscalizaciones->current())){
            $this->lNegocioFiscalizaciones->exportarArchivoExcelFiscalizaciones($fiscalizaciones);
        /*}else{
            echo "No se dispone de datos con los parámetros solicitados. Por favor intente nuevamente.";
        }*/
    }
}