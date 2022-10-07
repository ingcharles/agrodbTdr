<?php

/**
 * Lógica del negocio de  DetalleMuestrasModelo
 *
 * Este archivo se complementa con el archivo   DetalleMuestrasControlador.
 *
 * @author DATASTAR
 * @uses       DetalleMuestrasLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;

class DetalleMuestrasLogicaNegocio implements IModelo {

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct() {
        $this->modelo = new DetalleMuestrasModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos) {
        $tablaModelo = new DetalleMuestrasModelo($datos);
        if ($tablaModelo->getIdDetalleMuestra() != null && $tablaModelo->getIdDetalleMuestra() > 0) {
            return $this->modelo->actualizar($datos, $tablaModelo->getIdDetalleMuestra());
        } else {
            unset($datos["id_detalle_muestra"]);
            return $this->modelo->guardar($datos);
        }
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
     * @return DetalleMuestrasModelo
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
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarDetalleMuestras() {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". detalle_muestras";
        return $this->modelo->ejecutarConsulta($consulta);
    }

    /**
     * Columnas para guardar junto con la solicitud
     * @return string[]
     */
    public function columnas() {
        $columnas = array(
            'id_laboratorio',
            'id_muestra',
            'valor_usuario',
            'codigo_agrupa'
        );

        return $columnas;
    }

}
