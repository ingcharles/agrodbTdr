<?php
/**
 * Lógica del negocio de EfectosBiologicosModelo
 *
 * Este archivo se complementa con el archivo EfectosBiologicosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-06-23
 * @uses    EfectosBiologicosLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class EfectosBiologicosLogicaNegocio implements IModelo
{

    private $modeloEfectosBiologicos = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloEfectosBiologicos = new EfectosBiologicosModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new EfectosBiologicosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdEfectoBiologico() != null && $tablaModelo->getIdEfectoBiologico() > 0) {
            return $this->modeloEfectosBiologicos->actualizar($datosBd, $tablaModelo->getIdEfectoBiologico());
        } else {
            unset($datosBd["id_efecto_biologico"]);
            return $this->modeloEfectosBiologicos->guardar($datosBd);
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
        $this->modeloEfectosBiologicos->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return EfectosBiologicosModelo
     */
    public function buscar($id)
    {
        return $this->modeloEfectosBiologicos->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloEfectosBiologicos->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloEfectosBiologicos->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarEfectosBiologicos()
    {
        $consulta = "SELECT * FROM " . $this->modeloEfectosBiologicos->getEsquema() . ". efectos_biologicos";
        return $this->modeloEfectosBiologicos->ejecutarSqlNativo($consulta);
    }
}
