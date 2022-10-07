<?php
 /**
 * Modelo ProductosProveedorModelo
 *
 * Este archivo se complementa con el archivo   ProductosProveedorLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-10-19
 * @uses    ProductosProveedorModelo
 * @package ProveedoresExterior
 * @subpackage Modelos
 */
  namespace Agrodb\ProveedoresExterior\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class ProductosProveedorModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla
		*/
		protected $idProductoProveedor;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla g_proveedores_exterior.proveedor_exportador, llave foranea
		*/
		protected $idProveedorExterior;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla g_catalogos.subtipo_productos, llave foranea, producto de IAV
		*/
		protected $idSubtipoProducto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre del subtipo de producto de IAV
		*/
		protected $nombreSubtipoProducto;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha en la que se registra el producto del proveedor del exportador
		*/
		protected $fechaCreacionProductoProveedor;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_proveedores_exterior";

	/**
	* Nombre de la tabla: productos_proveedor
	* 
	 */
	Private $tabla="productos_proveedor";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_producto_proveedor";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_proveedores_exterior"."productos_proveedor_id_producto_proveedor_seq'; 



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
		throw new \Exception('Clase Modelo: ProductosProveedorModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: ProductosProveedorModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_proveedores_exterior
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idProductoProveedor
	*
	*Identificador unico de la tabla
	*
	* @parámetro Integer $idProductoProveedor
	* @return IdProductoProveedor
	*/
	public function setIdProductoProveedor($idProductoProveedor)
	{
	  $this->idProductoProveedor = (Integer) $idProductoProveedor;
	    return $this;
	}

	/**
	* Get idProductoProveedor
	*
	* @return null|Integer
	*/
	public function getIdProductoProveedor()
	{
		return $this->idProductoProveedor;
	}

	/**
	* Set idProveedorExterior
	*
	*Identificador unico de la tabla g_proveedores_exterior.proveedor_exportador, llave foranea
	*
	* @parámetro Integer $idProveedorExterior
	* @return IdProveedorExterior
	*/
	public function setIdProveedorExterior($idProveedorExterior)
	{
	  $this->idProveedorExterior = (Integer) $idProveedorExterior;
	    return $this;
	}

	/**
	* Get idProveedorExterior
	*
	* @return null|Integer
	*/
	public function getIdProveedorExterior()
	{
		return $this->idProveedorExterior;
	}

	/**
	* Set idSubtipoProducto
	*
	*Identificador unico de la tabla g_catalogos.subtipo_productos, llave foranea, producto de IAV
	*
	* @parámetro Integer $idSubtipoProducto
	* @return IdSubtipoProducto
	*/
	public function setIdSubtipoProducto($idSubtipoProducto)
	{
	  $this->idSubtipoProducto = (Integer) $idSubtipoProducto;
	    return $this;
	}

	/**
	* Get idSubtipoProducto
	*
	* @return null|Integer
	*/
	public function getIdSubtipoProducto()
	{
		return $this->idSubtipoProducto;
	}

	/**
	* Set nombreSubtipoProducto
	*
	*Campo que almacena el nombre del subtipo de producto de IAV
	*
	* @parámetro String $nombreSubtipoProducto
	* @return NombreSubtipoProducto
	*/
	public function setNombreSubtipoProducto($nombreSubtipoProducto)
	{
	  $this->nombreSubtipoProducto = (String) $nombreSubtipoProducto;
	    return $this;
	}

	/**
	* Get nombreSubtipoProducto
	*
	* @return null|String
	*/
	public function getNombreSubtipoProducto()
	{
		return $this->nombreSubtipoProducto;
	}

	/**
	* Set fechaCreacionProductoProveedor
	*
	*Fecha en la que se registra el producto del proveedor del exportador
	*
	* @parámetro Date $fechaCreacionProductoProveedor
	* @return FechaCreacionProductoProveedor
	*/
	public function setFechaCreacionProductoProveedor($fechaCreacionProductoProveedor)
	{
	  $this->fechaCreacionProductoProveedor = (String) $fechaCreacionProductoProveedor;
	    return $this;
	}

	/**
	* Get fechaCreacionProductoProveedor
	*
	* @return null|Date
	*/
	public function getFechaCreacionProductoProveedor()
	{
		return $this->fechaCreacionProductoProveedor;
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
	* @return ProductosProveedorModelo
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
