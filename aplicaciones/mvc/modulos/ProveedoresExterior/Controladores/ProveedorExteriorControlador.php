<?php
/**
 * Controlador ProveedorExterior
 *
 * Este archivo controla la lógica del negocio del modelo: ProveedorExteriorModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2021-07-13
 * @uses ProveedorExteriorControlador
 * @package ProveedoresExterior
 * @subpackage Controladores
 */
namespace Agrodb\ProveedoresExterior\Controladores;

use Agrodb\ProveedoresExterior\Modelos\ProveedorExteriorLogicaNegocio;
use Agrodb\ProveedoresExterior\Modelos\ProveedorExteriorModelo;
use Agrodb\ProveedoresExterior\Modelos\ProductosProveedorLogicaNegocio;
use Agrodb\ProveedoresExterior\Modelos\DocumentosAdjuntosLogicaNegocio;
use Agrodb\ProveedoresExterior\Modelos\SubsanacionLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\OperadoresLogicaNegocio;
use Agrodb\Catalogos\Modelos\TipoProductosLogicaNegocio;
use Agrodb\RevisionFormularios\Modelos\AsignacionCoordinadorLogicaNegocio;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\Catalogos\Modelos\SubtipoProductosLogicaNegocio;

class ProveedorExteriorControlador extends BaseControlador{

	private $lNegocioProveedorExterior = null;

	private $modeloProveedorExterior = null;

	private $lNegocioProductosProveedor = null;

	private $lNegocioDocumentosAdjuntos = null;

	private $lNegocioOperadores = null;

	private $lNegocioSubtipoProductos = null;

	private $lNegocioAsignacionCoordinador = null;

	private $lNegocioSubsanacion = null;

	private $accion = null;

	private $article = null;

	private $informacionOperador = null;

	private $documentosEmpresa = null;

	private $rutaFecha = null;

	private $observacionRevisionDocumental = null;

	private $tecnicoRevisionDocumentalAsignado = null;

	private $estadoSolicitudSeleccionada = false;

	private $solicitudModificada = false;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioProveedorExterior = new ProveedorExteriorLogicaNegocio();
		$this->modeloProveedorExterior = new ProveedorExteriorModelo();

		$this->lNegocioProductosProveedor = new ProductosProveedorLogicaNegocio();
		$this->lNegocioDocumentosAdjuntos = new DocumentosAdjuntosLogicaNegocio();
		$this->lNegocioOperadores = new OperadoresLogicaNegocio();
		$this->lNegocioSubtipoProductos = new SubtipoProductosLogicaNegocio();
		$this->lNegocioAsignacionCoordinador = new AsignacionCoordinadorLogicaNegocio();
		$this->lNegocioSubsanacion = new SubsanacionLogicaNegocio();

		$this->rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');

		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloProveedorExterior = $this->lNegocioProveedorExterior->buscarProveedorExterior();
		$this->articleHtmlSolicitudes($modeloProveedorExterior);
		require APP . 'ProveedoresExterior/vistas/listaProveedorExteriorVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->construirIngresoDatosOperador($_SESSION['usuario']);

