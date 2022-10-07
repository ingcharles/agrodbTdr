<?php
 /**
 * Modelo OficinaRecaudacionModelo
 *
 * Este archivo se complementa con el archivo   OficinaRecaudacionLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-10-10
 * @uses    OficinaRecaudacionModelo
 * @package Financiero
 * @subpackage Modelos
 */
  namespace Agrodb\Financiero\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class OficinaRecaudacionModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idOficinaRecaudacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $ruc;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $provincia;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idProvincia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $oficina;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idOficina;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $numeroEstablecimiento;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $identificadorFirmante;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $rutaFirma;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idRegionZonal;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $puntoEmision;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $clavePfx;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $fechaCaducidadPfx;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $estadoRecaudador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $iva;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $firmaAutomatica;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_financiero";

	/**
	* Nombre de la tabla: oficina_recaudacion
	* 
	 */
	Private $tabla="oficina_recaudacion";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_oficina_recaudacion";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_financiero"."OficinaRecaudacion_id_oficina_recaudacion_seq'; 



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
		throw new \Exception('Clase Modelo: OficinaRecaudacionModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: OficinaRecaudacionModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_financiero
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idOficinaRecaudacion
	*
	*
	*
	* @parámetro Integer $idOficinaRecaudacion
	* @return IdOficinaRecaudacion
	*/
	public function setIdOficinaRecaudacion($idOficinaRecaudacion)
	{
	  $this->idOficinaRecaudacion = (Integer) $idOficinaRecaudacion;
	    return $this;
	}

	/**
	* Get idOficinaRecaudacion
	*
	* @return null|Integer
	*/
	public function getIdOficinaRecaudacion()
	{
		return $this->idOficinaRecaudacion;
	}

	/**
	* Set ruc
	*
	*
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
	* Set provincia
	*
	*
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
	* Set oficina
	*
	*
	*
	* @parámetro String $oficina
	* @return Oficina
	*/
	public function setOficina($oficina)
	{
	  $this->oficina = (String) $oficina;
	    return $this;
	}

	/**
	* Get oficina
	*
	* @return null|String
	*/
	public function getOficina()
	{
		return $this->oficina;
	}

	/**
	* Set idOficina
	*
	*
	*
	* @parámetro Integer $idOficina
	* @return IdOficina
	*/
	public function setIdOficina($idOficina)
	{
	  $this->idOficina = (Integer) $idOficina;
	    return $this;
	}

	/**
	* Get idOficina
	*
	* @return null|Integer
	*/
	public function getIdOficina()
	{
		return $this->idOficina;
	}

	/**
	* Set numeroEstablecimiento
	*
	*
	*
	* @parámetro String $numeroEstablecimiento
	* @return NumeroEstablecimiento
	*/
	public function setNumeroEstablecimiento($numeroEstablecimiento)
	{
	  $this->numeroEstablecimiento = (String) $numeroEstablecimiento;
	    return $this;
	}

	/**
	* Get numeroEstablecimiento
	*
	* @return null|String
	*/
	public function getNumeroEstablecimiento()
	{
		return $this->numeroEstablecimiento;
	}

	/**
	* Set identificadorFirmante
	*
	*
	*
	* @parámetro String $identificadorFirmante
	* @return IdentificadorFirmante
	*/
	public function setIdentificadorFirmante($identificadorFirmante)
	{
	  $this->identificadorFirmante = (String) $identificadorFirmante;
	    return $this;
	}

	/**
	* Get identificadorFirmante
	*
	* @return null|String
	*/
	public function getIdentificadorFirmante()
	{
		return $this->identificadorFirmante;
	}

	/**
	* Set rutaFirma
	*
	*
	*
	* @parámetro String $rutaFirma
	* @return RutaFirma
	*/
	public function setRutaFirma($rutaFirma)
	{
	  $this->rutaFirma = (String) $rutaFirma;
	    return $this;
	}

	/**
	* Get rutaFirma
	*
	* @return null|String
	*/
	public function getRutaFirma()
	{
		return $this->rutaFirma;
	}

	/**
	* Set idRegionZonal
	*
	*
	*
	* @parámetro Integer $idRegionZonal
	* @return IdRegionZonal
	*/
	public function setIdRegionZonal($idRegionZonal)
	{
	  $this->idRegionZonal = (Integer) $idRegionZonal;
	    return $this;
	}

	/**
	* Get idRegionZonal
	*
	* @return null|Integer
	*/
	public function getIdRegionZonal()
	{
		return $this->idRegionZonal;
	}

	/**
	* Set puntoEmision
	*
	*
	*
	* @parámetro String $puntoEmision
	* @return PuntoEmision
	*/
	public function setPuntoEmision($puntoEmision)
	{
	  $this->puntoEmision = (String) $puntoEmision;
	    return $this;
	}

	/**
	* Get puntoEmision
	*
	* @return null|String
	*/
	public function getPuntoEmision()
	{
		return $this->puntoEmision;
	}

	/**
	* Set clavePfx
	*
	*
	*
	* @parámetro String $clavePfx
	* @return ClavePfx
	*/
	public function setClavePfx($clavePfx)
	{
	  $this->clavePfx = (String) $clavePfx;
	    return $this;
	}

	/**
	* Get clavePfx
	*
	* @return null|String
	*/
	public function getClavePfx()
	{
		return $this->clavePfx;
	}

	/**
	* Set fechaCaducidadPfx
	*
	*
	*
	* @parámetro Date $fechaCaducidadPfx
	* @return FechaCaducidadPfx
	*/
	public function setFechaCaducidadPfx($fechaCaducidadPfx)
	{
	  $this->fechaCaducidadPfx = (String) $fechaCaducidadPfx;
	    return $this;
	}

	/**
	* Get fechaCaducidadPfx
	*
	* @return null|Date
	*/
	public function getFechaCaducidadPfx()
	{
		return $this->fechaCaducidadPfx;
	}

	/**
	* Set estadoRecaudador
	*
	*
	*
	* @parámetro String $estadoRecaudador
	* @return EstadoRecaudador
	*/
	public function setEstadoRecaudador($estadoRecaudador)
	{
	  $this->estadoRecaudador = (String) $estadoRecaudador;
	    return $this;
	}

	/**
	* Get estadoRecaudador
	*
	* @return null|String
	*/
	public function getEstadoRecaudador()
	{
		return $this->estadoRecaudador;
	}

	/**
	* Set iva
	*
	*
	*
	* @parámetro String $iva
	* @return Iva
	*/
	public function setIva($iva)
	{
	  $this->iva = (String) $iva;
	    return $this;
	}

	/**
	* Get iva
	*
	* @return null|String
	*/
	public function getIva()
	{
		return $this->iva;
	}

	/**
	* Set firmaAutomatica
	*
	*
	*
	* @parámetro String $firmaAutomatica
	* @return FirmaAutomatica
	*/
	public function setFirmaAutomatica($firmaAutomatica)
	{
	  $this->firmaAutomatica = (String) $firmaAutomatica;
	    return $this;
	}

	/**
	* Get firmaAutomatica
	*
	* @return null|String
	*/
	public function getFirmaAutomatica()
	{
		return $this->firmaAutomatica;
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
	* @return OficinaRecaudacionModelo
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
