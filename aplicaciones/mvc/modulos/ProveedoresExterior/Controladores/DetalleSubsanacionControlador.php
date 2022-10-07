<?php
/**
 * Controlador DetalleSubsanacion
 *
 * Este archivo controla la lógica del negocio del modelo: DetalleSubsanacionModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2021-07-13
 * @uses DetalleSubsanacionControlador
 * @package ProveedoresExterior
 * @subpackage Controladores
 */
namespace Agrodb\ProveedoresExterior\Controladores;

use Agrodb\ProveedoresExterior\Modelos\DetalleSubsanacionLogicaNegocio;
use Agrodb\ProveedoresExterior\Modelos\DetalleSubsanacionModelo;

class DetalleSubsanacionControlador extends BaseControlador{

	private $lNegocioDetalleSubsanacion = null;

	private $modeloDetalleSubsanacion = null;

	private $accion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioDetalleSubsanacion = new DetalleSubsanacionLogicaNegocio();
		$this->modeloDetalleSubsanacion = new DetalleSubsanacionModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloDetalleSubsanacion = $this->lNegocioDetalleSubsanacion->buscarDetalleSubsanacion();
		$this->tablaHtmlDetalleSubsanacion($modeloDetalleSubsanacion);
		require APP . 'ProveedoresExterior/vistas/listaDetalleSubsanacionVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo DetalleSubsanacion";
		require APP . 'ProveedoresExterior/vistas/formularioDetalleSubsanacionVista.php';
	}

	/**
	 * Método para registrar en la base de datos -DetalleSubsanacion
	 */
	public function guardar(){
		$this->lNegocioDetalleSubsanacion->guardar($_POST);
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: DetalleSubsanacion
	 */
	public function editar(){
		$this->accion = "Editar DetalleSubsanacion";
		$this->modeloDetalleSubsanacion = $this->lNegocioDetalleSubsanacion->buscar($_POST["id"]);
		require APP . 'ProveedoresExterior/vistas/formularioDetalleSubsanacionVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - DetalleSubsanacion
	 */
	public function borrar(){
		$this->lNegocioDetalleSubsanacion->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - DetalleSubsanacion
	 */
	public function tablaHtmlDetalleSubsanacion($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_detalle_subsanacion'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'ProveedoresExterior\detallesubsanacion"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_detalle_subsanacion'] . '</b></td>
<td>' . $fila['id_subsanacion'] . '</td>
<td>' . $fila['identificador_revisor'] . '</td>
<td>' . $fila['fecha_subsanacion'] . '</td>
</tr>');
			}
		}
	}
}
