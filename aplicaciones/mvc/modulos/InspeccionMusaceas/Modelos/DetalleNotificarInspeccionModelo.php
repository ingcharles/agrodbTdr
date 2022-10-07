<?php
 /**
 * Modelo DetalleNotificarInspeccionModelo
 *
 * Este archivo se complementa con el archivo   DetalleNotificarInspeccionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    DetalleNotificarInspeccionModelo
 * @package InspeccionMusaceas
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionMusaceas\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class DetalleNotificarInspeccionModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
		protected $idDetalleNotificarInspeccion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave foránea de la tabla notificar_inspeccion
		*/
		protected $idNotificarInspeccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Correo del productor
		*/
		protected $correoProductor;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave foránea de la tabla detalle_solicitud_inspeccion
		*/
		protected $idDetalleSolicitudInspeccion;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de creación del registro
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
	Private $esquema ="g_inspeccion_musaceas";

	/**
	* Nombre de la tabla: detalle_notificar_inspeccion
	* 
	 */
	Private $tabla="detalle_notificar_inspeccion";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_detalle_notificar_inspeccion";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_inspeccion_musaceas"."detalle_notificar_inspeccion_id_detalle_notificar_inspeccio_seq'; 



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
		throw new \Exception('Clase Modelo: DetalleNotificarInspeccionModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: DetalleNotificarInspeccionModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_inspeccion_musaceas
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idDetalleNotificarInspeccion
	*
	*Llave primaria de la tabla
	*
	* @parámetro Integer $idDetalleNotificarInspeccion
	* @return IdDetalleNotificarInspeccion
	*/
	public function setIdDetalleNotificarInspeccion($idDetalleNotificarInspeccion)
	{
	  $this->idDetalleNotificarInspeccion = (Integer) $idDetalleNotificarInspeccion;
	    return $this;
	}

	/**
	* Get idDetalleNotificarInspeccion
	*
	* @return null|Integer
	*/
	public function getIdDetalleNotificarInspeccion()
	{
		return $this->idDetalleNotificarInspeccion;
	}

	/**
	* Set idNotificarInspeccion
	*
	*Llave foránea de la tabla notificar_inspeccion
	*
	* @parámetro Integer $idNotificarInspeccion
	* @return IdNotificarInspeccion
	*/
	public function setIdNotificarInspeccion($idNotificarInspeccion)
	{
	  $this->idNotificarInspeccion = (Integer) $idNotificarInspeccion;
	    return $this;
	}

	/**
	* Get idNotificarInspeccion
	*
	* @return null|Integer
	*/
	public function getIdNotificarInspeccion()
	{
		return $this->idNotificarInspeccion;
	}

	/**
	* Set correoProductor
	*
	*Correo del productor
	*
	* @parámetro String $correoProductor
	* @return CorreoProductor
	*/
	public function setCorreoProductor($correoProductor)
	{
	  $this->correoProductor = (String) $correoProductor;
	    return $this;
	}

	/**
	* Get correoProductor
	*
	* @return null|String
	*/
	public function getCorreoProductor()
	{
		return $this->correoProductor;
	}

	/**
	* Set idDetalleSolicitudInspeccion
	*
	*Llave foránea de la tabla detalle_solicitud_inspeccion
	*
	* @parámetro Integer $idDetalleSolicitudInspeccion
	* @return IdDetalleSolicitudInspeccion
	*/
	public function setIdDetalleSolicitudInspeccion($idDetalleSolicitudInspeccion)
	{
	  $this->idDetalleSolicitudInspeccion = (Integer) $idDetalleSolicitudInspeccion;
	    return $this;
	}

	/**
	* Get idDetalleSolicitudInspeccion
	*
	* @return null|Integer
	*/
	public function getIdDetalleSolicitudInspeccion()
	{
		return $this->idDetalleSolicitudInspeccion;
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
	* @return DetalleNotificarInspeccionModelo
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
