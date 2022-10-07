<?php
 /**
 * Modelo DetallePostAnimalesModelo
 *
 * Este archivo se complementa con el archivo   DetallePostAnimalesLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    DetallePostAnimalesModelo
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
  use Agrodb\Core\ModeloBase;
 
class DetallePostAnimalesModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
		protected $idDetallePostAnimales;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Llave foránea de la tabla formulario_post_mortem
		*/
		protected $idFormularioPostMortem;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Llave foránea de la tabla detalle_ante_animales
		*/
		protected $idDetalleAnteAnimales;
		/**
		* @var Date
		* Campo opcional
		* Campo visible en el formulario
		* Fecha de creación del registro
		*/
		protected $fechaCreacion;
		/**
		* @var Date
		* Campo opcional
		* Campo visible en el formulario
		* Fecha del formulario post morten
		*/
		protected $fechaFormulario;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Estado nodulos linfaticos
		*/
		protected $estadoNodulosLinfaticos;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Registrar nuevo diagnostico
		*/
		protected $otroDiagnostico;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Número de  canales de decomiso parcial
		*/
		protected $numCanalesDecomisoParcial;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Peso total de carne aprobada
		*/
		protected $pesoTotalCarneAprobada;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Peso total de carne decomisada
		*/
		protected $pesoTotalCarneDecomisada;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Número de canales de decomiso
		*/
		protected $numCanalesDecomiso;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* 
		*/
		protected $pesoTotalCarneDecomisadaProductivo;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Número de canales aprobadas totalmente
		*/
		protected $numCanalesAprobadasTotalmente;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Número de canales aprobadas parcialmente
		*/
		protected $numCanalesAprobadasParcialmente;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* 
		*/
		protected $pesoTotalCarneAprobadaProductivos;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Peso promedio de canal
		*/
		protected $pesoPromedioCanal;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Peso total de visceras decomisadas
		*/
		protected $pesoTotalViscerasDecomisadas;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Peso de carne para incineración
		*/
		protected $pesoCarneIncineracion;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Peso de visceras para incineracion
		*/
		protected $pesoViscerasIncineracion;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Peso de carne para rendering
		*/
		protected $pesoCarneRendering;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Peso de visceras para rendering
		*/
		protected $pesoViscerasRendering;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Peso de carne para abono
		*/
		protected $pesoCarneAbono;
		/**
		 * @var String
		 * Campo opcional
		 * Campo visible en el formulario
		 * Peso de visceras para abono
		 */
		protected $pesoViscerasAbono;
		/**
		 * @var String
		 * Campo opcional
		 * Campo visible en el formulario
		 * Peso de carne para ambiental
		 */
		protected $pesoCarneAmbiental;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Peso de visceras para ambiental
		*/
		protected $pesoViscerasAmbiental;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Lugar de incineración
		*/
		protected $lugarIncineracion;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Lugar de renderización
		*/
		protected $lugarRenderizacion;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Lugar de desconposición
		*/
		protected $lugarDesconposicion;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Nombre de gestor ambiental
		*/
		protected $nombreGestorAmbiental;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Actividades
		*/
		
		protected $descripcionActividadGeneral;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Observación del formulario
		*/
		protected $observacion;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * Actividad post médico
		 */
		protected $examenVisual;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * Actividad post médico palapación
		 */
		protected $palpacion;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * Actividad post médico insición
		 */
		protected $insicion;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * Actividad post médico toma muestra
		 */
		protected $tomaMuestra;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * Actividad post médico organo tejido
		 */
		protected $organoTejido;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * Actividad post médico descripcion organo tejido
		 */
		protected $descripcionActividad;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_centros_faenamiento";

	/**
	* Nombre de la tabla: detalle_post_animales
	* 
	 */
	Private $tabla="detalle_post_animales";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_detalle_post_animales";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_centros_faenamiento"."detalle_post_animales_id_detalle_post_animales_seq'; 



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
		throw new \Exception('Clase Modelo: DetallePostAnimalesModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: DetallePostAnimalesModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_centros_faenamiento
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idDetallePostAnimales
	*
	*Llave primaria de la tabla
	*
	* @parámetro Integer $idDetallePostAnimales
	* @return IdDetallePostAnimales
	*/
	public function setIdDetallePostAnimales($idDetallePostAnimales)
	{
	  $this->idDetallePostAnimales = (Integer) $idDetallePostAnimales;
	    return $this;
	}

	/**
	* Get idDetallePostAnimales
	*
	* @return null|Integer
	*/
	public function getIdDetallePostAnimales()
	{
		return $this->idDetallePostAnimales;
	}

	/**
	* Set idFormularioPostMortem
	*
	*Llave foránea de la tabla formulario_post_mortem
	*
	* @parámetro Integer $idFormularioPostMortem
	* @return IdFormularioPostMortem
	*/
	public function setIdFormularioPostMortem($idFormularioPostMortem)
	{
	  $this->idFormularioPostMortem = (Integer) $idFormularioPostMortem;
	    return $this;
	}

	/**
	* Get idFormularioPostMortem
	*
	* @return null|Integer
	*/
	public function getIdFormularioPostMortem()
	{
		return $this->idFormularioPostMortem;
	}

	/**
	* Set idDetalleAnteAnimales
	*
	*Llave foránea de la tabla detalle_ante_animales
	*
	* @parámetro Integer $idDetalleAnteAnimales
	* @return IdDetalleAnteAnimales
	*/
	public function setIdDetalleAnteAnimales($idDetalleAnteAnimales)
	{
	  $this->idDetalleAnteAnimales = (Integer) $idDetalleAnteAnimales;
	    return $this;
	}

	/**
	* Get idDetalleAnteAnimales
	*
	* @return null|Integer
	*/
	public function getIdDetalleAnteAnimales()
	{
		return $this->idDetalleAnteAnimales;
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
	* Set fechaFormulario
	*
	*Fecha del formulario post morten
	*
	* @parámetro Date $fechaFormulario
	* @return FechaFormulario
	*/
	public function setFechaFormulario($fechaFormulario)
	{
	  $this->fechaFormulario = (String) $fechaFormulario;
	    return $this;
	}

	/**
	* Get fechaFormulario
	*
	* @return null|Date
	*/
	public function getFechaFormulario()
	{
		return $this->fechaFormulario;
	}

	/**
	* Set estadoNodulosLinfaticos
	*
	*Estado nodulos linfaticos
	*
	* @parámetro String $estadoNodulosLinfaticos
	* @return EstadoNodulosLinfaticos
	*/
	public function setEstadoNodulosLinfaticos($estadoNodulosLinfaticos)
	{
	  $this->estadoNodulosLinfaticos = (String) $estadoNodulosLinfaticos;
	    return $this;
	}

	/**
	* Get estadoNodulosLinfaticos
	*
	* @return null|String
	*/
	public function getEstadoNodulosLinfaticos()
	{
		return $this->estadoNodulosLinfaticos;
	}

	/**
	* Set otroDiagnostico
	*
	*Registrar nuevo diagnostico
	*
	* @parámetro String $otroDiagnostico
	* @return OtroDiagnostico
	*/
	public function setOtroDiagnostico($otroDiagnostico)
	{
	  $this->otroDiagnostico = (String) $otroDiagnostico;
	    return $this;
	}

	/**
	* Get otroDiagnostico
	*
	* @return null|String
	*/
	public function getOtroDiagnostico()
	{
		return $this->otroDiagnostico;
	}

	/**
	* Set numCanalesDecomisoParcial
	*
	*Número de  canales de decomiso parcial
	*
	* @parámetro Integer $numCanalesDecomisoParcial
	* @return NumCanalesDecomisoParcial
	*/
	public function setNumCanalesDecomisoParcial($numCanalesDecomisoParcial)
	{
	  $this->numCanalesDecomisoParcial = (Integer) $numCanalesDecomisoParcial;
	    return $this;
	}

	/**
	* Get numCanalesDecomisoParcial
	*
	* @return null|Integer
	*/
	public function getNumCanalesDecomisoParcial()
	{
		return $this->numCanalesDecomisoParcial;
	}

	/**
	* Set pesoTotalCarneAprobada
	*
	*Peso total de carne aprobada
	*
	* @parámetro String $pesoTotalCarneAprobada
	* @return PesoTotalCarneAprobada
	*/
	public function setPesoTotalCarneAprobada($pesoTotalCarneAprobada)
	{
	  $this->pesoTotalCarneAprobada = (String) $pesoTotalCarneAprobada;
	    return $this;
	}

	/**
	* Get pesoTotalCarneAprobada
	*
	* @return null|String
	*/
	public function getPesoTotalCarneAprobada()
	{
		return $this->pesoTotalCarneAprobada;
	}

	/**
	* Set pesoTotalCarneDecomisada
	*
	*Peso total de carne decomisada
	*
	* @parámetro String $pesoTotalCarneDecomisada
	* @return PesoTotalCarneDecomisada
	*/
	public function setPesoTotalCarneDecomisada($pesoTotalCarneDecomisada)
	{
	  $this->pesoTotalCarneDecomisada = (String) $pesoTotalCarneDecomisada;
	    return $this;
	}

	/**
	* Get pesoTotalCarneDecomisada
	*
	* @return null|String
	*/
	public function getPesoTotalCarneDecomisada()
	{
		return $this->pesoTotalCarneDecomisada;
	}

	/**
	* Set numCanalesDecomiso
	*
	*Número de canales de decomiso
	*
	* @parámetro Integer $numCanalesDecomiso
	* @return NumCanalesDecomiso
	*/
	public function setNumCanalesDecomiso($numCanalesDecomiso)
	{
	  $this->numCanalesDecomiso = (Integer) $numCanalesDecomiso;
	    return $this;
	}

	/**
	* Get numCanalesDecomiso
	*
	* @return null|Integer
	*/
	public function getNumCanalesDecomiso()
	{
		return $this->numCanalesDecomiso;
	}

	/**
	* Set pesoTotalCarneDecomisadaProductivo
	*
	*
	*
	* @parámetro String $pesoTotalCarneDecomisadaProductivo
	* @return PesoTotalCarneDecomisadaProductivo
	*/
	public function setPesoTotalCarneDecomisadaProductivo($pesoTotalCarneDecomisadaProductivo)
	{
	  $this->pesoTotalCarneDecomisadaProductivo = (String) $pesoTotalCarneDecomisadaProductivo;
	    return $this;
	}

	/**
	* Get pesoTotalCarneDecomisadaProductivo
	*
	* @return null|String
	*/
	public function getPesoTotalCarneDecomisadaProductivo()
	{
		return $this->pesoTotalCarneDecomisadaProductivo;
	}

	/**
	* Set numCanalesAprobadasTotalmente
	*
	*Número de canales aprobadas totalmente
	*
	* @parámetro String $numCanalesAprobadasTotalmente
	* @return NumCanalesAprobadasTotalmente
	*/
	public function setNumCanalesAprobadasTotalmente($numCanalesAprobadasTotalmente)
	{
	  $this->numCanalesAprobadasTotalmente = (String) $numCanalesAprobadasTotalmente;
	    return $this;
	}

	/**
	* Get numCanalesAprobadasTotalmente
	*
	* @return null|String
	*/
	public function getNumCanalesAprobadasTotalmente()
	{
		return $this->numCanalesAprobadasTotalmente;
	}

	/**
	* Set numCanalesAprobadasParcialmente
	*
	*Número de canales aprobadas parcialmente
	*
	* @parámetro Integer $numCanalesAprobadasParcialmente
	* @return NumCanalesAprobadasParcialmente
	*/
	public function setNumCanalesAprobadasParcialmente($numCanalesAprobadasParcialmente)
	{
	  $this->numCanalesAprobadasParcialmente = (Integer) $numCanalesAprobadasParcialmente;
	    return $this;
	}

	/**
	* Get numCanalesAprobadasParcialmente
	*
	* @return null|Integer
	*/
	public function getNumCanalesAprobadasParcialmente()
	{
		return $this->numCanalesAprobadasParcialmente;
	}

	/**
	* Set pesoTotalCarneAprobadaProductivos
	*
	*
	*
	* @parámetro String $pesoTotalCarneAprobadaProductivos
	* @return PesoTotalCarneAprobadaProductivos
	*/
	public function setPesoTotalCarneAprobadaProductivos($pesoTotalCarneAprobadaProductivos)
	{
	  $this->pesoTotalCarneAprobadaProductivos = (String) $pesoTotalCarneAprobadaProductivos;
	    return $this;
	}

	/**
	* Get pesoTotalCarneAprobadaProductivos
	*
	* @return null|String
	*/
	public function getPesoTotalCarneAprobadaProductivos()
	{
		return $this->pesoTotalCarneAprobadaProductivos;
	}

	/**
	* Set pesoPromedioCanal
	*
	*Peso promedio de canal
	*
	* @parámetro String $pesoPromedioCanal
	* @return PesoPromedioCanal
	*/
	public function setPesoPromedioCanal($pesoPromedioCanal)
	{
	  $this->pesoPromedioCanal = (String) $pesoPromedioCanal;
	    return $this;
	}

	/**
	* Get pesoPromedioCanal
	*
	* @return null|String
	*/
	public function getPesoPromedioCanal()
	{
		return $this->pesoPromedioCanal;
	}

	/**
	* Set pesoTotalViscerasDecomisadas
	*
	*Peso total de visceras decomisadas
	*
	* @parámetro String $pesoTotalViscerasDecomisadas
	* @return PesoTotalViscerasDecomisadas
	*/
	public function setPesoTotalViscerasDecomisadas($pesoTotalViscerasDecomisadas)
	{
	  $this->pesoTotalViscerasDecomisadas = (String) $pesoTotalViscerasDecomisadas;
	    return $this;
	}

	/**
	* Get pesoTotalViscerasDecomisadas
	*
	* @return null|String
	*/
	public function getPesoTotalViscerasDecomisadas()
	{
		return $this->pesoTotalViscerasDecomisadas;
	}

	/**
	* Set pesoCarneIncineracion
	*
	*Peso de carne para incineración
	*
	* @parámetro String $pesoCarneIncineracion
	* @return PesoCarneIncineracion
	*/
	public function setPesoCarneIncineracion($pesoCarneIncineracion)
	{
	  $this->pesoCarneIncineracion = (String) $pesoCarneIncineracion;
	    return $this;
	}

	/**
	* Get pesoCarneIncineracion
	*
	* @return null|String
	*/
	public function getPesoCarneIncineracion()
	{
		return $this->pesoCarneIncineracion;
	}

	/**
	* Set pesoViscerasIncineracion
	*
	*Peso de visceras para incineracion
	*
	* @parámetro String $pesoViscerasIncineracion
	* @return PesoViscerasIncineracion
	*/
	public function setPesoViscerasIncineracion($pesoViscerasIncineracion)
	{
	  $this->pesoViscerasIncineracion = (String) $pesoViscerasIncineracion;
	    return $this;
	}

	/**
	* Get pesoViscerasIncineracion
	*
	* @return null|String
	*/
	public function getPesoViscerasIncineracion()
	{
		return $this->pesoViscerasIncineracion;
	}

	/**
	* Set pesoCarneRendering
	*
	*Peso de carne para rendering
	*
	* @parámetro String $pesoCarneRendering
	* @return PesoCarneRendering
	*/
	public function setPesoCarneRendering($pesoCarneRendering)
	{
	  $this->pesoCarneRendering = (String) $pesoCarneRendering;
	    return $this;
	}

	/**
	* Get pesoCarneRendering
	*
	* @return null|String
	*/
	public function getPesoCarneRendering()
	{
		return $this->pesoCarneRendering;
	}

	/**
	* Set pesoViscerasRendering
	*
	*Peso de visceras para rendering
	*
	* @parámetro String $pesoViscerasRendering
	* @return PesoViscerasRendering
	*/
	public function setPesoViscerasRendering($pesoViscerasRendering)
	{
	  $this->pesoViscerasRendering = (String) $pesoViscerasRendering;
	    return $this;
	}

	/**
	* Get pesoViscerasRendering
	*
	* @return null|String
	*/
	public function getPesoViscerasRendering()
	{
		return $this->pesoViscerasRendering;
	}

	/**
	* Set pesoCarneAbono
	*
	*Peso de carne para abono
	*
	* @parámetro String $pesoCarneAbono
	* @return PesoCarneAbono
	*/
	public function setPesoCarneAbono($pesoCarneAbono)
	{
	  $this->pesoCarneAbono = (String) $pesoCarneAbono;
	    return $this;
	}

	/**
	* Get pesoCarneAbono
	*
	* @return null|String
	*/
	public function getPesoCarneAbono()
	{
		return $this->pesoCarneAbono;
	}
	/**
	 * Set pesoViscerasAbono
	 *
	 *Peso de visceras para abono
	 *
	 * @parámetro String $pesoViscerasAbono
	 * @return PesoViscerasAbono
	 */
	public function setPesoViscerasAbono($pesoViscerasAbono)
	{
		$this->pesoViscerasAbono = (String) $pesoViscerasAbono;
		return $this;
	}
	
	/**
	 * Get pesoViscerasAbono
	 *
	 * @return null|String
	 */
	public function getPesoViscerasAbono()
	{
		return $this->pesoViscerasAbono;
	}
	
	/**
	 * Set pesoCarneAmbiental
	 *
	 *Peso de carne para ambiental
	 *
	 * @parámetro String $pesoCarneAmbiental
	 * @return PesoCarneAmbiental
	 */
	public function setPesoCarneAmbiental($pesoCarneAmbiental)
	{
		$this->pesoCarneAmbiental = (String) $pesoCarneAmbiental;
		return $this;
	}
	
	/**
	 * Get pesoCarneAmbiental
	 *
	 * @return null|String
	 */
	public function getPesoCarneAmbiental()
	{
		return $this->pesoCarneAmbiental;
	}

	/**
	* Set pesoViscerasAmbiental
	*
	*Peso de visceras para ambiental
	*
	* @parámetro String $pesoViscerasAmbiental
	* @return PesoViscerasAmbiental
	*/
	public function setPesoViscerasAmbiental($pesoViscerasAmbiental)
	{
	  $this->pesoViscerasAmbiental = (String) $pesoViscerasAmbiental;
	    return $this;
	}

	/**
	* Get pesoViscerasAmbiental
	*
	* @return null|String
	*/
	public function getPesoViscerasAmbiental()
	{
		return $this->pesoViscerasAmbiental;
	}

	/**
	* Set lugarIncineracion
	*
	*Lugar de incineración
	*
	* @parámetro String $lugarIncineracion
	* @return LugarIncineracion
	*/
	public function setLugarIncineracion($lugarIncineracion)
	{
	  $this->lugarIncineracion = (String) $lugarIncineracion;
	    return $this;
	}

	/**
	* Get lugarIncineracion
	*
	* @return null|String
	*/
	public function getLugarIncineracion()
	{
		return $this->lugarIncineracion;
	}

	/**
	* Set lugarRenderizacion
	*
	*Lugar de renderización
	*
	* @parámetro String $lugarRenderizacion
	* @return LugarRenderizacion
	*/
	public function setLugarRenderizacion($lugarRenderizacion)
	{
	  $this->lugarRenderizacion = (String) $lugarRenderizacion;
	    return $this;
	}

	/**
	* Get lugarRenderizacion
	*
	* @return null|String
	*/
	public function getLugarRenderizacion()
	{
		return $this->lugarRenderizacion;
	}

	/**
	* Set lugarDesconposicion
	*
	*Lugar de desconposición
	*
	* @parámetro String $lugarDesconposicion
	* @return LugarDesconposicion
	*/
	public function setLugarDesconposicion($lugarDesconposicion)
	{
	  $this->lugarDesconposicion = (String) $lugarDesconposicion;
	    return $this;
	}

	/**
	* Get lugarDesconposicion
	*
	* @return null|String
	*/
	public function getLugarDesconposicion()
	{
		return $this->lugarDesconposicion;
	}

	/**
	* Set nombreGestorAmbiental
	*
	*Nombre de gestor ambiental
	*
	* @parámetro String $nombreGestorAmbiental
	* @return NombreGestorAmbiental
	*/
	public function setNombreGestorAmbiental($nombreGestorAmbiental)
	{
	  $this->nombreGestorAmbiental = (String) $nombreGestorAmbiental;
	    return $this;
	}

	/**
	* Get nombreGestorAmbiental
	*
	* @return null|String
	*/
	public function getNombreGestorAmbiental()
	{
		return $this->nombreGestorAmbiental;
	}


	/**
	* Set descripcionActividad
	*
	*descripcion de la actividad
	*
	* @parámetro String $descripcionActividad
	* @return DescripcionActividad
	*/
	public function setDescripcionActividadGeneral($descripcionActividadGeneral)
	{
		$this->descripcionActividadGeneral = (String) $descripcionActividadGeneral;
	    return $this;
	}

	/**
	* Get descripcionActividadGeneral
	*
	* @return null|String
	*/
	public function getDescripcionActividadGeneral()
	{
		return $this->descripcionActividadGeneral;
	}

	/**
	* Set observacion
	*
	*Observación del formulario
	*
	* @parámetro String $observacion
	* @return Observacion
	*/
	public function setObservacion($observacion)
	{
	  $this->observacion = (String) $observacion;
	    return $this;
	}

	/**
	* Get observacion
	*
	* @return null|String
	*/
	public function getObservacion()
	{
		return $this->observacion;
	}

	/**
	 * Set examenVisual
	 *
	 *Actividad post médico
	 *
	 * @parámetro String $examenVisual
	 * @return ExamenVisual
	 */
	public function setExamenVisual($examenVisual)
	{
		$this->examenVisual = (String) $examenVisual;
		return $this;
	}
	
	/**
	 * Get examenVisual
	 *
	 * @return null|String
	 */
	public function getExamenVisual()
	{
		return $this->examenVisual;
	}
	
	/**
	 * Set palpacion
	 *
	 *Actividad post médico palapación
	 *
	 * @parámetro String $palpacion
	 * @return Palpacion
	 */
	public function setPalpacion($palpacion)
	{
		$this->palpacion = (String) $palpacion;
		return $this;
	}
	
	/**
	 * Get palpacion
	 *
	 * @return null|String
	 */
	public function getPalpacion()
	{
		return $this->palpacion;
	}
	
	/**
	 * Set insicion
	 *
	 *Actividad post médico insición
	 *
	 * @parámetro String $insicion
	 * @return Insicion
	 */
	public function setInsicion($insicion)
	{
		$this->insicion = (String) $insicion;
		return $this;
	}
	
	/**
	 * Get insicion
	 *
	 * @return null|String
	 */
	public function getInsicion()
	{
		return $this->insicion;
	}
	
	/**
	 * Set tomaMuestra
	 *
	 *Actividad post médico toma muestra
	 *
	 * @parámetro String $tomaMuestra
	 * @return TomaMuestra
	 */
	public function setTomaMuestra($tomaMuestra)
	{
		$this->tomaMuestra = (String) $tomaMuestra;
		return $this;
	}
	
	/**
	 * Get tomaMuestra
	 *
	 * @return null|String
	 */
	public function getTomaMuestra()
	{
		return $this->tomaMuestra;
	}
	
	/**
	 * Set organoTejido
	 *
	 *Actividad post médico organo tejido
	 *
	 * @parámetro String $organoTejido
	 * @return OrganoTejido
	 */
	public function setOrganoTejido($organoTejido)
	{
		$this->organoTejido = (String) $organoTejido;
		return $this;
	}
	
	/**
	 * Get organoTejido
	 *
	 * @return null|String
	 */
	public function getOrganoTejido()
	{
		return $this->organoTejido;
	}
	
	/**
	 * Set descripcionActividad
	 *
	 *Actividad post médico descripcion organo tejido
	 *
	 * @parámetro String $descripcionActividad
	 * @return DescripcionActividad
	 */
	public function setDescripcionActividad($descripcionActividad)
	{
		$this->descripcionActividad = (String) $descripcionActividad;
		return $this;
	}
	
	/**
	 * Get descripcionActividad
	 *
	 * @return null|String
	 */
	public function getDescripcionActividad()
	{
		return $this->descripcionActividad;
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
	* @return DetallePostAnimalesModelo
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
