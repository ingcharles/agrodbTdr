<?php
 /**
 * Modelo ConfiguracionCronogramaVacacionesModelo
 *
 * Este archivo se complementa con el archivo   ConfiguracionCronogramaVacacionesLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-11-21
 * @uses    ConfiguracionCronogramaVacacionesModelo
 * @package VacacionesPermisos
 * @subpackage Modelos
 */
  namespace Agrodb\VacacionesPermisos\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class ConfiguracionCronogramaVacacionesModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla
		*/
		protected $idConfiguracionCronogramaVacacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Anio del cronograma
		*/
		protected $anioConfiguracionCronogramaVacacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Descripcion del cronograma
		*/
		protected $descripcionConfiguracionVacacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del funcionario que configura el cronograma
		*/
		protected $identificadorConfiguracionCronogramaVacacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Estado de la configuracion del cronograma
		*/
		protected $estadoConfiguracionCronogramaVacacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Identificacion del responsable de la revision consolidada - director ejecutivo
		*/
		protected $identificadorDirectorEjecutivo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Almacena la ruta del reporte consolidado anual de planificacion de vacaciones en excel
		*/
		protected $rutaConsolidadoExcel;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Almacena la ruta del reporte consolidado anual de planificacion de vacaciones en pdf
		*/
		protected $rutaConsolidadoPdf;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Almacena la fecha de modificacion del registro
		*/
		protected $fechaModificacion;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Almacena la fecha de creacion del registro
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
	* Nombre de la tabla: configuracion_cronograma_vacaciones
	* 
	 */
	Private $tabla="configuracion_cronograma_vacaciones";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_configuracion_cronograma_vacacion";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_vacaciones"."configuracion_cronograma_vaca_id_configuracion_cronograma_v_seq'; 



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
		throw new \Exception('Clase Modelo: ConfiguracionCronogramaVacacionesModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: ConfiguracionCronogramaVacacionesModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idConfiguracionCronogramaVacacion
	*
	*Identificador unico de la tabla
	*
	* @parámetro Integer $idConfiguracionCronogramaVacacion
	* @return IdConfiguracionCronogramaVacacion
	*/
	public function setIdConfiguracionCronogramaVacacion($idConfiguracionCronogramaVacacion)
	{
	  $this->idConfiguracionCronogramaVacacion = (Integer) $idConfiguracionCronogramaVacacion;
	    return $this;
	}

	/**
	* Get idConfiguracionCronogramaVacacion
	*
	* @return null|Integer
	*/
	public function getIdConfiguracionCronogramaVacacion()
	{
		return $this->idConfiguracionCronogramaVacacion;
	}

	/**
	* Set anioConfiguracionCronogramaVacacion
	*
	*Anio del cronograma
	*
	* @parámetro Integer $anioConfiguracionCronogramaVacacion
	* @return AnioConfiguracionCronogramaVacacion
	*/
	public function setAnioConfiguracionCronogramaVacacion($anioConfiguracionCronogramaVacacion)
	{
	  $this->anioConfiguracionCronogramaVacacion = (Integer) $anioConfiguracionCronogramaVacacion;
	    return $this;
	}

	/**
	* Get anioConfiguracionCronogramaVacacion
	*
	* @return null|Integer
	*/
	public function getAnioConfiguracionCronogramaVacacion()
	{
		return $this->anioConfiguracionCronogramaVacacion;
	}

	/**
	* Set descripcionConfiguracionVacacion
	*
	*Descripcion del cronograma
	*
	* @parámetro String $descripcionConfiguracionVacacion
	* @return DescripcionConfiguracionVacacion
	*/
	public function setDescripcionConfiguracionVacacion($descripcionConfiguracionVacacion)
	{
	  $this->descripcionConfiguracionVacacion = (String) $descripcionConfiguracionVacacion;
	    return $this;
	}

	/**
	* Get descripcionConfiguracionVacacion
	*
	* @return null|String
	*/
	public function getDescripcionConfiguracionVacacion()
	{
		return $this->descripcionConfiguracionVacacion;
	}

	/**
	* Set identificadorConfiguracionCronogramaVacacion
	*
	*Identificador del funcionario que configura el cronograma
	*
	* @parámetro String $identificadorConfiguracionCronogramaVacacion
	* @return IdentificadorConfiguracionCronogramaVacacion
	*/
	public function setIdentificadorConfiguracionCronogramaVacacion($identificadorConfiguracionCronogramaVacacion)
	{
	  $this->identificadorConfiguracionCronogramaVacacion = (String) $identificadorConfiguracionCronogramaVacacion;
	    return $this;
	}

	/**
	* Get identificadorConfiguracionCronogramaVacacion
	*
	* @return null|String
	*/
	public function getIdentificadorConfiguracionCronogramaVacacion()
	{
		return $this->identificadorConfiguracionCronogramaVacacion;
	}

	/**
	* Set estadoConfiguracionCronogramaVacacion
	*
	*Estado de la configuracion del cronograma
	*
	* @parámetro String $estadoConfiguracionCronogramaVacacion
	* @return EstadoConfiguracionCronogramaVacacion
	*/
	public function setEstadoConfiguracionCronogramaVacacion($estadoConfiguracionCronogramaVacacion)
	{
	  $this->estadoConfiguracionCronogramaVacacion = (String) $estadoConfiguracionCronogramaVacacion;
	    return $this;
	}

	/**
	* Get estadoConfiguracionCronogramaVacacion
	*
	* @return null|String
	*/
	public function getEstadoConfiguracionCronogramaVacacion()
	{
		return $this->estadoConfiguracionCronogramaVacacion;
	}

	/**
	* Set identificadorDirectorEjecutivo
	*
	*Identificacion del responsable de la revision consolidada - director ejecutivo
	*
	* @parámetro String $identificadorDirectorEjecutivo
	* @return IdentificadorDirectorEjecutivo
	*/
	public function setIdentificadorDirectorEjecutivo($identificadorDirectorEjecutivo)
	{
	  $this->identificadorDirectorEjecutivo = (String) $identificadorDirectorEjecutivo;
	    return $this;
	}

	/**
	* Get identificadorDirectorEjecutivo
	*
	* @return null|String
	*/
	public function getIdentificadorDirectorEjecutivo()
	{
		return $this->identificadorDirectorEjecutivo;
	}

	/**
	* Set rutaConsolidadoExcel
	*
	*Almacena la ruta del reporte consolidado anual de planificacion de vacaciones en excel
	*
	* @parámetro String $rutaConsolidadoExcel
	* @return RutaConsolidadoExcel
	*/
	public function setRutaConsolidadoExcel($rutaConsolidadoExcel)
	{
	  $this->rutaConsolidadoExcel = (String) $rutaConsolidadoExcel;
	    return $this;
	}

	/**
	* Get rutaConsolidadoExcel
	*
	* @return null|String
	*/
	public function getRutaConsolidadoExcel()
	{
		return $this->rutaConsolidadoExcel;
	}

	/**
	* Set rutaConsolidadoPdf
	*
	*Almacena la ruta del reporte consolidado anual de planificacion de vacaciones en pdf
	*
	* @parámetro String $rutaConsolidadoPdf
	* @return RutaConsolidadoPdf
	*/
	public function setRutaConsolidadoPdf($rutaConsolidadoPdf)
	{
	  $this->rutaConsolidadoPdf = (String) $rutaConsolidadoPdf;
	    return $this;
	}

	/**
	* Get rutaConsolidadoPdf
	*
	* @return null|String
	*/
	public function getRutaConsolidadoPdf()
	{
		return $this->rutaConsolidadoPdf;
	}

	/**
	* Set fechaModificacion
	*
	*Almacena la fecha de modificacion del registro
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
	* Set fechaCreacion
	*
	*Almacena la fecha de creacion del registro
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
	* @return ConfiguracionCronogramaVacacionesModelo
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
