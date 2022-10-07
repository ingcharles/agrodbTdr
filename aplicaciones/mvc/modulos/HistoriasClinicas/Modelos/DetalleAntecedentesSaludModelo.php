<?php
/**
 * Modelo DetalleAntecedentesSaludModelo
 *
 * Este archivo se complementa con el archivo DetalleAntecedentesSaludLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses DetalleAntecedentesSaludModelo
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class DetalleAntecedentesSaludModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave primaria de la tabla
	 */
	protected $idDetalleAntecedentesSalud;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave foránea de la tabla antecedentes_salud
	 */
	protected $idAntecedentesSalud;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave foránea de la tabla cie_10
	 */
	protected $idCie;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Diagnostico
	 */
	protected $diagnostico;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Observaciones de los detalles de antecedentes de salud
	 */
	protected $observaciones;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Ciclo Mestrual
	 */
	protected $cicloMestrual;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de ultima regla
	 */
	protected $fechaUltimaRegla;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de ultima citologia
	 */
	protected $fechaUltimaCitologia;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Resultado de citologia
	 */
	protected $resultadoCitologia;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Número de gestaciones
	 */
	protected $numeroGestaciones;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Número de partos
	 */
	protected $numeroPartos;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Número de cesareas
	 */
	protected $numeroCesareas;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Número de abortos
	 */
	protected $numeroAbortos;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Número de hijos vivos
	 */
	protected $numeroHijosVivos;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Número de hijos muertos
	 */
	protected $numeroHijosMuertos;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Embarazo
	 */
	protected $embarazo;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Semanas de gestación
	 */
	protected $semanasGestacion;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Número de ecos
	 */
	protected $numeroEcos;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Número de controles de embarazo
	 */
	protected $numeroControlesEmbarazo;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Complicaciones en el embarazo
	 */
	protected $complicaciones;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Vida sexual activa
	 */
	protected $vidaSexualActiva;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Planificación familiar
	 */
	protected $planificacionFamiliar;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Método de planificación
	 */
	protected $metodoPlanificacion;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de creación del registro
	 */
	protected $fechaCreacion;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_historias_clinicas";

	/**
	 * Nombre de la tabla: detalle_antecedentes_salud
	 */
	private $tabla = "detalle_antecedentes_salud";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_detalle_antecedentes_salud";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_historias_clinicas"."detalle_antecedentes_salud_id_detalle_antecedentes_salud_seq';

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
			throw new \Exception('Clase Modelo: DetalleAntecedentesSaludModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: DetalleAntecedentesSaludModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_historias_clinicas
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idDetalleAntecedentesSalud
	 *
	 * Llave primaria de la tabla
	 *
	 * @parámetro Integer $idDetalleAntecedentesSalud
	 * @return IdDetalleAntecedentesSalud
	 */
	public function setIdDetalleAntecedentesSalud($idDetalleAntecedentesSalud){
		$this->idDetalleAntecedentesSalud = (integer) $idDetalleAntecedentesSalud;
		return $this;
	}

	/**
	 * Get idDetalleAntecedentesSalud
	 *
	 * @return null|Integer
	 */
	public function getIdDetalleAntecedentesSalud(){
		return $this->idDetalleAntecedentesSalud;
	}

	/**
	 * Set idAntecedentesSalud
	 *
	 * Llave foránea de la tabla antecedentes_salud
	 *
	 * @parámetro Integer $idAntecedentesSalud
	 * @return IdAntecedentesSalud
	 */
	public function setIdAntecedentesSalud($idAntecedentesSalud){
		$this->idAntecedentesSalud = (integer) $idAntecedentesSalud;
		return $this;
	}

	/**
	 * Get idAntecedentesSalud
	 *
	 * @return null|Integer
	 */
	public function getIdAntecedentesSalud(){
		return $this->idAntecedentesSalud;
	}

	/**
	 * Set idCie
	 *
	 * Llave foránea de la tabla cie
	 *
	 * @parámetro Integer $idCie
	 * @return IdCie
	 */
	public function setIdCie($idCie){
		$this->idCie = (integer) $idCie;
		return $this;
	}

	/**
	 * Get idCie
	 *
	 * @return null|Integer
	 */
	public function getIdCie(){
		return $this->idCie;
	}

	/**
	 * Set diagnostico
	 *
	 * Diagnostico
	 *
	 * @parámetro String $diagnostisco
	 * @return Diagnostico
	 */
	public function setDiagnostico($diagnostico){
		$this->diagnostico = (string) $diagnostico;
		return $this;
	}

	/**
	 * Get diagnostico
	 *
	 * @return null|String
	 */
	public function getDiagnostico(){
		return $this->diagnostico;
	}

	/**
	 * Set observaciones
	 *
	 * Observaciones de los detalles de antecedentes de salud
	 *
	 * @parámetro String $observaciones
	 * @return Observaciones
	 */
	public function setObservaciones($observaciones){
		$this->observaciones = (string) $observaciones;
		return $this;
	}

	/**
	 * Get observaciones
	 *
	 * @return null|String
	 */
	public function getObservaciones(){
		return $this->observaciones;
	}

	/**
	 * Set cicloMestrual
	 *
	 * Ciclo Mestrual
	 *
	 * @parámetro String $cicloMestrual
	 * @return CicloMestrual
	 */
	public function setCicloMestrual($cicloMestrual){
		$this->cicloMestrual = (string) $cicloMestrual;
		return $this;
	}

	/**
	 * Get cicloMestrual
	 *
	 * @return null|String
	 */
	public function getCicloMestrual(){
		return $this->cicloMestrual;
	}

	/**
	 * Set fechaUltimaRegla
	 *
	 * Fecha de ultima regla
	 *
	 * @parámetro Date $fechaUltimaRegla
	 * @return FechaUltimaRegla
	 */
	public function setFechaUltimaRegla($fechaUltimaRegla){
		$this->fechaUltimaRegla = (string) $fechaUltimaRegla;
		return $this;
	}

	/**
	 * Get fechaUltimaRegla
	 *
	 * @return null|Date
	 */
	public function getFechaUltimaRegla(){
		return $this->fechaUltimaRegla;
	}

	/**
	 * Set fechaUltimaCitologia
	 *
	 * Fecha de ultima citologia
	 *
	 * @parámetro Date $fechaUltimaCitologia
	 * @return FechaUltimaCitologia
	 */
	public function setFechaUltimaCitologia($fechaUltimaCitologia){
		$this->fechaUltimaCitologia = (string) $fechaUltimaCitologia;
		return $this;
	}

	/**
	 * Get fechaUltimaCitologia
	 *
	 * @return null|Date
	 */
	public function getFechaUltimaCitologia(){
		return $this->fechaUltimaCitologia;
	}

	/**
	 * Set resultadoCitologia
	 *
	 * Resultado de citologia
	 *
	 * @parámetro String $resultadoCitologia
	 * @return ResultadoCitologia
	 */
	public function setResultadoCitologia($resultadoCitologia){
		$this->resultadoCitologia = (string) $resultadoCitologia;
		return $this;
	}

	/**
	 * Get resultadoCitologia
	 *
	 * @return null|String
	 */
	public function getResultadoCitologia(){
		return $this->resultadoCitologia;
	}

	/**
	 * Set numeroGestaciones
	 *
	 * Número de gestaciones
	 *
	 * @parámetro Integer $numeroGestaciones
	 * @return NumeroGestaciones
	 */
	public function setNumeroGestaciones($numeroGestaciones){
		$this->numeroGestaciones = (integer) $numeroGestaciones;
		return $this;
	}

	/**
	 * Get numeroGestaciones
	 *
	 * @return null|Integer
	 */
	public function getNumeroGestaciones(){
		return $this->numeroGestaciones;
	}

	/**
	 * Set numeroPartos
	 *
	 * Número de partos
	 *
	 * @parámetro Integer $numeroPartos
	 * @return NumeroPartos
	 */
	public function setNumeroPartos($numeroPartos){
		$this->numeroPartos = (integer) $numeroPartos;
		return $this;
	}

	/**
	 * Get numeroPartos
	 *
	 * @return null|Integer
	 */
	public function getNumeroPartos(){
		return $this->numeroPartos;
	}

	/**
	 * Set numeroCesareas
	 *
	 * Número de cesareas
	 *
	 * @parámetro Integer $numeroCesareas
	 * @return NumeroCesareas
	 */
	public function setNumeroCesareas($numeroCesareas){
		$this->numeroCesareas = (integer) $numeroCesareas;
		return $this;
	}

	/**
	 * Get numeroCesareas
	 *
	 * @return null|Integer
	 */
	public function getNumeroCesareas(){
		return $this->numeroCesareas;
	}

	/**
	 * Set numeroAbortos
	 *
	 * Número de abortos
	 *
	 * @parámetro Integer $numeroAbortos
	 * @return NumeroAbortos
	 */
	public function setNumeroAbortos($numeroAbortos){
		$this->numeroAbortos = (integer) $numeroAbortos;
		return $this;
	}

	/**
	 * Get numeroAbortos
	 *
	 * @return null|Integer
	 */
	public function getNumeroAbortos(){
		return $this->numeroAbortos;
	}

	/**
	 * Set numeroHijosVivos
	 *
	 * Número de hijos vivos
	 *
	 * @parámetro Integer $numeroHijosVivos
	 * @return NumeroHijosVivos
	 */
	public function setNumeroHijosVivos($numeroHijosVivos){
		$this->numeroHijosVivos = (integer) $numeroHijosVivos;
		return $this;
	}

	/**
	 * Get numeroHijosVivos
	 *
	 * @return null|Integer
	 */
	public function getNumeroHijosVivos(){
		return $this->numeroHijosVivos;
	}

	/**
	 * Set numeroHijosMuertos
	 *
	 * Número de hijos muertos
	 *
	 * @parámetro Integer $numeroHijosMuertos
	 * @return NumeroHijosMuertos
	 */
	public function setNumeroHijosMuertos($numeroHijosMuertos){
		$this->numeroHijosMuertos = (integer) $numeroHijosMuertos;
		return $this;
	}

	/**
	 * Get numeroHijosMuertos
	 *
	 * @return null|Integer
	 */
	public function getNumeroHijosMuertos(){
		return $this->numeroHijosMuertos;
	}

	/**
	 * Set embarazo
	 *
	 * Embarazo
	 *
	 * @parámetro String $embarazo
	 * @return Embarazo
	 */
	public function setEmbarazo($embarazo){
		$this->embarazo = (string) $embarazo;
		return $this;
	}

	/**
	 * Get embarazo
	 *
	 * @return null|String
	 */
	public function getEmbarazo(){
		return $this->embarazo;
	}

	/**
	 * Set semanasGestacion
	 *
	 * Semanas de gestación
	 *
	 * @parámetro Integer $semanasGestacion
	 * @return SemanasGestacion
	 */
	public function setSemanasGestacion($semanasGestacion){
		$this->semanasGestacion = (integer) $semanasGestacion;
		return $this;
	}

	/**
	 * Get semanasGestacion
	 *
	 * @return null|Integer
	 */
	public function getSemanasGestacion(){
		return $this->semanasGestacion;
	}

	/**
	 * Set numeroEcos
	 *
	 * Número de ecos
	 *
	 * @parámetro Integer $numeroEcos
	 * @return NumeroEcos
	 */
	public function setNumeroEcos($numeroEcos){
		$this->numeroEcos = (integer) $numeroEcos;
		return $this;
	}

	/**
	 * Get numeroEcos
	 *
	 * @return null|Integer
	 */
	public function getNumeroEcos(){
		return $this->numeroEcos;
	}

	/**
	 * Set numeroControlesEmbarazo
	 *
	 * Número de controles de embarazo
	 *
	 * @parámetro Integer $numeroControlesEmbarazo
	 * @return NumeroControlesEmbarazo
	 */
	public function setNumeroControlesEmbarazo($numeroControlesEmbarazo){
		$this->numeroControlesEmbarazo = (integer) $numeroControlesEmbarazo;
		return $this;
	}

	/**
	 * Get numeroControlesEmbarazo
	 *
	 * @return null|Integer
	 */
	public function getNumeroControlesEmbarazo(){
		return $this->numeroControlesEmbarazo;
	}

	/**
	 * Set complicaciones
	 *
	 * Complicaciones en el embarazo
	 *
	 * @parámetro String $complicaciones
	 * @return Complicaciones
	 */
	public function setComplicaciones($complicaciones){
		$this->complicaciones = (string) $complicaciones;
		return $this;
	}

	/**
	 * Get complicaciones
	 *
	 * @return null|String
	 */
	public function getComplicaciones(){
		return $this->complicaciones;
	}

	/**
	 * Set vidaSexualActiva
	 *
	 * Vida sexual activa
	 *
	 * @parámetro String $vidaSexualActiva
	 * @return VidaSexualActiva
	 */
	public function setVidaSexualActiva($vidaSexualActiva){
		$this->vidaSexualActiva = (string) $vidaSexualActiva;
		return $this;
	}

	/**
	 * Get vidaSexualActiva
	 *
	 * @return null|String
	 */
	public function getVidaSexualActiva(){
		return $this->vidaSexualActiva;
	}

	/**
	 * Set planificacionFamiliar
	 *
	 * Planificación familiar
	 *
	 * @parámetro String $planificacionFamiliar
	 * @return PlanificacionFamiliar
	 */
	public function setPlanificacionFamiliar($planificacionFamiliar){
		$this->planificacionFamiliar = (string) $planificacionFamiliar;
		return $this;
	}

	/**
	 * Get planificacionFamiliar
	 *
	 * @return null|String
	 */
	public function getPlanificacionFamiliar(){
		return $this->planificacionFamiliar;
	}

	/**
	 * Set metodoPlanificacion
	 *
	 * Método de planificación
	 *
	 * @parámetro String $metodoPlanificacion
	 * @return MetodoPlanificacion
	 */
	public function setMetodoPlanificacion($metodoPlanificacion){
		$this->metodoPlanificacion = (string) $metodoPlanificacion;
		return $this;
	}

	/**
	 * Get metodoPlanificacion
	 *
	 * @return null|String
	 */
	public function getMetodoPlanificacion(){
		return $this->metodoPlanificacion;
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

	public function borrarPorParametro($param, $value){
		return parent::borrar($param . " = " . $value);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return DetalleAntecedentesSaludModelo
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
