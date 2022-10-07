<?php
 /**
 * Modelo Certificacionf11DetalleEnviosModelo
 *
 * Este archivo se complementa con el archivo   Certificacionf11DetalleEnviosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-21
 * @uses    Certificacionf11DetalleEnviosModelo
 * @package FormulariosInspeccion
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class Certificacionf11DetalleEnviosModelo extends ModeloBase 
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
		* RUC de operador
		*/
		protected $rucOperador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Operador
		*/
		protected $operador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idSitio;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Sitio
		*/
		protected $sitio;
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
		* 
		*/
		protected $idTipoProducto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Tipo de producto
		*/
		protected $tipoProducto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idSubtipoProducto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Subtipo de producto
		*/
		protected $subtipoProducto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idProducto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Producto
		*/
		protected $producto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* País de destino
		*/
		protected $paisDestino;
		/**
		* @var Decimal
		* Campo requerido
		* Campo visible en el formulario
		* Peso neto (Kg.)
		*/
		protected $pesoNeto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Unidad de cantidad total
		*/
		protected $unidadCantidadTotal;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Cantidad total
		*/
		protected $cantidadTotal;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Unidad de cantidad inspeccionada
		*/
		protected $unidadCantidadInspeccionada;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Cantidad inspeccionada
		*/
		protected $cantidadInspeccionada;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Requiere tratamiento
		*/
		protected $requiereTratamiento;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de tratamiento
		*/
		protected $fechaTratamiento;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Tramiento
		*/
		protected $tratamiento;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Otros
		*/
		protected $otros;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Producto químico
		*/
		protected $productoQuimico;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Unidad de duración tratamiento
		*/
		protected $unidadDuracionTratamiento;
		/**
		* @var Decimal
		* Campo requerido
		* Campo visible en el formulario
		* Duración de tratamiento
		*/
		protected $duracionTratamiento;
		/**
		* @var Decimal
		* Campo requerido
		* Campo visible en el formulario
		* Temperatura en C°
		*/
		protected $temperatura;
		/**
		* @var Decimal
		* Campo requerido
		* Campo visible en el formulario
		* Concentración (%)
		*/
		protected $concentracion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Cumplimiento del requisito
		*/
		protected $incumplimientoRequisito;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Detalles
		*/
		protected $detalles;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Medida adoptada
		*/
		protected $medidaAdoptada;

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
	* Nombre de la tabla: certificacionf11_detalle_envios
	* 
	 */
	Private $tabla="certificacionf11_detalle_envios";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id";



	/**
	*Secuencia
*/
		 private $secuencial = 'f_inspeccion"."Certificacionf11DetalleEnvios_id_seq'; 



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
		throw new \Exception('Clase Modelo: Certificacionf11DetalleEnviosModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: Certificacionf11DetalleEnviosModelo. Propiedad especificada invalida: get'.$name);
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
	* Set rucOperador
	*
	*RUC de operador
	*
	* @parámetro String $rucOperador
	* @return RucOperador
	*/
	public function setRucOperador($rucOperador)
	{
	  $this->rucOperador = (String) $rucOperador;
	    return $this;
	}

	/**
	* Get rucOperador
	*
	* @return null|String
	*/
	public function getRucOperador()
	{
		return $this->rucOperador;
	}

	/**
	* Set operador
	*
	*Operador
	*
	* @parámetro String $operador
	* @return Operador
	*/
	public function setOperador($operador)
	{
	  $this->operador = (String) $operador;
	    return $this;
	}

	/**
	* Get operador
	*
	* @return null|String
	*/
	public function getOperador()
	{
		return $this->operador;
	}

	/**
	* Set idSitio
	*
	*
	*
	* @parámetro String $idSitio
	* @return IdSitio
	*/
	public function setIdSitio($idSitio)
	{
	  $this->idSitio = (String) $idSitio;
	    return $this;
	}

	/**
	* Get idSitio
	*
	* @return null|String
	*/
	public function getIdSitio()
	{
		return $this->idSitio;
	}

	/**
	* Set sitio
	*
	*Sitio
	*
	* @parámetro String $sitio
	* @return Sitio
	*/
	public function setSitio($sitio)
	{
	  $this->sitio = (String) $sitio;
	    return $this;
	}

	/**
	* Get sitio
	*
	* @return null|String
	*/
	public function getSitio()
	{
		return $this->sitio;
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
	* Set idTipoProducto
	*
	*
	*
	* @parámetro String $idTipoProducto
	* @return IdTipoProducto
	*/
	public function setIdTipoProducto($idTipoProducto)
	{
	  $this->idTipoProducto = (String) $idTipoProducto;
	    return $this;
	}

	/**
	* Get idTipoProducto
	*
	* @return null|String
	*/
	public function getIdTipoProducto()
	{
		return $this->idTipoProducto;
	}

	/**
	* Set tipoProducto
	*
	*Tipo de producto
	*
	* @parámetro String $tipoProducto
	* @return TipoProducto
	*/
	public function setTipoProducto($tipoProducto)
	{
	  $this->tipoProducto = (String) $tipoProducto;
	    return $this;
	}

	/**
	* Get tipoProducto
	*
	* @return null|String
	*/
	public function getTipoProducto()
	{
		return $this->tipoProducto;
	}

	/**
	* Set idSubtipoProducto
	*
	*
	*
	* @parámetro String $idSubtipoProducto
	* @return IdSubtipoProducto
	*/
	public function setIdSubtipoProducto($idSubtipoProducto)
	{
	  $this->idSubtipoProducto = (String) $idSubtipoProducto;
	    return $this;
	}

	/**
	* Get idSubtipoProducto
	*
	* @return null|String
	*/
	public function getIdSubtipoProducto()
	{
		return $this->idSubtipoProducto;
	}

	/**
	* Set subtipoProducto
	*
	*Subtipo de producto
	*
	* @parámetro String $subtipoProducto
	* @return SubtipoProducto
	*/
	public function setSubtipoProducto($subtipoProducto)
	{
	  $this->subtipoProducto = (String) $subtipoProducto;
	    return $this;
	}

	/**
	* Get subtipoProducto
	*
	* @return null|String
	*/
	public function getSubtipoProducto()
	{
		return $this->subtipoProducto;
	}

	/**
	* Set idProducto
	*
	*
	*
	* @parámetro String $idProducto
	* @return IdProducto
	*/
	public function setIdProducto($idProducto)
	{
	  $this->idProducto = (String) $idProducto;
	    return $this;
	}

	/**
	* Get idProducto
	*
	* @return null|String
	*/
	public function getIdProducto()
	{
		return $this->idProducto;
	}

	/**
	* Set producto
	*
	*Producto
	*
	* @parámetro String $producto
	* @return Producto
	*/
	public function setProducto($producto)
	{
	  $this->producto = (String) $producto;
	    return $this;
	}

	/**
	* Get producto
	*
	* @return null|String
	*/
	public function getProducto()
	{
		return $this->producto;
	}

	/**
	* Set paisDestino
	*
	*País de destino
	*
	* @parámetro String $paisDestino
	* @return PaisDestino
	*/
	public function setPaisDestino($paisDestino)
	{
	  $this->paisDestino = (String) $paisDestino;
	    return $this;
	}

	/**
	* Get paisDestino
	*
	* @return null|String
	*/
	public function getPaisDestino()
	{
		return $this->paisDestino;
	}

	/**
	* Set pesoNeto
	*
	*Peso neto (Kg.)
	*
	* @parámetro Decimal $pesoNeto
	* @return PesoNeto
	*/
	public function setPesoNeto($pesoNeto)
	{
	  $this->pesoNeto = (Double) $pesoNeto;
	    return $this;
	}

	/**
	* Get pesoNeto
	*
	* @return null|Decimal
	*/
	public function getPesoNeto()
	{
		return $this->pesoNeto;
	}

	/**
	* Set unidadCantidadTotal
	*
	*Unidad de cantidad total
	*
	* @parámetro String $unidadCantidadTotal
	* @return UnidadCantidadTotal
	*/
	public function setUnidadCantidadTotal($unidadCantidadTotal)
	{
	  $this->unidadCantidadTotal = (String) $unidadCantidadTotal;
	    return $this;
	}

	/**
	* Get unidadCantidadTotal
	*
	* @return null|String
	*/
	public function getUnidadCantidadTotal()
	{
		return $this->unidadCantidadTotal;
	}

	/**
	* Set cantidadTotal
	*
	*Cantidad total
	*
	* @parámetro Integer $cantidadTotal
	* @return CantidadTotal
	*/
	public function setCantidadTotal($cantidadTotal)
	{
	  $this->cantidadTotal = (Integer) $cantidadTotal;
	    return $this;
	}

	/**
	* Get cantidadTotal
	*
	* @return null|Integer
	*/
	public function getCantidadTotal()
	{
		return $this->cantidadTotal;
	}

	/**
	* Set unidadCantidadInspeccionada
	*
	*Unidad de cantidad inspeccionada
	*
	* @parámetro String $unidadCantidadInspeccionada
	* @return UnidadCantidadInspeccionada
	*/
	public function setUnidadCantidadInspeccionada($unidadCantidadInspeccionada)
	{
	  $this->unidadCantidadInspeccionada = (String) $unidadCantidadInspeccionada;
	    return $this;
	}

	/**
	* Get unidadCantidadInspeccionada
	*
	* @return null|String
	*/
	public function getUnidadCantidadInspeccionada()
	{
		return $this->unidadCantidadInspeccionada;
	}

	/**
	* Set cantidadInspeccionada
	*
	*Cantidad inspeccionada
	*
	* @parámetro Integer $cantidadInspeccionada
	* @return CantidadInspeccionada
	*/
	public function setCantidadInspeccionada($cantidadInspeccionada)
	{
	  $this->cantidadInspeccionada = (Integer) $cantidadInspeccionada;
	    return $this;
	}

	/**
	* Get cantidadInspeccionada
	*
	* @return null|Integer
	*/
	public function getCantidadInspeccionada()
	{
		return $this->cantidadInspeccionada;
	}

	/**
	* Set requiereTratamiento
	*
	*Requiere tratamiento
	*
	* @parámetro String $requiereTratamiento
	* @return RequiereTratamiento
	*/
	public function setRequiereTratamiento($requiereTratamiento)
	{
	  $this->requiereTratamiento = (String) $requiereTratamiento;
	    return $this;
	}

	/**
	* Get requiereTratamiento
	*
	* @return null|String
	*/
	public function getRequiereTratamiento()
	{
		return $this->requiereTratamiento;
	}

	/**
	* Set fechaTratamiento
	*
	*Fecha de tratamiento
	*
	* @parámetro Date $fechaTratamiento
	* @return FechaTratamiento
	*/
	public function setFechaTratamiento($fechaTratamiento)
	{
	  $this->fechaTratamiento = (String) $fechaTratamiento;
	    return $this;
	}

	/**
	* Get fechaTratamiento
	*
	* @return null|Date
	*/
	public function getFechaTratamiento()
	{
		return $this->fechaTratamiento;
	}

	/**
	* Set tratamiento
	*
	*Tramiento
	*
	* @parámetro String $tratamiento
	* @return Tratamiento
	*/
	public function setTratamiento($tratamiento)
	{
	  $this->tratamiento = (String) $tratamiento;
	    return $this;
	}

	/**
	* Get tratamiento
	*
	* @return null|String
	*/
	public function getTratamiento()
	{
		return $this->tratamiento;
	}

	/**
	* Set otros
	*
	*Otros
	*
	* @parámetro String $otros
	* @return Otros
	*/
	public function setOtros($otros)
	{
	  $this->otros = (String) $otros;
	    return $this;
	}

	/**
	* Get otros
	*
	* @return null|String
	*/
	public function getOtros()
	{
		return $this->otros;
	}

	/**
	* Set productoQuimico
	*
	*Producto químico
	*
	* @parámetro String $productoQuimico
	* @return ProductoQuimico
	*/
	public function setProductoQuimico($productoQuimico)
	{
	  $this->productoQuimico = (String) $productoQuimico;
	    return $this;
	}

	/**
	* Get productoQuimico
	*
	* @return null|String
	*/
	public function getProductoQuimico()
	{
		return $this->productoQuimico;
	}

	/**
	* Set unidadDuracionTratamiento
	*
	*Unidad de duración tratamiento
	*
	* @parámetro String $unidadDuracionTratamiento
	* @return UnidadDuracionTratamiento
	*/
	public function setUnidadDuracionTratamiento($unidadDuracionTratamiento)
	{
	  $this->unidadDuracionTratamiento = (String) $unidadDuracionTratamiento;
	    return $this;
	}

	/**
	* Get unidadDuracionTratamiento
	*
	* @return null|String
	*/
	public function getUnidadDuracionTratamiento()
	{
		return $this->unidadDuracionTratamiento;
	}

	/**
	* Set duracionTratamiento
	*
	*Duración de tratamiento
	*
	* @parámetro Decimal $duracionTratamiento
	* @return DuracionTratamiento
	*/
	public function setDuracionTratamiento($duracionTratamiento)
	{
	  $this->duracionTratamiento = (Double) $duracionTratamiento;
	    return $this;
	}

	/**
	* Get duracionTratamiento
	*
	* @return null|Decimal
	*/
	public function getDuracionTratamiento()
	{
		return $this->duracionTratamiento;
	}

	/**
	* Set temperatura
	*
	*Temperatura en C°
	*
	* @parámetro Decimal $temperatura
	* @return Temperatura
	*/
	public function setTemperatura($temperatura)
	{
	  $this->temperatura = (Double) $temperatura;
	    return $this;
	}

	/**
	* Get temperatura
	*
	* @return null|Decimal
	*/
	public function getTemperatura()
	{
		return $this->temperatura;
	}

	/**
	* Set concentracion
	*
	*Concentración (%)
	*
	* @parámetro Decimal $concentracion
	* @return Concentracion
	*/
	public function setConcentracion($concentracion)
	{
	  $this->concentracion = (Double) $concentracion;
	    return $this;
	}

	/**
	* Get concentracion
	*
	* @return null|Decimal
	*/
	public function getConcentracion()
	{
		return $this->concentracion;
	}

	/**
	* Set incumplimientoRequisito
	*
	*Cumplimiento del requisito
	*
	* @parámetro String $incumplimientoRequisito
	* @return IncumplimientoRequisito
	*/
	public function setIncumplimientoRequisito($incumplimientoRequisito)
	{
	  $this->incumplimientoRequisito = (String) $incumplimientoRequisito;
	    return $this;
	}

	/**
	* Get incumplimientoRequisito
	*
	* @return null|String
	*/
	public function getIncumplimientoRequisito()
	{
		return $this->incumplimientoRequisito;
	}

	/**
	* Set detalles
	*
	*Detalles
	*
	* @parámetro String $detalles
	* @return Detalles
	*/
	public function setDetalles($detalles)
	{
	  $this->detalles = (String) $detalles;
	    return $this;
	}

	/**
	* Get detalles
	*
	* @return null|String
	*/
	public function getDetalles()
	{
		return $this->detalles;
	}

	/**
	* Set medidaAdoptada
	*
	*Medida adoptada
	*
	* @parámetro String $medidaAdoptada
	* @return MedidaAdoptada
	*/
	public function setMedidaAdoptada($medidaAdoptada)
	{
	  $this->medidaAdoptada = (String) $medidaAdoptada;
	    return $this;
	}

	/**
	* Get medidaAdoptada
	*
	* @return null|String
	*/
	public function getMedidaAdoptada()
	{
		return $this->medidaAdoptada;
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
	* @return Certificacionf11DetalleEnviosModelo
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
