<?php
/**
 * Modelo OperadoresModelo
 *
 * Este archivo se complementa con el archivo OperadoresLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2019-06-06
 * @uses OperadoresModelo
 * @package RegistroOperador
 * @subpackage Modelos
 */
namespace Agrodb\RegistroOperador\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class OperadoresModelo extends ModeloBase{

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador único de la tabla, cedula o RUC del operador.
	 */
	protected $identificador;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Razón social del operador
	 */
	protected $razonSocial;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombres del representante técnico del operador
	 */
	protected $nombreRepresentante;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Apellidos del representante técnico
	 */
	protected $apellidoRepresentante;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombres del técnico del oeprador
	 */
	protected $nombreTecnico;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Apellidos del técnico del oeprador
	 */
	protected $apellidoTecnico;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Dirección del domicilio tributario.
	 */
	protected $direccion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Telefono fijo de cuminicación con el operador
	 */
	protected $telefonoUno;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Telefono opcional fijo de cuminicación con el operador
	 */
	protected $telefonoDos;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Telefono celular de cuminicación con el operador
	 */
	protected $celularUno;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Celular opcional fijo de cuminicación con el operador
	 */
	protected $celularDos;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Correo electronico del operador
	 */
	protected $correo;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Clave encriptado ingresada por el operador al momento del registro
	 */
	protected $clave;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Parroquia del domicilio tributario
	 */
	protected $parroquia;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Columna de referencia a identificador dado en el sistema Saniflores
	 */
	protected $idSaniflores;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Provincia del domicilio tributario
	 */
	protected $provincia;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Cantón del domicilio tributario
	 */
	protected $canton;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Tipo de identificación seleccionado por el operador
	 */
	protected $tipoOperador;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Código gs1
	 */
	protected $gs1;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Número de registro de orquideas
	 */
	protected $registroOrquideas;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Número de registro de madera
	 */
	protected $registroMadera;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Fax del operador
	 */
	protected $fax;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que contiene resultado de validación co nel registro civil o SRI
	 */
	protected $validacionSri;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de registro del operador
	 */
	protected $fechaOperador;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Columna que identifica a un tipo de operador como individual o grupal
	 */
	protected $tipoActividad;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_operadores";

	/**
	 * Nombre de la tabla: operadores
	 */
	private $tabla = "operadores";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "identificador";

