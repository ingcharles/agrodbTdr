<?php

/**
 * Modelo FinancieroCabeceraModelo
 *
 * Este archivo se complementa con el archivo   FinancieroCabeceraLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       FinancieroCabeceraModelo
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\FinancieroAutomatico\Modelos;

use Agrodb\Core\ModeloBase;

class FinancieroCabeceraModelo extends ModeloBase
{

    /**
     * @var Integer
     * Campo requerido
     * Campo oculto en el formulario o manejado internamente
     * Identificador único de la tabla
     */
    protected $idFinancieroCabecera;

    /**
     * @var Decimal
     * Campo requerido
     * Campo visible en el formulario
     * Valor total a pagar de la orden de pago
     */
    protected $totalPagar;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Número que corresponde a solicitudes de comercio exterior
     */
    protected $idVue;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre del tipo de solicitud Ej. (Fitosanitario, Exportacion, Fitosanitario, Laboratorios)
     */
    protected $tipoSolicitud;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Estado del proceso de generación de orden de pago (Por atender, Atendida)
     */
    protected $estado;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Estado del proceso de facturación electrónica (Por atender, Atendida)
     */
    protected $estadoFactura;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Número de orden de pago el cual se genera en el esquema financiero.
     */
    protected $idOrdenPago;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Observación.
     */
    protected $observacion;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Tipo de proceso a ejecutarse. (factura, comprobanteFactura)
     */
    protected $tipoProceso;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * Fecha de ingreso del registro para la generación de orden de pago.
     */
    protected $fechaIngresoCabcera;

    /**
     * @var Date
     * Campo requerido
     * Campo visible en el formulario
     * Fecha de ingreso para el proceso de facturación electrónica.
     */
    protected $fechaIngresoFactura;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre de la tabla de donde se inserta el registro Ej. g_fitosanitario_exportacion.fitosanitario_exportaciones
     */
    protected $tablaModulo;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificador (Llave primaria) del registro asociado a al nombre de la tabla
     */
    protected $idSolicitudTabla;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Nombre de la persona que va a realizar el proceso de firma de la factura electrónica.
     */
    protected $provinciaFirmante;

    /**
     * @var Integer
     * Campo requerido
     * Campo visible en el formulario
     * Identificador (Llave primaria) de la tabla g_catalogos.localizacion asociada a la provincia_firmante.
     */
    protected $idProvinciaFirmante;

    /**
     * @var String
     * Campo requerido
     * Campo visible en el formulario
     * Campo que identifica la forma de pago de la solicitud 1.- saldoDisponible, 2.-pagoElectronico.
     */
    protected $formaPago;

    /**
     * Nombre del esquema 
     * 
     */
    Private $esquema = "g_financiero_automatico";

    /**
     * Nombre de la tabla: financiero_cabecera
     * 
     */
    Private $tabla = "financiero_cabecera";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_financiero_cabecera";

    /**
     * Secuencia
     */
    private $secuencial = 'g_financiero_automatico"."financiero_cabecera_id_financiero_cabecera_seq';

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
            throw new \Exception('Clase Modelo: FinancieroCabeceraModelo. Propiedad especificada invalida: set' . $name);
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
            throw new \Exception('Clase Modelo: FinancieroCabeceraModelo. Propiedad especificada invalida: get' . $name);
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
            if (strpos($key, '_') > 0)
            {
                $aux = preg_replace_callback(" /[-_]([a-z]+)/ ", function($string)
                {
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
     * Get g_financiero_automatico
     *
     * @return null|
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set idFinancieroCabecera
     *
     * Identificador único de la tabla
     *
     * @parámetro Integer $idFinancieroCabecera
     * @return IdFinancieroCabecera
     */
    public function setIdFinancieroCabecera($idFinancieroCabecera)
    {
        if (empty($idFinancieroCabecera))
        {
            $idFinancieroCabecera = "No informa";
        }
        $this->idFinancieroCabecera = (Integer) $idFinancieroCabecera;
        return $this;
    }

    /**
     * Get idFinancieroCabecera
     *
     * @return null|Integer
     */
    public function getIdFinancieroCabecera()
    {
        return $this->idFinancieroCabecera;
    }

    /**
     * Set totalPagar
     *
     * Valor total a pagar de la orden de pago
     *
     * @parámetro Decimal $totalPagar
     * @return TotalPagar
     */
    public function setTotalPagar($totalPagar)
    {
        if (empty($totalPagar))
        {
            $totalPagar = "No informa";
        }
        $this->totalPagar = (Double) $totalPagar;
        return $this;
    }

    /**
     * Get totalPagar
     *
     * @return null|Decimal
     */
    public function getTotalPagar()
    {
        return $this->totalPagar;
    }

    /**
     * Set idVue
     *
     * Número que corresponde a solicitudes de comercio exterior
     *
     * @parámetro String $idVue
     * @return IdVue
     */
    public function setIdVue($idVue)
    {
        if (empty($idVue))
        {
            $idVue = "No informa";
        }
        $this->idVue = (String) $idVue;
        return $this;
    }

    /**
     * Get idVue
     *
     * @return null|String
     */
    public function getIdVue()
    {
        return $this->idVue;
    }

    /**
     * Set tipoSolicitud
     *
     * Nombre del tipo de solicitud Ej. (Fitosanitario, Exportacion, Fitosanitario, Laboratorios)
     *
     * @parámetro String $tipoSolicitud
     * @return TipoSolicitud
     */
    public function setTipoSolicitud($tipoSolicitud)
    {
        if (empty($tipoSolicitud))
        {
            $tipoSolicitud = "No informa";
        }
        $this->tipoSolicitud = (String) $tipoSolicitud;
        return $this;
    }

    /**
     * Get tipoSolicitud
     *
     * @return null|String
     */
    public function getTipoSolicitud()
    {
        return $this->tipoSolicitud;
    }

    /**
     * Set estado
     *
     * Estado del proceso de generación de orden de pago (Por atender, Atendida)
     *
     * @parámetro String $estado
     * @return Estado
     */
    public function setEstado($estado)
    {
        if (empty($estado))
        {
            $estado = "No informa";
        }
        $this->estado = (String) $estado;
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
     * Set estadoFactura
     *
     * Estado del proceso de facturación electrónica (Por atender, Atendida)
     *
     * @parámetro String $estadoFactura
     * @return EstadoFactura
     */
    public function setEstadoFactura($estadoFactura)
    {
        if (empty($estadoFactura))
        {
            $estadoFactura = "No informa";
        }
        $this->estadoFactura = (String) $estadoFactura;
        return $this;
    }

    /**
     * Get estadoFactura
     *
     * @return null|String
     */
    public function getEstadoFactura()
    {
        return $this->estadoFactura;
    }

    /**
     * Set idOrdenPago
     *
     * Número de orden de pago el cual se genera en el esquema financiero.
     *
     * @parámetro Integer $idOrdenPago
     * @return IdOrdenPago
     */
    public function setIdOrdenPago($idOrdenPago)
    {
        if (empty($idOrdenPago))
        {
            $idOrdenPago = "No informa";
        }
        $this->idOrdenPago = (Integer) $idOrdenPago;
        return $this;
    }

    /**
     * Get idOrdenPago
     *
     * @return null|Integer
     */
    public function getIdOrdenPago()
    {
        return $this->idOrdenPago;
    }

    /**
     * Set observacion
     *
     * Observación.
     *
     * @parámetro String $observacion
     * @return Observacion
     */
    public function setObservacion($observacion)
    {
        if (empty($observacion))
        {
            $observacion = "No informa";
        }
        $this->observacion = (String) $observacion;
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
     * Set tipoProceso
     *
     * Tipo de proceso a ejecutarse. (factura, comprobanteFactura)
     *
     * @parámetro String $tipoProceso
     * @return TipoProceso
     */
    public function setTipoProceso($tipoProceso)
    {
        if (empty($tipoProceso))
        {
            $tipoProceso = "No informa";
        }
        $this->tipoProceso = (String) $tipoProceso;
        return $this;
    }

    /**
     * Get tipoProceso
     *
     * @return null|String
     */
    public function getTipoProceso()
    {
        return $this->tipoProceso;
    }

    /**
     * Set fechaIngresoCabcera
     *
     * Fecha de ingreso del registro para la generación de orden de pago.
     *
     * @parámetro Date $fechaIngresoCabcera
     * @return FechaIngresoCabcera
     */
    public function setFechaIngresoCabcera($fechaIngresoCabcera)
    {
        if (empty($fechaIngresoCabcera))
        {
            $fechaIngresoCabcera = "No informa";
        }
        $this->fechaIngresoCabcera = (String) $fechaIngresoCabcera;
        return $this;
    }

    /**
     * Get fechaIngresoCabcera
     *
     * @return null|Date
     */
    public function getFechaIngresoCabcera()
    {
        return $this->fechaIngresoCabcera;
    }

    /**
     * Set fechaIngresoFactura
     *
     * Fecha de ingreso para el proceso de facturación electrónica.
     *
     * @parámetro Date $fechaIngresoFactura
     * @return FechaIngresoFactura
     */
    public function setFechaIngresoFactura($fechaIngresoFactura)
    {
        if (empty($fechaIngresoFactura))
        {
            $fechaIngresoFactura = "No informa";
        }
        $this->fechaIngresoFactura = (String) $fechaIngresoFactura;
        return $this;
    }

    /**
     * Get fechaIngresoFactura
     *
     * @return null|Date
     */
    public function getFechaIngresoFactura()
    {
        return $this->fechaIngresoFactura;
    }

    /**
     * Set tablaModulo
     *
     * Nombre de la tabla de donde se inserta el registro Ej. g_fitosanitario_exportacion.fitosanitario_exportaciones
     *
     * @parámetro String $tablaModulo
     * @return TablaModulo
     */
    public function setTablaModulo($tablaModulo)
    {
        if (empty($tablaModulo))
        {
            $tablaModulo = "No informa";
        }
        $this->tablaModulo = (String) $tablaModulo;
        return $this;
    }

    /**
     * Get tablaModulo
     *
     * @return null|String
     */
    public function getTablaModulo()
    {
        return $this->tablaModulo;
    }

    /**
     * Set idSolicitudTabla
     *
     * Identificador (Llave primaria) del registro asociado a al nombre de la tabla
     *
     * @parámetro Integer $idSolicitudTabla
     * @return IdSolicitudTabla
     */
    public function setIdSolicitudTabla($idSolicitudTabla)
    {
        if (empty($idSolicitudTabla))
        {
            $idSolicitudTabla = "No informa";
        }
        $this->idSolicitudTabla = (Integer) $idSolicitudTabla;
        return $this;
    }

    /**
     * Get idSolicitudTabla
     *
     * @return null|Integer
     */
    public function getIdSolicitudTabla()
    {
        return $this->idSolicitudTabla;
    }

    /**
     * Set provinciaFirmante
     *
     * Nombre de la persona que va a realizar el proceso de firma de la factura electrónica.
     *
     * @parámetro String $provinciaFirmante
     * @return ProvinciaFirmante
     */
    public function setProvinciaFirmante($provinciaFirmante)
    {
        if (empty($provinciaFirmante))
        {
            $provinciaFirmante = "No informa";
        }
        $this->provinciaFirmante = (String) $provinciaFirmante;
        return $this;
    }

    /**
     * Get provinciaFirmante
     *
     * @return null|String
     */
    public function getProvinciaFirmante()
    {
        return $this->provinciaFirmante;
    }

    /**
     * Set idProvinciaFirmante
     *
     * Identificador (Llave primaria) de la tabla g_catalogos.localizacion asociada a la provincia_firmante.
     *
     * @parámetro Integer $idProvinciaFirmante
     * @return IdProvinciaFirmante
     */
    public function setIdProvinciaFirmante($idProvinciaFirmante)
    {
        if (empty($idProvinciaFirmante))
        {
            $idProvinciaFirmante = "No informa";
        }
        $this->idProvinciaFirmante = (Integer) $idProvinciaFirmante;
        return $this;
    }

    /**
     * Get idProvinciaFirmante
     *
     * @return null|Integer
     */
    public function getIdProvinciaFirmante()
    {
        return $this->idProvinciaFirmante;
    }

    /**
     * Set formaPago
     *
     * Campo que identifica la forma de pago de la solicitud 1.- saldoDisponible, 2.-pagoElectronico.
     *
     * @parámetro String $formaPago
     * @return ProvinciaFirmante
     */
    public function setformaPago($formaPago)
    {
        $this->formaPago = (String) $formaPago;
        return $this;
    }

    /**
     * Get formaPago
     *
     * @return null|String
     */
    public function getformaPago()
    {
        return $this->formaPago;
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
     * @return FinancieroCabeceraModelo
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
