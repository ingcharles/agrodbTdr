<?php
 /**
 * Modelo SolicitudesProductosModelo
 *
 * Este archivo se complementa con el archivo   SolicitudesProductosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-13
 * @uses    SolicitudesProductosModelo
 * @package ModificacionProductoRia
 * @subpackage Modelos
 */
  namespace Agrodb\ModificacionProductoRia\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class SolicitudesProductosModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla
		*/
		protected $idSolicitudProducto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el numero de solicitud creada
		*/
		protected $numeroSolicitud;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Razon social del operador
		*/
		protected $identificadorOperador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la razon social
		*/
		protected $razonSocial;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre del representante legal del exportador
		*/
		protected $representanteLegal;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la direccion del exportador
		*/
		protected $direccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el telefono del exportador
		*/
		protected $telefono;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el correo del exportador
		*/
		protected $correo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la informacion del representante tecnico del exportador
		*/
		protected $representanteTecnico;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el area tematica
		*/
		protected $idArea;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla g_catalogos.tipo_productos
		*/
		protected $idTipoProducto;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla g_catalogo.subtipo_productos
		*/
		protected $idSubtipoProducto;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla g_catalogos.productos
		*/
		protected $idProducto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el numero de registro del producto
		*/
		protected $numeroRegistro;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la etiqueta del producto
		*/
		protected $rutaEtiquetaProducto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena observacion
		*/
		protected $observacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena si solicita o no un descuento
		*/
		protected $descuento;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el estado de la solicitud
		*/
		protected $estadoSolicitudProducto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena a ruta del certificado
		*/
		protected $rutaCertificado;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la fecha de creacion de la solicitud
		*/
		protected $fechaCreacion;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la fecha de subsanacion de la solicitud
		*/
		protected $fechaSubsanacion;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la fecha de aprobacion de la solicitud
		*/
		protected $fechaAprobacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre de la provincia del oeprador
		*/
		protected $provinciaOperador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $identificadorRevisor;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Observacion del revisor en el proceso de inspeccion
		*/
		protected $observacionRevisor;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Ruta a documento con las observaciones del proceso de revision tecnica
		*/
		protected $rutaRevisor;

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
	* Nombre de la tabla: solicitudes_productos
	* 
	 */
	Private $tabla="solicitudes_productos";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_solicitud_producto";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_modificacion_productos"."solicitudes_productos_id_solicitud_producto_seq'; 



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
		throw new \Exception('Clase Modelo: SolicitudesProductosModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: SolicitudesProductosModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idSolicitudProducto
	*
	*Identificador unico de la tabla
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
	* Set numeroSolicitud
	*
	*Campo que almacena el numero de solicitud creada
	*
	* @parámetro String $numeroSolicitud
	* @return NumeroSolicitud
	*/
	public function setNumeroSolicitud($numeroSolicitud)
	{
	  $this->numeroSolicitud = (String) $numeroSolicitud;
	    return $this;
	}

	/**
	* Get numeroSolicitud
	*
	* @return null|String
	*/
	public function getNumeroSolicitud()
	{
		return $this->numeroSolicitud;
	}

	/**
	* Set identificadorOperador
	*
	*Razon social del operador
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
	* Set razonSocial
	*
	*Campo que almacena la razon social
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
	* Set representanteLegal
	*
	*Campo que almacena el nombre del representante legal del exportador
	*
	* @parámetro String $representanteLegal
	* @return RepresentanteLegal
	*/
	public function setRepresentanteLegal($representanteLegal)
	{
	  $this->representanteLegal = (String) $representanteLegal;
	    return $this;
	}

	/**
	* Get representanteLegal
	*
	* @return null|String
	*/
	public function getRepresentanteLegal()
	{
		return $this->representanteLegal;
	}

	/**
	* Set direccion
	*
	*Campo que almacena la direccion del exportador
	*
	* @parámetro String $direccion
	* @return Direccion
	*/
	public function setDireccion($direccion)
	{
	  $this->direccion = (String) $direccion;
	    return $this;
	}

	/**
	* Get direccion
	*
	* @return null|String
	*/
	public function getDireccion()
	{
		return $this->direccion;
	}

	/**
	* Set telefono
	*
	*Campo que almacena el telefono del exportador
	*
	* @parámetro String $telefono
	* @return Telefono
	*/
	public function setTelefono($telefono)
	{
	  $this->telefono = (String) $telefono;
	    return $this;
	}

	/**
	* Get telefono
	*
	* @return null|String
	*/
	public function getTelefono()
	{
		return $this->telefono;
	}

	/**
	* Set correo
	*
	*Campo que almacena el correo del exportador
	*
	* @parámetro String $correo
	* @return Correo
	*/
	public function setCorreo($correo)
	{
	  $this->correo = (String) $correo;
	    return $this;
	}

	/**
	* Get correo
	*
	* @return null|String
	*/
	public function getCorreo()
	{
		return $this->correo;
	}

	/**
	* Set representanteTecnico
	*
	*Campo que almacena la informacion del representante tecnico del exportador
	*
	* @parámetro String $representanteTecnico
	* @return RepresentanteTecnico
	*/
	public function setRepresentanteTecnico($representanteTecnico)
	{
	  $this->representanteTecnico = (String) $representanteTecnico;
	    return $this;
	}

	/**
	* Get representanteTecnico
	*
	* @return null|String
	*/
	public function getRepresentanteTecnico()
	{
		return $this->representanteTecnico;
	}

	/**
	* Set idArea
	*
	*Campo que almacena el area tematica
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
	* Set idTipoProducto
	*
	*Identificador unico de la tabla g_catalogos.tipo_productos
	*
	* @parámetro Integer $idTipoProducto
	* @return IdTipoProducto
	*/
	public function setIdTipoProducto($idTipoProducto)
	{
	  $this->idTipoProducto = (Integer) $idTipoProducto;
	    return $this;
	}

	/**
	* Get idTipoProducto
	*
	* @return null|Integer
	*/
	public function getIdTipoProducto()
	{
		return $this->idTipoProducto;
	}

	/**
	* Set idSubtipoProducto
	*
	*Identificador unico de la tabla g_catalogo.subtipo_productos
	*
	* @parámetro Integer $idSubtipoProducto
	* @return IdSubtipoProducto
	*/
	public function setIdSubtipoProducto($idSubtipoProducto)
	{
	  $this->idSubtipoProducto = (Integer) $idSubtipoProducto;
	    return $this;
	}

	/**
	* Get idSubtipoProducto
	*
	* @return null|Integer
	*/
	public function getIdSubtipoProducto()
	{
		return $this->idSubtipoProducto;
	}

	/**
	* Set idProducto
	*
	*Identificador unico de la tabla g_catalogos.productos
	*
	* @parámetro Integer $idProducto
	* @return IdProducto
	*/
	public function setIdProducto($idProducto)
	{
	  $this->idProducto = (Integer) $idProducto;
	    return $this;
	}

	/**
	* Get idProducto
	*
	* @return null|Integer
	*/
	public function getIdProducto()
	{
		return $this->idProducto;
	}

	/**
	* Set numeroRegistro
	*
	*Campo que almacena el numero de registro del producto
	*
	* @parámetro String $numeroRegistro
	* @return NumeroRegistro
	*/
	public function setNumeroRegistro($numeroRegistro)
	{
	  $this->numeroRegistro = (String) $numeroRegistro;
	    return $this;
	}

	/**
	* Get numeroRegistro
	*
	* @return null|String
	*/
	public function getNumeroRegistro()
	{
		return $this->numeroRegistro;
	}

	/**
	* Set rutaEtiquetaProducto
	*
	*Campo que almacena la etiqueta del producto
	*
	* @parámetro String $rutaEtiquetaProducto
	* @return RutaEtiquetaProducto
	*/
	public function setRutaEtiquetaProducto($rutaEtiquetaProducto)
	{
	  $this->rutaEtiquetaProducto = (String) $rutaEtiquetaProducto;
	    return $this;
	}

	/**
	* Get rutaEtiquetaProducto
	*
	* @return null|String
	*/
	public function getRutaEtiquetaProducto()
	{
		return $this->rutaEtiquetaProducto;
	}

	/**
	* Set observacion
	*
	*Campo que almacena observacion
	*
	* @parámetro String $observacion
	* @return Observacion
	*/
	public function setObservacion($observacion)
	{
	  $this->observacion = (String) $observacion;
	    return $this;
	}

	/**
	* Get observacion
	*
	* @return null|String
	*/
	public function getObservacion()
	{
		return $this->observacion;
	}

	/**
	* Set descuento
	*
	*Campo que almacena si solicita o no un descuento
	*
	* @parámetro String $descuento
	* @return Descuento
	*/
	public function setDescuento($descuento)
	{
	  $this->descuento = (String) $descuento;
	    return $this;
	}

	/**
	* Get descuento
	*
	* @return null|String
	*/
	public function getDescuento()
	{
		return $this->descuento;
	}

	/**
	* Set estadoSolicitudProducto
	*
	*Campo que almacena el estado de la solicitud
	*
	* @parámetro String $estadoSolicitudProducto
	* @return EstadoSolicitudProducto
	*/
	public function setEstadoSolicitudProducto($estadoSolicitudProducto)
	{
	  $this->estadoSolicitudProducto = (String) $estadoSolicitudProducto;
	    return $this;
	}

	/**
	* Get estadoSolicitudProducto
	*
	* @return null|String
	*/
	public function getEstadoSolicitudProducto()
	{
		return $this->estadoSolicitudProducto;
	}

	/**
	* Set rutaCertificado
	*
	*Campo que almacena a ruta del certificado
	*
	* @parámetro String $rutaCertificado
	* @return RutaCertificado
	*/
	public function setRutaCertificado($rutaCertificado)
	{
	  $this->rutaCertificado = (String) $rutaCertificado;
	    return $this;
	}

	/**
	* Get rutaCertificado
	*
	* @return null|String
	*/
	public function getRutaCertificado()
	{
		return $this->rutaCertificado;
	}

	/**
	* Set fechaCreacion
	*
	*Campo que almacena la fecha de creacion de la solicitud
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
	* Set fechaSubsanacion
	*
	*Campo que almacena la fecha de subsanacion de la solicitud
	*
	* @parámetro Date $fechaSubsanacion
	* @return FechaSubsanacion
	*/
	public function setFechaSubsanacion($fechaSubsanacion)
	{
	  $this->fechaSubsanacion = (String) $fechaSubsanacion;
	    return $this;
	}

	/**
	* Get fechaSubsanacion
	*
	* @return null|Date
	*/
	public function getFechaSubsanacion()
	{
		return $this->fechaSubsanacion;
	}

	/**
	* Set fechaAprobacion
	*
	*Campo que almacena la fecha de aprobacion de la solicitud
	*
	* @parámetro Date $fechaAprobacion
	* @return FechaAprobacion
	*/
	public function setFechaAprobacion($fechaAprobacion)
	{
	  $this->fechaAprobacion = (String) $fechaAprobacion;
	    return $this;
	}

	/**
	* Get fechaAprobacion
	*
	* @return null|Date
	*/
	public function getFechaAprobacion()
	{
		return $this->fechaAprobacion;
	}

	/**
	* Set provinciaOperador
	*
	*Nombre de la provincia del oeprador
	*
	* @parámetro String $provinciaOperador
	* @return ProvinciaOperador
	*/
	public function setProvinciaOperador($provinciaOperador)
	{
	  $this->provinciaOperador = (String) $provinciaOperador;
	    return $this;
	}

	/**
	* Get provinciaOperador
	*
	* @return null|String
	*/
	public function getProvinciaOperador()
	{
		return $this->provinciaOperador;
	}

	/**
	* Set identificadorRevisor
	*
	*
	*
	* @parámetro String $identificadorRevisor
	* @return IdentificadorRevisor
	*/
	public function setIdentificadorRevisor($identificadorRevisor)
	{
	  $this->identificadorRevisor = (String) $identificadorRevisor;
	    return $this;
	}

	/**
	* Get identificadorRevisor
	*
	* @return null|String
	*/
	public function getIdentificadorRevisor()
	{
		return $this->identificadorRevisor;
	}

	/**
	* Set observacionRevisor
	*
	*Observacion del revisor en el proceso de inspeccion
	*
	* @parámetro String $observacionRevisor
	* @return ObservacionRevisor
	*/
	public function setObservacionRevisor($observacionRevisor)
	{
	  $this->observacionRevisor = (String) $observacionRevisor;
	    return $this;
	}

	/**
	* Get observacionRevisor
	*
	* @return null|String
	*/
	public function getObservacionRevisor()
	{
		return $this->observacionRevisor;
	}

	/**
	* Set rutaRevisor
	*
	*Ruta a documento con las observaciones del proceso de revision tecnica
	*
	* @parámetro String $rutaRevisor
	* @return RutaRevisor
	*/
	public function setRutaRevisor($rutaRevisor)
	{
	  $this->rutaRevisor = (String) $rutaRevisor;
	    return $this;
	}

	/**
	* Get rutaRevisor
	*
	* @return null|String
	*/
	public function getRutaRevisor()
	{
		return $this->rutaRevisor;
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
	* @return SolicitudesProductosModelo
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
