<?php
/**
 * Modelo PartidaCodigosModelo
 *
 * Este archivo se complementa con el archivo   PartidaCodigosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    PartidaCodigosModelo
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class PartidaCodigosModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador único del registro
     */
    protected $idPartidaCodigo;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador de la solicitud de registro de producto
     */
    protected $idSolicitud;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      Fecha de creación del registro
     */
    protected $fechaCreacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Código de la partida arancelaria
     */
    protected $partidaArancelaria;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del código complementario
     */
    protected $idCodigoComplementario;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Código complementario del producto
     */
    protected $codigoComplementario;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      Identificador del código suplementario
     */
    protected $idCodigoSuplementario;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Código suplementario del producto
     */
    protected $codigoSuplementario;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_dossier_pecuario_mvc";

    /**
     * Nombre de la tabla: partida_codigos
     */
    private $tabla = "partida_codigos";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_partida_codigo";

    /**
     * Secuencia
     */
    private $secuencial = 'g_dossier_pecuario_mvc"."partida_codigos_id_partida_codigo_seq';

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
            throw new \Exception('Clase Modelo: PartidaCodigosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: PartidaCodigosModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_dossier_pecuario_mvc
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idPartidaCodigo
     *
     * Identificador único del registro
     *
     * @parámetro Integer $idPartidaCodigo
     * @return IdPartidaCodigo
     */
    public function setIdPartidaCodigo($idPartidaCodigo)
    {
        $this->idPartidaCodigo = (integer) $idPartidaCodigo;
        return $this;
    }

    /**
     * Get idPartidaCodigo
     *
     * @return null|Integer
     */
    public function getIdPartidaCodigo()
    {
        return $this->idPartidaCodigo;
    }

    /**
     * Set idSolicitud
     *
     * Identificador de la solicitud de registro de producto
     *
     * @parámetro Integer $idSolicitud
     * @return IdSolicitud
     */
    public function setIdSolicitud($idSolicitud)
    {
        $this->idSolicitud = (integer) $idSolicitud;
        return $this;
    }

    /**
     * Get idSolicitud
     *
     * @return null|Integer
     */
    public function getIdSolicitud()
    {
        return $this->idSolicitud;
    }

    /**
     * Set fechaCreacion
     *
     * Fecha de creación del registro
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
     * Set partidaArancelaria
     *
     * Código de la partida arancelaria
     *
     * @parámetro String $partidaArancelaria
     * @return PartidaArancelaria
     */
    public function setPartidaArancelaria($partidaArancelaria)
    {
        $this->partidaArancelaria = (string) $partidaArancelaria;
        return $this;
    }

    /**
     * Get partidaArancelaria
     *
     * @return null|String
     */
    public function getPartidaArancelaria()
    {
        return $this->partidaArancelaria;
    }

    /**
     * Set idCodigoComplementario
     *
     * Identificador del código complementario
     *
     * @parámetro Integer $idCodigoComplementario
     * @return IdCodigoComplementario
     */
    public function setIdCodigoComplementario($idCodigoComplementario)
    {
        $this->idCodigoComplementario = (integer) $idCodigoComplementario;
        return $this;
    }

    /**
     * Get idCodigoComplementario
     *
     * @return null|Integer
     */
    public function getIdCodigoComplementario()
    {
        return $this->idCodigoComplementario;
    }

    /**
     * Set codigoComplementario
     *
     * Código complementario del producto
     *
     * @parámetro String $codigoComplementario
     * @return CodigoComplementario
     */
    public function setCodigoComplementario($codigoComplementario)
    {
        $this->codigoComplementario = (string) $codigoComplementario;
        return $this;
    }

    /**
     * Get codigoComplementario
     *
     * @return null|String
     */
    public function getCodigoComplementario()
    {
        return $this->codigoComplementario;
    }

    /**
     * Set idCodigoSuplementario
     *
     * Identificador del código suplementario
     *
     * @parámetro Integer $idCodigoSuplementario
     * @return IdCodigoSuplementario
     */
    public function setIdCodigoSuplementario($idCodigoSuplementario)
    {
        $this->idCodigoSuplementario = (integer) $idCodigoSuplementario;
        return $this;
    }

    /**
     * Get idCodigoSuplementario
     *
     * @return null|Integer
     */
    public function getIdCodigoSuplementario()
    {
        return $this->idCodigoSuplementario;
    }

    /**
     * Set codigoSuplementario
     *
     * Código suplementario del producto
     *
     * @parámetro String $codigoSuplementario
     * @return CodigoSuplementario
     */
    public function setCodigoSuplementario($codigoSuplementario)
    {
        $this->codigoSuplementario = (string) $codigoSuplementario;
        return $this;
    }

    /**
     * Get codigoSuplementario
     *
     * @return null|String
     */
    public function getCodigoSuplementario()
    {
        return $this->codigoSuplementario;
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
     * @return PartidaCodigosModelo
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
