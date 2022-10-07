<?php
 /**
 * Modelo DetalleSolicitudInspeccionModelo
 *
 * Este archivo se complementa con el archivo   DetalleSolicitudInspeccionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    DetalleSolicitudInspeccionModelo
 * @package InspeccionMusaceas
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionMusaceas\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class DetalleSolicitudInspeccionModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
		protected $idDetalleSolicitudInspeccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Razón social
		*/
		protected $razonSocial;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Área
		*/
		protected $area;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $numCajas;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de creación del registro
		*/
		protected $fechaCreacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave foránea de la tabla solicitud_inspeccion
		*/
		protected $idSolicitudInspeccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Provincia
		*/
		protected $provincia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Código del área
		*/
		protected $codigoArea;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Código del MAG
		*/
		protected $codigoMag;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $identificadorOperador;

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
	* Nombre de la tabla: detalle_solicitud_inspeccion
	* 
	 */
	Private $tabla="detalle_solicitud_inspeccion";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_detalle_solicitud_inspeccion";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_inspeccion_musaceas"."detalle_solicitud_inspeccion_id_detalle_solicitud_inspeccio_seq'; 



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
		throw new \Exception('Clase Modelo: DetalleSolicitudInspeccionModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: DetalleSolicitudInspeccionModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idDetalleSolicitudInspeccion
	*
	*Llave primaria de la tabla
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
	* Set razonSocial
	*
	*Razón social
	*
	* @parámetro String $razonSocial
	* @return RazonSocial
	*/
	public function setRazonSocial($razonSocial)
	{
	  $this->razonSocial = (String) $razonSocial;
	    return $this;
	}

	/**
	* Get razonSocial
	*
	* @return null|String
	*/
	public function getRazonSocial()
	{
		return $this->razonSocial;
	}

	/**
	* Set area
	*
	*Área
	*
	* @parámetro String $area
	* @return Area
	*/
	public function setArea($area)
	{
	  $this->area = (String) $area;
	    return $this;
	}

	/**
	* Get area
	*
	* @return null|String
	*/
	public function getArea()
	{
		return $this->area;
	}

	/**
	* Set numCajas
	*
	*
	*
	* @parámetro Integer $numCajas
	* @return NumCajas
	*/
	public function setNumCajas($numCajas)
	{
	  $this->numCajas = (Integer) $numCajas;
	    return $this;
	}

	/**
	* Get numCajas
	*
	* @return null|Integer
	*/
	public function getNumCajas()
	{
		return $this->numCajas;
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
	* Set idSolicitudInspeccion
	*
	*Llave foránea de la tabla solicitud_inspeccion
	*
	* @parámetro Integer $idSolicitudInspeccion
	* @return IdSolicitudInspeccion
	*/
	public function setIdSolicitudInspeccion($idSolicitudInspeccion)
	{
	  $this->idSolicitudInspeccion = (Integer) $idSolicitudInspeccion;
	    return $this;
	}

	/**
	* Get idSolicitudInspeccion
	*
	* @return null|Integer
	*/
	public function getIdSolicitudInspeccion()
	{
		return $this->idSolicitudInspeccion;
	}

	/**
	* Set provincia
	*
	*Provincia
	*
	* @parámetro String $provincia
	* @return Provincia
	*/
	public function setProvincia($provincia)
	{
	  $this->provincia = (String) $provincia;
	    return $this;
	}

	/**
	* Get provincia
	*
	* @return null|String
	*/
	public function getProvincia()
	{
		return $this->provincia;
	}

	/**
	* Set codigoArea
	*
	*Código del área
	*
	* @parámetro String $codigoArea
	* @return CodigoArea
	*/
	public function setCodigoArea($codigoArea)
	{
	  $this->codigoArea = (String) $codigoArea;
	    return $this;
	}

	/**
	* Get codigoArea
	*
	* @return null|String
	*/
	public function getCodigoArea()
	{
		return $this->codigoArea;
	}

	/**
	* Set codigoMag
	*
	*Código del MAG
	*
	* @parámetro String $codigoMag
	* @return CodigoMag
	*/
	public function setCodigoMag($codigoMag)
	{
	  $this->codigoMag = (String) $codigoMag;
	    return $this;
	}

	/**
	* Get codigoMag
	*
	* @return null|String
	*/
	public function getCodigoMag()
	{
		return $this->codigoMag;
	}

	/**
	* Set identificadorOperador
	*
	*
	*
	* @parámetro String $identificadorOperador
	* @return IdentificadorOperador
	*/
	public function setIdentificadorOperador($identificadorOperador)
	{
	  $this->identificadorOperador = (String) $identificadorOperador;
	    return $this;
	}

	/**
	* Get identificadorOperador
	*
	* @return null|String
	*/
	public function getIdentificadorOperador()
	{
		return $this->identificadorOperador;
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
	* @return DetalleSolicitudInspeccionModelo
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
