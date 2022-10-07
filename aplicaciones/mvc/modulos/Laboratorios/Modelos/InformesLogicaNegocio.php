<?php

/**
 * Lógica del negocio de  InformesModelo
 *
 * Este archivo se complementa con el archivo   InformesControlador.
 *
 * @author DATASTAR
 * @uses       InformesLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;

class InformesLogicaNegocio implements IModelo {

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct() {
        $this->modelo = new InformesModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos) {
        $tablaModelo = new InformesModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if (isset($datosBd["fk_id_informe"]) && $datosBd["fk_id_informe"] <= 0) {
            $datosBd["fk_id_informe"] = null;
        }
        if (isset($datosBd["fk_id_laboratorio"]) && $datosBd["fk_id_laboratorio"] <= 0) {
            unset($datosBd["fk_id_laboratorio"]);
        }
        if (isset($datosBd["id_campos_resultados_inf"]) && $datosBd["id_campos_resultados_inf"] <= 0) {
            unset($datosBd["id_campos_resultados_inf"]);
        }
        if ($tablaModelo->getIdInforme() != null && $tablaModelo->getIdInforme() > 0) {
            unset($datosBd["aprobado_por"]);
            
            return $this->modelo->actualizar($datosBd, $tablaModelo->getIdInforme());
        } else {
            unset($datosBd["id_informe"]);
            unset($datosBd["aprobado_por"]);
            return $this->modelo->guardar($datosBd);
        }
    }

    /**
     * Ejecuta una funcion para copiar la rama de un arbol recursivo
     * @param array $datos
     */
    public function guardarCopia(Array $datos) {
        $tablaModelo = new InformesModelo($datos);
        $idPadre = null;
        if ($tablaModelo->getFkIdInforme() > 0) {
            $idPadre = $tablaModelo->getFkIdInforme();
        }

        $query = "select g_laboratorios.f_copiar_informes(" . $idPadre
                . "," . $tablaModelo->getIdDireccion() . "," . $tablaModelo->getIdLaboratorio() . ",0);";
        $this->modelo->ejecutarSqlNativo($query);
    }

    /**
     * Borra el registro actual
     * @param string Where|array $where
     * @return int
     */
    public function borrar($id) {
        $this->modelo->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param  int $id
     * @return InformesModelo
     */
    public function buscar($id) {
        return $this->modelo->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo() {
        return $this->modelo->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null) {
        return $this->modelo->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Busca los registros de informes hijos de cada laboratorio
     * @param type $idPadre
     * @return type
     */
    public function buscarInformes($idPadre = null) {
        if ($idPadre == null) {
            $where = "fk_id_laboratorio IS NULL order  by orden";
        } else {
            $where = "fk_id_laboratorio=" . $idPadre . " order  by orden";
        }
        return $this->modelo->buscarLista($where);
    }

    /**
     * Busca los registros de informes hijos de cada laboratorio
     * @param type $idPadre
     * @return type
     */
    public function buscarIdPadre($idPadre = null) {
        if ($idPadre == null) {
            $where = "fk_id_informe IS NULL order  by orden";
        } else {
            $where = "fk_id_informe=" . $idPadre . " order  by orden";
        }
        return $this->modelo->buscarLista($where);
    }

    /**
     * Ejecuta una función de postgres, para actaulizar los niveles y campos de la orden de trabajo
     * @param type $nodoRaiz
     * @return type
     */
    public function mantenimientoArbol($nodoRaiz = null) {
        $consulta = "select g_laboratorios.f_mantenimiento_informes('" . $nodoRaiz . "');";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
    /**
     * Servicios donde estan los campos
     * @param type $idPadre
     * @return type
     */
    public function buscarListaResultado($idPadre = null) {
        $consulta = "SELECT * FROM g_laboratorios.informes WHERE codigo='SERVICIO' "
                . "AND fk_id_informe=(SELECT id_informe FROM g_laboratorios.informes WHERE fk_id_informe = ".$idPadre." AND codigo like 'RESULTADO');";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

}
