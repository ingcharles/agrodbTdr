<?php
/**
 * Modelo SubsanacionModelo
 *
 * Este archivo se complementa con el archivo SubsanacionLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-07-25
 * @uses SubsanacionModelo
 * @package ProveedoresExterior
 * @subpackage Modelos
 */
namespace Agrodb\ProveedoresExterior\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class SubsanacionModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador_unico de la tabla
	 */
	protected $idSubsanacion;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador de la tabla g_proveedores_exterior.proveedor_exterior (llave foranea)
	 */
	protected $idProveedorExterior;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador unico de la tabla g_proveedores_exterior.periodo_subsanacion (llave foranea)
	 */
	protected $idPeriodoSubsanacion;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena los dias del periodo de subsanacion
	 */
	protected $diasSubsanacion;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena los dias restantes para realizar el proceso de subsanacion
	 */
	protected $saldoDiasSubsanacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena el identificador del técnico que genera el proceso de subsanacion
	 */
	protected $identificadorRevisor;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena la observacion registrada por el tecnico que genera el proceso de subsanacion
	 */
	protected $observacionSubsanacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena el documento de subsanacion adjuntado
	 */
	protected $rutaArchivoSubsanacion;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena la fecha en que se envia a subsanar la solicitud
	 */
	protected $fechaSubsanacion;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena la fecha en la que el operador subsana la solicitud
	 */
	protected $fechaSubsanacionOperador;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo bandera que indica si se debe realizar el proceso de descuento de dias
	 */
	protected $descontarDias;

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
	 * Nombre de la tabla: subsanacion
	 */
	private $tabla = "subsanacion";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_subsanacion";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_proveedores_exterior"."subsanacion_id_subsanacion_seq';

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
			throw new \Exception('Clase Modelo: SubsanacionModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: SubsanacionModelo. Propiedad especificada invalida: get' . $name);
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
	 * Set idSubsanacion
	 *
	 * Identificador_unico de la tabla
	 *
	 * @parámetro Integer $idSubsanacion
	 * @return IdSubsanacion
	 */
	public function setIdSubsanacion($idSubsanacion){
		$this->idSubsanacion = (integer) $idSubsanacion;
		return $this;
	}

	/**
	 * Get idSubsanacion
	 *
	 * @return null|Integer
	 */
	public function getIdSubsanacion(){
		return $this->idSubsanacion;
	}

	/**
	 * Set idProveedorExterior
	 *
	 * Identificador de la tabla g_proveedores_exterior.proveedor_exterior (llave foranea)
	 *
	 * @parámetro Integer $idProveedorExterior
	 * @return IdProveedorExterior
	 */
	public function setIdProveedorExterior($idProveedorExterior){
		$this->idProveedorExterior = (integer) $idProveedorExterior;
		return $this;
	}

	/**
	 * Get idProveedorExterior
	 *
	 * @return null|Integer
	 */
	public function getIdProveedorExterior(){
		return $this->idProveedorExterior;
	}

	/**
	 * Set idPeriodoSubsanacion
	 *
	 * Identificador unico de la tabla g_proveedores_exterior.periodo_subsanacion (llave foranea)
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
	 * Set diasSubsanacion
	 *
	 * Campo que almacena los dias del periodo de subsanacion
	 *
	 * @parámetro Integer $diasSubsanacion
	 * @return DiasSubsanacion
	 */
	public function setDiasSubsanacion($diasSubsanacion){
		$this->diasSubsanacion = (integer) $diasSubsanacion;
		return $this;
	}

	/**
	 * Get diasSubsanacion
	 *
	 * @return null|Integer
	 */
	public function getDiasSubsanacion(){
		return $this->diasSubsanacion;
	}

	/**
	 * Set saldoDiasSubsanacion
	 *
	 * Campo que almacena los dias restantes para realizar el proceso de subsanacion
	 *
	 * @parámetro Integer $saldoDiasSubsanacion
	 * @return SaldoDiasSubsanacion
	 */
	public function setSaldoDiasSubsanacion($saldoDiasSubsanacion){
		$this->saldoDiasSubsanacion = (integer) $saldoDiasSubsanacion;
		return $this;
	}

	/**
	 * Get saldoDiasSubsanacion
	 *
	 * @return null|Integer
	 */
	public function getSaldoDiasSubsanacion(){
		return $this->saldoDiasSubsanacion;
	}

	/**
	 * Set identificadorRevisor
	 *
	 * Campo que almacena el identificador del técnico que genera el proceso de subsanacion
	 *
	 * @parámetro String $identificadorRevisor
	 * @return IdentificadorRevisor
	 */
	public function setIdentificadorRevisor($identificadorRevisor){
		$this->identificadorRevisor = (string) $identificadorRevisor;
		return $this;
	}

	/**
	 * Get identificadorRevisor
	 *
	 * @return null|String
	 */
	public function getIdentificadorRevisor(){
		return $this->identificadorRevisor;
	}

	/**
	 * Set observacionSubsanacion
	 *
	 * Campo que almacena la observacion registrada por el tecnico que genera el proceso de subsanacion
	 *
	 * @parámetro String $observacionSubsanacion
	 * @return ObservacionSubsanacion
	 */
	public function setObservacionSubsanacion($observacionSubsanacion){
		$this->observacionSubsanacion = (string) $observacionSubsanacion;
		return $this;
	}

	/**
	 * Get observacionSubsanacion
	 *
	 * @return null|String
	 */
	public function getObservacionSubsanacion(){
		return $this->observacionSubsanacion;
	}

	/**
	 * Set rutaArchivoSubsanacion
	 *
	 * Campo que almacena el documento de subsanacion adjuntado
	 *
	 * @parámetro String $rutaArchivoSubsanacion
	 * @return RutaArchivoSubsanacion
	 */
	public function setRutaArchivoSubsanacion($rutaArchivoSubsanacion){
		$this->rutaArchivoSubsanacion = (string) $rutaArchivoSubsanacion;
		return $this;
	}

	/**
	 * Get rutaArchivoSubsanacion
	 *
	 * @return null|String
	 */
	public function getRutaArchivoSubsanacion(){
		return $this->rutaArchivoSubsanacion;
	}

	/**
	 * Set fechaSubsanacion
	 *
	 * Campo que almacena la fecha en que se envia a subsanar la solicitud
	 *
	 * @parámetro Date $fechaSubsanacion
	 * @return FechaSubsanacion
	 */
	public function setFechaSubsanacion($fechaSubsanacion){
		$this->fechaSubsanacion = (string) $fechaSubsanacion;
		return $this;
	}

	/**
	 * Get fechaSubsanacion
	 *
	 * @return null|Date
	 */
	public function getFechaSubsanacion(){
		return $this->fechaSubsanacion;
	}

	/**
	 * Set fechaSubsanacionOperador
	 *
	 * Campo que almacena la fecha en la que el operador subsana la solicitud
	 *
	 * @parámetro Date $fechaSubsanacionOperador
	 * @return FechaSubsanacionOperador
	 */
	public function setFechaSubsanacionOperador($fechaSubsanacionOperador){
		$this->fechaSubsanacionOperador = (string) $fechaSubsanacionOperador;
		return $this;
	}

	/**
	 * Get fechaSubsanacionOperador
	 *
	 * @return null|Date
	 */
	public function getFechaSubsanacionOperador(){
		return $this->fechaSubsanacionOperador;
	}

	/**
	 * Set descontarDias
	 *
	 * Campo bandera que indica si se debe realizar el proceso de descuento de dias
	 *
	 * @parámetro String $descontarDias
	 * @return DescontarDias
	 */
	public function setDescontarDias($descontarDias){
		$this->descontarDias = (string) $descontarDias;
		return $this;
	}

	/**
	 * Get descontarDias
	 *
	 * @return null|String
	 */
	public function getDescontarDias(){
		return $this->descontarDias;
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
	 * @return SubsanacionModelo
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
