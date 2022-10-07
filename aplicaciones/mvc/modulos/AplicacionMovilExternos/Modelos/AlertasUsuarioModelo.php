<?php
 /**
 * Modelo AlertasUsuarioModelo
 *
 * Este archivo se complementa con el archivo   AlertasUsuarioLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-07
 * @uses    AlertasUsuarioModelo
 * @package AplicacionMovilExternos
 * @subpackage Modelos
 */
  namespace Agrodb\AplicacionMovilExternos\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class AlertasUsuarioModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
		protected $idAlerta;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave foránea de la tabla a_movil_externos.tipos_alerta
		*/
		protected $idTipoAlerta;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo donde el usuario describe el motivo de la alerta.
		*/
		protected $descripcion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Lugar de donde se hace la alerta
		*/
		protected $lugar;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Coordenada de latitud del lugar de la alerta
		*/
		protected $latitud;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Coordenada de longitud del lugar de donde se hace la alerta
		*/
		protected $longitud;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre de la persona que realiza la alerta
		*/
		protected $nombreUsuario;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Correo electrónico del usuario
		*/
		protected $correoUsuario;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Número de teléfono del usuario
		*/
		protected $telefono;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de registro de la usuario
		*/
		protected $fechaRegistro;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Ruta de la foto almacenada en el servidor
		*/
		protected $rutaImagen;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que determina si una alerta fue atendida por el personal de Agrocalidad siendo:
		* Nueva : Nueva alerta
		* Seguimiento : Seguimiento por parte del técnico de agrocalidad
		* Atendida: Alerta resuelta por el técnico de agrocalidad
		*/
		protected $estado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo para que el técnico de la agencia coloque una observación al revisar las alertas
		*/
		protected $observacion;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="a_movil_externos";

	/**
	* Nombre de la tabla: alertas_usuario
	* 
	 */
	Private $tabla="alertas_usuario";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_alerta";



	/**
	*Secuencia
*/
		 private $secuencial = 'a_movil_externos"."alertas_usuario_id_alerta_seq'; 



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
		throw new \Exception('Clase Modelo: AlertasUsuarioModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: AlertasUsuarioModelo. Propiedad especificada invalida: get'.$name);
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
	* Get a_movil_externos
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idAlerta
	*
	*Llave primaria de la tabla
	*
	* @parámetro Integer $idAlerta
	* @return IdAlerta
	*/
	public function setIdAlerta($idAlerta)
	{
	  $this->idAlerta = (Integer) $idAlerta;
	    return $this;
	}

	/**
	* Get idAlerta
	*
	* @return null|Integer
	*/
	public function getIdAlerta()
	{
		return $this->idAlerta;
	}

	/**
	* Set idTipoAlerta
	*
	*Llave foránea de la tabla a_movil_externos.tipos_alerta
	*
	* @parámetro Integer $idTipoAlerta
	* @return IdTipoAlerta
	*/
	public function setIdTipoAlerta($idTipoAlerta)
	{
	  $this->idTipoAlerta = (Integer) $idTipoAlerta;
	    return $this;
	}

	/**
	* Get idTipoAlerta
	*
	* @return null|Integer
	*/
	public function getIdTipoAlerta()
	{
		return $this->idTipoAlerta;
	}

	/**
	* Set descripcion
	*
	*Campo donde el usuario describe el motivo de la alerta.
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
	* Set lugar
	*
	*Lugar de donde se hace la alerta
	*
	* @parámetro String $lugar
	* @return Lugar
	*/
	public function setLugar($lugar)
	{
	  $this->lugar = (String) $lugar;
	    return $this;
	}

	/**
	* Get lugar
	*
	* @return null|String
	*/
	public function getLugar()
	{
		return $this->lugar;
	}

	/**
	* Set latitud
	*
	*Coordenada de latitud del lugar de la alerta
	*
	* @parámetro String $latitud
	* @return Latitud
	*/
	public function setLatitud($latitud)
	{
	  $this->latitud = (String) $latitud;
	    return $this;
	}

	/**
	* Get latitud
	*
	* @return null|String
	*/
	public function getLatitud()
	{
		return $this->latitud;
	}

	/**
	* Set longitud
	*
	*Coordenada de longitud del lugar de donde se hace la alerta
	*
	* @parámetro String $longitud
	* @return Longitud
	*/
	public function setLongitud($longitud)
	{
	  $this->longitud = (String) $longitud;
	    return $this;
	}

	/**
	* Get longitud
	*
	* @return null|String
	*/
	public function getLongitud()
	{
		return $this->longitud;
	}

	/**
	* Set nombreUsuario
	*
	*Nombre de la persona que realiza la alerta
	*
	* @parámetro String $nombreUsuario
	* @return NombreUsuario
	*/
	public function setNombreUsuario($nombreUsuario)
	{
	  $this->nombreUsuario = (String) $nombreUsuario;
	    return $this;
	}

	/**
	* Get nombreUsuario
	*
	* @return null|String
	*/
	public function getNombreUsuario()
	{
		return $this->nombreUsuario;
	}

	/**
	* Set correoUsuario
	*
	*Correo electrónico del usuario
	*
	* @parámetro String $correoUsuario
	* @return CorreoUsuario
	*/
	public function setCorreoUsuario($correoUsuario)
	{
	  $this->correoUsuario = (String) $correoUsuario;
	    return $this;
	}

	/**
	* Get correoUsuario
	*
	* @return null|String
	*/
	public function getCorreoUsuario()
	{
		return $this->correoUsuario;
	}

	/**
	* Set telefono
	*
	*Número de teléfono del usuario
	*
	* @parámetro String $telefono
	* @return Telefono
	*/
	public function setTelefono($telefono)
	{
	  $this->telefono = (String) $telefono;
	    return $this;
	}

	/**
	* Get telefono
	*
	* @return null|String
	*/
	public function getTelefono()
	{
		return $this->telefono;
	}

	/**
	* Set fechaRegistro
	*
	*Fecha de registro de la usuario
	*
	* @parámetro Date $fechaRegistro
	* @return FechaRegistro
	*/
	public function setFechaRegistro($fechaRegistro)
	{
	  $this->fechaRegistro = (String) $fechaRegistro;
	    return $this;
	}

	/**
	* Get fechaRegistro
	*
	* @return null|Date
	*/
	public function getFechaRegistro()
	{
		return $this->fechaRegistro;
	}

	/**
	* Set rutaImagen
	*
	*Ruta de la foto almacenada en el servidor
	*
	* @parámetro String $rutaImagen
	* @return RutaImagen
	*/
	public function setRutaImagen($rutaImagen)
	{
	  $this->rutaImagen = (String) $rutaImagen;
	    return $this;
	}

	/**
	* Get rutaImagen
	*
	* @return null|String
	*/
	public function getRutaImagen()
	{
		return $this->rutaImagen;
	}

	/**
	* Set estado
	*
	*Campo que determina si una alerta fue atendida por el personal de Agrocalidad siendo:
	* Nueva : Nueva alerta
	* Seguimiento : Seguimiento por parte del técnico de agrocalidad
	* Atendida: Alerta resuelta por el técnico de agrocalidad
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
	* Set observacion
	*
	*Campo para que el técnico de la agencia coloque una observación al revisar las alertas
	*
	* @parámetro String $observacion
	* @return Observacion
	*/
	public function setObservacion($observacion)
	{
	  $this->observacion = (String) $observacion;
	    return $this;
	}

	/**
	* Get observacion
	*
	* @return null|String
	*/
	public function getObservacion()
	{
		return $this->observacion;
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
	* @return AlertasUsuarioModelo
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
