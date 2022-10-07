<?php

/**
 * Controlador LaboratoriosProvincia
 *
 * Este archivo controla la lógica del negocio del modelo:  LaboratoriosProvinciaModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     LaboratoriosProvinciaControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Laboratorios\Modelos\LaboratoriosProvinciaLogicaNegocio;
use Agrodb\Laboratorios\Modelos\LaboratoriosProvinciaModelo;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class LaboratoriosProvinciaControlador extends BaseControlador
{

    private $lNegocioLaboratoriosProvincia = null;
    private $modeloLaboratoriosProvincia = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioLaboratoriosProvincia = new LaboratoriosProvinciaLogicaNegocio();
        $this->modeloLaboratoriosProvincia = new LaboratoriosProvinciaModelo();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        require APP . 'Laboratorios/vistas/listaLaboratoriosProvinciaVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Laboratorios en Provincia";
        require APP . 'Laboratorios/vistas/formularioLaboratoriosProvinciaVista.php';
    }

    /**
     * Método para registrar en la base de datos -LaboratoriosProvincia
     */
    public function guardar()
    {
        $this->lNegocioLaboratoriosProvincia->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: LaboratoriosProvincia
     */
    public function editar()
    {
        $this->accion = "Editar Laboratorios en Provincia";
        $this->modeloLaboratoriosProvincia = $this->lNegocioLaboratoriosProvincia->buscar($_POST["id"]);
        require APP . 'Laboratorios/vistas/formularioLaboratoriosProvinciaVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - LaboratoriosProvincia
     */
    public function borrar()
    {
        $this->lNegocioLaboratoriosProvincia->borrar($_POST['elementos']);
    }

    /**
     * Búsqueda por filtro
     */
    public function listarDatos()
    {
        $arrayParametros = array();
        if (!empty($_POST['fDireccion']))
            $arrayParametros['idDireccion'] = $_POST['fDireccion'];
        if (!empty($_POST['fLaboratorio']))
            $arrayParametros['idLaboratorio'] = $_POST['fLaboratorio'];
        $datos = $this->lNegocioLaboratoriosProvincia->buscarLaboratoriosProvincia($arrayParametros);
        $this->tablaHtmlLaboratoriosProvincia($datos);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
        exit();
    }

    /**
     * Construye el código HTML para desplegar la lista de - LaboratoriosProvincia
     */
    public function tablaHtmlLaboratoriosProvincia($tabla)
    {
        $contador = 0;
        if (count($tabla) > 0)
        {
            foreach ($tabla as $fila)
            {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_laboratorios_provincia'] . '"
                        class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/LaboratoriosProvincia"
                        data-opcion="editar" ondragstart="drag(event)" draggable="true"
                        data-destino="detalleItem">
                        <td>' . ++$contador . '</td>
                        <td style="white - space:nowrap; "><b>' . $fila->nombre_laboratorio . '</b></td>
                        <td>' . $fila->nombre_provincia . '</td>
                        <td>' . $fila->tipo . '</td>
                        <td>' . $fila->estado . '</td>
                    </tr>');
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
        }
    }

}
