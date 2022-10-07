<?php
 /**
 * Modelo NotificacionPorPaisAfectadoModelo
 *
 * Este archivo se complementa con el archivo   NotificacionPorPaisAfectadoLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-09-09
 * @uses    NotificacionPorPaisAfectadoModelo
 * @package NotificacionesFitosanitarias
 * @subpackage Modelos
 */
  namespace Agrodb\NotificacionesFitosanitarias\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class NotificacionPorPaisAfectadoModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la tabla
		*/
		protected $idNotificacionPorProducto;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la notificación
		*/
		protected $idNotificacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del país afectado
		*/
		protected $idLocalizacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre del país afectado
		*/
		protected $nombrePais;

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
	* Nombre de la tabla: notificacion_por_pais_afectado
	* 
	 */
	Private $tabla="notificacion_por_pais_afectado";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_notificacion_por_producto";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_notificaciones_fitosanitarias"."notificacion_por_pais_afectado_id_notificacion_por_producto_seq';



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
		throw new \Exception('Clase Modelo: NotificacionPorPaisAfectadoModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: NotificacionPorPaisAfectadoModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idNotificacionPorProducto
	*
	*Identificador de la tabla
	*
	* @parámetro Integer $idNotificacionPorProducto
	* @return IdNotificacionPorProducto
	*/
	public function setIdNotificacionPorProducto($idNotificacionPorProducto)
	{
	  $this->idNotificacionPorProducto = (Integer) $idNotificacionPorProducto;
	    return $this;
	}

	/**
	* Get idNotificacionPorProducto
	*
	* @return null|Integer
	*/
	public function getIdNotificacionPorProducto()
	{
		return $this->idNotificacionPorProducto;
	}

	/**
	* Set idNotificacion
	*
	*Identificador de la notificación
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
	* Set idLocalizacion
	*
	*Identificador del país afectado
	*
	* @parámetro Integer $idLocalizacion
	* @return IdLocalizacion
	*/
	public function setIdLocalizacion($idLocalizacion)
	{
	  $this->idLocalizacion = (Integer) $idLocalizacion;
	    return $this;
	}

	/**
	* Get idLocalizacion
	*
	* @return null|Integer
	*/
	public function getIdLocalizacion()
	{
		return $this->idLocalizacion;
	}

	/**
	* Set nombrePais
	*
	*Nombre del país afectado
	*
	* @parámetro String $nombrePais
	* @return NombrePais
	*/
	public function setNombrePais($nombrePais)
	{
	  $this->nombrePais = (String) $nombrePais;
	    return $this;
	}

	/**
	* Get nombrePais
	*
	* @return null|String
	*/
	public function getNombrePais()
	{
		return $this->nombrePais;
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
	* @return NotificacionPorPaisAfectadoModelo
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
