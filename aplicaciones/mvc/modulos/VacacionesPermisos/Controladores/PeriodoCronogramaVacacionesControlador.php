<?php

/**
 * Controlador PeriodoCronogramaVacaciones
 *
 * Este archivo controla la lógica del negocio del modelo:  PeriodoCronogramaVacacionesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-10-22
 * @uses    PeriodoCronogramaVacacionesControlador
 * @package VacacionesPermisos
 * @subpackage Controladores
 */

namespace Agrodb\VacacionesPermisos\Controladores;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\VacacionesPermisos\Modelos\ConfiguracionCronogramaVacacionesLogicaNegocio;
use Agrodb\VacacionesPermisos\Modelos\CronogramaVacacionesLogicaNegocio;
use Agrodb\VacacionesPermisos\Modelos\CronogramaVacacionesModelo;
use Agrodb\VacacionesPermisos\Modelos\PeriodoCronogramaVacacionesLogicaNegocio;
use Agrodb\VacacionesPermisos\Modelos\PeriodoCronogramaVacacionesModelo;

class PeriodoCronogramaVacacionesControlador extends BaseControlador
{

	private $lNegocioPeriodoCronogramaVacaciones = null;
	private $modeloPeriodoCronogramaVacaciones = null;
	private $lNegocioConfiguracionCronogramaVacaciones = null;
	private $modeloCronogramaVacaciones = null;
	private $lNegocioCronogramaVacaciones = null;


	private $accion = null;
	private $datosPlanificacion = null;
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->lNegocioPeriodoCronogramaVacaciones = new PeriodoCronogramaVacacionesLogicaNegocio();
		$this->modeloPeriodoCronogramaVacaciones = new PeriodoCronogramaVacacionesModelo();
		$this->lNegocioConfiguracionCronogramaVacaciones = new ConfiguracionCronogramaVacacionesLogicaNegocio();
		$this->modeloCronogramaVacaciones = new CronogramaVacacionesModelo();
		$this->lNegocioCronogramaVacaciones = new CronogramaVacacionesLogicaNegocio();

