<?php
/**
 * Modelo EventosModelo
 *
 * Este archivo se complementa con el archivo EventosLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @date    2019-07-24
 * @uses EventosModelo
 * @package AplicacionMovilExternos
 * @subpackage Modelos
 */
namespace Agrodb\AplicacionMovilExternos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class EventosModelo extends ModeloBase{

	/**
	 *
	 * @var Integer Campo requerido
	 *      Campo visible en el formulario
	 *      Identificador de la tabla
	 */
	protected $idEvento;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Nombre del evento o campaña preventiva
	 */
	protected $nombreEvento;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Estado que determina si un evento o capaña se encuentra activo
	 */
	protected $estado;

	/**
	 *
	 * @var Date Campo requerido
	 *      Campo visible en el formulario
	 *      Fecha en que se crea el evento
	 */
	protected $fechaEvento;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Ruta de la imagen de la campaña
	 */
	protected $rutaImagen;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Campo donde se describe el por que de la creación del evento
	 */
	protected $descripcion;

	/**
	 *
	 * @var String Campo requerido
	 *      Campo visible en el formulario
	 *      Enlace a un recuro disponible para descargar
	 */
	protected $rutaRecurso;

	/**
	 * Campos del formulario
	 *
	 * @var array
	 */
	private $campos = Array();

	/**
	 * Nombre del esquema
	 */
	private $esquema = "a_movil_externos";

	/**
	 * Nombre de la tabla: eventos
	 */
	private $tabla = "eventos";

	/**
	 * Clave primaria
	 */
	private $clavePrimaria = "id_evento";

	/**
	 * Secuencia
	 */
	private $secuencial = 'a_movil_externos"."eventos_id_evento_seq';

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
			throw new \Exception('Clase Modelo: EventosModelo. Propiedad especificada invalida: set' . $name);
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
			throw new \Exception('Clase Modelo: EventosModelo. Propiedad especificada invalida: get' . $name);
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
	 * Get a_movil_externos
	 *
	 * @return null
	 */
	public function getEsquema(){
		return $this->esquema;
	}

	/**
	 * Set idEvento
	 *
	 * Identificador de la tabla
	 *
	 * @parámetro Integer $idEvento
	 * @return IdEvento
	 */
	public function setIdEvento($idEvento){
		$this->idEvento = (integer) $idEvento;
		return $this;
	}

	/**
	 * Get idEvento
	 *
	 * @return null|Integer
	 */
	public function getIdEvento(){
		return $this->idEvento;
	}

	/**
	 * Set nombreEvento
	 *
	 * Nombre del evento o campaña preventiva
	 *
	 * @parámetro String $nombreEvento
	 * @return NombreEvento
	 */
	public function setNombreEvento($nombreEvento){
		$this->nombreEvento = (string) $nombreEvento;
		return $this;
	}

	/**
	 * Get nombreEvento
	 *
	 * @return null|String
	 */
	public function getNombreEvento(){
		return $this->nombreEvento;
	}

	/**
	 * Set estado
	 *
	 * Estado que determina si un evento o capaña se encuentra activo
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
	 * Set fechaEvento
	 *
	 * Fecha en que se crea el evento
	 *
	 * @parámetro Date $fechaEvento
	 * @return FechaEvento
	 */
	public function setFechaEvento($fechaEvento){
		$this->fechaEvento = (string) $fechaEvento;
		return $this;
	}

	/**
	 * Get fechaEvento
	 *
	 * @return null|Date
	 */
	public function getFechaEvento(){
		return $this->fechaEvento;
	}

	/**
	 * Set rutaImagen
	 *
	 * Ruta de la imagen de la campaña
	 *
	 * @parámetro String $rutaImagen
	 * @return RutaImagen
	 */
	public function setRutaImagen($rutaImagen){
		$this->rutaImagen = (string) $rutaImagen;
		return $this;
	}

	/**
	 * Get rutaImagen
	 *
	 * @return null|String
	 */
	public function getRutaImagen(){
		return $this->rutaImagen;
	}

	/**
	 * Set descripcion
	 *
	 * Campo donde se describe el por que de la creación del evento
	 *
	 * @parámetro String $descripcion
	 * @return Descripcion
	 */
	public function setDescripcion($descripcion){
		$this->descripcion = (string) $descripcion;
		return $this;
	}

	/**
	 * Get descripcion
	 *
	 * @return null|String
	 */
	public function getDescripcion(){
		return $this->descripcion;
	}

	/**
	 * Set rutaRecurso
	 *
	 * Enlace a un recuro disponible para descargar
	 *
	 * @parámetro String $rutaRecurso
	 * @return RutaRecurso
	 */
	public function setRutaRecurso($rutaRecurso){
		$this->rutaRecurso = (string) $rutaRecurso;
		return $this;
	}

	/**
	 * Get rutaRecurso
	 *
	 * @return null|String
	 */
	public function getRutaRecurso(){
		return $this->rutaRecurso;
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
	 * @return EventosModelo
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
