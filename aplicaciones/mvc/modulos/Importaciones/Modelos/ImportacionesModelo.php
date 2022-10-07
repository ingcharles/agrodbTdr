<?php
/**
 * Modelo ImportacionesModelo
 *
 * Este archivo se complementa con el archivo ImportacionesLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-09-16
 * @uses ImportacionesModelo
 * @package Importaciones
 * @subpackage Modelos
 */
namespace Agrodb\Importaciones\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ImportacionesModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el id de la tabla y es llave primaria
	 */
	protected $idImportacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el identificador del operador dueño de la importación
	 */
	protected $identificadorOperador;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el nombre de exportador
	 */
	protected $nombreExportador;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra la direccion del exportador
	 */
	protected $direccionExportador;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el id del pais de exportación
	 */
	protected $idPaisExportacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el nombre del país de exportación
	 */
	protected $paisExportacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el nombre del embarcador
	 */
	protected $nombreEmbarcador;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el id de la localización
	 */
	protected $idLocalizacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el nombre del país de embarque
	 */
	protected $paisEmbarque;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el id del puerto de embarquer
	 */
	protected $idPuertoEmbarque;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el nombre del puerto de embarque
	 */
	protected $puertoEmbarque;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el id del puerto de destino
	 */
	protected $idPuertoDestino;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el nombre del puerto de destino
	 */
	protected $puertoDestino;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el codigo del certificado
	 */
	protected $codigoCertificado;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el id_vue que es el permiso de importación
	 */
	protected $idVue;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el estado de la solicitud
	 */
	protected $estado;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el tipo del certificado
	 */
	protected $tipoCertificado;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra la moneda
	 */
	protected $moneda;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra la ruta del informe de los requisitos
	 */
	protected $informeRequisitos;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el fecha inicio de la solicitud
	 */
	protected $fechaInicio;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra la fecha de fin de vigencia de la solicitud
	 */
	protected $fechaVigencia;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el tipo de transporte
	 */
	protected $tipoTransporte;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra la fecha en la que fue modificada la solicitud
	 */
	protected $fechaModificacion;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra la fecha de creación de la solicitud
	 */
	protected $fechaCreacion;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el regimen aduanera
	 */
	protected $regimenAduanero;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra la fecha en la que se amplio la solicitud
	 */
	protected $fechaAmpliacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra area tecnica a la que pertenece la solicitud
	 */
	protected $idArea;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el id de la provincia de la solicitud
	 */
	protected $idProvincia;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el nombre de la provincia de la solicitud
	 */
	protected $nombreProvincia;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el id de la ciudad de la solicitud
	 */
	protected $idCiudad;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el nombre de la ciudad de la solicitud
	 */
	protected $nombreCiudad;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra TRUE si la importación tiene seguimiento cuarentenario
	 */
	protected $estadoSeguimiento;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el id del area donde se va a realizar el seguimiento cuarentenario
	 */
	protected $idAreaSeguimiento;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Se registra el código de área de cuarentena de SA o SV
	 */
	protected $numeroCuarentena;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Usuario que realiza el proceso de rectificación
	 */
	protected $identificadorRectificacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Observación que se registra del proceso de modificación
	 */
	protected $observacionRectificacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Indica el motivo por el cual la solicitud fue rechazada si la misma no fue atendida por parte de usuario en un estado de verificación de pago o subsanación en base a decreto 68
	 */
	protected $observacionEliminacion;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_importaciones";

	/**
	 * Nombre de la tabla: importaciones
	 */
	private $tabla = "importaciones";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_importacion";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_importaciones"."Importaciones_id_importacion_seq';

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
			throw new \Exception('Clase Modelo: ImportacionesModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: ImportacionesModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_importaciones
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idImportacion
	 *
	 * Se registra el id de la tabla y es llave primaria
	 *
	 * @parámetro Integer $idImportacion
	 * @return IdImportacion
	 */
	public function setIdImportacion($idImportacion){
		$this->idImportacion = (integer) $idImportacion;
		return $this;
	}

	/**
	 * Get idImportacion
	 *
	 * @return null|Integer
	 */
	public function getIdImportacion(){
		return $this->idImportacion;
	}

	/**
	 * Set identificadorOperador
	 *
	 * Se registra el identificador del operador dueño de la importación
	 *
	 * @parámetro String $identificadorOperador
	 * @return IdentificadorOperador
	 */
	public function setIdentificadorOperador($identificadorOperador){
		$this->identificadorOperador = (string) $identificadorOperador;
		return $this;
	}

	/**
	 * Get identificadorOperador
	 *
	 * @return null|String
	 */
	public function getIdentificadorOperador(){
		return $this->identificadorOperador;
	}

	/**
	 * Set nombreExportador
	 *
	 * Se registra el nombre de exportador
	 *
	 * @parámetro String $nombreExportador
	 * @return NombreExportador
	 */
	public function setNombreExportador($nombreExportador){
		$this->nombreExportador = (string) $nombreExportador;
		return $this;
	}

	/**
	 * Get nombreExportador
	 *
	 * @return null|String
	 */
	public function getNombreExportador(){
		return $this->nombreExportador;
	}

	/**
	 * Set direccionExportador
	 *
	 * Se registra la direccion del exportador
	 *
	 * @parámetro String $direccionExportador
	 * @return DireccionExportador
	 */
	public function setDireccionExportador($direccionExportador){
		$this->direccionExportador = (string) $direccionExportador;
		return $this;
	}

	/**
	 * Get direccionExportador
	 *
	 * @return null|String
	 */
	public function getDireccionExportador(){
		return $this->direccionExportador;
	}

	/**
	 * Set idPaisExportacion
	 *
	 * Se registra el id del pais de exportación
	 *
	 * @parámetro Integer $idPaisExportacion
	 * @return IdPaisExportacion
	 */
	public function setIdPaisExportacion($idPaisExportacion){
		$this->idPaisExportacion = (integer) $idPaisExportacion;
		return $this;
	}

	/**
	 * Get idPaisExportacion
	 *
	 * @return null|Integer
	 */
	public function getIdPaisExportacion(){
		return $this->idPaisExportacion;
	}

	/**
	 * Set paisExportacion
	 *
	 * Se registra el nombre del país de exportación
	 *
	 * @parámetro String $paisExportacion
	 * @return PaisExportacion
	 */
	public function setPaisExportacion($paisExportacion){
		$this->paisExportacion = (string) $paisExportacion;
		return $this;
	}

	/**
	 * Get paisExportacion
	 *
	 * @return null|String
	 */
	public function getPaisExportacion(){
		return $this->paisExportacion;
	}

	/**
	 * Set nombreEmbarcador
	 *
	 * Se registra el nombre del embarcador
	 *
	 * @parámetro String $nombreEmbarcador
	 * @return NombreEmbarcador
	 */
	public function setNombreEmbarcador($nombreEmbarcador){
		$this->nombreEmbarcador = (string) $nombreEmbarcador;
		return $this;
	}

	/**
	 * Get nombreEmbarcador
	 *
	 * @return null|String
	 */
	public function getNombreEmbarcador(){
		return $this->nombreEmbarcador;
	}

	/**
	 * Set idLocalizacion
	 *
	 * Se registra el id de la localización
	 *
	 * @parámetro Integer $idLocalizacion
	 * @return IdLocalizacion
	 */
	public function setIdLocalizacion($idLocalizacion){
		$this->idLocalizacion = (integer) $idLocalizacion;
		return $this;
	}

	/**
	 * Get idLocalizacion
	 *
	 * @return null|Integer
	 */
	public function getIdLocalizacion(){
		return $this->idLocalizacion;
	}

	/**
	 * Set paisEmbarque
	 *
	 * Se registra el nombre del país de embarque
	 *
	 * @parámetro String $paisEmbarque
	 * @return PaisEmbarque
	 */
	public function setPaisEmbarque($paisEmbarque){
		$this->paisEmbarque = (string) $paisEmbarque;
		return $this;
	}

	/**
	 * Get paisEmbarque
	 *
	 * @return null|String
	 */
	public function getPaisEmbarque(){
		return $this->paisEmbarque;
	}

	/**
	 * Set idPuertoEmbarque
	 *
	 * Se registra el id del puerto de embarquer
	 *
	 * @parámetro Integer $idPuertoEmbarque
	 * @return IdPuertoEmbarque
	 */
	public function setIdPuertoEmbarque($idPuertoEmbarque){
		$this->idPuertoEmbarque = (integer) $idPuertoEmbarque;
		return $this;
	}

	/**
	 * Get idPuertoEmbarque
	 *
	 * @return null|Integer
	 */
	public function getIdPuertoEmbarque(){
		return $this->idPuertoEmbarque;
	}

	/**
	 * Set puertoEmbarque
	 *
	 * Se registra el nombre del puerto de embarque
	 *
	 * @parámetro String $puertoEmbarque
	 * @return PuertoEmbarque
	 */
	public function setPuertoEmbarque($puertoEmbarque){
		$this->puertoEmbarque = (string) $puertoEmbarque;
		return $this;
	}

	/**
	 * Get puertoEmbarque
	 *
	 * @return null|String
	 */
	public function getPuertoEmbarque(){
		return $this->puertoEmbarque;
	}

	/**
	 * Set idPuertoDestino
	 *
	 * Se registra el id del puerto de destino
	 *
	 * @parámetro Integer $idPuertoDestino
	 * @return IdPuertoDestino
	 */
	public function setIdPuertoDestino($idPuertoDestino){
		$this->idPuertoDestino = (integer) $idPuertoDestino;
		return $this;
	}

	/**
	 * Get idPuertoDestino
	 *
	 * @return null|Integer
	 */
	public function getIdPuertoDestino(){
		return $this->idPuertoDestino;
	}

	/**
	 * Set puertoDestino
	 *
	 * Se registra el nombre del puerto de destino
	 *
	 * @parámetro String $puertoDestino
	 * @return PuertoDestino
	 */
	public function setPuertoDestino($puertoDestino){
		$this->puertoDestino = (string) $puertoDestino;
		return $this;
	}

	/**
	 * Get puertoDestino
	 *
	 * @return null|String
	 */
	public function getPuertoDestino(){
		return $this->puertoDestino;
	}

	/**
	 * Set codigoCertificado
	 *
	 * Se registra el codigo del certificado
	 *
	 * @parámetro String $codigoCertificado
	 * @return CodigoCertificado
	 */
	public function setCodigoCertificado($codigoCertificado){
		$this->codigoCertificado = (string) $codigoCertificado;
		return $this;
	}

	/**
	 * Get codigoCertificado
	 *
	 * @return null|String
	 */
	public function getCodigoCertificado(){
		return $this->codigoCertificado;
	}

	/**
	 * Set idVue
	 *
	 * Se registra el id_vue que es el permiso de importación
	 *
	 * @parámetro String $idVue
	 * @return IdVue
	 */
	public function setIdVue($idVue){
		$this->idVue = (string) $idVue;
		return $this;
	}

	/**
	 * Get idVue
	 *
	 * @return null|String
	 */
	public function getIdVue(){
		return $this->idVue;
	}

	/**
	 * Set estado
	 *
	 * Se registra el estado de la solicitud
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
	 * Set tipoCertificado
	 *
	 * Se registra el tipo del certificado
	 *
	 * @parámetro String $tipoCertificado
	 * @return TipoCertificado
	 */
	public function setTipoCertificado($tipoCertificado){
		$this->tipoCertificado = (string) $tipoCertificado;
		return $this;
	}

	/**
	 * Get tipoCertificado
	 *
	 * @return null|String
	 */
	public function getTipoCertificado(){
		return $this->tipoCertificado;
	}

	/**
	 * Set moneda
	 *
	 * Se registra la moneda
	 *
	 * @parámetro Integer $moneda
	 * @return Moneda
	 */
	public function setMoneda($moneda){
		$this->moneda = (integer) $moneda;
		return $this;
	}

	/**
	 * Get moneda
	 *
	 * @return null|Integer
	 */
	public function getMoneda(){
		return $this->moneda;
	}

	/**
	 * Set informeRequisitos
	 *
	 * Se registra la ruta del informe de los requisitos
	 *
	 * @parámetro String $informeRequisitos
	 * @return InformeRequisitos
	 */
	public function setInformeRequisitos($informeRequisitos){
		$this->informeRequisitos = (string) $informeRequisitos;
		return $this;
	}

	/**
	 * Get informeRequisitos
	 *
	 * @return null|String
	 */
	public function getInformeRequisitos(){
		return $this->informeRequisitos;
	}

	/**
	 * Set fechaInicio
	 *
	 * Se registra el fecha inicio de la solicitud
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
	 * Set fechaVigencia
	 *
	 * Se registra la fecha de fin de vigencia de la solicitud
	 *
	 * @parámetro Date $fechaVigencia
	 * @return FechaVigencia
	 */
	public function setFechaVigencia($fechaVigencia){
		$this->fechaVigencia = (string) $fechaVigencia;
		return $this;
	}

	/**
	 * Get fechaVigencia
	 *
	 * @return null|Date
	 */
	public function getFechaVigencia(){
		return $this->fechaVigencia;
	}

	/**
	 * Set tipoTransporte
	 *
	 * Se registra el tipo de transporte
	 *
	 * @parámetro String $tipoTransporte
	 * @return TipoTransporte
	 */
	public function setTipoTransporte($tipoTransporte){
		$this->tipoTransporte = (string) $tipoTransporte;
		return $this;
	}

	/**
	 * Get tipoTransporte
	 *
	 * @return null|String
	 */
	public function getTipoTransporte(){
		return $this->tipoTransporte;
	}

	/**
	 * Set fechaModificacion
	 *
	 * Se registra la fecha en la que fue modificada la solicitud
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
	 * Set fechaCreacion
	 *
	 * Se registra la fecha de creación de la solicitud
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
	 * Set regimenAduanero
	 *
	 * Se registra el regimen aduanera
	 *
	 * @parámetro Integer $regimenAduanero
	 * @return RegimenAduanero
	 */
	public function setRegimenAduanero($regimenAduanero){
		$this->regimenAduanero = (integer) $regimenAduanero;
		return $this;
	}

	/**
	 * Get regimenAduanero
	 *
	 * @return null|Integer
	 */
	public function getRegimenAduanero(){
		return $this->regimenAduanero;
	}

	/**
	 * Set fechaAmpliacion
	 *
	 * Se registra la fecha en la que se amplio la solicitud
	 *
	 * @parámetro Date $fechaAmpliacion
	 * @return FechaAmpliacion
	 */
	public function setFechaAmpliacion($fechaAmpliacion){
		$this->fechaAmpliacion = (string) $fechaAmpliacion;
		return $this;
	}

	/**
	 * Get fechaAmpliacion
	 *
	 * @return null|Date
	 */
	public function getFechaAmpliacion(){
		return $this->fechaAmpliacion;
	}

	/**
	 * Set idArea
	 *
	 * Se registra area tecnica a la que pertenece la solicitud
	 *
	 * @parámetro String $idArea
	 * @return IdArea
	 */
	public function setIdArea($idArea){
		$this->idArea = (string) $idArea;
		return $this;
	}

	/**
	 * Get idArea
	 *
	 * @return null|String
	 */
	public function getIdArea(){
		return $this->idArea;
	}

	/**
	 * Set idProvincia
	 *
	 * Se registra el id de la provincia de la solicitud
	 *
	 * @parámetro Integer $idProvincia
	 * @return IdProvincia
	 */
	public function setIdProvincia($idProvincia){
		$this->idProvincia = (integer) $idProvincia;
		return $this;
	}

	/**
	 * Get idProvincia
	 *
	 * @return null|Integer
	 */
	public function getIdProvincia(){
		return $this->idProvincia;
	}

	/**
	 * Set nombreProvincia
	 *
	 * Se registra el nombre de la provincia de la solicitud
	 *
	 * @parámetro String $nombreProvincia
	 * @return NombreProvincia
	 */
	public function setNombreProvincia($nombreProvincia){
		$this->nombreProvincia = (string) $nombreProvincia;
		return $this;
	}

	/**
	 * Get nombreProvincia
	 *
	 * @return null|String
	 */
	public function getNombreProvincia(){
		return $this->nombreProvincia;
	}

	/**
	 * Set idCiudad
	 *
	 * Se registra el id de la ciudad de la solicitud
	 *
	 * @parámetro Integer $idCiudad
	 * @return IdCiudad
	 */
	public function setIdCiudad($idCiudad){
		$this->idCiudad = (integer) $idCiudad;
		return $this;
	}

	/**
	 * Get idCiudad
	 *
	 * @return null|Integer
	 */
	public function getIdCiudad(){
		return $this->idCiudad;
	}

	/**
	 * Set nombreCiudad
	 *
	 * Se registra el nombre de la ciudad de la solicitud
	 *
	 * @parámetro String $nombreCiudad
	 * @return NombreCiudad
	 */
	public function setNombreCiudad($nombreCiudad){
		$this->nombreCiudad = (string) $nombreCiudad;
		return $this;
	}

	/**
	 * Get nombreCiudad
	 *
	 * @return null|String
	 */
	public function getNombreCiudad(){
		return $this->nombreCiudad;
	}

	/**
	 * Set estadoSeguimiento
	 *
	 * Se registra TRUE si la importación tiene seguimiento cuarentenario
	 *
	 * @parámetro String $estadoSeguimiento
	 * @return EstadoSeguimiento
	 */
	public function setEstadoSeguimiento($estadoSeguimiento){
		$this->estadoSeguimiento = (string) $estadoSeguimiento;
		return $this;
	}

	/**
	 * Get estadoSeguimiento
	 *
	 * @return null|String
	 */
	public function getEstadoSeguimiento(){
		return $this->estadoSeguimiento;
	}

	/**
	 * Set idAreaSeguimiento
	 *
	 * Se registra el id del area donde se va a realizar el seguimiento cuarentenario
	 *
	 * @parámetro Integer $idAreaSeguimiento
	 * @return IdAreaSeguimiento
	 */
	public function setIdAreaSeguimiento($idAreaSeguimiento){
		$this->idAreaSeguimiento = (integer) $idAreaSeguimiento;
		return $this;
	}

	/**
	 * Get idAreaSeguimiento
	 *
	 * @return null|Integer
	 */
	public function getIdAreaSeguimiento(){
		return $this->idAreaSeguimiento;
	}

	/**
	 * Set numeroCuarentena
	 *
	 * Se registra el código de área de cuarentena de SA o SV
	 *
	 * @parámetro String $numeroCuarentena
	 * @return NumeroCuarentena
	 */
	public function setNumeroCuarentena($numeroCuarentena){
		$this->numeroCuarentena = (string) $numeroCuarentena;
		return $this;
	}

	/**
	 * Get numeroCuarentena
	 *
	 * @return null|String
	 */
	public function getNumeroCuarentena(){
		return $this->numeroCuarentena;
	}

	/**
	 * Set identificadorRectificacion
	 *
	 * Usuario que realiza el proceso de rectificación
	 *
	 * @parámetro String $identificadorRectificacion
	 * @return IdentificadorRectificacion
	 */
	public function setIdentificadorRectificacion($identificadorRectificacion){
		$this->identificadorRectificacion = (string) $identificadorRectificacion;
		return $this;
	}

	/**
	 * Get identificadorRectificacion
	 *
	 * @return null|String
	 */
	public function getIdentificadorRectificacion(){
		return $this->identificadorRectificacion;
	}

	/**
	 * Set observacionRectificacion
	 *
	 * Observación que se registra del proceso de modificación
	 *
	 * @parámetro String $observacionRectificacion
	 * @return ObservacionRectificacion
	 */
	public function setObservacionRectificacion($observacionRectificacion){
		$this->observacionRectificacion = (string) $observacionRectificacion;
		return $this;
	}

	/**
	 * Get observacionRectificacion
	 *
	 * @return null|String
	 */
	public function getObservacionRectificacion(){
		return $this->observacionRectificacion;
	}

	/**
	 * Set observacionEliminacion
	 *
	 * Indica el motivo por el cual la solicitud fue rechazada si la misma no fue atendida por parte de usuario en un estado de verificación de pago o subsanación en base a decreto 68
	 *
	 * @parámetro String $observacionEliminacion
	 * @return ObservacionEliminacion
	 */
	public function setObservacionEliminacion($observacionEliminacion){
		$this->observacionEliminacion = (string) $observacionEliminacion;
		return $this;
	}

	/**
	 * Get observacionEliminacion
	 *
	 * @return null|String
	 */
	public function getObservacionEliminacion(){
		return $this->observacionEliminacion;
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
	 * @return ImportacionesModelo
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
