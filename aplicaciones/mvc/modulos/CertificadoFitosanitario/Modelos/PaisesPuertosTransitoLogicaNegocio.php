<?php
/**
 * Lógica del negocio de PaisesPuertosTransitoModelo
 *
 * Este archivo se complementa con el archivo PaisesPuertosTransitoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-08-04
 * @uses    PaisesPuertosTransitoLogicaNegocio
 * @package CertificadoFitosanitario
 * @subpackage Modelos
 */
namespace Agrodb\CertificadoFitosanitario\Modelos;

use Agrodb\CertificadoFitosanitario\Modelos\IModelo;

class PaisesPuertosTransitoLogicaNegocio implements IModelo
{

    private $modeloPaisesPuertosTransito = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloPaisesPuertosTransito = new PaisesPuertosTransitoModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new PaisesPuertosTransitoModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdPaisPuertoTransito() != null && $tablaModelo->getIdPaisPuertoTransito() > 0) {
            return $this->modeloPaisesPuertosTransito->actualizar($datosBd, $tablaModelo->getIdPaisPuertoTransito());
        } else {
            unset($datosBd["id_pais_puerto_transito"]);
            return $this->modeloPaisesPuertosTransito->guardar($datosBd);
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
        $this->modeloPaisesPuertosTransito->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return PaisesPuertosTransitoModelo
     */
    public function buscar($id)
    {
        return $this->modeloPaisesPuertosTransito->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloPaisesPuertosTransito->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloPaisesPuertosTransito->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarPaisesPuertosTransito()
    {
        $consulta = "SELECT * FROM " . $this->modeloPaisesPuertosTransito->getEsquema() . ". paises_puertos_transito";
        return $this->modeloPaisesPuertosTransito->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * para verificar los paises y puertos de transito registrados.
     *
     * @return array|ResultSet
     */
    public function obtenerPaisesPuertosTransito($arrayParametros)
    {
        $idCertificadoFitosanitario = $arrayParametros['id_certificado_fitosanitario'];
        $idPaisTransito = $arrayParametros['id_pais_transito'];
        $idPuertoTransito = $arrayParametros['id_puerto_transito'];

        $consulta = "SELECT 
                        id_pais_puerto_transito
                        , id_certificado_fitosanitario
                        , id_pais_transito
                        , nombre_pais_transito
                        , id_puerto_transito
                        , nombre_puerto_transito
                        , id_medio_transporte_transito
                        , nombre_medio_transporte_transito
                     FROM 
                        g_certificado_fitosanitario.paises_puertos_transito
                     WHERE
                        id_certificado_fitosanitario = '" . $idCertificadoFitosanitario . "'
                        and id_pais_transito = '" . $idPaisTransito . "'
                        and id_puerto_transito = '" . $idPuertoTransito . "';";

        return $this->modeloPaisesPuertosTransito->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Consulta para obtener el nombre del país, los puertos de tránsito y medios de transporte
     * por los que pasará el envío.
     *
     * @return array|ResultSet
     */
    public function obtenerPaisPuertosTransitoXSolicitud($idSolicitud)
    {
        $consulta = "SELECT
                        ppt.nombre_pais_transito, ppt.nombre_puerto_transito, ppt.nombre_medio_transporte_transito
                    FROM
                        g_certificado_fitosanitario.paises_puertos_transito ppt
                    WHERE
                        ppt.id_certificado_fitosanitario = $idSolicitud
                    ORDER BY
                        ppt.nombre_pais_transito, ppt.nombre_puerto_transito, ppt.nombre_medio_transporte_transito ASC";
        
        return $this->modeloPaisesPuertosTransito->ejecutarSqlNativo($consulta);
    }
	
}