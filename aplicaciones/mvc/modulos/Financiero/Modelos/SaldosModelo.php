<?php
 /**
 * Modelo SaldosModelo
 *
 * Este archivo se complementa con el archivo   SaldosLogicaNegocio.
 *
 * @author AGROCALIDAD
 * @fecha 2018-10-03
 * @uses       SaldosModelo
 * @package financiero
 * @subpackage Modelos
 */
  namespace Agrodb\Financiero\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class SaldosModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idSaldo;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idPago;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $fechaDeposito;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $valorIngreso;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $valorEgreso;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $saldoDisponible;
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
		* 
		*/
		protected $tipoSaldo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idOrdenVue;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $observacionSaldo;

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
	* Nombre de la tabla: saldos
	* 
	 */
	Private $tabla="saldos";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_saldo";



	/**
	*Secuencia
*/
		 private $secuencial = '"Saldos_"id_saldo_seq'; 



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
		throw new \Exception('Clase Modelo: SaldosModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: SaldosModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idSaldo
	*
	*Identificador secuencial único de la tabla
	*
	* @parámetro Integer $idSaldo
	* @return IdSaldo
	*/
	public function setIdSaldo($idSaldo)
	{
	  $this->idSaldo = (Integer) $idSaldo;
	    return $this;
	}

	/**
	* Get idSaldo
	*
	* @return null|Integer
	*/
	public function getIdSaldo()
	{
		return $this->idSaldo;
	}

	/**
	* Set idPago
	*
	*Identificador de la tabla orden de pago
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
	* Set fechaDeposito
	*
	*Fecha de la realización del deposito
	*
	* @parámetro Date $fechaDeposito
	* @return FechaDeposito
	*/
	public function setFechaDeposito($fechaDeposito)
	{
	  $this->fechaDeposito = (String) $fechaDeposito;
	    return $this;
	}

	/**
	* Get fechaDeposito
	*
	* @return null|Date
	*/
	public function getFechaDeposito()
	{
		return $this->fechaDeposito;
	}

	/**
	* Set valorIngreso
	*
	*Valor de ingreso
	*
	* @parámetro String $valorIngreso
	* @return ValorIngreso
	*/
	public function setValorIngreso($valorIngreso)
	{
	  $this->valorIngreso = (String) $valorIngreso;
	    return $this;
	}

	/**
	* Get valorIngreso
	*
	* @return null|String
	*/
	public function getValorIngreso()
	{
		return $this->valorIngreso;
	}

	/**
	* Set valorEgreso
	*
	*Valor de egreso
	*
	* @parámetro String $valorEgreso
	* @return ValorEgreso
	*/
	public function setValorEgreso($valorEgreso)
	{
	  $this->valorEgreso = (String) $valorEgreso;
	    return $this;
	}

	/**
	* Get valorEgreso
	*
	* @return null|String
	*/
	public function getValorEgreso()
	{
		return $this->valorEgreso;
	}

	/**
	* Set saldoDisponible
	*
	*Saldo disponible
	*
	* @parámetro String $saldoDisponible
	* @return SaldoDisponible
	*/
	public function setSaldoDisponible($saldoDisponible)
	{
	  $this->saldoDisponible = (String) $saldoDisponible;
	    return $this;
	}

	/**
	* Get saldoDisponible
	*
	* @return null|String
	*/
	public function getSaldoDisponible()
	{
		return $this->saldoDisponible;
	}

	/**
	* Set identificadorOperador
	*
	*Idnetificador del operador
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
	* Set tipoSaldo
	*
	*No se esta utilizando
	*
	* @parámetro String $tipoSaldo
	* @return TipoSaldo
	*/
	public function setTipoSaldo($tipoSaldo)
	{
	  $this->tipoSaldo = (String) $tipoSaldo;
	    return $this;
	}

	/**
	* Get tipoSaldo
	*
	* @return null|String
	*/
	public function getTipoSaldo()
	{
		return $this->tipoSaldo;
	}

	/**
	* Set idOrdenVue
	*
	*No se esta utilizando
	*
	* @parámetro String $idOrdenVue
	* @return IdOrdenVue
	*/
	public function setIdOrdenVue($idOrdenVue)
	{
	  $this->idOrdenVue = (String) $idOrdenVue;
	    return $this;
	}

	/**
	* Get idOrdenVue
	*
	* @return null|String
	*/
	public function getIdOrdenVue()
	{
		return $this->idOrdenVue;
	}

	/**
	* Set observacionSaldo
	*
	*Observación en el caso de modificación de saldo
	*
	* @parámetro String $observacionSaldo
	* @return ObservacionSaldo
	*/
	public function setObservacionSaldo($observacionSaldo)
	{
	  $this->observacionSaldo = (String) $observacionSaldo;
	    return $this;
	}

	/**
	* Get observacionSaldo
	*
	* @return null|String
	*/
	public function getObservacionSaldo()
	{
		return $this->observacionSaldo;
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
	* @return SaldosModelo
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
