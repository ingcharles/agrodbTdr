<?php

/**
 * Lógica del negocio de  LaboratoriosModelo
 *
 * Este archivo se complementa con el archivo   LaboratoriosControlador.
 *
 * @author DATASTAR
 * @uses       LaboratoriosLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;

class LaboratoriosLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new LaboratoriosModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new LaboratoriosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdLaboratorio() != null && $tablaModelo->getIdLaboratorio() > 0)
        {
            //controlar que no sea padre de si mismo
            if (isset($datosBd["fk_id_laboratorio"]))
            {
                if ($datosBd["fk_id_laboratorio"] == $datosBd["id_laboratorio"])
                {
                    unset($datosBd["fk_id_laboratorio"]);
                }
            }
            return $this->modelo->actualizar($datosBd, $tablaModelo->getIdLaboratorio());
        } else
        {
            unset($datosBd["id_laboratorio"]);
            $datosBd['codigo_campo'] = substr(preg_replace('[^a-zA-Z]', '', $datosBd['nombre']), 0, 15);
            $this->modelo->guardar($datosBd);
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
     * @return LaboratoriosModelo
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
     * Busca los laboratorios y ejecuta la funcion de mantenimiento 
     * @param type $where
     * @param type $order
     * @param type $count
     * @param type $offset
     * @return type
     */
    public function buscarListaLaboratorios($where = null, $order = null, $count = null, $offset = null)
    {
        //Antes de retornas la consulta ejecutamos la funcion de mantenimiento para actualizar los niveles del recursividad
        if (!empty($where['fk_id_laboratorio']))
        {
            $consulta = "select * from g_laboratorios.f_mantenimiento_laboratorios('" . $where['fk_id_laboratorio'] . "')";
            $this->modelo->ejecutarSqlNativo($consulta);
        }
        return $this->buscarLista($where, $order, $count, $offset);
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
    public function buscarListaParametros($arrayParametros = null, $order = null, $count = null, $offset = null)
    {
        $where = null;
        $arrayWhere[] = "nivel = 1";
        if (!empty($arrayParametros['direccion']))
            $arrayWhere[] = " fk_id_laboratorio = {$arrayParametros['direccion']}";
        if (!empty($arrayParametros['codigo']))
            $arrayWhere[] = "UPPER(codigo) LIKE '%" . strtoupper($arrayParametros['codigo']) . "%'";
        if (!empty($arrayParametros['nombre']))
            $arrayWhere[] = "UPPER(nombre) LIKE '%" . strtoupper($arrayParametros['nombre']) . "%'";
        $where = implode(' AND ', $arrayWhere);
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
            if (isset($arrayParametros['codigo']))
            {
                if ($arrayParametros['codigo'] != "")
                {
                    $arrayWhere[] = " codigo = '{$arrayParametros['codigo']}'";
                } else
                {
                    $arrayWhere[] = " nivel = 1";
                }
            }
            if (isset($arrayParametros['direccion']))
            {
                if ($arrayParametros['direccion'] != "")
                {
                    $arrayWhere[] = " fk_id_laboratorio = '{$arrayParametros['direccion']}'";
                }
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
    public function buscarLaboratorios($idDireccion = null)
    {
        if ($idDireccion != null)
        {
            $where = "fk_id_laboratorio=" . $idDireccion . " AND nivel=1";
        } else
        {
            $where = "nivel=1";
        }

        return $this->modelo->buscarLista($where, 'orden');
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarDirecciones()
    {
        $where = "nivel=0 order by orden";
        return $this->modelo->buscarLista($where);
    }

    /**
     * Consulta de la tabla laboratorios para obtener los campos para DATOS DE LA MUESTRA
     * @param type $idPadre
     * @param type $nivelAcceso
     * @param type $codigo
     * @param type $idMuestra
     * @return type
     */
    public function camposLaboratorio($idPadre, $codigo, $idMuestra = null, $usuarioInterno)
    {
        $parametro = "";
        if ($idMuestra != null)
        {
            $parametro = " and id_muestra=$idMuestra";
        } else
        {
            $parametro = " and id_muestra=null";
        }
        if ($usuarioInterno == TRUE)
        {
            $nivelAcceso = " and nivel_acceso in ('0','1')";
        } else
        {
            $nivelAcceso = " and nivel_acceso in ('0','2')";
        }
        $consulta = "select 
        lab.id_laboratorio,
        lab.fk_id_laboratorio,
        lab.codigo,
        lab.nombre,
        lab.descripcion,
        lab.tipo_campo,
        lab.obligatorio,
        lab.nivel_acceso,
        lab.visible_en,
        lab.atributos,
        lab.estado_registro,
        lab.orden,
        lab.data_linea,
        lab.rama,
        lab.codigo_ejecutable,
        valor_usuario 
        FROM g_laboratorios.laboratorios lab
        LEFT JOIN g_laboratorios.detalle_muestras dm on dm.id_laboratorio = lab.id_laboratorio $parametro
        WHERE lab.fk_id_laboratorio=$idPadre
        AND lab.codigo LIKE '%$codigo%' 
        AND lab.estado_registro = 'ACTIVO' AND lab.visible_en<>'OT'
        $nivelAcceso
        ORDER BY orden";
        return $this->modelo->ejecutarSqlNativo($consulta);
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
            $where = "fk_id_laboratorio IS NULL order  by orden";
        } else
        {
            $where = "fk_id_laboratorio=" . $idPadre . " order  by orden";
        }
        return $this->modelo->buscarLista($where);
    }

    public function buscarConfLab($idLaboratorio)
    {
        $consulta = "SELECT 
        lab.id_laboratorio,
        lab.fk_id_laboratorio,
        lab.codigo,
        lab.nombre,
        lab.descripcion,
        lab.tipo_campo,
        lab.nivel,
        lab.ultimo_nivel,
        lab.obligatorio,
        lab.nivel_acceso,
        lab.visible_en,
        lab.atributos,
        lab.estado_registro,
        lab.orden,
        lab.data_linea,
        lab.id_sistema_guia,
        lab.aprobado_por,
        lab.codigo_campo,
        lab.rama,
        lab.orientacion,
        lab.conf_orden_trabajo,
        lab.codigo_ejecutable,
        lab.estado_aprobado,
        lab.observacion_aprobacion,
        (SELECT (((string_to_array((g_laboratorios.f_path_laboratorio(lab.id_laboratorio))::text, '/'::text))::character varying[])[2])::integer) AS direccion,
        (SELECT (((string_to_array((g_laboratorios.f_path_laboratorio(lab.id_laboratorio))::text, '/'::text))::character varying[])[3])::integer) AS laboratorio
         FROM g_laboratorios.laboratorios AS lab
        WHERE lab.id_laboratorio = $idLaboratorio";
        return $this->modelo->ejecutarConsulta($consulta);
    }

    /**
     * Busca elementos de un combo
     * @param type $idPadre
     * @return type
     */
    public function buscarItemCombo($idPadre)
    {

        $where = "fk_id_laboratorio=" . $idPadre . " AND estado_registro='ACTIVO' order  by orden";

        return $this->modelo->buscarLista($where);
    }

    /**
     * 
     * @param type $idLaboratoriosProvincia
     */
    public function buscarDatosLaboratorio($idLaboratoriosProvincia)
    {
        $consulta = "SELECT
        lprov.id_laboratorios_provincia,
        lab.atributos
        FROM
        g_laboratorios.laboratorios_provincia AS lprov
        INNER JOIN g_laboratorios.laboratorios AS lab ON lab.id_laboratorio = lprov.id_laboratorio
        WHERE lprov.id_laboratorios_provincia=$idLaboratoriosProvincia";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta la función  g_laboratorios.f_configurar_laboratorio
     * para terminar de configurar los laboratorios
     * @param type $idLaboratorio
     * @return type
     */
    public function actualizarConfiguracion($idLaboratorio)
    {
        $consulta = "SELECT   g_laboratorios.f_configurar_laboratorio(" . $idLaboratorio . ")";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

}
