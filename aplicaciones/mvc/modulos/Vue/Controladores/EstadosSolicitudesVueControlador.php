<?php
/**
 * Controlador EstadosSolicitudesVue
 *
 * Este archivo controla la lógica del negocio del modelo: EstadosSolicitudesVueModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2021-09-16
 * @uses EstadosSolicitudesVueControlador
 * @package Vue
 * @subpackage Controladores
 */
namespace Agrodb\Vue\Controladores;

use Agrodb\Core\Constantes;
use Agrodb\Vue\Modelos\EstadosSolicitudesVueLogicaNegocio;
use Agrodb\Vue\Modelos\EstadosSolicitudesVueModelo;

class EstadosSolicitudesVueControlador extends BaseControlador{

	private $lNegocioEstadosSolicitudesVue = null;

	private $modeloEstadosSolicitudesVue = null;

	private $accion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioEstadosSolicitudesVue = new EstadosSolicitudesVueLogicaNegocio();
		$this->modeloEstadosSolicitudesVue = new EstadosSolicitudesVueModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloEstadosSolicitudesVue = $this->lNegocioEstadosSolicitudesVue->buscarEstadosSolicitudesVue();
		$this->tablaHtmlEstadosSolicitudesVue($modeloEstadosSolicitudesVue);
		require APP . 'Vue/vistas/listaEstadosSolicitudesVueVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo EstadosSolicitudesVue";
		require APP . 'Vue/vistas/formularioEstadosSolicitudesVueVista.php';
	}

	/**
	 * Método para registrar en la base de datos -EstadosSolicitudesVue
	 */
	public function guardar(){
		$this->lNegocioEstadosSolicitudesVue->guardar($_POST);
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: EstadosSolicitudesVue
	 */
	public function editar(){
		$this->accion = "Editar EstadosSolicitudesVue";
		$this->modeloEstadosSolicitudesVue = $this->lNegocioEstadosSolicitudesVue->buscar($_POST["id"]);
		require APP . 'Vue/vistas/formularioEstadosSolicitudesVueVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - EstadosSolicitudesVue
	 */
	public function borrar(){
		$this->lNegocioEstadosSolicitudesVue->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - EstadosSolicitudesVue
	 */
	public function tablaHtmlEstadosSolicitudesVue($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_estado_solicitud_vue'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Vue\estadossolicitudesvue"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_estado_solicitud_vue'] . '</b></td>
<td>' . $fila['tipo_solicitud'] . '</td>
<td>' . $fila['id_vue'] . '</td>
<td>' . $fila['fecha_registro'] . '</td>
</tr>');
			}
		}
	}
	
	/**
	 * Proceso automático para generar certificados XML y envio a HUB
	 */
	public function paCambioEstadoVueTiempoRespuesta(){
		
		$fecha = date("Y-m-d h:m:s");
		echo Constantes::IN_MSG .'<b>PROCESO AUTOMÁTICO DE VERIFICACIÓN DE ESTADO DE SOLICITUDES VUE Y CAMBIO DE ESTADO DECRETO 68 '.$fecha.'</b>\n';
		
		$this->lNegocioEstadosSolicitudesVue->procesoGenerarCambioEstadoVueTiempoRepuesta();
		
		$fecha = date("Y-m-d h:m:s");
		echo Constantes::IN_MSG .'<b>PROCESO AUTOMÁTICO DE VERIFICACIÓN DE ESTADO DE SOLICITUDES VUE Y CAMBIO DE ESTADO DECRETO 68 '.$fecha.'</b>';
	}
}
