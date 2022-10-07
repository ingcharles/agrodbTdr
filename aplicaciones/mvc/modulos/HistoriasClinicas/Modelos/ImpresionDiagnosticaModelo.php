<?php
/**
 * Modelo ImpresionDiagnosticaModelo
 *
 * Este archivo se complementa con el archivo ImpresionDiagnosticaLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses ImpresionDiagnosticaModelo
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ImpresionDiagnosticaModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave primaria de la tabla
	 */
	protected $idImpresionDiagnostica;

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
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave foránea de la tabla cie_10
	 */
	protected $idCie;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Diagnostico
	 */
	protected $diagnostico;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Estado del diagnostico
	 */
	protected $estadoDiagnostico;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Observaciones
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
	 * Nombre de la tabla: impresion_diagnostica
	 */
	private $tabla = "impresion_diagnostica";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_impresion_diagnostica";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_historias_clinicas"."impresion_diagnostica_id_impresion_diagnostica_seq';

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
			throw new \Exception('Clase Modelo: ImpresionDiagnosticaModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: ImpresionDiagnosticaModelo. Propiedad especificada invalida: get' . $name);
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
	 * Set idImpresionDiagnostica
	 *
	 * Llave primaria de la tabla
	 *
	 * @parámetro Integer $idImpresionDiagnostica
	 * @return IdImpresionDiagnostica
	 */
	public function setIdImpresionDiagnostica($idImpresionDiagnostica){
		$this->idImpresionDiagnostica = (integer) $idImpresionDiagnostica;
		return $this;
	}

	/**
	 * Get idImpresionDiagnostica
	 *
	 * @return null|Integer
	 */
	public function getIdImpresionDiagnostica(){
		return $this->idImpresionDiagnostica;
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
	 * Set idCie
	 *
	 * Llave foránea de la tabla cie
	 *
	 * @parámetro Integer $idCie
	 * @return IdCie
	 */
	public function setIdCie($idCie){
		$this->idCie = (integer) $idCie;
		return $this;
	}

	/**
	 * Get idCie
	 *
	 * @return null|Integer
	 */
	public function getIdCie(){
		return $this->idCie;
	}

	/**
	 * Set diagnostico
	 *
	 * Diagnostico
	 *
	 * @parámetro String $diagnostico
	 * @return Diagnostico
	 */
	public function setDiagnostico($diagnostico){
		$this->diagnostico = (string) $diagnostico;
		return $this;
	}

	/**
	 * Get diagnostico
	 *
	 * @return null|String
	 */
	public function getDiagnostico(){
		return $this->diagnostico;
	}

	/**
	 * Set estadoDiagnostico
	 *
	 * Estado del diagnostico
	 *
	 * @parámetro String $estadoDiagnostico
	 * @return EstadoDiagnostico
	 */
	public function setEstadoDiagnostico($estadoDiagnostico){
		$this->estadoDiagnostico = (string) $estadoDiagnostico;
		return $this;
	}

	/**
	 * Get estadoDiagnostico
	 *
	 * @return null|String
	 */
	public function getEstadoDiagnostico(){
		return $this->estadoDiagnostico;
	}

	/**
	 * Set observaciones
	 *
	 * Observaciones
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
	 * @return ImpresionDiagnosticaModelo
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
