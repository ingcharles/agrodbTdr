<?php
/**
 * Lógica del negocio de RegistroDecesosModelo
 *
 * Este archivo se complementa con el archivo RegistroDecesosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-03-08
 * @uses    RegistroDecesosLogicaNegocio
 * @package PasaporteEquino
 * @subpackage Modelos
 */
namespace Agrodb\PasaporteEquino\Modelos;

use Agrodb\PasaporteEquino\Modelos\IModelo;

class RegistroDecesosLogicaNegocio implements IModelo
{

    private $modeloRegistroDecesos = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloRegistroDecesos = new RegistroDecesosModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new RegistroDecesosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdRegistro() != null && $tablaModelo->getIdRegistro() > 0) {
            return $this->modeloRegistroDecesos->actualizar($datosBd, $tablaModelo->getIdRegistro());
        } else {
            unset($datosBd["id_registro"]);
            return $this->modeloRegistroDecesos->guardar($datosBd);
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
        $this->modeloRegistroDecesos->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return RegistroDecesosModelo
     */
    public function buscar($id)
    {
        return $this->modeloRegistroDecesos->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloRegistroDecesos->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloRegistroDecesos->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarRegistroDecesos()
    {
        $consulta = "SELECT * FROM " . $this->modeloRegistroDecesos->getEsquema() . ". registro_decesos";
        return $this->modeloRegistroDecesos->ejecutarSqlNativo($consulta);
    }
}
