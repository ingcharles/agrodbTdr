<?php
/**
 * Lógica del negocio de AnexosPecuariosModelo
 *
 * Este archivo se complementa con el archivo AnexosPecuariosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-06-23
 * @uses    AnexosPecuariosLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class AnexosPecuariosLogicaNegocio implements IModelo
{

    private $modeloAnexosPecuarios = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloAnexosPecuarios = new AnexosPecuariosModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new AnexosPecuariosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdAnexoPecuario() != null && $tablaModelo->getIdAnexoPecuario() > 0) {
            return $this->modeloAnexosPecuarios->actualizar($datosBd, $tablaModelo->getIdAnexoPecuario());
        } else {
            unset($datosBd["id_anexo_pecuario"]);
            return $this->modeloAnexosPecuarios->guardar($datosBd);
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
        $this->modeloAnexosPecuarios->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return AnexosPecuariosModelo
     */
    public function buscar($id)
    {
        return $this->modeloAnexosPecuarios->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloAnexosPecuarios->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloAnexosPecuarios->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarAnexosPecuarios()
    {
        $consulta = "SELECT * FROM " . $this->modeloAnexosPecuarios->getEsquema() . ". anexos_pecuarios";
        return $this->modeloAnexosPecuarios->ejecutarSqlNativo($consulta);
    }
}
