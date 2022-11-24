<?php

 /**
 * Controlador Base
 *
 * Este archivo contiene métodos comunes para todos los controladores 
 *
 * @property  AGROCALIDAD
 * @author    Carlos Anchundia
 * @date      2022-10-22
 * @uses      BaseControlador
 * @package   VacacionesPermisos
 * @subpackage Controladores
 */
namespace Agrodb\VacacionesPermisos\Controladores;

session_start();

use Agrodb\Core\Comun;
use Agrodb\VacacionesPermisos\Modelos\CronogramaVacacionesLogicaNegocio;
use Agrodb\VacacionesPermisos\Modelos\PeriodoCronogramaVacacionesLogicaNegocio;

class BaseControlador extends Comun
{
	public $itemsFiltrados = array();
	public $codigoJS = null;

	/**
	* Constructor
	*/
	function __construct() {
		parent::usuarioActivo();
		//Si se requiere agregar código concatenar la nueva cadena con  ejemplo $this->codigoJS.=alert('hola');
		$this->codigoJS = \Agrodb\Core\Mensajes::limpiar();
	}
	public function crearTabla() {
		$tabla = "//No existen datos para mostrar...";
		if (count($this->itemsFiltrados) > 0) {
			$tabla = '$(document).ready(function() {
			construirPaginacion($("#paginacion"),' . json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE) . ');
			$("#listadoItems").removeClass("comunes");
			});';
		}

	return $tabla;
	}

	public function construirDatosGeneralesCronogramaVacaciones() {
		
		$cronogramaVacacionesLogicaNegocio = new CronogramaVacacionesLogicaNegocio();
		$qDatosFuncionario = $cronogramaVacacionesLogicaNegocio->obtenerDatosEmpleadoFechaIngresoInstitucion($this->identificador);

		$nombre = $qDatosFuncionario->current()->nombre;
		$fechaIngreso = $qDatosFuncionario->current()->fecha_ingreso_institucion;
		$unidadAdministrativa = $qDatosFuncionario->current()->nombre_unidad_administrativa;
		$gestionAdministrativa = $qDatosFuncionario->current()->nombre_gestion_administrativa;
		
		$puestoInstitucional = $qDatosFuncionario->current()->puesto_institucional;
		
		$qSaldoFuncionario = $cronogramaVacacionesLogicaNegocio->consultarSaldoFuncionario($this->identificador);
		
		if($qSaldoFuncionario->count()){
			$minutos = $qSaldoFuncionario->current()->minutos_disponibles;
		}else{
			$minutos = 0;
		}
		
		$qSaldoFuncionarioNuevo = $cronogramaVacacionesLogicaNegocio->consultarSaldoFuncionarioNuevo($this->identificador);
		
		if($qSaldoFuncionarioNuevo->count()){
			$minutosNuevo = $qSaldoFuncionarioNuevo->current()->minutos_disponibles;
		}else{
			$minutosNuevo = 0;
		}

		$minutos = $minutos + $minutosNuevo;

		$diasDisponibles = $cronogramaVacacionesLogicaNegocio->devolverFormatoDiasDisponibles($minutos);

		$datos = '
		<input type="hidden" name="fecha_ingreso_institucion" id="fecha_ingreso_institucion" value="' . $fechaIngreso . '"/>
		<input type="hidden" name="nombre_puesto" id="nombre_puesto" value="' . $puestoInstitucional . '"/>
		<fieldset>
		<legend>Cronograma de planificación</legend>
		<div data-linea="1">
			<label for="identificador">Identificador: </label>' 
			. $this->identificador .
		'</div>	
		
		<div data-linea="2">
			<label for="identificador">Apellidos y nombres: </label>'
			 . $nombre .
		'</div>	

		<div data-linea="3">
			<label for="fecha_ingreso_institucion">Fecha de ingreso: </label>'
			. $fechaIngreso . 
		'</div>				

		<div data-linea="4">
			<label for="unidad_administrativa">Unidad administrativa: </label>'
			. $unidadAdministrativa .
		'</div>

		<div data-linea="5">
			<label for="gestion_administrativa">Gestión administrativa: </label>'
			. $gestionAdministrativa .
		'</div>

		<div data-linea="6">
			<label for="puesto_institucional">Puesto institucional: </label>'
			. $puestoInstitucional .
		'</div>
		<hr/>
		<div data-linea="7">
			<label for="dias_disponibles">Días disponibles: </label>'
			. $diasDisponibles .
		'</div>
		</fieldset>';		

		return $datos;

	}

	public function construirDatosGeneralesCronogramaVacacionesNoConfigurado() {

		$datos = '<fieldset>
					<legend>Cronograma de planificación</legend>
					<div data-linea="1">
						<label for="observacion">Observacion: </label>No existe una planificación habilitada.
					</div>
					</fieldset>';

		return $datos;

	}

	public function obtenerSolicitudesPlanificacionVacaciones() {

		//TODO: Recibir os perfiles del funcionario y en base a eso aplicar filtros para la revisión de solicitudes
		//y la creación de los filtros de busqueda

		$cronogramaVacacionesLogicaNegocio = new CronogramaVacacionesLogicaNegocio();

		$qCronogramaVacacion = $cronogramaVacacionesLogicaNegocio->buscarLista();

		return $qCronogramaVacacion;
		
	}

	public function construirDetallePeriodosCronograma($arrayParametros)
	{

		$idCronogramaVacacion = $arrayParametros['id_cronograma_vacacion'];

		$periodoCronogramaVacacionesLogicaNegocio = new PeriodoCronogramaVacacionesLogicaNegocio();

		$datos = ['id_cronograma_vacacion' => $idCronogramaVacacion];
		
		$qCronogramaVacacion = $periodoCronogramaVacacionesLogicaNegocio->buscarLista($datos);

		$datosPlanificarPeriodos = '<fieldset>
										<legend>Detalle de periodos</legend>';
		$totalDias = 0;
					
		$datosPlanificarPeriodos .= '<table id="tPeriodosPlanificar" style="width: 100%;">
										<thead>
											<tr>
												<th>Periodo</th>
												<th>Fecha inicio</th>
												<th>Fecha fin</th>
												<th>Número días</th>
											</tr>
										</thead>
										<tbody>';

		foreach($qCronogramaVacacion as $item){

			$totalDias = $totalDias + $item->total_dias;

			$datosPlanificarPeriodos .= '<tr>	
			<td>Periodo ' . $item->numero_periodo . '</td>
			<td style="text-align: center;">' . $this->fechaEs(date('d-m-Y',strtotime($item->fecha_inicio))) . '</td>
			<td style="text-align: center;">' . $this->fechaEs(date('d-m-Y',strtotime($item->fecha_fin))) . '</td>
			<td style="text-align: center;">' . $item->total_dias . '</td><tr>';

		}

		$datosPlanificarPeriodos .= '<tr>>
										<td>Total días</td>
										<td></td>
										<td></td>
										<td style="text-align: center;">' . $totalDias . '</td>
									</td>
									</tbody>
									</table>
									</fieldset>';

		return $datosPlanificarPeriodos;

	}

 	/**
     * Construye el código HTML para desplegar panel de búsqueda
     */
    public function cargarPanelBusquedaSolicitud()
    {

		//TODO: Recibir el perfil para mostrar mas filtros

        $panelBusqueda = '<table id="fBusqueda" class="filtro" style="width: 400px;">
                        				<tbody>
											<tr>
												<th colspan="5">Buscar:</th>
											</tr>
											<tr>
                        						<td colspan="1">Identificador: </td>
                        						<td colspan="4">
                        							<input id="bIdentificadorFuncionario" type="text" name="bIdentificadorFuncionario" style="width: 100%" class="validacion">
                        						</td>
                        					</tr>
                        					<tr>
                        						<td colspan="1">Nombre: </td>
                        						<td colspan="4">
													<input id="bNombreFuncionario" type="text" name="bNombreFuncionario" style="width: 100%" class="validacion">
                        						</td>
                        					</tr>
                        					<tr>
												<td>Fecha inicio: </td>
												<td>
													<input id="bFechaInicio" type="text" name="bFechaInicio" style="width: 100%" class="validacion">
												</td>
												<td>Fecha fin: </td>
												<td>
													<input id="bFechaFin" type="text" name="bFechaFin" style="width: 100%" class="validacion">
												</td>
                        					</tr>
                        					<tr>
                        						<td colspan="4">
                        							<button id="btnFiltrar">Buscar</button>
                        						</td>
                        					</tr>
                        				</tbody>
                        			</table>';

			return $panelBusqueda;
    }

	public function fechaEs($fecha) {
		$fecha = substr($fecha, 0, 10);
		$numeroDia = date('d', strtotime($fecha));
		$dia = date('l', strtotime($fecha));
		$mes = date('F', strtotime($fecha));
		$anio = date('Y', strtotime($fecha));
		$dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
		$dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
		$nombredia = str_replace($dias_EN, $dias_ES, $dia);
		$meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
		$meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
		$nombreMes = str_replace($meses_EN, $meses_ES, $mes);
		return $nombredia.", ".$numeroDia." de ".$nombreMes." de ".$anio;
		}

}
