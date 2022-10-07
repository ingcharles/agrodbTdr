<?php
 /**
 * Controlador CatastroPredioEquidosEspecie
 *
 * Este archivo controla la lógica del negocio del modelo:  CatastroPredioEquidosEspecieModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-02-16
 * @uses    CatastroPredioEquidosEspecieControlador
 * @package ProgramasControlOficial
 * @subpackage Controladores
 */
 namespace Agrodb\ProgramasControlOficial\Controladores;
 use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosEspecieLogicaNegocio;
 use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosEspecieModelo;
 
class CatastroPredioEquidosEspecieControlador extends BaseControlador 
{

		 private $lNegocioCatastroPredioEquidosEspecie = null;
		 private $modeloCatastroPredioEquidosEspecie = null;
		 private $accion = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioCatastroPredioEquidosEspecie = new CatastroPredioEquidosEspecieLogicaNegocio();
		 $this->modeloCatastroPredioEquidosEspecie = new CatastroPredioEquidosEspecieModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloCatastroPredioEquidosEspecie = $this->lNegocioCatastroPredioEquidosEspecie->buscarCatastroPredioEquidosEspecie();
		 $this->tablaHtmlCatastroPredioEquidosEspecie($modeloCatastroPredioEquidosEspecie);
		 require APP . 'ProgramasControlOficial/vistas/listaCatastroPredioEquidosEspecieVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo CatastroPredioEquidosEspecie"; 
		 require APP . 'ProgramasControlOficial/vistas/formularioCatastroPredioEquidosEspecieVista.php';
		}	/**
		* Método para registrar en la base de datos -CatastroPredioEquidosEspecie
		*/
		public function guardar()
		{
		  $this->lNegocioCatastroPredioEquidosEspecie->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: CatastroPredioEquidosEspecie
		*/
		public function editar()
		{
		 $this->accion = "Editar CatastroPredioEquidosEspecie"; 
		 $this->modeloCatastroPredioEquidosEspecie = $this->lNegocioCatastroPredioEquidosEspecie->buscar($_POST["id"]);
		 require APP . 'ProgramasControlOficial/vistas/formularioCatastroPredioEquidosEspecieVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - CatastroPredioEquidosEspecie
		*/
		public function borrar()
		{
		  $this->lNegocioCatastroPredioEquidosEspecie->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - CatastroPredioEquidosEspecie
		*/
		 public function tablaHtmlCatastroPredioEquidosEspecie($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_catastro_predio_equidos_especie'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'ProgramasControlOficial\catastropredioequidosespecie"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_catastro_predio_equidos_especie'] . '</b></td>
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
