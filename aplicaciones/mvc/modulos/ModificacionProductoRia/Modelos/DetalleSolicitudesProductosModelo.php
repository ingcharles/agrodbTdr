<?php
 /**
 * Modelo DetalleSolicitudesProductosModelo
 *
 * Este archivo se complementa con el archivo   DetalleSolicitudesProductosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-21
 * @uses    DetalleSolicitudesProductosModelo
 * @package ModificacionProductoRia
 * @subpackage Modelos
 */
  namespace Agrodb\ModificacionProductoRia\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class DetalleSolicitudesProductosModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla
		*/
		protected $idDetalleSolicitudProducto;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla  g_modificacion_productos.solicitudes_productos
		*/
		protected $idSolicitudProducto;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla g_catalogos.tipo_modificacion_producto
		*/
		protected $idTipoModificacionProducto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el tipo de modificacion
		*/
		protected $tipoModificacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el tiempo de atencion
		*/
		protected $tiempoAtencion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el documento de respaldo
		*/
		protected $rutaDocumentoRespaldo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena enlace de ruta de documento de respaldo
		*/
		protected $enlaceDocumentoRespaldo;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacna la fecha de creacion del registro
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
	* Nombre de la tabla: detalle_solicitudes_productos
	* 
	 */
	Private $tabla="detalle_solicitudes_productos";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_detalle_solicitud_producto";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_modificacion_productos"."detalle_solicitudes_productos_id_detalle_solicitud_producto_seq'; 



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
		throw new \Exception('Clase Modelo: DetalleSolicitudesProductosModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: DetalleSolicitudesProductosModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idDetalleSolicitudProducto
	*
	*Identificador unico de la tabla
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
	* Set idSolicitudProducto
	*
	*Identificador unico de la tabla  g_modificacion_productos.solicitudes_productos
	*
	* @parámetro Integer $idSolicitudProducto
	* @return IdSolicitudProducto
	*/
	public function setIdSolicitudProducto($idSolicitudProducto)
	{
	  $this->idSolicitudProducto = (Integer) $idSolicitudProducto;
	    return $this;
	}

	/**
	* Get idSolicitudProducto
	*
	* @return null|Integer
	*/
	public function getIdSolicitudProducto()
	{
		return $this->idSolicitudProducto;
	}

	/**
	* Set idTipoModificacionProducto
	*
	*Identificador unico de la tabla g_catalogos.tipo_modificacion_producto
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
	*Campo que almacena el tipo de modificacion
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
	* Set tiempoAtencion
	*
	*Campo que almacena el tiempo de atencion
	*
	* @parámetro Integer $tiempoAtencion
	* @return TiempoAtencion
	*/
	public function setTiempoAtencion($tiempoAtencion)
	{
	  $this->tiempoAtencion = (Integer) $tiempoAtencion;
	    return $this;
	}

	/**
	* Get tiempoAtencion
	*
	* @return null|Integer
	*/
	public function getTiempoAtencion()
	{
		return $this->tiempoAtencion;
	}

	/**
	* Set rutaDocumentoRespaldo
	*
	*Campo que almacena el documento de respaldo
	*
	* @parámetro String $rutaDocumentoRespaldo
	* @return RutaDocumentoRespaldo
	*/
	public function setRutaDocumentoRespaldo($rutaDocumentoRespaldo)
	{
	  $this->rutaDocumentoRespaldo = (String) $rutaDocumentoRespaldo;
	    return $this;
	}

	/**
	* Get rutaDocumentoRespaldo
	*
	* @return null|String
	*/
	public function getRutaDocumentoRespaldo()
	{
		return $this->rutaDocumentoRespaldo;
	}

	/**
	* Set enlaceDocumentoRespaldo
	*
	*Campo que almacena enlace de ruta de documento de respaldo
	*
	* @parámetro String $enlaceDocumentoRespaldo
	* @return EnlaceDocumentoRespaldo
	*/
	public function setEnlaceDocumentoRespaldo($enlaceDocumentoRespaldo)
	{
	  $this->enlaceDocumentoRespaldo = (String) $enlaceDocumentoRespaldo;
	    return $this;
	}

	/**
	* Get enlaceDocumentoRespaldo
	*
	* @return null|String
	*/
	public function getEnlaceDocumentoRespaldo()
	{
		return $this->enlaceDocumentoRespaldo;
	}

	/**
	* Set fechaCreacion
	*
	*Campo que almacna la fecha de creacion del registro
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
	* @return DetalleSolicitudesProductosModelo
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
