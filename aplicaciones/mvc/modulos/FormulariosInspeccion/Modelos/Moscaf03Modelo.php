<?php
 /**
 * Modelo Moscaf03Modelo
 *
 * Este archivo se complementa con el archivo   Moscaf03LogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021/08/23
 * @uses    Moscaf03Modelo
 * @package FormulariosInspeccion
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class Moscaf03Modelo extends ModeloBase 
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
		* 
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
		* 
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
		* 
		*/
		protected $codigoLugarMuestreo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Lugar de muestreo
		*/
		protected $nombreLugarMuestreo;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Semana
		*/
		protected $semana;
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
		protected $estadoMf03;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $observacionMf03;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* sitio
		*/
		protected $sitio;
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
		* Foto
		*/
		protected $rutaFoto;

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
	* Nombre de la tabla: moscaf03
	* 
	 */
	Private $tabla="moscaf03";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id";



	/**
	*Secuencia
*/
		 private $secuencial = 'f_inspeccion"."Moscaf03_id_seq'; 



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
		throw new \Exception('Clase Modelo: Moscaf03Modelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: Moscaf03Modelo. Propiedad especificada invalida: get'.$name);
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
	* Set codigoLugarMuestreo
	*
	*
	*
	* @parámetro String $codigoLugarMuestreo
	* @return CodigoLugarMuestreo
	*/
	public function setCodigoLugarMuestreo($codigoLugarMuestreo)
	{
	  $this->codigoLugarMuestreo = (String) $codigoLugarMuestreo;
	    return $this;
	}

	/**
	* Get codigoLugarMuestreo
	*
	* @return null|String
	*/
	public function getCodigoLugarMuestreo()
	{
		return $this->codigoLugarMuestreo;
	}

	/**
	* Set nombreLugarMuestreo
	*
	*Lugar de muestreo
	*
	* @parámetro String $nombreLugarMuestreo
	* @return NombreLugarMuestreo
	*/
	public function setNombreLugarMuestreo($nombreLugarMuestreo)
	{
	  $this->nombreLugarMuestreo = (String) $nombreLugarMuestreo;
	    return $this;
	}

	/**
	* Get nombreLugarMuestreo
	*
	* @return null|String
	*/
	public function getNombreLugarMuestreo()
	{
		return $this->nombreLugarMuestreo;
	}

	/**
	* Set semana
	*
	*Semana
	*
	* @parámetro Integer $semana
	* @return Semana
	*/
	public function setSemana($semana)
	{
	  $this->semana = (Integer) $semana;
	    return $this;
	}

	/**
	* Get semana
	*
	* @return null|Integer
	*/
	public function getSemana()
	{
		return $this->semana;
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
	* Set estadoMf03
	*
	*
	*
	* @parámetro String $estadoMf03
	* @return EstadoMf03
	*/
	public function setEstadoMf03($estadoMf03)
	{
	  $this->estadoMf03 = (String) $estadoMf03;
	    return $this;
	}

	/**
	* Get estadoMf03
	*
	* @return null|String
	*/
	public function getEstadoMf03()
	{
		return $this->estadoMf03;
	}

	/**
	* Set observacionMf03
	*
	*
	*
	* @parámetro String $observacionMf03
	* @return ObservacionMf03
	*/
	public function setObservacionMf03($observacionMf03)
	{
	  $this->observacionMf03 = (String) $observacionMf03;
	    return $this;
	}

	/**
	* Get observacionMf03
	*
	* @return null|String
	*/
	public function getObservacionMf03()
	{
		return $this->observacionMf03;
	}

	/**
	* Set sitio
	*
	*sitio
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
	* Set rutaFoto
	*
	*Foto
	*
	* @parámetro String $rutaFoto
	* @return RutaFoto
	*/
	public function setRutaFoto($rutaFoto)
	{
	  $this->rutaFoto = (String) $rutaFoto;
	    return $this;
	}

	/**
	* Get rutaFoto
	*
	* @return null|String
	*/
	public function getRutaFoto()
	{
		return $this->rutaFoto;
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
	* @return Moscaf03Modelo
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
