<?php
/**
 * Lógica del negocio de PuertosDestinoModelo
 *
 * Este archivo se complementa con el archivo PuertosDestinoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-08-04
 * @uses    PuertosDestinoLogicaNegocio
 * @package CertificadoFitosanitario
 * @subpackage Modelos
 */
namespace Agrodb\CertificadoFitosanitario\Modelos;

use Agrodb\CertificadoFitosanitario\Modelos\IModelo;

class PuertosDestinoLogicaNegocio implements IModelo
{

    private $modeloPuertosDestino = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloPuertosDestino = new PuertosDestinoModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new PuertosDestinoModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdPuertoDestino() != null && $tablaModelo->getIdPuertoDestino() > 0) {
            return $this->modeloPuertosDestino->actualizar($datosBd, $tablaModelo->getIdPuertoDestino());
        } else {
            unset($datosBd["id_puerto_destino"]);
            return $this->modeloPuertosDestino->guardar($datosBd);
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
        $this->modeloPuertosDestino->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return PuertosDestinoModelo
     */
    public function buscar($id)
    {
        return $this->modeloPuertosDestino->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloPuertosDestino->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloPuertosDestino->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarPuertosDestino()
    {
        $consulta = "SELECT * FROM " . $this->modeloPuertosDestino->getEsquema() . ". puertos_destino";
        return $this->modeloPuertosDestino->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * para verificar los puertos del pais destino registrados.
     *
     * @return array|ResultSet
     */
    public function obtenerPuertosPaisDestino($arrayParametros)
    {
        $idCertificadoFitosanitario = $arrayParametros['id_certificado_fitosanitario'];
        $idPuertoPaisDestino = $arrayParametros['id_puerto_pais_destino'];

        $consulta = "SELECT 
                        id_puerto_destino
                        , id_certificado_fitosanitario
                        , id_puerto_pais_destino
                        , nombre_puerto_pais_destino
                    FROM 
                        g_certificado_fitosanitario.puertos_destino
                    WHERE
                        id_certificado_fitosanitario = '" . $idCertificadoFitosanitario . "'
                        and id_puerto_pais_destino = '" . $idPuertoPaisDestino . "';";

        return $this->modeloPuertosDestino->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Consulta para obtener el nombre del país y los puertos de destino por los que pasará el envío.
     *
     * @return array|ResultSet
     */
    public function obtenerPuertosPaisDestinoXSolicitud($idSolicitud)
    {
        $consulta = "SELECT 
                        cf.nombre_pais_destino, pd.nombre_puerto_pais_destino
                    FROM 
                        g_certificado_fitosanitario.puertos_destino pd
                        INNER JOIN g_certificado_fitosanitario.certificado_fitosanitario cf ON pd.id_certificado_fitosanitario = cf.id_certificado_fitosanitario
                    WHERE
                        pd.id_certificado_fitosanitario = $idSolicitud
                    ORDER BY
                        pd.nombre_puerto_pais_destino ASC";
        
        return $this->modeloPuertosDestino->ejecutarSqlNativo($consulta);
    }
	
}