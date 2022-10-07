<?php
/**
 * Modelo ConsultaMedicaModelo
 *
 * Este archivo se complementa con el archivo ConsultaMedicaLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses ConsultaMedicaModelo
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ConsultaMedicaModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave primaria de la tabla
	 */
	protected $idConsultaMedica;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave foránea de la tabla historia_clinica
	 */
	protected $idHistoriaClinica;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de consulta
	 */
	protected $fechaConsulta;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Sintomas que presenta el paciente
	 */
	protected $sintomas;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador del médico
	 */
	protected $identificadorMedico;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Determinar si tiene reposo médico
	 */
	protected $reposoMedico;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Días de reposo médico
	 */
	protected $diasReposo;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de inicio de reposo
	 */
	protected $fechaDesde;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de reposo hasta
	 */
	protected $fechaHasta;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Observaciones de la consulta médica
	 */
	protected $observaciones;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de creación del registro
	 */
	protected $fechaCreacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Estado del registro
	 */
	protected $estado;

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
	 * Nombre de la tabla: consulta_medica
	 */
	private $tabla = "consulta_medica";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_consulta_medica";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_historias_clinicas"."consulta_medica_id_consulta_medica_seq';

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
			throw new \Exception('Clase Modelo: ConsultaMedicaModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: ConsultaMedicaModelo. Propiedad especificada invalida: get' . $name);
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
	 * Set idConsultaMedica
	 *
	 * Llave primaria de la tabla
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
	 * Set fechaConsulta
	 *
	 * Fecha de consulta
	 *
	 * @parámetro Date $fechaConsulta
	 * @return FechaConsulta
	 */
	public function setFechaConsulta($fechaConsulta){
		$this->fechaConsulta = (string) $fechaConsulta;
		return $this;
	}

	/**
	 * Get fechaConsulta
	 *
	 * @return null|Date
	 */
	public function getFechaConsulta(){
		return $this->fechaConsulta;
	}

	/**
	 * Set sintomas
	 *
	 * Sintomas que presenta el paciente
	 *
	 * @parámetro String $sintomas
	 * @return Sintomas
	 */
	public function setSintomas($sintomas){
		$this->sintomas = (string) $sintomas;
		return $this;
	}

	/**
	 * Get sintomas
	 *
	 * @return null|String
	 */
	public function getSintomas(){
		return $this->sintomas;
	}

	/**
	 * Set identificadorMedico
	 *
	 * Identificador del médico
	 *
	 * @parámetro String $identificadorMedico
	 * @return IdentificadorMedico
	 */
	public function setIdentificadorMedico($identificadorMedico){
		$this->identificadorMedico = (string) $identificadorMedico;
		return $this;
	}

	/**
	 * Get identificadorMedico
	 *
	 * @return null|String
	 */
	public function getIdentificadorMedico(){
		return $this->identificadorMedico;
	}

	/**
	 * Set reposoMedico
	 *
	 * Determinar si tiene reposo médico
	 *
	 * @parámetro String $reposoMedico
	 * @return ReposoMedico
	 */
	public function setReposoMedico($reposoMedico){
		$this->reposoMedico = (string) $reposoMedico;
		return $this;
	}

	/**
	 * Get reposoMedico
	 *
	 * @return null|String
	 */
	public function getReposoMedico(){
		return $this->reposoMedico;
	}

	/**
	 * Set diasReposo
	 *
	 * Días de reposo médico
	 *
	 * @parámetro Integer $diasReposo
	 * @return DiasReposo
	 */
	public function setDiasReposo($diasReposo){
		$this->diasReposo = (integer) $diasReposo;
		return $this;
	}

	/**
	 * Get diasReposo
	 *
	 * @return null|Integer
	 */
	public function getDiasReposo(){
		return $this->diasReposo;
	}

	/**
	 * Set fechaDesde
	 *
	 * Fecha de inicio de reposo
	 *
	 * @parámetro Date $fechaDesde
	 * @return FechaDesde
	 */
	public function setFechaDesde($fechaDesde){
		$this->fechaDesde = (string) $fechaDesde;
		return $this;
	}

	/**
	 * Get fechaDesde
	 *
	 * @return null|Date
	 */
	public function getFechaDesde(){
		return $this->fechaDesde;
	}

	/**
	 * Set fechaHasta
	 *
	 * Fecha de reposo hasta
	 *
	 * @parámetro Date $fechaHasta
	 * @return FechaHasta
	 */
	public function setFechaHasta($fechaHasta){
		$this->fechaHasta = (string) $fechaHasta;
		return $this;
	}

	/**
	 * Get fechaHasta
	 *
	 * @return null|Date
	 */
	public function getFechaHasta(){
		return $this->fechaHasta;
	}

	/**
	 * Set observaciones
	 *
	 * Observaciones de la consulta médica
	 *
	 * @parámetro String $observaciones
	 * @return Observaciones
	 */
	public function setObservaciones($observaciones){
		$this->observaciones = (string) $observaciones;
		return $this;
	}

	/**
	 * Get observaciones
	 *
	 * @return null|String
	 */
	public function getObservaciones(){
		return $this->observaciones;
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
	 * Set estado
	 *
	 * Estado del registro
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
	 * @return ConsultaMedicaModelo
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
