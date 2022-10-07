<?php
/**
 * Modelo RespuestaNotificacionModelo
 *
 * Este archivo se complementa con el archivo   RespuestaNotificacionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-03-17
 * @uses    RespuestaNotificacionModelo
 * @package NotificacionesFitosanitarias
 * @subpackage Modelos
 */
namespace Agrodb\NotificacionesFitosanitarias\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class RespuestaNotificacionModelo extends ModeloBase
{
	
	/**
	 * @var Integer
	 * Campo requerido
	 * Campo visible en el formulario
	 * Identificador de la tabla de respuestas operadores-técnicos
	 */
	protected $idRespuestaNotificacion;
	/**
	 * @var Integer
	 * Campo requerido
	 * Campo visible en el formulario
	 * Identificador de la tabla notificaciones
	 */
	protected $idNotificacion;
	/**
	 * @var Integer
	 * Campo requerido
	 * Campo visible en el formulario
	 * Identificador de una revisión realizada por un operador a una notificación
	 */
	protected $idPadre;
	/**
	 * @var String
	 * Campo requerido
	 * Campo visible en el formulario
	 * Número de identificación de un operador-técnico
	 */
	protected $identificador;
	/**
	 * @var String
	 * Campo requerido
	 * Campo visible en el formulario
	 * Tipo de usuario operador-técnico
	 */
	protected $tipo;
	/**
	 * @var String
	 * Campo requerido
	 * Campo visible en el formulario
	 * Respuesta realizada por parte de un operador-técnico
	 */
	protected $respuesta;
	/**
	 * @var String
	 * Campo requerido
	 * Campo visible en el formulario
	 * Ruta de ubicación de archivos
	 */
	protected $archivo;
	/**
	 * @var Date
	 * Campo requerido
	 * Campo visible en el formulario
	 * Fecha que un operador realiza una pregunta a una notificación
	 */
	protected $fechaRevision;
	/**
	 * @var Date
	 * Campo requerido
	 * Campo visible en el formulario
	 * Fecha que un técnico responde a una pregunta realizada por un operador
	 */
	protected $fechaRespuesta;
	/**
	 * @var String
	 * Campo requerido
	 * Campo visible en el formulario
	 * True: el operador-técnico contesto y la respuesta es visible para todos los operadores y técnicos
	 False: el operador-técnico contesto y la respuesta es visible solo para quien realizo la pregunta
	 */
	protected $estadoRespuesta;
	/**
	 * @var String
	 * Campo requerido
	 * Campo visible en el formulario
	 * True: no se puede hacer preguntas por parte de los operadores ni tampoco respuestas por parte del técnico
	 False: se puede realizar preguntas por parte del operador y respuestas por parte del técnico
	 */
	protected $finalizarRespuesta;
	/**
	 * @var Integer
	 * Campo requerido
	 * Campo visible en el formulario
	 * Permitirá ingresar el párrafo al
	 que hará referencia el operador
	 para la revisión.
	 */
	protected $parrafo;
	/**
	 * @var String
	 * Campo requerido
	 * Campo visible en el formulario
	 * Respaldo bibliografico
	 */
	protected $respaldoBibliografico;
	/**
	 * @var String
	 * Campo requerido
	 * Campo visible en el formulario
	 * Observaciones a la notificación
	 */
	protected $observaciones;
	/**
	 * @var String
	 * Campo requerido
	 * Campo visible en el formulario
	 * Información complentaria
	 */
	protected $informacionComplementaria;
	
	/**
	 * Campos del formulario
	 * @var array
	 */
	Private $campos = Array();
	
	/**
	 * Nombre del esquema
	 *
	 */
	Private $esquema ="g_notificaciones_fitosanitarias";
	
	/**
	 * Nombre de la tabla: respuesta_notificacion
	 *
	 */
	Private $tabla="respuesta_notificacion";
	
	/**
	 *Clave primaria
	 */
	private $clavePrimaria = "id_respuesta_notificacion";
	
	
	
	/**
	 *Secuencia
	 */
	private $secuencial = 'g_notificaciones_fitosanitarias"."respuesta_notificacion_id_respuesta_notificacion_seq';
	
	
	
	/**
	 * Constructor
	 * $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
	 * @parámetro  array|null $datos
	 * @retorna void
	 */
	public function __construct(array $datos = null)
	{
		if (is_array($datos))
		{
			$this->setOptions($datos);
		}
		$features = new \Zend\Db\TableGateway\Feature\SequenceFeature($this->clavePrimaria, $this->secuencial);
		parent::__construct($this->esquema,$this->tabla, $features);
	}
	
	/**
	 * Permitir el acceso a la propiedad
	 *
	 * @parámetro  string $name
	 * @parámetro  mixed $value
	 * @retorna void
	 */
	public function __set($name, $value)
	{
		$method = 'set' . $name;
		if (!method_exists($this, $method))
		{
			throw new \Exception('Clase Modelo: RespuestaNotificacionModelo. Propiedad especificada invalida: set'.$name);
		}
		$this->$method($value);
	}
	
