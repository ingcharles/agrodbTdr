<?php
 /**
 * Modelo AdministracionTrampasModelo
 *
 * Este archivo se complementa con el archivo   AdministracionTrampasLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-07
 * @uses    AdministracionTrampasModelo
 * @package AdministracionTrampas
 * @subpackage Modelos
 */
  namespace Agrodb\AdministracionTrampas\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class AdministracionTrampasModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idAdministracionTrampa;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $codigoTrampa;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idAreaTrampa;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $etapaTrampa;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $fechaInstalacionTrampa;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idProvincia;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idCanton;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idParroquia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $coordenadax;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $coordenaday;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $coordenadaz;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idLugarInstalacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $numeroLugarInstalacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idPlaga;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idTipoTrampa;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idTipoAtrayente;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $estadoTrampa;
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
		protected $identificadorTecnico;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $fechaModificacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $codigoProgramaEspecifico;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_administracion_trampas";

	/**
	* Nombre de la tabla: administracion_trampas
	* 
	 */
	Private $tabla="administracion_trampas";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_administracion_trampa";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_administracion_trampas"."AdministracionTrampas_id_administracion_trampa_seq'; 



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
		throw new \Exception('Clase Modelo: AdministracionTrampasModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: AdministracionTrampasModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_administracion_trampas
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idAdministracionTrampa
	*
	*
	*
	* @parámetro Integer $idAdministracionTrampa
	* @return IdAdministracionTrampa
	*/
	public function setIdAdministracionTrampa($idAdministracionTrampa)
	{
	  $this->idAdministracionTrampa = (Integer) $idAdministracionTrampa;
	    return $this;
	}

	/**
	* Get idAdministracionTrampa
	*
	* @return null|Integer
	*/
	public function getIdAdministracionTrampa()
	{
		return $this->idAdministracionTrampa;
	}

	/**
	* Set codigoTrampa
	*
	*
	*
	* @parámetro String $codigoTrampa
	* @return CodigoTrampa
	*/
	public function setCodigoTrampa($codigoTrampa)
	{
	  $this->codigoTrampa = (String) $codigoTrampa;
	    return $this;
	}

	/**
	* Get codigoTrampa
	*
	* @return null|String
	*/
	public function getCodigoTrampa()
	{
		return $this->codigoTrampa;
	}

	/**
	* Set idAreaTrampa
	*
	*
	*
	* @parámetro Integer $idAreaTrampa
	* @return IdAreaTrampa
	*/
	public function setIdAreaTrampa($idAreaTrampa)
	{
	  $this->idAreaTrampa = (Integer) $idAreaTrampa;
	    return $this;
	}

	/**
	* Get idAreaTrampa
	*
	* @return null|Integer
	*/
	public function getIdAreaTrampa()
	{
		return $this->idAreaTrampa;
	}

	/**
	* Set etapaTrampa
	*
	*
	*
	* @parámetro String $etapaTrampa
	* @return EtapaTrampa
	*/
	public function setEtapaTrampa($etapaTrampa)
	{
	  $this->etapaTrampa = (String) $etapaTrampa;
	    return $this;
	}

	/**
	* Get etapaTrampa
	*
	* @return null|String
	*/
	public function getEtapaTrampa()
	{
		return $this->etapaTrampa;
	}

	/**
	* Set fechaInstalacionTrampa
	*
	*
	*
	* @parámetro Date $fechaInstalacionTrampa
	* @return FechaInstalacionTrampa
	*/
	public function setFechaInstalacionTrampa($fechaInstalacionTrampa)
	{
	  $this->fechaInstalacionTrampa = (String) $fechaInstalacionTrampa;
	    return $this;
	}

	/**
	* Get fechaInstalacionTrampa
	*
	* @return null|Date
	*/
	public function getFechaInstalacionTrampa()
	{
		return $this->fechaInstalacionTrampa;
	}

	/**
	* Set idProvincia
	*
	*
	*
	* @parámetro Integer $idProvincia
	* @return IdProvincia
	*/
	public function setIdProvincia($idProvincia)
	{
	  $this->idProvincia = (Integer) $idProvincia;
	    return $this;
	}

	/**
	* Get idProvincia
	*
	* @return null|Integer
	*/
	public function getIdProvincia()
	{
		return $this->idProvincia;
	}

	/**
	* Set idCanton
	*
	*
	*
	* @parámetro Integer $idCanton
	* @return IdCanton
	*/
	public function setIdCanton($idCanton)
	{
	  $this->idCanton = (Integer) $idCanton;
	    return $this;
	}

	/**
	* Get idCanton
	*
	* @return null|Integer
	*/
	public function getIdCanton()
	{
		return $this->idCanton;
	}

	/**
	* Set idParroquia
	*
	*
	*
	* @parámetro Integer $idParroquia
	* @return IdParroquia
	*/
	public function setIdParroquia($idParroquia)
	{
	  $this->idParroquia = (Integer) $idParroquia;
	    return $this;
	}

	/**
	* Get idParroquia
	*
	* @return null|Integer
	*/
	public function getIdParroquia()
	{
		return $this->idParroquia;
	}

	/**
	* Set coordenadax
	*
	*
	*
	* @parámetro String $coordenadax
	* @return Coordenadax
	*/
	public function setCoordenadax($coordenadax)
	{
	  $this->coordenadax = (String) $coordenadax;
	    return $this;
	}

	/**
	* Get coordenadax
	*
	* @return null|String
	*/
	public function getCoordenadax()
	{
		return $this->coordenadax;
	}

	/**
	* Set coordenaday
	*
	*
	*
	* @parámetro String $coordenaday
	* @return Coordenaday
	*/
	public function setCoordenaday($coordenaday)
	{
	  $this->coordenaday = (String) $coordenaday;
	    return $this;
	}

	/**
	* Get coordenaday
	*
	* @return null|String
	*/
	public function getCoordenaday()
	{
		return $this->coordenaday;
	}

	/**
	* Set coordenadaz
	*
	*
	*
	* @parámetro String $coordenadaz
	* @return Coordenadaz
	*/
	public function setCoordenadaz($coordenadaz)
	{
	  $this->coordenadaz = (String) $coordenadaz;
	    return $this;
	}

	/**
	* Get coordenadaz
	*
	* @return null|String
	*/
	public function getCoordenadaz()
	{
		return $this->coordenadaz;
	}

	/**
	* Set idLugarInstalacion
	*
	*
	*
	* @parámetro Integer $idLugarInstalacion
	* @return IdLugarInstalacion
	*/
	public function setIdLugarInstalacion($idLugarInstalacion)
	{
	  $this->idLugarInstalacion = (Integer) $idLugarInstalacion;
	    return $this;
	}

	/**
	* Get idLugarInstalacion
	*
	* @return null|Integer
	*/
	public function getIdLugarInstalacion()
	{
		return $this->idLugarInstalacion;
	}

	/**
	* Set numeroLugarInstalacion
	*
	*
	*
	* @parámetro Integer $numeroLugarInstalacion
	* @return NumeroLugarInstalacion
	*/
	public function setNumeroLugarInstalacion($numeroLugarInstalacion)
	{
	  $this->numeroLugarInstalacion = (Integer) $numeroLugarInstalacion;
	    return $this;
	}

	/**
	* Get numeroLugarInstalacion
	*
	* @return null|Integer
	*/
	public function getNumeroLugarInstalacion()
	{
		return $this->numeroLugarInstalacion;
	}

	/**
	* Set idPlaga
	*
	*
	*
	* @parámetro Integer $idPlaga
	* @return IdPlaga
	*/
	public function setIdPlaga($idPlaga)
	{
	  $this->idPlaga = (Integer) $idPlaga;
	    return $this;
	}

	/**
	* Get idPlaga
	*
	* @return null|Integer
	*/
	public function getIdPlaga()
	{
		return $this->idPlaga;
	}

	/**
	* Set idTipoTrampa
	*
	*
	*
	* @parámetro Integer $idTipoTrampa
	* @return IdTipoTrampa
	*/
	public function setIdTipoTrampa($idTipoTrampa)
	{
	  $this->idTipoTrampa = (Integer) $idTipoTrampa;
	    return $this;
	}

	/**
	* Get idTipoTrampa
	*
	* @return null|Integer
	*/
	public function getIdTipoTrampa()
	{
		return $this->idTipoTrampa;
	}

	/**
	* Set idTipoAtrayente
	*
	*
	*
	* @parámetro Integer $idTipoAtrayente
	* @return IdTipoAtrayente
	*/
	public function setIdTipoAtrayente($idTipoAtrayente)
	{
	  $this->idTipoAtrayente = (Integer) $idTipoAtrayente;
	    return $this;
	}

	/**
	* Get idTipoAtrayente
	*
	* @return null|Integer
	*/
	public function getIdTipoAtrayente()
	{
		return $this->idTipoAtrayente;
	}

	/**
	* Set estadoTrampa
	*
	*
	*
	* @parámetro String $estadoTrampa
	* @return EstadoTrampa
	*/
	public function setEstadoTrampa($estadoTrampa)
	{
	  $this->estadoTrampa = (String) $estadoTrampa;
	    return $this;
	}

	/**
	* Get estadoTrampa
	*
	* @return null|String
	*/
	public function getEstadoTrampa()
	{
		return $this->estadoTrampa;
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
	* Set identificadorTecnico
	*
	*
	*
	* @parámetro String $identificadorTecnico
	* @return IdentificadorTecnico
	*/
	public function setIdentificadorTecnico($identificadorTecnico)
	{
	  $this->identificadorTecnico = (String) $identificadorTecnico;
	    return $this;
	}

	/**
	* Get identificadorTecnico
	*
	* @return null|String
	*/
	public function getIdentificadorTecnico()
	{
		return $this->identificadorTecnico;
	}

	/**
	* Set fechaModificacion
	*
	*
	*
	* @parámetro Date $fechaModificacion
	* @return FechaModificacion
	*/
	public function setFechaModificacion($fechaModificacion)
	{
	  $this->fechaModificacion = (String) $fechaModificacion;
	    return $this;
	}

	/**
	* Get fechaModificacion
	*
	* @return null|Date
	*/
	public function getFechaModificacion()
	{
		return $this->fechaModificacion;
	}

	/**
	* Set codigoProgramaEspecifico
	*
	*
	*
	* @parámetro String $codigoProgramaEspecifico
	* @return CodigoProgramaEspecifico
	*/
	public function setCodigoProgramaEspecifico($codigoProgramaEspecifico)
	{
	  $this->codigoProgramaEspecifico = (String) $codigoProgramaEspecifico;
	    return $this;
	}

	/**
	* Get codigoProgramaEspecifico
	*
	* @return null|String
	*/
	public function getCodigoProgramaEspecifico()
	{
		return $this->codigoProgramaEspecifico;
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
	* @return AdministracionTrampasModelo
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
