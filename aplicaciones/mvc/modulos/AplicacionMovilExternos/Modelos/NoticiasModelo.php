<?php
 /**
 * Modelo NoticiasModelo
 *
 * Este archivo se complementa con el archivo   NoticiasLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-06-06
 * @uses    NoticiasModelo
 * @package AplicacionMovilExternos
 * @subpackage Modelos
 */
  namespace Agrodb\AplicacionMovilExternos\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class NoticiasModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la tabla
		*/
		protected $idNoticia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Título de la noticia
		*/
		protected $titulo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Contenido de la alerta
		*/
		protected $noticia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Ruta de la imagen relacionada con la noticia
		*/
		protected $ruta;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha en la que se crea el registro de la alerta
		*/
		protected $fechaNoticia;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Número de veces que se ha leído la noticia
		*/
		protected $visitas;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Estado que indica si una noticia está activa o inactiva para ser visualizada en la aplicación.
		*/
		protected $estado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que identifica la fuente de la noticia.
		*/
		protected $fuente;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo donde se registra la url de la noticia en caso de no ser generada originalmente por Agrocalidad..
		*/
		protected $urlFuente;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="a_movil_externos";

	/**
	* Nombre de la tabla: noticias
	* 
	 */
	Private $tabla="noticias";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_noticia";



	/**
	*Secuencia
*/
		 private $secuencial = 'a_movil_externos"."noticias_id_noticia_seq'; 



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
		throw new \Exception('Clase Modelo: NoticiasModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: NoticiasModelo. Propiedad especificada invalida: get'.$name);
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
	* Get a_movil_externos
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idNoticia
	*
	*Identificador de la tabla
	*
	* @parámetro Integer $idNoticia
	* @return IdNoticia
	*/
	public function setIdNoticia($idNoticia)
	{
	  $this->idNoticia = (Integer) $idNoticia;
	    return $this;
	}

	/**
	* Get idNoticia
	*
	* @return null|Integer
	*/
	public function getIdNoticia()
	{
		return $this->idNoticia;
	}

	/**
	* Set titulo
	*
	*Título de la noticia
	*
	* @parámetro String $titulo
	* @return Titulo
	*/
	public function setTitulo($titulo)
	{
	  $this->titulo = (String) $titulo;
	    return $this;
	}

	/**
	* Get titulo
	*
	* @return null|String
	*/
	public function getTitulo()
	{
		return $this->titulo;
	}

	/**
	* Set noticia
	*
	*Contenido de la alerta
	*
	* @parámetro String $noticia
	* @return Noticia
	*/
	public function setNoticia($noticia)
	{
	  $this->noticia = (String) $noticia;
	    return $this;
	}

	/**
	* Get noticia
	*
	* @return null|String
	*/
	public function getNoticia()
	{
		return $this->noticia;
	}

	/**
	* Set ruta
	*
	*Ruta de la imagen relacionada con la noticia
	*
	* @parámetro String $ruta
	* @return Ruta
	*/
	public function setRuta($ruta)
	{
	  $this->ruta = (String) $ruta;
	    return $this;
	}

	/**
	* Get ruta
	*
	* @return null|String
	*/
	public function getRuta()
	{
		return $this->ruta;
	}

	/**
	* Set fechaNoticia
	*
	*Fecha en la que se crea el registro de la alerta
	*
	* @parámetro Date $fechaNoticia
	* @return FechaNoticia
	*/
	public function setFechaNoticia($fechaNoticia)
	{
	  $this->fechaNoticia = (String) $fechaNoticia;
	    return $this;
	}

	/**
	* Get fechaNoticia
	*
	* @return null|Date
	*/
	public function getFechaNoticia()
	{
		return $this->fechaNoticia;
	}

	/**
	* Set visitas
	*
	*Número de veces que se ha leído la noticia
	*
	* @parámetro Integer $visitas
	* @return Visitas
	*/
	public function setVisitas($visitas)
	{
	  $this->visitas = (Integer) $visitas;
	    return $this;
	}

	/**
	* Get visitas
	*
	* @return null|Integer
	*/
	public function getVisitas()
	{
		return $this->visitas;
	}

	/**
	* Set estado
	*
	*Estado que indica si una noticia está activa o inactiva para ser visualizada en la aplicación.
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
	* Set fuente
	*
	*Campo que identifica la fuente de la noticia.
	*
	* @parámetro String $fuente
	* @return Fuente
	*/
	public function setFuente($fuente)
	{
	  $this->fuente = (String) $fuente;
	    return $this;
	}

	/**
	* Get fuente
	*
	* @return null|String
	*/
	public function getFuente()
	{
		return $this->fuente;
	}

	/**
	* Set urlFuente
	*
	*Campo donde se registra la url de la noticia en caso de no ser generada originalmente por Agrocalidad..
	*
	* @parámetro String $urlFuente
	* @return UrlFuente
	*/
	public function setUrlFuente($urlFuente)
	{
	  $this->urlFuente = (String) $urlFuente;
	    return $this;
	}

	/**
	* Get urlFuente
	*
	* @return null|String
	*/
	public function getUrlFuente()
	{
		return $this->urlFuente;
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
	* @return NoticiasModelo
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
		return parent::buscarLista($where, $order, $count, $offset);
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
