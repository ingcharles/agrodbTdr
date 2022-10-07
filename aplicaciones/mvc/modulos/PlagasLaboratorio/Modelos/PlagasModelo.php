<?php
/**
 * Modelo PlagasModelo
 *
 * Este archivo se complementa con el archivo PlagasLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-03-25
 * @uses PlagasModelo
 * @package PlagasLaboratorio
 * @subpackage Modelos
 */
namespace Agrodb\PlagasLaboratorio\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class PlagasModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador unico de la tabla
	 */
	protected $idPlaga;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave fonarea de la tabla g_plagas_laboratorio.cultivos
	 */
	protected $idCultivo;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre de la familia a la cual pertenece la plaga
	 */
	protected $familia;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre científico de la plaga
	 */
	protected $nombreCientifico;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre del técnico que identifica por primera vez la plaga
	 */
	protected $identificadoPor;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Número del primer informe en donde se detectó la plaga
	 */
	protected $numeroPrimerInforme;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Determinar si se dispone o no del espécimen de la plaga, se debe cargar un catálogo con dos ítems SI/NO
	 */
	protected $especimen;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Lugar en donde se encuentra el espécimen. Si escoge Si en el campo anterior el campo es obligatorio, si escoge no, será opcional.
	 */
	protected $ubicacionEspecimen;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Se ingresará el nombre del taxónomo que confirma el diagnóstico del espécimen
	 */
	protected $confirmacionDiagnostico;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo para ingresar una observación
	 */
	protected $observacion;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de creación del registro
	 */
	protected $fechaCreacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador del usuario que crea el registro
	 */
	protected $identificacionCreacion;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de modificación del registro
	 */
	protected $fechaModificacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Usuario que modifica el registro
	 */
	protected $identificacionModificacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Estado del registro
	 */
	protected $estado;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador de la tabla g_catalogos.localizacion
	 */
	protected $idProvinciaPlaga;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre de la provincia
	 */
	protected $nombreProvinciaPlaga;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_plagas_laboratorio";

	/**
	 * Nombre de la tabla: plagas
	 */
	private $tabla = "plagas";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_plaga";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_plagas_laboratorio"."plagas_id_plaga_seq';

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
			throw new \Exception('Clase Modelo: PlagasModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: PlagasModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_plagas_laboratorio
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idPlaga
	 *
	 * Identificador unico de la tabla
	 *
	 * @parámetro Integer $idPlaga
	 * @return IdPlaga
	 */
	public function setIdPlaga($idPlaga){
		$this->idPlaga = (integer) $idPlaga;
		return $this;
	}

	/**
	 * Get idPlaga
	 *
	 * @return null|Integer
	 */
	public function getIdPlaga(){
		return $this->idPlaga;
	}

	/**
	 * Set idCultivo
	 *
	 * Llave fonarea de la tabla g_plagas_laboratorio.cultivos
	 *
	 * @parámetro Integer $idCultivo
	 * @return IdCultivo
	 */
	public function setIdCultivo($idCultivo){
		$this->idCultivo = (integer) $idCultivo;
		return $this;
	}

	/**
	 * Get idCultivo
	 *
	 * @return null|Integer
	 */
	public function getIdCultivo(){
		return $this->idCultivo;
	}

	/**
	 * Set familia
	 *
	 * Nombre de la familia a la cual pertenece la plaga
	 *
	 * @parámetro String $familia
	 * @return Familia
	 */
	public function setFamilia($familia){
		$this->familia = (string) $familia;
		return $this;
	}

	/**
	 * Get familia
	 *
	 * @return null|String
	 */
	public function getFamilia(){
		return $this->familia;
	}

	/**
	 * Set nombreCientifico
	 *
	 * Nombre científico de la plaga
	 *
	 * @parámetro String $nombreCientifico
	 * @return NombreCientifico
	 */
	public function setNombreCientifico($nombreCientifico){
		$this->nombreCientifico = (string) $nombreCientifico;
		return $this;
	}

	/**
	 * Get nombreCientifico
	 *
	 * @return null|String
	 */
	public function getNombreCientifico(){
		return $this->nombreCientifico;
	}

	/**
	 * Set identificadoPor
	 *
	 * Nombre del técnico que identifica por primera vez la plaga
	 *
	 * @parámetro String $identificadoPor
	 * @return IdentificadoPor
	 */
	public function setIdentificadoPor($identificadoPor){
		$this->identificadoPor = (string) $identificadoPor;
		return $this;
	}

	/**
	 * Get identificadoPor
	 *
	 * @return null|String
	 */
	public function getIdentificadoPor(){
		return $this->identificadoPor;
	}

	/**
	 * Set numeroPrimerInforme
	 *
	 * Número del primer informe en donde se detectó la plaga
	 *
	 * @parámetro String $numeroPrimerInforme
	 * @return NumeroPrimerInforme
	 */
	public function setNumeroPrimerInforme($numeroPrimerInforme){
		$this->numeroPrimerInforme = (string) $numeroPrimerInforme;
		return $this;
	}

	/**
	 * Get numeroPrimerInforme
	 *
	 * @return null|String
	 */
	public function getNumeroPrimerInforme(){
		return $this->numeroPrimerInforme;
	}

	/**
	 * Set especimen
	 *
	 * Determinar si se dispone o no del espécimen de la plaga, se debe cargar un catálogo con dos ítems SI/NO
	 *
	 * @parámetro String $especimen
	 * @return Especimen
	 */
	public function setEspecimen($especimen){
		$this->especimen = (string) $especimen;
		return $this;
	}

	/**
	 * Get especimen
	 *
	 * @return null|String
	 */
	public function getEspecimen(){
		return $this->especimen;
	}

	/**
	 * Set ubicacionEspecimen
	 *
	 * Lugar en donde se encuentra el espécimen. Si escoge Si en el campo anterior el campo es obligatorio, si escoge no, será opcional.
	 *
	 * @parámetro String $ubicacionEspecimen
	 * @return UbicacionEspecimen
	 */
	public function setUbicacionEspecimen($ubicacionEspecimen){
		$this->ubicacionEspecimen = (string) $ubicacionEspecimen;
		return $this;
	}

	/**
	 * Get ubicacionEspecimen
	 *
	 * @return null|String
	 */
	public function getUbicacionEspecimen(){
		return $this->ubicacionEspecimen;
	}

	/**
	 * Set confirmacionDiagnostico
	 *
	 * Se ingresará el nombre del taxónomo que confirma el diagnóstico del espécimen
	 *
	 * @parámetro String $confirmacionDiagnostico
	 * @return ConfirmacionDiagnostico
	 */
	public function setConfirmacionDiagnostico($confirmacionDiagnostico){
		$this->confirmacionDiagnostico = (string) $confirmacionDiagnostico;
		return $this;
	}

	/**
	 * Get confirmacionDiagnostico
	 *
	 * @return null|String
	 */
	public function getConfirmacionDiagnostico(){
		return $this->confirmacionDiagnostico;
	}

	/**
	 * Set observacion
	 *
	 * Campo para ingresar una observación
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
	 * Set identificacionCreacion
	 *
	 * Identificador del usuario que crea el registro
	 *
	 * @parámetro String $identificacionCreacion
	 * @return IdentificacionCreacion
	 */
	public function setIdentificacionCreacion($identificacionCreacion){
		$this->identificacionCreacion = (string) $identificacionCreacion;
		return $this;
	}

	/**
	 * Get identificacionCreacion
	 *
	 * @return null|String
	 */
	public function getIdentificacionCreacion(){
		return $this->identificacionCreacion;
	}

	/**
	 * Set fechaModificacion
	 *
	 * Fecha de modificación del registro
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
	 * Set identificacionModificacion
	 *
	 * Usuario que modifica el registro
	 *
	 * @parámetro String $identificacionModificacion
	 * @return IdentificacionModificacion
	 */
	public function setIdentificacionModificacion($identificacionModificacion){
		$this->identificacionModificacion = (string) $identificacionModificacion;
		return $this;
	}

	/**
	 * Get identificacionModificacion
	 *
	 * @return null|String
	 */
	public function getIdentificacionModificacion(){
		return $this->identificacionModificacion;
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
	 * Set idProvinciaPlaga
	 *
	 * Identificador de la tabla g_catalogos.localizacion
	 *
	 * @parámetro Integer $idProvinciaPlaga
	 * @return IdProvinciaPlaga
	 */
	public function setIdProvinciaPlaga($idProvinciaPlaga){
		$this->idProvinciaPlaga = (integer) $idProvinciaPlaga;
		return $this;
	}

	/**
	 * Get idProvinciaPlaga
	 *
	 * @return null|Integer
	 */
	public function getIdProvinciaPlaga(){
		return $this->idProvinciaPlaga;
	}

	/**
	 * Set nombreProvinciaPlaga
	 *
	 * Nombre de la provincia
	 *
	 * @parámetro String $nombreProvinciaPlaga
	 * @return NombreProvinciaPlaga
	 */
	public function setNombreProvinciaPlaga($nombreProvinciaPlaga){
		$this->nombreProvinciaPlaga = (string) $nombreProvinciaPlaga;
		return $this;
	}

	/**
	 * Get nombreProvinciaPlaga
	 *
	 * @return null|String
	 */
	public function getNombreProvinciaPlaga(){
		return $this->nombreProvinciaPlaga;
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
	 * @return PlagasModelo
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
