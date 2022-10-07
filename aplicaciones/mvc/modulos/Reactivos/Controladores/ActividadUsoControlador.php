<?php

/**
 * Controlador ActividadUso
 *
 * Este archivo controla la lógica del negocio del modelo:  ActividadUsoModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     ActividadUsoControlador
 * @package Reactivos
 * @subpackage Controladores
 */

namespace Agrodb\Reactivos\Controladores;

use Agrodb\Reactivos\Modelos\ActividadUsoLogicaNegocio;
use Agrodb\Reactivos\Modelos\ActividadUsoModelo;
use Agrodb\Reactivos\Modelos\ReactivosBodegaLogicaNegocio;
use Agrodb\Reactivos\Modelos\ReactivosLaboratoriosLogicaNegocio;
use Agrodb\Laboratorios\Modelos\ServiciosLogicaNegocio;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class ActividadUsoControlador extends BaseControlador
{

    private $lNegocioActividadUso = null;
    private $modeloActividadUso = null;
    private $accion = null;
    private $lNegocioServicios = null;
    private $lNegocioReactivosBodega = null;
    private $lNegocioReactivosLaboratorios = null;
    private $servicios;
    private $listaActividadUso;

    /**
     * Constructor
     */
    function __construct()
    {
        $this->lNegocioServicios = new ServiciosLogicaNegocio();
        $this->lNegocioActividadUso = new ActividadUsoLogicaNegocio();
        $this->lNegocioReactivosBodega = new ReactivosBodegaLogicaNegocio();
        $this->lNegocioReactivosLaboratorios = new ReactivosLaboratoriosLogicaNegocio();
        $this->modeloActividadUso = new ActividadUsoModelo();
        parent::__construct();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        // buscar los servicios nivel 0 del laboratorio correspondiente al usuario
        $this->servicios = $this->lNegocioServicios->buscarLista(array('nivel' => 0));
        require APP . 'Reactivos/vistas/listaActividadUsoVista.php';
    }

    /**
     * Búsqueda por filtro
     */
    public function listarDatos()
    {
        $arrayParametros = array(
            'id_laboratorio' => $_POST['idLaboratorio'],
            'nivel' => 0,
            'estado' => 'ACTIVO');
        //mostrar todos los servicios que le correspende al laboratorio del usuario
        $buscaServicios = $this->lNegocioServicios->buscarLista($arrayParametros);

        $html = $this->tablaHtmlServiciosArbol($buscaServicios, $_POST['idLaboratoriosProvincia'], $_POST['idLaboratorio']);
        echo $html;
    }

    /**
     * Construye la tabla html tipo álbol del servicio
     * @param type $tabla
     * @param type $vista
     * @return string
     */
    public function tablaHtmlServiciosArbol($tabla, $idLaboratoriosProvincia, $idLaboratorio)
    {
        $html = "";
        foreach ($tabla as $fila)
        {
            $campoCopiar = "";

            // buscar los registro que tengan el id_padre
            $modeloServicio = $this->lNegocioServicios->buscarLista(array('fk_id_servicio' => $fila->id_servicio, 'estado' => 'ACTIVO'));
            $id = "$fila->id_servicio-$idLaboratoriosProvincia-$idLaboratorio";
            if (count($modeloServicio) > 0)
            { // hay hijos
                $html.="<tr data-tt-id='" . $fila->id_laboratorio . "-" . $fila->id_servicio . "' data-tt-parent-id='" . $fila->id_laboratorio . "-" . $fila->fk_id_servicio . "'"
                        . 'id="' . $id . '" class="item"'
                        . "data-rutaAplicacion='" . URL_MVC_FOLDER . "Reactivos/ActividadUso'"
                        . 'data-opcion="listarProcedimientoAnalisis"'
                        . 'data-destino="detalleItem">'
                        . "<td><span class='folder'>" . $fila->nombre . "</td>"
                        . "<td>" . $fila->tipo . "</td>"
                        . "<td>" . $fila->estado . "</td>"
                        . "<td>" . "<button class='bntGrid fas fa-plus' onclick='fn_abrirVistaAgregar(\"" . $id . "\")'/>" . "</td>"
                        . "<td>" . $campoCopiar . "</td>"
                        . "</tr>";
                $html.=self::tablaHtmlServiciosArbol($modeloServicio, $idLaboratorio, $idLaboratoriosProvincia);
            } else
            { //no hay hijos
                $html.="<tr data-tt-id='" . $fila->id_laboratorio . "-" . $fila->id_servicio . "' data-tt-parent-id='" . $fila->id_laboratorio . "-" . $fila->fk_id_servicio . "'"
                        . 'id="' . $id . '" class="item"'
                        . "data-rutaAplicacion='" . URL_MVC_FOLDER . "Reactivos/ActividadUso'"
                        . 'data-opcion="listarProcedimientoAnalisis"'
                        . 'data-destino="detalleItem">'
                        . "<td><span class='file'>" . $fila->nombre . "</td>"
                        . "<td>" . $fila->tipo . "</td>"
                        . "<td>" . $fila->estado . "</td>"
                        . "<td>" . "<button class='bntGrid fas fa-plus' onclick='fn_abrirVistaAgregar(\"" . $id . "\")'/>" . "</td>"
                        . "<td>" . $campoCopiar . "</td>"
                        . "</tr>";
            }
        }
        return $html;
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Procedimiento";
        require APP . 'Reactivos/vistas/formularioActividadUsoVista.php';
    }

    /**
     * Forma los datos de reactivo bodega en formato json para el formulario
     */
    public function buscarReactivosBodega($idLaboratoriosProvincia)
    {
        $buscaReactivosBodega = $this->lNegocioReactivosBodega->buscarReactivosPorLaboratorioProvincia($idLaboratoriosProvincia);
        $array = array();
        foreach ($buscaReactivosBodega as $fila)
        {
            $array[] = array("id" => $fila->id_reactivo_bodega, "text" => $fila->nombre);
        }
        echo json_encode($array);
    }

    /**
     * Forma los datos de reactivo laboratorio en formato json para el formulario
     */
    public function buscarReactivosLaboratorio($idLaboratoriosProvincia)
    {
        $lNReactivosLaboratorios = new ReactivosLaboratoriosLogicaNegocio();
        $arrayParametros = array('id_laboratorios_provincia' => $idLaboratoriosProvincia);
        $buscaReactivosLaboratorio = $lNReactivosLaboratorios->buscarLista($arrayParametros);
        $array = array();
        foreach ($buscaReactivosLaboratorio as $fila)
        {
            $array[] = array("id" => $fila->id_reactivo_laboratorio, "text" => $fila->nombre);
        }
        echo json_encode($array);
    }

    /**
     * Método para registrar en la base de datos -ActividadUso
     */
    public function guardar()
    {
        $this->lNegocioActividadUso->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: ActividadUso
     */
    public function listarProcedimientoAnalisis()
    {
        $this->accion = "Procedimiento para el An&aacute;lisis";
        //lista de reactivos para el procedimiento especifico de ensayo

        //id_servicio  + id_lp
        
        $id = explode('-', $_POST['id']);
        $idServicio = $id[0];
        $idLaboratoriosProvincia = $id[1];

        //setear el laboratorio para que se asigne por defecto 
        $this->modeloActividadUso->setIdServicio($idServicio);
        $this->modeloActividadUso->setIdLaboratoriosProvincia($idLaboratoriosProvincia);

        $this->tablaHtmlActividadUso($_POST['id']);
        require APP . 'Reactivos/vistas/formularioActividadUsoVista.php';
    }

    
    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: ActividadUso
     */
    public function editar()
    {
        $this->accion = "Procedimiento para el An&aacute;lisis";
        $idActividadUso = explode('-', $_POST['id']);
        $idActividadUso = $idActividadUso[3];
        $this->modeloActividadUso = $this->lNegocioActividadUso->buscar($idActividadUso);
        $this->tablaHtmlActividadUso($_POST['id']);
        require APP . 'Reactivos/vistas/formularioActividadUsoVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - ActividadUso
     */
    public function borrar()
    {
        $this->lNegocioActividadUso->borrar($_POST['elementos']);
    }

    /**
     * Método para buscar los datos del servicio tipo arbol segun el laboratorio
     */
    public function buscarServiciosPadre($idLaboratorio)
    {
        $modeloServicios = $this->lNegocioServicios->buscarLista(" id_laboratorio = $idLaboratorio and fk_id_servicio IS null and estado = 'ACTIVO'");
        $arbol = $this->arbol($modeloServicios);
        array_push($arbol, array("id" => "0", "text" => "NINGUNO"));
        echo json_encode($arbol);
    }

    /**
     * Construye array tipo árbol para los servicios
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
    public function obtenerDatosReactivoBodega($idReactivoBodega)
    {
        $buscaReactivoBodega = $this->lNegocioReactivosBodega->buscar($idReactivoBodega);
        echo json_encode(array("unidad" => $buscaReactivoBodega->getUnidad()));
    }

    /**
     * Método para buscar los datos del servicio tipo arbol segun el 
     */
    public function obtenerDatosReactivosLaboratorio($idReactivoLaboratorio)
    {
        $buscaReactivoLaboratorio = $this->lNegocioReactivosLaboratorios->buscar($idReactivoLaboratorio);
        echo json_encode(array("unidad" => $buscaReactivoLaboratorio->getUnidadMedida()));
    }

    /**
     * Construye el código HTML para desplegar la lista de - ActividadUso
     */
    public function tablaHtmlActividadUso($id)
    {
        $ids = explode('-', $id);
        $idServicio = $ids[0];
        $idLaboratoriosProvincia = $ids[1];
        $idLaboratorio = $ids[2];
        
        $buscaActividadUso = $this->lNegocioActividadUso->buscarReactivosActividadUso($idServicio, $idLaboratoriosProvincia);
        $html = "";
        $contador = 0;
        if (count($buscaActividadUso) > 0)
        {
            foreach ($buscaActividadUso as $fila)
            {
                $idx = "$idServicio-$idLaboratoriosProvincia-$idLaboratorio-$fila->id_actividad_uso";
                $html.="<tr data-tt-id='" . $idServicio . "-" . $fila->id_actividad_uso . "' data-tt-parent-id='servicio-" . $idServicio . "'"
                        . 'id="' . $idx . '" class="item"'
                        . "data-rutaAplicacion='" . URL_MVC_FOLDER . "Reactivos/ActividadUso'"
                        . 'data-opcion="editar"'
                        . 'data-destino="detalleItem">'
                        . "<td>" . ++$contador . "</td>"
                        . "<td>" . $fila->nombre . "</td>"
                        . "<td>" . $fila->cantidad . "</td>"
                        . "<td>" . $fila->unidad_medida . "</td>"
                        . "<td>" . $fila->estado . "</td>"
                        . "<td>" . $fila->tipo_procedimiento . "</td>"
                        . "<td>" . $fila->observaciones . "</td>"
                        . "</tr>";
            }
        } else
        {
            $html = "<tr colspan='5'><td>No tiene asignado los reactivos</td></tr>";
        }
        $this->listaActividadUso = $html;
    }

}
