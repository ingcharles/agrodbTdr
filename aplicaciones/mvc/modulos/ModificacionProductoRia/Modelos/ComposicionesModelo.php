<?php
 /**
 * Modelo ComposicionesModelo
 *
 * Este archivo se complementa con el archivo   ComposicionesLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-13
 * @uses    ComposicionesModelo
 * @package ModificacionProductoRia
 * @subpackage Modelos
 */
  namespace Agrodb\ModificacionProductoRia\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class ComposicionesModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llve primaria de la tabla
		*/
		protected $idComposicion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave foranea de la tabla g_modificaicon_producto.detalle_solicitudes_productos
		*/
		protected $idDetalleSolicitudProducto;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico del ingrediente activo
		*/
		protected $idIngredienteActivo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre del ingrediente activo
		*/
		protected $ingredienteActivo;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla de componentes
		*/
		protected $idTipoComponente;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre del tipo de componente
		*/
		protected $tipoComponente;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $concentracion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nomenclatura de la unidad de medida
		*/
		protected $unidadMedida;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Estado relacionado con la tabla de origen
		*/
		protected $estado;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla origen de registo
		*/
		protected $idTablaOrigen;
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
	Private $esquema ="g_modificacion_productos";

	/**
	* Nombre de la tabla: composiciones
	* 
	 */
	Private $tabla="composiciones";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_composicion";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_modificacion_productos"."composiciones_id_composicion_seq';



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
		throw new \Exception('Clase Modelo: ComposicionesModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: ComposicionesModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_modificacion_productos
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idComposicion
	*
	*Llve primaria de la tabla
	*
	* @parámetro Integer $idComposicion
	* @return IdComposicion
	*/
	public function setIdComposicion($idComposicion)
	{
	  $this->idComposicion = (Integer) $idComposicion;
	    return $this;
	}

	/**
	* Get idComposicion
	*
	* @return null|Integer
	*/
	public function getIdComposicion()
	{
		return $this->idComposicion;
	}

	/**
	* Set idDetalleSolicitudProducto
	*
	*Llave foranea de la tabla g_modificaicon_producto.detalle_solicitudes_productos
	*
	* @parámetro Integer $idDetalleSolicitudProducto
	* @return IdDetalleSolicitudProducto
	*/
	public function setIdDetalleSolicitudProducto($idDetalleSolicitudProducto)
	{
	  $this->idDetalleSolicitudProducto = (Integer) $idDetalleSolicitudProducto;
	    return $this;
	}

	/**
	* Get idDetalleSolicitudProducto
	*
	* @return null|Integer
	*/
	public function getIdDetalleSolicitudProducto()
	{
		return $this->idDetalleSolicitudProducto;
	}

	/**
	* Set idIngredienteActivo
	*
	*Identificador unico del ingrediente activo
	*
	* @parámetro Integer $idIngredienteActivo
	* @return IdIngredienteActivo
	*/
	public function setIdIngredienteActivo($idIngredienteActivo)
	{
	  $this->idIngredienteActivo = (Integer) $idIngredienteActivo;
	    return $this;
	}

	/**
	* Get idIngredienteActivo
	*
	* @return null|Integer
	*/
	public function getIdIngredienteActivo()
	{
		return $this->idIngredienteActivo;
	}

	/**
	* Set ingredienteActivo
	*
	*Nombre del ingrediente activo
	*
	* @parámetro String $ingredienteActivo
	* @return IngredienteActivo
	*/
	public function setIngredienteActivo($ingredienteActivo)
	{
	  $this->ingredienteActivo = (String) $ingredienteActivo;
	    return $this;
	}

	/**
	* Get ingredienteActivo
	*
	* @return null|String
	*/
	public function getIngredienteActivo()
	{
		return $this->ingredienteActivo;
	}

	/**
	* Set idTipoComponente
	*
	*Identificador unico de la tabla de componentes
	*
	* @parámetro Integer $idTipoComponente
	* @return IdTipoComponente
	*/
	public function setIdTipoComponente($idTipoComponente)
	{
	  $this->idTipoComponente = (Integer) $idTipoComponente;
	    return $this;
	}

	/**
	* Get idTipoComponente
	*
	* @return null|Integer
	*/
	public function getIdTipoComponente()
	{
		return $this->idTipoComponente;
	}

	/**
	* Set tipoComponente
	*
	*Nombre del tipo de componente
	*
	* @parámetro String $tipoComponente
	* @return TipoComponente
	*/
	public function setTipoComponente($tipoComponente)
	{
	  $this->tipoComponente = (String) $tipoComponente;
	    return $this;
	}

	/**
	* Get tipoComponente
	*
	* @return null|String
	*/
	public function getTipoComponente()
	{
		return $this->tipoComponente;
	}

	/**
	* Set concentracion
	*
	*
	*
	* @parámetro String $concentracion
	* @return Concentracion
	*/
	public function setConcentracion($concentracion)
	{
	  $this->concentracion = (String) $concentracion;
	    return $this;
	}

	/**
	* Get concentracion
	*
	* @return null|String
	*/
	public function getConcentracion()
	{
		return $this->concentracion;
	}

	/**
	* Set unidadMedida
	*
	*Nomenclatura de la unidad de medida
	*
	* @parámetro String $unidadMedida
	* @return UnidadMedida
	*/
	public function setUnidadMedida($unidadMedida)
	{
	  $this->unidadMedida = (String) $unidadMedida;
	    return $this;
	}

	/**
	* Get unidadMedida
	*
	* @return null|String
	*/
	public function getUnidadMedida()
	{
		return $this->unidadMedida;
	}

	/**
	* Set estado
	*
	*Estado relacionado con la tabla de origen
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
	* Set idTablaOrigen
	*
	*Identificador unico de la tabla origen de registo
	*
	* @parámetro Integer $idTablaOrigen
	* @return IdTablaOrigen
	*/
	public function setIdTablaOrigen($idTablaOrigen)
	{
	  $this->idTablaOrigen = (Integer) $idTablaOrigen;
	    return $this;
	}

	/**
	* Get idTablaOrigen
	*
	* @return null|Integer
	*/
	public function getIdTablaOrigen()
	{
		return $this->idTablaOrigen;
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
	* @return ComposicionesModelo
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
