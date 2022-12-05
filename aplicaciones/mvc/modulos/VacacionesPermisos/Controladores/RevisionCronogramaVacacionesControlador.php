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

use Agrodb\GUath\Modelos\FichaEmpleadoLogicaNegocio;
use Agrodb\Usuarios\Modelos\UsuariosPerfilesLogicaNegocio;
use Agrodb\VacacionesPermisos\Modelos\CronogramaVacacionesLogicaNegocio;
use Agrodb\VacacionesPermisos\Modelos\RevisionCronogramaVacacionesLogicaNegocio;
use Agrodb\VacacionesPermisos\Modelos\RevisionCronogramaVacacionesModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\VacacionesPermisos\Modelos\ConfiguracionCronogramaVacacionesLogicaNegocio;
use Agrodb\VacacionesPermisos\Modelos\ConfiguracionCronogramaVacacionesModelo;

class RevisionCronogramaVacacionesControlador extends BaseControlador
{

	private $lNegocioRevisionCronogramaVacaciones = null;
	private $modeloRevisionCronogramaVacaciones = null;
	private $lNegocioCronogramaVacaciones = null;
	private $lNegocioUsuariosPerfiles = null;
	private $lNegocioFichaEmpleado = null;
	private $lNegocioConfiguracionCronogramaVacaciones = null;
	private $descripcionConfiguracionCronogramaVacaciones = null;
	private $accion = null;
	private $article = null;
	private $datosGenerales = null;
	private $periodoCronograma = null;
	private $resultadoRevision = null;
	private $panelBusqueda = null;
	private $perfilUsuarioDirector = null;
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
				$estadoCronogramaVacacion = "EnviadoTthh";
				$arrayParametros += ['estado_cronograma_vacacion' => $estadoCronogramaVacacion];
				$solicitudesPlanificacionVacaciones = $this->lNegocioFichaEmpleado->obtenerDatosFuncionarioCronogramaVacacionesPorEstadoCronograma($arrayParametros);
				$this->perfilUsuarioDirector = $perfilUsuario;
				$this->panelBusqueda = $this->cargarPanelBusquedaSolicitud();
				$this->tablaHtmlRevisionCronogramaVacaciones($solicitudesPlanificacionVacaciones);
				break;
			case 'PFL_DE_PROG_VAC':
				//echo "ES DIRECTOR EJECUTIVO";
				$this->perfilUsuarioDirector = $perfilUsuario;
				$estadoCronogramaVacacion = "EnviadoDe";
				$arrayParametros += ['estado_cronograma_vacacion' => $estadoCronogramaVacacion];
				$this->articleHtmlCronogramaVacaciones();
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
		$estadoSolicitud = $_POST['resultado_revision'];
		$estadoCronogramaVacacion = $_POST['resultado_revision'];

		$_POST['identificador_revisor'] = $identificadorRevisor;
		$_POST['id_area_revisor'] = $idAreaRevisor;
		$_POST['estado_solicitud'] = $estadoSolicitud;
		$_POST['estado_cronograma_vacacion'] = $estadoCronogramaVacacion;

		$proceso = $this->lNegocioRevisionCronogramaVacaciones->guardar($_POST);

