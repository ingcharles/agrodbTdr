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
namespace Agrodb\PasaporteEquino\Controladores;

use Agrodb\PasaporteEquino\Modelos\EquinosLogicaNegocio;
use Agrodb\PasaporteEquino\Modelos\EquinosModelo;

use Agrodb\PasaporteEquino\Modelos\MovilizacionesLogicaNegocio;
use Agrodb\PasaporteEquino\Modelos\MovilizacionesModelo;

use Agrodb\PasaporteEquino\Modelos\FiscalizacionesLogicaNegocio;
use Agrodb\PasaporteEquino\Modelos\FiscalizacionesModelo;

use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosLogicaNegocio;
use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class ReportesControlador extends BaseControlador
{

    private $lNegocioEquinos = null;
    private $modeloEquinos = null;
    
    private $lNegocioMovilizaciones = null;
    private $modeloMovilizaciones = null;
    
    private $lNegocioFiscalizaciones = null;
    private $modeloFiscalizaciones = null;
    
    private $lNegocioCatastroPredioEquidos = null;
    private $modeloCatastroPredioEquidos = null;

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
        $this->lNegocioEquinos = new EquinosLogicaNegocio();
        $this->modeloEquinos = new EquinosModelo();
        
        $this->lNegocioMovilizaciones = new MovilizacionesLogicaNegocio();
        $this->modeloMovilizaciones = new MovilizacionesModelo();
        
        $this->lNegocioFiscalizaciones = new FiscalizacionesLogicaNegocio();
        $this->modeloFiscalizaciones = new FiscalizacionesModelo();
        
        $this->lNegocioCatastroPredioEquidos = new CatastroPredioEquidosLogicaNegocio();
        $this->modeloCatastroPredioEquidos = new CatastroPredioEquidosModelo();
        
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
        
        require APP . 'PasaporteEquino/vistas/listaOpcionesReportes.php';
    }
    
    /**
     * Método de inicio del controlador para reportes
     */
    public function listarReportePasaporte()
    {
        $this->cargarPanelReportes();
        
        require APP . 'PasaporteEquino/vistas/listaPasaporteReporteVista.php';
    }
    
    /**
     * Método de inicio del controlador para reportes
     */
    public function listarReporteMovilizacion()
    {
        $this->cargarPanelReportes();
        
        require APP . 'PasaporteEquino/vistas/listaMovilizacionReporteVista.php';
    }
    
    /**
     * Método de inicio del controlador para reportes
     */
    public function listarReporteFiscalizacion()
    {
        $this->cargarPanelReportes();
        
        require APP . 'PasaporteEquino/vistas/listaFiscalizacionReporteVista.php';
    }

    /**
     * Construye el código HTML para desplegar panel de busqueda para los reportes
     */
    public function cargarPanelReportes()
    {
        $this->panelBusquedaPasaporteReporte = '
                                                    <form id="filtrar" action="aplicaciones/mvc/PasaporteEquino/Equinos/exportarPasaportesExcel" target="_blank" method="post">
                                                        <table class="filtro" style="width: 450px;">
                                                            <tbody>
                                                                <tr>
                                                                    <th colspan="4">Filtro para el reporte de pasaportes equinos emitidos</th>
                                                                </tr>
                                            					<tr  style="width: 100%;">
                                            						<td >Provincia: </td>
                                            						<td style="width: 100%;" colspan="3">
                                                                        <select id="idProvinciaFiltro" name="idProvinciaFiltro" style="width: 100%;" >' .
                                                                            $this->comboProvinciaXPrediosOperacionesRegistradas() .
                                                                            '</select>
                                                                        <input type="hidden" id="provinciaFiltro" name="provinciaFiltro" />
                                            						</td>
                                            					</tr>
                                                                <tr  style="width: 100%;">
                                            						<td >Cantón: </td>
                                            						<td style="width: 100%;" colspan="3">
                                                                        <select id="idCantonFiltro" name="idCantonFiltro" style="width: 100%;" disabled>
                                                                            <option value>Seleccione....</option>
                                                                        </select>
                                            						</td>
                                            					</tr>
                                                                <tr  style="width: 100%;">
                                            						<td >Estado: </td>
                                            						<td style="width: 100%;" colspan="3">
                                                                        <select id="estadoFiltro" name="estadoFiltro" style="width: 100%;" >
                                                                            <option value>Seleccionar....</option>' .
                                                                            $this->comboEstadosEquino() .
                                                                            '</select>
                                            						</td>
                                            					</tr>
                                                                <tr  style="width: 100%;">
                                            						<td >Fecha Inicio: </td>
                                            						<td>
                                            							<input id="fechaInicio" type="text" name="fechaInicio" required="required" >
                                            						</td>
                                                                                
                                            						<td >Fecha Fin: </td>
                                            						<td>
                                            							<input id="fechaFin" type="text" name="fechaFin" required="required" >
                                            						</td>
                                            					</tr>
                                                                                
                                                                <tr></tr>
                                            					<tr>
                                            						<td colspan="3">
                                            							<button type="submit">Generar Reporte</button>
                                            						</td>
                                                                                
                                            					</tr>
                                            				</tbody>
                                            			</table>
                                                    </form>';
                                                                            
         $this->panelBusquedaMovilizacionReporte = '
                                                    <form id="filtrar" action="aplicaciones/mvc/PasaporteEquino/Movilizaciones/exportarMovilizacionesExcel" target="_blank" method="post">
                                                        <table class="filtro" style="width: 450px;">
                                                            <tbody>
                                                                <tr>
                                                                    <th colspan="4">Filtro para el reporte de movilizaciones generadas</th>
                                                                </tr>
                                            					<tr  style="width: 100%;">
                                            						<td >Provincia: </td>
                                            						<td style="width: 100%;" colspan="3">
                                                                        <select id="idProvinciaFiltro" name="idProvinciaFiltro" style="width: 100%;" >
                                                                            <option value>Seleccione....</option>
                                                                            <option value="Todas">Todas</option>' .
                                                                            $this->comboProvinciasEc() .
                                                                        '</select>
                                                                        <input type="hidden" id="provinciaFiltro" name="provinciaFiltro" />
                                            						</td>
                                            					</tr>
                                                                <tr  style="width: 100%;">
                                            						<td >Cantón: </td>
                                            						<td style="width: 100%;" colspan="3">
                                                                        <select id="idCantonFiltro" name="idCantonFiltro" style="width: 100%;" disabled>
                                                                            <option value>Seleccione....</option>
                                                                        </select>
                                            						</td>
                                            					</tr>
                                                                <tr  style="width: 100%;">
                                            						<td >Estado: </td>
                                            						<td style="width: 100%;" colspan="3">
                                                                        <select id="estadoFiltro" name="estadoFiltro" style="width: 100%;" >
                                                                            <option value>Seleccionar....</option>' .
                                                                            $this->comboEstadosMovilizacion() .
                                                                            '</select>
                                            						</td>
                                            					</tr>
                                                                <tr  style="width: 100%;">
                                            						<td >Fecha Inicio: </td>
                                            						<td>
                                            							<input id="fechaInicio" type="text" name="fechaInicio" required >
                                            						</td>
                                                                                
                                            						<td >Fecha Fin: </td>
                                            						<td>
                                            							<input id="fechaFin" type="text" name="fechaFin" required >
                                            						</td>
                                            					</tr>
                                                                                
                                                                <tr></tr>
                                            					<tr>
                                            						<td colspan="3">
                                            							<button type="submit">Generar Reporte</button>
                                            						</td>
                                                                                
                                            					</tr>
                                            				</tbody>
                                            			</table>
                                                    </form>';
                                                                            
        $this->panelBusquedaFiscalizacionReporte = '
                                                    <form id="filtrar" action="aplicaciones/mvc/PasaporteEquino/Fiscalizaciones/exportarFiscalizacionesExcel" target="_blank" method="post">
                                                        <table class="filtro" style="width: 450px;">
                                                            <tbody>
                                                                <tr>
                                                                    <th colspan="4">Filtro para el reporte de fiscalizaciones generadas</th>
                                                                </tr>
                                            					<tr  style="width: 100%;">
                                            						<td >Provincia: </td>
                                            						<td style="width: 100%;" colspan="3">
                                                                        <select id="idProvinciaFiltro" name="idProvinciaFiltro" style="width: 100%;" >
                                                                            <option value="Todas">Todas</option>' .
                                                                            $this->comboProvinciasEc() .
                                                                        '</select>
                                            						</td>
                                            					</tr>
                                                                <tr  style="width: 100%;">
                                            						<td >Cantón: </td>
                                            						<td style="width: 100%;" colspan="3">
                                                                        <select id="idCantonFiltro" name="idCantonFiltro" style="width: 100%;" disabled>
                                                                            <option value>Seleccione....</option>
                                                                        </select>
                                            						</td>
                                            					</tr>
                                                                <tr  style="width: 100%;">
                                            						<td >Estado: </td>
                                            						<td style="width: 100%;" colspan="3">
                                                                        <select id="estadoFiltro" name="estadoFiltro" style="width: 100%;" >
                                                                            <option value>Seleccionar....</option>' .
                                                                            $this->comboEstadosFiscalizacion() .
                                                                            '</select>
                                            						</td>
                                            					</tr>
                                                                <tr  style="width: 100%;">
                                            						<td >Fecha Inicio: </td>
                                            						<td>
                                            							<input id="fechaInicio" type="text" name="fechaInicio" required >
                                            						</td>
                                                                                
                                            						<td >Fecha Fin: </td>
                                            						<td>
                                            							<input id="fechaFin" type="text" name="fechaFin" required >
                                            						</td>
                                            					</tr>
                                                                                
                                                                <tr></tr>
                                            					<tr>
                                            						<td colspan="3">
                                            							<button type="submit">Generar Reporte</button>
                                            						</td>
                                                                                
                                            					</tr>
                                            				</tbody>
                                            			</table>
                                                    </form>';
    }
    
    /**
     * Combo de identificación adicional de Equinos
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboEstadosEquino()
    {
        $combo = "";
        
        $combo .= '<option value="Todos">Todos</option>';
        $combo .= '<option value="Activo">Activo</option>';
        $combo .= '<option value="Inactivo">Inactivo</option>';
        $combo .= '<option value="Liberado">Liberado</option>';
        $combo .= '<option value="Traspaso">Traspaso</option>';
        $combo .= '<option value="Vinculacion">Vinculacion</option>';
        $combo .= '<option value="Deceso">Deceso</option>';
        $combo .= '<option value="Movilizacion">Movilizacion</option>';
        
        return $combo;
    }
    
    /**
     * Combo de estados para trámites
     *
     * @param
     *            $respuesta
     * @return string
     */
    public function comboEstadosMovilizacion()
    {
        $combo = "";

    	$combo .= '<option value="Todos">Todos</option>';
        $combo .= '<option value="Vigente">Vigente</option>';
        $combo .= '<option value="Finalizado">Finalizado</option>';
        
        return $combo;
    }
    
    /**
     * Combo de estados para trámites
     *
     * @param
     *            $respuesta
     * @return string
     */
    public function comboEstadosFiscalizacion()
    {
        $combo = "";
        
        $combo .= '<option value="Todos">Todos</option>';
        $combo .= '<option value="Positivo">Positivo</option>';
        $combo .= '<option value="Negativo">Negativo</option>';
        
        return $combo;
    }
    
    /**
     * Consulta las provincias donde se tiene pasaportes de acuerdo al catastro de predio de équidos
     */
    public function comboProvinciaXPrediosOperacionesRegistradas()
    {
        $provincia = '<option value>Seleccione....</option>
                        <option value="Todas">Todas</option>';
        
        $combo = $this->lNegocioCatastroPredioEquidos->buscarProvinciasXPasaportePrediosRegistrados();
        
        foreach ($combo as $item) {
            $provincia .= '<option value="' . $item->id_provincia . '">' . $item->provincia . '</option>';
        }
        
        return $provincia;
    }
    
    /**
     * Consulta las provincias donde se tiene pasaportes de acuerdo al catastro de predio de équidos
     */
    public function comboCantonXPrediosOperacionesRegistradas($idProvincia)
    {
        $canton = '<option value>Seleccione....</option>';
        
        $combo = $this->lNegocioCatastroPredioEquidos->buscarCantonesXPasaportePrediosRegistrados($idProvincia);
        
        foreach ($combo as $item) {
            $canton .= '<option value="' . $item->id_canton . '">' . $item->canton . '</option>';
        }
        
        echo $canton;
        exit();
    }
}