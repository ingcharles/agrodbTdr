<?php

/**
 * Controlador RecetaAnalisis
 *
 * Este archivo controla la lógica del negocio del modelo:  RecetaAnalisisModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     RecetaAnalisisControlador
 * @package Reactivos
 * @subpackage Controladores
 */

namespace Agrodb\Reactivos\Controladores;

use Agrodb\Reactivos\Modelos\RecetaAnalisisLogicaNegocio;
use Agrodb\Reactivos\Modelos\RecetaAnalisisModelo;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class RecetaAnalisisControlador extends BaseControlador
{

    private $lNegocioRecetaAnalisis = null;
    private $modeloRecetaAnalisis = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        $this->lNegocioRecetaAnalisis = new RecetaAnalisisLogicaNegocio();
        $this->modeloRecetaAnalisis = new RecetaAnalisisModelo();
        parent::__construct();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $modeloRecetaAnalisis = $this->lNegocioRecetaAnalisis->buscarRecetaAnalisis();
        $this->tablaHtmlRecetaAnalisis($modeloRecetaAnalisis);
        require APP . 'Reactivos/vistas/listaRecetaAnalisisVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo RecetaAnalisis";
        require APP . 'Reactivos/vistas/formularioRecetaAnalisisVista.php';
    }

    /**
     * Método para registrar en la base de datos -RecetaAnalisis
     */
    public function guardar()
    {
        $this->lNegocioRecetaAnalisis->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: RecetaAnalisis
     */
    public function editar()
    {
        $this->accion = "Editar RecetaAnalisis";
        $this->modeloRecetaAnalisis = $this->lNegocioRecetaAnalisis->buscar($_POST["id"]);
        require APP . 'Reactivos/vistas/formularioRecetaAnalisisVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - RecetaAnalisis
     */
    public function borrar()
    {
        $this->lNegocioRecetaAnalisis->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - RecetaAnalisis
     */
    public function tablaHtmlRecetaAnalisis($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila)
        {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_receta_analisis'] . '"
		  class="item" data-rutaAplicacion="Reactivos\recetaanalisis"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_receta_analisis'] . '</b></td>
<td>'
                . $fila['id_servicio'] . '</td>
<td>' . $fila['id_reactivos']
                . '</td>
<td>' . $fila['codigo_bodega'] . '</td>
</tr>');
        }
    }

}
