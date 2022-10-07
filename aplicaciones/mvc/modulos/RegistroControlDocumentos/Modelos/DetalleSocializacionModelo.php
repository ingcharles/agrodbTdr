<?php
/**
 * Modelo DetalleSocializacionModelo
 *
 * Este archivo se complementa con el archivo DetalleSocializacionLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-10-18
 * @uses DetalleSocializacionModelo
 * @package RegistroControlDocumentos
 * @subpackage Modelos
 */
namespace Agrodb\RegistroControlDocumentos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class DetalleSocializacionModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave primaria de la tabla
	 */
	protected $idDetalleSocializacion;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave foránea de la tabla registro_sgc
	 */
	protected $idDetalleDestinatario;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Estado del registro de socialización
	 */
	protected $estadoSocializar;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha socializacion
	 */
	protected $fechaSocializacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre socializar
	 */
	protected $nombreSocializar;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Provincia socializar
	 */
	protected $provincia;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Oficina
	 */
	protected $oficina;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Coordinación
	 */
	protected $coordinacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Dirección
	 */
	protected $direccion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Documento socializar
	 */
	protected $documentoSocializar;

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
	 *      Estado del registro
	 */
	protected $estado;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador asignante
	 */
	protected $identifcadorAsignante;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave foránea de la tabla tecnico
	 */
	protected $idTecnico;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_registro_control_documentos";

	/**
	 * Nombre de la tabla: detalle_socializacion
	 */
	private $tabla = "detalle_socializacion";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_detalle_socializacion";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_registro_control_documentos"."detalle_socializacion_id_detalle_socializacion_seq';

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
			throw new \Exception('Clase Modelo: DetalleSocializacionModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: DetalleSocializacionModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_registro_control_documentos
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idDetalleSocializacion
	 *
	 * Llave primaria de la tabla
	 *
	 * @parámetro Integer $idDetalleSocializacion
	 * @return IdDetalleSocializacion
	 */
	public function setIdDetalleSocializacion($idDetalleSocializacion){
		$this->idDetalleSocializacion = (integer) $idDetalleSocializacion;
		return $this;
	}

	/**
	 * Get idDetalleSocializacion
	 *
	 * @return null|Integer
	 */
	public function getIdDetalleSocializacion(){
		return $this->idDetalleSocializacion;
	}

	/**
	 * Set IdDetalleDestinatario
	 *
	 * Llave foránea de la tabla registro_sgc
	 *
	 * @parámetro Integer $idRegistroSgc
	 * @return IdDetalleDestinatario
	 */
	public function setIdDetalleDestinatario($idDetalleDestinatario){
		$this->idDetalleDestinatario = (integer) $idDetalleDestinatario;
		return $this;
	}

	/**
	 * Get idDetalleDestinatario
	 *
	 * @return null|Integer
	 */
	public function getIdDetalleDestinatario(){
		return $this->idDetalleDestinatario;
	}

	/**
	 * Set estadoSocializar
	 *
	 * Estado del registro de socialización
	 *
	 * @parámetro String $estadoSocializar
	 * @return EstadoSocializar
	 */
	public function setEstadoSocializar($estadoSocializar){
		$this->estadoSocializar = (string) $estadoSocializar;
		return $this;
	}

	/**
	 * Get estadoSocializar
	 *
	 * @return null|String
	 */
	public function getEstadoSocializar(){
		return $this->estadoSocializar;
	}

	/**
	 * Set fechaSocializacion
	 *
	 * Fecha socializacion
	 *
	 * @parámetro Date $fechaSocializacion
	 * @return FechaSocializacion
	 */
	public function setFechaSocializacion($fechaSocializacion){
		$this->fechaSocializacion = (string) $fechaSocializacion;
		return $this;
	}

	/**
	 * Get fechaSocializacion
	 *
	 * @return null|Date
	 */
	public function getFechaSocializacion(){
		return $this->fechaSocializacion;
	}

	/**
	 * Set nombreSocializar
	 *
	 * Nombre socializar
	 *
	 * @parámetro String $nombreSocializar
	 * @return NombreSocializar
	 */
	public function setNombreSocializar($nombreSocializar){
		$this->nombreSocializar = (string) $nombreSocializar;
		return $this;
	}

	/**
	 * Get nombreSocializar
	 *
	 * @return null|String
	 */
	public function getNombreSocializar(){
		return $this->nombreSocializar;
	}

	/**
	 * Set provincia
	 *
	 * Provincia socializar
	 *
	 * @parámetro String $provincia
	 * @return Provincia
	 */
	public function setProvincia($provincia){
		$this->provincia = (string) $provincia;
		return $this;
	}

	/**
	 * Get provincia
	 *
	 * @return null|String
	 */
	public function getProvincia(){
		return $this->provincia;
	}

	/**
	 * Set oficina
	 *
	 * Oficina
	 *
	 * @parámetro String $oficina
	 * @return Oficina
	 */
	public function setOficina($oficina){
		$this->oficina = (string) $oficina;
		return $this;
	}

	/**
	 * Get oficina
	 *
	 * @return null|String
	 */
	public function getOficina(){
		return $this->oficina;
	}

	/**
	 * Set coordinacion
	 *
	 * Coordinación
	 *
	 * @parámetro String $coordinacion
	 * @return Coordinacion
	 */
	public function setCoordinacion($coordinacion){
		$this->coordinacion = (string) $coordinacion;
		return $this;
	}

	/**
	 * Get coordinacion
	 *
	 * @return null|String
	 */
	public function getCoordinacion(){
		return $this->coordinacion;
	}

	/**
	 * Set direccion
	 *
	 * Dirección
	 *
	 * @parámetro String $direccion
	 * @return Direccion
	 */
	public function setDireccion($direccion){
		$this->direccion = (string) $direccion;
		return $this;
	}

	/**
	 * Get direccion
	 *
	 * @return null|String
	 */
	public function getDireccion(){
		return $this->direccion;
	}

	/**
	 * Set documentoSocializar
	 *
	 * Documento socializar
	 *
	 * @parámetro String $documentoSocializar
	 * @return DocumentoSocializar
	 */
	public function setDocumentoSocializar($documentoSocializar){
		$this->documentoSocializar = (string) $documentoSocializar;
		return $this;
	}

	/**
	 * Get documentoSocializar
	 *
	 * @return null|String
	 */
	public function getDocumentoSocializar(){
		return $this->documentoSocializar;
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
	 * Set identifcadorAsignante
	 *
	 * Identificador asignante
	 *
	 * @parámetro String $identifcadorAsignante
	 * @return IdentifcadorAsignante
	 */
	public function setIdentifcadorAsignante($identifcadorAsignante){
		$this->identifcadorAsignante = (string) $identifcadorAsignante;
		return $this;
	}

	/**
	 * Get identifcadorAsignante
	 *
	 * @return null|String
	 */
	public function getIdentifcadorAsignante(){
		return $this->identifcadorAsignante;
	}

	/**
	 * Set idTecnico
	 *
	 * Llave foránea de la tabla tecnico
	 *
	 * @parámetro Integer $idTecnico
	 * @return IdTecnico
	 */
	public function setIdTecnico($idTecnico){
		$this->idTecnico = (integer) $idTecnico;
		return $this;
	}

	/**
	 * Get idTecnico
	 *
	 * @return null|Integer
	 */
	public function getIdTecnico(){
		return $this->idTecnico;
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
	 * @return DetalleSocializacionModelo
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
