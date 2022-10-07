<?php
 /**
 * Controlador CatastroPredioEquidosTipoActividad
 *
 * Este archivo controla la lógica del negocio del modelo:  CatastroPredioEquidosTipoActividadModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-02-16
 * @uses    CatastroPredioEquidosTipoActividadControlador
 * @package ProgramasControlOficial
 * @subpackage Controladores
 */
 namespace Agrodb\ProgramasControlOficial\Controladores;
 use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosTipoActividadLogicaNegocio;
 use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosTipoActividadModelo;
 
class CatastroPredioEquidosTipoActividadControlador extends BaseControlador 
{

		 private $lNegocioCatastroPredioEquidosTipoActividad = null;
		 private $modeloCatastroPredioEquidosTipoActividad = null;
		 private $accion = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioCatastroPredioEquidosTipoActividad = new CatastroPredioEquidosTipoActividadLogicaNegocio();
		 $this->modeloCatastroPredioEquidosTipoActividad = new CatastroPredioEquidosTipoActividadModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloCatastroPredioEquidosTipoActividad = $this->lNegocioCatastroPredioEquidosTipoActividad->buscarCatastroPredioEquidosTipoActividad();
		 $this->tablaHtmlCatastroPredioEquidosTipoActividad($modeloCatastroPredioEquidosTipoActividad);
		 require APP . 'ProgramasControlOficial/vistas/listaCatastroPredioEquidosTipoActividadVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo CatastroPredioEquidosTipoActividad"; 
		 require APP . 'ProgramasControlOficial/vistas/formularioCatastroPredioEquidosTipoActividadVista.php';
		}	/**
		* Método para registrar en la base de datos -CatastroPredioEquidosTipoActividad
		*/
		public function guardar()
		{
		  $this->lNegocioCatastroPredioEquidosTipoActividad->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: CatastroPredioEquidosTipoActividad
		*/
		public function editar()
		{
		 $this->accion = "Editar CatastroPredioEquidosTipoActividad"; 
		 $this->modeloCatastroPredioEquidosTipoActividad = $this->lNegocioCatastroPredioEquidosTipoActividad->buscar($_POST["id"]);
		 require APP . 'ProgramasControlOficial/vistas/formularioCatastroPredioEquidosTipoActividadVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - CatastroPredioEquidosTipoActividad
		*/
		public function borrar()
		{
		  $this->lNegocioCatastroPredioEquidosTipoActividad->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - CatastroPredioEquidosTipoActividad
		*/
		 public function tablaHtmlCatastroPredioEquidosTipoActividad($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_catastro_predio_equidos_tipo_actividad'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'ProgramasControlOficial\catastropredioequidostipoactividad"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_catastro_predio_equidos_tipo_actividad'] . '</b></td>
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
