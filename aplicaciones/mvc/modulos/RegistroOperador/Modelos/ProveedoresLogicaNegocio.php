<?php
/**
 * Lógica del negocio de ProveedoresModelo
 *
 * Este archivo se complementa con el archivo ProveedoresControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-08-04
 * @uses    ProveedoresLogicaNegocio
 * @package CertificadoFitosanitario
 * @subpackage Modelos
 */
namespace Agrodb\RegistroOperador\Modelos;

use Agrodb\RegistroOperador\Modelos\IModelo;

class ProveedoresLogicaNegocio implements IModelo
{

    private $modeloProveedores = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloProveedores = new ProveedoresModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new ProveedoresModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdProveedor() != null && $tablaModelo->getIdProveedor() > 0) {
            return $this->modeloProveedores->actualizar($datosBd, $tablaModelo->getIdProveedor());
        } else {
            unset($datosBd["id_proveedor"]);
            return $this->modeloProveedores->guardar($datosBd);
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
        $this->modeloProveedores->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return ProveedoresModelo
     */
    public function buscar($id)
    {
        return $this->modeloProveedores->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloProveedores->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloProveedores->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarProveedores()
    {
        $consulta = "SELECT * FROM " . $this->modeloProveedores->getEsquema() . ". proveedores";
        return $this->modeloProveedores->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los datos del operador
     * de acuerdo al identificador del operador
     *
     * @return array|ResultSet
     */
    public function obtenerInformacionProveedor($identificadorProveedor, $identificadorOperador, $idProducto)
    {
        $consulta = "SELECT
						p.*
    				FROM
    					g_operadores.proveedores p 
                    WHERE
                        identificador_operador = '" . $identificadorOperador . "' and
                        codigo_proveedor = '" . $identificadorProveedor . "' and
                        id_producto = '" . $idProducto . "' 
					ORDER BY 2;";
        
        return $this->modeloProveedores->ejecutarSqlNativo($consulta);
    }
}