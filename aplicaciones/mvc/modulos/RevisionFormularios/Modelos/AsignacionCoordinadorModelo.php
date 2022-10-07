<?php
 /**
 * Modelo AsignacionCoordinadorModelo
 *
 * Este archivo se complementa con el archivo   AsignacionCoordinadorLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-25
 * @uses    AsignacionCoordinadorModelo
 * @package RevisionFormularios
 * @subpackage Modelos
 */
  namespace Agrodb\RevisionFormularios\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class AsignacionCoordinadorModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idAsignacionCoordinador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $identificadorInspector;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $fechaAsignacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $identificadorAsignante;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $tipoSolicitud;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idSolicitud;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $tipoInspector;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
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
	Private $esquema ="g_revision_solicitudes";

	/**
	* Nombre de la tabla: asignacion_coordinador
	* 
	 */
	Private $tabla="asignacion_coordinador";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_asignacion_coordinador";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_revision_solicitudes"."asignacion_coordinador_id_asignacion_coordinador_seq'; 



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
		throw new \Exception('Clase Modelo: AsignacionCoordinadorModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: AsignacionCoordinadorModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_revision_solicitudes
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idAsignacionCoordinador
	*
	*
	*
	* @parámetro Integer $idAsignacionCoordinador
	* @return IdAsignacionCoordinador
	*/
	public function setIdAsignacionCoordinador($idAsignacionCoordinador)
	{
	  $this->idAsignacionCoordinador = (Integer) $idAsignacionCoordinador;
	    return $this;
	}

	/**
	* Get idAsignacionCoordinador
	*
	* @return null|Integer
	*/
	public function getIdAsignacionCoordinador()
	{
		return $this->idAsignacionCoordinador;
	}

	/**
	* Set identificadorInspector
	*
	*
	*
	* @parámetro String $identificadorInspector
	* @return IdentificadorInspector
	*/
	public function setIdentificadorInspector($identificadorInspector)
	{
	  $this->identificadorInspector = (String) $identificadorInspector;
	    return $this;
	}

	/**
	* Get identificadorInspector
	*
	* @return null|String
	*/
	public function getIdentificadorInspector()
	{
		return $this->identificadorInspector;
	}

	/**
	* Set fechaAsignacion
	*
	*
	*
	* @parámetro Date $fechaAsignacion
	* @return FechaAsignacion
	*/
	public function setFechaAsignacion($fechaAsignacion)
	{
	  $this->fechaAsignacion = (String) $fechaAsignacion;
	    return $this;
	}

	/**
	* Get fechaAsignacion
	*
	* @return null|Date
	*/
	public function getFechaAsignacion()
	{
		return $this->fechaAsignacion;
	}

	/**
	* Set identificadorAsignante
	*
	*
	*
	* @parámetro String $identificadorAsignante
	* @return IdentificadorAsignante
	*/
	public function setIdentificadorAsignante($identificadorAsignante)
	{
	  $this->identificadorAsignante = (String) $identificadorAsignante;
	    return $this;
	}

	/**
	* Get identificadorAsignante
	*
	* @return null|String
	*/
	public function getIdentificadorAsignante()
	{
		return $this->identificadorAsignante;
	}

	/**
	* Set tipoSolicitud
	*
	*
	*
	* @parámetro String $tipoSolicitud
	* @return TipoSolicitud
	*/
	public function setTipoSolicitud($tipoSolicitud)
	{
	  $this->tipoSolicitud = (String) $tipoSolicitud;
	    return $this;
	}

	/**
	* Get tipoSolicitud
	*
	* @return null|String
	*/
	public function getTipoSolicitud()
	{
		return $this->tipoSolicitud;
	}

	/**
	* Set idSolicitud
	*
	*
	*
	* @parámetro Integer $idSolicitud
	* @return IdSolicitud
	*/
	public function setIdSolicitud($idSolicitud)
	{
	  $this->idSolicitud = (Integer) $idSolicitud;
	    return $this;
	}

	/**
	* Get idSolicitud
	*
	* @return null|Integer
	*/
	public function getIdSolicitud()
	{
		return $this->idSolicitud;
	}

	/**
	* Set tipoInspector
	*
	*
	*
	* @parámetro String $tipoInspector
	* @return TipoInspector
	*/
	public function setTipoInspector($tipoInspector)
	{
	  $this->tipoInspector = (String) $tipoInspector;
	    return $this;
	}

	/**
	* Get tipoInspector
	*
	* @return null|String
	*/
	public function getTipoInspector()
	{
		return $this->tipoInspector;
	}

	/**
	* Set estado
	*
	*
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
	* @return AsignacionCoordinadorModelo
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
