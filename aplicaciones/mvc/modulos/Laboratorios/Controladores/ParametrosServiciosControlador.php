<?php

/**
 * Controlador ParametrosServicios
 *
 * Este archivo controla la lógica del negocio del modelo:  ParametrosServiciosModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     ParametrosServiciosControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Laboratorios\Modelos\ParametrosServiciosLogicaNegocio;
use Agrodb\Laboratorios\Modelos\ParametrosServiciosModelo;
use Agrodb\Laboratorios\Modelos\LaboratoriosModelo;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class ParametrosServiciosControlador extends BaseControlador
{

    private $lNegocioParametrosServicios = null;
    private $modeloParametrosServicios = null;
    private $modeloLaboratorios = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioParametrosServicios = new ParametrosServiciosLogicaNegocio();
        $this->modeloParametrosServicios = new ParametrosServiciosModelo();
        $this->modeloLaboratorios = new LaboratoriosModelo();
        set_exception_handler(array(
            $this,
            'manejadorExcepciones'
        ));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        require APP . 'Laboratorios/vistas/listaParametrosServiciosVista.php';
    }

    /**
     * Búsqueda por filtro
     */
    public function listarDatos()
    {
        $arrayParametros = array('id_direccion' => $_POST['fDireccion'], 'id_laboratorio' => $_POST['fLaboratorio'], 'id_servicio' => $_POST['fServicio']);
        $modeloParametrosServicios = $this->lNegocioParametrosServicios->buscarParametrosS($arrayParametros);
        $this->tablaHtmlParametrosServicios($modeloParametrosServicios);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Parámetro del servicio";
        require APP . 'Laboratorios/vistas/formularioParametrosServiciosVista.php';
    }

    /**
     * Método para registrar en la base de datos -ParametrosServicios
     */
    public function guardar()
    {
        $this->lNegocioParametrosServicios->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: ParametrosServicios
     */
    public function editar()
    {
        $this->accion = "Editar parámetro del servicio";
        $this->modeloParametrosServicios = $this->lNegocioParametrosServicios->buscar($_POST["id"]);
        require APP . 'Laboratorios/vistas/formularioParametrosServiciosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - ParametrosServicios
     */
    public function borrar()
    {
        $this->lNegocioParametrosServicios->borrar($_POST['elementos']);
    }

    /**
     * Construye array tipo árbol para los parámetros del servicio
     * @param type $tabla
     * @return type
     */
    public function arbol($tabla)
    {
        $array = array();
        foreach ($tabla as $fila)
        {
            $idParametrosServicio = $fila['id_parametros_servicio'];
            // buscar los registro que tengan el id_padre
            $modeloParametrosServicios = $this->lNegocioParametrosServicios->buscarIdPadre($idParametrosServicio);
            if (count($modeloParametrosServicios) > 0)
            { // hay hijos
                $array[] = array("id" => $fila['id_parametros_servicio'], "text" => strip_tags($fila['nombre']), "children" => self::arbol($modeloParametrosServicios));
            } else
            { //no hay hijos
                $array[] = array("id" => $fila['id_parametros_servicio'], "text" => strip_tags($fila['nombre']));
            }
        }
        return $array;
    }

    /**
     * Construye el código HTML para desplegar la lista de - ParametrosServicios
     */
    public function tablaHtmlParametrosServicios($tabla)
    {
        $contador = 0;
        if (count($tabla) > 0)
        {
            foreach ($tabla as $fila)
            {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_parametros_servicio . '"
                    class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/ParametrosServicios"
                    data-opcion="editar" ondragstart="drag(event)" draggable="true"
                    data-destino="detalleItem">
                    <td>' . ++$contador . '</td>
                    <td>' . $fila->servicio . '</td>
                    <td style="white - space:nowrap; "><b>' . $fila->nombre . '</b></td>
                    <td>' . $fila->estado . '</td>
                    <td>' . $fila->obligatorio . '</td>
                    <td>' . $fila->orden . '</td>
                    </tr>'
                );
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
        }
    }

}
