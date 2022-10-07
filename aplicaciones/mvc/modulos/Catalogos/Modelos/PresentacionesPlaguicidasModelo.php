<?php
/**
 * Modelo PresentacionesPlaguicidasModelo
 *
 * Este archivo se complementa con el archivo   PresentacionesPlaguicidasLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-21
 * @uses    PresentacionesPlaguicidasModelo
 * @package Catalogos
 * @subpackage Modelos
 */

namespace Agrodb\Catalogos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class PresentacionesPlaguicidasModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificador único del registro
     */
    protected $idPresentacion;
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
     * Identificador del registro de código complementario y suplementario
     */
    protected $idCodigoCompSupl;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre de la presentación del producto
     */
    protected $presentacion;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Código de la presentación del producto
     */
    protected $codigoPresentacion;
    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificador de la unidad de medida de la presentación
     */
    protected $idUnidad;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre de la unidad de medida de la presentación del producto
     */
    protected $unidad;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Estado del registro
     */
    protected $estado;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Identificador del usuario que modifica el registro
     */
    protected $identificadorModificacion;
    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * Fecha de modificación del registro
     */
    protected $fechaModificacion;

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
     * Nombre de la tabla: presentaciones_plaguicidas
     *
     */
    private $tabla = "presentaciones_plaguicidas";

    /**
     *Clave primaria
     */
    private $clavePrimaria = "id_presentacion";


    /**
     *Secuencia
     */
    private $secuencial = 'g_catalogos"."presentaciones_plaguicidas_id_presentacion_seq';


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
            throw new \Exception('Clase Modelo: PresentacionesPlaguicidasModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: PresentacionesPlaguicidasModelo. Propiedad especificada invalida: get' . $name);
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
     * Set idPresentacion
     *
     *Identificador único del registro
     *
     * @parámetro Integer $idPresentacion
     * @return IdPresentacion
     */
    public function setIdPresentacion($idPresentacion)
    {
        $this->idPresentacion = (integer)$idPresentacion;
        return $this;
    }

    /**
     * Get idPresentacion
     *
     * @return null|Integer
     */
    public function getIdPresentacion()
    {
        return $this->idPresentacion;
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
     * Set idCodigoCompSupl
     *
     *Identificador del registro de código complementario y suplementario
     *
     * @parámetro Integer $idCodigoCompSupl
     * @return IdCodigoCompSupl
     */
    public function setIdCodigoCompSupl($idCodigoCompSupl)
    {
        $this->idCodigoCompSupl = (integer)$idCodigoCompSupl;
        return $this;
    }

    /**
     * Get idCodigoCompSupl
     *
     * @return null|Integer
     */
    public function getIdCodigoCompSupl()
    {
        return $this->idCodigoCompSupl;
    }

    /**
     * Set presentacion
     *
     *Nombre de la presentación del producto
     *
     * @parámetro String $presentacion
     * @return Presentacion
     */
    public function setPresentacion($presentacion)
    {
        $this->presentacion = (string)$presentacion;
        return $this;
    }

    /**
     * Get presentacion
     *
     * @return null|String
     */
    public function getPresentacion()
    {
        return $this->presentacion;
    }

    /**
     * Set codigoPresentacion
     *
     *Código de la presentación del producto
     *
     * @parámetro String $codigoPresentacion
     * @return CodigoPresentacion
     */
    public function setCodigoPresentacion($codigoPresentacion)
    {
        $this->codigoPresentacion = (string)$codigoPresentacion;
        return $this;
    }

    /**
     * Get codigoPresentacion
     *
     * @return null|String
     */
    public function getCodigoPresentacion()
    {
        return $this->codigoPresentacion;
    }

    /**
     * Set idUnidad
     *
     *Identificador de la unidad de medida de la presentación
     *
     * @parámetro Integer $idUnidad
     * @return IdUnidad
     */
    public function setIdUnidad($idUnidad)
    {
        $this->idUnidad = (integer)$idUnidad;
        return $this;
    }

    /**
     * Get idUnidad
     *
     * @return null|Integer
     */
    public function getIdUnidad()
    {
        return $this->idUnidad;
    }

    /**
     * Set unidad
     *
     *Nombre de la unidad de medida de la presentación del producto
     *
     * @parámetro String $unidad
     * @return Unidad
     */
    public function setUnidad($unidad)
    {
        $this->unidad = (string)$unidad;
        return $this;
    }

    /**
     * Get unidad
     *
     * @return null|String
     */
    public function getUnidad()
    {
        return $this->unidad;
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
        $this->estado = (string)$estado;
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
     * Set identificadorModificacion
     *
     *Identificador del usuario que modifica el registro
     *
     * @parámetro String $identificadorModificacion
     * @return IdentificadorModificacion
     */
    public function setIdentificadorModificacion($identificadorModificacion)
    {
        $this->identificadorModificacion = (string)$identificadorModificacion;
        return $this;
    }

    /**
     * Get identificadorModificacion
     *
     * @return null|String
     */
    public function getIdentificadorModificacion()
    {
        return $this->identificadorModificacion;
    }

    /**
     * Set fechaModificacion
     *
     *Fecha de modificación del registro
     *
     * @parámetro Date $fechaModificacion
     * @return FechaModificacion
     */
    public function setFechaModificacion($fechaModificacion)
    {
        $this->fechaModificacion = (string)$fechaModificacion;
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
     * @return PresentacionesPlaguicidasModelo
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
