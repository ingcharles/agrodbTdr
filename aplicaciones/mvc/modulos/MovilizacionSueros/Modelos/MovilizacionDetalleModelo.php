<?php
 /**
 * Modelo MovilizacionDetalleModelo
 *
 * Este archivo se complementa con el archivo   MovilizacionDetalleLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-04-03
 * @uses    MovilizacionDetalleModelo
 * @package MovilizacionSueros
 * @subpackage Modelos
 */
  namespace Agrodb\MovilizacionSueros\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class MovilizacionDetalleModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador único de la tabla
		*/
		protected $idMovilizacionDetalle;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la tabla movilizacion
		*/
		protected $idMovilizacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del producto a movilizar
		*/
		protected $idProducto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre del producto a movilizar
		*/
		protected $nombreProducto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la cantidad de producto a movilizar
		*/
		protected $cantidadProducto;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_movilizacion_suero";

	/**
	* Nombre de la tabla: movilizacion_detalle
	* 
	 */
	Private $tabla="movilizacion_detalle";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_movilizacion_detalle";



	/**
	*Secuencia  
	*/   
		 private $secuencial = 'g_movilizacion_suero"."movilizacion_detalle_id_movilizacion_detalle_seq';



	/**
	* Constructor
	* $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
	* @parámetro  array|null $datos
	* @retorna void
	 */
	public function __construct(array $datos = null)
	{
		if (is_array($datos)) 
		{
			$this->setOptions($datos);
		}
			$features = new \Zend\Db\TableGateway\Feature\SequenceFeature($this->clavePrimaria, $this->secuencial);
			parent::__construct($this->esquema,$this->tabla, $features);
	}

	/**
	* Permitir el acceso a la propiedad
	* 
	* @parámetro  string $name 
	* @parámetro  mixed $value 
	* @retorna void
	*/
	public function __set($name, $value)
	{
		$method = 'set' . $name;
		if (!method_exists($this, $method)) 
	{
		throw new \Exception('Clase Modelo: MovilizacionDetalleModelo. Propiedad especificada invalida: set'.$name);
	}
	$this->$method($value);
	}

	/**
	* Permitir el acceso a la propiedad
	* 
	* @parámetro  string $name 
	* @retorna mixed
	*/
	public function __get($name)
	{
	$method = 'get' . $name;
	if (!method_exists($this, $method))
	{
	  throw new \Exception('Clase Modelo: MovilizacionDetalleModelo. Propiedad especificada invalida: get'.$name);
	}
	return $this->$method();
	}

	/**
	* Llena el modelo con datos
	* 
	* @parámetro  array $datos 
	* @retorna Modelo
	*/
	 public function setOptions(array $datos)
	{
	$methods = get_class_methods($this);
	foreach ($datos as $key => $value) 
	{
	$key_original = $key;
	 if (strpos($key, '_') > 0) {
	 $aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function($string) {
	return ucfirst($string[1]);
	 }, ucwords($key));
	  $key = $aux;
	}
	$method = 'set' . ucfirst($key);
	if (in_array($method, $methods)) 
	{
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
	public function getPrepararDatos()
	 {
	 $claseArray = get_object_vars($this);
	   foreach ($this->campos as $key => $value) {
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
	public function setEsquema($esquema)
	{
	  $this->esquema = $esquema;
	    return $this;
	}

	/**
	* Get g_movilizacion_suero
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idMovilizacionDetalle
	*
	*Identificador único de la tabla
	*
	* @parámetro Integer $idMovilizacionDetalle
	* @return IdMovilizacionDetalle
	*/
	public function setIdMovilizacionDetalle($idMovilizacionDetalle)
	{
	  $this->idMovilizacionDetalle = (Integer) $idMovilizacionDetalle;
	    return $this;
	}

	/**
	* Get idMovilizacionDetalle
	*
	* @return null|Integer
	*/
	public function getIdMovilizacionDetalle()
	{
		return $this->idMovilizacionDetalle;
	}

	/**
	* Set idMovilizacion
	*
	*Identificador de la tabla movilizacion
	*
	* @parámetro Integer $idMovilizacion
	* @return IdMovilizacion
	*/
	public function setIdMovilizacion($idMovilizacion)
	{
	  $this->idMovilizacion = (Integer) $idMovilizacion;
	    return $this;
	}

	/**
	* Get idMovilizacion
	*
	* @return null|Integer
	*/
	public function getIdMovilizacion()
	{
		return $this->idMovilizacion;
	}

	/**
	* Set idProducto
	*
	*Identificador del producto a movilizar
	*
	* @parámetro Integer $idProducto
	* @return IdProducto
	*/
	public function setIdProducto($idProducto)
	{
	  $this->idProducto = (Integer) $idProducto;
	    return $this;
	}

	/**
	* Get idProducto
	*
	* @return null|Integer
	*/
	public function getIdProducto()
	{
		return $this->idProducto;
	}

	/**
	* Set nombreProducto
	*
	*Campo que almacena el nombre del producto a movilizar
	*
	* @parámetro String $nombreProducto
	* @return NombreProducto
	*/
	public function setNombreProducto($nombreProducto)
	{
	  $this->nombreProducto = (String) $nombreProducto;
	    return $this;
	}

	/**
	* Get nombreProducto
	*
	* @return null|String
	*/
	public function getNombreProducto()
	{
		return $this->nombreProducto;
	}

	/**
	* Set cantidadProducto
	*
	*Campo que almacena la cantidad de producto a movilizar
	*
	* @parámetro String $cantidadProducto
	* @return CantidadProducto
	*/
	public function setCantidadProducto($cantidadProducto)
	{
	  $this->cantidadProducto = (String) $cantidadProducto;
	    return $this;
	}

	/**
	* Get cantidadProducto
	*
	* @return null|String
	*/
	public function getCantidadProducto()
	{
		return $this->cantidadProducto;
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		return parent::guardar($datos);
	}

	/**
	* Actualiza un registro actual
	* @param array $datos
	* @param int $id
	* @return int
	*/
	public function actualizar(Array $datos,$id)
	{
		 return parent::actualizar($datos, $this->clavePrimaria . " = " . $id);
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		return parent::borrar($this->clavePrimaria . " = " . $id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return MovilizacionDetalleModelo
	*/
	public function buscar($id)
	{
		return $this->setOptions(parent::buscar($this->clavePrimaria . " = " . $id));
		return $this;
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return parent::buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return parent::buscarLista($where);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function ejecutarConsulta($consulta)
	{
		 return parent::ejecutarConsulta($consulta);
	}

}
