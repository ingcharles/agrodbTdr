<?php
 /**
 * Controlador RespuestasIngreso
 *
 * Este archivo controla la lógica del negocio del modelo:  RespuestasIngresoModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-08-14
 * @uses    RespuestasIngresoControlador
 * @package FormularioBoleta
 * @subpackage Controladores
 */
 namespace Agrodb\FormularioBoleta\Controladores;
 use Agrodb\FormularioBoleta\Modelos\RespuestasIngresoLogicaNegocio;
 use Agrodb\FormularioBoleta\Modelos\RespuestasIngresoModelo;
 
class RespuestasIngresoControlador extends BaseControlador 
{

		 private $lNegocioRespuestasIngreso = null;
		 private $modeloRespuestasIngreso = null;
		 private $accion = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioRespuestasIngreso = new RespuestasIngresoLogicaNegocio();
		 $this->modeloRespuestasIngreso = new RespuestasIngresoModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloRespuestasIngreso = $this->lNegocioRespuestasIngreso->buscarRespuestasIngreso();
		 $this->tablaHtmlRespuestasIngreso($modeloRespuestasIngreso);
		 require APP . 'FormularioBoleta/vistas/listaRespuestasIngresoVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo RespuestasIngreso"; 
		 require APP . 'FormularioBoleta/vistas/formularioRespuestasIngresoVista.php';
		}	/**
		* Método para registrar en la base de datos -RespuestasIngreso
		*/
		public function guardar()
		{
		  $this->lNegocioRespuestasIngreso->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: RespuestasIngreso
		*/
		public function editar()
		{
		 $this->accion = "Editar RespuestasIngreso"; 
		 $this->modeloRespuestasIngreso = $this->lNegocioRespuestasIngreso->buscar($_POST["id"]);
		 require APP . 'FormularioBoleta/vistas/formularioRespuestasIngresoVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - RespuestasIngreso
		*/
		public function borrar()
		{
		  $this->lNegocioRespuestasIngreso->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - RespuestasIngreso
		*/
		 public function tablaHtmlRespuestasIngreso($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_respuestas_ingreso'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'FormularioBoleta\respuestasingreso"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_respuestas_ingreso'] . '</b></td>
<td>'
		  . $fila['id_preguntas_ingreso'] . '</td>
<td>' . $fila['id_datos_ingreso']
		  . '</td>
<td>' . $fila['respuesta'] . '</td>
</tr>');
		}
		}
	}

}
