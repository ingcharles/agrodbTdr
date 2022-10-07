<?php
/**
 * Modelo AsignacionInspectorModelo
 *
 * Este archivo se complementa con el archivo AsignacionInspectorLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-07-14
 * @uses AsignacionInspectorModelo
 * @package RevisionFormularios
 * @subpackage Modelos
 */
namespace Agrodb\RevisionFormularios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class AsignacionInspectorModelo extends ModeloBase{

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
	protected $fechaAsignacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $identificadorAsignante;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $tipoSolicitud;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $tipoInspector;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $resultado;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idOperadorTipoOperacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idHistorialOperacion;

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
	 * Nombre de la tabla: asignacion_inspector
	 */
	private $tabla = "asignacion_inspector";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_grupo";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_revision_solicitudes"."asignacion_inspector_id_grupo_seq';
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
			throw new \Exception('Clase Modelo: AsignacionInspectorModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: AsignacionInspectorModelo. Propiedad especificada invalida: get' . $name);
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
	 * Set fechaAsignacion
	 *
	 *
	 *
	 * @parámetro Date $fechaAsignacion
	 * @return FechaAsignacion
	 */
	public function setFechaAsignacion($fechaAsignacion){
		$this->fechaAsignacion = (string) $fechaAsignacion;
		return $this;
	}

	/**
	 * Get fechaAsignacion
	 *
	 * @return null|Date
	 */
	public function getFechaAsignacion(){
		return $this->fechaAsignacion;
	}

	/**
	 * Set identificadorAsignante
	 *
	 *
	 *
	 * @parámetro String $identificadorAsignante
	 * @return IdentificadorAsignante
	 */
	public function setIdentificadorAsignante($identificadorAsignante){
		$this->identificadorAsignante = (string) $identificadorAsignante;
		return $this;
	}

	/**
	 * Get identificadorAsignante
	 *
	 * @return null|String
	 */
	public function getIdentificadorAsignante(){
		return $this->identificadorAsignante;
	}

	/**
	 * Set tipoSolicitud
	 *
	 *
	 *
	 * @parámetro String $tipoSolicitud
	 * @return TipoSolicitud
	 */
	public function setTipoSolicitud($tipoSolicitud){
		$this->tipoSolicitud = (string) $tipoSolicitud;
		return $this;
	}

	/**
	 * Get tipoSolicitud
	 *
	 * @return null|String
	 */
	public function getTipoSolicitud(){
		return $this->tipoSolicitud;
	}

	/**
	 * Set tipoInspector
	 *
	 *
	 *
	 * @parámetro String $tipoInspector
	 * @return TipoInspector
	 */
	public function setTipoInspector($tipoInspector){
		$this->tipoInspector = (string) $tipoInspector;
		return $this;
	}

	/**
	 * Get tipoInspector
	 *
	 * @return null|String
	 */
	public function getTipoInspector(){
		return $this->tipoInspector;
	}

	/**
	 * Set resultado
	 *
	 *
	 *
	 * @parámetro String $resultado
	 * @return Resultado
	 */
	public function setResultado($resultado){
		$this->resultado = (string) $resultado;
		return $this;
	}

	/**
	 * Get resultado
	 *
	 * @return null|String
	 */
	public function getResultado(){
		return $this->resultado;
	}

	/**
	 * Set idOperadorTipoOperacion
	 *
	 *
	 *
	 * @parámetro String $idOperadorTipoOperacion
	 * @return IdOperadorTipoOperacion
	 */
	public function setIdOperadorTipoOperacion($idOperadorTipoOperacion){
		$this->idOperadorTipoOperacion = (string) $idOperadorTipoOperacion;
		return $this;
	}

	/**
	 * Get idOperadorTipoOperacion
	 *
	 * @return null|String
	 */
	public function getIdOperadorTipoOperacion(){
		return $this->idOperadorTipoOperacion;
	}

	/**
	 * Set idHistorialOperacion
	 *
	 *
	 *
	 * @parámetro String $idHistorialOperacion
	 * @return IdHistorialOperacion
	 */
	public function setIdHistorialOperacion($idHistorialOperacion){
		$this->idHistorialOperacion = (string) $idHistorialOperacion;
		return $this;
	}

	/**
	 * Get idHistorialOperacion
	 *
	 * @return null|String
	 */
	public function getIdHistorialOperacion(){
		return $this->idHistorialOperacion;
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
	 * @return AsignacionInspectorModelo
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
