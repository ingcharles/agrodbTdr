<?php

/**
 * Controlador Ubicaciones
 *
 * Este archivo controla la lógica del negocio del modelo:  UbicacionesModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     UbicacionesControlador
 * @package Reactivos
 * @subpackage Controladores
 */

namespace Agrodb\Reactivos\Controladores;

use Agrodb\Reactivos\Modelos\UbicacionesLogicaNegocio;
use Agrodb\Reactivos\Modelos\UbicacionesModelo;

class UbicacionesControlador extends BaseControlador {

    private $lNegocioUbicaciones = null;
    private $modeloUbicaciones = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct() {
        $this->lNegocioUbicaciones = new UbicacionesLogicaNegocio();
        $this->modeloUbicaciones = new UbicacionesModelo();
        parent::__construct();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

/**
     * Método de inicio del controlador
     */

    public function index() {
        $modeloUbicaciones = $this->lNegocioUbicaciones->buscarUbicaciones();
        $this->tablaHtmlUbicaciones($modeloUbicaciones);
        require APP . 'Reactivos/vistas/listaUbicacionesVista.php';
    }

/**
     * Método para desplegar el formulario vacio
     */

    public function nuevo() {
        $this->accion = "Nuevo Ubicaciones";
        require APP . 'Reactivos/vistas/formularioUbicacionesVista.php';
    }

/**
     * Método para registrar en la base de datos -Ubicaciones
     */

    public function guardar() {
        $this->lNegocioUbicaciones->guardar($_POST);
    }

/**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Ubicaciones
     */

    public function editar() {
        $this->accion = "Editar Ubicaciones";
        $this->modeloUbicaciones = $this->lNegocioUbicaciones->buscar($_POST["id"]);
        require APP . 'Reactivos/vistas/formularioUbicacionesVista.php';
    }

/**
     * Método para borrar un registro en la base de datos - Ubicaciones
     */

    public function borrar() {
        $this->lNegocioUbicaciones->borrar($_POST['elementos']);
    }

/**
     * Construye el código HTML para desplegar la lista de - Ubicaciones
     */

    public function tablaHtmlUbicaciones($tabla) { {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_ubicacion'] . '"
		  class="item" data-rutaAplicacion="Reactivos\ubicaciones"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_ubicacion'] . '</b></td>
<td>'
                    . $fila['fk_id_ubicacion'] . '</td>
<td>' . $fila['id_reactivo_laboratorio']
                    . '</td>
<td>' . $fila['nombre'] . '</td>
</tr>');
            }
        }
    }

}
