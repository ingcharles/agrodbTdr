<?php

/**
 * Lógica del negocio de  DocumentosReactivosModelo
 *
 * Este archivo se complementa con el archivo   DocumentosReactivosControlador.
 *
 * @author DATASTAR
 * @uses       DocumentosReactivosLogicaNegocio
 * @package Reactivos
 * @subpackage Modelo
 */

namespace Agrodb\Reactivos\Modelos;

use Agrodb\Reactivos\Modelos\IModelo;

class DocumentosReactivosLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new DocumentosReactivosModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new DocumentosReactivosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdDocumentosReactivos() != null && $tablaModelo->getIdDocumentosReactivos() > 0)
        {
            return $this->modelo->actualizar($datosBd, $tablaModelo->getIdDocumentosReactivos());
        } else
        {
            unset($datosBd["id_documentos_reactivos"]);
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
     * @return DocumentosReactivosModelo
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
    public function buscarDocumentosReactivos()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". documentos_reactivos";
        return $this->modelo->ejecutarConsulta($consulta);
    }

}
