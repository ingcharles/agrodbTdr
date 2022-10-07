<?php
 /**
 * Controlador DetalleSolicitudInspeccion
 *
 * Este archivo controla la lógica del negocio del modelo:  DetalleSolicitudInspeccionModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-09-18
 * @uses    DetalleSolicitudInspeccionControlador
 * @package InspeccionMusaceas
 * @subpackage Controladores
 */
 namespace Agrodb\InspeccionMusaceas\Controladores;
 use Agrodb\InspeccionMusaceas\Modelos\DetalleSolicitudInspeccionLogicaNegocio;
 use Agrodb\InspeccionMusaceas\Modelos\DetalleSolicitudInspeccionModelo;
 
class DetalleSolicitudInspeccionControlador extends BaseControlador 
{

		 private $lNegocioDetalleSolicitudInspeccion = null;
		 private $modeloDetalleSolicitudInspeccion = null;
		 private $accion = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioDetalleSolicitudInspeccion = new DetalleSolicitudInspeccionLogicaNegocio();
		 $this->modeloDetalleSolicitudInspeccion = new DetalleSolicitudInspeccionModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloDetalleSolicitudInspeccion = $this->lNegocioDetalleSolicitudInspeccion->buscarDetalleSolicitudInspeccion();
		 $this->tablaHtmlDetalleSolicitudInspeccion($modeloDetalleSolicitudInspeccion);
		 require APP . 'InspeccionMusaceas/vistas/listaDetalleSolicitudInspeccionVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo DetalleSolicitudInspeccion"; 
		 require APP . 'InspeccionMusaceas/vistas/formularioDetalleSolicitudInspeccionVista.php';
		}	/**
		* Método para registrar en la base de datos -DetalleSolicitudInspeccion
		*/
		public function guardar()
		{
		  $this->lNegocioDetalleSolicitudInspeccion->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: DetalleSolicitudInspeccion
		*/
		public function editar()
		{
		 $this->accion = "Editar DetalleSolicitudInspeccion"; 
		 $this->modeloDetalleSolicitudInspeccion = $this->lNegocioDetalleSolicitudInspeccion->buscar($_POST["id"]);
		 require APP . 'InspeccionMusaceas/vistas/formularioDetalleSolicitudInspeccionVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - DetalleSolicitudInspeccion
		*/
		public function borrar()
		{
		  $this->lNegocioDetalleSolicitudInspeccion->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - DetalleSolicitudInspeccion
		*/
		 public function tablaHtmlDetalleSolicitudInspeccion($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_detalle_solicitud_inspeccion'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'InspeccionMusaceas\detallesolicitudinspeccion"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_detalle_solicitud_inspeccion'] . '</b></td>
<td>'
		  . $fila['razon_social'] . '</td>
<td>' . $fila['area']
		  . '</td>
<td>' . $fila['num_cajas'] . '</td>
</tr>');
		}
		}
	}

}
