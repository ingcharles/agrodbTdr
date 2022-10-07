<?php
 /**
 * Modelo SubproductosEmisionCertificadoModelo
 *
 * Este archivo se complementa con el archivo   SubproductosEmisionCertificadoLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @uses    SubproductosEmisionCertificadoModelo
 * @subpackage Modelos
 */
  namespace Agrodb\EmisionCertificacionOrigen\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class SubproductosEmisionCertificadoModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
		protected $idSubproductosEmisionCertificado;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave foránea dela tabla detalle_emision_certificado
		*/
		protected $idDetalleEmisionCertificado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Subproducto agregado
		*/
		protected $subproducto;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $cantidadMovilizar;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $saldoDisponible;
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
		* Llave foránea de la tabla subproductos
		*/
		protected $idSubproductos;
		/**
		 * @var String
		 * Campo requerido
		 * Campo visible en el formulario
		 * Lote movilizar del registro
		 */
		protected $loteMovilizar;
		/**
		 * @var Integer
		 * Campo requerido
		 * Campo visible en el formulario
		 */
		protected $idEmisionCertificado;
		/**
		 * @var Integer
		 * Campo requerido
		 * Campo visible en el formulario
		 */
		protected $tipoEspecie;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_emision_certificacion_origen";

	/**
	* Nombre de la tabla: subproductos_emision_certificado
	* 
	 */
	Private $tabla="subproductos_emision_certificado";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_subproductos_emision_certificado";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_emision_certificacion_origen"."subproductos_emision_certific_id_subproductos_emision_certi_seq'; 



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
		throw new \Exception('Clase Modelo: SubproductosEmisionCertificadoModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: SubproductosEmisionCertificadoModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_emision_certificacion_origen
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idSubproductosEmisionCertificado
	*
	*Llave primaria de la tabla
	*
	* @parámetro Integer $idSubproductosEmisionCertificado
	* @return IdSubproductosEmisionCertificado
	*/
	public function setIdSubproductosEmisionCertificado($idSubproductosEmisionCertificado)
	{
	  $this->idSubproductosEmisionCertificado = (Integer) $idSubproductosEmisionCertificado;
	    return $this;
	}

	/**
	* Get idSubproductosEmisionCertificado
	*
	* @return null|Integer
	*/
	public function getIdSubproductosEmisionCertificado()
	{
		return $this->idSubproductosEmisionCertificado;
	}

	/**
	* Set idDetalleEmisionCertificado
	*
	*Llave foránea dela tabla detalle_emision_certificado
	*
	* @parámetro Integer $idDetalleEmisionCertificado
	* @return IdDetalleEmisionCertificado
	*/
	public function setIdDetalleEmisionCertificado($idDetalleEmisionCertificado)
	{
	    $this->idDetalleEmisionCertificado = (String) $idDetalleEmisionCertificado;
	    return $this;
	}

	/**
	* Get idDetalleEmisionCertificado
	*
	* @return null|Integer
	*/
	public function getIdDetalleEmisionCertificado()
	{
		return $this->idDetalleEmisionCertificado;
	}

	/**
	* Set subproducto
	*
	*Subproducto agregado
	*
	* @parámetro String $subproducto
	* @return Subproducto
	*/
	public function setSubproducto($subproducto)
	{
	  $this->subproducto = (String) $subproducto;
	    return $this;
	}

	/**
	* Get subproducto
	*
	* @return null|String
	*/
	public function getSubproducto()
	{
		return $this->subproducto;
	}

	/**
	* Set cantidadMovilizar
	*
	*
	*
	* @parámetro Integer $cantidadMovilizar
	* @return CantidadMovilizar
	*/
	public function setCantidadMovilizar($cantidadMovilizar)
	{
	  $this->cantidadMovilizar = (Integer) $cantidadMovilizar;
	    return $this;
	}

	/**
	* Get cantidadMovilizar
	*
	* @return null|Integer
	*/
	public function getCantidadMovilizar()
	{
		return $this->cantidadMovilizar;
	}

	/**
	* Set saldoDisponible
	*
	*
	*
	* @parámetro Integer $saldoDisponible
	* @return SaldoDisponible
	*/
	public function setSaldoDisponible($saldoDisponible)
	{
	  $this->saldoDisponible = (Integer) $saldoDisponible;
	    return $this;
	}

	/**
	* Get saldoDisponible
	*
	* @return null|Integer
	*/
	public function getSaldoDisponible()
	{
		return $this->saldoDisponible;
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
	 * Set lote movilizar
	 *
	 *Estado del registro
	 *
	 * @parámetro String $estado
	 * @return Estado
	 */
	public function setLoteMovilizar($loteMovilizar)
	{
	    $this->loteMovilizar = (String) $loteMovilizar;
	    return $this;
	}
	
	/**
	 * Get estado
	 *
	 * @return null|String
	 */
	public function getLoteMovilizar()
	{
	    return $this->loteMovilizar;
	}

	/**
	* Set idSubproductos
	*
	*Llave foránea de la tabla subproductos
	*
	* @parámetro Integer $idSubproductos
	* @return IdSubproductos
	*/
	public function setIdSubproductos($idSubproductos)
	{
	  $this->idSubproductos = (Integer) $idSubproductos;
	    return $this;
	}

	/**
	* Get idSubproductos
	*
	* @return null|Integer
	*/
	public function getIdSubproductos()
	{
		return $this->idSubproductos;
	}

	/**
	 * Set idEmisionCertificado
	 *
	 * @parámetro Integer $idEmisionCertificado
	 * @return IdEmisionCertificado
	 */
	public function setIdEmisionCertificado($idEmisionCertificado)
	{
	    $this->idEmisionCertificado = (Integer) $idEmisionCertificado;
	    return $this;
	}
	
	/**
	 * Get tipoEspecie
	 *
	 */
	public function getIdEmisionCertificado()
	{
	    return $this->idEmisionCertificado;
	}
	/**
	 * Set tipoEspecie
	 *
	 * @return TipoEspecie
	 */
	public function setTipoEspecie($tipoEspecie)
	{
	    $this->tipoEspecie = (String) $tipoEspecie;
	    return $this;
	}
	
	/**
	 * Get tipoEspecie
	 *
	 * @return null|String
	 */
	public function getTipoEspecie()
	{
	    return $this->tipoEspecie;
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
	public function borrarPorParametro($param, $value)
	{
	    return parent::borrar($param . " = " . $value);
	}
	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return SubproductosEmisionCertificadoModelo
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
