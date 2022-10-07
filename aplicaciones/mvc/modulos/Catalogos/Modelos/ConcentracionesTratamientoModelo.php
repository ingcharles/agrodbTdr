<?php
 /**
 * Modelo ConcentracionesTratamientoModelo
 *
 * Este archivo se complementa con el archivo   ConcentracionesTratamientoLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-07-04
 * @uses    ConcentracionesTratamientoModelo
 * @package Catalogos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class ConcentracionesTratamientoModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla
		*/
		protected $idConcentracionTratamiento;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el codigo de la unidad de concentracion de tratamiento
		*/
		protected $codigoConcentracionTratamiento;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre de la unidad de concentracion de tratamiento
		*/
		protected $nombreConcentracionTratamiento;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre en ingles de la unidad de concentracion de tratamiento
		*/
		protected $nombreConcentracionTratamientoIngles;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el estado de la unidad de concentracion de tratamiento, por defecto activo
		*/
		protected $estadoConcentracionTratamiento;

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
	* Nombre de la tabla: concentraciones_tratamiento
	* 
	 */
	Private $tabla="concentraciones_tratamiento";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_concentracion_tratamiento";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_catalogos"."concentraciones_tratamiento_id_concentracion_tratamiento_seq'; 



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
		throw new \Exception('Clase Modelo: ConcentracionesTratamientoModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: ConcentracionesTratamientoModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idConcentracionTratamiento
	*
	*Identificador unico de la tabla
	*
	* @parámetro Integer $idConcentracionTratamiento
	* @return IdConcentracionTratamiento
	*/
	public function setIdConcentracionTratamiento($idConcentracionTratamiento)
	{
	  $this->idConcentracionTratamiento = (Integer) $idConcentracionTratamiento;
	    return $this;
	}

	/**
	* Get idConcentracionTratamiento
	*
	* @return null|Integer
	*/
	public function getIdConcentracionTratamiento()
	{
		return $this->idConcentracionTratamiento;
	}

	/**
	* Set codigoConcentracionTratamiento
	*
	*Campo que almacena el codigo de la unidad de concentracion de tratamiento
	*
	* @parámetro String $codigoConcentracionTratamiento
	* @return CodigoConcentracionTratamiento
	*/
	public function setCodigoConcentracionTratamiento($codigoConcentracionTratamiento)
	{
	  $this->codigoConcentracionTratamiento = (String) $codigoConcentracionTratamiento;
	    return $this;
	}

	/**
	* Get codigoConcentracionTratamiento
	*
	* @return null|String
	*/
	public function getCodigoConcentracionTratamiento()
	{
		return $this->codigoConcentracionTratamiento;
	}

	/**
	* Set nombreConcentracionTratamiento
	*
	*Campo que almacena el nombre de la unidad de concentracion de tratamiento
	*
	* @parámetro String $nombreConcentracionTratamiento
	* @return NombreConcentracionTratamiento
	*/
	public function setNombreConcentracionTratamiento($nombreConcentracionTratamiento)
	{
	  $this->nombreConcentracionTratamiento = (String) $nombreConcentracionTratamiento;
	    return $this;
	}

	/**
	* Get nombreConcentracionTratamiento
	*
	* @return null|String
	*/
	public function getNombreConcentracionTratamiento()
	{
		return $this->nombreConcentracionTratamiento;
	}

	/**
	* Set nombreConcentracionTratamientoIngles
	*
	*Campo que almacena el nombre en ingles de la unidad de concentracion de tratamiento
	*
	* @parámetro String $nombreConcentracionTratamientoIngles
	* @return NombreConcentracionTratamientoIngles
	*/
	public function setNombreConcentracionTratamientoIngles($nombreConcentracionTratamientoIngles)
	{
	  $this->nombreConcentracionTratamientoIngles = (String) $nombreConcentracionTratamientoIngles;
	    return $this;
	}

	/**
	* Get nombreConcentracionTratamientoIngles
	*
	* @return null|String
	*/
	public function getNombreConcentracionTratamientoIngles()
	{
		return $this->nombreConcentracionTratamientoIngles;
	}

	/**
	* Set estadoConcentracionTratamiento
	*
	*Campo que almacena el estado de la unidad de concentracion de tratamiento, por defecto activo
	*
	* @parámetro String $estadoConcentracionTratamiento
	* @return EstadoConcentracionTratamiento
	*/
	public function setEstadoConcentracionTratamiento($estadoConcentracionTratamiento)
	{
	  $this->estadoConcentracionTratamiento = (String) $estadoConcentracionTratamiento;
	    return $this;
	}

	/**
	* Get estadoConcentracionTratamiento
	*
	* @return null|String
	*/
	public function getEstadoConcentracionTratamiento()
	{
		return $this->estadoConcentracionTratamiento;
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
	* @return ConcentracionesTratamientoModelo
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
