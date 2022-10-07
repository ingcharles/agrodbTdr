<?php
/**
 * Lógica del negocio de ViaAdministracionModelo
 *
 * Este archivo se complementa con el archivo ViaAdministracionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-06-23
 * @uses    ViaAdministracionLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class ViaAdministracionLogicaNegocio implements IModelo
{

    private $modeloViaAdministracion = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloViaAdministracion = new ViaAdministracionModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new ViaAdministracionModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdViaAdministracion() != null && $tablaModelo->getIdViaAdministracion() > 0) {
            return $this->modeloViaAdministracion->actualizar($datosBd, $tablaModelo->getIdViaAdministracion());
        } else {
            unset($datosBd["id_via_administracion"]);
            return $this->modeloViaAdministracion->guardar($datosBd);
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
        $this->modeloViaAdministracion->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return ViaAdministracionModelo
     */
    public function buscar($id)
    {
        return $this->modeloViaAdministracion->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloViaAdministracion->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloViaAdministracion->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarViaAdministracion()
    {
        $consulta = "SELECT * FROM " . $this->modeloViaAdministracion->getEsquema() . ". via_administracion";
        return $this->modeloViaAdministracion->ejecutarSqlNativo($consulta);
    }
}
