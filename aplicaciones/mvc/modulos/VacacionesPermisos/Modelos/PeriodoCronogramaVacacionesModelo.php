<?php
 /**
 * Modelo PeriodoCronogramaVacacionesModelo
 *
 * Este archivo se complementa con el archivo   PeriodoCronogramaVacacionesLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-11-21
 * @uses    PeriodoCronogramaVacacionesModelo
 * @package VacacionesPermisos
 * @subpackage Modelos
 */
  namespace Agrodb\VacacionesPermisos\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class PeriodoCronogramaVacacionesModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla
		*/
		protected $idPeriodoCronogramaVacacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla g_vacaciones.cronograma_vacaciones
		*/
		protected $idCronogramaVacacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Numero del periodo del cronograma de vacaciones Primer Periodo/Segundo Periodo
		*/
		protected $numeroPeriodo;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de inicio de periodo de vacaciones
		*/
		protected $fechaInicio;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de retorno de periodo de vacaciones
		*/
		protected $fechaFin;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Numero total de dias del periodo de vacaciones
		*/
		protected $totalDias;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Estado del registro Activo/Inactivo
		*/
		protected $estadoRegistro;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Estado de la reprogracion SI/NO caso contratio si es NULL es planificacion
		*/
		protected $estadoReprogramacion;

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
	* Nombre de la tabla: periodo_cronograma_vacaciones
	* 
	 */
	Private $tabla="periodo_cronograma_vacaciones";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_periodo_cronograma_vacacion";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_vacaciones"."periodo_cronograma_vacaciones_id_periodo_cronograma_vacacio_seq'; 



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
		throw new \Exception('Clase Modelo: PeriodoCronogramaVacacionesModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: PeriodoCronogramaVacacionesModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idPeriodoCronogramaVacacion
	*
	*Identificador unico de la tabla
	*
	* @parámetro Integer $idPeriodoCronogramaVacacion
	* @return IdPeriodoCronogramaVacacion
	*/
	public function setIdPeriodoCronogramaVacacion($idPeriodoCronogramaVacacion)
	{
	  $this->idPeriodoCronogramaVacacion = (Integer) $idPeriodoCronogramaVacacion;
	    return $this;
	}

	/**
	* Get idPeriodoCronogramaVacacion
	*
	* @return null|Integer
	*/
	public function getIdPeriodoCronogramaVacacion()
	{
		return $this->idPeriodoCronogramaVacacion;
	}

	/**
	* Set idCronogramaVacacion
	*
	*Identificador unico de la tabla g_vacaciones.cronograma_vacaciones
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
	* Set numeroPeriodo
	*
	*Numero del periodo del cronograma de vacaciones Primer Periodo/Segundo Periodo
	*
	* @parámetro Integer $numeroPeriodo
	* @return NumeroPeriodo
	*/
	public function setNumeroPeriodo($numeroPeriodo)
	{
	  $this->numeroPeriodo = (Integer) $numeroPeriodo;
	    return $this;
	}

	/**
	* Get numeroPeriodo
	*
	* @return null|Integer
	*/
	public function getNumeroPeriodo()
	{
		return $this->numeroPeriodo;
	}

	/**
	* Set fechaInicio
	*
	*Fecha de inicio de periodo de vacaciones
	*
	* @parámetro Date $fechaInicio
	* @return FechaInicio
	*/
	public function setFechaInicio($fechaInicio)
	{
	  $this->fechaInicio = (String) $fechaInicio;
	    return $this;
	}

	/**
	* Get fechaInicio
	*
	* @return null|Date
	*/
	public function getFechaInicio()
	{
		return $this->fechaInicio;
	}

	/**
	* Set fechaFin
	*
	*Fecha de retorno de periodo de vacaciones
	*
	* @parámetro Date $fechaFin
	* @return FechaFin
	*/
	public function setFechaFin($fechaFin)
	{
	  $this->fechaFin = (String) $fechaFin;
	    return $this;
	}

	/**
	* Get fechaFin
	*
	* @return null|Date
	*/
	public function getFechaFin()
	{
		return $this->fechaFin;
	}

	/**
	* Set totalDias
	*
	*Numero total de dias del periodo de vacaciones
	*
	* @parámetro Integer $totalDias
	* @return TotalDias
	*/
	public function setTotalDias($totalDias)
	{
	  $this->totalDias = (Integer) $totalDias;
	    return $this;
	}

	/**
	* Get totalDias
	*
	* @return null|Integer
	*/
	public function getTotalDias()
	{
		return $this->totalDias;
	}

	/**
	* Set estadoRegistro
	*
	*Estado del registro Activo/Inactivo
	*
	* @parámetro String $estadoRegistro
	* @return EstadoRegistro
	*/
	public function setEstadoRegistro($estadoRegistro)
	{
	  $this->estadoRegistro = (String) $estadoRegistro;
	    return $this;
	}

	/**
	* Get estadoRegistro
	*
	* @return null|String
	*/
	public function getEstadoRegistro()
	{
		return $this->estadoRegistro;
	}

	/**
	* Set estadoReprogramacion
	*
	*Estado de la reprogracion SI/NO caso contratio si es NULL es planificacion
	*
	* @parámetro String $estadoReprogramacion
	* @return EstadoReprogramacion
	*/
	public function setEstadoReprogramacion($estadoReprogramacion)
	{
	  $this->estadoReprogramacion = (String) $estadoReprogramacion;
	    return $this;
	}

	/**
	* Get estadoReprogramacion
	*
	* @return null|String
	*/
	public function getEstadoReprogramacion()
	{
		return $this->estadoReprogramacion;
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
	* @return PeriodoCronogramaVacacionesModelo
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
		return parent::buscarLista($where,$order,$count,$offset);
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
