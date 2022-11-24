<?php
 /**
 * Modelo RevisionCronogramaVacacionesModelo
 *
 * Este archivo se complementa con el archivo   RevisionCronogramaVacacionesLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-11-21
 * @uses    RevisionCronogramaVacacionesModelo
 * @package VacacionesPermisos
 * @subpackage Modelos
 */
  namespace Agrodb\VacacionesPermisos\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class RevisionCronogramaVacacionesModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla
		*/
		protected $idRevisionCronogramaVacacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* identificador unico de la tabla g_vacaciones.cronograma_vacacion
		*/
		protected $idCronogramaVacacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* usuario que va aprobar el cronograma vacacion
		*/
		protected $identificadorRevisor;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* area del usuario que va aprobar el cronograma vacacion
		*/
		protected $idAreaRevisor;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* estado de la solicitud
		*/
		protected $estadoSolicitud;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Observacion  cuando se rechaza o se aprueba el cronograma de vacaciones
		*/
		protected $observacion;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* fecha de creacion del registro
		*/
		protected $fechaCreacion;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_vacaciones";

	/**
	* Nombre de la tabla: revision_cronograma_vacaciones
	* 
	 */
	Private $tabla="revision_cronograma_vacaciones";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_revision_cronograma_vacacion";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_vacaciones"."revision_cronograma_vacacione_id_revision_cronograma_vacaci_seq'; 



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
		throw new \Exception('Clase Modelo: RevisionCronogramaVacacionesModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: RevisionCronogramaVacacionesModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_vacaciones
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idRevisionCronogramaVacacion
	*
	*Identificador unico de la tabla
	*
	* @parámetro Integer $idRevisionCronogramaVacacion
	* @return IdRevisionCronogramaVacacion
	*/
	public function setIdRevisionCronogramaVacacion($idRevisionCronogramaVacacion)
	{
	  $this->idRevisionCronogramaVacacion = (Integer) $idRevisionCronogramaVacacion;
	    return $this;
	}

	/**
	* Get idRevisionCronogramaVacacion
	*
	* @return null|Integer
	*/
	public function getIdRevisionCronogramaVacacion()
	{
		return $this->idRevisionCronogramaVacacion;
	}

	/**
	* Set idCronogramaVacacion
	*
	*identificador unico de la tabla g_vacaciones.cronograma_vacacion
	*
	* @parámetro Integer $idCronogramaVacacion
	* @return IdCronogramaVacacion
	*/
	public function setIdCronogramaVacacion($idCronogramaVacacion)
	{
	  $this->idCronogramaVacacion = (Integer) $idCronogramaVacacion;
	    return $this;
	}

	/**
	* Get idCronogramaVacacion
	*
	* @return null|Integer
	*/
	public function getIdCronogramaVacacion()
	{
		return $this->idCronogramaVacacion;
	}

	/**
	* Set identificadorRevisor
	*
	*usuario que va aprobar el cronograma vacacion
	*
	* @parámetro String $identificadorRevisor
	* @return IdentificadorRevisor
	*/
	public function setIdentificadorRevisor($identificadorRevisor)
	{
	  $this->identificadorRevisor = (String) $identificadorRevisor;
	    return $this;
	}

	/**
	* Get identificadorRevisor
	*
	* @return null|String
	*/
	public function getIdentificadorRevisor()
	{
		return $this->identificadorRevisor;
	}

	/**
	* Set idAreaRevisor
	*
	*area del usuario que va aprobar el cronograma vacacion
	*
	* @parámetro String $idAreaRevisor
	* @return IdAreaRevisor
	*/
	public function setIdAreaRevisor($idAreaRevisor)
	{
	  $this->idAreaRevisor = (String) $idAreaRevisor;
	    return $this;
	}

	/**
	* Get idAreaRevisor
	*
	* @return null|String
	*/
	public function getIdAreaRevisor()
	{
		return $this->idAreaRevisor;
	}

	/**
	* Set estadoSolicitud
	*
	*estado de la solicitud
	*
	* @parámetro String $estadoSolicitud
	* @return EstadoSolicitud
	*/
	public function setEstadoSolicitud($estadoSolicitud)
	{
	  $this->estadoSolicitud = (String) $estadoSolicitud;
	    return $this;
	}

	/**
	* Get estadoSolicitud
	*
	* @return null|String
	*/
	public function getEstadoSolicitud()
	{
		return $this->estadoSolicitud;
	}

	/**
	* Set observacion
	*
	*Observacion  cuando se rechaza o se aprueba el cronograma de vacaciones
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
	* Set fechaCreacion
	*
	*fecha de creacion del registro
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
	* @return RevisionCronogramaVacacionesModelo
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
