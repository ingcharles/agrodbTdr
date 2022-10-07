<?php
/**
 * Modelo LocalizacionModelo
 *
 * Este archivo se complementa con el archivo LocalizacionLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-09-03
 * @uses LocalizacionModelo
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class LocalizacionModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idLocalizacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $codigo;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $nombre;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idLocalizacionPadre;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $categoria;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $latitud;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $longitud;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $zona;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $codigoVue;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $otros;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $nombreIngles;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $geograficoMfin;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_catalogos";

	/**
	 * Nombre de la tabla: localizacion
	 */
	private $tabla = "localizacion";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_localizacion";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_catalogos"."Localizacion_id_localizacion_seq';

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
			throw new \Exception('Clase Modelo: LocalizacionModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: LocalizacionModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_catalogos
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idLocalizacion
	 *
	 *
	 *
	 * @parámetro Integer $idLocalizacion
	 * @return IdLocalizacion
	 */
	public function setIdLocalizacion($idLocalizacion){
		$this->idLocalizacion = (integer) $idLocalizacion;
		return $this;
	}

	/**
	 * Get idLocalizacion
	 *
	 * @return null|Integer
	 */
	public function getIdLocalizacion(){
		return $this->idLocalizacion;
	}

	/**
	 * Set codigo
	 *
	 *
	 *
	 * @parámetro String $codigo
	 * @return Codigo
	 */
	public function setCodigo($codigo){
		$this->codigo = (string) $codigo;
		return $this;
	}

	/**
	 * Get codigo
	 *
	 * @return null|String
	 */
	public function getCodigo(){
		return $this->codigo;
	}

	/**
	 * Set nombre
	 *
	 *
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
	 * Set idLocalizacionPadre
	 *
	 *
	 *
	 * @parámetro Integer $idLocalizacionPadre
	 * @return IdLocalizacionPadre
	 */
	public function setIdLocalizacionPadre($idLocalizacionPadre){
		$this->idLocalizacionPadre = (integer) $idLocalizacionPadre;
		return $this;
	}

	/**
	 * Get idLocalizacionPadre
	 *
	 * @return null|Integer
	 */
	public function getIdLocalizacionPadre(){
		return $this->idLocalizacionPadre;
	}

	/**
	 * Set categoria
	 *
	 *
	 *
	 * @parámetro Integer $categoria
	 * @return Categoria
	 */
	public function setCategoria($categoria){
		$this->categoria = (integer) $categoria;
		return $this;
	}

	/**
	 * Get categoria
	 *
	 * @return null|Integer
	 */
	public function getCategoria(){
		return $this->categoria;
	}

	/**
	 * Set latitud
	 *
	 *
	 *
	 * @parámetro String $latitud
	 * @return Latitud
	 */
	public function setLatitud($latitud){
		$this->latitud = (string) $latitud;
		return $this;
	}

	/**
	 * Get latitud
	 *
	 * @return null|String
	 */
	public function getLatitud(){
		return $this->latitud;
	}

	/**
	 * Set longitud
	 *
	 *
	 *
	 * @parámetro String $longitud
	 * @return Longitud
	 */
	public function setLongitud($longitud){
		$this->longitud = (string) $longitud;
		return $this;
	}

	/**
	 * Get longitud
	 *
	 * @return null|String
	 */
	public function getLongitud(){
		return $this->longitud;
	}

	/**
	 * Set zona
	 *
	 *
	 *
	 * @parámetro String $zona
	 * @return Zona
	 */
	public function setZona($zona){
		$this->zona = (string) $zona;
		return $this;
	}

	/**
	 * Get zona
	 *
	 * @return null|String
	 */
	public function getZona(){
		return $this->zona;
	}

	/**
	 * Set codigoVue
	 *
	 *
	 *
	 * @parámetro String $codigoVue
	 * @return CodigoVue
	 */
	public function setCodigoVue($codigoVue){
		$this->codigoVue = (string) $codigoVue;
		return $this;
	}

	/**
	 * Get codigoVue
	 *
	 * @return null|String
	 */
	public function getCodigoVue(){
		return $this->codigoVue;
	}

	/**
	 * Set otros
	 *
	 *
	 *
	 * @parámetro String $otros
	 * @return Otros
	 */
	public function setOtros($otros){
		$this->otros = (string) $otros;
		return $this;
	}

	/**
	 * Get otros
	 *
	 * @return null|String
	 */
	public function getOtros(){
		return $this->otros;
	}

	/**
	 * Set nombreIngles
	 *
	 *
	 *
	 * @parámetro String $nombreIngles
	 * @return NombreIngles
	 */
	public function setNombreIngles($nombreIngles){
		$this->nombreIngles = (string) $nombreIngles;
		return $this;
	}

	/**
	 * Get nombreIngles
	 *
	 * @return null|String
	 */
	public function getNombreIngles(){
		return $this->nombreIngles;
	}

	/**
	 * Set geograficoMfin
	 *
	 *
	 *
	 * @parámetro String $geograficoMfin
	 * @return GeograficoMfin
	 */
	public function setGeograficoMfin($geograficoMfin){
		$this->geograficoMfin = (string) $geograficoMfin;
		return $this;
	}

	/**
	 * Get geograficoMfin
	 *
	 * @return null|String
	 */
	public function getGeograficoMfin(){
		return $this->geograficoMfin;
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
	 * @return LocalizacionModelo
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
		return parent::buscarLista($where, $order);
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
