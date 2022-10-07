<?php
 /**
 * Controlador CatastroPredioEquidosHistorialPatologias
 *
 * Este archivo controla la lógica del negocio del modelo:  CatastroPredioEquidosHistorialPatologiasModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-02-16
 * @uses    CatastroPredioEquidosHistorialPatologiasControlador
 * @package ProgramasControlOficial
 * @subpackage Controladores
 */
 namespace Agrodb\ProgramasControlOficial\Controladores;
 use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosHistorialPatologiasLogicaNegocio;
 use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosHistorialPatologiasModelo;
 
class CatastroPredioEquidosHistorialPatologiasControlador extends BaseControlador 
{

		 private $lNegocioCatastroPredioEquidosHistorialPatologias = null;
		 private $modeloCatastroPredioEquidosHistorialPatologias = null;
		 private $accion = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioCatastroPredioEquidosHistorialPatologias = new CatastroPredioEquidosHistorialPatologiasLogicaNegocio();
		 $this->modeloCatastroPredioEquidosHistorialPatologias = new CatastroPredioEquidosHistorialPatologiasModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloCatastroPredioEquidosHistorialPatologias = $this->lNegocioCatastroPredioEquidosHistorialPatologias->buscarCatastroPredioEquidosHistorialPatologias();
		 $this->tablaHtmlCatastroPredioEquidosHistorialPatologias($modeloCatastroPredioEquidosHistorialPatologias);
		 require APP . 'ProgramasControlOficial/vistas/listaCatastroPredioEquidosHistorialPatologiasVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo CatastroPredioEquidosHistorialPatologias"; 
		 require APP . 'ProgramasControlOficial/vistas/formularioCatastroPredioEquidosHistorialPatologiasVista.php';
		}	/**
		* Método para registrar en la base de datos -CatastroPredioEquidosHistorialPatologias
		*/
		public function guardar()
		{
		  $this->lNegocioCatastroPredioEquidosHistorialPatologias->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: CatastroPredioEquidosHistorialPatologias
		*/
		public function editar()
		{
		 $this->accion = "Editar CatastroPredioEquidosHistorialPatologias"; 
		 $this->modeloCatastroPredioEquidosHistorialPatologias = $this->lNegocioCatastroPredioEquidosHistorialPatologias->buscar($_POST["id"]);
		 require APP . 'ProgramasControlOficial/vistas/formularioCatastroPredioEquidosHistorialPatologiasVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - CatastroPredioEquidosHistorialPatologias
		*/
		public function borrar()
		{
		  $this->lNegocioCatastroPredioEquidosHistorialPatologias->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - CatastroPredioEquidosHistorialPatologias
		*/
		 public function tablaHtmlCatastroPredioEquidosHistorialPatologias($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_catastro_predio_equidos_historial_patologias'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'ProgramasControlOficial\catastropredioequidoshistorialpatologias"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_catastro_predio_equidos_historial_patologias'] . '</b></td>
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
