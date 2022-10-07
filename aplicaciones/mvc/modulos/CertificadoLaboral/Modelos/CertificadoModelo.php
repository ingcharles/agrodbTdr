<?php
 /**
 * Modelo CertificadoModelo
 *
 * Este archivo se complementa con el archivo   CertificadoLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-02-12
 * @uses    CertificadoModelo
 * @package CertificadoLaboral
 * @subpackage Modelos
 */
  namespace Agrodb\CertificadoLaboral\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class CertificadoModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
        protected $idCertificado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de quien creó el certificado
		*/
		protected $identificador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Ruta del repositorio del archivo Certificado
		*/
		protected $rutaArchivo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Estado del registro
		*/
		protected $estado;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de creación del registro
		*/
		protected $fechaCreacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave foránea de la tabla formato
		*/
		protected $idFormato;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave foránea de la tabla firma_electronica
		*/
		protected $idFirmaElectronica;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Identificador del firmate de talento humano
		*/
		protected $identificadorUath;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_certificados_uath";

	/**
	* Nombre de la tabla: certificado
	* 
	 */
	Private $tabla="certificado";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_certificado";



	/**
	*Secuencia
	*'g_centros_faenamiento"."centros_faenamiento_id_centro_faenamiento_seq';
	*/
		 private $secuencial = 'g_certificados_uath"."certificado_id_certificado_seq'; 



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
		throw new \Exception('Clase Modelo: CertificadoModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: CertificadoModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_certificados_uath
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idCertificado
	*
	*Llave primaria de la tabla
	*
	* @parámetro Integer $idCertificado
	* @return IdCertificado
	*/
	public function setIdCertificado($idCertificado)
	{
	  $this->idCertificado = (Integer) $idCertificado;
	    return $this;
	}

	/**
	* Get idCertificado
	*
	* @return null|Integer
	*/
	public function getIdCertificado()
	{
		return $this->idCertificado;
	}

	/**
	* Set identificador
	*
	*Identificador de quien creó el certificado
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
	* Set rutaArchivo
	*
	*Ruta del repositorio del archivo Certificado
	*
	* @parámetro String $rutaArchivo
	* @return RutaArchivo
	*/
	public function setRutaArchivo($rutaArchivo)
	{
	  $this->rutaArchivo = (String) $rutaArchivo;
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
	* Set idFormato
	*
	*Llave foránea de la tabla formato
	*
	* @parámetro Integer $idFormato
	* @return IdFormato
	*/
	public function setIdFormato($idFormato)
	{
	  $this->idFormato = (Integer) $idFormato;
	    return $this;
	}

	/**
	* Get idFormato
	*
	* @return null|Integer
	*/
	public function getIdFormato()
	{
		return $this->idFormato;
	}

	/**
	* Set idFirmaElectronica
	*
	*Llave foránea de la tabla firma_electronica
	*
	* @parámetro Integer $idFirmaElectronica
	* @return IdFirmaElectronica
	*/
	public function setIdFirmaElectronica($idFirmaElectronica)
	{
	  $this->idFirmaElectronica = (Integer) $idFirmaElectronica;
	    return $this;
	}

	/**
	* Get idFirmaElectronica
	*
	* @return null|Integer
	*/
	public function getIdFirmaElectronica()
	{
		return $this->idFirmaElectronica;
	}

	/**
	* Set identificadorUath
	*
	*Identificador del firmate de talento humano
	*
	* @parámetro String $identificadorUath
	* @return IdentificadorUath
	*/
	public function setIdentificadorUath($identificadorUath)
	{
	  $this->identificadorUath = (String) $identificadorUath;
	    return $this;
	}

	/**
	* Get identificadorUath
	*
	* @return null|String
	*/
	public function getIdentificadorUath()
	{
		return $this->identificadorUath;
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
	* @return CertificadoModelo
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
