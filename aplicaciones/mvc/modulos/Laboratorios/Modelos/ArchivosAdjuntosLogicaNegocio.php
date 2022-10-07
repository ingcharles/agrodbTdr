<?php

/**
 * Lógica del negocio de  ArchivosAdjuntosModelo
 *
 * Este archivo se complementa con el archivo   ArchivosAdjuntosControlador.
 *
 * @author DATASTAR
 * @uses       ArchivosAdjuntosLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;

class ArchivosAdjuntosLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new ArchivosAdjuntosModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new ArchivosAdjuntosModelo($datos);
        if ($tablaModelo->getIdArchivosAdjuntos() != null && $tablaModelo->getIdArchivosAdjuntos() > 0)
        {
            return $this->modelo->actualizar($datos, $tablaModelo->getIdArchivosAdjuntos());
        } else
        {
            unset($datos["id_archivos_adjuntos"]);
            return $this->modelo->guardar($datos);
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
     * @return ArchivosAdjuntosModelo
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
    public function buscarArchivosAdjuntos()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". archivos_adjuntos";
        return $this->modelo->ejecutarConsulta($consulta);
    }

    /**
     * Columnas de la tabla archivos_adjuntos
     * @return string
     */
    public function columnas()
    {
        $columnasTipoAnalisis = array(
            'id_parametros_servicio',
            'id_detalle_solicitud',
            'nombre_archivo',
            'fecha_subido'
        );
        return $columnasTipoAnalisis;
    }

    /**
     * Buscar anexos de la solicitud
     * @param type $idSolicitud
     * @return type
     */
    public function buscarArchivosAdjuntosSolicitud($idSolicitud)
    {
        $consulta = "SELECT
        aadj.id_archivos_adjuntos,
        aadj.nombre_archivo,
        pser.nombre AS nombre_parametro
        FROM
        g_laboratorios.archivos_adjuntos AS aadj
        INNER JOIN g_laboratorios.detalle_solicitudes AS dsol ON dsol.id_detalle_solicitud = aadj.id_detalle_solicitud
        INNER JOIN g_laboratorios.parametros_servicios AS pser ON pser.id_parametros_servicio = aadj.id_parametros_servicio
        WHERE dsol.id_solicitud = $idSolicitud";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

}
