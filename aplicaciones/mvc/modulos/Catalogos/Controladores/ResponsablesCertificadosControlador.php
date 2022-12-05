<?php
 /**
 * Controlador ResponsablesCertificados
 *
 * Este archivo controla la lógica del negocio del modelo:  ResponsablesCertificadosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-09-23
 * @uses    ResponsablesCertificadosControlador
 * @package Catalogos
 * @subpackage Controladores
 */
 namespace Agrodb\Catalogos\Controladores;
 use Agrodb\Catalogos\Modelos\ResponsablesCertificadosLogicaNegocio;
 use Agrodb\Catalogos\Modelos\ResponsablesCertificadosModelo;
 
class ResponsablesCertificadosControlador extends BaseControlador 
{

		 private $lNegocioResponsablesCertificados = null;
		 private $modeloResponsablesCertificados = null;
		 private $accion = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioResponsablesCertificados = new ResponsablesCertificadosLogicaNegocio();
		 $this->modeloResponsablesCertificados = new ResponsablesCertificadosModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloResponsablesCertificados = $this->lNegocioResponsablesCertificados->buscarResponsablesCertificados();
		 $this->tablaHtmlResponsablesCertificados($modeloResponsablesCertificados);
		 require APP . 'Catalogos/vistas/listaResponsablesCertificadosVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo ResponsablesCertificados"; 
		 require APP . 'Catalogos/vistas/formularioResponsablesCertificadosVista.php';
		}	/**
		* Método para registrar en la base de datos -ResponsablesCertificados
		*/
		public function guardar()
		{
		  $this->lNegocioResponsablesCertificados->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: ResponsablesCertificados
		*/
		public function editar()
		{
		 $this->accion = "Editar ResponsablesCertificados"; 
		 $this->modeloResponsablesCertificados = $this->lNegocioResponsablesCertificados->buscar($_POST["id"]);
		 require APP . 'Catalogos/vistas/formularioResponsablesCertificadosVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - ResponsablesCertificados
		*/
		public function borrar()
		{
		  $this->lNegocioResponsablesCertificados->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - ResponsablesCertificados
		*/
		 public function tablaHtmlResponsablesCertificados($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_responsable_certificado'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'Catalogos\responsablescertificados"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_responsable_certificado'] . '</b></td>
<td>'
		  . $fila['identificador'] . '</td>
<td>' . $fila['cargo']
		  . '</td>
<td>' . $fila['nombre'] . '</td>
</tr>');
		}
		}
	}

}
