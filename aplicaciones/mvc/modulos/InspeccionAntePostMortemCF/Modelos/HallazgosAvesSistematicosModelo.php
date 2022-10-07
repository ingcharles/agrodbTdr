<?php
 /**
 * Modelo HallazgosAvesSistematicosModelo
 *
 * Este archivo se complementa con el archivo   HallazgosAvesSistematicosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    HallazgosAvesSistematicosModelo
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class HallazgosAvesSistematicosModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* llave primaria de la tabla
		*/
		protected $idHallazgosAvesSistematicos;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Aves con problemas respiratorios
		*/
		protected $problRespirat;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Porcentaje de aves con problemas respiratorios
		*/
		protected $porcentProblRespirat;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Aves con problemas nerviosos
		*/
		protected $problNerviosos;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Porcentaje de aves con problemas nerviosos
		*/
		protected $porcentProbleNerviosos;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Aves con problemas digestivos
		*/
		protected $problDigestivos;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Porcentaje de aves con problemas digestivos
		*/
		protected $porcentProblDigestivos;

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
	* Nombre de la tabla: hallazgos_aves_sistematicos
	* 
	 */
	Private $tabla="hallazgos_aves_sistematicos";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_hallazgos_aves_sistematicos";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_centros_faenamiento"."hallazgos_aves_sistematicos_id_hallazgos_aves_sistematicos_seq'; 



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
		throw new \Exception('Clase Modelo: HallazgosAvesSistematicosModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: HallazgosAvesSistematicosModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idHallazgosAvesSistematicos
	*
	*llave primaria de la tabla
	*
	* @parámetro Integer $idHallazgosAvesSistematicos
	* @return IdHallazgosAvesSistematicos
	*/
	public function setIdHallazgosAvesSistematicos($idHallazgosAvesSistematicos)
	{
	  $this->idHallazgosAvesSistematicos = (Integer) $idHallazgosAvesSistematicos;
	    return $this;
	}

	/**
	* Get idHallazgosAvesSistematicos
	*
	* @return null|Integer
	*/
	public function getIdHallazgosAvesSistematicos()
	{
		return $this->idHallazgosAvesSistematicos;
	}

	/**
	* Set problRespirat
	*
	*Aves con problemas respiratorios
	*
	* @parámetro String $problRespirat
	* @return ProblRespirat
	*/
	public function setProblRespirat($problRespirat)
	{
	  $this->problRespirat = (String) $problRespirat;
	    return $this;
	}

	/**
	* Get problRespirat
	*
	* @return null|String
	*/
	public function getProblRespirat()
	{
		return $this->problRespirat;
	}

	/**
	* Set porcentProblRespirat
	*
	*Porcentaje de aves con problemas respiratorios
	*
	* @parámetro String $porcentProblRespirat
	* @return PorcentProblRespirat
	*/
	public function setPorcentProblRespirat($porcentProblRespirat)
	{
	  $this->porcentProblRespirat = (String) $porcentProblRespirat;
	    return $this;
	}

	/**
	* Get porcentProblRespirat
	*
	* @return null|String
	*/
	public function getPorcentProblRespirat()
	{
		return $this->porcentProblRespirat;
	}

	/**
	* Set problNerviosos
	*
	*Aves con problemas nerviosos
	*
	* @parámetro String $problNerviosos
	* @return ProblNerviosos
	*/
	public function setProblNerviosos($problNerviosos)
	{
	  $this->problNerviosos = (String) $problNerviosos;
	    return $this;
	}

	/**
	* Get problNerviosos
	*
	* @return null|String
	*/
	public function getProblNerviosos()
	{
		return $this->problNerviosos;
	}

	/**
	* Set porcentProbleNerviosos
	*
	*Porcentaje de aves con problemas nerviosos
	*
	* @parámetro String $porcentProbleNerviosos
	* @return PorcentProbleNerviosos
	*/
	public function setPorcentProbleNerviosos($porcentProbleNerviosos)
	{
	  $this->porcentProbleNerviosos = (String) $porcentProbleNerviosos;
	    return $this;
	}

	/**
	* Get porcentProbleNerviosos
	*
	* @return null|String
	*/
	public function getPorcentProbleNerviosos()
	{
		return $this->porcentProbleNerviosos;
	}

	/**
	* Set problDigestivos
	*
	*Aves con problemas digestivos
	*
	* @parámetro String $problDigestivos
	* @return ProblDigestivos
	*/
	public function setProblDigestivos($problDigestivos)
	{
	  $this->problDigestivos = (String) $problDigestivos;
	    return $this;
	}

	/**
	* Get problDigestivos
	*
	* @return null|String
	*/
	public function getProblDigestivos()
	{
		return $this->problDigestivos;
	}

	/**
	* Set porcentProblDigestivos
	*
	*Porcentaje de aves con problemas digestivos
	*
	* @parámetro String $porcentProblDigestivos
	* @return PorcentProblDigestivos
	*/
	public function setPorcentProblDigestivos($porcentProblDigestivos)
	{
	  $this->porcentProblDigestivos = (String) $porcentProblDigestivos;
	    return $this;
	}

	/**
	* Get porcentProblDigestivos
	*
	* @return null|String
	*/
	public function getPorcentProblDigestivos()
	{
		return $this->porcentProblDigestivos;
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
	* @return HallazgosAvesSistematicosModelo
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
