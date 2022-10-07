<?php
/**
 * Controlador Administración Catálogos
 *
 * Este archivo controla la lógica del negocio del módulo de Administración de Catálogos RIA
 *
 * @author  AGROCALIDAD
 * @date   2021-06-23
 * @uses    AdministracionCatalogosControlador
 * @package AdministracionCatalogosRIA
 * @subpackage Controladores
 */
namespace Agrodb\AdministracionCatalogosRIA\Controladores;

use Agrodb\Catalogos\Modelos\ClasificacionLogicaNegocio;
use Agrodb\Catalogos\Modelos\ClasificacionModelo;

class AdministracionCatalogosControlador extends BaseControlador
{

    private $lNegocioClasificacion = null;
    private $modeloClasificacion = null;

    private $accion = null;
    private $listaBotones = null;
    
    private $formulario = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioClasificacion = new ClasificacionLogicaNegocio();
        $this->modeloClasificacion = new ClasificacionModelo();
        set_exception_handler(array(
            $this,
            'manejadorExcepciones'
        ));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        require APP . 'AdministracionCatalogosRIA/vistas/listaCatalogosAdministracion.php';
    }
    
}
