<?php
/**
 * Controlador ImportacionesProductos
 *
 * Este archivo controla la lógica del negocio del modelo: ImportacionesProductosModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2021-07-06
 * @uses ImportacionesProductosControlador
 * @package Importaciones
 * @subpackage Controladores
 */
namespace Agrodb\Importaciones\Controladores;

use Agrodb\Importaciones\Modelos\ImportacionesProductosLogicaNegocio;
use Agrodb\Importaciones\Modelos\ImportacionesProductosModelo;

class ImportacionesProductosControlador extends BaseControlador{

	private $lNegocioImportacionesProductos = null;

	private $modeloImportacionesProductos = null;

	private $accion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioImportacionesProductos = new ImportacionesProductosLogicaNegocio();
		$this->modeloImportacionesProductos = new ImportacionesProductosModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloImportacionesProductos = $this->lNegocioImportacionesProductos->buscarImportacionesProductos();
		$this->tablaHtmlImportacionesProductos($modeloImportacionesProductos);
		require APP . 'Importaciones/vistas/listaImportacionesProductosVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo ImportacionesProductos";
		require APP . 'Importaciones/vistas/formularioImportacionesProductosVista.php';
	}

	/**
	 * Método para registrar en la base de datos -ImportacionesProductos
	 */
	public function guardar(){
		$this->lNegocioImportacionesProductos->guardar($_POST);
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: ImportacionesProductos
	 */
	public function editar(){
		$this->accion = "Editar ImportacionesProductos";
		$this->modeloImportacionesProductos = $this->lNegocioImportacionesProductos->buscar($_POST["id"]);
		require APP . 'Importaciones/vistas/formularioImportacionesProductosVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - ImportacionesProductos
	 */
	public function borrar(){
		$this->lNegocioImportacionesProductos->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - ImportacionesProductos
	 */
	public function tablaHtmlImportacionesProductos($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_importacion_producto'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Importaciones\importacionesproductos"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_importacion_producto'] . '</b></td>
<td>' . $fila['id_importacion'] . '</td>
<td>' . $fila['id_producto'] . '</td>
<td>' . $fila['nombre_producto'] . '</td>
</tr>');
			}
		}
	}
}
