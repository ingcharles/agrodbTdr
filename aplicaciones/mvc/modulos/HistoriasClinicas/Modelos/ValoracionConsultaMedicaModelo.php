<?php
/**
 * Modelo ValoracionConsultaMedicaModelo
 *
 * Este archivo se complementa con el archivo ValoracionConsultaMedicaLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses ValoracionConsultaMedicaModelo
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ValoracionConsultaMedicaModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave primaria de la tabla
	 */
	protected $idValoracionConsultaMedica;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave foránea de la tabla consulta_médica
	 */
	protected $idConsultaMedica;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Medicación si o no
	 */
	protected $medicacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Descripción del medicamento
	 */
	protected $medicamento;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Forma farmaceutica
	 */
	protected $formaFarmaceutica;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Concentración
	 */
	protected $concentracion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Indicaciones
	 */
	protected $indicaciones;

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
	 * Nombre de la tabla: valoracion_consulta_medica
	 */
	private $tabla = "valoracion_consulta_medica";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_valoracion_consulta_medica";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_historias_clinicas"."valoracion_consulta_medica_id_valoracion_consulta_medica_seq';

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
			throw new \Exception('Clase Modelo: ValoracionConsultaMedicaModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: ValoracionConsultaMedicaModelo. Propiedad especificada invalida: get' . $name);
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
	 * Set idValoracionConsultaMedica
	 *
	 * Llave primaria de la tabla
	 *
	 * @parámetro Integer $idValoracionConsultaMedica
	 * @return IdValoracionConsultaMedica
	 */
	public function setIdValoracionConsultaMedica($idValoracionConsultaMedica){
		$this->idValoracionConsultaMedica = (integer) $idValoracionConsultaMedica;
		return $this;
	}

	/**
	 * Get idValoracionConsultaMedica
	 *
	 * @return null|Integer
	 */
	public function getIdValoracionConsultaMedica(){
		return $this->idValoracionConsultaMedica;
	}

	/**
	 * Set idConsultaMedica
	 *
	 * Llave foránea de la tabla consulta_médica
	 *
	 * @parámetro Integer $idConsultaMedica
	 * @return IdConsultaMedica
	 */
	public function setIdConsultaMedica($idConsultaMedica){
		$this->idConsultaMedica = (integer) $idConsultaMedica;
		return $this;
	}

	/**
	 * Get idConsultaMedica
	 *
	 * @return null|Integer
	 */
	public function getIdConsultaMedica(){
		return $this->idConsultaMedica;
	}

	/**
	 * Set medicacion
	 *
	 * Medicación si o no
	 *
	 * @parámetro String $medicacion
	 * @return Medicacion
	 */
	public function setMedicacion($medicacion){
		$this->medicacion = (string) $medicacion;
		return $this;
	}

	/**
	 * Get medicacion
	 *
	 * @return null|String
	 */
	public function getMedicacion(){
		return $this->medicacion;
	}

	/**
	 * Set medicamento
	 *
	 * Descripción del medicamento
	 *
	 * @parámetro String $medicamento
	 * @return Medicamento
	 */
	public function setMedicamento($medicamento){
		$this->medicamento = (string) $medicamento;
		return $this;
	}

	/**
	 * Get medicamento
	 *
	 * @return null|String
	 */
	public function getMedicamento(){
		return $this->medicamento;
	}

	/**
	 * Set formaFarmaceutica
	 *
	 * Forma farmaceutica
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
	 * Set concentracion
	 *
	 * Concentración
	 *
	 * @parámetro String $concentracion
	 * @return Concentracion
	 */
	public function setConcentracion($concentracion){
		$this->concentracion = (string) $concentracion;
		return $this;
	}

	/**
	 * Get concentracion
	 *
	 * @return null|String
	 */
	public function getConcentracion(){
		return $this->concentracion;
	}

	/**
	 * Set indicaciones
	 *
	 * Indicaciones
	 *
	 * @parámetro String $indicaciones
	 * @return Indicaciones
	 */
	public function setIndicaciones($indicaciones){
		$this->indicaciones = (string) $indicaciones;
		return $this;
	}

	/**
	 * Get indicaciones
	 *
	 * @return null|String
	 */
	public function getIndicaciones(){
		return $this->indicaciones;
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
	 * @return ValoracionConsultaMedicaModelo
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
