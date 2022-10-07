<?php
/**
 * Controlador SubtipoProductos
 *
 * Este archivo controla la lógica del negocio del modelo: SubtipoProductosModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2022-02-18
 * @uses SubtipoProductosControlador
 * @package Catalogos
 * @subpackage Controladores
 */
namespace Agrodb\Catalogos\Controladores;

use Agrodb\Catalogos\Modelos\SubtipoProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\SubtipoProductosModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\Catalogos\Modelos\ProductosLogicaNegocio;

class SubtipoProductosControlador extends BaseControlador{

	private $lNegocioSubtipoProductos = null;

	private $modeloSubtipoProductos = null;
	
	private $lNegocioProductos = null;

	private $accion = null;
	
	private $registroProducto = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioSubtipoProductos = new SubtipoProductosLogicaNegocio();
		$this->modeloSubtipoProductos = new SubtipoProductosModelo();
		
		$this->lNegocioProductos = new ProductosLogicaNegocio();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloSubtipoProductos = $this->lNegocioSubtipoProductos->buscarSubtipoProductos();
		$this->tablaHtmlSubtipoProductos($modeloSubtipoProductos);
		require APP . 'Catalogos/vistas/listaSubtipoProductosVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo SubtipoProductos";
		require APP . 'Catalogos/vistas/formularioSubtipoProductosVista.php';
	}

	/**
	 * Método para registrar en la base de datos -SubtipoProductos
	 */
	public function guardar(){
		$_POST['fecha_creacion'] = 'now()';
		$_POST['estado'] = '1';
		
		$idSubtipoProducto = $this->lNegocioSubtipoProductos->guardar($_POST);
		
		$arrayParametros[] = array(
			'id_subtipo_producto' => $idSubtipoProducto,
			'nombre' =>  $_POST['nombre']
		);
		
		$linea = $this->imprimirLineaRegistroSubTipoProducto($arrayParametros);
		
		echo json_encode(array(
			'estado' => 'exito',
			'mensaje' => Constantes::GUARDADO_CON_EXITO,
			'linea' => $linea
		));
	}
	
	/**
	 * Método para registrar en la base de datos -Subtipo de producto
	 */
	public function actualizar(){
		$_POST['fecha_modificacion'] = 'now()';
		$this->lNegocioSubtipoProductos->guardar($_POST);
		Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: SubtipoProductos
	 */
	public function editar(){
		$this->accion = "Subtipo producto";
		$arrayParametros = array('id_subtipo_producto' => $_POST["id_subtipo_producto"], 'estado' => '1');
		$this->modeloSubtipoProductos = $this->lNegocioSubtipoProductos->buscar($arrayParametros['id_subtipo_producto']);
		$this->registroProducto = $this->imprimirLineaRegistroProducto($this->lNegocioProductos->buscarLista($arrayParametros));
		require APP . 'AdministracionProductos/vistas/formularioSubtipoProductosVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - SubtipoProductos
	 */
	public function borrar(){
		$this->lNegocioSubtipoProductos->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - SubtipoProductos
	 */
	public function tablaHtmlSubtipoProductos($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_subtipo_producto'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos\subtipoproductos"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_subtipo_producto'] . '</b></td>
<td>' . $fila['nombre'] . '</td>
<td>' . $fila['estado'] . '</td>
<td>' . $fila['id_tipo_producto'] . '</td>
</tr>');
			}
		}
	}
}
