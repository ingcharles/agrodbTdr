<?php
 /**
 * Modelo Controlf03Modelo
 *
 * Este archivo se complementa con el archivo   Controlf03LogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021/08/23
 * @uses    Controlf03Modelo
 * @package FormulariosInspeccion
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class Controlf03Modelo extends ModeloBase 
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
		* 
		*/
		protected $idPuntoControl;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Punto de control
		*/
		protected $puntoControl;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Área de inspección
		*/
		protected $areaInspeccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Indentidad de embalaje
		*/
		protected $identidadEmbalaje;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idPaisOrigen;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* País de origen
		*/
		protected $paisOrigen;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Número de embalajes
		*/
		protected $numeroEmbalajes;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Número de unidades
		*/
		protected $numeroUnidades;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Embalajes cuentan con marca autorizada
		*/
		protected $marcaAutorizada;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Descripción de marca autorizada
		*/
		protected $marcaAutorizadaDescripcion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Marca es legible
		*/
		protected $marcaLegible;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Descripción de marca legible
		*/
		protected $marcaLegibleDescripcion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Ausencia de daño de insectos
		*/
		protected $ausenciaDanoInsectos;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Descripción de ausencia de daño de insectos
		*/
		protected $ausenciaDanoInsectosDescripcion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Ausencia de insectos vivos
		*/
		protected $ausenciaInsectosVivos;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Descripción de ausencia de insectos vivos
		*/
		protected $ausenciaInsectosVivosDescripcion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Ausencia de corteza
		*/
		protected $ausenciaCorteza;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Descripción de ausencia de corteza
		*/
		protected $ausenciaCortezaDescripcion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Razón social
		*/
		protected $razonSocial;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Manifesto
		*/
		protected $manifesto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Producto
		*/
		protected $producto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* ¿Envío de muestra?
		*/
		protected $envioMuestra;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Observaciones de inspección
		*/
		protected $observaciones;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Dictamen final de inspección
		*/
		protected $dicatamenFinal;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de la inspección
		*/
		protected $fechaInspeccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Cédula del inspector
		*/
		protected $usuarioId;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre del inspector
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
		protected $estadoCf03;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $observacionCf03;

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
	* Nombre de la tabla: controlf03
	* 
	 */
	Private $tabla="controlf03";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id";



	/**
	*Secuencia
*/
		 private $secuencial = 'f_inspeccion"."Controlf03_id_seq'; 



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
		throw new \Exception('Clase Modelo: Controlf03Modelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: Controlf03Modelo. Propiedad especificada invalida: get'.$name);
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
	* Set idPuntoControl
	*
	*
	*
	* @parámetro String $idPuntoControl
	* @return IdPuntoControl
	*/
	public function setIdPuntoControl($idPuntoControl)
	{
	  $this->idPuntoControl = (String) $idPuntoControl;
	    return $this;
	}

	/**
	* Get idPuntoControl
	*
	* @return null|String
	*/
	public function getIdPuntoControl()
	{
		return $this->idPuntoControl;
	}

	/**
	* Set puntoControl
	*
	*Punto de control
	*
	* @parámetro String $puntoControl
	* @return PuntoControl
	*/
	public function setPuntoControl($puntoControl)
	{
	  $this->puntoControl = (String) $puntoControl;
	    return $this;
	}

	/**
	* Get puntoControl
	*
	* @return null|String
	*/
	public function getPuntoControl()
	{
		return $this->puntoControl;
	}

	/**
	* Set areaInspeccion
	*
	*Área de inspección
	*
	* @parámetro String $areaInspeccion
	* @return AreaInspeccion
	*/
	public function setAreaInspeccion($areaInspeccion)
	{
	  $this->areaInspeccion = (String) $areaInspeccion;
	    return $this;
	}

	/**
	* Get areaInspeccion
	*
	* @return null|String
	*/
	public function getAreaInspeccion()
	{
		return $this->areaInspeccion;
	}

	/**
	* Set identidadEmbalaje
	*
	*Indentidad de embalaje
	*
	* @parámetro String $identidadEmbalaje
	* @return IdentidadEmbalaje
	*/
	public function setIdentidadEmbalaje($identidadEmbalaje)
	{
	  $this->identidadEmbalaje = (String) $identidadEmbalaje;
	    return $this;
	}

	/**
	* Get identidadEmbalaje
	*
	* @return null|String
	*/
	public function getIdentidadEmbalaje()
	{
		return $this->identidadEmbalaje;
	}

	/**
	* Set idPaisOrigen
	*
	*
	*
	* @parámetro String $idPaisOrigen
	* @return IdPaisOrigen
	*/
	public function setIdPaisOrigen($idPaisOrigen)
	{
	  $this->idPaisOrigen = (String) $idPaisOrigen;
	    return $this;
	}

	/**
	* Get idPaisOrigen
	*
	* @return null|String
	*/
	public function getIdPaisOrigen()
	{
		return $this->idPaisOrigen;
	}

	/**
	* Set paisOrigen
	*
	*País de origen
	*
	* @parámetro String $paisOrigen
	* @return PaisOrigen
	*/
	public function setPaisOrigen($paisOrigen)
	{
	  $this->paisOrigen = (String) $paisOrigen;
	    return $this;
	}

	/**
	* Get paisOrigen
	*
	* @return null|String
	*/
	public function getPaisOrigen()
	{
		return $this->paisOrigen;
	}

	/**
	* Set numeroEmbalajes
	*
	*Número de embalajes
	*
	* @parámetro Integer $numeroEmbalajes
	* @return NumeroEmbalajes
	*/
	public function setNumeroEmbalajes($numeroEmbalajes)
	{
	  $this->numeroEmbalajes = (Integer) $numeroEmbalajes;
	    return $this;
	}

	/**
	* Get numeroEmbalajes
	*
	* @return null|Integer
	*/
	public function getNumeroEmbalajes()
	{
		return $this->numeroEmbalajes;
	}

	/**
	* Set numeroUnidades
	*
	*Número de unidades
	*
	* @parámetro Integer $numeroUnidades
	* @return NumeroUnidades
	*/
	public function setNumeroUnidades($numeroUnidades)
	{
	  $this->numeroUnidades = (Integer) $numeroUnidades;
	    return $this;
	}

	/**
	* Get numeroUnidades
	*
	* @return null|Integer
	*/
	public function getNumeroUnidades()
	{
		return $this->numeroUnidades;
	}

	/**
	* Set marcaAutorizada
	*
	*Embalajes cuentan con marca autorizada
	*
	* @parámetro String $marcaAutorizada
	* @return MarcaAutorizada
	*/
	public function setMarcaAutorizada($marcaAutorizada)
	{
	  $this->marcaAutorizada = (String) $marcaAutorizada;
	    return $this;
	}

	/**
	* Get marcaAutorizada
	*
	* @return null|String
	*/
	public function getMarcaAutorizada()
	{
		return $this->marcaAutorizada;
	}

	/**
	* Set marcaAutorizadaDescripcion
	*
	*Descripción de marca autorizada
	*
	* @parámetro String $marcaAutorizadaDescripcion
	* @return MarcaAutorizadaDescripcion
	*/
	public function setMarcaAutorizadaDescripcion($marcaAutorizadaDescripcion)
	{
	  $this->marcaAutorizadaDescripcion = (String) $marcaAutorizadaDescripcion;
	    return $this;
	}

	/**
	* Get marcaAutorizadaDescripcion
	*
	* @return null|String
	*/
	public function getMarcaAutorizadaDescripcion()
	{
		return $this->marcaAutorizadaDescripcion;
	}

	/**
	* Set marcaLegible
	*
	*Marca es legible
	*
	* @parámetro String $marcaLegible
	* @return MarcaLegible
	*/
	public function setMarcaLegible($marcaLegible)
	{
	  $this->marcaLegible = (String) $marcaLegible;
	    return $this;
	}

	/**
	* Get marcaLegible
	*
	* @return null|String
	*/
	public function getMarcaLegible()
	{
		return $this->marcaLegible;
	}

	/**
	* Set marcaLegibleDescripcion
	*
	*Descripción de marca legible
	*
	* @parámetro String $marcaLegibleDescripcion
	* @return MarcaLegibleDescripcion
	*/
	public function setMarcaLegibleDescripcion($marcaLegibleDescripcion)
	{
	  $this->marcaLegibleDescripcion = (String) $marcaLegibleDescripcion;
	    return $this;
	}

	/**
	* Get marcaLegibleDescripcion
	*
	* @return null|String
	*/
	public function getMarcaLegibleDescripcion()
	{
		return $this->marcaLegibleDescripcion;
	}

	/**
	* Set ausenciaDanoInsectos
	*
	*Ausencia de daño de insectos
	*
	* @parámetro String $ausenciaDanoInsectos
	* @return AusenciaDanoInsectos
	*/
	public function setAusenciaDanoInsectos($ausenciaDanoInsectos)
	{
	  $this->ausenciaDanoInsectos = (String) $ausenciaDanoInsectos;
	    return $this;
	}

	/**
	* Get ausenciaDanoInsectos
	*
	* @return null|String
	*/
	public function getAusenciaDanoInsectos()
	{
		return $this->ausenciaDanoInsectos;
	}

	/**
	* Set ausenciaDanoInsectosDescripcion
	*
	*Descripción de ausencia de daño de insectos
	*
	* @parámetro String $ausenciaDanoInsectosDescripcion
	* @return AusenciaDanoInsectosDescripcion
	*/
	public function setAusenciaDanoInsectosDescripcion($ausenciaDanoInsectosDescripcion)
	{
	  $this->ausenciaDanoInsectosDescripcion = (String) $ausenciaDanoInsectosDescripcion;
	    return $this;
	}

	/**
	* Get ausenciaDanoInsectosDescripcion
	*
	* @return null|String
	*/
	public function getAusenciaDanoInsectosDescripcion()
	{
		return $this->ausenciaDanoInsectosDescripcion;
	}

	/**
	* Set ausenciaInsectosVivos
	*
	*Ausencia de insectos vivos
	*
	* @parámetro String $ausenciaInsectosVivos
	* @return AusenciaInsectosVivos
	*/
	public function setAusenciaInsectosVivos($ausenciaInsectosVivos)
	{
	  $this->ausenciaInsectosVivos = (String) $ausenciaInsectosVivos;
	    return $this;
	}

	/**
	* Get ausenciaInsectosVivos
	*
	* @return null|String
	*/
	public function getAusenciaInsectosVivos()
	{
		return $this->ausenciaInsectosVivos;
	}

	/**
	* Set ausenciaInsectosVivosDescripcion
	*
	*Descripción de ausencia de insectos vivos
	*
	* @parámetro String $ausenciaInsectosVivosDescripcion
	* @return AusenciaInsectosVivosDescripcion
	*/
	public function setAusenciaInsectosVivosDescripcion($ausenciaInsectosVivosDescripcion)
	{
	  $this->ausenciaInsectosVivosDescripcion = (String) $ausenciaInsectosVivosDescripcion;
	    return $this;
	}

	/**
	* Get ausenciaInsectosVivosDescripcion
	*
	* @return null|String
	*/
	public function getAusenciaInsectosVivosDescripcion()
	{
		return $this->ausenciaInsectosVivosDescripcion;
	}

	/**
	* Set ausenciaCorteza
	*
	*Ausencia de corteza
	*
	* @parámetro String $ausenciaCorteza
	* @return AusenciaCorteza
	*/
	public function setAusenciaCorteza($ausenciaCorteza)
	{
	  $this->ausenciaCorteza = (String) $ausenciaCorteza;
	    return $this;
	}

	/**
	* Get ausenciaCorteza
	*
	* @return null|String
	*/
	public function getAusenciaCorteza()
	{
		return $this->ausenciaCorteza;
	}

	/**
	* Set ausenciaCortezaDescripcion
	*
	*Descripción de ausencia de corteza
	*
	* @parámetro String $ausenciaCortezaDescripcion
	* @return AusenciaCortezaDescripcion
	*/
	public function setAusenciaCortezaDescripcion($ausenciaCortezaDescripcion)
	{
	  $this->ausenciaCortezaDescripcion = (String) $ausenciaCortezaDescripcion;
	    return $this;
	}

	/**
	* Get ausenciaCortezaDescripcion
	*
	* @return null|String
	*/
	public function getAusenciaCortezaDescripcion()
	{
		return $this->ausenciaCortezaDescripcion;
	}

	/**
	* Set razonSocial
	*
	*Razón social
	*
	* @parámetro String $razonSocial
	* @return RazonSocial
	*/
	public function setRazonSocial($razonSocial)
	{
	  $this->razonSocial = (String) $razonSocial;
	    return $this;
	}

	/**
	* Get razonSocial
	*
	* @return null|String
	*/
	public function getRazonSocial()
	{
		return $this->razonSocial;
	}

	/**
	* Set manifesto
	*
	*Manifesto
	*
	* @parámetro String $manifesto
	* @return Manifesto
	*/
	public function setManifesto($manifesto)
	{
	  $this->manifesto = (String) $manifesto;
	    return $this;
	}

	/**
	* Get manifesto
	*
	* @return null|String
	*/
	public function getManifesto()
	{
		return $this->manifesto;
	}

	/**
	* Set producto
	*
	*Producto
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
	* Set envioMuestra
	*
	*¿Envío de muestra?
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
	*Observaciones de inspección
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
	* Set dicatamenFinal
	*
	*Dictamen final de inspección
	*
	* @parámetro String $dicatamenFinal
	* @return DicatamenFinal
	*/
	public function setDicatamenFinal($dicatamenFinal)
	{
	  $this->dicatamenFinal = (String) $dicatamenFinal;
	    return $this;
	}

	/**
	* Get dicatamenFinal
	*
	* @return null|String
	*/
	public function getDicatamenFinal()
	{
		return $this->dicatamenFinal;
	}

	/**
	* Set fechaInspeccion
	*
	*Fecha de la inspección
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
	*Cédula del inspector
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
	*Nombre del inspector
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
	* Set estadoCf03
	*
	*
	*
	* @parámetro String $estadoCf03
	* @return EstadoCf03
	*/
	public function setEstadoCf03($estadoCf03)
	{
	  $this->estadoCf03 = (String) $estadoCf03;
	    return $this;
	}

	/**
	* Get estadoCf03
	*
	* @return null|String
	*/
	public function getEstadoCf03()
	{
		return $this->estadoCf03;
	}

	/**
	* Set observacionCf03
	*
	*
	*
	* @parámetro String $observacionCf03
	* @return ObservacionCf03
	*/
	public function setObservacionCf03($observacionCf03)
	{
	  $this->observacionCf03 = (String) $observacionCf03;
	    return $this;
	}

	/**
	* Get observacionCf03
	*
	* @return null|String
	*/
	public function getObservacionCf03()
	{
		return $this->observacionCf03;
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
	* @return Controlf03Modelo
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
