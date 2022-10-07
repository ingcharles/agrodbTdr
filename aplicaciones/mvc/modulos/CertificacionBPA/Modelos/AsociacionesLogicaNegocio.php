<?php
/**
 * Lógica del negocio de AsociacionesModelo
 *
 * Este archivo se complementa con el archivo AsociacionesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-03-23
 * @uses    AsociacionesLogicaNegocio
 * @package CertificacionBPA
 * @subpackage Modelos
 */
namespace Agrodb\CertificacionBPA\Modelos;

use Agrodb\CertificacionBPA\Modelos\IModelo;

class AsociacionesLogicaNegocio implements IModelo
{

    private $modeloAsociaciones = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloAsociaciones = new AsociacionesModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        if ($datos["id_asociacion"] === ''){
            $datos["identificador_operador"] = $_SESSION["usuario"];            
        }
        
        $tablaModelo = new AsociacionesModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        
        if ($tablaModelo->getIdAsociacion() != null && $tablaModelo->getIdAsociacion() > 0) {
            return $this->modeloAsociaciones->actualizar($datosBd, $tablaModelo->getIdAsociacion());
        } else {
            unset($datosBd["id_asociacion"]);
            return $this->modeloAsociaciones->guardar($datosBd);
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
        $this->modeloAsociaciones->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return AsociacionesModelo
     */
    public function buscar($id)
    {
        return $this->modeloAsociaciones->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloAsociaciones->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloAsociaciones->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarAsociaciones()
    {
        $consulta = "SELECT * FROM " . $this->modeloAsociaciones->getEsquema() . ". asociaciones";
        return $this->modeloAsociaciones->ejecutarSqlNativo($consulta);
    }
}
