<?php
/**
 * Controlador EmisionCertificado
 *
 * Este archivo controla la lógica del negocio del modelo: EmisionCertificadoModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2020-09-18
 * @uses EmisionCertificadoControlador
 * @package EmisionCertificacionOrigen
 * @subpackage Controladores
 */
namespace Agrodb\EmisionCertificacionOrigen\Controladores;

use Agrodb\EmisionCertificacionOrigen\Modelos\EmisionCertificadoLogicaNegocio;
use Agrodb\EmisionCertificacionOrigen\Modelos\EmisionCertificadoModelo;
use Agrodb\EmisionCertificacionOrigen\Modelos\DetalleEmisionCertificadoLogicaNegocio;
use Agrodb\EmisionCertificacionOrigen\Modelos\DetalleEmisionCertificadoModelo;
use Agrodb\EmisionCertificacionOrigen\Modelos\RegistroProduccionLogicaNegocio;
use Agrodb\EmisionCertificacionOrigen\Modelos\RegistroProduccionModelo;
use Agrodb\EmisionCertificacionOrigen\Modelos\ProductosLogicaNegocio;
use Agrodb\EmisionCertificacionOrigen\Modelos\ProductosModelo;
use Agrodb\EmisionCertificacionOrigen\Modelos\SubproductosEmisionCertificadoLogicaNegocio;
use Agrodb\EmisionCertificacionOrigen\Modelos\SubproductosEmisionCertificadoModelo;
use Agrodb\RegistroOperador\Modelos\SitiosLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\SitiosModelo;
use Agrodb\Core\JasperReport;
use Agrodb\Core\Constantes;
use Zend\Filter\File\UpperCase;

class EmisionCertificadoControlador extends BaseControlador{

	private $lNegocioEmisionCertificado = null;

	private $modeloEmisionCertificado = null;

	private $lNegocioDetalleEmisionCertificado = null;

	private $modeloDetalleEmisionCertificado = null;

	private $lNegocioSubproductoEmisionCertificado = null;

	private $modeloSubproductoEmisionCertificado = null;

	private $lNegocioRegistroProduccion = null;

	private $modeloRegistroProduccion = null;

	private $lNegocioProductos = null;

	private $modeloProductos = null;

	private $lNegocioSitio = null;

	private $modeloSitio = null;

	private $accion = null;

	private $sitio = null;

	private $especie = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioEmisionCertificado = new EmisionCertificadoLogicaNegocio();
		$this->modeloEmisionCertificado = new EmisionCertificadoModelo();
		$this->lNegocioDetalleEmisionCertificado = new DetalleEmisionCertificadoLogicaNegocio();
		$this->modeloDetalleEmisionCertificado = new DetalleEmisionCertificadoModelo();
		$this->lNegocioRegistroProduccion = new RegistroProduccionLogicaNegocio();
		$this->modeloRegistroProduccion = new RegistroProduccionModelo();
		$this->lNegocioProductos = new ProductosLogicaNegocio();
		$this->modeloProductos = new ProductosModelo();
		$this->lNegocioSubproductoEmisionCertificado = new SubproductosEmisionCertificadoLogicaNegocio();
		$this->modeloSubproductoEmisionCertificado = new SubproductosEmisionCertificadoModelo();

