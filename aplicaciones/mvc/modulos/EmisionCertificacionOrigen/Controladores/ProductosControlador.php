<?php
 /**
 * Controlador Productos
 *
 * Este archivo controla la lógica del negocio del modelo:  ProductosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-09-18
 * @uses    ProductosControlador
 * @package EmisionCertificacionOrigen
 * @subpackage Controladores
 */
 namespace Agrodb\EmisionCertificacionOrigen\Controladores;
 use Agrodb\EmisionCertificacionOrigen\Modelos\ProductosLogicaNegocio;
 use Agrodb\EmisionCertificacionOrigen\Modelos\ProductosModelo;
 
class ProductosControlador extends BaseControlador 
{

		 private $lNegocioProductos = null;
		 private $modeloProductos = null;
		 private $accion = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioProductos = new ProductosLogicaNegocio();
		 $this->modeloProductos = new ProductosModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloProductos = $this->lNegocioProductos->buscarProductos();
		 $this->tablaHtmlProductos($modeloProductos);
		 require APP . 'EmisionCertificacionOrigen/vistas/listaProductosVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo Productos"; 
		 require APP . 'EmisionCertificacionOrigen/vistas/formularioProductosVista.php';
		}	/**
		* Método para registrar en la base de datos -Productos
		*/
		public function guardar()
		{
		  $this->lNegocioProductos->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: Productos
		*/
		public function editar()
		{
		 $this->accion = "Editar Productos"; 
		 $this->modeloProductos = $this->lNegocioProductos->buscar($_POST["id"]);
		 require APP . 'EmisionCertificacionOrigen/vistas/formularioProductosVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - Productos
		*/
		public function borrar()
		{
		  $this->lNegocioProductos->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - Productos
		*/
		 public function tablaHtmlProductos($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_productos'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'EmisionCertificacionOrigen\productos"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_productos'] . '</b></td>
<td>'
		  . $fila['id_registro_produccion'] . '</td>
<td>' . $fila['num_canales_obtenidos']
		  . '</td>
<td>' . $fila['num_canales_obtenidos_uso'] . '</td>
</tr>');
		}
		}
	}

}
