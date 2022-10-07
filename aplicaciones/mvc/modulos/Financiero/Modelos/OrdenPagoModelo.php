<?php
 /**
 * Modelo OrdenPagoModelo
 *
 * Este archivo se complementa con el archivo   OrdenPagoLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @fecha 2018-10-03
 * @uses       OrdenPagoModelo
 * @package financiero
 * @subpackage Modelos
 */
  namespace Agrodb\Financiero\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class OrdenPagoModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idPago;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $identificadorOperador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $numeroSolicitud;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $fechaOrdenPago;
		/**
		* @var Decimal
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $totalPagar;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $observacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $estado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $localizacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $institucionBancaria;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $numeroPapeleta;
		/**
		* @var Decimal
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $valorDeposito;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $ordenPago;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $factura;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $numeroFactura;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $fechaFacturacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $rucInstitucion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $observacionSri;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $rutaXml;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $estadoSri;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $numeroAutorizacion;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $fechaAutorizacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $claveAcceso;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $identificadorUsuario;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $observacionEliminacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $estadoMail;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $comprobanteFactura;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $identificadorFirmante;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $tipoEmision;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $tipoSolicitud;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idGrupoSolicitud;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idSolicitud;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $nombreProvincia;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idProvincia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $numeroEstablecimiento;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $puntoEmision;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $utilizado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $notificacionDineroElectronico;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $tipoProceso;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $porcentajeIva;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $numeroOrdenVue;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $estadoConciliacion;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 *
		 */
		protected $rutaRecortadaXML;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_financiero";

	/**
	* Nombre de la tabla: orden_pago
	* 
	 */
	Private $tabla="orden_pago";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_pago";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_financiero"."orden_pago_id_pago_seq';



	/**
	* Constructor
	* $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
	* @parámetro  array|null $datos
	* @retorna void
	 */
	public function __construct(array $datos = null)
	{
		if (is_array($datos)) 
		{
			$this->setOptions($datos);
		}
			$features = new \Zend\Db\TableGateway\Feature\SequenceFeature($this->clavePrimaria, $this->secuencial);
			parent::__construct($this->esquema,$this->tabla, $features);
	}

	/**
	* Permitir el acceso a la propiedad
	* 
	* @parámetro  string $name 
	* @parámetro  mixed $value 
	* @retorna void
	*/
	public function __set($name, $value)
	{
		$method = 'set' . $name;
		if (!method_exists($this, $method)) 
	{
		throw new \Exception('Clase Modelo: OrdenPagoModelo. Propiedad especificada invalida: set'.$name);
	}
	$this->$method($value);
	}

	/**
	* Permitir el acceso a la propiedad
	* 
	* @parámetro  string $name 
	* @retorna mixed
	*/
	public function __get($name)
	{
	$method = 'get' . $name;
	if (!method_exists($this, $method))
	{
	  throw new \Exception('Clase Modelo: OrdenPagoModelo. Propiedad especificada invalida: get'.$name);
	}
	return $this->$method();
	}

	/**
	* Llena el modelo con datos
	* 
	* @parámetro  array $datos 
	* @retorna Modelo
	*/
	 public function setOptions(array $datos)
	{
	$methods = get_class_methods($this);
	foreach ($datos as $key => $value) 
	{
	$key_original = $key;
	 if (strpos($key, '_') > 0) {
	 $aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function($string) {
	return ucfirst($string[1]);
	 }, ucwords($key));
	  $key = $aux;
	}
	$method = 'set' . ucfirst($key);
	if (in_array($method, $methods)) 
	{
	$this->$method($value);
	$this->campos[$key_original] = $key;
	}
	}
	return $this;
	}
	 /**
	 * Recupera los datos validados del modelo y lo retorna en un arreglo
	 *  
	 * @return Array  
	 */
	public function getPrepararDatos()
	 {
	 $claseArray = get_object_vars($this);
	   foreach ($this->campos as $key => $value) {
	 $this->campos[$key] = $claseArray[lcfirst($value)];
	}
	return $this->campos;
	 }

	/**
	* Set $esquema
	*
	* Nombre del esquema del módulo 
	*
	* @parámetro $esquema
	* @return Nombre del esquema de la base de datos
	*/
	public function setEsquema($esquema)
	{
	  $this->esquema = $esquema;
	    return $this;
	}

	/**
	* Get g_financiero
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idPago
	*
	*Identificador secuencial unico de la tabla
	*
	* @parámetro Integer $idPago
	* @return IdPago
	*/
	public function setIdPago($idPago)
	{
	  $this->idPago = (Integer) $idPago;
	    return $this;
	}

	/**
	* Get idPago
	*
	* @return null|Integer
	*/
	public function getIdPago()
	{
		return $this->idPago;
	}

	/**
	* Set identificadorOperador
	*
	*Identificador del operador
	*
	* @parámetro String $identificadorOperador
	* @return IdentificadorOperador
	*/
	public function setIdentificadorOperador($identificadorOperador)
	{
	  $this->identificadorOperador = (String) $identificadorOperador;
	    return $this;
	}

	/**
	* Get identificadorOperador
	*
	* @return null|String
	*/
	public function getIdentificadorOperador()
	{
		return $this->identificadorOperador;
	}

	/**
	* Set numeroSolicitud
	*
	*Número de orden de pago
	*
	* @parámetro String $numeroSolicitud
	* @return NumeroSolicitud
	*/
	public function setNumeroSolicitud($numeroSolicitud)
	{
	  $this->numeroSolicitud = (String) $numeroSolicitud;
	    return $this;
	}

	/**
	* Get numeroSolicitud
	*
	* @return null|String
	*/
	public function getNumeroSolicitud()
	{
		return $this->numeroSolicitud;
	}

	/**
	* Set fechaOrdenPago
	*
	*Fecha de generación de orden de pago
	*
	* @parámetro Date $fechaOrdenPago
	* @return FechaOrdenPago
	*/
	public function setFechaOrdenPago($fechaOrdenPago)
	{
	  $this->fechaOrdenPago = (String) $fechaOrdenPago;
	    return $this;
	}

	/**
	* Get fechaOrdenPago
	*
	* @return null|Date
	*/
	public function getFechaOrdenPago()
	{
		return $this->fechaOrdenPago;
	}

	/**
	* Set totalPagar
	*
	*Total a pagar por la orden de pago
	*
	* @parámetro Decimal $totalPagar
	* @return TotalPagar
	*/
	public function setTotalPagar($totalPagar)
	{
	  $this->totalPagar = (Double) $totalPagar;
	    return $this;
	}

	/**
	* Get totalPagar
	*
	* @return null|Decimal
	*/
	public function getTotalPagar()
	{
		return $this->totalPagar;
	}

	/**
	* Set observacion
	*
	*Observaciones generales
	*
	* @parámetro String $observacion
	* @return Observacion
	*/
	public function setObservacion($observacion)
	{
	  $this->observacion = (String) $observacion;
	    return $this;
	}

	/**
	* Get observacion
	*
	* @return null|String
	*/
	public function getObservacion()
	{
		return $this->observacion;
	}

	/**
	* Set estado
	*
	*Estado de la orden 3-> Creación de orden , 4 -> Creación de factura, 9 -> ELiminación de orden
	*
	* @parámetro Integer $estado
	* @return Estado
	*/
	public function setEstado($estado)
	{
	  $this->estado = (Integer) $estado;
	    return $this;
	}

	/**
	* Get estado
	*
	* @return null|Integer
	*/
	public function getEstado()
	{
		return $this->estado;
	}

	/**
	* Set localizacion
	*
	*Localización en la cual se emitio la orden de pago
	*
	* @parámetro String $localizacion
	* @return Localizacion
	*/
	public function setLocalizacion($localizacion)
	{
	  $this->localizacion = (String) $localizacion;
	    return $this;
	}

	/**
	* Get localizacion
	*
	* @return null|String
	*/
	public function getLocalizacion()
	{
		return $this->localizacion;
	}

	/**
	* Set institucionBancaria
	*
	*No se esta utilizando
	*
	* @parámetro String $institucionBancaria
	* @return InstitucionBancaria
	*/
	public function setInstitucionBancaria($institucionBancaria)
	{
	  $this->institucionBancaria = (String) $institucionBancaria;
	    return $this;
	}

	/**
	* Get institucionBancaria
	*
	* @return null|String
	*/
	public function getInstitucionBancaria()
	{
		return $this->institucionBancaria;
	}

	/**
	* Set numeroPapeleta
	*
	*No se esta utilizando
	*
	* @parámetro String $numeroPapeleta
	* @return NumeroPapeleta
	*/
	public function setNumeroPapeleta($numeroPapeleta)
	{
	  $this->numeroPapeleta = (String) $numeroPapeleta;
	    return $this;
	}

	/**
	* Get numeroPapeleta
	*
	* @return null|String
	*/
	public function getNumeroPapeleta()
	{
		return $this->numeroPapeleta;
	}

	/**
	* Set valorDeposito
	*
	*No se esta utilizando
	*
	* @parámetro Decimal $valorDeposito
	* @return ValorDeposito
	*/
	public function setValorDeposito($valorDeposito)
	{
	  $this->valorDeposito = (Double) $valorDeposito;
	    return $this;
	}

	/**
	* Get valorDeposito
	*
	* @return null|Decimal
	*/
	public function getValorDeposito()
	{
		return $this->valorDeposito;
	}

	/**
	* Set ordenPago
	*
	*Ruta de la orden de pago
	*
	* @parámetro String $ordenPago
	* @return OrdenPago
	*/
	public function setOrdenPago($ordenPago)
	{
	  $this->ordenPago = (String) $ordenPago;
	    return $this;
	}

	/**
	* Get ordenPago
	*
	* @return null|String
	*/
	public function getOrdenPago()
	{
		return $this->ordenPago;
	}

	/**
	* Set factura
	*
	*Ruta de la factura
	*
	* @parámetro String $factura
	* @return Factura
	*/
	public function setFactura($factura)
	{
	  $this->factura = (String) $factura;
	    return $this;
	}

	/**
	* Get factura
	*
	* @return null|String
	*/
	public function getFactura()
	{
		return $this->factura;
	}

	/**
	* Set numeroFactura
	*
	*Número de factura
	*
	* @parámetro String $numeroFactura
	* @return NumeroFactura
	*/
	public function setNumeroFactura($numeroFactura)
	{
	  $this->numeroFactura = (String) $numeroFactura;
	    return $this;
	}

	/**
	* Get numeroFactura
	*
	* @return null|String
	*/
	public function getNumeroFactura()
	{
		return $this->numeroFactura;
	}

	/**
	* Set fechaFacturacion
	*
	*Fecha de facturación
	*
	* @parámetro Date $fechaFacturacion
	* @return FechaFacturacion
	*/
	public function setFechaFacturacion($fechaFacturacion)
	{
	  $this->fechaFacturacion = (String) $fechaFacturacion;
	    return $this;
	}

	/**
	* Get fechaFacturacion
	*
	* @return null|Date
	*/
	public function getFechaFacturacion()
	{
		return $this->fechaFacturacion;
	}

	/**
	* Set rucInstitucion
	*
	*Ruc del establecimiento
	*
	* @parámetro String $rucInstitucion
	* @return RucInstitucion
	*/
	public function setRucInstitucion($rucInstitucion)
	{
	  $this->rucInstitucion = (String) $rucInstitucion;
	    return $this;
	}

	/**
	* Get rucInstitucion
	*
	* @return null|String
	*/
	public function getRucInstitucion()
	{
		return $this->rucInstitucion;
	}

	/**
	* Set observacionSri
	*
	*Observación del sri en caso de existir algún error en la validación del documento
	*
	* @parámetro String $observacionSri
	* @return ObservacionSri
	*/
	public function setObservacionSri($observacionSri)
	{
	  $this->observacionSri = (String) $observacionSri;
	    return $this;
	}

	/**
	* Get observacionSri
	*
	* @return null|String
	*/
	public function getObservacionSri()
	{
		return $this->observacionSri;
	}

	/**
	* Set rutaXml
	*
	*Ruta del archivo  XML autorizado
	*
	* @parámetro String $rutaXml
	* @return RutaXml
	*/
	public function setRutaXml($rutaXml)
	{
	  $this->rutaXml = (String) $rutaXml;
	    return $this;
	}

	/**
	* Get rutaXml
	*
	* @return null|String
	*/
	public function getRutaXml()
	{
		return $this->rutaXml;
	}

	/**
	* Set estadoSri
	*
	*Estado del web services SRI RECEPTOR, POR ATENDER, RECIBIDA, AUTORIZADA, NO AUTORIZADA, FINALIZADO, DEVUELTA, ANULADO
	*
	* @parámetro String $estadoSri
	* @return EstadoSri
	*/
	public function setEstadoSri($estadoSri)
	{
	  $this->estadoSri = (String) $estadoSri;
	    return $this;
	}

	/**
	* Get estadoSri
	*
	* @return null|String
	*/
	public function getEstadoSri()
	{
		return $this->estadoSri;
	}

	/**
	* Set numeroAutorizacion
	*
	*Número de autorización generado para el SRI
	*
	* @parámetro String $numeroAutorizacion
	* @return NumeroAutorizacion
	*/
	public function setNumeroAutorizacion($numeroAutorizacion)
	{
	  $this->numeroAutorizacion = (String) $numeroAutorizacion;
	    return $this;
	}

	/**
	* Get numeroAutorizacion
	*
	* @return null|String
	*/
	public function getNumeroAutorizacion()
	{
		return $this->numeroAutorizacion;
	}

	/**
	* Set fechaAutorizacion
	*
	*Fecha de autorización del comprobante SRI
	*
	* @parámetro Date $fechaAutorizacion
	* @return FechaAutorizacion
	*/
	public function setFechaAutorizacion($fechaAutorizacion)
	{
	  $this->fechaAutorizacion = (String) $fechaAutorizacion;
	    return $this;
	}

	/**
	* Get fechaAutorizacion
	*
	* @return null|Date
	*/
	public function getFechaAutorizacion()
	{
		return $this->fechaAutorizacion;
	}

	/**
	* Set claveAcceso
	*
	*Número de autorización generado para el SRI
	*
	* @parámetro String $claveAcceso
	* @return ClaveAcceso
	*/
	public function setClaveAcceso($claveAcceso)
	{
	  $this->claveAcceso = (String) $claveAcceso;
	    return $this;
	}

	/**
	* Get claveAcceso
	*
	* @return null|String
	*/
	public function getClaveAcceso()
	{
		return $this->claveAcceso;
	}

	/**
	* Set identificadorUsuario
	*
	*Identificador del usuario que gnero la orden de pago
	*
	* @parámetro String $identificadorUsuario
	* @return IdentificadorUsuario
	*/
	public function setIdentificadorUsuario($identificadorUsuario)
	{
	  $this->identificadorUsuario = (String) $identificadorUsuario;
	    return $this;
	}

	/**
	* Get identificadorUsuario
	*
	* @return null|String
	*/
	public function getIdentificadorUsuario()
	{
		return $this->identificadorUsuario;
	}

	/**
	* Set observacionEliminacion
	*
	*Observación en el caso de eliminación
	*
	* @parámetro String $observacionEliminacion
	* @return ObservacionEliminacion
	*/
	public function setObservacionEliminacion($observacionEliminacion)
	{
	  $this->observacionEliminacion = (String) $observacionEliminacion;
	    return $this;
	}

	/**
	* Get observacionEliminacion
	*
	* @return null|String
	*/
	public function getObservacionEliminacion()
	{
		return $this->observacionEliminacion;
	}

	/**
	* Set estadoMail
	*
	*Estado del email Por enviar, Mail enviado.
	*
	* @parámetro String $estadoMail
	* @return EstadoMail
	*/
	public function setEstadoMail($estadoMail)
	{
	  $this->estadoMail = (String) $estadoMail;
	    return $this;
	}

	/**
	* Get estadoMail
	*
	* @return null|String
	*/
	public function getEstadoMail()
	{
		return $this->estadoMail;
	}

	/**
	* Set comprobanteFactura
	*
	*Ruta del comprobante de factura
	*
	* @parámetro String $comprobanteFactura
	* @return ComprobanteFactura
	*/
	public function setComprobanteFactura($comprobanteFactura)
	{
	  $this->comprobanteFactura = (String) $comprobanteFactura;
	    return $this;
	}

	/**
	* Get comprobanteFactura
	*
	* @return null|String
	*/
	public function getComprobanteFactura()
	{
		return $this->comprobanteFactura;
	}

	/**
	* Set identificadorFirmante
	*
	*Identificador del usuario que finaliza la factura
	*
	* @parámetro String $identificadorFirmante
	* @return IdentificadorFirmante
	*/
	public function setIdentificadorFirmante($identificadorFirmante)
	{
	  $this->identificadorFirmante = (String) $identificadorFirmante;
	    return $this;
	}

	/**
	* Get identificadorFirmante
	*
	* @return null|String
	*/
	public function getIdentificadorFirmante()
	{
		return $this->identificadorFirmante;
	}

	/**
	* Set tipoEmision
	*
	*No se esta utilizando
	*
	* @parámetro String $tipoEmision
	* @return TipoEmision
	*/
	public function setTipoEmision($tipoEmision)
	{
	  $this->tipoEmision = (String) $tipoEmision;
	    return $this;
	}

	/**
	* Get tipoEmision
	*
	* @return null|String
	*/
	public function getTipoEmision()
	{
		return $this->tipoEmision;
	}

	/**
	* Set tipoSolicitud
	*
	*Tipo de solicitud de la orden Emisión Emisión de Etiquetas, mercancias Sin Valor Comercial Exportacion, Importación, mercancias Sin Valor Comercial Importacion, Otros, Fitosanitario, Operadores, Ingreso Caja
	*
	* @parámetro String $tipoSolicitud
	* @return TipoSolicitud
	*/
	public function setTipoSolicitud($tipoSolicitud)
	{
	  $this->tipoSolicitud = (String) $tipoSolicitud;
	    return $this;
	}

	/**
	* Get tipoSolicitud
	*
	* @return null|String
	*/
	public function getTipoSolicitud()
	{
		return $this->tipoSolicitud;
	}

	/**
	* Set idGrupoSolicitud
	*
	*Grupo a la cual pertenece la operación
	*
	* @parámetro Integer $idGrupoSolicitud
	* @return IdGrupoSolicitud
	*/
	public function setIdGrupoSolicitud($idGrupoSolicitud)
	{
	  $this->idGrupoSolicitud = (Integer) $idGrupoSolicitud;
	    return $this;
	}

	/**
	* Get idGrupoSolicitud
	*
	* @return null|Integer
	*/
	public function getIdGrupoSolicitud()
	{
		return $this->idGrupoSolicitud;
	}

	/**
	* Set idSolicitud
	*
	*Identificador de la tabla del tipo de solicitud
	*
	* @parámetro String $idSolicitud
	* @return IdSolicitud
	*/
	public function setIdSolicitud($idSolicitud)
	{
	  $this->idSolicitud = (String) $idSolicitud;
	    return $this;
	}

	/**
	* Get idSolicitud
	*
	* @return null|String
	*/
	public function getIdSolicitud()
	{
		return $this->idSolicitud;
	}

	/**
	* Set nombreProvincia
	*
	*Nombre de la provincia en la cual fue emitida la orden de pago
	*
	* @parámetro String $nombreProvincia
	* @return NombreProvincia
	*/
	public function setNombreProvincia($nombreProvincia)
	{
	  $this->nombreProvincia = (String) $nombreProvincia;
	    return $this;
	}

	/**
	* Get nombreProvincia
	*
	* @return null|String
	*/
	public function getNombreProvincia()
	{
		return $this->nombreProvincia;
	}

	/**
	* Set idProvincia
	*
	*Identificador de la provincia
	*
	* @parámetro Integer $idProvincia
	* @return IdProvincia
	*/
	public function setIdProvincia($idProvincia)
	{
	  $this->idProvincia = (Integer) $idProvincia;
	    return $this;
	}

	/**
	* Get idProvincia
	*
	* @return null|Integer
	*/
	public function getIdProvincia()
	{
		return $this->idProvincia;
	}

	/**
	* Set numeroEstablecimiento
	*
	*Número del establecimiento de recaudación
	*
	* @parámetro String $numeroEstablecimiento
	* @return NumeroEstablecimiento
	*/
	public function setNumeroEstablecimiento($numeroEstablecimiento)
	{
	  $this->numeroEstablecimiento = (String) $numeroEstablecimiento;
	    return $this;
	}

	/**
	* Get numeroEstablecimiento
	*
	* @return null|String
	*/
	public function getNumeroEstablecimiento()
	{
		return $this->numeroEstablecimiento;
	}

	/**
	* Set puntoEmision
	*
	*Punto de emisión de recaudación
	*
	* @parámetro String $puntoEmision
	* @return PuntoEmision
	*/
	public function setPuntoEmision($puntoEmision)
	{
	  $this->puntoEmision = (String) $puntoEmision;
	    return $this;
	}

	/**
	* Get puntoEmision
	*
	* @return null|String
	*/
	public function getPuntoEmision()
	{
		return $this->puntoEmision;
	}

	/**
	* Set utilizado
	*
	*Indica si la orden ha sido consumida TRUE, FALSE
	*
	* @parámetro String $utilizado
	* @return Utilizado
	*/
	public function setUtilizado($utilizado)
	{
	  $this->utilizado = (String) $utilizado;
	    return $this;
	}

	/**
	* Get utilizado
	*
	* @return null|String
	*/
	public function getUtilizado()
	{
		return $this->utilizado;
	}

	/**
	* Set notificacionDineroElectronico
	*
	*Bandera de verificación de pago electronico
	*
	* @parámetro String $notificacionDineroElectronico
	* @return NotificacionDineroElectronico
	*/
	public function setNotificacionDineroElectronico($notificacionDineroElectronico)
	{
	  $this->notificacionDineroElectronico = (String) $notificacionDineroElectronico;
	    return $this;
	}

	/**
	* Get notificacionDineroElectronico
	*
	* @return null|String
	*/
	public function getNotificacionDineroElectronico()
	{
		return $this->notificacionDineroElectronico;
	}

	/**
	* Set tipoProceso
	*
	*Tipo de proceso de la orden de pago factura o comprobante de factura
	*
	* @parámetro String $tipoProceso
	* @return TipoProceso
	*/
	public function setTipoProceso($tipoProceso)
	{
	  $this->tipoProceso = (String) $tipoProceso;
	    return $this;
	}

	/**
	* Get tipoProceso
	*
	* @return null|String
	*/
	public function getTipoProceso()
	{
		return $this->tipoProceso;
	}

	/**
	* Set porcentajeIva
	*
	*Porcentaje iva con el cual fue emitida la orden de pago
	*
	* @parámetro Integer $porcentajeIva
	* @return PorcentajeIva
	*/
	public function setPorcentajeIva($porcentajeIva)
	{
	  $this->porcentajeIva = (Integer) $porcentajeIva;
	    return $this;
	}

	/**
	* Get porcentajeIva
	*
	* @return null|Integer
	*/
	public function getPorcentajeIva()
	{
		return $this->porcentajeIva;
	}

	/**
	* Set numeroOrdenVue
	*
	*Número de orden VUE
	*
	* @parámetro String $numeroOrdenVue
	* @return NumeroOrdenVue
	*/
	public function setNumeroOrdenVue($numeroOrdenVue)
	{
	  $this->numeroOrdenVue = (String) $numeroOrdenVue;
	    return $this;
	}

	/**
	* Get numeroOrdenVue
	*
	* @return null|String
	*/
	public function getNumeroOrdenVue()
	{
		return $this->numeroOrdenVue;
	}

	/**
	* Set estadoConciliacion
	*
	*Bandera que indica si la orden de pago fue conciliada
	*
	* @parámetro String $estadoConciliacion
	* @return EstadoConciliacion
	*/
	public function setEstadoConciliacion($estadoConciliacion)
	{
	  $this->estadoConciliacion = (String) $estadoConciliacion;
	    return $this;
	}

	/**
	* Get estadoConciliacion
	*
	* @return null|String
	*/
	public function getEstadoConciliacion()
	{
		return $this->estadoConciliacion;
	}
	
	/**
	 * Set rutaRecortadaXML
	 *
	 *Bandera que indica si la orden de pago fue conciliada
	 *
	 * @parámetro String $estadoConciliacion
	 * @return EstadoConciliacion
	 */
	public function setRutaRecortadaXML($rutaRecortadaXML)
	{
	    $this->rutaRecortadaXML = (String) $rutaRecortadaXML;
	    return $this;
	}
	
	/**
	 * Get rutaRecortadaXML
	 *
	 * @return null|String
	 */
	public function getRutaRecortadaXML()
	{
	    return $this->rutaRecortadaXML;
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		return parent::guardar($datos);
	}

	/**
	* Actualiza un registro actual
	* @param array $datos
	* @param int $id
	* @return int
	*/
	public function actualizar(Array $datos,$id)
	{
		 return parent::actualizar($datos, $this->clavePrimaria . " = " . $id);
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		return parent::borrar($this->clavePrimaria . " = " . $id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return OrdenPagoModelo
	*/
	public function buscar($id)
	{
		return $this->setOptions(parent::buscar($this->clavePrimaria . " = " . $id));
		return $this;
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return parent::buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return parent::buscarLista($where);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function ejecutarConsulta($consulta)
	{
		 return parent::ejecutarConsulta($consulta);
	}

}
