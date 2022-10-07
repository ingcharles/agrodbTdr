<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of accionesModelos
 *
 * @author Alvaro Sanchez
 */
namespace Agrodb\Programas\Modelos;

use Agrodb\Core\ModeloBase;

class AccionesModelos extends ModeloBase
{

    /**
     * Nombre del esquema
     */
    private $esquema = "g_programas";

    /**
     * Nombre de tabla
     */
    private $tabla = "acciones";

    /**
     * Nombre de tabla
     */
    private $clavePrimaria = "id_accion";

    function __construct()
    {
        parent::__construct($this->esquema, $this->tabla);
    }

    public function buscar($id_laboratorio)
    {}
}
