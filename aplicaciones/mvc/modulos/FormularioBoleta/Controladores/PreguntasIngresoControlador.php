<?php
 /**
 * Controlador PreguntasIngreso
 *
 * Este archivo controla la lógica del negocio del modelo:  PreguntasIngresoModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-08-14
 * @uses    PreguntasIngresoControlador
 * @package FormularioBoleta
 * @subpackage Controladores
 */
 namespace Agrodb\FormularioBoleta\Controladores;
 use Agrodb\FormularioBoleta\Modelos\PreguntasIngresoLogicaNegocio;
 use Agrodb\FormularioBoleta\Modelos\PreguntasIngresoModelo;
 
class PreguntasIngresoControlador extends BaseControlador 
{

		 private $lNegocioPreguntasIngreso = null;
		 private $modeloPreguntasIngreso = null;
		 private $accion = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioPreguntasIngreso = new PreguntasIngresoLogicaNegocio();
		 $this->modeloPreguntasIngreso = new PreguntasIngresoModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloPreguntasIngreso = $this->lNegocioPreguntasIngreso->buscarPreguntasIngreso();
		 $this->tablaHtmlPreguntasIngreso($modeloPreguntasIngreso);
		 require APP . 'FormularioBoleta/vistas/listaPreguntasIngresoVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo PreguntasIngreso"; 
		 require APP . 'FormularioBoleta/vistas/formularioPreguntasIngresoVista.php';
		}	/**
		* Método para registrar en la base de datos -PreguntasIngreso
		*/
		public function guardar()
		{
		  $this->lNegocioPreguntasIngreso->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: PreguntasIngreso
		*/
		public function editar()
		{
		 $this->accion = "Editar PreguntasIngreso"; 
		 $this->modeloPreguntasIngreso = $this->lNegocioPreguntasIngreso->buscar($_POST["id"]);
		 require APP . 'FormularioBoleta/vistas/formularioPreguntasIngresoVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - PreguntasIngreso
		*/
		public function borrar()
		{
		  $this->lNegocioPreguntasIngreso->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - PreguntasIngreso
		*/
		 public function tablaHtmlPreguntasIngreso($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_preguntas_ingreso'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'FormularioBoleta\preguntasingreso"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_preguntas_ingreso'] . '</b></td>
<td>'
		  . $fila['pregunta'] . '</td>
<td>' . $fila['estado']
		  . '</td>
<td>' . $fila['fecha_creacion'] . '</td>
</tr>');
		}
		}
	}

}
