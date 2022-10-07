<?php
 /**
 * Modelo HallazgosAvesCaractModelo
 *
 * Este archivo se complementa con el archivo   HallazgosAvesCaractLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    HallazgosAvesCaractModelo
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class HallazgosAvesCaractModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
		protected $idHallazgosAvesCaract;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Aves llegaron decaídas
		*/
		protected $decaidas;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Porcentaje de aves decaídas
		*/
		protected $porcentDecaidas;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Número de aves con traumas
		*/
		protected $numTraumas;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Porcentaje de aves con traumas
		*/
		protected $porcentTraumas;

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
	* Nombre de la tabla: hallazgos_aves_caract
	* 
	 */
	Private $tabla="hallazgos_aves_caract";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_hallazgos_aves_caract";



	/**
	*Secuencia 
*/
		 private $secuencial = 'g_centros_faenamiento"."hallazgos_aves_caract_id_hallazgos_aves_caract_seq'; 



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
		throw new \Exception('Clase Modelo: HallazgosAvesCaractModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: HallazgosAvesCaractModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idHallazgosAvesCaract
	*
	*Llave primaria de la tabla
	*
	* @parámetro Integer $idHallazgosAvesCaract
	* @return IdHallazgosAvesCaract
	*/
	public function setIdHallazgosAvesCaract($idHallazgosAvesCaract)
	{
	  $this->idHallazgosAvesCaract = (Integer) $idHallazgosAvesCaract;
	    return $this;
	}

	/**
	* Get idHallazgosAvesCaract
	*
	* @return null|Integer
	*/
	public function getIdHallazgosAvesCaract()
	{
		return $this->idHallazgosAvesCaract;
	}

	/**
	* Set decaidas
	*
	*Aves llegaron decaídas
	*
	* @parámetro String $decaidas
	* @return Decaidas
	*/
	public function setDecaidas($decaidas)
	{
	  $this->decaidas = (String) $decaidas;
	    return $this;
	}

	/**
	* Get decaidas
	*
	* @return null|String
	*/
	public function getDecaidas()
	{
		return $this->decaidas;
	}

	/**
	* Set porcentDecaidas
	*
	*Porcentaje de aves decaídas
	*
	* @parámetro String $porcentDecaidas
	* @return PorcentDecaidas
	*/
	public function setPorcentDecaidas($porcentDecaidas)
	{
	  $this->porcentDecaidas = (String) $porcentDecaidas;
	    return $this;
	}

	/**
	* Get porcentDecaidas
	*
	* @return null|String
	*/
	public function getPorcentDecaidas()
	{
		return $this->porcentDecaidas;
	}

	/**
	* Set numTraumas
	*
	*Número de aves con traumas
	*
	* @parámetro String $numTraumas
	* @return NumTraumas
	*/
	public function setNumTraumas($numTraumas)
	{
	  $this->numTraumas = (String) $numTraumas;
	    return $this;
	}

	/**
	* Get numTraumas
	*
	* @return null|String
	*/
	public function getNumTraumas()
	{
		return $this->numTraumas;
	}

	/**
	* Set porcentTraumas
	*
	*Porcentaje de aves con traumas
	*
	* @parámetro String $porcentTraumas
	* @return PorcentTraumas
	*/
	public function setPorcentTraumas($porcentTraumas)
	{
	  $this->porcentTraumas = (String) $porcentTraumas;
	    return $this;
	}

	/**
	* Get porcentTraumas
	*
	* @return null|String
	*/
	public function getPorcentTraumas()
	{
		return $this->porcentTraumas;
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
	* @return HallazgosAvesCaractModelo
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
