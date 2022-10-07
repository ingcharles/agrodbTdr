<?php
 /**
 * Modelo DetalleMovilizacionModelo
 *
 * Este archivo se complementa con el archivo   DetalleMovilizacionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-09-02
 * @uses    DetalleMovilizacionModelo
 * @package MovilizacionVegetal
 * @subpackage Modelos
 */
  namespace Agrodb\MovilizacionVegetal\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class DetalleMovilizacionModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del registro
		*/
		protected $idDetalleMovilizacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del registro de movilización
		*/
		protected $idMovilizacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del área de origen del producto a movilizar
		*/
		protected $idAreaOrigen;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * Nombre del área de origen del producto a movilizar
		 */
		protected $areaOrigen;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del área de destino del producto a movilizar
		*/
		protected $idAreaDestino;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * Nombre del área de destino del producto a movilizar
		 */
		protected $areaDestino;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del subtipo de producto
		*/
		protected $idSubtipoProducto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre del subtipo de producto
		*/
		protected $subtipoProducto;
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
		* Nombre del producto a movilizar
		*/
		protected $producto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre de la unidad de medida
		*/
		protected $unidad;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Cantidad del producto a movilizar
		*/
		protected $cantidad;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de modificación del registro por fiscalización
		*/
		protected $fechaModificacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del registro de fiscalización
		*/
		protected $idFiscalizacion;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_movilizacion_vegetal";

	/**
	* Nombre de la tabla: detalle_movilizacion
	* 
	 */
	Private $tabla="detalle_movilizacion";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_detalle_movilizacion";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_movilizacion_vegetal"."detalle_movilizacion_id_detalle_movilizacion_seq'; 



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
		throw new \Exception('Clase Modelo: DetalleMovilizacionModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: DetalleMovilizacionModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_movilizacion_vegetal
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idDetalleMovilizacion
	*
	*Identificador del registro
	*
	* @parámetro Integer $idDetalleMovilizacion
	* @return IdDetalleMovilizacion
	*/
	public function setIdDetalleMovilizacion($idDetalleMovilizacion)
	{
	  $this->idDetalleMovilizacion = (Integer) $idDetalleMovilizacion;
	    return $this;
	}

	/**
	* Get idDetalleMovilizacion
	*
	* @return null|Integer
	*/
	public function getIdDetalleMovilizacion()
	{
		return $this->idDetalleMovilizacion;
	}

	/**
	* Set idMovilizacion
	*
	*Identificador del registro de movilización
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
	* Set idAreaOrigen
	*
	*Identificador del área de origen del producto a movilizar
	*
	* @parámetro Integer $idAreaOrigen
	* @return IdAreaOrigen
	*/
	public function setIdAreaOrigen($idAreaOrigen)
	{
	  $this->idAreaOrigen = (Integer) $idAreaOrigen;
	    return $this;
	}

	/**
	* Get idAreaOrigen
	*
	* @return null|Integer
	*/
	public function getIdAreaOrigen()
	{
		return $this->idAreaOrigen;
	}

	/**
	 * Set areaOrigen
	 *
	 *Nombre del área de origen del producto a movilizar
	 *
	 * @parámetro String $areaOrigen
	 * @return AreaOrigen
	 */
	public function setAreaOrigen($areaOrigen)
	{
	    $this->areaOrigen = (String) $areaOrigen;
	    return $this;
	}
	
	/**
	 * Get areaOrigen
	 *
	 * @return null|String
	 */
	public function getAreaOrigen()
	{
	    return $this->areaOrigen;
	}

	/**
	* Set idAreaDestino
	*
	*Identificador del área de destino del producto a movilizar
	*
	* @parámetro Integer $idAreaDestino
	* @return IdAreaDestino
	*/
	public function setIdAreaDestino($idAreaDestino)
	{
	  $this->idAreaDestino = (Integer) $idAreaDestino;
	    return $this;
	}

	/**
	* Get idAreaDestino
	*
	* @return null|Integer
	*/
	public function getIdAreaDestino()
	{
		return $this->idAreaDestino;
	}
	
	/**
	 * Set areaDestino
	 *
	 *Nombre del área de destino del producto a movilizar
	 *
	 * @parámetro String $areaDestino
	 * @return AreaDestino
	 */
	public function setAreaDestino($areaDestino)
	{
	    $this->areaDestino = (String) $areaDestino;
	    return $this;
	}
	
	/**
	 * Get areaOrigen
	 *
	 * @return null|String
	 */
	public function getAreaDestino()
	{
	    return $this->areaDestino;
	}
	
	/**
	* Set idSubtipoProducto
	*
	*Identificador del subtipo de producto
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
	* Set subtipoProducto
	*
	*Nombre del subtipo de producto
	*
	* @parámetro String $subtipoProducto
	* @return SubtipoProducto
	*/
	public function setSubtipoProducto($subtipoProducto)
	{
	  $this->subtipoProducto = (String) $subtipoProducto;
	    return $this;
	}

	/**
	* Get subtipoProducto
	*
	* @return null|String
	*/
	public function getSubtipoProducto()
	{
		return $this->subtipoProducto;
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
	* Set producto
	*
	*Nombre del producto a movilizar
	*
	* @parámetro String $producto
	* @return Producto
	*/
	public function setProducto($producto)
	{
	  $this->producto = (String) $producto;
	    return $this;
	}

	/**
	* Get producto
	*
	* @return null|String
	*/
	public function getProducto()
	{
		return $this->producto;
	}

	/**
	* Set unidad
	*
	*Nombre de la unidad de medida
	*
	* @parámetro String $unidad
	* @return Unidad
	*/
	public function setUnidad($unidad)
	{
	  $this->unidad = (String) $unidad;
	    return $this;
	}

	/**
	* Get unidad
	*
	* @return null|String
	*/
	public function getUnidad()
	{
		return $this->unidad;
	}

	/**
	* Set cantidad
	*
	*Cantidad del producto a movilizar
	*
	* @parámetro Integer $cantidad
	* @return Cantidad
	*/
	public function setCantidad($cantidad)
	{
	  $this->cantidad = (Integer) $cantidad;
	    return $this;
	}

	/**
	* Get cantidad
	*
	* @return null|Integer
	*/
	public function getCantidad()
	{
		return $this->cantidad;
	}

	/**
	* Set fechaModificacion
	*
	*Fecha de modificación del registro por fiscalización
	*
	* @parámetro Date $fechaModificacion
	* @return FechaModificacion
	*/
	public function setFechaModificacion($fechaModificacion)
	{
	  $this->fechaModificacion = (String) $fechaModificacion;
	    return $this;
	}

	/**
	* Get fechaModificacion
	*
	* @return null|Date
	*/
	public function getFechaModificacion()
	{
		return $this->fechaModificacion;
	}

	/**
	* Set idFiscalizacion
	*
	*Identificador del registro de fiscalización
	*
	* @parámetro Integer $idFiscalizacion
	* @return IdFiscalizacion
	*/
	public function setIdFiscalizacion($idFiscalizacion)
	{
	  $this->idFiscalizacion = (Integer) $idFiscalizacion;
	    return $this;
	}

	/**
	* Get idFiscalizacion
	*
	* @return null|Integer
	*/
	public function getIdFiscalizacion()
	{
		return $this->idFiscalizacion;
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
	* @return DetalleMovilizacionModelo
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
