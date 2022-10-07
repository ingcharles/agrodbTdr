<?php
/**
 * Controlador AsistenciaEmpleado
 *
 * Este archivo controla la lógica del negocio del modelo: AsistenciaEmpleadoModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2020-06-09
 * @uses AsistenciaEmpleadoControlador
 * @package JornadaLaboral
 * @subpackage Controladores
 */
namespace Agrodb\JornadaLaboral\Controladores;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\JornadaLaboral\Modelos\AsistenciaEmpleadoLogicaNegocio;
use Agrodb\JornadaLaboral\Modelos\AsistenciaEmpleadoModelo;

class AsistenciaEmpleadoControlador extends BaseControlador{

	private $lNegocioAsistenciaEmpleado = null;

	private $modeloAsistenciaEmpleado = null;

	private $accion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioAsistenciaEmpleado = new AsistenciaEmpleadoLogicaNegocio();
		$this->modeloAsistenciaEmpleado = new AsistenciaEmpleadoModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$identificador = $this->identificador;
		$fechaActual = date('Y-m-d');
		$modeloAsistenciaEmpleado = $this->lNegocioAsistenciaEmpleado->buscarAsistenciaEmpleadoPivoteado($identificador, $fechaActual);
		$this->tablaHtmlAsistenciaEmpleado($modeloAsistenciaEmpleado);
		require APP . 'JornadaLaboral/vistas/listaAsistenciaEmpleadoVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo asistencia empleado";
		require APP . 'JornadaLaboral/vistas/formularioAsistenciaEmpleadoVista.php';
	}

	/**
	 * Método para registrar en la base de datos -AsistenciaEmpleado
	 */
	public function guardar(){
		$_POST['identificador'] = $this->identificador;
		$_POST['fecha_registro'] = 'now()';
		$_POST['ip_registro'] = $this->obtenerIpUsuario();
		
		
		//Busca los datos de marcación seleccionada
		$query = "  identificador='".$this->identificador."' and 
                    fecha_registro >= '". date('Y-m-d') ." 00:00:00' and
                    fecha_registro < '". date('Y-m-d') ." 24:00:00' and
                    tipo_registro = '".$_POST['tipo_registro']."'";
		
		$listaTimbrada = $this->lNegocioAsistenciaEmpleado->buscarLista($query);
		
		if(isset($listaTimbrada->current()->id_asistencia_empleado)){
		    Mensajes::fallo(Constantes::ERROR_DUPLICADO);
		}else{
		    $this->lNegocioAsistenciaEmpleado->guardar($_POST);
		    Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
		}
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: AsistenciaEmpleado
	 */
	public function editar(){
		$this->accion = "Editar AsistenciaEmpleado";
		$this->modeloAsistenciaEmpleado = $this->lNegocioAsistenciaEmpleado->buscar($_POST["id"]);
		require APP . 'JornadaLaboral/vistas/formularioAsistenciaEmpleadoVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - AsistenciaEmpleado
	 */
	public function borrar(){
		$this->lNegocioAsistenciaEmpleado->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - AsistenciaEmpleado
	 */
	public function tablaHtmlAsistenciaEmpleado($tabla){
		
		$contador = 0;
		foreach ($tabla as $fila){
			
			$identificador = explode('¬', $fila['identificador']);
			
			$this->itemsFiltrados[] = array(
				'<tr>
					<td>' . ++ $contador . '</td>
		  			<td style="white - space:nowrap; "><b>' . $identificador['0'] . '</b></td>
					<td>' . $fila['inicio_jornada'] . '</td>
					<td>' . $fila['inicio_receso'] . '</td>
					<td>' . $fila['fin_receso'] . '</td>
					<td>' . $fila['fin_jornada'] . '</td>
				</tr>');
		}
	}
}
