<?php
/**
 * Modelo ConfiguracionFitosanitarioModelo
 *
 * Este archivo se complementa con el archivo ConfiguracionFitosanitarioLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2020-07-04
 * @uses ConfiguracionFitosanitarioModelo
 * @package WsFitosanitario
 * @subpackage Modelos
 */
namespace Agrodb\ConfiguracionCertificadoFitosanitarioHub\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ConfiguracionFitosanitarioModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador único de la tabla
	 */
	protected $idConfiguracionFitosanitario;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena el tipo de configuración (1.emision, 2.recepcion)
	 */
	protected $tipoConfiguracionFitosanitario;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena el identificador del país de envío o recepción (localización)
	 */
	protected $idLocalizacionFitosanitario;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena el nombre del país de envío o recepción (localización)
	 */
	protected $nombrePaisFitosanitario;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena el tipo de plataforma de envío o recepción de certificado fitosanitario (1.hub, 2.puntoApunto)
	 */
	protected $plataformaFitosanitario;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que identifica si el país de envío posee certificado digital (SI, NO)
	 */
	protected $certificadoDigitalFitosanitario;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena la ruta del certficado digital del país de envío en caso de poseer
	 */
	protected $rutaCertificadoDigitalFitosanitario;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena el tipo de encriptación de envío de inforación (1.AES, 2.RCA)
	 */
	protected $encriptacionFitosanitario;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena el identificador del usuario que realiza el registro o modificación de la configuración
	 */
	protected $usuarioResponsableFitosanitario;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena la fecha de registro o actualización de la configuración
	 */
	protected $fechaRegistroFitosanitario;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_configuracion_ws";

	/**
	 * Nombre de la tabla: configuracion_fitosanitario
	 */
	private $tabla = "configuracion_fitosanitario";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_configuracion_fitosanitario";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_configuracion_ws"."configuracion_fitosanitario_id_configuracion_fitosanitario_seq';

	// g_configuracion_ws.configuracion_fitosanitario_id_configuracion_fitosanitario_seq

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
			throw new \Exception('Clase Modelo: ConfiguracionFitosanitarioModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: ConfiguracionFitosanitarioModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_configuracion_ws
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idConfiguracionFitosanitario
	 *
	 * Identificador único de la tabla
	 *
	 * @parámetro Integer $idConfiguracionFitosanitario
	 * @return IdConfiguracionFitosanitario
	 */
	public function setIdConfiguracionFitosanitario($idConfiguracionFitosanitario){
		$this->idConfiguracionFitosanitario = (integer) $idConfiguracionFitosanitario;
		return $this;
	}

	/**
	 * Get idConfiguracionFitosanitario
	 *
	 * @return null|Integer
	 */
	public function getIdConfiguracionFitosanitario(){
		return $this->idConfiguracionFitosanitario;
	}

	/**
	 * Set tipoConfiguracionFitosanitario
	 *
	 * Campo que almacena el tipo de configuración (1.emision, 2.recepcion)
	 *
	 * @parámetro String $tipoConfiguracionFitosanitario
	 * @return TipoConfiguracionFitosanitario
	 */
	public function setTipoConfiguracionFitosanitario($tipoConfiguracionFitosanitario){
		$this->tipoConfiguracionFitosanitario = (string) $tipoConfiguracionFitosanitario;
		return $this;
	}

	/**
	 * Get tipoConfiguracionFitosanitario
	 *
	 * @return null|String
	 */
	public function getTipoConfiguracionFitosanitario(){
		return $this->tipoConfiguracionFitosanitario;
	}

	/**
	 * Set idLocalizacionFitosanitario
	 *
	 * Campo que almacena el identificador del país de envío o recepción (localización)
	 *
	 * @parámetro Integer $idLocalizacionFitosanitario
	 * @return IdLocalizacionFitosanitario
	 */
	public function setIdLocalizacionFitosanitario($idLocalizacionFitosanitario){
		$this->idLocalizacionFitosanitario = (integer) $idLocalizacionFitosanitario;
		return $this;
	}

	/**
	 * Get idLocalizacionFitosanitario
	 *
	 * @return null|Integer
	 */
	public function getIdLocalizacionFitosanitario(){
		return $this->idLocalizacionFitosanitario;
	}

	/**
	 * Set nombrePaisFitosanitario
	 *
	 * Campo que almacena el nombre del país de envío o recepción (localización)
	 *
	 * @parámetro String $nombrePaisFitosanitario
	 * @return NombrePaisFitosanitario
	 */
	public function setNombrePaisFitosanitario($nombrePaisFitosanitario){
		$this->nombrePaisFitosanitario = (string) $nombrePaisFitosanitario;
		return $this;
	}

	/**
	 * Get nombrePaisFitosanitario
	 *
	 * @return null|String
	 */
	public function getNombrePaisFitosanitario(){
		return $this->nombrePaisFitosanitario;
	}

	/**
	 * Set plataformaFitosanitario
	 *
	 * Campo que almacena el tipo de plataforma de envío o recepción de certificado fitosanitario (1.hub, 2.puntoApunto)
	 *
	 * @parámetro String $plataformaFitosanitario
	 * @return PlataformaFitosanitario
	 */
	public function setPlataformaFitosanitario($plataformaFitosanitario){
		$this->plataformaFitosanitario = (string) $plataformaFitosanitario;
		return $this;
	}

	/**
	 * Get plataformaFitosanitario
	 *
	 * @return null|String
	 */
	public function getPlataformaFitosanitario(){
		return $this->plataformaFitosanitario;
	}

	/**
	 * Set certificadoDigitalFitosanitario
	 *
	 * Campo que identifica si el país de envío posee certificado digital (SI, NO)
	 *
	 * @parámetro String $certificadoDigitalFitosanitario
	 * @return CertificadoDigitalFitosanitario
	 */
	public function setCertificadoDigitalFitosanitario($certificadoDigitalFitosanitario){
		$this->certificadoDigitalFitosanitario = (string) $certificadoDigitalFitosanitario;
		return $this;
	}

	/**
	 * Get certificadoDigitalFitosanitario
	 *
	 * @return null|String
	 */
	public function getCertificadoDigitalFitosanitario(){
		return $this->certificadoDigitalFitosanitario;
	}

	/**
	 * Set rutaCertificadoDigitalFitosanitario
	 *
	 * Campo que almacena la ruta del certficado digital del país de envío en caso de poseer
	 *
	 * @parámetro String $rutaCertificadoDigitalFitosanitario
	 * @return RutaCertificadoDigitalFitosanitario
	 */
	public function setRutaCertificadoDigitalFitosanitario($rutaCertificadoDigitalFitosanitario){
		$this->rutaCertificadoDigitalFitosanitario = (string) $rutaCertificadoDigitalFitosanitario;
		return $this;
	}

	/**
	 * Get rutaCertificadoDigitalFitosanitario
	 *
	 * @return null|String
	 */
	public function getRutaCertificadoDigitalFitosanitario(){
		return $this->rutaCertificadoDigitalFitosanitario;
	}

	/**
	 * Set encriptacionFitosanitario
	 *
	 * Campo que almacena el tipo de encriptación de envío de inforación (1.AES, 2.RCA)
	 *
	 * @parámetro String $encriptacionFitosanitario
	 * @return EncriptacionFitosanitario
	 */
	public function setEncriptacionFitosanitario($encriptacionFitosanitario){
		$this->encriptacionFitosanitario = (string) $encriptacionFitosanitario;
		return $this;
	}

	/**
	 * Get encriptacionFitosanitario
	 *
	 * @return null|String
	 */
	public function getEncriptacionFitosanitario(){
		return $this->encriptacionFitosanitario;
	}

	/**
	 * Set usuarioResponsableFitosanitario
	 *
	 * Campo que almacena el identificador del usuario que realiza el registro o modificación de la configuración
	 *
	 * @parámetro String $usuarioResponsableFitosanitario
	 * @return UsuarioResponsableFitosanitario
	 */
	public function setUsuarioResponsableFitosanitario($usuarioResponsableFitosanitario){
		$this->usuarioResponsableFitosanitario = (string) $usuarioResponsableFitosanitario;
		return $this;
	}

	/**
	 * Get usuarioResponsableFitosanitario
	 *
	 * @return null|String
	 */
	public function getUsuarioResponsableFitosanitario(){
		return $this->usuarioResponsableFitosanitario;
	}

	/**
	 * Set fechaRegistroFitosanitario
	 *
	 * Campo que almacena la fecha de registro o actualización de la configuración
	 *
	 * @parámetro Date $fechaRegistroFitosanitario
	 * @return FechaRegistroFitosanitario
	 */
	public function setFechaRegistroFitosanitario($fechaRegistroFitosanitario){
		$this->fechaRegistroFitosanitario = (string) $fechaRegistroFitosanitario;
		return $this;
	}

	/**
	 * Get fechaRegistroFitosanitario
	 *
	 * @return null|Date
	 */
	public function getFechaRegistroFitosanitario(){
		return $this->fechaRegistroFitosanitario;
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
	 * @return ConfiguracionFitosanitarioModelo
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
