<?php
/**
 * Modelo CentrosFaenamientoModelo
 *
 * Este archivo se complementa con el archivo   CentrosFaenamientoLogicaNegocio.
 *
 * @author  AGROCALIDAD
 * @date    2018-11-21
 * @uses    CentrosFaenamientoModelo
 * @package CentrosFaenamiento
 * @subpackage Modelos
 */
namespace Agrodb\CentrosFaenamiento\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class CentrosFaenamientoModelo extends ModeloBase
{

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      llave primaria de la tabla
     */
    protected $idCentroFaenamiento;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      ruc del centro de faenamiento
     */
    protected $identificadorOperador;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      subtipo del producto
     */
    protected $especie;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      llave foranea de la tabla operador_tipo_operacion
     */
    protected $idOperadorTipoOperacion;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      criterio de funcionamiento del centro de faenamiento
     */
    protected $criterioFuncionamiento;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      observacion al asignar criterios de funcionamiento
     */
    protected $observacion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      llave foranea de la tabla sitio
     */
    protected $idSitio;

    /**
     *
     * @var Date Campo requerido
     *      Campo visible en el formulario
     *      fecha de creacion del registro
     */
    protected $fechaCreacion;

    /**
     *
     * @var Integer Campo requerido
     *      Campo visible en el formulario
     *      llave foranea de la tabla area
     */
    protected $idArea;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      identificador del que creo el registro
     */
    protected $identificadorRegistro;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Razon social del operador
     */
    protected $razonSocial;

    /**
     *
     * @var String Campo requerido
     *      Campo visible en el formulario
     *      Provincia del sitio del oeprador
     */
    protected $provincia;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * código del centro de faenamiento
     */
    protected $codigo;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Tipo de centro de faenamiento
     */
    protected $tipoCentroFaenamiento;
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Tipo de habilitación
     */
    protected $tipoHabilitacion;
    

    public $codigoEjecutable;

    /**
     * Campos del formulario
     * 
     * @var array
     */
    private $campos = Array();

    /**
     * Nombre del esquema
     */
    private $esquema = "g_centros_faenamiento";

    /**
     * Nombre de la tabla: centros_faenamiento
     */
    private $tabla = "centros_faenamiento";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_centro_faenamiento";

    /**
     * Secuencia
     */
    private $secuencial = 'g_centros_faenamiento"."centros_faenamiento_id_centro_faenamiento_seq';

