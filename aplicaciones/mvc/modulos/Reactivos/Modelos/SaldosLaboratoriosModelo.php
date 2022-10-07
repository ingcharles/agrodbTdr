<?php

/**
 * Modelo SaldosLaboratoriosModelo
 *
 * Este archivo se complementa con el archivo   SaldosLaboratoriosLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       SaldosLaboratoriosModelo
 * @package Reactivos
 * @subpackage Modelo
 */

namespace Agrodb\Reactivos\Modelos;

use Agrodb\Core\ModeloBase;
use Agrodb\Core\ValidarDatos;

class SaldosLaboratoriosModelo extends ModeloBase {

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idSaldoLaboratorio;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idSolicitudRequerimiento;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idRecetaAnalisis;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idTipoAnalisis;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idRegistroManual;
    
    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idSolucion;
    
    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $idReactivoLaboratorio;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $tipoIngreso;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $motivo;

    /**
     * @var Decimal
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $cantidad;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $fechaCaducidad;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $lote;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $fechaRegistro;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $fechaIngreso;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $egresoBodega;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * 
     */
    protected $tipoAlmacenado;
    
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
    protected $observacion;
    
    /**
     * @var String
     * 
     */
    protected $codCatalogo;
    
    /**
     * @var String
     * 
     */
    protected $autorizacion;

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
     * Nombre de la tabla: saldos_laboratorios
     * 
     */
    Private $tabla = "saldos_laboratorios";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_saldo_laboratorio";

    /**
     * Secuencia
     */
    private $secuencial = 'g_reactivos"."saldos_laboratorios_id_saldo_laboratorio_seq';

