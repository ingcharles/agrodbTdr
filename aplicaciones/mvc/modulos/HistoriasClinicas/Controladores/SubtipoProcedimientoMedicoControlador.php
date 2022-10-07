<?php
/**
 * Controlador SubtipoProcedimientoMedico
 *
 * Este archivo controla la lógica del negocio del modelo: SubtipoProcedimientoMedicoModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2020-03-16
 * @uses SubtipoProcedimientoMedicoControlador
 * @package HistoriasClinicas
 * @subpackage Controladores
 */
namespace Agrodb\HistoriasClinicas\Controladores;

use Agrodb\HistoriasClinicas\Modelos\SubtipoProcedimientoMedicoLogicaNegocio;
use Agrodb\HistoriasClinicas\Modelos\SubtipoProcedimientoMedicoModelo;

class SubtipoProcedimientoMedicoControlador extends BaseControlador{

	private $lNegocioSubtipoProcedimientoMedico = null;

	private $modeloSubtipoProcedimientoMedico = null;

	private $accion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioSubtipoProcedimientoMedico = new SubtipoProcedimientoMedicoLogicaNegocio();
		$this->modeloSubtipoProcedimientoMedico = new SubtipoProcedimientoMedicoModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloSubtipoProcedimientoMedico = $this->lNegocioSubtipoProcedimientoMedico->buscarSubtipoProcedimientoMedico();
		$this->tablaHtmlSubtipoProcedimientoMedico($modeloSubtipoProcedimientoMedico);
		require APP . 'HistoriasClinicas/vistas/listaSubtipoProcedimientoMedicoVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo SubtipoProcedimientoMedico";
		require APP . 'HistoriasClinicas/vistas/formularioSubtipoProcedimientoMedicoVista.php';
	}

	/**
	 * Método para registrar en la base de datos -SubtipoProcedimientoMedico
	 */
	public function guardar(){
		$this->lNegocioSubtipoProcedimientoMedico->guardar($_POST);
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: SubtipoProcedimientoMedico
	 */
	public function editar(){
		$this->accion = "Editar SubtipoProcedimientoMedico";
		$this->modeloSubtipoProcedimientoMedico = $this->lNegocioSubtipoProcedimientoMedico->buscar($_POST["id"]);
		require APP . 'HistoriasClinicas/vistas/formularioSubtipoProcedimientoMedicoVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - SubtipoProcedimientoMedico
	 */
	public function borrar(){
		$this->lNegocioSubtipoProcedimientoMedico->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - SubtipoProcedimientoMedico
	 */
	public function tablaHtmlSubtipoProcedimientoMedico($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_subtipo_proced_medico'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'HistoriasClinicas\subtipoprocedimientomedico"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_subtipo_proced_medico'] . '</b></td>
<td>' . $fila['id_tipo_procedimiento_medico'] . '</td>
<td>' . $fila['subtipo'] . '</td>
<td>' . $fila['estado'] . '</td>
</tr>');
			}
		}
	}
}
