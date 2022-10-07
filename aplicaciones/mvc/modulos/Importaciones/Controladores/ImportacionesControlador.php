<?php
/**
 * Controlador Importaciones
 *
 * Este archivo controla la lógica del negocio del modelo: ImportacionesModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2021-07-06
 * @uses ImportacionesControlador
 * @package Importaciones
 * @subpackage Controladores
 */
namespace Agrodb\Importaciones\Controladores;

use Agrodb\Catalogos\Modelos\CodigosAdicionalesPartidasLogicaNegocio;
use Agrodb\Catalogos\Modelos\CodigosInocuidadLogicaNegocio;
use Agrodb\Catalogos\Modelos\ProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\PuertosLogicaNegocio;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\Importaciones\Modelos\ImportacionesLogicaNegocio;
use Agrodb\Importaciones\Modelos\ImportacionesModelo;
use Agrodb\Importaciones\Modelos\ImportacionesProductosLogicaNegocio;
use Agrodb\Importaciones\Modelos\ImportacionesProductosModelo;
use Agrodb\RegistroOperador\Modelos\OperadoresLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\OperadoresModelo;

class ImportacionesControlador extends BaseControlador{

	private $lNegocioImportaciones = null;

	private $modeloImportaciones = null;

	private $lNegocioImportacionesProductos = null;

	private $modeloImportacionesProductos = null;

	private $lNegocioOperadores = null;

	private $modeloOperadores = null;

	private $accion = null;

	private $productos = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();

		$this->lNegocioImportaciones = new ImportacionesLogicaNegocio();
		$this->modeloImportaciones = new ImportacionesModelo();

		$this->lNegocioOperadores = new OperadoresLogicaNegocio();
		$this->modeloOperadores = new OperadoresModelo();

