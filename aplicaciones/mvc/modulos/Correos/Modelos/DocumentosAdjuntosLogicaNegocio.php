<?php
/**
 * Lógica del negocio de DocumentosAdjuntosModelo
 *
 * Este archivo se complementa con el archivo DocumentosAdjuntosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-22
 * @uses    DocumentosAdjuntosLogicaNegocio
 * @package Correos
 * @subpackage Modelos
 */
namespace Agrodb\Correos\Modelos;

use Agrodb\Correos\Modelos\IModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

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
}
