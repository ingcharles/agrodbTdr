<?php
/**
 * Modelo CertificadoClvModelo
 *
 * Este archivo se complementa con el archivo CertificadoClvLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-09-16
 * @uses CertificadoClvModelo
 * @package CertificadoLibreVenta
 * @subpackage Modelos
 */
namespace Agrodb\CertificadoLibreVenta\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class CertificadoClvModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idClv;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $identificadorTitulares;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $identificadorOperador;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $tipoProducto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $tipoDatosCertificado;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $nombreDatosCertificado;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $direccionDatosCertificado;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $subpartidaArancelaria;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idProducto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $codigoProducto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $nombreProducto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $nombreComercialProducto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idPresentacionComercialProducto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $presentacionComercialProducto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $clasificacionProducto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $formaFarmaceutica;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $usoProducto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $especieDestino;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $formulacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $numeroRegistroAgrocalidad;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idPais;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $nombrePais;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $observacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $observacionClv;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $codigoCertificado;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $estado;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idVue;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $fechaInscripcionProducto;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $fechaInicio;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $fechaVencimiento;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $fechaModificacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $rutaArchivo;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $fechaVigenciaProducto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $subpartidaProductoVue;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $codigoProductoVue;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $fechaCreacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Indica el motivo por el cual la solicitud fue rechazada si la misma no fue atendida por parte de usuario en un estado de verificación de pago o subsanación en base a decreto 68
	 */
	protected $observacionEliminacion;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_clv";

	/**
	 * Nombre de la tabla: certificado_clv
	 */
	private $tabla = "certificado_clv";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_clv";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_clv"."CertificadoClv_id_clv_seq';

	/**
	 * Constructor
	 * $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
	 *
	 * @parámetro  array|null $datos
	 * @retorna void
	 */
	public function __construct(array $datos = null){
		if (is_array($datos)){
			$this->setOptions($datos);
		}
		$features = new \Zend\Db\TableGateway\Feature\SequenceFeature($this->clavePrimaria, $this->secuencial);
		parent::__construct($this->esquema, $this->tabla, $features);
	}

	/**
	 * Permitir el acceso a la propiedad
	 *
	 * @parámetro  string $name
	 * @parámetro  mixed $value
	 * @retorna void
	 */
	public function __set($name, $value){
		$method = 'set' . $name;
		if (! method_exists($this, $method)){
			throw new \Exception('Clase Modelo: CertificadoClvModelo. Propiedad especificada invalida: set' . $name);
		}
		$this->$method($value);
	}

	/**
	 * Permitir el acceso a la propiedad
	 *
	 * @parámetro  string $name
	 * @retorna mixed
	 */
	public function __get($name){
		$method = 'get' . $name;
		if (! method_exists($this, $method)){
			throw new \Exception('Clase Modelo: CertificadoClvModelo. Propiedad especificada invalida: get' . $name);
		}
		return $this->$method();
	}

	/**
	 * Llena el modelo con datos
	 *
	 * @parámetro  array $datos
	 * @retorna Modelo
	 */
	public function setOptions(array $datos){
		$methods = get_class_methods($this);
		foreach ($datos as $key => $value){
			$key_original = $key;
			if (strpos($key, '_') > 0){
				$aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function ($string){
					return ucfirst($string[1]);
				}, ucwords($key));
				$key = $aux;
			}
			$method = 'set' . ucfirst($key);
			if (in_array($method, $methods)){
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
	public function getPrepararDatos(){
		$claseArray = get_object_vars($this);
		foreach ($this->campos as $key => $value){
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
	public function setEsquema($esquema){
		$this->esquema = $esquema;
		return $this;
	}

	/**
	 * Get g_clv
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idClv
	 *
	 *
	 *
	 * @parámetro Integer $idClv
	 * @return IdClv
	 */
	public function setIdClv($idClv){
		$this->idClv = (integer) $idClv;
		return $this;
	}

	/**
	 * Get idClv
	 *
	 * @return null|Integer
	 */
	public function getIdClv(){
		return $this->idClv;
	}

	/**
	 * Set identificadorTitulares
	 *
	 *
	 *
	 * @parámetro String $identificadorTitulares
	 * @return IdentificadorTitulares
	 */
	public function setIdentificadorTitulares($identificadorTitulares){
		$this->identificadorTitulares = (string) $identificadorTitulares;
		return $this;
	}

	/**
	 * Get identificadorTitulares
	 *
	 * @return null|String
	 */
	public function getIdentificadorTitulares(){
		return $this->identificadorTitulares;
	}

	/**
	 * Set identificadorOperador
	 *
	 *
	 *
	 * @parámetro String $identificadorOperador
	 * @return IdentificadorOperador
	 */
	public function setIdentificadorOperador($identificadorOperador){
		$this->identificadorOperador = (string) $identificadorOperador;
		return $this;
	}

	/**
	 * Get identificadorOperador
	 *
	 * @return null|String
	 */
	public function getIdentificadorOperador(){
		return $this->identificadorOperador;
	}

	/**
	 * Set tipoProducto
	 *
	 *
	 *
	 * @parámetro String $tipoProducto
	 * @return TipoProducto
	 */
	public function setTipoProducto($tipoProducto){
		$this->tipoProducto = (string) $tipoProducto;
		return $this;
	}

	/**
	 * Get tipoProducto
	 *
	 * @return null|String
	 */
	public function getTipoProducto(){
		return $this->tipoProducto;
	}

	/**
	 * Set tipoDatosCertificado
	 *
	 *
	 *
	 * @parámetro String $tipoDatosCertificado
	 * @return TipoDatosCertificado
	 */
	public function setTipoDatosCertificado($tipoDatosCertificado){
		$this->tipoDatosCertificado = (string) $tipoDatosCertificado;
		return $this;
	}

	/**
	 * Get tipoDatosCertificado
	 *
	 * @return null|String
	 */
	public function getTipoDatosCertificado(){
		return $this->tipoDatosCertificado;
	}

	/**
	 * Set nombreDatosCertificado
	 *
	 *
	 *
	 * @parámetro String $nombreDatosCertificado
	 * @return NombreDatosCertificado
	 */
	public function setNombreDatosCertificado($nombreDatosCertificado){
		$this->nombreDatosCertificado = (string) $nombreDatosCertificado;
		return $this;
	}

	/**
	 * Get nombreDatosCertificado
	 *
	 * @return null|String
	 */
	public function getNombreDatosCertificado(){
		return $this->nombreDatosCertificado;
	}

	/**
	 * Set direccionDatosCertificado
	 *
	 *
	 *
	 * @parámetro String $direccionDatosCertificado
	 * @return DireccionDatosCertificado
	 */
	public function setDireccionDatosCertificado($direccionDatosCertificado){
		$this->direccionDatosCertificado = (string) $direccionDatosCertificado;
		return $this;
	}

	/**
	 * Get direccionDatosCertificado
	 *
	 * @return null|String
	 */
	public function getDireccionDatosCertificado(){
		return $this->direccionDatosCertificado;
	}

	/**
	 * Set subpartidaArancelaria
	 *
	 *
	 *
	 * @parámetro String $subpartidaArancelaria
	 * @return SubpartidaArancelaria
	 */
	public function setSubpartidaArancelaria($subpartidaArancelaria){
		$this->subpartidaArancelaria = (string) $subpartidaArancelaria;
		return $this;
	}

	/**
	 * Get subpartidaArancelaria
	 *
	 * @return null|String
	 */
	public function getSubpartidaArancelaria(){
		return $this->subpartidaArancelaria;
	}

	/**
	 * Set idProducto
	 *
	 *
	 *
	 * @parámetro Integer $idProducto
	 * @return IdProducto
	 */
	public function setIdProducto($idProducto){
		$this->idProducto = (integer) $idProducto;
		return $this;
	}

	/**
	 * Get idProducto
	 *
	 * @return null|Integer
	 */
	public function getIdProducto(){
		return $this->idProducto;
	}

	/**
	 * Set codigoProducto
	 *
	 *
	 *
	 * @parámetro String $codigoProducto
	 * @return CodigoProducto
	 */
	public function setCodigoProducto($codigoProducto){
		$this->codigoProducto = (string) $codigoProducto;
		return $this;
	}

	/**
	 * Get codigoProducto
	 *
	 * @return null|String
	 */
	public function getCodigoProducto(){
		return $this->codigoProducto;
	}

	/**
	 * Set nombreProducto
	 *
	 *
	 *
	 * @parámetro String $nombreProducto
	 * @return NombreProducto
	 */
	public function setNombreProducto($nombreProducto){
		$this->nombreProducto = (string) $nombreProducto;
		return $this;
	}

	/**
	 * Get nombreProducto
	 *
	 * @return null|String
	 */
	public function getNombreProducto(){
		return $this->nombreProducto;
	}

	/**
	 * Set nombreComercialProducto
	 *
	 *
	 *
	 * @parámetro String $nombreComercialProducto
	 * @return NombreComercialProducto
	 */
	public function setNombreComercialProducto($nombreComercialProducto){
		$this->nombreComercialProducto = (string) $nombreComercialProducto;
		return $this;
	}

	/**
	 * Get nombreComercialProducto
	 *
	 * @return null|String
	 */
	public function getNombreComercialProducto(){
		return $this->nombreComercialProducto;
	}

	/**
	 * Set idPresentacionComercialProducto
	 *
	 *
	 *
	 * @parámetro String $idPresentacionComercialProducto
	 * @return IdPresentacionComercialProducto
	 */
	public function setIdPresentacionComercialProducto($idPresentacionComercialProducto){
		$this->idPresentacionComercialProducto = (string) $idPresentacionComercialProducto;
		return $this;
	}

	/**
	 * Get idPresentacionComercialProducto
	 *
	 * @return null|String
	 */
	public function getIdPresentacionComercialProducto(){
		return $this->idPresentacionComercialProducto;
	}

	/**
	 * Set presentacionComercialProducto
	 *
	 *
	 *
	 * @parámetro String $presentacionComercialProducto
	 * @return PresentacionComercialProducto
	 */
	public function setPresentacionComercialProducto($presentacionComercialProducto){
		$this->presentacionComercialProducto = (string) $presentacionComercialProducto;
		return $this;
	}

	/**
	 * Get presentacionComercialProducto
	 *
	 * @return null|String
	 */
	public function getPresentacionComercialProducto(){
		return $this->presentacionComercialProducto;
	}

	/**
	 * Set clasificacionProducto
	 *
	 *
	 *
	 * @parámetro String $clasificacionProducto
	 * @return ClasificacionProducto
	 */
	public function setClasificacionProducto($clasificacionProducto){
		$this->clasificacionProducto = (string) $clasificacionProducto;
		return $this;
	}

	/**
	 * Get clasificacionProducto
	 *
	 * @return null|String
	 */
	public function getClasificacionProducto(){
		return $this->clasificacionProducto;
	}

	/**
	 * Set formaFarmaceutica
	 *
	 *
	 *
	 * @parámetro String $formaFarmaceutica
	 * @return FormaFarmaceutica
	 */
	public function setFormaFarmaceutica($formaFarmaceutica){
		$this->formaFarmaceutica = (string) $formaFarmaceutica;
		return $this;
	}

	/**
	 * Get formaFarmaceutica
	 *
	 * @return null|String
	 */
	public function getFormaFarmaceutica(){
		return $this->formaFarmaceutica;
	}

	/**
	 * Set usoProducto
	 *
	 *
	 *
	 * @parámetro String $usoProducto
	 * @return UsoProducto
	 */
	public function setUsoProducto($usoProducto){
		$this->usoProducto = (string) $usoProducto;
		return $this;
	}

	/**
	 * Get usoProducto
	 *
	 * @return null|String
	 */
	public function getUsoProducto(){
		return $this->usoProducto;
	}

	/**
	 * Set especieDestino
	 *
	 *
	 *
	 * @parámetro String $especieDestino
	 * @return EspecieDestino
	 */
	public function setEspecieDestino($especieDestino){
		$this->especieDestino = (string) $especieDestino;
		return $this;
	}

	/**
	 * Get especieDestino
	 *
	 * @return null|String
	 */
	public function getEspecieDestino(){
		return $this->especieDestino;
	}

	/**
	 * Set formulacion
	 *
	 *
	 *
	 * @parámetro String $formulacion
	 * @return Formulacion
	 */
	public function setFormulacion($formulacion){
		$this->formulacion = (string) $formulacion;
		return $this;
	}

	/**
	 * Get formulacion
	 *
	 * @return null|String
	 */
	public function getFormulacion(){
		return $this->formulacion;
	}

	/**
	 * Set numeroRegistroAgrocalidad
	 *
	 *
	 *
	 * @parámetro String $numeroRegistroAgrocalidad
	 * @return NumeroRegistroAgrocalidad
	 */
	public function setNumeroRegistroAgrocalidad($numeroRegistroAgrocalidad){
		$this->numeroRegistroAgrocalidad = (string) $numeroRegistroAgrocalidad;
		return $this;
	}

	/**
	 * Get numeroRegistroAgrocalidad
	 *
	 * @return null|String
	 */
	public function getNumeroRegistroAgrocalidad(){
		return $this->numeroRegistroAgrocalidad;
	}

	/**
	 * Set idPais
	 *
	 *
	 *
	 * @parámetro Integer $idPais
	 * @return IdPais
	 */
	public function setIdPais($idPais){
		$this->idPais = (integer) $idPais;
		return $this;
	}

	/**
	 * Get idPais
	 *
	 * @return null|Integer
	 */
	public function getIdPais(){
		return $this->idPais;
	}

	/**
	 * Set nombrePais
	 *
	 *
	 *
	 * @parámetro String $nombrePais
	 * @return NombrePais
	 */
	public function setNombrePais($nombrePais){
		$this->nombrePais = (string) $nombrePais;
		return $this;
	}

	/**
	 * Get nombrePais
	 *
	 * @return null|String
	 */
	public function getNombrePais(){
		return $this->nombrePais;
	}

	/**
	 * Set observacion
	 *
	 *
	 *
	 * @parámetro String $observacion
	 * @return Observacion
	 */
	public function setObservacion($observacion){
		$this->observacion = (string) $observacion;
		return $this;
	}

	/**
	 * Get observacion
	 *
	 * @return null|String
	 */
	public function getObservacion(){
		return $this->observacion;
	}

	/**
	 * Set observacionClv
	 *
	 *
	 *
	 * @parámetro String $observacionClv
	 * @return ObservacionClv
	 */
	public function setObservacionClv($observacionClv){
		$this->observacionClv = (string) $observacionClv;
		return $this;
	}

	/**
	 * Get observacionClv
	 *
	 * @return null|String
	 */
	public function getObservacionClv(){
		return $this->observacionClv;
	}

	/**
	 * Set codigoCertificado
	 *
	 *
	 *
	 * @parámetro String $codigoCertificado
	 * @return CodigoCertificado
	 */
	public function setCodigoCertificado($codigoCertificado){
		$this->codigoCertificado = (string) $codigoCertificado;
		return $this;
	}

	/**
	 * Get codigoCertificado
	 *
	 * @return null|String
	 */
	public function getCodigoCertificado(){
		return $this->codigoCertificado;
	}

	/**
	 * Set estado
	 *
	 *
	 *
	 * @parámetro String $estado
	 * @return Estado
	 */
	public function setEstado($estado){
		$this->estado = (string) $estado;
		return $this;
	}

	/**
	 * Get estado
	 *
	 * @return null|String
	 */
	public function getEstado(){
		return $this->estado;
	}

	/**
	 * Set idVue
	 *
	 *
	 *
	 * @parámetro String $idVue
	 * @return IdVue
	 */
	public function setIdVue($idVue){
		$this->idVue = (string) $idVue;
		return $this;
	}

	/**
	 * Get idVue
	 *
	 * @return null|String
	 */
	public function getIdVue(){
		return $this->idVue;
	}

	/**
	 * Set fechaInscripcionProducto
	 *
	 *
	 *
	 * @parámetro Date $fechaInscripcionProducto
	 * @return FechaInscripcionProducto
	 */
	public function setFechaInscripcionProducto($fechaInscripcionProducto){
		$this->fechaInscripcionProducto = (string) $fechaInscripcionProducto;
		return $this;
	}

	/**
	 * Get fechaInscripcionProducto
	 *
	 * @return null|Date
	 */
	public function getFechaInscripcionProducto(){
		return $this->fechaInscripcionProducto;
	}

	/**
	 * Set fechaInicio
	 *
	 *
	 *
	 * @parámetro Date $fechaInicio
	 * @return FechaInicio
	 */
	public function setFechaInicio($fechaInicio){
		$this->fechaInicio = (string) $fechaInicio;
		return $this;
	}

	/**
	 * Get fechaInicio
	 *
	 * @return null|Date
	 */
	public function getFechaInicio(){
		return $this->fechaInicio;
	}

	/**
	 * Set fechaVencimiento
	 *
	 *
	 *
	 * @parámetro Date $fechaVencimiento
	 * @return FechaVencimiento
	 */
	public function setFechaVencimiento($fechaVencimiento){
		$this->fechaVencimiento = (string) $fechaVencimiento;
		return $this;
	}

	/**
	 * Get fechaVencimiento
	 *
	 * @return null|Date
	 */
	public function getFechaVencimiento(){
		return $this->fechaVencimiento;
	}

	/**
	 * Set fechaModificacion
	 *
	 *
	 *
	 * @parámetro Date $fechaModificacion
	 * @return FechaModificacion
	 */
	public function setFechaModificacion($fechaModificacion){
		$this->fechaModificacion = (string) $fechaModificacion;
		return $this;
	}

	/**
	 * Get fechaModificacion
	 *
	 * @return null|Date
	 */
	public function getFechaModificacion(){
		return $this->fechaModificacion;
	}

	/**
	 * Set rutaArchivo
	 *
	 *
	 *
	 * @parámetro String $rutaArchivo
	 * @return RutaArchivo
	 */
	public function setRutaArchivo($rutaArchivo){
		$this->rutaArchivo = (string) $rutaArchivo;
		return $this;
	}

	/**
	 * Get rutaArchivo
	 *
	 * @return null|String
	 */
	public function getRutaArchivo(){
		return $this->rutaArchivo;
	}

	/**
	 * Set fechaVigenciaProducto
	 *
	 *
	 *
	 * @parámetro Date $fechaVigenciaProducto
	 * @return FechaVigenciaProducto
	 */
	public function setFechaVigenciaProducto($fechaVigenciaProducto){
		$this->fechaVigenciaProducto = (string) $fechaVigenciaProducto;
		return $this;
	}

	/**
	 * Get fechaVigenciaProducto
	 *
	 * @return null|Date
	 */
	public function getFechaVigenciaProducto(){
		return $this->fechaVigenciaProducto;
	}

	/**
	 * Set subpartidaProductoVue
	 *
	 *
	 *
	 * @parámetro String $subpartidaProductoVue
	 * @return SubpartidaProductoVue
	 */
	public function setSubpartidaProductoVue($subpartidaProductoVue){
		$this->subpartidaProductoVue = (string) $subpartidaProductoVue;
		return $this;
	}

	/**
	 * Get subpartidaProductoVue
	 *
	 * @return null|String
	 */
	public function getSubpartidaProductoVue(){
		return $this->subpartidaProductoVue;
	}

	/**
	 * Set codigoProductoVue
	 *
	 *
	 *
	 * @parámetro String $codigoProductoVue
	 * @return CodigoProductoVue
	 */
	public function setCodigoProductoVue($codigoProductoVue){
		$this->codigoProductoVue = (string) $codigoProductoVue;
		return $this;
	}

	/**
	 * Get codigoProductoVue
	 *
	 * @return null|String
	 */
	public function getCodigoProductoVue(){
		return $this->codigoProductoVue;
	}

	/**
	 * Set fechaCreacion
	 *
	 *
	 *
	 * @parámetro Date $fechaCreacion
	 * @return FechaCreacion
	 */
	public function setFechaCreacion($fechaCreacion){
		$this->fechaCreacion = (string) $fechaCreacion;
		return $this;
	}

	/**
	 * Get fechaCreacion
	 *
	 * @return null|Date
	 */
	public function getFechaCreacion(){
		return $this->fechaCreacion;
	}

	/**
	 * Set observacionEliminacion
	 *
	 * Indica el motivo por el cual la solicitud fue rechazada si la misma no fue atendida por parte de usuario en un estado de verificación de pago o subsanación en base a decreto 68
	 *
	 * @parámetro String $observacionEliminacion
	 * @return ObservacionEliminacion
	 */
	public function setObservacionEliminacion($observacionEliminacion){
		$this->observacionEliminacion = (string) $observacionEliminacion;
		return $this;
	}

	/**
	 * Get observacionEliminacion
	 *
	 * @return null|String
	 */
	public function getObservacionEliminacion(){
		return $this->observacionEliminacion;
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		return parent::guardar($datos);
	}

	/**
	 * Actualiza un registro actual
	 *
	 * @param array $datos
	 * @param int $id
	 * @return int
	 */
	public function actualizar(Array $datos, $id){
		return parent::actualizar($datos, $this->clavePrimaria . " = " . $id);
	}

	/**
	 * Borra el registro actual
	 *
	 * @param
	 *        	string Where|array $where
	 * @return int
	 */
	public function borrar($id){
		return parent::borrar($this->clavePrimaria . " = " . $id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return CertificadoClvModelo
	 */
	public function buscar($id){
		return $this->setOptions(parent::buscar($this->clavePrimaria . " = " . $id));
		return $this;
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return parent::buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return parent::buscarLista($where);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function ejecutarConsulta($consulta){
		return parent::ejecutarConsulta($consulta);
	}
}
