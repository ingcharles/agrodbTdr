<?php
 /**
 * Modelo TipoInspectorModelo
 *
 * Este archivo se complementa con el archivo   TipoInspectorLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2018-11-21
 * @uses    TipoInspectorModelo
 * @package CentrosFaenamiento
 * @subpackage Modelos
 */
  namespace Agrodb\CentrosFaenamiento\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class TipoInspectorModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo oculto en el formulario o manejado internamente
		* llave primaria de la tabla
		*/
		protected $idTipoInspector;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* identificador del inspector
		*/
		protected $identificadorOperador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* resultado de la revision
		*/
		protected $resultado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* identificar el tipo de inspector
		*/
		protected $tipoInspector;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* observacion del registro
		*/
		protected $observacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo oculto en el formulario o manejado internamente
		* llave foreania de la tabla operadores_tipo_operaciones
		*/
		protected $idOperadorTipoOperacion;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* fecha de creacion del registro
		*/
		protected $fechaCreacion;
		/**
		* @var String
		* Campo requerido
		* Campo oculto en el formulario o manejado internamente
		* identificador del que creo el registro
		*/
		protected $identificadorRegistro;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible
		 * nombre del operador
		 */
		protected $nombreOperador;
		
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible
		 * provincia del operador
		 */
		protected $provincia;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_centros_faenamiento";

	/**
	* Nombre de la tabla: tipo_inspector
	* 
	 */
	Private $tabla="tipo_inspector";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_tipo_inspector";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_centros_faenamiento"."tipo_inspector_id_tipo_inspector_seq'; 



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
		throw new \Exception('Clase Modelo: TipoInspectorModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: TipoInspectorModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_centros_faenamiento
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idTipoInspector
	*
	*llave primaria de la tabla
	*
	* @parámetro Integer $idTipoInspector
	* @return IdTipoInspector
	*/
	public function setIdTipoInspector($idTipoInspector)
	{
	  $this->idTipoInspector = (Integer) $idTipoInspector;
	    return $this;
	}

	/**
	* Get idTipoInspector
	*
	* @return null|Integer
	*/
	public function getIdTipoInspector()
	{
		return $this->idTipoInspector;
	}

	/**
	* Set identificadorOperador
	*
	*identificador del operador
	*
	* @parámetro String $identificadorOperador
	* @return IdentificadorOperador
	*/
	public function setIdentificadorOperador($identificadorOperador)
	{
	  $this->identificadorOperador = (String) $identificadorOperador;
	    return $this;
	}

	/**
	* Get identificadorOperador
	*
	* @return null|String
	*/
	public function getIdentificadorOperador()
	{
		return $this->identificadorOperador;
	}

	/**
	* Set resultado
	*
	*resultado de la revision
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
	* Set tipoInspector
	*
	*identificar el tipo de inspector
	*
	* @parámetro String $tipoInspector
	* @return TipoInspector
	*/
	public function setTipoInspector($tipoInspector)
	{
	  $this->tipoInspector = (String) $tipoInspector;
	    return $this;
	}

	/**
	* Get tipoInspector
	*
	* @return null|String
	*/
	public function getTipoInspector()
	{
		return $this->tipoInspector;
	}

	/**
	* Set observacion
	*
	*observacion del registro
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
	* Set idOperadorTipoOperacion
	*
	*llave foreania de la tabla operadores_tipo_operaciones
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
	* Set fechaCreacion
	*
	*fecha de creacion del registro
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
	* Set identificadorRegistro
	*
	*identificador del que creo el registro
	*
	* @parámetro String $identificadorRegistro
	* @return IdentificadorRegistro
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

	/**
	* Get nombreOperador
	*
	* @return null|String
	*/
	public function getNombreOperador()
	{
		return $this->nombreOperador;
	}
	
	/**
	 * Set nombreOperador
	 *
	 *estado del registro
	 *
	 * @parámetro String $estado
	 * @return Estado
	 */
	public function setNombreOperador($nombreOperador)
	{
		$this->nombreOperador = (String) $nombreOperador;
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
	 * Set provincia
	 *
	 *estado del registro
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
	* @return TipoInspectorModelo
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
