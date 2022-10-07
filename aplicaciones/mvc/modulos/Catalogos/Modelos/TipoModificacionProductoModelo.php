<?php
 /**
 * Modelo TipoModificacionProductoModelo
 *
 * Este archivo se complementa con el archivo   TipoModificacionProductoLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-13
 * @uses    TipoModificacionProductoModelo
 * @package Catalogos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class TipoModificacionProductoModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla
		*/
		protected $idTipoModificacionProducto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Tipo de modificacion que se va aplicar al producto
		*/
		protected $tipoModificacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Codigo del tipo de modificacion que se va aplicar al producto
		*/
		protected $codigoModificacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Dias que dispone el tecnico para dar atencion al tipo de modificacion
		*/
		protected $diasAtencion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del area temantica
		*/
		protected $idArea;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Estado del registro
		*/
		protected $estado;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de creacion del registro
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
	Private $esquema ="g_catalogos";

	/**
	* Nombre de la tabla: tipo_modificacion_producto
	* 
	 */
	Private $tabla="tipo_modificacion_producto";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_tipo_modificacion_producto";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_catalogos"."TipoModificacionProducto_id_tipo_modificacion_producto_seq'; 



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
		throw new \Exception('Clase Modelo: TipoModificacionProductoModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: TipoModificacionProductoModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idTipoModificacionProducto
	*
	*Identificador unico de la tabla
	*
	* @parámetro Integer $idTipoModificacionProducto
	* @return IdTipoModificacionProducto
	*/
	public function setIdTipoModificacionProducto($idTipoModificacionProducto)
	{
	  $this->idTipoModificacionProducto = (Integer) $idTipoModificacionProducto;
	    return $this;
	}

	/**
	* Get idTipoModificacionProducto
	*
	* @return null|Integer
	*/
	public function getIdTipoModificacionProducto()
	{
		return $this->idTipoModificacionProducto;
	}

	/**
	* Set tipoModificacion
	*
	*Tipo de modificacion que se va aplicar al producto
	*
	* @parámetro String $tipoModificacion
	* @return TipoModificacion
	*/
	public function setTipoModificacion($tipoModificacion)
	{
	  $this->tipoModificacion = (String) $tipoModificacion;
	    return $this;
	}

	/**
	* Get tipoModificacion
	*
	* @return null|String
	*/
	public function getTipoModificacion()
	{
		return $this->tipoModificacion;
	}

	/**
	* Set codigoModificacion
	*
	*Codigo del tipo de modificacion que se va aplicar al producto
	*
	* @parámetro String $codigoModificacion
	* @return CodigoModificacion
	*/
	public function setCodigoModificacion($codigoModificacion)
	{
	  $this->codigoModificacion = (String) $codigoModificacion;
	    return $this;
	}

	/**
	* Get codigoModificacion
	*
	* @return null|String
	*/
	public function getCodigoModificacion()
	{
		return $this->codigoModificacion;
	}

	/**
	* Set diasAtencion
	*
	*Dias que dispone el tecnico para dar atencion al tipo de modificacion
	*
	* @parámetro Integer $diasAtencion
	* @return DiasAtencion
	*/
	public function setDiasAtencion($diasAtencion)
	{
	  $this->diasAtencion = (Integer) $diasAtencion;
	    return $this;
	}

	/**
	* Get diasAtencion
	*
	* @return null|Integer
	*/
	public function getDiasAtencion()
	{
		return $this->diasAtencion;
	}

	/**
	* Set idArea
	*
	*Identificador del area temantica
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
	*Estado del registro
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
	* Set fechaCreacion
	*
	*Fecha de creacion del registro
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
	* @return TipoModificacionProductoModelo
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
		return parent::buscarLista($where, $order);
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
