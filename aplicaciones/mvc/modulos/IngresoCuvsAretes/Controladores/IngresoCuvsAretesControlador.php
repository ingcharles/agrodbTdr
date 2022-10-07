<?php
 /**
 * Controlador CertificadosVacunacion
 *
 * Este archivo controla la lógica del negocio del modelo:  CertificadosVacunacionModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-09-08
 * @uses    CertificadosVacunacionControlador
 * @package IngresoCuvsAretes
 * @subpackage Controladores
 */
 namespace Agrodb\IngresoCuvsAretes\Controladores;
 use Agrodb\Catalogos\Modelos\CertificadosVacunacionLogicaNegocio;
 use Agrodb\Catalogos\Modelos\CertificadosVacunacionModelo;
 use Agrodb\Catalogos\Modelos\SerieAretesLogicaNegocio;
 use Agrodb\Catalogos\Modelos\SerieAretesModelo;
 
 class IngresoCuvsAretesControlador extends BaseControlador 
{

		 private $lNegocioCertificadosVacunacion = null;
		 private $modeloCertificadosVacunacion = null;
		 private $lNegocioSeriesAretes = null;
		 private $modeloSeriesAretes = null;
		 private $accion = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioCertificadosVacunacion = new CertificadosVacunacionLogicaNegocio();
		 $this->modeloCertificadosVacunacion = new CertificadosVacunacionModelo();
		 $this->lNegocioSeriesAretes = new SerieAretesLogicaNegocio();
		 $this->modeloSeriesAretes = new SerieAretesModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 //$modeloCertificadosVacunacion = $this->lNegocioCertificadosVacunacion->buscarCertificadosVacunacion();
		 //$this->tablaHtmlCertificadosVacunacion($modeloCertificadosVacunacion);
		 //require APP . 'IngresoCuvsAretes/vistas/listaCertificadosVacunacionVista.php';
		    require APP . 'IngresoCuvsAretes/vistas/listaOpcionesInsertarCuvsAretes.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 if($_POST['id'] == 1){		 
    		 $this->accion = "Ingreso Certificados de Vacunacion"; 
    		 require APP . 'IngresoCuvsAretes/vistas/formularioCargaCuvs.php';
		 }else{
		     $this->accion = "Ingreso de Identificadores";
		     require APP . 'IngresoCuvsAretes/vistas/formularioCargaIdentificadores.php';
		 }		 
		}	/**
		* Método para registrar en la base de datos -CertificadosVacunacion
		*/
		public function guardar()
		{
		  $this->lNegocioCertificadosVacunacion->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: CertificadosVacunacion
		*/
		public function editar()
		{
		 $this->accion = "Editar CertificadosVacunacion"; 
		 $this->modeloCertificadosVacunacion = $this->lNegocioCertificadosVacunacion->buscar($_POST["id"]);
		 require APP . 'IngresoCuvsAretes/vistas/formularioCertificadosVacunacionVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - CertificadosVacunacion
		*/
		public function borrar()
		{
		  $this->lNegocioCertificadosVacunacion->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - CertificadosVacunacion
		*/
		 public function tablaHtmlCertificadosVacunacion($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_certificado_vacunacion'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'IngresoCuvsAretes\certificadosvacunacion"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_certificado_vacunacion'] . '</b></td>
<td>'
		  . $fila['id_especie'] . '</td>
<td>' . $fila['numero_documento']
		  . '</td>
<td>' . $fila['estado'] . '</td>
</tr>');
		}
		}
	}
		
	/**
	 * Método para obtener ruta de archivo excel
	 * */
	public function cargarIdentificadores(){
	    $this->lNegocioSeriesAretes->leerArchivoExcelIdentificadores($_POST);    
    
	}
	
	/**
	 * Método para obtener ruta de archivo excel
	 * */
	public function cargarCuvs(){
	    $this->lNegocioCertificadosVacunacion->leerArchivoExcelCuvs($_POST);
	    
	}
	
}
