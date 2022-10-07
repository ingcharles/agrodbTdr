<?php

/**
 * Lógica del negocio de  SaldosLaboratoriosModelo
 *
 * Este archivo se complementa con el archivo   SaldosLaboratoriosControlador.
 *
 * @author DATASTAR
 * @uses       SaldosLaboratoriosLogicaNegocio
 * @package Reactivos
 * @subpackage Modelo
 */

namespace Agrodb\Reactivos\Modelos;

use Agrodb\Reactivos\Modelos\IModelo;

class SaldosLaboratoriosLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new SaldosLaboratoriosModelo();
    }

    /**
     * Guarda en la tabla saldos_laboratorios
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $this->modelo = new SaldosLaboratoriosModelo();
        $proceso = $this->modelo->getAdapter()
                ->getDriver()
                ->getConnection();
        if (!$proceso->beginTransaction())
        {
            throw new \Exception('No se pudo iniciar la transacción en: Guardar saldos_laboratorios');
        }
        $b = $datos['cantidad'];
        foreach ($b as $clave => $valor)
        {
            $lote = preg_replace('/( ){2,}/u', ' ', $datos['lote'][$clave]);
            $lote = strtoupper(trim($lote));
            $datosSaldosLaboratorios = array(
                'id_saldo_laboratorio' => $datos['id_saldo_laboratorio'][$clave],
                'cantidad' => $datos['cantidad'][$clave],
                'lote' => $lote,
                'fecha_caducidad' => $datos['fecha_caducidad'][$clave],
                'ubicacion' => $datos['ubicacion'][$clave]
            );
            if ($datos['estado'] == 'EN PROCESO' & $datosSaldosLaboratorios['cantidad'] == null)
            {
                $datosSaldosLaboratorios['cantidad'] = 0;
            }
            $tablaModelo = new SaldosLaboratoriosModelo($datosSaldosLaboratorios);
            $datosBd = $tablaModelo->getPrepararDatos();
            //Actualizar tabla saldos_laboratorios, solo debe actualizar
            $statement = $this->modelo->getAdapter()
                    ->getDriver()
                    ->createStatement();
            $sqlActualizar = $this->modelo->actualizarSql('saldos_laboratorios', $this->modelo->getEsquema());
            $sqlActualizar->set($datosBd);
            $sqlActualizar->where(array('id_saldo_laboratorio' => $tablaModelo->getIdSaldoLaboratorio()));
            $sqlActualizar->prepareStatement($this->modelo->getAdapter(), $statement);
            $statement->execute();
        }
        //Actualizar tabla solicitud_cabecera
        $datosSolicitud = array(
            'estado' => $datos['estado']
        );
        $statement = $this->modelo->getAdapter()
                ->getDriver()
                ->createStatement();
        $sqlActualizar = $this->modelo->actualizarSql('solicitud_cabecera', $this->modelo->getEsquema());
        $sqlActualizar->set($datosSolicitud);
        $sqlActualizar->where(array('id_solicitud_cabecera' => $datos['id_solicitud_cabecera']));
        $sqlActualizar->prepareStatement($this->modelo->getAdapter(), $statement);
        $statement->execute();

        $proceso->commit();
        return true;
    }

    /**
     * Guarda en la tabla saldos_laboratorios
     * @param array $datos
     * @return int
     */
    public function guardarSaldosSolucion(Array $datos)
    {
        $b = $datos['idReactivoLaboratorio'];
        foreach ($b as $clave => $valor)
        {
            $datosSaldosLaboratorios = array(
                'id_reactivo_laboratorio' => $datos['idReactivoLaboratorio'][$clave],
                'cantidad' => $datos['cantidad'][$clave],
                'id_solucion' => $datos['id_solucion'],
                'tipo_ingreso' => $datos['tipo_ingreso'],
                'motivo' => $datos['motivo']
            );
            $tablaModelo = new SaldosLaboratoriosModelo($datosSaldosLaboratorios);
            $datosBd = $tablaModelo->getPrepararDatos();
            if ($tablaModelo->getIdSaldoLaboratorio() != null && $tablaModelo->getIdSaldoLaboratorio() > 0)
            {
                $this->modelo->actualizar($datosBd, $tablaModelo->getIdSaldoLaboratorio());
            } else
            {
                unset($datos["id_saldo_laboratorio"]);
                $this->modelo->guardar($datosBd);
            }
        }
        return true;
    }

    /**
     * Guarda en la tabla saldos_laboratorios
     * @param array $datos
     * @return int
     */
    public function guardarSalidaReactivo(Array $datos)
    {
        $datosSaldosLaboratorios = array(
            'id_reactivo_laboratorio' => $datos['id_reactivo_laboratorio'],
            'cantidad' => $datos['cantidad'],
            'observacion' => $datos['observacion'],
            'tipo_ingreso' => $datos['tipo_ingreso'],
            'motivo' => $datos['motivo'],
            'cod_catalogo' => $datos['cod_catalago'],
            'lote' => $datos['lote']
        );
        $tablaModelo = new SaldosLaboratoriosModelo($datosSaldosLaboratorios);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdSaldoLaboratorio() != null && $tablaModelo->getIdSaldoLaboratorio() > 0)
        {
            
        } else
        {
            unset($datos["id_saldo_laboratorio"]);
            $this->modelo->guardar($datosBd);
        }
        return true;
    }

    /**
     * Guarda en la tabla saldos_laboratorios
     * @param array $datos
     * @return int
     */
    public function guardarReactivoManual(Array $datos)
    {
        $datosSaldosLaboratorios = array(
            'id_reactivo_laboratorio' => $datos['id_reactivo_laboratorio'],
            'cantidad' => $datos['cantidad'],
            'observacion' => $datos['observacion'],
            'tipo_ingreso' => $datos['tipo_ingreso'],
            'motivo' => $datos['motivo']
        );
        $tablaModelo = new SaldosLaboratoriosModelo($datosSaldosLaboratorios);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdSaldoLaboratorio() != null && $tablaModelo->getIdSaldoLaboratorio() > 0)
        {
            
        } else
        {
            unset($datos["id_saldo_laboratorio"]);
            $this->modelo->guardar($datosBd);
        }
        return true;
    }

    /**
     * Guarda en la tabla saldos_laboratorios
     * @param array $datos
     * @return int
     */
    public function guardarConsolidado(Array $datos)
    {
        $this->modelo = new SaldosLaboratoriosModelo();
        $proceso = $this->modelo->getAdapter()
                ->getDriver()
                ->getConnection();
        if (!$proceso->beginTransaction())
        {
            throw new \Exception('No se pudo iniciar la transacción en: Guardar saldos_laboratorios');
        }
        $datosSaldosLaboratorios = array(
            'id_reactivo_laboratorio' => $datos['id_reactivo_laboratorio'],
            'cantidad' => $datos['cantidad'],
            'observacion' => $datos['observacion'],
            'tipo_ingreso' => $datos['tipo_ingreso'],
            'motivo' => $datos['motivo'],
            'lote' => $datos['lote'],
            'autorizacion' => $datos['autorizacion']
        );
        $tablaModelo = new SaldosLaboratoriosModelo($datosSaldosLaboratorios);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdSaldoLaboratorio() != null && $tablaModelo->getIdSaldoLaboratorio() > 0)
        {
            
        } else if ($datos['autorizacion'] == 'SI')  //si requiere autorizacion del acta
        {
            unset($datos["id_saldo_laboratorio"]);
            unset($datos["cod_catalogo"]);
            $statement = $this->modelo->getAdapter()
                    ->getDriver()
                    ->createStatement();
            $sqlInsertar = $this->modelo->guardarSql('saldos_laboratorios', $this->modelo->getEsquema());
            $sqlInsertar->columns($this->columnas());
            $sqlInsertar->values($datosBd, $sqlInsertar::VALUES_MERGE);
            $sqlInsertar->prepareStatement($this->modelo->getAdapter(), $statement);
            $statement->execute();
            $idSaldoLaboratorio = $this->modelo->adapter->driver->getLastGeneratedValue($this->modelo->getEsquema() . '.saldos_laboratorios_id_saldo_laboratorio_seq');
            //Crear el registro en acta_baja
            $datosActaBaja = array(
                'nombre_acta' => null,
                'contenido' => null,
                'id_saldo_laboratorio' => $idSaldoLaboratorio,
                'responsable_crea' => $datos['identificador'],
                'id_laboratorios_provincia' => $datos['id_laboratorios_provincia']
            );
            $lNegocioActaBaja = new ActabajaLogicaNegocio();
            $statement = $this->modelo->getAdapter()
                    ->getDriver()
                    ->createStatement();
            $sqlInsertar = $this->modelo->guardarSql('acta_baja', $this->modelo->getEsquema());
            $sqlInsertar->columns($lNegocioActaBaja->columnas());
            $sqlInsertar->values($datosActaBaja, $sqlInsertar::VALUES_MERGE);
            $sqlInsertar->prepareStatement($this->modelo->getAdapter(), $statement);
            $statement->execute();
        }
        $proceso->commit();
        return true;
    }

    /**
     * Borra el registro actual
     * @param string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        $this->modelo->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param  int $id
     * @return SaldosLaboratoriosModelo
     */
    public function buscar($id)
    {
        return $this->modelo->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modelo->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modelo->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una función en postgres para preparar los saldos: g_reactivos.f_inicializar_ingreso(idSolicitudCabecera);
     * @param type $idSolicitudCabecera
     * @return type
     */
    public function inicializarIngreso($idSolicitudCabecera)
    {
        $consulta = "SELECT 
        nombre,
        cantidad_solicitada,
        unidad,
        cantidad,
        id_solicitud_cabecera,
        id_saldo_laboratorio,
        id_solicitud_requerimiento,
        id_reactivo_bodega,
        nuevo,
        lote,
        fecha_caducidad,
        ubicacion,
        nombre_archivo
        FROM " . $this->modelo->getEsquema() . ".f_inicializar_ingreso(" . $idSolicitudCabecera . ") 
        ORDER BY id_solicitud_requerimiento, nuevo";
        $resultado = $this->modelo->ejecutarSqlNativo($consulta);
        return $resultado;
    }

    /**
     * Llama a la funcion f_crear_saldo_lote que crea un nuevo registro (campo nuevo=SI)
     * @param type $idSaldoLaboratorio
     * @param type $idSolicitudCabecera
     * @return type
     */
    public function crearNuevoLote($idSaldoLaboratorio, $idSolicitudCabecera)
    {
        $consulta = "SELECT id_saldo_laboratorio,
        cantidad,
        cantidad_solicitada,
        unidad,
        nuevo,
        nombre,
        nombre_archivo,
        id_solicitud_requerimiento,
        ubicacion,
        fecha_caducidad,
        lote
        FROM " . $this->modelo->getEsquema() . ".f_crear_saldo_lote(" . $idSaldoLaboratorio . "," . $idSolicitudCabecera . ")
        ORDER BY id_solicitud_requerimiento, nuevo";
        $resultado = $this->modelo->ejecutarSqlNativo($consulta);
        return $resultado;
    }

    /**
     * Consulta los reactivos disponibles en bodega y los saldos que tiene el laboratorio
     *
     * @return array|ResultSet
     */
    public function buscarSaldosPorLaboratorio($arrayParametros)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['id_laboratorio']))
        {
            $arrayWhere[] = " id_laboratorio = " . $arrayParametros['id_laboratorio'];
        }
        if (!empty($arrayParametros['id_laboratorios_provincia']))
        {
            if (is_array($arrayParametros['id_laboratorios_provincia']))
            {
                $arrayWhere[] = " id_laboratorios_provincia IN (" . implode(",", $arrayParametros['id_laboratorios_provincia']) . ")";
            } else
            {
                $arrayWhere[] = " id_laboratorios_provincia = " . $arrayParametros['id_laboratorios_provincia'];
            }
        }
        if (!empty($arrayParametros['id_reactivo_laboratorio']))
        {
            $arrayWhere[] = " id_reactivo_laboratorio = " . $arrayParametros['id_reactivo_laboratorio'];
        }
        if ($arrayWhere)
        {
            $where = " WHERE " . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT v_slab.id_reactivo_bodega,
        v_slab.id_reactivo_laboratorio,
        v_slab.id_laboratorio,
        v_slab.id_laboratorios_provincia,
        v_slab.nombre,
        v_slab.unidad,
        v_slab.total_ingreso,
        v_slab.total_egreso,
        v_slab.saldo
        FROM " . $this->modelo->getEsquema() . ".v_saldos_por_laboratorio v_slab $where ORDER BY nombre";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Consulta los reactivos disponibles en bodega y los saldos que tiene el laboratorio
     *
     * @return array|ResultSet
     */
    public function buscarSaldosReactivosLaboratorios($arrayParametros)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['id_laboratorio']))
        {
            $arrayWhere[] = " id_laboratorio = " . $arrayParametros['id_laboratorio'];
        }
        if (!empty($arrayParametros['id_laboratorios_provincia']))
        {
            if (is_array($arrayParametros['id_laboratorios_provincia']))
            {
                $arrayWhere[] = " id_laboratorios_provincia IN (" . implode(",", $arrayParametros['id_laboratorios_provincia']) . ")";
            } else
            {
                $arrayWhere[] = " id_laboratorios_provincia = " . $arrayParametros['id_laboratorios_provincia'];
            }
        }
        if (!empty($arrayParametros['id_reactivo_laboratorio']))
        {
            $arrayWhere[] = " id_reactivo_laboratorio = " . $arrayParametros['id_reactivo_laboratorio'];
        }
        if (!empty($arrayParametros['tipo']))
        {
            if (is_array($arrayParametros['tipo']))
            {
                $arrayWhere[] = " tipo IN ('" . implode("','", $arrayParametros['tipo']) . "')";
            } else
            {
                $arrayWhere[] = " tipo = '{$arrayParametros['tipo']}'";
            }
        }
        if (!empty($arrayParametros['origen']))
        {
            $arrayWhere[] = " origen = '{$arrayParametros['origen']}'";
        }
        if (!empty($arrayParametros['nombre']))
        {
            $arrayWhere[] = " UPPER(nombre) LIKE '%" . strtoupper($arrayParametros['nombre']) . "%'";
        }
        if ($arrayWhere)
        {
            $where = " WHERE " . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT v_srealab.id_reactivo_laboratorio,
        v_srealab.nombre,
        v_srealab.unidad_medida,
        v_srealab.id_laboratorio,
        v_srealab.id_laboratorios_provincia,
        v_srealab.tipo,
        v_srealab.origen,
        v_srealab.estado_registro,
        v_srealab.cantidad_minima,
        v_srealab.cantidad_maxima,
        v_srealab.total_ingreso,
        v_srealab.total_egreso,
        v_srealab.saldo 
        FROM " . $this->modelo->getEsquema() . ".v_saldos_reactivos_laboratorios v_srealab $where ORDER BY nombre";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Buscar saldos del reactivo por lote
     * @param type $arrayParametros
     * @return type
     */
    public function buscarSaldosPorLote($arrayParametros)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['id_laboratorios_provincia']))
        {
            $arrayWhere[] = "id_laboratorios_provincia = " . $arrayParametros['id_laboratorios_provincia'];
        }
        if (!empty($arrayParametros['id_reactivo_laboratorio']))
        {
            $arrayWhere[] = "id_reactivo_laboratorio = " . $arrayParametros['id_reactivo_laboratorio'];
        }
        if (!empty($arrayParametros['lote']))
        {
            $arrayWhere[] = "lote = '" . $arrayParametros['lote'] . "'";
        }
        if ($arrayWhere)
        {
            $where = " WHERE " . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT v_slote.id_reactivo_laboratorio,
        v_slote.id_laboratorio,
        v_slote.id_laboratorios_provincia,
        v_slote.nombre,
        v_slote.unidad,
        v_slote.lote,
        v_slote.fecha_caducidad,
        v_slote.total_ingreso,
        v_slote.total_egreso,
        v_slote.saldo
        FROM " . $this->modelo->getEsquema() . ".v_saldos_por_lote v_slote $where";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Configura los campos de la tabla de muestras
     */
    public function columnas()
    {
        $columnas = array(
            'id_reactivo_laboratorio',
            'cantidad',
            'observacion',
            'tipo_ingreso',
            'motivo',
            'lote',
            'autorizacion'
        );
        return $columnas;
    }

    /**
     * Retorna los reactivos del laboratorio solicitado
     * @param type $idSolicitudCabecera
     * @return type
     */
    public function buscarReactivosLabSolicitados($idSolicitudCabecera)
    {
        $consulta = "SELECT
        scab.id_solicitud_cabecera,
        sreq.id_solicitud_requerimiento,
        v_saldos.id_reactivo_laboratorio,
        v_saldos.nombre,
        v_saldos.unidad_medida,
        sreq.cantidad_solicitada,
        v_saldos.tipo,
        v_saldos.total_ingreso,
        v_saldos.total_egreso,
        v_saldos.saldo
        FROM
        g_reactivos.solicitud_cabecera AS scab
        INNER JOIN g_reactivos.solicitud_requerimiento AS sreq ON scab.id_solicitud_cabecera = sreq.id_solicitud_cabecera
        INNER JOIN g_reactivos.v_saldos_reactivos_laboratorios AS v_saldos ON v_saldos.id_reactivo_laboratorio = sreq.id_reactivo_laboratorio
        WHERE scab.id_solicitud_cabecera = $idSolicitudCabecera";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Retorna el kardex del Laboratorio
     * @param type $arrayParametros
     * @return type
     */
    public function buscarKardexLaboratorios($arrayParametros){
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['id_reactivo_laboratorio']))
        {
            $arrayWhere[] = "id_reactivo_laboratorio = {$arrayParametros['id_reactivo_laboratorio']}";
        }
        if (!empty($arrayParametros['lote']))
        {
            $arrayWhere[] = "lote = '{$arrayParametros['lote']}'";
        }
        if ($arrayWhere)
        {
            $where = " WHERE " . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT
        v_k.id_saldo_laboratorio,
        v_k.id_reactivo_laboratorio,
        v_k.nombre,
        v_k.tipo,
        v_k.tipo_ingreso,
        v_k.motivo,
        v_k.cantidad,
        v_k.lote,
        v_k.fecha_registro,
        v_k.razon_salida,
        v_k.codigo_lab_muestra,
        v_k.num_resultado_analisis
        FROM " . $this->modelo->getEsquema() . ".v_kardex_laboratorio v_k
        $where
        ORDER BY
        id_saldo_laboratorio ASC";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

}
