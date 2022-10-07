<?php
/**
 * Controlador Administracion Revision Formularios
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
use Agrodb\Usuarios\Modelos\UsuariosPerfilesLogicaNegocio;
use Agrodb\RevisionFormularios\Modelos\AsignacionCoordinadorLogicaNegocio;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class AdministracionRevisionFormulariosControlador extends BaseControlador{

	private $lNegocioProveedorExterior = null;

	private $modeloProveedorExterior = null;

	private $lNegocioUsuariosPerfiles = null;

	private $lNegocioAsignacionCoordinador = null;

	private $accion = null;

	private $rutaFecha = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioProveedorExterior = new ProveedorExteriorLogicaNegocio();
		$this->modeloProveedorExterior = new ProveedorExteriorModelo();
		$this->lNegocioUsuariosPerfiles = new UsuariosPerfilesLogicaNegocio();
		$this->lNegocioAsignacionCoordinador = new AsignacionCoordinadorLogicaNegocio();

		$this->rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');

		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$this->cargarTecnicosAsigancionRevisionFormularios();
		$this->cargarPanelRevisionDocumentalProveedorExterior();
		require APP . 'ProveedoresExterior/vistas/listaRevisionDocumentalProveedorExterior.php';
	}

	/**
	 * Método de inicio del controlador
	 */
	public function asignarRevisor(){
		$this->accion = "Asignar técnico para inspección Documental";
		$solicitudes = $_POST["elementos"];
		$this->cargarAsignacionSolicitudesProveedorExterior($solicitudes);
		$this->cargarInspectoresRevisoresConProvincia();

		$arrayParametros = array(
			'idSolicitud' => $solicitudes,
			'tipoSolicitud' => 'proveedorExterior',
			'tipoInspector' => 'Documental');

		$this->desplegarDetalleRevisoresAsignados($arrayParametros);
		require APP . 'ProveedoresExterior/vistas/formularioAsignarRevisorVista.php';
	}

	/**
	 * Construye el código HTML para desplegar panel de busqueda para los reportes
	 */
	public function cargarPanelRevisionDocumentalProveedorExterior(){
		$this->panelBusquedaProveedoresExteriorReporte = '
            <input type="hidden" id="estadoSolicitud" name="estadoSolicitud">
                <table class="filtro">
    			<tbody>
                <tr>
    				<th>Condición:</th>			
    				<td>
    					<select id="condicion" name="condicion" required="">
    						<option value="">Seleccione opción....</option>
    						<option value="Documental">Revisión documental</option>		
    					</select>	
    				</td>			
    				<th>Asignación:</th>
    				<td>
                        <select id="inspector" name="inspector" required="">
                            ' . $this->comboTecnicoAsigancionRevisionFormularios . '			
                        </select>
    				</td>
                </tr>
                <tr>
    				<th>Operador:</th>			
    				<td>
    					<select id="identificadorOperador" name="identificadorOperador" required="">
                            <option value="">Seleccionar....</option>  						
    					</select>	
    				</td>
                </tr>
                <tr>
                    <td colspan="4">
                        <button id="btnFiltrar">Filtrar lista</button>
                    </td>
                </tr>
                </tbody>
                </table>
            ';
	}

	/**
	 * Metodo para cargar el combo de asignacion de tecnicos
	 */
	public function cargarTecnicosAsigancionRevisionFormularios(){
		$perfil = "('PFL_TEC_PROV_EXT')";

		$arrayParametros = array(
			'codigo_perfil' => $perfil);

		$tecnicosAsignacion = $this->lNegocioUsuariosPerfiles->buscarUsuariosInternosPorPerfil($arrayParametros);

		$this->comboTecnicoAsigancionRevisionFormularios = "";
		$this->comboTecnicoAsigancionRevisionFormularios .= '<option value="">Seleccionar....</option><option value="asignar">Por asignar</option>';

		foreach ($tecnicosAsignacion as $item){
			$this->comboTecnicoAsigancionRevisionFormularios .= '<option value="' . $item->identificador . '" >' . $item->nombre . ' ' . $item->apellido . '</option>';
		}

		$this->comboTecnicoAsigancionRevisionFormularios;
	}

	/**
	 * Método para obtener los operadores con solicitudes de proveedores en el exterior en estao de revisión documental
	 */
	public function buscarSolicitudesOperadoresProveedorExterior(){
		$condicion = $_POST["condicion"];
		$estadoSolicitud = $_POST["estadoSolicitud"];
		$inspector = $_POST["inspector"];
		$tipoSolicitud = $_POST['tipoSolicitud'];
		$tipoInspector = $_POST['tipoInspector'];

		$arrayParametros = array(
			'condicion' => $condicion,
			'estadoSolicitud' => $estadoSolicitud,
			'inspector' => $inspector,
			'tipoSolicitud' => $tipoSolicitud,
			'tipoInspector' => $tipoInspector);

		if ($inspector == "asignar"){

			$obtenerSolicitudes = $this->lNegocioProveedorExterior->obtenerSolicitudesOperadoresProveedorExteriorPorEstado($arrayParametros);

			$comboSolicitudesProveedorExterior = "";
			$comboSolicitudesProveedorExterior .= '<option value="">Seleccionar....</option>';

			foreach ($obtenerSolicitudes as $item){
				$comboSolicitudesProveedorExterior .= '<option value="' . $item->identificador . '" >' . $item->nombre_operador . '</option>';
			}
		}else{

			$obtenerSolicitudes = $this->lNegocioProveedorExterior->obtenerSolicitudesAsignadasOperadoresProveedorExteriorPorEstado($arrayParametros);

			$comboSolicitudesProveedorExterior = "";
			$comboSolicitudesProveedorExterior .= '<option value="">Seleccionar....</option>';

			foreach ($obtenerSolicitudes as $item){
				$comboSolicitudesProveedorExterior .= '<option value="' . $item->identificador . '" >' . $item->nombre_operador . '</option>';
			}
		}

		echo $comboSolicitudesProveedorExterior;
		exit();
	}

	/**
	 * Método para obtener las solicitudes de proveedores en el exterior por operador solicitante
	 */
	public function buscarSolicitudesProveedorExteriorPorOperador(){
		$condicion = $_POST["condicion"];
		$estadoSolicitud = $_POST["estadoSolicitud"];
		$inspector = $_POST["inspector"];
		$identificadorOperador = $_POST["identificadorOperador"];

		$arrayParametros = array(
			'condicion' => $condicion,
			'estadoSolicitud' => $estadoSolicitud,
			'inspector' => $inspector,
			'identificadorOperador' => $identificadorOperador,
			'tipoSolicitud' => 'proveedorExterior',
			'tipoInspector' => 'Documental');

		$this->articleHtmlSolicitudesRevisionDocumental($arrayParametros);
	}

	/**
	 * Construye el código HTML para desplegar las Solicitudes en forma de artículos
	 */
	public function articleHtmlSolicitudesRevisionDocumental($arrayParametros){
	    
	    $banderaMostarInformacion = false;
	    
		if ($arrayParametros["inspector"] == "asignar"){
		    
		    $tecnicoResponsable = $this->lNegocioUsuariosPerfiles->buscarUsuariosXAplicacionPerfil($_SESSION['usuario'], 'PFL_ADM_PROV_EXT');
		    
		    if(isset($tecnicoResponsable->current()->identificador)){		        
                $obtenerSolicitudes = $this->lNegocioProveedorExterior->obtenerSolicitudesProveedorExteriorPorOperadorPorEstado($arrayParametros);
                $banderaMostarInformacion = true;   
		    }
			
		}else{
		    $obtenerSolicitudes = $this->lNegocioProveedorExterior->obtenerSolicitudesAsignadasProveedorExteriorPorEstado($arrayParametros);
		    $banderaMostarInformacion = true;   
		}

		if ($banderaMostarInformacion){
		
    		$contador = 1;
    		$estadoMostrado = "";
    
    		$articulo = "";
    		foreach ($obtenerSolicitudes as $fila){
    
    			switch ($fila['estado_solicitud']) {
    				case 'RevisionDocumental':
    					$estadoMostrado = "Revision documental";
    				break;
    				case 'AsignadoDocumental':
    					$estadoMostrado = "Asignado documental";
    				break;
    			}
    			$articulo .= '<article id="' . $fila['id_proveedor_exterior'] . '" class="item"
                								data-rutaAplicacion="' . URL_MVC_FOLDER . 'ProveedoresExterior\AdministracionRevisionFormularios"
                								data-opcion="abrirSolicitudRevisionDocumental" ondragstart="drag(event)"
                								draggable="true" data-destino="detalleItem">
                								<span><small><b>' . ($fila["codigo_creacion_solicitud"] ? $fila["codigo_creacion_solicitud"] : 'TEMPORAL') . '</b> </small></span><br/>
                                                <span><small><b>Razón social: </b>' . $fila["nombre_operador"] . '</small></span><br/>
                                                <span><small><b>Provincia: </b>' . $fila["nombre_provincia_operador"] . '</small></span><br/>
                					 			<span class="ordinal">' . $contador ++ . '</span>
                								<aside><small><b>Estado: </b>' . $estadoMostrado . '</small></aside>
                						</article>';
    		}
    
    		echo $articulo;
		
		}else{
		    
		    echo $articulo = "";
		    
		}
		exit();
	}

	/**
	 * Método para abrir la solicitud en estado de revision documental
	 */
	public function abrirSolicitudRevisionDocumental(){
		$procesoModificacion = false;
		$arrayParametros = array(
			'id_proveedor_exterior' => $_POST["id"]);

		$this->modeloProveedorExterior = $this->lNegocioProveedorExterior->buscar($arrayParametros['id_proveedor_exterior']);
		$identificadorOperador = $this->modeloProveedorExterior->getIdentificadorOperador();
		$nombreProvinciaOperador = $this->modeloProveedorExterior->getNombreProvinciaOperador();

		$this->construirDatosOperador($identificadorOperador, $nombreProvinciaOperador);
		$this->desplegarDocumentosAdjuntos($arrayParametros);
		$this->construirDetalleProductosProveedor($arrayParametros, $procesoModificacion);
		$this->construirResultadoRevisionDocumental();

		$this->accion = "Solicitud operador";
		require APP . 'ProveedoresExterior/vistas/formularioProveedorExteriorRevisionDocumental.php';
	}

	/**
	 * Método para abrir la solicitud en estado de revision documental
	 */
	public function construirResultadoRevisionDocumental(){
		$this->resultadoRevisionDocumental = "";

		$this->resultadoRevisionDocumental = '<fieldset>
                                                <legend>Resultado de revisión documental</legend>
                                                <div data-linea="1">
                                                <label>Resultado: </label>
                                                    <select id="resultadoDocumento" name="resultadoDocumento" class="validacion">
                                                        <option value="">Seleccione....</option>
                                                        <option value="Aprobado">Aprobar revisión documental</option>
                                                        <option value="Inhabilitado">Rechazado</option>
                                                        <option value="Subsanacion">Subsanación</option>
                                                    </select>   
                                                </div>
                                                <div data-linea="2">
                                                <label>Observación: </label>
                                                    <input type="text" id="observacionDocumento" name="observacionDocumento" class="validacion">
                                                <div>
                                                </fieldset>
                                                <button type="submit" id="guardarResultado" class="guardar">Enviar resultado</button>';
	}

	/**
	 * Construye el código HTML para desplegar panel de busqueda para los reportes
	 */
	public function cargarAsignacionSolicitudesProveedorExterior($solicitudes){
		$query = "id_proveedor_exterior in (" . $solicitudes . ")";

		$solicitudesAsignadas = $this->lNegocioProveedorExterior->buscarLista($query);

		$this->solicitudesAsignadasRevision = "";

		foreach ($solicitudesAsignadas as $fila){

			$arrayParametros = array(
				'id_proveedor_exterior' => $fila['id_proveedor_exterior']);

			$this->modeloProveedorExterior = $this->lNegocioProveedorExterior->buscar($arrayParametros['id_proveedor_exterior']);
			$identificadorOperador = $this->modeloProveedorExterior->getIdentificadorOperador();
			$nombreProvinciaOperador = $this->modeloProveedorExterior->getNombreProvinciaOperador();

			$this->construirDatosOperador($identificadorOperador, $nombreProvinciaOperador);
			$this->desplegarDocumentosAdjuntos($arrayParametros);
			$this->construirDetalleProductosProveedor($arrayParametros, false);

			$this->solicitudesAsignadasRevision .= '<fieldset>
	               <legend>Información del solicitante - Solicitud N°  
                ' . $this->modeloProveedorExterior->getCodigoCreacionSolicitud() . '</legend>
                ' . $this->informacionSocilitante . '
                </fieldset>
                <fieldset>
                    <legend>Información del proveedor en el exterior</legend>    
                    <div data-linea="1">
                    	<label for="nombre_fabricante">Nombre del fabricante: </label>
                		' . $this->modeloProveedorExterior->getNombreFabricante() . '
                    </div>
                    <div data-linea="2">
                    	<label for="id_pais_fabricante">País del fabricante: </label>
                    	' . $this->modeloProveedorExterior->getNombrePaisFabricante() . '
                    </div>                
                    <div data-linea="3">
                    	<label for="direccion_fabricante">Dirección del fabricante: </label>
                    	' . $this->modeloProveedorExterior->getDireccionFabricante() . '
                    </div>
                    <div data-linea="4">
                    	<label for="servicio_oficial">Servicios oficiales que regulan los productos que fabrica la planta: </label>
                        ' . $this->modeloProveedorExterior->getServicioOficial() . '
                    </div>	
                </fieldset>
                <fieldset>
            		<legend>Tipos de productos veterinarios que desea exportar</legend>	
                		
            		<table id="detalleProductosProveedor" style="width: 100%">
            			<thead>
            				<tr>
            					<th>#</th>
            					<th>Tipos de productos agregados</th>
            				</tr>
            			</thead>			
            			<tbody>
            				' . $this->productosProveedorExterior . '
            			</tbody>
            		</table>		
                </fieldset><hr/><br/>';
		}

		$this->solicitudesAsignadasRevision;
	}

	/**
	 * Construye el código HTML para desplegar los inspectores revisores por provincia
	 */
	public function cargarInspectoresRevisoresConProvincia(){
		$this->inspectoresRevisores = "";

		$perfil = "('PFL_TEC_PROV_EXT')";

		$arrayParametros = array(
			'codigo_perfil' => $perfil);

		$tecnicosAsignacion = $this->lNegocioUsuariosPerfiles->buscarUsuariosInternosPorPerfil($arrayParametros);

		$this->comboInspectoresRevisores = "";
		$this->comboInspectoresRevisores .= '<option value="">Seleccionar....</option>';

		foreach ($tecnicosAsignacion as $item){
			$this->comboInspectoresRevisores .= '<option value="' . $item->identificador . '" data-nombreInspector="' . $item->nombre . ' ' . $item->apellido . '" >' . $item->nombre . ' ' . $item->apellido . ' - ' . $item->provincia . '</option>';
		}

		$this->comboInspectoresRevisores;
	}

	/**
	 * Método para registrar en la base de datos el técnico asignado
	 */
	public function guardarAsignacionRevisor(){
		$revisorAsignado = $_POST['revisorAsignado'];
		$nombreRevisorAsignado = $_POST['nombreRevisorAsignado'];
		$asignante = $_SESSION['usuario'];
		$idSolicitud = $_POST['idSolicitud'];
		$tipoSolicitud = $_POST['tipoSolicitud'];
		$tipoInspector = $_POST['tipoInspector'];

		$filaRevisorAsignado = "";
		$banderaRegistro = false;

		$validacion = "Fallo";
		$resultado = "La solicitud solo puede ser asignada a un técnico a la vez.";

		$arraySolicitudes = explode(",", $idSolicitud);

		foreach ($arraySolicitudes as $solicitud){

			$arrayParametros = array(
				'identificador_inspector' => $revisorAsignado,
				'fecha_asignacion' => 'now()',
				'identificador_asignante' => $asignante,
				'id_solicitud' => $solicitud,
				'tipo_solicitud' => $tipoSolicitud,
				'tipo_inspector' => $tipoInspector);

			$procesoValidacion = $this->lNegocioAsignacionCoordinador->guardar($arrayParametros);

			if ($procesoValidacion){

				$banderaRegistro = true;

				$arrayParametrosAsignacion = array(
					'id_proveedor_exterior' => $solicitud,
					'estado_solicitud' => 'AsignadoDocumental');

				$this->lNegocioProveedorExterior->actualizarEstadoProveedorExterior($arrayParametrosAsignacion);

				$datosSolicitud = $this->lNegocioProveedorExterior->buscar($solicitud);
				$codigoCreacionSolicitud = $datosSolicitud->getCodigoCreacionSolicitud();

				$arrayParametrosFila = array(
					'id_asignacion_coordinador' => $procesoValidacion,
					'codigo_creacion_solicitud' => $codigoCreacionSolicitud,
					'tipo_inspector' => $tipoInspector,
					'nombre_inspector_asignado' => $nombreRevisorAsignado,
					'id_proveedor_exterior' => $solicitud);

				$filaRevisorAsignado .= $this->generarFilaRevisorAsignado($arrayParametrosFila);
			}else{
				break;
			}
		}

		if ($banderaRegistro){

			$validacion = "Exito";
			$resultado = "";

			echo json_encode(array(
				'validacion' => $validacion,
				'resultado' => $resultado,
				'filaRevisorAsignado' => $filaRevisorAsignado));
		}else{

			echo json_encode(array(
				'validacion' => $validacion,
				'resultado' => $resultado));
		}
	}

	/**
	 * Método para borrar una fila de un revisor asignado
	 */
	public function eliminarAsignacionRevisor(){
		$idAsignacionCordinador = $_POST['idAsignacionCoordinador'];
		$this->lNegocioAsignacionCoordinador->borrar($idAsignacionCordinador);

		$arrayParametros = array(
			'id_proveedor_exterior' => $_POST['idProveedorExterior'],
			'estado_solicitud' => 'RevisionDocumental');

		$this->lNegocioProveedorExterior->actualizarEstadoProveedorExterior($arrayParametros);
	}

	/**
	 * /**
	 * Método para agregar una fila del revisor asignado a una solicitud.
	 */
	public function generarFilaRevisorAsignado($arrayParametros){
		$idAsignacionCoordinador = $arrayParametros['id_asignacion_coordinador'];
		$codigoCreacionSolicitud = $arrayParametros['codigo_creacion_solicitud'];
		$tipoInspector = $arrayParametros['tipo_inspector'];
		$nombreInspectorAsignado = $arrayParametros['nombre_inspector_asignado'];
		$idProveedorExterior = $arrayParametros['id_proveedor_exterior'];

		$this->listaRevisorAsignado = '
                        <tr id="fila' . $idAsignacionCoordinador . '">
                            <td>' . $codigoCreacionSolicitud . '</td>
                            <td>' . $tipoInspector . '</td>
                            <td>' . $nombreInspectorAsignado . '</td>
                            <td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarDetalleRevisorAsignado(' . $idAsignacionCoordinador . ', ' . $idProveedorExterior . '); return false;"/></td>
                        </tr>';

		return $this->listaRevisorAsignado;
	}

	/**
	 * Método para listar los revisaores asignadasos a una solicitud de proveedor en el exterior
	 */
	public function desplegarDetalleRevisoresAsignados($arrayParametros){
		$arraySolicitudes = explode(',', $arrayParametros['idSolicitud']);
		$tipoSolicitud = $arrayParametros['tipoSolicitud'];
		$tipoInspector = $arrayParametros['tipoInspector'];

		$this->generarFilaRevisorAsignado = "";

		foreach ($arraySolicitudes as $solicitud){

			$arrayParametros = array(
				'id_solicitud' => $solicitud,
				'tipo_solicitud' => $tipoSolicitud,
				'tipo_inspector' => $tipoInspector);

			$validarAsignacionInspector = $this->lNegocioAsignacionCoordinador->buscarAsignacionCoordinador($arrayParametros);

			if (isset($validarAsignacionInspector->current()->id_asignacion_coordinador)){

				$datosSolicitud = $this->lNegocioProveedorExterior->buscar($solicitud);
				$codigoCreacionSolicitud = $datosSolicitud->getCodigoCreacionSolicitud();
				$nombreRevisorAsignado = $validarAsignacionInspector->current()->nombre_revisor;

				$arrayParametrosFila = array(
					'id_asignacion_coordinador' => $validarAsignacionInspector->current()->id_asignacion_coordinador,
					'codigo_creacion_solicitud' => $codigoCreacionSolicitud,
					'tipo_inspector' => $tipoInspector,
					'nombre_inspector_asignado' => $nombreRevisorAsignado,
					'id_proveedor_exterior' => $solicitud);

				$this->generarFilaRevisorAsignado .= $this->generarFilaRevisorAsignado($arrayParametrosFila);
			}
		}

		$this->generarFilaRevisorAsignado;
	}

	/**
	 * Método para guardar el resultado de revisión documental
	 */
	public function guardarRevisionDocumental(){
		$solicitud = $_POST['id_proveedor_exterior'];
		$identificadorInspector = $_SESSION['usuario'];
		$identificadorAsignante = $_SESSION['usuario'];
		$observacion = $_POST['observacionDocumento'];
		$estadoSiguiente = $_POST['resultadoDocumento'];
		$rutaAdjunto = $_POST['ruta_adjunto'];

		$arrayParametrosSolicitud = array(
			'id_proveedor_exterior' => $solicitud,
			'estado_solicitud' => $estadoSiguiente,
			'observacion_solicitud' => $observacion,
			'identificador_revisor' => $identificadorInspector,
		    'identificador_asignante' => $identificadorAsignante,
		    'ruta_adjunto' => $rutaAdjunto,
			'fecha_atencion_documental' => 'now()');

		if ($estadoSiguiente == "Aprobado"){
			$arrayParametrosSolicitud += [
				'fecha_aprobacion_solicitud' => 'now()'];
		}

		if (isset($_POST['ruta_adjunto'])){
			$arrayParametrosSolicitud += [
				'ruta_archivo_subsanacion' => $_POST['ruta_adjunto']];
		}

		$this->lNegocioProveedorExterior->guardarRevisionSolicitud($arrayParametrosSolicitud);

		Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
	}
}
