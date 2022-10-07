<?php
/**
 * Modelo SitiosModelo
 *
 * Este archivo se complementa con el archivo   SitiosLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2020-02-19
 * @uses    SitiosModelo
 * @package RegistroOperador
 * @subpackage Modelos
 */
namespace Agrodb\RegistroOperador\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class SitiosModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $idSitio;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $nombreLugar;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $parroquia;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $direccion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $latitud;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $longitud;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $superficieTotal;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $croquis;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $identificadorOperador;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $referencia;

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
     *     
     */
    protected $telefono;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $canton;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $provincia;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $codigo;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $zona;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $codigoProvincia;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $imagenMapa;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *     
     */
    protected $fechaSitio;

    /**
     * Campos del formulario
     *
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_operadores";

    /**
     * Nombre de la tabla: sitios
     */
    private $tabla = "sitios";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_sitio";

    /**
     * Secuencia
     */
    private $secuencial = 'g_operadores"."sitios_id_sitio_seq';

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
            throw new \Exception('Clase Modelo: SitiosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: SitiosModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_operadores
     *
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idSitio
     *
     *
     *
     * @parámetro Integer $idSitio
     * @return IdSitio
     */
    public function setIdSitio($idSitio)
    {
        $this->idSitio = (integer) $idSitio;
        return $this;
    }

    /**
     * Get idSitio
     *
     * @return null|Integer
     */
    public function getIdSitio()
    {
        return $this->idSitio;
    }

    /**
     * Set nombreLugar
     *
     *
     *
     * @parámetro String $nombreLugar
     * @return NombreLugar
     */
    public function setNombreLugar($nombreLugar)
    {
        $this->nombreLugar = (string) $nombreLugar;
        return $this;
    }

    /**
     * Get nombreLugar
     *
     * @return null|String
     */
    public function getNombreLugar()
    {
        return $this->nombreLugar;
    }

    /**
     * Set parroquia
     *
     *
     *
     * @parámetro String $parroquia
     * @return Parroquia
     */
    public function setParroquia($parroquia)
    {
        $this->parroquia = (string) $parroquia;
        return $this;
    }

    /**
     * Get parroquia
     *
     * @return null|String
     */
    public function getParroquia()
    {
        return $this->parroquia;
    }

    /**
     * Set direccion
     *
     *
     *
     * @parámetro String $direccion
     * @return Direccion
     */
    public function setDireccion($direccion)
    {
        $this->direccion = (string) $direccion;
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
     * Set latitud
     *
     *
     *
     * @parámetro String $latitud
     * @return Latitud
     */
    public function setLatitud($latitud)
    {
        $this->latitud = (string) $latitud;
        return $this;
    }

    /**
     * Get latitud
     *
     * @return null|String
     */
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * Set longitud
     *
     *
     *
     * @parámetro String $longitud
     * @return Longitud
     */
    public function setLongitud($longitud)
    {
        $this->longitud = (string) $longitud;
        return $this;
    }

    /**
     * Get longitud
     *
     * @return null|String
     */
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * Set superficieTotal
     *
     *
     *
     * @parámetro String $superficieTotal
     * @return SuperficieTotal
     */
    public function setSuperficieTotal($superficieTotal)
    {
        $this->superficieTotal = (string) $superficieTotal;
        return $this;
    }

    /**
     * Get superficieTotal
     *
     * @return null|String
     */
    public function getSuperficieTotal()
    {
        return $this->superficieTotal;
    }

    /**
     * Set croquis
     *
     *
     *
     * @parámetro String $croquis
     * @return Croquis
     */
    public function setCroquis($croquis)
    {
        $this->croquis = (string) $croquis;
        return $this;
    }

    /**
     * Get croquis
     *
     * @return null|String
     */
    public function getCroquis()
    {
        return $this->croquis;
    }

    /**
     * Set identificadorOperador
     *
     *
     *
     * @parámetro String $identificadorOperador
     * @return IdentificadorOperador
     */
    public function setIdentificadorOperador($identificadorOperador)
    {
        $this->identificadorOperador = (string) $identificadorOperador;
        return $this;
    }

    /**
     * Get identificadorOperador
     *
     * @return null|String
     */
    public function getIdentificadorOperador()
    {
        return $this->identificadorOperador;
    }

    /**
     * Set referencia
     *
     *
     *
     * @parámetro String $referencia
     * @return Referencia
     */
    public function setReferencia($referencia)
    {
        $this->referencia = (string) $referencia;
        return $this;
    }

    /**
     * Get referencia
     *
     * @return null|String
     */
    public function getReferencia()
    {
        return $this->referencia;
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
     * Set telefono
     *
     *
     *
     * @parámetro String $telefono
     * @return Telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = (string) $telefono;
        return $this;
    }

    /**
     * Get telefono
     *
     * @return null|String
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set canton
     *
     *
     *
     * @parámetro String $canton
     * @return Canton
     */
    public function setCanton($canton)
    {
        $this->canton = (string) $canton;
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
     * Set provincia
     *
     *
     *
     * @parámetro String $provincia
     * @return Provincia
     */
    public function setProvincia($provincia)
    {
        $this->provincia = (string) $provincia;
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
     * Set codigo
     *
     *
     *
     * @parámetro String $codigo
     * @return Codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = (string) $codigo;
        return $this;
    }

    /**
     * Get codigo
     *
     * @return null|String
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set zona
     *
     *
     *
     * @parámetro String $zona
     * @return Zona
     */
    public function setZona($zona)
    {
        $this->zona = (string) $zona;
        return $this;
    }

    /**
     * Get zona
     *
     * @return null|String
     */
    public function getZona()
    {
        return $this->zona;
    }

    /**
     * Set codigoProvincia
     *
     *
     *
     * @parámetro String $codigoProvincia
     * @return CodigoProvincia
     */
    public function setCodigoProvincia($codigoProvincia)
    {
        $this->codigoProvincia = (string) $codigoProvincia;
        return $this;
    }

    /**
     * Get codigoProvincia
     *
     * @return null|String
     */
    public function getCodigoProvincia()
    {
        return $this->codigoProvincia;
    }

    /**
     * Set imagenMapa
     *
     *
     *
     * @parámetro String $imagenMapa
     * @return ImagenMapa
     */
    public function setImagenMapa($imagenMapa)
    {
        $this->imagenMapa = (string) $imagenMapa;
        return $this;
    }

    /**
     * Get imagenMapa
     *
     * @return null|String
     */
    public function getImagenMapa()
    {
        return $this->imagenMapa;
    }

    /**
     * Set fechaSitio
     *
     *
     *
     * @parámetro Date $fechaSitio
     * @return FechaSitio
     */
    public function setFechaSitio($fechaSitio)
    {
        $this->fechaSitio = (string) $fechaSitio;
        return $this;
    }

    /**
     * Get fechaSitio
     *
     * @return null|Date
     */
    public function getFechaSitio()
    {
        return $this->fechaSitio;
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
     * @return SitiosModelo
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
