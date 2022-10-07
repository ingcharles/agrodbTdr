<?php
 /**
 * Modelo Certificacionf11Modelo
 *
 * Este archivo se complementa con el archivo   Certificacionf11LogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-21
 * @uses    Certificacionf11Modelo
 * @package FormulariosInspeccion
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class Certificacionf11Modelo extends ModeloBase 
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
		* Reporte
		*/
		protected $numeroReporte;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* RUC de exportador
		*/
		protected $ruc;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Exportador
		*/
		protected $exportador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Sitio de inspección
		*/
		protected $sitioInspeccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Provincia
		*/
		protected $provincia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Cantón
		*/
		protected $canton;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Parroquia
		*/
		protected $parroquia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Importador
		*/
		protected $importador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Dirección de importador
		*/
		protected $direccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Medio de transporte
		*/
		protected $medioTransporte;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de embarque, vuelo o envío
		*/
		protected $fechaEmbarque;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Observaciones
		*/
		protected $observaciones;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Representante
		*/
		protected $representante;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha vigencia
		*/
		protected $fechaVigencia;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha inspección
		*/
		protected $fechaInspeccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Cédula de inspector
		*/
		protected $usuarioId;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Inspector
		*/
		protected $usuario;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $tabletId;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $tabletVersionBase;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $estadoF11;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $observacionF11;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $fechaIngresoGuia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que identifica si el reporte ya fue utilizado para un certificado fitosanitario
		*/
		protected $utilizadoCfe;

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
	* Nombre de la tabla: certificacionf11
	* 
	 */
	Private $tabla="certificacionf11";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id";



	/**
	*Secuencia
*/
		 private $secuencial = 'f_inspeccion"."Certificacionf11_id_seq'; 



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
		throw new \Exception('Clase Modelo: Certificacionf11Modelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: Certificacionf11Modelo. Propiedad especificada invalida: get'.$name);
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
	* Set numeroReporte
	*
	*Reporte
	*
	* @parámetro String $numeroReporte
	* @return NumeroReporte
	*/
	public function setNumeroReporte($numeroReporte)
	{
	  $this->numeroReporte = (String) $numeroReporte;
	    return $this;
	}

	/**
	* Get numeroReporte
	*
	* @return null|String
	*/
	public function getNumeroReporte()
	{
		return $this->numeroReporte;
	}

	/**
	* Set ruc
	*
	*RUC de exportador
	*
	* @parámetro String $ruc
	* @return Ruc
	*/
	public function setRuc($ruc)
	{
	  $this->ruc = (String) $ruc;
	    return $this;
	}

	/**
	* Get ruc
	*
	* @return null|String
	*/
	public function getRuc()
	{
		return $this->ruc;
	}

	/**
	* Set exportador
	*
	*Exportador
	*
	* @parámetro String $exportador
	* @return Exportador
	*/
	public function setExportador($exportador)
	{
	  $this->exportador = (String) $exportador;
	    return $this;
	}

	/**
	* Get exportador
	*
	* @return null|String
	*/
	public function getExportador()
	{
		return $this->exportador;
	}

	/**
	* Set sitioInspeccion
	*
	*Sitio de inspección
	*
	* @parámetro String $sitioInspeccion
	* @return SitioInspeccion
	*/
	public function setSitioInspeccion($sitioInspeccion)
	{
	  $this->sitioInspeccion = (String) $sitioInspeccion;
	    return $this;
	}

	/**
	* Get sitioInspeccion
	*
	* @return null|String
	*/
	public function getSitioInspeccion()
	{
		return $this->sitioInspeccion;
	}

	/**
	* Set provincia
	*
	*Provincia
	*
	* @parámetro String $provincia
	* @return Provincia
	*/
	public function setProvincia($provincia)
	{
	  $this->provincia = (String) $provincia;
	    return $this;
	}

	/**
	* Get provincia
	*
	* @return null|String
	*/
	public function getProvincia()
	{
		return $this->provincia;
	}

	/**
	* Set canton
	*
	*Cantón
	*
	* @parámetro String $canton
	* @return Canton
	*/
	public function setCanton($canton)
	{
	  $this->canton = (String) $canton;
	    return $this;
	}

	/**
	* Get canton
	*
	* @return null|String
	*/
	public function getCanton()
	{
		return $this->canton;
	}

	/**
	* Set parroquia
	*
	*Parroquia
	*
	* @parámetro String $parroquia
	* @return Parroquia
	*/
	public function setParroquia($parroquia)
	{
	  $this->parroquia = (String) $parroquia;
	    return $this;
	}

	/**
	* Get parroquia
	*
	* @return null|String
	*/
	public function getParroquia()
	{
		return $this->parroquia;
	}

	/**
	* Set importador
	*
	*Importador
	*
	* @parámetro String $importador
	* @return Importador
	*/
	public function setImportador($importador)
	{
	  $this->importador = (String) $importador;
	    return $this;
	}

	/**
	* Get importador
	*
	* @return null|String
	*/
	public function getImportador()
	{
		return $this->importador;
	}

	/**
	* Set direccion
	*
	*Dirección de importador
	*
	* @parámetro String $direccion
	* @return Direccion
	*/
	public function setDireccion($direccion)
	{
	  $this->direccion = (String) $direccion;
	    return $this;
	}

	/**
	* Get direccion
	*
	* @return null|String
	*/
	public function getDireccion()
	{
		return $this->direccion;
	}

	/**
	* Set medioTransporte
	*
	*Medio de transporte
	*
	* @parámetro String $medioTransporte
	* @return MedioTransporte
	*/
	public function setMedioTransporte($medioTransporte)
	{
	  $this->medioTransporte = (String) $medioTransporte;
	    return $this;
	}

	/**
	* Get medioTransporte
	*
	* @return null|String
	*/
	public function getMedioTransporte()
	{
		return $this->medioTransporte;
	}

	/**
	* Set fechaEmbarque
	*
	*Fecha de embarque, vuelo o envío
	*
	* @parámetro Date $fechaEmbarque
	* @return FechaEmbarque
	*/
	public function setFechaEmbarque($fechaEmbarque)
	{
	  $this->fechaEmbarque = (String) $fechaEmbarque;
	    return $this;
	}

	/**
	* Get fechaEmbarque
	*
	* @return null|Date
	*/
	public function getFechaEmbarque()
	{
		return $this->fechaEmbarque;
	}

	/**
	* Set observaciones
	*
	*Observaciones
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
	* Set representante
	*
	*Representante
	*
	* @parámetro String $representante
	* @return Representante
	*/
	public function setRepresentante($representante)
	{
	  $this->representante = (String) $representante;
	    return $this;
	}

	/**
	* Get representante
	*
	* @return null|String
	*/
	public function getRepresentante()
	{
		return $this->representante;
	}

	/**
	* Set fechaVigencia
	*
	*Fecha vigencia
	*
	* @parámetro Date $fechaVigencia
	* @return FechaVigencia
	*/
	public function setFechaVigencia($fechaVigencia)
	{
	  $this->fechaVigencia = (String) $fechaVigencia;
	    return $this;
	}

	/**
	* Get fechaVigencia
	*
	* @return null|Date
	*/
	public function getFechaVigencia()
	{
		return $this->fechaVigencia;
	}

	/**
	* Set fechaInspeccion
	*
	*Fecha inspección
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
	* Set usuarioId
	*
	*Cédula de inspector
	*
	* @parámetro String $usuarioId
	* @return UsuarioId
	*/
	public function setUsuarioId($usuarioId)
	{
	  $this->usuarioId = (String) $usuarioId;
	    return $this;
	}

	/**
	* Get usuarioId
	*
	* @return null|String
	*/
	public function getUsuarioId()
	{
		return $this->usuarioId;
	}

	/**
	* Set usuario
	*
	*Inspector
	*
	* @parámetro String $usuario
	* @return Usuario
	*/
	public function setUsuario($usuario)
	{
	  $this->usuario = (String) $usuario;
	    return $this;
	}

	/**
	* Get usuario
	*
	* @return null|String
	*/
	public function getUsuario()
	{
		return $this->usuario;
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
	* Set tabletVersionBase
	*
	*
	*
	* @parámetro String $tabletVersionBase
	* @return TabletVersionBase
	*/
	public function setTabletVersionBase($tabletVersionBase)
	{
	  $this->tabletVersionBase = (String) $tabletVersionBase;
	    return $this;
	}

	/**
	* Get tabletVersionBase
	*
	* @return null|String
	*/
	public function getTabletVersionBase()
	{
		return $this->tabletVersionBase;
	}

	/**
	* Set estadoF11
	*
	*
	*
	* @parámetro String $estadoF11
	* @return EstadoF11
	*/
	public function setEstadoF11($estadoF11)
	{
	  $this->estadoF11 = (String) $estadoF11;
	    return $this;
	}

	/**
	* Get estadoF11
	*
	* @return null|String
	*/
	public function getEstadoF11()
	{
		return $this->estadoF11;
	}

	/**
	* Set observacionF11
	*
	*
	*
	* @parámetro String $observacionF11
	* @return ObservacionF11
	*/
	public function setObservacionF11($observacionF11)
	{
	  $this->observacionF11 = (String) $observacionF11;
	    return $this;
	}

	/**
	* Get observacionF11
	*
	* @return null|String
	*/
	public function getObservacionF11()
	{
		return $this->observacionF11;
	}

	/**
	* Set fechaIngresoGuia
	*
	*
	*
	* @parámetro Date $fechaIngresoGuia
	* @return FechaIngresoGuia
	*/
	public function setFechaIngresoGuia($fechaIngresoGuia)
	{
	  $this->fechaIngresoGuia = (String) $fechaIngresoGuia;
	    return $this;
	}

	/**
	* Get fechaIngresoGuia
	*
	* @return null|Date
	*/
	public function getFechaIngresoGuia()
	{
		return $this->fechaIngresoGuia;
	}

	/**
	* Set utilizadoCfe
	*
	*Campo que identifica si el reporte ya fue utilizado para un certificado fitosanitario
	*
	* @parámetro String $utilizadoCfe
	* @return UtilizadoCfe
	*/
	public function setUtilizadoCfe($utilizadoCfe)
	{
	  $this->utilizadoCfe = (String) $utilizadoCfe;
	    return $this;
	}

	/**
	* Get utilizadoCfe
	*
	* @return null|String
	*/
	public function getUtilizadoCfe()
	{
		return $this->utilizadoCfe;
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
	* @return Certificacionf11Modelo
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