		if ($proceso) {
			Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
		}
	}

	/**
	 *Obtenemos los datos del registro seleccionado para editar - Tabla: RevisionCronogramaVacaciones
	 */
	/*public function editar()
		{
			$idCronogramaVacacion = $_POST['id'];
			$this->accion = "Revisión de cronograma de vacaciones";
			$this->datosGenerales = $this->construirDatosGeneralesCronogramaVacacionesAbrir($idCronogramaVacacion);
			$this->periodoCronograma = $this->construirDetallePeriodosCronograma(array('id_cronograma_vacacion' => $_POST["id"]));	
			$this->resultadoRevision = $this->construirResultadoRevision();

		$proceso = $this->lNegocioRevisionCronogramaVacaciones->guardar($_POST);

		if ($proceso) {
			Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
		}
	}*/

	/**
	 *Obtenemos los datos del registro seleccionado para editar - Tabla: RevisionCronogramaVacaciones
	 */
	public function editar()
	{
		$idCronogramaVacacion = $_POST['id'];
		$this->accion = "Revisión de cronograma de vacaciones";
		$this->datosGenerales = $this->construirDatosGeneralesCronogramaVacacionesAbrir($idCronogramaVacacion);
		$this->periodoCronograma = $this->construirDetallePeriodosCronograma(array('id_cronograma_vacacion' => $_POST["id"]));
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
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'VacacionesPermisos\RevisionCronogramavacaciones"
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
				$estadoCronograma = "EnviadoDe";
				break;
			case 'PFL_DE_PROG_VAC':
				$estadoCronograma = "Aprobado";
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
									<label for="observacion_revision">Observación: </label>
									<input id="observacion_revision" name="observacion_revision" value="" class="validacion">
								</div>	
							</fieldset >';

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
				$estadoCronogramaVacacion = "EnviadoTthh";
				$arrayParametros += ['estado_cronograma_vacacion' => $estadoCronogramaVacacion];
				$revisionCronogramaVacaciones = $this->lNegocioFichaEmpleado->obtenerDatosFuncionarioCronogramaVacacionesPorEstadoCronograma($arrayParametros);
				break;
			case 'PFL_DE_PROG_VAC':
				echo "ES DIRECTOR EJECUTIVO";
				$estadoCronogramaVacacion = "EnviadoDe";
				$arrayParametros += ['estado_cronograma_vacacion' => $estadoCronogramaVacacion];
				$revisionCronogramaVacaciones = $this->lNegocioFichaEmpleado->obtenerDatosFuncionarioCronogramaVacacionesPorEstadoCronograma($arrayParametros);

				break;
		}


		//$revisionCronogramaVacaciones = $this->lNegocioFichaEmpleado->obtenerDatosFuncionarioNivelInferiorFuncionarioPorIdentificadorPadre($arrayParametros);

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

	/**
	 * Método para dare de baja periodo de vacaciones - RevisionCronogramaVacaciones
	 */
	public function darBajaPeriodo()
	{

		$idCronogramaVacacion = $_POST['elementos'];

		$this->accion = "Dar de baja periodo de cranograma";
		$this->datosGenerales = $this->construirDatosGeneralesCronogramaVacacionesAbrir($idCronogramaVacacion);
		require APP . 'VacacionesPermisos/vistas/formularioDarBajaPeriodoCronogramaVacaciones.php';		
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
					$this->article .= "<h2>Cronograma Periodo Actual </h2>";
					$estadoMostrado = "Habilitado";
					$pagina = "aprobarConfiguracionCronogramaVacaciones";
					break;

					// case 'Inactivo':
				default:
					$this->article .= "<h2> Cronograma Periodos Histórico </h2>";
					$estadoMostrado = "Finalizado";
					$pagina = "aprobarConfiguracionCronogramaVacaciones";
					// break;

					// $pagina = "aprobarConfiguracionCronogramaVacaciones";

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
			<option value="Rechazado">Rechazado</option>';
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
					
					<textarea rows="2" cols="50" id="observacion" ' . $disabled . ' name="observacion" class="validacion" >' . $qDatoConfiguracion->current()->observacion . '</textarea>
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

		$arrayParametros = ['estado_configuracion_cronograma_vacacion' => 'Activo'];
		$verificarConfiguracionCronograma = $this->lNegocioConfiguracionCronogramaVacaciones->buscarLista($arrayParametros);

		if ($verificarConfiguracionCronograma->count()) {

			$idConfiguracionCronogramaVacacion = $verificarConfiguracionCronograma->current()->id_configuracion_cronograma_vacacion;
			$anioConfiguracionCronogramaVacacion = $verificarConfiguracionCronograma->current()->anio_configuracion_cronograma_vacacion;
			$descripcionConfiguracionCronogramaVacacion = $verificarConfiguracionCronograma->current()->descripcion_configuracion_vacacion;

			$arrayParametros = ['anio_cronograma_vacacion' => $anioConfiguracionCronogramaVacacion];

			$verificarRegistrosCronograma = $this->lNegocioCronogramaVacaciones->buscarLista($arrayParametros);

			if ($verificarRegistrosCronograma->count()) {
				$this->accion = "Envío cronograma vacaciones DE";
				$arrayResumenCronograma = [
					'id_configuracion_cronograma_vacacion' => $idConfiguracionCronogramaVacacion, 'anio_configuracion_cronograma_vacacion' => $anioConfiguracionCronogramaVacacion, 'descripcion_configuracion_vacacion' => $descripcionConfiguracionCronogramaVacacion
				];

				$this->resumenCronogramaVacacion = $this->construirResumenCronogramaVacaciones($arrayResumenCronograma);
			} else {
				$this->resumenCronogramaVacacion = $this->construirConfiguracionCronogramaVacacionesNoCreado();
			}
		} else {
			$this->resumenCronogramaVacacion = $this->construirDatosGeneralesCronogramaVacacionesNoConfigurado();
		}

		require APP . 'VacacionesPermisos/vistas/formularioResumenEnvioCronogramaVacacionesVista.php';
	}

	// /**
	//  * Método para construir resumen de consolidado de cronograma de vacaciones
	//  */
	public function construirConfiguracionCronogramaVacacionesNoCreado()
	{
		//$resumenConsolidado = "";
		//$resumenConsolidadoCronogramaVacaciones = $this->lNegocioCronogramaVacaciones->obtenerResumenConsolidadoCronogramaVacaciones();

		$resumenConsolidado = '<fieldset>
		<legend>Resumen cronograma vacaciones</legend>
		<div data-linea="1">
			<label>Resultado: </label> No se han generado registros para el año.
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
			<button type="submit" class="guardar">Enviar a Director Ejecutivo</button>
		</div>';

		return $resumenConsolidado;
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
				Mensajes::fallo("A ocurrido un error, por favor comunicar con Dtics.");
			}
		}
	}
}
