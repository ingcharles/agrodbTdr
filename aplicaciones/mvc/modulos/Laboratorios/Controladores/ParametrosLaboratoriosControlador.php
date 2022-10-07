<?php

/**
 * Controlador ParametrosLaboratorios
 *
 * Este archivo controla la lógica del negocio del modelo:  ParametrosLaboratoriosModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     ParametrosLaboratoriosControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Laboratorios\Modelos\ParametrosLaboratoriosLogicaNegocio;
use Agrodb\Laboratorios\Modelos\ParametrosLaboratoriosModelo;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class ParametrosLaboratoriosControlador extends BaseControlador
{

    private $lNegocioParametrosLaboratorios = null;
    private $modeloParametrosLaboratorios = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioParametrosLaboratorios = new ParametrosLaboratoriosLogicaNegocio();
        $this->modeloParametrosLaboratorios = new ParametrosLaboratoriosModelo();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        require APP . 'Laboratorios/vistas/listaParametrosLaboratoriosVista.php';
    }

    /**
     * Búsqueda por filtro
     */
    public function listarDatos()
    {
        $arrayParametros = array('id_direccion' => $_POST['fDireccion'], 'id_laboratorio' => $_POST['fLaboratorio']);
        $modeloParametrosLaboratorios = $this->lNegocioParametrosLaboratorios->buscarParametrosL($arrayParametros);
        $this->tablaHtmlParametrosLaboratorios($modeloParametrosLaboratorios);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo parámetro de laboratorio";
        require APP . 'Laboratorios/vistas/formularioParametrosLaboratoriosVista.php';
    }

    /**
     * Método para registrar en la base de datos -ParametrosLaboratorios
     */
    public function guardar()
    {
        $this->lNegocioParametrosLaboratorios->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Guarda las etiquetas
     */
    public function etiquetasMuestra()
    {
        $this->lNegocioParametrosLaboratorios->guardarEtiquetas($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: ParametrosLaboratorios
     */
    public function editar()
    {
        $this->accion = "Editar parámetros de laboratorios";
        $this->modeloParametrosLaboratorios = $this->lNegocioParametrosLaboratorios->buscar($_POST["id"]);
        require APP . 'Laboratorios/vistas/formularioParametrosLaboratoriosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - ParametrosLaboratorios
     */
    public function borrar()
    {
        $this->lNegocioParametrosLaboratorios->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - ParametrosLaboratorios
     */
    public function tablaHtmlParametrosLaboratorios($tabla)
    {
        $contador = 0;
        if (count($tabla) > 0)
        {
            foreach ($tabla as $fila)
            {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_parametros_laboratorio . '"
                    class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/ParametrosLaboratorios"
                    data-opcion="editar" ondragstart="drag(event)" draggable="true"
                    data-destino="detalleItem">
                    <td>' . ++$contador . '</td>
                    <td>' . $fila->direccion . '</td>
                    <td>' . $fila->laboratorio . '</td>
                    <td style="white - space:nowrap; "><b>' . $fila->nombre . '</b></td>
                    <td>' . $fila->estado . '</td>
                    </tr>'
                );
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
        }
    }

}
