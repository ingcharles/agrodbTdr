<?php
 /**
 * Modelo Bpaf01Modelo
 *
 * Este archivo se complementa con el archivo   Bpaf01LogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-02-02
 * @uses    Bpaf01Modelo
 * @package FormulariosInspeccion
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class Bpaf01Modelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla
		*/
		protected $id;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el id de la solicitud bpa
		*/
		protected $idSolicitud;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el id secuencial de la inspeccion en aplicativo movil
		*/
		protected $idTablet;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la puntuacion maxima
		*/
		protected $puntuacionMaxima;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la puntuacion obtenida
		*/
		protected $puntuacionObtenida;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la cantidad sin ncm
		*/
		protected $cantidadNcm;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la cantidad no aplica
		*/
		protected $cantidadNoAplica;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el valor del porcentaje sin ncm
		*/
		protected $porcentajeSinNcm;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el porcentaje final
		*/
		protected $porcentajeFinal;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la fecha del registro en tablet
		*/
		protected $fechaRegistroTablet;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la fecha de registro en GUIA
		*/
		protected $fechaRegistroGuia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el identificador del dispositivo movil
		*/
		protected $tabletId;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el identificador del usuario que realiza la inspeccion
		*/
		protected $identificadorUsuario;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el estado del resumen de la inspeccion
		*/
		protected $estado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la observacion del resumen de la inspeccion
		*/
		protected $observacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que controla el estado de la inspeccion
		*/
		protected $estadoRegistro;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="f_inspeccion";

	/**
	* Nombre de la tabla: bpaf01
	* 
	 */
	Private $tabla="bpaf01";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id";



	/**
	*Secuencia
*/
		 
		 private $secuencial = 'f_inspeccion"."bpaf01_id_seq';

	
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
		throw new \Exception('Clase Modelo: Bpaf01Modelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: Bpaf01Modelo. Propiedad especificada invalida: get'.$name);
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
	* Get f_inspeccion
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set id
	*
	*Identificador unico de la tabla
	*
	* @parámetro Integer $id
	* @return Id
	*/
	public function setId($id)
	{
	  $this->id = (Integer) $id;
	    return $this;
	}

	/**
	* Get id
	*
	* @return null|Integer
	*/
	public function getId()
	{
		return $this->id;
	}

	/**
	* Set idSolicitud
	*
	*Campo que almacena el id de la solicitud bpa
	*
	* @parámetro Integer $idSolicitud
	* @return IdSolicitud
	*/
	public function setIdSolicitud($idSolicitud)
	{
	  $this->idSolicitud = (Integer) $idSolicitud;
	    return $this;
	}

	/**
	* Get idSolicitud
	*
	* @return null|Integer
	*/
	public function getIdSolicitud()
	{
		return $this->idSolicitud;
	}

	/**
	* Set idTablet
	*
	*Campo que almacena el id secuencial de la inspeccion en aplicativo movil
	*
	* @parámetro Integer $idTablet
	* @return IdTablet
	*/
	public function setIdTablet($idTablet)
	{
	  $this->idTablet = (Integer) $idTablet;
	    return $this;
	}

	/**
	* Get idTablet
	*
	* @return null|Integer
	*/
	public function getIdTablet()
	{
		return $this->idTablet;
	}

	/**
	* Set puntuacionMaxima
	*
	*Campo que almacena la puntuacion maxima
	*
	* @parámetro Integer $puntuacionMaxima
	* @return PuntuacionMaxima
	*/
	public function setPuntuacionMaxima($puntuacionMaxima)
	{
	  $this->puntuacionMaxima = (Integer) $puntuacionMaxima;
	    return $this;
	}

	/**
	* Get puntuacionMaxima
	*
	* @return null|Integer
	*/
	public function getPuntuacionMaxima()
	{
		return $this->puntuacionMaxima;
	}

	/**
	* Set puntuacionObtenida
	*
	*Campo que almacena la puntuacion obtenida
	*
	* @parámetro Integer $puntuacionObtenida
	* @return PuntuacionObtenida
	*/
	public function setPuntuacionObtenida($puntuacionObtenida)
	{
	  $this->puntuacionObtenida = (Integer) $puntuacionObtenida;
	    return $this;
	}

	/**
	* Get puntuacionObtenida
	*
	* @return null|Integer
	*/
	public function getPuntuacionObtenida()
	{
		return $this->puntuacionObtenida;
	}

	/**
	* Set cantidadNcm
	*
	*Campo que almacena la cantidad sin ncm
	*
	* @parámetro Integer $cantidadNcm
	* @return CantidadNcm
	*/
	public function setCantidadNcm($cantidadNcm)
	{
	  $this->cantidadNcm = (Integer) $cantidadNcm;
	    return $this;
	}

	/**
	* Get cantidadNcm
	*
	* @return null|Integer
	*/
	public function getCantidadNcm()
	{
		return $this->cantidadNcm;
	}

	/**
	* Set cantidadNoAplica
	*
	*Campo que almacena la cantidad no aplica
	*
	* @parámetro String $cantidadNoAplica
	* @return CantidadNoAplica
	*/
	public function setCantidadNoAplica($cantidadNoAplica)
	{
	  $this->cantidadNoAplica = (String) $cantidadNoAplica;
	    return $this;
	}

	/**
	* Get cantidadNoAplica
	*
	* @return null|String
	*/
	public function getCantidadNoAplica()
	{
		return $this->cantidadNoAplica;
	}

	/**
	* Set porcentajeSinNcm
	*
	*Campo que almacena el valor del porcentaje sin ncm
	*
	* @parámetro String $porcentajeSinNcm
	* @return PorcentajeSinNcm
	*/
	public function setPorcentajeSinNcm($porcentajeSinNcm)
	{
	  $this->porcentajeSinNcm = (String) $porcentajeSinNcm;
	    return $this;
	}

	/**
	* Get porcentajeSinNcm
	*
	* @return null|String
	*/
	public function getPorcentajeSinNcm()
	{
		return $this->porcentajeSinNcm;
	}

	/**
	* Set porcentajeFinal
	*
	*Campo que almacena el porcentaje final
	*
	* @parámetro String $porcentajeFinal
	* @return PorcentajeFinal
	*/
	public function setPorcentajeFinal($porcentajeFinal)
	{
	  $this->porcentajeFinal = (String) $porcentajeFinal;
	    return $this;
	}

	/**
	* Get porcentajeFinal
	*
	* @return null|String
	*/
	public function getPorcentajeFinal()
	{
		return $this->porcentajeFinal;
	}

	/**
	* Set fechaRegistroTablet
	*
	*Campo que almacena la fecha del registro en tablet
	*
	* @parámetro Date $fechaRegistroTablet
	* @return FechaRegistroTablet
	*/
	public function setFechaRegistroTablet($fechaRegistroTablet)
	{
	  $this->fechaRegistroTablet = (String) $fechaRegistroTablet;
	    return $this;
	}

	/**
	* Get fechaRegistroTablet
	*
	* @return null|Date
	*/
	public function getFechaRegistroTablet()
	{
		return $this->fechaRegistroTablet;
	}

	/**
	* Set fechaRegistroGuia
	*
	*Campo que almacena la fecha de registro en GUIA
	*
	* @parámetro Date $fechaRegistroGuia
	* @return FechaRegistroGuia
	*/
	public function setFechaRegistroGuia($fechaRegistroGuia)
	{
	  $this->fechaRegistroGuia = (String) $fechaRegistroGuia;
	    return $this;
	}

	/**
	* Get fechaRegistroGuia
	*
	* @return null|Date
	*/
	public function getFechaRegistroGuia()
	{
		return $this->fechaRegistroGuia;
	}

	/**
	* Set tabletId
	*
	*Campo que almacena el identificador del dispositivo movil
	*
	* @parámetro String $tabletId
	* @return TabletId
	*/
	public function setTabletId($tabletId)
	{
	  $this->tabletId = (String) $tabletId;
	    return $this;
	}

	/**
	* Get tabletId
	*
	* @return null|String
	*/
	public function getTabletId()
	{
		return $this->tabletId;
	}

	/**
	* Set identificadorUsuario
	*
	*Campo que almacena el identificador del usuario que realiza la inspeccion
	*
	* @parámetro String $identificadorUsuario
	* @return IdentificadorUsuario
	*/
	public function setIdentificadorUsuario($identificadorUsuario)
	{
	  $this->identificadorUsuario = (String) $identificadorUsuario;
	    return $this;
	}

	/**
	* Get identificadorUsuario
	*
	* @return null|String
	*/
	public function getIdentificadorUsuario()
	{
		return $this->identificadorUsuario;
	}

	/**
	* Set estado
	*
	*Campo que almacena el estado del resumen de la inspeccion
	*
	* @parámetro String $estado
	* @return Estado
	*/
	public function setEstado($estado)
	{
	  $this->estado = (String) $estado;
	    return $this;
	}

	/**
	* Get estado
	*
	* @return null|String
	*/
	public function getEstado()
	{
		return $this->estado;
	}

	/**
	* Set observacion
	*
	*Campo que almacena la observacion del resumen de la inspeccion
	*
	* @parámetro String $observacion
	* @return Observacion
	*/
	public function setObservacion($observacion)
	{
	  $this->observacion = (String) $observacion;
	    return $this;
	}

	/**
	* Get observacion
	*
	* @return null|String
	*/
	public function getObservacion()
	{
		return $this->observacion;
	}

	/**
	* Set estadoInspeccionBpa
	*
	*Campo que controla el estado de generacion del checklist
	*
	* @parámetro String $estadoInspeccionBpa
	* @return EstadoInspeccionBpa
	*/
	public function setEstadoRegistro($estadoRegistro)
	{
	    $this->estadoRegistro = (String) $estadoRegistro;
	    return $this;
	}

	/**
	* Get estadoInspeccionBpa
	*
	* @return null|String
	*/
	public function getEstadoRegistro()
	{
	    return $this->estadoRegistro;
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
	* @return Bpaf01Modelo
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
