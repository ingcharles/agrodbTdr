<?php
 /**
 * Controlador TipoDocumento
 *
 * Este archivo controla la lógica del negocio del modelo:  TipoDocumentoModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-03-17
 * @uses    TipoDocumentoControlador
 * @package ProcesosAdministrativosJuridico
 * @subpackage Controladores
 */
 namespace Agrodb\ProcesosAdministrativosJuridico\Controladores;
 use Agrodb\ProcesosAdministrativosJuridico\Modelos\TipoDocumentoLogicaNegocio;
 use Agrodb\ProcesosAdministrativosJuridico\Modelos\TipoDocumentoModelo;
 
class TipoDocumentoControlador extends BaseControlador 
{

		 private $lNegocioTipoDocumento = null;
		 private $modeloTipoDocumento = null;
		 private $accion = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioTipoDocumento = new TipoDocumentoLogicaNegocio();
		 $this->modeloTipoDocumento = new TipoDocumentoModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloTipoDocumento = $this->lNegocioTipoDocumento->buscarTipoDocumento();
		 $this->tablaHtmlTipoDocumento($modeloTipoDocumento);
		 require APP . 'ProcesosAdministrativosJuridico/vistas/listaTipoDocumentoVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo TipoDocumento"; 
		 require APP . 'ProcesosAdministrativosJuridico/vistas/formularioTipoDocumentoVista.php';
		}	/**
		* Método para registrar en la base de datos -TipoDocumento
		*/
		public function guardar()
		{
		  $this->lNegocioTipoDocumento->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: TipoDocumento
		*/
		public function editar()
		{
		 $this->accion = "Editar TipoDocumento"; 
		 $this->modeloTipoDocumento = $this->lNegocioTipoDocumento->buscar($_POST["id"]);
		 require APP . 'ProcesosAdministrativosJuridico/vistas/formularioTipoDocumentoVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - TipoDocumento
		*/
		public function borrar()
		{
		  $this->lNegocioTipoDocumento->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - TipoDocumento
		*/
		 public function tablaHtmlTipoDocumento($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_tipo_documento'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'ProcesosAdministrativosJuridico\tipodocumento"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_tipo_documento'] . '</b></td>
<td>'
		  . $fila['id_proceso_administrativo'] . '</td>
<td>' . $fila['fecha_creacion']
		  . '</td>
<td>' . $fila['estado'] . '</td>
</tr>');
		}
		}
	}

}
