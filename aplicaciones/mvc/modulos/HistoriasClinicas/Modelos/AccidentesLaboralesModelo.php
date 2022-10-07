<?php
/**
 * Modelo AccidentesLaboralesModelo
 *
 * Este archivo se complementa con el archivo AccidentesLaboralesLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses AccidentesLaboralesModelo
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class AccidentesLaboralesModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave primaria de la tabla
	 */
	protected $idAccidentesLaborales;

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
	 *      determinar si tiene accidente laboral
	 */
	protected $accidenteLaboral;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Mes del accidente
	 */
	protected $mes;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Año del accidente
	 */
	protected $anio;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Reportado al iess
	 */
	protected $reportadoIess;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave foránea de la tabla historia_ocupacional
	 */
	protected $idHistoriaOcupacional;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Naturaleza de la lesion
	 */
	protected $naturalezaLesion;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Días de incapacidad
	 */
	protected $diasIncapacidad;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Partes afectadas
	 */
	protected $parteAfectada;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Secuelas
	 */
	protected $secuelas;

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
	 * Nombre de la tabla: accidentes_laborales
	 */
	private $tabla = "accidentes_laborales";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_accidentes_laborales";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_historias_clinicas"."accidentes_laborales_id_accidentes_laborales_seq';

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
			throw new \Exception('Clase Modelo: AccidentesLaboralesModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: AccidentesLaboralesModelo. Propiedad especificada invalida: get' . $name);
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
	 * Set idAccidentesLaborales
	 *
	 * Llave primaria de la tabla
	 *
	 * @parámetro Integer $idAccidentesLaborales
	 * @return IdAccidentesLaborales
	 */
	public function setIdAccidentesLaborales($idAccidentesLaborales){
		$this->idAccidentesLaborales = (integer) $idAccidentesLaborales;
		return $this;
	}

	/**
	 * Get idAccidentesLaborales
	 *
	 * @return null|Integer
	 */
	public function getIdAccidentesLaborales(){
		return $this->idAccidentesLaborales;
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
	 * Set accidenteLaboral
	 *
	 * determinar si tiene accidente laboral
	 *
	 * @parámetro String $accidenteLaboral
	 * @return AccidenteLaboral
	 */
	public function setAccidenteLaboral($accidenteLaboral){
		$this->accidenteLaboral = (string) $accidenteLaboral;
		return $this;
	}

	/**
	 * Get accidenteLaboral
	 *
	 * @return null|String
	 */
	public function getAccidenteLaboral(){
		return $this->accidenteLaboral;
	}

	/**
	 * Set mes
	 *
	 * Mes del accidente
	 *
	 * @parámetro String $mes
	 * @return Mes
	 */
	public function setMes($mes){
		$this->mes = (string) $mes;
		return $this;
	}

	/**
	 * Get mes
	 *
	 * @return null|String
	 */
	public function getMes(){
		return $this->mes;
	}

	/**
	 * Set anio
	 *
	 * Año del accidente
	 *
	 * @parámetro Integer $anio
	 * @return Anio
	 */
	public function setAnio($anio){
		$this->anio = (integer) $anio;
		return $this;
	}

	/**
	 * Get anio
	 *
	 * @return null|Integer
	 */
	public function getAnio(){
		return $this->anio;
	}

	/**
	 * Set reportadoIess
	 *
	 * Reportado al iess
	 *
	 * @parámetro String $reportadoIess
	 * @return ReportadoIess
	 */
	public function setReportadoIess($reportadoIess){
		$this->reportadoIess = (string) $reportadoIess;
		return $this;
	}

	/**
	 * Get reportadoIess
	 *
	 * @return null|String
	 */
	public function getReportadoIess(){
		return $this->reportadoIess;
	}

	/**
	 * Set idHistoriaOcupacional
	 *
	 * Llave foránea de la tabla historia_ocupacional
	 *
	 * @parámetro Integer $idHistoriaOcupacional
	 * @return IdHistoriaOcupacional
	 */
	public function setIdHistoriaOcupacional($idHistoriaOcupacional){
		$this->idHistoriaOcupacional = (integer) $idHistoriaOcupacional;
		return $this;
	}

	/**
	 * Get idHistoriaOcupacional
	 *
	 * @return null|Integer
	 */
	public function getIdHistoriaOcupacional(){
		return $this->idHistoriaOcupacional;
	}

	/**
	 * Set naturalezaLesion
	 *
	 * Naturaleza de la lesion
	 *
	 * @parámetro String $naturalezaLesion
	 * @return NaturalezaLesion
	 */
	public function setNaturalezaLesion($naturalezaLesion){
		$this->naturalezaLesion = (string) $naturalezaLesion;
		return $this;
	}

	/**
	 * Get naturalezaLesion
	 *
	 * @return null|String
	 */
	public function getNaturalezaLesion(){
		return $this->naturalezaLesion;
	}

	/**
	 * Set diasIncapacidad
	 *
	 * Días de incapacidad
	 *
	 * @parámetro Integer $diasIncapacidad
	 * @return DiasIncapacidad
	 */
	public function setDiasIncapacidad($diasIncapacidad){
		$this->diasIncapacidad = (integer) $diasIncapacidad;
		return $this;
	}

	/**
	 * Get diasIncapacidad
	 *
	 * @return null|Integer
	 */
	public function getDiasIncapacidad(){
		return $this->diasIncapacidad;
	}

	/**
	 * Set parteAfectada
	 *
	 * Partes afectadas
	 *
	 * @parámetro String $parteAfectada
	 * @return ParteAfectada
	 */
	public function setParteAfectada($parteAfectada){
		$this->parteAfectada = (string) $parteAfectada;
		return $this;
	}

	/**
	 * Get parteAfectada
	 *
	 * @return null|String
	 */
	public function getParteAfectada(){
		return $this->parteAfectada;
	}

	/**
	 * Set secuelas
	 *
	 * Secuelas
	 *
	 * @parámetro String $secuelas
	 * @return Secuelas
	 */
	public function setSecuelas($secuelas){
		$this->secuelas = (string) $secuelas;
		return $this;
	}

	/**
	 * Get secuelas
	 *
	 * @return null|String
	 */
	public function getSecuelas(){
		return $this->secuelas;
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
	 * @return AccidentesLaboralesModelo
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
