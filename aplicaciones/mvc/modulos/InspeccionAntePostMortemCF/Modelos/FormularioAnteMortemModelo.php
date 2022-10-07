<?php
 /**
 * Modelo FormularioAnteMortemModelo
 *
 * Este archivo se complementa con el archivo   FormularioAnteMortemLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    FormularioAnteMortemModelo
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
use Agrodb\Reactivos\Controladores\RegistroManualControlador;
 
class FormularioAnteMortemModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* llave primaria de la tabla
		*/
		protected $idFormularioAnteMortem;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* llave foranea de la tabla centro_faenamiento
		*/
		protected $idCentroFaenamiento;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* identificador del operador
		*/
		protected $identificador;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* estado del registro
		*/
		protected $estado;
		/**
		* @var Date
		* Campo opcional
		* Campo visible en el formulario
		* fecha de creación del registro
		*/
		protected $fechaCreacion;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* código del formulario
		*/
		protected $codigoFormulario;
		/**
		 * @var String
		 * Campo opcional
		 * Campo visible en el formulario
		 * especie de Registrada
		 */
		protected $especie;
		/**
		 * @var String
		 * Campo opcional
		 * Campo visible en el formulario
		 * ruta de archivo
		 */
		protected $rutaArchivo;

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
	* Nombre de la tabla: formulario_ante_mortem
	* 
	 */
	Private $tabla="formulario_ante_mortem";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_formulario_ante_mortem";



	/**
	*Secuencia 

*/
		 private $secuencial = 'g_centros_faenamiento"."formulario_ante_mortem_id_formulario_ante_mortem_seq'; 



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
		throw new \Exception('Clase Modelo: FormularioAnteMortemModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: FormularioAnteMortemModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idFormularioAnteMortem
	*
	*llave primaria de la tabla
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
	* Set identificador
	*
	*identificador del operador
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

	/**
	* Set fechaCreacion
	*
	*fecha de creación del registro
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
	* Set codigoFormulario
	*
	*código del formulario
	*
	* @parámetro String $codigoFormulario
	* @return CodigoFormulario
	*/
	public function setCodigoFormulario($codigoFormulario)
	{
	  $this->codigoFormulario = (String) $codigoFormulario;
	    return $this;
	}

	/**
	* Get codigoFormulario
	*
	* @return null|String
	*/
	public function getCodigoFormulario()
	{
		return $this->codigoFormulario;
	}

	/**
	* Set especie
	*
	*especie seleccionada
	*
	* @parámetro String $especie
	* @return Especie
	*/
	public function setEspecie($especie)
	{
		$this->especie = $especie;
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
	 * Set rutaArchivo
	 *
	 *especie seleccionada
	 *
	 * @parámetro String $rutaArchivo
	 * @return RutaArchivo
	 */
	public function setRutaArchivo($rutaArchivo)
	{
		$this->rutaArchivo = $rutaArchivo;
		return $this;
	}
	
	/**
	 * Get rutaArchivo
	 *
	 * @return null|String
	 */
	public function getRutaArchivo()
	{
		return $this->rutaArchivo;
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
	* @return FormularioAnteMortemModelo
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
