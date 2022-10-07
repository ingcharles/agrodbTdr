<?php
/**
 * Modelo ZooExportacionesModelo
 *
 * Este archivo se complementa con el archivo ZooExportacionesLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-09-16
 * @uses ZooExportacionesModelo
 * @package ZoosanitarioExportacion
 * @subpackage Modelos
 */
namespace Agrodb\ZoosanitarioExportacion\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ZooExportacionesModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idZooExportacion;

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
	protected $nombreTecnico;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $apellidoTecnico;

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
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $numeroBultos;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $descripcionBultos;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $codigoSitio;

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
	protected $fechaInspeccion;

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
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $usoProducto;

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
	protected $informeRequisitos;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $fechaInspeccionRealizada;

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
	private $esquema = "g_zoo_exportacion";

	/**
	 * Nombre de la tabla: zoo_exportaciones
	 */
	private $tabla = "zoo_exportaciones";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_zoo_exportacion";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_zoo_exportacion"."ZooExportaciones_id_zoo_exportacion_seq';

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
			throw new \Exception('Clase Modelo: ZooExportacionesModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: ZooExportacionesModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_zoo_exportacion
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idZooExportacion
	 *
	 *
	 *
	 * @parámetro Integer $idZooExportacion
	 * @return IdZooExportacion
	 */
	public function setIdZooExportacion($idZooExportacion){
		$this->idZooExportacion = (integer) $idZooExportacion;
		return $this;
	}

	/**
	 * Get idZooExportacion
	 *
	 * @return null|Integer
	 */
	public function getIdZooExportacion(){
		return $this->idZooExportacion;
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
	 * Set nombreTecnico
	 *
	 *
	 *
	 * @parámetro String $nombreTecnico
	 * @return NombreTecnico
	 */
	public function setNombreTecnico($nombreTecnico){
		$this->nombreTecnico = (string) $nombreTecnico;
		return $this;
	}

	/**
	 * Get nombreTecnico
	 *
	 * @return null|String
	 */
	public function getNombreTecnico(){
		return $this->nombreTecnico;
	}

	/**
	 * Set apellidoTecnico
	 *
	 *
	 *
	 * @parámetro String $apellidoTecnico
	 * @return ApellidoTecnico
	 */
	public function setApellidoTecnico($apellidoTecnico){
		$this->apellidoTecnico = (string) $apellidoTecnico;
		return $this;
	}

	/**
	 * Get apellidoTecnico
	 *
	 * @return null|String
	 */
	public function getApellidoTecnico(){
		return $this->apellidoTecnico;
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
	 * Set numeroBultos
	 *
	 *
	 *
	 * @parámetro Integer $numeroBultos
	 * @return NumeroBultos
	 */
	public function setNumeroBultos($numeroBultos){
		$this->numeroBultos = (integer) $numeroBultos;
		return $this;
	}

	/**
	 * Get numeroBultos
	 *
	 * @return null|Integer
	 */
	public function getNumeroBultos(){
		return $this->numeroBultos;
	}

	/**
	 * Set descripcionBultos
	 *
	 *
	 *
	 * @parámetro String $descripcionBultos
	 * @return DescripcionBultos
	 */
	public function setDescripcionBultos($descripcionBultos){
		$this->descripcionBultos = (string) $descripcionBultos;
		return $this;
	}

	/**
	 * Get descripcionBultos
	 *
	 * @return null|String
	 */
	public function getDescripcionBultos(){
		return $this->descripcionBultos;
	}

	/**
	 * Set codigoSitio
	 *
	 *
	 *
	 * @parámetro String $codigoSitio
	 * @return CodigoSitio
	 */
	public function setCodigoSitio($codigoSitio){
		$this->codigoSitio = (string) $codigoSitio;
		return $this;
	}

	/**
	 * Get codigoSitio
	 *
	 * @return null|String
	 */
	public function getCodigoSitio(){
		return $this->codigoSitio;
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
	 * Set fechaInspeccion
	 *
	 *
	 *
	 * @parámetro Date $fechaInspeccion
	 * @return FechaInspeccion
	 */
	public function setFechaInspeccion($fechaInspeccion){
		$this->fechaInspeccion = (string) $fechaInspeccion;
		return $this;
	}

	/**
	 * Get fechaInspeccion
	 *
	 * @return null|Date
	 */
	public function getFechaInspeccion(){
		return $this->fechaInspeccion;
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
	 * Set usoProducto
	 *
	 *
	 *
	 * @parámetro Integer $usoProducto
	 * @return UsoProducto
	 */
	public function setUsoProducto($usoProducto){
		$this->usoProducto = (integer) $usoProducto;
		return $this;
	}

	/**
	 * Get usoProducto
	 *
	 * @return null|Integer
	 */
	public function getUsoProducto(){
		return $this->usoProducto;
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
	 * Set fechaInspeccionRealizada
	 *
	 *
	 *
	 * @parámetro Date $fechaInspeccionRealizada
	 * @return FechaInspeccionRealizada
	 */
	public function setFechaInspeccionRealizada($fechaInspeccionRealizada){
		$this->fechaInspeccionRealizada = (string) $fechaInspeccionRealizada;
		return $this;
	}

	/**
	 * Get fechaInspeccionRealizada
	 *
	 * @return null|Date
	 */
	public function getFechaInspeccionRealizada(){
		return $this->fechaInspeccionRealizada;
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
	 * @return ZooExportacionesModelo
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
