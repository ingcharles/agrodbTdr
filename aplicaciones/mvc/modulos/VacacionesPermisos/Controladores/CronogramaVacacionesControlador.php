<?php

/**
 * Controlador CronogramaVacaciones
 *
 * Este archivo controla la lógica del negocio del modelo:  CronogramaVacacionesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-10-22
 * @uses    CronogramaVacacionesControlador
 * @package VacacionesPermisos
 * @subpackage Controladores
 */

namespace Agrodb\VacacionesPermisos\Controladores;

use Agrodb\GUath\Modelos\DatosContratoLogicaNegocio;
use Agrodb\VacacionesPermisos\Modelos\CronogramaVacacionesLogicaNegocio;
use Agrodb\VacacionesPermisos\Modelos\CronogramaVacacionesModelo;
use Agrodb\VacacionesPermisos\Modelos\PeriodoCronogramaVacacionesLogicaNegocio;
use Agrodb\VacacionesPermisos\Modelos\PeriodoCronogramaVacacionesModelo;

class CronogramaVacacionesControlador extends BaseControlador
{

	private $lNegocioCronogramaVacaciones = null;
	private $modeloCronogramaVacaciones = null;

	private $accion = null;
	private $datosGenerales = null;
	private $numeroPeriodos = null;
	private $lNegocioPeriodoCronogramaVacaciones = null;
	private $datosFuncionarioBackup = null;
	private $lNegocioDatosContrato = null;
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->lNegocioCronogramaVacaciones = new CronogramaVacacionesLogicaNegocio();
		$this->modeloCronogramaVacaciones = new CronogramaVacacionesModelo();
		$this->lNegocioDatosContrato = new DatosContratoLogicaNegocio();
		$this->lNegocioPeriodoCronogramaVacaciones = new PeriodoCronogramaVacacionesLogicaNegocio();
		set_exception_handler(array($this, 'manejadorExcepciones'));
	}
	/**
	 * Método de inicio del controlador
	 */
	public function index()
	{
		$modeloCronogramaVacaciones = $this->lNegocioCronogramaVacaciones->buscarCronogramaVacaciones();
		$this->tablaHtmlCronogramaVacaciones($modeloCronogramaVacaciones);
		require APP . 'VacacionesPermisos/vistas/listaCronogramaVacacionesVista.php';
	}
	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo()
	{
		$anioPlanificacion = (date('Y') + 1);
		$this->accion = "Nueva solicitud de planificación año " . $anioPlanificacion;
		$this->datosGenerales = $this->construirDatosGeneralesCronogramaVacaciones();
		$this->numeroPeriodos = $this->obtenerNumeroPeriodos();
		$this->datosFuncionarioBackup = $this->obtenerDatosFuncionarioBackup($this->identificador);
		$this->anioPlanificacion = $anioPlanificacion;
		require APP . 'VacacionesPermisos/vistas/formularioCronogramaVacacionesVista.php';
	}
	/**
	 * Método para registrar en la base de datos -CronogramaVacaciones
	 */
	public function guardar()
	{
		$this->lNegocioCronogramaVacaciones->guardar($_POST);
	}

	public function actualizarPlanificacion(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$lista = '';

		$_POST['identificador_registro'] = $_SESSION['usuario'];
		$id = $this->lNegocioCronogramaVacaciones->actualizarPlanificacionVacaciones($_POST);
		if ($id != 0) {
			$contenido = $id;
			// if ($_POST['accion'] == 'Nuevo Registro'){
			// 	$lista = $this->listarDestinatariosRegistrados($id);
			// }else{
			// 	$lista = $this->listarDestinatariosRegistrados($id, 'No');
			// }
		} else {
			$estado = 'FALLO';
			$mensaje = 'Error al guardar el registro !!';
		}
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'lista' => $lista,
			'contenido' => $contenido
		));
	}

	public function guardarPlanificacion()
	{
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$lista = '';

		$_POST['identificador_registro'] = $_SESSION['usuario'];
		$id = $this->lNegocioCronogramaVacaciones->guardarPlanificacionVacaciones($_POST);
		if ($id != 0) {
			$contenido = $id;
			// if ($_POST['accion'] == 'Nuevo Registro'){
			// 	$lista = $this->listarDestinatariosRegistrados($id);
			// }else{
			// 	$lista = $this->listarDestinatariosRegistrados($id, 'No');
			// }
		} else {
			$estado = 'FALLO';
			$mensaje = 'Error al guardar el registro !!';
		}
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'lista' => $lista,
			'contenido' => $contenido
		));
	}
	/**
	 *Obtenemos los datos del registro seleccionado para editar - Tabla: CronogramaVacaciones
	 */
	public function editar()
	{
		$anioPlanificacion = (date('Y') + 1);
		$this->datosGenerales = $this->construirDatosGeneralesCronogramaVacaciones();

		//lamar al backup del usuario 
		//buscar en la tabla
		$this->anioPlanificacion = $anioPlanificacion;
		$this->accion = "Editar CronogramaVacaciones";
		$this->modeloCronogramaVacaciones = $this->lNegocioCronogramaVacaciones->buscar($_POST["id"]);

		$this->numeroPeriodos = $this->obtenerNumeroPeriodos($this->modeloCronogramaVacaciones->getNumeroPeriodos());
		$this->datosFuncionarioBackup = $this->obtenerDatosFuncionarioBackup($this->identificador, $this->modeloCronogramaVacaciones->getIdentificadorBackup());
		require APP . 'VacacionesPermisos/vistas/formularioEditarCronogramaVacacionesVista.php';
	}
	/**
	 * Método para borrar un registro en la base de datos - CronogramaVacaciones
	 */
	public function borrar()
	{
		$this->lNegocioCronogramaVacaciones->borrar($_POST['elementos']);
	}
	/**
	 * Construye el código HTML para desplegar la lista de - CronogramaVacaciones
	 */
	public function tablaHtmlCronogramaVacaciones($tabla)
	{ {
			$contador = 0;
			foreach ($tabla as $fila) {
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_cronograma_vacacion'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'VacacionesPermisos\cronogramavacaciones"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_cronograma_vacacion'] . '</b></td>
<td>'
						. $fila['identificador'] . '</td>
<td>' . $fila['fecha_ingreso_institucion']
						. '</td>
<td>' . $fila['id_puesto'] . '</td>
</tr>'
				);
			}
		}
	}

	public function obtenerDatosFuncionarioBackup($identificadorFuncionario, $identificadorFuncionarioBackup = null)
	{

		$comboFuncionarioBackup = '<option value="">Seleccionar....</option>';

		$funcionarioBackup = $this->lNegocioDatosContrato->obtenerDatosFuncionarioBackup($identificadorFuncionario);

		foreach ($funcionarioBackup as $item) {
			if ($item->identificador == $identificadorFuncionarioBackup) {
				$comboFuncionarioBackup .= '<option selected value="' . $item->identificador . '">' . $item->nombre . '</option>';
			} else {
				$comboFuncionarioBackup .= '<option value="' . $item->identificador . '">' . $item->nombre . '</option>';
			}
		}

		return $comboFuncionarioBackup;
	}

	public function obtenerNumeroPeriodos($numeroPeriodos = null)
	{


		$comboNumeroPeriodos = "";
		$arrayEstados = array(
			'1' => 'Un periodo', '2' => 'Dos periodos', '3' => 'Tres periodos', '4' => 'Cuatro periodos'
		);
		foreach ($arrayEstados as $llaveEstado => $valorEstado) {
			if ($numeroPeriodos == $llaveEstado) {
				$comboNumeroPeriodos .= '<option value="' . $llaveEstado . '" selected>' . $valorEstado . '</option>';
			} else {
				$comboNumeroPeriodos .= '<option value="' . $llaveEstado . '" >' . $valorEstado . '</option>';
			}
		}

		return $comboNumeroPeriodos;
	}

	public function construirPlanificarPeriodos()
	{

		$numeroPeriodosPlanificar = $_POST['numero_periodos_planificar'];

		$datosPlanificarPeriodos = '<fieldset>
									<legend>Ingresar periodo</legend>';

		if (isset($_POST['id_cronograma_vacacion'])) {
			$idCronograma = $_POST['id_cronograma_vacacion'];
			$periodos = $this->lNegocioPeriodoCronogramaVacaciones->buscarLista(array('id_cronograma_vacacion' => $idCronograma,'estado_registro'=>'Activo'));
			$datosPlanificarPeriodos .= '<table id="tPeriodosPlanificar" style="width: 100%;">
												<thead>
													<tr>
														<th>Periodo</th>
														<th>Fecha inicio</th>
														<th>Número días</th>
														<th>Fecha retorno</th>
													</tr>
												</thead>
												
										';
			foreach ($periodos as $item) {
				$datosPlanificarPeriodos .= '<tbody>
			<tr>	
				<td style="font-weight: bold;">Primer periodo<input type="hidden" name="hPeriodo[]" value="1"></td>
				<td><input value=' . $item->fecha_inicio . ' type="text" class="piFechaInicio" name="hFechaInicio[]" readonly="readonly"></td>
				<td><input value=' . $item->total_dias . ' type="text" class="piNumeroDias" name="hNumeroDias[]" id="diaPrimerPeriodo" onkeyup="calculo(this,' . "'^(3[0]{0,1})$'" . ');"></td>
				<td><input value=' . $item->fecha_fin . ' type="text" class="piFechaFin" name="hFechaFin[]" readonly="readonly"></td>
			</tr>
		</tbody>';
			}
			$datosPlanificarPeriodos .= '	</table>';
		} else {
			switch ($numeroPeriodosPlanificar) {

				case '1':

					$datosPlanificarPeriodos .= '<table id="tPeriodosPlanificar" style="width: 100%;">
												<thead>
													<tr>
														<th>Periodo</th>
														<th>Fecha inicio</th>
														<th>Número días</th>
														<th>Fecha retorno</th>
													</tr>
												</thead>
												<tbody>
													<tr>	
														<td style="font-weight: bold;">Primer periodo<input type="hidden" name="hPeriodo[]" value="1"></td>
														<td><input type="text" class="piFechaInicio" name="hFechaInicio[]" readonly="readonly"></td>
														<td><input type="text" class="piNumeroDias" name="hNumeroDias[]" id="diaPrimerPeriodo" onkeyup="calculo(this,' . "'^(3[0]{0,1})$'" . ');"></td>
														<td><input type="text" class="piFechaFin" name="hFechaFin[]" readonly="readonly"></td>
													</tr>
												</tbody>
											</table>';

					break;

				case '2':
					$datosPlanificarPeriodos .= '<table id="tPeriodosPlanificar" style="width: 100%;">
												<thead>
													<tr>
														<th>Periodo</th>
														<th>Fecha inicio</th>
														<th>Número días</th>
														<th>Fecha fin</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td style="font-weight: bold;">Primer periodo<input type="hidden" name="hPeriodo[]" value="1"></td>
														<td><input type="text" class="piFechaInicio" name="hFechaInicio[]" readonly="readonly"></td>
														<td><input type="text" class="piNumeroDias" name="hNumeroDias[]" onkeyup="calculo(this,' . "'^(1[5]{0,1})$'" . ');"></td>
														<td><input type="text" class="piFechaFin" name="hFechaFin[]" readonly="readonly"></td>
													</tr>
													<tr>
														<td style="font-weight: bold;">Segundo periodo<input type="hidden" name="hPeriodo[]" value="2"></td>
														<td><input type="text" class="piFechaInicio" name="hFechaInicio[]" readonly="readonly"></td>
														<td><input type="text" class="piNumeroDias" name="hNumeroDias[]" onkeyup="calculo(this,' . "'^(1[5]{0,1})$'" . ');"></td>
														<td><input type="text"  class="piFechaFin" name="hFechaFin[]" readonly="readonly" ></td>
													</tr>
												</tbody>
											</table>';
					break;
				case '3':
					$datosPlanificarPeriodos .= '<table id="tPeriodosPlanificar" style="width: 100%;">
												<thead>
													<tr>
														<th>Periodo</th>
														<th>Fecha inicio</th>
														<th>Número días</th>
														<th>Fecha fin</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td style="font-weight: bold;">Primer periodo<input type="hidden" name="hPeriodo[]" value="1"></td>
														<td><input type="text" class="piFechaInicio" name="hFechaInicio[]" readonly="readonly"></td>
														<td><input type="text" class="piNumeroDias"  name="hNumeroDias[]" onkeyup="calculo(this,' . "'^(1[0]|[1-9])$'" . ');"></td>
														<td><input type="text" class="piFechaFin" name="hFechaFin[]" readonly="readonly"></td>
													</tr>
													<tr>
														<td style="font-weight: bold;">Segundo periodo<input type="hidden" name="hPeriodo[]" value="2"></td>
														<td><input type="text" class="piFechaInicio" name="hFechaInicio[]" readonly="readonly"></td>
														<td><input type="text" class="piNumeroDias" name="hNumeroDias[]" onkeyup="calculo(this,' . "'^(1[0]|[1-9])$'" . ');"></td>
														<td><input type="text"  class="piFechaFin" name="hFechaFin[]" readonly="readonly"></td>
													</tr>
													<tr>
														<td style="font-weight: bold;">Tercer periodo<input type="hidden" name="hPeriodo[]" value="3"></td>
														<td><input type="text" class="piFechaInicio" name="hFechaInicio[]" readonly="readonly"></td>
														<td><input type="text" class="piNumeroDias" name="hNumeroDias[]" onkeyup="calculo(this,' . "'^(1[0]|[1-9])$'" . ');"></td>
														<td><input type="text"  class="piFechaFin" name="hFechaFin[]" readonly="readonly"></td>
													</tr>
												</tbody>
											</table>';
					break;
				case '4':
					$datosPlanificarPeriodos .= '<table id="tPeriodosPlanificar" style="width: 100%;">
												<thead>
													<tr>
														<th>Periodo</th>
														<th>Fecha inicio</th>
														<th>Número días</th>
														<th>Fecha fin</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td style="font-weight: bold;">Primer periodo<input type="hidden" name="hPeriodo[]" value="1"></td>
														<td><input type="text" class="piFechaInicio" name="hFechaInicio[]" readonly="readonly"></td>
														<td><input type="text" class="piNumeroDias"  name="hNumeroDias[]" onkeyup="calculo(this,' . "'^([7-9])$'" . ');"></td>
														<td><input type="text" class="piFechaFin" name="hFechaFin[]" readonly="readonly"></td>
													</tr>
													<tr>
														<td style="font-weight: bold;">Segundo periodo<input type="hidden" name="hPeriodo[]" value="2"></td>
														<td><input type="text" class="piFechaInicio" name="hFechaInicio[]" readonly="readonly"></td>
														<td><input type="text" class="piNumeroDias" name="hNumeroDias[]" onkeyup="calculo(this,' . "'^([7-9])$'" . ');"></td>
														<td><input type="text"  class="piFechaFin" name="hFechaFin[]" readonly="readonly"></td>
													</tr>
													<tr>
													<td style="font-weight: bold;">Tercer periodo<input type="hidden" name="hPeriodo[]" value="3"></td>
													<td><input type="text" class="piFechaInicio" name="hFechaInicio[]" readonly="readonly"></td>
													<td><input type="text" class="piNumeroDias" name="hNumeroDias[]" onkeyup="calculo(this,' . "'^([7-9])$'" . ');"></td>
													<td><input type="text"  class="piFechaFin" name="hFechaFin[]" readonly="readonly"></td>
												</tr>
												<tr>
													<td style="font-weight: bold;">Cuarto periodo<input type="hidden" name="hPeriodo[]" value="4"></td>
													<td><input type="text" class="piFechaInicio" name="hFechaInicio[]" readonly="readonly"></td>
													<td><input type="text" class="piNumeroDias" name="hNumeroDias[]" onkeyup="calculo(this,' . "'^([7-9])$'" . ');"></td>
													<td><input type="text"  class="piFechaFin" name="hFechaFin[]" readonly="readonly"></td>
												</tr>
												</tbody>
											</table>';
					break;
			}
		}




		$datosPlanificarPeriodos .= '<div data-linea="1">
										<label for="total_dias_planificados">Días planificados: </label>
										<label for="total_dias" id="total_dias">0</label>
										<input type="hidden" id="total_dias_planificados" name="total_dias_planificados" value="" />
									</div>				
								</fieldset>';

		echo json_encode(array(
			'estado' => 'EXITO',
			'datosPlanificarPeriodos' => $datosPlanificarPeriodos
		));
	}
}
