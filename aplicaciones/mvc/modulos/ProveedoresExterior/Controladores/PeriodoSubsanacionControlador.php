<?php
/**
 * Controlador PeriodoSubsanacion
 *
 * Este archivo controla la lógica del negocio del modelo: PeriodoSubsanacionModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2021-07-13
 * @uses PeriodoSubsanacionControlador
 * @package ProveedoresExterior
 * @subpackage Controladores
 */
namespace Agrodb\ProveedoresExterior\Controladores;

use Agrodb\ProveedoresExterior\Modelos\PeriodoSubsanacionLogicaNegocio;
use Agrodb\ProveedoresExterior\Modelos\PeriodoSubsanacionModelo;

class PeriodoSubsanacionControlador extends BaseControlador{

	private $lNegocioPeriodoSubsanacion = null;

	private $modeloPeriodoSubsanacion = null;

	private $accion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioPeriodoSubsanacion = new PeriodoSubsanacionLogicaNegocio();
		$this->modeloPeriodoSubsanacion = new PeriodoSubsanacionModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloPeriodoSubsanacion = $this->lNegocioPeriodoSubsanacion->buscarPeriodoSubsanacion();
		$this->tablaHtmlPeriodoSubsanacion($modeloPeriodoSubsanacion);
		require APP . 'ProveedoresExterior/vistas/listaPeriodoSubsanacionVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo PeriodoSubsanacion";
		require APP . 'ProveedoresExterior/vistas/formularioPeriodoSubsanacionVista.php';
	}

	/**
	 * Método para registrar en la base de datos -PeriodoSubsanacion
	 */
	public function guardar(){
		$this->lNegocioPeriodoSubsanacion->guardar($_POST);
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: PeriodoSubsanacion
	 */
	public function editar(){
		$this->accion = "Editar PeriodoSubsanacion";
		$this->modeloPeriodoSubsanacion = $this->lNegocioPeriodoSubsanacion->buscar($_POST["id"]);
		require APP . 'ProveedoresExterior/vistas/formularioPeriodoSubsanacionVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - PeriodoSubsanacion
	 */
	public function borrar(){
		$this->lNegocioPeriodoSubsanacion->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - PeriodoSubsanacion
	 */
	public function tablaHtmlPeriodoSubsanacion($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_periodo_subsanacion'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'ProveedoresExterior\periodosubsanacion"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_periodo_subsanacion'] . '</b></td>
<td>' . $fila['tiempo_periodo_subsanacion'] . '</td>
<td>' . $fila['estado_periodo_subsanacion'] . '</td>
<td>' . $fila['fecha_creacion_periodo_subsanacion'] . '</td>
</tr>');
			}
		}
	}
}
