<?php
/**
 * Modelo RevisionOrganosSistemasModelo
 *
 * Este archivo se complementa con el archivo RevisionOrganosSistemasLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses RevisionOrganosSistemasModelo
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;
use PhpOffice\PhpSpreadsheet\Shared\StringHelper;

class RevisionOrganosSistemasModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave primaria de la tabla
	 */
	protected $idRevisionOrganosSistemas;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave foránea de la tabla historia_clinica
	 */
	protected $idHistoriaClinica;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave foránea de la tabla procedimiento_medico
	 */
	protected $idProcedimientoMedico;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave foránea de la tabla
	 */
	protected $idTipoProcedimientoMedico;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de creación del registro
	 */
	protected $fechaCreacion;

	/**
	 *
	 * @var string Campo
	 *      Campo visible en el formulario
	 *      Observaciones del registro
	 */
	protected $observaciones;

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
	 * Nombre de la tabla: revision_organos_sistemas
	 */
	private $tabla = "revision_organos_sistemas";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_revision_organos_sistemas";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_historias_clinicas"."revision_organos_sistemas_id_revision_organos_sistemas_seq';

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
			throw new \Exception('Clase Modelo: RevisionOrganosSistemasModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: RevisionOrganosSistemasModelo. Propiedad especificada invalida: get' . $name);
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
	 * Set idRevisionOrganosSistemas
	 *
	 * Llave primaria de la tabla
	 *
	 * @parámetro Integer $idRevisionOrganosSistemas
	 * @return IdRevisionOrganosSistemas
	 */
	public function setIdRevisionOrganosSistemas($idRevisionOrganosSistemas){
		$this->idRevisionOrganosSistemas = (integer) $idRevisionOrganosSistemas;
		return $this;
	}

	/**
	 * Get idRevisionOrganosSistemas
	 *
	 * @return null|Integer
	 */
	public function getIdRevisionOrganosSistemas(){
		return $this->idRevisionOrganosSistemas;
	}

	/**
	 * Set idHistoriaClinica
	 *
	 * Llave foránea de la tabla historia_clinica
	 *
	 * @parámetro Integer $idHistoriaClinica
	 * @return IdHistoriaClinica
	 */
	public function setIdHistoriaClinica($idHistoriaClinica){
		$this->idHistoriaClinica = (integer) $idHistoriaClinica;
		return $this;
	}

	/**
	 * Get idHistoriaClinica
	 *
	 * @return null|Integer
	 */
	public function getIdHistoriaClinica(){
		return $this->idHistoriaClinica;
	}

	/**
	 * Set idProcedimientoMedico
	 *
	 * Llave foránea de la tabla procedimiento_medico
	 *
	 * @parámetro Integer $idProcedimientoMedico
	 * @return IdProcedimientoMedico
	 */
	public function setIdProcedimientoMedico($idProcedimientoMedico){
		$this->idProcedimientoMedico = (integer) $idProcedimientoMedico;
		return $this;
	}

	/**
	 * Get idProcedimientoMedico
	 *
	 * @return null|Integer
	 */
	public function getIdProcedimientoMedico(){
		return $this->idProcedimientoMedico;
	}

	/**
	 * Set idTipoProcedimientoMedico
	 *
	 * Llave foránea de la tabla
	 *
	 * @parámetro Integer $idTipoProcedimientoMedico
	 * @return IdTipoProcedimientoMedico
	 */
	public function setIdTipoProcedimientoMedico($idTipoProcedimientoMedico){
		$this->idTipoProcedimientoMedico = (integer) $idTipoProcedimientoMedico;
		return $this;
	}

	/**
	 * Get idTipoProcedimientoMedico
	 *
	 * @return null|Integer
	 */
	public function getIdTipoProcedimientoMedico(){
		return $this->idTipoProcedimientoMedico;
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
	 * Set Observaciones
	 *
	 * Observaciones del registro
	 *
	 * @parámetro Date Observaciones
	 */
	public function setObservaciones($observaciones){
		$this->observaciones = (string) $observaciones;
		return $this;
	}

	/**
	 * Get Observaciones
	 *
	 * @return null|String
	 */
	public function getObservaciones(){
		return $this->observaciones;
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
	 * @return RevisionOrganosSistemasModelo
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
