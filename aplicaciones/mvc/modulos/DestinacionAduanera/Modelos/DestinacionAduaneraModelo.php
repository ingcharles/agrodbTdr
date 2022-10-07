<?php
/**
 * Modelo DestinacionAduaneraModelo
 *
 * Este archivo se complementa con el archivo DestinacionAduaneraLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-09-16
 * @uses DestinacionAduaneraModelo
 * @package DestinacionAduanera
 * @subpackage Modelos
 */
namespace Agrodb\DestinacionAduanera\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class DestinacionAduaneraModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idDestinacionAduanera;

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
	 *      Se registra el nombre del exportador que realiza la solicitud
	 */
	protected $nombreExportador;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $direccionExportador;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idPaisExportador;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $paisExportacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $proposito;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $tipoCertificado;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $categoriaProducto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $permisoImportacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $permisoExportacion;

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
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $numeroCarga;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $tipoTransporte;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $numeroTransporte;

	/**
	 *
	 * @var Integer Campo requerido
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
	protected $nombreLugarInspeccion;

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
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $fechaEmbarque;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $fechaArribo;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $numeroContenedores;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $pesoTotal;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $unidadPesoTotal;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $fechaCreacion;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $contadorInspeccion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $estadoSeguimiento;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $estadoMail;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $provinciaSeguimiento;

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
	private $esquema = "g_dda";

	/**
	 * Nombre de la tabla: destinacion_aduanera
	 */
	private $tabla = "destinacion_aduanera";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_destinacion_aduanera";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_dda"."DestinacionAduanera_id_destinacion_aduanera_seq';

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
			throw new \Exception('Clase Modelo: DestinacionAduaneraModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: DestinacionAduaneraModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_dda
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idDestinacionAduanera
	 *
	 *
	 *
	 * @parámetro Integer $idDestinacionAduanera
	 * @return IdDestinacionAduanera
	 */
	public function setIdDestinacionAduanera($idDestinacionAduanera){
		$this->idDestinacionAduanera = (integer) $idDestinacionAduanera;
		return $this;
	}

	/**
	 * Get idDestinacionAduanera
	 *
	 * @return null|Integer
	 */
	public function getIdDestinacionAduanera(){
		return $this->idDestinacionAduanera;
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
	 * Set nombreExportador
	 *
	 * Se registra el nombre del exportador que realiza la solicitud
	 *
	 * @parámetro String $nombreExportador
	 * @return NombreExportador
	 */
	public function setNombreExportador($nombreExportador){
		$this->nombreExportador = (string) $nombreExportador;
		return $this;
	}

	/**
	 * Get nombreExportador
	 *
	 * @return null|String
	 */
	public function getNombreExportador(){
		return $this->nombreExportador;
	}

	/**
	 * Set direccionExportador
	 *
	 *
	 *
	 * @parámetro String $direccionExportador
	 * @return DireccionExportador
	 */
	public function setDireccionExportador($direccionExportador){
		$this->direccionExportador = (string) $direccionExportador;
		return $this;
	}

	/**
	 * Get direccionExportador
	 *
	 * @return null|String
	 */
	public function getDireccionExportador(){
		return $this->direccionExportador;
	}

	/**
	 * Set idPaisExportador
	 *
	 *
	 *
	 * @parámetro Integer $idPaisExportador
	 * @return IdPaisExportador
	 */
	public function setIdPaisExportador($idPaisExportador){
		$this->idPaisExportador = (integer) $idPaisExportador;
		return $this;
	}

	/**
	 * Get idPaisExportador
	 *
	 * @return null|Integer
	 */
	public function getIdPaisExportador(){
		return $this->idPaisExportador;
	}

	/**
	 * Set paisExportacion
	 *
	 *
	 *
	 * @parámetro String $paisExportacion
	 * @return PaisExportacion
	 */
	public function setPaisExportacion($paisExportacion){
		$this->paisExportacion = (string) $paisExportacion;
		return $this;
	}

	/**
	 * Get paisExportacion
	 *
	 * @return null|String
	 */
	public function getPaisExportacion(){
		return $this->paisExportacion;
	}

	/**
	 * Set proposito
	 *
	 *
	 *
	 * @parámetro String $proposito
	 * @return Proposito
	 */
	public function setProposito($proposito){
		$this->proposito = (string) $proposito;
		return $this;
	}

	/**
	 * Get proposito
	 *
	 * @return null|String
	 */
	public function getProposito(){
		return $this->proposito;
	}

	/**
	 * Set tipoCertificado
	 *
	 *
	 *
	 * @parámetro String $tipoCertificado
	 * @return TipoCertificado
	 */
	public function setTipoCertificado($tipoCertificado){
		$this->tipoCertificado = (string) $tipoCertificado;
		return $this;
	}

	/**
	 * Get tipoCertificado
	 *
	 * @return null|String
	 */
	public function getTipoCertificado(){
		return $this->tipoCertificado;
	}

	/**
	 * Set categoriaProducto
	 *
	 *
	 *
	 * @parámetro String $categoriaProducto
	 * @return CategoriaProducto
	 */
	public function setCategoriaProducto($categoriaProducto){
		$this->categoriaProducto = (string) $categoriaProducto;
		return $this;
	}

	/**
	 * Get categoriaProducto
	 *
	 * @return null|String
	 */
	public function getCategoriaProducto(){
		return $this->categoriaProducto;
	}

	/**
	 * Set permisoImportacion
	 *
	 *
	 *
	 * @parámetro String $permisoImportacion
	 * @return PermisoImportacion
	 */
	public function setPermisoImportacion($permisoImportacion){
		$this->permisoImportacion = (string) $permisoImportacion;
		return $this;
	}

	/**
	 * Get permisoImportacion
	 *
	 * @return null|String
	 */
	public function getPermisoImportacion(){
		return $this->permisoImportacion;
	}

	/**
	 * Set permisoExportacion
	 *
	 *
	 *
	 * @parámetro String $permisoExportacion
	 * @return PermisoExportacion
	 */
	public function setPermisoExportacion($permisoExportacion){
		$this->permisoExportacion = (string) $permisoExportacion;
		return $this;
	}

	/**
	 * Get permisoExportacion
	 *
	 * @return null|String
	 */
	public function getPermisoExportacion(){
		return $this->permisoExportacion;
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
	 * Set numeroCarga
	 *
	 *
	 *
	 * @parámetro String $numeroCarga
	 * @return NumeroCarga
	 */
	public function setNumeroCarga($numeroCarga){
		$this->numeroCarga = (string) $numeroCarga;
		return $this;
	}

	/**
	 * Get numeroCarga
	 *
	 * @return null|String
	 */
	public function getNumeroCarga(){
		return $this->numeroCarga;
	}

	/**
	 * Set tipoTransporte
	 *
	 *
	 *
	 * @parámetro String $tipoTransporte
	 * @return TipoTransporte
	 */
	public function setTipoTransporte($tipoTransporte){
		$this->tipoTransporte = (string) $tipoTransporte;
		return $this;
	}

	/**
	 * Get tipoTransporte
	 *
	 * @return null|String
	 */
	public function getTipoTransporte(){
		return $this->tipoTransporte;
	}

	/**
	 * Set numeroTransporte
	 *
	 *
	 *
	 * @parámetro String $numeroTransporte
	 * @return NumeroTransporte
	 */
	public function setNumeroTransporte($numeroTransporte){
		$this->numeroTransporte = (string) $numeroTransporte;
		return $this;
	}

	/**
	 * Get numeroTransporte
	 *
	 * @return null|String
	 */
	public function getNumeroTransporte(){
		return $this->numeroTransporte;
	}

	/**
	 * Set lugarInspeccion
	 *
	 *
	 *
	 * @parámetro Integer $lugarInspeccion
	 * @return LugarInspeccion
	 */
	public function setLugarInspeccion($lugarInspeccion){
		$this->lugarInspeccion = (integer) $lugarInspeccion;
		return $this;
	}

	/**
	 * Get lugarInspeccion
	 *
	 * @return null|Integer
	 */
	public function getLugarInspeccion(){
		return $this->lugarInspeccion;
	}

	/**
	 * Set nombreLugarInspeccion
	 *
	 *
	 *
	 * @parámetro String $nombreLugarInspeccion
	 * @return NombreLugarInspeccion
	 */
	public function setNombreLugarInspeccion($nombreLugarInspeccion){
		$this->nombreLugarInspeccion = (string) $nombreLugarInspeccion;
		return $this;
	}

	/**
	 * Get nombreLugarInspeccion
	 *
	 * @return null|String
	 */
	public function getNombreLugarInspeccion(){
		return $this->nombreLugarInspeccion;
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
	 * Set fechaArribo
	 *
	 *
	 *
	 * @parámetro Date $fechaArribo
	 * @return FechaArribo
	 */
	public function setFechaArribo($fechaArribo){
		$this->fechaArribo = (string) $fechaArribo;
		return $this;
	}

	/**
	 * Get fechaArribo
	 *
	 * @return null|Date
	 */
	public function getFechaArribo(){
		return $this->fechaArribo;
	}

	/**
	 * Set numeroContenedores
	 *
	 *
	 *
	 * @parámetro String $numeroContenedores
	 * @return NumeroContenedores
	 */
	public function setNumeroContenedores($numeroContenedores){
		$this->numeroContenedores = (string) $numeroContenedores;
		return $this;
	}

	/**
	 * Get numeroContenedores
	 *
	 * @return null|String
	 */
	public function getNumeroContenedores(){
		return $this->numeroContenedores;
	}

	/**
	 * Set pesoTotal
	 *
	 *
	 *
	 * @parámetro String $pesoTotal
	 * @return PesoTotal
	 */
	public function setPesoTotal($pesoTotal){
		$this->pesoTotal = (string) $pesoTotal;
		return $this;
	}

	/**
	 * Get pesoTotal
	 *
	 * @return null|String
	 */
	public function getPesoTotal(){
		return $this->pesoTotal;
	}

	/**
	 * Set unidadPesoTotal
	 *
	 *
	 *
	 * @parámetro String $unidadPesoTotal
	 * @return UnidadPesoTotal
	 */
	public function setUnidadPesoTotal($unidadPesoTotal){
		$this->unidadPesoTotal = (string) $unidadPesoTotal;
		return $this;
	}

	/**
	 * Get unidadPesoTotal
	 *
	 * @return null|String
	 */
	public function getUnidadPesoTotal(){
		return $this->unidadPesoTotal;
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
	 * Set contadorInspeccion
	 *
	 *
	 *
	 * @parámetro Integer $contadorInspeccion
	 * @return ContadorInspeccion
	 */
	public function setContadorInspeccion($contadorInspeccion){
		$this->contadorInspeccion = (integer) $contadorInspeccion;
		return $this;
	}

	/**
	 * Get contadorInspeccion
	 *
	 * @return null|Integer
	 */
	public function getContadorInspeccion(){
		return $this->contadorInspeccion;
	}

	/**
	 * Set estadoSeguimiento
	 *
	 *
	 *
	 * @parámetro String $estadoSeguimiento
	 * @return EstadoSeguimiento
	 */
	public function setEstadoSeguimiento($estadoSeguimiento){
		$this->estadoSeguimiento = (string) $estadoSeguimiento;
		return $this;
	}

	/**
	 * Get estadoSeguimiento
	 *
	 * @return null|String
	 */
	public function getEstadoSeguimiento(){
		return $this->estadoSeguimiento;
	}

	/**
	 * Set estadoMail
	 *
	 *
	 *
	 * @parámetro String $estadoMail
	 * @return EstadoMail
	 */
	public function setEstadoMail($estadoMail){
		$this->estadoMail = (string) $estadoMail;
		return $this;
	}

	/**
	 * Get estadoMail
	 *
	 * @return null|String
	 */
	public function getEstadoMail(){
		return $this->estadoMail;
	}

	/**
	 * Set provinciaSeguimiento
	 *
	 *
	 *
	 * @parámetro String $provinciaSeguimiento
	 * @return ProvinciaSeguimiento
	 */
	public function setProvinciaSeguimiento($provinciaSeguimiento){
		$this->provinciaSeguimiento = (string) $provinciaSeguimiento;
		return $this;
	}

	/**
	 * Get provinciaSeguimiento
	 *
	 * @return null|String
	 */
	public function getProvinciaSeguimiento(){
		return $this->provinciaSeguimiento;
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
	 * @return DestinacionAduaneraModelo
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
