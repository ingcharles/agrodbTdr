<?php
/**
 * Modelo DocumentoAdjuntoModelo
 *
 * Este archivo se complementa con el archivo DocumentoAdjuntoLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-10-18
 * @uses DocumentoAdjuntoModelo
 * @package RegistroControlDocumentos
 * @subpackage Modelos
 */
namespace Agrodb\RegistroControlDocumentos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class DocumentoAdjuntoModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      LLave primaria de la tabla
	 */
	protected $idDocumentoAdjunto;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave foránea de la tabla registro_sgc
	 */
	protected $idRegistroSgc;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Ruta del archivo adjunto
	 */
	protected $rutaArchivo;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Estado del registro
	 */
	protected $estado;

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
	 *      Nombre archivo
	 */
	protected $nombreArchivo;

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
	 * Nombre de la tabla: documento_adjunto
	 */
	private $tabla = "documento_adjunto";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_documento_adjunto";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_registro_control_documentos"."documento_adjunto_id_documento_adjunto_seq';

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
			throw new \Exception('Clase Modelo: DocumentoAdjuntoModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: DocumentoAdjuntoModelo. Propiedad especificada invalida: get' . $name);
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
	 * Set idDocumentoAdjunto
	 *
	 * LLave primaria de la tabla
	 *
	 * @parámetro Integer $idDocumentoAdjunto
	 * @return IdDocumentoAdjunto
	 */
	public function setIdDocumentoAdjunto($idDocumentoAdjunto){
		$this->idDocumentoAdjunto = (integer) $idDocumentoAdjunto;
		return $this;
	}

	/**
	 * Get idDocumentoAdjunto
	 *
	 * @return null|Integer
	 */
	public function getIdDocumentoAdjunto(){
		return $this->idDocumentoAdjunto;
	}

	/**
	 * Set idRegistroSgc
	 *
	 * Llave foránea de la tabla registro_sgc
	 *
	 * @parámetro Integer $idRegistroSgc
	 * @return IdRegistroSgc
	 */
	public function setIdRegistroSgc($idRegistroSgc){
		$this->idRegistroSgc = (integer) $idRegistroSgc;
		return $this;
	}

	/**
	 * Get idRegistroSgc
	 *
	 * @return null|Integer
	 */
	public function getIdRegistroSgc(){
		return $this->idRegistroSgc;
	}

	/**
	 * Set rutaArchivo
	 *
	 * Ruta del archivo adjunto
	 *
	 * @parámetro String $rutaArchivo
	 * @return RutaArchivo
	 */
	public function setRutaArchivo($rutaArchivo){
		$this->rutaArchivo = (string) $rutaArchivo;
		return $this;
	}

	/**
	 * Get rutaArchivo
	 *
	 * @return null|String
	 */
	public function getRutaArchivo(){
		return $this->rutaArchivo;
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
	 * Set nombreArchivo
	 *
	 * @parámetro Date $fechaCreacion
	 * @return NombreArchivo
	 */
	public function setNombreArchivo($nombreArchivo){
		$this->nombreArchivo = (string) $nombreArchivo;
		return $this;
	}

	/**
	 * Get nombreArchivo
	 *
	 * @return null|string
	 */
	public function getNombreArchivo(){
		return $this->nombreArchivo;
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
	 * @return DocumentoAdjuntoModelo
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
