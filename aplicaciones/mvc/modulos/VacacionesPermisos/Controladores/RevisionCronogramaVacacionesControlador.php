<?php
 /**
 * Controlador RevisionCronogramaVacaciones
 *
 * Este archivo controla la lógica del negocio del modelo:  RevisionCronogramaVacacionesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-10-22
 * @uses    RevisionCronogramaVacacionesControlador
 * @package VacacionesPermisos
 * @subpackage Controladores
 */
 namespace Agrodb\VacacionesPermisos\Controladores;
 use Agrodb\VacacionesPermisos\Modelos\RevisionCronogramaVacacionesLogicaNegocio;
 use Agrodb\VacacionesPermisos\Modelos\RevisionCronogramaVacacionesModelo;
 
class RevisionCronogramaVacacionesControlador extends BaseControlador 
{

		 private $lNegocioRevisionCronogramaVacaciones = null;
		 private $modeloRevisionCronogramaVacaciones = null;
		 private $accion = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioRevisionCronogramaVacaciones = new RevisionCronogramaVacacionesLogicaNegocio();
		 $this->modeloRevisionCronogramaVacaciones = new RevisionCronogramaVacacionesModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloRevisionCronogramaVacaciones = $this->lNegocioRevisionCronogramaVacaciones->buscarRevisionCronogramaVacaciones();
		 $this->tablaHtmlRevisionCronogramaVacaciones($modeloRevisionCronogramaVacaciones);
		 require APP . 'VacacionesPermisos/vistas/listaRevisionCronogramaVacacionesVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo RevisionCronogramaVacaciones"; 
		 require APP . 'VacacionesPermisos/vistas/formularioRevisionCronogramaVacacionesVista.php';
		}	/**
		* Método para registrar en la base de datos -RevisionCronogramaVacaciones
		*/
		public function guardar()
		{
		  $this->lNegocioRevisionCronogramaVacaciones->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: RevisionCronogramaVacaciones
		*/
		public function editar()
		{
		 $this->accion = "Editar RevisionCronogramaVacaciones"; 
		 $this->modeloRevisionCronogramaVacaciones = $this->lNegocioRevisionCronogramaVacaciones->buscar($_POST["id"]);
		 require APP . 'VacacionesPermisos/vistas/formularioRevisionCronogramaVacacionesVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - RevisionCronogramaVacaciones
		*/
		public function borrar()
		{
		  $this->lNegocioRevisionCronogramaVacaciones->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - RevisionCronogramaVacaciones
		*/
		 public function tablaHtmlRevisionCronogramaVacaciones($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_revision_cronograma_vacacion'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'VacacionesPermisos\revisioncronogramavacaciones"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_revision_cronograma_vacacion'] . '</b></td>
<td>'
		  . $fila['id_cronograma_vacacion'] . '</td>
<td>' . $fila['identicador_revisor']
		  . '</td>
<td>' . $fila['id_area_revisor'] . '</td>
</tr>');
		}
		}
	}

}
