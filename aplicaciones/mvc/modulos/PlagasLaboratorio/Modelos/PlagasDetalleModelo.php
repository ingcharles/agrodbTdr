<?php
/**
 * Modelo PlagasDetalleModelo
 *
 * Este archivo se complementa con el archivo PlagasDetalleLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-03-24
 * @uses PlagasDetalleModelo
 * @package PlagasLaboratorio
 * @subpackage Modelos
 */
namespace Agrodb\PlagasLaboratorio\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class PlagasDetalleModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador único de la tabla
	 */
	protected $idPlagaDetalle;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave foránea de la tabla g_plagas_laboratorio.plagas
	 */
	protected $idPlaga;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Número de reportes en los cuales se detectó la plaga
	 */
	protected $numeroReporte;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador de la tabla g_catalogos.localizacion
	 */
	protected $idProvincia;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre de la provincia
	 */
	protected $nombreProvincia;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre del técnico que identifica la plaga
	 */
	protected $identificadoPor;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha en la cual se detecto el registro puede ser la fecha actual o anterior
	 */
	protected $fechaIngreso;

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
	 *      Usuario que crea el registro
	 */
	protected $identificadorCreacion;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de modificación del registro
	 */
	protected $fechaModificacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Usuario que modifica el registro
	 */
	protected $identificadorModificacion;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_plagas_laboratorio";

	/**
	 * Nombre de la tabla: plagas_detalle
	 */
	private $tabla = "plagas_detalle";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_plaga_detalle";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_plagas_laboratorio"."plagas_detalle_id_plaga_detalle_seq';

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
			throw new \Exception('Clase Modelo: PlagasDetalleModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: PlagasDetalleModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_plagas_laboratorio
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idPlagaDetalle
	 *
	 * Identificador único de la tabla
	 *
	 * @parámetro Integer $idPlagaDetalle
	 * @return IdPlagaDetalle
	 */
	public function setIdPlagaDetalle($idPlagaDetalle){
		$this->idPlagaDetalle = (integer) $idPlagaDetalle;
		return $this;
	}

	/**
	 * Get idPlagaDetalle
	 *
	 * @return null|Integer
	 */
	public function getIdPlagaDetalle(){
		return $this->idPlagaDetalle;
	}

	/**
	 * Set idPlaga
	 *
	 * Llave foránea de la tabla g_plagas_laboratorio.plagas
	 *
	 * @parámetro Integer $idPlaga
	 * @return IdPlaga
	 */
	public function setIdPlaga($idPlaga){
		$this->idPlaga = (integer) $idPlaga;
		return $this;
	}

	/**
	 * Get idPlaga
	 *
	 * @return null|Integer
	 */
	public function getIdPlaga(){
		return $this->idPlaga;
	}

	/**
	 * Set numeroReporte
	 *
	 * Número de reportes en los cuales se detectó la plaga
	 *
	 * @parámetro String $numeroReporte
	 * @return NumeroReporte
	 */
	public function setNumeroReporte($numeroReporte){
		$this->numeroReporte = (string) $numeroReporte;
		return $this;
	}

	/**
	 * Get numeroReporte
	 *
	 * @return null|String
	 */
	public function getNumeroReporte(){
		return $this->numeroReporte;
	}

	/**
	 * Set idProvincia
	 *
	 * Identificador de la tabla g_catalogos.localizacion
	 *
	 * @parámetro Integer $idProvincia
	 * @return IdProvincia
	 */
	public function setIdProvincia($idProvincia){
		$this->idProvincia = (integer) $idProvincia;
		return $this;
	}

	/**
	 * Get idProvincia
	 *
	 * @return null|Integer
	 */
	public function getIdProvincia(){
		return $this->idProvincia;
	}

	/**
	 * Set nombreProvincia
	 *
	 * Nombre de la provincia
	 *
	 * @parámetro String $nombreProvincia
	 * @return NombreProvincia
	 */
	public function setNombreProvincia($nombreProvincia){
		$this->nombreProvincia = (string) $nombreProvincia;
		return $this;
	}

	/**
	 * Get nombreProvincia
	 *
	 * @return null|String
	 */
	public function getNombreProvincia(){
		return $this->nombreProvincia;
	}

	/**
	 * Set identificadoPor
	 *
	 * Nombre del técnico que identifica la plaga
	 *
	 * @parámetro String $identificadoPor
	 * @return IdentificadoPor
	 */
	public function setIdentificadoPor($identificadoPor){
		$this->identificadoPor = (string) $identificadoPor;
		return $this;
	}

	/**
	 * Get identificadoPor
	 *
	 * @return null|String
	 */
	public function getIdentificadoPor(){
		return $this->identificadoPor;
	}

	/**
	 * Set fechaIngreso
	 *
	 * Fecha en la cual se detecto el registro puede ser la fecha actual o anterior
	 *
	 * @parámetro Date $fechaIngreso
	 * @return FechaIngreso
	 */
	public function setFechaIngreso($fechaIngreso){
		$this->fechaIngreso = (string) $fechaIngreso;
		return $this;
	}

	/**
	 * Get fechaIngreso
	 *
	 * @return null|Date
	 */
	public function getFechaIngreso(){
		return $this->fechaIngreso;
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
	 * Set identificadorCreacion
	 *
	 * Usuario que crea el registro
	 *
	 * @parámetro String $identificadorCreacion
	 * @return IdentificadorCreacion
	 */
	public function setIdentificadorCreacion($identificadorCreacion){
		$this->identificadorCreacion = (string) $identificadorCreacion;
		return $this;
	}

	/**
	 * Get identificadorCreacion
	 *
	 * @return null|String
	 */
	public function getIdentificadorCreacion(){
		return $this->identificadorCreacion;
	}

	/**
	 * Set fechaModificacion
	 *
	 * Fecha de modificación del registro
	 *
	 * @parámetro Date $fechaModificacion
	 * @return FechaModificacion
	 */
	public function setFechaModificacion($fechaModificacion){
		$this->fechaModificacion = (string) $fechaModificacion;
		return $this;
	}

	/**
	 * Get fechaModificacion
	 *
	 * @return null|Date
	 */
	public function getFechaModificacion(){
		return $this->fechaModificacion;
	}

	/**
	 * Set identificadorModificacion
	 *
	 * Usuario que modifica el registro
	 *
	 * @parámetro String $identificadorModificacion
	 * @return IdentificadorModificacion
	 */
	public function setIdentificadorModificacion($identificadorModificacion){
		$this->identificadorModificacion = (string) $identificadorModificacion;
		return $this;
	}

	/**
	 * Get identificadorModificacion
	 *
	 * @return null|String
	 */
	public function getIdentificadorModificacion(){
		return $this->identificadorModificacion;
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
	 * @return PlagasDetalleModelo
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
