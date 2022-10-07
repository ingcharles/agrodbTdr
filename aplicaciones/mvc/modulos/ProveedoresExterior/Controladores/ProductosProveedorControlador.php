<?php
/**
 * Controlador ProductosProveedor
 *
 * Este archivo controla la lógica del negocio del modelo: ProductosProveedorModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2021-07-13
 * @uses ProductosProveedorControlador
 * @package ProveedoresExterior
 * @subpackage Controladores
 */
namespace Agrodb\ProveedoresExterior\Controladores;

use Agrodb\ProveedoresExterior\Modelos\ProductosProveedorLogicaNegocio;
use Agrodb\ProveedoresExterior\Modelos\ProductosProveedorModelo;

class ProductosProveedorControlador extends BaseControlador{

	private $lNegocioProductosProveedor = null;

	private $modeloProductosProveedor = null;

	private $accion = null;

	private $productosProveedor = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioProductosProveedor = new ProductosProveedorLogicaNegocio();
		$this->modeloProductosProveedor = new ProductosProveedorModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloProductosProveedor = $this->lNegocioProductosProveedor->buscarProductosProveedor();
		$this->tablaHtmlProductosProveedor($modeloProductosProveedor);
		require APP . 'ProveedoresExterior/vistas/listaProductosProveedorVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo ProductosProveedor";
		require APP . 'ProveedoresExterior/vistas/formularioProductosProveedorVista.php';
	}

	/**
	 * Método para registrar en la base de datos -ProductosProveedor
	 */
	public function guardar(){
		$idProveedorExterior = $_POST['idProveedorExterior'];
		$idSubtipoProducto = $_POST['idSubtipoProducto'];
		$nombreSubtipoProducto = $_POST['nombreSubtipoProducto'];
		$filas = $_POST['filas'];

		$validacion = "Fallo";
		$resultado = "El tipo de producto ya ha sido registrado.";

		$arrayParametros = array(
			'id_proveedor_exterior' => $idProveedorExterior,
		    'id_subtipo_producto' => $idSubtipoProducto,
		    'nombre_subtipo_producto' => $nombreSubtipoProducto);

		$verificarProductosProveedor = $this->lNegocioProductosProveedor->obtenerProductosProveedor($arrayParametros);

		if (!isset($verificarProductosProveedor->current()->id_producto_proveedor)){

			$validacion = "Exito";
			$resultado = "";

			$datosProductosProveedor = $this->lNegocioProductosProveedor->guardar($arrayParametros);

			$filaProductosProveedor = $this->generarFilaProductosProveedor($datosProductosProveedor, $filas);

			echo json_encode(array(
				'validacion' => $validacion,
				'resultado' => $resultado,
				'filaProductosProveedor' => $filaProductosProveedor));
		}else{
			echo json_encode(array(
				'validacion' => $validacion,
				'resultado' => $resultado));
		}
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: ProductosProveedor
	 */
	public function editar(){
		$this->accion = "Editar ProductosProveedor";
		$this->modeloProductosProveedor = $this->lNegocioProductosProveedor->buscar($_POST["id"]);
		require APP . 'ProveedoresExterior/vistas/formularioProductosProveedorVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - ProductosProveedor
	 */
	public function borrar(){
		$this->lNegocioProductosProveedor->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - ProductosProveedor
	 */
	public function tablaHtmlProductosProveedor($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_producto_proveedor'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'ProveedoresExterior\productosproveedor"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_producto_proveedor'] . '</b></td>
        <td>' . $fila['id_proveedor_exterior'] . '</td>
        <td>' . $fila['id_tipo_producto'] . '</td>
        <td>' . $fila['nombre_tipo_producto'] . '</td>
        </tr>');
			}
		}
	}

	/**
	 * Método para agregar una fila de tipo de producto del proveeedor.
	 */
	public function generarFilaProductosProveedor($idProductoProveedor, $filas){
		$this->productosProveedor = $this->lNegocioProductosProveedor->buscar($idProductoProveedor);

		$nombreSubtipoProducto = $this->productosProveedor->getNombreSubtipoProducto();

		$i = $filas + 1;

		$this->listaDetalles = '
                        <tr id="fila' . $idProductoProveedor . '">
                            <td>' . $i . '</td>
                            <td>' . ($nombreSubtipoProducto != '' ? $nombreSubtipoProducto : '') . '</td>
                            <td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarDetalleProductosProveedor(' . $idProductoProveedor . '); return false;"/></td>
                        </tr>';

		return $this->listaDetalles;
	}
}
