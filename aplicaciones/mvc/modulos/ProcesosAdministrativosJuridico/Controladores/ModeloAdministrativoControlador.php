<?php
 /**
 * Controlador ModeloAdministrativo
 *
 * Este archivo controla la lógica del negocio del modelo:  ModeloAdministrativoModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-03-17
 * @uses    ModeloAdministrativoControlador
 * @package ProcesosAdministrativosJuridico
 * @subpackage Controladores
 */
 namespace Agrodb\ProcesosAdministrativosJuridico\Controladores;
 use Agrodb\ProcesosAdministrativosJuridico\Modelos\ModeloAdministrativoLogicaNegocio;
 use Agrodb\ProcesosAdministrativosJuridico\Modelos\ModeloAdministrativoModelo;
 
class ModeloAdministrativoControlador extends BaseControlador 
{

		 private $lNegocioModeloAdministrativo = null;
		 private $modeloModeloAdministrativo = null;
		 private $accion = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioModeloAdministrativo = new ModeloAdministrativoLogicaNegocio();
		 $this->modeloModeloAdministrativo = new ModeloAdministrativoModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloModeloAdministrativo = $this->lNegocioModeloAdministrativo->buscarModeloAdministrativo();
		 $this->tablaHtmlModeloAdministrativo($modeloModeloAdministrativo);
		 require APP . 'ProcesosAdministrativosJuridico/vistas/listaModeloAdministrativoVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo ModeloAdministrativo"; 
		 require APP . 'ProcesosAdministrativosJuridico/vistas/formularioModeloAdministrativoVista.php';
		}	/**
		* Método para registrar en la base de datos -ModeloAdministrativo
		*/
		public function guardar()
		{
		  $this->lNegocioModeloAdministrativo->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: ModeloAdministrativo
		*/
		public function editar()
		{
		 $this->accion = "Editar ModeloAdministrativo"; 
		 $this->modeloModeloAdministrativo = $this->lNegocioModeloAdministrativo->buscar($_POST["id"]);
		 require APP . 'ProcesosAdministrativosJuridico/vistas/formularioModeloAdministrativoVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - ModeloAdministrativo
		*/
		public function borrar()
		{
		  $this->lNegocioModeloAdministrativo->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - ModeloAdministrativo
		*/
		 public function tablaHtmlModeloAdministrativo($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_modelo_administrativo'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'ProcesosAdministrativosJuridico\modeloadministrativo"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_modelo_administrativo'] . '</b></td>
<td>'
		  . $fila['ruta_modelo'] . '</td>
<td>' . $fila['nombre_modelo']
		  . '</td>
<td>' . $fila['estado'] . '</td>
</tr>');
		}
		}
	}

}
