<?php

/**
 * Lógica del negocio de  PagosModelo
 *
 * Este archivo se complementa con el archivo   PagosControlador.
 *
 * @author DATASTAR
 * @uses       PagosLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;
use Agrodb\Laboratorios\Modelos\SolicitudesLogicaNegocio;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class PagosLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new PagosModelo();
    }

    /**
     * Guarda el en la tabla pagos según depósitos ingresados en el sistema
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $this->modelo = new PagosModelo();
        $proceso = $this->modelo->getAdapter()
                ->getDriver()
                ->getConnection();
        if (!$proceso->beginTransaction())
        {
            throw new \Exception('No se pudo iniciar la transacción en: Guardar pagos');
        }
        // Validar el pago
        // Consultar el pago que debe pagar
        $lNSolicitudes = new SolicitudesLogicaNegocio();
        $totalSolicitud = $lNSolicitudes->buscarTotalSolicitud($datos['idSolicitud']);
        // Calcular el total pagos realizados
        unset($datos["id_pagos"]);
        $bancos = $datos['idBanco'];
        $totalPagoRegistrar = 0;
        foreach ($bancos as $key => $value)
        {
            $totalPagoRegistrar+=$datos['valor'][$key];
        }

        if (trim($totalPagoRegistrar) != trim($totalSolicitud))
        {
            Mensajes::fallo(Constantes::VALIDAR_TOTAL_PAGO." | ".$totalPagoRegistrar."=".$totalSolicitud);
            throw new \Exception(Constantes::VALIDAR_TOTAL_PAGO." | ".$totalPagoRegistrar."=".$totalSolicitud);
        } else
        {
            // Registrar los pagos
            foreach ($bancos as $key => $value)
            {
                $pago = array();
                $pago = array(
                    'id_cuenta_bancaria' => $datos['idCuenta'][$key],
                    'id_banco' => $datos['idBanco'][$key],
                    'id_solicitud' => $datos['idSolicitud'],
                    'numero_deposito' => $datos['deposito'][$key],
                    'fecha_deposito' => $datos['fecha'][$key],
                    'valor_depositado' => $datos['valor'][$key],
                    'porcentaje_iva' => Constantes::IVA
                );
                $this->modelo->guardar($pago);
            }
            // Actualizar datos de la solicitud
            $datosSolicitud = array(
                'estado' => Constantes::estado_SO()->EN_PROCESO
            );
            $statement = $this->modelo->getAdapter()
                    ->getDriver()
                    ->createStatement();
            $sqlActualizar = $this->modelo->actualizarSql('solicitudes', $this->modelo->getEsquema());
            $sqlActualizar->set($datosSolicitud);
            $sqlActualizar->where(array('id_solicitud' => $datos['idSolicitud']));
            $sqlActualizar->prepareStatement($this->modelo->getAdapter(), $statement);
            $statement->execute();

            // Activar la orden
            $datosOrden = array(
                'fecha_activacion' => date('Y-m-d'),
                'estado' => Constantes::estado_OT()->ACTIVA
            );
            $statement = $this->modelo->getAdapter()
                    ->getDriver()
                    ->createStatement();
            $sqlActualizar = $this->modelo->actualizarSql('ordenes_trabajos', $this->modelo->getEsquema());
            $sqlActualizar->set($datosOrden);
            $sqlActualizar->where(array('id_orden_trabajo' => $datos['idOrdenTrabajo']));
            $sqlActualizar->prepareStatement($this->modelo->getAdapter(), $statement);
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
     * @return PagosModelo
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
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarPagos()
    {
        $consulta = "SELECT * FROM pagos";
        return $this->modelo->ejecutarConsulta($consulta);
    }

    /**
     * Ejecuta la funcion para saber si el pago está registrado en las tablas
     * g_laboratorios.pagos y/o g_financiero.detalle_forma_pago
     * la función retorna un valor t=true (existe un deposito) o f=false (no existe el deposito)
     * @param type $ciRuc
     * @return type
     */
    public function verficarDeposito($idBanco, $numeroDeposito)
    {
        $consulta = "SELECT f_verificar_deposito AS existe FROM g_laboratorios.f_verificar_deposito($idBanco,'$numeroDeposito');";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
}
