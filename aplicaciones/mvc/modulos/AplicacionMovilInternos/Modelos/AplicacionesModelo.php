<?php
 /**
 * Modelo AplicacionesModelo
 *
 * Este archivo se complementa con el archivo   AplicacionesLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-07
 * @uses    AplicacionesModelo
 * @package AplicacionMovilInternos
 * @subpackage Modelos
 */
  namespace Agrodb\AplicacionMovilInternos\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class AplicacionesModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idAplicacion;
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
		protected $version;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $descripcion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $colorInicio;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $colorFin;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $codificacionAplicacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $estadoAplicacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Código del área temática
		*/
		protected $idArea;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre de la vista en la aplicación móvil
		*/
		protected $vista;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="a_movil_internos";

	/**
	* Nombre de la tabla: aplicaciones
	* 
	 */
	Private $tabla="aplicaciones";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_aplicacion";



	/**
	*Secuencia
*/
		 private $secuencial = 'a_movil_internos"."Aplicaciones_id_aplicacion_seq'; 



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
		throw new \Exception('Clase Modelo: AplicacionesModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: AplicacionesModelo. Propiedad especificada invalida: get'.$name);
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
	* Get a_movil_internos
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idAplicacion
	*
	*
	*
	* @parámetro Integer $idAplicacion
	* @return IdAplicacion
	*/
	public function setIdAplicacion($idAplicacion)
	{
	  $this->idAplicacion = (Integer) $idAplicacion;
	    return $this;
	}

	/**
	* Get idAplicacion
	*
	* @return null|Integer
	*/
	public function getIdAplicacion()
	{
		return $this->idAplicacion;
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
	* Set version
	*
	*
	*
	* @parámetro String $version
	* @return Version
	*/
	public function setVersion($version)
	{
	  $this->version = (String) $version;
	    return $this;
	}

	/**
	* Get version
	*
	* @return null|String
	*/
	public function getVersion()
	{
		return $this->version;
	}

	/**
	* Set descripcion
	*
	*
	*
	* @parámetro String $descripcion
	* @return Descripcion
	*/
	public function setDescripcion($descripcion)
	{
	  $this->descripcion = (String) $descripcion;
	    return $this;
	}

	/**
	* Get descripcion
	*
	* @return null|String
	*/
	public function getDescripcion()
	{
		return $this->descripcion;
	}

	/**
	* Set colorInicio
	*
	*
	*
	* @parámetro String $colorInicio
	* @return ColorInicio
	*/
	public function setColorInicio($colorInicio)
	{
	  $this->colorInicio = (String) $colorInicio;
	    return $this;
	}

	/**
	* Get colorInicio
	*
	* @return null|String
	*/
	public function getColorInicio()
	{
		return $this->colorInicio;
	}

	/**
	* Set colorFin
	*
	*
	*
	* @parámetro String $colorFin
	* @return ColorFin
	*/
	public function setColorFin($colorFin)
	{
	  $this->colorFin = (String) $colorFin;
	    return $this;
	}

	/**
	* Get colorFin
	*
	* @return null|String
	*/
	public function getColorFin()
	{
		return $this->colorFin;
	}

	/**
	* Set codificacionAplicacion
	*
	*
	*
	* @parámetro String $codificacionAplicacion
	* @return CodificacionAplicacion
	*/
	public function setCodificacionAplicacion($codificacionAplicacion)
	{
	  $this->codificacionAplicacion = (String) $codificacionAplicacion;
	    return $this;
	}

	/**
	* Get codificacionAplicacion
	*
	* @return null|String
	*/
	public function getCodificacionAplicacion()
	{
		return $this->codificacionAplicacion;
	}

	/**
	* Set estadoAplicacion
	*
	*
	*
	* @parámetro String $estadoAplicacion
	* @return EstadoAplicacion
	*/
	public function setEstadoAplicacion($estadoAplicacion)
	{
	  $this->estadoAplicacion = (String) $estadoAplicacion;
	    return $this;
	}

	/**
	* Get estadoAplicacion
	*
	* @return null|String
	*/
	public function getEstadoAplicacion()
	{
		return $this->estadoAplicacion;
	}

	/**
	* Set idArea
	*
	*Código del área temática
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
	* Set vista
	*
	*Nombre de la vista en la aplicación móvil
	*
	* @parámetro String $vista
	* @return Vista
	*/
	public function setVista($vista)
	{
	  $this->vista = (String) $vista;
	    return $this;
	}

	/**
	* Get vista
	*
	* @return null|String
	*/
	public function getVista()
	{
		return $this->vista;
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
	* @return AplicacionesModelo
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
