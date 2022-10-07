<?php
/**
 * Controlador ImportacionesFertilizantesProductos
 *
 * Este archivo controla la lógica del negocio del modelo: ImportacionesFertilizantesProductosModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2020-02-20
 * @uses ImportacionesFertilizantesProductosControlador
 * @package ImportacionFertilizantes
 * @subpackage Controladores
 */
namespace Agrodb\ImportacionFertilizantes\Controladores;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\ImportacionFertilizantes\Modelos\ImportacionesFertilizantesProductosLogicaNegocio;
use Agrodb\ImportacionFertilizantes\Modelos\ImportacionesFertilizantesLogicaNegocio;
use Agrodb\ImportacionFertilizantes\Modelos\ImportacionesFertilizantesProductosModelo;

class ImportacionesFertilizantesProductosControlador extends BaseControlador{

	private $lNegocioImportacionesFertilizantesProductos = null;
	
	private $lNegocioImportacionesFertilizantes = null;

	private $modeloImportacionesFertilizantesProductos = null;

	private $accion = null;

	private $rutaFecha = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		
		$this->lNegocioImportacionesFertilizantes = new ImportacionesFertilizantesLogicaNegocio();
		$this->lNegocioImportacionesFertilizantesProductos = new ImportacionesFertilizantesProductosLogicaNegocio();
		$this->modeloImportacionesFertilizantesProductos = new ImportacionesFertilizantesProductosModelo();
		
		$this->rutaFecha = date('Y').'/'.date('m').'/'.date('d');
		
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloImportacionesFertilizantesProductos = $this->lNegocioImportacionesFertilizantesProductos->buscarImportacionesFertilizantesProductos();
		$this->tablaHtmlImportacionesFertilizantesProductos($modeloImportacionesFertilizantesProductos);
		require APP . 'ImportacionFertilizantes/vistas/listaImportacionesFertilizantesProductosVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo ImportacionesFertilizantesProductos";
		require APP . 'ImportacionFertilizantes/vistas/formularioImportacionesFertilizantesProductosVista.php';
	}

	/**
	 * Método para registrar en la base de datos -ImportacionesFertilizantesProductos
	 */
	public function guardar(){
		
		$_POST['ruta_fecha'] = $this->rutaFecha.'/';
		$_POST['nombre_archivo'] = md5(mt_rand());
		$idImportacionFertilizantes = $_POST['id_importacion_fertilizantes'];
		
		$this->lNegocioImportacionesFertilizantesProductos->guardar($_POST);
		$this->lNegocioImportacionesFertilizantes->generarDocumentoFertilizantes($idImportacionFertilizantes, $_POST['ruta_fecha'], $_POST['nombre_archivo']);
		Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: ImportacionesFertilizantesProductos
	 */
	public function editar(){
		$this->accion = "Editar ImportacionesFertilizantesProductos";
		$this->modeloImportacionesFertilizantesProductos = $this->lNegocioImportacionesFertilizantesProductos->buscar($_POST["id"]);
		require APP . 'ImportacionFertilizantes/vistas/formularioImportacionesFertilizantesProductosVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - ImportacionesFertilizantesProductos
	 */
	public function borrar(){
		$this->lNegocioImportacionesFertilizantesProductos->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - ImportacionesFertilizantesProductos
	 */
	public function tablaHtmlImportacionesFertilizantesProductos($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_importacion_fertilizante_producto'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'ImportacionFertilizantes\importacionesfertilizantesproductos"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_importacion_fertilizante_producto'] . '</b></td>
<td>' . $fila['id_importacion_fertilizantes'] . '</td>
<td>' . $fila['nombre_comercial_producto'] . '</td>
<td>' . $fila['nombre_producto_origen'] . '</td>
</tr>');
			}
		}
	}
}
