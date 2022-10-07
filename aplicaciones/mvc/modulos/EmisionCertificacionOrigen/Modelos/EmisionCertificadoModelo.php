<?php
 /**
 * Modelo EmisionCertificadoModelo
 *
 * Este archivo se complementa con el archivo   EmisionCertificadoLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    EmisionCertificadoModelo
 * @package EmisionCertificacionOrigen
 * @subpackage Modelos
 */
  namespace Agrodb\EmisionCertificacionOrigen\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class EmisionCertificadoModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
		protected $idEmisionCertificado;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Sitio de origen
		*/
		protected $sitioOrigen;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* aread_origen
		*/
		protected $areaOrigen;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Identificador destino
		*/
		protected $identificadorDestino;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Razon social destino
		*/
		protected $razonSocialDestino;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* provincia de destino
		*/
		protected $provinciaDestino;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Cantón destino
		*/
		protected $cantonDestino;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $parroquiaDestino;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de movilización
		*/
		protected $identificadorMovilizacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Condiciones del contenedor
		*/
		protected $contenedor;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de creación del registro
		*/
		protected $fechaCreacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Estado del registro
		*/
		protected $estado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $identificadorOperador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Dirección de destino
		*/
		protected $direccionDestino;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Número de certificado
		*/
		protected $numeroCertificado; 
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * Ruta certificado
		 */
		protected $rutaCertificado;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * id dato vehiculo
		 */
		protected $idDatoVehiculo;
		
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * fecha vigencia
		 */
		protected $fechaVigencia;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * fecha emision
		 */
		protected $fechaEmision;
		

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_emision_certificacion_origen";

	/**
	* Nombre de la tabla: emision_certificado
	* 
	 */
	Private $tabla="emision_certificado";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_emision_certificado";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_emision_certificacion_origen"."emision_certificado_id_emision_certificado_seq'; 



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
		throw new \Exception('Clase Modelo: EmisionCertificadoModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: EmisionCertificadoModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_emision_certificacion_origen
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idEmisionCertificado
	*
	*Llave primaria de la tabla
	*
	* @parámetro Integer $idEmisionCertificado
	* @return IdEmisionCertificado
	*/
	public function setIdEmisionCertificado($idEmisionCertificado)
	{
	  $this->idEmisionCertificado = (Integer) $idEmisionCertificado;
	    return $this;
	}

	/**
	* Get idEmisionCertificado
	*
	* @return null|Integer
	*/
	public function getIdEmisionCertificado()
	{
		return $this->idEmisionCertificado;
	}

	/**
	* Set sitioOrigen
	*
	*Sitio de origen
	*
	* @parámetro Integer $sitioOrigen
	* @return SitioOrigen
	*/
	public function setSitioOrigen($sitioOrigen)
	{
	  $this->sitioOrigen = (Integer) $sitioOrigen;
	    return $this;
	}

	/**
	* Get sitioOrigen
	*
	* @return null|Integer
	*/
	public function getSitioOrigen()
	{
		return $this->sitioOrigen;
	}

	/**
	* Set areaOrigen
	*
	*aread_origen
	*
	* @parámetro Integer $areaOrigen
	* @return AreaOrigen
	*/
	public function setAreaOrigen($areaOrigen)
	{
	  $this->areaOrigen = (Integer) $areaOrigen;
	    return $this;
	}

	/**
	* Get areaOrigen
	*
	* @return null|Integer
	*/
	public function getAreaOrigen()
	{
		return $this->areaOrigen;
	}

	/**
	* Set identificadorDestino
	*
	*Identificador destino
	*
	* @parámetro String $identificadorDestino
	* @return IdentificadorDestino
	*/
	public function setIdentificadorDestino($identificadorDestino)
	{
	  $this->identificadorDestino = (String) $identificadorDestino;
	    return $this;
	}

	/**
	* Get identificadorDestino
	*
	* @return null|String
	*/
	public function getIdentificadorDestino()
	{
		return $this->identificadorDestino;
	}

	/**
	* Set razonSocialDestino
	*
	*Razon social destino
	*
	* @parámetro String $razonSocialDestino
	* @return RazonSocialDestino
	*/
	public function setRazonSocialDestino($razonSocialDestino)
	{
	  $this->razonSocialDestino = (String) $razonSocialDestino;
	    return $this;
	}

	/**
	* Get razonSocialDestino
	*
	* @return null|String
	*/
	public function getRazonSocialDestino()
	{
		return $this->razonSocialDestino;
	}

	/**
	* Set provinciaDestino
	*
	*provincia de destino
	*
	* @parámetro Integer $provinciaDestino
	* @return ProvinciaDestino
	*/
	public function setProvinciaDestino($provinciaDestino)
	{
	  $this->provinciaDestino = (Integer) $provinciaDestino;
	    return $this;
	}

	/**
	* Get provinciaDestino
	*
	* @return null|Integer
	*/
	public function getProvinciaDestino()
	{
		return $this->provinciaDestino;
	}

	/**
	* Set cantonDestino
	*
	*Cantón destino
	*
	* @parámetro Integer $cantonDestino
	* @return CantonDestino
	*/
	public function setCantonDestino($cantonDestino)
	{
	  $this->cantonDestino = (Integer) $cantonDestino;
	    return $this;
	}

	/**
	* Get cantonDestino
	*
	* @return null|Integer
	*/
	public function getCantonDestino()
	{
		return $this->cantonDestino;
	}

	/**
	* Set parroquiaDestino
	*
	*
	*
	* @parámetro Integer $parroquiaDestino
	* @return ParroquiaDestino
	*/
	public function setParroquiaDestino($parroquiaDestino)
	{
	  $this->parroquiaDestino = (Integer) $parroquiaDestino;
	    return $this;
	}

	/**
	* Get parroquiaDestino
	*
	* @return null|Integer
	*/
	public function getParroquiaDestino()
	{
		return $this->parroquiaDestino;
	}

	/**
	* Set identificadorMovilizacion
	*
	*Identificador de movilización
	*
	* @parámetro String $identificadorMovilizacion
	* @return IdentificadorMovilizacion
	*/
	public function setIdentificadorMovilizacion($identificadorMovilizacion)
	{
	  $this->identificadorMovilizacion = (String) $identificadorMovilizacion;
	    return $this;
	}

	/**
	* Get identificadorMovilizacion
	*
	* @return null|String
	*/
	public function getIdentificadorMovilizacion()
	{
		return $this->identificadorMovilizacion;
	}

	/**
	* Set contenedor
	*
	*Condiciones del contenedor
	*
	* @parámetro String $contenedor
	* @return Contenedor
	*/
	public function setContenedor($contenedor)
	{
	  $this->contenedor = (String) $contenedor;
	    return $this;
	}

	/**
	* Get contenedor
	*
	* @return null|String
	*/
	public function getContenedor()
	{
		return $this->contenedor;
	}

	/**
	* Set fechaCreacion
	*
	*Fecha de creación del registro
	*
	* @parámetro Date $fechaCreacion
	* @return FechaCreacion
	*/
	public function setFechaCreacion($fechaCreacion)
	{
	  $this->fechaCreacion = (String) $fechaCreacion;
	    return $this;
	}

	/**
	* Get fechaCreacion
	*
	* @return null|Date
	*/
	public function getFechaCreacion()
	{
		return $this->fechaCreacion;
	}

	/**
	* Set estado
	*
	*Estado del registro
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
	* Set direccionDestino
	*
	*Dirección de destino
	*
	* @parámetro String $direccionDestino
	* @return DireccionDestino
	*/
	public function setDireccionDestino($direccionDestino)
	{
	  $this->direccionDestino = (String) $direccionDestino;
	    return $this;
	}

	/**
	* Get direccionDestino
	*
	* @return null|String
	*/
	public function getDireccionDestino()
	{
		return $this->direccionDestino;
	}

	/**
	* Set numeroCertificado
	*
	*Número de certificado
	*
	* @parámetro String $numeroCertificado
	* @return NumeroCertificado
	*/
	public function setNumeroCertificado($numeroCertificado)
	{
	  $this->numeroCertificado = (String) $numeroCertificado;
	    return $this;
	}

	/**
	* Get numeroCertificado
	*
	* @return null|String
	*/
	public function getNumeroCertificado()
	{
		return $this->numeroCertificado;
	}
	/**
	 * Set rutaCertificado
	 *
	 * @parámetro String $numeroCertificado
	 * @return RutaCertificado
	 */
	public function setRutaCertificado($rutaCertificado)
	{
	    $this->rutaCertificado = (String) $rutaCertificado;
	    return $this;
	}
	
	/**
	 * Get ruta certificado
	 *
	 * @return null|String
	 */
	public function getRutaCertificado()
	{
	    return $this->rutaCertificado;
	} 
	/**
	 * Set idDatoVehiculo
	 *
	 * @parámetro String $idDatoVehiculo
	 * @return IdDatoVehiculo
	 */
	public function setIdDatoVehiculo($idDatoVehiculo)
	{
	    $this->idDatoVehiculo = (String) $idDatoVehiculo;
	    return $this;
	}
	
	/**
	 * Get $idDatoVehiculo
	 *
	 * @return null|String
	 */
	public function getIdDatoVehiculo()
	{
	    return $this->idDatoVehiculo;
	}
	
	
	
	/**
	 * Set fechaVigencia
	 *
	 * @parámetro String $fechaVigencia
	 * @return FechaVigencia
	 */
	public function setFechaVigencia($fechaVigencia)
	{
	    $this->fechaVigencia = (String) $fechaVigencia;
	    return $this;
	}
	
	/**
	 * Get $fechaVigencia
	 *
	 * @return null|String
	 */
	public function getFechaVigencia()
	{
	    return $this->fechaVigencia;
	}
	
	/**
	 * Set fechaEmision
	 *
	 * @parámetro String $fechaEmision
	 * @return FechaEmision
	 */
	public function setFechaEmision($fechaEmision)
	{
	    $this->fechaEmision = (String) $fechaEmision;
	    return $this;
	}
	
	/**
	 * Get $fechaEmision
	 *
	 * @return null|String
	 */
	public function getFechaEmision()
	{
	    return $this->fechaEmision;
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
	* @return EmisionCertificadoModelo
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
