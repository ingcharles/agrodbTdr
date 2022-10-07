<?php
/**
 * Controlador Produccion
 *
 * Este archivo controla la lógica del negocio del modelo: ProduccionModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2019-04-03
 * @uses ProduccionControlador
 * @package MovilizacionSueros
 * @subpackage Controladores
 */
namespace Agrodb\MovilizacionSueros\Controladores;

use Agrodb\MovilizacionSueros\Modelos\ProduccionLogicaNegocio;
use Agrodb\MovilizacionSueros\Modelos\ProduccionModelo;

class ProduccionControlador extends BaseControlador{

	private $lNegocioProduccion = null;

	private $modeloProduccion = null;

	private $accion = null;

	private $panelBusqueda = null;

	private $btn = null;

	private $msg = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioProduccion = new ProduccionLogicaNegocio();
		$this->modeloProduccion = new ProduccionModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$fecha = date('Y-m-d');
		$arrayParametros = array(
			'identificador_operador' => $_SESSION['usuario'],
			'fechaInicio' => $fecha,
			'fechaFin' => $fecha);
		$modeloProduccion = $this->lNegocioProduccion->listarProduccionXIdentificadorOperador($arrayParametros);
		$this->tablaHtmlProduccion($modeloProduccion);
		$this->filtroProduccion($fecha);
		require APP . 'MovilizacionSueros/vistas/listarProduccionVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nueva Produccion";
		require APP . 'MovilizacionSueros/vistas/formularioProduccionVista.php';
	}

	/**
	 * Método para registrar en la base de datos -Produccion
	 */
	public function guardar(){
		$this->lNegocioProduccion->guardar($_POST);
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: Produccion
	 */
	public function editar(){
		$this->btn = "no";
		$this->accion = "Detalle Produccion";
		$this->modeloProduccion = $this->lNegocioProduccion->buscar($_POST["id"]);
		require APP . 'MovilizacionSueros/vistas/eliminarFormularioProduccionVista.php';
	}

	/**
	 * Obtenemos los datos del registro seleccionado para eliminar - Tabla: Produccion
	 */
	public function eliminar(){
		$this->accion = "Eliminar Produccion";
		if (empty($_POST["elementos"])){
			$this->msg = 'Debe seleccionar un registro.';
			require APP . 'MovilizacionSueros/vistas/errorFormularioVista.php';
		}else{
			$arrayParametros = array(
				'identificador_operador' => $_SESSION['usuario'],
				'idProduccion' => $_POST["elementos"]);
			$produccion = $this->lNegocioProduccion->listarProduccionXIdentificadorOperador($arrayParametros);
			$this->modeloProduccion->setOptions((array) $produccion->current());

			require APP . 'MovilizacionSueros/vistas/eliminarFormularioProduccionVista.php';
		}
	}

	/**
	 * Método para borrar un registro en la base de datos - Produccion
	 */
	public function borrar(){
		// $this->lNegocioProduccion->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - Produccion
	 */
	public function tablaHtmlProduccion($tabla){
		$contador = 0;
		foreach ($tabla as $fila){
			$this->itemsFiltrados[] = array(
				'<tr id="' . $fila['id_produccion'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'MovilizacionSueros\produccion"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . date('Y-n-j', strtotime($fila['fecha_produccion_suero'])) . '</b></td>
		  <td>' . $fila['cantidad_leche_acopio'] . '</td>
		  <td>' . $fila['cantidad_queso_produccion'] . '</td>
		  <td>' . $fila['cantidad_suero_produccion'] . '</td>
          <td>' . $fila['cantidad_suero_restante'] . '</td>
		  </tr>');
			//
		}
	}

	/**
	 * Método para listar la produccion por operador por fecha
	 */
	public function listarProduccionFiltro(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$fechaInicio = $_POST["fechaInicio"];
		$fechaFin = $_POST["fechaFin"];
		$arrayParametros = array(
			'identificador_operador' => $_SESSION['usuario'],
			'fechaInicio' => $fechaInicio,
			'fechaFin' => $fechaFin);
		$modeloProduccion = $this->lNegocioProduccion->listarProduccionXIdentificadorOperador($arrayParametros);

		$this->tablaHtmlProduccion($modeloProduccion);
		$contenido = \Zend\Json\Json::encode($this->itemsFiltrados);

		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido));
	}
	/**
	 * Consulta los productos de productos de tipo de operación "industria láctea" - tipo productos "lacteos" - subtipo producto "queso"
	 *
	 * @param
	 *        	String
	 * @return string Código html para llenar el combo de id_producto_queso
	 */
	public function comboProducto($identificador, $codificacionSubtipoProduc, $idProducto){
		$arrayParametros = array(
			'identificador_operador' => $identificador,
			'codificacion_subtipoprod' => $codificacionSubtipoProduc);
		$combo = $this->lNegocioProduccion->obtenerProductoXIdSubtipoProductoXIdentificadorOperador($arrayParametros);
		$opcionesHtml = '';
		foreach ($combo as $item){
			if ($item['id_producto'] == $idProducto){
				$opcionesHtml .= '<option value="' . $item['id_producto'] . '" selected = "selected">' . $item['nombre_producto'] . '</option>';
			}else{
				$opcionesHtml .= '<option value="' . $item['id_producto'] . '">' . $item['nombre_producto'] . '</option>';
			}
		}
		echo $opcionesHtml;
	}
	/**
	 * Consulta los productos de productos de tipo de operación "industria láctea" - tipo productos "lacteos" - subtipo producto "queso"
	 *
	 * @param
	 *        	String
	 * @return string Código html para llenar el combo de id_producto_queso
	 */
	public function buscarComboProducto(){
		$arrayParametros = array(
			'identificador_operador' => $_SESSION['usuario'],
			'codificacion_subtipoprod' => $_POST['idTipoProducto']);
		$combo = $this->lNegocioProduccion->obtenerProductoXIdSubtipoProductoXIdentificadorOperador($arrayParametros);
		$opcionesHtml = '<option value="">Seleccionar...</option>';
		foreach ($combo as $item){
			if ($item['id_producto'] == ''){
				$opcionesHtml .= '<option value="' . $item['id_producto'] . '" selected = "selected">' . $item['nombre_producto'] . '</option>';
			}else{
				$opcionesHtml .= '<option value="' . $item['id_producto'] . '">' . $item['nombre_producto'] . '</option>';
			}
		}
		echo $opcionesHtml;
	}

	/**
	 * Consulta los sub productos de productos de tipo de operación "industria láctea" - tipo productos "lacteos" - subtipo producto "queso"
	 *
	 * @param
	 *        	String
	 * @return string Código html para llenar el combo de id_producto_queso
	 */
	public function comboSubProducto($identificador, $codificacionSubtipoProduc, $codigo){
		$arrayParametros = array(
			'identificador_operador' => $identificador,
			'codificacion_subtipoprod' => $codificacionSubtipoProduc);
		$combo = $this->lNegocioProduccion->obtenerProductoXIdSubtipoProductoXIdentificadorOperador($arrayParametros);
		$opcionesHtml = '';

		foreach ($combo as $item){
			$arrayDatos[] = array(
				'codigo' => $item['codigo'],
				'sub_tipo' => $item['sub_tipo']);
		}
		$result = array_unique($arrayDatos, SORT_REGULAR);
		
		foreach ($result as $item){
			if ($item['codigo'] == $codigo){
				$opcionesHtml .= '<option value="' . $item['codigo'] . '" selected = "selected">' . $item['sub_tipo'] . '</option>';
			}else{
				$opcionesHtml .= '<option value="' . $item['codigo'] . '">' . $item['sub_tipo'] . '</option>';
			}
		}
		return $opcionesHtml;
	}

	public function filtroProduccion($fechaActual){
		$this->panelBusqueda = '<table class="filtro" style="width: 100%;">
                                            	<tbody>
	                                                <tr>
	                                                    <th colspan="2">Buscar:</th>
	                                                </tr>
	                            					<tr  style="width: 100%;">
	                            						<td >Fecha inicio: </td>
	                            						<td>
	                            							<input id="fecha_inicio" type="text" name="fecha_inicio"  value="' . $fechaActual . '" readonly>
	                            						</td>
	                            					</tr>
	                                                <tr  style="width: 100%;">
	                            						<td >Fecha fin: </td>
	                            						<td>
	                            							<input id="fecha_fin" type="text" name="fecha_fin" value="' . $fechaActual . '" >
	                            						</td>
	                            					</tr>
	                            								
                            						<td colspan="2" style="text-align: end;">
                            							<button id="btnFiltrar">Filtrar lista</button>
                            						</td>
                            					</tr>
                            				</tbody>
                            			</table>';
	}
}

