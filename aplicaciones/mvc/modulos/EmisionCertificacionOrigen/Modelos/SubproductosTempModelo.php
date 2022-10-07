<?php
 /**
 * Modelo SubproductosTempModelo
 *
 * Este archivo se complementa con el archivo   SubproductosTempLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    SubproductosTempModelo
 * @package InspeccionMusaceas
 * @subpackage Modelos
 */
  namespace Agrodb\EmisionCertificacionOrigen\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class SubproductosTempModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* LLave primaria de la tabla
		*/
		protected $idSubproductosTemp;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave foránea de la tabla productos
		*/
		protected $idProductosTemp;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Subproducto agregado
		*/
		protected $subproducto;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Cantidad de subproductos agregados
		*/
		protected $cantidad;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $fechaCreacion;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_emision_certificacion_origen";

	/**
	* Nombre de la tabla: subproductos_temp
	* 
	 */
	Private $tabla="subproductos_temp";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_subproductos_temp";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_emision_certificacion_origen"."subproductos_id_subproductos_seq'; 



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
		throw new \Exception('Clase Modelo: SubproductosTempModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: SubproductosTempModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_emision_certificacion_origen
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idSubproductosTemp
	*
	*LLave primaria de la tabla
	*
	* @parámetro Integer $idSubproductosTemp
	* @return IdSubproductosTemp
	*/
	public function setIdSubproductosTemp($idSubproductosTemp)
	{
	  $this->idSubproductosTemp = (Integer) $idSubproductosTemp;
	    return $this;
	}

	/**
	* Get idSubproductosTemp
	*
	* @return null|Integer
	*/
	public function getIdSubproductosTemp()
	{
		return $this->idSubproductosTemp;
	}

	/**
	* Set idProductosTemp
	*
	*Llave foránea de la tabla productos
	*
	* @parámetro Integer $idProductosTemp
	* @return IdProductosTemp
	*/
	public function setIdProductosTemp($idProductosTemp)
	{
	  $this->idProductosTemp = (Integer) $idProductosTemp;
	    return $this;
	}

	/**
	* Get idProductosTemp
	*
	* @return null|Integer
	*/
	public function getIdProductosTemp()
	{
		return $this->idProductosTemp;
	}

	/**
	* Set subproducto
	*
	*Subproducto agregado
	*
	* @parámetro String $subproducto
	* @return Subproducto
	*/
	public function setSubproducto($subproducto)
	{
	  $this->subproducto = (String) $subproducto;
	    return $this;
	}

	/**
	* Get subproducto
	*
	* @return null|String
	*/
	public function getSubproducto()
	{
		return $this->subproducto;
	}

	/**
	* Set cantidad
	*
	*Cantidad de subproductos agregados
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
	* Set fechaCreacion
	*
	*
	*
	* @parámetro Date $fechaCreacion
	* @return FechaCreacion
	*/
	public function setFechaCreacion($fechaCreacion)
	{
	  $this->fechaCreacion = (String) $fechaCreacion;
	    return $this;
	}

	/**
	* Get fechaCreacion
	*
	* @return null|Date
	*/
	public function getFechaCreacion()
	{
		return $this->fechaCreacion;
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
	public function borrarPorParametro($param, $value)
	{
	    return parent::borrar($param . " = " . $value);
	}
	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return SubproductosTempModelo
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
