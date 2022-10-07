<?php

/**
 * Lógica del negocio de  CamposResultadosInformesModelo
 *
 * Este archivo se complementa con el archivo   CamposResultadosInformesControlador.
 *
 * @author DATASTAR
 * @uses       CamposResultadosInformesLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;
use Agrodb\Core\Constantes;

class CamposResultadosInformesLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new CamposResultadosInformesModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new CamposResultadosInformesModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();

        if ($tablaModelo->getFkIdCamposResultadosInf() == 0 || $tablaModelo->getFkIdCamposResultadosInf() == null)
        {
            unset($datosBd["fk_id_campos_resultados_inf"]);
        }
        if ($tablaModelo->getIdCamposResultadosInf() != null && $tablaModelo->getIdCamposResultadosInf() > 0)
        {
            if (empty($datosBd['nivel']))
            {
                $datosBd['nivel'] = 0;
            }

            return $this->modelo->actualizar($datosBd, $tablaModelo->getIdCamposResultadosInf());
        } else
        {
            unset($datosBd["id_campos_resultados_inf"]);
            unset($datosBd["codigo"]);
            $datosBd['nivel'] = 1; //Valor temporal el sistema asigna de forma automatica, no puede ser 0
            $codigo = preg_replace("/[^a-zA-Z0-9\_\-]+/", "", $datosBd['nombre']);
            $datosBd['codigo'] = strtolower(substr($codigo, 0, 15)); //Códificamos con el nombre de forma autómatica la primera vez que se registra
            //Buscamos si existe un nodo raiz caso contrario lo creamos
            $resultado = $this->buscarLista(array("id_servicio" => $tablaModelo->getIdServicio(), "estado_registro" => "ACTIVO"));

            if ($resultado->count() > 0)
            {
                return $this->modelo->guardar($datosBd);
            } else
            {

                $contenedor = $datosBd;
                $contenedor["nombre"] = Constantes::CONTENEDOR_CAMPOS_RESULTADOS;
                $contenedor["nivel"] = 0;
                $contenedor["despliegue"] = 'VERTICAL';
                $contenedor["tipo_campo"] = 'CONTENEDOR';
                $idContenedor = $this->modelo->guardar($contenedor);
                //Insertamos el campo en el nuevo contendor
                $datosBd["fk_id_campos_resultados_inf"] = $idContenedor;
                $this->modelo->guardar($datosBd);
            }
        }
    }

    /**
     * LLama a una funcion y crea una copia de toda una rama del arbol recursivo
     * @param array $datos
     */
    public function guardarCopia(Array $datos)
    {
        $tablaModelo = new CamposResultadosInformesModelo($datos);

        $query = "select g_laboratorios.f_copiar_campos_resultados_servicio(" . $tablaModelo->getIdCamposResultadosInf()
                . "," . $tablaModelo->getIdDireccion() . "," . $tablaModelo->getIdLaboratorio() . "," . $tablaModelo->getIdServicio()
                . ",0);";
        $this->modelo->ejecutarSqlNativo($query);
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
     * @return CamposResultadosInformesModelo
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
     * Busca los campos para presentar en el formulario de resultados del Analista
     * @param type $rama
     * @param type $nivel
     * @return type
     */
    public function buscarCamposResultado($rama, $nivel)
    {
        $consulta = "SELECT * FROM  g_laboratorios.campos_resultados_informes AS cr WHERE cr.id_servicio IN (" . $rama . ") AND estado_registro='ACTIVO' AND nivel=" . $nivel;

        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Busca los campo que tenga un determinado servicio
     * @param type $idServicio
     * @return type
     */
    public function buscarCamposServicio($idServicio = null)
    {
        if ($idServicio != null)
        {
            $where = "id_servicio=" . $idServicio . " AND estado_registro='ACTIVO' AND nivel=1 ORDER BY orden ASC";
        } else
        {
            $where = "estado_registro='ACTIVO' AND nivel=1 ORDER BY orden ASC";
        }
        return $this->modelo->buscarLista($where);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarCamposResultadosInformes()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". campos_resultados_inf";
        return $this->modelo->ejecutarConsulta($consulta);
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
            $where = "fk_id_campos_resultados_inf IS NULL order by orden";
        } else
        {
            $where = "fk_id_campos_resultados_inf=" . $idPadre . " order by orden";
        }
        return $this->modelo->buscarLista($where);
    }

    /**
     * Retorna los servicios que están configurados en la tabla campos_resultados_inf
     * @param type $arrayParametros
     * @return type
     */
    public function buscarServicios($arrayParametros = null)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['idDireccion']))
        {
            $arrayWhere[] = " g_laboratorios.laboratorios.fk_id_laboratorio = {$arrayParametros['idDireccion']}";
        }
        if (!empty($arrayParametros['idLaboratorio']))
        {
            $arrayWhere[] = " g_laboratorios.servicios.id_laboratorio = {$arrayParametros['idLaboratorio']}";
        }
        if ($arrayWhere)
        {
            $where = "AND" . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT
        g_laboratorios.servicios.nombre,
        g_laboratorios.campos_resultados_informes.nivel,
        g_laboratorios.campos_resultados_informes.id_servicio,
        g_laboratorios.servicios.id_laboratorio,
        g_laboratorios.laboratorios.fk_id_laboratorio,
        (SELECT g_laboratorios.f_path_nom_servicio(g_laboratorios.campos_resultados_informes.id_servicio))
        FROM
        g_laboratorios.campos_resultados_informes
        INNER JOIN g_laboratorios.servicios ON g_laboratorios.campos_resultados_informes.id_servicio = g_laboratorios.servicios.id_servicio
        INNER JOIN g_laboratorios.laboratorios ON g_laboratorios.servicios.id_laboratorio = g_laboratorios.laboratorios.id_laboratorio
        WHERE
        g_laboratorios.campos_resultados_informes.nivel = 0 $where";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Consulta de la tabla laboratorios para obtener los campos para DATOS DE LA MUESTRA
     * @param type $idPadre
     * @param type $nivelAcceso
     * @param type $codigo
     * @param type $idMuestra
     * @return type
     */
    public function camposPorServicio($idServicio, $idRecepcionMuestra, $idPadre = null)
    {
        $parametro = "";
        if ($idPadre != null)
        {
            $parametro = " AND cri.fk_id_campos_resultados_inf =$idPadre";
        } else
        {
            $parametro = " AND cri.fk_id_campos_resultados_inf IS NULL";
        }
        $consulta = "SELECT
        cri.id_campos_resultados_inf,
        cri.codigo,
        cri.tipo_campo,
        cri.nombre,
        cri.descripcion,
        cri.estado_registro,
        cri.obligatorio,
        cri.orden, 
        r.id_resultado_analisis,
        r.resultado_analisis
        FROM
        g_laboratorios.campos_resultados_informes cri
        LEFT JOIN g_laboratorios.resultado_analisis r ON r.id_campos_resultados_inf = cri.id_campos_resultados_inf AND id_recepcion_muestras=$idRecepcionMuestra
        WHERE cri.estado_registro='ACTIVO' $parametro
           AND id_servicio = $idServicio
        ORDER BY cri.orden ASC";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Retorna los registros de la configuración de los campos del formulario de resultados
     * @param type $idPadre
     * @param type $idServicio
     * @return type
     */
    public function camposPorServicioVistaPrevia($idServicio, $idPadre = null)
    {
        $parametro = "";
        if ($idPadre != null)
        {
            $parametro = " AND cri.fk_id_campos_resultados_inf =$idPadre";
        } else
        {
            $parametro = " AND cri.fk_id_campos_resultados_inf IS NULL";
        }
        $consulta = "SELECT
        cri.id_campos_resultados_inf,
        cri.codigo,
        cri.tipo_campo,
        cri.nombre,
        cri.descripcion,
        cri.estado_registro,
        cri.obligatorio,
        cri.orden
        FROM
        g_laboratorios.campos_resultados_informes cri
        WHERE cri.estado_registro='ACTIVO' $parametro
        AND id_servicio = $idServicio
        ORDER BY cri.orden ASC";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Consulta de la tabla laboratorios para obtener los campos para DATOS DE LA MUESTRA
     * @param type $idCamposResultadosInf
     * @param type $idRecepcionMuestra
     * @param type $numResultado
     * @return type
     */
    public function camposResultadosInfomes($idCamposResultadosInf, $idRecepcionMuestra, $numResultado)
    {
        if ($numResultado == NULL)
        {
            $op = " AND ra.num_resultado IS NULL";
        } else
        {
            $op = " AND ra.num_resultado = $numResultado";
        }
        $consulta = "SELECT cri.id_campos_resultados_inf, cri.tipo_campo, cri.nombre, cri.obligatorio, cri.estado_registro, cri.id_servicio, ra.id_resultado_analisis,
        ra.id_recepcion_muestras,
        ra.resultado_analisis,
        ra.num_resultado,
        ra.estado_analisis 
        FROM g_laboratorios.campos_resultados_informes AS cri
        LEFT JOIN g_laboratorios.resultado_analisis AS ra ON ra.id_campos_resultados_inf = cri.id_campos_resultados_inf AND ra.id_recepcion_muestras=$idRecepcionMuestra $op 
        WHERE cri.fk_id_campos_resultados_inf = $idCamposResultadosInf
        ORDER BY cri.orden ASC";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Llama a la función para que se depure por base de datos los niveles de los registros
     * @param type $idPadre
     * @return type
     */
    public function mantenimientoArbolCampos($idPadre)
    {
        $consulta = "select g_laboratorios.f_mantenimiento_campos_resultados_informes('" . $idPadre . "') ";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Busca los registros hijos
     * @param type $idPadre
     * @return type
     */
    public function comboCamposResultado($idPadre = null)
    {
        $consulta = "SELECT cr.id_campos_resultados_inf,
         (SELECT s.nombre FROM g_laboratorios.servicios s WHERE s.id_servicio=cr.id_servicio) AS nombre
         FROM g_laboratorios.campos_resultados_informes AS cr WHERE cr.nivel = 0  AND cr.id_laboratorio=" . $idPadre;
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Retorna los campos para el resutlado de análisis de la muestra
     * @param type $idServicio
     * @param type $idRecepcionMuestras
     * @param type $numResultado
     * @return type
     */
    public function buscarCamposParaResultado($idServicio, $idRecepcionMuestras, $numResultado)
    {
        $consulta = "SELECT cri.id_campos_resultados_inf, cri.tipo_campo, cri.nombre, cri.obligatorio, cri.estado_registro, cri.id_servicio, cri.valor_defecto 
        FROM g_laboratorios.campos_resultados_informes AS cri 
        WHERE cri.id_servicio = (SELECT(((string_to_array((SELECT rama FROM g_laboratorios.servicios ser WHERE ser.id_servicio = $idServicio)::text, ','::text))::character varying[]))[1]::INTEGER)
        AND cri.nivel = 1 AND cri.estado_registro = 'ACTIVO'
        ORDER BY cri.orden;";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Retorna los campos para el resutlado de análisis de la muestra
     * @param type $idServicio
     * @param type $idRecepcionMuestras
     * @param type $numResultado
     * @return type
     */
    public function buscarResultado($idCamposResultadosInf, $numResultado)
    {
        $consulta = "SELECT
        cri.id_campos_resultados_inf,
        cri.codigo,
        cri.tipo_campo,
        cri.nombre,
        cri.descripcion,
        cri.estado_registro,
        cri.obligatorio,
        cri.orden,
        ra.id_resultado_analisis,
        ra.resultado_analisis
        FROM
        g_laboratorios.campos_resultados_informes AS cri
        INNER JOIN g_laboratorios.resultado_analisis AS ra ON ra.id_campos_resultados_inf = cri.id_campos_resultados_inf
        WHERE ra.id_campos_resultados_inf = $idCamposResultadosInf AND  ra.num_resultado = $numResultado";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

}
