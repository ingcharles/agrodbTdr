<?php
 /**
 * Modelo AreaModelo
 *
 * Este archivo se complementa con el archivo   AreaLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-02-13
 * @uses    AreaModelo
 * @package Estructura
 * @subpackage Modelos
 */
  namespace Agrodb\Estructura\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class AreaModelo extends ModeloBase 
{

		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idArea;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $nombre;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idAreaPadre;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $estado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $clasificacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 0-> AGR
1-> DE 
2-> Coordinaciones y zonas
3-> Direcciones PC y DDAT, DD
4-> Gestion y oficinas tecnicas
		*/
		protected $categoriaArea;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $zonaArea;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_estructura";

	/**
	* Nombre de la tabla: area
	* 
	 */
	Private $tabla="area";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_area";



	/**
	*Secuencia
*/
		 private $secuencial = '"Area_"id_area_seq'; 



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
		throw new \Exception('Clase Modelo: AreaModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: AreaModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_estructura
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idArea
	*
	*
	*
	* @parámetro String $idArea
	* @return IdArea
	*/
	public function setIdArea($idArea)
	{
	  $this->idArea = (String) $idArea;
	    return $this;
	}

	/**
	* Get idArea
	*
	* @return null|String
	*/
	public function getIdArea()
	{
		return $this->idArea;
	}

	/**
	* Set nombre
	*
	*
	*
	* @parámetro String $nombre
	* @return Nombre
	*/
	public function setNombre($nombre)
	{
	  $this->nombre = (String) $nombre;
	    return $this;
	}

	/**
	* Get nombre
	*
	* @return null|String
	*/
	public function getNombre()
	{
		return $this->nombre;
	}

	/**
	* Set idAreaPadre
	*
	*
	*
	* @parámetro String $idAreaPadre
	* @return IdAreaPadre
	*/
	public function setIdAreaPadre($idAreaPadre)
	{
	  $this->idAreaPadre = (String) $idAreaPadre;
	    return $this;
	}

	/**
	* Get idAreaPadre
	*
	* @return null|String
	*/
	public function getIdAreaPadre()
	{
		return $this->idAreaPadre;
	}

	/**
	* Set estado
	*
	*
	*
	* @parámetro Integer $estado
	* @return Estado
	*/
	public function setEstado($estado)
	{
	  $this->estado = (Integer) $estado;
	    return $this;
	}

	/**
	* Get estado
	*
	* @return null|Integer
	*/
	public function getEstado()
	{
		return $this->estado;
	}

	/**
	* Set clasificacion
	*
	*
	*
	* @parámetro String $clasificacion
	* @return Clasificacion
	*/
	public function setClasificacion($clasificacion)
	{
	  $this->clasificacion = (String) $clasificacion;
	    return $this;
	}

	/**
	* Get clasificacion
	*
	* @return null|String
	*/
	public function getClasificacion()
	{
		return $this->clasificacion;
	}

	/**
	* Set categoriaArea
	*
	*0-> AGR
1-> DE 
2-> Coordinaciones y zonas
3-> Direcciones PC y DDAT, DD
4-> Gestion y oficinas tecnicas
	*
	* @parámetro Integer $categoriaArea
	* @return CategoriaArea
	*/
	public function setCategoriaArea($categoriaArea)
	{
	  $this->categoriaArea = (Integer) $categoriaArea;
	    return $this;
	}

	/**
	* Get categoriaArea
	*
	* @return null|Integer
	*/
	public function getCategoriaArea()
	{
		return $this->categoriaArea;
	}

	/**
	* Set zonaArea
	*
	*
	*
	* @parámetro String $zonaArea
	* @return ZonaArea
	*/
	public function setZonaArea($zonaArea)
	{
	  $this->zonaArea = (String) $zonaArea;
	    return $this;
	}

	/**
	* Get zonaArea
	*
	* @return null|String
	*/
	public function getZonaArea()
	{
		return $this->zonaArea;
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
	* @return AreaModelo
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
		return parent::buscarLista($where, $order);
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
