<?php
 /**
 * Modelo InspeccionModelo
 *
 * Este archivo se complementa con el archivo   InspeccionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    InspeccionModelo
 * @package AdministrarOperacionesGuia
 * @subpackage Modelos
 */
namespace Agrodb\RevisionFormularios\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class InspeccionModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idInspeccion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idGrupo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $identificadorInspector;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idItemInspeccion;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $fechaInspeccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $rutaArchivo;
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
		protected $estado;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $revisionNumero;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $tipoElemento;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $orden;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idInspeccionTablet;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $identificadorTablet;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $versionBd;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $serial;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_revision_solicitudes";

	/**
	* Nombre de la tabla: inspeccion
	* 
	 */
	Private $tabla="inspeccion";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_inspeccion";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_revision_solicitudes"."inspeccion_id_inspeccion_seq'; 



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
		throw new \Exception('Clase Modelo: InspeccionModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: InspeccionModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_revision_solicitudes
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idInspeccion
	*
	*
	*
	* @parámetro Integer $idInspeccion
	* @return IdInspeccion
	*/
	public function setIdInspeccion($idInspeccion)
	{
	  $this->idInspeccion = (Integer) $idInspeccion;
	    return $this;
	}

	/**
	* Get idInspeccion
	*
	* @return null|Integer
	*/
	public function getIdInspeccion()
	{
		return $this->idInspeccion;
	}

	/**
	* Set idGrupo
	*
	*
	*
	* @parámetro Integer $idGrupo
	* @return IdGrupo
	*/
	public function setIdGrupo($idGrupo)
	{
	  $this->idGrupo = (Integer) $idGrupo;
	    return $this;
	}

	/**
	* Get idGrupo
	*
	* @return null|Integer
	*/
	public function getIdGrupo()
	{
		return $this->idGrupo;
	}

	/**
	* Set identificadorInspector
	*
	*
	*
	* @parámetro String $identificadorInspector
	* @return IdentificadorInspector
	*/
	public function setIdentificadorInspector($identificadorInspector)
	{
	  $this->identificadorInspector = (String) $identificadorInspector;
	    return $this;
	}

	/**
	* Get identificadorInspector
	*
	* @return null|String
	*/
	public function getIdentificadorInspector()
	{
		return $this->identificadorInspector;
	}

	/**
	* Set idItemInspeccion
	*
	*
	*
	* @parámetro Integer $idItemInspeccion
	* @return IdItemInspeccion
	*/
	public function setIdItemInspeccion($idItemInspeccion)
	{
	  $this->idItemInspeccion = (Integer) $idItemInspeccion;
	    return $this;
	}

	/**
	* Get idItemInspeccion
	*
	* @return null|Integer
	*/
	public function getIdItemInspeccion()
	{
		return $this->idItemInspeccion;
	}

	/**
	* Set fechaInspeccion
	*
	*
	*
	* @parámetro Date $fechaInspeccion
	* @return FechaInspeccion
	*/
	public function setFechaInspeccion($fechaInspeccion)
	{
	  $this->fechaInspeccion = (String) $fechaInspeccion;
	    return $this;
	}

	/**
	* Get fechaInspeccion
	*
	* @return null|Date
	*/
	public function getFechaInspeccion()
	{
		return $this->fechaInspeccion;
	}

	/**
	* Set rutaArchivo
	*
	*
	*
	* @parámetro String $rutaArchivo
	* @return RutaArchivo
	*/
	public function setRutaArchivo($rutaArchivo)
	{
	  $this->rutaArchivo = (String) $rutaArchivo;
	    return $this;
	}

	/**
	* Get rutaArchivo
	*
	* @return null|String
	*/
	public function getRutaArchivo()
	{
		return $this->rutaArchivo;
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
	* Set revisionNumero
	*
	*
	*
	* @parámetro Integer $revisionNumero
	* @return RevisionNumero
	*/
	public function setRevisionNumero($revisionNumero)
	{
	  $this->revisionNumero = (Integer) $revisionNumero;
	    return $this;
	}

	/**
	* Get revisionNumero
	*
	* @return null|Integer
	*/
	public function getRevisionNumero()
	{
		return $this->revisionNumero;
	}

	/**
	* Set tipoElemento
	*
	*
	*
	* @parámetro String $tipoElemento
	* @return TipoElemento
	*/
	public function setTipoElemento($tipoElemento)
	{
	  $this->tipoElemento = (String) $tipoElemento;
	    return $this;
	}

	/**
	* Get tipoElemento
	*
	* @return null|String
	*/
	public function getTipoElemento()
	{
		return $this->tipoElemento;
	}

	/**
	* Set orden
	*
	*
	*
	* @parámetro Integer $orden
	* @return Orden
	*/
	public function setOrden($orden)
	{
	  $this->orden = (Integer) $orden;
	    return $this;
	}

	/**
	* Get orden
	*
	* @return null|Integer
	*/
	public function getOrden()
	{
		return $this->orden;
	}

	/**
	* Set idInspeccionTablet
	*
	*
	*
	* @parámetro Integer $idInspeccionTablet
	* @return IdInspeccionTablet
	*/
	public function setIdInspeccionTablet($idInspeccionTablet)
	{
	  $this->idInspeccionTablet = (Integer) $idInspeccionTablet;
	    return $this;
	}

	/**
	* Get idInspeccionTablet
	*
	* @return null|Integer
	*/
	public function getIdInspeccionTablet()
	{
		return $this->idInspeccionTablet;
	}

	/**
	* Set identificadorTablet
	*
	*
	*
	* @parámetro String $identificadorTablet
	* @return IdentificadorTablet
	*/
	public function setIdentificadorTablet($identificadorTablet)
	{
	  $this->identificadorTablet = (String) $identificadorTablet;
	    return $this;
	}

	/**
	* Get identificadorTablet
	*
	* @return null|String
	*/
	public function getIdentificadorTablet()
	{
		return $this->identificadorTablet;
	}

	/**
	* Set versionBd
	*
	*
	*
	* @parámetro Integer $versionBd
	* @return VersionBd
	*/
	public function setVersionBd($versionBd)
	{
	  $this->versionBd = (Integer) $versionBd;
	    return $this;
	}

	/**
	* Get versionBd
	*
	* @return null|Integer
	*/
	public function getVersionBd()
	{
		return $this->versionBd;
	}

	/**
	* Set serial
	*
	*
	*
	* @parámetro Integer $serial
	* @return Serial
	*/
	public function setSerial($serial)
	{
	  $this->serial = (Integer) $serial;
	    return $this;
	}

	/**
	* Get serial
	*
	* @return null|Integer
	*/
	public function getSerial()
	{
		return $this->serial;
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
	* @return InspeccionModelo
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
