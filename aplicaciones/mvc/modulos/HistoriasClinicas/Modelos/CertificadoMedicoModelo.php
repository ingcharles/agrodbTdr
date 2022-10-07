<?php
/**
 * Modelo CertificadoMedicoModelo
 *
 * Este archivo se complementa con el archivo CertificadoMedicoLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses CertificadoMedicoModelo
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class CertificadoMedicoModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave primaria de la tabla
	 */
	protected $idCertificadoMedico;

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
	 *      Identificador del médico
	 */
	protected $identificadorMedico;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Descripción del certificado
	 */
	protected $descripcionCertificado;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha del certificado
	 */
	protected $fechaCertificado;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Análisis
	 */
	protected $analisis;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Recomendaciones
	 */
	protected $recomendaciones;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de salida
	 */
	protected $fechaSalida;

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
	 * Nombre de la tabla: certificado_medico
	 */
	private $tabla = "certificado_medico";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_certificado_medico";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_historias_clinicas"."certificado_medico_id_certificado_medico_seq';

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
			throw new \Exception('Clase Modelo: CertificadoMedicoModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: CertificadoMedicoModelo. Propiedad especificada invalida: get' . $name);
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
	 * Set idCertificadoMedico
	 *
	 * Llave primaria de la tabla
	 *
	 * @parámetro Integer $idCertificadoMedico
	 * @return IdCertificadoMedico
	 */
	public function setIdCertificadoMedico($idCertificadoMedico){
		$this->idCertificadoMedico = (integer) $idCertificadoMedico;
		return $this;
	}

	/**
	 * Get idCertificadoMedico
	 *
	 * @return null|Integer
	 */
	public function getIdCertificadoMedico(){
		return $this->idCertificadoMedico;
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
	 * Set descripcionCertificado
	 *
	 * Descripción del certificado
	 *
	 * @parámetro String $descripcionCertificado
	 * @return DescripcionCertificado
	 */
	public function setDescripcionCertificado($descripcionCertificado){
		$this->descripcionCertificado = (string) $descripcionCertificado;
		return $this;
	}

	/**
	 * Get descripcionCertificado
	 *
	 * @return null|String
	 */
	public function getDescripcionCertificado(){
		return $this->descripcionCertificado;
	}

	/**
	 * Set fechaCertificado
	 *
	 * Fecha del certificado
	 *
	 * @parámetro Date $fechaCertificado
	 * @return FechaCertificado
	 */
	public function setFechaCertificado($fechaCertificado){
		$this->fechaCertificado = (string) $fechaCertificado;
		return $this;
	}

	/**
	 * Get fechaCertificado
	 *
	 * @return null|Date
	 */
	public function getFechaCertificado(){
		return $this->fechaCertificado;
	}

	/**
	 * Set analisis
	 *
	 * Análisis
	 *
	 * @parámetro String $analisis
	 * @return Analisis
	 */
	public function setAnalisis($analisis){
		$this->analisis = (string) $analisis;
		return $this;
	}

	/**
	 * Get analisis
	 *
	 * @return null|String
	 */
	public function getAnalisis(){
		return $this->analisis;
	}

	/**
	 * Set recomendaciones
	 *
	 * Recomendaciones
	 *
	 * @parámetro String $recomendaciones
	 * @return Recomendaciones
	 */
	public function setRecomendaciones($recomendaciones){
		$this->recomendaciones = (string) $recomendaciones;
		return $this;
	}

	/**
	 * Get recomendaciones
	 *
	 * @return null|String
	 */
	public function getRecomendaciones(){
		return $this->recomendaciones;
	}

	/**
	 * Set fechaSalida
	 *
	 * Fecha de salida
	 *
	 * @parámetro Date $fechaSalida
	 * @return FechaSalida
	 */
	public function setFechaSalida($fechaSalida){
		$this->fechaSalida = (string) $fechaSalida;
		return $this;
	}

	/**
	 * Get fechaSalida
	 *
	 * @return null|Date
	 */
	public function getFechaSalida(){
		return $this->fechaSalida;
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
	 * @return CertificadoMedicoModelo
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
