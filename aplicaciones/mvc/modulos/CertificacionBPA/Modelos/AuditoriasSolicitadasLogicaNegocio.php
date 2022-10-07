<?php
/**
 * Lógica del negocio de AuditoriasSolicitadasModelo
 *
 * Este archivo se complementa con el archivo AuditoriasSolicitadasControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-03-23
 * @uses    AuditoriasSolicitadasLogicaNegocio
 * @package CertificacionBPA
 * @subpackage Modelos
 */
namespace Agrodb\CertificacionBPA\Modelos;

use Agrodb\CertificacionBPA\Modelos\IModelo;

class AuditoriasSolicitadasLogicaNegocio implements IModelo
{

    private $modeloAuditoriasSolicitadas = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloAuditoriasSolicitadas = new AuditoriasSolicitadasModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new AuditoriasSolicitadasModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();

        if ($tablaModelo->getIdAuditoriaSolicitada() != null && $tablaModelo->getIdAuditoriaSolicitada() > 0) {
            return $this->modeloAuditoriasSolicitadas->actualizar($datosBd, $tablaModelo->getIdAuditoriaSolicitada());
        } else {
            unset($datosBd["id_auditoria_solicitada"]);
            return $this->modeloAuditoriasSolicitadas->guardar($datosBd);
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
        $this->modeloAuditoriasSolicitadas->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return AuditoriasSolicitadasModelo
     */
    public function buscar($id)
    {
        return $this->modeloAuditoriasSolicitadas->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloAuditoriasSolicitadas->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloAuditoriasSolicitadas->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarAuditoriasSolicitadas()
    {
        $consulta = "SELECT * FROM " . $this->modeloAuditoriasSolicitadas->getEsquema() . ". auditorias_solicitadas";
        return $this->modeloAuditoriasSolicitadas->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Cambiar el estado de los registros de una solicitud a Inactivo.
     *
     * @return array|ResultSet
     */
    public function desactivarAuditorias($arrayParametros)
    {
        $consulta = "  UPDATE
                        	g_certificacion_bpa.auditorias_solicitadas
                        SET
                        	estado = 'Inactivo'
                        WHERE
                            id_solicitud = ". $arrayParametros['id_solicitud'] .";";

        return $this->modeloAuditoriasSolicitadas->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Cambia el estado de los registros de una solicitud a Inactivo de acuerdo al tipo de auditoría requerida.
     *
     * @return array|ResultSet
     */
    public function desactivarAuditoriasXTipo($arrayParametros)
    {
        $consulta = "  UPDATE
                        	g_certificacion_bpa.auditorias_solicitadas
                        SET
                        	estado = 'Inactivo'
                        WHERE
                            id_solicitud = ". $arrayParametros['id_solicitud'] ." and
                            id_tipo_auditoria not in (". $arrayParametros['id_auditorias_solicitadas'] .");";
        
        return $this->modeloAuditoriasSolicitadas->ejecutarSqlNativo($consulta);
    }
}