<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Laboratorios\Modelos\PagosLogicaNegocio;
use Agrodb\Laboratorios\Modelos\PagosModelo;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

/**
 * Description of PagosControlador
 *
 * @author moralesl
 */
class PagosControlador extends BaseControlador
{

    private $lNegocioPagos = null;
    private $modeloPagos = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioPagos = new PagosLogicaNegocio();
        $this->modeloPagos = new PagosModelo();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $modeloPagos = $this->$lNegocioPagos->buscarPagos();
        $this->tablaHtmlPersonas($modeloPagos);
        require APP . 'Laboratorios/vistas/listaPersonasVista.php';
    }

    /**
     * Método para registrar en la base de datos -Pagos
     */
    public function guardar()
    {
        $this->lNegocioPagos->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

}
