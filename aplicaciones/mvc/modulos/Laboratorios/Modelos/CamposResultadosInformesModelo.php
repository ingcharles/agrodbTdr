<?php

/**
 * Modelo CamposResultadosInformesModelo
 *
 * Este archivo se complementa con el archivo   CamposResultadosInformesLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       CamposResultadosInformesModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class CamposResultadosInformesModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificador de la tabla de campos_resultados_inf
     */
    protected $idCamposResultadosInf;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Secuencial (PK) de la tabla servicio
     */
    protected $idServicio;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Clave foranea recursiva
     */
    protected $fkIdCamposResultadosInf;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Secuencial clave primaria de la tabla laboratorios
     */
    protected $idLaboratorio;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Clave primaria de la tabla laboratorios(direcciones)
     */
    protected $idDireccion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Código del parámetro, este es utilizado en la programacion por lo que una vez establecido no debe ser cambiado.
     */
    protected $codigo;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Tipo de campo a mostrar Ej Texto, Combo
     */
    protected $tipoCampo;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre del campo de resultados
     */
    protected $nombre;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Descripcion o ayuda para el ingreso del resultado
     */
    protected $descripcion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Estado del registro
     */
    protected $estadoRegistro;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nivel en el árbol recursivo
     */
    protected $nivel;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Atributos que ayudan a la presentación del formulario o del informe; se debe ingresar en una cadena tipo json para diferenciar si se aplica al desplegar en el formulario o el informe
     */
    protected $atributosExtras;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Poner el valor true.  Cuando el campo debe ser controlado como obligatorio
     */
    protected $obligatorio;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Orden que se debe presentar en la pantalla
     */
    protected $orden;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Indica como agrupar los elementos en el formulario, parte del core del sistema GUIA
     */
    protected $dataLinea;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Usuario quien aprueba el cambio
     */
    protected $aprobadoPor;

    /**
     * Indica la forma de despligue de los campos
     * @var type 
     */
    protected $despliegue;

    /**
     * Valor por defecto si aplica
     * @var type 
     */
    protected $valorDefecto;

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
     * Nombre de la tabla: campos_resultados_informes
     * 
     */
    Private $tabla = "campos_resultados_informes";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_campos_resultados_inf";

    /**
     * Secuencia  g_laboratorios.campos_resultados_informes_id_campos_resultados_inf_seq
     */
    private $secuencial = 'g_laboratorios"."campos_resultados_informes_id_campos_resultados_inf_seq';

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
            throw new \Exception('Clase Modelo: CamposResultadosInformesModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: CamposResultadosInformesModelo. Propiedad especificada invalida: get' . $name);
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
     * Get g_laboratorios
     *
     * @return null|
     */
    public function getEsquema()
    {
        return $this->esquema;
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
     * Set idServicio
     *
     * Secuencial (PK) de la tabla servicio
     *
     * @parámetro Integer $idServicio
     * @return IdServicio
     */
    public function setIdServicio($idServicio)
    {
        if (empty($idServicio))
        {
            $idServicio = "No informa";
        }
        $this->idServicio = (Integer) $idServicio;
        return $this;
    }

    /**
     * Get idServicio
     *
     * @return null|Integer
     */
    public function getIdServicio()
    {
        return $this->idServicio;
    }

    /**
     * Set fkIdCamposResultadosInf
     *
     * Clave foranea recursiva
     *
     * @parámetro Integer $fkIdCamposResultadosInf
     * @return FkIdCamposResultadosInf
     */
    public function setFkIdCamposResultadosInf($fkIdCamposResultadosInf)
    {

        $this->fkIdCamposResultadosInf = (Integer) $fkIdCamposResultadosInf;
        return $this;
    }

    /**
     * Get fkIdCamposResultadosInf
     *
     * @return null|Integer
     */
    public function getFkIdCamposResultadosInf()
    {
        return $this->fkIdCamposResultadosInf;
    }

    /**
     * Set idLaboratorio
     *
     * Secuencial clave primaria de la tabla laboratorios
     *
     * @parámetro Integer $idLaboratorio
     * @return IdLaboratorio
     */
    public function setIdLaboratorio($idLaboratorio)
    {
        if (empty($idLaboratorio))
        {
            $idLaboratorio = "No informa";
        }
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
     * Set idDireccion
     *
     * Clave primaria de la tabla laboratorios(direcciones)
     *
     * @parámetro Integer $idDireccion
     * @return IdDireccion
     */
    public function setIdDireccion($idDireccion)
    {
        if (empty($idDireccion))
        {
            $idDireccion = "No informa";
        }
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
     * Set codigo
     *
     * Código del parámetro, este es utilizado en la programacion por lo que una vez establecido no debe ser cambiado.
     *
     * @parámetro String $codigo
     * @return Codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = ValidarDatos::validarAlfaEsp($codigo, $this->tabla, " Código", self::NO_REQUERIDO, 16);
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
     * Set tipoCampo
     *
     * Tipo de campo a mostrar Ej Texto, Combo
     *
     * @parámetro String $tipoCampo
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
     * Set nombre
     *
     * Nombre del campo de resultados
     *
     * @parámetro String $nombre
     * @return Nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = ValidarDatos::validarAlfaEsp($nombre, $this->tabla, " Nombre", self::REQUERIDO, 256);
        return $this;
    }

    /**
     * Get nombre
     *
     * @return null|String
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set descripcion
     *
     * Descripcion o ayuda para el ingreso del resultado
     *
     * @parámetro String $descripcion
     * @return Descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = ValidarDatos::validarAlfa($descripcion, $this->tabla, " Descripción", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return null|String
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set estado
     *
     * Estado del registro
     *
     * @parámetro String $estado
     * @return Estado
     */
    public function setEstadoRegistro($estadoRegistro)
    {
        $this->estadoRegistro = ValidarDatos::validarAlfa($estadoRegistro, $this->tabla, "Estado", self::REQUERIDO, 8);
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
     * Set nivel
     *
     * Nivel en el árbol recursivo
     *
     * @parámetro String $nivel
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
     * @return null|String
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * Set atributosExtras
     *
     * Atributos que ayudan a la presentación del formulario o del informe; se debe ingresar en una cadena tipo json para diferenciar si se aplica al desplegar en el formulario o el informe
     *
     * @parámetro String $atributosExtras
     * @return AtributosExtras
     */
    public function setAtributosExtras($atributosExtras)
    {
        $this->atributosExtras = ValidarDatos::validarAlfa($atributosExtras, $this->tabla, " Atributos Extras", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get atributosExtras
     *
     * @return null|String
     */
    public function getAtributosExtras()
    {
        return $this->atributosExtras;
    }

    /**
     * Set obligatorio
     *
     * Poner el valor true.  Cuando el campo debe ser controlado como obligatorio
     *
     * @parámetro String $obligatorio
     * @return Obligatorio
     */
    public function setObligatorio($obligatorio)
    {

        $this->obligatorio = ValidarDatos::validarAlfa($obligatorio, $this->tabla, " Obligatorio", self::REQUERIDO, 2);
        return $this;
    }

    /**
     * Get obligatorio
     *
     * @return null|String
     */
    public function getObligatorio()
    {
        return $this->obligatorio;
    }

    /**
     * Set orden
     *
     * Orden que se debe presentar en la pantalla
     *
     * @parámetro Integer $orden
     * @return Orden
     */
    public function setOrden($orden)
    {
        $this->orden = ValidarDatos::validarEntero($orden, $this->tabla, " Orden", self::REQUERIDO, 0);
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
     * Set dataLinea
     *
     * Indica como agrupar los elementos en el formulario, parte del core del sistema GUIA
     *
     * @parámetro String $dataLinea
     * @return DataLinea
     */
    public function setDataLinea($dataLinea)
    {
        $this->dataLinea = ValidarDatos::validarAlfa($dataLinea, $this->tabla, " Agrupa campos", self::NO_REQUERIDO, 16);
        return $this;
    }

    /**
     * Get dataLinea
     *
     * @return null|String
     */
    public function getDataLinea()
    {
        return $this->dataLinea;
    }

    /**
     * Set aprobadoPor
     *
     * Usuario quien aprueba el cambio
     *
     * @parámetro Integer $aprobadoPor
     * @return AprobadoPor
     */
    public function setAprobadoPor($aprobadoPor)
    {
        $this->aprobadoPor = ValidarDatos::validarEntero($aprobadoPor, $this->tabla, " ID usuario ", self::NO_REQUERIDO, 0);
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
     * Indica la forma de despliegue de los campos estos pueden ser Horizontal o Vertical
     * @return type
     */
    public function getDespliegue()
    {
        return $this->despliegue;
    }

    /**
     * Indica la forma de despliegue de los campos estos pueden ser Horizontal o Vertical
     * @param type $despliegue
     * @return $this
     */
    public function setDespliegue($despliegue)
    {
        $this->despliegue = $despliegue;
        return $this;
    }

    /**
     * Valor por defecto si aplica
     * @return type
     */
    public function getValorDefecto()
    {
        return $this->valorDefecto;
    }

    /**
     * Valor por defecto si aplica
     * @param type $valorDefecto
     * @return $this
     */
    public function setValorDefecto($valorDefecto)
    {
        $this->valorDefecto = $valorDefecto;
        return $this;
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
     * @return CamposResultadosInformesModelo
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
        return parent::buscarLista($where, $order);
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