	/**
	 * Permitir el acceso a la propiedad
	 *
	 * @parámetro  string $name
	 * @retorna mixed
	 */
	public function __get($name)
	{
		$method = 'get' . $name;
		if (!method_exists($this, $method))
		{
			throw new \Exception('Clase Modelo: RespuestaNotificacionModelo. Propiedad especificada invalida: get'.$name);
		}
		return $this->$method();
	}
	
	/**
	 * Llena el modelo con datos
	 *
	 * @parámetro  array $datos
	 * @retorna Modelo
	 */
	public function setOptions(array $datos)
	{
		$methods = get_class_methods($this);
		foreach ($datos as $key => $value)
		{
			$key_original = $key;
			if (strpos($key, '_') > 0) {
				$aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function($string) {
					return ucfirst($string[1]);
				}, ucwords($key));
					$key = $aux;
			}
			$method = 'set' . ucfirst($key);
			if (in_array($method, $methods))
			{
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
	public function getPrepararDatos()
	{
		$claseArray = get_object_vars($this);
		foreach ($this->campos as $key => $value) {
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
	public function setEsquema($esquema)
	{
		$this->esquema = $esquema;
		return $this;
	}
	
	/**
	 * Get g_notificaciones_fitosanitarias
	 *
	 * @return null|
	 */
	public function getEsquema()
	{
		return $this->esquema;
	}
	
	/**
	 * Set idRespuestaNotificacion
	 *
	 *Identificador de la tabla de respuestas operadores-técnicos
	 *
	 * @parámetro Integer $idRespuestaNotificacion
	 * @return IdRespuestaNotificacion
	 */
	public function setIdRespuestaNotificacion($idRespuestaNotificacion)
	{
		$this->idRespuestaNotificacion = (Integer) $idRespuestaNotificacion;
		return $this;
	}
	
	/**
	 * Get idRespuestaNotificacion
	 *
	 * @return null|Integer
	 */
	public function getIdRespuestaNotificacion()
	{
		return $this->idRespuestaNotificacion;
	}
	
	/**
	 * Set idNotificacion
	 *
	 *Identificador de la tabla notificaciones
	 *
	 * @parámetro Integer $idNotificacion
	 * @return IdNotificacion
	 */
	public function setIdNotificacion($idNotificacion)
	{
		$this->idNotificacion = (Integer) $idNotificacion;
		return $this;
	}
	
	/**
	 * Get idNotificacion
	 *
	 * @return null|Integer
	 */
	public function getIdNotificacion()
	{
		return $this->idNotificacion;
	}
	
	/**
	 * Set idPadre
	 *
	 *Identificador de una revisión realizada por un operador a una notificación
	 *
	 * @parámetro Integer $idPadre
	 * @return IdPadre
	 */
	public function setIdPadre($idPadre)
	{
		$this->idPadre = (Integer) $idPadre;
		return $this;
	}
	
	/**
	 * Get idPadre
	 *
	 * @return null|Integer
	 */
	public function getIdPadre()
	{
		return $this->idPadre;
	}
	
	/**
	 * Set identificador
	 *
	 *Número de identificación de un operador-técnico
	 *
	 * @parámetro String $identificador
	 * @return Identificador
	 */
	public function setIdentificador($identificador)
	{
		$this->identificador = (String) $identificador;
		return $this;
	}
	
	/**
	 * Get identificador
	 *
	 * @return null|String
	 */
	public function getIdentificador()
	{
		return $this->identificador;
	}
	
	/**
	 * Set tipo
	 *
	 *Tipo de usuario operador-técnico
	 *
	 * @parámetro String $tipo
	 * @return Tipo
	 */
	public function setTipo($tipo)
	{
		$this->tipo = (String) $tipo;
		return $this;
	}
	
	/**
	 * Get tipo
	 *
	 * @return null|String
	 */
	public function getTipo()
	{
		return $this->tipo;
	}
	
	/**
	 * Set respuesta
	 *
	 *Respuesta realizada por parte de un operador-técnico
	 *
	 * @parámetro String $respuesta
	 * @return Respuesta
	 */
	public function setRespuesta($respuesta)
	{
		$this->respuesta = (String) $respuesta;
		return $this;
	}
	
	/**
	 * Get respuesta
	 *
	 * @return null|String
	 */
	public function getRespuesta()
	{
		return $this->respuesta;
	}
	
	/**
	 * Set archivo
	 *
	 *Ruta de ubicación de archivos
	 *
	 * @parámetro String $archivo
	 * @return Archivo
	 */
	public function setArchivo($archivo)
	{
		$this->archivo = (String) $archivo;
		return $this;
	}
	
	/**
	 * Get archivo
	 *
	 * @return null|String
	 */
	public function getArchivo()
	{
		return $this->archivo;
	}
	
	/**
	 * Set fechaRevision
	 *
	 *Fecha que un operador realiza una pregunta a una notificación
	 *
	 * @parámetro Date $fechaRevision
	 * @return FechaRevision
	 */
	public function setFechaRevision($fechaRevision)
	{
		$this->fechaRevision = (String) $fechaRevision;
		return $this;
	}
	
	/**
	 * Get fechaRevision
	 *
	 * @return null|Date
	 */
	public function getFechaRevision()
	{
		return $this->fechaRevision;
	}
	
	/**
	 * Set fechaRespuesta
	 *
	 *Fecha que un técnico responde a una pregunta realizada por un operador
	 *
	 * @parámetro Date $fechaRespuesta
	 * @return FechaRespuesta
	 */
	public function setFechaRespuesta($fechaRespuesta)
	{
		$this->fechaRespuesta = (String) $fechaRespuesta;
		return $this;
	}
	
	/**
	 * Get fechaRespuesta
	 *
	 * @return null|Date
	 */
	public function getFechaRespuesta()
	{
		return $this->fechaRespuesta;
	}
	
	/**
	 * Set estadoRespuesta
	 *
	 *True: el operador-técnico contesto y la respuesta es visible para todos los operadores y técnicos
	 False: el operador-técnico contesto y la respuesta es visible solo para quien realizo la pregunta
	 *
	 * @parámetro String $estadoRespuesta
	 * @return EstadoRespuesta
	 */
	public function setEstadoRespuesta($estadoRespuesta)
	{
		$this->estadoRespuesta = (String) $estadoRespuesta;
		return $this;
	}
	
	/**
	 * Get estadoRespuesta
	 *
	 * @return null|String
	 */
	public function getEstadoRespuesta()
	{
		return $this->estadoRespuesta;
	}
	
	/**
	 * Set finalizarRespuesta
	 *
	 *True: no se puede hacer preguntas por parte de los operadores ni tampoco respuestas por parte del técnico
	 False: se puede realizar preguntas por parte del operador y respuestas por parte del técnico
	 *
	 * @parámetro String $finalizarRespuesta
	 * @return FinalizarRespuesta
	 */
	public function setFinalizarRespuesta($finalizarRespuesta)
	{
		$this->finalizarRespuesta = (String) $finalizarRespuesta;
		return $this;
	}
	
	/**
	 * Get finalizarRespuesta
	 *
	 * @return null|String
	 */
	public function getFinalizarRespuesta()
	{
		return $this->finalizarRespuesta;
	}
	
	/**
	 * Set parrafo
	 *
	 *Permitirá ingresar el párrafo al
	 que hará referencia el operador
	 para la revisión.
	 *
	 * @parámetro Integer $parrafo
	 * @return Parrafo
	 */
	public function setParrafo($parrafo)
	{
		$this->parrafo = (Integer) $parrafo;
		return $this;
	}
	
	/**
	 * Get parrafo
	 *
	 * @return null|Integer
	 */
	public function getParrafo()
	{
		return $this->parrafo;
	}
	
	/**
	 * Set respaldoBibliografico
	 *
	 *Respaldo bibliografico
	 *
	 * @parámetro String $respaldoBibliografico
	 * @return RespaldoBibliografico
	 */
	public function setRespaldoBibliografico($respaldoBibliografico)
	{
		$this->respaldoBibliografico = (String) $respaldoBibliografico;
		return $this;
	}
	
	/**
	 * Get respaldoBibliografico
	 *
	 * @return null|String
	 */
	public function getRespaldoBibliografico()
	{
		return $this->respaldoBibliografico;
	}
	
	/**
	 * Set observaciones
	 *
	 *Observaciones a la notificación
	 *
	 * @parámetro String $observaciones
	 * @return Observaciones
	 */
	public function setObservaciones($observaciones)
	{
		$this->observaciones = (String) $observaciones;
		return $this;
	}
	
	/**
	 * Get observaciones
	 *
	 * @return null|String
	 */
	public function getObservaciones()
	{
		return $this->observaciones;
	}
	
	/**
	 * Set informacionComplementaria
	 *
	 *Información complentaria
	 *
	 * @parámetro String $informacionComplementaria
	 * @return InformacionComplementaria
	 */
	public function setInformacionComplementaria($informacionComplementaria)
	{
		$this->informacionComplementaria = (String) $informacionComplementaria;
		return $this;
	}
	
	/**
	 * Get informacionComplementaria
	 *
	 * @return null|String
	 */
	public function getInformacionComplementaria()
	{
		return $this->informacionComplementaria;
	}
	
	/**
	 * Guarda el registro actual
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos)
	{
		return parent::guardar($datos);
	}
	
	/**
	 * Actualiza un registro actual
	 * @param array $datos
	 * @param int $id
	 * @return int
	 */
	public function actualizar(Array $datos,$id)
	{
		return parent::actualizar($datos, $this->clavePrimaria . " = " . $id);
	}
	
	/**
	 * Borra el registro actual
	 * @param string Where|array $where
	 * @return int
	 */
	public function borrar($id)
	{
		return parent::borrar($this->clavePrimaria . " = " . $id);
	}
	
	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param  int $id
	 * @return RespuestaNotificacionModelo
	 */
	public function buscar($id)
	{
		return $this->setOptions(parent::buscar($this->clavePrimaria . " = " . $id));
		return $this;
	}
	
	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo()
	{
		return parent::buscarTodo();
	}
	
	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return parent::buscarLista($where);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function ejecutarConsulta($consulta)
	{
		return parent::ejecutarConsulta($consulta);
	}
	
}