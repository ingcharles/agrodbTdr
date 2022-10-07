<?php
 /**
 * Modelo IdiomasModelo
 *
 * Este archivo se complementa con el archivo   IdiomasLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-07-04
 * @uses    IdiomasModelo
 * @package Catalogos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class IdiomasModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla
		*/
		protected $idIdioma;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el codigo del idioma
		*/
		protected $codigoIdioma;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre del idioma
		*/
		protected $nombreIdioma;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre del idioma en ingles
		*/
		protected $nombreIdiomaIngles;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el estado del idioma
		*/
		protected $estadoIdioma;

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
	* Nombre de la tabla: idiomas
	* 
	 */
	Private $tabla="idiomas";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_idioma";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_catalogos"."idiomas_id_idioma_seq'; 



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
		throw new \Exception('Clase Modelo: IdiomasModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: IdiomasModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idIdioma
	*
	*Identificador unico de la tabla
	*
	* @parámetro Integer $idIdioma
	* @return IdIdioma
	*/
	public function setIdIdioma($idIdioma)
	{
	  $this->idIdioma = (Integer) $idIdioma;
	    return $this;
	}

	/**
	* Get idIdioma
	*
	* @return null|Integer
	*/
	public function getIdIdioma()
	{
		return $this->idIdioma;
	}

	/**
	* Set codigoIdioma
	*
	*Campo que almacena el codigo del idioma
	*
	* @parámetro String $codigoIdioma
	* @return CodigoIdioma
	*/
	public function setCodigoIdioma($codigoIdioma)
	{
	  $this->codigoIdioma = (String) $codigoIdioma;
	    return $this;
	}

	/**
	* Get codigoIdioma
	*
	* @return null|String
	*/
	public function getCodigoIdioma()
	{
		return $this->codigoIdioma;
	}

	/**
	* Set nombreIdioma
	*
	*Campo que almacena el nombre del idioma
	*
	* @parámetro String $nombreIdioma
	* @return NombreIdioma
	*/
	public function setNombreIdioma($nombreIdioma)
	{
	  $this->nombreIdioma = (String) $nombreIdioma;
	    return $this;
	}

	/**
	* Get nombreIdioma
	*
	* @return null|String
	*/
	public function getNombreIdioma()
	{
		return $this->nombreIdioma;
	}

	/**
	* Set nombreIdiomaIngles
	*
	*Campo que almacena el nombre del idioma en ingles
	*
	* @parámetro String $nombreIdiomaIngles
	* @return NombreIdiomaIngles
	*/
	public function setNombreIdiomaIngles($nombreIdiomaIngles)
	{
	  $this->nombreIdiomaIngles = (String) $nombreIdiomaIngles;
	    return $this;
	}

	/**
	* Get nombreIdiomaIngles
	*
	* @return null|String
	*/
	public function getNombreIdiomaIngles()
	{
		return $this->nombreIdiomaIngles;
	}

	/**
	* Set estadoIdioma
	*
	*Campo que almacena el estado del idioma
	*
	* @parámetro String $estadoIdioma
	* @return EstadoIdioma
	*/
	public function setEstadoIdioma($estadoIdioma)
	{
	  $this->estadoIdioma = (String) $estadoIdioma;
	    return $this;
	}

	/**
	* Get estadoIdioma
	*
	* @return null|String
	*/
	public function getEstadoIdioma()
	{
		return $this->estadoIdioma;
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
	* @return IdiomasModelo
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
