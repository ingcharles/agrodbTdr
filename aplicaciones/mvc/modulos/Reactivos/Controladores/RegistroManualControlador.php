<?php

/**
 * Controlador RegistroManual
 *
 * Este archivo controla la lógica del negocio del modelo:  RegistroManualModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     RegistroManualControlador
 * @package Reactivos
 * @subpackage Controladores
 */

namespace Agrodb\Reactivos\Controladores;

use Agrodb\Reactivos\Modelos\RegistroManualLogicaNegocio;
use Agrodb\Reactivos\Modelos\RegistroManualModelo;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class RegistroManualControlador extends BaseControlador
{

    private $lNegocioRegistroManual = null;
    private $modeloRegistroManual = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        $this->lNegocioRegistroManual = new RegistroManualLogicaNegocio();
        $this->modeloRegistroManual = new RegistroManualModelo();
        parent::__construct();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $modeloRegistroManual = $this->lNegocioRegistroManual->buscarRegistroManual();
        $this->tablaHtmlRegistroManual($modeloRegistroManual);
        require APP . 'Reactivos/vistas/listaRegistroManualVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo RegistroManual";
        require APP . 'Reactivos/vistas/formularioRegistroManualVista.php';
    }

    /**
     * Método para registrar en la base de datos -RegistroManual
     */
    public function guardar()
    {
        $this->lNegocioRegistroManual->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: RegistroManual
     */
    public function editar()
    {
        $this->accion = "Editar RegistroManual";
        $this->modeloRegistroManual = $this->lNegocioRegistroManual->buscar($_POST["id"]);
        require APP . 'Reactivos/vistas/formularioRegistroManualVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - RegistroManual
     */
    public function borrar()
    {
        $this->lNegocioRegistroManual->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - RegistroManual
     */
    public function tablaHtmlRegistroManual($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila)
        {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_registro_manual'] . '"
		  class="item" data-rutaAplicacion="Reactivos\registromanual"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_registro_manual'] . '</b></td>
<td>'
                . $fila['id_reactivo_laboratorio'] . '</td>
<td>' . $fila['fecha_inicio']
                . '</td>
<td>' . $fila['fecha_fin'] . '</td>
</tr>');
        }
    }

}
