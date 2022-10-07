<?php
/**
 * Controlador TipoProcedimientoMedico
 *
 * Este archivo controla la lógica del negocio del modelo: TipoProcedimientoMedicoModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2020-03-16
 * @uses TipoProcedimientoMedicoControlador
 * @package HistoriasClinicas
 * @subpackage Controladores
 */
namespace Agrodb\HistoriasClinicas\Controladores;

use Agrodb\HistoriasClinicas\Modelos\TipoProcedimientoMedicoLogicaNegocio;
use Agrodb\HistoriasClinicas\Modelos\TipoProcedimientoMedicoModelo;

class TipoProcedimientoMedicoControlador extends BaseControlador{

	private $lNegocioTipoProcedimientoMedico = null;

	private $modeloTipoProcedimientoMedico = null;

	private $accion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioTipoProcedimientoMedico = new TipoProcedimientoMedicoLogicaNegocio();
		$this->modeloTipoProcedimientoMedico = new TipoProcedimientoMedicoModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloTipoProcedimientoMedico = $this->lNegocioTipoProcedimientoMedico->buscarTipoProcedimientoMedico();
		$this->tablaHtmlTipoProcedimientoMedico($modeloTipoProcedimientoMedico);
		require APP . 'HistoriasClinicas/vistas/listaTipoProcedimientoMedicoVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo TipoProcedimientoMedico";
		require APP . 'HistoriasClinicas/vistas/formularioTipoProcedimientoMedicoVista.php';
	}

	/**
	 * Método para registrar en la base de datos -TipoProcedimientoMedico
	 */
	public function guardar(){
		$this->lNegocioTipoProcedimientoMedico->guardar($_POST);
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: TipoProcedimientoMedico
	 */
	public function editar(){
		$this->accion = "Editar TipoProcedimientoMedico";
		$this->modeloTipoProcedimientoMedico = $this->lNegocioTipoProcedimientoMedico->buscar($_POST["id"]);
		require APP . 'HistoriasClinicas/vistas/formularioTipoProcedimientoMedicoVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - TipoProcedimientoMedico
	 */
	public function borrar(){
		$this->lNegocioTipoProcedimientoMedico->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - TipoProcedimientoMedico
	 */
	public function tablaHtmlTipoProcedimientoMedico($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_tipo_procedimiento_medico'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'HistoriasClinicas\tipoprocedimientomedico"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_tipo_procedimiento_medico'] . '</b></td>
<td>' . $fila['id_procedimiento_medico'] . '</td>
<td>' . $fila['tipo'] . '</td>
<td>' . $fila['estado'] . '</td>
</tr>');
			}
		}
	}
}
