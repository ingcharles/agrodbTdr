<?php
 /**
 * Modelo Controlf04Modelo
 *
 * Este archivo se complementa con el archivo   Controlf04LogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-09-02
 * @uses    Controlf04Modelo
 * @package FormulariosInspeccion
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class Controlf04Modelo extends ModeloBase 
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
		protected $idSeguimientoCuarentenario;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* RUC operador
		*/
		protected $rucOperador;
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
		* 
		*/
		protected $codigoPaisOrigen;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* País de origen
		*/
		protected $paisOrigen;
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
		* Subtipo
		*/
		protected $subtipoProducto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Peso
		*/
		protected $peso;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Número plantas ingreso
		*/
		protected $numeroPlantasIngreso;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $codigoProvincia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Provincia
		*/
		protected $provincia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $codigoCanton;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Cantón
		*/
		protected $canton;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $codigoParroquia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Parroquia
		*/
		protected $parroquia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre del SCPE
		*/
		protected $nombreScpe;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Tipo operación
		*/
		protected $tipoOperacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Tipo cuarentena/condición de producción
		*/
		protected $tipoCuarentenaCondicionProduccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Fase de seguimiento
		*/
		protected $faseSeguimiento;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Código de lote
		*/
		protected $codigoLote;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Número de seguimientos planificados
		*/
		protected $numeroSeguimientosPlanificados;
		/**
		* @var Decimal
		* Campo requerido
		* Campo visible en el formulario
		* Cantidad total
		*/
		protected $cantidadTotal;
		/**
		* @var Decimal
		* Campo requerido
		* Campo visible en el formulario
		* Cantidad vigilada
		*/
		protected $cantidadVigilada;
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
		* Etapa de cultivo
		*/
		protected $etapaCultivo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Posee registro de monitoreo de plagas
		*/
		protected $registroMonitoreoPlagas;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Ausencia de plagas
		*/
		protected $ausenciaPlagas;
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
		* % incidencia
		*/
		protected $porcentajeIncidencia;
		/**
		* @var Decimal
		* Campo requerido
		* Campo visible en el formulario
		* % severidad
		*/
		protected $porcentajeSeveridad;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Fase de desarrollo plaga
		*/
		protected $faseDesarrolloPlaga;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Órgano afectado de la planta
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
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Población
		*/
		protected $poblacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Descripción de síntomas
		*/
		protected $descripcionSintomas;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Envío muestra a laboratorio
		*/
		protected $envioMuestra;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Resultado inspección
		*/
		protected $resultadoInspeccion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Número de plantas en la inspección
		*/
		protected $numeroPlantasInspeccion;
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
		* Cédula inspector
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
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $estadoGuia;
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
		protected $estadoCf04;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $observacionCf04;

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
	* Nombre de la tabla: controlf04
	* 
	 */
	Private $tabla="controlf04";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id";



	/**
	*Secuencia
*/
		 private $secuencial = 'f_inspeccion"."Controlf04_id_seq'; 



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
		throw new \Exception('Clase Modelo: Controlf04Modelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: Controlf04Modelo. Propiedad especificada invalida: get'.$name);
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
	* Set idSeguimientoCuarentenario
	*
	*
	*
	* @parámetro String $idSeguimientoCuarentenario
	* @return IdSeguimientoCuarentenario
	*/
	public function setIdSeguimientoCuarentenario($idSeguimientoCuarentenario)
	{
	  $this->idSeguimientoCuarentenario = (String) $idSeguimientoCuarentenario;
	    return $this;
	}

	/**
	* Get idSeguimientoCuarentenario
	*
	* @return null|String
	*/
	public function getIdSeguimientoCuarentenario()
	{
		return $this->idSeguimientoCuarentenario;
	}

	/**
	* Set rucOperador
	*
	*RUC operador
	*
	* @parámetro String $rucOperador
	* @return RucOperador
	*/
	public function setRucOperador($rucOperador)
	{
	  $this->rucOperador = (String) $rucOperador;
	    return $this;
	}

	/**
	* Get rucOperador
	*
	* @return null|String
	*/
	public function getRucOperador()
	{
		return $this->rucOperador;
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
	* Set codigoPaisOrigen
	*
	*
	*
	* @parámetro String $codigoPaisOrigen
	* @return CodigoPaisOrigen
	*/
	public function setCodigoPaisOrigen($codigoPaisOrigen)
	{
	  $this->codigoPaisOrigen = (String) $codigoPaisOrigen;
	    return $this;
	}

	/**
	* Get codigoPaisOrigen
	*
	* @return null|String
	*/
	public function getCodigoPaisOrigen()
	{
		return $this->codigoPaisOrigen;
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
	* Set subtipoProducto
	*
	*Subtipo
	*
	* @parámetro String $subtipoProducto
	* @return SubtipoProducto
	*/
	public function setSubtipoProducto($subtipoProducto)
	{
	  $this->subtipoProducto = (String) $subtipoProducto;
	    return $this;
	}

	/**
	* Get subtipoProducto
	*
	* @return null|String
	*/
	public function getSubtipoProducto()
	{
		return $this->subtipoProducto;
	}

	/**
	* Set peso
	*
	*Peso
	*
	* @parámetro String $peso
	* @return Peso
	*/
	public function setPeso($peso)
	{
	  $this->peso = (String) $peso;
	    return $this;
	}

	/**
	* Get peso
	*
	* @return null|String
	*/
	public function getPeso()
	{
		return $this->peso;
	}

	/**
	* Set numeroPlantasIngreso
	*
	*Número plantas ingreso
	*
	* @parámetro Integer $numeroPlantasIngreso
	* @return NumeroPlantasIngreso
	*/
	public function setNumeroPlantasIngreso($numeroPlantasIngreso)
	{
	  $this->numeroPlantasIngreso = (Integer) $numeroPlantasIngreso;
	    return $this;
	}

	/**
	* Get numeroPlantasIngreso
	*
	* @return null|Integer
	*/
	public function getNumeroPlantasIngreso()
	{
		return $this->numeroPlantasIngreso;
	}

	/**
	* Set codigoProvincia
	*
	*
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
	* Set provincia
	*
	*Provincia
	*
	* @parámetro String $provincia
	* @return Provincia
	*/
	public function setProvincia($provincia)
	{
	  $this->provincia = (String) $provincia;
	    return $this;
	}

	/**
	* Get provincia
	*
	* @return null|String
	*/
	public function getProvincia()
	{
		return $this->provincia;
	}

	/**
	* Set codigoCanton
	*
	*
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
	* Set canton
	*
	*Cantón
	*
	* @parámetro String $canton
	* @return Canton
	*/
	public function setCanton($canton)
	{
	  $this->canton = (String) $canton;
	    return $this;
	}

	/**
	* Get canton
	*
	* @return null|String
	*/
	public function getCanton()
	{
		return $this->canton;
	}

	/**
	* Set codigoParroquia
	*
	*
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
	* Set parroquia
	*
	*Parroquia
	*
	* @parámetro String $parroquia
	* @return Parroquia
	*/
	public function setParroquia($parroquia)
	{
	  $this->parroquia = (String) $parroquia;
	    return $this;
	}

	/**
	* Get parroquia
	*
	* @return null|String
	*/
	public function getParroquia()
	{
		return $this->parroquia;
	}

	/**
	* Set nombreScpe
	*
	*Nombre del SCPE
	*
	* @parámetro String $nombreScpe
	* @return NombreScpe
	*/
	public function setNombreScpe($nombreScpe)
	{
	  $this->nombreScpe = (String) $nombreScpe;
	    return $this;
	}

	/**
	* Get nombreScpe
	*
	* @return null|String
	*/
	public function getNombreScpe()
	{
		return $this->nombreScpe;
	}

	/**
	* Set tipoOperacion
	*
	*Tipo operación
	*
	* @parámetro String $tipoOperacion
	* @return TipoOperacion
	*/
	public function setTipoOperacion($tipoOperacion)
	{
	  $this->tipoOperacion = (String) $tipoOperacion;
	    return $this;
	}

	/**
	* Get tipoOperacion
	*
	* @return null|String
	*/
	public function getTipoOperacion()
	{
		return $this->tipoOperacion;
	}

	/**
	* Set tipoCuarentenaCondicionProduccion
	*
	*Tipo cuarentena/condición de producción
	*
	* @parámetro String $tipoCuarentenaCondicionProduccion
	* @return TipoCuarentenaCondicionProduccion
	*/
	public function setTipoCuarentenaCondicionProduccion($tipoCuarentenaCondicionProduccion)
	{
	  $this->tipoCuarentenaCondicionProduccion = (String) $tipoCuarentenaCondicionProduccion;
	    return $this;
	}

	/**
	* Get tipoCuarentenaCondicionProduccion
	*
	* @return null|String
	*/
	public function getTipoCuarentenaCondicionProduccion()
	{
		return $this->tipoCuarentenaCondicionProduccion;
	}

	/**
	* Set faseSeguimiento
	*
	*Fase de seguimiento
	*
	* @parámetro String $faseSeguimiento
	* @return FaseSeguimiento
	*/
	public function setFaseSeguimiento($faseSeguimiento)
	{
	  $this->faseSeguimiento = (String) $faseSeguimiento;
	    return $this;
	}

	/**
	* Get faseSeguimiento
	*
	* @return null|String
	*/
	public function getFaseSeguimiento()
	{
		return $this->faseSeguimiento;
	}

	/**
	* Set codigoLote
	*
	*Código de lote
	*
	* @parámetro String $codigoLote
	* @return CodigoLote
	*/
	public function setCodigoLote($codigoLote)
	{
	  $this->codigoLote = (String) $codigoLote;
	    return $this;
	}

	/**
	* Get codigoLote
	*
	* @return null|String
	*/
	public function getCodigoLote()
	{
		return $this->codigoLote;
	}

	/**
	* Set numeroSeguimientosPlanificados
	*
	*Número de seguimientos planificados
	*
	* @parámetro Integer $numeroSeguimientosPlanificados
	* @return NumeroSeguimientosPlanificados
	*/
	public function setNumeroSeguimientosPlanificados($numeroSeguimientosPlanificados)
	{
	  $this->numeroSeguimientosPlanificados = (Integer) $numeroSeguimientosPlanificados;
	    return $this;
	}

	/**
	* Get numeroSeguimientosPlanificados
	*
	* @return null|Integer
	*/
	public function getNumeroSeguimientosPlanificados()
	{
		return $this->numeroSeguimientosPlanificados;
	}

	/**
	* Set cantidadTotal
	*
	*Cantidad total
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
	*Cantidad vigilada
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
	* Set registroMonitoreoPlagas
	*
	*Posee registro de monitoreo de plagas
	*
	* @parámetro String $registroMonitoreoPlagas
	* @return RegistroMonitoreoPlagas
	*/
	public function setRegistroMonitoreoPlagas($registroMonitoreoPlagas)
	{
	  $this->registroMonitoreoPlagas = (String) $registroMonitoreoPlagas;
	    return $this;
	}

	/**
	* Get registroMonitoreoPlagas
	*
	* @return null|String
	*/
	public function getRegistroMonitoreoPlagas()
	{
		return $this->registroMonitoreoPlagas;
	}

	/**
	* Set ausenciaPlagas
	*
	*Ausencia de plagas
	*
	* @parámetro String $ausenciaPlagas
	* @return AusenciaPlagas
	*/
	public function setAusenciaPlagas($ausenciaPlagas)
	{
	  $this->ausenciaPlagas = (String) $ausenciaPlagas;
	    return $this;
	}

	/**
	* Get ausenciaPlagas
	*
	* @return null|String
	*/
	public function getAusenciaPlagas()
	{
		return $this->ausenciaPlagas;
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
	*% incidencia
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
	*% severidad
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
	* Set faseDesarrolloPlaga
	*
	*Fase de desarrollo plaga
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
	*Órgano afectado de la planta
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
	* @parámetro String $poblacion
	* @return Poblacion
	*/
	public function setPoblacion($poblacion)
	{
	  $this->poblacion = (String) $poblacion;
	    return $this;
	}

	/**
	* Get poblacion
	*
	* @return null|String
	*/
	public function getPoblacion()
	{
		return $this->poblacion;
	}

	/**
	* Set descripcionSintomas
	*
	*Descripción de síntomas
	*
	* @parámetro String $descripcionSintomas
	* @return DescripcionSintomas
	*/
	public function setDescripcionSintomas($descripcionSintomas)
	{
	  $this->descripcionSintomas = (String) $descripcionSintomas;
	    return $this;
	}

	/**
	* Get descripcionSintomas
	*
	* @return null|String
	*/
	public function getDescripcionSintomas()
	{
		return $this->descripcionSintomas;
	}

	/**
	* Set envioMuestra
	*
	*Envío muestra a laboratorio
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
	* Set resultadoInspeccion
	*
	*Resultado inspección
	*
	* @parámetro String $resultadoInspeccion
	* @return ResultadoInspeccion
	*/
	public function setResultadoInspeccion($resultadoInspeccion)
	{
	  $this->resultadoInspeccion = (String) $resultadoInspeccion;
	    return $this;
	}

	/**
	* Get resultadoInspeccion
	*
	* @return null|String
	*/
	public function getResultadoInspeccion()
	{
		return $this->resultadoInspeccion;
	}

	/**
	* Set numeroPlantasInspeccion
	*
	*Número de plantas en la inspección
	*
	* @parámetro Integer $numeroPlantasInspeccion
	* @return NumeroPlantasInspeccion
	*/
	public function setNumeroPlantasInspeccion($numeroPlantasInspeccion)
	{
	  $this->numeroPlantasInspeccion = (Integer) $numeroPlantasInspeccion;
	    return $this;
	}

	/**
	* Get numeroPlantasInspeccion
	*
	* @return null|Integer
	*/
	public function getNumeroPlantasInspeccion()
	{
		return $this->numeroPlantasInspeccion;
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
	*Cédula inspector
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
	* Set estadoGuia
	*
	*
	*
	* @parámetro String $estadoGuia
	* @return EstadoGuia
	*/
	public function setEstadoGuia($estadoGuia)
	{
	  $this->estadoGuia = (String) $estadoGuia;
	    return $this;
	}

	/**
	* Get estadoGuia
	*
	* @return null|String
	*/
	public function getEstadoGuia()
	{
		return $this->estadoGuia;
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
	* Set estadoCf04
	*
	*
	*
	* @parámetro String $estadoCf04
	* @return EstadoCf04
	*/
	public function setEstadoCf04($estadoCf04)
	{
	  $this->estadoCf04 = (String) $estadoCf04;
	    return $this;
	}

	/**
	* Get estadoCf04
	*
	* @return null|String
	*/
	public function getEstadoCf04()
	{
		return $this->estadoCf04;
	}

	/**
	* Set observacionCf04
	*
	*
	*
	* @parámetro String $observacionCf04
	* @return ObservacionCf04
	*/
	public function setObservacionCf04($observacionCf04)
	{
	  $this->observacionCf04 = (String) $observacionCf04;
	    return $this;
	}

	/**
	* Get observacionCf04
	*
	* @return null|String
	*/
	public function getObservacionCf04()
	{
		return $this->observacionCf04;
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
	* @return Controlf04Modelo
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
