<?php
/**
 * Controlador Cie10
 *
 * Este archivo controla la lógica del negocio del modelo: Cie10Modelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2020-03-16
 * @uses Cie10Controlador
 * @package HistoriasClinicas
 * @subpackage Controladores
 */
namespace Agrodb\HistoriasClinicas\Controladores;

use Agrodb\HistoriasClinicas\Modelos\Cie10LogicaNegocio;
use Agrodb\HistoriasClinicas\Modelos\Cie10Modelo;

class Cie10Controlador extends BaseControlador{

	private $lNegocioCie10 = null;

	private $modeloCie10 = null;

	private $accion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioCie10 = new Cie10LogicaNegocio();
		$this->modeloCie10 = new Cie10Modelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloCie10 = $this->lNegocioCie10->buscarCie10();
		$this->tablaHtmlCie10($modeloCie10);
		require APP . 'HistoriasClinicas/vistas/listaCie10Vista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo Cie10";
		require APP . 'HistoriasClinicas/vistas/formularioCie10Vista.php';
	}

	/**
	 * Método para registrar en la base de datos -Cie10
	 */
	public function guardar(){
		$this->lNegocioCie10->guardar($_POST);
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: Cie10
	 */
	public function editar(){
		$this->accion = "Editar Cie10";
		$this->modeloCie10 = $this->lNegocioCie10->buscar($_POST["id"]);
		require APP . 'HistoriasClinicas/vistas/formularioCie10Vista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - Cie10
	 */
	public function borrar(){
		$this->lNegocioCie10->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - Cie10
	 */
	public function tablaHtmlCie10($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_cie_10'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'HistoriasClinicas\cie10"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_cie_10'] . '</b></td>
<td>' . $fila['codigo'] . '</td>
<td>' . $fila['descripcion'] . '</td>
<td>' . $fila['estado'] . '</td>
</tr>');
			}
		}
	}
}
