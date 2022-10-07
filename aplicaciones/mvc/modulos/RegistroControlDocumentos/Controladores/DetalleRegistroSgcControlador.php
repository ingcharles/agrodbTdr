<?php
/**
 * Controlador DetalleRegistroSgc
 *
 * Este archivo controla la lógica del negocio del modelo: DetalleRegistroSgcModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2021-10-18
 * @uses DetalleRegistroSgcControlador
 * @package RegistroControlDocumentos
 * @subpackage Controladores
 */
namespace Agrodb\RegistroControlDocumentos\Controladores;

use Agrodb\RegistroControlDocumentos\Modelos\DetalleRegistroSgcLogicaNegocio;
use Agrodb\RegistroControlDocumentos\Modelos\DetalleRegistroSgcModelo;

class DetalleRegistroSgcControlador extends BaseControlador{

	private $lNegocioDetalleRegistroSgc = null;

	private $modeloDetalleRegistroSgc = null;

	private $accion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioDetalleRegistroSgc = new DetalleRegistroSgcLogicaNegocio();
		$this->modeloDetalleRegistroSgc = new DetalleRegistroSgcModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloDetalleRegistroSgc = $this->lNegocioDetalleRegistroSgc->buscarDetalleRegistroSgc();
		$this->tablaHtmlDetalleRegistroSgc($modeloDetalleRegistroSgc);
		require APP . 'RegistroControlDocumentos/vistas/listaDetalleRegistroSgcVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo DetalleRegistroSgc";
		require APP . 'RegistroControlDocumentos/vistas/formularioDetalleRegistroSgcVista.php';
	}

	/**
	 * Método para registrar en la base de datos -DetalleRegistroSgc
	 */
	public function guardar(){
		$this->lNegocioDetalleRegistroSgc->guardar($_POST);
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: DetalleRegistroSgc
	 */
	public function editar(){
		$this->accion = "Editar DetalleRegistroSgc";
		$this->modeloDetalleRegistroSgc = $this->lNegocioDetalleRegistroSgc->buscar($_POST["id"]);
		require APP . 'RegistroControlDocumentos/vistas/formularioDetalleRegistroSgcVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - DetalleRegistroSgc
	 */
	public function borrar(){
		$this->lNegocioDetalleRegistroSgc->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - DetalleRegistroSgc
	 */
	public function tablaHtmlDetalleRegistroSgc($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_detalle_registro_sgc'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'RegistroControlDocumentos\detalleregistrosgc"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_detalle_registro_sgc'] . '</b></td>
<td>' . $fila['id_registro_sgc'] . '</td>
<td>' . $fila['numedo_glpi'] . '</td>
<td>' . $fila['asunto'] . '</td>
</tr>');
			}
		}
	}
}
