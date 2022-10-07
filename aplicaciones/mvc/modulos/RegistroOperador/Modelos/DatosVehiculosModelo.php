<?php
 /**
 * Modelo CodigosPoaModelo
 *
 * Este archivo se complementa con el archivo   CodigosPoaLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-01-10
 * @uses    CodigosPoaModelo
 * @package RegistroOperador
 * @subpackage Modelos
 */
  namespace Agrodb\RegistroOperador\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class DatosVehiculosModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la tabla datos_vehiculos
		*/
		protected $idDatoVehiculo;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la tabla area
		*/
		protected $idArea;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador (id_tipo_operacion) de la tabla operaciones
		*/
		protected $idTipoOperacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre de la marca del vehículo registrado
		*/
		protected $nombreMarcaVehiculo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre del modelo del vehículo registrado
		*/
		protected $nombreModeloVehiculo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre del tipo del vehículo registrado
		*/
		protected $nombreTipoVehiculo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre del color del vehículo registrado
		*/
		protected $nombreColorVehiculo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre de la clase del vehículo registrado
		*/
		protected $nombreClaseVehiculo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la placa del vehículo registrado
		*/
		protected $placaVehiculo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el registro contenedor del vehículo registrado
		*/
		protected $registroContenedorVehiculo;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la tabla g_catalogos.tipos_tanque_vehiculos (llave foránea)
		*/
		protected $idTipoTanqueVehiculo;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el año del vehículo registrado
		*/
		protected $anioVehiculo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la capacidad del vehículo registrado
		*/
		protected $capacidadVehiculo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la tabla g_catalogos.unidades_medida (llave foránea)
		*/
		protected $codigoUnidadMedida;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de cración del registro del vehículo
		*/
		protected $fechaCreacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la tabla g_operadores.operadores_tipo_operaciones
		*/
		protected $idOperadorTipoOperacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Estado del vehículo se inactivara cuando la operación asociada caduque
		*/
		protected $estadoDatoVehiculo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la hora de inicio de recoleción de leche
		*/
		protected $horaInicioRecoleccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la hora de fin de recoleción de leche
		*/
		protected $horaFinRecoleccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Descripción del contenedor
		*/
		protected $tipoContenedor;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Caracteristica del contenedor
		*/
		protected $caracteristicaContenedor;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* servicio que presta el vehiculo
		*/
		protected $servicio;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_operadores";

	/**
	* Nombre de la tabla: datos_vehiculos
	* 
	 */
	Private $tabla="datos_vehiculos";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_dato_vehiculo";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_operadores"."DatosVehiculos_id_dato_vehiculo_seq'; 



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
		throw new \Exception('Clase Modelo: DatosVehiculosModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: DatosVehiculosModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_operadores
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idDatoVehiculo
	*
	*Identificador de la tabla datos_vehiculos
	*
	* @parámetro Integer $idDatoVehiculo
	* @return IdDatoVehiculo
	*/
	public function setIdDatoVehiculo($idDatoVehiculo)
	{
	  $this->idDatoVehiculo = (Integer) $idDatoVehiculo;
	    return $this;
	}

	/**
	* Get idDatoVehiculo
	*
	* @return null|Integer
	*/
	public function getIdDatoVehiculo()
	{
		return $this->idDatoVehiculo;
	}

	/**
	* Set idArea
	*
	*Identificador de la tabla area
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
	* Set idTipoOperacion
	*
	*Identificador (id_tipo_operacion) de la tabla operaciones
	*
	* @parámetro Integer $idTipoOperacion
	* @return IdTipoOperacion
	*/
	public function setIdTipoOperacion($idTipoOperacion)
	{
	  $this->idTipoOperacion = (Integer) $idTipoOperacion;
	    return $this;
	}

	/**
	* Get idTipoOperacion
	*
	* @return null|Integer
	*/
	public function getIdTipoOperacion()
	{
		return $this->idTipoOperacion;
	}

	/**
	* Set nombreMarcaVehiculo
	*
	*Campo que almacena el nombre de la marca del vehículo registrado
	*
	* @parámetro String $nombreMarcaVehiculo
	* @return NombreMarcaVehiculo
	*/
	public function setNombreMarcaVehiculo($nombreMarcaVehiculo)
	{
	  $this->nombreMarcaVehiculo = (String) $nombreMarcaVehiculo;
	    return $this;
	}

	/**
	* Get nombreMarcaVehiculo
	*
	* @return null|String
	*/
	public function getNombreMarcaVehiculo()
	{
		return $this->nombreMarcaVehiculo;
	}

	/**
	* Set nombreModeloVehiculo
	*
	*Campo que almacena el nombre del modelo del vehículo registrado
	*
	* @parámetro String $nombreModeloVehiculo
	* @return NombreModeloVehiculo
	*/
	public function setNombreModeloVehiculo($nombreModeloVehiculo)
	{
	  $this->nombreModeloVehiculo = (String) $nombreModeloVehiculo;
	    return $this;
	}

	/**
	* Get nombreModeloVehiculo
	*
	* @return null|String
	*/
	public function getNombreModeloVehiculo()
	{
		return $this->nombreModeloVehiculo;
	}

	/**
	* Set nombreTipoVehiculo
	*
	*Campo que almacena el nombre del tipo del vehículo registrado
	*
	* @parámetro String $nombreTipoVehiculo
	* @return NombreTipoVehiculo
	*/
	public function setNombreTipoVehiculo($nombreTipoVehiculo)
	{
	  $this->nombreTipoVehiculo = (String) $nombreTipoVehiculo;
	    return $this;
	}

	/**
	* Get nombreTipoVehiculo
	*
	* @return null|String
	*/
	public function getNombreTipoVehiculo()
	{
		return $this->nombreTipoVehiculo;
	}

	/**
	* Set nombreColorVehiculo
	*
	*Campo que almacena el nombre del color del vehículo registrado
	*
	* @parámetro String $nombreColorVehiculo
	* @return NombreColorVehiculo
	*/
	public function setNombreColorVehiculo($nombreColorVehiculo)
	{
	  $this->nombreColorVehiculo = (String) $nombreColorVehiculo;
	    return $this;
	}

	/**
	* Get nombreColorVehiculo
	*
	* @return null|String
	*/
	public function getNombreColorVehiculo()
	{
		return $this->nombreColorVehiculo;
	}

	/**
	* Set nombreClaseVehiculo
	*
	*Campo que almacena el nombre de la clase del vehículo registrado
	*
	* @parámetro String $nombreClaseVehiculo
	* @return NombreClaseVehiculo
	*/
	public function setNombreClaseVehiculo($nombreClaseVehiculo)
	{
	  $this->nombreClaseVehiculo = (String) $nombreClaseVehiculo;
	    return $this;
	}

	/**
	* Get nombreClaseVehiculo
	*
	* @return null|String
	*/
	public function getNombreClaseVehiculo()
	{
		return $this->nombreClaseVehiculo;
	}

	/**
	* Set placaVehiculo
	*
	*Campo que almacena la placa del vehículo registrado
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
	* Set registroContenedorVehiculo
	*
	*Campo que almacena el registro contenedor del vehículo registrado
	*
	* @parámetro String $registroContenedorVehiculo
	* @return RegistroContenedorVehiculo
	*/
	public function setRegistroContenedorVehiculo($registroContenedorVehiculo)
	{
	  $this->registroContenedorVehiculo = (String) $registroContenedorVehiculo;
	    return $this;
	}

	/**
	* Get registroContenedorVehiculo
	*
	* @return null|String
	*/
	public function getRegistroContenedorVehiculo()
	{
		return $this->registroContenedorVehiculo;
	}

	/**
	* Set idTipoTanqueVehiculo
	*
	*Identificador de la tabla g_catalogos.tipos_tanque_vehiculos (llave foránea)
	*
	* @parámetro Integer $idTipoTanqueVehiculo
	* @return IdTipoTanqueVehiculo
	*/
	public function setIdTipoTanqueVehiculo($idTipoTanqueVehiculo)
	{
	  $this->idTipoTanqueVehiculo = (Integer) $idTipoTanqueVehiculo;
	    return $this;
	}

	/**
	* Get idTipoTanqueVehiculo
	*
	* @return null|Integer
	*/
	public function getIdTipoTanqueVehiculo()
	{
		return $this->idTipoTanqueVehiculo;
	}

	/**
	* Set anioVehiculo
	*
	*Campo que almacena el año del vehículo registrado
	*
	* @parámetro Integer $anioVehiculo
	* @return AnioVehiculo
	*/
	public function setAnioVehiculo($anioVehiculo)
	{
	  $this->anioVehiculo = (Integer) $anioVehiculo;
	    return $this;
	}

	/**
	* Get anioVehiculo
	*
	* @return null|Integer
	*/
	public function getAnioVehiculo()
	{
		return $this->anioVehiculo;
	}

	/**
	* Set capacidadVehiculo
	*
	*Campo que almacena la capacidad del vehículo registrado
	*
	* @parámetro String $capacidadVehiculo
	* @return CapacidadVehiculo
	*/
	public function setCapacidadVehiculo($capacidadVehiculo)
	{
	  $this->capacidadVehiculo = (String) $capacidadVehiculo;
	    return $this;
	}

	/**
	* Get capacidadVehiculo
	*
	* @return null|String
	*/
	public function getCapacidadVehiculo()
	{
		return $this->capacidadVehiculo;
	}

	/**
	* Set codigoUnidadMedida
	*
	*Identificador de la tabla g_catalogos.unidades_medida (llave foránea)
	*
	* @parámetro String $codigoUnidadMedida
	* @return CodigoUnidadMedida
	*/
	public function setCodigoUnidadMedida($codigoUnidadMedida)
	{
	  $this->codigoUnidadMedida = (String) $codigoUnidadMedida;
	    return $this;
	}

	/**
	* Get codigoUnidadMedida
	*
	* @return null|String
	*/
	public function getCodigoUnidadMedida()
	{
		return $this->codigoUnidadMedida;
	}

	/**
	* Set fechaCreacion
	*
	*Fecha de cración del registro del vehículo
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
	* Set idOperadorTipoOperacion
	*
	*Identificador de la tabla g_operadores.operadores_tipo_operaciones
	*
	* @parámetro Integer $idOperadorTipoOperacion
	* @return IdOperadorTipoOperacion
	*/
	public function setIdOperadorTipoOperacion($idOperadorTipoOperacion)
	{
	  $this->idOperadorTipoOperacion = (Integer) $idOperadorTipoOperacion;
	    return $this;
	}

	/**
	* Get idOperadorTipoOperacion
	*
	* @return null|Integer
	*/
	public function getIdOperadorTipoOperacion()
	{
		return $this->idOperadorTipoOperacion;
	}

	/**
	* Set estadoDatoVehiculo
	*
	*Estado del vehículo se inactivara cuando la operación asociada caduque
	*
	* @parámetro String $estadoDatoVehiculo
	* @return EstadoDatoVehiculo
	*/
	public function setEstadoDatoVehiculo($estadoDatoVehiculo)
	{
	  $this->estadoDatoVehiculo = (String) $estadoDatoVehiculo;
	    return $this;
	}

	/**
	* Get estadoDatoVehiculo
	*
	* @return null|String
	*/
	public function getEstadoDatoVehiculo()
	{
		return $this->estadoDatoVehiculo;
	}

	/**
	* Set horaInicioRecoleccion
	*
	*Campo que almacena la hora de inicio de recoleción de leche
	*
	* @parámetro String $horaInicioRecoleccion
	* @return HoraInicioRecoleccion
	*/
	public function setHoraInicioRecoleccion($horaInicioRecoleccion)
	{
	  $this->horaInicioRecoleccion = (String) $horaInicioRecoleccion;
	    return $this;
	}

	/**
	* Get horaInicioRecoleccion
	*
	* @return null|String
	*/
	public function getHoraInicioRecoleccion()
	{
		return $this->horaInicioRecoleccion;
	}

	/**
	* Set horaFinRecoleccion
	*
	*Campo que almacena la hora de fin de recoleción de leche
	*
	* @parámetro String $horaFinRecoleccion
	* @return HoraFinRecoleccion
	*/
	public function setHoraFinRecoleccion($horaFinRecoleccion)
	{
	  $this->horaFinRecoleccion = (String) $horaFinRecoleccion;
	    return $this;
	}

	/**
	* Get horaFinRecoleccion
	*
	* @return null|String
	*/
	public function getHoraFinRecoleccion()
	{
		return $this->horaFinRecoleccion;
	}

	/**
	* Set tipoContenedor
	*
	*Descripción del contenedor
	*
	* @parámetro String $tipoContenedor
	* @return TipoContenedor
	*/
	public function setTipoContenedor($tipoContenedor)
	{
	  $this->tipoContenedor = (String) $tipoContenedor;
	    return $this;
	}

	/**
	* Get tipoContenedor
	*
	* @return null|String
	*/
	public function getTipoContenedor()
	{
		return $this->tipoContenedor;
	}

	/**
	* Set caracteristicaContenedor
	*
	*Caracteristica del contenedor
	*
	* @parámetro String $caracteristicaContenedor
	* @return CaracteristicaContenedor
	*/
	public function setCaracteristicaContenedor($caracteristicaContenedor)
	{
	  $this->caracteristicaContenedor = (String) $caracteristicaContenedor;
	    return $this;
	}

	/**
	* Get caracteristicaContenedor
	*
	* @return null|String
	*/
	public function getCaracteristicaContenedor()
	{
		return $this->caracteristicaContenedor;
	}

	/**
	* Set servicio
	*
	*servicio que presta el vehiculo
	*
	* @parámetro String $servicio
	* @return Servicio
	*/
	public function setServicio($servicio)
	{
	  $this->servicio = (String) $servicio;
	    return $this;
	}

	/**
	* Get servicio
	*
	* @return null|String
	*/
	public function getServicio()
	{
		return $this->servicio;
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
	* @return DatosVehiculosModelo
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
