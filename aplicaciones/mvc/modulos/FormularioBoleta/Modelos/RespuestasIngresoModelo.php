<?php
 /**
 * Modelo RespuestasIngresoModelo
 *
 * Este archivo se complementa con el archivo   RespuestasIngresoLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-08-14
 * @uses    RespuestasIngresoModelo
 * @package FormularioBoleta
 * @subpackage Modelos
 */
  namespace Agrodb\FormularioBoleta\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class RespuestasIngresoModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave primaria de tabla
		*/
		protected $idRespuestasIngreso;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave foránea  de la tabla preguntas_ingreso
		*/
		protected $idPreguntasIngreso;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave foránea de la tabla datos_ingreso
		*/
		protected $idDatosIngreso;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Respuesta de la pregunta
		*/
		protected $respuesta;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Número de hombres
		*/
		protected $numHombres;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Número de mujeres
		*/
		protected $numMujeres;

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
	* Nombre de la tabla: respuestas_ingreso
	* 
	 */
	Private $tabla="respuestas_ingreso";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_respuestas_ingreso";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_formulario_boleta.respuestas_ingreso_id_respuestas_ingreso_seq'; 



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
		throw new \Exception('Clase Modelo: RespuestasIngresoModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: RespuestasIngresoModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idRespuestasIngreso
	*
	*Llave primaria de tabla
	*
	* @parámetro Integer $idRespuestasIngreso
	* @return IdRespuestasIngreso
	*/
	public function setIdRespuestasIngreso($idRespuestasIngreso)
	{
	  $this->idRespuestasIngreso = (Integer) $idRespuestasIngreso;
	    return $this;
	}

	/**
	* Get idRespuestasIngreso
	*
	* @return null|Integer
	*/
	public function getIdRespuestasIngreso()
	{
		return $this->idRespuestasIngreso;
	}

	/**
	* Set idPreguntasIngreso
	*
	*Llave foránea  de la tabla preguntas_ingreso
	*
	* @parámetro Integer $idPreguntasIngreso
	* @return IdPreguntasIngreso
	*/
	public function setIdPreguntasIngreso($idPreguntasIngreso)
	{
	  $this->idPreguntasIngreso = (Integer) $idPreguntasIngreso;
	    return $this;
	}

	/**
	* Get idPreguntasIngreso
	*
	* @return null|Integer
	*/
	public function getIdPreguntasIngreso()
	{
		return $this->idPreguntasIngreso;
	}

	/**
	* Set idDatosIngreso
	*
	*Llave foránea de la tabla datos_ingreso
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
	* Set respuesta
	*
	*Respuesta de la pregunta
	*
	* @parámetro String $respuesta
	* @return Respuesta
	*/
	public function setRespuesta($respuesta)
	{
	  $this->respuesta = (String) $respuesta;
	    return $this;
	}

	/**
	* Get respuesta
	*
	* @return null|String
	*/
	public function getRespuesta()
	{
		return $this->respuesta;
	}

	/**
	* Set numHombres
	*
	*Número de hombres
	*
	* @parámetro Integer $numHombres
	* @return NumHombres
	*/
	public function setNumHombres($numHombres)
	{
	  $this->numHombres = (Integer) $numHombres;
	    return $this;
	}

	/**
	* Get numHombres
	*
	* @return null|Integer
	*/
	public function getNumHombres()
	{
		return $this->numHombres;
	}

	/**
	* Set numMujeres
	*
	*Número de mujeres
	*
	* @parámetro Integer $numMujeres
	* @return NumMujeres
	*/
	public function setNumMujeres($numMujeres)
	{
	  $this->numMujeres = (Integer) $numMujeres;
	    return $this;
	}

	/**
	* Get numMujeres
	*
	* @return null|Integer
	*/
	public function getNumMujeres()
	{
		return $this->numMujeres;
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
	* @return RespuestasIngresoModelo
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
