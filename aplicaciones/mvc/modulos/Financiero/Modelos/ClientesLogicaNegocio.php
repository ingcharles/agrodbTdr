<?php

/**
 * Lógica del negocio de  ClientesModelo
 *
 * Este archivo se complementa con el archivo   ClientesControlador.
 *
 * @author DATASTAR
 * @uses       ClientesLogicaNegocio
 * @package Financiero
 * @subpackage Modelo
 */

namespace Agrodb\Financiero\Modelos;

use Agrodb\Financiero\Modelos\IModelo;

class ClientesLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new ClientesModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new ClientesModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdentificador() != null && $tablaModelo->getIdentificador() > 0)
        {
            return $this->modelo->actualizar($datosBd, $tablaModelo->getIdentificador());
        } else
        {
            unset($datosBd["identificador"]);
            return $this->modelo->guardar($datosBd);
        }
    }

    /**
     * Borra el registro actual
     * @param string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        $this->modelo->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param  int $id
     * @return ClientesModelo
     */
    public function buscar($id)
    {
        return $this->modelo->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modelo->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modelo->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarClientes()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". clientes";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Columnas para guardar junto con la solicitud
     * @return string[]
     */
    public function columnas()
    {
        $columnas = array(
            'identificador',
            'tipo_identificacion',
            'razon_social',
            'direccion',
            'telefono',
            'correo'
        );
        return $columnas;
    }

}
