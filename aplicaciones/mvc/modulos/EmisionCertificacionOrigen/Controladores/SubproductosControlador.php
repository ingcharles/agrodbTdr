<?php
 /**
 * Controlador Subproductos
 *
 * Este archivo controla la lógica del negocio del modelo:  SubproductosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-09-18
 * @uses    SubproductosControlador
 * @package EmisionCertificacionOrigen
 * @subpackage Controladores
 */
 namespace Agrodb\EmisionCertificacionOrigen\Controladores;
 use Agrodb\EmisionCertificacionOrigen\Modelos\SubproductosLogicaNegocio;
 use Agrodb\EmisionCertificacionOrigen\Modelos\SubproductosModelo;
 
class SubproductosControlador extends BaseControlador 
{

		 private $lNegocioSubproductos = null;
		 private $modeloSubproductos = null;
		 private $accion = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioSubproductos = new SubproductosLogicaNegocio();
		 $this->modeloSubproductos = new SubproductosModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloSubproductos = $this->lNegocioSubproductos->buscarSubproductos();
		 $this->tablaHtmlSubproductos($modeloSubproductos);
		 require APP . 'EmisionCertificacionOrigen/vistas/listaSubproductosVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo Subproductos"; 
		 require APP . 'EmisionCertificacionOrigen/vistas/formularioSubproductosVista.php';
		}	/**
		* Método para registrar en la base de datos -Subproductos
		*/
		public function guardar()
		{
		  $this->lNegocioSubproductos->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: Subproductos
		*/
		public function editar()
		{
		 $this->accion = "Editar Subproductos"; 
		 $this->modeloSubproductos = $this->lNegocioSubproductos->buscar($_POST["id"]);
		 require APP . 'EmisionCertificacionOrigen/vistas/formularioSubproductosVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - Subproductos
		*/
		public function borrar()
		{
		  $this->lNegocioSubproductos->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - Subproductos
		*/
		 public function tablaHtmlSubproductos($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_subproductos'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'EmisionCertificacionOrigen\subproductos"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_subproductos'] . '</b></td>
<td>'
		  . $fila['id_productos'] . '</td>
<td>' . $fila['subproducto']
		  . '</td>
<td>' . $fila['cantidad'] . '</td>
</tr>');
		}
		}
	}

}
