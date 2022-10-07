<?php
 /**
 * Modelo TiposOperacionModelo
 *
 * Este archivo se complementa con el archivo   TiposOperacionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-09-10
 * @uses    TiposOperacionModelo
 * @package Catalogos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class TiposOperacionModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idTipoOperacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $nombre;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $codigo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idArea;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 1 -> activo
2-> incativo
9->eliminado
		*/
		protected $estado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $requiereAnexo;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idFlujoOperacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $requiereProducto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $operacionMultiple;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que determina si el tipo de operación se debe listar para la parametrización de productos con trazabilidad
		*/
		protected $trazabilidadTipoOperacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $ubicacionRevision;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_catalogos";

	/**
	* Nombre de la tabla: tipos_operacion
	* 
	 */
	Private $tabla="tipos_operacion";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_tipo_operacion";



	/**
	*Secuencia
*/
		 private $secuencial = '"TiposOperacion_"id_tipo_operacion_seq'; 



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
		throw new \Exception('Clase Modelo: TiposOperacionModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: TiposOperacionModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_catalogos
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idTipoOperacion
	*
	*
	*
	* @parámetro Integer $idTipoOperacion
	* @return IdTipoOperacion
	*/
	public function setIdTipoOperacion($idTipoOperacion)
	{
	  $this->idTipoOperacion = (Integer) $idTipoOperacion;
	    return $this;
	}

	/**
	* Get idTipoOperacion
	*
	* @return null|Integer
	*/
	public function getIdTipoOperacion()
	{
		return $this->idTipoOperacion;
	}

	/**
	* Set nombre
	*
	*
	*
	* @parámetro String $nombre
	* @return Nombre
	*/
	public function setNombre($nombre)
	{
	  $this->nombre = (String) $nombre;
	    return $this;
	}

	/**
	* Get nombre
	*
	* @return null|String
	*/
	public function getNombre()
	{
		return $this->nombre;
	}

	/**
	* Set codigo
	*
	*
	*
	* @parámetro String $codigo
	* @return Codigo
	*/
	public function setCodigo($codigo)
	{
	  $this->codigo = (String) $codigo;
	    return $this;
	}

	/**
	* Get codigo
	*
	* @return null|String
	*/
	public function getCodigo()
	{
		return $this->codigo;
	}

	/**
	* Set idArea
	*
	*
	*
	* @parámetro String $idArea
	* @return IdArea
	*/
	public function setIdArea($idArea)
	{
	  $this->idArea = (String) $idArea;
	    return $this;
	}

	/**
	* Get idArea
	*
	* @return null|String
	*/
	public function getIdArea()
	{
		return $this->idArea;
	}

	/**
	* Set estado
	*
	*1 -> activo
2-> incativo
9->eliminado
	*
	* @parámetro Integer $estado
	* @return Estado
	*/
	public function setEstado($estado)
	{
	  $this->estado = (Integer) $estado;
	    return $this;
	}

	/**
	* Get estado
	*
	* @return null|Integer
	*/
	public function getEstado()
	{
		return $this->estado;
	}

	/**
	* Set requiereAnexo
	*
	*
	*
	* @parámetro String $requiereAnexo
	* @return RequiereAnexo
	*/
	public function setRequiereAnexo($requiereAnexo)
	{
	  $this->requiereAnexo = (String) $requiereAnexo;
	    return $this;
	}

	/**
	* Get requiereAnexo
	*
	* @return null|String
	*/
	public function getRequiereAnexo()
	{
		return $this->requiereAnexo;
	}

	/**
	* Set idFlujoOperacion
	*
	*
	*
	* @parámetro Integer $idFlujoOperacion
	* @return IdFlujoOperacion
	*/
	public function setIdFlujoOperacion($idFlujoOperacion)
	{
	  $this->idFlujoOperacion = (Integer) $idFlujoOperacion;
	    return $this;
	}

	/**
	* Get idFlujoOperacion
	*
	* @return null|Integer
	*/
	public function getIdFlujoOperacion()
	{
		return $this->idFlujoOperacion;
	}

	/**
	* Set requiereProducto
	*
	*
	*
	* @parámetro String $requiereProducto
	* @return RequiereProducto
	*/
	public function setRequiereProducto($requiereProducto)
	{
	  $this->requiereProducto = (String) $requiereProducto;
	    return $this;
	}

	/**
	* Get requiereProducto
	*
	* @return null|String
	*/
	public function getRequiereProducto()
	{
		return $this->requiereProducto;
	}

	/**
	* Set operacionMultiple
	*
	*
	*
	* @parámetro String $operacionMultiple
	* @return OperacionMultiple
	*/
	public function setOperacionMultiple($operacionMultiple)
	{
	  $this->operacionMultiple = (String) $operacionMultiple;
	    return $this;
	}

	/**
	* Get operacionMultiple
	*
	* @return null|String
	*/
	public function getOperacionMultiple()
	{
		return $this->operacionMultiple;
	}

	/**
	* Set trazabilidadTipoOperacion
	*
	*Campo que determina si el tipo de operación se debe listar para la parametrización de productos con trazabilidad
	*
	* @parámetro String $trazabilidadTipoOperacion
	* @return TrazabilidadTipoOperacion
	*/
	public function setTrazabilidadTipoOperacion($trazabilidadTipoOperacion)
	{
	  $this->trazabilidadTipoOperacion = (String) $trazabilidadTipoOperacion;
	    return $this;
	}

	/**
	* Get trazabilidadTipoOperacion
	*
	* @return null|String
	*/
	public function getTrazabilidadTipoOperacion()
	{
		return $this->trazabilidadTipoOperacion;
	}

	/**
	* Set ubicacionRevision
	*
	*
	*
	* @parámetro String $ubicacionRevision
	* @return UbicacionRevision
	*/
	public function setUbicacionRevision($ubicacionRevision)
	{
	  $this->ubicacionRevision = (String) $ubicacionRevision;
	    return $this;
	}

	/**
	* Get ubicacionRevision
	*
	* @return null|String
	*/
	public function getUbicacionRevision()
	{
		return $this->ubicacionRevision;
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
	* @return TiposOperacionModelo
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
