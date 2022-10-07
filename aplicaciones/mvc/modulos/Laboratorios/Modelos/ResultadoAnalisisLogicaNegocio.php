<?php

/**
 * Lógica del negocio de  ResultadoAnalisisModelo
 *
 * Este archivo se complementa con el archivo   ResultadoAnalisisControlador.
 *
 * @author DATASTAR
 * @uses       ResultadoAnalisisLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;

class ResultadoAnalisisLogicaNegocio implements IModelo
{

    private $modelo = null;
    private $numResultado = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new ResultadoAnalisisModelo();
    }

    /**
     * 
     * @param array $datos
     * @return boolean
     */
    public function guardar(Array $datos)
    {
        
    }

    /**
     * 
     * @param array $datos
     * @return boolean
     */
    public function guardarTipoVertical(Array $datos)
    {
        try {
            //$this->modelo = new ResultadoAnalisisModelo($datos);
            $proceso = $this->modelo->getAdapter()
                    ->getDriver()
                    ->getConnection();
            if (!$proceso->beginTransaction())
            {
                throw new \Exception('No se pudo iniciar la transacción en: guardar análisis');
            }
            //guardar los campos
            $this->guardarAnalisisVertical($datos);
            //realizar el proceso de descuento de reactivo
            $respuesta = array();
            $idRecepcionMuestras = $datos['idRecepcionMuestras'];
            $idServicio = $datos['idServicio'];
            $idLaboratoriosProvincia = $datos['idLaboratoriosProvincia'];
            //Procesar descuento solamente cuando ingresa un nuevo analisis
            //Si requiere descontar se debe anular el registro e ingresa uno nuevo
            if ($datos['numResultado'] == '')
            {
                $r = $this->procesarDescuentoReactivo($idRecepcionMuestras, $idServicio, $idLaboratoriosProvincia);
                if ($r !== null)
                {
                    $respuesta[] = $r;
                }
            }
            $proceso->commit();
            return $respuesta;
        } catch (GuardarExcepcion $ex) {
            $proceso->rollback();
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Guarda el resultado del análisis
     * @param type $datos
     * @return boolean
     */
    private function guardarAnalisisVertical($datos)
    {
        if ($datos['numResultado'] == "")
        {
            //buscar el número de resultado
            $this->numResultado = $this->buscarNumeroResultado($datos['idRecepcionMuestras']);
        } else
        {
            $this->numResultado = $datos['numResultado'];
        }
        foreach ($datos as $key => $value)
        {
            $campo = explode('-', $key);
            $idResultado = "";
            if (count($campo) == 4)
            {
                $nombreCampos = $campo[0];
                $idRecepcionMuestra = $campo[1];
                $idCampo = $campo[2];
                $idResultado = $campo[3];
            }
            $pos1 = strpos($key, 'r_texto');
            $pos2 = strpos($key, 'r_lista');
            if ($pos1 !== FALSE)
            {
                $datosResultado = array(
                    'id_campos_resultados_inf' => $idCampo,
                    'id_recepcion_muestras' => $idRecepcionMuestra,
                    'identificador' => $datos['identificador'],
                    'resultado_analisis' => $value,
                    'tipo_informe' => $datos['tipo_informe']
                );
                $this->procesarGuardado($idResultado, $datosResultado, $this->numResultado);
            } else if ($pos2 !== FALSE) //lista
            {
                //primero eliminar los registro de los combos
                if ($datos['numResultado'] !== "")
                {
                    $this->eliminarRegistrosListas($idRecepcionMuestra, $idCampo, $this->numResultado);
                }
                foreach ((Array) $value as $val)
                {
                    if ($val !== "")
                    {
                        $idResultado = ''; //imporante!, debe ir vacío para que registre nuevamente
                        $datosResultado = array(
                            'id_campos_resultados_inf' => $val,
                            'id_recepcion_muestras' => $idRecepcionMuestra,
                            'identificador' => $datos['identificador'],
                            'resultado_analisis' => 'check',
                            'tipo_informe' => $datos['tipo_informe']
                        );
                        $this->procesarGuardado($idResultado, $datosResultado, $this->numResultado);
                    }
                }
            }
        }
        return true;
    }

    /**
     * Registra en la tabla g_laboratorios.resultado_analisis
     * @param type $idResultado
     * @param type $datos
     */
    private function procesarGuardado($idResultado, $datos, $numResultado)
    {
        $statement = $this->modelo->getAdapter()
                ->getDriver()
                ->createStatement();
        if ($idResultado === "")
        {
            $datos['num_resultado'] = $numResultado;
            $sqlInsertar = $this->modelo->guardarSql('resultado_analisis', $this->modelo->getEsquema());
            $sqlInsertar->columns($this->columnas());
            $sqlInsertar->values($datos, $sqlInsertar::VALUES_MERGE);
            $sqlInsertar->prepareStatement($this->modelo->getAdapter(), $statement);
            $statement->execute();
        } else
        {
            $sqlActualizar = $this->modelo->actualizarSql('resultado_analisis', $this->modelo->getEsquema());
            $sqlActualizar->set($datos);
            $sqlActualizar->where(array('id_resultado_analisis' => $idResultado, 'num_resultado' => $numResultado));
            $sqlActualizar->prepareStatement($this->modelo->getAdapter(), $statement);
            $statement->execute();
        }
    }

    /**
     * Procesar el descuento del reactivo automatico
     * @param type $idRecepcionMuestras
     * @param type $idServicio
     * @param type $numResultado
     * @param type $idLaboratoriosProvincia
     * @return type
     */
    public function procesarDescuentoReactivo($idRecepcionMuestras, $idServicio, $idLaboratoriosProvincia)
    {
        $consulta = "SELECT f_descontar_reactivo AS resultado FROM g_laboratorios.f_descontar_reactivo($idRecepcionMuestras,$idServicio, $this->numResultado, $idLaboratoriosProvincia)";
        $resultado = $this->modelo->ejecutarSqlNativo($consulta);
        $fila = $resultado->current();
        return $fila->resultado;
    }

    /**
     * 
     * @param array $datos
     * @return boolean
     */
    public function guardarTipoHorizontal(Array $datos)
    {
        try {
            $this->modelo = new ResultadoAnalisisModelo();
            $proceso = $this->modelo->getAdapter()
                    ->getDriver()
                    ->getConnection();
            if (!$proceso->beginTransaction())
            {
                throw new \Exception('No se pudo iniciar la transacción en: guardar análisis');
            }
            $this->guardarAnalisisHorizontal($datos);

            //realizar el proceso de descuento de reactivo
            $campoAux = $datos['campoAux'];
            $respuesta = array();
            foreach ($campoAux as $fila)
            {
                $d = explode('-', $fila);
                $idRecepcionMuestras = $d[0];
                $idServicio = $d[1];
                $idLaboratoriosProvincia = $d[2];
                $numResultado = $d[3];
                if ($numResultado == "")
                {
                    $numResultado = 1;
                }
                //por cada nuevo registro de analisis obtengo una respuesta
                $r = $this->procesarDescuentoReactivo($idRecepcionMuestras, $idServicio, $numResultado, $idLaboratoriosProvincia);
                if ($r !== null)
                {
                    $respuesta[] = $r;
                }
            }
            $proceso->commit();
            return $respuesta;
        } catch (GuardarExcepcion $ex) {
            $proceso->rollback();
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Guarda el resultado del análisis
     * @param type $datos
     * @return boolean
     */
    private function guardarAnalisisHorizontal($datos)
    {
        foreach ($datos as $key => $value)
        {
            $campo = explode('-', $key);
            $idResultado = "";
            $numResultado = "";
            if (count($campo) == 5)
            {
                $nombreCampos = $campo[0];
                $idRecepcionMuestra = $campo[1];
                $idCampo = $campo[2];   //id_campos_resultados_inf
                $idResultado = $campo[3];
                $numResultado = $campo[4];
            }
            if ($numResultado == "")
            {
                $numResultado = 1;
            }
            $pos1 = strpos($key, 'r_texto');
            $pos2 = strpos($key, 'r_lista');
            if ($pos1 !== FALSE)
            {
                $datos = array(
                    'id_campos_resultados_inf' => $idCampo,
                    'id_recepcion_muestras' => $idRecepcionMuestra,
                    'identificador' => $datos['identificador'],
                    'resultado_analisis' => $value,
                    'tipo_informe' => $datos['tipo_informe']
                );
                $this->procesarGuardado($idResultado, $datos, $numResultado);
            } else if ($pos2 !== FALSE) //lista
            {
                //primero eliminar los registro de los combos
                $this->eliminarRegistrosListas($idRecepcionMuestra, $idCampo, $numResultado);
                foreach ((Array) $value as $val)
                {
                    if ($val !== "")
                    {

                        $idResultado = ''; //imporante!, debe ir vacío para que registre nuevamente
                        $datos = array(
                            'id_campos_resultados_inf' => $val,
                            'id_recepcion_muestras' => $idRecepcionMuestra,
                            'identificador' => $datos['identificador'],
                            'resultado_analisis' => 'check',
                            'tipo_informe' => $datos['tipo_informe']
                        );
                        $this->procesarGuardado($idResultado, $datos, $numResultado);
                    }
                }
            }
        }
        return true;
    }

    /**
     * Metodo aplicado para eliminar los registros de la tabla resultado_analisis cuando los campos son combos o multiselect
     * @param type $idRecepcionMuestra
     * @param type $idCampos
     * @param type $numResultado
     * @return type
     */
    public function eliminarRegistrosListas($idRecepcionMuestra, $idCampos, $numResultado)
    {
        $consulta = "DELETE FROM g_laboratorios.resultado_analisis
        WHERE id_campos_resultados_inf IN (SELECT id_campos_resultados_inf FROM g_laboratorios.campos_resultados_informes
        WHERE fk_id_campos_resultados_inf = $idCampos) AND id_recepcion_muestras = $idRecepcionMuestra AND num_resultado = $numResultado";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Retorna el numero de resultado nuevo, es decir cuenta el numero de resultados existentes e incrementa en uno
     * @param type $idRecepcionMuestras
     * @return type
     */
    public function buscarNumeroResultado($idRecepcionMuestras)
    {
        $consulta = "SELECT MAX(num_resultado) as numero_resultado FROM g_laboratorios.resultado_analisis WHERE id_recepcion_muestras = $idRecepcionMuestras;";
        $resultado = $this->modelo->ejecutarSqlNativo($consulta);
        $fila = $resultado->current();
        $numeroResultado = $fila->numero_resultado + 1;
        return $numeroResultado;
    }

    /**
     * Columnas para guardar junto con la solicitud
     * @return string[]
     */
    public function columnas()
    {
        $columnas = array(
            'id_campos_resultados_inf',
            'id_recepcion_muestras',
            'identificador',
            'resultado_analisis',
            'tipo_informe',
            'num_resultado'
        );

        return $columnas;
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
     * @return ResultadoAnalisisModelo
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
    public function buscarResultadoAnalisis()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". resultado_analisis";
        return $this->modelo->ejecutarConsulta($consulta);
    }

    /**
     * Retorna las muestras según la orden de trabajo
     * @param type $idOrdenTrabajo
     * @param type $arrayParametros
     * @return type
     */
    public function buscarMuestrasIdoneas($idOrdenTrabajo, $arrayParametros = null)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['codigo']))
        {
            $arrayWhere[] = "UPPER(codigo_lab_muestra) LIKE '%" . strtoupper($arrayParametros['codigo']) . "%'";
        }
        if (!empty($arrayParametros['analisis']))
        {
            $arrayWhere[] = "UPPER(servicio) LIKE '%" . strtoupper($arrayParametros['analisis']) . "%'";
        }
        if (!empty($arrayParametros['estado_actual']))
        {
            if (is_array($arrayParametros['estado_actual']))
            {
                $arrayWhere[] = " estado_actual IN ('" . implode("','", $arrayParametros['estado_actual']) . "')";
            } else
            {
                $arrayWhere[] = " estado_actual = '{$arrayParametros['estado_actual']}'";
            }
        }
        if (!empty($arrayParametros['id_servicio']))
        {
            $arrayWhere[] = " rm.id_servicio = {$arrayParametros['id_servicio']}";
        }
        if (!empty($arrayParametros['derivada']))
        {
            $arrayWhere[] = " rm.derivada = '{$arrayParametros['derivada']}'";
        }
        if (!empty($arrayParametros['es_idonea']))
        {
            $arrayWhere[] = " rm.es_idonea = '{$arrayParametros['es_idonea']}'";
        }
        if ($arrayWhere)
        {
            $where = " AND " . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT
        rm.id_recepcion_muestras,
        rm.id_orden_trabajo,
        rm.id_servicio,
        ser.rama_nombre,
        ser.rama,
        rm.codigo_usu_muestra,
        rm.codigo_lab_muestra,
        rm.numero_muestra,
        rm.fecha_inicio_analisis,
        rm.estado_actual,
        rm.estado_aprobacion,
        rm.observacion_aprobacion,
        rm.nuevo_analisis,
        rm.acreditado,
        rm.contador_no_aprobado,
        (SELECT c.despliegue
                FROM g_laboratorios.campos_resultados_informes c
                WHERE c.nivel = 0 AND c.estado_registro= 'ACTIVO' AND c.id_servicio::text = ANY (string_to_array((SELECT serv.rama
                        FROM g_laboratorios.servicios serv
                        WHERE serv.id_servicio = rm.id_servicio AND tipo_campo = 'CONTENEDOR'), ',')) LIMIT 1) AS despliegue
        FROM
        g_laboratorios.recepcion_muestras AS rm
        INNER JOIN g_laboratorios.servicios ser ON ser.id_servicio = rm.id_servicio
        WHERE rm.id_orden_trabajo = $idOrdenTrabajo $where
        ORDER BY id_servicio, numero_muestra";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Retorna las muestras según la orden de trabajo
     * @param type $idOrdenTrabajo
     * @param type $arrayParametros
     * @return type
     */
    public function buscarMuestrasIdoneasResultados($idOrdenTrabajo, $arrayParametros = null)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['codigo']))
        {
            $arrayWhere[] = "UPPER(codigo_lab_muestra) LIKE '%" . strtoupper($arrayParametros['codigo']) . "%'";
        }
        if (!empty($arrayParametros['analisis']))
        {
            $arrayWhere[] = "UPPER(servicio) LIKE '%" . strtoupper($arrayParametros['analisis']) . "%'";
        }
        if (!empty($arrayParametros['estado_actual']))
        {
            if (is_array($arrayParametros['estado_actual']))
            {
                $arrayWhere[] = " estado_actual IN ('" . implode("','", $arrayParametros['estado_actual']) . "')";
            } else
            {
                $arrayWhere[] = " estado_actual = '{$arrayParametros['estado_actual']}'";
            }
        }
        if (!empty($arrayParametros['id_servicio']))
        {
            $arrayWhere[] = " rm.id_servicio = {$arrayParametros['id_servicio']}";
        }
        if ($arrayWhere)
        {
            $where = " AND " . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT
        rm.id_recepcion_muestras,
        rm.id_orden_trabajo,
        rm.id_servicio,
        ser.rama_nombre,
        ser.rama,
        rm.codigo_usu_muestra,
        rm.codigo_lab_muestra,
        rm.numero_muestra,
        rm.fecha_inicio_analisis,
        rm.estado_actual,
        rm.estado_aprobacion,
        rm.observacion_aprobacion,
        rm.nuevo_analisis,
        rm.acreditado,
        ra.num_resultado,
        ra.estado_ra,
        ra.campos,
        (SELECT c.despliegue
                FROM g_laboratorios.campos_resultados_informes c
                WHERE c.nivel = 0 AND c.estado_registro= 'ACTIVO' AND c.id_servicio::text = ANY (string_to_array((SELECT serv.rama
                        FROM g_laboratorios.servicios serv
                        WHERE serv.id_servicio = rm.id_servicio), ',')) LIMIT 1) AS despliegue
        FROM
        g_laboratorios.recepcion_muestras AS rm
        INNER JOIN g_laboratorios.servicios ser ON ser.id_servicio = rm.id_servicio
        INNER JOIN (select * FROM g_laboratorios.f_resultados_analisis($idOrdenTrabajo)) AS ra ON ra.id_recepcion_muestras = rm.id_recepcion_muestras
        WHERE rm.id_orden_trabajo = $idOrdenTrabajo $where
        ORDER BY numero_muestra, num_resultado";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    public function buscarResultadosAnalisisPorMuestra($idRecepcionMuestra)
    {
        $consulta = "select g_laboratorios.colpivot('_pivoted', 'SELECT
        rana.num_resultado,
        cra.nombre,
        rana.resultado_analisis
        FROM
        g_laboratorios.resultado_analisis AS rana
        INNER JOIN g_laboratorios.campos_resultados_informes AS cra ON cra.id_campos_resultados_inf = rana.id_campos_resultados_inf
        WHERE id_recepcion_muestras = $idRecepcionMuestra
        ',
            array['num_resultado'], array['nombre'], '#.resultado_analisis', null);

        select * from _pivoted;";
        return $this->modelo->ejecutarConsulta($consulta);
    }

    /**
     * Retorna los datos de los resultados de los análisis guardados
     * Los campos del formulario son retornados tipo JSON
     * @param type $idRecepcionMuestra
     * @param type $idServicio
     * @return type
     */
    public function buscarResultadosPorMuestra($idRecepcionMuestra, $idServicio, $estadoAnalisis = null)
    {
        $where = null;
        if ($estadoAnalisis != null)
        {
            if (is_array($estadoAnalisis))
            {
                $where = " WHERE estado_ra IN ('" . implode("','", $estadoAnalisis) . "')";
            } else
            {
                $where = " WHERE estado_ra = '$estadoAnalisis'";
            }
        }
        $consulta = "SELECT 
        rm.id_recepcion_muestras, 
        rm.estado_actual, 
        ra.num_resultado,
        ra.estado_ra,
        ra.campos
        FROM g_laboratorios.recepcion_muestras AS rm
        INNER JOIN (SELECT * FROM g_laboratorios.f_resultados_analisis_muestra($idRecepcionMuestra,$idServicio)) AS ra ON ra.id_recepcion_muestras = rm.id_recepcion_muestras
        $where 
        ORDER BY num_resultado";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Cambia a estado ANULADO los registros de la tabla resultado_analisis
     * @param type $idRecepcionMuestra
     * @param type $numResultado
     * @return type
     */
    public function anularAnalisis($idRecepcionMuestra, $numResultado)
    {
        $consulta = "UPDATE g_laboratorios.resultado_analisis SET estado_analisis = 'ANULADO' WHERE id_recepcion_muestras = $idRecepcionMuestra AND num_resultado = $numResultado";
        return $this->modelo->ejecutarConsulta($consulta);
    }

}
