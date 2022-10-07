<?php
 /**
 * Controlador SecuenciaRevision
 *
 * Este archivo controla la lógica del negocio del modelo:  SecuenciaRevisionModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-07-21
 * @uses    SecuenciaRevisionControlador
 * @package DossierPecuario
 * @subpackage Controladores
 */
 namespace Agrodb\DossierPecuario\Controladores;
 use Agrodb\DossierPecuario\Modelos\SecuenciaRevisionLogicaNegocio;
 use Agrodb\DossierPecuario\Modelos\SecuenciaRevisionModelo;
 
class SecuenciaRevisionControlador extends BaseControlador 
{

		 private $lNegocioSecuenciaRevision = null;
		 private $modeloSecuenciaRevision = null;
		 private $accion = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioSecuenciaRevision = new SecuenciaRevisionLogicaNegocio();
		 $this->modeloSecuenciaRevision = new SecuenciaRevisionModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloSecuenciaRevision = $this->lNegocioSecuenciaRevision->buscarSecuenciaRevision();
		 $this->tablaHtmlSecuenciaRevision($modeloSecuenciaRevision);
		 require APP . 'DossierPecuario/vistas/listaSecuenciaRevisionVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo SecuenciaRevision"; 
		 require APP . 'DossierPecuario/vistas/formularioSecuenciaRevisionVista.php';
		}	/**
		* Método para registrar en la base de datos -SecuenciaRevision
		*/
		public function guardar()
		{
		  $this->lNegocioSecuenciaRevision->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: SecuenciaRevision
		*/
		public function editar()
		{
		 $this->accion = "Editar SecuenciaRevision"; 
		 $this->modeloSecuenciaRevision = $this->lNegocioSecuenciaRevision->buscar($_POST["id"]);
		 require APP . 'DossierPecuario/vistas/formularioSecuenciaRevisionVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - SecuenciaRevision
		*/
		public function borrar()
		{
		  $this->lNegocioSecuenciaRevision->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - SecuenciaRevision
		*/
		 public function tablaHtmlSecuenciaRevision($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_secuencia_revision'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'DossierPecuario\secuenciarevision"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_secuencia_revision'] . '</b></td>
<td>'
		  . $fila['id_solicitud'] . '</td>
<td>' . $fila['fecha_creacion']
		  . '</td>
<td>' . $fila['orden'] . '</td>
</tr>');
		}
		}
	}

}
