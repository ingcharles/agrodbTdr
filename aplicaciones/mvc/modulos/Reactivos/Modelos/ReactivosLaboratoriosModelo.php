<?php

/**
 * Modelo ReactivosLaboratoriosModelo
 *
 * Este archivo se complementa con el archivo   ReactivosLaboratoriosLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       ReactivosLaboratoriosModelo
 * @package Reactivos
 * @subpackage Modelo
 */

namespace Agrodb\Reactivos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ReactivosLaboratoriosModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idDireccion;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idReactivoLaboratorio;
    
    protected $fkIdReactivoLaboratorio;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idLaboratorio;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idLaboratoriosProvincia;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idReactivoBodega;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $codigoLaboratorio;

    /**
     *
     * @var type 
     * Nombre únicamente para reactivos nuevos (estandar analítico, soluciones)
     */
    protected $nombre;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $cantidadMinima;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $cantidadMaxima;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $unidadMedida;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $estadoRegistro;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $estadoReactivo;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $pureza;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $especificacion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $presentacion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $almacenamiento;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $tipo;
    
    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $origen;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $ubicacion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $observaciones;
    
    protected $volumenFinal;

    /**
     * Campos del formulario
     * @var type 
     */
    private $campos = Array();

    /**
     * Nombre del esquema 
     * 
     */
    Private $esquema = "g_reactivos";

    /**
     * Nombre de la tabla: reactivos_laboratorios
     * 
     */
    Private $tabla = "reactivos_laboratorios";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_reactivo_laboratorio";

    /**
     * Secuencia
     */
    private $secuencial = 'g_reactivos"."reactivos_laboratorios_id_reactivo_laboratorio_seq';

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
        if (!method_exists($this, $method))
        {
            throw new \Exception('Clase Modelo: ReactivosLaboratoriosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: ReactivosLaboratoriosModelo. Propiedad especificada invalida: get' . $name);
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
            if (strpos($key, '_') > 0)
            {
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
        foreach ($this->campos as $key => $value)
        {
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
     * Get g_reactivos
     *
     * @return null|
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /*     * *
     * Get idDireccion
     * 
     */

    public function getIdDireccion()
    {
        return $this->idDireccion;
    }

    /**
     * Set idReactivoLaboratorio
     *
     *
     *
     * @parámetro Integer $idReactivoLaboratorio
     * @return IdReactivoLaboratorio
     */
    public function setIdReactivoLaboratorio($idReactivoLaboratorio)
    {
        $this->idReactivoLaboratorio = (Integer) $idReactivoLaboratorio;
        return $this;
    }

    /**
     * Get idReactivoLaboratorio
     *
     * @return null|Integer
     */
    public function getIdReactivoLaboratorio()
    {
        return $this->idReactivoLaboratorio;
    }
    
    /**
     * Set fkIdReactivoLaboratorio
     *
     *
     *
     * @parámetro Integer $fkIdReactivoLaboratorio
     * @return FkIdReactivoLaboratorio
     */
    public function setFkIdReactivoLaboratorio($fkIdReactivoLaboratorio)
    {
        $this->fkIdReactivoLaboratorio = (Integer) $fkIdReactivoLaboratorio;
        return $this;
    }

    /**
     * Get idFkReactivoLaboratorio
     *
     * @return null|Integer
     */
    public function getFkIdReactivoLaboratorio()
    {
        return $this->fkIdReactivoLaboratorio;
    }

    /**
     * Set idLaboratorio
     *
     *
     *
     * @parámetro Integer $idLaboratorio
     * @return IdLaboratorio
     */
    public function setIdLaboratorio($idLaboratorio)
    {
        $this->idLaboratorio = (Integer) $idLaboratorio;
        return $this;
    }

    /**
     * Get idLaboratoriosProvincia
     *
     * @return null|Integer
     */
    public function getIdLaboratoriosProvincia()
    {
        return $this->idLaboratoriosProvincia;
    }

    /**
     * Set idLaboratoriosProvincia
     *
     *
     *
     * @parámetro Integer $idLaboratorioProvincia
     * @return IdLaboratoriosProvincia
     */
    public function setIdLaboratoriosProvincia($idLaboratoriosProvincia)
    {
        $this->idLaboratoriosProvincia = (Integer) $idLaboratoriosProvincia;
        return $this;
    }

    /**
     * Get idLaboratorio
     *
     * @return null|Integer
     */
    public function getIdLaboratorio()
    {
        return $this->idLaboratoriosProvincia;
    }

    /**
     * Set idReactivoBodega
     *
     *
     *
     * @parámetro Integer $idReactivoBodega
     * @return IdReactivoBodega
     */
    public function setIdReactivoBodega($idReactivoBodega)
    {
        $this->idReactivoBodega = (Integer) $idReactivoBodega;
        return $this;
    }

    /**
     * Get idReactivoBodega
     *
     * @return null|Integer
     */
    public function getIdReactivoBodega()
    {
        return $this->idReactivoBodega;
    }

    /**
     * Set codigoLaboratorio
     *
     *
     *
     * @parámetro String $codigoLaboratorio
     * @return CodigoLaboratorio
     */
    public function setCodigoLaboratorio($codigoLaboratorio)
    {
        $this->codigoLaboratorio = ValidarDatos::validarAlfa($codigoLaboratorio, "g_laboratorios.laboratorios", " Código", self::NO_REQUERIDO, 16);
        return $this;
    }

    /**
     * Get codigoLaboratorio
     *
     * @return null|String
     */
    public function getCodigoLaboratorio()
    {
        return $this->codigoLaboratorio;
    }

    /**
     * Nombre únicamente para reactivos nuevos (estandar analítico, soluciones)
     * @return type
     * 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Nombre únicamente para reactivos nuevos (estandar analítico, soluciones)
     * @param type $nombre
     * @return $this
     */
    public function setNombre($nombre)
    {
        $this->nombre = ValidarDatos::validarAlfaEsp($nombre, $this->tabla, " Nombre Reactivo", self::NO_REQUERIDO, 256);
        return $this;
    }

    /**
     * Set cantidadMinima
     *
     *
     *
     * @parámetro Integer $cantidadMinima
     * @return CantidadMinima
     */
    public function setCantidadMinima($cantidadMinima)
    {
        $cantidadMinima = ($cantidadMinima != '') ? $cantidadMinima : null;
        $this->cantidadMinima = ValidarDatos::validarDecimal($cantidadMinima, $this->tabla, " Cantidad Mínima", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get cantidadMinima
     *
     * @return null|Integer
     */
    public function getCantidadMinima()
    {
        return $this->cantidadMinima;
    }

    /**
     * Set cantidadMaxima
     *
     *
     *
     * @parámetro Integer $cantidadMaxima
     * @return CantidadMaxima
     */
    public function setCantidadMaxima($cantidadMaxima)
    {
        $cantidadMaxima = ($cantidadMaxima != '') ? $cantidadMaxima : null;
        $this->cantidadMaxima = ValidarDatos::validarDecimal($cantidadMaxima, $this->tabla, " Cantidad Máxima", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get cantidadMaxima
     *
     * @return null|Integer
     */
    public function getCantidadMaxima()
    {
        return $this->cantidadMaxima;
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
        $this->unidadMedida = strtoupper(ValidarDatos::validarAlfa($unidadMedida, $this->tabla, " Unidad de Medida", self::NO_REQUERIDO, 16));
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
     * Set estadoRegistro
     *
     *
     *
     * @parámetro String $estadoRegistro
     * @return EstadoRegistro
     */
    public function setEstadoRegistro($estadoRegistro)
    {
        $this->estadoRegistro = ValidarDatos::validarAlfa($estadoRegistro, $this->tabla, " Estado de Registro", self::NO_REQUERIDO, 8);
        return $this;
    }

    /**
     * Get estadoRegistro
     *
     * @return null|String
     */
    public function getEstadoRegistro()
    {
        return $this->estadoRegistro;
    }

    /**
     * Set estadoReactivo
     *
     *
     *
     * @parámetro String $estadoReactivo
     * @return EstadoReactivo
     */
    public function setEstadoReactivo($estadoReactivo)
    {
        $this->estadoReactivo = $estadoReactivo;
        return $this;
    }

    /**
     * Get estadoReactivo
     *
     * @return null|String
     */
    public function getEstadoReactivo()
    {
        return $this->estadoReactivo;
    }

    /**
     * Set pureza
     *
     *
     *
     * @parámetro String $pureza
     * @return Pureza
     */
    public function setPureza($pureza)
    {
        $this->pureza = ValidarDatos::validarAlfaEsp($pureza, $this->tabla, " Pureza", self::NO_REQUERIDO, 256);
        return $this;
    }

    /**
     * Get pureza
     *
     * @return null|String
     */
    public function getPureza()
    {
        return $this->pureza;
    }

    /**
     * Set especificacion
     *
     *
     *
     * @parámetro String $especificacion
     * @return Especificacion
     */
    public function setEspecificacion($especificacion)
    {
        $this->especificacion = ValidarDatos::validarAlfaEsp($especificacion, $this->tabla, " Especificación", self::NO_REQUERIDO, 128);
        return $this;
    }

    /**
     * Get especificacion
     *
     * @return null|String
     */
    public function getEspecificacion()
    {
        return $this->especificacion;
    }

    /**
     * Set presentacion
     *
     *
     *
     * @parámetro String $presentacion
     * @return Presentacion
     */
    public function setPresentacion($presentacion)
    {
        $this->presentacion = ValidarDatos::validarAlfaEsp($presentacion, $this->tabla, " Presentación", self::NO_REQUERIDO, 128);
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
     * Set almacenamiento
     *
     *
     *
     * @parámetro String $almacenamiento
     * @return Almacenamiento
     */
    public function setAlmacenamiento($almacenamiento)
    {
        $this->almacenamiento = ValidarDatos::validarAlfaEsp($almacenamiento, $this->tabla, " Almacenamiento", self::NO_REQUERIDO, 128);
        return $this;
    }

    /**
     * Get almacenamiento
     *
     * @return null|String
     */
    public function getAlmacenamiento()
    {
        return $this->almacenamiento;
    }

    /**
     * Set tipo
     *
     *
     *
     * @parámetro String $tipo
     * @return Tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = ValidarDatos::validarAlfa($tipo, $this->tabla, " Tipo", self::NO_REQUERIDO, 32);
        return $this;
    }

    /**
     * Get origen
     *
     * @return null|String
     */
    public function getTipo()
    {
        return $this->origen;
    }
    
    /**
     * Set origen
     *
     *
     *
     * @parámetro String $origen
     * @return Tipo
     */
    public function setOrigen($origen)
    {
        $this->origen = ValidarDatos::validarAlfa($origen, $this->tabla, " Origen", self::NO_REQUERIDO, 32);
        return $this;
    }

    /**
     * Get origen
     *
     * @return null|String
     */
    public function getOrigen()
    {
        return $this->origen;
    }

    /**
     * Set ubicacion
     *
     *
     *
     * @parámetro String $ubicacion
     * @return Ubicacion
     */
    public function setUbicacion($ubicacion)
    {
        $this->ubicacion = ValidarDatos::validarAlfaEsp($ubicacion, $this->tabla, " Ubicación", self::NO_REQUERIDO, 256);
        return $this;
    }

    /**
     * Get ubicacion
     *
     * @return null|String
     */
    public function getUbicacion()
    {
        return $this->ubicacion;
    }

    /**
     * Set observaciones
     *
     *
     *
     * @parámetro String $observaciones
     * @return Observaciones
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = ValidarDatos::validarAlfaEsp($observaciones, $this->tabla, " Observaciones", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get observaciones
     *
     * @return null|String
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }
    
    /**
     * Set observaciones
     *
     *
     *
     * @parámetro String $observaciones
     * @return Observaciones
     */
    public function setVolumenFinal($volumenFinal)
    {
        $this->volumenFinal = ValidarDatos::validarDecimal($volumenFinal, $this->tabla, " Volumen final", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get observaciones
     *
     * @return null|String
     */
    public function getVolumenFinal()
    {
        return $this->volumenFinal;
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
     * @return ReactivosLaboratoriosModelo
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
