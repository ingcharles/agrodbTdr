<?php
 /**
 * Modelo ExportadoresProductosModelo
 *
 * Este archivo se complementa con el archivo   ExportadoresProductosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-01-14
 * @uses    ExportadoresProductosModelo
 * @package CertificadoFItosanitario
 * @subpackage Modelos
 */
  namespace Agrodb\CertificadoFItosanitario\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class ExportadoresProductosModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador único de la tabla
		*/
		protected $idExportadorProducto;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la tabla certificado_fitosanitario (llave foránea)
		*/
		protected $idCertificadoFitosanitario;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el RUC/Cédula del exportador
		*/
		protected $identificadorExportador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la razón social o nombre del operador exportador
		*/
		protected $razonSocialExportador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la dirección del exportador
		*/
		protected $direccionExportador;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la tabla tipo_producto
		*/
		protected $idTipoProducto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre del tipo de producto
		*/
		protected $nombreTipoProducto;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la tabla subtipo_producto
		*/
		protected $idSubtipoProducto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre del subtipo de producto
		*/
		protected $nombreSubtipoProducto;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la tabla producto
		*/
		protected $idProducto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre del producto
		*/
		protected $nombreProducto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el código de certificación orgánica
		*/
		protected $certificacionOrganica;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la partida arancelaria del producto (verificar si se guarda)
		*/
		protected $partidaArancelariaProducto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la cantidad comercial
		*/
		protected $cantidadComercial;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la tabla unidad_medida
		*/
		protected $idUnidadCantidadComercial;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre de la unidad de medida Ejm:KILOGRAMO
		*/
		protected $nombreUnidadCantidadComercial;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el peso bruto
		*/
		protected $pesoBruto;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la tabla unidad_medida
		*/
		protected $idUnidadPesoBruto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre de la unidad de medida Ejm:KILOGRAMO
		*/
		protected $nombreUnidadPesoBruto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el peso neto
		*/
		protected $pesoNeto;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la tabla unidad_medida
		*/
		protected $idUnidadPesoNeto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre de la unidad de medida Ejm:KILOGRAMO
		*/
		protected $nombreUnidadPesoNeto;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador único de la tabla areas del exportador
		*/
		protected $idArea;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre del área del exportador
		*/
		protected $nombreArea;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el código de área (centro de acopio, solo para ornamentales y musáceas)
		*/
		protected $codigoCentroAcopio;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el id de la provincia donde se encuentra el centro de acopio
		*/
		protected $idProvinciaArea;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre de la provincia donde se encuentra el centro de acopio
		*/
		protected $nombreProvinciaArea;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la fecha de inspección (Solo para ornamentales y musáceas)
		*/
		protected $fechaInspeccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la hora de inspección (Solo para ornamentales y musáceas)
		*/
		protected $horaInspeccion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador único de la tabla tipos_tratamiento
		*/
		protected $idTipoTratamiento;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre del tipo de tratamiento
		*/
		protected $nombreTipoTratamiento;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador único de la tabla tratamientos
		*/
		protected $idTratamiento;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre del tratamiento
		*/
		protected $nombreTratamiento;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la duración del tratamiento
		*/
		protected $duracionTratamiento;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador único de la tabla unidades_duracion
		*/
		protected $idUnidadDuracion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la unidad de duración del tratamiento
		*/
		protected $nombreUnidadDuracion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la temperatura del tratamiento
		*/
		protected $temperaturaTratamiento;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador único de la tabla unidades_temperatura
		*/
		protected $idUnidadTemperatura;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el código de la temperatura de tratamiento
		*/
		protected $nombreUnidadTemperatura;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la fecha del tratamiento
		*/
		protected $fechaTratamiento;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre del producto químico
		*/
		protected $productoQuimico;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la cantidad de concentracion de tratamiento
		*/
		protected $concentracionTratamiento;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador único de la tabla concentraciones_tratamiento
		*/
		protected $idUnidadConcentracion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la concentración
		*/
		protected $nombreUnidadConcentracion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el estado del exportador y producto
		*/
		protected $estadoExportadorProducto;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la última fecha de revisión del centro de acopio
		*/
		protected $fechaRevision;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el el último tipo de revisión del centro de acopio
		*/
		protected $tipoRevision;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el último identificador que realiza la revisión del centro de acopio
		*/
		protected $identificadorRevision;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la última observación realizada al centro de acopio
		*/
		protected $observacionRevision;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_certificado_fitosanitario";

	/**
	* Nombre de la tabla: exportadores_productos
	* 
	 */
	Private $tabla="exportadores_productos";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_exportador_producto";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_certificado_fitosanitario"."exportadores_productos_id_exportador_producto_seq'; 



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
		throw new \Exception('Clase Modelo: ExportadoresProductosModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: ExportadoresProductosModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_certificado_fitosanitario
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idExportadorProducto
	*
	*Identificador único de la tabla
	*
	* @parámetro Integer $idExportadorProducto
	* @return IdExportadorProducto
	*/
	public function setIdExportadorProducto($idExportadorProducto)
	{
	  $this->idExportadorProducto = (Integer) $idExportadorProducto;
	    return $this;
	}

	/**
	* Get idExportadorProducto
	*
	* @return null|Integer
	*/
	public function getIdExportadorProducto()
	{
		return $this->idExportadorProducto;
	}

	/**
	* Set idCertificadoFitosanitario
	*
	*Identificador de la tabla certificado_fitosanitario (llave foránea)
	*
	* @parámetro Integer $idCertificadoFitosanitario
	* @return IdCertificadoFitosanitario
	*/
	public function setIdCertificadoFitosanitario($idCertificadoFitosanitario)
	{
	  $this->idCertificadoFitosanitario = (Integer) $idCertificadoFitosanitario;
	    return $this;
	}

	/**
	* Get idCertificadoFitosanitario
	*
	* @return null|Integer
	*/
	public function getIdCertificadoFitosanitario()
	{
		return $this->idCertificadoFitosanitario;
	}

	/**
	* Set identificadorExportador
	*
	*Campo que almacena el RUC/Cédula del exportador
	*
	* @parámetro String $identificadorExportador
	* @return IdentificadorExportador
	*/
	public function setIdentificadorExportador($identificadorExportador)
	{
	  $this->identificadorExportador = (String) $identificadorExportador;
	    return $this;
	}

	/**
	* Get identificadorExportador
	*
	* @return null|String
	*/
	public function getIdentificadorExportador()
	{
		return $this->identificadorExportador;
	}

	/**
	* Set razonSocialExportador
	*
	*Campo que almacena la razón social o nombre del operador exportador
	*
	* @parámetro String $razonSocialExportador
	* @return RazonSocialExportador
	*/
	public function setRazonSocialExportador($razonSocialExportador)
	{
	  $this->razonSocialExportador = (String) $razonSocialExportador;
	    return $this;
	}

	/**
	* Get razonSocialExportador
	*
	* @return null|String
	*/
	public function getRazonSocialExportador()
	{
		return $this->razonSocialExportador;
	}

	/**
	* Set direccionExportador
	*
	*Campo que almacena la dirección del exportador
	*
	* @parámetro String $direccionExportador
	* @return DireccionExportador
	*/
	public function setDireccionExportador($direccionExportador)
	{
	  $this->direccionExportador = (String) $direccionExportador;
	    return $this;
	}

	/**
	* Get direccionExportador
	*
	* @return null|String
	*/
	public function getDireccionExportador()
	{
		return $this->direccionExportador;
	}

	/**
	* Set idTipoProducto
	*
	*Identificador de la tabla tipo_producto
	*
	* @parámetro Integer $idTipoProducto
	* @return IdTipoProducto
	*/
	public function setIdTipoProducto($idTipoProducto)
	{
	  $this->idTipoProducto = (Integer) $idTipoProducto;
	    return $this;
	}

	/**
	* Get idTipoProducto
	*
	* @return null|Integer
	*/
	public function getIdTipoProducto()
	{
		return $this->idTipoProducto;
	}

	/**
	* Set nombreTipoProducto
	*
	*Campo que almacena el nombre del tipo de producto
	*
	* @parámetro String $nombreTipoProducto
	* @return NombreTipoProducto
	*/
	public function setNombreTipoProducto($nombreTipoProducto)
	{
	  $this->nombreTipoProducto = (String) $nombreTipoProducto;
	    return $this;
	}

	/**
	* Get nombreTipoProducto
	*
	* @return null|String
	*/
	public function getNombreTipoProducto()
	{
		return $this->nombreTipoProducto;
	}

	/**
	* Set idSubtipoProducto
	*
	*Identificador de la tabla subtipo_producto
	*
	* @parámetro Integer $idSubtipoProducto
	* @return IdSubtipoProducto
	*/
	public function setIdSubtipoProducto($idSubtipoProducto)
	{
	  $this->idSubtipoProducto = (Integer) $idSubtipoProducto;
	    return $this;
	}

	/**
	* Get idSubtipoProducto
	*
	* @return null|Integer
	*/
	public function getIdSubtipoProducto()
	{
		return $this->idSubtipoProducto;
	}

	/**
	* Set nombreSubtipoProducto
	*
	*Campo que almacena el nombre del subtipo de producto
	*
	* @parámetro String $nombreSubtipoProducto
	* @return NombreSubtipoProducto
	*/
	public function setNombreSubtipoProducto($nombreSubtipoProducto)
	{
	  $this->nombreSubtipoProducto = (String) $nombreSubtipoProducto;
	    return $this;
	}

	/**
	* Get nombreSubtipoProducto
	*
	* @return null|String
	*/
	public function getNombreSubtipoProducto()
	{
		return $this->nombreSubtipoProducto;
	}

	/**
	* Set idProducto
	*
	*Identificador de la tabla producto
	*
	* @parámetro Integer $idProducto
	* @return IdProducto
	*/
	public function setIdProducto($idProducto)
	{
	  $this->idProducto = (Integer) $idProducto;
	    return $this;
	}

	/**
	* Get idProducto
	*
	* @return null|Integer
	*/
	public function getIdProducto()
	{
		return $this->idProducto;
	}

	/**
	* Set nombreProducto
	*
	*Campo que almacena el nombre del producto
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
	* Set certificacionOrganica
	*
	*Campo que almacena el código de certificación orgánica
	*
	* @parámetro String $certificacionOrganica
	* @return CertificacionOrganica
	*/
	public function setCertificacionOrganica($certificacionOrganica)
	{
	  $this->certificacionOrganica = (String) $certificacionOrganica;
	    return $this;
	}

	/**
	* Get certificacionOrganica
	*
	* @return null|String
	*/
	public function getCertificacionOrganica()
	{
		return $this->certificacionOrganica;
	}

	/**
	* Set partidaArancelariaProducto
	*
	*Campo que almacena la partida arancelaria del producto (verificar si se guarda)
	*
	* @parámetro String $partidaArancelariaProducto
	* @return PartidaArancelariaProducto
	*/
	public function setPartidaArancelariaProducto($partidaArancelariaProducto)
	{
	  $this->partidaArancelariaProducto = (String) $partidaArancelariaProducto;
	    return $this;
	}

	/**
	* Get partidaArancelariaProducto
	*
	* @return null|String
	*/
	public function getPartidaArancelariaProducto()
	{
		return $this->partidaArancelariaProducto;
	}

	/**
	* Set cantidadComercial
	*
	*Campo que almacena la cantidad comercial
	*
	* @parámetro String $cantidadComercial
	* @return CantidadComercial
	*/
	public function setCantidadComercial($cantidadComercial)
	{
	  $this->cantidadComercial = (String) $cantidadComercial;
	    return $this;
	}

	/**
	* Get cantidadComercial
	*
	* @return null|String
	*/
	public function getCantidadComercial()
	{
		return $this->cantidadComercial;
	}

	/**
	* Set idUnidadCantidadComercial
	*
	*Identificador de la tabla unidad_medida
	*
	* @parámetro Integer $idUnidadCantidadComercial
	* @return IdUnidadCantidadComercial
	*/
	public function setIdUnidadCantidadComercial($idUnidadCantidadComercial)
	{
	  $this->idUnidadCantidadComercial = (Integer) $idUnidadCantidadComercial;
	    return $this;
	}

	/**
	* Get idUnidadCantidadComercial
	*
	* @return null|Integer
	*/
	public function getIdUnidadCantidadComercial()
	{
		return $this->idUnidadCantidadComercial;
	}

	/**
	* Set nombreUnidadCantidadComercial
	*
	*Campo que almacena el nombre de la unidad de medida Ejm:KILOGRAMO
	*
	* @parámetro String $nombreUnidadCantidadComercial
	* @return NombreUnidadCantidadComercial
	*/
	public function setNombreUnidadCantidadComercial($nombreUnidadCantidadComercial)
	{
	  $this->nombreUnidadCantidadComercial = (String) $nombreUnidadCantidadComercial;
	    return $this;
	}

	/**
	* Get nombreUnidadCantidadComercial
	*
	* @return null|String
	*/
	public function getNombreUnidadCantidadComercial()
	{
		return $this->nombreUnidadCantidadComercial;
	}

	/**
	* Set pesoBruto
	*
	*Campo que almacena el peso bruto
	*
	* @parámetro String $pesoBruto
	* @return PesoBruto
	*/
	public function setPesoBruto($pesoBruto)
	{
	  $this->pesoBruto = (String) $pesoBruto;
	    return $this;
	}

	/**
	* Get pesoBruto
	*
	* @return null|String
	*/
	public function getPesoBruto()
	{
		return $this->pesoBruto;
	}

	/**
	* Set idUnidadPesoBruto
	*
	*Identificador de la tabla unidad_medida
	*
	* @parámetro Integer $idUnidadPesoBruto
	* @return IdUnidadPesoBruto
	*/
	public function setIdUnidadPesoBruto($idUnidadPesoBruto)
	{
	  $this->idUnidadPesoBruto = (Integer) $idUnidadPesoBruto;
	    return $this;
	}

	/**
	* Get idUnidadPesoBruto
	*
	* @return null|Integer
	*/
	public function getIdUnidadPesoBruto()
	{
		return $this->idUnidadPesoBruto;
	}

	/**
	* Set nombreUnidadPesoBruto
	*
	*Campo que almacena el nombre de la unidad de medida Ejm:KILOGRAMO
	*
	* @parámetro String $nombreUnidadPesoBruto
	* @return NombreUnidadPesoBruto
	*/
	public function setNombreUnidadPesoBruto($nombreUnidadPesoBruto)
	{
	  $this->nombreUnidadPesoBruto = (String) $nombreUnidadPesoBruto;
	    return $this;
	}

	/**
	* Get nombreUnidadPesoBruto
	*
	* @return null|String
	*/
	public function getNombreUnidadPesoBruto()
	{
		return $this->nombreUnidadPesoBruto;
	}

	/**
	* Set pesoNeto
	*
	*Campo que almacena el peso neto
	*
	* @parámetro String $pesoNeto
	* @return PesoNeto
	*/
	public function setPesoNeto($pesoNeto)
	{
	  $this->pesoNeto = (String) $pesoNeto;
	    return $this;
	}

	/**
	* Get pesoNeto
	*
	* @return null|String
	*/
	public function getPesoNeto()
	{
		return $this->pesoNeto;
	}

	/**
	* Set idUnidadPesoNeto
	*
	*Identificador de la tabla unidad_medida
	*
	* @parámetro Integer $idUnidadPesoNeto
	* @return IdUnidadPesoNeto
	*/
	public function setIdUnidadPesoNeto($idUnidadPesoNeto)
	{
	  $this->idUnidadPesoNeto = (Integer) $idUnidadPesoNeto;
	    return $this;
	}

	/**
	* Get idUnidadPesoNeto
	*
	* @return null|Integer
	*/
	public function getIdUnidadPesoNeto()
	{
		return $this->idUnidadPesoNeto;
	}

	/**
	* Set nombreUnidadPesoNeto
	*
	*Campo que almacena el nombre de la unidad de medida Ejm:KILOGRAMO
	*
	* @parámetro String $nombreUnidadPesoNeto
	* @return NombreUnidadPesoNeto
	*/
	public function setNombreUnidadPesoNeto($nombreUnidadPesoNeto)
	{
	  $this->nombreUnidadPesoNeto = (String) $nombreUnidadPesoNeto;
	    return $this;
	}

	/**
	* Get nombreUnidadPesoNeto
	*
	* @return null|String
	*/
	public function getNombreUnidadPesoNeto()
	{
		return $this->nombreUnidadPesoNeto;
	}

	/**
	* Set idArea
	*
	*Identificador único de la tabla areas del exportador
	*
	* @parámetro Integer $idArea
	* @return IdArea
	*/
	public function setIdArea($idArea)
	{
	  $this->idArea = (Integer) $idArea;
	    return $this;
	}

	/**
	* Get idArea
	*
	* @return null|Integer
	*/
	public function getIdArea()
	{
		return $this->idArea;
	}

	/**
	* Set nombreArea
	*
	*Campo que almacena el nombre del área del exportador
	*
	* @parámetro String $nombreArea
	* @return NombreArea
	*/
	public function setNombreArea($nombreArea)
	{
	  $this->nombreArea = (String) $nombreArea;
	    return $this;
	}

	/**
	* Get nombreArea
	*
	* @return null|String
	*/
	public function getNombreArea()
	{
		return $this->nombreArea;
	}

	/**
	* Set codigoCentroAcopio
	*
	*Campo que almacena el código de área (centro de acopio, solo para ornamentales y musáceas)
	*
	* @parámetro String $codigoCentroAcopio
	* @return CodigoCentroAcopio
	*/
	public function setCodigoCentroAcopio($codigoCentroAcopio)
	{
	  $this->codigoCentroAcopio = (String) $codigoCentroAcopio;
	    return $this;
	}

	/**
	* Get codigoCentroAcopio
	*
	* @return null|String
	*/
	public function getCodigoCentroAcopio()
	{
		return $this->codigoCentroAcopio;
	}

	/**
	* Set idProvinciaArea
	*
	*Campo que almacena el id de la provincia donde se encuentra el centro de acopio
	*
	* @parámetro Integer $idProvinciaArea
	* @return IdProvinciaArea
	*/
	public function setIdProvinciaArea($idProvinciaArea)
	{
	  $this->idProvinciaArea = (Integer) $idProvinciaArea;
	    return $this;
	}

	/**
	* Get idProvinciaArea
	*
	* @return null|Integer
	*/
	public function getIdProvinciaArea()
	{
		return $this->idProvinciaArea;
	}

	/**
	* Set nombreProvinciaArea
	*
	*Campo que almacena el nombre de la provincia donde se encuentra el centro de acopio
	*
	* @parámetro String $nombreProvinciaArea
	* @return NombreProvinciaArea
	*/
	public function setNombreProvinciaArea($nombreProvinciaArea)
	{
	  $this->nombreProvinciaArea = (String) $nombreProvinciaArea;
	    return $this;
	}

	/**
	* Get nombreProvinciaArea
	*
	* @return null|String
	*/
	public function getNombreProvinciaArea()
	{
		return $this->nombreProvinciaArea;
	}

	/**
	* Set fechaInspeccion
	*
	*Campo que almacena la fecha de inspección (Solo para ornamentales y musáceas)
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
	* Set horaInspeccion
	*
	*Campo que almacena la hora de inspección (Solo para ornamentales y musáceas)
	*
	* @parámetro String $horaInspeccion
	* @return HoraInspeccion
	*/
	public function setHoraInspeccion($horaInspeccion)
	{
	  $this->horaInspeccion = (String) $horaInspeccion;
	    return $this;
	}

	/**
	* Get horaInspeccion
	*
	* @return null|String
	*/
	public function getHoraInspeccion()
	{
		return $this->horaInspeccion;
	}

	/**
	* Set idTipoTratamiento
	*
	*Identificador único de la tabla tipos_tratamiento
	*
	* @parámetro Integer $idTipoTratamiento
	* @return IdTipoTratamiento
	*/
	public function setIdTipoTratamiento($idTipoTratamiento)
	{
	  $this->idTipoTratamiento = (Integer) $idTipoTratamiento;
	    return $this;
	}

	/**
	* Get idTipoTratamiento
	*
	* @return null|Integer
	*/
	public function getIdTipoTratamiento()
	{
		return $this->idTipoTratamiento;
	}

	/**
	* Set nombreTipoTratamiento
	*
	*Campo que almacena el nombre del tipo de tratamiento
	*
	* @parámetro String $nombreTipoTratamiento
	* @return NombreTipoTratamiento
	*/
	public function setNombreTipoTratamiento($nombreTipoTratamiento)
	{
	  $this->nombreTipoTratamiento = (String) $nombreTipoTratamiento;
	    return $this;
	}

	/**
	* Get nombreTipoTratamiento
	*
	* @return null|String
	*/
	public function getNombreTipoTratamiento()
	{
		return $this->nombreTipoTratamiento;
	}

	/**
	* Set idTratamiento
	*
	*Identificador único de la tabla tratamientos
	*
	* @parámetro Integer $idTratamiento
	* @return IdTratamiento
	*/
	public function setIdTratamiento($idTratamiento)
	{
	  $this->idTratamiento = (Integer) $idTratamiento;
	    return $this;
	}

	/**
	* Get idTratamiento
	*
	* @return null|Integer
	*/
	public function getIdTratamiento()
	{
		return $this->idTratamiento;
	}

	/**
	* Set nombreTratamiento
	*
	*Campo que almacena el nombre del tratamiento
	*
	* @parámetro String $nombreTratamiento
	* @return NombreTratamiento
	*/
	public function setNombreTratamiento($nombreTratamiento)
	{
	  $this->nombreTratamiento = (String) $nombreTratamiento;
	    return $this;
	}

	/**
	* Get nombreTratamiento
	*
	* @return null|String
	*/
	public function getNombreTratamiento()
	{
		return $this->nombreTratamiento;
	}

	/**
	* Set duracionTratamiento
	*
	*Campo que almacena la duración del tratamiento
	*
	* @parámetro String $duracionTratamiento
	* @return DuracionTratamiento
	*/
	public function setDuracionTratamiento($duracionTratamiento)
	{
	  $this->duracionTratamiento = (String) $duracionTratamiento;
	    return $this;
	}

	/**
	* Get duracionTratamiento
	*
	* @return null|String
	*/
	public function getDuracionTratamiento()
	{
		return $this->duracionTratamiento;
	}

	/**
	* Set idUnidadDuracion
	*
	*Identificador único de la tabla unidades_duracion
	*
	* @parámetro Integer $idUnidadDuracion
	* @return IdUnidadDuracion
	*/
	public function setIdUnidadDuracion($idUnidadDuracion)
	{
	  $this->idUnidadDuracion = (Integer) $idUnidadDuracion;
	    return $this;
	}

	/**
	* Get idUnidadDuracion
	*
	* @return null|Integer
	*/
	public function getIdUnidadDuracion()
	{
		return $this->idUnidadDuracion;
	}

	/**
	* Set nombreUnidadDuracion
	*
	*Campo que almacena la unidad de duración del tratamiento
	*
	* @parámetro String $nombreUnidadDuracion
	* @return NombreUnidadDuracion
	*/
	public function setNombreUnidadDuracion($nombreUnidadDuracion)
	{
	  $this->nombreUnidadDuracion = (String) $nombreUnidadDuracion;
	    return $this;
	}

	/**
	* Get nombreUnidadDuracion
	*
	* @return null|String
	*/
	public function getNombreUnidadDuracion()
	{
		return $this->nombreUnidadDuracion;
	}

	/**
	* Set temperaturaTratamiento
	*
	*Campo que almacena la temperatura del tratamiento
	*
	* @parámetro String $temperaturaTratamiento
	* @return TemperaturaTratamiento
	*/
	public function setTemperaturaTratamiento($temperaturaTratamiento)
	{
	  $this->temperaturaTratamiento = (String) $temperaturaTratamiento;
	    return $this;
	}

	/**
	* Get temperaturaTratamiento
	*
	* @return null|String
	*/
	public function getTemperaturaTratamiento()
	{
		return $this->temperaturaTratamiento;
	}

	/**
	* Set idUnidadTemperatura
	*
	*Identificador único de la tabla unidades_temperatura
	*
	* @parámetro Integer $idUnidadTemperatura
	* @return IdUnidadTemperatura
	*/
	public function setIdUnidadTemperatura($idUnidadTemperatura)
	{
	  $this->idUnidadTemperatura = (Integer) $idUnidadTemperatura;
	    return $this;
	}

	/**
	* Get idUnidadTemperatura
	*
	* @return null|Integer
	*/
	public function getIdUnidadTemperatura()
	{
		return $this->idUnidadTemperatura;
	}

	/**
	* Set nombreUnidadTemperatura
	*
	*Campo que almacena el código de la temperatura de tratamiento
	*
	* @parámetro String $nombreUnidadTemperatura
	* @return NombreUnidadTemperatura
	*/
	public function setNombreUnidadTemperatura($nombreUnidadTemperatura)
	{
	  $this->nombreUnidadTemperatura = (String) $nombreUnidadTemperatura;
	    return $this;
	}

	/**
	* Get nombreUnidadTemperatura
	*
	* @return null|String
	*/
	public function getNombreUnidadTemperatura()
	{
		return $this->nombreUnidadTemperatura;
	}

	/**
	* Set fechaTratamiento
	*
	*Campo que almacena la fecha del tratamiento
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
	* Set productoQuimico
	*
	*Campo que almacena el nombre del producto químico
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
	* Set concentracionTratamiento
	*
	*Campo que almacena la cantidad de concentracion de tratamiento
	*
	* @parámetro String $concentracionTratamiento
	* @return ConcentracionTratamiento
	*/
	public function setConcentracionTratamiento($concentracionTratamiento)
	{
	  $this->concentracionTratamiento = (String) $concentracionTratamiento;
	    return $this;
	}

	/**
	* Get concentracionTratamiento
	*
	* @return null|String
	*/
	public function getConcentracionTratamiento()
	{
		return $this->concentracionTratamiento;
	}

	/**
	* Set idUnidadConcentracion
	*
	*Identificador único de la tabla concentraciones_tratamiento
	*
	* @parámetro Integer $idUnidadConcentracion
	* @return IdUnidadConcentracion
	*/
	public function setIdUnidadConcentracion($idUnidadConcentracion)
	{
	  $this->idUnidadConcentracion = (Integer) $idUnidadConcentracion;
	    return $this;
	}

	/**
	* Get idUnidadConcentracion
	*
	* @return null|Integer
	*/
	public function getIdUnidadConcentracion()
	{
		return $this->idUnidadConcentracion;
	}

	/**
	* Set nombreUnidadConcentracion
	*
	*Campo que almacena la concentración
	*
	* @parámetro String $nombreUnidadConcentracion
	* @return NombreUnidadConcentracion
	*/
	public function setNombreUnidadConcentracion($nombreUnidadConcentracion)
	{
	  $this->nombreUnidadConcentracion = (String) $nombreUnidadConcentracion;
	    return $this;
	}

	/**
	* Get nombreUnidadConcentracion
	*
	* @return null|String
	*/
	public function getNombreUnidadConcentracion()
	{
		return $this->nombreUnidadConcentracion;
	}

	/**
	* Set estadoExportadorProducto
	*
	*Campo que almacena el estado del exportador y producto
	*
	* @parámetro String $estadoExportadorProducto
	* @return EstadoExportadorProducto
	*/
	public function setEstadoExportadorProducto($estadoExportadorProducto)
	{
	  $this->estadoExportadorProducto = (String) $estadoExportadorProducto;
	    return $this;
	}

	/**
	* Get estadoExportadorProducto
	*
	* @return null|String
	*/
	public function getEstadoExportadorProducto()
	{
		return $this->estadoExportadorProducto;
	}

	/**
	* Set fechaRevision
	*
	*Campo que almacena la última fecha de revisión del centro de acopio
	*
	* @parámetro Date $fechaRevision
	* @return FechaRevision
	*/
	public function setFechaRevision($fechaRevision)
	{
	  $this->fechaRevision = (String) $fechaRevision;
	    return $this;
	}

	/**
	* Get fechaRevision
	*
	* @return null|Date
	*/
	public function getFechaRevision()
	{
		return $this->fechaRevision;
	}

	/**
	* Set tipoRevision
	*
	*Campo que almacena el el último tipo de revisión del centro de acopio
	*
	* @parámetro String $tipoRevision
	* @return TipoRevision
	*/
	public function setTipoRevision($tipoRevision)
	{
	  $this->tipoRevision = (String) $tipoRevision;
	    return $this;
	}

	/**
	* Get tipoRevision
	*
	* @return null|String
	*/
	public function getTipoRevision()
	{
		return $this->tipoRevision;
	}

	/**
	* Set identificadorRevision
	*
	*Campo que almacena el último identificador que realiza la revisión del centro de acopio
	*
	* @parámetro String $identificadorRevision
	* @return IdentificadorRevision
	*/
	public function setIdentificadorRevision($identificadorRevision)
	{
	  $this->identificadorRevision = (String) $identificadorRevision;
	    return $this;
	}

	/**
	* Get identificadorRevision
	*
	* @return null|String
	*/
	public function getIdentificadorRevision()
	{
		return $this->identificadorRevision;
	}

	/**
	* Set observacionRevision
	*
	*Campo que almacena la última observación realizada al centro de acopio
	*
	* @parámetro String $observacionRevision
	* @return ObservacionRevision
	*/
	public function setObservacionRevision($observacionRevision)
	{
	  $this->observacionRevision = (String) $observacionRevision;
	    return $this;
	}

	/**
	* Get observacionRevision
	*
	* @return null|String
	*/
	public function getObservacionRevision()
	{
		return $this->observacionRevision;
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
	* @return ExportadoresProductosModelo
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
