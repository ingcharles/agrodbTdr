<?php
/**
 * Modelo CodigosInocuidadModelo
 *
 * Este archivo se complementa con el archivo CodigosInocuidadLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-07-06
 * @uses CodigosInocuidadModelo
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class CodigosInocuidadModelo extends ModeloBase{

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
	protected $subcodigo;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $presentacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $unidadMedida;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $fechaCreacionCodigosInocuidad;
	
	/**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la unidad de medida cuando no se encuentra en el catálogo
     */
    protected $nombreUnidadMedida;

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
	 * Nombre de la tabla: codigos_inocuidad
	 */
	private $tabla = "codigos_inocuidad";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_producto";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_catalogos"."codigos_inocuidad_id_producto_seq';

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
			throw new \Exception('Clase Modelo: CodigosInocuidadModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: CodigosInocuidadModelo. Propiedad especificada invalida: get' . $name);
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
	 * Set subcodigo
	 *
	 *
	 *
	 * @parámetro String $subcodigo
	 * @return Subcodigo
	 */
	public function setSubcodigo($subcodigo){
		$this->subcodigo = (string) $subcodigo;
		return $this;
	}

	/**
	 * Get subcodigo
	 *
	 * @return null|String
	 */
	public function getSubcodigo(){
		return $this->subcodigo;
	}

	/**
	 * Set presentacion
	 *
	 *
	 *
	 * @parámetro String $presentacion
	 * @return Presentacion
	 */
	public function setPresentacion($presentacion){
		$this->presentacion = (string) $presentacion;
		return $this;
	}

	/**
	 * Get presentacion
	 *
	 * @return null|String
	 */
	public function getPresentacion(){
		return $this->presentacion;
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
	 * Set fechaCreacionCodigosInocuidad
	 *
	 *
	 *
	 * @parámetro Date $fechaCreacionCodigosInocuidad
	 * @return FechaCreacionCodigosInocuidad
	 */
	public function setFechaCreacionCodigosInocuidad($fechaCreacionCodigosInocuidad){
		$this->fechaCreacionCodigosInocuidad = (string) $fechaCreacionCodigosInocuidad;
		return $this;
	}

	/**
	 * Get fechaCreacionCodigosInocuidad
	 *
	 * @return null|Date
	 */
	public function getFechaCreacionCodigosInocuidad(){
		return $this->fechaCreacionCodigosInocuidad;
	}
	
	/**
     * Set nombreUnidadMedida
   
     *
     * Nombre de la unidad de medida cuando no se encuentra en el catálogo
     *
     * @parámetro String $nombreUnidadMedida
     * @return NombreUnidadMedida
     */
    public function setNombreUnidadMedida($nombreUnidadMedida)
    {
        $this->nombreUnidadMedida = (string) $nombreUnidadMedida;
        return $this;
    }

    /**
     * Get nombreUnidadMedida
     *
     * @return null|String
     */
    public function getNombreUnidadMedida()
    {
        return $this->nombreUnidadMedida;
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
	 * @return CodigosInocuidadModelo
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
