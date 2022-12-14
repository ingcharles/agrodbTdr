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
use Agrodb\GUath\Modelos\DatosContratoLogicaNegocio;
use Agrodb\VacacionesPermisos\Modelos\ConfiguracionCronogramaVacacionesLogicaNegocio;
use Agrodb\VacacionesPermisos\Modelos\CronogramaVacacionesLogicaNegocio;
use Agrodb\VacacionesPermisos\Modelos\PeriodoCronogramaVacacionesLogicaNegocio;
use Agrodb\VacacionesPermisos\Modelos\RevisionCronogramaVacacionesLogicaNegocio;
use Zend\Filter\File\UpperCase;

class BaseControlador extends Comun
{
	public $itemsFiltrados = array();
	public $codigoJS = null;
	private $lNegocioConfiguracionCronogramaVacaciones = null;
	private $lNegocioDatosContrato = null;
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::usuarioActivo();
		//Si se requiere agregar código concatenar la nueva cadena con  ejemplo $this->codigoJS.=alert('hola');
		$this->codigoJS = \Agrodb\Core\Mensajes::limpiar();
		$this->lNegocioDatosContrato = new DatosContratoLogicaNegocio();
	}
	public function crearTabla()
	{
		$tabla = "//No existen datos para mostrar...";
		if (count($this->itemsFiltrados) > 0) {
			$tabla = '$(document).ready(function() {
			construirPaginacion($("#paginacion"),' . json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE) . ');
			$("#listadoItems").removeClass("comunes");
			});';
		}

		return $tabla;
	}

	public function construirDatosGeneralesCronogramaVacaciones()
	{

		$cronogramaVacacionesLogicaNegocio = new CronogramaVacacionesLogicaNegocio();
		$configuracionCronogramaVacacionLogicaNegocio = new ConfiguracionCronogramaVacacionesLogicaNegocio();


		$datos = "estado_configuracion_cronograma_vacacion IN ('Activo','RechazadoDe')";
		$verificarConfiguracionCronograma = $configuracionCronogramaVacacionLogicaNegocio->buscarLista($datos);

		$idConfiguracionCronogramaVacacion = $verificarConfiguracionCronograma->current()->id_configuracion_cronograma_vacacion;
		$qDatosFuncionario = $cronogramaVacacionesLogicaNegocio->obtenerDatosEmpleadoFechaIngresoInstitucion($this->identificador);
		$nombre = $qDatosFuncionario->current()->nombre;
		$fechaIngreso = $qDatosFuncionario->current()->fecha_ingreso_institucion;
		$unidadAdministrativa = $qDatosFuncionario->current()->nombre_unidad_administrativa;
		$gestionAdministrativa = $qDatosFuncionario->current()->nombre_gestion_administrativa;
		$puestoInstitucional = $qDatosFuncionario->current()->puesto_institucional;
		$idAreaPadre = $qDatosFuncionario->current()->id_area_padre;
		$qSaldoFuncionario = $cronogramaVacacionesLogicaNegocio->consultarSaldoFuncionario($this->identificador);

		if ($qSaldoFuncionario->count()) {
			$minutos = $qSaldoFuncionario->current()->minutos_disponibles;
		} else {
			$minutos = 0;
		}

		$qSaldoFuncionarioNuevo = $cronogramaVacacionesLogicaNegocio->consultarSaldoFuncionarioNuevo($this->identificador);

		if ($qSaldoFuncionarioNuevo->count()) {
			$minutosNuevo = $qSaldoFuncionarioNuevo->current()->minutos_disponibles;
		} else {
			$minutosNuevo = 0;
		}

		$minutos = $minutos + $minutosNuevo;

		$diasDisponibles = $cronogramaVacacionesLogicaNegocio->devolverFormatoDiasDisponibles($minutos);

		$datos = '
		<input type="hidden" name="id_area_padre" id="id_area_padre" value="' . $idAreaPadre . '"/>
		<input type="hidden" name="fecha_ingreso_institucion" id="fecha_ingreso_institucion" value="' . $fechaIngreso . '"/>
		<input type="hidden" name="nombre_puesto" id="nombre_puesto" value="' . $puestoInstitucional . '"/>
		<input type="hidden" name="id_configuracion_cronograma_vacacion" id="id_configuracion_cronograma_vacacion" value="' . $idConfiguracionCronogramaVacacion  . '"/>
		
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

	public function construirDatosGeneralesCronogramaVacacionesAbrir($idCronogramaVacacion)
	{

		$cronogramaVacacionesLogicaNegocio = new CronogramaVacacionesLogicaNegocio();

		$datosCronogramaVacacion = $cronogramaVacacionesLogicaNegocio->buscar($idCronogramaVacacion);
		$idConfiguracionCronogramaVacacion = $datosCronogramaVacacion->getIdConfiguracionCronogramaVacacion();
		//$datos = ['id_configuracion_cronograma_vacacion' => $idConfiguracionCronogramaVacacion];
		$identificadorFuncionario = $datosCronogramaVacacion->getIdentificadorFuncionario();
		
		$qDatosFuncionario = $cronogramaVacacionesLogicaNegocio->obtenerDatosEmpleadoFechaIngresoInstitucion($identificadorFuncionario);
		$nombre = $qDatosFuncionario->current()->nombre;
		$fechaIngreso = $qDatosFuncionario->current()->fecha_ingreso_institucion;
		$unidadAdministrativa = $qDatosFuncionario->current()->nombre_unidad_administrativa;
		$gestionAdministrativa = $qDatosFuncionario->current()->nombre_gestion_administrativa;
		$puestoInstitucional = $qDatosFuncionario->current()->puesto_institucional;
		$idAreaPadre = $qDatosFuncionario->current()->id_area_padre;

		$qSaldoFuncionario = $cronogramaVacacionesLogicaNegocio->consultarSaldoFuncionario($identificadorFuncionario);

		if ($qSaldoFuncionario->count()) {
			$minutos = $qSaldoFuncionario->current()->minutos_disponibles;
		} else {
			$minutos = 0;
		}

		$qSaldoFuncionarioNuevo = $cronogramaVacacionesLogicaNegocio->consultarSaldoFuncionarioNuevo($identificadorFuncionario);

		if ($qSaldoFuncionarioNuevo->count()) {
			$minutosNuevo = $qSaldoFuncionarioNuevo->current()->minutos_disponibles;
		} else {
			$minutosNuevo = 0;
		}

		$minutos = $minutos + $minutosNuevo;

		$diasDisponibles = $cronogramaVacacionesLogicaNegocio->devolverFormatoDiasDisponibles($minutos);

		$datos = '
		<input type="hidden" name="id_area_padre" id="id_area_padre" value="' . $idAreaPadre . '"/>
		<input type="hidden" name="fecha_ingreso_institucion" id="fecha_ingreso_institucion" value="' . $fechaIngreso . '"/>
		<input type="hidden" name="nombre_puesto" id="nombre_puesto" value="' . $puestoInstitucional . '"/>
		<input type="hidden" name="id_configuracion_cronograma_vacacion" id="id_configuracion_cronograma_vacacion" value="' . $idConfiguracionCronogramaVacacion  . '"/>
		
		<fieldset>
		<legend>Cronograma de planificación</legend>
		<div data-linea="1">
			<label for="identificador">Identificador: </label>'
			. $identificadorFuncionario .
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

	public function construirDatosGeneralesCronogramaVacacionesNoConfigurado($arrayParametros)
	{
		$titulo = $arrayParametros['titulo'];
		$mensaje= $arrayParametros['mensaje'];
		
		$datos = '<fieldset>
					<legend>' . $titulo . '</legend>
					<div data-linea="1">
						<label for="observacion">Observacion: </label>' . $mensaje . '
					</div>
					</fieldset>';

		return $datos;
	}

	public function obtenerSolicitudesPlanificacionVacaciones()
	{

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

		$datos = ['id_cronograma_vacacion' => $idCronogramaVacacion,'estado_registro' => 'Activo'];

		$qCronogramaVacacion = $periodoCronogramaVacacionesLogicaNegocio->buscarLista($datos, 'numero_periodo asc');

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
												<th>Reprogramado</th>
											</tr>
										</thead>
										<tbody>';

		foreach ($qCronogramaVacacion as $item) {

			$totalDias = $totalDias + $item->total_dias;
			$reprogramado=$item->estado_reprogramacion != null ? strtoupper($item->estado_reprogramacion)   : 'NO';
			$datosPlanificarPeriodos .= '<tr>	
			<td>Periodo ' . $item->numero_periodo . '</td>
			<td style="text-align: center;">' . $this->fechaEs(date('d-m-Y', strtotime($item->fecha_inicio))) . '</td>
			<td style="text-align: center;">' . $this->fechaEs(date('d-m-Y', strtotime($item->fecha_fin))) . '</td>
			<td style="text-align: center;">' . $item->total_dias . '</td>
			<td style="text-align: center;">' . $reprogramado. '</td><tr>';
		}

		$datosPlanificarPeriodos .= '<tr>
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

	public function obtenerDatosFuncionarioBackup($identificadorFuncionario, $identificadorFuncionarioBackup = null, $habilitar = false)
	{
		$readOnly = "disabled";
		if ($habilitar) {
			$readOnly = "";
		}
		$comboFuncionarioBackup = '<select ' . $readOnly . ' name="identificador_backup" id="identificador_backup" class="validacion">';
		$comboFuncionarioBackup .= '<option value="">Seleccionar....</option>';

		$funcionarioBackup = $this->lNegocioDatosContrato->obtenerDatosFuncionarioBackup($identificadorFuncionario);

		foreach ($funcionarioBackup as $item) {
			if ($item->identificador == $identificadorFuncionarioBackup) {
				$comboFuncionarioBackup .= '<option selected value="' . $item->identificador . '">' . $item->nombre . '</option>';
			} else {
				$comboFuncionarioBackup .= '<option value="' . $item->identificador . '">' . $item->nombre . '</option>';
			}
		}
		$comboFuncionarioBackup .= '</select>';
		return $comboFuncionarioBackup;
	}


	public function fechaEs($fecha)
	{
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
		return $nombredia . ", " . $numeroDia . " de " . $nombreMes . " de " . $anio;
	}


	public function obtenerEstadoPlanificacionCronogramaVacaciones($estado)
	{
		$array = [
			"Finalizado" => "Aprobado",
			"EnviadoJefe" => "Enviado a Jefe Inmediato Superior",
			"EnviadoTthh" => "Enviado a Talento Humano",
			"EnviadoDe" => "Enviado a Director Ejecutivo",
			"Rechazado" => "Rechazado",
			"RechazadoReprogramacion" => "Reprogramación Rechazada",
			"RechazadoDe" => "Rechazado por el Director Ejecutivo",
		];
		return $array[$estado] ;
	}

	public function obtenerEstadoConfiguracionCronogramaVacaciones($estado)
	{
		$array = [
			"EnviadoDe" => "Por aprobar",
			"Finalizado" => "Aprobado",
			"RechazadoDe" => "Rechazado",
		
			
		];
		return $array[$estado] ;
	}

	
	public function construirDatosRevisionCronogramaVacaciones($idCronogramaVacacion)
	{
		$revisionCronogramaVacacionesLogicaNegocio = new RevisionCronogramaVacacionesLogicaNegocio();

		$datos = "";
		$arrayParametros = ['id_cronograma_vacacion' => $idCronogramaVacacion];

		$datosRevisionCronograma = $revisionCronogramaVacacionesLogicaNegocio->obtenerDatosUltimaRevisionCronograma($arrayParametros);

		if($datosRevisionCronograma->count()){

			$datos .= '<fieldset>
					<legend>Datos de revisión</legend>';
			
			foreach($datosRevisionCronograma as $item){

				$nombreRevisor = $item['nombre_revisor'];
				$observacion = ($item['observacion'] == "") ? 'Sin observación' : $item['observacion'];

				$datos .= '<div data-linea="1">
							<label for="revisor">Revisor: </label>' . $nombreRevisor . '
							</div>
							<div data-linea="2">
							<label for="observacion">Observación: </label>' . $observacion . '
							</div>';
			}

			$datos .= '</fieldset>';
		}	

		return $datos;
	}

	public function construirAprobacionReprogramacion($rutaArchivo){

		$archivoReprogramacion ='<fieldset>
									<legend>Reprogramación</legend>
									<input type="hidden" name="ruta_archivo_reprogramacion" id="ruta_archivo_reprogramacion" value="' . $rutaArchivo . '" readonly="readonly" />
									<div data-linea="3">
									<label>Archivo Pdf: </label>
									<a id="verReporteSolicitud" href="' . $rutaArchivo . '" target="_blank"> Descargar </a>
									</div>
								</fieldset>';
		return $archivoReprogramacion;
	}

}
