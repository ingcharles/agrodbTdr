<?php
 /**
 * Modelo Mdtf01Modelo
 *
 * Este archivo se complementa con el archivo   Mdtf01LogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-09-23
 * @uses    Mdtf01Modelo
 * @package FormulariosInspeccion
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class Mdtf01Modelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $id;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idTablet;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $tabletId;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idSolicitud;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $identificadorOperador;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idOperadorTipoOperacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $tipoSolicitud;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $puntuacionMaxima;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $puntuacionObtenida;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $cantidadCumple;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $cantidadNoCumple;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $cantidadNoAplica;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $cantidadNoAplicaDefinida;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $porcentajeFinal;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $estado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $observacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $identificadorUsuario;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $fechaRegistroTablet;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $fechaRegistroGuia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $estadoGenerarChecklist;

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
	* Nombre de la tabla: mdtf01
	* 
	 */
	Private $tabla="mdtf01";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id";



	/**
	*Secuencia
*/
		 private $secuencial = 'f_inspeccion.mdtf01_id_seq'; 



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
		throw new \Exception('Clase Modelo: Mdtf01Modelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: Mdtf01Modelo. Propiedad especificada invalida: get'.$name);
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
	*
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
	* Set idTablet
	*
	*
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
	* Set tabletId
	*
	*
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
	* Set idSolicitud
	*
	*
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
	* Set identificadorOperador
	*
	*
	*
	* @parámetro String $identificadorOperador
	* @return IdentificadorOperador
	*/
	public function setIdentificadorOperador($identificadorOperador)
	{
	  $this->identificadorOperador = (String) $identificadorOperador;
	    return $this;
	}

	/**
	* Get identificadorOperador
	*
	* @return null|String
	*/
	public function getIdentificadorOperador()
	{
		return $this->identificadorOperador;
	}

	/**
	* Set idOperadorTipoOperacion
	*
	*
	*
	* @parámetro Integer $idOperadorTipoOperacion
	* @return IdOperadorTipoOperacion
	*/
	public function setIdOperadorTipoOperacion($idOperadorTipoOperacion)
	{
	  $this->idOperadorTipoOperacion = (Integer) $idOperadorTipoOperacion;
	    return $this;
	}

	/**
	* Get idOperadorTipoOperacion
	*
	* @return null|Integer
	*/
	public function getIdOperadorTipoOperacion()
	{
		return $this->idOperadorTipoOperacion;
	}

	/**
	* Set tipoSolicitud
	*
	*
	*
	* @parámetro String $tipoSolicitud
	* @return TipoSolicitud
	*/
	public function setTipoSolicitud($tipoSolicitud)
	{
	  $this->tipoSolicitud = (String) $tipoSolicitud;
	    return $this;
	}

	/**
	* Get tipoSolicitud
	*
	* @return null|String
	*/
	public function getTipoSolicitud()
	{
		return $this->tipoSolicitud;
	}

	/**
	* Set puntuacionMaxima
	*
	*
	*
	* @parámetro String $puntuacionMaxima
	* @return PuntuacionMaxima
	*/
	public function setPuntuacionMaxima($puntuacionMaxima)
	{
	  $this->puntuacionMaxima = (String) $puntuacionMaxima;
	    return $this;
	}

	/**
	* Get puntuacionMaxima
	*
	* @return null|String
	*/
	public function getPuntuacionMaxima()
	{
		return $this->puntuacionMaxima;
	}

	/**
	* Set puntuacionObtenida
	*
	*
	*
	* @parámetro String $puntuacionObtenida
	* @return PuntuacionObtenida
	*/
	public function setPuntuacionObtenida($puntuacionObtenida)
	{
	  $this->puntuacionObtenida = (String) $puntuacionObtenida;
	    return $this;
	}

	/**
	* Get puntuacionObtenida
	*
	* @return null|String
	*/
	public function getPuntuacionObtenida()
	{
		return $this->puntuacionObtenida;
	}

	/**
	* Set cantidadCumple
	*
	*
	*
	* @parámetro String $cantidadCumple
	* @return CantidadCumple
	*/
	public function setCantidadCumple($cantidadCumple)
	{
	  $this->cantidadCumple = (String) $cantidadCumple;
	    return $this;
	}

	/**
	* Get cantidadCumple
	*
	* @return null|String
	*/
	public function getCantidadCumple()
	{
		return $this->cantidadCumple;
	}

	/**
	* Set cantidadNoCumple
	*
	*
	*
	* @parámetro String $cantidadNoCumple
	* @return CantidadNoCumple
	*/
	public function setCantidadNoCumple($cantidadNoCumple)
	{
	  $this->cantidadNoCumple = (String) $cantidadNoCumple;
	    return $this;
	}

	/**
	* Get cantidadNoCumple
	*
	* @return null|String
	*/
	public function getCantidadNoCumple()
	{
		return $this->cantidadNoCumple;
	}

	/**
	* Set cantidadNoAplica
	*
	*
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
	* Set cantidadNoAplicaDefinida
	*
	*
	*
	* @parámetro String $cantidadNoAplicaDefinida
	* @return CantidadNoAplicaDefinida
	*/
	public function setCantidadNoAplicaDefinida($cantidadNoAplicaDefinida)
	{
	  $this->cantidadNoAplicaDefinida = (String) $cantidadNoAplicaDefinida;
	    return $this;
	}

	/**
	* Get cantidadNoAplicaDefinida
	*
	* @return null|String
	*/
	public function getCantidadNoAplicaDefinida()
	{
		return $this->cantidadNoAplicaDefinida;
	}

	/**
	* Set porcentajeFinal
	*
	*
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
	* Set estado
	*
	*
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
	*
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
	* Set identificadorUsuario
	*
	*
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
	* Set fechaRegistroTablet
	*
	*
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
	*
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
	* Set estadoGenerarChecklist
	*
	*
	*
	* @parámetro String $estadoGenerarChecklist
	* @return EstadoGenerarChecklist
	*/
	public function setEstadoGenerarChecklist($estadoGenerarChecklist)
	{
	  $this->estadoGenerarChecklist = (String) $estadoGenerarChecklist;
	    return $this;
	}

	/**
	* Get estadoGenerarChecklist
	*
	* @return null|String
	*/
	public function getEstadoGenerarChecklist()
	{
		return $this->estadoGenerarChecklist;
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
	* @return Mdtf01Modelo
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
