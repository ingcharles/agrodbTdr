<?php
/**
 * Lógica del negocio de TitularesProductosModelo
 *
 * Este archivo se complementa con el archivo TitularesProductosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-13
 * @uses    TitularesProductosLogicaNegocio
 * @package ModificacionProductoRia
 * @subpackage Modelos
 */
namespace Agrodb\ModificacionProductoRia\Modelos;
use Agrodb\ModificacionProductoRia\Modelos\IModelo;

class TitularesProductosLogicaNegocio implements IModelo
{

    private $modeloTitularesProductos = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct ()
    {
        $this->modeloTitularesProductos = new TitularesProductosModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar (Array $datos)
    {
        $tablaModelo = new TitularesProductosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdTitularProducto() != null &&
                $tablaModelo->getIdTitularProducto() > 0) {
            return $this->modeloTitularesProductos->actualizar($datosBd,
                    $tablaModelo->getIdTitularProducto());
        } else {
            unset($datosBd["id_titular_producto"]);
            return $this->modeloTitularesProductos->guardar($datosBd);
        }
    }

    /**
     * Borra el registro actual
     *
     * @param
     *            string Where|array $where
     * @return int
     */
    public function borrar ($id)
    {
        $this->modeloTitularesProductos->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return TitularesProductosModelo
     */
    public function buscar ($id)
    {
        return $this->modeloTitularesProductos->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo ()
    {
        return $this->modeloTitularesProductos->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista ($where = null, $order = null, $count = null,
            $offset = null)
    {
        return $this->modeloTitularesProductos->buscarLista($where, $order,
                $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarTitularesProductos ()
    {
        $consulta = "SELECT * FROM " .
                $this->modeloTitularesProductos->getEsquema() .
                ". titulares_productos";
        return $this->modeloTitularesProductos->ejecutarSqlNativo($consulta);
    }
}
