<?php
 /**
 * Modelo SerieAretesModelo
 *
 * Este archivo se complementa con el archivo   SerieAretesLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-09-08
 * @uses    SerieAretesModelo
 * @package Catalogos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class SerieAretesModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla
		*/
		protected $idSerieAretes;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el estado del registro
		*/
		protected $estado;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el id de la especie
		*/
		protected $idEspecie;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el número de arete
		*/
		protected $numeroArete;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la fecha del registro
		*/
		protected $fechaRegistro;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la observacion
		*/
		protected $observacion;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_catalogos";

	/**
	* Nombre de la tabla: serie_aretes
	* 
	 */
	Private $tabla="serie_aretes";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_serie_aretes";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_catalogos"."SerieAretes_id_serie_aretes_seq'; 



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
		throw new \Exception('Clase Modelo: SerieAretesModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: SerieAretesModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_catalogos
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idSerieAretes
	*
	*Identificador unico de la tabla
	*
	* @parámetro Integer $idSerieAretes
	* @return IdSerieAretes
	*/
	public function setIdSerieAretes($idSerieAretes)
	{
	  $this->idSerieAretes = (Integer) $idSerieAretes;
	    return $this;
	}

	/**
	* Get idSerieAretes
	*
	* @return null|Integer
	*/
	public function getIdSerieAretes()
	{
		return $this->idSerieAretes;
	}

	/**
	* Set estado
	*
	*Campo que almacena el estado del registro
	*
	* @parámetro String $estado
	* @return Estado
	*/
	public function setEstado($estado)
	{
	  $this->estado = (String) $estado;
	    return $this;
	}

	/**
	* Get estado
	*
	* @return null|String
	*/
	public function getEstado()
	{
		return $this->estado;
	}

	/**
	* Set idEspecie
	*
	*Campo que almacena el id de la especie
	*
	* @parámetro Integer $idEspecie
	* @return IdEspecie
	*/
	public function setIdEspecie($idEspecie)
	{
	  $this->idEspecie = (Integer) $idEspecie;
	    return $this;
	}

	/**
	* Get idEspecie
	*
	* @return null|Integer
	*/
	public function getIdEspecie()
	{
		return $this->idEspecie;
	}

	/**
	* Set numeroArete
	*
	*Campo que almacena el número de arete
	*
	* @parámetro String $numeroArete
	* @return NumeroArete
	*/
	public function setNumeroArete($numeroArete)
	{
	  $this->numeroArete = (String) $numeroArete;
	    return $this;
	}

	/**
	* Get numeroArete
	*
	* @return null|String
	*/
	public function getNumeroArete()
	{
		return $this->numeroArete;
	}

	/**
	* Set fechaRegistro
	*
	*Campo que almacena la fecha del registro
	*
	* @parámetro Date $fechaRegistro
	* @return FechaRegistro
	*/
	public function setFechaRegistro($fechaRegistro)
	{
	  $this->fechaRegistro = (String) $fechaRegistro;
	    return $this;
	}

	/**
	* Get fechaRegistro
	*
	* @return null|Date
	*/
	public function getFechaRegistro()
	{
		return $this->fechaRegistro;
	}

	/**
	* Set observacion
	*
	*Campo que almacena la observacion
	*
	* @parámetro String $observacion
	* @return Observacion
	*/
	public function setObservacion($observacion)
	{
	  $this->observacion = (String) $observacion;
	    return $this;
	}

	/**
	* Get observacion
	*
	* @return null|String
	*/
	public function getObservacion()
	{
		return $this->observacion;
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
	* @return SerieAretesModelo
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
