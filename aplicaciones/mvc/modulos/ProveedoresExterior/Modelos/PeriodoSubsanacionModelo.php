<?php
/**
 * Modelo PeriodoSubsanacionModelo
 *
 * Este archivo se complementa con el archivo PeriodoSubsanacionLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-07-13
 * @uses PeriodoSubsanacionModelo
 * @package ProveedoresExterior
 * @subpackage Modelos
 */
namespace Agrodb\ProveedoresExterior\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class PeriodoSubsanacionModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador único de la tabla
	 */
	protected $idPeriodoSubsanacion;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena el tiempo en dias maximos para subsanar una solicitud
	 */
	protected $tiempoPeriodoSubsanacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena el estado del periodo de subsanacion (solo debe haber un activo)
	 */
	protected $estadoPeriodoSubsanacion;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena la fecha en que se creo el registro de periodo de subsanacion
	 */
	protected $fechaCreacionPeriodoSubsanacion;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_proveedores_exterior";

	/**
	 * Nombre de la tabla: periodo_subsanacion
	 */
	private $tabla = "periodo_subsanacion";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_periodo_subsanacion";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_proveedores_exterior"."periodo_subsanacion_id_periodo_subsanacion_seq';

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
			throw new \Exception('Clase Modelo: PeriodoSubsanacionModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: PeriodoSubsanacionModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_proveedores_exterior
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idPeriodoSubsanacion
	 *
	 * Identificador único de la tabla
	 *
	 * @parámetro Integer $idPeriodoSubsanacion
	 * @return IdPeriodoSubsanacion
	 */
	public function setIdPeriodoSubsanacion($idPeriodoSubsanacion){
		$this->idPeriodoSubsanacion = (integer) $idPeriodoSubsanacion;
		return $this;
	}

	/**
	 * Get idPeriodoSubsanacion
	 *
	 * @return null|Integer
	 */
	public function getIdPeriodoSubsanacion(){
		return $this->idPeriodoSubsanacion;
	}

	/**
	 * Set tiempoPeriodoSubsanacion
	 *
	 * Campo que almacena el tiempo en dias maximos para subsanar una solicitud
	 *
	 * @parámetro Integer $tiempoPeriodoSubsanacion
	 * @return TiempoPeriodoSubsanacion
	 */
	public function setTiempoPeriodoSubsanacion($tiempoPeriodoSubsanacion){
		$this->tiempoPeriodoSubsanacion = (integer) $tiempoPeriodoSubsanacion;
		return $this;
	}

	/**
	 * Get tiempoPeriodoSubsanacion
	 *
	 * @return null|Integer
	 */
	public function getTiempoPeriodoSubsanacion(){
		return $this->tiempoPeriodoSubsanacion;
	}

	/**
	 * Set estadoPeriodoSubsanacion
	 *
	 * Campo que almacena el estado del periodo de subsanacion (solo debe haber un activo)
	 *
	 * @parámetro String $estadoPeriodoSubsanacion
	 * @return EstadoPeriodoSubsanacion
	 */
	public function setEstadoPeriodoSubsanacion($estadoPeriodoSubsanacion){
		$this->estadoPeriodoSubsanacion = (string) $estadoPeriodoSubsanacion;
		return $this;
	}

	/**
	 * Get estadoPeriodoSubsanacion
	 *
	 * @return null|String
	 */
	public function getEstadoPeriodoSubsanacion(){
		return $this->estadoPeriodoSubsanacion;
	}

	/**
	 * Set fechaCreacionPeriodoSubsanacion
	 *
	 * Campo que almacena la fecha en que se creo el registro de periodo de subsanacion
	 *
	 * @parámetro Date $fechaCreacionPeriodoSubsanacion
	 * @return FechaCreacionPeriodoSubsanacion
	 */
	public function setFechaCreacionPeriodoSubsanacion($fechaCreacionPeriodoSubsanacion){
		$this->fechaCreacionPeriodoSubsanacion = (string) $fechaCreacionPeriodoSubsanacion;
		return $this;
	}

	/**
	 * Get fechaCreacionPeriodoSubsanacion
	 *
	 * @return null|Date
	 */
	public function getFechaCreacionPeriodoSubsanacion(){
		return $this->fechaCreacionPeriodoSubsanacion;
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
	 * @return PeriodoSubsanacionModelo
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
