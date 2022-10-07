<?php
/**
 * Lógica del negocio de RegistroMovimientosModelo
 *
 * Este archivo se complementa con el archivo RegistroMovimientosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-03-22
 * @uses    RegistroMovimientosLogicaNegocio
 * @package PasaporteEquino
 * @subpackage Modelos
 */
namespace Agrodb\PasaporteEquino\Modelos;

use Agrodb\PasaporteEquino\Modelos\IModelo;

class RegistroMovimientosLogicaNegocio implements IModelo
{

    private $modeloRegistroMovimientos = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloRegistroMovimientos = new RegistroMovimientosModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new RegistroMovimientosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdRegistro() != null && $tablaModelo->getIdRegistro() > 0) {
            return $this->modeloRegistroMovimientos->actualizar($datosBd, $tablaModelo->getIdRegistro());
        } else {
            unset($datosBd["id_registro"]);
            return $this->modeloRegistroMovimientos->guardar($datosBd);
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
        $this->modeloRegistroMovimientos->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return RegistroMovimientosModelo
     */
    public function buscar($id)
    {
        return $this->modeloRegistroMovimientos->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloRegistroMovimientos->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloRegistroMovimientos->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarRegistroMovimientos()
    {
        $consulta = "SELECT * FROM " . $this->modeloRegistroMovimientos->getEsquema() . ". registro_movimientos";
        return $this->modeloRegistroMovimientos->ejecutarSqlNativo($consulta);
    }
}
