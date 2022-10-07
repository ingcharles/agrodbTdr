<?php

/**
 * Controlador Catalogos
 *
 * Este archivo controla la lógica del negocio del modelo:  CatalogosModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     CatalogosControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Reactivos\Controladores;

use Agrodb\Catalogos\Modelos\CatalogosLaboratoriosLogicaNegocio;
use Agrodb\Catalogos\Modelos\CatalogosLaboratoriosModelo;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class CatalogosReactivosControlador extends BaseControlador
{

    private $lNegocioCatalogos = null;
    private $modeloCatalogos = null;
    private $accion = null;
    private $cmbCatalogos = null;

    /**
     * Constructor
     */
    function __construct()
    {
        $this->lNegocioCatalogos = new CatalogosLaboratoriosLogicaNegocio();
        $this->modeloCatalogos = new CatalogosLaboratoriosModelo();
        parent::__construct();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        require APP . 'Reactivos/vistas/listaCatalogosReactivosVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Catálogo";
        $modeloCatalogos = $this->lNegocioCatalogos->buscarCatalogos(null, 'REACTIVOS');
        $arbol = $this->arbol($modeloCatalogos);
        $this->cmbCatalogos = json_encode($arbol);
        require APP . 'Reactivos/vistas/formularioCatalogosReactivosVista.php';
    }

    /**
     * Método para registrar en la base de datos -Catalogos
     */
    public function guardar()
    {
        $_POST['modulo'] = "REACTIVOS";
        $this->lNegocioCatalogos->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Catalogos
     */
    public function editar()
    {
        $this->accion = "Editar Catálogos";
        $this->modeloCatalogos = $this->lNegocioCatalogos->buscar($_POST["id"]);
        $modeloCatalogos = $this->lNegocioCatalogos->buscarCatalogos(null, 'REACTIVOS');
        $arbol = $this->arbol($modeloCatalogos);
        $this->cmbCatalogos = json_encode($arbol);
        require APP . 'Reactivos/vistas/formularioCatalogosReactivosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Catalogos
     */
    public function borrar()
    {
        $this->lNegocioCatalogos->borrar($_POST['elementos']);
    }

    /**
     * Crea un arbol para ser desplegado en un combo 
     * @param type $tabla
     * @return type
     */
    public function arbol($tabla)
    {
        $array = array();
        if (!empty($tabla))
        {
            foreach ($tabla as $fila)
            {
                $idCatalogos = $fila->id_catalogos;
                // buscar los registro que tengan el id_padre
                $modeloCatalogo = $this->lNegocioCatalogos->buscarCatalogos($idCatalogos, 'REACTIVOS');
                if (count($modeloCatalogo) > 0)
                { // hay hijos
                    $array[] = array("id" => $fila->id_catalogos, "text" => strip_tags($fila->nombre), "children" => self::arbol($modeloCatalogo));
                } else
                { //no hay hijos
                    $array[] = array("id" => $fila->id_catalogos, "text" => strip_tags($fila->nombre));
                }
            }
        }
        return $array;
    }

    /**
     * Búsqueda por filtro 
     */
    public function listarCatalogos()
    {
        $html = "";
        $arrayParametros = array('nombre' => $_POST['fNombre'], 'modulo' => 'REACTIVOS');
        $modeloCatalogos = $this->lNegocioCatalogos->buscarListaCatalogosTree($arrayParametros, 'orden');
        foreach ($modeloCatalogos as $fila)
        {
            $html.="<tr data-tt-id='" . $fila->id_catalogos . "' data-tt-parent-id='" . $fila->fk_id_catalogos . "'"
                    . 'id="' . $fila->id_catalogos . '" class="item"'
                    . "data-rutaAplicacion='" . URL_MVC_FOLDER . "Reactivos/CatalogosReactivos'"
                    . 'data-opcion="editar" ondragstart="drag(event)" draggable="true"'
                    . 'data-destino="detalleItem">'
                    . "<td>" . $fila->nombre . "</td>"
                    . "<td>" . $fila->codigo . "</td>"
                    . "<td>" . $fila->descripcion . "</td>"
                    . "<td>" . $fila->orden . "</td>"
                    . "<td>" . $fila->estado . "</td>"
                    . "</tr>";
            $buscaCatalogosHijos = $this->lNegocioCatalogos->buscarCatalogos($fila->id_catalogos, 'REACTIVOS');
            $html.= $this->tablaHtmlArbol($buscaCatalogosHijos);
        }
        echo $html;
        exit();
    }

    /**
     * Construye la tabla html tipo árbol
     * @param type $tabla
     * @return string
     */
    public function tablaHtmlArbol($tabla)
    {
        $html = "";
        foreach ($tabla as $fila)
        {
            $idInforme = $fila->id_catalogos;
            // buscar los registro informes que tengan el id_padre
            $modeloInformes = $this->lNegocioCatalogos->buscarCatalogos($idInforme);
            if (count($modeloInformes) > 0)
            { // hay hijos
                $html.="<tr data-tt-id='" . $fila->id_catalogos . "' data-tt-parent-id='" . $fila->fk_id_catalogos . "'"
                        . 'id="' . $fila->id_catalogos . '" class="item"'
                        . "data-rutaAplicacion='" . URL_MVC_FOLDER . "Reactivos/CatalogosReactivos'"
                        . 'data-opcion="editar" ondragstart="drag(event)" draggable="true"'
                        . 'data-destino="detalleItem">'
                        . "<td>" . strip_tags($fila->nombre) . "</td>"
                        . "<td>" . $fila->codigo . "</td>"
                        . "<td>" . $fila->descripcion . "</td>"
                        . "<td>" . $fila->orden . "</td>"
                        . "<td>" . $fila->estado . "</td>"
                        . "<td></td>"
                        . "</tr>";
                $html.=self::tablaHtmlArbol($modeloInformes);
            } else
            { //no hay hijos
                $html.="<tr data-tt-id='" . $fila->id_catalogos . "' data-tt-parent-id='" . $fila->fk_id_catalogos . "'"
                        . 'id="' . $fila->id_catalogos . '" class="item"'
                        . "data-rutaAplicacion='" . URL_MVC_FOLDER . "Reactivos/catalogosReactivos'"
                        . 'data-opcion="editar" ondragstart="drag(event)" draggable="true"'
                        . 'data-destino="detalleItem">'
                        . "<td>" . strip_tags($fila->nombre) . "</td>"
                        . "<td>" . $fila->codigo . "</td>"
                        . "<td>" . $fila->descripcion . "</td>"
                        . "<td>" . $fila->orden . "</td>"
                        . "<td>" . $fila->estado . "</td>"
                        . "<td></td>"
                        . "</tr>";
            }
        }
        return $html;
    }

}
