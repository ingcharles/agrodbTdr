<?php
 /**
 * Controlador CronogramaVacaciones
 *
 * Este archivo controla la lógica del negocio del modelo:  CronogramaVacacionesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-10-22
 * @uses    CronogramaVacacionesControlador
 * @package VacacionesPermisos
 * @subpackage Controladores
 */
 namespace Agrodb\VacacionesPermisos\Controladores;
 use Agrodb\VacacionesPermisos\Modelos\CronogramaVacacionesLogicaNegocio;
 use Agrodb\VacacionesPermisos\Modelos\CronogramaVacacionesModelo;
 
class CronogramaVacacionesControlador extends BaseControlador 
{

		 private $lNegocioCronogramaVacaciones = null;
		 private $modeloCronogramaVacaciones = null;
		 private $accion = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioCronogramaVacaciones = new CronogramaVacacionesLogicaNegocio();
		 $this->modeloCronogramaVacaciones = new CronogramaVacacionesModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloCronogramaVacaciones = $this->lNegocioCronogramaVacaciones->buscarCronogramaVacaciones();
		 $this->tablaHtmlCronogramaVacaciones($modeloCronogramaVacaciones);
		 require APP . 'VacacionesPermisos/vistas/listaCronogramaVacacionesVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo CronogramaVacaciones"; 
		 require APP . 'VacacionesPermisos/vistas/formularioCronogramaVacacionesVista.php';
		}	/**
		* Método para registrar en la base de datos -CronogramaVacaciones
		*/
		public function guardar()
		{
		  $this->lNegocioCronogramaVacaciones->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: CronogramaVacaciones
		*/
		public function editar()
		{
		 $this->accion = "Editar CronogramaVacaciones"; 
		 $this->modeloCronogramaVacaciones = $this->lNegocioCronogramaVacaciones->buscar($_POST["id"]);
		 require APP . 'VacacionesPermisos/vistas/formularioCronogramaVacacionesVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - CronogramaVacaciones
		*/
		public function borrar()
		{
		  $this->lNegocioCronogramaVacaciones->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - CronogramaVacaciones
		*/
		 public function tablaHtmlCronogramaVacaciones($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_cronograma_vacacion'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'VacacionesPermisos\cronogramavacaciones"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_cronograma_vacacion'] . '</b></td>
<td>'
		  . $fila['identificador'] . '</td>
<td>' . $fila['fecha_ingreso_institucion']
		  . '</td>
<td>' . $fila['id_puesto'] . '</td>
</tr>');
		}
		}
	}

}
