<?php
 /**
 * Modelo ExamenPostEctoparasitosModelo
 *
 * Este archivo se complementa con el archivo   ExamenPostEctoparasitosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    ExamenPostEctoparasitosModelo
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class ExamenPostEctoparasitosModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Llave primaria de tabla
		*/
		protected $idExamenPostEctoparasitos;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Llave foránea de la tabla detalle_post_animales
		*/
		protected $idDetallePostAnimales;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Ectoparasitos presencia
		*/
		protected $ectoparasitosPresencia;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Ectoparasitos localización
		*/
		protected $ectoparasitosLocalizacion;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Ectoparasitos números de afectados
		*/
		protected $ectoparasitosNumAfectados;
		/**
		* @var Date
		* Campo opcional
		* Campo visible en el formulario
		* Fecha de creación del registro
		*/
		protected $fechaCreacion;

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
	* Nombre de la tabla: examen_post_ectoparasitos
	* 
	 */
	Private $tabla="examen_post_ectoparasitos";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_examen_post_ectoparasitos";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_centros_faenamiento"."examen_post_ectoparasitos_id_examen_post_ectoparasitos_seq'; 



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
		throw new \Exception('Clase Modelo: ExamenPostEctoparasitosModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: ExamenPostEctoparasitosModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idExamenPostEctoparasitos
	*
	*Llave primaria de tabla
	*
	* @parámetro Integer $idExamenPostEctoparasitos
	* @return IdExamenPostEctoparasitos
	*/
	public function setIdExamenPostEctoparasitos($idExamenPostEctoparasitos)
	{
	  $this->idExamenPostEctoparasitos = (Integer) $idExamenPostEctoparasitos;
	    return $this;
	}

	/**
	* Get idExamenPostEctoparasitos
	*
	* @return null|Integer
	*/
	public function getIdExamenPostEctoparasitos()
	{
		return $this->idExamenPostEctoparasitos;
	}

	/**
	* Set idDetallePostAnimales
	*
	*Llave foránea de la tabla detalle_post_animales
	*
	* @parámetro Integer $idDetallePostAnimales
	* @return IdDetallePostAnimales
	*/
	public function setIdDetallePostAnimales($idDetallePostAnimales)
	{
	  $this->idDetallePostAnimales = (Integer) $idDetallePostAnimales;
	    return $this;
	}

	/**
	* Get idDetallePostAnimales
	*
	* @return null|Integer
	*/
	public function getIdDetallePostAnimales()
	{
		return $this->idDetallePostAnimales;
	}

	/**
	* Set ectoparasitosPresencia
	*
	*Ectoparasitos presencia
	*
	* @parámetro String $ectoparasitosPresencia
	* @return EctoparasitosPresencia
	*/
	public function setEctoparasitosPresencia($ectoparasitosPresencia)
	{
	  $this->ectoparasitosPresencia = (String) $ectoparasitosPresencia;
	    return $this;
	}

	/**
	* Get ectoparasitosPresencia
	*
	* @return null|String
	*/
	public function getEctoparasitosPresencia()
	{
		return $this->ectoparasitosPresencia;
	}

	/**
	* Set ectoparasitosLocalizacion
	*
	*Ectoparasitos localización
	*
	* @parámetro String $ectoparasitosLocalizacion
	* @return EctoparasitosLocalizacion
	*/
	public function setEctoparasitosLocalizacion($ectoparasitosLocalizacion)
	{
	  $this->ectoparasitosLocalizacion = (String) $ectoparasitosLocalizacion;
	    return $this;
	}

	/**
	* Get ectoparasitosLocalizacion
	*
	* @return null|String
	*/
	public function getEctoparasitosLocalizacion()
	{
		return $this->ectoparasitosLocalizacion;
	}

	/**
	* Set ectoparasitosNumAfectados
	*
	*Ectoparasitos números de afectados
	*
	* @parámetro Integer $ectoparasitosNumAfectados
	* @return EctoparasitosNumAfectados
	*/
	public function setEctoparasitosNumAfectados($ectoparasitosNumAfectados)
	{
	  $this->ectoparasitosNumAfectados = (Integer) $ectoparasitosNumAfectados;
	    return $this;
	}

	/**
	* Get ectoparasitosNumAfectados
	*
	* @return null|Integer
	*/
	public function getEctoparasitosNumAfectados()
	{
		return $this->ectoparasitosNumAfectados;
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
	* @return ExamenPostEctoparasitosModelo
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
