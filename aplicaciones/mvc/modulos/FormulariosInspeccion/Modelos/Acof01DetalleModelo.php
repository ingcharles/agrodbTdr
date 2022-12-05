<?php
 /**
 * Modelo Acof01DetalleModelo
 *
 * Este archivo se complementa con el archivo   Acof01DetalleLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-04-05
 * @uses    Acof01DetalleModelo
 * @package FormulariosInspeccion
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class Acof01DetalleModelo extends ModeloBase 
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
		protected $mayor10;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $colorMayor10;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $mayor2;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $colorMayor2;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $menor2;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $colorMenor2;
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
	* Nombre de la tabla: acof01_detalle
	* 
	 */
	Private $tabla="acof01_detalle";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id";



	/**
	*Secuencia
*/
		 private $secuencial = 'f_inspeccion"."acof01_detalle_id_seq'; 



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
		throw new \Exception('Clase Modelo: Acof01DetalleModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: Acof01DetalleModelo. Propiedad especificada invalida: get'.$name);
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
	* Set mayor10
	*
	*
	*
	* @parámetro String $mayor10
	* @return Mayor10
	*/
	public function setMayor10($mayor10)
	{
	  $this->mayor10 = (String) $mayor10;
	    return $this;
	}

	/**
	* Get mayor10
	*
	* @return null|String
	*/
	public function getMayor10()
	{
		return $this->mayor10;
	}

	/**
	* Set colorMayor10
	*
	*
	*
	* @parámetro String $colorMayor10
	* @return ColorMayor10
	*/
	public function setColorMayor10($colorMayor10)
	{
	  $this->colorMayor10 = (String) $colorMayor10;
	    return $this;
	}

	/**
	* Get colorMayor10
	*
	* @return null|String
	*/
	public function getColorMayor10()
	{
		return $this->colorMayor10;
	}

	/**
	* Set mayor2
	*
	*
	*
	* @parámetro String $mayor2
	* @return Mayor2
	*/
	public function setMayor2($mayor2)
	{
	  $this->mayor2 = (String) $mayor2;
	    return $this;
	}

	/**
	* Get mayor2
	*
	* @return null|String
	*/
	public function getMayor2()
	{
		return $this->mayor2;
	}

	/**
	* Set colorMayor2
	*
	*
	*
	* @parámetro String $colorMayor2
	* @return ColorMayor2
	*/
	public function setColorMayor2($colorMayor2)
	{
	  $this->colorMayor2 = (String) $colorMayor2;
	    return $this;
	}

	/**
	* Get colorMayor2
	*
	* @return null|String
	*/
	public function getColorMayor2()
	{
		return $this->colorMayor2;
	}

	/**
	* Set menor2
	*
	*
	*
	* @parámetro String $menor2
	* @return Menor2
	*/
	public function setMenor2($menor2)
	{
	  $this->menor2 = (String) $menor2;
	    return $this;
	}

	/**
	* Get menor2
	*
	* @return null|String
	*/
	public function getMenor2()
	{
		return $this->menor2;
	}

	/**
	* Set colorMenor2
	*
	*
	*
	* @parámetro String $colorMenor2
	* @return ColorMenor2
	*/
	public function setColorMenor2($colorMenor2)
	{
	  $this->colorMenor2 = (String) $colorMenor2;
	    return $this;
	}

	/**
	* Get colorMenor2
	*
	* @return null|String
	*/
	public function getColorMenor2()
	{
		return $this->colorMenor2;
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
	* @return Acof01DetalleModelo
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
