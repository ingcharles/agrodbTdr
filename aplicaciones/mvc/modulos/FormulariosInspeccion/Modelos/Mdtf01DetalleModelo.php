<?php
 /**
 * Modelo Mdtf01DetalleModelo
 *
 * Este archivo se complementa con el archivo   Mdtf01DetalleLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-09-23
 * @uses    Mdtf01DetalleModelo
 * @package FormulariosInspeccion
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class Mdtf01DetalleModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $id;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $idPadre;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $numero;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $tema;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $criterioCumplimiento;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $tanquero;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $colorTanquero;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $bidon;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $colorBidon;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
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
	* Nombre de la tabla: mdtf01_detalle
	* 
	 */
	Private $tabla="mdtf01_detalle";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id";



	/**
	*Secuencia
*/
	 
	private $secuencial = 'f_inspeccion.mdtf01_detalle_id_seq';


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
		throw new \Exception('Clase Modelo: Mdtf01DetalleModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: Mdtf01DetalleModelo. Propiedad especificada invalida: get'.$name);
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
	*
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
	*
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
	* Set numero
	*
	*
	*
	* @parámetro Integer $numero
	* @return Numero
	*/
	public function setNumero($numero)
	{
	  $this->numero = (Integer) $numero;
	    return $this;
	}

	/**
	* Get numero
	*
	* @return null|Integer
	*/
	public function getNumero()
	{
		return $this->numero;
	}

	/**
	* Set tema
	*
	*
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
	* Set criterioCumplimiento
	*
	*
	*
	* @parámetro String $criterioCumplimiento
	* @return CriterioCumplimiento
	*/
	public function setCriterioCumplimiento($criterioCumplimiento)
	{
	  $this->criterioCumplimiento = (String) $criterioCumplimiento;
	    return $this;
	}

	/**
	* Get criterioCumplimiento
	*
	* @return null|String
	*/
	public function getCriterioCumplimiento()
	{
		return $this->criterioCumplimiento;
	}

	/**
	* Set tanquero
	*
	*
	*
	* @parámetro String $tanquero
	* @return Tanquero
	*/
	public function setTanquero($tanquero)
	{
	  $this->tanquero = (String) $tanquero;
	    return $this;
	}

	/**
	* Get tanquero
	*
	* @return null|String
	*/
	public function getTanquero()
	{
		return $this->tanquero;
	}

	/**
	* Set colorTanquero
	*
	*
	*
	* @parámetro String $colorTanquero
	* @return ColorTanquero
	*/
	public function setColorTanquero($colorTanquero)
	{
	  $this->colorTanquero = (String) $colorTanquero;
	    return $this;
	}

	/**
	* Get colorTanquero
	*
	* @return null|String
	*/
	public function getColorTanquero()
	{
		return $this->colorTanquero;
	}

	/**
	* Set bidon
	*
	*
	*
	* @parámetro String $bidon
	* @return Bidon
	*/
	public function setBidon($bidon)
	{
	  $this->bidon = (String) $bidon;
	    return $this;
	}

	/**
	* Get bidon
	*
	* @return null|String
	*/
	public function getBidon()
	{
		return $this->bidon;
	}

	/**
	* Set colorBidon
	*
	*
	*
	* @parámetro String $colorBidon
	* @return ColorBidon
	*/
	public function setColorBidon($colorBidon)
	{
	  $this->colorBidon = (String) $colorBidon;
	    return $this;
	}

	/**
	* Get colorBidon
	*
	* @return null|String
	*/
	public function getColorBidon()
	{
		return $this->colorBidon;
	}

	/**
	* Set observacion
	*
	*
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
	* @return Mdtf01DetalleModelo
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
