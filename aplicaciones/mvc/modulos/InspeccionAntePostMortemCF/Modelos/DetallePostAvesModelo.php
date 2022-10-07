<?php
 /**
 * Modelo DetallePostAvesModelo
 *
 * Este archivo se complementa con el archivo   DetallePostAvesLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    DetallePostAvesModelo
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
  use Agrodb\Core\ModeloBase;
  use Agrodb\Core\ValidarDatos;
 
class DetallePostAvesModelo extends ModeloBase 
{

		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Llave primaria de la tabla
		*/
		protected $idDetallePostAves;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Llave foránea de la tabla formulario_post_mortem
		*/
		protected $idFormularioPostMortem;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Llave foránea de la tabla detalle_ante_aves
		*/
		protected $idDetalleAnteAves;
		/**
		* @var Date
		* Campo opcional
		* Campo visible en el formulario
		* Fecha del post mortem formulario
		*/
		protected $fechaFormulario;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Tipo de ave
		*/
		protected $tipoAve;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Lugar de procedencia
		*/
		protected $lugarProcedencia;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Número CSMI
		*/
		protected $numCsmi;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Número total de aves
		*/
		protected $totalAves;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Promedio de aves
		*/
		protected $promedioAves;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Aves con descarte
		*/
		protected $numDescarte;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Porcentaje de aves con descarte
		*/
		protected $porcentNumDescarte;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Aves con colibacilosis
		*/
		protected $numColibacilosis;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Porcentaje de aves con colibacilosis
		*/
		protected $porcentNumColibacilosis;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Aves con podermatitis
		*/
		protected $numPododermatitis;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Porcentaje de aves con pododermatitis
		*/
		protected $porcentNumPododermatitis;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Aves con lesiones de piel
		*/
		protected $numLesionesPiel;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Porcentaje de aves con lesiones de piel
		*/
		protected $porcentNumLesionesPiel;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Aves con mal sangrado
		*/
		protected $numMalSangrado;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Porcentaje de aves con mal sangrado
		*/
		protected $porcentNumMalSangrado;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Aves con contusíon de pierna
		*/
		protected $numContusionPierna;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* porcentaje de aves con contusíon de pierna
		*/
		protected $porcentNumContusionPierna;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Aves con contusíon de ala
		*/
		protected $numContusionAla;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Porcentaje de aves con contusíon de ala
		*/
		protected $porcentNumContusionAla;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Aves con contusíon de pechuga
		*/
		protected $numContusionPechuga;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Porcentaje de aves con contusíon de pechuga
		*/
		protected $porcentNumContusionPechuga;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Aves con alas rotas
		*/
		protected $numAlasRotas;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Porcentaje de aves con alas rotas
		*/
		protected $porcentNumAlasRotas;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Aves con piernas rotas
		*/
		protected $numPiernasRotas;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Porcentaje de aves con piernas rotas
		*/
		protected $porcentNumPiernasRotas;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Total de canales aprobados
		*/
		protected $totalCanalesAprobados;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Peso total de canles aprobados
		*/
		protected $pesoTotalCanalesAprobadosTotalmente;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Total canales aprobados parcialmente
		*/
		protected $totalCanalesAprobadosParcialmente;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Peso total canales aprobados parcialmente
		*/
		protected $pesoTotalCanalesAprobadosParcialmente;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Canales decomiso parcial
		*/
		protected $canalesDecomisoParcial;
		/**
		 * @var Integer
		 * Campo opcional
		 * Campo visible en el formulario
		 * Canales decomiso total
		 */
		protected $canalesDecomisoTotal;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Peso promedio canales
		*/
		protected $pesoPromedioCanales;
		/**
		* @var Integer
		* Campo opcional
		* Campo visible en el formulario
		* Total carne decomisada
		*/
		protected $totalCarneDecomisada;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Destino decomisos
		*/
		protected $destinoDecomisos;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Lugar disposicion final
		*/
		protected $lugarDisposicionFinal;
		/**
		* @var String
		* Campo opcional
		* Campo visible en el formulario
		* Observacion del registro
		*/
		protected $observacion;
		/**
		* @var Date
		* Campo opcional
		* Campo visible en el formulario
		* 
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
	Private $esquema ="g_centros_faenamiento";

	/**
	* Nombre de la tabla: detalle_post_aves
	* 
	 */
	Private $tabla="detalle_post_aves";

	/**
	*Clave primaria
*/
		 private $clavePrimaria = "id_detalle_post_aves";



	/**
	*Secuencia
*/
		 private $secuencial = 'g_centros_faenamiento"."detalle_post_aves_id_detalle_post_aves_seq'; 



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
		throw new \Exception('Clase Modelo: DetallePostAvesModelo. Propiedad especificada invalida: set'.$name);
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
	  throw new \Exception('Clase Modelo: DetallePostAvesModelo. Propiedad especificada invalida: get'.$name);
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
	* Get g_centros_faenamiento
	*
	* @return null|
	*/
	public function getEsquema()
	{
		 return $this->esquema;
	}

	/**
	* Set idDetallePostAves
	*
	*Llave primaria de la tabla
	*
	* @parámetro Integer $idDetallePostAves
	* @return IdDetallePostAves
	*/
	public function setIdDetallePostAves($idDetallePostAves)
	{
	  $this->idDetallePostAves = (Integer) $idDetallePostAves;
	    return $this;
	}

	/**
	* Get idDetallePostAves
	*
	* @return null|Integer
	*/
	public function getIdDetallePostAves()
	{
		return $this->idDetallePostAves;
	}

	/**
	* Set idFormularioPostMortem
	*
	*Llave foránea de la tabla formulario_post_mortem
	*
	* @parámetro Integer $idFormularioPostMortem
	* @return IdFormularioPostMortem
	*/
	public function setIdFormularioPostMortem($idFormularioPostMortem)
	{
	  $this->idFormularioPostMortem = (Integer) $idFormularioPostMortem;
	    return $this;
	}

	/**
	* Get idFormularioPostMortem
	*
	* @return null|Integer
	*/
	public function getIdFormularioPostMortem()
	{
		return $this->idFormularioPostMortem;
	}

	/**
	* Set idDetalleAnteAves
	*
	*Llave foránea de la tabla detalle_ante_aves
	*
	* @parámetro Integer $idDetalleAnteAves
	* @return IdDetalleAnteAves
	*/
	public function setIdDetalleAnteAves($idDetalleAnteAves)
	{
	  $this->idDetalleAnteAves = (Integer) $idDetalleAnteAves;
	    return $this;
	}

	/**
	* Get idDetalleAnteAves
	*
	* @return null|Integer
	*/
	public function getIdDetalleAnteAves()
	{
		return $this->idDetalleAnteAves;
	}

	/**
	* Set fechaFormulario
	*
	*Fecha del post mortem formulario
	*
	* @parámetro Date $fechaFormulario
	* @return FechaFormulario
	*/
	public function setFechaFormulario($fechaFormulario)
	{
	  $this->fechaFormulario = (String) $fechaFormulario;
	    return $this;
	}

	/**
	* Get fechaFormulario
	*
	* @return null|Date
	*/
	public function getFechaFormulario()
	{
		return $this->fechaFormulario;
	}

	/**
	* Set tipoAve
	*
	*Tipo de ave
	*
	* @parámetro String $tipoAve
	* @return TipoAve
	*/
	public function setTipoAve($tipoAve)
	{
	  $this->tipoAve = (String) $tipoAve;
	    return $this;
	}

	/**
	* Get tipoAve
	*
	* @return null|String
	*/
	public function getTipoAve()
	{
		return $this->tipoAve;
	}

	/**
	* Set lugarProcedencia
	*
	*Lugar de procedencia
	*
	* @parámetro String $lugarProcedencia
	* @return LugarProcedencia
	*/
	public function setLugarProcedencia($lugarProcedencia)
	{
	  $this->lugarProcedencia = (String) $lugarProcedencia;
	    return $this;
	}

	/**
	* Get lugarProcedencia
	*
	* @return null|String
	*/
	public function getLugarProcedencia()
	{
		return $this->lugarProcedencia;
	}

	/**
	* Set numCsmi
	*
	*Número CSMI
	*
	* @parámetro Integer $numCsmi
	* @return NumCsmi
	*/
	public function setNumCsmi($numCsmi)
	{
	  $this->numCsmi = (Integer) $numCsmi;
	    return $this;
	}

	/**
	* Get numCsmi
	*
	* @return null|Integer
	*/
	public function getNumCsmi()
	{
		return $this->numCsmi;
	}

	/**
	* Set totalAves
	*
	*Número total de aves
	*
	* @parámetro Integer $totalAves
	* @return TotalAves
	*/
	public function setTotalAves($totalAves)
	{
	  $this->totalAves = (Integer) $totalAves;
	    return $this;
	}

	/**
	* Get totalAves
	*
	* @return null|Integer
	*/
	public function getTotalAves()
	{
		return $this->totalAves;
	}

	/**
	* Set promedioAves
	*
	*Promedio de aves
	*
	* @parámetro String $promedioAves
	* @return PromedioAves
	*/
	public function setPromedioAves($promedioAves)
	{
	  $this->promedioAves = (String) $promedioAves;
	    return $this;
	}

	/**
	* Get promedioAves
	*
	* @return null|String
	*/
	public function getPromedioAves()
	{
		return $this->promedioAves;
	}

	/**
	* Set numDescarte
	*
	*Aves con descarte
	*
	* @parámetro String $numDescarte
	* @return NumDescarte
	*/
	public function setNumDescarte($numDescarte)
	{
	  $this->numDescarte = (String) $numDescarte;
	    return $this;
	}

	/**
	* Get numDescarte
	*
	* @return null|String
	*/
	public function getNumDescarte()
	{
		return $this->numDescarte;
	}

	/**
	* Set porcentNumDescarte
	*
	*Porcentaje de aves con descarte
	*
	* @parámetro String $porcentNumDescarte
	* @return PorcentNumDescarte
	*/
	public function setPorcentNumDescarte($porcentNumDescarte)
	{
	  $this->porcentNumDescarte = (String) $porcentNumDescarte;
	    return $this;
	}

	/**
	* Get porcentNumDescarte
	*
	* @return null|String
	*/
	public function getPorcentNumDescarte()
	{
		return $this->porcentNumDescarte;
	}

	/**
	* Set numColibacilosis
	*
	*Aves con colibacilosis
	*
	* @parámetro String $numColibacilosis
	* @return NumColibacilosis
	*/
	public function setNumColibacilosis($numColibacilosis)
	{
	  $this->numColibacilosis = (String) $numColibacilosis;
	    return $this;
	}

	/**
	* Get numColibacilosis
	*
	* @return null|String
	*/
	public function getNumColibacilosis()
	{
		return $this->numColibacilosis;
	}

	/**
	* Set porcentNumColibacilosis
	*
	*Porcentaje de aves con colibacilosis
	*
	* @parámetro String $porcentNumColibacilosis
	* @return PorcentNumColibacilosis
	*/
	public function setPorcentNumColibacilosis($porcentNumColibacilosis)
	{
	  $this->porcentNumColibacilosis = (String) $porcentNumColibacilosis;
	    return $this;
	}

	/**
	* Get porcentNumColibacilosis
	*
	* @return null|String
	*/
	public function getPorcentNumColibacilosis()
	{
		return $this->porcentNumColibacilosis;
	}

	/**
	* Set numPododermatitis
	*
	*Aves con podermatitis
	*
	* @parámetro Integer $numPododermatitis
	* @return NumPododermatitis
	*/
	public function setNumPododermatitis($numPododermatitis)
	{
	  $this->numPododermatitis = (Integer) $numPododermatitis;
	    return $this;
	}

	/**
	* Get numPododermatitis
	*
	* @return null|Integer
	*/
	public function getNumPododermatitis()
	{
		return $this->numPododermatitis;
	}

	/**
	* Set porcentNumPododermatitis
	*
	*Porcentaje de aves con pododermatitis
	*
	* @parámetro String $porcentNumPododermatitis
	* @return PorcentNumPododermatitis
	*/
	public function setPorcentNumPododermatitis($porcentNumPododermatitis)
	{
	  $this->porcentNumPododermatitis = (String) $porcentNumPododermatitis;
	    return $this;
	}

	/**
	* Get porcentNumPododermatitis
	*
	* @return null|String
	*/
	public function getPorcentNumPododermatitis()
	{
		return $this->porcentNumPododermatitis;
	}

	/**
	* Set numLesionesPiel
	*
	*Aves con lesiones de piel
	*
	* @parámetro String $numLesionesPiel
	* @return NumLesionesPiel
	*/
	public function setNumLesionesPiel($numLesionesPiel)
	{
	  $this->numLesionesPiel = (String) $numLesionesPiel;
	    return $this;
	}

	/**
	* Get numLesionesPiel
	*
	* @return null|String
	*/
	public function getNumLesionesPiel()
	{
		return $this->numLesionesPiel;
	}

	/**
	* Set porcentNumLesionesPiel
	*
	*Porcentaje de aves con lesiones de piel
	*
	* @parámetro String $porcentNumLesionesPiel
	* @return PorcentNumLesionesPiel
	*/
	public function setPorcentNumLesionesPiel($porcentNumLesionesPiel)
	{
	  $this->porcentNumLesionesPiel = (String) $porcentNumLesionesPiel;
	    return $this;
	}

	/**
	* Get porcentNumLesionesPiel
	*
	* @return null|String
	*/
	public function getPorcentNumLesionesPiel()
	{
		return $this->porcentNumLesionesPiel;
	}

	/**
	* Set numMalSangrado
	*
	*Aves con mal sangrado
	*
	* @parámetro String $numMalSangrado
	* @return NumMalSangrado
	*/
	public function setNumMalSangrado($numMalSangrado)
	{
	  $this->numMalSangrado = (String) $numMalSangrado;
	    return $this;
	}

	/**
	* Get numMalSangrado
	*
	* @return null|String
	*/
	public function getNumMalSangrado()
	{
		return $this->numMalSangrado;
	}

	/**
	* Set porcentNumMalSangrado
	*
	*Porcentaje de aves con mal sangrado
	*
	* @parámetro String $porcentNumMalSangrado
	* @return PorcentNumMalSangrado
	*/
	public function setPorcentNumMalSangrado($porcentNumMalSangrado)
	{
	  $this->porcentNumMalSangrado = (String) $porcentNumMalSangrado;
	    return $this;
	}

	/**
	* Get porcentNumMalSangrado
	*
	* @return null|String
	*/
	public function getPorcentNumMalSangrado()
	{
		return $this->porcentNumMalSangrado;
	}

	/**
	* Set numContusionPierna
	*
	*Aves con contusíon de pierna
	*
	* @parámetro String $numContusionPierna
	* @return NumContusionPierna
	*/
	public function setNumContusionPierna($numContusionPierna)
	{
	  $this->numContusionPierna = (String) $numContusionPierna;
	    return $this;
	}

	/**
	* Get numContusionPierna
	*
	* @return null|String
	*/
	public function getNumContusionPierna()
	{
		return $this->numContusionPierna;
	}

	/**
	* Set porcentNumContusionPierna
	*
	*porcentaje de aves con contusíon de pierna
	*
	* @parámetro String $porcentNumContusionPierna
	* @return PorcentNumContusionPierna
	*/
	public function setPorcentNumContusionPierna($porcentNumContusionPierna)
	{
	  $this->porcentNumContusionPierna = (String) $porcentNumContusionPierna;
	    return $this;
	}

	/**
	* Get porcentNumContusionPierna
	*
	* @return null|String
	*/
	public function getPorcentNumContusionPierna()
	{
		return $this->porcentNumContusionPierna;
	}

	/**
	* Set numContusionAla
	*
	*Aves con contusíon de ala
	*
	* @parámetro String $numContusionAla
	* @return NumContusionAla
	*/
	public function setNumContusionAla($numContusionAla)
	{
	  $this->numContusionAla = (String) $numContusionAla;
	    return $this;
	}

	/**
	* Get numContusionAla
	*
	* @return null|String
	*/
	public function getNumContusionAla()
	{
		return $this->numContusionAla;
	}

	/**
	* Set porcentNumContusionAla
	*
	*Porcentaje de aves con contusíon de ala
	*
	* @parámetro String $porcentNumContusionAla
	* @return PorcentNumContusionAla
	*/
	public function setPorcentNumContusionAla($porcentNumContusionAla)
	{
	  $this->porcentNumContusionAla = (String) $porcentNumContusionAla;
	    return $this;
	}

	/**
	* Get porcentNumContusionAla
	*
	* @return null|String
	*/
	public function getPorcentNumContusionAla()
	{
		return $this->porcentNumContusionAla;
	}

	/**
	* Set numContusionPechuga
	*
	*Aves con contusíon de pechuga
	*
	* @parámetro String $numContusionPechuga
	* @return NumContusionPechuga
	*/
	public function setNumContusionPechuga($numContusionPechuga)
	{
	  $this->numContusionPechuga = (String) $numContusionPechuga;
	    return $this;
	}

	/**
	* Get numContusionPechuga
	*
	* @return null|String
	*/
	public function getNumContusionPechuga()
	{
		return $this->numContusionPechuga;
	}

	/**
	* Set porcentNumContusionPechuga
	*
	*Porcentaje de aves con contusíon de pechuga
	*
	* @parámetro String $porcentNumContusionPechuga
	* @return PorcentNumContusionPechuga
	*/
	public function setPorcentNumContusionPechuga($porcentNumContusionPechuga)
	{
	  $this->porcentNumContusionPechuga = (String) $porcentNumContusionPechuga;
	    return $this;
	}

	/**
	* Get porcentNumContusionPechuga
	*
	* @return null|String
	*/
	public function getPorcentNumContusionPechuga()
	{
		return $this->porcentNumContusionPechuga;
	}

	/**
	* Set numAlasRotas
	*
	*Aves con alas rotas
	*
	* @parámetro String $numAlasRotas
	* @return NumAlasRotas
	*/
	public function setNumAlasRotas($numAlasRotas)
	{
	  $this->numAlasRotas = (String) $numAlasRotas;
	    return $this;
	}

	/**
	* Get numAlasRotas
	*
	* @return null|String
	*/
	public function getNumAlasRotas()
	{
		return $this->numAlasRotas;
	}

	/**
	* Set porcentNumAlasRotas
	*
	*Porcentaje de aves con alas rotas
	*
	* @parámetro String $porcentNumAlasRotas
	* @return PorcentNumAlasRotas
	*/
	public function setPorcentNumAlasRotas($porcentNumAlasRotas)
	{
	  $this->porcentNumAlasRotas = (String) $porcentNumAlasRotas;
	    return $this;
	}

	/**
	* Get porcentNumAlasRotas
	*
	* @return null|String
	*/
	public function getPorcentNumAlasRotas()
	{
		return $this->porcentNumAlasRotas;
	}

	/**
	* Set numPiernasRotas
	*
	*Aves con piernas rotas
	*
	* @parámetro String $numPiernasRotas
	* @return NumPiernasRotas
	*/
	public function setNumPiernasRotas($numPiernasRotas)
	{
	  $this->numPiernasRotas = (String) $numPiernasRotas;
	    return $this;
	}

	/**
	* Get numPiernasRotas
	*
	* @return null|String
	*/
	public function getNumPiernasRotas()
	{
		return $this->numPiernasRotas;
	}

	/**
	* Set porcentNumPiernasRotas
	*
	*Porcentaje de aves con piernas rotas
	*
	* @parámetro String $porcentNumPiernasRotas
	* @return PorcentNumPiernasRotas
	*/
	public function setPorcentNumPiernasRotas($porcentNumPiernasRotas)
	{
	  $this->porcentNumPiernasRotas = (String) $porcentNumPiernasRotas;
	    return $this;
	}

	/**
	* Get porcentNumPiernasRotas
	*
	* @return null|String
	*/
	public function getPorcentNumPiernasRotas()
	{
		return $this->porcentNumPiernasRotas;
	}

	/**
	* Set totalCanalesAprobados
	*
	*Total de canales aprobados
	*
	* @parámetro Integer $totalCanalesAprobados
	* @return TotalCanalesAprobados
	*/
	public function setTotalCanalesAprobados($totalCanalesAprobados)
	{
	  $this->totalCanalesAprobados = (Integer) $totalCanalesAprobados;
	    return $this;
	}

	/**
	* Get totalCanalesAprobados
	*
	* @return null|Integer
	*/
	public function getTotalCanalesAprobados()
	{
		return $this->totalCanalesAprobados;
	}

	/**
	* Set pesoTotalCanalesAprobadosTotalmente
	*
	*Peso total de canles aprobados
	*
	* @parámetro String $pesoTotalCanalesAprobadosTotalmente
	* @return PesoTotalCanalesAprobadosTotalmente
	*/
	public function setPesoTotalCanalesAprobadosTotalmente($pesoTotalCanalesAprobadosTotalmente)
	{
	  $this->pesoTotalCanalesAprobadosTotalmente = (String) $pesoTotalCanalesAprobadosTotalmente;
	    return $this;
	}

	/**
	* Get pesoTotalCanalesAprobadosTotalmente
	*
	* @return null|String
	*/
	public function getPesoTotalCanalesAprobadosTotalmente()
	{
		return $this->pesoTotalCanalesAprobadosTotalmente;
	}

	/**
	* Set totalCanalesAprobadosParcialmente
	*
	*Total canales aprobados parcialmente
	*
	* @parámetro Integer $totalCanalesAprobadosParcialmente
	* @return TotalCanalesAprobadosParcialmente
	*/
	public function setTotalCanalesAprobadosParcialmente($totalCanalesAprobadosParcialmente)
	{
	  $this->totalCanalesAprobadosParcialmente = (Integer) $totalCanalesAprobadosParcialmente;
	    return $this;
	}

	/**
	* Get totalCanalesAprobadosParcialmente
	*
	* @return null|Integer
	*/
	public function getTotalCanalesAprobadosParcialmente()
	{
		return $this->totalCanalesAprobadosParcialmente;
	}

	/**
	* Set pesoTotalCanalesAprobadosParcialmente
	*
	*Peso total canales aprobados parcialmente
	*
	* @parámetro String $pesoTotalCanalesAprobadosParcialmente
	* @return PesoTotalCanalesAprobadosParcialmente
	*/
	public function setPesoTotalCanalesAprobadosParcialmente($pesoTotalCanalesAprobadosParcialmente)
	{
	  $this->pesoTotalCanalesAprobadosParcialmente = (String) $pesoTotalCanalesAprobadosParcialmente;
	    return $this;
	}

	/**
	* Get pesoTotalCanalesAprobadosParcialmente
	*
	* @return null|String
	*/
	public function getPesoTotalCanalesAprobadosParcialmente()
	{
		return $this->pesoTotalCanalesAprobadosParcialmente;
	}

	/**
	* Set canalesDecomisoParcial
	*
	*Canales decomiso parcial
	*
	* @parámetro Integer $canalesDecomisoParcial
	* @return CanalesDecomisoParcial
	*/
	public function setCanalesDecomisoParcial($canalesDecomisoParcial)
	{
	  $this->canalesDecomisoParcial = (Integer) $canalesDecomisoParcial;
	    return $this;
	}

	/**
	* Get canalesDecomisoParcial
	*
	* @return null|Integer
	*/
	public function getCanalesDecomisoParcial()
	{
		return $this->canalesDecomisoParcial;
	}

	/**
	 * Set canalesDecomisoTotal
	 *
	 *Canales decomiso total
	 *
	 * @parámetro Integer $canalesDecomisoTotal
	 * @return CanalesDecomisoTotal
	 */
	public function setCanalesDecomisoTotal($canalesDecomisoTotal)
	{
		$this->canalesDecomisoTotal = (Integer) $canalesDecomisoTotal;
		return $this;
	}
	
	/**
	 * Get canalesDecomisoTotal
	 *
	 * @return null|Integer
	 */
	public function getCanalesDecomisoTotal()
	{
		return $this->canalesDecomisoTotal;
	}
	/**
	* Set pesoPromedioCanales
	*
	*Peso promedio canales
	*
	* @parámetro String $pesoPromedioCanales
	* @return PesoPromedioCanales
	*/
	public function setPesoPromedioCanales($pesoPromedioCanales)
	{
	  $this->pesoPromedioCanales = (String) $pesoPromedioCanales;
	    return $this;
	}

	/**
	* Get pesoPromedioCanales
	*
	* @return null|String
	*/
	public function getPesoPromedioCanales()
	{
		return $this->pesoPromedioCanales;
	}

	/**
	* Set totalCarneDecomisada
	*
	*Total carne decomisada
	*
	* @parámetro Integer $totalCarneDecomisada
	* @return TotalCarneDecomisada
	*/
	public function setTotalCarneDecomisada($totalCarneDecomisada)
	{
	  $this->totalCarneDecomisada = (Integer) $totalCarneDecomisada;
	    return $this;
	}

	/**
	* Get totalCarneDecomisada
	*
	* @return null|Integer
	*/
	public function getTotalCarneDecomisada()
	{
		return $this->totalCarneDecomisada;
	}

	/**
	* Set destinoDecomisos
	*
	*Destino decomisos
	*
	* @parámetro String $destinoDecomisos
	* @return DestinoDecomisos
	*/
	public function setDestinoDecomisos($destinoDecomisos)
	{
	  $this->destinoDecomisos = (String) $destinoDecomisos;
	    return $this;
	}

	/**
	* Get destinoDecomisos
	*
	* @return null|String
	*/
	public function getDestinoDecomisos()
	{
		return $this->destinoDecomisos;
	}

	/**
	* Set lugarDisposicionFinal
	*
	*Lugar disposicion final
	*
	* @parámetro String $lugarDisposicionFinal
	* @return LugarDisposicionFinal
	*/
	public function setLugarDisposicionFinal($lugarDisposicionFinal)
	{
	  $this->lugarDisposicionFinal = (String) $lugarDisposicionFinal;
	    return $this;
	}

	/**
	* Get lugarDisposicionFinal
	*
	* @return null|String
	*/
	public function getLugarDisposicionFinal()
	{
		return $this->lugarDisposicionFinal;
	}

	/**
	* Set observacion
	*
	*Observacion del registro
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
	* Set fechaCreacion
	*
	*
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
	* @return DetallePostAvesModelo
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
