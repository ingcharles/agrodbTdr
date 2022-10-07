<?php
/**
 * Controlador ConfiguracionFitosanitario
 *
 * Este archivo controla la lógica del negocio del modelo: ConfiguracionFitosanitarioModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2020-07-04
 * @uses ConfiguracionFitosanitarioControlador
 * @package WsFitosanitario
 * @subpackage Controladores
 */
namespace Agrodb\ConfiguracionCertificadoFitosanitarioHub\Controladores;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\ConfiguracionCertificadoFitosanitarioHub\Modelos\ConfiguracionFitosanitarioLogicaNegocio;
use Agrodb\ConfiguracionCertificadoFitosanitarioHub\Modelos\ConfiguracionFitosanitarioModelo;

class ConfiguracionFitosanitarioControlador extends BaseControlador{

	private $lNegocioConfiguracionFitosanitario = null;

	private $modeloConfiguracionFitosanitario = null;

	private $accion = null;

	private $rutaFecha = null;
	
	private $arrayTipoEncriptacion = '';

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioConfiguracionFitosanitario = new ConfiguracionFitosanitarioLogicaNegocio();
		$this->modeloConfiguracionFitosanitario = new ConfiguracionFitosanitarioModelo();
		$this->rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloConfiguracionFitosanitario = $this->lNegocioConfiguracionFitosanitario->buscarTodo();
		$this->tablaHtmlConfiguracionFitosanitario($modeloConfiguracionFitosanitario);
		require APP . 'ConfiguracionCertificadoFitosanitarioHub/vistas/listaConfiguracionFitosanitarioVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo ConfiguracionFitosanitario";
		require APP . 'ConfiguracionCertificadoFitosanitarioHub/vistas/formularioConfiguracionFitosanitarioVista.php';
	}

	/**
	 * Método para registrar en la base de datos -ConfiguracionFitosanitario
	 */
	public function guardar(){

		$_POST['encriptacion_fitosanitario'] = implode(",", $_POST['encriptacion_fitosanitario']);
		$validacionProceso = $this->lNegocioConfiguracionFitosanitario->guardar($_POST);
		
		if($validacionProceso){
			Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
		}else{
			Mensajes::fallo(Constantes::ERROR_WSFITOSANITARIO);
		}

	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: ConfiguracionFitosanitario
	 */
	public function editar(){
		$this->accion = "Editar ConfiguracionFitosanitario";
		$this->modeloConfiguracionFitosanitario = $this->lNegocioConfiguracionFitosanitario->buscar($_POST["id"]);
		
		$this->arrayTipoEncriptacion = explode(",", $this->modeloConfiguracionFitosanitario->getEncriptacionFitosanitario());
		
		require APP . 'ConfiguracionCertificadoFitosanitarioHub/vistas/formularioConfiguracionFitosanitarioVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - ConfiguracionFitosanitario
	 */
	public function borrar(){
		$this->lNegocioConfiguracionFitosanitario->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - ConfiguracionFitosanitario
	 */
	public function tablaHtmlConfiguracionFitosanitario($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_configuracion_fitosanitario'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'ConfiguracionCertificadoFitosanitarioHub\ConfiguracionFitosanitario"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td><b>' . ++ $contador . '</b></td>
		  <td style="white - space:nowrap; ">' . ucfirst($fila['tipo_configuracion_fitosanitario']) . '</td>
<td>' . $fila['nombre_pais_fitosanitario'] . '</td>
<td>' . strtoupper($fila['plataforma_fitosanitario']) . '</td>
<td>' . $fila['certificado_digital_fitosanitario'] . '</td>
<td>' . strtoupper($fila['encriptacion_fitosanitario']) . '</td>
</tr>');
			}
		}
	}
}
