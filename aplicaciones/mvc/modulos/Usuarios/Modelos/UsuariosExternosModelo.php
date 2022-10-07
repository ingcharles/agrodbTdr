<?php
 /**
 * Modelo UsuariosExternosModelo
 *
 * Este archivo se complementa con el archivo   UsuariosExternosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-01-03
 * @uses    UsuariosExternosModelo
 * @package Usuarios
 * @subpackage Modelos
 */
  namespace Agrodb\Usuarios\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class UsuariosExternosModelo extends ModeloBase 
{

		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del usuario externo, Cédula/RUC
		*/
		protected $identificador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre del usuario externo
		*/
		protected $nombre;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Apellido del usuario externo
		*/
		protected $apellido;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Código de la institución/empresa a la que pertenece el usuario externo
		*/
		protected $entidad;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * Nombre de la institución/empresa a la que pertenece el usuario externo
		 */
		protected $institucion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la provincia a la que pertenece el usuario externo
		*/
		protected $idProvincia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre de la provincia a la que pertenece el usuario externo
		*/
		protected $provincia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Estado del registro:
- activo
- inactivo
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
	Private $esquema ="g_usuario";

	/**
	* Nombre de la tabla: usuarios_externos
	* 
	 */
	Private $tabla="usuarios_externos";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "identificador";



	/**
	*Secuencia
*/
		 private $secuencial = '"UsuariosExternos_"identificador_seq'; 



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
		throw new \Exception('Clase Modelo: UsuariosExternosModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: UsuariosExternosModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_usuario
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set identificador
	*
	*Identificador del usuario externo, Cédula/RUC
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
	* Set nombre
	*
	*Nombre del usuario externo
	*
	* @parámetro String $nombre
	* @return Nombre
	*/
	public function setNombre($nombre)
	{
	  $this->nombre = (String) $nombre;
	    return $this;
	}

	/**
	* Get nombre
	*
	* @return null|String
	*/
	public function getNombre()
	{
		return $this->nombre;
	}

	/**
	* Set apellido
	*
	*Apellido del usuario externo
	*
	* @parámetro String $apellido
	* @return Apellido
	*/
	public function setApellido($apellido)
	{
	  $this->apellido = (String) $apellido;
	    return $this;
	}

	/**
	* Get apellido
	*
	* @return null|String
	*/
	public function getApellido()
	{
		return $this->apellido;
	}
	
	/**
	 * Set entidad
	 *
	 *Código de la institución/empresa a la que pertenece el usuario externo
	 *
	 * @parámetro String $entidad
	 * @return Entidad
	 */
	public function setEntidad($entidad)
	{
	    $this->entidad = (String) $entidadn;
	    return $this;
	}
	
	/**
	 * Get entidad
	 *
	 * @return null|String
	 */
	public function getEntidadn()
	{
	    return $this->entidad;
	}

	/**
	* Set institucion
	*
	*Nombre de la institución/empresa a la que pertenece el usuario externo
	*
	* @parámetro String $institucion
	* @return Institucion
	*/
	public function setInstitucion($institucion)
	{
	  $this->institucion = (String) $institucion;
	    return $this;
	}

	/**
	* Get institucion
	*
	* @return null|String
	*/
	public function getInstitucion()
	{
		return $this->institucion;
	}

	/**
	* Set idProvincia
	*
	*Identificador de la provincia a la que pertenece el usuario externo
	*
	* @parámetro Integer $idProvincia
	* @return IdProvincia
	*/
	public function setIdProvincia($idProvincia)
	{
	  $this->idProvincia = (Integer) $idProvincia;
	    return $this;
	}

	/**
	* Get idProvincia
	*
	* @return null|Integer
	*/
	public function getIdProvincia()
	{
		return $this->idProvincia;
	}

	/**
	* Set provincia
	*
	*Nombre de la provincia a la que pertenece el usuario externo
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
	* Set estado
	*
	*Estado del registro:
- activo
- inactivo
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
	* @return UsuariosExternosModelo
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
