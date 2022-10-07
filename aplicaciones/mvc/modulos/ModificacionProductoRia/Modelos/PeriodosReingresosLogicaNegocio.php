<?php
/**
 * Lógica del negocio de PeriodosReingresosModelo
 *
 * Este archivo se complementa con el archivo PeriodosReingresosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-21
 * @uses    PeriodosReingresosLogicaNegocio
 * @package ModificacionProductoRia
 * @subpackage Modelos
 */

namespace Agrodb\ModificacionProductoRia\Modelos;

use Agrodb\ModificacionProductoRia\Modelos\IModelo;

class PeriodosReingresosLogicaNegocio implements IModelo
{

    private $modeloPeriodosReingresos = null;


    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloPeriodosReingresos = new PeriodosReingresosModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(array $datos)
    {
        $tablaModelo = new PeriodosReingresosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdPeriodoReingreso() != null && $tablaModelo->getIdPeriodoReingreso() > 0) {
            return $this->modeloPeriodosReingresos->actualizar($datosBd, $tablaModelo->getIdPeriodoReingreso());
        } else {
            unset($datosBd["id_periodo_reingreso"]);
            return $this->modeloPeriodosReingresos->guardar($datosBd);
        }
    }

    /**
     * Borra el registro actual
     * @param string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        $this->modeloPeriodosReingresos->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return PeriodosReingresosModelo
     */
    public function buscar($id)
    {
        return $this->modeloPeriodosReingresos->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloPeriodosReingresos->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloPeriodosReingresos->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarPeriodosReingresos()
    {
        $consulta = "SELECT * FROM " . $this->modeloPeriodosReingresos->getEsquema() . ". periodos_reingresos";
        return $this->modeloPeriodosReingresos->ejecutarSqlNativo($consulta);
    }

}
