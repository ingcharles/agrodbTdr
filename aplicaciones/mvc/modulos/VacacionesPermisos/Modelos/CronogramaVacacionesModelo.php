<?php
 /**
 * Modelo CronogramaVacacionesModelo
 *
 * Este archivo se complementa con el archivo   CronogramaVacacionesLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-11-21
 * @uses    CronogramaVacacionesModelo
 * @package VacacionesPermisos
 * @subpackage Modelos
 */
  namespace Agrodb\VacacionesPermisos\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class CronogramaVacacionesModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla
		*/
		protected $idCronogramaVacacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Cedula de funcionario que planifica las vacaciones
		*/
		protected $identificador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre del funcionario que planifica el cronograma de vacaciones
		*/
		protected $nombreFuncionario;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de ingreso del primer contrato
		*/
		protected $fechaIngresoInstitucion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* identificador unico de la tabla g_catalogos.puestos
		*/
		protected $nombrePuesto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Area de funcionario que tiene altualmente el tramite
		*/
		protected $idAreaPadre;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Cedula de funcionario backup del que planifica las vacaciones
		*/
		protected $identificadorBackup;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Número total de dias planificados de vacaciones
		*/
		protected $totalDiasPlanificados;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Es el anio actual + 1 para planificar las vacaciones
		*/
		protected $anioCronogramaVacacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Es el numero de periodos seleccionados al planficiar las vacaciones
		*/
		protected $numeroPeriodos;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Cedula de funcionario que tiene altualmente el tramite
		*/
		protected $identificadorRevisor;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Observaciones de la aprobacion o rechazo de la planificacion de vacaciones
		*/
		protected $observacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Cedula de funcionario que registra la planificacion las vacaciones
		*/
		protected $usuarioCreacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Cedula de funcionario que actualiza la planificacion las vacaciones
		*/
		protected $usuarioModificacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Estado de la revision del registro de planificacion de vacaciones
		*/
		protected $estadoCronogramaVacacion;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de registro en el sistema
		*/
		protected $fechaCreacion;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de modificacion en el sistema
		*/
		protected $fechaModificacion;

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
	* Nombre de la tabla: cronograma_vacaciones
	* 
	 */
	Private $tabla="cronograma_vacaciones";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_cronograma_vacacion";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_vacaciones"."cronograma_vacaciones_id_cronograma_vacacion_seq'; 



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
		throw new \Exception('Clase Modelo: CronogramaVacacionesModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: CronogramaVacacionesModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idCronogramaVacacion
	*
	*Identificador unico de la tabla
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
	* Set identificador
	*
	*Cedula de funcionario que planifica las vacaciones
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
	* Set nombreFuncionario
	*
	*Nombre del funcionario que planifica el cronograma de vacaciones
	*
	* @parámetro String $nombreFuncionario
	* @return NombreFuncionario
	*/
	public function setNombreFuncionario($nombreFuncionario)
	{
	  $this->nombreFuncionario = (String) $nombreFuncionario;
	    return $this;
	}

	/**
	* Get nombreFuncionario
	*
	* @return null|String
	*/
	public function getNombreFuncionario()
	{
		return $this->nombreFuncionario;
	}

	/**
	* Set fechaIngresoInstitucion
	*
	*Fecha de ingreso del primer contrato
	*
	* @parámetro Date $fechaIngresoInstitucion
	* @return FechaIngresoInstitucion
	*/
	public function setFechaIngresoInstitucion($fechaIngresoInstitucion)
	{
	  $this->fechaIngresoInstitucion = (String) $fechaIngresoInstitucion;
	    return $this;
	}

	/**
	* Get fechaIngresoInstitucion
	*
	* @return null|Date
	*/
	public function getFechaIngresoInstitucion()
	{
		return $this->fechaIngresoInstitucion;
	}

	/**
	* Set nombrePuesto
	*
	*identificador unico de la tabla g_catalogos.puestos
	*
	* @parámetro Integer $idPuesto
	* @return IdPuesto
	*/
	public function setIdPuesto($nombrePuesto)
	{
	  $this->nombrePuesto = (String) $nombrePuesto;
	    return $this;
	}

	/**
	* Get nombrePuesto
	*
	* @return null|String
	*/
	public function getNombrePuesto()
	{
		return $this->nombrePuesto;
	}

	/**
	* Set idAreaPadre
	*
	*Area de funcionario que tiene altualmente el tramite
	*
	* @parámetro String $idAreaPadre
	* @return IdAreaPadre
	*/
	public function setIdAreaPadre($idAreaPadre)
	{
	  $this->idAreaPadre = (String) $idAreaPadre;
	    return $this;
	}

	/**
	* Get idAreaPadre
	*
	* @return null|String
	*/
	public function getIdAreaPadre()
	{
		return $this->idAreaPadre;
	}

	/**
	* Set identificadorBackup
	*
	*Cedula de funcionario backup del que planifica las vacaciones
	*
	* @parámetro String $identificadorBackup
	* @return IdentificadorBackup
	*/
	public function setIdentificadorBackup($identificadorBackup)
	{
	  $this->identificadorBackup = (String) $identificadorBackup;
	    return $this;
	}

	/**
	* Get identificadorBackup
	*
	* @return null|String
	*/
	public function getIdentificadorBackup()
	{
		return $this->identificadorBackup;
	}

	/**
	* Set totalDiasPlanificados
	*
	*Número total de dias planificados de vacaciones
	*
	* @parámetro Integer $totalDiasPlanificados
	* @return TotalDiasPlanificados
	*/
	public function setTotalDiasPlanificados($totalDiasPlanificados)
	{
	  $this->totalDiasPlanificados = (Integer) $totalDiasPlanificados;
	    return $this;
	}

	/**
	* Get totalDiasPlanificados
	*
	* @return null|Integer
	*/
	public function getTotalDiasPlanificados()
	{
		return $this->totalDiasPlanificados;
	}

	/**
	* Set anioCronogramaVacacion
	*
	*Es el anio actual + 1 para planificar las vacaciones
	*
	* @parámetro Integer $anioCronogramaVacacion
	* @return AnioCronogramaVacacion
	*/
	public function setAnioCronogramaVacacion($anioCronogramaVacacion)
	{
	  $this->anioCronogramaVacacion = (Integer) $anioCronogramaVacacion;
	    return $this;
	}

	/**
	* Get anioCronogramaVacacion
	*
	* @return null|Integer
	*/
	public function getAnioCronogramaVacacion()
	{
		return $this->anioCronogramaVacacion;
	}

	/**
	* Set numeroPeriodos
	*
	*Es el numero de periodos seleccionados al planficiar las vacaciones
	*
	* @parámetro Integer $numeroPeriodos
	* @return NumeroPeriodos
	*/
	public function setNumeroPeriodos($numeroPeriodos)
	{
	  $this->numeroPeriodos = (Integer) $numeroPeriodos;
	    return $this;
	}

	/**
	* Get numeroPeriodos
	*
	* @return null|Integer
	*/
	public function getNumeroPeriodos()
	{
		return $this->numeroPeriodos;
	}

	/**
	* Set identificadorRevisor
	*
	*Cedula de funcionario que tiene altualmente el tramite
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
	* Set observacion
	*
	*Observaciones de la aprobacion o rechazo de la planificacion de vacaciones
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
	* Set usuarioCreacion
	*
	*Cedula de funcionario que registra la planificacion las vacaciones
	*
	* @parámetro String $usuarioCreacion
	* @return UsuarioCreacion
	*/
	public function setUsuarioCreacion($usuarioCreacion)
	{
	  $this->usuarioCreacion = (String) $usuarioCreacion;
	    return $this;
	}

	/**
	* Get usuarioCreacion
	*
	* @return null|String
	*/
	public function getUsuarioCreacion()
	{
		return $this->usuarioCreacion;
	}

	/**
	* Set usuarioModificacion
	*
	*Cedula de funcionario que actualiza la planificacion las vacaciones
	*
	* @parámetro String $usuarioModificacion
	* @return UsuarioModificacion
	*/
	public function setUsuarioModificacion($usuarioModificacion)
	{
	  $this->usuarioModificacion = (String) $usuarioModificacion;
	    return $this;
	}

	/**
	* Get usuarioModificacion
	*
	* @return null|String
	*/
	public function getUsuarioModificacion()
	{
		return $this->usuarioModificacion;
	}

	/**
	* Set estadoCronogramaVacacion
	*
	*Estado de la revision del registro de planificacion de vacaciones
	*
	* @parámetro String $estadoCronogramaVacacion
	* @return EstadoCronogramaVacacion
	*/
	public function setEstadoCronogramaVacacion($estadoCronogramaVacacion)
	{
	  $this->estadoCronogramaVacacion = (String) $estadoCronogramaVacacion;
	    return $this;
	}

	/**
	* Get estadoCronogramaVacacion
	*
	* @return null|String
	*/
	public function getEstadoCronogramaVacacion()
	{
		return $this->estadoCronogramaVacacion;
	}

	/**
	* Set fechaCreacion
	*
	*Fecha de registro en el sistema
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
	* Set fechaModificacion
	*
	*Fecha de modificacion en el sistema
	*
	* @parámetro Date $fechaModificacion
	* @return FechaModificacion
	*/
	public function setFechaModificacion($fechaModificacion)
	{
	  $this->fechaModificacion = (String) $fechaModificacion;
	    return $this;
	}

	/**
	* Get fechaModificacion
	*
	* @return null|Date
	*/
	public function getFechaModificacion()
	{
		return $this->fechaModificacion;
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
	* @return CronogramaVacacionesModelo
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
