<?php
 /**
 * Modelo TiposTratamientoModelo
 *
 * Este archivo se complementa con el archivo   TiposTratamientoLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-07-04
 * @uses    TiposTratamientoModelo
 * @package Catalogos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class TiposTratamientoModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla
		*/
		protected $idTipoTratamiento;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el codigo de tipo de tratamiento
		*/
		protected $codigoTipoTratamiento;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre de tipo de tratamiento
		*/
		protected $nombreTipoTratamiento;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre de en ingles del tipo de tratamiento
		*/
		protected $nombreTipoTratamientoIngles;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el estado de tipo de tratamiento
		*/
		protected $estadoTipoTratamiento;

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
	* Nombre de la tabla: tipos_tratamiento
	* 
	 */
	Private $tabla="tipos_tratamiento";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_tipo_tratamiento";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_catalogos"."tipos_tratamiento_id_tipo_tratamiento_seq'; 



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
		throw new \Exception('Clase Modelo: TiposTratamientoModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: TiposTratamientoModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idTipoTratamiento
	*
	*Identificador unico de la tabla
	*
	* @parámetro Integer $idTipoTratamiento
	* @return IdTipoTratamiento
	*/
	public function setIdTipoTratamiento($idTipoTratamiento)
	{
	  $this->idTipoTratamiento = (Integer) $idTipoTratamiento;
	    return $this;
	}

	/**
	* Get idTipoTratamiento
	*
	* @return null|Integer
	*/
	public function getIdTipoTratamiento()
	{
		return $this->idTipoTratamiento;
	}

	/**
	* Set codigoTipoTratamiento
	*
	*Campo que almacena el codigo de tipo de tratamiento
	*
	* @parámetro String $codigoTipoTratamiento
	* @return CodigoTipoTratamiento
	*/
	public function setCodigoTipoTratamiento($codigoTipoTratamiento)
	{
	  $this->codigoTipoTratamiento = (String) $codigoTipoTratamiento;
	    return $this;
	}

	/**
	* Get codigoTipoTratamiento
	*
	* @return null|String
	*/
	public function getCodigoTipoTratamiento()
	{
		return $this->codigoTipoTratamiento;
	}

	/**
	* Set nombreTipoTratamiento
	*
	*Campo que almacena el nombre de tipo de tratamiento
	*
	* @parámetro String $nombreTipoTratamiento
	* @return NombreTipoTratamiento
	*/
	public function setNombreTipoTratamiento($nombreTipoTratamiento)
	{
	  $this->nombreTipoTratamiento = (String) $nombreTipoTratamiento;
	    return $this;
	}

	/**
	* Get nombreTipoTratamiento
	*
	* @return null|String
	*/
	public function getNombreTipoTratamiento()
	{
		return $this->nombreTipoTratamiento;
	}

	/**
	* Set nombreTipoTratamientoIngles
	*
	*Campo que almacena el nombre de en ingles del tipo de tratamiento
	*
	* @parámetro String $nombreTipoTratamientoIngles
	* @return NombreTipoTratamientoIngles
	*/
	public function setNombreTipoTratamientoIngles($nombreTipoTratamientoIngles)
	{
	  $this->nombreTipoTratamientoIngles = (String) $nombreTipoTratamientoIngles;
	    return $this;
	}

	/**
	* Get nombreTipoTratamientoIngles
	*
	* @return null|String
	*/
	public function getNombreTipoTratamientoIngles()
	{
		return $this->nombreTipoTratamientoIngles;
	}

	/**
	* Set estadoTipoTratamiento
	*
	*Campo que almacena el estado de tipo de tratamiento
	*
	* @parámetro String $estadoTipoTratamiento
	* @return EstadoTipoTratamiento
	*/
	public function setEstadoTipoTratamiento($estadoTipoTratamiento)
	{
	  $this->estadoTipoTratamiento = (String) $estadoTipoTratamiento;
	    return $this;
	}

	/**
	* Get estadoTipoTratamiento
	*
	* @return null|String
	*/
	public function getEstadoTipoTratamiento()
	{
		return $this->estadoTipoTratamiento;
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
	* @return TiposTratamientoModelo
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
