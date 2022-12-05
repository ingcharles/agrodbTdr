<?php
 /**
 * Modelo ResponsablesCertificadosModelo
 *
 * Este archivo se complementa con el archivo   ResponsablesCertificadosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-09-23
 * @uses    ResponsablesCertificadosModelo
 * @package Catalogos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class ResponsablesCertificadosModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Identificador único de la tabla
		*/
		protected $idResponsableCertificado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Número de cédula del funcionario
		*/
		protected $identificador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Cargo que desempeña en Agrocalidad
		*/
		protected $cargo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombres y apellidos del funcionario
		*/
		protected $nombre;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Siglas del título
		*/
		protected $siglasTitulo;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Ruta en el servidor en donde se encuentra la imagen de la firma del funcionario
		*/
		protected $rutaFirma;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Estado del reposnable de firma
		*/
		protected $estado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre de la provincia a la cual pertence el responsable
		*/
		protected $nombreProvincia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que indica a que area pertenece el firmante.
		*/
		protected $idArea;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_catalogos";

	/**
	* Nombre de la tabla: responsables_certificados
	* 
	 */
	Private $tabla="responsables_certificados";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_responsable_certificado";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_catalogos."responsables_certificados_id_responsable_certificado_seq'; 

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
		throw new \Exception('Clase Modelo: ResponsablesCertificadosModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: ResponsablesCertificadosModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_catalogos
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idResponsableCertificado
	*
	*Identificador único de la tabla
	*
	* @parámetro Integer $idResponsableCertificado
	* @return IdResponsableCertificado
	*/
	public function setIdResponsableCertificado($idResponsableCertificado)
	{
	  $this->idResponsableCertificado = (Integer) $idResponsableCertificado;
	    return $this;
	}

	/**
	* Get idResponsableCertificado
	*
	* @return null|Integer
	*/
	public function getIdResponsableCertificado()
	{
		return $this->idResponsableCertificado;
	}

	/**
	* Set identificador
	*
	*Número de cédula del funcionario
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
	* Set cargo
	*
	*Cargo que desempeña en Agrocalidad
	*
	* @parámetro String $cargo
	* @return Cargo
	*/
	public function setCargo($cargo)
	{
	  $this->cargo = (String) $cargo;
	    return $this;
	}

	/**
	* Get cargo
	*
	* @return null|String
	*/
	public function getCargo()
	{
		return $this->cargo;
	}

	/**
	* Set nombre
	*
	*Nombres y apellidos del funcionario
	*
	* @parámetro String $nombre
	* @return Nombre
	*/
	public function setNombre($nombre)
	{
	  $this->nombre = (String) $nombre;
	    return $this;
	}

	/**
	* Get nombre
	*
	* @return null|String
	*/
	public function getNombre()
	{
		return $this->nombre;
	}

	/**
	* Set siglasTitulo
	*
	*Siglas del título
	*
	* @parámetro String $siglasTitulo
	* @return SiglasTitulo
	*/
	public function setSiglasTitulo($siglasTitulo)
	{
	  $this->siglasTitulo = (String) $siglasTitulo;
	    return $this;
	}

	/**
	* Get siglasTitulo
	*
	* @return null|String
	*/
	public function getSiglasTitulo()
	{
		return $this->siglasTitulo;
	}

	/**
	* Set rutaFirma
	*
	*Ruta en el servidor en donde se encuentra la imagen de la firma del funcionario
	*
	* @parámetro String $rutaFirma
	* @return RutaFirma
	*/
	public function setRutaFirma($rutaFirma)
	{
	  $this->rutaFirma = (String) $rutaFirma;
	    return $this;
	}

	/**
	* Get rutaFirma
	*
	* @return null|String
	*/
	public function getRutaFirma()
	{
		return $this->rutaFirma;
	}

	/**
	* Set estado
	*
	*Estado del reposnable de firma
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
	* Set nombreProvincia
	*
	*Nombre de la provincia a la cual pertence el responsable
	*
	* @parámetro String $nombreProvincia
	* @return NombreProvincia
	*/
	public function setNombreProvincia($nombreProvincia)
	{
	  $this->nombreProvincia = (String) $nombreProvincia;
	    return $this;
	}

	/**
	* Get nombreProvincia
	*
	* @return null|String
	*/
	public function getNombreProvincia()
	{
		return $this->nombreProvincia;
	}

	/**
	* Set idArea
	*
	*Campo que indica a que area pertenece el firmante.
	*
	* @parámetro String $idArea
	* @return IdArea
	*/
	public function setIdArea($idArea)
	{
	  $this->idArea = (String) $idArea;
	    return $this;
	}

	/**
	* Get idArea
	*
	* @return null|String
	*/
	public function getIdArea()
	{
		return $this->idArea;
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
	* @return ResponsablesCertificadosModelo
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
