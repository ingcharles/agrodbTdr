<?php
 /**
 * Modelo ListaNotificacionModelo
 *
 * Este archivo se complementa con el archivo   ListaNotificacionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    ListaNotificacionModelo
 * @package NotificacionesFitosanitarias
 * @subpackage Modelos
 */
  namespace Agrodb\NotificacionesFitosanitarias\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class ListaNotificacionModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la tabla
		*/
		protected $idListaNotificacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre de la lista de notificación
		*/
		protected $nombreLista;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Año de creación de la lista de notificación
		*/
		protected $anio;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Mes de creación de la lista de notificación
		*/
		protected $mes;

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
	* Nombre de la tabla: lista_notificacion
	* 
	 */
	Private $tabla="lista_notificacion";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_lista_notificacion";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_notificaciones_fitosanitarias"."lista_notificacion_id_lista_notificacion_seq'; 



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
		throw new \Exception('Clase Modelo: ListaNotificacionModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: ListaNotificacionModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idListaNotificacion
	*
	*Identificador de la tabla
	*
	* @parámetro Integer $idListaNotificacion
	* @return IdListaNotificacion
	*/
	public function setIdListaNotificacion($idListaNotificacion)
	{
	  $this->idListaNotificacion = (Integer) $idListaNotificacion;
	    return $this;
	}

	/**
	* Get idListaNotificacion
	*
	* @return null|Integer
	*/
	public function getIdListaNotificacion()
	{
		return $this->idListaNotificacion;
	}

	/**
	* Set nombreLista
	*
	*Nombre de la lista de notificación
	*
	* @parámetro String $nombreLista
	* @return NombreLista
	*/
	public function setNombreLista($nombreLista)
	{
	  $this->nombreLista = (String) $nombreLista;
	    return $this;
	}

	/**
	* Get nombreLista
	*
	* @return null|String
	*/
	public function getNombreLista()
	{
		return $this->nombreLista;
	}

	/**
	* Set anio
	*
	*Año de creación de la lista de notificación
	*
	* @parámetro Integer $anio
	* @return Anio
	*/
	public function setAnio($anio)
	{
	  $this->anio = (Integer) $anio;
	    return $this;
	}

	/**
	* Get anio
	*
	* @return null|Integer
	*/
	public function getAnio()
	{
		return $this->anio;
	}

	/**
	* Set mes
	*
	*Mes de creación de la lista de notificación
	*
	* @parámetro String $mes
	* @return Mes
	*/
	public function setMes($mes)
	{
	  $this->mes = (String) $mes;
	    return $this;
	}

	/**
	* Get mes
	*
	* @return null|String
	*/
	public function getMes()
	{
		return $this->mes;
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
	* @return ListaNotificacionModelo
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
