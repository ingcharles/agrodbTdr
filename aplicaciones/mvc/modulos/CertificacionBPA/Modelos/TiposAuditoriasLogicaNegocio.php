<?php
/**
 * Lógica del negocio de TiposAuditoriasModelo
 *
 * Este archivo se complementa con el archivo TiposAuditoriasControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-03-23
 * @uses    TiposAuditoriasLogicaNegocio
 * @package CertificacionBPA
 * @subpackage Modelos
 */
namespace Agrodb\CertificacionBPA\Modelos;

use Agrodb\CertificacionBPA\Modelos\IModelo;

class TiposAuditoriasLogicaNegocio implements IModelo
{

    private $modeloTiposAuditorias = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloTiposAuditorias = new TiposAuditoriasModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new TiposAuditoriasModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdTipoAuditoria() != null && $tablaModelo->getIdTipoAuditoria() > 0) {
            return $this->modeloTiposAuditorias->actualizar($datosBd, $tablaModelo->getIdTipoAuditoria());
        } else {
            unset($datosBd["id_tipo_auditoria"]);
            return $this->modeloTiposAuditorias->guardar($datosBd);
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
        $this->modeloTiposAuditorias->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return TiposAuditoriasModelo
     */
    public function buscar($id)
    {
        return $this->modeloTiposAuditorias->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloTiposAuditorias->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloTiposAuditorias->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarTiposAuditorias()
    {
        $consulta = "SELECT * FROM " . $this->modeloTiposAuditorias->getEsquema() . ". tipos_auditorias";
        return $this->modeloTiposAuditorias->ejecutarSqlNativo($consulta);
    }
}
