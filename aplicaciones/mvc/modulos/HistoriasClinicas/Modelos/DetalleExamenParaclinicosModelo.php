<?php
/**
 * Modelo DetalleExamenParaclinicosModelo
 *
 * Este archivo se complementa con el archivo DetalleExamenParaclinicosLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses DetalleExamenParaclinicosModelo
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class DetalleExamenParaclinicosModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave primaria de la tabla
	 */
	protected $idDetalExaParaclinicos;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave foránea de la tabla examen_paraclinicos
	 */
	protected $idExamenParaclinicos;

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Llave foránea de la tabla subtipo_proced_medico
	 */
	protected $idSubtipoProcedMedico;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Respuesta
	 */
	protected $respuesta;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Oído
	 */
	protected $oido;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha de creación del registro
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
	private $esquema = "g_historias_clinicas";

	/**
	 * Nombre de la tabla: detalle_examen_paraclinicos
	 */
	private $tabla = "detalle_examen_paraclinicos";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_detal_exa_paraclinicos";

	/**
	 * Secuencia
	 */
	private $secuencial = 'g_historias_clinicas"."detalle_examen_paraclinicos_id_detal_exa_paraclinicos_seq';

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
			throw new \Exception('Clase Modelo: DetalleExamenParaclinicosModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: DetalleExamenParaclinicosModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get g_historias_clinicas
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idDetalExaParaclinicos
	 *
	 * Llave primaria de la tabla
	 *
	 * @parámetro Integer $idDetalExaParaclinicos
	 * @return IdDetalExaParaclinicos
	 */
	public function setIdDetalExaParaclinicos($idDetalExaParaclinicos){
		$this->idDetalExaParaclinicos = (integer) $idDetalExaParaclinicos;
		return $this;
	}

	/**
	 * Get idDetalExaParaclinicos
	 *
	 * @return null|Integer
	 */
	public function getIdDetalExaParaclinicos(){
		return $this->idDetalExaParaclinicos;
	}

	/**
	 * Set idExamenParaclinicos
	 *
	 * Llave foránea de la tabla examen_paraclinicos
	 *
	 * @parámetro Integer $idExamenParaclinicos
	 * @return IdExamenParaclinicos
	 */
	public function setIdExamenParaclinicos($idExamenParaclinicos){
		$this->idExamenParaclinicos = (integer) $idExamenParaclinicos;
		return $this;
	}

	/**
	 * Get idExamenParaclinicos
	 *
	 * @return null|Integer
	 */
	public function getIdExamenParaclinicos(){
		return $this->idExamenParaclinicos;
	}

	/**
	 * Set idSubtipoProcedMedico
	 *
	 * Llave foránea de la tabla subtipo_proced_medico
	 *
	 * @parámetro Integer $idSubtipoProcedMedico
	 * @return IdSubtipoProcedMedico
	 */
	public function setIdSubtipoProcedMedico($idSubtipoProcedMedico){
		$this->idSubtipoProcedMedico = (integer) $idSubtipoProcedMedico;
		return $this;
	}

	/**
	 * Get idSubtipoProcedMedico
	 *
	 * @return null|Integer
	 */
	public function getIdSubtipoProcedMedico(){
		return $this->idSubtipoProcedMedico;
	}

	/**
	 * Set respuesta
	 *
	 * Respuesta
	 *
	 * @parámetro String $respuesta
	 * @return Respuesta
	 */
	public function setRespuesta($respuesta){
		$this->respuesta = (string) $respuesta;
		return $this;
	}

	/**
	 * Get respuesta
	 *
	 * @return null|String
	 */
	public function getRespuesta(){
		return $this->respuesta;
	}

	/**
	 * Set oido
	 *
	 * Oído
	 *
	 * @parámetro String $oido
	 * @return Oido
	 */
	public function setOido($oido){
		$this->oido = (string) $oido;
		return $this;
	}

	/**
	 * Get oido
	 *
	 * @return null|String
	 */
	public function getOido(){
		return $this->oido;
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

	public function borrarPorParametro($param, $value){
		return parent::borrar($param . " = " . $value);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return DetalleExamenParaclinicosModelo
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
