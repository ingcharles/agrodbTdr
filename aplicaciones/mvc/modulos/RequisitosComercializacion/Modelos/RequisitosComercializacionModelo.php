<?php
 /**
 * Modelo RequisitosComercializacionModelo
 *
 * Este archivo se complementa con el archivo   RequisitosComercializacionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-06-06
 * @uses    RequisitosComercializacionModelo
 * @package RequisitosComercializacion
 * @subpackage Modelos
 */
  namespace Agrodb\RequisitosComercializacion\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class RequisitosComercializacionModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador único de la tabla
		*/
		protected $idRequisitoComercio;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre por el cual fue declarado el requsitio.
		*/
		protected $declaracion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Número de resolución asociada a la creación de requisito
		*/
		protected $numeroResolucion;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de creación del reuqisito
		*/
		protected $fecha;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Observaciones relacionadas con el requisito.
		*/
		protected $observacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Ruta del archvio de la resolución del requisito
		*/
		protected $rutaArchivo;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la tabla lozalización que determina el país al que pertenece el requisito
		*/
		protected $idLocalizacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la tabla productos que determina el producto al que pertenece el requisito
		*/
		protected $idProducto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Área a la cual pertenece el requisito.
		*/
		protected $tipo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nomrbe del país al cual pertenece el requisito.
		*/
		protected $nombrePais;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre del producto al cual petenece el requisito
		*/
		protected $nombreProducto;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de creación del registro
		*/
		protected $fechaCreacion;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de módificación del registro
		*/
		protected $fechaModificacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del funcionario que crea el registro.
		*/
		protected $identificadorCreacionRequisitoComercializacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del funcionario que modifica el registro.
		*/
		protected $identificadorModificacionRequisitoComercializacion;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_requisitos";

	/**
	* Nombre de la tabla: requisitos_comercializacion
	* 
	 */
	Private $tabla="requisitos_comercializacion";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_requisito_comercio";



	/**
	*Secuencia
*/
		 private $secuencial = '"RequisitosComercializacion_"id_requisito_comercio_seq'; 



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
		throw new \Exception('Clase Modelo: RequisitosComercializacionModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: RequisitosComercializacionModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_requisitos
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idRequisitoComercio
	*
	*Identificador único de la tabla
	*
	* @parámetro Integer $idRequisitoComercio
	* @return IdRequisitoComercio
	*/
	public function setIdRequisitoComercio($idRequisitoComercio)
	{
	  $this->idRequisitoComercio = (Integer) $idRequisitoComercio;
	    return $this;
	}

	/**
	* Get idRequisitoComercio
	*
	* @return null|Integer
	*/
	public function getIdRequisitoComercio()
	{
		return $this->idRequisitoComercio;
	}

	/**
	* Set declaracion
	*
	*Nombre por el cual fue declarado el requsitio.
	*
	* @parámetro String $declaracion
	* @return Declaracion
	*/
	public function setDeclaracion($declaracion)
	{
	  $this->declaracion = (String) $declaracion;
	    return $this;
	}

	/**
	* Get declaracion
	*
	* @return null|String
	*/
	public function getDeclaracion()
	{
		return $this->declaracion;
	}

	/**
	* Set numeroResolucion
	*
	*Número de resolución asociada a la creación de requisito
	*
	* @parámetro String $numeroResolucion
	* @return NumeroResolucion
	*/
	public function setNumeroResolucion($numeroResolucion)
	{
	  $this->numeroResolucion = (String) $numeroResolucion;
	    return $this;
	}

	/**
	* Get numeroResolucion
	*
	* @return null|String
	*/
	public function getNumeroResolucion()
	{
		return $this->numeroResolucion;
	}

	/**
	* Set fecha
	*
	*Fecha de creación del reuqisito
	*
	* @parámetro Date $fecha
	* @return Fecha
	*/
	public function setFecha($fecha)
	{
	  $this->fecha = (String) $fecha;
	    return $this;
	}

	/**
	* Get fecha
	*
	* @return null|Date
	*/
	public function getFecha()
	{
		return $this->fecha;
	}

	/**
	* Set observacion
	*
	*Observaciones relacionadas con el requisito.
	*
	* @parámetro String $observacion
	* @return Observacion
	*/
	public function setObservacion($observacion)
	{
	  $this->observacion = (String) $observacion;
	    return $this;
	}

	/**
	* Get observacion
	*
	* @return null|String
	*/
	public function getObservacion()
	{
		return $this->observacion;
	}

	/**
	* Set rutaArchivo
	*
	*Ruta del archvio de la resolución del requisito
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
	* Set idLocalizacion
	*
	*Identificador de la tabla lozalización que determina el país al que pertenece el requisito
	*
	* @parámetro Integer $idLocalizacion
	* @return IdLocalizacion
	*/
	public function setIdLocalizacion($idLocalizacion)
	{
	  $this->idLocalizacion = (Integer) $idLocalizacion;
	    return $this;
	}

	/**
	* Get idLocalizacion
	*
	* @return null|Integer
	*/
	public function getIdLocalizacion()
	{
		return $this->idLocalizacion;
	}

	/**
	* Set idProducto
	*
	*Identificador de la tabla productos que determina el producto al que pertenece el requisito
	*
	* @parámetro Integer $idProducto
	* @return IdProducto
	*/
	public function setIdProducto($idProducto)
	{
	  $this->idProducto = (Integer) $idProducto;
	    return $this;
	}

	/**
	* Get idProducto
	*
	* @return null|Integer
	*/
	public function getIdProducto()
	{
		return $this->idProducto;
	}

	/**
	* Set tipo
	*
	*Área a la cual pertenece el requisito.
	*
	* @parámetro String $tipo
	* @return Tipo
	*/
	public function setTipo($tipo)
	{
	  $this->tipo = (String) $tipo;
	    return $this;
	}

	/**
	* Get tipo
	*
	* @return null|String
	*/
	public function getTipo()
	{
		return $this->tipo;
	}

	/**
	* Set nombrePais
	*
	*Nomrbe del país al cual pertenece el requisito.
	*
	* @parámetro String $nombrePais
	* @return NombrePais
	*/
	public function setNombrePais($nombrePais)
	{
	  $this->nombrePais = (String) $nombrePais;
	    return $this;
	}

	/**
	* Get nombrePais
	*
	* @return null|String
	*/
	public function getNombrePais()
	{
		return $this->nombrePais;
	}

	/**
	* Set nombreProducto
	*
	*Nombre del producto al cual petenece el requisito
	*
	* @parámetro String $nombreProducto
	* @return NombreProducto
	*/
	public function setNombreProducto($nombreProducto)
	{
	  $this->nombreProducto = (String) $nombreProducto;
	    return $this;
	}

	/**
	* Get nombreProducto
	*
	* @return null|String
	*/
	public function getNombreProducto()
	{
		return $this->nombreProducto;
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
	* Set fechaModificacion
	*
	*Fecha de módificación del registro
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
	* Set identificadorCreacionRequisitoComercializacion
	*
	*Identificador del funcionario que crea el registro.
	*
	* @parámetro String $identificadorCreacionRequisitoComercializacion
	* @return IdentificadorCreacionRequisitoComercializacion
	*/
	public function setIdentificadorCreacionRequisitoComercializacion($identificadorCreacionRequisitoComercializacion)
	{
	  $this->identificadorCreacionRequisitoComercializacion = (String) $identificadorCreacionRequisitoComercializacion;
	    return $this;
	}

	/**
	* Get identificadorCreacionRequisitoComercializacion
	*
	* @return null|String
	*/
	public function getIdentificadorCreacionRequisitoComercializacion()
	{
		return $this->identificadorCreacionRequisitoComercializacion;
	}

	/**
	* Set identificadorModificacionRequisitoComercializacion
	*
	*Identificador del funcionario que modifica el registro.
	*
	* @parámetro String $identificadorModificacionRequisitoComercializacion
	* @return IdentificadorModificacionRequisitoComercializacion
	*/
	public function setIdentificadorModificacionRequisitoComercializacion($identificadorModificacionRequisitoComercializacion)
	{
	  $this->identificadorModificacionRequisitoComercializacion = (String) $identificadorModificacionRequisitoComercializacion;
	    return $this;
	}

	/**
	* Get identificadorModificacionRequisitoComercializacion
	*
	* @return null|String
	*/
	public function getIdentificadorModificacionRequisitoComercializacion()
	{
		return $this->identificadorModificacionRequisitoComercializacion;
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
	* @return RequisitosComercializacionModelo
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
