<?php
 /**
 * Modelo Vigilanciaf02DetalleOrdenesModelo
 *
 * Este archivo se complementa con el archivo   Vigilanciaf02DetalleOrdenesLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021/08/23
 * @uses    Vigilanciaf02DetalleOrdenesModelo
 * @package AplicacionMovilInternos
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class Vigilanciaf02DetalleOrdenesModelo extends ModeloBase 
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
		protected $idPadre;
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
		* Actividad de origen
		*/
		protected $actividadOrigen;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Análisis solicitado
		*/
		protected $analisis;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Código de muestra
		*/
		protected $codigoMuestra;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Tipo de conservación
		*/
		protected $conservacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Tipo de muestra
		*/
		protected $tipoMuestra;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Síntomas
		*/
		protected $descripcionSintomas;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Fáse fenológica
		*/
		protected $faseFenologica;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Producto para
		*/
		protected $nombreProducto;
		/**
		* @var Decimal
		* Campo requerido
		* Campo visible en el formulario
		* Peso de muestra
		*/
		protected $pesoMuestra;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Prediagnóstico
		*/
		protected $prediagnostico;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Cliente
		*/
		protected $tipoCliente;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Aplicación de producto químico
		*/
		protected $aplicacionProductoQuimico;

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
	* Nombre de la tabla: vigilanciaf02_detalle_ordenes
	* 
	 */
	Private $tabla="vigilanciaf02_detalle_ordenes";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id";



	/**
	*Secuencia
*/
		 private $secuencial = 'f_inspeccion"."Vigilanciaf02DetalleOrdenes_id_seq'; 



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
		throw new \Exception('Clase Modelo: Vigilanciaf02DetalleOrdenesModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: Vigilanciaf02DetalleOrdenesModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idPadre
	*
	*
	*
	* @parámetro Integer $idPadre
	* @return IdPadre
	*/
	public function setIdPadre($idPadre)
	{
	  $this->idPadre = (Integer) $idPadre;
	    return $this;
	}

	/**
	* Get idPadre
	*
	* @return null|Integer
	*/
	public function getIdPadre()
	{
		return $this->idPadre;
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
	* Set actividadOrigen
	*
	*Actividad de origen
	*
	* @parámetro String $actividadOrigen
	* @return ActividadOrigen
	*/
	public function setActividadOrigen($actividadOrigen)
	{
	  $this->actividadOrigen = (String) $actividadOrigen;
	    return $this;
	}

	/**
	* Get actividadOrigen
	*
	* @return null|String
	*/
	public function getActividadOrigen()
	{
		return $this->actividadOrigen;
	}

	/**
	* Set analisis
	*
	*Análisis solicitado
	*
	* @parámetro String $analisis
	* @return Analisis
	*/
	public function setAnalisis($analisis)
	{
	  $this->analisis = (String) $analisis;
	    return $this;
	}

	/**
	* Get analisis
	*
	* @return null|String
	*/
	public function getAnalisis()
	{
		return $this->analisis;
	}

	/**
	* Set codigoMuestra
	*
	*Código de muestra
	*
	* @parámetro String $codigoMuestra
	* @return CodigoMuestra
	*/
	public function setCodigoMuestra($codigoMuestra)
	{
	  $this->codigoMuestra = (String) $codigoMuestra;
	    return $this;
	}

	/**
	* Get codigoMuestra
	*
	* @return null|String
	*/
	public function getCodigoMuestra()
	{
		return $this->codigoMuestra;
	}

	/**
	* Set conservacion
	*
	*Tipo de conservación
	*
	* @parámetro String $conservacion
	* @return Conservacion
	*/
	public function setConservacion($conservacion)
	{
	  $this->conservacion = (String) $conservacion;
	    return $this;
	}

	/**
	* Get conservacion
	*
	* @return null|String
	*/
	public function getConservacion()
	{
		return $this->conservacion;
	}

	/**
	* Set tipoMuestra
	*
	*Tipo de muestra
	*
	* @parámetro String $tipoMuestra
	* @return TipoMuestra
	*/
	public function setTipoMuestra($tipoMuestra)
	{
	  $this->tipoMuestra = (String) $tipoMuestra;
	    return $this;
	}

	/**
	* Get tipoMuestra
	*
	* @return null|String
	*/
	public function getTipoMuestra()
	{
		return $this->tipoMuestra;
	}

	/**
	* Set descripcionSintomas
	*
	*Síntomas
	*
	* @parámetro String $descripcionSintomas
	* @return DescripcionSintomas
	*/
	public function setDescripcionSintomas($descripcionSintomas)
	{
	  $this->descripcionSintomas = (String) $descripcionSintomas;
	    return $this;
	}

	/**
	* Get descripcionSintomas
	*
	* @return null|String
	*/
	public function getDescripcionSintomas()
	{
		return $this->descripcionSintomas;
	}

	/**
	* Set faseFenologica
	*
	*Fáse fenológica
	*
	* @parámetro String $faseFenologica
	* @return FaseFenologica
	*/
	public function setFaseFenologica($faseFenologica)
	{
	  $this->faseFenologica = (String) $faseFenologica;
	    return $this;
	}

	/**
	* Get faseFenologica
	*
	* @return null|String
	*/
	public function getFaseFenologica()
	{
		return $this->faseFenologica;
	}

	/**
	* Set nombreProducto
	*
	*Producto para
	*
	* @parámetro String $nombreProducto
	* @return NombreProducto
	*/
	public function setNombreProducto($nombreProducto)
	{
	  $this->nombreProducto = (String) $nombreProducto;
	    return $this;
	}

	/**
	* Get nombreProducto
	*
	* @return null|String
	*/
	public function getNombreProducto()
	{
		return $this->nombreProducto;
	}

	/**
	* Set pesoMuestra
	*
	*Peso de muestra
	*
	* @parámetro Decimal $pesoMuestra
	* @return PesoMuestra
	*/
	public function setPesoMuestra($pesoMuestra)
	{
	  $this->pesoMuestra = (Double) $pesoMuestra;
	    return $this;
	}

	/**
	* Get pesoMuestra
	*
	* @return null|Decimal
	*/
	public function getPesoMuestra()
	{
		return $this->pesoMuestra;
	}

	/**
	* Set prediagnostico
	*
	*Prediagnóstico
	*
	* @parámetro String $prediagnostico
	* @return Prediagnostico
	*/
	public function setPrediagnostico($prediagnostico)
	{
	  $this->prediagnostico = (String) $prediagnostico;
	    return $this;
	}

	/**
	* Get prediagnostico
	*
	* @return null|String
	*/
	public function getPrediagnostico()
	{
		return $this->prediagnostico;
	}

	/**
	* Set tipoCliente
	*
	*Cliente
	*
	* @parámetro String $tipoCliente
	* @return TipoCliente
	*/
	public function setTipoCliente($tipoCliente)
	{
	  $this->tipoCliente = (String) $tipoCliente;
	    return $this;
	}

	/**
	* Get tipoCliente
	*
	* @return null|String
	*/
	public function getTipoCliente()
	{
		return $this->tipoCliente;
	}

	/**
	* Set aplicacionProductoQuimico
	*
	*Aplicación de producto químico
	*
	* @parámetro String $aplicacionProductoQuimico
	* @return AplicacionProductoQuimico
	*/
	public function setAplicacionProductoQuimico($aplicacionProductoQuimico)
	{
	  $this->aplicacionProductoQuimico = (String) $aplicacionProductoQuimico;
	    return $this;
	}

	/**
	* Get aplicacionProductoQuimico
	*
	* @return null|String
	*/
	public function getAplicacionProductoQuimico()
	{
		return $this->aplicacionProductoQuimico;
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
	* @return Vigilanciaf02DetalleOrdenesModelo
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
