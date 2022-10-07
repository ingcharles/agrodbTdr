<?php
/**
 * Modelo ServiciosModelo
 *
 * Este archivo se complementa con el archivo   ServiciosLogicaNegocio.
 *
 * @author DATASTAR
 * @uses       ServiciosModelo
 * @package Financiero
 * @subpackage Modelo
 */
namespace Agrodb\Financiero\Modelos;

use Agrodb\Core\ModeloBase;

class ServiciosModelo extends ModeloBase
{

    /**
     * Nombre del esquema
     */
    private $esquema = "g_financiero";

    /**
     * Nombre de la tabla: servicios
     */
    private $tabla = "servicios";

    /**
     * Clave primaria
     */
    private $clavePrimaria = "id_servicio";

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct($this->esquema, $this->tabla);
    }

    /**
     * Busca una lista de registro de acuerdo a los parámetros <params> enviados.
     *
     * @return array
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return parent::buscarLista($where);
    }
    // Implementar en resto
}
