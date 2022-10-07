<?php
 /**
 * Modelo AsistenciaEmpleadoModelo
 *
 * Este archivo se complementa con el archivo   AsistenciaEmpleadoLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-06-09
 * @uses    AsistenciaEmpleadoModelo
 * @package JornadaLaboral
 * @subpackage Modelos
 */
  namespace Agrodb\JornadaLaboral\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class AsistenciaEmpleadoModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador único de la tabla
		*/
		protected $idAsistenciaEmpleado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del funcionario que registra el ingreso
		*/
		protected $identificador;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de ingreso de registro
		*/
		protected $fechaRegistro;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Tipo de registro insertado. 1. Ingreso de la jornada laboral, 2. Inicio del receso, 3. Fin del receso, 4. Fin de la jornada laboral
		*/
		protected $tipoRegistro;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Estado del registro.
		*/
		protected $estado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Ubicacion desde donde se realiza el registro.
		*/
		protected $ipRegistro;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_uath";

	/**
	* Nombre de la tabla: asistencia_empleado
	* 
	 */
	Private $tabla="asistencia_empleado";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_asistencia_empleado";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_uath"."asistencia_empleado_id_asistencia_empleado_seq';

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
		throw new \Exception('Clase Modelo: AsistenciaEmpleadoModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: AsistenciaEmpleadoModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_uath
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idAsistenciaEmpleado
	*
	*Identificador único de la tabla
	*
	* @parámetro Integer $idAsistenciaEmpleado
	* @return IdAsistenciaEmpleado
	*/
	public function setIdAsistenciaEmpleado($idAsistenciaEmpleado)
	{
	  $this->idAsistenciaEmpleado = (Integer) $idAsistenciaEmpleado;
	    return $this;
	}

	/**
	* Get idAsistenciaEmpleado
	*
	* @return null|Integer
	*/
	public function getIdAsistenciaEmpleado()
	{
		return $this->idAsistenciaEmpleado;
	}

	/**
	* Set identificador
	*
	*Identificador del funcionario que registra el ingreso
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
	* Set fechaRegistro
	*
	*Fecha de ingreso de registro
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
	* Set tipoRegistro
	*
	*Tipo de registro insertado. 1. Ingreso de la jornada laboral, 2. Inicio del receso, 3. Fin del receso, 4. Fin de la jornada laboral
	*
	* @parámetro String $tipoRegistro
	* @return TipoRegistro
	*/
	public function setTipoRegistro($tipoRegistro)
	{
	  $this->tipoRegistro = (String) $tipoRegistro;
	    return $this;
	}

	/**
	* Get tipoRegistro
	*
	* @return null|String
	*/
	public function getTipoRegistro()
	{
		return $this->tipoRegistro;
	}

	/**
	* Set estado
	*
	*Estado del registro.
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
	* Set ipRegistro
	*
	*Ubicacion desde donde se realiza el registro.
	*
	* @parámetro String $ipRegistro
	* @return IpRegistro
	*/
	public function setIpRegistro($ipRegistro)
	{
	  $this->ipRegistro = (String) $ipRegistro;
	    return $this;
	}

	/**
	* Get ipRegistro
	*
	* @return null|String
	*/
	public function getIpRegistro()
	{
		return $this->ipRegistro;
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
	* @return AsistenciaEmpleadoModelo
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
