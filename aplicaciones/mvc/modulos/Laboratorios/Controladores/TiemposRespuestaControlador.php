<?php

/**
 * Controlador TiemposRespuesta
 *
 * Este archivo controla la lógica del negocio del modelo:  TiemposRespuestaModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     TiemposRespuestaControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Laboratorios\Modelos\TiemposRespuestaLogicaNegocio;
use Agrodb\Laboratorios\Modelos\TiemposRespuestaModelo;
use Agrodb\Laboratorios\Modelos\ServiciosLogicaNegocio;
use Agrodb\Laboratorios\Modelos\ServiciosModelo;
use Agrodb\Laboratorios\Modelos\LaboratoriosModelo;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class TiemposRespuestaControlador extends BaseControlador
{

    private $lNegocioServicios = null;
    private $lNegocioTiemposRespuesta = null;
    private $modeloTiemposRespuesta = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioTiemposRespuesta = new TiemposRespuestaLogicaNegocio();
        $this->modeloTiemposRespuesta = new TiemposRespuestaModelo();
        $this->modeloServicios = new ServiciosModelo();
        $this->modeloLaboratorios = new LaboratoriosModelo();
        $this->lNegocioServicios = new ServiciosLogicaNegocio();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        require APP . 'Laboratorios/vistas/listaTiemposRespuestaVista.php';
    }

    /**
     * Búsqueda por filtro
     */
    public function listarDatos()
    {
        $arrayParametros = array('idDireccion' => $_POST['fDireccion'], 'idLaboratorio' => $_POST['fLaboratorio'], 'idServicio' => $_POST['fServicio'],'id_laboratorios_provincia'=>$_POST['fLaboratorios_provincia']);
        $modeloTiemposRespuesta = $this->lNegocioTiemposRespuesta->buscarListaTiempos($arrayParametros);
        $this->tablaHtmlTiemposRespuesta($modeloTiemposRespuesta);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Tiempos Respuesta";
        require APP . 'Laboratorios/vistas/formularioTiemposRespuestaVista.php';
    }

    /**
     * Método para registrar en la base de datos -TiemposRespuesta
     */
    public function guardar()
    {
        $this->lNegocioTiemposRespuesta->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: TiemposRespuesta
     */
    public function editar()
    {
        $this->accion = "Editar Tiempos Respuesta";
        $this->modeloTiemposRespuesta = $this->lNegocioTiemposRespuesta->buscar($_POST["id"]);
        require APP . 'Laboratorios/vistas/formularioTiemposRespuestaVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - TiemposRespuesta
     */
    public function borrar()
    {
        $this->lNegocioTiemposRespuesta->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - TiemposRespuesta
     */
    public function tablaHtmlTiemposRespuesta($tabla)
    {
        $contador = 0;
        if (count($tabla) > 0)
        {
            foreach ($tabla as $fila)
            {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_tiempos_respuesta'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/TiemposRespuesta"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		   <td>' . $fila->nombre_laboratorio . '</td>
                  <td>' . $fila->rama_nombre . '</td>
                  <td>' . $fila->condicion . '</td>
                  <td>' . $fila->tiempo_respuesta . '</td>
                  <td >' . $fila->tipo_usuario . '</td>
                  <td >' . $fila->tipo_laboratorio . '</td>
                  </tr>');
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
        }
    }

    /**
     * Método para buscar los datos del servicio tipo arbol segun el laboratorio
     */
    public function buscarServiciosPadre($idLaboratorio)
    {
        $modeloServicios = $this->lNegocioServicios->buscarLista(" id_laboratorio = $idLaboratorio and fk_id_servicio IS null ORDER BY nombre");
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

}
