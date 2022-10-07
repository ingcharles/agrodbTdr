<?php
/**
 * Controlador ImportacionesFertilizantes
 *
 * Este archivo controla la lógica del negocio del modelo: ImportacionesFertilizantesModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2020-02-20
 * @uses ImportacionesFertilizantesControlador
 * @package ImportacionFertilizantes
 * @subpackage Controladores
 */
namespace Agrodb\ImportacionFertilizantes\Controladores;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\ImportacionFertilizantes\Modelos\ImportacionesFertilizantesLogicaNegocio;
use Agrodb\ImportacionFertilizantes\Modelos\ImportacionesFertilizantesModelo;
use Agrodb\ImportacionFertilizantes\Modelos\DocumentosAdjuntosLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\OperadoresLogicaNegocio;
use Agrodb\ImportacionFertilizantes\Modelos\ImportacionesFertilizantesProductosLogicaNegocio;

class ImportacionesFertilizantesControlador extends BaseControlador{

	private $lNegocioImportacionesFertilizantes = null;
	private $lNegocioDocumentosAdjuntos = null;
	private $lNegocioOperadores = null;
	private $lNegocioImportacionesFertilizantesProductos = null;
	
	private $modeloImportacionesFertilizantes = null;
	
	private $accion = null;
	private $documentos = null;
	private $rutaFecha = null;
	private $resultadoRevision = null;
	private $tipoPagina = null;
	private $tablaProductos = null;
	private $botonActualizar = null;
	private $tipoSolicitud = array();

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		
		$this->lNegocioImportacionesFertilizantes = new ImportacionesFertilizantesLogicaNegocio();
		$this->lNegocioDocumentosAdjuntos = new DocumentosAdjuntosLogicaNegocio();
		$this->lNegocioOperadores = new OperadoresLogicaNegocio();
		$this->lNegocioImportacionesFertilizantesProductos = new ImportacionesFertilizantesProductosLogicaNegocio();
		
		$this->modeloImportacionesFertilizantes = new ImportacionesFertilizantesModelo();
		
		$this->rutaFecha = date('Y').'/'.date('m').'/'.date('d');
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$arrayParametros = array('identificador' => $_SESSION['usuario']);
		$modeloImportacionesFertilizantes = $this->lNegocioImportacionesFertilizantes->buscarLista($arrayParametros);
		$this->tablaHtmlImportacionesFertilizantes($modeloImportacionesFertilizantes);
		require APP . 'ImportacionFertilizantes/vistas/listaImportacionesFertilizantesVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		
		$arrayParametros = array('identificador' => $_SESSION['usuario']);
		
		$rDatosOperador = $this->lNegocioOperadores->obtenerDatosOperadores($arrayParametros);
		$datosOperador = $rDatosOperador->current();
		$this->modeloImportacionesFertilizantes->setIdentificador($datosOperador->identificador);
		$this->modeloImportacionesFertilizantes->setRazonSocial($datosOperador->nombre_operador);
		
