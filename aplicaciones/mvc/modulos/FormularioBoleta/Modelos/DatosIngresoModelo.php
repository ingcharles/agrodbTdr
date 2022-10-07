<?php
 /**
 * Modelo DatosIngresoModelo
 *
 * Este archivo se complementa con el archivo   DatosIngresoLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-08-14
 * @uses    DatosIngresoModelo
 * @package FormularioBoleta
 * @subpackage Modelos
 */
  namespace Agrodb\FormularioBoleta\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class DatosIngresoModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
		protected $idDatosIngreso;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* identificador del usuario
		*/
		protected $identificador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombres
		*/
		protected $nombres;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Apellidos
		*/
		protected $apellidos;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Genero
		*/
		protected $genero;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nacionalidad
		*/
		protected $nacionalidad;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* País de procedencia
		*/
		protected $paisProcedencia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Puerto aeropuerto
		*/
		protected $puertoAeropuerto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Países visitados
		*/
		protected $paisesVisitados;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Dirección de estadía en Ecuador
		*/
		protected $direccionEcuador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Medio de ingreso
		*/
		protected $medioIngreso;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Medio de transporte
		*/
		protected $medioTransporte;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Compañía de transporte
		*/
		protected $companiaTransporte;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Código de la boleta
		*/
		protected $codigoBoleta;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * Código de la boleta
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
	Private $esquema ="g_formulario_boleta";

	/**
	* Nombre de la tabla: datos_ingreso
	* 
	 */
	Private $tabla="datos_ingreso";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_datos_ingreso";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_formulario_boleta"."datos_ingreso_id_datos_ingreso_seq'; 



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
		throw new \Exception('Clase Modelo: DatosIngresoModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: DatosIngresoModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_formulario_boleta
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idDatosIngreso
	*
	*Llave primaria de la tabla
	*
	* @parámetro Integer $idDatosIngreso
	* @return IdDatosIngreso
	*/
	public function setIdDatosIngreso($idDatosIngreso)
	{
	  $this->idDatosIngreso = (Integer) $idDatosIngreso;
	    return $this;
	}

	/**
	* Get idDatosIngreso
	*
	* @return null|Integer
	*/
	public function getIdDatosIngreso()
	{
		return $this->idDatosIngreso;
	}

	/**
	* Set identificador
	*
	*identificador del usuario
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
	* Set nombres
	*
	*Nombres
	*
	* @parámetro String $nombres
	* @return Nombres
	*/
	public function setNombres($nombres)
	{
	  $this->nombres = (String) $nombres;
	    return $this;
	}

	/**
	* Get nombres
	*
	* @return null|String
	*/
	public function getNombres()
	{
		return $this->nombres;
	}

	/**
	* Set apellidos
	*
	*Apellidos
	*
	* @parámetro String $apellidos
	* @return Apellidos
	*/
	public function setApellidos($apellidos)
	{
	  $this->apellidos = (String) $apellidos;
	    return $this;
	}

	/**
	* Get apellidos
	*
	* @return null|String
	*/
	public function getApellidos()
	{
		return $this->apellidos;
	}

	/**
	* Set genero
	*
	*Genero
	*
	* @parámetro String $genero
	* @return Genero
	*/
	public function setGenero($genero)
	{
	  $this->genero = (String) $genero;
	    return $this;
	}

	/**
	* Get genero
	*
	* @return null|String
	*/
	public function getGenero()
	{
		return $this->genero;
	}

	/**
	* Set nacionalidad
	*
	*Nacionalidad
	*
	* @parámetro String $nacionalidad
	* @return Nacionalidad
	*/
	public function setNacionalidad($nacionalidad)
	{
	  $this->nacionalidad = (String) $nacionalidad;
	    return $this;
	}

	/**
	* Get nacionalidad
	*
	* @return null|String
	*/
	public function getNacionalidad()
	{
		return $this->nacionalidad;
	}

	/**
	* Set paisProcedencia
	*
	*País de procedencia
	*
	* @parámetro String $paisProcedencia
	* @return PaisProcedencia
	*/
	public function setPaisProcedencia($paisProcedencia)
	{
	  $this->paisProcedencia = (String) $paisProcedencia;
	    return $this;
	}

	/**
	* Get paisProcedencia
	*
	* @return null|String
	*/
	public function getPaisProcedencia()
	{
		return $this->paisProcedencia;
	}

	/**
	* Set puertoAeropuerto
	*
	*Puerto aeropuerto
	*
	* @parámetro String $puertoAeropuerto
	* @return PuertoAeropuerto
	*/
	public function setPuertoAeropuerto($puertoAeropuerto)
	{
	  $this->puertoAeropuerto = (String) $puertoAeropuerto;
	    return $this;
	}

	/**
	* Get puertoAeropuerto
	*
	* @return null|String
	*/
	public function getPuertoAeropuerto()
	{
		return $this->puertoAeropuerto;
	}

	/**
	* Set paisesVisitados
	*
	*Países visitados
	*
	* @parámetro String $paisesVisitados
	* @return PaisesVisitados
	*/
	public function setPaisesVisitados($paisesVisitados)
	{
	  $this->paisesVisitados = (String) $paisesVisitados;
	    return $this;
	}

	/**
	* Get paisesVisitados
	*
	* @return null|String
	*/
	public function getPaisesVisitados()
	{
		return $this->paisesVisitados;
	}

	/**
	* Set direccionEcuador
	*
	*Dirección de estadía en Ecuador
	*
	* @parámetro String $direccionEcuador
	* @return DireccionEcuador
	*/
	public function setDireccionEcuador($direccionEcuador)
	{
	  $this->direccionEcuador = (String) $direccionEcuador;
	    return $this;
	}

	/**
	* Get direccionEcuador
	*
	* @return null|String
	*/
	public function getDireccionEcuador()
	{
		return $this->direccionEcuador;
	}

	/**
	* Set medioIngreso
	*
	*Medio de ingreso
	*
	* @parámetro String $medioIngreso
	* @return MedioIngreso
	*/
	public function setMedioIngreso($medioIngreso)
	{
	  $this->medioIngreso = (String) $medioIngreso;
	    return $this;
	}

	/**
	* Get medioIngreso
	*
	* @return null|String
	*/
	public function getMedioIngreso()
	{
		return $this->medioIngreso;
	}

	/**
	* Set medioTransporte
	*
	*Medio de transporte
	*
	* @parámetro String $medioTransporte
	* @return MedioTransporte
	*/
	public function setMedioTransporte($medioTransporte)
	{
	  $this->medioTransporte = (String) $medioTransporte;
	    return $this;
	}

	/**
	* Get medioTransporte
	*
	* @return null|String
	*/
	public function getMedioTransporte()
	{
		return $this->medioTransporte;
	}

	/**
	* Set companiaTransporte
	*
	*Compañía de transporte
	*
	* @parámetro String $companiaTransporte
	* @return CompaniaTransporte
	*/
	public function setCompaniaTransporte($companiaTransporte)
	{
	  $this->companiaTransporte = (String) $companiaTransporte;
	    return $this;
	}

	/**
	* Get companiaTransporte
	*
	* @return null|String
	*/
	public function getCompaniaTransporte()
	{
		return $this->companiaTransporte;
	}

	/**
	* Set codigoBoleta
	*
	*Código de la boleta
	*
	* @parámetro String $codigoBoleta
	* @return CodigoBoleta
	*/
	public function setCodigoBoleta($codigoBoleta)
	{
	  $this->codigoBoleta = (String) $codigoBoleta;
	    return $this;
	}

	/**
	* Get codigoBoleta
	*
	* @return null|String
	*/
	public function getCodigoBoleta()
	{
		return $this->codigoBoleta;
	}
	/**
	 * Set codigoBoleta
	 *
	 *Código de la boleta
	 *
	 * @parámetro String $fechaCreacion
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
	 * @return null|String
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
	* @return DatosIngresoModelo
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
