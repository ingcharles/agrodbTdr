<?php

/**
 * Modelo ServiciosModelo
 *
 * Este archivo se complementa con el archivo   ServiciosLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       ServiciosModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class ServiciosModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Id de sevicio
     */
    protected $idServicio;

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Id de servicio de guia
     */
    protected $idServicioGuia;

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Id de laboratorio
     */
    protected $idLaboratorio;

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Fk de id de servicio
     */
    protected $fkIdServicio;

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Id de direccion
     */
    protected $idDireccion;

    /**
     * @var String
     * Campo opcional
     * Campo visible en el formulario
     * Codigo de analisis
     */
    protected $codigoAnalisis;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre
     */
    protected $nombre;

    /**
     * @var Integer
     * Campo opcional
     * Campo visible en el formulario
     * Nivel
     */
    protected $nivel;

    /**
     * @var String
     * Campo opcional
     * Campo visible en el formulario
     * Parametro
     */
    protected $parametro;

    /**
     * @var String
     * Campo opcional
     * Campo visible en el formulario
     * Metodo
     */
    protected $metodo;

    /**
     * @var String
     * Campo opcional
     * Campo visible en el formulario
     * Tecnica
     */
    protected $tecnica;

    /**
     * @var String
     * Campo opcional
     * Campo visible en el formulario
     * Metodo de referencia
     */
    protected $metodoReferencia;

    /**
     * @var String
     * Campo opcional
     * Campo visible en el formulario
     * Requisitos
     */
    protected $requisitos;

    /**
     * @var String
     * Campo opcional
     * Campo visible en el formulario
     * Procedimiento
     */
    protected $procedimiento;

    /**
     * @var String
     * Campo opcional
     * Campo visible en el formulario
     * Unidad de medida
     */
    protected $unidadMedida;

    /**
     * @var String
     * Campo opcional
     * Campo visible en el formulario
     * Acreditacion
     */
    protected $acreditacion;

    /**
     * @var Integer
     * Campo opcional
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
    protected $estado;

    /**
     * @var String
     * Campo opcional
     * Campo visible en el formulario
     * Atributos
     */
    protected $atributos;

    /**
     * @var String
     * Campo opcional
     * Rama
     */
    protected $rama;

    /**
     * @var String
     * Campo tipo
     * Rama
     */
    protected $tipo;
    
    /**
     * @var String
     * Campo codigoEspecial
     * CodigoEspecial
     */
    protected $codigoEspecial;

    /**
     * Nombre del esquema
     *
     */
    Private $esquema = "g_laboratorios";

    /**
     * Nombre de la tabla: servicios
     *
     */
    Private $tabla = "servicios";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_servicio";

    /**
     * Secuencia
     */
    private $secuencial = 'g_laboratorios"."servicios_id_servicio_seq';

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
            throw new \Exception('Clase Modelo: ServiciosModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: ServiciosModelo. Propiedad especificada invalida: get' . $name);
        }
        return $this->$method();
    }

    /**
     * Llena el modelo con datos
     *
     * @parÃ¡metro  array $datos
     * @retorna Modelo
     */
    public function setOptions(array $datos)
    {
        $methods = get_class_methods($this);
        foreach ($datos as $key => $value)
        {
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
            }
        }
        return $this;
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
     * Set idServicio
     *
     * Secuencial (PK) de la tabla servicio
     *
     * @parÃ¡metro Integer $idServicio
     * @return IdServicio
     */
    public function setIdServicio($idServicio)
    {
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
     * Set idServicioGuia
     *
     * Id
     *
     * @parÃ¡metro Integer $idServicioGuia
     * @return IdServicioGuia
     */
    public function setIdServicioGuia($idServicioGuia)
    {
        $this->idServicioGuia = (Integer) $idServicioGuia;
        return $this;
    }

    /**
     * Get idServicioGuia
     *
     * @return null|Integer
     */
    public function getIdServicioGuia()
    {
        return $this->idServicioGuia;
    }

    /**
     * Set idLaboratorio
     *
     * Secuencial clave primaria de la tabla laboratorios
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
     * Set fkIdServicio
     *
     * Secuencial (PK) de la tabla servicio
     *
     * @parÃ¡metro Integer $fkIdServicio
     * @return FkIdServicio
     */
    public function setFkIdServicio($fkIdServicio)
    {
        $this->fkIdServicio = (Integer) $fkIdServicio;
        return $this;
    }

    /**
     * Get fkIdServicio
     *
     * @return null|Integer
     */
    public function getFkIdServicio()
    {
        return $this->fkIdServicio;
    }

    /**
     * Set idDireccion
     *
     * Id de la direcciÃ³n de diagnÃ³stico
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
     * Set codigoAnalisis
     *
     * CÃ³digo que ayuda a identificar el tipo de anÃ¡lisis
     *
     * @parÃ¡metro String $codigoAnalisis
     * @return CodigoAnalisis
     */
    public function setCodigoAnalisis($codigoAnalisis)
    {
        if (empty($codigoAnalisis))
        {
            $codigoAnalisis = "No informa";
        }
        $this->codigoAnalisis = (String) $codigoAnalisis;
        return $this;
    }

    /**
     * Get codigoAnalisis
     *
     * @return null|String
     */
    public function getCodigoAnalisis()
    {
        return $this->codigoAnalisis;
    }

    /**
     * Set nombre
     *
     * Nombre del tipo de anÃ¡lisis
     *
     * @parÃ¡metro String $nombre
     * @return Nombre
     */
    public function setNombre($nombre)
    {
        if (empty($nombre))
        {
            $nombre = "No informa";
        }
        $this->nombre = (String) $nombre;
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
     * Set parametro
     *
     * ParÃ¡metro  de  anÃ¡lisis
     *
     * @parÃ¡metro String $parametro
     * @return Parametro
     */
    public function setParametro($parametro)
    {
        $this->parametro = ValidarDatos::validarAlfaEsp($parametro, $this->tabla, " Parámetro", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get parametro
     *
     * @return null|String
     */
    public function getParametro()
    {
        return $this->parametro;
    }

    /**
     * Set metodo
     *
     * MÃ©todo aplicado al tipo de anÃ¡lisis
     *
     * @parÃ¡metro String $metodo
     * @return Metodo
     */
    public function setMetodo($metodo)
    {
        $this->metodo = ValidarDatos::validarAlfaEsp($metodo, $this->tabla, " Método", self::NO_REQUERIDO, 64);
        return $this;
    }

    /**
     * Get metodo
     *
     * @return null|String
     */
    public function getMetodo()
    {
        return $this->metodo;
    }

    /**
     * Set tecnica
     *
     * TÃ©cnica utilizada para el anÃ¡lisis
     *
     * @parÃ¡metro String $tecnica
     * @return Tecnica
     */
    public function setTecnica($tecnica)
    {
        $this->tecnica = ValidarDatos::validarAlfaEsp($tecnica, $this->tabla, " Técnica", self::NO_REQUERIDO, 128);
        return $this;
    }

    /**
     * Get tecnica
     *
     * @return null|String
     */
    public function getTecnica()
    {
        return $this->tecnica;
    }

    /**
     * Set metodoReferencia
     *
     * MÃ©todo externo de referencia para el anÃ¡lisis
     *
     * @parÃ¡metro String $metodoReferencia
     * @return MetodoReferencia
     */
    public function setMetodoReferencia($metodoReferencia)
    {
        $this->metodoReferencia = ValidarDatos::validarAlfaEsp($metodoReferencia, $this->tabla, "metodo_referencia", self::NO_REQUERIDO, 64);
        return $this;
    }

    /**
     * Get metodoReferencia
     *
     * @return null|String
     */
    public function getMetodoReferencia()
    {
        return $this->metodoReferencia;
    }

    /**
     * Set requisitos
     *
     * Describe indicaciones que el cliente debe tomar en cuenta al solicitar el servicio
     *
     * @parÃ¡metro String $requisitos
     * @return Requisitos
     */
    public function setRequisitos($requisitos)
    {
        $this->requisitos = ValidarDatos::validarAlfa($requisitos, $this->tabla, " Requisitos", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get requisitos
     *
     * @return null|String
     */
    public function getRequisitos()
    {
        return $this->requisitos;
    }

    /**
     * Set procedimiento
     *
     * Describe el procedimiento del anÃ¡lisis
     *
     * @parÃ¡metro String $procedimiento
     * @return Procedimiento
     */
    public function setProcedimiento($procedimiento)
    {

        if (empty($procedimiento))
        {
            $procedimiento = "No informa";
        }
        $this->procedimiento = (String) $procedimiento;
        return $this;
    }

    /**
     * Get procedimiento
     *
     * @return null|String
     */
    public function getProcedimiento()
    {
        return $this->procedimiento;
    }

    /**
     * Set unidadMedida
     *
     * Indica la unidad de medida para el resultado del anÃ¡lisis
     *
     * @parÃ¡metro String $unidadMedida
     * @return UnidadMedida
     */
    public function setUnidadMedida($unidadMedida)
    {
        $this->unidadMedida = ValidarDatos::validarAlfa($unidadMedida, $this->tabla, " Unidad de Medida", self::NO_REQUERIDO, 128);
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
     * Set acreditacion
     *
     * Describe el nÃºmero de acreditaciÃ³n en se existir
     *
     * @parÃ¡metro String $acreditacion
     * @return Acreditacion
     */
    public function setAcreditacion($acreditacion)
    {
        $this->acreditacion = ValidarDatos::validarAlfa($acreditacion, $this->tabla, " Creditación", self::NO_REQUERIDO, 32);
        return $this;
    }

    /**
     * Get acreditacion
     *
     * @return null|String
     */
    public function getAcreditacion()
    {
        return $this->acreditacion;
    }

    /**
     * Set orden
     *
     * Indica el orden que debe desplegarse en la pantalla
     *
     * @parÃ¡metro Integer $orden
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
     * Set estado
     *
     * Indica el estado del registro, en caso borrado lÃ³gico estarÃ¡ borrado y por defecto estarÃ¡ activo.
     *
     * @parÃ¡metro String $estado
     * @return Estado
     */
    public function setEstado($estado)
    {
        $this->estado = ValidarDatos::validarAlfa($estado, $this->tabla, " Estado", self::REQUERIDO, 8);
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
     * Set atributos
     *
     * ParÃ¡metros adicionales para el despliegue en los formularios o informes
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
     * Set rama
     *
     * @parÃ¡metro String $rama
     * @return Rama
     */
    public function setRama($rama)
    {
        $this->rama = $rama;
        return $this;
    }

    /**
     * Get rama
     *
     * @return null|String
     */
    public function getRama()
    {
        return $this->rama;
    }

    /**
     * Set tipo
     *
     * @parÃ¡metro String $tipo
     * @return Tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = ValidarDatos::validarAlfa($tipo, $this->tabla, " Tipo", self::REQUERIDO, 0);
        return $this;
    }

    /**
     * Get tipo
     *
     * @return null|String
     */
    public function getTipo()
    {
        return $this->tipo;
    }
    
    /**
     * Set codigoEspecial
     *
     * CÃ³digo que ayuda a identificar el tipo de anÃ¡lisis
     *
     * @parÃ¡metro String $codigoEspecial
     * @return CodigoEspecial
     */
    public function setCodigoEspecial($codigoEspecial)
    {
        $this->codigoEspecial = ValidarDatos::validarAlfa($codigoEspecial, $this->tabla, " Código Especial", self::NO_REQUERIDO, 128);
        return $this;
    }

    /**
     * Get codigoEspecial
     *
     * @return null|String
     */
    public function getCodigoEspecial()
    {
        return $this->codigoEspecial;
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
     * @return ServiciosModelo
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
        return parent::buscarLista($where, $order, $count, $offset);
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
