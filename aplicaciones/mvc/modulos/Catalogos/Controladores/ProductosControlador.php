<?php
/**
 * Controlador Productos
 *
 * Este archivo controla la lógica del negocio del modelo: ProductosModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2022-02-18
 * @uses ProductosControlador
 * @package Catalogos
 * @subpackage Controladores
 */
namespace Agrodb\Catalogos\Controladores;

use Agrodb\Catalogos\Modelos\ProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\ProductosModelo;
use Agrodb\Catalogos\Modelos\ParametrosLogicaNegocio;

class ProductosControlador extends BaseControlador{

	private $lNegocioProductos = null;

	private $modeloProductos = null;
	
	private $lNegocioParametros = null;

	private $accion = null;
	
	private $linea = null;
	
	private $registroParametro = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioProductos = new ProductosLogicaNegocio();
		$this->modeloProductos = new ProductosModelo();
		
		$this->lNegocioParametros = new ParametrosLogicaNegocio();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloProductos = $this->lNegocioProductos->buscarProductos();
		$this->tablaHtmlProductos($modeloProductos);
		require APP . 'Catalogos/vistas/listaProductosVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo Productos";
		require APP . 'Catalogos/vistas/formularioProductoParametrosVista.php';
	}

	/**
	 * Método para registrar en la base de datos -Productos
	 */
	public function guardar(){
		
		$_POST['fecha_creacion'] = 'now()';
		$_POST['estado'] = '1';
		$_POST['identificador_creacion'] = $this->identificador;
		$_POST['tipo_proceso'] = 'guardar';
		
		$resultado = $this->lNegocioProductos->validarGuardarProducto($_POST);
		
		if($resultado['validacion']){
			
			$_POST['codigo_producto'] = $resultado['codigo'];
			
			$idProducto = $this->lNegocioProductos->guardar($_POST);
			
			$arrayParametros[] = array(
				'id_producto' => $idProducto,
				'nombre_comun' =>  $_POST['nombre_comun']
			);
			
			$this->linea = $this->imprimirLineaRegistroProducto($arrayParametros);
		}
		
		echo json_encode(array(
			'estado' => $resultado['estado'],
			'mensaje' => $resultado['mensaje'],
			'linea' => $this->linea
		));
	}
	
	/**
	 * Método para actualizar el registro en la base de datos - producto
	 */
	public function actualizar(){
		
		$_POST['fecha_modificacion'] = 'now()';
		$_POST['identificador_modificacion'] = $this->identificador;
		$_POST['tipo_proceso'] = 'actualizar';
		
		$resultado = $this->lNegocioProductos->validarGuardarProducto($_POST);
		
		if($resultado['validacion']){
			
			$_POST['codigo_producto'] = $resultado['codigo'];
			$this->lNegocioProductos->guardar($_POST);

		}
		
		echo json_encode(array(
			'estado' => $resultado['estado'],
			'mensaje' => $resultado['mensaje']
		));
		
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: Productos
	 */
	public function editar(){
		$this->accion = "Detalle de producto";
		$arrayParametros = array('id_producto' => $_POST["id_producto"], 'estado' => 'Activo');
		$this->modeloProductos = $this->lNegocioProductos->buscar($arrayParametros['id_producto']);
		$this->modeloProductos->setRuta(($this->modeloProductos->getRuta() == '0' ? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$this->modeloProductos->getRuta().' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>'));
		$this->registroParametro = $this->imprimirLineaRegistroParametro($this->lNegocioParametros->buscarLista($arrayParametros));
		require APP . 'AdministracionProductos/vistas/formularioProductoParametrosVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - Productos
	 */
	public function borrar(){
		$this->lNegocioProductos->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - Productos
	 */
	public function tablaHtmlProductos($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_producto'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos\productos"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_producto'] . '</b></td>
<td>' . $fila['nombre_comun'] . '</td>
<td>' . $fila['nombre_cientifico'] . '</td>
<td>' . $fila['partida_arancelaria'] . '</td>
</tr>');
			}
		}
	}
}
