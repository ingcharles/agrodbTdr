<?php
 /**
 * Modelo Vigilanciaf02Modelo
 *
 * Este archivo se complementa con el archivo   Vigilanciaf02LogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021/03/10
 * @uses    Vigilanciaf02Modelo
 * @package AplicacionMovilInternos
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class Vigilanciaf02Modelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $id;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idTablet;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Código provincia
		*/
		protected $codigoProvincia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Provincia
		*/
		protected $nombreProvincia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Código cantón
		*/
		protected $codigoCanton;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Cantón
		*/
		protected $nombreCanton;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Código parroquia
		*/
		protected $codigoParroquia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Parroquia
		*/
		protected $nombreParroquia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Propietario/Finca
		*/
		protected $nombrePropietarioFinca;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Localidad/Vía
		*/
		protected $localidadVia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* X
		*/
		protected $coordenadaX;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Y
		*/
		protected $coordenadaY;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Z
		*/
		protected $coordenadaZ;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Denuncia fitosanitaria
		*/
		protected $denunciaFitosanitaria;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre de denunciante
		*/
		protected $nombreDenunciante;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Teléfono de denunciante
		*/
		protected $telefonoDenunciante;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Dirección de denunciante
		*/
		protected $direccionDenunciante;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Correo electrónico de denunciante
		*/
		protected $correoElectronicoDenunciante;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Especie vegetal
		*/
		protected $especieVegetal;
		/**
		* @var Decimal
		* Campo requerido
		* Campo visible en el formulario
		* Cantidad total de especie
		*/
		protected $cantidadTotal;
		/**
		* @var Decimal
		* Campo requerido
		* Campo visible en el formulario
		* Cantidad vigilada de especie
		*/
		protected $cantidadVigilada;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Unidad
		*/
		protected $unidad;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Sitio de operación
		*/
		protected $sitioOperacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Condición de la producción
		*/
		protected $condicionProduccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Etapa de cultivo
		*/
		protected $etapaCultivo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Actividad
		*/
		protected $actividad;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Manejo del sitio de operacióin
		*/
		protected $manejoSitioOperacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Ausencia de plaga
		*/
		protected $ausenciaPlaga;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Plaga/Diagnóstico visual o prediagnostico
		*/
		protected $plagaDiagnosticoVisualPrediagnostico;
		/**
		* @var Decimal
		* Campo requerido
		* Campo visible en el formulario
		* Cantidad afectada
		*/
		protected $cantidadAfectada;
		/**
		* @var Decimal
		* Campo requerido
		* Campo visible en el formulario
		* % de incidencia
		*/
		protected $porcentajeIncidencia;
		/**
		* @var Decimal
		* Campo requerido
		* Campo visible en el formulario
		* % de severidad
		*/
		protected $porcentajeSeveridad;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Tipo de plaga
		*/
		protected $tipoPlaga;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Fase de desarrollo de plaga
		*/
		protected $faseDesarrolloPlaga;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Órgano afectado
		*/
		protected $organoAfectado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Distribución de la plaga
		*/
		protected $distribucionPlaga;
		/**
		* @var Decimal
		* Campo requerido
		* Campo visible en el formulario
		* Población
		*/
		protected $poblacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Diagnóstico visual
		*/
		protected $diagnosticoVisual;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Descripción de síntomas
		*/
		protected $descripcionSintomasP;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Envío de muestra
		*/
		protected $envioMuestra;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Observaciones
		*/
		protected $observaciones;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de inspección
		*/
		protected $fechaInspeccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Indentificación de inspector
		*/
		protected $usuarioId;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Inspector
		*/
		protected $usuario;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $tabletId;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $tabletVersionBase;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $fechaIngresoGuia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $estadoVf02;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $observacionVf02;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="f_inspeccion";

	/**
	* Nombre de la tabla: vigilanciaf02
	* 
	 */
	Private $tabla="vigilanciaf02";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id";



	/**
	*Secuencia
*/
		 private $secuencial = 'f_inspeccion"."vigilanciaf02_id_seq'; 



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
		throw new \Exception('Clase Modelo: Vigilanciaf02Modelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: Vigilanciaf02Modelo. Propiedad especificada invalida: get'.$name);
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
	* Get f_inspeccion
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set id
	*
	*
	*
	* @parámetro Integer $id
	* @return Id
	*/
	public function setId($id)
	{
	  $this->id = (Integer) $id;
	    return $this;
	}

	/**
	* Get id
	*
	* @return null|Integer
	*/
	public function getId()
	{
		return $this->id;
	}

	/**
	* Set idTablet
	*
	*
	*
	* @parámetro Integer $idTablet
	* @return IdTablet
	*/
	public function setIdTablet($idTablet)
	{
	  $this->idTablet = (Integer) $idTablet;
	    return $this;
	}

	/**
	* Get idTablet
	*
	* @return null|Integer
	*/
	public function getIdTablet()
	{
		return $this->idTablet;
	}

	/**
	* Set codigoProvincia
	*
	*Código provincia
	*
	* @parámetro String $codigoProvincia
	* @return CodigoProvincia
	*/
	public function setCodigoProvincia($codigoProvincia)
	{
	  $this->codigoProvincia = (String) $codigoProvincia;
	    return $this;
	}

	/**
	* Get codigoProvincia
	*
	* @return null|String
	*/
	public function getCodigoProvincia()
	{
		return $this->codigoProvincia;
	}

	/**
	* Set nombreProvincia
	*
	*Provincia
	*
	* @parámetro String $nombreProvincia
	* @return NombreProvincia
	*/
	public function setNombreProvincia($nombreProvincia)
	{
	  $this->nombreProvincia = (String) $nombreProvincia;
	    return $this;
	}

	/**
	* Get nombreProvincia
	*
	* @return null|String
	*/
	public function getNombreProvincia()
	{
		return $this->nombreProvincia;
	}

	/**
	* Set codigoCanton
	*
	*Código cantón
	*
	* @parámetro String $codigoCanton
	* @return CodigoCanton
	*/
	public function setCodigoCanton($codigoCanton)
	{
	  $this->codigoCanton = (String) $codigoCanton;
	    return $this;
	}

	/**
	* Get codigoCanton
	*
	* @return null|String
	*/
	public function getCodigoCanton()
	{
		return $this->codigoCanton;
	}

	/**
	* Set nombreCanton
	*
	*Cantón
	*
	* @parámetro String $nombreCanton
	* @return NombreCanton
	*/
	public function setNombreCanton($nombreCanton)
	{
	  $this->nombreCanton = (String) $nombreCanton;
	    return $this;
	}

	/**
	* Get nombreCanton
	*
	* @return null|String
	*/
	public function getNombreCanton()
	{
		return $this->nombreCanton;
	}

	/**
	* Set codigoParroquia
	*
	*Código parroquia
	*
	* @parámetro String $codigoParroquia
	* @return CodigoParroquia
	*/
	public function setCodigoParroquia($codigoParroquia)
	{
	  $this->codigoParroquia = (String) $codigoParroquia;
	    return $this;
	}

	/**
	* Get codigoParroquia
	*
	* @return null|String
	*/
	public function getCodigoParroquia()
	{
		return $this->codigoParroquia;
	}

	/**
	* Set nombreParroquia
	*
	*Parroquia
	*
	* @parámetro String $nombreParroquia
	* @return NombreParroquia
	*/
	public function setNombreParroquia($nombreParroquia)
	{
	  $this->nombreParroquia = (String) $nombreParroquia;
	    return $this;
	}

	/**
	* Get nombreParroquia
	*
	* @return null|String
	*/
	public function getNombreParroquia()
	{
		return $this->nombreParroquia;
	}

	/**
	* Set nombrePropietarioFinca
	*
	*Propietario/Finca
	*
	* @parámetro String $nombrePropietarioFinca
	* @return NombrePropietarioFinca
	*/
	public function setNombrePropietarioFinca($nombrePropietarioFinca)
	{
	  $this->nombrePropietarioFinca = (String) $nombrePropietarioFinca;
	    return $this;
	}

	/**
	* Get nombrePropietarioFinca
	*
	* @return null|String
	*/
	public function getNombrePropietarioFinca()
	{
		return $this->nombrePropietarioFinca;
	}

	/**
	* Set localidadVia
	*
	*Localidad/Vía
	*
	* @parámetro String $localidadVia
	* @return LocalidadVia
	*/
	public function setLocalidadVia($localidadVia)
	{
	  $this->localidadVia = (String) $localidadVia;
	    return $this;
	}

	/**
	* Get localidadVia
	*
	* @return null|String
	*/
	public function getLocalidadVia()
	{
		return $this->localidadVia;
	}

	/**
	* Set coordenadaX
	*
	*X
	*
	* @parámetro String $coordenadaX
	* @return CoordenadaX
	*/
	public function setCoordenadaX($coordenadaX)
	{
	  $this->coordenadaX = (String) $coordenadaX;
	    return $this;
	}

	/**
	* Get coordenadaX
	*
	* @return null|String
	*/
	public function getCoordenadaX()
	{
		return $this->coordenadaX;
	}

	/**
	* Set coordenadaY
	*
	*Y
	*
	* @parámetro String $coordenadaY
	* @return CoordenadaY
	*/
	public function setCoordenadaY($coordenadaY)
	{
	  $this->coordenadaY = (String) $coordenadaY;
	    return $this;
	}

	/**
	* Get coordenadaY
	*
	* @return null|String
	*/
	public function getCoordenadaY()
	{
		return $this->coordenadaY;
	}

	/**
	* Set coordenadaZ
	*
	*Z
	*
	* @parámetro String $coordenadaZ
	* @return CoordenadaZ
	*/
	public function setCoordenadaZ($coordenadaZ)
	{
	  $this->coordenadaZ = (String) $coordenadaZ;
	    return $this;
	}

	/**
	* Get coordenadaZ
	*
	* @return null|String
	*/
	public function getCoordenadaZ()
	{
		return $this->coordenadaZ;
	}

	/**
	* Set denunciaFitosanitaria
	*
	*Denuncia fitosanitaria
	*
	* @parámetro String $denunciaFitosanitaria
	* @return DenunciaFitosanitaria
	*/
	public function setDenunciaFitosanitaria($denunciaFitosanitaria)
	{
	  $this->denunciaFitosanitaria = (String) $denunciaFitosanitaria;
	    return $this;
	}

	/**
	* Get denunciaFitosanitaria
	*
	* @return null|String
	*/
	public function getDenunciaFitosanitaria()
	{
		return $this->denunciaFitosanitaria;
	}

	/**
	* Set nombreDenunciante
	*
	*Nombre de denunciante
	*
	* @parámetro String $nombreDenunciante
	* @return NombreDenunciante
	*/
	public function setNombreDenunciante($nombreDenunciante)
	{
	  $this->nombreDenunciante = (String) $nombreDenunciante;
	    return $this;
	}

	/**
	* Get nombreDenunciante
	*
	* @return null|String
	*/
	public function getNombreDenunciante()
	{
		return $this->nombreDenunciante;
	}

	/**
	* Set telefonoDenunciante
	*
	*Teléfono de denunciante
	*
	* @parámetro String $telefonoDenunciante
	* @return TelefonoDenunciante
	*/
	public function setTelefonoDenunciante($telefonoDenunciante)
	{
	  $this->telefonoDenunciante = (String) $telefonoDenunciante;
	    return $this;
	}

	/**
	* Get telefonoDenunciante
	*
	* @return null|String
	*/
	public function getTelefonoDenunciante()
	{
		return $this->telefonoDenunciante;
	}

	/**
	* Set direccionDenunciante
	*
	*Dirección de denunciante
	*
	* @parámetro String $direccionDenunciante
	* @return DireccionDenunciante
	*/
	public function setDireccionDenunciante($direccionDenunciante)
	{
	  $this->direccionDenunciante = (String) $direccionDenunciante;
	    return $this;
	}

	/**
	* Get direccionDenunciante
	*
	* @return null|String
	*/
	public function getDireccionDenunciante()
	{
		return $this->direccionDenunciante;
	}

	/**
	* Set correoElectronicoDenunciante
	*
	*Correo electrónico de denunciante
	*
	* @parámetro String $correoElectronicoDenunciante
	* @return CorreoElectronicoDenunciante
	*/
	public function setCorreoElectronicoDenunciante($correoElectronicoDenunciante)
	{
	  $this->correoElectronicoDenunciante = (String) $correoElectronicoDenunciante;
	    return $this;
	}

	/**
	* Get correoElectronicoDenunciante
	*
	* @return null|String
	*/
	public function getCorreoElectronicoDenunciante()
	{
		return $this->correoElectronicoDenunciante;
	}

	/**
	* Set especieVegetal
	*
	*Especie vegetal
	*
	* @parámetro String $especieVegetal
	* @return EspecieVegetal
	*/
	public function setEspecieVegetal($especieVegetal)
	{
	  $this->especieVegetal = (String) $especieVegetal;
	    return $this;
	}

	/**
	* Get especieVegetal
	*
	* @return null|String
	*/
	public function getEspecieVegetal()
	{
		return $this->especieVegetal;
	}

	/**
	* Set cantidadTotal
	*
	*Cantidad total de especie
	*
	* @parámetro Decimal $cantidadTotal
	* @return CantidadTotal
	*/
	public function setCantidadTotal($cantidadTotal)
	{
	  $this->cantidadTotal = (Double) $cantidadTotal;
	    return $this;
	}

	/**
	* Get cantidadTotal
	*
	* @return null|Decimal
	*/
	public function getCantidadTotal()
	{
		return $this->cantidadTotal;
	}

	/**
	* Set cantidadVigilada
	*
	*Cantidad vigilada de especie
	*
	* @parámetro Decimal $cantidadVigilada
	* @return CantidadVigilada
	*/
	public function setCantidadVigilada($cantidadVigilada)
	{
	  $this->cantidadVigilada = (Double) $cantidadVigilada;
	    return $this;
	}

	/**
	* Get cantidadVigilada
	*
	* @return null|Decimal
	*/
	public function getCantidadVigilada()
	{
		return $this->cantidadVigilada;
	}

	/**
	* Set unidad
	*
	*Unidad
	*
	* @parámetro String $unidad
	* @return Unidad
	*/
	public function setUnidad($unidad)
	{
	  $this->unidad = (String) $unidad;
	    return $this;
	}

	/**
	* Get unidad
	*
	* @return null|String
	*/
	public function getUnidad()
	{
		return $this->unidad;
	}

	/**
	* Set sitioOperacion
	*
	*Sitio de operación
	*
	* @parámetro String $sitioOperacion
	* @return SitioOperacion
	*/
	public function setSitioOperacion($sitioOperacion)
	{
	  $this->sitioOperacion = (String) $sitioOperacion;
	    return $this;
	}

	/**
	* Get sitioOperacion
	*
	* @return null|String
	*/
	public function getSitioOperacion()
	{
		return $this->sitioOperacion;
	}

	/**
	* Set condicionProduccion
	*
	*Condición de la producción
	*
	* @parámetro String $condicionProduccion
	* @return CondicionProduccion
	*/
	public function setCondicionProduccion($condicionProduccion)
	{
	  $this->condicionProduccion = (String) $condicionProduccion;
	    return $this;
	}

	/**
	* Get condicionProduccion
	*
	* @return null|String
	*/
	public function getCondicionProduccion()
	{
		return $this->condicionProduccion;
	}

	/**
	* Set etapaCultivo
	*
	*Etapa de cultivo
	*
	* @parámetro String $etapaCultivo
	* @return EtapaCultivo
	*/
	public function setEtapaCultivo($etapaCultivo)
	{
	  $this->etapaCultivo = (String) $etapaCultivo;
	    return $this;
	}

	/**
	* Get etapaCultivo
	*
	* @return null|String
	*/
	public function getEtapaCultivo()
	{
		return $this->etapaCultivo;
	}

	/**
	* Set actividad
	*
	*Actividad
	*
	* @parámetro String $actividad
	* @return Actividad
	*/
	public function setActividad($actividad)
	{
	  $this->actividad = (String) $actividad;
	    return $this;
	}

	/**
	* Get actividad
	*
	* @return null|String
	*/
	public function getActividad()
	{
		return $this->actividad;
	}

	/**
	* Set manejoSitioOperacion
	*
	*Manejo del sitio de operacióin
	*
	* @parámetro String $manejoSitioOperacion
	* @return ManejoSitioOperacion
	*/
	public function setManejoSitioOperacion($manejoSitioOperacion)
	{
	  $this->manejoSitioOperacion = (String) $manejoSitioOperacion;
	    return $this;
	}

	/**
	* Get manejoSitioOperacion
	*
	* @return null|String
	*/
	public function getManejoSitioOperacion()
	{
		return $this->manejoSitioOperacion;
	}

	/**
	* Set ausenciaPlaga
	*
	*Ausencia de plaga
	*
	* @parámetro String $ausenciaPlaga
	* @return AusenciaPlaga
	*/
	public function setAusenciaPlaga($ausenciaPlaga)
	{
	  $this->ausenciaPlaga = (String) $ausenciaPlaga;
	    return $this;
	}

	/**
	* Get ausenciaPlaga
	*
	* @return null|String
	*/
	public function getAusenciaPlaga()
	{
		return $this->ausenciaPlaga;
	}

	/**
	* Set plagaDiagnosticoVisualPrediagnostico
	*
	*Plaga/Diagnóstico visual o prediagnostico
	*
	* @parámetro String $plagaDiagnosticoVisualPrediagnostico
	* @return PlagaDiagnosticoVisualPrediagnostico
	*/
	public function setPlagaDiagnosticoVisualPrediagnostico($plagaDiagnosticoVisualPrediagnostico)
	{
	  $this->plagaDiagnosticoVisualPrediagnostico = (String) $plagaDiagnosticoVisualPrediagnostico;
	    return $this;
	}

	/**
	* Get plagaDiagnosticoVisualPrediagnostico
	*
	* @return null|String
	*/
	public function getPlagaDiagnosticoVisualPrediagnostico()
	{
		return $this->plagaDiagnosticoVisualPrediagnostico;
	}

	/**
	* Set cantidadAfectada
	*
	*Cantidad afectada
	*
	* @parámetro Decimal $cantidadAfectada
	* @return CantidadAfectada
	*/
	public function setCantidadAfectada($cantidadAfectada)
	{
	  $this->cantidadAfectada = (Double) $cantidadAfectada;
	    return $this;
	}

	/**
	* Get cantidadAfectada
	*
	* @return null|Decimal
	*/
	public function getCantidadAfectada()
	{
		return $this->cantidadAfectada;
	}

	/**
	* Set porcentajeIncidencia
	*
	*% de incidencia
	*
	* @parámetro Decimal $porcentajeIncidencia
	* @return PorcentajeIncidencia
	*/
	public function setPorcentajeIncidencia($porcentajeIncidencia)
	{
	  $this->porcentajeIncidencia = (Double) $porcentajeIncidencia;
	    return $this;
	}

	/**
	* Get porcentajeIncidencia
	*
	* @return null|Decimal
	*/
	public function getPorcentajeIncidencia()
	{
		return $this->porcentajeIncidencia;
	}

	/**
	* Set porcentajeSeveridad
	*
	*% de severidad
	*
	* @parámetro Decimal $porcentajeSeveridad
	* @return PorcentajeSeveridad
	*/
	public function setPorcentajeSeveridad($porcentajeSeveridad)
	{
	  $this->porcentajeSeveridad = (Double) $porcentajeSeveridad;
	    return $this;
	}

	/**
	* Get porcentajeSeveridad
	*
	* @return null|Decimal
	*/
	public function getPorcentajeSeveridad()
	{
		return $this->porcentajeSeveridad;
	}

	/**
	* Set tipoPlaga
	*
	*Tipo de plaga
	*
	* @parámetro String $tipoPlaga
	* @return TipoPlaga
	*/
	public function setTipoPlaga($tipoPlaga)
	{
	  $this->tipoPlaga = (String) $tipoPlaga;
	    return $this;
	}

	/**
	* Get tipoPlaga
	*
	* @return null|String
	*/
	public function getTipoPlaga()
	{
		return $this->tipoPlaga;
	}

	/**
	* Set faseDesarrolloPlaga
	*
	*Fase de desarrollo de plaga
	*
	* @parámetro String $faseDesarrolloPlaga
	* @return FaseDesarrolloPlaga
	*/
	public function setFaseDesarrolloPlaga($faseDesarrolloPlaga)
	{
	  $this->faseDesarrolloPlaga = (String) $faseDesarrolloPlaga;
	    return $this;
	}

	/**
	* Get faseDesarrolloPlaga
	*
	* @return null|String
	*/
	public function getFaseDesarrolloPlaga()
	{
		return $this->faseDesarrolloPlaga;
	}

	/**
	* Set organoAfectado
	*
	*Órgano afectado
	*
	* @parámetro String $organoAfectado
	* @return OrganoAfectado
	*/
	public function setOrganoAfectado($organoAfectado)
	{
	  $this->organoAfectado = (String) $organoAfectado;
	    return $this;
	}

	/**
	* Get organoAfectado
	*
	* @return null|String
	*/
	public function getOrganoAfectado()
	{
		return $this->organoAfectado;
	}

	/**
	* Set distribucionPlaga
	*
	*Distribución de la plaga
	*
	* @parámetro String $distribucionPlaga
	* @return DistribucionPlaga
	*/
	public function setDistribucionPlaga($distribucionPlaga)
	{
	  $this->distribucionPlaga = (String) $distribucionPlaga;
	    return $this;
	}

	/**
	* Get distribucionPlaga
	*
	* @return null|String
	*/
	public function getDistribucionPlaga()
	{
		return $this->distribucionPlaga;
	}

	/**
	* Set poblacion
	*
	*Población
	*
	* @parámetro Decimal $poblacion
	* @return Poblacion
	*/
	public function setPoblacion($poblacion)
	{
	  $this->poblacion = (Double) $poblacion;
	    return $this;
	}

	/**
	* Get poblacion
	*
	* @return null|Decimal
	*/
	public function getPoblacion()
	{
		return $this->poblacion;
	}

	/**
	* Set diagnosticoVisual
	*
	*Diagnóstico visual
	*
	* @parámetro String $diagnosticoVisual
	* @return DiagnosticoVisual
	*/
	public function setDiagnosticoVisual($diagnosticoVisual)
	{
	  $this->diagnosticoVisual = (String) $diagnosticoVisual;
	    return $this;
	}

	/**
	* Get diagnosticoVisual
	*
	* @return null|String
	*/
	public function getDiagnosticoVisual()
	{
		return $this->diagnosticoVisual;
	}

	/**
	* Set descripcionSintomasP
	*
	*Descripción de síntomas
	*
	* @parámetro String $descripcionSintomasP
	* @return DescripcionSintomasP
	*/
	public function setDescripcionSintomasP($descripcionSintomasP)
	{
	  $this->descripcionSintomasP = (String) $descripcionSintomasP;
	    return $this;
	}

	/**
	* Get descripcionSintomasP
	*
	* @return null|String
	*/
	public function getDescripcionSintomasP()
	{
		return $this->descripcionSintomasP;
	}

	/**
	* Set envioMuestra
	*
	*Envío de muestra
	*
	* @parámetro String $envioMuestra
	* @return EnvioMuestra
	*/
	public function setEnvioMuestra($envioMuestra)
	{
	  $this->envioMuestra = (String) $envioMuestra;
	    return $this;
	}

	/**
	* Get envioMuestra
	*
	* @return null|String
	*/
	public function getEnvioMuestra()
	{
		return $this->envioMuestra;
	}

	/**
	* Set observaciones
	*
	*Observaciones
	*
	* @parámetro String $observaciones
	* @return Observaciones
	*/
	public function setObservaciones($observaciones)
	{
	  $this->observaciones = (String) $observaciones;
	    return $this;
	}

	/**
	* Get observaciones
	*
	* @return null|String
	*/
	public function getObservaciones()
	{
		return $this->observaciones;
	}

	/**
	* Set fechaInspeccion
	*
	*Fecha de inspección
	*
	* @parámetro Date $fechaInspeccion
	* @return FechaInspeccion
	*/
	public function setFechaInspeccion($fechaInspeccion)
	{
	  $this->fechaInspeccion = (String) $fechaInspeccion;
	    return $this;
	}

	/**
	* Get fechaInspeccion
	*
	* @return null|Date
	*/
	public function getFechaInspeccion()
	{
		return $this->fechaInspeccion;
	}

	/**
	* Set usuarioId
	*
	*Indentificación de inspector
	*
	* @parámetro String $usuarioId
	* @return UsuarioId
	*/
	public function setUsuarioId($usuarioId)
	{
	  $this->usuarioId = (String) $usuarioId;
	    return $this;
	}

	/**
	* Get usuarioId
	*
	* @return null|String
	*/
	public function getUsuarioId()
	{
		return $this->usuarioId;
	}

	/**
	* Set usuario
	*
	*Inspector
	*
	* @parámetro String $usuario
	* @return Usuario
	*/
	public function setUsuario($usuario)
	{
	  $this->usuario = (String) $usuario;
	    return $this;
	}

	/**
	* Get usuario
	*
	* @return null|String
	*/
	public function getUsuario()
	{
		return $this->usuario;
	}

	/**
	* Set tabletId
	*
	*
	*
	* @parámetro String $tabletId
	* @return TabletId
	*/
	public function setTabletId($tabletId)
	{
	  $this->tabletId = (String) $tabletId;
	    return $this;
	}

	/**
	* Get tabletId
	*
	* @return null|String
	*/
	public function getTabletId()
	{
		return $this->tabletId;
	}

	/**
	* Set tabletVersionBase
	*
	*
	*
	* @parámetro String $tabletVersionBase
	* @return TabletVersionBase
	*/
	public function setTabletVersionBase($tabletVersionBase)
	{
	  $this->tabletVersionBase = (String) $tabletVersionBase;
	    return $this;
	}

	/**
	* Get tabletVersionBase
	*
	* @return null|String
	*/
	public function getTabletVersionBase()
	{
		return $this->tabletVersionBase;
	}

	/**
	* Set fechaIngresoGuia
	*
	*
	*
	* @parámetro Date $fechaIngresoGuia
	* @return FechaIngresoGuia
	*/
	public function setFechaIngresoGuia($fechaIngresoGuia)
	{
	  $this->fechaIngresoGuia = (String) $fechaIngresoGuia;
	    return $this;
	}

	/**
	* Get fechaIngresoGuia
	*
	* @return null|Date
	*/
	public function getFechaIngresoGuia()
	{
		return $this->fechaIngresoGuia;
	}

	/**
	* Set estadoVf02
	*
	*
	*
	* @parámetro String $estadoVf02
	* @return EstadoVf02
	*/
	public function setEstadoVf02($estadoVf02)
	{
	  $this->estadoVf02 = (String) $estadoVf02;
	    return $this;
	}

	/**
	* Get estadoVf02
	*
	* @return null|String
	*/
	public function getEstadoVf02()
	{
		return $this->estadoVf02;
	}

	/**
	* Set observacionVf02
	*
	*
	*
	* @parámetro String $observacionVf02
	* @return ObservacionVf02
	*/
	public function setObservacionVf02($observacionVf02)
	{
	  $this->observacionVf02 = (String) $observacionVf02;
	    return $this;
	}

	/**
	* Get observacionVf02
	*
	* @return null|String
	*/
	public function getObservacionVf02()
	{
		return $this->observacionVf02;
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
	* @return Vigilanciaf02Modelo
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
