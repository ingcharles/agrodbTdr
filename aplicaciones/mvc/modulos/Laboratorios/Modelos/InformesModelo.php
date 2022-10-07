<?php

/**
 * Modelo InformesModelo
 *
 * Este archivo se complementa con el archivo   InformesLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       InformesModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class InformesModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Id de informes
     */
    protected $idInforme;

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Fk de id de laboratorio
     */
    protected $fkIdLaboratorio;

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Fk de id informes
     */
    protected $fkIdInforme;

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Id de parametros
     */
    protected $idCamposResultadosInf;

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Id de direccion
     */
    protected $idDireccion;

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Id  de laboratorio
     */
    protected $idLaboratorio;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre de informe
     */
    protected $nombreInforme;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Título del informe
     */
    protected $tituloInforme;

    /**
     * @var String
     * Campo opcional
     * Campo visible en el formulario
     * Codigo
     */
    protected $codigo;

    /**
     * @var String
     * Campo opcional
     * Campo visible en el formulario
     * Orientacion
     */
    protected $orientacion;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Orden
     */
    protected $orden;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Estado
     */
    protected $estadoRegistro;

    /**
     * @var String
     * Campo opcional
     * Campo visible en el formulario
     * Tipo de campo
     */
    protected $tipoCampo;

    /**
     * @var Date
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Fecha de registro
     */
    protected $fechaRegistro;

    /**
     * @var Integer
     * Campo opcional
     * Campo visible en el formulario
     * Aprobado por
     */
    protected $aprobadoPor;

    /**
     * @var Integer
     * Campo opcional
     * Campo visible en el formulario
     * No. de Revision
     */
    protected $revision;

    /**
     * @var Integer
     * Campo opcional
     * Campo visible en el formulario
     * CÃ³digo SQL
     */
    protected $codigoSql;

    /**
     * @var Integer
     * Campo opcional
     * Campo visible en el formulario
     * Nivel
     */
    protected $nivel;

    /**
     * @var Integer
     * Campo opcional
     * Campo visible en el formulario
     * Atributos HTML
     */
    protected $atributos;

    /**
     * Campos del formulario
     * @var type 
     */
    private $campos = Array();

    /**
     * Nombre del esquema 
     * 
     */
    Private $esquema = "g_laboratorios";

    /**
     * Nombre de la tabla: informes
     * 
     */
    Private $tabla = "informes";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_informe";

    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."informes_id_informe_seq';

    /**
     * Constructor
     * $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
     * @parÃ¡metro  array|null $datos
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
     * @parÃ¡metro  string $name 
     * @parÃ¡metro  mixed $value 
     * @retorna void
     */
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (!method_exists($this, $method))
        {
            throw new \Exception('Clase Modelo: InformesModelo. Propiedad especificada invalida: set' . $name);
        }
        $this->$method($value);
    }

    /**
     * Permitir el acceso a la propiedad
     * 
     * @parÃ¡metro  string $name 
     * @retorna mixed
     */
    public function __get($name)
    {
        $method = 'get' . $name;
        if (!method_exists($this, $method))
        {
            throw new \Exception('Clase Modelo: InformesModelo. Propiedad especificada invalida: get' . $name);
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
     * Nombre del esquema del mÃ³dulo 
     *
     * @parÃ¡metro $esquema
     * @return Nombre del esquema de la base de datos
     */
    public function setEsquema($esquema)
    {
        $this->esquema = $esquema;
        return $this;
    }

    /**
     * Get g_laboratorios
     *
     * @return null|
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idInforme
     *
     * Id de la tabla informe
     *
     * @parÃ¡metro Integer $idInforme
     * @return IdInforme
     */
    public function setIdInforme($idInforme)
    {

        $this->idInforme = (Integer) $idInforme;
        return $this;
    }

    /**
     * Get idInforme
     *
     * @return null|Integer
     */
    public function getIdInforme()
    {
        return $this->idInforme;
    }

    /**
     * Set fkIdLaboratorio
     *
     * Secuencial clave primaria de la tabla laboratorios
     *
     * @parÃ¡metro Integer $fkIdLaboratorio
     * @return FkIdLaboratorio
     */
    public function setFkIdLaboratorio($fkIdLaboratorio)
    {

        $this->fkIdLaboratorio = (Integer) $fkIdLaboratorio;
        return $this;
    }

    /**
     * Get fkIdLaboratorio
     *
     * @return null|Integer
     */
    public function getFkIdLaboratorio()
    {
        return $this->fkIdLaboratorio;
    }

    /**
     * Set fkIdInforme
     *
     * Id de la tabla informe
     *
     * @parÃ¡metro Integer $fkIdInforme
     * @return FkIdInforme
     */
    public function setFkIdInforme($fkIdInforme)
    {

        $this->fkIdInforme = (Integer) $fkIdInforme;
        return $this;
    }

    /**
     * Get fkIdInforme
     *
     * @return null|Integer
     */
    public function getFkIdInforme()
    {
        return $this->fkIdInforme;
    }

    /**
     * Set idCamposResultadosInf
     *
     * Identificador de la tabla de campos_resultados_informes
     *
     * @parámetro Integer $idCamposResultadosInf
     * @return IdCamposResultadosInf
     *      */
    public function setIdCamposResultadosInf($idCamposResultadosInf)
    {

        $this->idCamposResultadosInf = (Integer) $idCamposResultadosInf;
        return $this;
    }

    /**
     * Get idCamposResultadosInf
     *
     * @return null|Integer
     */
    public function getIdCamposResultadosInf()
    {
        return $this->idCamposResultadosInf;
    }

    /**
     * Set idDireccion
     *
     * Direccion de diagnÃ³stico
     *
     * @parÃ¡metro Integer $idDireccion
     * @return IdDireccion
     */
    public function setIdDireccion($idDireccion)
    {

        $this->idDireccion = (Integer) $idDireccion;
        return $this;
    }

    /**
     * Get idDireccion
     *
     * @return null|Integer
     */
    public function getIdDireccion()
    {
        return $this->idDireccion;
    }

    /**
     * Set idLaboratorio
     *
     * Laboratorio
     *
     * @parÃ¡metro Integer $idLaboratorio
     * @return IdLaboratorio
     */
    public function setIdLaboratorio($idLaboratorio)
    {

        $this->idLaboratorio = (Integer) $idLaboratorio;
        return $this;
    }

    /**
     * Get idLaboratorio
     *
     * @return null|Integer
     */
    public function getIdLaboratorio()
    {
        return $this->idLaboratorio;
    }

    /**
     * Set nombreInforme
     *
     * Nombre de informe
     *
     * @parÃ¡metro String $nombreInforme
     * @return NombreInforme
     */
    public function setNombreInforme($nombreInforme)
    {
        $this->nombreInforme = ValidarDatos::validarAlfaEsp($nombreInforme, $this->tabla, " Nombre del Informe", self::NO_REQUERIDO, 1024);
        return $this;
    }

    /**
     * Get nombreInforme
     *
     * @return null|String
     */
    public function getNombreInforme()
    {
        return $this->nombreInforme;
    }

    /**
     * Título de informe
     * @return type
     */
    public function getTituloInforme()
    {
        return $this->tituloInforme;
    }

    /**
     * Título de informe
     * @param type $tituloInforme
     * @return \Agrodb\Laboratorios\Modelos\InformesModelo
     */
    public function setTituloInforme($tituloInforme)
    {
        $this->tituloInforme = $tituloInforme;
        return $this;
    }

    /**
     * Set codigo
     *
     * CÃ³digo del informe
     *
     * @parÃ¡metro String $codigo
     * @return Codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = ValidarDatos::validarAlfaEsp($codigo, $this->tabla, " Código del Inf.", self::NO_REQUERIDO, 32);
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
     * Set orientacion
     *
     * Puede ser horisontal o vertical
     *
     * @parÃ¡metro String $orientacion
     * @return Orientacion
     */
    public function setOrientacion($orientacion)
    {
        $this->orientacion = ValidarDatos::validarAlfa($orientacion, $this->tabla, " Orientación", self::NO_REQUERIDO, 16);
        return $this;
    }

    /**
     * Get orientacion
     *
     * @return null|String
     */
    public function getOrientacion()
    {
        return $this->orientacion;
    }

    /**
     * Set orden
     *
     * Ordenan los campos del informe
     *
     * @parÃ¡metro Integer $orden
     * @return Orden
     */
    public function setOrden($orden)
    {
        $this->orden = ValidarDatos::validarEntero($orden, $this->tabla, " Orden", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get orden
     *
     * @return null|Integer
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Set estado
     *
     * Identifica el estado del informe
     *
     * @parÃ¡metro String $estado
     * @return Estado
     */
    public function setEstadoRegistro($estadoRegistro)
    {
        $this->estadoRegistro = ValidarDatos::validarAlfa($estadoRegistro, $this->tabla, " Estado", self::REQUERIDO, 16);
        return $this;
    }

    /**
     * Get estado
     *
     * @return null|String
     */
    public function getEstadoRegistro()
    {
        return $this->estadoRegistro;
    }

    /**
     * Set tipoCampo
     *
     * Indica el tipo de campo en el formulario
     *
     * @parÃ¡metro String $tipoCampo
     * @return TipoCampo
     */
    public function setTipoCampo($tipoCampo)
    {
        $this->tipoCampo = ValidarDatos::validarAlfa($tipoCampo, $this->tabla, " Tipo de Campo", self::NO_REQUERIDO, 32);
        return $this;
    }

    /**
     * Get tipoCampo
     *
     * @return null|String
     */
    public function getTipoCampo()
    {
        return $this->tipoCampo;
    }

    /**
     * Set fechaRegistro
     *
     * Fecha de registro
     *
     * @parÃ¡metro Date $fechaRegistro
     * @return FechaRegistro
     */
    public function setFechaRegistro($fechaRegistro)
    {

        $newDate = date("d/m/Y", strtotime($fechaRegistro));
        $this->fechaRegistro = $newDate;
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
     * Set aprobadoPor
     *
     * Id del usuario que aprueba
     *
     * @parÃ¡metro Integer $aprobadoPor
     * @return AprobadoPor
     */
    public function setAprobadoPor($aprobadoPor)
    {

        $this->aprobadoPor = ValidarDatos::validarAlfa($aprobadoPor, $this->tabla, " Aprobado por", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get aprobadoPor
     *
     * @return null|Integer
     */
    public function getAprobadoPor()
    {
        return $this->aprobadoPor;
    }

    /**
     * Set revision
     *
     * No. de RevisiÃ³n
     *
     * @parÃ¡metro String $revision
     * @return Revision
     */
    public function setRevision($revision)
    {
        $this->revision = ValidarDatos::validarAlfaEsp($revision, $this->tabla, " N°.Revisión",false, 16);
        return $this;
    }

    /**
     * Get revision
     *
     * @return null|String
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * Set codigoSql
     *
     * CÃ³digo SQL
     *
     * @parÃ¡metro String $codigoSql
     * @return CodigoSql
     */
    public function setCodigoSql($codigoSql)
    {
        $this->codigoSql = ValidarDatos::campoVacio(ValidarDatos::validarAlfaEsp($codigoSql, $this->tabla, " Código sql", self::NO_REQUERIDO, 0));
        
        return $this;
    }

    /**
     * Get codigoSql
     *
     * @return null|String
     */
    public function getCodigoSql()
    {
        return $this->codigoSql;
    }

    /**
     * Set nivel
     *
     * Nivel del nodo
     *
     * @parÃ¡metro Integer $nivel
     * @return Nivel
     */
    public function setNivel($nivel)
    {

        $this->nivel = ValidarDatos::validarEntero($nivel, $this->tabla, " Nivel", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get nivel
     *
     * @return null|Integer
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * Set atributos
     *
     * atributos HTML
     *
     * @parÃ¡metro String $atributos
     * @return Atributos
     */
    public function setAtributos($atributos)
    {
        $this->atributos = ValidarDatos::validarAlfa($atributos, $this->tabla, " Atributos", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get atributos
     *
     * @return null|String
     */
    public function getAtributos()
    {
        return $this->atributos;
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
     * @return InformesModelo
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
     * Busca una lista de acuerdo a los parÃ¡metros <params> enviados.
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
