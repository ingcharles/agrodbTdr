<?php
/**
 * Modelo FormatoCertificadoModelo
 *
 * Este archivo se complementa con el archivo FormatoCertificadoLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses FormatoCertificadoModelo
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class FormatoCertificadoModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave primaria de la tabla
	 */
	protected $idFormatoCertificado;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Ciudad del certificado
	 */
	protected $ciudad;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Tipo de certificado
	 */
	protected $tipo;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Texto del cuerpo
	 */
	protected $textoCuerpo;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Texto de cierrre
	 */
	protected $textoCierre;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Estado del registro
	 */
	protected $estado;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de creación del registro
	 */
	protected $fechaCreacion;

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
	 * Nombre de la tabla: formato_certificado
	 */
	private $tabla = "formato_certificado";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_formato_certificado";

	/**
	 * Secuencia
	 */
	private $secuencial = '"FormatoCertificado_"id_formato_certificado_seq';

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
			throw new \Exception('Clase Modelo: FormatoCertificadoModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: FormatoCertificadoModelo. Propiedad especificada invalida: get' . $name);
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
	 * Set idFormatoCertificado
	 *
	 * Llave primaria de la tabla
	 *
	 * @parámetro Integer $idFormatoCertificado
	 * @return IdFormatoCertificado
	 */
	public function setIdFormatoCertificado($idFormatoCertificado){
		$this->idFormatoCertificado = (integer) $idFormatoCertificado;
		return $this;
	}

	/**
	 * Get idFormatoCertificado
	 *
	 * @return null|Integer
	 */
	public function getIdFormatoCertificado(){
		return $this->idFormatoCertificado;
	}

	/**
	 * Set ciudad
	 *
	 * Ciudad del certificado
	 *
	 * @parámetro String $ciudad
	 * @return Ciudad
	 */
	public function setCiudad($ciudad){
		$this->ciudad = (string) $ciudad;
		return $this;
	}

	/**
	 * Get ciudad
	 *
	 * @return null|String
	 */
	public function getCiudad(){
		return $this->ciudad;
	}

	/**
	 * Set tipo
	 *
	 * Tipo de certificado
	 *
	 * @parámetro String $tipo
	 * @return Tipo
	 */
	public function setTipo($tipo){
		$this->tipo = (string) $tipo;
		return $this;
	}

	/**
	 * Get tipo
	 *
	 * @return null|String
	 */
	public function getTipo(){
		return $this->tipo;
	}

	/**
	 * Set textoCuerpo
	 *
	 * Texto del cuerpo
	 *
	 * @parámetro String $textoCuerpo
	 * @return TextoCuerpo
	 */
	public function setTextoCuerpo($textoCuerpo){
		$this->textoCuerpo = (string) $textoCuerpo;
		return $this;
	}

	/**
	 * Get textoCuerpo
	 *
	 * @return null|String
	 */
	public function getTextoCuerpo(){
		return $this->textoCuerpo;
	}

	/**
	 * Set textoCierre
	 *
	 * Texto de cierrre
	 *
	 * @parámetro String $textoCierre
	 * @return TextoCierre
	 */
	public function setTextoCierre($textoCierre){
		$this->textoCierre = (string) $textoCierre;
		return $this;
	}

	/**
	 * Get textoCierre
	 *
	 * @return null|String
	 */
	public function getTextoCierre(){
		return $this->textoCierre;
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
	 * @return FormatoCertificadoModelo
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
