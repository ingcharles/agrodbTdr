<?php
 /**
 * Modelo Moscaf02Modelo
 *
 * Este archivo se complementa con el archivo   Moscaf02LogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021/08/23
 * @uses    Moscaf02Modelo
 * @package FormulariosInspeccion
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class Moscaf02Modelo extends ModeloBase 
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
		* Asociación/Productor
		*/
		protected $nombreAsociacionProductor;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Cédula/RUC
		*/
		protected $identificador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Teléfono
		*/
		protected $telefono;
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
		protected $provincia;
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
		protected $canton;
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
		protected $parroquia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Sitio
		*/
		protected $sitio;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Especie de producto Hortofrutícula
		*/
		protected $especie;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Variedad de producto Hortofrutícula
		*/
		protected $variedad;
		/**
		* @var Decimal
		* Campo requerido
		* Campo visible en el formulario
		* Área de producción estimada (Ha)
		*/
		protected $areaProduccionEstimada;
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
		* Observaciones
		*/
		protected $observaciones;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de registro
		*/
		protected $fechaInspeccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Identificación inspector
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
		protected $estadoMf02;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $observacionMf02;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Foto
		*/
		protected $imagen;

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
	* Nombre de la tabla: moscaf02
	* 
	 */
	Private $tabla="moscaf02";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id";



	/**
	*Secuencia
*/
		 private $secuencial = 'f_inspeccion"."Moscaf02_id_seq'; 



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
		throw new \Exception('Clase Modelo: Moscaf02Modelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: Moscaf02Modelo. Propiedad especificada invalida: get'.$name);
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
	* Set nombreAsociacionProductor
	*
	*Asociación/Productor
	*
	* @parámetro String $nombreAsociacionProductor
	* @return NombreAsociacionProductor
	*/
	public function setNombreAsociacionProductor($nombreAsociacionProductor)
	{
	  $this->nombreAsociacionProductor = (String) $nombreAsociacionProductor;
	    return $this;
	}

	/**
	* Get nombreAsociacionProductor
	*
	* @return null|String
	*/
	public function getNombreAsociacionProductor()
	{
		return $this->nombreAsociacionProductor;
	}

	/**
	* Set identificador
	*
	*Cédula/RUC
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
	* Set telefono
	*
	*Teléfono
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
	* Set sitio
	*
	*Sitio
	*
	* @parámetro String $sitio
	* @return Sitio
	*/
	public function setSitio($sitio)
	{
	  $this->sitio = (String) $sitio;
	    return $this;
	}

	/**
	* Get sitio
	*
	* @return null|String
	*/
	public function getSitio()
	{
		return $this->sitio;
	}

	/**
	* Set especie
	*
	*Especie de producto Hortofrutícula
	*
	* @parámetro String $especie
	* @return Especie
	*/
	public function setEspecie($especie)
	{
	  $this->especie = (String) $especie;
	    return $this;
	}

	/**
	* Get especie
	*
	* @return null|String
	*/
	public function getEspecie()
	{
		return $this->especie;
	}

	/**
	* Set variedad
	*
	*Variedad de producto Hortofrutícula
	*
	* @parámetro String $variedad
	* @return Variedad
	*/
	public function setVariedad($variedad)
	{
	  $this->variedad = (String) $variedad;
	    return $this;
	}

	/**
	* Get variedad
	*
	* @return null|String
	*/
	public function getVariedad()
	{
		return $this->variedad;
	}

	/**
	* Set areaProduccionEstimada
	*
	*Área de producción estimada (Ha)
	*
	* @parámetro Decimal $areaProduccionEstimada
	* @return AreaProduccionEstimada
	*/
	public function setAreaProduccionEstimada($areaProduccionEstimada)
	{
	  $this->areaProduccionEstimada = (Double) $areaProduccionEstimada;
	    return $this;
	}

	/**
	* Get areaProduccionEstimada
	*
	* @return null|Decimal
	*/
	public function getAreaProduccionEstimada()
	{
		return $this->areaProduccionEstimada;
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
	*Fecha de registro
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
	*Identificación inspector
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
	* Set estadoMf02
	*
	*
	*
	* @parámetro String $estadoMf02
	* @return EstadoMf02
	*/
	public function setEstadoMf02($estadoMf02)
	{
	  $this->estadoMf02 = (String) $estadoMf02;
	    return $this;
	}

	/**
	* Get estadoMf02
	*
	* @return null|String
	*/
	public function getEstadoMf02()
	{
		return $this->estadoMf02;
	}

	/**
	* Set observacionMf02
	*
	*
	*
	* @parámetro String $observacionMf02
	* @return ObservacionMf02
	*/
	public function setObservacionMf02($observacionMf02)
	{
	  $this->observacionMf02 = (String) $observacionMf02;
	    return $this;
	}

	/**
	* Get observacionMf02
	*
	* @return null|String
	*/
	public function getObservacionMf02()
	{
		return $this->observacionMf02;
	}

	/**
	* Set imagen
	*
	*Foto
	*
	* @parámetro String $imagen
	* @return Imagen
	*/
	public function setImagen($imagen)
	{
	  $this->imagen = (String) $imagen;
	    return $this;
	}

	/**
	* Get imagen
	*
	* @return null|String
	*/
	public function getImagen()
	{
		return $this->imagen;
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
	* @return Moscaf02Modelo
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
