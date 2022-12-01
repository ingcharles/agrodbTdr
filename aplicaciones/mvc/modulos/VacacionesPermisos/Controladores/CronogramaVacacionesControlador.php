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

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\GUath\Modelos\FichaEmpleadoLogicaNegocio;
use Agrodb\Usuarios\Modelos\UsuariosLogicaNegocio;
use Agrodb\VacacionesPermisos\Modelos\ConfiguracionCronogramaVacacionesLogicaNegocio;

class CronogramaVacacionesControlador extends BaseControlador
{

	private $lNegocioCronogramaVacaciones = null;
	private $modeloCronogramaVacaciones = null;

	private $accion = null;
	private $datosGenerales = null;
	private $numeroPeriodos = null;
	private $lNegocioPeriodoCronogramaVacaciones = null;
	private $lNegocioConfiguracionCronogramaVacaciones = null;
	private $lNegocioUsuario=null;
	private $datosFuncionarioBackup = null;
	private $datosPeriodoCronograma = null;
	private $lNegocioDatosContrato = null;
	private $panelBusqueda = null;
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
		$this->lNegocioConfiguracionCronogramaVacaciones = new ConfiguracionCronogramaVacacionesLogicaNegocio();
		$this->lNegocioUsuario=new FichaEmpleadoLogicaNegocio();
		set_exception_handler(array($this, 'manejadorExcepciones'));
	}
	/**
	 * Método de inicio del controlador
	 */
	public function index()
	{
		$identificadorFuncionario = $this->identificador;
		$modeloCronogramaVacaciones = $this->lNegocioCronogramaVacaciones->buscarLista(array('identificador_funcionario' => $identificadorFuncionario));
		$this->panelBusqueda = $this->cargarPanelBusquedaSolicitud();
		$this->tablaHtmlCronogramaVacaciones($modeloCronogramaVacaciones);
		require APP . 'VacacionesPermisos/vistas/listaCronogramaVacacionesVista.php';
	}
	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo()
	{

		$datos = ['estado_configuracion_cronograma_vacacion' => 'Activo'];

		$verificarConfiguracionCronograma = $this->lNegocioConfiguracionCronogramaVacaciones->buscarLista($datos);

		if($verificarConfiguracionCronograma->count()){

			$anioPlanificacion = $verificarConfiguracionCronograma->current()->anio_configuracion_cronograma_vacacion;
			$this->accion = "Nueva solicitud de planificación año " . $anioPlanificacion;
			$this->datosGenerales = $this->construirDatosGeneralesCronogramaVacaciones();
			$this->numeroPeriodos = $this->obtenerNumeroPeriodos(null, true);
			$this->datosPeriodoCronograma = $this->construirDatosPlanificacionCronograma($anioPlanificacion);
			$this->anioPlanificacion = $anioPlanificacion;
			
		}else{
			$this->accion = "Nueva solicitud de planificación año.";
			$this->datosGenerales = $this->construirDatosGeneralesCronogramaVacacionesNoConfigurado();
		}
		
		require APP . 'VacacionesPermisos/vistas/formularioCronogramaVacacionesVista.php';
	}
	/**
	 * Método para registrar en la base de datos -CronogramaVacaciones
	 */
	public function guardar()
	{

		$proceso = $this->lNegocioCronogramaVacaciones->guardar($_POST);;

		if ($proceso) {
			Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
		}
	}

	public function actualizarPlanificacion()
	{
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
		$filtro=['identificador'=>$_SESSION['usuario']];
		$usuario=$this->lNegocioUsuario->buscarLista($filtro);
		$nombreUsuario=$usuario->current()->nombre .' '. $usuario->current()->apellido;
		$_POST['identificador_registro'] = $_SESSION['usuario'];
		$_POST['nombre_funcionario']=$nombreUsuario;
		

		$existe = $this->lNegocioCronogramaVacaciones->buscarLista(array('identificador_funcionario' => $_POST['identificador_registro'], 'anio_cronograma_vacacion' => (int)$_POST['anio_cronograma_vacacion']));

		if (!$existe->count()) {
			$id = $this->lNegocioCronogramaVacaciones->guardarPlanificacionVacaciones($_POST);
			if ($id != 0) {
				Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
			} else {
				Mensajes::fallo(Constantes::ERROR_AL_GUARDAR);
			}
		} else {

			$mensaje = 'Ya existe una planificación en este año.';
			Mensajes::fallo($mensaje);
		}
	}
	/**
	 *Obtenemos los datos del registro seleccionado para editar - Tabla: CronogramaVacaciones
	 */
	public function editar()
	{
		
		$idCronogramaVacacion = $_POST['id'];		
		
		$this->modeloCronogramaVacaciones = $this->lNegocioCronogramaVacaciones->buscar($idCronogramaVacacion);

		$idConfiguracionCronogramaVacacion = $this->modeloCronogramaVacaciones->getIdConfiguracionCronogramaVacacion();

		$datosConfiguracionCronogramaVacacion = $this->lNegocioConfiguracionCronogramaVacaciones->buscar($idConfiguracionCronogramaVacacion);
		$anioPlanificacion = $datosConfiguracionCronogramaVacacion->getAnioConfiguracionCronogramaVacacion();

		$this->anioPlanificacion = $anioPlanificacion;
		$this->accion = "Editar solicitud de planificación " . $anioPlanificacion;

		$this->datosGenerales = $this->construirDatosGeneralesCronogramaVacacionesAbrir($idCronogramaVacacion);

		//lamar al backup del usuario 
		//buscar en la tabla
		
		$estadoCronogramaRegistro = $this->modeloCronogramaVacaciones->getEstadoCronogramaVacacion();
		$estado = false;
		switch ($estadoCronogramaRegistro) {
			case 'Rechazado':
				$estado = true;
				break;
		}
		$this->numeroPeriodos = $this->obtenerNumeroPeriodos($this->modeloCronogramaVacaciones->getNumeroPeriodos(), $estado);
		$this->datosFuncionarioBackup = $this->obtenerDatosFuncionarioBackup($this->identificador, $this->modeloCronogramaVacaciones->getIdentificadorBackup(), $estado);
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
					'<tr style="text-align:center; " id="' . $fila['id_cronograma_vacacion'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'VacacionesPermisos\cronogramavacaciones"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td >' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_cronograma_vacacion'] . '</b></td>
<td>'
						. $fila['identificador_funcionario'] . '</td>
<td>' . 	date('Y/m/d',strtotime($fila['fecha_ingreso_institucion']))
						. '</td>
<td>' . $fila['identificador_backup'] . '</td>
<td>' . $fila['total_dias_planificados'] . '</td>
<td>' . $fila['estado_cronograma_vacacion'] . '</td>
</tr>'
				);
			}
		}
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

	public function obtenerNumeroPeriodos($numeroPeriodos = null, $habilitar = false)
	{

		$readOnly = "disabled";
		if ($habilitar) {
			$readOnly = "";
		}

		$arrayEstados = array(
			'1' => 'Un periodo', '2' => 'Dos periodos', '3' => 'Tres periodos', '4' => 'Cuatro periodos'
		);
		$comboNumeroPeriodos = '<select ' . $readOnly . ' name="numero_periodos" id="numero_periodos"><option value="">Seleccionar...</option>';
		foreach ($arrayEstados as $llaveEstado => $valorEstado) {
			if ($numeroPeriodos == $llaveEstado) {
				$comboNumeroPeriodos .= '<option value="' . $llaveEstado . '" selected>' . $valorEstado . '</option>';
			} else {
				$comboNumeroPeriodos .= '<option value="' . $llaveEstado . '" >' . $valorEstado . '</option>';
			}
		}
		$comboNumeroPeriodos .= '</select>';
		return $comboNumeroPeriodos;
	}

	public function construirPlanificarPeriodos()
	{

		$numeroPeriodosPlanificar = $_POST['numero_periodos_planificar'];

		$datosPlanificarPeriodos = '<fieldset>
									<legend>Ingresar periodo</legend>';

		if (isset($_POST['id_cronograma_vacacion'])) {
			$idCronograma = $_POST['id_cronograma_vacacion'];
			$arrayEstados = ['Primer Periodo:','Segundo Periodo:','Tercer Periodo:','Cuarto Periodo:'];
			
			$periodos = $this->lNegocioPeriodoCronogramaVacaciones->buscarLista(array('id_cronograma_vacacion' => $idCronograma, 'estado_registro' => 'Activo'),'numero_periodo asc');
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
			$periodo=0;
			foreach ($periodos as $item) {
				$datosPlanificarPeriodos .= '<tbody>
			<tr>	
				<td style="font-weight: bold;">' . $arrayEstados[$periodo] . '<input type="hidden" name="hPeriodo[]" value="1"></td>
				<td><input value=' . $item->fecha_inicio . ' type="text" class="piFechaInicio" name="hFechaInicio[]" readonly="readonly"></td>
				<td><input value=' . $item->total_dias . ' type="text" class="piNumeroDias" name="hNumeroDias[]" id="diaPrimerPeriodo" onkeyup="calculo(this,' . "'^(3[0]{0,1})$'" . ');"></td>
				<td><input value=' . $item->fecha_fin . ' type="text" class="piFechaFin" name="hFechaFin[]" readonly="readonly"></td>
			</tr>
		</tbody>';
		$periodo++;
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



	public function cargarPanelBusquedaSolicitud()
	{

		//TODO: Recibir el perfil para mostrar mas filtros

		$panelBusqueda = '<table id="fBusqueda" class="filtro" style="width: 400px;">
                        				<tbody>
											<tr>
												<th colspan="5">Buscar:</th>
											</tr>
											
                        					<tr>
                        						<td colspan="1">Estado: </td>
                        						<td colspan="4">
												<select id="estado_cronograma_vacacion" name="estado_cronograma_vacacion"  style="width: 100%" class="validacion">
												<option value="">Seleccione...</option>
												<option value="Creado">Creado</option>
												<option value="EnviadoJefe">Revisión Jefe</option>
												<option value="Rechazado">Rechazado Jefe</option>
												<option value="Aprobado">Aprobado</option>
												</select>
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

	public function listarSolicitudesCronogramaVacacion()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
		$identificadorFuncionario = $this->identificador;
      	$estadoCronogramaVacacion =  $_POST['estado_cronograma_vacacion'];

		$arrayParametros = ['identificador_funcionario'=> $identificadorFuncionario
							, 'estado_cronograma_vacacion'=> $estadoCronogramaVacacion];

        $solicitudesModificacion = $this->lNegocioCronogramaVacaciones->buscarCronogramaVacacionesFiltro($arrayParametros);

		if ($solicitudesModificacion->count()) {
			$this->tablaHtmlCronogramaVacaciones($solicitudesModificacion);
			$contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
		} else {
			$contenido = \Zend\Json\Json::encode('');
			$mensaje = 'No existen registros';
			$estado = 'FALLO';
		}

		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido
		));
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function reprogramar()
	{
		$idCronogramaVacacion = $_POST['id'];		
		
		$this->modeloCronogramaVacaciones = $this->lNegocioCronogramaVacaciones->buscar($idCronogramaVacacion);

		$idConfiguracionCronogramaVacacion = $this->modeloCronogramaVacaciones->getIdConfiguracionCronogramaVacacion();

		$datosConfiguracionCronogramaVacacion = $this->lNegocioConfiguracionCronogramaVacaciones->buscar($idConfiguracionCronogramaVacacion);
		$anioPlanificacion = $datosConfiguracionCronogramaVacacion->getAnioConfiguracionCronogramaVacacion();

		$this->anioPlanificacion = $anioPlanificacion;
		$this->accion = "Editar solicitud de planificación " . $anioPlanificacion;

		$this->datosGenerales = $this->construirDatosGeneralesCronogramaVacacionesAbrir($idCronogramaVacacion);

		$idSolicitud = $_POST['elementos'];
		if ($idSolicitud != '') {
			//lamar al backup del usuario 
			//buscar en la tabla
			$this->anioPlanificacion = $anioPlanificacion;
			$this->accion = "Reprogramar Vacaciones";
			$this->modeloCronogramaVacaciones = $this->lNegocioCronogramaVacaciones->buscar($idSolicitud);
			$estadoCronogramaRegistro = $this->modeloCronogramaVacaciones->getEstadoCronogramaVacacion();
			$estado = false;
			switch ($estadoCronogramaRegistro) {
				case 'Rechazado':
					$estado = true;
					break;
			}
			$this->numeroPeriodos = $this->obtenerNumeroPeriodos($this->modeloCronogramaVacaciones->getNumeroPeriodos(), $estado);
			$this->datosFuncionarioBackup = $this->obtenerDatosFuncionarioBackup($this->identificador, $this->modeloCronogramaVacaciones->getIdentificadorBackup(), $estado);
			require APP . 'VacacionesPermisos/vistas/formularioReprogramarCronogramaVacacionesVista.php';
		}
	}

	public function construirDatosPlanificacionCronograma($anioPlanificacion) {
		
		$funcionarioBackup = $this->obtenerDatosFuncionarioBackup($this->identificador, null, true);
		
		$datos = '<fieldset>
					<legend>Datos planificación</legend>
					<input type="hidden" name="anio_cronograma_vacacion" id="anio_cronograma_vacacion" value="' . $anioPlanificacion  . '" />

					<div data-linea="5">
						<label for="identificador_backup">Funcionario reemplazo: </label>
						' . $funcionarioBackup . '
					</div>
					<div data-linea="6">
						<label for="numero_periodos">Número de periodos a planificar: </label>
						' . $this->numeroPeriodos . '
					</div>
				</fieldset>
				<div id="dDatosPeriodo"></div>
				<div id="datosPlanificarPeriodos"> </div>
				<div data-linea="17">
					<button type="submit" class="guardar">Guardar</button>
				</div>';
		
		return $datos;
				
	}

}
