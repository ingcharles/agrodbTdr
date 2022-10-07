<?php
/**
 * Lógica del negocio de DocumentosAdjuntosModelo
 *
 * Este archivo se complementa con el archivo DocumentosAdjuntosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-08-04
 * @uses    DocumentosAdjuntosLogicaNegocio
 * @package CertificadoFitosanitario
 * @subpackage Modelos
 */
namespace Agrodb\CertificadoFitosanitario\Modelos;

use Agrodb\CertificadoFitosanitario\Modelos\IModelo;

class DocumentosAdjuntosLogicaNegocio implements IModelo
{

    private $modeloDocumentosAdjuntos = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloDocumentosAdjuntos = new DocumentosAdjuntosModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    { 
        $datos['fecha_creacion_adjunto'] = 'now()';
        
        $tablaModelo = new DocumentosAdjuntosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        
        if ($tablaModelo->getIdDocumentoAdjunto() != null && $tablaModelo->getIdDocumentoAdjunto() > 0) {
            return $this->modeloDocumentosAdjuntos->actualizar($datosBd, $tablaModelo->getIdDocumentoAdjunto());
        } else {
            unset($datosBd["id_documento_adjunto"]);
            return $this->modeloDocumentosAdjuntos->guardar($datosBd);
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
        $this->modeloDocumentosAdjuntos->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return DocumentosAdjuntosModelo
     */
    public function buscar($id)
    {
        return $this->modeloDocumentosAdjuntos->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloDocumentosAdjuntos->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloDocumentosAdjuntos->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarDocumentosAdjuntos()
    {
        $consulta = "SELECT * FROM " . $this->modeloDocumentosAdjuntos->getEsquema() . ". documentos_adjuntos";
        return $this->modeloDocumentosAdjuntos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Consulta para obtener los documentos adjuntos remitidos con la solicitud.
     *
     * @return array|ResultSet
     */
    public function obtenerDocumentosAdjuntosXSolicitud($arrayParametros)
    {
        $consulta = "SELECT
                        da.*
                    FROM
                        g_certificado_fitosanitario.documentos_adjuntos da
                    WHERE
                        da.id_certificado_fitosanitario = ".$arrayParametros['id_solicitud']." and
                        da.estado_adjunto in ('activo', 'Activo') and
                        da.tipo_adjunto in(".$arrayParametros['tipo_adjunto'].")
                    ORDER BY
                        da.tipo_adjunto DESC";
        
        //echo $consulta;
        return $this->modeloDocumentosAdjuntos->ejecutarSqlNativo($consulta);
    }

}