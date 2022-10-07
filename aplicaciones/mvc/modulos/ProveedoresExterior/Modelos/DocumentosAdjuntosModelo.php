<?php
/**
 * Modelo DocumentosAdjuntosModelo
 *
 * Este archivo se complementa con el archivo DocumentosAdjuntosLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-07-13
 * @uses DocumentosAdjuntosModelo
 * @package ProveedoresExterior
 * @subpackage Modelos
 */
namespace Agrodb\ProveedoresExterior\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class DocumentosAdjuntosModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador unico de la tabla
	 */
	protected $idDocumentoAdjunto;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador unico de la tabla g_proveedores_exterior.proveedor_exterior (llave foranea)
	 */
	protected $idProveedorExterior;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena el tipo de documento adjunto cargado por el operador
	 */
	protected $tipoAdjunto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena la ruta del documento adjunto cargado por el operador
	 */
	protected $rutaAdjunto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Estado del documento adjunto cargado por el operador
	 */
	protected $estadoAdjunto;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $fechaCreacionDocumentoAdjunto;

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
	private $secuencial = 'g_proveedores_exterior"."documentos_adjuntos_id_documento_adjunto_seq';

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
	 * Get g_proveedores_exterior
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idDocumentoAdjunto
	 *
	 * Identificador unico de la tabla
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
	 * Set idProveedorExterior
	 *
	 * Identificador unico de la tabla g_proveedores_exterior.proveedor_exterior (llave foranea)
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
	 * Set tipoAdjunto
	 *
	 * Campo que almacena el tipo de documento adjunto cargado por el operador
	 *
	 * @parámetro String $tipoAdjunto
	 * @return TipoAdjunto
	 */
	public function setTipoAdjunto($tipoAdjunto){
		$this->tipoAdjunto = (string) $tipoAdjunto;
		return $this;
	}

	/**
	 * Get tipoAdjunto
	 *
	 * @return null|String
	 */
	public function getTipoAdjunto(){
		return $this->tipoAdjunto;
	}

	/**
	 * Set rutaAdjunto
	 *
	 * Campo que almacena la ruta del documento adjunto cargado por el operador
	 *
	 * @parámetro String $rutaAdjunto
	 * @return RutaAdjunto
	 */
	public function setRutaAdjunto($rutaAdjunto){
		$this->rutaAdjunto = (string) $rutaAdjunto;
		return $this;
	}

	/**
	 * Get rutaAdjunto
	 *
	 * @return null|String
	 */
	public function getRutaAdjunto(){
		return $this->rutaAdjunto;
	}

	/**
	 * Set estadoAdjunto
	 *
	 * Estado del documento adjunto cargado por el operador
	 *
	 * @parámetro String $estadoAdjunto
	 * @return EstadoAdjunto
	 */
	public function setEstadoAdjunto($estadoAdjunto){
		$this->estadoAdjunto = (string) $estadoAdjunto;
		return $this;
	}

	/**
	 * Get estadoAdjunto
	 *
	 * @return null|String
	 */
	public function getEstadoAdjunto(){
		return $this->estadoAdjunto;
	}

	/**
	 * Set fechaCreacionDocumentoAdjunto
	 *
	 *
	 *
	 * @parámetro Date $fechaCreacionDocumentoAdjunto
	 * @return FechaCreacionDocumentoAdjunto
	 */
	public function setFechaCreacionDocumentoAdjunto($fechaCreacionDocumentoAdjunto){
		$this->fechaCreacionDocumentoAdjunto = (string) $fechaCreacionDocumentoAdjunto;
		return $this;
	}

	/**
	 * Get fechaCreacionDocumentoAdjunto
	 *
	 * @return null|Date
	 */
	public function getFechaCreacionDocumentoAdjunto(){
		return $this->fechaCreacionDocumentoAdjunto;
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
