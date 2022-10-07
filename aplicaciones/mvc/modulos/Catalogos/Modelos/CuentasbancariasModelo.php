<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Agrodb\Catalogos\Modelos;

/**
 * Description of CuentasbancariasModelo
 *
 * @author moralesl
 */
use Agrodb\Core\ModeloBase;
use Zend\Db\Sql\Select;


class CuentasbancariasModelo extends ModeloBase {

    /**
     * Nombre del esquema
     */
    private $esquema = "g_catalogos";

    /**
     * Nombre de tabla
     */
    private $tabla = "cuentas_bancarias";

    /**
     * Nombre de tabla
     */
    private $clavePrimaria = "id_cuenta_bancaria";

    function __construct()
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
    
    // Implentar el resto
}
