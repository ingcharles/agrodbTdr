<?php
 /**
 * Modelo ResultadoInspeccionModelo
 *
 * Este archivo se complementa con el archivo   ResultadoInspeccionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    ResultadoInspeccionModelo
 * @package InspeccionMusaceas
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionMusaceas\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class ResultadoInspeccionModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
		protected $idResultadoInspeccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Lugar de inspección
		*/
		protected $lugarInspeccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Cantidad aprobada
		*/
		protected $cantidadAprobada;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Numero de contenedores
		*/
		protected $numContenedores;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave foránea de la tabla solicitud_inspeccion
		*/
		protected $idSolicitudInspeccion;
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
		 * resultado
		 */
		protected $resultado;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * resultado
		 */
		protected $observacion;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * identificador operador
		 */
		protected $identificadorOperador;

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
	* Nombre de la tabla: resultado_inspeccion
	* 
	 */
	Private $tabla="resultado_inspeccion";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_resultado_inspeccion";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_inspeccion_musaceas"."resultado_inspeccion_id_resultado_inspeccion_seq'; 



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
		throw new \Exception('Clase Modelo: ResultadoInspeccionModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: ResultadoInspeccionModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idResultadoInspeccion
	*
	*Llave primaria de la tabla
	*
	* @parámetro Integer $idResultadoInspeccion
	* @return IdResultadoInspeccion
	*/
	public function setIdResultadoInspeccion($idResultadoInspeccion)
	{
	  $this->idResultadoInspeccion = (Integer) $idResultadoInspeccion;
	    return $this;
	}

	/**
	* Get idResultadoInspeccion
	*
	* @return null|Integer
	*/
	public function getIdResultadoInspeccion()
	{
		return $this->idResultadoInspeccion;
	}

	/**
	* Set lugarInspeccion
	*
	*Lugar de inspección
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
	* Set cantidadAprobada
	*
	*Cantidad aprobada
	*
	* @parámetro String $cantidadAprobada
	* @return CantidadAprobada
	*/
	public function setCantidadAprobada($cantidadAprobada)
	{
	  $this->cantidadAprobada = (String) $cantidadAprobada;
	    return $this;
	}

	/**
	* Get cantidadAprobada
	*
	* @return null|String
	*/
	public function getCantidadAprobada()
	{
		return $this->cantidadAprobada;
	}

	/**
	* Set numContenedores
	*
	*Numero de contenedores
	*
	* @parámetro String $numContenedores
	* @return NumContenedores
	*/
	public function setNumContenedores($numContenedores)
	{
	  $this->numContenedores = (String) $numContenedores;
	    return $this;
	}

	/**
	* Get numContenedores
	*
	* @return null|String
	*/
	public function getNumContenedores()
	{
		return $this->numContenedores;
	}

	/**
	 * Set resultado
	 *
	 *Resultado de la inspeccion
	 *
	 * @parámetro String $resultado
	 * @return Resultado
	 */
	public function setResultado($resultado)
	{
	    $this->resultado = (String) $resultado;
	    return $this;
	}
	
	/**
	 * Get resultado
	 *
	 * @return null|String
	 */
	public function getResultado()
	{
	    return $this->resultado;
	}
	/**
	* Set idSolicitudInspeccion
	*
	*Llave foránea de la tabla solicitud_inspeccion
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
	 * Set observacion
	 *
	 *Observacion
	 *
	 * @parámetro Date $fechaCreacion
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
	 * @return null|string
	 */
	public function getObservacion()
	{
	    return $this->observacion;
	}
	/**
	 * Set identficador operador
	 *
	 *identificadorOperador
	 *
	 * @parámetro Date $identificadorOperador
	 * @return identificadorOperador
	 */
	public function setIdentificadorOperador($identificadorOperador)
	{
	    $this->identificadorOperador = (String) $identificadorOperador;
	    return $this;
	}
	
	/**
	 * Get identificadorOperador
	 *
	 * @return null|string
	 */
	public function getIdentificadorOperador()
	{
	    return $this->identificadorOperador;
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
	* @return ResultadoInspeccionModelo
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
