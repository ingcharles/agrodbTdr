<?php

/**
 * Controlador DiasNoLaborables
 *
 * Este archivo controla la lógica del negocio del modelo:  DiasNoLaborablesModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     DiasNoLaborablesControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Laboratorios\Modelos\DiasNoLaborablesLogicaNegocio;
use Agrodb\Laboratorios\Modelos\DiasNoLaborablesModelo;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class DiasNoLaborablesControlador extends BaseControlador
{

    private $lNegocioDiasNoLaborables = null;
    private $modeloDiasNoLaborables = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioDiasNoLaborables = new DiasNoLaborablesLogicaNegocio();
        $this->modeloDiasNoLaborables = new DiasNoLaborablesModelo();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $modeloDiasNoLaborables = $this->lNegocioDiasNoLaborables->buscarDiasNoLaborables();
        $this->tablaHtmlDiasNoLaborables($modeloDiasNoLaborables);
        require APP . 'Laboratorios/vistas/listaDiasNoLaborablesVista.php';
    }
    
    /**
     * Actualizar registros 
     */
    public function listaActualizar()
    {
        $modeloDiasNoLaborables = $this->lNegocioDiasNoLaborables->buscarDiasNoLaborables();
        $this->tablaHtmlDiasNoLaborables($modeloDiasNoLaborables);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
        exit();
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Días No Laborables";
        require APP . 'Laboratorios/vistas/formularioDiasNoLaborablesVista.php';
    }

    /**
     * Método para registrar en la base de datos -DiasNoLaborables
     */
    public function guardar()
    {
        $this->lNegocioDiasNoLaborables->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: DiasNoLaborables
     */
    public function editar()
    {
        $this->accion = "Editar Dias No Laborables";
        $this->modeloDiasNoLaborables = $this->lNegocioDiasNoLaborables->buscar($_POST["id"]);
        require APP . 'Laboratorios/vistas/formularioDiasNoLaborablesVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - DiasNoLaborables
     */
    public function borrar()
    {
        $this->lNegocioDiasNoLaborables->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - DiasNoLaborables
     */
    public function tablaHtmlDiasNoLaborables($tabla)
    {
        $contador = 0;
        if (count($tabla) > 0)
        {
            foreach ($tabla as $fila)
            {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_dias_no_laborables'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/DiasNoLaborables"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila->anio . '</b></td>
                <td>' . $fila->fecha . '</td>
                <td>' . $fila->descripcion . '</td>
                <td>' . $fila->alcance . '</td>
                    <td>' . $fila->estado . '</td>
                </tr>');
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
        }
    }

}
