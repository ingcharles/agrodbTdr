<?php
 /**
 * Modelo HallazgosAnimalesMuertosModelo
 *
 * Este archivo se complementa con el archivo   HallazgosAnimalesMuertosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    HallazgosAnimalesMuertosModelo
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class HallazgosAnimalesMuertosModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* llave primaria de la tabla
		*/
		protected $idHallazgosAnimalesMuertos;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Número de animales muertos
		*/
		protected $numAnimalesMuertos;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Causa probable de muerte
		*/
		protected $causaProbable;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Animales decomizados
		*/
		protected $decomiso;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Aprovehcamiento permitido
		*/
		protected $aprovechamiento;

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
	* Nombre de la tabla: hallazgos_animales_muertos
	* 
	 */
	Private $tabla="hallazgos_animales_muertos";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_hallazgos_animales_muertos";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_centros_faenamiento"."hallazgos_animales_muertos_id_hallazgos_animales_muertos_seq'; 



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
		throw new \Exception('Clase Modelo: HallazgosAnimalesMuertosModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: HallazgosAnimalesMuertosModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idHallazgosAnimalesMuertos
	*
	*llave primaria de la tabla
	*
	* @parámetro Integer $idHallazgosAnimalesMuertos
	* @return IdHallazgosAnimalesMuertos
	*/
	public function setIdHallazgosAnimalesMuertos($idHallazgosAnimalesMuertos)
	{
	  $this->idHallazgosAnimalesMuertos = (Integer) $idHallazgosAnimalesMuertos;
	    return $this;
	}

	/**
	* Get idHallazgosAnimalesMuertos
	*
	* @return null|Integer
	*/
	public function getIdHallazgosAnimalesMuertos()
	{
		return $this->idHallazgosAnimalesMuertos;
	}

	/**
	* Set numAnimalesMuertos
	*
	*Número de animales muertos
	*
	* @parámetro Integer $numAnimalesMuertos
	* @return NumAnimalesMuertos
	*/
	public function setNumAnimalesMuertos($numAnimalesMuertos)
	{
	  $this->numAnimalesMuertos = (Integer) $numAnimalesMuertos;
	    return $this;
	}

	/**
	* Get numAnimalesMuertos
	*
	* @return null|Integer
	*/
	public function getNumAnimalesMuertos()
	{
		return $this->numAnimalesMuertos;
	}

	/**
	* Set causaProbable
	*
	*Causa probable de muerte
	*
	* @parámetro String $causaProbable
	* @return CausaProbable
	*/
	public function setCausaProbable($causaProbable)
	{
	  $this->causaProbable = (String) $causaProbable;
	    return $this;
	}

	/**
	* Get causaProbable
	*
	* @return null|String
	*/
	public function getCausaProbable()
	{
		return $this->causaProbable;
	}

	/**
	* Set decomiso
	*
	*Animales decomizados
	*
	* @parámetro String $decomiso
	* @return Decomiso
	*/
	public function setDecomiso($decomiso)
	{
	  $this->decomiso = (String) $decomiso;
	    return $this;
	}

	/**
	* Get decomiso
	*
	* @return null|String
	*/
	public function getDecomiso()
	{
		return $this->decomiso;
	}

	/**
	* Set aprovechamiento
	*
	*Aprovehcamiento permitido
	*
	* @parámetro String $aprovechamiento
	* @return Aprovechamiento
	*/
	public function setAprovechamiento($aprovechamiento)
	{
	  $this->aprovechamiento = (String) $aprovechamiento;
	    return $this;
	}

	/**
	* Get aprovechamiento
	*
	* @return null|String
	*/
	public function getAprovechamiento()
	{
		return $this->aprovechamiento;
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
	* @return HallazgosAnimalesMuertosModelo
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
