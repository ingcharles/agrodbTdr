<?php
 /**
 * Modelo AdicionesPresentacionesPlaguicidasModelo
 *
 * Este archivo se complementa con el archivo   AdicionesPresentacionesPlaguicidasLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-21
 * @uses    AdicionesPresentacionesPlaguicidasModelo
 * @package ModificacionProductoRia
 * @subpackage Modelos
 */
  namespace Agrodb\ModificacionProductoRia\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class AdicionesPresentacionesPlaguicidasModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla
		*/
		protected $idAdicionPresentacionPlaguicida;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla g_modificacion_productos.detalle_solicitudes_productos
		*/
		protected $idDetalleSolicitudProducto;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla g_catalogos.presentaciones_plaguicidas
		*/
		protected $idTablaOrigen;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla g_catalogos.partidas_arancelarias
		*/
		protected $idPartidaArancelaria;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla g_catalogos.codigos_comp_supl
		*/
		protected $idCodigoCompSupl;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el subcodigo generado
		*/
		protected $subcodigo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la presentacion
		*/
		protected $presentacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla g_catalogos.unidades_medida
		*/
		protected $idUnidadMedida;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el nombre de la unidad de la presentacion
		*/
		protected $unidadMedida;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el estado del registro
		*/
		protected $estado;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena la fecha de creacion del registro
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
	Private $esquema ="g_modificacion_productos";

	/**
	* Nombre de la tabla: adiciones_presentaciones_plaguicidas
	* 
	 */
	Private $tabla="adiciones_presentaciones_plaguicidas";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_adicion_presentacion_plaguicida";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_modificacion_productos"."adiciones_presentaciones_plag_id_adicion_presentacion_plagu_seq'; 



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
		throw new \Exception('Clase Modelo: AdicionesPresentacionesPlaguicidasModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: AdicionesPresentacionesPlaguicidasModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_modificacion_productos
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idAdicionPresentacionPlaguicida
	*
	*Identificador unico de la tabla
	*
	* @parámetro Integer $idAdicionPresentacionPlaguicida
	* @return IdAdicionPresentacionPlaguicida
	*/
	public function setIdAdicionPresentacionPlaguicida($idAdicionPresentacionPlaguicida)
	{
	  $this->idAdicionPresentacionPlaguicida = (Integer) $idAdicionPresentacionPlaguicida;
	    return $this;
	}

	/**
	* Get idAdicionPresentacionPlaguicida
	*
	* @return null|Integer
	*/
	public function getIdAdicionPresentacionPlaguicida()
	{
		return $this->idAdicionPresentacionPlaguicida;
	}

	/**
	* Set idDetalleSolicitudProducto
	*
	*Identificador unico de la tabla g_modificacion_productos.detalle_solicitudes_productos
	*
	* @parámetro Integer $idDetalleSolicitudProducto
	* @return IdDetalleSolicitudProducto
	*/
	public function setIdDetalleSolicitudProducto($idDetalleSolicitudProducto)
	{
	  $this->idDetalleSolicitudProducto = (Integer) $idDetalleSolicitudProducto;
	    return $this;
	}

	/**
	* Get idDetalleSolicitudProducto
	*
	* @return null|Integer
	*/
	public function getIdDetalleSolicitudProducto()
	{
		return $this->idDetalleSolicitudProducto;
	}

	/**
	* Set idTablaOrigen
	*
	*Identificador unico de la tabla g_catalogos.presentaciones_plaguicidas
	*
	* @parámetro Integer $idTablaOrigen
	* @return IdTablaOrigen
	*/
	public function setIdTablaOrigen($idTablaOrigen)
	{
	  $this->idTablaOrigen = (Integer) $idTablaOrigen;
	    return $this;
	}

	/**
	* Get idTablaOrigen
	*
	* @return null|Integer
	*/
	public function getIdTablaOrigen()
	{
		return $this->idTablaOrigen;
	}

	/**
	* Set idPartidaArancelaria
	*
	*Identificador unico de la tabla g_catalogos.partidas_arancelarias
	*
	* @parámetro Integer $idPartidaArancelaria
	* @return IdPartidaArancelaria
	*/
	public function setIdPartidaArancelaria($idPartidaArancelaria)
	{
	  $this->idPartidaArancelaria = (Integer) $idPartidaArancelaria;
	    return $this;
	}

	/**
	* Get idPartidaArancelaria
	*
	* @return null|Integer
	*/
	public function getIdPartidaArancelaria()
	{
		return $this->idPartidaArancelaria;
	}

	/**
	* Set idCodigoCompSupl
	*
	*Identificador unico de la tabla g_catalogos.codigos_comp_supl
	*
	* @parámetro Integer $idCodigoCompSupl
	* @return IdCodigoCompSupl
	*/
	public function setIdCodigoCompSupl($idCodigoCompSupl)
	{
	  $this->idCodigoCompSupl = (Integer) $idCodigoCompSupl;
	    return $this;
	}

	/**
	* Get idCodigoCompSupl
	*
	* @return null|Integer
	*/
	public function getIdCodigoCompSupl()
	{
		return $this->idCodigoCompSupl;
	}

	/**
	* Set subcodigo
	*
	*Campo que almacena el subcodigo generado
	*
	* @parámetro String $subcodigo
	* @return Subcodigo
	*/
	public function setSubcodigo($subcodigo)
	{
	  $this->subcodigo = (String) $subcodigo;
	    return $this;
	}

	/**
	* Get subcodigo
	*
	* @return null|String
	*/
	public function getSubcodigo()
	{
		return $this->subcodigo;
	}

	/**
	* Set presentacion
	*
	*Campo que almacena la presentacion
	*
	* @parámetro String $presentacion
	* @return Presentacion
	*/
	public function setPresentacion($presentacion)
	{
	  $this->presentacion = (String) $presentacion;
	    return $this;
	}

	/**
	* Get presentacion
	*
	* @return null|String
	*/
	public function getPresentacion()
	{
		return $this->presentacion;
	}

	/**
	* Set idUnidadMedida
	*
	*Identificador unico de la tabla g_catalogos.unidades_medida
	*
	* @parámetro Integer $idUnidadMedida
	* @return IdUnidadMedida
	*/
	public function setIdUnidadMedida($idUnidadMedida)
	{
	  $this->idUnidadMedida = (Integer) $idUnidadMedida;
	    return $this;
	}

	/**
	* Get idUnidadMedida
	*
	* @return null|Integer
	*/
	public function getIdUnidadMedida()
	{
		return $this->idUnidadMedida;
	}

	/**
	* Set unidadMedida
	*
	*Campo que almacena el nombre de la unidad de la presentacion
	*
	* @parámetro String $unidadMedida
	* @return UnidadMedida
	*/
	public function setUnidadMedida($unidadMedida)
	{
	  $this->unidadMedida = (String) $unidadMedida;
	    return $this;
	}

	/**
	* Get unidadMedida
	*
	* @return null|String
	*/
	public function getUnidadMedida()
	{
		return $this->unidadMedida;
	}

	/**
	* Set estado
	*
	*Campo que almacena el estado del registro
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
	*Campo que almacena la fecha de creacion del registro
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
	* @return AdicionesPresentacionesPlaguicidasModelo
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
