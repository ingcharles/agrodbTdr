<?php
/**
 * Controlador JornadaLaboral
 *
 * Este archivo controla la lógica del negocio del modelo: JornadaLaboralModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2020-06-09
 * @uses JornadaLaboralControlador
 * @package JornadaLaboral
 * @subpackage Controladores
 */
namespace Agrodb\JornadaLaboral\Controladores;

use Agrodb\JornadaLaboral\Modelos\JornadaLaboralLogicaNegocio;
use Agrodb\JornadaLaboral\Modelos\JornadaLaboralModelo;
use Agrodb\GUath\Modelos\FichaEmpleadoLogicaNegocio;

class JornadaLaboralControlador extends BaseControlador{

	private $lNegocioJornadaLaboral = null;

	private $modeloJornadaLaboral = null;
	
	private $lNegocioFichaEmpleado = null;

	private $accion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioJornadaLaboral = new JornadaLaboralLogicaNegocio();
		$this->modeloJornadaLaboral = new JornadaLaboralModelo();
		
		$this->lNegocioFichaEmpleado = new FichaEmpleadoLogicaNegocio();
		
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$this->cargarPanelBusqueda();
		require APP . 'JornadaLaboral/vistas/listaJornadaLaboralVista.php';
	}
	
	/**
	 * Método de inicio del controlador
	 */
	public function listaVistaUsuario(){
		$this->cargarPanelBusqueda();
		require APP . 'JornadaLaboral/vistas/listaJornadaLaboralVistaUsuario.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo jornada laboral";
		require APP . 'JornadaLaboral/vistas/formularioJornadaLaboralVista.php';
	}

	/**
	 * Método para registrar en la base de datos -JornadaLaboral
	 */
	public function guardar(){
		$this->lNegocioJornadaLaboral->guardar($_POST);
	}

	/**
	 * Obtenemos los datos del registro seleccionado para deplegar datos - Tabla: JornadaLaboral
	 */
	public function desplegarRegistro(){
		$this->accion = "Datos del funcionario";
		$this->modeloJornadaLaboral = $this->lNegocioJornadaLaboral->buscar($_POST["id"]);
		$datosFuncionario = $this->lNegocioFichaEmpleado->buscarDatosUsuarioContrato($this->modeloJornadaLaboral->getIdentificador());
		$this->construirInformacionFuncionarioContrato($datosFuncionario);
		require APP . 'JornadaLaboral/vistas/formularioJornadaLaboralVistaUsuario.php';
	}
	
	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: JornadaLaboral
	 */
	public function editar(){
		$this->accion = "Editar jornada laboral";
		$this->modeloJornadaLaboral = $this->lNegocioJornadaLaboral->buscar($_POST["id"]);
		require APP . 'JornadaLaboral/vistas/formularioJornadaLaboralVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - JornadaLaboral
	 */
	public function borrar(){
		$this->lNegocioJornadaLaboral->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - JornadaLaboral
	 */
	public function tablaHtmlJornadaLaboral($tabla, $administrador){
		
		if($administrador == 'SI'){
			$pagina = 'editar';
		}else{
			$pagina = 'desplegarRegistro';
		}
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_jornada_laboral'] . '"
							class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'JornadaLaboral\jornadaLaboral"
							data-opcion="'.$pagina.'" ondragstart="drag(event)" draggable="true"
							data-destino="detalleItem">
						<td>' . ++ $contador . '</td>
						<td>' . $fila['identificador'] . '</td>
						<td>' . $fila['nombre'] . '</td>
						<td>' . $fila['mes'] . '</td>
					</tr>');
			}
	}

	/**
	 * Método para listar la jornada laboral registrada
	 */
	public function listarJornadaLaboralFuncionario(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';

		$identificador = $_POST["identificador"];
		$estadoRegistro = $_POST["estado"];
		$apellido = $_POST["apellido"];
		$nombre = $_POST["nombre"];
		$area = $_POST["area"];
		$administrador = $_POST["administrador"];

		$arrayParametros = array(
			'identificador' => $identificador,
			'estado_registro' => $estadoRegistro,
			'apellido' => $apellido,
			'nombre' => $nombre,
			'area' => $area);
		$registros = $this->lNegocioJornadaLaboral->buscarHorarioFuncionarioPorFiltro($arrayParametros);

		$this->tablaHtmlJornadaLaboral($registros, $administrador);
		$contenido = \Zend\Json\Json::encode($this->itemsFiltrados);

		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido));
	}
	
	/**
	 * Método para constuir informacion adicional del funcionario
	 */
	public function construirInformacionFuncionarioContrato($datos){
		
		$datos = $datos->current();
		
		$this->panelDatosFuncionarioContrato = '<fieldset>
													<legend>Datos funcionario</legend>
													<div data-linea="1">
														<label>Cédula: </label>' . $datos->identificador.'
													</div>
													<div data-linea="2">
														<label>Nombres: </label>' . $datos->nombre.'
													</div>
													<div data-linea="3">
														<label>Provincia: </label>' . $datos->provincia.'
													</div>
													<div data-linea="4">
														<label>Cargo: </label>' . $datos->nombre_puesto.'
													</div>
												</fieldset>';
		
		
	}
	
	/**
	 * Método para desplegar la pantalla de carga masiva de tramites.
	 */
	
	public function cargaMasiva(){
		$this->accion = "Carga masiva / Ingreso jornada laboral";
		require APP . 'JornadaLaboral/vistas/formularioCargaMasivaJornadaLaboral.php';
	}
	
	/**
	 * Método para obtener ruta de archivo excel
	 * */
	public function cargarJornadaLaboralMasivo(){
		$this->lNegocioJornadaLaboral->leerArchivoExcelJornadaLaboral($_POST);
	}
}
