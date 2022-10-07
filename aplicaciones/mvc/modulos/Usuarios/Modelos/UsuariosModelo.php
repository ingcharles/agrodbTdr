<?php
 /**
 * Modelo UsuariosModelo
 *
 * Este archivo se complementa con el archivo   UsuariosLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @fecha 2018-10-03
 * @uses       UsuariosModelo
 * @package usuarios
 * @subpackage Modelos
 */
  namespace Agrodb\Usuarios\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class UsuariosModelo extends ModeloBase 
{

		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Cedula de identidad o pasaporte.
		*/
		protected $identificador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre de usuario
		*/
		protected $nombreUsuario;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Contraseña del usuario
		*/
		protected $clave;
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
		protected $codigoTemporal;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $intento;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $fechaSolicitudCodigo;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $fechaModificacionClave;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $ipModificacionClave;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $validacionSri;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $observacionUsuario;
		/**
		 * @var String
		 * Campo necesario para obtener estado
		 * Campo no visible en el formulario
		 *
		 */
		protected $estadoNomenclatura;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_usuario";

	/**
	* Nombre de la tabla: usuarios
	* 
	 */
	Private $tabla="usuarios";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "identificador";



	/**
	*Secuencia
*/
		 private $secuencial = '"Usuarios_"identificador_seq'; 
    
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
		throw new \Exception('Clase Modelo: UsuariosModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: UsuariosModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_usuario
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set identificador
	*
	*Cedula de identidad o pasaporte.
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
	* Set nombreUsuario
	*
	*Nombre de usuario
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
	* Set clave
	*
	*Contraseña del usuario
	*
	* @parámetro String $clave
	* @return Clave
	*/
	public function setClave($clave)
	{
	  $this->clave = (String) $clave;
	    return $this;
	}

	/**
	* Get clave
	*
	* @return null|String
	*/
	public function getClave()
	{
		return $this->clave;
	}

	/**
	* Set estado
	*
	*Estado del usuario 1.- activo; 3.- Inactivo; 9.- Eliminado
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
	 *Get estadoNomenclatura
	 *
	 * @return null|string
	 */
	public function getEstadoNomenclatura()
	{
	    return $this->estadoNomenclatura;
	}
	
	/**
	 * Set estadoNomenclatura
	 *
	 *
	 *
	 * @parámetro string $estadoNomenclatura
	 *
	 * @return null|string
	 */
	public function setEstadoNomenclatura($estadoNomenclatura)
	{
	    $this->estadoNomenclatura = (String) $estadoNomenclatura;
	}

	/**
	* Set codigoTemporal
	*
	*Código temporal usuado para restablecer la contraseña
	*
	* @parámetro String $codigoTemporal
	* @return CodigoTemporal
	*/
	public function setCodigoTemporal($codigoTemporal)
	{
	  $this->codigoTemporal = (String) $codigoTemporal;
	    return $this;
	}

	/**
	* Get codigoTemporal
	*
	* @return null|String
	*/
	public function getCodigoTemporal()
	{
		return $this->codigoTemporal;
	}

	/**
	* Set intento
	*
	*Cantidad de intentos utilizado en el reseteo de clave
	*
	* @parámetro Integer $intento
	* @return Intento
	*/
	public function setIntento($intento)
	{
	  $this->intento = (Integer) $intento;
	    return $this;
	}

	/**
	* Get intento
	*
	* @return null|Integer
	*/
	public function getIntento()
	{
		return $this->intento;
	}

	/**
	* Set fechaSolicitudCodigo
	*
	*Fecha de solicitud de código de reseteo de clave
	*
	* @parámetro Date $fechaSolicitudCodigo
	* @return FechaSolicitudCodigo
	*/
	public function setFechaSolicitudCodigo($fechaSolicitudCodigo)
	{
	  $this->fechaSolicitudCodigo = (String) $fechaSolicitudCodigo;
	    return $this;
	}

	/**
	* Get fechaSolicitudCodigo
	*
	* @return null|Date
	*/
	public function getFechaSolicitudCodigo()
	{
		return $this->fechaSolicitudCodigo;
	}

	/**
	* Set fechaModificacionClave
	*
	*Fecha de modificación de clave
	*
	* @parámetro Date $fechaModificacionClave
	* @return FechaModificacionClave
	*/
	public function setFechaModificacionClave($fechaModificacionClave)
	{
	  $this->fechaModificacionClave = (String) $fechaModificacionClave;
	    return $this;
	}

	/**
	* Get fechaModificacionClave
	*
	* @return null|Date
	*/
	public function getFechaModificacionClave()
	{
		return $this->fechaModificacionClave;
	}

	/**
	* Set ipModificacionClave
	*
	*Ip desde la cual se realizo el cambio de clave
	*
	* @parámetro String $ipModificacionClave
	* @return IpModificacionClave
	*/
	public function setIpModificacionClave($ipModificacionClave)
	{
	  $this->ipModificacionClave = (String) $ipModificacionClave;
	    return $this;
	}

	/**
	* Get ipModificacionClave
	*
	* @return null|String
	*/
	public function getIpModificacionClave()
	{
		return $this->ipModificacionClave;
	}

	/**
	* Set validacionSri
	*
	*Campo que indica si el usuario se encuentra en base de datos del SRI o Registro civil
	*
	* @parámetro String $validacionSri
	* @return ValidacionSri
	*/
	public function setValidacionSri($validacionSri)
	{
	  $this->validacionSri = (String) $validacionSri;
	    return $this;
	}

	/**
	* Get validacionSri
	*
	* @return null|String
	*/
	public function getValidacionSri()
	{
		return $this->validacionSri;
	}

	/**
	* Set observacionUsuario
	*
	*Observación para seguimiento de auditoria
	*
	* @parámetro String $observacionUsuario
	* @return ObservacionUsuario
	*/
	public function setObservacionUsuario($observacionUsuario)
	{
        $this->observacionUsuario = (String) $observacionUsuario;
        $this->observacionUsuario = ValidarDatos::validarAlfaEsp($observacionUsuario, $this->tabla, " Observacion", self::NO_REQUERIDO, 512);
	    return $this;
	}

	/**
	* Get observacionUsuario
	*
	* @return null|String
	*/
	public function getObservacionUsuario()
	{
		return $this->observacionUsuario;
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
		 return parent::actualizar($datos, $this->clavePrimaria . " = " . "'$id'");
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
	* @return UsuariosModelo
	*/
	public function buscar($id)
	{
		return $this->setOptions(parent::buscar($this->clavePrimaria . " = " . "'$id'"));
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
