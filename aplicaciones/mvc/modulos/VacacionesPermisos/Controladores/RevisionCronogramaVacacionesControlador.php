<?php

/**
 * Controlador RevisionCronogramaVacaciones
 *
 * Este archivo controla la lógica del negocio del modelo:  RevisionCronogramaVacacionesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-10-22
 * @uses    RevisionCronogramaVacacionesControlador
 * @package VacacionesPermisos
 * @subpackage Controladores
 */

namespace Agrodb\VacacionesPermisos\Controladores;

use Agrodb\Catalogos\Modelos\ResponsablesCertificadosLogicaNegocio;
use Agrodb\GUath\Modelos\FichaEmpleadoLogicaNegocio;
use Agrodb\Usuarios\Modelos\UsuariosPerfilesLogicaNegocio;
use Agrodb\VacacionesPermisos\Modelos\CronogramaVacacionesLogicaNegocio;
use Agrodb\VacacionesPermisos\Modelos\RevisionCronogramaVacacionesLogicaNegocio;
use Agrodb\VacacionesPermisos\Modelos\RevisionCronogramaVacacionesModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\FirmaDocumentos\Modelos\FirmantesLogicaNegocio;
use Agrodb\VacacionesPermisos\Modelos\ConfiguracionCronogramaVacacionesLogicaNegocio;
use Agrodb\VacacionesPermisos\Modelos\ConfiguracionCronogramaVacacionesModelo;
use Agrodb\VacacionesPermisos\Modelos\PeriodoCronogramaVacacionesLogicaNegocio;

class RevisionCronogramaVacacionesControlador extends BaseControlador
{

	private $lNegocioRevisionCronogramaVacaciones = null;
	private $modeloRevisionCronogramaVacaciones = null;
	private $lNegocioCronogramaVacaciones = null;
	private $lNegocioUsuariosPerfiles = null;
	private $lNegocioFichaEmpleado = null;
	private $lNegocioConfiguracionCronogramaVacaciones = null;
	private $lNegocioPeriodoCronogramaVacaciones = null;
	private $lNegocioFirmantes = null;
	private $descripcionConfiguracionCronogramaVacaciones = null;
	private $accion = null;
	private $article = null;
	private $datosGenerales = null;
	private $periodoCronograma = null;
	private $resultadoRevision = null;
	private $panelBusqueda = null;
	private $perfilUsuarioDirector = null;
	private $esReprogramacion = null;
	private $bloqueAprobacionReprogramacion = null;
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->lNegocioRevisionCronogramaVacaciones = new RevisionCronogramaVacacionesLogicaNegocio();
		$this->modeloRevisionCronogramaVacaciones = new RevisionCronogramaVacacionesModelo();
		$this->lNegocioUsuariosPerfiles = new UsuariosPerfilesLogicaNegocio();
		$this->lNegocioFichaEmpleado = new FichaEmpleadoLogicaNegocio();
		$this->lNegocioCronogramaVacaciones = new CronogramaVacacionesLogicaNegocio();
		$this->lNegocioConfiguracionCronogramaVacaciones = new ConfiguracionCronogramaVacacionesLogicaNegocio();
		$this->lNegocioPeriodoCronogramaVacaciones = new PeriodoCronogramaVacacionesLogicaNegocio();
		$this->lNegocioFirmantes = new FirmantesLogicaNegocio();