		$this->accion = "Solicitud de habilitación";
		require APP . 'ProveedoresExterior/vistas/formularioProveedorExteriorVista.php';
	}

	/**
	 * Método para registrar en la base de datos -ProveedorExterior
	 */
	public function guardar(){
		$estado = 'Exito';
		$mensaje = '';
		$contenido = '';

		$arrayParametros = array(
			'identificador_operador' => $_POST['identificadorOperador'],
			'id_provincia_operador' => $_POST['idProvinciaOperador'],
			'nombre_provincia_operador' => $_POST['nombreProvinciaOperador'],
			'nombre_fabricante' => $_POST['nombreFabricante'],
			'id_pais_fabricante' => $_POST['idPaisFabricante'],
			'nombre_pais_fabricante' => $_POST['nombrePaisFabricante'],
			'direccion_fabricante' => $_POST['direccionFabricante'],
			'servicio_oficial' => $_POST['servicioOficial'],
			'estado_solicitud' => 'SinEnviar');

		$contenido = $this->lNegocioProveedorExterior->guardarSolicitud($arrayParametros);

		echo json_encode(array(
			"estado" => $estado,
			"mensaje" => $mensaje,
			"contenido" => $contenido));
	}

	/**
	 * Método para abrir la solicitud del proveedor
	 */
	public function abrirSolicitudCreada(){

		$arrayParametros = array(
			'id_proveedor_exterior' => $_POST["id"]);

		$this->modeloProveedorExterior = $this->lNegocioProveedorExterior->buscar($arrayParametros['id_proveedor_exterior']);
		$estadoSolicitud = $this->modeloProveedorExterior->getEstadoSolicitud();
		$nombreProvinciaOperador = $this->modeloProveedorExterior->getNombreProvinciaOperador();

		switch ($estadoSolicitud) {

			case "Subsanacion":
				$this->construirResultadoRevisionDocumental($arrayParametros['id_proveedor_exterior']);
			break;
		}

		$this->buscarSubtipoProductoPorArea();
		$this->construirDatosOperador($_SESSION['usuario'], $nombreProvinciaOperador);
		$this->cargarDocumentosAdjuntos();
		$this->cargarDocumentosAdjuntosEmpresa();
		$this->construirDetalleProductosProveedor($arrayParametros, true);

		$this->accion = "Solicitud de habilitación";
		$this->modeloProveedorExterior = $this->lNegocioProveedorExterior->buscar($_POST["id"]);
		require APP . 'ProveedoresExterior/vistas/formularioProveedorExteriorSolicitudCreadaVista.php';
	}

	/**
	 * Método para finalizar la solicitud
	 */
	public function finalizarSolicitud(){
		$this->lNegocioProveedorExterior->guardarFinalizarSolicitud($_POST);

		Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
	}

	/**
	 * Método para abrir la solicitud en estado de revision documental
	 */
	public function abrirSolicitudEnviada(){
		$arrayParametros = array(
			'id_proveedor_exterior' => $_POST["id"]);

		$this->modeloProveedorExterior = $this->lNegocioProveedorExterior->buscar($arrayParametros['id_proveedor_exterior']);
		$estadoSolicitud = $this->modeloProveedorExterior->getEstadoSolicitud();
		$nombreProvinciaOperador = $this->modeloProveedorExterior->getNombreProvinciaOperador();

		switch ($estadoSolicitud) {
			case "AsignadoDocumental":

				$arrayRevisorAsignado = array(
					'id_solicitud' => $arrayParametros['id_proveedor_exterior'],
					'tipo_solicitud' => 'proveedorExterior',
					'tipo_inspector' => 'Documental');

				$this->construirTecnicoRevisionDocumentalAsignado($arrayRevisorAsignado);
			break;
		}

		$this->construirDatosOperador($_SESSION['usuario'], $nombreProvinciaOperador);
		$this->desplegarDocumentosAdjuntos($arrayParametros);
		$this->construirDetalleProductosProveedor($arrayParametros, false);

		$this->accion = "Solicitud de habilitación";
		require APP . 'ProveedoresExterior/vistas/formularioProveedorExteriorSolicitudEnviadaVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - ProveedorExterior
	 */
	public function borrar(){
		$this->lNegocioProveedorExterior->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - ProveedorExterior
	 */
	public function tablaHtmlProveedorExterior($tabla){
		$contador = 0;
		foreach ($tabla as $fila){
			$this->itemsFiltrados[] = array(
				'<tr id="' . $fila['id_proveedor_exterior'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'ProveedoresExterior\ProveedorExterior"
		  data-opcion="abrirSolicitudCreada" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_proveedor_exterior'] . '</b></td>
        <td>' . $fila['identificador_operador'] . '</td>
        <td>' . $fila['nombre_provincia_operador'] . '</td>
        <td>' . $fila['nombre_fabricante'] . '</td>
        </tr>');
		}
	}

	/**
	 * Construye el código HTML para desplegar las Solicitudes en forma de artículos
	 */
	public function articleHtmlSolicitudes(){
		$qEstado = $this->lNegocioProveedorExterior->buscarEstadoSolicitudesProveedorExterior($_SESSION['usuario']);
		$contador = 1;
		$estadoMostrado = "";
		$pagina = "";

		foreach ($qEstado as $estado){

			switch ($estado['estado_solicitud']) {
				case 'Aprobado':
					$this->article .= "<h2> Solicitudes Aprobadas </h2>";
					$estadoMostrado = "Aprobado";
					$pagina = "abrirSolicitudEnviada";
				break;

				case 'SinEnviar':
					$this->article .= "<h2> Solicitudes sin Enviar </h2>";
					$estadoMostrado = "Sin enviar";
					$pagina = "abrirSolicitudCreada";
				break;

				case 'Subsanacion':
					$this->article .= "<h2> Solicitudes por Subsanar </h2>";
					$estadoMostrado = "Subsanación";
					$pagina = "abrirSolicitudCreada";
				break;

				case 'RevisionDocumental':
					$this->article .= "<h2> Solicitudes por Revisión Documental </h2>";
					$estadoMostrado = "Revision documental";
					$pagina = "abrirSolicitudEnviada";
				break;

				case 'AsignadoDocumental':
					$this->article .= "<h2> Solicitudes Asignadas a Revisión Documental </h2>";
					$estadoMostrado = "Asignado Documental";
					$pagina = "abrirSolicitudEnviada";
				break;

				case 'Inhabilitado':
					$this->article .= "<h2> Solicitudes Inhabilitadas </h2>";
					$estadoMostrado = "Inhabilitado";
					$pagina = "abrirSolicitudEnviada";
				break;

				default:
					$this->article .= "<h2> Solicitudes en estado " . $estado['estado_solicitud'] . "</h2>";
				break;
			}

			$query = "identificador_operador = '" . $_SESSION['usuario'] . "' and estado_solicitud = '" . $estado['estado_solicitud'] . "' ";

			$consulta = $this->lNegocioProveedorExterior->buscarLista($query);

			foreach ($consulta as $fila){

				$this->solicitudesProvedorExterior = $this->lNegocioOperadores->obtenerInformacionOperadorPorIdentificador($fila['identificador_operador']);

				$this->article .= '<article id="' . $fila['id_proveedor_exterior'] . '" class="item"
            								data-rutaAplicacion="' . URL_MVC_FOLDER . 'ProveedoresExterior\ProveedorExterior"
            								data-opcion="' . $pagina . '" ondragstart="drag(event)"
            								draggable="true" data-destino="detalleItem">
            								<span><small><b>' . ($fila["codigo_creacion_solicitud"] ? $fila["codigo_creacion_solicitud"] : 'TEMPORAL') . '</b> </small></span><br/>
                                            <span><small><b>Razón social: </b>' . $this->solicitudesProvedorExterior->current()->nombre_operador . '</small></span><br/>
                                            <span><small><b>Provincia: </b>' . $fila["nombre_provincia_operador"] . '</small></span><br/>
            					 			<span class="ordinal">' . $contador ++ . '</span>
            								<aside><small><b>Estado: </b>' . $estadoMostrado . '</small></aside>
    								</article>';
			}
		}
	}

	/**
	 * Metodo para obtener y construir los datos del operador
	 */
	public function construirIngresoDatosOperador($identificadorOperador){
		$datosOperador = $this->lNegocioOperadores->obtenerDatosOperadorPorCodigoOperacionPorEstado($identificadorOperador, "('IAVFRA' ,'IAVFOR', 'IAVDIS')", 'registrado');

		if (isset($datosOperador->current()->identificador)){

			$this->informacionOperador = '<div data-linea="1">
                                    			<label for="identificador_operador">RUC/RISE: </label>' . $datosOperador->current()->identificador . '
                                    			<input type="hidden" id="identificador_operador" name="identificador_operador" value="' . $datosOperador->current()->identificador . '" readonly="readonly" />
                                    		</div>
                                    			    
                                    		<div data-linea="1">
                                    			<label for="razon_social_operador">Razon social: </label>' . $datosOperador->current()->razon_social . '
                                    		</div>
                                    			    
                                    		<div data-linea="2">
                                    			<label for="direccion_operador">Direccion: </label>' . $datosOperador->current()->direccion . '
                                    		</div>
                                    			    
                                    		<div data-linea="3">
                                    			<label for="nombre_provincia_operador">Provincia: </label>' . $datosOperador->current()->provincia . '
                                    			<input type="hidden" id="id_provincia_operador" name="id_provincia_operador" value="' . $datosOperador->current()->id_provincia . '"
                                    			readonly="readonly" />
                                                <input type="hidden" id="nombre_provincia_operador" name="nombre_provincia_operador" value="' . $datosOperador->current()->provincia . '"
                                    			readonly="readonly" />
                                    		</div>
                                    			    
                                    		<div data-linea="4">
                                    			<label for="telefono_operador">Teléfono: </label>' . $datosOperador->current()->telefono . '
                                    		</div>
                                    			    
                                    		<div data-linea="4">
                                    			<label for="celular_operador">Celular: </label>' . $datosOperador->current()->celular . '
                                    		</div>
                                    			    
                                    		<div data-linea="5">
                                    			<label for="correo_electronico_operador">Correo: </label>' . $datosOperador->current()->correo . '
                                    		</div>
                                    			    
                                    		<div data-linea="6">
                                    			<label for="representante_legal_operador">Representante legal: </label>' . $datosOperador->current()->representante_legal . '
                                    		</div>';

			$this->informacionOperador;
		}
	}

	/**
	 * Método para desplegar los documentos adjuntos en el formulario
	 */
	private function cargarDocumentosAdjuntos(){

		$arrayDocumentos = array(
			array(
				'literal' => 'A',
				'descripcion' => 'Habilitación en el país de origen.',
				'obligatorio' => 'SI'),
			array(
				'literal' => 'B',
				'descripcion' => 'Certificado o acreditación de calidad 1.',
				'obligatorio' => 'SI'),
			array(
				'literal' => 'C',
				'descripcion' => 'Certificado o acreditación de calidad 2.',
				'obligatorio' => 'NO'),
			array(
				'literal' => 'D',
				'descripcion' => 'Varios.',
				'obligatorio' => 'NO'));

		$this->documentosAnexos = '<table style="width: 100%;">';

		foreach ($arrayDocumentos as $documento){
			$this->documentosAnexos .= '<tr>' . '<td class="' . ($documento['obligatorio'] == 'SI' ? 'obligatorio' : '') . '">' . $documento['literal'] . '</td>' . '<td>' . '<div>' . $documento['descripcion'] . '</div>' . '<div>' . '<input type="hidden" class="rutaArchivo" data-obligatorio="' . $documento['obligatorio'] . '" id="' . $documento['literal'] . '" name="ruta_archivo[]" value="0"/>
							 <input type="hidden" name="tipo_archivo[]" value="' . $documento['descripcion'] . '"/>
	                         <input type="file" class="archivo ' . ($documento['obligatorio'] == 'SI' ? 'validacion' : '') . '"  accept="application/pdf" />
	                         <div class="estadoCarga">En espera de archivo... (Tamaño máximo ' . ini_get('upload_max_filesize') . 'B)</div>
	                         <button type="button" class="subirArchivo adjunto" data-rutaCarga=' . PROV_EXTE_DOC_ADJ . $this->rutaFecha . ' >Subir archivo</button>
	                      </div>' . '</td>' . '</tr>';
		}

		$this->documentosAnexos .= '</table><p class="nota"><span class="obligatorio"/> Documento obligatorio.</p>';
	}

	/**
	 * Método para desplegar los documentos adjuntos de la empresa
	 */
	private function cargarDocumentosAdjuntosEmpresa(){

		$arrayDocumentos = array(
			array(
				'literal' => 'F',
				'descripcion' => 'Organigrama de la empresa.',
				'obligatorio' => 'SI'));

		$this->documentosEmpresa = '<table style="width: 100%;">';

		foreach ($arrayDocumentos as $documento){
			$this->documentosEmpresa .= '<tr>' . '<td class="' . ($documento['obligatorio'] == 'SI' ? 'obligatorio' : '') . '">' . $documento['literal'] . '</td>' . '<td>' . '<div>' . $documento['descripcion'] . '</div>' . '<div>' . '<input type="hidden" class="rutaArchivo" data-obligatorio="' . $documento['obligatorio'] . '" id="' . $documento['literal'] . '" name="ruta_archivo[]" value="0"/>
							 <input type="hidden" name="tipo_archivo[]" value="' . $documento['descripcion'] . '"/>
	                         <input type="file" class="archivo ' . ($documento['obligatorio'] == 'SI' ? 'validacion' : '') . '" accept="application/pdf" />
	                         <div class="estadoCarga">En espera de archivo... (Tamaño máximo ' . ini_get('upload_max_filesize') . 'B)</div>
	                         <button type="button" class="subirArchivo adjunto" data-rutaCarga=' . PROV_EXTE_DOC_ADJ . $this->rutaFecha . ' >Subir archivo</button>
	                      </div>' . '</td>' . '</tr>';
		}

		$this->documentosEmpresa .= '</table><p class="nota"><span class="obligatorio"/> Documento obligatorio.</p>';
	}

	/**
	 * Método para obtener los tipos de productos disponibles del operador con una operación definida y un área temática
	 */
	public function buscarSubtipoProductoPorArea(){
		$idArea = 'IAV';

		$qTipoProductos = $this->lNegocioSubtipoProductos->buscarSubtipoProductoPorAreaTematica($idArea);

		$this->comboSubtipoProductos = "";

		foreach ($qTipoProductos as $item){
			$this->comboSubtipoProductos .= '<option value="' . $item->id_subtipo_producto . '" >' . $item->nombre . '</option>';
		}

		$this->comboSubtipoProductos;
	}

	/**
	 * Método para actualizar los datos de la informacion del solicitante
	 */
	public function actualizarInformacionProveedor(){
		$idProveedorExterior = $_POST["idProveedorExterior"];
		$nombreFabricante = $_POST["nombreFabricante"];
		$idPaisFabricante = $_POST["idPaisFabricante"];
		$nombrePaisFabricante = $_POST["nombrePaisFabricante"];
		$direccionFabricante = $_POST["direccionFabricante"];
		$servicioOficial = $_POST["servicioOficial"];

		$arrayParametros = array(
			'id_proveedor_exterior' => $idProveedorExterior,
			'nombre_fabricante' => $nombreFabricante,
			'id_pais_fabricante' => $idPaisFabricante,
			'nombre_pais_fabricante' => $nombrePaisFabricante,
			'direccion_fabricante' => $direccionFabricante,
			'servicio_oficial' => $servicioOficial);

		$validacion = "Exito";
		$resultado = "Los datos han sido actualizados";

		$this->lNegocioProveedorExterior->actualizarDatosProveedorExterior($arrayParametros);

		echo json_encode(array(
			'resultado' => $resultado,
			'validacion' => $validacion));
	}

	/**
	 * Método para mostrar el resultado de subsanacion de la revision documental
	 */
	public function construirResultadoRevisionDocumental($idProveedorExterior){
		$subsanacion = $this->lNegocioSubsanacion->buscarSubsanacion($idProveedorExterior);

		$this->observacionRevisionDocumental = '<fieldset>
                                                    <legend>Resultado de la evaluación técnica</legend>
                                                    <div data-linea="1">
                                                    <label>Observación técnica: </label>' . $subsanacion->current()->observacion_subsanacion . '
                                                    </div>
                                                    <div data-linea="2">
                                                    <a href="' . $subsanacion->current()->ruta_archivo_subsanacion . '" target="_blank">Informe de análisis</a>
                                                    </div>
                                                    <div data-linea="3">
                                                    <label>Notificación:</label> Estimado usuario dispone de 60 días, desde la emisión del informe de resultados, para subsanar las observaciones emitidas por Agrocalidad. Superado este tiempo la solicitud será cancelada.
                                                    </div>
                                                </fieldset>';
	}

	/**
	 * Método para mostrar el tecnico asignado para la revision documental
	 */
	public function construirTecnicoRevisionDocumentalAsignado($arrayParametros){
		$validarAsignacionInspector = $this->lNegocioAsignacionCoordinador->buscarAsignacionCoordinador($arrayParametros);

		if (isset($validarAsignacionInspector->current()->id_asignacion_coordinador)){

			$this->tecnicoRevisionDocumentalAsignado = '<fieldset><legend>Técnico asignado</legend>
                                                <div data-linea="1">
                                                    <label>Identificador: </label>' . $validarAsignacionInspector->current()->identificador_inspector . '
                                                </div>
                                                <div data-linea="2">
                                                    <label>Nombre del técnico: </label>' . $validarAsignacionInspector->current()->nombre_revisor . '
                                                </div>
                                                <div data-linea="3">
                                                    <label>Provincia: </label>' . $validarAsignacionInspector->current()->provincia . '
                                                </div>
                                                </fieldset>';
		}

		$this->tecnicoRevisionDocumentalAsignado;
	}

	/**
	 * Método para generar el reporte de estado de solicitudes de habilitacion
	 */
	public function exportarEstadoSolicitudesExcel(){
		$idProvinciaFiltro = $_POST["idProvinciaFiltro"];
		$fechaInicio = $_POST["fechaInicio"];
		$fechaFin = $_POST["fechaFin"];

		$arrayParametros = array(
			'id_provincia' => $idProvinciaFiltro,
			'fecha_inicio' => $fechaInicio,
			'fecha_fin' => $fechaFin);

		$proveedoresExteriorEstados = $this->lNegocioProveedorExterior->buscarProveedoresExteriorEstadoSolictudes($arrayParametros);

		$this->lNegocioProveedorExterior->exportarArchivoExcelEstadoSolicitudes($proveedoresExteriorEstados);
	}

	/**
	 * Método para generar el reporte de estado de solicitudes de habilitacion
	 */
	public function exportarSolicitudesHabilitadasExcel(){
		$idProvinciaFiltro = $_POST["idProvinciaFiltro"];
		$fechaInicio = $_POST["fechaInicio"];
		$fechaFin = $_POST["fechaFin"];

		$arrayParametros = array(
			'id_provincia' => $idProvinciaFiltro,
			'fecha_inicio' => $fechaInicio,
			'fecha_fin' => $fechaFin);

		$proveedoresExteriorHabilitados = $this->lNegocioProveedorExterior->buscarProveedoresExteriorHabilitados($arrayParametros);

		$this->lNegocioProveedorExterior->exportarArchivoExcelSolicitudesHabilitadas($proveedoresExteriorHabilitados);
	}

	/**
	 * Método para abrir la solicitud a ser modificada
	 */
	public function modificarSolicitud(){
		$solicitudSeleccionada = $_POST['elementos'];

		if (! empty($solicitudSeleccionada)){

			$this->modeloProveedorExterior = $this->lNegocioProveedorExterior->buscar($solicitudSeleccionada);
			$estadoSolicitud = $this->modeloProveedorExterior->getEstadoSolicitud();
			$nombreProvinciaOperador = $this->modeloProveedorExterior->getNombreProvinciaOperador();

			if ($estadoSolicitud == "Aprobado"){

				$verificarSolicitudModificacion = $this->lNegocioProveedorExterior->verificarSolicitudProcesoModificacion($solicitudSeleccionada);

				if (isset($verificarSolicitudModificacion->current()->id_proveedor_exterior)){

					$this->solicitudModificada = true;
				}else{

					$this->accion = "Solicitud a ser modificada";
					$this->estadoSolicitudSeleccionada = true;
					$arrayParametros = array(
						'id_proveedor_exterior' => $_POST["elementos"]);

					$this->construirDatosOperador($_SESSION['usuario'], $nombreProvinciaOperador);
					$this->desplegarDocumentosAdjuntos($arrayParametros);
					$this->construirDetalleProductosProveedor($arrayParametros, false);
				}
			}
		}
		require APP . 'ProveedoresExterior/vistas/formularioProveedorExteriorModificarSolicitud.php';
	}

	/**
	 * Método para registrar solicitud modificada en la base de datos - ProveedorExterior
	 */
	public function guardarModificarSolicitud(){
		$estado = 'Exito';
		$mensaje = '';
		$contenido = '';

		$idProveedorExterior = $_POST['idProveedorExterior'];

		$this->modeloProveedorExterior = $this->lNegocioProveedorExterior->buscar($idProveedorExterior);
		$identificadorOperador = $this->modeloProveedorExterior->getIdentificadorOperador();
		$nombreFabricante = $this->modeloProveedorExterior->getNombreFabricante();
		$idPaisFabricante = $this->modeloProveedorExterior->getIdPaisFabricante();
		$nombrePaisFabricante = $this->modeloProveedorExterior->getNombrePaisFabricante();
		$direccionFabricante = $this->modeloProveedorExterior->getDireccionFabricante();
		$servicioOficial = $this->modeloProveedorExterior->getServicioOficial();

		$arrayParametros = array(
			'identificador' => $identificadorOperador);

		$datosOperador = $this->lNegocioOperadores->obtenerDatosOperadores($arrayParametros);
		$nombreProvinciaOperador = $datosOperador->current()->provincia;
		$idProvinciaOperador = $datosOperador->current()->id_provincia;

		$arrayParametros = array(
			'identificador_operador' => $identificadorOperador,
			'id_provincia_operador' => $idProvinciaOperador,
			'nombre_provincia_operador' => $nombreProvinciaOperador,
			'nombre_fabricante' => $nombreFabricante,
			'id_pais_fabricante' => $idPaisFabricante,
			'nombre_pais_fabricante' => $nombrePaisFabricante,
			'direccion_fabricante' => $direccionFabricante,
			'servicio_oficial' => $servicioOficial,
			'fecha_modificacion_solicitud' => 'now()',
			'es_modificada' => 'SI',
			'id_solicitud_modificada' => $idProveedorExterior,
			'estado_solicitud' => 'SinEnviar');

		$contenido = $this->lNegocioProveedorExterior->guardar($arrayParametros);

		if ($contenido){

			$arrayIdProductosProveedor = array();
			$arrayNombreProductosProveedor = array();
			$arrayDocumentosAnexosNombre = array();
			$arrayDocumentosAnexos = array();

			$query = "id_proveedor_exterior = " . $idProveedorExterior;

			$consulta = $this->lNegocioProductosProveedor->buscarLista($query);

			foreach ($consulta as $fila){
				$arrayIdProductosProveedor[] = $fila["id_subtipo_producto"];
				$arrayNombreProductosProveedor[] = $fila["nombre_subtipo_producto"];
			}

			$query = "id_proveedor_exterior = " . $idProveedorExterior;

			$consulta = $this->lNegocioDocumentosAdjuntos->buscarLista($query);

			foreach ($consulta as $fila){

				if ($fila["tipo_adjunto"] != "Certificado Proveedor Exterior"){

					$arrayDocumentosAnexosNombre[] = $fila["tipo_adjunto"];
					$arrayDocumentosAnexos[] = $fila["ruta_adjunto"];
				}
			}

			$arrayParametros = array(
				'id_proveedor_exterior' => $contenido,
				'array_id_productos_proveedor' => $arrayIdProductosProveedor,
				'array_nombre_productos_proveedor' => $arrayNombreProductosProveedor,
				'array_documentos_anexos_nombre' => $arrayDocumentosAnexosNombre,
				'array_documentos_anexos' => $arrayDocumentosAnexos);

			$this->lNegocioProveedorExterior->guardarFinalizarSolicitudModificada($arrayParametros);
		}

		echo json_encode(array(
			"estado" => $estado,
			"mensaje" => $mensaje,
			"contenido" => $contenido));
	}
	
	public function paInhabilitarSolicitudesProveedoresExterior(){
		
		$fecha = date("Y-m-d h:m:s");
		$actualizarEstado = "Inhabilitado";
		
		echo "\n".'Proceso Automático de solicitudes' . $fecha ."\n" . "\n";
		
		echo "\n".'Inicio inhabilitación de solicitudes sin enviar' . "\n" . "\n";
		
		$intervalo = 5;
		$estado = "SinEnviar";
		
		$query = " to_char(fecha_creacion_solicitud,'YYYY-MM-DD')::date + interval '" . $intervalo . " days' = current_date and estado_solicitud = '" . $estado . "'";
		
		$solicitudes = $this->lNegocioProveedorExterior->buscarLista($query);
		
		foreach ($solicitudes as $fila) {
			$arrayParametros = array(
				'id_proveedor_exterior' => $fila['id_proveedor_exterior'],
				'estado_solicitud' => $actualizarEstado
			);
			
			$this->lNegocioProveedorExterior->actualizarEstadoProveedorExterior($arrayParametros);
			
			echo 'La Solicitud de Proveedor en el Exterior ' . $fila['id_proveedor_exterior']. ' cambia de estado ' . $actualizarEstado . "\n";
		}
		
		
		echo "\n".'Inicio inhabilitación de solicitudes sin subsanar' . "\n" . "\n";
				
		$intervalo = 60;
		$estado = "Subsanacion";
		
		$solicitudes = $this->lNegocioProveedorExterior->obtenerSolicitudesPorIntervaloPorEstadoSubsanacion($intervalo, $estado);
		
		foreach ($solicitudes as $fila) {
			$arrayParametros = array(
				'id_proveedor_exterior' => $fila['id_proveedor_exterior'],
				'estado_solicitud' => $actualizarEstado
			);
			
			$this->lNegocioProveedorExterior->actualizarEstadoProveedorExterior($arrayParametros);
			
			echo 'La Solicitud de Proveedor en el Exterior ' . $fila['id_proveedor_exterior']. ' cambia de estado ' . $actualizarEstado . "\n";
		}	
		
		echo "\n";
	}
}
