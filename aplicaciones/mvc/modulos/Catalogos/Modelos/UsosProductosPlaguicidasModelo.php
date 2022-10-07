<?php
/**
 * Modelo UsosProductosPlaguicidasModelo
 *
 * Este archivo se complementa con el archivo   UsosProductosPlaguicidasLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-13
 * @uses    UsosProductosPlaguicidasModelo
 * @package Catalogos
 * @subpackage Modelos
 */

namespace Agrodb\Catalogos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class UsosProductosPlaguicidasModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificador único del registro
     */
    protected $idUso;
    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * Fcha de creación del registro
     */
    protected $fechaCreacion;
    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificador del producto
     */
    protected $idProducto;
    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificador del registro de usos
     */
    protected $idPlaga;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre común del uso
     */
    protected $plagaNombreComun;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre científico del uso
     */
    protected $plagaNombreCientifico;
    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificador del registro de cultipo
     */
    protected $idCultivo;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre común del cultivo
     */
    protected $cultivoNombreComun;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre cientifico del cultivo
     */
    protected $cultivoNombreCientifico;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Información de la dosis del producto
     */
    protected $dosis;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Unidad de la dosis del producto
     */
    protected $unidadDosis;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Período de carencia del producto
     */
    protected $periodoCarencia;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Información del gasto de agua
     */
    protected $gastoAgua;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Unidad del gasto de agua
     */
    protected $unidadGastoAgua;

    /**
     * Campos del formulario
     * @var array
     */
    private $campos = array();

    /**
     * Nombre del esquema
     *
     */
    private $esquema = "g_catalogos";

    /**
     * Nombre de la tabla: usos_productos_plaguicidas
     *
     */
    private $tabla = "usos_productos_plaguicidas";

    /**
     *Clave primaria
     */
    private $clavePrimaria = "id_uso";


    /**
     *Secuencia
     */
    private $secuencial = 'g_catalogos"."usos_productos_plaguicidas_id_uso_seq';


    /**
     * Constructor
     * $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
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
        if (!method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: UsosProductosPlaguicidasModelo. Propiedad especificada invalida: set' . $name);
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
        if (!method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: UsosProductosPlaguicidasModelo. Propiedad especificada invalida: get' . $name);
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
     * @return null|
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idUso
     *
     *Identificador único del registro
     *
     * @parámetro Integer $idUso
     * @return IdUso
     */
    public function setIdUso($idUso)
    {
        $this->idUso = (integer)$idUso;
        return $this;
    }

    /**
     * Get idUso
     *
     * @return null|Integer
     */
    public function getIdUso()
    {
        return $this->idUso;
    }

    /**
     * Set fechaCreacion
     *
     *Fcha de creación del registro
     *
     * @parámetro Date $fechaCreacion
     * @return FechaCreacion
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = (string)$fechaCreacion;
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
     * Set idProducto
     *
     *Identificador del producto
     *
     * @parámetro Integer $idProducto
     * @return IdProducto
     */
    public function setIdProducto($idProducto)
    {
        $this->idProducto = (integer)$idProducto;
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
     * Set idPlaga
     *
     *Identificador del registro de usos
     *
     * @parámetro Integer $idPlaga
     * @return IdPlaga
     */
    public function setIdPlaga($idPlaga)
    {
        $this->idPlaga = (integer)$idPlaga;
        return $this;
    }

    /**
     * Get idPlaga
     *
     * @return null|Integer
     */
    public function getIdPlaga()
    {
        return $this->idPlaga;
    }

    /**
     * Set plagaNombreComun
     *
     *Nombre común del uso
     *
     * @parámetro String $plagaNombreComun
     * @return PlagaNombreComun
     */
    public function setPlagaNombreComun($plagaNombreComun)
    {
        $this->plagaNombreComun = (string)$plagaNombreComun;
        return $this;
    }

    /**
     * Get plagaNombreComun
     *
     * @return null|String
     */
    public function getPlagaNombreComun()
    {
        return $this->plagaNombreComun;
    }

    /**
     * Set plagaNombreCientifico
     *
     *Nombre científico del uso
     *
     * @parámetro String $plagaNombreCientifico
     * @return PlagaNombreCientifico
     */
    public function setPlagaNombreCientifico($plagaNombreCientifico)
    {
        $this->plagaNombreCientifico = (string)$plagaNombreCientifico;
        return $this;
    }

    /**
     * Get plagaNombreCientifico
     *
     * @return null|String
     */
    public function getPlagaNombreCientifico()
    {
        return $this->plagaNombreCientifico;
    }

    /**
     * Set idCultivo
     *
     *Identificador del registro de cultipo
     *
     * @parámetro Integer $idCultivo
     * @return IdCultivo
     */
    public function setIdCultivo($idCultivo)
    {
        $this->idCultivo = (integer)$idCultivo;
        return $this;
    }

    /**
     * Get idCultivo
     *
     * @return null|Integer
     */
    public function getIdCultivo()
    {
        return $this->idCultivo;
    }

    /**
     * Set cultivoNombreComun
     *
     *Nombre común del cultivo
     *
     * @parámetro String $cultivoNombreComun
     * @return CultivoNombreComun
     */
    public function setCultivoNombreComun($cultivoNombreComun)
    {
        $this->cultivoNombreComun = (string)$cultivoNombreComun;
        return $this;
    }

    /**
     * Get cultivoNombreComun
     *
     * @return null|String
     */
    public function getCultivoNombreComun()
    {
        return $this->cultivoNombreComun;
    }

    /**
     * Set cultivoNombreCientifico
     *
     *Nombre cientifico del cultivo
     *
     * @parámetro String $cultivoNombreCientifico
     * @return CultivoNombreCientifico
     */
    public function setCultivoNombreCientifico($cultivoNombreCientifico)
    {
        $this->cultivoNombreCientifico = (string)$cultivoNombreCientifico;
        return $this;
    }

    /**
     * Get cultivoNombreCientifico
     *
     * @return null|String
     */
    public function getCultivoNombreCientifico()
    {
        return $this->cultivoNombreCientifico;
    }

    /**
     * Set dosis
     *
     *Información de la dosis del producto
     *
     * @parámetro String $dosis
     * @return Dosis
     */
    public function setDosis($dosis)
    {
        $this->dosis = (string)$dosis;
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
     * Set unidadDosis
     *
     *Unidad de la dosis del producto
     *
     * @parámetro String $unidadDosis
     * @return UnidadDosis
     */
    public function setUnidadDosis($unidadDosis)
    {
        $this->unidadDosis = (string)$unidadDosis;
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
     * Set periodoCarencia
     *
     *Período de carencia del producto
     *
     * @parámetro String $periodoCarencia
     * @return PeriodoCarencia
     */
    public function setPeriodoCarencia($periodoCarencia)
    {
        $this->periodoCarencia = (string)$periodoCarencia;
        return $this;
    }

    /**
     * Get periodoCarencia
     *
     * @return null|String
     */
    public function getPeriodoCarencia()
    {
        return $this->periodoCarencia;
    }

    /**
     * Set gastoAgua
     *
     *Información del gasto de agua
     *
     * @parámetro String $gastoAgua
     * @return GastoAgua
     */
    public function setGastoAgua($gastoAgua)
    {
        $this->gastoAgua = (string)$gastoAgua;
        return $this;
    }

    /**
     * Get gastoAgua
     *
     * @return null|String
     */
    public function getGastoAgua()
    {
        return $this->gastoAgua;
    }

    /**
     * Set unidadGastoAgua
     *
     *Unidad del gasto de agua
     *
     * @parámetro String $unidadGastoAgua
     * @return UnidadGastoAgua
     */
    public function setUnidadGastoAgua($unidadGastoAgua)
    {
        $this->unidadGastoAgua = (string)$unidadGastoAgua;
        return $this;
    }

    /**
     * Get unidadGastoAgua
     *
     * @return null|String
     */
    public function getUnidadGastoAgua()
    {
        return $this->unidadGastoAgua;
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(array $datos)
    {
        return parent::guardar($datos);
    }

    /**
     * Actualiza un registro actual
     * @param array $datos
     * @param int $id
     * @return int
     */
    public function actualizar(array $datos, $id)
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
     * @param int $id
     * @return UsosProductosPlaguicidasModelo
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
