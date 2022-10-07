<?php
/**
 * Modelo CultivosModelo
 *
 * Este archivo se complementa con el archivo CultivosLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-03-24
 * @uses CultivosModelo
 * @package PlagasLaboratorio
 * @subpackage Modelos
 */
namespace Agrodb\PlagasLaboratorio\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class CultivosModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador único de la tabla
	 */
	protected $idCultivo;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre común de un cultivo, sobre el que se detecta una o varias plagas
	 */
	protected $nombreComun;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre científico del cultivo
	 */
	protected $nombreCientifico;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de creación del registro
	 */
	protected $fechaCreacion;

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
	 *      Identificación de usuario que crea el registro
	 */
	protected $identificacionCreacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Identificación de usuario que modifica el registro
	 */
	protected $identificacionModificacion;

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
	 * Nombre de la tabla: cultivos
	 */
	private $tabla = "cultivos";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_cultivo";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_plagas_laboratorio"."cultivos_id_cultivo_seq';

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
			throw new \Exception('Clase Modelo: CultivosModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: CultivosModelo. Propiedad especificada invalida: get' . $name);
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
	 * Set idCultivo
	 *
	 * Identificador único de la tabla
	 *
	 * @parámetro Integer $idCultivo
	 * @return IdCultivo
	 */
	public function setIdCultivo($idCultivo){
		$this->idCultivo = (integer) $idCultivo;
		return $this;
	}

	/**
	 * Get idCultivo
	 *
	 * @return null|Integer
	 */
	public function getIdCultivo(){
		return $this->idCultivo;
	}

	/**
	 * Set nombreComun
	 *
	 * Nombre común de un cultivo, sobre el que se detecta una o varias plagas
	 *
	 * @parámetro String $nombreComun
	 * @return NombreComun
	 */
	public function setNombreComun($nombreComun){
		$this->nombreComun = (string) $nombreComun;
		return $this;
	}

	/**
	 * Get nombreComun
	 *
	 * @return null|String
	 */
	public function getNombreComun(){
		return $this->nombreComun;
	}

	/**
	 * Set nombreCientifico
	 *
	 * Nombre científico del cultivo
	 *
	 * @parámetro String $nombreCientifico
	 * @return NombreCientifico
	 */
	public function setNombreCientifico($nombreCientifico){
		$this->nombreCientifico = (string) $nombreCientifico;
		return $this;
	}

	/**
	 * Get nombreCientifico
	 *
	 * @return null|String
	 */
	public function getNombreCientifico(){
		return $this->nombreCientifico;
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
	 * Set identificacionCreacion
	 *
	 * Identificación de usuario que crea el registro
	 *
	 * @parámetro String $identificacionCreacion
	 * @return IdentificacionCreacion
	 */
	public function setIdentificacionCreacion($identificacionCreacion){
		$this->identificacionCreacion = (string) $identificacionCreacion;
		return $this;
	}

	/**
	 * Get identificacionCreacion
	 *
	 * @return null|String
	 */
	public function getIdentificacionCreacion(){
		return $this->identificacionCreacion;
	}

	/**
	 * Set identificacionModificacion
	 *
	 * Identificación de usuario que modifica el registro
	 *
	 * @parámetro String $identificacionModificacion
	 * @return IdentificacionModificacion
	 */
	public function setIdentificacionModificacion($identificacionModificacion){
		$this->identificacionModificacion = (string) $identificacionModificacion;
		return $this;
	}

	/**
	 * Get identificacionModificacion
	 *
	 * @return null|String
	 */
	public function getIdentificacionModificacion(){
		return $this->identificacionModificacion;
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
	 * @return CultivosModelo
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
