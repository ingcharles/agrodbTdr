<?php
/**
 * Modelo MediosTransporteModelo
 *
 * Este archivo se complementa con el archivo MediosTransporteLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2021-09-03
 * @uses MediosTransporteModelo
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class MediosTransporteModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $idMediosTransporte;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      - aéreo
	 *      - marítimo
	 *      - terrestre
	 */
	protected $tipo;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $codigo;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $tipoIngles;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *     
	 */
	protected $estado;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Código correspondiente para el envió a hub proceso Ephyto
	 */
	protected $codigoHub;

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
	 * Nombre de la tabla: medios_transporte
	 */
	private $tabla = "medios_transporte";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_medios_transporte";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_catalogos"."MediosTransporte_id_medios_transporte_seq';

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
			throw new \Exception('Clase Modelo: MediosTransporteModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: MediosTransporteModelo. Propiedad especificada invalida: get' . $name);
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
	 * Set idMediosTransporte
	 *
	 *
	 *
	 * @parámetro Integer $idMediosTransporte
	 * @return IdMediosTransporte
	 */
	public function setIdMediosTransporte($idMediosTransporte){
		$this->idMediosTransporte = (integer) $idMediosTransporte;
		return $this;
	}

	/**
	 * Get idMediosTransporte
	 *
	 * @return null|Integer
	 */
	public function getIdMediosTransporte(){
		return $this->idMediosTransporte;
	}

	/**
	 * Set tipo
	 *
	 * - aéreo
	 * - marítimo
	 * - terrestre
	 *
	 * @parámetro String $tipo
	 * @return Tipo
	 */
	public function setTipo($tipo){
		$this->tipo = (string) $tipo;
		return $this;
	}

	/**
	 * Get tipo
	 *
	 * @return null|String
	 */
	public function getTipo(){
		return $this->tipo;
	}

	/**
	 * Set codigo
	 *
	 *
	 *
	 * @parámetro String $codigo
	 * @return Codigo
	 */
	public function setCodigo($codigo){
		$this->codigo = (string) $codigo;
		return $this;
	}

	/**
	 * Get codigo
	 *
	 * @return null|String
	 */
	public function getCodigo(){
		return $this->codigo;
	}

	/**
	 * Set tipoIngles
	 *
	 *
	 *
	 * @parámetro String $tipoIngles
	 * @return TipoIngles
	 */
	public function setTipoIngles($tipoIngles){
		$this->tipoIngles = (string) $tipoIngles;
		return $this;
	}

	/**
	 * Get tipoIngles
	 *
	 * @return null|String
	 */
	public function getTipoIngles(){
		return $this->tipoIngles;
	}

	/**
	 * Set estado
	 *
	 *
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
	 * Set codigoHub
	 *
	 * Código correspondiente para el envió a hub proceso Ephyto
	 *
	 * @parámetro Integer $codigoHub
	 * @return CodigoHub
	 */
	public function setCodigoHub($codigoHub){
		$this->codigoHub = (integer) $codigoHub;
		return $this;
	}

	/**
	 * Get codigoHub
	 *
	 * @return null|Integer
	 */
	public function getCodigoHub(){
		return $this->codigoHub;
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
	 * @return MediosTransporteModelo
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
