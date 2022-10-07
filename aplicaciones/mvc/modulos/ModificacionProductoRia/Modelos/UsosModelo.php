<?php
/**
 * Modelo UsosModelo
 *
 * Este archivo se complementa con el archivo   UsosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-13
 * @uses    UsosModelo
 * @package ModificacionProductoRia
 * @subpackage Modelos
 */

namespace Agrodb\ModificacionProductoRia\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class UsosModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificador unico de la tabla
     */
    protected $idUso;
    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificador unico de la tabla g_modificacion_productos.detalle_solicitudes_productos
     */
    protected $idDetalleSolicitudProducto;
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
     * Nombre del cultivo
     */
    protected $nombreCultivo;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre cientifico del cultivo
     */
    protected $nombreCientificoCultivo;
    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificador del registro de la plaga
     */
    protected $idPlaga;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre de la plaga
     */
    protected $nombrePlaga;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre científico de la plaga
     */
    protected $nombreCientificoPlaga;
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
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificador del uso
     */
    protected $idUsoProducto;
    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificador de producto al cual se aplica el producto
     */
    protected $idAplicacionProducto;
    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificador de la especie
     */
    protected $idEspecie;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre especifico del animal que esta relacionado con la especie
     */
    protected $nombreEspecie;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Tipo de aplicacion
     */
    protected $aplicadoA;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre de la aplicacion
     */
    protected $instalacion;
    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificador unico de la tabla origen de registo
     */
    protected $idTablaOrigen;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Estado relacionado con la tabla de origen
     */
    protected $estado;
    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * Campo que almacena la fecha de creacion del registro
     */
    protected $fechaCreacion;

    /**
     * Campos del formulario
     * @var array
     */
    private $campos = array();

    /**
     * Nombre del esquema
     *
     */
    private $esquema = "g_modificacion_productos";

    /**
     * Nombre de la tabla: usos
     *
     */
    private $tabla = "usos";

    /**
     *Clave primaria
     */
    private $clavePrimaria = "id_uso";


    /**
     *Secuencia
     */
    private $secuencial = 'g_modificacion_productos"."usos_id_uso_seq';


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
            throw new \Exception('Clase Modelo: UsosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: UsosModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_modificacion_productos
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
     *Identificador unico de la tabla
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
     * Set idDetalleSolicitudProducto
     *
     *Identificador unico de la tabla g_modificacion_productos.detalle_solicitudes_productos
     *
     * @parámetro Integer $idDetalleSolicitudProducto
     * @return IdDetalleSolicitudProducto
     */
    public function setIdDetalleSolicitudProducto($idDetalleSolicitudProducto)
    {
        $this->idDetalleSolicitudProducto = (integer)$idDetalleSolicitudProducto;
        return $this;
    }

    /**
     * Get idDetalleSolicitudProducto
     *
     * @return null|Integer
     */
    public function getIdDetalleSolicitudProducto()
    {
        return $this->idDetalleSolicitudProducto;
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
     * Set nombreCultivo
     *
     *Nombre del cultivo
     *
     * @parámetro String $nombreCultivo
     * @return NombreCultivo
     */
    public function setNombreCultivo($nombreCultivo)
    {
        $this->nombreCultivo = (string)$nombreCultivo;
        return $this;
    }

    /**
     * Get nombreCultivo
     *
     * @return null|String
     */
    public function getNombreCultivo()
    {
        return $this->nombreCultivo;
    }

    /**
     * Set nombreCientificoCultivo
     *
     *Nombre cientifico del cultivo
     *
     * @parámetro String $nombreCientificoCultivo
     * @return NombreCientificoCultivo
     */
    public function setNombreCientificoCultivo($nombreCientificoCultivo)
    {
        $this->nombreCientificoCultivo = (string)$nombreCientificoCultivo;
        return $this;
    }

    /**
     * Get nombreCientificoCultivo
     *
     * @return null|String
     */
    public function getNombreCientificoCultivo()
    {
        return $this->nombreCientificoCultivo;
    }

    /**
     * Set idPlaga
     *
     *Identificador del registro de la plaga
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
     * Set nombrePlaga
     *
     *Nombre de la plaga
     *
     * @parámetro String $nombrePlaga
     * @return NombrePlaga
     */
    public function setNombrePlaga($nombrePlaga)
    {
        $this->nombrePlaga = (string)$nombrePlaga;
        return $this;
    }

    /**
     * Get nombrePlaga
     *
     * @return null|String
     */
    public function getNombrePlaga()
    {
        return $this->nombrePlaga;
    }

    /**
     * Set nombreCientificoPlaga
     *
     *Nombre científico de la plaga
     *
     * @parámetro String $nombreCientificoPlaga
     * @return NombreCientificoPlaga
     */
    public function setNombreCientificoPlaga($nombreCientificoPlaga)
    {
        $this->nombreCientificoPlaga = (string)$nombreCientificoPlaga;
        return $this;
    }

    /**
     * Get nombreCientificoPlaga
     *
     * @return null|String
     */
    public function getNombreCientificoPlaga()
    {
        return $this->nombreCientificoPlaga;
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
     * Set idUsoProducto
     *
     *Identificador del uso
     *
     * @parámetro Integer $idUsoProducto
     * @return IdUsoProducto
     */
    public function setIdUsoProducto($idUsoProducto)
    {
        $this->idUsoProducto = (integer)$idUsoProducto;
        return $this;
    }

    /**
     * Get idUsoProducto
     *
     * @return null|Integer
     */
    public function getIdUsoProducto()
    {
        return $this->idUsoProducto;
    }

    /**
     * Set idAplicacionProducto
     *
     *Identificador de producto al cual se aplica el producto
     *
     * @parámetro Integer $idAplicacionProducto
     * @return IdAplicacionProducto
     */
    public function setIdAplicacionProducto($idAplicacionProducto)
    {
        $this->idAplicacionProducto = (integer)$idAplicacionProducto;
        return $this;
    }

    /**
     * Get idAplicacionProducto
     *
     * @return null|Integer
     */
    public function getIdAplicacionProducto()
    {
        return $this->idAplicacionProducto;
    }

    /**
     * Set idEspecie
     *
     *Identificador de la especie
     *
     * @parámetro Integer $idEspecie
     * @return IdEspecie
     */
    public function setIdEspecie($idEspecie)
    {
        $this->idEspecie = (integer)$idEspecie;
        return $this;
    }

    /**
     * Get idEspecie
     *
     * @return null|Integer
     */
    public function getIdEspecie()
    {
        return $this->idEspecie;
    }

    /**
     * Set nombreEspecie
     *
     *Nombre especifico del animal que esta relacionado con la especie
     *
     * @parámetro String $nombreEspecie
     * @return NombreEspecie
     */
    public function setNombreEspecie($nombreEspecie)
    {
        $this->nombreEspecie = (string)$nombreEspecie;
        return $this;
    }

    /**
     * Get nombreEspecie
     *
     * @return null|String
     */
    public function getNombreEspecie()
    {
        return $this->nombreEspecie;
    }

    /**
     * Set aplicadoA
     *
     *Tipo de aplicacion
     *
     * @parámetro String $aplicadoA
     * @return AplicadoA
     */
    public function setAplicadoA($aplicadoA)
    {
        $this->aplicadoA = (string)$aplicadoA;
        return $this;
    }

    /**
     * Get aplicadoA
     *
     * @return null|String
     */
    public function getAplicadoA()
    {
        return $this->aplicadoA;
    }

    /**
     * Set instalacion
     *
     *Nombre de la aplicacion
     *
     * @parámetro String $instalacion
     * @return Instalacion
     */
    public function setInstalacion($instalacion)
    {
        $this->instalacion = (string)$instalacion;
        return $this;
    }

    /**
     * Get instalacion
     *
     * @return null|String
     */
    public function getInstalacion()
    {
        return $this->instalacion;
    }

    /**
     * Set idTablaOrigen
     *
     *Identificador unico de la tabla origen de registo
     *
     * @parámetro Integer $idTablaOrigen
     * @return IdTablaOrigen
     */
    public function setIdTablaOrigen($idTablaOrigen)
    {
        $this->idTablaOrigen = (integer)$idTablaOrigen;
        return $this;
    }

    /**
     * Get idTablaOrigen
     *
     * @return null|Integer
     */
    public function getIdTablaOrigen()
    {
        return $this->idTablaOrigen;
    }

    /**
     * Set estado
     *
     *Estado relacionado con la tabla de origen
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
     * Set fechaCreacion
     *
     *Campo que almacena la fecha de creacion del registro
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
     * @return UsosModelo
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
