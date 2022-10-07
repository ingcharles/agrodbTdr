<?php
/**
 * Modelo ImportacionesFertilizantesProductosModelo
 *
 * Este archivo se complementa con el archivo ImportacionesFertilizantesProductosLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2020-02-20
 * @uses ImportacionesFertilizantesProductosModelo
 * @package ImportacionFertilizantes
 * @subpackage Modelos
 */
namespace Agrodb\ImportacionFertilizantes\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ImportacionesFertilizantesProductosModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador único de la tabla
	 */
	protected $idImportacionFertilizanteProducto;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave foranea que hace referencia a la tabla g_importaciones_fertilizantes.importaciones_fertilizantes
	 */
	protected $idImportacionFertilizante;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre comercial del producto fertilizante que se desea importar.
	 */
	protected $nombreComercialProducto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre del producto en el país de origen.
	 */
	protected $nombreProductoOrigen;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Número de registro del producto.
	 */
	protected $numeroRegistro;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Descripción de la composición del producto.
	 */
	protected $composicion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Cantidad y unidad de medida del producto a ser importado.
	 */
	protected $cantidad;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Peso neto del producto.
	 */
	protected $pesoNeto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Partida arancelaria relacionada con el producto que se desea importar.
	 */
	protected $partidaArancelaria;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Estado del registro del producto.
	 */
	protected $estado;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de creación del registro en base de datos
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
	private $esquema = "g_importaciones_fertilizantes";

	/**
	 * Nombre de la tabla: importaciones_fertilizantes_productos
	 */
	private $tabla = "importaciones_fertilizantes_productos";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_importacion_fertilizante_producto";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_importaciones_fertilizantes"."importaciones_fertilizantes_p_id_importacion_fertilizante_p_seq';

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
			throw new \Exception('Clase Modelo: ImportacionesFertilizantesProductosModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: ImportacionesFertilizantesProductosModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_importaciones_fertilizantes
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idImportacionFertilizanteProducto
	 *
	 * Identificador único de la tabla
	 *
	 * @parámetro Integer $idImportacionFertilizanteProducto
	 * @return IdImportacionFertilizanteProducto
	 */
	public function setIdImportacionFertilizanteProducto($idImportacionFertilizanteProducto){
		$this->idImportacionFertilizanteProducto = (integer) $idImportacionFertilizanteProducto;
		return $this;
	}

	/**
	 * Get idImportacionFertilizanteProducto
	 *
	 * @return null|Integer
	 */
	public function getIdImportacionFertilizanteProducto(){
		return $this->idImportacionFertilizanteProducto;
	}

	/**
	 * Set idImportacionFertilizante
	 *
	 * Llave foranea que hace referencia a la tabla g_importaciones_fertilizantes.importaciones_fertilizantes
	 *
	 * @parámetro Integer $idImportacionFertilizante
	 * @return IdImportacionFertilizante
	 */
	public function setIdImportacionFertilizante($idImportacionFertilizante){
		$this->idImportacionFertilizante = (integer) $idImportacionFertilizante;
		return $this;
	}

	/**
	 * Get idImportacionFertilizante
	 *
	 * @return null|Integer
	 */
	public function getIdImportacionFertilizante(){
		return $this->idImportacionFertilizante;
	}

	/**
	 * Set nombreComercialProducto
	 *
	 * Nombre comercial del producto fertilizante que se desea importar.
	 *
	 * @parámetro String $nombreComercialProducto
	 * @return NombreComercialProducto
	 */
	public function setNombreComercialProducto($nombreComercialProducto){
		$this->nombreComercialProducto = (string) $nombreComercialProducto;
		return $this;
	}

	/**
	 * Get nombreComercialProducto
	 *
	 * @return null|String
	 */
	public function getNombreComercialProducto(){
		return $this->nombreComercialProducto;
	}

	/**
	 * Set nombreProductoOrigen
	 *
	 * Nombre del producto en el país de origen.
	 *
	 * @parámetro String $nombreProductoOrigen
	 * @return NombreProductoOrigen
	 */
	public function setNombreProductoOrigen($nombreProductoOrigen){
		$this->nombreProductoOrigen = (string) $nombreProductoOrigen;
		return $this;
	}

	/**
	 * Get nombreProductoOrigen
	 *
	 * @return null|String
	 */
	public function getNombreProductoOrigen(){
		return $this->nombreProductoOrigen;
	}

	/**
	 * Set numeroRegistro
	 *
	 * Número de registro del producto.
	 *
	 * @parámetro String $numeroRegistro
	 * @return NumeroRegistro
	 */
	public function setNumeroRegistro($numeroRegistro){
		$this->numeroRegistro = (string) $numeroRegistro;
		return $this;
	}

	/**
	 * Get numeroRegistro
	 *
	 * @return null|String
	 */
	public function getNumeroRegistro(){
		return $this->numeroRegistro;
	}

	/**
	 * Set composicion
	 *
	 * Descripción de la composición del producto.
	 *
	 * @parámetro String $composicion
	 * @return Composicion
	 */
	public function setComposicion($composicion){
		$this->composicion = (string) $composicion;
		return $this;
	}

	/**
	 * Get composicion
	 *
	 * @return null|String
	 */
	public function getComposicion(){
		return $this->composicion;
	}

	/**
	 * Set cantidad
	 *
	 * Cantidad y unidad de medida del producto a ser importado.
	 *
	 * @parámetro String $cantidad
	 * @return Cantidad
	 */
	public function setCantidad($cantidad){
		$this->cantidad = (string) $cantidad;
		return $this;
	}

	/**
	 * Get cantidad
	 *
	 * @return null|String
	 */
	public function getCantidad(){
		return $this->cantidad;
	}

	/**
	 * Set pesoNeto
	 *
	 * Peso neto del producto.
	 *
	 * @parámetro String $pesoNeto
	 * @return PesoNeto
	 */
	public function setPesoNeto($pesoNeto){
		$this->pesoNeto = (string) $pesoNeto;
		return $this;
	}

	/**
	 * Get pesoNeto
	 *
	 * @return null|String
	 */
	public function getPesoNeto(){
		return $this->pesoNeto;
	}

	/**
	 * Set partidaArancelaria
	 *
	 * Partida arancelaria relacionada con el producto que se desea importar.
	 *
	 * @parámetro String $partidaArancelaria
	 * @return PartidaArancelaria
	 */
	public function setPartidaArancelaria($partidaArancelaria){
		$this->partidaArancelaria = (string) $partidaArancelaria;
		return $this;
	}

	/**
	 * Get partidaArancelaria
	 *
	 * @return null|String
	 */
	public function getPartidaArancelaria(){
		return $this->partidaArancelaria;
	}

	/**
	 * Set estado
	 *
	 * Estado del registro del producto.
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
	 * Fecha de creación del registro en base de datos
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
	 * @return ImportacionesFertilizantesProductosModelo
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
