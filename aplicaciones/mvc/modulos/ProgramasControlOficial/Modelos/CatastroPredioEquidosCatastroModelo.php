<?php
 /**
 * Modelo CatastroPredioEquidosCatastroModelo
 *
 * Este archivo se complementa con el archivo   CatastroPredioEquidosCatastroLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-02-16
 * @uses    CatastroPredioEquidosCatastroModelo
 * @package ProgramasControlOficial
 * @subpackage Modelos
 */
  namespace Agrodb\ProgramasControlOficial\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class CatastroPredioEquidosCatastroModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idCatastroPredioEquidosCatastro;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idCatastroPredioEquidos;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $identificador;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $fechaCreacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idCatastro;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $catastro;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_programas_control_oficial";

	/**
	* Nombre de la tabla: catastro_predio_equidos_catastro
	* 
	 */
	Private $tabla="catastro_predio_equidos_catastro";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_catastro_predio_equidos_catastro";



	/**
	*Secuencia
*/
		 private $secuencial = '"CatastroPredioEquidosCatastro_"id_catastro_predio_equidos_catastro_seq'; 



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
		throw new \Exception('Clase Modelo: CatastroPredioEquidosCatastroModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: CatastroPredioEquidosCatastroModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_programas_control_oficial
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idCatastroPredioEquidosCatastro
	*
	*
	*
	* @parámetro Integer $idCatastroPredioEquidosCatastro
	* @return IdCatastroPredioEquidosCatastro
	*/
	public function setIdCatastroPredioEquidosCatastro($idCatastroPredioEquidosCatastro)
	{
	  $this->idCatastroPredioEquidosCatastro = (Integer) $idCatastroPredioEquidosCatastro;
	    return $this;
	}

	/**
	* Get idCatastroPredioEquidosCatastro
	*
	* @return null|Integer
	*/
	public function getIdCatastroPredioEquidosCatastro()
	{
		return $this->idCatastroPredioEquidosCatastro;
	}

	/**
	* Set idCatastroPredioEquidos
	*
	*
	*
	* @parámetro Integer $idCatastroPredioEquidos
	* @return IdCatastroPredioEquidos
	*/
	public function setIdCatastroPredioEquidos($idCatastroPredioEquidos)
	{
	  $this->idCatastroPredioEquidos = (Integer) $idCatastroPredioEquidos;
	    return $this;
	}

	/**
	* Get idCatastroPredioEquidos
	*
	* @return null|Integer
	*/
	public function getIdCatastroPredioEquidos()
	{
		return $this->idCatastroPredioEquidos;
	}

	/**
	* Set identificador
	*
	*
	*
	* @parámetro String $identificador
	* @return Identificador
	*/
	public function setIdentificador($identificador)
	{
	  $this->identificador = (String) $identificador;
	    return $this;
	}

	/**
	* Get identificador
	*
	* @return null|String
	*/
	public function getIdentificador()
	{
		return $this->identificador;
	}

	/**
	* Set fechaCreacion
	*
	*
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
	* Set idCatastro
	*
	*
	*
	* @parámetro Integer $idCatastro
	* @return IdCatastro
	*/
	public function setIdCatastro($idCatastro)
	{
	  $this->idCatastro = (Integer) $idCatastro;
	    return $this;
	}

	/**
	* Get idCatastro
	*
	* @return null|Integer
	*/
	public function getIdCatastro()
	{
		return $this->idCatastro;
	}

	/**
	* Set catastro
	*
	*
	*
	* @parámetro String $catastro
	* @return Catastro
	*/
	public function setCatastro($catastro)
	{
	  $this->catastro = (String) $catastro;
	    return $this;
	}

	/**
	* Get catastro
	*
	* @return null|String
	*/
	public function getCatastro()
	{
		return $this->catastro;
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
	* @return CatastroPredioEquidosCatastroModelo
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
