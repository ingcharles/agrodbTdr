<?php
 /**
 * Modelo DatosVehiculoTransporteAnimalesModelo
 *
 * Este archivo se complementa con el archivo   DatosVehiculoTransporteAnimalesLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-11-22
 * @uses    DatosVehiculoTransporteAnimalesModelo
 * @package RegistroOperador
 * @subpackage Modelos
 */
  namespace Agrodb\RegistroOperador\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class DatosVehiculoTransporteAnimalesModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador único de la tabla
		*/
		protected $idDatoVehiculoTransporteAnimales;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla g_operadores.areas (llave foranea)
		*/
		protected $idArea;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla g_catalogos.tipos_operacion (llave foranea)
		*/
		protected $idTipoOperacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla g_operadores.operadores_tipo_operaciones (llave foranea)
		*/
		protected $idOperadorTipoOperacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla g_operadores.historial_operaciones(llave foranea)
		*/
		protected $idHistorialOperacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla g_catalogos_localizacion (llave foranea)
		*/
		protected $idCodigoProvincia;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Campo que genera un número secuencial por provincia
		*/
		protected $secuencialProvincia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el anio en que se generó el certificado
		*/
		protected $anioCertificado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el código del certificado (se genera por provincia)
		*/
		protected $codigoCertificado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la placa del vehiculo
		*/
		protected $placaVehiculo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el identificador del propietario del vehiculo (operador registrado en el sistema GUIA)
		*/
		protected $identificadorPropietarioVehiculo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la marca del vehiculo
		*/
		protected $marcaVehiculo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el modelo del vehiculo
		*/
		protected $modeloVehiculo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el anio del vehiculo
		*/
		protected $anioVehiculo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el color del vehiculo
		*/
		protected $colorVehiculo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la clase del vehiculo
		*/
		protected $claseVehiculo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el tipo de vehiculo
		*/
		protected $tipoVehiculo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el tamanio del contenedor del vehiculo
		*/
		protected $tamanioContenedorVehiculo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almavena las caracteristicas del contenedor del vehiculo
		*/
		protected $caracteristicaContenedorVehiculo;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la fecha en la que se realizo la actualizacion de los datos del vehiculo (tamanio_contenedor, caracteristica_contenedor)
		*/
		protected $fechaModificacion;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la fecha en la que se realizo el registro de los datos del vehiculo
		*/
		protected $fechaCreacion;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la fecha en la que se aprueba el registro de datos del vehiculo
		*/
		protected $fechaAprobacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el estado del vehiculo
		*/
		protected $estadoVehiculo;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_operadores";

	/**
	* Nombre de la tabla: datos_vehiculo_transporte_animales
	* 
	 */
	Private $tabla="datos_vehiculo_transporte_animales";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_dato_vehiculo_transporte_animales";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_operadores"."DatosVehiculoTransporteAnimales_id_dato_vehiculo_transporte_animales_seq'; 



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
		throw new \Exception('Clase Modelo: DatosVehiculoTransporteAnimalesModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: DatosVehiculoTransporteAnimalesModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_operadores
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idDatoVehiculoTransporteAnimales
	*
	*Identificador único de la tabla
	*
	* @parámetro Integer $idDatoVehiculoTransporteAnimales
	* @return IdDatoVehiculoTransporteAnimales
	*/
	public function setIdDatoVehiculoTransporteAnimales($idDatoVehiculoTransporteAnimales)
	{
	  $this->idDatoVehiculoTransporteAnimales = (Integer) $idDatoVehiculoTransporteAnimales;
	    return $this;
	}

	/**
	* Get idDatoVehiculoTransporteAnimales
	*
	* @return null|Integer
	*/
	public function getIdDatoVehiculoTransporteAnimales()
	{
		return $this->idDatoVehiculoTransporteAnimales;
	}

	/**
	* Set idArea
	*
	*Identificador unico de la tabla g_operadores.areas (llave foranea)
	*
	* @parámetro Integer $idArea
	* @return IdArea
	*/
	public function setIdArea($idArea)
	{
	  $this->idArea = (Integer) $idArea;
	    return $this;
	}

	/**
	* Get idArea
	*
	* @return null|Integer
	*/
	public function getIdArea()
	{
		return $this->idArea;
	}

	/**
	* Set idTipoOperacion
	*
	*Identificador unico de la tabla g_catalogos.tipos_operacion (llave foranea)
	*
	* @parámetro Integer $idTipoOperacion
	* @return IdTipoOperacion
	*/
	public function setIdTipoOperacion($idTipoOperacion)
	{
	  $this->idTipoOperacion = (Integer) $idTipoOperacion;
	    return $this;
	}

	/**
	* Get idTipoOperacion
	*
	* @return null|Integer
	*/
	public function getIdTipoOperacion()
	{
		return $this->idTipoOperacion;
	}

	/**
	* Set idOperadorTipoOperacion
	*
	*Identificador unico de la tabla g_operadores.operadores_tipo_operaciones (llave foranea)
	*
	* @parámetro Integer $idOperadorTipoOperacion
	* @return IdOperadorTipoOperacion
	*/
	public function setIdOperadorTipoOperacion($idOperadorTipoOperacion)
	{
	  $this->idOperadorTipoOperacion = (Integer) $idOperadorTipoOperacion;
	    return $this;
	}

	/**
	* Get idOperadorTipoOperacion
	*
	* @return null|Integer
	*/
	public function getIdOperadorTipoOperacion()
	{
		return $this->idOperadorTipoOperacion;
	}

	/**
	* Set idHistorialOperacion
	*
	*Identificador unico de la tabla g_operadores.historial_operaciones(llave foranea)
	*
	* @parámetro Integer $idHistorialOperacion
	* @return IdHistorialOperacion
	*/
	public function setIdHistorialOperacion($idHistorialOperacion)
	{
	  $this->idHistorialOperacion = (Integer) $idHistorialOperacion;
	    return $this;
	}

	/**
	* Get idHistorialOperacion
	*
	* @return null|Integer
	*/
	public function getIdHistorialOperacion()
	{
		return $this->idHistorialOperacion;
	}

	/**
	* Set idCodigoProvincia
	*
	*Identificador unico de la tabla g_catalogos_localizacion (llave foranea)
	*
	* @parámetro Integer $idCodigoProvincia
	* @return IdCodigoProvincia
	*/
	public function setIdCodigoProvincia($idCodigoProvincia)
	{
	  $this->idCodigoProvincia = (Integer) $idCodigoProvincia;
	    return $this;
	}

	/**
	* Get idCodigoProvincia
	*
	* @return null|Integer
	*/
	public function getIdCodigoProvincia()
	{
		return $this->idCodigoProvincia;
	}

	/**
	* Set secuencialProvincia
	*
	*Campo que genera un número secuencial por provincia
	*
	* @parámetro Integer $secuencialProvincia
	* @return SecuencialProvincia
	*/
	public function setSecuencialProvincia($secuencialProvincia)
	{
	  $this->secuencialProvincia = (Integer) $secuencialProvincia;
	    return $this;
	}

	/**
	* Get secuencialProvincia
	*
	* @return null|Integer
	*/
	public function getSecuencialProvincia()
	{
		return $this->secuencialProvincia;
	}

	/**
	* Set anioCertificado
	*
	*Campo que almacena el anio en que se generó el certificado
	*
	* @parámetro String $anioCertificado
	* @return AnioCertificado
	*/
	public function setAnioCertificado($anioCertificado)
	{
	  $this->anioCertificado = (String) $anioCertificado;
	    return $this;
	}

	/**
	* Get anioCertificado
	*
	* @return null|String
	*/
	public function getAnioCertificado()
	{
		return $this->anioCertificado;
	}

	/**
	* Set codigoCertificado
	*
	*Campo que almacena el código del certificado (se genera por provincia)
	*
	* @parámetro String $codigoCertificado
	* @return CodigoCertificado
	*/
	public function setCodigoCertificado($codigoCertificado)
	{
	  $this->codigoCertificado = (String) $codigoCertificado;
	    return $this;
	}

	/**
	* Get codigoCertificado
	*
	* @return null|String
	*/
	public function getCodigoCertificado()
	{
		return $this->codigoCertificado;
	}

	/**
	* Set placaVehiculo
	*
	*Campo que almacena la placa del vehiculo
	*
	* @parámetro String $placaVehiculo
	* @return PlacaVehiculo
	*/
	public function setPlacaVehiculo($placaVehiculo)
	{
	  $this->placaVehiculo = (String) $placaVehiculo;
	    return $this;
	}

	/**
	* Get placaVehiculo
	*
	* @return null|String
	*/
	public function getPlacaVehiculo()
	{
		return $this->placaVehiculo;
	}

	/**
	* Set identificadorPropietarioVehiculo
	*
	*Campo que almacena el identificador del propietario del vehiculo (operador registrado en el sistema GUIA)
	*
	* @parámetro String $identificadorPropietarioVehiculo
	* @return IdentificadorPropietarioVehiculo
	*/
	public function setIdentificadorPropietarioVehiculo($identificadorPropietarioVehiculo)
	{
	  $this->identificadorPropietarioVehiculo = (String) $identificadorPropietarioVehiculo;
	    return $this;
	}

	/**
	* Get identificadorPropietarioVehiculo
	*
	* @return null|String
	*/
	public function getIdentificadorPropietarioVehiculo()
	{
		return $this->identificadorPropietarioVehiculo;
	}

	/**
	* Set marcaVehiculo
	*
	*Campo que almacena la marca del vehiculo
	*
	* @parámetro String $marcaVehiculo
	* @return MarcaVehiculo
	*/
	public function setMarcaVehiculo($marcaVehiculo)
	{
	  $this->marcaVehiculo = (String) $marcaVehiculo;
	    return $this;
	}

	/**
	* Get marcaVehiculo
	*
	* @return null|String
	*/
	public function getMarcaVehiculo()
	{
		return $this->marcaVehiculo;
	}

	/**
	* Set modeloVehiculo
	*
	*Campo que almacena el modelo del vehiculo
	*
	* @parámetro String $modeloVehiculo
	* @return ModeloVehiculo
	*/
	public function setModeloVehiculo($modeloVehiculo)
	{
	  $this->modeloVehiculo = (String) $modeloVehiculo;
	    return $this;
	}

	/**
	* Get modeloVehiculo
	*
	* @return null|String
	*/
	public function getModeloVehiculo()
	{
		return $this->modeloVehiculo;
	}

	/**
	* Set anioVehiculo
	*
	*Campo que almacena el anio del vehiculo
	*
	* @parámetro String $anioVehiculo
	* @return AnioVehiculo
	*/
	public function setAnioVehiculo($anioVehiculo)
	{
	  $this->anioVehiculo = (String) $anioVehiculo;
	    return $this;
	}

	/**
	* Get anioVehiculo
	*
	* @return null|String
	*/
	public function getAnioVehiculo()
	{
		return $this->anioVehiculo;
	}

	/**
	* Set colorVehiculo
	*
	*Campo que almacena el color del vehiculo
	*
	* @parámetro String $colorVehiculo
	* @return ColorVehiculo
	*/
	public function setColorVehiculo($colorVehiculo)
	{
	  $this->colorVehiculo = (String) $colorVehiculo;
	    return $this;
	}

	/**
	* Get colorVehiculo
	*
	* @return null|String
	*/
	public function getColorVehiculo()
	{
		return $this->colorVehiculo;
	}

	/**
	* Set claseVehiculo
	*
	*Campo que almacena la clase del vehiculo
	*
	* @parámetro String $claseVehiculo
	* @return ClaseVehiculo
	*/
	public function setClaseVehiculo($claseVehiculo)
	{
	  $this->claseVehiculo = (String) $claseVehiculo;
	    return $this;
	}

	/**
	* Get claseVehiculo
	*
	* @return null|String
	*/
	public function getClaseVehiculo()
	{
		return $this->claseVehiculo;
	}

	/**
	* Set tipoVehiculo
	*
	*Campo que almacena el tipo de vehiculo
	*
	* @parámetro String $tipoVehiculo
	* @return TipoVehiculo
	*/
	public function setTipoVehiculo($tipoVehiculo)
	{
	  $this->tipoVehiculo = (String) $tipoVehiculo;
	    return $this;
	}

	/**
	* Get tipoVehiculo
	*
	* @return null|String
	*/
	public function getTipoVehiculo()
	{
		return $this->tipoVehiculo;
	}

	/**
	* Set tamanioContenedorVehiculo
	*
	*Campo que almacena el tamanio del contenedor del vehiculo
	*
	* @parámetro String $tamanioContenedorVehiculo
	* @return TamanioContenedorVehiculo
	*/
	public function setTamanioContenedorVehiculo($tamanioContenedorVehiculo)
	{
	  $this->tamanioContenedorVehiculo = (String) $tamanioContenedorVehiculo;
	    return $this;
	}

	/**
	* Get tamanioContenedorVehiculo
	*
	* @return null|String
	*/
	public function getTamanioContenedorVehiculo()
	{
		return $this->tamanioContenedorVehiculo;
	}

	/**
	* Set caracteristicaContenedorVehiculo
	*
	*Campo que almavena las caracteristicas del contenedor del vehiculo
	*
	* @parámetro String $caracteristicaContenedorVehiculo
	* @return CaracteristicaContenedorVehiculo
	*/
	public function setCaracteristicaContenedorVehiculo($caracteristicaContenedorVehiculo)
	{
	  $this->caracteristicaContenedorVehiculo = (String) $caracteristicaContenedorVehiculo;
	    return $this;
	}

	/**
	* Get caracteristicaContenedorVehiculo
	*
	* @return null|String
	*/
	public function getCaracteristicaContenedorVehiculo()
	{
		return $this->caracteristicaContenedorVehiculo;
	}

	/**
	* Set fechaModificacion
	*
	*Campo que almacena la fecha en la que se realizo la actualizacion de los datos del vehiculo (tamanio_contenedor, caracteristica_contenedor)
	*
	* @parámetro Date $fechaModificacion
	* @return FechaModificacion
	*/
	public function setFechaModificacion($fechaModificacion)
	{
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
	* Set fechaCreacion
	*
	*Campo que almacena la fecha en la que se realizo el registro de los datos del vehiculo
	*
	* @parámetro Date $fechaCreacion
	* @return FechaCreacion
	*/
	public function setFechaCreacion($fechaCreacion)
	{
	  $this->fechaCreacion = (String) $fechaCreacion;
	    return $this;
	}

	/**
	* Get fechaCreacion
	*
	* @return null|Date
	*/
	public function getFechaCreacion()
	{
		return $this->fechaCreacion;
	}

	/**
	* Set fechaAprobacion
	*
	*Campo que almacena la fecha en la que se aprueba el registro de datos del vehiculo
	*
	* @parámetro Date $fechaAprobacion
	* @return FechaAprobacion
	*/
	public function setFechaAprobacion($fechaAprobacion)
	{
	  $this->fechaAprobacion = (String) $fechaAprobacion;
	    return $this;
	}

	/**
	* Get fechaAprobacion
	*
	* @return null|Date
	*/
	public function getFechaAprobacion()
	{
		return $this->fechaAprobacion;
	}

	/**
	* Set estadoVehiculo
	*
	*Campo que almacena el estado del vehiculo
	*
	* @parámetro String $estadoVehiculo
	* @return EstadoVehiculo
	*/
	public function setEstadoVehiculo($estadoVehiculo)
	{
	  $this->estadoVehiculo = (String) $estadoVehiculo;
	    return $this;
	}

	/**
	* Get estadoVehiculo
	*
	* @return null|String
	*/
	public function getEstadoVehiculo()
	{
		return $this->estadoVehiculo;
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
	* @return DatosVehiculoTransporteAnimalesModelo
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
