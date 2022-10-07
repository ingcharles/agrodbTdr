<?php
/**
 * Modelo ProductosModelo
 *
 * Este archivo se complementa con el archivo ProductosLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2020-07-04
 * @uses ProductosModelo
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ProductosModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador único de la tabla
	 */
	protected $idProducto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre común del producto
	 */
	protected $nombreComun;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre científico del producto
	 */
	protected $nombreCientifico;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Partida arancelaria del producto
	 */
	protected $partidaArancelaria;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Código del producto
	 */
	protected $codigoProducto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Subcódigo del producto
	 */
	protected $subcodigoProducto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Ruta de información adicional del producto(PDF)
	 */
	protected $ruta;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Estado que indica si el producto puede ser utilizado
	 */
	protected $estado;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador de la tabla subtipo productos que determina el subtipo de producto al que pertenece el producto
	 */
	protected $idSubtipoProducto;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Indica si el producto necesita de un proceso de certificación de semillas
	 */
	protected $certificadoSemillas;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Indica si el producto posee una licencia del MAGAP
	 */
	protected $licenciaMagap;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Representa la unidad de medida del producto
	 */
	protected $unidadMedida;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha en la que se registró del producto
	 */
	protected $fechaCreacion;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha que se modificó el producto
	 */
	protected $fechaModificacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que indica si el producto pertenece o no a un programa
	 */
	protected $programa;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Indica si el producto tiene trazabilidad
	 */
	protected $trazabilidad;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador del técnico que registra el producto
	 */
	protected $identificadorCreacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador del técnico que realizad a modificación del producto
	 */
	protected $identificadorModificacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Permite identificar si el producto se puede movilizar o no.
	 *      Su uso es para Sanidad Vegetal.
	 */
	protected $movilizacion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Ruta del certificado para productos plaguicidas
	 */
	protected $rutaCertificado;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo que almacena la clasificación de los subtios de productos 1.musaceas, 2.ornamentales, 3.otros
	 */
	protected $clasificacion;
	
	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Id de registro de dossier pecuario
	 */
	protected $idDossierPecuario;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "g_catalogos";

	/**
	 * Nombre de la tabla: productos
	 */
	private $tabla = "productos";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_producto";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_catalogos"."productos_id_producto_seq';

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
			throw new \Exception('Clase Modelo: ProductosModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: ProductosModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_catalogos
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idProducto
	 *
	 * Identificador único de la tabla
	 *
	 * @parámetro Integer $idProducto
	 * @return IdProducto
	 */
	public function setIdProducto($idProducto){
		$this->idProducto = (integer) $idProducto;
		return $this;
	}

	/**
	 * Get idProducto
	 *
	 * @return null|Integer
	 */
	public function getIdProducto(){
		return $this->idProducto;
	}

	/**
	 * Set nombreComun
	 *
	 * Nombre común del producto
	 *
	 * @parámetro String $nombreComun
	 * @return NombreComun
	 */
	public function setNombreComun($nombreComun){
		$this->nombreComun = (string) $nombreComun;
		return $this;
	}

	/**
	 * Get nombreComun
	 *
	 * @return null|String
	 */
	public function getNombreComun(){
		return $this->nombreComun;
	}

	/**
	 * Set nombreCientifico
	 *
	 * Nombre científico del producto
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
	 * Set partidaArancelaria
	 *
	 * Partida arancelaria del producto
	 *
	 * @parámetro String $partidaArancelaria
	 * @return PartidaArancelaria
	 */
	public function setPartidaArancelaria($partidaArancelaria){
		$this->partidaArancelaria = (string) $partidaArancelaria;
		return $this;
	}

	/**
	 * Get partidaArancelaria
	 *
	 * @return null|String
	 */
	public function getPartidaArancelaria(){
		return $this->partidaArancelaria;
	}

	/**
	 * Set codigoProducto
	 *
	 * Código del producto
	 *
	 * @parámetro String $codigoProducto
	 * @return CodigoProducto
	 */
	public function setCodigoProducto($codigoProducto){
		$this->codigoProducto = (string) $codigoProducto;
		return $this;
	}

	/**
	 * Get codigoProducto
	 *
	 * @return null|String
	 */
	public function getCodigoProducto(){
		return $this->codigoProducto;
	}

	/**
	 * Set subcodigoProducto
	 *
	 * Subcódigo del producto
	 *
	 * @parámetro String $subcodigoProducto
	 * @return SubcodigoProducto
	 */
	public function setSubcodigoProducto($subcodigoProducto){
		$this->subcodigoProducto = (string) $subcodigoProducto;
		return $this;
	}

	/**
	 * Get subcodigoProducto
	 *
	 * @return null|String
	 */
	public function getSubcodigoProducto(){
		return $this->subcodigoProducto;
	}

	/**
	 * Set ruta
	 *
	 * Ruta de información adicional del producto(PDF)
	 *
	 * @parámetro String $ruta
	 * @return Ruta
	 */
	public function setRuta($ruta){
		$this->ruta = (string) $ruta;
		return $this;
	}

	/**
	 * Get ruta
	 *
	 * @return null|String
	 */
	public function getRuta(){
		return $this->ruta;
	}

	/**
	 * Set estado
	 *
	 * Estado que indica si el producto puede ser utilizado
	 *
	 * @parámetro Integer $estado
	 * @return Estado
	 */
	public function setEstado($estado){
		$this->estado = (integer) $estado;
		return $this;
	}

	/**
	 * Get estado
	 *
	 * @return null|Integer
	 */
	public function getEstado(){
		return $this->estado;
	}

	/**
	 * Set idSubtipoProducto
	 *
	 * Identificador de la tabla subtipo productos que determina el subtipo de producto al que pertenece el producto
	 *
	 * @parámetro Integer $idSubtipoProducto
	 * @return IdSubtipoProducto
	 */
	public function setIdSubtipoProducto($idSubtipoProducto){
		$this->idSubtipoProducto = (integer) $idSubtipoProducto;
		return $this;
	}

	/**
	 * Get idSubtipoProducto
	 *
	 * @return null|Integer
	 */
	public function getIdSubtipoProducto(){
		return $this->idSubtipoProducto;
	}

	/**
	 * Set certificadoSemillas
	 *
	 * Indica si el producto necesita de un proceso de certificación de semillas
	 *
	 * @parámetro String $certificadoSemillas
	 * @return CertificadoSemillas
	 */
	public function setCertificadoSemillas($certificadoSemillas){
		$this->certificadoSemillas = (string) $certificadoSemillas;
		return $this;
	}

	/**
	 * Get certificadoSemillas
	 *
	 * @return null|String
	 */
	public function getCertificadoSemillas(){
		return $this->certificadoSemillas;
	}

	/**
	 * Set licenciaMagap
	 *
	 * Indica si el producto posee una licencia del MAGAP
	 *
	 * @parámetro String $licenciaMagap
	 * @return LicenciaMagap
	 */
	public function setLicenciaMagap($licenciaMagap){
		$this->licenciaMagap = (string) $licenciaMagap;
		return $this;
	}

	/**
	 * Get licenciaMagap
	 *
	 * @return null|String
	 */
	public function getLicenciaMagap(){
		return $this->licenciaMagap;
	}

	/**
	 * Set unidadMedida
	 *
	 * Representa la unidad de medida del producto
	 *
	 * @parámetro String $unidadMedida
	 * @return UnidadMedida
	 */
	public function setUnidadMedida($unidadMedida){
		$this->unidadMedida = (string) $unidadMedida;
		return $this;
	}

	/**
	 * Get unidadMedida
	 *
	 * @return null|String
	 */
	public function getUnidadMedida(){
		return $this->unidadMedida;
	}

	/**
	 * Set fechaCreacion
	 *
	 * Fecha en la que se registró del producto
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
	 * Set fechaModificacion
	 *
	 * Fecha que se modificó el producto
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
	 * Set programa
	 *
	 * Campo que indica si el producto pertenece o no a un programa
	 *
	 * @parámetro String $programa
	 * @return Programa
	 */
	public function setPrograma($programa){
		$this->programa = (string) $programa;
		return $this;
	}

	/**
	 * Get programa
	 *
	 * @return null|String
	 */
	public function getPrograma(){
		return $this->programa;
	}

	/**
	 * Set trazabilidad
	 *
	 * Indica si el producto tiene trazabilidad
	 *
	 * @parámetro String $trazabilidad
	 * @return Trazabilidad
	 */
	public function setTrazabilidad($trazabilidad){
		$this->trazabilidad = (string) $trazabilidad;
		return $this;
	}

	/**
	 * Get trazabilidad
	 *
	 * @return null|String
	 */
	public function getTrazabilidad(){
		return $this->trazabilidad;
	}

	/**
	 * Set identificadorCreacion
	 *
	 * Identificador del técnico que registra el producto
	 *
	 * @parámetro String $identificadorCreacion
	 * @return IdentificadorCreacion
	 */
	public function setIdentificadorCreacion($identificadorCreacion){
		$this->identificadorCreacion = (string) $identificadorCreacion;
		return $this;
	}

	/**
	 * Get identificadorCreacion
	 *
	 * @return null|String
	 */
	public function getIdentificadorCreacion(){
		return $this->identificadorCreacion;
	}

	/**
	 * Set identificadorModificacion
	 *
	 * Identificador del técnico que realizad a modificación del producto
	 *
	 * @parámetro String $identificadorModificacion
	 * @return IdentificadorModificacion
	 */
	public function setIdentificadorModificacion($identificadorModificacion){
		$this->identificadorModificacion = (string) $identificadorModificacion;
		return $this;
	}

	/**
	 * Get identificadorModificacion
	 *
	 * @return null|String
	 */
	public function getIdentificadorModificacion(){
		return $this->identificadorModificacion;
	}

	/**
	 * Set movilizacion
	 *
	 * Permite identificar si el producto se puede movilizar o no.
	 * Su uso es para Sanidad Vegetal.
	 *
	 * @parámetro String $movilizacion
	 * @return Movilizacion
	 */
	public function setMovilizacion($movilizacion){
		$this->movilizacion = (string) $movilizacion;
		return $this;
	}

	/**
	 * Get movilizacion
	 *
	 * @return null|String
	 */
	public function getMovilizacion(){
		return $this->movilizacion;
	}

	/**
	 * Set rutaCertificado
	 *
	 * Ruta del certificado para productos plaguicidas
	 *
	 * @parámetro String $rutaCertificado
	 * @return RutaCertificado
	 */
	public function setRutaCertificado($rutaCertificado){
		$this->rutaCertificado = (string) $rutaCertificado;
		return $this;
	}

	/**
	 * Get rutaCertificado
	 *
	 * @return null|String
	 */
	public function getRutaCertificado(){
		return $this->rutaCertificado;
	}

	/**
	 * Set clasificacion
	 *
	 * Campo que almacena la clasificación de los subtios de productos 1.musaceas, 2.ornamentales, 3.otros
	 *
	 * @parámetro String $clasificacion
	 * @return Clasificacion
	 */
	public function setClasificacion($clasificacion){
		$this->clasificacion = (string) $clasificacion;
		return $this;
	}

	/**
	 * Get clasificacion
	 *
	 * @return null|String
	 */
	public function getClasificacion(){
		return $this->clasificacion;
	}

	/**
	 * Set idDossierPecuario
	 *
	 * Id de dossier pecuario
	 *
	 * @parámetro Integer $idDossierPecuario
	 * @return IdDossierPecuario
	 */
	public function setIdDossierPecuario($idDossierPecuario){
	    $this->idDossierPecuario = (integer) $idDossierPecuario;
	    return $this;
	}
	
	/**
	 * Get idDossierPecuario
	 *
	 * @return null|Integer
	 */
	public function getIdDossierPecuario(){
	    return $this->idDossierPecuario;
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
	 * @return ProductosModelo
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
		return parent::buscarLista($where, $order);
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
