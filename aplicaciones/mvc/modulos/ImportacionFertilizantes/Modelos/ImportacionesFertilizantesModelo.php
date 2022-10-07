<?php
/**
 * Modelo ImportacionesFertilizantesModelo
 *
 * Este archivo se complementa con el archivo ImportacionesFertilizantesLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2020-02-20
 * @uses ImportacionesFertilizantesModelo
 * @package ImportacionFertilizantes
 * @subpackage Modelos
 */
namespace Agrodb\ImportacionFertilizantes\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ImportacionesFertilizantesModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador único de la tabla
	 */
	protected $idImportacionFertilizantes;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Tipo de solicitud se tiene los siguientes tipos:
	 *      Solicitud de importación de materias primas
	 *      Solicitud de importación de muestras sin valor comercial
	 *      Solicitud de importación de producto formulado
	 */
	protected $tipoSolicitud;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Finalidad por la cual se realizada la importación: Importador consumo propio, Importador
	 */
	protected $tipoOperacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Ruc o cédula del operador registrado en el sistema GUIA.
	 */
	protected $identificador;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Razón social del operador registrado en el sistema GUIA
	 */
	protected $razonSocial;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador del país de origen
	 */
	protected $idPaisOrigen;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre del país de origen.
	 */
	protected $nombrePaisOrigen;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador del país de procedencia
	 */
	protected $idPaisProcedencia;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre del país de procedencia.
	 */
	protected $nombrePaisProcedencia;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Número de factura de pedido
	 */
	protected $numeroFacturaPedido;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de creación del registro
	 */
	protected $fechaCreacion;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de inicio de vigencia
	 */
	protected $fechaInicio;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de finalización de vigencia
	 */
	protected $fechaFin;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Estado del registro
	 */
	protected $estado;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Observación emitida por el técnico en caso de realizar un proceso de subsanación
	 */
	protected $observacionTecnico;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador del técnico que realizo el proceso de revisión documental
	 */
	protected $identificadorTecnico;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Producto a formular.
	 */
	protected $productoFormular;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_importaciones_fertilizantes";

	/**
	 * Nombre de la tabla: importaciones_fertilizantes
	 */
	private $tabla = "importaciones_fertilizantes";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_importacion_fertilizantes";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_importaciones_fertilizantes"."importaciones_fertilizantes_id_importacion_fertilizantes_seq';

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
			throw new \Exception('Clase Modelo: ImportacionesFertilizantesModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: ImportacionesFertilizantesModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_importaciones_fertilizantes
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idImportacionFertilizantes
	 *
	 * Identificador único de la tabla
	 *
	 * @parámetro Integer $idImportacionFertilizantes
	 * @return IdImportacionFertilizantes
	 */
	public function setIdImportacionFertilizantes($idImportacionFertilizantes){
		$this->idImportacionFertilizantes = (integer) $idImportacionFertilizantes;
		return $this;
	}

	/**
	 * Get idImportacionFertilizantes
	 *
	 * @return null|Integer
	 */
	public function getIdImportacionFertilizantes(){
		return $this->idImportacionFertilizantes;
	}

	/**
	 * Set tipoSolicitud
	 *
	 * Tipo de solicitud se tiene los siguientes tipos:
	 * Solicitud de importación de materias primas
	 * Solicitud de importación de muestras sin valor comercial
	 * Solicitud de importación de producto formulado
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
	 * Set tipoOperacion
	 *
	 * Finalidad por la cual se realizada la importación: Importador consumo propio, Importador
	 *
	 * @parámetro String $tipoOperacion
	 * @return TipoOperacion
	 */
	public function setTipoOperacion($tipoOperacion){
		$this->tipoOperacion = (string) $tipoOperacion;
		return $this;
	}

	/**
	 * Get tipoOperacion
	 *
	 * @return null|String
	 */
	public function getTipoOperacion(){
		return $this->tipoOperacion;
	}

	/**
	 * Set identificador
	 *
	 * Ruc o cédula del operador registrado en el sistema GUIA.
	 *
	 * @parámetro String $identificador
	 * @return Identificador
	 */
	public function setIdentificador($identificador){
		$this->identificador = (string) $identificador;
		return $this;
	}

	/**
	 * Get identificador
	 *
	 * @return null|String
	 */
	public function getIdentificador(){
		return $this->identificador;
	}

	/**
	 * Set razonSocial
	 *
	 * Razón social del operador registrado en el sistema GUIA
	 *
	 * @parámetro String $razonSocial
	 * @return RazonSocial
	 */
	public function setRazonSocial($razonSocial){
		$this->razonSocial = (string) $razonSocial;
		return $this;
	}

	/**
	 * Get razonSocial
	 *
	 * @return null|String
	 */
	public function getRazonSocial(){
		return $this->razonSocial;
	}

	/**
	 * Set idPaisOrigen
	 *
	 * Identificador del país de origen
	 *
	 * @parámetro Integer $idPaisOrigen
	 * @return IdPaisOrigen
	 */
	public function setIdPaisOrigen($idPaisOrigen){
		$this->idPaisOrigen = (integer) $idPaisOrigen;
		return $this;
	}

	/**
	 * Get idPaisOrigen
	 *
	 * @return null|Integer
	 */
	public function getIdPaisOrigen(){
		return $this->idPaisOrigen;
	}

	/**
	 * Set nombrePaisOrigen
	 *
	 * Nombre del país de origen.
	 *
	 * @parámetro String $nombrePaisOrigen
	 * @return NombrePaisOrigen
	 */
	public function setNombrePaisOrigen($nombrePaisOrigen){
		$this->nombrePaisOrigen = (string) $nombrePaisOrigen;
		return $this;
	}

	/**
	 * Get nombrePaisOrigen
	 *
	 * @return null|String
	 */
	public function getNombrePaisOrigen(){
		return $this->nombrePaisOrigen;
	}

	/**
	 * Set idPaisProcedencia
	 *
	 * Identificador del país de procedencia
	 *
	 * @parámetro Integer $idPaisProcedencia
	 * @return IdPaisProcedencia
	 */
	public function setIdPaisProcedencia($idPaisProcedencia){
		$this->idPaisProcedencia = (integer) $idPaisProcedencia;
		return $this;
	}

	/**
	 * Get idPaisProcedencia
	 *
	 * @return null|Integer
	 */
	public function getIdPaisProcedencia(){
		return $this->idPaisProcedencia;
	}

	/**
	 * Set nombrePaisProcedencia
	 *
	 * Nombre del país de procedencia.
	 *
	 * @parámetro String $nombrePaisProcedencia
	 * @return NombrePaisProcedencia
	 */
	public function setNombrePaisProcedencia($nombrePaisProcedencia){
		$this->nombrePaisProcedencia = (string) $nombrePaisProcedencia;
		return $this;
	}

	/**
	 * Get nombrePaisProcedencia
	 *
	 * @return null|String
	 */
	public function getNombrePaisProcedencia(){
		return $this->nombrePaisProcedencia;
	}

	/**
	 * Set numeroFacturaPedido
	 *
	 * Número de factura de pedido
	 *
	 * @parámetro String $numeroFacturaPedido
	 * @return NumeroFacturaPedido
	 */
	public function setNumeroFacturaPedido($numeroFacturaPedido){
		$this->numeroFacturaPedido = (string) $numeroFacturaPedido;
		return $this;
	}

	/**
	 * Get numeroFacturaPedido
	 *
	 * @return null|String
	 */
	public function getNumeroFacturaPedido(){
		return $this->numeroFacturaPedido;
	}

	/**
	 * Set fechaCreacion
	 *
	 * Fecha de creación del registro
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
	 * Set fechaInicio
	 *
	 * Fecha de inicio de vigencia
	 *
	 * @parámetro Date $fechaInicio
	 * @return FechaInicio
	 */
	public function setFechaInicio($fechaInicio){
		$this->fechaInicio = (string) $fechaInicio;
		return $this;
	}

	/**
	 * Get fechaInicio
	 *
	 * @return null|Date
	 */
	public function getFechaInicio(){
		return $this->fechaInicio;
	}

	/**
	 * Set fechaFin
	 *
	 * Fecha de finalización de vigencia
	 *
	 * @parámetro Date $fechaFin
	 * @return FechaFin
	 */
	public function setFechaFin($fechaFin){
		$this->fechaFin = (string) $fechaFin;
		return $this;
	}

	/**
	 * Get fechaFin
	 *
	 * @return null|Date
	 */
	public function getFechaFin(){
		return $this->fechaFin;
	}

	/**
	 * Set estado
	 *
	 * Estado del registro
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
	 * Set observacionTecnico
	 *
	 * Observación emitida por el técnico en caso de realizar un proceso de subsanación
	 *
	 * @parámetro String $observacionTecnico
	 * @return ObservacionTecnico
	 */
	public function setObservacionTecnico($observacionTecnico){
		$this->observacionTecnico = (string) $observacionTecnico;
		return $this;
	}

	/**
	 * Get observacionTecnico
	 *
	 * @return null|String
	 */
	public function getObservacionTecnico(){
		return $this->observacionTecnico;
	}

	/**
	 * Set identificadorTecnico
	 *
	 * Identificador del técnico que realizo el proceso de revisión documental
	 *
	 * @parámetro String $identificadorTecnico
	 * @return IdentificadorTecnico
	 */
	public function setIdentificadorTecnico($identificadorTecnico){
		$this->identificadorTecnico = (string) $identificadorTecnico;
		return $this;
	}

	/**
	 * Get identificadorTecnico
	 *
	 * @return null|String
	 */
	public function getIdentificadorTecnico(){
		return $this->identificadorTecnico;
	}

	/**
	 * Set productoFormular
	 *
	 * Producto a formular.
	 *
	 * @parámetro String $productoFormular
	 * @return ProductoFormular
	 */
	public function setProductoFormular($productoFormular){
		$this->productoFormular = (string) $productoFormular;
		return $this;
	}

	/**
	 * Get productoFormular
	 *
	 * @return null|String
	 */
	public function getProductoFormular(){
		return $this->productoFormular;
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
	 * @return ImportacionesFertilizantesModelo
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
