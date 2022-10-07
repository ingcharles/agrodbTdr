<?php
/**
 * Modelo IngresoAplicacionModelo
 *
 * Este archivo se complementa con el archivo IngresoAplicacionLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-07-01
 * @uses IngresoAplicacionModelo
 * @package Auditoria
 * @subpackage Modelos
 */
namespace Agrodb\Auditoria\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class IngresoAplicacionModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador unico de la tabla
	 */
	protected $idIngresoAplicacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador del usuario interno o externo que ingresa al modulo
	 */
	protected $identificador;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Id de la opcion o aplicacion a la cual esta accediendo el usuario interno o externo
	 */
	protected $idAcceso;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de acceso
	 */
	protected $fechaAcceso;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $tipoAcceso;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_auditoria";

	/**
	 * Nombre de la tabla: ingreso_aplicacion
	 */
	private $tabla = "ingreso_aplicacion";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_ingreso_aplicacion";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_auditoria"."ingreso_aplicacion_id_ingreso_aplicacion_seq';

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
			throw new \Exception('Clase Modelo: IngresoAplicacionModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: IngresoAplicacionModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_auditoria
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idIngresoAplicacion
	 *
	 * Identificador unico de la tabla
	 *
	 * @parámetro Integer $idIngresoAplicacion
	 * @return IdIngresoAplicacion
	 */
	public function setIdIngresoAplicacion($idIngresoAplicacion){
		$this->idIngresoAplicacion = (integer) $idIngresoAplicacion;
		return $this;
	}

	/**
	 * Get idIngresoAplicacion
	 *
	 * @return null|Integer
	 */
	public function getIdIngresoAplicacion(){
		return $this->idIngresoAplicacion;
	}

	/**
	 * Set identificador
	 *
	 * Identificador del usuario interno o externo que ingresa al modulo
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
	 * Set idAcceso
	 *
	 * Id de la opcion o aplicacion a la cual esta accediendo el usuario interno o externo
	 *
	 * @parámetro Integer $idAcceso
	 * @return IdAcceso
	 */
	public function setIdAcceso($idAcceso){
		$this->idAcceso = (integer) $idAcceso;
		return $this;
	}

	/**
	 * Get idAcceso
	 *
	 * @return null|Integer
	 */
	public function getIdAcceso(){
		return $this->idAcceso;
	}

	/**
	 * Set fechaAcceso
	 *
	 * Fecha de acceso
	 *
	 * @parámetro Date $fechaAcceso
	 * @return FechaAcceso
	 */
	public function setFechaAcceso($fechaAcceso){
		$this->fechaAcceso = (string) $fechaAcceso;
		return $this;
	}

	/**
	 * Get fechaAcceso
	 *
	 * @return null|Date
	 */
	public function getFechaAcceso(){
		return $this->fechaAcceso;
	}

	/**
	 * Set tipoAcceso
	 *
	 *
	 *
	 * @parámetro String $tipoAcceso
	 * @return TipoAcceso
	 */
	public function setTipoAcceso($tipoAcceso){
		$this->tipoAcceso = (string) $tipoAcceso;
		return $this;
	}

	/**
	 * Get tipoAcceso
	 *
	 * @return null|String
	 */
	public function getTipoAcceso(){
		return $this->tipoAcceso;
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
	 * @return IngresoAplicacionModelo
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
