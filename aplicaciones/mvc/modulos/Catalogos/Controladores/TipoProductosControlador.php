<?php
/**
 * Controlador TipoProductos
 *
 * Este archivo controla la lógica del negocio del modelo: TipoProductosModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2022-02-18
 * @uses TipoProductosControlador
 * @package Catalogos
 * @subpackage Controladores
 */
namespace Agrodb\Catalogos\Controladores;

use Agrodb\Catalogos\Modelos\TipoProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\TipoProductosModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\Catalogos\Modelos\SubtipoProductosLogicaNegocio;

class TipoProductosControlador extends BaseControlador{

	private $lNegocioTipoProductos = null;

	private $modeloTipoProductos = null;
	
	private $lNegocioSubTipoProductos = null;

	private $accion = null;
	
	private $registroSubtipoProducto = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioTipoProductos = new TipoProductosLogicaNegocio();
		$this->modeloTipoProductos = new TipoProductosModelo();
		
		$this->lNegocioSubTipoProductos = new SubtipoProductosLogicaNegocio();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloTipoProductos = $this->lNegocioTipoProductos->buscarTipoProductos();
		$this->tablaHtmlTipoProductos($modeloTipoProductos);
		require APP . 'Catalogos/vistas/listaTipoProductosVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo TipoProductos";
		require APP . 'Catalogos/vistas/formularioTipoProductosVista.php';
	}

	/**
	 * Método para registrar en la base de datos -TipoProductos
	 */
	public function guardar(){
		$_POST['fecha_creacion'] = 'now()';
		$_POST['estado'] = '1';
		$idTipoProducto = $this->lNegocioTipoProductos->guardar($_POST);
		
		echo json_encode(array(
			'estado' => 'exito',
			'mensaje' => Constantes::GUARDADO_CON_EXITO,
			'id' => $idTipoProducto
		));
		
	}

	/**
	 * Método para registrar en la base de datos -Tipo de producto
	 */
	public function actualizar(){
		$_POST['fecha_modificacion'] = 'now()';
		$this->lNegocioTipoProductos->guardar($_POST);
		Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: TipoProductos
	 */
	public function editar(){
		$this->accion = "Tipo Producto";
		$arrayParametros = array('id_tipo_producto' => $_POST["id"]);
		$this->modeloTipoProductos = $this->lNegocioTipoProductos->buscar($arrayParametros['id_tipo_producto']);
		$this->registroSubtipoProducto = $this->imprimirLineaRegistroSubTipoProducto($this->lNegocioSubTipoProductos->buscarLista($arrayParametros));
		require APP . 'AdministracionProductos/vistas/formularioTipoSubtipoProductosVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - TipoProductos
	 */
	public function borrar(){
		$this->lNegocioTipoProductos->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - TipoProductos
	 */
	public function tablaHtmlTipoProductos($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_tipo_producto'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos\tipoproductos"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_tipo_producto'] . '</b></td>
<td>' . $fila['nombre'] . '</td>
<td>' . $fila['estado'] . '</td>
<td>' . $fila['id_area'] . '</td>
</tr>');
			}
		}
	}
}
