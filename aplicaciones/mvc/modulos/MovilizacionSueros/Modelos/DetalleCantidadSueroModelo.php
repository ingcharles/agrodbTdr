<?php
 /**
 * Modelo DetalleCantidadSueroModelo
 *
 * Este archivo se complementa con el archivo   DetalleCantidadSueroLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2018-11-21
 * @uses    DetalleCantidadSueroModelo
 * @package Movilizacion_suero
 * @subpackage Modelos
 */
namespace Agrodb\MovilizacionSueros\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class DetalleCantidadSueroModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* llave primaria de la tabla
		*/
		protected $idDetalleConsumoSuero;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* llave foreanea de la tabla de producción
		*/
		protected $idProduccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* cantidad de suero ingresado
		*/
		protected $cantidadSueroProducido;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* cantidad de suero utilizado
		*/
		protected $cantidadSueroUtilizado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* cantidad de suero restante
		*/
		protected $cantidadSueroRestante;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* fecha de modificacion del registro
		*/
		protected $fechaModificacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* estado del registro
		*/
		protected $estado;
		
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * identificador de la tabla movilizacion
		 */
		protected $idMovilizacion;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_movilizacion_suero";

	/**
	* Nombre de la tabla: detalle_cantidad_suero
	* 
	 */
	Private $tabla="detalle_cantidad_suero";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_detalle_consumo_suero";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_movilizacion_suero"."detalle_cantidad_suero_id_detalle_consumo_suero_seq'; 



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
		throw new \Exception('Clase Modelo: DetalleCantidadSueroModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: DetalleCantidadSueroModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_movilizacion_suero
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idDetalleConsumoSuero
	*
	*llave primaria de la tabla
	*
	* @parámetro Integer $idDetalleConsumoSuero
	* @return IdDetalleConsumoSuero
	*/
	public function setIdDetalleConsumoSuero($idDetalleConsumoSuero)
	{
	  $this->idDetalleConsumoSuero = (Integer) $idDetalleConsumoSuero;
	    return $this;
	}

	/**
	* Get idDetalleConsumoSuero
	*
	* @return null|Integer
	*/
	public function getIdDetalleConsumoSuero()
	{
		return $this->idDetalleConsumoSuero;
	}

	/**
	* Set idProduccion
	*
	*llave foreanea de la tabla de producción
	*
	* @parámetro Integer $idProduccion
	* @return IdProduccion
	*/
	public function setIdProduccion($idProduccion)
	{
	  $this->idProduccion = (Integer) $idProduccion;
	    return $this;
	}

	/**
	* Get idProduccion
	*
	* @return null|Integer
	*/
	public function getIdProduccion()
	{
		return $this->idProduccion;
	}

	/**
	* Set cantidadSueroProducido
	*
	*cantidad de suero ingresado
	*
	* @parámetro String $cantidadSueroProducido
	* @return CantidadSueroProducido
	*/
	public function setCantidadSueroProducido($cantidadSueroProducido)
	{
	  $this->cantidadSueroProducido = (String) $cantidadSueroProducido;
	    return $this;
	}

	/**
	* Get cantidadSueroProducido
	*
	* @return null|String
	*/
	public function getCantidadSueroProducido()
	{
		return $this->cantidadSueroProducido;
	}

	/**
	* Set cantidadSueroUtilizado
	*
	*cantidad de suero utilizado
	*
	* @parámetro String $cantidadSueroUtilizado
	* @return CantidadSueroUtilizado
	*/
	public function setCantidadSueroUtilizado($cantidadSueroUtilizado)
	{
	  $this->cantidadSueroUtilizado = (String) $cantidadSueroUtilizado;
	    return $this;
	}

	/**
	* Get cantidadSueroUtilizado
	*
	* @return null|String
	*/
	public function getCantidadSueroUtilizado()
	{
		return $this->cantidadSueroUtilizado;
	}

	/**
	* Set cantidadSueroRestante
	*
	*cantidad de suero restante
	*
	* @parámetro String $cantidadSueroRestante
	* @return CantidadSueroRestante
	*/
	public function setCantidadSueroRestante($cantidadSueroRestante)
	{
	  $this->cantidadSueroRestante = (String) $cantidadSueroRestante;
	    return $this;
	}

	/**
	* Get cantidadSueroRestante
	*
	* @return null|String
	*/
	public function getCantidadSueroRestante()
	{
		return $this->cantidadSueroRestante;
	}

	/**
	* Set fechaModificacion
	*
	*fecha de modificacion del registro
	*
	* @parámetro Date $fechaModificacion
	* @return FechaModificacion
	*/
	public function setFechaModificacion($fechaModificacion)
	{
	  $this->fechaModificacion = (String) $fechaModificacion;
	    return $this;
	}

	/**
	* Get fechaModificacion
	*
	* @return null|Date
	*/
	public function getFechaModificacion()
	{
		return $this->fechaModificacion;
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
	 * Set fechaModificacion
	 *
	 *fecha de modificacion del registro
	 *
	 * @parámetro Date $fechaModificacion
	 * @return string
	 */
	public function setIdMovilizacion($idMovilizacion)
	{
		$this->idMovilizacion = (Integer) $idMovilizacion;
		return $this;
	}
	
	/**
	 * Get idMovilizacion
	 *
	 * @return integer
	 */
	public function getIdMovilizacion()
	{
		return $this->idMovilizacion;
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
	* @return DetalleCantidadSueroModelo
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
