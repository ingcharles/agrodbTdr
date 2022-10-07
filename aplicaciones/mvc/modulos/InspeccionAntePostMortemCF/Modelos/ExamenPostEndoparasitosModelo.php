<?php
 /**
 * Modelo ExamenPostEndoparasitosModelo
 *
 * Este archivo se complementa con el archivo   ExamenPostEndoparasitosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    ExamenPostEndoparasitosModelo
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class ExamenPostEndoparasitosModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
		protected $idExamenPostEndoparasitos;
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
		* Descripción de endoparasitos
		*/
		protected $endoparasitosPresencia;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Endoparasitos localización
		*/
		protected $endoparasitosLocalizacion;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Endoparasitos números de afectados
		*/
		protected $endoparasitosNumAfectados;
		/**
		* @var Date
		* Campo opcional
		* Campo visible en el formulario
		* Fecha de creación del regisro
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
	* Nombre de la tabla: examen_post_endoparasitos
	* 
	 */
	Private $tabla="examen_post_endoparasitos";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_examen_post_endoparasitos";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_centros_faenamiento"."examen_post_endoparasitos_id_examen_post_endoparasitos_seq'; 



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
		throw new \Exception('Clase Modelo: ExamenPostEndoparasitosModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: ExamenPostEndoparasitosModelo. Propiedad especificada invalida: get'.$name);
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
	* Set idExamenPostEndoparasitos
	*
	*Llave primaria de la tabla
	*
	* @parámetro Integer $idExamenPostEndoparasitos
	* @return IdExamenPostEndoparasitos
	*/
	public function setIdExamenPostEndoparasitos($idExamenPostEndoparasitos)
	{
	  $this->idExamenPostEndoparasitos = (Integer) $idExamenPostEndoparasitos;
	    return $this;
	}

	/**
	* Get idExamenPostEndoparasitos
	*
	* @return null|Integer
	*/
	public function getIdExamenPostEndoparasitos()
	{
		return $this->idExamenPostEndoparasitos;
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
	* Set endoparasitosPresencia
	*
	*Descripción de endoparasitos
	*
	* @parámetro String $endoparasitosPresencia
	* @return EndoparasitosPresencia
	*/
	public function setEndoparasitosPresencia($endoparasitosPresencia)
	{
	  $this->endoparasitosPresencia = (String) $endoparasitosPresencia;
	    return $this;
	}

	/**
	* Get endoparasitosPresencia
	*
	* @return null|String
	*/
	public function getEndoparasitosPresencia()
	{
		return $this->endoparasitosPresencia;
	}

	/**
	* Set endoparasitosLocalizacion
	*
	*Endoparasitos localización
	*
	* @parámetro String $endoparasitosLocalizacion
	* @return EndoparasitosLocalizacion
	*/
	public function setEndoparasitosLocalizacion($endoparasitosLocalizacion)
	{
	  $this->endoparasitosLocalizacion = (String) $endoparasitosLocalizacion;
	    return $this;
	}

	/**
	* Get endoparasitosLocalizacion
	*
	* @return null|String
	*/
	public function getEndoparasitosLocalizacion()
	{
		return $this->endoparasitosLocalizacion;
	}

	/**
	* Set endoparasitosNumAfectados
	*
	*Endoparasitos números de afectados
	*
	* @parámetro Integer $endoparasitosNumAfectados
	* @return EndoparasitosNumAfectados
	*/
	public function setEndoparasitosNumAfectados($endoparasitosNumAfectados)
	{
	  $this->endoparasitosNumAfectados = (Integer) $endoparasitosNumAfectados;
	    return $this;
	}

	/**
	* Get endoparasitosNumAfectados
	*
	* @return null|Integer
	*/
	public function getEndoparasitosNumAfectados()
	{
		return $this->endoparasitosNumAfectados;
	}

	/**
	* Set fechaCreacion
	*
	*Fecha de creación del regisro
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
	* @return ExamenPostEndoparasitosModelo
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
