<?php
 /**
 * Modelo DatosContratoModelo
 *
 * Este archivo se complementa con el archivo   DatosContratoLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021/08/23
 * @uses    DatosContratoModelo
 * @package GUath
 * @subpackage Modelos
 */
  namespace Agrodb\GUath\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class DatosContratoModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave principal de la tabla
		*/
		protected $idDatosContrato;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Llave foránea de la tabla g_uath.ficha_empleado
		*/
		protected $identificador;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Tipo de contrato del funcionario
		*/
		protected $tipoContrato;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Número del contrato
		*/
		protected $numeroContrato;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de inicio del contrato
		*/
		protected $fechaInicio;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de finalización del contrato
		*/
		protected $fechaFin;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Observación realizada por Talento humano
		*/
		protected $observacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Ruta del archivo subido del contrato
		*/
		protected $archivoContrato;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Régimen laboral
		*/
		protected $regimenLaboral;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Número de notaría donde se realiza declaración juramentada
		*/
		protected $numeroNotaria;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Lugar donde se realiza la notarización
		*/
		protected $lugarNotaria;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de declaración
		*/
		protected $fechaDeclaracion;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha en la que se modifica algún campo del dato de contrato
		*/
		protected $fechaModificacion;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* 1 -> activo
2 -> inactivo
3-> finalizado
		*/
		protected $estado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Presupuesto contractual
		*/
		protected $presupuesto;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Fuente de la partida presupuestaria
		*/
		protected $fuente;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Partida presupuestaria
		*/
		protected $partidaPresupuestaria;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Provincia a la que pertenece el contrato
		*/
		protected $provincia;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Cantón al que pertenece el contrato
		*/
		protected $canton;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Oficina a la que pertenece el contrato
		*/
		protected $oficina;
		/**
		* @var Integer
		* Campo requerido
		* Campo visible en el formulario
		* Llave foránea de la tabla g_catalogos.localizacion
		*/
		protected $idOficina;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Dirección a la que pertenece el contrato dentro de la estructura organizacional
		*/
		protected $direccion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Coordinación a la que pertenece el contrato dentro de la estructura organizacional
		*/
		protected $coordinacion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Grupo ocupacional al que pertenece el contrato dentro de la estructura organizacional
		*/
		protected $grupoOcupacional;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Nombre del cargo al que pertenece el contrato dentro de la estructura organizacional
		*/
		protected $nombrePuesto;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Asociada a la partida general en casos especiales
		*/
		protected $partidaIndividual;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Remuneración salarial establecida para el contrato
		*/
		protected $remuneracion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Grado del grupo ocupacional al que pertenece el contrato
		*/
		protected $grado;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo que determina si se contabilizan los días de contrato
		*/
		protected $contabilizarDias;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Motivo de finalización de contrato
		*/
		protected $motivoTerminacionLaboral;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo sin utilización
		*/
		protected $nota;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Campo sin utilización
		*/
		protected $escalaCalificacion;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* Fecha de finalización de contrato
		*/
		protected $fechaSalida;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Gestión a la que pertenece el contrato
		*/
		protected $gestion;
		/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Identificador de la gestión
		*/
		protected $idGestion;
		/**
		* @var Date
		* Campo requerido
		* Campo visible en el formulario
		* 
		*/
		protected $fechaCreacionContrato;

	/**
	* Campos del formulario 
	* @var array 
	 */
	Private $campos = Array();

	/**
	* Nombre del esquema 
	* 
	 */
	Private $esquema ="g_uath";

	/**
	* Nombre de la tabla: datos_contrato
	* 
	 */
	Private $tabla="datos_contrato";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_datos_contrato";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_uath"."DatosContrato_id_datos_contrato_seq'; 



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
		throw new \Exception('Clase Modelo: DatosContratoModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: DatosContratoModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_uath
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idDatosContrato
	*
	*Llave principal de la tabla
	*
	* @parámetro Integer $idDatosContrato
	* @return IdDatosContrato
	*/
	public function setIdDatosContrato($idDatosContrato)
	{
	  $this->idDatosContrato = (Integer) $idDatosContrato;
	    return $this;
	}

	/**
	* Get idDatosContrato
	*
	* @return null|Integer
	*/
	public function getIdDatosContrato()
	{
		return $this->idDatosContrato;
	}

	/**
	* Set identificador
	*
	*Llave foránea de la tabla g_uath.ficha_empleado
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
	* Set tipoContrato
	*
	*Tipo de contrato del funcionario
	*
	* @parámetro String $tipoContrato
	* @return TipoContrato
	*/
	public function setTipoContrato($tipoContrato)
	{
	  $this->tipoContrato = (String) $tipoContrato;
	    return $this;
	}

	/**
	* Get tipoContrato
	*
	* @return null|String
	*/
	public function getTipoContrato()
	{
		return $this->tipoContrato;
	}

	/**
	* Set numeroContrato
	*
	*Número del contrato
	*
	* @parámetro String $numeroContrato
	* @return NumeroContrato
	*/
	public function setNumeroContrato($numeroContrato)
	{
	  $this->numeroContrato = (String) $numeroContrato;
	    return $this;
	}

	/**
	* Get numeroContrato
	*
	* @return null|String
	*/
	public function getNumeroContrato()
	{
		return $this->numeroContrato;
	}

	/**
	* Set fechaInicio
	*
	*Fecha de inicio del contrato
	*
	* @parámetro Date $fechaInicio
	* @return FechaInicio
	*/
	public function setFechaInicio($fechaInicio)
	{
	  $this->fechaInicio = (String) $fechaInicio;
	    return $this;
	}

	/**
	* Get fechaInicio
	*
	* @return null|Date
	*/
	public function getFechaInicio()
	{
		return $this->fechaInicio;
	}

	/**
	* Set fechaFin
	*
	*Fecha de finalización del contrato
	*
	* @parámetro Date $fechaFin
	* @return FechaFin
	*/
	public function setFechaFin($fechaFin)
	{
	  $this->fechaFin = (String) $fechaFin;
	    return $this;
	}

	/**
	* Get fechaFin
	*
	* @return null|Date
	*/
	public function getFechaFin()
	{
		return $this->fechaFin;
	}

	/**
	* Set observacion
	*
	*Observación realizada por Talento humano
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
	* Set archivoContrato
	*
	*Ruta del archivo subido del contrato
	*
	* @parámetro String $archivoContrato
	* @return ArchivoContrato
	*/
	public function setArchivoContrato($archivoContrato)
	{
	  $this->archivoContrato = (String) $archivoContrato;
	    return $this;
	}

	/**
	* Get archivoContrato
	*
	* @return null|String
	*/
	public function getArchivoContrato()
	{
		return $this->archivoContrato;
	}

	/**
	* Set regimenLaboral
	*
	*Régimen laboral
	*
	* @parámetro String $regimenLaboral
	* @return RegimenLaboral
	*/
	public function setRegimenLaboral($regimenLaboral)
	{
	  $this->regimenLaboral = (String) $regimenLaboral;
	    return $this;
	}

	/**
	* Get regimenLaboral
	*
	* @return null|String
	*/
	public function getRegimenLaboral()
	{
		return $this->regimenLaboral;
	}

	/**
	* Set numeroNotaria
	*
	*Número de notaría donde se realiza declaración juramentada
	*
	* @parámetro Integer $numeroNotaria
	* @return NumeroNotaria
	*/
	public function setNumeroNotaria($numeroNotaria)
	{
	  $this->numeroNotaria = (Integer) $numeroNotaria;
	    return $this;
	}

	/**
	* Get numeroNotaria
	*
	* @return null|Integer
	*/
	public function getNumeroNotaria()
	{
		return $this->numeroNotaria;
	}

	/**
	* Set lugarNotaria
	*
	*Lugar donde se realiza la notarización
	*
	* @parámetro String $lugarNotaria
	* @return LugarNotaria
	*/
	public function setLugarNotaria($lugarNotaria)
	{
	  $this->lugarNotaria = (String) $lugarNotaria;
	    return $this;
	}

	/**
	* Get lugarNotaria
	*
	* @return null|String
	*/
	public function getLugarNotaria()
	{
		return $this->lugarNotaria;
	}

	/**
	* Set fechaDeclaracion
	*
	*Fecha de declaración
	*
	* @parámetro Date $fechaDeclaracion
	* @return FechaDeclaracion
	*/
	public function setFechaDeclaracion($fechaDeclaracion)
	{
	  $this->fechaDeclaracion = (String) $fechaDeclaracion;
	    return $this;
	}

	/**
	* Get fechaDeclaracion
	*
	* @return null|Date
	*/
	public function getFechaDeclaracion()
	{
		return $this->fechaDeclaracion;
	}

	/**
	* Set fechaModificacion
	*
	*Fecha en la que se modifica algún campo del dato de contrato
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
	*1 -> activo
2 -> inactivo
3-> finalizado
	*
	* @parámetro Integer $estado
	* @return Estado
	*/
	public function setEstado($estado)
	{
	  $this->estado = (Integer) $estado;
	    return $this;
	}

	/**
	* Get estado
	*
	* @return null|Integer
	*/
	public function getEstado()
	{
		return $this->estado;
	}

	/**
	* Set presupuesto
	*
	*Presupuesto contractual
	*
	* @parámetro String $presupuesto
	* @return Presupuesto
	*/
	public function setPresupuesto($presupuesto)
	{
	  $this->presupuesto = (String) $presupuesto;
	    return $this;
	}

	/**
	* Get presupuesto
	*
	* @return null|String
	*/
	public function getPresupuesto()
	{
		return $this->presupuesto;
	}

	/**
	* Set fuente
	*
	*Fuente de la partida presupuestaria
	*
	* @parámetro Integer $fuente
	* @return Fuente
	*/
	public function setFuente($fuente)
	{
	  $this->fuente = (Integer) $fuente;
	    return $this;
	}

	/**
	* Get fuente
	*
	* @return null|Integer
	*/
	public function getFuente()
	{
		return $this->fuente;
	}

	/**
	* Set partidaPresupuestaria
	*
	*Partida presupuestaria
	*
	* @parámetro String $partidaPresupuestaria
	* @return PartidaPresupuestaria
	*/
	public function setPartidaPresupuestaria($partidaPresupuestaria)
	{
	  $this->partidaPresupuestaria = (String) $partidaPresupuestaria;
	    return $this;
	}

	/**
	* Get partidaPresupuestaria
	*
	* @return null|String
	*/
	public function getPartidaPresupuestaria()
	{
		return $this->partidaPresupuestaria;
	}

	/**
	* Set provincia
	*
	*Provincia a la que pertenece el contrato
	*
	* @parámetro String $provincia
	* @return Provincia
	*/
	public function setProvincia($provincia)
	{
	  $this->provincia = (String) $provincia;
	    return $this;
	}

	/**
	* Get provincia
	*
	* @return null|String
	*/
	public function getProvincia()
	{
		return $this->provincia;
	}

	/**
	* Set canton
	*
	*Cantón al que pertenece el contrato
	*
	* @parámetro String $canton
	* @return Canton
	*/
	public function setCanton($canton)
	{
	  $this->canton = (String) $canton;
	    return $this;
	}

	/**
	* Get canton
	*
	* @return null|String
	*/
	public function getCanton()
	{
		return $this->canton;
	}

	/**
	* Set oficina
	*
	*Oficina a la que pertenece el contrato
	*
	* @parámetro String $oficina
	* @return Oficina
	*/
	public function setOficina($oficina)
	{
	  $this->oficina = (String) $oficina;
	    return $this;
	}

	/**
	* Get oficina
	*
	* @return null|String
	*/
	public function getOficina()
	{
		return $this->oficina;
	}

	/**
	* Set idOficina
	*
	*Llave foránea de la tabla g_catalogos.localizacion
	*
	* @parámetro Integer $idOficina
	* @return IdOficina
	*/
	public function setIdOficina($idOficina)
	{
	  $this->idOficina = (Integer) $idOficina;
	    return $this;
	}

	/**
	* Get idOficina
	*
	* @return null|Integer
	*/
	public function getIdOficina()
	{
		return $this->idOficina;
	}

	/**
	* Set direccion
	*
	*Dirección a la que pertenece el contrato dentro de la estructura organizacional
	*
	* @parámetro String $direccion
	* @return Direccion
	*/
	public function setDireccion($direccion)
	{
	  $this->direccion = (String) $direccion;
	    return $this;
	}

	/**
	* Get direccion
	*
	* @return null|String
	*/
	public function getDireccion()
	{
		return $this->direccion;
	}

	/**
	* Set coordinacion
	*
	*Coordinación a la que pertenece el contrato dentro de la estructura organizacional
	*
	* @parámetro String $coordinacion
	* @return Coordinacion
	*/
	public function setCoordinacion($coordinacion)
	{
	  $this->coordinacion = (String) $coordinacion;
	    return $this;
	}

	/**
	* Get coordinacion
	*
	* @return null|String
	*/
	public function getCoordinacion()
	{
		return $this->coordinacion;
	}

	/**
	* Set grupoOcupacional
	*
	*Grupo ocupacional al que pertenece el contrato dentro de la estructura organizacional
	*
	* @parámetro String $grupoOcupacional
	* @return GrupoOcupacional
	*/
	public function setGrupoOcupacional($grupoOcupacional)
	{
	  $this->grupoOcupacional = (String) $grupoOcupacional;
	    return $this;
	}

	/**
	* Get grupoOcupacional
	*
	* @return null|String
	*/
	public function getGrupoOcupacional()
	{
		return $this->grupoOcupacional;
	}

	/**
	* Set nombrePuesto
	*
	*Nombre del cargo al que pertenece el contrato dentro de la estructura organizacional
	*
	* @parámetro String $nombrePuesto
	* @return NombrePuesto
	*/
	public function setNombrePuesto($nombrePuesto)
	{
	  $this->nombrePuesto = (String) $nombrePuesto;
	    return $this;
	}

	/**
	* Get nombrePuesto
	*
	* @return null|String
	*/
	public function getNombrePuesto()
	{
		return $this->nombrePuesto;
	}

	/**
	* Set partidaIndividual
	*
	*Asociada a la partida general en casos especiales
	*
	* @parámetro String $partidaIndividual
	* @return PartidaIndividual
	*/
	public function setPartidaIndividual($partidaIndividual)
	{
	  $this->partidaIndividual = (String) $partidaIndividual;
	    return $this;
	}

	/**
	* Get partidaIndividual
	*
	* @return null|String
	*/
	public function getPartidaIndividual()
	{
		return $this->partidaIndividual;
	}

	/**
	* Set remuneracion
	*
	*Remuneración salarial establecida para el contrato
	*
	* @parámetro String $remuneracion
	* @return Remuneracion
	*/
	public function setRemuneracion($remuneracion)
	{
	  $this->remuneracion = (String) $remuneracion;
	    return $this;
	}

	/**
	* Get remuneracion
	*
	* @return null|String
	*/
	public function getRemuneracion()
	{
		return $this->remuneracion;
	}

	/**
	* Set grado
	*
	*Grado del grupo ocupacional al que pertenece el contrato
	*
	* @parámetro String $grado
	* @return Grado
	*/
	public function setGrado($grado)
	{
	  $this->grado = (String) $grado;
	    return $this;
	}

	/**
	* Get grado
	*
	* @return null|String
	*/
	public function getGrado()
	{
		return $this->grado;
	}

	/**
	* Set contabilizarDias
	*
	*Campo que determina si se contabilizan los días de contrato
	*
	* @parámetro String $contabilizarDias
	* @return ContabilizarDias
	*/
	public function setContabilizarDias($contabilizarDias)
	{
	  $this->contabilizarDias = (String) $contabilizarDias;
	    return $this;
	}

	/**
	* Get contabilizarDias
	*
	* @return null|String
	*/
	public function getContabilizarDias()
	{
		return $this->contabilizarDias;
	}

	/**
	* Set motivoTerminacionLaboral
	*
	*Motivo de finalización de contrato
	*
	* @parámetro String $motivoTerminacionLaboral
	* @return MotivoTerminacionLaboral
	*/
	public function setMotivoTerminacionLaboral($motivoTerminacionLaboral)
	{
	  $this->motivoTerminacionLaboral = (String) $motivoTerminacionLaboral;
	    return $this;
	}

	/**
	* Get motivoTerminacionLaboral
	*
	* @return null|String
	*/
	public function getMotivoTerminacionLaboral()
	{
		return $this->motivoTerminacionLaboral;
	}

	/**
	* Set nota
	*
	*Campo sin utilización
	*
	* @parámetro String $nota
	* @return Nota
	*/
	public function setNota($nota)
	{
	  $this->nota = (String) $nota;
	    return $this;
	}

	/**
	* Get nota
	*
	* @return null|String
	*/
	public function getNota()
	{
		return $this->nota;
	}

	/**
	* Set escalaCalificacion
	*
	*Campo sin utilización
	*
	* @parámetro String $escalaCalificacion
	* @return EscalaCalificacion
	*/
	public function setEscalaCalificacion($escalaCalificacion)
	{
	  $this->escalaCalificacion = (String) $escalaCalificacion;
	    return $this;
	}

	/**
	* Get escalaCalificacion
	*
	* @return null|String
	*/
	public function getEscalaCalificacion()
	{
		return $this->escalaCalificacion;
	}

	/**
	* Set fechaSalida
	*
	*Fecha de finalización de contrato
	*
	* @parámetro Date $fechaSalida
	* @return FechaSalida
	*/
	public function setFechaSalida($fechaSalida)
	{
	  $this->fechaSalida = (String) $fechaSalida;
	    return $this;
	}

	/**
	* Get fechaSalida
	*
	* @return null|Date
	*/
	public function getFechaSalida()
	{
		return $this->fechaSalida;
	}

	/**
	* Set gestion
	*
	*Gestión a la que pertenece el contrato
	*
	* @parámetro String $gestion
	* @return Gestion
	*/
	public function setGestion($gestion)
	{
	  $this->gestion = (String) $gestion;
	    return $this;
	}

	/**
	* Get gestion
	*
	* @return null|String
	*/
	public function getGestion()
	{
		return $this->gestion;
	}

	/**
	* Set idGestion
	*
	*Identificador de la gestión
	*
	* @parámetro String $idGestion
	* @return IdGestion
	*/
	public function setIdGestion($idGestion)
	{
	  $this->idGestion = (String) $idGestion;
	    return $this;
	}

	/**
	* Get idGestion
	*
	* @return null|String
	*/
	public function getIdGestion()
	{
		return $this->idGestion;
	}

	/**
	* Set fechaCreacionContrato
	*
	*
	*
	* @parámetro Date $fechaCreacionContrato
	* @return FechaCreacionContrato
	*/
	public function setFechaCreacionContrato($fechaCreacionContrato)
	{
	  $this->fechaCreacionContrato = (String) $fechaCreacionContrato;
	    return $this;
	}

	/**
	* Get fechaCreacionContrato
	*
	* @return null|Date
	*/
	public function getFechaCreacionContrato()
	{
		return $this->fechaCreacionContrato;
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
	* @return DatosContratoModelo
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
