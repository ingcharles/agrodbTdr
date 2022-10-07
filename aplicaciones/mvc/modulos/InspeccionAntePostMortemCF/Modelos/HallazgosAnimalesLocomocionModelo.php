<?php
 /**
 * Modelo HallazgosAnimalesLocomocionModelo
 *
 * Este archivo se complementa con el archivo   HallazgosAnimalesLocomocionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    HallazgosAnimalesLocomocionModelo
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class HallazgosAnimalesLocomocionModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* llave primaria de la tabla
		*/
		protected $idHallazgosAnimalesLocomocion;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Número de animales con cogera
		*/
		protected $numAnimalesCogera;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Número de animales no ambulatorios
		*/
		protected $numAnimalesAmbulatorios;

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
	* Nombre de la tabla: hallazgos_animales_locomocion
	* 
	 */
	Private $tabla="hallazgos_animales_locomocion";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_hallazgos_animales_locomocion";



	/**
	*Secuencia 

*/
		 private $secuencial = 'g_centros_faenamiento"."hallazgos_animales_locomocion_id_hallazgos_animales_locomoc_seq'; 



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
		throw new \Exception('Clase Modelo: HallazgosAnimalesLocomocionModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: HallazgosAnimalesLocomocionModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idHallazgosAnimalesLocomocion
	*
	*llave primaria de la tabla
	*
	* @parámetro Integer $idHallazgosAnimalesLocomocion
	* @return IdHallazgosAnimalesLocomocion
	*/
	public function setIdHallazgosAnimalesLocomocion($idHallazgosAnimalesLocomocion)
	{
	  $this->idHallazgosAnimalesLocomocion = (Integer) $idHallazgosAnimalesLocomocion;
	    return $this;
	}

	/**
	* Get idHallazgosAnimalesLocomocion
	*
	* @return null|Integer
	*/
	public function getIdHallazgosAnimalesLocomocion()
	{
		return $this->idHallazgosAnimalesLocomocion;
	}

	/**
	* Set numAnimalesCogera
	*
	*Número de animales con cogera
	*
	* @parámetro Integer $numAnimalesCogera
	* @return NumAnimalesCogera
	*/
	public function setNumAnimalesCogera($numAnimalesCogera)
	{
	  $this->numAnimalesCogera = (Integer) $numAnimalesCogera;
	    return $this;
	}

	/**
	* Get numAnimalesCogera
	*
	* @return null|Integer
	*/
	public function getNumAnimalesCogera()
	{
		return $this->numAnimalesCogera;
	}

	/**
	* Set numAnimalesAmbulatorios
	*
	*Número de animales no ambulatorios
	*
	* @parámetro Integer $numAnimalesAmbulatorios
	* @return NumAnimalesAmbulatorios
	*/
	public function setNumAnimalesAmbulatorios($numAnimalesAmbulatorios)
	{
	  $this->numAnimalesAmbulatorios = (Integer) $numAnimalesAmbulatorios;
	    return $this;
	}

	/**
	* Get numAnimalesAmbulatorios
	*
	* @return null|Integer
	*/
	public function getNumAnimalesAmbulatorios()
	{
		return $this->numAnimalesAmbulatorios;
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
	* @return HallazgosAnimalesLocomocionModelo
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
