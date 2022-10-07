<?php
 /**
 * Modelo ProcesoAdministrativoModelo
 *
 * Este archivo se complementa con el archivo   ProcesoAdministrativoLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-03-17
 * @uses    ProcesoAdministrativoModelo
 * @package ProcesosAdministrativosJuridico
 * @subpackage Modelos
 */
  namespace Agrodb\ProcesosAdministrativosJuridico\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class ProcesoAdministrativoModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
		protected $idProcesoAdministrativo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Provincia donde se genero el tramite
		*/
		protected $provincia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Área técnica
		*/
		protected $areaTecnica;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre establecimiento
		*/
		protected $nombreEstablecimiento;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de creación del registro
		*/
		protected $fechaCreacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $estado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de quien creo el registro
		*/
		protected $identificadorRegistro;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * Número del proceso
		 */
		protected $numeroProceso;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * Nombre Accionado
		 */
		protected $nombreAccionado;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 *detalle sancion
		 */
		protected $detalleSancion;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * observacion
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
	Private $esquema ="g_procesos_administrativos_juridico";

	/**
	* Nombre de la tabla: proceso_administrativo
	* 
	 */
	Private $tabla="proceso_administrativo";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_proceso_administrativo";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_procesos_administrativos_juridico"."proceso_administrativo_id_proceso_administrativo_seq'; 



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
		throw new \Exception('Clase Modelo: ProcesoAdministrativoModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: ProcesoAdministrativoModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_procesos_administrativos_juridico
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idProcesoAdministrativo
	*
	*Llave primaria de la tabla
	*
	* @parámetro Integer $idProcesoAdministrativo
	* @return IdProcesoAdministrativo
	*/
	public function setIdProcesoAdministrativo($idProcesoAdministrativo)
	{
	  $this->idProcesoAdministrativo = (Integer) $idProcesoAdministrativo;
	    return $this;
	}

	/**
	* Get idProcesoAdministrativo
	*
	* @return null|Integer
	*/
	public function getIdProcesoAdministrativo()
	{
		return $this->idProcesoAdministrativo;
	}

	/**
	* Set provincia
	*
	*Provincia donde se genero el tramite
	*
	* @parámetro String $provincia
	* @return Provincia
	*/
	public function setProvincia($provincia)
	{
	  $this->provincia = (String) $provincia;
	    return $this;
	}

	/**
	* Get provincia
	*
	* @return null|String
	*/
	public function getProvincia()
	{
		return $this->provincia;
	}

	/**
	* Set areaTecnica
	*
	*Área técnica
	*
	* @parámetro String $areaTecnica
	* @return AreaTecnica
	*/
	public function setAreaTecnica($areaTecnica)
	{
	  $this->areaTecnica = (String) $areaTecnica;
	    return $this;
	}

	/**
	* Get areaTecnica
	*
	* @return null|String
	*/
	public function getAreaTecnica()
	{
		return $this->areaTecnica;
	}

	/**
	* Set nombreEstablecimiento
	*
	*Nombre establecimiento
	*
	* @parámetro String $nombreEstablecimiento
	* @return NombreEstablecimiento
	*/
	public function setNombreEstablecimiento($nombreEstablecimiento)
	{
	  $this->nombreEstablecimiento = (String) $nombreEstablecimiento;
	    return $this;
	}

	/**
	* Get nombreEstablecimiento
	*
	* @return null|String
	*/
	public function getNombreEstablecimiento()
	{
		return $this->nombreEstablecimiento;
	}

	/**
	* Set fechaCreacion
	*
	*Fecha de creación del registro
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
	* Set identificadorRegistro
	*
	*Identificador de quien creo el registro
	*
	* @parámetro String $identificadorRegistro
	* @return IdentificadorRegistro
	*/
	public function setIdentificadorRegistro($identificadorRegistro)
	{
	  $this->identificadorRegistro = (String) $identificadorRegistro;
	    return $this;
	}

	/**
	* Get identificadorRegistro
	*
	* @return null|String
	*/
	public function getIdentificadorRegistro()
	{
		return $this->identificadorRegistro;
	}
	/**
	 * Set numero_proceso
	 *
	 *Identificador de quien creo el registro
	 *
	 * @parámetro String $numeroProceso
	 * @return NumeroProceso
	 */
	public function setNumeroProceso($numeroProceso)
	{
	    $this->numeroProceso = (String) $numeroProceso;
	    return $this;
	}
	
	/**
	 * Get numeroProceso
	 *
	 * @return null|String
	 */
	public function getNumeroProceso()
	{
	    return $this->numeroProceso;
	}
	/**
	 * Set nombre accionado
	 *
	 * @parámetro String $numeroProceso
	 * @return Nombre accionado
	 */
	public function setNombreAccionado($nombreAccionado)
	{
	    $this->nombreAccionado = (String) $nombreAccionado;
	    return $this;
	}
	
	/**
	 * Get nombreAccionado
	 *
	 * @return null|String
	 */
	public function getNombreAccionado()
	{
	    return $this->nombreAccionado;
	}
	/**
	 * Set detalleSancion
	 *
	 * @parámetro String $numeroProceso
	 * @return detalleSancion
	 */
	public function setDetalleSancion($detalleSancion)
	{
	    $this->detalleSancion = (String) $detalleSancion;
	    return $this;
	}
	
	/**
	 * Get detalleSancion
	 *
	 * @return null|String
	 */
	public function getDetalleSancion()
	{
	    return $this->detalleSancion;
	}
	/**
	 * Set observacion
	 *
	 * @parámetro String $observacion
	 * @return observacion
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
	* @return ProcesoAdministrativoModelo
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
