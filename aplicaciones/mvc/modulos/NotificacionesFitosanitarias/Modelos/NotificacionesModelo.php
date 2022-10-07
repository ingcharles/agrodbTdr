<?php
 /**
 * Modelo NotificacionesModelo
 *
 * Este archivo se complementa con el archivo   NotificacionesLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-09-09
 * @uses    NotificacionesModelo
 * @package NotificacionesFitosanitarias
 * @subpackage Modelos
 */
  namespace Agrodb\NotificacionesFitosanitarias\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class NotificacionesModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la tabla notificaciones
		*/
		protected $idNotificacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador tabla Lista notificaciones
		*/
		protected $idListaNotificacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del país que realiza la notitifcación
		*/
		protected $idPaisNotifica;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre del país que realiza la notitifcación
		*/
		protected $nombrePaisNotifica;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Código de la notificación
		*/
		protected $codigoDocumento;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Tipo de documento generado
		*/
		protected $tipoDocumento;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Descripción de la notificación
		*/
		protected $descripcion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Enlace donde se encuentra el documento de notificación detallado
		*/
		protected $enlace;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Ruta donde se almacena el archivo
		*/
		protected $rutaArchivo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Productos relacionados a la notificación
		*/
		protected $producto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Palabras claves de la notificación
		*/
		protected $palabraClave;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha que se realiza la notificación
		*/
		protected $fechaNotificacion;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de cierre de la notificación será 60 días a partir de la fecha de notificación
		*/
		protected $fechaCierre;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 */
		protected $comentarios;

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
	* Nombre de la tabla: notificaciones
	* 
	 */
	Private $tabla="notificaciones";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_notificacion";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_notificaciones_fitosanitarias"."notificaciones_id_notificacion_seq'; 



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
		throw new \Exception('Clase Modelo: NotificacionesModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: NotificacionesModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idNotificacion
	*
	*Identificador de la tabla notificaciones
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
	* Set idListaNotificacion
	*
	*Identificador tabla Lista notificaciones
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
	* Set idPaisNotifica
	*
	*Identificador del país que realiza la notitifcación
	*
	* @parámetro Integer $idPaisNotifica
	* @return IdPaisNotifica
	*/
	public function setIdPaisNotifica($idPaisNotifica)
	{
	  $this->idPaisNotifica = (Integer) $idPaisNotifica;
	    return $this;
	}

	/**
	* Get idPaisNotifica
	*
	* @return null|Integer
	*/
	public function getIdPaisNotifica()
	{
		return $this->idPaisNotifica;
	}

	/**
	* Set nombrePaisNotifica
	*
	*Nombre del país que realiza la notitifcación
	*
	* @parámetro String $nombrePaisNotifica
	* @return NombrePaisNotifica
	*/
	public function setNombrePaisNotifica($nombrePaisNotifica)
	{
	  $this->nombrePaisNotifica = (String) $nombrePaisNotifica;
	    return $this;
	}

	/**
	* Get nombrePaisNotifica
	*
	* @return null|String
	*/
	public function getNombrePaisNotifica()
	{
		return $this->nombrePaisNotifica;
	}

	/**
	* Set codigoDocumento
	*
	*Código de la notificación
	*
	* @parámetro String $codigoDocumento
	* @return CodigoDocumento
	*/
	public function setCodigoDocumento($codigoDocumento)
	{
	  $this->codigoDocumento = (String) $codigoDocumento;
	    return $this;
	}

	/**
	* Get codigoDocumento
	*
	* @return null|String
	*/
	public function getCodigoDocumento()
	{
		return $this->codigoDocumento;
	}

	/**
	* Set tipoDocumento
	*
	*Tipo de documento generado
	*
	* @parámetro String $tipoDocumento
	* @return TipoDocumento
	*/
	public function setTipoDocumento($tipoDocumento)
	{
	  $this->tipoDocumento = (String) $tipoDocumento;
	    return $this;
	}

	/**
	* Get tipoDocumento
	*
	* @return null|String
	*/
	public function getTipoDocumento()
	{
		return $this->tipoDocumento;
	}

	/**
	* Set descripcion
	*
	*Descripción de la notificación
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
	* Set enlace
	*
	*Enlace donde se encuentra el documento de notificación detallado
	*
	* @parámetro String $enlace
	* @return Enlace
	*/
	public function setEnlace($enlace)
	{
	  $this->enlace = (String) $enlace;
	    return $this;
	}

	/**
	* Get enlace
	*
	* @return null|String
	*/
	public function getEnlace()
	{
		return $this->enlace;
	}

	/**
	* Set rutaArchivo
	*
	*Ruta donde se almacena el archivo
	*
	* @parámetro String $rutaArchivo
	* @return RutaArchivo
	*/
	public function setRutaArchivo($rutaArchivo)
	{
	  $this->rutaArchivo = (String) $rutaArchivo;
	    return $this;
	}

	/**
	* Get rutaArchivo
	*
	* @return null|String
	*/
	public function getRutaArchivo()
	{
		return $this->rutaArchivo;
	}

	/**
	* Set producto
	*
	*Productos relacionados a la notificación
	*
	* @parámetro String $producto
	* @return Producto
	*/
	public function setProducto($producto)
	{
	  $this->producto = (String) $producto;
	    return $this;
	}

	/**
	* Get producto
	*
	* @return null|String
	*/
	public function getProducto()
	{
		return $this->producto;
	}

	/**
	* Set palabraClave
	*
	*Palabras claves de la notificación
	*
	* @parámetro String $palabraClave
	* @return PalabraClave
	*/
	public function setPalabraClave($palabraClave)
	{
	  $this->palabraClave = (String) $palabraClave;
	    return $this;
	}

	/**
	* Get palabraClave
	*
	* @return null|String
	*/
	public function getPalabraClave()
	{
		return $this->palabraClave;
	}

	/**
	* Set fechaNotificacion
	*
	*Fecha que se realiza la notificación
	*
	* @parámetro Date $fechaNotificacion
	* @return FechaNotificacion
	*/
	public function setFechaNotificacion($fechaNotificacion)
	{
	  $this->fechaNotificacion = (String) $fechaNotificacion;
	    return $this;
	}

	/**
	* Get fechaNotificacion
	*
	* @return null|Date
	*/
	public function getFechaNotificacion()
	{
		return $this->fechaNotificacion;
	}

	/**
	* Set fechaCierre
	*
	*Fecha de cierre de la notificación será 60 días a partir de la fecha de notificación
	*
	* @parámetro Date $fechaCierre
	* @return FechaCierre
	*/
	public function setFechaCierre($fechaCierre)
	{
	  $this->fechaCierre = (String) $fechaCierre;
	    return $this;
	}

	/**
	* Get fechaCierre
	*
	* @return null|Date
	*/
	public function getFechaCierre()
	{
		return $this->fechaCierre;
	}
	/**
	 * Set comentarios
	 *
	 * @parámetro String $comentarios
	 * @return Comentarios
	 */
	public function setComentarios($comentarios)
	{
		$this->comentarios = (String) $comentarios;
		return $this;
	}
	
	/**
	 * Get comentarios
	 *
	 * @return null|String
	 */
	public function getComentarios()
	{
		return $this->comentarios;
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
	* @return NotificacionesModelo
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
