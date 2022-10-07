<?php
/**
 * Modelo DocumentosAdjuntosModelo
 *
 * Este archivo se complementa con el archivo DocumentosAdjuntosLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2020-02-20
 * @uses DocumentosAdjuntosModelo
 * @package ImportacionFertilizantes
 * @subpackage Modelos
 */
namespace Agrodb\ImportacionFertilizantes\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class DocumentosAdjuntosModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador único de la tabla
	 */
	protected $idDocumentoAdjunto;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que hace referencia a la tabla g_importaciones_fertizantes.importaciones_fertilizantes
	 */
	protected $idImportacionFertilizantes;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre especifico del documento adjunto
	 */
	protected $tipoArchivo;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Ruta especifica en donde se almacena el archivo en el servidor
	 */
	protected $rutaArchivo;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Estado del documento
	 */
	protected $estado;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de registro del documento
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
	private $esquema = "g_importaciones_fertilizantes";

	/**
	 * Nombre de la tabla: documentos_adjuntos
	 */
	private $tabla = "documentos_adjuntos";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_documento_adjunto";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_importaciones_fertilizantes"."documentos_adjuntos_id_documento_adjunto_seq';

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
			throw new \Exception('Clase Modelo: DocumentosAdjuntosModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: DocumentosAdjuntosModelo. Propiedad especificada invalida: get' . $name);
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
	 * Set idDocumentoAdjunto
	 *
	 * Identificador único de la tabla
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
	 * Set idImportacionFertilizantes
	 *
	 * Campo que hace referencia a la tabla g_importaciones_fertizantes.importaciones_fertilizantes
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
	 * Set tipoArchivo
	 *
	 * Nombre especifico del documento adjunto
	 *
	 * @parámetro String $tipoArchivo
	 * @return TipoArchivo
	 */
	public function setTipoArchivo($tipoArchivo){
		$this->tipoArchivo = (string) $tipoArchivo;
		return $this;
	}

	/**
	 * Get tipoArchivo
	 *
	 * @return null|String
	 */
	public function getTipoArchivo(){
		return $this->tipoArchivo;
	}

	/**
	 * Set rutaArchivo
	 *
	 * Ruta especifica en donde se almacena el archivo en el servidor
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
	 * Estado del documento
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
	 * Fecha de registro del documento
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

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return DocumentosAdjuntosModelo
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
