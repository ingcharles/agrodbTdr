<?php
/**
 * Modelo RevisionDocumentalModelo
 *
 * Este archivo se complementa con el archivo RevisionDocumentalLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-07-14
 * @uses RevisionDocumentalModelo
 * @package RevisionFormularios
 * @subpackage Modelos
 */
namespace Agrodb\RevisionFormularios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class RevisionDocumentalModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idRevisionDocumental;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idGrupo;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $identificadorInspector;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $fechaInspeccion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $observacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $estado;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $orden;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $rutaArchivoDocumental;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_revision_solicitudes";

	/**
	 * Nombre de la tabla: revision_documental
	 */
	private $tabla = "revision_documental";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_revision_documental";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_revision_solicitudes"."revision_documental_id_revision_documental_seq';

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
			throw new \Exception('Clase Modelo: RevisionDocumentalModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: RevisionDocumentalModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_revision_solicitudes
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idRevisionDocumental
	 *
	 *
	 *
	 * @parámetro Integer $idRevisionDocumental
	 * @return IdRevisionDocumental
	 */
	public function setIdRevisionDocumental($idRevisionDocumental){
		$this->idRevisionDocumental = (integer) $idRevisionDocumental;
		return $this;
	}

	/**
	 * Get idRevisionDocumental
	 *
	 * @return null|Integer
	 */
	public function getIdRevisionDocumental(){
		return $this->idRevisionDocumental;
	}

	/**
	 * Set idGrupo
	 *
	 *
	 *
	 * @parámetro Integer $idGrupo
	 * @return IdGrupo
	 */
	public function setIdGrupo($idGrupo){
		$this->idGrupo = (integer) $idGrupo;
		return $this;
	}

	/**
	 * Get idGrupo
	 *
	 * @return null|Integer
	 */
	public function getIdGrupo(){
		return $this->idGrupo;
	}

	/**
	 * Set identificadorInspector
	 *
	 *
	 *
	 * @parámetro String $identificadorInspector
	 * @return IdentificadorInspector
	 */
	public function setIdentificadorInspector($identificadorInspector){
		$this->identificadorInspector = (string) $identificadorInspector;
		return $this;
	}

	/**
	 * Get identificadorInspector
	 *
	 * @return null|String
	 */
	public function getIdentificadorInspector(){
		return $this->identificadorInspector;
	}

	/**
	 * Set fechaInspeccion
	 *
	 *
	 *
	 * @parámetro Date $fechaInspeccion
	 * @return FechaInspeccion
	 */
	public function setFechaInspeccion($fechaInspeccion){
		$this->fechaInspeccion = (string) $fechaInspeccion;
		return $this;
	}

	/**
	 * Get fechaInspeccion
	 *
	 * @return null|Date
	 */
	public function getFechaInspeccion(){
		return $this->fechaInspeccion;
	}

	/**
	 * Set observacion
	 *
	 *
	 *
	 * @parámetro String $observacion
	 * @return Observacion
	 */
	public function setObservacion($observacion){
		$this->observacion = (string) $observacion;
		return $this;
	}

	/**
	 * Get observacion
	 *
	 * @return null|String
	 */
	public function getObservacion(){
		return $this->observacion;
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
	 * Set orden
	 *
	 *
	 *
	 * @parámetro Integer $orden
	 * @return Orden
	 */
	public function setOrden($orden){
		$this->orden = (integer) $orden;
		return $this;
	}

	/**
	 * Get orden
	 *
	 * @return null|Integer
	 */
	public function getOrden(){
		return $this->orden;
	}

	/**
	 * Set rutaArchivoDocumental
	 *
	 *
	 *
	 * @parámetro String $rutaArchivoDocumental
	 * @return RutaArchivoDocumental
	 */
	public function setRutaArchivoDocumental($rutaArchivoDocumental){
		$this->rutaArchivoDocumental = (string) $rutaArchivoDocumental;
		return $this;
	}

	/**
	 * Get rutaArchivoDocumental
	 *
	 * @return null|String
	 */
	public function getRutaArchivoDocumental(){
		return $this->rutaArchivoDocumental;
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
	 * @return RevisionDocumentalModelo
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
