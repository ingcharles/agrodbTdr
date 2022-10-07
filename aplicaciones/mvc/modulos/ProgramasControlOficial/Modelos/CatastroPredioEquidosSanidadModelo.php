<?php
 /**
 * Modelo CatastroPredioEquidosSanidadModelo
 *
 * Este archivo se complementa con el archivo   CatastroPredioEquidosSanidadLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-02-16
 * @uses    CatastroPredioEquidosSanidadModelo
 * @package ProgramasControlOficial
 * @subpackage Modelos
 */
  namespace Agrodb\ProgramasControlOficial\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class CatastroPredioEquidosSanidadModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idCatastroPredioEquidosSanidad;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idCatastroPredioEquidos;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $identificador;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $fechaCreacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $profesionalTecnico;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $pesebreras;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $areaCuarentena;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $eliminacionDesechos;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $controlVectores;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $usoAperosIndividuales;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $reportePositivoAie;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idMedidaSanitaria;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $medidaSanitaria;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_programas_control_oficial";

	/**
	* Nombre de la tabla: catastro_predio_equidos_sanidad
	* 
	 */
	Private $tabla="catastro_predio_equidos_sanidad";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_catastro_predio_equidos_sanidad";



	/**
	*Secuencia
*/
		 private $secuencial = '"CatastroPredioEquidosSanidad_"id_catastro_predio_equidos_sanidad_seq'; 



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
		throw new \Exception('Clase Modelo: CatastroPredioEquidosSanidadModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: CatastroPredioEquidosSanidadModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_programas_control_oficial
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idCatastroPredioEquidosSanidad
	*
	*
	*
	* @parámetro Integer $idCatastroPredioEquidosSanidad
	* @return IdCatastroPredioEquidosSanidad
	*/
	public function setIdCatastroPredioEquidosSanidad($idCatastroPredioEquidosSanidad)
	{
	  $this->idCatastroPredioEquidosSanidad = (Integer) $idCatastroPredioEquidosSanidad;
	    return $this;
	}

	/**
	* Get idCatastroPredioEquidosSanidad
	*
	* @return null|Integer
	*/
	public function getIdCatastroPredioEquidosSanidad()
	{
		return $this->idCatastroPredioEquidosSanidad;
	}

	/**
	* Set idCatastroPredioEquidos
	*
	*
	*
	* @parámetro Integer $idCatastroPredioEquidos
	* @return IdCatastroPredioEquidos
	*/
	public function setIdCatastroPredioEquidos($idCatastroPredioEquidos)
	{
	  $this->idCatastroPredioEquidos = (Integer) $idCatastroPredioEquidos;
	    return $this;
	}

	/**
	* Get idCatastroPredioEquidos
	*
	* @return null|Integer
	*/
	public function getIdCatastroPredioEquidos()
	{
		return $this->idCatastroPredioEquidos;
	}

	/**
	* Set identificador
	*
	*
	*
	* @parámetro String $identificador
	* @return Identificador
	*/
	public function setIdentificador($identificador)
	{
	  $this->identificador = (String) $identificador;
	    return $this;
	}

	/**
	* Get identificador
	*
	* @return null|String
	*/
	public function getIdentificador()
	{
		return $this->identificador;
	}

	/**
	* Set fechaCreacion
	*
	*
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
	* Set profesionalTecnico
	*
	*
	*
	* @parámetro String $profesionalTecnico
	* @return ProfesionalTecnico
	*/
	public function setProfesionalTecnico($profesionalTecnico)
	{
	  $this->profesionalTecnico = (String) $profesionalTecnico;
	    return $this;
	}

	/**
	* Get profesionalTecnico
	*
	* @return null|String
	*/
	public function getProfesionalTecnico()
	{
		return $this->profesionalTecnico;
	}

	/**
	* Set pesebreras
	*
	*
	*
	* @parámetro String $pesebreras
	* @return Pesebreras
	*/
	public function setPesebreras($pesebreras)
	{
	  $this->pesebreras = (String) $pesebreras;
	    return $this;
	}

	/**
	* Get pesebreras
	*
	* @return null|String
	*/
	public function getPesebreras()
	{
		return $this->pesebreras;
	}

	/**
	* Set areaCuarentena
	*
	*
	*
	* @parámetro String $areaCuarentena
	* @return AreaCuarentena
	*/
	public function setAreaCuarentena($areaCuarentena)
	{
	  $this->areaCuarentena = (String) $areaCuarentena;
	    return $this;
	}

	/**
	* Get areaCuarentena
	*
	* @return null|String
	*/
	public function getAreaCuarentena()
	{
		return $this->areaCuarentena;
	}

	/**
	* Set eliminacionDesechos
	*
	*
	*
	* @parámetro String $eliminacionDesechos
	* @return EliminacionDesechos
	*/
	public function setEliminacionDesechos($eliminacionDesechos)
	{
	  $this->eliminacionDesechos = (String) $eliminacionDesechos;
	    return $this;
	}

	/**
	* Get eliminacionDesechos
	*
	* @return null|String
	*/
	public function getEliminacionDesechos()
	{
		return $this->eliminacionDesechos;
	}

	/**
	* Set controlVectores
	*
	*
	*
	* @parámetro String $controlVectores
	* @return ControlVectores
	*/
	public function setControlVectores($controlVectores)
	{
	  $this->controlVectores = (String) $controlVectores;
	    return $this;
	}

	/**
	* Get controlVectores
	*
	* @return null|String
	*/
	public function getControlVectores()
	{
		return $this->controlVectores;
	}

	/**
	* Set usoAperosIndividuales
	*
	*
	*
	* @parámetro String $usoAperosIndividuales
	* @return UsoAperosIndividuales
	*/
	public function setUsoAperosIndividuales($usoAperosIndividuales)
	{
	  $this->usoAperosIndividuales = (String) $usoAperosIndividuales;
	    return $this;
	}

	/**
	* Get usoAperosIndividuales
	*
	* @return null|String
	*/
	public function getUsoAperosIndividuales()
	{
		return $this->usoAperosIndividuales;
	}

	/**
	* Set reportePositivoAie
	*
	*
	*
	* @parámetro String $reportePositivoAie
	* @return ReportePositivoAie
	*/
	public function setReportePositivoAie($reportePositivoAie)
	{
	  $this->reportePositivoAie = (String) $reportePositivoAie;
	    return $this;
	}

	/**
	* Get reportePositivoAie
	*
	* @return null|String
	*/
	public function getReportePositivoAie()
	{
		return $this->reportePositivoAie;
	}

	/**
	* Set idMedidaSanitaria
	*
	*
	*
	* @parámetro Integer $idMedidaSanitaria
	* @return IdMedidaSanitaria
	*/
	public function setIdMedidaSanitaria($idMedidaSanitaria)
	{
	  $this->idMedidaSanitaria = (Integer) $idMedidaSanitaria;
	    return $this;
	}

	/**
	* Get idMedidaSanitaria
	*
	* @return null|Integer
	*/
	public function getIdMedidaSanitaria()
	{
		return $this->idMedidaSanitaria;
	}

	/**
	* Set medidaSanitaria
	*
	*
	*
	* @parámetro String $medidaSanitaria
	* @return MedidaSanitaria
	*/
	public function setMedidaSanitaria($medidaSanitaria)
	{
	  $this->medidaSanitaria = (String) $medidaSanitaria;
	    return $this;
	}

	/**
	* Get medidaSanitaria
	*
	* @return null|String
	*/
	public function getMedidaSanitaria()
	{
		return $this->medidaSanitaria;
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
	* @return CatastroPredioEquidosSanidadModelo
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