		$this->cargarDocumentosAdjuntos();
		$this->obtenerTipoSolicitudes();
		$this->accion = "Autorización de importación";
		require APP . 'ImportacionFertilizantes/vistas/formularioImportacionesFertilizantesVista.php';
	}
	
	/**
	 * Método para desplegar el formulario solo para visualización
	 */
	public function abrir(){
		
		$arrayParametros = array('id_importacion_fertilizantes' => $_POST["id"], 'estado'=>'activo');
		
		$this->modeloImportacionesFertilizantes = $this->lNegocioImportacionesFertilizantes->buscar($arrayParametros['id_importacion_fertilizantes']);
		$listaProductos = $this->lNegocioImportacionesFertilizantesProductos->buscarLista($arrayParametros);
		$this->tablaProductos = $this->generarListaProducto($listaProductos);
		
		$this->panelResultadoRevision();
		$this->desplegarDocumentosAdjuntos($arrayParametros);
		$this->accion = "Autorización de importación";
		require APP . 'ImportacionFertilizantes/vistas/formularioImportacionesFertilizantesAbrirVista.php';
	}

	/**
	 * Método para registrar en la base de datos -ImportacionesFertilizantes
	 */
	public function guardar(){
		$_POST['estado'] = 'enviado';
		$_POST['ruta_fecha'] = $this->rutaFecha.'/';
		$_POST['nombre_archivo'] = md5(mt_rand());
		$idImportacionFertilizantes = $this->lNegocioImportacionesFertilizantes->guardar($_POST);
		$this->lNegocioImportacionesFertilizantes->generarDocumentoFertilizantes($idImportacionFertilizantes, $_POST['ruta_fecha'], $_POST['nombre_archivo']);
		Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: ImportacionesFertilizantes
	 */
	public function editar(){
		
		$arrayParametros = array('id_importacion_fertilizantes' => $_POST["id"], 'estado'=>'activo');
		
		$this->modeloImportacionesFertilizantes = $this->lNegocioImportacionesFertilizantes->buscar($arrayParametros['id_importacion_fertilizantes']);
		$listaProductos = $this->lNegocioImportacionesFertilizantesProductos->buscarLista($arrayParametros);
		$this->tablaProductos = $this->generarListaProducto($listaProductos, true);
		
		$this->panelResultadoRevision();
		$this->cargarDocumentosAdjuntos();
		$this->obtenerTipoSolicitudes();
		
		$this->accion = "Autorización de importación";
		require APP . 'ImportacionFertilizantes/vistas/formularioImportacionesFertilizantesVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - ImportacionesFertilizantes
	 */
	public function borrar(){
		$this->lNegocioImportacionesFertilizantes->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - ImportacionesFertilizantes
	 */
	public function tablaHtmlImportacionesFertilizantes($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				
				$estado = $this->obtenerEstado($fila['estado']);
				
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_importacion_fertilizantes'] . '"
						 class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'ImportacionFertilizantes/importacionesFertilizantes"
						 data-opcion="'.$this->tipoPagina.'" ondragstart="drag(event)" draggable="true"
						 data-destino="detalleItem">
						 <td>' . ++ $contador . '</td>
						 <td style="white - space:nowrap; "><b>' . $fila['id_importacion_fertilizantes'] . '</b></td>
						 <td>' . $fila['tipo_solicitud'] . '</td>
						 <td>' . $fila['tipo_operacion'] . '</td>
						 <td>' . $estado . '</td>
					</tr>');
			}
		}
	}
	
	/**
	 * Método para desplegar los documentos adjuntos en el formulario 
	 */
	private function cargarDocumentosAdjuntos(){
		
		$arrayDocumentos = array(
			array('literal' => 'A', 'descripcion' => 'Constancia de ingreso (para productos terminados en proceso de registro).', 'obligatorio' => 'NO'),
			array('literal' => 'B', 'descripcion' => 'Carta Autorización (para productos terminados cuando no sea importado por el titular).', 'obligatorio' => 'NO'),
			array('literal' => 'C', 'descripcion' => 'Ficha técnica (para muestras y consumo propio).', 'obligatorio' => 'NO'),
			array('literal' => 'D', 'descripcion' => 'Hoja de seguridad (para consumo propio).', 'obligatorio' => 'NO'),
			array('literal' => 'E', 'descripcion' => 'Otros (para autorización de cantidades mayores en muestras y consumo propio).', 'obligatorio' => 'NO')
		);
		
		$this->documentos = '<table style="width: 100%;">';
		
		foreach ($arrayDocumentos as $documento) {
			$this->documentos .= '<tr>' .
				'<td class="'.($documento['obligatorio']=='SI'?'obligatorio':'').'">' . $documento['literal'] . '</td>' .
				'<td>' .
				'<div>' . $documento['descripcion'] . '</div>' .
				'<div>' .
				'<input type="hidden" class="rutaArchivo" data-obligatorio="' . $documento['obligatorio'] . '" id="' . $documento['literal'] . '" name="ruta_archivo[]" value="0"/>
							 <input type="hidden" name="tipo_archivo[]" value="' . $documento['descripcion'] . '"/>
	                         <input type="file" class="archivo" accept="application/pdf" />
	                         <div class="estadoCarga">En espera de archivo... (Tamaño máximo ' . ini_get('upload_max_filesize') . 'B)</div>
	                         <button type="button" class="subirArchivo adjunto" data-rutaCarga='.IMP_FERT_DOC_ADJ.$this->rutaFecha.' >Subir archivo</button>
	                      </div>' .
	                      '</td>' .
	                      '</tr>';
		}
		
		$this->documentos .= '</table><p class="nota"><span class="obligatorio"/> Documento obligatorio.</p>';
		
	}
	
	/**
	 * Método para desplegar los documentos adjuntos cargados por el operador en el formulario
	 */
	private function desplegarDocumentosAdjuntos($arrayParametros){
		
		$arrayDocumentos = $this->lNegocioDocumentosAdjuntos->buscarLista($arrayParametros);
		
		$this->documentos = '<table style="width: 100%;">
								<tr>
										<td><label>#</label></td>
										<td><label>Nombre</label></td>
										<td><label>Enlace</label></td>
									</tr>';
		$i = 1;
		foreach ($arrayDocumentos as $documento) {
			$this->documentos .= '<tr>
						  			<td>'.$i.'</td>
									<td>'.$documento['tipo_archivo'].'</td>
									<td>
										<a href="'.$documento['ruta_archivo'].'" target="_blank">Archivo</a>
									</td>
						 		</tr>';
			$i++;
		}
		
		$this->documentos .= '</table>';
		
	}
	
	/**
	 * Método para obtener los tipos de solicitud del formulario
	 */
	private function obtenerTipoSolicitudes() {
		
		$arrayTipoSolicitudes = array('Solicitud de importación de materias primas, productos que se comercializan con el nombre del compuesto químico o nombre genérico',
			'Solicitud de importación de muestras sin valor comercial',
			'Solicitud de importación de producto terminado',
			'Solicitud de importación de para consumo propio');
		
		foreach ($arrayTipoSolicitudes as $solicitudes){
			$this->tipoSolicitud[] =  '<option value="'.$solicitudes. '" >'. $solicitudes .'</option>';
		}
		
	}
	
	/**
	 * Método para verificar el estado del formulario
	 */
	private function obtenerEstado($estado) {
		
		switch ($estado){
			case 'enviado':
				$estado = 'Revisión documental';
				$this->tipoPagina = 'abrir';
			break;
			case 'subsanacion':
				$estado = 'Subsanación';
				$this->tipoPagina = 'editar';
			break;
			default:
				$estado = ucfirst($estado);
				$this->tipoPagina = 'abrir';
		}
		
		return $estado;
	}
	
	/**
	 * Método para imprimir la observación del técnico en el formulario
	 */
	private function panelResultadoRevision(){
		
		if(!empty($this->modeloImportacionesFertilizantes->getObservacionTecnico())){
			
			$estado = $this->obtenerEstado($this->modeloImportacionesFertilizantes->getEstado());
			
			$this->resultadoRevision = '<fieldset>
											<legend>Resultado revisión</legend>
												<div data-linea="1">
													<label>Observación: </label>'.$this->modeloImportacionesFertilizantes->getObservacionTecnico().'
												</div>
														
												<div data-linea="2">
													<label>Fecha: </label>'.date("Y-m-d H:i",strtotime($this->modeloImportacionesFertilizantes->getFechaInicio())).'
												</div>
														
												<div data-linea="3">
													<label>Estado: </label>'.$estado.'
												</div>
										</fieldset>';
		}
	}
	
	/**
	 * Obtener datos para generación de filas para los productos del permiso de importación desde vista.
	 */
	public function obtenerDatosFilaProducto(){
		
		$arrayParametros = array(
			'nombre_comercial_producto' => $_POST['nombre_comercial_producto'],
			'nombre_producto_origen' => $_POST['nombre_producto_origen'],
			'numero_registro' => $_POST['numero_registro'],
			'composicion' => $_POST['composicion'],
			'cantidad' => $_POST['cantidad'],
			'peso_neto' => $_POST['peso_neto'],
			'partida_arancelaria' => $_POST['partida_arancelaria']
		);
		
		$registroProducto = $this->generarFilaProducto($arrayParametros, true);
		
		echo json_encode(array('estado' => 'EXITO', 'mensaje' => $registroProducto));
	}
	
	/**
	 * Obtener datos para generación de filas para los productos del permiso de importación desde funcion abrir.
	 */
	public function generarListaProducto($listaProductos, $proceso=false){
		
		$registroProducto = null;
		
		foreach ($listaProductos as $producto){
			
			$arrayParametros = array(
				'nombre_comercial_producto' => $producto['nombre_comercial_producto'],
				'nombre_producto_origen' => $producto['nombre_producto_origen'],
				'numero_registro' => $producto['numero_registro'],
				'composicion' => $producto['composicion'],
				'cantidad' => $producto['cantidad'],
				'peso_neto' => $producto['peso_neto'],
				'partida_arancelaria' => $producto['partida_arancelaria']
			);
			
			$registroProducto .= $this->generarFilaProducto($arrayParametros, $proceso);
		}
				
		return $registroProducto;
		
	}
	
	
	/**
	 * Generación de filas para los productos del permiso de importación.
	 */
	private function generarFilaProducto($arrayParametros, $proceso){
		
		if($proceso){
			
			$registroFila = mt_rand();
			
			$registroProducto = '<tr id="r_'.$registroFila.'">
								<td>'.$arrayParametros['nombre_comercial_producto'].'<input name="nombre_comercial_producto[]" value="'.$arrayParametros['nombre_comercial_producto'].'" type="hidden"></td>
								<td>'.$arrayParametros['numero_registro'].'<input name="numero_registro[]" value="'.$arrayParametros['numero_registro'].'" type="hidden"></td>
								<td>'.$arrayParametros['cantidad'].'<input name="cantidad[]" value="'.$arrayParametros['cantidad'].'" type="hidden"></td>
								<td>'.$arrayParametros['peso_neto'].'<input name="peso_neto[]" value="'.$arrayParametros['peso_neto'].'" type="hidden"></td>
								<td>'.$arrayParametros['partida_arancelaria'].'<input name="partida_arancelaria[]" value="'.$arrayParametros['partida_arancelaria'].'" type="hidden"></td>
								<td><button type="button" onclick="quitarProductos(r_'.$registroFila.')" class="menos">Quitar</button></td>
								<input name="nombre_producto_origen[]" value="'.$arrayParametros['nombre_producto_origen'].'" type="hidden">
								<input name="composicion[]" value="'.$arrayParametros['composicion'].'" type="hidden">
							</tr>';
		}else{
			
			$columnasModificables = '<td>'.$arrayParametros['nombre_comercial_producto'].'</td>
								     <td>'.$arrayParametros['numero_registro'].'</td>
									 <td>'.$arrayParametros['cantidad'].'</td>
									 <td>'.$arrayParametros['peso_neto'].'</td>
									 <td>'.$arrayParametros['partida_arancelaria'].'</td>';
			
			if($this->modeloImportacionesFertilizantes->getTipoSolicitud() == 'Solicitud de importación de materias primas, productos que se comercializan con el nombre del compuesto químico o nombre genérico' && $this->modeloImportacionesFertilizantes->getEstado() == 'aprobado'){
				$columnasModificables = '<td>'.$arrayParametros['nombre_comercial_producto'].'<input name="nombre_comercial_producto[]" value="'.$arrayParametros['nombre_comercial_producto'].'" type="hidden"></td>
										 <td>'.$arrayParametros['numero_registro'].'<input name="numero_registro[]" value="'.$arrayParametros['numero_registro'].'" type="hidden"></td>
										 <td><input name="cantidad[]" value="'.$arrayParametros['cantidad'].'" type="text" class="validacionProducto"></td>
										 <td><input name="peso_neto[]" value="'.$arrayParametros['peso_neto'].'" type="number" class="validacionProducto"></td>
										 <td>'.$arrayParametros['partida_arancelaria'].'<input name="partida_arancelaria[]" value="'.$arrayParametros['partida_arancelaria'].'" type="hidden"></td>
										 <input name="nombre_producto_origen[]" value="'.$arrayParametros['nombre_producto_origen'].'" type="hidden">
										 <input name="composicion[]" value="'.$arrayParametros['composicion'].'" type="hidden">';
				
				$this->botonActualizar = '<div data-linea="10">
												<button type="submit" class="guardar">Actualizar</button>
										</div>';
			}
			
			$registroProducto = '<tr>'.$columnasModificables.'</tr>';
		}
		
		

	return $registroProducto;

	}
}

