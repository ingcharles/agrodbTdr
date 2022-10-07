<?php

/**
 * Lógica del negocio de  ActaBajaModelo
 *
 * Este archivo se complementa con el archivo   ActaBajaControlador.
 *
 * @author DATASTAR
 * @uses       ActabajaLogicaNegocio
 * @package Reactivos
 * @subpackage Modelo
 */

namespace Agrodb\Reactivos\Modelos;

use Agrodb\Reactivos\Modelos\IModelo;

class ActabajaLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new ActabajaModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new ActabajaModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdActaBaja() != null && $tablaModelo->getIdActaBaja() > 0)
        {
            if ($datosBd['estado_acta'] == 'APROBADA')
            {
                $datosBd['responsable_aprueba'] = $datos['identificador'];
            }
            return $this->modelo->actualizar($datosBd, $tablaModelo->getIdActaBaja());
        } else
        {
            unset($datosBd["id_acta_baja"]);
            return $this->modelo->guardar($datosBd);
        }
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
     * @return ActaBajaModelo
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
    public function buscarActaBaja()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". acta_baja";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Retorna los registros de la tabla g_reactivos.acta_baja
     * @param type $arrayParametros
     * @return type
     */
    public function buscarActaBajaReactivo($arrayParametros = null)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['id_laboratorio']))
        {
            $arrayWhere[] = "id_laboratorio = " . $arrayParametros['id_laboratorio'];
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
        if ($arrayWhere)
        {
            $where = " WHERE " . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT
        ab.id_acta_baja,
        ab.nombre_acta,
        ab.fecha_registro,
        ab.estado_acta,
        ab.id_saldo_laboratorio,
        ab.responsable_crea,
        fe.nombre||' '||fe.apellido AS nombre_crea,
        ab.responsable_aprueba,
        fea.nombre||' '||fea.apellido AS nombre_aprueba
        FROM
        g_reactivos.acta_baja AS ab
        JOIN g_uath.ficha_empleado fe ON fe.identificador = ab.responsable_crea
        LEFT JOIN g_uath.ficha_empleado fea ON fea.identificador = ab.responsable_aprueba
        $where ORDER BY id_acta_baja DESC";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Configura los campos de la tabla de muestras
     */
    public function columnas()
    {
        $columnas = array(
            'id_saldo_laboratorio',
            'nombre_acta',
            'contenido',
            'responsable_crea'
        );
        return $columnas;
    }

}
