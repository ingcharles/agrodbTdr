<?php

/**
 * Controlador Servicios
 *
 * Este archivo controla la lógica del negocio del modelo:  ServiciosModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     ServiciosControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Laboratorios\Modelos\ServiciosLogicaNegocio;
use Agrodb\Laboratorios\Modelos\ServiciosModelo;
use Agrodb\Laboratorios\Modelos\LaboratoriosLogicaNegocio;
use Agrodb\Laboratorios\Modelos\LaboratoriosModelo;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class ServiciosControlador extends BaseControlador
{

    private $lNegocioServicios = null;
    private $modeloServicios = null;
    private $lNegocioLaboratorios = null;
    private $modeloLaboratorios = null;
    private $accion = null;
    private $visible_id_servicio_guia = true;
    private $campo_disabled = null;
    private $nombreCampoRaiz = null;
    private $idCampoRaiz = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioServicios = new ServiciosLogicaNegocio();
        $this->modeloServicios = new ServiciosModelo();
        $this->lNegocioLaboratorios = new LaboratoriosLogicaNegocio();
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
        require APP . 'Laboratorios/vistas/listaServiciosVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {

        $this->accion = "Nuevo Servicio";
        require APP . 'Laboratorios/vistas/formularioServiciosVista.php';
    }

    /**
     * Agregar configuración al servicio
     */
    public function agregarCampos()
    {
        $this->modeloServicios = $this->lNegocioServicios->buscar($_POST["idPadre"]);
        $this->accion = "Agregar configuración al Análisis";
        require APP . 'Laboratorios/vistas/formularioAgregarCamposServiciosVista.php';
    }

    /**
     * Método para registrar en la base de datos -Servicios
     */
    public function guardar()
    {
        $this->lNegocioServicios->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Servicios
     */
    public function editar()
    {
        $this->accion = "Editar Servicios";
        $this->modeloServicios = $this->lNegocioServicios->buscar($_POST["id"]);
        require APP . 'Laboratorios/vistas/formularioServiciosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Servicios
     */
    public function borrar()
    {
        $this->lNegocioServicios->borrar($_POST['elementos']);
    }

    /**
     * Copia un formulario de un servicion a otro similar
     */
    public function copiar()
    {
        $this->nombreCampoRaiz = $_POST['nombreCampoRaiz'];
        $this->idCampoRaiz = $_POST['idServicio'];
        $this->accion = "Copiar: Tipo de análisis";
        require APP . 'Laboratorios/vistas/copiarServiciosVista.php';
    }

    /**
     * Envia los datos para copiar
     */
    public function guardarCopia()
    {
        $this->lNegocioServicios->guardarCopia($_POST);
        Constantes::COPIADO_CON_EXITO;
    }

    /**
     * Búsqueda por filtro
     */
    public function listarDatos()
    {
        $direccion = $_POST['fDireccion'];
        $laboratorio = $_POST['fLaboratorio'];
        $arrayParametros = array();
        if (!empty($direccion))
        {
            $arrayParametros['fk_id_laboratorio'] = $direccion;
        }
        if (!empty($laboratorio))
        {
            $arrayParametros['id_laboratorio'] = $laboratorio;
        }
        $arrayParametros['nivel'] = 1;
        $modeloLaboratorios = $this->lNegocioLaboratorios->buscarLista($arrayParametros);

        $html = "";
        foreach ($modeloLaboratorios as $fila)
        {
            $html.="<tr data-tt-id='" . $fila['id_laboratorio'] . "-'>"
                    . "<td>" . $fila['nombre'] . "</td>"
                    . "<td></td>"
                    . "<td>" . $fila['estado_registro'] . "</td>"
                    . "<td></td>"
                    . "<td></td>"
                    . "<td></td>"
                    . "<td></td>"
                    . "</tr>";
            $modeloServicios = $this->lNegocioServicios->buscarLista(" id_laboratorio = {$fila['id_laboratorio']} and fk_id_servicio IS null", "orden");
            $html.= $this->tablaHtmlServiciosArbol($modeloServicios, "/servicios");
        }
        echo $html;
        exit();
    }

    /**
     * Construye el código HTML para desplegar la lista de - Servicios
     */
    public function tablaHtmlServicios($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila)
        {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_servicio'] . '"
                    class="item" data-rutaAplicacion="Laboratorios/servicios"
                    data-opcion="editar" ondragstart="drag(event)" draggable="true"
                    data-destino="detalleItem">
                    <td>' . ++$contador . '</td>
                    <td style="white - space:nowrap; "><b>' . $fila['nombre'] . '</b></td>
                    <td>' . $fila['parametro'] . '</td>
                    <td>' . $fila['metodo'] . '</td>
                    <td>' . $fila['estado'] . '</td>
                </tr>'
            );
        }
    }

    /**
     * Construye la tabla html tipo álbol del servicio
     * @param type $tabla
     * @param type $vista
     * @return string
     */
    public function tablaHtmlServiciosArbol($tabla, $vista = null)
    {
        $html = "";
        foreach ($tabla as $fila)
        {
            //Actualizamos los niveles del arbol, enviando el nodo nivel 0
            if ($fila['nivel'] == 0)
            {

                $this->lNegocioServicios->actualizarNivelNodos($fila['id_servicio']);
            }

            $campoCopiar = "<button class='bntGrid far fa-clone' onclick='fn_copiar(\"" . $fila['nombre'] . "\"," . $fila['id_servicio'] . ")'/>";

            $idServicio = $fila['id_servicio'];
            // buscar los registro que tengan el id_padre
            $modeloServicio = $this->lNegocioServicios->buscarIdPadreTodos($idServicio);
            if (count($modeloServicio) > 0)
            { // hay hijos
                $html.="<tr data-tt-id='" . $fila['id_laboratorio'] . "-" . $fila['id_servicio'] . "' data-tt-parent-id='" . $fila['id_laboratorio'] . "-" . $fila['fk_id_servicio'] . "'"
                        . 'id="' . $fila['id_servicio'] . '" class="item"'
                        . "data-rutaAplicacion='" . URL_MVC_FOLDER . "Laboratorios/Servicios'"
                        . 'data-opcion="editar' . $vista . '"'
                        . 'data-destino="detalleItem">'
                        . "<td><span class='folder'>" . $fila['nombre'] . "</td>"
                        . "<td>" . $fila['tipo'] . "</td>"
                        . "<td>" . $fila['estado'] . "</td>"
                        . "<td>" . $fila['nivel'] . "</td>"
                        . "<td>" . $fila['orden'] . "</td>"
                        . "<td>" . '<button class="bntGrid fas fa-plus" onclick="fn_abrirVistaAgregar(' . $fila['id_servicio'] . ')"/>' . "</td>"
                        . "<td>" . $campoCopiar . "</td>"
                        . "</tr>";
                $html.=self::tablaHtmlServiciosArbol($modeloServicio, $vista);
            } else
            { //no hay hijos
                $html.="<tr data-tt-id='" . $fila['id_laboratorio'] . "-" . $fila['id_servicio'] . "' data-tt-parent-id='" . $fila['id_laboratorio'] . "-" . $fila['fk_id_servicio'] . "'"
                        . 'id="' . $fila['id_servicio'] . '" class="item"'
                        . "data-rutaAplicacion='" . URL_MVC_FOLDER . "Laboratorios/servicios'"
                        . 'data-opcion="editar' . $vista . '"'
                        . 'data-destino="detalleItem">'
                        . "<td><span class='file'>" . $fila['nombre'] . "</td>"
                        . "<td>" . $fila['tipo'] . "</td>"
                        . "<td>" . $fila['estado'] . "</td>"
                        . "<td>" . $fila['nivel'] . "</td>"
                        . "<td>" . $fila['orden'] . "</td>"
                        . "<td>" . '<button class="bntGrid fas fa-plus" onclick="fn_abrirVistaAgregar(' . $fila['id_servicio'] . ')"/>' . "</td>"
                        . "<td>" . $campoCopiar . "</td>"
                        . "</tr>";
            }
        }
        return $html;
    }

    /**
     * Método para buscar los datos del servicio tipo arbol segun el laboratorio
     */
    public function buscarServiciosPadre($idLaboratorio)
    {
        $modeloServicios = $this->lNegocioServicios->buscarLista(" id_laboratorio = $idLaboratorio and fk_id_servicio IS null");
        $arbol = $this->arbol($modeloServicios);
        array_push($arbol, array("id" => "0", "text" => "NINGUNO"));
        echo json_encode($arbol);
    }

    /**
     * Retornar los servicios tipo árbol
     * @param type $tabla
     * @return type
     */
    public function arbol($tabla)
    {
        $array = array();
        foreach ($tabla as $fila)
        {
            $idServicio = $fila['id_servicio'];
            // buscar los registro que tengan el id_padre
            $modeloServicios = $this->lNegocioServicios->buscarIdPadre($idServicio);
            if (count($modeloServicios) > 0)
            { // hay hijos
                $array[] = array("id" => $fila['id_servicio'], "text" => $fila['nombre'], "children" => self::arbol($modeloServicios));
            } else
            { //no hay hijos
                $array[] = array("id" => $fila['id_servicio'], "text" => $fila['nombre']);
            }
        }
        return $array;
    }

    /**
     * Método para buscar los datos del servicio tipo arbol segun el laboratorio
     */
    public function buscarServicio($idServicio)
    {
        $modeloServicio = new ServiciosModelo();
        $modeloServicio = $this->lNegocioServicios->buscar($idServicio);
        echo json_encode(array("nivel" => $modeloServicio->getNivel()));
    }

    /**
     * Funcion para editar el id padre desde la grilla
     */
    public function editarDnD()
    {
        $idServicio = explode('-', $_POST['idServicio']);
        $fkIdServicio = explode('-', $_POST['fkIdServicio']);
        $datos = array('id_servicio' => $idServicio[1], 'fk_id_servicio' => $fkIdServicio[1]);
        $lNegocioServicio = new ServiciosLogicaNegocio();
        $lNegocioServicio->guardar($datos);
    }

}
