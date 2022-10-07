<?php
/**
 * Modelo ProductosInocuidadModelo
 *
 * Este archivo se complementa con el archivo   ProductosInocuidadLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    ProductosInocuidadModelo
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ProductosInocuidadModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idProducto;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $composicion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $formulacion;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fechaCreacion;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fechaModificacion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idFormulacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $numeroRegistro;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $dosis;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $periodoCarenciaRetiro;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $periodoReingreso;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $observacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $unidadDosis;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idCategoriaToxicologica;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $categoriaToxicologica;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fechaRegistro;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idOperador;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fechaVencimiento;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fechaRevaluacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $ingredienteActivo;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $declaracionVenta;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idComposicion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $viaAdministracion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $codigoSecuencial;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idPais;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idFabricante;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $estado;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Razón social de la empresa que registra el producto
     */
    protected $razonSocial;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Información de estabilidad del producto plaguicida
     */
    protected $estabilidad;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la declaración de venta
     */
    protected $idDeclaracionVenta;
	
	/**
		* @var String
		* Campo requerido
		* Campo visible en el formulario
		* Ruta del archivo de etiquetas generado en el modulo de modificacion de producto
		*/
		protected $rutaEtiqueta;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_catalogos";

    /**
     * Nombre de la tabla: productos_inocuidad
     */
    private $tabla = "productos_inocuidad";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_producto";

    /**
     * Secuencia
     */
    private $secuencial = 'g_catalogos"."productos_inocuidad_id_producto_seq';

    /**
     * Constructor
     * $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
     *
     * @parámetro  array|null $datos
     * @retorna void
     */
    public function __construct(array $datos = null)
    {
        if (is_array($datos)) {
            $this->setOptions($datos);
        }
        $features = new \Zend\Db\TableGateway\Feature\SequenceFeature($this->clavePrimaria, $this->secuencial);
        parent::__construct($this->esquema, $this->tabla, $features);
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
        if (! method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: ProductosInocuidadModelo. Propiedad especificada invalida: set' . $name);
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
        if (! method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: ProductosInocuidadModelo. Propiedad especificada invalida: get' . $name);
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
        foreach ($datos as $key => $value) {
            $key_original = $key;
            if (strpos($key, '_') > 0) {
                $aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function ($string) {
                    return ucfirst($string[1]);
                }, ucwords($key));
                $key = $aux;
            }
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
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
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idProducto
     *
     *
     *
     * @parámetro Integer $idProducto
     * @return IdProducto
     */
    public function setIdProducto($idProducto)
    {
        $this->idProducto = (integer) $idProducto;
        return $this;
    }

    /**
     * Get idProducto
     *
     * @return null|Integer
     */
    public function getIdProducto()
    {
        return $this->idProducto;
    }

    /**
     * Set composicion
     *
     *
     *
     * @parámetro String $composicion
     * @return Composicion
     */
    public function setComposicion($composicion)
    {
        $this->composicion = (string) $composicion;
        return $this;
    }

    /**
     * Get composicion
     *
     * @return null|String
     */
    public function getComposicion()
    {
        return $this->composicion;
    }

    /**
     * Set formulacion
     *
     *
     *
     * @parámetro String $formulacion
     * @return Formulacion
     */
    public function setFormulacion($formulacion)
    {
        $this->formulacion = (string) $formulacion;
        return $this;
    }

    /**
     * Get formulacion
     *
     * @return null|String
     */
    public function getFormulacion()
    {
        return $this->formulacion;
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
        $this->fechaCreacion = (string) $fechaCreacion;
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
     * Set fechaModificacion
     *
     *
     *
     * @parámetro Date $fechaModificacion
     * @return FechaModificacion
     */
    public function setFechaModificacion($fechaModificacion)
    {
        $this->fechaModificacion = (string) $fechaModificacion;
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
     * Set idFormulacion
     *
     *
     *
     * @parámetro Integer $idFormulacion
     * @return IdFormulacion
     */
    public function setIdFormulacion($idFormulacion)
    {
        $this->idFormulacion = (integer) $idFormulacion;
        return $this;
    }

    /**
     * Get idFormulacion
     *
     * @return null|Integer
     */
    public function getIdFormulacion()
    {
        return $this->idFormulacion;
    }

    /**
     * Set numeroRegistro
     *
     *
     *
     * @parámetro String $numeroRegistro
     * @return NumeroRegistro
     */
    public function setNumeroRegistro($numeroRegistro)
    {
        $this->numeroRegistro = (string) $numeroRegistro;
        return $this;
    }

    /**
     * Get numeroRegistro
     *
     * @return null|String
     */
    public function getNumeroRegistro()
    {
        return $this->numeroRegistro;
    }

    /**
     * Set dosis
     *
     *
     *
     * @parámetro String $dosis
     * @return Dosis
     */
    public function setDosis($dosis)
    {
        $this->dosis = (string) $dosis;
        return $this;
    }

    /**
     * Get dosis
     *
     * @return null|String
     */
    public function getDosis()
    {
        return $this->dosis;
    }

    /**
     * Set periodoCarenciaRetiro
     *
     *
     *
     * @parámetro String $periodoCarenciaRetiro
     * @return PeriodoCarenciaRetiro
     */
    public function setPeriodoCarenciaRetiro($periodoCarenciaRetiro)
    {
        $this->periodoCarenciaRetiro = (string) $periodoCarenciaRetiro;
        return $this;
    }

    /**
     * Get periodoCarenciaRetiro
     *
     * @return null|String
     */
    public function getPeriodoCarenciaRetiro()
    {
        return $this->periodoCarenciaRetiro;
    }

    /**
     * Set periodoReingreso
     *
     *
     *
     * @parámetro String $periodoReingreso
     * @return PeriodoReingreso
     */
    public function setPeriodoReingreso($periodoReingreso)
    {
        $this->periodoReingreso = (string) $periodoReingreso;
        return $this;
    }

    /**
     * Get periodoReingreso
     *
     * @return null|String
     */
    public function getPeriodoReingreso()
    {
        return $this->periodoReingreso;
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
        $this->observacion = (string) $observacion;
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
     * Set unidadDosis
     *
     *
     *
     * @parámetro String $unidadDosis
     * @return UnidadDosis
     */
    public function setUnidadDosis($unidadDosis)
    {
        $this->unidadDosis = (string) $unidadDosis;
        return $this;
    }

    /**
     * Get unidadDosis
     *
     * @return null|String
     */
    public function getUnidadDosis()
    {
        return $this->unidadDosis;
    }

    /**
     * Set idCategoriaToxicologica
     *
     *
     *
     * @parámetro Integer $idCategoriaToxicologica
     * @return IdCategoriaToxicologica
     */
    public function setIdCategoriaToxicologica($idCategoriaToxicologica)
    {
        $this->idCategoriaToxicologica = (integer) $idCategoriaToxicologica;
        return $this;
    }

    /**
     * Get idCategoriaToxicologica
     *
     * @return null|Integer
     */
    public function getIdCategoriaToxicologica()
    {
        return $this->idCategoriaToxicologica;
    }

    /**
     * Set categoriaToxicologica
     *
     *
     *
     * @parámetro String $categoriaToxicologica
     * @return CategoriaToxicologica
     */
    public function setCategoriaToxicologica($categoriaToxicologica)
    {
        $this->categoriaToxicologica = (string) $categoriaToxicologica;
        return $this;
    }

    /**
     * Get categoriaToxicologica
     *
     * @return null|String
     */
    public function getCategoriaToxicologica()
    {
        return $this->categoriaToxicologica;
    }

    /**
     * Set fechaRegistro
     *
     *
     *
     * @parámetro Date $fechaRegistro
     * @return FechaRegistro
     */
    public function setFechaRegistro($fechaRegistro)
    {
        $this->fechaRegistro = (string) $fechaRegistro;
        return $this;
    }

    /**
     * Get fechaRegistro
     *
     * @return null|Date
     */
    public function getFechaRegistro()
    {
        return $this->fechaRegistro;
    }

    /**
     * Set idOperador
     *
     *
     *
     * @parámetro String $idOperador
     * @return IdOperador
     */
    public function setIdOperador($idOperador)
    {
        $this->idOperador = (string) $idOperador;
        return $this;
    }

    /**
     * Get idOperador
     *
     * @return null|String
     */
    public function getIdOperador()
    {
        return $this->idOperador;
    }

    /**
     * Set fechaVencimiento
     *
     *
     *
     * @parámetro Date $fechaVencimiento
     * @return FechaVencimiento
     */
    public function setFechaVencimiento($fechaVencimiento)
    {
        $this->fechaVencimiento = (string) $fechaVencimiento;
        return $this;
    }

    /**
     * Get fechaVencimiento
     *
     * @return null|Date
     */
    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    /**
     * Set fechaRevaluacion
     *
     *
     *
     * @parámetro Date $fechaRevaluacion
     * @return FechaRevaluacion
     */
    public function setFechaRevaluacion($fechaRevaluacion)
    {
        $this->fechaRevaluacion = (string) $fechaRevaluacion;
        return $this;
    }

    /**
     * Get fechaRevaluacion
     *
     * @return null|Date
     */
    public function getFechaRevaluacion()
    {
        return $this->fechaRevaluacion;
    }

    /**
     * Set ingredienteActivo
     *
     *
     *
     * @parámetro String $ingredienteActivo
     * @return IngredienteActivo
     */
    public function setIngredienteActivo($ingredienteActivo)
    {
        $this->ingredienteActivo = (string) $ingredienteActivo;
        return $this;
    }

    /**
     * Get ingredienteActivo
     *
     * @return null|String
     */
    public function getIngredienteActivo()
    {
        return $this->ingredienteActivo;
    }

    /**
     * Set declaracionVenta
     *
     *
     *
     * @parámetro String $declaracionVenta
     * @return DeclaracionVenta
     */
    public function setDeclaracionVenta($declaracionVenta)
    {
        $this->declaracionVenta = (string) $declaracionVenta;
        return $this;
    }

    /**
     * Get declaracionVenta
     *
     * @return null|String
     */
    public function getDeclaracionVenta()
    {
        return $this->declaracionVenta;
    }

    /**
     * Set idComposicion
     *
     *
     *
     * @parámetro Integer $idComposicion
     * @return IdComposicion
     */
    public function setIdComposicion($idComposicion)
    {
        $this->idComposicion = (integer) $idComposicion;
        return $this;
    }

    /**
     * Get idComposicion
     *
     * @return null|Integer
     */
    public function getIdComposicion()
    {
        return $this->idComposicion;
    }

    /**
     * Set viaAdministracion
     *
     *
     *
     * @parámetro String $viaAdministracion
     * @return ViaAdministracion
     */
    public function setViaAdministracion($viaAdministracion)
    {
        $this->viaAdministracion = (string) $viaAdministracion;
        return $this;
    }

    /**
     * Get viaAdministracion
     *
     * @return null|String
     */
    public function getViaAdministracion()
    {
        return $this->viaAdministracion;
    }

    /**
     * Set codigoSecuencial
     *
     *
     *
     * @parámetro Integer $codigoSecuencial
     * @return CodigoSecuencial
     */
    public function setCodigoSecuencial($codigoSecuencial)
    {
        $this->codigoSecuencial = (integer) $codigoSecuencial;
        return $this;
    }

    /**
     * Get codigoSecuencial
     *
     * @return null|Integer
     */
    public function getCodigoSecuencial()
    {
        return $this->codigoSecuencial;
    }

    /**
     * Set idPais
     *
     *
     *
     * @parámetro Integer $idPais
     * @return IdPais
     */
    public function setIdPais($idPais)
    {
        $this->idPais = (integer) $idPais;
        return $this;
    }

    /**
     * Get idPais
     *
     * @return null|Integer
     */
    public function getIdPais()
    {
        return $this->idPais;
    }

    /**
     * Set idFabricante
     *
     *
     *
     * @parámetro Integer $idFabricante
     * @return IdFabricante
     */
    public function setIdFabricante($idFabricante)
    {
        $this->idFabricante = (integer) $idFabricante;
        return $this;
    }

    /**
     * Get idFabricante
     *
     * @return null|Integer
     */
    public function getIdFabricante()
    {
        return $this->idFabricante;
    }

    /**
     * Set estado
     *
     *
     *
     * @parámetro String $estado
     * @return Estado
     */
    public function setEstado($estado)
    {
        $this->estado = (string) $estado;
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
     * Set razonSocial
     *
     * Razón social de la empresa que registra el producto
     *
     * @parámetro String $razonSocial
     * @return RazonSocial
     */
    public function setRazonSocial($razonSocial)
    {
        $this->razonSocial = (string) $razonSocial;
        return $this;
    }

    /**
     * Get razonSocial
     *
     * @return null|String
     */
    public function getRazonSocial()
    {
        return $this->razonSocial;
    }

    /**
     * Set estabilidad
     *
     * Información de estabilidad del producto plaguicida
     *
     * @parámetro String $estabilidad
     * @return Estabilidad
     */
    public function setEstabilidad($estabilidad)
    {
        $this->estabilidad = (string) $estabilidad;
        return $this;
    }

    /**
     * Get estabilidad
     *
     * @return null|String
     */
    public function getEstabilidad()
    {
        return $this->estabilidad;
    }

    /**
     * Set idDeclaracionVenta
     *
     * Identificador de la declaración de venta
     *
     * @parámetro Integer $idDeclaracionVenta
     * @return IdDeclaracionVenta
     */
    public function setIdDeclaracionVenta($idDeclaracionVenta)
    {
        $this->idDeclaracionVenta = (integer) $idDeclaracionVenta;
        return $this;
    }

    /**
     * Get idDeclaracionVenta
     *
     * @return null|Integer
     */
    public function getIdDeclaracionVenta()
    {
        return $this->idDeclaracionVenta;
    }
	
	/**
	* Set rutaEtiqueta
	*
	*Ruta del archivo de etiquetas generado en el modulo de modificacion de producto
	*
	* @parámetro String $rutaEtiqueta
	* @return RutaEtiqueta
	*/
	public function setRutaEtiqueta($rutaEtiqueta)
	{
	  $this->rutaEtiqueta = (String) $rutaEtiqueta;
	    return $this;
	}

	/**
	* Get rutaEtiqueta
	*
	* @return null|String
	*/
	public function getRutaEtiqueta()
	{
		return $this->rutaEtiqueta;
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
    public function actualizar(Array $datos, $id)
    {
        return parent::actualizar($datos, $this->clavePrimaria . " = " . $id);
    }

    /**
     * Borra el registro actual
     *
     * @param
     *            string Where|array $where
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
     * @param int $id
     * @return ProductosInocuidadModelo
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
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
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
