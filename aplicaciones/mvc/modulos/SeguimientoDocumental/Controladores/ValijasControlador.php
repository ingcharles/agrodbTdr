<?php
/**
 * Controlador Valijas
 *
 * Este archivo controla la lógica del negocio del modelo:  ValijasModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2019-02-13
 * @uses    ValijasControlador
 * @package SeguimientoDocumental
 * @subpackage Controladores
 */
namespace Agrodb\SeguimientoDocumental\Controladores;

use Agrodb\SeguimientoDocumental\Modelos\ValijasLogicaNegocio;
use Agrodb\SeguimientoDocumental\Modelos\ValijasModelo;
use Agrodb\SeguimientoDocumental\Modelos\UsuariosVentanillaLogicaNegocio;
use Agrodb\SeguimientoDocumental\Modelos\UsuariosVentanillaModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class ValijasControlador extends BaseControlador
{

    private $lNegocioValijas = null;    
    private $modeloValijas = null;
    private $accion = null;
    private $formulario = null;
    
    private $lNegocioUsuariosVentanilla = null;
    private $modeloUsuariosVentanilla = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
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
        $this->cargarPanelValijas();
        require APP . 'SeguimientoDocumental/vistas/listaValijasVista.php';
    }

    /**
     * Método para desplegar el formulario vaci
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Valijas";
        $this->formulario = 'nuevo';
        
        $datosUsuario=$this->obtenerDatosUsuarioTecnico($_SESSION['usuario']);
        
        $this->modeloValijas->setIdVentanilla($datosUsuario['idVentanilla']);
        $this->modeloValijas->setNombreVentanilla($datosUsuario['ventanillaUsuario']);
        $this->modeloValijas->setidentificador($_SESSION['usuario']);
        $this->modeloValijas->setNombreEmpleado($datosUsuario['nombreUsuarioVentanilla']);
        
        $numero=$this->generarCodigoValijas($datosUsuario['idVentanilla'], $datosUsuario['codigoVentanilla']);
        $this->modeloValijas->setNumeroValija($numero);
        
        require APP . 'SeguimientoDocumental/vistas/formularioValijasVista.php';
    }

    /**
     * Método para registrar en la base de datos -Valijas
     */
    public function guardar()
    {
        $numero=$this->generarCodigoValijas($_POST["id_ventanilla"], $_POST["codigo_ventanilla"]);
        
        if($_POST["id_valija"] === ''){
            $_POST['numero_valija'] = $numero;
            $_POST['fecha_creacion'] = 'now()';
        }

        $this->lNegocioValijas->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Valijas
     */
    public function editar()
    {
        $this->accion = "Editar Valijas";
        $this->formulario = 'abrir';
        $this->modeloValijas = $this->lNegocioValijas->buscar($_POST["id"]);
        
        $datosUsuario=$this->obtenerDatosUsuarioTecnico($this->modeloValijas->getIdentificador());
        
        $this->modeloValijas->setNombreVentanilla($datosUsuario['ventanillaUsuario']);
        $this->modeloValijas->setNombreEmpleado($datosUsuario['nombreUsuarioVentanilla']);
        
        require APP . 'SeguimientoDocumental/vistas/formularioValijasVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Valijas
     */
    public function borrar()
    {
        $this->lNegocioValijas->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Valijas
     */
    public function tablaHtmlValijas($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_valija'] . '"
                    		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'SeguimientoDocumental\valijas"
                    		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                    		  data-destino="detalleItem">
                    		  <td>' . ++ $contador . '</td>
                        <td style="white - space:nowrap; "><b>' . $fila['numero_valija'] . '</b></td>
                        <td>' . ($fila['fecha_creacion']!=null?date('Y-m-d',strtotime($fila['fecha_creacion'])):'') . '</td>
                        <td>' . $fila['destinatario'] . '</td>
                        <td>' . $fila['guia_correo'] . '</td>
                        <td>' . $fila['estado_entrega'] . '</td>
                    </tr>'
                );
            }
        }
    }
	
	/**
     * Construye el código HTML para desplegar la lista de - Valijas
     */
    public function listarReporteValijasAdministradorFiltradas($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_valija'] . '"
                    		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'SeguimientoDocumental\valijas"
                    		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                    		  data-destino="detalleItem">
                    		  <td>' . ++ $contador . '</td>
                        <td style="white - space:nowrap; "><b>' . $fila['numero_valija'] . '</b></td>
                        <td>' . $fila['destinatario'] . '</td>
                        <td>' . $fila['guia_correo'] . '</td>
                        <td>' . $fila['estado_entrega'] . '</td>
                    </tr>'
                );
            }
        }
    }

    /**
     * Construye el código HTML para desplegar panel de busqueda para Valijas
     */
    public function cargarPanelValijas()
    {
        $datosUsuario=$this->obtenerDatosUsuarioTecnico($_SESSION['usuario']);
        
        $this->panelBusquedaValijas = '<form id="filtrar" action="aplicaciones/mvc/SeguimientoDocumental/Valijas/exportarListaExcel" target="_blank" method="post">
											<table class="filtro" style="width: 450px;">
                                            	<input type="hidden" id="idVentanillaFiltro" name="idVentanillaFiltro" value="'.$datosUsuario['idVentanilla'].'" readonly="readonly" >
                            					<tbody>
	                                                <tr>
	                                                    <th colspan="2">Buscar:</th>
	                                                </tr>
	                            					<tr  style="width: 100%;">
	                            						<td >Número de Trámite: </td>
	                            						<td>
	                            							<input id="numValija" type="text" name="numValija" style="width: 100%" >
	                            						</td>
	                            					</tr>
	                                                <tr  style="width: 100%;">
	                            						<td >Destinatario: </td>
	                            						<td>
	                            							<input id="nombreDestinatario" type="text" name="nombreDestinatario" style="width: 100%" >
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
                            							<td>
                                                        	<select id="estadoEntrega" name="estadoEntrega" style="width: 100%;" required>' . 
                                                        	$this->comboEnviadoCerrado() .
                                                        	'</select>
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
     * Combo de dos estados ENVIADO/RECIBIDO
     *
     * @param $respuesta
     * @return string
     */
    public function comboEnviadoCerrado($opcion=null)
    {
        $combo = "";
        if ($opcion == "Enviado") {
            $combo .= '<option value="Enviado" selected="selected">Enviado</option>';
            $combo .= '<option value="Entregado">Entregado</option>';
        } else if ($opcion == "Entregado") {
            $combo .= '<option value="Enviado" >Enviado</option>';
            $combo .= '<option value="Entregado" selected="selected">Entregado</option>';
        } else {
            $combo .= '<option value="Enviado" selected="selected">Enviado</option>';
            $combo .= '<option value="Entregado">Entregado</option>';
        }
        return $combo;
    }
    
    /**
     * Método para listar las valijas registradas
     * */
    public function listarValijasFiltradas()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        $idVentanillaFiltro = $_POST["idVentanillaFiltro"];
        $numValija = $_POST["numValija"];
        $nombreDestinatario = $_POST["nombreDestinatario"];
        $numGuia = $_POST["numGuia"];
        $fechaInicio = $_POST["fechaInicio"];
        $fechaFin = $_POST["fechaFin"];
        $estadoEntrega = $_POST["estadoEntrega"];
        
        $arrayParametros = array('id_ventanilla' => $idVentanillaFiltro, 'numero_valija' => $numValija, 
                                 'destinatario' => $nombreDestinatario, 'guia_correo' => $numGuia,
        						 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin,
                                 'estado_entrega' => $estadoEntrega);
        
        $valijas = $this->lNegocioValijas->buscarValijasXFiltro($arrayParametros);
        
        $this->tablaHtmlValijas($valijas);
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        
        echo json_encode(array('estado' => $estado, 'mensaje' => $mensaje, 'contenido' => $contenido));
    }
    
    /**
     * Método para listar las valijas registradas
     * */
    public function obtenerDatosUsuarioTecnico($identificador)
    {
    	return $this->lNegocioUsuariosVentanilla->buscarDatosUsuarioTecnico($identificador);
    }
    
    /**
     * Método para generar la numeración de las valijas
     * */
    public function generarCodigoValijas($idVentanilla, $idCodigoVentanilla)
    {
    	return $this->lNegocioValijas->buscarNumeroValija($idCodigoVentanilla);
    }
    
	/**
     * Método para carga masiva de valijas
     * */
    public function cargaMasiva(){
    	$this->accion = "Carga masiva / Ingreso documental";
    	require APP . 'SeguimientoDocumental/vistas/formularioCargaMasivaValija.php';
    }
    
    /**
     * Método para obtener ruta de archivo excel
     * */
    public function cargarDocumentoMasivo(){
    	$this->lNegocioValijas->leerArchivoExcelValijas($_POST);
    }
    
    public function exportarListaExcel() {
    	
    	$idVentanillaFiltro = $_POST["idVentanillaFiltro"];
    	$numValija = $_POST["numValija"];
    	$nombreDestinatario = $_POST["nombreDestinatario"];
    	$numGuia = $_POST["numGuia"];
    	$fechaInicio = $_POST["fechaInicio"];
    	$fechaFin = $_POST["fechaFin"];
    	$estadoEntrega = $_POST["estadoEntrega"];
    	
    	$arrayParametros = array('id_ventanilla' => $idVentanillaFiltro, 'numero_valija' => $numValija,
    							 'destinatario' => $nombreDestinatario, 'guia_correo' => $numGuia,
    							 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin,
    							 'estado_entrega' => $estadoEntrega);
    	
    	$valijas = $this->lNegocioValijas->buscarValijasXFiltro($arrayParametros);

    	$this->lNegocioValijas->exportarArchivoExcel($valijas);
    }
    
    public function exportarListaExcelAdministrador() {
        
        $idVentanillaFiltro = $_POST["idVentanillaReporte"];
        $numGuia = $_POST["numGuiaReporte"];
        $fechaInicio = $_POST["fechaInicioReporte"];
        $fechaFin = $_POST["fechaFinReporte"];        
        $estadoEntrega = $_POST["estadoEntregaReporte"];
        
        $arrayParametros = array('id_ventanilla' => $idVentanillaFiltro, 'guia_correo' => $numGuia,
            'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin, 
            'estado_entrega' => $estadoEntrega);
        
        $valijas = $this->lNegocioValijas->buscarValijasNacionalXFiltro($arrayParametros);
        
        $this->lNegocioValijas->exportarArchivoExcelAdministrador($valijas);
    }
    
    public function exportarListaExcelAdministradorMensual() {
        
        $idVentanillaFiltro = $_POST["idVentanillaReporte"];
        $numGuia = $_POST["numGuiaReporte"];
        $fechaInicio = $_POST["fechaInicioReporte"];
        $fechaFin = $_POST["fechaFinReporte"];
        $estadoEntrega = $_POST["estadoEntregaReporte"];
        
        $arrayParametros = array('id_ventanilla' => $idVentanillaFiltro, 'guia_correo' => $numGuia,
            'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin,
            'estado_entrega' => $estadoEntrega);
        
        $valijas = $this->lNegocioValijas->buscarValijasNacionalXFiltro($arrayParametros);
        
        $this->lNegocioValijas->exportarArchivoExcelAdministradorMensual($valijas);
    }
}