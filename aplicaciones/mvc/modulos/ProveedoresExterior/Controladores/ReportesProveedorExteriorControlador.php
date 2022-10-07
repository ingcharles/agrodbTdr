<?php
/**
 * Controlador ReportesProveedorExterior
 *
 * Este archivo controla la lógica del negocio del modelo: ProveedorExteriorModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2021-07-13
 * @uses ProveedorExteriorControlador
 * @package ProveedoresExterior
 * @subpackage Controladores
 */
namespace Agrodb\ProveedoresExterior\Controladores;

use Agrodb\ProveedoresExterior\Modelos\ProveedorExteriorLogicaNegocio;
use Agrodb\ProveedoresExterior\Modelos\ProveedorExteriorModelo;

class ReportesProveedorExteriorControlador extends BaseControlador{

	private $lNegocioProveedorExterior = null;

	private $modeloProveedorExterior = null;

	private $accion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		// $this->lNegocioProveedorExterior = new ProveedorExteriorLogicaNegocio();
		// $this->modeloProveedorExterior = new ProveedorExteriorModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		require APP . 'ProveedoresExterior/vistas/listaOpcionesReportesProveedorExterior.php';
	}

	/**
	 * Método para mostrar el panel de busqueda de estado de solicitudes de proveedor exterior
	 */
	public function listarReporteEstadoSolicitudes(){
		$tipoReporte = 'estadoSocilicitudes';
		$this->cargarPanelReportes($tipoReporte);
		require APP . 'ProveedoresExterior/vistas/listaReporteEstadoSolicitudes.php';
	}

	/**
	 * Método para mostrar el panel de busqueda de estado de solicitudes de proveedor exterior
	 */
	public function listarReporteSolicitudesHabilitadas(){
		$tipoReporte = 'socilicitudesHabilitadas';
		$this->cargarPanelReportes($tipoReporte);
		require APP . 'ProveedoresExterior/vistas/listaReporteSolicitudesHabilitadas.php';
	}

	/**
	 * Construye el código HTML para desplegar panel de busqueda para los reportes
	 */
	public function cargarPanelReportes($tipoReporte){
		$tituloFiltro = "";
		$pagina = "";

		switch ($tipoReporte) {

			case "estadoSocilicitudes":
				$tituloFiltro = "Reporte de estado de solicitudes de habilitación";
				$pagina = "exportarEstadoSolicitudesExcel";
			break;
			case "socilicitudesHabilitadas":
				$tituloFiltro = "Reporte de Proveedores en el exterior habilitados";
				$pagina = "exportarSolicitudesHabilitadasExcel";
			break;
		}

		$this->panelBusquedaProveedoresExteriorReporte = '
                                                    <form id="filtrar" action="aplicaciones/mvc/ProveedoresExterior/ProveedorExterior/' . $pagina . '" target="_blank" method="post">
                                                        <table class="filtro">
                                                            <tbody>
                                                                <tr>
                                                                    <th colspan="4">' . $tituloFiltro . '</th>
                                                                </tr>
                                            					<tr style="width: 100%;">
                                            						<td >Provincia: </td>
                                            						<td style="width: 100%;" colspan="3">
                                                                        <select id="idProvinciaFiltro" name="idProvinciaFiltro" style="width: 100%;" required>
                                                                            <option value="">Seleccione....</option>
                                                                            <option value="Todas">Todas</option>' . $this->comboProvinciasEc() . '</select>
                                                                        <input type="hidden" id="provinciaFiltro" name="provinciaFiltro" />
                                            						</td>
                                            					</tr>
                                                                <tr style="width: 100%;">
                                            						<td >Fecha Inicio: </td>
                                            						<td>
                                            							<input id="fechaInicio" type="text" name="fechaInicio" required readonly="readonly">
                                            						</td>                                                                                
                                            						<td>Fecha Fin: </td>
                                            						<td>
                                            							<input id="fechaFin" type="text" name="fechaFin" required readonly="readonly">
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
}
