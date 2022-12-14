<?php

/**
 * Controlador ConfiguracionCronogramaVacaciones
 *
 * Este archivo controla la lógica del negocio del modelo:  ConfiguracionCronogramaVacacionesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-11-21
 * @uses    ConfiguracionCronogramaVacacionesControlador
 * @package VacacionesPermisos
 * @subpackage Controladores
 */

namespace Agrodb\VacacionesPermisos\Controladores;

use Agrodb\VacacionesPermisos\Modelos\ConfiguracionCronogramaVacacionesLogicaNegocio;
use Agrodb\VacacionesPermisos\Modelos\ConfiguracionCronogramaVacacionesModelo;
use Agrodb\VacacionesPermisos\Modelos\CronogramaVacacionesLogicaNegocio;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\GUath\Modelos\FichaEmpleadoLogicaNegocio;
use DateTime;

class ConfiguracionCronogramaVacacionesControlador extends BaseControlador
{

	private $lNegocioConfiguracionCronogramaVacaciones = null;
	private $modeloConfiguracionCronogramaVacaciones = null;
	private $configuracionCronogramaVacacion = null;
	private $lNegocioCronogramaVacaciones = null;
	private $lNegocioFichaEmpleado = null;
	private $accion = null;
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->lNegocioConfiguracionCronogramaVacaciones = new ConfiguracionCronogramaVacacionesLogicaNegocio();
		$this->modeloConfiguracionCronogramaVacaciones = new ConfiguracionCronogramaVacacionesModelo();
		$this->lNegocioCronogramaVacaciones = new CronogramaVacacionesLogicaNegocio();
		$this->lNegocioFichaEmpleado = new FichaEmpleadoLogicaNegocio();
		set_exception_handler(array($this, 'manejadorExcepciones'));
	}
	/**
	 * Método de inicio del controlador
	 */
	public function index()
	{
		$modeloConfiguracionCronogramaVacaciones = $this->lNegocioConfiguracionCronogramaVacaciones->buscarConfiguracionCronogramaVacaciones();
		$this->tablaHtmlConfiguracionCronogramaVacaciones($modeloConfiguracionCronogramaVacaciones);
		require APP . 'VacacionesPermisos/vistas/listaConfiguracionCronogramaVacacionesVista.php';
	}
	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo()
	{
		$this->accion = "Nueva configuracion de cronograma de vacaciones";
		$arrayParametros = "estado_configuracion_cronograma_vacacion IN ('Activo','RechazadoDe')";
		$verificarConfiguracionCronograma = $this->lNegocioConfiguracionCronogramaVacaciones->buscarLista($arrayParametros);

		if ($verificarConfiguracionCronograma->count()) {
			$datos = ['titulo' => 'Cronograma de planificación', 'mensaje' => 'Ya existe una planificación habilitada.'];
			$this->configuracionCronogramaVacacion = $this->construirDatosGeneralesCronogramaVacacionesNoConfigurado($datos);
		} else {
			$anioConfiguracionCronogramaVacacion = date('Y') + 1;

			$this->configuracionCronogramaVacacion = $this->construirIngresoConfiguracionCronogramaVacaciones($anioConfiguracionCronogramaVacacion);
		}

		require APP . 'VacacionesPermisos/vistas/formularioConfiguracionCronogramaVacacionesVista.php';
	}



	/**
	 * Método para registrar en la base de datos -ConfiguracionCronogramaVacaciones
	 */
	public function guardar()
	{

		$identificadorConfiguracionCronogramaVacacion = $this->identificador;
		$anioConfiguracionCronogramaVacacion = $_POST['anio_configuracion_cronograma_vacacion'];
		$_POST['identificador_configuracion_cronograma_vacacion'] = $identificadorConfiguracionCronogramaVacacion;
		$where = "anio_configuracion_cronograma_vacacion = $anioConfiguracionCronogramaVacacion AND estado_configuracion_cronograma_vacacion != 'Inactivo' ";
		$datosConfiguracionCronogramaVacacion = $this->lNegocioConfiguracionCronogramaVacaciones->buscarLista($where);

		if ($datosConfiguracionCronogramaVacacion->count() <= 0) {
			$datoGuardado = $this->lNegocioConfiguracionCronogramaVacaciones->guardar($_POST);
			if ($datoGuardado) {
				Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
			} else {
				Mensajes::fallo(Constantes::ERROR_AL_GUARDAR);
			}
		} else {
			Mensajes::fallo("Ya existe un cronograma de vacaciones registrado en el año " . $anioConfiguracionCronogramaVacacion);
		}
	}
	/**
	 *Obtenemos los datos del registro seleccionado para editar - Tabla: ConfiguracionCronogramaVacaciones
	 */
	public function editar()
	{
		$this->accion = "Configuracion de cronograma de vacaciones";
		$this->configuracionCronogramaVacacion = $this->construirDatosConfiguracionCronogramaVacaciones($_POST["id"]);
		require APP . 'VacacionesPermisos/vistas/formularioConfiguracionCronogramaVacacionesVista.php';
	}
	/**
	 * Método para borrar un registro en la base de datos - ConfiguracionCronogramaVacaciones
	 */
	public function borrar()
	{
		$this->lNegocioConfiguracionCronogramaVacaciones->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - ConfiguracionCronogramaVacaciones
	 */
	public function tablaHtmlConfiguracionCronogramaVacaciones($tabla)
	{ {
			$contador = 0;
			foreach ($tabla as $fila) {
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_configuracion_cronograma_vacacion'] . '"
				class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'VacacionesPermisos\configuracioncronogramavacaciones"
				data-opcion="editar" ondragstart="drag(event)" draggable="true"
				data-destino="detalleItem">
				<td>' . ++$contador . '</td>
				<td>' . $fila['identificador_configuracion_cronograma_vacacion'] . '</td>
				<td>' . $fila['descripcion_configuracion_vacacion']	. '</td>
				<td>' . $fila['anio_configuracion_cronograma_vacacion'] . '</td>
				</tr>'
				);
			}
		}
	}

	/**
	 *Obtenemos los datos del registro seleccionado para editar - Tabla: ConfiguracionCronogramaVacaciones
	 */
	public function construirIngresoConfiguracionCronogramaVacaciones($anioConfiguracionCronogramaVacacion)
	{

		$anioCronogramaVacacion = $anioConfiguracionCronogramaVacacion;

		$configuracionCronograma = '<form id="formulario" data-rutaAplicacion="' . URL_MVC_FOLDER . 'VacacionesPermisos" data-opcion="configuracioncronogramavacaciones/guardar" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
		<fieldset>
			<legend>Datos de cronograma</legend>

			<div data-linea="1">
				<label for="anio_configuracion_cronograma_vacacion">Año cronograma: </label>
				<input type="text" id="anio_configuracion_cronograma_vacacion" name="anio_configuracion_cronograma_vacacion" value="' . $anioCronogramaVacacion . '" readonly />
			</div>

			<div data-linea="2">
				<label for="descripcion_configuracion_vacacion">Descripción: </label>
				<input type="text" id="descripcion_configuracion_vacacion" name="descripcion_configuracion_vacacion" value="" placeholder="Coloque una descripción" maxlength="512" class="validacion"/>
			</div>

		</fieldset >

		<div data-linea="8">
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</form >';

		return $configuracionCronograma;
	}


	/**
	 *Obtenemos los datos del registro seleccionado para editar - Tabla: ConfiguracionCronogramaVacaciones
	 */
	public function construirDatosConfiguracionCronogramaVacaciones($idConfiguracionCronograma)
	{

		$datosConfiguracionCronograma = $this->lNegocioConfiguracionCronogramaVacaciones->buscar($idConfiguracionCronograma);
		$datos = ['identificador' =>  $datosConfiguracionCronograma->getIdentificadorConfiguracionCronogramaVacacion()];
		$qDatosFuncionario = $this->lNegocioFichaEmpleado->buscarLista($datos);
		$nombreFuncionario = $qDatosFuncionario->current()->nombre . ' ' . $qDatosFuncionario->current()->apellido;

		$configuracionCronograma = '<fieldset>
											<legend>Datos de cronograma</legend>
											<div data-linea="1">
												<label for="anio_configuracion_cronograma_vacacion">Año cronograma: </label>
												' . $datosConfiguracionCronograma->getAnioConfiguracionCronogramaVacacion() . '
											</div>
											<div data-linea="2">
												<label for="descripcion_vacacion">Descripción: </label>
												' . $datosConfiguracionCronograma->getDescripcionConfiguracionVacacion() . '
											</div>
											<div data-linea="3">
												<label for="fecha_creacion">Fecha de creación: </label>
												' . (new DateTime((string)($datosConfiguracionCronograma->getFechaCreacion())))->format('Y-m-d')  . '
											</div>
											<div data-linea="4">
												<label for="identificador_configuracion_cronograma">Identificador creación: </label>
												' . $datosConfiguracionCronograma->getIdentificadorConfiguracionCronogramaVacacion() . '
											</div>
											<div data-linea="5">
												<label for="nombre_configuracion_cronograma">Nombre creación: </label>
												' . $nombreFuncionario . '
											</div>
										</fieldset>';

		return $configuracionCronograma;
	}

	/**
	 *Obtenemos los datos del registro seleccionado para editar - Tabla: RevisionCronogramaVacaciones
	 */
	public function aprobarDeCronogramaVacaciones()
	{

		$_POST['identificador_director_ejecutivo'] = $this->identificador;

		$proceso = $this->lNegocioConfiguracionCronogramaVacaciones->aprobarDeCronogramaVacaciones($_POST);
		if ($proceso) {
			Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
		} else {
			Mensajes::fallo("A ocurrido un error, por favor comunicar con Dtics.");
		}
	}
}
