<?php

/**
 * Lógica del negocio de  CatalogosModelo
 *
 * Este archivo se complementa con el archivo   CatalogosControlador.
 *
 * @author DATASTAR
 * @uses       CatalogosLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class CatalogosLaboratoriosLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new CatalogosLaboratoriosModelo();
    }

    /**
     * Guarda el registro actual
     * 
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new CatalogosLaboratoriosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdCatalogos() != null && $tablaModelo->getIdCatalogos() > 0)
        {
            return $this->modelo->actualizar($datosBd, $tablaModelo->getIdCatalogos());
        } else
        {
            unset($datosBd["id_catalogos"]);
            return $this->modelo->guardar($datosBd);
        }
    }

    /**
     * Borra el registro actual
     * 
     * @param
     *            string Where|array $where
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
     * @param int $id
     * @return CatalogosModelo
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
     * Busca los registros de informes hijos de cada laboratorio
     * @param type $idPadre
     * @return type
     */
    public function buscarCatalogos($idPadre = null, $modulo = 'LABORATORIOS')
    {
        if ($idPadre == null)
        {
            $where = "fk_id_catalogos IS NULL AND modulo like '" . $modulo . "' order  by orden";
        } else
        {
            $where = "fk_id_catalogos=" . $idPadre . " AND modulo like'" . $modulo . "' order  by orden";
        }
        return $this->modelo->buscarLista($where);
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarListaCatalogosTree($arrayParametros = null, $order = null, $count = null, $offset = null)
    {
        if (count($arrayParametros) > 0)
        {
            $arrayWhere = array();
            if (isset($arrayParametros['nombre']))
            {
                if ($arrayParametros['nombre'] != "")
                {
                    $arrayWhere[] = "UPPER(nombre) LIKE '%" . strtoupper($arrayParametros['nombre']) . "%'";
                } else
                {
                    $arrayWhere[] = " fk_id_catalogos IS NULL";
                }
            }
            $arrayWhere[] = " modulo = '{$arrayParametros['modulo']}'";

            $where = implode(' AND ', $arrayWhere);
        }
        return $this->modelo->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Retorna los registro hijos de acuerdo al código del catálogo padre 
     * @param type $param
     */
    public function buscarHijosDeCodigo($codigo)
    {
        $consulta = "SELECT * FROM g_catalogos.catalogos_laboratorios 
        WHERE fk_id_catalogos = (SELECT id_catalogos FROM g_catalogos.catalogos_laboratorios 
        WHERE codigo = '$codigo' AND ESTADO = 'ACTIVO')";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Busca los registros de g_catalogos.unidades_medidas
     * @param type $arrayParametros
     * @return type
     */
    public function buscarCatalogosUnidadesMedidas($arrayParametros)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['clasificacion']))
        {
            if (is_array($arrayParametros['clasificacion']))
            {
                $or = array();
                foreach ($arrayParametros['clasificacion'] as $valor){
                    $or[] = " clasificacion LIKE '%{$valor}%'";
                }
                $arrayWhere[] = implode(' OR ', $or);
            } else
            {
                $arrayWhere[] = " clasificacion LIKE '%{$arrayParametros['clasificacion']}%'";
            }
        }
        if ($arrayWhere)
        {
            $where = "WHERE " . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT um.id_unidad_medida, um.nombre, um.clasificacion 
        FROM g_catalogos.unidades_medidas um
        $where 
        ORDER BY um.nombre";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

}
