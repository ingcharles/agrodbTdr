<?php

/**
 * Controlador Actabaja
 *
 * Este archivo controla la lógica del negocio del modelo:  ActabajaModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     ActabajaControlador
 * @package Reactivos
 * @subpackage Controladores
 */

namespace Agrodb\Reactivos\Controladores;

use Agrodb\Reactivos\Modelos\ActabajaLogicaNegocio;
use Agrodb\Reactivos\Modelos\ActabajaModelo;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class ActabajaControlador extends BaseControlador
{

    private $lNegocioActabaja = null;
    private $modeloActabaja = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioActabaja = new ActabajaLogicaNegocio();
        $this->modeloActabaja = new ActabajaModelo();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        require APP . 'Reactivos/vistas/listaActaBajaVista.php';
    }

    /**
     * Búsqueda por filtro
     */
    public function listarDatos()
    {
        $arrayParametros = array();
        if (!empty($_POST['nombre']))
        {
            $arrayParametros['nombre'] = $_POST['nombre'];
        }
        $arrayParametros['id_laboratorios_provincia'] = $_POST['id_laboratorios_provincia'];
        $modeloActabaja = $this->lNegocioActabaja->buscarActabajaReactivo($arrayParametros);
        $this->tablaHtmlActabaja($modeloActabaja);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Acta Baja";
        require APP . 'Reactivos/vistas/formularioActaBajaVista.php';
    }

    /**
     * Método para registrar en la base de datos -Actabaja
     */
    public function guardar()
    {
        $_POST['identificador'] = parent::usuarioActivo();
        $this->lNegocioActabaja->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Actabaja
     */
    public function editar()
    {
        $this->accion = "Editar Acta Baja";
        $this->modeloActabaja = $this->lNegocioActabaja->buscar($_POST["id"]);
        require APP . 'Reactivos/vistas/formularioActaBajaVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Actabaja
     */
    public function borrar()
    {
        $this->lNegocioActabaja->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Actabaja
     */
    public function tablaHtmlActabaja($tabla)
    {
        if (count($tabla) > 0)
        {
            $contador = 0;
            foreach ($tabla as $fila)
            {
                $nombreActa = !empty($fila->nombre_acta) ? $fila->nombre_acta : '(pendiente)';
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_acta_baja . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Reactivos/Actabaja"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">'
                    . "<td>" . ++$contador . "</td>"
                    . "<td style='white - space:nowrap; '><b>" . $nombreActa . "</b></td>"
                    . "<td>$fila->fecha_registro</td>"
                    . "<td>$fila->nombre_crea</td>"
                    . "<td>$fila->nombre_aprueba</td>"
                    . "<td>$fila->estado_acta</td>"
                    . "</tr>");
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
        }
    }

}
