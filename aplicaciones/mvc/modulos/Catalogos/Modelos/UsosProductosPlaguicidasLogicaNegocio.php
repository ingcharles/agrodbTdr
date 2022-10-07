<?php
/**
 * Lógica del negocio de UsosProductosPlaguicidasModelo
 *
 * Este archivo se complementa con el archivo UsosProductosPlaguicidasControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-13
 * @uses    UsosProductosPlaguicidasLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */

namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class UsosProductosPlaguicidasLogicaNegocio implements IModelo
{

    private $modeloUsosProductosPlaguicidas = null;


    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloUsosProductosPlaguicidas = new UsosProductosPlaguicidasModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(array $datos)
    {
        $tablaModelo = new UsosProductosPlaguicidasModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdUso() != null && $tablaModelo->getIdUso() > 0) {
            return $this->modeloUsosProductosPlaguicidas->actualizar($datosBd, $tablaModelo->getIdUso());
        } else {
            unset($datosBd["id_uso"]);
            return $this->modeloUsosProductosPlaguicidas->guardar($datosBd);
        }
    }

    /**
     * Borra el registro actual
     * @param string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        $this->modeloUsosProductosPlaguicidas->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return UsosProductosPlaguicidasModelo
     */
    public function buscar($id)
    {
        return $this->modeloUsosProductosPlaguicidas->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloUsosProductosPlaguicidas->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloUsosProductosPlaguicidas->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarUsosProductosPlaguicidas()
    {
        $consulta = "SELECT * FROM " . $this->modeloUsosProductosPlaguicidas->getEsquema() . ". usos_productos_plaguicidas";
        return $this->modeloUsosProductosPlaguicidas->ejecutarSqlNativo($consulta);
    }

}
