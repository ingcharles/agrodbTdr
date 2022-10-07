<?php
/**
 * Controlador Log
 *
 * Este archivo controla la lógica del negocio del modelo: LogModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2020-03-16
 * @uses LogControlador
 * @package HistoriasClinicas
 * @subpackage Controladores
 */
namespace Agrodb\HistoriasClinicas\Controladores;

use Agrodb\HistoriasClinicas\Modelos\LogLogicaNegocio;
use Agrodb\HistoriasClinicas\Modelos\LogModelo;

class LogControlador extends BaseControlador{

	private $lNegocioLog = null;

	private $modeloLog = null;

	private $accion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioLog = new LogLogicaNegocio();
		$this->modeloLog = new LogModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloLog = $this->lNegocioLog->buscarLog();
		$this->tablaHtmlLog($modeloLog);
		require APP . 'HistoriasClinicas/vistas/listaLogVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo Log";
		require APP . 'HistoriasClinicas/vistas/formularioLogVista.php';
	}

	/**
	 * Método para registrar en la base de datos -Log
	 */
	public function guardar(){
		$this->lNegocioLog->guardar($_POST);
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: Log
	 */
	public function editar(){
		$this->accion = "Editar Log";
		$this->modeloLog = $this->lNegocioLog->buscar($_POST["id"]);
		require APP . 'HistoriasClinicas/vistas/formularioLogVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - Log
	 */
	public function borrar(){
		$this->lNegocioLog->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - Log
	 */
	public function tablaHtmlLog($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_log'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'HistoriasClinicas\log"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_log'] . '</b></td>
<td>' . $fila['identificador'] . '</td>
<td>' . $fila['nombre_provincia'] . '</td>
<td>' . $fila['area'] . '</td>
</tr>');
			}
		}
	}
}
