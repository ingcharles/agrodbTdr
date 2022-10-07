<?php
 /**
 * Modelo DetalleEmisionCertificadoModelo
 *
 * Este archivo se complementa con el archivo   DetalleEmisionCertificadoLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    DetalleEmisionCertificadoModelo
 * @package EmisionCertificacionOrigen
 * @subpackage Modelos
 */
  namespace Agrodb\EmisionCertificacionOrigen\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class DetalleEmisionCertificadoModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
		protected $idDetalleEmisionCertificado;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave foránea de la tabla emision_certificado
		*/
		protected $idEmisionCertificado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Producto a movilizar
		*/
		protected $productoMovilizar;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Tipo especie
		*/
		protected $tipoEspecie;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Tipo producto a movilizar el canal
		*/
		protected $tipoProductoMovilizarCanal;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Código de la canal
		*/
		protected $codigoCanal;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Destino
		*/
		protected $destino;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Subproducto
		*/
		protected $subproducto;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Saldo disponible
		*/
		protected $saldoDisponible;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Cantidad movilizar
		*/
		protected $cantidadMovilizar;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave foránea de la tabla
		*/
		protected $idProductos;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de producción
		*/
		protected $fechaProduccion;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de creación del registro
		*/
		protected $fechaCreacion;
		protected $tipoMovilizacionCanal;

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
	* Nombre de la tabla: detalle_emision_certificado
	* 
	 */
	Private $tabla="detalle_emision_certificado";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_detalle_emision_certificado";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_emision_certificacion_origen"."detalle_emision_certificado_id_detalle_emision_certificado_seq'; 



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
		throw new \Exception('Clase Modelo: DetalleEmisionCertificadoModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: DetalleEmisionCertificadoModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idDetalleEmisionCertificado
	*
	*Llave primaria de la tabla
	*
	* @parámetro Integer $idDetalleEmisionCertificado
	* @return IdDetalleEmisionCertificado
	*/
	public function setIdDetalleEmisionCertificado($idDetalleEmisionCertificado)
	{
	  $this->idDetalleEmisionCertificado = (Integer) $idDetalleEmisionCertificado;
	    return $this;
	}

	/**
	* Get idDetalleEmisionCertificado
	*
	* @return null|Integer
	*/
	public function getIdDetalleEmisionCertificado()
	{
		return $this->idDetalleEmisionCertificado;
	}

	/**
	* Set idEmisionCertificado
	*
	*Llave foránea de la tabla emision_certificado
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
	* Set productoMovilizar
	*
	*Producto a movilizar
	*
	* @parámetro String $productoMovilizar
	* @return ProductoMovilizar
	*/
	public function setProductoMovilizar($productoMovilizar)
	{
	  $this->productoMovilizar = (String) $productoMovilizar;
	    return $this;
	}

	/**
	* Get productoMovilizar
	*
	* @return null|String
	*/
	public function getProductoMovilizar()
	{
		return $this->productoMovilizar;
	}

	/**
	* Set tipoEspecie
	*
	*Tipo especie
	*
	* @parámetro String $tipoEspecie
	* @return TipoEspecie
	*/
	public function setTipoEspecie($tipoEspecie)
	{
	  $this->tipoEspecie = (String) $tipoEspecie;
	    return $this;
	}

	/**
	* Get tipoEspecie
	*
	* @return null|String
	*/
	public function getTipoEspecie()
	{
		return $this->tipoEspecie;
	}

	/**
	* Set tipoProductoMovilizarCanal
	*
	*Tipo producto a movilizar el canal
	*
	* @parámetro String $tipoProductoMovilizarCanal
	* @return TipoProductoMovilizarCanal
	*/
	public function setTipoProductoMovilizarCanal($tipoProductoMovilizarCanal)
	{
	  $this->tipoProductoMovilizarCanal = (String) $tipoProductoMovilizarCanal;
	    return $this;
	}

	/**
	* Get tipoProductoMovilizarCanal
	*
	* @return null|String
	*/
	public function getTipoProductoMovilizarCanal()
	{
		return $this->tipoProductoMovilizarCanal;
	}

	/**
	* Set codigoCanal
	*
	*Código de la canal
	*
	* @parámetro String $codigoCanal
	* @return CodigoCanal
	*/
	public function setCodigoCanal($codigoCanal)
	{
	  $this->codigoCanal = (String) $codigoCanal;
	    return $this;
	}

	/**
	* Get codigoCanal
	*
	* @return null|String
	*/
	public function getCodigoCanal()
	{
		return $this->codigoCanal;
	}

	/**
	* Set destino
	*
	*Destino
	*
	* @parámetro String $destino
	* @return Destino
	*/
	public function setDestino($destino)
	{
	  $this->destino = (String) $destino;
	    return $this;
	}

	/**
	* Get destino
	*
	* @return null|String
	*/
	public function getDestino()
	{
		return $this->destino;
	}

	/**
	* Set subproducto
	*
	*Subproducto
	*
	* @parámetro String $subproducto
	* @return Subproducto
	*/
	public function setSubproducto($subproducto)
	{
	  $this->subproducto = (String) $subproducto;
	    return $this;
	}

	/**
	* Get subproducto
	*
	* @return null|String
	*/
	public function getSubproducto()
	{
		return $this->subproducto;
	}

	/**
	* Set saldoDisponible
	*
	*Saldo disponible
	*
	* @parámetro Integer $saldoDisponible
	* @return SaldoDisponible
	*/
	public function setSaldoDisponible($saldoDisponible)
	{
	  $this->saldoDisponible = (Integer) $saldoDisponible;
	    return $this;
	}

	/**
	* Get saldoDisponible
	*
	* @return null|Integer
	*/
	public function getSaldoDisponible()
	{
		return $this->saldoDisponible;
	}

	/**
	* Set cantidadMovilizar
	*
	*Cantidad movilizar
	*
	* @parámetro Integer $cantidadMovilizar
	* @return CantidadMovilizar
	*/
	public function setCantidadMovilizar($cantidadMovilizar)
	{
	  $this->cantidadMovilizar = (Integer) $cantidadMovilizar;
	    return $this;
	}

	/**
	* Get cantidadMovilizar
	*
	* @return null|Integer
	*/
	public function getCantidadMovilizar()
	{
		return $this->cantidadMovilizar;
	}

	/**
	* Set idProductos
	*
	*Llave foránea de la tabla
	*
	* @parámetro Integer $idProductos
	* @return IdProductos
	*/
	public function setIdProductos($idProductos)
	{
	  $this->idProductos = (Integer) $idProductos;
	    return $this;
	}

	/**
	* Get idProductos
	*
	* @return null|Integer
	*/
	public function getIdProductos()
	{
		return $this->idProductos;
	}

	/**
	* Set fechaProduccion
	*
	*Fecha de producción
	*
	* @parámetro Date $fechaProduccion
	* @return FechaProduccion
	*/
	public function setFechaProduccion($fechaProduccion)
	{
	  $this->fechaProduccion = (String) $fechaProduccion;
	    return $this;
	}

	/**
	* Get fechaProduccion
	*
	* @return null|Date
	*/
	public function getFechaProduccion()
	{
		return $this->fechaProduccion;
	}

	/**
	* Set fechaCreación
	*
	*Fecha de creación del registro
	*
	* @parámetro Date $fechaCreación
	* @return FechaCreacion
	*/
	public function setFechaCreacion($fechaCreacion)
	{
	  $this->fechaCreacion = (String) $fechaCreacion;
	    return $this;
	}

	/**
	* Get fechaCreación
	*
	* @return null|Date
	*/
	public function getFechaCreacion()
	{
		return $this->fechaCreacion;
	}
	/**
	 
	 */
	public function setTipoMovilizacionCanal($tipoMovilizacionCanal)
	{
	    $this->tipoMovilizacionCanal = (String) $tipoMovilizacionCanal;
	    return $this;
	}
	
	/**
	 * Get fechaCreación
	 *
	 * @return null|String
	 */
	public function getTipoMovilizacionCanal()
	{
	    return $this->tipoMovilizacionCanal;
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
	public function borrarPorParametro($param, $value)
	{
	    return parent::borrar($param . " = " . $value);
	}
	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return DetalleEmisionCertificadoModelo
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
