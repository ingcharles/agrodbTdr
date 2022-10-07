<?php

/**
 * Lógica del negocio de  ServiciosModelo
 *
 * Este archivo se complementa con el archivo   ServiciosControlador.
 *
 * @author DATASTAR
 * @uses       ServiciosLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;

class ServiciosLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {

        $this->modelo = new ServiciosModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new ServiciosModelo($datos);
        if ($tablaModelo->getFkIdServicio() == 0)
        {
            $datos["fk_id_servicio"] = null;
        }
        if ($tablaModelo->getIdServicioGuia() == null | $tablaModelo->getIdServicioGuia() == "")
        {
            $datos["id_servicio_guia"] = null;
        }
        if ($tablaModelo->getIdServicio() != null && $tablaModelo->getIdServicio() > 0)
        {
            //controlar que no sea padre de si mismo
            if ($datos["fk_id_servicio"] == $datos["id_servicio"])
                unset($datos["fk_id_servicio"]);
            return $this->modelo->actualizar($datos, $tablaModelo->getIdServicio());
        } else
        {
            unset($datos["id_servicio"]);
            return $this->modelo->guardar($datos);
        }
    }

    /**
     * Ejecuta una funcion para copiar la rama de un arbol recursivo
     * @param array $datos
     */
    public function guardarCopia(Array $datos)
    {
        $tablaModelo = new ServiciosModelo($datos);
        $idPadre = null;
        if ($tablaModelo->getFkIdServicio() > 0)
        {
            $idPadre = $tablaModelo->getFkIdServicio();
        }

        $query = "select g_laboratorios.f_copiar_item_servicios(" . $idPadre
                . "," . $tablaModelo->getIdDireccion() . "," . $tablaModelo->getIdLaboratorio() . ",0"
                . "," . $tablaModelo->getIdServicio() . ");";
        $this->modelo->ejecutarSqlNativo($query);
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
     * @return ServiciosModelo
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
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarListaParametrosTree($arrayParametros = null, $order = null, $count = null, $offset = null)
    {
        if (count($arrayParametros) > 0)
        {
            $arrayWhere = array();
            if (isset($arrayParametros['id_laboratorio']))
            {
                if ($arrayParametros['codigo'] != "")
                    $arrayWhere[] = " codigo = '{$arrayParametros['codigo']}'";
                else
                    $arrayWhere[] = " nivel = 0";
            }
            $where = implode(' AND ', $arrayWhere);
        }
        return $this->modelo->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarServicios($idLaboratorio = null)
    {

        //  echo $where;
        if ($idLaboratorio != null)
        {
            $where = "id_laboratorio=" . $idLaboratorio . " AND estado='ACTIVO' AND nivel=0 ORDER BY orden ASC";
        } else
        {
            $where = "estado='ACTIVO' AND nivel=0 ORDER BY orden ASC";
        }

        return $this->modelo->buscarLista($where);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarServiciosJoinGuia($idLaboratorio = null)
    {
        $consulta = "SELECT 
        lser.id_servicio,
        lser.nombre,
        fser.valor 
        FROM g_laboratorios.servicios lser
        JOIN g_financiero.servicios fser ON lser.id_servicio_guia = fser.id_servicio
        WHERE lser.id_laboratorio = $idLaboratorio AND lser.estado='ACTIVO'
        ORDER BY orden";
        return $this->modelo->ejecutarConsulta($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarAnalisis($idServicio = null)
    {
        $where = "";
        if ($idServicio != null)
        {
            $where = "fk_id_servicio=" . $idServicio . " AND estado='ACTIVO' AND tipo IN('INDIVIDUAL','PAQUETE') ORDER BY nombre ASC";
        } else
        {
            $where = "estado='ACTIVO' AND tipo IN('INDIVIDUAL','PAQUETE') ORDER BY nombre ASC";
        }
        return $this->modelo->buscarLista($where);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarProcedimiento($idAnalisis = null)
    {
        $where = "";
        if ($idAnalisis != null)
        {
            $where = "fk_id_servicio=" . $idAnalisis . " AND estado='ACTIVO'";
        } else
        {
            $where = "estado='ACTIVO'";
        }
        return $this->modelo->buscarLista($where);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarServiciosHijos($fkIdServicio = null)
    {
        $where = "";
        if ($fkIdServicio != null)
        {
            $where = "fk_id_servicio=" . $fkIdServicio . " AND estado='ACTIVO'";
        } else
        {
            $where = "estado='ACTIVO'";
        }
        return $this->modelo->buscarLista($where, 'orden');
    }

    public function buscarProcedimientoTiempoRespuesta($idServicio = null)
    {
        $consulta = "SELECT
        g_laboratorios.servicios.id_servicio,
        g_laboratorios.servicios.nombre,
        g_laboratorios.tiempos_respuesta.tiempo_respuesta
        FROM
        g_laboratorios.servicios
        INNER JOIN g_laboratorios.tiempos_respuesta ON g_laboratorios.servicios.id_servicio = g_laboratorios.tiempos_respuesta.id_servicio
        WHERE g_laboratorios.servicios.id_servicio = $idServicio";
        return $this->modelo->ejecutarConsulta($consulta);
    }

    /**
     * Busca varios tipos de análisis de acuerdo a los id separados por una coma.
     * Ej $idServicios= 5,6,8,56
     *
     * @param array $filtro
     * @return array|ResultSet
     */
    public function buscarTipoAnalisis(Array $filtro)
    {
        $idServicios = implode(",", $filtro);
        $where = "id_servicio in (" . $idServicios . ") order by nombre";
        return $this->modelo->buscarLista($where);
    }

    /**
     * Busca el path del nombre del servicio segú los niveles
     * @param array $filtro
     * @return type
     */
    public function buscarPathServicio(array $filtro)
    {
        $idServicios = implode(",", $filtro);
        $consulta = "SELECT *, g_laboratorios.f_path_nom_servicio(id_servicio) as path_nombre
        FROM
        g_laboratorios.servicios
        WHERE id_servicio IN (" . $idServicios . ")";
        return $this->modelo->ejecutarConsulta($consulta);
    }
    
    /**
     * Busca los registros hijos con todos los estados ACTIVO, INACTIVO, PAQUETE
     * @param type $idPadre
     * @return type
     */
    public function buscarIdPadreTodos($idPadre = null)
    {
        if ($idPadre == null)
        {
            $where = "fk_id_servicio IS NULL order by orden";
        } else
        {
            $where = "fk_id_servicio=" . $idPadre . " order by orden";
        }
        return $this->modelo->buscarLista($where);
    }

    /**
     * Busca los registros hijos
     * @param type $idPadre
     * @return type
     */
    public function buscarIdPadre($idPadre = null)
    {
        if ($idPadre == null)
        {
            $where = "fk_id_servicio IS NULL AND estado = 'ACTIVO' order by orden";
        } else
        {
            $where = "fk_id_servicio=" . $idPadre . " AND estado = 'ACTIVO' order by orden";
        }
        return $this->modelo->buscarLista($where);
    }

    /**
     * Actualiza los niveles todos los nodos del arbol recursivo del nodo padre nivel=0
     */
    public function actualizarNivelNodos($idNodoRaiz)
    {
        $query = "select  " . $this->modelo->getEsquema() . ".f_mantenimiento_servicios('" . $idNodoRaiz . "')";
        return $this->modelo->ejecutarSqlNativo($query);
    }
    
    /**
     * 
     * @param type $idServicios
     * @return type
     */
    public function buscarServiciosPaquetes($idServicios)
    {
        $servicios = implode(',', $idServicios);
        $query = "SELECT * FROM " . $this->modelo->getEsquema() . ".f_servicios_paquete(ARRAY [$servicios])";
        return $this->modelo->ejecutarSqlNativo($query);
    }

    /**
     * Busca los servicios del laboratorio que contengan el codigo_especial
     * @param type $idLaboratorio
     * @param type $codigo
     * @return type
     */
    public function buscarServiciosCodigoEspecial($idLaboratorio, $codigo)
    {
        $consulta = "SELECT id_servicio FROM g_laboratorios.servicios
        WHERE id_laboratorio = $idLaboratorio AND codigo_especial LIKE '%$codigo%'";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
}
