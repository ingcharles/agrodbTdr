<?php
/**
 * Modelo HistoriaClinicaModelo
 *
 * Este archivo se complementa con el archivo HistoriaClinicaLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses HistoriaClinicaModelo
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class HistoriaClinicaModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave primaria de la tabla
	 */
	protected $idHistoriaClinica;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador del funcionario
	 */
	protected $identificadorPaciente;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador médico ocupacional
	 */
	protected $identificadorMedico;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Observaciones de revision de organos
	 */
	protected $observacionesRevisionOrganos;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Documento adjunto de examenes clínicos
	 */
	protected $documentoAdjuntoExamenesClinicos;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Descripcion de concepto
	 */
	protected $descripcionConcepto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Tipo de restricción o limitación
	 */
	protected $tipoRestriccionLimitacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
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
	 * Nombre de la tabla: historia_clinica
	 */
	private $tabla = "historia_clinica";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_historia_clinica";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_historias_clinicas"."historia_clinica_id_historia_clinica_seq';

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
			throw new \Exception('Clase Modelo: HistoriaClinicaModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: HistoriaClinicaModelo. Propiedad especificada invalida: get' . $name);
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
	 * Set idHistoriaClinica
	 *
	 * Llave primaria de la tabla
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
	 * Set identificadorPaciente
	 *
	 * Identificador del funcionario
	 *
	 * @parámetro String $identificadorPaciente
	 * @return IdentificadorPaciente
	 */
	public function setIdentificadorPaciente($identificadorPaciente){
		$this->identificadorPaciente = (string) $identificadorPaciente;
		return $this;
	}

	/**
	 * Get identificadorPaciente
	 *
	 * @return null|String
	 */
	public function getIdentificadorPaciente(){
		return $this->identificadorPaciente;
	}

	/**
	 * Set identificadorMedico
	 *
	 * Identificador médico ocupacional
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
	 * Set observacionesRevisionOrganos
	 *
	 * Observaciones de revision de organos
	 *
	 * @parámetro String $observacionesRevisionOrganos
	 * @return ObservacionesRevisionOrganos
	 */
	public function setObservacionesRevisionOrganos($observacionesRevisionOrganos){
		$this->observacionesRevisionOrganos = (string) $observacionesRevisionOrganos;
		return $this;
	}

	/**
	 * Get observacionesRevisionOrganos
	 *
	 * @return null|String
	 */
	public function getObservacionesRevisionOrganos(){
		return $this->observacionesRevisionOrganos;
	}

	/**
	 * Set documentoAdjuntoExamenesClinicos
	 *
	 * Documento adjunto de examenes clínicos
	 *
	 * @parámetro String $documentoAdjuntoExamenesClinicos
	 * @return DocumentoAdjuntoExamenesClinicos
	 */
	public function setDocumentoAdjuntoExamenesClinicos($documentoAdjuntoExamenesClinicos){
		$this->documentoAdjuntoExamenesClinicos = (string) $documentoAdjuntoExamenesClinicos;
		return $this;
	}

	/**
	 * Get documentoAdjuntoExamenesClinicos
	 *
	 * @return null|String
	 */
	public function getDocumentoAdjuntoExamenesClinicos(){
		return $this->documentoAdjuntoExamenesClinicos;
	}

	/**
	 * Set descripcionConcepto
	 *
	 * Descripcion de concepto
	 *
	 * @parámetro String $descripcionConcepto
	 * @return DescripcionConcepto
	 */
	public function setDescripcionConcepto($descripcionConcepto){
		$this->descripcionConcepto = (string) $descripcionConcepto;
		return $this;
	}

	/**
	 * Get descripcionConcepto
	 *
	 * @return null|String
	 */
	public function getDescripcionConcepto(){
		return $this->descripcionConcepto;
	}

	/**
	 * Set tipoRestriccionLimitacion
	 *
	 * Tipo de restricción o limitación
	 *
	 * @parámetro String $tipoRestriccionLimitacion
	 * @return TipoRestriccionLimitacion
	 */
	public function setTipoRestriccionLimitacion($tipoRestriccionLimitacion){
		$this->tipoRestriccionLimitacion = (string) $tipoRestriccionLimitacion;
		return $this;
	}

	/**
	 * Get tipoRestriccionLimitacion
	 *
	 * @return null|String
	 */
	public function getTipoRestriccionLimitacion(){
		return $this->tipoRestriccionLimitacion;
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
	 * @return HistoriaClinicaModelo
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
