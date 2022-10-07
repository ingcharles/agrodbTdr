<?php
 /**
 * Modelo CodigosPoaModelo
 *
 * Este archivo se complementa con el archivo   CodigosPoaLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-01-10
 * @uses    CodigosPoaModelo
 * @package RegistroOperador
 * @subpackage Modelos
 */
  namespace Agrodb\RegistroOperador\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class CodigosPoaModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla
		*/
		protected $idCodigoPoa;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el RUC/Cedula del operador
		*/
		protected $identificadorOperador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el codigo POA del operador
		*/
		protected $codigoPoa;

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
	* Nombre de la tabla: codigos_poa
	* 
	 */
	Private $tabla="codigos_poa";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_codigo_poa";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_operadores"."CodigosPoa_id_codigo_poa_seq'; 



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
		throw new \Exception('Clase Modelo: CodigosPoaModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: CodigosPoaModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idCodigoPoa
	*
	*Identificador unico de la tabla
	*
	* @parámetro Integer $idCodigoPoa
	* @return IdCodigoPoa
	*/
	public function setIdCodigoPoa($idCodigoPoa)
	{
	  $this->idCodigoPoa = (Integer) $idCodigoPoa;
	    return $this;
	}

	/**
	* Get idCodigoPoa
	*
	* @return null|Integer
	*/
	public function getIdCodigoPoa()
	{
		return $this->idCodigoPoa;
	}

	/**
	* Set identificadorOperador
	*
	*Campo que almacena el RUC/Cedula del operador
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
	* Set codigoPoa
	*
	*Campo que almacena el codigo POA del operador
	*
	* @parámetro String $codigoPoa
	* @return CodigoPoa
	*/
	public function setCodigoPoa($codigoPoa)
	{
	  $this->codigoPoa = (String) $codigoPoa;
	    return $this;
	}

	/**
	* Get codigoPoa
	*
	* @return null|String
	*/
	public function getCodigoPoa()
	{
		return $this->codigoPoa;
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
	* @return CodigosPoaModelo
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
