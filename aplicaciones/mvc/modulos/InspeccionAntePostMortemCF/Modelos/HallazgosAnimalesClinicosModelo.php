<?php
 /**
 * Modelo HallazgosAnimalesClinicosModelo
 *
 * Este archivo se complementa con el archivo   HallazgosAnimalesClinicosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    HallazgosAnimalesClinicosModelo
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class HallazgosAnimalesClinicosModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* llave primaria de la tabla
		*/
		protected $idHallazgosAnimalesClinicos;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Número de animales nerviosos
		*/
		protected $numAnimalesNerviosos;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Números de animales  con signos digestivos
		*/
		protected $numAnimalesDigestivo;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Número de animales con signos respiratorios
		*/
		protected $numAnimalesRespiratorio;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Números de animales con signos vesicular
		*/
		protected $numAnimalesVesicular;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Números de animales con signos reproductivos
		*/
		protected $numAnimalesReproductivo;

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
	* Nombre de la tabla: hallazgos_animales_clinicos
	* 
	 */
	Private $tabla="hallazgos_animales_clinicos";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_hallazgos_animales_clinicos";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_centros_faenamiento"."hallazgos_animales_clinicos_id_hallazgos_animales_clinicos_seq'; 



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
		throw new \Exception('Clase Modelo: HallazgosAnimalesClinicosModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: HallazgosAnimalesClinicosModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idHallazgosAnimalesClinicos
	*
	*llave primaria de la tabla
	*
	* @parámetro Integer $idHallazgosAnimalesClinicos
	* @return IdHallazgosAnimalesClinicos
	*/
	public function setIdHallazgosAnimalesClinicos($idHallazgosAnimalesClinicos)
	{
	  $this->idHallazgosAnimalesClinicos = (Integer) $idHallazgosAnimalesClinicos;
	    return $this;
	}

	/**
	* Get idHallazgosAnimalesClinicos
	*
	* @return null|Integer
	*/
	public function getIdHallazgosAnimalesClinicos()
	{
		return $this->idHallazgosAnimalesClinicos;
	}

	/**
	* Set numAnimalesNerviosos
	*
	*Número de animales nerviosos
	*
	* @parámetro Integer $numAnimalesNerviosos
	* @return NumAnimalesNerviosos
	*/
	public function setNumAnimalesNerviosos($numAnimalesNerviosos)
	{
	  $this->numAnimalesNerviosos = (Integer) $numAnimalesNerviosos;
	    return $this;
	}

	/**
	* Get numAnimalesNerviosos
	*
	* @return null|Integer
	*/
	public function getNumAnimalesNerviosos()
	{
		return $this->numAnimalesNerviosos;
	}

	/**
	* Set numAnimalesDigestivo
	*
	*Números de animales  con signos digestivos
	*
	* @parámetro Integer $numAnimalesDigestivo
	* @return NumAnimalesDigestivo
	*/
	public function setNumAnimalesDigestivo($numAnimalesDigestivo)
	{
	  $this->numAnimalesDigestivo = (Integer) $numAnimalesDigestivo;
	    return $this;
	}

	/**
	* Get numAnimalesDigestivo
	*
	* @return null|Integer
	*/
	public function getNumAnimalesDigestivo()
	{
		return $this->numAnimalesDigestivo;
	}

	/**
	* Set numAnimalesRespiratorio
	*
	*Número de animales con signos respiratorios
	*
	* @parámetro Integer $numAnimalesRespiratorio
	* @return NumAnimalesRespiratorio
	*/
	public function setNumAnimalesRespiratorio($numAnimalesRespiratorio)
	{
	  $this->numAnimalesRespiratorio = (Integer) $numAnimalesRespiratorio;
	    return $this;
	}

	/**
	* Get numAnimalesRespiratorio
	*
	* @return null|Integer
	*/
	public function getNumAnimalesRespiratorio()
	{
		return $this->numAnimalesRespiratorio;
	}

	/**
	* Set numAnimalesVesicular
	*
	*Números de animales con signos vesicular
	*
	* @parámetro Integer $numAnimalesVesicular
	* @return NumAnimalesVesicular
	*/
	public function setNumAnimalesVesicular($numAnimalesVesicular)
	{
	  $this->numAnimalesVesicular = (Integer) $numAnimalesVesicular;
	    return $this;
	}

	/**
	* Get numAnimalesVesicular
	*
	* @return null|Integer
	*/
	public function getNumAnimalesVesicular()
	{
		return $this->numAnimalesVesicular;
	}

	/**
	* Set numAnimalesReproductivo
	*
	*Números de animales con signos reproductivos
	*
	* @parámetro Integer $numAnimalesReproductivo
	* @return NumAnimalesReproductivo
	*/
	public function setNumAnimalesReproductivo($numAnimalesReproductivo)
	{
	  $this->numAnimalesReproductivo = (Integer) $numAnimalesReproductivo;
	    return $this;
	}

	/**
	* Get numAnimalesReproductivo
	*
	* @return null|Integer
	*/
	public function getNumAnimalesReproductivo()
	{
		return $this->numAnimalesReproductivo;
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
	* @return HallazgosAnimalesClinicosModelo
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
