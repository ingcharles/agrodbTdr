<?php
/**
 * Lógica del negocio de EnfermedadesEquinasModelo
 *
 * Este archivo se complementa con el archivo EnfermedadesEquinasControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-03-07
 * @uses    EnfermedadesEquinasLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class EnfermedadesEquinasLogicaNegocio implements IModelo
{

    private $modeloEnfermedadesEquinas = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloEnfermedadesEquinas = new EnfermedadesEquinasModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new EnfermedadesEquinasModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdEnfermedadEquino() != null && $tablaModelo->getIdEnfermedadEquino() > 0) {
            return $this->modeloEnfermedadesEquinas->actualizar($datosBd, $tablaModelo->getIdEnfermedadEquino());
        } else {
            unset($datosBd["id_enfermedad_equino"]);
            return $this->modeloEnfermedadesEquinas->guardar($datosBd);
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
        $this->modeloEnfermedadesEquinas->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return EnfermedadesEquinasModelo
     */
    public function buscar($id)
    {
        return $this->modeloEnfermedadesEquinas->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloEnfermedadesEquinas->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloEnfermedadesEquinas->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarEnfermedadesEquinas()
    {
        $consulta = "SELECT * FROM " . $this->modeloEnfermedadesEquinas->getEsquema() . ". enfermedades_equinas";
        return $this->modeloEnfermedadesEquinas->ejecutarSqlNativo($consulta);
    }
}
