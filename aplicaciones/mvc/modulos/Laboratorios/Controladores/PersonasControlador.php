<?php

/**
 * Controlador Personas
 *
 * Este archivo controla la lógica del negocio del modelo:  PersonasModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     PersonasControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Laboratorios\Modelos\PersonasLogicaNegocio;
use Agrodb\Laboratorios\Modelos\PersonasModelo;

class PersonasControlador extends BaseControlador
{

    private $lNegocioPersonas = null;
    private $modeloPersonas = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioPersonas = new PersonasLogicaNegocio();
        $this->modeloPersonas = new PersonasModelo();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $modeloPersonas = $this->lNegocioPersonas->buscarPersonas();
        $this->tablaHtmlPersonas($modeloPersonas);
        require APP . 'Laboratorios/vistas/listaPersonasVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Personas";
        require APP . 'Laboratorios/vistas/formularioPersonasVista.php';
    }

    /**
     * Método para registrar en la base de datos -Personas
     */
    public function guardar()
    {
        $this->lNegocioPersonas->guardar($_POST);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Personas
     */
    public function editar()
    {
        $this->accion = "Editar Personas";
        $this->modeloPersonas = $this->lNegocioPersonas->buscar($_POST["id"]);
        require APP . 'Laboratorios/vistas/formularioPersonasVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Personas
     */
    public function borrar()
    {
        $this->lNegocioPersonas->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Personas
     */
    public function tablaHtmlPersonas($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila)
        {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_persona'] . '"
		  class="item" data-rutaAplicacion="Laboratorios\personas"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_persona'] . '</b></td>
<td>'
                . $fila['ci_ruc'] . '</td>
<td>' . $fila['nombre']
                . '</td>
<td>' . $fila['direccion'] . '</td>
</tr>');
        }
    }

}
