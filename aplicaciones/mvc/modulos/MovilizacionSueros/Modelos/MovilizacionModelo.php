<?php
 /**
 * Modelo MovilizacionModelo
 *
 * Este archivo se complementa con el archivo   MovilizacionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-04-03
 * @uses    MovilizacionModelo
 * @package MovilizacionSueros
 * @subpackage Modelos
 */
  namespace Agrodb\MovilizacionSueros\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class MovilizacionModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador único de la tabla movilizacion
		*/
		protected $idMovilizacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la identificación del sitio de origen donde se encuentren operaciones de "industria láctea"
		*/
		protected $idSitioOrigen;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la identificación del area de origen donde se encuentren operaciones de "industria láctea"
		*/
		protected $idAreaOrigen;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del uso del suero tabla de catálogos
		*/
		protected $idUsoSuero;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del destinatario del suero
		*/
		protected $identificadorOperadorDestino;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre del operador de destino del suero
		*/
		protected $nombreOperadorDestino;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la provincia donde hacia donde se moviliza el suero
		*/
		protected $idProvincia;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del cantón hacia donde se moviliza el suero
		*/
		protected $idCanton;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la parroquia hacia donde se moviliza el suero
		*/
		protected $idParroquia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Dirección del operador hacia donde se moviliza el suero
		*/
		protected $direccionOperadorDestino;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del operador que transporta el suero movilizado
		*/
		protected $identificadorOperadorTransportista;
		
		/**
		 * @var String
		 * codigo certificado
		 */
		protected $codigoCertificado;
		
		/**
		 * @var String
		 * ruta del certificado
		 */
		protected $rutaCertificado;
		
		/**
		 * @var String
		 * estado del certificado
		 */
		protected $estado;
		
		/**
		 * @var String
		 * identificadorOperador
		 */
		protected $identificadorOperador;
		
		/**
		 * @var integer
		 * idDetalleUsoSuero
		 */
		protected $idDetalleUsoSuero;
		
		

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_movilizacion_suero";

	/**
	* Nombre de la tabla: movilizacion
	* 
	 */
	Private $tabla="movilizacion";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_movilizacion";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_movilizacion_suero"."movilizacion_id_movilizacion_seq';



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
		throw new \Exception('Clase Modelo: MovilizacionModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: MovilizacionModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_movilizacion_suero
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idMovilizacion
	*
	*Identificador único de la tabla movilizacion
	*
	* @parámetro Integer $idMovilizacion
	* @return IdMovilizacion
	*/
	public function setIdMovilizacion($idMovilizacion)
	{
	  $this->idMovilizacion = (Integer) $idMovilizacion;
	    return $this;
	}

	/**
	* Get idMovilizacion
	*
	* @return null|Integer
	*/
	public function getIdMovilizacion()
	{
		return $this->idMovilizacion;
	}

	/**
	* Set idSitioOrigen
	*
	*Campo que almacena la identificación del sitio de origen donde se encuentren operaciones de "industria láctea"
	*
	* @parámetro Integer $idSitioOrigen
	* @return IdSitioOrigen
	*/
	public function setIdSitioOrigen($idSitioOrigen)
	{
	  $this->idSitioOrigen = (Integer) $idSitioOrigen;
	    return $this;
	}

	/**
	* Get idSitioOrigen
	*
	* @return null|Integer
	*/
	public function getIdSitioOrigen()
	{
		return $this->idSitioOrigen;
	}

	/**
	* Set idAreaOrigen
	*
	*Campo que almacena la identificación del area de origen donde se encuentren operaciones de "industria láctea"
	*
	* @parámetro Integer $idAreaOrigen
	* @return IdAreaOrigen
	*/
	public function setIdAreaOrigen($idAreaOrigen)
	{
	  $this->idAreaOrigen = (Integer) $idAreaOrigen;
	    return $this;
	}

	/**
	* Get idAreaOrigen
	*
	* @return null|Integer
	*/
	public function getIdAreaOrigen()
	{
		return $this->idAreaOrigen;
	}

	/**
	* Set idUsoSuero
	*
	*Identificador del uso del suero tabla de catálogos
	*
	* @parámetro Integer $idUsoSuero
	* @return IdUsoSuero
	*/
	public function setIdUsoSuero($idUsoSuero)
	{
	  $this->idUsoSuero = (Integer) $idUsoSuero;
	    return $this;
	}

	/**
	* Get idUsoSuero
	*
	* @return null|Integer
	*/
	public function getIdUsoSuero()
	{
		return $this->idUsoSuero;
	}

	/**
	* Set identificadorOperadorDestino
	*
	*Identificador del destinatario del suero
	*
	* @parámetro String $identificadorOperadorDestino
	* @return IdentificadorOperadorDestino
	*/
	public function setIdentificadorOperadorDestino($identificadorOperadorDestino)
	{
	  $this->identificadorOperadorDestino = (String) $identificadorOperadorDestino;
	    return $this;
	}

	/**
	* Get identificadorOperadorDestino
	*
	* @return null|String
	*/
	public function getIdentificadorOperadorDestino()
	{
		return $this->identificadorOperadorDestino;
	}

	/**
	* Set nombreOperadorDestino
	*
	*Nombre del operador de destino del suero
	*
	* @parámetro String $nombreOperadorDestino
	* @return NombreOperadorDestino
	*/
	public function setNombreOperadorDestino($nombreOperadorDestino)
	{
	  $this->nombreOperadorDestino = (String) $nombreOperadorDestino;
	    return $this;
	}

	/**
	* Get nombreOperadorDestino
	*
	* @return null|String
	*/
	public function getNombreOperadorDestino()
	{
		return $this->nombreOperadorDestino;
	}

	/**
	* Set idProvincia
	*
	*Identificador de la provincia donde hacia donde se moviliza el suero
	*
	* @parámetro Integer $idProvincia
	* @return IdProvincia
	*/
	public function setIdProvincia($idProvincia)
	{
	  $this->idProvincia = (Integer) $idProvincia;
	    return $this;
	}

	/**
	* Get idProvincia
	*
	* @return null|Integer
	*/
	public function getIdProvincia()
	{
		return $this->idProvincia;
	}

	/**
	* Set idCanton
	*
	*Identificador del cantón hacia donde se moviliza el suero
	*
	* @parámetro Integer $idCanton
	* @return IdCanton
	*/
	public function setIdCanton($idCanton)
	{
	  $this->idCanton = (Integer) $idCanton;
	    return $this;
	}

	/**
	* Get idCanton
	*
	* @return null|Integer
	*/
	public function getIdCanton()
	{
		return $this->idCanton;
	}

	/**
	* Set idParroquia
	*
	*Identificador de la parroquia hacia donde se moviliza el suero
	*
	* @parámetro Integer $idParroquia
	* @return IdParroquia
	*/
	public function setIdParroquia($idParroquia)
	{
	  $this->idParroquia = (Integer) $idParroquia;
	    return $this;
	}

	/**
	* Get idParroquia
	*
	* @return null|Integer
	*/
	public function getIdParroquia()
	{
		return $this->idParroquia;
	}

	/**
	* Set direccionOperadorDestino
	*
	*Dirección del operador hacia donde se moviliza el suero
	*
	* @parámetro String $direccionOperadorDestino
	* @return DireccionOperadorDestino
	*/
	public function setDireccionOperadorDestino($direccionOperadorDestino)
	{
	  $this->direccionOperadorDestino = (String) $direccionOperadorDestino;
	    return $this;
	}

	/**
	* Get direccionOperadorDestino
	*
	* @return null|String
	*/
	public function getDireccionOperadorDestino()
	{
		return $this->direccionOperadorDestino;
	}

	/**
	* Set identificadorOperadorTransportista
	*
	*Identificador del operador que transporta el suero movilizado
	*
	* @parámetro String $identificadorOperadorTransportista
	* @return IdentificadorOperadorTransportista
	*/
	public function setIdentificadorOperadorTransportista($identificadorOperadorTransportista)
	{
	  $this->identificadorOperadorTransportista = (String) $identificadorOperadorTransportista;
	    return $this;
	}

	/**
	* Get identificadorOperadorTransportista
	*
	* @return null|String
	*/
	public function getIdentificadorOperadorTransportista()
	{
		return $this->identificadorOperadorTransportista;
	}

	/**
	 * Set codigoCertificado
	 *
	 */
	public function setCodigoCertificado($codigoCertificado)
	{
		$this->codigoCertificado = (String) $codigoCertificado;
		return $this;
	}
	
	/**
	 * Get $codigoCertificado
	 *
	 * @return null|string
	 */
	public function getCodigoCertificado()
	{
		return $this->codigoCertificado;
	}
	/**
	 * Set rutaCertificado
	 *
	 */
	public function setRutaCertificado($rutaCertificado)
	{
		$this->rutaCertificado = (string) $rutaCertificado;
		return $this;
	}
	/**
	 * Get rutaCertificado
	 *
	 * @return null|Integer
	 */
	public function getRutaCertificado()
	{
		return $this->rutaCertificado;
	}
	
	/**
	 * Get estado
	 *
	 */
	public function getEstado()
	{
		return $this->estado;
	}
	/**
	 * Set estado
	 *
	 */
	public function setEstado($estado)
	{
		$this->estado = (String) $estado;
		return $this;
	}
	
	/**
	 * Get identificadorOperador
	 *
	 */
	public function getIdentificadorOperador()
	{
		return $this->identificadorOperador;
	}
	/**
	 * Set identificadorOperador
	 *
	 */
	public function setIdentificadorOperador($identificadorOperador)
	{
		$this->identificadorOperador = (String) $identificadorOperador;
		return $this;
	}
	
	
	/**
	 * Get idDetalleUsoSuero
	 *
	 */
	public function getIdDetalleUsoSuero()
	{
		return $this->idDetalleUsoSuero;
	}
	/**
	 * Set idDetalleUsoSuero
	 *
	 */
	public function setIdDetalleUsoSuero($idDetalleUsoSuero)
	{
		$this->idDetalleUsoSuero = (Integer) $idDetalleUsoSuero;
		return $this;
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
	* @return MovilizacionModelo
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
