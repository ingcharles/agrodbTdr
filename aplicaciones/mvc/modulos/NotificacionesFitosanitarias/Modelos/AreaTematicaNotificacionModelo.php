<?php
 /**
 * Modelo AreaTematicaNotificacionModelo
 *
 * Este archivo se complementa con el archivo   AreaTematicaNotificacionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-03-17
 * @uses    AreaTematicaNotificacionModelo
 * @package NotificacionesFitosanitarias
 * @subpackage Modelos
 */
  namespace Agrodb\NotificacionesFitosanitarias\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class AreaTematicaNotificacionModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
		protected $idAreaTematicaNotificacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave foránea de la tabla notifiaciones
		*/
		protected $idNotificacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Área temática seleccionada
		*/
		protected $areaTematica;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de creación del registro
		*/
		protected $fechaCreacion;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_notificaciones_fitosanitarias";

	/**
	* Nombre de la tabla: area_tematica_notificacion
	* 
	 */
	Private $tabla="area_tematica_notificacion";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_area_tematica_notificacion";



	/**
	*Secuencia
*/
		 private $secuencial ='g_notificaciones_fitosanitarias"."area_tematica_notificacion_id_area_tematica_notificacion_seq'; 



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
		throw new \Exception('Clase Modelo: AreaTematicaNotificacionModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: AreaTematicaNotificacionModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_notificaciones_fitosanitarias
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idAreaTematicaNotificacion
	*
	*Llave primaria de la tabla
	*
	* @parámetro Integer $idAreaTematicaNotificacion
	* @return IdAreaTematicaNotificacion
	*/
	public function setIdAreaTematicaNotificacion($idAreaTematicaNotificacion)
	{
	  $this->idAreaTematicaNotificacion = (Integer) $idAreaTematicaNotificacion;
	    return $this;
	}

	/**
	* Get idAreaTematicaNotificacion
	*
	* @return null|Integer
	*/
	public function getIdAreaTematicaNotificacion()
	{
		return $this->idAreaTematicaNotificacion;
	}

	/**
	* Set idNotificacion
	*
	*Llave foránea de la tabla notifiaciones
	*
	* @parámetro Integer $idNotificacion
	* @return IdNotificacion
	*/
	public function setIdNotificacion($idNotificacion)
	{
	  $this->idNotificacion = (Integer) $idNotificacion;
	    return $this;
	}

	/**
	* Get idNotificacion
	*
	* @return null|Integer
	*/
	public function getIdNotificacion()
	{
		return $this->idNotificacion;
	}

	/**
	* Set areaTematica
	*
	*Área temática seleccionada
	*
	* @parámetro String $areaTematica
	* @return AreaTematica
	*/
	public function setAreaTematica($areaTematica)
	{
	  $this->areaTematica = (String) $areaTematica;
	    return $this;
	}

	/**
	* Get areaTematica
	*
	* @return null|String
	*/
	public function getAreaTematica()
	{
		return $this->areaTematica;
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
	* @return AreaTematicaNotificacionModelo
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
