<?php
/**
 * Modelo OperacionesModelo
 *
 * Este archivo se complementa con el archivo OperacionesLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2020-09-18
 * @uses OperacionesModelo
 * @package AdministrarOperacionesGuia
 * @subpackage Modelos
 */
namespace Agrodb\AdministrarOperacionesGuia\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class OperacionesModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador único de la tabla
	 */
	protected $idOperacion;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      lave foranea que hace referencia al atabla de identificador de tipo de operación declarado por el operador
	 */
	protected $idTipoOperacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador del operador cédula o RUC
	 */
	protected $identificadorOperador;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Estado en el que se encuentra la operación
	 */
	protected $estado;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que permite colocar una observación en la operación
	 */
	protected $observacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo no utilizado
	 */
	protected $informe;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador de la tabla productos que determina el producto al que pertenece la operación
	 */
	protected $idProducto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre del producto al que pertenece la operación
	 */
	protected $nombreProducto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que determina si la operación tiene asociada una solicitud de comercio exterior(VUE)
	 */
	protected $idVue;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de creación de la operación
	 */
	protected $fechaCreacion;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de modificación de la operación
	 */
	protected $fechaModificacion;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador de la tabla localizacion que se llena cuando la operación tiene asociada una solicitud de comercio exterior(VUE)
	 */
	protected $idPais;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre del país que se llena cuando la operación tiene asociada una solicitud de comercio exterior(VUE)
	 */
	protected $nombrePais;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Subpartida del producto que se llena cuando la operación tiene asociada una solicitud de comercio exterior(VUE)
	 */
	protected $subpartidaProductoVue;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Código del producto que se llena cuando la operación tiene asociada una solicitud de comercio exterior(VUE)
	 */
	protected $codigoProductoVue;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha en la que se aprueba la operación
	 */
	protected $fechaAprobacion;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que agrupa operaciones registradas en un mismo sitio, area y operación
	 */
	protected $idOperadorTipoOperacion;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que registra el cambio de estado para una operación en un proceso de renovación
	 */
	protected $idHistorialOperacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que indica el estado anterior de la operación
	 */
	protected $estadoAnterior;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de finalización de vigencia del registro
	 */
	protected $fechaFinalizacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Módulo el cual ingresa la información
	 */
	protected $moduloProvee;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Observación interna que indica en el caso de alteració nde estado por parte de un requerimiento o incidencia.
	 */
	protected $observacionTecnica;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador de la tabla vigencia documentos
	 */
	protected $idVigenciaDocumento;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Indica si se ha iniciado un proceso de actualización para el registro relacionado con el id_operador_tipo_operacion
	 */
	protected $procesoModificacion;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_operadores";

	/**
	 * Nombre de la tabla: operaciones
	 */
	private $tabla = "operaciones";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_operacion";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_operadores"."operaciones_id_operacion_seq';

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
			throw new \Exception('Clase Modelo: OperacionesModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: OperacionesModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_operadores
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idOperacion
	 *
	 * Identificador único de la tabla
	 *
	 * @parámetro Integer $idOperacion
	 * @return IdOperacion
	 */
	public function setIdOperacion($idOperacion){
		$this->idOperacion = (integer) $idOperacion;
		return $this;
	}

	/**
	 * Get idOperacion
	 *
	 * @return null|Integer
	 */
	public function getIdOperacion(){
		return $this->idOperacion;
	}

	/**
	 * Set idTipoOperacion
	 *
	 * lave foranea que hace referencia al atabla de identificador de tipo de operación declarado por el operador
	 *
	 * @parámetro Integer $idTipoOperacion
	 * @return IdTipoOperacion
	 */
	public function setIdTipoOperacion($idTipoOperacion){
		$this->idTipoOperacion = (integer) $idTipoOperacion;
		return $this;
	}

	/**
	 * Get idTipoOperacion
	 *
	 * @return null|Integer
	 */
	public function getIdTipoOperacion(){
		return $this->idTipoOperacion;
	}

	/**
	 * Set identificadorOperador
	 *
	 * Identificador del operador cédula o RUC
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
	 * Set estado
	 *
	 * Estado en el que se encuentra la operación
	 *
	 * @parámetro String $estado
	 * @return Estado
	 */
	public function setEstado($estado){
		$this->estado = (string) $estado;
		return $this;
	}

	/**
	 * Get estado
	 *
	 * @return null|String
	 */
	public function getEstado(){
		return $this->estado;
	}

	/**
	 * Set observacion
	 *
	 * Campo que permite colocar una observación en la operación
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
	 * Set informe
	 *
	 * Campo no utilizado
	 *
	 * @parámetro String $informe
	 * @return Informe
	 */
	public function setInforme($informe){
		$this->informe = (string) $informe;
		return $this;
	}

	/**
	 * Get informe
	 *
	 * @return null|String
	 */
	public function getInforme(){
		return $this->informe;
	}

	/**
	 * Set idProducto
	 *
	 * Identificador de la tabla productos que determina el producto al que pertenece la operación
	 *
	 * @parámetro Integer $idProducto
	 * @return IdProducto
	 */
	public function setIdProducto($idProducto){
		$this->idProducto = (integer) $idProducto;
		return $this;
	}

	/**
	 * Get idProducto
	 *
	 * @return null|Integer
	 */
	public function getIdProducto(){
		return $this->idProducto;
	}

	/**
	 * Set nombreProducto
	 *
	 * Nombre del producto al que pertenece la operación
	 *
	 * @parámetro String $nombreProducto
	 * @return NombreProducto
	 */
	public function setNombreProducto($nombreProducto){
		$this->nombreProducto = (string) $nombreProducto;
		return $this;
	}

	/**
	 * Get nombreProducto
	 *
	 * @return null|String
	 */
	public function getNombreProducto(){
		return $this->nombreProducto;
	}

	/**
	 * Set idVue
	 *
	 * Campo que determina si la operación tiene asociada una solicitud de comercio exterior(VUE)
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
	 * Set fechaCreacion
	 *
	 * Fecha de creación de la operación
	 *
	 * @parámetro Date $fechaCreacion
	 * @return FechaCreacion
	 */
	public function setFechaCreacion($fechaCreacion){
		$this->fechaCreacion = (string) $fechaCreacion;
		return $this;
	}

	/**
	 * Get fechaCreacion
	 *
	 * @return null|Date
	 */
	public function getFechaCreacion(){
		return $this->fechaCreacion;
	}

	/**
	 * Set fechaModificacion
	 *
	 * Fecha de modificación de la operación
	 *
	 * @parámetro Date $fechaModificacion
	 * @return FechaModificacion
	 */
	public function setFechaModificacion($fechaModificacion){
		$this->fechaModificacion = (string) $fechaModificacion;
		return $this;
	}

	/**
	 * Get fechaModificacion
	 *
	 * @return null|Date
	 */
	public function getFechaModificacion(){
		return $this->fechaModificacion;
	}

	/**
	 * Set idPais
	 *
	 * Identificador de la tabla localizacion que se llena cuando la operación tiene asociada una solicitud de comercio exterior(VUE)
	 *
	 * @parámetro Integer $idPais
	 * @return IdPais
	 */
	public function setIdPais($idPais){
		$this->idPais = (integer) $idPais;
		return $this;
	}

	/**
	 * Get idPais
	 *
	 * @return null|Integer
	 */
	public function getIdPais(){
		return $this->idPais;
	}

	/**
	 * Set nombrePais
	 *
	 * Nombre del país que se llena cuando la operación tiene asociada una solicitud de comercio exterior(VUE)
	 *
	 * @parámetro String $nombrePais
	 * @return NombrePais
	 */
	public function setNombrePais($nombrePais){
		$this->nombrePais = (string) $nombrePais;
		return $this;
	}

	/**
	 * Get nombrePais
	 *
	 * @return null|String
	 */
	public function getNombrePais(){
		return $this->nombrePais;
	}

	/**
	 * Set subpartidaProductoVue
	 *
	 * Subpartida del producto que se llena cuando la operación tiene asociada una solicitud de comercio exterior(VUE)
	 *
	 * @parámetro String $subpartidaProductoVue
	 * @return SubpartidaProductoVue
	 */
	public function setSubpartidaProductoVue($subpartidaProductoVue){
		$this->subpartidaProductoVue = (string) $subpartidaProductoVue;
		return $this;
	}

	/**
	 * Get subpartidaProductoVue
	 *
	 * @return null|String
	 */
	public function getSubpartidaProductoVue(){
		return $this->subpartidaProductoVue;
	}

	/**
	 * Set codigoProductoVue
	 *
	 * Código del producto que se llena cuando la operación tiene asociada una solicitud de comercio exterior(VUE)
	 *
	 * @parámetro String $codigoProductoVue
	 * @return CodigoProductoVue
	 */
	public function setCodigoProductoVue($codigoProductoVue){
		$this->codigoProductoVue = (string) $codigoProductoVue;
		return $this;
	}

	/**
	 * Get codigoProductoVue
	 *
	 * @return null|String
	 */
	public function getCodigoProductoVue(){
		return $this->codigoProductoVue;
	}

	/**
	 * Set fechaAprobacion
	 *
	 * Fecha en la que se aprueba la operación
	 *
	 * @parámetro Date $fechaAprobacion
	 * @return FechaAprobacion
	 */
	public function setFechaAprobacion($fechaAprobacion){
		$this->fechaAprobacion = (string) $fechaAprobacion;
		return $this;
	}

	/**
	 * Get fechaAprobacion
	 *
	 * @return null|Date
	 */
	public function getFechaAprobacion(){
		return $this->fechaAprobacion;
	}

	/**
	 * Set idOperadorTipoOperacion
	 *
	 * Campo que agrupa operaciones registradas en un mismo sitio, area y operación
	 *
	 * @parámetro Integer $idOperadorTipoOperacion
	 * @return IdOperadorTipoOperacion
	 */
	public function setIdOperadorTipoOperacion($idOperadorTipoOperacion){
		$this->idOperadorTipoOperacion = (integer) $idOperadorTipoOperacion;
		return $this;
	}

	/**
	 * Get idOperadorTipoOperacion
	 *
	 * @return null|Integer
	 */
	public function getIdOperadorTipoOperacion(){
		return $this->idOperadorTipoOperacion;
	}

	/**
	 * Set idHistorialOperacion
	 *
	 * Campo que registra el cambio de estado para una operación en un proceso de renovación
	 *
	 * @parámetro Integer $idHistorialOperacion
	 * @return IdHistorialOperacion
	 */
	public function setIdHistorialOperacion($idHistorialOperacion){
		$this->idHistorialOperacion = (integer) $idHistorialOperacion;
		return $this;
	}

	/**
	 * Get idHistorialOperacion
	 *
	 * @return null|Integer
	 */
	public function getIdHistorialOperacion(){
		return $this->idHistorialOperacion;
	}

	/**
	 * Set estadoAnterior
	 *
	 * Campo que indica el estado anterior de la operación
	 *
	 * @parámetro String $estadoAnterior
	 * @return EstadoAnterior
	 */
	public function setEstadoAnterior($estadoAnterior){
		$this->estadoAnterior = (string) $estadoAnterior;
		return $this;
	}

	/**
	 * Get estadoAnterior
	 *
	 * @return null|String
	 */
	public function getEstadoAnterior(){
		return $this->estadoAnterior;
	}

	/**
	 * Set fechaFinalizacion
	 *
	 * Fecha de finalización de vigencia del registro
	 *
	 * @parámetro Date $fechaFinalizacion
	 * @return FechaFinalizacion
	 */
	public function setFechaFinalizacion($fechaFinalizacion){
		$this->fechaFinalizacion = (string) $fechaFinalizacion;
		return $this;
	}

	/**
	 * Get fechaFinalizacion
	 *
	 * @return null|Date
	 */
	public function getFechaFinalizacion(){
		return $this->fechaFinalizacion;
	}

	/**
	 * Set moduloProvee
	 *
	 * Módulo el cual ingresa la información
	 *
	 * @parámetro String $moduloProvee
	 * @return ModuloProvee
	 */
	public function setModuloProvee($moduloProvee){
		$this->moduloProvee = (string) $moduloProvee;
		return $this;
	}

	/**
	 * Get moduloProvee
	 *
	 * @return null|String
	 */
	public function getModuloProvee(){
		return $this->moduloProvee;
	}

	/**
	 * Set observacionTecnica
	 *
	 * Observación interna que indica en el caso de alteració nde estado por parte de un requerimiento o incidencia.
	 *
	 * @parámetro String $observacionTecnica
	 * @return ObservacionTecnica
	 */
	public function setObservacionTecnica($observacionTecnica){
		$this->observacionTecnica = (string) $observacionTecnica;
		return $this;
	}

	/**
	 * Get observacionTecnica
	 *
	 * @return null|String
	 */
	public function getObservacionTecnica(){
		return $this->observacionTecnica;
	}

	/**
	 * Set idVigenciaDocumento
	 *
	 * Identificador de la tabla vigencia documentos
	 *
	 * @parámetro Integer $idVigenciaDocumento
	 * @return IdVigenciaDocumento
	 */
	public function setIdVigenciaDocumento($idVigenciaDocumento){
		$this->idVigenciaDocumento = (integer) $idVigenciaDocumento;
		return $this;
	}

	/**
	 * Get idVigenciaDocumento
	 *
	 * @return null|Integer
	 */
	public function getIdVigenciaDocumento(){
		return $this->idVigenciaDocumento;
	}

	/**
	 * Set procesoModificacion
	 *
	 * Indica si se ha iniciado un proceso de actualización para el registro relacionado con el id_operador_tipo_operacion
	 *
	 * @parámetro String $procesoModificacion
	 * @return ProcesoModificacion
	 */
	public function setProcesoModificacion($procesoModificacion){
		$this->procesoModificacion = (string) $procesoModificacion;
		return $this;
	}

	/**
	 * Get procesoModificacion
	 *
	 * @return null|String
	 */
	public function getProcesoModificacion(){
		return $this->procesoModificacion;
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
	 * @return OperacionesModelo
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
