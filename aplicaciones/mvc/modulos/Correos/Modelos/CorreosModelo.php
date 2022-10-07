<?php
/**
 * Modelo CorreosModelo
 *
 * Este archivo se complementa con el archivo CorreosLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-07-22
 * @uses CorreosModelo
 * @package Correos
 * @subpackage Modelos
 */
namespace Agrodb\Correos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class CorreosModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idCorreo;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $asunto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $cuerpo;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $estado;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $fechaEnvio;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $codigoModulo;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $tablaModulo;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idSolicitudTabla;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_correos";

	/**
	 * Nombre de la tabla: correos
	 */
	private $tabla = "correos";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_correo";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_correos"."correos_id_correo_seq';

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
			throw new \Exception('Clase Modelo: CorreosModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: CorreosModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_correos
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idCorreo
	 *
	 *
	 *
	 * @parámetro Integer $idCorreo
	 * @return IdCorreo
	 */
	public function setIdCorreo($idCorreo){
		$this->idCorreo = (integer) $idCorreo;
		return $this;
	}

	/**
	 * Get idCorreo
	 *
	 * @return null|Integer
	 */
	public function getIdCorreo(){
		return $this->idCorreo;
	}

	/**
	 * Set asunto
	 *
	 *
	 *
	 * @parámetro String $asunto
	 * @return Asunto
	 */
	public function setAsunto($asunto){
		$this->asunto = (string) $asunto;
		return $this;
	}

	/**
	 * Get asunto
	 *
	 * @return null|String
	 */
	public function getAsunto(){
		return $this->asunto;
	}

	/**
	 * Set cuerpo
	 *
	 *
	 *
	 * @parámetro String $cuerpo
	 * @return Cuerpo
	 */
	public function setCuerpo($cuerpo){
		$this->cuerpo = (string) $cuerpo;
		return $this;
	}

	/**
	 * Get cuerpo
	 *
	 * @return null|String
	 */
	public function getCuerpo(){
		return $this->cuerpo;
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
	 * Set fechaEnvio
	 *
	 *
	 *
	 * @parámetro Date $fechaEnvio
	 * @return FechaEnvio
	 */
	public function setFechaEnvio($fechaEnvio){
		$this->fechaEnvio = (string) $fechaEnvio;
		return $this;
	}

	/**
	 * Get fechaEnvio
	 *
	 * @return null|Date
	 */
	public function getFechaEnvio(){
		return $this->fechaEnvio;
	}

	/**
	 * Set codigoModulo
	 *
	 *
	 *
	 * @parámetro String $codigoModulo
	 * @return CodigoModulo
	 */
	public function setCodigoModulo($codigoModulo){
		$this->codigoModulo = (string) $codigoModulo;
		return $this;
	}

	/**
	 * Get codigoModulo
	 *
	 * @return null|String
	 */
	public function getCodigoModulo(){
		return $this->codigoModulo;
	}

	/**
	 * Set tablaModulo
	 *
	 *
	 *
	 * @parámetro String $tablaModulo
	 * @return TablaModulo
	 */
	public function setTablaModulo($tablaModulo){
		$this->tablaModulo = (string) $tablaModulo;
		return $this;
	}

	/**
	 * Get tablaModulo
	 *
	 * @return null|String
	 */
	public function getTablaModulo(){
		return $this->tablaModulo;
	}

	/**
	 * Set idSolicitudTabla
	 *
	 *
	 *
	 * @parámetro String $idSolicitudTabla
	 * @return IdSolicitudTabla
	 */
	public function setIdSolicitudTabla($idSolicitudTabla){
		$this->idSolicitudTabla = (string) $idSolicitudTabla;
		return $this;
	}

	/**
	 * Get idSolicitudTabla
	 *
	 * @return null|String
	 */
	public function getIdSolicitudTabla(){
		return $this->idSolicitudTabla;
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
	 * @return CorreosModelo
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