    /**
     * Constructor
     * $datos - Puede ser los campos del formualario que deben considir con los campos de la tabla
     * @parÃ¡metro  array|null $datos
     * @retorna void
     */
    public function __construct(array $datos = null) {
        if (is_array($datos)) {
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
    public function __set($name, $value) {
        $method = 'set' . $name;
        if (!method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: SaldosLaboratoriosModelo. Propiedad especificada invalida: set' . $name);
        }
        $this->$method($value);
    }

    /**
     * Permitir el acceso a la propiedad
     * 
     * @parÃ¡metro  string $name 
     * @retorna mixed
     */
    public function __get($name) {
        $method = 'get' . $name;
        if (!method_exists($this, $method)) {
            throw new \Exception('Clase Modelo: SaldosLaboratoriosModelo. Propiedad especificada invalida: get' . $name);
        }
        return $this->$method();
    }

    /**
     * Llena el modelo con datos
     * 
     * @parÃ¡metro  array $datos 
     * @retorna Modelo
     */
    public function setOptions(array $datos) {
        $methods = get_class_methods($this);
        foreach ($datos as $key => $value) {
            $key_original = $key;
            if (strpos($key, '_') > 0) {
                $aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function($string) {
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
    public function getPrepararDatos() {
        $claseArray = get_object_vars($this);
        foreach ($this->campos as $key => $value) {
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
    public function setEsquema($esquema) {
        $this->esquema = $esquema;
        return $this;
    }

    /**
     * Get g_reactivos
     *
     * @return null|
     */
    public function getEsquema() {
        return $this->esquema;
    }

    /**
     * Set idSaldoLaboratorio
     *
     *
     *
     * @parÃ¡metro Integer $idSaldoLaboratorio
     * @return IdSaldoLaboratorio
     */
    public function setIdSaldoLaboratorio($idSaldoLaboratorio) {
        $this->idSaldoLaboratorio = (Integer) $idSaldoLaboratorio;
        return $this;
    }

    /**
     * Get idSaldoLaboratorio
     *
     * @return null|Integer
     */
    public function getIdSaldoLaboratorio() {
        return $this->idSaldoLaboratorio;
    }

    /**
     * Set idSolicitudRequerimiento
     *
     *
     *
     * @parÃ¡metro Integer $idSolicitudRequerimiento
     * @return IdSolicitudRequerimiento
     */
    public function setIdSolicitudRequerimiento($idSolicitudRequerimiento) {
        $this->idSolicitudRequerimiento = (Integer) $idSolicitudRequerimiento;
        return $this;
    }

    /**
     * Get idSolicitudRequerimiento
     *
     * @return null|Integer
     */
    public function getIdSolicitudRequerimiento() {
        return $this->idSolicitudRequerimiento;
    }

    /**
     * Set idRecetaAnalisis
     *
     *
     *
     * @parÃ¡metro Integer $idRecetaAnalisis
     * @return IdRecetaAnalisis
     */
    public function setIdRecetaAnalisis($idRecetaAnalisis) {
        $this->idRecetaAnalisis = (Integer) $idRecetaAnalisis;
        return $this;
    }

    /**
     * Get idRecetaAnalisis
     *
     * @return null|Integer
     */
    public function getIdRecetaAnalisis() {
        return $this->idRecetaAnalisis;
    }

    /**
     * Set idTipoAnalisis
     *
     *
     *
     * @parÃ¡metro Integer $idTipoAnalisis
     * @return IdTipoAnalisis
     */
    public function setIdTipoAnalisis($idTipoAnalisis) {
        $this->idTipoAnalisis = (Integer) $idTipoAnalisis;
        return $this;
    }

    /**
     * Get idTipoAnalisis
     *
     * @return null|Integer
     */
    public function getIdTipoAnalisis() {
        return $this->idTipoAnalisis;
    }

    /**
     * Set idRegistroManual
     *
     *
     *
     * @parÃ¡metro Integer $idRegistroManual
     * @return IdRegistroManual
     */
    public function setIdRegistroManual($idRegistroManual) {
        $this->idRegistroManual = (Integer) $idRegistroManual;
        return $this;
    }

    /**
     * Get idRegistroManual
     *
     * @return null|Integer
     */
    public function getIdRegistroManual() {
        return $this->idRegistroManual;
    }

    /**
     * Set tipoIngreso
     *
     *
     *
     * @parÃ¡metro String $tipoIngreso
     * @return TipoIngreso
     */
    public function setTipoIngreso($tipoIngreso) {
        $this->tipoIngreso = ValidarDatos::validarAlfa($tipoIngreso, $this->tabla, " Tipo Tngreso", self::REQUERIDO, 0);
        return $this;
    }

    /**
     * Get tipoIngreso
     *
     * @return null|String
     */
    public function getTipoIngreso() {
        return $this->tipoIngreso;
    }
    
    /**
     * Set tipoIngreso
     *
     *
     *
     * @parÃ¡metro String $tipoIngreso
     * @return TipoIngreso
     */
    public function setIdSolucion($idSolucion) {
        $this->idSolucion = $idSolucion;
        return $this;
    }

    /**
     * Get tipoIngreso
     *
     * @return null|String
     */
    public function getIdSolucion() {
        return $this->idSolucion;
    }
    
    /**
     * Set tipoIngreso
     *
     *
     *
     * @parÃ¡metro String $tipoIngreso
     * @return TipoIngreso
     */
    public function setIdReactivoLaboratorio($idReactivoLaboratorio) {
        $this->idReactivoLaboratorio = $idReactivoLaboratorio;
        return $this;
    }

    /**
     * Get tipoIngreso
     *
     * @return null|String
     */
    public function getIdReactivoLaboratorio() {
        return $this->idReactivoLaboratorio;
    }

    /**
     * Set motivo
     *
     *
     *
     * @parÃ¡metro String $motivo
     * @return Motivo
     */
    public function setMotivo($motivo) {
        $this->motivo = ValidarDatos::validarAlfaEsp($motivo, $this->tabla, " Motivo", self::REQUERIDO, 128);
        return $this;
    }

    /**
     * Get motivo
     *
     * @return null|String
     */
    public function getMotivo() {
        return $this->motivo;
    }

    /**
     * Set cantidad
     *
     *
     *
     * @parÃ¡metro Decimal $cantidad
     * @return Cantidad
     */
    public function setCantidad($cantidad) {
        $this->cantidad = ValidarDatos::validarDecimal($cantidad, $this->tabla, " Cantidad", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get cantidad
     *
     * @return null|Decimal
     */
    public function getCantidad() {
        return $this->cantidad;
    }

    /**
     * Set fechaCaducidad
     *
     *
     *
     * @parÃ¡metro Date $fechaCaducidad
     * @return FechaCaducidad
     */
    public function setFechaCaducidad($fechaCaducidad) {
        $this->fechaCaducidad = ValidarDatos::validarFecha($fechaCaducidad, $this->tabla, " Fecha Caducidad", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaCaducidad
     *
     * @return null|Date
     */
    public function getFechaCaducidad() {
        return $this->fechaCaducidad;
    }

    /**
     * Set lote
     *
     *
     *
     * @parÃ¡metro String $lote
     * @return Lote
     */
    public function setLote($lote) {
        $this->lote = ValidarDatos::validarAlfa($lote, $this->tabla, " Lote", self::NO_REQUERIDO, 32);
        return $this;
    }

    /**
     * Get lote
     *
     * @return null|String
     */
    public function getLote() {
        return $this->lote;
    }

    /**
     * Set fechaRegistro
     *
     *
     *
     * @parÃ¡metro Date $fechaRegistro
     * @return FechaRegistro
     */
    public function setFechaRegistro($fechaRegistro) {
        $this->fechaRegistro = ValidarDatos::validarFecha($fechaRegistro, $this->tabla, " Fecha Registro", self::REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaRegistro
     *
     * @return null|Date
     */
    public function getFechaRegistro() {
        return $this->fechaRegistro;
    }

    /**
     * Set fechaIngreso
     *
     *
     *
     * @parÃ¡metro Date $fechaIngreso
     * @return FechaIngreso
     */
    public function setFechaIngreso($fechaIngreso) {
        $this->fechaIngreso = ValidarDatos::validarFecha($fechaIngreso, $this->tabla, " Fecha Ingreso", self::NO_REQUERIDO, 0);
        return $this;
    }

    /**
     * Get fechaIngreso
     *
     * @return null|Date
     */
    public function getFechaIngreso() {
        return $this->fechaIngreso;
    }

    /**
     * Set egresoBodega
     *
     *
     *
     * @parÃ¡metro String $egresoBodega
     * @return EgresoBodega
     */
    public function setEgresoBodega($egresoBodega) {
        $this->egresoBodega = ValidarDatos::validarAlfa($egresoBodega, $this->tabla, " Salida de Bodega", self::NO_REQUERIDO, 32);
        return $this;
    }

    /**
     * Get egresoBodega
     *
     * @return null|String
     */
    public function getEgresoBodega() {
        return $this->egresoBodega;
    }

    /**
     * Set tipoAlmacenado
     *
     *
     *
     * @parÃ¡metro String $tipoAlmacenado
     * @return TipoAlmacenado
     */
    public function setTipoAlmacenado($tipoAlmacenado) {
        $this->tipoAlmacenado = ValidarDatos::validarAlfa($tipoAlmacenado, $this->tabla, " Tipo de Almacenado", self::NO_REQUERIDO, 128);
        return $this;
    }

    /**
     * Get tipoAlmacenado
     *
     * @return null|String
     */
    public function getTipoAlmacenado() {
        return $this->tipoAlmacenado;
    }
    
    /**
     * Set tipoAlmacenado
     *
     *
     *
     * @parÃ¡metro String $tipoAlmacenado
     * @return TipoAlmacenado
     */
    public function setUbicacion($ubicacion) {
        $this->ubicacion = ValidarDatos::validarAlfaEsp($ubicacion, $this->tabla, " Ubicación", self::NO_REQUERIDO, 256);
        return $this;
    }

    /**
     * Get tipoAlmacenado
     *
     * @return null|String
     */
    public function getUbicacion() {
        return $this->ubicacion;
    }

    /**
     * Set observacion
     *
     *
     *
     * @parÃ¡metro String $observacion
     * @return Observacion
     */
    public function setObservacion($observacion) {
        $this->observacion = ValidarDatos::validarAlfaEsp($observacion, $this->tabla, " Observación", self::NO_REQUERIDO, 512);
        return $this;
    }

    /**
     * Get observacion
     *
     * @return null|String
     */
    public function getObservacion() {
        return $this->observacion;
    }
    
    /**
     * Set Cod Catalogo
     *
     *
     *
     * @parÃ¡metro String $observacion
     * @return Observacion
     */
    public function setCodCatalogo($codCatalogo) {
        $this->codCatalogo = ValidarDatos::validarAlfa($codCatalogo, $this->tabla, " Código Catálogo", self::NO_REQUERIDO, 512);
        return $this;
    }

    /**
     * Get observacion
     *
     * @return null|String
     */
    public function getCodCatalogo() {
        return $this->codCatalogo;
    }
    
    /**
     * Set autorizacion
     *
     * @parÃ¡metro String $autorizacion
     * @return Observacion
     */
    public function setAutorizacion($autorizacion) {
        $this->autorizacion = ValidarDatos::validarAlfa($autorizacion, $this->tabla, " Autorización", self::NO_REQUERIDO, 2);
        return $this;
    }

    /**
     * Get observacion
     *
     * @return null|String
     */
    public function getAutorizacion() {
        return $this->autorizacion;
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos) {
        return parent::guardar($datos);
    }

    /**
     * Actualiza un registro actual
     * @param array $datos
     * @param int $id
     * @return int
     */
    public function actualizar(Array $datos, $id) {
        return parent::actualizar($datos, $this->clavePrimaria . " = " . $id);
    }

    /**
     * Borra el registro actual
     * @param string Where|array $where
     * @return int
     */
    public function borrar($id) {
        return parent::borrar($this->clavePrimaria . " = " . $id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param  int $id
     * @return SaldosLaboratoriosModelo
     */
    public function buscar($id) {
        return $this->setOptions(parent::buscar($this->clavePrimaria . " = " . $id));
        return $this;
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo() {
        return parent::buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parÃ¡metros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null) {
        return parent::buscarLista($where);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function ejecutarConsulta($consulta) {
        return parent::ejecutarConsulta($consulta);
    }

}
