<?php
 /**
 * Modelo Controlf02Modelo
 *
 * Este archivo se complementa con el archivo   Controlf02LogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021/08/23
 * @uses    Controlf02Modelo
 * @package FormulariosInspeccion
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class Controlf02Modelo extends ModeloBase 
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
		* Nombre de razón social
		*/
		protected $nombreRazonSocial;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* RUC/CI
		*/
		protected $rucCi;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idPaisOrigen;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* País de origen
		*/
		protected $paisOrigen;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idPaisProcedencia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* País de procedencia
		*/
		protected $paisProcedencia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idPaisDestino;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* País de destino
		*/
		protected $paisDestino;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idPuntoIngreso;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Punto de ingreso
		*/
		protected $puntoIngreso;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idPuntoSalida;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Punto de salida
		*/
		protected $puntoSalida;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Placa del vehículo
		*/
		protected $placaVehiculo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* DDA
		*/
		protected $dda;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Número precinto/sticker
		*/
		protected $precintoSticker;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Estado de carga
		*/
		protected $estado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Tipo de verificación
		*/
		protected $tipoVerificacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Estado precinto/sticker a salida
		*/
		protected $estadoPrecinto;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de registro de ingreso
		*/
		protected $fechaIngreso;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Cédula del inspector de registro de ingreso
		*/
		protected $usuarioIdIngreso;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Usuario de registro de ingreso
		*/
		protected $usuarioIngreso;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $tabletIdIngreso;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $tabletVersionBaseIngreso;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de registro de salida
		*/
		protected $fechaSalida;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Cédula del inspector de registro de salida
		*/
		protected $usuarioIdSalida;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Usuario de registro de salida
		*/
		protected $usuarioSalida;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $tabletIdSalida;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $tabletVersionBaseSalida;
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
		protected $estadoCf02;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $observacionCf02;

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
	* Nombre de la tabla: controlf02
	* 
	 */
	Private $tabla="controlf02";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id";



	/**
	*Secuencia
*/
		 private $secuencial = 'f_inspeccion"."Controlf02_id_seq'; 



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
		throw new \Exception('Clase Modelo: Controlf02Modelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: Controlf02Modelo. Propiedad especificada invalida: get'.$name);
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
	* Set nombreRazonSocial
	*
	*Nombre de razón social
	*
	* @parámetro String $nombreRazonSocial
	* @return NombreRazonSocial
	*/
	public function setNombreRazonSocial($nombreRazonSocial)
	{
	  $this->nombreRazonSocial = (String) $nombreRazonSocial;
	    return $this;
	}

	/**
	* Get nombreRazonSocial
	*
	* @return null|String
	*/
	public function getNombreRazonSocial()
	{
		return $this->nombreRazonSocial;
	}

	/**
	* Set rucCi
	*
	*RUC/CI
	*
	* @parámetro String $rucCi
	* @return RucCi
	*/
	public function setRucCi($rucCi)
	{
	  $this->rucCi = (String) $rucCi;
	    return $this;
	}

	/**
	* Get rucCi
	*
	* @return null|String
	*/
	public function getRucCi()
	{
		return $this->rucCi;
	}

	/**
	* Set idPaisOrigen
	*
	*
	*
	* @parámetro String $idPaisOrigen
	* @return IdPaisOrigen
	*/
	public function setIdPaisOrigen($idPaisOrigen)
	{
	  $this->idPaisOrigen = (String) $idPaisOrigen;
	    return $this;
	}

	/**
	* Get idPaisOrigen
	*
	* @return null|String
	*/
	public function getIdPaisOrigen()
	{
		return $this->idPaisOrigen;
	}

	/**
	* Set paisOrigen
	*
	*País de origen
	*
	* @parámetro String $paisOrigen
	* @return PaisOrigen
	*/
	public function setPaisOrigen($paisOrigen)
	{
	  $this->paisOrigen = (String) $paisOrigen;
	    return $this;
	}

	/**
	* Get paisOrigen
	*
	* @return null|String
	*/
	public function getPaisOrigen()
	{
		return $this->paisOrigen;
	}

	/**
	* Set idPaisProcedencia
	*
	*
	*
	* @parámetro String $idPaisProcedencia
	* @return IdPaisProcedencia
	*/
	public function setIdPaisProcedencia($idPaisProcedencia)
	{
	  $this->idPaisProcedencia = (String) $idPaisProcedencia;
	    return $this;
	}

	/**
	* Get idPaisProcedencia
	*
	* @return null|String
	*/
	public function getIdPaisProcedencia()
	{
		return $this->idPaisProcedencia;
	}

	/**
	* Set paisProcedencia
	*
	*País de procedencia
	*
	* @parámetro String $paisProcedencia
	* @return PaisProcedencia
	*/
	public function setPaisProcedencia($paisProcedencia)
	{
	  $this->paisProcedencia = (String) $paisProcedencia;
	    return $this;
	}

	/**
	* Get paisProcedencia
	*
	* @return null|String
	*/
	public function getPaisProcedencia()
	{
		return $this->paisProcedencia;
	}

	/**
	* Set idPaisDestino
	*
	*
	*
	* @parámetro String $idPaisDestino
	* @return IdPaisDestino
	*/
	public function setIdPaisDestino($idPaisDestino)
	{
	  $this->idPaisDestino = (String) $idPaisDestino;
	    return $this;
	}

	/**
	* Get idPaisDestino
	*
	* @return null|String
	*/
	public function getIdPaisDestino()
	{
		return $this->idPaisDestino;
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
	* Set idPuntoIngreso
	*
	*
	*
	* @parámetro String $idPuntoIngreso
	* @return IdPuntoIngreso
	*/
	public function setIdPuntoIngreso($idPuntoIngreso)
	{
	  $this->idPuntoIngreso = (String) $idPuntoIngreso;
	    return $this;
	}

	/**
	* Get idPuntoIngreso
	*
	* @return null|String
	*/
	public function getIdPuntoIngreso()
	{
		return $this->idPuntoIngreso;
	}

	/**
	* Set puntoIngreso
	*
	*Punto de ingreso
	*
	* @parámetro String $puntoIngreso
	* @return PuntoIngreso
	*/
	public function setPuntoIngreso($puntoIngreso)
	{
	  $this->puntoIngreso = (String) $puntoIngreso;
	    return $this;
	}

	/**
	* Get puntoIngreso
	*
	* @return null|String
	*/
	public function getPuntoIngreso()
	{
		return $this->puntoIngreso;
	}

	/**
	* Set idPuntoSalida
	*
	*
	*
	* @parámetro String $idPuntoSalida
	* @return IdPuntoSalida
	*/
	public function setIdPuntoSalida($idPuntoSalida)
	{
	  $this->idPuntoSalida = (String) $idPuntoSalida;
	    return $this;
	}

	/**
	* Get idPuntoSalida
	*
	* @return null|String
	*/
	public function getIdPuntoSalida()
	{
		return $this->idPuntoSalida;
	}

	/**
	* Set puntoSalida
	*
	*Punto de salida
	*
	* @parámetro String $puntoSalida
	* @return PuntoSalida
	*/
	public function setPuntoSalida($puntoSalida)
	{
	  $this->puntoSalida = (String) $puntoSalida;
	    return $this;
	}

	/**
	* Get puntoSalida
	*
	* @return null|String
	*/
	public function getPuntoSalida()
	{
		return $this->puntoSalida;
	}

	/**
	* Set placaVehiculo
	*
	*Placa del vehículo
	*
	* @parámetro String $placaVehiculo
	* @return PlacaVehiculo
	*/
	public function setPlacaVehiculo($placaVehiculo)
	{
	  $this->placaVehiculo = (String) $placaVehiculo;
	    return $this;
	}

	/**
	* Get placaVehiculo
	*
	* @return null|String
	*/
	public function getPlacaVehiculo()
	{
		return $this->placaVehiculo;
	}

	/**
	* Set dda
	*
	*DDA
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
	* Set precintoSticker
	*
	*Número precinto/sticker
	*
	* @parámetro String $precintoSticker
	* @return PrecintoSticker
	*/
	public function setPrecintoSticker($precintoSticker)
	{
	  $this->precintoSticker = (String) $precintoSticker;
	    return $this;
	}

	/**
	* Get precintoSticker
	*
	* @return null|String
	*/
	public function getPrecintoSticker()
	{
		return $this->precintoSticker;
	}

	/**
	* Set estado
	*
	*Estado de carga
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
	* Set tipoVerificacion
	*
	*Tipo de verificación
	*
	* @parámetro String $tipoVerificacion
	* @return TipoVerificacion
	*/
	public function setTipoVerificacion($tipoVerificacion)
	{
	  $this->tipoVerificacion = (String) $tipoVerificacion;
	    return $this;
	}

	/**
	* Get tipoVerificacion
	*
	* @return null|String
	*/
	public function getTipoVerificacion()
	{
		return $this->tipoVerificacion;
	}

	/**
	* Set estadoPrecinto
	*
	*Estado precinto/sticker a salida
	*
	* @parámetro String $estadoPrecinto
	* @return EstadoPrecinto
	*/
	public function setEstadoPrecinto($estadoPrecinto)
	{
	  $this->estadoPrecinto = (String) $estadoPrecinto;
	    return $this;
	}

	/**
	* Get estadoPrecinto
	*
	* @return null|String
	*/
	public function getEstadoPrecinto()
	{
		return $this->estadoPrecinto;
	}

	/**
	* Set fechaIngreso
	*
	*Fecha de registro de ingreso
	*
	* @parámetro Date $fechaIngreso
	* @return FechaIngreso
	*/
	public function setFechaIngreso($fechaIngreso)
	{
	  $this->fechaIngreso = (String) $fechaIngreso;
	    return $this;
	}

	/**
	* Get fechaIngreso
	*
	* @return null|Date
	*/
	public function getFechaIngreso()
	{
		return $this->fechaIngreso;
	}

	/**
	* Set usuarioIdIngreso
	*
	*Cédula del inspector de registro de ingreso
	*
	* @parámetro String $usuarioIdIngreso
	* @return UsuarioIdIngreso
	*/
	public function setUsuarioIdIngreso($usuarioIdIngreso)
	{
	  $this->usuarioIdIngreso = (String) $usuarioIdIngreso;
	    return $this;
	}

	/**
	* Get usuarioIdIngreso
	*
	* @return null|String
	*/
	public function getUsuarioIdIngreso()
	{
		return $this->usuarioIdIngreso;
	}

	/**
	* Set usuarioIngreso
	*
	*Usuario de registro de ingreso
	*
	* @parámetro String $usuarioIngreso
	* @return UsuarioIngreso
	*/
	public function setUsuarioIngreso($usuarioIngreso)
	{
	  $this->usuarioIngreso = (String) $usuarioIngreso;
	    return $this;
	}

	/**
	* Get usuarioIngreso
	*
	* @return null|String
	*/
	public function getUsuarioIngreso()
	{
		return $this->usuarioIngreso;
	}

	/**
	* Set tabletIdIngreso
	*
	*
	*
	* @parámetro String $tabletIdIngreso
	* @return TabletIdIngreso
	*/
	public function setTabletIdIngreso($tabletIdIngreso)
	{
	  $this->tabletIdIngreso = (String) $tabletIdIngreso;
	    return $this;
	}

	/**
	* Get tabletIdIngreso
	*
	* @return null|String
	*/
	public function getTabletIdIngreso()
	{
		return $this->tabletIdIngreso;
	}

	/**
	* Set tabletVersionBaseIngreso
	*
	*
	*
	* @parámetro String $tabletVersionBaseIngreso
	* @return TabletVersionBaseIngreso
	*/
	public function setTabletVersionBaseIngreso($tabletVersionBaseIngreso)
	{
	  $this->tabletVersionBaseIngreso = (String) $tabletVersionBaseIngreso;
	    return $this;
	}

	/**
	* Get tabletVersionBaseIngreso
	*
	* @return null|String
	*/
	public function getTabletVersionBaseIngreso()
	{
		return $this->tabletVersionBaseIngreso;
	}

	/**
	* Set fechaSalida
	*
	*Fecha de registro de salida
	*
	* @parámetro Date $fechaSalida
	* @return FechaSalida
	*/
	public function setFechaSalida($fechaSalida)
	{
	  $this->fechaSalida = (String) $fechaSalida;
	    return $this;
	}

	/**
	* Get fechaSalida
	*
	* @return null|Date
	*/
	public function getFechaSalida()
	{
		return $this->fechaSalida;
	}

	/**
	* Set usuarioIdSalida
	*
	*Cédula del inspector de registro de salida
	*
	* @parámetro String $usuarioIdSalida
	* @return UsuarioIdSalida
	*/
	public function setUsuarioIdSalida($usuarioIdSalida)
	{
	  $this->usuarioIdSalida = (String) $usuarioIdSalida;
	    return $this;
	}

	/**
	* Get usuarioIdSalida
	*
	* @return null|String
	*/
	public function getUsuarioIdSalida()
	{
		return $this->usuarioIdSalida;
	}

	/**
	* Set usuarioSalida
	*
	*Usuario de registro de salida
	*
	* @parámetro String $usuarioSalida
	* @return UsuarioSalida
	*/
	public function setUsuarioSalida($usuarioSalida)
	{
	  $this->usuarioSalida = (String) $usuarioSalida;
	    return $this;
	}

	/**
	* Get usuarioSalida
	*
	* @return null|String
	*/
	public function getUsuarioSalida()
	{
		return $this->usuarioSalida;
	}

	/**
	* Set tabletIdSalida
	*
	*
	*
	* @parámetro String $tabletIdSalida
	* @return TabletIdSalida
	*/
	public function setTabletIdSalida($tabletIdSalida)
	{
	  $this->tabletIdSalida = (String) $tabletIdSalida;
	    return $this;
	}

	/**
	* Get tabletIdSalida
	*
	* @return null|String
	*/
	public function getTabletIdSalida()
	{
		return $this->tabletIdSalida;
	}

	/**
	* Set tabletVersionBaseSalida
	*
	*
	*
	* @parámetro String $tabletVersionBaseSalida
	* @return TabletVersionBaseSalida
	*/
	public function setTabletVersionBaseSalida($tabletVersionBaseSalida)
	{
	  $this->tabletVersionBaseSalida = (String) $tabletVersionBaseSalida;
	    return $this;
	}

	/**
	* Get tabletVersionBaseSalida
	*
	* @return null|String
	*/
	public function getTabletVersionBaseSalida()
	{
		return $this->tabletVersionBaseSalida;
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
	* Set estadoCf02
	*
	*
	*
	* @parámetro String $estadoCf02
	* @return EstadoCf02
	*/
	public function setEstadoCf02($estadoCf02)
	{
	  $this->estadoCf02 = (String) $estadoCf02;
	    return $this;
	}

	/**
	* Get estadoCf02
	*
	* @return null|String
	*/
	public function getEstadoCf02()
	{
		return $this->estadoCf02;
	}

	/**
	* Set observacionCf02
	*
	*
	*
	* @parámetro String $observacionCf02
	* @return ObservacionCf02
	*/
	public function setObservacionCf02($observacionCf02)
	{
	  $this->observacionCf02 = (String) $observacionCf02;
	    return $this;
	}

	/**
	* Get observacionCf02
	*
	* @return null|String
	*/
	public function getObservacionCf02()
	{
		return $this->observacionCf02;
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
	* @return Controlf02Modelo
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
