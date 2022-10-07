<?php
/**
 * Modelo ProveedorExteriorModelo
 *
 * Este archivo se complementa con el archivo ProveedorExteriorLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-07-25
 * @uses ProveedorExteriorModelo
 * @package ProveedoresExterior
 * @subpackage Modelos
 */
namespace Agrodb\ProveedoresExterior\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ProveedorExteriorModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador único de la tabla
	 */
	protected $idProveedorExterior;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena el identificador del operador solicitante
	 */
	protected $identificadorOperador;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena el id de la provincia del operador solicitante
	 */
	protected $idProvinciaOperador;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena el nombre de la provincia del operador solicitante
	 */
	protected $nombreProvinciaOperador;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena el nombre del fabricante
	 */
	protected $nombreFabricante;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador del país de la tabla g_catalogos.localizacion
	 */
	protected $idPaisFabricante;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena el nombre del país del fabricante
	 */
	protected $nombrePaisFabricante;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena la dirección del fabricante
	 */
	protected $direccionFabricante;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacenan los servicios oficiales que regulan los productos de la fábrica
	 */
	protected $servicioOficial;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que amacena el código de creación de la solicitud
	 */
	protected $codigoCreacionSolicitud;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena el codigo de aprobacion de la solicitud
	 */
	protected $codigoAprobacionSolicitud;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena el estado de la solicitud
	 */
	protected $estadoSolicitud;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena la fecha de envio de la solicitud a revision documental
	 */
	protected $fechaEnvioDocumental;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena la fecha en que se realiza la revision documental
	 */
	protected $fechaAtencionDocumental;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena la observacion registrada sobre la solicitud
	 */
	protected $observacionSolicitud;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena el identificador del técnico que realiza la revision
	 */
	protected $identificadorRevisor;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena la fecha de aprobación de la solicitud
	 */
	protected $fechaAprobacionSolicitud;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena la fecha en la que se modifica la solicitud
	 */
	protected $fechaModificacionSolicitud;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena si la solicitud es modificada
	 */
	protected $esModificada;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena el id de la solicitud padre modificada
	 */
	protected $idSolicitudModificada;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena la fecha de creacion de la solicitud
	 */
	protected $fechaCreacionSolicitud;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_proveedores_exterior";

	/**
	 * Nombre de la tabla: proveedor_exterior
	 */
	private $tabla = "proveedor_exterior";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_proveedor_exterior";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_proveedores_exterior"."proveedor_exterior_id_proveedor_exterior_seq';

	/**
	 * Constructor
	 * $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
	 *
	 * @parámetro  array|null $datos
	 * @retorna void
	 */
	public function __construct(array $datos = null){
		if (is_array($datos)){
			$this->setOptions($datos);
		}
		$features = new \Zend\Db\TableGateway\Feature\SequenceFeature($this->clavePrimaria, $this->secuencial);
		parent::__construct($this->esquema, $this->tabla, $features);
	}

	/**
	 * Permitir el acceso a la propiedad
	 *
	 * @parámetro  string $name
	 * @parámetro  mixed $value
	 * @retorna void
	 */
	public function __set($name, $value){
		$method = 'set' . $name;
		if (! method_exists($this, $method)){
			throw new \Exception('Clase Modelo: ProveedorExteriorModelo. Propiedad especificada invalida: set' . $name);
		}
		$this->$method($value);
	}

	/**
	 * Permitir el acceso a la propiedad
	 *
	 * @parámetro  string $name
	 * @retorna mixed
	 */
	public function __get($name){
		$method = 'get' . $name;
		if (! method_exists($this, $method)){
			throw new \Exception('Clase Modelo: ProveedorExteriorModelo. Propiedad especificada invalida: get' . $name);
		}
		return $this->$method();
	}

	/**
	 * Llena el modelo con datos
	 *
	 * @parámetro  array $datos
	 * @retorna Modelo
	 */
	public function setOptions(array $datos){
		$methods = get_class_methods($this);
		foreach ($datos as $key => $value){
			$key_original = $key;
			if (strpos($key, '_') > 0){
				$aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function ($string){
					return ucfirst($string[1]);
				}, ucwords($key));
				$key = $aux;
			}
			$method = 'set' . ucfirst($key);
			if (in_array($method, $methods)){
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
	public function getPrepararDatos(){
		$claseArray = get_object_vars($this);
		foreach ($this->campos as $key => $value){
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
	public function setEsquema($esquema){
		$this->esquema = $esquema;
		return $this;
	}

	/**
	 * Get g_proveedores_exterior
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idProveedorExterior
	 *
	 * Identificador único de la tabla
	 *
	 * @parámetro Integer $idProveedorExterior
	 * @return IdProveedorExterior
	 */
	public function setIdProveedorExterior($idProveedorExterior){
		$this->idProveedorExterior = (integer) $idProveedorExterior;
		return $this;
	}

	/**
	 * Get idProveedorExterior
	 *
	 * @return null|Integer
	 */
	public function getIdProveedorExterior(){
		return $this->idProveedorExterior;
	}

	/**
	 * Set identificadorOperador
	 *
	 * Campo que almacena el identificador del operador solicitante
	 *
	 * @parámetro String $identificadorOperador
	 * @return IdentificadorOperador
	 */
	public function setIdentificadorOperador($identificadorOperador){
		$this->identificadorOperador = (string) $identificadorOperador;
		return $this;
	}

	/**
	 * Get identificadorOperador
	 *
	 * @return null|String
	 */
	public function getIdentificadorOperador(){
		return $this->identificadorOperador;
	}

	/**
	 * Set idProvinciaOperador
	 *
	 * Campo que almacena el id de la provincia del operador solicitante
	 *
	 * @parámetro Integer $idProvinciaOperador
	 * @return IdProvinciaOperador
	 */
	public function setIdProvinciaOperador($idProvinciaOperador){
		$this->idProvinciaOperador = (integer) $idProvinciaOperador;
		return $this;
	}

	/**
	 * Get idProvinciaOperador
	 *
	 * @return null|Integer
	 */
	public function getIdProvinciaOperador(){
		return $this->idProvinciaOperador;
	}

	/**
	 * Set nombreProvinciaOperador
	 *
	 * Campo que almacena el nombre de la provincia del operador solicitante
	 *
	 * @parámetro String $nombreProvinciaOperador
	 * @return NombreProvinciaOperador
	 */
	public function setNombreProvinciaOperador($nombreProvinciaOperador){
		$this->nombreProvinciaOperador = (string) $nombreProvinciaOperador;
		return $this;
	}

	/**
	 * Get nombreProvinciaOperador
	 *
	 * @return null|String
	 */
	public function getNombreProvinciaOperador(){
		return $this->nombreProvinciaOperador;
	}

	/**
	 * Set nombreFabricante
	 *
	 * Campo que almacena el nombre del fabricante
	 *
	 * @parámetro String $nombreFabricante
	 * @return NombreFabricante
	 */
	public function setNombreFabricante($nombreFabricante){
		$this->nombreFabricante = (string) $nombreFabricante;
		return $this;
	}

	/**
	 * Get nombreFabricante
	 *
	 * @return null|String
	 */
	public function getNombreFabricante(){
		return $this->nombreFabricante;
	}

	/**
	 * Set idPaisFabricante
	 *
	 * Identificador del país de la tabla g_catalogos.localizacion
	 *
	 * @parámetro Integer $idPaisFabricante
	 * @return IdPaisFabricante
	 */
	public function setIdPaisFabricante($idPaisFabricante){
		$this->idPaisFabricante = (integer) $idPaisFabricante;
		return $this;
	}

	/**
	 * Get idPaisFabricante
	 *
	 * @return null|Integer
	 */
	public function getIdPaisFabricante(){
		return $this->idPaisFabricante;
	}

	/**
	 * Set nombrePaisFabricante
	 *
	 * Campo que almacena el nombre del país del fabricante
	 *
	 * @parámetro String $nombrePaisFabricante
	 * @return NombrePaisFabricante
	 */
	public function setNombrePaisFabricante($nombrePaisFabricante){
		$this->nombrePaisFabricante = (string) $nombrePaisFabricante;
		return $this;
	}

	/**
	 * Get nombrePaisFabricante
	 *
	 * @return null|String
	 */
	public function getNombrePaisFabricante(){
		return $this->nombrePaisFabricante;
	}

	/**
	 * Set direccionFabricante
	 *
	 * Campo que almacena la dirección del fabricante
	 *
	 * @parámetro String $direccionFabricante
	 * @return DireccionFabricante
	 */
	public function setDireccionFabricante($direccionFabricante){
		$this->direccionFabricante = (string) $direccionFabricante;
		return $this;
	}

	/**
	 * Get direccionFabricante
	 *
	 * @return null|String
	 */
	public function getDireccionFabricante(){
		return $this->direccionFabricante;
	}

	/**
	 * Set servicioOficial
	 *
	 * Campo que almacenan los servicios oficiales que regulan los productos de la fábrica
	 *
	 * @parámetro String $servicioOficial
	 * @return ServicioOficial
	 */
	public function setServicioOficial($servicioOficial){
		$this->servicioOficial = (string) $servicioOficial;
		return $this;
	}

	/**
	 * Get servicioOficial
	 *
	 * @return null|String
	 */
	public function getServicioOficial(){
		return $this->servicioOficial;
	}

	/**
	 * Set codigoCreacionSolicitud
	 *
	 * Campo que amacena el código de creación de la solicitud
	 *
	 * @parámetro String $codigoCreacionSolicitud
	 * @return CodigoCreacionSolicitud
	 */
	public function setCodigoCreacionSolicitud($codigoCreacionSolicitud){
		$this->codigoCreacionSolicitud = (string) $codigoCreacionSolicitud;
		return $this;
	}

	/**
	 * Get codigoCreacionSolicitud
	 *
	 * @return null|String
	 */
	public function getCodigoCreacionSolicitud(){
		return $this->codigoCreacionSolicitud;
	}

	/**
	 * Set codigoAprobacionSolicitud
	 *
	 * Campo que almacena el codigo de aprobacion de la solicitud
	 *
	 * @parámetro String $codigoAprobacionSolicitud
	 * @return CodigoAprobacionSolicitud
	 */
	public function setCodigoAprobacionSolicitud($codigoAprobacionSolicitud){
		$this->codigoAprobacionSolicitud = (string) $codigoAprobacionSolicitud;
		return $this;
	}

	/**
	 * Get codigoAprobacionSolicitud
	 *
	 * @return null|String
	 */
	public function getCodigoAprobacionSolicitud(){
		return $this->codigoAprobacionSolicitud;
	}

	/**
	 * Set estadoSolicitud
	 *
	 * Campo que almacena el estado de la solicitud
	 *
	 * @parámetro String $estadoSolicitud
	 * @return EstadoSolicitud
	 */
	public function setEstadoSolicitud($estadoSolicitud){
		$this->estadoSolicitud = (string) $estadoSolicitud;
		return $this;
	}

	/**
	 * Get estadoSolicitud
	 *
	 * @return null|String
	 */
	public function getEstadoSolicitud(){
		return $this->estadoSolicitud;
	}

	/**
	 * Set fechaEnvioDocumental
	 *
	 * Campo que almacena la fecha de envio de la solicitud a revision documental
	 *
	 * @parámetro Date $fechaEnvioDocumental
	 * @return FechaEnvioDocumental
	 */
	public function setFechaEnvioDocumental($fechaEnvioDocumental){
		$this->fechaEnvioDocumental = (string) $fechaEnvioDocumental;
		return $this;
	}

	/**
	 * Get fechaEnvioDocumental
	 *
	 * @return null|Date
	 */
	public function getFechaEnvioDocumental(){
		return $this->fechaEnvioDocumental;
	}

	/**
	 * Set fechaAtencionDocumental
	 *
	 * Campo que almacena la fecha en que se realiza la revision documental
	 *
	 * @parámetro Date $fechaAtencionDocumental
	 * @return FechaAtencionDocumental
	 */
	public function setFechaAtencionDocumental($fechaAtencionDocumental){
		$this->fechaAtencionDocumental = $fechaAtencionDocumental;
		return $this;
	}

	/**
	 * Get fechaAtencionDocumental
	 *
	 * @return null|Date
	 */
	public function getFechaAtencionDocumental(){
		return $this->fechaAtencionDocumental;
	}

	/**
	 * Set observacionSolicitud
	 *
	 * Campo que almacena la observacion registrada sobre la solicitud
	 *
	 * @parámetro String $observacionSolicitud
	 * @return ObservacionSolicitud
	 */
	public function setObservacionSolicitud($observacionSolicitud){
		$this->observacionSolicitud = (string) $observacionSolicitud;
		return $this;
	}

	/**
	 * Get observacionSolicitud
	 *
	 * @return null|String
	 */
	public function getObservacionSolicitud(){
		return $this->observacionSolicitud;
	}

	/**
	 * Set identificadorRevisor
	 *
	 * Campo que almacena el identificador del técnico que realiza la revision
	 *
	 * @parámetro String $identificadorRevisor
	 * @return IdentificadorRevisor
	 */
	public function setIdentificadorRevisor($identificadorRevisor){
		$this->identificadorRevisor = (string) $identificadorRevisor;
		return $this;
	}

	/**
	 * Get identificadorRevisor
	 *
	 * @return null|String
	 */
	public function getIdentificadorRevisor(){
		return $this->identificadorRevisor;
	}

	/**
	 * Set fechaAprobacionSolicitud
	 *
	 * Campo que almacena la fecha de aprobación de la solicitud
	 *
	 * @parámetro Date $fechaAprobacionSolicitud
	 * @return FechaAprobacionSolicitud
	 */
	public function setFechaAprobacionSolicitud($fechaAprobacionSolicitud){
		$this->fechaAprobacionSolicitud = (string) $fechaAprobacionSolicitud;
		return $this;
	}

	/**
	 * Get fechaAprobacionSolicitud
	 *
	 * @return null|Date
	 */
	public function getFechaAprobacionSolicitud(){
		return $this->fechaAprobacionSolicitud;
	}

	/**
	 * Set fechaModificacionSolicitud
	 *
	 * Campo que almacena la fecha en la que se modifica la solicitud
	 *
	 * @parámetro Date $fechaModificacionSolicitud
	 * @return FechaModificacionSolicitud
	 */
	public function setFechaModificacionSolicitud($fechaModificacionSolicitud){
		$this->fechaModificacionSolicitud = (string) $fechaModificacionSolicitud;
		return $this;
	}

	/**
	 * Get fechaModificacionSolicitud
	 *
	 * @return null|Date
	 */
	public function getFechaModificacionSolicitud(){
		return $this->fechaModificacionSolicitud;
	}

	/**
	 * Set esModificada
	 *
	 * Campo que almacena si la solicitud es modificada
	 *
	 * @parámetro String $esModificada
	 * @return EsModificada
	 */
	public function setEsModificada($esModificada){
		$this->esModificada = (string) $esModificada;
		return $this;
	}

	/**
	 * Get esModificada
	 *
	 * @return null|String
	 */
	public function getEsModificada(){
		return $this->esModificada;
	}

	/**
	 * Set idSolicitudModificada
	 *
	 * Campo que almacena el id de la solicitud padre modificada
	 *
	 * @parámetro Integer $idSolicitudModificada
	 * @return IdSolicitudModificada
	 */
	public function setIdSolicitudModificada($idSolicitudModificada){
		$this->idSolicitudModificada = (integer) $idSolicitudModificada;
		return $this;
	}

	/**
	 * Get idSolicitudModificada
	 *
	 * @return null|Integer
	 */
	public function getIdSolicitudModificada(){
		return $this->idSolicitudModificada;
	}

	/**
	 * Set fechaCreacionSolicitud
	 *
	 * Campo que almacena la fecha de creacion de la solicitud
	 *
	 * @parámetro Date $fechaCreacionSolicitud
	 * @return FechaCreacionSolicitud
	 */
	public function setFechaCreacionSolicitud($fechaCreacionSolicitud){
		$this->fechaCreacionSolicitud = (string) $fechaCreacionSolicitud;
		return $this;
	}

	/**
	 * Get fechaCreacionSolicitud
	 *
	 * @return null|Date
	 */
	public function getFechaCreacionSolicitud(){
		return $this->fechaCreacionSolicitud;
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		return parent::guardar($datos);
	}

	/**
	 * Actualiza un registro actual
	 *
	 * @param array $datos
	 * @param int $id
	 * @return int
	 */
	public function actualizar(Array $datos, $id){
		return parent::actualizar($datos, $this->clavePrimaria . " = " . $id);
	}

	/**
	 * Borra el registro actual
	 *
	 * @param
	 *        	string Where|array $where
	 * @return int
	 */
	public function borrar($id){
		return parent::borrar($this->clavePrimaria . " = " . $id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return ProveedorExteriorModelo
	 */
	public function buscar($id){
		return $this->setOptions(parent::buscar($this->clavePrimaria . " = " . $id));
		return $this;
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return parent::buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return parent::buscarLista($where);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function ejecutarConsulta($consulta){
		return parent::ejecutarConsulta($consulta);
	}
}
