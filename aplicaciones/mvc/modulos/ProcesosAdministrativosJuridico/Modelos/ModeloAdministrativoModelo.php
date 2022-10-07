<?php
 /**
 * Modelo ModeloAdministrativoModelo
 *
 * Este archivo se complementa con el archivo   ModeloAdministrativoLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-03-17
 * @uses    ModeloAdministrativoModelo
 * @package ProcesosAdministrativosJuridico
 * @subpackage Modelos
 */
  namespace Agrodb\ProcesosAdministrativosJuridico\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class ModeloAdministrativoModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
		protected $idModeloAdministrativo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Ruta del modelo adminstrativo
		*/
		protected $rutaModelo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre del modelo administrativo
		*/
		protected $nombreModelo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Estado del registro
		*/
		protected $estado;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de creación del registro
		*/
		protected $fechaCreacion;
		/**
		 * @var Integer
		 * Campo requerido
		 * Campo visible en el formulario
		 * orden del modelo
		 */
		protected $orden;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * Descripcion del registro
		 */
		protected $descripcion;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_procesos_administrativos_juridico";

	/**
	* Nombre de la tabla: modelo_administrativo
	* 
	 */
	Private $tabla="modelo_administrativo";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_modelo_administrativo";



	/**
	*Secuencia
*/
		 private $secuencial = '"ModeloAdministrativo_"id_modelo_administrativo_seq'; 



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
		throw new \Exception('Clase Modelo: ModeloAdministrativoModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: ModeloAdministrativoModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_procesos_administrativos_juridico
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idModeloAdministrativo
	*
	*Llave primaria de la tabla
	*
	* @parámetro Integer $idModeloAdministrativo
	* @return IdModeloAdministrativo
	*/
	public function setIdModeloAdministrativo($idModeloAdministrativo)
	{
	  $this->idModeloAdministrativo = (Integer) $idModeloAdministrativo;
	    return $this;
	}

	/**
	* Get idModeloAdministrativo
	*
	* @return null|Integer
	*/
	public function getIdModeloAdministrativo()
	{
		return $this->idModeloAdministrativo;
	}

	/**
	* Set rutaModelo
	*
	*Ruta del modelo adminstrativo
	*
	* @parámetro String $rutaModelo
	* @return RutaModelo
	*/
	public function setRutaModelo($rutaModelo)
	{
	  $this->rutaModelo = (String) $rutaModelo;
	    return $this;
	}

	/**
	* Get rutaModelo
	*
	* @return null|String
	*/
	public function getRutaModelo()
	{
		return $this->rutaModelo;
	}

	/**
	* Set nombreModelo
	*
	*Nombre del modelo administrativo
	*
	* @parámetro String $nombreModelo
	* @return NombreModelo
	*/
	public function setNombreModelo($nombreModelo)
	{
	  $this->nombreModelo = (String) $nombreModelo;
	    return $this;
	}

	/**
	* Get nombreModelo
	*
	* @return null|String
	*/
	public function getNombreModelo()
	{
		return $this->nombreModelo;
	}

	/**
	* Set estado
	*
	*Estado del registro
	*
	* @parámetro String $estado
	* @return Estado
	*/
	public function setEstado($estado)
	{
	  $this->estado = (String) $estado;
	    return $this;
	}

	/**
	* Get estado
	*
	* @return null|String
	*/
	public function getEstado()
	{
		return $this->estado;
	}

	/**
	* Set fechaCreacion
	*
	*Fecha de creación del registro
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
	 * Set orden
	 *
	 * @parámetro Integer $orden
	 * @return Orden
	 */
	public function setOrden($orden)
	{
	    $this->orden = (Integer) $orden;
	    return $this;
	}
	
	/**
	 * Get Orden
	 *
	 * @return null|Integer
	 */
	public function getOrden()
	{
	    return $this->orden;
	}
	
	
	/**
	 * Set descripicion
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
	 * Get deescripcion
	 *
	 * @return null|String
	 */
	public function getDescripcion()
	{
	    return $this->descripcion;
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
	* @return ModeloAdministrativoModelo
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
