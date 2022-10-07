<?php
/**
 * Controlador Documentos
 *
 * Este archivo controla la lógica del negocio del modelo: DocumentosModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2022-01-14
 * @uses DocumentosControlador
 * @package FirmaDocumentos
 * @subpackage Controladores
 */
namespace Agrodb\FirmaDocumentos\Controladores;

use Agrodb\Core\Constantes;
use Agrodb\FirmaDocumentos\Modelos\DocumentosLogicaNegocio;
use Agrodb\FirmaDocumentos\Modelos\DocumentosModelo;

class DocumentosControlador extends BaseControlador{

	private $lNegocioDocumentos = null;

	private $modeloDocumentos = null;

	private $accion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioDocumentos = new DocumentosLogicaNegocio();
		$this->modeloDocumentos = new DocumentosModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloDocumentos = $this->lNegocioDocumentos->buscarDocumentos();
		$this->tablaHtmlDocumentos($modeloDocumentos);
		require APP . 'FirmaDocumentos/vistas/listaDocumentosVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo Documentos";
		require APP . 'FirmaDocumentos/vistas/formularioDocumentosVista.php';
	}

	/**
	 * Método para registrar en la base de datos -Documentos
	 */
	public function guardar(){
		
		$resultadoProceso = $this->lNegocioDocumentos->guardar($_POST);
		
		echo json_encode(array(
			'estado' => $resultadoProceso['estado'],
			'mensaje' => $resultadoProceso['mensaje']));
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: Documentos
	 */
	public function editar(){
		$this->accion = "Editar Documentos";
		$this->modeloDocumentos = $this->lNegocioDocumentos->buscar($_POST["id"]);
		require APP . 'FirmaDocumentos/vistas/formularioDocumentosVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - Documentos
	 */
	public function borrar(){
		$this->lNegocioDocumentos->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - Documentos
	 */
	public function tablaHtmlDocumentos($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_documento'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'FirmaDocumentos\documentos"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_documento'] . '</b></td>
<td>' . $fila['identificador'] . '</td>
<td>' . $fila['nombre_firmante'] . '</td>
<td>' . $fila['localizacion'] . '</td>
</tr>');
			}
		}
	}
	
	public function paFirmarDocumentos(){
		
		$fecha = date("Y-m-d h:m:s");
		echo Constantes::IN_MSG .'<b>PROCESO AUTOMÁTICO DE FIRMA ELECTRONICA DE DOCUMENTOS GUIA '.$fecha.'</b>\n';
		
		$this->lNegocioDocumentos->buscarDocumentosPorFirmar();
		
		$fecha = date("Y-m-d h:m:s");
		echo Constantes::IN_MSG .'<b>FIN PROCESO AUTOMÁTICO DE FIRMA ELECTRONICA DE DOCUMENTOS GUIA '.$fecha.'</b>';
	}
}
