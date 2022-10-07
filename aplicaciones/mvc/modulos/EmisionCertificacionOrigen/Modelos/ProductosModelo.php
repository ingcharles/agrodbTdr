<?php
 /**
 * Modelo ProductosModelo
 *
 * Este archivo se complementa con el archivo   ProductosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    ProductosModelo
 * @package EmisionCertificacionOrigen
 * @subpackage Modelos
 */
  namespace Agrodb\EmisionCertificacionOrigen\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class ProductosModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
		protected $idProductos;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave foránea de la tabla registro_produccion
		*/
		protected $idRegistroProduccion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Número de canales obtenidos
		*/
		protected $numCanalesObtenidos;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Número de canales obtenidos sin restricción de uso
		*/
		protected $numCanalesObtenidosUso;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Número de canales para uso industrial
		*/
		protected $numCanalesUsoIndustri;
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
		* Especie seleccionada
		*/
		protected $tipoEspecie;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* número de animales recibidos
		*/
		protected $numAnimalesRecibidos;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de recepción
		*/
		protected $fechaRecepcion;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * Especie seleccionada
		 */
		protected $codigoCanal;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * Estado del registro
		 */
		protected $estadoTipoMovi;
		/**
		 * @var Integer
		 * Campo requerido
		 * Campo visible en el formulario
		 * Número de tipo movilización en emisión
		 */
		protected $numTipoMov;
		
		protected $fechaFaenamiento;
		

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_emision_certificacion_origen";

	/**
	* Nombre de la tabla: productos
	* 
	 */
	Private $tabla="productos";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_productos";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_emision_certificacion_origen"."productos_id_productos_seq'; 



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
		throw new \Exception('Clase Modelo: ProductosModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: ProductosModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_emision_certificacion_origen
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idProductos
	*
	*Llave primaria de la tabla
	*
	* @parámetro Integer $idProductos
	* @return IdProductos
	*/
	public function setIdProductos($idProductos)
	{
	  $this->idProductos = (Integer) $idProductos;
	    return $this;
	}

	/**
	* Get idProductos
	*
	* @return null|Integer
	*/
	public function getIdProductos()
	{
		return $this->idProductos;
	}

	/**
	* Set idRegistroProduccion
	*
	*Llave foránea de la tabla registro_produccion
	*
	* @parámetro Integer $idRegistroProduccion
	* @return IdRegistroProduccion
	*/
	public function setIdRegistroProduccion($idRegistroProduccion)
	{
	  $this->idRegistroProduccion = (Integer) $idRegistroProduccion;
	    return $this;
	}

	/**
	* Get idRegistroProduccion
	*
	* @return null|Integer
	*/
	public function getIdRegistroProduccion()
	{
		return $this->idRegistroProduccion;
	}

	/**
	* Set numCanalesObtenidos
	*
	*Número de canales obtenidos
	*
	* @parámetro Integer $numCanalesObtenidos
	* @return NumCanalesObtenidos
	*/
	public function setNumCanalesObtenidos($numCanalesObtenidos)
	{
	  $this->numCanalesObtenidos = (Integer) $numCanalesObtenidos;
	    return $this;
	}

	/**
	* Get numCanalesObtenidos
	*
	* @return null|Integer
	*/
	public function getNumCanalesObtenidos()
	{
		return $this->numCanalesObtenidos;
	}

	/**
	* Set numCanalesObtenidosUso
	*
	*Número de canales obtenidos sin restricción de uso
	*
	* @parámetro Integer $numCanalesObtenidosUso
	* @return NumCanalesObtenidosUso
	*/
	public function setNumCanalesObtenidosUso($numCanalesObtenidosUso)
	{
	  $this->numCanalesObtenidosUso = (Integer) $numCanalesObtenidosUso;
	    return $this;
	}

	/**
	* Get numCanalesObtenidosUso
	*
	* @return null|Integer
	*/
	public function getNumCanalesObtenidosUso()
	{
		return $this->numCanalesObtenidosUso;
	}

	/**
	* Set numCanalesUsoIndustri
	*
	*Número de canales para uso industrial
	*
	* @parámetro Integer $numCanalesUsoIndustri
	* @return NumCanalesUsoIndustri
	*/
	public function setNumCanalesUsoIndustri($numCanalesUsoIndustri)
	{
	  $this->numCanalesUsoIndustri = (Integer) $numCanalesUsoIndustri;
	    return $this;
	}

	/**
	* Get numCanalesUsoIndustri
	*
	* @return null|Integer
	*/
	public function getNumCanalesUsoIndustri()
	{
		return $this->numCanalesUsoIndustri;
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
	* Set tipoEspecie
	*
	*Especie seleccionada
	*
	* @parámetro String $tipoEspecie
	* @return TipoEspecie
	*/
	public function setTipoEspecie($tipoEspecie)
	{
	  $this->tipoEspecie = (String) $tipoEspecie;
	    return $this;
	}

	/**
	* Get tipoEspecie
	*
	* @return null|String
	*/
	public function getTipoEspecie()
	{
		return $this->tipoEspecie;
	}

	/**
	* Set numAnimalesRecibidos
	*
	*número de animales recibidos
	*
	* @parámetro Integer $numAnimalesRecibidos
	* @return NumAnimalesRecibidos
	*/
	public function setNumAnimalesRecibidos($numAnimalesRecibidos)
	{
	  $this->numAnimalesRecibidos = (Integer) $numAnimalesRecibidos;
	    return $this;
	}

	/**
	* Get numAnimalesRecibidos
	*
	* @return null|Integer
	*/
	public function getNumAnimalesRecibidos()
	{
		return $this->numAnimalesRecibidos;
	}

	/**
	* Set fechaRecepcion
	*
	*Fecha de recepción
	*
	* @parámetro Date $fechaRecepcion
	* @return FechaRecepcion
	*/
	public function setFechaRecepcion($fechaRecepcion)
	{
	  $this->fechaRecepcion = (String) $fechaRecepcion;
	    return $this;
	}

	/**
	* Get fechaRecepcion
	*
	* @return null|Date
	*/
	public function getFechaRecepcion()
	{
		return $this->fechaRecepcion;
	}
	/**
	 * Set codigoCanal
	 *
	 *Especie seleccionada
	 *
	 * @parámetro String $tipoEspecie
	 * @return codigoCanal
	 */
	public function setCodigoCanal($codigoCanal)
	{
	    $this->codigoCanal = (String) $codigoCanal;
	    return $this;
	}
	
	/**
	 * Get codigoCanal
	 *
	 * @return null|String
	 */
	public function getCodigoCanal()
	{
	    return $this->tipoEspecie;
	}
	/**
	 * Set estadoTipoMovi
	 *
	 *Estado del registro
	 *
	 * @parámetro String $estadoTipoMovi
	 * @return EstadoTipoMovi
	 */
	public function setEstadoTipoMovi($estadoTipoMovi)
	{
	    $this->estadoTipoMovi = (String) $estadoTipoMovi;
	    return $this;
	}
	
	/**
	 * Get estadoTipoMovi
	 *
	 * @return null|String
	 */
	public function getEstadoTipoMovi()
	{
	    return $this->estadoTipoMovi;
	}
	
	/**
	 * Set numTipoMov
	 *
	 *Número de tipo movilización en emisión
	 *
	 * @parámetro Integer $numTipoMov
	 * @return NumTipoMov
	 */
	public function setNumTipoMov($numTipoMov)
	{
	    $this->numTipoMov = (Integer) $numTipoMov;
	    return $this;
	}
	
	/**
	 * Get numTipoMov
	 *
	 * @return null|Integer
	 */
	public function getNumTipoMov()
	{
	    return $this->numTipoMov;
	}
	
	
	/**
	 * Set fechafaenamiento
	 *
	 *Fecha de faenamiento del registro
	 *
	 * @parámetro Date $fechaFaenamiento
	 * @return FechaFaenamiento
	 */
	public function setFechaFaenamiento($fechaFaenamiento)
	{
	    $this->fechaFaenamiento = (String) $fechaFaenamiento;
	    return $this;
	}
	
	/**
	 * Get fechaFaenamiento
	 *
	 * @return null|Date
	 */
	public function getFechaFaenamiento()
	{
	    return $this->fechaFaenamiento;
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
	* @return ProductosModelo
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