		$this->lNegocioImportacionesProductos = new ImportacionesProductosLogicaNegocio();
		$this->modeloImportacionesProductos = new ImportacionesProductosModelo();

		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloImportaciones = $this->lNegocioImportaciones->buscarImportaciones();
		$this->tablaHtmlImportaciones($modeloImportaciones);
		require APP . 'Importaciones/vistas/listaImportacionesVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo Importaciones";
		require APP . 'Importaciones/vistas/formularioImportacionesVista.php';
	}

	/**
	 * Método para registrar en la base de datos -Importaciones
	 */
	public function guardar(){
		
		$_POST['tipo_transporte'] = strtoupper($this->quitarTildes($_POST['tipo_transporte']));
		$_POST['identificador_rectificacion'] = $this->usuarioActivo();
		
		$procesoValidacion = $this->lNegocioImportaciones->guardar($_POST);
		if($procesoValidacion[0]){
			Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
		}else{
			Mensajes::fallo($procesoValidacion[1]);
		}
		
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: Importaciones
	 */
	public function editar(){
		$this->accion = "Rectificar importación";
		$idImportacion = $_POST["id"];
		$this->modeloImportaciones = $this->lNegocioImportaciones->buscar($_POST["id"]);
		$this->modeloOperadores = $this->lNegocioOperadores->buscar($this->modeloImportaciones->getIdentificadorOperador());
		$productosImportacion = $this->lNegocioImportacionesProductos->buscarLista(array(
			'id_importacion' => $idImportacion));
		$this->listaProductosImportacion($productosImportacion, $this->modeloImportaciones->getIdArea());
		require APP . 'RevisionSolicitudesVue/vistas/formularioRevisionSolicitudesVueImportacionesVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - Importaciones
	 */
	public function borrar(){
		$this->lNegocioImportaciones->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - Importaciones
	 */
	public function tablaHtmlImportaciones($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_importacion'] . '"
							  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Importaciones\importaciones"
							  data-opcion="editar" ondragstart="drag(event)" draggable="true"
							  data-destino="detalleItem">
							  <td>' . ++ $contador . '</td>
							  <td style="white - space:nowrap; "><b>' . $fila['identificador_operador'] . '</b></td>
					<td>' . $fila['tipo_certificado'] . '</td>
					<td>' . $fila['pais_exportacion'] . '</td>
					<td>' . ucfirst($fila['estado']) . '</td>
					</tr>');
			}
		}
	}

	/**
	 * Desplegar la lista de Importaciones
	 */
	public function listarImportacion(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$idVue = $_POST['id_vue'];

		$query = "id_vue = '$idVue' and estado IN ('ampliado','aprobado')";

		$importacion = $this->lNegocioImportaciones->buscarLista($query);

		if ($importacion->count()){
			$this->tablaHtmlImportaciones($importacion);
			$contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
		}else{
			$contenido = \Zend\Json\Json::encode('');
			$mensaje = 'No existen registros';
			$estado = 'FALLO';
		}

		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido));
	}

	/**
	 * Desplegar la lista de productos de importacion
	 */
	public function listaProductosImportacion($productosImportacion, $idArea){
		$contador = 0;
		$datosCodigosInocuidad = '';

		$lNegocioProducto = new ProductosLogicaNegocio();
		$lNegocioCodigosAdicionales = new CodigosAdicionalesPartidasLogicaNegocio();

		foreach ($productosImportacion as $producto){

			$partidaArancelaria = substr($producto['partida_producto_vue'], 0, 10);
			$codigoComplementarioSuplementario = substr($producto['partida_producto_vue'], 10, 8);
			//$codigoSuplementario = substr($producto['partida_producto_vue'], 14, 4);
			$codigoInocuidad = substr($producto['codigo_producto_vue'], 5, 4);

			$datosProducto = $lNegocioProducto->comboProductoImportacion($producto['id_producto'], $partidaArancelaria);
			$datosCodigosAdicionales = $lNegocioCodigosAdicionales->comboCodigosAdicionalesPartida($producto['id_producto'], $codigoComplementarioSuplementario);

			if ($idArea == 'IAV' || $idArea == 'IAP'){
				$lNegociCodigosInocuidad = new CodigosInocuidadLogicaNegocio();
				$datosCodigosInocuidad = $lNegociCodigosInocuidad->comboCodigosInocuidad($producto['id_producto'], $codigoInocuidad);
			}

			$this->productos .= '<fieldset>
									<legend>Producto de importación ' . ++ $contador . '</legend>

									<input type="hidden" name="id_importacion_producto[]" value="' . $producto['id_importacion_producto'] . '"/>
									<input type="hidden" name="id_producto[]" value="' . $producto['id_producto'] . '"/>

									<div data-linea="1">
										<label>Nombre del producto: </label> ' . $producto['nombre_producto'] . '
									</div>
									<div data-linea="2">
										<label>Partida arancelaria: </label>
										<select name="partida_arancelaria[]" class="validacion">
											<option value="">Seleccionar....</option>
											' . $datosProducto['select_producto'] . '
										</select>
									</div>
									<div data-linea="2">
										<label>Código producto: </label>
										<input type="text" name="codigo_producto_vue[]" value="A' . $datosProducto['codigo_producto'] . '" readonly/>
									</div>
									<div data-linea="3">
										<label>Código complementario/suplementario: </label>
										<select name="codigo_complementario_suplementario[]" class="validacion">
											<option value="">Seleccionar....</option>
											' . $datosCodigosAdicionales . '
										</select>
									</div>
									' . $datosCodigosInocuidad . '
									<div data-linea="5">
										<label for="unidad">Cantidad:</label>
											<input type="number" step="any" name="unidad[]" value="' . $producto['unidad'] . '" class="validacion"/>
									</div>
									<div data-linea="5">
										<label for="unidad_medida">Unidad cantidad:</label>
											<select name="unidad_medida[]" class="validacion">
												<option value="">Seleccionar....</option>
												' . $this->comboUnidadesMedida($producto['unidad_medida']) . '
											</select>
									</div>
									<div data-linea="6">
										<label for="unidad">Peso:</label>
										<input type="number" step="any" name="peso[]" value="' . $producto['peso'] . '" class="validacion"/>
									</div>
									<div data-linea="6">
										<label>Unidad peso: </label>' . $producto['unidad_peso'] . '
									</div>
									<div data-linea="7">
										<label for="valor_cif">Valor CIF:</label>
										<input type="number" step="any" name="valor_cif[]" value="' . $producto['valor_cif'] . '" class="validacion"/>
									</div>
									<div data-linea="7">
										<label for="valor_fob">Valor FOB:</label>
										<input type="number" step="any" name="valor_fob[]" value="' . $producto['valor_fob'] . '" class="validacion"/>
									</div>
									
								</fieldset>';
		}
	}
	
	/**
	 * Carga combo de  la lista de productos de importacion
	 */
	public function buscarPuertosPorPaisMedioTransporte() {

		$medioTransporte = $_POST["medioTransporte"];
		$paisEmbarque = $_POST["paisEmbarque"];

		$lNegocioPuerto = new PuertosLogicaNegocio();

		$arrayParametrosEmbarque = array(
			'id_pais' => $paisEmbarque
		);

		$arrayParametrosEcuador = array(
			'codigo_pais' => 'EC'
		);

		if($medioTransporte != 'Fluvial'){
			$arrayParametrosEmbarque += ['tipo_puerto' => $medioTransporte];
			$arrayParametrosEcuador += ['tipo_puerto' => $medioTransporte];
		}

		$puertosEmbarque = $lNegocioPuerto->buscarLista($arrayParametrosEmbarque);
		
		$comboPuertosEmbarque = "";
		$comboPuertosEmbarque .= '<option value="">Seleccionar....</option>';
		
		foreach ($puertosEmbarque as $item){
			$comboPuertosEmbarque .= '<option value="' . $item->id_puerto . '" data-nombre="' . $item->nombre_puerto . '">['.$item->codigo_puerto.'] - ' . $item->nombre_puerto . '</option>';
		}
		

		$puertosEcuador = $lNegocioPuerto->buscarLista($arrayParametrosEcuador);

		$comboPuertosEcuador = "";
		$comboPuertosEcuador .= '<option value="">Seleccionar....</option>';

		foreach ($puertosEcuador as $item){
			$comboPuertosEcuador .= '<option value="' . $item->id_puerto . '" data-nombre="' . $item->nombre_puerto . '">['.$item->codigo_puerto.'] - ' . $item->nombre_puerto . '</option>';
		}

		echo json_encode(array('estado' => 'EXITO', 'comboPuertosEmbarque' => $comboPuertosEmbarque, 'comboPuertosEcuador' => $comboPuertosEcuador));
	}
}
