<?php
 /**
 * Modelo ProveedoresModelo
 *
 * Este archivo se complementa con el archivo   ProveedoresLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-08-04
 * @uses    ProveedoresModelo
 * @package CertificadoFitosanitario
 * @subpackage Modelos
 */
  namespace Agrodb\RegistroOperador\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class ProveedoresModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idProveedor;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $codigoProveedor;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $identificadorOperador;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $operacionOperador;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idProducto;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idPais;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $nombrePais;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $nombreProducto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $nombreOperacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idVue;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_operadores";

	/**
	* Nombre de la tabla: proveedores
	* 
	 */
	Private $tabla="proveedores";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_proveedor";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_operadores"."Proveedores_id_proveedor_seq'; 



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
		throw new \Exception('Clase Modelo: ProveedoresModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: ProveedoresModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_operadores
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idProveedor
	*
	*
	*
	* @parámetro Integer $idProveedor
	* @return IdProveedor
	*/
	public function setIdProveedor($idProveedor)
	{
	  $this->idProveedor = (Integer) $idProveedor;
	    return $this;
	}

	/**
	* Get idProveedor
	*
	* @return null|Integer
	*/
	public function getIdProveedor()
	{
		return $this->idProveedor;
	}

	/**
	* Set codigoProveedor
	*
	*
	*
	* @parámetro String $codigoProveedor
	* @return CodigoProveedor
	*/
	public function setCodigoProveedor($codigoProveedor)
	{
	  $this->codigoProveedor = (String) $codigoProveedor;
	    return $this;
	}

	/**
	* Get codigoProveedor
	*
	* @return null|String
	*/
	public function getCodigoProveedor()
	{
		return $this->codigoProveedor;
	}

	/**
	* Set identificadorOperador
	*
	*
	*
	* @parámetro String $identificadorOperador
	* @return IdentificadorOperador
	*/
	public function setIdentificadorOperador($identificadorOperador)
	{
	  $this->identificadorOperador = (String) $identificadorOperador;
	    return $this;
	}

	/**
	* Get identificadorOperador
	*
	* @return null|String
	*/
	public function getIdentificadorOperador()
	{
		return $this->identificadorOperador;
	}

	/**
	* Set operacionOperador
	*
	*
	*
	* @parámetro Integer $operacionOperador
	* @return OperacionOperador
	*/
	public function setOperacionOperador($operacionOperador)
	{
	  $this->operacionOperador = (Integer) $operacionOperador;
	    return $this;
	}

	/**
	* Get operacionOperador
	*
	* @return null|Integer
	*/
	public function getOperacionOperador()
	{
		return $this->operacionOperador;
	}

	/**
	* Set idProducto
	*
	*
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
	* Set idPais
	*
	*
	*
	* @parámetro Integer $idPais
	* @return IdPais
	*/
	public function setIdPais($idPais)
	{
	  $this->idPais = (Integer) $idPais;
	    return $this;
	}

	/**
	* Get idPais
	*
	* @return null|Integer
	*/
	public function getIdPais()
	{
		return $this->idPais;
	}

	/**
	* Set nombrePais
	*
	*
	*
	* @parámetro String $nombrePais
	* @return NombrePais
	*/
	public function setNombrePais($nombrePais)
	{
	  $this->nombrePais = (String) $nombrePais;
	    return $this;
	}

	/**
	* Get nombrePais
	*
	* @return null|String
	*/
	public function getNombrePais()
	{
		return $this->nombrePais;
	}

	/**
	* Set nombreProducto
	*
	*
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
	* Set nombreOperacion
	*
	*
	*
	* @parámetro String $nombreOperacion
	* @return NombreOperacion
	*/
	public function setNombreOperacion($nombreOperacion)
	{
	  $this->nombreOperacion = (String) $nombreOperacion;
	    return $this;
	}

	/**
	* Get nombreOperacion
	*
	* @return null|String
	*/
	public function getNombreOperacion()
	{
		return $this->nombreOperacion;
	}

	/**
	* Set idVue
	*
	*
	*
	* @parámetro String $idVue
	* @return IdVue
	*/
	public function setIdVue($idVue)
	{
	  $this->idVue = (String) $idVue;
	    return $this;
	}

	/**
	* Get idVue
	*
	* @return null|String
	*/
	public function getIdVue()
	{
		return $this->idVue;
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
	* @return ProveedoresModelo
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
