<?php
 /**
 * Modelo CentrosAcopioModelo
 *
 * Este archivo se complementa con el archivo   CentrosAcopioLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-04-05
 * @uses    CentrosAcopioModelo
 * @package RegistroOperador
 * @subpackage Modelos
 */
  namespace Agrodb\RegistroOperador\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class CentrosAcopioModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la tabla centro_acopio
		*/
		protected $idCentroAcopio;
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
		* Tipo de operacion de la tabla operaciones
		*/
		protected $idTipoOperacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la capacidad del centro de acopio
		*/
		protected $capacidadInstalada;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la tabla unidades_medidas
		*/
		protected $codigoUnidadMedida;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena numero de trabajadores de centro de acopio
		*/
		protected $numeroTrabajadores;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la tabla laboratorios_leche
		*/
		protected $idLaboratorioLeche;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el numero de proveedores del centro de acopio
		*/
		protected $numeroProveedores;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha en la que se registra la información del centro de acopio
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
		* Estado del centro de acopio se inactivara cuando la operación asociada caduque
		*/
		protected $estadoCentroAcopio;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la hora de recolección de la mañana del centro de acopio
		*/
		protected $horaRecoleccionManiana;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la hora de recolección de la tarde del centro de acopio
		*/
		protected $horaRecoleccionTarde;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena si pertenece a MAG
		*/
		protected $perteneceMag;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el origen de la inspeccion (GUIA, aplicativoMovil)
		*/
		protected $origenInspeccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que identifica si debe generarse el checklist (generar, generado)
		*/
		protected $estadoChecklist;

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
	* Nombre de la tabla: centros_acopio
	* 
	 */
	Private $tabla="centros_acopio";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_centro_acopio";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_operadores"."centros_acopio_id_centro_acopio_seq'; 



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
		throw new \Exception('Clase Modelo: CentrosAcopioModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: CentrosAcopioModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idCentroAcopio
	*
	*Identificador de la tabla centro_acopio
	*
	* @parámetro Integer $idCentroAcopio
	* @return IdCentroAcopio
	*/
	public function setIdCentroAcopio($idCentroAcopio)
	{
	  $this->idCentroAcopio = (Integer) $idCentroAcopio;
	    return $this;
	}

	/**
	* Get idCentroAcopio
	*
	* @return null|Integer
	*/
	public function getIdCentroAcopio()
	{
		return $this->idCentroAcopio;
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
	*Tipo de operacion de la tabla operaciones
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
	* Set capacidadInstalada
	*
	*Campo que almacena la capacidad del centro de acopio
	*
	* @parámetro String $capacidadInstalada
	* @return CapacidadInstalada
	*/
	public function setCapacidadInstalada($capacidadInstalada)
	{
	  $this->capacidadInstalada = (String) $capacidadInstalada;
	    return $this;
	}

	/**
	* Get capacidadInstalada
	*
	* @return null|String
	*/
	public function getCapacidadInstalada()
	{
		return $this->capacidadInstalada;
	}

	/**
	* Set codigoUnidadMedida
	*
	*Identificador de la tabla unidades_medidas
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
	* Set numeroTrabajadores
	*
	*Campo que almacena numero de trabajadores de centro de acopio
	*
	* @parámetro Integer $numeroTrabajadores
	* @return NumeroTrabajadores
	*/
	public function setNumeroTrabajadores($numeroTrabajadores)
	{
	  $this->numeroTrabajadores = (Integer) $numeroTrabajadores;
	    return $this;
	}

	/**
	* Get numeroTrabajadores
	*
	* @return null|Integer
	*/
	public function getNumeroTrabajadores()
	{
		return $this->numeroTrabajadores;
	}

	/**
	* Set idLaboratorioLeche
	*
	*Identificador de la tabla laboratorios_leche
	*
	* @parámetro Integer $idLaboratorioLeche
	* @return IdLaboratorioLeche
	*/
	public function setIdLaboratorioLeche($idLaboratorioLeche)
	{
	  $this->idLaboratorioLeche = (Integer) $idLaboratorioLeche;
	    return $this;
	}

	/**
	* Get idLaboratorioLeche
	*
	* @return null|Integer
	*/
	public function getIdLaboratorioLeche()
	{
		return $this->idLaboratorioLeche;
	}

	/**
	* Set numeroProveedores
	*
	*Campo que almacena el numero de proveedores del centro de acopio
	*
	* @parámetro Integer $numeroProveedores
	* @return NumeroProveedores
	*/
	public function setNumeroProveedores($numeroProveedores)
	{
	  $this->numeroProveedores = (Integer) $numeroProveedores;
	    return $this;
	}

	/**
	* Get numeroProveedores
	*
	* @return null|Integer
	*/
	public function getNumeroProveedores()
	{
		return $this->numeroProveedores;
	}

	/**
	* Set fechaCreacion
	*
	*Fecha en la que se registra la información del centro de acopio
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
	* Set estadoCentroAcopio
	*
	*Estado del centro de acopio se inactivara cuando la operación asociada caduque
	*
	* @parámetro String $estadoCentroAcopio
	* @return EstadoCentroAcopio
	*/
	public function setEstadoCentroAcopio($estadoCentroAcopio)
	{
	  $this->estadoCentroAcopio = (String) $estadoCentroAcopio;
	    return $this;
	}

	/**
	* Get estadoCentroAcopio
	*
	* @return null|String
	*/
	public function getEstadoCentroAcopio()
	{
		return $this->estadoCentroAcopio;
	}

	/**
	* Set horaRecoleccionManiana
	*
	*Campo que almacena la hora de recolección de la mañana del centro de acopio
	*
	* @parámetro String $horaRecoleccionManiana
	* @return HoraRecoleccionManiana
	*/
	public function setHoraRecoleccionManiana($horaRecoleccionManiana)
	{
	  $this->horaRecoleccionManiana = (String) $horaRecoleccionManiana;
	    return $this;
	}

	/**
	* Get horaRecoleccionManiana
	*
	* @return null|String
	*/
	public function getHoraRecoleccionManiana()
	{
		return $this->horaRecoleccionManiana;
	}

	/**
	* Set horaRecoleccionTarde
	*
	*Campo que almacena la hora de recolección de la tarde del centro de acopio
	*
	* @parámetro String $horaRecoleccionTarde
	* @return HoraRecoleccionTarde
	*/
	public function setHoraRecoleccionTarde($horaRecoleccionTarde)
	{
	  $this->horaRecoleccionTarde = (String) $horaRecoleccionTarde;
	    return $this;
	}

	/**
	* Get horaRecoleccionTarde
	*
	* @return null|String
	*/
	public function getHoraRecoleccionTarde()
	{
		return $this->horaRecoleccionTarde;
	}

	/**
	* Set perteneceMag
	*
	*Campo que almacena si pertenece a MAG
	*
	* @parámetro String $perteneceMag
	* @return PerteneceMag
	*/
	public function setPerteneceMag($perteneceMag)
	{
	  $this->perteneceMag = (String) $perteneceMag;
	    return $this;
	}

	/**
	* Get perteneceMag
	*
	* @return null|String
	*/
	public function getPerteneceMag()
	{
		return $this->perteneceMag;
	}

	/**
	* Set origenInspeccion
	*
	*Campo que almacena el origen de la inspeccion (GUIA, aplicativoMovil)
	*
	* @parámetro String $origenInspeccion
	* @return OrigenInspeccion
	*/
	public function setOrigenInspeccion($origenInspeccion)
	{
	  $this->origenInspeccion = (String) $origenInspeccion;
	    return $this;
	}

	/**
	* Get origenInspeccion
	*
	* @return null|String
	*/
	public function getOrigenInspeccion()
	{
		return $this->origenInspeccion;
	}

	/**
	* Set estadoChecklist
	*
	*Campo que identifica si debe generarse el checklist (generar, generado)
	*
	* @parámetro String $estadoChecklist
	* @return EstadoChecklist
	*/
	public function setEstadoChecklist($estadoChecklist)
	{
	  $this->estadoChecklist = (String) $estadoChecklist;
	    return $this;
	}

	/**
	* Get estadoChecklist
	*
	* @return null|String
	*/
	public function getEstadoChecklist()
	{
		return $this->estadoChecklist;
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
	* @return CentrosAcopioModelo
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
