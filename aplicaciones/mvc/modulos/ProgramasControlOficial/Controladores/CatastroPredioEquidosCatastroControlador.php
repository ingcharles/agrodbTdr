<?php
 /**
 * Controlador CatastroPredioEquidosCatastro
 *
 * Este archivo controla la lógica del negocio del modelo:  CatastroPredioEquidosCatastroModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-02-16
 * @uses    CatastroPredioEquidosCatastroControlador
 * @package ProgramasControlOficial
 * @subpackage Controladores
 */
 namespace Agrodb\ProgramasControlOficial\Controladores;
 use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosCatastroLogicaNegocio;
 use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosCatastroModelo;
 
class CatastroPredioEquidosCatastroControlador extends BaseControlador 
{

		 private $lNegocioCatastroPredioEquidosCatastro = null;
		 private $modeloCatastroPredioEquidosCatastro = null;
		 private $accion = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioCatastroPredioEquidosCatastro = new CatastroPredioEquidosCatastroLogicaNegocio();
		 $this->modeloCatastroPredioEquidosCatastro = new CatastroPredioEquidosCatastroModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloCatastroPredioEquidosCatastro = $this->lNegocioCatastroPredioEquidosCatastro->buscarCatastroPredioEquidosCatastro();
		 $this->tablaHtmlCatastroPredioEquidosCatastro($modeloCatastroPredioEquidosCatastro);
		 require APP . 'ProgramasControlOficial/vistas/listaCatastroPredioEquidosCatastroVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo CatastroPredioEquidosCatastro"; 
		 require APP . 'ProgramasControlOficial/vistas/formularioCatastroPredioEquidosCatastroVista.php';
		}	/**
		* Método para registrar en la base de datos -CatastroPredioEquidosCatastro
		*/
		public function guardar()
		{
		  $this->lNegocioCatastroPredioEquidosCatastro->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: CatastroPredioEquidosCatastro
		*/
		public function editar()
		{
		 $this->accion = "Editar CatastroPredioEquidosCatastro"; 
		 $this->modeloCatastroPredioEquidosCatastro = $this->lNegocioCatastroPredioEquidosCatastro->buscar($_POST["id"]);
		 require APP . 'ProgramasControlOficial/vistas/formularioCatastroPredioEquidosCatastroVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - CatastroPredioEquidosCatastro
		*/
		public function borrar()
		{
		  $this->lNegocioCatastroPredioEquidosCatastro->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - CatastroPredioEquidosCatastro
		*/
		 public function tablaHtmlCatastroPredioEquidosCatastro($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_catastro_predio_equidos_catastro'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'ProgramasControlOficial\catastropredioequidoscatastro"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_catastro_predio_equidos_catastro'] . '</b></td>
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
