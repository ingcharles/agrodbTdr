<?php
/**
 * Controlador DetalleTecnico
 *
 * Este archivo controla la lógica del negocio del modelo: DetalleTecnicoModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2021-10-18
 * @uses DetalleTecnicoControlador
 * @package RegistroControlDocumentos
 * @subpackage Controladores
 */
namespace Agrodb\RegistroControlDocumentos\Controladores;

use Agrodb\RegistroControlDocumentos\Modelos\DetalleTecnicoLogicaNegocio;
use Agrodb\RegistroControlDocumentos\Modelos\DetalleTecnicoModelo;

class DetalleTecnicoControlador extends BaseControlador{

	private $lNegocioDetalleTecnico = null;

	private $modeloDetalleTecnico = null;

	private $accion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioDetalleTecnico = new DetalleTecnicoLogicaNegocio();
		$this->modeloDetalleTecnico = new DetalleTecnicoModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloDetalleTecnico = $this->lNegocioDetalleTecnico->buscarDetalleTecnico();
		$this->tablaHtmlDetalleTecnico($modeloDetalleTecnico);
		require APP . 'RegistroControlDocumentos/vistas/listaDetalleTecnicoVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo DetalleTecnico";
		require APP . 'RegistroControlDocumentos/vistas/formularioDetalleTecnicoVista.php';
	}

	/**
	 * Método para registrar en la base de datos -DetalleTecnico
	 */
	public function guardar(){
		$this->lNegocioDetalleTecnico->guardar($_POST);
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: DetalleTecnico
	 */
	public function editar(){
		$this->accion = "Editar DetalleTecnico";
		$this->modeloDetalleTecnico = $this->lNegocioDetalleTecnico->buscar($_POST["id"]);
		require APP . 'RegistroControlDocumentos/vistas/formularioDetalleTecnicoVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - DetalleTecnico
	 */
	public function borrar(){
		$this->lNegocioDetalleTecnico->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - DetalleTecnico
	 */
	public function tablaHtmlDetalleTecnico($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_detalle_tecnico'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'RegistroControlDocumentos\detalletecnico"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_detalle_tecnico'] . '</b></td>
<td>' . $fila['id_registro_sgc'] . '</td>
<td>' . $fila['identificador'] . '</td>
<td>' . $fila['tecnico'] . '</td>
</tr>');
			}
		}
	}
}
