<?php
 /**
 * Modelo HallazgosAvesMuertasModelo
 *
 * Este archivo se complementa con el archivo   HallazgosAvesMuertasLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    HallazgosAvesMuertasModelo
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class HallazgosAvesMuertasModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
		protected $idHallazgosAvesMuertas;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Número de aves muertas
		*/
		protected $avesMuertas;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Porcentaje de aves muertas
		*/
		protected $porcentAvesMuertas;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Causa por lo arribaron muertas
		*/
		protected $causaProbable;

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
	* Nombre de la tabla: hallazgos_aves_muertas
	* 
	 */
	Private $tabla="hallazgos_aves_muertas";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_hallazgos_aves_muertas";



	/**
	*Secuencia 
*/
		 private $secuencial = 'g_centros_faenamiento"."hallazgos_aves_muertas_id_hallazgos_aves_muertas_seq'; 



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
		throw new \Exception('Clase Modelo: HallazgosAvesMuertasModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: HallazgosAvesMuertasModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idHallazgosAvesMuertas
	*
	*Llave primaria de la tabla
	*
	* @parámetro Integer $idHallazgosAvesMuertas
	* @return IdHallazgosAvesMuertas
	*/
	public function setIdHallazgosAvesMuertas($idHallazgosAvesMuertas)
	{
	  $this->idHallazgosAvesMuertas = (Integer) $idHallazgosAvesMuertas;
	    return $this;
	}

	/**
	* Get idHallazgosAvesMuertas
	*
	* @return null|Integer
	*/
	public function getIdHallazgosAvesMuertas()
	{
		return $this->idHallazgosAvesMuertas;
	}

	/**
	* Set avesMuertas
	*
	*Número de aves muertas
	*
	* @parámetro String $avesMuertas
	* @return AvesMuertas
	*/
	public function setAvesMuertas($avesMuertas)
	{
	  $this->avesMuertas = (String) $avesMuertas;
	    return $this;
	}

	/**
	* Get avesMuertas
	*
	* @return null|String
	*/
	public function getAvesMuertas()
	{
		return $this->avesMuertas;
	}

	/**
	* Set porcentAvesMuertas
	*
	*Porcentaje de aves muertas
	*
	* @parámetro String $porcentAvesMuertas
	* @return PorcentAvesMuertas
	*/
	public function setPorcentAvesMuertas($porcentAvesMuertas)
	{
	  $this->porcentAvesMuertas = (String) $porcentAvesMuertas;
	    return $this;
	}

	/**
	* Get porcentAvesMuertas
	*
	* @return null|String
	*/
	public function getPorcentAvesMuertas()
	{
		return $this->porcentAvesMuertas;
	}

	/**
	* Set causaProbable
	*
	*Causa por lo arribaron muertas
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
	* @return HallazgosAvesMuertasModelo
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
