<?php
/**
 * Lógica del negocio de DestinatariosModelo
 *
 * Este archivo se complementa con el archivo DestinatariosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-22
 * @uses    DestinatariosLogicaNegocio
 * @package Correos
 * @subpackage Modelos
 */
namespace Agrodb\Correos\Modelos;

use Agrodb\Correos\Modelos\IModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class DestinatariosLogicaNegocio implements IModelo
{

    private $modeloDestinatarios = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloDestinatarios = new DestinatariosModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new DestinatariosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdDestinatario() != null && $tablaModelo->getIdDestinatario() > 0) {
            return $this->modeloDestinatarios->actualizar($datosBd, $tablaModelo->getIdDestinatario());
        } else {
            unset($datosBd["id_destinatario"]);
            return $this->modeloDestinatarios->guardar($datosBd);
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
        $this->modeloDestinatarios->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return DestinatariosModelo
     */
    public function buscar($id)
    {
        return $this->modeloDestinatarios->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloDestinatarios->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloDestinatarios->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarDestinatarios()
    {
        $consulta = "SELECT * FROM " . $this->modeloDestinatarios->getEsquema() . ". destinatarios";
        return $this->modeloDestinatarios->ejecutarSqlNativo($consulta);
    }
}
