<?php
 /**
 * Modelo TipoDocumentoModelo
 *
 * Este archivo se complementa con el archivo   TipoDocumentoLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-03-17
 * @uses    TipoDocumentoModelo
 * @package ProcesosAdministrativosJuridico
 * @subpackage Modelos
 */
  namespace Agrodb\ProcesosAdministrativosJuridico\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class TipoDocumentoModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
		protected $idTipoDocumento;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave foránea de la tabla proceso_administrativo
		*/
		protected $idProcesoAdministrativo;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de creación del registro
		*/
		protected $fechaCreacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Estado del registro
		*/
		protected $estado;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave foránea de la tabla modelo_administrativo
		*/
		protected $idModeloAdministrativo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Ruta del archivo adjunto
		*/
		protected $rutaDocumento;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de quien realizo el registro
		*/
		protected $identificadorRegistro;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * Identificador de quien realizo el registro
		 */
		protected $nombreAnexo;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_procesos_administrativos_juridico";

	/**
	* Nombre de la tabla: tipo_documento
	* 
	 */
	Private $tabla="tipo_documento";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_tipo_documento";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_procesos_administrativos_juridico"."tipo_documento_id_tipo_documento_seq'; 



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
		throw new \Exception('Clase Modelo: TipoDocumentoModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: TipoDocumentoModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_procesos_administrativos_juridico
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idTipoDocumento
	*
	*Llave primaria de la tabla
	*
	* @parámetro Integer $idTipoDocumento
	* @return IdTipoDocumento
	*/
	public function setIdTipoDocumento($idTipoDocumento)
	{
	  $this->idTipoDocumento = (Integer) $idTipoDocumento;
	    return $this;
	}

	/**
	* Get idTipoDocumento
	*
	* @return null|Integer
	*/
	public function getIdTipoDocumento()
	{
		return $this->idTipoDocumento;
	}

	/**
	* Set idProcesoAdministrativo
	*
	*Llave foránea de la tabla proceso_administrativo
	*
	* @parámetro Integer $idProcesoAdministrativo
	* @return IdProcesoAdministrativo
	*/
	public function setIdProcesoAdministrativo($idProcesoAdministrativo)
	{
	  $this->idProcesoAdministrativo = (Integer) $idProcesoAdministrativo;
	    return $this;
	}

	/**
	* Get idProcesoAdministrativo
	*
	* @return null|Integer
	*/
	public function getIdProcesoAdministrativo()
	{
		return $this->idProcesoAdministrativo;
	}

	/**
	* Set fechaCreacion
	*
	*Fecha de creación del registro
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
	* Set estado
	*
	*Estado del registro
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
	* Set idModeloAdministrativo
	*
	*Llave foránea de la tabla modelo_administrativo
	*
	* @parámetro Integer $idModeloAdministrativo
	* @return IdModeloAdministrativo
	*/
	public function setIdModeloAdministrativo($idModeloAdministrativo)
	{
	  $this->idModeloAdministrativo = (Integer) $idModeloAdministrativo;
	    return $this;
	}

	/**
	* Get idModeloAdministrativo
	*
	* @return null|Integer
	*/
	public function getIdModeloAdministrativo()
	{
		return $this->idModeloAdministrativo;
	}

	/**
	* Set rutaDocumento
	*
	*Ruta del archivo adjunto
	*
	* @parámetro String $rutaDocumento
	* @return RutaDocumento
	*/
	public function setRutaDocumento($rutaDocumento)
	{
	  $this->rutaDocumento = (String) $rutaDocumento;
	    return $this;
	}

	/**
	* Get rutaDocumento
	*
	* @return null|String
	*/
	public function getRutaDocumento()
	{
		return $this->rutaDocumento;
	}

	/**
	* Set identificadorRegistro
	*
	*Identificador de quien realizo el registro
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
	 * Set nombreAnexo
	 *
	 * @parámetro String $nombreAnexo
	 * @return nombreAnexo
	 */
	public function setNombreAnexo($nombreAnexo)
	{
	    $this->nombreAnexo = (String) $nombreAnexo;
	    return $this;
	}
	
	/**
	 * Get observacion
	 *
	 * @return null|String
	 */
	public function getNombreAnexo()
	{
	    return $this->nombreAnexo;
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
	* @return TipoDocumentoModelo
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
