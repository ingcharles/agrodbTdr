<?php
/**
 * Modelo LogModelo
 *
 * Este archivo se complementa con el archivo LogLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses LogModelo
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class LogModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave primaria de la tabla
	 */
	protected $idLog;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $identificador;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre de provincia
	 */
	protected $nombreProvincia;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Área
	 */
	protected $area;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Remote addr
	 */
	protected $remoteAddr;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      http referer
	 */
	protected $httpReferer;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Acción
	 */
	protected $accion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      id_historia_clinica
	 */
	protected $idHistoriaClinica;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Transacción
	 */
	protected $transaccion;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de creación del registro
	 */
	protected $fechaCreacion;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_historias_clinicas";

	/**
	 * Nombre de la tabla: log
	 */
	private $tabla = "log";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_log";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_historias_clinicas"."log_id_log_seq';

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
			throw new \Exception('Clase Modelo: LogModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: LogModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_historias_clinicas
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idLog
	 *
	 * Llave primaria de la tabla
	 *
	 * @parámetro Integer $idLog
	 * @return IdLog
	 */
	public function setIdLog($idLog){
		$this->idLog = (integer) $idLog;
		return $this;
	}

	/**
	 * Get idLog
	 *
	 * @return null|Integer
	 */
	public function getIdLog(){
		return $this->idLog;
	}

	/**
	 * Set identificador
	 *
	 *
	 *
	 * @parámetro String $identificador
	 * @return Identificador
	 */
	public function setIdentificador($identificador){
		$this->identificador = (string) $identificador;
		return $this;
	}

	/**
	 * Get identificador
	 *
	 * @return null|String
	 */
	public function getIdentificador(){
		return $this->identificador;
	}

	/**
	 * Set nombreProvincia
	 *
	 * Nombre de provincia
	 *
	 * @parámetro String $nombreProvincia
	 * @return NombreProvincia
	 */
	public function setNombreProvincia($nombreProvincia){
		$this->nombreProvincia = (string) $nombreProvincia;
		return $this;
	}

	/**
	 * Get nombreProvincia
	 *
	 * @return null|String
	 */
	public function getNombreProvincia(){
		return $this->nombreProvincia;
	}

	/**
	 * Set area
	 *
	 * Área
	 *
	 * @parámetro String $area
	 * @return Area
	 */
	public function setArea($area){
		$this->area = (string) $area;
		return $this;
	}

	/**
	 * Get area
	 *
	 * @return null|String
	 */
	public function getArea(){
		return $this->area;
	}

	/**
	 * Set remoteAddr
	 *
	 * Remote addr
	 *
	 * @parámetro String $remoteAddr
	 * @return RemoteAddr
	 */
	public function setRemoteAddr($remoteAddr){
		$this->remoteAddr = (string) $remoteAddr;
		return $this;
	}

	/**
	 * Get remoteAddr
	 *
	 * @return null|String
	 */
	public function getRemoteAddr(){
		return $this->remoteAddr;
	}

	/**
	 * Set idHistoriaClinica
	 *
	 * Remote addr
	 *
	 * @parámetro String $remoteAddr
	 * @return idHistoriaClinica
	 */
	public function setIdHistoriaClinica($idHistoriaClinica){
		$this->idHistoriaClinica = (integer) $idHistoriaClinica;
		return $this;
	}

	/**
	 * Get remoteAddr
	 *
	 * @return null|String
	 */
	public function getIdHistoriaClinica(){
		return $this->idHistoriaClinica;
	}

	/**
	 * Set httpReferer
	 *
	 * http referer
	 *
	 * @parámetro String $httpReferer
	 * @return HttpReferer
	 */
	public function setHttpReferer($httpReferer){
		$this->httpReferer = (string) $httpReferer;
		return $this;
	}

	/**
	 * Get httpReferer
	 *
	 * @return null|String
	 */
	public function getHttpReferer(){
		return $this->httpReferer;
	}

	/**
	 * Set accion
	 *
	 * Acción
	 *
	 * @parámetro String $accion
	 * @return Accion
	 */
	public function setAccion($accion){
		$this->accion = (string) $accion;
		return $this;
	}

	/**
	 * Get accion
	 *
	 * @return null|String
	 */
	public function getAccion(){
		return $this->accion;
	}

	/**
	 * Set transaccion
	 *
	 * Transacción
	 *
	 * @parámetro String $transaccion
	 * @return Transaccion
	 */
	public function setTransaccion($transaccion){
		$this->transaccion = (string) $transaccion;
		return $this;
	}

	/**
	 * Get transaccion
	 *
	 * @return null|String
	 */
	public function getTransaccion(){
		return $this->transaccion;
	}

	/**
	 * Set fechaCreacion
	 *
	 * Fecha de creación del registro
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
	 * @return LogModelo
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
