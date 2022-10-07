<?php
/**
 * Controlador DetalleSocializacion
 *
 * Este archivo controla la lógica del negocio del modelo: DetalleSocializacionModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2021-10-18
 * @uses DetalleSocializacionControlador
 * @package RegistroControlDocumentos
 * @subpackage Controladores
 */
namespace Agrodb\RegistroControlDocumentos\Controladores;

use Agrodb\RegistroControlDocumentos\Modelos\DetalleSocializacionLogicaNegocio;
use Agrodb\RegistroControlDocumentos\Modelos\DetalleSocializacionModelo;

class DetalleSocializacionControlador extends BaseControlador{

	private $lNegocioDetalleSocializacion = null;

	private $modeloDetalleSocializacion = null;

	private $accion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioDetalleSocializacion = new DetalleSocializacionLogicaNegocio();
		$this->modeloDetalleSocializacion = new DetalleSocializacionModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloDetalleSocializacion = $this->lNegocioDetalleSocializacion->buscarDetalleSocializacion();
		$this->tablaHtmlDetalleSocializacion($modeloDetalleSocializacion);
		require APP . 'RegistroControlDocumentos/vistas/listaDetalleSocializacionVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo DetalleSocializacion";
		require APP . 'RegistroControlDocumentos/vistas/formularioDetalleSocializacionVista.php';
	}

	/**
	 * Método para registrar en la base de datos -DetalleSocializacion
	 */
	public function guardar(){
		$this->lNegocioDetalleSocializacion->guardar($_POST);
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: DetalleSocializacion
	 */
	public function editar(){
		$this->accion = "Editar DetalleSocializacion";
		$this->modeloDetalleSocializacion = $this->lNegocioDetalleSocializacion->buscar($_POST["id"]);
		require APP . 'RegistroControlDocumentos/vistas/formularioDetalleSocializacionVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - DetalleSocializacion
	 */
	public function borrar(){
		$this->lNegocioDetalleSocializacion->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - DetalleSocializacion
	 */
	public function tablaHtmlDetalleSocializacion($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_detalle_socializacion'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'RegistroControlDocumentos\detallesocializacion"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_detalle_socializacion'] . '</b></td>
<td>' . $fila['id_registro_sgc'] . '</td>
<td>' . $fila['estado_socializar'] . '</td>
<td>' . $fila['fecha_socializacion'] . '</td>
</tr>');
			}
		}
	}
}
