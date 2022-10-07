<?php

/**
 * Controlador Bodegas
 *
 * Este archivo controla la lógica del negocio del modelo:  BodegasModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     BodegasControlador
 * @package Reactivos
 * @subpackage Controladores
 */

namespace Agrodb\Reactivos\Controladores;

use Agrodb\Reactivos\Modelos\BodegasLogicaNegocio;
use Agrodb\Reactivos\Modelos\BodegasModelo;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class BodegasControlador extends BaseControlador
{

    private $lNegocioBodegas = null;
    private $modeloBodegas = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        $this->lNegocioBodegas = new BodegasLogicaNegocio();
        $this->modeloBodegas = new BodegasModelo();
        parent::__construct();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $modeloBodegas = $this->lNegocioBodegas->buscarBodegas();
        $this->tablaHtmlBodegas($modeloBodegas);
        require APP . 'Reactivos/vistas/listaBodegasVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nueva Bodega";
        require APP . 'Reactivos/vistas/formularioBodegasVista.php';
    }

    /**
     * Método para registrar en la base de datos -Bodegas
     */
    public function guardar()
    {
        $this->lNegocioBodegas->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Bodegas
     */
    public function editar()
    {
        $this->accion = "Editar Bodegas";
        $this->modeloBodegas = $this->lNegocioBodegas->buscar($_POST["id"]);
        require APP . 'Reactivos/vistas/formularioBodegasVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Bodegas
     */
    public function borrar()
    {
        $this->lNegocioBodegas->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Bodegas
     */
    public function tablaHtmlBodegas($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila)
        {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_bodega'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Reactivos/Bodegas"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['provincia'] . '</b></td>
                  <td>' . $fila['nombre_bodega'] . '</td>
                  <td>' . $fila['estado'] . '</td>
                  </tr>');
        }
    }

}
