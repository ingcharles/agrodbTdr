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
 
class RevisionCronogramaVacacionesControlador extends BaseControlador 
{

		 private $lNegocioRevisionCronogramaVacaciones = null;
		 private $modeloRevisionCronogramaVacaciones = null;
		 private $lNegocioCronogramaVacaciones = null;
		 private $lNegocioUsuariosPerfiles = null;
		 private $lNegocioFichaEmpleado = null;
		 private $accion = null;
		 private $datosGenerales = null;
		 private $periodoCronograma = null;
		 private $resultadoRevision = null;
		 private $panelBusqueda = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioRevisionCronogramaVacaciones = new RevisionCronogramaVacacionesLogicaNegocio();
		 $this->modeloRevisionCronogramaVacaciones = new RevisionCronogramaVacacionesModelo();
		 $this->lNegocioCronogramaVacaciones = new CronogramaVacacionesLogicaNegocio();
		 $this->lNegocioUsuariosPerfiles = new UsuariosPerfilesLogicaNegocio();
		 $this->lNegocioFichaEmpleado = new FichaEmpleadoLogicaNegocio();

		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{

			$estadoCronogramaVacacion = "";
			$identificadorFuncionario = $this->identificador;
			$perfil = "('PFL_DIR_PROG_VAC', 'PFL_DIR_VAC_TTHH', 'PFL_DE_PROG_VAC')";

        	$arrayParametros = array(
			'identificador' => $identificadorFuncionario
            , 'codigo_perfil' => $perfil
			);

			$qObtenerPerfilFuncionario = $this->lNegocioUsuariosPerfiles->buscarUsuariosPorIdentificadorPorPerfil($arrayParametros);
			$perfilUsuario = $qObtenerPerfilFuncionario->current()->codificacion_perfil;

			$arrayParametros += ['identificador_funcionario_inferior' => ''
								, 'nombre_funcionario' => ''
								, 'fecha_inicio' => ''
								, 'fecha_fin' => ''];

			switch($perfilUsuario){

				case 'PFL_DIR_PROG_VAC':
					//echo "ES DIRECTOR";
					$solicitudesPlanificacionVacaciones = $this->lNegocioFichaEmpleado->obtenerDatosFuncionarioNivelInferiorFuncionarioPorIdentificadorPadre($arrayParametros);
				break;
				case 'PFL_DIR_VAC_TTHH':
					//echo "ES DE TALENTO HUMANO";
					$estadoCronogramaVacacion = "EnviadoTthh";
					$arrayParametros += ['estado_cronograma_vacacion' => $estadoCronogramaVacacion];
					$solicitudesPlanificacionVacaciones = $this->lNegocioFichaEmpleado->obtenerDatosFuncionarioCronogramaVacacionesPorEstadoCronograma($arrayParametros);
				break;
				case'PFL_DE_PROG_VAC':					
					echo "ES DIRECTOR EJECUTIVO";
					$estadoCronogramaVacacion = "EnviadoDdee";
					$arrayParametros += ['estado_cronograma_vacacion' => $estadoCronogramaVacacion];
					$solicitudesPlanificacionVacaciones = $this->lNegocioFichaEmpleado->obtenerDatosFuncionarioCronogramaVacacionesPorEstadoCronograma($arrayParametros);

				break;

			}

			$this->panelBusqueda = $this->cargarPanelBusquedaSolicitud();
			$this->tablaHtmlRevisionCronogramaVacaciones($solicitudesPlanificacionVacaciones);
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
		public function editar()
		{
			$this->accion = "Revisión de cronograma de vacaciones";
			$this->datosGenerales = $this->construirDatosGeneralesCronogramaVacaciones();
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
		 public function tablaHtmlRevisionCronogramaVacaciones($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_cronograma_vacacion'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'VacacionesPermisos\RevisionCronogramavacaciones"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['identificador'] . '</b></td>
		<td>' . $fila['nombre'] . '</td>
		<td>' . $fila['direccion'] . '</td>
		<td>' . date('Y-m-d',strtotime($fila['fecha_creacion'])) . '</td>
		</tr>');
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
								'identificador' => $identificadorFuncionario
								, 'codigo_perfil' => $perfil
								);

		$qObtenerPerfilFuncionario = $this->lNegocioUsuariosPerfiles->buscarUsuariosPorIdentificadorPorPerfil($arrayParametros);
		$perfilUsuario = $qObtenerPerfilFuncionario->current()->codificacion_perfil;

		switch($perfilUsuario){
			case 'PFL_DIR_PROG_VAC':
				$estadoCronograma = "EnviadoTthh";
			break;
			case 'PFL_DIR_VAC_TTHH':
				$estadoCronograma = "EnviadoDdee";
			break;
			case'PFL_DE_PROG_VAC':
				$estadoCronograma = "Aprobado";
			break;
		}

		$resultadoRevision = '<fieldset>
								<legend>Resultado de revisión</legend>
								<div data-linea="1">
									<label for="resultado_revision">Resultado: </label>
									<select id="resultado_revision" name="resultado_revision" class="validacion">
										<option value="">Seleccione</option>
										<option value="'. $estadoCronograma .'">Aprobado</option>
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

	public function filtrarInformacion(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$revisionCronogramaVacaciones = array();
		$identificadorFuncionario = $this->identificador;
		$perfil = "('PFL_DIR_PROG_VAC', 'PFL_DIR_VAC_TTHH', 'PFL_DE_PROG_VAC')";

        	$arrayParametros = array('identificador' => $identificadorFuncionario
									, 'codigo_perfil' => $perfil
									);

			$qObtenerPerfilFuncionario = $this->lNegocioUsuariosPerfiles->buscarUsuariosPorIdentificadorPorPerfil($arrayParametros);
			$perfilUsuario = $qObtenerPerfilFuncionario->current()->codificacion_perfil;
			
			$arrayParametros = array('identificador' => $identificadorFuncionario
									, 'identificador_funcionario_inferior' => $_POST['identificadorFuncionarioInferior']
									, 'nombre_funcionario' => $_POST['nombreFuncionario']
									, 'fecha_inicio' => $_POST['fechaInicio'] 
									, 'fecha_fin' => $_POST['fechaFin']);

			switch($perfilUsuario){

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
				case'PFL_DE_PROG_VAC':					
					echo "ES DIRECTOR EJECUTIVO";
					$estadoCronogramaVacacion = "EnviadoDdee";
					$arrayParametros += ['estado_cronograma_vacacion' => $estadoCronogramaVacacion];
					$revisionCronogramaVacaciones = $this->lNegocioFichaEmpleado->obtenerDatosFuncionarioCronogramaVacacionesPorEstadoCronograma($arrayParametros);

				break;

			}


		//$revisionCronogramaVacaciones = $this->lNegocioFichaEmpleado->obtenerDatosFuncionarioNivelInferiorFuncionarioPorIdentificadorPadre($arrayParametros);

		if($revisionCronogramaVacaciones->count() == 0){
			$estado = 'FALLO';
			$mensaje = 'No existen registros para la busqueda..!!';
		}

		$this->tablaHtmlRevisionCronogramaVacaciones($revisionCronogramaVacaciones);

		$contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
		echo json_encode(array('estado' => $estado,
								'mensaje' => $mensaje,
								'contenido' => $contenido)
						);
	}

}
