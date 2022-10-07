<?php
 /**
 * Modelo AlertasModelo
 *
 * Este archivo se complementa con el archivo   AlertasLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-06-06
 * @uses    AlertasModelo
 * @package AplicacionMovilExternos
 * @subpackage Modelos
 */
  namespace Agrodb\AplicacionMovilExternos\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class AlertasModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador único de la tabla
		*/
		protected $idAlerta;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Título de la alerta
		*/
		protected $titulo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Descripción de la alerta sanitaria
		*/
		protected $alerta;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de registro de la alerta
		*/
		protected $fechaAlerta;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Estado para verificar si la alerta se muestra (activo) o no se muestra
		*/
		protected $estado;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="a_movil_externos";

	/**
	* Nombre de la tabla: alertas
	* 
	 */
	Private $tabla="alertas";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_alerta";



	/**
	*Secuencia
*/
		 private $secuencial = 'a_movil_externos"."alertas_id_alerta_seq'; 



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
		throw new \Exception('Clase Modelo: AlertasModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: AlertasModelo. Propiedad especificada invalida: get'.$name);
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
	* Get a_movil_externos
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idAlerta
	*
	*Identificador único de la tabla
	*
	* @parámetro Integer $idAlerta
	* @return IdAlerta
	*/
	public function setIdAlerta($idAlerta)
	{
	  $this->idAlerta = (Integer) $idAlerta;
	    return $this;
	}

	/**
	* Get idAlerta
	*
	* @return null|Integer
	*/
	public function getIdAlerta()
	{
		return $this->idAlerta;
	}

	/**
	* Set titulo
	*
	*Título de la alerta
	*
	* @parámetro String $titulo
	* @return Titulo
	*/
	public function setTitulo($titulo)
	{
	  $this->titulo = (String) $titulo;
	    return $this;
	}

	/**
	* Get titulo
	*
	* @return null|String
	*/
	public function getTitulo()
	{
		return $this->titulo;
	}

	/**
	* Set alerta
	*
	*Descripción de la alerta sanitaria
	*
	* @parámetro String $alerta
	* @return Alerta
	*/
	public function setAlerta($alerta)
	{
	  $this->alerta = (String) $alerta;
	    return $this;
	}

	/**
	* Get alerta
	*
	* @return null|String
	*/
	public function getAlerta()
	{
		return $this->alerta;
	}

	/**
	* Set fechaAlerta
	*
	*Fecha de registro de la alerta
	*
	* @parámetro Date $fechaAlerta
	* @return FechaAlerta
	*/
	public function setFechaAlerta($fechaAlerta)
	{
	  $this->fechaAlerta = (String) $fechaAlerta;
	    return $this;
	}

	/**
	* Get fechaAlerta
	*
	* @return null|Date
	*/
	public function getFechaAlerta()
	{
		return $this->fechaAlerta;
	}

	/**
	* Set estado
	*
	*Estado para verificar si la alerta se muestra (activo) o no se muestra
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
	* @return AlertasModelo
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
