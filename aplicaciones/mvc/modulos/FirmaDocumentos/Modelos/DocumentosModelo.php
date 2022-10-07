<?php
/**
 * Modelo DocumentosModelo
 *
 * Este archivo se complementa con el archivo DocumentosLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2022-01-14
 * @uses DocumentosModelo
 * @package FirmaDocumentos
 * @subpackage Modelos
 */
namespace Agrodb\FirmaDocumentos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class DocumentosModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador unico de la tabla
	 */
	protected $idDocumento;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Numero de identificación del firmante del documento
	 */
	protected $identificador;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre del firmante del documento
	 */
	protected $nombreFirmante;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Ubicación del firmante del documento
	 */
	protected $localizacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Teléfono del firmante del documento
	 */
	protected $telefono;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre del documento el cual se va a firmar. Por ejemplo: Documento de registro de empresa, Documento de exportación de mascotas, etc.
	 */
	protected $razonDocumento;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Localización del archivo de entrada en el repositorio, ruta completa con directorio raíz.
	 */
	protected $archivoEntrada;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Localización del archivo de salida en el repositorio, ruta completa con directorio raíz.
	 */
	protected $archivoSalida;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Tabla en la cual se almacena el registro del documento, debe ir con el nombre del esquema
	 */
	protected $tablaOrigen;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador del registro de la tabla a la cual pertenece el documento, indispensable para los proceso de actualización de la ruta y estado.
	 */
	protected $idOrigen;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre del campo en el cual se realiza la actualizacion de la ruta del certificado.
	 */
	protected $campoOrigen;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre del campo estado en el cual se realiza la actualizacion del estado del certificado a firamdo, para su despliegue en lso diferentes modulos
	 */
	protected $estdoOrigen;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de registro del documento
	 */
	protected $fechaCreacion;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de registro de proceso de firmado del documento
	 */
	protected $fechaFirmado;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Estado del proceso de firmado:
	 *      Por atender - Documento en espera de firmado
	 *      W - Documento en proceso de firamdo
	 *      Atendida - Documento firamdo
	 */
	protected $estado;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_firma_documentos";

	/**
	 * Nombre de la tabla: documentos
	 */
	private $tabla = "documentos";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_documento";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_firma_documentos"."documentos_id_documento_seq';

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
			throw new \Exception('Clase Modelo: DocumentosModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: DocumentosModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_firma_documentos
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idDocumento
	 *
	 * Identificador unico de la tabla
	 *
	 * @parámetro Integer $idDocumento
	 * @return IdDocumento
	 */
	public function setIdDocumento($idDocumento){
		$this->idDocumento = (integer) $idDocumento;
		return $this;
	}

	/**
	 * Get idDocumento
	 *
	 * @return null|Integer
	 */
	public function getIdDocumento(){
		return $this->idDocumento;
	}

	/**
	 * Set identificador
	 *
	 * Numero de identificación del firmante del documento
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
	 * Set nombreFirmante
	 *
	 * Nombre del firmante del documento
	 *
	 * @parámetro String $nombreFirmante
	 * @return NombreFirmante
	 */
	public function setNombreFirmante($nombreFirmante){
		$this->nombreFirmante = (string) $nombreFirmante;
		return $this;
	}

	/**
	 * Get nombreFirmante
	 *
	 * @return null|String
	 */
	public function getNombreFirmante(){
		return $this->nombreFirmante;
	}

	/**
	 * Set localizacion
	 *
	 * Ubicación del firmante del documento
	 *
	 * @parámetro String $localizacion
	 * @return Localizacion
	 */
	public function setLocalizacion($localizacion){
		$this->localizacion = (string) $localizacion;
		return $this;
	}

	/**
	 * Get localizacion
	 *
	 * @return null|String
	 */
	public function getLocalizacion(){
		return $this->localizacion;
	}

	/**
	 * Set telefono
	 *
	 * Teléfono del firmante del documento
	 *
	 * @parámetro String $telefono
	 * @return Telefono
	 */
	public function setTelefono($telefono){
		$this->telefono = (string) $telefono;
		return $this;
	}

	/**
	 * Get telefono
	 *
	 * @return null|String
	 */
	public function getTelefono(){
		return $this->telefono;
	}

	/**
	 * Set razonDocumento
	 *
	 * Nombre del documento el cual se va a firmar. Por ejemplo: Documento de registro de empresa, Documento de exportación de mascotas, etc.
	 *
	 * @parámetro String $razonDocumento
	 * @return RazonDocumento
	 */
	public function setRazonDocumento($razonDocumento){
		$this->razonDocumento = (string) $razonDocumento;
		return $this;
	}

	/**
	 * Get razonDocumento
	 *
	 * @return null|String
	 */
	public function getRazonDocumento(){
		return $this->razonDocumento;
	}

	/**
	 * Set archivoEntrada
	 *
	 * Localización del archivo de entrada en el repositorio, ruta completa con directorio raíz.
	 *
	 * @parámetro String $archivoEntrada
	 * @return ArchivoEntrada
	 */
	public function setArchivoEntrada($archivoEntrada){
		$this->archivoEntrada = (string) $archivoEntrada;
		return $this;
	}

	/**
	 * Get archivoEntrada
	 *
	 * @return null|String
	 */
	public function getArchivoEntrada(){
		return $this->archivoEntrada;
	}

	/**
	 * Set archivoSalida
	 *
	 * Localización del archivo de salida en el repositorio, ruta completa con directorio raíz.
	 *
	 * @parámetro String $archivoSalida
	 * @return ArchivoSalida
	 */
	public function setArchivoSalida($archivoSalida){
		$this->archivoSalida = (string) $archivoSalida;
		return $this;
	}

	/**
	 * Get archivoSalida
	 *
	 * @return null|String
	 */
	public function getArchivoSalida(){
		return $this->archivoSalida;
	}

	/**
	 * Set tablaOrigen
	 *
	 * Tabla en la cual se almacena el registro del documento, debe ir con el nombre del esquema
	 *
	 * @parámetro String $tablaOrigen
	 * @return TablaOrigen
	 */
	public function setTablaOrigen($tablaOrigen){
		$this->tablaOrigen = (string) $tablaOrigen;
		return $this;
	}

	/**
	 * Get tablaOrigen
	 *
	 * @return null|String
	 */
	public function getTablaOrigen(){
		return $this->tablaOrigen;
	}

	/**
	 * Set idOrigen
	 *
	 * Identificador del registro de la tabla a la cual pertenece el documento, indispensable para los proceso de actualización de la ruta y estado.
	 *
	 * @parámetro Integer $idOrigen
	 * @return IdOrigen
	 */
	public function setIdOrigen($idOrigen){
		$this->idOrigen = (integer) $idOrigen;
		return $this;
	}

	/**
	 * Get idOrigen
	 *
	 * @return null|Integer
	 */
	public function getIdOrigen(){
		return $this->idOrigen;
	}

	/**
	 * Set campoOrigen
	 *
	 * Nombre del campo en el cual se realiza la actualizacion de la ruta del certificado.
	 *
	 * @parámetro String $campoOrigen
	 * @return CampoOrigen
	 */
	public function setCampoOrigen($campoOrigen){
		$this->campoOrigen = (string) $campoOrigen;
		return $this;
	}

	/**
	 * Get campoOrigen
	 *
	 * @return null|String
	 */
	public function getCampoOrigen(){
		return $this->campoOrigen;
	}

	/**
	 * Set estdoOrigen
	 *
	 * Nombre del campo estado en el cual se realiza la actualizacion del estado del certificado a firamdo, para su despliegue en lso diferentes modulos
	 *
	 * @parámetro String $estdoOrigen
	 * @return EstdoOrigen
	 */
	public function setEstdoOrigen($estdoOrigen){
		$this->estdoOrigen = (string) $estdoOrigen;
		return $this;
	}

	/**
	 * Get estdoOrigen
	 *
	 * @return null|String
	 */
	public function getEstdoOrigen(){
		return $this->estdoOrigen;
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
	 * Set fechaFirmado
	 *
	 * Fecha de registro de proceso de firmado del documento
	 *
	 * @parámetro Date $fechaFirmado
	 * @return FechaFirmado
	 */
	public function setFechaFirmado($fechaFirmado){
		$this->fechaFirmado = (string) $fechaFirmado;
		return $this;
	}

	/**
	 * Get fechaFirmado
	 *
	 * @return null|Date
	 */
	public function getFechaFirmado(){
		return $this->fechaFirmado;
	}

	/**
	 * Set estado
	 *
	 * Estado del proceso de firmado:
	 * Por atender - Documento en espera de firmado
	 * W - Documento en proceso de firamdo
	 * Atendida - Documento firamdo
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
	 * @return DocumentosModelo
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
