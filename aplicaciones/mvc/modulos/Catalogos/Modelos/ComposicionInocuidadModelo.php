<?php
/**
 * Modelo ComposicionInocuidadModelo
 *
 * Este archivo se complementa con el archivo   ComposicionInocuidadLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-23
 * @uses    ComposicionInocuidadModelo
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ComposicionInocuidadModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idComposicion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idProducto;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idIngredienteActivo;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $concentracion;

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
    protected $unidadMedida;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Clasificación del tipo de composición del producto:
     *      - Ingrediente activo
     *      - Activo de importancia toxicológica
     */
    protected $tipoComposicion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del tipo de componente
     */
    protected $idTipoComponente;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre del tipo de componente
     */
    protected $tipoComponente;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Nombre de la unidad de medida cuando no se disponga de una unidad en el catálogo
     */
    protected $nombreUnidadMedida;

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
     * Nombre de la tabla: composicion_inocuidad
     */
    private $tabla = "composicion_inocuidad";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_composicion";

    /**
     * Secuencia
     */
    private $secuencial = 'g_catalogos"."composicion_inocuidad_id_composicion_seq';

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
            throw new \Exception('Clase Modelo: ComposicionInocuidadModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: ComposicionInocuidadModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idIngredienteActivo
     *
     *
     *
     * @parámetro Integer $idIngredienteActivo
     * @return IdIngredienteActivo
     */
    public function setIdIngredienteActivo($idIngredienteActivo)
    {
        $this->idIngredienteActivo = (integer) $idIngredienteActivo;
        return $this;
    }

    /**
     * Get idIngredienteActivo
     *
     * @return null|Integer
     */
    public function getIdIngredienteActivo()
    {
        return $this->idIngredienteActivo;
    }

    /**
     * Set concentracion
     *
     *
     *
     * @parámetro String $concentracion
     * @return Concentracion
     */
    public function setConcentracion($concentracion)
    {
        $this->concentracion = (string) $concentracion;
        return $this;
    }

    /**
     * Get concentracion
     *
     * @return null|String
     */
    public function getConcentracion()
    {
        return $this->concentracion;
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
     * Set unidadMedida
     *
     *
     *
     * @parámetro String $unidadMedida
     * @return UnidadMedida
     */
    public function setUnidadMedida($unidadMedida)
    {
        $this->unidadMedida = (string) $unidadMedida;
        return $this;
    }

    /**
     * Get unidadMedida
     *
     * @return null|String
     */
    public function getUnidadMedida()
    {
        return $this->unidadMedida;
    }

    /**
     * Set tipoComposicion
     *
     * Clasificación del tipo de composición del producto:
     * - Ingrediente activo
     * - Activo de importancia toxicológica
     *
     * @parámetro String $tipoComposicion
     * @return TipoComposicion
     */
    public function setTipoComposicion($tipoComposicion)
    {
        $this->tipoComposicion = (string) $tipoComposicion;
        return $this;
    }

    /**
     * Get tipoComposicion
     *
     * @return null|String
     */
    public function getTipoComposicion()
    {
        return $this->tipoComposicion;
    }

    /**
     * Set idTipoComponente
     *
     * Identificador del tipo de componente
     *
     * @parámetro Integer $idTipoComponente
     * @return IdTipoComponente
     */
    public function setIdTipoComponente($idTipoComponente)
    {
        $this->idTipoComponente = (integer) $idTipoComponente;
        return $this;
    }

    /**
     * Get idTipoComponente
     *
     * @return null|Integer
     */
    public function getIdTipoComponente()
    {
        return $this->idTipoComponente;
    }

    /**
     * Set tipoComponente
     *
     * Nombre del tipo de componente
     *
     * @parámetro String $tipoComponente
     * @return TipoComponente
     */
    public function setTipoComponente($tipoComponente)
    {
        $this->tipoComponente = (string) $tipoComponente;
        return $this;
    }

    /**
     * Get tipoComponente
     *
     * @return null|String
     */
    public function getTipoComponente()
    {
        return $this->tipoComponente;
    }

    /**
     * Set nombreUnidadMedida
     *
     * Nombre de la unidad de medida cuando no se disponga de una unidad en el catálogo
     *
     * @parámetro String $nombreUnidadMedida
     * @return NombreUnidadMedida
     */
    public function setNombreUnidadMedida($nombreUnidadMedida)
    {
        $this->nombreUnidadMedida = (string) $nombreUnidadMedida;
        return $this;
    }

    /**
     * Get nombreUnidadMedida
     *
     * @return null|String
     */
    public function getNombreUnidadMedida()
    {
        return $this->nombreUnidadMedida;
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        return parent::guardar($datos);
    }

    /**
     * Actualiza un registro actual
     *
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
     * @return ComposicionInocuidadModelo
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
