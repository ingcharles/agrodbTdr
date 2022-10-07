<?php
 /**
 * Modelo TratamientosModelo
 *
 * Este archivo se complementa con el archivo   TratamientosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-07-04
 * @uses    TratamientosModelo
 * @package Catalogos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class TratamientosModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla
		*/
		protected $idTratamiento;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el codigo del tratamiento
		*/
		protected $codigoTratamiento;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre en del tratamiento
		*/
		protected $nombreTratamiento;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre en ingles del tratamiento
		*/
		protected $nombreTratamientoIngles;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el estado del tratamiento, por defecto activo
		*/
		protected $estadoTratamiento;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_catalogos";

	/**
	* Nombre de la tabla: tratamientos
	* 
	 */
	Private $tabla="tratamientos";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_tratamiento";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_catalogos"."tratamientos_id_tratamiento_seq'; 



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
		throw new \Exception('Clase Modelo: TratamientosModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: TratamientosModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_catalogos
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idTratamiento
	*
	*Identificador unico de la tabla
	*
	* @parámetro Integer $idTratamiento
	* @return IdTratamiento
	*/
	public function setIdTratamiento($idTratamiento)
	{
	  $this->idTratamiento = (Integer) $idTratamiento;
	    return $this;
	}

	/**
	* Get idTratamiento
	*
	* @return null|Integer
	*/
	public function getIdTratamiento()
	{
		return $this->idTratamiento;
	}

	/**
	* Set codigoTratamiento
	*
	*Campo que almacena el codigo del tratamiento
	*
	* @parámetro String $codigoTratamiento
	* @return CodigoTratamiento
	*/
	public function setCodigoTratamiento($codigoTratamiento)
	{
	  $this->codigoTratamiento = (String) $codigoTratamiento;
	    return $this;
	}

	/**
	* Get codigoTratamiento
	*
	* @return null|String
	*/
	public function getCodigoTratamiento()
	{
		return $this->codigoTratamiento;
	}

	/**
	* Set nombreTratamiento
	*
	*Campo que almacena el nombre en del tratamiento
	*
	* @parámetro String $nombreTratamiento
	* @return NombreTratamiento
	*/
	public function setNombreTratamiento($nombreTratamiento)
	{
	  $this->nombreTratamiento = (String) $nombreTratamiento;
	    return $this;
	}

	/**
	* Get nombreTratamiento
	*
	* @return null|String
	*/
	public function getNombreTratamiento()
	{
		return $this->nombreTratamiento;
	}

	/**
	* Set nombreTratamientoIngles
	*
	*Campo que almacena el nombre en ingles del tratamiento
	*
	* @parámetro String $nombreTratamientoIngles
	* @return NombreTratamientoIngles
	*/
	public function setNombreTratamientoIngles($nombreTratamientoIngles)
	{
	  $this->nombreTratamientoIngles = (String) $nombreTratamientoIngles;
	    return $this;
	}

	/**
	* Get nombreTratamientoIngles
	*
	* @return null|String
	*/
	public function getNombreTratamientoIngles()
	{
		return $this->nombreTratamientoIngles;
	}

	/**
	* Set estadoTratamiento
	*
	*Campo que almacena el estado del tratamiento, por defecto activo
	*
	* @parámetro String $estadoTratamiento
	* @return EstadoTratamiento
	*/
	public function setEstadoTratamiento($estadoTratamiento)
	{
	  $this->estadoTratamiento = (String) $estadoTratamiento;
	    return $this;
	}

	/**
	* Get estadoTratamiento
	*
	* @return null|String
	*/
	public function getEstadoTratamiento()
	{
		return $this->estadoTratamiento;
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
	* @return TratamientosModelo
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
