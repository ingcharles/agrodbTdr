<?php
/**
 * Controlador Subsanacion
 *
 * Este archivo controla la lógica del negocio del modelo: SubsanacionModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2021-07-13
 * @uses SubsanacionControlador
 * @package ProveedoresExterior
 * @subpackage Controladores
 */
namespace Agrodb\ProveedoresExterior\Controladores;

use Agrodb\ProveedoresExterior\Modelos\SubsanacionLogicaNegocio;
use Agrodb\ProveedoresExterior\Modelos\SubsanacionModelo;

class SubsanacionControlador extends BaseControlador{

	private $lNegocioSubsanacion = null;

	private $modeloSubsanacion = null;

	private $accion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioSubsanacion = new SubsanacionLogicaNegocio();
		$this->modeloSubsanacion = new SubsanacionModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloSubsanacion = $this->lNegocioSubsanacion->buscarSubsanacion();
		$this->tablaHtmlSubsanacion($modeloSubsanacion);
		require APP . 'ProveedoresExterior/vistas/listaSubsanacionVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo Subsanacion";
		require APP . 'ProveedoresExterior/vistas/formularioSubsanacionVista.php';
	}

	/**
	 * Método para registrar en la base de datos -Subsanacion
	 */
	public function guardar(){
		$this->lNegocioSubsanacion->guardar($_POST);
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: Subsanacion
	 */
	public function editar(){
		$this->accion = "Editar Subsanacion";
		$this->modeloSubsanacion = $this->lNegocioSubsanacion->buscar($_POST["id"]);
		require APP . 'ProveedoresExterior/vistas/formularioSubsanacionVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - Subsanacion
	 */
	public function borrar(){
		$this->lNegocioSubsanacion->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - Subsanacion
	 */
	public function tablaHtmlSubsanacion($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_subsanacion'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'ProveedoresExterior\subsanacion"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_subsanacion'] . '</b></td>
<td>' . $fila['id_proveedor_exterior'] . '</td>
<td>' . $fila['id_periodo_subsanacion'] . '</td>
<td>' . $fila['dias_subsanacion'] . '</td>
</tr>');
			}
		}
	}
}
