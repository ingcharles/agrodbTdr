<?php

/**
 * Controlador ReactivosBodega
 *
 * Este archivo controla la lógica del negocio del modelo:  ReactivosBodegaModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     ReactivosBodegaControlador
 * @package Reactivos
 * @subpackage Controladores
 */

namespace Agrodb\Reactivos\Controladores;

use Agrodb\Reactivos\Modelos\ReactivosBodegaLogicaNegocio;
use Agrodb\Reactivos\Modelos\ReactivosBodegaModelo;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class ReactivosBodegaControlador extends BaseControlador
{

    private $lNegocioReactivosBodega = null;
    private $modeloReactivosBodega = null;
    private $accion = null;
    private $rutaExcel = null;

    /**
     * Constructor
     */
    function __construct()
    {
        $this->lNegocioReactivosBodega = new ReactivosBodegaLogicaNegocio();
        $this->modeloReactivosBodega = new ReactivosBodegaModelo();
        parent::__construct();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $this->usuarioBodegas();
        require APP . 'Reactivos/vistas/listaReactivosBodegaVista.php';
    }

    /**
     * Búsqueda por filtro
     */
    public function listarDatos()
    {
        $arrayParametros = array('codigo' => $_POST['codigo'],
            'nombre' => $_POST['nombre'],
            'id_bodega' => $this->usuarioBodegas());
        $modeloReactivosBodega = $this->lNegocioReactivosBodega->buscarReactivosBodega($arrayParametros);
        $this->tablaHtmlReactivosBodega($modeloReactivosBodega);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        //buscar las bodegas al que pertenece el usuario
        $this->accion = "Nuevo Reactivos Bodega";
        require APP . 'Reactivos/vistas/formularioReactivosBodegaVista.php';
    }

    /**
     * Método para registrar en la base de datos -ReactivosBodega
     */
    public function guardar()
    {
        $this->lNegocioReactivosBodega->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Método para borrar un registro en la base de datos - ReactivosBodega
     */
    public function borrar()
    {
        $this->lNegocioReactivosBodega->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - ReactivosBodega
     */
    public function tablaHtmlReactivosBodega($tabla)
    {
        if (count($tabla) > 0)
        {
            $contador = 0;
            foreach ($tabla as $fila)
            {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_reactivo_bodega . '"
                        class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Reactivos/documentosReactivos"
                        data-opcion="certificado" ondragstart="drag(event)" draggable="true"
                        data-destino="detalleItem">
                        <td>' . ++$contador . '</td>
                        <td>' . $fila->provincia_bodega . '</td>
                        <td>' . $fila->nombre_bodega . '</td>
                        <td style="white - space:nowrap; "><b>' . $fila->codigo_bodega . '</b></td>
                        <td>' . $fila->nombre . '</td>
                        <td>' . $fila->cantidad_anterior . '</td>
                        <td>' . $fila->cantidad . '</td>
                        <td>' . $fila->unidad . '</td>
                        <td>' . $fila->fecha_actualizacion . '</td>
                    </tr>');
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
        }
    }

}
