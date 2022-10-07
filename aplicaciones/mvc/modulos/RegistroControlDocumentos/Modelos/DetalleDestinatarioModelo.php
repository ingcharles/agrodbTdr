<?php
/**
 * Modelo DetalleDestinatarioModelo
 *
 * Este archivo se complementa con el archivo DetalleDestinatarioLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-10-18
 * @uses DetalleDestinatarioModelo
 * @package RegistroControlDocumentos
 * @subpackage Modelos
 */
namespace Agrodb\RegistroControlDocumentos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class DetalleDestinatarioModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      LLave primaria de la tabla
	 */
	protected $idDetalleDestinatario;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave foránea de tabla registro_sgc
	 */
	protected $idRegistroSgc;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Descripción
	 */
	protected $nombre;

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
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador registro
	 */
	protected $identificador;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador del área
	 */
	protected $idArea;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 */
	protected $nombreArea;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 */
	protected $estadoSocializacion;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_registro_control_documentos";

	/**
	 * Nombre de la tabla: detalle_destinatario
	 */
	private $tabla = "detalle_destinatario";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_detalle_destinatario";

	/**
	 * Secuencia
	 */
	private $secuencial = '"DetalleDestinatario_"id_detalle_destinatario_seq';

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
			throw new \Exception('Clase Modelo: DetalleDestinatarioModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: DetalleDestinatarioModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_registro_control_documentos
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idDetalleDestinatario
	 *
	 * LLave primaria de la tabla
	 *
	 * @parámetro Integer $idDetalleDestinatario
	 * @return IdDetalleDestinatario
	 */
	public function setIdDetalleDestinatario($idDetalleDestinatario){
		$this->idDetalleDestinatario = (integer) $idDetalleDestinatario;
		return $this;
	}

	/**
	 * Get idDetalleDestinatario
	 *
	 * @return null|Integer
	 */
	public function getIdDetalleDestinatario(){
		return $this->idDetalleDestinatario;
	}

	/**
	 * Set idRegistroSgc
	 *
	 * Llave foránea de tabla registro_sgc
	 *
	 * @parámetro Integer $idRegistroSgc
	 * @return IdRegistroSgc
	 */
	public function setIdRegistroSgc($idRegistroSgc){
		$this->idRegistroSgc = (integer) $idRegistroSgc;
		return $this;
	}

	/**
	 * Get idRegistroSgc
	 *
	 * @return null|Integer
	 */
	public function getIdRegistroSgc(){
		return $this->idRegistroSgc;
	}

	/**
	 * Set nombre
	 *
	 * Descripción
	 *
	 * @parámetro String $nombre
	 * @return Nombre
	 */
	public function setNombre($nombre){
		$this->nombre = (string) $nombre;
		return $this;
	}

	/**
	 * Get nombre
	 *
	 * @return null|String
	 */
	public function getNombre(){
		return $this->nombre;
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
	 * Set identificador
	 *
	 * Identificador registro
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
	 * Set idArea
	 *
	 * Identificador del área
	 *
	 * @parámetro String $idArea
	 * @return IdArea
	 */
	public function setIdArea($idArea){
		$this->idArea = (string) $idArea;
		return $this;
	}

	/**
	 * Get idArea
	 *
	 * @return null|String
	 */
	public function getIdArea(){
		return $this->idArea;
	}

	/**
	 * Set nombreArea
	 *
	 * @parámetro String $nombreArea
	 */
	public function setNombreArea($nombreArea){
		$this->nombreArea = (string) $nombreArea;
		return $this;
	}

	/**
	 * Get nombreArea
	 *
	 * @return null|String
	 */
	public function getNombreArea(){
		return $this->nombreArea;
	}

	/**
	 * Set estadoSocializacion
	 *
	 * @parámetro String $nombreArea
	 */
	public function setEstadoSocializacion($estadoSocializacion){
		$this->estadoSocializacion = (string) $estadoSocializacion;
		return $this;
	}

	/**
	 * Get nombreArea
	 *
	 * @return null|String
	 */
	public function getEstadoSocializacion(){
		return $this->EstadoSocializacion;
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

	public function borrarPorParametro($param, $value){
		return parent::borrar($param . " = " . $value);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return DetalleDestinatarioModelo
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
