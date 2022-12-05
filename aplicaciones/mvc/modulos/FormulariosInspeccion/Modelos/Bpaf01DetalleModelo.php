<?php
 /**
 * Modelo Bpaf01DetalleModelo
 *
 * Este archivo se complementa con el archivo   Bpaf01DetalleLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-09-23
 * @uses    Bpaf01DetalleModelo
 * @package FormulariosInspeccion
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class Bpaf01DetalleModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador unico de la tabla
		*/
		protected $id;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el id del resumen de la inspeccion de tablet
		*/
		protected $idPadre;
		
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el numero de articulo
		*/
		protected $articulo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena los criterio de inspeccion BPA
		*/
		protected $tema;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena valores de parametrización no conformidad mayor(NCM) y para los colores a cada fila
		*/
		protected $tipoNivel;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena valores (no conformidad mayor(NCM), desvicación)
		*/
		protected $nivel;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el valor del cumplimiento del requisito del tema (A,B,C,D,NCM)
		*/

		protected $criterio;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el valor del cumplimiento del requisito del tema (A,B,C,D,NCM)
		*/
		
		protected $medio;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena el valor del cumplimiento del requisito del tema (A,B,C,D,NCM)
		*/
		

		protected $cumple;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena si aplica o no al requisito del tema
		*/
		protected $noAplica;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que almacena observacion
		*/
		protected $observacion;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="f_inspeccion";

	/**
	* Nombre de la tabla: bpaf01_detalle
	* 
	 */
	Private $tabla="bpaf01_detalle";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id";



	/**
	*Secuencia
*/
	
		 private $secuencial = 'f_inspeccion"."bpaf01_detalle_id_seq';

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
		throw new \Exception('Clase Modelo: Bpaf01DetalleModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: Bpaf01DetalleModelo. Propiedad especificada invalida: get'.$name);
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
	* Get f_inspeccion
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set id
	*
	*Identificador unico de la tabla
	*
	* @parámetro Integer $id
	* @return Id
	*/
	public function setId($id)
	{
	  $this->id = (Integer) $id;
	    return $this;
	}

	/**
	* Get id
	*
	* @return null|Integer
	*/
	public function getId()
	{
		return $this->id;
	}

	/**
	* Set idPadre
	*
	*Campo que almacena el id del resumen de la inspeccion de tablet
	*
	* @parámetro Integer $idPadre
	* @return IdPadre
	*/
	public function setIdPadre($idPadre)
	{
	  $this->idPadre = (Integer) $idPadre;
	    return $this;
	}

	/**
	* Get idPadre
	*
	* @return null|Integer
	*/
	public function getIdPadre()
	{
		return $this->idPadre;
	}


	/**
	* Set articulo
	*
	*Campo que almacena el numero de articulo
	*
	* @parámetro String $articulo
	* @return Articulo
	*/
	public function setArticulo($articulo)
	{
	  $this->articulo = (String) $articulo;
	    return $this;
	}

	/**
	* Get articulo
	*
	* @return null|String
	*/
	public function getArticulo()
	{
		return $this->articulo;
	}

	/**
	* Set tema
	*
	*Campo que almacena los criterio de inspeccion BPA
	*
	* @parámetro String $tema
	* @return Tema
	*/
	public function setTema($tema)
	{
	  $this->tema = (String) $tema;
	    return $this;
	}

	/**
	* Get tema
	*
	* @return null|String
	*/
	public function getTema()
	{
		return $this->tema;
	}

	/**
	* Set tipoNivel
	*
	*Campo que almacena valores de parametrización no conformidad mayor(NCM) y para los colores a cada fila
	*
	* @parámetro String $tipoNivel
	* @return TipoNivel
	*/
	public function setTipoNivel($tipoNivel)
	{
	  $this->tipoNivel = (String) $tipoNivel;
	    return $this;
	}

	/**
	* Get tipoNivel
	*
	* @return null|String
	*/
	public function getTipoNivel()
	{
		return $this->tipoNivel;
	}

	/**
	* Set nivel
	*
	*Campo que almacena valores (no conformidad mayor(NCM), desvicación)
	*
	* @parámetro String $nivel
	* @return Nivel
	*/
	public function setNivel($nivel)
	{
	  $this->nivel = (String) $nivel;
	    return $this;
	}

	/**
	* Get nivel
	*
	* @return null|String
	*/
	public function getNivel()
	{
		return $this->nivel;
	}

	/**
	* Set criterio
	*
	*Campo que almacena el valor del cumplimiento del requisito del tema (A,B,C,D,NCM)
	*
	* @parámetro String $criterio
	* @return Criterio
	*/
	public function setCriterio($criterio)
	{
	  $this->criterio = (String) $criterio;
	    return $this;
	}

	/**
	* Get criterio
	*
	* @return null|String
	*/
	public function getCriterio()
	{
		return $this->criterio;
	}

	
	/**
	* Set medio
	*
	*Campo que almacena el valor del cumplimiento del requisito del tema (A,B,C,D,NCM)
	*
	* @parámetro String $medio
	* @return Medio
	*/
	public function setMedio($medio)
	{
	  $this->medio = (String) $medio;
	    return $this;
	}

	/**
	* Get medio
	*
	* @return null|String
	*/
	public function getMedio()
	{
		return $this->medio;
	}

	/**
	* Set cumple
	*
	*Campo que almacena el valor del cumplimiento del requisito del tema (A,B,C,D,NCM)
	*
	* @parámetro String $cumple
	* @return Cumple
	*/
	public function setCumple($cumple)
	{
	  $this->cumple = (String) $cumple;
	    return $this;
	}

	/**
	* Get cumple
	*
	* @return null|String
	*/
	public function getCumple()
	{
		return $this->cumple;
	}

	/**
	* Set noAplica
	*
	*Campo que almacena si aplica o no al requisito del tema
	*
	* @parámetro String $noAplica
	* @return NoAplica
	*/
	public function setNoAplica($noAplica)
	{
	  $this->noAplica = (String) $noAplica;
	    return $this;
	}

	/**
	* Get noAplica
	*
	* @return null|String
	*/
	public function getNoAplica()
	{
		return $this->noAplica;
	}

	/**
	* Set observacion
	*
	*Campo que almacena observacion
	*
	* @parámetro String $observacion
	* @return Observacion
	*/
	public function setObservacion($observacion)
	{
	  $this->observacion = (String) $observacion;
	    return $this;
	}

	/**
	* Get observacion
	*
	* @return null|String
	*/
	public function getObservacion()
	{
		return $this->observacion;
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
	* @return Bpaf01DetalleModelo
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
