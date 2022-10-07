<?php
 /**
 * Modelo SolicitudInspeccionModelo
 *
 * Este archivo se complementa con el archivo   SolicitudInspeccionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    SolicitudInspeccionModelo
 * @package InspeccionMusaceas
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionMusaceas\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class SolicitudInspeccionModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
		protected $idSolicitudInspeccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Producto de exportación
		*/
		protected $producto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Marca de exportación
		*/
		protected $marca;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Tipo de producción de exportación
		*/
		protected $tipoProduccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Viaje de exportación
		*/
		protected $viaje;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* País destino de exportación
		*/
		protected $paisDestino;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Puerto de embarque exportación
		*/
		protected $puertoEmbarque;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre de vapor exportación
		*/
		protected $nombreVapor;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Estado del registro
		*/
		protected $estado;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de creación del registro
		*/
		protected $fechaCreacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Código de la solicitud
		*/
		protected $codigoSolicitud;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Ruta de archivo PDF
		*/
		protected $rutaArchivo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del Exportador
		*/
		protected $identificador;
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
		* Lugar de inspeccion
		*/
		protected $lugarInspeccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre del lugar de inspección
		*/
		protected $nombreInspeccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Representante técnico
		*/
		protected $representanteTecnico;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Celular de inspección
		*/
		protected $celularInspeccion;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * Identificador registro
		 */
		protected $identificadorRegistro;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * Identificador Inspeccion externa
		 */
		protected $identificadorInspeccionExterna;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_inspeccion_musaceas";

	/**
	* Nombre de la tabla: solicitud_inspeccion
	* 
	 */
	Private $tabla="solicitud_inspeccion";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_solicitud_inspeccion";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_inspeccion_musaceas"."solicitud_inspeccion_id_solicitud_inspeccion_seq';



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
		throw new \Exception('Clase Modelo: SolicitudInspeccionModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: SolicitudInspeccionModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_inspeccion_musaceas
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idSolicitudInspeccion
	*
	*Llave primaria de la tabla
	*
	* @parámetro Integer $idSolicitudInspeccion
	* @return IdSolicitudInspeccion
	*/
	public function setIdSolicitudInspeccion($idSolicitudInspeccion)
	{
	  $this->idSolicitudInspeccion = (Integer) $idSolicitudInspeccion;
	    return $this;
	}

	/**
	* Get idSolicitudInspeccion
	*
	* @return null|Integer
	*/
	public function getIdSolicitudInspeccion()
	{
		return $this->idSolicitudInspeccion;
	}

	/**
	* Set producto
	*
	*Producto de exportación
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
	* Set marca
	*
	*Marca de exportación
	*
	* @parámetro String $marca
	* @return Marca
	*/
	public function setMarca($marca)
	{
	  $this->marca = (String) $marca;
	    return $this;
	}

	/**
	* Get marca
	*
	* @return null|String
	*/
	public function getMarca()
	{
		return $this->marca;
	}

	/**
	* Set tipoProduccion
	*
	*Tipo de producción de exportación
	*
	* @parámetro String $tipoProduccion
	* @return TipoProduccion
	*/
	public function setTipoProduccion($tipoProduccion)
	{
	  $this->tipoProduccion = (String) $tipoProduccion;
	    return $this;
	}

	/**
	* Get tipoProduccion
	*
	* @return null|String
	*/
	public function getTipoProduccion()
	{
		return $this->tipoProduccion;
	}

	/**
	* Set viaje
	*
	*Viaje de exportación
	*
	* @parámetro String $viaje
	* @return Viaje
	*/
	public function setViaje($viaje)
	{
	  $this->viaje = (String) $viaje;
	    return $this;
	}

	/**
	* Get viaje
	*
	* @return null|String
	*/
	public function getViaje()
	{
		return $this->viaje;
	}

	/**
	* Set paisDestino
	*
	*País destino de exportación
	*
	* @parámetro String $paisDestino
	* @return PaisDestino
	*/
	public function setPaisDestino($paisDestino)
	{
	  $this->paisDestino = (String) $paisDestino;
	    return $this;
	}

	/**
	* Get paisDestino
	*
	* @return null|String
	*/
	public function getPaisDestino()
	{
		return $this->paisDestino;
	}

	/**
	* Set puertoEmbarque
	*
	*Puerto de embarque exportación
	*
	* @parámetro String $puertoEmbarque
	* @return PuertoEmbarque
	*/
	public function setPuertoEmbarque($puertoEmbarque)
	{
	  $this->puertoEmbarque = (String) $puertoEmbarque;
	    return $this;
	}

	/**
	* Get puertoEmbarque
	*
	* @return null|String
	*/
	public function getPuertoEmbarque()
	{
		return $this->puertoEmbarque;
	}

	/**
	* Set nombreVapor
	*
	*Nombre de vapor exportación
	*
	* @parámetro String $nombreVapor
	* @return NombreVapor
	*/
	public function setNombreVapor($nombreVapor)
	{
	  $this->nombreVapor = (String) $nombreVapor;
	    return $this;
	}

	/**
	* Get nombreVapor
	*
	* @return null|String
	*/
	public function getNombreVapor()
	{
		return $this->nombreVapor;
	}

	/**
	* Set estado
	*
	*Estado del registro
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
	* Set fechaCreacion
	*
	*Fecha de creación del registro
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
	* Set codigoSolicitud
	*
	*Código de la solicitud
	*
	* @parámetro String $codigoSolicitud
	* @return CodigoSolicitud
	*/
	public function setCodigoSolicitud($codigoSolicitud)
	{
	  $this->codigoSolicitud = (String) $codigoSolicitud;
	    return $this;
	}

	/**
	* Get codigoSolicitud
	*
	* @return null|String
	*/
	public function getCodigoSolicitud()
	{
		return $this->codigoSolicitud;
	}

	/**
	* Set rutaArchivo
	*
	*Ruta de archivo PDF
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
	* Set identificador
	*
	*Identificador del Exportador
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
	 * Set IdentificadorInspeccionExterna
	 *
	 * @parámetro String $identificadorInspeccionExterna
	 * @return IdentificadorInspeccionExterna
	 */
	public function setIdentificadorInspeccionExterna($identificadorInspeccionExterna)
	{
		$this->identificadorInspeccionExterna = (String) $identificadorInspeccionExterna;
		return $this;
	}
	
	/**
	 * Get identificadorInspeccionExterna
	 *
	 * @return null|String
	 */
	public function getIdentificadorInspeccionExterna()
	{
		return $this->identificadorInspeccionExterna;
	}
	/**
	* Set lugarInspeccion
	*
	*Lugar de inspeccion
	*
	* @parámetro String $lugarInspeccion
	* @return LugarInspeccion
	*/
	public function setLugarInspeccion($lugarInspeccion)
	{
	  $this->lugarInspeccion = (String) $lugarInspeccion;
	    return $this;
	}

	/**
	* Get lugarInspeccion
	*
	* @return null|String
	*/
	public function getLugarInspeccion()
	{
		return $this->lugarInspeccion;
	}

	/**
	* Set nombreInspeccion
	*
	*Nombre del lugar de inspección
	*
	* @parámetro String $nombreInspeccion
	* @return NombreInspeccion
	*/
	public function setNombreInspeccion($nombreInspeccion)
	{
	  $this->nombreInspeccion = (String) $nombreInspeccion;
	    return $this;
	}

	/**
	* Get nombreInspeccion
	*
	* @return null|String
	*/
	public function getNombreInspeccion()
	{
		return $this->nombreInspeccion;
	}

	/**
	* Set representanteTecnico
	*
	*Representante técnico
	*
	* @parámetro String $representanteTecnico
	* @return RepresentanteTecnico
	*/
	public function setRepresentanteTecnico($representanteTecnico)
	{
	  $this->representanteTecnico = (String) $representanteTecnico;
	    return $this;
	}

	/**
	* Get representanteTecnico
	*
	* @return null|String
	*/
	public function getRepresentanteTecnico()
	{
		return $this->representanteTecnico;
	}

	/**
	* Set celularInspeccion
	*
	*Celular de inspección
	*
	* @parámetro String $celularInspeccion
	* @return CelularInspeccion
	*/
	public function setCelularInspeccion($celularInspeccion)
	{
	  $this->celularInspeccion = (String) $celularInspeccion;
	    return $this;
	}

	/**
	* Get celularInspeccion
	*
	* @return null|String
	*/
	public function getCelularInspeccion()
	{
		return $this->celularInspeccion;
	}
	/**
	 * Set identificadorRegistro
	 *
	 * @parámetro String $identificadorRegistro
	 * @return Identificador Registro
	 */
	public function setIdentificadorRegistro($identificadorRegistro)
	{
	    $this->identificadorRegistro = (String) $identificadorRegistro;
	    return $this;
	}
	/**
	 * Get identificadorRegistro
	 *
	 * @return null|String
	 */
	public function getIdentificadorRegistro()
	{
	    return $this->identificadorRegistro;
	}
	/** c
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
	* @return SolicitudInspeccionModelo
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
