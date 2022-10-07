<?php
 /**
 * Modelo DetalleAnteAvesModelo
 *
 * Este archivo se complementa con el archivo   DetalleAnteAvesLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    DetalleAnteAvesModelo
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class DetalleAnteAvesModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
		protected $idDetalleAnteAves;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Llave foránea de la tabla formulario_ante_mortem
		*/
		protected $idFormularioAnteMortem;
		/**
		* @var Date
		* Campo opcional
		* Campo visible en el formulario
		* Fecha del ante mortem
		*/
		protected $fechaFormulario;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Tipo de ave seleccionada
		*/
		protected $tipoAve;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Lugar de procedencia
		*/
		protected $lugarProcedencia;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Número de permiso CSMI
		*/
		protected $numCsmi;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Total de aves ingresadas
		*/
		protected $totalAves;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Promedio de aves ingresadas
		*/
		protected $promedioAves;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Determina si exite hallazgos
		*/
		protected $hallazgos;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Aves recibirán fenamiento normal
		*/
		protected $faenamientoNormal;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Porcentaje aves recibirán fenamiento normal
		*/
		protected $procentFaenamientoNormal;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Aves recibirán fenamiento bajo precauciones especiales
		*/
		protected $faenamientoEspecial;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Porcentaje aves recibirán fenamiento bajo precauciones especiales
		*/
		protected $porcentFaenamientoEspecial;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Aves recibirán faenmaiento de emergencia
		*/
		protected $faenamientoEmergencia;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Porcentaje aves recibirán faenmaiento de emergencia
		*/
		protected $porcentEmergencia;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Aplazamiento del faenamiento
		*/
		protected $aplazamientoFaenamiento;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Porcentaje del aplazamiento del faenamiento
		*/
		protected $porcentAplazamientoFaenamiento;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Suma total del dictamen
		*/
		protected $totalFaenamiento;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Observación del registro
		*/
		protected $observacion;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Llave foránea de la tabla hallazgos_aves_muertas
		*/
		protected $idHallazgosAvesMuertas;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Llave foránea de la tabla hallazgos_aves_caract
		*/
		protected $idHallazgosAvesCaract;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Llave foránea de la tabla hallazgos_aves_sistematicos
		*/
		protected $idHallazgosAvesSistematicos;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Llave foránea de la tabla hallazgos_aves_externas
		*/
		protected $idHallazgosAvesExternas;

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
	* Nombre de la tabla: detalle_ante_aves
	* 
	 */
	Private $tabla="detalle_ante_aves";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_detalle_ante_aves";



	/**
	*Secuencia 
*/
		 private $secuencial = 'g_centros_faenamiento"."detalle_ante_mortem_aves_id_detalle_ante_aves_seq'; 



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
		throw new \Exception('Clase Modelo: DetalleAnteAvesModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: DetalleAnteAvesModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idDetalleAnteAves
	*
	*Llave primaria de la tabla
	*
	* @parámetro Integer $idDetalleAnteAves
	* @return IdDetalleAnteAves
	*/
	public function setIdDetalleAnteAves($idDetalleAnteAves)
	{
	  $this->idDetalleAnteAves = (Integer) $idDetalleAnteAves;
	    return $this;
	}

	/**
	* Get idDetalleAnteAves
	*
	* @return null|Integer
	*/
	public function getIdDetalleAnteAves()
	{
		return $this->idDetalleAnteAves;
	}

	/**
	* Set idFormularioAnteMortem
	*
	*Llave foránea de la tabla formulario_ante_mortem
	*
	* @parámetro Integer $idFormularioAnteMortem
	* @return IdFormularioAnteMortem
	*/
	public function setIdFormularioAnteMortem($idFormularioAnteMortem)
	{
	  $this->idFormularioAnteMortem = (Integer) $idFormularioAnteMortem;
	    return $this;
	}

	/**
	* Get idFormularioAnteMortem
	*
	* @return null|Integer
	*/
	public function getIdFormularioAnteMortem()
	{
		return $this->idFormularioAnteMortem;
	}

	/**
	* Set fechaFormulario
	*
	*Fecha del ante mortem
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
	* Set tipoAve
	*
	*Tipo de ave seleccionada
	*
	* @parámetro String $tipoAve
	* @return TipoAve
	*/
	public function setTipoAve($tipoAve)
	{
	  $this->tipoAve = (String) $tipoAve;
	    return $this;
	}

	/**
	* Get tipoAve
	*
	* @return null|String
	*/
	public function getTipoAve()
	{
		return $this->tipoAve;
	}

	/**
	* Set lugarProcedencia
	*
	*Lugar de procedencia
	*
	* @parámetro String $lugarProcedencia
	* @return LugarProcedencia
	*/
	public function setLugarProcedencia($lugarProcedencia)
	{
	  $this->lugarProcedencia = (String) $lugarProcedencia;
	    return $this;
	}

	/**
	* Get lugarProcedencia
	*
	* @return null|String
	*/
	public function getLugarProcedencia()
	{
		return $this->lugarProcedencia;
	}

	/**
	* Set numCsmi
	*
	*Número de permiso CSMI
	*
	* @parámetro Integer $numCsmi
	* @return NumCsmi
	*/
	public function setNumCsmi($numCsmi)
	{
	  $this->numCsmi = (Integer) $numCsmi;
	    return $this;
	}

	/**
	* Get numCsmi
	*
	* @return null|Integer
	*/
	public function getNumCsmi()
	{
		return $this->numCsmi;
	}

	/**
	* Set totalAves
	*
	*Total de aves ingresadas
	*
	* @parámetro Integer $totalAves
	* @return TotalAves
	*/
	public function setTotalAves($totalAves)
	{
	  $this->totalAves = (Integer) $totalAves;
	    return $this;
	}

	/**
	* Get totalAves
	*
	* @return null|Integer
	*/
	public function getTotalAves()
	{
		return $this->totalAves;
	}

	/**
	* Set promedioAves
	*
	*Promedio de aves ingresadas
	*
	* @parámetro String $promedioAves
	* @return PromedioAves
	*/
	public function setPromedioAves($promedioAves)
	{
	  $this->promedioAves = (String) $promedioAves;
	    return $this;
	}

	/**
	* Get promedioAves
	*
	* @return null|String
	*/
	public function getPromedioAves()
	{
		return $this->promedioAves;
	}

	/**
	* Set hallazgos
	*
	*Determina si exite hallazgos
	*
	* @parámetro String $hallazgos
	* @return Hallazgos
	*/
	public function setHallazgos($hallazgos)
	{
	  $this->hallazgos = (String) $hallazgos;
	    return $this;
	}

	/**
	* Get hallazgos
	*
	* @return null|String
	*/
	public function getHallazgos()
	{
		return $this->hallazgos;
	}

	/**
	* Set faenamientoNormal
	*
	*Aves recibirán fenamiento normal
	*
	* @parámetro String $faenamientoNormal
	* @return FaenamientoNormal
	*/
	public function setFaenamientoNormal($faenamientoNormal)
	{
	  $this->faenamientoNormal = (String) $faenamientoNormal;
	    return $this;
	}

	/**
	* Get faenamientoNormal
	*
	* @return null|String
	*/
	public function getFaenamientoNormal()
	{
		return $this->faenamientoNormal;
	}

	/**
	* Set procentFaenamientoNormal
	*
	*Porcentaje aves recibirán fenamiento normal
	*
	* @parámetro String $procentFaenamientoNormal
	* @return ProcentFaenamientoNormal
	*/
	public function setProcentFaenamientoNormal($procentFaenamientoNormal)
	{
	  $this->procentFaenamientoNormal = (String) $procentFaenamientoNormal;
	    return $this;
	}

	/**
	* Get procentFaenamientoNormal
	*
	* @return null|String
	*/
	public function getProcentFaenamientoNormal()
	{
		return $this->procentFaenamientoNormal;
	}

	/**
	* Set faenamientoEspecial
	*
	*Aves recibirán fenamiento bajo precauciones especiales
	*
	* @parámetro String $faenamientoEspecial
	* @return FaenamientoEspecial
	*/
	public function setFaenamientoEspecial($faenamientoEspecial)
	{
	  $this->faenamientoEspecial = (String) $faenamientoEspecial;
	    return $this;
	}

	/**
	* Get faenamientoEspecial
	*
	* @return null|String
	*/
	public function getFaenamientoEspecial()
	{
		return $this->faenamientoEspecial;
	}

	/**
	* Set porcentFaenamientoEspecial
	*
	*Porcentaje aves recibirán fenamiento bajo precauciones especiales
	*
	* @parámetro String $porcentFaenamientoEspecial
	* @return PorcentFaenamientoEspecial
	*/
	public function setPorcentFaenamientoEspecial($porcentFaenamientoEspecial)
	{
	  $this->porcentFaenamientoEspecial = (String) $porcentFaenamientoEspecial;
	    return $this;
	}

	/**
	* Get porcentFaenamientoEspecial
	*
	* @return null|String
	*/
	public function getPorcentFaenamientoEspecial()
	{
		return $this->porcentFaenamientoEspecial;
	}

	/**
	* Set faenamientoEmergencia
	*
	*Aves recibirán faenmaiento de emergencia
	*
	* @parámetro String $faenamientoEmergencia
	* @return FaenamientoEmergencia
	*/
	public function setFaenamientoEmergencia($faenamientoEmergencia)
	{
	  $this->faenamientoEmergencia = (String) $faenamientoEmergencia;
	    return $this;
	}

	/**
	* Get faenamientoEmergencia
	*
	* @return null|String
	*/
	public function getFaenamientoEmergencia()
	{
		return $this->faenamientoEmergencia;
	}

	/**
	* Set porcentEmergencia
	*
	*Porcentaje aves recibirán faenmaiento de emergencia
	*
	* @parámetro String $porcentEmergencia
	* @return PorcentEmergencia
	*/
	public function setPorcentEmergencia($porcentEmergencia)
	{
	  $this->porcentEmergencia = (String) $porcentEmergencia;
	    return $this;
	}

	/**
	* Get porcentEmergencia
	*
	* @return null|String
	*/
	public function getPorcentEmergencia()
	{
		return $this->porcentEmergencia;
	}

	/**
	* Set aplazamientoFaenamiento
	*
	*Aplazamiento del faenamiento
	*
	* @parámetro String $aplazamientoFaenamiento
	* @return AplazamientoFaenamiento
	*/
	public function setAplazamientoFaenamiento($aplazamientoFaenamiento)
	{
	  $this->aplazamientoFaenamiento = (String) $aplazamientoFaenamiento;
	    return $this;
	}

	/**
	* Get aplazamientoFaenamiento
	*
	* @return null|String
	*/
	public function getAplazamientoFaenamiento()
	{
		return $this->aplazamientoFaenamiento;
	}

	/**
	* Set porcentAplazamientoFaenamiento
	*
	*Porcentaje del aplazamiento del faenamiento
	*
	* @parámetro String $porcentAplazamientoFaenamiento
	* @return PorcentAplazamientoFaenamiento
	*/
	public function setPorcentAplazamientoFaenamiento($porcentAplazamientoFaenamiento)
	{
	  $this->porcentAplazamientoFaenamiento = (String) $porcentAplazamientoFaenamiento;
	    return $this;
	}

	/**
	* Get porcentAplazamientoFaenamiento
	*
	* @return null|String
	*/
	public function getPorcentAplazamientoFaenamiento()
	{
		return $this->porcentAplazamientoFaenamiento;
	}

	/**
	* Set totalFaenamiento
	*
	*Suma total del dictamen
	*
	* @parámetro String $totalFaenamiento
	* @return TotalFaenamiento
	*/
	public function setTotalFaenamiento($totalFaenamiento)
	{
	  $this->totalFaenamiento = (String) $totalFaenamiento;
	    return $this;
	}

	/**
	* Get totalFaenamiento
	*
	* @return null|String
	*/
	public function getTotalFaenamiento()
	{
		return $this->totalFaenamiento;
	}

	/**
	* Set observacion
	*
	*Observación del registro
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
	* Set idHallazgosAvesMuertas
	*
	*Llave foránea de la tabla hallazgos_aves_muertas
	*
	* @parámetro Integer $idHallazgosAvesMuertas
	* @return IdHallazgosAvesMuertas
	*/
	public function setIdHallazgosAvesMuertas($idHallazgosAvesMuertas)
	{
	  $this->idHallazgosAvesMuertas = (Integer) $idHallazgosAvesMuertas;
	    return $this;
	}

	/**
	* Get idHallazgosAvesMuertas
	*
	* @return null|Integer
	*/
	public function getIdHallazgosAvesMuertas()
	{
		return $this->idHallazgosAvesMuertas;
	}

	/**
	* Set idHallazgosAvesCaract
	*
	*Llave foránea de la tabla hallazgos_aves_caract
	*
	* @parámetro Integer $idHallazgosAvesCaract
	* @return IdHallazgosAvesCaract
	*/
	public function setIdHallazgosAvesCaract($idHallazgosAvesCaract)
	{
	  $this->idHallazgosAvesCaract = (Integer) $idHallazgosAvesCaract;
	    return $this;
	}

	/**
	* Get idHallazgosAvesCaract
	*
	* @return null|Integer
	*/
	public function getIdHallazgosAvesCaract()
	{
		return $this->idHallazgosAvesCaract;
	}

	/**
	* Set idHallazgosAvesSistematicos
	*
	*Llave foránea de la tabla hallazgos_aves_sistematicos
	*
	* @parámetro Integer $idHallazgosAvesSistematicos
	* @return IdHallazgosAvesSistematicos
	*/
	public function setIdHallazgosAvesSistematicos($idHallazgosAvesSistematicos)
	{
	  $this->idHallazgosAvesSistematicos = (Integer) $idHallazgosAvesSistematicos;
	    return $this;
	}

	/**
	* Get idHallazgosAvesSistematicos
	*
	* @return null|Integer
	*/
	public function getIdHallazgosAvesSistematicos()
	{
		return $this->idHallazgosAvesSistematicos;
	}

	/**
	* Set idHallazgosAvesExternas
	*
	*Llave foránea de la tabla hallazgos_aves_externas
	*
	* @parámetro Integer $idHallazgosAvesExternas
	* @return IdHallazgosAvesExternas
	*/
	public function setIdHallazgosAvesExternas($idHallazgosAvesExternas)
	{
	  $this->idHallazgosAvesExternas = (Integer) $idHallazgosAvesExternas;
	    return $this;
	}

	/**
	* Get idHallazgosAvesExternas
	*
	* @return null|Integer
	*/
	public function getIdHallazgosAvesExternas()
	{
		return $this->idHallazgosAvesExternas;
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
	* @return DetalleAnteAvesModelo
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
