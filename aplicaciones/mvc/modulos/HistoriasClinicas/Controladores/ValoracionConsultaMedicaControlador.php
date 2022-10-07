<?php
/**
 * Controlador ValoracionConsultaMedica
 *
 * Este archivo controla la lógica del negocio del modelo: ValoracionConsultaMedicaModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2020-03-16
 * @uses ValoracionConsultaMedicaControlador
 * @package HistoriasClinicas
 * @subpackage Controladores
 */
namespace Agrodb\HistoriasClinicas\Controladores;

use Agrodb\HistoriasClinicas\Modelos\ValoracionConsultaMedicaLogicaNegocio;
use Agrodb\HistoriasClinicas\Modelos\ValoracionConsultaMedicaModelo;

class ValoracionConsultaMedicaControlador extends BaseControlador{

	private $lNegocioValoracionConsultaMedica = null;

	private $modeloValoracionConsultaMedica = null;

	private $accion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioValoracionConsultaMedica = new ValoracionConsultaMedicaLogicaNegocio();
		$this->modeloValoracionConsultaMedica = new ValoracionConsultaMedicaModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloValoracionConsultaMedica = $this->lNegocioValoracionConsultaMedica->buscarValoracionConsultaMedica();
		$this->tablaHtmlValoracionConsultaMedica($modeloValoracionConsultaMedica);
		require APP . 'HistoriasClinicas/vistas/listaValoracionConsultaMedicaVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo ValoracionConsultaMedica";
		require APP . 'HistoriasClinicas/vistas/formularioValoracionConsultaMedicaVista.php';
	}

	/**
	 * Método para registrar en la base de datos -ValoracionConsultaMedica
	 */
	public function guardar(){
		$this->lNegocioValoracionConsultaMedica->guardar($_POST);
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: ValoracionConsultaMedica
	 */
	public function editar(){
		$this->accion = "Editar ValoracionConsultaMedica";
		$this->modeloValoracionConsultaMedica = $this->lNegocioValoracionConsultaMedica->buscar($_POST["id"]);
		require APP . 'HistoriasClinicas/vistas/formularioValoracionConsultaMedicaVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - ValoracionConsultaMedica
	 */
	public function borrar(){
		$this->lNegocioValoracionConsultaMedica->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - ValoracionConsultaMedica
	 */
	public function tablaHtmlValoracionConsultaMedica($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_valoracion_consulta_medica'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'HistoriasClinicas\valoracionconsultamedica"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_valoracion_consulta_medica'] . '</b></td>
<td>' . $fila['id_consulta_medica'] . '</td>
<td>' . $fila['medicacion'] . '</td>
<td>' . $fila['medicamento'] . '</td>
</tr>');
			}
		}
	}
}