		set_exception_handler(array($this, 'manejadorExcepciones'));
	}
	/**
	 * Método de inicio del controlador
	 */
	public function index()
	{
		$modeloPeriodoCronogramaVacaciones = $this->lNegocioPeriodoCronogramaVacaciones->buscarPeriodoCronogramaVacaciones();
		$this->tablaHtmlPeriodoCronogramaVacaciones($modeloPeriodoCronogramaVacaciones);
		require APP . 'VacacionesPermisos/vistas/listaPeriodoCronogramaVacacionesVista.php';
	}
	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo()
	{
		$this->accion = "Nuevo PeriodoCronogramaVacaciones";
		require APP . 'VacacionesPermisos/vistas/formularioPeriodoCronogramaVacacionesVista.php';
	}
	/**
	 * Método para registrar en la base de datos -PeriodoCronogramaVacaciones
	 */
	public function guardar()
	{
		$this->lNegocioPeriodoCronogramaVacaciones->guardar($_POST);
	}
	/**
	 *Obtenemos los datos del registro seleccionado para editar - Tabla: PeriodoCronogramaVacaciones
	 */
	public function editar()
	{
		$this->accion = "Editar PeriodoCronogramaVacaciones";
		$this->modeloPeriodoCronogramaVacaciones = $this->lNegocioPeriodoCronogramaVacaciones->buscar($_POST["id"]);
		require APP . 'VacacionesPermisos/vistas/formularioPeriodoCronogramaVacacionesVista.php';
	}
	/**
	 * Método para borrar un registro en la base de datos - PeriodoCronogramaVacaciones
	 */
	public function borrar()
	{
		$this->lNegocioPeriodoCronogramaVacaciones->borrar($_POST['elementos']);
	}
	/**
	 * Construye el código HTML para desplegar la lista de - PeriodoCronogramaVacaciones
	 */
	public function tablaHtmlPeriodoCronogramaVacaciones($tabla)
	{
		$contador = 0;
		foreach ($tabla as $fila) {
			$this->itemsFiltrados[] = array(
				'<tr id="' . $fila['id_periodo_cronograma_vacacion'] . '"
				class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'VacacionesPermisos\periodocronogramavacaciones"
				data-opcion="editar" ondragstart="drag(event)" draggable="true"
				data-destino="detalleItem">
				<td>' . ++$contador . '</td>
				<td style="white - space:nowrap; "><b>' . $fila['id_periodo_cronograma_vacacion'] . '</b></td>
				<td>'
					. $fila['id_cronograma_vacacion'] . '</td>
				<td>' . $fila['numero_periodo']
					. '</td>
				<td>' . $fila['fecha_inicio'] . '</td>
				</tr>'
			);
		}
	}

	/**
	 * Método para registrar en la base de datos PeriodoCronogramaVacaciones Reprogramados
	 */
	public function guardarReprogramacionPeriodo()
	{


		$proceso = $this->lNegocioPeriodoCronogramaVacaciones->guardarReprogramacionPeriodo($_POST);;
		if ($proceso) {
			Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
		} else {
			Mensajes::fallo("A ocurrido un error, por favor comunicar con Dtics.");
		}
	}


	/**
	 * Método para abrir el formulario de reprogramamacion de vacaciones
	 */
	public function reprogramarPeriodo()
	{

		$idCronogramaVacacion = $_POST['elementos'];


		$datos = "estado_configuracion_cronograma_vacacion IN ('Finalizado')";
		$verificarConfiguracionCronograma = $this->lNegocioConfiguracionCronogramaVacaciones->buscarLista($datos);

		if ($verificarConfiguracionCronograma->count()) {

			if ($idCronogramaVacacion != '') {


				$this->modeloCronogramaVacaciones = $this->lNegocioCronogramaVacaciones->buscar($idCronogramaVacacion);
	
				$idConfiguracionCronogramaVacacion = $this->modeloCronogramaVacaciones->getIdConfiguracionCronogramaVacacion();
				$estadoCronogramaRegistro = $this->modeloCronogramaVacaciones->getEstadoCronogramaVacacion();
				
				$datosConfiguracionCronogramaVacacion = $this->lNegocioConfiguracionCronogramaVacaciones->buscar($idConfiguracionCronogramaVacacion);
				$anioPlanificacion = $datosConfiguracionCronogramaVacacion->getAnioConfiguracionCronogramaVacacion();
	
				$this->anioPlanificacion = $anioPlanificacion;
				$this->datosGenerales = $this->construirDatosGeneralesCronogramaVacacionesAbrir($idCronogramaVacacion);
				$this->accion = "Reprogramar vacaciones del cronograma ". $anioPlanificacion;
	
	
				$estado = false;
				switch ($estadoCronogramaRegistro) {
					case 'RechazadoReprogramacion':
						$estado = true;
						break;
				}
				$numeroPeriodos = $this->lNegocioPeriodoCronogramaVacaciones->obtenerNumeroPeriodos($this->modeloCronogramaVacaciones->getNumeroPeriodos(), $estado);
				$datosFuncionarioBackup = $this->obtenerDatosFuncionarioBackup($this->identificador, $this->modeloCronogramaVacaciones->getIdentificadorBackup(), $estado);
	
				$this->datosPlanificacion = '<fieldset>
												<legend>Datos de planificación</legend>
												<input type="hidden" name="id_cronograma_vacacion" id="id_cronograma_vacacion" value="' . $this->modeloCronogramaVacaciones->getIdCronogramaVacacion() . '" />
												<input type="hidden" name="anio_cronograma_vacacion" id="anio_cronograma_vacacion" value="' . $this->anioPlanificacion . '" />
										
												<div data-linea="5">
													<label for="identificador_backup">Funcionario reemplazo: </label>										
													' . $datosFuncionarioBackup . '										
												</div>
										
												<div data-linea="6">
													<label for="numero_periodos_planificar">Número de periodos a planificar: </label>										
													' . $numeroPeriodos . '										
												</div>	
											</fieldset>';

			}
			
		} else {
			$this->accion = "Reprogramar vacaciones del cronograma";
			$datos = ['titulo' => 'Cronograma de planificación', 'mensaje' => 'El cronograma no se encuentra aprobado. Usted no puede realizar una solicitud de reprogramación.'];
			$this->datosGenerales = $this->construirDatosGeneralesCronogramaVacacionesNoConfigurado($datos);
		}

		require APP . 'VacacionesPermisos/vistas/formularioReprogramarCronogramaVacacionesVista.php';
		
	}

	/**
	 * Método para contruir los periodos de reprogramamacion de vacaciones
	 */
	public function construirReprogramarPeriodos()
	{

		$idCronogramaVacacion = $_POST['id_cronograma_vacacion'];
		$numeroPeriodos = $_POST['numero_periodos'];
		$estadoCronograma = $_POST['estado_cronograma'];
		$datosPlanificarPeriodos = '<fieldset>
									<legend>Ingresar periodo</legend>
									<label>*Nota: </label><spam>Solo se reprogramarán los periodos seleccionados en la columna "Reprogramación".</spam>';
		$cantidadRegistros = 0;
		if (isset($idCronogramaVacacion)) {
			$arrayEstados = ['Primer Periodo:', 'Segundo Periodo:', 'Tercer Periodo:', 'Cuarto Periodo:'];
			if($estadoCronograma == "RechazadoReprogramacion"){
				$periodos = $this->lNegocioPeriodoCronogramaVacaciones->buscarLista(array('id_cronograma_vacacion' => $idCronogramaVacacion, 'estado_registro' => 'Activo'), 'numero_periodo ASC');
			}else{
				$periodos = $this->lNegocioPeriodoCronogramaVacaciones->buscarLista(array('id_cronograma_vacacion' => $idCronogramaVacacion, 'estado_registro' => 'Activo', 'estado_reprogramacion' => null), 'numero_periodo ASC');
			}

			$cantidadRegistros = count($periodos);
			if ($cantidadRegistros > 0) {
				$datosPlanificarPeriodos .= '<table id="tPeriodosPlanificar" style="width: 100%;">
												<thead>
													<tr>
														<th>Periodo</th>
														<th>Fecha inicio</th>
														<th>Número días</th>
														<th>Fecha retorno</th>
														<th>Seleccine para reprogramar</th>
													</tr>
												</thead>
										';
				$validacion = '';
				switch ($numeroPeriodos) {
					case '1':
						$validacion = 'onkeyup="calculo(this,' . "'^(3[0]{0,1})$'" . ');"';
						break;
					case '2':
						$validacion = 'onkeyup="calculo(this,' . "'^(1[5]{0,1})$'" . ');"';
						break;
					case '3':
						$validacion = 'onkeyup="calculo(this,' . "'^(1[0]|[1-9])$'" . ');"';
						break;
					case '4':
						$validacion = 'onkeyup="calculo(this,' . "'^([7-9])$'" . ');"';
						break;
				}


				foreach ($periodos as $item) {
					$datosPlanificarPeriodos .= '<tbody>
					<tr>	
						<td style="font-weight: bold;">' . $arrayEstados[($item->numero_periodo - 1)] . '<input type="hidden" name="hPeriodo['.$item->numero_periodo.']" value="1"></td>
						<td><input value=' . $item->numero_periodo . ' type="hidden" class="piNumeroPeriodo" name="hNumeroPeriodo['.$item->numero_periodo.']" readonly="readonly">
						<input value=' . $item->id_periodo_cronograma_vacacion . ' type="hidden" class="piPeriodoCronogramaVacacion" name="hIdPeriodoCronogramaVacacion['.$item->numero_periodo.']" readonly="readonly">
						<input value=' . $item->fecha_inicio . ' type="text" class="piFechaInicio" name="hFechaInicio['.$item->numero_periodo.']" readonly="readonly"></td>
						<td><input value=' . $item->total_dias . ' type="text" class="piNumeroDias" name="hNumeroDias['.$item->numero_periodo.']" ' . $validacion . '></td>
						<td><input value=' . $item->fecha_fin . ' type="text" class="piFechaFin" name="hFechaFin['.$item->numero_periodo.']" readonly="readonly"></td>
						<td style="text-align: center;"><input type="checkbox" name="hReprogramado['.$item->numero_periodo.']" value="Si" class="reprogramar"></td>;
					</tr>
				</tbody>';
				}
				$datosPlanificarPeriodos .= '</table>';
			}
		}
		if ($cantidadRegistros > 0) {
			$datosPlanificarPeriodos .= '<div data-linea="1">
											<label for="total_dias_planificados">Días planificados: </label>
											<label for="total_dias" id="total_dias">0</label>
											<input type="hidden" id="total_dias_planificados" name="total_dias_planificados" value="" />
										</div>
										</fieldset>
										<button  type="submit" class="guardar">Guardar</button>';
		} else {
			$datosPlanificarPeriodos .= '<div data-linea="1">
											<label for="observacion">Observacion: </label>No existen registro de periodos para reprogramar.
										</div>
										</fieldset>';
		}

		echo json_encode(array(
			'estado' => 'EXITO',
			'datosPlanificarPeriodos' => $datosPlanificarPeriodos
		));
		//return $datosPlanificarPeriodos;
	}
}
