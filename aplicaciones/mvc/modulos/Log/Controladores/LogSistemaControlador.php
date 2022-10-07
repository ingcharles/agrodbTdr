<?php

namespace Agrodb\Log\Controladores;


use Agrodb\Log\Modelos\LogSistemaLogicaNegocio;

class LogSistemaControlador extends BaseControlador {

    private $lNegocioLogSistema = null;

    /*
     * Constructor
     */

    function __construct() {
        $this->lNegocioLogSistema = new LogSistemaLogicaNegocio();
    }

    /**
     * Método de inicio del controlador
     */
    public function index() {
        $this->lNegocioLogSistema->verLog('log_sistema');
    }

    /**
     * Construye el código HTML para desplegar la lista de - Solicitudes
     */
    public function tablaHtmlSolicitudes($tabla) {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'LogLaboratorios/descripcion"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['fecha'] . '</b></td>
                  <td>' . $fila['tipo'] . '</td>
                  <td>' . substr($fila['evento'], 0, 20) . '...</td>
                  </tr>'
            );
        }
    }

}
