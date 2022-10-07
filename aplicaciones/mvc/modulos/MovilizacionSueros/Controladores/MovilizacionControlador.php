<?php
/**
 * Controlador Movilizacion
 *
 * Este archivo controla la lógica del negocio del modelo: MovilizacionModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2019-04-03
 * @uses MovilizacionControlador
 * @package MovilizacionSueros
 * @subpackage Controladores
 */
namespace Agrodb\MovilizacionSueros\Controladores;

use Agrodb\MovilizacionSueros\Modelos\MovilizacionLogicaNegocio;
use Agrodb\MovilizacionSueros\Modelos\MovilizacionModelo;
use Agrodb\MovilizacionSueros\Modelos\MovilizacionDetalleLogicaNegocio;
use Agrodb\MovilizacionSueros\Modelos\MovilizacionDetalleModelo;
use Agrodb\MovilizacionSueros\Modelos\DetalleCantidadSueroLogicaNegocio;
use Agrodb\MovilizacionSueros\Modelos\DetalleCantidadSueroModelo;
use Agrodb\Core\ValidarIdentificador;


class MovilizacionControlador extends BaseControlador{

	private $lNegocioMovilizacion = null;

	private $modeloMovilizacion = null;

	private $lNegocioMovilizacionDetalle = null;

	private $modeloMovilizacionDetalle = null;

	private $lNegocioDetalleCantidadSuero = null;

	private $modeloDetalleCantidadSuero = null;

	private $accion = null;

	private $areas = null;

	private $sitios = null;

	private $uso = null;

	private $productos = null;

	private $arrayDetalle = Array();

	private $controladorReporte = null;

	private $urlPdf = null;

