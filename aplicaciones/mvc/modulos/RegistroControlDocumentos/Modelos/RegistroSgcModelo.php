<?php
/**
 * Modelo RegistroSgcModelo
 *
 * Este archivo se complementa con el archivo RegistroSgcLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-10-18
 * @uses RegistroSgcModelo
 * @package RegistroControlDocumentos
 * @subpackage Modelos
 */
namespace Agrodb\RegistroControlDocumentos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class RegistroSgcModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      LLave primaria de la tabla
	 */
	protected $idRegistroSgc;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Coordinación
	 */
	protected $coordinacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Formato
	 */
	protected $formato;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre documento
	 */
	protected $nombreDocumento;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de aprobación
	 */
	protected $fechaAprobacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Edición
	 */
	protected $edicion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Resolucion
	 */
	protected $resolucion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Observación
	 */
	protected $observacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Estado registro
	 */
	protected $estadoRegistro;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Socializar
	 */
	protected $socializar;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de creacion del registro
	 */
	protected $fechaCreacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador del registro
	 */
	protected $identificadorRegistro;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Número de GLPI
	 */
	protected $numeroGlpi;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Asunto
	 */
	protected $asunto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Subproceso
	 */
	protected $subproceso;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Estado
	 */
	protected $estado;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 */
	protected $numeroMemorando;

	protected $fechaNotificacion;

	protected $fechaVigencia;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_registro_control_documentos";

	/**
	 * Nombre de la tabla: registro_sgc
	 */
	private $tabla = "registro_sgc";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_registro_sgc";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_registro_control_documentos"."registro_sgc_id_registro_sgc_seq';

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
			throw new \Exception('Clase Modelo: RegistroSgcModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: RegistroSgcModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_registro_control_documentos
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idRegistroSgc
	 *
	 * LLave primaria de la tabla
	 *
	 * @parámetro Integer $idRegistroSgc
	 * @return IdRegistroSgc
	 */
	public function setIdRegistroSgc($idRegistroSgc){
		$this->idRegistroSgc = (integer) $idRegistroSgc;
		return $this;
	}

	/**
	 * Get idRegistroSgc
	 *
	 * @return null|Integer
	 */
	public function getIdRegistroSgc(){
		return $this->idRegistroSgc;
	}

	/**
	 * Set coordinacion
	 *
	 * Coordinación
	 *
	 * @parámetro String $coordinacion
	 * @return Coordinacion
	 */
	public function setCoordinacion($coordinacion){
		$this->coordinacion = (string) $coordinacion;
		return $this;
	}

	/**
	 * Get coordinacion
	 *
	 * @return null|String
	 */
	public function getCoordinacion(){
		return $this->coordinacion;
	}

	/**
	 * Set formato
	 *
	 * Formato
	 *
	 * @parámetro String $formato
	 * @return Formato
	 */
	public function setFormato($formato){
		$this->formato = (string) $formato;
		return $this;
	}

	/**
	 * Get formato
	 *
	 * @return null|String
	 */
	public function getFormato(){
		return $this->formato;
	}

	/**
	 * Set nombreDocumento
	 *
	 * Nombre documento
	 *
	 * @parámetro String $nombreDocumento
	 * @return NombreDocumento
	 */
	public function setNombreDocumento($nombreDocumento){
		$this->nombreDocumento = (string) $nombreDocumento;
		return $this;
	}

	/**
	 * Get nombreDocumento
	 *
	 * @return null|String
	 */
	public function getNombreDocumento(){
		return $this->nombreDocumento;
	}

	/**
	 * Set fechaAprobacion
	 *
	 * Fecha de aprobación
	 *
	 * @parámetro Date $fechaAprobacion
	 * @return FechaAprobacion
	 */
	public function setFechaAprobacion($fechaAprobacion){
		$this->fechaAprobacion = (string) $fechaAprobacion;
		return $this;
	}

	/**
	 * Get fechaAprobacion
	 *
	 * @return null|Date
	 */
	public function getFechaAprobacion(){
		return $this->fechaAprobacion;
	}

	/**
	 * Set edicion
	 *
	 * Edición
	 *
	 * @parámetro String $edicion
	 * @return Edicion
	 */
	public function setEdicion($edicion){
		$this->edicion = (string) $edicion;
		return $this;
	}

	/**
	 * Get edicion
	 *
	 * @return null|String
	 */
	public function getEdicion(){
		return $this->edicion;
	}

	/**
	 * Set resolucion
	 *
	 * Resolucion
	 *
	 * @parámetro String $resolucion
	 * @return Resolucion
	 */
	public function setResolucion($resolucion){
		$this->resolucion = (string) $resolucion;
		return $this;
	}

	/**
	 * Get resolucion
	 *
	 * @return null|String
	 */
	public function getResolucion(){
		return $this->resolucion;
	}

	/**
	 * Set observacion
	 *
	 * Observación
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
	 * Set estadoRegistro
	 *
	 * Estado registro
	 *
	 * @parámetro String $estadoRegistro
	 * @return EstadoRegistro
	 */
	public function setEstadoRegistro($estadoRegistro){
		$this->estadoRegistro = (string) $estadoRegistro;
		return $this;
	}

	/**
	 * Get estadoRegistro
	 *
	 * @return null|String
	 */
	public function getEstadoRegistro(){
		return $this->estadoRegistro;
	}

	/**
	 * Set socializar
	 *
	 * Socializar
	 *
	 * @parámetro String $socializar
	 * @return Socializar
	 */
	public function setSocializar($socializar){
		$this->socializar = (string) $socializar;
		return $this;
	}

	/**
	 * Get socializar
	 *
	 * @return null|String
	 */
	public function getSocializar(){
		return $this->socializar;
	}

	/**
	 * Set fechaCreacion
	 *
	 * Fecha de creacion del registro
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
	 * Set identificadorRegistro
	 *
	 * Identificador del registro
	 *
	 * @parámetro String $identificadorRegistro
	 * @return IdentificadorRegistro
	 */
	public function setIdentificadorRegistro($identificadorRegistro){
		$this->identificadorRegistro = (string) $identificadorRegistro;
		return $this;
	}

	/**
	 * Get identificadorRegistro
	 *
	 * @return null|String
	 */
	public function getIdentificadorRegistro(){
		return $this->identificadorRegistro;
	}

	/**
	 * Set numeroGlpi
	 *
	 * Número de GLPI
	 *
	 * @parámetro String $numeroGlpi
	 * @return NumeroGlpi
	 */
	public function setNumeroGlpi($numeroGlpi){
		$this->numeroGlpi = (string) $numeroGlpi;
		return $this;
	}

	/**
	 * Get numeroGlpi
	 *
	 * @return null|String
	 */
	public function getNumeroGlpi(){
		return $this->numeroGlpi;
	}

	/**
	 * Set asunto
	 *
	 * Asunto
	 *
	 * @parámetro String $asunto
	 * @return Asunto
	 */
	public function setAsunto($asunto){
		$this->asunto = (string) $asunto;
		return $this;
	}

	/**
	 * Get asunto
	 *
	 * @return null|String
	 */
	public function getAsunto(){
		return $this->asunto;
	}

	/**
	 * Set subproceso
	 *
	 * subproceso
	 *
	 * @parámetro String $asunto
	 * @return subproceso
	 */
	public function setSubproceso($subproceso){
		$this->subproceso = (string) $subproceso;
		return $this;
	}

	/**
	 * Get subproceso
	 *
	 * @return null|String
	 */
	public function getSubproceso(){
		return $this->subproceso;
	}

	/**
	 * Set estado
	 *
	 * Estado
	 *
	 * @parámetro String $estadoRegistro
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

	public function setNumeroMemorando($numeroMemorando){
		$this->numeroMemorando = (string) $numeroMemorando;
		return $this;
	}

	/**
	 * Get estado
	 *
	 * @return null|String
	 */
	public function getNumeroMemorando(){
		return $this->numeroMemorando;
	}

	public function setFechaNotificacion($fechaNotificacion){
		$this->fechaNotificacion = (string) $fechaNotificacion;
		return $this;
	}

	/**
	 * Get fecha notificacion
	 *
	 * @return null|String
	 */
	public function getFechaNotificacion(){
		return $this->fechaNotificacion;
	}

	public function setFechaVigencia($fechaVigencia){
		$this->fechaVigencia = (string) $fechaVigencia;
		return $this;
	}

	/**
	 * Get fechavigencia
	 *
	 * @return null|String
	 */
	public function getFechaVigencia(){
		return $this->fechaVigencia;
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

	public function borrarPorParametro($param, $value){
		return parent::borrar($param . " = " . $value);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return RegistroSgcModelo
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
