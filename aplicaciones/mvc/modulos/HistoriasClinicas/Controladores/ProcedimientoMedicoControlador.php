<?php
/**
 * Controlador ProcedimientoMedico
 *
 * Este archivo controla la lógica del negocio del modelo: ProcedimientoMedicoModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2020-03-16
 * @uses ProcedimientoMedicoControlador
 * @package HistoriasClinicas
 * @subpackage Controladores
 */
namespace Agrodb\HistoriasClinicas\Controladores;

use Agrodb\HistoriasClinicas\Modelos\ProcedimientoMedicoLogicaNegocio;
use Agrodb\HistoriasClinicas\Modelos\ProcedimientoMedicoModelo;

class ProcedimientoMedicoControlador extends BaseControlador{

	private $lNegocioProcedimientoMedico = null;

	private $modeloProcedimientoMedico = null;

	private $accion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioProcedimientoMedico = new ProcedimientoMedicoLogicaNegocio();
		$this->modeloProcedimientoMedico = new ProcedimientoMedicoModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloProcedimientoMedico = $this->lNegocioProcedimientoMedico->buscarProcedimientoMedico();
		$this->tablaHtmlProcedimientoMedico($modeloProcedimientoMedico);
		require APP . 'HistoriasClinicas/vistas/listaProcedimientoMedicoVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo ProcedimientoMedico";
		require APP . 'HistoriasClinicas/vistas/formularioProcedimientoMedicoVista.php';
	}

	/**
	 * Método para registrar en la base de datos -ProcedimientoMedico
	 */
	public function guardar(){
		$this->lNegocioProcedimientoMedico->guardar($_POST);
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: ProcedimientoMedico
	 */
	public function editar(){
		$this->accion = "Editar ProcedimientoMedico";
		$this->modeloProcedimientoMedico = $this->lNegocioProcedimientoMedico->buscar($_POST["id"]);
		require APP . 'HistoriasClinicas/vistas/formularioProcedimientoMedicoVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - ProcedimientoMedico
	 */
	public function borrar(){
		$this->lNegocioProcedimientoMedico->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - ProcedimientoMedico
	 */
	public function tablaHtmlProcedimientoMedico($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_procedimiento_medico'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'HistoriasClinicas\procedimientomedico"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_procedimiento_medico'] . '</b></td>
<td>' . $fila['nombre'] . '</td>
<td>' . $fila['estado'] . '</td>
<td>' . $fila['fecha_creacion'] . '</td>
</tr>');
			}
		}
	}
}
