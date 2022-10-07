<?php
/**
 * Modelo EnfermedadProfesionalModelo
 *
 * Este archivo se complementa con el archivo EnfermedadProfesionalLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses EnfermedadProfesionalModelo
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class EnfermedadProfesionalModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave primaria de la tabla
	 */
	protected $idEnfermedadProfesional;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave foránea de la tabla historia_clinica
	 */
	protected $idHistoriaClinica;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Tiene enfermedad
	 */
	protected $tieneEnfermedad;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de diagnostico
	 */
	protected $fechaDiagnostico;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Descripción de la enfermedad
	 */
	protected $descripcion;

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
	 * Nombre de la tabla: enfermedad_profesional
	 */
	private $tabla = "enfermedad_profesional";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_enfermedad_profesional";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_historias_clinicas"."enfermedad_profesional_id_enfermedad_profesional_seq';

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
			throw new \Exception('Clase Modelo: EnfermedadProfesionalModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: EnfermedadProfesionalModelo. Propiedad especificada invalida: get' . $name);
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
	 * Set idEnfermedadProfesional
	 *
	 * Llave primaria de la tabla
	 *
	 * @parámetro Integer $idEnfermedadProfesional
	 * @return IdEnfermedadProfesional
	 */
	public function setIdEnfermedadProfesional($idEnfermedadProfesional){
		$this->idEnfermedadProfesional = (integer) $idEnfermedadProfesional;
		return $this;
	}

	/**
	 * Get idEnfermedadProfesional
	 *
	 * @return null|Integer
	 */
	public function getIdEnfermedadProfesional(){
		return $this->idEnfermedadProfesional;
	}

	/**
	 * Set idHistoriaClinica
	 *
	 * Llave foránea de la tabla historia_clinica
	 *
	 * @parámetro Integer $idHistoriaClinica
	 * @return IdHistoriaClinica
	 */
	public function setIdHistoriaClinica($idHistoriaClinica){
		$this->idHistoriaClinica = (integer) $idHistoriaClinica;
		return $this;
	}

	/**
	 * Get idHistoriaClinica
	 *
	 * @return null|Integer
	 */
	public function getIdHistoriaClinica(){
		return $this->idHistoriaClinica;
	}

	/**
	 * Set tieneEnfermedad
	 *
	 * Tiene enfermedad
	 *
	 * @parámetro String $tieneEnfermedad
	 * @return TieneEnfermedad
	 */
	public function setTieneEnfermedad($tieneEnfermedad){
		$this->tieneEnfermedad = (string) $tieneEnfermedad;
		return $this;
	}

	/**
	 * Get tieneEnfermedad
	 *
	 * @return null|String
	 */
	public function getTieneEnfermedad(){
		return $this->tieneEnfermedad;
	}

	/**
	 * Set fechaDiagnostico
	 *
	 * Fecha de diagnostico
	 *
	 * @parámetro Date $fechaDiagnostico
	 * @return FechaDiagnostico
	 */
	public function setFechaDiagnostico($fechaDiagnostico){
		$this->fechaDiagnostico = (string) $fechaDiagnostico;
		return $this;
	}

	/**
	 * Get fechaDiagnostico
	 *
	 * @return null|Date
	 */
	public function getFechaDiagnostico(){
		return $this->fechaDiagnostico;
	}

	/**
	 * Set descripcion
	 *
	 * Descripción de la enfermedad
	 *
	 * @parámetro String $descripcion
	 * @return Descripcion
	 */
	public function setDescripcion($descripcion){
		$this->descripcion = (string) $descripcion;
		return $this;
	}

	/**
	 * Get descripcion
	 *
	 * @return null|String
	 */
	public function getDescripcion(){
		return $this->descripcion;
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
	 * @return EnfermedadProfesionalModelo
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
