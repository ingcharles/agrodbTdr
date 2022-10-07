<?php
/**
 * Modelo ExamenFisicoModelo
 *
 * Este archivo se complementa con el archivo ExamenFisicoLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses ExamenFisicoModelo
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ExamenFisicoModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave primaria de la tabla
	 */
	protected $idExamenFisico;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave foránea de la tabla historia_clinica
	 */
	protected $idHistoriaClinica;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave foránea de la tabla consulta_medica
	 */
	protected $idConsultaMedica;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Tension arterial
	 */
	protected $tensionArterial;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Saturación de oxigeno
	 */
	protected $saturacionOxigeno;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Frecuencia cardiaca
	 */
	protected $frecuenciaCardiaca;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Frecuencia respiratoria
	 */
	protected $frecuenciaRespiratoria;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Tall en metros
	 */
	protected $tallaMts;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Temperatura en grados centigrados
	 */
	protected $temperaturaC;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Peso en kilogramos
	 */
	protected $pesoKg;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Imc
	 */
	protected $imc;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Interpretación de imc
	 */
	protected $interpretacionImc;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Estado del registro
	 */
	protected $estado;

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
	 * Nombre de la tabla: examen_fisico
	 */
	private $tabla = "examen_fisico";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_examen_fisico";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_historias_clinicas"."examen_fisico_id_examen_fisico_seq';

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
			throw new \Exception('Clase Modelo: ExamenFisicoModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: ExamenFisicoModelo. Propiedad especificada invalida: get' . $name);
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
	 * Set idExamenFisico
	 *
	 * Llave primaria de la tabla
	 *
	 * @parámetro Integer $idExamenFisico
	 * @return IdExamenFisico
	 */
	public function setIdExamenFisico($idExamenFisico){
		$this->idExamenFisico = (integer) $idExamenFisico;
		return $this;
	}

	/**
	 * Get idExamenFisico
	 *
	 * @return null|Integer
	 */
	public function getIdExamenFisico(){
		return $this->idExamenFisico;
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
	 * Set idConsultaMedica
	 *
	 * Llave foránea de la tabla consulta_medica
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
	 * Set tensionArterial
	 *
	 * Tension arterial
	 *
	 * @parámetro String $tensionArterial
	 * @return TensionArterial
	 */
	public function setTensionArterial($tensionArterial){
		$this->tensionArterial = (string) $tensionArterial;
		return $this;
	}

	/**
	 * Get tensionArterial
	 *
	 * @return null|String
	 */
	public function getTensionArterial(){
		return $this->tensionArterial;
	}

	/**
	 * Set saturacionOxigeno
	 *
	 * Saturación de oxigeno
	 *
	 * @parámetro String $saturacionOxigeno
	 * @return SaturacionOxigeno
	 */
	public function setSaturacionOxigeno($saturacionOxigeno){
		$this->saturacionOxigeno = (string) $saturacionOxigeno;
		return $this;
	}

	/**
	 * Get saturacionOxigeno
	 *
	 * @return null|String
	 */
	public function getSaturacionOxigeno(){
		return $this->saturacionOxigeno;
	}

	/**
	 * Set frecuenciaCardiaca
	 *
	 * Frecuencia cardiaca
	 *
	 * @parámetro String $frecuenciaCardiaca
	 * @return FrecuenciaCardiaca
	 */
	public function setFrecuenciaCardiaca($frecuenciaCardiaca){
		$this->frecuenciaCardiaca = (string) $frecuenciaCardiaca;
		return $this;
	}

	/**
	 * Get frecuenciaCardiaca
	 *
	 * @return null|String
	 */
	public function getFrecuenciaCardiaca(){
		return $this->frecuenciaCardiaca;
	}

	/**
	 * Set frecuenciaRespiratoria
	 *
	 * Frecuencia respiratoria
	 *
	 * @parámetro String $frecuenciaRespiratoria
	 * @return FrecuenciaRespiratoria
	 */
	public function setFrecuenciaRespiratoria($frecuenciaRespiratoria){
		$this->frecuenciaRespiratoria = (string) $frecuenciaRespiratoria;
		return $this;
	}

	/**
	 * Get frecuenciaRespiratoria
	 *
	 * @return null|String
	 */
	public function getFrecuenciaRespiratoria(){
		return $this->frecuenciaRespiratoria;
	}

	/**
	 * Set tallaMts
	 *
	 * Tall en metros
	 *
	 * @parámetro String $tallaMts
	 * @return TallaMts
	 */
	public function setTallaMts($tallaMts){
		$this->tallaMts = (string) $tallaMts;
		return $this;
	}

	/**
	 * Get tallaMts
	 *
	 * @return null|String
	 */
	public function getTallaMts(){
		return $this->tallaMts;
	}

	/**
	 * Set temperaturaC
	 *
	 * Temperatura en grados centigrados
	 *
	 * @parámetro String $temperaturaC
	 * @return TemperaturaC
	 */
	public function setTemperaturaC($temperaturaC){
		$this->temperaturaC = (string) $temperaturaC;
		return $this;
	}

	/**
	 * Get temperaturaC
	 *
	 * @return null|String
	 */
	public function getTemperaturaC(){
		return $this->temperaturaC;
	}

	/**
	 * Set pesoKg
	 *
	 * Peso en kilogramos
	 *
	 * @parámetro String $pesoKg
	 * @return PesoKg
	 */
	public function setPesoKg($pesoKg){
		$this->pesoKg = (string) $pesoKg;
		return $this;
	}

	/**
	 * Get pesoKg
	 *
	 * @return null|String
	 */
	public function getPesoKg(){
		return $this->pesoKg;
	}

	/**
	 * Set imc
	 *
	 * Imc
	 *
	 * @parámetro String $imc
	 * @return Imc
	 */
	public function setImc($imc){
		$this->imc = (string) $imc;
		return $this;
	}

	/**
	 * Get imc
	 *
	 * @return null|String
	 */
	public function getImc(){
		return $this->imc;
	}

	/**
	 * Set interpretacionImc
	 *
	 * Interpretación de imc
	 *
	 * @parámetro String $interpretacionImc
	 * @return InterpretacionImc
	 */
	public function setInterpretacionImc($interpretacionImc){
		$this->interpretacionImc = (string) $interpretacionImc;
		return $this;
	}

	/**
	 * Get interpretacionImc
	 *
	 * @return null|String
	 */
	public function getInterpretacionImc(){
		return $this->interpretacionImc;
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
	 * @return ExamenFisicoModelo
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
