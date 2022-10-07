<?php
/**
 * Lógica del negocio de ComposicionInocuidadModelo
 *
 * Este archivo se complementa con el archivo ComposicionInocuidadControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-23
 * @uses    ComposicionInocuidadLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class ComposicionInocuidadLogicaNegocio implements IModelo
{

    private $modeloComposicionInocuidad = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloComposicionInocuidad = new ComposicionInocuidadModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new ComposicionInocuidadModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdComposicion() != null && $tablaModelo->getIdComposicion() > 0) {
            return $this->modeloComposicionInocuidad->actualizar($datosBd, $tablaModelo->getIdComposicion());
        } else {
            unset($datosBd["id_composicion"]);
            return $this->modeloComposicionInocuidad->guardar($datosBd);
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
        $this->modeloComposicionInocuidad->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return ComposicionInocuidadModelo
     */
    public function buscar($id)
    {
        return $this->modeloComposicionInocuidad->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloComposicionInocuidad->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloComposicionInocuidad->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarComposicionInocuidad()
    {
        $consulta = "SELECT * FROM " . $this->modeloComposicionInocuidad->getEsquema() . ". composicion_inocuidad";
        return $this->modeloComposicionInocuidad->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Elimina todos los registros vinculados a un producto
     *
     * @return array|ResultSet
     */
    public function borrarTodo($idProducto)
    {
        $consulta = "   DELETE FROM 
                            g_catalogos.composicion_inocuidad
                        WHERE 
                            id_producto = $idProducto; ";
        
        return $this->modeloComposicionInocuidad->ejecutarSqlNativo($consulta);
    }
}