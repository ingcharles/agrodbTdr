<?php
 /**
 * Modelo TemporalProductoresModelo
 *
 * Este archivo se complementa con el archivo   TemporalProductoresLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    TemporalProductoresModelo
 * @package InspeccionMusaceas
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionMusaceas\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class TemporalProductoresModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
		protected $idTemporalProductores;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave foránea de la tabla área
		*/
		protected $idArea;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Razon social
		*/
		protected $razonSocial;
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
		* Código del área
		*/
		protected $codigoArea;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del exportador
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
		* Nombre del área
		*/
		protected $nombreArea;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Código de la provincia
		*/
		protected $codProvincia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Código MAG
		*/
		protected $codMag;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $identificadorOperador;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_inspeccion_musaceas";

	/**
	* Nombre de la tabla: temporal_productores
	* 
	 */
	Private $tabla="temporal_productores";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_temporal_productores";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_inspeccion_musaceas"."temporal_productores_id_temporal_productores_seq'; 



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
		throw new \Exception('Clase Modelo: TemporalProductoresModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: TemporalProductoresModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_inspeccion_musaceas
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idTemporalProductores
	*
	*Llave primaria de la tabla
	*
	* @parámetro Integer $idTemporalProductores
	* @return IdTemporalProductores
	*/
	public function setIdTemporalProductores($idTemporalProductores)
	{
	  $this->idTemporalProductores = (Integer) $idTemporalProductores;
	    return $this;
	}

	/**
	* Get idTemporalProductores
	*
	* @return null|Integer
	*/
	public function getIdTemporalProductores()
	{
		return $this->idTemporalProductores;
	}

	/**
	* Set idArea
	*
	*Llave foránea de la tabla área
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
	* Set razonSocial
	*
	*Razon social
	*
	* @parámetro String $razonSocial
	* @return RazonSocial
	*/
	public function setRazonSocial($razonSocial)
	{
	  $this->razonSocial = (String) $razonSocial;
	    return $this;
	}

	/**
	* Get razonSocial
	*
	* @return null|String
	*/
	public function getRazonSocial()
	{
		return $this->razonSocial;
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
	* Set codigoArea
	*
	*Código del área
	*
	* @parámetro String $codigoArea
	* @return CodigoArea
	*/
	public function setCodigoArea($codigoArea)
	{
	  $this->codigoArea = (String) $codigoArea;
	    return $this;
	}

	/**
	* Get codigoArea
	*
	* @return null|String
	*/
	public function getCodigoArea()
	{
		return $this->codigoArea;
	}

	/**
	* Set identificador
	*
	*Identificador del exportador
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
	* Set nombreArea
	*
	*Nombre del área
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
	* Set codProvincia
	*
	*Código de la provincia
	*
	* @parámetro String $codProvincia
	* @return CodProvincia
	*/
	public function setCodProvincia($codProvincia)
	{
	  $this->codProvincia = (String) $codProvincia;
	    return $this;
	}

	/**
	* Get codProvincia
	*
	* @return null|String
	*/
	public function getCodProvincia()
	{
		return $this->codProvincia;
	}

	/**
	* Set codMag
	*
	*Código MAG
	*
	* @parámetro String $codMag
	* @return CodMag
	*/
	public function setCodMag($codMag)
	{
	  $this->codMag = (String) $codMag;
	    return $this;
	}

	/**
	* Get codMag
	*
	* @return null|String
	*/
	public function getCodMag()
	{
		return $this->codMag;
	}

	/**
	* Set identificadorOperador
	*
	*
	*
	* @parámetro String $identificadorOperador
	* @return IdentificadorOperador
	*/
	public function setIdentificadorOperador($identificadorOperador)
	{
	  $this->identificadorOperador = (String) $identificadorOperador;
	    return $this;
	}

	/**
	* Get identificadorOperador
	*
	* @return null|String
	*/
	public function getIdentificadorOperador()
	{
		return $this->identificadorOperador;
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
	* @return TemporalProductoresModelo
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
