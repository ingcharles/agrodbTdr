<?php
 /**
 * Modelo SeguimientosCuarentenariosModelo
 *
 * Este archivo se complementa con el archivo   SeguimientosCuarentenariosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022/02/02
 * @uses    SeguimientosCuarentenariosModelo
 * @package SeguimientoCuarentenario
 * @subpackage Modelos
 */
  namespace Agrodb\SeguimientoCuarentenario\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class SeguimientosCuarentenariosModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Se registra el identificador unico de la tabla (secuencial)  y es PRIMARY KEY
		*/
		protected $idSeguimientoCuarentenario;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Se registra el identificador unico de la tabla destinacion aduanera como FOREIGN KEY para su relación
		*/
		protected $idDestinacionAduanera;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Se registra el estado del seguimiento cuarentenario
abierto
cerrado
		*/
		protected $estado;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Se registra el numero de seguimiento que tiene el seguimiento cuarentenario
		*/
		protected $numeroSeguimientos;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Se registra el numero de plantas que tiene el seguimiento cuarentenario
		*/
		protected $numeroPlantas;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Se registra la cantidad de productos con la que el seguimiento cuarentenario realiza el cierre
		*/
		protected $cantidadProductoCierre;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Se registra fecha de cierre del seguimiento cuarentenario
		*/
		protected $fechaCierre;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Se registra la observacion de cierre del seguimiento cuarentenario
		*/
		protected $observacionCierre;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_seguimiento_cuarentenario";

	/**
	* Nombre de la tabla: seguimientos_cuarentenarios
	* 
	 */
	Private $tabla="seguimientos_cuarentenarios";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_seguimiento_cuarentenario";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_seguimiento_cuarentenario"."SeguimientosCuarentenarios_id_seguimiento_cuarentenario_seq'; 



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
		throw new \Exception('Clase Modelo: SeguimientosCuarentenariosModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: SeguimientosCuarentenariosModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_seguimiento_cuarentenario
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idSeguimientoCuarentenario
	*
	*Se registra el identificador unico de la tabla (secuencial)  y es PRIMARY KEY
	*
	* @parámetro Integer $idSeguimientoCuarentenario
	* @return IdSeguimientoCuarentenario
	*/
	public function setIdSeguimientoCuarentenario($idSeguimientoCuarentenario)
	{
	  $this->idSeguimientoCuarentenario = (Integer) $idSeguimientoCuarentenario;
	    return $this;
	}

	/**
	* Get idSeguimientoCuarentenario
	*
	* @return null|Integer
	*/
	public function getIdSeguimientoCuarentenario()
	{
		return $this->idSeguimientoCuarentenario;
	}

	/**
	* Set idDestinacionAduanera
	*
	*Se registra el identificador unico de la tabla destinacion aduanera como FOREIGN KEY para su relación
	*
	* @parámetro Integer $idDestinacionAduanera
	* @return IdDestinacionAduanera
	*/
	public function setIdDestinacionAduanera($idDestinacionAduanera)
	{
	  $this->idDestinacionAduanera = (Integer) $idDestinacionAduanera;
	    return $this;
	}

	/**
	* Get idDestinacionAduanera
	*
	* @return null|Integer
	*/
	public function getIdDestinacionAduanera()
	{
		return $this->idDestinacionAduanera;
	}

	/**
	* Set estado
	*
	*Se registra el estado del seguimiento cuarentenario
abierto
cerrado
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
	* Set numeroSeguimientos
	*
	*Se registra el numero de seguimiento que tiene el seguimiento cuarentenario
	*
	* @parámetro Integer $numeroSeguimientos
	* @return NumeroSeguimientos
	*/
	public function setNumeroSeguimientos($numeroSeguimientos)
	{
	  $this->numeroSeguimientos = (Integer) $numeroSeguimientos;
	    return $this;
	}

	/**
	* Get numeroSeguimientos
	*
	* @return null|Integer
	*/
	public function getNumeroSeguimientos()
	{
		return $this->numeroSeguimientos;
	}

	/**
	* Set numeroPlantas
	*
	*Se registra el numero de plantas que tiene el seguimiento cuarentenario
	*
	* @parámetro Integer $numeroPlantas
	* @return NumeroPlantas
	*/
	public function setNumeroPlantas($numeroPlantas)
	{
	  $this->numeroPlantas = (Integer) $numeroPlantas;
	    return $this;
	}

	/**
	* Get numeroPlantas
	*
	* @return null|Integer
	*/
	public function getNumeroPlantas()
	{
		return $this->numeroPlantas;
	}

	/**
	* Set cantidadProductoCierre
	*
	*Se registra la cantidad de productos con la que el seguimiento cuarentenario realiza el cierre
	*
	* @parámetro Integer $cantidadProductoCierre
	* @return CantidadProductoCierre
	*/
	public function setCantidadProductoCierre($cantidadProductoCierre)
	{
	  $this->cantidadProductoCierre = (Integer) $cantidadProductoCierre;
	    return $this;
	}

	/**
	* Get cantidadProductoCierre
	*
	* @return null|Integer
	*/
	public function getCantidadProductoCierre()
	{
		return $this->cantidadProductoCierre;
	}

	/**
	* Set fechaCierre
	*
	*Se registra fecha de cierre del seguimiento cuarentenario
	*
	* @parámetro Date $fechaCierre
	* @return FechaCierre
	*/
	public function setFechaCierre($fechaCierre)
	{
	  $this->fechaCierre = (String) $fechaCierre;
	    return $this;
	}

	/**
	* Get fechaCierre
	*
	* @return null|Date
	*/
	public function getFechaCierre()
	{
		return $this->fechaCierre;
	}

	/**
	* Set observacionCierre
	*
	*Se registra la observacion de cierre del seguimiento cuarentenario
	*
	* @parámetro String $observacionCierre
	* @return ObservacionCierre
	*/
	public function setObservacionCierre($observacionCierre)
	{
	  $this->observacionCierre = (String) $observacionCierre;
	    return $this;
	}

	/**
	* Get observacionCierre
	*
	* @return null|String
	*/
	public function getObservacionCierre()
	{
		return $this->observacionCierre;
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
	* @return SeguimientosCuarentenariosModelo
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
