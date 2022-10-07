<?php
/**
 * Controlador DetalleDestinatario
 *
 * Este archivo controla la lógica del negocio del modelo: DetalleDestinatarioModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2021-10-18
 * @uses DetalleDestinatarioControlador
 * @package RegistroControlDocumentos
 * @subpackage Controladores
 */
namespace Agrodb\RegistroControlDocumentos\Controladores;

use Agrodb\RegistroControlDocumentos\Modelos\DetalleDestinatarioLogicaNegocio;
use Agrodb\RegistroControlDocumentos\Modelos\DetalleDestinatarioModelo;

class DetalleDestinatarioControlador extends BaseControlador{

	private $lNegocioDetalleDestinatario = null;

	private $modeloDetalleDestinatario = null;

	private $accion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioDetalleDestinatario = new DetalleDestinatarioLogicaNegocio();
		$this->modeloDetalleDestinatario = new DetalleDestinatarioModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloDetalleDestinatario = $this->lNegocioDetalleDestinatario->buscarDetalleDestinatario();
		$this->tablaHtmlDetalleDestinatario($modeloDetalleDestinatario);
		require APP . 'RegistroControlDocumentos/vistas/listaDetalleDestinatarioVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo DetalleDestinatario";
		require APP . 'RegistroControlDocumentos/vistas/formularioDetalleDestinatarioVista.php';
	}

	/**
	 * Método para registrar en la base de datos -DetalleDestinatario
	 */
	public function guardar(){
		$this->lNegocioDetalleDestinatario->guardar($_POST);
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: DetalleDestinatario
	 */
	public function editar(){
		$this->accion = "Editar DetalleDestinatario";
		$this->modeloDetalleDestinatario = $this->lNegocioDetalleDestinatario->buscar($_POST["id"]);
		require APP . 'RegistroControlDocumentos/vistas/formularioDetalleDestinatarioVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - DetalleDestinatario
	 */
	public function borrar(){
		$this->lNegocioDetalleDestinatario->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - DetalleDestinatario
	 */
	public function tablaHtmlDetalleDestinatario($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_detalle_destinatario'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'RegistroControlDocumentos\detalledestinatario"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_detalle_destinatario'] . '</b></td>
<td>' . $fila['id_registro_sgc'] . '</td>
<td>' . $fila['decripcion'] . '</td>
<td>' . $fila['fecha_creacion'] . '</td>
</tr>');
			}
		}
	}
}
