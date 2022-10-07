<?php
 /**
 * Modelo CatastroPredioEquidosTipoActividadModelo
 *
 * Este archivo se complementa con el archivo   CatastroPredioEquidosTipoActividadLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-02-16
 * @uses    CatastroPredioEquidosTipoActividadModelo
 * @package ProgramasControlOficial
 * @subpackage Modelos
 */
  namespace Agrodb\ProgramasControlOficial\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class CatastroPredioEquidosTipoActividadModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idCatastroPredioEquidosTipoActividad;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idCatastroPredioEquidos;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $identificador;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $fechaCreacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idTipoActividad;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $tipoActividad;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $extensionActividad;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_programas_control_oficial";

	/**
	* Nombre de la tabla: catastro_predio_equidos_tipo_actividad
	* 
	 */
	Private $tabla="catastro_predio_equidos_tipo_actividad";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_catastro_predio_equidos_tipo_actividad";



	/**
	*Secuencia
*/
		 private $secuencial = '"CatastroPredioEquidosTipoActividad_"id_catastro_predio_equidos_tipo_actividad_seq'; 



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
		throw new \Exception('Clase Modelo: CatastroPredioEquidosTipoActividadModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: CatastroPredioEquidosTipoActividadModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_programas_control_oficial
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idCatastroPredioEquidosTipoActividad
	*
	*
	*
	* @parámetro Integer $idCatastroPredioEquidosTipoActividad
	* @return IdCatastroPredioEquidosTipoActividad
	*/
	public function setIdCatastroPredioEquidosTipoActividad($idCatastroPredioEquidosTipoActividad)
	{
	  $this->idCatastroPredioEquidosTipoActividad = (Integer) $idCatastroPredioEquidosTipoActividad;
	    return $this;
	}

	/**
	* Get idCatastroPredioEquidosTipoActividad
	*
	* @return null|Integer
	*/
	public function getIdCatastroPredioEquidosTipoActividad()
	{
		return $this->idCatastroPredioEquidosTipoActividad;
	}

	/**
	* Set idCatastroPredioEquidos
	*
	*
	*
	* @parámetro Integer $idCatastroPredioEquidos
	* @return IdCatastroPredioEquidos
	*/
	public function setIdCatastroPredioEquidos($idCatastroPredioEquidos)
	{
	  $this->idCatastroPredioEquidos = (Integer) $idCatastroPredioEquidos;
	    return $this;
	}

	/**
	* Get idCatastroPredioEquidos
	*
	* @return null|Integer
	*/
	public function getIdCatastroPredioEquidos()
	{
		return $this->idCatastroPredioEquidos;
	}

	/**
	* Set identificador
	*
	*
	*
	* @parámetro String $identificador
	* @return Identificador
	*/
	public function setIdentificador($identificador)
	{
	  $this->identificador = (String) $identificador;
	    return $this;
	}

	/**
	* Get identificador
	*
	* @return null|String
	*/
	public function getIdentificador()
	{
		return $this->identificador;
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
	* Set idTipoActividad
	*
	*
	*
	* @parámetro Integer $idTipoActividad
	* @return IdTipoActividad
	*/
	public function setIdTipoActividad($idTipoActividad)
	{
	  $this->idTipoActividad = (Integer) $idTipoActividad;
	    return $this;
	}

	/**
	* Get idTipoActividad
	*
	* @return null|Integer
	*/
	public function getIdTipoActividad()
	{
		return $this->idTipoActividad;
	}

	/**
	* Set tipoActividad
	*
	*
	*
	* @parámetro String $tipoActividad
	* @return TipoActividad
	*/
	public function setTipoActividad($tipoActividad)
	{
	  $this->tipoActividad = (String) $tipoActividad;
	    return $this;
	}

	/**
	* Get tipoActividad
	*
	* @return null|String
	*/
	public function getTipoActividad()
	{
		return $this->tipoActividad;
	}

	/**
	* Set extensionActividad
	*
	*
	*
	* @parámetro String $extensionActividad
	* @return ExtensionActividad
	*/
	public function setExtensionActividad($extensionActividad)
	{
	  $this->extensionActividad = (String) $extensionActividad;
	    return $this;
	}

	/**
	* Get extensionActividad
	*
	* @return null|String
	*/
	public function getExtensionActividad()
	{
		return $this->extensionActividad;
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
	* @return CatastroPredioEquidosTipoActividadModelo
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