    /**
     * Secuencia
     *
     * Constructor
     * $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
     * @parámetro array|null $datos
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
     * @parámetro string $name
     * @parámetro mixed $value
     * @retorna void
     */
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (! method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: CentrosFaenamientoModelo. Propiedad especificada invalida: set' . $name);
        }
        $this->$method($value);
    }

    /**
     * Permitir el acceso a la propiedad
     *
     * @parámetro string $name
     * @retorna mixed
     */
    public function __get($name)
    {
        $method = 'get' . $name;
        if (! method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: CentrosFaenamientoModelo. Propiedad especificada invalida: get' . $name);
        }
        return $this->$method();
    }

    /**
     * Llena el modelo con datos
     *
     * @parámetro array $datos
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
     * 
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
     * @return null
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idCentroFaenamiento
     * $this->codigoEjecutable=$this->modeloCentrosFaenamiento->getCodigoEjecutable();
     * llave primaria de la tabla
     *
     * @parámetro Integer $idCentroFaenamiento
     * 
     * @return IdCentroFaenamiento
     */
    public function setIdCentroFaenamiento($idCentroFaenamiento)
    {
        $this->idCentroFaenamiento = (integer) $idCentroFaenamiento;
        return $this;
    }

    /**
     * Get idCentroFaenamiento
     *
     * @return null|Integer
     */
    public function getIdCentroFaenamiento()
    {
        return $this->idCentroFaenamiento;
    }

    /**
     * Set identificadorOperador
     *
     * identificadorOperador del centro de faenamiento
     *
     * @parámetro String $identificadorOperador
     * 
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
     * Set especie
     *
     * subtipo del producto
     *
     * @parámetro String $especie
     * 
     * @return Especie
     */
    public function setEspecie($especie)
    {
        $this->especie = (string) $especie;
        return $this;
    }

    /**
     * Get especie
     *
     * @return null|String
     */
    public function getEspecie()
    {
        return $this->especie;
    }

    /**
     * Set idOperadorTipoOperacion
     *
     * llave foranea de la tabla operador_tipo_operacion
     *
     * @parámetro Integer $idOperadorTipoOperacion
     * 
     * @return IdOperadorTipoOperacion
     */
    public function setIdOperadorTipoOperacion($idOperadorTipoOperacion)
    {
        $this->idOperadorTipoOperacion = (integer) $idOperadorTipoOperacion;
        return $this;
    }

    /**
     * Get idOperadorTipoOperacion
     *
     * @return null|Integer
     */
    public function getIdOperadorTipoOperacion()
    {
        return $this->idOperadorTipoOperacion;
    }

    /**
     * Set criterioFuncionamiento
     *
     * criterio de funcionamiento del centro de faenamiento
     *
     * @parámetro String $criterioFuncionamiento
     * 
     * @return CriterioFuncionamiento
     */
    public function setCriterioFuncionamiento($criterioFuncionamiento)
    {
        $this->criterioFuncionamiento = (string) $criterioFuncionamiento;
        return $this;
    }

    /**
     * Get criterioFuncionamiento
     *
     * @return null|String
     */
    public function getCriterioFuncionamiento()
    {
        return $this->criterioFuncionamiento;
    }

    /**
     * Set observacion
     *
     * observacion al asignar criterios de funcionamiento
     *
     * @parámetro String $observacion
     * 
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
     * Set idSitio
     *
     * llave foranea de la tabla sitio
     *
     * @parámetro Integer $idSitio
     * 
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
     * Set fechaCreacion
     *
     * fecha de creacion del registro
     *
     * @parámetro Date $fechaCreacion
     * 
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
     * Set idArea
     *
     * llave foranea de la tabla area
     *
     * @parámetro Integer $idArea
     * 
     * @return IdArea
     */
    public function setIdArea($idArea)
    {
        $this->idArea = (integer) $idArea;
        return $this;
    }

    /**
     * Get idArea
     *
     * @return null|Integer
     */
    public function getIdArea()
    {
        return $this->idArea;
    }

    /**
     * Set identificadorRegistro
     *
     * identificador del que creo el registro
     *
     * @parámetro String $identificadorRegistro
     * 
     * @return IdentificadorRegistro
     */
    public function setIdentificadorRegistro($identificadorRegistro)
    {
        $this->identificadorRegistro = (string) $identificadorRegistro;
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
     * Set razonSocial
     *
     * Razón social del operador
     *
     * @parámetro String $razonSocial
     * 
     * @return RazonSocial
     */
    public function setRazonSocial($razonSocial)
    {
        $this->razonSocial = (string) $razonSocial;
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
     * Set provincia
     *
     * Provincia del sitio del operador
     *
     * @parámetro String $provincia
     * 
     * @return Provincia
     */
    public function setProvincia($provincia)
    {
        $this->provincia = (string) $provincia;
        return $this;
    }
    /**
     * Set codigo
     *
     *código del centro de faenamiento
     *
     * @parámetro String $codigo
     * @return Codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = (String) $codigo;
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
     * Set tipoCentroFaenamiento
     *
     *Tipo de centro de faenamiento
     *
     * @parámetro String $tipoCentroFaenamiento
     * @return TipoCentroFaenamiento
     */
    public function setTipoCentroFaenamiento($tipoCentroFaenamiento)
    {
        $this->tipoCentroFaenamiento = (String) $tipoCentroFaenamiento;
        return $this;
    }
    
    /**
     * Get tipoCentroFaenamiento
     *
     * @return null|String
     */
    public function getTipoCentroFaenamiento()
    {
        return $this->tipoCentroFaenamiento;
    }
    
    /**
     * Set tipoHabilitacion
     *
     *Tipo de habilitación
     *
     * @parámetro String $tipoHabilitacion
     * @return TipoHabilitacion
     */
    public function setTipoHabilitacion($tipoHabilitacion)
    {
        $this->tipoHabilitacion = (String) $tipoHabilitacion;
        return $this;
    }
    
    /**
     * Get tipoHabilitacion
     *
     * @return null|String
     */
    public function getTipoHabilitacion()
    {
        return $this->tipoHabilitacion;
    }
    
    /**
     * Get identificadorRegistro
     *
     * @return null|String
     */
    public function getIdentificadorRegistro()
    {
        return $this->identificadorRegistro;
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
     * @return CentrosFaenamientoModelo
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

    /**
     * Código html o jacascript
     * 
     * @param type $codigoEjecutable
     * @return
     */
    public function setCodigoEjecutable($codigoEjecutable)
    {
        $this->codigoEjecutable = $codigoEjecutable;
        return $this;
    }

    public function getCodigoEjecutable()
    {
        return $this->codigoEjecutable;
    }
}
