<?php
 /**
 * Controlador PresentacionesPlaguicidas
 *
 * Este archivo controla la lógica del negocio del modelo:  PresentacionesPlaguicidasModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-07-21
 * @uses    PresentacionesPlaguicidasControlador
 * @package Catalogos
 * @subpackage Controladores
 */
 namespace Agrodb\Catalogos\Controladores;
 use Agrodb\Catalogos\Modelos\PresentacionesPlaguicidasLogicaNegocio;
 use Agrodb\Catalogos\Modelos\PresentacionesPlaguicidasModelo;
 
class PresentacionesPlaguicidasControlador extends BaseControlador 
{

		 private $lNegocioPresentacionesPlaguicidas = null;
		 private $modeloPresentacionesPlaguicidas = null;
		 private $accion = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioPresentacionesPlaguicidas = new PresentacionesPlaguicidasLogicaNegocio();
		 $this->modeloPresentacionesPlaguicidas = new PresentacionesPlaguicidasModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloPresentacionesPlaguicidas = $this->lNegocioPresentacionesPlaguicidas->buscarPresentacionesPlaguicidas();
		 $this->tablaHtmlPresentacionesPlaguicidas($modeloPresentacionesPlaguicidas);
		 require APP . 'Catalogos/vistas/listaPresentacionesPlaguicidasVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo PresentacionesPlaguicidas"; 
		 require APP . 'Catalogos/vistas/formularioPresentacionesPlaguicidasVista.php';
		}	/**
		* Método para registrar en la base de datos -PresentacionesPlaguicidas
		*/
		public function guardar()
		{
		  $this->lNegocioPresentacionesPlaguicidas->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: PresentacionesPlaguicidas
		*/
		public function editar()
		{
		 $this->accion = "Editar PresentacionesPlaguicidas"; 
		 $this->modeloPresentacionesPlaguicidas = $this->lNegocioPresentacionesPlaguicidas->buscar($_POST["id"]);
		 require APP . 'Catalogos/vistas/formularioPresentacionesPlaguicidasVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - PresentacionesPlaguicidas
		*/
		public function borrar()
		{
		  $this->lNegocioPresentacionesPlaguicidas->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - PresentacionesPlaguicidas
		*/
		 public function tablaHtmlPresentacionesPlaguicidas($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_presentacion'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'Catalogos\presentacionesplaguicidas"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_presentacion'] . '</b></td>
<td>'
		  . $fila['fecha_creacion'] . '</td>
<td>' . $fila['id_codigo_comp_supl']
		  . '</td>
<td>' . $fila['presentacion'] . '</td>
</tr>');
		}
		}
	}

}