	/**
	 * Secuencia
	 */
	private $secuencial = '"Operadores_"identificador_seq';

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
			throw new \Exception('Clase Modelo: OperadoresModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: OperadoresModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_operadores
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set identificador
	 *
	 * Identificador único de la tabla, cedula o RUC del operador.
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
	 * Set razonSocial
	 *
	 * Razón social del operador
	 *
	 * @parámetro String $razonSocial
	 * @return RazonSocial
	 */
	public function setRazonSocial($razonSocial){
		$this->razonSocial = (string) $razonSocial;
		return $this;
	}

	/**
	 * Get razonSocial
	 *
	 * @return null|String
	 */
	public function getRazonSocial(){
		return $this->razonSocial;
	}

	/**
	 * Set nombreRepresentante
	 *
	 * Nombres del representante técnico del operador
	 *
	 * @parámetro String $nombreRepresentante
	 * @return NombreRepresentante
	 */
	public function setNombreRepresentante($nombreRepresentante){
		$this->nombreRepresentante = (string) $nombreRepresentante;
		return $this;
	}

	/**
	 * Get nombreRepresentante
	 *
	 * @return null|String
	 */
	public function getNombreRepresentante(){
		return $this->nombreRepresentante;
	}

	/**
	 * Set apellidoRepresentante
	 *
	 * Apellidos del representante técnico
	 *
	 * @parámetro String $apellidoRepresentante
	 * @return ApellidoRepresentante
	 */
	public function setApellidoRepresentante($apellidoRepresentante){
		$this->apellidoRepresentante = (string) $apellidoRepresentante;
		return $this;
	}

	/**
	 * Get apellidoRepresentante
	 *
	 * @return null|String
	 */
	public function getApellidoRepresentante(){
		return $this->apellidoRepresentante;
	}

	/**
	 * Set nombreTecnico
	 *
	 * Nombres del técnico del oeprador
	 *
	 * @parámetro String $nombreTecnico
	 * @return NombreTecnico
	 */
	public function setNombreTecnico($nombreTecnico){
		$this->nombreTecnico = (string) $nombreTecnico;
		return $this;
	}

	/**
	 * Get nombreTecnico
	 *
	 * @return null|String
	 */
	public function getNombreTecnico(){
		return $this->nombreTecnico;
	}

	/**
	 * Set apellidoTecnico
	 *
	 * Apellidos del técnico del oeprador
	 *
	 * @parámetro String $apellidoTecnico
	 * @return ApellidoTecnico
	 */
	public function setApellidoTecnico($apellidoTecnico){
		$this->apellidoTecnico = (string) $apellidoTecnico;
		return $this;
	}

	/**
	 * Get apellidoTecnico
	 *
	 * @return null|String
	 */
	public function getApellidoTecnico(){
		return $this->apellidoTecnico;
	}

	/**
	 * Set direccion
	 *
	 * Dirección del domicilio tributario.
	 *
	 * @parámetro String $direccion
	 * @return Direccion
	 */
	public function setDireccion($direccion){
		$this->direccion = (string) $direccion;
		return $this;
	}

	/**
	 * Get direccion
	 *
	 * @return null|String
	 */
	public function getDireccion(){
		return $this->direccion;
	}

	/**
	 * Set telefonoUno
	 *
	 * Telefono fijo de cuminicación con el operador
	 *
	 * @parámetro String $telefonoUno
	 * @return TelefonoUno
	 */
	public function setTelefonoUno($telefonoUno){
		$this->telefonoUno = (string) $telefonoUno;
		return $this;
	}

	/**
	 * Get telefonoUno
	 *
	 * @return null|String
	 */
	public function getTelefonoUno(){
		return $this->telefonoUno;
	}

	/**
	 * Set telefonoDos
	 *
	 * Telefono opcional fijo de cuminicación con el operador
	 *
	 * @parámetro String $telefonoDos
	 * @return TelefonoDos
	 */
	public function setTelefonoDos($telefonoDos){
		$this->telefonoDos = (string) $telefonoDos;
		return $this;
	}

	/**
	 * Get telefonoDos
	 *
	 * @return null|String
	 */
	public function getTelefonoDos(){
		return $this->telefonoDos;
	}

	/**
	 * Set celularUno
	 *
	 * Telefono celular de cuminicación con el operador
	 *
	 * @parámetro String $celularUno
	 * @return CelularUno
	 */
	public function setCelularUno($celularUno){
		$this->celularUno = (string) $celularUno;
		return $this;
	}

	/**
	 * Get celularUno
	 *
	 * @return null|String
	 */
	public function getCelularUno(){
		return $this->celularUno;
	}

	/**
	 * Set celularDos
	 *
	 * Celular opcional fijo de cuminicación con el operador
	 *
	 * @parámetro String $celularDos
	 * @return CelularDos
	 */
	public function setCelularDos($celularDos){
		$this->celularDos = (string) $celularDos;
		return $this;
	}

	/**
	 * Get celularDos
	 *
	 * @return null|String
	 */
	public function getCelularDos(){
		return $this->celularDos;
	}

	/**
	 * Set correo
	 *
	 * Correo electronico del operador
	 *
	 * @parámetro String $correo
	 * @return Correo
	 */
	public function setCorreo($correo){
		$this->correo = (string) $correo;
		return $this;
	}

	/**
	 * Get correo
	 *
	 * @return null|String
	 */
	public function getCorreo(){
		return $this->correo;
	}

	/**
	 * Set clave
	 *
	 * Clave encriptado ingresada por el operador al momento del registro
	 *
	 * @parámetro String $clave
	 * @return Clave
	 */
	public function setClave($clave){
		$this->clave = (string) $clave;
		return $this;
	}

	/**
	 * Get clave
	 *
	 * @return null|String
	 */
	public function getClave(){
		return $this->clave;
	}

	/**
	 * Set parroquia
	 *
	 * Parroquia del domicilio tributario
	 *
	 * @parámetro String $parroquia
	 * @return Parroquia
	 */
	public function setParroquia($parroquia){
		$this->parroquia = (string) $parroquia;
		return $this;
	}

	/**
	 * Get parroquia
	 *
	 * @return null|String
	 */
	public function getParroquia(){
		return $this->parroquia;
	}

	/**
	 * Set idSaniflores
	 *
	 * Columna de referencia a identificador dado en el sistema Saniflores
	 *
	 * @parámetro Integer $idSaniflores
	 * @return IdSaniflores
	 */
	public function setIdSaniflores($idSaniflores){
		$this->idSaniflores = (integer) $idSaniflores;
		return $this;
	}

	/**
	 * Get idSaniflores
	 *
	 * @return null|Integer
	 */
	public function getIdSaniflores(){
		return $this->idSaniflores;
	}

	/**
	 * Set provincia
	 *
	 * Provincia del domicilio tributario
	 *
	 * @parámetro String $provincia
	 * @return Provincia
	 */
	public function setProvincia($provincia){
		$this->provincia = (string) $provincia;
		return $this;
	}

	/**
	 * Get provincia
	 *
	 * @return null|String
	 */
	public function getProvincia(){
		return $this->provincia;
	}

	/**
	 * Set canton
	 *
	 * Cantón del domicilio tributario
	 *
	 * @parámetro String $canton
	 * @return Canton
	 */
	public function setCanton($canton){
		$this->canton = (string) $canton;
		return $this;
	}

	/**
	 * Get canton
	 *
	 * @return null|String
	 */
	public function getCanton(){
		return $this->canton;
	}

	/**
	 * Set tipoOperador
	 *
	 * Tipo de identificación seleccionado por el operador
	 *
	 * @parámetro String $tipoOperador
	 * @return TipoOperador
	 */
	public function setTipoOperador($tipoOperador){
		$this->tipoOperador = (string) $tipoOperador;
		return $this;
	}

	/**
	 * Get tipoOperador
	 *
	 * @return null|String
	 */
	public function getTipoOperador(){
		return $this->tipoOperador;
	}

	/**
	 * Set gs1
	 *
	 * Código gs1
	 *
	 * @parámetro String $gs1
	 * @return Gs1
	 */
	public function setGs1($gs1){
		$this->gs1 = (string) $gs1;
		return $this;
	}

	/**
	 * Get gs1
	 *
	 * @return null|String
	 */
	public function getGs1(){
		return $this->gs1;
	}

	/**
	 * Set registroOrquideas
	 *
	 * Número de registro de orquideas
	 *
	 * @parámetro String $registroOrquideas
	 * @return RegistroOrquideas
	 */
	public function setRegistroOrquideas($registroOrquideas){
		$this->registroOrquideas = (string) $registroOrquideas;
		return $this;
	}

	/**
	 * Get registroOrquideas
	 *
	 * @return null|String
	 */
	public function getRegistroOrquideas(){
		return $this->registroOrquideas;
	}

	/**
	 * Set registroMadera
	 *
	 * Número de registro de madera
	 *
	 * @parámetro String $registroMadera
	 * @return RegistroMadera
	 */
	public function setRegistroMadera($registroMadera){
		$this->registroMadera = (string) $registroMadera;
		return $this;
	}

	/**
	 * Get registroMadera
	 *
	 * @return null|String
	 */
	public function getRegistroMadera(){
		return $this->registroMadera;
	}

	/**
	 * Set fax
	 *
	 * Fax del operador
	 *
	 * @parámetro String $fax
	 * @return Fax
	 */
	public function setFax($fax){
		$this->fax = (string) $fax;
		return $this;
	}

	/**
	 * Get fax
	 *
	 * @return null|String
	 */
	public function getFax(){
		return $this->fax;
	}

	/**
	 * Set validacionSri
	 *
	 * Campo que contiene resultado de validación co nel registro civil o SRI
	 *
	 * @parámetro String $validacionSri
	 * @return ValidacionSri
	 */
	public function setValidacionSri($validacionSri){
		$this->validacionSri = (string) $validacionSri;
		return $this;
	}

	/**
	 * Get validacionSri
	 *
	 * @return null|String
	 */
	public function getValidacionSri(){
		return $this->validacionSri;
	}

	/**
	 * Set fechaOperador
	 *
	 * Fecha de registro del operador
	 *
	 * @parámetro Date $fechaOperador
	 * @return FechaOperador
	 */
	public function setFechaOperador($fechaOperador){
		$this->fechaOperador = (string) $fechaOperador;
		return $this;
	}

	/**
	 * Get fechaOperador
	 *
	 * @return null|Date
	 */
	public function getFechaOperador(){
		return $this->fechaOperador;
	}

	/**
	 * Set tipoActividad
	 *
	 * Columna que identifica a un tipo de operador como individual o grupal
	 *
	 * @parámetro String $tipoActividad
	 * @return TipoActividad
	 */
	public function setTipoActividad($tipoActividad){
		$this->tipoActividad = (string) $tipoActividad;
		return $this;
	}

	/**
	 * Get tipoActividad
	 *
	 * @return null|String
	 */
	public function getTipoActividad(){
		return $this->tipoActividad;
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
	 * @return OperadoresModelo
	 */
	public function buscar($id){
		return $this->setOptions(parent::buscar($this->clavePrimaria . " = " . "'$id'"));
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
