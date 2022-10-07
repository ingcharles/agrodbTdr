<?php
/**
 * Controlador CatastroPredioEquidos
 *
 * Este archivo controla la lógica del negocio del modelo:  CatastroPredioEquidosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-02-16
 * @uses    CatastroPredioEquidosControlador
 * @package ProgramasControlOficial
 * @subpackage Controladores
 */
namespace Agrodb\ProgramasControlOficial\Controladores;

use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosLogicaNegocio;
use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosModelo;

class CatastroPredioEquidosControlador extends BaseControlador
{

    private $lNegocioCatastroPredioEquidos = null;

    private $modeloCatastroPredioEquidos = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioCatastroPredioEquidos = new CatastroPredioEquidosLogicaNegocio();
        $this->modeloCatastroPredioEquidos = new CatastroPredioEquidosModelo();
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
        $modeloCatastroPredioEquidos = $this->lNegocioCatastroPredioEquidos->buscarCatastroPredioEquidos();
        $this->tablaHtmlCatastroPredioEquidos($modeloCatastroPredioEquidos);
        require APP . 'ProgramasControlOficial/vistas/listaCatastroPredioEquidosVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo CatastroPredioEquidos";
        require APP . 'ProgramasControlOficial/vistas/formularioCatastroPredioEquidosVista.php';
    }

    /**
     * Método para registrar en la base de datos -CatastroPredioEquidos
     */
    public function guardar()
    {
        $this->lNegocioCatastroPredioEquidos->guardar($_POST);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: CatastroPredioEquidos
     */
    public function editar()
    {
        $this->accion = "Editar CatastroPredioEquidos";
        $this->modeloCatastroPredioEquidos = $this->lNegocioCatastroPredioEquidos->buscar($_POST["id"]);
        require APP . 'ProgramasControlOficial/vistas/formularioCatastroPredioEquidosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - CatastroPredioEquidos
     */
    public function borrar()
    {
        $this->lNegocioCatastroPredioEquidos->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - CatastroPredioEquidos
     */
    public function tablaHtmlCatastroPredioEquidos($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_catastro_predio_equidos'] . '"
                		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'ProgramasControlOficial\catastropredioequidos"
                		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                		  data-destino="detalleItem">
                    	<td>' . ++ $contador . '</td>
                    	<td style="white - space:nowrap; "><b>' . $fila['id_catastro_predio_equidos'] . '</b></td>
                        <td>' . $fila['identificador'] . '</td>
                        <td>' . $fila['fecha_creacion'] . '</td>
                        <td>' . $fila['num_solicitud'] . '</td>
                    </tr>'
            );
        }
    }
}