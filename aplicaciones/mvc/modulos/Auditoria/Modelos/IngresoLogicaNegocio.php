<?php
/**
 * Lógica del negocio de  IngresoModelo
 *
 * Este archivo se complementa con el archivo   IngresoControlador.
 *
 * @author AGROCALIDAD
 * @fecha 2018-10-03
 * @uses       IngresoLogicaNegocio
 * @package auditoria
 * @subpackage Modelos
 */
namespace Agrodb\Auditoria\Modelos;

use Agrodb\Auditoria\Modelos\IModelo;

class IngresoLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new IngresoModelo();
    }

    /**
     * Guarda el registro actual
     * 
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new IngresoModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdIngreso() != null && $tablaModelo->getIdIngreso() > 0) {
            return $this->modelo->actualizar($datosBd, $tablaModelo->getIdIngreso());
        } else {
            unset($datosBd["id_ingreso"]);
            return $this->modelo->guardar($datosBd);
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
        $this->modelo->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return IngresoModelo
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
    public function buscarIngreso()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". ingreso";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
}
