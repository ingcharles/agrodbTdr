<?php
 /**
 * Modelo DetalleAnteAnimalesModelo
 *
 * Este archivo se complementa con el archivo   DetalleAnteAnimalesLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    DetalleAnteAnimalesModelo
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
  use Agrodb\Core\ModeloBase;
 
class DetalleAnteAnimalesModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
		protected $idDetalleAnteAnimales;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Llave foránea de la tabla formulario_ante_morten
		*/
		protected $idFormularioAnteMortem;
		/**
		* @var Date
		* Campo opcional
		* Campo visible en el formulario
		* Fecha levantará el formulario
		*/
		protected $fechaFormulario;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Número de CSMI
		*/
		protected $numCsmi;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Número de lote
		*/
		protected $numLote;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Nombre de la especie
		*/
		protected $especie;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Categoría etaria de la especie
		*/
		protected $categoriaEtaria;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Peso vivo promedio
		*/
		protected $pesoVivoPromedio;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Número de machos
		*/
		protected $numMachos;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Números de hembras
		*/
		protected $numHembras;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Número total de animales
		*/
		protected $numTotalAnimales;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Determinar si hay hallazgos
		*/
		protected $hallazgos;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Número de animales requieren matanza normal
		*/
		protected $matanzaNormal;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Número de animales que requieren matanza especial
		*/
		protected $matanzaEspeciales;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Número de animales que requieren matanza de emergencia
		*/
		protected $matanzaEmergencia;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Número de animales que requieren aplazamiento de matanza
		*/
		protected $aplazamientoMatanza;
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
		* Llave foránea de la tabla hallazgos_animales_muertos
		*/
		protected $idHallazgosAnimalesMuertos;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Llave foránea de la tabla hallazgos_animales_clinicos
		*/
		protected $idHallazgosAnimalesClinicos;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Llave foránea de la tabla hallazgos_animales_locomocion
		*/
		protected $idHallazgosAnimalesLocomocion;

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
	* Nombre de la tabla: detalle_ante_animales
	* 
	 */
	Private $tabla="detalle_ante_animales";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_detalle_ante_animales";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_centros_faenamiento"."detalle_ante_animales_id_detalle_ante_animales_seq'; 



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
		throw new \Exception('Clase Modelo: DetalleAnteAnimalesModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: DetalleAnteAnimalesModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idDetalleAnteAnimales
	*
	*Llave primaria de la tabla
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
	* Set idFormularioAnteMortem
	*
	*Llave foránea de la tabla formulario_ante_morten
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
	*Fecha levantará el formulario
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
	* Set numCsmi
	*
	*Número de CSMI
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
	* Set numLote
	*
	*Número de lote
	*
	* @parámetro Integer $numLote
	* @return NumLote
	*/
	public function setNumLote($numLote)
	{
	  $this->numLote = (Integer) $numLote;
	    return $this;
	}

	/**
	* Get numLote
	*
	* @return null|Integer
	*/
	public function getNumLote()
	{
		return $this->numLote;
	}

	/**
	* Set especie
	*
	*Nombre de la especie
	*
	* @parámetro String $especie
	* @return Especie
	*/
	public function setEspecie($especie)
	{
	  $this->especie = (String) $especie;
	    return $this;
	}

	/**
	* Get especie
	*
	* @return null|String
	*/
	public function getEspecie()
	{
		return $this->especie;
	}

	/**
	* Set categoriaEtaria
	*
	*Categoría etaria de la especie
	*
	* @parámetro String $categoriaEtaria
	* @return CategoriaEtaria
	*/
	public function setCategoriaEtaria($categoriaEtaria)
	{
	  $this->categoriaEtaria = (String) $categoriaEtaria;
	    return $this;
	}

	/**
	* Get categoriaEtaria
	*
	* @return null|String
	*/
	public function getCategoriaEtaria()
	{
		return $this->categoriaEtaria;
	}

	/**
	* Set pesoVivoPromedio
	*
	*Peso vivo promedio
	*
	* @parámetro String $pesoVivoPromedio
	* @return PesoVivoPromedio
	*/
	public function setPesoVivoPromedio($pesoVivoPromedio)
	{
	  $this->pesoVivoPromedio = (String) $pesoVivoPromedio;
	    return $this;
	}

	/**
	* Get pesoVivoPromedio
	*
	* @return null|String
	*/
	public function getPesoVivoPromedio()
	{
		return $this->pesoVivoPromedio;
	}

	/**
	* Set numMachos
	*
	*Número de machos
	*
	* @parámetro Integer $numMachos
	* @return NumMachos
	*/
	public function setNumMachos($numMachos)
	{
	  $this->numMachos = (Integer) $numMachos;
	    return $this;
	}

	/**
	* Get numMachos
	*
	* @return null|Integer
	*/
	public function getNumMachos()
	{
		return $this->numMachos;
	}

	/**
	* Set numHembras
	*
	*Números de hembras
	*
	* @parámetro Integer $numHembras
	* @return NumHembras
	*/
	public function setNumHembras($numHembras)
	{
	  $this->numHembras = (Integer) $numHembras;
	    return $this;
	}

	/**
	* Get numHembras
	*
	* @return null|Integer
	*/
	public function getNumHembras()
	{
		return $this->numHembras;
	}

	/**
	* Set numTotalAnimales
	*
	*Número total de animales
	*
	* @parámetro Integer $numTotalAnimales
	* @return NumTotalAnimales
	*/
	public function setNumTotalAnimales($numTotalAnimales)
	{
	  $this->numTotalAnimales = (Integer) $numTotalAnimales;
	    return $this;
	}

	/**
	* Get numTotalAnimales
	*
	* @return null|Integer
	*/
	public function getNumTotalAnimales()
	{
		return $this->numTotalAnimales;
	}

	/**
	* Set hallazgos
	*
	*Determinar si hay hallazgos
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
	* Set matanzaNormal
	*
	*Número de animales requieren matanza normal
	*
	* @parámetro Integer $matanzaNormal
	* @return MatanzaNormal
	*/
	public function setMatanzaNormal($matanzaNormal)
	{
	  $this->matanzaNormal = (Integer) $matanzaNormal;
	    return $this;
	}

	/**
	* Get matanzaNormal
	*
	* @return null|Integer
	*/
	public function getMatanzaNormal()
	{
		return $this->matanzaNormal;
	}

	/**
	* Set matanzaEspeciales
	*
	*Número de animales que requieren matanza especial
	*
	* @parámetro Integer $matanzaEspeciales
	* @return MatanzaEspeciales
	*/
	public function setMatanzaEspeciales($matanzaEspeciales)
	{
	  $this->matanzaEspeciales = (Integer) $matanzaEspeciales;
	    return $this;
	}

	/**
	* Get matanzaEspeciales
	*
	* @return null|Integer
	*/
	public function getMatanzaEspeciales()
	{
		return $this->matanzaEspeciales;
	}

	/**
	* Set matanzaEmergencia
	*
	*Número de animales que requieren matanza de emergencia
	*
	* @parámetro Integer $matanzaEmergencia
	* @return MatanzaEmergencia
	*/
	public function setMatanzaEmergencia($matanzaEmergencia)
	{
	  $this->matanzaEmergencia = (Integer) $matanzaEmergencia;
	    return $this;
	}

	/**
	* Get matanzaEmergencia
	*
	* @return null|Integer
	*/
	public function getMatanzaEmergencia()
	{
		return $this->matanzaEmergencia;
	}

	/**
	* Set aplazamientoMatanza
	*
	*Número de animales que requieren aplazamiento de matanza
	*
	* @parámetro Integer $aplazamientoMatanza
	* @return AplazamientoMatanza
	*/
	public function setAplazamientoMatanza($aplazamientoMatanza)
	{
	  $this->aplazamientoMatanza = (Integer) $aplazamientoMatanza;
	    return $this;
	}

	/**
	* Get aplazamientoMatanza
	*
	* @return null|Integer
	*/
	public function getAplazamientoMatanza()
	{
		return $this->aplazamientoMatanza;
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
	* Set idHallazgosAnimalesMuertos
	*
	*Llave foránea de la tabla hallazgos_animales_muertos
	*
	* @parámetro Integer $idHallazgosAnimalesMuertos
	* @return IdHallazgosAnimalesMuertos
	*/
	public function setIdHallazgosAnimalesMuertos($idHallazgosAnimalesMuertos)
	{
	  $this->idHallazgosAnimalesMuertos = (Integer) $idHallazgosAnimalesMuertos;
	    return $this;
	}

	/**
	* Get idHallazgosAnimalesMuertos
	*
	* @return null|Integer
	*/
	public function getIdHallazgosAnimalesMuertos()
	{
		return $this->idHallazgosAnimalesMuertos;
	}

	/**
	* Set idHallazgosAnimalesClinicos
	*
	*Llave foránea de la tabla hallazgos_animales_clinicos
	*
	* @parámetro Integer $idHallazgosAnimalesClinicos
	* @return IdHallazgosAnimalesClinicos
	*/
	public function setIdHallazgosAnimalesClinicos($idHallazgosAnimalesClinicos)
	{
	  $this->idHallazgosAnimalesClinicos = (Integer) $idHallazgosAnimalesClinicos;
	    return $this;
	}

	/**
	* Get idHallazgosAnimalesClinicos
	*
	* @return null|Integer
	*/
	public function getIdHallazgosAnimalesClinicos()
	{
		return $this->idHallazgosAnimalesClinicos;
	}

	/**
	* Set idHallazgosAnimalesLocomocion
	*
	*Llave foránea de la tabla hallazgos_animales_locomocion
	*
	* @parámetro Integer $idHallazgosAnimalesLocomocion
	* @return IdHallazgosAnimalesLocomocion
	*/
	public function setIdHallazgosAnimalesLocomocion($idHallazgosAnimalesLocomocion)
	{
	  $this->idHallazgosAnimalesLocomocion = (Integer) $idHallazgosAnimalesLocomocion;
	    return $this;
	}

	/**
	* Get idHallazgosAnimalesLocomocion
	*
	* @return null|Integer
	*/
	public function getIdHallazgosAnimalesLocomocion()
	{
		return $this->idHallazgosAnimalesLocomocion;
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
	* @return DetalleAnteAnimalesModelo
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
