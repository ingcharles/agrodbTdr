<?php
 /**
 * Modelo CatastroPredioEquidosHistorialPatologiasModelo
 *
 * Este archivo se complementa con el archivo   CatastroPredioEquidosHistorialPatologiasLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-02-16
 * @uses    CatastroPredioEquidosHistorialPatologiasModelo
 * @package ProgramasControlOficial
 * @subpackage Modelos
 */
  namespace Agrodb\ProgramasControlOficial\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class CatastroPredioEquidosHistorialPatologiasModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idCatastroPredioEquidosHistorialPatologias;
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
		protected $idEnfermedad;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $enfermedad;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idVacuna;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $vacuna;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $laboratorio;

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
	* Nombre de la tabla: catastro_predio_equidos_historial_patologias
	* 
	 */
	Private $tabla="catastro_predio_equidos_historial_patologias";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_catastro_predio_equidos_historial_patologias";



	/**
	*Secuencia
*/
		 private $secuencial = '"CatastroPredioEquidosHistorialPatologias_"id_catastro_predio_equidos_historial_patologias_seq'; 



	/**
	* Constructor
	* $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
	* @par??metro  array|null $datos
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
	* @par??metro  string $name 
	* @par??metro  mixed $value 
	* @retorna void
	*/
	public function __set($name, $value)
	{
		$method = 'set' . $name;
		if (!method_exists($this, $method)) 
	{
		throw new \Exception('Clase Modelo: CatastroPredioEquidosHistorialPatologiasModelo. Propiedad especificada invalida: set'.$name);
	}
	$this->$method($value);
	}

	/**
	* Permitir el acceso a la propiedad
	* 
	* @par??metro  string $name 
	* @retorna mixed
	*/
	public function __get($name)
	{
	$method = 'get' . $name;
	if (!method_exists($this, $method))
	{
	  throw new \Exception('Clase Modelo: CatastroPredioEquidosHistorialPatologiasModelo. Propiedad especificada invalida: get'.$name);
	}
	return $this->$method();
	}

	/**
	* Llena el modelo con datos
	* 
	* @par??metro  array $datos 
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
	* Nombre del esquema del m??dulo 
	*
	* @par??metro $esquema
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
	* Set idCatastroPredioEquidosHistorialPatologias
	*
	*
	*
	* @par??metro Integer $idCatastroPredioEquidosHistorialPatologias
	* @return IdCatastroPredioEquidosHistorialPatologias
	*/
	public function setIdCatastroPredioEquidosHistorialPatologias($idCatastroPredioEquidosHistorialPatologias)
	{
	  $this->idCatastroPredioEquidosHistorialPatologias = (Integer) $idCatastroPredioEquidosHistorialPatologias;
	    return $this;
	}

	/**
	* Get idCatastroPredioEquidosHistorialPatologias
	*
	* @return null|Integer
	*/
	public function getIdCatastroPredioEquidosHistorialPatologias()
	{
		return $this->idCatastroPredioEquidosHistorialPatologias;
	}

	/**
	* Set idCatastroPredioEquidos
	*
	*
	*
	* @par??metro Integer $idCatastroPredioEquidos
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
	* @par??metro String $identificador
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
	* @par??metro Date $fechaCreacion
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
	* Set idEnfermedad
	*
	*
	*
	* @par??metro Integer $idEnfermedad
	* @return IdEnfermedad
	*/
	public function setIdEnfermedad($idEnfermedad)
	{
	  $this->idEnfermedad = (Integer) $idEnfermedad;
	    return $this;
	}

	/**
	* Get idEnfermedad
	*
	* @return null|Integer
	*/
	public function getIdEnfermedad()
	{
		return $this->idEnfermedad;
	}

	/**
	* Set enfermedad
	*
	*
	*
	* @par??metro String $enfermedad
	* @return Enfermedad
	*/
	public function setEnfermedad($enfermedad)
	{
	  $this->enfermedad = (String) $enfermedad;
	    return $this;
	}

	/**
	* Get enfermedad
	*
	* @return null|String
	*/
	public function getEnfermedad()
	{
		return $this->enfermedad;
	}

	/**
	* Set idVacuna
	*
	*
	*
	* @par??metro Integer $idVacuna
	* @return IdVacuna
	*/
	public function setIdVacuna($idVacuna)
	{
	  $this->idVacuna = (Integer) $idVacuna;
	    return $this;
	}

	/**
	* Get idVacuna
	*
	* @return null|Integer
	*/
	public function getIdVacuna()
	{
		return $this->idVacuna;
	}

	/**
	* Set vacuna
	*
	*
	*
	* @par??metro String $vacuna
	* @return Vacuna
	*/
	public function setVacuna($vacuna)
	{
	  $this->vacuna = (String) $vacuna;
	    return $this;
	}

	/**
	* Get vacuna
	*
	* @return null|String
	*/
	public function getVacuna()
	{
		return $this->vacuna;
	}

	/**
	* Set laboratorio
	*
	*
	*
	* @par??metro String $laboratorio
	* @return Laboratorio
	*/
	public function setLaboratorio($laboratorio)
	{
	  $this->laboratorio = (String) $laboratorio;
	    return $this;
	}

	/**
	* Get laboratorio
	*
	* @return null|String
	*/
	public function getLaboratorio()
	{
		return $this->laboratorio;
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
	* @return CatastroPredioEquidosHistorialPatologiasModelo
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
	* Busca una lista de acuerdo a los par??metros <params> enviados.
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
