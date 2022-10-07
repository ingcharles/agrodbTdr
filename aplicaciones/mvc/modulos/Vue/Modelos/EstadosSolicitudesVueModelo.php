<?php
/**
 * Modelo EstadosSolicitudesVueModelo
 *
 * Este archivo se complementa con el archivo EstadosSolicitudesVueLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-09-16
 * @uses EstadosSolicitudesVueModelo
 * @package Vue
 * @subpackage Modelos
 */
namespace Agrodb\Vue\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class EstadosSolicitudesVueModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador unico de la tabla
	 */
	protected $idEstadoSolicitudVue;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre del formulario que se está registrando en VUE ej. Importación, exportación, etc
	 */
	protected $tipoSolicitud;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador unico de la solicitud en sistema VUE
	 */
	protected $idVue;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de ingreso/actualización del registro
	 */
	protected $fechaRegistro;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Cantidad de días transcurridos para el proceso de cierre por no atención de la solicitud esto para estados de pago autorizado y subsanación
	 */
	protected $cantidadDia;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Estado actual de la solicitud de VUE
	 */
	protected $estadoSolicitud;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Estado anterior de la solicitud de VUE
	 */
	protected $estadoSolicitudAnterior;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Observacion del proceso aplicado a la solicitud
	 */
	protected $observacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Permite verificar el estado del procesamiento de ejecución del proceso automático
	 */
	protected $estadoProcesamiento;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_vue";

	/**
	 * Nombre de la tabla: estados_solicitudes_vue
	 */
	private $tabla = "estados_solicitudes_vue";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_estado_solicitud_vue";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_vue"."EstadosSolicitudesVue_id_estado_solicitud_vue_seq';

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
			throw new \Exception('Clase Modelo: EstadosSolicitudesVueModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: EstadosSolicitudesVueModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_vue
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idEstadoSolicitudVue
	 *
	 * Identificador unico de la tabla
	 *
	 * @parámetro Integer $idEstadoSolicitudVue
	 * @return IdEstadoSolicitudVue
	 */
	public function setIdEstadoSolicitudVue($idEstadoSolicitudVue){
		$this->idEstadoSolicitudVue = (integer) $idEstadoSolicitudVue;
		return $this;
	}

	/**
	 * Get idEstadoSolicitudVue
	 *
	 * @return null|Integer
	 */
	public function getIdEstadoSolicitudVue(){
		return $this->idEstadoSolicitudVue;
	}

	/**
	 * Set tipoSolicitud
	 *
	 * Nombre del formulario que se está registrando en VUE ej. Importación, exportación, etc
	 *
	 * @parámetro String $tipoSolicitud
	 * @return TipoSolicitud
	 */
	public function setTipoSolicitud($tipoSolicitud){
		$this->tipoSolicitud = (string) $tipoSolicitud;
		return $this;
	}

	/**
	 * Get tipoSolicitud
	 *
	 * @return null|String
	 */
	public function getTipoSolicitud(){
		return $this->tipoSolicitud;
	}

	/**
	 * Set idVue
	 *
	 * Identificador unico de la solicitud en sistema VUE
	 *
	 * @parámetro String $idVue
	 * @return IdVue
	 */
	public function setIdVue($idVue){
		$this->idVue = (string) $idVue;
		return $this;
	}

	/**
	 * Get idVue
	 *
	 * @return null|String
	 */
	public function getIdVue(){
		return $this->idVue;
	}

	/**
	 * Set fechaRegistro
	 *
	 * Fecha de ingreso/actualización del registro
	 *
	 * @parámetro Date $fechaRegistro
	 * @return FechaRegistro
	 */
	public function setFechaRegistro($fechaRegistro){
		$this->fechaRegistro = (string) $fechaRegistro;
		return $this;
	}

	/**
	 * Get fechaRegistro
	 *
	 * @return null|Date
	 */
	public function getFechaRegistro(){
		return $this->fechaRegistro;
	}

	/**
	 * Set cantidadDia
	 *
	 * Cantidad de días transcurridos para el proceso de cierre por no atención de la solicitud esto para estados de pago autorizado y subsanación
	 *
	 * @parámetro Integer $cantidadDia
	 * @return CantidadDia
	 */
	public function setCantidadDia($cantidadDia){
		$this->cantidadDia = (integer) $cantidadDia;
		return $this;
	}

	/**
	 * Get cantidadDia
	 *
	 * @return null|Integer
	 */
	public function getCantidadDia(){
		return $this->cantidadDia;
	}

	/**
	 * Set estadoSolicitud
	 *
	 * Estado actual de la solicitud de VUE
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
	 * Set estadoSolicitudAnterior
	 *
	 * Estado anterior de la solicitud de VUE
	 *
	 * @parámetro String $estadoSolicitudAnterior
	 * @return EstadoSolicitudAnterior
	 */
	public function setEstadoSolicitudAnterior($estadoSolicitudAnterior){
		$this->estadoSolicitudAnterior = (string) $estadoSolicitudAnterior;
		return $this;
	}

	/**
	 * Get estadoSolicitudAnterior
	 *
	 * @return null|String
	 */
	public function getEstadoSolicitudAnterior(){
		return $this->estadoSolicitudAnterior;
	}

	/**
	 * Set observacion
	 *
	 * Observacion del proceso aplicado a la solicitud
	 *
	 * @parámetro String $observacion
	 * @return Observacion
	 */
	public function setObservacion($observacion){
		$this->observacion = (string) $observacion;
		return $this;
	}

	/**
	 * Get observacion
	 *
	 * @return null|String
	 */
	public function getObservacion(){
		return $this->observacion;
	}

	/**
	 * Set estadoProcesamiento
	 *
	 * Permite verificar el estado del procesamiento de ejecución del proceso automático
	 *
	 * @parámetro String $estadoProcesamiento
	 * @return EstadoProcesamiento
	 */
	public function setEstadoProcesamiento($estadoProcesamiento){
		$this->estadoProcesamiento = (string) $estadoProcesamiento;
		return $this;
	}

	/**
	 * Get estadoProcesamiento
	 *
	 * @return null|String
	 */
	public function getEstadoProcesamiento(){
		return $this->estadoProcesamiento;
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
	 * @return EstadosSolicitudesVueModelo
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
