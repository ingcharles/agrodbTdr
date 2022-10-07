<?php
/**
 * Lógica del negocio de CategoriaEspecieModelo
 *
 * Este archivo se complementa con el archivo CategoriaEspecieControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-03-22
 * @uses    CategoriaEspecieLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class CategoriaEspecieLogicaNegocio implements IModelo
{

    private $modeloCategoriaEspecie = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloCategoriaEspecie = new CategoriaEspecieModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new CategoriaEspecieModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdCategoriaEspecie() != null && $tablaModelo->getIdCategoriaEspecie() > 0) {
            return $this->modeloCategoriaEspecie->actualizar($datosBd, $tablaModelo->getIdCategoriaEspecie());
        } else {
            unset($datosBd["id_categoria_especie"]);
            return $this->modeloCategoriaEspecie->guardar($datosBd);
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
        $this->modeloCategoriaEspecie->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return CategoriaEspecieModelo
     */
    public function buscar($id)
    {
        return $this->modeloCategoriaEspecie->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloCategoriaEspecie->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloCategoriaEspecie->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarCategoriaEspecie()
    {
        $consulta = "SELECT * FROM " . $this->modeloCategoriaEspecie->getEsquema() . ". categoria_especie";
        return $this->modeloCategoriaEspecie->ejecutarSqlNativo($consulta);
    }
}
