<?php
 /**
 * Modelo ResultadoDecomisoParcialModelo
 *
 * Este archivo se complementa con el archivo   ResultadoDecomisoParcialLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    ResultadoDecomisoParcialModelo
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class ResultadoDecomisoParcialModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
		protected $idResultadoDecomisoParcial;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Razón de decomiso
		*/
		protected $razonDecomiso;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Números de canales decomisadas
		*/
		protected $numCanalesDecomisadas;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Peso de carne aprobada
		*/
		protected $pesoCarneAprobada;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Peso de carne decomisada
		*/
		protected $pesoCarneDecomisada;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Llave foránea de la tabla detalle_post_animales
		*/
		protected $idDetallePostAnimales;
		/**
		* @var Date
		* Campo opcional
		* Campo visible en el formulario
		* Fecha de creación del registro
		*/
		protected $fechaCreacion;

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
	* Nombre de la tabla: resultado_decomiso_parcial
	* 
	 */
	Private $tabla="resultado_decomiso_parcial";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_resultado_decomiso_parcial";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_centros_faenamiento"."resultado_decomiso_parcial_id_resultado_decomiso_parcial_seq'; 



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
		throw new \Exception('Clase Modelo: ResultadoDecomisoParcialModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: ResultadoDecomisoParcialModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idResultadoDecomisoParcial
	*
	*Llave primaria de la tabla
	*
	* @parámetro Integer $idResultadoDecomisoParcial
	* @return IdResultadoDecomisoParcial
	*/
	public function setIdResultadoDecomisoParcial($idResultadoDecomisoParcial)
	{
	  $this->idResultadoDecomisoParcial = (Integer) $idResultadoDecomisoParcial;
	    return $this;
	}

	/**
	* Get idResultadoDecomisoParcial
	*
	* @return null|Integer
	*/
	public function getIdResultadoDecomisoParcial()
	{
		return $this->idResultadoDecomisoParcial;
	}

	/**
	* Set razonDecomiso
	*
	*Razón de decomiso
	*
	* @parámetro String $razonDecomiso
	* @return RazonDecomiso
	*/
	public function setRazonDecomiso($razonDecomiso)
	{
	  $this->razonDecomiso = (String) $razonDecomiso;
	    return $this;
	}

	/**
	* Get razonDecomiso
	*
	* @return null|String
	*/
	public function getRazonDecomiso()
	{
		return $this->razonDecomiso;
	}

	/**
	* Set numCanalesDecomisadas
	*
	*Números de canales decomisadas
	*
	* @parámetro Integer $numCanalesDecomisadas
	* @return NumCanalesDecomisadas
	*/
	public function setNumCanalesDecomisadas($numCanalesDecomisadas)
	{
	  $this->numCanalesDecomisadas = (Integer) $numCanalesDecomisadas;
	    return $this;
	}

	/**
	* Get numCanalesDecomisadas
	*
	* @return null|Integer
	*/
	public function getNumCanalesDecomisadas()
	{
		return $this->numCanalesDecomisadas;
	}

	/**
	* Set pesoCarneAprobada
	*
	*Peso de carne aprobada
	*
	* @parámetro String $pesoCarneAprobada
	* @return PesoCarneAprobada
	*/
	public function setPesoCarneAprobada($pesoCarneAprobada)
	{
	  $this->pesoCarneAprobada = (String) $pesoCarneAprobada;
	    return $this;
	}

	/**
	* Get pesoCarneAprobada
	*
	* @return null|String
	*/
	public function getPesoCarneAprobada()
	{
		return $this->pesoCarneAprobada;
	}

	/**
	* Set pesoCarneDecomisada
	*
	*Peso de carne decomisada
	*
	* @parámetro String $pesoCarneDecomisada
	* @return PesoCarneDecomisada
	*/
	public function setPesoCarneDecomisada($pesoCarneDecomisada)
	{
	  $this->pesoCarneDecomisada = (String) $pesoCarneDecomisada;
	    return $this;
	}

	/**
	* Get pesoCarneDecomisada
	*
	* @return null|String
	*/
	public function getPesoCarneDecomisada()
	{
		return $this->pesoCarneDecomisada;
	}

	/**
	* Set idDetallePostAnimales
	*
	*Llave foránea de la tabla detalle_post_animales
	*
	* @parámetro Integer $idDetallePostAnimales
	* @return IdDetallePostAnimales
	*/
	public function setIdDetallePostAnimales($idDetallePostAnimales)
	{
	  $this->idDetallePostAnimales = (Integer) $idDetallePostAnimales;
	    return $this;
	}

	/**
	* Get idDetallePostAnimales
	*
	* @return null|Integer
	*/
	public function getIdDetallePostAnimales()
	{
		return $this->idDetallePostAnimales;
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
	* @return ResultadoDecomisoParcialModelo
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
