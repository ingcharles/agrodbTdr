<?php
 /**
 * Modelo DetallePagoModelo
 *
 * Este archivo se complementa con el archivo   DetallePagoLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-10-10
 * @uses    DetallePagoModelo
 * @package Financiero
 * @subpackage Modelos
 */
  namespace Agrodb\Financiero\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class DetallePagoModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idDetalle;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idPago;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idServicio;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $conceptoOrden;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $cantidad;
		/**
		* @var Decimal
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $precioUnitario;
		/**
		* @var Decimal
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $descuento;
		/**
		* @var Decimal
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $iva;
		/**
		* @var Decimal
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $total;
		/**
		* @var Decimal
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $subsidio;

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
	* Nombre de la tabla: detalle_pago
	* 
	 */
	Private $tabla="detalle_pago";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_detalle";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_financiero"."detalle_pago_id_detalle_seq'; 



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
		throw new \Exception('Clase Modelo: DetallePagoModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: DetallePagoModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idDetalle
	*
	*
	*
	* @parámetro Integer $idDetalle
	* @return IdDetalle
	*/
	public function setIdDetalle($idDetalle)
	{
	  $this->idDetalle = (Integer) $idDetalle;
	    return $this;
	}

	/**
	* Get idDetalle
	*
	* @return null|Integer
	*/
	public function getIdDetalle()
	{
		return $this->idDetalle;
	}

	/**
	* Set idPago
	*
	*
	*
	* @parámetro Integer $idPago
	* @return IdPago
	*/
	public function setIdPago($idPago)
	{
	  $this->idPago = (Integer) $idPago;
	    return $this;
	}

	/**
	* Get idPago
	*
	* @return null|Integer
	*/
	public function getIdPago()
	{
		return $this->idPago;
	}

	/**
	* Set idServicio
	*
	*
	*
	* @parámetro Integer $idServicio
	* @return IdServicio
	*/
	public function setIdServicio($idServicio)
	{
	  $this->idServicio = (Integer) $idServicio;
	    return $this;
	}

	/**
	* Get idServicio
	*
	* @return null|Integer
	*/
	public function getIdServicio()
	{
		return $this->idServicio;
	}

	/**
	* Set conceptoOrden
	*
	*
	*
	* @parámetro String $conceptoOrden
	* @return ConceptoOrden
	*/
	public function setConceptoOrden($conceptoOrden)
	{
	  $this->conceptoOrden = (String) $conceptoOrden;
	    return $this;
	}

	/**
	* Get conceptoOrden
	*
	* @return null|String
	*/
	public function getConceptoOrden()
	{
		return $this->conceptoOrden;
	}

	/**
	* Set cantidad
	*
	*
	*
	* @parámetro String $cantidad
	* @return Cantidad
	*/
	public function setCantidad($cantidad)
	{
	  $this->cantidad = (String) $cantidad;
	    return $this;
	}

	/**
	* Get cantidad
	*
	* @return null|String
	*/
	public function getCantidad()
	{
		return $this->cantidad;
	}

	/**
	* Set precioUnitario
	*
	*
	*
	* @parámetro Decimal $precioUnitario
	* @return PrecioUnitario
	*/
	public function setPrecioUnitario($precioUnitario)
	{
	  $this->precioUnitario = (Double) $precioUnitario;
	    return $this;
	}

	/**
	* Get precioUnitario
	*
	* @return null|Decimal
	*/
	public function getPrecioUnitario()
	{
		return $this->precioUnitario;
	}

	/**
	* Set descuento
	*
	*
	*
	* @parámetro Decimal $descuento
	* @return Descuento
	*/
	public function setDescuento($descuento)
	{
	  $this->descuento = (Double) $descuento;
	    return $this;
	}

	/**
	* Get descuento
	*
	* @return null|Decimal
	*/
	public function getDescuento()
	{
		return $this->descuento;
	}

	/**
	* Set iva
	*
	*
	*
	* @parámetro Decimal $iva
	* @return Iva
	*/
	public function setIva($iva)
	{
	  $this->iva = (Double) $iva;
	    return $this;
	}

	/**
	* Get iva
	*
	* @return null|Decimal
	*/
	public function getIva()
	{
		return $this->iva;
	}

	/**
	* Set total
	*
	*
	*
	* @parámetro Decimal $total
	* @return Total
	*/
	public function setTotal($total)
	{
	  $this->total = (Double) $total;
	    return $this;
	}

	/**
	* Get total
	*
	* @return null|Decimal
	*/
	public function getTotal()
	{
		return $this->total;
	}

	/**
	* Set subsidio
	*
	*
	*
	* @parámetro Decimal $subsidio
	* @return Subsidio
	*/
	public function setSubsidio($subsidio)
	{
	  $this->subsidio = (Double) $subsidio;
	    return $this;
	}

	/**
	* Get subsidio
	*
	* @return null|Decimal
	*/
	public function getSubsidio()
	{
		return $this->subsidio;
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
	* @return DetallePagoModelo
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
