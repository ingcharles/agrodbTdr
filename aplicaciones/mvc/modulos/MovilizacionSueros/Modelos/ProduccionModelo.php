<?php
 /**
 * Modelo ProduccionModelo
 *
 * Este archivo se complementa con el archivo   ProduccionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-04-03
 * @uses    ProduccionModelo
 * @package MovilizacionSueros
 * @subpackage Modelos
 */
  namespace Agrodb\MovilizacionSueros\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class ProduccionModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador único de la tabla produccion
		*/
		protected $idProduccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del operador productor
		*/
		protected $identificadorOperador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la cantidad de leche acopiada por el operador
		*/
		protected $cantidadLecheAcopio;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la cantidad de leche que se va a destinar para la producción de quesos
		*/
		protected $cantidadLecheProduccion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del producto de tipo "productos lácteos", subtipo "queso"
		*/
		protected $idProductoQueso;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre del produto de tipo "productos lácteos", subtipo "queso"
		*/
		protected $nombreProductoQueso;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la cantidad de quesos producidos
		*/
		protected $cantidadQuesoProduccion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del producto de tipo "industrias lácteas", subtipo "suero"
		*/
		protected $idProductoSuero;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre del produto de tipo "productos lácteos", subtipo "suero"
		*/
		protected $nombreProductoSuero;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la cantidad de suero producido por el operador
		*/
		protected $cantidadSueroProduccion;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de producción del suero registrado
		*/
		protected $fechaProduccionSuero;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * estado del registro
		 */
		protected $estado;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * Identificador del registro
		 */
		protected $identificador;

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
	* Nombre de la tabla: produccion
	* 
	 */
	Private $tabla="produccion";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_produccion";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_movilizacion_suero"."produccion_id_produccion_seq'; 



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
		throw new \Exception('Clase Modelo: ProduccionModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: ProduccionModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idProduccion
	*
	*Identificador único de la tabla produccion
	*
	* @parámetro Integer $idProduccion
	* @return IdProduccion
	*/
	public function setIdProduccion($idProduccion)
	{
	  $this->idProduccion = (Integer) $idProduccion;
	    return $this;
	}

	/**
	* Get idProduccion
	*
	* @return null|Integer
	*/
	public function getIdProduccion()
	{
		return $this->idProduccion;
	}

	/**
	* Set identificadorOperador
	*
	*Identificador del operador productor
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
	* Set cantidadLecheAcopio
	*
	*Campo que almacena la cantidad de leche acopiada por el operador
	*
	* @parámetro String $cantidadLecheAcopio
	* @return CantidadLecheAcopio
	*/
	public function setCantidadLecheAcopio($cantidadLecheAcopio)
	{
	  $this->cantidadLecheAcopio = (String) $cantidadLecheAcopio;
	    return $this;
	}

	/**
	* Get cantidadLecheAcopio
	*
	* @return null|String
	*/
	public function getCantidadLecheAcopio()
	{
		return $this->cantidadLecheAcopio;
	}

	/**
	* Set cantidadLecheProduccion
	*
	*Campo que almacena la cantidad de leche que se va a destinar para la producción de quesos
	*
	* @parámetro String $cantidadLecheProduccion
	* @return CantidadLecheProduccion
	*/
	public function setCantidadLecheProduccion($cantidadLecheProduccion)
	{
	  $this->cantidadLecheProduccion = (String) $cantidadLecheProduccion;
	    return $this;
	}

	/**
	* Get cantidadLecheProduccion
	*
	* @return null|String
	*/
	public function getCantidadLecheProduccion()
	{
		return $this->cantidadLecheProduccion;
	}

	/**
	* Set idProductoQueso
	*
	*Identificador del producto de tipo "productos lácteos", subtipo "queso"
	*
	* @parámetro Integer $idProductoQueso
	* @return IdProductoQueso
	*/
	public function setIdProductoQueso($idProductoQueso)
	{
	  $this->idProductoQueso = (Integer) $idProductoQueso;
	    return $this;
	}

	/**
	* Get idProductoQueso
	*
	* @return null|Integer
	*/
	public function getIdProductoQueso()
	{
		return $this->idProductoQueso;
	}

	/**
	* Set nombreProductoQueso
	*
	*Nombre del produto de tipo "productos lácteos", subtipo "queso"
	*
	* @parámetro String $nombreProductoQueso
	* @return NombreProductoQueso
	*/
	public function setNombreProductoQueso($nombreProductoQueso)
	{
	  $this->nombreProductoQueso = (String) $nombreProductoQueso;
	    return $this;
	}

	/**
	* Get nombreProductoQueso
	*
	* @return null|String
	*/
	public function getNombreProductoQueso()
	{
		return $this->nombreProductoQueso;
	}

	/**
	* Set cantidadQuesoProduccion
	*
	*Campo que almacena la cantidad de quesos producidos
	*
	* @parámetro String $cantidadQuesoProduccion
	* @return CantidadQuesoProduccion
	*/
	public function setCantidadQuesoProduccion($cantidadQuesoProduccion)
	{
	  $this->cantidadQuesoProduccion = (String) $cantidadQuesoProduccion;
	    return $this;
	}

	/**
	* Get cantidadQuesoProduccion
	*
	* @return null|String
	*/
	public function getCantidadQuesoProduccion()
	{
		return $this->cantidadQuesoProduccion;
	}

	/**
	* Set idProductoSuero
	*
	*Identificador del producto de tipo "industrias lácteas", subtipo "suero"
	*
	* @parámetro Integer $idProductoSuero
	* @return IdProductoSuero
	*/
	public function setIdProductoSuero($idProductoSuero)
	{
	  $this->idProductoSuero = (Integer) $idProductoSuero;
	    return $this;
	}

	/**
	* Get idProductoSuero
	*
	* @return null|Integer
	*/
	public function getIdProductoSuero()
	{
		return $this->idProductoSuero;
	}

	/**
	* Set nombreProductoSuero
	*
	*Nombre del produto de tipo "productos lácteos", subtipo "suero"
	*
	* @parámetro String $nombreProductoSuero
	* @return NombreProductoSuero
	*/
	public function setNombreProductoSuero($nombreProductoSuero)
	{
	  $this->nombreProductoSuero = (String) $nombreProductoSuero;
	    return $this;
	}

	/**
	* Get nombreProductoSuero
	*
	* @return null|String
	*/
	public function getNombreProductoSuero()
	{
		return $this->nombreProductoSuero;
	}

	/**
	* Set cantidadSueroProduccion
	*
	*Campo que almacena la cantidad de suero producido por el operador
	*
	* @parámetro String $cantidadSueroProduccion
	* @return CantidadSueroProduccion
	*/
	public function setCantidadSueroProduccion($cantidadSueroProduccion)
	{
	  $this->cantidadSueroProduccion = (String) $cantidadSueroProduccion;
	    return $this;
	}

	/**
	* Get cantidadSueroProduccion
	*
	* @return null|String
	*/
	public function getCantidadSueroProduccion()
	{
		return $this->cantidadSueroProduccion;
	}

	/**
	* Set fechaProduccionSuero
	*
	*Fecha de producción del suero registrado
	*
	* @parámetro Date $fechaProduccionSuero
	* @return FechaProduccionSuero
	*/
	public function setFechaProduccionSuero($fechaProduccionSuero)
	{
	  $this->fechaProduccionSuero = (String) $fechaProduccionSuero;
	    return $this;
	}

	/**
	* Get fechaProduccionSuero
	*
	* @return null|Date
	*/
	public function getFechaProduccionSuero()
	{
		return $this->fechaProduccionSuero;
	}
	
	
	/**
	 * Set estado
	 *
	 *Estado del registro
	 *
	 * @parámetro Date $fechaProduccionSuero
	 * @return estado
	 */
	public function setEstado($estado)
	{
		$this->estado = (String) $estado;
		return $this;
	}
	
	/**
	 * Get Estado
	 *
	 * @return null|string
	 */
	public function getEstado()
	{
		return $this->estado;
	}
	
	/**
	 * Set identificador
	 *
	 *identificador del registro
	 *
	 * @parámetro Date $identificador
	 * @return identificador
	 */
	public function setIdentificador($identificador)
	{
		$this->identificador = (String) $identificador;
		return $this;
	}
	
	/**
	 * Get identificador
	 *
	 * @return null|string
	 */
	public function getIdentificador()
	{
		return $this->identificador;
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
	* @return ProduccionModelo
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
