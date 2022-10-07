<?php
/**
 * Modelo JornadaLaboralModelo
 *
 * Este archivo se complementa con el archivo JornadaLaboralLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2020-06-09
 * @uses JornadaLaboralModelo
 * @package JornadaLaboral
 * @subpackage Modelos
 */
namespace Agrodb\JornadaLaboral\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class JornadaLaboralModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador único de la tabla
	 */
	protected $idJornadaLaboral;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Numero de identificación del funcionario
	 */
	protected $identificador;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Tipo de grupo, opciones: Grupo 1, Grupo 2
	 */
	protected $grupo;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Horario en el que desempeña su jornada laboral cada funcionario
	 */
	protected $horario;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      mes en el cual se estable la jornada laboral
	 */
	protected $mes;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Estado del registro
	 */
	protected $estadoRegistro;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de ingreso del registro
	 */
	protected $fechaRegistro;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de modificación del registro
	 */
	protected $fechaModificacion;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_uath";

	/**
	 * Nombre de la tabla: jornada_laboral
	 */
	private $tabla = "jornada_laboral";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_jornada_laboral";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_uath"."jornada_laboral_id_jornada_laboral_seq';

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
			throw new \Exception('Clase Modelo: JornadaLaboralModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: JornadaLaboralModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_uath
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idJornadaLaboral
	 *
	 * Identificador único de la tabla
	 *
	 * @parámetro Integer $idJornadaLaboral
	 * @return IdJornadaLaboral
	 */
	public function setIdJornadaLaboral($idJornadaLaboral){
		$this->idJornadaLaboral = (integer) $idJornadaLaboral;
		return $this;
	}

	/**
	 * Get idJornadaLaboral
	 *
	 * @return null|Integer
	 */
	public function getIdJornadaLaboral(){
		return $this->idJornadaLaboral;
	}

	/**
	 * Set identificador
	 *
	 * Numero de identificación del funcionario
	 *
	 * @parámetro String $identificador
	 * @return Identificador
	 */
	public function setIdentificador($identificador){
		$this->identificador = (string) $identificador;
		return $this;
	}

	/**
	 * Get identificador
	 *
	 * @return null|String
	 */
	public function getIdentificador(){
		return $this->identificador;
	}

	/**
	 * Set grupo
	 *
	 * Tipo de grupo, opciones: Grupo 1, Grupo 2
	 *
	 * @parámetro String $grupo
	 * @return Grupo
	 */
	public function setGrupo($grupo){
		$this->grupo = (string) $grupo;
		return $this;
	}

	/**
	 * Get grupo
	 *
	 * @return null|String
	 */
	public function getGrupo(){
		return $this->grupo;
	}

	/**
	 * Set horario
	 *
	 * Horario en el que desempeña su jornada laboral cada funcionario
	 *
	 * @parámetro String $horario
	 * @return Horario
	 */
	public function setHorario($horario){
		$this->horario = (string) $horario;
		return $this;
	}

	/**
	 * Get horario
	 *
	 * @return null|String
	 */
	public function getHorario(){
		return $this->horario;
	}

	/**
	 * Set mes
	 *
	 * mes en el cual se estable la jornada laboral
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
	 * Set estadoRegistro
	 *
	 * Estado del registro
	 *
	 * @parámetro String $estadoRegistro
	 * @return EstadoRegistro
	 */
	public function setEstadoRegistro($estadoRegistro){
		$this->estadoRegistro = (string) $estadoRegistro;
		return $this;
	}

	/**
	 * Get estadoRegistro
	 *
	 * @return null|String
	 */
	public function getEstadoRegistro(){
		return $this->estadoRegistro;
	}

	/**
	 * Set fechaRegistro
	 *
	 * Fecha de ingreso del registro
	 *
	 * @parámetro Date $fechaRegistro
	 * @return FechaRegistro
	 */
	public function setFechaRegistro($fechaRegistro){
		$this->fechaRegistro = (string) $fechaRegistro;
		return $this;
	}

	/**
	 * Get fechaRegistro
	 *
	 * @return null|Date
	 */
	public function getFechaRegistro(){
		return $this->fechaRegistro;
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
	 * @return JornadaLaboralModelo
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
