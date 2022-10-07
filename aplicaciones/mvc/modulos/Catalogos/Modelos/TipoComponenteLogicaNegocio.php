<?php
/**
 * Lógica del negocio de TipoComponenteModelo
 *
 * Este archivo se complementa con el archivo TipoComponenteControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-06-23
 * @uses    TipoComponenteLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class TipoComponenteLogicaNegocio implements IModelo
{

    private $modeloTipoComponente = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloTipoComponente = new TipoComponenteModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new TipoComponenteModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdTipoComponente() != null && $tablaModelo->getIdTipoComponente() > 0) {
            return $this->modeloTipoComponente->actualizar($datosBd, $tablaModelo->getIdTipoComponente());
        } else {
            unset($datosBd["id_tipo_componente"]);
            return $this->modeloTipoComponente->guardar($datosBd);
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
        $this->modeloTipoComponente->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return TipoComponenteModelo
     */
    public function buscar($id)
    {
        return $this->modeloTipoComponente->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloTipoComponente->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloTipoComponente->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarTipoComponente()
    {
        $consulta = "SELECT * FROM " . $this->modeloTipoComponente->getEsquema() . ". tipo_componente";
        return $this->modeloTipoComponente->ejecutarSqlNativo($consulta);
    }
}
