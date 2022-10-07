<?php
 /**
 * Controlador UsuariosPerfiles
 *
 * Este archivo controla la lógica del negocio del modelo:  UsuariosPerfilesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-09-07
 * @uses    UsuariosPerfilesControlador
 * @package AplicacionMovilInternos
 * @subpackage Controladores
 */
 namespace Agrodb\AplicacionMovilInternos\Controladores;
 use Agrodb\AplicacionMovilInternos\Modelos\UsuariosPerfilesLogicaNegocio;
 use Agrodb\AplicacionMovilInternos\Modelos\UsuariosPerfilesModelo;
 
class UsuariosPerfilesControlador extends BaseControlador 
{

		 private $lNegocioUsuariosPerfiles = null;
		 private $modeloUsuariosPerfiles = null;
		 private $accion = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioUsuariosPerfiles = new UsuariosPerfilesLogicaNegocio();
		 $this->modeloUsuariosPerfiles = new UsuariosPerfilesModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloUsuariosPerfiles = $this->lNegocioUsuariosPerfiles->buscarUsuariosPerfiles();
		 $this->tablaHtmlUsuariosPerfiles($modeloUsuariosPerfiles);
		 require APP . 'AplicacionMovilInternos/vistas/listaUsuariosPerfilesVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo UsuariosPerfiles"; 
		 require APP . 'AplicacionMovilInternos/vistas/formularioUsuariosPerfilesVista.php';
		}	/**
		* Método para registrar en la base de datos -UsuariosPerfiles
		*/
		public function guardar()
		{
		  $this->lNegocioUsuariosPerfiles->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: UsuariosPerfiles
		*/
		public function editar()
		{
		 $this->accion = "Editar UsuariosPerfiles"; 
		 $this->modeloUsuariosPerfiles = $this->lNegocioUsuariosPerfiles->buscar($_POST["id"]);
		 require APP . 'AplicacionMovilInternos/vistas/formularioUsuariosPerfilesVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - UsuariosPerfiles
		*/
		public function borrar()
		{
		  $this->lNegocioUsuariosPerfiles->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - UsuariosPerfiles
		*/
		 public function tablaHtmlUsuariosPerfiles($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['identificador'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'AplicacionMovilInternos\usuariosperfiles"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['identificador'] . '</b></td>
<td>'
		  . $fila['id_perfil'] . '</td>
<td>' . $fila['identificador']
		  . '</td>
<td>' . $fila['identificador'] . '</td>
</tr>');
		}
		}
	}

}