		$this->lNegocioSitio = new SitiosLogicaNegocio();
		$this->modeloSitio = new SitiosModelo();

		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$this->filtroEmision();
		$modeloEmisionCertificado = $this->lNegocioEmisionCertificado->buscarLista("estado not in ('temporal','Notificar') and identificador_operador='" . $_SESSION['usuario'] . "' order by 1 DESC");
		$this->tablaHtmlEmisionCertificado($modeloEmisionCertificado);
		require APP . 'EmisionCertificacionOrigen/vistas/listaEmisionCertificadoVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$verificar = $this->lNegocioEmisionCertificado->buscarLista("estado ='temporal' and identificador_operador='" . $_SESSION['usuario'] . "'");
		if ($verificar->count()){
			$subValidar = $this->lNegocioDetalleEmisionCertificado->buscarLista("id_emision_certificado=" . $verificar->current()->id_emision_certificado);
			if ($subValidar->count()){
				foreach ($subValidar as $value){
					$this->lNegocioSubproductoEmisionCertificado->borrarPorParametro('id_detalle_emision_certificado', $value['id_detalle_emision_certificado']);
				}
			}
			// $this->lNegocioSubproductoEmisionCertificado->borrarPorParametro('id_emision_certificado',$verificar->current()->id_emision_certificado );
			$this->lNegocioDetalleEmisionCertificado->borrarPorParametro('id_emision_certificado', $verificar->current()->id_emision_certificado);
			$this->lNegocioEmisionCertificado->borrar($verificar->current()->id_emision_certificado);
		}
		$this->accion = "Nuevo Certificado sanitario de origen y movilización";
		$this->sitio = $this->comboSitio();
		$this->especie = $this->comboEspecie();
		require APP . 'EmisionCertificacionOrigen/vistas/formularioEmisionCertificadoVista.php';
	}

	/**
	 * Método para registrar en la base de datos -EmisionCertificado
	 */
	public function guardar(){
		$this->lNegocioEmisionCertificado->guardar($_POST);
	}

	/**
	 * Método para registrar en la base de datos -HistoriaClinica
	 */
	public function guardarRegistros(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		if ($_POST['contenedor'] == 'Si'){
			date_default_timezone_set('America/Guayaquil');
			$_POST['estado'] = 'Vigente';
			$nuevaFecha = strtotime('+24 hour', strtotime(date("Y-m-d H:i:s")));
			$fechaFinVigencia = date('Y-m-d H:i:s', $nuevaFecha);
			$_POST['fecha_vigencia'] = $this->fecha('', 3, $fechaFinVigencia);
			$_POST['fecha_emision'] = $this->fecha('', 3, date("Y-m-d H:i:s"));

			$id = $this->lNegocioEmisionCertificado->guardarRegistros($_POST);
			if ($id != ''){
				$this->modeloEmisionCertificado = $this->lNegocioEmisionCertificado->buscar($id);
				if ($_POST['tipo_especie'] == 'AVICOLA'){
					$rutaReporte = 'EmisionCertificacionOrigen/vistas/reportes/certificadoMovilizacionSanitariaOrigenMenor.jasper';
				}else{
					$rutaReporte = 'EmisionCertificacionOrigen/vistas/reportes/certificadoMovilizacionSanitariaOrigen.jasper';
				}
				$rutaCarpeta = EMI_CERT_ORIG_URL . "certificadosMovilizacion/" . $this->modeloEmisionCertificado->getIdentificadorOperador();
				if (! file_exists('../../' . $rutaCarpeta)){
					mkdir('../../' . $rutaCarpeta, 0777, true);
				}
				$nombre = 'certificado_movilizacion_origen_' . $this->modeloEmisionCertificado->getIdEmisionCertificado();
				$rutaArchivo = "certificadosMovilizacion/" . $this->modeloEmisionCertificado->getIdentificadorOperador() . "/" . $nombre;
				try{
					$jasper = new JasperReport();
					$datosReporte = array();
					$rutaArchivoBase = 'EmisionCertificacionOrigen/archivos/';
					$datosReporte = array(
						'rutaReporte' => $rutaReporte,
						'rutaSalidaReporte' => $rutaArchivoBase . $rutaArchivo,
						'tipoSalidaReporte' => array(
							'pdf'),
						'parametrosReporte' => array(
							'idEmisionCertificado' => $id,
							'fondoCertificado' => RUTA_IMG_GENE . 'fondoCertificado.png',
							'fondoSeguridad' => RUTA_IMG_GENE . 'logoSeguridadCSM.png',
							'rutaCertificado' => URL_MVC_MODULO.$rutaArchivoBase.$rutaArchivo . '.pdf'),
						'conexionBase' => 'SI');
					$validar = 1;
					$jasper->generarArchivo($datosReporte);
					$contenido = EMI_CERT_ORIG_URL . $rutaArchivo . '.pdf';
				}catch (\Exception $e){
					$validar = 0;
				}
				if ($validar){
					$arrayParametros = array(
						'id_emision_certificado' => $this->modeloEmisionCertificado->getIdEmisionCertificado(),
						'ruta_certificado' => $contenido);
					$id = $this->lNegocioEmisionCertificado->guardar($arrayParametros);
					if ($id){
						$mensaje = 'Registro agregado correctamente';
					}else{
						$estado = 'FALLO';
						$mensaje = 'Error al guardar el registro..!!';
					}
				}else{
					$estado = 'ERROR';
					$mensaje = 'Error al crear el archivo pdf';
				}
			}else{
				$estado = 'FALLO';
				$mensaje = 'Error al guardar el registro..!!';
			}
			// *******************enviar email**********************************
		}else{
			$_POST['estado'] = 'Notificar';
			$id = $this->lNegocioEmisionCertificado->guardarRegistros($_POST);
			if ($id){
				$this->modeloEmisionCertificado = $this->lNegocioEmisionCertificado->buscar($id);
				$datosTrasnporte = $this->lNegocioEmisionCertificado->buscarInfoTrasnportista($this->modeloEmisionCertificado->getIdDatoVehiculo());

				if ($datosTrasnporte->count()){
					$estado = 'EXITO';
					$arrayEmail = array(
						'identificador_operador' => $datosTrasnporte->current()->identificador,
						'nombre_operador' => $datosTrasnporte->current()->nombre_operador,
						'sitio' => $datosTrasnporte->current()->sitio,
						'provincia' => $datosTrasnporte->current()->provincia,
						'canton' => $datosTrasnporte->current()->canton,
						'parroquia' => $datosTrasnporte->current()->parroquia,
						'direccion' => $datosTrasnporte->current()->direccion,
						'operacion' => $datosTrasnporte->current()->operacion,
						'id_emision_certificado' => $id);
					$this->lNegocioEmisionCertificado->notificarEmail($arrayEmail);
					$mensaje = 'Email agregado correctamente';
				}else{
					$estado = 'FALLO';
					$mensaje = 'Error al enviar email..!!';
				}
			}else{
				$estado = 'FALLO';
				$mensaje = 'Error al guardar el registro..!!';
			}
		}
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $this->modeloEmisionCertificado->getIdEmisionCertificado()));
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: EmisionCertificado
	 */
	public function editar(){
		$this->accion = "Editar Emisión Certificado";
		$this->modeloEmisionCertificado = $this->lNegocioEmisionCertificado->buscar($_POST["id"]);
		require APP . 'EmisionCertificacionOrigen/vistas/formularioPDFVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - EmisionCertificado
	 */
	public function borrar(){
		$this->lNegocioEmisionCertificado->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - EmisionCertificado
	 */
	public function tablaHtmlEmisionCertificado($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->modeloSitio = $this->lNegocioSitio->buscar($fila['sitio_origen']);

				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_emision_certificado'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'EmisionCertificacionOrigen\emisionCertificado"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['numero_certificado'] . '</b></td>
            <td>' . $this->modeloSitio->getNombreLugar() . '</td>
            <td></td>
            <td>' . date('Y-m-d', strtotime($fila['fecha_creacion'])) . '</td>
            <td>' . $fila['estado'] . '</td>
            </tr>');
			}
		}
	}

	public function listarAreaCF(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$dato = explode('-', $_POST['idSitio']);
		$arrayParametros = array(
			'id_sitio' => $dato[0],
			'id_area_tipo_operacion' => 'AI',
			'codigo' => 'FAE');
		$consulta = $this->lNegocioEmisionCertificado->buscarAreaXSitioCentroFaenamiento($arrayParametros);
		$combo = '<option value="">Seleccionar...</option>';
		if ($consulta->count()){
			foreach ($consulta as $item){
				$combo .= '<option value="' . $item['id_area'] . '" >' . $item['nombre_area'] . '</option>';
			}
		}else{
			$estado = 'FALLO';
			$mensaje = 'No existen áreas para el sitio seleccionado..!!';
		}
		$contenido = $combo;

		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido));
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
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$datos = explode('-', $_POST['idCentroFaenamiento']);
		$arrayParametros = array(
			'identificador' => $_POST['idtransportista'],
			'razon_social' => strtoupper($_POST['transportista']),
			'id_centro_faenamiento' => $datos[2]);
		$combo = $this->lNegocioEmisionCertificado->obtenerTransporteXIdentificador($arrayParametros);
		$opcionesHtml = '<option value="">Seleccione...</option>';
		if ($combo->count()){
			foreach ($combo as $item){
				if($item['placa_vehiculo']!=""){
					if ($item['identificador'] == $_POST['idtransportista'] || $item['nombre_operador'] == strtoupper($_POST['transportista'])){
						$opcionesHtml .= '<option value="' . $item['identificador'] . '-' . $item['id_operador_tipo_operacion'] . '-' . $item['id_dato_vehiculo'] . '" selected = "selected">' . $item['nombre_operador'] . ' - Placa: ' . $item['placa_vehiculo'] . '</option>';
					}else{
						$opcionesHtml .= '<option value="' . $item['identificador'] . '-' . $item['id_operador_tipo_operacion'] . '-' . $item['id_dato_vehiculo'] . '">' . $item['nombre_operador'] . ' - Placa: ' . $item['placa_vehiculo'] . '</option>';
					}
				}else{
					
					if ($item['identificador'] == $_POST['idtransportista'] || $item['nombre_operador'] == strtoupper($_POST['transportista'])){
						$opcionesHtml .= '<option value="' . $item['identificador'] . '-' . $item['id_operador_tipo_operacion'] . '-' . $item['id_dato_vehiculo'] . '" selected = "selected">' . $item['nombre_operador'] . '</option>';
					}else{
						$opcionesHtml .= '<option value="' . $item['identificador'] . '-' . $item['id_operador_tipo_operacion'] . '-' . $item['id_dato_vehiculo'] . '">' . $item['nombre_operador'] . '</option>';
					}

				}
			}
		}else{
			$estado = 'FALLO';
			$mensaje = 'No existen datos para la busqueda realizada..!!';
		}
		$contenido = $opcionesHtml;
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido));
	}

	/**
	 * Construye el código HTML para desplegar el combo de transportes
	 */
	public function buscarCanal(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$valores = '';
		$canal = '';
		$arrayParametros = array(
			'identificador_operador' => $_SESSION['usuario'],
			'tipo_especie' => $_POST['tipoEspecie'],
			'fecha_faenamiento' => $_POST['fechaProduccion']);
		$modeloRegistroProduccion = $this->lNegocioRegistroProduccion->listarRegistroProduccion($arrayParametros);
		$combo = '<option value="">Seleccione...</option>';
		if ($modeloRegistroProduccion->count()){
			foreach ($modeloRegistroProduccion as $value){
				$combo .= '<option value="' . $value['id_productos'] . '-' . $value['codigo_canal'] . '">' . $value['codigo_canal'] . '</option>';
				$canal = $this->comboNumerosCanal($value['num_canales_obtenidos'], $value['id_productos']);
			}
			$valores = $this->listarProductoMovilizar($_POST['producto_movilizar'], $_POST['tipoEspecie']);
		}else{
			$estado = 'FALLO';
			$mensaje = 'No existen canales para la especie registrados..!!';
		}
		$contenido = $combo;
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido,
			'valores' => $valores,
			'canal' => $canal));
	}

	/**
	 * Construye el código HTML para desplegar el combo de transportes
	 */
	public function listarProductoMovilizar($tipo, $especie){
		$html = '';
		$htmlSub = '';
		if ($especie == 'AVICOLA'){
			switch ($tipo) {
				case 'Canal':
					$html .= '
		            <div data-linea="4">
			                  <label for="tipo_producto_movilizar_canal">Tipo producto a movilizar: </label>
			                 <select id="tipo_producto_movilizar_canal" name="tipo_producto_movilizar_canal" onchange="saldoDisponible(id); return false; ">
				         ' . $this->comboTipoProductoMov() . '
		                </select>
		                </div>				
                  <div data-linea="5">
            			<label for="saldo_disponible_spam">Saldo disponible: </label>
            			<spam id="saldo_disponible_spam"></spam>
            		</div>				
            
            		<div data-linea="6">
            			<label for="cantidad_movilizar">Cantidad a movilizar: </label>
            			<select id="cantidad_movilizar" name="cantidad_movilizar" >
            				<option value="">Seleccionar...</option>
            			</select>
            		</div>				
            		</div>';
				break;
				case 'Subproductos':
					$html .= '
            		<div data-linea="4">
            			<label for="subproducto">Subproducto: </label>
            			<select id="subproducto" name="subproducto" onclick="subproductobus(id); return false; ">
	                    
            			</select>
            		</div>
            		<div data-linea="5">
            			<label for="saldo_disponible_spam">Saldo disponible: </label>
            			<span id="saldo_disponible_spam"></spam>
            		</div>
	                    
            		<div data-linea="6">
            			<label for="cantidad_movilizar">Cantidad a movilizar: </label>
            			<select id="cantidad_movilizar" name="cantidad_movilizar" >
	                    
            			</select>
            		</div>	
                        ';
				break;
				case 'Canal con subproductos':
					$html .= '
		            <div data-linea="4">
			                  <label for="tipo_producto_movilizar_canal">Tipo producto a movilizar: </label>
			                 <select id="tipo_producto_movilizar_canal" name="tipo_producto_movilizar_canal" onchange="saldoDisponible(id); return false; ">
				         ' . $this->comboTipoProductoMov() . '
		                </select>
		                </div>
                  <div data-linea="5">
            			<label for="saldo_disponible_spam">Saldo disponible: </label>
            			<spam id="saldo_disponible_spam"></spam>
            		</div>
				             
            		<div data-linea="6">
            			<label for="cantidad_movilizar">Cantidad a movilizar: </label>
            			<select id="cantidad_movilizar" name="cantidad_movilizar" >
            				<option value="">Seleccionar...</option>
            			</select>
            		</div>
            		</div>';
				break;

				default:
					;
				break;
			}
			// /***************************especies mayores*****************
		}else{
			switch ($tipo) {
				case 'Canal':
					$html .= '
		                  <div data-linea="4">
			                  <label for="tipo_producto_movilizar_canal">Tipo producto a movilizar: </label>
			                 <select id="tipo_producto_movilizar_canal" name="tipo_producto_movilizar_canal" >
				         ' . $this->comboTipoProductoMov() . '
		                </select>
		                </div>
                   <div data-linea="5">
            			<label for="tipo_movilizacion_canal">Tipo movilización de la canal: </label>
            			<select id="tipo_movilizacion_canal" name="tipo_movilizacion_canal" onChange="tipoMovilizacionCanal(id); return false; ">
            				' . $this->comboTipoMovCanal() . '
            			</select>
            		</div>
		              <div data-linea="6">
            			<label for="codigo_canal">Código de la canal: </label>
            			<select id="codigo_canal" name="codigo_canal" disabled >
            				<option value="">Seleccionar...</option>
            			</select>
            		</div>
            		<div data-linea="7">
            			<label for="destino">Destino: </label>
            			<select id="destino" name="destino" >
            				' . $this->comboDestino() . '
            			</select>
            		</div>';
				break;
				case 'Subproductos':
					$html .= '
            		<div data-linea="4">
            			<label for="subproducto">Subproducto: </label>
            			<select id="subproducto" name="subproducto" onclick="subproductobus(id); return false; ">
	                    
            			</select>
            		</div>
	                    
            		<div data-linea="5">
            			<label for="saldo_disponible_spam">Saldo disponible: </label>
            			<span id="saldo_disponible_spam"></span>
            		</div>
	                    
            		<div data-linea="6">
            			<label for="cantidad_movilizar">Cantidad a movilizar: </label>
            			<select id="cantidad_movilizar" name="cantidad_movilizar" >
	                    
            			</select>
            		</div>
                        ';
				break;
				case 'Canal con subproductos':
					$html .= '
		                  <div data-linea="4">
			                  <label for="tipo_producto_movilizar_canal">Tipo producto a movilizar: </label>
			                 <select id="tipo_producto_movilizar_canal" name="tipo_producto_movilizar_canal" >
				         ' . $this->comboTipoProductoMov() . '
		                </select>
		                </div>
                   <div data-linea="5">
            			<label for="tipo_movilizacion_canal">Tipo movilización de la canal: </label>
            			<select id="tipo_movilizacion_canal" name="tipo_movilizacion_canal" onChange="tipoMovilizacionCanal(id); return false; ">
            				' . $this->comboTipoMovCanal() . '
            			</select>
            		</div>
		              <div data-linea="6">
            			<label for="codigo_canal">Código de la canal: </label>
            			<select id="codigo_canal" name="codigo_canal" disabled >
            				<option value="">Seleccionar...</option>
            			</select>
            		</div>
            		<div data-linea="7">
            			<label for="destino">Destino: </label>
            			<select id="destino" name="destino" >
            				' . $this->comboDestino() . '
            			</select>
            		</div>';
					$htmlSub .= '
                        <div data-linea="4">
            			<label for="subproducto">Subproducto: </label>
            			<select id="subproducto" name="subproducto" onclick="subproductobus(id); return false; ">
	                    
            			 </select>
            		     </div>
	                    
            		    <div data-linea="5">
            			  <label for="saldo_disponible_spam">Saldo disponible: </label>
            			  <spam id="saldo_disponible_spam"></spam>
            		   </div>
	                    
            		   <div data-linea="6">
            			  <label for="cantidad_movilizar">Cantidad a movilizar: </label>
            			  <select id="cantidad_movilizar" name="cantidad_movilizar" >
            			</select>
            		</div>';
				break;
				default:
					;
				break;
			}
		}
		return $html;
	}

	/**
	 * Construye el código HTML
	 */
	public function agregarProductosMovilizar(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$canal = '';
		$id = '';
		$canalSub = '';
		$total = 0;
		if (strtoupper($_POST['tipo_especie']) == strtoupper('AVICOLA')){

			switch ($_POST['producto_movilizar']) {
				case 'Canal':
				case 'Canal con subproductos':
					if ($_POST['id_emision_certificado'] != ''){
						$cantidaMovilizar = 0;
						$idEmisionCertificado = $_POST['id_emision_certificado'];
						$id = $idEmisionCertificado;
						$validardetalleEmision = $this->lNegocioDetalleEmisionCertificado->buscarLista("id_emision_certificado=" . $_POST['id_emision_certificado'] . " and tipo_producto_movilizar_canal='" . $_POST['tipo_producto_movilizar_canal'] . "'");
						if ($validardetalleEmision->count() > 0){
							$this->modeloProductos = $this->lNegocioProductos->buscar($_POST['id_productos']);
							if ($_POST['tipo_producto_movilizar_canal'] == 'Canales sin restricción de uso'){
								$totalPermitido = $this->modeloProductos->getNumCanalesObtenidosUso();
							}else{
								$totalPermitido = $this->modeloProductos->getNumCanalesUsoIndustri();
							}
							$saldoUtilizado = $this->lNegocioDetalleEmisionCertificado->buscarLista("id_emision_certificado=" . $_POST['id_emision_certificado'] . " and tipo_especie='" . $_POST['tipo_especie'] . "'");
							if ($saldoUtilizado->count() > 0){
								foreach ($saldoUtilizado as $value){
									$cantidaMovilizar = $cantidaMovilizar + $value['cantidad_movilizar'];
								}
								if (($cantidaMovilizar + $_POST['cantidad_movilizar']) < $totalPermitido){
									$idEmisionCertificado = $this->lNegocioEmisionCertificado->guardarProductosMovilizarMenor($_POST);
									if ($idEmisionCertificado > 0){
										$id = $idEmisionCertificado;
										$canalSub = $this->listarProductosCanalSub($idEmisionCertificado);
									}
								}else{
									$estado = 'FALLO';
									$mensaje = 'Cantidad a movilizar excele a la ingresada..!!';
								}
							}else{
							}
						}else{
							$estado = 'FALLO';
							$mensaje = 'Tipo de producto a movilizar no es igual al registrado..!!';
						}
					}else{
						$idEmisionCertificado = $this->lNegocioEmisionCertificado->guardarProductosMovilizarMenor($_POST);
						if ($idEmisionCertificado > 0){
							$id = $idEmisionCertificado;
							$canalSub = $this->listarProductosCanalSub($idEmisionCertificado);
						}
					}

					$_POST['fecha_faenamiento'] = $_POST['fecha_produccion'];
					$_POST['id_emision_certificado'] = $idEmisionCertificado;
					$saldo = $this->consultarSaldoDisponible($_POST);
					if ($saldo['estado'] == 'EXITO'){
						$canal = $saldo['contenido'];
						$total = $saldo['total'];
					}else{
						$estado = 'FALLO';
						$mensaje = $saldo['mensaje'];
					}
					$contenido = $this->listarProductosMenor($idEmisionCertificado);
				break;
				case 'Subproductos':
					$idEmisionCertificado = $this->lNegocioEmisionCertificado->guardarProductosMovilizar($_POST);
					if ($idEmisionCertificado > 0){
						$id = $idEmisionCertificado;
						$contenido = $this->listarSubproductos($idEmisionCertificado);
					}
				break;
				default:
					;
				break;
			}

		/**
		 * ***********************especies mayores**
		 */
			// ///
		}else{
			switch ($_POST['producto_movilizar']) {
				case 'Canal':
				case 'Canal con subproductos':
					$totalPermitido = 0;
					$id = explode('-', $_POST['codigo_canal']);
					$this->modeloProductos = $this->lNegocioProductos->buscar($id[0]);
					if ($_POST['tipo_producto_movilizar_canal'] == 'Canales sin restricción de uso'){
						$totalPermitido = $this->modeloProductos->getNumCanalesObtenidosUso();
					}else{
						$totalPermitido = $this->modeloProductos->getNumCanalesUsoIndustri();
					}
					switch ($_POST['tipo_movilizacion_canal']) {
						case 'Entera':
							$banValidarTipo = 1;
							if ($_POST['id_emision_certificado'] != ''){
								$validardetalleEmision = $this->lNegocioDetalleEmisionCertificado->buscarLista("id_emision_certificado=" . $_POST['id_emision_certificado'] . " and producto_movilizar ='" . $_POST['producto_movilizar'] . "' and tipo_producto_movilizar_canal='" . $_POST['tipo_producto_movilizar_canal'] . "' and tipo_movilizacion_canal='" . $_POST['tipo_movilizacion_canal'] . "'");
								if ($validardetalleEmision->count() > 0){
								}else{
									$banValidarTipo = 0;
								}
							}
							if ($banValidarTipo){
								
								$datosProducto = array(
									'identificador_operador' => $_SESSION['usuario'],
									'fecha_produccion' => $_POST['fecha_produccion'],
									'tipo_movilizacion_canal' => $_POST['tipo_movilizacion_canal'],
									'tipo_especie' => $_POST['tipo_especie'],
									'producto_movilizar' => $_POST['producto_movilizar'],
									'tipo_producto_movilizar_canal' => $_POST['tipo_producto_movilizar_canal']
								);

								$detalleEmision = $this->lNegocioDetalleEmisionCertificado->obtenerCantidadEmisionCertificado($datosProducto);
								
								if ($detalleEmision->count() < $totalPermitido){
									$ban = 1;
									foreach ($detalleEmision as $value){
										if ($value['codigo_canal'] == $id[1]){
											$ban = 0;
										}
									}
									if ($ban){
										$idEmisionCertificado = $this->lNegocioEmisionCertificado->guardarProductosMovilizar($_POST);
										if ($idEmisionCertificado > 0){
											$id = $idEmisionCertificado;
											$contenido = $this->listarProductos($idEmisionCertificado);
											$canalSub = $this->listarProductosCanalSub($idEmisionCertificado);
											$canal = $this->generarcodigoCanal($_POST);
											if ($canal['estado'] == 'EXITO'){
												$canal = $canal['contenido'];
											}else{
												$canal = '';
											}
										}
									}else{
										$estado = 'FALLO';
										$mensaje = 'Código de la canal ya registrado..!!';
										if ($_POST['id_emision_certificado'] != ''){
											$contenido = $this->listarProductos($_POST['id_emision_certificado']);
										}
									}
								}else{
									$estado = 'FALLO';
									$mensaje = 'Excede el número de canales permitidos..!!';
									if ($_POST['id_emision_certificado'] != ''){
										$contenido = $this->listarProductos($_POST['id_emision_certificado']);
									}
								}
							}else{
								$estado = 'FALLO';
								$mensaje = 'Tipo de producto a movilizar ó Tipo movilización canal no es igual al registrado..!!';
								if ($_POST['id_emision_certificado'] != ''){
									$contenido = $this->listarProductos($_POST['id_emision_certificado']);
								}
							}
						break;
						case 'Media':
							$banValidarTipo = 1;
							if ($_POST['id_emision_certificado'] != ''){
								$validardetalleEmision = $this->lNegocioDetalleEmisionCertificado->buscarLista("id_emision_certificado=" . $_POST['id_emision_certificado'] . " and fecha_produccion='" . $_POST['fecha_produccion'] . "' and producto_movilizar ='" . $_POST['producto_movilizar'] . "' and tipo_producto_movilizar_canal='" . $_POST['tipo_producto_movilizar_canal'] . "' and tipo_movilizacion_canal='" . $_POST['tipo_movilizacion_canal'] . "'");
								if ($validardetalleEmision->count() > 0){
								}else{
									$banValidarTipo = 0;
								}
							}
							if ($banValidarTipo){
								// if($_POST['destino'] == 'Un destino'){
								$detalleEmision = $this->lNegocioDetalleEmisionCertificado->buscarLista("fecha_produccion='" . $_POST['fecha_produccion'] . "' and tipo_movilizacion_canal='" . $_POST['tipo_movilizacion_canal'] . "' and tipo_especie='" . $_POST['tipo_especie'] . "' and producto_movilizar ='" . $_POST['producto_movilizar'] . "' and tipo_producto_movilizar_canal='" . $_POST['tipo_producto_movilizar_canal'] . "'");
								if ($detalleEmision->count() < ($totalPermitido * 2)){
									$ban = 1;
									foreach ($detalleEmision as $value){
										if ($value['codigo_canal'] == $id[1]){
											$ban = 0;
										}
										if ($value['codigo_canal'] == $id[1] && $_POST['destino'] == 'Varios destinos'){
											$ban = 1;
										}
									}
									if ($ban){
										$idEmisionCertificado = $this->lNegocioEmisionCertificado->guardarProductosMovilizar($_POST);
										if ($idEmisionCertificado > 0){
											$id = $idEmisionCertificado;
											$contenido = $this->listarProductos($idEmisionCertificado);
											$canalSub = $this->listarProductosCanalSub($idEmisionCertificado);
											$canal = $this->generarcodigoCanal($_POST);
											if ($canal['estado'] == 'EXITO'){
												$canal = $canal['contenido'];
											}else{
												$canal = '';
											}
										}
									}else{
										$estado = 'FALLO';
										$mensaje = 'Código de la canal ya registrado..!!';
										if ($_POST['id_emision_certificado'] != ''){
											$contenido = $this->listarProductos($_POST['id_emision_certificado']);
										}
									}
								}else{
									$estado = 'FALLO';
									$mensaje = 'Excede el número de canales permitidos..!!';
									if ($_POST['id_emision_certificado'] != ''){
										$contenido = $this->listarProductos($_POST['id_emision_certificado']);
									}
								}
							}else{
								$estado = 'FALLO';
								$mensaje = 'Tipo de producto a movilizar ó Tipo movilización canal no es igual al registrado..!!';
								if ($_POST['id_emision_certificado'] != ''){
									$contenido = $this->listarProductos($_POST['id_emision_certificado']);
								}
							}
						break;
						case 'Cuarto':
							$banValidarTipo = 1;
							if ($_POST['id_emision_certificado'] != ''){
								$validardetalleEmision = $this->lNegocioDetalleEmisionCertificado->buscarLista("id_emision_certificado=" . $_POST['id_emision_certificado'] . " and fecha_produccion='" . $_POST['fecha_produccion'] . "' and producto_movilizar ='" . $_POST['producto_movilizar'] . "' and tipo_producto_movilizar_canal='" . $_POST['tipo_producto_movilizar_canal'] . "' and tipo_movilizacion_canal='" . $_POST['tipo_movilizacion_canal'] . "'");
								if ($validardetalleEmision->count() > 0){
								}else{
									$banValidarTipo = 0;
								}
							}

							if ($banValidarTipo){

								$detalleEmision = $this->lNegocioDetalleEmisionCertificado->buscarLista("fecha_produccion='" . $_POST['fecha_produccion'] . "' and tipo_movilizacion_canal='" . $_POST['tipo_movilizacion_canal'] . "' and tipo_especie='" . $_POST['tipo_especie'] . "' and producto_movilizar ='" . $_POST['producto_movilizar'] . "' and tipo_producto_movilizar_canal='" . $_POST['tipo_producto_movilizar_canal'] . "'");
								if ($detalleEmision->count() < ($totalPermitido * 4)){
									$ban = 1;
									foreach ($detalleEmision as $value){
										if ($value['codigo_canal'] == $id[1]){
											$ban = 0;
										}
									}
									if ($ban){
										$idEmisionCertificado = $this->lNegocioEmisionCertificado->guardarProductosMovilizar($_POST);
										if ($idEmisionCertificado > 0){
											$id = $idEmisionCertificado;
											$contenido = $this->listarProductos($idEmisionCertificado);
											$canalSub = $this->listarProductosCanalSub($idEmisionCertificado);
											$canal = $this->generarcodigoCanal($_POST);
											if ($canal['estado'] == 'EXITO'){
												$canal = $canal['contenido'];
											}else{
												$canal = '';
											}
										}
									}else{
										$estado = 'FALLO';
										$mensaje = 'Código de la canal ya registrado..!!';
										if ($_POST['id_emision_certificado'] != ''){
											$contenido = $this->listarProductos($_POST['id_emision_certificado']);
										}
									}
								}else{
									$estado = 'FALLO';
									$mensaje = 'Excede el número de canales permitidos..!!';
									if ($_POST['id_emision_certificado'] != ''){
										$contenido = $this->listarProductos($_POST['id_emision_certificado']);
									}
								}
							}else{
								$estado = 'FALLO';
								$mensaje = 'Tipo de producto a movilizar ó Tipo movilización canal no es igual al registrado..!!';
								if ($_POST['id_emision_certificado'] != ''){
									$contenido = $this->listarProductos($_POST['id_emision_certificado']);
								}
							}
						break;
						default:
							;
						break;
					}

				break;
				case 'Subproductos':
					if ($_POST['id_emision_certificado'] == ''){
						$idEmisionCertificado = $this->lNegocioEmisionCertificado->guardarProductosMovilizar($_POST);
						$this->actualizarNumeracionLote($idEmisionCertificado);
						$contenido = $this->listarSubproductos($idEmisionCertificado);
						$id = $idEmisionCertificado;
					}else{
						$datos = array(
							'id_emision_certificado' => $_POST['id_emision_certificado'],
							'subproducto' => $_POST['subproducto'],
							'cantidad_movilizar' => $_POST['cantidad_movilizar'],
							'tipo_especie' => $_POST['tipo_especie']);

						$respuesta = $this->guardarSubProductosMovilizar($datos);
						$estado = $respuesta['estado'];
						$mensaje = $respuesta['mensaje'];
						$contenido = $respuesta['contenido'];
						$id = $_POST['id_emision_certificado'];
					}
				break;
				default:
					;
				break;
			}
		}
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido,
			'id' => $id,
			'canal' => $canal,
			'canalSub' => $canalSub,
			'total' => $total));
	}

	public function agregarSubProductosMovilizar(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$canal = '';
		$id = '';
		$canalSub = '';

		$respuesta = $this->guardarSubProductosMovilizar($_POST);
		$estado = $respuesta['estado'];
		$mensaje = $respuesta['mensaje'];
		$contenido = $respuesta['contenido'];
		$canal = $respuesta['canal'];
		$id = $respuesta['id'];
		$canalSub = $respuesta['canalSub'];

		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido,
			'id' => $id,
			'canal' => $canal,
			'canalSub' => $canalSub));
	}

	public function guardarSubProductosMovilizar($arrayProductos){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$canal = '';
		$id = '';
		$canalSub = '';
		if ($arrayProductos['id_emision_certificado'] != ''){
			if ($arrayProductos['subproducto'] != '' && $arrayProductos['cantidad_movilizar'] != ''){
				$idDetalleEmisionCertificado = null;
				if (isset($arrayProductos['producto_agregado'])){
					$campos = explode('-', $arrayProductos['producto_agregado']);
					$idDetalleEmisionCertificado = $campos[1];
					$_POST['tipo_especie'] = $campos[0];
				}
				$subPro = explode('-', $arrayProductos['subproducto']);

				if ($_POST['tipo_especie'] == 'AVICOLA'){
					$arrayDatos = array(
						'tipo_especie' => $_POST['tipo_especie'],
						'fecha_creacion' => date('Y-m-d'),
						'estado' => 'creado',
						'identificador_operador' => $_SESSION['usuario']);
				}else{
					$arrayDatos = array(
						'tipo_especie_no' => 'AVICOLA',
						'fecha_creacion' => date('Y-m-d'),
						'estado' => 'creado',
						'identificador_operador' => $_SESSION['usuario']);
				}
				$resultado = $this->lNegocioSubproductoEmisionCertificado->buscarSubproductosEmision($arrayDatos);

				if ($resultado->count()){
					if ($resultado->count() == 0){
						$num = $resultado->count() + 1;
					}else{
						$num = $resultado->count();
					}
					$loteMovilizar = str_pad($num, 3, "0", STR_PAD_LEFT);
				}else{
					$num = 1;
					$loteMovilizar = str_pad($num, 3, "0", STR_PAD_LEFT);
				}
				if ($idDetalleEmisionCertificado == null){
					$arrayDatos = array(
						'fecha_produccion' => $_POST['fecha_produccion'],
						'id_emision_certificado' => $arrayProductos['id_emision_certificado'],
						'estado' => 'creado',
						'identificador_operador' => $_SESSION['usuario'],
					    'tipo_especie' => $_POST['tipo_especie']
					);
					$resultado = $this->lNegocioSubproductoEmisionCertificado->buscarSubproductosEmision($arrayDatos);
					if ($resultado->count()){
					    foreach ($resultado as $value) {
					        $idDetalleEmisionCertificado=$value['id_detalle_emision_certificado'];
					    }
						//$idDetalleEmisionCertificado = $resultado->current()->id_detalle_emision_certificado;
					}else{
						$campo = explode('-', $_POST['subproducto']);
						$datos1 = array(
							'id_emision_certificado' => $_POST['id_emision_certificado'],
							'producto_movilizar' => $_POST['producto_movilizar'],
							'tipo_especie' => $_POST['tipo_especie'],
							'id_productos' => $campo[0],
							'fecha_produccion' => $_POST['fecha_produccion']);
						$idDetalleEmisionCertificado = $this->lNegocioDetalleEmisionCertificado->guardar($datos1);
					}
				}
				$datos = array(
					'id_detalle_emision_certificado' => $idDetalleEmisionCertificado,
					'subproducto' => $subPro[1],
					'cantidad_movilizar' => $_POST['cantidad_movilizar'],
					'saldo_disponible' => $_POST['saldo_disponible'],
					'id_subproductos' => $subPro[2],
					'lote_movilizar' => $loteMovilizar // 'id_emision_certificado' => $_POST['id_emision_certificado'],
					                                   // 'tipo_especie' => $campos[0]
				);
				$id = $this->lNegocioSubproductoEmisionCertificado->guardar($datos);
				if ($id){
					$this->actualizarNumeracionLote($_POST['id_emision_certificado']);
					$contenido = $this->listarSubproductos($_POST['id_emision_certificado']);
				}else{
					$estado = 'FALLO';
					$mensaje = 'Error al guardar los datos.';
				}
			}else{
				$estado = 'FALLO';
				$mensaje = 'Por favor revise los campos obligatorios.';
			}
		}else{
			$estado = 'FALLO';
			$mensaje = 'No existen productos agregados..!!.';
		}

		return array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido,
			'id' => $id,
			'canal' => $canal,
			'canalSub' => $canalSub);
	}

	/**
	 *
	 * @return string
	 */
	public function listarProductos($idEmisionCertificado){
		$html = $datos = '';
		$resultado = $this->lNegocioDetalleEmisionCertificado->buscarLista("id_emision_certificado = " . $idEmisionCertificado . " order by 1");
		if ($resultado->count()){
			$contador = 0;
			foreach ($resultado as $item){

				$datos .= '<tr>';
				$datos .= '<td>' . ++ $contador . '</td>';
				$datos .= '<td align="center">' . $item->fecha_produccion . '</td>';
				$datos .= '<td align="center">' . $item->tipo_especie . '</td>';
				$datos .= '<td align="center">' . $item->tipo_producto_movilizar_canal . '</td>';
				$datos .= '<td align="center">' . $item->codigo_canal . '</td>';
				$datos .= '<td align="center">' . $item->tipo_movilizacion_canal . '</td>';
				$datos .= '<td><button class="bEliminar icono" onclick="eliminarProducto(' . $item->id_detalle_emision_certificado . '); return false; "></button></td>';
				$datos .= '</tr>';
			}
			$html = '
        				<table style="width:100%">
        					<thead><tr>
        						<th>#</th>
        						<th>Fecha de faenamiento</th>
        						<th>Especie</th>
        						<th>Tipo producto a movilizar</th>
                                <th>Código Canal</th>
                                <th>Tipo movilización canal</th>
                                <th></th>
        						</tr></thead>
        					<tbody>' . $datos . '</tbody>
        				</table>';
		}
		return $html;
	}

	/**
	 */
	public function eliminarProduccion(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$canal = '<option value="">Seleccione...</option>';
		$this->modeloDetalleEmisionCertificado = $this->lNegocioDetalleEmisionCertificado->buscar($_POST['id']);
		$validarRegistro = $this->lNegocioSubproductoEmisionCertificado->buscarLista("id_detalle_emision_certificado=" . $_POST['id']);
		if ($validarRegistro->count()){
			$estado = 'FALLO';
			$mensaje = 'No se puede eliminar existen subproductos agregados..!!';
		}else{
			$this->lNegocioDetalleEmisionCertificado->borrar($_POST['id']);
		}
		$contenido = $this->listarProductos($_POST['id_emision_certificado']);
		$canalSub = $this->listarProductosCanalSub($_POST['id_emision_certificado']);

		if ($contenido == ''){
			$id_emision_certificado = '';
		}else{
			$id_emision_certificado = $_POST['id_emision_certificado'];
		}
		$arrayCodigoCanal = array(
			'tipo_especie' => $this->modeloDetalleEmisionCertificado->getTipoEspecie(),
			'fecha_produccion' => $this->modeloDetalleEmisionCertificado->getFechaProduccion(),
			'tipo_movilizacion_canal' => $this->modeloDetalleEmisionCertificado->getTipoMovilizacionCanal(),
			'producto_movilizar' => $this->modeloDetalleEmisionCertificado->getProductoMovilizar(),
			'id_emision_certificado' => $id_emision_certificado);

		$detalleEmision = $this->generarcodigoCanal($arrayCodigoCanal);

		if ($detalleEmision['estado'] == 'EXITO'){
			$canal = $detalleEmision['contenido'];
		}else{
			$numeroCanales = $this->lNegocioProductos->buscar($this->modeloDetalleEmisionCertificado->getIdProductos());
			for ($i = 1; $i <= $numeroCanales->getNumCanalesObtenidos(); $i ++){
				$canal .= '<option value="' . $this->modeloDetalleEmisionCertificado->getIdProductos() . '-' . $i . '" >' . $i . '</option>';
			}
		}

		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido,
			'canal' => $canal,
			'canalSub' => $canalSub));
	}

	/**
	 */
	public function eliminarSubproductos(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';

		$verificarSubProdu = $this->lNegocioSubproductoEmisionCertificado->buscar($_POST['id']);
		$this->lNegocioSubproductoEmisionCertificado->borrar($_POST['id']);
		$this->actualizarNumeracionLote($_POST['id_emision_certificado']);
		$contenido = $this->listarSubproductos($_POST['id_emision_certificado']);
		if ($contenido == ''){
			$this->modeloDetalleEmisionCertificado = $this->lNegocioDetalleEmisionCertificado->buscar($verificarSubProdu->getIdDetalleEmisionCertificado());
			if ($this->modeloDetalleEmisionCertificado->getProductoMovilizar() != 'Canal con subproductos'){
				$verificar = $this->lNegocioEmisionCertificado->buscarLista("estado ='temporal' and identificador_operador='" . $_SESSION['usuario'] . "'");
				if ($verificar->count()){
					$subValidar = $this->lNegocioDetalleEmisionCertificado->buscarLista("id_emision_certificado=" . $verificar->current()->id_emision_certificado);
					if ($subValidar->count()){
						foreach ($subValidar as $value){
							$this->lNegocioSubproductoEmisionCertificado->borrarPorParametro('id_detalle_emision_certificado', $value['id_detalle_emision_certificado']);
						}
					}
					$this->lNegocioDetalleEmisionCertificado->borrarPorParametro('id_emision_certificado', $verificar->current()->id_emision_certificado);
					$this->lNegocioEmisionCertificado->borrar($verificar->current()->id_emision_certificado);
				}
			}
		}

		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido));
	}

	public function actualizarNumeracionLote($idEmisionCertificado){
		$arrayDatos = array(
			"id_emision_certificado" => $idEmisionCertificado,
			'estado' => 'creado',
			'estadoEmision' => 'temporal',
			'identificador_operador' => $_SESSION['usuario']);
		$resultado = $this->lNegocioSubproductoEmisionCertificado->buscarSubproductosEmision($arrayDatos);
		$contador = 1;
		$banInicializar = true;
		foreach ($resultado as $value){
			if ($banInicializar){
				$arrayDatos = array(
					"id_emision_certificado_no" => $idEmisionCertificado,
					//'tipo_especie' => $value['tipo_especie'],
					'fecha_creacion' => date('Y-m-d'),
					'estado' => 'creado',
					'identificador_operador' => $_SESSION['usuario']);
				$resultadoNum = $this->lNegocioSubproductoEmisionCertificado->buscarSubproductosEmision($arrayDatos);
				if ($resultadoNum->count()){
					$contador = $resultadoNum->count() + 1;
				}
				$banInicializar = false;
			}

			$loteMovilizar = str_pad($contador, 3, "0", STR_PAD_LEFT);
			$campos = array(
				'lote_movilizar' => $loteMovilizar,
				'id_subproductos_emision_certificado' => $value['id_subproductos_emision_certificado']);
			$this->lNegocioSubproductoEmisionCertificado->guardar($campos);
			$contador ++;
		}
	}

	/**
	 *
	 * @return string
	 */
	public function listarProductosMenor($idEmisionCertificado){
		$html = $datos = '';
		$resultado = $this->lNegocioDetalleEmisionCertificado->buscarLista("id_emision_certificado = " . $idEmisionCertificado . " order by 1");
		if ($resultado->count()){
			$contador = 0;
			foreach ($resultado as $item){

				$datos .= '<tr>';
				$datos .= '<td>' . ++ $contador . '</td>';
				$datos .= '<td align="center">' . $item->fecha_produccion . '</td>';
				$datos .= '<td align="center">' . $item->tipo_especie . '</td>';
				$datos .= '<td align="center">' . $item->tipo_producto_movilizar_canal . '</td>';
				$datos .= '<td align="center">' . $item->codigo_canal . '</td>';
				$datos .= '<td align="center">' . $item->cantidad_movilizar . '</td>';
				$datos .= '<td><button class="bEliminar icono" onclick="eliminarProductoMenor(' . $item->id_detalle_emision_certificado . '); return false; "></button></td>';
				$datos .= '</tr>';
			}
			$html = '
        				<table style="width:100%">
        					<thead><tr>
        						<th>#</th>
        						<th>Fecha de faenamiento</th>
        						<th>Especie</th>
        						<th>Tipo producto a movilizar</th>
                                <th>Lote a movilizar</th>
                                <th>Cantidad a movilizar</th>
                                <th></th>
        						</tr></thead>
        					<tbody>' . $datos . '</tbody>
        				</table>';
		}
		return $html;
	}

	/**
	 *
	 * @return string
	 */
	public function listarSubproductos($idEmisionCertificado){
		$html = $datos = '';

		$arrayDatos = array(
			'id_emision_certificado' => $idEmisionCertificado,
			'estado' => 'creado',
			'estadoEmision' => 'temporal',
			'identificador_operador' => $_SESSION['usuario']);
		$resultado = $this->lNegocioSubproductoEmisionCertificado->buscarSubproductosEmision($arrayDatos);
		if ($resultado->count()){
			$contador = 0;
			foreach ($resultado as $item){

				$datos .= '<tr>';
				$datos .= '<td>' . ++ $contador . '</td>';
				$datos .= '<td align="center">' . $item['fecha_produccion'] . '</td>';
				$datos .= '<td align="center">' . $item['tipo_especie'] . '</td>';
				$datos .= '<td align="center">' . $item['subproducto'] . '</td>';
				$datos .= '<td align="center">' . $item['lote_movilizar'] . '</td>';
				$datos .= '<td align="center">' . $item['cantidad_movilizar'] . '</td>';
				$datos .= '<td><button class="bEliminar icono" onclick="eliminarSubproductos(' . $item['id_subproductos_emision_certificado'] . '); return false; "></button></td>';
				$datos .= '</tr>';
			}
			$html = '
        				<table style="width:100%">
        					<thead><tr>
        						<th>#</th>
        						<th>Fecha de faenamiento</th>
        						<th>Especie</th>
        						<th>Subproducto</th>
                                <th>Lote a movilizar</th>
                                <th>Cantidad a movilizar</th>
                                <th></th>
        						</tr></thead>
        					<tbody>' . $datos . '</tbody>
        				</table>';
		}
		return $html;
	}

	/**
	 */
	public function eliminarProduccionMenor(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$saldoDisponible = '';
		$total = '';
		$cantidadMovilizar = '';

		$this->modeloDetalleEmisionCertificado = $this->lNegocioDetalleEmisionCertificado->buscar($_POST['id']);

		$verificarSubproductos = $this->lNegocioSubproductoEmisionCertificado->buscarLista("id_detalle_emision_certificado =" . $_POST['id']);

		if ($verificarSubproductos->count()){
			$estado = 'FALLO';
			$mensaje = 'No se puede eliminar existen subproductos agregados..!!';
		}else{
			$this->lNegocioDetalleEmisionCertificado->borrar($_POST['id']);
		}

		$contenido = $this->listarProductosMenor($_POST['id_emision_certificado']);
		if ($contenido == ''){
			$arrayDatos = array(
				'tipo_producto_movilizar_canal' => $this->modeloDetalleEmisionCertificado->getTipoProductoMovilizarCanal(),
				'fecha_faenamiento' => $this->modeloDetalleEmisionCertificado->getFechaProduccion(),
				'tipo_especie' => $this->modeloDetalleEmisionCertificado->getTipoEspecie(),
				'id_emision_certificado' => '');

			$this->lNegocioEmisionCertificado->borrar($_POST['id_emision_certificado']);
		}else{
			$arrayDatos = array(
				'tipo_producto_movilizar_canal' => $this->modeloDetalleEmisionCertificado->getTipoProductoMovilizarCanal(),
				'fecha_faenamiento' => $this->modeloDetalleEmisionCertificado->getFechaProduccion(),
				'tipo_especie' => $this->modeloDetalleEmisionCertificado->getTipoEspecie(),
				'id_emision_certificado' => $this->modeloDetalleEmisionCertificado->getIdEmisionCertificado());
		}
		$saldo = $this->consultarSaldoDisponible($arrayDatos);
		if ($saldo['estado'] == 'EXITO'){
			$cantidadMovilizar = $saldo['contenido'];
			$total = $saldo['total'];
		}else{
			$estado = 'FALLO';
			$mensaje = $saldo['mensaje'];
		}

		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido,
			'total' => $total,
			'cantidadMovilizar' => $cantidadMovilizar));
	}

	/**
	 * Construye el código HTML
	 */
	public function buscarSubproductos(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$canal = '';
		$valores = '';

		if (isset($_POST['producto_agregado'])){
			if ($_POST['producto_agregado'] != ''){
				$campos = explode('-', $_POST['producto_agregado']);
				// $arrayParametros = array('id_detalle_emision_certificado'=> $campos[1]);
				// $consulta = $this->lNegocioDetalleEmisionCertificado->obtenerDetalleEmisionCertificadoRegistrado($arrayParametros);
				// $especie = $consulta->current()->tipo_especie;
				// $fechaFaenamiento = $consulta->current()->fecha_produccion;
				$campos = explode('-', $_POST['producto_agregado']);
				$this->modeloDetalleEmisionCertificado = $this->lNegocioDetalleEmisionCertificado->buscar($campos[1]);
				$especie = $this->modeloDetalleEmisionCertificado->getTipoEspecie();
				$fechaFaenamiento = $this->modeloDetalleEmisionCertificado->getFechaProduccion();
			}else{
				$especie = $_POST['tipoEspecie'];
				$fechaFaenamiento = $_POST['fechaProduccion'];
			}
		}else{
			$especie = $_POST['tipoEspecie'];
			$fechaFaenamiento = $_POST['fechaProduccion'];
		}
		$arrayParametros = array(
			'identificador_operador' => $_SESSION['usuario'],
			'tipo_especie' => $especie,
			'fecha_faenamiento' => $fechaFaenamiento);
		$modeloRegistroProduccion = $this->lNegocioRegistroProduccion->listarRegistroSubProduccion($arrayParametros);
		$combo = '<option value="">Seleccione...</option>';
		if ($modeloRegistroProduccion->count()){
			foreach ($modeloRegistroProduccion as $value){
				$combo .= '<option value="' . $value['id_productos'] . '-' . $value['subproducto'] . '-' . $value['id_subproductos'] . '">' . $value['subproducto'] . '</option>';
				$canal = $this->comboNumerosCanal($value['num_canales_obtenidos'], $value['id_productos']);
			}
			$valores = $this->listarProductoMovilizar($_POST['producto_movilizar'], $especie);
		}else{
			$estado = 'FALLO';
			$mensaje = 'No existen subproductos para la especie seleccionada..!!';
		}
		$contenido = $combo;
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido,
			'canal' => $canal,
			'valores' => $valores));
	}

	/**
	 * Construye el código HTML
	 */
	public function buscarCanalSub(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';

		$arrayParametros = array(
			'identificador_operador' => $_SESSION['usuario'],
			'tipo_especie' => $_POST['tipoEspecie'],
			'fecha_recepcion' => $_POST['fechaProduccion']);
		$modeloRegistroProduccion = $this->lNegocioRegistroProduccion->listarRegistroProduccion($arrayParametros);
		$combo = '<option value="">Seleccione...</option>';
		if ($modeloRegistroProduccion->count()){
			foreach ($modeloRegistroProduccion as $value){
				$combo .= '<option value="' . $value['id_productos'] . '-' . $value['codigo_canal'] . '">' . $value['codigo_canal'] . '</option>';
			}
		}else{
			$estado = 'FALLO';
			$mensaje = 'No existen canales para la especie registrados..!!';
		}
		$contenido = $combo;
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido));
	}

	public function saldoDisponible(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$total = 0;
		$idRegistroProduccion = '';
		$idProductos = '';

		if (isset($_POST['tipo_producto_movilizar_canal']) && isset($_POST['fecha_faenamiento']) && isset($_POST['tipo_especie'])){

			$resultado = $this->consultarSaldoDisponible($_POST);
			$estado = $resultado['estado'];
			$mensaje = $resultado['mensaje'];
			$contenido = $resultado['contenido'];
			$total = $resultado['total'];
			$idRegistroProduccion = $resultado['idRegistroProduccion'];
			$idProductos = $resultado['idProductos'];
		}else{
			$estado = 'FALLO';
			$mensaje = 'Error al consultar el saldo disponible...!!!';
		}

		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido,
			'total' => $total,
			'idRegistroProduccion' => $idRegistroProduccion,
			'idProductos' => $idProductos));
	}

	public function consultarSaldoDisponible($arrayParametros){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$total = 0;
		$idRegistroProduccion = '';
		$idProductos = '';

		if (isset($arrayParametros['tipo_producto_movilizar_canal']) && isset($arrayParametros['fecha_faenamiento']) && isset($arrayParametros['tipo_especie'])){

			if ($arrayParametros['tipo_producto_movilizar_canal'] == 'Canales sin restricción de uso'){
				$campo = 'num_canales_obtenidos_uso';
			}else{
				$campo = 'num_canales_uso_industri';
			}
			// $consulta = $this->lNegocioProductos->buscarLista("fecha_faenamiento='".$_POST['fecha_faenamiento']."' and tipo_especie = '".$_POST['tipo_especie']."'");

			$arrayDatos = array(
				'fecha_faenamiento' => $arrayParametros['fecha_faenamiento'],
				'tipo_especie' => $arrayParametros['tipo_especie'],
				'identificador_operador' => $_SESSION['usuario']);
			$consulta = $this->lNegocioRegistroProduccion->listarRegistroProduccion($arrayDatos);
			if ($consulta->count()){
				$total = $total + $consulta->current()->$campo;
				$idRegistroProduccion = $consulta->current()->id_registro_produccion;
				$idProductos = $consulta->current()->id_productos;
				$cantidadMovilizar = 0;

				$arrayVerificar = array(
					'fecha_produccion' => $arrayParametros['fecha_faenamiento'],
					'tipo_producto_movilizar_canal' => $arrayParametros['tipo_producto_movilizar_canal'],
					'tipo_especie' => $arrayParametros['tipo_especie'],
					'identificador_operador' => $_SESSION['usuario']);
				$verificarTotal = $this->lNegocioDetalleEmisionCertificado->obtenerDetalleEmisionCertificado($arrayVerificar);
				// if($arrayParametros['id_emision_certificado'] !=''){
				// $verificarTotal = $this->lNegocioDetalleEmisionCertificado->buscarLista("fecha_produccion ='".$arrayParametros['fecha_faenamiento']."' and tipo_especie='".$arrayParametros['tipo_especie']."'");
				if ($verificarTotal->count()){
					foreach ($verificarTotal as $value){
						$cantidadMovilizar = $cantidadMovilizar + $value['cantidad_movilizar'];
					}
					$total = $total - $cantidadMovilizar;
				}
				// }
				$contenido = $this->comboNumeros($total);
			}
		}else{
			$estado = 'FALLO';
			$mensaje = 'Error al consultar el saldo disponible...!!!';
		}

		return array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido,
			'total' => $total,
			'idRegistroProduccion' => $idRegistroProduccion,
			'idProductos' => $idProductos);
	}

	/**
	 * Construye el código HTML
	 */
	public function saldoDisponibleSubProducto(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$total = 0;
		$utilizado = 0;
		$idProductos = '';
		$contador = 0;

		$dato = explode('-', $_POST['subproducto']);
		if (isset($_POST['fecha_faenamiento']) && isset($_POST['tipo_especie'])){
			if ($_POST['producto_agregado'] != ''){
				$campos = explode('-', $_POST['producto_agregado']);
				// $arrayParametros = array('id_detalle_emision_certificado'=> $campos[1]);
				// $consulta = $this->lNegocioDetalleEmisionCertificado->obtenerDetalleEmisionCertificadoRegistrado($arrayParametros);
				// $especie = $consulta->current()->tipo_especie;
				// $fechaFaenamiento = $consulta->current()->fecha_produccion;
				$this->modeloDetalleEmisionCertificado = $this->lNegocioDetalleEmisionCertificado->buscar($campos[1]);
				$especie = $this->modeloDetalleEmisionCertificado->getTipoEspecie();
				$fechaFaenamiento = $this->modeloDetalleEmisionCertificado->getFechaProduccion();
			}else{
				$especie = $_POST['tipo_especie'];
				$fechaFaenamiento = $_POST['fecha_faenamiento'];
			}
			$arrayProductos = array(
				'fecha_faenamiento' => $fechaFaenamiento,
				'tipo_especie' => $especie,
				'subproducto' => $dato[1],
				'id_productos' => $dato[0]);
			$consulta = $this->lNegocioProductos->buscarCantidadProductos($arrayProductos);

			if ($consulta->count()){
				foreach ($consulta as $value){
					$total = $total + $value['cantidad'];
					$idProductos = $value['id_productos'];
				}
				$arrayDatos = array(
					'fecha_produccion' => $fechaFaenamiento,
					'estado' => 'creado',
					'tipo_especie' => $especie,
					'subproducto' => $dato[1],
					'identificador_operador' => $_SESSION['usuario']);
				$resultado = $this->lNegocioSubproductoEmisionCertificado->buscarSubproductosEmision($arrayDatos);
				if ($resultado->count()){
					foreach ($resultado as $value){
						$utilizado = $utilizado + $value['cantidad_movilizar'];
					}
					if ($utilizado < $total){
						$contador = $total - $utilizado;
						$contenido = $this->comboNumeros($contador);
					}else{
						$estado = 'FALLO';
						$mensaje = 'No tiene saldo disponible...!!!';
					}
				}else{
					$contador = $total;
					$contenido = $this->comboNumeros($total);
				}
			}
		}else{
			$estado = 'FALLO';
			$mensaje = 'Error al consultar el saldo disponible...!!!';
		}

		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido,
			'total' => $contador,
			'idProductos' => $idProductos));
	}

	/**
	 * Construye el código HTML
	 */
	public function cantidadMovilizar(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$datos = explode('-', $_POST['subproducto']);
		$arrayParametros = array(
			'id_productos' => $datos[0],
			'subproducto' => $datos[1]);
		$consulta = $this->lNegocioEmisionCertificado->sumarProduccion($arrayParametros);
		if ($consulta->count()){
			$contenido = $this->comboNumeros($consulta->current()->resultado);
		}else{
			$estado = 'FALLO';
			$mensaje = 'No existe productos...!!';
		}
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido));
	}

	// ****************************filtra informacion segun parametros************
	public function filtrarInformacion(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$modeloEmisionCertificado = array();
		if (isset($_POST['nombreSitio']) || isset($_POST['numCertificado']) || isset($_POST['estadoEmision'])){
			if ($_POST['nombreSitio'] != '' || $_POST['numCertificado'] != '' || $_POST['estadoEmision'] != ''){
				$_POST['sitio_origen'] = '';
				if ($_POST['nombreSitio'] != ''){
					$dato = explode('-', $_POST['nombreSitio']);
					$_POST['sitio_origen'] = $dato[0];
				}

				$arrayParametros = array(
					'identificador_operador' => $_SESSION['usuario'],
					'sitio_origen' => $_POST['sitio_origen'],
					'numero_certificado' => $_POST['numCertificado'],
					'estado' => $_POST['estadoEmision'],
					'fechaInicio' => $_POST['fechaInicio'],
					'fechaFin' => $_POST['fechaFin']);
				$modeloEmisionCertificado = $this->lNegocioEmisionCertificado->filtrarInformacion($arrayParametros);
				if ($modeloEmisionCertificado->count() == 0){
					$estado = 'FALLO';
					$mensaje = 'No existen registros para la busqueda..!!';
				}
			}else{
				$estado = 'FALLO';
				$mensaje = 'Debe ingresar al menos un campo obligatorio (*)..!!';
			}
		}else{
			$estado = 'FALLO';
			$mensaje = 'Debe ingresar al menos un campo obligatorio (*)..!!';
		}
		$this->tablaHtmlEmisionCertificado($modeloEmisionCertificado);
		$contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido));
	}

	/**
	 *
	 * @return string obtener el código VUE
	 */
	public function obtenerCodigoProvincia($idLocalizacion){
		$arrayParametros = array(
			'id_localizacion' => $idLocalizacion);
		$consulta = $this->lNegocioEmisionCertificado->obtenerCodigoVueLocalizacion($arrayParametros);
		$codigo = $consulta->current()->codigo_vue;
		return $codigo;
	}

	/**
	 * validar identificador por medio de webservices
	 */
	public function validarIdentificador(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';

		if (isset($_POST['identificador'])){
			$numero = strlen($_POST['identificador']);
			switch ($numero) {
				case 10:
					$arrayParametros = array(
						'numero' => $_POST['identificador'],
						'clasificacion' => 'Cédula');
					$verificar = $this->consultarWebService($arrayParametros);
					if ($verificar['estado'] == 'exito'){
						$contenido = $verificar['valores']['Nombre'];
					}else{
						$estado = 'FALLO';
						$mensaje = $verificar['mensaje'];
					}
				break;
				case 13:
					$arrayParametros = array(
						'numero' => $_POST['identificador'],
						'clasificacion' => 'Natural');
					$verificar = $this->consultarWebService($arrayParametros);
					if ($verificar['estado'] == 'exito'){
						$contenido = $verificar['valores']['razonSocial'];
					}else{
						$estado = 'FALLO';
						$mensaje = $verificar['mensaje'];
					}
				break;

				default:
					$estado = 'FALLO';
					$mensaje = 'Número de caracteres incorrecto..!!!';
				break;
			}
		}

		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido));
	}

	/**
	 * validar el numero de canales
	 */
	public function codigoCanal(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$arrayCodigoCanal = array(
			'tipo_especie' => $_POST['tipo_especie'],
			'fecha_produccion' => $_POST['fecha_produccion'],
			'tipo_movilizacion_canal' => $_POST['tipo_movilizacion_canal'],
			'producto_movilizar' => $_POST['producto_movilizar']);
		$resultado = $this->generarcodigoCanal($_POST);

		if ($resultado['estado'] == 'EXITO'){
			$contenido = $resultado['contenido'];
		}else{
			$estado = 'FALLO';
			$mensaje = 'No existen canales para la especie registrados..!!';
		}
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido));
	}

	/**
	 * validar el numero de canales
	 */
	public function generarcodigoCanal($arrayCodigoCanal){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$numeroCanales = 0;
		$idProductos = '';
		$arrayParametros = array(
			'identificador_operador' => $_SESSION['usuario'],
			'tipo_especie' => $arrayCodigoCanal['tipo_especie'],
			'fecha_creacion' =>  $arrayCodigoCanal['fecha_produccion']);
		$modeloRegistroProduccion = $this->lNegocioRegistroProduccion->listarRegistroProduccion($arrayParametros);
		$combo = '<option value="">Seleccione...</option>';
		if ($modeloRegistroProduccion->count()){
			// $detalleEmision = $this->lNegocioDetalleEmisionCertificado->buscarLista("fecha_produccion='".$arrayCodigoCanal['fecha_produccion']."' and tipo_especie='".$arrayCodigoCanal['tipo_especie']."' and producto_movilizar ='".$arrayCodigoCanal['producto_movilizar']."' order by codigo_canal");
			$arrayParame = array(
				'producto_movilizar' => 'Subproductos',
				'identificador_operador' => $_SESSION['usuario'],
				'tipo_especie' => $arrayCodigoCanal['tipo_especie'],
				'fecha_creacion' => date('Y-m-d'));
			$detalleEmision = $this->lNegocioDetalleEmisionCertificado->obtenerDetalleEmisionCertificadoLista($arrayParame);
			foreach ($modeloRegistroProduccion as $value){
				$numeroCanales = $value['num_canales_obtenidos'];
				$idProductos = $value['id_productos'];
				$contenido = $this->comboNumerosCanal($value['num_canales_obtenidos'], $value['id_productos']);
			}
			switch ($arrayCodigoCanal['tipo_movilizacion_canal']) {
				case 'Entera':
					if ($detalleEmision->count() > 0){
						$numerosIngresados = array();
						foreach ($detalleEmision as $item){
							$numerosIngresados[] = $item['codigo_canal'];
						}
						for ($i = 1; $i <= $numeroCanales; $i ++){
							$num = str_pad($i, 3, "0", STR_PAD_LEFT);
							if (! in_array($num, $numerosIngresados)){
								$combo .= '<option value="' . $idProductos . '-' . $num . '" >' . $num . '</option>';
							}
						}
						$contenido = $combo;
					}
				break;
				case 'Media':
					$arrayParametros = array(
						"fecha_produccion" => $arrayCodigoCanal['fecha_produccion'],
						"tipo_especie" => $arrayCodigoCanal['tipo_especie'],
						"producto_movilizar" => $arrayCodigoCanal['producto_movilizar']);
					$detalleEmision = $this->lNegocioDetalleEmisionCertificado->contadorCodigoCanal($arrayParametros);

					if ($detalleEmision->count() > 0){
						$numerosIngresados = array();
						foreach ($detalleEmision as $item){
							if ($item['destino'] == 'Un destino'){
								$numerosIngresados[] = $item['codigo_canal'];
							}else if ($item['repeticion'] == 2){
								$numerosIngresados[] = $item['codigo_canal'];
							}else if ($arrayCodigoCanal['id_emision_certificado'] != ''){
								$numerosIngresados[] = $item['codigo_canal'];
							}else if ($item['repeticion'] == 1 && $arrayCodigoCanal['id_emision_certificado'] == ''){
								// $numerosIngresados[] = $item['codigo_canal'];
							}
						}
						for ($i = 1; $i <= $numeroCanales; $i ++){
							if (! in_array($i, $numerosIngresados)){
								$num = str_pad($i, 3, "0", STR_PAD_LEFT);
								$combo .= '<option value="' . $idProductos . '-' . $num . '" >' . $num . '</option>';
							}
						}
						$contenido = $combo;
					}
				break;
				case 'Cuarto':
					if ($detalleEmision->count() > 0){
						$numerosIngresados = array();
						foreach ($detalleEmision as $item){
							$numerosIngresados[] = $item['codigo_canal'];
						}
						for ($i = 1; $i <= $numeroCanales; $i ++){
							if (! in_array($i, $numerosIngresados)){
								$num = str_pad($i, 3, "0", STR_PAD_LEFT);
								$combo .= '<option value="' . $idProductos . '-' . $num . '" >' . $num . '</option>';
							}
						}
						$contenido = $combo;
					}
				break;
				default:
					;
				break;
			}
		}else{
			$estado = 'FALLO';
			$mensaje = 'No existen canales para la especie registrados..!!';
		}

		return array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido);
	}

	public function listarProductosCanalSubNew($idEmisionCertificado){
		$arrayParametros = array(
			'id_emision_certificado' => $idEmisionCertificado);

		$resultado = $this->lNegocioDetalleEmisionCertificado->obtenerDetalleEmisionCertificadoRegistrado($arrayParametros);
		$combo = '<option value="">Seleccionar...</option>';
		// $contador = 1;
		if ($resultado->count()){
			foreach ($resultado as $item){
				$combo .= '<option value="' . $item['tipo_especie'] . '-' . $item['id_detalle_emision_certificado'] . '-' . $item['id_productos'] . '" >' . $item['tipo_especie'] . '</option>';
			}
		}
		return $combo;
	}

	public function listarProductosCanalSub($idEmisionCertificado){
		$resultado = $this->lNegocioDetalleEmisionCertificado->buscarLista("id_emision_certificado = " . $idEmisionCertificado . " order by 1");
		$combo = '<option value="">Seleccionar...</option>';
		$contador = 1;
		if ($resultado->count()){
			foreach ($resultado as $item){
				$combo .= '<option value="' . $item->tipo_especie . '-' . $item->id_detalle_emision_certificado . '-' . $item->id_productos . '" >' . $contador ++ . '-' . $item->tipo_especie . '</option>';
			}
		}
		return $combo;
	}
}
