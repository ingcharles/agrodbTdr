<?php
/**
 * Modelo ProductosAreasOperacionModelo
 *
 * Este archivo se complementa con el archivo ProductosAreasOperacionLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2020-09-18
 * @uses ProductosAreasOperacionModelo
 * @package AdministrarOperacionesGuia
 * @subpackage Modelos
 */
namespace Agrodb\AdministrarOperacionesGuia\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ProductosAreasOperacionModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idProductoAreaOperacion;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idArea;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idOperacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $estado;

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
	protected $rutaArchivo;

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
	 * Nombre de la tabla: productos_areas_operacion
	 */
	private $tabla = "productos_areas_operacion";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_producto_area_operacion";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_operadores"."productos_areas_operacion_id_producto_area_operacion_seq';

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
			throw new \Exception('Clase Modelo: ProductosAreasOperacionModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: ProductosAreasOperacionModelo. Propiedad especificada invalida: get' . $name);
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
	 * Set idProductoAreaOperacion
	 *
	 *
	 *
	 * @parámetro Integer $idProductoAreaOperacion
	 * @return IdProductoAreaOperacion
	 */
	public function setIdProductoAreaOperacion($idProductoAreaOperacion){
		$this->idProductoAreaOperacion = (integer) $idProductoAreaOperacion;
		return $this;
	}

	/**
	 * Get idProductoAreaOperacion
	 *
	 * @return null|Integer
	 */
	public function getIdProductoAreaOperacion(){
		return $this->idProductoAreaOperacion;
	}

	/**
	 * Set idArea
	 *
	 *
	 *
	 * @parámetro Integer $idArea
	 * @return IdArea
	 */
	public function setIdArea($idArea){
		$this->idArea = (integer) $idArea;
		return $this;
	}

	/**
	 * Get idArea
	 *
	 * @return null|Integer
	 */
	public function getIdArea(){
		return $this->idArea;
	}

	/**
	 * Set idOperacion
	 *
	 *
	 *
	 * @parámetro Integer $idOperacion
	 * @return IdOperacion
	 */
	public function setIdOperacion($idOperacion){
		$this->idOperacion = (integer) $idOperacion;
		return $this;
	}

	/**
	 * Get idOperacion
	 *
	 * @return null|Integer
	 */
	public function getIdOperacion(){
		return $this->idOperacion;
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
	 * Set rutaArchivo
	 *
	 *
	 *
	 * @parámetro String $rutaArchivo
	 * @return RutaArchivo
	 */
	public function setRutaArchivo($rutaArchivo){
		$this->rutaArchivo = (string) $rutaArchivo;
		return $this;
	}

	/**
	 * Get rutaArchivo
	 *
	 * @return null|String
	 */
	public function getRutaArchivo(){
		return $this->rutaArchivo;
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
	 * @return ProductosAreasOperacionModelo
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
