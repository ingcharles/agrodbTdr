<?php
/**
 * Modelo SolicitudesAtenderModelo
 *
 * Este archivo se complementa con el archivo SolicitudesAtenderLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-07-14
 * @uses SolicitudesAtenderModelo
 * @package Vue
 * @subpackage Modelos
 */
namespace Agrodb\Vue\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class SolicitudesAtenderModelo extends ModeloBase{

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      dcm_cd
	 */
	protected $formulario;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      afr_prst_cd
	 */
	protected $codigoProcesamiento;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      ntfc_cfm_cd
	 */
	protected $codigoVerificacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      req_no
	 */
	protected $solicitud;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $estado;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $id;

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
	protected $fecha;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $tipoProceso;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_vue";

	/**
	 * Nombre de la tabla: solicitudes_atender
	 */
	private $tabla = "solicitudes_atender";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_vue"."solicitudes_atender_id_seq';

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
			throw new \Exception('Clase Modelo: SolicitudesAtenderModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: SolicitudesAtenderModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_vue
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set formulario
	 *
	 * dcm_cd
	 *
	 * @parámetro String $formulario
	 * @return Formulario
	 */
	public function setFormulario($formulario){
		$this->formulario = (string) $formulario;
		return $this;
	}

	/**
	 * Get formulario
	 *
	 * @return null|String
	 */
	public function getFormulario(){
		return $this->formulario;
	}

	/**
	 * Set codigoProcesamiento
	 *
	 * afr_prst_cd
	 *
	 * @parámetro String $codigoProcesamiento
	 * @return CodigoProcesamiento
	 */
	public function setCodigoProcesamiento($codigoProcesamiento){
		$this->codigoProcesamiento = (string) $codigoProcesamiento;
		return $this;
	}

	/**
	 * Get codigoProcesamiento
	 *
	 * @return null|String
	 */
	public function getCodigoProcesamiento(){
		return $this->codigoProcesamiento;
	}

	/**
	 * Set codigoVerificacion
	 *
	 * ntfc_cfm_cd
	 *
	 * @parámetro String $codigoVerificacion
	 * @return CodigoVerificacion
	 */
	public function setCodigoVerificacion($codigoVerificacion){
		$this->codigoVerificacion = (string) $codigoVerificacion;
		return $this;
	}

	/**
	 * Get codigoVerificacion
	 *
	 * @return null|String
	 */
	public function getCodigoVerificacion(){
		return $this->codigoVerificacion;
	}

	/**
	 * Set solicitud
	 *
	 * req_no
	 *
	 * @parámetro String $solicitud
	 * @return Solicitud
	 */
	public function setSolicitud($solicitud){
		$this->solicitud = (string) $solicitud;
		return $this;
	}

	/**
	 * Get solicitud
	 *
	 * @return null|String
	 */
	public function getSolicitud(){
		return $this->solicitud;
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
	 * Set id
	 *
	 *
	 *
	 * @parámetro Integer $id
	 * @return Id
	 */
	public function setId($id){
		$this->id = (integer) $id;
		return $this;
	}

	/**
	 * Get id
	 *
	 * @return null|Integer
	 */
	public function getId(){
		return $this->id;
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
	 * Set fecha
	 *
	 *
	 *
	 * @parámetro Date $fecha
	 * @return Fecha
	 */
	public function setFecha($fecha){
		$this->fecha = (string) $fecha;
		return $this;
	}

	/**
	 * Get fecha
	 *
	 * @return null|Date
	 */
	public function getFecha(){
		return $this->fecha;
	}

	/**
	 * Set tipoProceso
	 *
	 *
	 *
	 * @parámetro String $tipoProceso
	 * @return TipoProceso
	 */
	public function setTipoProceso($tipoProceso){
		$this->tipoProceso = (string) $tipoProceso;
		return $this;
	}

	/**
	 * Get tipoProceso
	 *
	 * @return null|String
	 */
	public function getTipoProceso(){
		return $this->tipoProceso;
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
	 * @return SolicitudesAtenderModelo
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
