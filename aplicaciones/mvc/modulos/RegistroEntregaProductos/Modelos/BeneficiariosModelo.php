<?php
 /**
 * Modelo BeneficiariosModelo
 *
 * Este archivo se complementa con el archivo   BeneficiariosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-01-03
 * @uses    BeneficiariosModelo
 * @package RegistroEntregaProductos
 * @subpackage Modelos
 */
  namespace Agrodb\RegistroEntregaProductos\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class BeneficiariosModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador único del registro
		*/
		protected $idBeneficiario;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del beneficiario
		*/
		protected $identificador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombres del beneficiario
		*/
		protected $nombre;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Apellidos del beneficiario
		*/
		protected $apellido;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Dirección del beneficiario
		*/
		protected $direccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Teléfono de contacto del beneficiario
		*/
		protected $telefono;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Correo electrónico del beneficiario
		*/
		protected $correoElectronico;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Estado del registro:
- activo
- inativo
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
	Private $esquema ="g_registro_entrega_producto";

	/**
	* Nombre de la tabla: beneficiarios
	* 
	 */
	Private $tabla="beneficiarios";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_beneficiario";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_registro_entrega_producto"."beneficiarios_id_beneficiario_seq';



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
		throw new \Exception('Clase Modelo: BeneficiariosModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: BeneficiariosModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_registro_entrega_producto
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idBeneficiario
	*
	*Identificador único del registro
	*
	* @parámetro Integer $idBeneficiario
	* @return IdBeneficiario
	*/
	public function setIdBeneficiario($idBeneficiario)
	{
	  $this->idBeneficiario = (Integer) $idBeneficiario;
	    return $this;
	}

	/**
	* Get idBeneficiario
	*
	* @return null|Integer
	*/
	public function getIdBeneficiario()
	{
		return $this->idBeneficiario;
	}

	/**
	* Set identificador
	*
	*Identificador del beneficiario
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
	*Nombres del beneficiario
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
	*Apellidos del beneficiario
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
	* Set direccion
	*
	*Dirección del beneficiario
	*
	* @parámetro String $direccion
	* @return Direccion
	*/
	public function setDireccion($direccion)
	{
	  $this->direccion = (String) $direccion;
	    return $this;
	}

	/**
	* Get direccion
	*
	* @return null|String
	*/
	public function getDireccion()
	{
		return $this->direccion;
	}

	/**
	* Set telefono
	*
	*Teléfono de contacto del beneficiario
	*
	* @parámetro String $telefono
	* @return Telefono
	*/
	public function setTelefono($telefono)
	{
	  $this->telefono = (String) $telefono;
	    return $this;
	}

	/**
	* Get telefono
	*
	* @return null|String
	*/
	public function getTelefono()
	{
		return $this->telefono;
	}

	/**
	* Set correoElectronico
	*
	*Correo electrónico del beneficiario
	*
	* @parámetro String $correoElectronico
	* @return CorreoElectronico
	*/
	public function setCorreoElectronico($correoElectronico)
	{
	  $this->correoElectronico = (String) $correoElectronico;
	    return $this;
	}

	/**
	* Get correoElectronico
	*
	* @return null|String
	*/
	public function getCorreoElectronico()
	{
		return $this->correoElectronico;
	}

	/**
	* Set estado
	*
	*Estado del registro:
- activo
- inativo
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
	* @return BeneficiariosModelo
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
