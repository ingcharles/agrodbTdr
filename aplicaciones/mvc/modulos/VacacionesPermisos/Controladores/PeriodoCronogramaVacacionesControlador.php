<?php

/**
 * Controlador PeriodoCronogramaVacaciones
 *
 * Este archivo controla la lógica del negocio del modelo:  PeriodoCronogramaVacacionesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-10-22
 * @uses    PeriodoCronogramaVacacionesControlador
 * @package VacacionesPermisos
 * @subpackage Controladores
 */

namespace Agrodb\VacacionesPermisos\Controladores;

use Agrodb\VacacionesPermisos\Modelos\PeriodoCronogramaVacacionesLogicaNegocio;
use Agrodb\VacacionesPermisos\Modelos\PeriodoCronogramaVacacionesModelo;

class PeriodoCronogramaVacacionesControlador extends BaseControlador
{

	private $lNegocioPeriodoCronogramaVacaciones = null;
	private $modeloPeriodoCronogramaVacaciones = null;
	private $accion = null;
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->lNegocioPeriodoCronogramaVacaciones = new PeriodoCronogramaVacacionesLogicaNegocio();
		$this->modeloPeriodoCronogramaVacaciones = new PeriodoCronogramaVacacionesModelo();
		set_exception_handler(array($this, 'manejadorExcepciones'));
	}
	/**
	 * Método de inicio del controlador
	 */
	public function index()
	{
		$modeloPeriodoCronogramaVacaciones = $this->lNegocioPeriodoCronogramaVacaciones->buscarPeriodoCronogramaVacaciones();
		$this->tablaHtmlPeriodoCronogramaVacaciones($modeloPeriodoCronogramaVacaciones);
		require APP . 'VacacionesPermisos/vistas/listaPeriodoCronogramaVacacionesVista.php';
	}
	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo()
	{
		$this->accion = "Nuevo PeriodoCronogramaVacaciones";
		require APP . 'VacacionesPermisos/vistas/formularioPeriodoCronogramaVacacionesVista.php';
	}
	/**
	 * Método para registrar en la base de datos -PeriodoCronogramaVacaciones
	 */
	public function guardar()
	{
		$this->lNegocioPeriodoCronogramaVacaciones->guardar($_POST);
	}
	/**
	 *Obtenemos los datos del registro seleccionado para editar - Tabla: PeriodoCronogramaVacaciones
	 */
	public function editar()
	{
		$this->accion = "Editar PeriodoCronogramaVacaciones";
		$this->modeloPeriodoCronogramaVacaciones = $this->lNegocioPeriodoCronogramaVacaciones->buscar($_POST["id"]);
		require APP . 'VacacionesPermisos/vistas/formularioPeriodoCronogramaVacacionesVista.php';
	}
	/**
	 * Método para borrar un registro en la base de datos - PeriodoCronogramaVacaciones
	 */
	public function borrar()
	{
		$this->lNegocioPeriodoCronogramaVacaciones->borrar($_POST['elementos']);
	}
	/**
	 * Construye el código HTML para desplegar la lista de - PeriodoCronogramaVacaciones
	 */
	public function tablaHtmlPeriodoCronogramaVacaciones($tabla)
	{ {
			$contador = 0;
			foreach ($tabla as $fila) {
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_periodo_cronograma_vacacion'] . '"
				class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'VacacionesPermisos\periodocronogramavacaciones"
				data-opcion="editar" ondragstart="drag(event)" draggable="true"
				data-destino="detalleItem">
				<td>' . ++$contador . '</td>
				<td style="white - space:nowrap; "><b>' . $fila['id_periodo_cronograma_vacacion'] . '</b></td>
				<td>'
						. $fila['id_cronograma_vacacion'] . '</td>
				<td>' . $fila['numero_periodo']
						. '</td>
				<td>' . $fila['fecha_inicio'] . '</td>
				</tr>'
				);
			}
		}
	}
}
