<?php
 /**
 * Modelo Controlf01Modelo
 *
 * Este archivo se complementa con el archivo   Controlf01LogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021/08/23
 * @uses    Controlf01Modelo
 * @package FormulariosInspeccion
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class Controlf01Modelo extends ModeloBase 
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
		* Documento de Destinación Aduanera
		*/
		protected $dda;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Permiso Fitosanitario de Importación
		*/
		protected $pfi;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Dictamen final de inspección
		*/
		protected $dictamenFinal;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Observaciones de la inspección
		*/
		protected $observaciones;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* ¿Envío de muestra?
		*/
		protected $envioMuestra;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Identificación de inspector
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
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de la inspección
		*/
		protected $fechaInspeccion;
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
		* Descripción del producto coincide con el producto físico
		*/
		protected $pregunta01;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Cantidad del producto vegetal es menor o igual al autorizado
		*/
		protected $pregunta02;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Los embalajes de madera cuentan con la marca autorizada del país de origen
		*/
		protected $pregunta03;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* La marca es legible
		*/
		protected $pregunta04;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Ausencia de daño de insectos
		*/
		protected $pregunta05;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Ausencia de insectos vivos en los embalajes
		*/
		protected $pregunta06;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Ausencia de corteza
		*/
		protected $pregunta07;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Empaques nuevos de primer uso
		*/
		protected $pregunta08;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* No. de contenedores/vehiculos del envio
		*/
		protected $pregunta09;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* No. de contenedores seleccionados para el aforo
		*/
		protected $pregunta10;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Criterio usado para la división de los envíos en lotes
		*/
		protected $pregunta11;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Categoría de riesgo
		*/
		protected $categoriaRiesgo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* ¿Requiere seguimiento cuarentenario?
		*/
		protected $seguimientoCuarentenario;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Provincia
		*/
		protected $provincia;
		/**
		* @var Decimal
		* Campo requerido
		* Campo visible en el formulario
		* Peso de ingreso
		*/
		protected $pesoIngreso;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Embalajes de envío
		*/
		protected $numeroEmbalajesEnvio;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Embalajes inspeccionados
		*/
		protected $numeroEmbalajesInspeccionados;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $estadoGuia;
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
		* 
		*/
		protected $estadoCf01;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $observacionCf01;

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
	* Nombre de la tabla: controlf01
	* 
	 */
	Private $tabla="controlf01";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id";



	/**
	*Secuencia
*/
		 private $secuencial = 'f_inspeccion"."Controlf01_id_seq'; 



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
		throw new \Exception('Clase Modelo: Controlf01Modelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: Controlf01Modelo. Propiedad especificada invalida: get'.$name);
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
	* Set dda
	*
	*Documento de Destinación Aduanera
	*
	* @parámetro String $dda
	* @return Dda
	*/
	public function setDda($dda)
	{
	  $this->dda = (String) $dda;
	    return $this;
	}

	/**
	* Get dda
	*
	* @return null|String
	*/
	public function getDda()
	{
		return $this->dda;
	}

	/**
	* Set pfi
	*
	*Permiso Fitosanitario de Importación
	*
	* @parámetro String $pfi
	* @return Pfi
	*/
	public function setPfi($pfi)
	{
	  $this->pfi = (String) $pfi;
	    return $this;
	}

	/**
	* Get pfi
	*
	* @return null|String
	*/
	public function getPfi()
	{
		return $this->pfi;
	}

	/**
	* Set dictamenFinal
	*
	*Dictamen final de inspección
	*
	* @parámetro String $dictamenFinal
	* @return DictamenFinal
	*/
	public function setDictamenFinal($dictamenFinal)
	{
	  $this->dictamenFinal = (String) $dictamenFinal;
	    return $this;
	}

	/**
	* Get dictamenFinal
	*
	* @return null|String
	*/
	public function getDictamenFinal()
	{
		return $this->dictamenFinal;
	}

	/**
	* Set observaciones
	*
	*Observaciones de la inspección
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
	* Set envioMuestra
	*
	*¿Envío de muestra?
	*
	* @parámetro String $envioMuestra
	* @return EnvioMuestra
	*/
	public function setEnvioMuestra($envioMuestra)
	{
	  $this->envioMuestra = (String) $envioMuestra;
	    return $this;
	}

	/**
	* Get envioMuestra
	*
	* @return null|String
	*/
	public function getEnvioMuestra()
	{
		return $this->envioMuestra;
	}

	/**
	* Set usuarioId
	*
	*Identificación de inspector
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
	* Set fechaInspeccion
	*
	*Fecha de la inspección
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
	* Set pregunta01
	*
	*Descripción del producto coincide con el producto físico
	*
	* @parámetro String $pregunta01
	* @return Pregunta01
	*/
	public function setPregunta01($pregunta01)
	{
	  $this->pregunta01 = (String) $pregunta01;
	    return $this;
	}

	/**
	* Get pregunta01
	*
	* @return null|String
	*/
	public function getPregunta01()
	{
		return $this->pregunta01;
	}

	/**
	* Set pregunta02
	*
	*Cantidad del producto vegetal es menor o igual al autorizado
	*
	* @parámetro String $pregunta02
	* @return Pregunta02
	*/
	public function setPregunta02($pregunta02)
	{
	  $this->pregunta02 = (String) $pregunta02;
	    return $this;
	}

	/**
	* Get pregunta02
	*
	* @return null|String
	*/
	public function getPregunta02()
	{
		return $this->pregunta02;
	}

	/**
	* Set pregunta03
	*
	*Los embalajes de madera cuentan con la marca autorizada del país de origen
	*
	* @parámetro String $pregunta03
	* @return Pregunta03
	*/
	public function setPregunta03($pregunta03)
	{
	  $this->pregunta03 = (String) $pregunta03;
	    return $this;
	}

	/**
	* Get pregunta03
	*
	* @return null|String
	*/
	public function getPregunta03()
	{
		return $this->pregunta03;
	}

	/**
	* Set pregunta04
	*
	*La marca es legible
	*
	* @parámetro String $pregunta04
	* @return Pregunta04
	*/
	public function setPregunta04($pregunta04)
	{
	  $this->pregunta04 = (String) $pregunta04;
	    return $this;
	}

	/**
	* Get pregunta04
	*
	* @return null|String
	*/
	public function getPregunta04()
	{
		return $this->pregunta04;
	}

	/**
	* Set pregunta05
	*
	*Ausencia de daño de insectos
	*
	* @parámetro String $pregunta05
	* @return Pregunta05
	*/
	public function setPregunta05($pregunta05)
	{
	  $this->pregunta05 = (String) $pregunta05;
	    return $this;
	}

	/**
	* Get pregunta05
	*
	* @return null|String
	*/
	public function getPregunta05()
	{
		return $this->pregunta05;
	}

	/**
	* Set pregunta06
	*
	*Ausencia de insectos vivos en los embalajes
	*
	* @parámetro String $pregunta06
	* @return Pregunta06
	*/
	public function setPregunta06($pregunta06)
	{
	  $this->pregunta06 = (String) $pregunta06;
	    return $this;
	}

	/**
	* Get pregunta06
	*
	* @return null|String
	*/
	public function getPregunta06()
	{
		return $this->pregunta06;
	}

	/**
	* Set pregunta07
	*
	*Ausencia de corteza
	*
	* @parámetro String $pregunta07
	* @return Pregunta07
	*/
	public function setPregunta07($pregunta07)
	{
	  $this->pregunta07 = (String) $pregunta07;
	    return $this;
	}

	/**
	* Get pregunta07
	*
	* @return null|String
	*/
	public function getPregunta07()
	{
		return $this->pregunta07;
	}

	/**
	* Set pregunta08
	*
	*Empaques nuevos de primer uso
	*
	* @parámetro String $pregunta08
	* @return Pregunta08
	*/
	public function setPregunta08($pregunta08)
	{
	  $this->pregunta08 = (String) $pregunta08;
	    return $this;
	}

	/**
	* Get pregunta08
	*
	* @return null|String
	*/
	public function getPregunta08()
	{
		return $this->pregunta08;
	}

	/**
	* Set pregunta09
	*
	*No. de contenedores/vehiculos del envio
	*
	* @parámetro Integer $pregunta09
	* @return Pregunta09
	*/
	public function setPregunta09($pregunta09)
	{
	  $this->pregunta09 = (Integer) $pregunta09;
	    return $this;
	}

	/**
	* Get pregunta09
	*
	* @return null|Integer
	*/
	public function getPregunta09()
	{
		return $this->pregunta09;
	}

	/**
	* Set pregunta10
	*
	*No. de contenedores seleccionados para el aforo
	*
	* @parámetro Integer $pregunta10
	* @return Pregunta10
	*/
	public function setPregunta10($pregunta10)
	{
	  $this->pregunta10 = (Integer) $pregunta10;
	    return $this;
	}

	/**
	* Get pregunta10
	*
	* @return null|Integer
	*/
	public function getPregunta10()
	{
		return $this->pregunta10;
	}

	/**
	* Set pregunta11
	*
	*Criterio usado para la división de los envíos en lotes
	*
	* @parámetro String $pregunta11
	* @return Pregunta11
	*/
	public function setPregunta11($pregunta11)
	{
	  $this->pregunta11 = (String) $pregunta11;
	    return $this;
	}

	/**
	* Get pregunta11
	*
	* @return null|String
	*/
	public function getPregunta11()
	{
		return $this->pregunta11;
	}

	/**
	* Set categoriaRiesgo
	*
	*Categoría de riesgo
	*
	* @parámetro String $categoriaRiesgo
	* @return CategoriaRiesgo
	*/
	public function setCategoriaRiesgo($categoriaRiesgo)
	{
	  $this->categoriaRiesgo = (String) $categoriaRiesgo;
	    return $this;
	}

	/**
	* Get categoriaRiesgo
	*
	* @return null|String
	*/
	public function getCategoriaRiesgo()
	{
		return $this->categoriaRiesgo;
	}

	/**
	* Set seguimientoCuarentenario
	*
	*¿Requiere seguimiento cuarentenario?
	*
	* @parámetro String $seguimientoCuarentenario
	* @return SeguimientoCuarentenario
	*/
	public function setSeguimientoCuarentenario($seguimientoCuarentenario)
	{
	  $this->seguimientoCuarentenario = (String) $seguimientoCuarentenario;
	    return $this;
	}

	/**
	* Get seguimientoCuarentenario
	*
	* @return null|String
	*/
	public function getSeguimientoCuarentenario()
	{
		return $this->seguimientoCuarentenario;
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
	* Set pesoIngreso
	*
	*Peso de ingreso
	*
	* @parámetro Decimal $pesoIngreso
	* @return PesoIngreso
	*/
	public function setPesoIngreso($pesoIngreso)
	{
	  $this->pesoIngreso = (Double) $pesoIngreso;
	    return $this;
	}

	/**
	* Get pesoIngreso
	*
	* @return null|Decimal
	*/
	public function getPesoIngreso()
	{
		return $this->pesoIngreso;
	}

	/**
	* Set numeroEmbalajesEnvio
	*
	*Embalajes de envío
	*
	* @parámetro Integer $numeroEmbalajesEnvio
	* @return NumeroEmbalajesEnvio
	*/
	public function setNumeroEmbalajesEnvio($numeroEmbalajesEnvio)
	{
	  $this->numeroEmbalajesEnvio = (Integer) $numeroEmbalajesEnvio;
	    return $this;
	}

	/**
	* Get numeroEmbalajesEnvio
	*
	* @return null|Integer
	*/
	public function getNumeroEmbalajesEnvio()
	{
		return $this->numeroEmbalajesEnvio;
	}

	/**
	* Set numeroEmbalajesInspeccionados
	*
	*Embalajes inspeccionados
	*
	* @parámetro Integer $numeroEmbalajesInspeccionados
	* @return NumeroEmbalajesInspeccionados
	*/
	public function setNumeroEmbalajesInspeccionados($numeroEmbalajesInspeccionados)
	{
	  $this->numeroEmbalajesInspeccionados = (Integer) $numeroEmbalajesInspeccionados;
	    return $this;
	}

	/**
	* Get numeroEmbalajesInspeccionados
	*
	* @return null|Integer
	*/
	public function getNumeroEmbalajesInspeccionados()
	{
		return $this->numeroEmbalajesInspeccionados;
	}

	/**
	* Set estadoGuia
	*
	*
	*
	* @parámetro String $estadoGuia
	* @return EstadoGuia
	*/
	public function setEstadoGuia($estadoGuia)
	{
	  $this->estadoGuia = (String) $estadoGuia;
	    return $this;
	}

	/**
	* Get estadoGuia
	*
	* @return null|String
	*/
	public function getEstadoGuia()
	{
		return $this->estadoGuia;
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
	* Set estadoCf01
	*
	*
	*
	* @parámetro String $estadoCf01
	* @return EstadoCf01
	*/
	public function setEstadoCf01($estadoCf01)
	{
	  $this->estadoCf01 = (String) $estadoCf01;
	    return $this;
	}

	/**
	* Get estadoCf01
	*
	* @return null|String
	*/
	public function getEstadoCf01()
	{
		return $this->estadoCf01;
	}

	/**
	* Set observacionCf01
	*
	*
	*
	* @parámetro String $observacionCf01
	* @return ObservacionCf01
	*/
	public function setObservacionCf01($observacionCf01)
	{
	  $this->observacionCf01 = (String) $observacionCf01;
	    return $this;
	}

	/**
	* Get observacionCf01
	*
	* @return null|String
	*/
	public function getObservacionCf01()
	{
		return $this->observacionCf01;
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
	* @return Controlf01Modelo
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