	private $validarIdentificador = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioMovilizacion = new MovilizacionLogicaNegocio();
		$this->modeloMovilizacion = new MovilizacionModelo();
		$this->lNegocioMovilizacionDetalle = new MovilizacionDetalleLogicaNegocio();
		$this->modeloMovilizacionDetalle = new MovilizacionDetalleModelo();
		$this->lNegocioDetalleCantidadSuero = new DetalleCantidadSueroLogicaNegocio();
		$this->modeloDetalleCantidadSuero = new DetalleCantidadSueroModelo();
		$this->validarIdentificador = new ValidarIdentificador();

		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$arrayIndex = "identificador_operador='" . $_SESSION['usuario'] . "' order by 1";
		$modeloMovilizacion = $this->lNegocioMovilizacion->buscarLista($arrayIndex);
		$this->tablaHtmlMovilizacion($modeloMovilizacion);
		require APP . 'MovilizacionSueros/vistas/listarMovilizacionVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo Certificado de Movilización";
		$this->uso = $this->comboUsoSuero('activo');
		$this->sitios = $this->comboSitios($_SESSION['usuario']);
		require APP . 'MovilizacionSueros/vistas/formularioMovilizacionVista.php';
	}

	/**
	 * Método para registrar en la base de datos -Movilizacion
	 */
	public function guardar(){
		try{
			$this->modelo = new MovilizacionModelo();
			$proceso = $this->modelo->getAdapter()
				->getDriver()
				->getConnection();
			if (! $proceso->beginTransaction()){
				throw new \Exception('No se pudo iniciar la transacción en: Guardar movilizacion de suero');
			}
			$transporte = explode("-", $_POST['identificador_operador_transportista']);
			$_POST['identificador_operador_transportista'] = $transporte[0];
			$idMovilizacion = $this->lNegocioMovilizacion->guardar($_POST);
			$arrayDetalle = $this->unificarDatosArray($_POST['listDetalleProducto'], $idMovilizacion);
			foreach ($arrayDetalle as $item){
				$this->lNegocioMovilizacionDetalle->guardar($item);
				$this->actualizarCantidadSuero($item);
			}
			$proceso->commit();
			$this->crearCertificadoMovilizacionSuero($_POST, $arrayDetalle, $idMovilizacion, $transporte[1]);
		}catch (\Exception $ex){
			$proceso->rollback();
			throw new \Exception($ex->getMessage());
		}
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: Movilizacion
	 */
	public function editar(){
		$this->accion = "Editar Movilizacion";
		$this->modeloMovilizacion = $this->lNegocioMovilizacion->buscar($_POST["id"]);
		require APP . 'MovilizacionSueros/vistas/formularioMovilizacionVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - Movilizacion
	 */
	public function borrar(){
		$this->lNegocioMovilizacion->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - Movilizacion
	 */
	public function tablaHtmlMovilizacion($tabla){
		$contador = 0;
		foreach ($tabla as $fila){

			$arrayParametros = array(
				'identificador_operador' => $_SESSION['usuario'],
				'idSitio' => $fila['id_sitio_origen'],
				'idArea' => $fila['id_area_origen']);
			$sitioOrigen = $this->lNegocioMovilizacion->obtenerSitioOrigen($arrayParametros);
			if ($fila['estado'] == 'Vigente'){
				$item = 'item';
			}else{
				$item = '';
			}
			$this->itemsFiltrados[] = array(
				'<tr id="' . $fila['ruta_certificado'] . '"
		  class="' . $item . '" data-rutaAplicacion="' . URL_MVC_FOLDER . 'MovilizacionSueros\movilizacion"
		  data-opcion="visorPdf" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['codigo_certificado'] . '</b></td>
		  <td>' . $sitioOrigen->current()->nombre_lugar . '</td>
		  <td>' . $fila['estado'] . '</td>
		  </tr>');
		}
	}

	/**
	 * Construye el código HTML para desplegar el combo de sitios
	 */
	public function comboSitios($identificador){
		$arrayParametros = array(
			'identificador_operador' => $identificador);
		$combo = $this->lNegocioMovilizacion->obtenerSitiosAreasXIdentificador($arrayParametros);
		$opcionesHtml = '';
		$areas = array();
		$sitios = array();
		foreach ($combo as $item){
			$sitios[] = array(
				'nombre_lugar' => $item['nombre_lugar'],
				'id_sitio' => $item['id_sitio'],
				'codigo_provincia' => $item['codigo_provincia']);
			$areas[] = array(
				'nombre_area' => $item['nombre_area'],
				'id_sitio' => $item['id_sitio'],
				'id_area' => $item['id_area']);
		}
		$this->areas = $areas;
		$input = array_unique($sitios, SORT_REGULAR);

		foreach ($input as $item){
			$opcionesHtml .= '<option value="' . $item['id_sitio'] . '-' . $item['codigo_provincia'] . '">' . $item['nombre_lugar'] . '</option>';
		}
		return $opcionesHtml;
	}

	/**
	 * Construye el código HTML para desplegar el combo de cantones
	 */
	public function buscarCantones(){
		echo $this->comboCantones($_POST['idProvincia']);
	}

	/**
	 * Construye el código HTML para desplegar el combo de parroquias
	 */
	public function buscarParroquias(){
		echo $this->comboParroquias($_POST['idCanton']);
	}

	/**
	 * Construye el código HTML para desplegar el combo de transportes
	 */
	public function buscarTransporte(){
		$bandera = 1;
		$arrayParametros = array(
			'identificador' => $_POST['idtransportista'],
			'razon_social' => strtoupper($_POST['transportista']));
		$combo = $this->lNegocioMovilizacion->obtenerTransporteXIdentificador($arrayParametros);
		$opcionesHtml = '<option value="">Seleccione...</option>';
		foreach ($combo as $item){
			$bandera = 0;
			if ($item['identificador'] == $_POST['idtransportista'] || $item['nombre_operador'] == strtoupper($_POST['transportista'])){
				$opcionesHtml .= '<option value="' . $item['identificador'] . '-' . $item['id_operador_tipo_operacion'] . '" selected = "selected">' . $item['nombre_operador'] . ' - PLaca: ' . $item['placa_vehiculo'] . '</option>';
			}else{
				$opcionesHtml .= '<option value="' . $item['identificador'] . '-' . $item['id_operador_tipo_operacion'] . '">' . $item['nombre_operador'] . ' - Placa: ' . $item['placa_vehiculo'] . '</option>';
			}
		}
		if ($bandera){
			$opcionesHtml = 'FALLO';
		}
		echo $opcionesHtml;
	}

	/**
	 * Construye el código HTML para desplegar el combo de productos
	 */
	public function buscarProductos(){
		$bandera = 1;
		$arrayParametros = array(
			'identificador_operador' => $_SESSION['usuario'],
			'codificacion_subtipoprod' => 'SUB_TIPO_IA_SUER',
			'id_area' => $_POST['idAreaOrigen']);
		$combo = $this->lNegocioMovilizacion->obtenerProductoXIdSubtipoProductoXIdentificadorOperador($arrayParametros);
		$opcionesHtml = '<option value="">Seleccione...</option>';
		foreach ($combo as $item){
			$bandera = 0;
			$opcionesHtml .= '<option value="' . $item['id_producto'] . '">' . $item['nombre_producto'] . '</option>';
		}
		if ($bandera){
			$opcionesHtml = 'FALLO';
		}
		echo $opcionesHtml;
	}

	/**
	 * Construye el código HTML para desplegar el combo de detalle uso suero
	 */
	public function buscarDetalleSuero(){
		
		$arrayParametros = array(
			'estado' => 'activo',
			'id_uso_suero' => $_POST['idUsoSuero']);
		$consulta = $this->lNegocioMovilizacion->obtenerDetalleUsoSuero($arrayParametros);

		$opcionesHtml = '<option value="">Seleccione...</option>';
		foreach ($consulta as $item){
			$bandera = 0;
			$opcionesHtml .= '<option value="' . $item['id_detalle_uso_suero'] . '">' . $item['descripcion'] . '</option>';
		}
		if ($bandera){
			$opcionesHtml = 'FALLO';
		}
		echo $opcionesHtml;
	}
	
	/**
	 * Construye el código HTML para desplegar el combo de transportes
	 */
	public function agregarproducto(){
		$arrayParametros = array(
			'identificador_operador' => $_SESSION['usuario'],
			'idProductoSuero' => $_POST['idProducto'],
			'producto' => $_POST['producto']);
		$cantidaSuero = $this->lNegocioMovilizacion->vericarCantidadSuero($arrayParametros);
		$html = "";
		$count = 0;
		if ($cantidaSuero->current()->total >= $_POST['total']){

			foreach ($_POST['arreglo'] as $item){
				$html .= "<tr id=" . $count . "><td>" . $item['producto'] . "</td><td class='" . $item['idProducto'] . "'>" . $item['cantidad'] . "</td><td><button class='bEliminar icono' onclick='eliminarTr(this); return false; '></button></td></tr>";
				$count ++;
			}
		}else{
			$html = 'FALLO';
		}
		echo $html;
	}

	/**
	 * Consulta los productos de productos de tipo de operación "industria láctea" - tipo productos "lacteos" - subtipo producto "queso"
	 *
	 * @param
	 *        	String
	 * @return string Código html para llenar el combo de id_producto_queso
	 */
	public function comboProducto($identificador, $codificacionSubtipoProduc, $idArea){
		$arrayParametros = array(
			'identificador_operador' => $identificador,
			'codificacion_subtipoprod' => $codificacionSubtipoProduc,
			'id_area' => $idArea);
		$combo = $this->lNegocioMovilizacion->obtenerProductoXIdSubtipoProductoXIdentificadorOperador($arrayParametros);
		$opcionesHtml = '';
		foreach ($combo as $item){
			if ($item['id_producto'] == $idArea){
				$opcionesHtml .= '<option value="' . $item['id_producto'] . '" selected = "selected">' . $item['nombre_producto'] . '</option>';
			}else{
				$opcionesHtml .= '<option value="' . $item['id_producto'] . '">' . $item['nombre_producto'] . '</option>';
			}
		}
		return $opcionesHtml;
	}

	/**
	 *
	 * @return string Código html para llenar el combo de del uso del suero
	 */
	public function comboUsoSuero($estado){
		$arrayParametros = array(
			'estado' => $estado);
		$combo = $this->lNegocioMovilizacion->obtenerUsoSuero($arrayParametros);
		$opcionesHtml = '';
		foreach ($combo as $item){
			$opcionesHtml .= '<option value="' . $item['id_uso_suero'] . '">' . $item['descripcion'] . '</option>';
		}
		return $opcionesHtml;
	}

	/**
	 *
	 * @return string obtener el código VUE
	 */
	public function obtenerCodigoProvincia($idLocalizacion){
		$arrayParametros = array(
			'id_localizacion' => $idLocalizacion);
		$consulta = $this->lNegocioMovilizacion->obtenerCodigoVueLocalizacion($arrayParametros);
		$codigo = $consulta->current()->codigo_vue;
		return $codigo;
	}

	/**
	 *
	 * @return string obtener razon social operador
	 */
	public function obtenerRazonSocial($identificador){
		$arrayParametros = array(
			'identificador' => $identificador);
		$consulta = $this->lNegocioMovilizacion->obtenerRazonSocial($arrayParametros);
		$codigo = $consulta->current()->razon_social;
		return $codigo;
	}

	/**
	 *
	 * @return array con la respuesta de la validación
	 */
	public function validarRuc(){
		$contador = strlen($_POST['identificador']);
		$mensaje = array();
		if ($contador == 10){
			$consulta = $this->validarIdentificador->validarIdentificador($_POST['identificador'], 'Cédula');
			echo json_encode($consulta);
		}else if ($contador == 13){
			$consulta = $this->validarIdentificador->validarIdentificador($_POST['identificador'], 'Natural');
			echo json_encode($consulta);
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'Número incorrecto de dígitos';
			echo json_encode($mensaje);
		}
	}

	/**
	 * extraer y unificar datos de un array
	 */
	public function unificarDatosArray($array, $idMovilizacion){
		$resultado = array();
		$resultadoFinal = array();
		$data = json_decode($array);
		foreach ($data as $item){
			$resultado[] = $item->idProducto;
		}
		$unificar = array_unique($resultado, SORT_REGULAR);
		foreach ($unificar as $item){
			$valor1 = $valor2 = '';
			$valor3 = 0;
			foreach ($data as $valor){
				if ($valor->idProducto == $item){
					$valor1 = $valor->idProducto;
					$valor2 = $valor->producto;
					$valor3 = $valor3 + $valor->cantidad;
				}
			}
			$resultadoFinal[] = array(
				'id_producto' => $valor1,
				'nombre_producto' => $valor2,
				'cantidad_producto' => $valor3,
				'id_movilizacion' => $idMovilizacion);
		}
		return $resultadoFinal;
	}

	/**
	 * descontar cantidad de suero
	 */
	public function actualizarCantidadSuero($arrayCantidad){
		$arrayParametros = array(
			'identificador_operador' => $_SESSION['usuario'],
			'idProductoSuero' => $arrayCantidad['id_producto'],
			'producto' => $arrayCantidad['nombre_producto']);
		$cantidadSuero = $this->lNegocioMovilizacion->vericarCantidadSuero($arrayParametros);

		$arrayParametrosConsumo = array(
			'identificador_operador' => $_SESSION['usuario'],
			'idProductoSuero' => $arrayCantidad['id_producto']);
		$detalleConsumoSuero = $this->lNegocioDetalleCantidadSuero->obtenerDetalleCantidadSuero($arrayParametrosConsumo);

		$cantidadPendiente = $arrayCantidad['cantidad_producto'];
		$cantidadDescontar = 0;

		foreach ($detalleConsumoSuero as $cantidadSuero){

			if ($cantidadPendiente > 0){
				if ($cantidadSuero['cantidad_suero_restante'] <= $cantidadPendiente){
					$cantidadPendiente = $cantidadPendiente - $cantidadSuero['cantidad_suero_restante'];
					$cantidadDescontar = $cantidadSuero['cantidad_suero_restante'];
					$estado = 'utilizado';
				}else{
					$cantidadDescontar = $cantidadPendiente;
					$cantidadPendiente -= $cantidadPendiente;
					$estado = 'pendiente';
				}
				$arrayParametros = array(
					'id_detalle_consumo_suero' => $cantidadSuero['id_detalle_consumo_suero'],
					'cantidad_suero_utilizado' => $cantidadDescontar,
					'estado' => $estado,
					'id_movilizacion' => $arrayCantidad['id_movilizacion']);
				$this->lNegocioDetalleCantidadSuero->guardar($arrayParametros);
			}
		}
	}

	/**
	 * Construye el código HTML para finalizar los certificados
	 */
	public function finalizarCertificadoMovilizacion(){
		$this->urlPdf = $_POST['id'];
		require APP . 'MovilizacionSueros/vistas/visorPDF.php';
	}

	/**
	 * Construye el código HTML para desplegar el combo de cantones
	 */
	public function visorPdf(){
		$this->urlPdf = $_POST['id'];
		require APP . 'MovilizacionSueros/vistas/visorPDF.php';
	}

	/**
	 * crear certificado de movilizacion
	 */
	public function crearCertificadoMovilizacionSuero($arrayDatos, $arrayDetalle, $idMovilizacion, $idTransporte){
		$arrayDetalleMovilizacion = array();
		$sitio = explode("-", $arrayDatos['id_sitio_origen']);
		$arrayParametros = array(
			'identificador_operador' => $_SESSION['usuario'],
			'idSitio' => $sitio[0],
			'idArea' => $arrayDatos['id_area_origen']);
		$provinciaOrigen = '';
		$parroquiaOrigen = '';
		foreach ($arrayDetalle as $item){
			$sitioOrigen1 = $this->lNegocioMovilizacion->obtenerSitioOrigen($arrayParametros);
			$sitioOrigen = $sitioOrigen1->current()->nombre_lugar;
			$provinciaOrigen = $sitioOrigen1->current()->provincia;
			$cantonOrigen = $sitioOrigen1->current()->canton;
			$parroquiaOrigen = $sitioOrigen1->current()->parroquia;
			$arrayDetalleMovilizacion[] = array(
				'origen' => $sitioOrigen,
				'producto' => $item['nombre_producto'],
				'cantidad' => $item['cantidad_producto'],
				'unidad' => 'lt');
		}

		// **************************************************************************************************
		$arraySecuencial = array(
			'identificador_operador' => $_SESSION['usuario']);
		$secuencial = $this->lNegocioMovilizacion->obtenerSecuencialMovilizacion($arraySecuencial);
		$secuencialCertificado = str_pad($secuencial->current()->numero, 6, "0", STR_PAD_LEFT);
		$codigoProvincia = $this->obtenerCodigoProvincia($arrayDatos['id_provincia']);
		$codigoProvincia = substr($codigoProvincia, 1, 2);
		$numeroCertificado = $arrayDatos['cod_provincia'] . '-' . $codigoProvincia . '-' . $secuencialCertificado . '-' . date('dmy');
		$numeroCertificadoNew = str_replace("-", "_", $numeroCertificado);
		$nombreArchivo = 'certificado_movilizacion_suero_' . $_SESSION['usuario'] . '_' . $numeroCertificadoNew;

		// **************************************************************************************************
		$fechaInicioVigencia = date("Y-m-d H:i:s");
		$fechaFinVigencia = strtotime('+1 day', strtotime($fechaInicioVigencia));
		$fechaFinVigencia = date("Y-m-d H:i:s", $fechaFinVigencia);
		// **************************************************************************************************

		$rutaArchivo = CERT_MOV_SUERO . "certificadosGenerados/" . $nombreArchivo . ".pdf";
		$this->urlPdf = $rutaArchivo;

		$arrayParametros = array(
			'id_movilizacion' => $idMovilizacion,
			'codigo_certificado' => $numeroCertificado,
			'ruta_certificado' => $rutaArchivo);
		$this->lNegocioMovilizacion->guardar($arrayParametros);

		// **************************************************************************************************

		$arrayDatosGenerales = array(
			'nombreArchivo' => $nombreArchivo,
			'nombreCertificado' => 'CERTIFICADO DE MOVILIZACIÓN TERRESTRE DE SUERO DE LECHE LÍQUIDA',
			'numeroCertificado' => $numeroCertificado,
			'lugarEmision' => $provinciaOrigen . ' - ' . $sitioOrigen,
			'fechaEmision' => $fechaInicioVigencia,
			'fechaInicioVigencia' => $fechaInicioVigencia,
			'fechaFinVigencia' => $fechaFinVigencia);
		// **************************************************************************************************
		$razonSocial = $this->obtenerRazonSocial($_SESSION['usuario']);

		$arrayDatosOrigen = array(
			'identificadorIndustria' => $_SESSION['usuario'],
			'razonSocial' => $razonSocial,
			'provincia' => $provinciaOrigen,
			'canton' => $cantonOrigen,
			'parroquia' => $parroquiaOrigen);

		// **************************************************************************************************
		$provincia1 = $this->lNegocioMovilizacion->obtenerNombreLocalizacion($arrayDatos['id_provincia']);
		$provincia = $provincia1->current()->nombre;

		$canton1 = $this->lNegocioMovilizacion->obtenerNombreLocalizacion($arrayDatos['id_canton']);
		$canton = $canton1->current()->nombre;

		$parroquia = '';
		if (array_key_exists('id_parroquia', $arrayDatos)){
			$consult = $this->lNegocioMovilizacion->obtenerNombreLocalizacion($arrayDatos['id_parroquia']);
			$parroquia = $consult->current()->nombre;
		}

		$arrayParametros = array(
			'estado' => 'activo',
			'id_uso_suero' => $arrayDatos['id_uso_suero']);
		$consulta = $this->lNegocioMovilizacion->obtenerUsoSuero($arrayParametros);
		$uso = $consulta->current()->descripcion;
		//**************************************************************************************************
		$arrayParametros = array(
			'estado' => 'activo',
			'id_detalle_uso_suero' => $arrayDatos['id_detalle_uso_suero']);
		$consulta = $this->lNegocioMovilizacion->obtenerDetalleUsoSuero($arrayParametros);
		$uso = $uso.'/'.$consulta->current()->descripcion;

		// **************************************************************************************************
		$arrayDatosDestino = array(
			'identificadorDestino' => $arrayDatos['identificador_operador_destino'],
			'razonSocial' => $arrayDatos['nombre_operador_destino'],
			'direccion' => $arrayDatos['direccion_operador_destino'],
			'provincia' => $provincia,
			'canton' => $canton,
			'parroquia' => $parroquia,
			'uso' => $uso);

		// **************************************************************************************************
		$arrayParametros = array(
			'identificador' => $arrayDatos['identificador_operador_transportista'],
			'id_operador_tipo_operacion' => $idTransporte);
		$transporte = $this->lNegocioMovilizacion->obtenerTransporteXIdentificador($arrayParametros);

		$nombreConductor = $transporte->current()->nombre_operador;
		$placaTransporte = $transporte->current()->placa_vehiculo;
		$codigoOperadorLeche = $transporte->current()->codigo_operador_leche;

		$arrayDatosMovilizacion = array(
			'identificadorConductor' => $arrayDatos['identificador_operador_transportista'],
			'nombreConductor' => $nombreConductor,
			'placaTransporte' => $placaTransporte,
			'numeroRegistroTransporte' => $codigoOperadorLeche);
		// **************************************************************************************************
		if ($this->usuarioInterno){
			$nombreOperador = $_SESSION['datosUsuario'];
		}else{
			$operador = $this->lNegocioMovilizacion->obtenerNombresOperador($_SESSION['usuario']);
			$nombreOperador = $operador->current()->operador;
		}
		$arrayDatosFoot = array(
			'identificadorEmision' => $_SESSION['usuario'],
			'nombreEmision' => $nombreOperador,
			'identificadorSolicitante' => $_SESSION['usuario'],
			'nombreSolicitante' => $nombreOperador,
			'numeroCertificado' => $numeroCertificado);

		$this->lNegocioMovilizacion->generarCertificadoMovilización($arrayDatosGenerales, $arrayDatosOrigen, $arrayDatosDestino, $arrayDatosMovilizacion, $arrayDatosFoot, $arrayDetalleMovilizacion);

		$estado = 'exito';
		$mensaje = 'Certificado generado con exito';
		$contenido = $this->urlPdf;
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido));
	}

	/**
	 * proceso automatico para eliminar certificados laborales
	 */
	public function procesoAutomatico(){
		echo "\n" . 'Proceso Automatico de finalización de certificados de movilización de sueros' . "\n" . "\n";
		$modeloCertificado = $this->lNegocioMovilizacion->obtenerCertificadoCaducado();
		foreach ($modeloCertificado as $fila){
			$arrayGuardar = array(
				'id_movilizacion' => $fila['id_movilizacion'],
				'estado' => 'caducado');
			$this->lNegocioMovilizacion->guardar($arrayGuardar);
			echo $fila['identificador_operador'] . '->certificado de movilización de suero se actualizado su estado (caducado)' . "\n";
		}
		echo "\n";
	}
}
