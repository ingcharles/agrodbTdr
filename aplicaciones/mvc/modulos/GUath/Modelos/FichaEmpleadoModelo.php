<?php
 /**
 * Modelo FichaEmpleadoModelo
 *
 * Este archivo se complementa con el archivo   FichaEmpleadoLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       FichaEmpleadoModelo
 * @package Laboratorios
 * @subpackage Modelo
 */
  namespace Agrodb\GUath\Modelos;
  
  use Agrodb\Core\ModeloBase;
 
class FichaEmpleadoModelo extends ModeloBase 
{

		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $identificador;
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
		protected $apellido;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $tipoDocumento;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $nacionalidad;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $genero;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $estadoCivil;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $cedulaMilitar;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $fechaNacimiento;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $edad;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $tipoSangre;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $identificacionEtnica;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $nacionalidadIndigena;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $fechaModificacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $fotografia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $estadoEmpleado;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $extension;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $domicilio;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $convencional;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $celular;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $mailPersonal;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $mailInstitucional;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $extensionMagap;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $tieneDiscapacidad;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $carnetConadisEmpleado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $representanteFamiliarDiscapacidad;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $carnetConadisFamiliar;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idLocalizacionParroquia;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idLocalizacionProvincia;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idLocalizacionCanton;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $tieneEnfermedadCatastrofica;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $nombreEnfermedadCatastrofica;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $tipoEmpleado;

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_uath";

	/**
	* Nombre de la tabla: ficha_empleado
	* 
	 */
	Private $tabla="ficha_empleado";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "identificador";



