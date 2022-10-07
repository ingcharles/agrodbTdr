<?php
 /**
 * Controlador CatastroPredioEquidosSanidad
 *
 * Este archivo controla la lógica del negocio del modelo:  CatastroPredioEquidosSanidadModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-02-16
 * @uses    CatastroPredioEquidosSanidadControlador
 * @package ProgramasControlOficial
 * @subpackage Controladores
 */
 namespace Agrodb\ProgramasControlOficial\Controladores;
 use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosSanidadLogicaNegocio;
 use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosSanidadModelo;
 
class CatastroPredioEquidosSanidadControlador extends BaseControlador 
{

		 private $lNegocioCatastroPredioEquidosSanidad = null;
		 private $modeloCatastroPredioEquidosSanidad = null;
		 private $accion = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioCatastroPredioEquidosSanidad = new CatastroPredioEquidosSanidadLogicaNegocio();
		 $this->modeloCatastroPredioEquidosSanidad = new CatastroPredioEquidosSanidadModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloCatastroPredioEquidosSanidad = $this->lNegocioCatastroPredioEquidosSanidad->buscarCatastroPredioEquidosSanidad();
		 $this->tablaHtmlCatastroPredioEquidosSanidad($modeloCatastroPredioEquidosSanidad);
		 require APP . 'ProgramasControlOficial/vistas/listaCatastroPredioEquidosSanidadVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo CatastroPredioEquidosSanidad"; 
		 require APP . 'ProgramasControlOficial/vistas/formularioCatastroPredioEquidosSanidadVista.php';
		}	/**
		* Método para registrar en la base de datos -CatastroPredioEquidosSanidad
		*/
		public function guardar()
		{
		  $this->lNegocioCatastroPredioEquidosSanidad->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: CatastroPredioEquidosSanidad
		*/
		public function editar()
		{
		 $this->accion = "Editar CatastroPredioEquidosSanidad"; 
		 $this->modeloCatastroPredioEquidosSanidad = $this->lNegocioCatastroPredioEquidosSanidad->buscar($_POST["id"]);
		 require APP . 'ProgramasControlOficial/vistas/formularioCatastroPredioEquidosSanidadVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - CatastroPredioEquidosSanidad
		*/
		public function borrar()
		{
		  $this->lNegocioCatastroPredioEquidosSanidad->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - CatastroPredioEquidosSanidad
		*/
		 public function tablaHtmlCatastroPredioEquidosSanidad($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_catastro_predio_equidos_sanidad'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'ProgramasControlOficial\catastropredioequidossanidad"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_catastro_predio_equidos_sanidad'] . '</b></td>
<td>'
		  . $fila['id_catastro_predio_equidos'] . '</td>
<td>' . $fila['identificador']
		  . '</td>
<td>' . $fila['fecha_creacion'] . '</td>
</tr>');
		}
		}
	}

}
