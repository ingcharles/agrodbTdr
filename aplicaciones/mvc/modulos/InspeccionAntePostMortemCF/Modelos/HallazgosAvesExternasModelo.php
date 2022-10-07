<?php
 /**
 * Modelo HallazgosAvesExternasModelo
 *
 * Este archivo se complementa con el archivo   HallazgosAvesExternasLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    HallazgosAvesExternasModelo
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class HallazgosAvesExternasModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
		protected $idHallazgosAvesExternas;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Aves con cabeza hinchada
		*/
		protected $cabezaHinchada;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Porcentaje de aves con cabeza hinchada
		*/
		protected $porcentCabezaHinchada;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Aves con plumas erizadas
		*/
		protected $plumasErizadas;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Procentaje de aves con plumas erizadas
		*/
		protected $porcentPlumasErizadas;

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
	* Nombre de la tabla: hallazgos_aves_externas
	* 
	 */
	Private $tabla="hallazgos_aves_externas";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_hallazgos_aves_externas";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_centros_faenamiento"."hallazgos_aves_externas_id_hallazgos_aves_externas_seq'; 



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
		throw new \Exception('Clase Modelo: HallazgosAvesExternasModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: HallazgosAvesExternasModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idHallazgosAvesExternas
	*
	*Llave primaria de la tabla
	*
	* @parámetro Integer $idHallazgosAvesExternas
	* @return IdHallazgosAvesExternas
	*/
	public function setIdHallazgosAvesExternas($idHallazgosAvesExternas)
	{
	  $this->idHallazgosAvesExternas = (Integer) $idHallazgosAvesExternas;
	    return $this;
	}

	/**
	* Get idHallazgosAvesExternas
	*
	* @return null|Integer
	*/
	public function getIdHallazgosAvesExternas()
	{
		return $this->idHallazgosAvesExternas;
	}

	/**
	* Set cabezaHinchada
	*
	*Aves con cabeza hinchada
	*
	* @parámetro String $cabezaHinchada
	* @return CabezaHinchada
	*/
	public function setCabezaHinchada($cabezaHinchada)
	{
	  $this->cabezaHinchada = (String) $cabezaHinchada;
	    return $this;
	}

	/**
	* Get cabezaHinchada
	*
	* @return null|String
	*/
	public function getCabezaHinchada()
	{
		return $this->cabezaHinchada;
	}

	/**
	* Set porcentCabezaHinchada
	*
	*Porcentaje de aves con cabeza hinchada
	*
	* @parámetro String $porcentCabezaHinchada
	* @return PorcentCabezaHinchada
	*/
	public function setPorcentCabezaHinchada($porcentCabezaHinchada)
	{
	  $this->porcentCabezaHinchada = (String) $porcentCabezaHinchada;
	    return $this;
	}

	/**
	* Get porcentCabezaHinchada
	*
	* @return null|String
	*/
	public function getPorcentCabezaHinchada()
	{
		return $this->porcentCabezaHinchada;
	}

	/**
	* Set plumasErizadas
	*
	*Aves con plumas erizadas
	*
	* @parámetro String $plumasErizadas
	* @return PlumasErizadas
	*/
	public function setPlumasErizadas($plumasErizadas)
	{
	  $this->plumasErizadas = (String) $plumasErizadas;
	    return $this;
	}

	/**
	* Get plumasErizadas
	*
	* @return null|String
	*/
	public function getPlumasErizadas()
	{
		return $this->plumasErizadas;
	}

	/**
	* Set porcentPlumasErizadas
	*
	*Procentaje de aves con plumas erizadas
	*
	* @parámetro String $porcentPlumasErizadas
	* @return PorcentPlumasErizadas
	*/
	public function setPorcentPlumasErizadas($porcentPlumasErizadas)
	{
	  $this->porcentPlumasErizadas = (String) $porcentPlumasErizadas;
	    return $this;
	}

	/**
	* Get porcentPlumasErizadas
	*
	* @return null|String
	*/
	public function getPorcentPlumasErizadas()
	{
		return $this->porcentPlumasErizadas;
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
	* @return HallazgosAvesExternasModelo
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