	/**
	*Secuencia
*/
		 private $secuencial = ""; 



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
		throw new \Exception('Clase Modelo: FichaEmpleadoModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: FichaEmpleadoModelo. Propiedad especificada invalida: get'.$name);
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
	}
	}
	return $this;
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
	* Get g_uath
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
	*
	*
	* @parámetro String $identificador
	* @return Identificador
	*/
	public function setIdentificador($identificador)
	{
	 if(empty($identificador)) 
 {
	 $identificador="No informa";
 }
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
	* Set nombre
	*
	*
	*
	* @parámetro String $nombre
	* @return Nombre
	*/
	public function setNombre($nombre)
	{
	 if(empty($nombre)) 
 {
	 $nombre="No informa";
 }
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
	* Set apellido
	*
	*
	*
	* @parámetro String $apellido
	* @return Apellido
	*/
	public function setApellido($apellido)
	{
	 if(empty($apellido)) 
 {
	 $apellido="No informa";
 }
	  $this->apellido = (String) $apellido;
	    return $this;
	}

	/**
	* Get apellido
	*
	* @return null|String
	*/
	public function getApellido()
	{
		return $this->apellido;
	}

	/**
	* Set tipoDocumento
	*
	*
	*
	* @parámetro String $tipoDocumento
	* @return TipoDocumento
	*/
	public function setTipoDocumento($tipoDocumento)
	{
	 if(empty($tipoDocumento)) 
 {
	 $tipoDocumento="No informa";
 }
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
	* Set nacionalidad
	*
	*
	*
	* @parámetro String $nacionalidad
	* @return Nacionalidad
	*/
	public function setNacionalidad($nacionalidad)
	{
	 if(empty($nacionalidad)) 
 {
	 $nacionalidad="No informa";
 }
	  $this->nacionalidad = (String) $nacionalidad;
	    return $this;
	}

	/**
	* Get nacionalidad
	*
	* @return null|String
	*/
	public function getNacionalidad()
	{
		return $this->nacionalidad;
	}

	/**
	* Set genero
	*
	*
	*
	* @parámetro String $genero
	* @return Genero
	*/
	public function setGenero($genero)
	{
	 if(empty($genero)) 
 {
	 $genero="No informa";
 }
	  $this->genero = (String) $genero;
	    return $this;
	}

	/**
	* Get genero
	*
	* @return null|String
	*/
	public function getGenero()
	{
		return $this->genero;
	}

	/**
	* Set estadoCivil
	*
	*
	*
	* @parámetro String $estadoCivil
	* @return EstadoCivil
	*/
	public function setEstadoCivil($estadoCivil)
	{
	 if(empty($estadoCivil)) 
 {
	 $estadoCivil="No informa";
 }
	  $this->estadoCivil = (String) $estadoCivil;
	    return $this;
	}

	/**
	* Get estadoCivil
	*
	* @return null|String
	*/
	public function getEstadoCivil()
	{
		return $this->estadoCivil;
	}

	/**
	* Set cedulaMilitar
	*
	*
	*
	* @parámetro String $cedulaMilitar
	* @return CedulaMilitar
	*/
	public function setCedulaMilitar($cedulaMilitar)
	{
	 if(empty($cedulaMilitar)) 
 {
	 $cedulaMilitar="No informa";
 }
	  $this->cedulaMilitar = (String) $cedulaMilitar;
	    return $this;
	}

	/**
	* Get cedulaMilitar
	*
	* @return null|String
	*/
	public function getCedulaMilitar()
	{
		return $this->cedulaMilitar;
	}

	/**
	* Set fechaNacimiento
	*
	*
	*
	* @parámetro Date $fechaNacimiento
	* @return FechaNacimiento
	*/
	public function setFechaNacimiento($fechaNacimiento)
	{
	 if(empty($fechaNacimiento)) 
 {
	 $fechaNacimiento="No informa";
 }
	  $this->fechaNacimiento = (String) $fechaNacimiento;
	    return $this;
	}

	/**
	* Get fechaNacimiento
	*
	* @return null|Date
	*/
	public function getFechaNacimiento()
	{
		return $this->fechaNacimiento;
	}

	/**
	* Set edad
	*
	*
	*
	* @parámetro Integer $edad
	* @return Edad
	*/
	public function setEdad($edad)
	{
	 if(empty($edad)) 
 {
	 $edad="No informa";
 }
	  $this->edad = (Integer) $edad;
	    return $this;
	}

	/**
	* Get edad
	*
	* @return null|Integer
	*/
	public function getEdad()
	{
		return $this->edad;
	}

	/**
	* Set tipoSangre
	*
	*
	*
	* @parámetro String $tipoSangre
	* @return TipoSangre
	*/
	public function setTipoSangre($tipoSangre)
	{
	 if(empty($tipoSangre)) 
 {
	 $tipoSangre="No informa";
 }
	  $this->tipoSangre = (String) $tipoSangre;
	    return $this;
	}

	/**
	* Get tipoSangre
	*
	* @return null|String
	*/
	public function getTipoSangre()
	{
		return $this->tipoSangre;
	}

	/**
	* Set identificacionEtnica
	*
	*
	*
	* @parámetro String $identificacionEtnica
	* @return IdentificacionEtnica
	*/
	public function setIdentificacionEtnica($identificacionEtnica)
	{
	 if(empty($identificacionEtnica)) 
 {
	 $identificacionEtnica="No informa";
 }
	  $this->identificacionEtnica = (String) $identificacionEtnica;
	    return $this;
	}

	/**
	* Get identificacionEtnica
	*
	* @return null|String
	*/
	public function getIdentificacionEtnica()
	{
		return $this->identificacionEtnica;
	}

	/**
	* Set nacionalidadIndigena
	*
	*
	*
	* @parámetro String $nacionalidadIndigena
	* @return NacionalidadIndigena
	*/
	public function setNacionalidadIndigena($nacionalidadIndigena)
	{
	 if(empty($nacionalidadIndigena)) 
 {
	 $nacionalidadIndigena="No informa";
 }
	  $this->nacionalidadIndigena = (String) $nacionalidadIndigena;
	    return $this;
	}

	/**
	* Get nacionalidadIndigena
	*
	* @return null|String
	*/
	public function getNacionalidadIndigena()
	{
		return $this->nacionalidadIndigena;
	}

	/**
	* Set fechaModificacion
	*
	*
	*
	* @parámetro Date $fechaModificacion
	* @return FechaModificacion
	*/
	public function setFechaModificacion($fechaModificacion)
	{
	 if(empty($fechaModificacion)) 
 {
	 $fechaModificacion="No informa";
 }
	  $this->fechaModificacion = (String) $fechaModificacion;
	    return $this;
	}

	/**
	* Get fechaModificacion
	*
	* @return null|Date
	*/
	public function getFechaModificacion()
	{
		return $this->fechaModificacion;
	}

	/**
	* Set fotografia
	*
	*
	*
	* @parámetro String $fotografia
	* @return Fotografia
	*/
	public function setFotografia($fotografia)
	{
	 if(empty($fotografia)) 
 {
	 $fotografia="No informa";
 }
	  $this->fotografia = (String) $fotografia;
	    return $this;
	}

	/**
	* Get fotografia
	*
	* @return null|String
	*/
	public function getFotografia()
	{
		return $this->fotografia;
	}

	/**
	* Set estadoEmpleado
	*
	*
	*
	* @parámetro String $estadoEmpleado
	* @return EstadoEmpleado
	*/
	public function setEstadoEmpleado($estadoEmpleado)
	{
	 if(empty($estadoEmpleado)) 
 {
	 $estadoEmpleado="No informa";
 }
	  $this->estadoEmpleado = (String) $estadoEmpleado;
	    return $this;
	}

	/**
	* Get estadoEmpleado
	*
	* @return null|String
	*/
	public function getEstadoEmpleado()
	{
		return $this->estadoEmpleado;
	}

	/**
	* Set extension
	*
	*
	*
	* @parámetro Integer $extension
	* @return Extension
	*/
	public function setExtension($extension)
	{
	 if(empty($extension)) 
 {
	 $extension="No informa";
 }
	  $this->extension = (Integer) $extension;
	    return $this;
	}

	/**
	* Get extension
	*
	* @return null|Integer
	*/
	public function getExtension()
	{
		return $this->extension;
	}

	/**
	* Set domicilio
	*
	*
	*
	* @parámetro String $domicilio
	* @return Domicilio
	*/
	public function setDomicilio($domicilio)
	{
	 if(empty($domicilio)) 
 {
	 $domicilio="No informa";
 }
	  $this->domicilio = (String) $domicilio;
	    return $this;
	}

	/**
	* Get domicilio
	*
	* @return null|String
	*/
	public function getDomicilio()
	{
		return $this->domicilio;
	}

	/**
	* Set convencional
	*
	*
	*
	* @parámetro String $convencional
	* @return Convencional
	*/
	public function setConvencional($convencional)
	{
	 if(empty($convencional)) 
 {
	 $convencional="No informa";
 }
	  $this->convencional = (String) $convencional;
	    return $this;
	}

	/**
	* Get convencional
	*
	* @return null|String
	*/
	public function getConvencional()
	{
		return $this->convencional;
	}

	/**
	* Set celular
	*
	*
	*
	* @parámetro String $celular
	* @return Celular
	*/
	public function setCelular($celular)
	{
	 if(empty($celular)) 
 {
	 $celular="No informa";
 }
	  $this->celular = (String) $celular;
	    return $this;
	}

	/**
	* Get celular
	*
	* @return null|String
	*/
	public function getCelular()
	{
		return $this->celular;
	}

	/**
	* Set mailPersonal
	*
	*
	*
	* @parámetro String $mailPersonal
	* @return MailPersonal
	*/
	public function setMailPersonal($mailPersonal)
	{
	 if(empty($mailPersonal)) 
 {
	 $mailPersonal="No informa";
 }
	  $this->mailPersonal = (String) $mailPersonal;
	    return $this;
	}

	/**
	* Get mailPersonal
	*
	* @return null|String
	*/
	public function getMailPersonal()
	{
		return $this->mailPersonal;
	}

	/**
	* Set mailInstitucional
	*
	*
	*
	* @parámetro String $mailInstitucional
	* @return MailInstitucional
	*/
	public function setMailInstitucional($mailInstitucional)
	{
	 if(empty($mailInstitucional)) 
 {
	 $mailInstitucional="No informa";
 }
	  $this->mailInstitucional = (String) $mailInstitucional;
	    return $this;
	}

	/**
	* Get mailInstitucional
	*
	* @return null|String
	*/
	public function getMailInstitucional()
	{
		return $this->mailInstitucional;
	}

	/**
	* Set extensionMagap
	*
	*
	*
	* @parámetro String $extensionMagap
	* @return ExtensionMagap
	*/
	public function setExtensionMagap($extensionMagap)
	{
	 if(empty($extensionMagap)) 
 {
	 $extensionMagap="No informa";
 }
	  $this->extensionMagap = (String) $extensionMagap;
	    return $this;
	}

	/**
	* Get extensionMagap
	*
	* @return null|String
	*/
	public function getExtensionMagap()
	{
		return $this->extensionMagap;
	}

	/**
	* Set tieneDiscapacidad
	*
	*
	*
	* @parámetro String $tieneDiscapacidad
	* @return TieneDiscapacidad
	*/
	public function setTieneDiscapacidad($tieneDiscapacidad)
	{
	 if(empty($tieneDiscapacidad)) 
 {
	 $tieneDiscapacidad="No informa";
 }
	  $this->tieneDiscapacidad = (String) $tieneDiscapacidad;
	    return $this;
	}

	/**
	* Get tieneDiscapacidad
	*
	* @return null|String
	*/
	public function getTieneDiscapacidad()
	{
		return $this->tieneDiscapacidad;
	}

	/**
	* Set carnetConadisEmpleado
	*
	*
	*
	* @parámetro String $carnetConadisEmpleado
	* @return CarnetConadisEmpleado
	*/
	public function setCarnetConadisEmpleado($carnetConadisEmpleado)
	{
	 if(empty($carnetConadisEmpleado)) 
 {
	 $carnetConadisEmpleado="No informa";
 }
	  $this->carnetConadisEmpleado = (String) $carnetConadisEmpleado;
	    return $this;
	}

	/**
	* Get carnetConadisEmpleado
	*
	* @return null|String
	*/
	public function getCarnetConadisEmpleado()
	{
		return $this->carnetConadisEmpleado;
	}

	/**
	* Set representanteFamiliarDiscapacidad
	*
	*
	*
	* @parámetro String $representanteFamiliarDiscapacidad
	* @return RepresentanteFamiliarDiscapacidad
	*/
	public function setRepresentanteFamiliarDiscapacidad($representanteFamiliarDiscapacidad)
	{
	 if(empty($representanteFamiliarDiscapacidad)) 
 {
	 $representanteFamiliarDiscapacidad="No informa";
 }
	  $this->representanteFamiliarDiscapacidad = (String) $representanteFamiliarDiscapacidad;
	    return $this;
	}

	/**
	* Get representanteFamiliarDiscapacidad
	*
	* @return null|String
	*/
	public function getRepresentanteFamiliarDiscapacidad()
	{
		return $this->representanteFamiliarDiscapacidad;
	}

	/**
	* Set carnetConadisFamiliar
	*
	*
	*
	* @parámetro String $carnetConadisFamiliar
	* @return CarnetConadisFamiliar
	*/
	public function setCarnetConadisFamiliar($carnetConadisFamiliar)
	{
	 if(empty($carnetConadisFamiliar)) 
 {
	 $carnetConadisFamiliar="No informa";
 }
	  $this->carnetConadisFamiliar = (String) $carnetConadisFamiliar;
	    return $this;
	}

	/**
	* Get carnetConadisFamiliar
	*
	* @return null|String
	*/
	public function getCarnetConadisFamiliar()
	{
		return $this->carnetConadisFamiliar;
	}

	/**
	* Set idLocalizacionParroquia
	*
	*
	*
	* @parámetro Integer $idLocalizacionParroquia
	* @return IdLocalizacionParroquia
	*/
	public function setIdLocalizacionParroquia($idLocalizacionParroquia)
	{
	 if(empty($idLocalizacionParroquia)) 
 {
	 $idLocalizacionParroquia="No informa";
 }
	  $this->idLocalizacionParroquia = (Integer) $idLocalizacionParroquia;
	    return $this;
	}

	/**
	* Get idLocalizacionParroquia
	*
	* @return null|Integer
	*/
	public function getIdLocalizacionParroquia()
	{
		return $this->idLocalizacionParroquia;
	}

	/**
	* Set idLocalizacionProvincia
	*
	*
	*
	* @parámetro Integer $idLocalizacionProvincia
	* @return IdLocalizacionProvincia
	*/
	public function setIdLocalizacionProvincia($idLocalizacionProvincia)
	{
	 if(empty($idLocalizacionProvincia)) 
 {
	 $idLocalizacionProvincia="No informa";
 }
	  $this->idLocalizacionProvincia = (Integer) $idLocalizacionProvincia;
	    return $this;
	}

	/**
	* Get idLocalizacionProvincia
	*
	* @return null|Integer
	*/
	public function getIdLocalizacionProvincia()
	{
		return $this->idLocalizacionProvincia;
	}

	/**
	* Set idLocalizacionCanton
	*
	*
	*
	* @parámetro Integer $idLocalizacionCanton
	* @return IdLocalizacionCanton
	*/
	public function setIdLocalizacionCanton($idLocalizacionCanton)
	{
	 if(empty($idLocalizacionCanton)) 
 {
	 $idLocalizacionCanton="No informa";
 }
	  $this->idLocalizacionCanton = (Integer) $idLocalizacionCanton;
	    return $this;
	}

	/**
	* Get idLocalizacionCanton
	*
	* @return null|Integer
	*/
	public function getIdLocalizacionCanton()
	{
		return $this->idLocalizacionCanton;
	}

	/**
	* Set tieneEnfermedadCatastrofica
	*
	*
	*
	* @parámetro String $tieneEnfermedadCatastrofica
	* @return TieneEnfermedadCatastrofica
	*/
	public function setTieneEnfermedadCatastrofica($tieneEnfermedadCatastrofica)
	{
	 if(empty($tieneEnfermedadCatastrofica)) 
 {
	 $tieneEnfermedadCatastrofica="No informa";
 }
	  $this->tieneEnfermedadCatastrofica = (String) $tieneEnfermedadCatastrofica;
	    return $this;
	}

	/**
	* Get tieneEnfermedadCatastrofica
	*
	* @return null|String
	*/
	public function getTieneEnfermedadCatastrofica()
	{
		return $this->tieneEnfermedadCatastrofica;
	}

	/**
	* Set nombreEnfermedadCatastrofica
	*
	*
	*
	* @parámetro String $nombreEnfermedadCatastrofica
	* @return NombreEnfermedadCatastrofica
	*/
	public function setNombreEnfermedadCatastrofica($nombreEnfermedadCatastrofica)
	{
	 if(empty($nombreEnfermedadCatastrofica)) 
 {
	 $nombreEnfermedadCatastrofica="No informa";
 }
	  $this->nombreEnfermedadCatastrofica = (String) $nombreEnfermedadCatastrofica;
	    return $this;
	}

	/**
	* Get nombreEnfermedadCatastrofica
	*
	* @return null|String
	*/
	public function getNombreEnfermedadCatastrofica()
	{
		return $this->nombreEnfermedadCatastrofica;
	}

	/**
	* Set tipoEmpleado
	*
	*
	*
	* @parámetro String $tipoEmpleado
	* @return TipoEmpleado
	*/
	public function setTipoEmpleado($tipoEmpleado)
	{
	 if(empty($tipoEmpleado)) 
 {
	 $tipoEmpleado="No informa";
 }
	  $this->tipoEmpleado = (String) $tipoEmpleado;
	    return $this;
	}

	/**
	* Get tipoEmpleado
	*
	* @return null|String
	*/
	public function getTipoEmpleado()
	{
		return $this->tipoEmpleado;
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
	* @return FichaEmpleadoModelo
	*/
	public function buscar($id)
	{
		return $this->setOptions(parent::buscar($this->clavePrimaria . " = '" . $id."'"));
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
