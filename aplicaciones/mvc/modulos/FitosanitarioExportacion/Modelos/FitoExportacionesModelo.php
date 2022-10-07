<?php
/**
 * Modelo FitoExportacionesModelo
 *
 * Este archivo se complementa con el archivo FitoExportacionesLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-09-16
 * @uses FitoExportacionesModelo
 * @package FitosanitarioExportacion
 * @subpackage Modelos
 */
namespace Agrodb\FitosanitarioExportacion\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class FitoExportacionesModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idFitoExportacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $nombreImportador;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $direccionImportador;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idPaisDestino;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $paisDestino;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $nombreAgenciaCarga;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $nombreMarcas;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idPuertoDestino;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $puertoDestino;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idPuertoEmbarque;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $puertoEmbarque;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $transporte;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $fechaEmbarque;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $numeroViaje;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $tratamientoRealizado;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $duracionTratamiento;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $temperaturaTratamiento;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $fechaTratamiento;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $quimicoTratamiento;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $concentracionProducto;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idProvincia;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $provincia;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $observacionOperador;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $reporteInspeccion;

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
	protected $idVue;

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
	protected $observacion;

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
	protected $fechaVigencia;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $fechaModificacion;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idPaisEmbarque;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $paisEmbarque;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idPaisOrigen;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $paisOrigen;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $informeRequisitos;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $lugarInspeccion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $unidadTemperatura;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $identificadorSolicitante;

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
	 *     
	 */
	protected $productoOrganico;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $numeroProductoOrganico;

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
	private $esquema = "g_fito_exportacion";

	/**
	 * Nombre de la tabla: fito_exportaciones
	 */
	private $tabla = "fito_exportaciones";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_fito_exportacion";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_fito_exportacion"."FitoExportaciones_id_fito_exportacion_seq';

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
			throw new \Exception('Clase Modelo: FitoExportacionesModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: FitoExportacionesModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_fito_exportacion
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idFitoExportacion
	 *
	 *
	 *
	 * @parámetro Integer $idFitoExportacion
	 * @return IdFitoExportacion
	 */
	public function setIdFitoExportacion($idFitoExportacion){
		$this->idFitoExportacion = (integer) $idFitoExportacion;
		return $this;
	}

	/**
	 * Get idFitoExportacion
	 *
	 * @return null|Integer
	 */
	public function getIdFitoExportacion(){
		return $this->idFitoExportacion;
	}

	/**
	 * Set nombreImportador
	 *
	 *
	 *
	 * @parámetro String $nombreImportador
	 * @return NombreImportador
	 */
	public function setNombreImportador($nombreImportador){
		$this->nombreImportador = (string) $nombreImportador;
		return $this;
	}

	/**
	 * Get nombreImportador
	 *
	 * @return null|String
	 */
	public function getNombreImportador(){
		return $this->nombreImportador;
	}

	/**
	 * Set direccionImportador
	 *
	 *
	 *
	 * @parámetro String $direccionImportador
	 * @return DireccionImportador
	 */
	public function setDireccionImportador($direccionImportador){
		$this->direccionImportador = (string) $direccionImportador;
		return $this;
	}

	/**
	 * Get direccionImportador
	 *
	 * @return null|String
	 */
	public function getDireccionImportador(){
		return $this->direccionImportador;
	}

	/**
	 * Set idPaisDestino
	 *
	 *
	 *
	 * @parámetro Integer $idPaisDestino
	 * @return IdPaisDestino
	 */
	public function setIdPaisDestino($idPaisDestino){
		$this->idPaisDestino = (integer) $idPaisDestino;
		return $this;
	}

	/**
	 * Get idPaisDestino
	 *
	 * @return null|Integer
	 */
	public function getIdPaisDestino(){
		return $this->idPaisDestino;
	}

	/**
	 * Set paisDestino
	 *
	 *
	 *
	 * @parámetro String $paisDestino
	 * @return PaisDestino
	 */
	public function setPaisDestino($paisDestino){
		$this->paisDestino = (string) $paisDestino;
		return $this;
	}

	/**
	 * Get paisDestino
	 *
	 * @return null|String
	 */
	public function getPaisDestino(){
		return $this->paisDestino;
	}

	/**
	 * Set nombreAgenciaCarga
	 *
	 *
	 *
	 * @parámetro String $nombreAgenciaCarga
	 * @return NombreAgenciaCarga
	 */
	public function setNombreAgenciaCarga($nombreAgenciaCarga){
		$this->nombreAgenciaCarga = (string) $nombreAgenciaCarga;
		return $this;
	}

	/**
	 * Get nombreAgenciaCarga
	 *
	 * @return null|String
	 */
	public function getNombreAgenciaCarga(){
		return $this->nombreAgenciaCarga;
	}

	/**
	 * Set nombreMarcas
	 *
	 *
	 *
	 * @parámetro String $nombreMarcas
	 * @return NombreMarcas
	 */
	public function setNombreMarcas($nombreMarcas){
		$this->nombreMarcas = (string) $nombreMarcas;
		return $this;
	}

	/**
	 * Get nombreMarcas
	 *
	 * @return null|String
	 */
	public function getNombreMarcas(){
		return $this->nombreMarcas;
	}

	/**
	 * Set idPuertoDestino
	 *
	 *
	 *
	 * @parámetro Integer $idPuertoDestino
	 * @return IdPuertoDestino
	 */
	public function setIdPuertoDestino($idPuertoDestino){
		$this->idPuertoDestino = (integer) $idPuertoDestino;
		return $this;
	}

	/**
	 * Get idPuertoDestino
	 *
	 * @return null|Integer
	 */
	public function getIdPuertoDestino(){
		return $this->idPuertoDestino;
	}

	/**
	 * Set puertoDestino
	 *
	 *
	 *
	 * @parámetro String $puertoDestino
	 * @return PuertoDestino
	 */
	public function setPuertoDestino($puertoDestino){
		$this->puertoDestino = (string) $puertoDestino;
		return $this;
	}

	/**
	 * Get puertoDestino
	 *
	 * @return null|String
	 */
	public function getPuertoDestino(){
		return $this->puertoDestino;
	}

	/**
	 * Set idPuertoEmbarque
	 *
	 *
	 *
	 * @parámetro Integer $idPuertoEmbarque
	 * @return IdPuertoEmbarque
	 */
	public function setIdPuertoEmbarque($idPuertoEmbarque){
		$this->idPuertoEmbarque = (integer) $idPuertoEmbarque;
		return $this;
	}

	/**
	 * Get idPuertoEmbarque
	 *
	 * @return null|Integer
	 */
	public function getIdPuertoEmbarque(){
		return $this->idPuertoEmbarque;
	}

	/**
	 * Set puertoEmbarque
	 *
	 *
	 *
	 * @parámetro String $puertoEmbarque
	 * @return PuertoEmbarque
	 */
	public function setPuertoEmbarque($puertoEmbarque){
		$this->puertoEmbarque = (string) $puertoEmbarque;
		return $this;
	}

	/**
	 * Get puertoEmbarque
	 *
	 * @return null|String
	 */
	public function getPuertoEmbarque(){
		return $this->puertoEmbarque;
	}

	/**
	 * Set transporte
	 *
	 *
	 *
	 * @parámetro String $transporte
	 * @return Transporte
	 */
	public function setTransporte($transporte){
		$this->transporte = (string) $transporte;
		return $this;
	}

	/**
	 * Get transporte
	 *
	 * @return null|String
	 */
	public function getTransporte(){
		return $this->transporte;
	}

	/**
	 * Set fechaEmbarque
	 *
	 *
	 *
	 * @parámetro Date $fechaEmbarque
	 * @return FechaEmbarque
	 */
	public function setFechaEmbarque($fechaEmbarque){
		$this->fechaEmbarque = (string) $fechaEmbarque;
		return $this;
	}

	/**
	 * Get fechaEmbarque
	 *
	 * @return null|Date
	 */
	public function getFechaEmbarque(){
		return $this->fechaEmbarque;
	}

	/**
	 * Set numeroViaje
	 *
	 *
	 *
	 * @parámetro String $numeroViaje
	 * @return NumeroViaje
	 */
	public function setNumeroViaje($numeroViaje){
		$this->numeroViaje = (string) $numeroViaje;
		return $this;
	}

	/**
	 * Get numeroViaje
	 *
	 * @return null|String
	 */
	public function getNumeroViaje(){
		return $this->numeroViaje;
	}

	/**
	 * Set tratamientoRealizado
	 *
	 *
	 *
	 * @parámetro String $tratamientoRealizado
	 * @return TratamientoRealizado
	 */
	public function setTratamientoRealizado($tratamientoRealizado){
		$this->tratamientoRealizado = (string) $tratamientoRealizado;
		return $this;
	}

	/**
	 * Get tratamientoRealizado
	 *
	 * @return null|String
	 */
	public function getTratamientoRealizado(){
		return $this->tratamientoRealizado;
	}

	/**
	 * Set duracionTratamiento
	 *
	 *
	 *
	 * @parámetro String $duracionTratamiento
	 * @return DuracionTratamiento
	 */
	public function setDuracionTratamiento($duracionTratamiento){
		$this->duracionTratamiento = (string) $duracionTratamiento;
		return $this;
	}

	/**
	 * Get duracionTratamiento
	 *
	 * @return null|String
	 */
	public function getDuracionTratamiento(){
		return $this->duracionTratamiento;
	}

	/**
	 * Set temperaturaTratamiento
	 *
	 *
	 *
	 * @parámetro String $temperaturaTratamiento
	 * @return TemperaturaTratamiento
	 */
	public function setTemperaturaTratamiento($temperaturaTratamiento){
		$this->temperaturaTratamiento = (string) $temperaturaTratamiento;
		return $this;
	}

	/**
	 * Get temperaturaTratamiento
	 *
	 * @return null|String
	 */
	public function getTemperaturaTratamiento(){
		return $this->temperaturaTratamiento;
	}

	/**
	 * Set fechaTratamiento
	 *
	 *
	 *
	 * @parámetro Date $fechaTratamiento
	 * @return FechaTratamiento
	 */
	public function setFechaTratamiento($fechaTratamiento){
		$this->fechaTratamiento = (string) $fechaTratamiento;
		return $this;
	}

	/**
	 * Get fechaTratamiento
	 *
	 * @return null|Date
	 */
	public function getFechaTratamiento(){
		return $this->fechaTratamiento;
	}

	/**
	 * Set quimicoTratamiento
	 *
	 *
	 *
	 * @parámetro String $quimicoTratamiento
	 * @return QuimicoTratamiento
	 */
	public function setQuimicoTratamiento($quimicoTratamiento){
		$this->quimicoTratamiento = (string) $quimicoTratamiento;
		return $this;
	}

	/**
	 * Get quimicoTratamiento
	 *
	 * @return null|String
	 */
	public function getQuimicoTratamiento(){
		return $this->quimicoTratamiento;
	}

	/**
	 * Set concentracionProducto
	 *
	 *
	 *
	 * @parámetro String $concentracionProducto
	 * @return ConcentracionProducto
	 */
	public function setConcentracionProducto($concentracionProducto){
		$this->concentracionProducto = (string) $concentracionProducto;
		return $this;
	}

	/**
	 * Get concentracionProducto
	 *
	 * @return null|String
	 */
	public function getConcentracionProducto(){
		return $this->concentracionProducto;
	}

	/**
	 * Set idProvincia
	 *
	 *
	 *
	 * @parámetro Integer $idProvincia
	 * @return IdProvincia
	 */
	public function setIdProvincia($idProvincia){
		$this->idProvincia = (integer) $idProvincia;
		return $this;
	}

	/**
	 * Get idProvincia
	 *
	 * @return null|Integer
	 */
	public function getIdProvincia(){
		return $this->idProvincia;
	}

	/**
	 * Set provincia
	 *
	 *
	 *
	 * @parámetro String $provincia
	 * @return Provincia
	 */
	public function setProvincia($provincia){
		$this->provincia = (string) $provincia;
		return $this;
	}

	/**
	 * Get provincia
	 *
	 * @return null|String
	 */
	public function getProvincia(){
		return $this->provincia;
	}

	/**
	 * Set observacionOperador
	 *
	 *
	 *
	 * @parámetro String $observacionOperador
	 * @return ObservacionOperador
	 */
	public function setObservacionOperador($observacionOperador){
		$this->observacionOperador = (string) $observacionOperador;
		return $this;
	}

	/**
	 * Get observacionOperador
	 *
	 * @return null|String
	 */
	public function getObservacionOperador(){
		return $this->observacionOperador;
	}

	/**
	 * Set reporteInspeccion
	 *
	 *
	 *
	 * @parámetro String $reporteInspeccion
	 * @return ReporteInspeccion
	 */
	public function setReporteInspeccion($reporteInspeccion){
		$this->reporteInspeccion = (string) $reporteInspeccion;
		return $this;
	}

	/**
	 * Get reporteInspeccion
	 *
	 * @return null|String
	 */
	public function getReporteInspeccion(){
		return $this->reporteInspeccion;
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
	 * Set fechaVigencia
	 *
	 *
	 *
	 * @parámetro Date $fechaVigencia
	 * @return FechaVigencia
	 */
	public function setFechaVigencia($fechaVigencia){
		$this->fechaVigencia = (string) $fechaVigencia;
		return $this;
	}

	/**
	 * Get fechaVigencia
	 *
	 * @return null|Date
	 */
	public function getFechaVigencia(){
		return $this->fechaVigencia;
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
	 * Set idPaisEmbarque
	 *
	 *
	 *
	 * @parámetro Integer $idPaisEmbarque
	 * @return IdPaisEmbarque
	 */
	public function setIdPaisEmbarque($idPaisEmbarque){
		$this->idPaisEmbarque = (integer) $idPaisEmbarque;
		return $this;
	}

	/**
	 * Get idPaisEmbarque
	 *
	 * @return null|Integer
	 */
	public function getIdPaisEmbarque(){
		return $this->idPaisEmbarque;
	}

	/**
	 * Set paisEmbarque
	 *
	 *
	 *
	 * @parámetro String $paisEmbarque
	 * @return PaisEmbarque
	 */
	public function setPaisEmbarque($paisEmbarque){
		$this->paisEmbarque = (string) $paisEmbarque;
		return $this;
	}

	/**
	 * Get paisEmbarque
	 *
	 * @return null|String
	 */
	public function getPaisEmbarque(){
		return $this->paisEmbarque;
	}

	/**
	 * Set idPaisOrigen
	 *
	 *
	 *
	 * @parámetro Integer $idPaisOrigen
	 * @return IdPaisOrigen
	 */
	public function setIdPaisOrigen($idPaisOrigen){
		$this->idPaisOrigen = (integer) $idPaisOrigen;
		return $this;
	}

	/**
	 * Get idPaisOrigen
	 *
	 * @return null|Integer
	 */
	public function getIdPaisOrigen(){
		return $this->idPaisOrigen;
	}

	/**
	 * Set paisOrigen
	 *
	 *
	 *
	 * @parámetro String $paisOrigen
	 * @return PaisOrigen
	 */
	public function setPaisOrigen($paisOrigen){
		$this->paisOrigen = (string) $paisOrigen;
		return $this;
	}

	/**
	 * Get paisOrigen
	 *
	 * @return null|String
	 */
	public function getPaisOrigen(){
		return $this->paisOrigen;
	}

	/**
	 * Set informeRequisitos
	 *
	 *
	 *
	 * @parámetro String $informeRequisitos
	 * @return InformeRequisitos
	 */
	public function setInformeRequisitos($informeRequisitos){
		$this->informeRequisitos = (string) $informeRequisitos;
		return $this;
	}

	/**
	 * Get informeRequisitos
	 *
	 * @return null|String
	 */
	public function getInformeRequisitos(){
		return $this->informeRequisitos;
	}

	/**
	 * Set lugarInspeccion
	 *
	 *
	 *
	 * @parámetro String $lugarInspeccion
	 * @return LugarInspeccion
	 */
	public function setLugarInspeccion($lugarInspeccion){
		$this->lugarInspeccion = (string) $lugarInspeccion;
		return $this;
	}

	/**
	 * Get lugarInspeccion
	 *
	 * @return null|String
	 */
	public function getLugarInspeccion(){
		return $this->lugarInspeccion;
	}

	/**
	 * Set unidadTemperatura
	 *
	 *
	 *
	 * @parámetro String $unidadTemperatura
	 * @return UnidadTemperatura
	 */
	public function setUnidadTemperatura($unidadTemperatura){
		$this->unidadTemperatura = (string) $unidadTemperatura;
		return $this;
	}

	/**
	 * Get unidadTemperatura
	 *
	 * @return null|String
	 */
	public function getUnidadTemperatura(){
		return $this->unidadTemperatura;
	}

	/**
	 * Set identificadorSolicitante
	 *
	 *
	 *
	 * @parámetro String $identificadorSolicitante
	 * @return IdentificadorSolicitante
	 */
	public function setIdentificadorSolicitante($identificadorSolicitante){
		$this->identificadorSolicitante = (string) $identificadorSolicitante;
		return $this;
	}

	/**
	 * Get identificadorSolicitante
	 *
	 * @return null|String
	 */
	public function getIdentificadorSolicitante(){
		return $this->identificadorSolicitante;
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
	 * Set productoOrganico
	 *
	 *
	 *
	 * @parámetro String $productoOrganico
	 * @return ProductoOrganico
	 */
	public function setProductoOrganico($productoOrganico){
		$this->productoOrganico = (string) $productoOrganico;
		return $this;
	}

	/**
	 * Get productoOrganico
	 *
	 * @return null|String
	 */
	public function getProductoOrganico(){
		return $this->productoOrganico;
	}

	/**
	 * Set numeroProductoOrganico
	 *
	 *
	 *
	 * @parámetro String $numeroProductoOrganico
	 * @return NumeroProductoOrganico
	 */
	public function setNumeroProductoOrganico($numeroProductoOrganico){
		$this->numeroProductoOrganico = (string) $numeroProductoOrganico;
		return $this;
	}

	/**
	 * Get numeroProductoOrganico
	 *
	 * @return null|String
	 */
	public function getNumeroProductoOrganico(){
		return $this->numeroProductoOrganico;
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
	 * @return FitoExportacionesModelo
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
