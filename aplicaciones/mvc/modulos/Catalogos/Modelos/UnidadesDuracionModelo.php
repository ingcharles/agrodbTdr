<?php
 /**
 * Modelo UnidadesDuracionModelo
 *
 * Este archivo se complementa con el archivo   UnidadesDuracionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-07-04
 * @uses    UnidadesDuracionModelo
 * @package Catalogos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class UnidadesDuracionModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla
		*/
		protected $idUnidadDuracion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el codigo de la unidad de duracion
		*/
		protected $codigoUnidadDuracion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre de la unidad de duracion
		*/
		protected $nombreUnidadDuracion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre en ingles de la unidad de duracion
		*/
		protected $nombreUnidadDuracionIngles;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el estado de la unidad de duracion, por defecto activo
		*/
		protected $estadoUnidadDuracion;

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
	* Nombre de la tabla: unidades_duracion
	* 
	 */
	Private $tabla="unidades_duracion";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_unidad_duracion";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_catalogos"."unidades_duracion_id_unidad_duracion_seq'; 



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
		throw new \Exception('Clase Modelo: UnidadesDuracionModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: UnidadesDuracionModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idUnidadDuracion
	*
	*Identificador unico de la tabla
	*
	* @parámetro Integer $idUnidadDuracion
	* @return IdUnidadDuracion
	*/
	public function setIdUnidadDuracion($idUnidadDuracion)
	{
	  $this->idUnidadDuracion = (Integer) $idUnidadDuracion;
	    return $this;
	}

	/**
	* Get idUnidadDuracion
	*
	* @return null|Integer
	*/
	public function getIdUnidadDuracion()
	{
		return $this->idUnidadDuracion;
	}

	/**
	* Set codigoUnidadDuracion
	*
	*Campo que almacena el codigo de la unidad de duracion
	*
	* @parámetro String $codigoUnidadDuracion
	* @return CodigoUnidadDuracion
	*/
	public function setCodigoUnidadDuracion($codigoUnidadDuracion)
	{
	  $this->codigoUnidadDuracion = (String) $codigoUnidadDuracion;
	    return $this;
	}

	/**
	* Get codigoUnidadDuracion
	*
	* @return null|String
	*/
	public function getCodigoUnidadDuracion()
	{
		return $this->codigoUnidadDuracion;
	}

	/**
	* Set nombreUnidadDuracion
	*
	*Campo que almacena el nombre de la unidad de duracion
	*
	* @parámetro String $nombreUnidadDuracion
	* @return NombreUnidadDuracion
	*/
	public function setNombreUnidadDuracion($nombreUnidadDuracion)
	{
	  $this->nombreUnidadDuracion = (String) $nombreUnidadDuracion;
	    return $this;
	}

	/**
	* Get nombreUnidadDuracion
	*
	* @return null|String
	*/
	public function getNombreUnidadDuracion()
	{
		return $this->nombreUnidadDuracion;
	}

	/**
	* Set nombreUnidadDuracionIngles
	*
	*Campo que almacena el nombre en ingles de la unidad de duracion
	*
	* @parámetro String $nombreUnidadDuracionIngles
	* @return NombreUnidadDuracionIngles
	*/
	public function setNombreUnidadDuracionIngles($nombreUnidadDuracionIngles)
	{
	  $this->nombreUnidadDuracionIngles = (String) $nombreUnidadDuracionIngles;
	    return $this;
	}

	/**
	* Get nombreUnidadDuracionIngles
	*
	* @return null|String
	*/
	public function getNombreUnidadDuracionIngles()
	{
		return $this->nombreUnidadDuracionIngles;
	}

	/**
	* Set estadoUnidadDuracion
	*
	*Campo que almacena el estado de la unidad de duracion, por defecto activo
	*
	* @parámetro String $estadoUnidadDuracion
	* @return EstadoUnidadDuracion
	*/
	public function setEstadoUnidadDuracion($estadoUnidadDuracion)
	{
	  $this->estadoUnidadDuracion = (String) $estadoUnidadDuracion;
	    return $this;
	}

	/**
	* Get estadoUnidadDuracion
	*
	* @return null|String
	*/
	public function getEstadoUnidadDuracion()
	{
		return $this->estadoUnidadDuracion;
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
	* @return UnidadesDuracionModelo
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
