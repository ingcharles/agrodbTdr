<?php
 /**
 * Modelo CentroFaenamientoTipoInspectorModelo
 *
 * Este archivo se complementa con el archivo   CentroFaenamientoTipoInspectorLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2018-11-21
 * @uses    CentroFaenamientoTipoInspectorModelo
 * @package CentrosFaenamiento
 * @subpackage Modelos
 */
  namespace Agrodb\CentrosFaenamiento\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class CentroFaenamientoTipoInspectorModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo oculto en el formulario o manejado internamente
		* llave primaria de la tabla
		*/
		protected $idCentroFaenamientoTipoInspector;
		/**
		* @var Integer
		* Campo requerido
		* Campo oculto en el formulario o manejado internamente
		* llave foranea de la tabla centro_faenamiento
		*/
		protected $idCentroFaenamiento;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* llave foreania de la tabla tipo_inspector
		*/
		protected $idTipoInspector;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* fecha de creacion del registro
		*/
		protected $fechaCreacion;
		/**
		* @var String
		* Campo requerido
		* Campo oculto en el formulario o manejado internamente
		* identificador del que creo el registro
		*/
		protected $identificadorRegistro;
		/**
		 * @var String
		 * identificador del operador
		 */
		protected $identificadorOperador;
		/**
		 * @var String
		 * nombre
		 */
		protected $razonSocial;
		/**
		 * @var String
		 * nombre
		 */
		protected $tipoInspector;
		/**
		 * @var String
		 * nombre
		 */
		protected $resultado;
		/**
		 * @var String
		 * nombre
		 */
		protected $contador;
		/**
		 * @var String
		 * Campo requerido
		 * Campo oculto en el formulario o manejado internamente
		 * estado del registro
		 */
		protected $estado;

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
	* Nombre de la tabla: centro_faenamiento_tipo_inspector
	* 
	 */
	Private $tabla="centro_faenamiento_tipo_inspector";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_centro_faenamiento_tipo_inspector";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_centros_faenamiento"."centro_faenamiento_tipo_inspe_id_centro_faenamiento_tipo_in_seq'; 

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
		throw new \Exception('Clase Modelo: CentroFaenamientoTipoInspectorModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: CentroFaenamientoTipoInspectorModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idCentroFaenamientoTipoInspector
	*
	*llave primaria de la tabla
	*
	* @parámetro Integer $idCentroFaenamientoTipoInspector
	* @return IdCentroFaenamientoTipoInspector
	*/
	public function setIdCentroFaenamientoTipoInspector($idCentroFaenamientoTipoInspector)
	{
	  $this->idCentroFaenamientoTipoInspector = (Integer) $idCentroFaenamientoTipoInspector;
	    return $this;
	}

	/**
	* Get idCentroFaenamientoTipoInspector
	*
	* @return null|Integer
	*/
	public function getIdCentroFaenamientoTipoInspector()
	{
		return $this->idCentroFaenamientoTipoInspector;
	}

	/**
	* Set idCentroFaenamiento
	*
	*llave foranea de la tabla centro_faenamiento
	*
	* @parámetro Integer $idCentroFaenamiento
	* @return IdCentroFaenamiento
	*/
	public function setIdCentroFaenamiento($idCentroFaenamiento)
	{
	  $this->idCentroFaenamiento = (Integer) $idCentroFaenamiento;
	    return $this;
	}

//****************************************************************************************
	/**
	 * Set identificadorOperador
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
	 * Set razonSocial
	 * @parámetro Date razonSocial
	 * @return razonSocial
	 */
	public function setRazonSocial($razonSocial)
	{
	    $this->razonSocial = (String) $razonSocial;
	    return $this;
	}
	/**
	 * Get razonSocial
	 * @return null|Date
	 */
	public function getRazonSocial()
	{
	    return $this->razonSocial;
	}
	
	/**
	 * Set tipoInspector
	 * @parámetro Date tipoInspector
	 * @return tipoInspector
	 */
	public function setTipoInspector($tipoInspector)
	{
	    $this->tipoInspector = (String) $tipoInspector;
	    return $this;
	}
	/**
	 * Get tipoInspector
	 */
	public function getTipoInspector()
	{
	    return $this->tipoInspector;
	}
	/**
	 * Set resultado
	 * @parámetro Date resultado
	 * @return resultado
	 */
	public function setResultado($resultado)
	{
	    $this->resultado = (String) $resultado;
	    return $this;
	}
	/**
	 * Get $resultado
	 * @return null|Date
	 */
	public function getResultado()
	{
	    return $this->resultado;
	}
	/**
	 * Set resultado
	 * @parámetro Date resultado
	 * @return resultado
	 */
	public function setContador($contador)
	{
	    $this->contador = (String) $contador;
	    return $this;
	}
	/**
	 * Get $resultado
	 * @return null|Date
	 */
	public function getContador()
	{
	    return $this->contador;
	}
//****************************************************************************************
	
	/**
	* Get idCentroFaenamiento
	*
	* @return null|Integer
	*/
	public function getIdCentroFaenamiento()
	{
		return $this->idCentroFaenamiento;
	}

	/**
	* Set idTipoInspector
	*
	*llave foreania de la tabla tipo_inspector
	*
	* @parámetro Integer $idTipoInspector
	* @return IdTipoInspector
	*/
	public function setIdTipoInspector($idTipoInspector)
	{
	  $this->idTipoInspector = (Integer) $idTipoInspector;
	    return $this;
	}

	/**
	* Get idTipoInspector
	*
	* @return null|Integer
	*/
	public function getIdTipoInspector()
	{
		return $this->idTipoInspector;
	}

	/**
	* Set fechaCreacion
	*
	*fecha de creacion del registro
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
	* Set identificadorRegistro
	*
	*identificador del que creo el registro
	*
	* @parámetro String $identificadorRegistro
	* @return IdentificadorRegistro
	*/
	public function setIdentificadorRegistro($identificadorRegistro)
	{
	  $this->identificadorRegistro = (String) $identificadorRegistro;
	    return $this;
	}

	/**
	* Get identificadorRegistro
	*
	* @return null|String
	*/
	public function getIdentificadorRegistro()
	{
		return $this->identificadorRegistro;
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	
	/**
	 * Set estado
	 *
	 *estado del registro
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
	* @return CentroFaenamientoTipoInspectorModelo
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
