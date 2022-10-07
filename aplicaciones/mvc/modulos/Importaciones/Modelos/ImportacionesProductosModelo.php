<?php
/**
 * Modelo ImportacionesProductosModelo
 *
 * Este archivo se complementa con el archivo ImportacionesProductosLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-07-06
 * @uses ImportacionesProductosModelo
 * @package Importaciones
 * @subpackage Modelos
 */
namespace Agrodb\Importaciones\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ImportacionesProductosModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idImportacionProducto;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idImportacion;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idProducto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $nombreProducto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $unidad;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $peso;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $valorFob;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $valorCif;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $licenciaMagap;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $registroSemillas;

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
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $unidadMedida;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $partidaProductoVue;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $codigoProductoVue;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $presentacionProducto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $unidadPeso;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_importaciones";

	/**
	 * Nombre de la tabla: importaciones_productos
	 */
	private $tabla = "importaciones_productos";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_importacion_producto";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_importaciones"."ImportacionesProductos_id_importacion_producto_seq';

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
			throw new \Exception('Clase Modelo: ImportacionesProductosModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: ImportacionesProductosModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_importaciones
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idImportacionProducto
	 *
	 *
	 *
	 * @parámetro Integer $idImportacionProducto
	 * @return IdImportacionProducto
	 */
	public function setIdImportacionProducto($idImportacionProducto){
		$this->idImportacionProducto = (integer) $idImportacionProducto;
		return $this;
	}

	/**
	 * Get idImportacionProducto
	 *
	 * @return null|Integer
	 */
	public function getIdImportacionProducto(){
		return $this->idImportacionProducto;
	}

	/**
	 * Set idImportacion
	 *
	 *
	 *
	 * @parámetro Integer $idImportacion
	 * @return IdImportacion
	 */
	public function setIdImportacion($idImportacion){
		$this->idImportacion = (integer) $idImportacion;
		return $this;
	}

	/**
	 * Get idImportacion
	 *
	 * @return null|Integer
	 */
	public function getIdImportacion(){
		return $this->idImportacion;
	}

	/**
	 * Set idProducto
	 *
	 *
	 *
	 * @parámetro Integer $idProducto
	 * @return IdProducto
	 */
	public function setIdProducto($idProducto){
		$this->idProducto = (integer) $idProducto;
		return $this;
	}

	/**
	 * Get idProducto
	 *
	 * @return null|Integer
	 */
	public function getIdProducto(){
		return $this->idProducto;
	}

	/**
	 * Set nombreProducto
	 *
	 *
	 *
	 * @parámetro String $nombreProducto
	 * @return NombreProducto
	 */
	public function setNombreProducto($nombreProducto){
		$this->nombreProducto = (string) $nombreProducto;
		return $this;
	}

	/**
	 * Get nombreProducto
	 *
	 * @return null|String
	 */
	public function getNombreProducto(){
		return $this->nombreProducto;
	}

	/**
	 * Set unidad
	 *
	 *
	 *
	 * @parámetro String $unidad
	 * @return Unidad
	 */
	public function setUnidad($unidad){
		$this->unidad = (string) $unidad;
		return $this;
	}

	/**
	 * Get unidad
	 *
	 * @return null|String
	 */
	public function getUnidad(){
		return $this->unidad;
	}

	/**
	 * Set peso
	 *
	 *
	 *
	 * @parámetro String $peso
	 * @return Peso
	 */
	public function setPeso($peso){
		$this->peso = (string) $peso;
		return $this;
	}

	/**
	 * Get peso
	 *
	 * @return null|String
	 */
	public function getPeso(){
		return $this->peso;
	}

	/**
	 * Set valorFob
	 *
	 *
	 *
	 * @parámetro String $valorFob
	 * @return ValorFob
	 */
	public function setValorFob($valorFob){
		$this->valorFob = (string) $valorFob;
		return $this;
	}

	/**
	 * Get valorFob
	 *
	 * @return null|String
	 */
	public function getValorFob(){
		return $this->valorFob;
	}

	/**
	 * Set valorCif
	 *
	 *
	 *
	 * @parámetro String $valorCif
	 * @return ValorCif
	 */
	public function setValorCif($valorCif){
		$this->valorCif = (string) $valorCif;
		return $this;
	}

	/**
	 * Get valorCif
	 *
	 * @return null|String
	 */
	public function getValorCif(){
		return $this->valorCif;
	}

	/**
	 * Set licenciaMagap
	 *
	 *
	 *
	 * @parámetro String $licenciaMagap
	 * @return LicenciaMagap
	 */
	public function setLicenciaMagap($licenciaMagap){
		$this->licenciaMagap = (string) $licenciaMagap;
		return $this;
	}

	/**
	 * Get licenciaMagap
	 *
	 * @return null|String
	 */
	public function getLicenciaMagap(){
		return $this->licenciaMagap;
	}

	/**
	 * Set registroSemillas
	 *
	 *
	 *
	 * @parámetro String $registroSemillas
	 * @return RegistroSemillas
	 */
	public function setRegistroSemillas($registroSemillas){
		$this->registroSemillas = (string) $registroSemillas;
		return $this;
	}

	/**
	 * Get registroSemillas
	 *
	 * @return null|String
	 */
	public function getRegistroSemillas(){
		return $this->registroSemillas;
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
	 * Set unidadMedida
	 *
	 *
	 *
	 * @parámetro String $unidadMedida
	 * @return UnidadMedida
	 */
	public function setUnidadMedida($unidadMedida){
		$this->unidadMedida = (string) $unidadMedida;
		return $this;
	}

	/**
	 * Get unidadMedida
	 *
	 * @return null|String
	 */
	public function getUnidadMedida(){
		return $this->unidadMedida;
	}

	/**
	 * Set partidaProductoVue
	 *
	 *
	 *
	 * @parámetro String $partidaProductoVue
	 * @return PartidaProductoVue
	 */
	public function setPartidaProductoVue($partidaProductoVue){
		$this->partidaProductoVue = (string) $partidaProductoVue;
		return $this;
	}

	/**
	 * Get partidaProductoVue
	 *
	 * @return null|String
	 */
	public function getPartidaProductoVue(){
		return $this->partidaProductoVue;
	}

	/**
	 * Set codigoProductoVue
	 *
	 *
	 *
	 * @parámetro String $codigoProductoVue
	 * @return CodigoProductoVue
	 */
	public function setCodigoProductoVue($codigoProductoVue){
		$this->codigoProductoVue = (string) $codigoProductoVue;
		return $this;
	}

	/**
	 * Get codigoProductoVue
	 *
	 * @return null|String
	 */
	public function getCodigoProductoVue(){
		return $this->codigoProductoVue;
	}

	/**
	 * Set presentacionProducto
	 *
	 *
	 *
	 * @parámetro String $presentacionProducto
	 * @return PresentacionProducto
	 */
	public function setPresentacionProducto($presentacionProducto){
		$this->presentacionProducto = (string) $presentacionProducto;
		return $this;
	}

	/**
	 * Get presentacionProducto
	 *
	 * @return null|String
	 */
	public function getPresentacionProducto(){
		return $this->presentacionProducto;
	}

	/**
	 * Set unidadPeso
	 *
	 *
	 *
	 * @parámetro String $unidadPeso
	 * @return UnidadPeso
	 */
	public function setUnidadPeso($unidadPeso){
		$this->unidadPeso = (string) $unidadPeso;
		return $this;
	}

	/**
	 * Get unidadPeso
	 *
	 * @return null|String
	 */
	public function getUnidadPeso(){
		return $this->unidadPeso;
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
	 * @return ImportacionesProductosModelo
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
