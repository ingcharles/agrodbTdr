<?php
/**
 * Controlador Cultivos
 *
 * Este archivo controla la lógica del negocio del modelo: CultivosModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2021-03-24
 * @uses CultivosControlador
 * @package PlagasLaboratorio
 * @subpackage Controladores
 */
namespace Agrodb\PlagasLaboratorio\Controladores;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\PlagasLaboratorio\Modelos\CultivosLogicaNegocio;
use Agrodb\PlagasLaboratorio\Modelos\CultivosModelo;
use Agrodb\PlagasLaboratorio\Modelos\PlagasLogicaNegocio;

class CultivosControlador extends BaseControlador{

	private $lNegocioCultivos = null;

	private $modeloCultivos = null;

	private $lNegocioPlagas = null;

	private $accion = null;

	private $article = null;

	private $registroPlagas = null;
	
	private $listaCultivos = null;
	
	private $listaPlagas = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioCultivos = new CultivosLogicaNegocio();
		$this->modeloCultivos = new CultivosModelo();

		$this->lNegocioPlagas = new PlagasLogicaNegocio();

		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloCultivos = $this->lNegocioCultivos->buscarCultivos();
		$this->aticuloHtmlCultivos($modeloCultivos->toArray());
		require APP . 'PlagasLaboratorio/vistas/listaCultivosVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo Cultivo";
		require APP . 'PlagasLaboratorio/vistas/formularioCultivosVista.php';
	}

	/**
	 * Método para registrar en la base de datos -Cultivos
	 */
	public function guardar(){
		$_POST['fecha_creacion'] = 'now()';
		$_POST['identificacion_creacion'] = $this->identificador;
		$this->lNegocioCultivos->guardar($_POST);
		Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
	}

	/**
	 * Método para registrar en la base de datos -Cultivos
	 */
	public function actualizar(){
		$_POST['fecha_modificacion'] = 'now()';
		$_POST['identificacion_modificacion'] = $this->identificador;
		$this->lNegocioCultivos->guardar($_POST);
		Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: Cultivos
	 */
	public function editar(){
		$this->accion = "Editar Cultivo/Nueva plaga";
		$arrayParametros = array(
			'id_cultivo' => $_POST["id"]);
		$this->modeloCultivos = $this->lNegocioCultivos->buscar($arrayParametros['id_cultivo']);
		$this->registroPlagas = $this->imprimirLineaPlaga($this->lNegocioPlagas->buscarLista($arrayParametros));
		require APP . 'PlagasLaboratorio/vistas/formularioCultivosPlagasVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - Cultivos
	 */
	public function borrar(){
		$this->lNegocioCultivos->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - Cultivos
	 */
	public function aticuloHtmlCultivos($registros){
		$this->article = "";
		$contador = 0;
		
		$keys = array_column($registros, 'nombre_comun', 'id_cultivo');
		array_multisort($keys, SORT_ASC, SORT_STRING|SORT_FLAG_CASE, $registros);
		
		foreach ($registros as $fila){

			$this->article .= '<article id="' . $fila['id_cultivo'] . '" class="item"
            								data-rutaAplicacion="' . URL_MVC_FOLDER . 'PlagasLaboratorio/cultivos"
            								data-opcion="editar" ondragstart="drag(event)" draggable="true" data-destino="detalleItem">
        								<span><small><b>' . $fila['nombre_comun'] . '</b></small></span><br/>
                                        <span><small>' . $fila['nombre_cientifico'] . '</small></span><br/>
        					 			<span class="ordinal">' . ++ $contador . '</span>
        								<aside><small>' . date('Y-m-d H:i:s', strtotime($fila['fecha_creacion'])) . '</small></aside>
    								</article>';
		}
	}

	/**
	 * Método para desplegar los reportes
	 */
	public function reportes(){
		$this->accion = "Reportes";
		$this->cargarPanelReportes();
		
		$cultivos = $this->lNegocioCultivos->buscarCultivos();
		
		$arrayCultivo = array();
		
		foreach ($cultivos as $cultivo){
			$arrayCultivo[] = array ('value' => $cultivo['nombre_comun'], 'label' => $cultivo['nombre_comun'], 'idCultivo' => $cultivo['id_cultivo']);
		}
		
		$plagas = $this->lNegocioPlagas->buscarPlagas();
		
		$arrayPlaga = array();
		
		foreach ($plagas as $plaga){
			$arrayPlaga[] = array ('value' => $plaga['nombre_cientifico'], 'label' => $plaga['nombre_cientifico'], 'idPlaga' => $plaga['id_plaga']);
		}
		
		$this->listaCultivos = $arrayCultivo;
		$this->listaPlagas = $arrayPlaga;
		require APP . 'PlagasLaboratorio/vistas/listaReporte.php';
	}

	/**
	 * Construye el código HTML para desplegar panel de busqueda para los reportes
	 */
	public function cargarPanelReportes(){

		$this->panelBusquedaReporte = '<form id="filtrar" action="aplicaciones/'.URL_MVC_FOLDER.'PlagasLaboratorio/cultivos/exportarReporteExcel" method="post">
											<table class="filtro" style="width: 450px;">
                                                        <tbody>
                                                            <tr>
                                                                <th colspan="2">Filtros para el reporte:</th>
                                                            </tr>
                                        					<tr  style="width: 100%;">
                                        						<td >Cultivo: </td>
                                        						<td style="width: 100%;">
                                                                    <input id="cultivo" type="text" name="cultivo" style="width: 100%">
																	<input type="hidden" id="id_cultivo" name="id_cultivo" />
                                        						</td>
                                        					</tr>
                                                            <tr  style="width: 100%;">
                                        						<td >Plaga: </td>
                                        						<td style="width: 100%;">
                                                                    <input id="plaga" type="text" name="plaga" style="width: 100%">
																	<input type="hidden" id="id_plaga" name="id_plaga" />
                                        						</td>
                                        					</tr>
                                                            <tr  style="width: 100%;">
                                        						<td >Provincia: </td>
                                        						<td>
																	<select id="id_provincia" name="id_provincia" style="width: 100%;">
                                                                    <option value="">Seleccionar....</option>' . $this->comboProvinciasEc('') . '</select>
                                        						</td>
                                        					</tr>
                                                            <tr></tr>
                                        					<tr>
                                        						<td colspan="3">
                                        							<button id="btnExcel">Generar Reporte</button>
                                        						</td>
                                        					</tr>
                                        				</tbody>
                                        			</table>
											</from>';
	}
	
	public function listarPlagas(){
		
		$arrayParametros = array('id_cultivo' => $_POST['id_cultivo']);
		
		$plagas = $this->lNegocioPlagas->buscarLista($arrayParametros);
		
		$arrayPlaga = array();
		
		foreach ($plagas as $plaga){
			$arrayPlaga[] = array ('value' => $plaga['nombre_cientifico'], 'label' => $plaga['nombre_cientifico'], 'idPlaga' => $plaga['id_plaga']);
		}

		echo json_encode(array('mensaje' => $arrayPlaga ));
	}
	
	public function exportarReporteExcel() {
		
		$arrayParametros = array(
			'id_cultivo' => $_POST['id_cultivo'],
			'id_plaga' => $_POST['id_plaga'],
			'id_provincia' => $_POST['id_provincia']
		);
		
		$datos = $this->lNegocioCultivos->buscarCultivoPlagaDetalleXFiltro($arrayParametros);
		
		$this->lNegocioCultivos->exportarArchivoExcelCultivoPlagaDetalle($datos);
	}
}