		//set_exception_handler(array($this, 'manejadorExcepciones'));
	}
	/**
	 * Método de inicio del controlador
	 */
	public function index()
	{

		$estadoCronogramaVacacion = "";
		$identificadorFuncionario = $this->identificador;
		$perfil = "('PFL_DIR_PROG_VAC', 'PFL_DIR_VAC_TTHH', 'PFL_DE_PROG_VAC')";

		$arrayParametros = array(
			'identificador' => $identificadorFuncionario, 'codigo_perfil' => $perfil
		);

		$qObtenerPerfilFuncionario = $this->lNegocioUsuariosPerfiles->buscarUsuariosPorIdentificadorPorPerfil($arrayParametros);
		$perfilUsuario = $qObtenerPerfilFuncionario->current()->codificacion_perfil;

		$arrayParametros += [
			'identificador_funcionario_inferior' => '', 'nombre_funcionario' => '', 'fecha_inicio' => '', 'fecha_fin' => ''
		];

		switch ($perfilUsuario) {

			case 'PFL_DIR_PROG_VAC':
				//echo "ES DIRECTOR";
				$solicitudesPlanificacionVacaciones = $this->lNegocioFichaEmpleado->obtenerDatosFuncionarioNivelInferiorFuncionarioPorIdentificadorPadre($arrayParametros);
				$this->perfilUsuarioDirector = $perfilUsuario;
				$this->panelBusqueda = $this->cargarPanelBusquedaSolicitud();
				$this->tablaHtmlRevisionCronogramaVacaciones($solicitudesPlanificacionVacaciones);
				break;
			case 'PFL_DIR_VAC_TTHH':
				//echo "ES DE TALENTO HUMANO";
				$estadoCronogramaVacacion = "('RechazadoDe','EnviadoTthh', 'ReprogramadoTthh')";
				$arrayParametros += ['estado_cronograma_vacacion' => $estadoCronogramaVacacion];
				$solicitudesPlanificacionVacaciones = $this->lNegocioFichaEmpleado->obtenerDatosFuncionarioCronogramaVacacionesPorEstadoCronograma($arrayParametros);
				$this->perfilUsuarioDirector = $perfilUsuario;
				$this->panelBusqueda = $this->cargarPanelBusquedaSolicitud();
				$this->tablaHtmlRevisionCronogramaVacaciones($solicitudesPlanificacionVacaciones);
				break;
			case 'PFL_DE_PROG_VAC':
				//echo "ES DIRECTOR EJECUTIVO";

				$arrayParametrosCronograma = "estado_configuracion_cronograma_vacacion IN ('Activo','RechazadoDe')";
				$verificarConfiguracionCronograma = $this->lNegocioConfiguracionCronogramaVacaciones->buscarLista($arrayParametrosCronograma);

				if ($verificarConfiguracionCronograma->count()) {
					$this->perfilUsuarioDirector = $perfilUsuario . '_1';
					$estadoCronogramaVacacion = "('EnviadoJefe')";
					$arrayParametros += ['estado_cronograma_vacacion' => $estadoCronogramaVacacion];
					$arrayParametros += ['busquedaDe' => "INNER JOIN g_estructura.area ar ON ar.id_area = dc.id_gestion AND ar.id_area_padre = 'DE'"];

					$solicitudesPlanificacionVacaciones = $this->lNegocioFichaEmpleado->obtenerDatosFuncionarioCronogramaVacacionesPorEstadoCronograma($arrayParametros);
					$this->panelBusqueda = $this->cargarPanelBusquedaSolicitud();
					$this->tablaHtmlRevisionCronogramaVacaciones($solicitudesPlanificacionVacaciones);
				} else {
					$this->perfilUsuarioDirector = $perfilUsuario . '_2';
					$estadoCronogramaVacacion = "EnviadoDe";
					$arrayParametros += ['estado_cronograma_vacacion' => $estadoCronogramaVacacion];
					$this->articleHtmlCronogramaVacaciones();
				}

				break;
		}


		require APP . 'VacacionesPermisos/vistas/listaRevisionCronogramaVacacionesVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo()
	{
		$this->accion = "Nuevo RevisionCronogramaVacaciones";
		require APP . 'VacacionesPermisos/vistas/formularioRevisionCronogramaVacacionesVista.php';
	}

	/**
	 * Método para registrar en la base de datos -RevisionCronogramaVacaciones
	 */
	public function guardar()
	{
		$identificadorRevisor = $this->identificador;
		$idAreaRevisor = $this->idArea;
		$idCronogramaVacacion = $_POST['id_cronograma_vacacion'];
		$estadoSolicitud = $_POST['resultado_revision'];
		$observacion = $_POST['observacion'];

		$procesoIngreso = $this->modeloRevisionCronogramaVacaciones->getAdapter()
			->getDriver()
			->getConnection();
		$procesoIngreso->beginTransaction();

		




		if ($_POST['es_reprogramacion'] > 0) {






			if ($estadoSolicitud == 'Rechazado') {
				$estadoSolicitud = 'RechazadoReprogramacion';

				// $arrayRevisionCronogramaVacacion = [
				// 	'id_cronograma_vacacion' => $idCronogramaVacacion, 'identificador_revisor' => $identificadorRevisor, 'id_area_revisor' => $idAreaRevisor, 'estado_solicitud' => $estadoSolicitud, 'observacion' => $observacion
				// ];

				// $this->modeloRevisionCronogramaVacaciones->guardar($arrayRevisionCronogramaVacacion);



				$statement = $this->modeloRevisionCronogramaVacaciones->getAdapter()
					->getDriver()
					->createStatement();

				$arrayParametros = array(
					'estado_registro' => 'Activo'
				);

				$sqlActualizar = $this->modeloRevisionCronogramaVacaciones->actualizarSql('periodo_cronograma_vacaciones', $this->modeloRevisionCronogramaVacaciones->getEsquema());
				$sqlActualizar->set($arrayParametros);
				$sqlActualizar->where(array('id_cronograma_vacacion' => $idCronogramaVacacion, 'estado_registro' => 'Inactivo', 'ultima_reprogramacion' => true));
				$sqlActualizar->prepareStatement($this->modeloRevisionCronogramaVacaciones->getAdapter(), $statement);
				$statement->execute();


				$statement = $this->modeloRevisionCronogramaVacaciones->getAdapter()
					->getDriver()
					->createStatement();

				$arrayParametros2 = array(
					'estado_registro' => 'Inactivo'
				);

				$sqlActualizar = $this->modeloRevisionCronogramaVacaciones->actualizarSql('periodo_cronograma_vacaciones', $this->modeloRevisionCronogramaVacaciones->getEsquema());
				$sqlActualizar->set($arrayParametros2);
				$sqlActualizar->where(array('id_cronograma_vacacion' => $idCronogramaVacacion, 'estado_registro' => 'Activo', 'estado_reprogramacion' => 'Si', 'ultima_reprogramacion' => true));
				$sqlActualizar->prepareStatement($this->modeloRevisionCronogramaVacaciones->getAdapter(), $statement);
				$statement->execute();
			} else if ($estadoSolicitud == 'EnviadoDe') {

						$estadoSolicitud = 'Finalizado';
				// 		$estadoSolicitud = 'ReprogramadoTthh';

				// 		//CEDULA DENNIS 1716825326
						$identificadorInmediatoSuperior = $this->identificador;

						$rutaArchivo = Constantes::RUTA_SERVIDOR_OPT . '/' . Constantes::RUTA_APLICACION . '/' . $_POST['ruta_archivo_reprogramacion'];

						//Firma Electrónica

						$parametrosFirma = array(
							'archivo_entrada' => $rutaArchivo,
							'archivo_salida' => $rutaArchivo,
							'identificador' => $identificadorInmediatoSuperior,
							'razon_documento' => 'Reprogramación de Vacaciones',
							'tabla_origen' => 'g_vacaciones.cronograma_vacaciones',
							'campo_origen' => 'id_cronograma_vacacion',
							'id_origen' => $idCronogramaVacacion,
							'estado' => 'Por atender',
							'proceso_firmado' => 'NO'
						);

						//Guardar registro para firma
						$this->lNegocioFirmantes->ingresoFirmaDocumento($parametrosFirma);
					//}
			}
			// if ($estadoSolicitud == 'Rechazado') {

			// }else if ($estadoSolicitud == 'ReprogramadoTthh'){

			// }
		}

		$statement = $this->modeloRevisionCronogramaVacaciones->getAdapter()->getDriver()->createStatement();

		$arrayRevisionCronogramaVacacion = [
			'id_cronograma_vacacion' => $idCronogramaVacacion, 'identificador_revisor' => $identificadorRevisor, 'id_area_revisor' => $idAreaRevisor, 'estado_solicitud' => $estadoSolicitud, 'observacion' => $observacion
		];

		$sqlInsertar = $this->modeloRevisionCronogramaVacaciones->guardarSql('revision_cronograma_vacaciones', $this->modeloRevisionCronogramaVacaciones->getEsquema());
		$sqlInsertar->columns(array_keys($arrayRevisionCronogramaVacacion));
		$sqlInsertar->values($arrayRevisionCronogramaVacacion, $sqlInsertar::VALUES_MERGE);
		$sqlInsertar->prepareStatement($this->modeloRevisionCronogramaVacaciones->getAdapter(), $statement);
		$statement->execute();

		$statement = $this->modeloRevisionCronogramaVacaciones->getAdapter()
			->getDriver()
			->createStatement();

		$arrayCronogramaVacacion = [
			'id_cronograma_vacacion' => $idCronogramaVacacion, 'estado_cronograma_vacacion' => $estadoSolicitud, 'observacion' => $observacion
		];

		$sqlActualizar = $this->modeloRevisionCronogramaVacaciones->actualizarSql('cronograma_vacaciones', $this->modeloRevisionCronogramaVacaciones->getEsquema());
		$sqlActualizar->set($arrayCronogramaVacacion);
		$sqlActualizar->where(array('id_cronograma_vacacion' => $idCronogramaVacacion));
		$sqlActualizar->prepareStatement($this->modeloRevisionCronogramaVacaciones->getAdapter(), $statement);
		$statement->execute();
		$proceso = true;


		$procesoIngreso->commit();
		if ($proceso) {
			Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
		} else {
			Mensajes::fallo(Constantes::ERROR_GUARDAR);
		}
	}

	public function editar()
	{
		$idCronogramaVacacion = $_POST['id'];
		$esReprogramacion = $this->lNegocioRevisionCronogramaVacaciones->obtenerCronogramaReprogramado(array('id_cronograma_vacacion' => $_POST["id"]));
		$this->datosGenerales = $this->construirDatosGeneralesCronogramaVacacionesAbrir($idCronogramaVacacion);
		$this->esReprogramacion = $esReprogramacion->current()->cantidad;
		$this->periodoCronograma = $this->construirDetallePeriodosCronograma(array('id_cronograma_vacacion' => $_POST["id"]));

		if ($esReprogramacion->current()->cantidad > 0) {
			$this->accion = "Revisión de reprogramación de vacaciones";
			$datos = ['id_cronograma_vacacion' => $idCronogramaVacacion, 'estado_registro' => 'Activo', 'estado_reprogramacion' => 'Si', 'ultima_reprogramacion' => true];
			$rutaArchivo = $this->lNegocioPeriodoCronogramaVacaciones->buscarLista($datos)->current()->ruta_archivo_reprogramacion;
			$this->bloqueAprobacionReprogramacion = $this->construirAprobacionReprogramacion($rutaArchivo);
		} else {
			$this->accion = "Revisión de cronograma de vacaciones";
		}
		$this->resultadoRevision = $this->construirResultadoRevision();
		require APP . 'VacacionesPermisos/vistas/formularioRevisionCronogramaVacacionesVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - RevisionCronogramaVacaciones
	 */
	public function borrar()
	{
		$this->lNegocioRevisionCronogramaVacaciones->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - RevisionCronogramaVacaciones
	 */
	public function tablaHtmlRevisionCronogramaVacaciones($tabla)
	{ {
			$contador = 0;
			foreach ($tabla as $fila) {
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_cronograma_vacacion'] . '"
				class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'VacacionesPermisos/RevisionCronogramavacaciones"
				data-opcion="editar" ondragstart="drag(event)" draggable="true"
				data-destino="detalleItem">
				<td>' . ++$contador . '</td>
				<td style="white - space:nowrap; "><b>' . $fila['identificador'] . '</b></td>
				<td>' . $fila['nombre'] . '</td>
				<td>' . $fila['direccion'] . '</td>
				<td>' . date('Y-m-d', strtotime($fila['fecha_creacion'])) . '</td>
				</tr>'
				);
			}
		}
	}

	/**
	 * Construye el código HTML para desplegar la lista de - RevisionCronogramaVacaciones
	 */
	public function tablaHtmlValidacionPlanificacionPeriodo($tabla)
	{ {
			$contador = 0;
			foreach ($tabla as $fila) {
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_cronograma_vacacion'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'VacacionesPermisos/RevisionCronogramavacaciones"
		  data-opcion="validarPeriodo" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['identificador'] . '</b></td>
		<td>' . $fila['nombre'] . '</td>
		<td>' . $fila['direccion'] . '</td>
		<td>' . date('Y-m-d', strtotime($fila['fecha_creacion'])) . '</td>
		</tr>'
				);
			}
		}
	}

	public function construirResultadoRevision()
	{

		//Recibir el parámetro del perfil para hacer dinamico el estado por perfil "AprobadoDirector", "AprobadoTthh", "AprobadoDe"
		//Rechazado deberia ser un solo estado y devuelto al usuario directamente
		$estadoCronograma = "";
		$identificadorFuncionario = $this->identificador;
		$perfil = "('PFL_DIR_PROG_VAC', 'PFL_DIR_VAC_TTHH', 'PFL_DE_PROG_VAC')";

		$arrayParametros = array(
			'identificador' => $identificadorFuncionario, 'codigo_perfil' => $perfil
		);

		$qObtenerPerfilFuncionario = $this->lNegocioUsuariosPerfiles->buscarUsuariosPorIdentificadorPorPerfil($arrayParametros);
		$perfilUsuario = $qObtenerPerfilFuncionario->current()->codificacion_perfil;

		switch ($perfilUsuario) {
			case 'PFL_DIR_PROG_VAC':
				$estadoCronograma = "EnviadoTthh";
				break;
			case 'PFL_DIR_VAC_TTHH':
				$datos = "estado_cronograma_vacacion IN ('ReprogramadoTthh')";
				$verificarCronograma = $this->lNegocioCronogramaVacaciones->buscarLista($datos);

				if ($verificarCronograma->count()) {
					$estadoCronograma = "Finalizado";
				} else {
					$estadoCronograma = "EnviadoDe";
				}

				break;
			case 'PFL_DE_PROG_VAC':
				$datos = "estado_configuracion_cronograma_vacacion IN ('Activo','RechazadoDe')";
				$verificarConfiguracionCronograma = $this->lNegocioConfiguracionCronogramaVacaciones->buscarLista($datos);

				if ($verificarConfiguracionCronograma->count()) {
					$estadoCronograma = "EnviadoTthh";
				} else {
					$estadoCronograma = "Aprobado";
				}
				break;
		}

		$resultadoRevision = '<fieldset>
								<legend>Resultado de revisión</legend>
								<div data-linea="1">
									<label for="resultado_revision">Resultado: </label>
									<select id="resultado_revision" name="resultado_revision" class="validacion">
										<option value="">Seleccione</option>
										<option value="' . $estadoCronograma . '">Aprobado</option>
										<option value="Rechazado">Rechazado</option>
									</select>
								</div>
								<div data-linea="2">
									<label for="observacion">Observación: </label>
									<input id="observacion" name="observacion" value="" class="validacion" />
								</div>	
							</fieldset>';

		return $resultadoRevision;
	}

	public function filtrarInformacion()
	{
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$revisionCronogramaVacaciones = array();
		$identificadorFuncionario = $this->identificador;
		$perfil = "('PFL_DIR_PROG_VAC', 'PFL_DIR_VAC_TTHH', 'PFL_DE_PROG_VAC')";

		$arrayParametros = array(
			'identificador' => $identificadorFuncionario, 'codigo_perfil' => $perfil
		);

		$qObtenerPerfilFuncionario = $this->lNegocioUsuariosPerfiles->buscarUsuariosPorIdentificadorPorPerfil($arrayParametros);
		$perfilUsuario = $qObtenerPerfilFuncionario->current()->codificacion_perfil;

		$arrayParametros = array(
			'identificador' => $identificadorFuncionario, 'identificador_funcionario_inferior' => $_POST['identificadorFuncionarioInferior'], 'nombre_funcionario' => $_POST['nombreFuncionario'], 'fecha_inicio' => $_POST['fechaInicio'], 'fecha_fin' => $_POST['fechaFin']
		);

		switch ($perfilUsuario) {

			case 'PFL_DIR_PROG_VAC':
				//echo "ES DIRECTOR";
				$revisionCronogramaVacaciones = $this->lNegocioFichaEmpleado->obtenerDatosFuncionarioNivelInferiorFuncionarioPorIdentificadorPadre($arrayParametros);
				break;
			case 'PFL_DIR_VAC_TTHH':
				//echo "ES DE TALENTO HUMANO";
				$estadoCronogramaVacacion = "('EnviadoTthh', 'ReplanificadoTthh')";
				$arrayParametros += ['estado_cronograma_vacacion' => $estadoCronogramaVacacion];
				$revisionCronogramaVacaciones = $this->lNegocioFichaEmpleado->obtenerDatosFuncionarioCronogramaVacacionesPorEstadoCronograma($arrayParametros);
				break;
			case 'PFL_DE_PROG_VAC':
				//echo " DIRECTOR EJECUTIVO";
				$estadoCronogramaVacacion = "('EnviadoDe')";
				$arrayParametros += ['estado_cronograma_vacacion' => $estadoCronogramaVacacion];
				$revisionCronogramaVacaciones = $this->lNegocioFichaEmpleado->obtenerDatosFuncionarioCronogramaVacacionesPorEstadoCronograma($arrayParametros);
				break;
		}

		if ($revisionCronogramaVacaciones->count() == 0) {
			$estado = 'FALLO';
			$mensaje = 'No existen registros para la busqueda..!!';
		}

		$this->tablaHtmlRevisionCronogramaVacaciones($revisionCronogramaVacaciones);

		$contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
		echo json_encode(
			array(
				'estado' => $estado,
				'mensaje' => $mensaje,
				'contenido' => $contenido
			)
		);
	}

	public function filtrarInformacionValidarPlanificacionPeriodo()
	{
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$revisionCronogramaVacaciones = array();
		$identificadorFuncionario = $this->identificador;
		$perfil = "('PFL_DIR_PROG_VAC', 'PFL_DIR_VAC_TTHH', 'PFL_DE_PROG_VAC')";

		$arrayParametros = array(
			'identificador' => $identificadorFuncionario, 'codigo_perfil' => $perfil
		);

		$qObtenerPerfilFuncionario = $this->lNegocioUsuariosPerfiles->buscarUsuariosPorIdentificadorPorPerfil($arrayParametros);
		$perfilUsuario = $qObtenerPerfilFuncionario->current()->codificacion_perfil;

		$arrayParametros = array(
			'identificador' => $identificadorFuncionario, 'identificador_funcionario_inferior' => $_POST['identificadorFuncionarioInferior'], 'nombre_funcionario' => $_POST['nombreFuncionario'], 'fecha_inicio' => $_POST['fechaInicio'], 'fecha_fin' => $_POST['fechaFin']
		);

		switch ($perfilUsuario) {

			case 'PFL_DIR_VAC_TTHH':
				$estadoCronogramaVacacion = "('Finalizado')";
				$arrayParametros += ['estado_cronograma_vacacion' => $estadoCronogramaVacacion];
				$revisionCronogramaVacaciones = $this->lNegocioFichaEmpleado->obtenerDatosFuncionarioCronogramaVacacionesPorEstadoCronograma($arrayParametros);
				break;
		}

		if ($revisionCronogramaVacaciones->count() == 0) {
			$estado = 'FALLO';
			$mensaje = 'No existen registros para la busqueda..!!';
		}

		$this->tablaHtmlValidacionPlanificacionPeriodo($revisionCronogramaVacaciones);

		$contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
		echo json_encode(
			array(
				'estado' => $estado,
				'mensaje' => $mensaje,
				'contenido' => $contenido
			)
		);
	}

	/**
	 * Método para dare de baja periodo de vacaciones - RevisionCronogramaVacaciones
	 */
	public function validarPeriodo()
	{
		$idCronogramaVacacion = $_POST['id'];

		$this->accion = "Validar cronograma periodo de vacaciones";

		$validarEstadoCronogramaVacacion = $this->lNegocioCronogramaVacaciones->buscar($idCronogramaVacacion);
		$estadoCronogramaVacacion = $validarEstadoCronogramaVacacion->getEstadoCronogramaVacacion();

		switch ($estadoCronogramaVacacion) {
			case 'Finalizado':
				$this->datosGenerales = $this->construirDatosGeneralesCronogramaVacacionesAbrir($idCronogramaVacacion);
				$this->periodoCronograma = $this->construirValidarPeriodo(array('id_cronograma_vacacion' => $idCronogramaVacacion));
				break;
			default:
				$datos = [
					'titulo' => 'Cronograma de planificación', 'mensaje' => 'La solicitud no se encuentra en estado "<b>Aprobado</b>".'
				];
				$this->datosGenerales = $this->construirDatosProcesoNoPermitido($datos);
				break;
		}

		require APP . 'VacacionesPermisos/vistas/formularioValidarPeriodoCronogramaVacaciones.php';
	}

	public function articleHtmlCronogramaVacaciones()
	{
		$qEstado = $this->lNegocioConfiguracionCronogramaVacaciones->buscarEstadosConfiguracionCronogramaVacaciones();
		$contador = 1;
		$estadoMostrado = "";
		$pagina = "";
		foreach ($qEstado as $estado) {

			switch ($estado['estado']) {

				case 'EnviadoDe':
				case 'RechazadoDe':
					$this->article .= "<h2>Cronograma Periodo Actual </h2>";
					$estadoMostrado = $this->obtenerEstadoConfiguracionCronogramaVacaciones($estado['estado']);
					$pagina = "aprobarConfiguracionCronogramaVacaciones";
					break;
				default:
					$this->article .= "<h2> Cronograma Periodos Histórico </h2>";
					$estadoMostrado = $this->obtenerEstadoConfiguracionCronogramaVacaciones($estado['estado']);
					$pagina = "aprobarConfiguracionCronogramaVacaciones";

					break;
			}
			$query = "estado_configuracion_cronograma_vacacion = '" . $estado['estado'] . "' ";

			$consulta = $this->lNegocioConfiguracionCronogramaVacaciones->buscarLista($query);
			foreach ($consulta as $fila) {
				$this->article .= '<article id="' . $fila['anio_configuracion_cronograma_vacacion'] . '" class="item"
										data-rutaAplicacion="' . URL_MVC_FOLDER . 'VacacionesPermisos\RevisionCronogramaVacaciones"
										data-opcion="' . $pagina . '" ondragstart="drag(event)"
										draggable="true" data-destino="detalleItem">
										<span><small><b>' . $fila["descripcion_configuracion_vacacion"]  . '</b> </small></span><br/>
										<aside><small><b>Estado: </b>' . $estadoMostrado . '</small></aside>
									</article>';
			}
		}
	}

	/**
	 * Método para abrir la solicitud en estado de revision documental
	 */
	public function aprobarConfiguracionCronogramaVacaciones()
	{


		$arrayParametros = array('anio' => $_POST["id"]);

		$query = "anio_configuracion_cronograma_vacacion = " . $arrayParametros['anio'];

		$qDatoConfiguracion = $this->lNegocioConfiguracionCronogramaVacaciones->buscarLista($query);
		$estado = $qDatoConfiguracion->current()->estado_configuracion_cronograma_vacacion;



		if ($estado == "EnviadoDe") {
			$muestraGuardar = '<button id="btnEnviarDe" type="submit" class="guardar">Enviar</button>';
			$disabled = '';
			$comboNumeroPeriodos = '
			<option value="">Seleccione....</option>
			<option value="Finalizado">Aprobado</option>
			<option value="RechazadoDe">Rechazado</option>';
		} else {
			$muestraGuardar = '';
			$disabled = 'disabled';

			switch ($estado) {
				case 'Finalizado':
					$comboNumeroPeriodos = '<option selected>Aprobado</option>';
					break;
				default:
					$comboNumeroPeriodos = '<option selected>Rechazado</option>';
					break;
			}
		}

		$observacion = $disabled != "" ?  $qDatoConfiguracion->current()->observacion : "";
		$this->descripcionConfiguracionCronogramaVacaciones = '
		<form id="formEnviarDe" data-rutaAplicacion="' . URL_MVC_FOLDER . 'VacacionesPermisos" data-opcion="ConfiguracionCronogramaVacaciones/aprobarDeCronogramaVacaciones" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
			<input type="hidden" name="id_configuracion_cronograma_vacacion" value="' . $qDatoConfiguracion->current()->id_configuracion_cronograma_vacacion . '" />
			<input type="hidden" name="ruta_consolidado_pdf" value="' . $qDatoConfiguracion->current()->ruta_consolidado_pdf . '" />

			<fieldset>
				<legend>Cronograma de planificación año ' . $qDatoConfiguracion->current()->anio_configuracion_cronograma_vacacion . '</legend>

				<div data-linea="1">
					<label for="descripcion_configuracion_vacacion">Descripción: </label>
					' . $qDatoConfiguracion->current()->descripcion_configuracion_vacacion . '
				</div>

				<hr/>
				<div data-linea="2">
				<label>Archivo Excel: </label>
					<a id="verReporteSolicitud" href="' . $qDatoConfiguracion->current()->ruta_consolidado_excel . '" download="cronograma_de_vacaciones.xlsx" target="_blank"> Descargar </a>
				</div>

				<div data-linea="3">
				<label>Archivo Pdf: </label>
					<a id="verReporteSolicitud" href="' . $qDatoConfiguracion->current()->ruta_consolidado_pdf . '" target="_blank"> Descargar </a>
				</div>
				<hr/>
				<div data-linea="4">
					<label>Resultado: </label>
					<select id="estado_configuracion_cronograma_vacacion" name="estado_configuracion_cronograma_vacacion" ' . $disabled . ' class="validacion">
					' . $comboNumeroPeriodos . '
					</select>
				</div>	
				<label for="comentario_configuracion_vacacion" style="vertical-align:top;">Comentario: </label>
				<div data-linea="5">
					
					<textarea rows="2" cols="50" id="observacion" ' . $disabled . ' name="observacion" class="validacion" >' . $observacion . '</textarea>
				</div>
			</fieldset>
			' . $muestraGuardar . '
		</form>';

		require APP . 'VacacionesPermisos/vistas/formularioAprobarConfiguracionCronogramaVacacionesVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function enviarDirectorEjecutivo()
	{

		//$arrayParametros = ['estado_configuracion_cronograma_vacacion' => 'Activo'];
		$arrayParametros = "estado_configuracion_cronograma_vacacion IN ('Activo','RechazadoDe')";
		$verificarConfiguracionCronograma = $this->lNegocioConfiguracionCronogramaVacaciones->buscarLista($arrayParametros);

		if ($verificarConfiguracionCronograma->count()) {

			$idConfiguracionCronogramaVacacion = $verificarConfiguracionCronograma->current()->id_configuracion_cronograma_vacacion;
			$anioConfiguracionCronogramaVacacion = $verificarConfiguracionCronograma->current()->anio_configuracion_cronograma_vacacion;
			$descripcionConfiguracionCronogramaVacacion = $verificarConfiguracionCronograma->current()->descripcion_configuracion_vacacion;

			$arrayParametros = ['anio_cronograma_vacacion' => $anioConfiguracionCronogramaVacacion];

			$verificarRegistrosCronograma = $this->lNegocioCronogramaVacaciones->buscarLista($arrayParametros);

			if ($verificarRegistrosCronograma->count()) {
				$this->accion = "Envíar cronograma de vacaciones al Director Ejecutivo";
				$arrayResumenCronograma = [
					'id_configuracion_cronograma_vacacion' => $idConfiguracionCronogramaVacacion, 'anio_configuracion_cronograma_vacacion' => $anioConfiguracionCronogramaVacacion, 'descripcion_configuracion_vacacion' => $descripcionConfiguracionCronogramaVacacion
				];

				$this->resumenCronogramaVacacion = $this->construirResumenCronogramaVacaciones($arrayResumenCronograma);
			} else {
				$datos = [
					'titulo' => 'Cronograma de planificación', 'mensaje' => 'No existen planificaciones de vacaciones realizadas para el año ' + $anioConfiguracionCronogramaVacacion
				];
				$this->resumenCronogramaVacacion = $this->construirDatosProcesoNoPermitido($datos);
			}
		} else {

			$arrayParametros = ['estado_configuracion_cronograma_vacacion' => 'EnviadoDe'];
			$verificarConfiguracionCronograma = $this->lNegocioConfiguracionCronogramaVacaciones->buscarLista($arrayParametros);
			if ($verificarConfiguracionCronograma->count()) {
				$anioConfiguracionCronogramaVacacion = $verificarConfiguracionCronograma->current()->anio_configuracion_cronograma_vacacion;
				$datos = [
					'titulo' => 'Cronograma de planificación', 'mensaje' => 'Ya existe un cronograma de planificación de vacaciones enviado al Director Ejecutivo del año ' . $anioConfiguracionCronogramaVacacion . ' por aprobar'
				];
				$this->resumenCronogramaVacacion = $this->construirDatosGeneralesCronogramaVacacionesNoConfigurado($datos);
			} else {
				$datos = [
					'titulo' => 'Cronograma de planificación', 'mensaje' => 'No existe un cronograma de planificación de vacaciones para enviar al Director Ejecutivo'
				];
				$this->resumenCronogramaVacacion = $this->construirDatosGeneralesCronogramaVacacionesNoConfigurado($datos);
			}
		}

		require APP . 'VacacionesPermisos/vistas/formularioResumenEnvioCronogramaVacacionesVista.php';
	}

	// /**
	//  * Método para construir lista de validar periodo planificacion
	//  */
	public function validarPlanificacionPeriodo()
	{

		$estadoCronogramaVacacion = "('Aprobado')";
		$arrayParametros = ['estado_cronograma_vacacion' => $estadoCronogramaVacacion];

		$arrayParametros += [
			'identificador_funcionario_inferior' => '', 'nombre_funcionario' => '', 'fecha_inicio' => '', 'fecha_fin' => ''
		];

		$solicitudesPlanificacionVacaciones = $this->lNegocioFichaEmpleado->obtenerDatosFuncionarioCronogramaVacacionesPorEstadoCronograma($arrayParametros);
		$this->panelBusqueda = $this->cargarPanelBusquedaSolicitud();
		$this->tablaHtmlValidacionPlanificacionPeriodo($solicitudesPlanificacionVacaciones);

		require APP . 'VacacionesPermisos/vistas/listaValidacionPlanificacionPeriodoVista.php';
	}


	// /**
	//  * Método para construir resumen de consolidado de cronograma de vacaciones
	//  */
	public function construirDatosProcesoNoPermitido($arrayParametros)
	{
		$titulo = $arrayParametros['titulo'];
		$mensaje = $arrayParametros['mensaje'];

		$resumenConsolidado = '<fieldset>
		<legend>' . $titulo . '</legend>
		<div data-linea="1">
			<label>Resultado: </label> ' . $mensaje . '
		<div>
		</fieldset>';

		return $resumenConsolidado;
	}
	/**
	 * Método para construir resumen de consolidado de cronograma de vacaciones
	 */
	public function construirResumenCronogramaVacaciones($arrayResumenCronograma)
	{
		$resumenConsolidado = "";
		$idConfiguracionCronogramaVacacion = $arrayResumenCronograma['id_configuracion_cronograma_vacacion'];
		$anioConfiguracionCronogramaVacacion = $arrayResumenCronograma['anio_configuracion_cronograma_vacacion'];
		$descripcionConfiguracionCronogramaVacacion = $arrayResumenCronograma['descripcion_configuracion_vacacion'];

		$resumenConsolidadoCronogramaVacaciones = $this->lNegocioCronogramaVacaciones->obtenerResumenConsolidadoCronogramaVacaciones();

		$resumenConsolidado .= '
		<input type="hidden" name="id_configuracion_cronograma_vacacion" id="id_configuracion_cronograma_vacacion" value="' . $idConfiguracionCronogramaVacacion . '" >
		<fieldset>
		<legend>Datos generales</legend>
		<div data-linea="1">
			<label>Año cronograma: </label>' . $anioConfiguracionCronogramaVacacion . '
		</div>
		<div data-linea="2">
			<label>Descripción: </label>' . $descripcionConfiguracionCronogramaVacacion . '
		</div>		
		</fieldset>
		<fieldset>
		<legend>Resumen cronograma vacaciones</legend>
		<table style="width: 100%">
		<thead>
		<tr>
		<th>Descripción</th>
		<th>Cantidad</th>
		</tr>
		</thead>
		<tbody>';

		foreach ($resumenConsolidadoCronogramaVacaciones as $item) {
			$resumenConsolidado .= '<tr>
										<td>' . $item['descripcion'] . '</td>
										<td style="text-align: right;">' . $item['cantidad'] . '</td>
									</tr>';
		}

		$resumenConsolidado .= '</tbody></table></fieldset>
		<div data-linea="10">
			<button type="submit" class="guardar">Enviar Director Ejecutivo</button>
		</div>';

		return $resumenConsolidado;
	}

	/**
	 *Metodo para guardar el proceso de validacion de peroiodo
	 */
	public function guardarValidarPeriodo()
	{
		$proceso = $this->lNegocioPeriodoCronogramaVacaciones->guardarValidarPeriodo($_POST);

		if ($proceso) {
			Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
		}
	}

	/**
	 *Obtenemos los datos del registro seleccionado para editar - Tabla: RevisionCronogramaVacaciones
	 */
	public function guardarEnviarDirectorEjecutivo()
	{

		$_POST['estado_configuracion_cronograma_vacacion'] = 'EnviadoDe';

		//Verifica que no existan revisiones pendentes en estado "EnviadoTtthh"
		$datos = ['estado_cronograma_vacacion' => 'EnviadoTthh'];
		$verificarEstadoCronogramaVacaciones = $this->lNegocioCronogramaVacaciones->buscarLista($datos);

		if ($verificarEstadoCronogramaVacaciones->count()) {
			Mensajes::fallo("Aún existen solicitudes para revisión por parte de Talento Humano.");
		} else {
			$proceso = $this->lNegocioRevisionCronogramaVacaciones->guardarEnviarDirectorEjecutivo($_POST);

			if ($proceso) {
				Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
			} else {
				Mensajes::fallo("No existen planificaciones aprobadas por Talento Humano.");
			}
		}
	}

	/**
	 *Construye el detalle de periodos de cronograma para cerrar un periodo
	 */
	public function construirValidarPeriodo($arrayParametros)
	{

		$idCronogramaVacacion = $arrayParametros['id_cronograma_vacacion'];
		$datosPlanificarPeriodos = "";
		$banderaMostrarBotonGuardar = false;

		$datos = ['id_cronograma_vacacion' => $idCronogramaVacacion, 'estado_reprogramacion' => null];

		$qCronogramaVacacion = $this->lNegocioPeriodoCronogramaVacaciones->buscarLista($datos, 'numero_periodo ASC');

		$datosPlanificarPeriodos = '<fieldset>
										<legend>Detalle de periodos</legend>
										<input type="hidden" name="id_cronograma_vacacion" id="id_cronograma_vacacion" value="' . $idCronogramaVacacion . '">';
		$totalDias = 0;

		$datosPlanificarPeriodos .= '<table id="tPeriodosPlanificar" style="width: 100%;">
										<thead>
											<tr>
												<th>Periodo</th>
												<th>Fecha inicio</th>
												<th>Fecha fin</th>
												<th>Número días</th>
												<th>Cerrar periodo</th>
											</tr>
										</thead>
										<tbody>';

		foreach ($qCronogramaVacacion as $item) {

			$totalDias = $totalDias + $item->total_dias;

			$estadoPeriodo = $item['estado_registro'];
			$estadoReprogramacion = $item['estado_reprogramacion'];
			$propiedadPeriodo = "";

			//Valida si existe almenos un registro en estado_periodo -> activo y estado-reprogramacion -> null y permite guardar el registro
			if ($estadoPeriodo == 'Activo' && !isset($estadoReprogramacion)) {
				$banderaMostrarBotonGuardar = true;
			}

			($estadoPeriodo == "Cerrado") ? $propiedadPeriodo = ' disabled="disabled" checked' : $propiedadPeriodo = ' class="activo"';

			$datosPlanificarPeriodos .= '<tr>	
			<td><input type="hidden" name="hNumeroPeriodo[' . $item->numero_periodo . ']" value="' . $item->numero_periodo . '" ' . $propiedadPeriodo . '>Periodo ' . $item->numero_periodo . '</td>
			<td style="text-align: center;">' . $this->fechaEs(date('d-m-Y', strtotime($item->fecha_inicio))) . '</td>
			<td style="text-align: center;">' . $this->fechaEs(date('d-m-Y', strtotime($item->fecha_fin))) . '</td>
			<td style="text-align: center;">' . $item->total_dias . '</td>
			<td style="text-align: center;"><input type="checkbox" name="hCerrarPeriodo[' . $item->numero_periodo . ']" value="Cerrado" ' . $propiedadPeriodo . '></td><tr>';
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

		if ($banderaMostrarBotonGuardar) {
			$datosPlanificarPeriodos .= '<div data-linea="9">
										<button type="submit" class="guardar">Guardar</button>
										</div>';
		}

		return $datosPlanificarPeriodos;
	}
}
